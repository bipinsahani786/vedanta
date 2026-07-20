<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendAbandonedRegistrationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:abandoned-registration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a reminder to candidates who have not completed their profile/payment after 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $candidates = \App\Models\User::where('role', 'candidate')
            ->whereHas('profile', function($q) {
                $q->where(function($query) {
                    $query->where('is_profile_complete', false)
                          ->orWhere('is_fee_paid', false);
                })->where('abandoned_reminder_sent', false)
                ->where('created_at', '<=', now()->subHours(24));
            })
            ->with('profile')
            ->get();

        $count = 0;
        foreach ($candidates as $candidate) {
            \Illuminate\Support\Facades\Mail::to($candidate->email)->send(new \App\Mail\AbandonedRegistrationMail($candidate));
            $candidate->profile->update(['abandoned_reminder_sent' => true]);
            $count++;
        }

        $this->info("Sent $count abandoned registration reminders.");
    }
}
