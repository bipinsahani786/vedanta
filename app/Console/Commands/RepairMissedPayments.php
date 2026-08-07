<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\CandidateProfile;
use App\Services\PaymentFulfillmentService;
use App\Services\PhonePeService;
use Illuminate\Support\Facades\Log;

class RepairMissedPayments extends Command
{
    protected $signature = 'payments:repair 
                            {--dry-run : Sirf dikhao kya fix hoga, actually fix mat karo}
                            {--user= : Specific user ID fix karo}
                            {--recheck-gateway : PhonePe se dubara status check karo}';

    protected $description = 'Find and fix payments that completed on PhonePe but were not properly fulfilled in our system';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $specificUserId = $this->option('user');
        $recheckGateway = $this->option('recheck-gateway');

        if ($isDryRun) {
            $this->warn('🔍 DRY RUN MODE — Kuch change nahi hoga, sirf dikhayega kya fix hona chahiye');
        }

        $this->info('');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('  STEP 1: Successful transactions jinke profile update miss hua');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $this->repairSuccessfulButUnfulfilled($isDryRun, $specificUserId);

        $this->info('');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('  STEP 2: Candidates jinke profile incomplete hai but payment tha');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $this->repairIncompleteProfiles($isDryRun, $specificUserId);

        if ($recheckGateway) {
            $this->info('');
            $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->info('  STEP 3: Failed/Pending transactions ko PhonePe se recheck karo');
            $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

            $this->recheckFailedTransactions($isDryRun, $specificUserId);
        }

