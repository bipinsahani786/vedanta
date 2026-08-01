<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Candidate Profile Forwarded

Dear {{ $application->jobPost->contact_person ?? 'Employer' }},

We have shortlisted and forwarded a new candidate for your **{{ $application->jobPost->title }}** vacancy.

<x-mail::panel>
### Candidate Summary
**Name:** {{ $application->candidate->name }}  
**Subject Expertise:** {{ $application->candidate->profile->subject->name ?? 'N/A' }}  
**Experience:** {{ $application->candidate->profile->experience_years ?? 0 }} Years  
**Qualification:** {{ $application->candidate->profile->highestQualification->name ?? 'N/A' }}  
</x-mail::panel>

We have attached the candidate's resume to this email for your review. You can also log into your Employer Portal to view their full profile, schedule an interview, or update their application status.

<x-mail::button :url="route('employer.dashboard', ['fallback' => url('/')])" color="primary">
Log In To Portal
</x-mail::button>

If you have any questions or require further assistance, please contact our support team.

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
