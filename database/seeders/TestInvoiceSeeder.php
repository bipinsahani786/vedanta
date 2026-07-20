<?php

namespace Database\Seeders;

use App\Models\ServiceChargeInvoice;
use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Database\Seeder;

class TestInvoiceSeeder extends Seeder
{
    /**
     * Seed a dummy ServiceChargeInvoice for audit/testing purposes.
     * Run: php artisan db:seed --class=TestInvoiceSeeder
     * 
     * Safe to run multiple times — will not duplicate if one already exists.
     */
    public function run(): void
    {
        // Only seed if no invoices exist yet
        if (ServiceChargeInvoice::count() > 0) {
            $this->command->info('Test invoice already exists. Skipping.');
            return;
        }

        $candidate = User::where('role', 'candidate')->first();
        if (!$candidate) {
            $this->command->warn('No candidate found. Skipping invoice seeder.');
            return;
        }

        // Get or create a job application for the candidate
        $jobApp = JobApplication::where('candidate_id', $candidate->id)->first();
        if (!$jobApp) {
            $this->command->warn('No job application found for candidate. Skipping invoice seeder.');
            return;
        }

        ServiceChargeInvoice::create([
            'candidate_id'       => $candidate->id,
            'job_application_id' => $jobApp->id,
            'amount'             => 5000.00,
            'late_fee'           => 300.00,
            'due_date'           => now()->subDays(5)->toDateString(),
            'status'             => 'overdue',
            'payment_date'       => null,
        ]);

        $this->command->info("Test invoice created for candidate: {$candidate->name}");
    }
}
