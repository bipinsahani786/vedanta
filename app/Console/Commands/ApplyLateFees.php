<?php

namespace App\Console\Commands;

use App\Models\ServiceChargeInvoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ApplyLateFees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:apply-late-fees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply daily late fees to overdue service charge invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        // Get all pending/overdue invoices where due date is passed
        $overdueInvoices = ServiceChargeInvoice::whereIn('status', ['pending', 'overdue'])
            ->whereDate('due_date', '<', $today)
            ->get();

        $count = 0;
        foreach ($overdueInvoices as $invoice) {
            // Apply 50 rs late fee per day it is overdue, or just a fixed daily addition
            // For this logic, we just add 50 to late_fee every day this runs
            $invoice->late_fee += 50.00;
            $invoice->status = 'overdue';
            $invoice->save();
            $count++;
        }

        $this->info("Late fees applied to {$count} invoices.");
    }
}
