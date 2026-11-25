<?php

namespace App\Jobs;

use App\Mail\NewJobAlertMail;
use App\Models\JobAlertDispatch;
use App\Models\JobAlertSubscriber;
use App\Models\JobPost;
use Illuminate\Bus\Queueable;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class SendNewJobAlertToSubscribersJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $jobPostId;

    public function __construct(int $jobPostId)
    {
        $this->jobPostId = $jobPostId;
    }

    public function handle(): void
    {
        try {
            if (! Schema::hasTable('job_alert_subscribers') || ! Schema::hasTable('job_alert_dispatches')) {
                return;
            }

            $job = JobPost::with(['category', 'subject', 'qualification', 'state'])->find($this->jobPostId);
            if (! $job || $job->status !== 'approved') {
                return;
            }

            if (! JobAlertSubscriber::active()->exists()) {
                return;
            }

            try {
                $dispatch = JobAlertDispatch::create([
                    'job_post_id' => $job->id,
                    'sent_count' => 0,
                    'dispatched_at' => now(),
                ]);
            } catch (UniqueConstraintViolationException $e) {
                // Already dispatched for this job post
                return;
            }

            @ignore_user_abort(true);
            @set_time_limit(180);

            $sentCount = 0;

            JobAlertSubscriber::active()
                ->orderBy('id')
                ->chunkById(50, function ($subscribers) use ($job, &$sentCount) {
                    foreach ($subscribers as $subscriber) {
                        try {
                            if (empty($subscriber->unsubscribe_token)) {
                                $subscriber->update([
                                    'unsubscribe_token' => JobAlertSubscriber::generateUniqueToken(),
                                ]);
                            }
                            Mail::to($subscriber->email)->send(new NewJobAlertMail($job, $subscriber));
                            $sentCount++;
                        } catch (\Throwable $e) {
                            Log::warning('Failed to send daily job alert email to ' . $subscriber->email . ': ' . $e->getMessage());
                        }
                    }
                });

            $dispatch->update(['sent_count' => $sentCount]);
        } catch (\Throwable $e) {
            Log::warning('Job alert dispatch skipped for job #' . $this->jobPostId . ': ' . $e->getMessage());
        }
    }
}
