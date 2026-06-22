@extends('layouts.app')

@section('content')
@include('employer.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-text-main">Candidates</h1>
            <p class="text-sm text-text-dark/50 mt-0.5">View candidates whose applications have been forwarded to you for an interview.</p>
        </div>
        <form action="{{ route('employer.applicants.index') }}" method="GET" class="flex items-center gap-2">
            <select name="job_post_id" class="bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:border-accent-yellow focus:outline-none" onchange="this.form.submit()">
                <option value="">All Jobs</option>
                @foreach($myJobs as $job)
                    <option value="{{ $job->id }}" {{ request('job_post_id') == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                @endforeach
            </select>
            @if(request('job_post_id'))
                <a href="{{ route('employer.applicants.index') }}" class="text-text-dark/50 hover:text-red-400 text-sm font-bold ml-2">Clear</a>
            @endif
        </form>
    </div>

    <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-secondary-bg/50 border-b border-card-border text-xs text-text-dark uppercase tracking-wider font-bold">
                    <th class="py-4 px-6">Candidate Details</th>
                    <th class="py-4 px-6">Applied For</th>
                    <th class="py-4 px-6">Experience & Qual.</th>
                    <th class="py-4 px-6">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-card-border">
                @forelse($applications as $app)
                <tr class="hover:bg-secondary-bg/30 transition-colors group">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-accent-blue/20 to-accent-blue/10 text-accent-blue flex items-center justify-center font-bold">
                                {{ strtoupper(substr($app->candidate->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-bold text-text-main">{{ $app->candidate->name }}</div>
                                <div class="text-xs text-text-dark/60 mt-0.5 flex flex-col gap-0.5">
                                    <span><i class="fas fa-envelope text-[10px] w-3"></i> {{ $app->candidate->email }}</span>
                                    <span><i class="fas fa-phone-alt text-[10px] w-3"></i> {{ $app->candidate->phone }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-semibold text-text-main">{{ $app->jobPost->title }}</div>
                        <div class="text-xs text-text-dark/50">Forwarded on {{ $app->updated_at->format('M d, Y') }}</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-sm font-medium text-text-main">{{ $app->candidate->profile->years_of_experience ?? '0' }} Years</div>
                        <div class="text-xs text-text-dark/50">{{ $app->candidate->profile->highestQualification->name ?? 'N/A' }}</div>
                    </td>
                    <td class="py-4 px-6">
                        @if($app->status === 'shortlisted')
                            <span class="bg-accent-yellow/10 text-accent-yellow px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider flex items-center gap-1 w-max">
                                <i class="fas fa-hourglass-half"></i> Pending Interview
                            </span>
                        @elseif($app->status === 'hired')
                            <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider flex items-center gap-1 w-max">
                                <i class="fas fa-check-circle"></i> Selected
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-16 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-secondary-bg text-text-dark/20 flex items-center justify-center text-3xl mx-auto mb-4 border border-card-border">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <p class="text-text-main font-bold text-lg mb-1">No candidates forwarded yet</p>
                        <p class="text-text-dark/40 text-sm">When the admin forwards a candidate for your jobs, they will appear here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($applications->hasPages())
    <div class="mt-6 flex justify-end">
        {{ $applications->links('pagination::tailwind') }}
    </div>
    @endif

</div>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
