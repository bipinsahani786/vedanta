@extends('layouts.app')
@section('content')
<div class="pt-32 pb-16 px-6 lg:px-[5%] text-center">
    <h4 class="text-accent-blue text-sm font-bold mb-3 uppercase tracking-wider">What We Offer</h4>
    <h1 class="text-4xl md:text-5xl font-extrabold text-text-main mb-6">Our Premium Services</h1>
    <p class="text-text-main opacity-70 max-w-2xl mx-auto">We provide a comprehensive suite of services designed specifically for the education sector.</p>
</div>

<!-- Deep Dive 1 -->
<div class="py-16 px-6 lg:px-[5%] border-t border-card-border">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="order-2 md:order-1 reveal">
            <div class="w-14 h-14 bg-accent-blue/10 text-accent-blue rounded-xl flex items-center justify-center text-2xl mb-6"><i class="fas fa-users-cog"></i></div>
            <h2 class="text-3xl font-bold text-text-main mb-4">Recruitment Services</h2>
            <p class="text-text-main opacity-70 leading-relaxed mb-6">
                Our flagship recruitment service connects highly qualified educators with top-tier schools and colleges. We handle the entire process from sourcing and screening to interview scheduling and final placement.
            </p>
            <ul class="space-y-3 mb-8">
                <li class="flex items-center gap-3 text-sm text-text-main opacity-80"><i class="fas fa-check-circle text-accent-yellow"></i> Massive database of verified candidates</li>
                <li class="flex items-center gap-3 text-sm text-text-main opacity-80"><i class="fas fa-check-circle text-accent-yellow"></i> Rigorous background and skill checking</li>
                <li class="flex items-center gap-3 text-sm text-text-main opacity-80"><i class="fas fa-check-circle text-accent-yellow"></i> Fast turnaround times for urgent vacancies</li>
            </ul>
            <a href="{{ route('contact') }}" class="px-6 py-2.5 bg-accent-blue text-white rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">Get Started</a>
        </div>
        <div class="order-1 md:order-2 relative h-[400px] rounded-2xl overflow-hidden shadow-xl reveal reveal-delay-2">
            <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Recruitment" class="w-full h-full object-cover">
        </div>
    </div>
</div>

<!-- Deep Dive 2 -->
<div class="py-16 px-6 lg:px-[5%] bg-card-bg/30 border-t border-card-border">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="relative h-[400px] rounded-2xl overflow-hidden shadow-xl reveal reveal-delay-1">
            <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Digital Support" class="w-full h-full object-cover">
        </div>
        <div class="reveal reveal-delay-2">
            <div class="w-14 h-14 bg-accent-blue/10 text-accent-blue rounded-xl flex items-center justify-center text-2xl mb-6"><i class="fas fa-globe"></i></div>
            <h2 class="text-3xl font-bold text-text-main mb-4">Digital Support</h2>
            <p class="text-text-main opacity-70 leading-relaxed mb-6">
                Modern education requires modern infrastructure. We help schools implement and manage robust IT systems, smart classrooms, and digital learning platforms.
            </p>
            <ul class="space-y-3 mb-8">
                <li class="flex items-center gap-3 text-sm text-text-main opacity-80"><i class="fas fa-check-circle text-accent-yellow"></i> Smart classroom setup & maintenance</li>
                <li class="flex items-center gap-3 text-sm text-text-main opacity-80"><i class="fas fa-check-circle text-accent-yellow"></i> School Management System (ERP) implementation</li>
                <li class="flex items-center gap-3 text-sm text-text-main opacity-80"><i class="fas fa-check-circle text-accent-yellow"></i> Staff training on digital tools</li>
            </ul>
            <a href="{{ route('contact') }}" class="px-6 py-2.5 border border-card-border text-text-main rounded-lg text-sm font-semibold hover:bg-card-bg transition-colors">Learn More</a>
        </div>
    </div>
</div>
@endsection