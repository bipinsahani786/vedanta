<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Welcome to Vedanta Placement Agency

Dear {{ $user->name }},

We are pleased to inform you that your recent job posting has been successfully **approved** and is now live on our platform. 

To help you manage your job postings, view candidate profiles, and update application statuses, we have automatically created an Employer Account for you.

<x-mail::panel>
### Your Login Credentials
**Login URL:** [{{ route('login') }}]({{ route('login') }})  
**Email:** {{ $user->email }}  
**Temporary Password:** `{{ $password }}`

*(We strongly recommend changing your password after your first login.)*
</x-mail::panel>

<x-mail::button :url="route('login')" color="success">
Log In To Employer Portal
</x-mail::button>

If you have any questions or need assistance navigating the portal, our support team is here to help.

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
