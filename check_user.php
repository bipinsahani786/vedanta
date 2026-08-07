<?php
// Check user 664 (Koustav Mani Pathak)
$u = \App\Models\User::find(664);
$p = $u->profile;

echo "=== USER ===" . PHP_EOL;
echo "ID: {$u->id} | Name: {$u->name} | Email: {$u->email}" . PHP_EOL;

echo PHP_EOL . "=== PROFILE ===" . PHP_EOL;
echo "plan_type: {$p->plan_type}" . PHP_EOL;
echo "initial_fee_paid: {$p->initial_fee_paid}" . PHP_EOL;
echo "is_fee_paid: {$p->is_fee_paid}" . PHP_EOL;
echo "paid_amount: {$p->paid_amount}" . PHP_EOL;
echo "pending_amount: {$p->pending_amount}" . PHP_EOL;
echo "total_allowed_applications: {$p->total_allowed_applications}" . PHP_EOL;
echo "used_applications: {$p->used_applications}" . PHP_EOL;
echo "registration_step: {$p->registration_step}" . PHP_EOL;
echo "registration_completed_at: {$p->registration_completed_at}" . PHP_EOL;
echo "plan_started_at: {$p->plan_started_at}" . PHP_EOL;
echo "is_profile_complete: {$p->is_profile_complete}" . PHP_EOL;
echo "is_agreement_signed: {$p->is_agreement_signed}" . PHP_EOL;
echo "verified: {$p->verified}" . PHP_EOL;

echo PHP_EOL . "=== PAYMENT TRANSACTIONS ===" . PHP_EOL;
$txns = \App\Models\PaymentTransaction::where('candidate_id', 664)->get();
if ($txns->isEmpty()) {
    echo "NO TRANSACTIONS FOUND!" . PHP_EOL;
} else {
    foreach ($txns as $t) {
        echo "TXN: {$t->transaction_id} | Amount: {$t->amount} | Status: {$t->status} | Type: {$t->type} | Date: {$t->created_at}" . PHP_EOL;
    }
}
