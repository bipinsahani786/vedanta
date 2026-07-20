<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('invoices:calculate-late-fees')]
#[Description('Calculate and apply daily late fees to overdue service charge invoices')]
class CalculateLateFees extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $overdueInvoices = \App\Models\ServiceChargeInvoice::where('status', '!=', 'paid')
            ->whereDate('due_date', '<', now()->toDateString())
            ->get();

        $count = 0;
        foreach ($overdueInvoices as $invoice) {
            $daysOverdue = (int) now()->diffInDays(\Carbon\Carbon::parse($invoice->due_date), false) * -1;
            if ($daysOverdue > 0) {
                $newLateFee = $daysOverdue * 300; // 300 per day

                if ($newLateFee > $invoice->late_fee) {
                    $difference = $newLateFee - $invoice->late_fee;
                    
                    $invoice->update([
                        'late_fee' => $newLateFee,
                        'status' => 'overdue'
                    ]);

                    // Update candidate profile pending amount
                    $candidate = \App\Models\User::find($invoice->candidate_id);
                    if ($candidate && $candidate->profile) {
                        $candidate->profile->increment('pending_amount', $difference);

                        // Notify Candidate DB
                        \Illuminate\Support\Facades\DB::table('notifications')->insert([
                            'id' => \Illuminate\Support\Str::uuid()->toString(),
                            'type' => 'App\Notifications\ServiceChargeLateFeeAdded',
                            'notifiable_type' => 'App\Models\User',
                            'notifiable_id' => $candidate->id,
                            'data' => json_encode([
                                'title' => 'Invoice Overdue - Late Fine Added',
                                'message' => 'A late fine of ₹' . number_format($difference, 2) . ' has been added to your pending invoice.',
                                'amount' => $difference,
                                'invoice_id' => $invoice->id
                            ]),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Send Email
                        \Illuminate\Support\Facades\Mail::to($candidate->email)->send(new \App\Mail\LateFeeAlertMail($invoice, $difference));
                    }
                    $count++;
                }
            }
        }

        $this->info("Late fees calculated successfully. Updated $count invoices.");
    }
}
