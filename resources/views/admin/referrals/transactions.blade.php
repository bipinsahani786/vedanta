@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Wallet Transactions History</h1>
            <p class="text-xs text-slate-500 mt-0.5">Complete immutable ledger of all reward point credits, debits, redemptions, and adjustments.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.referrals.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('admin.referrals.wallets') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                <i class="fas fa-wallet"></i> Wallets List
            </a>
        </div>
    </div>

    {{-- 3 KPI Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase text-slate-400">Total Points Issued</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs"><i class="fas fa-coins"></i></span>
            </div>
            <div class="text-2xl font-black text-emerald-600">+{{ number_format($stats['total_credits']) }} pts</div>
            <div class="text-[11px] text-slate-400 mt-1">All milestone and referral bonuses</div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase text-slate-400">Total Points Debited</span>
                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs"><i class="fas fa-receipt"></i></span>
            </div>
            <div class="text-2xl font-black text-rose-600">-{{ number_format($stats['total_debits']) }} pts</div>
            <div class="text-[11px] text-slate-400 mt-1">Redemptions and administrative debits</div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase text-slate-400">Service Charge Discounts</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs"><i class="fas fa-tag"></i></span>
            </div>
            <div class="text-2xl font-black text-indigo-600">₹{{ number_format($stats['total_inr_redeemed'], 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Direct savings granted to candidates</div>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.referrals.transactions') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            <div class="lg:col-span-2 relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"><i class="fas fa-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
            </div>

            <div>
                <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                    <option value="">All Types (Credit/Debit)</option>
                    <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>🟢 Credits (+)</option>
                    <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>🔴 Debits (-)</option>
                </select>
            </div>

            <div>
                <select name="source" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                    <option value="">All Reward Sources</option>
                    <option value="referral_registration" {{ request('source') === 'referral_registration' ? 'selected' : '' }}>Registration Bonus</option>
                    <option value="referral_profile" {{ request('source') === 'referral_profile' ? 'selected' : '' }}>Profile Complete</option>
                    <option value="referral_verification" {{ request('source') === 'referral_verification' ? 'selected' : '' }}>Profile Verified</option>
                    <option value="referral_interview" {{ request('source') === 'referral_interview' ? 'selected' : '' }}>Interview Scheduled</option>
                    <option value="referral_placement" {{ request('source') === 'referral_placement' ? 'selected' : '' }}>Joined Success</option>
                    <option value="milestone_bonus" {{ request('source') === 'milestone_bonus' ? 'selected' : '' }}>Milestone Bonus</option>
                    <option value="service_charge_redemption" {{ request('source') === 'service_charge_redemption' ? 'selected' : '' }}>Service Charge Discount</option>
                    <option value="admin_adjustment" {{ request('source') === 'admin_adjustment' ? 'selected' : '' }}>Admin Adjustment</option>
                    <option value="welcome_bonus" {{ request('source') === 'welcome_bonus' ? 'selected' : '' }}>Welcome Bonus</option>
                </select>
            </div>

            <div>
                <input type="date" name="start_date" value="{{ request('start_date') }}" placeholder="Start Date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full py-2 bg-accent-blue hover:bg-accent-blue-hover text-white font-bold text-xs rounded-xl transition-all shadow-sm flex items-center justify-center gap-1.5">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'type', 'source', 'start_date', 'end_date']))
                    <a href="{{ route('admin.referrals.transactions') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-all">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($transactions->isEmpty())
            <div class="py-16 text-center text-slate-400 text-xs">
                <i class="fas fa-receipt text-4xl mb-2 text-slate-300"></i>
                <p class="font-bold text-slate-600">No transactions found</p>
                <p class="text-slate-400 mt-1">Try adjusting your filters or search query.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs admin-table">
                    <thead>
                        <tr>
                            <th>Txn ID</th>
                            <th>Candidate</th>
                            <th>Type</th>
                            <th>Points</th>
                            <th>INR Value</th>
                            <th>Source</th>
                            <th>Description</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transactions as $txn)
                            @php $srcBadge = $txn->source_badge; @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="font-mono text-slate-400 text-[11px]">
                                    #TXN-{{ $txn->id }}
                                </td>
                                <td>
                                    <div class="font-bold text-slate-800">{{ $txn->user?->name ?? 'Deleted User' }}</div>
                                    <div class="text-[11px] text-slate-500 font-mono">{{ $txn->user?->email }}</div>
                                </td>
                                <td>
                                    @if($txn->type === 'credit')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                            <i class="fas fa-arrow-down text-[9px] mr-1"></i> CREDIT
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                            <i class="fas fa-arrow-up text-[9px] mr-1"></i> DEBIT
                                        </span>
                                    @endif
                                </td>
                                <td class="font-black text-sm {{ $txn->type === 'credit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $txn->type === 'credit' ? '+' : '-' }}{{ number_format($txn->points) }} pts
                                </td>
                                <td class="font-bold text-slate-700">
                                    ₹{{ number_format($txn->amount_equivalent, 2) }}
                                </td>
                                <td>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded border {{ $srcBadge['class'] }}">
                                        <i class="fas {{ $srcBadge['icon'] }}"></i> {{ $srcBadge['label'] }}
                                    </span>
                                </td>
                                <td class="text-slate-600 max-w-xs">
                                    {{ $txn->description }}
                                    @if($txn->admin)
                                        <span class="text-[10px] text-slate-400 block">by Admin: {{ $txn->admin->name }}</span>
                                    @endif
                                </td>
                                <td class="text-slate-500 whitespace-nowrap text-[11px]">
                                    {{ $txn->created_at->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-200">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
