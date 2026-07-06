@extends('layouts.app')

@section('title', 'About Us - Best Education Recruitment Agency in India | Vedanta')
@section('meta_description', 'Discover Vedanta Placement Agency, India\'s leading education recruitment experts. We connect top teaching talent with premier schools and institutions.')

@section('content')
<x-page-header title="About Us" image="{{ asset('images/about_us_hero.png') }}" :breadcrumbs="['Home' => route('home'), 'About Us' => null]" />

<!-- Our Story / Who We Are (SEO Optimized) -->
<div class="py-20 px-6 lg:px-[5%] bg-white">
    <div class="max-w-4xl mx-auto text-center reveal">
        <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-6">Empowering Education Through <span class="text-accent-blue">Expert Recruitment</span></h1>
        <p class="text-lg text-slate-700 leading-relaxed mb-6">
            Welcome to <strong>Vedanta Placement Agency</strong>, the most trusted name in <strong>education recruitment across India</strong>. 
            With years of specialized experience, we serve as the vital bridge connecting passionate educators with premier educational institutions. 
            Whether you are a school looking to hire top-tier teaching faculty or an educator seeking the perfect teaching job, our comprehensive 
            placement services are tailored to meet your unique needs.
        </p>
        <p class="text-lg text-slate-700 leading-relaxed">
            We specialize in providing end-to-end recruitment solutions for schools, colleges, and universities, ensuring that every classroom is led by an inspiring and qualified professional.
        </p>
    </div>
</div>

<!-- Why Choose Us Section -->
<div class="py-20 px-6 lg:px-[5%] bg-slate-50 border-t border-slate-200">
    <div class="text-center mb-16 reveal">
        <h4 class="text-accent-blue text-sm font-bold mb-2 uppercase tracking-wider">Why Choose Us</h4>
        <h2 class="text-3xl font-bold text-slate-900">The Vedanta Advantage</h2>
        <p class="mt-4 text-slate-600 max-w-2xl mx-auto">As a leading teacher placement agency, we offer unparalleled benefits to both schools and job seekers.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow reveal reveal-delay-1">
            <div class="w-14 h-14 bg-accent-blue/10 rounded-xl flex items-center justify-center text-accent-blue mb-6 text-2xl">
                <i class="fas fa-network-wired"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-3">Extensive Network</h3>
            <p class="text-slate-600">Access our vast database of verified schools and pre-screened educators across multiple boards including CBSE, ICSE, and IB.</p>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow reveal reveal-delay-2">
            <div class="w-14 h-14 bg-accent-yellow/20 rounded-xl flex items-center justify-center text-[#ffcc00] mb-6 text-2xl">
                <i class="fas fa-check-double"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-3">Quality Assurance</h3>
            <p class="text-slate-600">Our rigorous screening process ensures only the most qualified and dedicated professionals make it to your interview rounds.</p>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow reveal reveal-delay-3">
            <div class="w-14 h-14 bg-green-500/10 rounded-xl flex items-center justify-center text-green-500 mb-6 text-2xl">
                <i class="fas fa-clock"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-3">Time & Cost Efficient</h3>
            <p class="text-slate-600">We streamline the hiring process, saving educational institutions valuable time and resources while delivering exceptional talent.</p>
        </div>
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

<!-- CTA Section -->
<div class="py-20 px-6 lg:px-[5%] bg-accent-blue relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
    <div class="max-w-4xl mx-auto text-center relative z-10 reveal">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready to Transform Your Educational Journey?</h2>
        <p class="text-white/80 text-lg mb-8">Whether you're hiring top talent or looking for your next teaching opportunity, Vedanta Placement Agency is here to help.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('contact') }}" class="bg-accent-yellow text-[#031b4e] px-8 py-3 rounded-full font-bold hover:shadow-lg transition-all hover:-translate-y-1">
                Contact Us Today
            </a>
            <a href="{{ route('jobs') }}" class="bg-white/10 text-white border border-white/30 px-8 py-3 rounded-full font-bold hover:bg-white hover:text-accent-blue transition-all">
                Browse Teaching Jobs
            </a>
        </div>
    </div>
</div>
@endsection