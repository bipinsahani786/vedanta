@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center bg-secondary-bg py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md bg-card-bg rounded-3xl shadow-2xl border border-card-border overflow-hidden reveal">
        <div class="p-8 sm:p-10 flex flex-col justify-center">
            
            <div class="flex justify-center mb-6">
                <a href="{{ route('home') }}">
                    <img src="/images/logo.png" alt="Vedanta Placement Agency" class="h-12 w-auto object-contain">
                </a>
            </div>

            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold text-text-main">Set New Password</h2>
                <p class="mt-2 text-sm text-text-dark/60">Choose a new password for your account.</p>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/30 p-4 rounded-xl">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-red-400">Error</p>
                            <ul class="mt-1.5 text-sm text-red-300/80 list-disc pl-4 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email-address" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Email Address</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-envelope text-sm"></i></span>
                        <input id="email-address" name="email" type="email" autocomplete="email" required readonly
                            class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main opacity-70 cursor-not-allowed focus:outline-none"
                            value="{{ $email ?? old('email') }}">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">New Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-lock text-sm"></i></span>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Confirm Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-lock text-sm"></i></span>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                            class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-accent-blue text-white font-semibold py-3.5 rounded-xl hover:bg-accent-blue-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-blue transition-all shadow-lg hover:shadow-[0_4px_20px_rgba(var(--theme-accent-blue-rgb,18,154,239),0.35)] hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i>
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
