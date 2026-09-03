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

    {{-- Top Overview Metrics (Row of 5 Cards) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
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

        {{-- Card 5: Service Charge (After Joining) --}}
        <div class="col-span-2 sm:col-span-1 bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-4 flex flex-col justify-between shadow-[0_4px_20px_rgba(0,0,0,0.25)] hover:border-pink-500/40 transition-all group">
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
    </div>

    {{-- Middle Section (2 Columns): Payment Progress (Left) + Upgrade Your Plan (Right) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch" id="plans-section">

        {{-- Left: Payment Progress Timeline --}}
        <div class="lg:col-span-6 bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 sm:p-6 shadow-[0_4px_20px_rgba(0,0,0,0.25)] flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-white mb-6">Payment Progress</h3>

                {{-- Horizontal Stepper with Lines --}}
                <div class="relative flex items-start justify-between mb-8 px-2 sm:px-6">
                    {{-- Connecting Line 1 (Step 1 to Step 2) --}}
                    <div class="absolute left-8 sm:left-14 right-1/2 top-5 h-0.5 {{ ($profile->initial_fee_paid || $profile->is_fee_paid) ? 'bg-emerald-500' : 'bg-white/10' }} -z-0"></div>
                    {{-- Connecting Line 2 (Step 2 to Step 3) --}}
                    <div class="absolute left-1/2 right-8 sm:right-14 top-5 h-0.5 {{ $profile->is_fee_paid ? 'bg-emerald-500' : 'bg-white/10' }} -z-0"></div>

                    {{-- Step 1: Registration Fee --}}
                    <div class="relative z-10 flex flex-col items-center text-center max-w-[110px]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold shadow-md {{ ($profile->initial_fee_paid || $profile->is_fee_paid) ? 'bg-emerald-500 text-white ring-4 ring-emerald-500/20' : 'bg-[#0a1e4a] border-2 border-accent-blue text-accent-blue' }}">
                            <i class="fas {{ ($profile->initial_fee_paid || $profile->is_fee_paid) ? 'fa-check' : 'fa-receipt' }}"></i>
                        </div>
                        <span class="text-xs font-bold text-white mt-3 leading-tight">Registration Fee</span>
                        <span class="text-sm font-black text-white mt-0.5">₹500</span>
                        <span class="text-[10px] font-semibold {{ ($profile->initial_fee_paid || $profile->is_fee_paid) ? 'text-emerald-400' : 'text-slate-400' }} mt-1">
                            {{ ($profile->initial_fee_paid || $profile->is_fee_paid) ? 'Paid on ' . ($registrationPaidDate ?? '19 Aug 2024') : 'Pending' }}
                        </span>
                    </div>

                    {{-- Step 2: Final Registration Payment --}}
                    <div class="relative z-10 flex flex-col items-center text-center max-w-[125px]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold shadow-md {{ $profile->is_fee_paid ? 'bg-emerald-500 text-white ring-4 ring-emerald-500/20' : ($profile->initial_fee_paid ? 'bg-blue-600 text-white ring-4 ring-blue-500/25 font-black' : 'bg-white/5 border border-white/10 text-white/40') }}">
                            @if($profile->is_fee_paid)
                                <i class="fas fa-check"></i>
                            @else
                                <span>2</span>
                            @endif
                        </div>
                        <span class="text-xs font-bold text-white mt-3 leading-tight">Final Registration Payment</span>
                        <span class="text-sm font-black text-white mt-0.5">₹500</span>
                        <span class="text-[10px] font-semibold {{ $profile->is_fee_paid ? 'text-emerald-400' : 'text-amber-400' }} mt-1">
                            {{ $profile->is_fee_paid ? 'Paid' : 'Pending Payment' }}
                        </span>
                    </div>

                    {{-- Step 3: Service Charge --}}
                    <div class="relative z-10 flex flex-col items-center text-center max-w-[110px]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold shadow-md bg-white/5 border border-white/10 text-slate-400">
                            <span>3</span>
                        </div>
                        <span class="text-xs font-bold text-white mt-3 leading-tight">Service Charge</span>
                        <span class="text-xs font-semibold text-slate-300 mt-0.5">After Joining</span>
                        <span class="text-[10px] text-slate-400 mt-1 leading-tight">
                            Pay after 1st month salary
                        </span>
                    </div>
                </div>
            </div>

            {{-- Alert Callout Container --}}
            @if(!$profile->is_fee_paid && $profile->initial_fee_paid)
                <div class="bg-[#0b1b4d]/90 border border-amber-500/30 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-inner">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl shrink-0 shadow-sm">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Final registration payment of ₹500 is pending.</h4>
                            <p class="text-[11px] text-slate-300 mt-0.5">Complete your payment to activate full access and apply to more schools.</p>
                        </div>
                    </div>
                    <form action="{{ route('candidate.payment.process') }}" method="POST" class="shrink-0 w-full sm:w-auto">
                        @csrf
                        <input type="hidden" name="plan" value="upgrade">
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-accent-yellow hover:brightness-110 text-slate-950 font-black text-xs shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-1.5">
                            <span>Pay Now</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </button>
                    </form>
                </div>
            @elseif(!$profile->initial_fee_paid)
                <div class="bg-[#0b1b4d]/90 border border-accent-blue/30 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-inner">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-accent-blue/20 text-accent-blue flex items-center justify-center text-xl shrink-0 shadow-sm">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Initial registration payment of ₹500 is pending.</h4>
                            <p class="text-[11px] text-slate-300 mt-0.5">Complete your payment to activate your profile and unlock job applications.</p>
                        </div>
                    </div>
                    <form action="{{ route('candidate.payment.process') }}" method="POST" class="shrink-0 w-full sm:w-auto">
                        @csrf
                        <input type="hidden" name="plan" value="basic">
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-accent-blue hover:bg-accent-blue-hover text-white font-black text-xs shadow-[0_4px_15px_rgba(18,154,239,0.35)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-1.5">
                            <span>Pay Now</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-4 flex items-center gap-3.5 text-emerald-300">
                    <i class="fas fa-check-circle text-emerald-400 text-2xl shrink-0"></i>
                    <div class="text-xs">
                        <span class="font-bold">All registration payments completed!</span>
                        <p class="text-emerald-200/80 text-[11px] mt-0.5">Your profile is fully verified. The service charge of 50% will be payable only after you join a school.</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right: Upgrade Your Plan --}}
        <div class="lg:col-span-6 bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 sm:p-6 shadow-[0_4px_20px_rgba(0,0,0,0.25)] flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-bold text-white">Upgrade Your Plan</h3>
                    <a href="#plans-section" class="text-xs font-semibold text-accent-blue hover:text-white flex items-center gap-1.5 transition-colors">
                        <i class="fas fa-sliders-h text-[10px]"></i>
                        <span>Compare Plans</span>
                    </a>
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
                            <p class="text-[10px] text-slate-400 mb-4">Up to 3 applications/interviews</p>

                            <ul class="space-y-2 text-xs mb-5">
                                <li class="flex items-center gap-2 text-slate-300">
                                    <i class="fas fa-check text-emerald-400 text-xs"></i>
                                    <span>Apply to 3 Schools</span>
                                </li>
                                <li class="flex items-center gap-2 text-slate-300">
                                    <i class="fas fa-check text-emerald-400 text-xs"></i>
                                    <span>Standard Processing</span>
                                </li>
                                <li class="flex items-center gap-2 text-slate-300">
                                    <i class="fas fa-check text-emerald-400 text-xs"></i>
                                    <span>Email Support</span>
                                </li>
                                <li class="flex items-center gap-2 text-slate-300">
                                    <i class="fas fa-check text-emerald-400 text-xs"></i>
                                    <span>Profile Visibility</span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <div class="mb-3">
                                <span class="text-xl font-black text-white">₹1,000</span>
                                <span class="text-[10px] text-slate-400 ml-1">Total (₹500 + ₹500)</span>
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
                                    <i class="fas fa-check text-emerald-400 text-xs"></i>
                                    <span>Unlimited Applications</span>
                                </li>
                                <li class="flex items-center gap-2 text-white font-medium">
                                    <i class="fas fa-check text-emerald-400 text-xs"></i>
                                    <span>Priority Processing</span>
                                </li>
                                <li class="flex items-center gap-2 text-white font-medium">
                                    <i class="fas fa-check text-emerald-400 text-xs"></i>
                                    <span>Profile Highlight</span>
                                </li>
                                <li class="flex items-center gap-2 text-white font-medium">
                                    <i class="fas fa-check text-emerald-400 text-xs"></i>
                                    <span>WhatsApp Support</span>
                                </li>
                                <li class="flex items-center gap-2 text-white font-medium">
                                    <i class="fas fa-check text-emerald-400 text-xs"></i>
                                    <span>Early Access to Jobs</span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <div class="mb-3">
                                <span class="text-xl font-black text-purple-400">₹2,000</span>
                                <span class="text-[10px] text-slate-400 ml-1">Total (₹1000 + ₹1000)</span>
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
            </div>

            {{-- Footer Note --}}
            <div class="pt-4 mt-4 border-t border-white/[0.08] flex items-center gap-2 text-xs text-slate-300">
                <i class="fas fa-star text-amber-400 text-xs"></i>
                <span>Upgrade to Premium Plan and get priority access to top schools.</span>
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
