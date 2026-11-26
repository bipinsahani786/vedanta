@extends('layouts.admin')

@section('title')
    CRM: {{ $candidate->name }}
    @if($candidate->profile && $candidate->profile->is_verified)
        <i class="fas fa-check-circle text-blue-500 text-base" title="Verified Candidate"></i>
    @endif
@endsection

@section('actions')
    <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-2 sm:mt-0">
        <a href="{{ route('admin.crm.index') }}" class="text-sm text-gray-600 hover:underline shrink-0">&larr; Back to List</a>
        
        <form action="{{ route('admin.crm.candidate.verify', $candidate->id) }}" method="POST" class="inline">
            @csrf
            @if($candidate->profile && $candidate->profile->is_verified)
                <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 text-sm font-semibold rounded-xl hover:bg-red-200 transition-colors flex items-center shadow-sm">
                    <i class="fas fa-times-circle mr-2"></i> Remove Verification
                </button>
            @else
                <button type="submit" class="px-4 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded-xl hover:bg-green-200 transition-colors flex items-center shadow-sm">
                    <i class="fas fa-check-circle mr-2"></i> Verify Profile
                </button>
            @endif
        </form>

        <a href="{{ route('admin.crm.edit', $candidate->id) }}" class="px-4 py-2 bg-blue-100 text-blue-700 text-sm font-semibold rounded-xl hover:bg-blue-200 transition-colors flex items-center shadow-sm">
            <i class="fas fa-edit mr-2"></i> Edit Profile
        </a>

        @if($candidate->phone)
            @php
                $waPhone = preg_replace('/[^0-9]/', '', $candidate->phone);
                if (strlen($waPhone) === 10) {
                    $waPhone = '91' . $waPhone;
                }
            @endphp
            <a href="https://wa.me/{{ $waPhone }}" target="_blank" class="px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-colors flex items-center shadow-sm">
                <i class="fab fa-whatsapp text-lg mr-2"></i> WhatsApp
            </a>
        @endif
        
        <button type="button" onclick="openSendEmailModal()" class="px-4 py-2 bg-purple-600 text-white text-sm font-semibold rounded-xl hover:bg-purple-700 transition-colors flex items-center shadow-sm">
            <i class="fas fa-paper-plane mr-2"></i> Send Email
        </button>

        <form action="{{ Route::has('admin.crm.candidate.send-agreement-link') ? route('admin.crm.candidate.send-agreement-link', $candidate->id) : url('/admin/crm/candidate/'.$candidate->id.'/send-agreement-link') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors flex items-center shadow-sm" title="Email agreement link to candidate" onclick="return confirm('Send agreement link email to {{ addslashes($candidate->name) }}?')">
                <i class="fas fa-envelope mr-2"></i> Send Agreement Link
            </button>
        </form>
        
        <a href="{{ route('admin.crm.candidate.magic-login', $candidate->id) }}" target="_blank" class="px-4 py-2 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-xl hover:bg-indigo-200 transition-colors flex items-center shadow-sm">
            <i class="fas fa-sign-in-alt mr-2"></i> Login as Candidate
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left Column: Profile & Applications -->
    <div class="lg:col-span-1 space-y-6 min-w-0">
        <!-- Candidate Profile -->
        <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 mb-6 overflow-hidden">
            <!-- Banner / Header -->
            <div class="p-4 sm:p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50/50 to-white flex flex-col sm:flex-row justify-between items-start relative gap-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 relative z-10 w-full">
                    @php
                        $primaryPhotoPath = $candidate->profile?->profile_photo_path ?: ($candidate->profile?->live_photo_path ?: $candidate->profile?->passport_photo_path);
                        $primaryPhotoUrl = $primaryPhotoPath ? Storage::url($primaryPhotoPath) : '';
                        $primaryPhotoLabel = $candidate->profile?->profile_photo_path ? 'Profile Photo' : ($candidate->profile?->live_photo_path ? 'Live / ID Photo' : ($candidate->profile?->passport_photo_path ? 'Passport Photo' : 'Profile Photo'));
                        $adminPhotoUploadUrl = route('admin.crm.candidate.photo', $candidate->id);
                    @endphp
                    <button type="button"
                            onclick='openAdminPhotoModal(@json($candidate->name), @json($candidate->profile?->vpa_id ?? ""), @json($primaryPhotoUrl), @json($adminPhotoUploadUrl), @json($primaryPhotoLabel))'
                            class="relative group shrink-0 rounded-full focus:outline-none cursor-pointer"
                            title="Click to enlarge or update photo">
                        @if($primaryPhotoUrl)
                            <img src="{{ $primaryPhotoUrl }}" alt="{{ $candidate->name }}" class="w-16 h-16 shrink-0 rounded-full object-cover shadow-sm border-4 border-white group-hover:ring-4 group-hover:ring-blue-400/50 transition-all">
                        @else
                            <div class="w-16 h-16 shrink-0 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-extrabold shadow-sm border-4 border-white group-hover:ring-4 group-hover:ring-blue-400/50 transition-all">
                                {{ strtoupper(substr($candidate->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="absolute -bottom-0.5 -right-0.5 w-6 h-6 rounded-full bg-blue-600 text-white border-2 border-white flex items-center justify-center text-[10px] shadow group-hover:scale-110 transition-transform">
                            <i class="fas {{ $primaryPhotoUrl ? 'fa-search-plus' : 'fa-camera' }}"></i>
                        </span>
                    </button>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h3 class="text-xl font-extrabold text-gray-900 break-words">{{ $candidate->name }}</h3>
                            @if($candidate->profile && $candidate->profile->vpa_id)
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs font-mono font-bold border border-gray-200 shadow-sm flex items-center gap-1.5" title="Vedanta Candidate ID">
                                    <i class="fas fa-id-badge text-gray-400"></i> {{ $candidate->profile->vpa_id }}
                                </span>
                            @endif

                            @php
                                $showProfile = $candidate->profile;
                                $isDetailsDone = $showProfile && $showProfile->hasCompletedProfileDetails();
                                $isAgrDone = $showProfile && ($showProfile->is_agreement_signed || !empty($showProfile->signature_date_time) || !empty($showProfile->agreement_signed_at) || !empty($showProfile->agreement_pdf_path) || $showProfile->is_manual_agreement);
                                $isFeeDone = $showProfile && $showProfile->has_paid_plan;
                                $isRegFullyDone = $showProfile && $showProfile->isRegistrationCompleted();
                            @endphp

                            @if(!$isDetailsDone)
                                <span class="bg-red-50 text-red-700 px-2.5 py-0.5 rounded-lg text-xs font-bold border border-red-200 shadow-sm flex items-center gap-1" title="Candidate profile details are incomplete (DOB/Address/Qualification pending)">
                                    <i class="fas fa-exclamation-circle text-red-500"></i> Profile Incomplete
                                </span>
                            @elseif(!$isAgrDone)
                                <span class="bg-amber-50 text-amber-800 px-2.5 py-0.5 rounded-lg text-xs font-bold border border-amber-200 shadow-sm flex items-center gap-1" title="Placement agreement signature is pending">
                                    <i class="fas fa-file-contract text-amber-600"></i> Pending Agreement
                                </span>
                            @elseif(!$isFeeDone)
                                <span class="bg-amber-50 text-amber-800 px-2.5 py-0.5 rounded-lg text-xs font-bold border border-amber-200 shadow-sm flex items-center gap-1" title="Registration fee payment is pending">
                                    <i class="fas fa-credit-card text-amber-600"></i> Pending Fee
                                </span>
                            @else
                                <span class="bg-emerald-50 text-emerald-800 px-2.5 py-0.5 rounded-lg text-xs font-bold border border-emerald-200 shadow-sm flex items-center gap-1" title="Registration Completed">
                                    <i class="fas fa-check-circle text-emerald-600"></i> Registered & Active
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 mt-1.5 flex flex-wrap items-center gap-1.5 sm:gap-4">
                            <span class="flex items-center gap-1.5"><i class="fas fa-envelope text-gray-400"></i> {{ $candidate->email }}</span>
                            <span class="flex items-center gap-1.5">
                                <i class="fas fa-phone-alt text-gray-400"></i> {{ $candidate->phone }}
                                @if($candidate->phone)
                                    @php
                                        $waCard = preg_replace('/[^0-9]/', '', $candidate->phone);
                                        if (strlen($waCard) === 10) {
                                            $waCard = '91' . $waCard;
                                        }
                                    @endphp
                                    <a href="https://wa.me/{{ $waCard }}" target="_blank" class="ml-1 inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[11px] font-bold rounded-full hover:bg-emerald-200 transition-colors">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </a>
                                @endif
                            </span>
                            @if($candidate->profile && $candidate->profile->latitude && $candidate->profile->longitude)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $candidate->profile->latitude }},{{ $candidate->profile->longitude }}" target="_blank" class="flex items-center gap-1.5 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-map-marker-alt text-gray-400"></i> {{ $candidate->profile->latitude }}, {{ $candidate->profile->longitude }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                {{-- Incomplete Profile / Registration Alert Banner --}}
                @if(!$isRegFullyDone)
                    <div class="mb-6 p-4 rounded-2xl border {{ !$isDetailsDone ? 'bg-red-50/80 border-red-200 shadow-sm' : 'bg-amber-50/80 border-amber-200 shadow-sm' }}">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ !$isDetailsDone ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-600' }}">
                                    <i class="fas fa-exclamation-triangle text-base"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-sm font-extrabold {{ !$isDetailsDone ? 'text-red-900' : 'text-amber-900' }}">
                                            Registration Status: {{ $showProfile ? $showProfile->pending_reason : 'Pending Profile Completion' }}
                                        </h4>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ !$isDetailsDone ? 'bg-red-200/60 text-red-800' : 'bg-amber-200/60 text-amber-800' }}">
                                            Action Required
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                        @if(!$isDetailsDone)
                                            Candidate has not completed essential profile details. 
                                            @php
                                                $missing = [];
                                                if(!$showProfile || empty($showProfile->date_of_birth)) $missing[] = 'Date of Birth';
                                                if(!$showProfile || empty($showProfile->address)) $missing[] = 'Address';
                                                if(!$showProfile || empty($showProfile->highest_qualification_id)) $missing[] = 'Highest Qualification';
                                                if(!$showProfile || empty($showProfile->resume_path)) $missing[] = 'Resume';
                                            @endphp
                                            @if(!empty($missing))
                                                <span class="font-semibold text-red-700">Missing: {{ implode(', ', $missing) }}</span>.
                                            @endif
                                        @elseif(!$isAgrDone)
                                            Candidate profile details are saved, but the placement agreement is pending digital signature or manual upload.
                                        @else
                                            Profile details and agreement are completed. Registration plan fee payment is pending.
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.crm.edit', $candidate->id) }}" class="px-3.5 py-1.5 bg-white hover:bg-gray-50 text-gray-800 text-xs font-bold rounded-xl border border-gray-300 transition-colors shadow-xs inline-flex items-center gap-1.5">
                                    <i class="fas fa-edit text-blue-600"></i> Edit Profile
                                </a>
                            </div>
                        </div>

                        {{-- 3-Step Onboarding Funnel Progress Tracker --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mt-4 pt-3.5 border-t {{ !$isDetailsDone ? 'border-red-100' : 'border-amber-100' }}">
                            <div class="flex items-center gap-2 p-2 rounded-xl {{ $isDetailsDone ? 'bg-emerald-50 text-emerald-800 border border-emerald-100' : 'bg-red-100/60 text-red-800 border border-red-200' }}">
                                <i class="fas {{ $isDetailsDone ? 'fa-check-circle text-emerald-600 text-sm' : 'fa-times-circle text-red-600 text-sm' }}"></i>
                                <div class="text-xs">
                                    <div class="font-bold">1. Profile Details</div>
                                    <div class="text-[10px] {{ $isDetailsDone ? 'text-emerald-700' : 'text-red-700 font-medium' }}">{{ $isDetailsDone ? 'Complete' : 'Incomplete' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 p-2 rounded-xl {{ $isAgrDone ? 'bg-emerald-50 text-emerald-800 border border-emerald-100' : 'bg-amber-100/60 text-amber-800 border border-amber-200' }}">
                                <i class="fas {{ $isAgrDone ? 'fa-check-circle text-emerald-600 text-sm' : 'fa-clock text-amber-600 text-sm' }}"></i>
                                <div class="text-xs">
                                    <div class="font-bold">2. Agreement</div>
                                    <div class="text-[10px] {{ $isAgrDone ? 'text-emerald-700' : 'text-amber-700 font-medium' }}">{{ $isAgrDone ? 'Signed' : 'Pending' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 p-2 rounded-xl {{ $isFeeDone ? 'bg-emerald-50 text-emerald-800 border border-emerald-100' : 'bg-amber-100/60 text-amber-800 border border-amber-200' }}">
                                <i class="fas {{ $isFeeDone ? 'fa-check-circle text-emerald-600 text-sm' : 'fa-clock text-amber-600 text-sm' }}"></i>
                                <div class="text-xs">
                                    <div class="font-bold">3. Plan Fee</div>
                                    <div class="text-[10px] {{ $isFeeDone ? 'text-emerald-700' : 'text-amber-700 font-medium' }}">{{ $isFeeDone ? 'Active / Paid' : 'Pending' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Account & Registration Overview (Always visible even if profile incomplete) -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3 gap-2 flex-wrap">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Contact, Account &amp; Registration Overview</h4>
                        <button type="button"
                                onclick='openAdminPhotoModal(@json($candidate->name), @json($candidate->profile?->vpa_id ?? ""), @json($primaryPhotoUrl), @json($adminPhotoUploadUrl), @json($primaryPhotoLabel))'
                                class="text-[11px] font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded-lg border border-blue-200 transition-colors inline-flex items-center gap-1 cursor-pointer">
                            <i class="fas fa-camera"></i> {{ $primaryPhotoUrl ? 'View / Update Photo' : 'Upload Photo' }}
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                            <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Candidate ID / User ID</div>
                            <div class="text-xs font-mono font-bold text-gray-800">{{ $candidate->profile?->vpa_id ?? 'Not Assigned' }} (#{{ $candidate->id }})</div>
                        </div>
                        <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                            <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Account &amp; Email</div>
                            <div class="text-xs font-bold {{ $candidate->is_active ? 'text-emerald-700' : 'text-red-600' }}">
                                {{ $candidate->is_active ? 'Active Account' : 'Inactive' }}
                                <span class="text-gray-400 font-normal">· {{ $candidate->email_verified_at ? 'Email Verified' : 'Email Unverified' }}</span>
                            </div>
                        </div>
                        <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                            <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Registered On</div>
                            <div class="text-xs font-medium text-gray-800">{{ $candidate->created_at ? $candidate->created_at->format('M d, Y h:i A') : 'N/A' }}</div>
                        </div>
                        <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                            <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Verification &amp; Views</div>
                            <div class="text-xs font-bold {{ $candidate->profile?->is_verified ? 'text-blue-700' : 'text-gray-600' }}">
                                {{ $candidate->profile?->is_verified ? 'Verified Badge' : 'Unverified' }}
                                <span class="text-gray-400 font-normal">· {{ $candidate->profile?->views_count ?? 0 }} Views</span>
                            </div>
                        </div>
                        <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                            <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Referral Code</div>
                            <div class="text-xs font-mono font-bold text-gray-700">{{ $candidate->referral_code ?? 'N/A' }}</div>
                        </div>
                        <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                            <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Referred By</div>
                            <div class="text-xs font-medium text-gray-800">{{ $candidate->referredBy?->name ?? 'Direct Signup' }}</div>
                        </div>
                    </div>

                    <!-- Password Security & Recovery Controls -->
                    @php
                        $issuedTemp = session('issued_temp_password');
                        $showIssuedBanner = is_array($issuedTemp) && (int) ($issuedTemp['candidate_id'] ?? 0) === (int) $candidate->id && !empty($issuedTemp['password']);
                    @endphp
                    <div class="mt-3 p-3.5 rounded-xl border {{ $candidate->requiresPasswordChange() ? 'bg-amber-50/70 border-amber-200' : 'bg-slate-50 border-gray-200' }}">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-600 flex items-center gap-1.5">
                                <i class="fas fa-shield-alt text-blue-600"></i> Password &amp; Recovery
                            </span>
                            @if($candidate->requiresPasswordChange())
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-200 text-amber-900">
                                    Temp Password Active · Must Change Next Login
                                </span>
                            @else
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700">
                                    Encrypted · Hidden
                                </span>
                            @endif
                        </div>

                        <p class="text-[11px] text-gray-500 leading-relaxed mb-2.5">
                            Passwords are stored encrypted and cannot be viewed by admins.
                            @if($candidate->temp_password_issued_at && $candidate->requiresPasswordChange())
                                Temp password issued {{ $candidate->temp_password_issued_at->copy()->timezone('Asia/Kolkata')->format('M d, Y h:i A') }} IST.
                            @elseif($candidate->password_changed_at)
                                Last changed {{ $candidate->password_changed_at->copy()->timezone('Asia/Kolkata')->format('M d, Y h:i A') }} IST.
                            @endif
                        </p>

                        @if($showIssuedBanner)
                            <div class="mb-2.5 p-2.5 rounded-lg bg-white border border-amber-300 shadow-sm">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-amber-800 mb-1">
                                    One-Time Temporary Password (Shown Once Only)
                                </div>
                                <div class="flex items-center justify-between gap-2">
                                    <code id="issuedTempPasswordText" class="text-xs font-mono font-extrabold text-gray-900 bg-gray-100 px-2.5 py-1 rounded select-all">{{ $issuedTemp['password'] }}</code>
                                    <button type="button" onclick="copyIssuedTempPassword(this)" class="text-[11px] font-bold px-2.5 py-1 rounded bg-blue-600 hover:bg-blue-700 text-white transition-colors cursor-pointer">
                                        <i class="fas fa-copy mr-1"></i> <span>Copy</span>
                                    </button>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-1">
                                    Not stored in readable form. Candidate will be forced to set a new password upon next login.
                                </p>
                            </div>
                        @endif

                        @if($errors->has('temporary_password'))
                            <div class="mb-2.5 p-2 rounded-lg bg-red-50 border border-red-200 text-[11px] text-red-700 font-semibold">
                                {{ $errors->first('temporary_password') }}
                            </div>
                        @endif

                        <div class="flex flex-wrap items-center gap-2">
                            <form action="{{ route('admin.crm.candidate.password-reset-link', $candidate->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Send self-service password reset link to {{ addslashes($candidate->email) }}?');">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-white hover:bg-blue-50 text-blue-700 border border-blue-200 text-[11px] font-bold transition-colors inline-flex items-center gap-1.5 cursor-pointer shadow-xs">
                                    <i class="fas fa-envelope-open-text"></i> Send Reset Link
                                </button>
                            </form>

                            <button type="button" onclick="openTempPasswordModal()" class="px-3 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold transition-colors inline-flex items-center gap-1.5 cursor-pointer shadow-xs">
                                <i class="fas fa-key"></i> Set Temporary Password
                            </button>
                        </div>
                    </div>

                    @php
                        $latestFu = $followUps->first();
                        $openFuCount = $followUps->where('status', 'open')->count();
                    @endphp
                    <div class="mt-3 p-3 rounded-xl border {{ $latestFu && $latestFu->is_due ? 'bg-amber-50 border-amber-200' : 'bg-blue-50/40 border-blue-100' }}">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider {{ $latestFu && $latestFu->is_due ? 'text-amber-700' : 'text-blue-700' }}">
                                <i class="fas fa-headset mr-1"></i> Latest Follow-up Status
                            </span>
                            @if($latestFu)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $latestFu->status === 'closed' ? 'bg-emerald-100 text-emerald-700' : ($latestFu->is_due ? 'bg-amber-200 text-amber-900' : 'bg-blue-100 text-blue-700') }}">
                                    {{ $latestFu->status === 'closed' ? 'Closed' : ($latestFu->is_due ? 'Due Now' : 'Open') }}
                                    @if($openFuCount > 1) · {{ $openFuCount }} Open @endif
                                </span>
                            @else
                                <span class="text-[10px] font-semibold text-gray-400">No Follow-ups Yet</span>
                            @endif
                        </div>
                        @if($latestFu)
                            @if(!empty($latestFu->next_action))
                                <div class="text-xs font-bold text-gray-800">Next Action: {{ $latestFu->next_action }}</div>
                            @endif
                            <div class="text-[11px] text-gray-600 mt-0.5 truncate">{{ $latestFu->notes }}</div>
                            <div class="flex items-center justify-between gap-2 mt-1.5 text-[10px]">
                                <span class="text-gray-500">
                                    @if($latestFu->scheduled_date_time)
                                        Scheduled: <strong>{{ $latestFu->follow_up_at ? $latestFu->follow_up_at->copy()->timezone('Asia/Kolkata')->format('M d, Y h:i A') . ' IST' : $latestFu->follow_up_date->format('M d, Y') }}</strong>
                                    @else
                                        Logged {{ $latestFu->created_at->format('M d, Y') }}
                                    @endif
                                </span>
                                <a href="#follow-ups-section" class="font-bold text-blue-600 hover:underline">Manage &darr;</a>
                            </div>
                        @else
                            <div class="flex items-center justify-between text-xs text-gray-500 mt-1">
                                <span>No call notes or next steps logged yet.</span>
                                <a href="#follow-ups-section" class="font-bold text-blue-600 hover:underline">+ Log Follow-up</a>
                            </div>
                        @endif
                    </div>

                    {{-- School Discovery & Visibility Controls --}}
                    @if($candidate->profile && \Illuminate\Support\Facades\Schema::hasColumn('candidate_profiles', 'school_discovery_visible'))
                        <div class="mt-3 p-3.5 rounded-xl border border-indigo-100 bg-indigo-50/40 space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-700">
                                    <i class="fas fa-school mr-1"></i> School Discovery Visibility
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $candidate->profile->school_discovery_visible ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-700' }}">
                                    {{ $candidate->profile->school_discovery_visible ? 'Visible to Schools' : 'Hidden from Schools' }}
                                </span>
                            </div>

                            <form action="{{ route('admin.crm.candidate.school-discovery', $candidate->id) }}" method="POST" class="space-y-2.5 text-xs">
                                @csrf
                                <div class="flex flex-wrap items-center gap-4">
                                    <label class="inline-flex items-center gap-1.5 font-semibold text-gray-800 cursor-pointer">
                                        <input type="checkbox" name="school_discovery_visible" value="1"
                                               {{ $candidate->profile->school_discovery_visible ? 'checked' : '' }}
                                               class="rounded text-indigo-600">
                                        <span>Allow in School Search</span>
                                    </label>
                                    <label class="inline-flex items-center gap-1.5 font-semibold text-amber-800 cursor-pointer">
                                        <input type="checkbox" name="is_best_profile" value="1"
                                               {{ $candidate->profile->is_best_profile ? 'checked' : '' }}
                                               class="rounded text-amber-600">
                                        <span>Mark as Best Profile</span>
                                    </label>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    <select name="school_display_mode" class="flex-1 min-w-[160px] rounded-lg border border-indigo-200 bg-white px-2.5 py-1.5 text-[11px] font-semibold text-gray-700 outline-none">
                                        <option value="vpa_id" {{ ($candidate->profile->school_display_mode ?? 'vpa_id') === 'vpa_id' ? 'selected' : '' }}>Show Identifier: VPA ID Only</option>
                                        <option value="masked_name" {{ ($candidate->profile->school_display_mode ?? '') === 'masked_name' ? 'selected' : '' }}>Show Identifier: Masked Name + ID</option>
                                        <option value="full_name" {{ ($candidate->profile->school_display_mode ?? '') === 'full_name' ? 'selected' : '' }}>Show Identifier: Full Name</option>
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-bold cursor-pointer">
                                        Save Visibility
                                    </button>
                                </div>
                            </form>

                            @if(isset($accessRequests) && $accessRequests->count() > 0)
                                <div class="pt-2 border-t border-indigo-100 space-y-1">
                                    <span class="text-[10px] font-bold uppercase text-indigo-700 block">School Unlock Requests ({{ $accessRequests->count() }})</span>
                                    @foreach($accessRequests->take(3) as $ar)
                                        <div class="flex items-center justify-between gap-2 text-[11px]">
                                            <a href="{{ route('admin.access-requests.show', $ar->id) }}" class="font-semibold text-blue-700 hover:underline truncate">
                                                {{ $ar->school?->employerProfile?->school_name ?: ($ar->school?->name ?? 'School') }}
                                            </a>
                                            <a href="{{ route('admin.access-requests.show', $ar->id) }}" class="px-1.5 py-0.5 rounded bg-white border border-indigo-200 text-[10px] font-bold text-gray-700 shrink-0">
                                                {{ $ar->statusLabel() }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Add to School Sharing File / Category --}}
                    @if(isset($sharingFiles) && \Illuminate\Support\Facades\Schema::hasTable('school_sharing_files'))
                        <div class="mt-3 p-3.5 rounded-xl border border-blue-100 bg-blue-50/40 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700">
                                    <i class="fas fa-folder-plus mr-1"></i> Add to School Sharing File
                                </span>
                                <a href="{{ route('admin.sharing-files.index') }}" class="text-[10px] font-bold text-blue-600 hover:underline">Manage Files</a>
                            </div>

                            @if($sharingFiles->count() > 0)
                                <form action="{{ route('admin.crm.candidate.add-to-sharing-file') }}" method="POST" class="space-y-2">
                                    @csrf
                                    <input type="hidden" name="candidate_ids[]" value="{{ $candidate->id }}">
                                    <select id="showSharingFileSelect" name="sharing_file_id" required onchange="syncShowFileCategories(this.value)"
                                            class="w-full rounded-lg border border-blue-200 bg-white px-2.5 py-1.5 text-[11px] font-semibold text-gray-700">
                                        <option value="">-- Select School File --</option>
                                        @foreach($sharingFiles as $sf)
                                            <option value="{{ $sf->id }}">
                                                {{ $sf->school?->employerProfile?->school_name ?: ($sf->school?->name ?? 'School') }} — {{ $sf->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select id="showSharingCategorySelect" name="category_id"
                                            class="w-full rounded-lg border border-blue-200 bg-white px-2.5 py-1.5 text-[11px] font-semibold text-gray-700">
                                        <option value="">-- Select Existing Category --</option>
                                    </select>
                                    <input type="text" name="new_category_name" maxlength="120"
                                           placeholder="Or create new category (e.g. TGT Hindi)"
                                           class="w-full rounded-lg border border-blue-200 bg-white px-2.5 py-1.5 text-[11px] text-gray-700">
                                    <button type="submit" class="w-full py-1.5 px-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold cursor-pointer">
                                        Add to School File / Category
                                    </button>
                                </form>
                                <script>
                                    const showSharingCatsMap = @json($sharingFiles->mapWithKeys(fn ($sf) => [$sf->id => $sf->categories->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->values()]));
                                    function syncShowFileCategories(fileId) {
                                        const sel = document.getElementById('showSharingCategorySelect');
                                        sel.innerHTML = '<option value="">-- Select Existing Category --</option>';
                                        (showSharingCatsMap[fileId] || []).forEach(c => {
                                            const opt = document.createElement('option');
                                            opt.value = c.id;
                                            opt.textContent = c.name;
                                            sel.appendChild(opt);
                                        });
                                    }
                                </script>
                            @else
                                <p class="text-[11px] text-gray-500">Create a School Sharing File first to assign this candidate.</p>
                            @endif

                            @if(isset($candidateSharingEntries) && $candidateSharingEntries->count() > 0)
                                <div class="pt-2 border-t border-blue-100 space-y-1">
                                    <span class="text-[10px] font-bold uppercase text-blue-700 block">Assigned Files ({{ $candidateSharingEntries->count() }})</span>
                                    @foreach($candidateSharingEntries as $cse)
                                        <div class="flex items-center justify-between gap-2 text-[11px]">
                                            <a href="{{ route('admin.sharing-files.show', ['id' => $cse->sharing_file_id, 'category_id' => $cse->category_id]) }}" class="font-semibold text-blue-700 hover:underline truncate">
                                                {{ $cse->sharingFile?->title }} · {{ $cse->category?->name }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                @if($candidate->profile)
                    <!-- Personal Info -->
                    <div class="mb-6">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Personal Details</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Gender</div>
                                <div class="text-sm font-medium text-gray-800">{{ $candidate->profile->gender ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Date of Birth</div>
                                <div class="text-sm font-medium text-gray-800">{{ $candidate->profile->date_of_birth ? $candidate->profile->date_of_birth->format('M d, Y') : 'N/A' }}</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100 col-span-2">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Address</div>
                                <div class="text-sm font-medium text-gray-800">{{ $candidate->profile->address ?? 'N/A' }}</div>
                                @if($candidate->profile->latitude && $candidate->profile->longitude)
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $candidate->profile->latitude }},{{ $candidate->profile->longitude }}" target="_blank" class="mt-1 inline-flex items-center gap-1 text-[11px] font-semibold text-blue-600 hover:underline">
                                        <i class="fas fa-map-pin"></i> GPS: {{ $candidate->profile->latitude }}, {{ $candidate->profile->longitude }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Professional Info -->
                    <div class="mb-6">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Professional Details</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-blue-50/50 p-3 rounded-xl border border-blue-50">
                                <div class="text-[10px] text-blue-400 uppercase font-bold mb-0.5 flex items-center gap-1"><i class="fas fa-folder"></i> Category</div>
                                <div class="text-sm font-bold text-blue-900">{{ $candidate->profile->category?->name ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-blue-50/50 p-3 rounded-xl border border-blue-50">
                                <div class="text-[10px] text-blue-400 uppercase font-bold mb-0.5 flex items-center gap-1"><i class="fas fa-book"></i> Subject</div>
                                <div class="text-sm font-bold text-blue-900">{{ $candidate->profile->subject?->name ?? 'N/A' }}</div>
                            </div>
                            @if($candidate->profile->specialization)
                                <div class="bg-blue-50/50 p-3 rounded-xl border border-blue-50 col-span-2">
                                    <div class="text-[10px] text-blue-400 uppercase font-bold mb-0.5 flex items-center gap-1"><i class="fas fa-tag"></i> Specialization</div>
                                    <div class="text-sm font-bold text-blue-900">{{ $candidate->profile->specialization->name }}</div>
                                </div>
                            @endif
                            <div class="bg-orange-50/50 p-3 rounded-xl border border-orange-50">
                                <div class="text-[10px] text-orange-400 uppercase font-bold mb-0.5 flex items-center gap-1"><i class="fas fa-graduation-cap"></i> Highest Qualification</div>
                                <div class="text-sm font-bold text-orange-900">{{ $candidate->profile->highestQualification?->name ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-emerald-50/50 p-3 rounded-xl border border-emerald-50">
                                <div class="text-[10px] text-emerald-500 uppercase font-bold mb-0.5 flex items-center gap-1"><i class="fas fa-briefcase"></i> Experience</div>
                                <div class="text-sm font-bold text-emerald-900">{{ $candidate->profile->experience_years ?? 0 }} Years</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Current School / Institution</div>
                                <div class="text-sm font-medium text-gray-800">{{ $candidate->profile->current_school ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">English Fluency</div>
                                <div class="text-sm font-medium text-gray-800 capitalize">{{ $candidate->profile->english_fluency ?? 'N/A' }}</div>
                            </div>

                            @if($candidate->profile->other_qualifications)
                                <div class="bg-amber-50/50 p-3 rounded-xl border border-amber-100 col-span-2">
                                    <div class="text-[10px] text-amber-600 uppercase font-bold mb-1 flex items-center gap-1"><i class="fas fa-certificate"></i> Other Qualifications & Certifications</div>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach(array_filter(array_map('trim', explode(',', $candidate->profile->other_qualifications))) as $qualItem)
                                            <span class="inline-block px-2 py-0.5 bg-amber-100/80 text-amber-900 text-xs font-semibold rounded-md border border-amber-200">{{ $qualItem }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Current Salary</div>
                                <div class="text-sm font-medium text-gray-800">{{ $candidate->profile->current_salary ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Expected Salary</div>
                                <div class="text-sm font-medium text-gray-800">{{ $candidate->profile->expected_salary ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">School Type Preference</div>
                                <div class="text-sm font-medium text-gray-800 capitalize">{{ $candidate->profile->residential_preference ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Availability to Join</div>
                                <div class="text-sm font-medium text-gray-800">{{ $candidate->profile->availability_to_join ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100 col-span-2">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5 flex items-center gap-1"><i class="fas fa-map-marker-alt"></i> Preferred Location</div>
                                <div class="text-sm font-medium text-gray-800">{{ $candidate->profile->preferredCity?->name ?? 'N/A' }}, {{ $candidate->profile->preferredState?->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Plan & Transactions -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Plan & Transaction</h4>
                            <button type="button" onclick="document.getElementById('manualPaymentModal').style.display='flex'" class="px-2.5 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg text-xs font-bold border border-emerald-200 transition-colors flex items-center gap-1 shadow-sm">
                                <i class="fas fa-arrow-up"></i> Manual Upgrade / Payment
                            </button>
                        </div>

                        <!-- Manual Payment & Plan Upgrade Modal -->
                        <div id="manualPaymentModal" style="display: none;" class="fixed inset-0 z-[9999] bg-gray-900/60 backdrop-blur-sm items-center justify-center p-4">
                            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 relative">
                                <button type="button" onclick="document.getElementById('manualPaymentModal').style.display='none'" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 font-bold text-lg">
                                    &times;
                                </button>
                                
                                <h3 class="text-lg font-extrabold text-gray-900 mb-1 flex items-center gap-2">
                                    <i class="fas fa-credit-card text-emerald-600"></i> Manual Plan Upgrade / Payment
                                </h3>
                                <p class="text-xs text-gray-500 mb-4">Mark candidate payment as completed and upgrade their plan directly from admin panel.</p>

                                <form action="{{ route('admin.crm.candidate.fulfill-payment', $candidate->id) }}" method="POST" class="space-y-4" onsubmit="return handleManualPaymentSubmit(this);">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Select Plan Type</label>
                                        <select name="plan_type" required class="w-full rounded-xl border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                                            <option value="standard" {{ ($candidate->profile->plan_type ?? '') === 'standard' ? 'selected' : '' }}>Standard Plan (2 Applications)</option>
                                            <option value="premium" {{ ($candidate->profile->plan_type ?? '') === 'premium' || !($candidate->profile->plan_type ?? '') ? 'selected' : '' }}>Premium Plan (3 Applications)</option>
                                        </select>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Amount Paid (₹)</label>
                                            <input type="number" name="amount" value="1000" min="0" required class="w-full rounded-xl border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Payment Method</label>
                                            <select name="payment_method" required class="w-full rounded-xl border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                                                <option value="phonepe">PhonePe / UPI</option>
                                                <option value="bank_transfer">Bank Transfer / NEFT</option>
                                                <option value="cash">Cash</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Admin Notes (Optional)</label>
                                        <textarea name="admin_notes" rows="2" placeholder="e.g. Payment received on PhonePe for candidate" class="w-full rounded-xl border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                                    </div>

                                    <div class="flex justify-end gap-3 pt-2">
                                        <button type="button" onclick="document.getElementById('manualPaymentModal').style.display='none'" class="px-4 py-2.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-200">
                                            Cancel
                                        </button>
                                        <button type="submit" id="manualPaymentSubmitBtn" class="px-5 py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 shadow-sm transition-all flex items-center gap-2">
                                            <span>Confirm & Upgrade Plan</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-1">Fee Payment</div>
                                @if($candidate->profile->is_fee_paid)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1 text-[10px]"></i> Active / Paid</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1 text-[10px]"></i> Pending</span>
                                @endif
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-1">Profile Details</div>
                                @if($isDetailsDone)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1 text-[10px]"></i> Complete</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800"><i class="fas fa-exclamation-circle mr-1 text-[10px]"></i> Incomplete</span>
                                @endif
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100 col-span-2 sm:col-span-1">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-1">Plan Details</div>
                                <div class="text-sm font-bold text-gray-800">{{ $candidate->profile->plan_type ? ucfirst($candidate->profile->plan_type) : 'Standard' }} Plan</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Paid / Pending Dues</div>
                                <div class="text-xs font-bold text-emerald-700">
                                    ₹{{ number_format($candidate->profile->paid_amount ?? 0) }}
                                    @if(($candidate->profile->pending_amount ?? 0) > 0)
                                        <span class="text-rose-600 ml-1">/ Due ₹{{ number_format($candidate->profile->pending_amount) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Applications Quota</div>
                                <div class="text-xs font-bold text-gray-800">{{ $candidate->profile->used_applications ?? 0 }} / {{ $candidate->profile->total_allowed_applications ?? 0 }} Used</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Plan Start / Validity</div>
                                <div class="text-xs font-medium text-gray-700">
                                    {{ $candidate->profile->plan_started_at ? $candidate->profile->plan_started_at->format('M d, Y') : 'N/A' }}
                                    @if($candidate->profile->plan_validity_date)
                                        <span class="text-[10px] text-gray-500 block">Valid till {{ $candidate->profile->plan_validity_date->format('M d, Y') }} ({{ $candidate->profile->plan_status_badge }})</span>
                                    @endif
                                </div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100 col-span-2 flex justify-between items-center">
                                <div>
                                    <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Transaction ID &amp; Agreement ID</div>
                                    <div class="font-mono text-xs font-bold text-gray-600">{{ $candidate->profile->payment_id ?? 'No Transaction' }}</div>
                                    <div class="font-mono text-[10px] text-gray-500 mt-0.5">Agr: {{ $candidate->profile->agreement_id ?? 'Not Assigned' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Completed / Signed On</div>
                                    <div class="text-xs font-medium text-gray-600">{{ $candidate->profile->registration_completed_at ? $candidate->profile->registration_completed_at->format('M d, Y') : 'N/A' }}</div>
                                    <div class="text-[10px] text-gray-500 mt-0.5">Terms: {{ $candidate->profile->is_terms_agreed ? 'Agreed' : 'Pending' }}</div>
                                </div>
                            </div>
                            @if($isAgrDone)
                            <div class="bg-blue-50/40 p-3 rounded-xl border border-blue-100 col-span-2 sm:col-span-3">
                                <div class="text-[10px] text-blue-600 uppercase font-bold mb-1">Agreement Signature Audit Details</div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs text-gray-700">
                                    <div><span class="text-gray-400 block text-[10px]">Type</span><strong>{{ $candidate->profile->signature_type ? ucfirst($candidate->profile->signature_type) : 'Signed' }}</strong></div>
                                    <div><span class="text-gray-400 block text-[10px]">Signed At</span><strong>{{ $candidate->profile->agreement_signed_at ? $candidate->profile->agreement_signed_at->format('M d, Y h:i A') : 'N/A' }}</strong></div>
                                    <div><span class="text-gray-400 block text-[10px]">IP Address</span><span class="font-mono">{{ $candidate->profile->signature_ip_address ?? 'N/A' }}</span></div>
                                    <div class="truncate" title="{{ $candidate->profile->signature_device_info ?? '' }}"><span class="text-gray-400 block text-[10px]">Device</span><span>{{ $candidate->profile->signature_device_info ?? 'N/A' }}</span></div>
                                </div>
                                @if($candidate->profile->signature_type === 'type' && $candidate->profile->signature_data)
                                    <div class="mt-2 pt-2 border-t border-blue-100/70 text-xs text-gray-600">
                                        Signature Name: <strong class="italic text-gray-900">{{ $candidate->profile->signature_data }}</strong>
                                    </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Photos & Documents Gallery -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Collected Photos &amp; Documents</h4>
                            <button type="button"
                                    onclick='openAdminPhotoModal(@json($candidate->name), @json($candidate->profile?->vpa_id ?? ""), @json($primaryPhotoUrl), @json($adminPhotoUploadUrl), "Profile Photo")'
                                    class="text-[11px] font-bold text-blue-600 hover:underline cursor-pointer">
                                <i class="fas fa-camera mr-1"></i> Update Photo
                            </button>
                        </div>
                        @if($candidate->profile->profile_photo_path || $candidate->profile->live_photo_path || $candidate->profile->passport_photo_path)
                            <div class="grid grid-cols-3 gap-2.5 mb-3">
                                @if($candidate->profile->profile_photo_path)
                                    <button type="button"
                                            onclick='openAdminPhotoModal(@json($candidate->name), @json($candidate->profile?->vpa_id ?? ""), @json(Storage::url($candidate->profile->profile_photo_path)), @json($adminPhotoUploadUrl), "Profile Photo")'
                                            class="group text-left bg-amber-50/70 hover:bg-amber-100/80 border border-amber-200/80 rounded-xl p-2 transition-all cursor-pointer">
                                        <img src="{{ Storage::url($candidate->profile->profile_photo_path) }}" alt="Profile Photo" class="w-full h-16 object-cover rounded-lg mb-1.5 group-hover:opacity-90">
                                        <div class="text-[10px] font-bold text-amber-800 flex items-center justify-between">
                                            <span>Profile Photo</span>
                                            <i class="fas fa-expand-alt text-[9px]"></i>
                                        </div>
                                    </button>
                                @endif
                                @if($candidate->profile->live_photo_path)
                                    <button type="button"
                                            onclick='openAdminPhotoModal(@json($candidate->name), @json($candidate->profile?->vpa_id ?? ""), @json(Storage::url($candidate->profile->live_photo_path)), @json($adminPhotoUploadUrl), "Live / ID Photo")'
                                            class="group text-left bg-rose-50/70 hover:bg-rose-100/80 border border-rose-200/80 rounded-xl p-2 transition-all cursor-pointer">
                                        <img src="{{ Storage::url($candidate->profile->live_photo_path) }}" alt="Live Photo" class="w-full h-16 object-cover rounded-lg mb-1.5 group-hover:opacity-90">
                                        <div class="text-[10px] font-bold text-rose-800 flex items-center justify-between">
                                            <span>Live Photo</span>
                                            <i class="fas fa-expand-alt text-[9px]"></i>
                                        </div>
                                    </button>
                                @endif
                                @if($candidate->profile->passport_photo_path)
                                    <button type="button"
                                            onclick='openAdminPhotoModal(@json($candidate->name), @json($candidate->profile?->vpa_id ?? ""), @json(Storage::url($candidate->profile->passport_photo_path)), @json($adminPhotoUploadUrl), "Passport Photo")'
                                            class="group text-left bg-blue-50/70 hover:bg-blue-100/80 border border-blue-200/80 rounded-xl p-2 transition-all cursor-pointer">
                                        <img src="{{ Storage::url($candidate->profile->passport_photo_path) }}" alt="Passport Photo" class="w-full h-16 object-cover rounded-lg mb-1.5 group-hover:opacity-90">
                                        <div class="text-[10px] font-bold text-blue-800 flex items-center justify-between">
                                            <span>Passport Photo</span>
                                            <i class="fas fa-expand-alt text-[9px]"></i>
                                        </div>
                                    </button>
                                @endif
                            </div>
                        @endif
                        <div class="flex flex-wrap gap-2">
                            @if($candidate->profile->resume_path)
                                <a href="{{ Storage::url($candidate->profile->resume_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 text-indigo-700 rounded-lg text-xs font-bold transition-colors">
                                    <i class="fas fa-file-pdf"></i> Resume
                                </a>
                            @endif
                            @if($candidate->profile->salary_slip_path)
                                <a href="{{ Storage::url($candidate->profile->salary_slip_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 text-emerald-700 rounded-lg text-xs font-bold transition-colors">
                                    <i class="fas fa-file-invoice-dollar"></i> Salary Slip
                                </a>
                            @endif
                            @if($candidate->profile->offer_letter_path)
                                <a href="{{ Storage::url($candidate->profile->offer_letter_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-purple-50 hover:bg-purple-100 border border-purple-100 text-purple-700 rounded-lg text-xs font-bold transition-colors">
                                    <i class="fas fa-file-contract"></i> Offer Letter
                                </a>
                            @endif
                            @if($candidate->profile->profile_photo_path)
                                <button type="button" onclick='openAdminPhotoModal(@json($candidate->name), @json($candidate->profile?->vpa_id ?? ""), @json(Storage::url($candidate->profile->profile_photo_path)), @json($adminPhotoUploadUrl), "Profile Photo")' class="inline-flex items-center gap-2 px-3 py-2 bg-amber-50 hover:bg-amber-100 border border-amber-100 text-amber-700 rounded-lg text-xs font-bold transition-colors cursor-pointer">
                                    <i class="fas fa-image"></i> Profile Photo
                                </button>
                            @endif
                            @if($candidate->profile->live_photo_path)
                                <button type="button" onclick='openAdminPhotoModal(@json($candidate->name), @json($candidate->profile?->vpa_id ?? ""), @json(Storage::url($candidate->profile->live_photo_path)), @json($adminPhotoUploadUrl), "Live / ID Photo")' class="inline-flex items-center gap-2 px-3 py-2 bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-700 rounded-lg text-xs font-bold transition-colors cursor-pointer">
                                    <i class="fas fa-camera"></i> Live Photo
                                </button>
                            @endif
                             @if($candidate->profile->agreement_pdf_path || $candidate->profile->is_agreement_signed || $candidate->profile->signature_data)
                                 <a href="{{ Route::has('admin.crm.candidate.download-agreement') ? route('admin.crm.candidate.download-agreement', $candidate->id) : url('/admin/crm/candidate/'.$candidate->id.'/download-agreement') }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-teal-50 hover:bg-teal-100 border border-teal-100 text-teal-700 rounded-lg text-xs font-bold transition-colors" title="Click to view/download signed agreement (auto-generates if missing)">
                                     <i class="fas fa-file-signature"></i> Signed Agreement (PDF)
                                 </a>
                             @endif
                        </div>
                    </div>

                    <!-- Manual Agreement Upload -->
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                            <div>
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Agreement Status</h4>
                                @if($candidate->profile && $candidate->profile->is_manual_agreement)
                                    <div class="text-[11px] text-purple-700 font-bold flex items-center gap-1 mt-0.5">
                                        <i class="fas fa-file-pdf"></i> Custom Admin-Uploaded PDF Active
                                    </div>
                                @elseif($candidate->profile && $candidate->profile->has_uploaded_manual_backup)
                                    <div class="text-[11px] text-amber-600 font-bold flex items-center gap-1 mt-0.5">
                                        <i class="fas fa-info-circle"></i> System Template Active (Uploaded Backup Available)
                                    </div>
                                @endif
                            </div>
                            @if($candidate->profile && ($candidate->profile->is_agreement_signed || $candidate->profile->agreement_pdf_path || $candidate->profile->signature_date_time || $candidate->profile->signature_data))
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($candidate->profile->has_uploaded_manual_backup && ! $candidate->profile->is_manual_agreement)
                                        <form action="{{ Route::has('admin.crm.candidate.restore-agreement') ? route('admin.crm.candidate.restore-agreement', $candidate->id) : url('/admin/crm/candidate/'.$candidate->id.'/restore-agreement') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 text-amber-800 rounded-full text-xs font-bold border border-amber-300 hover:bg-amber-100 transition-colors shadow-sm" title="Restore previously uploaded custom agreement" onclick="return confirm('Restore your previously uploaded custom agreement PDF as active for this candidate?')">
                                                <i class="fas fa-undo-alt text-amber-600"></i> Restore Uploaded PDF
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ Route::has('admin.crm.candidate.send-agreement-link') ? route('admin.crm.candidate.send-agreement-link', $candidate->id) : url('/admin/crm/candidate/'.$candidate->id.'/send-agreement-link') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-full text-[11px] font-bold border border-blue-200 transition-colors shadow-xs" title="Send agreement email link to candidate" onclick="return confirm('Send agreement link email to {{ addslashes($candidate->name) }}?')">
                                            <i class="fas fa-envelope"></i> Send Link
                                        </button>
                                    </form>

                                    <a href="{{ Route::has('admin.crm.candidate.preview-agreement') ? route('admin.crm.candidate.preview-agreement', $candidate->id) : url('/admin/crm/candidate/'.$candidate->id.'/preview-agreement') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-100 transition-colors" title="Live Preview Agreement PDF">
                                        <i class="fas fa-eye"></i> Live Preview
                                    </a>
                                    <a href="{{ Route::has('admin.crm.candidate.download-agreement') ? route('admin.crm.candidate.download-agreement', $candidate->id) : url('/admin/crm/candidate/'.$candidate->id.'/download-agreement') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-bold border border-green-200 hover:bg-green-100 transition-colors" title="Download Agreement">
                                        <i class="fas fa-download"></i> Download PDF
                                    </a>
                                    <a href="{{ Route::has('admin.crm.candidate.download-agreement') ? route('admin.crm.candidate.download-agreement', ['id' => $candidate->id, 'regenerate' => 1]) : url('/admin/crm/candidate/'.$candidate->id.'/download-agreement?regenerate=1') }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-600 hover:text-indigo-600 rounded-full text-[11px] font-bold border border-gray-300 hover:bg-indigo-50 transition-colors" title="Force regenerate PDF from template" onclick="return confirm('Attention: Re-generate will switch the active agreement to the system auto-generated template. You can restore your custom uploaded PDF anytime with the \'Restore Uploaded PDF\' button. Proceed?')">
                                        <i class="fas fa-sync-alt"></i> Re-generate
                                    </a>
                                </div>
                            @else
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-yellow-50 text-yellow-700 rounded-full text-xs font-bold border border-yellow-200">
                                        <i class="fas fa-clock"></i> Not Signed
                                    </span>
                                    <form action="{{ Route::has('admin.crm.candidate.send-agreement-link') ? route('admin.crm.candidate.send-agreement-link', $candidate->id) : url('/admin/crm/candidate/'.$candidate->id.'/send-agreement-link') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-full text-[11px] font-bold border border-blue-200 transition-colors" title="Send email link to candidate to sign agreement">
                                            <i class="fas fa-envelope"></i> Send Link
                                        </button>
                                    </form>
                                    <a href="{{ Route::has('admin.crm.candidate.download-agreement') ? route('admin.crm.candidate.download-agreement', ['id' => $candidate->id, 'regenerate' => 1]) : url('/admin/crm/candidate/'.$candidate->id.'/download-agreement?regenerate=1') }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-full text-[11px] font-bold border border-indigo-200 transition-colors" title="Force generate Agreement PDF">
                                        <i class="fas fa-file-pdf"></i> Generate PDF
                                    </a>
                                </div>
                            @endif
                        </div>

                        <form action="{{ Route::has('admin.crm.candidate.upload-agreement') ? route('admin.crm.candidate.upload-agreement', $candidate->id) : url('/admin/crm/candidate/'.$candidate->id.'/upload-agreement') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-4 rounded-xl border border-gray-200 shadow-sm">
                            @csrf
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-bold text-gray-700">Manually Upload Agreement (PDF)</label>
                                <span class="text-[11px] text-gray-400">Replaces current agreement for candidate</span>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <input type="file" name="agreement_pdf" accept="application/pdf" required class="flex-1 block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                <button type="submit" class="shrink-0 px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 transition-colors shadow-sm flex items-center justify-center gap-1.5">
                                    <i class="fas fa-cloud-upload-alt"></i> Upload & Send
                                </button>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-2">
                                <i class="fas fa-info-circle mr-1"></i> Uploading will immediately update candidate's agreement view and email a copy.
                            </p>
                        </form>
                    </div>

                    <!-- Verification -->
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <form action="{{ route('admin.crm.candidate.verify', $candidate->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 {{ $candidate->profile->is_verified ? 'bg-red-50 text-red-600 border border-red-200 hover:bg-red-100' : 'bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100' }} rounded-xl text-sm font-bold transition-colors shadow-sm flex items-center justify-center gap-2">
                                @if($candidate->profile->is_verified)
                                    <i class="fas fa-times-circle"></i> Revoke Verification Badge
                                @else
                                    <i class="fas fa-check-circle"></i> Verify & Award Badge
                                @endif
                            </button>
                        </form>
                    </div>
                @else
                    <div class="py-10 flex flex-col items-center justify-center text-gray-500">
                        <i class="fas fa-user-slash text-4xl mb-3 text-gray-300"></i>
                        <p class="font-medium text-sm">No profile data available yet.</p>
                        <p class="text-xs text-gray-400 mt-1">Candidate has not completed their profile setup.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Job Applications -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col gap-3 mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Job Applications</h3>
                    <form action="{{ route('admin.crm.application.assign', $candidate->id) }}" method="POST" class="flex flex-col gap-2 w-full">
                        @csrf
                        <select name="job_post_id" required class="text-sm rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 w-full">
                            <option value="">-- Assign a Job to Candidate --</option>
                            @foreach($availableJobs as $job)
                                <option value="{{ $job->id }}">{{ $job->title }} ({{ $job->job_code }}) - {{ $job->school_name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-3 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm w-full text-center">Assign Job</button>
                    </form>
                </div>
                @forelse($candidate->applications as $app)
                    <div class="mb-4 pb-4 border-b border-gray-100 last:border-0 last:mb-0 last:pb-0">
                        <div class="font-semibold text-gray-800">
                            {{ $app->jobPost->title ?? 'N/A' }}
                            @if($app->jobPost)
                                <span class="text-xs font-mono font-bold text-indigo-600 ml-1">({{ $app->jobPost->job_code }})</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 mb-1">{{ $app->jobPost->school_name ?? '' }}</div>
                            <div class="mt-3">
                                <form action="{{ route('admin.applications.status.update', $app->id) }}" method="POST" class="space-y-3 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    @csrf
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                        <label class="text-xs font-bold text-gray-700 shrink-0">Status:</label>
                                        <select name="status" class="text-xs font-bold px-2 py-1.5 rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer w-full">
                                            <option value="applied" {{ $app->status === 'applied' ? 'selected' : '' }}>Applied</option>
                                            <option value="shortlisted" {{ $app->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted (Schedule Interview)</option>
                                            <option value="hold" {{ $app->status === 'hold' ? 'selected' : '' }}>Hold</option>
                                            <option value="hired" {{ $app->status === 'hired' ? 'selected' : '' }}>Hired</option>
                                            <option value="rejected" {{ $app->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Interview Date (If Shortlisted)</label>
                                            <input type="datetime-local" name="interview_date" value="{{ $app->interview_date }}" class="w-full text-xs rounded-lg border-gray-300 shadow-sm px-2 py-1.5 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Interview Link / Location</label>
                                            <input type="text" name="interview_link" value="{{ $app->interview_link }}" placeholder="e.g. Zoom Link or Address" class="w-full text-xs rounded-lg border-gray-300 shadow-sm px-2 py-1.5 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Remarks (Visible to Candidate)</label>
                                        <textarea name="remarks" rows="2" class="w-full text-xs rounded-lg border-gray-300 shadow-sm px-2 py-1.5 focus:ring-blue-500 focus:border-blue-500" placeholder="Add feedback or updates here...">{{ $app->remarks }}</textarea>
                                    </div>

                                    <div class="flex flex-col sm:flex-row justify-end gap-2 sm:items-center w-full">
                                        @if($app->status === 'hired')
                                            @if(!$app->invoice)
                                                <button type="button" onclick="prepareInvoice({{ $app->id }})" class="w-full sm:w-auto text-xs px-3 py-2 sm:py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 font-bold transition-colors shadow-sm text-center">
                                                    <i class="fas fa-file-invoice-dollar mr-1"></i> Generate Invoice
                                                </button>
                                            @else
                                                <span class="w-full sm:w-auto text-center text-xs px-3 py-2 sm:py-1.5 bg-gray-100 text-green-700 font-bold rounded-lg border border-green-200">
                                                    <i class="fas fa-check-circle mr-1"></i> Invoiced
                                                </span>
                                            @endif
                                        @endif
                                        
                                        <button type="submit" class="w-full sm:w-auto text-center text-xs px-4 py-2 sm:py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold transition-colors shadow-sm whitespace-nowrap">
                                            Save Updates
                                        </button>
                                    </div>
                                </form>
                            </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No applications found.</p>
                @endforelse
            </div>
        </div>

        <!-- Candidate Rating System -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Admin Ratings</h3>
                    @if($rating)
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded"><i class="fas fa-star text-yellow-500 mr-1"></i> {{ number_format($rating->overall_rating, 1) }} Overall</span>
                    @endif
                </div>

                <form action="{{ route('admin.crm.candidate.rate', $candidate->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @php
                        $params = [
                            'communication' => 'Communication Skills',
                            'subject_knowledge' => 'Subject Knowledge',
                            'demo_performance' => 'Demo Performance',
                            'english_fluency' => 'English Fluency',
                            'discipline' => 'Professionalism & Discipline'
                        ];
                    @endphp

                    @foreach($params as $key => $label)
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700">{{ $label }}</label>
                        <select name="{{ $key }}" class="rounded-md border-gray-300 shadow-sm text-sm p-1 w-24">
                            @for($i=1; $i<=5; $i++)
                                <option value="{{ $i }}" {{ ($rating && $rating->$key == $i) ? 'selected' : ($i==3 ? 'selected' : '') }}>{{ $i }} Stars</option>
                            @endfor
                        </select>
                    </div>
                    @endforeach

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Remarks</label>
                        <textarea name="remarks" rows="2" class="w-full rounded-md border-gray-300 shadow-sm text-sm">{{ $rating->remarks ?? '' }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded text-sm font-bold hover:bg-gray-900 transition-colors">
                        Save Ratings
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: CRM Follow-ups & Invoices -->
    <div class="lg:col-span-2 space-y-6 min-w-0">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Service Charge Invoices -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Service Charge Invoices</h3>
                </div>

                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full bg-white border border-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="py-2 px-4 text-left font-medium">Job Role</th>
                                <th class="py-2 px-4 text-left font-medium">Amount</th>
                                <th class="py-2 px-4 text-left font-medium">Late Fee</th>
                                <th class="py-2 px-4 text-left font-medium">Due Date</th>
                                <th class="py-2 px-4 text-left font-medium">Status</th>
                                <th class="py-2 px-4 text-left font-medium">Payment Mode</th>
                                <th class="py-2 px-4 text-left font-medium">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($invoices as $invoice)
                            <tr>
                                <td class="py-2 px-4">
                                    {{ $invoice->jobApplication?->jobPost?->title ?? 'N/A' }}
                                    @if($invoice->jobApplication?->jobPost)
                                        <span class="text-xs font-mono font-semibold text-gray-500 ml-1">({{ $invoice->jobApplication->jobPost->job_code }})</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4">₹{{ number_format($invoice->amount, 2) }}</td>
                                <td class="py-2 px-4 text-red-600">₹{{ number_format($invoice->late_fee, 2) }}</td>
                                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                                <td class="py-2 px-4">
                                    <span class="px-2 py-1 rounded text-xs font-bold 
                                        {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="py-2 px-4">
                                    @if($invoice->status === 'paid')
                                        @if($invoice->payment_method === 'Online')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                                <i class="fas fa-globe text-[10px]"></i> Online (Auto)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                                <i class="fas fa-user-check text-[10px]"></i> Manual (Admin)
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 font-medium">&mdash;</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 space-y-2">
                                    <div class="flex items-center flex-wrap gap-2">
                                        <button type="button" 
                                                class="text-xs text-purple-700 hover:text-purple-900 font-bold bg-purple-50 hover:bg-purple-100 border border-purple-200 px-2 py-1 rounded transition-colors inline-flex items-center gap-1"
                                                onclick="openSendEmailForSpecificInvoice({{ $invoice->id }})"
                                                title="Send email or payment receipt for this specific invoice">
                                            <i class="fas fa-paper-plane text-[10px]"></i> Send Email
                                        </button>

                                        <button type="button" 
                                                class="text-xs text-amber-600 hover:text-amber-900 font-bold bg-amber-50 px-2 py-1 rounded transition-colors" 
                                                onclick="openEditInvoiceModal({{ $invoice->id }}, {{ $invoice->amount }}, {{ $invoice->late_fee }}, '{{ \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') }}', '{{ $invoice->status }}')">
                                            <i class="fas fa-edit mr-0.5"></i> Edit
                                        </button>

                                        @if($invoice->status !== 'paid')
                                        <form action="{{ route('admin.crm.invoice.update', $invoice->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="paid">
                                            <button type="submit" class="text-xs text-green-600 hover:text-green-900 font-bold" onclick="return confirm('Mark this invoice as Paid?')">Mark Paid</button>
                                        </form>

                                        <form action="{{ route('admin.crm.invoice.remind', $invoice->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-900 font-bold" onclick="return confirm('Send payment reminder email to candidate?')">Send Reminder</button>
                                        </form>
                                        @else
                                            <span class="text-xs text-gray-400 font-medium">Settled</span>
                                        @endif

                                        <form action="{{ route('admin.crm.invoice.destroy', $invoice->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-900 font-bold bg-red-50 px-2 py-1 rounded transition-colors" onclick="return confirm('Are you sure you want to delete this invoice?')">
                                                <i class="fas fa-trash-alt mr-0.5"></i> Delete
                                            </button>
                                        </form>
                                    </div>

                                    @if($invoice->status !== 'paid' && $invoice->late_fee > 0)
                                    <div class="mt-2 border-t border-gray-100 pt-2">
                                        <form action="{{ route('admin.crm.invoice.adjust', $invoice->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <input type="number" name="deduction" max="{{ $invoice->late_fee }}" min="1" required placeholder="Amt" class="w-16 rounded-md border-gray-300 shadow-sm text-xs py-1 px-2 focus:ring-blue-500 focus:border-blue-500">
                                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-900 font-bold bg-blue-50 px-2 py-1 rounded">Waive</button>
                                        </form>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-500">No invoices generated yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Generate Invoice Form -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-4">
                    <h4 class="font-bold text-sm text-gray-800 mb-3">Generate New Invoice</h4>
                    <form action="{{ route('admin.crm.invoice.store', $candidate->id) }}" method="POST" class="flex flex-wrap gap-4 items-end">
                        @csrf
                        
                        @if($errors->any())
                            <div class="w-full bg-red-100 text-red-700 p-2 rounded text-xs mb-2">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="w-full md:w-auto flex-1">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Select Hired Job Application</label>
                            <select name="job_application_id" id="job_application_id" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">-- Select Application --</option>
                                @php
                                    $hiredApps = $candidate->applications->where('status', 'hired');
                                    $otherApps = $candidate->applications->where('status', '!=', 'hired');
                                @endphp
                                @if($hiredApps->isNotEmpty())
                                    <optgroup label="Hired Applications (Ready for Invoicing)">
                                        @foreach($hiredApps as $app)
                                            <option value="{{ $app->id }}" selected>{{ $app->jobPost->title ?? 'Job' }} @if($app->jobPost)({{ $app->jobPost->job_code }}) - {{ $app->jobPost->school_name }}@endif [Hired]</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                                @if($otherApps->isNotEmpty())
                                    <optgroup label="Other Assigned Applications">
                                        @foreach($otherApps as $app)
                                            <option value="{{ $app->id }}">{{ $app->jobPost->title ?? 'Job' }} @if($app->jobPost)({{ $app->jobPost->job_code }}) - {{ $app->jobPost->school_name }}@endif [{{ ucfirst($app->status) }}]</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                            @if($candidate->applications->isEmpty())
                                <p class="text-[11px] text-amber-600 mt-1 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>No applications found. Please assign a job using the form above first.</p>
                            @endif
                        </div>
                        <div class="w-24">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Amount (₹)</label>
                            <input type="number" name="amount" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500" required min="0">
                        </div>
                        <div class="w-36">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Due Date</label>
                            <input type="date" name="due_date" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm">
                                Create Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Follow-ups -->
        <div id="follow-ups-section" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-calendar-check text-blue-600"></i> Follow-ups &amp; Conversation Notes
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Record call discussion, schedule date &amp; time, and track next action with automatic admin reminders.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $followUps->where('status', 'open')->isNotEmpty() ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600' }}">
                        {{ $followUps->where('status', 'open')->count() }} Open / {{ $followUps->count() }} Total
                    </span>
                </div>
                
                <!-- Add Follow-up Form -->
                <form action="{{ route('admin.crm.followup.store', $candidate->id) }}" method="POST" class="mb-6 bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-4">
                    @csrf
                    <input type="hidden" name="tz_offset" id="follow_up_tz_offset" value="-330">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Conversation Notes / Call Summary <span class="text-red-500">*</span></label>
                        <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500" required placeholder="What was discussed with the candidate?">{{ old('notes') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Next Process / Action</label>
                            <input type="text"
                                   id="follow_up_next_action"
                                   name="next_action"
                                   list="crmNextActionSuggestions"
                                   value="{{ old('next_action') }}"
                                   placeholder="e.g. Agreement Call, Payment Link, Interview"
                                   class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                            <datalist id="crmNextActionSuggestions">
                                <option value="Follow-up Call">
                                <option value="Agreement Signature Follow-up">
                                <option value="Registration Payment Follow-up">
                                <option value="Collect Pending Profile / CV">
                                <option value="Schedule School Interview">
                                <option value="Share Matching Vacancy">
                                <option value="Profile Verification Review">
                            </datalist>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Follow-up Date &amp; Time</label>
                            <input type="datetime-local"
                                   name="follow_up_at"
                                   value="{{ old('follow_up_at') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                                <option value="open" {{ old('status', 'open') === 'open' ? 'selected' : '' }}>Open / Scheduled (Send Reminder)</option>
                                <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed / Completed</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                        <div class="flex flex-wrap items-center gap-1.5">
                            @foreach(['Follow-up Call', 'Agreement Follow-up', 'Payment Follow-up', 'Schedule Interview', 'Verify Profile'] as $quickAct)
                                <button type="button"
                                        onclick="document.getElementById('follow_up_next_action').value = @js($quickAct)"
                                        class="px-2.5 py-1 rounded-md bg-white hover:bg-blue-50 border border-gray-200 hover:border-blue-300 text-[11px] font-semibold text-gray-600 hover:text-blue-700 transition-colors cursor-pointer">
                                    + {{ $quickAct }}
                                </button>
                            @endforeach
                        </div>
                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm cursor-pointer">
                            Save Follow-up
                        </button>
                    </div>
                </form>

                <!-- Follow-up History -->
                <div class="space-y-4">
                    <h4 class="font-bold text-sm text-gray-600 uppercase tracking-wider mb-2">Follow-up History</h4>
                    @forelse($followUps as $fu)
                        <div class="border-l-4 {{ $fu->status === 'closed' ? 'border-green-400 bg-green-50/30' : ($fu->is_due ? 'border-red-500 bg-red-50/40' : 'border-yellow-400 bg-yellow-50/20') }} pl-4 pr-3 py-3 rounded-r-xl border border-l-4 border-gray-100">
                            <div class="flex flex-wrap justify-between items-start gap-2 mb-1.5">
                                <span class="text-xs font-bold text-gray-500">{{ $fu->admin->name ?? 'Admin' }} &bull; {{ $fu->created_at->format('M d, Y h:i A') }}</span>
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($fu->status === 'open' && $fu->is_due)
                                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-red-100 text-red-700">Due / Overdue</span>
                                    @endif
                                    @if($fu->reminder_sent_at)
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-blue-100 text-blue-700" title="Admin notified on {{ $fu->reminder_sent_at->format('M d, Y h:i A') }}">
                                            <i class="fas fa-bell mr-0.5"></i> Reminder Sent
                                        </span>
                                    @elseif($fu->status === 'open' && $fu->scheduled_date_time)
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-600">
                                            <i class="fas fa-clock mr-0.5"></i> Reminder Scheduled
                                        </span>
                                    @endif
                                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded {{ $fu->status === 'open' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">{{ ucfirst($fu->status) }}</span>
                                    <form action="{{ route('admin.crm.followup.status', $fu->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ $fu->status === 'open' ? 'closed' : 'open' }}">
                                        <button type="submit" class="text-[11px] font-bold px-2 py-0.5 rounded border {{ $fu->status === 'open' ? 'bg-white text-emerald-700 border-emerald-300 hover:bg-emerald-50' : 'bg-white text-amber-700 border-amber-300 hover:bg-amber-50' }} transition-colors cursor-pointer">
                                            {{ $fu->status === 'open' ? 'Mark Closed' : 'Reopen' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if(!empty($fu->next_action))
                                <div class="mb-1.5 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-indigo-50 border border-indigo-200 text-indigo-800 text-xs font-bold">
                                    <i class="fas fa-forward text-[10px]"></i> Next Action: {{ $fu->next_action }}
                                </div>
                            @endif
                            <p class="text-sm text-gray-800 leading-relaxed">{{ $fu->notes }}</p>
                            @if($fu->scheduled_date_time)
                                <div class="mt-2 text-xs {{ $fu->is_due ? 'text-red-600 font-bold' : 'text-indigo-600 font-medium' }}">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Scheduled Follow-up: {{ $fu->follow_up_at ? $fu->follow_up_at->copy()->timezone('Asia/Kolkata')->format('M d, Y h:i A') . ' IST' : $fu->follow_up_date->format('M d, Y') }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">No follow-ups recorded yet.</p>
                    @endforelse
                </div>

            </div>
        </div>

        <!-- History Timeline -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Audit Trail / History</h3>
                
                <div class="relative border-l-2 border-gray-200 ml-4 space-y-8">
                    @forelse($history as $event)
                        <div class="relative pl-6">
                            <!-- Timeline Dot -->
                            <div class="absolute -left-3.5 top-0 w-7 h-7 rounded-full {{ $event['color'] }} text-white flex items-center justify-center text-xs shadow-md border-2 border-white">
                                <i class="{{ $event['icon'] }}"></i>
                            </div>
                            
                            <!-- Content -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-bold text-gray-800 text-sm">{{ $event['title'] }}</h4>
                                    <span class="text-xs text-gray-500 font-medium">{{ \Carbon\Carbon::parse($event['date'])->format('M d, Y h:i A') }}</span>
                                </div>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $event['description'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="pl-6 text-sm text-gray-500 italic">No history found for this candidate.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Issue Temporary Password Modal -->
<div id="tempPasswordModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4 hidden">
    <div class="absolute inset-0" onclick="closeTempPasswordModal()"></div>
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 border border-gray-100 relative z-10">
        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
            <div>
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <i class="fas fa-key text-amber-600"></i> Issue Temporary Password
                </h3>
                <p class="text-[11px] text-gray-500 mt-0.5">{{ $candidate->name }} ({{ $candidate->email }})</p>
            </div>
            <button type="button" onclick="closeTempPasswordModal()" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.crm.candidate.temporary-password', $candidate->id) }}" class="space-y-4">
            @csrf

            <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 leading-relaxed">
                <i class="fas fa-lock mr-1"></i>
                Existing passwords are stored one-way hashed and cannot be retrieved. Issuing a temporary password forces <strong>{{ $candidate->name }}</strong> to create a new password on their next login.
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Generation Method</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center gap-2 p-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 text-xs font-semibold text-gray-800">
                        <input type="radio" name="mode" value="auto" checked onchange="toggleTempPasswordMode()" class="text-amber-600 focus:ring-amber-500">
                        <span>Auto-Generate</span>
                    </label>
                    <label class="flex items-center gap-2 p-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 text-xs font-semibold text-gray-800">
                        <input type="radio" name="mode" value="manual" onchange="toggleTempPasswordMode()" class="text-amber-600 focus:ring-amber-500">
                        <span>Custom Temporary</span>
                    </label>
                </div>
            </div>

            <div id="manualTempPasswordGroup" class="hidden">
                <label for="manual_temp_password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Custom Temporary Password <span class="text-red-500">*</span>
                </label>
                <input type="text" id="manual_temp_password" name="temporary_password" minlength="8" maxlength="64" autocomplete="off"
                       placeholder="At least 8 characters"
                       class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2 px-3 focus:ring-amber-500 focus:border-amber-500 font-mono">
            </div>

            <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                <input type="checkbox" name="send_email" value="1" checked class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                <span>Email temporary login instructions to <strong>{{ $candidate->email }}</strong></span>
            </label>

            <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeTempPasswordModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-amber-600 text-white rounded-xl text-xs font-bold hover:bg-amber-700 transition-colors shadow-sm">
                    Issue Temporary Password
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Invoice Modal -->
<div id="editInvoiceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 border border-gray-100 relative mx-4">
        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
            <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                <i class="fas fa-file-invoice text-blue-600"></i> Edit Service Charge Invoice
            </h3>
            <button type="button" onclick="closeEditInvoiceModal()" class="text-gray-400 hover:text-gray-600 text-lg font-bold">
                &times;
            </button>
        </div>

        <form id="editInvoiceForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Base Amount (₹)</label>
                <input type="number" step="0.01" min="0" id="edit_invoice_amount" name="amount" required class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Late Fee (₹)</label>
                <input type="number" step="0.01" min="0" id="edit_invoice_late_fee" name="late_fee" required class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Due Date</label>
                <input type="date" id="edit_invoice_due_date" name="due_date" required class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Status</label>
                                        <select id="edit_invoice_status" name="status" required class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                    <option value="pending">Pending</option>
                    <option value="overdue">Overdue</option>
                    <option value="paid">Paid</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeEditInvoiceModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition-colors shadow-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

    <!-- Send Email to Candidate Modal -->
    <div id="sendEmailModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4 hidden">
        <div class="absolute inset-0" onclick="closeSendEmailModal()"></div>
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl relative z-10 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-100">
                <div>
                    <h3 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                        <i class="fas fa-paper-plane text-purple-600"></i> Send Email to Candidate
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Recipient: <span class="font-semibold text-gray-800">{{ $candidate->name }}</span> ({{ $candidate->email }})</p>
                </div>
                <button type="button" onclick="closeSendEmailModal()" class="text-gray-400 hover:text-gray-600 text-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('admin.crm.candidate.send-email', $candidate->id) }}" method="POST" class="space-y-4" onsubmit="return handleSendEmailSubmit(this)">
                @csrf

                @if($invoices->isNotEmpty())
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Select Related Invoice</label>
                        <span class="text-[11px] text-purple-600 font-semibold"><i class="fas fa-sync-alt mr-1"></i>Switch invoice anytime</span>
                    </div>
                    <select id="crm_invoice_select" name="invoice_id" class="w-full rounded-xl border-purple-200 bg-purple-50/50 shadow-sm text-sm py-2 px-3 focus:ring-purple-500 focus:border-purple-500 font-medium" onchange="switchEmailInvoice(this.value)">
                        @foreach($invoices as $inv)
                            <option value="{{ $inv->id }}" {{ $loop->first ? 'selected' : '' }}>
                                #INV-SC-{{ str_pad($inv->id, 5, '0', STR_PAD_LEFT) }} &mdash; ₹{{ number_format($inv->amount, 2) }} ({{ ucfirst($inv->status) }}) &mdash; {{ $inv->jobApplication?->jobPost?->title ?? 'Placement' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-gray-400 mt-1">If candidate has multiple invoices, choose which one to include in this email.</p>
                </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Choose Email Template</label>
                    <select id="crm_template_select" class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-purple-500 focus:border-purple-500" onchange="applySelectedTemplate(this.value)">
                        <option value="">-- Select an Email Template ({{ $emailTemplates->count() }} Available) --</option>
                        @foreach($emailTemplates as $tmpl)
                            <option value="{{ $tmpl->id }}">{{ $tmpl->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-gray-400 mt-1">Selecting a template auto-fills the subject and message with this candidate's details.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Subject <span class="text-red-500">*</span></label>
                    <input type="text" id="email_subject" name="subject" required placeholder="Email subject..." class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2 px-3 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Email HTML Body <span class="text-red-500">*</span></label>
                        <button type="button" onclick="toggleEmailPreview()" class="text-xs font-semibold text-purple-600 hover:underline">
                            <span id="preview_btn_text">Preview Rendered Email</span>
                        </button>
                    </div>
                    <textarea id="email_body" name="body" rows="10" required class="w-full font-mono text-xs rounded-xl border-gray-300 shadow-sm py-2 px-3 focus:ring-purple-500 focus:border-purple-500" placeholder="Select a template above or type HTML content..."></textarea>
                    
                    <div id="email_rendered_preview" class="hidden border border-gray-200 rounded-xl p-4 bg-gray-50 max-h-[350px] overflow-y-auto mt-2"></div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeSendEmailModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="sendEmailSubmitBtn" class="px-6 py-2 bg-purple-600 text-white rounded-xl text-xs font-bold hover:bg-purple-700 transition-colors shadow-sm flex items-center gap-1.5">
                        <i class="fas fa-paper-plane"></i> Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
@include('admin.crm.partials.photo-modal')
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tzInput = document.getElementById('follow_up_tz_offset');
        if (tzInput) {
            tzInput.value = new Date().getTimezoneOffset();
        }
    });
</script>
@endpush
@endsection

@push('scripts')
<script>
    function openSendEmailModal() {
        const modal = document.getElementById('sendEmailModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeSendEmailModal() {
        const modal = document.getElementById('sendEmailModal');
        if (modal) modal.classList.add('hidden');
    }

    @php
        $latestInv = isset($invoices) && $invoices ? $invoices->first() : null;
        $latestPay = isset($payments) && $payments ? $payments->first() : null;

        $rawInvAmt = $latestInv ? (float) $latestInv->amount : 0;
        $rawPaidAmt = $candidate->profile ? (float) $candidate->profile->paid_amount : 0;
        $rawPayTxnAmt = $latestPay ? (float) $latestPay->amount : 0;

        $computedAmt = $rawInvAmt > 0 ? $rawInvAmt : ($rawPayTxnAmt > 0 ? $rawPayTxnAmt : ($rawPaidAmt > 0 ? $rawPaidAmt : 500));
        $formattedAmt = number_format($computedAmt, 2);

        $invNum = $latestInv ? 'INV-SC-'.str_pad($latestInv->id, 5, '0', STR_PAD_LEFT) : ($latestPay ? ($latestPay->transaction_id ?? 'INV-PAY') : ($candidate->profile?->payment_id ?? 'INV-VPA'));
        $invDueDate = $latestInv && $latestInv->due_date ? \Carbon\Carbon::parse($latestInv->due_date)->format('M d, Y') : now()->addDays(7)->format('M d, Y');
        $invStatus = $latestInv ? ucfirst($latestInv->status) : ($candidate->profile?->is_fee_paid ? 'Paid' : 'Pending');

        $invoicesMapData = ($invoices ?? collect())->mapWithKeys(function($inv) {
            $formattedInvAmt = number_format((float) $inv->amount, 2);
            return [$inv->id => [
                'id' => $inv->id,
                'amount' => $formattedInvAmt,
                'payment_amount' => $formattedInvAmt,
                'invoice_amount' => $formattedInvAmt,
                'service_charge' => $formattedInvAmt,
                'service_charge_amount' => $formattedInvAmt,
                'pending_amount' => number_format((float) ($inv->amount + ($inv->late_fee ?? 0)), 2),
                'invoice_number' => 'INV-SC-' . str_pad($inv->id, 5, '0', STR_PAD_LEFT),
                'due_date' => $inv->due_date ? \Carbon\Carbon::parse($inv->due_date)->format('M d, Y') : now()->addDays(7)->format('M d, Y'),
                'status' => ucfirst($inv->status),
                'job_title' => $inv->jobApplication?->jobPost?->title ?? 'Teaching Position',
                'school_name' => $inv->jobApplication?->jobPost?->school_name ?? 'Partner Educational Institution',
            ]];
        });
    @endphp

    const invoicesMap = @json($invoicesMapData);

    const candidateData = {
        name: @json($candidate->name),
        email: @json($candidate->email),
        phone: @json($candidate->phone ?? ''),
        category: @json($candidate->profile->category->name ?? 'Teaching'),
        subject: @json($candidate->profile->subject->name ?? 'Faculty'),
        plan_type: @json(ucfirst($candidate->profile->plan_type ?? 'Standard')),
        payment_amount: @json($formattedAmt),
        amount: @json($formattedAmt),
        invoice_amount: @json($formattedAmt),
        service_charge: @json($formattedAmt),
        service_charge_amount: @json($formattedAmt),
        pending_amount: @json($latestInv ? number_format($latestInv->amount + $latestInv->late_fee, 2) : number_format($candidate->profile->pending_amount ?? 0, 2)),
        invoice_number: @json($invNum),
        due_date: @json($invDueDate),
        status: @json($invStatus),
        job_title: @json($candidate->applications->first()?->jobPost?->title ?? 'Teaching Position'),
        school_name: @json($candidate->applications->first()?->jobPost?->school_name ?? 'Partner Educational Institution'),
        interview_date: @json($candidate->applications->first()?->interview_date ? \Carbon\Carbon::parse($candidate->applications->first()->interview_date)->format('M d, Y h:i A') : 'To be confirmed'),
        interview_link: @json($candidate->applications->first()?->interview_link ?? 'https://vedantaplacementagency.in/candidate/applications'),
        remarks: @json($candidate->applications->first()?->remarks ?? 'None'),
        action_url: 'https://vedantaplacementagency.in/candidate/dashboard'
    };

    const emailTemplatesMap = @json($emailTemplates->keyBy('id'));

    function switchEmailInvoice(invId) {
        if (!invId || !invoicesMap[invId]) return;
        const inv = invoicesMap[invId];
        candidateData.payment_amount = inv.payment_amount;
        candidateData.amount = inv.amount;
        candidateData.invoice_amount = inv.invoice_amount;
        candidateData.service_charge = inv.service_charge;
        candidateData.service_charge_amount = inv.service_charge_amount;
        candidateData.pending_amount = inv.pending_amount;
        candidateData.invoice_number = inv.invoice_number;
        candidateData.due_date = inv.due_date;
        candidateData.status = inv.status;
        if (inv.job_title) candidateData.job_title = inv.job_title;
        if (inv.school_name) candidateData.school_name = inv.school_name;

        const tmplSelect = document.getElementById('crm_template_select');
        if (tmplSelect && tmplSelect.value) {
            applySelectedTemplate(tmplSelect.value);
        }
    }

    function openSendEmailForSpecificInvoice(invId) {
        openSendEmailModal();
        const invSelect = document.getElementById('crm_invoice_select');
        if (invSelect) {
            invSelect.value = invId;
        }
        switchEmailInvoice(invId);

        const tmplSelect = document.getElementById('crm_template_select');
        if (tmplSelect) {
            const inv = invoicesMap[invId];
            const targetName = (inv && inv.status === 'Paid') ? 'Payment Confirmation' : 'Service Charge Invoice Generated';
            for (let i = 0; i < tmplSelect.options.length; i++) {
                if (tmplSelect.options[i].text.toLowerCase().includes(targetName.toLowerCase())) {
                    tmplSelect.selectedIndex = i;
                    applySelectedTemplate(tmplSelect.value);
                    break;
                }
            }
        }
    }

    function applySelectedTemplate(tmplId) {
        if (!tmplId || !emailTemplatesMap[tmplId]) return;
        const tmpl = emailTemplatesMap[tmplId];
        
        let subj = tmpl.subject;
        let body = tmpl.body;

        for (const [key, val] of Object.entries(candidateData)) {
            const regex = new RegExp('(\\{' + key + '\\}|\\[\\s*' + key + '\\s*\\])', 'gi');
            subj = subj.replace(regex, val || '');
            body = body.replace(regex, val || '');
        }

        document.getElementById('email_subject').value = subj;
        document.getElementById('email_body').value = body;
        
        const previewDiv = document.getElementById('email_rendered_preview');
        if (!previewDiv.classList.contains('hidden')) {
            previewDiv.innerHTML = body;
        }
    }

    function toggleEmailPreview() {
        const bodyVal = document.getElementById('email_body').value;
        const previewDiv = document.getElementById('email_rendered_preview');
        const btnText = document.getElementById('preview_btn_text');

        if (previewDiv.classList.contains('hidden')) {
            previewDiv.innerHTML = bodyVal;
            previewDiv.classList.remove('hidden');
            btnText.textContent = 'Hide Preview';
        } else {
            previewDiv.classList.add('hidden');
            btnText.textContent = 'Preview Rendered Email';
        }
    }

    function handleSendEmailSubmit(form) {
        const btn = document.getElementById('sendEmailSubmitBtn');
        if (btn) {
            setTimeout(() => {
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Sending...';
            }, 10);
        }
        return true;
    }

    function prepareInvoice(appId) {
        const select = document.getElementById('job_application_id');
        if(select) {
            select.value = appId;
            select.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Highlight the form momentarily
            const formContainer = select.closest('.bg-gray-50');
            if(formContainer) {
                formContainer.classList.add('ring-2', 'ring-indigo-500', 'transition-all', 'duration-500');
                setTimeout(() => formContainer.classList.remove('ring-2', 'ring-indigo-500'), 1500);
            }

            // Focus on amount
            const amountInput = document.querySelector('input[name="amount"]');
            if(amountInput) {
                amountInput.focus();
            }
        }
    }

    function openEditInvoiceModal(id, amount, lateFee, dueDate, status) {
        const modal = document.getElementById('editInvoiceModal');
        const form = document.getElementById('editInvoiceForm');
        
        form.action = `/admin/crm/invoice/${id}/edit-details`;
        document.getElementById('edit_invoice_amount').value = amount;
        document.getElementById('edit_invoice_late_fee').value = lateFee;
        document.getElementById('edit_invoice_due_date').value = dueDate;
        document.getElementById('edit_invoice_status').value = status;
        
        modal.classList.remove('hidden');
    }

    function closeEditInvoiceModal() {
        document.getElementById('editInvoiceModal').classList.add('hidden');
    }

    function handleManualPaymentSubmit(form) {
        if (form.dataset.submitted === 'true') {
            return false;
        }
        form.dataset.submitted = 'true';
        const btn = document.getElementById('manualPaymentSubmitBtn') || form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Processing...';
        }
        return true;
    }

    function openTempPasswordModal() {
        document.getElementById('tempPasswordModal').classList.remove('hidden');
    }

    function closeTempPasswordModal() {
        document.getElementById('tempPasswordModal').classList.add('hidden');
    }

    function toggleTempPasswordMode() {
        const selected = document.querySelector('#tempPasswordModal input[name="mode"]:checked')?.value || 'auto';
        const group = document.getElementById('manualTempPasswordGroup');
        const input = document.getElementById('manual_temp_password');
        if (selected === 'manual') {
            group.classList.remove('hidden');
            input.required = true;
            input.focus();
        } else {
            group.classList.add('hidden');
            input.required = false;
            input.value = '';
        }
    }

    function copyIssuedTempPassword(btn) {
        const el = document.getElementById('issuedTempPasswordText');
        if (!el) return;
        const text = el.textContent.trim();
        if (!navigator.clipboard) {
            window.prompt('Copy temporary password:', text);
            return;
        }
        navigator.clipboard.writeText(text).then(function () {
            const label = btn.querySelector('span');
            const prev = label.textContent;
            label.textContent = 'Copied!';
            setTimeout(() => { label.textContent = prev; }, 1600);
        });
    }
</script>
@endpush
