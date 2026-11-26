<?php

namespace App\Notifications;

use App\Models\CandidateAccessRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SchoolProfileAccessReviewed extends Notification
{
    use Queueable;

    public CandidateAccessRequest $accessRequest;

    public string $candidateLabel;

    public function __construct(CandidateAccessRequest $accessRequest, string $candidateLabel)
    {
        $this->accessRequest = $accessRequest;
        $this->candidateLabel = $candidateLabel;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusLabel = $this->accessRequest->statusLabel();

        if ($this->accessRequest->status === CandidateAccessRequest::STATUS_DECLINED) {
            $reason = $this->accessRequest->admin_notes ? " Reason: {$this->accessRequest->admin_notes}" : '';
            $message = "Your access request for {$this->candidateLabel} was declined.{$reason}";
        } else {
            $perms = implode(', ', $this->accessRequest->grantedPermissionLabels()) ?: 'Selected Details';
            $via = match ($this->accessRequest->delivery_method) {
                CandidateAccessRequest::DELIVERY_EMAIL => 'shared via Email',
                CandidateAccessRequest::DELIVERY_BOTH => 'unlocked on School Dashboard & emailed',
                default => 'unlocked on School Dashboard',
            };
            $message = "Access for {$this->candidateLabel} is {$statusLabel} ({$perms}) — {$via}.";
        }

        return [
            'title' => "Profile Access Request: {$statusLabel}",
            'message' => $message,
            'request_id' => $this->accessRequest->id,
            'status' => $this->accessRequest->status,
            'action_url' => route('employer.talent.index', ['tab' => 'requests'], false),
        ];
    }
}
