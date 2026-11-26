<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\CandidateAccessRequest;
use App\Models\Category;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\Subject;
use App\Models\User;
use App\Services\SchoolCandidateAccessService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(SchoolCandidateAccessService $accessService)
    {
        $userId = auth()->id();
        
        $jobSummary = JobPost::statusSummary($userId);

        $stats = [
            'total_jobs' => $jobSummary['total'],
            'active_jobs' => $jobSummary['live'],
            'pending_jobs' => $jobSummary['pending'],
            'rejected_jobs' => $jobSummary['rejected'],
            'total_candidates' => JobApplication::whereHas('jobPost', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->whereIn('status', ['shortlisted', 'hired'])->count(),
        ];

        $recentJobs = JobPost::where('user_id', $userId)->latest()->take(5)->get();
        $employerProfile = auth()->user()->employerProfile;

        $discoveryReady = $accessService->isFeatureReady();
        $discoveryStats = [
            'eligible' => 0,
            'verified' => 0,
            'best' => 0,
            'pending_requests' => 0,
            'approved_requests' => 0,
        ];
        $recentAccessRequests = collect();
        $quickCategories = Category::orderBy('name')->get();
        $quickSubjects = Subject::orderBy('name')->get();

        if ($discoveryReady) {
            $discoveryStats['eligible'] = User::where('role', 'candidate')
                ->where('is_active', true)
                ->whereHas('profile', fn ($p) => $p->where('school_discovery_visible', true))
                ->count();
            $discoveryStats['verified'] = User::where('role', 'candidate')
                ->where('is_active', true)
                ->whereHas('profile', fn ($p) => $p->where('school_discovery_visible', true)->where('is_verified', true))
                ->count();
            $discoveryStats['best'] = User::where('role', 'candidate')
                ->where('is_active', true)
                ->whereHas('profile', fn ($p) => $p->where('school_discovery_visible', true)->where('is_best_profile', true))
                ->count();
            $discoveryStats['pending_requests'] = CandidateAccessRequest::where('school_user_id', $userId)
                ->where('status', CandidateAccessRequest::STATUS_PENDING)
                ->count();
            $discoveryStats['approved_requests'] = CandidateAccessRequest::where('school_user_id', $userId)
                ->whereIn('status', [CandidateAccessRequest::STATUS_APPROVED, CandidateAccessRequest::STATUS_PARTIALLY_APPROVED])
                ->count();

            $recentAccessRequests = CandidateAccessRequest::where('school_user_id', $userId)
                ->with(['candidate.profile.subject', 'candidate.profile.category'])
                ->latest('updated_at')
                ->take(4)
                ->get();
        }

        $walkthroughKey = $userId ? "platform_walkthrough_done:employer:{$userId}" : null;
        $forceWalkthrough = request()->boolean('tour');
        $shouldStartWalkthrough = $forceWalkthrough || ($walkthroughKey ? ! \Illuminate\Support\Facades\Cache::get($walkthroughKey, false) : false);

        return view('employer.dashboard', compact(
            'stats',
            'recentJobs',
            'employerProfile',
            'discoveryReady',
            'discoveryStats',
            'recentAccessRequests',
            'quickCategories',
            'quickSubjects',
            'shouldStartWalkthrough',
            'forceWalkthrough'
        ));
    }
}
