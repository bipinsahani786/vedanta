<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Services\PhonePeService;

class PaymentController extends Controller
{
    private PhonePeService $phonePe;

    public function __construct()
    {
        $this->phonePe = new PhonePeService();
    }

    public function show(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile ?: $user->profile()->firstOrCreate([]);
        $isRenewal = $request->query('type') === 'renewal';

        // Auto-heal any pending payment in the last 2 hours
        $pendingTxn = \App\Models\PaymentTransaction::where('candidate_id', $user->id)
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subHours(2))
            ->latest()
            ->first();

        if ($pendingTxn) {
            try {
                $statusResult = $this->phonePe->checkStatus($pendingTxn->transaction_id);
                if ($statusResult['success']) {
                    \App\Services\PaymentFulfillmentService::fulfill(
                        $pendingTxn->transaction_id,
                        true,
                        ($statusResult['amount'] ?? 0) / 100,
                        $statusResult['raw'] ?? [],
                        $statusResult['transactionId'] ?? null
                    );
                    $profile = $user->fresh()->profile ?: $user->profile()->firstOrCreate([]);
                } elseif ($statusResult['is_failed'] ?? false) {
                    $pendingTxn->update(['status' => 'failed']);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Payment show auto-heal error: ' . $e->getMessage());
            }
        }

        // Allow candidate to view Payment & Plan dashboard
        // If renewal with pending amount, guide to service charge
        if ($isRenewal && $profile->pending_amount > 0 && $profile->plan_type !== 'standard') {
            return redirect()->route('candidate.serviceCharge.show')->with('error', 'You must clear your pending dues of ₹' . $profile->pending_amount . ' before renewing your plan.');
        }

        // Referral Wallet
        $wallet = \App\Services\ReferralService::getWallet($user);
        $pointRate = \App\Services\ReferralService::getPointRate();
        $availablePoints = $wallet ? (float)$wallet->available_points : 0;
        $walletBalanceInr = round($availablePoints * $pointRate, 2);

        // Transactions & Paid Amounts
        $transactions = \App\Models\PaymentTransaction::where('candidate_id', $user->id)
            ->latest()
            ->get();
        $dbPaidSum = $transactions->where('status', 'success')->sum('amount');
        $fallbackPaid = (($profile->plan_type === 'premium' || $profile->is_fee_paid) ? 1000 : ($profile->initial_fee_paid ? 500 : 0));
        $totalPaidAmount = max($dbPaidSum, $fallbackPaid);

        // Next Payment Due
        if ($profile->plan_type === 'premium' || $profile->is_fee_paid) {
            $nextPaymentDue = (float)($profile->pending_amount ?? 0);
        } elseif ($profile->initial_fee_paid) {
            $nextPaymentDue = 500;
        } else {
            $nextPaymentDue = 500;
        }

        // Validity Date & Registration Date
        $planStartedAt = $profile->plan_started_at ?? $profile->created_at ?? now();
        $planValidityDate = \Carbon\Carbon::parse($planStartedAt)->addDays(30);
        $firstSuccessTxn = $transactions->where('status', 'success')->first();
        $registrationPaidDate = $firstSuccessTxn ? $firstSuccessTxn->created_at->format('d M Y') : ($profile->initial_fee_paid ? ($profile->updated_at ? $profile->updated_at->format('d M Y') : now()->format('d M Y')) : null);

        return view('candidate.payment.show', compact(
            'user',
            'profile',
            'isRenewal',
            'wallet',
            'pointRate',
            'availablePoints',
            'walletBalanceInr',
            'transactions',
            'totalPaidAmount',
            'nextPaymentDue',
            'planValidityDate',
            'registrationPaidDate'
        ));
    }

