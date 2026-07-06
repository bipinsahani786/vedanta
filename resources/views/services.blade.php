@extends('layouts.app')
@section('content')
<x-page-header title="Our Premium Services" :breadcrumbs="['Home' => route('home'), 'Services' => null]" />

<!-- Services Section -->
    <section class="py-24 px-6 lg:px-[5%] bg-white text-slate-900 text-center relative overflow-hidden">
        <div class="mb-16 relative z-10 reveal">
            <h4 class="text-accent-blue text-sm font-bold mb-2 uppercase tracking-widest">Providing Everything You Need</h4>
            <h2 class="text-4xl lg:text-5xl font-extrabold text-slate-900 mb-4">Our Premium Services</h2>
            <p class="text-slate-600 max-w-2xl mx-auto text-lg">We deliver top-tier recruitment and digital support solutions tailored for modern educational institutions.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 relative z-10 max-w-7xl mx-auto">
            @forelse($services as $index => $service)
            <div class="relative bg-slate-50 border border-slate-100 p-8 rounded-[2rem] transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl hover:shadow-accent-blue/10 hover:border-accent-blue/30 group overflow-hidden reveal reveal-delay-{{ ($index % 4) + 1 }}">
                <div class="absolute -top-24 -right-24 w-56 h-56 bg-accent-blue opacity-5 rounded-full blur-3xl group-hover:opacity-10 group-hover:scale-150 transition-all duration-700 z-0 pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-accent-blue to-blue-700 text-white flex items-center justify-center text-2xl mb-8 transition-all duration-500 group-hover:scale-110 group-hover:-rotate-6 group-hover:shadow-[0_0_25px_rgba(37,99,235,0.4)] shadow-lg mx-auto">
                        <i class="{{ $service->icon }}"></i>
                    </div>
                    <h3 class="text-slate-900 font-bold text-lg mb-3 group-hover:text-accent-blue transition-colors duration-300">{{ $service->title }}</h3>
                    <p class="text-slate-600 text-[13px] leading-relaxed mb-6">
                        {{ $service->description }}
                    </p>
                    <a href="{{ route('service.details', $service->slug) }}" class="inline-flex items-center gap-2 text-accent-blue font-bold text-sm group/link hover:text-blue-700 transition-colors">
                        Explore Service 
                        <i class="fas fa-arrow-right text-xs transition-transform duration-300 group-hover/link:translate-x-1"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 text-slate-500 bg-slate-50 rounded-3xl border border-slate-100">
                <i class="fas fa-box-open text-4xl mb-4 opacity-50"></i>
                <p class="text-lg">No services currently available.</p>
            </div>
            @endforelse
        </div>
    </section>

<!-- Deep Dive 1 -->
<div class="py-24 px-6 lg:px-[5%] bg-slate-50 border-t border-slate-200 overflow-hidden">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center max-w-7xl mx-auto">
        <div class="order-2 md:order-1 reveal md:pr-12">
            <div class="w-16 h-16 bg-accent-blue/10 text-accent-blue rounded-2xl flex items-center justify-center text-3xl mb-8 shadow-sm">
                <i class="fas fa-users-cog"></i>
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6">Expert Recruitment Services</h2>
            <p class="text-slate-600 text-lg leading-relaxed mb-8">
                Our flagship recruitment service connects highly qualified educators with top-tier schools and colleges. We handle the entire process from sourcing and screening to interview scheduling and final placement.
            </p>
            <ul class="space-y-4 mb-10">
                <li class="flex items-center gap-4 text-base text-slate-700 font-medium"><i class="fas fa-check-circle text-accent-blue text-xl"></i> Massive database of verified candidates</li>
                <li class="flex items-center gap-4 text-base text-slate-700 font-medium"><i class="fas fa-check-circle text-accent-blue text-xl"></i> Rigorous background and skill checking</li>
                <li class="flex items-center gap-4 text-base text-slate-700 font-medium"><i class="fas fa-check-circle text-accent-blue text-xl"></i> Fast turnaround times for urgent vacancies</li>
            </ul>
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-accent-blue text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-accent-blue/30 hover:-translate-y-1">Get Started Now</a>
        </div>
        <div class="order-1 md:order-2 relative h-[450px] rounded-3xl overflow-hidden shadow-2xl shadow-slate-300/50 reveal reveal-delay-2 group">
            <div class="absolute inset-0 bg-accent-blue/10 mix-blend-overlay z-10 group-hover:bg-transparent transition-colors duration-700"></div>
            <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Recruitment" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
        </div>
    </div>
