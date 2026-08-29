@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">
    {{-- Alerts --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center justify-between text-emerald-800 text-xs font-semibold">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800"><i class="fas fa-times"></i></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.referrals.index') }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 hover:border-accent-blue text-slate-700 flex items-center justify-center transition-all shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Referral Details #REF{{ str_pad($referral->id, 5, '0', STR_PAD_LEFT) }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Comprehensive timeline, conversion stages, and transaction ledger.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @if($referral->status === 'flagged')
                <form action="{{ route('admin.referrals.approve', $referral->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this referral?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                        <i class="fas fa-check"></i> Approve Referral
                    </button>
                </form>
            @elseif($referral->status === 'active')
                <form action="{{ route('admin.referrals.reject', $referral->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to flag/cancel this referral?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                        <i class="fas fa-flag"></i> Flag / Reject
                    </button>
                </form>
            @endif
        </div>
    </div>

    @php
        $stageBadge = $referral->stage_badge;
        $sourceBadge = $referral->source_badge;
        $referrer = $referral->referrer;
        $referee = $referral->referee;

        $stages = [
            'registered' => [
                'step' => 1,
                'name' => 'Registered',
                'desc' => 'Candidate created account using referral code',
                'points' => 50,
                'date' => $referral->created_at,
                'icon' => 'fa-user-check',
            ],
            'profile_completed' => [
                'step' => 2,
                'name' => 'Profile Completed',
                'desc' => 'Candidate filled personal details, education & preferences',
                'points' => 100,
                'date' => $referral->completed_at,
                'icon' => 'fa-id-card',
            ],
            'verified' => [
                'step' => 3,
                'name' => 'Profile Verified',
                'desc' => 'Admin verified candidate documentation and credentials',
                'points' => 100,
                'date' => $referral->verified_at,
                'icon' => 'fa-check-double',
            ],
            'interview_scheduled' => [
                'step' => 4,
                'name' => 'Interview Scheduled',
                'desc' => 'Interview coordinated with school or employer',
                'points' => 150,
                'date' => $referral->interview_at,
                'icon' => 'fa-calendar-alt',
            ],
            'selected' => [
                'step' => 5,
                'name' => 'Selected for Placement',
                'desc' => 'Candidate accepted offer / selected by employer',
                'points' => 0,
                'date' => $referral->selected_at,
                'icon' => 'fa-user-graduate',
            ],
            'joined' => [
                'step' => 6,
                'name' => 'Successfully Joined',
                'desc' => 'Placement finalized and confirmed with service charge',
                'points' => 500,
                'date' => $referral->joined_at,
                'icon' => 'fa-trophy',
            ],
        ];

        $stageOrder = [
            'registered' => 1,
            'profile_completed' => 2,
            'verified' => 3,
            'interview_scheduled' => 4,
            'selected' => 5,
            'joined' => 6,
            'placed' => 6,
        ];
        $currentOrder = $stageOrder[$referral->stage] ?? 1;
    @endphp

    {{-- 2 Column Grid: Referrer & Referee Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Referrer Box --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Referrer (Inviter)</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-600">Invited Friend</span>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 font-bold text-lg flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($referrer?->name ?? 'U', 0, 1)) }}
                </div>
                <div class="space-y-1">
                    <div class="font-bold text-slate-800 text-base">{{ $referrer?->name ?? 'Deleted User' }}</div>
                    <div class="text-xs text-slate-500 font-mono">{{ $referrer?->email }}</div>
                    <div class="text-xs text-slate-400">{{ $referrer?->phone }}</div>
                    @if($referrer?->referralWallet)
                        <div class="pt-2 flex items-center gap-2 text-xs">
                            <span class="text-slate-500">Wallet Balance:</span>
                            <span class="font-black text-emerald-600">{{ number_format($referrer->referralWallet->available_points) }} pts</span>
                            <span class="text-slate-400">(₹{{ number_format($referrer->referralWallet->available_points * $pointRate, 2) }})</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Referee Box --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Referee (Joined Candidate)</span>
                <div class="flex items-center gap-1.5">
                    @if($referee?->profile?->is_verified)
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-50 text-cyan-600">
                            <i class="fas fa-check-circle"></i> Verified
                        </span>
                    @endif
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $stageBadge['class'] }}">
                        {{ $stageBadge['label'] }}
                    </span>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-accent-blue font-bold text-lg flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($referee?->name ?? 'U', 0, 1)) }}
                </div>
                <div class="space-y-1">
                    <div class="font-bold text-slate-800 text-base">{{ $referee?->name ?? 'Deleted User' }}</div>
                    <div class="text-xs text-slate-500 font-mono">{{ $referee?->email }}</div>
                    <div class="text-xs text-slate-400">{{ $referee?->phone }}</div>
                    <div class="pt-2 flex flex-wrap items-center gap-2 text-xs">
                        <span class="text-slate-500">Source:</span>
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold {{ $sourceBadge['class'] }} px-2 py-0.5 rounded">
                            <i class="fab {{ $sourceBadge['icon'] }}"></i> {{ $sourceBadge['label'] }}
                        </span>
                        <span class="font-mono text-accent-blue bg-blue-50 px-2 py-0.5 rounded border border-blue-200 text-[10px]">
                            Code: {{ $referral->referral_code_used }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 6-Stage Progress Funnel --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-stream text-accent-blue"></i> 6-Stage Referral Lifecycle & Point Allocation
            </h3>
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-500 font-semibold">Total Earned:</span>
                <span class="text-base font-black text-emerald-600">+{{ number_format($referral->points_earned) }} pts</span>
                <span class="text-xs text-slate-400">(₹{{ number_format($referral->points_earned * $pointRate, 2) }})</span>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="w-full bg-slate-100 rounded-full h-2 mb-8 overflow-hidden">
            <div class="bg-accent-blue h-2 rounded-full transition-all duration-500" style="width: {{ $referral->progress_percent }}%"></div>
        </div>

        {{-- 6 Stages Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
            @foreach($stages as $stageKey => $stageInfo)
                @php
                    $isPassed = $currentOrder >= $stageInfo['step'];
                    $isCurrent = $currentOrder === $stageInfo['step'];
                @endphp
                <div class="p-4 rounded-xl border {{ $isCurrent ? 'border-accent-blue bg-blue-50/50 ring-2 ring-accent-blue/20' : ($isPassed ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-200 bg-slate-50/50') }} flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $isPassed ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                                @if($isPassed) <i class="fas fa-check text-[10px]"></i> @else {{ $stageInfo['step'] }} @endif
                            </span>
                            @if($stageInfo['points'] > 0)
                                <span class="text-[10px] font-black {{ $isPassed ? 'text-emerald-700' : 'text-slate-400' }}">
                                    +{{ $stageInfo['points'] }} pts
                                </span>
                            @endif
                        </div>
                        <div class="font-bold text-xs text-slate-800 mt-1">{{ $stageInfo['name'] }}</div>
                        <div class="text-[10px] text-slate-500 mt-0.5 leading-tight">{{ $stageInfo['desc'] }}</div>
                    </div>
                    <div class="mt-3 pt-2 border-t border-slate-200/60 text-[10px] font-mono {{ $isPassed ? 'text-slate-600' : 'text-slate-400' }}">
                        @if($isPassed && $stageInfo['date'])
                            <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($stageInfo['date'])->format('d M Y') }}
                        @else
                            <span class="italic">Pending</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Transactions Ledger for this Referral --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-800 flex items-center gap-2 mb-4">
            <i class="fas fa-receipt text-accent-blue"></i> Associated Wallet Transactions
        </h3>

        @if($referral->transactions->isEmpty())
            <div class="py-8 text-center text-slate-400 text-xs">
                <i class="fas fa-receipt text-3xl mb-2 text-slate-300"></i>
                <p>No wallet points transactions recorded for this referral yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs admin-table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Recipient (Referrer)</th>
                            <th>Source</th>
                            <th>Description</th>
                            <th>Points</th>
                            <th>INR Equivalent</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($referral->transactions as $txn)
                            @php $srcBadge = $txn->source_badge; @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="font-mono text-slate-500 text-[11px]">#TXN-{{ $txn->id }}</td>
                                <td class="font-bold text-slate-800">{{ $referral->referrer?->name ?? 'User' }}</td>
                                <td>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded border {{ $srcBadge['class'] }}">
                                        <i class="fas {{ $srcBadge['icon'] }}"></i> {{ $srcBadge['label'] }}
                                    </span>
                                </td>
                                <td class="text-slate-600">{{ $txn->description }}</td>
                                <td class="font-black text-emerald-600">+{{ number_format($txn->points) }} pts</td>
                                <td class="font-bold text-slate-700">₹{{ number_format($txn->amount_equivalent, 2) }}</td>
                                <td class="text-slate-500 whitespace-nowrap text-[11px]">{{ $txn->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
