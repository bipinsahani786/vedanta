<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\DynamicTemplateMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBulkTemplateEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userIds;
    protected $subjectTemplate;
    protected $bodyTemplate;

    public function __construct(array $userIds, $subjectTemplate, $bodyTemplate)
    {
        $this->userIds = $userIds;
        $this->subjectTemplate = $subjectTemplate;
        $this->bodyTemplate = $bodyTemplate;
    }

    public function handle(): void
    {
        $users = User::with(['profile.category', 'profile.subject', 'employerProfile'])->whereIn('id', $this->userIds)->get();

        foreach ($users as $user) {
            try {
                $replacements = [
                    '{name}' => $user->name,
                    '{email}' => $user->email,
                    '{phone}' => $user->phone ?? '',
                ];

                if ($user->role === 'candidate') {
                    $replacements['{category}'] = $user->profile?->category?->name ?? '';
                    $replacements['{subject}'] = $user->profile?->subject?->name ?? '';
                    $replacements['{plan_type}'] = $user->profile?->plan_type ?? '';
                    $replacements['{invoice_number}'] = $user->profile?->payment_id ?? '';
                    $replacements['{payment_amount}'] = $user->profile?->payment_amount ?? '';
                } elseif ($user->role === 'employer') {
                    $replacements['{company_name}'] = $user->employerProfile?->company_name ?? '';
                    $replacements['{job_title}'] = ''; // Depends on context, leave empty for general
                }

                $subject = str_replace(array_keys($replacements), array_values($replacements), $this->subjectTemplate);
                $body = str_replace(array_keys($replacements), array_values($replacements), $this->bodyTemplate);

                Mail::to($user->email)->send(new DynamicTemplateMail($subject, $body));

            } catch (\Exception $e) {
                Log::error("Failed to send bulk email to {$user->email}: " . $e->getMessage());
            }
        }
    }
}
