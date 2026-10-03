<?php

namespace App\Services;

use App\Mail\DynamicTemplateMail;
use App\Models\EmailTemplate;
use App\Models\PaymentTransaction;
use App\Models\ServiceChargeInvoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CandidateLifecycleMailService
{
    /**
     * Send an email to a candidate using a predefined EmailTemplate by name.
     */
    public static function send(User $user, string $templateName, array $extraData = []): bool
    {
        try {
            if (! $user->email) {
                return false;
            }

            $template = EmailTemplate::where('name', $templateName)->first();
            if (! $template) {
                Log::warning("CandidateLifecycleMailService: Template '{$templateName}' not found in database.");

                return false;
            }

            $latestInv = ServiceChargeInvoice::where('candidate_id', $user->id)->latest()->first();
            $latestPay = PaymentTransaction::where('candidate_id', $user->id)->where('status', 'success')->latest()->first();

            $rawInvAmt = $latestInv ? (float) $latestInv->amount : 0;
            $rawPaidAmt = $user->profile ? (float) $user->profile->paid_amount : 0;
            $rawPayTxnAmt = $latestPay ? (float) $latestPay->amount : 0;

            $computedAmt = $rawInvAmt > 0 ? $rawInvAmt : ($rawPayTxnAmt > 0 ? $rawPayTxnAmt : ($rawPaidAmt > 0 ? $rawPaidAmt : 500));
            $formattedAmt = number_format($computedAmt, 2);

            $invNum = $extraData['invoice_number'] ?? ($latestInv ? 'INV-SC-'.str_pad($latestInv->id, 5, '0', STR_PAD_LEFT) : ($latestPay ? ($latestPay->transaction_id ?? 'INV-PAY') : ($user->profile?->payment_id ?? 'INV-VPA')));
            $invDueDate = $extraData['due_date'] ?? ($latestInv && $latestInv->due_date ? Carbon::parse($latestInv->due_date)->format('M d, Y') : now()->addDays(7)->format('M d, Y'));
            $invStatus = $extraData['status'] ?? ($latestInv ? ucfirst($latestInv->status) : ($user->profile?->is_fee_paid ? 'Paid' : 'Pending'));
            $amtVal = $extraData['amount'] ?? $extraData['payment_amount'] ?? $formattedAmt;

            $replacements = [
                '{name}' => $user->name,
                '[name]' => $user->name,
                '{candidate_name}' => $user->name,
                '[candidate_name]' => $user->name,
                '{email}' => $user->email,
                '[email]' => $user->email,
                '{phone}' => $user->phone ?? 'N/A',
                '[phone]' => $user->phone ?? 'N/A',
                '{category}' => $user->profile?->category?->name ?? 'Teaching',
                '[category]' => $user->profile?->category?->name ?? 'Teaching',
                '{subject}' => $user->profile?->subject?->name ?? 'Faculty',
                '[subject]' => $user->profile?->subject?->name ?? 'Faculty',
                '{plan_type}' => ucfirst($user->profile?->plan_type ?? 'Standard'),
                '[plan_type]' => ucfirst($user->profile?->plan_type ?? 'Standard'),
                '{invoice_number}' => $invNum,
                '[invoice_number]' => $invNum,
                '{payment_amount}' => $amtVal,
                '[payment_amount]' => $amtVal,
                '{amount}' => $amtVal,
                '[amount]' => $amtVal,
                '{invoice_amount}' => $amtVal,
                '[invoice_amount]' => $amtVal,
                '{service_charge}' => $amtVal,
                '[service_charge]' => $amtVal,
                '{service_charge_amount}' => $amtVal,
                '[service_charge_amount]' => $amtVal,
                '{pending_amount}' => $extraData['pending_amount'] ?? ($latestInv ? number_format($latestInv->amount + $latestInv->late_fee, 2) : number_format($user->profile?->pending_amount ?? 0, 2)),
                '[pending_amount]' => $extraData['pending_amount'] ?? ($latestInv ? number_format($latestInv->amount + $latestInv->late_fee, 2) : number_format($user->profile?->pending_amount ?? 0, 2)),
                '{due_date}' => $invDueDate,
                '[due_date]' => $invDueDate,
                '{status}' => $invStatus,
                '[status]' => $invStatus,
                '{job_title}' => $extraData['job_title'] ?? 'Teacher',
                '[job_title]' => $extraData['job_title'] ?? 'Teacher',
                '{school_name}' => $extraData['school_name'] ?? 'Partner Educational Institution',
                '[school_name]' => $extraData['school_name'] ?? 'Partner Educational Institution',
                '{interview_date}' => $extraData['interview_date'] ?? 'To be notified',
                '[interview_date]' => $extraData['interview_date'] ?? 'To be notified',
                '{interview_link}' => $extraData['interview_link'] ?? route('candidate.applications.index'),
                '[interview_link]' => $extraData['interview_link'] ?? route('candidate.applications.index'),
                '{remarks}' => $extraData['remarks'] ?? 'None',
                '[remarks]' => $extraData['remarks'] ?? 'None',
                '{action_url}' => $extraData['action_url'] ?? route('candidate.dashboard'),
                '[action_url]' => $extraData['action_url'] ?? route('candidate.dashboard'),
            ];

            $subject = str_replace(array_keys($replacements), array_values($replacements), $template->subject);
            $body = str_replace(array_keys($replacements), array_values($replacements), $template->body);

            Mail::to($user->email)->send(new DynamicTemplateMail($subject, $body));

            return true;
        } catch (\Exception $e) {
            Log::error("CandidateLifecycleMailService: Failed to send '{$templateName}' to {$user->email}: ".$e->getMessage());

            return false;
        }
    }
}
