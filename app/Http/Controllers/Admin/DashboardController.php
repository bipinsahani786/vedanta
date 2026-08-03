<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\CandidateProfile;
use App\Models\ServiceChargeInvoice;
use App\Models\PaymentTransaction;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Date filters for application stats
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Registration Revenue (Paid registration fees)
        $registrationTxnRevenue = PaymentTransaction::where('status', 'success')
            ->where('type', 'registration_fee')
            ->sum('amount');
        $registrationProfileRevenue = CandidateProfile::where('is_fee_paid', true)->sum('paid_amount');
        $registrationRevenue = $registrationTxnRevenue > 0 ? $registrationTxnRevenue : $registrationProfileRevenue;
        
        // Service Charge Revenue (Paid service charges)
        $serviceChargeRevenue = ServiceChargeInvoice::where('status', 'paid')->sum('amount');
        
        // Pending Collections & Dues Breakdown
        $pendingCollections = CandidateProfile::sum('pending_amount');
        $overdueInvoicesAmount = ServiceChargeInvoice::where('status', 'overdue')->selectRaw('SUM(amount + late_fee) as total')->value('total') ?? 0;
        $totalLateFees = ServiceChargeInvoice::sum('late_fee');

        // Total Collections (Registration Revenue + Paid Service Charges)
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

        // Revenue Chart Data Generation (Combines Registration Payments & Service Charge Invoices cleanly)
        $registrationTxns = PaymentTransaction::where('status', 'success')->get(['amount', 'created_at']);
        $paidInvoices = ServiceChargeInvoice::where('status', 'paid')->get(['amount', 'updated_at', 'payment_date']);
        
        $chartData = ['days' => ['labels' => [], 'data' => []], 'months' => ['labels' => [], 'data' => []], 'years' => ['labels' => [], 'data' => []]];
        
        // Days (last 30)
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $chartData['days']['labels'][] = $date->format('M d');
            
            $reg = $registrationTxns->filter(fn($t) => $t->created_at && $t->created_at->format('Y-m-d') === $dateStr)->sum('amount');
            $srv = $paidInvoices->filter(fn($inv) => ($inv->payment_date ? \Carbon\Carbon::parse($inv->payment_date)->format('Y-m-d') : ($inv->updated_at ? $inv->updated_at->format('Y-m-d') : null)) === $dateStr)->sum('amount');
            
            $chartData['days']['data'][] = $reg + $srv;
        }
        
        // Months (last 12)
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $dateStr = $date->format('Y-m');
            $chartData['months']['labels'][] = $date->format('M Y');
            
            $reg = $registrationTxns->filter(fn($t) => $t->created_at && $t->created_at->format('Y-m') === $dateStr)->sum('amount');
            $srv = $paidInvoices->filter(fn($inv) => ($inv->payment_date ? \Carbon\Carbon::parse($inv->payment_date)->format('Y-m') : ($inv->updated_at ? $inv->updated_at->format('Y-m') : null)) === $dateStr)->sum('amount');
            
            $chartData['months']['data'][] = $reg + $srv;
        }

        // Years (last 5)
        for ($i = 4; $i >= 0; $i--) {
            $date = now()->subYears($i);
            $dateStr = $date->format('Y');
            $chartData['years']['labels'][] = $dateStr;
            
            $reg = $registrationTxns->filter(fn($t) => $t->created_at && $t->created_at->format('Y') === $dateStr)->sum('amount');
            $srv = $paidInvoices->filter(fn($inv) => ($inv->payment_date ? \Carbon\Carbon::parse($inv->payment_date)->format('Y') : ($inv->updated_at ? $inv->updated_at->format('Y') : null)) === $dateStr)->sum('amount');
            
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
            'overdueInvoicesAmount',
            'totalLateFees',
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