        $this->info('');
        $this->info('✅ Repair process complete!');
    }

    /**
     * STEP 1: Find transactions marked 'success' but profile not updated
     */
    private function repairSuccessfulButUnfulfilled(bool $isDryRun, ?string $specificUserId)
    {
        $query = PaymentTransaction::where('status', 'success');

        if ($specificUserId) {
            $query->where('candidate_id', $specificUserId);
        }

        $successTxns = $query->orderBy('created_at', 'desc')->get();
        $fixedCount = 0;

        foreach ($successTxns as $txn) {
            $user = User::find($txn->candidate_id);
            if (!$user || !$user->profile) continue;

            $profile = $user->profile;
            $txnId = $txn->transaction_id;
            $needsFix = false;
            $reason = '';

            // Registration payment (TXN_) — check if profile was properly completed
            if (str_starts_with($txnId, 'TXN_')) {
                if (!$profile->initial_fee_paid) {
                    $needsFix = true;
                    $reason = "initial_fee_paid=false (should be true)";
                } elseif (!$profile->registration_completed_at) {
                    $needsFix = true;
                    $reason = "registration_completed_at=null";
                } elseif (!$profile->plan_type) {
                    $needsFix = true;
                    $reason = "plan_type=null";
                } elseif ($profile->total_allowed_applications < 2) {
                    $needsFix = true;
                    $reason = "total_allowed_applications={$profile->total_allowed_applications} (should be >=2)";
                }
            }

            // Upgrade payment — check if plan is premium
            if (str_starts_with($txnId, 'UPGRADE_')) {
                if ($profile->plan_type !== 'premium') {
                    $needsFix = true;
                    $reason = "plan_type='{$profile->plan_type}' (should be 'premium')";
                } elseif (!$profile->is_fee_paid) {
                    $needsFix = true;
                    $reason = "is_fee_paid=false (should be true after upgrade)";
                } elseif ($profile->total_allowed_applications < 3) {
                    $needsFix = true;
                    $reason = "total_allowed_applications={$profile->total_allowed_applications} (should be 3)";
                }
            }

            // Renewal payment — check if plan is correct
            if (str_starts_with($txnId, 'RENEW_PREMIUM_')) {
                if ($profile->plan_type !== 'premium') {
                    $needsFix = true;
                    $reason = "plan_type='{$profile->plan_type}' (should be 'premium' after renewal)";
                } elseif ($profile->total_allowed_applications < 3) {
                    $needsFix = true;
                    $reason = "total_allowed_applications={$profile->total_allowed_applications} (should be 3)";
                }
            }

            if (str_starts_with($txnId, 'RENEW_BASIC_')) {
                if ($profile->total_allowed_applications < 2) {
                    $needsFix = true;
                    $reason = "total_allowed_applications={$profile->total_allowed_applications} (should be 2)";
                }
            }

            // Service Charge — check if invoice marked paid
            if (str_starts_with($txnId, 'SC_')) {
                $parts = explode('_', $txnId);
                $invoiceId = (count($parts) >= 2) ? $parts[1] : null;
                if ($invoiceId) {
                    $invoice = \App\Models\ServiceChargeInvoice::find($invoiceId);
                    if ($invoice && $invoice->status !== 'paid') {
                        $needsFix = true;
                        $reason = "Service Charge Invoice #{$invoiceId} status='{$invoice->status}' (should be 'paid')";
                    }
                }
            }

            if ($needsFix) {
                $fixedCount++;
                $this->warn("  ⚠️  User #{$txn->candidate_id} ({$user->name}) | TXN: {$txnId} | ₹{$txn->amount}");
                $this->line("      Reason: {$reason}");

                if (!$isDryRun) {
                    $pendingPlan = null;
                    if (str_starts_with($txnId, 'TXN_')) {
                        $pendingPlan = $txn->amount >= 1000 ? 'premium' : 'standard';
                    }

                    $result = PaymentFulfillmentService::fulfill(
                        $txnId,
                        true,
                        $txn->amount,
                        is_array($txn->gateway_response) ? $txn->gateway_response : [],
                        null,
                        $pendingPlan,
                        true // forceReFulfill
                    );

                    if ($result['success']) {
                        $this->info("      ✅ FIXED: {$result['message']}");
                    } else {
                        $this->error("      ❌ FAILED: {$result['message']}");
                    }
                }
            }
        }

        if ($fixedCount === 0) {
            $this->info('  ✅ Koi unfulfilled successful transaction nahi mila.');
        } else {
            $this->info("  📊 Total found: {$fixedCount} transactions");
        }
    }

    /**
     * STEP 2: Find candidates whose profile says they haven't paid, but they have a successful txn
     */
    private function repairIncompleteProfiles(bool $isDryRun, ?string $specificUserId)
    {
        $query = CandidateProfile::where('initial_fee_paid', false)
            ->orWhere(function ($q) {
                $q->whereNull('registration_completed_at')
                  ->where('initial_fee_paid', true);
            });

        if ($specificUserId) {
            $query->where('user_id', $specificUserId);
        }

        $profiles = $query->get();
        $fixedCount = 0;

        foreach ($profiles as $profile) {
            $user = $profile->user;
            if (!$user) continue;

            // Check if this candidate has a successful payment
            $successTxn = PaymentTransaction::where('candidate_id', $user->id)
                ->where('type', 'registration_fee')
                ->where('status', 'success')
                ->latest()
                ->first();

            if ($successTxn) {
                $fixedCount++;
                $this->warn("  ⚠️  User #{$user->id} ({$user->name}) | Has successful TXN but profile incomplete");
                $this->line("      TXN: {$successTxn->transaction_id} | ₹{$successTxn->amount} | Paid at: {$successTxn->created_at}");
                $this->line("      Profile: initial_fee_paid={$profile->initial_fee_paid}, plan_type={$profile->plan_type}, registration_completed_at={$profile->registration_completed_at}");

                if (!$isDryRun) {
                    $pendingPlan = $successTxn->amount >= 1000 ? 'premium' : 'standard';

                    $result = PaymentFulfillmentService::fulfill(
                        $successTxn->transaction_id,
                        true,
                        $successTxn->amount,
                        is_array($successTxn->gateway_response) ? $successTxn->gateway_response : [],
                        null,
                        $pendingPlan,
                        true // forceReFulfill
                    );

                    if ($result['success']) {
                        $this->info("      ✅ FIXED: {$result['message']}");
                    } else {
                        $this->error("      ❌ FAILED: {$result['message']}");
                    }
                }
            }
        }

        if ($fixedCount === 0) {
            $this->info('  ✅ Koi incomplete profile with successful payment nahi mila.');
        } else {
            $this->info("  📊 Total found: {$fixedCount} profiles");
        }
    }

    /**
     * STEP 3: Recheck 'failed' or recent transactions with PhonePe gateway
     */
    private function recheckFailedTransactions(bool $isDryRun, ?string $specificUserId)
    {
        $query = PaymentTransaction::whereIn('status', ['failed', 'pending'])
            ->where('created_at', '>=', now()->subDays(30)); // Last 30 days only

        if ($specificUserId) {
            $query->where('candidate_id', $specificUserId);
        }

        $failedTxns = $query->orderBy('created_at', 'desc')->get();
        $fixedCount = 0;

        if ($failedTxns->isEmpty()) {
            $this->info('  ✅ Koi failed/pending transaction nahi mila (last 30 days).');
            return;
        }

        $phonePe = new PhonePeService();

        foreach ($failedTxns as $txn) {
            $this->line("  🔄 Rechecking: {$txn->transaction_id} (₹{$txn->amount})...");

            try {
                $statusResult = $phonePe->checkStatus($txn->transaction_id);

                if ($statusResult['success']) {
                    $fixedCount++;
                    $amountPaid = ($statusResult['amount'] ?? 0) / 100;

                    $user = User::find($txn->candidate_id);
                    $this->warn("  ⚠️  FOUND! User #{$txn->candidate_id} ({$user?->name}) | TXN actually COMPLETED on PhonePe!");
                    $this->line("      PhonePe state: {$statusResult['state']} | Amount: ₹{$amountPaid}");

                    if (!$isDryRun) {
                        $pendingPlan = null;
                        if (str_starts_with($txn->transaction_id, 'TXN_')) {
                            $pendingPlan = $amountPaid >= 1000 ? 'premium' : 'standard';
                        }

                        $result = PaymentFulfillmentService::fulfill(
                            $txn->transaction_id,
                            true,
                            $amountPaid,
                            $statusResult['raw'] ?? [],
                            $statusResult['transactionId'] ?? null,
                            $pendingPlan,
                            true // forceReFulfill
                        );

                        if ($result['success']) {
                            $this->info("      ✅ FIXED: {$result['message']}");
                        } else {
                            $this->error("      ❌ FAILED: {$result['message']}");
                        }
                    }
                } else {
                    $this->line("      ↪ Still failed/pending on PhonePe (state: {$statusResult['state']})");
                }
            } catch (\Throwable $e) {
                $this->error("      ❌ Gateway check failed: {$e->getMessage()}");
            }

            // Rate limit — don't hammer PhonePe API
            usleep(500000); // 0.5 second delay
        }

        if ($fixedCount === 0) {
            $this->info('  ✅ PhonePe pe koi naya successful transaction nahi mila.');
        } else {
            $this->info("  📊 Total recovered from gateway: {$fixedCount} transactions");
        }
    }
}
