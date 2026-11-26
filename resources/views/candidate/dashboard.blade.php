@extends('layouts.candidate')

@section('candidate_content')
{{-- Popup Modal for Incomplete Registration / Profile --}}
@include('candidate.partials.incomplete-profile-modal')

@php
    $dashUser = auth()->user();
    $istHour = (int) now('Asia/Kolkata')->format('G');
    $timeGreeting = $istHour < 12 ? 'Good Morning' : ($istHour < 17 ? 'Good Afternoon' : 'Good Evening');

    $hasPaidPlan = (bool) ($profile && $profile->has_paid_plan);
    $isPlanActive = (bool) ($profile && $profile->is_plan_active);
    $isPlanExpired = (bool) ($profile && $profile->is_plan_expired);
    $isAgreementSigned = (bool) ($profile && ($profile->is_agreement_signed || !empty($profile->signature_date_time) || !empty($profile->agreement_pdf_path) || $profile->is_manual_agreement));

    $planStartDate = null;
    if ($hasPaidPlan) {
        $planStartDate = $profile->plan_started_at
            ? \Carbon\Carbon::parse($profile->plan_started_at)
            : ($dashUser->paymentTransactions()->where('status', 'success')->latest()->first()?->created_at ?? $profile->registration_completed_at);
    }

    $planMonths = $profile ? $profile->plan_duration_months : 3;
    $planExpiryDate = ($hasPaidPlan && $planStartDate)
        ? ($profile->plan_validity_date ?? $planStartDate->copy()->addMonths($planMonths))
        : null;

    $planDaysNote = null;
    if ($planExpiryDate) {
        $nowIstDay = now('Asia/Kolkata')->startOfDay();
        $expIstDay = $planExpiryDate->copy()->timezone('Asia/Kolkata')->startOfDay();
        $diffDays = (int) $nowIstDay->diffInDays($expIstDay, false);
        if ($diffDays > 0) {
            $planDaysNote = $diffDays . ' ' . ($diffDays === 1 ? 'day' : 'days') . ' left';
        } elseif ($diffDays === 0) {
            $planDaysNote = 'Expires today';
        } else {
            $daysAgo = abs($diffDays);
            $planDaysNote = 'Expired ' . $daysAgo . ' ' . ($daysAgo === 1 ? 'day' : 'days') . ' ago';
        }
    }

    $usedApplications = (int) ($profile->used_applications ?? 0);
    $totalAllowedApplications = (int) ($profile->total_allowed_applications ?? ($profile && $profile->plan_type === 'premium' ? 3 : 2));
    $placedCount = (int) ($placedCount ?? 0);

    $isProfileDetailsDone = (bool) ($profile && $profile->hasCompletedProfileDetails());
    $isRegistrationComplete = (bool) (($profile && $profile->isRegistrationCompleted()) || ($isAgreementSigned && $hasPaidPlan && $isProfileDetailsDone));
    $isVerified = (bool) ($profile && $profile->is_verified);
    $verificationRequested = (bool) ($verificationRequested ?? false);
    $verificationRequestedAt = $verificationRequestedAt ?? null;
    $hasResume = (bool) ($profile && !empty($profile->resume_path));
    $hasPhoto = (bool) ($profile && !empty($profile->profile_photo_path));

    // Missing profile fields checklist (matches ProfileController & Wizard fields)
    $personalMissing = [];
    if (!$profile || empty($profile->date_of_birth)) { $personalMissing[] = 'Date of Birth'; }
    if (!$profile || empty($profile->gender)) { $personalMissing[] = 'Gender'; }
    if (!$profile || empty($profile->address)) { $personalMissing[] = 'Full Address'; }

    $academicMissing = [];
    if (!$profile || empty($profile->category_id)) { $academicMissing[] = 'Job Category'; }
    if (!$profile || empty($profile->subject_id)) { $academicMissing[] = 'Subject'; }
    if (!$profile || empty($profile->highest_qualification_id)) { $academicMissing[] = 'Highest Qualification'; }

    $preferencesMissing = [];
    if (!$profile || empty($profile->preferred_state_id)) { $preferencesMissing[] = 'Preferred State'; }
    if (!$profile || empty($profile->preferred_city_id)) { $preferencesMissing[] = 'Preferred City'; }
    if (!$profile || $profile->experience_years === null) { $preferencesMissing[] = 'Experience (Years)'; }
    if (!$profile || empty($profile->current_salary)) { $preferencesMissing[] = 'Current Salary'; }
    if (!$profile || empty($profile->expected_salary)) { $preferencesMissing[] = 'Expected Salary'; }
    if (!$profile || empty($profile->english_fluency)) { $preferencesMissing[] = 'English Fluency'; }
    if (!$profile || empty($profile->residential_preference)) { $preferencesMissing[] = 'School Type Preference'; }
    if (!$profile || empty($profile->availability_to_join)) { $preferencesMissing[] = 'Availability to Join'; }

    $documentMissing = [];
    if (!$hasPhoto) { $documentMissing[] = 'Profile Photo'; }
    if (!$hasResume) { $documentMissing[] = 'Resume / CV'; }

    $missingRequiredFields = array_merge($personalMissing, $academicMissing, $preferencesMissing);
    $allMissingFields = array_merge($missingRequiredFields, $documentMissing);

    $profileChecklistGroups = [
        [
            'label' => 'Personal Info & Address',
            'done' => empty($personalMissing),
            'missing' => $personalMissing,
            'url' => route('candidate.profile.edit'),
        ],
        [
            'label' => 'Category, Subject & Qualification',
            'done' => empty($academicMissing),
            'missing' => $academicMissing,
            'url' => route('candidate.profile.edit'),
        ],
        [
            'label' => 'Location & Career Preferences',
            'done' => empty($preferencesMissing),
            'missing' => $preferencesMissing,
            'url' => route('candidate.profile.edit'),
        ],
        [
            'label' => 'Profile Photo & Resume / CV',
            'done' => empty($documentMissing),
            'missing' => $documentMissing,
            'url' => route('candidate.profile.edit') . '#resume-section',
        ],
    ];

    // Relevant next step prompt based on candidate state
    $nextStepBadge = 'Ready';
    $nextStepTitle = 'Your Profile is Ready to Apply';
    $nextStepDescription = 'Browse verified school vacancies and apply directly.';
    $nextStepCtaLabel = 'Browse Jobs';
    $nextStepUrl = route('jobs');
    $nextStepTone = 'emerald';

    if (!$isProfileDetailsDone && $isAgreementSigned && $hasPaidPlan) {
        $nextStepBadge = 'Action Required';
        $nextStepTitle = 'Complete Profile to Apply';
        $nextStepDescription = 'Your agreement & plan are active, but required profile details are incomplete. Complete Profile to Apply for jobs.';
        $nextStepCtaLabel = 'Complete Profile to Apply';
        $nextStepUrl = route('candidate.wizard', ['step' => 4]);
        $nextStepTone = 'amber';
    } elseif (!$isProfileDetailsDone) {
        $nextStepBadge = !$isAgreementSigned ? 'Next Step: Agreement or Profile' : 'Next Step: Plan or Profile';
        $nextStepTitle = 'Complete Profile to Apply';
        if (!$isAgreementSigned) {
            $nextStepDescription = 'Sign your placement agreement next (Step 1), or fill your missing profile fields now. Agreement, plan, and 100% profile completion are required to apply.';
            $nextStepCtaLabel = 'Sign Agreement (Step 1)';
            $nextStepUrl = route('candidate.wizard', ['step' => ($profile && $profile->is_terms_agreed) ? 2 : 1]);
        } else {
            $nextStepDescription = 'Agreement signed! Activate your plan next (Step 2) and finish missing profile details to apply for jobs.';
            $nextStepCtaLabel = 'Activate Plan (Step 2)';
            $nextStepUrl = route('candidate.wizard', ['step' => 3]);
        }
        $nextStepTone = 'amber';
    } elseif (!$isAgreementSigned) {
        $nextStepBadge = 'Next Step: Agreement';
        $nextStepTitle = 'Sign Placement Agreement to Unlock Plan';
        $nextStepDescription = 'Your profile details are saved! Sign the placement agreement next so you can activate a plan and apply.';
        $nextStepCtaLabel = 'Sign Agreement';
        $nextStepUrl = route('candidate.wizard', ['step' => ($profile && $profile->is_terms_agreed) ? 2 : 1]);
        $nextStepTone = 'sky';
    } elseif (!$hasPaidPlan) {
        $nextStepBadge = 'Next Step: Activate Plan';
        $nextStepTitle = 'Activate Your Plan to Apply';
        $nextStepDescription = 'Profile & agreement are complete! Choose a membership plan to unlock school details and apply.';
        $nextStepCtaLabel = 'Payment / Plan';
        $nextStepUrl = route('candidate.wizard', ['step' => 3]);
        $nextStepTone = 'sky';
    } elseif ($isPlanExpired) {
        $nextStepBadge = 'Plan Expired';
        $nextStepTitle = 'Renew Your Plan to Keep Applying';
        $nextStepDescription = 'Your membership plan has expired. Renew now to apply for new vacancies and receive interview calls.';
        $nextStepCtaLabel = 'Renew Now';
        $nextStepUrl = route('candidate.payment.show', ['type' => 'renewal']);
        $nextStepTone = 'rose';
    }

    $showNextStepCard = !$isRegistrationComplete || !$isProfileDetailsDone || !empty($missingRequiredFields) || $isPlanExpired || !$hasResume || !$hasPhoto;

    $remainingApplications = $placedCount > 0 ? 0 : max(0, $totalAllowedApplications - $usedApplications);
    $canApplyNow = $isRegistrationComplete && $isPlanActive && ($usedApplications < $totalAllowedApplications) && ($placedCount === 0);

    $appStatusBadges = [
        'applied' => ['label' => 'Applied', 'class' => 'bg-sky-500/15 text-sky-300 border-sky-500/30'],
        'shortlisted' => ['label' => 'Shortlisted', 'class' => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30'],
        'interviewed' => ['label' => 'Interview Scheduled', 'class' => 'bg-amber-500/15 text-amber-300 border-amber-500/30'],
        'hold' => ['label' => 'On Hold', 'class' => 'bg-amber-500/15 text-amber-300 border-amber-500/30'],
        'hired' => ['label' => 'Placed', 'class' => 'bg-emerald-500/20 text-emerald-300 border-emerald-400/40'],
        'rejected' => ['label' => 'Not Selected', 'class' => 'bg-rose-500/15 text-rose-300 border-rose-500/30'],
    ];
@endphp

<div class="space-y-5 pb-8">

    {{-- Flash Alerts --}}
    @php
        $flashAlertStyles = [
            'success' => ['wrap' => 'bg-emerald-500/15 border-emerald-500/35 text-emerald-200', 'icon' => 'fa-check-circle text-emerald-400', 'close' => 'text-emerald-300/70'],
            'error'   => ['wrap' => 'bg-rose-500/15 border-rose-500/35 text-rose-200', 'icon' => 'fa-exclamation-circle text-rose-400', 'close' => 'text-rose-300/70'],
            'warning' => ['wrap' => 'bg-amber-500/15 border-amber-500/35 text-amber-200', 'icon' => 'fa-exclamation-triangle text-amber-400', 'close' => 'text-amber-300/70'],
            'info'    => ['wrap' => 'bg-sky-500/15 border-sky-500/35 text-sky-200', 'icon' => 'fa-info-circle text-sky-400', 'close' => 'text-sky-300/70'],
        ];
    @endphp
    @foreach($flashAlertStyles as $flashKey => $flashStyle)
        @if(session($flashKey))
            <div class="rounded-2xl border px-4 py-3 flex items-center justify-between gap-3 text-xs sm:text-sm shadow-sm {{ $flashStyle['wrap'] }}">
                <div class="flex items-center gap-2.5 min-w-0">
                    <i class="fas {{ $flashStyle['icon'] }} shrink-0"></i>
                    <span class="font-semibold">{{ session($flashKey) }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="{{ $flashStyle['close'] }} hover:text-white p-1 shrink-0" aria-label="Dismiss">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        @endif
    @endforeach
    @if($errors->has('profile_photo'))
        <div class="rounded-2xl border px-4 py-3 flex items-center justify-between gap-3 text-xs sm:text-sm shadow-sm bg-rose-500/15 border-rose-500/35 text-rose-200">
            <div class="flex items-center gap-2.5 min-w-0">
                <i class="fas fa-exclamation-circle text-rose-400 shrink-0"></i>
                <span class="font-semibold">{{ $errors->first('profile_photo') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-300/70 hover:text-white p-1 shrink-0" aria-label="Dismiss">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    @endif

    {{-- Top Welcome & Profile Strength Banner --}}
    <div id="tour-cand-hero" class="bg-gradient-to-r from-[#031544] via-[#092b7a] to-[#1e0e47] border border-blue-400/25 rounded-3xl p-6 sm:p-7 text-white shadow-[0_10px_35px_rgba(3,27,78,0.45)] relative overflow-hidden">
        {{-- Decorative ambient glow backlights --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-sky-400/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 left-1/3 w-80 h-80 bg-purple-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-12 w-48 h-48 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col xl:flex-row items-center justify-between gap-6">
            {{-- Left: Candidate Info & Meta Badges --}}
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-5 text-center sm:text-left flex-1 min-w-0 w-full">
                {{-- Avatar with Click-to-Preview Lightbox & Change Photo Action --}}
                <div class="flex flex-col items-center gap-2 shrink-0">
                    <div class="relative group">
                        <button type="button"
                                @click="$dispatch('open-photo-modal')"
                                class="relative block rounded-full focus:outline-none focus:ring-2 focus:ring-sky-400 cursor-pointer"
                                title="{{ $hasPhoto ? 'Click to view large photo' : 'Click to view or upload photo' }}">
                            @if($profile->profile_photo_path)
                                <img src="{{ asset('storage/' . $profile->profile_photo_path) }}" alt="{{ auth()->user()->name }}"
                                    class="w-20 h-20 sm:w-22 sm:h-22 rounded-full object-cover ring-4 ring-white/20 shadow-2xl transition-transform duration-300 group-hover:scale-105">
                            @else
                                <div class="w-20 h-20 sm:w-22 sm:h-22 rounded-full bg-gradient-to-br from-accent-blue via-blue-600 to-indigo-800 flex items-center justify-center text-3xl font-black ring-4 ring-white/20 shadow-2xl transition-transform duration-300 group-hover:scale-105">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="absolute inset-0 rounded-full bg-slate-950/45 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-sm pointer-events-none">
                                <i class="fas {{ $hasPhoto ? 'fa-search-plus' : 'fa-camera' }}"></i>
                            </span>
                        </button>

                        @if($profile->is_verified || $profile->is_fee_paid)
                            <span class="absolute bottom-0.5 right-0.5 w-6 h-6 rounded-full bg-emerald-500 ring-2 ring-[#031544] flex items-center justify-center text-[10px] text-white shadow-lg pointer-events-none" title="Verified Candidate">
                                <i class="fas fa-check"></i>
                            </span>
                        @endif
                    </div>

                    <button type="button"
                            @click="$dispatch('trigger-photo-upload')"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 border border-white/15 text-[10px] font-bold text-sky-200 hover:text-white transition-all cursor-pointer whitespace-nowrap">
                        <i class="fas fa-camera text-[9px] text-sky-300"></i>
                        <span>{{ $hasPhoto ? 'Change Photo' : 'Upload Photo' }}</span>
                    </button>
                </div>

                {{-- Text Info --}}
                <div class="space-y-2 min-w-0 flex-1">
                    <div class="text-[11px] font-extrabold uppercase tracking-wider text-sky-300">
                        {{ $timeGreeting }}
                    </div>
                    <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                            Welcome back, {{ auth()->user()->name }}
                        </h1>
                        <span class="text-2xl select-none animate-pulse">👋</span>
                    </div>

                    <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                        @if($profile->vpa_id)
                            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-lg bg-white/10 backdrop-blur-md border border-white/20 text-xs font-mono font-bold text-sky-200 tracking-wider shadow-inner">
                                <i class="fas fa-id-badge text-sky-400 text-xs"></i>
                                <span>{{ $profile->vpa_id }}</span>
                            </div>
                        @endif
                        <span class="text-xs text-blue-200/80 font-medium">
                            Your profile is active and visible to top schools.
                        </span>
                    </div>

                    {{-- Profile Completion Percentage & Linear Progress Bar --}}
                    <div class="max-w-md mx-auto sm:mx-0 pt-1">
                        <div class="flex items-center justify-between text-[11px] font-bold mb-1">
                            <span class="text-slate-200">Profile Completion</span>
                            <span class="{{ $profileStrength >= 100 ? 'text-emerald-300' : 'text-sky-300' }}">{{ $profileStrength }}%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-950/60 border border-white/10 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700 {{ $profileStrength >= 100 ? 'bg-gradient-to-r from-emerald-400 to-teal-400' : 'bg-gradient-to-r from-sky-400 via-blue-500 to-emerald-400' }}"
                                 style="width: {{ $profileStrength }}%;"></div>
                        </div>
                    </div>

                    {{-- Meta Badges Row --}}
                    <div class="flex items-center justify-center sm:justify-start gap-2 pt-1 flex-wrap text-xs">
                        {{-- Profile Strength --}}
                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-[11px] font-semibold backdrop-blur-sm shadow-sm">
                            <i class="fas fa-bullseye text-xs text-emerald-400"></i>
                            <span>Profile Strength <strong class="text-white ml-0.5">{{ $profileStrength }}%</strong></span>
                        </div>

                        {{-- Profile Views --}}
                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-sky-500/15 border border-sky-500/30 text-sky-200 text-[11px] font-semibold backdrop-blur-sm shadow-sm">
                            <i class="fas fa-eye text-xs text-sky-400"></i>
                            <span>Profile Views <strong class="text-white ml-0.5">{{ $profileViews }}</strong></span>
                        </div>

                        {{-- Member Since --}}
                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-200 text-[11px] font-semibold backdrop-blur-sm shadow-sm">
                            <i class="fas fa-calendar-alt text-xs text-amber-400"></i>
                            <span>Member Since <strong class="text-white ml-0.5">{{ auth()->user()->created_at->format('M Y') }}</strong></span>
                        </div>

                        {{-- Plan --}}
                        @if($profile && $profile->is_plan_active)
                            <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-purple-500/15 border border-purple-500/30 text-purple-200 text-[11px] font-semibold backdrop-blur-sm shadow-sm">
                                <i class="fas fa-crown text-xs text-amber-400"></i>
                                <span>Plan <strong class="text-white capitalize ml-0.5">{{ $profile->plan_type ? ucfirst($profile->plan_type) : 'Standard' }}</strong></span>
                            </div>
                        @elseif($profile && $profile->is_plan_expired)
                            <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-200 text-[11px] font-semibold backdrop-blur-sm shadow-sm">
                                <i class="fas fa-clock text-xs text-rose-400"></i>
                                <span>Plan <strong class="text-rose-300 capitalize ml-0.5">Expired</strong></span>
                            </div>
                        @else
                            <a href="{{ route('candidate.payment.show') }}" class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-500/15 border border-amber-500/30 hover:bg-amber-500/25 text-amber-200 text-[11px] font-semibold backdrop-blur-sm shadow-sm transition-all group">
                                <i class="fas fa-bolt text-xs text-amber-400 group-hover:scale-110 transition-transform"></i>
                                <span>Plan <strong class="text-amber-300 ml-0.5">Activate Now</strong></span>
                            </a>
                        @endif

                        {{-- Help / Platform Tour Trigger --}}
                        <button type="button"
                                @click="$dispatch('start-platform-tour')"
                                class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-sky-200 hover:text-white text-[11px] font-semibold backdrop-blur-sm shadow-sm transition-all cursor-pointer">
                            <i class="fas fa-compass text-xs text-sky-300"></i>
                            <span>Platform Tour</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right: Profile Strength Gauge & Interactive Checklist --}}
            <div class="flex flex-col sm:flex-row items-center gap-5 sm:gap-6 bg-white/[0.08] backdrop-blur-xl border border-white/15 rounded-2xl p-4 sm:p-5 shrink-0 shadow-lg">
                {{-- Neon Circular Gauge --}}
                <div class="relative w-[84px] h-[84px] flex items-center justify-center shrink-0">
                    <svg class="w-[84px] h-[84px] transform -rotate-90" viewBox="0 0 36 36">
                        <defs>
                            <linearGradient id="neonGauge" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#34d399" />
                                <stop offset="100%" stop-color="#06b6d4" />
                            </linearGradient>
                        </defs>
                        {{-- Background Ring --}}
                        <path class="text-white/10" stroke-width="3.5" stroke="currentColor" fill="none"
                              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        {{-- Foreground Neon Progress Ring --}}
                        <path stroke="url(#neonGauge)" stroke-dasharray="{{ $profileStrength }}, 100" stroke-width="3.5" stroke-linecap="round" fill="none"
                              class="transition-all duration-1000 ease-out drop-shadow-[0_0_8px_rgba(52,211,153,0.5)]"
                              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="absolute flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-xl font-black text-white tracking-tight">{{ $profileStrength }}%</span>
                        <span class="text-[8px] uppercase tracking-widest text-emerald-300 font-extrabold">Strength</span>
                    </div>
                </div>

                {{-- Checklist Items --}}
                <div class="space-y-1.5 text-xs text-left">
                    <div class="flex items-center gap-2 {{ empty($personalMissing) ? 'text-emerald-300 font-semibold' : 'text-amber-200/80' }}">
                        <i class="fas {{ empty($personalMissing) ? 'fa-check-circle text-emerald-400' : 'fa-exclamation-circle text-amber-400 text-[10px]' }}"></i>
                        <span>Personal details &amp; address</span>
                    </div>
                    <div class="flex items-center gap-2 {{ empty($academicMissing) ? 'text-emerald-300 font-semibold' : 'text-amber-200/80' }}">
                        <i class="fas {{ empty($academicMissing) ? 'fa-check-circle text-emerald-400' : 'fa-exclamation-circle text-amber-400 text-[10px]' }}"></i>
                        <span>Category, subject &amp; qualification</span>
                    </div>
                    <div class="flex items-center gap-2 {{ $hasResume ? 'text-emerald-300 font-semibold' : ($isProfileDetailsDone ? 'text-sky-200/80' : 'text-amber-200/80') }}">
                        <i class="fas {{ $hasResume ? 'fa-check-circle text-emerald-400' : ($isProfileDetailsDone ? 'fa-file-upload text-sky-400 text-[10px]' : 'fa-circle text-[7px] text-white/30') }}"></i>
                        <span>{{ $hasResume ? 'Resume on record' : 'Upload Resume / CV' }}</span>
                    </div>
                    <div class="flex items-center gap-2 {{ ($isVerified || $isRegistrationComplete) ? 'text-emerald-300 font-semibold' : 'text-white/55' }}">
                        <i class="fas {{ ($isVerified || $isRegistrationComplete) ? 'fa-check-circle text-emerald-400' : 'fa-circle text-[7px] text-white/30' }}"></i>
                        <span>{{ $isVerified ? 'Verified candidate profile' : 'Ready to apply & be shortlisted' }}</span>
                    </div>

                    <div class="pt-1.5">
                        <a href="{{ route('candidate.profile.edit') }}" 
                           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 text-xs font-bold text-white transition-all shadow hover:shadow-md hover:scale-[1.02] active:scale-[0.98]">
                            <span>{{ $isProfileDetailsDone ? 'Update Profile' : 'Complete Profile' }}</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Relevant Next Step & Missing Profile Fields Checklist Banner --}}
    @if($showNextStepCard)
        @php
            $bannerBorder = $nextStepTone === 'rose'
                ? 'border-rose-500/40'
                : ($nextStepTone === 'amber' ? 'border-amber-500/40' : ($nextStepTone === 'sky' ? 'border-sky-500/35' : 'border-emerald-500/30'));
        @endphp
        <div id="tour-cand-nextstep" class="bg-gradient-to-br from-[#0a1e4a]/95 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border {{ $bannerBorder }} p-4 sm:p-5 shadow-[0_6px_25px_rgba(0,0,0,0.28)] space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="space-y-1.5 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $nextStepTone === 'rose' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/35' : ($nextStepTone === 'amber' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/35' : ($nextStepTone === 'sky' ? 'bg-sky-500/20 text-sky-300 border border-sky-500/35' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/35')) }}">
                            {{ $nextStepBadge }}
                        </span>
                        @if(!empty($missingRequiredFields))
                            <span class="text-[11px] font-bold text-amber-300">
                                {{ count($missingRequiredFields) }} required {{ count($missingRequiredFields) === 1 ? 'field' : 'fields' }} missing
                            </span>
                        @elseif(!$isProfileDetailsDone)
                            <span class="text-[11px] font-bold text-amber-300">
                                Save profile details to unlock applications
                            </span>
                        @elseif(!empty($documentMissing))
                            <span class="text-[11px] font-semibold text-sky-300">
                                Optional boost: {{ implode(', ', $documentMissing) }} not uploaded
                            </span>
                        @endif
                    </div>

                    <h2 class="text-base sm:text-lg font-black text-white tracking-tight">
                        {{ $nextStepTitle }}
                    </h2>
                    <p class="text-xs text-slate-300 leading-relaxed max-w-3xl">
                        {{ $nextStepDescription }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    @if(!$isProfileDetailsDone || !$isRegistrationComplete || $isPlanExpired)
                        <a href="{{ $nextStepUrl }}"
                           class="px-4 py-2.5 rounded-xl bg-gradient-to-r {{ $nextStepTone === 'rose' ? 'from-rose-500 to-amber-500 text-white' : ($nextStepTone === 'amber' ? 'from-amber-400 to-amber-500 text-slate-950' : 'from-sky-400 to-blue-600 text-white') }} font-black text-xs shadow-md hover:brightness-105 hover:-translate-y-0.5 transition-all inline-flex items-center gap-1.5 whitespace-nowrap">
                            <span>{{ $nextStepCtaLabel }}</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    @endif

                    @if(!$isProfileDetailsDone || !empty($allMissingFields))
                        <a href="{{ route('candidate.profile.edit') }}"
                           class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-white font-bold text-xs inline-flex items-center gap-1.5 transition-colors whitespace-nowrap">
                            <i class="fas fa-user-edit text-[11px] text-sky-300"></i>
                            <span>{{ $isProfileDetailsDone ? 'Update Profile / Docs' : 'Fill Profile Fields' }}</span>
                        </a>
                    @endif

                    @if(!$isProfileDetailsDone && !$isRegistrationComplete)
                        <button type="button"
                                @click="$dispatch('open-incomplete-modal')"
                                class="px-3 py-2.5 rounded-xl bg-white/[0.04] hover:bg-white/10 border border-white/10 text-slate-300 hover:text-white font-semibold text-xs transition-colors">
                            Steps Guide
                        </button>
                    @endif
                </div>
            </div>

            {{-- Missing Profile Fields Checklist --}}
            @if(!$isProfileDetailsDone || !empty($allMissingFields))
                <div class="pt-3 border-t border-white/[0.08] space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-2.5">
                        @foreach($profileChecklistGroups as $group)
                            <a href="{{ $group['url'] }}"
                               class="rounded-xl p-3 border transition-all flex items-start gap-2.5 {{ $group['done'] ? 'bg-emerald-500/[0.07] border-emerald-500/25 hover:border-emerald-400/40' : 'bg-white/[0.03] border-amber-500/25 hover:border-amber-400/50 hover:bg-white/[0.06]' }}">
                                <span class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 mt-0.5 text-[10px] {{ $group['done'] ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                                    <i class="fas {{ $group['done'] ? 'fa-check' : 'fa-exclamation' }}"></i>
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold text-white leading-tight">{{ $group['label'] }}</div>
                                    @if($group['done'])
                                        <div class="text-[11px] text-emerald-300 font-medium mt-0.5">Completed</div>
                                    @else
                                        <div class="text-[11px] text-amber-200/90 mt-0.5 leading-snug">
                                            Missing: {{ implode(', ', array_slice($group['missing'], 0, 3)) }}{{ count($group['missing']) > 3 ? ' +' . (count($group['missing']) - 3) . ' more' : '' }}
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>

                    @if(!empty($missingRequiredFields))
                        <div class="flex flex-wrap items-center gap-1.5 text-[11px]">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mr-1">Missing Fields:</span>
                            @foreach($missingRequiredFields as $fieldLabel)
                                <span class="px-2 py-0.5 rounded-md bg-amber-500/10 border border-amber-500/25 text-amber-200 font-semibold">
                                    {{ $fieldLabel }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- Candidate Application & Verification Status Summary Cards --}}
    <div id="tour-cand-summary" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Card A: Application Status Summary --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/95 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 sm:p-5 shadow-[0_4px_20px_rgba(0,0,0,0.25)] flex flex-col justify-between gap-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-sky-500/15 border border-sky-500/25 text-sky-300 flex items-center justify-center text-base shrink-0">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Application Status</span>
                        <h3 class="text-base font-black text-white tracking-tight truncate">
                            @if($canApplyNow)
                                Ready to Apply
                            @elseif($placedCount > 0)
                                Placed Successfully
                            @elseif(!$isProfileDetailsDone)
                                Complete Profile to Apply
                            @elseif(!$isAgreementSigned)
                                Agreement Pending
                            @elseif(!$hasPaidPlan || $isPlanExpired)
                                Plan Activation Required
                            @else
                                Application Quota Used
                            @endif
                        </h3>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider shrink-0 {{ $canApplyNow ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30' : ($placedCount > 0 ? 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/40' : 'bg-amber-500/15 text-amber-300 border border-amber-500/30') }}">
                    {{ $canApplyNow ? $remainingApplications . ' Left' : ($placedCount > 0 ? 'Placed' : 'Action Needed') }}
                </span>
            </div>

            <div class="grid grid-cols-4 gap-2 text-center">
                <div class="rounded-xl bg-white/[0.04] border border-white/[0.06] py-2 px-1">
                    <div class="text-base sm:text-lg font-black text-white">{{ $applicationsCount }}</div>
                    <div class="text-[10px] text-slate-400 font-semibold">Applied</div>
                </div>
                <div class="rounded-xl bg-white/[0.04] border border-white/[0.06] py-2 px-1">
                    <div class="text-base sm:text-lg font-black text-emerald-300">{{ $shortlistedCount }}</div>
                    <div class="text-[10px] text-slate-400 font-semibold">Shortlisted</div>
                </div>
                <div class="rounded-xl bg-white/[0.04] border border-white/[0.06] py-2 px-1">
                    <div class="text-base sm:text-lg font-black text-amber-300">{{ $interviewsCount }}</div>
                    <div class="text-[10px] text-slate-400 font-semibold">Interviews</div>
                </div>
                <div class="rounded-xl bg-white/[0.04] border border-white/[0.06] py-2 px-1">
                    <div class="text-base sm:text-lg font-black text-sky-300">{{ $placedCount }}</div>
                    <div class="text-[10px] text-slate-400 font-semibold">Placed</div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-white/[0.08] text-xs">
                @if(!empty($latestApplication))
                    @php
                        $latestAppMeta = $appStatusBadges[$latestApplication->status ?? 'applied'] ?? $appStatusBadges['applied'];
                    @endphp
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="text-slate-400 shrink-0">Latest:</span>
                        <span class="font-semibold text-white truncate max-w-[170px]">{{ $latestApplication->jobPost?->title ?? 'Job Application' }}</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border shrink-0 {{ $latestAppMeta['class'] }}">{{ $latestAppMeta['label'] }}</span>
                    </div>
                @else
                    <span class="text-slate-400">
                        @if($hasPaidPlan)
                            Quota: <strong class="text-white">{{ $usedApplications }}/{{ $totalAllowedApplications }}</strong> applications used
                        @else
                            No applications submitted yet
                        @endif
                    </span>
                @endif

                <div class="flex items-center gap-3 shrink-0 ml-auto">
                    @if(!$isProfileDetailsDone)
                        <a href="{{ route('candidate.profile.edit') }}" class="font-bold text-amber-300 hover:text-amber-200 inline-flex items-center gap-1 transition-colors">
                            <span>Complete Profile to Apply</span>
                            <i class="fas fa-arrow-right text-[9px]"></i>
                        </a>
                    @else
                        <a href="{{ route('candidate.applications.index') }}" class="font-bold text-accent-blue hover:text-white inline-flex items-center gap-1 transition-colors">
                            <span>Applications</span>
                            <i class="fas fa-arrow-right text-[9px]"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Card B: Verification Status Summary --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/95 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border {{ $isVerified ? 'border-emerald-500/35' : ($verificationRequested ? 'border-sky-500/35' : 'border-white/[0.08]') }} p-4 sm:p-5 shadow-[0_4px_20px_rgba(0,0,0,0.25)] flex flex-col justify-between gap-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl {{ $isVerified ? 'bg-emerald-500/15 border-emerald-500/30 text-emerald-300' : ($verificationRequested ? 'bg-sky-500/15 border-sky-500/30 text-sky-300' : 'bg-amber-500/15 border-amber-500/30 text-amber-300') }} border flex items-center justify-center text-base shrink-0">
                        <i class="fas {{ $isVerified ? 'fa-shield-alt' : ($verificationRequested ? 'fa-hourglass-half' : 'fa-user-check') }}"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Verification Status</span>
                        <h3 class="text-base font-black text-white tracking-tight truncate">
                            @if($isVerified)
                                Verified Candidate
                            @elseif($verificationRequested)
                                Verification Under Review
                            @elseif(!$isProfileDetailsDone)
                                Profile Incomplete
                            @else
                                Ready for Verification
                            @endif
                        </h3>
                    </div>
                </div>
                @if($isVerified)
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 shrink-0">
                        Verified
                    </span>
                @elseif($verificationRequested)
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-sky-500/15 text-sky-300 border border-sky-500/30 shrink-0">
                        Requested
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-500/15 text-amber-300 border border-amber-500/30 shrink-0">
                        Unverified
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px]">
                <div class="rounded-xl bg-white/[0.04] border border-white/[0.06] px-2.5 py-2 flex items-center gap-1.5">
                    <i class="fas {{ $isProfileDetailsDone ? 'fa-check-circle text-emerald-400' : 'fa-clock text-amber-400' }} text-[10px]"></i>
                    <span class="font-semibold text-slate-200 truncate">Profile</span>
                </div>
                <div class="rounded-xl bg-white/[0.04] border border-white/[0.06] px-2.5 py-2 flex items-center gap-1.5">
                    <i class="fas {{ $isAgreementSigned ? 'fa-check-circle text-emerald-400' : 'fa-clock text-amber-400' }} text-[10px]"></i>
                    <span class="font-semibold text-slate-200 truncate">Agreement</span>
                </div>
                <div class="rounded-xl bg-white/[0.04] border border-white/[0.06] px-2.5 py-2 flex items-center gap-1.5">
                    <i class="fas {{ $hasPaidPlan ? 'fa-check-circle text-emerald-400' : 'fa-clock text-amber-400' }} text-[10px]"></i>
                    <span class="font-semibold text-slate-200 truncate">Plan / Fee</span>
                </div>
                <div class="rounded-xl bg-white/[0.04] border border-white/[0.06] px-2.5 py-2 flex items-center gap-1.5">
                    <i class="fas {{ $isVerified ? 'fa-check-circle text-emerald-400' : ($verificationRequested ? 'fa-hourglass-half text-sky-400' : 'fa-minus-circle text-slate-500') }} text-[10px]"></i>
                    <span class="font-semibold text-slate-200 truncate">Badge</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-white/[0.08] text-xs">
                @if($isVerified)
                    <span class="text-emerald-300 font-medium">Your profile carries the Verified Badge for schools.</span>
                    <a href="{{ route('candidate.profile.edit') }}" class="font-bold text-accent-blue hover:text-white inline-flex items-center gap-1 shrink-0">
                        <span>View Profile</span>
                        <i class="fas fa-arrow-right text-[9px]"></i>
                    </a>
                @elseif($verificationRequested)
                    <span class="text-sky-200 font-medium">
                        Submitted {{ $verificationRequestedAt ? $verificationRequestedAt->diffForHumans() : 'recently' }} — team review pending.
                    </span>
                    <a href="{{ route('candidate.profile.edit') }}" class="font-bold text-accent-blue hover:text-white inline-flex items-center gap-1 shrink-0">
                        <span>Update Profile</span>
                        <i class="fas fa-arrow-right text-[9px]"></i>
                    </a>
                @elseif(!$isProfileDetailsDone)
                    <span class="text-slate-300">Complete your profile details first to request verification.</span>
                    <a href="{{ route('candidate.profile.edit') }}" class="font-bold text-amber-300 hover:text-amber-200 inline-flex items-center gap-1 shrink-0">
                        <span>Complete Profile</span>
                        <i class="fas fa-arrow-right text-[9px]"></i>
                    </a>
                @else
                    <span class="text-slate-300">Profile complete — boost visibility with verification.</span>
                    <form action="{{ route('candidate.verification.request') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-3.5 py-1.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-black text-xs transition-all shadow cursor-pointer inline-flex items-center gap-1.5">
                            <i class="fas fa-paper-plane text-[10px]"></i>
                            <span>Request Verification</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Refer & Earn Top Promo Banner --}}
    <div class="bg-gradient-to-r from-amber-500/15 via-[#0a1e4a]/95 to-[#1a103c]/90 backdrop-blur-xl border border-amber-500/30 rounded-3xl p-4 sm:p-5 shadow-[0_6px_25px_rgba(245,158,11,0.12)] flex flex-col sm:flex-row items-center justify-between gap-4 relative overflow-hidden group hover:border-amber-400/50 transition-all duration-300">
        <div class="absolute -right-12 -top-12 w-40 h-40 bg-amber-400/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute left-1/3 -bottom-10 w-32 h-32 bg-sky-400/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row items-center sm:items-center gap-3.5 sm:gap-4 text-center sm:text-left z-10">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-500 text-slate-950 flex items-center justify-center text-xl shadow-[0_4px_15px_rgba(245,158,11,0.35)] shrink-0 group-hover:scale-105 transition-transform">
                <i class="fas fa-gift"></i>
            </div>
            <div>
                <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                    <h3 class="text-sm sm:text-base font-black text-white flex items-center gap-1.5 tracking-tight">
                        <span class="text-amber-400">Refer & Earn Points</span>
                    </h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400/15 text-amber-300 border border-amber-400/30 shadow-sm">
                        🎁 Bonus Points on Every Friend
                    </span>
                </div>
                <p class="text-xs text-slate-300/90 mt-0.5 font-medium">
                    Invite fellow educators & earn discount points on your service charge!
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 shrink-0 z-10 w-full sm:w-auto justify-center">
            @if(auth()->user()->referral_code)
                <div class="hidden md:flex items-center gap-1.5 px-3 py-2 rounded-xl bg-white/5 border border-white/10 text-xs text-white">
                    <span class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Your Code:</span>
                    <strong class="text-amber-400 font-mono font-bold tracking-wider">{{ auth()->user()->referral_code }}</strong>
                </div>
            @endif
            <a href="{{ route('candidate.referral.index') }}" 
               style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: #020617;"
               class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs shadow-md shadow-amber-500/30 hover:shadow-lg hover:shadow-amber-500/50 hover:brightness-105 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2 shrink-0 whitespace-nowrap">
                <i class="fas fa-share-alt text-xs"></i>
                <span class="font-black tracking-wide">Refer Friends</span>
            </a>
        </div>
    </div>

    {{-- Metrics / Stat Cards (Row of 4 Cards) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        {{-- Card 1: Shortlisted --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-emerald-500/40 hover:-translate-y-0.5 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 flex items-center justify-center text-lg shadow-sm group-hover:scale-110 group-hover:bg-emerald-500/25 transition-all">
                    <i class="fas fa-bookmark"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Shortlisted</span>
            </div>
            <div>
                <div class="text-2xl lg:text-3xl font-black text-white tracking-tight">{{ $shortlistedCount }}</div>
                <div class="text-[11px] text-slate-400 font-medium mt-0.5">Times shortlisted</div>
            </div>
        </div>

        {{-- Card 2: Interviews --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-amber-500/40 hover:-translate-y-0.5 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-2xl bg-amber-500/15 border border-amber-500/25 text-amber-400 flex items-center justify-center text-lg shadow-sm group-hover:scale-110 group-hover:bg-amber-500/25 transition-all">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Interviews</span>
            </div>
            <div>
                <div class="text-2xl lg:text-3xl font-black text-white tracking-tight">{{ $interviewsCount }}</div>
                <div class="text-[11px] text-slate-400 font-medium mt-0.5">Scheduled</div>
            </div>
        </div>

        {{-- Card 3: Profile Views --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-sky-500/40 hover:-translate-y-0.5 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-2xl bg-sky-500/15 border border-sky-500/25 text-sky-400 flex items-center justify-center text-lg shadow-sm group-hover:scale-110 group-hover:bg-sky-500/25 transition-all">
                    <i class="fas fa-eye"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Profile Views</span>
            </div>
            <div>
                <div class="text-2xl lg:text-3xl font-black text-white tracking-tight">{{ $profileViews }}</div>
                <div class="text-[11px] text-slate-400 font-medium mt-0.5">Total views</div>
            </div>
        </div>

        {{-- Card 4: Wallet Balance --}}
        <a href="{{ route('candidate.referral.index') }}"
           class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-pink-500/40 hover:-translate-y-0.5 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-2xl bg-pink-500/15 border border-pink-500/25 text-pink-400 flex items-center justify-center text-lg shadow-sm group-hover:scale-110 group-hover:bg-pink-500/25 transition-all">
                    <i class="fas fa-wallet"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Wallet</span>
            </div>
            <div>
                <div class="text-2xl lg:text-3xl font-black text-white flex items-center justify-between tracking-tight">
                    <span>₹{{ number_format($walletBalanceInr, 0) }}</span>
                    <i class="fas fa-chevron-right text-xs text-slate-500 group-hover:text-pink-400 group-hover:translate-x-1 transition-all"></i>
                </div>
                <div class="text-[11px] text-slate-400 font-medium mt-0.5">
                    = {{ number_format($availablePoints, 0) }} Points
                </div>
            </div>
        </a>
    </div>

    {{-- Main Two-Column Grid: Left (Col 8) + Right (Col 4) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

        {{-- ==================== LEFT COLUMN ==================== --}}
        <div class="lg:col-span-8 space-y-5">

            {{-- 1. Quick Actions (Core Candidate Shortcuts) --}}
            <div id="tour-cand-quick-actions">
                <h3 class="text-base font-bold text-white mb-3 flex items-center gap-2">
                    <i class="fas fa-bolt text-accent-yellow text-sm"></i>
                    <span>Quick Actions</span>
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
                    {{-- 1. Complete / Update Profile --}}
                    <a href="{{ route('candidate.profile.edit') }}"
                       class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border {{ !$isProfileDetailsDone ? 'border-amber-500/35' : 'border-white/[0.08]' }} p-4 hover:border-sky-400/50 hover:-translate-y-1 hover:shadow-[0_8px_25px_rgba(18,154,239,0.2)] transition-all duration-300 flex flex-col justify-between group">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/15 border border-sky-500/25 text-sky-400 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold {{ !empty($missingRequiredFields) ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30' }}">
                                {{ !empty($missingRequiredFields) ? count($missingRequiredFields) . ' Left' : 'Done' }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">{{ $isProfileDetailsDone ? 'Update Profile' : 'Complete Profile' }}</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">Personal &amp; teaching info</p>
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-sky-400 mt-2.5 group-hover:translate-x-1 transition-transform">
                                <span>{{ $isProfileDetailsDone ? 'Edit Details' : 'Fill Now' }}</span>
                                <i class="fas fa-arrow-right text-[9px]"></i>
                            </span>
                        </div>
                    </a>

                    {{-- 2. View / Update Resume --}}
                    <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 hover:border-purple-500/40 hover:-translate-y-1 hover:shadow-[0_8px_25px_rgba(168,85,247,0.2)] transition-all duration-300 flex flex-col justify-between group">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/15 border border-purple-500/25 text-purple-400 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold {{ $hasResume ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                                {{ $hasResume ? 'On File' : 'Missing' }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">View / Update Resume</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">
                                {{ $hasResume ? 'Open or replace CV file' : 'Upload CV or build ATS resume' }}
                            </p>
                            <div class="mt-2.5 flex flex-wrap items-center gap-2 text-[11px] font-bold">
                                @if($hasResume)
                                    <a href="{{ route('candidate.resume.view') }}" target="_blank" class="text-emerald-400 hover:text-emerald-300 inline-flex items-center gap-1">
                                        <span>View</span>
                                        <i class="fas fa-external-link-alt text-[9px]"></i>
                                    </a>
                                    <span class="text-slate-600">&bull;</span>
                                    <a href="{{ route('candidate.profile.edit') }}#resume-section" class="text-purple-400 hover:text-purple-300">
                                        Update
                                    </a>
                                @else
                                    <a href="{{ route('candidate.profile.edit') }}#resume-section" class="text-purple-400 hover:text-purple-300">
                                        Upload
                                    </a>
                                    <span class="text-slate-600">&bull;</span>
                                    <a href="{{ route('resume.builder') }}" class="text-sky-400 hover:text-sky-300">
                                        Builder
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- 3. Browse Jobs --}}
                    <a href="{{ route('jobs') }}"
                       class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 hover:border-accent-blue/40 hover:-translate-y-1 hover:shadow-[0_8px_25px_rgba(18,154,239,0.2)] transition-all duration-300 flex flex-col justify-between group">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-accent-blue/15 border border-accent-blue/25 text-accent-blue flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Browse Jobs</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">Explore school vacancies</p>
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-accent-blue mt-2.5 group-hover:translate-x-1 transition-transform">
                                <span>Search Jobs</span>
                                <i class="fas fa-arrow-right text-[9px]"></i>
                            </span>
                        </div>
                    </a>

                    {{-- 4. Applications --}}
                    <a href="{{ route('candidate.applications.index') }}"
                       class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 hover:border-emerald-500/40 hover:-translate-y-1 hover:shadow-[0_8px_25px_rgba(52,211,153,0.2)] transition-all duration-300 flex flex-col justify-between group">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                                {{ $applicationsCount }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Applications</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">Track applied &amp; interviews</p>
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-400 mt-2.5 group-hover:translate-x-1 transition-transform">
                                <span>View Tracker</span>
                                <i class="fas fa-arrow-right text-[9px]"></i>
                            </span>
                        </div>
                    </a>

                    {{-- 5. Agreement --}}
                    <a href="{{ route('candidate.agreement.show') }}"
                       class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 hover:border-indigo-400/40 hover:-translate-y-1 hover:shadow-[0_8px_25px_rgba(99,102,241,0.2)] transition-all duration-300 flex flex-col justify-between group">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/15 border border-indigo-500/25 text-indigo-300 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold {{ $isAgreementSigned ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                                {{ $isAgreementSigned ? 'Signed' : 'Pending' }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Agreement</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">{{ $isAgreementSigned ? 'View or download PDF' : 'Accept & digitally sign' }}</p>
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-indigo-300 mt-2.5 group-hover:translate-x-1 transition-transform">
                                <span>{{ $isAgreementSigned ? 'View Agreement' : 'Sign Now' }}</span>
                                <i class="fas fa-arrow-right text-[9px]"></i>
                            </span>
                        </div>
                    </a>

                    {{-- 6. Payment / Plan --}}
                    <a href="{{ route('candidate.payment.show') }}"
                       class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 hover:border-amber-500/40 hover:-translate-y-1 hover:shadow-[0_8px_25px_rgba(245,158,11,0.2)] transition-all duration-300 flex flex-col justify-between group">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/15 border border-amber-500/25 text-amber-400 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold {{ $isPlanActive ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30' : ($isPlanExpired ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30') }}">
                                {{ $isPlanActive ? 'Active' : ($isPlanExpired ? 'Expired' : 'Inactive') }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Payment / Plan</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">Invoices, upgrade &amp; renew</p>
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-amber-400 mt-2.5 group-hover:translate-x-1 transition-transform">
                                <span>{{ $isPlanExpired ? 'Renew Plan' : 'Manage Plan' }}</span>
                                <i class="fas fa-arrow-right text-[9px]"></i>
                            </span>
                        </div>
                    </a>

                    {{-- 7. Request Verification --}}
                    @if($isVerified)
                        <a href="{{ route('candidate.profile.edit') }}"
                           class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-emerald-500/30 p-4 hover:border-emerald-400/50 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 flex items-center justify-center text-base">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                                    Verified
                                </span>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-white">Request Verification</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">Profile is already verified</p>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-400 mt-2.5">
                                    <span>Verified Badge</span>
                                    <i class="fas fa-check-circle text-[10px]"></i>
                                </span>
                            </div>
                        </a>
                    @elseif($verificationRequested)
                        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-sky-500/30 p-4 flex flex-col justify-between">
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <div class="w-10 h-10 rounded-xl bg-sky-500/15 border border-sky-500/25 text-sky-400 flex items-center justify-center text-base">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-bold bg-sky-500/15 text-sky-300 border border-sky-500/30">
                                    Requested
                                </span>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-white">Request Verification</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">Team review in progress</p>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-sky-300 mt-2.5">
                                    <span>Under Review</span>
                                </span>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('candidate.verification.request') }}" method="POST" class="flex">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 hover:border-cyan-400/40 hover:-translate-y-1 hover:shadow-[0_8px_25px_rgba(34,211,238,0.18)] transition-all duration-300 flex flex-col justify-between group cursor-pointer">
                                <div class="flex items-start justify-between gap-2 mb-3 w-full">
                                    <div class="w-10 h-10 rounded-xl bg-cyan-500/15 border border-cyan-500/25 text-cyan-300 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-md text-[9px] font-bold {{ $isProfileDetailsDone ? 'bg-cyan-500/15 text-cyan-300 border border-cyan-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                                        {{ $isProfileDetailsDone ? 'Ready' : 'Profile 1st' }}
                                    </span>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-white">Request Verification</h4>
                                    <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">Get verified badge for schools</p>
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-cyan-300 mt-2.5 group-hover:translate-x-1 transition-transform">
                                        <span>{{ $isProfileDetailsDone ? 'Submit Request' : 'Complete Profile First' }}</span>
                                        <i class="fas fa-arrow-right text-[9px]"></i>
                                    </span>
                                </div>
                            </button>
                        </form>
                    @endif

                    {{-- 8. Saved Jobs --}}
                    <a href="{{ route('candidate.savedJobs.index') }}"
                       class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 hover:border-pink-500/40 hover:-translate-y-1 hover:shadow-[0_8px_25px_rgba(236,72,153,0.18)] transition-all duration-300 flex flex-col justify-between group">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-pink-500/15 border border-pink-500/25 text-pink-400 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                <i class="fas fa-bookmark"></i>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold bg-pink-500/15 text-pink-300 border border-pink-500/30">
                                {{ $savedJobsCount ?? 0 }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Saved Jobs</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">Your bookmarked vacancies</p>
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-pink-400 mt-2.5 group-hover:translate-x-1 transition-transform">
                                <span>View Saved</span>
                                <i class="fas fa-arrow-right text-[9px]"></i>
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- 2. Notifications & Updates List --}}
            <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 shadow-[0_4px_20px_rgba(0,0,0,0.25)]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="fas fa-bell text-accent-blue text-sm"></i>
                        <span>Notifications</span>
                    </h3>
                    <a href="{{ route('candidate.aditionalFeature.show') }}" class="text-xs font-semibold text-accent-blue hover:text-white transition-colors">
                        View All
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($notifications as $notif)
                        <div class="p-3.5 rounded-xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] hover:border-accent-blue/30 transition-all flex items-center justify-between gap-3 group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-accent-blue/15 text-accent-blue flex items-center justify-center text-sm shrink-0 group-hover:scale-105 transition-transform">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-white truncate">{{ $notif->data['title'] ?? 'System Update' }}</h4>
                                    <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $notif->data['message'] ?? 'You have a new update.' }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-500 font-medium shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        {{-- Clean Realistic System Notification Updates --}}
                        <div class="p-3.5 rounded-xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] hover:border-blue-500/30 transition-all flex items-center justify-between gap-3 group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-blue-500/15 border border-blue-500/25 text-blue-400 flex items-center justify-center text-sm shrink-0 group-hover:scale-105 transition-transform">
                                    <i class="fas fa-file-signature"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-white">Application Update</h4>
                                    <p class="text-[11px] text-slate-400 truncate mt-0.5">Your profile application status has been updated by Vedanta Placement Agency.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-[10px] text-slate-500 font-medium">1 week ago</span>
                                <i class="fas fa-chevron-right text-[10px] text-slate-600 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] hover:border-emerald-500/30 transition-all flex items-center justify-between gap-3 group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 flex items-center justify-center text-sm shrink-0 group-hover:scale-105 transition-transform">
                                    <i class="fas fa-bookmark"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-white">Application Update</h4>
                                    <p class="text-[11px] text-slate-400 truncate mt-0.5">Your application has been successfully shortlisted for reviewed schools.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-[10px] text-slate-500 font-medium">1 week ago</span>
                                <i class="fas fa-chevron-right text-[10px] text-slate-600 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] hover:border-amber-500/30 transition-all flex items-center justify-between gap-3 group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-amber-500/15 border border-amber-500/25 text-amber-400 flex items-center justify-center text-sm shrink-0 group-hover:scale-105 transition-transform">
                                    <i class="fas fa-paper-plane"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-white">New Job Alert</h4>
                                    <p class="text-[11px] text-slate-400 truncate mt-0.5">New high-priority teaching opportunities have been shared matching your profile.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-[10px] text-slate-500 font-medium">2 weeks ago</span>
                                <i class="fas fa-chevron-right text-[10px] text-slate-600 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 4. Recommended Jobs for You --}}
            <div id="tour-cand-recommended" class="space-y-3.5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-white">Recommended Jobs for You</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Jobs that match your profile and preferences</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('jobs') }}" class="text-xs font-bold text-accent-blue hover:text-white flex items-center gap-1.5 transition-colors">
                            <span>View All Jobs</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    @forelse($recommendedJobs as $job)
                        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 hover:border-accent-blue/40 hover:-translate-y-0.5 hover:shadow-[0_8px_30px_rgba(0,0,0,0.35)] transition-all duration-300 flex flex-col justify-between group">
                            <div>
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-accent-blue/15 border border-accent-blue/30 text-accent-blue font-black flex items-center justify-center text-xs shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                                            {{ strtoupper(substr($job->title ?? 'TR', 0, 2)) }}
                                        </div>
                                        <div>
                                            <h4 class="text-xs sm:text-sm font-bold text-white group-hover:text-accent-blue transition-colors line-clamp-1">
                                                {{ $job->title }}
                                            </h4>
                                            <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">{{ $job->getMaskedSchoolName() }}</p>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            x-data="{
                                                isSaved: {{ in_array($job->id, $savedJobIds ?? []) ? 'true' : 'false' }},
                                                loading: false,
                                                async toggleSave() {
                                                    if (this.loading) return;
                                                    this.loading = true;
                                                    try {
                                                        const res = await fetch('{{ route('candidate.jobs.toggleSave', $job->id) }}', {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                                'Accept': 'application/json'
                                                            }
                                                        });
                                                        const data = await res.json();
                                                        this.isSaved = data.saved;
                                                    } catch (e) {
                                                        console.error('Error saving job:', e);
                                                    } finally {
                                                        this.loading = false;
                                                    }
                                                }
                                            }"
                                            @click.prevent.stop="toggleSave()"
                                            :disabled="loading"
                                            :class="isSaved ? 'text-amber-400 hover:text-amber-300 scale-110' : 'text-slate-500 hover:text-accent-blue'"
                                            class="transition-all p-1.5 rounded-lg hover:bg-white/10 active:scale-90"
                                            :title="isSaved ? 'Remove from Saved' : 'Save Job'">
                                        <i :class="isSaved ? 'fas fa-bookmark text-xs text-amber-400' : 'far fa-bookmark text-xs'"></i>
                                    </button>
                                </div>

                                <div class="space-y-1.5 text-xs text-slate-300 mb-3">
                                    <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
                                        <i class="fas fa-map-marker-alt text-amber-400 text-xs w-3 text-center"></i>
                                        <span>{{ $job->getMaskedLocation() }}</span>
                                    </div>
                                    <div class="text-xs font-bold text-white flex items-center gap-1">
                                        <span>{{ $job->formatted_salary }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-white/5 border border-white/10 text-slate-300">
                                        Full Time
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-white/5 border border-white/10 text-slate-300">
                                        On-site
                                    </span>
                                    <span class="ml-auto text-[10px] text-slate-500 font-medium">
                                        {{ $job->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            <div class="pt-3 mt-3 border-t border-white/[0.08]">
                                <a href="{{ route('jobs.show', $job->id) }}" 
                                   class="w-full py-2 px-3 rounded-xl bg-accent-blue/15 hover:bg-accent-blue text-accent-blue hover:text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all shadow-sm">
                                    <span>Apply Now</span>
                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-8 text-slate-500 text-xs bg-white/[0.02] rounded-2xl border border-white/[0.05]">
                            No jobs currently available. Check back soon!
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ==================== RIGHT COLUMN ==================== --}}
        <div class="lg:col-span-4 space-y-5">

            {{-- 1. Prominent Membership / Plan Status Card --}}
            <div id="tour-cand-plan" class="bg-gradient-to-b from-[#0a1e4a]/95 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border {{ $isPlanExpired ? 'border-rose-500/40' : ($isPlanActive ? 'border-emerald-500/35' : 'border-amber-500/35') }} p-5 shadow-[0_6px_25px_rgba(0,0,0,0.28)] space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-white/[0.08]">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Membership / Plan Status</span>
                        <h4 class="text-xl font-black text-white capitalize mt-0.5 tracking-tight">
                            @if($profile && $profile->has_paid_plan)
                                {{ $profile->plan_type ? ucfirst($profile->plan_type) . ' Plan' : 'Standard Plan' }}
                            @else
                                No Active Plan
                            @endif
                        </h4>
                    </div>
                    @if($profile && $profile->is_plan_active)
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 uppercase tracking-wider shadow-sm">
                            Active
                        </span>
                    @elseif($profile && $profile->is_plan_expired)
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-rose-500/15 text-rose-300 border border-rose-500/30 uppercase tracking-wider shadow-sm">
                            Expired
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30 uppercase tracking-wider shadow-sm">
                            Inactive
                        </span>
                    @endif
                </div>

                {{-- Membership Dates & Validity Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-2 gap-2.5 text-xs">
                    <div class="rounded-xl bg-white/[0.04] border border-white/[0.08] px-3 py-2.5">
                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Start Date</span>
                        <span class="block font-extrabold text-white mt-0.5">
                            {{ $planStartDate ? $planStartDate->format('d M Y') : 'Not Activated' }}
                        </span>
                    </div>
                    <div class="rounded-xl bg-white/[0.04] border border-white/[0.08] px-3 py-2.5">
                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Expiry Date</span>
                        <span class="block font-extrabold {{ $isPlanExpired ? 'text-rose-300' : 'text-white' }} mt-0.5">
                            @if($planExpiryDate)
                                {{ $planExpiryDate->format('d M Y') }}
                                @if($planDaysNote)
                                    <span class="block text-[10px] font-semibold {{ $isPlanExpired ? 'text-rose-400' : 'text-emerald-300' }}">{{ $planDaysNote }}</span>
                                @endif
                            @else
                                —
                            @endif
                        </span>
                    </div>
                    <div class="rounded-xl bg-white/[0.04] border border-white/[0.08] px-3 py-2.5 lg:col-span-2">
                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Plan Validity</span>
                        <span class="block font-extrabold text-white mt-0.5">
                            @if($hasPaidPlan)
                                {{ $planMonths }} Months
                                <span class="text-[11px] font-semibold text-sky-300 ml-1">({{ $usedApplications }}/{{ $totalAllowedApplications }} applications used)</span>
                            @else
                                3 Months (Standard) / 6 Months (Premium)
                            @endif
                        </span>
                    </div>
                </div>

                @if($profile && $profile->is_plan_active && $profile->plan_type === 'premium')
                    <p class="text-xs text-emerald-400 font-medium leading-relaxed flex items-center gap-1.5">
                        <i class="fas fa-check-circle text-xs text-emerald-400"></i>
                        <span>You are enjoying full Premium benefits &amp; priority placement access.</span>
                    </p>
                @elseif($profile && $profile->is_plan_active)
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Standard Plan active. Upgrade to Premium and unlock exclusive career benefits.
                    </p>
                @elseif($profile && $profile->is_plan_expired)
                    <p class="text-xs text-rose-300 leading-relaxed">
                        Your plan membership has expired. Renew your plan to continue applying and receiving interview calls.
                    </p>
                @else
                    <p class="text-xs text-amber-300/90 leading-relaxed">
                        No active membership. Choose and activate a plan to unlock job applications and school interview calls.
                    </p>
                @endif

                {{-- Plan Features with Circular Check / Cross Icons --}}
                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center gap-2.5 {{ ($profile && $profile->has_paid_plan) ? 'text-white font-medium' : 'text-slate-400' }}">
                        <i class="fas {{ ($profile && $profile->has_paid_plan) ? 'fa-check-circle text-emerald-400' : 'fa-circle text-slate-600 text-[8px] mx-1' }} text-sm"></i>
                        <span>Apply to all available jobs</span>
                    </div>
                    <div class="flex items-center gap-2.5 {{ ($profile && $profile->has_paid_plan) ? 'text-white font-medium' : 'text-slate-400' }}">
                        <i class="fas {{ ($profile && $profile->has_paid_plan) ? 'fa-check-circle text-emerald-400' : 'fa-circle text-slate-600 text-[8px] mx-1' }} text-sm"></i>
                        <span>Profile visibility to schools</span>
                    </div>
                    <div class="flex items-center gap-2.5 {{ ($profile && $profile->has_paid_plan && $profile->plan_type === 'premium') ? 'text-white font-medium' : 'text-slate-500' }}">
                        <i class="fas {{ ($profile && $profile->has_paid_plan && $profile->plan_type === 'premium') ? 'fa-check-circle text-emerald-400' : 'fa-times-circle text-red-400/60' }} text-sm"></i>
                        <span>Dedicated Relationship Manager</span>
                    </div>
                    <div class="flex items-center gap-2.5 {{ ($profile && $profile->has_paid_plan && $profile->plan_type === 'premium') ? 'text-white font-medium' : 'text-slate-500' }}">
                        <i class="fas {{ ($profile && $profile->has_paid_plan && $profile->plan_type === 'premium') ? 'fa-check-circle text-emerald-400' : 'fa-times-circle text-red-400/60' }} text-sm"></i>
                        <span>Guaranteed Interviews</span>
                    </div>
                    <div class="flex items-center gap-2.5 {{ ($profile && $profile->has_paid_plan && $profile->plan_type === 'premium') ? 'text-white font-medium' : 'text-slate-500' }}">
                        <i class="fas {{ ($profile && $profile->has_paid_plan && $profile->plan_type === 'premium') ? 'fa-check-circle text-emerald-400' : 'fa-times-circle text-red-400/60' }} text-sm"></i>
                        <span>Resume Building Assistance</span>
                    </div>
                    <div class="flex items-center gap-2.5 {{ ($profile && $profile->has_paid_plan && $profile->plan_type === 'premium') ? 'text-white font-medium' : 'text-slate-500' }}">
                        <i class="fas {{ ($profile && $profile->has_paid_plan && $profile->plan_type === 'premium') ? 'fa-check-circle text-emerald-400' : 'fa-times-circle text-red-400/60' }} text-sm"></i>
                        <span>Priority application processing</span>
                    </div>
                </div>

                @if(!$profile || !$profile->has_paid_plan)
                    {{-- Activate Plan Box for Unpaid Users --}}
                    <div class="p-3.5 rounded-2xl bg-gradient-to-br from-emerald-500/20 via-[#0d2258] to-[#120f38] border border-emerald-500/30 flex items-center justify-between gap-3 shadow-md">
                        <div>
                            <div class="flex items-center gap-1.5 text-xs font-black text-emerald-400">
                                <i class="fas fa-bolt"></i>
                                <span>Activate Membership</span>
                            </div>
                        </div>
                        <a href="{{ $isAgreementSigned ? route('candidate.payment.show') : route('candidate.wizard') }}" 
                           class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 hover:from-emerald-300 hover:to-teal-400 text-slate-950 font-black text-xs shadow-md shadow-emerald-500/30 hover:shadow-lg hover:shadow-emerald-500/50 hover:brightness-105 hover:-translate-y-0.5 transition-all shrink-0 flex items-center gap-1.5 whitespace-nowrap">
                            <span class="font-black">Choose Plan</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                @elseif($profile->is_plan_expired)
                    {{-- Renew Plan Box --}}
                    <div class="p-3.5 rounded-2xl bg-gradient-to-br from-rose-500/20 via-[#0d2258] to-[#120f38] border border-rose-500/30 flex items-center justify-between gap-3 shadow-md">
                        <div>
                            <div class="flex items-center gap-1.5 text-xs font-black text-rose-400">
                                <i class="fas fa-redo-alt"></i>
                                <span>Renew Your Plan</span>
                            </div>
                            <p class="text-[10px] text-slate-300 mt-0.5">Extend validity for {{ ($profile && $profile->plan_type === 'premium') ? '6 Months' : '3 Months' }}</p>
                        </div>
                        <a href="{{ route('candidate.payment.show', ['type' => 'renewal']) }}" 
                           class="px-4 py-2 rounded-xl bg-gradient-to-r from-rose-500 to-amber-500 hover:from-rose-400 hover:to-amber-400 text-white font-black text-xs shadow-md shadow-rose-500/30 hover:shadow-lg transition-all shrink-0 flex items-center gap-1.5 whitespace-nowrap">
                            <span class="font-black">Renew Now</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                @elseif($profile->plan_type !== 'premium')
                    {{-- Upgrade & Renewal Actions for Active Standard Plan --}}
                    <div class="p-3.5 rounded-2xl bg-gradient-to-br from-amber-500/20 via-[#0d2258] to-[#120f38] border border-amber-500/30 flex items-center justify-between gap-3 shadow-md">
                        <div>
                            <div class="flex items-center gap-1.5 text-xs font-black text-amber-400">
                                <i class="fas fa-crown"></i>
                                <span>Upgrade to Premium</span>
                            </div>
                            <p class="text-[10px] text-slate-300 mt-0.5">Priority calls &amp; dedicated support</p>
                        </div>
                        <a href="{{ route('candidate.payment.show') }}" 
                           style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: #020617;"
                           class="px-4 py-2 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs shadow-md shadow-amber-500/30 hover:shadow-lg hover:shadow-amber-500/50 hover:brightness-105 hover:-translate-y-0.5 transition-all shrink-0 flex items-center gap-1.5 whitespace-nowrap">
                            <span class="font-black">Upgrade Now</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                    <div class="pt-1 flex items-center justify-between gap-2">
                        <a href="{{ route('candidate.payment.show', ['type' => 'renewal']) }}" class="text-xs font-bold text-amber-300 hover:text-amber-200 inline-flex items-center gap-1.5 transition-colors">
                            <i class="fas fa-redo-alt text-[10px]"></i>
                            <span>Renew Plan</span>
                        </a>
                        <a href="{{ route('candidate.payment.show') }}" class="text-xs font-bold text-accent-blue hover:text-white inline-flex items-center gap-1.5 transition-colors">
                            <span>Manage Plan &amp; Billing</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                @else
                    {{-- Renew & Manage Plan Actions for Active Premium --}}
                    <div class="pt-2 flex flex-wrap items-center justify-between gap-2.5 border-t border-white/[0.08]">
                        <a href="{{ route('candidate.payment.show', ['type' => 'renewal']) }}"
                           class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-white font-bold text-xs inline-flex items-center gap-1.5 transition-colors">
                            <i class="fas fa-redo-alt text-[10px]"></i>
                            <span>Renew Plan</span>
                        </a>
                        <a href="{{ route('candidate.payment.show') }}" class="text-xs font-bold text-accent-blue hover:text-white inline-flex items-center gap-1.5 transition-colors">
                            <span>Manage Plan &amp; Billing</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                @endif
            </div>

            {{-- 2. Refer & Earn Leaderboard Card --}}
            <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 shadow-[0_4px_20px_rgba(0,0,0,0.25)]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fas fa-trophy text-amber-400 text-sm"></i>
                        <span>Refer & Earn Leaderboard</span>
                    </h3>
                    <span class="text-[10px] font-bold text-slate-400 px-2.5 py-0.5 rounded-lg bg-white/5 border border-white/10">
                        This Week ⌵
                    </span>
                </div>

                {{-- Podium: Top 3 Referrers --}}
                @php
                    $rank1 = $leaderboard->get(0);
                    $rank2 = $leaderboard->get(1);
                    $rank3 = $leaderboard->get(2);
                @endphp
                <div class="grid grid-cols-3 gap-2 items-end text-center pt-3 pb-5 border-b border-white/[0.08]">
                    {{-- Rank 2 (Silver) --}}
                    @if($rank2)
                        <div class="flex flex-col items-center">
                            <div class="relative mb-2">
                                <div class="w-12 h-12 rounded-full bg-slate-800 border-2 border-slate-300 flex items-center justify-center font-bold text-xs text-white shadow-md">
                                    {{ strtoupper(substr($rank2['name'], 0, 1)) }}
                                </div>
                                <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-slate-300 text-slate-900 font-black text-[10px] flex items-center justify-center shadow">
                                    2
                                </span>
                            </div>
                            <div class="text-[11px] font-bold text-white truncate w-full" title="{{ $rank2['name'] }}">{{ $rank2['name'] }}</div>
                            <div class="text-[10px] font-extrabold text-amber-400 mt-0.5">{{ number_format($rank2['points']) }} <span class="text-[8px] font-normal text-slate-400">Pts</span></div>
                        </div>
                    @endif

                    {{-- Rank 1 (Gold Crown 👑) --}}
                    @if($rank1)
                        <div class="flex flex-col items-center -mt-3">
                            <div class="text-amber-400 text-base mb-0.5 animate-bounce">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="relative mb-2">
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 border-2 border-amber-300 flex items-center justify-center font-black text-sm text-slate-950 shadow-[0_0_15px_rgba(245,158,11,0.4)]">
                                    {{ strtoupper(substr($rank1['name'], 0, 1)) }}
                                </div>
                                <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-amber-400 text-slate-950 font-black text-[10px] flex items-center justify-center shadow">
                                    1
                                </span>
                            </div>
                            <div class="text-xs font-black text-white truncate w-full" title="{{ $rank1['name'] }}">{{ $rank1['name'] }}</div>
                            <div class="text-[11px] font-black text-amber-400 mt-0.5">{{ number_format($rank1['points']) }} <span class="text-[8px] font-normal text-slate-400">Pts</span></div>
                        </div>
                    @endif

                    {{-- Rank 3 (Bronze) --}}
                    @if($rank3)
                        <div class="flex flex-col items-center">
                            <div class="relative mb-2">
                                <div class="w-12 h-12 rounded-full bg-amber-950/80 border-2 border-amber-600 flex items-center justify-center font-bold text-xs text-white shadow-md">
                                    {{ strtoupper(substr($rank3['name'], 0, 1)) }}
                                </div>
                                <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-amber-600 text-white font-black text-[10px] flex items-center justify-center shadow">
                                    3
                                </span>
                            </div>
                            <div class="text-[11px] font-bold text-white truncate w-full" title="{{ $rank3['name'] }}">{{ $rank3['name'] }}</div>
                            <div class="text-[10px] font-extrabold text-amber-400 mt-0.5">{{ number_format($rank3['points']) }} <span class="text-[8px] font-normal text-slate-400">Pts</span></div>
                        </div>
                    @endif
                </div>

                {{-- Ranks 4 & 5 List --}}
                <div class="divide-y divide-white/[0.06]">
                    @foreach($leaderboard->slice(3, 2)->values() as $runner)
                        <div class="py-2.5 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="text-[10px] font-bold text-slate-500 w-4 text-center">{{ $loop->iteration + 3 }}</span>
                                <div class="w-7 h-7 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center text-[10px] font-bold text-white shrink-0">
                                    {{ strtoupper(substr($runner['name'], 0, 1)) }}
                                </div>
                                <span class="font-semibold text-white truncate">{{ $runner['name'] }}</span>
                            </div>
                            <span class="text-[11px] font-bold text-slate-300 shrink-0">{{ number_format($runner['points']) }} Pts</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3.5 mt-1 text-center">
                    <a href="{{ route('candidate.referral.index') }}" 
                       class="text-xs font-bold text-accent-blue hover:text-white inline-flex items-center gap-1.5 transition-colors">
                        <span>View Full Leaderboard</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

    {{-- Candidate Photo Lightbox Preview & Direct Photo Upload Modal --}}
    <div x-data="{
            photoModalOpen: false,
            uploading: false,
            uploadError: '',
            currentPhotoUrl: @js($hasPhoto ? asset('storage/' . $profile->profile_photo_path) : ''),
            pickPhoto() {
                this.uploadError = '';
                if (this.$refs.photoInput) {
                    this.$refs.photoInput.click();
                }
            },
            handlePhotoChange(event) {
                const file = event.target.files && event.target.files[0];
                if (!file) return;
                this.uploadError = '';
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    this.uploadError = 'Please select a valid JPG, PNG, or WEBP image.';
                    this.photoModalOpen = true;
                    event.target.value = '';
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    this.uploadError = 'Photo size must be 5MB or smaller.';
                    this.photoModalOpen = true;
                    event.target.value = '';
                    return;
                }
                this.uploading = true;
                this.$refs.photoForm.submit();
            }
        }"
        @open-photo-modal.window="photoModalOpen = true"
        @trigger-photo-upload.window="pickPhoto()"
        @keydown.escape.window="photoModalOpen = false">

        <form x-ref="photoForm" action="{{ route('candidate.profile.photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
            @csrf
            <input x-ref="photoInput" type="file" name="profile_photo" accept="image/jpeg,image/png,image/jpg,image/webp" @change="handlePhotoChange($event)">
        </form>

        <div x-show="photoModalOpen"
             x-cloak
             class="fixed inset-0 z-[998] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
             role="dialog"
             aria-modal="true"
             aria-labelledby="candidate-photo-modal-title">
            <div x-show="photoModalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="if (!uploading) photoModalOpen = false"
                 class="fixed inset-0 bg-slate-950/85 backdrop-blur-md"></div>

            <div x-show="photoModalOpen"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative z-10 w-full max-w-lg rounded-3xl bg-gradient-to-b from-[#0a1b4d] to-[#040e2d] border border-sky-400/30 p-5 sm:p-6 text-white shadow-2xl space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <h3 id="candidate-photo-modal-title" class="text-base sm:text-lg font-black text-white truncate">
                            {{ auth()->user()->name }}
                        </h3>
                        @if($profile->vpa_id)
                            <p class="text-[11px] font-mono text-sky-300 font-bold">{{ $profile->vpa_id }}</p>
                        @endif
                    </div>
                    <button type="button"
                            @click="if (!uploading) photoModalOpen = false"
                            class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center border border-white/15 transition-colors cursor-pointer"
                            title="Close preview">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <div x-show="uploadError" x-cloak class="rounded-xl bg-rose-500/15 border border-rose-500/35 px-3 py-2 text-xs text-rose-200 font-semibold flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-rose-400"></i>
                    <span x-text="uploadError"></span>
                </div>

                <div class="rounded-2xl bg-slate-950/70 border border-white/10 p-3 flex items-center justify-center min-h-[240px] max-h-[65vh] overflow-hidden">
                    @if($profile->profile_photo_path)
                        <img src="{{ asset('storage/' . $profile->profile_photo_path) }}"
                             alt="{{ auth()->user()->name }}"
                             class="max-h-[60vh] w-auto max-w-full rounded-xl object-contain shadow-xl">
                    @else
                        <div class="flex flex-col items-center justify-center py-8 text-center space-y-3">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-accent-blue via-blue-600 to-indigo-800 flex items-center justify-center text-4xl font-black ring-4 ring-white/20 shadow-xl">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <p class="text-xs text-slate-300">No profile photo uploaded yet.</p>
                        </div>
                    @endif
                </div>

                <div class="flex flex-wrap items-center justify-between gap-2.5 pt-1">
                    <span class="text-[11px] text-slate-400">JPG, PNG, WEBP (max 5MB)</span>
                    <div class="flex items-center gap-2 ml-auto">
                        <button type="button"
                                @click="photoModalOpen = false"
                                :disabled="uploading"
                                class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-xs font-bold text-slate-200 transition-colors cursor-pointer">
                            Close
                        </button>
                        <button type="button"
                                @click="pickPhoto()"
                                :disabled="uploading"
                                class="px-4 py-2 rounded-xl bg-gradient-to-r from-sky-400 to-blue-600 hover:brightness-110 text-white text-xs font-black shadow-md flex items-center gap-1.5 transition-all cursor-pointer">
                            <i class="fas" :class="uploading ? 'fa-spinner fa-spin' : 'fa-camera'"></i>
                            <span x-text="uploading ? 'Uploading...' : (currentPhotoUrl ? 'Update Photo' : 'Upload Photo')"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.platform-walkthrough', ['portal' => 'candidate'])

</div>
@endsection