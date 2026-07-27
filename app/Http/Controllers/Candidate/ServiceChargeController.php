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

        $transactionId = 'SC_' . $invoice->id . '_' . time();
        session(['sc_invoice_id' => $invoice->id, 'last_txn_id' => $transactionId]);

        $redirectUrl = route('candidate.serviceCharge.callback');

        // Initiate payment via PhonePe V2
        $phonePe = new PhonePeService();
        $result = $phonePe->initiatePay($transactionId, $amount, $redirectUrl);

        if ($result['success']) {
            return redirect()->away($result['redirect_url']);
        }

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

        $transactionId = $request->merchantOrderId ?? $request->transactionId ?? session('last_txn_id');
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

        // Verify status with PhonePe V2
        $phonePe = new PhonePeService();
        $statusResult = $phonePe->checkStatus($transactionId);

        // Log full response for debugging
        \Illuminate\Support\Facades\Log::info('PhonePe V2 Service Charge Callback', [
            'txn' => $transactionId,
            'invoice_id' => $invoiceId,
            'result' => $statusResult,
        ]);

        $isSuccess = $statusResult['success'];
        $amountPaid = $statusResult['amount'] / 100; // Convert paise to rupees

        // Always record transaction
        PaymentTransaction::create([
            'candidate_id' => $user->id,
            'amount' => $amountPaid,
            'transaction_id' => $transactionId,
            'type' => 'service_charge',
            'status' => $isSuccess ? 'success' : 'failed',
            'gateway_response' => $statusResult['raw']
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
