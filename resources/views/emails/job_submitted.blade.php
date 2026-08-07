<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Job Query Submitted Successfully

Dear **{{ $job->contact_person ?? 'Employer' }}**,

Thank you for choosing Vedanta Placement Agency! 

We have successfully received your job query for the position of **{{ $job->title }}** at **{{ $job->school_name }}**. 

Your requirement has been submitted to our team and is currently **Pending Approval**. Our administrative team will review the details shortly. Once approved, your job post will be live on our platform and we will start matching the best candidates for your institution.

<x-mail::panel>
### Job Query Details
**Job Title:** {{ $job->title }}  
**School/Institution:** {{ $job->school_name }}  
**Status:** Pending Approval
</x-mail::panel>

We will notify you again via email once your job query has been approved.

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
