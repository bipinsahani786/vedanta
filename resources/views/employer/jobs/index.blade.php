@extends('layouts.app')

@section('content')
@include('employer.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-text-main">My Jobs</h1>
            <p class="text-sm text-text-dark/50 mt-0.5">Manage your job postings and track their approval status.</p>
        </div>
        <a href="{{ route('employer.jobs.create') }}" class="px-5 py-2.5 bg-accent-yellow text-[#031b4e] rounded-xl text-sm font-bold hover:brightness-110 hover:-translate-y-0.5 transition-all shadow-lg hover:shadow-glow-yellow flex items-center gap-2">
            <i class="fas fa-plus"></i> Post New Job
        </a>
    </div>

    <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-secondary-bg/50 border-b border-card-border text-xs text-text-dark uppercase tracking-wider font-bold">
                    <th class="py-4 px-6">Job Title</th>
                    <th class="py-4 px-6">Category</th>
                    <th class="py-4 px-6">Status</th>
                    <th class="py-4 px-6">Posted On</th>
                    <th class="py-4 px-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-card-border">
                @forelse($jobs as $job)
                <tr class="hover:bg-secondary-bg/30 transition-colors group">
                    <td class="py-4 px-6">
                        <div class="font-bold text-text-main">{{ $job->title }}</div>
                        <div class="text-xs text-text-dark/60 mt-0.5"><i class="fas fa-map-marker-alt text-accent-yellow/70 mr-1"></i> {{ $job->location->city ?? 'N/A' }}</div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-white/5 text-text-main border border-white/10">
                            {{ $job->category->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
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
                    <td class="py-4 px-6 text-sm text-text-dark/70">
                        {{ $job->created_at->format('M d, Y') }}
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('employer.jobs.show', $job->id) }}" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-text-dark hover:text-accent-yellow hover:bg-accent-yellow/10 transition-colors" title="View Job">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($job->status === 'pending')
                                <a href="{{ route('employer.jobs.edit', $job->id) }}" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-text-dark hover:text-blue-400 hover:bg-blue-400/10 transition-colors" title="Edit Job">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif
                            @if($job->status === 'approved')
                                <a href="{{ route('employer.applicants.index', ['job_post_id' => $job->id]) }}" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-text-dark hover:text-green-400 hover:bg-green-400/10 transition-colors" title="View Applicants">
                                    <i class="fas fa-users"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-16 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-secondary-bg text-text-dark/20 flex items-center justify-center text-3xl mx-auto mb-4 border border-card-border">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <p class="text-text-main font-bold text-lg mb-1">No jobs posted yet</p>
                        <p class="text-text-dark/40 text-sm mb-4">You haven't posted any jobs. Create your first job posting now.</p>
                        <a href="{{ route('employer.jobs.create') }}" class="inline-block px-6 py-2.5 bg-white/5 hover:bg-white/10 text-white rounded-xl text-sm font-semibold transition-colors">
                            Post a Job
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($jobs->hasPages())
    <div class="mt-6 flex justify-end">
        {{ $jobs->links('pagination::tailwind') }}
    </div>
    @endif
</div>
@endsection
