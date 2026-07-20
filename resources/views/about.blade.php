@extends('layouts.app')

@section('title', 'About Us - Best Education Recruitment Agency in India | Vedanta')
@section('meta_description', 'Discover Vedanta Placement Agency, India\'s leading education recruitment experts. We connect top teaching talent with premier schools and institutions.')

@section('content')
<x-page-header title="About Us" image="{{ asset('images/about_us_hero.png') }}" :breadcrumbs="['Home' => route('home'), 'About Us' => null]" />

<!-- Our Story / Who We Are (SEO Optimized Diagram Layout) -->
<div class="py-24 px-6 lg:px-[5%] bg-white relative overflow-hidden">
    <!-- Subtle Background Pattern -->
    <div class="absolute inset-0 z-0 opacity-5" style="background-image: radial-gradient(#129aef 1px, transparent 1px); background-size: 32px 32px;"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center mb-16 reveal">
            <h1 class="text-4xl md:text-5xl font-bold text-[#040e2d] leading-tight mb-4">Empowering Education Through <br/><span class="text-[#129aef]">Expert Recruitment</span></h1>
            <div class="w-24 h-1 bg-gradient-to-r from-[#040e2d] to-[#129aef] mx-auto rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 lg:gap-4 items-center relative reveal reveal-delay-1">
            
            <!-- Left Side Text (Who We Are & Mission) -->
            <div class="space-y-6 lg:space-y-12 text-center lg:text-right lg:pr-8 relative">
                
                <!-- Card 1: Who We Are -->
                <div class="bg-white p-8 rounded-2xl border border-blue-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] relative group hover:border-[#129aef]/50 hover:shadow-[0_10px_20px_rgba(18,154,239,0.1)] transition-all duration-300">
                    <div class="hidden lg:block absolute top-1/2 -right-8 w-8 h-0.5 bg-blue-100 group-hover:bg-[#129aef] transition-colors"></div>
                    <div class="hidden lg:block absolute top-1/2 -right-9 w-2 h-2 rounded-full bg-blue-100 group-hover:bg-[#129aef] -mt-[3px] transition-colors"></div>
                    
                    <div class="w-12 h-12 bg-[#f0f8ff] text-[#129aef] rounded-full flex items-center justify-center text-xl mb-4 mx-auto lg:ml-auto lg:mr-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-[#040e2d] mb-3">Who We Are</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Welcome to <strong>Vedanta Placement Agency</strong>, the most trusted name in <strong>education recruitment across India</strong>. 
                        With years of specialized experience, we serve as the vital bridge connecting passionate educators with premier educational institutions.
                    </p>
                </div>

                <!-- Card 2: Our Mission -->
                <div class="bg-white p-8 rounded-2xl border border-blue-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] relative group hover:border-[#129aef]/50 hover:shadow-[0_10px_20px_rgba(18,154,239,0.1)] transition-all duration-300">
                    <div class="hidden lg:block absolute top-1/2 -right-8 w-8 h-0.5 bg-blue-100 group-hover:bg-[#129aef] transition-colors"></div>
                    <div class="hidden lg:block absolute top-1/2 -right-9 w-2 h-2 rounded-full bg-blue-100 group-hover:bg-[#129aef] -mt-[3px] transition-colors"></div>
                    
                    <div class="w-12 h-12 bg-[#f0f8ff] text-[#129aef] rounded-full flex items-center justify-center text-xl mb-4 mx-auto lg:ml-auto lg:mr-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-[#040e2d] mb-3">Our Mission</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Our mission is to support schools and educational institutions by providing reliable recruitment and manpower solutions. We work to connect talented educators and skilled professionals with institutions that value quality education and professional growth.
                    </p>
                </div>

            </div>

            <!-- Center Logo Hub -->
            <div class="flex justify-center items-center relative z-10 py-8 lg:py-0">
                <div class="w-48 h-48 md:w-64 md:h-64 rounded-full bg-white shadow-[0_0_50px_rgba(18,154,239,0.15)] border-8 border-[#f0f8ff] flex items-center justify-center p-6 md:p-10 relative">
                    <!-- Outer pulsating rings -->
                    <div class="absolute inset-0 rounded-full border border-[#129aef]/40 animate-ping" style="animation-duration: 3s;"></div>
                    <div class="absolute -inset-4 rounded-full border border-[#129aef]/20 animate-ping" style="animation-duration: 3s; animation-delay: 1s;"></div>
                    
                    <img src="{{ asset('images/logo.png') }}" alt="Vedanta Logo" class="w-full h-auto object-contain relative z-10">
                </div>
            </div>

            <!-- Right Side Text (What We Do & Vision) -->
            <div class="space-y-6 lg:space-y-12 text-center lg:text-left lg:pl-8 relative">
                
                <!-- Card 3: What We Do -->
                <div class="bg-white p-8 rounded-2xl border border-blue-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] relative group hover:border-[#129aef]/50 hover:shadow-[0_10px_20px_rgba(18,154,239,0.1)] transition-all duration-300">
                    <div class="hidden lg:block absolute top-1/2 -left-8 w-8 h-0.5 bg-blue-100 group-hover:bg-[#129aef] transition-colors"></div>
                    <div class="hidden lg:block absolute top-1/2 -left-9 w-2 h-2 rounded-full bg-blue-100 group-hover:bg-[#129aef] -mt-[3px] transition-colors"></div>
                    
                    <div class="w-12 h-12 bg-[#f0f8ff] text-[#129aef] rounded-full flex items-center justify-center text-xl mb-4 mx-auto lg:mr-auto lg:ml-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-[#040e2d] mb-3">What We Do</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Whether you are a school looking to hire top-tier teaching faculty or an educator seeking the perfect teaching job, our comprehensive placement services are tailored to meet your unique needs. We provide end-to-end recruitment solutions.
                    </p>
                </div>

                <!-- Card 4: Our Vision -->
                <div class="bg-white p-8 rounded-2xl border border-blue-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] relative group hover:border-[#129aef]/50 hover:shadow-[0_10px_20px_rgba(18,154,239,0.1)] transition-all duration-300">
                    <div class="hidden lg:block absolute top-1/2 -left-8 w-8 h-0.5 bg-blue-100 group-hover:bg-[#129aef] transition-colors"></div>
                    <div class="hidden lg:block absolute top-1/2 -left-9 w-2 h-2 rounded-full bg-blue-100 group-hover:bg-[#129aef] -mt-[3px] transition-colors"></div>
                    
                    <div class="w-12 h-12 bg-[#f0f8ff] text-[#129aef] rounded-full flex items-center justify-center text-xl mb-4 mx-auto lg:mr-auto lg:ml-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-[#040e2d] mb-3">Our Vision</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Our vision is to become a trusted recruitment and institutional support partner for schools and organizations across India. We aim to build a strong network of educators and professionals while contributing to the growth of the education sector.
                    </p>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- Why Choose Us Section -->
