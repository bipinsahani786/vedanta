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

    <!-- Job List -->
    <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 relative z-10">
        @forelse($jobs as $job)
        <div class="bg-white border border-slate-200 rounded-2xl p-6 flex flex-col justify-between hover:border-accent-blue/50 hover:shadow-xl transition-all duration-300 group reveal">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center p-2 group-hover:scale-110 transition-transform">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($job->school_name) }}&background=random" class="rounded">
                    </div>
                    <span class="bg-blue-50 text-accent-blue px-3 py-1.5 rounded-full text-xs font-bold whitespace-nowrap">{{ $job->category?->name ?? 'N/A' }}</span>
                </div>
                
                <h3 class="text-lg font-bold text-slate-900 mb-1 group-hover:text-accent-blue transition-colors line-clamp-1">
                    <a href="{{ route('jobs.show', $job->id) }}">{{ $job->title ?? 'Job Requirement' }}</a>
                </h3>
                <p class="text-sm text-slate-500 font-medium mb-3 line-clamp-1">{{ $job->school_name }} • {{ $job->city?->name ?? 'N/A' }}, {{ $job->state?->name ?? 'N/A' }}</p>
                
                <p class="text-sm text-slate-600 leading-relaxed mb-5 line-clamp-3">
                    {{ Str::limit(strip_tags($job->description), 100) }}
                </p>
                
                <div class="flex flex-wrap items-center gap-2 mb-6">
                    <span class="bg-slate-50 border border-slate-200 px-2 py-1 rounded-lg text-[11px] font-bold text-slate-600 flex items-center gap-1.5 transition-colors">
                        <i class="fas fa-book text-accent-blue"></i> {{ Str::limit($job->subject?->name ?? 'N/A', 15) }}
                    </span>
                    <span class="bg-slate-50 border border-slate-200 px-2 py-1 rounded-lg text-[11px] font-bold text-slate-600 flex items-center gap-1.5 transition-colors">
                        <i class="fas fa-graduation-cap text-accent-blue"></i> {{ Str::limit($job->qualification?->name ?? 'N/A', 15) }}
                    </span>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between items-center border-t border-slate-100 pt-4">
                    <div class="flex flex-col">
                        @if($job->salary_range)
                        <span class="text-sm font-bold text-slate-700"><i class="fas fa-rupee-sign text-slate-400"></i> {{ $job->salary_range }}</span>
                        @endif
                        <span class="text-[10px] text-slate-400 font-medium mt-1">Posted {{ $job->created_at->diffForHumans() }}</span>
                    </div>
                    <a href="{{ route('jobs.show', $job->id) }}" class="text-white bg-accent-blue px-4 py-2 rounded-lg font-bold text-xs hover:bg-blue-600 transition-colors shadow-glow-blue flex items-center gap-2">Apply</a>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-16 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-slate-300 shadow-sm text-2xl mx-auto mb-4"><i class="fas fa-briefcase"></i></div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">No Active Jobs</h3>
            <p class="text-slate-500 text-sm max-w-md mx-auto">We currently don't have any job openings that match your exact criteria. Please try adjusting your filters.</p>
        </div>
        @endforelse

        <div class="mt-12">
            {{ $jobs->links() }}
        </div>
    </div>
</div>
@include('partials.job-registration-popup')

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