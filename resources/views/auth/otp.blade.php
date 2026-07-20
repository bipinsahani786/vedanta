@extends('layouts.app')

@section('title', 'OTP Login - Vedanta Placement Agency')

@section('content')
<div class="bg-primary-bg min-h-[calc(100vh-80px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    
    {{-- Decorative elements --}}
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 rounded-full bg-accent-blue/5 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-72 h-72 rounded-full bg-accent-yellow/5 blur-3xl"></div>
    
    <div class="max-w-md w-full relative z-10">
        <div class="text-center mb-8 reveal">
            <h2 class="text-3xl font-extrabold text-text-main tracking-tight">Login with OTP</h2>
            <p class="mt-3 text-sm text-text-dark/60">Fast, secure, and password-less access to your account.</p>
        </div>

        <div class="bg-card-bg rounded-2xl border border-card-border shadow-2xl p-8 reveal reveal-delay-1 relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-blue to-accent-yellow"></div>
            
            @if(session('success'))
                <div class="mb-6 bg-green-500/10 border border-green-500/30 p-4 rounded-xl flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-400"></i>
                    <span class="text-sm text-green-600 font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/30 p-4 rounded-xl flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                    <span class="text-sm text-red-400 font-medium">{{ $errors->first() }}</span>
                </div>
            @endif

            @if(!session('otp_email'))
                {{-- Step 1: Send OTP --}}
                <form action="{{ route('login.otp.send') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-semibold text-text-main mb-2">Registered Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-text-dark/40"></i>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                class="appearance-none rounded-xl relative block w-full px-4 py-3.5 pl-11 border border-card-border bg-secondary-bg text-text-main placeholder-text-dark/30 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all sm:text-sm" 
                                placeholder="john@example.com">
                        </div>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-accent-blue hover:bg-accent-blue-hover hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-blue transition-all">
                        <i class="fas fa-paper-plane mr-2"></i> Send OTP
                    </button>
                </form>
            @else
                {{-- Step 2: Verify OTP --}}
                <form action="{{ route('login.otp.verify') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-green-500/10 text-green-500 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <p class="text-sm text-text-dark/60">OTP sent to <strong>{{ session('otp_email') }}</strong></p>
                    </div>

                    <div>
                        <label for="otp" class="block text-sm font-semibold text-text-main mb-2">Enter 6-Digit OTP</label>
                        <div class="relative">
                            <input id="otp" name="otp" type="text" maxlength="6" autocomplete="one-time-code" required 
                                class="appearance-none rounded-xl relative block w-full px-4 py-3.5 border border-card-border bg-secondary-bg text-text-main placeholder-text-dark/30 focus:outline-none focus:ring-2 focus:ring-accent-yellow/50 focus:border-accent-yellow transition-all sm:text-center text-2xl tracking-widest font-bold" 
                                placeholder="------">
                        </div>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-[0_0_15px_rgba(255,184,0,0.3)] text-sm font-bold text-[#031b4e] bg-accent-yellow hover:brightness-110 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-yellow transition-all">
                        Verify & Login
                    </button>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('login.otp') }}" class="text-xs text-text-dark/60 hover:text-accent-blue">Change Email / Resend</a>
                    </div>
                </form>
            @endif

            <div class="mt-8 relative flex items-center justify-center">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-card-border"></div>
                </div>
                <div class="relative bg-card-bg px-4 text-xs text-text-dark/40 uppercase tracking-widest font-semibold">
                    Or
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-semibold text-accent-blue hover:text-accent-blue-hover transition-colors">
                    <i class="fas fa-key mr-2"></i> Login with Password
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
