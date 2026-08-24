@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Fraud & Audit Security Logs</h1>
            <p class="text-xs text-slate-500 mt-0.5">Track flagged referrals, frozen wallets, and administrator audit actions.</p>
        </div>
        <a href="{{ route('admin.referrals.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    {{-- Frozen Wallets Section --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-800 flex items-center gap-2 mb-4">
            <i class="fas fa-snowflake text-cyan-600"></i> Currently Frozen Wallets
        </h3>

        @if($lockedWallets->isEmpty())
            <div class="py-8 text-center text-slate-400 text-xs font-semibold">
                <i class="fas fa-shield-alt text-2xl text-emerald-500 mb-2"></i>
                <p>No wallets are currently frozen. All candidate accounts are clean.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs admin-table">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Available Points</th>
                            <th>Reason for Lock</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($lockedWallets as $w)
                            <tr class="hover:bg-slate-50">
                                <td>
                                    <div class="font-bold text-slate-800">{{ $w->user?->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $w->user?->email }}</div>
                                </td>
                                <td class="font-black text-rose-600">
                                    {{ number_format($w->available_points) }} pts
                                </td>
                                <td class="text-slate-600 text-xs">
                                    {{ $w->lock_reason ?: 'Suspicious volume' }}
                                </td>
                                <td class="text-right">
                                    <form action="{{ route('admin.referrals.wallets.lock', $w->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg text-xs font-bold transition-all">
                                            <i class="fas fa-lock-open mr-1"></i> Unfreeze
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Audit Logs Section --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-800 flex items-center gap-2 mb-4">
            <i class="fas fa-history text-accent-blue"></i> Security & Audit Trail
        </h3>

        @if($auditLogs->isEmpty())
            <div class="py-8 text-center text-slate-400 text-xs font-semibold">
                No audit events recorded yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs admin-table">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Admin</th>
                            <th>Candidate</th>
                            <th>Action</th>
                            <th>Reason / Note</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($auditLogs as $log)
                            <tr class="hover:bg-slate-50">
                                <td class="text-slate-400 whitespace-nowrap text-[11px]">
                                    {{ $log->created_at->format('d M Y, H:i:s') }}
                                </td>
                                <td class="font-bold text-slate-700">
                                    {{ $log->admin?->name ?? 'System' }}
                                </td>
                                <td class="font-bold text-slate-800">
                                    {{ $log->candidate?->name ?? 'N/A' }}
                                </td>
                                <td>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                </td>
                                <td class="text-slate-600 max-w-sm truncate">
                                    {{ $log->reason ?: 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
