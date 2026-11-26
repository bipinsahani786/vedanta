<x-mail::message>
<div style="text-align: center; margin-bottom: 24px;">
    <img src="{{ asset('images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 54px; width: auto;">
</div>

@if($mode === 'signed_copy')
# Signed Agreement Confirmation

Hello **{{ $agreement->signer_name ?: ($agreement->recipient_name ?: $agreement->recipient_email) }}**,

Thank you for completing your digital signature on the **Vedanta Placement Agency Service Agreement** (`{{ $agreement->agreement_code }}`).

A PDF copy of your digitally executed agreement is attached to this email and can also be downloaded directly using the link below.

<x-mail::panel>
**Agreement Reference:** {{ $agreement->agreement_code }}<br>
**Recipient Email:** {{ $agreement->recipient_email }}<br>
**Signed By:** {{ $agreement->signer_name ?: $agreement->recipient_email }}<br>
**Signed On:** {{ \App\Models\StandaloneAgreement::formatIst($agreement->signed_at) }}
</x-mail::panel>

<x-mail::button :url="route('standalone-agreement.download', ['token' => $agreement->token])" color="primary">
Download Signed Agreement PDF
</x-mail::button>

@else
# {{ $mode === 'resend' ? 'Agreement Link Resent — Action Required' : 'Service Agreement — Digital Signature Requested' }}

Hello **{{ $agreement->recipient_name ?: $agreement->recipient_email }}**,

**Vedanta Placement Agency** has sent you a **Service Agreement** (`{{ $agreement->agreement_code }}`) for your review and online digital signature. No prior account registration, login, or payment is required to review and sign this document.

@if(!empty($agreement->admin_note))
<x-mail::panel>
**Note from Vedanta Placement Agency:**<br>
{{ $agreement->admin_note }}
</x-mail::panel>
@endif

<x-mail::panel>
**Agreement ID:** {{ $agreement->agreement_code }}<br>
**Recipient Email:** {{ $agreement->recipient_email }}<br>
@if($agreement->expires_at)
**Link Valid Until:** {{ \App\Models\StandaloneAgreement::formatIst($agreement->expires_at) }}
@endif
</x-mail::panel>

Click the button below to review the terms, draw or type your signature, and submit the agreement securely online:

<x-mail::button :url="$agreement->signing_url" color="primary">
Review &amp; Sign Agreement Now
</x-mail::button>

If the button does not open automatically, copy and paste this secure link into your browser:<br>
<span style="word-break: break-all; font-size: 12px; color: #0284c7;">{{ $agreement->signing_url }}</span>

@endif

---

Need assistance? Reply to this email or contact us at [info@vedantaplacementagency.in](mailto:info@vedantaplacementagency.in) or **+91-7070938975**.

Warm regards,<br>
**Vedanta Placement Agency®**  
Patna, Bihar, India
</x-mail::message>
