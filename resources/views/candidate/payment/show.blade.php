@extends('layouts.candidate')

@section('candidate_content')
@php
    $canUpgrade = ($profile->plan_type === 'standard' && ($profile->initial_fee_paid || ($profile->paid_amount ?? 0) >= 500) && !$profile->is_fee_paid);
    $basicCode = $isRenewal ? 'renewal_basic' : 'basic';
    $premiumCode = $canUpgrade ? 'upgrade' : ($isRenewal ? 'renewal_premium' : 'premium');
    $basicCost = 500;
    $premiumCost = $canUpgrade ? 500 : 1000;
    $upgradeDifference = $premiumCost;
@endphp

<div class="space-y-6 pb-10" x-data="{
    planFlowOpen: false,
    flowStep: 1,
    selectedPlan: '{{ $premiumCode }}',
    selectedAmount: {{ $premiumCost }},
    selectedTitle: '{{ $canUpgrade ? 'Premium Upgrade' : 'Premium Registration' }}',

    openFlow(step = 1, plan = 'premium') {
        this.flowStep = step;
        this.choosePlan(plan);
        this.planFlowOpen = true;
    },
    choosePlan(type) {
        if (type === 'basic' || type === 'standard' || type === 'renewal_basic') {
            this.selectedPlan = '{{ $basicCode }}';
            this.selectedAmount = {{ $basicCost }};
            this.selectedTitle = 'Standard Registration';
        } else {
            this.selectedPlan = '{{ $premiumCode }}';
            this.selectedAmount = {{ $premiumCost }};
            this.selectedTitle = '{{ $canUpgrade ? 'Premium Upgrade' : 'Premium Registration' }}';
        }
    },
    selectAndConfirm(type) {
        this.choosePlan(type);
        this.flowStep = 3;
    },
    selectStandardAndDownsell() {
        this.choosePlan('basic');
        this.flowStep = 2;
    },
    closeFlow() {
        this.planFlowOpen = false;
    }
}">

    {{-- Header & Breadcrumbs --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Payment & Plan</h1>
            <div class="flex items-center gap-2 text-xs text-slate-400 mt-1">
                <a href="{{ route('candidate.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                <span class="text-slate-600">&rsaquo;</span>
                <span class="text-white font-medium">Payment & Plan</span>
            </div>
        </div>

        @if(session('error'))
            <div class="bg-red-500/15 border border-red-500/30 text-red-300 text-xs px-4 py-2 rounded-xl flex items-center gap-2 shadow-md">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('success'))
            <div class="bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs px-4 py-2 rounded-xl flex items-center gap-2 shadow-md">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
    </div>

    {{-- Top Overview Metrics (Row of 4 Cards) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Current Plan --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-purple-500/40 transition-all group">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-2xl bg-purple-500/15 border border-purple-500/25 text-purple-400 flex items-center justify-center text-lg shadow-sm group-hover:scale-105 transition-transform">
                        <i class="fas fa-crown"></i>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                        Active
                    </span>
                </div>
                <div class="text-[11px] text-slate-400 font-medium">Current Plan</div>
                <div class="text-lg lg:text-xl font-black text-white tracking-tight mt-0.5">
                    {{ ucfirst($profile->plan_type ?? 'Standard') }} Plan
                </div>
                <div class="text-[10px] text-slate-400 mt-1">
                    Valid till {{ $planValidityDate->format('d M Y') }}
                </div>
            </div>
            <div class="pt-3 mt-3 border-t border-white/[0.08]">
                <a href="#plans-section" class="w-full py-1.5 px-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all">
                    <span>View Plan Details</span>
                </a>
            </div>
        </div>

        {{-- Card 2: Wallet Balance --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-sky-500/40 transition-all group">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-2xl bg-sky-500/15 border border-sky-500/25 text-sky-400 flex items-center justify-center text-lg shadow-sm group-hover:scale-105 transition-transform">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="text-[11px] text-slate-400 font-medium">Wallet Balance</div>
                <div class="text-2xl font-black text-white tracking-tight mt-0.5">
                    ₹{{ number_format($walletBalanceInr, 0) }}
                </div>
                <div class="text-[10px] text-slate-400 mt-1">
                    Available Balance
                </div>
            </div>
            <div class="pt-3 mt-3 border-t border-white/[0.08]">
                <a href="{{ route('candidate.referral.index') }}" class="w-full py-1.5 px-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all">
                    <span>View Wallet</span>
                </a>
            </div>
        </div>

        {{-- Card 3: Total Paid --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-emerald-500/40 transition-all group">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 flex items-center justify-center text-lg shadow-sm group-hover:scale-105 transition-transform">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
                <div class="text-[11px] text-slate-400 font-medium">Total Paid</div>
                <div class="text-2xl font-black text-white tracking-tight mt-0.5">
                    ₹{{ number_format($totalPaidAmount, 0) }}
                </div>
                <div class="text-[10px] text-slate-400 mt-1">
                    All time payments
                </div>
            </div>
            <div class="pt-3 mt-3 border-t border-white/[0.08]">
                <a href="#payment-history-section" class="w-full py-1.5 px-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all">
                    <span>Payment History</span>
                </a>
            </div>
        </div>

        {{-- Card 4: Next Payment --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-amber-500/40 transition-all group">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-2xl bg-amber-500/15 border border-amber-500/25 text-amber-400 flex items-center justify-center text-lg shadow-sm group-hover:scale-105 transition-transform">
                        <i class="fas fa-briefcase"></i>
                    </div>
                </div>
                <div class="text-[11px] text-slate-400 font-medium">Next Payment</div>
                <div class="text-2xl font-black text-white tracking-tight mt-0.5">
                    ₹{{ number_format($nextPaymentDue, 0) }}
                </div>
                <div class="text-[10px] text-slate-400 mt-1">
                    Due payment
                </div>
            </div>
            <div class="pt-3 mt-3 border-t border-white/[0.08]">
                @if($nextPaymentDue > 0)
                    <button type="button" 
                            @click="openFlow(1, '{{ $premiumCode }}')" 
                            class="w-full py-1.5 px-3 rounded-xl bg-accent-yellow hover:brightness-110 text-slate-950 font-black text-xs flex items-center justify-center gap-1.5 transition-all shadow-[0_2px_10px_rgba(245,158,11,0.3)] cursor-pointer">
                        <i class="fas fa-credit-card text-[10px]"></i>
                        <span>Pay Now</span>
                    </button>
                @else
                    <button disabled class="w-full py-1.5 px-3 rounded-xl bg-white/5 border border-white/10 text-slate-400 font-bold text-xs flex items-center justify-center gap-1.5 cursor-not-allowed">
                        <i class="fas fa-check text-[10px] text-emerald-400"></i>
                        <span>No Dues</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- Card 5: Service Charge (After Joining) - Commented Out --}}
        {{--
        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-pink-500/40 transition-all group">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-2xl bg-pink-500/15 border border-pink-500/25 text-pink-400 flex items-center justify-center text-lg shadow-sm group-hover:scale-105 transition-transform">
                        <i class="fas fa-stopwatch"></i>
                    </div>
                </div>
                <div class="text-[11px] text-slate-400 font-medium">Service Charge (After Joining)</div>
                <div class="text-2xl font-black text-white tracking-tight mt-0.5">
                    50%
                </div>
                <div class="text-[10px] text-slate-400 mt-1">
                    Of one month salary
                </div>
            </div>
            <div class="pt-3 mt-3 border-t border-white/[0.08]">
                <a href="{{ route('candidate.agreement.show') }}" class="w-full py-1.5 px-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all">
                    <span>View Policy</span>
                </a>
            </div>
        </div>
        --}}
    </div>

    {{-- Upgrade Your Plan Section --}}
    <div id="plans-section" class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 sm:p-6 shadow-[0_4px_20px_rgba(0,0,0,0.25)]">
        <div>
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-white">
                    {{ $profile->plan_type === 'premium' ? 'Your Membership Plan' : 'Upgrade Your Plan' }}
                </h3>
                <button type="button" @click="openFlow(2, '{{ $premiumCode }}')" class="text-xs font-semibold text-accent-blue hover:text-white flex items-center gap-1.5 transition-colors cursor-pointer">
                    <i class="fas fa-sliders-h text-[10px]"></i>
                    <span>Compare Plans</span>
                </button>
            </div>

            {{-- 2 Plan Cards Side-by-Side --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Standard Plan Card --}}
                <div class="rounded-2xl border {{ $profile->plan_type === 'standard' ? 'border-accent-blue/40 bg-accent-blue/[0.04]' : 'border-white/[0.08] bg-white/[0.02]' }} p-4 flex flex-col justify-between transition-all">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-crown text-amber-400 text-sm"></i>
                            <h4 class="text-sm font-black text-white">Standard Plan</h4>
                        </div>
                        <p class="text-[10px] text-slate-400 mb-4">Standard Access & Basic Support</p>

                        <ul class="space-y-2 text-xs mb-5">
                            <li class="flex items-center gap-2 text-slate-300">
                                <i class="fas fa-check text-emerald-400 text-xs shrink-0"></i>
                                <span>Up to 2 Applications / Interviews</span>
                            </li>
                            <li class="flex items-center gap-2 text-slate-300">
                                <i class="fas fa-check text-emerald-400 text-xs shrink-0"></i>
                                <span>Standard Processing</span>
                            </li>
                            <li class="flex items-center gap-2 text-slate-300">
                                <i class="fas fa-check text-emerald-400 text-xs shrink-0"></i>
                                <span>Email Support</span>
                            </li>
                            <li class="flex items-center gap-2 text-slate-400">
                                <i class="fas fa-times text-rose-400 text-xs shrink-0"></i>
                                <span>WhatsApp Assistance</span>
                            </li>
                            <li class="flex items-center gap-2 text-slate-400">
                                <i class="fas fa-times text-rose-400 text-xs shrink-0"></i>
                                <span>Call Support</span>
                            </li>
                            <li class="flex items-center gap-2 text-slate-400">
                                <i class="fas fa-times text-rose-400 text-xs shrink-0"></i>
                                <span>Early Access to New Jobs</span>
                            </li>
                            <li class="flex items-center gap-2 text-slate-400">
                                <i class="fas fa-times text-rose-400 text-xs shrink-0"></i>
                                <span>Relationship Manager</span>
                            </li>
                            <li class="flex items-center gap-2 text-slate-300">
                                <i class="fas fa-check text-emerald-400 text-xs shrink-0"></i>
                                <span>Standard Profile Visibility</span>
                            </li>
                            <li class="flex items-center gap-2 text-slate-300">
                                <i class="fas fa-check text-emerald-400 text-xs shrink-0"></i>
                                <span>Regular Process Updates</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <div class="mb-3">
                            <span class="text-xl font-black text-white">₹500</span>
                            <span class="text-[10px] text-slate-400 ml-1">Plan Fee</span>
                        </div>

                        @if($profile->plan_type === 'standard' && ($profile->initial_fee_paid || $profile->is_fee_paid))
                            <button disabled class="w-full py-2 rounded-xl bg-accent-blue/15 border border-accent-blue/30 text-accent-blue font-bold text-xs flex items-center justify-center gap-1.5 cursor-default">
                                <i class="fas fa-check text-[10px]"></i>
                                <span>Current Plan</span>
                            </button>
                        @elseif($profile->plan_type === 'premium')
                            <button disabled class="w-full py-2 rounded-xl bg-white/5 border border-white/10 text-slate-400 font-bold text-xs flex items-center justify-center gap-1.5 cursor-default">
                                <span>Included in Premium</span>
                            </button>
                        @else
                            <button type="button" 
                                    @click="openFlow(2, 'basic')" 
                                    class="w-full py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                                <span>Select Standard</span>
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Premium Plan Card --}}
                <div class="rounded-2xl border-2 border-purple-500/40 bg-gradient-to-b from-purple-500/10 to-transparent p-4 flex flex-col justify-between relative shadow-[0_0_20px_rgba(168,85,247,0.15)]">
                    {{-- Popular Badge --}}
                    <div class="absolute -top-2.5 right-3 px-2.5 py-0.5 rounded-full bg-emerald-500 text-slate-950 font-black text-[9px] uppercase tracking-wider shadow-md">
                        Popular
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-crown text-amber-400 text-sm"></i>
                            <h4 class="text-sm font-black text-white">Premium Plan</h4>
                        </div>
                        <p class="text-[10px] text-slate-400 mb-4">Priority Access & More Benefits</p>

                        <ul class="space-y-2 text-xs mb-5">
                            <li class="flex items-center gap-2 text-white font-medium">
                                <i class="fas fa-check text-emerald-400 text-xs shrink-0"></i>
                                <span>Up to 3 Applications / Interviews</span>
                            </li>
                            <li class="flex items-center gap-2 text-white font-medium">
                                <i class="fas fa-check text-emerald-400 text-xs shrink-0"></i>
                                <span>Priority Processing</span>
                            </li>
                            <li class="flex items-center gap-2 text-white font-medium">
                                <i class="fas fa-check text-emerald-400 text-xs shrink-0"></i>
                                <span>Email + WhatsApp Support</span>
                            </li>
                            <li class="flex items-center gap-2 text-white font-medium">
                                <i class="fas fa-check text-emerald-400 text-xs shrink-0"></i>
                                <span>WhatsApp Assistance</span>
                            </li>
                            <li class="flex items-center gap-2 text-white font-medium">
                                <i class="fas fa-check text-emerald-400 text-xs shrink-0"></i>
                                <span>Priority Call Assistance</span>
                            </li>
                            <li class="flex items-center gap-2 text-white font-medium">
                                <i class="fas fa-check text-emerald-400 text-xs shrink-0"></i>
                                <span>Early Access to New Jobs</span>
                            </li>
                            <li class="flex items-center gap-2 text-white font-medium">
                                <i class="fas fa-check text-emerald-400 text-xs shrink-0"></i>
                                <span>Dedicated Relationship Manager</span>
                            </li>
                            <li class="flex items-center gap-2 text-white font-medium">
                                <i class="fas fa-check text-emerald-400 text-xs shrink-0"></i>
                                <span>Priority Profile Highlight</span>
                            </li>
                            <li class="flex items-center gap-2 text-white font-medium">
                                <i class="fas fa-check text-emerald-400 text-xs shrink-0"></i>
                                <span>Priority Process Updates</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <div class="mb-3">
                            @if($profile->plan_type === 'standard' && ($profile->initial_fee_paid || ($profile->paid_amount ?? 0) >= 500) && !$profile->is_fee_paid)
                                <span class="text-xl font-black text-purple-400">₹500</span>
                                <span class="text-[10px] text-slate-400 ml-1">Upgrade Fee (₹1,000 Plan)</span>
                            @else
                                <span class="text-xl font-black text-purple-400">₹1,000</span>
                                <span class="text-[10px] text-slate-400 ml-1">Plan Fee</span>
                            @endif
                        </div>

                        @if($profile->plan_type === 'premium')
                            <button disabled class="w-full py-2 rounded-xl bg-purple-500/15 border border-purple-500/30 text-purple-300 font-bold text-xs flex items-center justify-center gap-1.5 cursor-default">
                                <i class="fas fa-check text-[10px]"></i>
                                <span>Current Plan</span>
                            </button>
                        @else
                            <button type="button" 
                                    @click="openFlow(1, 'premium')" 
                                    class="w-full py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-black text-xs flex items-center justify-center gap-1.5 transition-all shadow-[0_4px_15px_rgba(168,85,247,0.4)] hover:-translate-y-0.5 cursor-pointer">
                                <span>{{ $canUpgrade ? 'Upgrade to Premium' : 'Upgrade to Premium' }}</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer Note --}}
            <div class="pt-4 mt-4 border-t border-white/[0.08] flex items-center gap-2 text-xs text-slate-300">
                @if($profile->plan_type === 'premium')
                    <i class="fas fa-crown text-amber-400 text-xs"></i>
                    <span class="text-emerald-300 font-medium">You are on the Premium Plan with priority access and dedicated placement support.</span>
                @else
                    <i class="fas fa-star text-amber-400 text-xs"></i>
                    <span>Upgrade to Premium Plan and get priority access to top schools.</span>
                @endif
            </div>
        </div>

    {{-- ========================================================================= --}}
    {{-- 3-STEP PAYMENT & UPSELL FLOW MODAL (Screen 1, Screen 2, Screen 3)        --}}
    {{-- ========================================================================= --}}
    <div x-show="planFlowOpen" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-3 sm:p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @keydown.escape.window="closeFlow()">
        
        <div class="bg-white text-slate-900 rounded-3xl max-w-md w-full shadow-2xl overflow-hidden relative border border-slate-100 p-5 sm:p-6 my-auto max-h-[92vh] overflow-y-auto no-scrollbar"
             @click.away="closeFlow()">
            
            {{-- Close Button (Top Right on all screens) --}}
            <button type="button" 
                    @click="closeFlow()" 
                    class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-sm transition-colors z-20 cursor-pointer"
                    aria-label="Close modal">
                <i class="fas fa-times"></i>
            </button>

            {{-- ========================================== --}}
            {{-- SCREEN 1: UPSELL / RECOMMENDED FOR YOU     --}}
            {{-- ========================================== --}}
            <div x-show="flowStep === 1" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0">
                
                {{-- Recommended Pill --}}
                <div class="text-center pt-1">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#4f28d9] text-white text-[10px] sm:text-xs font-black tracking-wider uppercase shadow-sm">
                        <i class="fas fa-crown text-[10px] text-amber-300"></i>
                        <span>RECOMMENDED FOR YOU</span>
                    </span>
                </div>

                {{-- Title & Subtitle --}}
                <div class="text-center mt-3 mb-4">
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-snug">
                        Get Faster Results with <br><span class="text-[#4f28d9]">Premium Registration</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto leading-relaxed">
                        Unlock priority processing and get shortlisted faster for the best teaching opportunities.
                    </p>
                </div>

                {{-- Featured Premium Card --}}
                <div class="rounded-2xl border-2 border-[#b8a2ff] bg-gradient-to-b from-[#f7f4ff] via-white to-[#f7f4ff] p-4 relative shadow-[0_4px_20px_rgba(79,40,217,0.08)]">
                    
                    {{-- Header inside card --}}
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-amber-400/20 text-amber-500 flex items-center justify-center text-lg shadow-sm shrink-0">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div>
                                <div class="font-black text-slate-900 text-sm sm:text-base leading-tight">Premium Registration</div>
                                <div class="text-[10px] text-slate-500 font-medium mt-0.5">Priority processing starts within 24 hours</div>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full bg-[#4f28d9] text-white text-[9px] font-bold uppercase tracking-wider shrink-0 shadow-sm">
                            Most Preferred
                        </span>
                    </div>

                    {{-- Big Price & Best Value Rosette --}}
                    <div class="flex items-center justify-between my-3 px-1">
                        <div class="text-3xl sm:text-4xl font-black text-[#4f28d9] tracking-tight">
                            ₹{{ number_format($premiumCost, 0) }}
                        </div>
                        {{-- Best Value Rosette Badge --}}
                        <div class="relative flex items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-400 via-amber-500 to-yellow-400 text-slate-950 font-black text-[9px] uppercase leading-tight text-center flex items-center justify-center shadow-md p-1 border-2 border-white ring-2 ring-amber-300">
                                BEST<br>VALUE
                            </div>
                        </div>
                    </div>

                    {{-- Features List --}}
                    <ul class="space-y-2 text-xs text-slate-700 my-3 font-medium">
                        <li class="flex items-center gap-2">
                            <span class="text-amber-500 font-bold text-sm leading-none shrink-0">⚡</span>
                            <span>Same-day profile verification</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-[#4f28d9] text-xs shrink-0"><i class="fas fa-rocket"></i></span>
                            <span>Priority processing &amp; faster shortlisting</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-[#4f28d9] text-xs shrink-0"><i class="fas fa-file-alt"></i></span>
                            <span>Process initiation on priority</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-[#4f28d9] text-xs shrink-0"><i class="fas fa-users"></i></span>
                            <span>Valid for up to 3 job applications/interviews</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-amber-400 text-xs shrink-0"><i class="fas fa-star"></i></span>
                            <span>Priority consideration for better opportunities</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-[#4f28d9] text-xs shrink-0"><i class="fas fa-shield-alt"></i></span>
                            <span>No additional registration payment after final selection</span>
                        </li>
                    </ul>

                    {{-- Primary CTA Button --}}
                    <button type="button" 
                            @click="selectAndConfirm('premium')"
                            class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-[#4f28d9] to-[#6d3df2] hover:brightness-110 text-white font-black text-xs sm:text-sm flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/25 transition-all hover:scale-[1.01] active:scale-[0.99] cursor-pointer mt-2">
                        <span>Continue with ₹{{ number_format($premiumCost, 0) }} Premium</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </div>

                {{-- Separator --}}
                <div class="relative flex py-3 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-3 text-slate-400 text-xs font-semibold uppercase tracking-wider">or</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                {{-- Standard Registration Card (Triggers Downsell Step 2) --}}
                <div @click="selectStandardAndDownsell()" 
                     class="rounded-xl border border-slate-200 hover:border-[#4f28d9] bg-slate-50/80 hover:bg-indigo-50/30 p-3 flex items-center justify-between transition-all cursor-pointer group shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-sm group-hover:bg-[#4f28d9]/15 group-hover:text-[#4f28d9] transition-colors">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <div class="font-bold text-slate-900 text-xs sm:text-sm group-hover:text-[#4f28d9] transition-colors">
                                Standard Registration
                            </div>
                            <div class="text-[10px] text-slate-500">
                                Process starts within 24 hours
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 font-black text-slate-900 text-sm sm:text-base">
                        <span>₹{{ number_format($basicCost, 0) }}</span>
                        <i class="fas fa-chevron-right text-xs text-slate-400 group-hover:text-[#4f28d9] group-hover:translate-x-0.5 transition-all"></i>
                    </div>
                </div>

                {{-- Info Note --}}
                <div class="mt-3.5 bg-slate-50 border border-slate-200/80 rounded-xl p-2.5 text-[11px] text-slate-500 flex items-start gap-2">
                    <i class="fas fa-info-circle text-slate-400 text-xs mt-0.5 shrink-0"></i>
                    <span>Standard plan is suitable if you are not in a hurry. For better opportunities and faster processing, we recommend Premium Registration.</span>
                </div>

                {{-- Trust Bar --}}
                <div class="mt-3 text-center text-[10px] font-semibold text-slate-400 flex items-center justify-center gap-1.5">
                    <i class="fas fa-shield-alt text-[#4f28d9] text-xs"></i>
                    <span>Your information is safe and secure with us</span>
                </div>
            </div>

            {{-- ================================================= --}}
            {{-- SCREEN 2: DOWNSELL ("Wait! Before You Continue")  --}}
            {{-- ================================================= --}}
            <div x-show="flowStep === 2" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0">
                
                {{-- Warning Circle Icon --}}
                <div class="text-center pt-1">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-amber-500 to-amber-400 text-white flex items-center justify-center text-xl font-black mx-auto shadow-md shadow-amber-500/20">
                        <i class="fas fa-exclamation"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-2.5">
                        Wait! Before You Continue
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        You have selected the Standard Registration for <strong class="text-slate-800">₹{{ number_format($basicCost, 0) }}</strong>.
                    </p>
                </div>

                {{-- Upgrade Offer Banner --}}
                <div class="mt-3 bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200/80 rounded-2xl p-3 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#4f28d9] text-white flex items-center justify-center text-base shrink-0 shadow-sm">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div class="text-xs font-bold text-purple-950 leading-snug">
                        For only ₹{{ number_format($upgradeDifference, 0) }} more, upgrade to Premium and get many extra benefits with priority processing.
                    </div>
                </div>

                {{-- 2-Column Comparison Table --}}
                <div class="grid grid-cols-2 gap-2 mt-3.5 items-stretch text-[11px]">
                    {{-- Standard Column --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-2.5 flex flex-col justify-between">
                        <div>
                            <div class="text-center pb-2 border-b border-slate-200/70">
                                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Standard</div>
                                <div class="text-base font-black text-slate-800">₹{{ number_format($basicCost, 0) }}</div>
                            </div>
                            <ul class="space-y-1.5 mt-2 text-slate-600 font-medium">
                                <li class="flex items-center gap-1.5">
                                    <i class="fas fa-user text-slate-400 text-[10px] w-3.5 text-center"></i>
                                    <span>Up to 2 Applications</span>
                                </li>
                                <li class="flex items-center gap-1.5">
                                    <i class="fas fa-rocket text-slate-400 text-[10px] w-3.5 text-center"></i>
                                    <span>Standard Processing</span>
                                </li>
                                <li class="flex items-center gap-1.5">
                                    <i class="fas fa-envelope text-slate-400 text-[10px] w-3.5 text-center"></i>
                                    <span>Email Support</span>
                                </li>
                                <li class="flex items-center gap-1.5 text-slate-400">
                                    <i class="fas fa-times-circle text-rose-400 text-[10px] w-3.5 text-center"></i>
                                    <span>WhatsApp Assistance</span>
                                </li>
                                <li class="flex items-center gap-1.5 text-slate-400">
                                    <i class="fas fa-times-circle text-rose-400 text-[10px] w-3.5 text-center"></i>
                                    <span>Call Support</span>
                                </li>
                                <li class="flex items-center gap-1.5 text-slate-400">
                                    <i class="fas fa-times-circle text-rose-400 text-[10px] w-3.5 text-center"></i>
                                    <span>Early Access to New Jobs</span>
                                </li>
                                <li class="flex items-center gap-1.5 text-slate-400">
                                    <i class="fas fa-times-circle text-rose-400 text-[10px] w-3.5 text-center"></i>
                                    <span>Relationship Manager</span>
                                </li>
                                <li class="flex items-center gap-1.5">
                                    <i class="fas fa-id-badge text-slate-400 text-[10px] w-3.5 text-center"></i>
                                    <span>Standard Visibility</span>
                                </li>
                                <li class="flex items-center gap-1.5">
                                    <i class="fas fa-bell text-slate-400 text-[10px] w-3.5 text-center"></i>
                                    <span>Regular Process Updates</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Premium Column (Highlighted & Recommended) --}}
                    <div class="rounded-2xl border-2 border-amber-400 bg-amber-50/25 p-2.5 flex flex-col justify-between relative shadow-sm">
                        <div class="absolute -top-2.5 right-2 px-2 py-0.5 rounded-full bg-[#4f28d9] text-white text-[8px] font-black uppercase tracking-wider shadow-sm">
                            Recommended
                        </div>
                        <div>
                            <div class="text-center pb-2 border-b border-amber-200/70">
                                <div class="flex items-center justify-center gap-1 text-[11px] font-black text-amber-600 uppercase tracking-wide">
                                    <i class="fas fa-crown text-[9px]"></i> Premium
                                </div>
                                <div class="text-base font-black text-purple-900">₹{{ number_format($premiumCost, 0) }}</div>
                            </div>
                            <ul class="space-y-1.5 mt-2 text-slate-800 font-semibold">
                                <li class="flex items-center gap-1.5 text-slate-900">
                                    <i class="fas fa-check-circle text-emerald-500 text-[10px] w-3.5 text-center"></i>
                                    <span>Up to 3 Applications</span>
                                </li>
                                <li class="flex items-center gap-1.5 text-slate-900">
                                    <i class="fas fa-check-circle text-emerald-500 text-[10px] w-3.5 text-center"></i>
                                    <span>Priority Processing</span>
                                </li>
                                <li class="flex items-center gap-1.5 text-slate-900">
                                    <i class="fas fa-check-circle text-emerald-500 text-[10px] w-3.5 text-center"></i>
                                    <span>Email + WhatsApp Support</span>
                                </li>
                                <li class="flex items-center gap-1.5 text-slate-900">
                                    <i class="fas fa-check-circle text-emerald-500 text-[10px] w-3.5 text-center"></i>
                                    <span>WhatsApp Assistance</span>
                                </li>
                                <li class="flex items-center gap-1.5 text-slate-900">
                                    <i class="fas fa-check-circle text-emerald-500 text-[10px] w-3.5 text-center"></i>
                                    <span>Priority Call Assistance</span>
                                </li>
                                <li class="flex items-center gap-1.5 text-slate-900">
                                    <i class="fas fa-check-circle text-emerald-500 text-[10px] w-3.5 text-center"></i>
                                    <span>Early Access to New Jobs</span>
                                </li>
                                <li class="flex items-center gap-1.5 text-slate-900">
                                    <i class="fas fa-check-circle text-emerald-500 text-[10px] w-3.5 text-center"></i>
                                    <span>Dedicated Relationship Manager</span>
                                </li>
                                <li class="flex items-center gap-1.5 text-amber-700">
                                    <i class="fas fa-medal text-amber-500 text-[10px] w-3.5 text-center"></i>
                                    <span>Priority Profile Highlight</span>
                                </li>
                                <li class="flex items-center gap-1.5 text-amber-700">
                                    <i class="fas fa-bell text-amber-500 text-[10px] w-3.5 text-center"></i>
                                    <span>Priority Process Updates</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Primary Upgrade CTA Button --}}
                <button type="button" 
                        @click="selectAndConfirm('premium')"
                        class="w-full mt-3.5 py-3 px-4 rounded-xl bg-gradient-to-r from-[#4f28d9] to-[#6d3df2] hover:brightness-110 text-white font-black text-xs sm:text-sm flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/25 transition-all hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                    <span>Upgrade to Premium ₹{{ number_format($premiumCost, 0) }}</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </button>

                {{-- Secondary Link: Continue with Standard --}}
                <button type="button" 
                        @click="selectAndConfirm('basic')"
                        class="text-xs font-bold text-slate-500 hover:text-slate-800 underline decoration-slate-300 hover:decoration-slate-600 transition-colors text-center block w-full mt-2 py-1 cursor-pointer">
                    Continue with Standard ₹{{ number_format($basicCost, 0) }}
                </button>

                {{-- Testimonial Box --}}
                <div class="mt-3 bg-amber-50/70 border border-amber-200/70 rounded-xl p-3 text-left">
                    <div class="flex items-start gap-2">
                        <span class="text-amber-500 font-serif text-2xl leading-none">&ldquo;</span>
                        <div>
                            <p class="text-xs italic text-slate-700 font-medium">
                                "Premium registration helped me get interview calls faster. Highly recommended!"
                            </p>
                            <span class="text-[10px] font-bold text-slate-500 mt-1 block">&mdash; Placed Teacher</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==================================================== --}}
            {{-- SCREEN 3: CONFIRMATION & LIVE PAYMENT GATEWAY SUBMIT --}}
            {{-- ==================================================== --}}
            <div x-show="flowStep === 3" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0">
                
                {{-- Header Icon --}}
                <div class="text-center pt-1">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-[#4f28d9] to-[#7c4dff] text-white flex items-center justify-center text-2xl mx-auto shadow-lg shadow-indigo-500/30 mb-2">
                        <i :class="selectedPlan === '{{ $basicCode }}' ? 'fas fa-user' : 'fas fa-crown text-amber-300'"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight" x-text="selectedTitle">
                        Premium Registration
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Confirm your payment details</p>
                </div>

                {{-- Amount Payable Box --}}
                <div class="mt-4 bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between">
                    <div class="text-xs sm:text-sm font-bold text-slate-600">Amount Payable</div>
                    <div class="text-2xl sm:text-3xl font-black text-[#4f28d9]" x-text="'₹' + selectedAmount.toLocaleString()">
                        ₹{{ number_format($premiumCost, 0) }}
                    </div>
                </div>

                {{-- Details Breakdown Table --}}
                <div class="mt-3 bg-white border border-slate-200/70 rounded-2xl divide-y divide-slate-100 text-xs overflow-hidden font-medium">
                    <div class="flex items-center justify-between py-2.5 px-3.5">
                        <div class="flex items-center gap-2 text-slate-500">
                            <i class="fas fa-calendar-alt text-slate-400 w-4 text-center"></i>
                            <span>Validity</span>
                        </div>
                        <div class="font-bold text-slate-900" x-text="selectedPlan === '{{ $basicCode }}' ? 'Up to 2 applications' : 'Up to 3 applications'">
                            Up to 3 applications
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-2.5 px-3.5">
                        <div class="flex items-center gap-2 text-slate-500">
                            <i class="fas fa-bolt text-amber-500 w-4 text-center"></i>
                            <span>Profile Verification</span>
                        </div>
                        <div class="font-bold text-slate-900" x-text="selectedPlan === '{{ $basicCode }}' ? 'Within 24-48 hours' : 'Same-day'">
                            Same-day
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-2.5 px-3.5">
                        <div class="flex items-center gap-2 text-slate-500">
                            <i class="fas fa-rocket text-[#4f28d9] w-4 text-center"></i>
                            <span>Processing</span>
                        </div>
                        <div class="font-bold text-slate-900" x-text="selectedPlan === '{{ $basicCode }}' ? 'Standard' : 'Priority'">
                            Priority
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-2.5 px-3.5">
                        <div class="flex items-center gap-2 text-slate-500">
                            <i class="fas fa-star text-amber-400 w-4 text-center"></i>
                            <span>Job Opportunities</span>
                        </div>
                        <div class="font-bold text-slate-900" x-text="selectedPlan === '{{ $basicCode }}' ? 'Standard consideration' : 'Priority consideration'">
                            Priority consideration
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-2.5 px-3.5">
                        <div class="flex items-center gap-2 text-slate-500">
                            <i class="fas fa-file-invoice text-slate-400 w-4 text-center"></i>
                            <span>Additional Payment</span>
                        </div>
                        <div class="font-bold text-slate-900">
                            Not required after selection
                        </div>
                    </div>
                </div>

                {{-- Encouragement Box --}}
                <div class="mt-3 bg-indigo-50/80 border border-indigo-100 rounded-xl p-3 flex items-center gap-2.5 text-xs font-semibold text-indigo-950">
                    <div class="w-7 h-7 rounded-lg bg-[#4f28d9] text-white flex items-center justify-center text-xs shrink-0">
                        <i class="fas fa-gift"></i>
                    </div>
                    <span x-text="selectedPlan === '{{ $basicCode }}' ? 'You are registering with Standard access.' : 'You are choosing the best option for a faster and smoother hiring experience!'"></span>
                </div>

                {{-- Form Submission to Live Payment Gateway --}}
                <form action="{{ route('candidate.payment.process') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="plan" :value="selectedPlan">
                    <button type="submit" 
                            class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-[#4f28d9] to-[#6d3df2] hover:brightness-110 text-white font-black text-sm sm:text-base flex items-center justify-center gap-2 shadow-xl shadow-indigo-500/25 transition-all hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                        <i class="fas fa-lock text-sm"></i>
                        <span>Pay ₹<span x-text="selectedAmount.toLocaleString()"></span> Securely</span>
                    </button>
                </form>

                {{-- Back / Switch Option --}}
                <div class="text-center mt-2">
                    <button type="button" @click="flowStep = 1" class="text-[11px] font-semibold text-slate-400 hover:text-slate-700 transition-colors cursor-pointer">
                        &larr; Change Plan
                    </button>
                </div>

                {{-- Terms note --}}
                <p class="text-[10px] text-slate-400 text-center mt-2">
                    By proceeding, you agree to our 
                    <a href="{{ route('terms') }}" target="_blank" class="underline hover:text-slate-600">Terms &amp; Conditions</a> 
                    and 
                    <a href="{{ route('refund') }}" target="_blank" class="underline hover:text-slate-600">Refund Policy</a>.
                </p>

                {{-- Trust Badges --}}
                <div class="grid grid-cols-3 gap-2 mt-3.5 pt-3 border-t border-slate-100 text-center">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-shield-alt text-slate-400 text-base mb-1"></i>
                        <div class="text-[10px] font-bold text-slate-700 leading-tight">Secure Payment</div>
                        <div class="text-[8px] text-slate-400">(SSL Encrypted)</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <i class="fas fa-credit-card text-slate-400 text-base mb-1"></i>
                        <div class="text-[10px] font-bold text-slate-700 leading-tight">Multiple Payment</div>
                        <div class="text-[8px] text-slate-400">Options</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <i class="fas fa-user-shield text-slate-400 text-base mb-1"></i>
                        <div class="text-[10px] font-bold text-slate-700 leading-tight">Trusted by</div>
                        <div class="text-[8px] text-slate-400">Thousands of Educators</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Bottom Section (2 Columns): Payment History (Left 7) + Invoices (Right 5) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start" id="payment-history-section">

        {{-- Payment History Table (Width ~60% / col-span-7) --}}
        <div class="lg:col-span-7 bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 shadow-[0_4px_20px_rgba(0,0,0,0.25)]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-white">Payment History</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-[11px] font-bold text-slate-400 border-b border-white/[0.08]">
                            <th class="pb-3 font-semibold">Date</th>
                            <th class="pb-3 font-semibold">Description</th>
                            <th class="pb-3 font-semibold">Amount</th>
                            <th class="pb-3 font-semibold">Payment Method</th>
                            <th class="pb-3 font-semibold">Status</th>
                            <th class="pb-3 font-semibold text-right">Invoice</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.06]">
                        {{-- Row 1: Part 1 Registration Fee --}}
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3 text-slate-300">
                                {{ $registrationPaidDate ?? '19 Aug 2024' }}
                            </td>
                            <td class="py-3 text-white font-medium">
                                Registration Fee - Part 1
                            </td>
                            <td class="py-3 font-bold text-white">
                                ₹500
                            </td>
                            <td class="py-3 text-slate-400">
                                UPI (PhonePe)
                            </td>
                            <td class="py-3">
                                @if($profile->initial_fee_paid || $profile->is_fee_paid)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                                        Paid
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 text-right">
                                @php
                                    $firstSuccess = $transactions->where('status', 'success')->first();
                                @endphp
                                @if($firstSuccess)
                                    <a href="{{ route('candidate.payment.invoice', $firstSuccess->id) }}" class="inline-flex items-center gap-1 text-sky-400 hover:text-white font-semibold">
                                        <span>INV-{{ 1000 + $firstSuccess->id }}</span>
                                        <i class="fas fa-download text-[10px]"></i>
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-1 text-sky-400 hover:text-white font-semibold">
                                        <span>INV-1001</span>
                                        <i class="fas fa-download text-[10px]"></i>
                                    </span>
                                @endif
                            </td>
                        </tr>

                        {{-- Row 2: Part 2 Final Registration Fee --}}
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3 text-slate-400">
                                {{ $profile->is_fee_paid ? ($profile->updated_at ? $profile->updated_at->format('d M Y') : '--') : '--' }}
                            </td>
                            <td class="py-3 text-white font-medium">
                                Final Registration Fee - Part 2
                            </td>
                            <td class="py-3 font-bold text-white">
                                ₹500
                            </td>
                            <td class="py-3 text-slate-400">
                                {{ $profile->is_fee_paid ? 'UPI (PhonePe)' : '--' }}
                            </td>
                            <td class="py-3">
                                @if($profile->is_fee_paid)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                                        Paid
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 text-right text-slate-500">
                                @if($profile->is_fee_paid)
                                    <span class="text-sky-400 font-semibold cursor-pointer">INV-1002 <i class="fas fa-download text-[10px]"></i></span>
                                @else
                                    --
                                @endif
                            </td>
                        </tr>

                        {{-- Row 3: Service Charge (After Joining) --}}
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3 text-slate-400">--</td>
                            <td class="py-3 text-white font-medium">Service Charge (After Joining)</td>
                            <td class="py-3 text-slate-400">--</td>
                            <td class="py-3 text-slate-400">--</td>
                            <td class="py-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                    Pending
                                </span>
                            </td>
                            <td class="py-3 text-right text-slate-500">--</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pt-4 mt-3 border-t border-white/[0.08] text-center">
                <a href="#payment-history-section" class="text-xs font-bold text-accent-blue hover:text-white inline-flex items-center gap-1.5 transition-colors">
                    <span>View All Transactions</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        {{-- Invoices List (Width ~40% / col-span-5) --}}
        <div class="lg:col-span-5 bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 shadow-[0_4px_20px_rgba(0,0,0,0.25)]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-white">Invoices</h3>
                <a href="#payment-history-section" class="text-xs font-semibold text-accent-blue hover:text-white transition-colors">
                    View All
                </a>
            </div>

            <div class="space-y-3">
                {{-- Invoice 1: INV-1001 --}}
                <div class="p-3.5 rounded-xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] transition-all flex items-center justify-between gap-3 group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">INV-1001</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">Registration Fee - Part 1</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <div class="text-xs font-black text-white">₹500</div>
                            <div class="text-[10px] text-slate-500">{{ $registrationPaidDate ?? '19 Aug 2024' }}</div>
                        </div>
                        @if($firstSuccess)
                            <a href="{{ route('candidate.payment.invoice', $firstSuccess->id) }}" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-accent-blue hover:text-white flex items-center justify-center text-slate-300 text-xs transition-all shadow-sm" title="Download Invoice">
                                <i class="fas fa-download"></i>
                            </a>
                        @else
                            <button type="button" onclick="alert('Invoice downloaded for INV-1001')" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-accent-blue hover:text-white flex items-center justify-center text-slate-300 text-xs transition-all shadow-sm" title="Download Invoice">
                                <i class="fas fa-download"></i>
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Invoice 2: INV-1002 --}}
                <div class="p-3.5 rounded-xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] transition-all flex items-center justify-between gap-3 group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-700/40 border border-slate-600/30 text-slate-400 flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">INV-1002</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">Final Registration Fee - Part 2</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <div class="text-xs font-black text-white">₹500</div>
                            <div class="text-[10px] font-semibold text-amber-400">Due Soon</div>
                        </div>
                        <button type="button" onclick="alert('Invoice will be available upon payment.')" class="w-8 h-8 rounded-lg bg-white/5 text-slate-500 flex items-center justify-center text-xs cursor-pointer hover:bg-white/10 hover:text-slate-300 transition-all" title="Download Invoice">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
