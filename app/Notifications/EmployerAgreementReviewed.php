<?php

namespace App\Notifications;

use App\Models\EmployerProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmployerAgreementReviewed extends Notification
{
    use Queueable;

    public EmployerProfile $profile;

    public string $decision;

    public ?string $reason;

    public function __construct(EmployerProfile $profile, string $decision, ?string $reason = null)
    {
        $this->profile = $profile;
        $this->decision = $decision;
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $schoolName = $this->profile->school_name ?: 'Your Institution';

        if ($this->decision === 'approved') {
            return [
                'title' => 'Institution Agreement Approved',
                'message' => "Your signed agreement for {$schoolName} has been approved by the Vedanta Placement Agency admin team.",
                'status' => 'approved',
                'action_url' => route('employer.dashboard', [], false),
            ];
        }

        $reasonSuffix = !empty($this->reason) ? " Reason: {$this->reason}" : '';

        return [
            'title' => 'Institution Agreement Needs Re-upload (Rejected)',
            'message' => "Your uploaded agreement for {$schoolName} was rejected.{$reasonSuffix} Please re-upload a corrected signed copy.",
            'status' => 'rejected',
            'action_url' => route('employer.dashboard', [], false),
        ];
    }
}
