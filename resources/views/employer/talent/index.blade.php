@extends('layouts.app')

@section('content')
@include('employer.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-main flex items-center gap-2.5">
                <i class="fas fa-chalkboard-teacher text-accent-yellow"></i>
                <span>Search Candidates / Search Teachers</span>
            </h1>
            <p class="text-sm text-text-dark/60 mt-1">
                Browse Admin-verified &amp; curated teaching profiles across subjects and request field-level access to unlock full profiles, CVs, photos, or contact details.
            </p>
        </div>

        <div class="flex items-center gap-2 bg-card-bg border border-card-border rounded-xl p-1">
            <a href="{{ route('employer.talent.index', array_merge(request()->except('tab', 'page'), ['tab' => 'search'])) }}"
               class="px-3.5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ $activeTab === 'search' ? 'bg-accent-yellow text-[#031b4e] shadow-sm' : 'text-text-dark/60 hover:text-text-main' }}">
                <i class="fas fa-search"></i>
                <span>Browse Teachers ({{ $discoveryStats['eligible'] }})</span>
            </a>
            <a href="{{ route('employer.talent.index', ['tab' => 'requests']) }}"
               class="px-3.5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ $activeTab === 'requests' ? 'bg-accent-yellow text-[#031b4e] shadow-sm' : 'text-text-dark/60 hover:text-text-main' }}">
                <i class="fas fa-unlock-alt"></i>
                <span>My Access Requests ({{ $discoveryStats['requests'] }})</span>
            </a>
        </div>
    </div>

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-5 py-3.5 rounded-2xl flex items-center gap-3 text-sm font-medium">
            <i class="fas fa-check-circle text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-5 py-3.5 rounded-2xl flex items-center gap-3 text-sm font-medium">
            <i class="fas fa-exclamation-circle text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(!$featureReady)
        <div class="bg-card-bg border border-card-border rounded-2xl p-8 text-center text-text-dark/60 text-sm">
            Candidate discovery is being initialized. Please try again after maintenance completes.
        </div>
    @elseif($activeTab === 'requests')
        {{-- School Unlock Requests & Access Audit History --}}
        <div class="space-y-6">
            <div class="bg-card-bg border border-card-border rounded-2xl overflow-hidden shadow-xl">
                <div class="px-6 py-4 border-b border-card-border flex flex-wrap items-center justify-between gap-2 bg-secondary-bg/30">
                    <div>
                        <h2 class="font-bold text-text-main text-base flex items-center gap-2">
                            <i class="fas fa-history text-accent-yellow"></i>
                            <span>Profile Access Requests &amp; Granted Permissions Log</span>
                        </h2>
                        <p class="text-xs text-text-dark/50 mt-0.5">Track approval status, delivery method (Dashboard / Email), and exact field-level permissions granted by Admin.</p>
                    </div>
                    <a href="{{ route('employer.talent.index') }}" class="px-3.5 py-2 rounded-xl bg-accent-yellow text-[#031b4e] text-xs font-extrabold hover:brightness-110 transition-all">
                        <i class="fas fa-search mr-1"></i> Back to Teacher Search
                    </a>
                </div>

                @if($myRequests->count() > 0)
                    <div class="divide-y divide-card-border">
                        @foreach($myRequests as $req)
                            @php
                                $reqBadge = match ($req['access_status']) {
                                    'approved' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                                    'partially_approved' => 'bg-blue-500/15 text-blue-400 border-blue-500/30',
                                    'declined' => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                                    default => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                                };
                            @endphp
                            <div class="p-5 hover:bg-secondary-bg/20 transition-colors space-y-4">
                                <div class="flex flex-wrap items-start justify-between gap-4">
                                    <div class="flex items-center gap-3.5">
                                        <img src="{{ $req['photo_url'] }}" alt="Candidate Preview"
                                             class="w-12 h-12 rounded-xl object-cover border border-card-border bg-secondary-bg">
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-bold text-text-main">{{ $req['display_name'] }}</span>
                                                <span class="text-[11px] font-mono text-text-dark/50">{{ $req['vpa_id'] }}</span>
                                                @if($req['is_verified'])
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/15 text-blue-400 border border-blue-500/30">
                                                        <i class="fas fa-check-circle"></i> Verified
                                                    </span>
                                                @endif
                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $reqBadge }}">
                                                    {{ $req['access_status_label'] }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-text-dark/70 mt-1">
                                                {{ $req['category'] }} · <strong>{{ $req['subject'] }}</strong> · {{ $req['qualification'] }} · {{ $req['experience_years'] }} Yrs Exp
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if($req['can_view_cv'] && !empty($req['unlocked_cv']['url']))
                                            <a href="{{ $req['unlocked_cv']['url'] }}" target="_blank"
                                               class="px-3 py-1.5 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 border border-emerald-500/30 text-xs font-bold inline-flex items-center gap-1.5">
                                                <i class="fas fa-file-pdf"></i> Download CV
                                            </a>
                                        @endif
                                        <button type="button" onclick="openTalentPreviewModal({{ $req['id'] }})"
                                                class="px-3.5 py-1.5 rounded-xl bg-accent-yellow text-[#031b4e] text-xs font-extrabold hover:brightness-110 cursor-pointer inline-flex items-center gap-1.5">
                                            <i class="fas fa-eye"></i> View / Manage
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs bg-secondary-bg/40 rounded-xl p-3.5 border border-card-border/70">
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-text-dark/50 block mb-1">Requested Permissions</span>
                                        <span class="text-text-main font-medium">{{ !empty($req['requested_labels']) ? implode(', ', $req['requested_labels']) : 'Standard Access' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-text-dark/50 block mb-1">Granted Access &amp; Method</span>
                                        @if(!empty($req['granted_permissions']))
                                            <span class="text-emerald-400 font-bold">{{ implode(', ', $req['granted_permissions']) }}</span>
                                            <span class="text-text-dark/60 block text-[11px] mt-0.5">
                                                Via: <strong>{{ ucfirst($req['delivery_method'] ?? 'dashboard') }}</strong>
                                                @if(!empty($req['email_shared_at'])) · Emailed {{ $req['email_shared_at'] }} @endif
                                            </span>
                                        @else
                                            <span class="text-text-dark/50">{{ $req['access_status'] === 'declined' ? 'None (Declined)' : 'Awaiting Admin Approval' }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-text-dark/50 block mb-1">Timeline</span>
                                        <span class="text-text-dark/80 block">Requested: {{ $req['requested_at'] ? $req['requested_at'] . ' IST' : '—' }}</span>
                                        @if(!empty($req['reviewed_at']))
                                            <span class="text-text-dark/80 block">Reviewed: {{ $req['reviewed_at'] }} IST</span>
                                        @endif
                                    </div>
                                </div>

                                @if(!empty($req['unlocked_contact']))
                                    <div class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex flex-wrap items-center gap-4 text-xs text-emerald-300">
                                        <span class="font-bold"><i class="fas fa-address-card mr-1"></i> Unlocked Contact:</span>
                                        <span><i class="fas fa-envelope mr-1"></i> {{ $req['unlocked_contact']['email'] }}</span>
                                        <span><i class="fas fa-phone-alt mr-1"></i> {{ $req['unlocked_contact']['phone'] }}</span>
                                    </div>
                                @elseif(in_array($req['access_status'], ['approved', 'partially_approved']) && ($req['delivery_method'] ?? '') === 'email')
                                    <div class="p-3 rounded-xl bg-blue-500/10 border border-blue-500/30 text-xs text-blue-300">
                                        <i class="fas fa-envelope-open-text mr-1.5"></i>
                                        Admin approved selected permissions for email sharing only — details were dispatched to <strong>{{ $req['school_email'] }}</strong>.
                                    </div>
                                @endif

                                @if(!empty($req['admin_notes']))
                                    <div class="text-xs text-text-dark/70 bg-secondary-bg/30 px-3 py-2 rounded-lg border border-card-border">
                                        <strong class="text-text-main">Admin Remarks:</strong> {{ $req['admin_notes'] }}
                                    </div>
                                @endif

                                @if(!empty($req['request_history']))
                                    <div class="text-[11px] text-text-dark/60 space-y-1 pt-1">
                                        <span class="font-bold uppercase tracking-wider text-[10px] text-text-dark/40 block">Audit Log</span>
                                        @foreach($req['request_history'] as $h)
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="px-1.5 py-0.5 rounded bg-secondary-bg border border-card-border text-text-main font-semibold">{{ $h['action'] }}</span>
                                                <span>{{ $h['at'] }}</span>
                                                @if(!empty($h['permissions']))
                                                    <span class="text-emerald-400">({{ implode(', ', $h['permissions']) }})</span>
                                                @endif
                                                @if(!empty($h['note']))
                                                    <span class="text-text-dark/50 italic">— "{{ $h['note'] }}"</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center text-text-dark/50 text-sm">
                        You have not requested profile access for any candidate yet. Browse verified teachers and click the eye icon or "Request Access".
                    </div>
                @endif
            </div>
        </div>
    @else
        {{-- Curated Summary Strip & Quick Category/Status Filters --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('employer.talent.index') }}"
               class="bg-card-bg border {{ !request('curated') ? 'border-accent-yellow ring-1 ring-accent-yellow/30' : 'border-card-border hover:border-accent-yellow/40' }} rounded-2xl p-4 flex items-center justify-between transition-all">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-text-dark/50">Eligible Candidate Pool</p>
                    <p class="text-2xl font-extrabold text-text-main mt-0.5">{{ $discoveryStats['eligible'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-accent-yellow/15 text-accent-yellow flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
            </a>

            <a href="{{ route('employer.talent.index', array_merge(request()->except('page'), ['curated' => request('curated') === 'verified' ? null : 'verified'])) }}"
               class="bg-card-bg border {{ request('curated') === 'verified' ? 'border-blue-400 ring-1 ring-blue-400/30' : 'border-card-border hover:border-blue-400/40' }} rounded-2xl p-4 flex items-center justify-between transition-all">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-text-dark/50">Verified Profiles</p>
                    <p class="text-2xl font-extrabold text-blue-400 mt-0.5">{{ $discoveryStats['verified'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-500/15 text-blue-400 flex items-center justify-center">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </a>

            <a href="{{ route('employer.talent.index', array_merge(request()->except('page'), ['curated' => request('curated') === 'best' ? null : 'best'])) }}"
               class="bg-card-bg border {{ request('curated') === 'best' ? 'border-amber-400 ring-1 ring-amber-400/30' : 'border-card-border hover:border-amber-400/40' }} rounded-2xl p-4 flex items-center justify-between transition-all">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-text-dark/50">Best Profiles (Admin Curated)</p>
                    <p class="text-2xl font-extrabold text-amber-400 mt-0.5">{{ $discoveryStats['best'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-500/15 text-amber-400 flex items-center justify-center">
                    <i class="fas fa-award"></i>
                </div>
            </a>
        </div>

        {{-- Curated Sections (When Browsing Default Discovery View) --}}
        @if($curatedBest->count() > 0 || $curatedVerified->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if($curatedVerified->count() > 0)
                    <div class="bg-card-bg border border-blue-500/30 rounded-2xl p-5 shadow-lg">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-sm font-bold text-text-main flex items-center gap-2">
                                <i class="fas fa-check-circle text-blue-400"></i>
                                <span>Verified Profiles</span>
                            </h2>
                            <a href="{{ route('employer.talent.index', ['curated' => 'verified']) }}" class="text-xs font-bold text-blue-400 hover:underline">View All &rarr;</a>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($curatedVerified as $cv)
                                <button type="button" onclick="openTalentPreviewModal({{ $cv['id'] }})"
                                        class="text-left p-3 rounded-xl bg-secondary-bg/50 border border-card-border hover:border-blue-400/60 transition-all flex items-center gap-3 cursor-pointer">
                                    <img src="{{ $cv['photo_url'] }}" alt="Preview" class="w-11 h-11 rounded-xl object-cover border border-card-border shrink-0">
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-text-main truncate">{{ $cv['display_name'] }}</div>
                                        <div class="text-[11px] text-blue-400 font-semibold truncate">{{ $cv['category'] }} · {{ $cv['subject'] }}</div>
                                        <div class="text-[10px] text-text-dark/50">{{ $cv['experience_years'] }} yrs · {{ $cv['availability_to_join'] }}</div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($curatedBest->count() > 0)
                    <div class="bg-card-bg border border-amber-500/30 rounded-2xl p-5 shadow-lg">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-sm font-bold text-text-main flex items-center gap-2">
                                <i class="fas fa-award text-amber-400"></i>
                                <span>Best Profiles (Handpicked)</span>
                            </h2>
                            <a href="{{ route('employer.talent.index', ['curated' => 'best']) }}" class="text-xs font-bold text-amber-400 hover:underline">View All &rarr;</a>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($curatedBest as $cb)
                                <button type="button" onclick="openTalentPreviewModal({{ $cb['id'] }})"
                                        class="text-left p-3 rounded-xl bg-secondary-bg/50 border border-card-border hover:border-amber-400/60 transition-all flex items-center gap-3 cursor-pointer">
                                    <img src="{{ $cb['photo_url'] }}" alt="Preview" class="w-11 h-11 rounded-xl object-cover border border-card-border shrink-0">
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-text-main truncate">{{ $cb['display_name'] }}</div>
                                        <div class="text-[11px] text-amber-400 font-semibold truncate">{{ $cb['category'] }} · {{ $cb['subject'] }}</div>
                                        <div class="text-[10px] text-text-dark/50">{{ $cb['experience_years'] }} yrs · {{ $cb['availability_to_join'] }}</div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Multi-Filter Search Bar --}}
        <form action="{{ route('employer.talent.index') }}" method="GET" class="bg-card-bg border border-card-border rounded-2xl p-5 shadow-xl space-y-4">
            @if(request('curated'))
                <input type="hidden" name="curated" value="{{ request('curated') }}">
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="lg:col-span-2 relative">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-text-dark/40 text-xs"></i>
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                           placeholder="Keywords (e.g. B.Ed, Hindi, TGT, Maths, CTET, VPA ID, City)..."
                           class="w-full pl-9 pr-4 py-2.5 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:border-accent-yellow focus:outline-none">
                </div>

                <select name="category_id" class="bg-secondary-bg border border-card-border rounded-xl px-3 py-2.5 text-sm text-text-main focus:border-accent-yellow focus:outline-none">
                    <option value="">All Categories (TGT / PGT / PRT)</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="subject_id" class="bg-secondary-bg border border-card-border rounded-xl px-3 py-2.5 text-sm text-text-main focus:border-accent-yellow focus:outline-none">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                    @endforeach
                </select>

                <select name="qualification_id" class="bg-secondary-bg border border-card-border rounded-xl px-3 py-2.5 text-sm text-text-main focus:border-accent-yellow focus:outline-none">
                    <option value="">All Qualifications</option>
                    @foreach($qualifications as $qual)
                        <option value="{{ $qual->id }}" {{ request('qualification_id') == $qual->id ? 'selected' : '' }}>{{ $qual->name }}</option>
                    @endforeach
                </select>

                <select name="min_experience" class="bg-secondary-bg border border-card-border rounded-xl px-3 py-2.5 text-sm text-text-main focus:border-accent-yellow focus:outline-none">
                    <option value="">Any Experience</option>
                    <option value="1" {{ request('min_experience') === '1' ? 'selected' : '' }}>1+ Years</option>
                    <option value="3" {{ request('min_experience') === '3' ? 'selected' : '' }}>3+ Years</option>
                    <option value="5" {{ request('min_experience') === '5' ? 'selected' : '' }}>5+ Years</option>
                    <option value="10" {{ request('min_experience') === '10' ? 'selected' : '' }}>10+ Years</option>
                </select>

                <select name="availability" class="bg-secondary-bg border border-card-border rounded-xl px-3 py-2.5 text-sm text-text-main focus:border-accent-yellow focus:outline-none">
                    <option value="">Availability / Joining Time</option>
                    <option value="immediate" {{ request('availability') === 'immediate' ? 'selected' : '' }}>Immediate Joining</option>
                    <option value="within_15_days" {{ request('availability') === 'within_15_days' ? 'selected' : '' }}>Within 15 Days</option>
                    <option value="within_1_month" {{ request('availability') === 'within_1_month' ? 'selected' : '' }}>Within 1 Month (30 Days)</option>
                    <option value="within_2_months" {{ request('availability') === 'within_2_months' ? 'selected' : '' }}>Within 2 Months (60 Days)</option>
                    <option value="3_months_plus" {{ request('availability') === '3_months_plus' ? 'selected' : '' }}>3+ Months Notice</option>
                </select>

                <select name="verified_status" class="bg-secondary-bg border border-card-border rounded-xl px-3 py-2.5 text-sm text-text-main focus:border-accent-yellow focus:outline-none">
                    <option value="">Verification Status</option>
                    <option value="verified" {{ request('verified_status') === 'verified' ? 'selected' : '' }}>Verified Only</option>
                    <option value="unverified" {{ request('verified_status') === 'unverified' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                <span class="text-xs text-text-dark/50">
                    Showing <strong class="text-text-main">{{ $cards->total() }}</strong> eligible candidate profile(s). Sensitive contact details &amp; unblurred photos remain protected until Admin approval.
                </span>
                <div class="flex items-center gap-2">
                    @if(request()->anyFilled(['keyword', 'category_id', 'subject_id', 'qualification_id', 'min_experience', 'availability', 'verified_status', 'curated']))
                        <a href="{{ route('employer.talent.index') }}" class="px-4 py-2 rounded-xl bg-secondary-bg border border-card-border text-xs font-bold text-text-dark/70 hover:text-text-main">
                            Reset Filters
                        </a>
                    @endif
                    <button type="submit" class="px-5 py-2 rounded-xl bg-accent-yellow text-[#031b4e] text-xs font-extrabold hover:brightness-110 cursor-pointer shadow-sm">
                        <i class="fas fa-filter mr-1"></i> Search Teachers
                    </button>
                </div>
            </div>
        </form>

        {{-- Candidate Result Cards --}}
        @if($cards->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($cards as $card)
                    @php
                        $statusChip = match ($card['access_status']) {
                            'approved' => ['label' => 'Approved', 'class' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30'],
                            'partially_approved' => ['label' => 'Partially Approved', 'class' => 'bg-blue-500/15 text-blue-400 border-blue-500/30'],
                            'pending' => ['label' => 'Pending Review', 'class' => 'bg-amber-500/15 text-amber-400 border-amber-500/30'],
                            'declined' => ['label' => 'Declined', 'class' => 'bg-rose-500/15 text-rose-400 border-rose-500/30'],
                            default => ['label' => 'Locked Preview', 'class' => 'bg-slate-500/15 text-slate-300 border-slate-500/30'],
                        };
                    @endphp
                    <div class="bg-card-bg border border-card-border rounded-2xl p-5 flex flex-col justify-between gap-4 shadow-xl hover:border-accent-yellow/40 transition-all">
                        <div>
                            {{-- Top Identity + Gated Photo Preview --}}
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <button type="button" onclick="openTalentPreviewModal({{ $card['id'] }})"
                                            class="relative shrink-0 rounded-xl overflow-hidden group cursor-pointer focus:outline-none"
                                            title="{{ $card['photo_unlocked'] ? 'View Unlocked Photo & Profile' : 'Photo protected — click to preview & request access' }}">
                                        <img src="{{ $card['photo_url'] }}" alt="{{ $card['display_name'] }}"
                                             class="w-14 h-14 rounded-xl object-cover border border-card-border">
                                        @if(!$card['photo_unlocked'])
                                            <span class="absolute inset-0 bg-black/35 flex items-center justify-center text-white/90 text-xs">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        @endif
                                    </button>

                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <h3 class="font-bold text-text-main text-sm truncate">{{ $card['display_name'] }}</h3>
                                            @if($card['is_verified'])
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/15 text-blue-400 border border-blue-500/30" title="Verified Profile">
                                                    <i class="fas fa-check-circle"></i> Verified
                                                </span>
                                            @endif
                                            @if($card['is_best_profile'])
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-400 border border-amber-500/30">
                                                    <i class="fas fa-award"></i> Best
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-accent-yellow font-semibold mt-0.5 truncate">
                                            {{ $card['category'] }} · {{ $card['subject'] }}
                                        </p>
                                    </div>
                                </div>

                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border shrink-0 {{ $statusChip['class'] }}">
                                    {{ $statusChip['label'] }}
                                </span>
                            </div>

                            {{-- Safe Basic Attributes --}}
                            <div class="mt-4 grid grid-cols-2 gap-2.5 text-xs">
                                <div class="bg-secondary-bg/45 p-2.5 rounded-xl border border-card-border">
                                    <span class="text-[10px] text-text-dark/50 uppercase font-bold block">Qualification</span>
                                    <span class="font-semibold text-text-main truncate block">{{ $card['qualification'] }}</span>
                                </div>
                                <div class="bg-secondary-bg/45 p-2.5 rounded-xl border border-card-border">
                                    <span class="text-[10px] text-text-dark/50 uppercase font-bold block">Experience</span>
                                    <span class="font-semibold text-text-main">{{ $card['experience_years'] }} Year(s)</span>
                                </div>
                                <div class="bg-secondary-bg/45 p-2.5 rounded-xl border border-card-border">
                                    <span class="text-[10px] text-text-dark/50 uppercase font-bold block">Current / Last Salary</span>
                                    <span class="font-semibold text-text-main truncate block">{{ $card['current_salary'] }}</span>
                                </div>
                                <div class="bg-secondary-bg/45 p-2.5 rounded-xl border border-card-border">
                                    <span class="text-[10px] text-text-dark/50 uppercase font-bold block">Joining / Availability</span>
                                    <span class="font-semibold text-emerald-400 truncate block">{{ $card['availability_to_join'] }}</span>
                                </div>
                            </div>

                            <div class="mt-2.5 flex items-center justify-between text-[11px] text-text-dark/60">
                                <span><i class="fas fa-map-marker-alt text-accent-yellow/70 mr-1"></i> {{ $card['preferred_location'] }}</span>
                                @if(!empty($card['granted_permissions']))
                                    <span class="text-emerald-400 font-semibold">Unlocked: {{ implode(', ', $card['granted_permissions']) }}</span>
                                @else
                                    <span><i class="fas fa-shield-alt text-text-dark/40 mr-1"></i> Contact Protected</span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Actions --}}
                        <div class="pt-3 border-t border-card-border/70 flex items-center justify-between gap-2">
                            <button type="button" onclick="openTalentPreviewModal({{ $card['id'] }})"
                                    class="px-3.5 py-2 rounded-xl bg-secondary-bg hover:bg-card-border/60 border border-card-border text-text-main text-xs font-bold cursor-pointer inline-flex items-center gap-1.5 transition-colors">
                                <i class="fas fa-eye text-accent-yellow"></i>
                                <span>Preview</span>
                            </button>

                            @if(in_array($card['access_status'], ['none', 'declined']))
                                <button type="button" onclick="openTalentPreviewModal({{ $card['id'] }}, true)"
                                        class="px-3.5 py-2 rounded-xl bg-accent-yellow hover:brightness-110 text-[#031b4e] text-xs font-extrabold cursor-pointer inline-flex items-center gap-1.5 transition-all shadow-sm">
                                    <i class="fas fa-unlock"></i>
                                    <span>{{ $card['access_status'] === 'declined' ? 'Re-request Access' : 'Request Profile Access' }}</span>
                                </button>
                            @elseif($card['access_status'] === 'pending')
                                <span class="px-3 py-2 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold inline-flex items-center gap-1.5">
                                    <i class="fas fa-clock"></i> Pending Admin Review
                                </span>
                            @else
                                <button type="button" onclick="openTalentPreviewModal({{ $card['id'] }})"
                                        class="px-3.5 py-2 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/30 text-emerald-400 text-xs font-bold cursor-pointer inline-flex items-center gap-1.5">
                                    <i class="fas fa-check-circle"></i> View Granted Access
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($cards->hasPages())
                <div class="pt-4 flex justify-end">
                    {{ $cards->links('pagination::tailwind') }}
                </div>
            @endif
        @else
            <div class="bg-card-bg border border-card-border rounded-2xl p-12 text-center shadow-xl">
                <div class="w-16 h-16 rounded-2xl bg-secondary-bg text-text-dark/30 flex items-center justify-center text-3xl mx-auto mb-3 border border-card-border">
                    <i class="fas fa-user-search"></i>
                </div>
                <h3 class="text-lg font-bold text-text-main">No matching eligible teachers found</h3>
                <p class="text-xs text-text-dark/50 mt-1">Only candidate profiles approved by Admin for school discovery appear here. Try widening your subject, category, or experience filters.</p>
            </div>
        @endif
    @endif
</div>

{{-- Candidate Preview & Profile Unlock Request Modal --}}
<div id="talentPreviewModal" class="fixed inset-0 z-[9999] hidden bg-black/75 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-card-bg border border-card-border rounded-2xl max-w-2xl w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto space-y-5">
        <button type="button" onclick="closeTalentPreviewModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-secondary-bg text-text-dark/60 hover:text-text-main flex items-center justify-center cursor-pointer">
            <i class="fas fa-times"></i>
        </button>

        <div id="talentModalLoading" class="py-12 text-center text-text-dark/60 text-sm">
            <i class="fas fa-spinner fa-spin text-2xl text-accent-yellow mb-2 block"></i>
            Loading candidate profile...
        </div>

        <div id="talentModalBody" class="hidden space-y-5">
            {{-- Header --}}
            <div class="flex items-start gap-4 pb-4 border-b border-card-border">
                <div class="relative shrink-0">
                    <img id="tmPhoto" src="" alt="Candidate Preview" class="w-16 h-16 rounded-2xl object-cover border border-card-border">
                    <span id="tmPhotoLockBadge" class="hidden absolute inset-0 rounded-2xl bg-black/40 flex items-center justify-center text-white text-xs" title="Photo blurred until Admin unlocks">
                        <i class="fas fa-lock"></i>
                    </span>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 id="tmDisplayName" class="text-lg font-bold text-text-main"></h3>
                        <span id="tmVerifiedBadge" class="hidden px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/15 text-blue-400 border border-blue-500/30">
                            <i class="fas fa-check-circle"></i> Verified
                        </span>
                        <span id="tmBestBadge" class="hidden px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-400 border border-amber-500/30">
                            <i class="fas fa-award"></i> Best Profile
                        </span>
                        <span id="tmAccessStatusBadge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border"></span>
                    </div>
                    <p id="tmSubhead" class="text-xs text-accent-yellow font-semibold mt-1"></p>
                    <p id="tmProtectionNote" class="text-[11px] text-text-dark/60 mt-1"></p>
                </div>
            </div>

            {{-- Safe Metrics --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                <div class="bg-secondary-bg/50 p-3 rounded-xl border border-card-border">
                    <span class="text-[10px] uppercase font-bold text-text-dark/50 block">Qualification</span>
                    <span id="tmQualification" class="font-bold text-text-main"></span>
                </div>
                <div class="bg-secondary-bg/50 p-3 rounded-xl border border-card-border">
                    <span class="text-[10px] uppercase font-bold text-text-dark/50 block">Experience</span>
                    <span id="tmExperience" class="font-bold text-text-main"></span>
                </div>
                <div class="bg-secondary-bg/50 p-3 rounded-xl border border-card-border">
                    <span class="text-[10px] uppercase font-bold text-text-dark/50 block">Current Salary</span>
                    <span id="tmSalary" class="font-bold text-text-main"></span>
                </div>
                <div class="bg-secondary-bg/50 p-3 rounded-xl border border-card-border">
                    <span class="text-[10px] uppercase font-bold text-text-dark/50 block">Availability</span>
                    <span id="tmAvailability" class="font-bold text-emerald-400"></span>
                </div>
                <div class="bg-secondary-bg/50 p-3 rounded-xl border border-card-border sm:col-span-2">
                    <span class="text-[10px] uppercase font-bold text-text-dark/50 block">Preferred Location</span>
                    <span id="tmLocation" class="font-bold text-text-main"></span>
                </div>
            </div>

            {{-- Unlocked Email-Only Banner --}}
            <div id="tmEmailOnlyBanner" class="hidden p-4 rounded-xl bg-blue-500/10 border border-blue-500/30 text-xs text-blue-300">
                <p class="font-bold text-blue-400 mb-0.5"><i class="fas fa-envelope-open-text mr-1"></i> Delivered via Email Only</p>
                <p id="tmEmailOnlyText"></p>
            </div>

            {{-- Unlocked Full Profile Section --}}
            <div id="tmFullProfileBox" class="hidden bg-emerald-500/5 border border-emerald-500/25 rounded-2xl p-4 space-y-3">
                <h4 class="text-xs font-bold text-emerald-400 uppercase tracking-wider"><i class="fas fa-id-card mr-1.5"></i> Unlocked Full Profile Details</h4>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                    <div><span class="text-[10px] text-text-dark/50 block">Full Name</span><strong id="tmFpName" class="text-text-main"></strong></div>
                    <div><span class="text-[10px] text-text-dark/50 block">Gender</span><strong id="tmFpGender" class="text-text-main"></strong></div>
                    <div><span class="text-[10px] text-text-dark/50 block">Current School</span><strong id="tmFpSchool" class="text-text-main"></strong></div>
                    <div><span class="text-[10px] text-text-dark/50 block">Expected Salary</span><strong id="tmFpExpSalary" class="text-text-main"></strong></div>
                    <div><span class="text-[10px] text-text-dark/50 block">English Fluency</span><strong id="tmFpFluency" class="text-text-main"></strong></div>
                    <div><span class="text-[10px] text-text-dark/50 block">Residential Pref</span><strong id="tmFpRes" class="text-text-main"></strong></div>
                    <div class="col-span-2 sm:col-span-3"><span class="text-[10px] text-text-dark/50 block">Other Qualifications / Address</span><span id="tmFpExtra" class="text-text-main"></span></div>
                </div>
            </div>

            {{-- Unlocked Contact Details Section --}}
            <div id="tmContactBox" class="hidden bg-emerald-500/10 border border-emerald-500/30 rounded-2xl p-4 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h4 class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-1"><i class="fas fa-phone-alt mr-1.5"></i> Unlocked Contact Details</h4>
                    <div class="text-xs text-text-main space-y-0.5">
                        <div>Email: <strong id="tmContactEmail"></strong></div>
                        <div>Phone: <strong id="tmContactPhone"></strong></div>
                    </div>
                </div>
            </div>

            {{-- Unlocked CV Action --}}
            <div id="tmCvBox" class="hidden bg-secondary-bg/60 border border-card-border rounded-2xl p-4 flex items-center justify-between gap-3">
                <div class="text-xs">
                    <strong class="text-text-main block">Unlocked Resume / CV</strong>
                    <span class="text-text-dark/60">Admin has enabled CV access for your school.</span>
                </div>
                <a id="tmCvDownloadBtn" href="#" target="_blank"
                   class="px-4 py-2 rounded-xl bg-accent-yellow text-[#031b4e] text-xs font-extrabold hover:brightness-110 inline-flex items-center gap-1.5">
                    <i class="fas fa-file-pdf"></i> View / Download CV
                </a>
                <span id="tmCvUnavailable" class="hidden text-xs text-amber-400 font-semibold">Candidate has not uploaded a CV file yet.</span>
            </div>

            {{-- Request Profile Access Form (Visible if not unlocked or declined) --}}
            <form id="tmRequestForm" method="POST" action="" class="hidden bg-secondary-bg/50 border border-accent-yellow/30 rounded-2xl p-4 space-y-3">
                @csrf
                <div>
                    <h4 class="text-xs font-bold text-accent-yellow uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fas fa-unlock"></i> Request Profile Access / Unlock Profile
                    </h4>
                    <p class="text-[11px] text-text-dark/60 mt-0.5">Select the details you need. Admin will review your request and share approved fields securely.</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                    <label class="flex items-center gap-2 bg-card-bg border border-card-border rounded-xl px-3 py-2 cursor-pointer">
                        <input type="checkbox" name="requested_photo" value="1" checked class="rounded text-accent-yellow">
                        <span class="text-text-main font-semibold">Photo</span>
                    </label>
                    <label class="flex items-center gap-2 bg-card-bg border border-card-border rounded-xl px-3 py-2 cursor-pointer">
                        <input type="checkbox" name="requested_full_profile" value="1" checked class="rounded text-accent-yellow">
                        <span class="text-text-main font-semibold">Full Profile</span>
                    </label>
                    <label class="flex items-center gap-2 bg-card-bg border border-card-border rounded-xl px-3 py-2 cursor-pointer">
                        <input type="checkbox" name="requested_cv" value="1" checked class="rounded text-accent-yellow">
                        <span class="text-text-main font-semibold">CV / Resume</span>
                    </label>
                    <label class="flex items-center gap-2 bg-card-bg border border-card-border rounded-xl px-3 py-2 cursor-pointer">
                        <input type="checkbox" name="requested_contact" value="1" checked class="rounded text-accent-yellow">
                        <span class="text-text-main font-semibold">Contact</span>
                    </label>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-text-dark/70 mb-1">Requirement Note for Admin (Optional)</label>
                    <textarea name="school_note" rows="2" maxlength="1000"
                              placeholder="e.g., Need contact & CV for TGT Hindi urgent opening in our Ranchi branch..."
                              class="w-full rounded-xl bg-card-bg border border-card-border px-3 py-2 text-xs text-text-main focus:border-accent-yellow focus:outline-none"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-accent-yellow text-[#031b4e] text-xs font-extrabold hover:brightness-110 cursor-pointer shadow-sm">
                        <i class="fas fa-paper-plane mr-1.5"></i> Send Unlock Request to Admin
                    </button>
                </div>
            </form>

            {{-- Admin Notes & Request Timeline --}}
            <div id="tmAdminNotesBox" class="hidden p-3.5 rounded-xl bg-secondary-bg border border-card-border text-xs text-text-main"></div>
            <div id="tmHistoryBox" class="hidden space-y-1.5 text-[11px] text-text-dark/70 border-t border-card-border pt-3">
                <span class="font-bold uppercase tracking-wider text-[10px] text-text-dark/40 block">Request &amp; Access History</span>
                <div id="tmHistoryList" class="space-y-1"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function openTalentPreviewModal(candidateId, focusRequest = false) {
        const modal = document.getElementById('talentPreviewModal');
        const loading = document.getElementById('talentModalLoading');
        const body = document.getElementById('talentModalBody');

        modal.classList.remove('hidden');
        loading.classList.remove('hidden');
        body.classList.add('hidden');

        fetch(`{{ url('/employer/search-candidates') }}/${candidateId}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => {
            if (!r.ok) throw new Error('Unable to load candidate preview.');
            return r.json();
        })
        .then(res => {
            const c = res.candidate;
            document.getElementById('tmPhoto').src = c.photo_url;
            document.getElementById('tmPhotoLockBadge').classList.toggle('hidden', !!c.photo_unlocked);
            document.getElementById('tmDisplayName').textContent = c.display_name;
            document.getElementById('tmVerifiedBadge').classList.toggle('hidden', !c.is_verified);
            document.getElementById('tmBestBadge').classList.toggle('hidden', !c.is_best_profile);

            const statusBadge = document.getElementById('tmAccessStatusBadge');
            statusBadge.textContent = c.access_status_label || 'Locked';
            statusBadge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold border ' + (
                c.access_status === 'approved' ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30' :
                c.access_status === 'partially_approved' ? 'bg-blue-500/15 text-blue-400 border-blue-500/30' :
                c.access_status === 'pending' ? 'bg-amber-500/15 text-amber-400 border-amber-500/30' :
                c.access_status === 'declined' ? 'bg-rose-500/15 text-rose-400 border-rose-500/30' :
                'bg-slate-500/15 text-slate-300 border-slate-500/30'
            );

            document.getElementById('tmSubhead').textContent = `${c.category} · ${c.subject}${c.specialization ? ' · ' + c.specialization : ''}`;
            document.getElementById('tmProtectionNote').textContent = c.allows_dashboard
                ? `Unlocked via Dashboard: ${c.granted_permissions.join(', ') || 'Limited'}`
                : 'Email, phone, CV, full profile details, and clear photo stay protected until Admin approval.';

            document.getElementById('tmQualification').textContent = c.qualification;
            document.getElementById('tmExperience').textContent = `${c.experience_years} Year(s)`;
            document.getElementById('tmSalary').textContent = c.current_salary;
            document.getElementById('tmAvailability').textContent = c.availability_to_join;
            document.getElementById('tmLocation').textContent = c.preferred_location;

            // Email-only notice
            const emailOnlyBox = document.getElementById('tmEmailOnlyBanner');
            if ((c.access_status === 'approved' || c.access_status === 'partially_approved') && c.delivery_method === 'email') {
                emailOnlyBox.classList.remove('hidden');
                document.getElementById('tmEmailOnlyText').textContent =
                    `Admin approved (${c.granted_permissions.join(', ')}) via Email delivery only and shared details directly to ${c.school_email}.`;
            } else {
                emailOnlyBox.classList.add('hidden');
            }

            // Full Profile
            const fpBox = document.getElementById('tmFullProfileBox');
            if (c.unlocked_full_profile) {
                fpBox.classList.remove('hidden');
                document.getElementById('tmFpName').textContent = c.unlocked_full_profile.full_name;
                document.getElementById('tmFpGender').textContent = c.unlocked_full_profile.gender;
                document.getElementById('tmFpSchool').textContent = c.unlocked_full_profile.current_school;
                document.getElementById('tmFpExpSalary').textContent = c.unlocked_full_profile.expected_salary;
                document.getElementById('tmFpFluency').textContent = c.unlocked_full_profile.english_fluency;
                document.getElementById('tmFpRes').textContent = c.unlocked_full_profile.residential_preference;
                document.getElementById('tmFpExtra').textContent =
                    `Other Quals: ${c.unlocked_full_profile.other_qualifications} | Address: ${c.unlocked_full_profile.address}`;
            } else {
                fpBox.classList.add('hidden');
            }

            // Contact
            const contactBox = document.getElementById('tmContactBox');
            if (c.unlocked_contact) {
                contactBox.classList.remove('hidden');
                document.getElementById('tmContactEmail').textContent = c.unlocked_contact.email || 'N/A';
                document.getElementById('tmContactPhone').textContent = c.unlocked_contact.phone || 'N/A';
            } else {
                contactBox.classList.add('hidden');
            }

            // CV
            const cvBox = document.getElementById('tmCvBox');
            const cvBtn = document.getElementById('tmCvDownloadBtn');
            const cvUnavail = document.getElementById('tmCvUnavailable');
            if (c.unlocked_cv) {
                cvBox.classList.remove('hidden');
                if (c.unlocked_cv.available && c.unlocked_cv.url) {
                    cvBtn.href = c.unlocked_cv.url;
                    cvBtn.classList.remove('hidden');
                    cvUnavail.classList.add('hidden');
                } else {
                    cvBtn.classList.add('hidden');
                    cvUnavail.classList.remove('hidden');
                }
            } else {
                cvBox.classList.add('hidden');
            }

            // Request Access Form
            const reqForm = document.getElementById('tmRequestForm');
            reqForm.action = `{{ url('/employer/search-candidates') }}/${c.id}/request-access`;
            const canRequest = c.access_status === 'none' || c.access_status === 'declined';
            reqForm.classList.toggle('hidden', !canRequest);

            // Admin Notes
            const notesBox = document.getElementById('tmAdminNotesBox');
            if (c.admin_notes) {
                notesBox.classList.remove('hidden');
                notesBox.innerHTML = `<strong>Admin Notes:</strong> `;
                notesBox.appendChild(document.createTextNode(c.admin_notes));
            } else {
                notesBox.classList.add('hidden');
            }

            // History Log
            const histBox = document.getElementById('tmHistoryBox');
            const histList = document.getElementById('tmHistoryList');
            histList.innerHTML = '';
            if (c.request_history && c.request_history.length > 0) {
                histBox.classList.remove('hidden');
                c.request_history.forEach(item => {
                    const row = document.createElement('div');
                    row.className = 'flex flex-wrap items-center gap-2';
                    const badge = document.createElement('span');
                    badge.className = 'px-1.5 py-0.5 rounded bg-secondary-bg border border-card-border text-text-main font-semibold';
                    badge.textContent = item.action;
                    row.appendChild(badge);
                    const meta = document.createElement('span');
                    meta.textContent = item.at + (item.permissions && item.permissions.length ? ` · Granted: ${item.permissions.join(', ')}` : '');
                    row.appendChild(meta);
                    histList.appendChild(row);
                });
            } else {
                histBox.classList.add('hidden');
            }

            loading.classList.add('hidden');
            body.classList.remove('hidden');
            if (focusRequest && canRequest) {
                reqForm.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        })
        .catch(() => {
            loading.textContent = 'Failed to load candidate preview. Please refresh and try again.';
        });
    }

    function closeTalentPreviewModal() {
        document.getElementById('talentPreviewModal').classList.add('hidden');
    }
</script>
@endsection
