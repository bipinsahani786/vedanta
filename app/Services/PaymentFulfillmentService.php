<?php

namespace App\Services;

use App\Models\User;
use App\Models\CandidateProfile;
use App\Models\PaymentTransaction;
use App\Models\ServiceChargeInvoice;
use App\Mail\PaymentReceiptMail;
use App\Mail\RegistrationSuccessMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentFulfillmentService
{
    /**
     * Fulfill a payment transaction reliably from Callback or Webhook.
     *
     * @param string $transactionId
     * @param bool $isSuccess
     * @param float $amountPaid Amount in rupees
     * @param array $gatewayResponse
     * @param string|null $gatewayTxnId
     * @param string|null $pendingPlanType Optional override for wizard plan choice ('standard' or 'premium')
     * @return array ['success' => bool, 'message' => string, 'user' => User|null]
     */
    public static function fulfill(
        string $transactionId,
        bool $isSuccess,
        float $amountPaid,
        array $gatewayResponse = [],
        ?string $gatewayTxnId = null,
        ?string $pendingPlanType = null
    ): array {
        Log::info('PaymentFulfillmentService: Processing fulfillment', [
            'txn_id' => $transactionId,
            'is_success' => $isSuccess,
            'amount' => $amountPaid,
        ]);

        // Find candidate ID from transaction ID prefix or existing transaction record
        $candidateId = self::extractCandidateId($transactionId);
        
        $existingTxn = PaymentTransaction::where('transaction_id', $transactionId)->first();
        
        if (!$candidateId && $existingTxn) {
            $candidateId = $existingTxn->candidate_id;
        }

        $user = $candidateId ? User::find($candidateId) : null;

        // IDEMPOTENCY GUARD: If this transaction was already processed as 'success', skip everything.
        // This prevents double-counting paid_amount when both Webhook and Callback fire.
        $alreadyFulfilled = $existingTxn && $existingTxn->status === 'success';

        // 1. Record or update PaymentTransaction
        if (!$existingTxn) {
            $type = 'registration_fee';
            if (str_starts_with($transactionId, 'SC_')) {
                $type = 'service_charge';
            }
            $existingTxn = PaymentTransaction::create([
                'candidate_id' => $candidateId,
                'amount' => $amountPaid,
                'transaction_id' => $transactionId,
                'type' => $type,
                'status' => $isSuccess ? 'success' : 'failed',
                'gateway_response' => $gatewayResponse
            ]);
        } else if (!$alreadyFulfilled) {
            // Only update if not already successfully processed
            $existingTxn->update([
                'status' => $isSuccess ? 'success' : 'failed',
                'amount' => $amountPaid > 0 ? $amountPaid : $existingTxn->amount,
                'gateway_response' => !empty($gatewayResponse) ? $gatewayResponse : $existingTxn->gateway_response
            ]);
        }

        if (!$isSuccess) {
            return [
                'success' => false,
                'message' => 'Payment failed or was cancelled.',
                'user' => $user
            ];
        }

        // If already fulfilled, return success but skip profile updates & emails
        if ($alreadyFulfilled) {
            Log::info('PaymentFulfillmentService: Transaction already fulfilled, skipping.', [
                'txn_id' => $transactionId
            ]);
            return [
                'success' => true,
                'message' => 'Payment already fulfilled.',
                'user' => $user
            ];
        }

        if (!$user || !$user->profile) {
            Log::error('PaymentFulfillmentService: User or Profile not found for transaction', [
                'txn_id' => $transactionId,
                'candidate_id' => $candidateId
            ]);
            return [
                'success' => true,
                'message' => 'Payment recorded, but user profile not found.',
                'user' => null
            ];
        }

        $profile = $user->profile;
        $txnId = $gatewayTxnId ?? $transactionId;

        // 2. Fulfill based on Transaction Type
        if (str_starts_with($transactionId, 'SC_')) {
            // --- SERVICE CHARGE INVOICE PAYMENT ---
            $parts = explode('_', $transactionId);
            $invoiceId = (count($parts) >= 2) ? $parts[1] : null;
            $invoice = $invoiceId ? ServiceChargeInvoice::find($invoiceId) : null;

            if (!$invoice) {
                $invoice = ServiceChargeInvoice::where('candidate_id', $user->id)
                    ->whereIn('status', ['pending', 'overdue'])
                    ->latest()
                    ->first();
            }

            if ($invoice && $invoice->status !== 'paid') {
                $invoice->update([
                    'status' => 'paid',
                    'payment_date' => now()
                ]);

                $profile->pending_amount = max(0, $profile->pending_amount - $invoice->amount);
                if ($profile->pending_amount <= 0) {
                    $profile->is_fee_paid = true;
                }
                if ($profile->placed_status !== 'placed') {
                    $profile->placed_status = 'placed';
                }
                $profile->save();

                self::sendNotification($user, 'Service Charge Received', "₹{$amountPaid} was received for Service Charge.");
                self::sendEmailOnce($transactionId, 'receipt', function() use ($user, $transactionId, $amountPaid) {
                    Mail::to($user->email)->send(new PaymentReceiptMail($user, $transactionId, $amountPaid, 'Service Charge Invoice Payment'));
                });
            }

        } elseif (str_starts_with($transactionId, 'UPGRADE_')) {
            // --- UPGRADE TO PREMIUM PLAN ---
            $profile->update([
                'plan_type' => 'premium',
                'total_allowed_applications' => 3,
                'initial_fee_paid' => true,
                'is_fee_paid' => true,
                'paid_amount' => $profile->paid_amount + $amountPaid,
                'pending_amount' => 0,
                'payment_id' => $txnId,
                'plan_started_at' => now(),
            ]);

            self::sendNotification($user, 'Plan Upgraded to Premium', 'Your plan has been upgraded to Premium with 3 application slots.');
            self::sendEmailOnce($transactionId, 'upgrade_receipt', function() use ($user, $transactionId, $amountPaid) {
                Mail::to($user->email)->send(new PaymentReceiptMail($user, $transactionId, $amountPaid, 'Upgrade to Premium Plan'));
            });

        } elseif (str_starts_with($transactionId, 'RENEW_BASIC_')) {
            // --- RENEWAL TO BASIC / STANDARD PLAN ---
            $profile->update([
                'plan_type' => 'standard',
                'total_allowed_applications' => 2,
                'used_applications' => 0, // Reset application count for new cycle
                'initial_fee_paid' => true,
                'paid_amount' => $profile->paid_amount + $amountPaid,
                'pending_amount' => 500,
                'payment_id' => $txnId,
                'plan_started_at' => now(),
            ]);

            self::sendNotification($user, 'Plan Renewed Successfully', 'Basic Plan renewed with 2 application slots.');
            self::sendEmailOnce($transactionId, 'renew_basic_receipt', function() use ($user, $transactionId, $amountPaid) {
                Mail::to($user->email)->send(new PaymentReceiptMail($user, $transactionId, $amountPaid, 'Basic Plan Renewal'));
            });

        } elseif (str_starts_with($transactionId, 'RENEW_PREMIUM_')) {
            // --- RENEWAL TO PREMIUM PLAN ---
            $profile->update([
                'plan_type' => 'premium',
                'total_allowed_applications' => 3,
                'used_applications' => 0, // Reset application count for new cycle
                'initial_fee_paid' => true,
                'is_fee_paid' => true,
                'paid_amount' => $profile->paid_amount + $amountPaid,
                'pending_amount' => 0,
                'payment_id' => $txnId,
                'plan_started_at' => now(),
            ]);

            self::sendNotification($user, 'Plan Renewed Successfully', 'Premium Plan renewed with 3 application slots.');
            self::sendEmailOnce($transactionId, 'renew_premium_receipt', function() use ($user, $transactionId, $amountPaid) {
                Mail::to($user->email)->send(new PaymentReceiptMail($user, $transactionId, $amountPaid, 'Premium Plan Renewal'));
            });

        } else {
            // --- INITIAL REGISTRATION / WIZARD PAYMENT (TXN_) ---
            $isPremium = ($pendingPlanType === 'premium') || ($amountPaid >= 1000);

            if ($isPremium) {
                $profile->update([
                    'plan_type' => 'premium',
                    'total_allowed_applications' => 3,
                    'initial_fee_paid' => true,
                    'is_fee_paid' => true,
                    'paid_amount' => $profile->paid_amount + $amountPaid,
                    'pending_amount' => 0,
                    'payment_id' => $txnId,
                    'registration_step' => 'completed',
                    'verified' => true,
                    'registration_completed_at' => $profile->registration_completed_at ?? now(),
                    'plan_started_at' => $profile->plan_started_at ?? now(),
                ]);
            } else {
                $profile->update([
                    'plan_type' => 'standard',
                    'total_allowed_applications' => 2,
                    'initial_fee_paid' => true,
                    'paid_amount' => $profile->paid_amount + $amountPaid,
                    'pending_amount' => 500,
                    'payment_id' => $txnId,
                    'registration_step' => 'completed',
                    'verified' => true,
                    'registration_completed_at' => $profile->registration_completed_at ?? now(),
                    'plan_started_at' => $profile->plan_started_at ?? now(),
                ]);
            }

            self::sendNotification($user, 'Registration Successful', 'Welcome to Vedanta! Your registration plan is now active.');
            self::sendEmailOnce($transactionId, 'welcome_emails', function() use ($user, $transactionId, $amountPaid) {
                Mail::to($user->email)->send(new PaymentReceiptMail($user, $transactionId, $amountPaid, 'Candidate Profile Registration Fee'));
                Mail::to($user->email)->send(new RegistrationSuccessMail($user));
            });
        }

        return [
            'success' => true,
            'message' => 'Payment fulfilled successfully.',
            'user' => $user
        ];
    }

    /**
     * Safely extract Candidate ID from transaction string
     */
    public static function extractCandidateId(string $transactionId): ?int
    {
        $parts = explode('_', $transactionId);

        if (str_starts_with($transactionId, 'SC_')) {
            if (count($parts) >= 2) {
                $invoice = ServiceChargeInvoice::find($parts[1]);
                return $invoice ? $invoice->candidate_id : null;
            }
            return null;
        }

        if (count($parts) >= 3) {
            $possibleId = $parts[count($parts) - 2];
            if (is_numeric($possibleId)) {
                return (int) $possibleId;
            }
        }

        return null;
    }

    private static function sendNotification(User $user, string $title, string $message)
    {
        $adminUser = User::where('role', 'admin')->first();
        if ($adminUser) {
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => 'App\Notifications\PaymentReceived',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $adminUser->id,
                'data' => json_encode([
                    'title' => $title,
                    'message' => "{$message} (Candidate: {$user->name})",
                    'candidate_id' => $user->id
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private static function sendEmailOnce(string $transactionId, string $emailType, callable $sendCallable)
    {
        $cacheKey = "email_sent_{$transactionId}_{$emailType}";
        if (!cache()->has($cacheKey)) {
            cache()->put($cacheKey, true, now()->addDays(7));
            try {
                $sendCallable();
            } catch (\Throwable $e) {
                Log::error("Failed to send email {$emailType} for transaction {$transactionId}: " . $e->getMessage());
            }
        }
    }
}
