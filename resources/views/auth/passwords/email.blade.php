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
                <h2 class="text-2xl font-bold text-text-main">Reset Password</h2>
                <p class="mt-2 text-sm text-text-dark/60">Enter your email address and we'll send you a link to reset your password.</p>
            </div>

            @if (session('status'))
                <div class="mb-6 bg-green-500/10 border border-green-500/30 p-4 rounded-xl flex items-start gap-3">
                    <i class="fas fa-check-circle text-green-400 mt-0.5"></i>
                    <p class="text-sm font-semibold text-green-400">{{ session('status') }}</p>
                </div>
            @endif

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

            <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email-address" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Email Address</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-envelope text-sm"></i></span>
                        <input id="email-address" name="email" type="email" autocomplete="email" required
                            class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                            placeholder="you@example.com" value="{{ old('email') }}">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-accent-blue text-white font-semibold py-3.5 rounded-xl hover:bg-accent-blue-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-blue transition-all shadow-lg hover:shadow-[0_4px_20px_rgba(var(--theme-accent-blue-rgb,18,154,239),0.35)] hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i>
                    Send Password Reset Link
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm font-medium text-text-dark/60 hover:text-accent-blue transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i> Back to login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