</div>

<!-- Deep Dive 2 -->
<div class="py-24 px-6 lg:px-[5%] bg-white border-t border-slate-100 overflow-hidden">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center max-w-7xl mx-auto">
        <div class="relative h-[450px] rounded-3xl overflow-hidden shadow-2xl shadow-slate-300/50 reveal reveal-delay-1 group">
            <div class="absolute inset-0 bg-accent-blue/10 mix-blend-overlay z-10 group-hover:bg-transparent transition-colors duration-700"></div>
            <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Digital Support" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
        </div>
        <div class="reveal reveal-delay-2 md:pl-12">
            <div class="w-16 h-16 bg-accent-blue/10 text-accent-blue rounded-2xl flex items-center justify-center text-3xl mb-8 shadow-sm">
                <i class="fas fa-globe"></i>
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6">Digital Infrastructure Support</h2>
            <p class="text-slate-600 text-lg leading-relaxed mb-8">
                Modern education requires modern infrastructure. We help schools implement and manage robust IT systems, smart classrooms, and digital learning platforms for an interactive future.
            </p>
            <ul class="space-y-4 mb-10">
                <li class="flex items-center gap-4 text-base text-slate-700 font-medium"><i class="fas fa-check-circle text-accent-blue text-xl"></i> Smart classroom setup & maintenance</li>
                <li class="flex items-center gap-4 text-base text-slate-700 font-medium"><i class="fas fa-check-circle text-accent-blue text-xl"></i> School Management System (ERP) implementation</li>
                <li class="flex items-center gap-4 text-base text-slate-700 font-medium"><i class="fas fa-check-circle text-accent-blue text-xl"></i> Staff training on digital tools</li>
            </ul>
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-3.5 border-2 border-accent-blue text-accent-blue rounded-xl text-sm font-bold hover:bg-accent-blue hover:text-white transition-all duration-300 shadow-sm hover:shadow-accent-blue/30 hover:-translate-y-1">Learn More</a>
        </div>
    </div>
</div>

