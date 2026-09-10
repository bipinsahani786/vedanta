<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\User;
use App\Mail\DynamicTemplateMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CandidateLifecycleMailService
{
    /**
     * Send an email to a candidate using a predefined EmailTemplate by name.
     *
     * @param User $user
     * @param string $templateName
     * @param array $extraData
     * @return bool
     */
    public static function send(User $user, string $templateName, array $extraData = []): bool
    {
        try {
            if (!$user->email) {
                return false;
            }

            $template = EmailTemplate::where('name', $templateName)->first();
            if (!$template) {
                Log::warning("CandidateLifecycleMailService: Template '{$templateName}' not found in database.");
                return false;
            }

            $replacements = [
                '{name}' => $user->name,
                '[name]' => $user->name,
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
                '{invoice_number}' => $extraData['invoice_number'] ?? $user->profile?->payment_id ?? 'INV-VPA',
                '[invoice_number]' => $extraData['invoice_number'] ?? $user->profile?->payment_id ?? 'INV-VPA',
                '{payment_amount}' => $extraData['payment_amount'] ?? $user->profile?->paid_amount ?? '500',
                '[payment_amount]' => $extraData['payment_amount'] ?? $user->profile?->paid_amount ?? '500',
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
            Log::error("CandidateLifecycleMailService: Failed to send '{$templateName}' to {$user->email}: " . $e->getMessage());
            return false;
        }
    }
}
