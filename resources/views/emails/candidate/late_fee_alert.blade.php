<x-mail::message>
# URGENT: Late Fee Added

Dear {{ $invoice->candidate->name ?? 'Candidate' }},

This is an automated notice that your Service Charge Invoice is now overdue and a daily late fee has been applied.

An additional **₹{{ number_format($difference, 2) }}** has been added to your outstanding balance.

### Updated Invoice Details
**Original Amount:** ₹{{ number_format($invoice->amount, 2) }}  
**Total Late Fees:** ₹{{ number_format($invoice->late_fee, 2) }}  
**Total Amount Due:** ₹{{ number_format($invoice->amount + $invoice->late_fee, 2) }}  
**Due Date:** {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}  

Please settle this balance immediately to prevent further daily late charges (₹300/day).

<x-mail::button :url="route('candidate.serviceCharge.show')">
Pay Overdue Invoice
</x-mail::button>

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