<div class="py-20 px-6 lg:px-[5%] bg-slate-50 border-t border-slate-200">
    <div class="text-center mb-16 reveal">
        <h2 class="text-accent-blue text-3xl md:text-4xl font-black mb-3 uppercase tracking-wider">Why Choose Us</h2>
        <h3 class="text-2xl md:text-3xl font-bold text-slate-900">The Vedanta Advantage</h3>
        <p class="mt-4 text-slate-600 text-lg max-w-2xl mx-auto">As a leading teacher placement agency, we offer unparalleled benefits to both schools and job seekers.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow reveal reveal-delay-1 text-center md:text-left">
            <div class="w-14 h-14 bg-accent-blue/10 rounded-xl flex items-center justify-center text-accent-blue mb-6 text-2xl mx-auto md:mx-0">
                <i class="fas fa-network-wired"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-3">Extensive Network</h3>
            <p class="text-slate-600 leading-relaxed text-justify md:text-left">Access our vast database of verified schools and pre-screened educators across multiple boards including CBSE, ICSE, and IB.</p>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow reveal reveal-delay-2 text-center md:text-left">
            <div class="w-14 h-14 bg-accent-yellow/20 rounded-xl flex items-center justify-center text-[#d99f00] mb-6 text-2xl mx-auto md:mx-0">
                <i class="fas fa-check-double"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-3">Quality Assurance</h3>
            <p class="text-slate-600 leading-relaxed text-justify md:text-left">Our rigorous screening process ensures only the most qualified and dedicated professionals make it to your interview rounds.</p>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow reveal reveal-delay-3 text-center md:text-left">
            <div class="w-14 h-14 bg-green-500/10 rounded-xl flex items-center justify-center text-green-600 mb-6 text-2xl mx-auto md:mx-0">
                <i class="fas fa-clock"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-3">Time & Cost Efficient</h3>
            <p class="text-slate-600 leading-relaxed text-justify md:text-left">We streamline the hiring process, saving educational institutions valuable time and resources while delivering exceptional talent.</p>
        </div>
    </div>
