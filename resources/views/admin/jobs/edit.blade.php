@extends('layouts.admin')

@section('title', 'Edit Job')
@section('subtitle', 'Edit job posting details before or after approval.')

@section('actions')
    <a href="{{ route('admin.jobs.show', $job->id) }}" class="px-4 py-2 bg-secondary-bg border border-card-border hover:bg-card-border/50 text-text-main rounded-xl text-sm font-semibold transition-all">
        <i class="fas fa-arrow-left mr-2"></i> Back to Job
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-card-bg border border-card-border rounded-2xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-card-border bg-secondary-bg/30">
            <h3 class="text-lg font-bold text-text-main flex items-center gap-2">
                <i class="fas fa-edit text-accent-blue"></i> Edit Job Details
            </h3>
        </div>

        <form action="{{ route('admin.jobs.update', $job->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- School Name -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">School/Institution Name</label>
                    <input type="text" name="school_name" value="{{ old('school_name', $job->school_name) }}" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all" placeholder="e.g. Delhi Public School">
                    @error('school_name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Contact Person -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Contact Person</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person', $job->contact_person) }}" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all" placeholder="e.g. Mr. Sharma">
                    @error('contact_person') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $job->email) }}" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all" placeholder="e.g. hr@school.com">
                    @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $job->phone) }}" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all" placeholder="e.g. 9876543210">
                    @error('phone') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- School Image -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">School Image / Photo (Optional)</label>
                    <div class="flex items-center gap-4">
                        @if($job->school_image)
                            <div class="w-16 h-16 rounded-xl overflow-hidden border border-card-border shrink-0">
                                <img src="{{ asset('storage/' . $job->school_image) }}" class="w-full h-full object-cover" alt="School photo">
                            </div>
                        @endif
                        <input type="file" name="school_image" accept="image/*" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-accent-blue file:text-white hover:file:bg-accent-blue-hover">
                    </div>
                    <span class="text-[11px] text-text-dark/60 mt-1 block">Protected image displayed when candidate unlocks job details.</span>
                    @error('school_image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="h-px w-full bg-card-border my-8"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Job Title *</label>
                    <input type="text" name="title" value="{{ old('title', $job->title) }}" required class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                    @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Job Category *</label>
                    <select name="category_id" required class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $job->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Subject -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Subject *</label>
                    <select name="subject_id" required class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $job->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    @error('subject_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Qualification -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Required Qualification *</label>
                    <select name="qualification_id" required class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        <option value="">Select Qualification</option>
                        @foreach($qualifications as $qualification)
                            <option value="{{ $qualification->id }}" {{ old('qualification_id', $job->qualification_id) == $qualification->id ? 'selected' : '' }}>{{ $qualification->name }}</option>
                        @endforeach
                    </select>
                    @error('qualification_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Experience -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Experience</label>
                    <select name="experience" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        <option value="Preferred" {{ old('experience', $job->experience ?? 'Preferred') == 'Preferred' ? 'selected' : '' }}>Preferred</option>
                        <option value="Fresher" {{ old('experience', $job->experience) == 'Fresher' ? 'selected' : '' }}>Fresher</option>
                        <option value="1 - 3 Years" {{ old('experience', $job->experience) == '1 - 3 Years' ? 'selected' : '' }}>1 - 3 Years</option>
                        <option value="3 - 5 Years" {{ old('experience', $job->experience) == '3 - 5 Years' ? 'selected' : '' }}>3 - 5 Years</option>
                        <option value="5+ Years" {{ old('experience', $job->experience) == '5+ Years' ? 'selected' : '' }}>5+ Years</option>
                    </select>
                    @error('experience') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Openings -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">No. of Openings</label>
                    <input type="text" name="openings" value="{{ old('openings', $job->openings ?? 'Multiple') }}" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all" placeholder="e.g. 1, 2, or Multiple">
                    @error('openings') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- State -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">State *</label>
                    <select name="state_id" id="state_id" required class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        <option value="">Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" {{ old('state_id', $job->state_id) == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                    @error('state_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- City -->
                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">City *</label>
                    <select name="city_id" id="city_id" required class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        <option value="">Select City</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id', $job->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                    @error('city_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Salary Configuration (Interactive Section matching Reference Image 5) -->
                <div class="md:col-span-2 bg-secondary-bg/50 border border-card-border rounded-2xl p-6 shadow-inner">
                    <div class="flex items-center justify-between mb-4">
                        <label class="text-xs font-bold text-text-dark/80 uppercase tracking-wider flex items-center gap-2">
                            <i class="fas fa-indian-rupee-sign text-accent-blue"></i> Salary & Compensation (Reference Image 5)
                        </label>
                        <div id="salary-preview-badge" class="px-3 py-1 rounded-full bg-accent-blue/10 border border-accent-blue/30 text-accent-blue text-xs font-bold">
                            Preview: {{ $job->formatted_salary }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        <!-- Show pay by -->
                        <div>
                            <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Show pay by</label>
                            <select name="salary_mode" id="salary_mode" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue font-semibold transition-all">
                                <option value="range" {{ old('salary_mode', $job->salary_mode ?? 'range') == 'range' ? 'selected' : '' }}>Range</option>
                                <option value="starting_amount" {{ old('salary_mode', $job->salary_mode) == 'starting_amount' ? 'selected' : '' }}>Starting amount</option>
                                <option value="maximum_amount" {{ old('salary_mode', $job->salary_mode) == 'maximum_amount' ? 'selected' : '' }}>Maximum amount</option>
                                <option value="exact_amount" {{ old('salary_mode', $job->salary_mode) == 'exact_amount' ? 'selected' : '' }}>Exact amount</option>
                            </select>
                        </div>

                        <!-- Minimum / Amount -->
                        <div id="min_salary_wrapper">
                            <label id="min_salary_label" class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Minimum</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-text-dark/60 font-bold">₹</span>
                                <input type="number" step="100" name="salary_min" id="salary_min" value="{{ old('salary_min', $job->salary_min) }}" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl pl-8 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all font-semibold" placeholder="e.g. 40,000">
                            </div>
                        </div>

                        <!-- Maximum -->
                        <div id="max_salary_wrapper">
                            <label id="max_salary_label" class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Maximum</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-text-dark/60 font-bold">₹</span>
                                <input type="number" step="100" name="salary_max" id="salary_max" value="{{ old('salary_max', $job->salary_max) }}" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl pl-8 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all font-semibold" placeholder="e.g. 55,000">
                            </div>
                        </div>

                        <!-- Rate -->
                        <div>
                            <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Rate</label>
                            <select name="salary_rate" id="salary_rate" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue font-semibold transition-all">
                                <option value="per month" {{ old('salary_rate', $job->salary_rate ?? 'per month') == 'per month' ? 'selected' : '' }}>per month</option>
                                <option value="per year" {{ old('salary_rate', $job->salary_rate) == 'per year' ? 'selected' : '' }}>per year</option>
                                <option value="per hour" {{ old('salary_rate', $job->salary_rate) == 'per hour' ? 'selected' : '' }}>per hour</option>
                                <option value="per week" {{ old('salary_rate', $job->salary_rate) == 'per week' ? 'selected' : '' }}>per week</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Job Description & Requirements</label>
                    <textarea name="description" id="editor" rows="5" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">{{ old('description', $job->description) }}</textarea>
                    @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('admin.jobs.show', $job->id) }}" class="px-6 py-3 rounded-xl font-bold text-sm text-text-main bg-secondary-bg border border-card-border hover:bg-card-border/50 transition-all">Cancel</a>
                <button type="submit" class="px-6 py-3 rounded-xl font-bold text-sm text-white bg-accent-blue hover:bg-accent-blue-hover shadow-lg shadow-accent-blue/30 transition-all">
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<style>
    .ck-editor__editable_inline {
        min-height: 200px;
        color: #1e293b !important;
        background-color: #ffffff !important;
        border-radius: 0 0 0.75rem 0.75rem !important;
    }
    .ck-toolbar {
        border-radius: 0.75rem 0.75rem 0 0 !important;
        border-color: #cbd5e1 !important;
        background-color: #f8fafc !important;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editorEl = document.querySelector('#editor');
        if (editorEl) {
            ClassicEditor
                .create(editorEl, {
                    toolbar: ['heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo']
                })
                .catch(error => console.error('CKEditor init error:', error));
        }
    });

    document.getElementById('state_id').addEventListener('change', function() {
        let stateId = this.value;
        let citySelect = document.getElementById('city_id');
        citySelect.innerHTML = '<option value="">Loading...</option>';
        
        if(stateId) {
            fetch(`/api/states/${stateId}/cities`)
                .then(response => response.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    data.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Error fetching cities:', error);
                    citySelect.innerHTML = '<option value="">Select City</option>';
                });
        } else {
            citySelect.innerHTML = '<option value="">Select City</option>';
        }
    });

    // Salary Configuration Logic (Matching Reference Image 5)
    (function() {
        const modeSelect = document.getElementById('salary_mode');
        const minWrapper = document.getElementById('min_salary_wrapper');
        const maxWrapper = document.getElementById('max_salary_wrapper');
        const minLabel = document.getElementById('min_salary_label');
        const maxLabel = document.getElementById('max_salary_label');
        const minInput = document.getElementById('salary_min');
        const maxInput = document.getElementById('salary_max');
        const rateSelect = document.getElementById('salary_rate');
        const previewBadge = document.getElementById('salary-preview-badge');

        function formatCurrency(val) {
            const num = parseFloat(val);
            if (isNaN(num)) return '0';
            return num.toLocaleString('en-IN');
        }

        function updateSalaryFields() {
            const mode = modeSelect.value;
            const rate = rateSelect.value || 'per month';
            const minVal = parseFloat(minInput.value) || 0;
            const maxVal = parseFloat(maxInput.value) || 0;

            if (mode === 'range') {
                minWrapper.style.display = 'block';
                maxWrapper.style.display = 'block';
                minLabel.textContent = 'Minimum';
                maxLabel.textContent = 'Maximum';
                previewBadge.textContent = 'Preview: ₹' + formatCurrency(minVal) + ' – ₹' + formatCurrency(maxVal) + ' ' + rate;
            } else if (mode === 'starting_amount') {
                minWrapper.style.display = 'block';
                maxWrapper.style.display = 'none';
                minLabel.textContent = 'Starting Amount';
                previewBadge.textContent = 'Preview: From ₹' + formatCurrency(minVal) + ' ' + rate;
            } else if (mode === 'maximum_amount') {
                minWrapper.style.display = 'none';
                maxWrapper.style.display = 'block';
                maxLabel.textContent = 'Maximum Amount';
                previewBadge.textContent = 'Preview: Up to ₹' + formatCurrency(maxVal) + ' ' + rate;
            } else if (mode === 'exact_amount') {
                minWrapper.style.display = 'block';
                maxWrapper.style.display = 'none';
                minLabel.textContent = 'Exact Amount';
                previewBadge.textContent = 'Preview: ₹' + formatCurrency(minVal) + ' ' + rate;
            }
        }

        modeSelect.addEventListener('change', updateSalaryFields);
        rateSelect.addEventListener('change', updateSalaryFields);
        minInput.addEventListener('input', updateSalaryFields);
        maxInput.addEventListener('input', updateSalaryFields);

        // Initial setup
        updateSalaryFields();
    })();
</script>
@endpush
@endsection

