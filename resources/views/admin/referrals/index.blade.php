@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">All Candidate Referrals</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage, track, and export all candidate referral relationships and conversion milestones.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.referrals.index', array_merge(request()->query(), ['export' => 'csv'])) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                <i class="fas fa-file-csv"></i> Export to CSV
            </a>
            <a href="{{ route('admin.referrals.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                <i class="fas fa-chart-line"></i> Analytics Funnel
            </a>
        </div>
    </div>

    {{-- Stage Statistics Quick Filter Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <a href="{{ route('admin.referrals.index') }}" class="p-4 bg-white rounded-xl border {{ !request('stage') ? 'border-accent-blue ring-2 ring-accent-blue/20' : 'border-slate-200' }} shadow-sm text-center">
            <div class="text-xs text-slate-400 font-bold uppercase">All Referrals</div>
            <div class="text-2xl font-black text-slate-800 mt-1">{{ number_format($stats['total']) }}</div>
        </a>
        <a href="{{ route('admin.referrals.index', ['stage' => 'registered']) }}" class="p-4 bg-white rounded-xl border {{ request('stage') === 'registered' ? 'border-blue-500 ring-2 ring-blue-500/20' : 'border-slate-200' }} shadow-sm text-center">
            <div class="text-xs text-blue-500 font-bold uppercase">1. Registered</div>
            <div class="text-2xl font-black text-blue-600 mt-1">{{ number_format($stats['registered']) }}</div>
        </a>
        <a href="{{ route('admin.referrals.index', ['stage' => 'profile_completed']) }}" class="p-4 bg-white rounded-xl border {{ request('stage') === 'profile_completed' ? 'border-purple-500 ring-2 ring-purple-500/20' : 'border-slate-200' }} shadow-sm text-center">
            <div class="text-xs text-purple-500 font-bold uppercase">2. Profile Done</div>
            <div class="text-2xl font-black text-purple-600 mt-1">{{ number_format($stats['profile_completed']) }}</div>
        </a>
        <a href="{{ route('admin.referrals.index', ['stage' => 'interview_scheduled']) }}" class="p-4 bg-white rounded-xl border {{ request('stage') === 'interview_scheduled' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-slate-200' }} shadow-sm text-center">
            <div class="text-xs text-amber-500 font-bold uppercase">3. Interviewed</div>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ number_format($stats['interview_scheduled']) }}</div>
        </a>
        <a href="{{ route('admin.referrals.index', ['stage' => 'placed']) }}" class="p-4 bg-white rounded-xl border {{ request('stage') === 'placed' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-slate-200' }} shadow-sm text-center col-span-2 sm:col-span-1">
            <div class="text-xs text-emerald-500 font-bold uppercase">4. Placed & Paid</div>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($stats['placed']) }}</div>
        </a>
    </div>

    {{-- Filter & Search Toolbar --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.referrals.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2 relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"><i class="fas fa-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by candidate name, email, phone, or referral code..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
            </div>

            <div>
                <select name="stage" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                    <option value="">All Funnel Stages</option>
                    <option value="registered" {{ request('stage') === 'registered' ? 'selected' : '' }}>Stage 1: Registered</option>
                    <option value="profile_completed" {{ request('stage') === 'profile_completed' ? 'selected' : '' }}>Stage 2: Profile Completed</option>
                    <option value="interview_scheduled" {{ request('stage') === 'interview_scheduled' ? 'selected' : '' }}>Stage 3: Interview Scheduled</option>
                    <option value="placed" {{ request('stage') === 'placed' ? 'selected' : '' }}>Stage 4: Placed & Converted</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full py-2 bg-accent-blue hover:bg-accent-blue-hover text-white font-bold text-xs rounded-xl transition-all shadow-sm">
                    Filter Results
                </button>
                @if(request()->hasAny(['search', 'stage']))
                    <a href="{{ route('admin.referrals.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-all">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Main Referrals Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($referrals->isEmpty())
            <div class="py-16 text-center text-slate-400">
                <i class="fas fa-users-slash text-4xl mb-3"></i>
                <p class="text-sm font-semibold text-slate-600">No referral records found</p>
                <p class="text-xs text-slate-400 mt-1">Try adjusting your search criteria or filter options.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs admin-table">
                    <thead>
                        <tr>
                            <th>Referrer (Invited By)</th>
                            <th>Referee (Joined Friend)</th>
                            <th>Referral Code</th>
                            <th>Current Stage</th>
                            <th>Total Reward Points</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($referrals as $ref)
                            @php $stageBadge = $ref->stage_badge; @endphp
                            <tr class="hover:bg-slate-50">
                                <td>
                                    <div class="font-bold text-slate-800">{{ $ref->referrer?->name ?? 'Deleted User' }}</div>
                                    <div class="text-[11px] text-slate-500 font-mono">{{ $ref->referrer?->email }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $ref->referrer?->phone }}</div>
                                </td>
                                <td>
                                    <div class="font-bold text-slate-800">{{ $ref->referee?->name ?? 'Deleted User' }}</div>
                                    <div class="text-[11px] text-slate-500 font-mono">{{ $ref->referee?->email }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $ref->referee?->phone }}</div>
                                </td>
                                <td>
                                    <span class="font-mono font-bold text-accent-blue bg-blue-50 px-2 py-0.5 rounded border border-blue-200 text-[11px]">
                                        {{ $ref->referral_code_used }}
                                    </span>
                                </td>
                                <td>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $stageBadge['class'] }}">
                                        <i class="fas {{ $stageBadge['icon'] }}"></i> {{ $stageBadge['label'] }}
                                    </span>
                                </td>
                                <td class="font-bold text-slate-800 text-sm">
                                    +{{ number_format($ref->points_earned) }} pts
                                </td>
                                <td>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $ref->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $ref->status }}
                                    </span>
                                </td>
                                <td class="text-slate-500 whitespace-nowrap">
                                    {{ $ref->created_at->format('d M, Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-200">
                {{ $referrals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
