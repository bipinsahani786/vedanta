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
        $this->merchantId = env('PHONEPE_MERCHANT_ID', 'PGTESTPAYUAT');
        $this->saltKey = env('PHONEPE_SALT_KEY', '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399');
        $this->saltIndex = env('PHONEPE_SALT_INDEX', '1');
        $this->env = env('PHONEPE_ENV', 'sandbox');
    }

    public function show()
    {
        $user = auth()->user();
        $profile = $user->profile;

        if (!$profile->is_profile_complete || !$profile->is_agreement_signed) {
            return redirect()->route('candidate.dashboard')->with('error', 'Please complete previous steps first.');
        }

        if ($profile->is_fee_paid) {
            return redirect()->route('candidate.dashboard')->with('info', 'Your fee is already paid. Profile is active.');
        }

        return view('candidate.payment.show', compact('user', 'profile'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:basic,premium'
        ]);

        $user = auth()->user();
        $amount = $request->plan === 'basic' ? 500 : 1500; // Mock amounts
        $transactionId = 'TXN_' . $user->id . '_' . time();

        $payload = [
            'merchantId' => $this->merchantId,
            'merchantTransactionId' => $transactionId,
            'merchantUserId' => 'MUID_' . $user->id,
            'amount' => $amount * 100, // Amount in paise
            'redirectUrl' => route('candidate.payment.callback'),
            'redirectMode' => 'POST',
            'callbackUrl' => route('candidate.payment.callback'),
            'mobileNumber' => $user->phone,
            'paymentInstrument' => [
                'type' => 'PAY_PAGE'
            ]
        ];

        $encode = base64_encode(json_encode($payload));
        $string = $encode . '/pg/v1/pay' . $this->saltKey;
        $sha256 = hash('sha256', $string);
        $finalXHeader = $sha256 . '###' . $this->saltIndex;

        $url = $this->env === 'production' 
            ? 'https://api.phonepe.com/apis/hermes/pg/v1/pay'
            : 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $finalXHeader,
            'X-MERCHANT-ID' => $this->merchantId
        ])->post($url, [
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

        $url = $this->env === 'production' 
            ? "https://api.phonepe.com/apis/hermes/pg/v1/status/{$this->merchantId}/{$transactionId}"
            : "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/{$this->merchantId}/{$transactionId}";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $finalXHeader,
            'X-MERCHANT-ID' => $this->merchantId
        ])->get($url);

        $rData = $response->json();

        if (isset($rData['success']) && $rData['success'] === true && $rData['data']['state'] === 'COMPLETED') {
            
            $user = auth()->user();
            $user->profile->update([
                'is_fee_paid' => true,
                'payment_id' => $rData['data']['transactionId'],
                'registration_completed_at' => now()
            ]);

            return redirect()->route('candidate.dashboard')->with('success', 'Payment successful! Your profile is now active and live.');
        }

        return redirect()->route('candidate.dashboard')->with('error', 'Payment failed or cancelled.');
    }
}
