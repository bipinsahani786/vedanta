@extends('layouts.admin')

@section('title', 'Transaction Logs')
@section('subtitle', 'Monitor all payment attempts and transactions.')

@section('content')

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
                <th>Actions</th>
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
                    <div class="font-semibold text-text-main group-hover:text-accent-blue transition-colors">{{ $txn->candidate->name ?? 'Unknown' }}</div>
                    <div class="text-xs text-text-dark/50">{{ $txn->candidate->email ?? '' }}</div>
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
                <td>
                    @if($txn->status === 'pending')
                        <form action="{{ route('admin.transactions.approve', $txn->id) }}" method="POST" class="inline" onsubmit="return confirm('Approve this offline payment?');">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-accent-blue hover:text-accent-blue-hover hover:underline transition-colors flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> Approve
                            </button>
                        </form>
                    @else
                        <span class="text-xs text-text-dark/40 font-semibold">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-16 text-center">
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
