<x-mail::message>
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ url('/images/logo.png') }}" alt="Vedanta Placement Agency" style="height: 55px; max-width: 100%;">
</div>

# Candidate Profile Access: {{ $accessRequest->statusLabel() }}

Dear **{{ $schoolName }}**,

Your request to access candidate profile **{{ $candidateRef }}** has been reviewed by the Vedanta Placement Agency admin team.

**Approved Permissions:** {{ implode(', ', $grantedLabels) ?: 'None' }}  
**Delivery Method:** {{ ucfirst($accessRequest->delivery_method) }}

<x-mail::panel>
### Basic Profile Overview
- **Candidate ID:** {{ $profile?->vpa_id ?? ('#'.$candidate?->id) }}
- **Category / Subject:** {{ $profile?->category?->name ?? 'General' }} · {{ $profile?->subject?->name ?? 'General' }}
- **Highest Qualification:** {{ $profile?->highestQualification?->name ?? 'N/A' }}
- **Experience:** {{ (int) ($profile?->experience_years ?? 0) }} Year(s)
- **Current / Last Salary:** {{ $profile?->current_salary ?: 'Not Disclosed' }}
- **Availability to Join:** {{ $profile?->availability_to_join ?: 'On Inquiry' }}
</x-mail::panel>

@if($accessRequest->can_view_full_profile)
<x-mail::panel>
### Unlocked Full Profile Details
- **Full Name:** {{ $candidate?->name }}
- **Gender:** {{ $profile?->gender ?? 'N/A' }}
- **Current / Last School:** {{ $profile?->current_school ?: 'N/A' }}
- **Expected Salary:** {{ $profile?->expected_salary ?: 'N/A' }}
- **Preferred Location:** {{ trim(($profile?->preferredCity?->name ?? '') . ($profile?->preferredState?->name ? ', ' . $profile->preferredState->name : ''), ', ') ?: 'Flexible' }}
- **Other Qualifications:** {{ $profile?->other_qualifications ?: 'N/A' }}
- **English Fluency:** {{ $profile?->english_fluency ? ucfirst($profile->english_fluency) : 'N/A' }}
- **Residential Preference:** {{ $profile?->residential_preference ? ucwords(str_replace('_', ' ', $profile->residential_preference)) : 'N/A' }}
- **Address:** {{ $profile?->address ?: 'N/A' }}
</x-mail::panel>
@endif

@if($accessRequest->can_view_contact)
<x-mail::panel>
### Unlocked Contact Information
- **Email:** {{ $candidate?->email ?? 'N/A' }}
- **Phone / Mobile:** {{ $candidate?->phone ?? 'N/A' }}
</x-mail::panel>
@endif

@if($accessRequest->can_view_photo || $accessRequest->can_view_cv)
@if($accessRequest->can_view_photo)
- **Profile Photo:** Attached with this email{{ $accessRequest->allowsDashboardView() ? ' (also unlocked on your School Dashboard)' : '' }}.
@endif
@if($accessRequest->can_view_cv)
- **Resume / CV:** Attached with this email (if uploaded by candidate){{ $accessRequest->allowsDashboardView() ? ' and available from your School Dashboard' : '' }}.
@endif
@endif

@if(!empty($accessRequest->admin_notes))
**Admin Notes:** {{ $accessRequest->admin_notes }}
@endif

@if($accessRequest->allowsDashboardView())
<div style="text-align: center; margin: 24px 0;">
    <x-mail::button :url="$dashboardUrl" color="primary">
    View in School Dashboard
    </x-mail::button>
</div>
@endif

Regards,<br>
**Vedanta Placement Agency**
</x-mail::message>
