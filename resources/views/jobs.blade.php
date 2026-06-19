@extends('layouts.app')
@section('content')
<div class="pt-32 pb-12 px-6 lg:px-[5%] bg-card-bg/30 border-b border-card-border">
    <div class="max-w-4xl mx-auto text-center reveal">
        <h1 class="text-3xl md:text-5xl font-extrabold text-text-main mb-6">Find Your Dream Role</h1>
        <!-- Search Bar -->
        <div class="bg-primary-bg border border-card-border p-2 rounded-full flex items-center shadow-lg mx-auto mb-6 transition-all focus-within:border-accent-blue focus-within:shadow-[0_4px_20px_rgba(var(--theme-accent-blue-rgb),0.2)]">
            <div class="px-4 text-text-main opacity-50"><i class="fas fa-search"></i></div>
            <input type="text" placeholder="Job title, keywords, or school..." class="bg-transparent border-none outline-none text-text-main flex-grow text-sm py-2">
            <div class="hidden md:flex border-l border-card-border px-4 text-text-main opacity-50 items-center gap-2">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" placeholder="Location" class="bg-transparent border-none outline-none text-text-main text-sm w-32">
            </div>
            <button class="bg-accent-blue text-white font-bold px-6 py-2.5 rounded-full text-sm hover:opacity-90 transition-opacity">Search</button>
        </div>
        <div class="flex flex-wrap justify-center gap-2 text-xs text-text-main opacity-70">
            <span>Popular:</span>
            <a href="#" class="hover:text-accent-blue transition-colors">Mathematics Teacher</a>,
            <a href="#" class="hover:text-accent-blue transition-colors">Principal</a>,
            <a href="#" class="hover:text-accent-blue transition-colors">Computer Science</a>
        </div>
    </div>
</div>

<div class="py-12 px-6 lg:px-[5%] flex flex-col lg:flex-row gap-8">
    <!-- Filters -->
    <div class="w-full lg:w-1/4">
        <div class="bg-card-bg border border-card-border rounded-xl p-6 sticky top-28">
            <h3 class="text-lg font-bold text-text-main mb-4">Filters</h3>
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-text-main mb-3">Job Type</h4>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm text-text-main opacity-80 cursor-pointer hover:text-accent-blue transition-colors"><input type="checkbox" class="accent-accent-blue"> Full Time</label>
                    <label class="flex items-center gap-2 text-sm text-text-main opacity-80 cursor-pointer hover:text-accent-blue transition-colors"><input type="checkbox" class="accent-accent-blue"> Part Time</label>
                    <label class="flex items-center gap-2 text-sm text-text-main opacity-80 cursor-pointer hover:text-accent-blue transition-colors"><input type="checkbox" class="accent-accent-blue"> Contract</label>
                </div>
            </div>
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-text-main mb-3">Category</h4>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm text-text-main opacity-80 cursor-pointer hover:text-accent-blue transition-colors"><input type="checkbox" class="accent-accent-blue"> Teaching Staff</label>
                    <label class="flex items-center gap-2 text-sm text-text-main opacity-80 cursor-pointer hover:text-accent-blue transition-colors"><input type="checkbox" class="accent-accent-blue"> Administration</label>
                    <label class="flex items-center gap-2 text-sm text-text-main opacity-80 cursor-pointer hover:text-accent-blue transition-colors"><input type="checkbox" class="accent-accent-blue"> Support Staff</label>
                </div>
            </div>
            <button class="w-full border border-accent-blue text-accent-blue rounded-lg py-2 text-sm font-semibold hover:bg-accent-blue/10 transition-colors">Apply Filters</button>
        </div>
    </div>

    <!-- Job List -->
    <div class="w-full lg:w-3/4 space-y-4">
        <!-- Job Card 1 -->
        <div class="bg-card-bg border border-card-border rounded-xl p-6 hover:border-accent-blue/50 hover:shadow-lg transition-all group reveal">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white rounded-lg flex items-center justify-center p-2"><img src="https://ui-avatars.com/api/?name=DPS&background=random" class="rounded"></div>
                    <div>
                        <h3 class="text-lg font-bold text-text-main group-hover:text-accent-blue transition-colors">Senior Mathematics Teacher</h3>
                        <p class="text-sm text-text-main opacity-60">Delhi Public School • Patna, Bihar</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="bg-accent-blue/10 text-accent-blue px-3 py-1 rounded-full text-xs font-bold">Full Time</span>
                    <p class="text-xs text-text-main opacity-50 mt-2">Posted 2 days ago</p>
                </div>
            </div>
            <p class="text-sm text-text-main opacity-70 leading-relaxed mb-4">We are looking for an experienced Mathematics teacher for senior secondary classes. Must have minimum 5 years of experience in CBSE curriculum.</p>
            <div class="flex justify-between items-center border-t border-card-border pt-4">
                <div class="flex gap-4 text-xs text-text-main opacity-60">
                    <span><i class="fas fa-rupee-sign"></i> 40k - 60k / month</span>
                    <span><i class="fas fa-briefcase"></i> 5+ Years Exp.</span>
                </div>
                <a href="{{ route('apply') }}" class="text-accent-blue font-semibold text-sm hover:underline">Apply Now <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
        </div>

        <!-- Job Card 2 -->
        <div class="bg-card-bg border border-card-border rounded-xl p-6 hover:border-accent-blue/50 hover:shadow-lg transition-all group reveal reveal-delay-1">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white rounded-lg flex items-center justify-center p-2"><img src="https://ui-avatars.com/api/?name=SA&background=random" class="rounded"></div>
                    <div>
                        <h3 class="text-lg font-bold text-text-main group-hover:text-accent-blue transition-colors">Primary English Coordinator</h3>
                        <p class="text-sm text-text-main opacity-60">St. Albert's Academy • Online / Remote</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="bg-accent-yellow/20 text-accent-yellow px-3 py-1 rounded-full text-xs font-bold">Contract</span>
                    <p class="text-xs text-text-main opacity-50 mt-2">Posted 5 days ago</p>
                </div>
            </div>
            <p class="text-sm text-text-main opacity-70 leading-relaxed mb-4">Seeking a dynamic English coordinator to design primary curriculum and mentor junior staff. Work from home flexibility available.</p>
            <div class="flex justify-between items-center border-t border-card-border pt-4">
                <div class="flex gap-4 text-xs text-text-main opacity-60">
                    <span><i class="fas fa-rupee-sign"></i> 30k - 45k / month</span>
                    <span><i class="fas fa-briefcase"></i> 3+ Years Exp.</span>
                </div>
                <a href="{{ route('apply') }}" class="text-accent-blue font-semibold text-sm hover:underline">Apply Now <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
        </div>
    </div>
</div>
@endsection