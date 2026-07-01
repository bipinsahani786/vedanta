@extends('layouts.app')

@section('content')
<!-- Hero Section -->
    <section class="bg-[#040e2d] relative min-h-[90vh] flex flex-col lg:flex-row items-center px-6 lg:px-[8%] py-20 lg:py-0 overflow-hidden font-sans">
        
        <style>
            @keyframes slowFloat {
                0%, 100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-15px) scale(1.02); }
            }
            @keyframes floatHorizontal {
                0%, 100% { transform: translateX(0); }
                50% { transform: translateX(10px); }
            }
            .anim-bg-waves { animation: slowFloat 15s ease-in-out infinite; }
            .anim-float-h { animation: floatHorizontal 5s ease-in-out infinite; }
        </style>

        <!-- Wavy Topographic Background Pattern (Faded on left) -->
        <svg width="100%" height="100%" class="absolute inset-0 z-0 opacity-[0.35] pointer-events-none anim-bg-waves origin-center" style="-webkit-mask-image: linear-gradient(to right, transparent 10%, black 60%); mask-image: linear-gradient(to right, transparent 10%, black 60%);" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="topo" width="600" height="200" patternUnits="userSpaceOnUse">
                    <path d="M0,10 C100,30 200,-10 300,10 C400,30 500,-10 600,10" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,20 C100,45 200,-5 300,20 C400,45 500,-5 600,20" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,30 C100,65 200,5 300,30 C400,65 500,5 600,30" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,40 C100,85 200,15 300,40 C400,85 500,15 600,40" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,50 C100,100 200,30 300,50 C400,100 500,30 600,50" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,60 C100,110 200,45 300,60 C400,110 500,45 600,60" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,70 C100,115 200,60 300,70 C400,115 500,60 600,70" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,80 C100,110 200,75 300,80 C400,110 500,75 600,80" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,90 C100,100 200,90 300,90 C400,100 500,90 600,90" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,100 C100,85 200,105 300,100 C400,85 500,105 600,100" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,110 C100,70 200,120 300,110 C400,70 500,120 600,110" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,120 C100,55 200,135 300,120 C400,55 500,135 600,120" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,130 C100,40 200,150 300,130 C400,40 500,150 600,130" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,140 C100,30 200,165 300,140 C400,30 500,165 600,140" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,150 C100,20 200,180 300,150 C400,20 500,180 600,150" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,160 C100,10 200,190 300,160 C400,10 500,190 600,160" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,170 C100,5 200,195 300,170 C400,5 500,195 600,170" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,180 C100,-5 200,200 300,180 C400,-5 500,200 600,180" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,190 C100,-10 200,205 300,190 C400,-10 500,205 600,190" fill="none" stroke="white" stroke-width="0.5"/>
                    <path d="M0,200 C100,-10 200,210 300,200 C400,-10 500,210 600,200" fill="none" stroke="white" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect x="0" y="0" width="100%" height="100%" fill="url(#topo)"/>
        </svg>

        <!-- Decorative Background Elements -->
        <!-- Top Left Yellow Diagonal Stripes Circle -->
        <div class="absolute -top-[5%] left-[-5%] w-[250px] h-[250px] rounded-full z-0 opacity-[0.4] pointer-events-none overflow-hidden mix-blend-screen hidden lg:block animate-[spin_40s_linear_infinite]">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="diagonal-stripes" width="12" height="12" patternTransform="rotate(45)" patternUnits="userSpaceOnUse">
                        <line x1="0" y1="0" x2="0" y2="12" stroke="#ffb800" stroke-width="2" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#diagonal-stripes)" />
            </svg>
        </div>

        <!-- Decorative Background Elements -->
        <!-- Top Right White Hollow Circle -->
        <div class="absolute top-[8%] right-[12%] w-[45px] h-[45px] border-[4px] border-white rounded-full z-0 hidden lg:block opacity-90 shadow-md animate-[bounce_4s_ease-in-out_infinite]"></div>
        
        <!-- Right Middle ZigZag Line -->
        <div class="absolute top-[52%] right-[2%] w-[80px] h-[10px] z-0 hidden lg:block opacity-90 shadow-md anim-float-h">
            <svg viewBox="0 0 80 10" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="0,5 5,0 15,10 25,0 35,10 45,0 55,10 65,0 75,10 80,5"/>
            </svg>
        </div>

        <!-- Left Content -->
        <div class="flex-1 relative z-10 w-full lg:pr-12 flex flex-col items-start text-left mb-16 lg:mb-0 pt-10">
            <h1 id="hero-title" class="text-4xl lg:text-[54px] font-extrabold leading-[1.1] mb-6 text-white tracking-tight drop-shadow-md relative z-10 transition-all duration-500 ease-in-out">
                Get placed in top<br>schools across india
            </h1>
            
            <p id="hero-subtitle" class="text-[15px] lg:text-[17px] font-light max-w-[480px] leading-relaxed mb-8 text-white/90 relative z-10 transition-all duration-500 ease-in-out">
                step into the right opportunity with trusted schools that value your talent
            </p>

            <a href="{{ route('candidate.register') }}" id="hero-cta-btn" class="bg-[#ffb800] text-slate-900 px-7 py-3 rounded-full font-bold text-[15px] inline-flex items-center gap-4 transition-transform hover:scale-105 shadow-[0_4px_15px_rgba(255,184,0,0.3)] mb-12 relative z-10">
                <span id="cta-text">Job Seeker</span> 
                <span class="bg-white w-7 h-7 rounded-full flex items-center justify-center text-slate-900 text-sm shadow-sm">
                    <i class="fas fa-chevron-right"></i>
                </span>
            </a>

            <div class="w-full relative z-10">
                <h3 class="text-[22px] font-bold mb-3 text-white drop-shadow-sm">I am a</h3>
                <div class="bg-white inline-flex rounded-xl p-1.5 shadow-xl w-full max-w-[400px]">
                    <button id="btn-seeker" onclick="toggleRole('seeker')" class="role-btn flex-1 py-3.5 rounded-lg text-[15px] font-extrabold flex items-center justify-center gap-2.5 transition-all duration-300 bg-gradient-to-r from-[#2196f3] to-[#00bcd4] text-white shadow-md">
                        <i class="fas fa-user-tie text-[17px]"></i> Job Seeker
                    </button>
                    <button id="btn-employer" onclick="toggleRole('employer')" class="role-btn flex-1 py-3.5 rounded-lg text-[15px] font-extrabold text-slate-800 flex items-center justify-center gap-2.5 transition-all duration-300 bg-transparent hover:bg-slate-50">
                        <i class="fas fa-building text-[17px]"></i> Employer
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Content (Image & Graphics) -->
        <div class="flex-1 relative z-10 flex justify-center items-center min-h-[550px] lg:min-h-[650px] w-full lg:mt-0">
            
            <div class="relative w-[340px] h-[340px] lg:w-[400px] lg:h-[400px] flex justify-center items-center">
                <!-- Large White Background Circle -->
                <div class="absolute inset-0 bg-white rounded-full z-0 shadow-2xl"></div>
                
                <!-- SVG Arcs for perfect rounded caps and exact circle tracing -->
                <div class="absolute z-0 pointer-events-none flex justify-center items-center">
                    <svg id="hero-svg-rings" class="w-[480px] h-[480px] lg:w-[580px] lg:h-[580px] drop-shadow-xl transition-transform duration-500 ease-in-out" viewBox="0 0 700 700">
                        <!-- Yellow Arch (Top Right, slightly longer) -->
                        <circle cx="350" cy="350" r="290" fill="none" stroke="#ffb800" stroke-width="4" stroke-linecap="round" stroke-dasharray="450 2000" transform="rotate(-110 350 350)" />
                        
                        <!-- White Arch (Top Right) -->
                        <circle cx="350" cy="350" r="275" fill="none" stroke="white" stroke-width="4" stroke-linecap="round" stroke-dasharray="400 2000" transform="rotate(-100 350 350)" />

                        <!-- Thick Blue Arch Top Right -->
                        <circle cx="350" cy="350" r="235" fill="none" stroke="#2196f3" stroke-width="45" stroke-linecap="round" stroke-dasharray="310 2000" transform="rotate(-90 350 350)" />

                        <!-- Thick Blue Arch Bottom Left -->
                        <circle cx="350" cy="350" r="235" fill="none" stroke="#2196f3" stroke-width="45" stroke-linecap="round" stroke-dasharray="310 2000" transform="rotate(90 350 350)" />
                    </svg>
                </div>

                <!-- Main Image container -->
                <div class="relative z-10 w-[300px] h-[300px] lg:w-[360px] lg:h-[360px] rounded-full overflow-hidden shadow-inner">
                    <!-- Using the same unspash image -->
                    <img id="hero-img" src="https://images.unsplash.com/photo-1556157382-97eda2d62296?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Professional" class="w-full h-full object-cover relative z-10 transition-all duration-500 ease-in-out">
                </div>

                <!-- Floating Card 1 (Top Left) -->
                <div id="fc-1" class="absolute z-30 top-[5%] lg:top-[10%] -left-[10%] lg:-left-[15%] cursor-pointer group transition-all duration-500 ease-in-out">
                    <div class="bg-white rounded-[14px] p-5 shadow-2xl flex flex-col items-center min-w-[130px] animate-float group-hover:animate-none group-hover:scale-105 group-hover:rotate-3 transition-transform duration-300">
                        <div id="fc-1-icon-wrap" class="bg-[#ffb800] w-12 h-12 rounded-xl flex items-center justify-center text-white text-xl mb-3 shadow-md border border-[#ffb800]/50 transition-colors duration-300">
                            <i id="fc-1-icon" class="fas fa-briefcase"></i>
                        </div>
                        <h4 id="fc-1-title" class="text-slate-800 font-extrabold text-[16px] mb-0.5">20K +</h4>
                        <p id="fc-1-desc" class="text-[9px] text-slate-500 font-bold uppercase tracking-wide">Job Vacancy</p>
                    </div>
                </div>

                <!-- Floating Card 2 (Bottom Right) -->
                <div id="fc-2" class="absolute z-30 bottom-[0%] lg:bottom-[5%] -right-[5%] lg:-right-[10%] cursor-pointer group transition-all duration-500 ease-in-out">
                    <div class="bg-white rounded-[14px] p-5 shadow-2xl flex flex-col items-center min-w-[130px] animate-float group-hover:animate-none group-hover:scale-105 group-hover:-rotate-3 transition-transform duration-300" style="animation-delay: 1.5s;">
                        <h4 id="fc-2-title" class="text-slate-800 font-extrabold text-[14px] mb-1">1+ Million</h4>
                        <p id="fc-2-desc" class="text-[10px] text-slate-500 font-bold mb-3">Trusted User</p>
                        <div id="fc-2-avatars" class="flex items-center">
                            <img src="https://i.pravatar.cc/100?img=11" alt="User" class="w-8 h-8 rounded-full border-2 border-white first:ml-0 shadow-md relative z-10">
                            <img src="https://i.pravatar.cc/100?img=32" alt="User" class="w-8 h-8 rounded-full border-2 border-white -ml-3 shadow-md relative z-20">
                            <img src="https://i.pravatar.cc/100?img=44" alt="User" class="w-8 h-8 rounded-full border-2 border-white -ml-3 shadow-md relative z-30">
                            <img src="https://i.pravatar.cc/100?img=55" alt="User" class="w-8 h-8 rounded-full border-2 border-white -ml-3 shadow-md relative z-40">
                            <div class="w-8 h-8 rounded-full bg-[#ffb800] text-white flex items-center justify-center font-bold border-2 border-white -ml-3 text-[11px] shadow-md relative z-50">+</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Welcome & Statistics Section -->
    <section class="relative bg-cover bg-center bg-no-repeat bg-fixed py-16 px-6 lg:px-[8%]" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=100');">
        <!-- Top Smooth Fade Transition -->
        <div class="absolute top-0 left-0 w-full h-48 z-10 pointer-events-none" style="background: linear-gradient(to bottom, #040e2d 0%, rgba(4, 14, 45, 0) 100%);"></div>
        
        <!-- Glassmorphism Card -->
        <div class="relative z-20 flex flex-col md:flex-row rounded-3xl overflow-hidden mt-8 border border-white/60 shadow-[0_8px_32px_rgba(0,0,0,0.1)]" style="background: rgba(255, 255, 255, 0.45); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);">
            <div class="md:w-1/2 p-10 lg:p-16 flex flex-col justify-center">
                <h4 class="text-[#129aef] font-semibold tracking-wider uppercase mb-3">Welcome</h4>
                <h2 class="text-3xl lg:text-5xl font-bold leading-tight mb-2 text-slate-900">Vedanta Placement<br>Agency</h2>
            </div>
            <div class="md:w-1/2 p-10 lg:p-16 text-slate-900 text-sm leading-relaxed flex flex-col justify-center relative border-t md:border-t-0 md:border-l border-white/40">
                <p class="mb-5 text-[15px] font-medium">Vedanta Placement Agency is an ISO-certified and government-registered education recruitment consultancy operating at a national level across India. We provide structured, compliance-driven, and outcome-focused hiring solutions to schools, colleges, and educational institutions.</p>
                <p class="mb-8 text-[15px] font-medium">With a strong operational presence and an extensive talent network across multiple states, we support institutions in building high-performing academic and administrative teams. Our recruitment methodology is aligned with national education standards, institutional governance requirements, and best practices followed by leading recruitment consultancies in India.</p>
                <a href="#" class="text-[#129aef] font-bold text-base flex items-center gap-2 hover:text-[#031b4e] transition-colors inline-block w-fit">Know More <i class="fas fa-chevron-right text-[10px] bg-[#129aef] text-white rounded-full w-5 h-5 flex items-center justify-center ml-1 inline-flex"></i></a>
                
                <!-- Wavy line decoration -->
                <svg class="absolute right-0 top-1/2 text-[#129aef] opacity-30 w-32 h-32 -mr-8 pointer-events-none" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 50 Q 12.5 30 25 50 T 50 50 T 75 50 T 100 50" stroke="currentColor" stroke-width="4" stroke-linecap="round" fill="none"/>
                </svg>
            </div>
        </div>
    </section>

    <section class="bg-gradient-to-r from-[#129aef] to-[#031b4e] py-12 px-6 lg:px-[5%] text-white">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-white/20">
            <div>
                <h3 class="text-4xl lg:text-6xl font-bold mb-2"><span class="stat-counter" data-count="85">0</span><span class="text-[#ffb800]">+</span></h3>
                <p class="text-sm font-medium text-white/80 uppercase tracking-wide mt-3">Current Openings</p>
            </div>
            <div>
                <h3 class="text-4xl lg:text-6xl font-bold mb-2"><span class="stat-counter" data-count="95">0</span><span class="text-[#ffb800]">%</span></h3>
                <p class="text-sm font-medium text-white/80 uppercase tracking-wide mt-3">Jobs Fulfillment Rate</p>
            </div>
            <div>
                <h3 class="text-4xl lg:text-6xl font-bold mb-2 text-slate-200"><span class="stat-counter" data-count="75">0</span><span class="text-[#ffb800]">K</span></h3>
                <p class="text-sm font-medium text-white/80 uppercase tracking-wide mt-3">Jobs Applied</p>
            </div>
            <div>
                <h3 class="text-4xl lg:text-6xl font-bold mb-2"><span class="stat-counter" data-count="95">0</span><span class="text-[#ffb800]">%</span></h3>
                <p class="text-sm font-medium text-white/80 uppercase tracking-wide mt-3">Satisfied School</p>
            </div>
        </div>
    </section>

