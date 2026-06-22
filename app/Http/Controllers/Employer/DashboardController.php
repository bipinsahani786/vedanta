<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JobPost;
use App\Models\JobApplication;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        $stats = [
            'total_jobs' => JobPost::where('user_id', $userId)->count(),
            'active_jobs' => JobPost::where('user_id', $userId)->where('status', 'approved')->count(),
            'pending_jobs' => JobPost::where('user_id', $userId)->where('status', 'pending')->count(),
            'total_candidates' => JobApplication::whereHas('jobPost', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->whereIn('status', ['shortlisted', 'hired'])->count(),
        ];

        $recentJobs = JobPost::where('user_id', $userId)->latest()->take(5)->get();

        return view('employer.dashboard', compact('stats', 'recentJobs'));
    }
}
