<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class SendInterviewReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:interview';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a reminder to candidates exactly 24 hours before their scheduled interview';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $applications = \App\Models\JobApplication::whereNotNull('interview_date')
            ->where('interview_reminder_sent', false)
            ->whereBetween('interview_date', [now(), now()->addHours(24)])
            ->with(['candidate', 'jobPost'])
            ->get();

        $count = 0;
        foreach ($applications as $application) {
            \Illuminate\Support\Facades\Mail::to($application->candidate->email)
                ->send(new \App\Mail\InterviewReminderMail($application));
            
            $application->update(['interview_reminder_sent' => true]);
            $count++;
        }

        $this->info("Sent $count interview reminders.");
    }
}
