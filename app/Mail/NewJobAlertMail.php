<?php

namespace App\Mail;

use App\Models\JobAlertSubscriber;
use App\Models\JobPost;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewJobAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public JobPost $job;

    public JobAlertSubscriber $subscriber;

    public function __construct(JobPost $job, JobAlertSubscriber $subscriber)
    {
        $job->loadMissing(['category', 'subject', 'qualification', 'state']);
        $this->job = $job;
        $this->subscriber = $subscriber;
    }

    public function envelope(): Envelope
    {
        $title = trim((string) ($this->job->title ?? '')) ?: 'New School / Teaching Vacancy';

        return new Envelope(
            subject: 'New Job Alert: ' . $title . ' | Vedanta Placement Agency',
        );
    }

    public function content(): Content
    {
        $this->job->loadMissing(['category', 'subject', 'qualification', 'state']);

        return new Content(
            markdown: 'emails.job_alert',
            with: [
                'job' => $this->job,
                'subscriber' => $this->subscriber,
                'unsubscribeUrl' => route('job-alerts.unsubscribe', $this->subscriber->unsubscribe_token),
                'jobUrl' => route('jobs.show', $this->job->id),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
