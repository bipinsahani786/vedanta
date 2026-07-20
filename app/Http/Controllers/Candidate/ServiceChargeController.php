<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\ServiceChargeInvoice;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class ServiceChargeController extends Controller
{
    public function show()
    {
        $candidateId = auth()->id();
        
        $invoice = ServiceChargeInvoice::where('candidate_id', $candidateId)->latest()->first();
        
        $paymentHistory = PaymentTransaction::where('candidate_id', $candidateId)
            ->where('type', 'service_charge')
            ->latest()
            ->get();
            
        return view('candidate.serviceCharge.show', compact('invoice', 'paymentHistory'));
    }

    public function process(Request $request)
    {
        $user = auth()->user();
        $invoice = ServiceChargeInvoice::where('candidate_id', $user->id)
            ->whereIn('status', ['pending', 'overdue'])
            ->latest()
            ->first();

        if (!$invoice) {
            return back()->with('error', 'No pending service charge invoice found.');
        }

        $amount = $invoice->amount + $invoice->late_fee;
        if ($amount <= 0) {
            return back()->with('error', 'Invalid invoice amount.');
        }

        // --- LOCAL BYPASS ---
        if (env('APP_ENV') === 'local') {
            return redirect()->route('candidate.serviceCharge.callback', [
                'transactionId' => 'BYPASS_' . time(),
                'bypass' => true,
                'amount' => $amount
            ]);
        }
        // --------------------

        $merchantId = env('PHONEPE_MERCHANT_ID', 'PGTESTPAYUAT86');
        $saltKey = env('PHONEPE_SALT_KEY', '96434309-7796-489d-8924-ab56988a6076');
        $saltIndex = env('PHONEPE_SALT_INDEX', '1');
        $isProd = env('PHONEPE_ENV', 'sandbox') === 'production';

        $transactionId = 'SC_' . $invoice->id . '_' . time();
        session(['sc_invoice_id' => $invoice->id, 'last_txn_id' => $transactionId]);

        $payload = [
            'merchantId' => $merchantId,
            'merchantTransactionId' => $transactionId,
            'merchantUserId' => 'MUID_' . $user->id,
            'amount' => $amount * 100, // Amount in paise
            'redirectUrl' => route('candidate.serviceCharge.callback'),
            'redirectMode' => 'REDIRECT',
            'callbackUrl' => $isProd ? route('candidate.serviceCharge.callback') : 'https://webhook.site/phonepe-dummy-callback',
            'mobileNumber' => $user->phone ?? '9999999999',
            'paymentInstrument' => [
                'type' => 'PAY_PAGE'
            ]
        ];

        $encode = base64_encode(json_encode($payload));
        $string = $encode . '/pg/v1/pay' . $saltKey;
        $sha256 = hash('sha256', $string);
        $finalXHeader = $sha256 . '###' . $saltIndex;

        $url = $isProd 
            ? 'https://api.phonepe.com/apis/hermes/pg/v1/pay'
            : 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay';

        $http = \Illuminate\Support\Facades\Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $finalXHeader,
            'X-MERCHANT-ID' => $merchantId
        ]);

        if (!$isProd) {
            $http = $http->withoutVerifying();
        }

        $response = $http->post($url, [
            'request' => $encode
        ]);

        $rData = $response->json();

        if (isset($rData['success']) && $rData['success'] === true) {
            return redirect()->away($rData['data']['instrumentResponse']['redirectInfo']['url']);
        }

        return back()->with('error', 'Failed to initiate payment. ' . ($rData['message'] ?? ''));
    }

    public function callback(Request $request)
    {
        $user = auth()->user();
        
        // --- LOCAL BYPASS ---
        if ($request->bypass && env('APP_ENV') === 'local') {
            $invoice = ServiceChargeInvoice::where('candidate_id', $user->id)->whereIn('status', ['pending', 'overdue'])->latest()->first();
            if ($invoice) {
                $invoice->update(['status' => 'paid', 'paid_at' => now()]);
                PaymentTransaction::create([
                    'candidate_id' => $user->id,
                    'amount' => $request->amount,
                    'transaction_id' => $request->transactionId,
                    'type' => 'service_charge',
                    'status' => 'success',
                    'gateway_response' => ['bypassed' => true]
                ]);
            }
            return redirect()->route('candidate.serviceCharge.show')->with('success', 'Service charge paid successfully! (Local Bypass)');
        }
        // --------------------

        $transactionId = $request->transactionId ?? session('last_txn_id');
        $invoiceId = session('sc_invoice_id');
        
        $merchantId = env('PHONEPE_MERCHANT_ID', 'PGTESTPAYUAT86');
        $saltKey = env('PHONEPE_SALT_KEY', '96434309-7796-489d-8924-ab56988a6076');
        $saltIndex = env('PHONEPE_SALT_INDEX', '1');
        $isProd = env('PHONEPE_ENV', 'sandbox') === 'production';

        $string = "/pg/v1/status/{$merchantId}/{$transactionId}" . $saltKey;
        $sha256 = hash('sha256', $string);
        $finalXHeader = $sha256 . '###' . $saltIndex;

        $url = $isProd 
            ? "https://api.phonepe.com/apis/hermes/pg/v1/status/{$merchantId}/{$transactionId}"
            : "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/{$merchantId}/{$transactionId}";

        $http = \Illuminate\Support\Facades\Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $finalXHeader,
            'X-MERCHANT-ID' => $merchantId
        ]);

        if (!$isProd) {
            $http = $http->withoutVerifying();
        }

        $response = $http->get($url);
        $rData = $response->json();

        if ($user) {
            PaymentTransaction::create([
                'candidate_id' => $user->id,
                'amount' => isset($rData['data']['amount']) ? $rData['data']['amount'] / 100 : 0,
                'transaction_id' => $transactionId,
                'type' => 'service_charge',
                'status' => (isset($rData['success']) && $rData['success'] === true && $rData['data']['state'] === 'COMPLETED') ? 'success' : 'failed',
                'gateway_response' => $rData
            ]);
        }

        if (isset($rData['success']) && $rData['success'] === true && $rData['data']['state'] === 'COMPLETED') {
            if ($invoiceId) {
                ServiceChargeInvoice::where('id', $invoiceId)->update([
                    'status' => 'paid',
                    'paid_at' => now()
                ]);
            } else {
                // Fallback to latest pending invoice
                ServiceChargeInvoice::where('candidate_id', $user->id)->whereIn('status', ['pending', 'overdue'])->update([
                    'status' => 'paid',
                    'paid_at' => now()
                ]);
            }
            return redirect()->route('candidate.serviceCharge.show')->with('success', 'Service charge paid successfully!');
        }

        return redirect()->route('candidate.serviceCharge.show')->with('error', 'Payment failed or cancelled.');
    }
}
