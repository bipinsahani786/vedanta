@extends('layouts.app')

@section('content')
@include('employer.partials.nav')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-text-main">Post a New Job</h1>
        <p class="text-sm text-text-dark/50 mt-0.5">Fill in the details to post a new job requirement. Once approved, it will be visible to candidates.</p>
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

            <form action="{{ route('employer.jobs.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Institution Details -->
                <div>
                    <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2 border-b border-card-border pb-2"><i class="fas fa-university text-accent-yellow"></i> Institution Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Institution/School Name <span class="text-red-500">*</span></label>
                            <input type="text" name="school_name" value="{{ old('school_name', $profile?->school_name ?? '') }}" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Contact Person <span class="text-red-500">*</span></label>
                            <input type="text" name="contact_person" value="{{ old('contact_person', $profile?->contact_person ?? auth()->user()->name) }}" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Contact Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Phone Number <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Job Details -->
                <div class="pt-2">
                    <h3 class="text-lg font-bold text-text-main mb-6 flex items-center gap-2 border-b border-card-border pb-2">
                        <i class="fas fa-briefcase text-accent-yellow"></i> Job Requirements
                    </h3>
                    
                    <div id="requirements-container" class="space-y-6">
                        @php
                            $oldRequirements = old('requirements', [[]]);
                        @endphp
                        @foreach($oldRequirements as $index => $oldReq)
                            <div class="requirement-block p-6 bg-secondary-bg/20 rounded-2xl border border-card-border mb-6 relative">
                                <button type="button" class="remove-requirement-btn absolute top-6 right-6 text-red-400 hover:text-red-500 text-sm font-semibold {{ count($oldRequirements) > 1 ? '' : 'hidden' }}">
                                    <i class="fas fa-trash mr-1"></i> Remove
                                </button>
                                <h4 class="text-sm font-bold text-accent-yellow mb-4 uppercase tracking-wider">
                                    Requirement #<span class="requirement-index">{{ $index + 1 }}</span>
                                </h4>
                                
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Job Title <span class="text-red-500">*</span></label>
                                        <input type="text" name="requirements[{{ $index }}][title]" value="{{ old("requirements.$index.title") }}" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors" placeholder="e.g. Senior Physics Teacher">
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Job Category <span class="text-red-500">*</span></label>
                                            <select name="requirements[{{ $index }}][category_id]" required class="category-select w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old("requirements.$index.category_id") == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Subject <span class="text-red-500">*</span></label>
                                            <select name="requirements[{{ $index }}][subject_id]" required class="subject-select w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                                <option value="">Select Subject</option>
                                                @if(old("requirements.$index.category_id"))
                                                    @php
                                                        $selectedCategory = $categories->find(old("requirements.$index.category_id"));
                                                        $subjs = $selectedCategory ? $selectedCategory->subjects()->where('is_active', true)->get() : collect();
                                                    @endphp
                                                    @foreach($subjs as $subj)
                                                        <option value="{{ $subj->id }}" {{ old("requirements.$index.subject_id") == $subj->id ? 'selected' : '' }}>{{ $subj->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Required Qualification <span class="text-red-500">*</span></label>
                                            <select name="requirements[{{ $index }}][qualification_id]" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                                <option value="">Select Qualification</option>
                                                @foreach($qualifications as $qualification)
                                                    <option value="{{ $qualification->id }}" {{ old("requirements.$index.qualification_id") == $qualification->id ? 'selected' : '' }}>{{ $qualification->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <!-- State Select -->
                                        <div>
                                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">State *</label>
                                            @php
                                                $selectedLoc = $locations->find(old("requirements.$index.location_id"));
                                                $selectedState = $selectedLoc ? $selectedLoc->state : '';
                                            @endphp
                                            <select required class="state-select w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                                <option value="">Select State</option>
                                                @php
                                                    $states = $locations->whereNotNull('state')->where('state', '!=', '')->pluck('state')->unique()->sort();
                                                @endphp
                                                @foreach($states as $state)
                                                    <option value="{{ $state }}" {{ $selectedState == $state ? 'selected' : '' }}>{{ $state }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <!-- City Select -->
                                        <div>
                                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">City *</label>
                                            <select name="requirements[{{ $index }}][location_id]" required class="city-select w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                                <option value="">Select City</option>
                                                @if($selectedState)
                                                    @php
                                                        $cities = $locations->where('state', $selectedState);
                                                    @endphp
                                                    @foreach($cities as $city)
                                                        <option value="{{ $city->id }}" {{ old("requirements.$index.location_id") == $city->id ? 'selected' : '' }}>{{ $city->city }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Salary Range (Monthly)</label>
                                            <input type="text" name="requirements[{{ $index }}][salary_range]" value="{{ old("requirements.$index.salary_range") }}" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors" placeholder="e.g. 40,000 - 60,000">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Job Description <span class="text-red-500">*</span></label>
                                        <textarea name="requirements[{{ $index }}][description]" required rows="6" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors resize-none" placeholder="Describe the responsibilities and requirements...">{{ old("requirements.$index.description") }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add More Button -->
                    <div class="mt-4">
                        <button type="button" id="add-requirement-btn" class="w-full py-4 border-2 border-dashed border-card-border rounded-2xl text-sm font-bold text-text-dark/60 hover:text-accent-yellow hover:border-accent-yellow/50 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-plus-circle"></i> Add Another Job Requirement
                        </button>
                    </div>
                </div>

                <div class="pt-6 border-t border-card-border flex justify-between items-center">
                    <div class="text-xs text-text-dark/40">
                        * All posted jobs require administrator review before going live.
                    </div>
                    <div class="text-right">
                        <a href="{{ route('employer.jobs.index') }}" class="inline-block px-6 py-3.5 bg-secondary-bg hover:bg-white/5 text-text-main rounded-xl font-bold transition-colors mr-2">Cancel</a>
                        <button type="submit" class="px-8 py-3.5 bg-accent-yellow text-[#031b4e] font-bold rounded-xl shadow-lg hover:shadow-glow-yellow hover:-translate-y-0.5 transition-all">Submit for Approval</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('requirements-container');
    const addButton = document.getElementById('add-requirement-btn');
    
    // Function to update indices and hidden removal buttons
    function updateBlockIndices() {
        const blocks = container.querySelectorAll('.requirement-block');
        blocks.forEach((block, index) => {
            block.querySelector('.requirement-index').textContent = index + 1;
            
            // Update input/select names
            block.querySelectorAll('[name^="requirements["]').forEach(input => {
                const name = input.getAttribute('name');
                const newName = name.replace(/requirements\[\d+\]/, `requirements[${index}]`);
                input.setAttribute('name', newName);
            });
            
            // Show/hide remove button
            const removeBtn = block.querySelector('.remove-requirement-btn');
            if (blocks.length > 1) {
                removeBtn.classList.remove('hidden');
            } else {
                removeBtn.classList.add('hidden');
            }
        });
    }
    
    if (addButton && container) {
        addButton.addEventListener('click', function() {
            // Get the first requirement block as template
            const template = container.querySelector('.requirement-block');
            const clone = template.cloneNode(true);
            
            // Clear input values in clone
            clone.querySelectorAll('input').forEach(input => input.value = '');
            clone.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            clone.querySelectorAll('textarea').forEach(textarea => textarea.value = '');
            
            // Clear subject and city select options
            clone.querySelector('.subject-select').innerHTML = '<option value="">Select Subject</option>';
            clone.querySelector('.city-select').innerHTML = '<option value="">Select City</option>';
            
            // Append clone
            container.appendChild(clone);
            updateBlockIndices();
        });
    }
    
    // Handle remove block
    container.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-requirement-btn');
        if (removeBtn) {
            const block = removeBtn.closest('.requirement-block');
            block.remove();
            updateBlockIndices();
        }
    });

    // Event delegation for category -> subject, state -> city
    document.addEventListener('change', function(e) {
        if (e.target.matches('.category-select')) {
            const categoryId = e.target.value;
            const block = e.target.closest('.requirement-block');
            const subjectSelect = block.querySelector('.subject-select');
            
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            if (categoryId) {
                fetch(`/api/categories/${categoryId}/subjects`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = subject.name;
                            subjectSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching subjects:', error));
            }
        }
        
        if (e.target.matches('.state-select')) {
            const stateName = e.target.value;
            const block = e.target.closest('.requirement-block');
            const citySelect = block.querySelector('.city-select');
            
            citySelect.innerHTML = '<option value="">Select City</option>';
            if (stateName) {
                fetch(`/api/states/${encodeURIComponent(stateName)}/cities`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(loc => {
                            const option = document.createElement('option');
                            option.value = loc.id;
                            option.textContent = loc.city;
                            citySelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching cities:', error));
            }
        }
    });
});
</script>
@endpush
@endsection
