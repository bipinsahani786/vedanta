<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\CandidateAccessRequest;
use App\Models\Category;
use App\Models\City;
use App\Models\Qualification;
use App\Models\State;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\SchoolProfileAccessRequested;
use App\Services\SchoolCandidateAccessService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Throwable;

class CandidateSearchController extends Controller
{
    public function __construct(private readonly SchoolCandidateAccessService $accessService)
    {
    }

    public function index(Request $request)
    {
        $school = auth()->user();
        $schoolId = (int) $school->id;
        $featureReady = $this->accessService->isFeatureReady();

        $categories = Category::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $qualifications = Qualification::orderBy('name')->get();
        $states = State::where('is_active', true)->orderBy('name')->get();
        $cities = City::where('is_active', true)->orderBy('name')->get();

        $filters = $request->only([
            'keyword',
            'category_id',
            'subject_id',
            'qualification_id',
            'min_experience',
            'verified_status',
            'curated',
            'availability',
            'state_id',
            'city_id',
        ]);

        $activeTab = $request->input('tab') === 'requests' ? 'requests' : 'search';

        if (! $featureReady) {
            $emptyPaginator = new LengthAwarePaginator([], 0, 12);

            return view('employer.talent.index', [
                'featureReady' => false,
                'activeTab' => $activeTab,
                'cards' => $emptyPaginator,
                'curatedVerified' => collect(),
                'curatedBest' => collect(),
                'myRequests' => collect(),
                'discoveryStats' => ['eligible' => 0, 'verified' => 0, 'best' => 0, 'requests' => 0],
                'categories' => $categories,
                'subjects' => $subjects,
                'qualifications' => $qualifications,
                'states' => $states,
                'cities' => $cities,
            ]);
        }

        $requestsByCandidate = CandidateAccessRequest::where('school_user_id', $schoolId)
            ->with(['logs'])
            ->get()
            ->keyBy('candidate_user_id');

        $baseQuery = $this->accessService->buildEligibleCandidatesQuery($filters, $schoolId)
            ->with([
                'profile.category',
                'profile.subject',
                'profile.specialization',
                'profile.highestQualification',
                'profile.preferredState',
                'profile.preferredCity',
            ]);

        $paginator = (clone $baseQuery)
            ->latest('users.id')
            ->paginate(12)
            ->withQueryString();

        $paginator->setCollection(
            $paginator->getCollection()->map(
                fn (User $candidate) => $this->accessService->buildSafeCandidateCard(
                    $candidate,
                    $requestsByCandidate->get($candidate->id)
                )
            )
        );

        // Curated sections from school-visible pool
        $hasSearchParams = count(array_filter($filters, fn ($v) => $v !== null && $v !== '')) > 0;
        $curatedVerified = collect();
        $curatedBest = collect();

        if (! $hasSearchParams) {
            $curatedVerified = $this->accessService
                ->buildEligibleCandidatesQuery(['curated' => 'verified'], $schoolId)
                ->with(['profile.category', 'profile.subject', 'profile.specialization', 'profile.highestQualification', 'profile.preferredState', 'profile.preferredCity'])
                ->latest('users.id')
                ->take(4)
                ->get()
                ->map(fn (User $c) => $this->accessService->buildSafeCandidateCard($c, $requestsByCandidate->get($c->id)));

            $curatedBest = $this->accessService
                ->buildEligibleCandidatesQuery(['curated' => 'best'], $schoolId)
                ->with(['profile.category', 'profile.subject', 'profile.specialization', 'profile.highestQualification', 'profile.preferredState', 'profile.preferredCity'])
                ->latest('users.id')
                ->take(4)
                ->get()
                ->map(fn (User $c) => $this->accessService->buildSafeCandidateCard($c, $requestsByCandidate->get($c->id)));
        }

        $discoveryStats = [
            'eligible' => User::where('role', 'candidate')->where('is_active', true)
                ->whereHas('profile', fn ($p) => $p->where('school_discovery_visible', true))->count(),
            'verified' => User::where('role', 'candidate')->where('is_active', true)
                ->whereHas('profile', fn ($p) => $p->where('school_discovery_visible', true)->where('is_verified', true))->count(),
            'best' => User::where('role', 'candidate')->where('is_active', true)
                ->whereHas('profile', fn ($p) => $p->where('school_discovery_visible', true)->where('is_best_profile', true))->count(),
            'requests' => $requestsByCandidate->count(),
        ];

        $myRequests = CandidateAccessRequest::where('school_user_id', $schoolId)
            ->with([
                'candidate.profile.category',
                'candidate.profile.subject',
                'candidate.profile.specialization',
                'candidate.profile.highestQualification',
                'candidate.profile.preferredState',
                'candidate.profile.preferredCity',
                'logs',
            ])
            ->latest('updated_at')
            ->get()
            ->filter(fn (CandidateAccessRequest $req) => $req->candidate !== null)
            ->map(function (CandidateAccessRequest $req) use ($school) {
                $preview = $this->accessService->buildCandidatePreviewPayload($req->candidate, $school, $req);
                $preview['school_note'] = $req->school_note;
                $preview['requested_labels'] = $req->requestedPermissionLabels();

                return $preview;
            })
            ->values();

        return view('employer.talent.index', [
            'featureReady' => true,
            'activeTab' => $activeTab,
            'cards' => $paginator,
            'curatedVerified' => $curatedVerified,
            'curatedBest' => $curatedBest,
            'myRequests' => $myRequests,
            'discoveryStats' => $discoveryStats,
            'categories' => $categories,
            'subjects' => $subjects,
            'qualifications' => $qualifications,
            'states' => $states,
            'cities' => $cities,
        ]);
    }

