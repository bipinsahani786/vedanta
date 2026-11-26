<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TemporaryPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $candidate;

    public string $temporaryPassword;

    public function __construct(User $candidate, string $temporaryPassword)
    {
        $this->candidate = $candidate;
        $this->temporaryPassword = $temporaryPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Temporary Login Password — Vedanta Placement Agency®',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.temporary_password',
            with: [
                'candidate' => $this->candidate,
                'temporaryPassword' => $this->temporaryPassword,
                'loginUrl' => route('login'),
                'resetUrl' => route('password.request'),
            ],
        );
    }
}
