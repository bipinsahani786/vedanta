@extends('layouts.admin')

@section('title', 'Post New Job')
@section('subtitle', 'Create a new job posting directly from the admin panel.')

@section('actions')
    <a href="{{ route('admin.jobs.index') }}" class="px-4 py-2 bg-secondary-bg border border-card-border hover:bg-card-border/50 text-text-main rounded-xl text-sm font-semibold transition-all">
        <i class="fas fa-arrow-left mr-2"></i> Back to Jobs
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-card-bg border border-card-border rounded-2xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-card-border bg-secondary-bg/30">
            <h3 class="text-lg font-bold text-text-main flex items-center gap-2">
                <i class="fas fa-briefcase text-accent-blue"></i> Job Details
            </h3>
        </div>

        <form action="{{ route('admin.jobs.store') }}" method="POST" class="p-8">
            @csrf
            
            <!-- Hidden institutional fields with default values -->
            <input type="hidden" name="school_name" value="Vedanta Placement Agency">
            <input type="hidden" name="contact_person" value="Super Admin">
            <input type="hidden" name="email" value="admin@vedanta.com">
            <input type="hidden" name="phone" value="9876543210">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Job Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all" placeholder="e.g. Computer Teacher">
                    @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Job Category *</label>
                    <select id="category_id" name="category_id" required class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Subject -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Subject *</label>
                    <select id="subject_id" name="subject_id" required class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        <option value="">Select Subject</option>
                    </select>
                    @error('subject_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Qualification -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Required Qualification *</label>
                    <select name="qualification_id" required class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        <option value="">Select Qualification</option>
                        @foreach($qualifications as $qualification)
                            <option value="{{ $qualification->id }}" {{ old('qualification_id') == $qualification->id ? 'selected' : '' }}>{{ $qualification->name }}</option>
                        @endforeach
                    </select>
                    @error('qualification_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- State -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">State *</label>
                    <select id="state_select" required class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        <option value="">Select State</option>
                        @php
                            $states = $locations->whereNotNull('state')->where('state', '!=', '')->pluck('state')->unique()->sort();
                        @endphp
                        @foreach($states as $state)
                            <option value="{{ $state }}" {{ old('state_select') == $state ? 'selected' : '' }}>{{ $state }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- City -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">City *</label>
                    <select id="location_id" name="location_id" required class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        <option value="">Select City</option>
                    </select>
                    @error('location_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Salary Range -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Salary Range</label>
                    <input type="text" name="salary_range" value="{{ old('salary_range') }}" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all" placeholder="e.g. 30,000 - 45,000 / month">
                    @error('salary_range') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Job Description & Requirements</label>
                    <textarea name="description" rows="5" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all" placeholder="Enter detailed job description, responsibilities, and requirements here...">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Publishing Status *</label>
                    <select name="status" required class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        <option value="approved" selected>Live / Approved (Publish Immediately)</option>
                        <option value="pending">Pending Review (Save as Draft)</option>
                        <option value="rejected">Rejected / Closed</option>
                    </select>
                    @error('status') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('admin.jobs.index') }}" class="px-6 py-3 rounded-xl font-bold text-sm text-text-main bg-secondary-bg border border-card-border hover:bg-card-border/50 transition-all">Cancel</a>
                <button type="submit" class="px-6 py-3 rounded-xl font-bold text-sm text-white bg-accent-blue hover:bg-accent-blue-hover shadow-lg shadow-accent-blue/30 transition-all">
                    Post Job Now
                </button>
            </div>
        </form>
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
    
    const initialSubjectId = "{{ old('subject_id') }}";
    const initialCityId = "{{ old('location_id') }}";
    
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
