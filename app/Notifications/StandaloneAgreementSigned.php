<?php

namespace App\Notifications;

use App\Models\StandaloneAgreement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StandaloneAgreementSigned extends Notification
{
    use Queueable;

    public StandaloneAgreement $agreement;

    public function __construct(StandaloneAgreement $agreement)
    {
        $this->agreement = $agreement;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $signer = $this->agreement->signer_name ?: $this->agreement->recipient_email;

        return [
            'title' => 'Agreement Signed (' . $this->agreement->agreement_code . ')',
            'message' => "{$signer} ({$this->agreement->recipient_email}) digitally signed and submitted the standalone agreement.",
            'agreement_id' => $this->agreement->id,
            'agreement_code' => $this->agreement->agreement_code,
            'recipient_email' => $this->agreement->recipient_email,
            'action_url' => route('admin.standalone-agreements.index', ['highlight' => $this->agreement->id], false),
        ];
    }
}
