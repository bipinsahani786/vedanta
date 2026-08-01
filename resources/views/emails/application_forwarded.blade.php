<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Application Shortlisted

Dear **{{ $application->candidate->name }}**,

We have great news! Your application for the position of **{{ $application->jobPost->title }}** at **{{ $application->jobPost->employer->name ?? 'our partner school' }}** has been reviewed and shortlisted.

Your profile has been forwarded to the school administration. The school will directly contact you or schedule an interview with you shortly. Please keep an eye on your email and phone.

@if($application->remarks)
<x-mail::panel>
**Admin Remarks:**  
{{ $application->remarks }}
</x-mail::panel>
@endif

To check the status of your applications, you can log in to your candidate dashboard.

<x-mail::button :url="route('login')" color="success">
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
