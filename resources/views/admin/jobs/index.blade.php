@extends('layouts.admin')

@php
    $isPendingView = request('status') === 'pending';
    $title = $isPendingView ? 'Job Approvals' : 'Live Jobs';
@endphp

@section('title', $title)

@section('actions')
    <a href="{{ route('admin.jobs.create') }}" class="px-4 py-2 bg-accent-blue text-white hover:bg-accent-blue-hover rounded-xl text-sm font-semibold shadow-lg shadow-accent-blue/30 transition-all flex items-center gap-2">
        <i class="fas fa-plus"></i> Post New Job
    </a>
@endsection

@section('content')

{{-- Filter/Search Bar --}}
<div class="bg-card-bg rounded-t-2xl border-x border-t border-card-border p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
    <div class="text-sm text-text-dark/50 font-medium">
        Showing {{ $jobs->firstItem() ?? 0 }} to {{ $jobs->lastItem() ?? 0 }} of {{ $jobs->total() }} entries
    </div>
    <form action="{{ route('admin.jobs.index') }}" method="GET" class="w-full sm:w-auto flex items-center relative gap-3">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        
        <div class="relative w-full sm:w-72">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-text-dark/40 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title or school..." 
                   class="w-full pl-9 pr-8 py-2 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
            @if(request('search'))
                <a href="{{ route('admin.jobs.index', ['status' => request('status')]) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-text-dark/40 hover:text-red-400 transition-colors">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
        <button type="submit" class="hidden sm:block px-4 py-2 bg-secondary-bg hover:bg-card-border border border-card-border text-text-main rounded-xl text-sm transition-colors">
            Filter
        </button>
    </form>
</div>

{{-- Data Table --}}
<div class="bg-card-bg rounded-b-2xl border border-card-border overflow-x-auto shadow-xl">
    <table class="w-full text-left border-collapse admin-table">
        <thead>
            <tr>
                @php
                    $route = 'admin.jobs.index';
                    $order = request('order') === 'asc' ? 'desc' : 'asc';
                @endphp
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'title', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Job Title & School
                        @if(request('sort_by') === 'title')
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th>Subject & Category</th>
                <th>Location</th>
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'status', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Status
                        @if(request('sort_by') === 'status')
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route($route, array_merge(request()->query(), ['sort_by' => 'created_at', 'order' => $order])) }}" class="flex items-center gap-2 hover:text-accent-blue transition-colors">
                        Posted On
                        @if(request('sort_by') === 'created_at' || !request('sort_by'))
                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-accent-blue"></i>
                        @else
                            <i class="fas fa-sort text-text-dark/20"></i>
                        @endif
                    </a>
                </th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-card-border">
            @forelse($jobs as $job)
            <tr class="group">
                <td>
                    <div class="font-semibold text-text-main group-hover:text-accent-blue transition-colors">{{ $job->title ?? 'Untitled Job' }}</div>
                    <div class="text-xs text-text-dark/50 flex items-center gap-1 mt-1">
                        <i class="fas fa-building text-[10px]"></i> {{ $job->school_name }}
                    </div>
                </td>
                <td>
                    <div class="text-sm text-text-main font-medium">{{ $job->subject?->name ?? 'N/A' }}</div>
                    <div class="text-[10px] text-text-dark/40 uppercase tracking-wider mt-1">{{ $job->category?->name ?? 'N/A' }}</div>
                </td>
                <td>
                    <div class="text-sm text-text-main flex items-center gap-1.5">
                        <i class="fas fa-map-marker-alt text-red-400"></i> {{ $job->city?->name ?? 'N/A' }}, {{ $job->state?->name ?? '' }}
                    </div>
                </td>
                <td>
                    @if($job->status === 'approved')
                        <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-green-500/20 uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-check-circle"></i> Live
                        </span>
                    @elseif($job->status === 'pending')
                        <span class="bg-accent-yellow/10 text-accent-yellow px-2.5 py-1 rounded-lg text-[10px] font-bold border border-accent-yellow/20 uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-clock"></i> Pending
                        </span>
                    @else
                        <span class="bg-red-500/10 text-red-400 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-red-500/20 uppercase tracking-wider flex items-center gap-1 w-max">
                            <i class="fas fa-times-circle"></i> Rejected
                        </span>
                    @endif
                </td>
                <td class="text-text-dark/60 text-sm">
                    {{ $job->created_at->format('M d, Y') }}
                    <div class="text-[10px] text-text-dark/40 mt-0.5">{{ $job->created_at->diffForHumans() }}</div>
                </td>
                <td>
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.jobs.show', $job) }}" class="w-8 h-8 rounded-lg bg-accent-blue/10 text-accent-blue flex items-center justify-center hover:bg-accent-blue hover:text-white transition-colors tooltip" title="Review Job">
                            <i class="fas fa-eye text-xs"></i>
                        </a>
                        <a href="{{ route('admin.jobs.edit', $job) }}" class="w-8 h-8 rounded-lg bg-accent-yellow/10 text-accent-yellow flex items-center justify-center hover:bg-accent-yellow hover:text-white transition-colors tooltip" title="Edit Job">
                            <i class="fas fa-pen text-xs"></i>
                        </a>
                        
                        @if($job->status === 'pending')
                            <form action="{{ route('admin.jobs.approve', $job) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="create_account" value="1">
                                <button type="submit" class="w-8 h-8 rounded-lg bg-green-500/10 text-green-500 flex items-center justify-center hover:bg-green-500 hover:text-white transition-colors tooltip" title="Approve & Generate Account">
                                    <i class="fas fa-check text-xs"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.jobs.reject', $job) }}" method="POST" class="inline" onsubmit="return confirm('Reject this job?');">
                                @csrf
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors tooltip" title="Reject">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this job?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors tooltip" title="Delete">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-16 text-center">
                    <div class="w-16 h-16 bg-secondary-bg rounded-2xl flex items-center justify-center text-text-dark/20 text-3xl mx-auto mb-4 border border-card-border">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <p class="text-text-main font-bold text-lg mb-1">No jobs found</p>
                    <p class="text-text-dark/40 text-sm">
                        {{ $isPendingView ? "There are currently no job queries awaiting your approval." : "Try adjusting your search criteria." }}
                    </p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($jobs->hasPages())
<div class="mt-6 flex justify-end">
    {{ $jobs->links('pagination::tailwind') }}
</div>
@endif

@endsection
