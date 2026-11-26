@extends('layouts.admin')

@section('title', 'CRM & Follow-ups')
@section('subtitle', 'Manage candidates, track hiring status, generate invoices, and log follow-ups.')

@section('actions')
    <a href="{{ route('admin.crm.create') }}" class="px-5 py-2.5 bg-blue-600 text-white hover:bg-blue-700 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 shadow-sm">
        <i class="fas fa-user-plus text-xs"></i>
        <span>Manually Onboard Candidate</span>
    </a>
@endsection

@section('content')

@if(!empty($lastBulkBatch))
<div id="crmBatchStatusBanner" class="mb-5 bg-card-bg border border-accent-blue/40 rounded-2xl p-4 shadow-sm flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-accent-blue/15 text-accent-blue flex items-center justify-center">
            <i class="fas {{ ($lastBulkBatch['status'] ?? '') === 'completed' ? 'fa-check-circle text-emerald-500' : 'fa-layer-group' }}"></i>
        </div>
        <div class="text-xs">
            <div class="font-bold text-text-main flex items-center gap-2 flex-wrap">
                <span>Background Bulk Notification Queue</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold {{ ($lastBulkBatch['status'] ?? '') === 'completed' ? 'bg-emerald-500/15 text-emerald-500' : 'bg-blue-500/15 text-accent-blue' }}">
                    {{ ucfirst($lastBulkBatch['status'] ?? 'queued') }}
                </span>
            </div>
            <p class="text-text-dark/70 mt-0.5">
                Target: <strong>{{ number_format((int) ($lastBulkBatch['total'] ?? 0)) }}</strong> candidate(s) ·
                Batches: <strong>{{ (int) ($lastBulkBatch['chunks_done'] ?? 0) }}/{{ (int) ($lastBulkBatch['total_chunks'] ?? 0) }}</strong> ·
                Sent: <strong class="text-emerald-500">{{ (int) ($lastBulkBatch['sent'] ?? 0) }}</strong>
                @if(($lastBulkBatch['skipped'] ?? 0) > 0)
                    · Skipped: <strong>{{ (int) $lastBulkBatch['skipped'] }}</strong>
                @endif
                @if(($lastBulkBatch['failed'] ?? 0) > 0)
                    · Failed: <strong class="text-red-500">{{ (int) $lastBulkBatch['failed'] }}</strong>
                @endif
            </p>
        </div>
    </div>
    <a href="{{ request()->fullUrl() }}" class="px-3 py-1.5 rounded-lg bg-secondary-bg border border-card-border text-xs font-bold text-text-main hover:border-accent-blue transition-colors">
        <i class="fas fa-sync-alt mr-1"></i> Refresh Queue Progress
    </a>
</div>
@endif

{{-- Analytics Cards --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <a href="{{ request()->fullUrlWithQuery(['status' => null, 'page' => null]) }}" class="bg-card-bg border {{ request('status') === null ? 'border-blue-500 shadow-md ring-1 ring-blue-500' : 'border-card-border' }} rounded-xl p-4 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group hover:border-blue-500 transition-all">
        <div class="absolute inset-0 bg-blue-500/5 group-hover:bg-blue-500/10 transition-colors"></div>
        <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider mb-1 relative z-10">Total Candidates</p>
        <h4 class="text-2xl font-extrabold text-blue-500 relative z-10">{{ $stats['total'] }}</h4>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'active_paid', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'active_paid' ? 'border-green-500 shadow-md ring-1 ring-green-500' : 'border-card-border' }} rounded-xl p-4 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group hover:border-green-500 transition-all">
        <div class="absolute inset-0 bg-green-500/5 group-hover:bg-green-500/10 transition-colors"></div>
        <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider mb-1 relative z-10">Active / Paid</p>
        <h4 class="text-2xl font-extrabold text-green-500 relative z-10">{{ $stats['active_paid'] }}</h4>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending_dues', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'pending_dues' ? 'border-rose-500 shadow-md ring-1 ring-rose-500' : 'border-card-border' }} rounded-xl p-4 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group hover:border-rose-500 transition-all">
        <div class="absolute inset-0 bg-rose-500/5 group-hover:bg-rose-500/10 transition-colors"></div>
        <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider mb-1 relative z-10">Pending Dues</p>
        <h4 class="text-2xl font-extrabold text-rose-500 relative z-10">{{ $stats['pending_dues'] }}</h4>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'signed', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'signed' ? 'border-accent-blue shadow-md ring-1 ring-accent-blue' : 'border-card-border' }} rounded-xl p-4 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group hover:border-accent-blue transition-all">
        <div class="absolute inset-0 bg-accent-blue/5 group-hover:bg-accent-blue/10 transition-colors"></div>
        <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider mb-1 relative z-10">Signed Agreement</p>
        <h4 class="text-2xl font-extrabold text-accent-blue relative z-10">{{ $stats['signed'] }}</h4>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'incomplete', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'incomplete' ? 'border-red-500 shadow-md ring-1 ring-red-500' : 'border-card-border' }} rounded-xl p-4 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group hover:border-red-500 transition-all">
        <div class="absolute inset-0 bg-red-500/5 group-hover:bg-red-500/10 transition-colors"></div>
        <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider mb-1 relative z-10">Incomplete</p>
        <h4 class="text-2xl font-extrabold text-red-500 relative z-10">{{ $stats['incomplete'] }}</h4>
    </a>
</div>

{{-- Registration / Onboarding Status Filters --}}
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 mb-6">
    <a href="{{ request()->fullUrlWithQuery(['status' => 'completed', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'completed' ? 'border-emerald-500 shadow-md ring-1 ring-emerald-500' : 'border-card-border' }} rounded-xl px-4 py-3 shadow-sm flex items-center justify-between hover:border-emerald-500 transition-all">
        <div>
            <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider">Onboarding Complete</p>
            <h5 class="text-lg font-extrabold text-emerald-500">{{ $stats['onboarding_complete'] ?? 0 }}</h5>
        </div>
        <i class="fas fa-check-double text-emerald-500/60"></i>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'pending' ? 'border-rose-500 shadow-md ring-1 ring-rose-500' : 'border-card-border' }} rounded-xl px-4 py-3 shadow-sm flex items-center justify-between hover:border-rose-500 transition-all">
        <div>
            <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider">Onboarding Pending</p>
            <h5 class="text-lg font-extrabold text-rose-500">{{ $stats['onboarding_pending'] ?? 0 }}</h5>
        </div>
        <i class="fas fa-hourglass-half text-rose-500/60"></i>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending_agreement', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'pending_agreement' ? 'border-indigo-500 shadow-md ring-1 ring-indigo-500' : 'border-card-border' }} rounded-xl px-4 py-3 shadow-sm flex items-center justify-between hover:border-indigo-500 transition-all">
        <div>
            <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider">Pending Agreement</p>
            <h5 class="text-lg font-extrabold text-indigo-500">{{ $stats['pending_agreement'] ?? 0 }}</h5>
        </div>
        <i class="fas fa-file-signature text-indigo-500/60"></i>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending_fee', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'pending_fee' ? 'border-amber-500 shadow-md ring-1 ring-amber-500' : 'border-card-border' }} rounded-xl px-4 py-3 shadow-sm flex items-center justify-between hover:border-amber-500 transition-all">
        <div>
            <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider">Pending Fee</p>
            <h5 class="text-lg font-extrabold text-amber-500">{{ $stats['pending_fee'] ?? 0 }}</h5>
        </div>
        <i class="fas fa-rupee-sign text-amber-500/60"></i>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending_profile', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'pending_profile' ? 'border-orange-500 shadow-md ring-1 ring-orange-500' : 'border-card-border' }} rounded-xl px-4 py-3 shadow-sm flex items-center justify-between hover:border-orange-500 transition-all">
        <div>
            <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider">Pending Profile</p>
            <h5 class="text-lg font-extrabold text-orange-500">{{ $stats['pending_profile'] ?? 0 }}</h5>
        </div>
        <i class="fas fa-user-edit text-orange-500/60"></i>
    </a>
