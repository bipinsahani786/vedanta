@extends('layouts.app')
@section('content')
<x-page-header title="Find Your Dream Role" :breadcrumbs="['Home' => route('home'), 'Jobs' => null]" />
<div class="py-12 px-6 lg:px-[5%] bg-slate-50/50 border-b border-slate-200 relative overflow-hidden">
    <!-- Decorative Pattern -->
    <div class="absolute inset-0 z-0 opacity-[0.04]" style="background-image: radial-gradient(#3b82f6 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>

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
                            <option value="{{ $st }}" {{ request('state') == $st ? 'selected' : '' }}>{{ $st }}</option>
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

    <!-- Filters -->
    <div class="w-full lg:w-1/4 relative z-10">
        <form action="{{ route('jobs') }}" method="GET" class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 sticky top-28">
            @if(request('state'))
                <input type="hidden" name="state" value="{{ request('state') }}">
            @endif
            @if(request('subject'))
                <input type="hidden" name="subject" value="{{ request('subject') }}">
            @endif
            @if(request('class'))
                <input type="hidden" name="class" value="{{ request('class') }}">
            @endif
            <h3 class="text-lg font-bold text-slate-900 mb-4">Filters</h3>
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-slate-800 mb-3">Job Type</h4>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-accent-blue transition-colors"><input type="radio" name="job_type" value="Full Time" class="accent-accent-blue" {{ request('job_type') == 'Full Time' ? 'checked' : '' }}> Full Time</label>
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-accent-blue transition-colors"><input type="radio" name="job_type" value="Part Time" class="accent-accent-blue" {{ request('job_type') == 'Part Time' ? 'checked' : '' }}> Part Time</label>
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-accent-blue transition-colors"><input type="radio" name="job_type" value="Contract" class="accent-accent-blue" {{ request('job_type') == 'Contract' ? 'checked' : '' }}> Contract</label>
                </div>
            </div>

            <div class="space-y-3">
                <button type="submit" class="w-full border-2 border-accent-blue text-accent-blue rounded-xl py-2.5 text-sm font-bold hover:bg-accent-blue hover:text-white transition-colors">Apply Filters</button>
                <a href="{{ route('jobs') }}" class="block text-center w-full bg-slate-100 text-slate-600 rounded-xl py-2.5 text-sm font-bold hover:bg-slate-200 transition-colors">Clear Filters</a>
            </div>
        </form>
    </div>

    <!-- Job List -->
    <div class="w-full lg:w-3/4 space-y-4 relative z-10">
        @forelse($jobs as $job)
        <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-accent-blue/50 hover:shadow-xl transition-all duration-300 group reveal">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center p-2 group-hover:scale-110 transition-transform">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($job->school_name) }}&background=random" class="rounded">
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-accent-blue transition-colors">
                            <a href="{{ route('jobs.show', $job->id) }}">{{ $job->title ?? 'Job Requirement' }}</a>
                        </h3>
                        <p class="text-sm text-slate-500 font-medium">{{ $job->school_name }} • {{ $job->location?->city ?? 'N/A' }}, {{ $job->location?->state ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="bg-blue-50 text-accent-blue px-3 py-1.5 rounded-full text-xs font-bold">{{ $job->category?->name ?? 'N/A' }}</span>
                    <p class="text-xs text-slate-400 font-medium mt-2">Posted {{ $job->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed mb-6">
                {{ Str::limit($job->description, 150) }}
            </p>
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <span class="bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 flex items-center gap-2 group-hover:border-accent-blue/30 transition-colors">
                    <i class="fas fa-book text-accent-blue"></i> {{ $job->subject?->name ?? 'N/A' }}
                </span>
                <span class="bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 flex items-center gap-2 group-hover:border-accent-blue/30 transition-colors">
                    <i class="fas fa-graduation-cap text-accent-blue"></i> {{ $job->qualification?->name ?? 'N/A' }}
                </span>
            </div>
            <div class="flex justify-between items-center border-t border-slate-100 pt-5">
                <div class="flex gap-4 text-sm font-bold text-slate-700">
                    @if($job->salary_range)
                    <span><i class="fas fa-rupee-sign text-slate-400"></i> {{ $job->salary_range }}</span>
                    @endif
                </div>
                <a href="{{ route('jobs.show', $job->id) }}" class="text-accent-blue font-bold text-sm hover:underline flex items-center gap-2">Apply Now <i class="fas fa-arrow-right"></i></a>
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
<!-- Registration Popup -->
<div id="jobRegPopup" class="fixed inset-0 hidden items-center justify-center px-4 bg-slate-900/50 backdrop-blur-sm opacity-0 transition-opacity duration-500" style="z-index: 99999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden relative transform transition-transform duration-500 popup-content" style="transform: scale(0.95);">
        <!-- Close Button -->
        <button id="closeJobRegPopup" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-full w-8 h-8 flex items-center justify-center transition-colors z-20">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-accent-blue to-blue-600 p-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-accent-blue shadow-lg text-2xl mx-auto mb-4 relative z-10">
                <i class="fas fa-user-plus"></i>
            </div>
            <h3 class="text-2xl font-bold text-white relative z-10">Join Us Now!</h3>
            <p class="text-blue-100 mt-2 text-sm relative z-10">Register to apply for jobs and get notified about new opportunities.</p>
        </div>
        
        <!-- Registration Form -->
        <div class="p-6 bg-slate-50">
            @if($errors->any())
                <div class="mb-4 bg-red-500/10 border border-red-500/30 p-3 rounded-xl">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                        <div>
                            <ul class="text-xs text-red-400 list-disc pl-4 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <form action="{{ route('candidate.register.post') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-user text-sm"></i></span>
                        <input name="name" type="text" required class="w-full bg-white border border-slate-200 rounded-lg pl-9 pr-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-accent-blue" placeholder="Full Name" value="{{ old('name') }}">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-envelope text-sm"></i></span>
                        <input name="email" type="email" required class="w-full bg-white border border-slate-200 rounded-lg pl-9 pr-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-accent-blue" placeholder="Email" value="{{ old('email') }}">
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-phone-alt text-sm"></i></span>
                        <input name="phone" type="text" required class="w-full bg-white border border-slate-200 rounded-lg pl-9 pr-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-accent-blue" placeholder="Phone Number" value="{{ old('phone') }}">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-lock text-sm"></i></span>
                        <input name="password" type="password" required class="w-full bg-white border border-slate-200 rounded-lg pl-9 pr-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-accent-blue" placeholder="Password">
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-shield-alt text-sm"></i></span>
                        <input name="password_confirmation" type="password" required class="w-full bg-white border border-slate-200 rounded-lg pl-9 pr-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-accent-blue" placeholder="Confirm Password">
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-accent-blue text-white font-bold py-2.5 rounded-lg hover:bg-blue-600 transition-colors shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 mt-2">
                    <i class="fas fa-paper-plane"></i> Register as Candidate
                </button>
            </form>
            
            <div class="mt-4 text-center">
                <p class="text-xs text-slate-500">Already have an account? <a href="{{ route('login') }}" class="text-accent-blue font-bold hover:underline">Login here</a></p>
                <p class="text-xs text-slate-400 mt-2">Looking to hire? <a href="{{ route('employer.register') }}" class="text-slate-600 hover:text-accent-yellow transition-colors underline">Register as Employer</a></p>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const showPopup = () => {
            const popup = document.getElementById('jobRegPopup');
            if(popup) {
                const content = popup.querySelector('.popup-content');
                
                popup.classList.remove('hidden');
                popup.style.display = 'flex';
                
                // Trigger animation
                setTimeout(() => {
                    popup.classList.remove('opacity-0');
                    popup.style.opacity = '1';
                    content.style.transform = 'scale(1)';
                }, 50);
            }
        };

        @if($errors->any())
            // Show immediately if there are validation errors
            showPopup();
        @else
            // Show after 2 seconds normally
            setTimeout(showPopup, 2000);
        @endif

        function closeJobPopup() {
            const popup = document.getElementById('jobRegPopup');
            const content = popup.querySelector('.popup-content');
            
            // Revert inline styles
            popup.style.opacity = '0';
            content.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                popup.style.display = 'none';
            }, 500);
        }

        // Attach event listeners immediately since script is at the bottom of the DOM
        const closeBtn = document.getElementById('closeJobRegPopup');
        if(closeBtn) {
            closeBtn.addEventListener('click', closeJobPopup);
        }

        const popupEl = document.getElementById('jobRegPopup');
        if(popupEl) {
            popupEl.addEventListener('click', function(e) {
                if(e.target === this) {
                    closeJobPopup();
                }
            });
        }

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