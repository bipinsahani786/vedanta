<?php

namespace App\Console\Commands;

use App\Mail\PaymentReceiptMail;
use App\Models\ServiceChargeInvoice;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class TestInvoiceMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:test {email? : Optional email to send the test mail to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test invoice generation, save PDF previews to public folder, and optionally send test mail';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('==========================================');
        $this->info('  Vedanta Invoice Testing & Verification  ');
        $this->info('==========================================');

        // 1. Pick a test candidate/user
        $user = User::has('profile')->first() ?? User::first();
        if (! $user) {
            $this->error('No users found in database.');

            return 1;
        }

        if ($user->profile && method_exists($user->profile, 'ensureIdsAssigned')) {
            $user->profile->ensureIdsAssigned();
            $user->refresh();
        }

        $this->line("Candidate: <comment>{$user->name}</comment> ({$user->email})");
        $this->line('Candidate ID: <comment>'.($user->profile->vpa_id ?? 'N/A').'</comment>');

        // 2. Generate Regular Payment Invoice (Candidate Payment View)
        $this->newLine();
        $this->info('1. Generating Regular Payment Invoice (New Design)...');
        $txnId = 'TEST_REG_'.strtoupper(bin2hex(random_bytes(3)));

        $mailPayment = new PaymentReceiptMail(
            $user,
            $txnId,
            500.00,
            'Candidate Profile Registration Fee'
        );

        $paymentAttachments = $mailPayment->attachments();
        if (! empty($paymentAttachments)) {
            $pdfBytes = null;
            $paymentAttachments[0]->attachWith(
                function ($path) use (&$pdfBytes) { $pdfBytes = file_get_contents($path); },
                function ($data) use (&$pdfBytes) { $pdfBytes = $data(); }
            );
            $paymentPdfPath = public_path('test-payment-invoice.pdf');
            File::put($paymentPdfPath, $pdfBytes);

            $this->info('   [SUCCESS] Regular Payment Invoice PDF generated!');
            $this->line("   File saved at: <comment>{$paymentPdfPath}</comment>");
            $this->line('   Browser Preview: <info>http://127.0.0.1:8000/test-payment-invoice.pdf</info>');
        } else {
            $this->error('   [FAIL] Could not generate Regular Payment Invoice PDF.');
        }

        // 3. Generate Service Charge Invoice (Service Charge PDF View)
        $this->newLine();
        $this->info('2. Generating Service Charge Invoice (Manual SC Payment)...');
        $scInvoice = ServiceChargeInvoice::with(['jobApplication.jobPost', 'candidate'])->first();

        $scTxnId = $scInvoice ? 'MANUAL_SC_'.$scInvoice->id : 'SC_TEST_001';
        $scAmount = $scInvoice ? $scInvoice->amount : 2500.00;

        $mailSc = new PaymentReceiptMail(
            $scInvoice?->candidate ?? $user,
            $scTxnId,
            $scAmount,
            'Service Charge Invoice Payment (Manual)'
        );

        $scAttachments = $mailSc->attachments();
        if (! empty($scAttachments)) {
            $pdfBytes = null;
            $scAttachments[0]->attachWith(
                function ($path) use (&$pdfBytes) { $pdfBytes = file_get_contents($path); },
                function ($data) use (&$pdfBytes) { $pdfBytes = $data(); }
            );
            $scPdfPath = public_path('test-service-charge-invoice.pdf');
            File::put($scPdfPath, $pdfBytes);

            $this->info('   [SUCCESS] Service Charge Invoice PDF generated!');
            $this->line("   File saved at: <comment>{$scPdfPath}</comment>");
            $this->line('   Browser Preview: <info>http://127.0.0.1:8000/test-service-charge-invoice.pdf</info>');
        } else {
            $this->warn('   [NOTICE] No Service Charge invoice found to attach; fallback invoice rendered.');
        }

        // 4. Optionally Send Real Test Email if email argument provided
        $recipientEmail = $this->argument('email');
        if ($recipientEmail) {
            $this->newLine();
            $this->info("3. Sending Test Email with New Invoice attachment to: <comment>{$recipientEmail}</comment>...");
            try {
                Mail::to($recipientEmail)->send($mailPayment);
                $this->info("   [SENT] Test Email dispatched successfully to {$recipientEmail}!");
                $mailer = config('mail.default');
                if ($mailer === 'log') {
                    $this->warn("   (Note: MAIL_MAILER is 'log' in .env, so email was logged in storage/logs/laravel.log)");
                }
            } catch (\Throwable $e) {
                $this->error('   [FAIL] Email dispatch failed: '.$e->getMessage());
            }
        } else {
            $this->newLine();
            $this->line('<comment>Tip:</comment> If you want to send a real email to your inbox, run:');
            $this->line('     <info>php artisan invoice:test your-email@gmail.com</info>');
        }

        $this->newLine();
        $this->info('Testing complete! You can open the Browser Preview links above to verify the new design.');

        return 0;
    }
}
