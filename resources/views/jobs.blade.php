@extends('layouts.app')
@section('content')
<x-page-header title="Find Your Dream Role" :breadcrumbs="['Home' => route('home'), 'Jobs' => null]" />
<div class="py-12 px-6 lg:px-[5%] bg-card-bg/30 border-b border-card-border">
    <div class="max-w-4xl mx-auto text-center reveal">
        <!-- Search Bar -->
        <div class="bg-primary-bg border border-card-border p-2 rounded-full flex items-center shadow-lg mx-auto mb-6 transition-all focus-within:border-accent-blue focus-within:shadow-[0_4px_20px_rgba(var(--theme-accent-blue-rgb),0.2)]">
            <div class="px-4 text-text-main opacity-50"><i class="fas fa-search"></i></div>
            <input type="text" placeholder="Job title, keywords, or school..." class="bg-transparent border-none outline-none text-text-main flex-grow text-sm py-2">
            <div class="hidden md:flex border-l border-card-border px-4 text-text-main opacity-50 items-center gap-2">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" placeholder="Location" class="bg-transparent border-none outline-none text-text-main text-sm w-32">
            </div>
            <button class="bg-accent-blue text-white font-bold px-6 py-2.5 rounded-full text-sm hover:opacity-90 transition-opacity">Search</button>
        </div>
        <div class="flex flex-wrap justify-center gap-2 text-xs text-text-main opacity-70">
            <span>Popular:</span>
            <a href="#" class="hover:text-accent-blue transition-colors">Mathematics Teacher</a>,
            <a href="#" class="hover:text-accent-blue transition-colors">Principal</a>,
            <a href="#" class="hover:text-accent-blue transition-colors">Computer Science</a>
        </div>
    </div>
</div>

<div class="py-12 px-6 lg:px-[5%] flex flex-col lg:flex-row gap-8">
    <!-- Filters -->
    <div class="w-full lg:w-1/4">
        <div class="bg-card-bg border border-card-border rounded-xl p-6 sticky top-28">
            <h3 class="text-lg font-bold text-text-main mb-4">Filters</h3>
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-text-main mb-3">Job Type</h4>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm text-text-main opacity-80 cursor-pointer hover:text-accent-blue transition-colors"><input type="checkbox" class="accent-accent-blue"> Full Time</label>
                    <label class="flex items-center gap-2 text-sm text-text-main opacity-80 cursor-pointer hover:text-accent-blue transition-colors"><input type="checkbox" class="accent-accent-blue"> Part Time</label>
                    <label class="flex items-center gap-2 text-sm text-text-main opacity-80 cursor-pointer hover:text-accent-blue transition-colors"><input type="checkbox" class="accent-accent-blue"> Contract</label>
                </div>
            </div>
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-text-main mb-3">Category</h4>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm text-text-main opacity-80 cursor-pointer hover:text-accent-blue transition-colors"><input type="checkbox" class="accent-accent-blue"> Teaching Staff</label>
                    <label class="flex items-center gap-2 text-sm text-text-main opacity-80 cursor-pointer hover:text-accent-blue transition-colors"><input type="checkbox" class="accent-accent-blue"> Administration</label>
                    <label class="flex items-center gap-2 text-sm text-text-main opacity-80 cursor-pointer hover:text-accent-blue transition-colors"><input type="checkbox" class="accent-accent-blue"> Support Staff</label>
                </div>
            </div>
            <button class="w-full border border-accent-blue text-accent-blue rounded-lg py-2 text-sm font-semibold hover:bg-accent-blue/10 transition-colors">Apply Filters</button>
        </div>
    </div>

    <!-- Job List -->
    <div class="w-full lg:w-3/4 space-y-4">
        @forelse($jobs as $job)
        <div class="bg-card-bg border border-card-border rounded-xl p-6 hover:border-accent-blue/50 hover:shadow-lg transition-all group reveal">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white rounded-lg flex items-center justify-center p-2">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($job->school_name) }}&background=random" class="rounded">
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-text-main group-hover:text-accent-blue transition-colors">
                            <a href="{{ route('jobs.show', $job->id) }}">{{ $job->title ?? 'Job Requirement' }}</a>
                        </h3>
                        <p class="text-sm text-text-main opacity-60">{{ $job->school_name }} • {{ $job->location->city }}, {{ $job->location->state }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="bg-accent-blue/10 text-accent-blue px-3 py-1 rounded-full text-xs font-bold">{{ $job->category->name }}</span>
                    <p class="text-xs text-text-main opacity-50 mt-2">Posted {{ $job->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <p class="text-sm text-text-main opacity-70 leading-relaxed mb-4">
                {{ Str::limit($job->description, 150) }}
            </p>
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <span class="bg-card-bg border border-card-border px-2.5 py-1 rounded-lg text-[11px] font-semibold text-text-main opacity-80 flex items-center gap-1.5">
                    <i class="fas fa-book text-accent-blue text-[9px]"></i> {{ $job->subject->name }}
                </span>
                <span class="bg-card-bg border border-card-border px-2.5 py-1 rounded-lg text-[11px] font-semibold text-text-main opacity-80 flex items-center gap-1.5">
                    <i class="fas fa-graduation-cap text-accent-blue text-[9px]"></i> {{ $job->qualification->name }}
                </span>
            </div>
            <div class="flex justify-between items-center border-t border-card-border pt-4">
                <div class="flex gap-4 text-xs text-text-main opacity-60">
                    @if($job->salary_range)
                    <span><i class="fas fa-rupee-sign"></i> {{ $job->salary_range }}</span>
                    @endif
                </div>
                <a href="{{ route('jobs.show', $job->id) }}" class="text-accent-blue font-semibold text-sm hover:underline">Apply Now <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
        </div>
        @empty
        <div class="text-center py-12 border border-card-border rounded-xl">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 text-2xl mx-auto mb-4"><i class="fas fa-briefcase"></i></div>
            <h3 class="text-lg font-bold text-text-main mb-2">No Active Jobs</h3>
            <p class="text-text-main opacity-60 text-sm">We currently don't have any job openings that match your criteria.</p>
        </div>
        @endforelse

        <div class="mt-8">
            {{ $jobs->links() }}
        </div>
    </div>
</div>
@endsection