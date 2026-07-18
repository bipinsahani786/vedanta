@extends('layouts.app')

@section('content')
    @include('candidate.partials.nav')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if($profile->initial_fee_paid || $profile->is_fee_paid)
            {{-- ================= FULLY REGISTERED DASHBOARD ================= --}}

            {{-- Welcome Banner --}}
            <div
                class="bg-gradient-to-r from-accent-blue to-accent-blue-hover rounded-3xl p-8 mb-8 text-white shadow-lg relative overflow-hidden reveal">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl"></div>
                <div class="absolute bottom-0 right-32 -mb-16 w-40 h-40 rounded-full bg-white opacity-10 blur-xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                    @if($profile->profile_photo_path)
                        <img src="{{ asset('storage/' . $profile->profile_photo_path) }}" alt="Profile Photo"
                            class="w-24 h-24 rounded-full object-cover border-4 border-white/20 shadow-xl">
                    @else
                        <div
                            class="w-24 h-24 rounded-full bg-white/20 flex items-center justify-center text-4xl border-4 border-white/20 shadow-xl">
                            <i class="fas fa-user text-white"></i>
                        </div>
                    @endif
                    <div class="text-center md:text-left flex-1">
                        <h1 class="text-3xl font-bold mb-1 flex items-center flex-wrap gap-2">
                            Welcome back, {{ auth()->user()->name }}!
                            @if($profile->is_verified)
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-500/20 border border-blue-400/50 text-blue-300 text-xs font-bold uppercase tracking-wider rounded-full shadow-[0_0_15px_rgba(59,130,246,0.3)]"
                                    title="Verified Profile">
                                    <i class="fas fa-check-circle"></i> Verified
                                </span>
                            @endif
                        </h1>
                        <p class="text-white/80 text-lg">Your profile is active and visible to top schools.</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex gap-3">
                        <a href="{{ route('jobs') }}"
                            class="px-6 py-3 bg-white text-accent-blue font-bold rounded-xl hover:bg-gray-50 transition-all shadow-md flex items-center gap-2">
                            <i class="fas fa-search"></i> Find Jobs
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Column: Stats & Plan --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Quick Stats & Application Limit --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 reveal reveal-delay-1">
                        <div onclick="window.location='{{ route('candidate.applications.index') }}'"
                            class="bg-card-bg rounded-2xl border border-card-border p-6 flex flex-col items-center justify-center text-center hover:border-accent-blue/30 transition-all shadow-sm relative cursor-pointer hover:bg-secondary-bg/30">
                            <div class="w-12 h-12 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-xl mb-3">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            @php
                                $actualUsedApplications = $profile->used_applications;
                            @endphp
                            <h3 class="text-3xl font-bold text-text-main">{{ $actualUsedApplications }} <span
                                    class="text-sm text-text-dark/40 font-normal">/
                                    {{ $profile->total_allowed_applications }}</span>
                            </h3>
                            <p class="text-xs font-semibold text-text-dark/50 uppercase tracking-wide mt-1">Applications Used
                            </p>
            @php
                $isHired = \App\Models\JobApplication::where('candidate_id', auth()->id())
                    ->where('status', 'hired')
                    ->exists();
                $limitReached = $actualUsedApplications >= $profile->total_allowed_applications;
                $hasActiveApplications = \App\Models\JobApplication::where('candidate_id', auth()->id())
                    ->whereIn('status', ['applied', 'shortlisted'])
                    ->exists();
                $isExpired = $limitReached && !$hasActiveApplications && !$isHired;
            @endphp
                            @if($isHired || $isExpired || $limitReached)
                                <div onclick="event.stopPropagation()"
                                    class="absolute inset-0 bg-black/50 rounded-2xl border border-card-border flex items-center justify-center backdrop-blur-sm flex-col z-10 cursor-default">
                                    <span
                                        class="{{ $isHired ? 'bg-green-500' : ($isExpired ? 'bg-red-500' : 'bg-accent-yellow text-slate-900') }} text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg mb-2">
                                        {{ $isHired ? 'Plan Completed' : ($isExpired ? 'Plan Expired' : 'Applications In Progress') }}
                                    </span>
                                    @if($isExpired)
                                        <a href="{{ route('candidate.payment.show', ['type' => 'renewal']) }}"
                                            class="px-3 py-1 bg-white text-red-600 text-xs font-bold rounded shadow hover:bg-red-50 transition-colors">Renew
                                            Plan</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        {{-- Card 2: Shortlisted --}}
                        <a href="{{ route('candidate.applications.index') }}"
                            class="bg-card-bg rounded-2xl border border-card-border p-6 flex flex-col items-center justify-center text-center hover:border-green-500/30 hover:bg-secondary-bg/30 transition-all shadow-sm cursor-pointer block">
                            <div
                                class="w-12 h-12 rounded-xl bg-green-500/10 text-green-400 flex items-center justify-center text-xl mb-3 mx-auto">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <h3 class="text-3xl font-bold text-text-main">
                                {{ auth()->user()->applications()->where('status', 'shortlisted')->count() }}</h3>
                            <p class="text-xs font-semibold text-text-dark/50 uppercase tracking-wide mt-1">Shortlisted</p>
                        </a>
                    </div>

                    {{-- Financial & Pending Charges --}}
                    @if($profile->pending_amount > 0)
                        <div class="bg-blue-50/50 border border-blue-200/50 rounded-2xl p-6 flex items-center justify-between shadow-sm reveal reveal-delay-2">
                            <div>
                                <h3 class="text-lg font-bold text-blue-800 flex items-center gap-2">
                                    <i class="fas fa-info-circle"></i> Pending Final Registration Fee
                                </h3>
                                <p class="text-sm text-blue-700/80 mt-1">
                                    You have a pending balance of <strong>₹{{ number_format($profile->pending_amount, 0) }}</strong> for your Standard Plan.
                                    <br>
                                    <span class="text-xs opacity-90 block mt-1"><i class="fas fa-clock mr-1"></i> This amount will be requested by the Admin upon successful job placement / final registration.</span>
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Recent Notifications --}}
                    <div
                        class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-sm reveal reveal-delay-2">
                        <div class="px-6 py-4 border-b border-card-border flex justify-between items-center bg-secondary-bg/30">
                            <h3 class="font-bold text-text-main flex items-center gap-2"><i
                                    class="fas fa-bell text-accent-yellow"></i> Notifications & Updates</h3>
                        </div>
                        <div class="divide-y divide-card-border">
                            <div class="p-5 flex gap-4 hover:bg-secondary-bg/30 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-text-main mb-1">Registration Complete</h4>
                                    <p class="text-xs text-text-dark/70 leading-relaxed">Your profile, agreement, and payment
                                        have been verified. You can now apply to unlimited jobs.</p>
                                    <span
                                        class="text-[10px] text-text-dark/40 font-medium mt-2 block">{{ $profile->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="p-5 flex gap-4 hover:bg-secondary-bg/30 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-full bg-accent-blue/10 text-accent-blue flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-search"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-text-main mb-1">New Jobs Available</h4>
                                    <p class="text-xs text-text-dark/70 leading-relaxed">We have added new teaching
                                        opportunities that match your preferred location and subject.</p>
                                    <span class="text-[10px] text-text-dark/40 font-medium mt-2 block">1 day ago</span>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-3 border-t border-card-border bg-secondary-bg/30 text-center">
                            <a href="#" class="text-xs font-semibold text-accent-blue hover:text-accent-blue-hover">View All
                                Notifications</a>
                        </div>
                    </div>

                </div>

                {{-- Right Column: Plan Details --}}
                <div class="space-y-6 reveal reveal-delay-3">
                    <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-sm relative">
                        <div
                            class="px-6 py-5 border-b border-card-border {{ $profile->plan_type === 'premium' ? 'bg-gradient-to-r from-accent-yellow/20 to-transparent border-accent-yellow/30' : 'bg-secondary-bg/30' }}">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-text-main flex items-center gap-2">
                                    <i
                                        class="fas fa-star {{ $profile->plan_type === 'premium' ? 'text-accent-yellow' : 'text-text-dark/40' }}"></i>
                                    Current Plan
                                </h3>
                                @if($isHired)
                                    <span class="px-3 py-1 bg-accent-blue/10 text-accent-blue text-[10px] font-bold uppercase tracking-wider rounded-lg border border-accent-blue/20">Completed</span>
                                @elseif($isExpired)
                                    <span class="px-3 py-1 bg-red-500/10 text-red-500 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-red-500/20">Expired</span>
                                @else
                                    <span class="px-3 py-1 bg-green-500/10 text-green-400 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-green-500/20">Active</span>
                                @endif
                            </div>
                            <div class="mt-4">
                                <span
                                    class="text-3xl font-black text-text-main capitalize">{{ $profile->plan_type ?? 'Standard' }}</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <ul class="space-y-3 mb-6">
                                <li class="flex items-start gap-3 text-sm text-text-dark/70">
                                    <i class="fas fa-check text-green-400 mt-1"></i> Apply to all available jobs
                                </li>
                                <li class="flex items-start gap-3 text-sm text-text-dark/70">
                                    <i class="fas fa-check text-green-400 mt-1"></i> Profile visibility to schools
                                </li>
                                <li class="flex items-start gap-3 text-sm text-text-dark/70">
                                    <i class="fas fa-check text-green-400 mt-1"></i> Standard placement support
                                </li>
                                @if($profile->plan_type === 'premium')
                                    <li class="flex items-start gap-3 text-sm text-text-main font-semibold">
                                        <i class="fas fa-check text-accent-yellow mt-1"></i> Dedicated Relationship Manager
                                    </li>
                                    <li class="flex items-start gap-3 text-sm text-text-main font-semibold">
                                        <i class="fas fa-check text-accent-yellow mt-1"></i> Guaranteed Interviews
                                    </li>
                                    <li class="flex items-start gap-3 text-sm text-text-main font-semibold">
                                        <i class="fas fa-check text-accent-yellow mt-1"></i> Resume Building Assistance
                                    </li>
                                @else
                                    <li class="flex items-start gap-3 text-sm text-text-dark/40">
                                        <i class="fas fa-times text-red-400/50 mt-1"></i> Dedicated Relationship Manager
                                    </li>
                                    <li class="flex items-start gap-3 text-sm text-text-dark/40">
                                        <i class="fas fa-times text-red-400/50 mt-1"></i> Guaranteed Interviews
                                    </li>
                                @endif
                            </ul>

                            @if($isHired)
                                <div class="pt-4 border-t border-card-border text-center">
                                    <span class="inline-block px-4 py-2 bg-green-500/10 text-green-400 font-bold text-xs rounded-lg border border-green-500/20">
                                        <i class="fas fa-trophy mr-1"></i> Congratulations! You are placed.
                                    </span>
                                </div>
                            @elseif($isExpired)
                                <div class="pt-4 border-t border-card-border">
                                    <p class="text-xs text-text-dark/60 mb-3 text-center">Your plan has expired. Renew to get more applications.</p>
                                    <a href="{{ route('candidate.payment.show', ['type' => 'renewal']) }}"
                                        class="block w-full py-3 bg-red-50 text-red-600 font-bold text-sm text-center rounded-xl hover:bg-red-100 transition-all border border-red-200">
                                        <i class="fas fa-sync-alt mr-1"></i> Renew Plan
                                    </a>
                                </div>
                            @elseif($limitReached && $hasActiveApplications)
                                <div class="pt-4 border-t border-card-border text-center">
                                    <p class="text-xs text-text-dark/60 mb-3 text-center">You've reached your application limit, but your applications are currently in progress. Please wait for the results.</p>
                                    <span class="inline-block px-4 py-2 bg-accent-yellow/10 text-accent-yellow font-bold text-xs rounded-lg border border-accent-yellow/20">
                                        <i class="fas fa-spinner fa-spin mr-1"></i> Applications Under Review
                                    </span>
                                </div>
                            @elseif($profile->plan_type !== 'premium')
                                <div class="pt-4 border-t border-card-border">
                                    <p class="text-xs text-text-dark/60 mb-3 text-center">Get more opportunities and faster
                                        placements with Premium.</p>
                                    <a href="{{ route('candidate.payment.show') }}"
                                        class="block w-full py-3 bg-gradient-to-r from-accent-yellow to-yellow-500 text-[#031b4e] font-bold text-sm text-center rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                        <i class="fas fa-rocket mr-1"></i> Upgrade to Premium
                                    </a>
                                </div>
                            @else
                                <div class="pt-4 border-t border-card-border text-center">
                                    <span
                                        class="inline-block px-4 py-2 bg-accent-yellow/10 text-accent-yellow font-bold text-xs rounded-lg border border-accent-yellow/20">
                                        <i class="fas fa-crown mr-1"></i> You are on the best plan!
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @else
            {{-- ================= PENDING REGISTRATION DASHBOARD ================= --}}

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10 reveal">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-blue to-accent-blue/60 text-white flex items-center justify-center text-xl font-bold shadow-lg">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-text-main">Welcome, {{ auth()->user()->name }}</h1>
                        <p class="text-sm text-text-dark/50 mt-0.5">Complete your registration to unlock job applications.</p>
                    </div>
                </div>
                <div>
                    @if($profile->is_agreement_signed)
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-accent-blue/10 text-accent-blue border border-accent-blue/20">
                            <i class="fas fa-clock mr-2"></i> Payment Pending
                        </span>
                    @elseif($profile->is_profile_complete)
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-accent-blue/10 text-accent-blue border border-accent-blue/20">
                            <i class="fas fa-file-signature mr-2"></i> Agreement Pending
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-accent-yellow/10 text-accent-yellow border border-accent-yellow/20">
                            <i class="fas fa-exclamation-circle mr-2"></i> Registration Pending
                        </span>
                    @endif
                </div>
            </div>

            {{-- Progress Bar --}}
            @php
                $completedSteps = 0;
                if ($profile->is_profile_complete)
                    $completedSteps++;
                if ($profile->is_agreement_signed)
                    $completedSteps++;
                if ($profile->is_fee_paid)
                    $completedSteps++;
                $progressPercent = ($completedSteps / 3) * 100;
            @endphp
            <div class="mb-10 reveal reveal-delay-1">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-text-main">Registration Progress</span>
                    <span class="text-sm font-bold text-accent-blue">{{ $completedSteps }}/3 Completed</span>
                </div>
                <div class="w-full h-2.5 bg-card-border rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-accent-blue to-accent-yellow rounded-full transition-all duration-700 ease-out"
                        style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>

            {{-- Step Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                {{-- Step 1: Profile --}}
                <div
                    class="bg-card-bg rounded-2xl border transition-all duration-300 hover:shadow-xl group overflow-hidden reveal reveal-delay-1
                        {{ $profile->is_profile_complete ? 'border-green-500/20' : 'border-accent-blue/30 shadow-[0_0_20px_rgba(var(--theme-accent-blue-rgb,18,154,239),0.08)]' }}">
                    <div class="px-6 pt-5 pb-0 flex items-center justify-between">
                        <span
                            class="text-[10px] font-bold uppercase tracking-widest {{ $profile->is_profile_complete ? 'text-green-400' : 'text-accent-blue' }}">Step
                            1</span>
                        @if($profile->is_profile_complete)
                            <span
                                class="w-6 h-6 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center text-xs"><i
                                    class="fas fa-check"></i></span>
                        @else
                            <span
                                class="w-6 h-6 rounded-full bg-accent-blue/10 text-accent-blue flex items-center justify-center text-[10px] font-bold">1</span>
                        @endif
                    </div>
                    <div class="p-6 flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 rounded-2xl {{ $profile->is_profile_complete ? 'bg-green-500/10 text-green-400' : 'bg-accent-blue/10 text-accent-blue' }} flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                            <i class="fas {{ $profile->is_profile_complete ? 'fa-check-circle' : 'fa-user-edit' }}"></i>
                        </div>
                        <h3 class="font-bold text-text-main mb-1.5 text-lg">Complete Profile</h3>
                        <p class="text-sm text-text-dark/50 mb-6 leading-relaxed">Fill in your professional details,
                            qualifications, and experience</p>
                        @if($profile->is_profile_complete)
                            <a href="{{ route('candidate.profile.edit') }}"
                                class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-semibold border border-green-500/20 text-green-400 hover:bg-green-500/5 transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-pen text-xs"></i> Edit Profile
                            </a>
                        @else
                            <a href="{{ route('candidate.wizard') }}"
                                class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-semibold bg-accent-blue text-white hover:bg-accent-blue-hover hover:-translate-y-0.5 shadow-lg transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-arrow-right text-xs"></i> Complete Profile
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Step 2: Agreement --}}
                <div
                    class="bg-card-bg rounded-2xl border transition-all duration-300 hover:shadow-xl group overflow-hidden reveal reveal-delay-2
                        {{ $profile->is_agreement_signed ? 'border-green-500/20' : ($profile->is_profile_complete ? 'border-accent-blue/30 shadow-[0_0_20px_rgba(var(--theme-accent-blue-rgb,18,154,239),0.08)]' : 'border-card-border opacity-50') }}">
                    <div class="px-6 pt-5 pb-0 flex items-center justify-between">
                        <span
                            class="text-[10px] font-bold uppercase tracking-widest {{ $profile->is_agreement_signed ? 'text-green-400' : ($profile->is_profile_complete ? 'text-accent-blue' : 'text-text-dark/30') }}">Step
                            2</span>
                        @if($profile->is_agreement_signed)
                            <span
                                class="w-6 h-6 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center text-xs"><i
                                    class="fas fa-check"></i></span>
                        @else
                            <span
                                class="w-6 h-6 rounded-full {{ $profile->is_profile_complete ? 'bg-accent-blue/10 text-accent-blue' : 'bg-card-border text-text-dark/30' }} flex items-center justify-center text-[10px] font-bold">2</span>
                        @endif
                    </div>
                    <div class="p-6 flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 rounded-2xl {{ $profile->is_agreement_signed ? 'bg-green-500/10 text-green-400' : ($profile->is_profile_complete ? 'bg-accent-blue/10 text-accent-blue' : 'bg-card-border/50 text-text-dark/20') }} flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                            <i class="fas {{ $profile->is_agreement_signed ? 'fa-check-circle' : 'fa-file-signature' }}"></i>
                        </div>
                        <h3 class="font-bold text-text-main mb-1.5 text-lg">Sign Agreement</h3>
                        <p class="text-sm text-text-dark/50 mb-6 leading-relaxed">Review and digitally sign the placement terms
                            & conditions</p>
                        @if($profile->is_agreement_signed)
                            <a href="{{ route('candidate.agreement.show') }}"
                                class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-semibold border border-green-500/20 text-green-400 hover:bg-green-500/5 transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-eye text-xs"></i> View Agreement
                            </a>
                        @elseif($profile->is_profile_complete)
                            <a href="{{ route('candidate.wizard') }}"
                                class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-semibold bg-accent-blue text-white hover:bg-accent-blue-hover hover:-translate-y-0.5 shadow-lg transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-arrow-right text-xs"></i> Review & Sign
                            </a>
                        @else
                            <button disabled
                                class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-semibold bg-card-border/50 text-text-dark/30 cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fas fa-lock text-xs"></i> Locked
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Step 3: Payment --}}
                <div
                    class="bg-card-bg rounded-2xl border transition-all duration-300 hover:shadow-xl group overflow-hidden reveal reveal-delay-3
                        {{ $profile->initial_fee_paid ? 'border-green-500/20' : ($profile->is_agreement_signed ? 'border-accent-yellow/30 shadow-[0_0_20px_rgba(255,184,0,0.08)]' : 'border-card-border opacity-50') }}">
                    <div class="px-6 pt-5 pb-0 flex items-center justify-between">
                        <span
                            class="text-[10px] font-bold uppercase tracking-widest {{ $profile->initial_fee_paid ? 'text-green-400' : ($profile->is_agreement_signed ? 'text-accent-yellow' : 'text-text-dark/30') }}">Step
                            3</span>
                        @if($profile->initial_fee_paid)
                            <span
                                class="w-6 h-6 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center text-xs"><i
                                    class="fas fa-check"></i></span>
                        @else
                            <span
                                class="w-6 h-6 rounded-full {{ $profile->is_agreement_signed ? 'bg-accent-yellow/10 text-accent-yellow' : 'bg-card-border text-text-dark/30' }} flex items-center justify-center text-[10px] font-bold">3</span>
                        @endif
                    </div>
                    <div class="p-6 flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 rounded-2xl {{ $profile->is_fee_paid ? 'bg-green-500/10 text-green-400' : ($profile->is_agreement_signed ? 'bg-accent-yellow/10 text-accent-yellow' : 'bg-card-border/50 text-text-dark/20') }} flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                            <i class="fas {{ $profile->is_fee_paid ? 'fa-check-circle' : 'fa-credit-card' }}"></i>
                        </div>
                        <h3 class="font-bold text-text-main mb-1.5 text-lg">Initial Registration Fee</h3>
                        <p class="text-sm text-text-dark/50 mb-4 leading-relaxed">Please pay the initial <strong class="text-accent-yellow">₹500</strong> registration fee to activate your profile and start applying for jobs.</p>
                        @if($profile->initial_fee_paid)
                            <span
                                class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-bold bg-green-500/10 text-green-400 border border-green-500/20 flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i> Initial Payment Received
                            </span>
                        @elseif($profile->is_agreement_signed)
                            <a href="{{ route('candidate.payment.show') }}"
                                class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-semibold bg-accent-yellow text-[#031b4e] hover:brightness-110 hover:-translate-y-0.5 shadow-lg transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-arrow-right text-xs"></i> Proceed to Pay
                            </a>
                        @else
                            <button disabled
                                class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-semibold bg-card-border/50 text-text-dark/30 cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fas fa-lock text-xs"></i> Locked
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection