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
        $query = JobApplication::with(['candidate.profile', 'jobPost'])
            ->whereHas('jobPost', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->whereIn('status', ['shortlisted', 'hired']);

        if ($jobId = $request->input('job_post_id')) {
            $query->where('job_post_id', $jobId);
        }

        $applications = $query->latest()->paginate(15)->withQueryString();
        
        $myJobs = \App\Models\JobPost::where('user_id', auth()->id())->get();

        return view('employer.applicants.index', compact('applications', 'myJobs'));
    }
}
