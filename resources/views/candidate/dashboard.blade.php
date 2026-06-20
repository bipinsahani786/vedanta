@extends('layouts.app')

@section('content')
@include('candidate.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Welcome Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10 reveal">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-blue to-accent-blue/60 text-white flex items-center justify-center text-xl font-bold shadow-lg">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-text-main">Welcome, {{ auth()->user()->name }}</h1>
                <p class="text-sm text-text-dark/50 mt-0.5">Complete your profile to unlock job applications.</p>
            </div>
        </div>
        <div>
            @if($profile->is_fee_paid)
                <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-green-500/10 text-green-400 border border-green-500/20">
                    <i class="fas fa-check-circle mr-2"></i> Fully Registered
                </span>
            @elseif($profile->is_agreement_signed)
                <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-accent-blue/10 text-accent-blue border border-accent-blue/20">
                    <i class="fas fa-clock mr-2"></i> Payment Pending
                </span>
            @elseif($profile->is_profile_complete)
                <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-accent-blue/10 text-accent-blue border border-accent-blue/20">
                    <i class="fas fa-file-signature mr-2"></i> Agreement Pending
                </span>
            @else
                <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-accent-yellow/10 text-accent-yellow border border-accent-yellow/20">
                    <i class="fas fa-exclamation-circle mr-2"></i> Registration Pending
                </span>
            @endif
        </div>
    </div>

    {{-- Progress Bar --}}
    @php
        $completedSteps = 0;
        if($profile->is_profile_complete) $completedSteps++;
        if($profile->is_agreement_signed) $completedSteps++;
        if($profile->is_fee_paid) $completedSteps++;
        $progressPercent = ($completedSteps / 3) * 100;
    @endphp
    <div class="mb-10 reveal reveal-delay-1">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-semibold text-text-main">Registration Progress</span>
            <span class="text-sm font-bold text-accent-blue">{{ $completedSteps }}/3 Completed</span>
        </div>
        <div class="w-full h-2.5 bg-card-border rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-accent-blue to-accent-yellow rounded-full transition-all duration-700 ease-out" style="width: {{ $progressPercent }}%"></div>
        </div>
    </div>

    {{-- Step Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        {{-- Step 1: Profile --}}
        <div class="bg-card-bg rounded-2xl border transition-all duration-300 hover:shadow-xl group overflow-hidden reveal reveal-delay-1
            {{ $profile->is_profile_complete ? 'border-green-500/20' : 'border-accent-blue/30 shadow-[0_0_20px_rgba(var(--theme-accent-blue-rgb,18,154,239),0.08)]' }}">
            {{-- Step Number Ribbon --}}
            <div class="px-6 pt-5 pb-0 flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest {{ $profile->is_profile_complete ? 'text-green-400' : 'text-accent-blue' }}">Step 1</span>
                @if($profile->is_profile_complete)
                    <span class="w-6 h-6 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center text-xs"><i class="fas fa-check"></i></span>
                @else
                    <span class="w-6 h-6 rounded-full bg-accent-blue/10 text-accent-blue flex items-center justify-center text-[10px] font-bold">1</span>
                @endif
            </div>
            <div class="p-6 flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-2xl {{ $profile->is_profile_complete ? 'bg-green-500/10 text-green-400' : 'bg-accent-blue/10 text-accent-blue' }} flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                    <i class="fas {{ $profile->is_profile_complete ? 'fa-check-circle' : 'fa-user-edit' }}"></i>
                </div>
                <h3 class="font-bold text-text-main mb-1.5 text-lg">Complete Profile</h3>
                <p class="text-sm text-text-dark/50 mb-6 leading-relaxed">Fill in your professional details, qualifications, and experience</p>
                @if($profile->is_profile_complete)
                    <a href="{{ route('candidate.profile.edit') }}" class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-semibold border border-green-500/20 text-green-400 hover:bg-green-500/5 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-pen text-xs"></i> Edit Profile
                    </a>
                @else
                    <a href="{{ route('candidate.profile.edit') }}" class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-semibold bg-accent-blue text-white hover:bg-accent-blue-hover hover:-translate-y-0.5 shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-right text-xs"></i> Complete Profile
                    </a>
                @endif
            </div>
        </div>

        {{-- Step 2: Agreement --}}
        <div class="bg-card-bg rounded-2xl border transition-all duration-300 hover:shadow-xl group overflow-hidden reveal reveal-delay-2
            {{ $profile->is_agreement_signed ? 'border-green-500/20' : ($profile->is_profile_complete ? 'border-accent-blue/30 shadow-[0_0_20px_rgba(var(--theme-accent-blue-rgb,18,154,239),0.08)]' : 'border-card-border opacity-50') }}">
            <div class="px-6 pt-5 pb-0 flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest {{ $profile->is_agreement_signed ? 'text-green-400' : ($profile->is_profile_complete ? 'text-accent-blue' : 'text-text-dark/30') }}">Step 2</span>
                @if($profile->is_agreement_signed)
                    <span class="w-6 h-6 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center text-xs"><i class="fas fa-check"></i></span>
                @else
                    <span class="w-6 h-6 rounded-full {{ $profile->is_profile_complete ? 'bg-accent-blue/10 text-accent-blue' : 'bg-card-border text-text-dark/30' }} flex items-center justify-center text-[10px] font-bold">2</span>
                @endif
            </div>
            <div class="p-6 flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-2xl {{ $profile->is_agreement_signed ? 'bg-green-500/10 text-green-400' : ($profile->is_profile_complete ? 'bg-accent-blue/10 text-accent-blue' : 'bg-card-border/50 text-text-dark/20') }} flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                    <i class="fas {{ $profile->is_agreement_signed ? 'fa-check-circle' : 'fa-file-signature' }}"></i>
                </div>
                <h3 class="font-bold text-text-main mb-1.5 text-lg">Sign Agreement</h3>
                <p class="text-sm text-text-dark/50 mb-6 leading-relaxed">Review and digitally sign the placement terms & conditions</p>
                @if($profile->is_agreement_signed)
                    <a href="{{ route('candidate.agreement.download') }}" class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-semibold border border-green-500/20 text-green-400 hover:bg-green-500/5 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-download text-xs"></i> Download PDF
                    </a>
                @elseif($profile->is_profile_complete)
                    <a href="{{ route('candidate.agreement.show') }}" class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-semibold bg-accent-blue text-white hover:bg-accent-blue-hover hover:-translate-y-0.5 shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-right text-xs"></i> Review & Sign
                    </a>
                @else
                    <button disabled class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-semibold bg-card-border/50 text-text-dark/30 cursor-not-allowed flex items-center justify-center gap-2">
                        <i class="fas fa-lock text-xs"></i> Locked
                    </button>
                @endif
            </div>
        </div>

        {{-- Step 3: Payment --}}
        <div class="bg-card-bg rounded-2xl border transition-all duration-300 hover:shadow-xl group overflow-hidden reveal reveal-delay-3
            {{ $profile->is_fee_paid ? 'border-green-500/20' : ($profile->is_agreement_signed ? 'border-accent-yellow/30 shadow-[0_0_20px_rgba(255,184,0,0.08)]' : 'border-card-border opacity-50') }}">
            <div class="px-6 pt-5 pb-0 flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest {{ $profile->is_fee_paid ? 'text-green-400' : ($profile->is_agreement_signed ? 'text-accent-yellow' : 'text-text-dark/30') }}">Step 3</span>
                @if($profile->is_fee_paid)
                    <span class="w-6 h-6 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center text-xs"><i class="fas fa-check"></i></span>
                @else
                    <span class="w-6 h-6 rounded-full {{ $profile->is_agreement_signed ? 'bg-accent-yellow/10 text-accent-yellow' : 'bg-card-border text-text-dark/30' }} flex items-center justify-center text-[10px] font-bold">3</span>
                @endif
            </div>
            <div class="p-6 flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-2xl {{ $profile->is_fee_paid ? 'bg-green-500/10 text-green-400' : ($profile->is_agreement_signed ? 'bg-accent-yellow/10 text-accent-yellow' : 'bg-card-border/50 text-text-dark/20') }} flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                    <i class="fas {{ $profile->is_fee_paid ? 'fa-check-circle' : 'fa-credit-card' }}"></i>
                </div>
                <h3 class="font-bold text-text-main mb-1.5 text-lg">Make Payment</h3>
                <p class="text-sm text-text-dark/50 mb-6 leading-relaxed">Choose your plan and complete the registration fee payment</p>
                @if($profile->is_fee_paid)
                    <span class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-bold bg-green-500/10 text-green-400 border border-green-500/20 flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Payment Received
                    </span>
                @elseif($profile->is_agreement_signed)
                    <a href="{{ route('candidate.payment.show') }}" class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-semibold bg-accent-yellow text-[#031b4e] hover:brightness-110 hover:-translate-y-0.5 shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-right text-xs"></i> Proceed to Pay
                    </a>
                @else
                    <button disabled class="mt-auto w-full px-4 py-3 rounded-xl text-sm font-semibold bg-card-border/50 text-text-dark/30 cursor-not-allowed flex items-center justify-center gap-2">
                        <i class="fas fa-lock text-xs"></i> Locked
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Info Cards (only show when fully registered) --}}
    @if($profile->is_fee_paid)
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 reveal reveal-delay-2">
        <div class="bg-card-bg rounded-2xl border border-card-border p-6 flex items-center gap-4 hover:border-accent-blue/30 transition-all">
            <div class="w-12 h-12 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-lg">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-text-main">{{ auth()->user()->applications()->count() }}</p>
                <p class="text-xs text-text-dark/50 mt-0.5">Applications Sent</p>
            </div>
        </div>
        <div class="bg-card-bg rounded-2xl border border-card-border p-6 flex items-center gap-4 hover:border-green-500/30 transition-all">
            <div class="w-12 h-12 rounded-xl bg-green-500/10 text-green-400 flex items-center justify-center text-lg">
                <i class="fas fa-check-double"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-text-main">{{ auth()->user()->applications()->where('status', 'shortlisted')->count() }}</p>
                <p class="text-xs text-text-dark/50 mt-0.5">Shortlisted</p>
            </div>
        </div>
        <div class="bg-card-bg rounded-2xl border border-card-border p-6 flex items-center gap-4 hover:border-accent-yellow/30 transition-all">
            <div class="w-12 h-12 rounded-xl bg-accent-yellow/10 text-accent-yellow flex items-center justify-center text-lg">
                <i class="fas fa-briefcase"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-text-main">{{ \App\Models\JobPost::where('status', 'approved')->count() }}</p>
                <p class="text-xs text-text-dark/50 mt-0.5">Active Jobs</p>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
