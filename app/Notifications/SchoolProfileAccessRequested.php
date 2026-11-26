<?php

namespace App\Notifications;

use App\Models\CandidateAccessRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SchoolProfileAccessRequested extends Notification
{
    use Queueable;

    public CandidateAccessRequest $accessRequest;

    public string $schoolName;

    public string $candidateLabel;

    public function __construct(CandidateAccessRequest $accessRequest, string $schoolName, string $candidateLabel)
    {
        $this->accessRequest = $accessRequest;
        $this->schoolName = $schoolName;
        $this->candidateLabel = $candidateLabel;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $scopes = implode(', ', $this->accessRequest->requestedPermissionLabels()) ?: 'Profile Access';

        return [
            'title' => 'School Requested Candidate Profile Access',
            'message' => "{$this->schoolName} requested unlock ({$scopes}) for {$this->candidateLabel}.",
            'request_id' => $this->accessRequest->id,
            'school_id' => $this->accessRequest->school_user_id,
            'candidate_id' => $this->accessRequest->candidate_user_id,
            'action_url' => route('admin.access-requests.show', $this->accessRequest->id, false),
        ];
    }
}
