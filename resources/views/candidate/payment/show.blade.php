@extends('layouts.candidate')

@section('candidate_content')
<div class="space-y-6 pb-10">

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
                    <form action="{{ route('candidate.payment.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="{{ ($profile->plan_type === 'standard' && ($profile->initial_fee_paid || $profile->is_fee_paid)) ? 'upgrade' : ($isRenewal ? 'renewal_basic' : 'basic') }}">
                        <button type="submit" class="w-full py-1.5 px-3 rounded-xl bg-accent-yellow hover:brightness-110 text-slate-950 font-black text-xs flex items-center justify-center gap-1.5 transition-all shadow-[0_2px_10px_rgba(245,158,11,0.3)]">
                            <i class="fas fa-credit-card text-[10px]"></i>
                            <span>Pay Now</span>
                        </button>
                    </form>
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
    <div id="plans-section" x-data="{ showCompareModal: false }" class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 sm:p-6 shadow-[0_4px_20px_rgba(0,0,0,0.25)]">
        <div>
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-white">Upgrade Your Plan</h3>
                <button type="button" @click="showCompareModal = true" class="text-xs font-semibold text-accent-blue hover:text-white flex items-center gap-1.5 transition-colors cursor-pointer">
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
                        @else
                            <form action="{{ route('candidate.payment.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan" value="{{ $isRenewal ? 'renewal_basic' : 'basic' }}">
                                <button type="submit" class="w-full py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all">
                                    <span>Select Standard</span>
                                </button>
                            </form>
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

                        @if($profile->plan_type === 'premium' && $profile->is_fee_paid)
                            <button disabled class="w-full py-2 rounded-xl bg-purple-500/15 border border-purple-500/30 text-purple-300 font-bold text-xs flex items-center justify-center gap-1.5 cursor-default">
                                <i class="fas fa-check text-[10px]"></i>
                                <span>Current Plan</span>
                            </button>
                        @else
                            <form action="{{ route('candidate.payment.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan" value="{{ ($profile->plan_type === 'standard' && ($profile->initial_fee_paid || $profile->is_fee_paid)) ? 'upgrade' : ($isRenewal ? 'renewal_premium' : 'premium') }}">
                                <button type="submit" class="w-full py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-black text-xs flex items-center justify-center gap-1.5 transition-all shadow-[0_4px_15px_rgba(168,85,247,0.4)] hover:-translate-y-0.5">
                                    <span>Upgrade to Premium</span>
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer Note --}}
            <div class="pt-4 mt-4 border-t border-white/[0.08] flex items-center gap-2 text-xs text-slate-300">
                <i class="fas fa-star text-amber-400 text-xs"></i>
                <span>Upgrade to Premium Plan and get priority access to top schools.</span>
            </div>
        </div>

        {{-- Compare Plans Modal --}}
        <div x-show="showCompareModal" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="bg-[#07173e] border border-white/15 rounded-2xl max-w-2xl w-full p-5 sm:p-6 shadow-2xl relative overflow-hidden"
                 @click.away="showCompareModal = false">
                
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-white/10">
                    <h4 class="text-base sm:text-lg font-black text-white flex items-center gap-2">
                        <i class="fas fa-sliders-h text-accent-blue text-sm"></i>
                        <span>Compare Plans</span>
                    </h4>
                    <button type="button" @click="showCompareModal = false" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white flex items-center justify-center text-sm transition-colors cursor-pointer">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead>
                            <tr class="border-b border-white/10 text-white">
                                <th class="py-3 px-3 font-black text-xs sm:text-sm">
                                    STANDARD — ₹500
                                </th>
                                <th class="py-3 px-3 font-black text-xs sm:text-sm text-purple-300">
                                    PREMIUM — ₹1,000 <span class="text-amber-400">👑</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.06]">
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-2.5 px-3 text-slate-300">
                                    <span class="text-emerald-400 font-bold mr-2">✓</span> Up to 2 Applications / Interviews
                                </td>
                                <td class="py-2.5 px-3 text-white font-medium">
                                    <span class="text-emerald-400 font-bold mr-2">✓</span> Up to 3 Applications / Interviews
                                </td>
                            </tr>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-2.5 px-3 text-slate-300">
                                    <span class="text-emerald-400 font-bold mr-2">✓</span> Standard Processing
                                </td>
                                <td class="py-2.5 px-3 text-white font-medium">
                                    <span class="text-emerald-400 font-bold mr-2">✓</span> Priority Processing
                                </td>
                            </tr>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-2.5 px-3 text-slate-300">
                                    <span class="text-emerald-400 font-bold mr-2">✓</span> Email Support
                                </td>
                                <td class="py-2.5 px-3 text-white font-medium">
                                    <span class="text-emerald-400 font-bold mr-2">✓</span> Email + WhatsApp Support
                                </td>
                            </tr>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-2.5 px-3 text-slate-400">
                                    <span class="text-rose-400 font-bold mr-2">✕</span> WhatsApp Assistance
                                </td>
                                <td class="py-2.5 px-3 text-white font-medium">
                                    <span class="text-emerald-400 font-bold mr-2">✓</span> WhatsApp Assistance
                                </td>
                            </tr>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-2.5 px-3 text-slate-400">
                                    <span class="text-rose-400 font-bold mr-2">✕</span> Call Support
                                </td>
                                <td class="py-2.5 px-3 text-white font-medium">
                                    <span class="text-emerald-400 font-bold mr-2">✓</span> Priority Call Assistance
                                </td>
                            </tr>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-2.5 px-3 text-slate-400">
                                    <span class="text-rose-400 font-bold mr-2">✕</span> Early Access to New Jobs
                                </td>
                                <td class="py-2.5 px-3 text-white font-medium">
                                    <span class="text-emerald-400 font-bold mr-2">✓</span> Early Access to New Jobs
                                </td>
                            </tr>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-2.5 px-3 text-slate-400">
                                    <span class="text-rose-400 font-bold mr-2">✕</span> Relationship Manager
                                </td>
                                <td class="py-2.5 px-3 text-white font-medium">
                                    <span class="text-emerald-400 font-bold mr-2">✓</span> Dedicated Relationship Manager
                                </td>
                            </tr>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-2.5 px-3 text-slate-300">
                                    Standard Profile Visibility
                                </td>
                                <td class="py-2.5 px-3 text-white font-medium">
                                    Priority Profile Highlight
                                </td>
                            </tr>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-2.5 px-3 text-slate-300">
                                    Regular Process Updates
                                </td>
                                <td class="py-2.5 px-3 text-white font-medium">
                                    Priority Process Updates
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-5 pt-4 border-t border-white/10 flex justify-end">
                    <button type="button" @click="showCompareModal = false" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition-colors cursor-pointer">
                        Close
                    </button>
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
