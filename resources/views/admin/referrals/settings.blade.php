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

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 text-rose-800 text-xs">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Referral & Reward Settings</h1>
            <p class="text-xs text-slate-500 mt-0.5">Configure 6-stage reward point thresholds, INR conversion rates, service charge discounts, and email invite controls.</p>
        </div>
        <a href="{{ route('admin.referrals.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <form action="{{ route('admin.referrals.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Section 1: System Status & Core Economics --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-sliders-h text-accent-blue"></i> Global System Controls & Economics
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- System Active Toggle --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Program Status</label>
                    <select name="is_referral_active" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 font-bold">
                        <option value="1" {{ ($settings['is_referral_active'] ?? '1') == '1' ? 'selected' : '' }}>🟢 Enabled (Active)</option>
                        <option value="0" {{ ($settings['is_referral_active'] ?? '1') == '0' ? 'selected' : '' }}>🔴 Disabled (Paused)</option>
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Temporarily turn off all referral point awarding</p>
                </div>

                {{-- Point Conversion Rate (INR) --}}
                <div>
                    <label for="point_rate_inr" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Point to INR Conversion Rate</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">₹</span>
                        <input id="point_rate_inr" type="number" step="0.01" min="0.01" name="point_rate_inr" value="{{ $settings['point_rate_inr'] ?? '0.50' }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 font-bold">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">1 Reward Point = X Indian Rupees (e.g. ₹0.50)</p>
                </div>

                {{-- Max Discount Cap % --}}
                <div>
                    <label for="max_discount_percentage" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Max Service Charge Discount Cap</label>
                    <div class="relative">
                        <input id="max_discount_percentage" type="number" step="1" min="1" max="100" name="max_discount_percentage" value="{{ $settings['max_discount_percentage'] ?? '30' }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-8 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 font-bold">
                        <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">%</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Maximum % discount allowable via points on invoice</p>
                </div>
            </div>
        </div>

        {{-- Section 2: 6-Stage Milestone Reward Points Configuration --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
            <div>
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-gift text-amber-500"></i> 6-Stage Funnel Reward Points Allocation
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Specify how many reward points the referrer receives as their invited friend advances through each milestone.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                {{-- Stage 1: Registration --}}
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold text-blue-600 uppercase">1. Register</span>
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 text-[10px] font-bold flex items-center justify-center">1</span>
                    </div>
                    <label for="points_on_registration" class="block text-[10px] text-slate-500 mb-1">Points on Sign-up</label>
                    <input id="points_on_registration" type="number" min="0" step="1" name="points_on_registration" value="{{ $settings['points_on_registration'] ?? '50' }}" required class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                    <p class="text-[9px] text-slate-400 mt-1">On account create</p>
                </div>

                {{-- Stage 2: Profile Complete --}}
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold text-purple-600 uppercase">2. Profile</span>
                        <span class="w-5 h-5 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold flex items-center justify-center">2</span>
                    </div>
                    <label for="points_on_profile_complete" class="block text-[10px] text-slate-500 mb-1">Wizard Done</label>
                    <input id="points_on_profile_complete" type="number" min="0" step="1" name="points_on_profile_complete" value="{{ $settings['points_on_profile_complete'] ?? '100' }}" required class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                    <p class="text-[9px] text-slate-400 mt-1">On wizard finish</p>
                </div>

                {{-- Stage 3: Verification --}}
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold text-cyan-600 uppercase">3. Verified</span>
                        <span class="w-5 h-5 rounded-full bg-cyan-100 text-cyan-600 text-[10px] font-bold flex items-center justify-center">3</span>
                    </div>
                    <label for="points_on_verification" class="block text-[10px] text-slate-500 mb-1">Verified Badge</label>
                    <input id="points_on_verification" type="number" min="0" step="1" name="points_on_verification" value="{{ $settings['points_on_verification'] ?? '100' }}" required class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                    <p class="text-[9px] text-slate-400 mt-1">Admin verification</p>
                </div>

                {{-- Stage 4: Interview --}}
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold text-amber-600 uppercase">4. Interview</span>
                        <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-600 text-[10px] font-bold flex items-center justify-center">4</span>
                    </div>
                    <label for="points_on_interview" class="block text-[10px] text-slate-500 mb-1">Interview Set</label>
                    <input id="points_on_interview" type="number" min="0" step="1" name="points_on_interview" value="{{ $settings['points_on_interview'] ?? '150' }}" required class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                    <p class="text-[9px] text-slate-400 mt-1">School interview</p>
                </div>

                {{-- Stage 5: Selection --}}
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold text-indigo-600 uppercase">5. Selected</span>
                        <span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 text-[10px] font-bold flex items-center justify-center">5</span>
                    </div>
                    <label for="points_on_selection" class="block text-[10px] text-slate-500 mb-1">Selection Milestone</label>
                    <input id="points_on_selection" type="number" min="0" step="1" name="points_on_selection" value="{{ $settings['points_on_selection'] ?? '0' }}" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                    <p class="text-[9px] text-slate-400 mt-1">0 = Reward on join</p>
                </div>

                {{-- Stage 6: Final Joining --}}
                <div class="p-4 bg-emerald-50/60 border border-emerald-200 rounded-xl">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold text-emerald-700 uppercase">6. Joined</span>
                        <span class="w-5 h-5 rounded-full bg-emerald-200 text-emerald-800 text-[10px] font-bold flex items-center justify-center">6</span>
                    </div>
                    <label for="points_on_placement" class="block text-[10px] text-emerald-800 mb-1 font-semibold">Joined Reward</label>
                    <input id="points_on_placement" type="number" min="0" step="1" name="points_on_placement" value="{{ $settings['points_on_placement'] ?? '500' }}" required class="w-full bg-white border border-emerald-300 rounded-lg px-2.5 py-1.5 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
                    <p class="text-[9px] text-emerald-600 mt-1 font-semibold">On joining & fee</p>
                </div>
            </div>
        </div>

        {{-- Section 3: Anti-Spam & Cookies --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-shield-alt text-accent-blue"></i> Welcome Bonus & Anti-Spam Limits
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="referee_bonus_points" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">New Candidate Welcome Bonus Points</label>
                    <input id="referee_bonus_points" type="number" min="0" step="1" name="referee_bonus_points" value="{{ $settings['referee_bonus_points'] ?? '100' }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 font-bold">
                    <p class="text-[11px] text-slate-400 mt-1">Given to the invited friend immediately upon sign-up</p>
                </div>

                <div>
                    <label for="max_daily_invites" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Max Daily Email Invites per User</label>
                    <input id="max_daily_invites" type="number" min="1" step="1" name="max_daily_invites" value="{{ $settings['max_daily_invites'] ?? '10' }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 font-bold">
                    <p class="text-[11px] text-slate-400 mt-1">Limits outgoing invitation emails per candidate per 24 hours (SRS: 10/day)</p>
                </div>

                <div>
                    <label for="cookie_duration_days" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tracking Cookie Duration (Days)</label>
                    <input id="cookie_duration_days" type="number" min="1" max="365" step="1" name="cookie_duration_days" value="{{ $settings['cookie_duration_days'] ?? '30' }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 font-bold">
                    <p class="text-[11px] text-slate-400 mt-1">How long referral link clicks remain active on visitor device</p>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button type="submit" class="px-8 py-3 bg-accent-blue hover:bg-accent-blue-hover text-white font-bold text-xs rounded-xl transition-all shadow-lg flex items-center gap-2">
                <i class="fas fa-save"></i> Save Settings Changes
            </button>
        </div>
    </form>

    {{-- Section 4: Email Invitation Template Preview & Test Send --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6 mt-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-envelope-open-text text-purple-600"></i> Referral Email Invitation Template
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Preview the email layout and send a test invitation email to verify mail deliverability.</p>
            </div>
            <a href="{{ route('admin.referrals.email-preview') }}" target="_blank" class="px-4 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-xl text-xs font-bold transition-all border border-purple-200 flex items-center gap-2">
                <i class="fas fa-external-link-alt text-xs"></i> Open In-Browser Preview
            </a>
        </div>

        <div class="pt-4 border-t border-slate-100">
            <form action="{{ route('admin.referrals.email-test') }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                @csrf
                <div class="flex-1 relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" placeholder="Enter recipient email..." required class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accent-blue/50">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i> Send Test Referral Email
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
