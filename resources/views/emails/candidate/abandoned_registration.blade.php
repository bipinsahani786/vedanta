<x-mail::message>
# You're Almost There!

Dear {{ $user->name }},

We noticed you started setting up your profile at Vedanta Placement Agency, but haven't quite finished yet!

To start applying for your dream teaching jobs, you just need to complete your profile registration. This involves uploading your documents and processing your one-time registration fee.

<x-mail::button :url="route('candidate.payment.show')">
Complete Your Registration
</x-mail::button>

If you ran into any issues or have questions about the process, we are here to help. Just reply to this email or contact us below.

<x-mail::panel>
**Get In Touch**  
Career Point Building, 2nd floor,  
Patna, 800001, Bihar

**Email:** info@vedantaplacementagency.in  
**Phone:** +91-7070938975
</x-mail::panel>

Best regards,<br>
**Vedanta Placement Agency**
</x-mail::message>
