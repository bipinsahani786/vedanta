<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployerProfile;
use App\Models\JobPost;
use App\Models\User;
use App\Notifications\EmployerAgreementReviewed;
use App\Services\EmployerAgreementService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class SchoolCrmController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'employer');

        if ($search = trim((string) $request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('employerProfile', function ($ep) use ($search) {
                        $ep->where('school_name', 'like', "%{$search}%")
                            ->orWhere('contact_person', 'like', "%{$search}%")
                            ->orWhere('address', 'like', "%{$search}%");
                    });

                $cleanId = ltrim($search, '#');
                if (is_numeric($cleanId)) {
                    $q->orWhere('users.id', (int) $cleanId);
                }
            });
        }

        $baseQuery = clone $query;
        $hasAgreementCol = Schema::hasColumn('employer_profiles', 'agreement_status');

        $withProfileDetails = function ($q) {
            $q->whereNotNull('school_name')->where('school_name', '!=', '')
                ->whereNotNull('contact_person')->where('contact_person', '!=', '')
                ->whereNotNull('address')->where('address', '!=', '')
                ->whereNotNull('logo_path')->where('logo_path', '!=', '');
        };

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->where('is_active', true)->count(),
            'inactive' => (clone $baseQuery)->where('is_active', false)->count(),
            'profile_complete' => (clone $baseQuery)->whereHas('employerProfile', $withProfileDetails)->count(),
            'profile_incomplete' => (clone $baseQuery)->whereDoesntHave('employerProfile', $withProfileDetails)->count(),
            'with_jobs' => (clone $baseQuery)->whereHas('jobPosts')->count(),
            'agreement_pending' => $hasAgreementCol
                ? (clone $baseQuery)->whereHas('employerProfile', fn ($q) => $q->where('agreement_status', 'pending'))->count()
                : 0,
            'agreement_approved' => $hasAgreementCol
                ? (clone $baseQuery)->whereHas('employerProfile', fn ($q) => $q->where('agreement_status', 'approved'))->count()
                : 0,
            'agreement_rejected' => $hasAgreementCol
                ? (clone $baseQuery)->whereHas('employerProfile', fn ($q) => $q->where('agreement_status', 'rejected'))->count()
                : 0,
            'agreement_not_uploaded' => $hasAgreementCol
                ? (clone $baseQuery)->where(function ($q) {
                    $q->whereDoesntHave('employerProfile')
                        ->orWhereHas('employerProfile', fn ($ep) => $ep->whereNull('agreement_path')->orWhere('agreement_status', 'not_uploaded'));
                })->count()
                : (clone $baseQuery)->count(),
        ];

        $status = (string) $request->input('status', '');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        } elseif ($status === 'profile_complete') {
            $query->whereHas('employerProfile', $withProfileDetails);
        } elseif ($status === 'profile_incomplete') {
            $query->whereDoesntHave('employerProfile', $withProfileDetails);
        } elseif ($status === 'with_jobs') {
            $query->whereHas('jobPosts');
        } elseif ($hasAgreementCol && $status === 'agreement_pending') {
            $query->whereHas('employerProfile', fn ($q) => $q->where('agreement_status', 'pending'));
        } elseif ($hasAgreementCol && $status === 'agreement_approved') {
            $query->whereHas('employerProfile', fn ($q) => $q->where('agreement_status', 'approved'));
        } elseif ($hasAgreementCol && $status === 'agreement_rejected') {
            $query->whereHas('employerProfile', fn ($q) => $q->where('agreement_status', 'rejected'));
        } elseif ($hasAgreementCol && $status === 'agreement_not_uploaded') {
            $query->where(function ($q) {
                $q->whereDoesntHave('employerProfile')
                    ->orWhereHas('employerProfile', fn ($ep) => $ep->whereNull('agreement_path')->orWhere('agreement_status', 'not_uploaded'));
            });
        }

        $schools = $query->with(['employerProfile'])
            ->withCount([
                'jobPosts',
                'jobPosts as live_jobs_count' => fn ($q) => $q->where('status', 'approved'),
            ])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.schools.index', compact('schools', 'stats'));
    }

    public function create()
    {
        return view('admin.schools.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'nullable|string|min:8|max:64',
            'address' => 'required|string|max:500',
            'about' => 'nullable|string|max:5000',
            'is_active' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        try {
            $result = self::createSchoolAccount(
                array_merge($validated, ['is_active' => $request->boolean('is_active', true)]),
                $request->file('logo')
            );

            if ($result['temp_password'] !== null) {
                session()->flash('school_temp_password', $result['temp_password']);
            }

            return redirect()
                ->route('admin.schools.show', $result['user']->id)
                ->with('success', 'School account created successfully: ' . $validated['school_name']);
        } catch (Throwable $e) {
            Log::error('Admin school creation failed', ['error' => $e->getMessage()]);

            return back()->withInput()->withErrors([
                'error' => 'Failed to create school account: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Inline AJAX endpoint called from the Job Post form to add and immediately select a new school.
     */
    public function quickStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'address' => 'required|string|max:500',
            'about' => 'nullable|string|max:5000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        $validated = $validator->validated();

        try {
            $result = self::createSchoolAccount(
                array_merge($validated, ['is_active' => true]),
                $request->file('logo')
            );

            $user = $result['user'];
            $profile = $result['profile'];

            return response()->json([
                'success' => true,
                'message' => 'School added to School CRM and selected for this job post.',
                'school' => self::formatSchoolPayload($user, $profile),
            ], 201);
        } catch (Throwable $e) {
            Log::error('Admin quick school creation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to save school: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Shared factory for creating an employer user + EmployerProfile cleanly in a transaction.
     *
     * @return array{user: User, profile: EmployerProfile, temp_password: ?string}
     */
    public static function createSchoolAccount(array $data, ?UploadedFile $logoFile = null): array
    {
        $logoPath = $logoFile ? $logoFile->store('employer_logos', 'public') : ($data['logo_path'] ?? null);

        $rawPassword = !empty($data['password']) ? (string) $data['password'] : null;
        $autoGeneratedPassword = null;
        if ($rawPassword === null) {
            $autoGeneratedPassword = 'Vpa@' . random_int(1000, 9999) . Str::lower(Str::random(4));
            $rawPassword = $autoGeneratedPassword;
        }

        try {
            return DB::transaction(function () use ($data, $rawPassword, $autoGeneratedPassword, $logoPath) {
                $accountName = trim((string) ($data['name'] ?? '')) ?: trim((string) $data['contact_person']);

                $user = new User([
                    'name' => $accountName,
                    'email' => strtolower(trim((string) $data['email'])),
                    'phone' => trim((string) $data['phone']),
                    'role' => 'employer',
                    'password' => Hash::make($rawPassword),
                ]);
                $user->email_verified_at = now();
                $user->is_active = array_key_exists('is_active', $data) ? (bool) $data['is_active'] : true;
                $user->save();

                $profile = EmployerProfile::create([
                    'user_id' => $user->id,
                    'school_name' => trim((string) $data['school_name']),
                    'contact_person' => trim((string) $data['contact_person']),
                    'address' => trim((string) ($data['address'] ?? '')),
                    'about' => !empty($data['about']) ? trim((string) $data['about']) : null,
                    'logo_path' => $logoPath,
                ]);

                return [
                    'user' => $user,
                    'profile' => $profile,
                    'temp_password' => $autoGeneratedPassword,
                ];
            });
        } catch (Throwable $e) {
            if ($logoFile && $logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            throw $e;
        }
    }

    public static function formatSchoolPayload(User $user, ?EmployerProfile $profile = null): array
    {
        $ep = $profile ?? $user->employerProfile;
        $logoPath = $ep?->logo_path;

        return [
            'id' => $user->id,
            'school_name' => $ep?->school_name ?: $user->name,
            'contact_person' => $ep?->contact_person ?: $user->name,
            'email' => $user->email ?? '',
            'phone' => $user->phone ?? '',
            'address' => $ep?->address ?? '',
            'logo_path' => $logoPath ?? '',
            'logo_url' => $logoPath ? Storage::url($logoPath) : '',
        ];
    }

    public function show($id)
    {
        $school = User::where('role', 'employer')
            ->with([
                'employerProfile.reviewer',
                'jobPosts' => function ($q) {
                    $q->with(['category', 'subject', 'state', 'city'])
                        ->withCount('applications')
                        ->latest();
                },
            ])
            ->findOrFail($id);

        $profile = $school->employerProfile;
        $jobSummary = JobPost::statusSummary($school->id);

        return view('admin.schools.show', compact('school', 'profile', 'jobSummary'));
    }

    public function edit($id)
    {
        $school = User::where('role', 'employer')->with('employerProfile')->findOrFail($id);
        $profile = $school->employerProfile ?? new EmployerProfile(['school_name' => '', 'contact_person' => '']);

        return view('admin.schools.edit', compact('school', 'profile'));
    }

    public function update(Request $request, $id)
    {
        $school = User::where('role', 'employer')->with('employerProfile')->findOrFail($id);

        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $school->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $school->id,
            'password' => 'nullable|string|min:8|max:64',
            'address' => 'required|string|max:500',
            'about' => 'nullable|string|max:5000',
            'is_active' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'remove_logo' => 'nullable|boolean',
        ]);

        $newLogoPath = null;
        if ($request->hasFile('logo')) {
            $newLogoPath = $request->file('logo')->store('employer_logos', 'public');
        }

        try {
            $oldLogoToDelete = null;

            DB::transaction(function () use ($school, $validated, $request, $newLogoPath, &$oldLogoToDelete) {
                $school->name = trim($validated['name']);
                $school->email = strtolower(trim($validated['email']));
                $school->phone = trim($validated['phone']);
                $school->is_active = $request->boolean('is_active', true);

                if (!empty($validated['password'])) {
                    $school->password = Hash::make($validated['password']);
                }
                $school->save();

                $profile = $school->employerProfile ?: new EmployerProfile(['user_id' => $school->id]);
                $profile->school_name = trim($validated['school_name']);
                $profile->contact_person = trim($validated['contact_person']);
                $profile->address = trim($validated['address']);
                $profile->about = !empty($validated['about']) ? trim($validated['about']) : null;

                if ($newLogoPath !== null) {
                    $oldLogoToDelete = $profile->logo_path;
                    $profile->logo_path = $newLogoPath;
                } elseif ($request->boolean('remove_logo') && !empty($profile->logo_path)) {
                    $oldLogoToDelete = $profile->logo_path;
                    $profile->logo_path = null;
                }

                $profile->save();
            });

            if ($oldLogoToDelete && $oldLogoToDelete !== $newLogoPath && Storage::disk('public')->exists($oldLogoToDelete)) {
                Storage::disk('public')->delete($oldLogoToDelete);
            }

            return redirect()
                ->route('admin.schools.show', $school->id)
                ->with('success', 'School profile & account updated successfully.');
        } catch (Throwable $e) {
            if ($newLogoPath && Storage::disk('public')->exists($newLogoPath)) {
                Storage::disk('public')->delete($newLogoPath);
            }

            Log::error('Admin school update failed', [
                'school_id' => $school->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->withErrors([
                'error' => 'Failed to update school details: ' . $e->getMessage(),
            ]);
        }
    }

    public function updatePhoto(Request $request, $id)
    {
        $school = User::where('role', 'employer')->with('employerProfile')->findOrFail($id);

        $fileKey = $request->hasFile('logo') ? 'logo' : 'profile_photo';
        $request->validate([
            $fileKey => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            "{$fileKey}.required" => 'Please select a valid image file.',
        ]);

        $newPath = $request->file($fileKey)->store('employer_logos', 'public');
        $profile = $school->employerProfile ?: $school->employerProfile()->create([
            'school_name' => $school->name,
            'contact_person' => $school->name,
        ]);

        $oldPath = $profile->logo_path;
        $profile->update(['logo_path' => $newPath]);

        if ($oldPath && $oldPath !== $newPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return back()->with('success', 'School logo / photo updated successfully.');
    }

    public function toggleStatus($id)
    {
        $school = User::where('role', 'employer')->findOrFail($id);
        $school->is_active = !$school->is_active;
        $school->save();

        $stateLabel = $school->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "School account ({$school->name}) has been {$stateLabel}.");
    }

    public function downloadAgreementTemplate($id, EmployerAgreementService $agreementService)
    {
        $school = User::where('role', 'employer')->with('employerProfile')->findOrFail($id);
        $profile = $school->employerProfile ?: $school->employerProfile()->create([
            'school_name' => $school->name,
            'contact_person' => $school->name,
        ]);

        return $agreementService->buildTemplatePdfResponse($school, $profile);
    }

    public function viewUploadedAgreement(Request $request, $id, EmployerAgreementService $agreementService)
    {
        $school = User::where('role', 'employer')->with('employerProfile')->findOrFail($id);
        $profile = $school->employerProfile;

        if (! $profile || empty($profile->agreement_path) || ! Storage::disk('public')->exists($profile->agreement_path)) {
            return back()->with('error', 'No uploaded agreement file was found for this school.');
        }

        $disposition = $request->boolean('download') ? 'attachment' : 'inline';

        return $agreementService->streamUploadedAgreement($profile, $disposition);
    }

    public function approveAgreement($id)
    {
        if (! Schema::hasColumn('employer_profiles', 'agreement_status')) {
            return back()->with('error', 'Agreement workflow columns are not migrated yet. Please run php artisan migrate --force.');
        }

        $school = User::where('role', 'employer')->with('employerProfile')->findOrFail($id);
        $profile = $school->employerProfile;

        if (! $profile || empty($profile->agreement_path) || ! Storage::disk('public')->exists($profile->agreement_path)) {
            return back()->with('error', 'This school has not uploaded a signed agreement yet.');
        }

        $profile->update([
            'agreement_status' => 'approved',
            'agreement_reviewed_at' => now(),
            'agreement_reviewed_by' => auth()->id(),
            'agreement_rejection_reason' => null,
        ]);

        try {
            $school->notify(new EmployerAgreementReviewed($profile->fresh(), 'approved'));
        } catch (Throwable $e) {
            Log::warning('Failed to notify school on agreement approval', [
                'school_id' => $school->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect(route('admin.schools.show', $school->id) . '#agreement-section')
            ->with('success', 'School agreement approved successfully.');
    }

    public function rejectAgreement(Request $request, $id)
    {
        if (! Schema::hasColumn('employer_profiles', 'agreement_status')) {
            return back()->with('error', 'Agreement workflow columns are not migrated yet. Please run php artisan migrate --force.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ], [
            'rejection_reason.required' => 'Please enter a reason before rejecting the agreement.',
        ]);

        $school = User::where('role', 'employer')->with('employerProfile')->findOrFail($id);
        $profile = $school->employerProfile;

        if (! $profile || empty($profile->agreement_path) || ! Storage::disk('public')->exists($profile->agreement_path)) {
            return back()->with('error', 'No uploaded agreement file was found for this school.');
        }

        $reason = trim($validated['rejection_reason']);

        $profile->update([
            'agreement_status' => 'rejected',
            'agreement_reviewed_at' => now(),
            'agreement_reviewed_by' => auth()->id(),
            'agreement_rejection_reason' => $reason,
        ]);

        try {
            $school->notify(new EmployerAgreementReviewed($profile->fresh(), 'rejected', $reason));
        } catch (Throwable $e) {
            Log::warning('Failed to notify school on agreement rejection', [
                'school_id' => $school->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect(route('admin.schools.show', $school->id) . '#agreement-section')
            ->with('success', 'School agreement rejected and school notified with reason.');
    }
}
