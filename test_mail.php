<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $user = \App\Models\User::first();
    $mail = new \App\Mail\PaymentReceiptMail($user, 'TXN123', 500, 'Test');
    \Illuminate\Support\Facades\Mail::to($user->email)->send($mail);
    echo "Mail Sent Successfully\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
