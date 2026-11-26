@extends('layouts.app')

@section('title', 'Set New Private Password — Vedanta Placement Agency®')

@section('content')
<div class="min-h-[82vh] flex items-center justify-center bg-secondary-bg py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md bg-card-bg rounded-3xl shadow-2xl border border-card-border overflow-hidden">
        <div class="p-8 sm:p-10">
            <div class="flex justify-center mb-5">
                <a href="{{ route('home') }}">
                    <img src="/images/logo.png" alt="Vedanta Placement Agency" class="h-12 w-auto object-contain">
                </a>
            </div>

            <div class="text-center mb-6">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/15 text-amber-400 border border-amber-500/30 mb-3">
                    <i class="fas fa-shield-alt"></i> Security Action Required
                </span>
                <h1 class="text-2xl font-extrabold text-text-main">Change Temporary Password</h1>
                <p class="mt-2 text-sm text-text-dark/70 leading-relaxed">
                    You signed in using a temporary password issued for <strong>{{ $user->email }}</strong>. Set a private password of at least 8 characters to continue to your dashboard.
                </p>
            </div>

            @if(session('warning'))
                <div class="mb-5 bg-amber-500/10 border border-amber-500/30 p-3.5 rounded-xl flex items-start gap-2.5">
                    <i class="fas fa-exclamation-triangle text-amber-400 mt-0.5 text-sm"></i>
                    <p class="text-xs sm:text-sm font-medium text-amber-300">{{ session('warning') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 bg-red-500/10 border border-red-500/30 p-3.5 rounded-xl">
                    <ul class="text-xs sm:text-sm text-red-300 list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('candidate.force-password.update') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="new_password" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">
                        New Private Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-lock text-sm"></i></span>
                        <input id="new_password" name="password" type="password" required minlength="8" autocomplete="new-password"
                               class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                               placeholder="Minimum 8 characters">
                    </div>
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">
                        Confirm New Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-check-double text-sm"></i></span>
                        <input id="new_password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password"
                               class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                               placeholder="Re-enter your new password">
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-accent-blue text-white font-bold py-3.5 rounded-xl hover:bg-accent-blue-hover transition-all shadow-lg flex items-center justify-center gap-2">
                    <i class="fas fa-key"></i>
                    <span>Save New Password &amp; Continue</span>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-card-border/60 flex items-center justify-between text-xs text-text-dark/60">
                <span><i class="fas fa-lock text-emerald-400 mr-1"></i> Encrypted with one-way hashing</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="font-semibold text-red-400 hover:text-red-300 transition-colors cursor-pointer">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
