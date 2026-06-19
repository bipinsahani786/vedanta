@extends('layouts.app')
@section('content')
<div class="pt-32 pb-20 px-6 lg:px-[5%] text-center">
    <h4 class="text-accent-blue text-sm font-bold mb-3 uppercase tracking-wider">How We Work</h4>
    <h1 class="text-4xl md:text-5xl font-extrabold text-text-main mb-6">Our Hiring Process</h1>
    <p class="text-text-main opacity-70 max-w-2xl mx-auto mb-16">A streamlined, transparent 4-step process designed to connect the best educators with the perfect institutions efficiently.</p>

    <!-- Vertical Timeline -->
    <div class="max-w-3xl mx-auto relative text-left">
        <!-- Line -->
        <div class="absolute left-[27px] md:left-1/2 top-0 bottom-0 w-1 bg-card-border transform md:-translate-x-1/2 rounded-full hidden sm:block"></div>

        <!-- Step 1 -->
        <div class="relative flex flex-col md:flex-row items-center mb-12 reveal reveal-delay-1">
            <div class="md:w-1/2 md:pr-12 text-left md:text-right mb-4 md:mb-0 ml-16 md:ml-0">
                <h3 class="text-2xl font-bold text-text-main mb-2">1. Application & Screening</h3>
                <p class="text-sm text-text-main opacity-60 leading-relaxed">Candidates submit their detailed resumes and portfolios. Our AI-assisted system and expert recruiters screen profiles based on qualifications, experience, and location preferences.</p>
            </div>
            <div class="absolute left-0 md:left-1/2 transform md:-translate-x-1/2 w-14 h-14 bg-primary-bg border-4 border-accent-blue rounded-full flex items-center justify-center text-accent-blue z-10 shadow-lg">
                <i class="fas fa-file-alt text-xl"></i>
            </div>
            <div class="md:w-1/2 md:pl-12 hidden md:block"></div>
        </div>

        <!-- Step 2 -->
        <div class="relative flex flex-col md:flex-row items-center mb-12 reveal reveal-delay-2">
            <div class="md:w-1/2 md:pr-12 hidden md:block"></div>
            <div class="absolute left-0 md:left-1/2 transform md:-translate-x-1/2 w-14 h-14 bg-primary-bg border-4 border-accent-blue rounded-full flex items-center justify-center text-accent-blue z-10 shadow-lg">
                <i class="fas fa-video text-xl"></i>
            </div>
            <div class="md:w-1/2 md:pl-12 text-left mb-4 md:mb-0 ml-16 md:ml-0">
                <h3 class="text-2xl font-bold text-text-main mb-2">2. Initial Interview</h3>
                <p class="text-sm text-text-main opacity-60 leading-relaxed">Shortlisted candidates undergo a rigorous initial interview with our HR experts. We assess communication skills, pedagogical knowledge, and cultural fit.</p>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="relative flex flex-col md:flex-row items-center mb-12 reveal reveal-delay-3">
            <div class="md:w-1/2 md:pr-12 text-left md:text-right mb-4 md:mb-0 ml-16 md:ml-0">
                <h3 class="text-2xl font-bold text-text-main mb-2">3. School Interaction</h3>
                <p class="text-sm text-text-main opacity-60 leading-relaxed">Top profiles are shared with partnering institutions. We arrange demo classes and final interviews directly between the candidate and the school administration.</p>
            </div>
            <div class="absolute left-0 md:left-1/2 transform md:-translate-x-1/2 w-14 h-14 bg-primary-bg border-4 border-accent-blue rounded-full flex items-center justify-center text-accent-blue z-10 shadow-lg">
                <i class="fas fa-school text-xl"></i>
            </div>
            <div class="md:w-1/2 md:pl-12 hidden md:block"></div>
        </div>

        <!-- Step 4 -->
        <div class="relative flex flex-col md:flex-row items-center reveal reveal-delay-4">
            <div class="md:w-1/2 md:pr-12 hidden md:block"></div>
            <div class="absolute left-0 md:left-1/2 transform md:-translate-x-1/2 w-14 h-14 bg-accent-blue border-4 border-primary-bg rounded-full flex items-center justify-center text-white z-10 shadow-glow-blue">
                <i class="fas fa-check text-xl"></i>
            </div>
            <div class="md:w-1/2 md:pl-12 text-left ml-16 md:ml-0">
                <h3 class="text-2xl font-bold text-text-main mb-2">4. Final Placement</h3>
                <p class="text-sm text-text-main opacity-60 leading-relaxed">Upon successful selection, we assist with offer negotiation, documentation, and onboarding to ensure a smooth transition into the new role.</p>
            </div>
        </div>
    </div>
</div>
@endsection