</div>

<!-- Stats Banner -->
<div class="py-12 px-6 lg:px-[5%] mt-10">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm relative overflow-hidden p-8 md:p-12">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
        <!-- Optional faint logo in center -->
        <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none text-slate-300">
            <i class="fas fa-graduation-cap text-[15rem]"></i>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center relative z-10">
            <div>
                <div class="text-4xl lg:text-5xl font-black mb-2 text-accent-blue"><span class="stat-counter" data-count="85">0</span><span class="text-slate-900">+</span></div>
                <div class="text-sm lg:text-sm font-bold tracking-wide uppercase text-slate-900">Current Openings</div>
            </div>
            <div>
                <div class="text-4xl lg:text-5xl font-black mb-2 text-accent-blue"><span class="stat-counter" data-count="95">0</span><span class="text-slate-900">%</span></div>
                <div class="text-sm lg:text-sm font-bold tracking-wide uppercase text-slate-900">Jobs Fulfillment Rate</div>
            </div>
            <div>
                <div class="text-4xl lg:text-5xl font-black mb-2 text-accent-blue"><span class="stat-counter" data-count="75">0</span><span class="text-slate-900">K</span></div>
                <div class="text-sm lg:text-sm font-bold tracking-wide uppercase text-slate-900">Jobs Applied</div>
            </div>
            <div>
                <div class="text-4xl lg:text-5xl font-black mb-2 text-accent-blue"><span class="stat-counter" data-count="95">0</span><span class="text-slate-900"> %</span></div>
                <div class="text-sm lg:text-sm font-bold tracking-wide uppercase text-slate-900">Satisfied school</div>
            </div>
        </div>
    </div>
</div>