<!-- How It Works / Our Process Section -->
<div class="py-24 px-6 lg:px-[5%] bg-slate-50 border-t border-slate-200">
    <div class="text-center mb-16 reveal">
        <h4 class="text-accent-blue text-sm font-bold mb-2 uppercase tracking-widest">Our Process</h4>
        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">How We Deliver Excellence</h2>
        <p class="text-slate-600 max-w-2xl mx-auto text-lg">A streamlined approach designed to bring you the best talent and infrastructure efficiently.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative z-10 max-w-7xl mx-auto">
        <!-- Step 1 -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 text-center relative reveal reveal-delay-1">
            <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-accent-blue text-white rounded-full flex items-center justify-center font-bold text-xl border-4 border-slate-50 shadow-md">1</div>
            <div class="text-accent-blue text-4xl mb-6 mt-4 opacity-80"><i class="fas fa-comments"></i></div>
            <h3 class="text-slate-900 font-bold text-xl mb-3">Consultation</h3>
            <p class="text-slate-600 text-sm leading-relaxed">We understand your unique needs, school culture, and specific requirements for the roles you want to fill.</p>
        </div>
        <!-- Step 2 -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 text-center relative reveal reveal-delay-2">
            <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-accent-blue text-white rounded-full flex items-center justify-center font-bold text-xl border-4 border-slate-50 shadow-md">2</div>
            <div class="text-accent-yellow text-4xl mb-6 mt-4 opacity-80"><i class="fas fa-search"></i></div>
            <h3 class="text-slate-900 font-bold text-xl mb-3">Sourcing</h3>
            <p class="text-slate-600 text-sm leading-relaxed">Our experts tap into our extensive network to identify and shortlist the most qualified and passionate candidates.</p>
        </div>
        <!-- Step 3 -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 text-center relative reveal reveal-delay-3">
            <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-accent-blue text-white rounded-full flex items-center justify-center font-bold text-xl border-4 border-slate-50 shadow-md">3</div>
            <div class="text-green-500 text-4xl mb-6 mt-4 opacity-80"><i class="fas fa-clipboard-check"></i></div>
            <h3 class="text-slate-900 font-bold text-xl mb-3">Screening</h3>
            <p class="text-slate-600 text-sm leading-relaxed">Rigorous background checks, credential verification, and multiple interview rounds ensure only the best make the cut.</p>
        </div>
        <!-- Step 4 -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 text-center relative reveal reveal-delay-4">
            <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-accent-blue text-white rounded-full flex items-center justify-center font-bold text-xl border-4 border-slate-50 shadow-md">4</div>
            <div class="text-accent-blue text-4xl mb-6 mt-4 opacity-80"><i class="fas fa-handshake"></i></div>
            <h3 class="text-slate-900 font-bold text-xl mb-3">Placement</h3>
            <p class="text-slate-600 text-sm leading-relaxed">We handle the coordination and finalizing of the placement, ensuring a smooth transition for both the school and the candidate.</p>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="py-24 px-6 lg:px-[5%] bg-white border-t border-slate-200">
    <div class="max-w-3xl mx-auto text-center mb-16 reveal">
        <h4 class="text-accent-blue text-sm font-bold mb-2 uppercase tracking-widest">Got Questions?</h4>
        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">Frequently Asked Questions</h2>
        <p class="text-slate-600 text-lg">Here are some common questions about our services and how we can help you.</p>
    </div>
    <div class="max-w-4xl mx-auto space-y-6 reveal reveal-delay-1">
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <h3 class="text-slate-900 font-bold text-lg mb-2"><i class="fas fa-question-circle text-accent-blue mr-2"></i> How quickly can you fill an urgent vacancy?</h3>
            <p class="text-slate-600 pl-7 leading-relaxed">Thanks to our vast pre-screened database, we can typically provide a shortlist of highly qualified candidates within 48 to 72 hours of your request.</p>
        </div>
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <h3 class="text-slate-900 font-bold text-lg mb-2"><i class="fas fa-question-circle text-accent-blue mr-2"></i> Do you only recruit for teaching positions?</h3>
            <p class="text-slate-600 pl-7 leading-relaxed">No, while teachers form a large part of our network, we also recruit for administrative roles, principals, coordinators, and specialized staff like counselors and IT administrators.</p>
        </div>
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <h3 class="text-slate-900 font-bold text-lg mb-2"><i class="fas fa-question-circle text-accent-blue mr-2"></i> What kind of digital support do you offer?</h3>
            <p class="text-slate-600 pl-7 leading-relaxed">We provide complete end-to-end digital infrastructure setup. This includes interactive smart boards, reliable campus Wi-Fi, School Management ERP systems, and comprehensive staff training.</p>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="py-24 px-6 lg:px-[5%] bg-accent-blue relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
    <div class="max-w-4xl mx-auto text-center relative z-10 reveal">
        <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">Take the Next Step Towards Excellence</h2>
        <p class="text-white/90 text-xl mb-10 max-w-2xl mx-auto">Get in touch with our experts today and discover how our premium services can transform your educational institution.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-5">
            <a href="{{ route('contact') }}" class="bg-accent-yellow text-slate-900 px-10 py-4 rounded-xl font-bold text-lg hover:shadow-2xl hover:bg-yellow-400 transition-all hover:-translate-y-1">
                Contact Our Team
            </a>
            <a href="{{ route('jobs') }}" class="bg-white/10 text-white border-2 border-white/30 px-10 py-4 rounded-xl font-bold text-lg hover:bg-white hover:text-accent-blue transition-all hover:shadow-2xl">
                Browse Active Jobs
            </a>
        </div>
    </div>
</div>
@endsection