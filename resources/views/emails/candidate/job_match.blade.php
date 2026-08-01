<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Great News! A New Job Opportunity is Available.

Hi there,

We have a new open position that aligns with your profile preferences. Please review the details below:

<x-mail::panel>
### Job Details

**Position:** {{ $job->title ?? 'Teacher' }}  
**Institution:** {{ $job->school_name }}  
**Subject:** {{ $job->subject->name ?? 'N/A' }}  
**Location:** {{ $job->city?->name ?? 'N/A' }}
</x-mail::panel>

<x-mail::button :url="route('candidate.applications.available')" color="success">
View Job Details & Apply
</x-mail::button>

If you have any questions or need assistance with your application, feel free to reach out to our support team.

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
