@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6" x-data="{
    adjustModal: false,
    adjustType: 'credit',
    selectedCandidateId: '',
    selectedCandidateName: '',
    pointsAmount: '',
    adjustReason: '',
    freezeModal: false,
    freezeWalletId: '',
    freezeCandidateName: '',
    freezeReason: '',
    openAdjust(type, name = '', id = '') {
        this.adjustType = type;
        this.selectedCandidateName = name;
        this.selectedCandidateId = id;
        this.pointsAmount = '';
        this.adjustReason = '';
        this.adjustModal = true;
    }
}">

    {{-- Top Action Alerts --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center justify-between text-emerald-800 text-xs font-semibold">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800"><i class="fas fa-times"></i></button>
        </div>
    @endif

    {{-- TOP BAR: Title & Date Range Picker & Export --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-lg">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Referral & Earn Dashboard</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Comprehensive analytics of viral growth, referral funnel, point rewards, and redemptions.</p>
                </div>
            </div>
        </div>

        {{-- Date Range Filter & Export Report --}}
        <div class="flex flex-wrap items-center gap-2">
            <form method="GET" action="{{ route('admin.referrals.dashboard') }}" class="flex items-center gap-2">
                <div class="flex items-center bg-white border border-slate-200 rounded-xl px-3 py-1.5 shadow-sm text-xs text-slate-700">
                    <i class="far fa-calendar-alt text-slate-400 mr-2"></i>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="border-0 p-0 text-xs text-slate-700 focus:ring-0">
                    <span class="mx-1 text-slate-400">-</span>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="border-0 p-0 text-xs text-slate-700 focus:ring-0">
                    <button type="submit" class="ml-2 text-accent-blue hover:text-accent-blue-hover font-bold"><i class="fas fa-arrow-right"></i></button>
                </div>
            </form>

            <a href="{{ route('admin.referrals.index', ['export' => 'csv']) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                <i class="fas fa-file-export"></i> Export Report
            </a>
        </div>
    </div>

    {{-- 6 TOP KPI CARDS (MATCHING MOCKUP 1) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        {{-- Card 1: Total Referrers --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-bold uppercase text-slate-400">Total Referrers</span>
                <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs"><i class="fas fa-users"></i></span>
            </div>
            <div class="text-2xl font-black text-slate-800">{{ number_format($totalReferrers) }}</div>
            <div class="text-[10px] text-emerald-600 font-bold mt-1 flex items-center gap-1">
                <i class="fas fa-arrow-up text-[9px]"></i> +{{ $thisMonthReferrers }} this month
            </div>
        </div>

        {{-- Card 2: Total Referrals --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-bold uppercase text-slate-400">Total Referrals</span>
                <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs"><i class="fas fa-link"></i></span>
            </div>
            <div class="text-2xl font-black text-slate-800">{{ number_format($totalReferrals) }}</div>
            <div class="text-[10px] text-emerald-600 font-bold mt-1 flex items-center gap-1">
                <i class="fas fa-arrow-up text-[9px]"></i> +{{ $thisMonthReferrals }} this month
            </div>
        </div>

        {{-- Card 3: Successful Referrals --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-bold uppercase text-slate-400">Successful Referrals</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs"><i class="fas fa-check-circle"></i></span>
            </div>
            <div class="text-2xl font-black text-emerald-600">{{ number_format($successfulReferrals) }}</div>
            <div class="text-[10px] text-emerald-600 font-bold mt-1 flex items-center gap-1">
                <i class="fas fa-arrow-up text-[9px]"></i> +{{ $thisMonthSuccessful }} this month
            </div>
        </div>

        {{-- Card 4: Pending Referrals --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-bold uppercase text-slate-400">Pending Referrals</span>
                <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs"><i class="fas fa-hourglass-half"></i></span>
            </div>
            <div class="text-2xl font-black text-amber-600">{{ number_format($pendingReferrals) }}</div>
            <div class="text-[10px] text-slate-500 font-medium mt-1">In active pipeline</div>
        </div>

        {{-- Card 5: Points Issued --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-bold uppercase text-slate-400">Points Issued</span>
                <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs"><i class="fas fa-coins"></i></span>
            </div>
            <div class="text-2xl font-black text-slate-800">{{ number_format($pointsIssued) }}</div>
            <div class="text-[10px] text-emerald-600 font-bold mt-1 flex items-center gap-1">
                <i class="fas fa-arrow-up text-[9px]"></i> +{{ number_format($thisMonthPointsIssued) }} this month
            </div>
        </div>

        {{-- Card 6: Points Redeemed --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-bold uppercase text-slate-400">Points Redeemed</span>
                <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs"><i class="fas fa-gift"></i></span>
            </div>
            <div class="text-2xl font-black text-rose-600">{{ number_format($pointsRedeemed) }}</div>
            <div class="text-[10px] text-rose-600 font-bold mt-1 flex items-center gap-1">
                <i class="fas fa-tag text-[9px]"></i> ₹{{ number_format($pointsRedeemed * $pointRate, 2) }} value
            </div>
        </div>
    </div>

    {{-- MIDDLE ROW: GROWTH CHART + FUNNEL + QUICK ACTIONS --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Growth Chart (5 cols) --}}
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Referral Growth Overview</h3>
                        <p class="text-[11px] text-slate-400">Total vs Successful vs Pending over time</p>
                    </div>
                    <span class="text-[10px] font-bold bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg">Last 30 Days</span>
                </div>
                <div class="h-60 relative">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
            <div class="flex items-center justify-center gap-4 text-[11px] text-slate-500 mt-2 border-t border-slate-100 pt-3">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Total Referrals</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Successful</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Pending</span>
            </div>
        </div>

        {{-- Conversion Funnel (4 cols) --}}
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Referral Conversion Funnel</h3>
                        <p class="text-[11px] text-slate-400">Step-by-step conversion drop-offs</p>
                    </div>
                </div>

                <div class="space-y-2.5">
                    {{-- 1. Link Clicks --}}
                    <div class="p-2.5 rounded-xl bg-blue-50/60 border border-blue-100 flex items-center justify-between text-xs">
                        <span class="font-bold text-blue-700 flex items-center gap-2"><i class="fas fa-mouse-pointer text-blue-500"></i> Link Clicks</span>
                        <span class="font-mono font-black text-slate-800">{{ number_format($funnel['clicks']) }}</span>
                    </div>

                    {{-- 2. Registrations --}}
                    @php
                        $regPct = $funnel['clicks'] > 0 ? round(($funnel['registered'] / $funnel['clicks']) * 100, 1) : 0;
                    @endphp
                    <div class="p-2.5 rounded-xl bg-emerald-50/60 border border-emerald-100 flex items-center justify-between text-xs">
                        <span class="font-bold text-emerald-700 flex items-center gap-2"><i class="fas fa-user-plus text-emerald-500"></i> Registrations</span>
                        <div class="text-right">
                            <span class="font-mono font-black text-slate-800">{{ number_format($funnel['registered']) }}</span>
                            <span class="text-[10px] text-slate-400 ml-1">({{ $regPct }}%)</span>
                        </div>
                    </div>

                    {{-- 3. Profile Completed --}}
                    @php
                        $profPct = $funnel['registered'] > 0 ? round(($funnel['profile_completed'] / $funnel['registered']) * 100, 1) : 0;
                    @endphp
                    <div class="p-2.5 rounded-xl bg-purple-50/60 border border-purple-100 flex items-center justify-between text-xs">
                        <span class="font-bold text-purple-700 flex items-center gap-2"><i class="fas fa-id-card text-purple-500"></i> Profile Completed</span>
                        <div class="text-right">
                            <span class="font-mono font-black text-slate-800">{{ number_format($funnel['profile_completed']) }}</span>
                            <span class="text-[10px] text-slate-400 ml-1">({{ $profPct }}%)</span>
                        </div>
                    </div>

                    {{-- 4. Interview Scheduled --}}
                    @php
                        $intPct = $funnel['registered'] > 0 ? round(($funnel['interview_scheduled'] / $funnel['registered']) * 100, 1) : 0;
                    @endphp
                    <div class="p-2.5 rounded-xl bg-amber-50/60 border border-amber-100 flex items-center justify-between text-xs">
                        <span class="font-bold text-amber-700 flex items-center gap-2"><i class="fas fa-calendar-alt text-amber-500"></i> Interviews Scheduled</span>
                        <div class="text-right">
                            <span class="font-mono font-black text-slate-800">{{ number_format($funnel['interview_scheduled']) }}</span>
                            <span class="text-[10px] text-slate-400 ml-1">({{ $intPct }}%)</span>
                        </div>
                    </div>

                    {{-- 5. Joined Successfully --}}
                    @php
                        $joinPct = $funnel['registered'] > 0 ? round(($funnel['placed'] / $funnel['registered']) * 100, 1) : 0;
                    @endphp
                    <div class="p-2.5 rounded-xl bg-emerald-100/70 border border-emerald-200 flex items-center justify-between text-xs">
                        <span class="font-bold text-emerald-900 flex items-center gap-2"><i class="fas fa-trophy text-emerald-600"></i> Joined Successfully</span>
                        <div class="text-right">
                            <span class="font-mono font-black text-emerald-700">{{ number_format($funnel['placed']) }}</span>
                            <span class="text-[10px] text-emerald-700 ml-1 font-bold">({{ $joinPct }}%)</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-[10px] text-slate-400 text-center border-t border-slate-100 pt-2.5 mt-2">
                Overall conversion from Registration to Join: <strong>{{ $joinPct }}%</strong>
            </div>
        </div>

        {{-- Quick Actions Panel (3 cols) --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800 mb-1">Quick Actions</h3>
                <p class="text-[11px] text-slate-400 mb-4">Direct administrator controls</p>

                <div class="space-y-2">
                    <a href="{{ route('admin.referrals.wallets') }}" class="w-full p-2.5 bg-blue-50/70 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-bold flex items-center gap-2.5 transition-all">
                        <i class="fas fa-plus-circle text-blue-600"></i> Add Points to Wallet
                    </a>

                    <a href="{{ route('admin.referrals.wallets') }}" class="w-full p-2.5 bg-rose-50/70 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold flex items-center gap-2.5 transition-all">
                        <i class="fas fa-minus-circle text-rose-600"></i> Deduct Points from Wallet
                    </a>

                    <a href="{{ route('admin.referrals.wallets') }}" class="w-full p-2.5 bg-cyan-50/70 hover:bg-cyan-100 text-cyan-700 rounded-xl text-xs font-bold flex items-center gap-2.5 transition-all">
                        <i class="fas fa-snowflake text-cyan-600"></i> Freeze / Unfreeze Wallet
                    </a>

                    <a href="{{ route('admin.referrals.index') }}" class="w-full p-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold flex items-center gap-2.5 transition-all border border-slate-200/60">
                        <i class="fas fa-user-check text-slate-600"></i> Approve / Reject Referrals
                    </a>

                    <a href="{{ route('admin.bulk-email.index') }}" class="w-full p-2.5 bg-purple-50/70 hover:bg-purple-100 text-purple-700 rounded-xl text-xs font-bold flex items-center gap-2.5 transition-all">
                        <i class="fas fa-bullhorn text-purple-600"></i> Send Announcement
                    </a>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 mt-3">
                <a href="{{ route('admin.referrals.settings') }}" class="text-[11px] font-bold text-accent-blue hover:underline flex items-center justify-between">
                    <span>Manage Reward Settings</span>
                    <i class="fas fa-cog"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- TABLES ROW: RECENT REFERRALS + TOP REFERRERS LEADERBOARD --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Recent Referrals Table (8 cols) --}}
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Recent Referrals</h3>
                    <p class="text-[11px] text-slate-400">Latest candidate referral activities and stages</p>
                </div>
                <a href="{{ route('admin.referrals.index') }}" class="text-xs font-bold text-accent-blue hover:underline">View All Referrals</a>
            </div>

            @if($recentReferrals->isEmpty())
                <div class="py-12 text-center text-slate-400 text-xs">No referrals recorded yet.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                                <th class="py-2.5 px-3">Ref ID</th>
                                <th class="py-2.5 px-3">Referrer</th>
                                <th class="py-2.5 px-3">Referred Candidate</th>
                                <th class="py-2.5 px-3">Stage</th>
                                <th class="py-2.5 px-3">Status</th>
                                <th class="py-2.5 px-3 text-right">Points</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentReferrals as $ref)
                                @php $badge = $ref->stage_badge; @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3 px-3 font-mono font-bold text-accent-blue text-[11px]">
                                        REF{{ str_pad($ref->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="font-bold text-slate-800">{{ $ref->referrer?->name ?? 'Candidate' }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $ref->referrer?->email }}</div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="font-bold text-slate-800">{{ $ref->referee?->name ?? 'Friend' }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $ref->referee?->email }}</div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badge['class'] }}">
                                            <i class="fas {{ $badge['icon'] }}"></i> {{ $badge['label'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $ref->status === 'active' || $ref->status === 'completed' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $ref->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-right font-bold text-slate-800">
                                        {{ number_format($ref->points_earned) }} pts
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Top Referrers Leaderboard (4 cols) --}}
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Top Referrers (This Month)</h3>
                        <p class="text-[11px] text-slate-400">Leading candidate referrers</p>
                    </div>
                    <a href="{{ route('admin.referrals.wallets') }}" class="text-xs font-bold text-accent-blue hover:underline">View All</a>
                </div>

                @if($topReferrers->isEmpty())
                    <div class="py-12 text-center text-slate-400 text-xs">No active referrers this month.</div>
                @else
                    <div class="space-y-3">
                        @foreach($topReferrers as $idx => $user)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-6 h-6 rounded-full {{ $idx === 0 ? 'bg-amber-100 text-amber-700 font-bold' : ($idx === 1 ? 'bg-slate-200 text-slate-700' : 'bg-amber-50 text-amber-800') }} flex items-center justify-center text-xs font-black">
                                        {{ $idx === 0 ? '🥇' : ($idx === 1 ? '🥈' : ($idx === 2 ? '🥉' : '#' . ($idx + 1))) }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                        <div class="text-[10px] text-slate-400">Successful: {{ $user->placed_count }}</div>
                                    </div>
                                </div>
                                <div class="text-right font-black text-slate-800">
                                    {{ number_format($user->referralWallet?->available_points ?? 0) }} pts
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="pt-3 border-t border-slate-100 mt-4 text-center">
                <a href="{{ route('admin.referrals.milestones') }}" class="text-[11px] font-bold text-indigo-600 hover:underline">
                    <i class="fas fa-trophy mr-1"></i> Configure Referral Milestone Bonuses
                </a>
            </div>
        </div>
    </div>

    {{-- BOTTOM ROW: POINTS SUMMARY + POINTS BY TYPE + SOURCES + RECENT ACTIVITIES --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Points Summary Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-800">Points Summary</h3>
            
            <div class="space-y-2.5 text-xs">
                <div class="flex justify-between text-slate-500">
                    <span>Lifetime Points Issued:</span>
                    <span class="font-bold text-slate-800">{{ number_format($pointsIssued) }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Lifetime Points Redeemed:</span>
                    <span class="font-bold text-rose-600">{{ number_format($pointsRedeemed) }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Available in All Wallets:</span>
                    <span class="font-bold text-emerald-600">{{ number_format($availablePointsAllWallets) }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Expired / Cancelled Points:</span>
                    <span class="font-bold text-slate-400">{{ number_format($expiredOrCancelledPoints) }}</span>
                </div>
            </div>

            <div class="p-3 rounded-xl bg-purple-50 border border-purple-100 text-center">
                <div class="text-[10px] uppercase font-bold text-purple-600">Total Discount Given (All Time)</div>
                <div class="text-xl font-black text-purple-700 mt-0.5">₹{{ number_format($totalDiscountGiven, 2) }}</div>
            </div>
        </div>

        {{-- Points by Type Donut Chart --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800 mb-2">Points by Type (This Month)</h3>
                <div class="h-44 relative">
                    <canvas id="pointsTypeChart"></canvas>
                </div>
            </div>
            <div class="text-[10px] text-slate-400 text-center mt-2 border-t border-slate-100 pt-2">
                Registration (50) &bull; Profile (100) &bull; Verified (100) &bull; Interview (150) &bull; Joining (500)
            </div>
        </div>

        {{-- Referral Source Donut Chart --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800 mb-2">Referral Source (This Month)</h3>
                <div class="h-44 relative">
                    <canvas id="sourceChart"></canvas>
                </div>
            </div>
            <div class="text-[10px] text-slate-400 text-center mt-2 border-t border-slate-100 pt-2">
                WhatsApp &bull; Email &bull; Direct Link &bull; Others
            </div>
        </div>

        {{-- Recent Activities Feed --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800 mb-3">Recent Activities</h3>
                <div class="space-y-2.5">
                    @forelse($recentActivities as $act)
                        <div class="flex items-center justify-between text-[11px]">
                            <div class="flex items-center gap-2 truncate">
                                <span class="w-2 h-2 rounded-full {{ $act->type === 'credit' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                <span class="text-slate-700 truncate max-w-[140px]" title="{{ $act->description }}">{{ $act->description }}</span>
                            </div>
                            <span class="text-[10px] text-slate-400 whitespace-nowrap">{{ $act->created_at->diffForHumans(null, true) }}</span>
                        </div>
                    @empty
                        <div class="text-slate-400 text-xs text-center py-6">No recent activities.</div>
                    @endforelse
                </div>
            </div>
            <a href="{{ route('admin.referrals.fraud') }}" class="text-[11px] font-bold text-rose-600 hover:underline pt-2 border-t border-slate-100 flex items-center justify-between">
                <span>View Fraud & Audit Logs</span>
                <i class="fas fa-shield-alt"></i>
            </a>
        </div>
    </div>
</div>

{{-- Chart.js Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Growth Overview Line Chart
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: @json($growthLabels),
            datasets: [
                {
                    label: 'Total Referrals',
                    data: @json($growthTotal),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Successful',
                    data: @json($growthSuccessful),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Pending',
                    data: @json($growthPending),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: '#f1f5f9' } }
            }
        }
    });

    // 2. Points by Type Donut
    const typeCtx = document.getElementById('pointsTypeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Registration', 'Profile', 'Verification', 'Interview', 'Joining'],
            datasets: [{
                data: [8.5, 12.4, 12.6, 15.7, 50.8],
                backgroundColor: ['#3b82f6', '#8b5cf6', '#06b6d4', '#f59e0b', '#10b981']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // 3. Referral Source Donut
    const sourceCtx = document.getElementById('sourceChart').getContext('2d');
    new Chart(sourceCtx, {
        type: 'doughnut',
        data: {
            labels: ['WhatsApp', 'Email Invite', 'Direct Link', 'Others'],
            datasets: [{
                data: [62.2, 21.4, 13.7, 2.7],
                backgroundColor: ['#25D366', '#8b5cf6', '#3b82f6', '#94a3b8']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endsection
