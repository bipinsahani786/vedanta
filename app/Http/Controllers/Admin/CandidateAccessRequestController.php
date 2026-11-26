<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SchoolCandidateAccessSharedMail;
use App\Models\CandidateAccessLog;
use App\Models\CandidateAccessRequest;
use App\Notifications\SchoolProfileAccessReviewed;
use App\Services\SchoolCandidateAccessService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Throwable;

class CandidateAccessRequestController extends Controller
{
    public function __construct(private readonly SchoolCandidateAccessService $accessService)
    {
    }

    public function index(Request $request)
    {
        $ready = $this->accessService->isFeatureReady();

        if (! $ready) {
            return view('admin.access-requests.index', [
                'featureReady' => false,
                'requests' => new LengthAwarePaginator([], 0, 15),
                'recentLogs' => collect(),
                'stats' => ['total' => 0, 'pending' => 0, 'approved' => 0, 'partially_approved' => 0, 'declined' => 0],
            ]);
        }

        $stats = [
            'total' => CandidateAccessRequest::count(),
            'pending' => CandidateAccessRequest::where('status', CandidateAccessRequest::STATUS_PENDING)->count(),
            'approved' => CandidateAccessRequest::where('status', CandidateAccessRequest::STATUS_APPROVED)->count(),
            'partially_approved' => CandidateAccessRequest::where('status', CandidateAccessRequest::STATUS_PARTIALLY_APPROVED)->count(),
            'declined' => CandidateAccessRequest::where('status', CandidateAccessRequest::STATUS_DECLINED)->count(),
        ];

        $query = CandidateAccessRequest::with([
            'school.employerProfile',
            'candidate.profile.category',
            'candidate.profile.subject',
            'candidate.profile.highestQualification',
            'reviewer',
        ]);

        if ($status = trim((string) $request->input('status', ''))) {
            $query->where('status', $status);
        }

        if ($search = trim((string) $request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('school', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('employerProfile', fn ($ep) => $ep->where('school_name', 'like', "%{$search}%"));
                })->orWhereHas('candidate', function ($cq) use ($search) {
                    $cq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('profile', function ($cp) use ($search) {
                            $cp->where('vpa_id', 'like', "%{$search}%")
                                ->orWhereHas('subject', fn ($sub) => $sub->where('name', 'like', "%{$search}%"));
                        });
                });
            });
        }

        $requests = $query
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        $recentLogs = CandidateAccessLog::with([
            'school.employerProfile',
            'candidate.profile',
            'actor',
        ])
            ->latest('created_at')
            ->latest('id')
            ->take(25)
            ->get();

        return view('admin.access-requests.index', [
            'featureReady' => true,
            'requests' => $requests,
            'recentLogs' => $recentLogs,
            'stats' => $stats,
        ]);
    }

    public function show($id)
    {
        abort_unless(Schema::hasTable('candidate_access_requests'), 404);

        $accessRequest = CandidateAccessRequest::with([
            'school.employerProfile',
            'candidate.profile.category',
            'candidate.profile.subject',
            'candidate.profile.specialization',
            'candidate.profile.highestQualification',
            'candidate.profile.preferredState',
            'candidate.profile.preferredCity',
            'reviewer',
            'logs.actor',
        ])->findOrFail($id);

        return view('admin.access-requests.show', compact('accessRequest'));
    }

    public function review(Request $request, $id)
    {
        abort_unless($this->accessService->isFeatureReady(), 503);

        $accessRequest = CandidateAccessRequest::with([
            'school.employerProfile',
            'candidate.profile.category',
            'candidate.profile.subject',
            'candidate.profile.highestQualification',
            'candidate.profile.preferredState',
            'candidate.profile.preferredCity',
        ])->findOrFail($id);

        $validated = $request->validate([
            'decision' => 'required|in:approve,decline',
            'delivery_method' => 'required_if:decision,approve|nullable|in:dashboard,email,both',
            'admin_notes' => 'nullable|string|max:1000',
            'can_view_photo' => 'nullable|boolean',
            'can_view_full_profile' => 'nullable|boolean',
            'can_view_cv' => 'nullable|boolean',
            'can_view_contact' => 'nullable|boolean',
        ]);

        $school = $accessRequest->school;
        $candidate = $accessRequest->candidate;
        $schoolName = $school?->employerProfile?->school_name ?: ($school?->name ?? 'School');
        $candidateRef = $candidate?->profile?->vpa_id ?: ($candidate?->name ?? ('Candidate #' . $accessRequest->candidate_user_id));

        if ($validated['decision'] === 'decline') {
            $reason = trim((string) ($validated['admin_notes'] ?? ''));
            if ($reason === '') {
                return back()->withInput()->withErrors([
                    'admin_notes' => 'Please enter a reason before declining the access request.',
                ]);
            }

            $accessRequest->update([
                'status' => CandidateAccessRequest::STATUS_DECLINED,
                'can_view_photo' => false,
                'can_view_full_profile' => false,
                'can_view_cv' => false,
                'can_view_contact' => false,
                'admin_notes' => $reason,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            $this->accessService->recordAccessLog($accessRequest->fresh(), 'declined', auth()->id(), $reason);

            if ($school) {
                try {
                    $school->notify(new SchoolProfileAccessReviewed($accessRequest->fresh(), $candidateRef));
                } catch (Throwable $e) {
                    Log::warning('Failed notifying school on access decline', ['request_id' => $accessRequest->id, 'error' => $e->getMessage()]);
                }
            }

            return redirect()
                ->route('admin.access-requests.show', $accessRequest->id)
                ->with('success', "Access request from {$schoolName} has been declined and logged.");
        }

        $photo = $request->boolean('can_view_photo');
        $fullProfile = $request->boolean('can_view_full_profile');
        $cv = $request->boolean('can_view_cv');
        $contact = $request->boolean('can_view_contact');

        if (! $photo && ! $fullProfile && ! $cv && ! $contact) {
            return back()->withInput()->withErrors([
                'permissions' => 'Please grant at least one field permission (Photo, Full Profile, CV, or Contact) or choose Decline.',
            ]);
        }

        $allGranted = $photo && $fullProfile && $cv && $contact;
        $computedStatus = $allGranted
            ? CandidateAccessRequest::STATUS_APPROVED
            : CandidateAccessRequest::STATUS_PARTIALLY_APPROVED;

        $deliveryMethod = $validated['delivery_method'] ?? CandidateAccessRequest::DELIVERY_DASHBOARD;
        $notes = ! empty($validated['admin_notes']) ? trim($validated['admin_notes']) : null;

        $accessRequest->update([
            'status' => $computedStatus,
            'can_view_photo' => $photo,
            'can_view_full_profile' => $fullProfile,
            'can_view_cv' => $cv,
            'can_view_contact' => $contact,
            'delivery_method' => $deliveryMethod,
            'admin_notes' => $notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $freshRequest = $accessRequest->fresh([
            'school.employerProfile',
            'candidate.profile.category',
            'candidate.profile.subject',
            'candidate.profile.highestQualification',
            'candidate.profile.preferredState',
            'candidate.profile.preferredCity',
        ]);

        $this->accessService->recordAccessLog($freshRequest, $computedStatus, auth()->id(), $notes);

        $emailStatusMsg = '';
        if (in_array($deliveryMethod, [CandidateAccessRequest::DELIVERY_EMAIL, CandidateAccessRequest::DELIVERY_BOTH], true) && $school && ! empty($school->email)) {
            try {
                Mail::to($school->email)->send(new SchoolCandidateAccessSharedMail($freshRequest, $schoolName, $candidateRef));
                $freshRequest->forceFill(['email_sent_at' => now()])->save();
                $this->accessService->recordAccessLog(
                    $freshRequest->fresh(),
                    'email_shared',
                    auth()->id(),
                    "Shared approved fields via email to {$school->email}"
                );
                $emailStatusMsg = " Shared via email to {$school->email}.";
            } catch (Throwable $e) {
                Log::error('Failed to email shared candidate details to school', [
                    'request_id' => $freshRequest->id,
                    'school_email' => $school->email,
                    'error' => $e->getMessage(),
                ]);
                $emailStatusMsg = ' (Warning: Email dispatch failed — check mail logs.)';
            }
        }

        if ($school) {
            try {
                $school->notify(new SchoolProfileAccessReviewed($freshRequest->fresh(), $candidateRef));
            } catch (Throwable $e) {
                Log::warning('Failed notifying school on access approval', ['request_id' => $freshRequest->id, 'error' => $e->getMessage()]);
            }
        }

        return redirect()
            ->route('admin.access-requests.show', $freshRequest->id)
            ->with('success', "Profile access updated to {$freshRequest->statusLabel()} for {$schoolName}.{$emailStatusMsg}");
    }
}
