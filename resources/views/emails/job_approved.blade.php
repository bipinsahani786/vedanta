<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Job Query Approved Successfully

Dear **{{ $job->contact_person ?? 'Employer' }}**,

We are pleased to inform you that your job query for the position of **{{ $job->title }}** at **{{ $job->school_name }}** has been **Approved** by our administrative team.

Your job post is now live on the Vedanta Placement Agency platform. We have already started using our AI matching engine to notify highly qualified candidates who match your requirements. 

<x-mail::panel>
### Job Details
**Job Title:** {{ $job->title }}  
**School/Institution:** {{ $job->school_name }}  
**Status:** Live & Approved
</x-mail::panel>

You can expect to start receiving candidate applications soon. If an employer account was created for you, you can log in to your dashboard to review applications directly.

<x-mail::button :url="url('/login')" color="success">
Login to Dashboard
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
