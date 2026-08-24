@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6" x-data="{ adjustModalOpen: false, selectedWallet: null, adjustType: 'credit' }">
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
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Candidate Referral Wallets</h1>
            <p class="text-xs text-slate-500 mt-0.5">Audit candidate reward balances, freeze/unfreeze wallets, and perform manual adjustments.</p>
        </div>
        <a href="{{ route('admin.referrals.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    {{-- Search & Filter Toolbar --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.referrals.wallets') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2 relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"><i class="fas fa-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search candidate by name, email, or phone..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
            </div>

            <div>
                <select name="locked" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                    <option value="">All Wallet Statuses</option>
                    <option value="0" {{ request('locked') === '0' ? 'selected' : '' }}>Active Wallets</option>
                    <option value="1" {{ request('locked') === '1' ? 'selected' : '' }}>Locked Wallets</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full py-2 bg-accent-blue hover:bg-accent-blue-hover text-white font-bold text-xs rounded-xl transition-all shadow-sm">
                    Search
                </button>
                @if(request()->hasAny(['search', 'locked']))
                    <a href="{{ route('admin.referrals.wallets') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-all">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Main Wallets Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($wallets->isEmpty())
            <div class="py-16 text-center text-slate-400 text-xs">
                <i class="fas fa-wallet text-4xl mb-2 text-slate-300"></i>
                <p class="font-bold text-slate-600">No candidate wallets found</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs admin-table">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Available Points</th>
                            <th>Pending Points</th>
                            <th>Lifetime Earned</th>
                            <th>Status</th>
                            <th class="text-right">Admin Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($wallets as $wallet)
                            <tr class="hover:bg-slate-50">
                                <td>
                                    <div class="font-bold text-slate-800">{{ $wallet->user?->name ?? 'Candidate' }}</div>
                                    <div class="text-[11px] text-slate-500 font-mono">{{ $wallet->user?->email }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $wallet->user?->phone }}</div>
                                </td>
                                <td>
                                    <div class="text-sm font-black text-emerald-600">{{ number_format($wallet->available_points) }} pts</div>
                                    <div class="text-[10px] text-slate-500">₹{{ number_format($wallet->available_points * $pointRate, 2) }}</div>
                                </td>
                                <td>
                                    <div class="font-bold text-amber-600">{{ number_format($wallet->pending_points) }} pts</div>
                                    <div class="text-[10px] text-slate-500">₹{{ number_format($wallet->pending_points * $pointRate, 2) }}</div>
                                </td>
                                <td>
                                    <div class="font-bold text-slate-800">{{ number_format($wallet->lifetime_points) }} pts</div>
                                    <div class="text-[10px] text-slate-500">₹{{ number_format($wallet->lifetime_points * $pointRate, 2) }}</div>
                                </td>
                                <td>
                                    @if($wallet->is_locked)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                            <i class="fas fa-lock"></i> Locked
                                        </span>
                                        @if($wallet->lock_reason)
                                            <div class="text-[10px] text-slate-400 mt-0.5 truncate max-w-[120px]" title="{{ $wallet->lock_reason }}">{{ $wallet->lock_reason }}</div>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                            <i class="fas fa-check-circle"></i> Active
                                        </span>
                                    @endif
                                </td>
                                <td class="text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        {{-- Adjust Points Button --}}
                                        <button type="button" @click="selectedWallet = { id: {{ $wallet->id }}, name: '{{ addslashes($wallet->user?->name) }}', available: {{ $wallet->available_points }} }; adjustModalOpen = true;" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-bold transition-all">
                                            <i class="fas fa-sliders-h mr-1"></i> Adjust
                                        </button>

                                        {{-- Toggle Lock Form --}}
                                        <form action="{{ route('admin.referrals.wallets.lock', $wallet->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to {{ $wallet->is_locked ? 'unlock' : 'lock' }} this wallet?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 {{ $wallet->is_locked ? 'bg-emerald-50 hover:bg-emerald-100 text-emerald-600' : 'bg-rose-50 hover:bg-rose-100 text-rose-600' }} rounded-lg text-xs font-bold transition-all">
                                                <i class="fas {{ $wallet->is_locked ? 'fa-lock-open' : 'fa-lock' }}"></i> {{ $wallet->is_locked ? 'Unlock' : 'Lock' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-200">
                {{ $wallets->links() }}
            </div>
        @endif
    </div>

    {{-- Adjust Points Modal --}}
    <div x-show="adjustModalOpen" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl relative" @click.away="adjustModalOpen = false">
            <button type="button" @click="adjustModalOpen = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>

            <h3 class="text-lg font-bold text-slate-800 mb-1">Manual Wallet Adjustment</h3>
            <p class="text-xs text-slate-500 mb-6">Candidate: <strong x-text="selectedWallet?.name"></strong> (Current: <span x-text="selectedWallet?.available"></span> pts)</p>

            <form :action="'/admin/referrals/wallets/' + selectedWallet?.id + '/adjust'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Adjustment Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="p-3 border rounded-xl flex items-center gap-2 cursor-pointer transition-all" :class="adjustType === 'credit' ? 'border-emerald-500 bg-emerald-50/50 text-emerald-700 font-bold' : 'border-slate-200 text-slate-600'">
                            <input type="radio" name="type" value="credit" x-model="adjustType" class="text-emerald-600">
                            <span><i class="fas fa-plus-circle mr-1"></i> Credit (+)</span>
                        </label>
                        <label class="p-3 border rounded-xl flex items-center gap-2 cursor-pointer transition-all" :class="adjustType === 'debit' ? 'border-rose-500 bg-rose-50/50 text-rose-700 font-bold' : 'border-slate-200 text-slate-600'">
                            <input type="radio" name="type" value="debit" x-model="adjustType" class="text-rose-600">
                            <span><i class="fas fa-minus-circle mr-1"></i> Debit (-)</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="modal_points" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Points Amount</label>
                    <input id="modal_points" type="number" name="points" min="1" step="any" required placeholder="e.g. 100" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                </div>

                <div>
                    <label for="modal_reason" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mandatory Reason / Note</label>
                    <input id="modal_reason" type="text" name="reason" required placeholder="e.g. Special campaign bonus / Correction" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" @click="adjustModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white rounded-xl text-xs font-bold shadow-md">Apply Adjustment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
