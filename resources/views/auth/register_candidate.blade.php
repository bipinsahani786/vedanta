@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center bg-secondary-bg py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 bg-card-bg rounded-3xl shadow-2xl border border-card-border overflow-hidden reveal">

        {{-- Left Panel - Branding --}}
        <div class="hidden lg:flex flex-col justify-between relative bg-gradient-to-br from-primary-bg via-accent-blue/20 to-primary-bg p-10 overflow-hidden">
            {{-- Decorative Elements --}}
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-accent-blue/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-accent-yellow/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-40 h-40 border border-white/5 rounded-full"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 border border-white/5 rounded-full"></div>

            <div class="relative z-10">
                <a href="{{ route('home') }}">
                    <img src="/images/logo.png" alt="Vedanta Placement Agency" class="h-12 w-auto object-contain mb-2">
                </a>
            </div>

            <div class="relative z-10 space-y-6">
                <h2 class="text-3xl font-bold text-text-main leading-snug">Start your teaching<br>career with <span class="text-accent-yellow">Vedanta</span></h2>
                <p class="text-sm text-text-main/70 leading-relaxed max-w-xs">
                    Create your free candidate profile, discover job opportunities, and get placed at top schools across India.
                </p>

                {{-- Benefits --}}
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-accent-blue/15 text-accent-blue flex items-center justify-center text-xs"><i class="fas fa-briefcase"></i></div>
                        <span class="text-sm text-text-main/80">Access 20K+ verified job vacancies</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-accent-yellow/15 text-accent-yellow flex items-center justify-center text-xs"><i class="fas fa-shield-alt"></i></div>
                        <span class="text-sm text-text-main/80">100% free registration for candidates</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-accent-blue/15 text-accent-blue flex items-center justify-center text-xs"><i class="fas fa-headset"></i></div>
                        <span class="text-sm text-text-main/80">Dedicated placement support team</span>
                    </div>
                </div>
            </div>

            <div class="relative z-10">
                <p class="text-[11px] text-text-main/40">&copy; {{ date('Y') }} Vedanta Placement Agency. All rights reserved.</p>
            </div>
        </div>

        {{-- Right Panel - Registration Form --}}
        <div class="p-8 sm:p-10 lg:p-12 flex flex-col justify-center">
            {{-- Mobile Logo --}}
            <div class="lg:hidden flex justify-center mb-6">
                <a href="{{ route('home') }}">
                    <img src="/images/logo.png" alt="Vedanta Placement Agency" class="h-10 w-auto object-contain">
                </a>
            </div>

            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-lg">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-text-main">Candidate Registration</h2>
                </div>
                <p class="mt-1.5 text-sm text-text-dark/60">Join Vedanta and find your dream teaching job</p>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/30 p-4 rounded-xl">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                        <div>
                            <ul class="text-sm text-red-300/80 list-disc pl-4 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('candidate.register.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Full Name</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-user text-sm"></i></span>
                        <input id="name" name="name" type="text" required
                            class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                            placeholder="Your full name" value="{{ old('name') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Email</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-envelope text-sm"></i></span>
                            <input id="email" name="email" type="email" required
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                                placeholder="you@example.com" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Phone</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-phone-alt text-sm"></i></span>
                            <input id="phone" name="phone" type="text" required
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                                placeholder="+91-XXXXXXXXXX" value="{{ old('phone') }}">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-lock text-sm"></i></span>
                            <input id="password" name="password" type="password" required
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-shield-alt text-sm"></i></span>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-accent-blue text-white font-semibold py-3.5 rounded-xl hover:bg-accent-blue-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-blue transition-all shadow-lg hover:shadow-[0_4px_20px_rgba(var(--theme-accent-blue-rgb,18,154,239),0.35)] hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-2">
                    <i class="fas fa-user-plus"></i>
                    Create Candidate Account
                </button>
            </form>

            <div class="mt-6 flex items-center gap-4">
                <div class="flex-1 h-px bg-card-border"></div>
                <span class="text-xs text-text-dark/40 uppercase tracking-wider font-medium">Or</span>
                <div class="flex-1 h-px bg-card-border"></div>
            </div>

            <div class="mt-4 text-center space-y-2">
                <p class="text-sm text-text-dark/60">
                    Already have an account? <a href="{{ route('login') }}" class="font-semibold text-accent-blue hover:underline">Sign in</a>
                </p>
                <p class="text-sm text-text-dark/60">
                    Want to hire? <a href="{{ route('employer.register') }}" class="font-semibold text-accent-yellow hover:underline">Register as Employer</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
