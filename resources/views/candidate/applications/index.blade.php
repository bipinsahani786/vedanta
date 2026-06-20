@extends('layouts.app')

@section('content')
@include('candidate.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 reveal">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-lg">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-text-main">My Applications</h1>
                <p class="text-sm text-text-dark/50 mt-0.5">Track the status of jobs you've applied for.</p>
            </div>
        </div>
        <a href="{{ route('candidate.applications.available') }}" class="px-5 py-2.5 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover hover:-translate-y-0.5 transition-all shadow-lg flex items-center gap-2">
            <i class="fas fa-search text-xs"></i> Find More Jobs
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-500/10 border border-green-500/30 p-4 rounded-xl flex items-center gap-3 reveal">
            <i class="fas fa-check-circle text-green-400"></i>
            <span class="text-sm text-green-400 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Applications Table --}}
    <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl reveal reveal-delay-1">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-card-border">
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Institution & Role</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Applied On</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Match Score</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-card-border">
                    @forelse($applications as $app)
                    <tr class="hover:bg-secondary-bg/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-accent-blue/10 rounded-xl flex items-center justify-center text-accent-blue text-sm font-bold shrink-0">
                                    {{ strtoupper(substr($app->jobPost->school_name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-text-main">{{ $app->jobPost->title ?? 'Teacher' }}</div>
                                    <div class="text-xs text-text-dark/40 mt-0.5">{{ $app->jobPost->school_name }} &bull; {{ $app->jobPost->location->city }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-text-dark/50 text-sm">
                            {{ $app->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($app->match_score >= 80)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">
                                    <i class="fas fa-star mr-1 text-[9px]"></i> {{ $app->match_score }}%
                                </span>
                            @elseif($app->match_score >= 50)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-accent-blue/10 text-accent-blue border border-accent-blue/20">
                                    <i class="fas fa-thumbs-up mr-1 text-[9px]"></i> {{ $app->match_score }}%
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-card-border/50 text-text-dark/40">
                                    {{ $app->match_score }}%
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($app->status === 'applied')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-accent-blue/10 text-accent-blue border border-accent-blue/20">
                                    <i class="fas fa-paper-plane mr-1.5 text-[9px]"></i> Applied
                                </span>
                            @elseif($app->status === 'shortlisted')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-accent-yellow/10 text-accent-yellow border border-accent-yellow/20">
                                    <i class="fas fa-star mr-1.5 text-[9px]"></i> Shortlisted
                                </span>
                            @elseif($app->status === 'hired')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-green-500/10 text-green-400 border border-green-500/20">
                                    <i class="fas fa-check-circle mr-1.5 text-[9px]"></i> Hired
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                    <i class="fas fa-times-circle mr-1.5 text-[9px]"></i> Rejected
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 bg-card-border/30 rounded-2xl flex items-center justify-center text-text-dark/20 text-2xl mx-auto mb-4">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <h3 class="text-base font-semibold text-text-main mb-1">No Applications Yet</h3>
                            <p class="text-sm text-text-dark/40 mb-4">Start by browsing available jobs that match your profile.</p>
                            <a href="{{ route('candidate.applications.available') }}" class="text-accent-blue hover:underline text-sm font-semibold">
                                Browse Jobs &rarr;
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($applications->hasPages())
        <div class="p-4 border-t border-card-border">
            {{ $applications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
