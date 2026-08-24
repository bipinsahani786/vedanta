@extends('layouts.app')

@section('title', "You're Invited to Join Vedanta Placement Agency")

@section('content')
<div class="min-h-screen bg-slate-900 text-white flex items-center justify-center p-4 sm:p-6 py-16 relative overflow-hidden">
    {{-- Background glowing orbs --}}
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-accent-blue/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-accent-yellow/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-xl w-full bg-slate-800/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-10 shadow-2xl text-center relative z-10">
        {{-- Vedanta Logo / Badge --}}
        <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-tr from-accent-blue to-accent-yellow p-0.5 shadow-xl mb-6">
            <div class="w-full h-full bg-slate-900 rounded-2xl flex items-center justify-center text-3xl">
                🎁
            </div>
        </div>

        {{-- Headline --}}
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-accent-yellow/10 border border-accent-yellow/20 text-accent-yellow text-xs font-bold uppercase tracking-wider mb-4">
            <i class="fas fa-sparkles"></i> Exclusive Invitation
        </div>

        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white mb-3">
            @if(isset($friendName) && $friendName)
                Hello {{ $friendName }}, You're Invited!
            @else
                You've Been Invited to Join Vedanta!
            @endif
        </h1>

        <p class="text-sm text-slate-300 mb-8 leading-relaxed">
            Your friend <strong class="text-white font-bold">{{ $referrer->name }}</strong> has invited you to join **Vedanta Placement Agency** — India's leading teacher recruitment and institutional placement network.
        </p>

        {{-- Welcome Gift Card --}}
        <div class="p-5 rounded-2xl bg-gradient-to-br from-emerald-500/10 to-teal-500/10 border border-emerald-500/30 mb-8 text-left relative overflow-hidden">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl shrink-0">
                    <i class="fas fa-coins"></i>
                </div>
                <div>
                    <div class="text-xs font-bold uppercase text-emerald-400 tracking-wider">Your Referral Gift</div>
                    <div class="text-lg font-black text-white">100 Welcome Points (Worth ₹50)</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">Credited immediately to your reward wallet upon free registration!</div>
                </div>
            </div>
        </div>

        {{-- Referral Code Lock Box --}}
        <div class="p-4 rounded-xl bg-slate-900/60 border border-white/5 mb-8 flex items-center justify-between">
            <div class="text-left">
                <div class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">Referral Code Applied</div>
                <div class="text-base font-mono font-black text-accent-blue">{{ $code }}</div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-bold">
                <i class="fas fa-check-circle"></i> Auto-Verified
            </span>
        </div>

        {{-- Action CTA Buttons --}}
        <div class="space-y-3">
            <a href="{{ route('register', ['ref' => $code]) }}" class="w-full py-4 px-6 bg-gradient-to-r from-accent-blue to-accent-blue-hover hover:from-accent-blue-hover hover:to-accent-blue text-white font-bold text-base rounded-2xl transition-all shadow-lg hover:shadow-accent-blue/25 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <span>Claim 100 Welcome Points & Register</span>
                <i class="fas fa-arrow-right text-sm"></i>
            </a>

            <a href="{{ route('login') }}" class="block text-xs text-slate-400 hover:text-slate-200 transition-colors">
                Already have an account? <span class="text-accent-blue font-bold">Sign In</span>
            </a>
        </div>
    </div>
</div>
@endsection
