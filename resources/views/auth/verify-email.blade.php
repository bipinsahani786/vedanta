@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center bg-secondary-bg py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md bg-card-bg rounded-3xl shadow-2xl border border-card-border overflow-hidden reveal p-10 text-center relative">

        {{-- Decorative Elements --}}
        <div class="absolute -top-16 -right-16 w-32 h-32 bg-accent-blue/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-16 -left-16 w-32 h-32 bg-accent-yellow/10 rounded-full blur-2xl"></div>

        {{-- Icon --}}
        <div class="w-20 h-20 bg-accent-blue/10 text-accent-blue rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 relative z-10">
            <i class="fas fa-envelope-open-text"></i>
        </div>

        <h2 class="text-2xl font-bold text-text-main relative z-10">
            Verify Your Email Address
        </h2>

        <p class="text-sm text-text-dark/60 mt-4 leading-relaxed relative z-10">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
        </p>

        @if (session('message'))
            <div class="mt-6 bg-green-500/10 border border-green-500/30 text-green-400 p-4 rounded-xl text-sm relative z-10">
                <i class="fas fa-check-circle mr-1.5"></i> {{ session('message') }}
            </div>
        @endif

        <div class="mt-8 space-y-3 relative z-10">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-accent-blue text-white font-semibold py-3.5 rounded-xl hover:bg-accent-blue-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-blue transition-all shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i>
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full py-3 rounded-xl text-sm font-medium text-text-dark/60 hover:text-red-400 hover:bg-red-500/5 border border-card-border transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-sign-out-alt"></i>
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
