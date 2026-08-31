@extends('layouts.app')

@section('content')
@php
    $currentRole = $role ?? request('role', 'candidate');
    if (!in_array($currentRole, ['candidate', 'employer'])) {
        $currentRole = 'candidate';
    }
    $isEmployer = $currentRole === 'employer';
@endphp

<div class="min-h-[85vh] flex items-center justify-center bg-secondary-bg py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 bg-card-bg rounded-3xl shadow-2xl border border-card-border overflow-hidden reveal">
        
        {{-- Left Panel - Branding --}}
        <div class="hidden lg:flex flex-col justify-between relative bg-gradient-to-br {{ $isEmployer ? 'from-primary-bg via-emerald-600/15 to-primary-bg' : 'from-primary-bg via-accent-blue/20 to-primary-bg' }} p-10 overflow-hidden">
            {{-- Decorative Elements --}}
            <div class="absolute -top-20 -left-20 w-72 h-72 {{ $isEmployer ? 'bg-emerald-500/10' : 'bg-accent-blue/10' }} rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-accent-yellow/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-40 h-40 border border-white/5 rounded-full"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 border border-white/5 rounded-full"></div>

            <div class="relative z-10">
                <a href="{{ route('home') }}">
                    <img src="/images/logo.png" alt="Vedanta Placement Agency" class="h-12 w-auto object-contain mb-2">
                </a>
            </div>

            <div class="relative z-10 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold {{ $isEmployer ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-accent-blue/20 text-blue-300 border border-accent-blue/30' }}">
                    <i class="{{ $isEmployer ? 'fas fa-building' : 'fas fa-user-graduate' }}"></i>
                    <span>{{ $isEmployer ? 'Employer Portal' : 'Job Seeker Portal' }}</span>
                </div>

                <h2 class="text-3xl font-bold text-text-main leading-snug">
                    @if($isEmployer)
                        Hire top educators<br>with <span class="text-accent-yellow">Vedanta Placement</span>
                    @else
                        Welcome back to<br><span class="text-accent-yellow">Vedanta Placement</span>
                    @endif
                </h2>
                <p class="text-sm text-text-main/70 leading-relaxed max-w-xs">
                    @if($isEmployer)
                        Sign in to post teaching vacancies, review verified applications, and manage your school hiring pipeline.
                    @else
                        Sign in to access your dashboard, discover teaching jobs, track applications, and manage your verified profile.
                    @endif
                </p>
                <div class="flex items-center gap-4 pt-2">
                    <div class="flex -space-x-2">
                        <img src="https://i.pravatar.cc/80?img=11" alt="" class="w-8 h-8 rounded-full border-2 border-card-bg">
                        <img src="https://i.pravatar.cc/80?img=32" alt="" class="w-8 h-8 rounded-full border-2 border-card-bg">
                        <img src="https://i.pravatar.cc/80?img=44" alt="" class="w-8 h-8 rounded-full border-2 border-card-bg">
                        <div class="w-8 h-8 rounded-full {{ $isEmployer ? 'bg-emerald-600' : 'bg-accent-blue' }} text-white text-[10px] font-bold flex items-center justify-center border-2 border-card-bg">+500</div>
                    </div>
                    <p class="text-xs text-text-main/60">
                        {{ $isEmployer ? 'Trusted by 500+ schools & colleges' : 'Trusted by 500+ verified educators' }}
                    </p>
                </div>
            </div>

            <div class="relative z-10">
                <p class="text-[11px] text-text-main/40">&copy; {{ date('Y') }} Vedanta Placement Agency. All rights reserved.</p>
            </div>
        </div>

        {{-- Right Panel - Login Form --}}
        <div class="p-8 sm:p-10 lg:p-12 flex flex-col justify-center">
            {{-- Mobile Logo --}}
            <div class="lg:hidden flex justify-center mb-6">
                <a href="{{ route('home') }}">
                    <img src="/images/logo.png" alt="Vedanta Placement Agency" class="h-10 w-auto object-contain">
                </a>
            </div>

            {{-- Role Switcher Tabs --}}
            <div class="mb-6 p-1 bg-secondary-bg border border-card-border rounded-2xl grid grid-cols-2 gap-1">
                <a href="{{ route('login', ['role' => 'candidate']) }}" 
                   class="py-2.5 px-3 rounded-xl text-xs sm:text-sm font-bold flex items-center justify-center gap-2 transition-all {{ !$isEmployer ? 'bg-[#0052cc] text-white shadow-md' : 'text-text-dark/70 hover:text-text-main hover:bg-white/5' }}">
                    <i class="far fa-user"></i>
                    <span>Job Seeker</span>
                </a>
                <a href="{{ route('login', ['role' => 'employer']) }}" 
                   class="py-2.5 px-3 rounded-xl text-xs sm:text-sm font-bold flex items-center justify-center gap-2 transition-all {{ $isEmployer ? 'bg-[#008a4e] text-white shadow-md' : 'text-text-dark/70 hover:text-text-main hover:bg-white/5' }}">
                    <i class="far fa-building"></i>
                    <span>Employer</span>
                </a>
            </div>

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-text-main">
                    {{ $isEmployer ? 'Employer Sign In' : 'Job Seeker Sign In' }}
                </h2>
                <p class="mt-1.5 text-sm text-text-dark/60">
                    {{ $isEmployer ? 'Enter your credentials to manage your school vacancies' : 'Enter your credentials to access your candidate dashboard' }}
                </p>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/30 p-4 rounded-xl">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-red-400">Authentication Error</p>
                            <ul class="mt-1.5 text-sm text-red-300/80 list-disc pl-4 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
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

                <div>
                    <label for="password" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-lock text-sm"></i></span>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-11 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                            placeholder="••••••••">
                        <button type="button" id="toggle-password" class="absolute right-4 top-1/2 -translate-y-1/2 text-text-dark/40 hover:text-text-main focus:outline-none transition-colors" title="Toggle Password Visibility">
                            <i class="fas fa-eye text-sm" id="toggle-password-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <input id="remember-me" name="remember" type="checkbox"
                            class="w-4 h-4 rounded border-card-border text-accent-blue focus:ring-accent-blue/50 bg-secondary-bg cursor-pointer">
                        <span class="text-sm text-text-dark/60 group-hover:text-text-main transition-colors">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-accent-blue hover:text-accent-blue/80 transition-colors">Forgot password?</a>
                </div>

                <button type="submit"
                    class="w-full text-white font-semibold py-3.5 rounded-xl transition-all shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 {{ $isEmployer ? 'bg-[#008a4e] hover:bg-[#007340] focus:ring-emerald-500' : 'bg-accent-blue hover:bg-accent-blue-hover focus:ring-accent-blue' }} focus:outline-none focus:ring-2 focus:ring-offset-2">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>{{ $isEmployer ? 'Sign In as Employer' : 'Sign In as Job Seeker' }}</span>
                </button>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('login.otp') }}" class="w-full bg-secondary-bg text-text-main border border-card-border font-semibold py-3.5 rounded-xl hover:bg-card-bg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-yellow transition-all shadow-sm hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <i class="fas fa-mobile-alt text-accent-yellow"></i>
                        Login with OTP
                    </a>
                </div>
            </form>

            <div class="mt-8 flex items-center gap-4">
                <div class="flex-1 h-px bg-card-border"></div>
                <span class="text-xs text-text-dark/40 uppercase tracking-wider font-medium">New to Vedanta?</span>
                <div class="flex-1 h-px bg-card-border"></div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <a href="{{ route('candidate.register') }}"
                    class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-sm font-semibold border transition-all group {{ !$isEmployer ? 'border-[#0052cc] bg-[#0052cc]/10 text-[#0052cc]' : 'border-card-border text-text-main hover:bg-white/5' }}">
                    <i class="fas fa-user-graduate group-hover:scale-110 transition-transform"></i>
                    <span>Register Candidate</span>
                </a>
                <a href="{{ route('employer.register') }}"
                    class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-sm font-semibold border transition-all group {{ $isEmployer ? 'border-[#008a4e] bg-[#008a4e]/10 text-[#008a4e]' : 'border-card-border text-text-main hover:bg-white/5' }}">
                    <i class="fas fa-building group-hover:scale-110 transition-transform"></i>
                    <span>Register Employer</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggle-password-icon');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'password') {
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                } else {
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                }
            });
        }
    });
</script>
@endsection
