<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

@php
    $profile = $candidate->profile;
    $isSigned = $profile && ($profile->is_agreement_signed || !empty($profile->agreement_pdf_path) || !empty($profile->signature_date_time));
@endphp

@if($isSigned)
# Your Registration Agreement - Vedanta Placement Agency

Dear **{{ $candidate->name }}**,

Your registration agreement with **Vedanta Placement Agency** is signed and on record.

<x-mail::panel>
### Access Your Agreement
You can review, verify, and download your signed registration agreement anytime by accessing your candidate portal below.
</x-mail::panel>

<div style="text-align: center; margin: 30px 0;">
    <x-mail::button :url="$signUrl" color="primary">
    View Signed Agreement
    </x-mail::button>
</div>
@else
# Action Required: Sign Your Registration Agreement

Dear **{{ $candidate->name }}**,

Your registration profile has been successfully created with **Vedanta Placement Agency**.

To finalize your onboarding process and activate your profile, you need to sign the Registration Agreement. This agreement outlines our terms of service, payment policies, and mutual responsibilities.

<x-mail::panel>
### Final Step
Please log in to your account and review the agreement. Once signed, your profile will be fully active and our team will start matching you with the best teaching opportunities!
</x-mail::panel>

<div style="text-align: center; margin: 30px 0;">
    <x-mail::button :url="$signUrl" color="primary">
    Sign Agreement Now
    </x-mail::button>
</div>
@endif

If you have any questions or need assistance, feel free to reply directly to this email.

<x-mail::panel>
### Contact Us
**Vedanta Placement Agency**  
Career Point Building, 2nd floor,  
Patna, 800001, Bihar

**Website:** [vedantaplacementagency.in](https://vedantaplacementagency.in)  
**Email:** info@vedantaplacementagency.in  
**Phone:** +91-7070938975
</x-mail::panel>

Best regards,<br>
**The Vedanta Team**
</x-mail::message>
