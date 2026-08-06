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
        $profile = $user->profile;
        $isRenewal = $request->query('type') === 'renewal';

        if (!$profile->is_profile_complete || !$profile->is_agreement_signed) {
            return redirect()->route('candidate.dashboard')->with('error', 'Please complete previous steps first.');
        }

        // Allow standard plan users to upgrade by paying their pending amount as an upgrade fee
        if ($isRenewal && $profile->pending_amount > 0 && $profile->plan_type !== 'standard') {
            return redirect()->route('candidate.serviceCharge.show')->with('error', 'You must clear your pending dues of ₹' . $profile->pending_amount . ' before renewing your plan.');
        }

        // Removed the check that blocked paid users from viewing their plans
        return view('candidate.payment.show', compact('user', 'profile', 'isRenewal'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:basic,premium,renewal_basic,renewal_premium,upgrade'
        ]);

        $user = auth()->user();
        $isRenewal = str_starts_with($request->plan, 'renewal');
        $isUpgrade = $request->plan === 'upgrade';
        
        $amount = 500;
        if ($request->plan === 'premium' || $request->plan === 'renewal_premium') $amount = 1000;
        if ($isUpgrade) $amount = 500;
        
        $profile = $user->profile;

        // Prevent duplicate payments
        if ($request->plan === 'basic' && $profile->plan_type === 'standard' && ($profile->initial_fee_paid || $profile->is_fee_paid)) {
            return back()->with('error', 'You have already paid for the Basic plan.');
        }
        if (($request->plan === 'premium' || $request->plan === 'upgrade') && $profile->plan_type === 'premium' && $profile->is_fee_paid) {
            return back()->with('error', 'You are already a Premium member.');
        }

        $prefix = 'TXN_';
        if ($request->plan === 'renewal_basic') $prefix = 'RENEW_BASIC_';
        if ($request->plan === 'renewal_premium') $prefix = 'RENEW_PREMIUM_';
        if ($isUpgrade) $prefix = 'UPGRADE_';
        $transactionId = $prefix . $user->id . '_' . time();

        $redirectUrl = route('candidate.payment.callback');

        // Initiate payment via PhonePe V2
        $result = $this->phonePe->initiatePay($transactionId, $amount, $redirectUrl);

        if ($result['success']) {
            session(['last_txn_id' => $transactionId]);
            return redirect()->away($result['redirect_url']);
        }

        return back()->with('error', 'Failed to initiate payment: ' . $result['error']);
    }

    public function callback(Request $request)
    {
        $transactionId = $request->merchantOrderId ?? $request->transactionId ?? $request->orderId ?? session('last_txn_id');

        if (!$transactionId) {
            return redirect()->route('candidate.dashboard')->with('error', 'Payment session expired. Please try again.');
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

        $isSuccess = $statusResult['success'];
        $amountPaid = ($statusResult['amount'] ?? 0) / 100; // Convert paise to rupees

        $fulfillment = \App\Services\PaymentFulfillmentService::fulfill(
            $transactionId,
            $isSuccess,
            $amountPaid,
            $statusResult['raw'] ?? [],
            $statusResult['transactionId'] ?? null
        );

        if (!$isSuccess) {
            return redirect()->route('candidate.dashboard')->with('error', 'Payment failed or was cancelled. Please try again.');
        }

        return redirect()->route('candidate.dashboard')->with('success', 'Payment processed successfully.');
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
