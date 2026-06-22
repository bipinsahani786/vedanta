@extends('layouts.app')

@section('content')
@include('candidate.partials.nav')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Page Header --}}
    <div class="flex items-center gap-4 mb-8 reveal">
        @if($profile->profile_photo_path)
            <img src="{{ asset('storage/' . $profile->profile_photo_path) }}" alt="Profile Photo" class="w-16 h-16 rounded-full object-cover border-2 border-accent-blue shadow-lg">
        @else
            <div class="w-16 h-16 rounded-full bg-accent-blue/10 text-accent-blue flex items-center justify-center text-3xl shadow-inner">
                <i class="fas fa-user"></i>
            </div>
        @endif
        <div>
            <h1 class="text-2xl font-bold text-text-main flex items-center gap-2">
                My Profile
                @if($profile->is_verified)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-500/20 border border-blue-400/50 text-blue-300 text-[10px] font-bold uppercase tracking-wider rounded-full" title="Verified Profile">
                        <i class="fas fa-check-circle"></i> Verified
                    </span>
                @endif
            </h1>
            <p class="text-sm text-text-dark/50 mt-0.5">Manage your personal and professional details.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-500/10 border border-green-500/30 p-4 rounded-xl flex items-center gap-3 reveal">
            <i class="fas fa-check-circle text-green-400"></i>
            <span class="text-sm text-green-400 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-500/10 border border-red-500/30 p-4 rounded-xl reveal">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold text-red-400 mb-1">Please correct the following errors:</p>
                    <ul class="text-sm text-red-300/80 list-disc pl-4 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Profile Form --}}
    <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl reveal reveal-delay-1">
        <form action="{{ route('candidate.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Section 1: Personal Information --}}
            <div class="p-6 md:p-8 border-b border-card-border">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-8 h-8 rounded-lg bg-accent-blue/10 text-accent-blue flex items-center justify-center text-xs font-bold">1</span>
                    <h3 class="text-lg font-bold text-text-main">Personal Information</h3>
                </div>

                <div class="mb-8 flex items-center gap-6">
                    <div class="relative group">
                        @if($profile->profile_photo_path)
                            <img src="{{ asset('storage/' . $profile->profile_photo_path) }}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover border-4 border-secondary-bg shadow-xl">
                        @else
                            <div class="w-24 h-24 rounded-full bg-secondary-bg flex items-center justify-center text-4xl text-text-dark/30 border-4 border-secondary-bg shadow-inner">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Update Profile Photo</label>
                        <input type="file" name="profile_photo" accept="image/jpeg,image/png,image/jpg,image/webp"
                            class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2.5 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-accent-blue/10 file:text-accent-blue hover:file:bg-accent-blue/20">
                        <p class="text-[11px] text-text-dark/40 mt-1.5"><i class="fas fa-info-circle mr-1"></i> JPG, PNG, WEBP. Max size: 2MB.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Full Name</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/30"><i class="fas fa-user text-sm"></i></span>
                            <input type="text" value="{{ $user->name }}" disabled class="w-full bg-secondary-bg/50 border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-dark/50 cursor-not-allowed">
                        </div>
                        <p class="text-[11px] text-text-dark/30 mt-1.5"><i class="fas fa-lock text-[9px] mr-1"></i>Cannot be changed after registration</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Email</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/30"><i class="fas fa-envelope text-sm"></i></span>
                            <input type="email" value="{{ $user->email }}" disabled class="w-full bg-secondary-bg/50 border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-dark/50 cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Phone Number</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/30"><i class="fas fa-phone-alt text-sm"></i></span>
                            <input type="text" value="{{ $user->phone }}" disabled class="w-full bg-secondary-bg/50 border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-dark/50 cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Date of Birth <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-calendar-alt text-sm"></i></span>
                            <input type="date" name="date_of_birth" required value="{{ old('date_of_birth', $profile->date_of_birth?->format('Y-m-d')) }}"
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Gender <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-venus-mars text-sm"></i></span>
                            <select name="gender" required class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all appearance-none">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $profile->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $profile->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $profile->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Full Address <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-text-dark/40"><i class="fas fa-map-marker-alt text-sm"></i></span>
                            <textarea name="address" required rows="3" class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all resize-none">{{ old('address', $profile->address) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Professional Details --}}
            <div class="p-6 md:p-8 border-b border-card-border">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-8 h-8 rounded-lg bg-accent-yellow/10 text-accent-yellow flex items-center justify-center text-xs font-bold">2</span>
                    <h3 class="text-lg font-bold text-text-main">Professional & Educational Details</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Highest Qualification <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-graduation-cap text-sm"></i></span>
                            <select name="highest_qualification_id" required class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all appearance-none">
                                <option value="">Select Qualification</option>
                                @foreach($qualifications as $qualification)
                                    <option value="{{ $qualification->id }}" {{ old('highest_qualification_id', $profile->highest_qualification_id) == $qualification->id ? 'selected' : '' }}>{{ $qualification->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Job Category <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-th-large text-sm"></i></span>
                            <select name="category_id" required class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all appearance-none">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $profile->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Subject Specialization <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-book text-sm"></i></span>
                            <select name="subject_id" required class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all appearance-none">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $profile->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Preferred Location <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-map-pin text-sm"></i></span>
                            <select name="preferred_location_id" required class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all appearance-none">
                                <option value="">Select Location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('preferred_location_id', $profile->preferred_location_id) == $location->id ? 'selected' : '' }}>{{ $location->city }}, {{ $location->state }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Experience (Years) <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-briefcase text-sm"></i></span>
                            <input type="number" name="experience_years" min="0" required value="{{ old('experience_years', $profile->experience_years) }}"
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                                placeholder="e.g. 3">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Upload Resume <span class="text-text-dark/30 text-[10px] normal-case">(PDF, DOC)</span></label>
                        <div class="relative">
                            <input type="file" name="resume" accept=".pdf,.doc,.docx"
                                class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2.5 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-accent-blue/10 file:text-accent-blue hover:file:bg-accent-blue/20">
                        </div>
                        @if($profile->resume_path)
                            <p class="text-[11px] text-green-400 mt-1.5 flex items-center gap-1"><i class="fas fa-check-circle"></i> Resume uploaded. Select new file to replace.</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Current Salary <span class="text-text-dark/30 text-[10px] normal-case">(Optional)</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-rupee-sign text-sm"></i></span>
                            <input type="text" name="current_salary" value="{{ old('current_salary', $profile->current_salary) }}" placeholder="e.g. 25,000 / month"
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/30 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Expected Salary <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-rupee-sign text-sm"></i></span>
                            <input type="text" name="expected_salary" required value="{{ old('expected_salary', $profile->expected_salary) }}" placeholder="e.g. 35,000 / month"
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/30 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Current School/Organization <span class="text-text-dark/30 text-[10px] normal-case">(Optional)</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-building text-sm"></i></span>
                            <input type="text" name="current_school" value="{{ old('current_school', $profile->current_school) }}" placeholder="e.g. XYZ Public School"
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/30 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">English Fluency</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-language text-sm"></i></span>
                            <select name="english_fluency" class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all appearance-none">
                                <option value="">Select Fluency</option>
                                <option value="beginner" {{ old('english_fluency', $profile->english_fluency) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('english_fluency', $profile->english_fluency) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="fluent" {{ old('english_fluency', $profile->english_fluency) == 'fluent' ? 'selected' : '' }}>Fluent/Native</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Residential Preference</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-home text-sm"></i></span>
                            <select name="residential_preference" class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all appearance-none">
                                <option value="">Select Preference</option>
                                <option value="residential" {{ old('residential_preference', $profile->residential_preference) == 'residential' ? 'selected' : '' }}>Residential (Need Accommodation)</option>
                                <option value="day" {{ old('residential_preference', $profile->residential_preference) == 'day' ? 'selected' : '' }}>Day Boarding (No Accommodation)</option>
                                <option value="both" {{ old('residential_preference', $profile->residential_preference) == 'both' ? 'selected' : '' }}>Open to Both</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Availability to Join</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-clock text-sm"></i></span>
                            <input type="text" name="availability_to_join" value="{{ old('availability_to_join', $profile->availability_to_join) }}" placeholder="e.g. Immediate, 1 Month"
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/30 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="p-6 md:p-8 bg-secondary-bg/30 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-xs text-text-dark/40"><i class="fas fa-info-circle mr-1"></i> Fields marked with <span class="text-red-400">*</span> are required</p>
                <button type="submit" class="px-8 py-3 bg-accent-blue text-white font-semibold rounded-xl hover:bg-accent-blue-hover hover:-translate-y-0.5 transition-all shadow-lg flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Profile
                </button>
            </div>
        </form>
    </div>

    {{-- Change Password Section --}}
    <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl mt-8 reveal reveal-delay-2">
        <form action="{{ route('candidate.password.update') }}" method="POST">
            @csrf
            <div class="p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 flex items-center justify-center text-xs"><i class="fas fa-lock"></i></span>
                    <h3 class="text-lg font-bold text-text-main">Change Password</h3>
                </div>

                @if(session('password_success'))
                    <div class="mb-5 bg-green-500/10 border border-green-500/30 p-4 rounded-xl flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-400"></i>
                        <span class="text-sm text-green-400 font-medium">{{ session('password_success') }}</span>
                    </div>
                @endif

                @if(session('password_error'))
                    <div class="mb-5 bg-red-500/10 border border-red-500/30 p-4 rounded-xl flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                        <span class="text-sm text-red-400 font-medium">{{ session('password_error') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Current Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-key text-sm"></i></span>
                            <input type="password" name="current_password" required class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all" placeholder="••••••••">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">New Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-lock text-sm"></i></span>
                            <input type="password" name="new_password" required class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all" placeholder="••••••••">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Confirm New Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-shield-alt text-sm"></i></span>
                            <input type="password" name="new_password_confirmation" required class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all" placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 md:px-8 pb-6 md:pb-8 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-red-500/10 text-red-400 border border-red-500/20 font-semibold rounded-xl hover:bg-red-500/20 transition-all flex items-center gap-2">
                    <i class="fas fa-key text-xs"></i> Update Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
