<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\CandidateProfile;
use App\Models\ServiceChargeInvoice;

class DashboardController extends Controller
{
    public function index()
    {
        // Registration Revenue (Paid amounts from candidate profiles)
        $registrationRevenue = CandidateProfile::sum('paid_amount');
        
        // Service Charge Revenue (Paid service charges)
        $serviceChargeRevenue = ServiceChargeInvoice::where('status', 'paid')->sum('amount');
        
        // Pending Collections
        $pendingCollections = CandidateProfile::sum('pending_amount');

        // Total Collections
        $totalCollections = $registrationRevenue + $serviceChargeRevenue;

        // Statistics
        $totalCandidates = User::where('role', 'candidate')->count();
        $totalSchools = User::where('role', 'employer')->count();
        $activeJobs = JobPost::where('status', 'approved')->count();
        $placements = JobApplication::where('status', 'hired')->count();

        // Recent Candidates
        $recentCandidates = User::where('role', 'candidate')
            ->with('profile')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Pending Approvals
        $pendingJobs = JobPost::where('status', 'pending')
            ->with(['user', 'category', 'subject'])
            ->limit(5)
            ->get();

        // Plan Purchases
        $plan500Count = CandidateProfile::where('paid_amount', 500)->count();
        $plan1000Count = CandidateProfile::where('paid_amount', 1000)->count();

        return view('admin.dashboard', compact(
            'registrationRevenue',
            'serviceChargeRevenue',
            'pendingCollections',
            'totalCollections',
            'totalCandidates',
            'totalSchools',
            'activeJobs',
            'placements',
            'recentCandidates',
            'pendingJobs',
            'plan500Count',
            'plan1000Count'
        ));
    }
}
