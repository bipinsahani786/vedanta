<?php

namespace App\Mail;

use App\Models\ServiceChargeInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LateFeeAlertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invoice;
    public $difference;

    /**
     * Create a new message instance.
     */
    public function __construct(ServiceChargeInvoice $invoice, $difference)
    {
        $this->invoice = $invoice;
        $this->difference = $difference;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'URGENT: Late Fee Added to Overdue Invoice',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.candidate.late_fee_alert',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
