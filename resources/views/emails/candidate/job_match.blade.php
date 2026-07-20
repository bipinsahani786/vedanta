<x-mail::message>
# Great news! We found a new job match for you.

Hi there,

A new job post has been approved that matches **{{ $matchScore }}%** of your profile preferences.

**Job Title:** {{ $job->title ?? 'Teacher' }}  
**Institution:** {{ $job->school_name }}  
**Location:** {{ $job->city?->name ?? 'N/A' }}  
**Subject:** {{ $job->subject->name }}  

<x-mail::button :url="route('candidate.applications.available')">
View Job & Apply
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
