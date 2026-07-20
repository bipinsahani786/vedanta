<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPost;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    private function ensureRegistrationComplete()
    {
        $profile = auth()->user()->profile;
        if (!$profile || (!$profile->initial_fee_paid && !$profile->is_fee_paid)) {
            return redirect()->route('candidate.dashboard')->with('error', 'Please complete your registration and fee payment to unlock job applications.');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->ensureRegistrationComplete()) return $redirect;

        $applications = JobApplication::with(['jobPost.category', 'jobPost.city', 'jobPost.state'])
            ->where('candidate_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('candidate.applications.index', compact('applications'));
    }

    public function available()
    {
        if ($redirect = $this->ensureRegistrationComplete()) return $redirect;

        $user = auth()->user();
        $profile = $user->profile;

        // Get approved jobs
        $jobs = JobPost::with(['category', 'subject', 'city', 'state', 'qualification'])
            ->where('status', 'approved')
            ->whereDoesntHave('applications', function ($query) use ($user) {
                $query->where('candidate_id', $user->id);
            })
            ->get();

        // Calculate match scores
        $matchedJobs = $jobs->map(function ($job) use ($profile) {
            $score = 0;
            
            // Subject match is most important (40%)
            if ($job->subject_id == $profile->subject_id) $score += 40;
            
            // Category match (30%)
            if ($job->category_id == $profile->category_id) $score += 30;
            
            // Qualification match (20%)
            if ($job->qualification_id == $profile->highest_qualification_id) $score += 20;
            
            // Location match (10%)
            if ($job->city_id == $profile->preferred_city_id) $score += 10;
            elseif ($job->state_id == $profile->preferred_state_id) $score += 5;
            
            $job->match_score = $score;
            return $job;
        })->sortByDesc('match_score')->values();

        return view('candidate.applications.available', compact('matchedJobs'));
    }

    public function apply(Request $request, JobPost $job)
    {
        if ($redirect = $this->ensureRegistrationComplete()) return $redirect;

        $user = auth()->user();
        $profile = $user->profile;

        // Calculate score again for saving
        $score = 0;
        if ($job->subject_id == $profile->subject_id) $score += 40;
        if ($job->category_id == $profile->category_id) $score += 30;
        if ($job->qualification_id == $profile->highest_qualification_id) $score += 20;
        if ($job->city_id == $profile->preferred_city_id) $score += 10;
        elseif ($job->state_id == $profile->preferred_state_id) $score += 5;

        // Prevent duplicate application
        if (JobApplication::where('job_post_id', $job->id)->where('candidate_id', $user->id)->exists()) {
            return back()->with('error', 'You have already applied for this job.');
        }

        // Check if candidate is already hired (Plan Ended) in the current plan cycle
        if (JobApplication::where('candidate_id', $user->id)
            ->where('status', 'hired')
            ->where('created_at', '>=', $profile->plan_started_at ?? $profile->created_at)
            ->exists()) {
            return back()->with('error', 'Congratulations on being selected! Your current plan has successfully ended. You cannot apply for new jobs at this time.');
        }

        // Limit Check (Applies to all plans)
        if ($profile->used_applications >= $profile->total_allowed_applications) {
            return back()->with('error', 'You have reached your maximum allowed applications for your current plan.');
        }

        JobApplication::create([
            'job_post_id' => $job->id,
            'candidate_id' => $user->id,
            'status' => 'applied',
            'match_score' => $score
        ]);

        $profile->increment('used_applications');

        return redirect()->route('candidate.applications.index')->with('success', 'Application submitted successfully.');
    }
}
