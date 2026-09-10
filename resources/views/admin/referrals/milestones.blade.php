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
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Referral Milestone Bonuses</h1>
            <p class="text-xs text-slate-500 mt-0.5">Configure reward bonus points awarded when candidates achieve volume milestones (e.g. 5, 10, 25 successful joins).</p>
        </div>
        <a href="{{ route('admin.referrals.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <form action="{{ route('admin.referrals.milestones.update') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-trophy text-amber-500"></i> Active Milestone Bonus Tiers
            </h3>

            <div class="space-y-4">
                @foreach($milestones as $idx => $milestone)
                    <input type="hidden" name="milestones[{{ $idx }}][id]" value="{{ $milestone->id }}">
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-slate-500 mb-1">Required Successful Referrals</label>
                            <input type="number" min="1" step="1" name="milestones[{{ $idx }}][successful_referrals_required]" value="{{ $milestone->successful_referrals_required }}" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold text-slate-500 mb-1">Bonus Points Awarded</label>
                            <div class="relative">
                                <input type="number" min="0" step="any" name="milestones[{{ $idx }}][bonus_points]" value="{{ $milestone->bonus_points }}" required class="w-full bg-white border border-slate-200 rounded-xl pl-3.5 pr-12 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                                <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-400">PTS</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-3 pt-4 sm:pt-0">
                            <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                                <input type="checkbox" name="milestones[{{ $idx }}][is_active]" value="1" {{ $milestone->is_active ? 'checked' : '' }} class="rounded text-accent-blue focus:ring-0">
                                <span>Active Tier</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-accent-blue hover:bg-accent-blue-hover text-white font-bold text-xs rounded-xl transition-all shadow-md flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Milestone Tiers
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
