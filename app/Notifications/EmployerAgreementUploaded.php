<?php

namespace App\Notifications;

use App\Models\EmployerProfile;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmployerAgreementUploaded extends Notification
{
    use Queueable;

    public User $employer;

    public EmployerProfile $profile;

    public function __construct(User $employer, EmployerProfile $profile)
    {
        $this->employer = $employer;
        $this->profile = $profile;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $schoolName = $this->profile->school_name ?: $this->employer->name;

        return [
            'title' => 'School Agreement Uploaded for Approval',
            'message' => "{$schoolName} ({$this->employer->email}) uploaded a signed institution agreement and is awaiting Admin approval.",
            'employer_id' => $this->employer->id,
            'action_url' => route('admin.schools.show', $this->employer->id, false) . '#agreement-section',
        ];
    }
}
