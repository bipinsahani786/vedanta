<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidateForwardedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $application;

    /**
     * Create a new message instance.
     */
    public function __construct(JobApplication $application)
    {
        $this->application = $application;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Candidate Profile Forwarded for ' . $this->application->jobPost->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.employer.candidate_forwarded',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        $profile = $this->application->candidate->profile;
        if ($profile && $profile->resume_path) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromStorageDisk('public', $profile->resume_path)
                                ->as('Resume_' . str_replace(' ', '_', $this->application->candidate->name) . '.' . pathinfo($profile->resume_path, PATHINFO_EXTENSION));
        }
        return $attachments;
    }
}
