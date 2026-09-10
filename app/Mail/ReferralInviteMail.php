<?php

namespace App\Mail;

use App\Models\User;
use App\Models\ReferralEmailInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReferralInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $referrer;
    public ReferralEmailInvite $invite;
    public ?string $personalNote;

    public function __construct(
        User $referrer,
        ReferralEmailInvite $invite,
        ?string $personalNote = null
    ) {
        $this->referrer = $referrer;
        $this->invite = $invite;
        $this->personalNote = $personalNote;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->referrer->name} invited you to join Vedanta Placement Agency!"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.referral_invite',
        );
    }
}
