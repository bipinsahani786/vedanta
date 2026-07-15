@extends('layouts.app')

@section('content')
@include('employer.partials.nav')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-text-main">Edit Job Posting</h1>
        <p class="text-sm text-text-dark/50 mt-0.5">Update the details of your pending job requirement.</p>
    </div>

    <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl reveal">
        <div class="p-8">
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-500 px-4 py-3 rounded-xl mb-6 flex items-start gap-3">
                    <i class="fas fa-check-circle mt-1"></i>
                    <div>
                        <p class="font-bold text-sm">Success!</p>
                        <p class="text-xs mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl mb-6 flex items-start gap-3">
                    <i class="fas fa-exclamation-circle mt-1"></i>
                    <div>
                        <p class="font-bold text-sm">Please fix the following errors:</p>
                        <ul class="list-disc pl-5 text-xs mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('employer.jobs.update', $job->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Institution Details (Read-only as they were saved on creation, or we can just hide them or make them disabled since they are not updated in JobController@update) -->
                <div class="opacity-50">
                    <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2 border-b border-card-border pb-2"><i class="fas fa-university text-accent-yellow"></i> Institution Details (Locked)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Institution/School Name</label>
                            <input type="text" value="{{ $job->school_name }}" disabled class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main cursor-not-allowed opacity-70">
                        </div>
                    </div>
                </div>

                <!-- Job Details -->
                <div class="pt-2">
                    <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2 border-b border-card-border pb-2"><i class="fas fa-briefcase text-accent-yellow"></i> Job Requirements</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Job Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $job->title) }}" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Job Category <span class="text-red-500">*</span></label>
                                <select id="category_id" name="category_id" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $job->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Subject <span class="text-red-500">*</span></label>
                                <select id="subject_id" name="subject_id" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                    <option value="">Select Subject</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Required Qualification <span class="text-red-500">*</span></label>
                                <select name="qualification_id" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                    <option value="">Select Qualification</option>
                                    @foreach($qualifications as $qualification)
                                        <option value="{{ $qualification->id }}" {{ old('qualification_id', $job->qualification_id) == $qualification->id ? 'selected' : '' }}>{{ $qualification->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- State Select -->
                            <div>
                                <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">State *</label>
                                <select id="state_select" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                    <option value="">Select State</option>
                                    @php
                                        $states = $locations->whereNotNull('state')->where('state', '!=', '')->pluck('state')->unique()->sort();
                                    @endphp
                                    @foreach($states as $state)
                                        <option value="{{ $state }}" {{ old('state_select', $job->location->state ?? '') == $state ? 'selected' : '' }}>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- City Select -->
                            <div>
                                <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">City *</label>
                                <select id="location_id" name="location_id" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                    <option value="">Select City</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Salary Range (Monthly)</label>
                                <input type="text" name="salary_range" value="{{ old('salary_range', $job->salary_range) }}" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Job Description <span class="text-red-500">*</span></label>
                            <textarea name="description" required rows="6" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors resize-none">{{ old('description', $job->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-card-border text-right">
                    <a href="{{ route('employer.jobs.index') }}" class="inline-block px-6 py-3.5 bg-secondary-bg hover:bg-white/5 text-text-main rounded-xl font-bold transition-colors mr-2">Cancel</a>
                    <button type="submit" class="px-8 py-3.5 bg-accent-yellow text-[#031b4e] font-bold rounded-xl shadow-lg hover:shadow-glow-yellow hover:-translate-y-0.5 transition-all">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const subjectSelect = document.getElementById('subject_id');
    const stateSelect = document.getElementById('state_select');
    const citySelect = document.getElementById('location_id');
    
    const initialSubjectId = "{{ old('subject_id', $job->subject_id) }}";
    const initialCityId = "{{ old('location_id', $job->location_id) }}";
    
    function loadSubjects(categoryId, selectedSubjectId = null) {
        subjectSelect.innerHTML = '<option value="">Select Subject</option>';
        if (categoryId) {
            fetch(`/api/categories/${categoryId}/subjects`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name;
                        if (selectedSubjectId && subject.id == selectedSubjectId) {
                            option.selected = true;
                        }
                        subjectSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching subjects:', error));
        }
    }
    
    function loadCities(stateName, selectedCityId = null) {
        citySelect.innerHTML = '<option value="">Select City</option>';
        if (stateName) {
            fetch(`/api/states/${encodeURIComponent(stateName)}/cities`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(loc => {
                        const option = document.createElement('option');
                        option.value = loc.id;
                        option.textContent = loc.city;
                        if (selectedCityId && loc.id == selectedCityId) {
                            option.selected = true;
                        }
                        citySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching cities:', error));
        }
    }
    
    if (categorySelect && subjectSelect) {
        const initialCategoryId = categorySelect.value;
        if (initialCategoryId) {
            loadSubjects(initialCategoryId, initialSubjectId);
        } else {
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
        }
        
        categorySelect.addEventListener('change', function() {
            loadSubjects(this.value);
        });
    }
    
    if (stateSelect && citySelect) {
        const initialState = stateSelect.value;
        if (initialState) {
            loadCities(initialState, initialCityId);
        } else {
            citySelect.innerHTML = '<option value="">Select City</option>';
        }
        
        stateSelect.addEventListener('change', function() {
            loadCities(this.value);
        });
    }
});
</script>
@endpush