    public function process(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:basic,premium,renewal_basic,renewal_premium,upgrade'
        ]);

        $user = auth()->user();
        $isRenewal = str_starts_with($request->plan, 'renewal');
        $isUpgrade = $request->plan === 'upgrade';
        
        $profile = $user->profile ?: $user->profile()->firstOrCreate([]);

        // Check if existing plan is expired (after 30 days)
        $planStartedAt = $profile->plan_started_at ?? $profile->created_at;
        $isPlanExpired = $planStartedAt ? \Carbon\Carbon::parse($planStartedAt)->addDays(30)->isPast() : false;

        // If candidate already paid ₹500 for Standard plan:
        if ($profile->plan_type === 'standard' && ($profile->initial_fee_paid || ($profile->paid_amount ?? 0) >= 500)) {
            if ($isPlanExpired && !$isUpgrade && $request->plan !== 'premium') {
                // Plan is expired/ended (after 30 days): ₹500 payment is a RENEWAL of Standard plan, NOT an upgrade!
                $isRenewal = true;
                $request->merge(['plan' => 'renewal_basic']);
            } elseif (!$profile->is_fee_paid || $profile->pending_amount > 0) {
                // Plan is still active (within 30 days): second ₹500 payment upgrades to Premium (total ₹1000)
                if ($request->plan === 'basic' || $request->plan === 'upgrade' || $request->plan === 'premium') {
                    $isUpgrade = true;
                }
            } else {
                if ($request->plan === 'basic') {
                    return back()->with('error', 'You have already paid for the Basic plan.');
                }
            }
        }

        $amount = 500;
        if ($request->plan === 'premium' || $request->plan === 'renewal_premium') $amount = 1000;
        if ($isUpgrade) $amount = 500;

        // Prevent duplicate payment if already active Premium
        if (($request->plan === 'premium' || $request->plan === 'upgrade') && $profile->plan_type === 'premium' && $profile->is_fee_paid) {
            return back()->with('error', 'You are already a Premium member.');
        }

        $prefix = 'TXN_';
        if ($request->plan === 'renewal_basic') $prefix = 'RENEW_BASIC_';
        if ($request->plan === 'renewal_premium') $prefix = 'RENEW_PREMIUM_';
        if ($isUpgrade) $prefix = 'UPGRADE_';
        $transactionId = $prefix . $user->id . '_' . time();

        $planTypeChoice = ($isUpgrade || $request->plan === 'premium' || $request->plan === 'renewal_premium') ? 'premium' : 'standard';

        // Pre-create transaction record in database so intent is never lost even if session drops
        \App\Models\PaymentTransaction::create([
            'candidate_id' => $user->id,
            'amount' => $amount,
            'transaction_id' => $transactionId,
            'type' => 'registration_fee',
            'status' => 'pending',
            'gateway_response' => [
                'plan' => $request->plan,
                'plan_type' => $planTypeChoice,
                'initiated_at' => now()->toIso8601String(),
                'ip' => $request->ip()
            ]
        ]);

        $redirectUrl = route('candidate.payment.callback');

        // Initiate payment via PhonePe V2
        $result = $this->phonePe->initiatePay($transactionId, $amount, $redirectUrl);

        if ($result['success']) {
            session(['last_txn_id' => $transactionId, 'pending_plan_type' => $planTypeChoice]);
            return redirect()->away($result['redirect_url']);
        }

        // Mark as failed if initiation could not connect to PhonePe
        \App\Models\PaymentTransaction::where('transaction_id', $transactionId)->update([
            'status' => 'failed',
            'gateway_response' => ['init_error' => $result['error']]
        ]);

        return back()->with('error', 'Failed to initiate payment: ' . $result['error']);
    }

    public function callback(Request $request)
    {
        $transactionId = $request->merchantOrderId 
            ?? $request->merchantTransactionId 
            ?? $request->orderId 
            ?? (str_starts_with($request->transactionId ?? '', 'TXN_') || str_starts_with($request->transactionId ?? '', 'UPGRADE_') || str_starts_with($request->transactionId ?? '', 'RENEW_') ? $request->transactionId : null)
            ?? session('last_txn_id');

        // Fallback: If session was lost on mobile and no ID in request, recover active user's pending transaction
        if (!$transactionId && auth()->check()) {
            $latestPending = \App\Models\PaymentTransaction::where('candidate_id', auth()->id())
                ->where('status', 'pending')
                ->where('created_at', '>=', now()->subHours(2))
                ->latest()
                ->first();
            $transactionId = $latestPending?->transaction_id;
        }

        if (!$transactionId) {
            return redirect()->route('candidate.dashboard')->with('error', 'Payment session expired. Please check your dashboard or try again.');
        }

        // Auto-login user if session was lost on cross-site redirect
        if (!auth()->check()) {
            $candidateId = \App\Services\PaymentFulfillmentService::extractCandidateId($transactionId);
            if ($candidateId) {
                $u = \App\Models\User::find($candidateId);
                if ($u) auth()->login($u);
            }
        }

        // Verify status with PhonePe V2
        $statusResult = $this->phonePe->checkStatus($transactionId);

        \Illuminate\Support\Facades\Log::info('PhonePe V2 Payment Callback Status', [
            'result' => $statusResult, 
            'txn' => $transactionId
        ]);

        $isSuccess = $statusResult['success'] ?? false;
        $isPending = $statusResult['is_pending'] ?? false;
        $amountPaid = ($statusResult['amount'] ?? 0) / 100; // Convert paise to rupees

        $fulfillment = \App\Services\PaymentFulfillmentService::fulfill(
            $transactionId,
            $isSuccess,
            $amountPaid,
            $statusResult['raw'] ?? ['is_pending' => $isPending],
            $statusResult['transactionId'] ?? null
        );

        if ($isPending) {
            return redirect()->route('candidate.dashboard')->with('warning', 'Payment is being processed by your bank. Your plan will be updated automatically shortly.');
        }

        if (!$isSuccess) {
            return redirect()->route('candidate.dashboard')->with('error', 'Payment failed or was cancelled. Please try again.');
        }

        return redirect()->route('candidate.dashboard')->with('success', 'Payment processed successfully. Your plan is now active!');
    }

    public function invoice($id)
    {
        $transaction = \App\Models\PaymentTransaction::where('candidate_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        if ($transaction->status !== 'success' && $transaction->status !== 'COMPLETED') {
            return redirect()->back()->with('error', 'Invoice is only available for successful payments.');
        }

        $user = auth()->user();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('candidate.payment.invoice', [
            'transaction' => $transaction,
            'user' => $user
        ]);

        return $pdf->download('Invoice-' . $transaction->transaction_id . '.pdf');
    }
}
