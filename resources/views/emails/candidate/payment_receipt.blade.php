<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Payment Successful

Dear {{ $user->name }},

Thank you for your payment! We have successfully received your payment of **&#8377;{{ number_format($amount, 2) }}** for **{{ $description }}**.

Your payment receipt and invoice have been attached to this email as a PDF document for your records.

<x-mail::button :url="route('candidate.dashboard')" color="success">
Go to Dashboard
</x-mail::button>

If you have any questions regarding this transaction, please do not hesitate to contact us.

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
