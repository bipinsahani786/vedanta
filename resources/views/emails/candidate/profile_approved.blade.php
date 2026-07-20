<x-mail::message>
# You Are Verified!

Dear {{ $user->name }},

Congratulations! We are thrilled to let you know that our team has successfully reviewed your submitted documents, and your profile is now **Officially Verified**.

You will now display the **Verified Badge** on your profile when schools review your application. This significantly increases your chances of being shortlisted for top teaching positions!

<x-mail::button :url="route('candidate.dashboard')">
View Your Dashboard
</x-mail::button>

Keep applying to jobs that match your profile. If you have any questions, feel free to reach out to us.

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
