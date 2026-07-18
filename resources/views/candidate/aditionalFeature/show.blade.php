@extends('layouts.app')

@section('content')
    @include('candidate.partials.nav')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 reveal">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-lg">
                    <i class="fas fa-puzzle-piece"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-text-main">Additional Features</h1>
                    <p class="text-sm text-text-dark/50 mt-0.5">Explore premium tools and settings to boost your profile.</p>
                </div>
            </div>
        </div>

        {{-- Features Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 reveal reveal-delay-1">
            
            {{-- Job Application Tracker --}}
            <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-sm hover:shadow-xl hover:border-accent-blue/30 transition-all flex flex-col group">
                <div class="w-12 h-12 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-route"></i>
                </div>
                <h3 class="text-lg font-bold text-text-main mb-2">Job Application Tracker</h3>
                <p class="text-sm text-text-dark/50 mb-6 flex-1">Monitor the real-time status of your job applications, interviews, and final placements.</p>
                <a href="{{ route('candidate.applications.index') }}" class="mt-auto px-4 py-2.5 bg-secondary-bg text-text-main text-sm font-semibold rounded-xl hover:bg-accent-blue hover:text-white transition-colors text-center border border-card-border hover:border-transparent">
                    View Tracker
                </a>
            </div>


            {{-- Resume Builder --}}
            <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-sm hover:shadow-xl hover:border-green-500/30 transition-all flex flex-col group">
                <div class="w-12 h-12 rounded-xl bg-green-500/10 text-green-400 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="text-lg font-bold text-text-main mb-2">Resume Builder</h3>
                <p class="text-sm text-text-dark/50 mb-6 flex-1">Create a professional, ATS-friendly resume using our premium templates tailored for educators.</p>
                <a href="{{ route('resume.builder') }}" class="mt-auto px-4 py-2.5 bg-secondary-bg text-text-main text-sm font-semibold rounded-xl hover:bg-green-500 hover:text-white transition-colors text-center border border-card-border hover:border-transparent">
                    Build Resume
                </a>
            </div>

            {{-- Verified Candidate Badge --}}
            <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-sm hover:shadow-xl hover:border-blue-400/30 transition-all flex flex-col group">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    @if(auth()->user()->profile && auth()->user()->profile->is_verified)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-500/10 border border-blue-400/30 text-blue-400 text-xs font-bold uppercase tracking-wider rounded-lg">
                            Verified
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-card-border/50 text-text-dark/40 text-xs font-bold uppercase tracking-wider rounded-lg">
                            Unverified
                        </span>
                    @endif
                </div>
                <h3 class="text-lg font-bold text-text-main mb-2">Verified Candidate Badge</h3>
                <p class="text-sm text-text-dark/50 mb-6 flex-1">Boost your visibility. Verified profiles are 3x more likely to be shortlisted by top schools.</p>
                <a href="{{ route('candidate.profile.edit') }}" class="mt-auto px-4 py-2.5 bg-secondary-bg text-text-main text-sm font-semibold rounded-xl hover:bg-card-border/50 transition-colors text-center border border-card-border">
                    Request Verification
                </a>
            </div>

            {{-- Notification Center --}}
            <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-sm hover:shadow-xl hover:border-purple-400/30 transition-all flex flex-col group">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform relative">
                    <i class="fas fa-bell"></i>
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-card-bg"></span>
                </div>
                <h3 class="text-lg font-bold text-text-main mb-2">Notification Center</h3>
                <p class="text-sm text-text-dark/50 mb-6 flex-1">Manage your email and SMS alert preferences for new jobs, interviews, and profile updates.</p>
                <button class="mt-auto px-4 py-2.5 bg-secondary-bg text-text-main text-sm font-semibold rounded-xl hover:bg-purple-500 hover:text-white transition-colors text-center border border-card-border hover:border-transparent">
                    Manage Alerts
                </button>
            </div>

        </div>
    </div>
@endsection