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

            <!-- Error Message -->
            <div x-show="error" class="bg-red-500/10 border-l-4 border-red-500 p-4 mb-4 mx-8 mt-8 rounded-r-xl" x-transition>
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-400 font-medium" x-text="error"></p>
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

                            <!-- Profile Photo -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Profile Photo (Optional)</label>
                                <input type="file" accept="image/*" @change="handleProfilePhotoUpload"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-accent-blue file:text-white hover:file:bg-accent-blue-hover cursor-pointer">
                                <template x-if="fieldErrors.profile_photo"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.profile_photo[0]"></p></template>
                                <p class="text-xs text-text-dark/40 mt-1">Format: JPG, PNG. Max size: 2MB.</p>
                                <div x-show="profilePhotoPreview" class="mt-3">
                                    <img :src="profilePhotoPreview" class="h-20 w-20 object-cover rounded-full border-2 border-accent-blue shadow-lg">
                                </div>
                            </div>

                            <!-- Resume Upload (Required) -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Resume / CV *</label>
                                <input type="file" accept=".pdf,.doc,.docx" @change="handleResumeUpload" required
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

                            <!-- Qualification -->
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

                            <!-- Current School (Optional) -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Current School (Optional)</label>
                                <input type="text" x-model="formData.current_school" placeholder="E.g., DPS Patna"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                <template x-if="fieldErrors.current_school"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.current_school[0]"></p></template>
                            </div>

                            <!-- English Fluency -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">English Fluency (Optional)</label>
                                <select x-model="formData.english_fluency"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                    <option value="">Select Fluency</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="fluent">Fluent/Advanced</option>
                                </select>
                                <template x-if="fieldErrors.english_fluency"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.english_fluency[0]"></p></template>
                            </div>

                            <!-- Residential Preference -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">School Type Preference (Optional)</label>
                                <select x-model="formData.residential_preference"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                    <option value="">Select Preference</option>
                                    <option value="day">Day School</option>
                                    <option value="residential">Residential/Boarding School</option>
                                    <option value="both">Both</option>
                                </select>
                                <template x-if="fieldErrors.residential_preference"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.residential_preference[0]"></p></template>
                            </div>

                            <!-- Salaries (Optional) -->
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Current Salary (Optional)</label>
                                <input type="text" x-model="formData.current_salary" placeholder="E.g., ₹25,000/month"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                <template x-if="fieldErrors.current_salary"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.current_salary[0]"></p></template>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Expected Salary (Optional)</label>
                                <input type="text" x-model="formData.expected_salary" placeholder="E.g., ₹35,000/month"
                                    class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                                <template x-if="fieldErrors.expected_salary"><p class="text-red-500 text-xs mt-1 font-medium" x-text="fieldErrors.expected_salary[0]"></p></template>
                            </div>
                            
                            <!-- Availability -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Availability to Join (Optional)</label>
                                <input type="text" x-model="formData.availability_to_join" placeholder="E.g., Immediate, 15 Days, 1 Month"
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
                                    <span class="text-xs text-text-dark/50 font-medium">OR</span>
                                    <label class="bg-secondary-bg text-text-main border border-card-border px-4 py-2 rounded-lg text-sm font-semibold hover:bg-card-border transition-colors cursor-pointer shadow-sm">
                                        <i class="fas fa-upload"></i> Upload Photo
                                        <input type="file" class="hidden" accept="image/*" @change="handleLivePhotoUpload">
                                    </label>
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

                <!-- STEP 4: Plan & Payment -->
                <div x-show="step === 4" x-transition.opacity.duration.500ms style="display: none;">
                    <h2 class="text-2xl font-bold text-text-main mb-6 flex items-center gap-3">
                        <i class="fas fa-credit-card text-accent-blue"></i> Choose Registration Plan
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Standard Plan -->
                        <div class="border-2 rounded-2xl p-6 cursor-pointer transition-all duration-300 relative"
                            :class="selectedPlan === 'standard' ? 'border-accent-blue bg-accent-blue/5 shadow-glow-blue' : 'border-card-border hover:border-accent-blue/50 bg-secondary-bg'"
                            @click="selectedPlan = 'standard'">
                            
                            <div class="absolute top-4 right-4 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors"
                                :class="selectedPlan === 'standard' ? 'border-accent-blue' : 'border-text-dark/30'">
                                <div class="w-2.5 h-2.5 rounded-full bg-accent-blue transition-transform" :class="selectedPlan === 'standard' ? 'scale-100' : 'scale-0'"></div>
                            </div>

                            <h3 class="text-xl font-bold text-text-main mb-1">Standard Plan</h3>
                            <div class="text-2xl font-bold text-accent-blue mb-4">₹500 <span class="text-sm font-normal text-text-dark/50">Initially</span></div>
                            
                            <ul class="space-y-3 text-sm text-text-dark/80">
                                <li class="flex gap-2"><i class="fas fa-check text-green-500 mt-1"></i> Profile Activation</li>
                                <li class="flex gap-2"><i class="fas fa-check text-green-500 mt-1"></i> Resume Verification</li>
                                <li class="flex gap-2"><i class="fas fa-check text-green-500 mt-1"></i> Dashboard Access</li>
                                <li class="flex gap-2 text-text-dark/50"><i class="fas fa-info-circle mt-1"></i> Final ₹500 required later</li>
                            </ul>
                        </div>

                        <!-- Premium Plan -->
                        <div class="border-2 rounded-2xl p-6 cursor-pointer transition-all duration-300 relative overflow-hidden"
                            :class="selectedPlan === 'premium' ? 'border-accent-yellow bg-accent-yellow/5 shadow-glow-yellow' : 'border-card-border hover:border-accent-yellow/50 bg-secondary-bg'"
                            @click="selectedPlan = 'premium'">
                            
                            <div class="absolute top-0 right-0 bg-accent-yellow text-[#031b4e] text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-bl-lg">Recommended</div>

                            <div class="absolute top-4 right-4 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors"
                                :class="selectedPlan === 'premium' ? 'border-accent-yellow' : 'border-text-dark/30'">
                                <div class="w-2.5 h-2.5 rounded-full bg-accent-yellow transition-transform" :class="selectedPlan === 'premium' ? 'scale-100' : 'scale-0'"></div>
                            </div>

                            <h3 class="text-xl font-bold text-text-main mb-1">Premium Plan</h3>
                            <div class="text-2xl font-bold text-accent-yellow mb-4">₹1000 <span class="text-sm font-normal text-text-dark/50">One-Time</span></div>
                            
                            <ul class="space-y-3 text-sm text-text-dark/80">
                                <li class="flex gap-2 font-semibold text-accent-yellow"><i class="fas fa-star mt-1"></i> Priority Shortlisting</li>
                                <li class="flex gap-2 font-semibold text-accent-yellow"><i class="fas fa-rocket mt-1"></i> Faster Interview Coordination</li>
                                <li class="flex gap-2"><i class="fas fa-check text-green-500 mt-1"></i> Dedicated Support</li>
                                <li class="flex gap-2"><i class="fas fa-check text-green-500 mt-1"></i> Premium Badge</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between items-center pt-6 border-t border-card-border">
                        <button type="button" @click="step = 3" class="px-6 py-3 rounded-xl font-semibold text-text-dark hover:bg-card-border transition-colors flex items-center gap-2">
                            <i class="fas fa-arrow-left text-sm"></i> Back
                        </button>
                        
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs text-text-dark/50 uppercase tracking-wider font-semibold">Total to Pay</p>
                                <p class="text-xl font-bold text-text-main" x-text="selectedPlan === 'premium' ? '₹1000' : '₹500'"></p>
                            </div>
                            <button type="button" @click="submitPayment" class="bg-[#5f259f] text-white px-8 py-3.5 rounded-xl font-semibold shadow-lg hover:brightness-110 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                                Pay via PhonePe <i class="fas fa-lock text-xs opacity-70"></i>
                            </button>
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
                address: '{{ addslashes($profile->address) }}',
                marital_status: '{{ $profile->marital_status }}',
                religion: '{{ $profile->religion }}',
                english_fluency: '{{ $profile->english_fluency }}',
                residential_preference: '{{ $profile->residential_preference }}',
                availability_to_join: '{{ $profile->availability_to_join }}'
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

            // Payment Data
            selectedPlan: 'standard',

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
                    'experience_years': 'Experience (Years) is required.'
                };

                let hasError = false;
                for (const [field, message] of Object.entries(requiredFields)) {
                    if (!this.formData[field] || this.formData[field] === '') {
                        this.fieldErrors[field] = [message];
                        hasError = true;
                    }
                }

                // Check resume file
                if (!this.resumeFile) {
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
                        if (this.formData[key] !== null && this.formData[key] !== undefined && this.formData[key] !== '') {
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

                    const response = await fetch('{{ route("candidate.wizard.step1") }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: fd
                    });

                    const text = await response.text();
                    let data;
                    try {
                        const jsonStart = text.indexOf('{');
                        const jsonEnd = text.lastIndexOf('}');
                        if (jsonStart !== -1 && jsonEnd !== -1) {
                            data = JSON.parse(text.substring(jsonStart, jsonEnd + 1));
                        } else {
                            throw new Error("No JSON object found");
                        }
                    } catch (parseError) {
                        console.error('Non-JSON response:', text);
                        throw new Error('Server returned an invalid response. Please try again.');
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
                        this.error = data.message || 'An error occurred on the server.';
                    }
                } catch (e) {
                    console.error("Submit Step 1 Error:", e);
                    this.error = e.message || 'Something went wrong. Please try again.';
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

                    const response = await fetch('{{ route("candidate.wizard.step2") }}', {
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

                    const response = await fetch('{{ route("candidate.wizard.step3") }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const result = await response.json();
                    
                    if (response.ok) {
                        this.step = 4;
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
                    const response = await fetch('{{ route("candidate.wizard.payment") }}', {
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
