@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="min-h-[85vh] bg-secondary-bg py-12 px-4 sm:px-6 lg:px-8 relative" x-data="registrationWizard()">
    <!-- Decorative background elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-accent-blue/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-accent-yellow/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="max-w-4xl mx-auto relative z-10">
        <!-- Header -->
        <div class="text-center mb-10 reveal">
            <h1 class="text-3xl md:text-4xl font-bold text-text-main mb-3">Complete Your Registration</h1>
            <p class="text-text-dark/60 text-sm md:text-base max-w-xl mx-auto">Follow these simple steps to complete your profile and activate your candidate account.</p>
        </div>

        <!-- Progress Bar -->
        <div class="mb-10 reveal reveal-delay-1">
            <div class="flex justify-between mb-2">
                <template x-for="(s, index) in steps" :key="index">
                    <div class="text-center w-1/3 relative z-10">
                        <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300"
                            :class="[
                                step > index + 1 ? 'bg-green-500 border-green-500 text-white' : '',
                                step === index + 1 ? 'bg-accent-blue border-accent-blue text-white shadow-[0_0_15px_rgba(18,154,239,0.5)]' : '',
                                step < index + 1 ? 'bg-card-bg border-card-border text-text-dark/40' : ''
                            ]">
                            <i x-show="step > index + 1" class="fas fa-check"></i>
                            <span x-show="step <= index + 1" x-text="index + 1"></span>
                        </div>
                        <div class="mt-3 text-xs font-semibold tracking-wider uppercase transition-colors duration-300"
                            :class="step >= index + 1 ? 'text-text-main' : 'text-text-dark/40'"
                            x-text="s"></div>
                    </div>
                </template>
            </div>
            <!-- Connecting Line -->
            <div class="relative w-full h-1 bg-card-border rounded-full -mt-[3.25rem] z-0 mx-auto" style="width: 66%;">
                <div class="absolute top-0 left-0 h-full bg-accent-blue rounded-full transition-all duration-500 ease-out"
                    :style="'width: ' + ((step - 1) / (steps.length - 1) * 100) + '%'"></div>
            </div>
        </div>

        <!-- Forms Container -->
        <div class="bg-card-bg/80 backdrop-blur-xl border border-card-border rounded-3xl shadow-2xl overflow-hidden reveal reveal-delay-2 relative">
            
            <!-- Loading Overlay -->
            <div x-show="loading" class="absolute inset-0 z-50 bg-card-bg/80 backdrop-blur-sm flex flex-col items-center justify-center" x-transition>
                <div class="w-10 h-10 border-4 border-accent-blue border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-4 text-sm font-semibold text-text-main animate-pulse" x-text="loadingMessage"></p>
            </div>

            <!-- Error Message Summary -->
            <div x-show="error || Object.keys(fieldErrors).length > 0" class="bg-red-500/10 border-l-4 border-red-500 p-4 mb-4 mx-8 mt-8 rounded-r-xl" x-transition>
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-0.5">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm text-red-400 font-bold" x-text="error || 'Please correct the errors below:'"></p>
                        <ul class="mt-2 text-xs text-red-300 list-disc list-inside space-y-1" x-show="Object.keys(fieldErrors).length > 0">
                            <template x-for="(errArr, field) in fieldErrors" :key="field">
                                <template x-for="(msg, i) in errArr" :key="i">
                                    <li x-text="msg"></li>
                                </template>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="p-8 md:p-10">
                <!-- STEP 1: Profile Details -->
                <div x-show="step === 1" x-transition.opacity.duration.500ms>
                    <h2 class="text-2xl font-bold text-text-main mb-6 flex items-center gap-3">
                        <i class="fas fa-user-edit text-accent-blue"></i> Profile Details
                    </h2>
                    
                    <form id="step1Form" @submit.prevent="submitStep1" novalidate>
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date of Birth -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Date of Birth *</label>
                                <div class="relative">
                                    <input type="text" x-model="formData.date_of_birth" required
                                        x-init="flatpickr($el, { dateFormat: 'Y-m-d', maxDate: 'today' })"
                                        placeholder="YYYY-MM-DD"
                                        class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                <template x-if="fieldErrors.date_of_birth"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.date_of_birth[0]"></p></template>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-text-main/50">
                                        <i class="far fa-calendar-alt"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Gender -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Gender *</label>
                                <select x-model="formData.gender" required
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <template x-if="fieldErrors.gender"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.gender[0]"></p></template>
                            </div>

                            <!-- Profile Photo (Required) -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Profile Photo *</label>
                                <input type="file" accept="image/*" @change="handleProfilePhotoUpload"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-accent-blue file:text-white hover:file:bg-accent-blue-hover cursor-pointer">
                                <template x-if="fieldErrors.profile_photo"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.profile_photo[0]"></p></template>
                                <p class="text-xs text-text-dark/40 mt-1">Format: JPG, PNG. Max size: 2MB.</p>
                                <div x-show="profilePhotoPreview || '{{ $profile->profile_photo_path ? Storage::url($profile->profile_photo_path) : '' }}'" class="mt-3">
                                    <img :src="profilePhotoPreview || '{{ $profile->profile_photo_path ? Storage::url($profile->profile_photo_path) : '' }}'" class="h-20 w-20 object-cover rounded-full border-2 border-accent-blue shadow-lg">
                                </div>
                            </div>

                            <!-- Resume Upload (Required) -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Resume / CV *</label>
                                <input type="file" accept=".pdf,.doc,.docx" @change="handleResumeUpload"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-accent-blue file:text-white hover:file:bg-accent-blue-hover cursor-pointer">
                                <template x-if="fieldErrors.resume"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.resume[0]"></p></template>
                                <p class="text-xs text-text-dark/40 mt-1">Format: PDF, DOC, DOCX. Max size: 2MB.</p>
                            </div>

                            <!-- Salary Slip (Optional) -->
                            <div class="md:col-span-1">
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Salary Slip (Optional)</label>
                                <input type="file" accept=".pdf,.doc,.docx,.jpg,.png,.jpeg" @change="handleSalarySlipUpload"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-accent-blue file:text-white hover:file:bg-accent-blue-hover cursor-pointer">
                                <template x-if="fieldErrors.salary_slip"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.salary_slip[0]"></p></template>
                            </div>

                            <!-- Offer Letter (Optional) -->
                            <div class="md:col-span-1">
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Offer Letter (Optional)</label>
                                <input type="file" accept=".pdf,.doc,.docx,.jpg,.png,.jpeg" @change="handleOfferLetterUpload"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-accent-blue file:text-white hover:file:bg-accent-blue-hover cursor-pointer">
                                <template x-if="fieldErrors.offer_letter"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.offer_letter[0]"></p></template>
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Full Address *</label>
                                <textarea x-model="formData.address" required rows="2"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                                    placeholder="Enter your complete address"></textarea>
                                <template x-if="fieldErrors.address"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.address[0]"></p></template>
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Position Applied For *</label>
                                <select x-model="formData.category_id" required
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <template x-if="fieldErrors.category_id"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.category_id[0]"></p></template>
                            </div>

                            <!-- Subject -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Subject *</label>
                                <select x-model="formData.subject_id" required
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                    <option value="">Select Subject</option>
                                    <template x-for="subject in availableSubjects" :key="subject.id">
                                        <option :value="subject.id" x-text="subject.name" :selected="formData.subject_id == subject.id"></option>
                                    </template>
                                </select>
                                <template x-if="fieldErrors.subject_id"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.subject_id[0]"></p></template>
                            </div>

                            <!-- Specialization -->
                            <div x-show="availableSpecializations.length > 0">
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Specialization *</label>
                                <select x-model="formData.specialization_id" :required="availableSpecializations.length > 0"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                    <option value="">Select Specialization</option>
                                    <template x-for="spec in availableSpecializations" :key="spec.id">
                                        <option :value="spec.id" x-text="spec.name" :selected="formData.specialization_id == spec.id"></option>
                                    </template>
                                </select>
                                <template x-if="fieldErrors.specialization_id"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.specialization_id[0]"></p></template>
                            </div>

                            <!-- Primary / Highest Qualification -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Highest Qualification *</label>
                                <select x-model="formData.highest_qualification_id" required
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                    <option value="">Select Qualification</option>
                                    @foreach($qualifications as $qualification)
                                        <option value="{{ $qualification->id }}">{{ $qualification->name }}</option>
                                    @endforeach
                                </select>
                                <template x-if="fieldErrors.highest_qualification_id"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.highest_qualification_id[0]"></p></template>
                            </div>

                            <!-- Additional Qualifications (Multi-Select) -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">
                                    Other / Additional Qualifications & Certifications
                                    <span class="text-text-dark/40 font-normal lowercase">(select all that apply or type below)</span>
                                </label>
                                
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 bg-secondary-bg/50 p-4 rounded-xl border border-card-border max-h-48 overflow-y-auto">
                                    @foreach($qualifications as $qual)
                                        <label class="flex items-center space-x-2 text-xs text-text-main cursor-pointer hover:text-accent-blue transition-colors">
                                            <input type="checkbox" value="{{ $qual->name }}" x-model="formData.other_qualifications"
                                                class="rounded border-card-border bg-secondary-bg text-accent-blue focus:ring-accent-blue">
                                            <span>{{ $qual->name }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <!-- Custom qualification type-in field -->
                                <div class="mt-3">
                                    <label class="block text-[11px] font-semibold text-text-main/60 mb-1">Other Qualification (If not listed above, type here):</label>
                                    <input type="text" x-model="formData.custom_qualification"
                                        placeholder="E.g., CTET Paper 2, P.G. Diploma, Ph.D, DCA, etc."
                                        class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2.5 text-xs text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                </div>
                            </div>

                            <!-- Experience -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Experience (Years) *</label>
                                <input type="number" x-model="formData.experience_years" min="0" required
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                <template x-if="fieldErrors.experience_years"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.experience_years[0]"></p></template>
                            </div>

                            <!-- State Preference -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Preferred State *</label>
                                <select x-model="formData.preferred_state_id" @change="fetchCities(formData.preferred_state_id)" required
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                                <template x-if="fieldErrors.preferred_state_id"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.preferred_state_id[0]"></p></template>
                            </div>
                            
                            <!-- City Preference -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Preferred City *</label>
                                <select x-model="formData.preferred_city_id" required
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                    <option value="">Select City</option>
                                    <template x-for="city in availableCities" :key="city.id">
                                        <option :value="city.id" x-text="city.name"></option>
                                    </template>
                                </select>
                                <template x-if="fieldErrors.preferred_city_id"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.preferred_city_id[0]"></p></template>
                            </div>

                            <!-- Current School (Required) -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Current School *</label>
                                <input type="text" x-model="formData.current_school" placeholder="E.g. DPS Patna (or write 'Fresher')" required
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                <template x-if="fieldErrors.current_school"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.current_school[0]"></p></template>
                            </div>

                            <!-- English Fluency -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">English Fluency *</label>
                                <select x-model="formData.english_fluency" required
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                    <option value="">Select Fluency</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="fluent">Fluent/Advanced</option>
                                </select>
                                <template x-if="fieldErrors.english_fluency"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.english_fluency[0]"></p></template>
                            </div>

                            <!-- Residential Preference (Required) -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">School Type Preference *</label>
                                <select x-model="formData.residential_preference" required
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                    <option value="">Select Preference</option>
                                    <option value="day">Day School</option>
                                    <option value="residential">Residential/Boarding School</option>
                                    <option value="both">Both</option>
                                </select>
                                <template x-if="fieldErrors.residential_preference"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.residential_preference[0]"></p></template>
                            </div>

                            <!-- Salaries (Required) -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Current Salary *</label>
                                <input type="text" x-model="formData.current_salary" required placeholder="E.g., ₹25,000/month"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                <template x-if="fieldErrors.current_salary"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.current_salary[0]"></p></template>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Expected Salary *</label>
                                <input type="text" x-model="formData.expected_salary" required placeholder="E.g., ₹35,000/month"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                <template x-if="fieldErrors.expected_salary"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.expected_salary[0]"></p></template>
                            </div>
                            
                            <!-- Availability (Required) -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Availability to Join *</label>
                                <input type="text" x-model="formData.availability_to_join" required placeholder="E.g., Immediate, 15 Days, 1 Month"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                <template x-if="fieldErrors.availability_to_join"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.availability_to_join[0]"></p></template>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-4">
                            <button type="button" @click="window.location.href = '{{ route('candidate.dashboard') }}'" class="text-text-dark font-semibold hover:text-accent-blue px-4 py-3 transition-colors">
                                Skip for now
                            </button>
                            <button type="submit" class="bg-accent-blue text-white px-8 py-3 rounded-xl font-semibold shadow-glow-blue hover:bg-accent-blue-hover transition-all hover:-translate-y-0.5 flex items-center gap-2">
                                Next Step <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- STEP 2: Agreement -->
                <div x-show="step === 2" x-transition.opacity.duration.500ms style="display: none;">
                    <h2 class="text-2xl font-bold text-text-main mb-6 flex items-center gap-3">
                        <i class="fas fa-file-contract text-accent-blue"></i> Agreement
                    </h2>

                    <!-- Terms Box -->
                    <div class="bg-secondary-bg border border-card-border rounded-xl p-6 mb-6 h-64 overflow-y-auto text-sm text-text-dark/80 custom-scrollbar">
                        <h4 class="font-bold text-text-main mb-4 text-center">Vedanta Placement Agency – Candidate Overview / Terms & Conditions</h4>
                        
                        <p class="mb-4">This document sets forth the official Terms & Conditions, policies, responsibilities, and professional expectations applicable to all candidates registering with Vedanta Placement Agency.</p>
                        <p class="mb-4">By registering with the Agency and proceeding further, the candidate acknowledges and enters into a legal and professional agreement governed by these Terms & Conditions.</p>
                        
                        <h5 class="font-bold text-text-main mb-2">Purpose of This Document</h5>
                        <p class="mb-4">The objective of this document is to ensure clarity, transparency, and mutual understanding between the candidate and Vedanta Placement Agency throughout the recruitment and placement process.</p>

                        <h5 class="font-bold text-text-main mb-2">TERMS & CONDITIONS (SUMMARY)</h5>
                        <ul class="list-disc pl-5 mb-4 space-y-2">
                            <li><strong>Registration:</strong> A non-refundable registration fee of ₹1,000 is payable. Registration remains valid for 3 job applications.</li>
                            <li><strong>Eligibility:</strong> Candidates must meet eligibility criteria as prescribed by the hiring institution. Documents: Submission of genuine and verifiable documents is mandatory. Any misrepresentation may result in cancellation without refund.</li>
                            <li><strong>Interviews & Demos:</strong> Attendance as scheduled is compulsory. Non-attendance may lead to removal from opportunities.</li>
                            <li><strong>Selection & Joining:</strong> Final selection rests solely with the hiring institution. Candidates must honor joining commitments once selected.</li>
                            <li><strong>Service Charges:</strong> The candidate agrees to pay the applicable service charge within 12 hours of receiving the first month's salary: Teaching Staff – 50% of one month's gross salary | Management/Non-Teaching Staff – 66.67% of one month's gross salary (20 days' salary).</li>
                            <li><strong>Refund Policy:</strong> Registration fees are strictly non-refundable under any circumstances.</li>
                            <li><strong>Payment Default:</strong> Delay or failure in payment may attract penalties, service suspension, or legal action.</li>
                            <li><strong>Job Commitment:</strong> A minimum service period of 90 working days is required unless otherwise agreed in writing.</li>
                            <li>These terms shall be deemed legally binding and enforceable, subject to the jurisdiction of Patna, Bihar.</li>
                        </ul>

                        <h5 class="font-bold text-text-main mb-2">PAYMENT, CONFIDENTIALITY & LEGAL COMPLIANCE</h5>
                        <ul class="list-disc pl-5 mb-4 space-y-2">
                            <li>The candidate agrees to remit the applicable service charge within twelve (12) hours of receipt of the first salary.</li>
                            <li>Failure to make payment within the stipulated period shall attract a late penalty of ₹300 per day until the outstanding amount is cleared in full.</li>
                            <li>Non-payment beyond seven (7) days shall be treated as a material breach of contract under the Indian Contract Act, 1872, and may result in recovery proceedings, blacklisting, and suspension or termination of all placement services.</li>
                            <li>The candidate shall maintain strict confidentiality and shall not misuse, disclose, or share any employer, school, or Agency information. Any such violation may attract action under applicable laws, including the Information Technology Act, 2000, wherever applicable.</li>
                            <li>These terms shall be deemed legally binding and enforceable, subject to the exclusive jurisdiction of Patna, Bihar.</li>
                        </ul>

                        <h5 class="font-bold text-text-main mb-2">Candidates must:</h5>
                        <ul class="list-disc pl-5 mb-4 space-y-2">
                            <li>Follow the school’s internal guidelines and rules be punctual and cooperative and maintain decorum and professionalism at all times.</li>
                            <li>Candidates must not share or misuse School contact information, Job leads and agency reference letters or documents.</li>
                            <li>Approaching a school directly or through any third party after receiving the lead from the Agency will result in Immediate blacklisting and legal action under data breach or professional misconduct.</li>
                        </ul>

                        <h5 class="font-bold text-text-main mb-2">Registration fee is strictly non-refundable under any condition:</h5>
                        <ul class="list-disc pl-5 mb-4 space-y-2">
                            <li>Rejection by school.</li>
                            <li>Voluntary withdrawal by candidate.</li>
                            <li>Change of mind.</li>
                            <li>The service charge is also non-refundable once the candidate has received their salary and the due period for payment has begun.</li>
                            <li>Refunds will not be entertained for dissatisfaction with salary, location, or working conditions post joining.</li>
                        </ul>

                        <h5 class="font-bold text-text-main mb-2">BEHAVIORAL CODE OF CONDUCT</h5>
                        <p class="mb-2"><strong>Candidates must always:</strong></p>
                        <ul class="list-disc pl-5 mb-4 space-y-2">
                            <li>Be respectful and honest in communication.</li>
                            <li>Maintain professional appearance and behavior.</li>
                            <li>Refrain from abusive language or harassment.</li>
                            <li>Avoid any disputes with the employer during tenure.</li>
                            <li>Complaints from employers regarding attitude, communication, or ethics will be taken seriously and may result in blacklisting.</li>
                        </ul>

                        <h5 class="font-bold text-text-main mb-2">COMMUNICATION GUIDELINES</h5>
                        <p class="mb-2">All communication from the Agency will be done via: WhatsApp (only through registered numbers), Email (vedantaplacementagency@gmail.com), Direct phone calls.</p>
                        <p class="mb-2"><strong>Candidates must:</strong></p>
                        <ul class="list-disc pl-5 mb-4 space-y-2">
                            <li>Respond within 24–48 hours to all official communications</li>
                            <li>Keep their registered mobile number and email active</li>
                            <li>Inform the Agency of any number/email changes.</li>
                            <li>Failure to communicate may result in cancellation of interview or job opportunity.</li>
                        </ul>
                        
                        <p class="mt-6 font-semibold">By clicking Accept & Continue, I acknowledge and accept all these terms and conditions.</p>
                    </div>

                    <div class="mb-6 flex items-center gap-3 bg-accent-blue/5 p-4 rounded-xl border border-accent-blue/20 cursor-pointer" @click="agreed = !agreed">
                        <div class="w-6 h-6 rounded-md border-2 border-accent-blue flex items-center justify-center transition-colors"
                            :class="agreed ? 'bg-accent-blue text-white' : 'bg-transparent text-transparent'">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-sm font-semibold text-text-main select-none">I have read and agree to the Terms & Conditions of Vedanta Placement Agency.</span>
                    </div>

                    <!-- Signature Options moved to step 3 -->

                    <div class="mt-8 flex justify-between">
                        <button type="button" @click="step = 1" class="px-6 py-3 rounded-xl font-semibold text-text-dark hover:bg-card-border transition-colors flex items-center gap-2">
                            <i class="fas fa-arrow-left text-sm"></i> Back
                        </button>
                        <button type="button" @click="submitStep2" class="bg-accent-blue text-white px-8 py-3 rounded-xl font-semibold shadow-glow-blue hover:bg-accent-blue-hover transition-all hover:-translate-y-0.5 flex items-center gap-2" :disabled="!agreed" :class="!agreed ? 'opacity-50 cursor-not-allowed' : ''">
                            Accept & Continue <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 3: Identity Verification -->
                <div x-show="step === 3" x-transition.opacity.duration.500ms style="display: none;" x-init="$watch('step', value => { if(value === 3 && !latitude) getLocation(); })">
                    <h2 class="text-2xl font-bold text-text-main mb-6 flex items-center gap-3">
                        <i class="fas fa-user-check text-accent-blue"></i> Identity Verification
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Live Photo Section -->
                        <div class="border border-card-border rounded-2xl p-6 bg-secondary-bg relative">
                            <h3 class="font-bold text-text-main mb-4 flex items-center gap-2"><i class="fas fa-camera text-accent-blue"></i> Live Photo</h3>
                            
                            <div x-show="!livePhotoBase64" class="w-full aspect-video bg-card-bg rounded-xl overflow-hidden relative border border-card-border">
                                <video id="cameraFeed" class="w-full h-full object-cover" autoplay playsinline muted></video>
                                <div class="absolute inset-0 flex flex-col items-center justify-center bg-card-bg/80 gap-3" x-show="!isCameraOn">
                                    <button @click="startCamera" class="bg-accent-blue text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-accent-blue-hover transition-colors shadow-lg"><i class="fas fa-camera"></i> Start Camera</button>
                                    <!-- <span class="text-xs text-text-dark/50 font-medium">OR</span>
                                    <label class="bg-secondary-bg text-text-main border border-card-border px-4 py-2 rounded-lg text-sm font-semibold hover:bg-card-border transition-colors cursor-pointer shadow-sm">
                                        <i class="fas fa-upload"></i> Upload Photo
                                        <input type="file" class="hidden" accept="image/*" @change="handleLivePhotoUpload">
                                    </label> -->
                                </div>
                            </div>

                            <div x-show="livePhotoBase64" class="w-full aspect-video bg-card-bg rounded-xl overflow-hidden border border-card-border relative">
                                <img :src="livePhotoBase64" class="w-full h-full object-cover" />
                                <button @click="livePhotoBase64 = null; startCamera()" class="absolute top-2 right-2 bg-red-500 text-white w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-600 transition-colors"><i class="fas fa-redo"></i></button>
                            </div>

                            <div class="mt-4 flex justify-center" x-show="isCameraOn && !livePhotoBase64">
                                <button @click="takePhoto" class="bg-green-500 text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-green-600 transition-colors shadow-lg flex items-center gap-2"><i class="fas fa-camera"></i> Capture Photo</button>
                            </div>
                            <p x-show="cameraError" class="text-red-500 text-xs mt-2 text-center" x-text="cameraError"></p>
                        </div>

                        <!-- Location & Signature Section -->
                        <div class="flex flex-col gap-6">
                            <!-- Location -->
                            <div class="border border-card-border rounded-2xl p-6 bg-secondary-bg">
                                <h3 class="font-bold text-text-main mb-4 flex items-center gap-2"><i class="fas fa-map-marker-alt text-accent-blue"></i> Location</h3>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center" :class="latitude ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500'">
                                        <i class="fas" :class="latitude ? 'fa-check' : 'fa-times'"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-text-main" x-text="latitude ? 'Location Captured' : 'Location Required'"></p>
                                        <p class="text-xs text-text-dark/60" x-text="latitude ? latitude.toFixed(4) + ', ' + longitude.toFixed(4) : 'Please allow location access'"></p>
                                    </div>
                                    <button x-show="!latitude" @click="getLocation" class="ml-auto bg-card-bg border border-card-border text-xs px-3 py-1.5 rounded-lg font-semibold hover:bg-card-border transition-colors">Retry</button>
                                </div>
                                <p x-show="locationError" class="text-red-500 text-xs mt-2" x-text="locationError"></p>
                            </div>

                            <!-- Signature Options -->
                            <div class="border border-card-border rounded-2xl overflow-hidden flex-1 flex flex-col">
                                <div class="flex border-b border-card-border bg-secondary-bg">
                                    <button type="button" @click="sigType = 'draw'; initSignaturePad()" class="flex-1 py-3 text-sm font-semibold transition-colors" :class="sigType === 'draw' ? 'text-accent-blue bg-card-bg border-b-2 border-accent-blue' : 'text-text-dark/60 hover:bg-card-bg/50'">Draw</button>
                                    <button type="button" @click="sigType = 'type'" class="flex-1 py-3 text-sm font-semibold transition-colors" :class="sigType === 'type' ? 'text-accent-blue bg-card-bg border-b-2 border-accent-blue' : 'text-text-dark/60 hover:bg-card-bg/50'">Type</button>
                                    <button type="button" @click="sigType = 'upload'" class="flex-1 py-3 text-sm font-semibold transition-colors" :class="sigType === 'upload' ? 'text-accent-blue bg-card-bg border-b-2 border-accent-blue' : 'text-text-dark/60 hover:bg-card-bg/50'">Upload</button>
                                </div>

                                <div class="p-4 bg-card-bg flex-1">
                                    <!-- Draw Pad -->
                                    <div x-show="sigType === 'draw'" class="h-full flex flex-col">
                                        <div class="border-2 border-dashed border-card-border rounded-xl bg-white relative flex-1">
                                            <canvas id="signature-pad" class="w-full h-full min-h-[120px] rounded-xl cursor-crosshair touch-none"></canvas>
                                            <button type="button" @click="clearSignature" class="absolute top-2 right-2 w-8 h-8 rounded-lg bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500/20 transition-colors" title="Clear">
                                                <i class="fas fa-eraser"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Type Name -->
                                    <div x-show="sigType === 'type'" style="display: none;" class="h-full flex items-center">
                                        <input type="text" x-model="typedSignature" placeholder="Type full name"
                                            class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-4 text-xl text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                                            style="font-family: 'Playfair Display', cursive; font-style: italic;">
                                    </div>

                                    <!-- Upload Image -->
                                    <div x-show="sigType === 'upload'" style="display: none;" class="h-full">
                                        <label class="w-full h-full min-h-[120px] flex flex-col items-center justify-center border-2 border-dashed border-card-border rounded-xl bg-secondary-bg hover:bg-card-border/30 transition-colors cursor-pointer relative overflow-hidden">
                                            <div class="flex flex-col items-center justify-center py-4" x-show="!uploadedImagePreview">
                                                <i class="fas fa-upload text-2xl text-accent-blue mb-2"></i>
                                                <p class="text-xs text-text-dark/60">Click to upload signature</p>
                                            </div>
                                            <img x-show="uploadedImagePreview" :src="uploadedImagePreview" class="absolute inset-0 w-full h-full object-contain bg-white p-2" />
                                            <input type="file" class="hidden" accept="image/png, image/jpeg, image/jpg" @change="handleFileUpload" x-ref="sigFileInput" />
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <button type="button" @click="step = 2" class="px-6 py-3 rounded-xl font-semibold text-text-dark hover:bg-card-border transition-colors flex items-center gap-2">
                            <i class="fas fa-arrow-left text-sm"></i> Back
                        </button>
                        <button type="button" @click="submitStep3" class="bg-accent-blue text-white px-8 py-3 rounded-xl font-semibold shadow-glow-blue hover:bg-accent-blue-hover transition-all hover:-translate-y-0.5 flex items-center gap-2">
                            Verify Identity <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 4: Plan & Payment (High-Converting 3-Step Flow) -->
                <div x-show="step === 4" x-transition.opacity.duration.500ms style="display: none;" class="max-w-xl mx-auto">
                    
                    <!-- ===================================================== -->
                    <!-- SCREEN 1: UPSELL / RECOMMENDED FOR YOU (flowStep === 1) -->
                    <!-- ===================================================== -->
                    <div x-show="flowStep === 1" 
                         x-transition:enter="transition ease-out duration-250"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                        
                        <!-- Recommended Pill -->
                        <div class="text-center pt-2">
                            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-purple-500/20 border border-purple-500/35 text-purple-300 text-xs font-black tracking-wider uppercase shadow-inner">
                                <i class="fas fa-crown text-[11px] text-amber-400"></i>
                                <span>RECOMMENDED FOR YOU</span>
                            </span>
                        </div>

                        <!-- Title & Subtitle -->
                        <div class="text-center mt-3.5 mb-5">
                            <h3 class="text-2xl sm:text-3xl font-black text-text-main tracking-tight leading-snug">
                                Get Faster Results with <br>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-purple-300 to-indigo-300">Premium Registration</span>
                            </h3>
                            <p class="text-xs sm:text-sm text-text-dark/70 mt-1.5 max-w-md mx-auto leading-relaxed">
                                Unlock priority processing and get shortlisted faster for the best teaching opportunities.
                            </p>
                        </div>

                        <!-- Featured Premium Card (₹1,000) -->
                        <div class="rounded-3xl border-2 border-purple-500/50 bg-gradient-to-b from-purple-900/30 via-white/[0.04] to-purple-950/20 p-5 sm:p-6 relative shadow-[0_0_40px_rgba(168,85,247,0.22)]">
                            
                            <!-- Header inside card -->
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-2xl bg-purple-500/25 border border-purple-500/35 text-amber-400 flex items-center justify-center text-2xl shadow-md shrink-0">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <div>
                                        <div class="font-black text-text-main text-lg sm:text-xl leading-tight">Premium Registration</div>
                                        <div class="text-xs text-text-dark/60 font-medium mt-0.5">Priority processing starts within 24 hours</div>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full bg-purple-600 border border-purple-400/40 text-white text-[10px] font-black uppercase tracking-wider shrink-0 shadow-md">
                                    Most Preferred
                                </span>
                            </div>

                            <!-- Price & Best Value Badge -->
                            <div class="flex items-center justify-between my-4 px-1">
                                <div class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                                    ₹1,000
                                </div>
                                <div class="px-3.5 py-1 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg flex items-center gap-1.5 border border-amber-300">
                                    <i class="fas fa-award text-slate-950"></i>
                                    <span>BEST VALUE</span>
                                </div>
                            </div>

                            <!-- Features List -->
                            <ul class="space-y-2.5 text-xs sm:text-sm text-text-main/90 my-5 font-medium">
                                <li class="flex items-center gap-2.5">
                                    <span class="text-amber-400 font-bold text-sm leading-none shrink-0">⚡</span>
                                    <span>Same-day profile verification</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="text-purple-400 text-xs shrink-0"><i class="fas fa-rocket"></i></span>
                                    <span>Priority processing &amp; faster shortlisting</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="text-purple-400 text-xs shrink-0"><i class="fas fa-file-alt"></i></span>
                                    <span>Process initiation on priority</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="text-purple-400 text-xs shrink-0"><i class="fas fa-users"></i></span>
                                    <span>Valid for up to 3 job applications/interviews</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="text-amber-400 text-xs shrink-0"><i class="fas fa-star"></i></span>
                                    <span>Priority consideration for better opportunities</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="text-purple-400 text-xs shrink-0"><i class="fas fa-shield-alt"></i></span>
                                    <span>No additional registration payment after final selection</span>
                                </li>
                            </ul>

                            <!-- Primary CTA Button -->
                            <button type="button" 
                                    @click="selectAndConfirm('premium')"
                                    class="w-full py-4 px-5 rounded-2xl bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-600 hover:from-purple-500 hover:to-indigo-500 text-white font-black text-sm sm:text-base flex items-center justify-center gap-2 shadow-[0_4px_25px_rgba(147,51,234,0.45)] hover:shadow-[0_6px_30px_rgba(147,51,234,0.6)] transition-all hover:scale-[1.01] active:scale-[0.99] cursor-pointer mt-2">
                                <span>Continue with ₹1,000 Premium</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </button>
                        </div>

                        <!-- Separator -->
                        <div class="relative flex py-4 items-center">
                            <div class="flex-grow border-t border-card-border"></div>
                            <span class="flex-shrink mx-4 text-text-dark/40 text-xs font-bold uppercase tracking-wider">or</span>
                            <div class="flex-grow border-t border-card-border"></div>
                        </div>

                        <!-- Standard Registration Card (₹500 - Triggers Downsell Screen 2) -->
                        <div @click="selectStandardAndDownsell()" 
                             class="rounded-2xl border border-card-border hover:border-purple-500/40 bg-secondary-bg hover:bg-card-border/20 p-4 flex items-center justify-between transition-all cursor-pointer group shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-card-bg border border-card-border text-text-dark/70 flex items-center justify-center text-sm group-hover:border-purple-500/40 group-hover:text-purple-400 transition-colors shrink-0">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-text-main text-sm sm:text-base group-hover:text-purple-400 transition-colors">
                                        Standard Registration
                                    </div>
                                    <div class="text-xs text-text-dark/50 mt-0.5">
                                        Process starts within 24 hours
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 font-black text-text-main text-base sm:text-lg group-hover:text-purple-400 transition-colors">
                                <span>₹500</span>
                                <i class="fas fa-chevron-right text-xs text-text-dark/40 group-hover:text-purple-400 group-hover:translate-x-0.5 transition-all"></i>
                            </div>
                        </div>

                        <!-- Info Note -->
                        <div class="mt-4 bg-secondary-bg border border-card-border rounded-xl p-3.5 text-xs text-text-dark/70 flex items-start gap-2.5">
                            <i class="fas fa-info-circle text-sky-400 text-xs mt-0.5 shrink-0"></i>
                            <span>Standard plan is suitable if you are not in a hurry. For better opportunities and faster processing, we recommend Premium Registration.</span>
                        </div>

                        <!-- Trust Bar -->
                        <div class="mt-3.5 text-center text-xs font-semibold text-text-dark/50 flex items-center justify-center gap-2">
                            <i class="fas fa-shield-alt text-purple-400 text-xs"></i>
                            <span>Your information is safe and secure with us</span>
                        </div>

                        <!-- Back Button to Verification -->
                        <div class="mt-6 pt-4 border-t border-card-border flex justify-start">
                            <button type="button" @click="step = 3" class="px-5 py-2.5 rounded-xl font-semibold text-text-dark/60 hover:text-text-main hover:bg-card-border/30 transition-colors flex items-center gap-2 text-xs cursor-pointer">
                                <i class="fas fa-arrow-left text-xs"></i> Back to Verification
                            </button>
                        </div>
                    </div>


                    <!-- ======================================================= -->
                    <!-- SCREEN 2: DOWNSELL / COMPARISON (flowStep === 2)        -->
                    <!-- ======================================================= -->
                    <div x-show="flowStep === 2" 
                         x-transition:enter="transition ease-out duration-250"
                         x-transition:enter-start="opacity-0 translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0">
                        
                        <!-- Warning Circle Icon -->
                        <div class="text-center pt-2">
                            <div class="w-12 h-12 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center text-xl font-black mx-auto shadow-md">
                                <i class="fas fa-exclamation"></i>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-black text-text-main tracking-tight mt-3">
                                Wait! Before You Continue
                            </h3>
                            <p class="text-xs sm:text-sm text-text-dark/70 mt-1">
                                You have selected the Standard Registration for <strong class="text-text-main font-bold">₹500</strong>.
                            </p>
                        </div>

                        <!-- Upgrade Offer Banner -->
                        <div class="mt-4 rounded-2xl bg-gradient-to-r from-purple-500/15 to-indigo-500/15 border border-purple-500/30 p-3.5 flex items-center gap-3 shadow-inner">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/25 border border-purple-500/35 text-purple-300 flex items-center justify-center text-lg shrink-0 shadow-sm">
                                <i class="fas fa-gift"></i>
                            </div>
                            <div class="text-xs sm:text-sm font-semibold text-purple-200 leading-snug">
                                For only ₹500 more, upgrade to Premium and get many extra benefits with priority processing.
                            </div>
                        </div>

                        <!-- 2-Column Comparison Table -->
                        <div class="grid grid-cols-2 gap-3 mt-4 items-stretch text-xs">
                            <!-- Standard Column -->
                            <div class="rounded-2xl border border-card-border bg-secondary-bg p-3.5 flex flex-col justify-between">
                                <div>
                                    <div class="text-center pb-2.5 border-b border-card-border">
                                        <div class="text-[11px] font-bold text-text-dark/60 uppercase tracking-wide">Standard</div>
                                        <div class="text-lg font-black text-text-main mt-0.5">₹500</div>
                                    </div>
                                    <ul class="space-y-2 mt-3 text-text-dark/80 font-medium">
                                        <li class="flex items-center gap-2">
                                            <i class="fas fa-user text-text-dark/40 text-[10px] w-3.5 text-center"></i>
                                            <span>Up to 2 Applications</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <i class="fas fa-rocket text-text-dark/40 text-[10px] w-3.5 text-center"></i>
                                            <span>Standard Processing</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <i class="fas fa-envelope text-text-dark/40 text-[10px] w-3.5 text-center"></i>
                                            <span>Email Support</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-text-dark/40">
                                            <i class="fas fa-times-circle text-rose-400 text-[10px] w-3.5 text-center"></i>
                                            <span>WhatsApp Assistance</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-text-dark/40">
                                            <i class="fas fa-times-circle text-rose-400 text-[10px] w-3.5 text-center"></i>
                                            <span>Call Support</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-text-dark/40">
                                            <i class="fas fa-times-circle text-rose-400 text-[10px] w-3.5 text-center"></i>
                                            <span>Early Access to New Jobs</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-text-dark/40">
                                            <i class="fas fa-times-circle text-rose-400 text-[10px] w-3.5 text-center"></i>
                                            <span>Relationship Manager</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <i class="fas fa-id-badge text-text-dark/40 text-[10px] w-3.5 text-center"></i>
                                            <span>Standard Visibility</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <i class="fas fa-bell text-text-dark/40 text-[10px] w-3.5 text-center"></i>
                                            <span>Regular Process Updates</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Premium Column (Recommended) -->
                            <div class="rounded-2xl border-2 border-purple-500/60 bg-purple-900/20 p-3.5 flex flex-col justify-between relative shadow-[0_0_25px_rgba(168,85,247,0.18)]">
                                <div class="absolute -top-2.5 right-3 px-2.5 py-0.5 rounded-full bg-purple-600 text-white text-[9px] font-black uppercase tracking-wider shadow-md">
                                    Recommended
                                </div>
                                <div>
                                    <div class="text-center pb-2.5 border-b border-purple-500/20">
                                        <div class="flex items-center justify-center gap-1.5 text-[11px] font-black text-purple-300 uppercase tracking-wide">
                                            <i class="fas fa-crown text-[10px] text-amber-400"></i> Premium
                                        </div>
                                        <div class="text-lg font-black text-white mt-0.5">₹1,000</div>
                                    </div>
                                    <ul class="space-y-2 mt-3 text-text-main font-semibold">
                                        <li class="flex items-center gap-2 text-white">
                                            <i class="fas fa-check-circle text-emerald-400 text-[10px] w-3.5 text-center"></i>
                                            <span>Up to 3 Applications</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-white">
                                            <i class="fas fa-check-circle text-emerald-400 text-[10px] w-3.5 text-center"></i>
                                            <span>Priority Processing</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-white">
                                            <i class="fas fa-check-circle text-emerald-400 text-[10px] w-3.5 text-center"></i>
                                            <span>Email + WhatsApp Support</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-white">
                                            <i class="fas fa-check-circle text-emerald-400 text-[10px] w-3.5 text-center"></i>
                                            <span>WhatsApp Assistance</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-white">
                                            <i class="fas fa-check-circle text-emerald-400 text-[10px] w-3.5 text-center"></i>
                                            <span>Priority Call Assistance</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-white">
                                            <i class="fas fa-check-circle text-emerald-400 text-[10px] w-3.5 text-center"></i>
                                            <span>Early Access to New Jobs</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-white">
                                            <i class="fas fa-check-circle text-emerald-400 text-[10px] w-3.5 text-center"></i>
                                            <span>Dedicated Relationship Manager</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-amber-300">
                                            <i class="fas fa-medal text-amber-400 text-[10px] w-3.5 text-center"></i>
                                            <span>Priority Profile Highlight</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-amber-300">
                                            <i class="fas fa-bell text-amber-400 text-[10px] w-3.5 text-center"></i>
                                            <span>Priority Process Updates</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Upgrade Button -->
                        <button type="button" 
                                @click="selectAndConfirm('premium')"
                                class="w-full mt-4 py-3.5 px-5 rounded-2xl bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-600 hover:from-purple-500 hover:to-indigo-500 text-white font-black text-sm sm:text-base flex items-center justify-center gap-2 shadow-[0_4px_25px_rgba(147,51,234,0.45)] transition-all hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                            <span>Upgrade to Premium ₹1,000</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </button>

                        <!-- Continue with Standard Link -->
                        <button type="button" 
                                @click="selectAndConfirm('standard')"
                                class="text-xs font-bold text-text-dark/60 hover:text-text-main underline decoration-text-dark/40 hover:decoration-text-main transition-colors text-center block w-full mt-2.5 py-1.5 cursor-pointer">
                            Continue with Standard ₹500
                        </button>

                        <!-- Testimonial Quote Box -->
                        <div class="mt-4 rounded-xl bg-secondary-bg border border-card-border p-3 text-left">
                            <div class="flex items-start gap-2.5">
                                <span class="text-amber-400 font-serif text-3xl leading-none">&ldquo;</span>
                                <div>
                                    <p class="text-xs italic text-text-main/80 font-medium">
                                        "Premium registration helped me get interview calls faster. Highly recommended!"
                                    </p>
                                    <span class="text-[10px] font-bold text-text-dark/50 mt-1 block">&mdash; Placed Teacher</span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- ==================================================== -->
                    <!-- SCREEN 3: CONFIRMATION & SECURE PAYMENT (flowStep === 3) -->
                    <!-- ==================================================== -->
                    <div x-show="flowStep === 3" 
                         x-transition:enter="transition ease-out duration-250"
                         x-transition:enter-start="opacity-0 translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0">
                        
                        <!-- Header Icon -->
                        <div class="text-center pt-2">
                            <div class="w-14 h-14 rounded-2xl bg-purple-500/20 border border-purple-500/30 text-purple-300 flex items-center justify-center text-2xl mx-auto shadow-lg mb-2">
                                <i :class="selectedPlan === 'standard' ? 'fas fa-user' : 'fas fa-crown text-amber-400'"></i>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-black text-text-main tracking-tight" x-text="selectedTitle">
                                Premium Registration
                            </h3>
                            <p class="text-xs sm:text-sm text-text-dark/60 mt-0.5">Confirm your payment details</p>
                        </div>

                        <!-- Amount Payable Box -->
                        <div class="mt-4 rounded-2xl bg-secondary-bg border border-card-border p-4 flex items-center justify-between shadow-inner">
                            <div class="text-xs sm:text-sm font-semibold text-text-dark/70">Amount Payable</div>
                            <div class="text-2xl sm:text-3xl font-black text-purple-400" x-text="'₹' + selectedAmount.toLocaleString()">
                                ₹1,000
                            </div>
                        </div>

                        <!-- Details Breakdown Table -->
                        <div class="mt-3 rounded-2xl border border-card-border bg-secondary-bg divide-y divide-card-border text-xs sm:text-sm overflow-hidden font-medium">
                            <div class="flex items-center justify-between py-2.5 px-4">
                                <div class="flex items-center gap-2 text-text-dark/70">
                                    <i class="fas fa-calendar-alt text-text-dark/40 w-4 text-center"></i>
                                    <span>Validity</span>
                                </div>
                                <div class="font-bold text-text-main" x-text="selectedPlan === 'standard' ? 'Up to 2 applications' : 'Up to 3 applications'">
                                    Up to 3 applications
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-2.5 px-4">
                                <div class="flex items-center gap-2 text-text-dark/70">
                                    <i class="fas fa-bolt text-amber-400 w-4 text-center"></i>
                                    <span>Profile Verification</span>
                                </div>
                                <div class="font-bold text-text-main" x-text="selectedPlan === 'standard' ? 'Within 24-48 hours' : 'Same-day'">
                                    Same-day
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-2.5 px-4">
                                <div class="flex items-center gap-2 text-text-dark/70">
                                    <i class="fas fa-rocket text-purple-400 w-4 text-center"></i>
                                    <span>Processing</span>
                                </div>
                                <div class="font-bold text-text-main" x-text="selectedPlan === 'standard' ? 'Standard' : 'Priority'">
                                    Priority
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-2.5 px-4">
                                <div class="flex items-center gap-2 text-text-dark/70">
                                    <i class="fas fa-star text-amber-400 w-4 text-center"></i>
                                    <span>Job Opportunities</span>
                                </div>
                                <div class="font-bold text-text-main" x-text="selectedPlan === 'standard' ? 'Standard consideration' : 'Priority consideration'">
                                    Priority consideration
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-2.5 px-4">
                                <div class="flex items-center gap-2 text-text-dark/70">
                                    <i class="fas fa-file-invoice text-text-dark/40 w-4 text-center"></i>
                                    <span>Additional Payment</span>
                                </div>
                                <div class="font-bold text-text-main">
                                    Not required after selection
                                </div>
                            </div>
                        </div>

                        <!-- Encouragement Box -->
                        <div class="mt-3.5 rounded-xl bg-purple-500/15 border border-purple-500/30 p-3 flex items-center gap-2.5 text-xs text-purple-200">
                            <div class="w-7 h-7 rounded-lg bg-purple-500 text-white flex items-center justify-center text-xs shrink-0">
                                <i class="fas fa-gift"></i>
                            </div>
                            <span x-text="selectedPlan === 'standard' ? 'You are registering with Standard access.' : 'You are choosing the best option for a faster and smoother hiring experience!'"></span>
                        </div>

                        <!-- Live Payment Gateway Button (Calls submitPayment) -->
                        <div class="mt-4">
                            <button type="button" 
                                    @click="submitPayment"
                                    class="w-full py-4 px-5 rounded-2xl bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-600 hover:from-purple-500 hover:to-indigo-500 text-white font-black text-sm sm:text-base flex items-center justify-center gap-2 shadow-[0_4px_25px_rgba(147,51,234,0.45)] hover:shadow-[0_6px_30px_rgba(147,51,234,0.6)] transition-all hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                                <i class="fas fa-lock text-sm"></i>
                                <span>Pay ₹<span x-text="selectedAmount.toLocaleString()"></span> Securely</span>
                            </button>
                        </div>

                        <!-- Back to Screen 1 Link -->
                        <div class="text-center mt-3">
                            <button type="button" @click="flowStep = 1" class="text-xs font-semibold text-text-dark/60 hover:text-text-main transition-colors cursor-pointer">
                                &larr; Change Plan
                            </button>
                        </div>

                        <!-- Terms Note -->
                        <p class="text-[11px] text-text-dark/50 text-center mt-3">
                            By proceeding, you agree to our <a href="{{ route('terms') }}" target="_blank" class="underline hover:text-text-main">Terms &amp; Conditions</a> and <a href="{{ route('refund') }}" target="_blank" class="underline hover:text-text-main">Refund Policy</a>.
                        </p>

                        <!-- Trust Badges Row -->
                        <div class="mt-4 pt-3.5 border-t border-card-border grid grid-cols-3 gap-2 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-shield-alt text-emerald-400 text-sm mb-1"></i>
                                <span class="text-[10px] text-text-dark/70 font-semibold">Secure Payment</span>
                                <span class="text-[9px] text-text-dark/40">(SSL Encrypted)</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <i class="fas fa-credit-card text-purple-400 text-sm mb-1"></i>
                                <span class="text-[10px] text-text-dark/70 font-semibold">Multiple Options</span>
                                <span class="text-[9px] text-text-dark/40">UPI, Cards, NetBanking</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <i class="fas fa-user-check text-sky-400 text-sm mb-1"></i>
                                <span class="text-[10px] text-text-dark/70 font-semibold">Trusted Partner</span>
                                <span class="text-[9px] text-text-dark/40">10,000+ Educators</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('registrationWizard', () => ({
            step: 1,
            steps: ['Profile', 'Agreement', 'Verification', 'Payment'],
            loading: false,
            loadingMessage: '',
            error: '',
            fieldErrors: {},
            
            // Profile Data
            formData: {
                date_of_birth: '{{ $profile->date_of_birth ? $profile->date_of_birth->format("Y-m-d") : "" }}',
                gender: '{{ $profile->gender }}',
                category_id: '{{ $profile->category_id }}',
                subject_id: '{{ $profile->subject_id }}',
                specialization_id: '{{ $profile->specialization_id }}',
                highest_qualification_id: '{{ $profile->highest_qualification_id }}',
                preferred_state_id: '{{ $profile->preferred_state_id }}',
                preferred_city_id: '{{ $profile->preferred_city_id }}',
                experience_years: '{{ $profile->experience_years }}',
                current_salary: '{{ $profile->current_salary }}',
                expected_salary: '{{ $profile->expected_salary }}',
                address: {!! json_encode($profile->address ?? '') !!},
                marital_status: '{{ $profile->marital_status }}',
                religion: '{{ $profile->religion }}',
                english_fluency: '{{ $profile->english_fluency }}',
                residential_preference: '{{ $profile->residential_preference }}',
                availability_to_join: '{{ $profile->availability_to_join }}',
                @php
                    $dbQualNames = $qualifications->pluck('name')->toArray();
                    $allUserQuals = array_filter(array_map('trim', explode(',', $profile->other_qualifications ?? '')));
                    $checkedQuals = array_values(array_intersect($allUserQuals, $dbQualNames));
                    $customQuals = array_values(array_diff($allUserQuals, $dbQualNames));
                    $customQualString = implode(', ', $customQuals);
                @endphp
                other_qualifications: {!! json_encode($checkedQuals) !!},
                custom_qualification: {!! json_encode($customQualString) !!}
            },

            availableSubjects: {!! json_encode($subjects) !!},
            availableSpecializations: [],
            availableCities: [],

            profilePhotoFile: null,
            profilePhotoPreview: null,

            resumeFile: null,
            salarySlipFile: null,
            offerLetterFile: null,
            
            // Signature & Identity Data
            agreed: false,
            sigType: 'draw',
            signaturePad: null,
            typedSignature: '',
            uploadedImagePreview: null,
            uploadedFile: null,
            
            livePhotoBase64: null,
            latitude: null,
            longitude: null,
            locationError: '',
            cameraError: '',
            stream: null,
            isCameraOn: false,

            // Payment Data & High-Conversion 3-Step Flow
            flowStep: 1, // 1: Recommended/Upsell, 2: Downsell/Comparison, 3: Confirmation
            selectedPlan: 'premium',
            selectedAmount: 1000,
            selectedTitle: 'Premium Registration',

            choosePlan(type) {
                if (type === 'standard' || type === 'basic') {
                    this.selectedPlan = 'standard';
                    this.selectedAmount = 500;
                    this.selectedTitle = 'Standard Registration';
                } else {
                    this.selectedPlan = 'premium';
                    this.selectedAmount = 1000;
                    this.selectedTitle = 'Premium Registration';
                }
            },
            selectAndConfirm(type) {
                this.choosePlan(type);
                this.flowStep = 3;
            },
            selectStandardAndDownsell() {
                this.choosePlan('standard');
                this.flowStep = 2;
            },

            fetchCities(stateId) {
                if(stateId) {
                    fetch(`/api/states/${stateId}/cities`)
                        .then(response => response.json())
                        .then(data => {
                            this.availableCities = data;
                            if(!data.find(c => c.id == this.formData.preferred_city_id)) {
                                this.formData.preferred_city_id = '';
                            }
                        })
                        .catch(error => console.error('Error fetching cities:', error));
                } else {
                    this.availableCities = [];
                    this.formData.preferred_city_id = '';
                }
            },

            init() {
                // Determine initial step based on profile status
                const isProfileComplete = {{ $profile->is_profile_complete ? 'true' : 'false' }};
                const isTermsAgreed = {{ $profile->is_terms_agreed ? 'true' : 'false' }};
                const isAgreementSigned = {{ $profile->is_agreement_signed ? 'true' : 'false' }};
                
                if (isProfileComplete && !isTermsAgreed) this.step = 2;
                if (isProfileComplete && isTermsAgreed && !isAgreementSigned) this.step = 3;
                if (isProfileComplete && isTermsAgreed && isAgreementSigned) this.step = 4;

                this.$watch('step', value => {
                    if (value === 3 && this.sigType === 'draw') {
                        setTimeout(() => this.initSignaturePad(), 300);
                    }
                });

                this.$watch('sigType', value => {
                    if (value === 'draw' && this.step === 3) {
                        setTimeout(() => this.initSignaturePad(), 300);
                    }
                });

                this.$watch('formData.category_id', value => {
                    if (value) {
                        fetch(`/api/categories/${value}/subjects`)
                            .then(response => response.json())
                            .then(data => {
                                this.availableSubjects = data;
                                // Reset subject if current subject is not in new list
                                if(!data.find(s => s.id == this.formData.subject_id)) {
                                    this.formData.subject_id = '';
                                }
                            })
                            .catch(error => console.error('Error fetching subjects:', error));
                    } else {
                        this.availableSubjects = [];
                        this.formData.subject_id = '';
                    }
                });

                this.$watch('formData.subject_id', value => {
                    if (value) {
                        fetch(`/api/subjects/${value}/specializations`)
                            .then(response => response.json())
                            .then(data => {
                                this.availableSpecializations = data;
                                if(!data.find(s => s.id == this.formData.specialization_id)) {
                                    this.formData.specialization_id = '';
                                }
                            })
                            .catch(error => console.error('Error fetching specializations:', error));
                    } else {
                        this.availableSpecializations = [];
                        this.formData.specialization_id = '';
                    }
                });
                
                // Initialize subjects if category already selected
                if(this.formData.category_id) {
                    fetch(`/api/categories/${this.formData.category_id}/subjects`)
                        .then(response => response.json())
                        .then(data => {
                            this.availableSubjects = data;
                        });
                }

                // Initialize specializations if subject already selected
                if(this.formData.subject_id) {
                    fetch(`/api/subjects/${this.formData.subject_id}/specializations`)
                        .then(response => response.json())
                        .then(data => {
                            this.availableSpecializations = data;
                        });
                }

                // Initialize cities if state already selected
                if(this.formData.preferred_state_id) {
                    this.fetchCities(this.formData.preferred_state_id);
                }
            },

            async submitStep1() {
                this.error = '';
                this.fieldErrors = {};

                const hasExistingPhoto = {{ $profile->profile_photo_path ? 'true' : 'false' }};
                const hasExistingSalarySlip = {{ $profile->salary_slip_path ? 'true' : 'false' }};
                const hasExistingResume = {{ $profile->resume_path ? 'true' : 'false' }};

                // Client-side validation — check required fields before calling server
                const requiredFields = {
                    'date_of_birth': 'Date of Birth is required.',
                    'gender': 'Gender is required.',
                    'address': 'Address is required.',
                    'category_id': 'Position / Category is required.',
                    'subject_id': 'Subject is required.',
                    'highest_qualification_id': 'Highest Qualification is required.',
                    'preferred_state_id': 'Preferred State is required.',
                    'preferred_city_id': 'Preferred City is required.',
                    'experience_years': 'Experience (Years) is required.',
                    'residential_preference': 'School Type Preference is required.',
                    'current_salary': 'Current Salary is required.',
                    'expected_salary': 'Expected Salary is required.',
                    'availability_to_join': 'Availability to Join is required.'
                };

                let hasError = false;
                for (const [field, message] of Object.entries(requiredFields)) {
                    if (!this.formData[field] || this.formData[field] === '') {
                        this.fieldErrors[field] = [message];
                        hasError = true;
                    }
                }

                // Check profile photo
                if (!this.profilePhotoFile && !hasExistingPhoto) {
                    this.fieldErrors['profile_photo'] = ['Profile Photo is required.'];
                    hasError = true;
                }

                // Check resume file
                if (!this.resumeFile && !hasExistingResume) {
                    this.fieldErrors['resume'] = ['Resume / CV is required.'];
                    hasError = true;
                }


                if (hasError) {
                    this.error = 'Please fill in all required fields.';
                    // Scroll to the first error
                    this.$nextTick(() => {
                        const firstError = document.querySelector('.text-red-500');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    });
                    return;
                }

                this.loadingMessage = 'Saving Profile...';
                this.loading = true;

                try {
                    const fd = new FormData();
                    fd.append('_token', '{{ csrf_token() }}');
                    for (const key in this.formData) {
                        if (Array.isArray(this.formData[key])) {
                            this.formData[key].forEach(val => {
                                if (val) fd.append(`${key}[]`, val);
                            });
                        } else if (this.formData[key] !== null && this.formData[key] !== undefined && this.formData[key] !== '') {
                            fd.append(key, this.formData[key]);
                        }
                    }
                    if (this.profilePhotoFile) {
                        fd.append('profile_photo', this.profilePhotoFile);
                    }
                    if (this.resumeFile) {
                        fd.append('resume', this.resumeFile);
                    }
                    if (this.salarySlipFile) {
                        fd.append('salary_slip', this.salarySlipFile);
                    }
                    if (this.offerLetterFile) {
                        fd.append('offer_letter', this.offerLetterFile);
                    }

                    const response = await fetch('{{ route("candidate.wizard.step1", [], false) }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: fd
                    });

                    if (response.status === 413) {
                        this.error = 'Uploaded files are too large for the server limit. Please select files under 5MB each.';
                        this.loading = false;
                        return;
                    }
                    if (response.status === 419) {
                        this.error = 'Your session has expired due to inactivity. Please refresh the page and try again.';
                        this.loading = false;
                        return;
                    }

                    const text = await response.text();
                    let data = {};
                    try {
                        const jsonStart = text.indexOf('{');
                        const jsonEnd = text.lastIndexOf('}');
                        if (jsonStart !== -1 && jsonEnd !== -1) {
                            data = JSON.parse(text.substring(jsonStart, jsonEnd + 1));
                        }
                    } catch (parseError) {
                        console.error('Non-JSON response:', text);
                    }
                    
                    if (response.ok && (data.success || !data.errors)) {
                        this.step = 2;
                        window.scrollTo({top: 0, behavior: 'smooth'});
                    } else if (response.status === 422 || data.errors) {
                        this.fieldErrors = data.errors || {};
                        this.error = 'Please fix the errors below.';
                        this.loading = false;
                        return;
                    } else {
                        this.error = data.message || 'Server error occurred. Please try again.';
                    }
                } catch (e) {
                    console.error("Submit Step 1 Error:", e);
                    if (e.name === 'TypeError' || (e.message && (e.message.toLowerCase().includes('fetch') || e.message.toLowerCase().includes('network')))) {
                        this.error = 'Network error or connection lost. Please check your internet connection or file size (Max 5MB) and try again.';
                    } else {
                        this.error = e.message || 'Something went wrong. Please try again.';
                    }
                } finally {
                    this.loading = false;
                }
            },

            initSignaturePad() {
                if (this.sigType === 'draw' && !this.signaturePad) {
                    const canvas = document.getElementById('signature-pad');
                    if(canvas) {
                        if (canvas.offsetWidth === 0) {
                            // Retry if it's still hidden by transition
                            setTimeout(() => this.initSignaturePad(), 100);
                            return;
                        }
                        
                        const resizeCanvas = () => {
                            const ratio =  Math.max(window.devicePixelRatio || 1, 1);
                            canvas.width = canvas.offsetWidth * ratio;
                            canvas.height = canvas.offsetHeight * ratio;
                            canvas.getContext("2d").scale(ratio, ratio);
                        };
                        
                        window.addEventListener("resize", resizeCanvas);
                        resizeCanvas();
                        
                        this.signaturePad = new SignaturePad(canvas, {
                            backgroundColor: 'rgb(255, 255, 255)',
                            penColor: 'rgb(0, 0, 0)'
                        });
                    }
                }
            },

            clearSignature() {
                if (this.signaturePad) {
                    this.signaturePad.clear();
                }
            },

            handleFileUpload(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                if (file.size > 2 * 1024 * 1024) {
                    this.error = "Image size must be less than 2MB";
                    return;
                }

                this.uploadedFile = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.uploadedImagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            handleProfilePhotoUpload(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                if (file.size > 2 * 1024 * 1024) {
                    this.error = "Profile photo size must be less than 2MB";
                    return;
                }

                this.profilePhotoFile = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.profilePhotoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            handleResumeUpload(e) {
                const file = e.target.files[0];
                if (!file) return;
                this.resumeFile = file;
            },

            handleSalarySlipUpload(e) {
                const file = e.target.files[0];
                if (!file) return;
                this.salarySlipFile = file;
            },

            handleOfferLetterUpload(e) {
                const file = e.target.files[0];
                if (!file) return;
                this.offerLetterFile = file;
            },

            async submitStep2() {
                if (!this.agreed) return;
                this.error = '';
                this.loadingMessage = 'Saving Agreement...';
                this.loading = true;

                try {
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('agreed', 1);

                    const response = await fetch('{{ route("candidate.wizard.step2", [], false) }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const result = await response.json();
                    
                    if (response.ok) {
                        this.step = 3;
                    } else {
                        this.error = result.message || 'An error occurred while saving the agreement.';
                    }
                } catch (error) {
                    this.error = 'Network error. Please try again.';
                } finally {
                    this.loading = false;
                }
            },

            async submitStep3() {
                this.error = '';
                
                if (!this.livePhotoBase64) {
                    this.error = 'Please capture a live photo before continuing.';
                    return;
                }

                if (!this.latitude || !this.longitude) {
                    this.error = 'Please share your location before continuing.';
                    return;
                }

                let sigData = '';

                if (this.sigType === 'draw') {
                    if (this.signaturePad.isEmpty()) {
                        this.error = 'Please provide your signature before continuing.';
                        return;
                    }
                    sigData = this.signaturePad.toDataURL();
                } else if (this.sigType === 'type') {
                    if (!this.typedSignature.trim()) {
                        this.error = 'Please type your name as a signature.';
                        return;
                    }
                    sigData = this.typedSignature;
                } else if (this.sigType === 'upload') {
                    if (!this.uploadedImagePreview) {
                        this.error = 'Please upload a signature image.';
                        return;
                    }
                    sigData = this.uploadedImagePreview;
                }

                this.loadingMessage = 'Verifying Identity...';
                this.loading = true;

                try {
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('signature_type', this.sigType);
                    
                    if (this.sigType === 'upload' && this.uploadedFile) {
                        formData.append('signature_file', this.uploadedFile);
                        formData.append('signature_data', 'uploaded');
                    } else {
                        formData.append('signature_data', sigData);
                    }
                    
                    formData.append('live_photo', this.livePhotoBase64);
                    formData.append('latitude', this.latitude);
                    formData.append('longitude', this.longitude);

                    const response = await fetch('{{ route("candidate.wizard.step3", [], false) }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const result = await response.json();
                    
                    if (response.ok) {
                        const isFeePaid = {{ ($profile->initial_fee_paid || $profile->is_fee_paid) ? 'true' : 'false' }};
                        if (isFeePaid) {
                            window.location.href = '{{ route("candidate.dashboard") }}';
                        } else {
                            this.step = 4;
                        }
                    } else {
                        this.error = result.message || 'An error occurred while verifying identity.';
                    }
                } catch (error) {
                    this.error = 'Network error. Please try again.';
                } finally {
                    this.loading = false;
                }
            },

            startCamera() {
                this.cameraError = '';
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: true }).then(stream => {
                        this.stream = stream;
                        const video = document.getElementById('cameraFeed');
                        video.srcObject = stream;
                        video.play();
                        this.isCameraOn = true;
                    }).catch(err => {
                        this.cameraError = "Unable to access camera. Please grant permission.";
                    });
                } else {
                    this.cameraError = "Camera not supported in this browser.";
                }
            },
            
            takePhoto() {
                const video = document.getElementById('cameraFeed');
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth || 640;
                canvas.height = video.videoHeight || 480;
                canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                this.livePhotoBase64 = canvas.toDataURL('image/jpeg');
                this.stopCamera();
            },
            
            stopCamera() {
                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                }
                this.isCameraOn = false;
            },

            handleLivePhotoUpload(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                if (file.size > 2 * 1024 * 1024) {
                    this.error = "Photo size must be less than 2MB";
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.livePhotoBase64 = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            
            getLocation() {
                this.locationError = '';
                this.loadingMessage = 'Fetching location...';
                this.loading = true;
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.latitude = position.coords.latitude;
                            this.longitude = position.coords.longitude;
                            this.loading = false;
                        },
                        (error) => {
                            this.locationError = "Unable to fetch location. Please grant permission.";
                            this.loading = false;
                        }
                    );
                } else {
                    this.locationError = "Geolocation is not supported by this browser.";
                    this.loading = false;
                }
            },

            async submitPayment() {
                this.error = '';
                this.loadingMessage = 'Initiating Secure Payment...';
                this.loading = true;

                try {
                    const response = await fetch('{{ route("candidate.wizard.payment", [], false) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ plan_type: this.selectedPlan })
                    });

                    const data = await response.json();
                    
                    if (response.ok && data.success && data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        this.error = data.message || 'Failed to connect to payment gateway.';
                        this.loading = false;
                    }
                } catch (e) {
                    this.error = 'Something went wrong. Please check your connection.';
                    this.loading = false;
                }
            }
        }));
    });
</script>

<style>
    select option {
        background-color: #0a1e4a;
        color: #ffffff;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(255,255,255,0.1);
        border-radius: 10px;
    }
</style>
@endsection
