<?php

namespace App\Console\Commands;

use App\Models\CrmFollowUp;
use Illuminate\Console\Command;

class SendCandidateFollowUpReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:candidate-follow-ups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify admins when scheduled candidate CRM follow-ups become due';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sentCount = CrmFollowUp::dispatchDueReminders();

        if ($sentCount > 0) {
            $this->info("Dispatched {$sentCount} candidate follow-up reminder notification(s).");
        } else {
            $this->info('No due candidate follow-up reminders.');
        }

        return self::SUCCESS;
    }
}
