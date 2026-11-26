@extends('layouts.admin')

@section('title', 'Transaction Logs')
@section('subtitle', 'Monitor all payment attempts and transactions.')

@section('content')

{{-- Transaction Summary Cards --}}
@if(isset($stats))
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <a href="{{ request()->fullUrlWithQuery(['status' => null, 'page' => null]) }}" class="bg-card-bg border {{ !request('status') ? 'border-blue-500 shadow-md ring-1 ring-blue-500' : 'border-card-border' }} rounded-xl p-4 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group hover:border-blue-500 transition-all">
        <div class="absolute inset-0 bg-blue-500/5 group-hover:bg-blue-500/10 transition-colors"></div>
        <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider mb-1 relative z-10">Total Transactions</p>
        <h4 class="text-2xl font-extrabold text-blue-500 relative z-10">{{ $stats['total'] }}</h4>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'success', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'success' ? 'border-green-500 shadow-md ring-1 ring-green-500' : 'border-card-border' }} rounded-xl p-4 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group hover:border-green-500 transition-all">
        <div class="absolute inset-0 bg-green-500/5 group-hover:bg-green-500/10 transition-colors"></div>
        <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider mb-1 relative z-10">Success</p>
        <h4 class="text-2xl font-extrabold text-green-500 relative z-10">{{ $stats['success'] }}</h4>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'pending' ? 'border-amber-500 shadow-md ring-1 ring-amber-500' : 'border-card-border' }} rounded-xl p-4 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group hover:border-amber-500 transition-all">
        <div class="absolute inset-0 bg-amber-500/5 group-hover:bg-amber-500/10 transition-colors"></div>
        <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider mb-1 relative z-10">Pending</p>
        <h4 class="text-2xl font-extrabold text-amber-500 relative z-10">{{ $stats['pending'] }}</h4>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'failed', 'page' => null]) }}" class="bg-card-bg border {{ request('status') === 'failed' ? 'border-red-500 shadow-md ring-1 ring-red-500' : 'border-card-border' }} rounded-xl p-4 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group hover:border-red-500 transition-all">
        <div class="absolute inset-0 bg-red-500/5 group-hover:bg-red-500/10 transition-colors"></div>
        <p class="text-[10px] text-text-dark/60 font-bold uppercase tracking-wider mb-1 relative z-10">Failed</p>
        <h4 class="text-2xl font-extrabold text-red-500 relative z-10">{{ $stats['failed'] }}</h4>
    </a>
</div>
@endif

<div class="bg-card-bg rounded-t-2xl border-x border-t border-card-border p-4">
    <form action="{{ route('admin.transactions.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-3 top-3 text-text-dark/40 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search candidate, email or transaction ID..." 
                   class="w-full pl-9 pr-4 py-2.5 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
        </div>
        <div class="w-full md:w-48">
            <select name="type" class="w-full bg-secondary-bg border border-card-border rounded-xl px-3 py-2.5 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">All Types</option>
                <option value="registration_fee" {{ request('type') == 'registration_fee' ? 'selected' : '' }}>Registration Fee</option>
                <option value="placement_fee" {{ request('type') == 'placement_fee' ? 'selected' : '' }}>Placement Fee</option>
            </select>
        </div>
        <div class="w-full md:w-48">
            <select name="status" class="w-full bg-secondary-bg border border-card-border rounded-xl px-3 py-2.5 text-sm text-text-main focus:border-accent-blue focus:outline-none">
                <option value="">All Statuses</option>
                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>
        <button type="submit" class="bg-accent-blue text-white rounded-xl px-6 py-2.5 text-sm font-bold shadow hover:bg-accent-blue-hover transition-colors">
            Filter
        </button>
        @if(request()->anyFilled(['search', 'status', 'type']))
            <a href="{{ route('admin.transactions.index') }}" class="flex items-center justify-center px-4 py-2 text-text-dark/40 hover:text-red-400 transition-colors text-sm font-bold">
                Clear
            </a>
        @endif
    </form>
</div>

<div class="bg-card-bg rounded-b-2xl border border-card-border overflow-x-auto shadow-xl mb-6">
    <table class="w-full text-left border-collapse admin-table">
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Candidate</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-card-border">
            @forelse($transactions as $txn)
            <tr class="group hover:bg-secondary-bg/20 transition-colors">
                <td>
                    <div class="font-mono text-xs text-text-main font-bold">{{ $txn->transaction_id ?? 'N/A' }}</div>
                    @if($txn->gateway_response)
                        <div class="text-[10px] text-text-dark/40 truncate max-w-[150px]" title="{{ json_encode($txn->gateway_response) }}">View details log</div>
                    @endif
                </td>
                <td>
                    @if($txn->candidate)
                        <a href="{{ route('admin.crm.show', $txn->candidate->id) }}" class="font-semibold text-text-main hover:text-accent-blue transition-colors inline-flex items-center gap-1">
                            <span>{{ $txn->candidate->name }}</span>
                            <i class="fas fa-external-link-alt text-[9px] opacity-60"></i>
                        </a>
                        <div class="text-xs text-text-dark/50">{{ $txn->candidate->email ?? '' }}</div>
                    @else
                        <div class="font-semibold text-text-main">Unknown</div>
                    @endif
                </td>
                <td>
                    @if($txn->type === 'registration_fee')
                        <span class="text-xs font-semibold text-text-main">Registration Fee</span>
                    @else
                        <span class="text-xs font-semibold text-text-main">Placement Fee</span>
                    @endif
                </td>
                <td>
                    <div class="font-bold text-text-main">₹{{ number_format($txn->amount, 2) }}</div>
                </td>
                <td>
                    @if($txn->status === 'success')
                        <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-check-circle"></i> Success
                        </span>
                    @elseif($txn->status === 'failed')
                        <span class="bg-red-500/10 text-red-500 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider flex items-center gap-1 w-max" title="{{ $txn->gateway_response['message'] ?? 'Payment Failed' }}">
                            <i class="fas fa-times-circle"></i> Failed
                        </span>
                    @else
                        <span class="bg-yellow-500/10 text-yellow-500 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-clock"></i> Pending
                        </span>
                    @endif
                </td>
                <td class="text-text-dark/60 text-sm">
                    {{ $txn->created_at->format('d M, Y h:i A') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-16 text-center">
                    <p class="text-text-main font-bold text-lg mb-1">No transactions found</p>
                    <p class="text-text-dark/40 text-sm">Try adjusting your filters.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($transactions->hasPages())
<div class="mt-4">
    {{ $transactions->links('pagination::tailwind') }}
</div>
@endif

@endsection
