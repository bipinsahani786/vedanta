<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationSuccessMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Registration Successful - Welcome to Vedanta Placement Agency',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.candidate.registration_success',
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
        if ($this->user->profile && $this->user->profile->agreement_pdf_path) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromStorageDisk('public', $this->user->profile->agreement_pdf_path)
                                ->as('Registration_Agreement.pdf')
                                ->withMime('application/pdf');
        }
        return $attachments;
    }
}
