@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-trophy text-amber-500"></i> Referrer Leaderboard & Growth Champions
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Top candidate advocates ranked by verified placements, total invites, and reward milestones.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.referrals.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('admin.referrals.index') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                <i class="fas fa-list"></i> All Referrals
            </a>
        </div>
    </div>

    {{-- Filter & Timeframe Toolbar --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.referrals.leaderboard') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.referrals.leaderboard', ['timeframe' => 'all', 'search' => request('search')]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $timeframe !== 'month' ? 'bg-accent-blue text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    🏆 All Time
                </a>
                <a href="{{ route('admin.referrals.leaderboard', ['timeframe' => 'month', 'search' => request('search')]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $timeframe === 'month' ? 'bg-accent-blue text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    📅 This Month
                </a>
            </div>

            <div class="flex items-center gap-2 max-w-md w-full">
                <input type="hidden" name="timeframe" value="{{ $timeframe }}">
                <div class="relative flex-1">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search candidate by name, email, or phone..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                </div>
                <button type="submit" class="px-4 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white font-bold text-xs rounded-xl transition-all shadow-sm">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.referrals.leaderboard', ['timeframe' => $timeframe]) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-all">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Leaderboard Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($leaderboard->isEmpty())
            <div class="py-16 text-center text-slate-400 text-xs">
                <i class="fas fa-trophy text-4xl mb-2 text-slate-300"></i>
                <p class="font-bold text-slate-600">No referrers found</p>
                <p class="text-slate-400 mt-1">Try adjusting your search criteria.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs admin-table">
                    <thead>
                        <tr>
                            <th class="w-16 text-center">Rank</th>
                            <th>Advocate Candidate</th>
                            <th>Total Referrals</th>
                            <th>Joined & Verified</th>
                            <th>Conversion Rate</th>
                            <th>Lifetime Points</th>
                            <th>Available Balance</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($leaderboard as $index => $candidate)
                            @php
                                $rank = ($leaderboard->currentPage() - 1) * $leaderboard->perPage() + $index + 1;
                                $total = $candidate->total_referred ?? 0;
                                $joined = $candidate->joined_count ?? 0;
                                $convRate = $total > 0 ? round(($joined / $total) * 100, 1) : 0;
                                $wallet = $candidate->referralWallet;
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="text-center font-bold">
                                    @if($rank === 1)
                                        <span class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 inline-flex items-center justify-center font-black text-sm shadow-sm">🥇 1</span>
                                    @elseif($rank === 2)
                                        <span class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 inline-flex items-center justify-center font-black text-sm shadow-sm">🥈 2</span>
                                    @elseif($rank === 3)
                                        <span class="w-8 h-8 rounded-full bg-amber-50 text-amber-700 border border-amber-300 inline-flex items-center justify-center font-black text-sm shadow-sm">🥉 3</span>
                                    @else
                                        <span class="text-slate-400 font-mono font-bold">#{{ $rank }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-bold text-slate-800 text-sm">{{ $candidate->name }}</div>
                                    <div class="text-[11px] text-slate-500 font-mono">{{ $candidate->email }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $candidate->phone }}</div>
                                </td>
                                <td class="font-bold text-slate-800 text-sm">
                                    {{ number_format($total) }} friends
                                </td>
                                <td>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-600 border border-emerald-200">
                                        <i class="fas fa-check-circle text-[10px] mr-1"></i> {{ number_format($joined) }} Joined
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 bg-slate-100 rounded-full h-2 overflow-hidden">
                                            <div class="bg-gradient-to-r from-accent-blue to-emerald-500 h-2 rounded-full" style="width: {{ min(100, $convRate) }}%"></div>
                                        </div>
                                        <span class="font-bold text-slate-700 text-xs">{{ $convRate }}%</span>
                                    </div>
                                </td>
                                <td class="font-bold text-slate-700">
                                    {{ number_format($wallet?->lifetime_points ?? 0) }} pts
                                </td>
                                <td>
                                    <div class="font-black text-emerald-600 text-sm">
                                        {{ number_format($wallet?->available_points ?? 0) }} pts
                                    </div>
                                    <div class="text-[10px] text-slate-400">
                                        ≈ ₹{{ number_format(($wallet?->available_points ?? 0) * $pointRate, 2) }}
                                    </div>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.crm.show', $candidate->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-accent-blue hover:text-white text-slate-700 rounded-lg text-xs font-bold transition-all inline-flex items-center gap-1">
                                        <i class="fas fa-user-circle"></i> CRM
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-200">
                {{ $leaderboard->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
