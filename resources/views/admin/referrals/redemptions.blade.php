@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Point Redemptions & Discounts</h1>
            <p class="text-xs text-slate-500 mt-0.5">Audit log of all referral points redeemed by candidates as discounts against service charge invoices.</p>
        </div>
        <a href="{{ route('admin.referrals.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase text-slate-400">Total Points Redeemed</span>
                <div class="text-3xl font-black text-slate-800 mt-1">{{ number_format($totalRedeemedPoints) }} pts</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fas fa-coins"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase text-slate-400">Total Discount Granted</span>
                <div class="text-3xl font-black text-emerald-600 mt-1">₹{{ number_format($totalRedeemedAmount, 2) }}</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fas fa-tag"></i>
            </div>
        </div>
    </div>

    {{-- Search Toolbar --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.referrals.redemptions') }}" class="flex items-center gap-3">
            <div class="flex-1 relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"><i class="fas fa-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by candidate name or email..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
            </div>
            <button type="submit" class="px-5 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white font-bold text-xs rounded-xl transition-all shadow-sm">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.referrals.redemptions') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-all">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Redemptions Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($redemptions->isEmpty())
            <div class="py-16 text-center text-slate-400 text-xs">
                <i class="fas fa-receipt text-4xl mb-2 text-slate-300"></i>
                <p class="font-bold text-slate-600">No point redemptions recorded yet</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs admin-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Candidate</th>
                            <th>Invoice #</th>
                            <th>Points Redeemed</th>
                            <th>Discount (INR)</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($redemptions as $redemption)
                            <tr class="hover:bg-slate-50">
                                <td class="text-slate-500 whitespace-nowrap">
                                    {{ $redemption->created_at->format('d M, Y H:i') }}
                                </td>
                                <td>
                                    <div class="font-bold text-slate-800">{{ $redemption->user?->name ?? 'Candidate' }}</div>
                                    <div class="text-[11px] text-slate-500 font-mono">{{ $redemption->user?->email }}</div>
                                </td>
                                <td>
                                    @if($redemption->serviceChargeInvoice)
                                        <a href="{{ route('admin.crm.show', $redemption->user_id) }}" class="font-mono font-bold text-accent-blue hover:underline">
                                            #INV-{{ str_pad($redemption->serviceChargeInvoice->id, 5, '0', STR_PAD_LEFT) }}
                                        </a>
                                    @else
                                        <span class="text-slate-400">N/A</span>
                                    @endif
                                </td>
                                <td class="font-bold text-rose-600 text-sm">
                                    -{{ number_format($redemption->points_redeemed) }} pts
                                </td>
                                <td class="font-bold text-emerald-600 text-sm">
                                    ₹{{ number_format($redemption->discount_amount, 2) }}
                                </td>
                                <td>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-600 border border-emerald-200">
                                        {{ $redemption->status }}
                                    </span>
                                </td>
                                <td class="text-slate-600 max-w-xs truncate">
                                    {{ $redemption->notes ?? 'Service charge discount' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-200">
                {{ $redemptions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
