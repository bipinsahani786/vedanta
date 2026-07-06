@extends('layouts.app')

@section('title', 'About Us - Best Education Recruitment Agency in India | Vedanta')
@section('meta_description', 'Discover Vedanta Placement Agency, India\'s leading education recruitment experts. We connect top teaching talent with premier schools and institutions.')

@section('content')
<x-page-header title="About Us" image="{{ asset('images/about_us_hero.png') }}" :breadcrumbs="['Home' => route('home'), 'About Us' => null]" />

<!-- Our Story / Who We Are (SEO Optimized) -->
<div class="py-20 px-6 lg:px-[5%] bg-white">
    <div class="max-w-4xl mx-auto text-center reveal">
        <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-8 leading-tight">Empowering Education Through <br/><span class="text-accent-blue">Expert Recruitment</span></h1>
        <div class="text-lg text-slate-700 leading-relaxed space-y-6 text-justify md:text-center md:px-10">
            <p>
                Welcome to <strong>Vedanta Placement Agency</strong>, the most trusted name in <strong>education recruitment across India</strong>. 
                With years of specialized experience, we serve as the vital bridge connecting passionate educators with premier educational institutions. 
            </p>
            <p>
                Whether you are a school looking to hire top-tier teaching faculty or an educator seeking the perfect teaching job, our comprehensive 
                placement services are tailored to meet your unique needs. We specialize in providing end-to-end recruitment solutions for schools, colleges, and universities, ensuring that every classroom is led by an inspiring and qualified professional.
            </p>
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