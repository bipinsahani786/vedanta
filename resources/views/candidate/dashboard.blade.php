@extends('layouts.candidate')

@section('candidate_content')
{{-- Popup Modal for Incomplete Registration / Profile --}}
@include('candidate.partials.incomplete-profile-modal')

<div class="space-y-6 pb-8">

    {{-- Top Welcome & Profile Strength Banner --}}
    <div class="bg-gradient-to-r from-[#031544] via-[#092b7a] to-[#1e0e47] border border-blue-400/25 rounded-3xl p-6 sm:p-7 text-white shadow-[0_10px_35px_rgba(3,27,78,0.45)] relative overflow-hidden">
        {{-- Decorative ambient glow backlights --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-sky-400/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 left-1/3 w-80 h-80 bg-purple-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-12 w-48 h-48 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col xl:flex-row items-center justify-between gap-6">
            {{-- Left: Candidate Info & Meta Badges --}}
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-5 text-center sm:text-left flex-1 min-w-0">
                {{-- Avatar with Verified Badge --}}
                <div class="relative shrink-0 group">
                    @if($profile->profile_photo_path)
                        <img src="{{ asset('storage/' . $profile->profile_photo_path) }}" alt="{{ auth()->user()->name }}"
                            class="w-20 h-20 sm:w-22 sm:h-22 rounded-full object-cover ring-4 ring-white/20 shadow-2xl transition-transform duration-300 group-hover:scale-105">
                    @else
                        <div class="w-20 h-20 sm:w-22 sm:h-22 rounded-full bg-gradient-to-br from-accent-blue via-blue-600 to-indigo-800 flex items-center justify-center text-3xl font-black ring-4 ring-white/20 shadow-2xl transition-transform duration-300 group-hover:scale-105">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif

                    @if($profile->is_verified || $profile->is_fee_paid)
                        <span class="absolute bottom-0.5 right-0.5 w-6 h-6 rounded-full bg-emerald-500 ring-2 ring-[#031544] flex items-center justify-center text-[10px] text-white shadow-lg" title="Verified Candidate">
                            <i class="fas fa-check"></i>
                        </span>
                    @endif
                </div>

                {{-- Text Info --}}
                <div class="space-y-2 min-w-0">
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
                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-purple-500/15 border border-purple-500/30 text-purple-200 text-[11px] font-semibold backdrop-blur-sm shadow-sm">
                            <i class="fas fa-crown text-xs text-amber-400"></i>
                            <span>Plan <strong class="text-white capitalize ml-0.5">{{ $profile->plan_type ?? 'Standard' }}</strong></span>
                        </div>
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
                    <div class="flex items-center gap-2 {{ $profile->is_profile_complete ? 'text-emerald-300 font-semibold' : 'text-white/45' }}">
                        <i class="fas {{ $profile->is_profile_complete ? 'fa-check-circle text-emerald-400' : 'fa-circle text-[7px] text-white/30' }}"></i>
                        <span>Complete your profile</span>
                    </div>
                    <div class="flex items-center gap-2 {{ $profile->experience_years > 0 ? 'text-emerald-300 font-semibold' : 'text-white/45' }}">
                        <i class="fas {{ $profile->experience_years > 0 ? 'fa-check-circle text-emerald-400' : 'fa-circle text-[7px] text-white/30' }}"></i>
                        <span>Add experience details</span>
                    </div>
                    <div class="flex items-center gap-2 {{ !empty($profile->resume_path) ? 'text-emerald-300 font-semibold' : 'text-white/45' }}">
                        <i class="fas {{ !empty($profile->resume_path) ? 'fa-check-circle text-emerald-400' : 'fa-circle text-[7px] text-white/30' }}"></i>
                        <span>Upload documents</span>
                    </div>
                    <div class="flex items-center gap-2 {{ ($profile->is_verified || $profile->is_fee_paid) ? 'text-emerald-300 font-semibold' : 'text-white/45' }}">
                        <i class="fas {{ ($profile->is_verified || $profile->is_fee_paid) ? 'fa-check-circle text-emerald-400' : 'fa-circle text-[7px] text-white/30' }}"></i>
                        <span>Profile is visible to schools</span>
                    </div>

                    <div class="pt-1.5">
                        <a href="{{ route('candidate.profile.edit') }}" 
                           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 text-xs font-bold text-white transition-all shadow hover:shadow-md hover:scale-[1.02] active:scale-[0.98]">
                            <span>Improve Profile</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
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

    {{-- Metrics / Stat Cards (Row of 5 Cards) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
        {{-- Card 1: Applications --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-purple-500/40 hover:-translate-y-0.5 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-2xl bg-purple-500/15 border border-purple-500/25 text-purple-400 flex items-center justify-center text-lg shadow-sm group-hover:scale-110 group-hover:bg-purple-500/25 transition-all">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Applications</span>
            </div>
            <div>
                <div class="text-2xl lg:text-3xl font-black text-white flex items-baseline gap-1 tracking-tight">
                    <span>{{ $profile->used_applications }}</span>
                    <span class="text-xs text-slate-400 font-semibold">/ {{ $profile->total_allowed_applications }}</span>
                </div>
                <div class="text-[11px] text-slate-400 font-medium mt-0.5">Used</div>
                <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden mt-3 shadow-inner">
                    <div class="h-full bg-gradient-to-r from-purple-500 via-indigo-500 to-accent-blue rounded-full transition-all duration-700 shadow-[0_0_10px_rgba(168,85,247,0.5)]"
                         style="width: {{ $profile->total_allowed_applications > 0 ? min(100, ($profile->used_applications / $profile->total_allowed_applications) * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>

        {{-- Card 2: Shortlisted --}}
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

        {{-- Card 3: Interviews --}}
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

        {{-- Card 4: Profile Views --}}
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

        {{-- Card 5: Wallet Balance --}}
        <a href="{{ route('candidate.referral.index') }}"
           class="col-span-2 sm:col-span-1 bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-pink-500/40 hover:-translate-y-0.5 transition-all duration-300 group">
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

            {{-- 1. Notifications & Updates List --}}
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

            {{-- 3. Quick Actions (4 Interactive Cards Grid) --}}
            <div>
                <h3 class="text-base font-bold text-white mb-3 flex items-center gap-2">
                    <i class="fas fa-bolt text-accent-yellow text-sm"></i>
                    <span>Quick Actions</span>
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
                    {{-- 1. Resume Builder --}}
                    <a href="{{ route('resume.builder') }}" 
                       class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 hover:border-purple-500/40 hover:-translate-y-1 hover:shadow-[0_8px_25px_rgba(168,85,247,0.2)] transition-all duration-300 flex flex-col justify-between group">
                        <div class="w-11 h-11 rounded-2xl bg-purple-500/15 border border-purple-500/25 text-purple-400 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Resume Builder</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">Create ATS friendly resume</p>
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-purple-400 mt-3 group-hover:translate-x-1 transition-transform">
                                <span>Build Resume</span>
                                <i class="fas fa-arrow-right text-[9px]"></i>
                            </span>
                        </div>
                    </a>

                    {{-- 2. Find Jobs --}}
                    <a href="{{ route('jobs') }}" 
                       class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 hover:border-accent-blue/40 hover:-translate-y-1 hover:shadow-[0_8px_25px_rgba(18,154,239,0.2)] transition-all duration-300 flex flex-col justify-between group">
                        <div class="w-11 h-11 rounded-2xl bg-accent-blue/15 border border-accent-blue/25 text-accent-blue flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-search"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Find Jobs</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">Explore matching opportunities</p>
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-accent-blue mt-3 group-hover:translate-x-1 transition-transform">
                                <span>Search Jobs</span>
                                <i class="fas fa-arrow-right text-[9px]"></i>
                            </span>
                        </div>
                    </a>

                    {{-- 3. Saved Jobs --}}
                    <a href="{{ route('candidate.savedJobs.index') }}" 
                       class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 hover:border-emerald-500/40 hover:-translate-y-1 hover:shadow-[0_8px_25px_rgba(52,211,153,0.2)] transition-all duration-300 flex flex-col justify-between group">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-bookmark"></i>
                        </div>
                        <div>
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-bold text-white">Saved Jobs</h4>
                                @if(!empty($savedJobsCount) && $savedJobsCount > 0)
                                    <span class="px-1.5 py-0.2 rounded-md text-[9px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        {{ $savedJobsCount }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">View your bookmarked jobs</p>
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-400 mt-3 group-hover:translate-x-1 transition-transform">
                                <span>View Saved ({{ $savedJobsCount ?? 0 }})</span>
                                <i class="fas fa-arrow-right text-[9px]"></i>
                            </span>
                        </div>
                    </a>

                    {{-- 4. Job Alerts --}}
                    <a href="{{ route('candidate.aditionalFeature.show') }}" 
                       class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 hover:border-amber-500/40 hover:-translate-y-1 hover:shadow-[0_8px_25px_rgba(245,158,11,0.2)] transition-all duration-300 flex flex-col justify-between group">
                        <div class="w-11 h-11 rounded-2xl bg-amber-500/15 border border-amber-500/25 text-amber-400 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Job Alerts</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">Get notified about new jobs</p>
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-amber-400 mt-3 group-hover:translate-x-1 transition-transform">
                                <span>Manage Alerts</span>
                                <i class="fas fa-arrow-right text-[9px]"></i>
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- 4. Recommended Jobs for You --}}
            <div class="space-y-3.5">
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
                                            <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">{{ $job->school_name }}</p>
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
                                        <span>{{ $job->city->name ?? 'Bihar' }}, {{ $job->state->name ?? 'India' }}</span>
                                    </div>
                                    <div class="text-xs font-bold text-white flex items-center gap-1">
                                        <span>₹{{ $job->salary_range ?? '25,000 - 35,000 / Month' }}</span>
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

            {{-- 1. Refer & Earn Leaderboard Card --}}
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

            {{-- 2. Your Current Plan Card --}}
            <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 shadow-[0_4px_20px_rgba(0,0,0,0.25)] space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-white/[0.08]">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Your Current Plan</span>
                        <h4 class="text-xl font-black text-white capitalize mt-0.5 tracking-tight">
                            {{ $profile->plan_type ?? 'Standard' }}
                        </h4>
                    </div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 uppercase tracking-wider shadow-sm">
                        Active
                    </span>
                </div>

                <p class="text-xs text-slate-400 leading-relaxed">
                    Upgrade to Premium and unlock exclusive career benefits.
                </p>

                {{-- Plan Features with Circular Check / Cross Icons --}}
                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center gap-2.5 text-white font-medium">
                        <i class="fas fa-check-circle text-emerald-400 text-sm"></i>
                        <span>Apply to all available jobs</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-white font-medium">
                        <i class="fas fa-check-circle text-emerald-400 text-sm"></i>
                        <span>Profile visibility to schools</span>
                    </div>
                    <div class="flex items-center gap-2.5 {{ $profile->plan_type === 'premium' ? 'text-white font-medium' : 'text-slate-500' }}">
                        <i class="fas {{ $profile->plan_type === 'premium' ? 'fa-check-circle text-emerald-400' : 'fa-times-circle text-red-400/60' }} text-sm"></i>
                        <span>Dedicated Relationship Manager</span>
                    </div>
                    <div class="flex items-center gap-2.5 {{ $profile->plan_type === 'premium' ? 'text-white font-medium' : 'text-slate-500' }}">
                        <i class="fas {{ $profile->plan_type === 'premium' ? 'fa-check-circle text-emerald-400' : 'fa-times-circle text-red-400/60' }} text-sm"></i>
                        <span>Guaranteed Interviews</span>
                    </div>
                    <div class="flex items-center gap-2.5 {{ $profile->plan_type === 'premium' ? 'text-white font-medium' : 'text-slate-500' }}">
                        <i class="fas {{ $profile->plan_type === 'premium' ? 'fa-check-circle text-emerald-400' : 'fa-times-circle text-red-400/60' }} text-sm"></i>
                        <span>Resume Building Assistance</span>
                    </div>
                    <div class="flex items-center gap-2.5 {{ $profile->plan_type === 'premium' ? 'text-white font-medium' : 'text-slate-500' }}">
                        <i class="fas {{ $profile->plan_type === 'premium' ? 'fa-check-circle text-emerald-400' : 'fa-times-circle text-red-400/60' }} text-sm"></i>
                        <span>Priority application processing</span>
                    </div>
                </div>

                {{-- Upgrade Promo Box --}}
                <div class="p-3.5 rounded-2xl bg-gradient-to-br from-amber-500/20 via-[#0d2258] to-[#120f38] border border-amber-500/30 flex items-center justify-between gap-3 shadow-md">
                    <div>
                        <div class="flex items-center gap-1.5 text-xs font-black text-amber-400">
                            <i class="fas fa-crown"></i>
                            <span>Upgrade to Premium</span>
                        </div>
                        <p class="text-[10px] text-slate-300 mt-0.5"></p>
                    </div>
                    <a href="{{ route('candidate.payment.show') }}" 
                       style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: #020617;"
                       class="px-4 py-2 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs shadow-md shadow-amber-500/30 hover:shadow-lg hover:shadow-amber-500/50 hover:brightness-105 hover:-translate-y-0.5 transition-all shrink-0 flex items-center gap-1.5 whitespace-nowrap">
                        <span class="font-black">Upgrade Now</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection