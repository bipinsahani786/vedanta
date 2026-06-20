@extends('layouts.app')

@section('content')
{{-- Employer Navigation --}}
<div class="bg-card-bg/80 backdrop-blur-md border-b border-card-border sticky top-[60px] z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex overflow-x-auto py-0 gap-1 text-sm font-medium hide-scrollbar items-center">
            <a href="{{ route('employer.dashboard') }}"
               class="relative px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 text-accent-yellow after:absolute after:bottom-0 after:left-2 after:right-2 after:h-[2px] after:bg-accent-yellow after:rounded-full">
                <i class="fas fa-th-large text-xs"></i> Dashboard
            </a>
            <a href="#" class="px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 text-text-dark/50 hover:text-text-main">
                <i class="fas fa-plus-circle text-xs"></i> Post Job
            </a>
            <a href="#" class="px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 text-text-dark/50 hover:text-text-main">
                <i class="fas fa-briefcase text-xs"></i> My Jobs
            </a>
            <a href="#" class="px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 text-text-dark/50 hover:text-text-main">
                <i class="fas fa-users text-xs"></i> Candidates
            </a>
            <a href="#" class="px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 text-text-dark/50 hover:text-text-main">
                <i class="fas fa-cog text-xs"></i> Settings
            </a>
            <form action="{{ route('logout') }}" method="POST" class="ml-auto">
                @csrf
                <button type="submit" class="px-4 py-3.5 text-red-400/70 hover:text-red-400 whitespace-nowrap transition-colors flex items-center gap-1.5 text-sm">
                    <i class="fas fa-sign-out-alt text-xs"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Welcome Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10 reveal">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-yellow to-accent-yellow/60 text-[#031b4e] flex items-center justify-center text-xl font-bold shadow-lg">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-text-main">Welcome, {{ auth()->user()->name }}</h1>
                <p class="text-sm text-text-dark/50 mt-0.5">Manage your job postings and find the best candidates.</p>
            </div>
        </div>
        <a href="#" class="px-5 py-2.5 bg-accent-yellow text-[#031b4e] rounded-xl text-sm font-bold hover:brightness-110 hover:-translate-y-0.5 transition-all shadow-lg hover:shadow-glow-yellow flex items-center gap-2">
            <i class="fas fa-plus"></i> Post New Job
        </a>
    </div>

    {{-- Empty State --}}
    <div class="bg-card-bg rounded-2xl border border-card-border p-12 text-center shadow-xl reveal reveal-delay-1">
        <div class="w-20 h-20 rounded-2xl bg-accent-yellow/10 text-accent-yellow flex items-center justify-center text-3xl mx-auto mb-5">
            <i class="fas fa-briefcase"></i>
        </div>
        <h3 class="text-xl font-bold text-text-main mb-2">No Active Jobs</h3>
        <p class="text-sm text-text-dark/40 max-w-md mx-auto mb-8 leading-relaxed">You haven't posted any job requirements yet. Post a job to start receiving candidate applications from qualified teachers.</p>
        <a href="#" class="inline-flex items-center px-8 py-3.5 bg-accent-yellow text-[#031b4e] rounded-xl font-bold hover:brightness-110 hover:-translate-y-0.5 transition-all shadow-lg hover:shadow-glow-yellow gap-2">
            <i class="fas fa-plus-circle"></i> Post Your First Job
        </a>
    </div>
</div>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
