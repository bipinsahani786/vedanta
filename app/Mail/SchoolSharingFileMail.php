<?php

namespace App\Mail;

use App\Models\SchoolSharingFile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SchoolSharingFileMail extends Mailable
{
    use Queueable, SerializesModels;

    public SchoolSharingFile $sharingFile;

    public string $schoolName;

    public ?string $plainPassword;

    public ?string $customNote;

    public function __construct(
        SchoolSharingFile $sharingFile,
        string $schoolName,
        ?string $plainPassword = null,
        ?string $customNote = null
    ) {
        $this->sharingFile = $sharingFile;
        $this->schoolName = $schoolName;
        $this->plainPassword = $plainPassword;
        $this->customNote = $customNote;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Candidate Sharing File: {$this->sharingFile->title} - Vedanta Placement Agency",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.school.sharing_file_link',
            with: [
                'sharingFile' => $this->sharingFile,
                'schoolName' => $this->schoolName,
                'shareUrl' => $this->sharingFile->shareUrl(),
                'plainPassword' => $this->plainPassword,
                'customNote' => $this->customNote,
                'categories' => $this->sharingFile->categories()->pluck('name')->all(),
                'approvedFields' => $this->sharingFile->approvedFieldLabels(),
            ],
        );
    }
}
