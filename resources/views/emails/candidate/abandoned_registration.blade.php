<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Complete Your Registration

Dear {{ $user->name }},

We noticed you started setting up your profile at Vedanta Placement Agency, but haven't quite finished yet.

To start applying for your desired teaching jobs, please complete your profile registration. This involves uploading your required documents and processing your one-time registration fee.

<x-mail::button :url="route('candidate.payment.show')" color="success">
Complete Your Registration
</x-mail::button>

If you ran into any issues or have questions about the process, we are here to help. Just reply to this email or contact us using the details below.

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
