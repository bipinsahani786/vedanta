<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$candidateId = 4;
$successTxns = App\Models\PaymentTransaction::where('candidate_id', $candidateId)
    ->whereIn('type', ['service_charge', 'placement_fee'])
    ->where('status', 'success')
    ->latest()
    ->get();

echo "Successful Transactions Count for Candidate 4: " . $successTxns->count() . "\n";
foreach ($successTxns as $t) {
    echo "  ID: {$t->id} | txn: {$t->transaction_id} | amt: {$t->amount} | status: {$t->status}\n";
}
