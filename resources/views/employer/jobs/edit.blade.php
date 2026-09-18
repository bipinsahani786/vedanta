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

            <form action="{{ route('employer.jobs.update', $job->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- School / Institution Banner Image Upload -->
                <div>
                    <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2 border-b border-card-border pb-2">
                        <i class="fas fa-image text-accent-yellow"></i> School / Institution Banner Image <span class="text-xs font-normal text-text-dark/60 lowercase">(optional)</span>
                    </h3>
                    
                    <div id="school-image-upload-box" class="relative group border-2 border-dashed border-card-border hover:border-accent-yellow/60 rounded-2xl bg-secondary-bg/30 p-6 transition-all duration-200 text-center cursor-pointer">
                        <input type="file" name="school_image" id="school_image" accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden">
                        <input type="hidden" name="remove_school_image" id="remove_school_image" value="0">
                        
                        <!-- Empty State -->
                        <div id="image-empty-state" class="{{ $job->school_image ? 'hidden' : 'py-4' }}">
                            <div class="w-16 h-16 rounded-2xl bg-accent-yellow/10 border border-accent-yellow/20 text-accent-yellow flex items-center justify-center mx-auto mb-3 text-2xl group-hover:scale-110 transition-transform shadow-sm">
                                <i class="fas fa-cloud-arrow-up"></i>
                            </div>
                            <p class="text-sm font-bold text-text-main mb-1">
                                Click or drag &amp; drop to upload School / Campus Photo
                            </p>
                            <p class="text-xs text-text-dark/60">
                                Recommended: 1200×600px landscape photo • PNG, JPG, WEBP (Max 3MB)
                            </p>
                        </div>

                        <!-- Preview State -->
                        <div id="image-preview-state" class="{{ $job->school_image ? '' : 'hidden' }}">
                            <div class="relative max-w-xl mx-auto rounded-xl overflow-hidden border border-card-border shadow-lg">
                                <img id="image-preview-img" src="{{ $job->school_image ? asset('storage/' . $job->school_image) : '' }}" alt="Preview" class="w-full h-48 sm:h-56 object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-transparent to-transparent pointer-events-none"></div>
                                <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between pointer-events-auto">
                                    <span id="image-preview-name" class="text-xs font-bold text-white bg-black/60 backdrop-blur-md px-3 py-1 rounded-lg truncate max-w-[220px]">
                                        {{ $job->school_image ? 'Current Banner Photo' : '' }}
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button type="button" id="btn-change-image" class="px-3 py-1.5 bg-white/90 hover:bg-white text-slate-900 rounded-lg text-xs font-bold transition-all shadow-md flex items-center gap-1">
                                            <i class="fas fa-arrows-rotate text-[10px]"></i> Change
                                        </button>
                                        <button type="button" id="btn-remove-image" class="px-3 py-1.5 bg-red-500/90 hover:bg-red-600 text-white rounded-lg text-xs font-bold transition-all shadow-md flex items-center gap-1">
                                            <i class="fas fa-trash-alt text-[10px]"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="text-[11px] text-text-dark/60 mt-2 block">
                        <i class="fas fa-shield-halved text-accent-yellow mr-1"></i> Displayed as the official verified campus banner when candidates view or unlock job details.
                    </span>
                    @error('school_image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

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
                                <select name="category_id" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $job->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Subject <span class="text-red-500">*</span></label>
                                <select name="subject_id" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id', $job->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                    @endforeach
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
                            <div>
                                <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">State <span class="text-red-500">*</span></label>
                                <select name="state_id" id="state_id" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" {{ old('state_id', $job->state_id) == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">City <span class="text-red-500">*</span></label>
                                <select name="city_id" id="city_id" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id', $job->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @php
                                // Intelligently parse min and max if salary_min/salary_max are null but salary_range exists
                                $initMin = $job->salary_min;
                                $initMax = $job->salary_max;
                                if (is_null($initMin) && is_null($initMax) && !empty($job->salary_range)) {
                                    preg_match_all('/\d+/', str_replace(',', '', $job->salary_range), $numMatches);
                                    if (!empty($numMatches[0])) {
                                        $initMin = $numMatches[0][0] ?? null;
                                        $initMax = $numMatches[0][1] ?? null;
                                    }
                                }
                                $initMin = $initMin !== null ? (int)$initMin : 40000;
                                $initMax = $initMax !== null ? (int)$initMax : 55000;
                            @endphp

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
                                        <select name="salary_mode" id="salary_mode" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue font-semibold transition-all text-sm">
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
                                            <input type="number" step="100" name="salary_min" id="salary_min" value="{{ old('salary_min', $initMin) }}" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl pl-8 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all font-semibold text-sm" placeholder="e.g. 40,000">
                                        </div>
                                    </div>

                                    <!-- Maximum -->
                                    <div id="max_salary_wrapper">
                                        <label id="max_salary_label" class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Maximum</label>
                                        <div class="relative">
                                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-text-dark/60 font-bold">₹</span>
                                            <input type="number" step="100" name="salary_max" id="salary_max" value="{{ old('salary_max', $initMax) }}" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl pl-8 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all font-semibold text-sm" placeholder="e.g. 55,000">
                                        </div>
                                    </div>

                                    <!-- Rate -->
                                    <div>
                                        <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wide mb-2">Rate</label>
                                        <select name="salary_rate" id="salary_rate" class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue font-semibold transition-all text-sm">
                                            <option value="per month" {{ old('salary_rate', $job->salary_rate ?? 'per month') == 'per month' ? 'selected' : '' }}>per month</option>
                                            <option value="per year" {{ old('salary_rate', $job->salary_rate) == 'per year' ? 'selected' : '' }}>per year</option>
                                            <option value="per hour" {{ old('salary_rate', $job->salary_rate) == 'per hour' ? 'selected' : '' }}>per hour</option>
                                            <option value="per week" {{ old('salary_rate', $job->salary_rate) == 'per week' ? 'selected' : '' }}>per week</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="salary_range" id="salary_range" value="{{ old('salary_range', $job->salary_range) }}">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Job Description <span class="text-red-500">*</span></label>
                            <textarea name="description" id="editor" rows="6" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors resize-none">{{ old('description', $job->description) }}</textarea>
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
        const rangeHidden = document.getElementById('salary_range');

        if (!modeSelect || !minInput || !maxInput || !rateSelect || !previewBadge) return;

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
                if (rangeHidden) rangeHidden.value = '₹' + formatCurrency(minVal) + ' – ₹' + formatCurrency(maxVal) + ' ' + rate;
            } else if (mode === 'starting_amount') {
                minWrapper.style.display = 'block';
                maxWrapper.style.display = 'none';
                minLabel.textContent = 'Starting Amount';
                previewBadge.textContent = 'Preview: From ₹' + formatCurrency(minVal) + ' ' + rate;
                if (rangeHidden) rangeHidden.value = 'From ₹' + formatCurrency(minVal) + ' ' + rate;
            } else if (mode === 'maximum_amount') {
                minWrapper.style.display = 'none';
                maxWrapper.style.display = 'block';
                maxLabel.textContent = 'Maximum Amount';
                previewBadge.textContent = 'Preview: Up to ₹' + formatCurrency(maxVal) + ' ' + rate;
                if (rangeHidden) rangeHidden.value = 'Up to ₹' + formatCurrency(maxVal) + ' ' + rate;
            } else if (mode === 'exact_amount') {
                minWrapper.style.display = 'block';
                maxWrapper.style.display = 'none';
                minLabel.textContent = 'Exact Amount';
                previewBadge.textContent = 'Preview: ₹' + formatCurrency(minVal) + ' ' + rate;
                if (rangeHidden) rangeHidden.value = '₹' + formatCurrency(minVal) + ' ' + rate;
            }
        }

        modeSelect.addEventListener('change', updateSalaryFields);
        rateSelect.addEventListener('change', updateSalaryFields);
        minInput.addEventListener('input', updateSalaryFields);
        maxInput.addEventListener('input', updateSalaryFields);

        // Initial setup
        updateSalaryFields();
    })();

    // Dynamic School Image Uploader
    (function() {
        const uploadBox = document.getElementById('school-image-upload-box');
        const fileInput = document.getElementById('school_image');
        const removeInput = document.getElementById('remove_school_image');
        const emptyState = document.getElementById('image-empty-state');
        const previewState = document.getElementById('image-preview-state');
        const previewImg = document.getElementById('image-preview-img');
        const previewName = document.getElementById('image-preview-name');
        const btnChange = document.getElementById('btn-change-image');
        const btnRemove = document.getElementById('btn-remove-image');

        if (!uploadBox || !fileInput) return;

        uploadBox.addEventListener('click', function(e) {
            if (e.target.closest('#btn-remove-image')) return;
            fileInput.click();
        });

        if (btnChange) {
            btnChange.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.click();
            });
        }

        if (btnRemove) {
            btnRemove.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.value = '';
                previewImg.src = '';
                previewState.classList.add('hidden');
                emptyState.classList.remove('hidden');
                if (removeInput) removeInput.value = '1';
            });
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadBox.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                uploadBox.classList.add('border-accent-yellow', 'bg-accent-yellow/5');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadBox.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                uploadBox.classList.remove('border-accent-yellow', 'bg-accent-yellow/5');
            }, false);
        });

        uploadBox.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                fileInput.files = files;
                handleFile(files[0]);
            }
        });

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                handleFile(this.files[0]);
            }
        });

        function handleFile(file) {
            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image file (PNG, JPG, JPEG, WEBP).');
                return;
            }
            if (file.size > 3 * 1024 * 1024) {
                alert('File size exceeds 3MB limit. Please choose a smaller image.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                if (previewName) {
                    const sizeKb = (file.size / 1024).toFixed(0);
                    previewName.textContent = file.name + ' (' + sizeKb + ' KB)';
                }
                emptyState.classList.add('hidden');
                previewState.classList.remove('hidden');
                if (removeInput) removeInput.value = '0';
            };
            reader.readAsDataURL(file);
        }
    })();
</script>
@endpush
