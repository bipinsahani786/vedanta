@extends('layouts.app')

@section('content')
@include('candidate.partials.nav')

<div class="max-w-5xl mx-auto space-y-6 pb-12">
    {{-- Header with Back Navigation --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('candidate.referral.index') }}" class="w-10 h-10 rounded-xl bg-card-bg border border-card-border hover:border-accent-blue text-text-main flex items-center justify-center transition-all shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-text-main flex items-center gap-2">
                    Referral Timeline & Details
                </h1>
                <p class="text-xs text-text-dark/60 mt-0.5">Track your friend's onboarding progress and milestone reward earnings.</p>
            </div>
        </div>

        <a href="{{ route('candidate.referral.index') }}#share-card" class="px-4 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-2">
            <i class="fas fa-user-plus text-xs"></i> Refer More Friends
        </a>
    </div>

    @php
        $stageBadge = $referral->stage_badge;
        $sourceBadge = $referral->source_badge;
        $referee = $referral->referee;

        $regPoints = (int) ($rewardSettings['points_on_registration'] ?? \App\Models\ReferralSetting::get('points_on_registration', 50));
        $profilePoints = (int) ($rewardSettings['points_on_profile_complete'] ?? \App\Models\ReferralSetting::get('points_on_profile_complete', 100));
        $verifyPoints = (int) ($rewardSettings['points_on_verification'] ?? \App\Models\ReferralSetting::get('points_on_verification', 100));
        $interviewPoints = (int) ($rewardSettings['points_on_interview'] ?? \App\Models\ReferralSetting::get('points_on_interview', 150));
        $selectionPoints = (int) ($rewardSettings['points_on_selection'] ?? \App\Models\ReferralSetting::get('points_on_selection', 0));
        $placementPoints = (int) ($rewardSettings['points_on_placement'] ?? \App\Models\ReferralSetting::get('points_on_placement', 500));

        $stages = [
            'registered' => [
                'step' => 1,
                'name' => 'Registered on Vedanta',
                'desc' => 'Account created with your referral code',
                'points' => $regPoints,
                'date' => $referral->created_at,
                'icon' => 'fa-user-check',
                'color' => 'blue',
            ],
            'profile_completed' => [
                'step' => 2,
                'name' => 'Profile Completed',
                'desc' => 'Bio, qualifications & experience submitted',
                'points' => $profilePoints,
                'date' => $referral->completed_at,
                'icon' => 'fa-id-card',
                'color' => 'purple',
            ],
            'verified' => [
                'step' => 3,
                'name' => 'Profile Verified',
                'desc' => 'Approved by Vedanta verification team',
                'points' => $verifyPoints,
                'date' => $referral->verified_at,
                'icon' => 'fa-check-double',
                'color' => 'cyan',
            ],
            'interview_scheduled' => [
                'step' => 4,
                'name' => 'Interview Scheduled',
                'desc' => 'School interview coordinated by Vedanta',
                'points' => $interviewPoints,
                'date' => $referral->interview_at,
                'icon' => 'fa-calendar-alt',
                'color' => 'amber',
            ],
            'selected' => [
                'step' => 5,
                'name' => 'Selected for Placement',
                'desc' => 'Offer extended by institution',
                'points' => $selectionPoints,
                'date' => $referral->selected_at,
                'icon' => 'fa-user-graduate',
                'color' => 'indigo',
            ],
            'joined' => [
                'step' => 6,
                'name' => 'Successfully Joined',
                'desc' => 'Placement finalized & confirmed',
                'points' => $placementPoints,
                'date' => $referral->joined_at,
                'icon' => 'fa-trophy',
                'color' => 'emerald',
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

    {{-- Referee Summary Card --}}
    <div class="bg-card-bg border border-card-border rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-80 h-80 bg-accent-blue/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-start sm:items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-accent-blue to-accent-blue/40 text-white font-black text-2xl flex items-center justify-center shadow-lg shrink-0">
                    {{ strtoupper(substr($referee?->name ?? 'F', 0, 1)) }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-xl font-bold text-text-main">{{ $referee?->name ?? 'Candidate' }}</h2>
                        @if($referee?->profile?->is_verified)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">
                                <i class="fas fa-check-circle"></i> Verified
                            </span>
                        @endif
                    </div>
                    <div class="text-xs text-text-dark/60 mt-1 flex flex-wrap items-center gap-x-4 gap-y-1">
                        <span><i class="far fa-envelope mr-1"></i> {{ $referee?->email }}</span>
                        @if($referee?->phone)
                            <span><i class="fas fa-phone-alt mr-1"></i> {{ substr($referee->phone, 0, 4) . '******' . substr($referee->phone, -2) }}</span>
                        @endif
                        <span><i class="far fa-calendar-alt mr-1"></i> Joined {{ $referral->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold {{ $sourceBadge['class'] }} px-2 py-0.5 rounded-lg border">
                            <i class="fab {{ $sourceBadge['icon'] }}"></i> {{ $sourceBadge['label'] }}
                        </span>
                        <span class="text-[11px] font-mono text-accent-blue bg-accent-blue/10 border border-accent-blue/20 px-2 py-0.5 rounded-lg">
                            Code: {{ $referral->referral_code_used }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Reward Status Box --}}
            <div class="flex items-center gap-3 bg-secondary-bg/60 border border-card-border p-4 rounded-2xl shrink-0">
                <div class="w-12 h-12 rounded-xl bg-accent-yellow/15 text-accent-yellow flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-coins"></i>
                </div>
                <div>
                    <div class="text-[10px] uppercase font-bold text-text-dark/50 tracking-wider">Total Points Earned</div>
                    <div class="text-2xl font-black text-accent-yellow">+{{ number_format($referral->points_earned) }} <span class="text-xs font-semibold text-text-dark/60">pts</span></div>
                    <div class="text-[10px] text-emerald-400 font-semibold">≈ ₹{{ number_format($referral->points_earned * $pointRate, 2) }} INR value</div>
                </div>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="mt-6 pt-6 border-t border-card-border/60">
            <div class="flex justify-between items-center text-xs mb-2">
                <span class="text-text-dark/60 font-semibold">Overall Conversion Progress</span>
                <span class="font-bold text-accent-blue">{{ $referral->progress_percent }}% Completed</span>
            </div>
            <div class="w-full bg-secondary-bg rounded-full h-2.5 overflow-hidden p-0.5 border border-card-border">
                <div class="bg-gradient-to-r from-accent-blue via-purple-500 to-emerald-400 h-1.5 rounded-full transition-all duration-500" style="width: {{ $referral->progress_percent }}%"></div>
            </div>
        </div>
    </div>

    {{-- 6-Stage Visual Timeline --}}
    <div class="bg-card-bg border border-card-border rounded-3xl p-6 sm:p-8 shadow-xl">
        <h3 class="text-lg font-bold text-text-main flex items-center gap-2 mb-6">
            <i class="fas fa-stream text-accent-blue"></i> 6-Stage Progression Funnel
        </h3>

        <div class="relative pl-6 sm:pl-8 space-y-6 before:absolute before:left-3 sm:before:left-4 before:top-3 before:bottom-3 before:w-0.5 before:bg-card-border">
            @foreach($stages as $stageKey => $stageInfo)
                @php
                    $isPassed = $currentOrder >= $stageInfo['step'];
                    $isCurrent = $currentOrder === $stageInfo['step'];
                @endphp
                <div class="relative flex items-start gap-4 sm:gap-6 group">
                    {{-- Timeline Dot --}}
                    <div class="absolute -left-6 sm:-left-8 top-1 w-6 h-6 sm:w-8 sm:h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all shadow-md z-10
                        {{ $isPassed ? 'bg-emerald-500 text-white ring-4 ring-emerald-500/20' : 'bg-secondary-bg border-2 border-card-border text-text-dark/40' }}">
                        @if($isPassed)
                            <i class="fas fa-check text-[10px]"></i>
                        @else
                            <span>{{ $stageInfo['step'] }}</span>
                        @endif
                    </div>

                    {{-- Stage Card --}}
                    <div class="flex-1 bg-secondary-bg/60 border {{ $isCurrent ? 'border-accent-blue ring-2 ring-accent-blue/20' : ($isPassed ? 'border-emerald-500/30' : 'border-card-border') }} rounded-2xl p-4 sm:p-5 transition-all">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl {{ $isPassed ? 'bg-emerald-500/20 text-emerald-400' : 'bg-secondary-bg text-text-dark/40' }} flex items-center justify-center text-xs shrink-0">
                                    <i class="fas {{ $stageInfo['icon'] }}"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-text-main flex items-center gap-2">
                                        {{ $stageInfo['name'] }}
                                        @if($isCurrent && $referral->status === 'active')
                                            <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded-full bg-accent-blue/20 text-accent-blue border border-accent-blue/30 animate-pulse">
                                                Current Stage
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-text-dark/50">{{ $stageInfo['desc'] }}</div>
                                </div>
                            </div>

                            {{-- Reward Points Tag --}}
                            <div class="flex items-center gap-3 sm:self-center">
                                @if($stageInfo['points'] > 0)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-black {{ $isPassed ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-secondary-bg text-text-dark/40 border border-card-border' }}">
                                        <i class="fas fa-coins text-[10px]"></i> +{{ $stageInfo['points'] }} Pts
                                    </span>
                                @else
                                    <span class="text-xs text-text-dark/40 font-semibold">Milestone</span>
                                @endif

                                @if($isPassed && $stageInfo['date'])
                                    <span class="text-[11px] text-text-dark/40 font-mono">
                                        <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($stageInfo['date'])->format('d M Y') }}
                                    </span>
                                @elseif(!$isPassed)
                                    <span class="text-[11px] text-text-dark/30 italic">Pending</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Transactions for this Referral --}}
    @if($referral->transactions->isNotEmpty())
        <div class="bg-card-bg border border-card-border rounded-3xl p-6 sm:p-8 shadow-xl">
            <h3 class="text-lg font-bold text-text-main flex items-center gap-2 mb-4">
                <i class="fas fa-receipt text-accent-yellow"></i> Reward History for this Referral
            </h3>

            <div class="divide-y divide-card-border">
                @foreach($referral->transactions as $txn)
                    @php $src = $txn->source_badge; @endphp
                    <div class="py-3 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">
                                <i class="fas {{ $src['icon'] ?? 'fa-plus' }}"></i>
                            </div>
                            <div>
                                <div class="font-bold text-text-main">{{ $txn->description }}</div>
                                <div class="text-[10px] text-text-dark/40">{{ $txn->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-black text-emerald-400">+{{ number_format($txn->points) }} pts</div>
                            <div class="text-[10px] text-text-dark/50">≈ ₹{{ number_format($txn->amount_equivalent, 2) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
