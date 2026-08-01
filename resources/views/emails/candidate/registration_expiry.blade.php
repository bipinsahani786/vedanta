<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Registration Limit Notice

Dear {{ $user->name }},

We noticed you have been actively applying for jobs on our platform. 

This is a quick notification to let you know that you have only **{{ $remaining }}** opportunity(s) remaining on your current registration plan.

Once you exhaust all your allowed applications, you will need to renew your plan to continue applying for new positions.

<x-mail::button :url="route('candidate.dashboard')">
View Your Dashboard
</x-mail::button>

We wish you the best of luck with your current applications! If you have any questions about renewing your plan, please contact our support team.

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
