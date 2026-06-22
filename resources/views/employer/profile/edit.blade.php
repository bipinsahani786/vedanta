@extends('layouts.app')

@section('content')
@include('employer.partials.nav')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-text-main">Settings & Profile</h1>
        <p class="text-sm text-text-dark/50 mt-0.5">Manage your account details, change password, and update your institution profile.</p>
    </div>

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

    <form action="{{ route('employer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- Account Details -->
        <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl reveal">
            <div class="p-8">
                <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2 border-b border-card-border pb-2"><i class="fas fa-user text-accent-yellow"></i> Account Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Account Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Account Email</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main cursor-not-allowed opacity-70">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Phone Number <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                    </div>
                </div>
            </div>
        </div>

        <!-- Institution Profile -->
        <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl reveal">
            <div class="p-8">
                <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2 border-b border-card-border pb-2"><i class="fas fa-university text-accent-yellow"></i> Default Institution Profile</h3>
                <p class="text-xs text-text-dark/50 mb-6">These details will be used to automatically fill your job posting forms.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Institution / School Name</label>
                        <input type="text" name="school_name" value="{{ old('school_name', $profile->school_name) }}" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Default Contact Person</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person', $profile->contact_person) }}" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Address</label>
                        <input type="text" name="address" value="{{ old('address', $profile->address) }}" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">About Institution (Optional)</label>
                        <textarea name="about" rows="4" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors resize-none">{{ old('about', $profile->about) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Logo (Optional)</label>
                        @if($profile->logo_path)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="Logo" class="h-16 rounded object-contain bg-white p-1">
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl reveal">
            <div class="p-8">
                <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2 border-b border-card-border pb-2"><i class="fas fa-lock text-accent-yellow"></i> Change Password</h3>
                <p class="text-xs text-text-dark/50 mb-6">Leave blank if you do not want to change your password.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Current Password</label>
                        <input type="password" name="current_password" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">New Password</label>
                        <input type="password" name="new_password" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="px-8 py-3.5 bg-accent-yellow text-[#031b4e] font-bold rounded-xl shadow-lg hover:shadow-glow-yellow hover:-translate-y-0.5 transition-all">Save All Changes</button>
        </div>
    </form>
</div>
@endsection
