@include('candidate.payment.invoice', [
    'user' => $user ?? $invoice->candidate,
    'transaction' => (object)[
        'transaction_id' => 'INV-SC-' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT),
        'created_at' => $invoice->payment_date ?? $invoice->created_at ?? now(),
        'amount' => $invoice->net_amount ?? $invoice->amount,
        'type' => 'service_charge',
        'formatted_description' => $invoice->description ?: ($invoice->jobApplication?->jobPost?->title ? 'Placement Service Charge - ' . $invoice->jobApplication->jobPost->title : 'Placement Service Charge')
    ],
    'transactionId' => 'INV-SC-' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT),
    'amount' => $invoice->net_amount ?? $invoice->amount,
    'description' => $invoice->description ?: ($invoice->jobApplication?->jobPost?->title ? 'Placement Service Charge - ' . $invoice->jobApplication->jobPost->title : 'Placement Service Charge'),
    'invoice' => $invoice
])
