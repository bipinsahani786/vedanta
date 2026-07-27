@extends('layouts.app')

@section('content')
@include('candidate.partials.nav')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Page Header --}}
    <div class="text-center mb-10 reveal">
        <div class="w-14 h-14 rounded-2xl bg-accent-yellow/10 text-accent-yellow flex items-center justify-center text-2xl mx-auto mb-4">
            <i class="fas fa-crown"></i>
        </div>
        <h1 class="text-2xl font-bold text-text-main">Select Your Membership Plan</h1>
        <p class="text-sm text-text-dark/50 mt-2 max-w-md mx-auto">Choose a plan that best fits your job search needs. Pay securely using PhonePe.</p>
    </div>

    @if(session('error'))
        <div class="mb-8 bg-red-500/10 border border-red-500/30 p-4 rounded-xl flex items-center gap-3 text-center justify-center reveal">
            <i class="fas fa-exclamation-circle text-red-400"></i>
            <span class="text-sm text-red-400 font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Plans Grid --}}

    {{-- Already on Premium — Best plan message --}}
    @if(!$isRenewal && $profile->plan_type === 'premium' && ($profile->initial_fee_paid || $profile->is_fee_paid))
    <div class="max-w-md mx-auto mb-10">
        <div class="bg-card-bg rounded-2xl border-2 border-accent-yellow/40 p-8 text-center shadow-xl relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-24 h-24 bg-accent-yellow/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-12 -left-12 w-24 h-24 bg-accent-yellow/5 rounded-full blur-2xl"></div>
            
            <div class="relative z-10">
                <div class="w-20 h-20 rounded-2xl bg-accent-yellow/10 text-accent-yellow flex items-center justify-center text-4xl mx-auto mb-5">
                    <i class="fas fa-crown"></i>
                </div>
                <h3 class="text-2xl font-bold text-text-main mb-2">You're on the Premium Plan!</h3>
                <p class="text-sm text-text-dark/60 mb-6">You already have the best plan with all features unlocked. No upgrade needed.</p>
                
                <div class="bg-accent-yellow/5 border border-accent-yellow/20 rounded-xl p-4 mb-6">
                    <ul class="space-y-3 text-left">
                        <li class="flex items-center gap-3 text-sm text-text-main">
                            <i class="fas fa-check text-accent-yellow"></i> Priority application processing
                        </li>
                        <li class="flex items-center gap-3 text-sm text-text-main">
                            <i class="fas fa-check text-accent-yellow"></i> Profile highlighted to employers
                        </li>
                        <li class="flex items-center gap-3 text-sm text-text-main">
                            <i class="fas fa-check text-accent-yellow"></i> Dedicated Relationship Manager
                        </li>
                        <li class="flex items-center gap-3 text-sm text-text-main">
                            <i class="fas fa-check text-accent-yellow"></i> Guaranteed Interviews
                        </li>
                    </ul>
                </div>

                <span class="inline-block px-6 py-3 bg-accent-yellow/10 text-accent-yellow font-bold text-sm rounded-xl border border-accent-yellow/20">
                    <i class="fas fa-star mr-1"></i> Active Premium Member
                </span>

                <div class="mt-6">
                    <a href="{{ route('candidate.dashboard') }}" class="text-sm text-accent-blue hover:text-accent-blue-hover font-semibold">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto mb-10">

        {{-- Standard Plan --}}
        <div class="bg-card-bg rounded-2xl border border-card-border p-8 flex flex-col hover:border-accent-blue/30 hover:shadow-xl transition-all duration-300 group reveal reveal-delay-1 relative overflow-hidden">
            {{-- Decorative --}}
            <div class="absolute -top-12 -right-12 w-24 h-24 bg-accent-blue/5 rounded-full blur-2xl"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-lg">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="text-lg font-bold text-text-main">Standard Plan</h3>
                </div>

                <div class="mb-6 pb-6 border-b border-card-border">
                    <span class="text-4xl font-extrabold text-text-main">₹500</span>
                    <span class="text-sm text-text-dark/40 ml-1">/ Initially</span>
                </div>

                <ul class="space-y-4 mb-8 flex-grow">
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center text-[10px] shrink-0 mt-0.5"><i class="fas fa-check"></i></span>
                        <span class="text-sm text-text-dark/60">Profile visible to all schools</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center text-[10px] shrink-0 mt-0.5"><i class="fas fa-check"></i></span>
                        <span class="text-sm text-text-dark/60">Apply to standard job postings</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center text-[10px] shrink-0 mt-0.5"><i class="fas fa-check"></i></span>
                        <span class="text-sm text-text-dark/60">Email alerts for matching jobs</span>
                    </li>
                    <li class="flex items-start gap-3 opacity-80">
                        <span class="w-5 h-5 rounded-full bg-accent-yellow/10 text-accent-yellow flex items-center justify-center text-[10px] shrink-0 mt-0.5"><i class="fas fa-info"></i></span>
                        <span class="text-sm text-text-main font-medium">Final ₹500 required upon job placement</span>
                    </li>
                    <li class="flex items-start gap-3 opacity-40">
                        <span class="w-5 h-5 rounded-full bg-red-500/10 text-red-400 flex items-center justify-center text-[10px] shrink-0 mt-0.5"><i class="fas fa-times"></i></span>
                        <span class="text-sm text-text-dark/60">Dedicated Relationship Manager</span>
                    </li>
                </ul>

                @if(!$isRenewal && $profile->plan_type === 'standard' && ($profile->initial_fee_paid || $profile->is_fee_paid))
                    <button type="button" disabled class="w-full py-3.5 border border-green-500/50 text-green-500 font-semibold rounded-xl bg-green-500/10 flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Current Plan
                    </button>
                @else
                    <form action="{{ route('candidate.payment.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="{{ $isRenewal ? 'renewal_basic' : 'basic' }}">
                        <button type="submit" class="w-full py-3.5 border border-card-border text-text-main font-semibold rounded-xl hover:bg-accent-blue/10 hover:border-accent-blue/30 transition-all flex items-center justify-center gap-2 group-hover:border-accent-blue/30">
                            {{ $isRenewal ? 'Renew with Standard Plan' : 'Select Standard Plan' }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Premium Plan --}}
        <div class="bg-card-bg rounded-2xl border-2 border-accent-yellow/40 p-8 flex flex-col hover:shadow-2xl transition-all duration-300 group reveal reveal-delay-2 relative overflow-hidden shadow-[0_0_30px_rgba(255,184,0,0.08)]">
            {{-- Recommended Badge --}}
            <div class="absolute top-0 right-0 bg-accent-yellow text-[#031b4e] text-[10px] font-bold px-4 py-1.5 rounded-bl-xl uppercase tracking-wider">
                <i class="fas fa-star mr-1"></i> Recommended
            </div>

            {{-- Decorative --}}
            <div class="absolute -top-12 -right-12 w-24 h-24 bg-accent-yellow/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-12 -left-12 w-24 h-24 bg-accent-yellow/5 rounded-full blur-2xl"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-accent-yellow/10 text-accent-yellow flex items-center justify-center text-lg">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h3 class="text-lg font-bold text-text-main">Premium Plan</h3>
                </div>

                <div class="mb-6 pb-6 border-b border-card-border">
                    @if(!$isRenewal && $profile->plan_type === 'standard' && ($profile->initial_fee_paid || $profile->is_fee_paid))
                        <span class="text-4xl font-extrabold text-accent-yellow">₹500</span>
                        <span class="text-sm text-text-dark/40 ml-1">/ Upgrade (₹1000 - ₹500 paid)</span>
                    @else
                        <span class="text-4xl font-extrabold text-accent-yellow">₹1000</span>
                        <span class="text-sm text-text-dark/40 ml-1">/ One Time</span>
                    @endif
                </div>

                <ul class="space-y-4 mb-8 flex-grow">
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center text-[10px] shrink-0 mt-0.5"><i class="fas fa-check"></i></span>
                        <span class="text-sm text-text-main font-medium">Priority application processing</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center text-[10px] shrink-0 mt-0.5"><i class="fas fa-check"></i></span>
                        <span class="text-sm text-text-dark/60">Profile highlighted to employers</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center text-[10px] shrink-0 mt-0.5"><i class="fas fa-check"></i></span>
                        <span class="text-sm text-text-dark/60">Apply to premium & featured jobs</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center text-[10px] shrink-0 mt-0.5"><i class="fas fa-check"></i></span>
                        <span class="text-sm text-text-dark/60">Dedicated Relationship Manager</span>
                    </li>
                </ul>

                <form action="{{ route('candidate.payment.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="{{ $isRenewal ? 'renewal_premium' : ($profile->plan_type === 'standard' && ($profile->initial_fee_paid || $profile->is_fee_paid) ? 'upgrade' : 'premium') }}">
                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-accent-yellow to-yellow-500 text-[#031b4e] font-bold rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        @if($isRenewal)
                            Renew with Premium Plan (₹1000)
                        @elseif($profile->plan_type === 'standard' && ($profile->initial_fee_paid || $profile->is_fee_paid))
                            Upgrade to Premium (Pay ₹500)
                        @else
                            Select Premium Plan (₹1000)
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Secure Payment Note --}}
    <div class="text-center reveal reveal-delay-3">
        <div class="inline-flex items-center gap-2 bg-card-bg border border-card-border px-5 py-2.5 rounded-full text-sm text-text-dark/40">
            <i class="fas fa-lock text-green-400 text-xs"></i>
            Secure payments processed by <strong class="text-purple-400 ml-0.5">PhonePe</strong>
        </div>
    </div>
</div>
@endsection