<!-- Categories Section -->
    <section class="py-16 px-6 lg:px-[5%] relative bg-slate-50">
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 relative z-10">
            @foreach($categories as $category)
            <a href="{{ route('category.jobs', $category->id) }}"
                class="block bg-[#129aef] border-none rounded-xl p-8 text-center text-white transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl cursor-pointer group reveal shadow-md flex flex-col items-center justify-center no-underline">
                <i class="fas fa-briefcase text-4xl mb-4 block text-white group-hover:scale-110 transition-transform"></i>
                <h3 class="text-sm font-semibold mb-4">{{ $category->name }}</h3>
                <div class="bg-white text-[#129aef] px-5 py-2 rounded-full text-xs font-bold inline-block shadow-sm mt-3">
                    {{ $category->jobs_count }} Active Jobs
                </div>
            </a>
            @endforeach
        </div>
    </section>


    

    <!-- Services Section -->
    <section class="py-20 px-6 lg:px-[5%] bg-[#031b4e] text-white text-center relative overflow-hidden">
        <div
            class="absolute top-5 right-[5%] opacity-[0.02] text-7xl md:text-[100px] font-extrabold uppercase pointer-events-none select-none tracking-wider">
            VEDANTA</div>
        <div class="mb-12 relative z-10 reveal">
            <h4 class="text-accent-blue text-base font-medium mb-1.5 uppercase tracking-wider">Providing Everything You
                Need</h4>
            <h2 class="text-4xl lg:text-5xl font-bold text-white">Our Services</h2>
        </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
            @forelse($services as $index => $service)
            <div class="relative bg-slate-50 border border-transparent p-10 rounded-3xl transition-all duration-300 hover:-translate-y-6 hover:scale-110 hover:shadow-[0_40px_80px_rgba(0,0,0,0.2)] hover:border-[#129aef]/30 hover:z-50 group flex flex-col items-center text-center reveal overflow-hidden z-10 reveal-delay-{{ ($index % 4) + 1 }}">
                
                <!-- Animated Background Blob -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-[#129aef] rounded-bl-full opacity-0 group-hover:opacity-10 transition-all duration-700 group-hover:scale-[2.5] origin-top-right z-0 pointer-events-none"></div>

                <!-- Icon Box with Advanced Animation -->
                <div class="relative z-10 w-24 h-24 rounded-3xl bg-[#129aef] text-white flex items-center justify-center text-4xl mb-6 transition-all duration-500 group-hover:-translate-y-4 group-hover:scale-110 group-hover:shadow-[0_20px_40px_rgba(18,154,239,0.5)] group-hover:rotate-12">
                    <i class="{{ $service->icon }} transition-transform duration-500 group-hover:-rotate-12 group-hover:scale-110"></i>
                </div>
                
                <!-- Title -->
                <h3 class="relative z-10 text-slate-900 font-extrabold text-xl mb-6 transition-colors duration-300 group-hover:text-[#129aef]">{{ $service->title }}</h3>
                
                <!-- Read More Link with split animation -->
                <a href="#" class="relative z-10 inline-flex items-center gap-3 text-[#129aef] font-semibold text-[15px] mt-auto overflow-visible">
                    <span class="transition-transform duration-500 group-hover:-translate-x-1">Read More</span>
                    <div class="bg-[#ffb800] text-slate-900 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-500 group-hover:translate-x-3 group-hover:bg-[#129aef] group-hover:text-white group-hover:shadow-lg group-hover:scale-110">
                        <i class="fas fa-chevron-right text-[11px] transition-transform duration-500 group-hover:translate-x-0.5"></i>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-span-full text-center py-10 opacity-60">
                <p>No services currently available.</p>
            </div>
            @endforelse
        </div>
    </section>

<!-- Our Clients -->
    <section class="bg-white py-14 overflow-hidden border-b border-slate-100">
        <div class="text-left mb-6 px-6 lg:px-[5%] reveal">
            <h2 class="text-3xl font-bold text-slate-800 text-center mb-10">Our Clients</h2>
        </div>
        <div class="swiper marquee-swiper reveal">
            <div class="swiper-wrapper items-center">
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-red-400 text-sm text-center leading-tight">BIRLA<br>OPEN MINDS</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-slate-800 text-sm flex items-center gap-1.5">
                            <i class="fas fa-graduation-cap text-red-600 text-xs"></i> D. GOENKA
                        </span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-blue-400 text-xs leading-tight text-center">Mount Litera<br>Zee
                            School</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="bg-slate-100 text-slate-800 rounded-full w-9 h-9 flex justify-center items-center font-bold text-lg">
                            PW</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="border-2 border-accent-yellow rounded-full w-10 h-10 flex justify-center items-center font-extrabold text-primary-bg text-[11px]">
                            ALLEN</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-indigo-300 text-base tracking-tight">VMC <span
                                class="text-xs font-normal">Classes</span></span>
                    </div>
                </div>
            
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-red-400 text-sm text-center leading-tight">BIRLA<br>OPEN MINDS</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-slate-800 text-sm flex items-center gap-1.5">
                            <i class="fas fa-graduation-cap text-red-600 text-xs"></i> D. GOENKA
                        </span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-blue-400 text-xs leading-tight text-center">Mount Litera<br>Zee
                            School</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="bg-slate-100 text-slate-800 rounded-full w-9 h-9 flex justify-center items-center font-bold text-lg">
                            PW</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="border-2 border-accent-yellow rounded-full w-10 h-10 flex justify-center items-center font-extrabold text-primary-bg text-[11px]">
                            ALLEN</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-indigo-300 text-base tracking-tight">VMC <span
                                class="text-xs font-normal">Classes</span></span>
                    </div>
                </div>
            
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-red-400 text-sm text-center leading-tight">BIRLA<br>OPEN MINDS</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-slate-800 text-sm flex items-center gap-1.5">
                            <i class="fas fa-graduation-cap text-red-600 text-xs"></i> D. GOENKA
                        </span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-blue-400 text-xs leading-tight text-center">Mount Litera<br>Zee
                            School</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="bg-slate-100 text-slate-800 rounded-full w-9 h-9 flex justify-center items-center font-bold text-lg">
                            PW</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="border-2 border-accent-yellow rounded-full w-10 h-10 flex justify-center items-center font-extrabold text-primary-bg text-[11px]">
                            ALLEN</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-indigo-300 text-base tracking-tight">VMC <span
                                class="text-xs font-normal">Classes</span></span>
                    </div>
                </div>
            
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-red-400 text-sm text-center leading-tight">BIRLA<br>OPEN MINDS</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-slate-800 text-sm flex items-center gap-1.5">
                            <i class="fas fa-graduation-cap text-red-600 text-xs"></i> D. GOENKA
                        </span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-blue-400 text-xs leading-tight text-center">Mount Litera<br>Zee
                            School</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="bg-slate-100 text-slate-800 rounded-full w-9 h-9 flex justify-center items-center font-bold text-lg">
                            PW</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="border-2 border-accent-yellow rounded-full w-10 h-10 flex justify-center items-center font-extrabold text-primary-bg text-[11px]">
                            ALLEN</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-indigo-300 text-base tracking-tight">VMC <span
                                class="text-xs font-normal">Classes</span></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Jobs Section -->
    <section class="py-20 px-6 lg:px-[5%] bg-[#031b4e] text-white relative">
        <div class="text-center mb-12 reveal">
            <h4 class="text-accent-blue text-base font-medium mb-1.5 uppercase tracking-wider">Latest Jobs</h4>
            <h2 class="text-white text-3xl lg:text-4xl font-bold mb-4">Explore Recent Opportunities</h2>
            <div class="zigzag-divider w-16 h-2 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
            @forelse($recentJobs as $job)
            <a href="{{ route('jobs.show', $job->id) }}"
                class="block bg-white border border-slate-200 rounded-2xl p-7 text-slate-800 transition-all duration-300 hover:-translate-y-2 shadow-lg hover:shadow-[0_20px_40px_rgba(18,154,239,0.15)] hover:border-[#129aef]/30 flex flex-col group reveal cursor-pointer">
                <h3 class="text-xl font-bold mb-3 text-slate-900 group-hover:text-[#129aef] transition-colors">{{ $job->title ?? 'Job Requirement' }}</h3>
                <p class="text-xs text-slate-500 mb-4 flex items-center gap-3">
                    <span class="text-red-400"><i class="fas fa-map-marker-alt mr-0.5"></i> {{ $job->location->city }}, {{ $job->location->state }}</span>
                    <span><i class="far fa-calendar-alt mr-0.5"></i> {{ $job->created_at->format('d M Y') }}</span>
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="bg-accent-blue/8 text-accent-blue px-2.5 py-1 rounded-lg text-[11px] font-semibold flex items-center gap-1.5">
                        <i class="fas fa-folder-open text-[9px]"></i> {{ $job->category->name }}
                    </span>
                    <span class="bg-accent-blue/8 text-accent-blue px-2.5 py-1 rounded-lg text-[11px] font-semibold flex items-center gap-1.5">
                        <i class="fas fa-book text-[9px]"></i> {{ $job->subject->name }}
                    </span>
                    <span class="bg-accent-blue/8 text-accent-blue px-2.5 py-1 rounded-lg text-[11px] font-semibold flex items-center gap-1.5">
                        <i class="fas fa-graduation-cap text-[9px]"></i> {{ $job->qualification->name }}
                    </span>
                </div>
                <p class="text-[13px] text-slate-600 leading-relaxed mb-6 flex-grow">
                    {{ Str::limit($job->description, 100) }}
                </p>
                <div class="text-accent-blue font-semibold text-[13px] inline-flex items-center gap-2 self-start group-hover:gap-3 transition-all mt-auto">
                    View Details 
                    <span class="bg-accent-yellow text-slate-900 w-5 h-5 rounded-full flex items-center justify-center text-[9px] transition-transform group-hover:scale-110">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-10 opacity-60">
                <p>No recent job openings available at the moment.</p>
            </div>
            @endforelse
        </div>

        <div class="text-center mt-12 reveal">
            <a href="{{ route('jobs') }}" class="inline-flex items-center justify-center gap-3 bg-[#129aef] text-white font-bold text-[15px] px-8 py-3.5 rounded-full hover:bg-[#031b4e] hover:-translate-y-1 transition-all duration-300 shadow-lg hover:shadow-xl group">
                View All Jobs 
                <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>
    </section>

    

    <!-- Testimonial Section -->
    <section class="py-20 px-6 lg:px-[5%] text-center relative bg-slate-50">
        
        <div class="mb-8 relative z-10 reveal">
            <h4 class="text-accent-yellow text-base font-medium mb-1.5 uppercase tracking-wider">Testimonial</h4>
            <h2 class="text-slate-800 text-3xl lg:text-4xl font-bold mb-4">What Our Clients Has To Say About Us</h2>
            <p class="max-w-2xl mx-auto text-[13px] text-slate-600 leading-relaxed">
                Discover first hand experiences as our satisfied clients share their testimonials about the exceptional
                recruitment services we provide. Hear what they have to say about our commitment to finding the right
                talent.
            </p>
        </div>

        @if(isset($testimonials) && count($testimonials) > 0)
        <style>
            @keyframes marqueeLeft {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
            @keyframes marqueeRight {
                0% { transform: translateX(-50%); }
                100% { transform: translateX(0); }
            }
            .animate-marquee-left {
                animation: marqueeLeft 35s linear infinite;
                display: flex;
                width: max-content;
            }
            .animate-marquee-right {
                animation: marqueeRight 35s linear infinite;
                display: flex;
                width: max-content;
            }
            .animate-marquee-left:hover, .animate-marquee-right:hover {
                animation-play-state: paused;
            }
            .testimonial-card-w {
                width: 380px;
                flex-shrink: 0;
                white-space: normal;
            }
            .fade-edges {
                -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
                mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
            }
        </style>

        <!-- Marquee Row 1 (Right to Left) -->
        <div class="overflow-hidden w-full relative z-10 mt-16 mb-12 py-6 fade-edges reveal">
            <div class="animate-marquee-left flex" style="padding-top: 50px;">
                @for($i = 0; $i < 2; $i++)
                <div class="flex gap-6 px-3">
                    @foreach($testimonials as $testimonial)
                    <div class="testimonial-card-w border border-blue-100 rounded-2xl p-8 pt-6 relative shadow-[0_8px_30px_rgba(0,0,0,0.06)] hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(18,154,239,0.15)] transition-all duration-300" style="background-color: #f0f8ff;">
                        <div class="absolute top-6 right-6 text-[#129aef]/10 text-4xl"><i class="fas fa-quote-right"></i></div>
                        <div class="w-16 h-16 rounded-full -mt-[60px] mx-auto mb-4 border-4 border-white relative overflow-hidden shadow-md flex items-center justify-center" style="background-color: #1e9af0;">
                            @if($testimonial->image_path)
                                <img src="{{ Storage::url($testimonial->image_path) }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover bg-white">
                            @else
                                <span class="text-xl font-bold text-white">{{ substr($testimonial->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <p class="text-[13px] text-slate-600 leading-relaxed mb-5 italic line-clamp-4 relative z-10">"{{ $testimonial->message }}"</p>
                        <div class="flex justify-center gap-1 text-accent-yellow text-[12px] mb-3 relative z-10">
                            @for($stars=0; $stars<$testimonial->rating; $stars++) <i class="fas fa-star"></i> @endfor
                            @for($stars=0; $stars<(5-$testimonial->rating); $stars++) <i class="far fa-star text-slate-300"></i> @endfor
                        </div>
                        <h4 class="text-slate-800 text-base font-extrabold mb-0.5 text-center relative z-10">{{ $testimonial->name }}</h4>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold text-center relative z-10">{{ $testimonial->role }}</p>
                    </div>
                    @endforeach
                </div>
                @endfor
            </div>
        </div>

        <!-- Marquee Row 2 (Left to Right) -->
        <div class="overflow-hidden w-full relative z-10 mb-16 py-6 fade-edges reveal reveal-delay-1">
            <div class="animate-marquee-right flex" style="padding-top: 50px;">
                @for($i = 0; $i < 2; $i++)
                <div class="flex gap-6 px-3">
                    @foreach($testimonials->reverse() as $testimonial)
                    <div class="testimonial-card-w border border-blue-100 rounded-2xl p-8 pt-6 relative shadow-[0_8px_30px_rgba(0,0,0,0.06)] hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(18,154,239,0.15)] transition-all duration-300" style="background-color: #f0f8ff;">
                        <div class="absolute top-6 right-6 text-[#129aef]/10 text-4xl"><i class="fas fa-quote-right"></i></div>
                        <div class="w-16 h-16 rounded-full -mt-[60px] mx-auto mb-4 border-4 border-white relative overflow-hidden shadow-md flex items-center justify-center" style="background-color: #1e9af0;">
                            @if($testimonial->image_path)
                                <img src="{{ Storage::url($testimonial->image_path) }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover bg-white">
                            @else
                                <span class="text-xl font-bold text-white">{{ substr($testimonial->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <p class="text-[13px] text-slate-600 leading-relaxed mb-5 italic line-clamp-4 relative z-10">"{{ $testimonial->message }}"</p>
                        <div class="flex justify-center gap-1 text-accent-yellow text-[12px] mb-3 relative z-10">
                            @for($stars=0; $stars<$testimonial->rating; $stars++) <i class="fas fa-star"></i> @endfor
                            @for($stars=0; $stars<(5-$testimonial->rating); $stars++) <i class="far fa-star text-slate-300"></i> @endfor
                        </div>
                        <h4 class="text-slate-800 text-base font-extrabold mb-0.5 text-center relative z-10">{{ $testimonial->name }}</h4>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold text-center relative z-10">{{ $testimonial->role }}</p>
                    </div>
                    @endforeach
                </div>
                @endfor
            </div>
        </div>
        @else
        <div class="text-center py-10 opacity-60 text-slate-800 relative z-10">
            <p>No testimonials available.</p>
        </div>
        @endif
    </section>

    <!-- Get In Touch Section (CTA Banner) -->
    <section class="py-12 px-6 lg:px-[5%] bg-slate-50" id="contact-section">
        <div class="flex flex-col lg:flex-row relative p-10 lg:p-14 text-white shadow-xl reveal group perspective-[1000px] min-h-[320px] overflow-hidden" style="background-color: #1e9af0; border-radius: 2.5rem;">
            <!-- Woven Isometric Background Pattern -->
            <div class="absolute inset-0 z-0 opacity-10 pointer-events-none mix-blend-overlay">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <g id="cube-pattern" stroke="white" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <!-- Cube Outlines -->
                            <path d="M30,0 L60,17.32 L60,51.96 L30,69.28 L0,51.96 L0,17.32 Z" />
                            <path d="M0,17.32 L30,34.64 L60,17.32 M30,34.64 L30,69.28" />
                            <!-- Woven Stripes -->
                            <path d="M10,11.54 L40,28.86 M20,5.77 L50,23.09" />
                            <path d="M10,23.09 L10,57.73 M20,28.86 L20,63.5" />
                            <path d="M40,28.86 L40,63.5 M50,23.09 L50,57.73" />
                        </g>
                        <pattern id="woven-cubes" width="60" height="103.923" patternUnits="userSpaceOnUse" patternTransform="scale(1.2)">
                            <use href="#cube-pattern" x="0" y="0" />
                            <use href="#cube-pattern" x="-30" y="51.9615" />
                            <use href="#cube-pattern" x="30" y="51.9615" />
                            <use href="#cube-pattern" x="-30" y="-51.9615" />
                            <use href="#cube-pattern" x="30" y="-51.9615" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#woven-cubes)" />
                </svg>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-12 opacity-80 hidden lg:block anim-float-h" style="left: 55%;">
                <svg width="60" height="15" viewBox="0 0 60 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 7.5L10 2L18 7.5L26 2L34 7.5L42 2L50 7.5L58 2" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="absolute bottom-10 opacity-60 hidden lg:block anim-bg-waves" style="left: 55%; animation-duration: 8s;">
                <div class="grid grid-cols-4 gap-3">
                    @for($i=0; $i<12; $i++)
                        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                    @endfor
                </div>
            </div>
            <div class="absolute top-1/2 right-[5%] opacity-40 hidden lg:block -translate-y-1/2 anim-float-h" style="animation-duration: 6s; animation-direction: reverse;">
                <div class="grid grid-cols-3 gap-3">
                    @for($i=0; $i<9; $i++)
                        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                    @endfor
                </div>
            </div>

            <!-- Additional Decorative Patterns -->
            <div class="absolute top-[20%] right-[30%] opacity-40 hidden lg:block anim-bg-waves" style="animation-duration: 12s;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round">
                    <path d="M12 2v20M2 12h20"/>
                </svg>
            </div>
            <div class="absolute bottom-[15%] left-[45%] opacity-30 hidden lg:block anim-float-h" style="animation-duration: 9s;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round">
                    <path d="M12 2v20M2 12h20"/>
                </svg>
            </div>
            <div class="absolute bottom-[20%] left-[8%] w-[40px] h-[40px] border-[3px] border-white/30 rounded-full z-0 hidden lg:block anim-bg-waves" style="animation-duration: 7s;"></div>
            
            <div class="absolute -bottom-16 -right-16 w-80 h-80 border-[1px] border-white/20 rounded-full z-0 pointer-events-none hidden lg:block"></div>

            <!-- Content -->
            <div class="w-full lg:w-[55%] z-20 relative text-center lg:text-left my-auto mb-8 lg:mb-0">
                <h2 class="text-3xl lg:text-4xl font-bold mb-3 tracking-wide">Get In Touch With Us</h2>
                <p class="text-[14px] lg:text-sm leading-relaxed mb-6 opacity-90 max-w-xl mx-auto lg:mx-0 font-medium">Have any queries or want to follow up? Fill out the form and our team will get back to you shortly.</p>
                
                <div id="form-messages" class="mb-4">
                    @if(session('success'))
                        <div class="bg-green-500/20 border border-green-500/50 text-slate-800 px-4 py-3 rounded text-sm text-left">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="bg-red-500/20 border border-red-500/50 text-slate-800 px-4 py-3 rounded text-sm text-left">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <form id="ajaxContactForm" action="{{ route('contact.store') }}" method="POST" class="text-left space-y-4 max-w-md mx-auto lg:mx-0">
                    @csrf
                    <div>
                        <input type="text" name="name" placeholder="Your Name" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-400 transition-colors">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="email" name="email" placeholder="Email Address" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-400 transition-colors">
                        <input type="text" name="phone" placeholder="Phone Number" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-400 transition-colors">
                    </div>
                    <div>
                        <textarea name="message" rows="3" placeholder="How can we help you?" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-400 transition-colors"></textarea>
                    </div>
                    <button type="submit" id="submitBtn" class="text-slate-900 px-8 py-3 rounded-full font-bold text-sm hover:-translate-y-1 transition-all duration-300 shadow-lg hover:shadow-xl w-full md:w-auto flex justify-center items-center gap-2" style="background-color: #ffcc00;">
                        <span>Submit Query</span> <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>

            <!-- Absolute Image clipped to the box size -->
            <div class="absolute bottom-0 -right-4 lg:-right-8 z-30 hidden md:block transition-transform duration-700 ease-out transform group-hover:scale-105 origin-bottom pointer-events-none">
                <img src="{{ asset('images/pic.png') }}"
                    alt="Contact Support"
                    class="w-auto h-[400px] lg:h-[500px] object-contain object-bottom transition-all duration-700">
            </div>
        </div>
    </section>

<!-- We Are Available On Section -->
    <section class="py-12 bg-slate-100 overflow-hidden">
        <div class="mb-10 text-center px-6 lg:px-[5%] reveal">
            <h2 class="text-slate-800 text-2xl font-bold">We are available on</h2>
        </div>
        <div class="swiper marquee-swiper reveal">
            <div class="swiper-wrapper items-center">
                @forelse($clients as $client)
                <div class="swiper-slide w-auto">
                    <div class="bg-white border border-slate-200 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/60 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <img src="{{ Storage::url($client->logo_path) }}" alt="{{ $client->name }}" class="max-h-12 max-w-[150px] object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300">
                    </div>
                </div>
                @empty
                <div class="swiper-slide !w-full flex justify-center text-center text-slate-800 py-4 opacity-60">
                    <p>No client partners available.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Certifications We Have Section -->
    <section class="py-16 px-6 lg:px-[5%] bg-slate-50 text-center border-t border-slate-200">
        <div class="mb-10 reveal">
            <h2 class="text-slate-800 text-2xl lg:text-3xl font-bold mb-2 tracking-wide uppercase">Certifications We
                Have</h2>
            <p class="text-slate-500 text-xs">Verified standards empowering educational careers worldwide.</p>
        </div>
        <style>
            @media (min-width: 768px) {
                .cert-step-1 { margin-top: 0px !important; }
                .cert-step-2 { margin-top: 40px !important; }
                .cert-step-3 { margin-top: 80px !important; }
                .cert-step-4 { margin-top: 40px !important; }
                .cert-step-5 { margin-top: 0px !important; }
            }
            .cert-card-hover {
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                border: 2px solid transparent;
            }
            .cert-card-hover:hover {
                transform: translateY(-12px);
                box-shadow: 0 20px 40px rgba(30, 154, 240, 0.15);
                border-color: #1e9af0;
            }
            .cert-card-hover img {
                transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }
            .cert-card-hover:hover img {
                transform: scale(1.15);
            }
            .cert-card-hover .badge-verified {
                transition: all 0.3s ease;
            }
            .cert-card-hover:hover .badge-verified {
                background-color: #1e9af0;
                color: white;
            }
        </style>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 lg:gap-6 items-start pb-20">
            <!-- Card 1 -->
            <div
                class="bg-white rounded-xl p-6 flex flex-col items-center shadow-md reveal reveal-delay-1 cert-step-1 cert-card-hover group">
                <div class="h-16 mb-4 flex items-center justify-center">
                    <img src="https://api.cookingbook.in/wp-content/uploads/2026/03/msme.jpg" class="max-h-full max-w-full object-contain" alt="MSME">
                </div>
                <h3 class="font-bold text-sm mb-3 text-slate-800">MSME</h3>
                <div
                    class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-semibold flex items-center gap-1 badge-verified">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
            </div>
            <!-- Card 2 -->
            <div
                class="bg-white rounded-xl p-6 flex flex-col items-center shadow-md reveal reveal-delay-2 cert-step-2 cert-card-hover group">
                <div class="h-16 mb-4 flex items-center justify-center">
                    <img src="https://api.cookingbook.in/wp-content/uploads/2026/03/NCS.jpg" class="max-h-full max-w-full object-contain" alt="NCS">
                </div>
                <h3 class="font-bold text-sm mb-3 text-slate-800">NCS</h3>
                <div
                    class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-semibold flex items-center gap-1 badge-verified">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
            </div>
            <!-- Card 3 -->
            <div
                class="bg-white rounded-xl p-6 flex flex-col items-center shadow-md reveal reveal-delay-3 cert-step-3 cert-card-hover group">
                <div class="h-16 mb-4 flex items-center justify-center">
                    <img src="https://api.cookingbook.in/wp-content/uploads/2026/03/ISO-Logo-e1773586209102.png" class="max-h-full max-w-full object-contain" alt="ISO">
                </div>
                <h3 class="font-bold text-sm mb-3 text-slate-800">ISO</h3>
                <div
                    class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-semibold flex items-center gap-1 badge-verified">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
            </div>
            <!-- Card 4 -->
            <div
                class="bg-white rounded-xl p-6 flex flex-col items-center shadow-md reveal reveal-delay-4 cert-step-4 cert-card-hover group">
                <div class="h-16 mb-4 flex items-center justify-center">
                    <img src="https://api.cookingbook.in/wp-content/uploads/2026/03/IEC.png" class="max-h-full max-w-full object-contain" alt="IEC">
                </div>
                <h3 class="font-bold text-sm mb-3 text-slate-800">IEC</h3>
                <div
                    class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-semibold flex items-center gap-1 badge-verified">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
            </div>
            <!-- Card 5 -->
            <div
                class="bg-white rounded-xl p-6 flex flex-col items-center shadow-md reveal reveal-delay-1 cert-step-5 cert-card-hover group">
                <div class="h-16 mb-4 flex items-center justify-center">
                    <img src="https://api.cookingbook.in/wp-content/uploads/2026/03/standup-india.jpg" class="max-h-full max-w-full object-contain" alt="START UP INDIA">
                </div>
                <h3 class="font-bold text-sm mb-3 text-slate-800">START UP INDIA</h3>
                <div
                    class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-semibold flex items-center gap-1 badge-verified">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
            </div>
        </div>
    </section>

    @endsection

@push('scripts')
<script>
    document.getElementById('ajaxContactForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let form = this;
        let submitBtn = document.getElementById('submitBtn');
        let originalBtnText = submitBtn.innerHTML;
        let msgContainer = document.getElementById('form-messages');
        let formData = new FormData(form);

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        submitBtn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;

            if (data.success || data.message) {
                let msg = data.message || 'Thank you! Your message has been sent.';
                msgContainer.innerHTML = `<div class="bg-green-500/20 border border-green-500/50 text-slate-800 px-4 py-3 rounded text-sm text-left animate-fade-in"><i class="fas fa-check-circle mr-2"></i> ${msg}</div>`;
                form.reset();
                setTimeout(() => { msgContainer.innerHTML = ''; }, 5000);
            } else if (data.errors) {
                let errorsHtml = '<ul class="list-disc pl-5">';
                for(let key in data.errors) {
                    errorsHtml += `<li>${data.errors[key][0]}</li>`;
                }
                errorsHtml += '</ul>';
                msgContainer.innerHTML = `<div class="bg-red-500/20 border border-red-500/50 text-slate-800 px-4 py-3 rounded text-sm text-left animate-fade-in">${errorsHtml}</div>`;
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            msgContainer.innerHTML = `<div class="bg-red-500/20 border border-red-500/50 text-slate-800 px-4 py-3 rounded text-sm text-left animate-fade-in"><i class="fas fa-exclamation-triangle mr-2"></i> Something went wrong. Please try again.</div>`;
            console.error('Error:', error);
        });
    });

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
