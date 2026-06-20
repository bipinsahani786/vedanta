<x-mail::message>
# Great news! We found a new job match for you.

Hi there,

A new job post has been approved that matches **{{ $matchScore }}%** of your profile preferences.

**Job Title:** {{ $job->title ?? 'Teacher' }}  
**Institution:** {{ $job->school_name }}  
**Location:** {{ $job->location->city }}  
**Subject:** {{ $job->subject->name }}  

<x-mail::button :url="route('candidate.applications.available')">
View Job & Apply
</x-mail::button>

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