<!-- Mission, Vision, Commitment Section -->
<div class="py-12 px-6 lg:px-[5%] mb-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        
        <!-- Left Column: Mission & Vision -->
        <div class="space-y-8">
            <!-- Our Mission Card -->
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm relative hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 bg-accent-blue text-white w-12 h-12 flex items-center justify-center rounded-bl-2xl text-xl">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="p-8">
                    <h3 class="text-2xl font-black text-slate-900 mb-6">Our Mission</h3>
                    <div class="h-48 w-full rounded-xl overflow-hidden mb-6">
                        <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Mission" class="w-full h-full object-cover">
                    </div>
                    <div class="text-slate-600 text-sm leading-relaxed space-y-4 text-justify">
                        <p>Our mission is to support schools and educational institutions by providing reliable recruitment and manpower solutions. Vedanta Placement Agency works to connect talented educators and skilled professionals with institutions that value quality education and professional growth.</p>
                        <p>We focus on identifying candidates who not only have the right qualifications and experience but also share a genuine commitment to teaching and institutional development.</p>
                    </div>
                </div>
            </div>
            
            <!-- Our Vision Card -->
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm relative hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 bg-accent-blue text-white w-12 h-12 flex items-center justify-center rounded-bl-2xl text-xl">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="p-8">
                    <h3 class="text-2xl font-black text-slate-900 mb-6">Our Vision</h3>
                    <div class="h-48 w-full rounded-xl overflow-hidden mb-6">
                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Vision" class="w-full h-full object-cover">
                    </div>
                    <div class="text-slate-600 text-sm leading-relaxed space-y-4 text-justify">
                        <p>Our vision is to become a trusted recruitment and institutional support partner for schools and organizations across India. We aim to build a strong network of educators, institutions, and professionals while contributing to the growth and quality of the education sector.</p>
                        <p>Through professional recruitment practices and responsible staffing solutions, we strive to support institutions in building strong academic environments.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Our Commitment -->
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm relative hover:shadow-md transition-shadow h-full">
            <div class="absolute top-0 right-0 bg-accent-blue text-white w-12 h-12 flex items-center justify-center rounded-bl-2xl text-xl">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="p-8">
                <h3 class="text-2xl font-black text-slate-900 mb-6">Our Commitment</h3>
                <div class="h-64 w-full rounded-xl overflow-hidden mb-8">
                    <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Commitment" class="w-full h-full object-cover">
                </div>
                <div class="text-slate-600 text-sm leading-relaxed space-y-4 mb-8 text-justify">
                    <p>At Vedanta Placement Agency, we are committed to providing dependable and professional recruitment services. We work closely with institutions to understand their requirements and deliver staffing solutions that support smooth operations and long-term growth.</p>
                    <p>Our goal is to create strong and lasting partnerships with schools, organizations, and professionals through trust, transparency, and consistent service quality.</p>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <h4 class="text-slate-900 font-bold text-lg mb-2">Honesty</h4>
                        <p class="text-slate-600 text-sm leading-relaxed text-justify">Honesty and transparency are at the core of our work. We maintain clear communication with institutions and candidates to ensure that every recruitment process is fair, ethical, and reliable.</p>
                    </div>
                    <div>
                        <h4 class="text-slate-900 font-bold text-lg mb-2">Knowledge</h4>
                        <p class="text-slate-600 text-sm leading-relaxed text-justify">Understanding the needs of modern educational institutions is essential for successful recruitment. We continuously improve our knowledge of the education sector to provide better staffing solutions and connect institutions with capable professionals.</p>
                    </div>
                    <div>
                        <h4 class="text-slate-900 font-bold text-lg mb-2">Performance</h4>
                        <p class="text-slate-600 text-sm leading-relaxed text-justify">We believe in delivering results through dedication and professional service. Our team works consistently to provide reliable recruitment support that helps institutions maintain strong academic and operational standards.</p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- CTA Section with Wave Shape -->
<div class="relative overflow-hidden w-full">
    <!-- Multi-layered Wave Curve Divider -->
    <div class="w-full leading-none overflow-hidden transform rotate-180 -mb-1 relative z-10">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-16 md:h-24 lg:h-32 block">
            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="#129aef"></path>
            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="#129aef"></path>
            <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="#129aef"></path>
        </svg>
    </div>
    
    <div class="py-20 px-6 lg:px-[5%] bg-[#129aef] relative overflow-hidden">
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

@push('scripts')
<script>
    // Statistics Counter Animation
    document.addEventListener('DOMContentLoaded', () => {
        const counters = document.querySelectorAll('.stat-counter');
        const speed = 40; 

        const animateCounters = (entries, observer) => {
            entries.forEach(entry => {
                const counter = entry.target;
                if (entry.isIntersecting) {
                    if(counter.dataset.isAnimating === 'true') return;
                    counter.dataset.isAnimating = 'true';
                    counter.innerText = '0';
                    
                    const updateCount = () => {
                        if(counter.dataset.isAnimating !== 'true') return;
                        
                        const target = +counter.getAttribute('data-count');
                        const count = +counter.innerText;
                        const inc = target / speed;

                        if (count < target) {
                            counter.innerText = Math.ceil(count + inc);
                            counter.timeoutId = setTimeout(updateCount, 40);
                        } else {
                            counter.innerText = target;
                            counter.dataset.isAnimating = 'false';
                        }
                    };
                    updateCount();
                } else {
                    counter.dataset.isAnimating = 'false';
                    if(counter.timeoutId) clearTimeout(counter.timeoutId);
                    counter.innerText = '0';
                }
            });
        };

        const observer = new IntersectionObserver(animateCounters, {
            threshold: 0.1
        });

        counters.forEach(counter => {
            observer.observe(counter);
        });
    });
</script>
@endpush