</div>

{{-- Filter/Search Bar & View Mode Wrapper --}}
<div x-data="{ viewMode: localStorage.getItem('crm_view_mode') || 'table' }">
<div class="bg-card-bg rounded-t-2xl border-x border-t border-card-border p-4">
    <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
        <div class="text-sm text-text-dark/50 font-medium flex flex-wrap items-center gap-2">
            <span>Showing {{ $candidates->firstItem() ?? 0 }} to {{ $candidates->lastItem() ?? 0 }} of {{ $candidates->total() }} entries</span>
            @if(request('status'))
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-accent-blue/10 text-accent-blue border border-accent-blue/20 inline-flex items-center gap-1.5">
                    <span>Filter: {{ str_replace('_', ' ', ucfirst(request('status'))) }}</span>
                    <a href="{{ route('admin.crm.index') }}" class="hover:text-red-500" title="Clear status filter"><i class="fas fa-times"></i></a>
                </span>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-secondary-bg border border-card-border rounded-xl p-1 flex items-center gap-1">
                <button type="button"
                        @click="viewMode = 'table'; localStorage.setItem('crm_view_mode', 'table')"
                        :class="viewMode === 'table' ? 'bg-accent-blue text-white shadow-sm' : 'text-text-dark/60 hover:text-text-main'"
                        class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all cursor-pointer inline-flex items-center gap-1">
                    <i class="fas fa-list"></i> Table
                </button>
                <button type="button"
                        @click="viewMode = 'cards'; localStorage.setItem('crm_view_mode', 'cards')"
                        :class="viewMode === 'cards' ? 'bg-accent-blue text-white shadow-sm' : 'text-text-dark/60 hover:text-text-main'"
                        class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all cursor-pointer inline-flex items-center gap-1">
                    <i class="fas fa-th-large"></i> Cards
                </button>
            </div>
            <button type="button" onclick="document.getElementById('advanced-filters').classList.toggle('hidden')" class="text-sm font-semibold text-accent-blue flex items-center gap-2 hover:text-accent-blue-hover transition-colors cursor-pointer">
                <i class="fas fa-filter"></i> Advanced Filters
            </button>
            @if($candidates->total() > 0)
                <button type="button" id="selectAllMatchingTopBtn" onclick="selectAllMatchingCandidates()"
                        class="px-3 py-1.5 rounded-xl bg-accent-blue/10 hover:bg-accent-blue text-accent-blue hover:text-white border border-accent-blue/30 text-xs font-bold transition-all cursor-pointer inline-flex items-center gap-1.5">
                    <i class="fas fa-check-double"></i>
                    <span>Select All Matching ({{ number_format($candidates->total()) }})</span>
                </button>
            @endif
        </div>
    </div>

    <form action="{{ route('admin.crm.index') }}" method="GET" class="space-y-4">
        @php
            $currentStatus = strtolower((string) request('status', ''));
            $statusAliasMap = [
                'incomplete_profile' => 'pending_profile',
                'unsigned_agreement' => 'pending_agreement',
                'unpaid_payment' => 'pending_fee',
            ];
            $normalizedStatus = $statusAliasMap[$currentStatus] ?? $currentStatus;
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-7 flex items-center relative">
                <i class="fas fa-search absolute left-3 text-text-dark/40 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone, candidate ID..." 
                       class="w-full pl-9 pr-28 py-2.5 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                @if(request()->anyFilled(['status', 'search', 'subject_id', 'experience', 'qualification_id', 'state_id', 'city_id', 'gender', 'english_fluency', 'availability', 'plan_amount']))
                    <a href="{{ route('admin.crm.index') }}" class="absolute right-3 text-text-dark/40 hover:text-red-400 transition-colors text-xs font-bold flex items-center gap-1">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                @endif
            </div>

            <div class="md:col-span-4">
                <select name="status" onchange="this.form.submit()" class="w-full bg-secondary-bg border border-card-border rounded-xl px-3 py-2.5 text-sm text-text-main font-medium focus:border-accent-blue focus:outline-none">
                    <option value="">All Candidate Statuses</option>
                    <option value="pending_profile" {{ $normalizedStatus === 'pending_profile' ? 'selected' : '' }}>Incomplete Profile Details</option>
                    <option value="pending_agreement" {{ $normalizedStatus === 'pending_agreement' ? 'selected' : '' }}>Unsigned Agreement</option>
                    <option value="pending_fee" {{ $normalizedStatus === 'pending_fee' ? 'selected' : '' }}>Unpaid Payment / Fee</option>
                    <option value="pending_dues" {{ $normalizedStatus === 'pending_dues' ? 'selected' : '' }}>Pending Service Charge Dues</option>
                    <option value="pending" {{ $normalizedStatus === 'pending' ? 'selected' : '' }}>Onboarding Pending (Any Missing Step)</option>
                    <option value="completed" {{ $normalizedStatus === 'completed' ? 'selected' : '' }}>Onboarding Complete</option>
                    <option value="active_paid" {{ $normalizedStatus === 'active_paid' ? 'selected' : '' }}>Active / Paid Plan</option>
                    <option value="signed" {{ $normalizedStatus === 'signed' ? 'selected' : '' }}>Signed Agreement Only (Unpaid Fee)</option>
                    <option value="incomplete" {{ $normalizedStatus === 'incomplete' ? 'selected' : '' }}>Incomplete (Not Signed or Paid)</option>
                    <option value="verified" {{ $normalizedStatus === 'verified' ? 'selected' : '' }}>Verified Profiles</option>
                    <option value="unverified" {{ $normalizedStatus === 'unverified' ? 'selected' : '' }}>Unverified Profiles</option>
                    <option value="has_resume" {{ $normalizedStatus === 'has_resume' ? 'selected' : '' }}>With Resume Uploaded</option>
                    <option value="missing_resume" {{ $normalizedStatus === 'missing_resume' ? 'selected' : '' }}>No Resume Uploaded</option>
                    <option value="active_account" {{ $normalizedStatus === 'active_account' ? 'selected' : '' }}>Active Accounts</option>
                    <option value="inactive_account" {{ $normalizedStatus === 'inactive_account' ? 'selected' : '' }}>Inactive / Deactivated Accounts</option>
                    <optgroup label="School Discovery Visibility">
                        <option value="school_visible" {{ $normalizedStatus === 'school_visible' ? 'selected' : '' }}>School Discovery: Visible ({{ $stats['school_visible'] ?? 0 }})</option>
                        <option value="school_hidden" {{ $normalizedStatus === 'school_hidden' ? 'selected' : '' }}>School Discovery: Hidden</option>
                        <option value="best_profile" {{ $normalizedStatus === 'best_profile' ? 'selected' : '' }}>Best Profiles for Schools ({{ $stats['best_profile'] ?? 0 }})</option>
                    </optgroup>
                </select>
            </div>

            <div class="md:col-span-1">
                <button type="submit" class="w-full h-full bg-accent-blue text-white rounded-xl px-3 py-2.5 text-sm font-bold shadow hover:bg-accent-blue-hover transition-colors cursor-pointer">
                    Go
                </button>
            </div>
        </div>

        <div id="advanced-filters" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 {{ request()->anyFilled(['subject_id', 'experience', 'qualification_id', 'state_id', 'city_id', 'gender', 'english_fluency', 'availability', 'plan_amount']) ? '' : 'hidden' }}">
            <select name="subject_id" class="w-full bg-secondary-bg border border-card-border rounded-lg px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">All Subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                @endforeach
            </select>

            <select name="qualification_id" class="w-full bg-secondary-bg border border-card-border rounded-lg px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">All Qualifications</option>
                @foreach($qualifications as $qualification)
                    <option value="{{ $qualification->id }}" {{ request('qualification_id') == $qualification->id ? 'selected' : '' }}>{{ $qualification->name }}</option>
                @endforeach
            </select>

            <select name="state_id" class="w-full bg-secondary-bg border border-card-border rounded-lg px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">All States</option>
                @foreach($states as $state)
                    <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                @endforeach
            </select>
            <select name="city_id" class="w-full bg-secondary-bg border border-card-border rounded-lg px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">All Cities</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                @endforeach
            </select>

            <select name="experience" class="w-full bg-secondary-bg border border-card-border rounded-lg px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">Min Experience</option>
                <option value="1" {{ request('experience') == '1' ? 'selected' : '' }}>1+ Years</option>
                <option value="3" {{ request('experience') == '3' ? 'selected' : '' }}>3+ Years</option>
                <option value="5" {{ request('experience') == '5' ? 'selected' : '' }}>5+ Years</option>
                <option value="10" {{ request('experience') == '10' ? 'selected' : '' }}>10+ Years</option>
            </select>

            <select name="gender" class="w-full bg-secondary-bg border border-card-border rounded-lg px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">All Genders</option>
                <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
            </select>

            <select name="english_fluency" class="w-full bg-secondary-bg border border-card-border rounded-lg px-3 py-2 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">English Fluency</option>
                <option value="beginner" {{ request('english_fluency') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                <option value="intermediate" {{ request('english_fluency') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                <option value="fluent" {{ request('english_fluency') == 'fluent' ? 'selected' : '' }}>Fluent</option>
            </select>

            <button type="submit" class="w-full bg-accent-blue text-white rounded-lg px-4 py-2 text-sm font-bold shadow hover:bg-accent-blue-hover transition-colors">
                Apply Filters
            </button>
        </div>
    </form>
</div>

{{-- Cross-Page Select All Matching Banner --}}
<div id="selectAllMatchingBanner" class="hidden bg-accent-blue/10 border-x border-b border-accent-blue/30 px-4 py-2.5 flex flex-wrap items-center justify-between gap-2 text-xs">
    <div id="selectAllMatchingBannerText" class="font-semibold text-text-main"></div>
    <div class="flex items-center gap-3">
        <button type="button" id="selectAllMatchingActionBtn" onclick="selectAllMatchingCandidates()"
                class="hidden font-bold text-accent-blue hover:underline cursor-pointer">
            Select all {{ number_format($candidates->total()) }} matching candidates (across all pages)
        </button>
        <button type="button" onclick="deselectAllCandidates()" class="font-semibold text-text-dark/70 hover:text-red-400 cursor-pointer">
            Clear selection
        </button>
    </div>
</div>

{{-- Candidate View Details Cards Grid --}}
<div x-show="viewMode === 'cards'" x-cloak class="bg-card-bg rounded-b-2xl border border-card-border p-5 shadow-xl">
    @if($candidates->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($candidates as $candidate)
                @php
                    $cp = $candidate->profile;
                    $cardPhotoPath = $cp?->profile_photo_path ?: ($cp?->live_photo_path ?: $cp?->passport_photo_path);
                    $cardPhotoUrl = $cardPhotoPath ? Storage::url($cardPhotoPath) : '';
                    $cardUploadUrl = route('admin.crm.candidate.photo', $candidate->id);
                    $isDone = $cp && $cp->isRegistrationCompleted();
                @endphp
                <div class="rounded-2xl border border-card-border bg-secondary-bg/40 p-5 flex flex-col justify-between gap-4 hover:border-accent-blue/50 transition-all shadow-sm">
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <input type="checkbox" value="{{ $candidate->id }}" data-candidate-id="{{ $candidate->id }}"
                                       class="candidate-checkbox rounded border-card-border text-accent-blue focus:ring-accent-blue cursor-pointer shrink-0"
                                       onchange="onCandidateCheckboxChange(this)">
                                <button type="button"
                                        onclick='openAdminPhotoModal(@json($candidate->name), @json($cp?->vpa_id ?? ""), @json($cardPhotoUrl), @json($cardUploadUrl), "Profile Photo")'
                                        class="relative group shrink-0 rounded-full focus:outline-none cursor-pointer"
                                        title="Click to enlarge or update photo">
                                    @if($cardPhotoUrl)
                                        <img src="{{ $cardPhotoUrl }}" alt="{{ $candidate->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-card-border group-hover:border-accent-blue transition-colors">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-accent-blue/15 text-accent-blue flex items-center justify-center font-extrabold text-base border border-accent-blue/30">
                                            {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full bg-accent-blue text-white text-[8px] flex items-center justify-center shadow">
                                        <i class="fas {{ $cardPhotoUrl ? 'fa-search-plus' : 'fa-camera' }}"></i>
                                    </span>
                                </button>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <h4 class="font-bold text-text-main truncate">{{ $candidate->name }}</h4>
                                        @if($cp?->is_verified)
                                            <i class="fas fa-check-circle text-blue-500 text-xs" title="Verified"></i>
                                        @endif
                                    </div>
                                    <div class="text-[11px] font-mono text-text-dark/60">{{ $cp?->vpa_id ?? 'No VPA ID' }}</div>
                                    @if($cp?->school_discovery_visible)
                                        <div class="mt-1 flex items-center gap-1 flex-wrap">
                                            <span class="px-1.5 py-0.5 rounded bg-indigo-500/15 text-indigo-400 border border-indigo-500/30 text-[9px] font-bold">School Visible</span>
                                            @if($cp?->is_best_profile)
                                                <span class="px-1.5 py-0.5 rounded bg-amber-500/15 text-amber-400 border border-amber-500/30 text-[9px] font-bold">Best</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold shrink-0 {{ $isDone ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-400 border border-rose-500/30' }}">
                                {{ $isDone ? 'Complete' : 'Pending' }}
                            </span>
                        </div>

                        <div class="mt-3 text-xs text-text-dark/70 space-y-1">
                            <div class="truncate"><i class="fas fa-envelope w-4 text-text-dark/40"></i> {{ $candidate->email }}</div>
                            <div><i class="fas fa-phone-alt w-4 text-text-dark/40"></i> {{ $candidate->phone }}</div>
                            <div><i class="fas fa-map-marker-alt w-4 text-text-dark/40"></i> {{ $cp?->preferredCity?->name ?? 'N/A' }}, {{ $cp?->preferredState?->name ?? 'N/A' }}</div>
                        </div>

                        <div class="mt-3 pt-3 border-t border-card-border/70 grid grid-cols-2 gap-2 text-xs">
                            <div><span class="text-[10px] text-text-dark/40 block uppercase font-bold">Category</span><span class="font-semibold text-text-main">{{ $cp?->category?->name ?? 'N/A' }}</span></div>
                            <div><span class="text-[10px] text-text-dark/40 block uppercase font-bold">Subject</span><span class="font-semibold text-text-main">{{ $cp?->subject?->name ?? 'N/A' }}</span></div>
                            <div><span class="text-[10px] text-text-dark/40 block uppercase font-bold">Qualification</span><span class="font-medium text-text-main">{{ $cp?->highestQualification?->name ?? 'N/A' }}</span></div>
                            <div><span class="text-[10px] text-text-dark/40 block uppercase font-bold">Exp / Plan</span><span class="font-medium text-text-main">{{ $cp?->experience_years ?? 0 }} yrs · {{ $cp?->plan_type ? ucfirst($cp->plan_type) : 'Unpaid' }}</span></div>
                        </div>
                        @if($candidate->latestFollowUp)
                            @php $cardFu = $candidate->latestFollowUp; @endphp
                            <div class="mt-2.5 px-2.5 py-1.5 rounded-lg text-[11px] flex items-center justify-between gap-2 {{ $cardFu->status === 'closed' ? 'bg-emerald-500/10 text-emerald-400' : ($cardFu->is_due ? 'bg-red-500/15 text-red-400' : 'bg-amber-500/10 text-amber-400') }}">
                                <span class="font-bold truncate"><i class="fas fa-headset mr-1"></i>{{ $cardFu->next_action ?: ucfirst($cardFu->status) }}</span>
                                @if($cardFu->scheduled_date_time)
                                    <span class="text-[10px] shrink-0">{{ $cardFu->follow_up_at ? $cardFu->follow_up_at->copy()->timezone('Asia/Kolkata')->format('M d, h:i A') : $cardFu->follow_up_date->format('M d') }}</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-card-border/70 flex items-center justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-1.5">
                            @if($cp?->resume_path)
                                <a href="{{ Storage::url($cp->resume_path) }}" target="_blank" class="px-2 py-1 rounded-lg bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 text-[11px] font-bold">CV</a>
                            @endif
                            <button type="button"
                                    onclick='openAdminPhotoModal(@json($candidate->name), @json($cp?->vpa_id ?? ""), @json($cardPhotoUrl), @json($cardUploadUrl), "Profile Photo")'
                                    class="px-2 py-1 rounded-lg bg-amber-500/10 text-amber-400 hover:bg-amber-500/20 text-[11px] font-bold cursor-pointer">
                                Photo
                            </button>
                        </div>
                        <a href="{{ route('admin.crm.show', $candidate->id) }}"
                           class="px-3.5 py-1.5 rounded-xl bg-accent-blue text-white hover:bg-accent-blue-hover text-xs font-bold inline-flex items-center gap-1.5 shadow-sm transition-colors">
                            <span>View Details</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="py-12 text-center text-text-dark/50 text-sm">No candidates found.</div>
    @endif
</div>

{{-- Data Table --}}
<div x-show="viewMode === 'table'" class="bg-card-bg rounded-b-2xl border border-card-border overflow-x-auto shadow-xl">
    <table class="w-full text-left border-collapse admin-table">
        <thead>
            <tr>
                <th class="w-10 text-center">
                    <input type="checkbox" id="selectAllCandidates" onclick="toggleSelectAllCandidates(this)" class="rounded border-card-border text-accent-blue focus:ring-accent-blue cursor-pointer" title="Select All Candidates">
                </th>
                @php
                    $route = 'admin.crm.index';
                    $order = request('order') === 'asc' ? 'desc' : 'asc';
                @endphp
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'name', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Candidate
                        @if(request('sort_by') === 'name')
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th>Registration Status</th>
                <th>Hired Roles</th>
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'created_at', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Joined
                        @if(request('sort_by') === 'created_at' || !request('sort_by'))
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th>Admin Rating</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-card-border">
            @forelse($candidates as $candidate)
            <tr class="group">
                <td class="text-center">
                    <input type="checkbox" value="{{ $candidate->id }}" data-candidate-id="{{ $candidate->id }}"
                           class="candidate-checkbox rounded border-card-border text-accent-blue focus:ring-accent-blue cursor-pointer"
                           onchange="onCandidateCheckboxChange(this)">
                </td>
                <td>
                    @php
                        $rowPhotoPath = $candidate->profile?->profile_photo_path ?: ($candidate->profile?->live_photo_path ?: $candidate->profile?->passport_photo_path);
                        $rowPhotoUrl = $rowPhotoPath ? Storage::url($rowPhotoPath) : '';
                        $rowUploadUrl = route('admin.crm.candidate.photo', $candidate->id);
                    @endphp
                    <div class="flex items-start gap-3">
                        <button type="button"
                                onclick='openAdminPhotoModal(@json($candidate->name), @json($candidate->profile?->vpa_id ?? ""), @json($rowPhotoUrl), @json($rowUploadUrl), "Profile Photo")'
                                class="relative group shrink-0 rounded-full focus:outline-none cursor-pointer mt-0.5"
                                title="Click to enlarge or update photo">
                            @if($rowPhotoUrl)
                                <img src="{{ $rowPhotoUrl }}" alt="{{ $candidate->name }}" class="w-10 h-10 rounded-full object-cover border border-card-border group-hover:border-accent-blue transition-colors">
                            @else
                                <div class="w-10 h-10 rounded-full bg-accent-blue/10 text-accent-blue flex items-center justify-center text-sm font-bold border border-accent-blue/20">
                                    {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full bg-accent-blue text-white text-[8px] flex items-center justify-center shadow">
                                <i class="fas {{ $rowPhotoUrl ? 'fa-search-plus' : 'fa-camera' }}"></i>
                            </span>
                        </button>
                        <div class="min-w-0">
                            <div class="font-semibold text-text-main group-hover:text-accent-blue transition-colors flex items-center gap-1.5 flex-wrap">
                                <span>{{ $candidate->name }}</span>
                                @if($candidate->profile?->is_verified)
                                    <i class="fas fa-check-circle text-blue-500 text-xs" title="Verified"></i>
                                @endif
                            </div>
                            @if($candidate->profile && $candidate->profile->vpa_id)
                                <div class="text-[10px] font-mono text-text-dark/60 bg-secondary-bg px-1.5 py-0.5 rounded border border-card-border w-max mt-0.5" title="Vedanta Candidate ID">
                                    <i class="fas fa-id-badge w-3"></i> {{ $candidate->profile->vpa_id }}
                                </div>
                            @endif
                            <div class="text-xs text-text-dark/50 flex flex-col gap-0.5 mt-1">
                                <span><i class="fas fa-envelope text-[10px] w-3"></i> {{ $candidate->email }}</span>
                                <span><i class="fas fa-phone-alt text-[10px] w-3"></i> {{ $candidate->phone }}</span>
                            </div>
                            @if($candidate->profile)
                                <div class="flex flex-wrap gap-1 mt-1.5 text-[10px]">
                                    @if($candidate->profile->category?->name)
                                        <span class="px-1.5 py-0.5 rounded bg-blue-500/10 text-accent-blue font-semibold">{{ $candidate->profile->category->name }}</span>
                                    @endif
                                    @if($candidate->profile->subject?->name)
                                        <span class="px-1.5 py-0.5 rounded bg-purple-500/10 text-purple-400 font-semibold">{{ $candidate->profile->subject->name }}</span>
                                    @endif
                                    @if($candidate->profile->highestQualification?->name)
                                        <span class="px-1.5 py-0.5 rounded bg-orange-500/10 text-orange-400 font-semibold">{{ $candidate->profile->highestQualification->name }}</span>
                                    @endif
                                    <span class="px-1.5 py-0.5 rounded bg-secondary-bg text-text-dark/60 border border-card-border font-medium">{{ $candidate->profile->experience_years ?? 0 }} yrs</span>
                                    @if($candidate->profile->preferredCity?->name || $candidate->profile->preferredState?->name)
                                        <span class="px-1.5 py-0.5 rounded bg-secondary-bg text-text-dark/60 border border-card-border font-medium">
                                            {{ $candidate->profile->preferredCity?->name ?? 'N/A' }}, {{ $candidate->profile->preferredState?->name ?? '' }}
                                        </span>
                                    @endif
                                    @if($candidate->profile->school_discovery_visible)
                                        <span class="px-1.5 py-0.5 rounded bg-indigo-500/15 text-indigo-400 border border-indigo-500/30 font-bold">School Visible</span>
                                    @endif
                                    @if($candidate->profile->is_best_profile)
                                        <span class="px-1.5 py-0.5 rounded bg-amber-500/15 text-amber-400 border border-amber-500/30 font-bold">Best Profile</span>
                                    @endif
                                </div>
                            @endif
                            @if($candidate->latestFollowUp)
                                @php $lfu = $candidate->latestFollowUp; @endphp
                                <div class="mt-1.5 inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold {{ $lfu->status === 'closed' ? 'bg-emerald-500/10 text-emerald-400' : ($lfu->is_due ? 'bg-red-500/15 text-red-400' : 'bg-amber-500/15 text-amber-400') }}">
                                    <i class="fas fa-headset"></i>
                                    <span>{{ $lfu->next_action ?: ($lfu->status === 'closed' ? 'Follow-up Closed' : 'Follow-up Open') }}</span>
                                    @if($lfu->scheduled_date_time)
                                        <span>· {{ $lfu->follow_up_at ? $lfu->follow_up_at->copy()->timezone('Asia/Kolkata')->format('M d, h:i A') : $lfu->follow_up_date->format('M d') }}</span>
                                    @endif
                                </div>
                            @endif
                            @if($candidate->profile && $candidate->profile->plan_type === 'standard' && !$candidate->profile->is_fee_paid)
                                <div class="mt-2">
                                    <span class="bg-red-500/10 text-red-500 px-2 py-0.5 rounded flex items-center gap-1 text-[10px] font-bold w-max" title="Standard Plan Placement Fee Pending">
                                        <i class="fas fa-exclamation-triangle"></i> ₹500 Due
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    @php
                        $cp = $candidate->profile;
                        $hasDetails = $cp && $cp->hasCompletedProfileDetails();
                        $hasFee = $cp && $cp->has_paid_plan;
                        $hasAgr = $cp && ($cp->is_agreement_signed || !empty($cp->signature_date_time) || !empty($cp->agreement_signed_at) || !empty($cp->agreement_pdf_path) || $cp->is_manual_agreement);
                        $isOnboardingDone = $cp && $cp->isRegistrationCompleted();
                    @endphp
                    @if($isOnboardingDone)
                        <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-green-500/20 uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-check-circle"></i> Active / Paid
                        </span>
                        <div class="mt-1 text-[10px] text-emerald-500 font-bold">
                            Onboarding Complete
                        </div>
                    @elseif($hasFee && !$hasDetails)
                        <span class="bg-amber-500/10 text-amber-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-amber-500/20 uppercase tracking-wider flex items-center gap-1 w-max" title="Fee paid but profile details are incomplete">
                            <i class="fas fa-exclamation-triangle"></i> Paid / Incomplete
                        </span>
                        <div class="mt-1 text-[10px] text-red-400 font-semibold max-w-[165px] leading-tight">
                            Onboarding Pending · Profile Details
                        </div>
                    @elseif($hasAgr && $hasDetails)
                        <span class="bg-accent-blue/10 text-accent-blue px-2.5 py-1 rounded-lg text-[10px] font-bold border border-accent-blue/20 uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-signature"></i> Signed
                        </span>
                        <div class="mt-1 text-[10px] text-amber-400 font-semibold max-w-[165px] leading-tight">
                            Onboarding Pending · Registration Fee
                        </div>
                    @else
                        <span class="bg-red-500/10 text-red-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-red-500/20 uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-exclamation-circle"></i> Incomplete
                        </span>
                        <div class="mt-1 text-[10px] text-red-400 font-semibold max-w-[165px] leading-tight">
                            Onboarding Pending · {{ $cp ? $cp->pending_reason : 'Pending Profile Completion' }}
                        </div>
                    @endif
                    <div class="mt-1 flex items-center gap-1.5 flex-wrap text-[9px] font-bold">
                        <span class="px-1.5 py-0.5 rounded {{ $hasAgr ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-400' }}">Agr {{ $hasAgr ? '✓' : '✗' }}</span>
                        <span class="px-1.5 py-0.5 rounded {{ $hasFee ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-400' }}">Fee {{ $hasFee ? '✓' : '✗' }}</span>
                        <span class="px-1.5 py-0.5 rounded {{ $hasDetails ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-400' }}">Profile {{ $hasDetails ? '✓' : '✗' }}</span>
                    </div>
                    @if($candidate->profile)
                        <div class="mt-1.5 flex items-center gap-1.5 flex-wrap">
                            @if($candidate->profile->is_agreement_signed || $candidate->profile->agreement_pdf_path || $candidate->profile->signature_data || $candidate->profile->is_fee_paid)
                                <a href="{{ route('admin.crm.candidate.download-agreement', $candidate->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 bg-teal-500/10 text-teal-500 hover:bg-teal-500/20 rounded text-[10px] font-bold border border-teal-500/20 transition-colors w-max" title="Download Signed Agreement (Auto-generates if missing)">
                                    <i class="fas fa-file-download"></i> Agreement PDF
                                </a>
                            @else
                                <a href="{{ route('admin.crm.candidate.download-agreement', ['id' => $candidate->id, 'regenerate' => 1]) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-500/10 text-indigo-500 hover:bg-indigo-500/20 rounded text-[10px] font-bold border border-indigo-500/20 transition-colors w-max" title="Generate Agreement PDF on-the-fly">
                                    <i class="fas fa-file-pdf"></i> Generate PDF
                                </a>
                            @endif

                            <form action="{{ Route::has('admin.crm.candidate.send-agreement-link') ? route('admin.crm.candidate.send-agreement-link', $candidate->id) : url('/admin/crm/candidate/'.$candidate->id.'/send-agreement-link') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-500/10 text-accent-blue hover:bg-accent-blue hover:text-white rounded text-[10px] font-bold border border-accent-blue/20 transition-colors w-max" title="Send agreement email link to candidate" onclick="return confirm('Send agreement link email to {{ addslashes($candidate->name) }}?')">
                                    <i class="fas fa-envelope"></i> Send Link
                                </button>
                            </form>
                        </div>
                    @endif
                </td>
                <td>
                    @php
                        $hired = $candidate->applications->where('status', 'hired');
                    @endphp
                    @if($hired->count() > 0)
                        <span class="text-green-400 font-bold bg-green-500/10 px-2.5 py-1 rounded-lg text-xs">{{ $hired->count() }} Role(s)</span>
                    @else
                        <span class="text-text-dark/30 text-xs font-semibold">None</span>
                    @endif
                </td>
                <td class="text-text-dark/60 text-sm leading-tight">
                    {{ $candidate->created_at->format('M d, Y') }}<br>
                    <span class="text-[10px] text-text-dark/40">{{ $candidate->created_at->format('h:i A') }}</span>
                </td>
                <td>
                    @if($candidate->rating)
                        <div class="flex items-center gap-1 cursor-pointer hover:opacity-80 transition-opacity" onclick="openRatingModal({{ $candidate->id }}, {{ $candidate->rating->communication }}, {{ $candidate->rating->subject_knowledge }}, {{ $candidate->rating->demo_performance }}, {{ $candidate->rating->english_fluency }}, {{ $candidate->rating->discipline }}, '{{ addslashes($candidate->rating->remarks ?? '') }}')">
                            <span class="bg-yellow-500/10 text-yellow-500 text-xs font-bold px-2 py-1 rounded border border-yellow-500/20">
                                <i class="fas fa-star text-yellow-500 mr-1"></i> {{ number_format($candidate->rating->overall_rating, 1) }}
                            </span>
                        </div>
                    @else
                        <button type="button" onclick="openRatingModal({{ $candidate->id }}, 3, 3, 3, 3, 3, '')" class="text-[10px] uppercase font-bold text-text-dark/50 hover:text-accent-blue transition-colors px-2 py-1 border border-dashed border-card-border rounded">
                            <i class="far fa-star"></i> Rate
                        </button>
                    @endif
                </td>
                <td>
                    <div class="flex items-center justify-end gap-2">
                        @if(!$candidate->profile)
                            <form action="{{ route('admin.crm.candidate.remind', $candidate->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Send an email reminder to candidate about: Pending Profile Completion?')" class="px-2.5 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white border border-indigo-100 hover:border-indigo-600 text-xs font-semibold transition-colors flex items-center gap-1" title="Send Reminder for: Pending Profile Completion">
                                    <i class="fas fa-bell"></i>
                                </button>
                            </form>
                        @elseif($candidate->profile->pending_reason !== 'Completed')
                            <form action="{{ route('admin.crm.candidate.remind', $candidate->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Send an email reminder to candidate about: {{ $candidate->profile->pending_reason }}?')" class="px-2.5 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white border border-indigo-100 hover:border-indigo-600 text-xs font-semibold transition-colors flex items-center gap-1" title="Send Reminder for: {{ $candidate->profile->pending_reason }}">
                                    <i class="fas fa-bell"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ Route::has('admin.crm.candidate.send-agreement-link') ? route('admin.crm.candidate.send-agreement-link', $candidate->id) : url('/admin/crm/candidate/'.$candidate->id.'/send-agreement-link') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Send agreement email link to {{ addslashes($candidate->name) }}?')" class="px-2.5 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white border border-blue-100 hover:border-blue-600 text-xs font-semibold transition-colors flex items-center gap-1" title="Send Agreement Email Link to {{ $candidate->name }}">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.crm.show', $candidate->id) }}" class="px-3 py-1.5 rounded-lg bg-accent-blue/10 text-accent-blue hover:bg-accent-blue hover:text-white text-xs font-semibold transition-colors flex items-center gap-1 whitespace-nowrap">
                            View Details <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-16 text-center">
                    <div class="w-16 h-16 bg-secondary-bg rounded-2xl flex items-center justify-center text-text-dark/20 text-3xl mx-auto mb-4 border border-card-border">
                        <i class="fas fa-users-slash"></i>
                    </div>
                    <p class="text-text-main font-bold text-lg mb-1">No candidates found</p>
                    <p class="text-text-dark/40 text-sm">Try adjusting your search criteria.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($candidates->hasPages())
<div class="mt-6 flex justify-end">
    {{ $candidates->links('pagination::tailwind') }}
</div>
@endif
</div>

@include('admin.crm.partials.photo-modal')

{{-- Rating Modal --}}
<div id="ratingModal" class="fixed inset-0 z-[105] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="document.getElementById('ratingModal').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-md bg-card-bg rounded-2xl shadow-2xl overflow-hidden animate-[fadeIn_0.3s_ease-out]">
        <div class="p-6 border-b border-card-border flex justify-between items-center">
            <h3 class="text-xl font-bold text-text-main">Admin Rating</h3>
            <button type="button" onclick="document.getElementById('ratingModal').classList.add('hidden')" class="text-text-dark hover:text-red-500 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="ratingForm" action="" method="POST" class="p-6 space-y-4">
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
                <label class="text-sm font-medium text-text-main">{{ $label }}</label>
                <select name="{{ $key }}" id="rating_{{ $key }}" class="rounded-lg bg-secondary-bg border-card-border text-text-main focus:border-accent-blue focus:ring-0 text-sm p-1.5 w-24">
                    @for($i=1; $i<=5; $i++)
                        <option value="{{ $i }}">{{ $i }} Stars</option>
                    @endfor
                </select>
            </div>
            @endforeach

            <div class="pt-2">
                <label class="block text-xs font-semibold text-text-dark mb-1">Remarks</label>
                <textarea name="remarks" id="rating_remarks" rows="2" class="w-full rounded-lg bg-secondary-bg border-card-border text-text-main focus:border-accent-blue focus:ring-0 text-sm placeholder-text-dark/40"></textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('ratingModal').classList.add('hidden')" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-text-main bg-secondary-bg hover:bg-card-border transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-accent-blue hover:bg-accent-blue-hover transition-colors shadow-glow-blue">
                    Save Ratings
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Floating Bulk Action Bar -->
<div id="bulkActionBar" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden bg-card-bg border border-accent-blue/50 rounded-2xl shadow-2xl p-4 flex items-center gap-3 flex-wrap backdrop-blur-xl animate-bounce-short">
    <div class="flex items-center gap-2 text-sm font-bold text-text-main">
        <span class="px-2.5 h-7 rounded-full bg-accent-blue text-white flex items-center justify-center text-xs" id="selectedCount">0</span>
        <span id="selectedCountLabel">Candidates Selected</span>
    </div>
    <div class="h-6 w-px bg-card-border"></div>
    <button type="button" onclick="openBulkNotificationModal()" class="px-4 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white text-xs font-bold rounded-xl transition-all flex items-center gap-2 shadow-md cursor-pointer">
        <i class="fas fa-paper-plane"></i> Send Bulk Notification / Email
    </button>
    @if(\Illuminate\Support\Facades\Schema::hasColumn('candidate_profiles', 'school_discovery_visible'))
        <button type="button" onclick="openBulkSchoolDiscoveryModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all flex items-center gap-2 shadow-md cursor-pointer">
            <i class="fas fa-school"></i> School Visibility
        </button>
    @endif
    <button type="button" onclick="deselectAllCandidates()" class="text-xs text-text-dark/50 hover:text-red-400 font-semibold px-2 cursor-pointer">
        Cancel
    </button>
</div>

<!-- Bulk Notification Modal -->
<div id="bulkNotificationModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-card-bg border border-card-border rounded-2xl max-w-lg w-full p-6 shadow-2xl relative">
        <button type="button" onclick="closeBulkNotificationModal()" class="absolute top-4 right-4 text-text-dark/50 hover:text-text-main">
            <i class="fas fa-times text-lg"></i>
        </button>
        
        <h3 class="text-lg font-bold text-text-main mb-1 flex items-center gap-2">
            <i class="fas fa-bullhorn text-accent-blue"></i> Send Bulk Notification
        </h3>
        <p class="text-xs text-text-dark/60 mb-1">
            Queue email notifications for <span id="modalSelectedCount" class="font-bold text-accent-blue">0</span> selected candidate(s).
        </p>
        <p class="text-[11px] text-emerald-500 font-medium mb-4">
            <i class="fas fa-layer-group mr-1"></i> Processed asynchronously in background queue batches of 50 (supports 2,000+ records cleanly).
        </p>
        
        <form action="{{ route('admin.crm.candidate.bulk-remind') }}" method="POST" id="bulkNotificationForm">
            @csrf
            <input type="hidden" name="select_all_matching" id="bulkSelectAllMatchingInput" value="0">
            <input type="hidden" name="excluded_ids_csv" id="bulkExcludedCsvInput" value="">
            @foreach(['status', 'search', 'subject_id', 'experience', 'qualification_id', 'state_id', 'city_id', 'gender', 'english_fluency', 'availability', 'salary', 'plan_amount'] as $filterKey)
                @if(request()->filled($filterKey))
                    <input type="hidden" name="{{ $filterKey }}" value="{{ request($filterKey) }}">
                @endif
            @endforeach
            <!-- Hidden input containers for selected candidate IDs (manual selection mode) -->
            <div id="bulkCandidateInputs"></div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wider mb-2">Notification Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 p-3 bg-secondary-bg rounded-xl border border-card-border cursor-pointer hover:border-accent-blue transition-all">
                            <input type="radio" name="notification_type" value="status_reminder" checked onclick="toggleNotificationFields('status_reminder')" class="text-accent-blue focus:ring-accent-blue">
                            <div class="text-xs">
                                <div class="font-bold text-text-main">Status Reminders</div>
                                <div class="text-[10px] text-text-dark/50">Auto-send pending step email</div>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 p-3 bg-secondary-bg rounded-xl border border-card-border cursor-pointer hover:border-accent-blue transition-all">
                            <input type="radio" name="notification_type" value="custom_email" onclick="toggleNotificationFields('custom_email')" class="text-accent-blue focus:ring-accent-blue">
                            <div class="text-xs">
                                <div class="font-bold text-text-main">Custom Email</div>
                                <div class="text-[10px] text-text-dark/50">Write custom email content</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="customEmailFields" class="hidden space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-text-dark/70 mb-1">Email Subject <span class="text-red-500">*</span></label>
                        <input type="text" name="custom_subject" placeholder="E.g., Important Update Regarding Your Vedanta Profile" class="w-full bg-secondary-bg border border-card-border rounded-xl px-3 py-2 text-xs text-text-main focus:outline-none focus:border-accent-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-text-dark/70 mb-1">Email Message <span class="text-red-500">*</span></label>
                        <textarea name="custom_message" rows="4" placeholder="Type your custom notification message here... (Optional placeholders: {name}, {email}, {phone})" class="w-full bg-secondary-bg border border-card-border rounded-xl p-3 text-xs text-text-main focus:outline-none focus:border-accent-blue"></textarea>
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" onclick="closeBulkNotificationModal()" class="px-4 py-2 bg-secondary-bg text-text-dark/70 text-xs font-bold rounded-xl hover:bg-card-border transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-accent-blue text-white text-xs font-bold rounded-xl hover:bg-accent-blue-hover shadow-md flex items-center gap-2 transition-all cursor-pointer">
                        <i class="fas fa-paper-plane"></i> Queue Notifications
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bulk School Discovery Modal -->
<div id="bulkSchoolDiscoveryModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-card-bg border border-card-border rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
        <button type="button" onclick="closeBulkSchoolDiscoveryModal()" class="absolute top-4 right-4 text-text-dark/50 hover:text-text-main cursor-pointer">
            <i class="fas fa-times text-lg"></i>
        </button>

        <h3 class="text-lg font-bold text-text-main mb-1 flex items-center gap-2">
            <i class="fas fa-school text-indigo-500"></i> Bulk School Discovery Visibility
        </h3>
        <p class="text-xs text-text-dark/60 mb-4">
            Update School Search visibility for <span id="discoveryModalSelectedCount" class="font-bold text-indigo-500">0</span> selected candidate(s).
        </p>

        <form action="{{ route('admin.crm.candidate.bulk-school-discovery') }}" method="POST" id="bulkSchoolDiscoveryForm" class="space-y-4">
            @csrf
            <input type="hidden" name="select_all_matching" id="bulkDiscoverySelectAllMatching" value="0">
            <input type="hidden" name="excluded_ids_csv" id="bulkDiscoveryExcludedCsv" value="">
            @foreach(['status', 'search', 'subject_id', 'experience', 'qualification_id', 'state_id', 'city_id', 'gender', 'english_fluency', 'availability', 'salary', 'plan_amount'] as $filterKey)
                @if(request()->filled($filterKey))
                    <input type="hidden" name="{{ $filterKey }}" value="{{ request($filterKey) }}">
                @endif
            @endforeach
            <div id="bulkDiscoveryCandidateInputs"></div>

            <div>
                <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wider mb-1.5">Action</label>
                <select name="discovery_action" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-3 py-2.5 text-sm text-text-main">
                    <option value="show">Make Visible in School Search</option>
                    <option value="mark_best">Make Visible + Mark as Best Profile</option>
                    <option value="hide">Hide from School Search</option>
                    <option value="unmark_best">Remove Best Profile Flag</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wider mb-1.5">Identifier Mode (Optional Override)</label>
                <select name="school_display_mode" class="w-full bg-secondary-bg border border-card-border rounded-xl px-3 py-2.5 text-sm text-text-main">
                    <option value="">Keep Existing Mode</option>
                    <option value="vpa_id">VPA ID Only (Most Private)</option>
                    <option value="masked_name">Masked Name + VPA ID</option>
                    <option value="full_name">Full Name</option>
                </select>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeBulkSchoolDiscoveryModal()" class="px-4 py-2 bg-secondary-bg text-text-dark/70 text-xs font-bold rounded-xl cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl cursor-pointer shadow-sm">
                    Apply Visibility
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openRatingModal(candidateId, comm, subj, demo, eng, disc, rem) {
        const form = document.getElementById('ratingForm');
        form.action = `/admin/crm/candidate/${candidateId}/rate`;

        document.getElementById('rating_communication').value = comm;
        document.getElementById('rating_subject_knowledge').value = subj;
        document.getElementById('rating_demo_performance').value = demo;
        document.getElementById('rating_english_fluency').value = eng;
        document.getElementById('rating_discipline').value = disc;
        document.getElementById('rating_remarks').value = rem;

        document.getElementById('ratingModal').classList.remove('hidden');
    }

    const totalMatchingCandidates = {{ (int) $candidates->total() }};
    const visiblePageCount = {{ (int) $candidates->count() }};
    let selectAllMatchingMode = false;
    let excludedCandidateIds = new Set();

    function getUniquePageIds() {
        const ids = new Set();
        document.querySelectorAll('.candidate-checkbox').forEach(cb => ids.add(String(cb.value)));
        return ids;
    }

    function getSelectedPageIds() {
        const ids = new Set();
        document.querySelectorAll('.candidate-checkbox:checked').forEach(cb => ids.add(String(cb.value)));
        return ids;
    }

    function syncCandidateCheckboxes(candidateId, checked) {
        document.querySelectorAll(`.candidate-checkbox[data-candidate-id="${candidateId}"]`).forEach(cb => {
            cb.checked = checked;
        });
    }

    function onCandidateCheckboxChange(cb) {
        const id = String(cb.value);
        syncCandidateCheckboxes(id, cb.checked);

        if (selectAllMatchingMode) {
            if (cb.checked) {
                excludedCandidateIds.delete(id);
            } else {
                excludedCandidateIds.add(id);
            }
        }
        updateBulkActionState();
    }

    function toggleSelectAllCandidates(master) {
        if (!master.checked) {
            deselectAllCandidates();
            return;
        }
        selectAllMatchingMode = false;
        excludedCandidateIds.clear();
        document.querySelectorAll('.candidate-checkbox').forEach(cb => cb.checked = true);
        updateBulkActionState();
    }

    function selectAllMatchingCandidates() {
        if (totalMatchingCandidates <= 0) return;
        selectAllMatchingMode = true;
        excludedCandidateIds.clear();
        document.querySelectorAll('.candidate-checkbox').forEach(cb => cb.checked = true);
        updateBulkActionState();
    }

    function deselectAllCandidates() {
        selectAllMatchingMode = false;
        excludedCandidateIds.clear();
        const master = document.getElementById('selectAllCandidates');
        if (master) master.checked = false;
        document.querySelectorAll('.candidate-checkbox').forEach(cb => cb.checked = false);
        updateBulkActionState();
    }

    function updateBulkActionState() {
        const allPageIds = getUniquePageIds();
        const selectedPageIds = getSelectedPageIds();
        const effectiveCount = selectAllMatchingMode
            ? Math.max(0, totalMatchingCandidates - excludedCandidateIds.size)
            : selectedPageIds.size;

        const bar = document.getElementById('bulkActionBar');
        const countSpan = document.getElementById('selectedCount');
        const countLabel = document.getElementById('selectedCountLabel');
        const master = document.getElementById('selectAllCandidates');
        const banner = document.getElementById('selectAllMatchingBanner');
        const bannerText = document.getElementById('selectAllMatchingBannerText');
        const bannerBtn = document.getElementById('selectAllMatchingActionBtn');

        if (countSpan) countSpan.textContent = effectiveCount.toLocaleString();
        if (countLabel) {
            countLabel.textContent = selectAllMatchingMode
                ? 'Matching Candidates Selected (All Pages)'
                : 'Candidates Selected';
        }

        if (master && allPageIds.size > 0) {
            master.checked = (selectedPageIds.size === allPageIds.size);
        }

        if (banner && bannerText && bannerBtn) {
            if (selectAllMatchingMode) {
                banner.classList.remove('hidden');
                bannerText.innerHTML = `<i class="fas fa-check-double text-accent-blue mr-1.5"></i> All <strong>${effectiveCount.toLocaleString()}</strong> matching candidate(s) across all pages are selected.`;
                bannerBtn.classList.add('hidden');
            } else if (allPageIds.size > 0 && selectedPageIds.size === allPageIds.size && totalMatchingCandidates > visiblePageCount) {
                banner.classList.remove('hidden');
                bannerText.innerHTML = `All <strong>${selectedPageIds.size}</strong> candidate(s) on this page are selected.`;
                bannerBtn.classList.remove('hidden');
            } else {
                banner.classList.add('hidden');
            }
        }

        if (effectiveCount > 0) {
            bar.classList.remove('hidden');
        } else {
            bar.classList.add('hidden');
        }
    }

    function syncBulkSelectionInputs(containerId, allMatchingId, excludedCsvId, countDisplayId) {
        const selectedPageIds = getSelectedPageIds();
        const effectiveCount = selectAllMatchingMode
            ? Math.max(0, totalMatchingCandidates - excludedCandidateIds.size)
            : selectedPageIds.size;

        if (effectiveCount === 0) return false;

        const inputsContainer = document.getElementById(containerId);
        const allMatchingInput = document.getElementById(allMatchingId);
        const excludedCsvInput = document.getElementById(excludedCsvId);
        inputsContainer.innerHTML = '';

        if (selectAllMatchingMode) {
            allMatchingInput.value = '1';
            excludedCsvInput.value = Array.from(excludedCandidateIds).join(',');
        } else {
            allMatchingInput.value = '0';
            excludedCsvInput.value = '';
            selectedPageIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'candidate_ids[]';
                input.value = id;
                inputsContainer.appendChild(input);
            });
        }

        document.getElementById(countDisplayId).textContent = effectiveCount.toLocaleString();
        return true;
    }

    function openBulkNotificationModal() {
        if (!syncBulkSelectionInputs('bulkCandidateInputs', 'bulkSelectAllMatchingInput', 'bulkExcludedCsvInput', 'modalSelectedCount')) {
            return;
        }
        document.getElementById('bulkNotificationModal').classList.remove('hidden');
    }

    function closeBulkNotificationModal() {
        document.getElementById('bulkNotificationModal').classList.add('hidden');
    }

    function openBulkSchoolDiscoveryModal() {
        if (!syncBulkSelectionInputs('bulkDiscoveryCandidateInputs', 'bulkDiscoverySelectAllMatching', 'bulkDiscoveryExcludedCsv', 'discoveryModalSelectedCount')) {
            return;
        }
        document.getElementById('bulkSchoolDiscoveryModal').classList.remove('hidden');
    }

    function closeBulkSchoolDiscoveryModal() {
        document.getElementById('bulkSchoolDiscoveryModal').classList.add('hidden');
    }

    function toggleNotificationFields(type) {
        const customFields = document.getElementById('customEmailFields');
        if (type === 'custom_email') {
            customFields.classList.remove('hidden');
        } else {
            customFields.classList.add('hidden');
        }
    }
</script>
@endpush
