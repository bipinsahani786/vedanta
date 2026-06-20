@extends('layouts.app')
@section('content')
<div class="pt-32 pb-20 px-6 lg:px-[5%] relative">
    <div class="max-w-4xl mx-auto bg-theme-card-bg border border-theme-card-border rounded-2xl shadow-2xl relative z-10 overflow-hidden reveal">
        <div class="bg-theme-primary-bg border-b border-theme-card-border p-8 text-center relative">
            <div class="absolute -top-16 -right-16 w-32 h-32 bg-theme-accent-yellow opacity-10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-16 -left-16 w-32 h-32 bg-theme-accent-blue opacity-10 rounded-full blur-2xl"></div>
            
            <div class="w-16 h-16 bg-theme-accent-yellow/20 rounded-full flex items-center justify-center text-theme-accent-yellow text-2xl mx-auto mb-4 relative z-10"><i class="fas fa-building"></i></div>
            <h1 class="text-3xl font-bold text-theme-text-dark mb-2 relative z-10">Post a Job Requirement</h1>
            <p class="text-theme-text-light text-sm relative z-10">Provide details about your institution and the position you are hiring for.</p>
        </div>
        
        <div class="p-8">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-start gap-3">
                    <i class="fas fa-check-circle mt-1 text-green-500"></i>
                    <div>
                        <p class="font-bold">Success!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-start gap-3">
                    <i class="fas fa-exclamation-circle mt-1 text-red-500"></i>
                    <div>
                        <p class="font-bold">Please fix the following errors:</p>
                        <ul class="list-disc pl-5 text-sm mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('post-job.store') }}" method="POST" class="space-y-8">
                @csrf
                
                @if(!auth()->check() || auth()->user()->role !== 'employer')
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg flex items-center gap-4 mb-6">
                        <i class="fas fa-info-circle text-theme-accent-blue text-xl"></i>
                        <p class="text-sm text-theme-text-dark">You are posting as a Guest. To track your queries and hire faster, <a href="{{ route('employer.register') }}" class="text-theme-accent-blue font-bold underline">Register an Employer Account</a> or <a href="{{ route('login') }}" class="text-theme-accent-blue font-bold underline">Login</a>.</p>
                    </div>
                @endif

                <!-- Institution Details -->
                <div>
                    <h3 class="text-lg font-bold text-theme-text-dark mb-4 flex items-center gap-2"><i class="fas fa-university text-theme-accent-blue"></i> Institution Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-theme-text-light mb-2 uppercase">Institution Name <span class="text-red-500">*</span></label>
                            <input type="text" name="school_name" value="{{ old('school_name', (auth()->check() && auth()->user()->role == 'employer') ? auth()->user()->name : '') }}" required class="w-full bg-theme-primary-bg border border-theme-card-border rounded-lg px-4 py-3 text-sm text-theme-text-dark focus:outline-none focus:border-theme-accent-blue transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-theme-text-light mb-2 uppercase">Contact Person <span class="text-red-500">*</span></label>
                            <input type="text" name="contact_person" value="{{ old('contact_person', (auth()->check() && auth()->user()->role == 'employer') ? auth()->user()->name : '') }}" required class="w-full bg-theme-primary-bg border border-theme-card-border rounded-lg px-4 py-3 text-sm text-theme-text-dark focus:outline-none focus:border-theme-accent-blue transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-theme-text-light mb-2 uppercase">Contact Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', (auth()->check() && auth()->user()->role == 'employer') ? auth()->user()->email : '') }}" required class="w-full bg-theme-primary-bg border border-theme-card-border rounded-lg px-4 py-3 text-sm text-theme-text-dark focus:outline-none focus:border-theme-accent-blue transition-colors">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-theme-text-light mb-2 uppercase">Phone Number <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', (auth()->check() && auth()->user()->role == 'employer') ? auth()->user()->phone : '') }}" required class="w-full bg-theme-primary-bg border border-theme-card-border rounded-lg px-4 py-3 text-sm text-theme-text-dark focus:outline-none focus:border-theme-accent-blue transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Job Details -->
                <div class="pt-6 border-t border-theme-card-border">
                    <h3 class="text-lg font-bold text-theme-text-dark mb-4 flex items-center gap-2"><i class="fas fa-briefcase text-theme-accent-blue"></i> Job Requirements</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-theme-text-light mb-2 uppercase">Job Title</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="w-full bg-theme-primary-bg border border-theme-card-border rounded-lg px-4 py-3 text-sm text-theme-text-dark focus:outline-none focus:border-theme-accent-blue transition-colors" placeholder="e.g. Senior Physics Teacher">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-theme-text-light mb-2 uppercase">Job Category <span class="text-red-500">*</span></label>
                                <select name="category_id" required class="w-full bg-theme-primary-bg border border-theme-card-border rounded-lg px-4 py-3 text-sm text-theme-text-dark focus:outline-none focus:border-theme-accent-blue transition-colors">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-theme-text-light mb-2 uppercase">Subject <span class="text-red-500">*</span></label>
                                <select name="subject_id" required class="w-full bg-theme-primary-bg border border-theme-card-border rounded-lg px-4 py-3 text-sm text-theme-text-dark focus:outline-none focus:border-theme-accent-blue transition-colors">
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-theme-text-light mb-2 uppercase">Required Qualification <span class="text-red-500">*</span></label>
                                <select name="qualification_id" required class="w-full bg-theme-primary-bg border border-theme-card-border rounded-lg px-4 py-3 text-sm text-theme-text-dark focus:outline-none focus:border-theme-accent-blue transition-colors">
                                    <option value="">Select Qualification</option>
                                    @foreach($qualifications as $qualification)
                                        <option value="{{ $qualification->id }}" {{ old('qualification_id') == $qualification->id ? 'selected' : '' }}>{{ $qualification->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-theme-text-light mb-2 uppercase">Location <span class="text-red-500">*</span></label>
                                <select name="location_id" required class="w-full bg-theme-primary-bg border border-theme-card-border rounded-lg px-4 py-3 text-sm text-theme-text-dark focus:outline-none focus:border-theme-accent-blue transition-colors">
                                    <option value="">Select Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->city }}, {{ $location->state }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-theme-text-light mb-2 uppercase">Salary Range (Monthly)</label>
                                <input type="text" name="salary_range" value="{{ old('salary_range') }}" class="w-full bg-theme-primary-bg border border-theme-card-border rounded-lg px-4 py-3 text-sm text-theme-text-dark focus:outline-none focus:border-theme-accent-blue transition-colors" placeholder="e.g. 40,000 - 60,000">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-theme-text-light mb-2 uppercase">Job Description</label>
                            <textarea name="description" rows="4" class="w-full bg-theme-primary-bg border border-theme-card-border rounded-lg px-4 py-3 text-sm text-theme-text-dark focus:outline-none focus:border-theme-accent-blue transition-colors resize-none" placeholder="Describe the responsibilities and requirements...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-theme-card-border">
                    <button type="submit" class="w-full py-4 bg-theme-accent-blue text-white font-bold rounded-lg hover:bg-theme-accent-blue-hover shadow-lg transition-all">Submit Job Posting</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection