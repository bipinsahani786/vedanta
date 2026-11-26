<?php

namespace App\Mail;

use App\Models\CandidateAccessRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SchoolCandidateAccessSharedMail extends Mailable
{
    use Queueable, SerializesModels;

    public CandidateAccessRequest $accessRequest;

    public string $schoolName;

    public string $candidateRef;

    public function __construct(CandidateAccessRequest $accessRequest, string $schoolName, string $candidateRef)
    {
        $this->accessRequest = $accessRequest;
        $this->schoolName = $schoolName;
        $this->candidateRef = $candidateRef;
    }

    public function envelope(): Envelope
    {
        $statusLabel = $this->accessRequest->statusLabel();

        return new Envelope(
            subject: "Teacher Profile Access ({$statusLabel}): {$this->candidateRef} - Vedanta Placement Agency",
        );
    }

    public function content(): Content
    {
        $candidate = $this->accessRequest->candidate;
        $profile = $candidate?->profile;

        return new Content(
            markdown: 'emails.school.candidate_access_shared',
            with: [
                'accessRequest' => $this->accessRequest,
                'schoolName' => $this->schoolName,
                'candidateRef' => $this->candidateRef,
                'candidate' => $candidate,
                'profile' => $profile,
                'grantedLabels' => $this->accessRequest->grantedPermissionLabels(),
                'dashboardUrl' => route('employer.talent.index', ['tab' => 'requests']),
            ],
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        $candidate = $this->accessRequest->candidate;
        $profile = $candidate?->profile;
        $safeRef = preg_replace('/[^A-Za-z0-9_\-]/', '_', $this->candidateRef);

        if ($this->accessRequest->can_view_cv && $profile && ! empty($profile->resume_path) && Storage::disk('public')->exists($profile->resume_path)) {
            $ext = strtolower(pathinfo($profile->resume_path, PATHINFO_EXTENSION) ?: 'pdf');
            $resumePath = $profile->resume_path;
            $attachments[] = Attachment::fromData(
                fn () => Storage::disk('public')->get($resumePath),
                "{$safeRef}_CV.{$ext}"
            );
        }

        if ($this->accessRequest->can_view_photo && $profile) {
            $photoPath = $profile->profile_photo_path ?: ($profile->live_photo_path ?: $profile->passport_photo_path);
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION) ?: 'jpg');
                $attachments[] = Attachment::fromData(
                    fn () => Storage::disk('public')->get($photoPath),
                    "{$safeRef}_Photo.{$ext}"
                );
            }
        }

        return $attachments;
    }
}
