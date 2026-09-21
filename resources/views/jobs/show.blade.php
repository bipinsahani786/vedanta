@extends('layouts.app')

@section('content')
<!-- Hero Breadcrumb Banner (Reference Image 2 & 3) -->
<div class="bg-gradient-to-r from-[#031333] via-[#08296a] to-[#031333] text-white py-8 sm:py-10 px-4 sm:px-6 lg:px-[5%] relative overflow-hidden">
    <!-- Decorative background glow & pattern -->
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-[#129aef]/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative z-10">
        <div>
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-blue-200/80 mb-2 font-medium">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <span class="text-blue-300/50">/</span>
                <a href="{{ route('jobs') }}" class="hover:text-white transition-colors">Jobs</a>
                <span class="text-blue-300/50">/</span>
                <span class="text-white font-semibold line-clamp-1">{{ $job->title ?? 'Job Details' }}</span>
            </nav>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight uppercase">
                {{ $job->title ?? 'Job Requirement' }}
            </h1>
            <p class="text-xs sm:text-sm text-blue-200/90 mt-1 font-medium">
                Join a reputed school and make a difference in students' future
            </p>
        </div>

        <!-- Right Side Brand Watermark Graphic -->
        <div class="hidden lg:flex items-center gap-4 bg-white/10 backdrop-blur-md border border-white/15 px-5 py-3 rounded-2xl shadow-lg">
            <div class="w-10 h-10 rounded-xl bg-amber-400 text-slate-950 flex items-center justify-center text-lg font-black shrink-0 shadow">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div>
                <span class="block text-[11px] font-black text-amber-300 uppercase tracking-widest">Great Teachers</span>
                <span class="block text-xs font-bold text-white">Build Brighter Futures</span>
            </div>
        </div>
    </div>
</div>

<div class="py-10 px-4 sm:px-6 lg:px-[5%] bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        <!-- Flash Messages -->
        @if(session('error'))
            <div class="mb-6 p-4 sm:p-5 bg-rose-50 border-l-4 border-rose-500 rounded-r-2xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h4 class="text-rose-900 font-bold text-sm">Action Notice</h4>
                        <span class="text-xs sm:text-sm text-rose-700 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
                <button type="button" class="text-rose-400 hover:text-rose-600 p-2" onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 p-4 sm:p-5 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-2xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h4 class="text-emerald-900 font-bold text-sm">Success</h4>
                        <span class="text-xs sm:text-sm text-emerald-700 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="text-emerald-400 hover:text-emerald-600 p-2" onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Main Layout Grid (Left: 8 cols, Right: 4 cols) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT MAIN COLUMN (Image 2 & 3) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Main Job Card Container -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    
                    <!-- Top Meta Bar: Job ID, Status, Save & Share -->
                    <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <span class="px-3 py-1 rounded-lg bg-blue-50 text-[#129aef] text-xs font-black tracking-wider">
                                {{ $job->job_code }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200/60">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Actively Hiring
                            </span>
                        </div>

                        <!-- Save & Share Buttons -->
                        <div class="flex items-center gap-2">
                            @auth
                                @if(auth()->user()->role === 'candidate')
                                    @php
                                        $isJobSaved = \App\Models\SavedJob::where('user_id', auth()->id())->where('job_post_id', $job->id)->exists();
                                    @endphp
                                    <button type="button" 
                                            x-data="{
                                                isSaved: {{ $isJobSaved ? 'true' : 'false' }},
                                                loading: false,
                                                async toggleSave() {
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
                                                        this.isSaved = data.saved;
                                                    } catch(e) {
                                                        console.error(e);
                                                    } finally {
                                                        this.loading = false;
                                                    }
                                                }
                                            }"
                                            @click="toggleSave()"
                                            :disabled="loading"
                                            :class="isSaved ? 'bg-amber-50 text-amber-600 border-amber-300' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                                            class="px-3.5 py-1.5 rounded-xl border font-bold text-xs flex items-center gap-1.5 transition-all shadow-sm cursor-pointer">
                                        <i :class="isSaved ? 'fas fa-bookmark text-amber-500' : 'far fa-bookmark'"></i>
                                        <span x-text="isSaved ? 'Saved' : 'Save Job'"></span>
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100 font-bold text-xs flex items-center gap-1.5 transition-all shadow-sm">
                                    <i class="far fa-bookmark"></i>
                                    <span>Save Job</span>
                                </a>
                            @endauth

                            <!-- Share Button -->
                            @php
                                $detailJobShareData = [
                                    'id' => $job->id,
                                    'code' => $job->job_code,
                                    'title' => $job->title ?? 'Teaching Opportunity',
                                    'location' => $job->getMaskedLocation(),
                                    'salary' => $job->formatted_salary,
                                    'category' => $job->category?->name ?? '',
                                    'subject' => $job->subject?->name ?? '',
                                    'url' => route('jobs.show', $job->id),
                                ];
                            @endphp
                            <button type="button" 
                                    onclick="openJobShare({{ json_encode($detailJobShareData) }})" 
                                    class="px-3.5 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 hover:text-[#129aef] hover:bg-slate-100 font-bold text-xs flex items-center gap-1.5 transition-all shadow-sm cursor-pointer active:scale-95"
                                    title="Share Job">
                                <i class="fas fa-share-alt text-[#129aef]"></i>
                                <span>Share</span>
                            </button>
                        </div>
                    </div>

                    <!-- Job Title & Category Badges -->
                    <div class="mt-6">
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-3">
                            {{ $job->title ?? 'Job Requirement' }}
                        </h2>

                        <!-- Feature Badges -->
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            @if($job->category)
                                <span class="px-3 py-1 rounded-xl bg-blue-50 text-[#129aef] text-xs font-bold border border-blue-100">
                                    {{ $job->category->name }}
                                </span>
                            @endif
                            @if($job->subject)
                                <span class="px-3 py-1 rounded-xl bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-100">
                                    {{ $job->subject->name }}
                                </span>
                            @endif
                            @if($job->qualification)
                                <span class="px-3 py-1 rounded-xl bg-purple-50 text-purple-700 text-xs font-bold border border-purple-100">
                                    {{ $job->qualification->name }}
                                </span>
                            @endif
                            <span class="px-3 py-1 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">
                                {{ $job->job_type ?? 'Full Time' }}
                            </span>
                        </div>

                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            We are hiring a qualified and experienced {{ $job->title }} for a reputed institution. Interested eligible educators can apply directly through Vedanta Placement Agency.
                        </p>
                    </div>

                    <!-- Salary Section (Reference Image 2 & 3) -->
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <div class="flex flex-wrap items-baseline gap-2 mb-1">
                            <span class="text-2xl sm:text-3xl font-black text-[#040e2d] tracking-tight">
                                {{ $job->formatted_salary }}
                            </span>
                        </div>
                        <span class="text-xs text-slate-500 font-medium">
                            (As per experience, qualification and interview assessment)
                        </span>
                    </div>

                    <!-- 4 Icon Badges Grid (Image 2 & 3) -->
                    <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-100/80 text-[#129aef] flex items-center justify-center shrink-0 text-sm">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="text-left">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase">Role</span>
                                <span class="block text-xs font-extrabold text-slate-800 line-clamp-1">{{ $job->category?->name ?? 'Teaching' }}</span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100/80 text-emerald-600 flex items-center justify-center shrink-0 text-sm">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="text-left">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase">Job Type</span>
                                <span class="block text-xs font-extrabold text-slate-800 line-clamp-1">{{ $job->job_type ?? 'Full Time' }}</span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-100/80 text-amber-700 flex items-center justify-center shrink-0 text-sm">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="text-left">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase">Vacancies</span>
                                <span class="block text-xs font-extrabold text-slate-800 line-clamp-1">{{ $job->openings ?? 'Multiple' }}</span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-purple-100/80 text-purple-600 flex items-center justify-center shrink-0 text-sm">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="text-left">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase">Career</span>
                                <span class="block text-xs font-extrabold text-slate-800 line-clamp-1">High Growth</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Job Description & Details Section -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#129aef] flex items-center justify-center text-base font-bold shadow-sm">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Job Description & Requirements
                        </h3>
                    </div>

                    <!-- Enhanced Parsed Description -->
                    <div class="bg-slate-50/60 border border-slate-200/60 rounded-2xl p-5 sm:p-7 text-slate-700 leading-relaxed [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:space-y-2 [&_ul]:my-3 [&_li]:text-slate-700 [&_li]:marker:text-[#129aef] [&_p]:mb-3 [&_h4]:font-extrabold [&_h4]:text-slate-900 [&_h4]:text-xs sm:[&_h4]:text-sm [&_h4]:mt-5 [&_h4]:mb-2 [&_h4]:uppercase [&_h4]:tracking-wider">
                        @php
                            $rawDesc = $job->description ?? '';
                            if (empty(trim($rawDesc))) {
                                $formattedDescription = '<p class="text-slate-500 italic text-sm">We are seeking dynamic educators dedicated to student growth and academic excellence. Candidate should possess relevant qualifications and good subject command.</p>';
                            } else {
                                $hasHtml = preg_match('/<[a-z][\s\S]*>/i', $rawDesc);
                                if ($hasHtml) {
                                    $formattedDescription = $rawDesc;
                                } else {
                                    $normalized = str_replace(['•', '·', '►', '▪', '⁃', '●'], '•', $rawDesc);
                                    $normalized = preg_replace('/(?<!^|\n)•/', "\n•", $normalized);
                                    $lines = explode("\n", $normalized);
                                    $html = '';
                                    $inUl = false;
                                    foreach ($lines as $l) {
                                        $t = trim($l);
                                        if (empty($t)) continue;
                                        if (str_starts_with($t, '•') || str_starts_with($t, '-')) {
                                            if (!$inUl) { $html .= '<ul>'; $inUl = true; }
                                            $html .= '<li>' . e(ltrim($t, '•- ')) . '</li>';
                                        } else {
                                            if ($inUl) { $html .= '</ul>'; $inUl = false; }
                                            $html .= '<p>' . e($t) . '</p>';
                                        }
                                    }
                                    if ($inUl) $html .= '</ul>';
                                    $formattedDescription = $html;
                                }
                            }
                        @endphp
                        {!! $formattedDescription !!}
                    </div>

                    <!-- Inspirational Quote Callout (Reference Image 2 & 3) -->
                    <div class="mt-6 p-5 rounded-2xl bg-gradient-to-r from-blue-50 via-sky-50/60 to-transparent border-l-4 border-[#129aef] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <span class="text-3xl text-[#129aef]/40 font-serif leading-none">“</span>
                            <p class="text-xs sm:text-sm font-semibold text-slate-800 italic leading-snug">
                                Great teachers don't just teach subjects, they shape brighter futures.
                            </p>
                        </div>
                        <span class="text-[11px] font-black text-[#129aef] tracking-wider uppercase whitespace-nowrap self-end sm:self-center">
                            Better Educators, Brighter Futures
                        </span>
                    </div>

                </div>

                <!-- Selection Process (Reference Image 2 & 3) -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#129aef] flex items-center justify-center text-base font-bold shadow-sm">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Our Selection Process
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 relative">
                        <!-- Step 1 -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center relative">
                            <div class="w-8 h-8 rounded-full bg-[#129aef] text-white flex items-center justify-center text-xs font-black mx-auto mb-2.5 shadow-sm">1</div>
                            <h4 class="text-xs font-extrabold text-slate-900 mb-1">Application Shortlisting</h4>
                            <p class="text-[11px] text-slate-500">Profile & resume screening by Vedanta experts</p>
                        </div>

                        <!-- Step 2 -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center relative">
                            <div class="w-8 h-8 rounded-full bg-[#129aef] text-white flex items-center justify-center text-xs font-black mx-auto mb-2.5 shadow-sm">2</div>
                            <h4 class="text-xs font-extrabold text-slate-900 mb-1">Interview Process</h4>
                            <p class="text-[11px] text-slate-500">Direct coordination with school management</p>
                        </div>

                        <!-- Step 3 -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center relative">
                            <div class="w-8 h-8 rounded-full bg-[#129aef] text-white flex items-center justify-center text-xs font-black mx-auto mb-2.5 shadow-sm">3</div>
                            <h4 class="text-xs font-extrabold text-slate-900 mb-1">Final Selection</h4>
                            <p class="text-[11px] text-slate-500">Demonstration class & committee review</p>
                        </div>

                        <!-- Step 4 -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center relative">
                            <div class="w-8 h-8 rounded-full bg-[#129aef] text-white flex items-center justify-center text-xs font-black mx-auto mb-2.5 shadow-sm">4</div>
                            <h4 class="text-xs font-extrabold text-slate-900 mb-1">Offer Letter & Joining</h4>
                            <p class="text-[11px] text-slate-500">Official onboarding & salary confirmation</p>
                        </div>
                    </div>
                </div>

                <!-- Important Notice Callout (Reference Image 2 & 3) -->
                <div class="bg-amber-50/80 border-2 border-amber-200/80 rounded-3xl p-6 sm:p-7 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-amber-400 text-amber-950 flex items-center justify-center text-xl shrink-0 font-black shadow-sm">
                            !
                        </div>
                        <div>
                            <h4 class="text-sm sm:text-base font-black text-amber-950 mb-1">Important Notice</h4>
                            <p class="text-xs sm:text-sm text-amber-900/90 leading-relaxed">
                                To apply for this opportunity, candidates are required to complete the registration process with Vedanta Placement Agency. Registration charges are applicable as per the selected registration plan. Registration gives you access to eligible job applications, interview coordination and complete guidance till joining.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('pricing') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white border border-amber-300 text-amber-950 hover:bg-amber-100 font-extrabold text-xs transition-all shadow-sm text-center whitespace-nowrap">
                        Know More About Registration
                    </a>
                </div>

            </div>

            <!-- RIGHT SIDEBAR (Image 2 & 3) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- 1. SCHOOL DETAILS CARD (THE CORE REQUIREMENT) -->
                @if($isUnlocked)
                    <!-- ============================================== -->
                    <!-- UNLOCKED STATE (Reference Image 2)           -->
                    <!-- ============================================== -->
                    <div class="bg-white border-2 border-emerald-500/30 rounded-3xl overflow-hidden shadow-lg hover:shadow-xl transition-all">
                        
                        <!-- Top Banner / Badge -->
                        <div class="bg-emerald-600 text-white px-5 py-2.5 flex items-center justify-between">
                            <span class="text-xs font-black flex items-center gap-1.5 uppercase tracking-wider">
                                <i class="fas fa-check-circle"></i> School Details Revealed
                            </span>
                            <span class="text-[11px] font-semibold text-emerald-100">Verified Partner</span>
                        </div>

                        <!-- School Photo -->
                        <div class="h-44 w-full relative overflow-hidden bg-slate-100">
                            @if($job->school_image)
                                <img src="{{ asset('storage/' . $job->school_image) }}" alt="{{ $job->school_name }}" class="w-full h-full object-cover">
                            @else
                                <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&auto=format&fit=crop&q=80" alt="School building" class="w-full h-full object-cover">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                            <div class="absolute bottom-3 left-4 text-white">
                                <span class="text-[10px] uppercase font-bold tracking-widest text-emerald-300">Partner School</span>
                                <h3 class="text-lg font-black leading-tight text-white drop-shadow">{{ $job->school_name }}</h3>
                            </div>
                        </div>

                        <!-- School Content -->
                        <div class="p-5 sm:p-6 space-y-4">
                            <div>
                                <h4 class="text-lg font-black text-slate-900 leading-tight">
                                    {{ $job->school_name }}
                                </h4>
                                <p class="text-xs sm:text-sm text-slate-600 font-semibold flex items-center gap-1.5 mt-1">
                                    <i class="fas fa-map-marker-alt text-rose-500"></i>
                                    <span>{{ $job->city?->name ?? 'Aurangabad' }}, {{ $job->state?->name ?? 'Bihar' }}</span>
                                </p>
                            </div>

                            @if($job->contact_person || $job->phone)
                            <div class="p-3 bg-slate-50 rounded-xl text-xs text-slate-600 space-y-1">
                                @if($job->contact_person)
                                    <div><span class="font-bold text-slate-800">Contact:</span> {{ $job->contact_person }}</div>
                                @endif
                                @if($job->email)
                                    <div><span class="font-bold text-slate-800">Email:</span> {{ $job->email }}</div>
                                @endif
                            </div>
                            @endif

                            <!-- Confirmation Green Strip -->
                            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2 text-xs font-bold text-emerald-800">
                                <i class="fas fa-check text-emerald-600"></i>
                                <span>School name & exact location are now visible.</span>
                            </div>

                            <!-- Apply Action Button for Unlocked Users -->
                            @auth
                                @if(auth()->user()->role === 'candidate')
                                    <form action="{{ route('candidate.applications.apply', $job->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full py-3.5 bg-[#129aef] hover:bg-[#0d85d4] text-white font-black rounded-xl shadow-lg shadow-[#129aef]/25 hover:shadow-[#129aef]/40 transition-all text-sm flex items-center justify-center gap-2 cursor-pointer">
                                            <i class="fas fa-paper-plane"></i>
                                            <span>Apply for this Position</span>
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @else
                    <!-- ============================================== -->
                    <!-- LOCKED STATE (Reference Image 3)             -->
                    <!-- Small, Clean, Compact Integrated Lock Card     -->
                    <!-- ============================================== -->
                    <div class="trigger-school-lock-modal bg-gradient-to-b from-white to-blue-50/40 border border-slate-200 hover:border-[#129aef]/60 rounded-3xl p-6 sm:p-7 text-center shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:shadow-xl transition-all cursor-pointer group relative overflow-hidden">
                        
                        <!-- Top Accent Glow -->
                        <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-blue-400 via-[#129aef] to-blue-600"></div>
                        
                        <!-- Circular Lock Badge (Small & Clean - Requirement 4) -->
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-200/80 text-[#129aef] group-hover:bg-[#129aef] group-hover:text-white flex items-center justify-center mx-auto mb-4 text-xl shadow-sm transition-all duration-300 group-hover:scale-110">
                            <i class="fas fa-lock"></i>
                        </div>

                        <!-- Locked Heading (Requirement 8) -->
                        <h3 class="text-base sm:text-lg font-black text-slate-900 mb-2 leading-tight">
                            School Name & Location are hidden
                        </h3>

                        <!-- Locked Helper (Requirement 8) -->
                        <p class="text-xs text-slate-500 leading-relaxed mb-5 max-w-xs mx-auto">
                            Login or register to view complete details including school name, exact location and contact information.
                        </p>

                        <!-- Locked CTA Button (Requirement 8) -->
                        <button type="button" class="w-full py-3 px-5 bg-[#129aef] group-hover:bg-[#0d85d4] text-white font-extrabold rounded-xl shadow-md shadow-[#129aef]/20 transition-all text-xs sm:text-sm flex items-center justify-center gap-2 cursor-pointer">
                            <i class="far fa-eye"></i>
                            <span>Click to View School Details</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </button>

                        <!-- Sub-link (Requirement 8) -->
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <span class="text-[11px] text-slate-500">Already registered? </span>
                            <span class="text-[11px] font-bold text-[#129aef] hover:underline">Login here</span>
                        </div>
                    </div>
                @endif

                <!-- 2. JOB OVERVIEW CARD (Reference Image 2 & 3) -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-7 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    <h3 class="text-base font-black text-slate-900 pb-4 mb-4 border-b border-slate-100 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[#129aef]"></i> Job Overview
                    </h3>

                    <div class="space-y-3.5 text-xs sm:text-sm">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-50">
                            <span class="text-slate-500 font-medium">Job Code</span>
                            <span class="font-extrabold text-slate-800">{{ $job->job_code }}</span>
                        </div>

                        <div class="flex items-center justify-between pb-2 border-b border-slate-50">
                            <span class="text-slate-500 font-medium">Position</span>
                            <span class="font-extrabold text-slate-800">{{ $job->title }}</span>
                        </div>

                        <div class="flex items-center justify-between pb-2 border-b border-slate-50">
                            <span class="text-slate-500 font-medium">Qualification</span>
                            <span class="font-extrabold text-slate-800">{{ $job->qualification?->name ?? 'Any Graduate' }}</span>
                        </div>

                        <div class="flex items-center justify-between pb-2 border-b border-slate-50">
                            <span class="text-slate-500 font-medium">Salary</span>
                            <span class="font-extrabold text-emerald-700 text-right">{{ $job->formatted_salary }}</span>
                        </div>

                        <div class="flex items-center justify-between pb-2 border-b border-slate-50">
                            <span class="text-slate-500 font-medium">Job Type</span>
                            <span class="font-extrabold text-slate-800">{{ $job->job_type ?? 'Full Time' }}</span>
                        </div>

                        <div class="flex items-center justify-between pb-2 border-b border-slate-50">
                            <span class="text-slate-500 font-medium">Experience</span>
                            <span class="font-extrabold text-slate-800">{{ $job->experience ?? 'Preferred' }}</span>
                        </div>

                        <div class="flex items-center justify-between pb-2 border-b border-slate-50">
                            <span class="text-slate-500 font-medium">Category</span>
                            <span class="font-extrabold text-slate-800">{{ $job->category?->name ?? 'Teaching' }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 font-medium">No. of Openings</span>
                            <span class="font-extrabold text-slate-800">{{ $job->openings ?? 'Multiple' }}</span>
                        </div>
                    </div>
                </div>

                <!-- 3. WHY APPLY WITH VEDANTA? (Reference Image 2 & 3) -->
                <div class="bg-gradient-to-br from-[#040e2d] to-[#082265] text-white rounded-3xl p-6 sm:p-7 shadow-lg relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-[#129aef]/15 rounded-full blur-2xl pointer-events-none"></div>

                    <h3 class="text-base font-black text-white pb-4 mb-4 border-b border-white/10 flex items-center gap-2">
                        <i class="fas fa-shield-alt text-amber-400"></i> Why Apply with Vedanta?
                    </h3>

                    <!-- Checklist -->
                    <div class="space-y-2.5 text-xs sm:text-sm mb-6">
                        <div class="flex items-center gap-2.5 text-blue-100">
                            <span class="w-4 h-4 rounded-full bg-emerald-400 text-slate-950 flex items-center justify-center text-[9px] font-black shrink-0">✓</span>
                            <span>Verified and genuine opportunities</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-blue-100">
                            <span class="w-4 h-4 rounded-full bg-emerald-400 text-slate-950 flex items-center justify-center text-[9px] font-black shrink-0">✓</span>
                            <span>Direct coordination with schools</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-blue-100">
                            <span class="w-4 h-4 rounded-full bg-emerald-400 text-slate-950 flex items-center justify-center text-[9px] font-black shrink-0">✓</span>
                            <span>End-to-end interview support</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-blue-100">
                            <span class="w-4 h-4 rounded-full bg-emerald-400 text-slate-950 flex items-center justify-center text-[9px] font-black shrink-0">✓</span>
                            <span>Guidance till joining</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-blue-100">
                            <span class="w-4 h-4 rounded-full bg-emerald-400 text-slate-950 flex items-center justify-center text-[9px] font-black shrink-0">✓</span>
                            <span>Trusted by 10,000+ educators</span>
                        </div>
                    </div>

                    <!-- Credibility Micro-Stats Grid -->
                    <div class="grid grid-cols-3 gap-2 pt-4 border-t border-white/10 text-center">
                        <div class="p-2 bg-white/5 rounded-xl border border-white/10">
                            <span class="block text-sm sm:text-base font-black text-amber-300">10k+</span>
                            <span class="block text-[9px] text-blue-200">Educators</span>
                        </div>
                        <div class="p-2 bg-white/5 rounded-xl border border-white/10">
                            <span class="block text-sm sm:text-base font-black text-emerald-300">1,500+</span>
                            <span class="block text-[9px] text-blue-200">Schools</span>
                        </div>
                        <div class="p-2 bg-white/5 rounded-xl border border-white/10">
                            <span class="block text-sm sm:text-base font-black text-sky-300">500+</span>
                            <span class="block text-[9px] text-blue-200">Vacancies</span>
                        </div>
                    </div>
                </div>

                <!-- 4. SIMILAR JOBS CARD (Reference Image 2 & 3) -->
                @if(isset($similarJobs) && $similarJobs->count() > 0)
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-7 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                        <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                            <i class="fas fa-briefcase text-[#129aef]"></i> Similar Jobs
                        </h3>
                        <a href="{{ route('jobs') }}" class="text-xs font-bold text-[#129aef] hover:underline flex items-center gap-1">
                            <span>View All</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach($similarJobs as $simJob)
                        <a href="{{ route('jobs.show', $simJob->id) }}" class="block p-3 rounded-2xl bg-slate-50 hover:bg-blue-50/60 border border-slate-100 hover:border-blue-200 transition-all group">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <h4 class="text-xs sm:text-sm font-extrabold text-slate-800 group-hover:text-[#129aef] transition-colors line-clamp-1">
                                    {{ $simJob->title }}
                                </h4>
                                <span class="w-6 h-6 rounded-full bg-white text-slate-400 group-hover:bg-[#129aef] group-hover:text-white flex items-center justify-center text-[10px] shrink-0 transition-colors shadow-sm">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500 font-semibold">
                                <span>{{ $simJob->subject?->name ?? 'Teaching' }}</span>
                                <span class="text-emerald-700 font-bold">{{ $simJob->formatted_salary }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

        </div>

    </div>
</div>

<!-- Include the School Details Lock Modal (Reference Image 4) -->
@include('partials.school-details-modal')

@endsection
