<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralWallet;
use App\Models\ReferralWalletTransaction;
use App\Models\ReferralSetting;
use App\Models\ReferralRedemption;
use App\Models\ReferralEmailInvite;
use App\Models\ReferralMilestone;
use App\Models\ReferralAuditLog;
use App\Models\User;
use App\Services\ReferralService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    /**
     * Admin Referral Dashboard (Matching Mockup 1).
     */
    public function dashboard(Request $request)
    {
        $pointRate = ReferralService::getPointRate();

        // Date range filter (Default: current month)
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : now()->startOfMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfMonth();

        // 6 Top KPI Metrics with Month-over-Month calculations
        $totalReferrers = Referral::distinct('referrer_id')->count('referrer_id');
        $totalReferrals = Referral::count();
        $successfulReferrals = Referral::where('stage', 'joined')->count();
        $pendingReferrals = Referral::where('stage', '!=', 'joined')->where('status', 'active')->count();
        $pointsIssued = ReferralWalletTransaction::where('type', 'credit')->sum('points');
        $pointsRedeemed = ReferralWalletTransaction::where('type', 'debit')->where('source', 'service_charge_redemption')->sum('points');

        // This month diffs
        $thisMonthReferrers = Referral::whereBetween('created_at', [$startDate, $endDate])->distinct('referrer_id')->count('referrer_id');
        $thisMonthReferrals = Referral::whereBetween('created_at', [$startDate, $endDate])->count();
        $thisMonthSuccessful = Referral::where('stage', 'joined')->whereBetween('updated_at', [$startDate, $endDate])->count();
        $thisMonthPointsIssued = ReferralWalletTransaction::where('type', 'credit')->whereBetween('created_at', [$startDate, $endDate])->sum('points');
        $thisMonthPointsRedeemed = ReferralWalletTransaction::where('type', 'debit')->where('source', 'service_charge_redemption')->whereBetween('created_at', [$startDate, $endDate])->sum('points');

        // Referral Conversion Funnel
        $totalClicks = (int) ReferralSetting::get('mock_link_clicks', max($totalReferrals * 2, 100));
        $funnel = [
            'clicks' => $totalClicks,
            'registered' => Referral::count(),
            'profile_completed' => Referral::whereIn('stage', ['profile_completed', 'verified', 'interview_scheduled', 'selected', 'joined'])->count(),
            'interview_scheduled' => Referral::whereIn('stage', ['interview_scheduled', 'selected', 'joined'])->count(),
            'selected' => Referral::whereIn('stage', ['selected', 'joined'])->count(),
            'joined' => Referral::where('stage', 'joined')->count(),
        ];

        // Growth Chart Data (Last 30 Days or Days in Range)
        $growthLabels = [];
        $growthTotal = [];
        $growthSuccessful = [];
        $growthPending = [];

        for ($i = 6; $i >= 0; $i--) {
            $dayStart = now()->subDays($i * 5)->startOfDay();
            $dayEnd = now()->subDays($i * 5)->endOfDay();
            $growthLabels[] = $dayStart->format('d M');
            $growthTotal[] = Referral::where('created_at', '<=', $dayEnd)->count();
            $growthSuccessful[] = Referral::where('stage', 'joined')->where('created_at', '<=', $dayEnd)->count();
            $growthPending[] = Referral::where('stage', '!=', 'joined')->where('created_at', '<=', $dayEnd)->count();
        }

        // Recent Referrals Table (10 records)
        $recentReferrals = Referral::with(['referrer', 'referee'])
            ->latest()
            ->take(10)
            ->get();

        // Top Referrers Leaderboard (This Month)
        $topReferrers = User::whereHas('referralsMade')
            ->withCount(['referralsMade as total_referred', 'referralsMade as joined_count' => function ($q) {
                $q->where('stage', 'joined');
            }])
            ->with('referralWallet')
            ->orderByDesc('joined_count')
            ->orderByDesc('total_referred')
            ->take(5)
            ->get();

        // Financial Points Summary
        $availablePointsAllWallets = ReferralWallet::sum('available_points');
        $expiredOrCancelledPoints = ReferralWalletTransaction::where('type', 'debit')->where('source', 'admin_adjustment')->sum('points');
        $totalDiscountGiven = ReferralRedemption::sum('discount_amount');

        // Recent Activities Feed
        $recentActivities = ReferralWalletTransaction::with(['user', 'referral.referee'])
            ->latest()
            ->take(6)
            ->get();

        // Referral Source Performance Stats
        $sourceStats = [
            'whatsapp' => Referral::where('source', 'whatsapp')->count(),
            'email' => Referral::where('source', 'email')->count(),
            'telegram' => Referral::where('source', 'telegram')->count(),
            'copy_link' => Referral::where('source', 'copy_link')->count(),
            'other' => Referral::whereNotIn('source', ['whatsapp', 'email', 'telegram', 'copy_link'])->count(),
        ];

        return view('admin.referrals.dashboard', compact(
            'totalReferrers',
            'totalReferrals',
            'successfulReferrals',
            'pendingReferrals',
            'pointsIssued',
            'pointsRedeemed',
            'thisMonthReferrers',
            'thisMonthReferrals',
            'thisMonthSuccessful',
            'thisMonthPointsIssued',
            'thisMonthPointsRedeemed',
            'funnel',
            'growthLabels',
            'growthTotal',
            'growthSuccessful',
            'growthPending',
            'recentReferrals',
            'topReferrers',
            'availablePointsAllWallets',
            'expiredOrCancelledPoints',
            'totalDiscountGiven',
            'recentActivities',
            'pointRate',
            'startDate',
            'endDate',
            'sourceStats'
        ));
    }

    /**
     * All Referrals List & Search & Export.
     */
    public function index(Request $request)
    {
        $query = Referral::with(['referrer', 'referee', 'transactions']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('referrer', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })->orWhereHas('referee', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })->orWhere('referral_code_used', 'like', "%{$search}%");
            });
        }

        if ($stage = $request->input('stage')) {
            $query->where('stage', $stage);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($source = $request->input('source')) {
            $query->where('source', $source);
        }

        // Export to CSV
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportReferralsCsv($query->get());
        }

        $referrals = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total' => Referral::count(),
            'registered' => Referral::where('stage', 'registered')->count(),
            'profile_completed' => Referral::where('stage', 'profile_completed')->count(),
            'verified' => Referral::where('stage', 'verified')->count(),
            'interview_scheduled' => Referral::where('stage', 'interview_scheduled')->count(),
            'selected' => Referral::where('stage', 'selected')->count(),
            'joined' => Referral::where('stage', 'joined')->count(),
        ];

        return view('admin.referrals.index', compact('referrals', 'stats'));
    }

    /**
     * Candidate Referral Wallets Management.
     */
    public function wallets(Request $request)
    {
        $query = ReferralWallet::with(['user.profile']);

        if ($search = $request->input('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('locked')) {
            $query->where('is_locked', $request->locked == '1');
        }

        $wallets = $query->orderByDesc('available_points')->paginate(20)->withQueryString();
        $pointRate = ReferralService::getPointRate();

        return view('admin.referrals.wallets', compact('wallets', 'pointRate'));
    }

    /**
     * Adjust Wallet Points (Credit or Debit) with mandatory reason.
     */
    public function adjustWallet(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:credit,debit',
            'points' => 'required|numeric|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $wallet = ReferralWallet::findOrFail($id);
        ReferralService::adminAdjust(
            $wallet,
            (float) $request->points,
            $request->type,
            $request->reason,
            auth()->user()
        );

        return back()->with('success', "Wallet successfully adjusted! {$request->type} of {$request->points} points recorded.");
    }

    /**
     * Freeze / Unfreeze Candidate Wallet.
     */
    public function toggleLock(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        $wallet = ReferralWallet::findOrFail($id);
        $wallet->is_locked = !$wallet->is_locked;
        $wallet->lock_reason = $wallet->is_locked ? ($request->reason ?: 'Locked by administrator') : null;
        $wallet->save();

        ReferralService::logAudit(
            auth()->id(),
            $wallet->user_id,
            $wallet->is_locked ? 'wallet_frozen' : 'wallet_unfrozen',
            $wallet->lock_reason
        );

        $status = $wallet->is_locked ? 'frozen' : 'unfrozen';
        return back()->with('success', "Candidate referral wallet has been {$status}.");
    }

    /**
     * Approve or Reject Referral.
     */
    public function approveReferral(Request $request, $id)
    {
        $referral = Referral::findOrFail($id);
        $referral->status = 'active';
        $referral->save();

        ReferralService::logAudit(auth()->id(), $referral->referrer_id, 'referral_approved', "Referral #{$id} approved");

        return back()->with('success', "Referral #{$id} approved.");
    }

    public function rejectReferral(Request $request, $id)
    {
        $request->validate(['reason' => 'nullable|string|max:255']);
        $referral = Referral::findOrFail($id);
        $referral->status = 'flagged';
        $referral->save();

        $reason = $request->filled('reason') ? $request->reason : 'Flagged / rejected by administrator';
        ReferralService::logAudit(auth()->id(), $referral->referrer_id, 'referral_rejected', $reason);

        return back()->with('success', "Referral #{$id} rejected & flagged.");
    }

    /**
     * Manage Reward Settings.
     */
    public function settings()
    {
        $settings = [
            'is_referral_active' => ReferralSetting::get('is_referral_active', '1'),
            'points_on_registration' => ReferralSetting::get('points_on_registration', '50'),
            'points_on_profile_complete' => ReferralSetting::get('points_on_profile_complete', '100'),
            'points_on_verification' => ReferralSetting::get('points_on_verification', '100'),
            'points_on_interview' => ReferralSetting::get('points_on_interview', '150'),
            'points_on_placement' => ReferralSetting::get('points_on_placement', '500'),
            'referee_bonus_points' => ReferralSetting::get('referee_bonus_points', '100'),
            'point_rate_inr' => ReferralSetting::get('point_rate_inr', '0.50'),
            'max_discount_percentage' => ReferralSetting::get('max_discount_percentage', '30'),
            'cookie_duration_days' => ReferralSetting::get('cookie_duration_days', '30'),
            'max_daily_invites' => ReferralSetting::get('max_daily_invites', '20'),
        ];

        return view('admin.referrals.settings', compact('settings'));
    }

    /**
     * Update Reward Settings.
     */
    public function updateSettings(Request $request)
    {
        $fields = [
            'is_referral_active' => 'required|in:0,1',
            'points_on_registration' => 'required|numeric|min:0',
            'points_on_profile_complete' => 'required|numeric|min:0',
            'points_on_verification' => 'required|numeric|min:0',
            'points_on_interview' => 'required|numeric|min:0',
            'points_on_selection' => 'nullable|numeric|min:0',
            'points_on_placement' => 'required|numeric|min:0',
            'referee_bonus_points' => 'required|numeric|min:0',
            'point_rate_inr' => 'required|numeric|min:0.01',
            'max_discount_percentage' => 'required|numeric|min:0|max:100',
            'cookie_duration_days' => 'required|integer|min:1',
            'max_daily_invites' => 'required|integer|min:1',
        ];

        $request->validate($fields);

        foreach ($fields as $key => $rule) {
            ReferralSetting::set($key, $request->input($key));
        }

        return back()->with('success', 'Referral & Reward settings updated successfully!');
    }

    /**
     * Manage Referral Milestones (5, 10, 25 Referrals).
     */
    public function milestones()
    {
        $milestones = ReferralMilestone::orderBy('successful_referrals_required')->get();
        return view('admin.referrals.milestones', compact('milestones'));
    }

    public function updateMilestones(Request $request)
    {
        $request->validate([
            'milestones' => 'required|array',
            'milestones.*.id' => 'required|exists:referral_milestones,id',
            'milestones.*.successful_referrals_required' => 'required|integer|min:1',
            'milestones.*.bonus_points' => 'required|numeric|min:0',
            'milestones.*.is_active' => 'nullable|boolean',
        ]);

        foreach ($request->milestones as $item) {
            ReferralMilestone::where('id', $item['id'])->update([
                'successful_referrals_required' => $item['successful_referrals_required'],
                'bonus_points' => $item['bonus_points'],
                'is_active' => isset($item['is_active']) && $item['is_active'],
            ]);
        }

        return back()->with('success', 'Referral milestones updated successfully!');
    }

    /**
     * Suspicious / Fraud Activity Screen.
     */
    public function fraud()
    {
        // Potential duplicates / flagged referrals
        $flaggedReferrals = Referral::with(['referrer', 'referee'])
            ->where('status', 'flagged')
            ->latest()
            ->paginate(15);

        $lockedWallets = ReferralWallet::with('user')
            ->where('is_locked', true)
            ->get();

        $auditLogs = ReferralAuditLog::with(['admin', 'candidate'])
            ->latest()
            ->take(30)
            ->get();

        return view('admin.referrals.fraud', compact('flaggedReferrals', 'lockedWallets', 'auditLogs'));
    }

    /**
     * Service Charge Redemptions Log.
     */
    public function redemptions(Request $request)
    {
        $query = ReferralRedemption::with(['user', 'serviceChargeInvoice']);

        if ($search = $request->input('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $redemptions = $query->latest()->paginate(20)->withQueryString();
        $totalRedeemedAmount = ReferralRedemption::sum('discount_amount');
        $totalRedeemedPoints = ReferralRedemption::sum('points_redeemed');

        return view('admin.referrals.redemptions', compact('redemptions', 'totalRedeemedAmount', 'totalRedeemedPoints'));
    }

    /**
     * Show Single Referral Detail & 6-Stage Timeline for Admin.
     */
    public function show($id)
    {
        $referral = Referral::with(['referrer.referralWallet', 'referee.profile', 'transactions.wallet'])
            ->findOrFail($id);

        $pointRate = (float) ReferralSetting::get('point_rate_inr', 0.50);

        $rewardSettings = [
            'points_on_registration' => (float) ReferralSetting::get('points_on_registration', 50),
            'points_on_profile_complete' => (float) ReferralSetting::get('points_on_profile_complete', 100),
            'points_on_verification' => (float) ReferralSetting::get('points_on_verification', 100),
            'points_on_interview' => (float) ReferralSetting::get('points_on_interview', 150),
            'points_on_selection' => (float) ReferralSetting::get('points_on_selection', 0),
            'points_on_placement' => (float) ReferralSetting::get('points_on_placement', 500),
        ];

        return view('admin.referrals.show', compact('referral', 'pointRate', 'rewardSettings'));
    }

    /**
     * Complete Wallet Transactions History Ledger.
     */
    public function transactions(Request $request)
    {
        $query = ReferralWalletTransaction::with(['user', 'referral.referee', 'admin', 'serviceChargeInvoice']);

        if ($search = $request->input('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($source = $request->input('source')) {
            $query->where('source', $source);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                \Carbon\Carbon::parse($request->start_date)->startOfDay(),
                \Carbon\Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $transactions = $query->latest()->paginate(25)->withQueryString();

        $stats = [
            'total_credits' => ReferralWalletTransaction::where('type', 'credit')->sum('points'),
            'total_debits' => ReferralWalletTransaction::where('type', 'debit')->sum('points'),
            'total_inr_redeemed' => ReferralWalletTransaction::where('type', 'debit')->where('source', 'service_charge_redemption')->sum('amount_equivalent'),
        ];

        return view('admin.referrals.transactions', compact('transactions', 'stats'));
    }

    /**
     * Full Referrers Leaderboard with Rankings.
     */
    public function leaderboard(Request $request)
    {
        $timeframe = $request->input('timeframe', 'all');

        $query = User::whereHas('referralsMade');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($timeframe === 'month') {
            $monthStart = now()->startOfMonth();
            $query->withCount([
                'referralsMade as total_referred' => function ($q) use ($monthStart) {
                    $q->where('created_at', '>=', $monthStart);
                },
                'referralsMade as joined_count' => function ($q) use ($monthStart) {
                    $q->where('stage', 'joined')->where('created_at', '>=', $monthStart);
                },
            ]);
        } else {
            $query->withCount([
                'referralsMade as total_referred',
                'referralsMade as joined_count' => function ($q) {
                    $q->where('stage', 'joined');
                },
            ]);
        }

        $leaderboard = $query->with('referralWallet')
            ->orderByDesc('joined_count')
            ->orderByDesc('total_referred')
            ->paginate(20)
            ->withQueryString();

        $pointRate = (float) ReferralSetting::get('point_rate_inr', 0.50);

        return view('admin.referrals.leaderboard', compact('leaderboard', 'timeframe', 'pointRate'));
    }

    /**
     * Preview Referral Email Template in Browser.
     */
    public function emailPreview()
    {
        $referrer = auth()->user();
        $dummyInvite = new ReferralEmailInvite([
            'friend_name' => 'Aditi Sharma',
            'friend_email' => 'aditi@example.com',
            'token' => 'PREVIEW_TOKEN_123',
            'status' => 'sent',
        ]);

        return new \App\Mail\ReferralInviteMail(
            $referrer,
            $dummyInvite,
            'Check out this platform for teaching jobs across India!'
        );
    }

    /**
     * Send Test Referral Email to Admin.
     */
    public function emailTest(Request $request)
    {
        $admin = auth()->user();
        $targetEmail = $request->input('email', $admin->email);

        $dummyInvite = new ReferralEmailInvite([
            'friend_name' => $admin->name,
            'friend_email' => $targetEmail,
            'token' => 'TEST_' . \Illuminate\Support\Str::random(16),
            'status' => 'sent',
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($targetEmail)->send(
                new \App\Mail\ReferralInviteMail($admin, $dummyInvite, 'This is a test invitation email from Vedanta Admin.')
            );
            return back()->with('success', "Test referral email sent successfully to {$targetEmail}!");
        } catch (\Exception $e) {
            return back()->with('error', "Failed to send test email: " . $e->getMessage());
        }
    }

    /**
     * Export Referrals to CSV.
     */
    private function exportReferralsCsv($referrals)
    {
        $filename = 'referrals-report-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($referrals) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Referrer Name', 'Referrer Email', 'Referrer Phone', 'Referee Name', 'Referee Email', 'Referee Phone', 'Referral Code', 'Source', 'Stage', 'Points Earned', 'Status', 'Created At']);

            foreach ($referrals as $ref) {
                fputcsv($file, [
                    'REF' . str_pad($ref->id, 5, '0', STR_PAD_LEFT),
                    $ref->referrer?->name ?? 'N/A',
                    $ref->referrer?->email ?? 'N/A',
                    $ref->referrer?->phone ?? 'N/A',
                    $ref->referee?->name ?? 'N/A',
                    $ref->referee?->email ?? 'N/A',
                    $ref->referee?->phone ?? 'N/A',
                    $ref->referral_code_used,
                    $ref->source,
                    $ref->stage,
                    $ref->points_earned,
                    $ref->status,
                    $ref->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
