@extends('layouts.app')
@section('content')
<div class="pt-32 pb-20 px-6 lg:px-[5%] relative">
    <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-accent-blue/10 blur-3xl rounded-full z-0 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-1/3 h-1/3 bg-accent-yellow/5 blur-3xl rounded-full z-0 pointer-events-none"></div>

    <div class="text-center mb-16 relative z-10 reveal">
        <h4 class="text-accent-blue text-sm font-bold mb-2 uppercase tracking-wider">Contact Us</h4>
        <h1 class="text-4xl md:text-5xl font-extrabold text-text-main">Let's Get In Touch</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 relative z-10 max-w-6xl mx-auto">
        <!-- Contact Form -->
        <div class="lg:col-span-3 bg-card-bg border border-card-border p-8 rounded-2xl shadow-xl reveal reveal-delay-1">
            <h3 class="text-2xl font-bold text-text-main mb-6">Send us a message</h3>
            <form class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-text-main opacity-70 mb-2 uppercase tracking-wider">Full Name</label>
                        <input type="text" class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors" placeholder="John Doe">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-main opacity-70 mb-2 uppercase tracking-wider">Email Address</label>
                        <input type="email" class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors" placeholder="john@example.com">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-main opacity-70 mb-2 uppercase tracking-wider">Subject</label>
                    <input type="text" class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors" placeholder="How can we help?">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-text-main opacity-70 mb-2 uppercase tracking-wider">Message</label>
                    <textarea rows="5" class="w-full bg-secondary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-blue transition-colors resize-none" placeholder="Type your message here..."></textarea>
                </div>
                <button type="button" class="w-full bg-accent-blue text-white font-bold py-3.5 rounded-lg hover:shadow-[0_4px_15px_rgba(var(--theme-accent-blue-rgb),0.4)] transition-all">
                    Send Message <i class="fas fa-paper-plane ml-2"></i>
                </button>
            </form>
        </div>

        <!-- Contact Info & Map -->
        <div class="lg:col-span-2 space-y-6 reveal reveal-delay-2">
            <!-- Info Cards -->
            <div class="bg-card-bg border border-card-border p-6 rounded-2xl flex items-start gap-4 hover:border-accent-blue/50 transition-colors">
                <div class="w-12 h-12 bg-accent-blue/10 text-accent-blue rounded-full flex items-center justify-center text-lg shrink-0"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <h4 class="text-text-main font-bold mb-1">Our Location</h4>
                    <p class="text-sm text-text-main opacity-60 leading-relaxed">Agam Kuan, Sardar Patel Colony,<br>Patna, Bihar - 800007, India</p>
                </div>
            </div>
            <div class="bg-card-bg border border-card-border p-6 rounded-2xl flex items-start gap-4 hover:border-accent-blue/50 transition-colors">
                <div class="w-12 h-12 bg-accent-blue/10 text-accent-blue rounded-full flex items-center justify-center text-lg shrink-0"><i class="fas fa-envelope"></i></div>
                <div>
                    <h4 class="text-text-main font-bold mb-1">Email Us</h4>
                    <a href="mailto:info@vedantaplacementagency.in" class="text-sm text-text-main opacity-60 hover:text-accent-blue transition-colors">info@vedantaplacementagency.in</a>
                </div>
            </div>
            <div class="bg-card-bg border border-card-border p-6 rounded-2xl flex items-start gap-4 hover:border-accent-blue/50 transition-colors">
                <div class="w-12 h-12 bg-accent-blue/10 text-accent-blue rounded-full flex items-center justify-center text-lg shrink-0"><i class="fas fa-phone-alt"></i></div>
                <div>
                    <h4 class="text-text-main font-bold mb-1">Call Us</h4>
                    <a href="tel:+917070938975" class="text-sm text-text-main opacity-60 hover:text-accent-blue transition-colors">+91-7070938975</a>
                </div>
            </div>
            
            <!-- Map Placeholder -->
            <div class="h-48 bg-secondary-bg border border-card-border rounded-2xl overflow-hidden relative group">
                <div class="absolute inset-0 flex items-center justify-center text-text-main opacity-50 text-sm font-semibold z-10 group-hover:opacity-100 group-hover:bg-card-bg/80 transition-all cursor-pointer">
                    <i class="fas fa-map mr-2"></i> View on Google Maps
                </div>
                <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Map" class="w-full h-full object-cover opacity-30 grayscale">
            </div>
        </div>
    </div>
</div>
@endsection