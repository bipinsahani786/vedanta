<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralEmailInvite;
use App\Models\ReferralSetting;
use App\Models\ReferralMilestone;
use App\Models\ServiceChargeInvoice;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ReferralController extends Controller
{
    /**
     * Display Candidate Refer & Earn Hub (Mockup 2 Design).
     */
    public function index()
    {
        $user = auth()->user();
        $wallet = ReferralService::getWallet($user);
        $pointRate = ReferralService::getPointRate(); // 0.50 INR per point

        // Core Candidate KPIs
        $totalReferrals = Referral::where('referrer_id', $user->id)->count();
        $successfulReferrals = Referral::where('referrer_id', $user->id)->whereIn('stage', ['joined', 'placed'])->count();
        $pendingReferrals = Referral::where('referrer_id', $user->id)->whereNotIn('stage', ['joined', 'placed'])->where('status', 'active')->count();
        $rejectedReferrals = Referral::where('referrer_id', $user->id)->whereIn('status', ['cancelled', 'flagged'])->count();

        $availablePoints = (float) $wallet->available_points;
        $inrValue = round($availablePoints * $pointRate, 2);
        $lifetimeEarned = (float) $wallet->lifetime_points;
        $redeemedPoints = (float) $user->referralTransactions()->where('type', 'debit')->where('source', 'service_charge_redemption')->sum('points');
        $pendingPoints = (float) $wallet->pending_points;

        // Next Milestone Calculation
        $milestones = ReferralMilestone::where('is_active', true)->orderBy('successful_referrals_required')->get();
        $nextMilestone = $milestones->firstWhere('successful_referrals_required', '>', $successfulReferrals);
        
        $milestoneData = [
            'has_next' => (bool) $nextMilestone,
            'required' => $nextMilestone ? $nextMilestone->successful_referrals_required : 25,
            'bonus_points' => $nextMilestone ? $nextMilestone->bonus_points : 5000,
            'remaining' => $nextMilestone ? ($nextMilestone->successful_referrals_required - $successfulReferrals) : 0,
        ];

        // Recent Referrals List
        $recentReferrals = Referral::with('referee')
            ->where('referrer_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Recent Transactions Ledger
        $recentTransactions = $user->referralTransactions()
            ->latest()
            ->take(10)
            ->get();

        // Sent Email Invites
        $sentInvites = ReferralEmailInvite::where('referrer_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Fetch any unpaid Service Charge Invoice for the Live Calculator
        $activeInvoice = ServiceChargeInvoice::where('candidate_id', $user->id)
            ->whereIn('status', ['pending', 'overdue'])
            ->latest()
            ->first();

        $sampleOriginal = $activeInvoice ? ($activeInvoice->amount + ($activeInvoice->late_fee ?? 0)) : 15000;
        $maxDiscountPercentage = (float) ReferralSetting::get('max_discount_percentage', 30);
        $sampleMaxDiscount = round($sampleOriginal * ($maxDiscountPercentage / 100), 2);
        $sampleAvailableDiscount = min($inrValue, $sampleMaxDiscount);
        $sampleFinalPayable = max(0, $sampleOriginal - $sampleAvailableDiscount);

        // Milestone Bonus Tiers for Showcase
        $allMilestones = ReferralMilestone::where('is_active', true)->orderBy('successful_referrals_required')->get();

        return view('candidate.referral.index', compact(
            'user',
            'wallet',
            'pointRate',
            'totalReferrals',
            'successfulReferrals',
            'pendingReferrals',
            'rejectedReferrals',
            'availablePoints',
            'inrValue',
            'lifetimeEarned',
            'redeemedPoints',
            'pendingPoints',
            'milestoneData',
            'recentReferrals',
            'recentTransactions',
            'sentInvites',
            'activeInvoice',
            'sampleOriginal',
            'sampleMaxDiscount',
            'sampleAvailableDiscount',
            'sampleFinalPayable',
            'maxDiscountPercentage',
            'allMilestones'
        ));
    }

    /**
     * Send Email Invitations (Supports single or multi-invite).
     */
    public function sendInvite(Request $request)
    {
        $request->validate([
            'friend_name' => 'nullable|string|max:100',
            'friend_email' => 'nullable|email|max:150',
            'friend_names' => 'nullable|array',
            'friend_names.*' => 'nullable|string|max:100',
            'friend_emails' => 'nullable|array',
            'friend_emails.*' => 'nullable|email|max:150',
        ]);

        $user = auth()->user();
        $sentCount = 0;
        $errors = [];

        // Check array submissions first
        if ($request->filled('friend_emails') && is_array($request->friend_emails)) {
            foreach ($request->friend_emails as $idx => $email) {
                if (empty($email)) continue;
                $name = $request->friend_names[$idx] ?? 'Friend';
                $res = ReferralService::sendEmailInvite($user, $name, $email);
                if ($res['success']) {
                    $sentCount++;
                } else {
                    $errors[] = $res['message'];
                }
            }
        } elseif ($request->filled('friend_email')) {
            $name = $request->friend_name ?: 'Friend';
            $res = ReferralService::sendEmailInvite($user, $name, $request->friend_email);
            if ($res['success']) {
                $sentCount++;
            } else {
                $errors[] = $res['message'];
            }
        }

        if ($sentCount > 0) {
            return back()->with('success', "Successfully sent {$sentCount} referral invitation email(s)!");
        }

        return back()->with('error', !empty($errors) ? implode(' ', $errors) : 'Please provide a valid friend name and email address.');
    }

    /**
     * Redeem available points on active service charge invoice.
     */
    public function redeem(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:service_charge_invoices,id',
            'points' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();
        $invoice = ServiceChargeInvoice::where('candidate_id', $user->id)
            ->where('id', $request->invoice_id)
            ->firstOrFail();

        if ($invoice->status === 'paid') {
            return back()->with('error', 'This invoice has already been fully paid.');
        }

        $result = ReferralService::redeemForServiceCharge($user, $invoice, (float) $request->points);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Clean Short Link Interceptor: /r/{code}
     * Sets 30-day cookie and renders invitation landing or redirects to register.
     */
    public function handleShortLink(Request $request, string $code)
    {
        $code = strtoupper(trim($code));
        $referrer = User::where('referral_code', $code)->first();

        // 30 days cookie
        $cookieDays = (int) ReferralSetting::get('cookie_duration_days', 30);
        $cookie = cookie('vpa_referral_code', $code, 60 * 24 * $cookieDays);
        session(['referral_code' => $code]);

        // Track referral source from query param (?source=whatsapp/email/telegram/copy_link/other)
        $source = $request->input('source', 'other');
        session(['referral_source' => $source]);

        if (!$referrer) {
            return redirect()->route('candidate.register')->withCookie($cookie);
        }

        return response()
            ->view('candidate.referral.r_landing', compact('referrer', 'code'))
            ->withCookie($cookie);
    }

    /**
     * Token Invitation Handler: /invite/{token}
     */
    public function handleInviteToken(Request $request, string $token)
    {
        $invite = ReferralEmailInvite::with('referrer')->where('token', $token)->first();

        if (!$invite || !$invite->referrer) {
            return redirect()->route('candidate.register');
        }

        // Mark as opened
        if ($invite->status === 'sent') {
            $invite->update(['status' => 'opened']);
        }

        $code = $invite->referrer->referral_code;
        $cookieDays = (int) ReferralSetting::get('cookie_duration_days', 30);
        $cookie = cookie('vpa_referral_code', $code, 60 * 24 * $cookieDays);
        session(['referral_code' => $code]);

        return response()
            ->view('candidate.referral.r_landing', [
                'referrer' => $invite->referrer,
                'code' => $code,
                'friendName' => $invite->friend_name,
            ])
            ->withCookie($cookie);
    }

    /**
     * Display Single Candidate Referral Detail & 6-Stage Timeline.
     */
    public function show($id)
    {
        $user = auth()->user();
        $referral = Referral::with(['referee.profile', 'transactions' => function ($q) {
            $q->latest();
        }])
            ->where('referrer_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $pointRate = ReferralService::getPointRate();
        $wallet = ReferralService::getWallet($user);

        return view('candidate.referral.show', compact('referral', 'user', 'wallet', 'pointRate'));
    }
}
