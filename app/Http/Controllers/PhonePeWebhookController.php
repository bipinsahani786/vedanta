<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\PhonePeService;
use App\Models\User;
use App\Models\ServiceChargeInvoice;
use App\Models\PaymentTransaction;
use App\Models\CandidateProfile;
use App\Mail\PaymentReceiptMail;
use App\Mail\RegistrationSuccessMail;

class PhonePeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('PhonePe S2S Webhook Received', ['payload' => $request->all()]);

        $transactionId = null;

        if ($request->has('response')) {
            $decoded = json_decode(base64_decode($request->response), true);
            $transactionId = $decoded['data']['merchantTransactionId'] ?? $decoded['data']['merchantOrderId'] ?? null;
        } elseif ($request->has('merchantOrderId')) {
            $transactionId = $request->merchantOrderId;
        } elseif ($request->has('merchantTransactionId')) {
            $transactionId = $request->merchantTransactionId;
        }

        if (!$transactionId) {
            Log::error('PhonePe Webhook: Could not extract transaction ID', ['payload' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Invalid Payload'], 400);
        }

        $phonePe = new PhonePeService();
        $statusResult = $phonePe->checkStatus($transactionId);

        Log::info('PhonePe Webhook Status Check', ['txn' => $transactionId, 'result' => $statusResult]);

        $isSuccess = $statusResult['success'] ?? false;
        $amountPaid = ($statusResult['amount'] ?? 0) / 100;

        if (str_starts_with($transactionId, 'SC_')) {
            $this->processServiceCharge($transactionId, $isSuccess, $amountPaid, $statusResult['raw']);
        } elseif (str_starts_with($transactionId, 'TXN_')) {
            $this->processWizardPayment($transactionId, $isSuccess, $amountPaid, $statusResult['raw']);
        } elseif (str_starts_with($transactionId, 'UPGRADE_') || str_starts_with($transactionId, 'RENEW_')) {
            $this->processUpgradePayment($transactionId, $isSuccess, $amountPaid, $statusResult['raw']);
        } else {
            Log::warning('PhonePe Webhook: Unknown prefix for TXN', ['txn' => $transactionId]);
        }

        return response()->json(['success' => true]);
    }

    private function processServiceCharge($transactionId, $isSuccess, $amountPaid, $rawResponse)
    {
        $parts = explode('_', $transactionId);
        if (count($parts) < 3) return;

        $invoiceId = $parts[1];
        $invoice = ServiceChargeInvoice::find($invoiceId);

        if (!$invoice) return;

        $userId = $invoice->candidate_id;
        $user = User::find($userId);

        if ($invoice->status === 'paid') return;

        $needsEmail = false;
        $existingTxn = PaymentTransaction::where('transaction_id', $transactionId)->first();
        if (!$existingTxn) {
            PaymentTransaction::create([
                'candidate_id' => $userId,
                'amount' => $amountPaid,
                'transaction_id' => $transactionId,
                'type' => 'service_charge',
                'status' => $isSuccess ? 'success' : 'failed',
                'gateway_response' => $rawResponse
            ]);
            $needsEmail = $isSuccess;
        } else if ($existingTxn->status === 'success') {
            return;
        } else if ($isSuccess && $existingTxn->status !== 'success') {
             $existingTxn->update(['status' => 'success', 'gateway_response' => $rawResponse]);
             $needsEmail = true;
        }

        if ($isSuccess) {
            $invoice->update([
                'status' => 'paid',
                'payment_date' => now()
            ]);
            
            if ($user && $user->profile) {
                if ($user->profile->placed_status !== 'placed') {
                    $user->profile->update(['placed_status' => 'placed']);
                }
            }
            Log::info('PhonePe Webhook: Service Charge Processed successfully.', ['invoice_id' => $invoiceId]);
            
            if ($needsEmail && $user) {
                Mail::to($user->email)->send(new PaymentReceiptMail($user, $transactionId, $amountPaid, 'Service Charge Invoice Payment'));
            }
        }
    }

    private function processWizardPayment($transactionId, $isSuccess, $amountPaid, $rawResponse)
    {
        $parts = explode('_', $transactionId);
        if (count($parts) < 3) return;

        $userId = $parts[1];
        $user = User::find($userId);

        if (!$user || !$user->profile) return;
        
        $needsEmail = false;

        $existing = PaymentTransaction::where('transaction_id', $transactionId)->first();
        if (!$existing) {
            PaymentTransaction::create([
                'candidate_id' => $userId,
                'transaction_id' => $transactionId,
                'amount' => $amountPaid,
                'type' => 'registration_fee',
                'status' => $isSuccess ? 'success' : 'failed',
                'gateway_response' => $rawResponse
            ]);
            $needsEmail = $isSuccess;
        } else if ($existing->status === 'success') {
            return;
        } else if ($isSuccess && $existing->status !== 'success') {
             $existing->update(['status' => 'success', 'gateway_response' => $rawResponse]);
             $needsEmail = true;
        }

        if ($isSuccess) {
            $profile = $user->profile;
            if (!$profile->initial_fee_paid) {
                $isFullPay = $amountPaid >= 1000;
                $profile->update([
                    'initial_fee_paid' => true,
                    'is_fee_paid' => $isFullPay,
                    'paid_amount' => $profile->paid_amount + $amountPaid,
                    'pending_amount' => $isFullPay ? 0 : 500,
                    'plan_type' => $isFullPay ? 'premium' : 'standard',
                    'registration_step' => 'completed',
                    'verified' => true
                ]);

                Log::info('PhonePe Webhook: Wizard Payment Processed successfully.', ['user_id' => $userId]);
            }
            
            if ($needsEmail) {
                Mail::to($user->email)->send(new PaymentReceiptMail($user, $transactionId, $amountPaid, 'Candidate Profile Registration Fee'));
                Mail::to($user->email)->send(new RegistrationSuccessMail($user));
            }
        }
    }

    private function processUpgradePayment($transactionId, $isSuccess, $amountPaid, $rawResponse)
    {
        $parts = explode('_', $transactionId);
        if (count($parts) < 3) return;

        $userId = (int) $parts[count($parts) - 2];
        $user = User::find($userId);

        if (!$user || !$user->profile) return;
        
        $needsEmail = false;

        $existing = PaymentTransaction::where('transaction_id', $transactionId)->first();
        if (!$existing) {
            PaymentTransaction::create([
                'candidate_id' => $userId,
                'transaction_id' => $transactionId,
                'amount' => $amountPaid,
                'type' => 'registration_fee', // Treat upgrade as registration fee txn
                'status' => $isSuccess ? 'success' : 'failed',
                'gateway_response' => $rawResponse
            ]);
            $needsEmail = $isSuccess;
        } else if ($existing->status === 'success') {
            return;
        } else if ($isSuccess && $existing->status !== 'success') {
             $existing->update(['status' => 'success', 'gateway_response' => $rawResponse]);
             $needsEmail = true;
        }

        if ($isSuccess) {
            $profile = $user->profile;
            $desc = 'Profile Renewal / Upgrade';
            
            if (str_starts_with($transactionId, 'UPGRADE_')) {
                $profile->update([
                    'plan_type' => 'premium',
                    'paid_amount' => $profile->paid_amount + $amountPaid,
                    'pending_amount' => 0,
                    'is_fee_paid' => true,
                    'initial_fee_paid' => true
                ]);
                $desc = 'Upgrade to Premium Plan';
            } elseif (str_starts_with($transactionId, 'RENEW_BASIC_')) {
                $profile->increment('total_allowed_applications', 2);
                $desc = 'Basic Plan Renewal';
            } elseif (str_starts_with($transactionId, 'RENEW_PREMIUM_')) {
                $profile->update(['is_fee_paid' => true]);
                $desc = 'Premium Plan Renewal';
            }

            Log::info('PhonePe Webhook: Upgrade/Renewal Processed successfully.', ['user_id' => $userId]);
            
            if ($needsEmail) {
                Mail::to($user->email)->send(new PaymentReceiptMail($user, $transactionId, $amountPaid, $desc));
            }
        }
    }
}
