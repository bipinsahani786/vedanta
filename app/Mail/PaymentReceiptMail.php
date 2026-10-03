<?php

namespace App\Mail;

use App\Models\PaymentTransaction;
use App\Models\ServiceChargeInvoice;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PaymentReceiptMail extends Mailable
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
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        try {
            // Ensure candidate IDs are assigned
            if ($this->user && $this->user->profile && method_exists($this->user->profile, 'ensureIdsAssigned')) {
                $this->user->profile->ensureIdsAssigned();
                $this->user->refresh();
            }

            // 1. Check if this is a Service Charge transaction (SC_..., MANUAL_SC_..., or description matches)
            $invoice = null;

            if (str_starts_with($this->transactionId, 'SC_')) {
                $parts = explode('_', $this->transactionId);
                $invoiceId = count($parts) >= 2 ? $parts[1] : null;
                $invoice = $invoiceId ? ServiceChargeInvoice::with(['jobApplication.jobPost', 'candidate'])->find($invoiceId) : null;
            } elseif (str_starts_with($this->transactionId, 'MANUAL_SC_')) {
                $invoiceId = str_replace('MANUAL_SC_', '', $this->transactionId);
                $invoice = $invoiceId ? ServiceChargeInvoice::with(['jobApplication.jobPost', 'candidate'])->find($invoiceId) : null;
            } elseif (str_contains($this->description, 'Service Charge')) {
                $txn = PaymentTransaction::where('transaction_id', $this->transactionId)->first();
                $invoiceId = $txn?->gateway_response['invoice_id'] ?? null;
                if ($invoiceId) {
                    $invoice = ServiceChargeInvoice::with(['jobApplication.jobPost', 'candidate'])->find($invoiceId);
                }
                if (! $invoice) {
                    $invoice = ServiceChargeInvoice::where('candidate_id', $this->user->id)->latest()->first();
                }
            }

            if ($invoice) {
                $pdf = Pdf::loadView('candidate.serviceCharge.invoice_pdf', [
                    'invoice' => $invoice,
                    'user' => $this->user,
                ]);

                return [
                    Attachment::fromData(fn () => $pdf->output(), "Service_Charge_Invoice_{$invoice->id}.pdf")
                        ->withMime('application/pdf'),
                ];
            }

            // 2. Regular Payment Invoice (Registration, Plan Renewal, Plan Upgrade, etc.)
            $transaction = PaymentTransaction::where('transaction_id', $this->transactionId)->first();
            if (! $transaction) {
                $transaction = (object) [
                    'transaction_id' => $this->transactionId,
                    'amount' => $this->amount,
                    'created_at' => now(),
                    'type' => 'payment',
                    'formatted_description' => $this->description,
                    'status' => 'success',
                ];
            }

            // Load the NEW candidate payment invoice template
            $pdf = Pdf::loadView('candidate.payment.invoice', [
                'user' => $this->user,
                'transaction' => $transaction,
                'transactionId' => $this->transactionId,
                'amount' => $this->amount,
                'description' => $this->description,
            ]);

            return [
                Attachment::fromData(fn () => $pdf->output(), "Invoice_{$this->transactionId}.pdf")
                    ->withMime('application/pdf'),
            ];
        } catch (\Throwable $e) {
            Log::error("Failed to generate/attach PDF receipt for transaction {$this->transactionId}: ".$e->getMessage());

            return [];
        }
    }
}
