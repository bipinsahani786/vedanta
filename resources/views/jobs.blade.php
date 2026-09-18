@extends('layouts.app')
@section('content')
<x-page-header title="Find Your Dream Role" :breadcrumbs="['Home' => route('home'), 'Jobs' => null]" />
<div class="py-12 px-6 lg:px-[5%] bg-gradient-to-r from-[#040e2d] via-[#129aef] to-[#040e2d] border-b border-white/10 relative overflow-hidden">
    <!-- Decorative Pattern -->
    <div class="absolute inset-0 z-0 opacity-10" style="background-image: radial-gradient(#ffffff 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>

    <div class="max-w-5xl mx-auto reveal relative z-10">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 relative overflow-hidden">
            <!-- Subtle accent inside the box -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-accent-blue/5 rounded-full blur-3xl -mr-20 -mt-20"></div>
            
            <h2 class="text-2xl font-bold text-slate-800 mb-8 relative z-10">Let Your Teaching Career Begin Here</h2>
            
            <form action="{{ route('jobs') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end relative z-10">
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                @if(request('job_type'))
                    <input type="hidden" name="job_type" value="{{ request('job_type') }}">
                @endif
                <!-- State -->
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-slate-600 mb-2">State</label>
                    <select name="state" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-accent-blue/30 focus:border-accent-blue appearance-none" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                        <option value="">Select State</option>
                        @foreach($states as $st)
                            <option value="{{ $st->id }}" {{ request('state') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Category -->
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-slate-600 mb-2">Category</label>
                    <select name="class" id="search_category" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-accent-blue/30 focus:border-accent-blue appearance-none" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('class') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Subject -->
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-slate-600 mb-2">Subject</label>
                    <select name="subject" id="search_subject" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-accent-blue/30 focus:border-accent-blue appearance-none" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                        <option value="">Select Subject</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}" {{ request('subject') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Specialization (Hidden initially) -->
                <div class="flex-1 w-full" id="specialization_container" style="display: none;">
                    <label class="block text-sm font-medium text-slate-600 mb-2">Specialization</label>
                    <select name="specialization" id="search_specialization" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-accent-blue/30 focus:border-accent-blue appearance-none" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                        <option value="">Select Specialization</option>
                    </select>
                </div>
                
                <!-- Search Button -->
                <div class="w-full md:w-auto flex gap-2">
                    <a href="{{ route('jobs') }}" class="w-full md:w-auto bg-slate-200 text-slate-700 rounded-lg px-8 py-3 font-bold hover:bg-slate-300 transition-colors shadow-sm text-center">Clear</a>
                    <button type="submit" class="w-full md:w-auto bg-white border border-slate-200 text-slate-800 rounded-lg px-8 py-3 font-bold hover:border-accent-blue hover:text-accent-blue transition-colors shadow-sm">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="py-12 px-6 lg:px-[5%] flex flex-col lg:flex-row gap-8 bg-white relative overflow-hidden">
    <!-- Decorative Pattern -->
    <div class="absolute inset-0 z-0 opacity-[0.02]" style="background-image: radial-gradient(#000000 1.5px, transparent 1.5px); background-size: 32px 32px;"></div>

    <!-- Job List (Reference Image 1 & 4) -->
    <div class="w-full relative z-10">
        @if(request('q'))
            <div class="mb-6 p-4 rounded-2xl bg-blue-50/80 border border-blue-200/70 flex flex-wrap items-center justify-between gap-3 text-slate-800 shadow-sm">
                <div class="flex items-center gap-2.5 text-xs sm:text-sm">
                    <span class="w-8 h-8 rounded-xl bg-[#129aef]/15 text-[#129aef] flex items-center justify-center font-bold">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <div>
                        <span>Search results for: <strong class="text-[#040e2d]">"{{ request('q') }}"</strong></span>
                        <span class="text-xs text-slate-500 font-semibold block sm:inline sm:ml-2">({{ $jobs->total() }} {{ Str::plural('job', $jobs->total()) }} found)</span>
                    </div>
                </div>
                <a href="{{ route('jobs') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white hover:bg-rose-50 text-rose-600 border border-rose-200/80 font-bold text-xs transition-all shadow-sm">
                    <i class="fas fa-times text-[10px]"></i>
                    <span>Clear Search</span>
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($jobs as $job)
            @php
                $isJobUnlocked = $job->canUserViewProtectedDetails();
            @endphp
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 flex flex-col justify-between hover:border-[#129aef]/60 hover:shadow-xl transition-all duration-300 group reveal relative">
                <div>
                    <!-- Card Top Meta: Job Code, Status, Bookmark -->
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
                        
                        <a href="{{ route('jobs.show', $job->id) }}" class="text-slate-400 hover:text-amber-500 transition-colors" title="Save Job">
                            <i class="far fa-bookmark text-sm"></i>
                        </a>
                    </div>
                    
                    <!-- Job Title -->
                    <h3 class="text-lg font-black text-slate-900 mb-1.5 group-hover:text-[#129aef] transition-colors line-clamp-1">
                        <a href="{{ route('jobs.show', $job->id) }}">{{ $job->title ?? 'Job Requirement' }}</a>
                    </h3>

                    <!-- School / Confidential Institution & Location (Masked if not unlocked) -->
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
                    <p class="text-xs text-slate-600 leading-relaxed mb-4 line-clamp-2">
                        {{ Str::limit(strip_tags($job->description), 110) ?: 'Seeking dedicated educators for this position. Candidate should have relevant qualification and experience.' }}
                    </p>

                    <!-- Salary Section (Formatted) -->
                    <div class="mb-4">
                        <span class="text-base font-black text-[#040e2d]">
                            {{ $job->formatted_salary }}
                        </span>
                    </div>

                    <!-- Protected Details Banner (if locked - Image 4) -->
                    @if(!$isJobUnlocked)
                    <div class="trigger-school-lock-modal mb-4 p-2.5 rounded-xl bg-blue-50/70 hover:bg-blue-100/70 border border-blue-200/60 text-[#129aef] text-xs font-bold flex items-center justify-between cursor-pointer transition-all">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-lock text-xs"></i>
                            <span>Login to view school name & exact location</span>
                        </span>
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </div>
                    @endif
                </div>
                
                <!-- Bottom Action Row (Image 1) -->
                <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
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
                        <span>Share</span>
                    </button>
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