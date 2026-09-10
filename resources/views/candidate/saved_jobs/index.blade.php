@extends('layouts.candidate')

@section('candidate_content')
<div class="space-y-6 pb-8">

    {{-- Page Header --}}
    <div class="bg-gradient-to-r from-[#031544] via-[#092b7a] to-[#1e0e47] border border-blue-400/25 rounded-3xl p-6 sm:p-7 text-white shadow-[0_10px_35px_rgba(3,27,78,0.45)] relative overflow-hidden">
        {{-- Decorative ambient glow --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 left-1/3 w-80 h-80 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center text-2xl shrink-0 shadow-lg">
                    <i class="fas fa-bookmark"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Saved Jobs</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                            {{ $savedJobs->total() }} {{ \Illuminate\Support\Str::plural('Job', $savedJobs->total()) }}
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-blue-200/80 mt-1">
                        Review your bookmarked opportunities and apply when you are ready.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('jobs') }}" 
                   class="px-4 py-2 rounded-xl bg-accent-blue/20 hover:bg-accent-blue text-accent-blue hover:text-white border border-accent-blue/30 font-bold text-xs flex items-center gap-2 transition-all shadow-sm">
                    <i class="fas fa-search text-xs"></i>
                    <span>Explore More Jobs</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 px-4 py-3 rounded-2xl text-xs font-semibold flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-400 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Saved Jobs Grid --}}
    @if($savedJobs->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($savedJobs as $saved)
                @php
                    $job = $saved->jobPost;
                @endphp
                @if($job)
                    <div id="saved-job-card-{{ $saved->id }}" 
                         class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-2xl border border-white/[0.08] p-5 hover:border-accent-blue/40 hover:-translate-y-1 hover:shadow-[0_12px_35px_rgba(0,0,0,0.35)] transition-all duration-300 flex flex-col justify-between group relative"
                         x-data="{
                             removed: false,
                             loading: false,
                             async removeJob() {
                                 if (this.loading) return;
                                 this.loading = true;
                                 try {
                                     const res = await fetch('{{ route('candidate.jobs.toggleSave', $job->id) }}', {
                                         method: 'POST',
                                         headers: {
                                             'Content-Type': 'application/json',
                                             'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                             'Accept': 'application/json'
                                         }
                                     });
                                     const data = await res.json();
                                     if (!data.saved) {
                                         this.removed = true;
                                     }
                                 } catch (e) {
                                     console.error(e);
                                 } finally {
                                     this.loading = false;
                                 }
                             }
                         }"
                         x-show="!removed"
                         x-transition:leave="transition ease-in duration-300 transform"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-4">
                        
                        <div>
                            {{-- Card Top Row --}}
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-accent-blue/15 border border-accent-blue/30 text-accent-blue font-black flex items-center justify-center text-xs shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                                        {{ strtoupper(substr($job->title ?? 'TR', 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-sm font-bold text-white group-hover:text-accent-blue transition-colors line-clamp-1">
                                            {{ $job->title }}
                                        </h3>
                                        <p class="text-xs text-slate-400 line-clamp-1 mt-0.5">
                                            {{ $job->school_name ?? 'Trusted Education Partner' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Bookmark / Remove Button --}}
                                <button type="button" 
                                        @click="removeJob()" 
                                        :disabled="loading"
                                        class="text-amber-400 hover:text-rose-400 p-1.5 rounded-lg hover:bg-white/10 transition-colors"
                                        title="Remove from Saved">
                                    <i class="fas fa-bookmark text-sm"></i>
                                </button>
                            </div>

                            {{-- Meta Badges Row --}}
                            <div class="flex items-center gap-2 flex-wrap mb-3.5">
                                @if($job->subject)
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-accent-blue/15 text-accent-blue border border-accent-blue/25">
                                        {{ $job->subject->name }}
                                    </span>
                                @endif
                                @if($job->category)
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-white/5 text-slate-300 border border-white/10">
                                        {{ $job->category->name }}
                                    </span>
                                @endif
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">
                                    Full Time
                                </span>
                            </div>

                            {{-- Location & Salary --}}
                            <div class="space-y-1.5 text-xs text-slate-300 mb-4 bg-white/[0.02] p-3 rounded-xl border border-white/[0.05]">
                                <div class="flex items-center gap-2 text-slate-400">
                                    <i class="fas fa-map-marker-alt text-amber-400 text-xs w-3 text-center"></i>
                                    <span>{{ $job->city->name ?? 'Bihar' }}, {{ $job->state->name ?? 'India' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-white font-bold">
                                    <i class="fas fa-wallet text-emerald-400 text-xs w-3 text-center"></i>
                                    <span>₹{{ $job->salary_range ?? '25,000 - 35,000 / Month' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                    <i class="fas fa-clock text-sky-400 text-xs w-3 text-center"></i>
                                    <span>Saved {{ $saved->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="pt-3 border-t border-white/[0.08] flex items-center gap-2">
                            <a href="{{ route('jobs.show', $job->id) }}" 
                               class="flex-1 py-2.5 px-3 rounded-xl bg-accent-blue hover:bg-accent-blue/90 text-white font-bold text-xs flex items-center justify-center gap-2 transition-all shadow-md shadow-accent-blue/20 hover:scale-[1.02] active:scale-[0.98]">
                                <span>Apply Now</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                            <button type="button" 
                                    @click="removeJob()"
                                    :disabled="loading"
                                    class="py-2.5 px-3 rounded-xl bg-white/5 hover:bg-rose-500/20 text-slate-400 hover:text-rose-300 border border-white/10 hover:border-rose-500/30 font-bold text-xs flex items-center justify-center transition-all"
                                    title="Remove">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>

                    </div>
                @endif
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pt-4">
            {{ $savedJobs->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-gradient-to-b from-[#0a1e4a]/90 to-[#07173e]/95 backdrop-blur-xl rounded-3xl border border-white/[0.08] p-10 sm:p-14 text-center max-w-lg mx-auto shadow-2xl">
            <div class="w-20 h-20 rounded-3xl bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center text-3xl mx-auto mb-4 shadow-[0_0_30px_rgba(245,158,11,0.2)]">
                <i class="far fa-bookmark"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">No Saved Jobs Yet</h3>
            <p class="text-xs text-slate-400 max-w-sm mx-auto leading-relaxed mb-6">
                When you find jobs that interest you, click the bookmark icon on any job card to save them here for quick access later.
            </p>
            <a href="{{ route('jobs') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-accent-blue to-blue-600 hover:from-blue-500 hover:to-sky-400 text-white font-bold text-xs shadow-lg shadow-accent-blue/30 transition-all hover:scale-105 active:scale-95">
                <i class="fas fa-search text-xs"></i>
                <span>Explore Teaching Jobs</span>
            </a>
        </div>
    @endif

</div>
@endsection
