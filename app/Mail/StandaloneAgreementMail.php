<?php

namespace App\Mail;

use App\Models\StandaloneAgreement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class StandaloneAgreementMail extends Mailable
{
    use Queueable, SerializesModels;

    public StandaloneAgreement $agreement;

    public string $mode;

    public function __construct(StandaloneAgreement $agreement, string $mode = 'initial')
    {
        $this->agreement = $agreement;
        $this->mode = $mode;
    }

    public function envelope(): Envelope
    {
        $code = $this->agreement->agreement_code;

        $subject = match ($this->mode) {
            'signed_copy' => "Signed Service Agreement Copy ({$code}) — Vedanta Placement Agency",
            'resend' => "Resent: Service Agreement for Your Digital Signature ({$code})",
            default => $this->agreement->subject ?: "Service Agreement for Your Digital Signature ({$code}) — Vedanta Placement Agency",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.standalone_agreement',
            with: [
                'agreement' => $this->agreement,
                'mode' => $this->mode,
            ],
        );
    }

    public function attachments(): array
    {
        if (
            $this->mode === 'signed_copy'
            && !empty($this->agreement->pdf_path)
            && Storage::disk('public')->exists($this->agreement->pdf_path)
        ) {
            return [
                Attachment::fromStorageDisk('public', $this->agreement->pdf_path)
                    ->as('Vedanta_Signed_Agreement_' . $this->agreement->agreement_code . '.pdf')
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
