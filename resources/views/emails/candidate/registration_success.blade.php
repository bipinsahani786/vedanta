<x-mail::message>
# Welcome to Vedanta Placement Agency!

Dear {{ $user->name }},

Congratulations! Your registration with Vedanta Placement Agency has been successfully completed. 

We are excited to have you on board. You are now a step closer to finding your dream teaching job.

### Your Plan Details
**Plan:** {{ ucfirst($user->profile->plan_type ?? 'Standard') }} Plan
@if($user->profile->plan_type === 'standard')
**Paid Amount:** ₹{{ $user->profile->paid_amount ?? 500 }}  
**Pending Amount:** ₹{{ $user->profile->pending_amount ?? 500 }}  
*(Pending amount will be collected during the final stage of registration)*
@else
**Paid Amount:** ₹{{ $user->profile->paid_amount ?? 1000 }} (Fully Paid)
@endif

We have attached your digitally signed **Registration Agreement** to this email for your records. You can also view it anytime from your dashboard.

<x-mail::button :url="route('candidate.dashboard')">
Go to Dashboard
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
