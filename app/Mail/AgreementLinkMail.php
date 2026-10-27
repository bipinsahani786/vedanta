<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgreementLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $candidate;
    public $signUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($candidate, $signUrl)
    {
        $this->candidate = $candidate;
        $this->signUrl = $signUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $profile = $this->candidate->profile;
        $isSigned = $profile && ($profile->is_agreement_signed || !empty($profile->agreement_pdf_path) || !empty($profile->signature_date_time));

        $subject = $isSigned
            ? 'Your Registration Agreement - ' . config('app.name')
            : 'Action Required: Sign Your Registration Agreement - ' . config('app.name');

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.agreement_link',
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
