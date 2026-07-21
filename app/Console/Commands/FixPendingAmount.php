<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class FixPendingAmount extends Command
{
    protected $signature = 'app:fix-pending-amount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix pending amounts for users who have paid their service charges';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = \App\Models\PaymentTransaction::where('type', 'service_charge')
            ->where('status', 'success')
            ->get();

        foreach ($transactions as $tx) {
            $profile = \App\Models\CandidateProfile::where('user_id', $tx->candidate_id)->first();
            if ($profile && $profile->pending_amount > 0) {
                $profile->pending_amount = max(0, $profile->pending_amount - 500);
                $profile->save();
                $this->info("Fixed user {$tx->candidate_id}");
            }
        }
        $this->info('Done.');
    }
}
