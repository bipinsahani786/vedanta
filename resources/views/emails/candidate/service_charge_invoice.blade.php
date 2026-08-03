<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Service Charge Invoice

Dear {{ $invoice->candidate->name ?? 'Candidate' }},

Congratulations on your recent placement! 

An invoice for your service charge has been generated. Please review the details below and ensure the payment is made before the due date to avoid any late fees.

<x-mail::panel>
### Invoice Details
**Amount Due:** &#8377;{{ number_format($invoice->amount, 2) }}  
**Due Date:** {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}  
**Status:** {{ ucfirst($invoice->status) }}  
</x-mail::panel>

<x-mail::button :url="route('candidate.serviceCharge.show')" color="primary">
Pay Invoice Now
</x-mail::button>

If you have already made this payment offline, please contact us so we can update our records.

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
