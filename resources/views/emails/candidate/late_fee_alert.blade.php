<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Late Fee Notice

Dear {{ $invoice->candidate->name ?? 'Candidate' }},

This is an automated notice that your Service Charge Invoice is now overdue, and a daily late fee has been applied.

An additional **₹{{ number_format($difference, 2) }}** has been added to your outstanding balance.

<x-mail::panel>
### Updated Invoice Details
**Original Amount:** ₹{{ number_format($invoice->amount, 2) }}  
**Total Late Fees:** ₹{{ number_format($invoice->late_fee, 2) }}  
**Total Amount Due:** ₹{{ number_format($invoice->amount + $invoice->late_fee, 2) }}  
**Due Date:** {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}  
</x-mail::panel>

Please settle this balance at your earliest convenience to prevent further daily late charges (₹300/day).

<x-mail::button :url="route('candidate.serviceCharge.show')" color="error">
Pay Overdue Invoice
</x-mail::button>

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
