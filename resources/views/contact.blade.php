@extends('layouts.app')
@section('content')
    <x-page-header title="Contact Us" :breadcrumbs="['Home' => route('home'), 'Contact Us' => null]" />

    <div class="py-20 px-6 lg:px-[5%] relative bg-slate-50">

        <div class="max-w-6xl mx-auto relative z-10">
            <!-- Contact Info Cards (Above Form) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 reveal">
                <!-- Location -->
                <div
                    class="bg-white border border-blue-100 p-6 rounded-2xl flex flex-col items-center text-center gap-4 hover:border-[#129aef]/50 hover:shadow-[0_10px_20px_rgba(18,154,239,0.1)] transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-[#f0f8ff] text-[#129aef] rounded-full flex items-center justify-center text-xl shrink-0 group-hover:scale-110 group-hover:bg-[#129aef] group-hover:text-white transition-all">
                        <i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h4 class="text-slate-800 font-extrabold mb-2 group-hover:text-[#129aef] transition-colors">Our
                            Location</h4>
                        <p class="text-sm text-slate-500 leading-relaxed">Career Point Building, 2nd floor,<br>Patna,800001,
                            Bihar</p>
                    </div>
                </div>

                <!-- Email -->
                <a href="mailto:info@vedantaplacementagency.in"
                    class="bg-white border border-blue-100 p-6 rounded-2xl flex flex-col items-center text-center gap-4 hover:border-[#129aef]/50 hover:shadow-[0_10px_20px_rgba(18,154,239,0.1)] transition-all duration-300 group block">
                    <div
                        class="w-14 h-14 bg-[#f0f8ff] text-[#129aef] rounded-full flex items-center justify-center text-xl shrink-0 group-hover:scale-110 group-hover:bg-[#129aef] group-hover:text-white transition-all">
                        <i class="fas fa-envelope"></i></div>
                    <div>
                        <h4 class="text-slate-800 font-extrabold mb-2 group-hover:text-[#129aef] transition-colors">Email Us
                        </h4>
                        <span class="text-sm text-slate-500 transition-colors">info@vedantaplacementagency.in</span>
                    </div>
                </a>

                <!-- Call -->
                <a href="tel:+917070938975"
                    class="bg-white border border-blue-100 p-6 rounded-2xl flex flex-col items-center text-center gap-4 hover:border-[#129aef]/50 hover:shadow-[0_10px_20px_rgba(18,154,239,0.1)] transition-all duration-300 group block">
                    <div
                        class="w-14 h-14 bg-[#f0f8ff] text-[#129aef] rounded-full flex items-center justify-center text-xl shrink-0 group-hover:scale-110 group-hover:bg-[#129aef] group-hover:text-white transition-all">
                        <i class="fas fa-phone-alt"></i></div>
                    <div>
                        <h4 class="text-slate-800 font-extrabold mb-2 group-hover:text-[#129aef] transition-colors">Call Us
                        </h4>
                        <span class="text-sm text-slate-500 transition-colors">+91-7070938975</span>
                    </div>
                </a>
            </div>

            <!-- Contact Form -->
            <div
                class="max-w-3xl mx-auto bg-white border border-blue-100 p-8 rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] reveal reveal-delay-1">
                <h3 class="text-2xl font-extrabold text-[#040e2d] mb-6 text-center">Send us a message</h3>
                <form class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wider">Full
                                Name</label>
                            <input type="text"
                                class="w-full bg-[#f0f8ff] border border-blue-100 rounded-lg px-4 py-3 text-sm text-slate-700 focus:outline-none focus:border-[#129aef] focus:ring-1 focus:ring-[#129aef] transition-all"
                                placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wider">Email
                                Address</label>
                            <input type="email"
                                class="w-full bg-[#f0f8ff] border border-blue-100 rounded-lg px-4 py-3 text-sm text-slate-700 focus:outline-none focus:border-[#129aef] focus:ring-1 focus:ring-[#129aef] transition-all"
                                placeholder="john@example.com">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wider">Subject</label>
                        <input type="text"
                            class="w-full bg-[#f0f8ff] border border-blue-100 rounded-lg px-4 py-3 text-sm text-slate-700 focus:outline-none focus:border-[#129aef] focus:ring-1 focus:ring-[#129aef] transition-all"
                            placeholder="How can we help?">
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wider">Message</label>
                        <textarea rows="5"
                            class="w-full bg-[#f0f8ff] border border-blue-100 rounded-lg px-4 py-3 text-sm text-slate-700 focus:outline-none focus:border-[#129aef] focus:ring-1 focus:ring-[#129aef] transition-all resize-none"
                            placeholder="Type your message here..."></textarea>
                    </div>
                    <button type="button"
                        class="w-full bg-[#129aef] text-white font-bold py-3.5 rounded-lg hover:-translate-y-1 hover:shadow-[0_10px_20px_rgba(18,154,239,0.3)] transition-all duration-300">
                        Send Message <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection