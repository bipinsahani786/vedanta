<x-mail::message>
<div style="text-align: center; margin-bottom: 24px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 56px; max-width: 100%;">
</div>

# New Job Alert: {{ $job->title ?? 'School Vacancy Available' }}

Hello,

A new verified vacancy matching our Daily Job Updates has just been published on **Vedanta Placement Agency®**:

<x-mail::panel>
### Vacancy Overview

**Role / Position:** {{ $job->title ?? 'Teaching / School Staff' }} ({{ $job->job_code }})  
@if(!empty($job->category?->name))
**Category:** {{ $job->category->name }}  
@endif
@if(!empty($job->subject?->name))
**Subject / Specialization:** {{ $job->subject->name }}  
@endif
@if(!empty($job->qualification?->name))
**Qualification Required:** {{ $job->qualification->name }}  
@endif
**Location:** {{ $job->state?->name ?? 'India' }}  
**Salary:** {{ $job->formatted_salary }}
</x-mail::panel>

@if(!empty($job->description))
{{ \Illuminate\Support\Str::limit(trim(strip_tags($job->description)), 220) }}
@endif

<x-mail::button :url="$jobUrl" color="success">
View Vacancy Details
</x-mail::button>

If you need any help, write to our support team at **info@vedantaplacementagency.in** or call **+91-7070938975**.

---

<p style="font-size: 12px; color: #64748b; line-height: 1.5; margin-top: 16px;">
You are receiving this free email alert because <strong>{{ $subscriber->email }}</strong> subscribed to Daily Job Updates on Vedanta Placement Agency®.
If you no longer wish to receive these alerts, you can <a href="{{ $unsubscribeUrl }}" style="color: #0f172a; font-weight: bold;">unsubscribe anytime in one click</a>.
</p>

Best regards,<br>
**Vedanta Placement Agency® Team**
</x-mail::message>
