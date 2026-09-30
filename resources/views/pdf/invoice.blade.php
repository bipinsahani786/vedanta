@include('candidate.payment.invoice', [
    'user' => $user,
    'transaction' => $transaction ?? (object)[
        'transaction_id' => $transactionId ?? 'N/A',
        'created_at' => now(),
        'amount' => $amount ?? 0,
        'type' => 'payment',
        'formatted_description' => $description ?? 'Payment Receipt'
    ],
    'transactionId' => $transactionId ?? ($transaction->transaction_id ?? 'N/A'),
    'amount' => $amount ?? ($transaction->amount ?? 0),
    'description' => $description ?? ($transaction->formatted_description ?? 'Payment Receipt')
])
