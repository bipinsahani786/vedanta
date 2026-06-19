@extends('layouts.app')
@section('content')
<!-- Hero Section -->
<div class="relative pt-32 pb-20 px-6 lg:px-[5%] flex flex-col items-center justify-center text-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2850&q=80" alt="Team" class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-primary-bg/90 backdrop-blur-sm"></div>
    </div>
    <div class="relative z-10 max-w-3xl mx-auto reveal">
        <h4 class="text-accent-yellow text-sm md:text-base font-bold mb-3 uppercase tracking-[0.2em]">Our Story</h4>
        <h1 class="text-4xl md:text-6xl font-extrabold text-text-main mb-6 leading-tight">Empowering Education Since 2020</h1>
        <p class="text-text-main opacity-80 text-base md:text-lg leading-relaxed max-w-2xl mx-auto">
            We are dedicated to bridging the gap between exceptional educators and premier institutions across India.
        </p>
    </div>
</div>

<!-- Split Content -->
<div class="py-20 px-6 lg:px-[5%]">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="relative rounded-2xl overflow-hidden shadow-2xl reveal reveal-delay-1">
            <div class="absolute inset-0 bg-accent-blue/20 mix-blend-overlay z-10"></div>
            <img src="https://images.unsplash.com/photo-1573164713988-8665fc963095?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Office" class="w-full h-full object-cover">
        </div>
        <div class="reveal reveal-delay-2">
            <h2 class="text-3xl font-bold text-text-main mb-6">Our Mission & Vision</h2>
            <p class="text-text-main opacity-70 leading-relaxed mb-6">
                At Vedanta Placement Agency, our mission is to simplify the hiring process in the education sector. We believe that every student deserves a great teacher, and every school deserves a dedicated faculty.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-8">
                <div class="bg-card-bg border border-card-border p-6 rounded-xl">
                    <div class="w-10 h-10 bg-accent-blue rounded-lg flex items-center justify-center text-white mb-4"><i class="fas fa-bullseye"></i></div>
                    <h4 class="text-text-main font-bold mb-2">Our Mission</h4>
                    <p class="text-sm text-text-main opacity-60">To provide seamless recruitment solutions that foster educational excellence.</p>
                </div>
                <div class="bg-card-bg border border-card-border p-6 rounded-xl">
                    <div class="w-10 h-10 bg-accent-yellow rounded-lg flex items-center justify-center text-[#031b4e] mb-4"><i class="fas fa-eye"></i></div>
                    <h4 class="text-text-main font-bold mb-2">Our Vision</h4>
                    <p class="text-sm text-text-main opacity-60">To be the gold standard in education recruitment across the globe.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Team Section -->
<div class="py-20 px-6 lg:px-[5%] bg-card-bg/30 border-t border-card-border">
    <div class="text-center mb-16 reveal">
        <h4 class="text-accent-blue text-sm font-bold mb-2 uppercase tracking-wider">Meet The Team</h4>
        <h2 class="text-3xl font-bold text-text-main">The Minds Behind Vedanta</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Team Member 1 -->
        <div class="text-center group reveal reveal-delay-1">
            <div class="relative w-40 h-40 mx-auto rounded-full overflow-hidden mb-6 border-4 border-card-border group-hover:border-accent-blue transition-colors duration-300">
                <img src="https://i.pravatar.cc/300?img=11" alt="Team" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            </div>
            <h3 class="text-lg font-bold text-text-main mb-1">Ramesh Kumar</h3>
            <p class="text-xs text-accent-blue uppercase tracking-wider font-semibold mb-3">Founder & CEO</p>
        </div>
        <!-- Team Member 2 -->
        <div class="text-center group reveal reveal-delay-2">
            <div class="relative w-40 h-40 mx-auto rounded-full overflow-hidden mb-6 border-4 border-card-border group-hover:border-accent-blue transition-colors duration-300">
                <img src="https://i.pravatar.cc/300?img=5" alt="Team" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            </div>
            <h3 class="text-lg font-bold text-text-main mb-1">Priya Sharma</h3>
            <p class="text-xs text-accent-blue uppercase tracking-wider font-semibold mb-3">Head of Recruitment</p>
        </div>
        <!-- Team Member 3 -->
        <div class="text-center group reveal reveal-delay-3">
            <div class="relative w-40 h-40 mx-auto rounded-full overflow-hidden mb-6 border-4 border-card-border group-hover:border-accent-blue transition-colors duration-300">
                <img src="https://i.pravatar.cc/300?img=3" alt="Team" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            </div>
            <h3 class="text-lg font-bold text-text-main mb-1">Amit Singh</h3>
            <p class="text-xs text-accent-blue uppercase tracking-wider font-semibold mb-3">Operations Manager</p>
        </div>
        <!-- Team Member 4 -->
        <div class="text-center group reveal reveal-delay-4">
            <div class="relative w-40 h-40 mx-auto rounded-full overflow-hidden mb-6 border-4 border-card-border group-hover:border-accent-blue transition-colors duration-300">
                <img src="https://i.pravatar.cc/300?img=9" alt="Team" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            </div>
            <h3 class="text-lg font-bold text-text-main mb-1">Neha Gupta</h3>
            <p class="text-xs text-accent-blue uppercase tracking-wider font-semibold mb-3">Client Relations</p>
        </div>
    </div>
</div>
@endsection