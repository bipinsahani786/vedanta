<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Welcome to Vedanta Placement Agency

Dear {{ $user->name }},

Your registration with Vedanta Placement Agency has been successfully completed. 

We are excited to have you on board. You are now one step closer to finding your ideal teaching position.

<x-mail::panel>
### Your Plan Details
**Plan:** {{ ucfirst($user->profile->plan_type ?? 'Standard') }} Plan
@if($user->profile->plan_type === 'standard')
**Paid Amount:** &#8377;{{ $user->profile->paid_amount ?? 500 }}  
**Pending Amount:** &#8377;{{ $user->profile->pending_amount ?? 500 }}  
*(Pending amount will be collected during the final stage of registration)*
@else
**Paid Amount:** &#8377;{{ $user->profile->paid_amount ?? 1000 }} (Fully Paid)
@endif
</x-mail::panel>

We have attached your digitally signed **Registration Agreement** and your **Payment Invoice** to this email for your records. You can also view them anytime from your dashboard.

<x-mail::button :url="route('candidate.dashboard')" color="success">
Go to Dashboard
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
