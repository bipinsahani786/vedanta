<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Invoice Updated

Dear **{{ $invoice->candidate->name }}**,

Your Service Charge Invoice (ID: #{{ $invoice->id }}) has been recently **updated** by our team. 

<x-mail::panel>
### Updated Invoice Details:
- **Base Amount:** ₹{{ number_format($invoice->amount, 2) }}
- **Late Fee:** ₹{{ number_format($invoice->late_fee, 2) }}
- **Total Amount Due:** ₹{{ number_format($invoice->amount + $invoice->late_fee, 2) }}
- **Due Date:** {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M, Y') }}
- **Status:** <strong style="text-transform: uppercase;">{{ $invoice->status }}</strong>
</x-mail::panel>

@if($invoice->status !== 'paid')
Please ensure that the payment is completed before the due date to avoid further late fees.
<div style="text-align: center; margin: 30px 0;">
    <x-mail::button :url="route('candidate.serviceCharge.show')" color="primary">
    View & Pay Invoice
    </x-mail::button>
</div>
@else
Your invoice is marked as Paid. No further action is required from you for this invoice.
@endif

If you have any questions or need clarification regarding this update, please reply to this email or contact our support team.

Best regards,<br>
**The Vedanta Team**
</x-mail::message>
