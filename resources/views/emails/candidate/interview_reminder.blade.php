<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Interview Reminder

Dear {{ $application->candidate->name }},

This is a quick reminder that your interview for the **{{ $application->jobPost->title }}** position at **{{ $application->jobPost->school_name }}** is scheduled for tomorrow.

<x-mail::panel>
### Interview Details
**Date & Time:** {{ \Carbon\Carbon::parse($application->interview_date)->format('l, F j, Y \a\t g:i A') }}  

@if($application->interview_link)
**Meeting Link / Location:** [Click here to join]({{ $application->interview_link }})  
*(Or copy and paste this link: {{ $application->interview_link }})*
@endif

@if($application->remarks)
**Special Instructions:**  
{{ $application->remarks }}
@endif
</x-mail::panel>

Please ensure you are prepared and arrive (or log in) at least 5 minutes early.

<x-mail::button :url="route('candidate.applications.index')" color="success">
View Application Details
</x-mail::button>

Best of luck!

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
