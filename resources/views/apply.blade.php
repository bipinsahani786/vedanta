@extends('layouts.app')
@section('content')
<div class="pt-32 pb-20 px-6 lg:px-[5%] relative">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-accent-blue/5 rounded-full blur-[100px] z-0 pointer-events-none"></div>
    
    <div class="max-w-3xl mx-auto bg-card-bg border border-card-border rounded-2xl shadow-2xl relative z-10 overflow-hidden reveal">
        <div class="bg-primary-bg border-b border-card-border p-8 text-center">
            <div class="w-16 h-16 bg-accent-blue rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4 shadow-glow-blue"><i class="fas fa-user-graduate"></i></div>
            <h1 class="text-3xl font-bold text-text-main mb-2">Job Application</h1>
            <p class="text-text-main opacity-60 text-sm">Submit your profile and let our AI match you with the perfect institution.</p>
        </div>
        <div class="p-8">
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-text-main opacity-80 mb-2 uppercase">First Name</label>
                        <input type="text" class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-text-main opacity-80 mb-2 uppercase">Last Name</label>
                        <input type="text" class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-text-main opacity-80 mb-2 uppercase">Email</label>
                        <input type="email" class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-text-main opacity-80 mb-2 uppercase">Phone Number</label>
                        <input type="tel" class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-main opacity-80 mb-2 uppercase">Highest Qualification</label>
                    <select class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors appearance-none">
                        <option>B.Ed</option>
                        <option>M.Ed</option>
                        <option>Ph.D</option>
                        <option>Other Graduation</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-main opacity-80 mb-2 uppercase">Upload Resume</label>
                    <div class="border-2 border-dashed border-card-border rounded-xl p-8 text-center hover:border-accent-blue hover:bg-accent-blue/5 transition-all cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-4xl text-accent-blue mb-4"></i>
                        <p class="text-sm font-bold text-text-main mb-1">Click to upload or drag and drop</p>
                        <p class="text-xs text-text-main opacity-50">PDF, DOCX, or RTF (max 5MB)</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-card-border flex justify-end gap-4">
                    <button type="button" class="px-6 py-2.5 text-text-main opacity-70 font-bold hover:opacity-100 transition-opacity">Cancel</button>
                    <button type="button" class="px-8 py-2.5 bg-accent-blue text-white font-bold rounded-lg hover:shadow-[0_4px_15px_rgba(var(--theme-accent-blue-rgb),0.4)] transition-all">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection