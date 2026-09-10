<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\ServiceChargeInvoice;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use App\Services\PhonePeService;

class ServiceChargeController extends Controller
{
    public function show()
    {
        $candidateId = auth()->id();
        $user = auth()->user();
        $profile = $user ? ($user->profile ?: $user->profile()->firstOrCreate([])) : null;

        // Auto-create pending service charge invoice for standard plan remaining balance ONLY if no invoice exists yet for candidate
        if ($profile && $profile->pending_amount > 0 && !$profile->is_fee_paid) {
            $hasInvoice = ServiceChargeInvoice::where('candidate_id', $candidateId)->exists();

            if (!$hasInvoice) {
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
        
        if ($profile) {
            $profile->loadMissing(['category', 'subject']);
        }

        $invoices = ServiceChargeInvoice::where('candidate_id', $candidateId)
            ->latest()
            ->get();
        
        $paymentHistory = PaymentTransaction::where('candidate_id', $candidateId)
            ->where('type', 'service_charge')
            ->latest()
            ->get();

        $hiredApp = \App\Models\JobApplication::where('candidate_id', $candidateId)
            ->where('status', 'hired')
            ->with('jobPost')
            ->latest()
            ->first();
            
        return view('candidate.serviceCharge.show', compact('invoices', 'paymentHistory', 'profile', 'user', 'hiredApp'));
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

        $transactionId = 'SC_' . $invoice->id . '_' . time();

        // Pre-create pending transaction record in database
        \App\Models\PaymentTransaction::create([
            'candidate_id' => $user->id,
            'amount' => $amount,
            'transaction_id' => $transactionId,
            'type' => 'service_charge',
            'status' => 'pending',
            'gateway_response' => [
                'invoice_id' => $invoice->id,
                'initiated_at' => now()->toIso8601String(),
                'ip' => $request->ip()
            ]
        ]);

        session(['sc_invoice_id' => $invoice->id, 'last_txn_id' => $transactionId]);

        $redirectUrl = route('candidate.serviceCharge.callback');

        // Initiate payment via PhonePe V2
        $phonePe = new PhonePeService();
        $result = $phonePe->initiatePay($transactionId, $amount, $redirectUrl);

        if ($result['success']) {
            return redirect()->away($result['redirect_url']);
        }

        // Mark as failed if initiation failed
        \App\Models\PaymentTransaction::where('transaction_id', $transactionId)->update([
            'status' => 'failed',
            'gateway_response' => ['init_error' => $result['error']]
        ]);

        \Illuminate\Support\Facades\Log::error('PhonePe ServiceCharge Pay Initiation Failed', [
            'error' => $result['error'],
            'raw' => $result['raw'],
        ]);

        return back()->with('error', 'Failed to initiate payment: ' . $result['error']);
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
                    $user->profile->pending_amount = 0;
                    $user->profile->is_fee_paid = true;
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

                // Send email receipt
                try {
                    \Illuminate\Support\Facades\Mail::to($user->email)->send(
                        new \App\Mail\PaymentReceiptMail($user, $request->transactionId, $request->amount, 'Service Charge Invoice Payment')
                    );
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Bypass email dispatch failed: ' . $e->getMessage());
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

        $transactionId = $request->merchantOrderId 
            ?? $request->merchantTransactionId 
            ?? $request->orderId 
            ?? (str_starts_with($request->transactionId ?? '', 'SC_') ? $request->transactionId : null)
            ?? session('last_txn_id');

        // Fallback: If session was lost on mobile and no ID in request, recover active user's pending transaction
        if (!$transactionId && auth()->check()) {
            $latestPending = \App\Models\PaymentTransaction::where('candidate_id', auth()->id())
                ->where('type', 'service_charge')
                ->where('status', 'pending')
                ->where('created_at', '>=', now()->subHours(2))
                ->latest()
                ->first();
            $transactionId = $latestPending?->transaction_id;
        }

        if (!$transactionId) {
            return redirect()->route('candidate.serviceCharge.show')->with('error', 'Payment session expired. Please check your invoice or try again.');
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
        $phonePe = new PhonePeService();
        $statusResult = $phonePe->checkStatus($transactionId);

        \Illuminate\Support\Facades\Log::info('PhonePe V2 Service Charge Callback', [
            'txn' => $transactionId,
            'result' => $statusResult,
        ]);

        $isSuccess = $statusResult['success'] ?? false;
        $isPending = $statusResult['is_pending'] ?? false;
        $amountPaid = ($statusResult['amount'] ?? 0) / 100;

        $fulfillment = \App\Services\PaymentFulfillmentService::fulfill(
            $transactionId,
            $isSuccess,
            $amountPaid,
            $statusResult['raw'] ?? ['is_pending' => $isPending],
            $statusResult['transactionId'] ?? null
        );

        if ($isPending) {
            return redirect()->route('candidate.serviceCharge.show')->with('warning', 'Payment is being processed by your bank. Invoice will be updated shortly.');
        }

        if (!$isSuccess) {
            return redirect()->route('candidate.serviceCharge.show')->with('error', 'Payment failed or was cancelled. Please try again.');
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
