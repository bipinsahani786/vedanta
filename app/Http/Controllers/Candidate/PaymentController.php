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

        if ($isRenewal && $profile->pending_amount > 0) {
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
        $transactionId = $request->merchantOrderId ?? $request->transactionId ?? session('last_txn_id');

        $user = auth()->user();

        // Guard: If transactionId is missing, abort
        if (!$transactionId || !$user) {
            return redirect()->route('candidate.dashboard')->with('error', 'Payment session expired. Please try again.');
        }

        // Guard: Prevent duplicate processing for the same transaction
        $existingTxn = \App\Models\PaymentTransaction::where('transaction_id', $transactionId)->first();
        if ($existingTxn) {
            if ($existingTxn->status === 'success') {
                return redirect()->route('candidate.dashboard')->with('success', 'Payment already processed successfully.');
            }
            return redirect()->route('candidate.dashboard')->with('error', 'Payment failed or was already processed.');
        }

        // Verify status with PhonePe V2
        $statusResult = $this->phonePe->checkStatus($transactionId);
        
        \Illuminate\Support\Facades\Log::info('PhonePe V2 Upgrade/Renewal Callback Status', [
            'result' => $statusResult, 
            'txn' => $transactionId
        ]);

        $isSuccess = $statusResult['success'];
        $amountPaid = $statusResult['amount'] / 100; // Convert paise to rupees

        // Always record the transaction
        \App\Models\PaymentTransaction::create([
            'candidate_id' => $user->id,
            'amount' => $amountPaid,
            'transaction_id' => $transactionId,
            'type' => 'registration_fee',
            'status' => $isSuccess ? 'success' : 'failed',
            'gateway_response' => $statusResult['raw']
        ]);

        // If payment failed, stop here — do NOT update the profile
        if (!$isSuccess) {
            return redirect()->route('candidate.dashboard')->with('error', 'Payment failed or cancelled. Please try again.');
        }

        if (str_starts_with($transactionId, 'RENEW_BASIC_')) {
            // Handle Renewal to Basic/Standard (2 applications)
            $user->profile->update([
                'plan_type' => 'standard',
                'total_allowed_applications' => 2,
                'initial_fee_paid' => true,
                'paid_amount' => $user->profile->paid_amount + $amountPaid,
                'pending_amount' => 500, // Basic plan rule
                'used_applications' => 0, // Reset applications
                'payment_id' => $statusResult['transactionId'],
                'plan_started_at' => now()
            ]);
            return redirect()->route('candidate.dashboard')->with('success', 'Plan Renewed Successfully! You are now on the Standard Plan with 2 application slots.');
        } elseif (str_starts_with($transactionId, 'RENEW_PREMIUM_')) {
            // Handle Renewal to Premium (3 applications)
            $user->profile->update([
                'plan_type' => 'premium',
                'total_allowed_applications' => 3,
                'initial_fee_paid' => true,
                'is_fee_paid' => true,
                'paid_amount' => $user->profile->paid_amount + $amountPaid,
                'pending_amount' => 0, // Premium plan rule
                'used_applications' => 0, // Reset applications
                'payment_id' => $statusResult['transactionId'],
                'plan_started_at' => now()
            ]);
            return redirect()->route('candidate.dashboard')->with('success', 'Plan Renewed Successfully! You are now on the Premium Plan with 3 application slots.');
        } elseif (str_starts_with($transactionId, 'UPGRADE_')) {
            // Handle Upgrade to Premium (3 applications)
            $user->profile->update([
                'plan_type' => 'premium',
                'total_allowed_applications' => 3,
                'is_fee_paid' => true,
                'paid_amount' => $user->profile->paid_amount + $amountPaid,
                'pending_amount' => 0, // Cleared upon upgrade
                'payment_id' => $statusResult['transactionId'],
                'plan_started_at' => now()
            ]);
            return redirect()->route('candidate.dashboard')->with('success', 'Plan Upgraded to Premium Successfully!');
        } else {
            // Handle Initial Registration
            if ($amountPaid == 500) {
                $user->profile->update([
                    'plan_type' => 'standard',
                    'total_allowed_applications' => 2,
                    'initial_fee_paid' => true,
                    'paid_amount' => $user->profile->paid_amount + $amountPaid,
                    'pending_amount' => 500, // Initial 500 paid, 500 pending
                    'payment_id' => $statusResult['transactionId'],
                    'registration_completed_at' => now(),
                    'plan_started_at' => now()
                ]);
            } else {
                $user->profile->update([
                    'plan_type' => 'premium',
                    'total_allowed_applications' => 3,
                    'initial_fee_paid' => true,
                    'is_fee_paid' => true,
                    'paid_amount' => $user->profile->paid_amount + $amountPaid,
                    'payment_id' => $statusResult['transactionId'],
                    'registration_completed_at' => now(),
                    'plan_started_at' => now()
                ]);
            }
        }

        // Notify Admin of Payment Received
        $adminUser = \App\Models\User::where('role', 'admin')->first();
        if ($adminUser) {
            \Illuminate\Support\Facades\DB::table('notifications')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\PaymentReceived',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $adminUser->id,
                'data' => json_encode([
                    'title' => 'Payment Received',
                    'message' => '₹' . $amountPaid . ' was received from ' . $user->name . ' for ' . (str_starts_with($transactionId, 'RENEW_') ? 'Renewal' : (str_starts_with($transactionId, 'UPGRADE_') ? 'Upgrade' : 'Registration')) . '.',
                    'candidate_id' => $user->id,
                    'amount' => $amountPaid
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
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
