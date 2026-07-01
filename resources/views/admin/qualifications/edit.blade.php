@extends('layouts.admin')

@section('title', 'Edit Qualification')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-bold text-gray-800">Edit Qualification: {{ $qualification->name }}</h2>
        <a href="{{ route('admin.qualifications.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>

    <form action="{{ route('admin.qualifications.update', $qualification) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-5">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Qualification Name <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $qualification->name) }}" required 
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#00a8e8] focus:ring focus:ring-[#00a8e8]/20 px-4 py-2 border transition-colors @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6 flex items-center">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $qualification->is_active) ? 'checked' : '' }}
                class="rounded border-gray-300 text-[#00a8e8] shadow-sm focus:border-[#00a8e8] focus:ring focus:ring-[#00a8e8]/20 w-4 h-4 cursor-pointer">
            <label for="is_active" class="ml-2 block text-sm text-gray-700 cursor-pointer">Active</label>
        </div>

        <div class="flex justify-end gap-3">
            <button type="button" onclick="window.history.back()" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-colors">Cancel</button>
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#00a8e8] text-white text-sm font-medium hover:bg-[#008ecc] transition-colors shadow-glow-blue">Update Qualification</button>
        </div>
    </form>
</div>
@endsection
