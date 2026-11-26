<x-mail::message>
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 55px; max-width: 100%;">
</div>

# Candidate Sharing File Ready: {{ $sharingFile->title }}

Dear **{{ $schoolName }}**,

Vedanta Placement Agency has prepared a curated candidate sharing file for your institution.

@if(!empty($customNote))
> {{ $customNote }}
@endif

<x-mail::panel>
### File Overview
- **File Title:** {{ $sharingFile->title }}
- **Categories Included:** {{ !empty($categories) ? implode(', ', $categories) : 'General Shortlist' }}
- **Approved View Fields:** {{ implode(', ', $approvedFields) ?: 'Profile Summary' }}
@if($sharingFile->hasPassword())
@if(!empty($plainPassword))
- **File Access Password:** `{{ $plainPassword }}`
@else
- **Password Protection:** Protected (Password will be shared with you separately for security.)
@endif
@else
- **Password Protection:** No password required
@endif
</x-mail::panel>

<div style="text-align: center; margin: 24px 0;">
    <x-mail::button :url="$shareUrl" color="primary">
    Open Candidate Sharing File
    </x-mail::button>
</div>

*Note: Candidate profiles and CVs inside this file are configured for online view-only review.*

Regards,<br>
**Vedanta Placement Agency**
</x-mail::message>
