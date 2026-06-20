<?php

namespace App\Mail;

use App\Models\JobPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidateJobMatchNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $job;
    public $matchScore;

    /**
     * Create a new message instance.
     */
    public function __construct(JobPost $job, $matchScore)
    {
        $this->job = $job;
        $this->matchScore = $matchScore;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Job Match Found: ' . $this->job->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.candidate.job_match',
            with: [
                'job' => $this->job,
                'matchScore' => $this->matchScore,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
