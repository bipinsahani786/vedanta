@extends('layouts.app')
@section('title', 'Post a Job Requirement — Vedanta Placement Agency')

@section('content')
<x-page-header title="Post a Job Requirement" :breadcrumbs="['Home' => route('home'), 'Post a Job' => null]" />

<div class="py-12 md:py-16 px-4 sm:px-6 lg:px-[5%] bg-slate-50 relative min-h-screen">
    <div class="max-w-4xl mx-auto bg-white border border-slate-200/80 rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.06)] overflow-hidden reveal">
        
        <!-- Header Sub-banner -->
        <div class="bg-gradient-to-r from-[#040e2d] via-[#092265] to-[#040e2d] p-6 sm:p-8 text-center text-white relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#129aef]/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-[#ffb800]/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="w-14 h-14 bg-white/10 border border-white/20 rounded-2xl flex items-center justify-center text-[#ffb800] text-2xl mx-auto mb-3 backdrop-blur-md shadow-inner">
                <i class="fas fa-building-columns"></i>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold tracking-tight mb-1 text-white">Hire Top Educators & Academic Staff</h2>
            <p class="text-blue-200 text-xs sm:text-sm max-w-xl mx-auto font-medium">
                Provide details about your institution and job requirements. Connect with verified teaching & non-teaching talent across India.
            </p>
        </div>

        <div class="p-6 sm:p-10">
            <!-- Success Alert -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl mb-8 flex items-start gap-4 shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5 font-bold">
                        <i class="fas fa-check-circle text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-emerald-900">Requirement Submitted Successfully!</h4>
                        <p class="text-xs sm:text-sm text-emerald-700 mt-1 leading-relaxed">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Validation Errors Alert -->
            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl mb-8 flex items-start gap-4 shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 mt-0.5 font-bold">
                        <i class="fas fa-exclamation-triangle text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-rose-900">Please correct the errors below:</h4>
                        <ul class="list-disc pl-5 text-xs sm:text-sm text-rose-700 mt-2 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('post-job.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Guest Alert Banner -->
                @if(!auth()->check() || auth()->user()->role !== 'employer')
                    <div class="bg-blue-50/80 border border-blue-100 rounded-xl p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4 text-slate-700 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-lg">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="text-xs sm:text-sm leading-relaxed">
                            <span class="font-bold text-slate-900">Posting as Guest:</span> You are submitting a requirement directly. To manage candidates and track applications faster, 
                            <a href="{{ route('employer.register') }}" class="text-[#129aef] font-bold underline hover:text-[#0d85d4] transition-colors">Register as Employer</a> or 
                            <a href="{{ route('login') }}" class="text-[#129aef] font-bold underline hover:text-[#0d85d4] transition-colors">Login</a>.
                        </div>
                    </div>
                @endif

                <!-- Section 1: Institution Details -->
                <div>
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3 mb-6">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#129aef] flex items-center justify-center text-sm font-bold shadow-sm">
                            <i class="fas fa-university"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#040e2d] tracking-wide">1. Institution Details</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Institution / School Name
                            </label>
                            <input type="text" name="school_name" value="{{ old('school_name', (auth()->check() && auth()->user()->role == 'employer') ? auth()->user()->name : '') }}" 
                                class="w-full bg-[#f8fafc] border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:ring-2 focus:ring-[#129aef]/20 transition-all placeholder:text-slate-400 font-medium"
                                placeholder="e.g. St. Xavier International School">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Contact Person Name
                            </label>
                            <input type="text" name="contact_person" value="{{ old('contact_person', (auth()->check() && auth()->user()->role == 'employer') ? auth()->user()->name : '') }}" 
                                class="w-full bg-[#f8fafc] border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:ring-2 focus:ring-[#129aef]/20 transition-all placeholder:text-slate-400 font-medium"
                                placeholder="e.g. Dr. Rajesh Sharma">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Official Email Address
                            </label>
                            <input type="email" name="email" value="{{ old('email', (auth()->check() && auth()->user()->role == 'employer') ? auth()->user()->email : '') }}" 
                                class="w-full bg-[#f8fafc] border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:ring-2 focus:ring-[#129aef]/20 transition-all placeholder:text-slate-400 font-medium"
                                placeholder="e.g. hr@stxaviers.edu.in">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Contact Phone Number
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone', (auth()->check() && auth()->user()->role == 'employer') ? auth()->user()->phone : '') }}" 
                                class="w-full bg-[#f8fafc] border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:ring-2 focus:ring-[#129aef]/20 transition-all placeholder:text-slate-400 font-medium"
                                placeholder="e.g. +91 98765 43210">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Job Requirements -->
                <div class="pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3 mb-6">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#129aef] flex items-center justify-center text-sm font-bold shadow-sm">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#040e2d] tracking-wide">2. Job Position Details</h3>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Job Title / Position Name
                            </label>
                            <input type="text" name="title" value="{{ old('title') }}" 
                                class="w-full bg-[#f8fafc] border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:ring-2 focus:ring-[#129aef]/20 transition-all placeholder:text-slate-400 font-medium" 
                                placeholder="e.g. PGT Physics Teacher / Vice Principal / Academic Coordinator">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Job Category <span class="text-rose-500">*</span>
                                </label>
                                <select name="category_id" id="job_category" required 
                                    class="w-full bg-[#f8fafc] border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:ring-2 focus:ring-[#129aef]/20 transition-all appearance-none cursor-pointer font-medium"
                                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Subject <span class="text-rose-500">*</span>
                                </label>
                                <select name="subject_id" id="job_subject" required 
                                    class="w-full bg-[#f8fafc] border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:ring-2 focus:ring-[#129aef]/20 transition-all appearance-none cursor-pointer font-medium"
                                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="job_specialization_container" style="display: none;" class="md:col-span-2 sm:col-span-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Specialization <span class="text-rose-500">*</span>
                                </label>
                                <select name="specialization_id" id="job_specialization" 
                                    class="w-full bg-[#f8fafc] border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:ring-2 focus:ring-[#129aef]/20 transition-all appearance-none cursor-pointer font-medium"
                                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                                    <option value="">Select Specialization</option>
                                </select>
                            </div>

                            <div class="md:col-span-2 sm:col-span-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Required Qualification <span class="text-rose-500">*</span>
                                </label>
                                <select name="qualification_id" required 
                                    class="w-full bg-[#f8fafc] border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:ring-2 focus:ring-[#129aef]/20 transition-all appearance-none cursor-pointer font-medium"
                                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                                    <option value="">Select Qualification</option>
                                    @foreach($qualifications as $qualification)
                                        <option value="{{ $qualification->id }}" {{ old('qualification_id') == $qualification->id ? 'selected' : '' }}>
                                            {{ $qualification->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Location & Salary -->
                <div class="pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3 mb-6">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#129aef] flex items-center justify-center text-sm font-bold shadow-sm">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#040e2d] tracking-wide">3. Location & Salary Details</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                State <span class="text-rose-500">*</span>
                            </label>
                            <select name="state_id" id="state_id" required 
                                class="w-full bg-[#f8fafc] border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:ring-2 focus:ring-[#129aef]/20 transition-all appearance-none cursor-pointer font-medium"
                                style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                City <span class="text-rose-500">*</span>
                            </label>
                            <select name="city_id" id="city_id" required 
                                class="w-full bg-[#f8fafc] border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:ring-2 focus:ring-[#129aef]/20 transition-all appearance-none cursor-pointer font-medium"
                                style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                                <option value="">Select City</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Salary Range (Monthly)
                            </label>
                            <input type="text" name="salary_range" value="{{ old('salary_range') }}" 
                                class="w-full bg-[#f8fafc] border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:ring-2 focus:ring-[#129aef]/20 transition-all placeholder:text-slate-400 font-medium" 
                                placeholder="e.g. ₹40,000 - ₹60,000 per month / Negotiable">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Job Description & Requirements
                            </label>
                            <textarea name="description" id="editor" rows="5" 
                                class="w-full bg-[#f8fafc] border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:ring-2 focus:ring-[#129aef]/20 transition-all placeholder:text-slate-400 font-medium resize-none" 
                                placeholder="Describe roles, key expectations, perks, or specific qualifications needed...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" 
                        class="w-full py-4 bg-[#129aef] hover:bg-[#0d85d4] text-white font-extrabold text-base sm:text-lg rounded-xl shadow-lg shadow-[#129aef]/25 hover:shadow-[#129aef]/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2.5 cursor-pointer">
                        <i class="fas fa-paper-plane"></i>
                        <span>Submit Job Posting</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<style>
    .ck-editor__editable_inline {
        min-height: 180px;
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
                .catch(error => {
                    console.error('CKEditor init error:', error);
                });
        }

        const stateSelect = document.getElementById('state_id');
        const citySelect = document.getElementById('city_id');
        const jobCategory = document.getElementById('job_category');
        const jobSubject = document.getElementById('job_subject');
        const jobSpecialization = document.getElementById('job_specialization');
        const specializationContainer = document.getElementById('job_specialization_container');

        const initialOldCityId = "{{ old('city_id') }}";
        const initialOldSubjectId = "{{ old('subject_id') }}";
        const initialOldSpecId = "{{ old('specialization_id') }}";

        // State -> City fetch logic
        function loadCities(stateId, selectedCityId = null) {
            citySelect.innerHTML = '<option value="">Loading cities...</option>';
            citySelect.disabled = true;

            if (!stateId) {
                citySelect.innerHTML = '<option value="">Select City</option>';
                citySelect.disabled = false;
                return;
            }

            fetch(`/api/states/${stateId}/cities`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response failed');
                    return response.json();
                })
                .then(cities => {
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.name;
                        if (selectedCityId && selectedCityId == city.id) {
                            option.selected = true;
                        }
                        citySelect.appendChild(option);
                    });
                    citySelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error fetching cities:', error);
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    citySelect.disabled = false;
                });
        }

        if (stateSelect && citySelect) {
            stateSelect.addEventListener('change', function() {
                loadCities(this.value);
            });

            // Initial trigger if state is already selected
            if (stateSelect.value) {
                loadCities(stateSelect.value, initialOldCityId);
            }
        }

        // Category -> Subject logic
        if (jobCategory && jobSubject) {
            jobCategory.addEventListener('change', function() {
                const categoryId = this.value;
                
                jobSubject.innerHTML = '<option value="">Loading subjects...</option>';
                if (jobSpecialization) jobSpecialization.innerHTML = '<option value="">Select Specialization</option>';
                if (specializationContainer) specializationContainer.style.display = 'none';
                
                if (categoryId) {
                    fetch(`/api/categories/${categoryId}/subjects`)
                        .then(response => response.json())
                        .then(subjects => {
                            jobSubject.innerHTML = '<option value="">Select Subject</option>';
                            subjects.forEach(subject => {
                                const option = document.createElement('option');
                                option.value = subject.id;
                                option.textContent = subject.name;
                                jobSubject.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching subjects:', error);
                            jobSubject.innerHTML = '<option value="">Select Subject</option>';
                        });
                } else {
                    jobSubject.innerHTML = '<option value="">Select Subject</option>';
                }
            });
        }

        // Subject -> Specialization logic
        function loadSpecializations(subjectId, selectedSpecId = null) {
            if (!jobSpecialization || !specializationContainer) return;

            jobSpecialization.innerHTML = '<option value="">Select Specialization</option>';
            specializationContainer.style.display = 'none';
            jobSpecialization.required = false;

            if (subjectId) {
                fetch(`/api/subjects/${subjectId}/specializations`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            specializationContainer.style.display = 'block';
                            jobSpecialization.required = true;
                            data.forEach(spec => {
                                const option = document.createElement('option');
                                option.value = spec.id;
                                option.textContent = spec.name;
                                if (selectedSpecId && selectedSpecId == spec.id) {
                                    option.selected = true;
                                }
                                jobSpecialization.appendChild(option);
                            });
                        }
                    })
                    .catch(error => console.error('Error fetching specializations:', error));
            }
        }

        if (jobSubject) {
            jobSubject.addEventListener('change', function() {
                loadSpecializations(this.value);
            });

            if (jobSubject.value && initialOldSpecId) {
                loadSpecializations(jobSubject.value, initialOldSpecId);
            }
        }
    });
</script>
@endpush
@endsection