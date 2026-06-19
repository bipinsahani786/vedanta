@extends('layouts.app')
@section('content')
<div class="pt-32 pb-20 px-6 lg:px-[5%] relative">
    <div class="max-w-3xl mx-auto bg-card-bg border border-card-border rounded-2xl shadow-2xl relative z-10 overflow-hidden reveal">
        <div class="bg-primary-bg border-b border-card-border p-8 text-center">
            <div class="w-16 h-16 bg-accent-yellow rounded-full flex items-center justify-center text-[#031b4e] text-2xl mx-auto mb-4"><i class="fas fa-building"></i></div>
            <h1 class="text-3xl font-bold text-text-main mb-2">Post a Job Requirement</h1>
            <p class="text-text-main opacity-60 text-sm">Provide details about your institution and the position you are hiring for.</p>
        </div>
        <div class="p-8">
            <form class="space-y-8">
                <!-- Institution Details -->
                <div>
                    <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2"><i class="fas fa-university text-accent-blue"></i> Institution Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-text-main opacity-80 mb-2 uppercase">Institution Name</label>
                            <input type="text" class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-text-main opacity-80 mb-2 uppercase">Contact Person</label>
                            <input type="text" class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-text-main opacity-80 mb-2 uppercase">Contact Email</label>
                            <input type="email" class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Job Details -->
                <div class="pt-6 border-t border-card-border">
                    <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2"><i class="fas fa-briefcase text-accent-blue"></i> Job Details</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-text-main opacity-80 mb-2 uppercase">Job Title</label>
                            <input type="text" class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors" placeholder="e.g. Senior Physics Teacher">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-text-main opacity-80 mb-2 uppercase">Job Type</label>
                                <select class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors appearance-none">
                                    <option>Full Time</option>
                                    <option>Part Time</option>
                                    <option>Contract</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-text-main opacity-80 mb-2 uppercase">Salary Range (Monthly)</label>
                                <input type="text" class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors" placeholder="e.g. 40,000 - 60,000">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-text-main opacity-80 mb-2 uppercase">Job Description</label>
                            <textarea rows="4" class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors resize-none" placeholder="Describe the responsibilities and requirements..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-card-border">
                    <button type="button" class="w-full py-4 bg-accent-blue text-white font-bold rounded-lg hover:shadow-[0_4px_15px_rgba(var(--theme-accent-blue-rgb),0.4)] transition-all">Submit Job Posting</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection