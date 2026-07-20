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
    public function index(Request $request)
    {
        // Date filters for application stats
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

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

        // Application Metrics with Date Filters
        $appQuery = JobApplication::query();
        if ($fromDate) {
            $appQuery->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $appQuery->whereDate('created_at', '<=', $toDate);
        }

        $totalApplications = (clone $appQuery)->count();
        $rejectedApplications = (clone $appQuery)->where('status', 'rejected')->count();
        $transferredApplications = (clone $appQuery)->where('is_forwarded', true)->count();

        // Revenue Chart Data Generation
        $profiles = CandidateProfile::where('paid_amount', '>', 0)->get(['paid_amount', 'updated_at']);
        $invoices = ServiceChargeInvoice::where('status', 'paid')->get(['amount', 'updated_at']);
        
        $chartData = ['days' => ['labels' => [], 'data' => []], 'months' => ['labels' => [], 'data' => []], 'years' => ['labels' => [], 'data' => []]];
        
        // Days (last 30)
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $chartData['days']['labels'][] = $date->format('M d');
            
            $reg = $profiles->filter(fn($p) => $p->updated_at && $p->updated_at->format('Y-m-d') === $dateStr)->sum('paid_amount');
            $srv = $invoices->filter(fn($i) => $i->updated_at && $i->updated_at->format('Y-m-d') === $dateStr)->sum('amount');
            $chartData['days']['data'][] = $reg + $srv;
        }
        
        // Months (last 12)
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $dateStr = $date->format('Y-m');
            $chartData['months']['labels'][] = $date->format('M Y');
            
            $reg = $profiles->filter(fn($p) => $p->updated_at && $p->updated_at->format('Y-m') === $dateStr)->sum('paid_amount');
            $srv = $invoices->filter(fn($i) => $i->updated_at && $i->updated_at->format('Y-m') === $dateStr)->sum('amount');
            $chartData['months']['data'][] = $reg + $srv;
        }

        // Years (last 5)
        for ($i = 4; $i >= 0; $i--) {
            $date = now()->subYears($i);
            $dateStr = $date->format('Y');
            $chartData['years']['labels'][] = $dateStr;
            
            $reg = $profiles->filter(fn($p) => $p->updated_at && $p->updated_at->format('Y') === $dateStr)->sum('paid_amount');
            $srv = $invoices->filter(fn($i) => $i->updated_at && $i->updated_at->format('Y') === $dateStr)->sum('amount');
            $chartData['years']['data'][] = $reg + $srv;
        }

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

        // Filtered Recent Applications for the table
        $recentApps = (clone $appQuery)
            ->with(['candidate', 'jobPost'])
            ->orderBy('created_at', 'desc')
            ->limit(50) // Show up to 50 matching applications in the table
            ->get();

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
            'plan1000Count',
            'totalApplications',
            'rejectedApplications',
            'transferredApplications',
            'recentApps',
            'chartData',
            'fromDate',
            'toDate'
        ));
    }
}
