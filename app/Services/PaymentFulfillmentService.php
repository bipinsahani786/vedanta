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
     * Fulfill a payment transaction reliably from Callback, Webhook, or Repair command.
     *
     * @param string $transactionId
     * @param bool $isSuccess
     * @param float $amountPaid Amount in rupees
     * @param array $gatewayResponse
     * @param string|null $gatewayTxnId
     * @param string|null $pendingPlanType Optional override for wizard plan choice ('standard' or 'premium')
     * @param bool $forceReFulfill
     * @return array ['success' => bool, 'is_pending' => bool, 'message' => string, 'user' => User|null]
     */
    public static function fulfill(
        string $transactionId,
        bool $isSuccess,
        float $amountPaid,
        array $gatewayResponse = [],
        ?string $gatewayTxnId = null,
        ?string $pendingPlanType = null,
        bool $forceReFulfill = false
    ): array {
        $isPending = $gatewayResponse['is_pending'] ?? false;

        Log::info('PaymentFulfillmentService: Processing fulfillment', [
            'txn_id' => $transactionId,
            'is_success' => $isSuccess,
            'is_pending' => $isPending,
            'amount' => $amountPaid,
            'force' => $forceReFulfill
        ]);

        // Find candidate ID from transaction record or ID pattern
        $candidateId = self::extractCandidateId($transactionId);
        
        $existingTxn = PaymentTransaction::where('transaction_id', $transactionId)->first();
        
        if (!$candidateId && $existingTxn) {
            $candidateId = $existingTxn->candidate_id;
        }

        // Recover stored plan_type metadata if not passed explicitly
        if (!$pendingPlanType && $existingTxn && is_array($existingTxn->gateway_response)) {
            $pendingPlanType = $existingTxn->gateway_response['plan_type'] ?? null;
        }

        $user = $candidateId ? User::find($candidateId) : null;

        // IDEMPOTENCY GUARD: If this transaction was already processed as 'success', skip everything.
        // Bypassed if forceReFulfill is true (e.g. from payments:repair command).
        $alreadyFulfilled = !$forceReFulfill && $existingTxn && $existingTxn->status === 'success';

        // 1. Record or update PaymentTransaction
        $newStatus = $isSuccess ? 'success' : ($isPending ? 'pending' : 'failed');

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
                'status' => $newStatus,
                'gateway_response' => !empty($gatewayResponse) ? $gatewayResponse : ['plan_type' => $pendingPlanType]
            ]);
        } else if (!$alreadyFulfilled) {
            // Only update if not already successfully processed
            $mergedResponse = is_array($existingTxn->gateway_response) ? $existingTxn->gateway_response : [];
            if (!empty($gatewayResponse)) {
                $mergedResponse = array_merge($mergedResponse, $gatewayResponse);
            }
            if ($pendingPlanType && !isset($mergedResponse['plan_type'])) {
                $mergedResponse['plan_type'] = $pendingPlanType;
            }

            $existingTxn->update([
                'candidate_id' => $existingTxn->candidate_id ?? $candidateId,
                'status' => $newStatus,
                'amount' => $amountPaid > 0 ? $amountPaid : $existingTxn->amount,
                'gateway_response' => $mergedResponse
            ]);
        }

        // Handle Pending State
        if ($isPending) {
            return [
                'success' => false,
                'is_pending' => true,
                'message' => 'Payment is processing. Please wait a moment.',
                'user' => $user
            ];
        }

        // Handle Failed State
        if (!$isSuccess) {
            return [
                'success' => false,
                'is_pending' => false,
                'message' => 'Payment failed or was cancelled.',
                'user' => $user
            ];
        }

        // If already fulfilled, return success but skip duplicate profile updates & emails
        if ($alreadyFulfilled) {
            Log::info('PaymentFulfillmentService: Transaction already fulfilled, skipping duplicate update.', [
                'txn_id' => $transactionId
            ]);
            return [
                'success' => true,
                'is_pending' => false,
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
                'is_pending' => false,
                'message' => 'Payment recorded, but user profile not found.',
                'user' => null
            ];
        }

        $profile = $user->profile;
        $txnId = $gatewayTxnId ?? $transactionId;

        // 2. Fulfill within a database transaction for full consistency
        DB::transaction(function () use ($user, $profile, $transactionId, $amountPaid, $txnId, $pendingPlanType, $existingTxn) {
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

                    $otherPendingCount = ServiceChargeInvoice::where('candidate_id', $user->id)
                        ->where('id', '!=', $invoice->id)
                        ->whereIn('status', ['pending', 'overdue'])
                        ->count();

                    if ($otherPendingCount === 0) {
                        $profile->pending_amount = 0;
                        $profile->is_fee_paid = true;
                    } else {
                        $profile->pending_amount = max(0, $profile->pending_amount - $invoice->amount);
                        if ($profile->pending_amount <= 0) {
                            $profile->is_fee_paid = true;
                            $profile->pending_amount = 0;
                        }
                    }

                    if ($profile->placed_status !== 'placed') {
                        $profile->placed_status = 'placed';
                    }
                    $profile->save();

                    // Advance referral stage to joined & complete
                    \App\Services\ReferralService::advanceStage($user, 'joined');
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

            } else {
                // --- INITIAL REGISTRATION / WIZARD PAYMENT (TXN_ / MANUAL_) ---
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
                        'registration_completed_at' => $profile->registration_completed_at ?? now(),
                        'plan_started_at' => $profile->plan_started_at ?? now(),
                    ]);
                }
            }

            // Ensure transaction status is marked success
            if ($existingTxn && $existingTxn->status !== 'success') {
                $existingTxn->update(['status' => 'success']);
            }
        });

        // 3. Post-Fulfillment Side Effects (Wrapped safely so failures do not affect plan upgrade)
        try {
            if (str_starts_with($transactionId, 'SC_')) {
                self::sendNotification($user, 'Service Charge Received', "₹{$amountPaid} was received for Service Charge.");
                self::sendEmailOnce($transactionId, 'receipt', function() use ($user, $transactionId, $amountPaid) {
                    Mail::to($user->email)->send(new PaymentReceiptMail($user, $transactionId, $amountPaid, 'Service Charge Invoice Payment'));
                });
            } elseif (str_starts_with($transactionId, 'UPGRADE_')) {
                self::sendNotification($user, 'Plan Upgraded to Premium', 'Your plan has been upgraded to Premium with 3 application slots.');
                self::sendEmailOnce($transactionId, 'upgrade_receipt', function() use ($user, $transactionId, $amountPaid) {
                    Mail::to($user->email)->send(new PaymentReceiptMail($user, $transactionId, $amountPaid, 'Upgrade to Premium Plan'));
                });
            } elseif (str_starts_with($transactionId, 'RENEW_BASIC_')) {
                self::sendNotification($user, 'Plan Renewed Successfully', 'Basic Plan renewed with 2 application slots.');
                self::sendEmailOnce($transactionId, 'renew_basic_receipt', function() use ($user, $transactionId, $amountPaid) {
                    Mail::to($user->email)->send(new PaymentReceiptMail($user, $transactionId, $amountPaid, 'Basic Plan Renewal'));
                });
            } elseif (str_starts_with($transactionId, 'RENEW_PREMIUM_')) {
                self::sendNotification($user, 'Plan Renewed Successfully', 'Premium Plan renewed with 3 application slots.');
                self::sendEmailOnce($transactionId, 'renew_premium_receipt', function() use ($user, $transactionId, $amountPaid) {
                    Mail::to($user->email)->send(new PaymentReceiptMail($user, $transactionId, $amountPaid, 'Premium Plan Renewal'));
                });
            } else {
                self::sendNotification($user, 'Registration Successful', 'Welcome to Vedanta! Your registration plan is now active.');
                
                try {
                    // Ensure the agreement PDF is generated before sending the welcome email
                    \App\Http\Controllers\Candidate\AgreementController::ensureAgreementPdfExists($profile);
                    $user->refresh();
                } catch (\Throwable $pdfEx) {
                    Log::warning('Agreement PDF auto-generation warning: ' . $pdfEx->getMessage());
                }

                self::sendEmailOnce($transactionId, 'welcome_emails', function() use ($user, $transactionId, $amountPaid) {
                    Mail::to($user->email)->send(new PaymentReceiptMail($user, $transactionId, $amountPaid, 'Candidate Profile Registration Fee'));
                    Mail::to($user->email)->send(new RegistrationSuccessMail($user));
                });
            }
        } catch (\Throwable $e) {
            Log::error('Post-fulfillment notification/mail error (plan remains upgraded): ' . $e->getMessage());
        }

        return [
            'success' => true,
            'is_pending' => false,
            'message' => 'Payment fulfilled successfully.',
            'user' => $user
        ];
    }

    /**
     * Safely extract Candidate ID from transaction string or database
     */
    public static function extractCandidateId(string $transactionId): ?int
    {
        // 1. Direct DB lookup
        try {
            $txn = PaymentTransaction::where('transaction_id', $transactionId)->first();
            if ($txn && $txn->candidate_id) {
                return (int) $txn->candidate_id;
            }
        } catch (\Throwable $e) {
            Log::warning('extractCandidateId DB check error: ' . $e->getMessage());
        }

        $parts = explode('_', $transactionId);

        // 2. Service charge invoice lookup
        if (str_starts_with($transactionId, 'SC_')) {
            if (count($parts) >= 2 && is_numeric($parts[1])) {
                $invoice = ServiceChargeInvoice::find($parts[1]);
                return $invoice ? (int) $invoice->candidate_id : null;
            }
            return null;
        }

        // 3. Structured prefixes (TXN_id_time, UPGRADE_id_time, RENEW_BASIC_id_time, MANUAL_CASH_id_time)
        if (count($parts) >= 3) {
            $possibleId = $parts[count($parts) - 2];
            if (is_numeric($possibleId)) {
                return (int) $possibleId;
            }
        }

        // 4. Regex fallback: looks for _<id>_<digits> at the end
        if (preg_match('/_(\d+)_\d+$/', $transactionId, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    private static function sendNotification(User $user, string $title, string $message)
    {
        try {
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
        } catch (\Throwable $e) {
            Log::warning('sendNotification failed: ' . $e->getMessage());
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

