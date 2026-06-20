@extends('layouts.app')

@section('content')
<!-- Hero Section -->
    <section
        class="bg-primary-bg relative min-h-[82vh] flex flex-col lg:flex-row items-center px-6 lg:px-[6%] py-16 lg:py-0 overflow-hidden">
        <div class="hero-bg-pattern absolute inset-0 z-0"></div>
        
        <!-- Animated Background SVGs -->
        <svg class="absolute top-[5%] -left-[5%] w-[400px] h-[400px] text-accent-blue/10 animate-float-slow z-0" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="currentColor" d="M42.7,-68.8C54.1,-58.5,61.4,-43.5,69.5,-28.3C77.6,-13.2,86.5,2.1,83.9,15.6C81.2,29.1,67.1,40.8,53.2,50.7C39.4,60.6,25.8,68.7,10.6,73.1C-4.7,77.5,-21.5,78.2,-36.8,72.7C-52.1,67.2,-65.9,55.5,-74.6,40.5C-83.3,25.6,-86.9,7.5,-83.4,-9.1C-79.8,-25.6,-69,-40.7,-55.4,-51.1C-41.8,-61.6,-25.4,-67.4,-10.1,-69.6C5.3,-71.8,20.5,-70.5,31.3,-79.1Z" transform="translate(100 100)" />
        </svg>

        <svg class="absolute bottom-[5%] left-[30%] w-[300px] h-[300px] text-white/5 animate-float z-0" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="currentColor" d="M51.8,-66.4C64.9,-54.6,71.8,-35.1,75.4,-15C79,5.1,79.2,25.9,69.5,41.9C59.7,57.9,40,69.1,19.3,73.2C-1.4,77.3,-23.1,74.3,-41.8,64.2C-60.6,54.1,-76.3,37,-82.1,17.2C-87.9,-2.7,-83.7,-25.3,-71,-41.3C-58.3,-57.2,-37,-66.5,-18.2,-68.4C0.5,-70.3,19.2,-64.8,38.6,-78.2Z" transform="translate(100 100)" />
        </svg>

        <svg class="absolute top-[20%] right-[40%] w-[150px] h-[150px] text-accent-yellow/10 animate-pulse-soft z-0" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="currentColor" d="M39.6,-62.4C51.5,-54.2,61.4,-41.9,69.1,-27.6C76.8,-13.3,82.3,3.1,78.8,17.4C75.3,31.7,62.8,44,50.1,54.6C37.4,65.2,24.5,74.1,10.2,76.5C-4.1,78.9,-19.8,74.8,-33.5,66.6C-47.2,58.4,-58.9,46.1,-67.6,32C-76.3,17.9,-82,2,-79.8,-12.3C-77.6,-26.6,-67.5,-39.3,-55.1,-48.5C-42.7,-57.7,-28,-63.4,-13.6,-66.3C0.8,-69.2,15.6,-69.3,27.7,-70.6Z" transform="translate(100 100)" />
        </svg>

        <div class="hero-waves absolute top-[10%] left-[20%] w-[300px] h-[400px] z-0 opacity-50 hidden lg:block"></div>

        <div
            class="flex-1 z-10 pr-0 lg:pr-12 text-text-main flex flex-col items-center lg:items-start text-center lg:text-left mb-14 lg:mb-0">
            <h1 id="hero-title"
                class="text-4xl lg:text-[52px] font-extrabold leading-[1.15] mb-5 transition-all duration-500 ease-in-out animate-slide-in-left">
                Get placed in top<br>schools across...</h1>
            <p id="hero-subtitle"
                class="text-base lg:text-lg font-light max-w-[480px] leading-relaxed mb-8 text-white/75 transition-all duration-500 ease-in-out animate-slide-in-right" style="animation-delay: 0.2s; opacity: 0;">
                step into the right opportunity with trusted schools that value your talent</p>

            <a href="#" id="hero-cta"
                class="bg-accent-yellow text-[#031b4e] px-5 py-2.5 rounded-full font-semibold text-sm inline-flex items-center gap-3 transition-all duration-300 hover:scale-105 shadow-glow-yellow hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.4s; opacity: 0;">
                <span id="cta-text">Job Seeker</span>
                <span class="bg-white w-6 h-6 rounded-full flex items-center justify-center text-[#031b4e] text-xs"><i
                        class="fas fa-chevron-right"></i></span>
            </a>

            <div class="mt-10 animate-zoom-in" style="animation-delay: 0.6s; opacity: 0;">
                <h3 class="text-2xl font-bold mb-4 text-white/90">I am a</h3>
                <div class="bg-white/10 glass inline-flex rounded-2xl p-1.5">
                    <button id="btn-seeker" onclick="toggleRole('seeker')"
                        class="role-btn px-5 lg:px-8 py-3 rounded-xl text-sm font-semibold flex items-center gap-2.5 transition-all duration-300 border-none cursor-pointer bg-accent-blue text-white shadow-glow-blue">
                        <i class="fas fa-user-tie text-xs"></i> Job Seeker
                    </button>
                    <button id="btn-employer" onclick="toggleRole('employer')"
                        class="role-btn px-5 lg:px-8 py-3 rounded-xl text-sm font-semibold text-text-main opacity-70 flex items-center gap-2.5 transition-all duration-300 border-none cursor-pointer bg-transparent hover:text-text-main hover:bg-white/10">
                        <i class="fas fa-building text-xs"></i> Employer
                    </button>
                </div>
            </div>
        </div>

        <div class="flex-1 relative z-10 flex justify-center items-center h-[420px] lg:h-[540px] w-full">
            <div id="ring-1"
                class="absolute w-[380px] h-[380px] lg:w-[480px] lg:h-[480px] border-[24px] border-accent-blue border-b-transparent border-l-transparent rounded-full -top-4 -right-1 rotate-[-15deg] transition-transform duration-1000 ease-in-out opacity-80 animate-[spin_40s_linear_infinite]">
            </div>
            <div id="ring-2"
                class="absolute w-[420px] h-[420px] lg:w-[530px] lg:h-[530px] border-[4px] border-accent-yellow border-b-transparent border-l-transparent rounded-full -top-9 -right-7 rotate-[-15deg] transition-transform duration-1000 ease-in-out opacity-60 animate-[spin_30s_linear_infinite_reverse]">
            </div>

            <div
                class="w-[300px] h-[300px] lg:w-[400px] lg:h-[400px] bg-[#e5e5e5] rounded-full relative flex justify-center items-end overflow-hidden transition-transform duration-500 ease-[cubic-bezier(0.175,0.885,0.32,1.275)]">
                <img id="hero-img"
                    src="https://images.unsplash.com/photo-1556157382-97eda2d62296?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                    alt="Professional" class="w-full h-full object-cover transition-all duration-500 ease-in-out">
            </div>

            <!-- Floating Card 1 -->
            <div id="fc-1"
                class="absolute bg-card-bg border border-card-border px-4 py-3.5 rounded-xl shadow-card animate-float transition-all duration-500 ease-in-out top-8 lg:top-[80px] left-0 lg:-left-4 flex flex-col items-center">
                <div id="fc-1-icon-wrap"
                    class="bg-accent-yellow w-10 h-10 rounded-lg flex items-center justify-center text-text-main text-lg mb-2 transition-colors duration-400">
                    <i id="fc-1-icon" class="fas fa-briefcase"></i>
                </div>
                <h4 id="fc-1-title" class="text-lg font-extrabold text-text-main">20K +</h4>
                <p id="fc-1-desc" class="text-[11px] text-text-main opacity-50">Job Vacancy</p>
            </div>

            <!-- Floating Card 2 -->
            <div id="fc-2"
                class="absolute bg-card-bg border border-card-border px-4 py-3.5 rounded-xl shadow-card animate-float transition-all duration-500 ease-in-out bottom-2 lg:bottom-[60px] right-0 lg:-right-6"
                style="animation-delay: 2.5s;">
                <h4 id="fc-2-title" class="text-sm font-extrabold mb-0.5 text-text-main">1+ Million</h4>
                <p id="fc-2-desc" class="text-[10px] text-text-main opacity-50 mb-2">Trusted User</p>
                <div id="fc-2-avatars" class="flex items-center">
                    <img src="https://i.pravatar.cc/100?img=11" alt="User"
                        class="w-7 h-7 rounded-full border-2 border-white first:ml-0">
                    <img src="https://i.pravatar.cc/100?img=32" alt="User"
                        class="w-7 h-7 rounded-full border-2 border-white -ml-2">
                    <img src="https://i.pravatar.cc/100?img=44" alt="User"
                        class="w-7 h-7 rounded-full border-2 border-white -ml-2">
                    <img src="https://i.pravatar.cc/100?img=55" alt="User"
                        class="w-7 h-7 rounded-full border-2 border-white -ml-2">
                    <div
                        class="w-7 h-7 rounded-full bg-accent-yellow text-[#031b4e] flex items-center justify-center font-bold border-2 border-white -ml-2 text-[10px]">
                        +</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Clients -->
    <section class="bg-secondary-bg py-8 overflow-hidden">
        <div class="text-left mb-6 px-6 lg:px-[5%] reveal">
            <h2 class="text-2xl font-bold text-text-dark">Our Clients</h2>
        </div>
        <div class="swiper marquee-swiper reveal">
            <div class="swiper-wrapper items-center">
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-red-400 text-sm text-center leading-tight">BIRLA<br>OPEN MINDS</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-text-main text-sm flex items-center gap-1.5">
                            <i class="fas fa-graduation-cap text-red-600 text-xs"></i> D. GOENKA
                        </span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-blue-400 text-xs leading-tight text-center">Mount Litera<br>Zee
                            School</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="bg-white/10 text-text-main rounded-full w-9 h-9 flex justify-center items-center font-bold text-lg">
                            PW</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="border-2 border-accent-yellow rounded-full w-10 h-10 flex justify-center items-center font-extrabold text-primary-bg text-[11px]">
                            ALLEN</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-indigo-300 text-base tracking-tight">VMC <span
                                class="text-xs font-normal">Classes</span></span>
                    </div>
                </div>
            
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-red-400 text-sm text-center leading-tight">BIRLA<br>OPEN MINDS</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-text-main text-sm flex items-center gap-1.5">
                            <i class="fas fa-graduation-cap text-red-600 text-xs"></i> D. GOENKA
                        </span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-blue-400 text-xs leading-tight text-center">Mount Litera<br>Zee
                            School</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="bg-white/10 text-text-main rounded-full w-9 h-9 flex justify-center items-center font-bold text-lg">
                            PW</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="border-2 border-accent-yellow rounded-full w-10 h-10 flex justify-center items-center font-extrabold text-primary-bg text-[11px]">
                            ALLEN</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-indigo-300 text-base tracking-tight">VMC <span
                                class="text-xs font-normal">Classes</span></span>
                    </div>
                </div>
            
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-red-400 text-sm text-center leading-tight">BIRLA<br>OPEN MINDS</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-text-main text-sm flex items-center gap-1.5">
                            <i class="fas fa-graduation-cap text-red-600 text-xs"></i> D. GOENKA
                        </span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-blue-400 text-xs leading-tight text-center">Mount Litera<br>Zee
                            School</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="bg-white/10 text-text-main rounded-full w-9 h-9 flex justify-center items-center font-bold text-lg">
                            PW</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="border-2 border-accent-yellow rounded-full w-10 h-10 flex justify-center items-center font-extrabold text-primary-bg text-[11px]">
                            ALLEN</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-indigo-300 text-base tracking-tight">VMC <span
                                class="text-xs font-normal">Classes</span></span>
                    </div>
                </div>
            
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-red-400 text-sm text-center leading-tight">BIRLA<br>OPEN MINDS</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-text-main text-sm flex items-center gap-1.5">
                            <i class="fas fa-graduation-cap text-red-600 text-xs"></i> D. GOENKA
                        </span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-blue-400 text-xs leading-tight text-center">Mount Litera<br>Zee
                            School</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="bg-white/10 text-text-main rounded-full w-9 h-9 flex justify-center items-center font-bold text-lg">
                            PW</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <div
                            class="border-2 border-accent-yellow rounded-full w-10 h-10 flex justify-center items-center font-extrabold text-primary-bg text-[11px]">
                            ALLEN</div>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-indigo-300 text-base tracking-tight">VMC <span
                                class="text-xs font-normal">Classes</span></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Jobs Section -->
    <section class="py-16 px-6 lg:px-[5%] bg-primary-bg text-text-main relative">
        <div class="text-center mb-12 reveal">
            <h4 class="text-accent-blue text-base font-medium mb-1.5 uppercase tracking-wider">Latest Jobs</h4>
            <h2 class="text-text-main text-3xl lg:text-4xl font-bold mb-4">Explore Recent Opportunities</h2>
            <div class="zigzag-divider w-16 h-2 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
            @forelse($recentJobs as $job)
            <div
                class="bg-card-bg border border-card-border rounded-2xl p-6 text-text-dark transition-all duration-300 hover:-translate-y-1.5 shadow-card hover:shadow-card-hover flex flex-col group reveal">
                <h3 class="text-lg font-bold mb-3 text-text-main">{{ $job->title ?? 'Job Requirement' }}</h3>
                <p class="text-xs text-text-main opacity-60 mb-4 flex items-center gap-3">
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
                <p class="text-[13px] text-text-main opacity-60 leading-relaxed mb-6 flex-grow">
                    {{ Str::limit($job->description, 100) }}
                </p>
                <a href="#"
                    class="text-accent-blue font-semibold text-[13px] inline-flex items-center gap-2 self-start group-hover:gap-3 transition-all">View
                    Details <span
                        class="bg-accent-yellow text-[#031b4e] w-5 h-5 rounded-full flex items-center justify-center text-[9px]"><i
                            class="fas fa-chevron-right"></i></span></a>
            </div>
            @empty
            <div class="col-span-full text-center py-10 opacity-60">
                <p>No recent job openings available at the moment.</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-14 px-6 lg:px-[5%] relative bg-cover bg-center bg-fixed" style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
        <div class="absolute inset-0 bg-[#0f172a]/90 backdrop-blur-sm z-0"></div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 relative z-10">
            @foreach($categories as $category)
            <div
                class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 text-center text-text-main transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:border-accent-blue/50 cursor-pointer group reveal">
                <i class="fas fa-folder-open text-3xl mb-4 block text-accent-blue group-hover:scale-110 transition-transform"></i>
                <h3 class="text-sm font-semibold mb-4">{{ $category->name }}</h3>
                <div class="bg-white/20 backdrop-blur-md text-text-main px-3 py-1.5 rounded-full text-[11px] font-semibold inline-block">
                    {{ $category->jobs_count }} Active Jobs
                </div>
            </div>
            @endforeach
        </div>
    </section>

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

    <!-- Testimonial Section -->
    <section class="py-16 px-6 lg:px-[5%] text-center relative bg-cover bg-center bg-fixed" style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
        <div class="absolute inset-0 bg-secondary-bg opacity-95 backdrop-blur-[2px] z-0"></div>
        <div class="mb-8 relative z-10 reveal">
            <h4 class="text-accent-yellow text-base font-medium mb-1.5 uppercase tracking-wider">Testimonial</h4>
            <h2 class="text-text-main text-3xl lg:text-4xl font-bold mb-4">What Our Clients Has To Say About Us</h2>
            <p class="max-w-2xl mx-auto text-[13px] text-text-main opacity-70 leading-relaxed">
                Discover first hand experiences as our satisfied clients share their testimonials about the exceptional
                recruitment services we provide. Hear what they have to say about our commitment to finding the right
                talent.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16 px-0 lg:px-4 relative z-10">
            @forelse($testimonials as $index => $testimonial)
            <div class="{{ $index % 2 == 1 ? 'bg-gradient-to-b from-accent-blue to-accent-blue-hover backdrop-blur-xl border border-white/20 rounded-2xl p-7 pt-6 relative shadow-2xl transform scale-100 md:scale-105 hover:md:scale-[1.08] transition-all duration-300' : 'bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-7 pt-6 relative shadow-card hover:-translate-y-1.5 hover:shadow-card-hover transition-all duration-300' }} reveal reveal-delay-{{ ($index % 3) + 1 }}">
                <div class="absolute top-5 right-6 text-white/20 text-2xl"><i class="fas fa-quote-right"></i></div>
                <div class="w-14 h-14 rounded-full -mt-[52px] mx-auto mb-4 border-4 {{ $index % 2 == 1 ? 'border-accent-blue' : 'border-secondary-bg' }} relative bg-white overflow-hidden shadow-lg flex items-center justify-center">
                    @if($testimonial->image_path)
                        <img src="{{ Storage::url($testimonial->image_path) }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-xl font-bold text-indigo-600">{{ substr($testimonial->name, 0, 1) }}</span>
                    @endif
                </div>
                <p class="text-[13px] {{ $index % 2 == 1 ? 'text-white/90' : 'text-text-main opacity-80' }} leading-relaxed mb-4 italic">"{{ $testimonial->message }}"</p>
                <div class="flex justify-center gap-1 text-accent-yellow text-[11px] mb-3">
                    @for($i=0; $i<$testimonial->rating; $i++) <i class="fas fa-star"></i> @endfor
                    @for($i=0; $i<(5-$testimonial->rating); $i++) <i class="far fa-star {{ $index % 2 == 1 ? 'text-white/50' : 'text-text-main opacity-50' }}"></i> @endfor
                </div>
                <h4 class="{{ $index % 2 == 1 ? 'text-white shadow-sm' : 'text-text-main' }} text-sm font-bold mb-0.5">{{ $testimonial->name }}</h4>
                <p class="text-[10px] {{ $index % 2 == 1 ? 'text-white opacity-80' : 'text-accent-blue' }} uppercase tracking-wider font-semibold">{{ $testimonial->role }}</p>
            </div>
            @empty
            <div class="col-span-full text-center py-10 opacity-60 text-white">
                <p>No testimonials available.</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- We Are Available On Section -->
    <section class="py-12 bg-secondary-bg overflow-hidden">
        <div class="mb-8 text-center px-6 lg:px-[5%] reveal">
            <h2 class="text-white text-2xl font-bold">We are available on</h2>
        </div>
        <div class="swiper marquee-swiper reveal">
            <div class="swiper-wrapper items-center">
                @forelse($clients as $client)
                <div class="swiper-slide w-auto">
                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <img src="{{ Storage::url($client->logo_path) }}" alt="{{ $client->name }}" class="max-h-12 max-w-[150px] object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300">
                    </div>
                </div>
                @empty
                <div class="swiper-slide !w-full flex justify-center text-center text-white py-4 opacity-60">
                    <p>No client partners available.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Certifications We Have Section -->
    <section class="py-14 px-6 lg:px-[5%] bg-primary-bg text-center">
        <div class="mb-10 reveal">
            <h2 class="text-text-main text-2xl lg:text-3xl font-bold mb-2 tracking-wide uppercase">Certifications We
                Have</h2>
            <p class="text-text-main opacity-60 text-xs">Verified standards empowering educational careers worldwide.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div
                class="bg-card-bg border border-card-border rounded-xl p-6 flex flex-col items-center shadow-card hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300 reveal reveal-delay-1">
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 mb-3">
                    <i class="fas fa-certificate text-xl"></i>
                </div>
                <h3 class="font-bold text-sm mb-3 text-text-main">MSME</h3>
                <div
                    class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-semibold flex items-center gap-1">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
            </div>
            <div
                class="bg-card-bg border border-card-border rounded-xl p-6 flex flex-col items-center shadow-card hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300 reveal reveal-delay-2">
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-500 mb-3">
                    <i class="fas fa-briefcase text-xl"></i>
                </div>
                <h3 class="font-bold text-sm mb-3 text-text-dark">NCS</h3>
                <div
                    class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-semibold flex items-center gap-1">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
            </div>
            <div
                class="bg-card-bg border border-card-border rounded-xl p-6 flex flex-col items-center shadow-card hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300 reveal reveal-delay-3">
                <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-700 mb-3">
                    <i class="fas fa-globe text-xl"></i>
                </div>
                <h3 class="font-bold text-sm mb-3 text-text-dark">ISO</h3>
                <div
                    class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-semibold flex items-center gap-1">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
            </div>
            <div
                class="bg-card-bg border border-card-border rounded-xl p-6 flex flex-col items-center shadow-card hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300 reveal reveal-delay-4">
                <div
                    class="w-12 h-12 bg-blue-50 rounded-sm flex items-center justify-center text-blue-700 mb-3 font-bold text-base border border-blue-200">
                    IEC
                </div>
                <h3 class="font-bold text-sm mb-3 text-text-dark">IEC</h3>
                <div
                    class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-semibold flex items-center gap-1">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
            </div>
            <div
                class="bg-card-bg border border-card-border rounded-xl p-6 flex flex-col items-center shadow-card hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300 reveal reveal-delay-1">
                <div class="mb-3 text-center leading-none text-sm">
                    <span class="text-orange-500 font-bold">START</span><span
                        class="text-blue-500 font-bold">UP</span><br>
                    <span class="text-green-600 font-bold">INDIA</span>
                </div>
                <h3 class="font-bold text-sm mb-3 text-text-dark">START UP INDIA</h3>
                <div
                    class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-semibold flex items-center gap-1">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
            </div>
        </div>
    </section>

    <!-- Get In Touch Section (Contact Form) -->
    <section class="py-10 px-6 lg:px-[5%] pb-16 bg-secondary-bg" id="contact-section">
        <div class="bg-gradient-to-r from-primary-bg to-accent-blue/90 border border-white/10 rounded-3xl flex flex-col lg:flex-row items-center relative overflow-hidden p-8 lg:p-12 text-text-main shadow-2xl reveal">
            <div class="git-bg-pattern absolute inset-0 z-0"></div>
            
            <div class="flex-1 z-20 pr-0 lg:pr-8 mb-8 lg:mb-0 text-center lg:text-left">
                <h2 class="text-3xl font-bold mb-4">Get In Touch With Us</h2>
                <p class="text-sm leading-relaxed mb-6 opacity-85">Have any queries or want to follow up? Fill out the form and our team will get back to you shortly.</p>
                
                <div id="form-messages" class="mb-4">
                    @if(session('success'))
                        <div class="bg-green-500/20 border border-green-500/50 text-white px-4 py-3 rounded text-sm text-left">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="bg-red-500/20 border border-red-500/50 text-white px-4 py-3 rounded text-sm text-left">
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
                        <input type="text" name="name" placeholder="Your Name" required class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-sm text-white placeholder-white/60 focus:outline-none focus:border-accent-yellow transition-colors">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="email" name="email" placeholder="Email Address" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-sm text-white placeholder-white/60 focus:outline-none focus:border-accent-yellow transition-colors">
                        <input type="text" name="phone" placeholder="Phone Number" required class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-sm text-white placeholder-white/60 focus:outline-none focus:border-accent-yellow transition-colors">
                    </div>
                    <div>
                        <textarea name="message" rows="3" placeholder="How can we help you?" required class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-sm text-white placeholder-white/60 focus:outline-none focus:border-accent-yellow transition-colors"></textarea>
                    </div>
                    <button type="submit" id="submitBtn" class="bg-accent-yellow text-[#031b4e] px-8 py-3 rounded-full font-bold text-sm hover:-translate-y-1 hover:shadow-glow-yellow transition-all w-full md:w-auto flex justify-center items-center gap-2">
                        <span>Submit Query</span> <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>

            <div class="flex-1 z-20 flex justify-center lg:justify-end relative hidden md:flex">
                <img src="https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                    alt="Contact Us"
                    class="relative lg:absolute -bottom-12 right-0 max-h-[350px] rounded-2xl shadow-2xl object-cover">
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
                msgContainer.innerHTML = `<div class="bg-green-500/20 border border-green-500/50 text-white px-4 py-3 rounded text-sm text-left animate-fade-in"><i class="fas fa-check-circle mr-2"></i> ${msg}</div>`;
                form.reset();
                setTimeout(() => { msgContainer.innerHTML = ''; }, 5000);
            } else if (data.errors) {
                let errorsHtml = '<ul class="list-disc pl-5">';
                for(let key in data.errors) {
                    errorsHtml += `<li>${data.errors[key][0]}</li>`;
                }
                errorsHtml += '</ul>';
                msgContainer.innerHTML = `<div class="bg-red-500/20 border border-red-500/50 text-white px-4 py-3 rounded text-sm text-left animate-fade-in">${errorsHtml}</div>`;
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            msgContainer.innerHTML = `<div class="bg-red-500/20 border border-red-500/50 text-white px-4 py-3 rounded text-sm text-left animate-fade-in"><i class="fas fa-exclamation-triangle mr-2"></i> Something went wrong. Please try again.</div>`;
            console.error('Error:', error);
        });
    });
</script>
@endpush
