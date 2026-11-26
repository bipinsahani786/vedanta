@extends('layouts.admin')

@section('title', 'Edit School: ' . ($profile->school_name ?: $school->name))
@section('subtitle', 'Update saved institution profile details, school photo/logo, and login account settings.')

@section('actions')
    <a href="{{ route('admin.schools.show', $school->id) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-colors">
        &larr; Back to School Profile
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm max-w-4xl">
    <form action="{{ route('admin.schools.update', $school->id) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        @if($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-xl text-sm border border-red-200">
                <p class="font-bold mb-1">Please fix the following errors:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4">1. Institution Profile Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label for="school_name" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        School / Institution Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="school_name" name="school_name" required value="{{ old('school_name', $profile->school_name) }}"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 outline-none">
                </div>

                <div>
                    <label for="contact_person" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        Primary Contact Person <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="contact_person" name="contact_person" required value="{{ old('contact_person', $profile->contact_person) }}"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 outline-none">
                </div>

                <div>
                    <label for="address" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        School Full Address <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="address" name="address" required value="{{ old('address', $profile->address) }}"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 outline-none">
                </div>

                <div class="md:col-span-2">
                    <label for="about" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        About School / Description
                    </label>
                    <textarea id="about" name="about" rows="3"
                              class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 outline-none">{{ old('about', $profile->about) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        School Photo / Logo
                    </label>
                    @if(!empty($profile->logo_path))
                        <div class="mb-3 flex flex-wrap items-center gap-4 p-3 rounded-xl bg-gray-50 border border-gray-200">
                            <img src="{{ Storage::url($profile->logo_path) }}" alt="{{ $profile->school_name }}" class="h-16 w-16 rounded-xl object-cover border border-gray-200 bg-white">
                            <label class="inline-flex items-center gap-2 text-xs text-red-600 font-semibold cursor-pointer">
                                <input type="checkbox" name="remove_logo" value="1" class="rounded border-gray-300 text-red-600">
                                <span>Remove current photo</span>
                            </label>
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/jpeg,image/png,image/jpg,image/webp"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-xs text-gray-700 bg-gray-50">
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4">2. Account Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        Account Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" required value="{{ old('name', $school->name) }}"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 outline-none">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" required value="{{ old('email', $school->email) }}"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 outline-none">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" required value="{{ old('phone', $school->phone) }}"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 outline-none">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        Reset Password <span class="text-gray-400 font-normal">(Leave blank to keep current)</span>
                    </label>
                    <input type="password" id="password" name="password" minlength="8" placeholder="New password (min 8 chars)"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 font-medium cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $school->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span>Account Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('admin.schools.show', $school->id) }}" class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-sm transition-colors cursor-pointer">
                <i class="fas fa-save mr-1.5"></i> Save School Changes
            </button>
        </div>
    </form>
</div>
@endsection
