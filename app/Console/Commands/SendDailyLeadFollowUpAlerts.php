<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendDailyLeadFollowUpAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:lead-follow-ups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Alert admin about any leads scheduled for follow-up today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $leadsCount = \App\Models\LeadFollowUp::whereDate('follow_up_date', now()->toDateString())
            ->distinct('lead_id')
            ->count('lead_id');

        if ($leadsCount > 0) {
            $adminUser = \App\Models\User::where('role', 'admin')->first();
            
            if ($adminUser) {
                \Illuminate\Support\Facades\DB::table('notifications')->insert([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'type' => 'App\Notifications\DailyLeadFollowUp',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $adminUser->id,
                    'data' => json_encode([
                        'title' => 'Lead Follow-Ups Due Today',
                        'message' => "You have $leadsCount lead(s) scheduled for follow-up today.",
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $this->info("Notified admin about $leadsCount lead follow-ups today.");
        } else {
            $this->info("No lead follow-ups due today.");
        }
    }
}
