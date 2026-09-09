<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        // Only show applications that are shortlisted or hired for this employer's jobs
        $query = JobApplication::with([
            'candidate.profile.category',
            'candidate.profile.subject',
            'candidate.profile.highestQualification',
            'jobPost'
        ])
        ->whereHas('jobPost', function ($q) {
            $q->where('user_id', auth()->id());
        })
        ->whereIn('status', ['shortlisted', 'hired', 'rejected', 'hold']);

        if ($jobId = $request->input('job_post_id')) {
            $query->where('job_post_id', $jobId);
        }

        $applications = $query->latest()->paginate(15)->withQueryString();
        
        $myJobs = \App\Models\JobPost::where('user_id', auth()->id())->get();

        return view('employer.applicants.index', compact('applications', 'myJobs'));
    }

    /**
     * Track when an employer views a candidate's profile/resume.
     */
    public function trackView($candidateId, Request $request)
    {
        $employerId = auth()->id();
        $candidate = \App\Models\User::find($candidateId);

        if (!$candidate || $candidate->role !== 'candidate') {
            return response()->json(['success' => false, 'message' => 'Candidate not found'], 404);
        }

        // Check if viewed within cooldown (30 minutes) to avoid artificial multi-click spam in same session
        $recentView = \App\Models\CandidateProfileView::where('candidate_id', $candidate->id)
            ->where('employer_id', $employerId)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->first();

        if (!$recentView) {
            \App\Models\CandidateProfileView::create([
                'candidate_id' => $candidate->id,
                'employer_id' => $employerId,
                'ip_address' => $request->ip(),
                'user_agent' => substr($request->userAgent(), 0, 255),
            ]);

            // Increment candidate profile views_count
            $profile = $candidate->profile ?: $candidate->profile()->firstOrCreate([]);
            if ($profile) {
                $profile->increment('views_count');
            }
        }

        $viewsCount = $candidate->profile ? (int) $candidate->profile->views_count : 0;

        return response()->json([
            'success' => true,
            'views_count' => $viewsCount,
        ]);
    }
}
