@extends('layouts.app')
@section('content')
<!-- Hero Section (Matches Reference Image) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&display=swap" rel="stylesheet">

<div class="relative bg-[#040e2d] overflow-hidden text-white border-b border-white/10">
    <!-- Right Background School Campus Image -->
    <div class="absolute inset-y-0 right-0 w-full lg:w-[58%] bg-cover bg-right lg:bg-center pointer-events-none"
         style="background-image: url('{{ asset('images/hero_school.jpg') }}');">
    </div>

    <!-- Gradient Overlays for Seamless Fade -->
    <!-- 1. Deep navy overlay fading to transparent from left to right -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#040e2d] via-[#040e2d]/95 via-[42%] via-[#040e2d]/75 via-[60%] to-[#040e2d]/30 pointer-events-none"></div>
    <!-- 2. Extra soft ambient vignette on top and bottom -->
    <div class="absolute inset-0 bg-gradient-to-b from-[#040e2d]/60 via-transparent to-[#040e2d]/80 pointer-events-none"></div>

    <!-- Golden Accent Slash Beam in Bottom-Right -->
    <svg class="absolute bottom-0 right-[12%] lg:right-[18%] w-44 sm:w-56 h-72 sm:h-80 pointer-events-none overflow-visible opacity-90 hidden md:block" viewBox="0 0 160 240" fill="none">
        <polygon points="90,240 150,240 60,0 0,0" fill="url(#goldenBeamGrad)" />
        <defs>
            <linearGradient id="goldenBeamGrad" x1="0" y1="1" x2="0" y2="0">
                <stop offset="0%" stop-color="#ea580c" stop-opacity="0.95" />
                <stop offset="30%" stop-color="#f59e0b" stop-opacity="0.9" />
                <stop offset="70%" stop-color="#fbbf24" stop-opacity="0.5" />
                <stop offset="100%" stop-color="#fde047" stop-opacity="0" />
            </linearGradient>
        </defs>
    </svg>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 pt-14 pb-16 md:pt-20 md:pb-20 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <!-- Left Column: Title, Subtitle, Search Form, Popular Tags (8 cols on lg) -->
            <div class="lg:col-span-8 xl:col-span-8">
                <!-- Eyebrow -->
                <div class="text-amber-400 font-extrabold text-xs sm:text-[13px] tracking-[0.16em] uppercase mb-3">
                    CONNECTING EDUCATORS, BUILDING BRIGHTER FUTURES
                </div>

                <!-- Main Heading -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[3.25rem] font-black text-white tracking-tight leading-[1.15] mb-4">
                    Find Your Next <br class="hidden sm:inline" />
                    <span class="text-[#129aef]">Teaching Opportunity</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-slate-300 text-sm sm:text-base md:text-[1.05rem] max-w-2xl leading-relaxed mb-8">
                    Get placed in reputed schools across India with Vedanta Placement Agency.<br class="hidden sm:inline" />
                    Your teaching career, our commitment.
                </p>

                <!-- Floating White Search Capsule Form -->
                <form action="{{ route('jobs') }}" method="GET" class="bg-white rounded-2xl md:rounded-full p-2 md:p-2 shadow-2xl border border-white/30 flex flex-col md:flex-row items-center gap-2 max-w-2xl relative z-20">
                    <!-- Keyword input -->
                    <div class="flex items-center gap-3 w-full flex-1 px-4 py-1.5">
                        <svg class="w-5 h-5 text-[#129aef] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by job title, subject, or keyword..." class="w-full bg-transparent text-slate-800 placeholder:text-slate-400 text-sm font-medium border-0 focus:ring-0 focus:outline-none p-0">
                    </div>

                    <!-- Desktop Divider -->
                    <div class="hidden md:block w-px h-8 bg-slate-200"></div>

                    <!-- Location Selector -->
                    <div class="flex items-center gap-2.5 w-full md:w-56 px-4 py-1.5 relative">
                        <svg class="w-5 h-5 text-[#129aef] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <select name="state" class="w-full bg-transparent text-slate-700 text-sm font-medium border-0 focus:ring-0 focus:outline-none p-0 appearance-none cursor-pointer pr-6 truncate">
                            <option value="" class="text-slate-500">Select preferred location</option>
                            @foreach($states as $st)
                                <option value="{{ $st->id }}" {{ request('state') == $st->id ? 'selected' : '' }} class="text-slate-800">
                                    {{ $st->name }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="w-4 h-4 text-slate-400 pointer-events-none absolute right-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full md:w-auto bg-[#129aef] hover:bg-[#0d85d0] active:scale-[0.98] text-white font-bold px-7 py-3 rounded-xl md:rounded-full transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-sm whitespace-nowrap cursor-pointer">
                        <span>Search Jobs</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <!-- Popular Searches -->
                <div class="mt-6 flex flex-wrap items-center gap-2 text-xs sm:text-sm">
                    <span class="text-slate-300 font-medium">Popular Searches:</span>
                    @php
                        $popularTags = ['PRT', 'TGT', 'PGT', 'Principal', 'Pre-Primary', 'NTT', 'Counselor', 'Coaches'];
                    @endphp
                    @foreach($popularTags as $tag)
                        <a href="{{ route('jobs', ['q' => $tag]) }}" 
                           class="px-3.5 py-1 rounded-full text-xs font-semibold transition-all duration-200 border {{ request('q') === $tag ? 'bg-[#129aef] text-white border-[#129aef] shadow-md' : 'bg-white/10 hover:bg-white/20 text-white/90 hover:text-white border-white/15' }}">
                            {{ $tag }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Slogan in sky + Vertical Stats (4 cols on lg) -->
            <div class="lg:col-span-4 xl:col-span-4 flex flex-col justify-between items-center lg:items-end relative">
                
                <!-- Floating Slogan in Sky -->
                <div class="mb-8 lg:mb-12 text-center lg:text-right pr-0 lg:pr-6 rotate-[-5deg] select-none">
                    <div class="font-['Caveat',cursive] text-2xl sm:text-3xl lg:text-[2.25rem] text-white font-bold leading-tight drop-shadow-md">
                        <div>Better Teachers</div>
                        <div>Brighter Futures</div>
                    </div>
                    <!-- Golden Swoop Arc Accent Underline -->
                    <svg class="w-36 sm:w-40 h-4 text-amber-400 mx-auto lg:ml-auto lg:mr-0 -mt-1 drop-shadow" viewBox="0 0 140 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 10C45 17 95 16 134 4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>

                <!-- Vertical Stats Stack -->
                <div class="space-y-4 w-full max-w-[240px] sm:max-w-[260px] self-center lg:self-end">
                    <!-- Stat 1: Educators -->
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shrink-0 shadow-lg">
                            <i class="fa-solid fa-graduation-cap text-lg"></i>
                        </div>
                        <div class="leading-tight">
                            <div class="text-[11px] text-slate-300 font-medium">Trusted by</div>
                            <div class="text-sm sm:text-[15px] font-bold text-white tracking-wide">10,000+ Educators</div>
                        </div>
                    </div>

                    <!-- Stat 2: Partner Schools -->
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shrink-0 shadow-lg">
                            <i class="fa-solid fa-school text-lg"></i>
                        </div>
                        <div class="leading-tight">
                            <div class="text-sm sm:text-[15px] font-bold text-white tracking-wide">1,500+</div>
                            <div class="text-[11px] text-slate-300 font-medium">Partner Schools</div>
                        </div>
                    </div>

                    <!-- Stat 3: Active Opportunities -->
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shrink-0 shadow-lg">
                            <i class="fa-solid fa-file-lines text-lg"></i>
                        </div>
                        <div class="leading-tight">
                            <div class="text-sm sm:text-[15px] font-bold text-white tracking-wide">500+</div>
                            <div class="text-[11px] text-slate-300 font-medium">Active Opportunities</div>
                        </div>
                    </div>

                    <!-- Stat 4: Presence -->
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shrink-0 shadow-lg">
                            <i class="fa-solid fa-users text-lg"></i>
                        </div>
                        <div class="leading-tight">
                            <div class="text-sm sm:text-[15px] font-bold text-white tracking-wide">Pan India</div>
                            <div class="text-[11px] text-slate-300 font-medium">Presence</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<div class="py-12 px-6 lg:px-[5%] flex flex-col lg:flex-row gap-8 bg-white relative overflow-hidden">
    <!-- Decorative Pattern -->
    <div class="absolute inset-0 z-0 opacity-[0.02]" style="background-image: radial-gradient(#000000 1.5px, transparent 1.5px); background-size: 32px 32px;"></div>

    <!-- Job List Container with Alpine View Mode -->
    <div x-data="{ viewMode: localStorage.getItem('job_view_mode') || 'grid' }" class="w-full relative z-10">
        @if(request('q') || request('state') || request('class') || request('subject') || request('sort'))
            <div class="mb-6 p-4 rounded-2xl bg-blue-50/80 border border-blue-200/70 flex flex-wrap items-center justify-between gap-3 text-slate-800 shadow-sm">
                <div class="flex items-center gap-2.5 text-xs sm:text-sm">
                    <span class="w-8 h-8 rounded-xl bg-[#129aef]/15 text-[#129aef] flex items-center justify-center font-bold">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <div>
                        <span>Showing search results
                            @if(request('q')) for <strong class="text-[#040e2d]">"{{ request('q') }}"</strong> @endif
                            @if(request('state')) in <strong class="text-[#040e2d]">{{ $states->firstWhere('id', request('state'))?->name ?? 'State' }}</strong> @endif
                        </span>
                        <span class="text-xs text-slate-500 font-semibold block sm:inline sm:ml-2">({{ $jobs->total() }} {{ Str::plural('job', $jobs->total()) }} found)</span>
                    </div>
                </div>
                <a href="{{ route('jobs') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white hover:bg-rose-50 text-rose-600 border border-rose-200/80 font-bold text-xs transition-all shadow-sm">
                    <i class="fas fa-times text-[10px]"></i>
                    <span>Clear Filters</span>
                </a>
            </div>
        @endif

        <!-- Toolbar: Header count, Sort by dropdown, & Grid/List switcher (Matches Reference Image) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <h2 class="text-lg sm:text-xl font-black text-slate-900">
                    Explore Jobs
                </h2>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-[#129aef] text-xs font-bold border border-blue-200/50">
                    {{ $jobs->total() }} {{ Str::plural('Opportunity', $jobs->total()) }}
                </span>
            </div>

            <!-- Right Controls: Sort by + Grid/List Toggle -->
            <div class="flex items-center gap-3 self-end sm:self-auto">
                <!-- Sort by Dropdown (Exact match to Reference Image) -->
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-slate-700 select-none">Sort by</span>
                    <div class="relative">
                        <select id="sort_by_select" 
                                onchange="updateJobSort(this.value)" 
                                class="appearance-none bg-white border border-slate-200/90 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs sm:text-sm font-semibold text-slate-800 shadow-xs focus:outline-none focus:ring-2 focus:ring-[#129aef]/20 cursor-pointer transition-all">
                            <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="salary_high" {{ request('sort') == 'salary_high' ? 'selected' : '' }}>Salary: High to Low</option>
                            <option value="salary_low" {{ request('sort') == 'salary_low' ? 'selected' : '' }}>Salary: Low to High</option>
                        </select>
                        <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- View Mode Switcher (Exact match to Reference Image) -->
                <div class="bg-slate-100/90 p-1 rounded-xl flex items-center border border-slate-200/80 shadow-xs">
                    <!-- Grid View Button -->
                    <button type="button" 
                            @click="viewMode = 'grid'; localStorage.setItem('job_view_mode', 'grid')" 
                            :class="viewMode === 'grid' ? 'bg-white text-[#129aef] shadow-xs' : 'text-slate-400 hover:text-slate-600'" 
                            class="p-2 rounded-lg transition-all cursor-pointer flex items-center justify-center"
                            title="Grid View">
                        <svg class="w-4 h-4" viewBox="0 0 16 16" fill="currentColor">
                            <rect x="1.5" y="1.5" width="5.5" height="5.5" rx="1.2"></rect>
                            <rect x="9" y="1.5" width="5.5" height="5.5" rx="1.2"></rect>
                            <rect x="1.5" y="9" width="5.5" height="5.5" rx="1.2"></rect>
                            <rect x="9" y="9" width="5.5" height="5.5" rx="1.2"></rect>
                        </svg>
                    </button>
                    <!-- List View Button -->
                    <button type="button" 
                            @click="viewMode = 'list'; localStorage.setItem('job_view_mode', 'list')" 
                            :class="viewMode === 'list' ? 'bg-white text-[#129aef] shadow-xs' : 'text-slate-400 hover:text-slate-600'" 
                            class="p-2 rounded-lg transition-all cursor-pointer flex items-center justify-center"
                            title="List View">
                        <svg class="w-4 h-4" viewBox="0 0 16 16" fill="currentColor">
                            <circle cx="2.5" cy="3.5" r="1.2"></circle>
                            <rect x="5.5" y="2.5" width="9" height="2" rx="1"></rect>
                            <circle cx="2.5" cy="8" r="1.2"></circle>
                            <rect x="5.5" y="7" width="9" height="2" rx="1"></rect>
                            <circle cx="2.5" cy="12.5" r="1.2"></circle>
                            <rect x="5.5" y="11.5" width="9" height="2" rx="1"></rect>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Jobs Grid or List (Adaptive via viewMode) -->
        <div :class="viewMode === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6' : 'flex flex-col gap-4'">
            @forelse($jobs as $job)
            @php
                $isJobUnlocked = $job->canUserViewProtectedDetails();
            @endphp
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 hover:border-[#129aef]/60 hover:shadow-xl transition-all duration-300 group relative flex"
                 :class="viewMode === 'list' ? 'flex-col md:flex-row md:items-center md:justify-between gap-6' : 'flex-col justify-between'">
                
                <!-- Main Content Area -->
                <div :class="viewMode === 'list' ? 'flex-1 min-w-0' : ''">
                    <!-- Card Top Meta: Job Code, Status, Bookmark (in Grid view) -->
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-[#129aef] text-[11px] font-black tracking-wider">
                                {{ $job->job_code }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Actively Hiring
                            </span>
                        </div>
                        
                        <a href="{{ route('jobs.show', $job->id) }}" 
                           class="text-slate-400 hover:text-amber-500 transition-colors" 
                           :class="viewMode === 'list' ? 'md:hidden' : ''"
                           title="Save Job">
                            <i class="far fa-bookmark text-sm"></i>
                        </a>
                    </div>
                    
                    <!-- Job Title -->
                    <h3 class="text-lg font-black text-slate-900 mb-1.5 group-hover:text-[#129aef] transition-colors line-clamp-1"
                        :class="viewMode === 'list' ? 'md:text-xl' : ''">
                        <a href="{{ route('jobs.show', $job->id) }}">{{ $job->title ?? 'Job Requirement' }}</a>
                    </h3>

                    <!-- School / Location -->
                    <div class="text-xs text-slate-500 font-semibold mb-3 flex items-center gap-2">
                        @if($isJobUnlocked)
                            <span class="text-slate-800 font-bold line-clamp-1">{{ $job->school_name }}</span>
                            <span>•</span>
                            <span class="text-rose-500 shrink-0"><i class="fas fa-map-marker-alt text-[10px]"></i> {{ $job->city?->name }}, {{ $job->state?->name }}</span>
                        @else
                            <span class="text-slate-700 font-semibold">Reputed School</span>
                            <span>•</span>
                            <span class="text-slate-500 shrink-0"><i class="fas fa-map-marker-alt text-[10px]"></i> {{ $job->state?->name ?? 'India' }}</span>
                        @endif
                    </div>
                    
                    <!-- Tags: Category, Subject, Qualification -->
                    <div class="flex flex-wrap items-center gap-1.5 mb-3.5">
                        @if($job->category)
                            <span class="bg-blue-50 text-[#129aef] border border-blue-100 px-2 py-0.5 rounded-md text-[10px] font-extrabold">
                                {{ $job->category->name }}
                            </span>
                        @endif
                        @if($job->subject)
                            <span class="bg-indigo-50 text-indigo-700 border border-indigo-100 px-2 py-0.5 rounded-md text-[10px] font-extrabold">
                                {{ $job->subject->name }}
                            </span>
                        @endif
                        @if($job->qualification)
                            <span class="bg-purple-50 text-purple-700 border border-purple-100 px-2 py-0.5 rounded-md text-[10px] font-extrabold">
                                {{ $job->qualification->name }}
                            </span>
                        @endif
                    </div>

                    <!-- Description preview -->
                    <p class="text-xs text-slate-600 leading-relaxed line-clamp-2"
                       :class="viewMode === 'list' ? 'mb-0 md:max-w-2xl' : 'mb-4'">
                        {{ Str::limit(strip_tags($job->description), 120) ?: 'Seeking dedicated educators for this position. Candidate should have relevant qualification and experience.' }}
                    </p>

                    <!-- Salary Section (in Grid mode) -->
                    <div class="my-4" :class="viewMode === 'list' ? 'md:hidden' : ''">
                        <span class="text-base font-black text-[#040e2d]">
                            {{ $job->formatted_salary }}
                        </span>
                    </div>

                    <!-- Protected Details Banner (in Grid mode) -->
                    @if(!$isJobUnlocked)
                    <div class="trigger-school-lock-modal mb-4 p-2.5 rounded-xl bg-blue-50/70 hover:bg-blue-100/70 border border-blue-200/60 text-[#129aef] text-xs font-bold flex items-center justify-between cursor-pointer transition-all"
                         :class="viewMode === 'list' ? 'md:hidden' : ''">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-lock text-xs"></i>
                            <span>Login to view school name & exact location</span>
                        </span>
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </div>
                    @endif
                </div>
                
                <!-- Actions Section (Adapts to Grid vs List) -->
                <div :class="viewMode === 'list' ? 'md:w-64 md:border-l md:border-slate-100 md:pl-6 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100 flex flex-col justify-center shrink-0' : 'pt-3 border-t border-slate-100 flex items-center gap-2'">
                    
                    <!-- Salary & Bookmark (in List mode) -->
                    <div class="hidden" :class="viewMode === 'list' ? 'md:flex items-center justify-between mb-3' : ''">
                        <div class="text-lg font-black text-[#040e2d]">
                            {{ $job->formatted_salary }}
                        </div>
                        <a href="{{ route('jobs.show', $job->id) }}" class="text-slate-400 hover:text-amber-500 transition-colors" title="Save Job">
                            <i class="far fa-bookmark text-sm"></i>
                        </a>
                    </div>

                    @if(!$isJobUnlocked)
                    <div class="hidden" :class="viewMode === 'list' ? 'md:block trigger-school-lock-modal mb-3 p-2 rounded-lg bg-blue-50/70 hover:bg-blue-100/70 border border-blue-200/60 text-[#129aef] text-[11px] font-bold cursor-pointer transition-all text-center' : ''">
                        <span class="flex items-center justify-center gap-1.5">
                            <i class="fas fa-lock text-[10px]"></i>
                            <span>Login to unlock details</span>
                        </span>
                    </div>
                    @endif

                    <div class="flex items-center gap-2 w-full">
                        @if($isJobUnlocked)
                            <a href="{{ route('jobs.show', $job->id) }}" class="flex-1 py-2.5 px-4 bg-[#129aef] hover:bg-[#0d85d4] text-white rounded-xl font-extrabold text-xs text-center transition-all shadow-sm">
                                Apply with Vedanta
                            </a>
                        @else
                            <button type="button" class="trigger-school-lock-modal flex-1 py-2.5 px-4 bg-[#129aef] hover:bg-[#0d85d4] text-white rounded-xl font-extrabold text-xs text-center transition-all shadow-sm flex items-center justify-center gap-1.5 cursor-pointer">
                                <i class="fas fa-lock text-[11px]"></i>
                                <span>Apply with Vedanta</span>
                            </button>
                        @endif

                        <button type="button" onclick="navigator.clipboard.writeText('{{ route('jobs.show', $job->id) }}'); alert('Job link copied!');" class="py-2.5 px-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors flex items-center gap-1.5 cursor-pointer" title="Share Job">
                            <i class="fas fa-share-alt text-[#129aef]"></i>
                            <span class="hidden sm:inline">Share</span>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-slate-300 shadow-sm text-2xl mx-auto mb-4"><i class="fas fa-briefcase"></i></div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">No Active Jobs</h3>
                <p class="text-slate-500 text-sm max-w-md mx-auto">We currently don't have any job openings that match your exact criteria. Please try adjusting your filters.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $jobs->links() }}
        </div>
    </div>
</div>

<!-- Bottom Credibility & Trust Bar (Reference Image 1) -->
<div class="bg-white border-t border-b border-slate-100 py-6 px-4 sm:px-6 lg:px-[5%]">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-around gap-6">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-[#129aef] flex items-center justify-center text-lg">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <span class="block text-base font-black text-slate-900 leading-none">10,000+</span>
                <span class="text-xs text-slate-500 font-medium">Registered Educators</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                <i class="fas fa-school"></i>
            </div>
            <div>
                <span class="block text-base font-black text-slate-900 leading-none">1,500+</span>
                <span class="text-xs text-slate-500 font-medium">Partner Schools</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fas fa-briefcase"></i>
            </div>
            <div>
                <span class="block text-base font-black text-slate-900 leading-none">500+</span>
                <span class="text-xs text-slate-500 font-medium">Active Vacancies</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <span class="block text-base font-black text-slate-900 leading-none">100%</span>
                <span class="text-xs text-slate-500 font-medium">Verified Opportunities</span>
            </div>
        </div>

        <div class="hidden xl:block">
            <span class="text-sm font-black text-[#129aef] italic" style="font-family: Georgia, serif;">
                Better Teachers, Brighter Futures
            </span>
        </div>
    </div>
</div>

@include('partials.school-details-modal')


<script>
    function updateJobSort(sortVal) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sortVal);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    (function() {

        // Dynamic Subjects and Specializations Dropdowns
        const searchCategory = document.getElementById('search_category');
        const searchSubject = document.getElementById('search_subject');
        const searchSpecialization = document.getElementById('search_specialization');
        const specializationContainer = document.getElementById('specialization_container');

        if(searchCategory && searchSubject) {
            searchCategory.addEventListener('change', function() {
                const categoryId = this.value;
                
                // Clear existing options
                searchSubject.innerHTML = '<option value="">Select Subject</option>';
                if(searchSpecialization) searchSpecialization.innerHTML = '<option value="">Select Specialization</option>';
                if(specializationContainer) specializationContainer.style.display = 'none';
                
                if(categoryId) {
                    fetch(`/api/categories/${categoryId}/subjects`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(subject => {
                                const option = document.createElement('option');
                                option.value = subject.id;
                                option.textContent = subject.name;
                                searchSubject.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching subjects:', error));
                }
            });
        }

        if(searchSubject && searchSpecialization && specializationContainer) {
            searchSubject.addEventListener('change', function() {
                const subjectId = this.value;
                
                // Clear existing options
                searchSpecialization.innerHTML = '<option value="">Select Specialization</option>';
                specializationContainer.style.display = 'none';
                
                if(subjectId) {
                    fetch(`/api/subjects/${subjectId}/specializations`)
                        .then(response => response.json())
                        .then(data => {
                            if(data.length > 0) {
                                specializationContainer.style.display = 'block';
                                data.forEach(spec => {
                                    const option = document.createElement('option');
                                    option.value = spec.id;
                                    option.textContent = spec.name;
                                    searchSpecialization.appendChild(option);
                                });
                            }
                        })
                        .catch(error => console.error('Error fetching specializations:', error));
                }
            });
        }
    })();
</script>
@endsection