@extends('layouts.app')

@section('content')
    @include('candidate.partials.nav')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 reveal">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-lg">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-text-main">My Applications</h1>
                    <p class="text-sm text-text-dark/50 mt-0.5">Track the status of jobs you've applied for.</p>
                </div>
            </div>
            <a href="{{ route('candidate.applications.available') }}"
                class="px-5 py-2.5 bg-accent-blue text-white rounded-xl text-sm font-semibold hover:bg-accent-blue-hover hover:-translate-y-0.5 transition-all shadow-lg flex items-center gap-2">
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
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">
                                Institution & Role</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Total
                                Allowed Applications</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Used
                                Applications</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">
                                Remaining Applications </th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Applied
                                Schools </th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">
                                Interview Status </th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-text-dark/40">
                                Placement Status </th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-card-border" x-data="{ expandedId: null }">
                        @php
                            $profile = auth()->user()->profile;
                            $totalAllowed = $profile->plan_type === 'premium' ? '∞' : $profile->total_allowed_applications;
                            $used = $profile->used_applications;
                            $remaining = $profile->plan_type === 'premium' ? '∞' : max(0, $profile->total_allowed_applications - $profile->used_applications);
                        @endphp
                        @forelse($applications as $app)
                            <tr class="hover:bg-secondary-bg/30 transition-colors cursor-pointer group"
                                @click="expandedId === {{ $app->id }} ? expandedId = null : expandedId = {{ $app->id }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-accent-blue/10 rounded-xl flex items-center justify-center text-accent-blue text-sm font-bold shrink-0 group-hover:scale-110 transition-transform">
                                            {{ strtoupper(substr($app->jobPost->school_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div
                                                class="font-semibold text-text-main flex items-center gap-2 hover:text-accent-blue transition-colors">
                                                <a href="{{ route('jobs.show', $app->jobPost->id) }}" target="_blank"
                                                    @click.stop>{{ $app->jobPost->title ?? 'Teacher' }}</a>
                                                <i class="fas fa-chevron-down text-[10px] text-text-dark/30 transition-transform"
                                                    :class="expandedId === {{ $app->id }} ? 'rotate-180 text-accent-blue' : ''"></i>
                                            </div>
                                            <div class="text-xs text-text-dark/40 mt-0.5">{{ $app->jobPost->school_name }}
                                                &bull; {{ $app->jobPost->location->city }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-text-main">
                                    {{ $totalAllowed }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-accent-blue">
                                    {{ $used }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-green-400">
                                    {{ $remaining }}
                                </td>
                                <td class="px-6 py-4 text-text-main text-sm font-medium">
                                    {{ $app->jobPost->school_name }}
                                </td>
                                <td class="px-6 py-4">
                                    @if(in_array($app->status, ['shortlisted', 'hired', 'rejected']))
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">
                                            <i class="fas fa-check mr-1 text-[9px]"></i> Scheduled
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-card-border/50 text-text-dark/40">
                                            <i class="fas fa-clock mr-1 text-[9px]"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($app->status === 'hired')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">
                                            <i class="fas fa-trophy mr-1 text-[9px]"></i> Placed
                                        </span>
                                    @elseif($app->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/20">
                                            <i class="fas fa-times mr-1 text-[9px]"></i> Not Selected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-accent-yellow/10 text-accent-yellow border border-accent-yellow/20">
                                            <i class="fas fa-spinner fa-spin mr-1 text-[9px]"></i> In Progress
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Expanded Tracking Details --}}
                            <tr x-show="expandedId === {{ $app->id }}" class="bg-secondary-bg/20" x-transition.opacity
                                style="display: none;">
                                <td colspan="7" class="px-6 py-6 border-b-2 border-accent-blue/30">
                                    <div class="p-5 border border-card-border rounded-2xl bg-card-bg shadow-inner">
                                        <h4 class="font-bold text-text-main mb-6 flex items-center gap-2">
                                            <i class="fas fa-route text-accent-blue"></i> Application Tracker
                                        </h4>

                                        <div
                                            class="relative flex flex-col md:flex-row justify-between w-full mb-6 gap-6 md:gap-0">
                                            <!-- Connecting Line -->
                                            <div
                                                class="absolute top-4 left-[10%] w-[80%] h-1 bg-card-border z-0 hidden md:block">
                                                <div class="h-full bg-accent-blue transition-all duration-500"
                                                    style="width: {{ in_array($app->status, ['shortlisted', 'hired', 'rejected']) ? (in_array($app->status, ['hired', 'rejected']) ? '100%' : '50%') : '0%' }}">
                                                </div>
                                            </div>

                                            <!-- Step 1: Applied -->
                                            <div class="relative z-10 flex flex-col items-center flex-1">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center shadow-[0_0_15px_rgba(34,197,94,0.4)] border-2 border-card-bg z-10">
                                                    <i class="fas fa-check text-xs"></i>
                                                </div>
                                                <div class="mt-3 text-sm font-bold text-text-main">Applied</div>
                                                <div class="text-[10px] text-text-dark/50">
                                                    {{ $app->created_at->format('d M, Y h:i A') }}
                                                </div>
                                            </div>

                                            <!-- Step 2: Forwarded -->
                                            <div class="relative z-10 flex flex-col items-center flex-1">
                                                <div
                                                    class="w-8 h-8 rounded-full flex items-center justify-center border-2 border-card-bg z-10 transition-colors duration-300 {{ in_array($app->status, ['shortlisted', 'hired', 'rejected']) ? 'bg-accent-yellow text-[#031b4e] shadow-[0_0_15px_rgba(255,184,0,0.4)]' : 'bg-card-border text-text-dark/40' }}">
                                                    <i
                                                        class="fas {{ in_array($app->status, ['shortlisted', 'hired', 'rejected']) ? 'fa-check' : 'fa-hourglass-half' }} text-xs"></i>
                                                </div>
                                                <div
                                                    class="mt-3 text-sm font-bold {{ in_array($app->status, ['shortlisted', 'hired', 'rejected']) ? 'text-text-main' : 'text-text-dark/50' }}">
                                                    Forwarded to School</div>
                                                @if($app->is_forwarded)
                                                    <div class="text-[10px] text-text-dark/50"><i
                                                            class="fas fa-share text-[8px]"></i> Profile sent to employer</div>
                                                @else
                                                    <div class="text-[10px] text-text-dark/40">Pending admin review</div>
                                                @endif
                                            </div>

                                            <!-- Step 3: Final Decision -->
                                            <div class="relative z-10 flex flex-col items-center flex-1">
                                                <div
                                                    class="w-8 h-8 rounded-full flex items-center justify-center border-2 border-card-bg z-10 transition-colors duration-300 
                                                                {{ $app->status === 'hired' ? 'bg-green-500 text-white shadow-[0_0_15px_rgba(34,197,94,0.4)]' : ($app->status === 'rejected' ? 'bg-red-500 text-white shadow-[0_0_15px_rgba(239,68,68,0.4)]' : 'bg-card-border text-text-dark/40') }}">
                                                    @if($app->status === 'hired')
                                                        <i class="fas fa-trophy text-xs"></i>
                                                    @elseif($app->status === 'rejected')
                                                        <i class="fas fa-times text-xs"></i>
                                                    @else
                                                        <i class="fas fa-question text-xs"></i>
                                                    @endif
                                                </div>
                                                <div
                                                    class="mt-3 text-sm font-bold 
                                                                {{ $app->status === 'hired' ? 'text-green-400' : ($app->status === 'rejected' ? 'text-red-400' : 'text-text-dark/50') }}">
                                                    {{ $app->status === 'hired' ? 'Selected / Hired' : ($app->status === 'rejected' ? 'Not Selected' : 'Final Decision') }}
                                                </div>
                                                <div
                                                    class="text-[10px] {{ in_array($app->status, ['hired', 'rejected']) ? 'text-text-dark/50' : 'text-text-dark/40' }}">
                                                    {{ in_array($app->status, ['hired', 'rejected']) ? 'Process completed' : 'Awaiting interview feedback' }}
                                                </div>
                                            </div>
                                        </div>

                                        @if($app->remarks)
                                            <div
                                                class="mt-6 p-4 bg-accent-blue/5 border border-accent-blue/20 rounded-xl relative overflow-hidden">
                                                <div class="absolute -right-4 -top-4 text-accent-blue/10 text-5xl"><i
                                                        class="fas fa-quote-right"></i></div>
                                                <h5
                                                    class="text-xs font-bold text-accent-blue uppercase tracking-wider mb-2 flex items-center gap-2">
                                                    <i class="fas fa-comment-dots"></i> Update / Remarks:
                                                </h5>
                                                <p class="text-sm text-text-main relative z-10 leading-relaxed">{{ $app->remarks }}
                                                </p>
                                            </div>
                                        @elseif($app->status === 'shortlisted')
                                            <div class="mt-6 p-4 bg-accent-yellow/5 border border-accent-yellow/20 rounded-xl">
                                                <p class="text-sm text-text-main flex items-center gap-2">
                                                    <i class="fas fa-info-circle text-accent-yellow"></i>
                                                    Your profile has been forwarded to the school. The school will contact you
                                                    directly to schedule an interview.
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div
                                        class="w-16 h-16 bg-card-border/30 rounded-2xl flex items-center justify-center text-text-dark/20 text-2xl mx-auto mb-4">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                    <h3 class="text-base font-semibold text-text-main mb-1">No Applications Yet</h3>
                                    <p class="text-sm text-text-dark/40 mb-4">Start by browsing available jobs that match your
                                        profile.</p>
                                    <a href="{{ route('candidate.applications.available') }}"
                                        class="text-accent-blue hover:underline text-sm font-semibold">
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