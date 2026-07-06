@extends('layouts.app')

@section('content')
<x-page-header title="{{ $job->title ?? 'Job Requirement' }}" :breadcrumbs="['Home' => route('home'), 'Jobs' => route('jobs'), 'Job Details' => null]" />

<div class="py-12 px-6 lg:px-[5%] bg-primary-bg min-h-screen">
    <div class="max-w-4xl mx-auto bg-card-bg border border-card-border rounded-2xl shadow-xl overflow-hidden reveal">
        <!-- Job Header -->
        <div class="p-8 border-b border-card-border">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center p-2 shadow-sm border border-gray-100">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($job->school_name) }}&background=random" class="rounded">
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-text-main mb-1">{{ $job->title ?? 'Job Requirement' }}</h1>
                        <p class="text-text-main opacity-60 flex items-center gap-2 text-sm">
                            <i class="fas fa-building text-accent-blue"></i> {{ $job->school_name }}
                            <span class="mx-1">•</span>
                            <i class="fas fa-map-marker-alt text-accent-blue"></i> {{ $job->location->city }}, {{ $job->location->state }}
                        </p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    @auth
                        @if(auth()->user()->role === 'candidate')
                            <form action="{{ route('candidate.applications.apply', $job->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-8 py-3 bg-accent-blue text-white font-bold rounded-xl shadow-lg hover:bg-accent-blue-hover hover:-translate-y-0.5 transition-all inline-block text-center">
                                    Apply Now
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('candidate.register') }}" class="px-8 py-3 bg-accent-blue text-white font-bold rounded-xl shadow-lg hover:bg-accent-blue-hover hover:-translate-y-0.5 transition-all inline-block text-center">
                            Login to Apply
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Job Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-card-border bg-secondary-bg/30">
            <div class="p-6 text-center">
                <div class="w-10 h-10 rounded-full bg-accent-blue/10 text-accent-blue flex items-center justify-center mx-auto mb-3 text-lg"><i class="fas fa-book"></i></div>
                <h4 class="text-xs font-bold text-text-dark/50 uppercase tracking-wider mb-1">Subject</h4>
                <p class="font-semibold text-text-main">{{ $job->subject?->name ?? 'N/A' }}</p>
            </div>
            <div class="p-6 text-center">
                <div class="w-10 h-10 rounded-full bg-purple-500/10 text-purple-500 flex items-center justify-center mx-auto mb-3 text-lg"><i class="fas fa-graduation-cap"></i></div>
                <h4 class="text-xs font-bold text-text-dark/50 uppercase tracking-wider mb-1">Qualification</h4>
                <p class="font-semibold text-text-main">{{ $job->qualification?->name ?? 'N/A' }}</p>
            </div>
            <div class="p-6 text-center">
                <div class="w-10 h-10 rounded-full bg-green-500/10 text-green-500 flex items-center justify-center mx-auto mb-3 text-lg"><i class="fas fa-rupee-sign"></i></div>
                <h4 class="text-xs font-bold text-text-dark/50 uppercase tracking-wider mb-1">Salary Range</h4>
                <p class="font-semibold text-text-main">{{ $job->salary_range ?? 'Not specified' }}</p>
            </div>
        </div>

        <!-- Job Description -->
        <div class="p-8">
            <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-accent-blue"></i> Job Description & Requirements
            </h3>
            <div class="prose prose-sm max-w-none text-text-main opacity-80 leading-relaxed space-y-4">
                {!! nl2br(e($job->description ?? 'No detailed description provided for this job role.')) !!}
            </div>

            <div class="mt-10 pt-6 border-t border-card-border flex justify-between items-center">
                <div class="text-sm text-text-main opacity-50">
                    Posted on {{ $job->created_at->format('d M, Y') }}
                </div>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-accent-blue/10 text-accent-blue text-xs font-bold rounded-lg border border-accent-blue/20">
                        <i class="fas fa-tag"></i> {{ $job->category?->name ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Application Footer -->
        @guest
        <div class="p-8 bg-gradient-to-r from-accent-blue to-accent-blue-hover text-white flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold mb-1">Interested in this role?</h3>
                <p class="text-white/80 text-sm">Join Vedanta Agency and apply directly to top schools.</p>
            </div>
            <a href="{{ route('candidate.register') }}" class="px-8 py-3 bg-white text-accent-blue font-bold rounded-xl shadow-lg hover:bg-gray-50 hover:-translate-y-0.5 transition-all whitespace-nowrap">
                Register as Teacher
            </a>
        </div>
        @endguest
    </div>
</div>
@endsection
