<x-mail::message>
<div style="text-align: center; margin-bottom: 24px;">
    <img src="{{ asset('images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 52px; width: auto;">
</div>

# Temporary Password Issued

Hello **{{ $candidate->name }}**,

An administrator at **Vedanta Placement Agency®** has set a **temporary password** for your candidate account so you can sign in cleanly.

<x-mail::panel>
**Login Email:** {{ $candidate->email }}<br>
**Temporary Password:** `{{ $temporaryPassword }}`
</x-mail::panel>

> **Important Security Notice:** For your account security, passwords are stored encrypted and cannot be viewed by staff. You will be **required to set a new private password immediately upon your next login**.

<x-mail::button :url="$loginUrl" color="primary">
Login to Candidate Portal
</x-mail::button>

If you prefer to set a new password directly using self-service recovery instead of signing in with this temporary password, you can also use our password recovery link:<br>
<a href="{{ $resetUrl }}">{{ $resetUrl }}</a>

---

If you did not expect this change, please reset your password immediately or contact [info@vedantaplacementagency.in](mailto:info@vedantaplacementagency.in) / **+91-7070938975**.

Warm regards,<br>
**Vedanta Placement Agency®**
</x-mail::message>
