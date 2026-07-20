<x-mail::message>
# Application Status Update

Dear {{ $application->candidate->name }},

There is an update regarding your application for the **{{ $application->jobPost->title }}** position at **{{ $application->jobPost->school_name }}**.

Your application status is now: **{{ ucfirst($application->status) }}**

@if($application->status === 'shortlisted')
Congratulations! Your profile has been shortlisted and forwarded to the school. We will let you know if the school schedules an interview.
@elseif($application->status === 'hired')
Congratulations! You have been selected for this position! Please check your dashboard for further instructions and service charge details.
@elseif($application->status === 'rejected')
Unfortunately, the school has decided to move forward with other candidates at this time. Don't worry, your remaining opportunities are still valid for other roles!
@endif

@if($application->remarks)
**Additional Remarks:**
{{ $application->remarks }}
@endif

@if($application->interview_date)
### Interview Details
**Date & Time:** {{ \Carbon\Carbon::parse($application->interview_date)->format('M d, Y h:i A') }}
@if($application->interview_link)
**Meeting Link / Location:** {{ $application->interview_link }}
@endif
@endif

<x-mail::button :url="route('candidate.applications.index')">
View Applications
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
