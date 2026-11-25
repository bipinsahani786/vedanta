<?php

namespace App\Observers;

use App\Jobs\SendNewJobAlertToSubscribersJob;
use App\Models\JobAlertDispatch;
use App\Models\JobAlertSubscriber;
use App\Models\JobPost;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class JobPostObserver
{
    public function created(JobPost $job): void
    {
        if ($job->status === 'approved') {
            $this->dispatchAlert($job);
        }
    }

    public function updated(JobPost $job): void
    {
        if ($job->wasChanged('status') && $job->status === 'approved') {
            $this->dispatchAlert($job);
        }
    }

    protected function dispatchAlert(JobPost $job): void
    {
        try {
            if (! Schema::hasTable('job_alert_subscribers') || ! Schema::hasTable('job_alert_dispatches')) {
                return;
            }

            if (JobAlertDispatch::where('job_post_id', $job->id)->exists()) {
                return;
            }

            if (! JobAlertSubscriber::active()->exists()) {
                return;
            }

            if (app()->runningInConsole()) {
                SendNewJobAlertToSubscribersJob::dispatchSync($job->id);
            } else {
                SendNewJobAlertToSubscribersJob::dispatchAfterResponse($job->id);
            }
        } catch (\Throwable $e) {
            Log::warning('Unable to dispatch job alert for job #' . $job->id . ': ' . $e->getMessage());
        }
    }
}
