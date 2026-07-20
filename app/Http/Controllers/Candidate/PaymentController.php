<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private $merchantId;
    private $saltKey;
    private $saltIndex;
    private $env;

    public function __construct()
    {
        // Using PhonePe Sandbox details for testing
        $this->merchantId = env('PHONEPE_MERCHANT_ID', 'PGTESTPAYUAT86');
        $this->saltKey = env('PHONEPE_SALT_KEY', '96434309-7796-489d-8924-ab56988a6076');
        $this->saltIndex = env('PHONEPE_SALT_INDEX', '1');
        $this->env = env('PHONEPE_ENV', 'sandbox');
    }

    public function show(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;
        $isRenewal = $request->query('type') === 'renewal';

        if (!$profile->is_profile_complete || !$profile->is_agreement_signed) {
            return redirect()->route('candidate.dashboard')->with('error', 'Please complete previous steps first.');
        }

        // Removed the check that blocked paid users from viewing their plans
        return view('candidate.payment.show', compact('user', 'profile', 'isRenewal'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:basic,premium,renewal,upgrade'
        ]);

        $user = auth()->user();
        $isRenewal = $request->plan === 'renewal';
        $isUpgrade = $request->plan === 'upgrade';
        
        $amount = 500;
        if ($request->plan === 'premium') $amount = 1000;
        if ($isUpgrade) $amount = 500;
        
        $profile = $user->profile;

        // Prevent duplicate payments
        if ($request->plan === 'basic' && $profile->plan_type === 'standard' && ($profile->initial_fee_paid || $profile->is_fee_paid)) {
            return back()->with('error', 'You have already paid for the Basic plan.');
        }
        if (($request->plan === 'premium' || $request->plan === 'upgrade') && $profile->plan_type === 'premium' && $profile->is_fee_paid) {
            return back()->with('error', 'You are already on the Premium plan.');
        }

        $prefix = $isRenewal ? 'RENEW_' : ($isUpgrade ? 'UPGRADE_' : 'TXN_');
        $transactionId = $prefix . $user->id . '_' . time();

        $isProd = $this->env === 'production';

        $payload = [
            'merchantId' => $this->merchantId,
            'merchantTransactionId' => $transactionId,
            'merchantUserId' => 'MUID_' . $user->id,
            'amount' => $amount * 100, // Amount in paise
            'redirectUrl' => route('candidate.payment.callback'),
            'redirectMode' => 'REDIRECT',
            'callbackUrl' => $isProd ? route('candidate.payment.callback') : 'https://webhook.site/phonepe-dummy-callback',
            'mobileNumber' => $user->phone ?? '9999999999',
            'paymentInstrument' => [
                'type' => 'PAY_PAGE'
            ]
        ];

        $encode = base64_encode(json_encode($payload));
        $string = $encode . '/pg/v1/pay' . $this->saltKey;
        $sha256 = hash('sha256', $string);
        $finalXHeader = $sha256 . '###' . $this->saltIndex;

        $url = $isProd 
            ? 'https://api.phonepe.com/apis/hermes/pg/v1/pay'
            : 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay';

        $http = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $finalXHeader,
            'X-MERCHANT-ID' => $this->merchantId
        ]);

        if (!$isProd) {
            $http = $http->withoutVerifying();
        }

        $response = $http->post($url, [
            'request' => $encode
        ]);

        $rData = $response->json();

        if (isset($rData['success']) && $rData['success'] === true) {
            // Save transaction id locally temporarily if needed
            session(['last_txn_id' => $transactionId]);
            return redirect()->away($rData['data']['instrumentResponse']['redirectInfo']['url']);
        }

        return back()->with('error', 'Failed to initiate payment. ' . ($rData['message'] ?? ''));
    }

    public function callback(Request $request)
    {
        $code = $request->code;
        $transactionId = $request->transactionId ?? session('last_txn_id');
        $merchantId = $request->merchantId;

        // Verify status with PhonePe
        $string = "/pg/v1/status/{$this->merchantId}/{$transactionId}" . $this->saltKey;
        $sha256 = hash('sha256', $string);
        $finalXHeader = $sha256 . '###' . $this->saltIndex;

        $isProd = $this->env === 'production';
        $url = $isProd 
            ? "https://api.phonepe.com/apis/hermes/pg/v1/status/{$this->merchantId}/{$transactionId}"
            : "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/{$this->merchantId}/{$transactionId}";

        $http = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $finalXHeader,
            'X-MERCHANT-ID' => $this->merchantId
        ]);

        if (!$isProd) {
            $http = $http->withoutVerifying();
        }

        $response = $http->get($url);

        $rData = $response->json();
        
        \Illuminate\Support\Facades\Log::info('PhonePe Upgrade/Renewal Callback Status', ['response' => $rData, 'txn' => $transactionId]);
        
        $user = auth()->user();

        if ($user) {
            \App\Models\PaymentTransaction::create([
                'candidate_id' => $user->id,
                'amount' => isset($rData['data']['amount']) ? $rData['data']['amount'] / 100 : 0,
                'transaction_id' => $transactionId,
                'type' => 'registration_fee',
                'status' => (isset($rData['success']) && $rData['success'] === true && $rData['data']['state'] === 'COMPLETED') ? 'success' : 'failed',
                'gateway_response' => $rData
            ]);
        }

        if (isset($rData['success']) && $rData['success'] === true && $rData['data']['state'] === 'COMPLETED') {
            
            $amountPaid = $rData['data']['amount'] / 100;

            if (str_starts_with($transactionId, 'RENEW_')) {
                // Handle Renewal
                $user->profile->update([
                    'used_applications' => 0, // Reset applications
                    'payment_id' => $rData['data']['transactionId'],
                    'plan_started_at' => now()
                ]);
                return redirect()->route('candidate.dashboard')->with('success', 'Plan Renewed Successfully! You have new application slots.');
            } elseif (str_starts_with($transactionId, 'UPGRADE_')) {
                // Handle Upgrade
                $user->profile->update([
                    'plan_type' => 'premium',
                    'is_fee_paid' => true,
                    'paid_amount' => $user->profile->paid_amount + $amountPaid,
                    'pending_amount' => 0, // Cleared upon upgrade
                    'payment_id' => $rData['data']['transactionId'],
                    'plan_started_at' => now()
                ]);
                return redirect()->route('candidate.dashboard')->with('success', 'Plan Upgraded to Premium Successfully!');
            } else {
                // Handle Initial Registration
                if ($amountPaid == 500) {
                    $user->profile->update([
                        'plan_type' => 'standard',
                        'initial_fee_paid' => true,
                        'paid_amount' => $user->profile->paid_amount + $amountPaid,
                        'pending_amount' => 500, // Initial 500 paid, 500 pending
                        'payment_id' => $rData['data']['transactionId'],
                        'registration_completed_at' => now(),
                        'plan_started_at' => now()
                    ]);
                } else {
                    $user->profile->update([
                        'plan_type' => 'premium',
                        'initial_fee_paid' => true,
                        'is_fee_paid' => true,
                        'paid_amount' => $user->profile->paid_amount + $amountPaid,
                        'payment_id' => $rData['data']['transactionId'],
                        'registration_completed_at' => now(),
                        'plan_started_at' => now()
                    ]);
                }
                $returnRedirect = redirect()->route('candidate.dashboard')->with('success', 'Payment successful! Your profile is now active and live.');
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

            return $returnRedirect ?? redirect()->route('candidate.dashboard')->with('success', 'Payment processed successfully.');
        }

        return redirect()->route('candidate.dashboard')->with('error', 'Payment failed or cancelled.');
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
