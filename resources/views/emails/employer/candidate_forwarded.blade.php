<x-mail::message>
# New Candidate Profile Forwarded

Dear {{ $application->jobPost->contact_person ?? 'Employer' }},

We have shortlisted and forwarded a new candidate for your **{{ $application->jobPost->title }}** vacancy.

### Candidate Summary
**Name:** {{ $application->candidate->name }}  
**Subject Expertise:** {{ $application->candidate->profile->subject->name ?? 'N/A' }}  
**Experience:** {{ $application->candidate->profile->experience_years ?? 0 }} Years  
**Qualification:** {{ $application->candidate->profile->highestQualification->name ?? 'N/A' }}  

We have attached their resume to this email for your review. You can also log into your Employer Portal to view their full profile, schedule an interview, or update their application status.

<x-mail::button :url="route('employer.dashboard', ['fallback' => url('/')])">
Log In To Portal
</x-mail::button>

If you have any questions, please contact our support team.

Best regards,<br>
**Vedanta Placement Agency**
</x-mail::message>