    public function preview($candidateId)
    {
        abort_unless($this->accessService->isFeatureReady(), 503);

        $school = auth()->user();
        $candidate = User::where('role', 'candidate')
            ->where('is_active', true)
            ->with([
                'profile.category',
                'profile.subject',
                'profile.specialization',
                'profile.highestQualification',
                'profile.preferredState',
                'profile.preferredCity',
            ])
            ->findOrFail($candidateId);

        $accessRequest = CandidateAccessRequest::where('school_user_id', $school->id)
            ->where('candidate_user_id', $candidate->id)
            ->with('logs')
            ->first();

        abort_unless(
            ($candidate->profile && $candidate->profile->school_discovery_visible)
            || ($accessRequest && $accessRequest->isUnlockGranted()),
            404
        );

        return response()->json([
            'success' => true,
            'candidate' => $this->accessService->buildCandidatePreviewPayload($candidate, $school, $accessRequest),
        ]);
    }

    public function photo($candidateId)
    {
        abort_unless($this->accessService->isFeatureReady(), 404);

        $school = auth()->user();
        $candidate = User::where('role', 'candidate')
            ->where('is_active', true)
            ->with('profile')
            ->findOrFail($candidateId);

        $accessRequest = CandidateAccessRequest::where('school_user_id', $school->id)
            ->where('candidate_user_id', $candidate->id)
            ->first();

        abort_unless(
            ($candidate->profile && $candidate->profile->school_discovery_visible)
            || ($accessRequest && $accessRequest->isUnlockGranted()),
            404
        );

        return $this->accessService->streamGatedCandidatePhoto($candidate, $accessRequest);
    }

    public function cv($candidateId)
    {
        abort_unless($this->accessService->isFeatureReady(), 404);

        $school = auth()->user();
        $candidate = User::where('role', 'candidate')
            ->where('is_active', true)
            ->with('profile')
            ->findOrFail($candidateId);

        $accessRequest = CandidateAccessRequest::where('school_user_id', $school->id)
            ->where('candidate_user_id', $candidate->id)
            ->first();

        abort_unless(
            $accessRequest && $accessRequest->canViewFieldOnDashboard('cv'),
            403,
            'CV access has not been unlocked for your school.'
        );

        return $this->accessService->streamUnlockedCv($candidate);
    }

