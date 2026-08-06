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
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentReceiptMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $transactionId;
    public $amount;
    public $description;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $transactionId, float $amount, string $description)
    {
        $this->user = $user;
        $this->transactionId = $transactionId;
        $this->amount = $amount;
        $this->description = $description;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Receipt & Invoice - Vedanta Placement Agency',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.candidate.payment_receipt',
            with: [
                'user' => $this->user,
                'transactionId' => $this->transactionId,
                'amount' => $this->amount,
                'description' => $this->description,
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
        try {
            // Generate PDF on the fly
            $pdf = Pdf::loadView('pdf.invoice', [
                'user' => $this->user,
                'transactionId' => $this->transactionId,
                'amount' => $this->amount,
                'description' => $this->description,
            ]);

            return [
                Attachment::fromData(fn () => $pdf->output(), "Invoice_{$this->transactionId}.pdf")
                    ->withMime('application/pdf'),
            ];
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to generate/attach PDF receipt for transaction {$this->transactionId}: " . $e->getMessage());
            return [];
        }
    }
}
