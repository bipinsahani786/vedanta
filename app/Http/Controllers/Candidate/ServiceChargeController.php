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
        $user = auth()->user();
        $profile = $user->profile;

        // Auto-create pending service charge invoice for standard plan remaining balance
        if ($profile && $profile->pending_amount > 0) {
            $hasPending = ServiceChargeInvoice::where('candidate_id', $candidateId)
                ->whereIn('status', ['pending', 'overdue'])
                ->exists();

            if (!$hasPending) {
                $latestApp = \App\Models\JobApplication::where('candidate_id', $candidateId)->latest()->first();

                ServiceChargeInvoice::create([
                    'candidate_id' => $candidateId,
                    'job_application_id' => $latestApp?->id,
                    'amount' => $profile->pending_amount,
                    'late_fee' => 0,
                    'due_date' => now()->addDays(7),
                    'status' => 'pending',
                    'description' => 'Standard Plan Remaining Placement Balance'
                ]);
            }
        }
        
        $invoices = ServiceChargeInvoice::where('candidate_id', $candidateId)
            ->latest()
            ->get();
        
        $paymentHistory = PaymentTransaction::where('candidate_id', $candidateId)
            ->where('type', 'service_charge')
            ->latest()
            ->get();
            
        return view('candidate.serviceCharge.show', compact('invoices', 'paymentHistory', 'profile'));
    }

    public function process(Request $request)
    {
        $request->validate(['invoice_id' => 'required|exists:service_charge_invoices,id']);
        $user = auth()->user();
        
        $invoice = ServiceChargeInvoice::where('id', $request->invoice_id)
            ->where('candidate_id', $user->id)
            ->whereIn('status', ['pending', 'overdue'])
            ->first();

        if (!$invoice) {
            return back()->with('error', 'No pending service charge invoice found.');
        }

        $amount = $invoice->amount + $invoice->late_fee;
        if ($amount <= 0) {
            return back()->with('error', 'Invalid invoice amount.');
        }

        // --- LOCAL BYPASS (Disabled for gateway testing) ---
        // if (env('APP_ENV') === 'local') {
        //     return redirect()->route('candidate.serviceCharge.callback', [
        //         'transactionId' => 'BYPASS_' . time(),
        //         'bypass' => true,
        //         'amount' => $amount
        //     ]);
        // }
        // --------------------

        $merchantId = env('PHONEPE_MERCHANT_ID', 'PGTESTPAYUAT86');
        $saltKey = env('PHONEPE_SALT_KEY', '96434309-7796-489d-8924-ab56988a6076');
        $saltIndex = env('PHONEPE_SALT_INDEX', '1');
        $isProd = env('PHONEPE_ENV', 'sandbox') === 'production';

        $transactionId = 'SC_' . $invoice->id . '_' . time();
        session(['sc_invoice_id' => $invoice->id, 'last_txn_id' => $transactionId]);

        $redirectUrl = route('candidate.serviceCharge.callback');
        $callbackUrl = $isProd ? route('candidate.serviceCharge.callback') : 'https://webhook.site/phonepe-dummy-callback';

        if ($isProd) {
            $redirectUrl = str_replace('http://', 'https://', $redirectUrl);
            $callbackUrl = str_replace('http://', 'https://', $callbackUrl);
        }

        $payload = [
            'merchantId' => $merchantId,
            'merchantTransactionId' => $transactionId,
            'merchantUserId' => 'MUID_' . $user->id,
            'amount' => $amount * 100, // Amount in paise
            'redirectUrl' => $redirectUrl,
            'redirectMode' => 'REDIRECT',
            'callbackUrl' => $callbackUrl,
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
            ? 'https://api.phonepe.com/apis/pg/v1/pay'
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

        \Illuminate\Support\Facades\Log::error('PhonePe ServiceCharge Pay Initiation Failed', [
            'merchantId' => $merchantId,
            'http_status' => $response->status(),
            'response' => $rData,
            'raw_body' => $response->body()
        ]);

        $errorDetails = $rData['message'] ?? $rData['code'] ?? ('HTTP ' . $response->status() . ': ' . $response->body());
        return back()->with('error', 'Failed to initiate payment: ' . $errorDetails);
    }

    public function callback(Request $request)
    {
        $user = auth()->user();
        
        // --- LOCAL BYPASS ---
        if ($request->bypass && env('APP_ENV') === 'local') {
            $invoice = ServiceChargeInvoice::where('candidate_id', $user->id)->whereIn('status', ['pending', 'overdue'])->latest()->first();
            if ($invoice) {
                $invoice->update(['status' => 'paid', 'payment_date' => now()]);
                if ($user->profile) {
                    $user->profile->pending_amount = max(0, $user->profile->pending_amount - $invoice->amount);
                    $user->profile->save();
                }
                PaymentTransaction::create([
                    'candidate_id' => $user->id,
                    'amount' => $request->amount,
                    'transaction_id' => $request->transactionId,
                    'type' => 'service_charge',
                    'status' => 'success',
                    'gateway_response' => ['bypassed' => true]
                ]);

                // Notify Admin
                $adminUser = \App\Models\User::where('role', 'admin')->first();
                if ($adminUser) {
                    \Illuminate\Support\Facades\DB::table('notifications')->insert([
                        'id' => \Illuminate\Support\Str::uuid(),
                        'type' => 'App\Notifications\ServiceChargePaid',
                        'notifiable_type' => 'App\Models\User',
                        'notifiable_id' => $adminUser->id,
                        'data' => json_encode([
                            'title' => 'Service Charge Received',
                            'message' => '₹' . $request->amount . ' was received from ' . $user->name . ' for Service Charge.',
                            'candidate_id' => $user->id,
                            'amount' => $request->amount
                        ]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            return redirect()->route('candidate.serviceCharge.show')->with('success', 'Service charge paid successfully! (Local Bypass)');
        }
        // --------------------

        $transactionId = $request->transactionId ?? session('last_txn_id');
        $invoiceId = session('sc_invoice_id');

        // Guard: If transactionId or user is missing, abort
        if (!$transactionId || !$user) {
            return redirect()->route('candidate.serviceCharge.show')->with('error', 'Payment session expired. Please try again.');
        }

        // Guard: Prevent duplicate processing
        $existingTxn = PaymentTransaction::where('transaction_id', $transactionId)->first();
        if ($existingTxn) {
            if ($existingTxn->status === 'success') {
                return redirect()->route('candidate.serviceCharge.show')->with('success', 'Payment already processed successfully.');
            }
            return redirect()->route('candidate.serviceCharge.show')->with('error', 'Payment failed or was already processed.');
        }
        
        $merchantId = env('PHONEPE_MERCHANT_ID', 'PGTESTPAYUAT86');
        $saltKey = env('PHONEPE_SALT_KEY', '96434309-7796-489d-8924-ab56988a6076');
        $saltIndex = env('PHONEPE_SALT_INDEX', '1');
        $isProd = env('PHONEPE_ENV', 'sandbox') === 'production';

        $string = "/pg/v1/status/{$merchantId}/{$transactionId}" . $saltKey;
        $sha256 = hash('sha256', $string);
        $finalXHeader = $sha256 . '###' . $saltIndex;

        $url = $isProd 
            ? "https://api.phonepe.com/apis/pg/v1/status/{$merchantId}/{$transactionId}"
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

        // Log full response for debugging
        \Illuminate\Support\Facades\Log::info('PhonePe Service Charge Callback', [
            'txn' => $transactionId,
            'invoice_id' => $invoiceId,
            'gateway_response' => $rData
        ]);

        // Check gateway response — ONLY COMPLETED means success
        $isSuccess = isset($rData['success']) 
            && $rData['success'] === true 
            && isset($rData['data']['state']) 
            && $rData['data']['state'] === 'COMPLETED';

        $amountPaid = isset($rData['data']['amount']) ? $rData['data']['amount'] / 100 : 0;

        // Always record transaction
        PaymentTransaction::create([
            'candidate_id' => $user->id,
            'amount' => $amountPaid,
            'transaction_id' => $transactionId,
            'type' => 'service_charge',
            'status' => $isSuccess ? 'success' : 'failed',
            'gateway_response' => $rData
        ]);

        // If payment failed, stop here — do NOT update invoice or profile
        if (!$isSuccess) {
            return redirect()->route('candidate.serviceCharge.show')->with('error', 'Payment failed or cancelled. Please try again.');
        }

        // Payment confirmed COMPLETED — update invoice and profile
        if ($invoiceId) {
            ServiceChargeInvoice::where('id', $invoiceId)->update([
                'status' => 'paid',
                'payment_date' => now()
            ]);
            $inv = ServiceChargeInvoice::find($invoiceId);
            if ($inv && $user->profile) {
                $user->profile->pending_amount = max(0, $user->profile->pending_amount - $inv->amount);
                if ($user->profile->pending_amount <= 0) {
                    $user->profile->is_fee_paid = true;
                }
                $user->profile->save();
            }
        } else {
            // Fallback to latest pending invoice
            $latestInvoice = ServiceChargeInvoice::where('candidate_id', $user->id)
                ->whereIn('status', ['pending', 'overdue'])
                ->latest()
                ->first();
            if ($latestInvoice) {
                $latestInvoice->update(['status' => 'paid', 'payment_date' => now()]);
                if ($user->profile) {
                    $user->profile->pending_amount = max(0, $user->profile->pending_amount - $latestInvoice->amount);
                    if ($user->profile->pending_amount <= 0) {
                        $user->profile->is_fee_paid = true;
                    }
                    $user->profile->save();
                }
            }
        }

        // Notify Admin
        $adminUser = \App\Models\User::where('role', 'admin')->first();
        if ($adminUser) {
            \Illuminate\Support\Facades\DB::table('notifications')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\ServiceChargePaid',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $adminUser->id,
                'data' => json_encode([
                    'title' => 'Service Charge Received',
                    'message' => '₹' . $amountPaid . ' was received from ' . $user->name . ' for Service Charge.',
                    'candidate_id' => $user->id,
                    'amount' => $amountPaid
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('candidate.serviceCharge.show')->with('success', 'Service charge paid successfully!');
    }

    public function downloadInvoicePdf($id)
    {
        $candidateId = auth()->id();
        $invoice = ServiceChargeInvoice::where('id', $id)
            ->where('candidate_id', $candidateId)
            ->with(['jobApplication.jobPost', 'candidate'])
            ->firstOrFail();

        $user = auth()->user();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('candidate.serviceCharge.invoice_pdf', [
            'invoice' => $invoice,
            'user' => $user
        ]);

        return $pdf->download('Service-Charge-Invoice-' . $invoice->id . '.pdf');
    }
}
