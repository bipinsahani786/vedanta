<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CandidateProfile;
use App\Models\EmployerProfile;
use App\Models\User;
use App\Services\SpreadsheetExportService;
use App\Services\SpreadsheetImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class UserController extends Controller
{
    protected function buildFilteredUsersQuery(Request $request, bool $applyRoleAndStatus = true)
    {
        $query = User::whereIn('role', ['candidate', 'employer']);

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($applyRoleAndStatus) {
            if ($request->filled('role') && in_array($request->role, ['candidate', 'employer'], true)) {
                $query->where('role', $request->role);
            }

            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        return $query;
    }

    public function index(Request $request)
    {
        $baseQuery = $this->buildFilteredUsersQuery($request, false);
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'candidates' => (clone $baseQuery)->where('role', 'candidate')->count(),
            'employers' => (clone $baseQuery)->where('role', 'employer')->count(),
            'active' => (clone $baseQuery)->where('is_active', true)->count(),
            'inactive' => (clone $baseQuery)->where('is_active', false)->count(),
        ];

        $users = $this->buildFilteredUsersQuery($request, true)
            ->with('profile')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function export(Request $request, SpreadsheetExportService $exporter)
    {
        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (!in_array($format, ['xlsx', 'csv'], true)) {
            $format = 'xlsx';
        }

        $query = $this->buildFilteredUsersQuery($request, true)
            ->with(['profile.category', 'profile.subject', 'employerProfile']);

        $headers = [
            'User ID',
            'Name',
            'Email',
            'Phone',
            'Role',
            'Account Status',
            'Email Verified',
            'VPA ID / School Name',
            'Category & Subject / Contact Person',
            'Onboarding / Verification Status',
            'Plan / Paid Amount',
            'Referral Code',
            'Joined Date (IST)',
        ];

        $rows = [];
        $query->chunkById(500, function ($chunk) use (&$rows): void {
            foreach ($chunk as $user) {
                $profile = $user->profile;
                $employer = $user->employerProfile;

                $vpaOrSchool = $user->role === 'candidate'
                    ? ($profile?->vpa_id ?? '')
                    : ($employer?->school_name ?? '');

                $categoryOrContact = $user->role === 'candidate'
                    ? trim(implode(' - ', array_filter([
                        $profile?->category?->name,
                        $profile?->subject?->name,
                    ])))
                    : ($employer?->contact_person ?? '');

                if ($user->role === 'candidate') {
                    if ($profile) {
                        $statusText = $profile->isRegistrationCompleted() ? 'Completed' : $profile->pending_reason;
                        if ($profile->is_verified) {
                            $statusText .= ' (Verified)';
                        }
                    } else {
                        $statusText = 'Profile Pending';
                    }
                } else {
                    $statusText = 'Employer Account';
                }

                $planInfo = $user->role === 'candidate' && $profile
                    ? (($profile->plan_type ? ucfirst($profile->plan_type) : 'None') . ' / Rs. ' . number_format((float) ($profile->paid_amount ?? 0), 2))
                    : '';

                $rows[] = [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone ?? '',
                    ucfirst((string) $user->role),
                    $user->is_active ? 'Active' : 'Inactive',
                    $user->email_verified_at ? 'Yes' : 'No',
                    $vpaOrSchool,
                    $categoryOrContact,
                    $statusText,
                    $planInfo,
                    $user->referral_code ?? '',
                    $user->created_at ? $user->created_at->copy()->timezone('Asia/Kolkata')->format('d M Y, h:i A') . ' IST' : '',
                ];
            }
        }, 'users.id', 'id');

        usort($rows, static fn (array $a, array $b): int => ((int) $b[0]) <=> ((int) $a[0]));

        $roleSlug = $request->filled('role') && in_array($request->role, ['candidate', 'employer'], true)
            ? $request->role
            : 'all';
        $fileBaseName = 'vedanta_users_' . $roleSlug . '_' . now('Asia/Kolkata')->format('Ymd_His');

        return $format === 'csv'
            ? $exporter->downloadCsv($fileBaseName, $headers, $rows)
            : $exporter->downloadXlsx($fileBaseName, $headers, $rows, 'Vedanta Users');
    }

    public function downloadImportTemplate(Request $request, SpreadsheetExportService $exporter)
    {
        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (!in_array($format, ['xlsx', 'csv'], true)) {
            $format = 'xlsx';
        }

        $headers = [
            'Name',
            'Email',
            'Phone',
            'Role',
            'Temporary Password',
            'Account Status',
            'VPA ID / School Name',
            'Contact Person',
            'Address',
        ];

        $sampleRows = [
            [
                'Anita Verma',
                'anita.verma@example.com',
                '9876543210',
                'candidate',
                '',
                'active',
                '',
                '',
                'Boring Road, Patna, Bihar',
            ],
            [
                'St. Xavier High School',
                'hr.stxavier@example.com',
                '9811223344',
                'employer',
                'TempPass@2026',
                'active',
                'St. Xavier High School',
                'Rajesh Sinha',
                'Kankarbagh, Patna, Bihar',
            ],
        ];

        return $format === 'csv'
            ? $exporter->downloadCsv('vedanta_users_import_template', $headers, $sampleRows)
            : $exporter->downloadXlsx('vedanta_users_import_template', $headers, $sampleRows, 'Users Import Template');
    }

    public function import(Request $request, SpreadsheetImportService $importer)
    {
        $request->validate([
            'import_file' => 'required|file|extensions:xlsx,csv,txt|max:10240',
            'duplicate_mode' => 'nullable|in:skip,update',
        ], [
            'import_file.required' => 'Please select an Excel (.xlsx) or CSV (.csv) file to import.',
            'import_file.extensions' => 'Only .xlsx and .csv files are supported.',
        ]);

        $duplicateMode = $request->input('duplicate_mode', 'skip');
        $file = $request->file('import_file');
        $extension = $file->getClientOriginalExtension() ?: 'csv';

        try {
            $parsed = $importer->parse($file->getRealPath(), $extension);
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        $rows = $parsed['rows'];
        if (empty($rows)) {
            return back()->with('warning', 'The uploaded file only contains headers with no user rows to import.');
        }

        if (count($rows) > 5000) {
            return back()->with('error', 'Please split imports into files of 5,000 rows or fewer.');
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $failed = 0;
        $issues = [];

        $recordIssue = static function (string $msg) use (&$issues): void {
            if (count($issues) < 30) {
                $issues[] = $msg;
            }
        };

        foreach ($rows as $rowEntry) {
            $rowNum = $rowEntry['row_number'];
            $data = $rowEntry['data'];

            $name = trim((string) ($data['name'] ?? ''));
            $email = strtolower(trim((string) ($data['email'] ?? '')));
            $rawPhone = trim((string) ($data['phone'] ?? ''));
            $phone = $rawPhone !== '' ? mb_substr($rawPhone, 0, 25) : null;
            $rawRole = strtolower(trim((string) ($data['role'] ?? '')));
            $role = $rawRole !== '' ? $rawRole : 'candidate';
            $rawPassword = (string) ($data['password'] ?? '');
            $rawStatus = strtolower(trim((string) ($data['status'] ?? '')));
            $vpaOrSchool = trim((string) ($data['vpa_or_school'] ?? ''));
            $contactPerson = trim((string) ($data['contact_person'] ?? ''));
            $address = trim((string) ($data['address'] ?? ''));

            if ($name === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $failed++;
                $recordIssue("Row {$rowNum}: Valid Name and Email are required.");
                continue;
            }

            if (!in_array($role, ['candidate', 'employer'], true)) {
                $failed++;
                $recordIssue("Row {$rowNum} ({$email}): Invalid role '{$role}'. Allowed roles: candidate, employer.");
                continue;
            }

            $isActive = true;
            if ($rawStatus !== '') {
                if (in_array($rawStatus, ['inactive', 'disabled', '0', 'no', 'false', 'blocked'], true)) {
                    $isActive = false;
                } elseif (!in_array($rawStatus, ['active', 'enabled', '1', 'yes', 'true'], true)) {
                    $failed++;
                    $recordIssue("Row {$rowNum} ({$email}): Invalid status '{$rawStatus}'. Use active or inactive.");
                    continue;
                }
            }

            if ($rawPassword !== '' && mb_strlen($rawPassword) < 8) {
                $failed++;
                $recordIssue("Row {$rowNum} ({$email}): Temporary Password must be at least 8 characters if provided.");
                continue;
            }

            try {
                $existing = User::where('email', $email)->first();

                if ($existing) {
                    if ($existing->role === 'admin') {
                        $skipped++;
                        $recordIssue("Row {$rowNum} ({$email}): Admin account cannot be modified via import (skipped).");
                        continue;
                    }

                    if ($duplicateMode !== 'update') {
                        $skipped++;
                        $recordIssue("Row {$rowNum} ({$email}): User already exists (skipped).");
                        continue;
                    }

                    if ($phone !== null && User::where('phone', $phone)->where('id', '!=', $existing->id)->exists()) {
                        $failed++;
                        $recordIssue("Row {$rowNum} ({$email}): Phone '{$phone}' already belongs to another user.");
                        continue;
                    }

                    DB::transaction(function () use ($existing, $name, $phone, $rawStatus, $isActive, $rawPassword, $vpaOrSchool, $contactPerson, $address): void {
                        $userUpdates = ['name' => $name];
                        if ($phone !== null) {
                            $userUpdates['phone'] = $phone;
                        }
                        if ($rawStatus !== '') {
                            $userUpdates['is_active'] = $isActive;
                        }
                        $existing->update($userUpdates);

                        if ($rawPassword !== '') {
                            $existing->issueTemporaryPassword($rawPassword);
                        }

                        if ($existing->role === 'candidate') {
                            $profile = $existing->profile ?: CandidateProfile::firstOrCreate(['user_id' => $existing->id]);
                            $profileUpdates = [];
                            if ($address !== '') {
                                $profileUpdates['address'] = $address;
                            }
                            if ($vpaOrSchool !== '' && !CandidateProfile::where('vpa_id', $vpaOrSchool)->where('id', '!=', $profile->id)->exists()) {
                                $profileUpdates['vpa_id'] = mb_substr($vpaOrSchool, 0, 50);
                            }
                            if (!empty($profileUpdates)) {
                                $profile->update($profileUpdates);
                            }
                        } elseif ($existing->role === 'employer' && ($vpaOrSchool !== '' || $contactPerson !== '' || $address !== '')) {
                            $emp = $existing->employerProfile ?: EmployerProfile::create([
                                'user_id' => $existing->id,
                                'school_name' => $vpaOrSchool !== '' ? $vpaOrSchool : $name,
                            ]);
                            $empUpdates = [];
                            if ($vpaOrSchool !== '') {
                                $empUpdates['school_name'] = $vpaOrSchool;
                            }
                            if ($contactPerson !== '') {
                                $empUpdates['contact_person'] = $contactPerson;
                            }
                            if ($address !== '') {
                                $empUpdates['address'] = $address;
                            }
                            if (!empty($empUpdates)) {
                                $emp->update($empUpdates);
                            }
                        }
                    });

                    $updated++;
                    continue;
                }

                if ($phone !== null && User::where('phone', $phone)->exists()) {
                    $failed++;
                    $recordIssue("Row {$rowNum} ({$email}): Phone '{$phone}' already exists for another user.");
                    continue;
                }

                DB::transaction(function () use ($name, $email, $phone, $role, $isActive, $rawPassword, $vpaOrSchool, $contactPerson, $address): void {
                    $initialPassword = $rawPassword !== '' ? $rawPassword : ('Vpa@' . random_int(1000, 9999) . Str::lower(Str::random(4)));

                    $user = new User([
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'role' => $role,
                        'password' => $initialPassword,
                    ]);
                    $user->email_verified_at = now();
                    $user->is_active = $isActive;
                    $user->save();

                    // Store hashed only & enforce next-login password rotation cleanly.
                    $user->issueTemporaryPassword($initialPassword);

                    if ($role === 'candidate') {
                        $profileAttrs = ['user_id' => $user->id];
                        if ($address !== '') {
                            $profileAttrs['address'] = $address;
                        }
                        if ($vpaOrSchool !== '' && !CandidateProfile::where('vpa_id', $vpaOrSchool)->exists()) {
                            $profileAttrs['vpa_id'] = mb_substr($vpaOrSchool, 0, 50);
                        }
                        CandidateProfile::create($profileAttrs);
                    } elseif ($role === 'employer') {
                        EmployerProfile::create([
                            'user_id' => $user->id,
                            'school_name' => $vpaOrSchool !== '' ? $vpaOrSchool : $name,
                            'contact_person' => $contactPerson !== '' ? $contactPerson : $name,
                            'address' => $address !== '' ? $address : null,
                        ]);
                    }
                });

                $created++;
            } catch (Throwable $e) {
                $failed++;
                Log::warning('Admin user import row failed', [
                    'row' => $rowNum,
                    'email' => $email,
                    'error' => $e->getMessage(),
                ]);
                $recordIssue("Row {$rowNum} ({$email}): " . $e->getMessage());
            }
        }

        $summary = "Import complete: {$created} created, {$updated} updated, {$skipped} skipped, {$failed} failed.";
        $flashType = ($created > 0 || $updated > 0) ? 'success' : ($failed > 0 ? 'error' : 'warning');

        return redirect()
            ->route('admin.users.index')
            ->with($flashType, $summary)
            ->with('import_report', [
                'created' => $created,
                'updated' => $updated,
                'skipped' => $skipped,
                'failed' => $failed,
                'issues' => $issues,
            ]);
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot modify admin status.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User account has been {$status}.");
    }

    public function impersonate($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot impersonate an admin.');
        }

        if (!$user->is_active) {
            return back()->with('error', 'Cannot impersonate an inactive user.');
        }

        // Store admin id in session to return later
        $adminId = auth()->id();
        
        Auth::login($user);
        
        session()->put('impersonate_admin_id', $adminId);
        session()->save();

        if ($user->role === 'employer') {
            return redirect()->route('employer.dashboard');
        }

        return redirect()->route('candidate.dashboard');
    }

    public function leaveImpersonate()
    {
        if (session()->has('impersonate_admin_id')) {
            $adminId = session('impersonate_admin_id');
            $admin = User::find($adminId);

            if ($admin && $admin->role === 'admin') {
                session()->forget('impersonate_admin_id');
                Auth::login($admin);
                session()->save();
                return redirect()->route('admin.users.index')->with('success', 'Returned to admin dashboard.');
            }
        }

        return redirect('/');
    }
}