    public function requestAccess(Request $request, $candidateId)
    {
        if (! $this->accessService->isFeatureReady()) {
            return $this->respondAccessOutcome($request, false, 'Candidate access request service is not ready yet.');
        }

        $validated = $request->validate([
            'school_note' => 'nullable|string|max:1000',
            'requested_photo' => 'nullable|boolean',
            'requested_full_profile' => 'nullable|boolean',
            'requested_cv' => 'nullable|boolean',
            'requested_contact' => 'nullable|boolean',
        ]);

        $school = auth()->user()->loadMissing('employerProfile');
        $candidate = User::where('role', 'candidate')
            ->where('is_active', true)
            ->with(['profile.category', 'profile.subject'])
            ->findOrFail($candidateId);

        if (! $candidate->profile || ! $candidate->profile->school_discovery_visible) {
            return $this->respondAccessOutcome($request, false, 'This candidate profile is not available for school discovery.', 404);
        }

        $reqPhoto = $request->hasAny(['requested_photo', 'requested_full_profile', 'requested_cv', 'requested_contact'])
            ? $request->boolean('requested_photo')
            : true;
        $reqFull = $request->hasAny(['requested_photo', 'requested_full_profile', 'requested_cv', 'requested_contact'])
            ? $request->boolean('requested_full_profile')
            : true;
        $reqCv = $request->hasAny(['requested_photo', 'requested_full_profile', 'requested_cv', 'requested_contact'])
            ? $request->boolean('requested_cv')
            : true;
        $reqContact = $request->hasAny(['requested_photo', 'requested_full_profile', 'requested_cv', 'requested_contact'])
            ? $request->boolean('requested_contact')
            : true;

        if (! $reqPhoto && ! $reqFull && ! $reqCv && ! $reqContact) {
            $reqFull = true;
            $reqContact = true;
        }

        $existing = CandidateAccessRequest::where('school_user_id', $school->id)
            ->where('candidate_user_id', $candidate->id)
            ->first();

        if ($existing) {
            if ($existing->status === CandidateAccessRequest::STATUS_PENDING) {
                return $this->respondAccessOutcome($request, true, 'Your unlock request for this candidate is already Pending Admin approval.', 200, $existing);
            }

            if ($existing->status !== CandidateAccessRequest::STATUS_DECLINED) {
                return $this->respondAccessOutcome(
                    $request,
                    true,
                    "This candidate profile already has status: {$existing->statusLabel()}.",
                    200,
                    $existing
                );
            }

            // Allow re-request after prior decline
            $existing->update([
                'status' => CandidateAccessRequest::STATUS_PENDING,
                'requested_photo' => $reqPhoto,
                'requested_full_profile' => $reqFull,
                'requested_cv' => $reqCv,
                'requested_contact' => $reqContact,
                'school_note' => ! empty($validated['school_note']) ? trim($validated['school_note']) : null,
                'can_view_photo' => false,
                'can_view_full_profile' => false,
                'can_view_cv' => false,
                'can_view_contact' => false,
                'admin_notes' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]);

            $accessRequest = $existing->fresh();
            $this->accessService->recordAccessLog($accessRequest, 're_requested', $school->id, $accessRequest->school_note);
        } else {
            $accessRequest = CandidateAccessRequest::create([
                'school_user_id' => $school->id,
                'candidate_user_id' => $candidate->id,
                'status' => CandidateAccessRequest::STATUS_PENDING,
                'requested_photo' => $reqPhoto,
                'requested_full_profile' => $reqFull,
                'requested_cv' => $reqCv,
                'requested_contact' => $reqContact,
                'school_note' => ! empty($validated['school_note']) ? trim($validated['school_note']) : null,
            ]);

            $this->accessService->recordAccessLog($accessRequest, 'requested', $school->id, $accessRequest->school_note);
        }

        $schoolName = $school->employerProfile?->school_name ?: $school->name;
        $candidateRef = trim(($candidate->profile->vpa_id ?: '#' . $candidate->id) . ' (' . ($candidate->profile->subject?->name ?? 'Teacher') . ')');

        try {
            User::where('role', 'admin')->get()->each(function (User $admin) use ($accessRequest, $schoolName, $candidateRef): void {
                $admin->notify(new SchoolProfileAccessRequested($accessRequest, $schoolName, $candidateRef));
            });
        } catch (Throwable $e) {
            Log::warning('Failed to notify admins about school profile access request', [
                'request_id' => $accessRequest->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $this->respondAccessOutcome(
            $request,
            true,
            'Profile unlock request submitted! Admin has been notified and will review field permissions shortly.',
            200,
            $accessRequest
        );
    }

    private function respondAccessOutcome(Request $request, bool $ok, string $message, int $code = 422, ?CandidateAccessRequest $accessRequest = null)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => $ok,
                'message' => $message,
                'access_status' => $accessRequest?->status,
                'access_status_label' => $accessRequest?->statusLabel(),
            ], $ok ? 200 : $code);
        }

        return back()->with($ok ? 'success' : 'error', $message);
    }
}
