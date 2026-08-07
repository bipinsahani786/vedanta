<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 60px; max-width: 100%;">
</div>

# Welcome to Vedanta Placement Agency!

Dear **{{ $user->name }}**,

Thank you for registering your school/institution with Vedanta Placement Agency. Your employer account has been successfully created.

We connect top-tier teaching and administrative professionals with premier institutions like yours. Our platform is designed to make your hiring process fast, efficient, and seamless.

<x-mail::panel>
### What's Next?
1. **Post Jobs:** Login to your dashboard to post new job vacancies.
2. **Review Applicants:** Screen and manage candidates directly from your panel.
3. **Hire the Best:** Let our AI matching engine find the most suitable candidates for your needs.
</x-mail::panel>

<x-mail::button :url="route('employer.dashboard')" color="primary">
Login to Employer Dashboard
</x-mail::button>

<x-mail::panel>
### Need Help?
**Vedanta Placement Agency**  
Career Point Building, 2nd floor, Patna, 800001, Bihar  
**Website:** [vedantaplacementagency.in](https://vedantaplacementagency.in)  
**Email:** info@vedantaplacementagency.in  
**Phone:** +91-7070938975
</x-mail::panel>

We look forward to a successful partnership.

Best regards,<br>
**The Vedanta Team**
</x-mail::message>
