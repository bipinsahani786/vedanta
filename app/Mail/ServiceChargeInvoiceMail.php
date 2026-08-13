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

class ServiceChargeInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    /**
     * Create a new message instance.
     */
    public function __construct(ServiceChargeInvoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Service Charge Invoice - Vedanta Placement Agency',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.candidate.service_charge_invoice',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        try {
            if ($this->invoice && $this->invoice->candidate) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('candidate.serviceCharge.invoice_pdf', [
                    'invoice' => $this->invoice,
                    'user' => $this->invoice->candidate
                ]);

                return [
                    Attachment::fromData(fn () => $pdf->output(), "Service_Charge_Invoice_{$this->invoice->id}.pdf")
                        ->withMime('application/pdf'),
                ];
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to generate PDF attachment for ServiceChargeInvoiceMail #{$this->invoice->id}: " . $e->getMessage());
        }

        return [];
    }
}
