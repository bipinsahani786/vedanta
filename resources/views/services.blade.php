@extends('layouts.app')
@section('content')
<x-page-header title="Our Premium Services" :breadcrumbs="['Home' => route('home'), 'Services' => null]" />

        <!-- Services Section -->
    <section class="py-16 px-6 lg:px-[5%] bg-primary-bg text-text-main text-center relative overflow-hidden">
        <div
            class="absolute top-5 right-[5%] opacity-[0.02] text-7xl md:text-[100px] font-extrabold uppercase pointer-events-none select-none tracking-wider">
            VEDANTA</div>
        <div class="mb-12 relative z-10 reveal">
            <h4 class="text-accent-blue text-base font-medium mb-1.5 uppercase tracking-wider">Providing Everything You
                Need</h4>
            <h2 class="text-3xl lg:text-4xl font-bold text-text-main">Our Services</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
            @forelse($services as $index => $service)
            <div class="relative bg-card-bg border border-card-border p-7 rounded-2xl transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:border-accent-blue/50 group overflow-hidden reveal reveal-delay-{{ ($index % 4) + 1 }}">
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-accent-blue opacity-5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500 z-0"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-xl bg-accent-blue text-white flex items-center justify-center text-xl mb-6 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3 shadow-lg">
                        <i class="{{ $service->icon }}"></i>
                    </div>
                    <h3 class="text-text-main font-bold text-lg mb-2">{{ $service->title }}</h3>
                    <p class="text-text-main opacity-60 text-sm leading-relaxed mb-6">
                        {{ $service->description }}
                    </p>
                    <a href="#" class="inline-flex items-center gap-2 text-accent-blue font-semibold text-sm group/link">
                        Explore Service 
                        <i class="fas fa-arrow-right text-xs transition-transform duration-300 group-hover/link:translate-x-1"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-10 opacity-60">
                <p>No services currently available.</p>
            </div>
            @endforelse
        </div>
    </section>

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