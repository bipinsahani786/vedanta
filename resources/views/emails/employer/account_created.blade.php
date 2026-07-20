<x-mail::message>
# Welcome to Vedanta Placement Agency!

Dear {{ $user->name }},

Great news! Your recent job posting has been successfully **approved** and is now live on our platform. 

To help you manage your job postings, view candidates, and update application statuses, we have automatically created an Employer Account for you.

### Your Login Credentials
**Login URL:** [{{ route('login') }}]({{ route('login') }})  
**Email:** {{ $user->email }}  
**Temporary Password:** `{{ $password }}`

*(We strongly recommend changing your password after your first login.)*

<x-mail::button :url="route('login')">
Log In To Employer Portal
</x-mail::button>

If you have any questions or need assistance navigating the portal, our support team is here to help.

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
