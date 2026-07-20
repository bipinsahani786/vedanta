@extends('layouts.admin')

@section('title', 'Manually Onboard Candidate')
@section('subtitle', 'Create a candidate profile, upload documents, and bypass public payment and OTP flows.')

@section('actions')
    <a href="{{ route('admin.crm.index') }}" class="px-5 py-2.5 bg-gray-600 text-white hover:bg-gray-700 rounded-xl text-sm font-semibold transition-all shadow-sm">
        &larr; Back to Candidates
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
    <form action="{{ route('admin.crm.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
        @csrf

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Section 1: Account Setup -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4">1. Account Setup</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Section 2: Personal Info -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4">2. Personal Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Address <span class="text-red-500">*</span></label>
                    <textarea name="address" rows="2" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 3: Professional Info -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4">3. Professional Details & Preferences</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teaching Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject <span class="text-red-500">*</span></label>
                    <select name="subject_id" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Highest Qualification <span class="text-red-500">*</span></label>
                    <select name="highest_qualification_id" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Qualification</option>
                        @foreach($qualifications as $qual)
                            <option value="{{ $qual->id }}" {{ old('highest_qualification_id') == $qual->id ? 'selected' : '' }}>{{ $qual->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Years of Experience <span class="text-red-500">*</span></label>
                    <input type="number" name="experience_years" value="{{ old('experience_years') }}" required min="0" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Salary</label>
                    <input type="text" name="current_salary" value="{{ old('current_salary') }}" placeholder="e.g. 30,000" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expected Salary</label>
                    <input type="text" name="expected_salary" value="{{ old('expected_salary') }}" placeholder="e.g. 40,000" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred State <span class="text-red-500">*</span></label>
                    <select name="preferred_state_id" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" {{ old('preferred_state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred City <span class="text-red-500">*</span></label>
                    <select name="preferred_city_id" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select City</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('preferred_city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">English Fluency</label>
                    <select name="english_fluency" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Fluency</option>
                        <option value="beginner" {{ old('english_fluency') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('english_fluency') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="fluent" {{ old('english_fluency') == 'fluent' ? 'selected' : '' }}>Fluent</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Residential Preference</label>
                    <select name="residential_preference" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Option</option>
                        <option value="residential" {{ old('residential_preference') == 'residential' ? 'selected' : '' }}>Residential</option>
                        <option value="day" {{ old('residential_preference') == 'day' ? 'selected' : '' }}>Day</option>
                        <option value="both" {{ old('residential_preference') == 'both' ? 'selected' : '' }}>Both</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Availability to Join</label>
                    <input type="text" name="availability_to_join" value="{{ old('availability_to_join') }}" placeholder="e.g. 15 Days" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current School</label>
                    <input type="text" name="current_school" value="{{ old('current_school') }}" placeholder="School Name" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Section 4: Document Uploads -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4">4. Document Uploads</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50/50 p-6 rounded-xl border border-gray-100">
                <div class="file-upload-wrapper">
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center justify-between">
                        <span>Resume (PDF, DOCX)</span>
                        <i class="fas fa-check-circle text-green-500 hidden upload-success-icon"></i>
                    </label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-gray-50 transition-colors">
                        <input type="file" name="resume" accept=".pdf,.doc,.docx" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer file-input">
                        <div class="text-gray-500 text-sm pointer-events-none">
                            <i class="fas fa-cloud-upload-alt text-xl mb-2 text-blue-400"></i>
                            <p class="file-name-display font-medium">Click to upload Resume</p>
                        </div>
                    </div>
                </div>
                <div class="file-upload-wrapper">
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center justify-between">
                        <span>Profile Photo (JPG, PNG)</span>
                        <i class="fas fa-check-circle text-green-500 hidden upload-success-icon"></i>
                    </label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-gray-50 transition-colors">
                        <input type="file" name="profile_photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer file-input">
                        <div class="text-gray-500 text-sm pointer-events-none">
                            <i class="fas fa-image text-xl mb-2 text-blue-400"></i>
                            <p class="file-name-display font-medium">Click to upload Profile Photo</p>
                        </div>
                    </div>
                </div>
                <div class="file-upload-wrapper">
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center justify-between">
                        <span>Live Photo / ID Photo (JPG, PNG)</span>
                        <i class="fas fa-check-circle text-green-500 hidden upload-success-icon"></i>
                    </label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-gray-50 transition-colors">
                        <input type="file" name="live_photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer file-input">
                        <div class="text-gray-500 text-sm pointer-events-none">
                            <i class="fas fa-camera text-xl mb-2 text-blue-400"></i>
                            <p class="file-name-display font-medium">Click to upload ID Photo</p>
                        </div>
                    </div>
                </div>
                <div class="file-upload-wrapper">
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center justify-between">
                        <span>Salary Slip (PDF, Image)</span>
                        <i class="fas fa-check-circle text-green-500 hidden upload-success-icon"></i>
                    </label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-gray-50 transition-colors">
                        <input type="file" name="salary_slip" accept=".pdf,image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer file-input">
                        <div class="text-gray-500 text-sm pointer-events-none">
                            <i class="fas fa-file-invoice-dollar text-xl mb-2 text-blue-400"></i>
                            <p class="file-name-display font-medium">Click to upload Salary Slip</p>
                        </div>
                    </div>
                </div>
                <div class="file-upload-wrapper">
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center justify-between">
                        <span>Offer Letter (PDF, Image)</span>
                        <i class="fas fa-check-circle text-green-500 hidden upload-success-icon"></i>
                    </label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-gray-50 transition-colors">
                        <input type="file" name="offer_letter" accept=".pdf,image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer file-input">
                        <div class="text-gray-500 text-sm pointer-events-none">
                            <i class="fas fa-envelope-open-text text-xl mb-2 text-blue-400"></i>
                            <p class="file-name-display font-medium">Click to upload Offer Letter</p>
                        </div>
                    </div>
                </div>
                <div class="file-upload-wrapper">
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center justify-between">
                        <span>Manually Signed Agreement (PDF)</span>
                        <i class="fas fa-check-circle text-green-500 hidden upload-success-icon"></i>
                    </label>
                    <div class="relative border-2 border-dashed border-green-300 rounded-xl p-4 text-center hover:bg-green-50 transition-colors bg-green-50/30">
                        <input type="file" name="agreement_pdf" accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer file-input">
                        <div class="text-green-700 text-sm pointer-events-none">
                            <i class="fas fa-file-signature text-xl mb-2 text-green-500"></i>
                            <p class="file-name-display font-bold">Click to upload Agreement</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5: Plan & Finalize -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4">5. Plan & Payment Status</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-blue-50/30 p-6 rounded-xl border border-blue-100">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Subscription Plan <span class="text-red-500">*</span></label>
                    <select name="plan_type" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Plan</option>
                        <option value="standard" {{ old('plan_type') == 'standard' ? 'selected' : '' }}>Standard Plan (₹500)</option>
                        <option value="premium" {{ old('plan_type') == 'premium' ? 'selected' : '' }}>Premium Plan (₹1000)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Mode <span class="text-red-500">*</span></label>
                    <select name="payment_method" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Mode</option>
                        <option value="CASH" {{ old('payment_method') == 'CASH' ? 'selected' : '' }}>Cash</option>
                        <option value="ONLINE_TRANSFER" {{ old('payment_method') == 'ONLINE_TRANSFER' ? 'selected' : '' }}>Online Transfer (UPI/NEFT)</option>
                        <option value="CHEQUE" {{ old('payment_method') == 'CHEQUE' ? 'selected' : '' }}>Cheque</option>
                        <option value="SPLIT_PAYMENT" {{ old('payment_method') == 'SPLIT_PAYMENT' ? 'selected' : '' }}>Split Payment</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount Collected (₹) <span class="text-red-500">*</span></label>
                    <input type="number" name="payment_amount" value="{{ old('payment_amount') }}" required min="0" placeholder="e.g. 500" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Notes (Optional)</label>
                    <input type="text" name="payment_notes" value="{{ old('payment_notes') }}" placeholder="e.g. Received via GPay, Transaction ID XXXXX..." class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> Complete Candidate Onboarding
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.querySelector('select[name="category_id"]');
        const subjectSelect = document.querySelector('select[name="subject_id"]');
        
        if (categorySelect && subjectSelect) {
            categorySelect.addEventListener('change', function() {
                const categoryId = this.value;
                subjectSelect.innerHTML = '<option value="">Loading...</option>';
                
                if (categoryId) {
                    fetch(`/api/categories/${categoryId}/subjects`)
                        .then(response => response.json())
                        .then(data => {
                            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                            data.forEach(subject => {
                                subjectSelect.innerHTML += `<option value="${subject.id}">${subject.name}</option>`;
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching subjects:', error);
                            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                        });
                } else {
                    subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                }
            });
        }

        const stateSelect = document.querySelector('select[name="preferred_state_id"]');
        const citySelect = document.querySelector('select[name="preferred_city_id"]');

        if (stateSelect && citySelect) {
            stateSelect.addEventListener('change', function() {
                const stateId = this.value;
                citySelect.innerHTML = '<option value="">Loading...</option>';
                
                if (stateId) {
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
        }

        // Handle File Upload UI
        const fileInputs = document.querySelectorAll('.file-input');
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                const wrapper = this.closest('.file-upload-wrapper');
                const nameDisplay = wrapper.querySelector('.file-name-display');
                const successIcon = wrapper.querySelector('.upload-success-icon');
                const dropZone = this.closest('.border-dashed');
                
                if (this.files && this.files.length > 0) {
                    nameDisplay.textContent = this.files[0].name;
                    nameDisplay.classList.add('text-green-600');
                    successIcon.classList.remove('hidden');
                    dropZone.classList.add('border-green-400', 'bg-green-50');
                } else {
                    nameDisplay.textContent = 'Click to upload';
                    nameDisplay.classList.remove('text-green-600');
                    successIcon.classList.add('hidden');
                    dropZone.classList.remove('border-green-400', 'bg-green-50');
                }
            });
        });
    });
</script>
@endpush
@endsection
