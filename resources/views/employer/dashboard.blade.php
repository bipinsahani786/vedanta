@extends('layouts.app')

@section('content')
{{-- Employer Navigation --}}
@include('employer.partials.nav')

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
        <a href="{{ route('employer.jobs.create') }}" class="px-5 py-2.5 bg-accent-yellow text-[#031b4e] rounded-xl text-sm font-bold hover:brightness-110 hover:-translate-y-0.5 transition-all shadow-lg hover:shadow-glow-yellow flex items-center gap-2">
            <i class="fas fa-plus"></i> Post New Job
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10 reveal">
        <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-accent-yellow/10 rounded-full blur-xl group-hover:bg-accent-yellow/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-accent-yellow/10 text-accent-yellow flex items-center justify-center text-xl">
                    <i class="fas fa-briefcase"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-text-main relative z-10">{{ $stats['total_jobs'] }}</h3>
            <p class="text-xs font-semibold text-text-dark/50 uppercase tracking-wide mt-1 relative z-10">Total Jobs Posted</p>
        </div>

        <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-500/10 rounded-full blur-xl group-hover:bg-green-500/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-green-500/10 text-green-500 flex items-center justify-center text-xl">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-text-main relative z-10">{{ $stats['active_jobs'] }}</h3>
            <p class="text-xs font-semibold text-text-dark/50 uppercase tracking-wide mt-1 relative z-10">Active Jobs</p>
        </div>

        <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500/10 rounded-full blur-xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center text-xl">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-text-main relative z-10">{{ $stats['pending_jobs'] }}</h3>
            <p class="text-xs font-semibold text-text-dark/50 uppercase tracking-wide mt-1 relative z-10">Pending Approval</p>
        </div>

        <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-purple-500/10 rounded-full blur-xl group-hover:bg-purple-500/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center text-xl">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-text-main relative z-10">{{ $stats['total_candidates'] }}</h3>
            <p class="text-xs font-semibold text-text-dark/50 uppercase tracking-wide mt-1 relative z-10">Shortlisted/Hired Candidates</p>
        </div>
    </div>

    @if($recentJobs->count() > 0)
        <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl reveal">
            <div class="px-6 py-4 border-b border-card-border flex justify-between items-center bg-black/20">
                <h3 class="font-bold text-text-main flex items-center gap-2">
                    <i class="fas fa-history text-accent-yellow"></i> Recent Jobs
                </h3>
                <a href="{{ route('employer.jobs.index') }}" class="text-xs font-semibold text-accent-yellow hover:text-white transition-colors">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-card-border/50 bg-black/10">
                            <th class="px-6 py-4 text-xs font-semibold text-text-dark/50 uppercase tracking-wider">Job Title</th>
                            <th class="px-6 py-4 text-xs font-semibold text-text-dark/50 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-4 text-xs font-semibold text-text-dark/50 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-text-dark/50 uppercase tracking-wider">Posted On</th>
                            <th class="px-6 py-4 text-xs font-semibold text-text-dark/50 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-card-border/50">
                        @foreach($recentJobs as $job)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-text-main">{{ $job->title ?? 'N/A' }}</div>
                                    <div class="text-xs text-text-dark/50 mt-1"><i class="fas fa-map-marker-alt text-accent-yellow/70 mr-1"></i> {{ $job->location->city ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-white/5 text-text-main border border-white/10">
                                        {{ $job->category->name ?? '' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($job->status === 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Approved
                                        </span>
                                    @elseif($job->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-text-dark/70">
                                    {{ $job->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('employer.jobs.show', $job->id) }}" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-text-dark hover:text-accent-yellow hover:bg-accent-yellow/10 transition-colors" title="View Job">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($job->status === 'pending')
                                            <a href="{{ route('employer.jobs.edit', $job->id) }}" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-text-dark hover:text-blue-400 hover:bg-blue-400/10 transition-colors" title="Edit Job">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-card-bg rounded-2xl border border-card-border p-12 text-center shadow-xl reveal reveal-delay-1">
            <div class="w-20 h-20 rounded-2xl bg-accent-yellow/10 text-accent-yellow flex items-center justify-center text-3xl mx-auto mb-5">
                <i class="fas fa-briefcase"></i>
            </div>
            <h3 class="text-xl font-bold text-text-main mb-2">No Active Jobs</h3>
            <p class="text-sm text-text-dark/40 max-w-md mx-auto mb-8 leading-relaxed">You haven't posted any job requirements yet. Post a job to start receiving candidate applications from qualified teachers.</p>
            <a href="{{ route('employer.jobs.create') }}" class="inline-flex items-center px-8 py-3.5 bg-accent-yellow text-[#031b4e] rounded-xl font-bold hover:brightness-110 hover:-translate-y-0.5 transition-all shadow-lg hover:shadow-glow-yellow gap-2">
                <i class="fas fa-plus-circle"></i> Post Your First Job
            </a>
        </div>
    @endif
</div>
@endsection
