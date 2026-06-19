<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vedanta Placement Agency — The Gold Standard in Education Recruitment</title>
    <meta name="description"
        content="Vedanta Placement Agency connects educators and schools across India. Find top teaching jobs or hire expert educators with us.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .marquee-swiper {
            -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        }
        .marquee-swiper .swiper-wrapper {
            transition-timing-function: linear !important;
        }
        .marquee-swiper .swiper-slide {
            width: auto !important;
        }
        body {
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
        }

        .hero-bg-pattern {
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.04) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(18, 154, 239, 0.08) 0%, transparent 40%);
        }

        .hero-waves {
            background-image: repeating-linear-gradient(transparent, transparent 10px, rgba(255, 255, 255, 0.03) 10px, rgba(255, 255, 255, 0.03) 11px);
            mask-image: radial-gradient(circle, black 40%, transparent 100%);
            -webkit-mask-image: radial-gradient(circle, black 40%, transparent 100%);
        }

        .zigzag-divider {
            background-image: linear-gradient(135deg, transparent 25%, white 25%, white 50%, transparent 50%, transparent 75%, white 75%, white 100%);
            background-size: 10px 10px;
        }

        .git-bg-pattern {
            background-image:
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.08) 0%, transparent 20%),
                radial-gradient(circle at 10% 80%, rgba(255, 255, 255, 0.04) 0%, transparent 30%);
        }

        .fade-out {
            opacity: 0;
            transform: scale(0.95);
        }

        .float-fade-out {
            opacity: 0 !important;
            transform: translateY(20px) !important;
        }

        .shadow-glow-blue {
            box-shadow: 0 4px 15px rgba(18, 154, 239, 0.25);
        }

        .shadow-glow-yellow {
            box-shadow: 0 8px 20px rgba(255, 184, 0, 0.25);
        }

        .shadow-card {
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        .shadow-card-hover {
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.10);
        }

        /* Scroll-triggered animations via IntersectionObserver */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: all 0.7s cubic-bezier(.22, .61, .36, 1);
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-delay-1 {
            transition-delay: 0.1s;
        }

        .reveal-delay-2 {
            transition-delay: 0.2s;
        }

        .reveal-delay-3 {
            transition-delay: 0.3s;
        }

        .reveal-delay-4 {
            transition-delay: 0.4s;
        }

        /* Glassmorphism utility */
        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        /* Smooth header on scroll */
        .header-scrolled {
            background: rgba(3, 27, 78, 0.92) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body class="bg-secondary-bg text-text-dark">

    <!-- Top Contact Bar -->
    <div
        class="bg-[#021a3d] text-white/80 text-xs py-2 px-6 lg:px-[5%] hidden md:flex justify-between items-center border-b border-white/5">
        <div class="flex items-center gap-6">
            <span class="flex items-center gap-1.5"><i class="fas fa-envelope text-accent-blue text-[10px]"></i>
                info@vedantaplacementagency.in</span>
            <span class="flex items-center gap-1.5"><i class="fas fa-phone-alt text-accent-blue text-[10px]"></i>
                +91-7070938975</span>
            <span class="flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-accent-blue text-[10px]"></i>
                Patna, Bihar, India</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="#" class="hover:text-accent-blue transition-colors"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="hover:text-accent-blue transition-colors"><i class="fab fa-instagram"></i></a>
            <a href="#" class="hover:text-accent-blue transition-colors"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" class="hover:text-accent-blue transition-colors"><i class="fab fa-youtube"></i></a>
            <a href="#" class="hover:text-accent-blue transition-colors"><i class="fab fa-whatsapp"></i></a>
        </div>
    </div>

    <!-- Header (Sticky) -->
    <header id="main-header"
        class="sticky top-0 bg-primary-bg px-6 py-4 lg:px-[5%] flex justify-between items-center z-50 transition-all duration-500">
        <a href="#" class="flex items-center no-underline">
            <img src="/images/logo.png" alt="Vedanta Placement Agency" class="h-12 w-auto object-contain">
        </a>
        <nav class="hidden lg:flex items-center">
            <ul class="flex gap-6 mr-8 list-none">
                <li><a href="#"
                        class="text-white font-medium text-[13px] transition-all hover:text-accent-blue relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-[2px] after:bg-accent-blue after:transition-all hover:after:w-full">Home</a>
                </li>
                <li><a href="#"
                        class="text-white/80 font-medium text-[13px] transition-all hover:text-accent-blue relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-[2px] after:bg-accent-blue after:transition-all hover:after:w-full">About
                        us</a></li>
                <li><a href="#"
                        class="text-white/80 font-medium text-[13px] transition-all hover:text-accent-blue relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-[2px] after:bg-accent-blue after:transition-all hover:after:w-full">Our
                        Services</a></li>
                <li><a href="#"
                        class="text-white/80 font-medium text-[13px] transition-all hover:text-accent-blue relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-[2px] after:bg-accent-blue after:transition-all hover:after:w-full">Jobs</a>
                </li>
                <li><a href="#"
                        class="text-white/80 font-medium text-[13px] transition-all hover:text-accent-blue relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-[2px] after:bg-accent-blue after:transition-all hover:after:w-full">Hiring
                        Process</a></li>
                <li><a href="#"
                        class="text-white/80 font-medium text-[13px] transition-all hover:text-accent-blue relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-[2px] after:bg-accent-blue after:transition-all hover:after:w-full">Contact
                        us</a></li>
            </ul>
            <div class="flex gap-3">
                <a href="/login"
                    class="px-5 py-2 rounded-xl font-medium text-[13px] cursor-pointer transition-all bg-white/10 text-white hover:bg-white/20 border border-white/15">Login</a>
                <a href="/register"
                    class="px-5 py-2 rounded-xl font-medium text-[13px] cursor-pointer transition-all bg-accent-blue text-white hover:bg-accent-blue-hover hover:-translate-y-0.5 shadow-glow-blue">Register</a>
            </div>
        </nav>
    </header>

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
            class="flex-1 z-10 pr-0 lg:pr-12 text-white flex flex-col items-center lg:items-start text-center lg:text-left mb-14 lg:mb-0">
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
                        class="role-btn px-5 lg:px-8 py-3 rounded-xl text-sm font-semibold text-white/70 flex items-center gap-2.5 transition-all duration-300 border-none cursor-pointer bg-transparent hover:text-white hover:bg-white/10">
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
                class="absolute bg-[#0a1e4a] border border-white/5 px-4 py-3.5 rounded-xl shadow-card animate-float transition-all duration-500 ease-in-out top-8 lg:top-[80px] left-0 lg:-left-4 flex flex-col items-center">
                <div id="fc-1-icon-wrap"
                    class="bg-accent-yellow w-10 h-10 rounded-lg flex items-center justify-center text-white text-lg mb-2 transition-colors duration-400">
                    <i id="fc-1-icon" class="fas fa-briefcase"></i>
                </div>
                <h4 id="fc-1-title" class="text-lg font-extrabold text-white">20K +</h4>
                <p id="fc-1-desc" class="text-[11px] text-white/50">Job Vacancy</p>
            </div>

            <!-- Floating Card 2 -->
            <div id="fc-2"
                class="absolute bg-[#0a1e4a] border border-white/5 px-4 py-3.5 rounded-xl shadow-card animate-float transition-all duration-500 ease-in-out bottom-2 lg:bottom-[60px] right-0 lg:-right-6"
                style="animation-delay: 2.5s;">
                <h4 id="fc-2-title" class="text-sm font-extrabold mb-0.5 text-white">1+ Million</h4>
                <p id="fc-2-desc" class="text-[10px] text-white/50 mb-2">Trusted User</p>
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
                        <span class="font-extrabold text-white text-sm flex items-center gap-1.5">
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
                            class="bg-white/10 text-white rounded-full w-9 h-9 flex justify-center items-center font-bold text-lg">
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
                        <span class="font-extrabold text-white text-sm flex items-center gap-1.5">
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
                            class="bg-white/10 text-white rounded-full w-9 h-9 flex justify-center items-center font-bold text-lg">
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
                        <span class="font-extrabold text-white text-sm flex items-center gap-1.5">
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
                            class="bg-white/10 text-white rounded-full w-9 h-9 flex justify-center items-center font-bold text-lg">
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
                        <span class="font-extrabold text-white text-sm flex items-center gap-1.5">
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
                            class="bg-white/10 text-white rounded-full w-9 h-9 flex justify-center items-center font-bold text-lg">
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
    <section class="py-16 px-6 lg:px-[5%] bg-primary-bg text-white relative">
        <div class="text-center mb-12 reveal">
            <h4 class="text-accent-blue text-base font-medium mb-1.5 uppercase tracking-wider">Latest Jobs</h4>
            <h2 class="text-white text-3xl lg:text-4xl font-bold mb-4">Explore Recent Opportunities</h2>
            <div class="zigzag-divider w-16 h-2 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <!-- Job Card 1 -->
            <div
                class="bg-[#0a1e4a] border border-white/5 rounded-2xl p-6 text-text-dark transition-all duration-300 hover:-translate-y-1.5 shadow-card hover:shadow-card-hover flex flex-col group reveal reveal-delay-1">
                <h3 class="text-lg font-bold mb-3 text-white">Required math teacher</h3>
                <p class="text-xs text-white/60 mb-4 flex items-center gap-3">
                    <span class="text-red-400"><i class="fas fa-map-marker-alt mr-0.5"></i> Bokaro, Jharkhand</span>
                    <span><i class="far fa-calendar-alt mr-0.5"></i> 04 May 2026</span>
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span
                        class="bg-accent-blue/8 text-accent-blue px-2.5 py-1 rounded-lg text-[11px] font-semibold flex items-center gap-1.5"><i
                            class="fas fa-user text-[9px]"></i> 2 Openings</span>
                    <span
                        class="bg-accent-blue/8 text-accent-blue px-2.5 py-1 rounded-lg text-[11px] font-semibold flex items-center gap-1.5"><i
                            class="fas fa-graduation-cap text-[9px]"></i> Lower Primary - I - IV</span>
                    <span
                        class="bg-accent-blue/8 text-accent-blue px-2.5 py-1 rounded-lg text-[11px] font-semibold flex items-center gap-1.5"><i
                            class="fas fa-laptop text-[9px]"></i> Offline</span>
                </div>
                <p class="text-[13px] text-white/60 leading-relaxed mb-6 flex-grow">Lorem Ipsum is simply dummy text of
                    the printing and typesetting industry. Lorem Ipsum has been the industry's...</p>
                <a href="#"
                    class="text-accent-blue font-semibold text-[13px] inline-flex items-center gap-2 self-start group-hover:gap-3 transition-all">View
                    Details <span
                        class="bg-accent-yellow text-[#031b4e] w-5 h-5 rounded-full flex items-center justify-center text-[9px]"><i
                            class="fas fa-chevron-right"></i></span></a>
            </div>

            <!-- Job Card 2 -->
            <div
                class="bg-[#0a1e4a] border border-white/5 rounded-2xl p-6 text-text-dark transition-all duration-300 hover:-translate-y-1.5 shadow-card hover:shadow-card-hover flex flex-col group reveal reveal-delay-2">
                <h3 class="text-lg font-bold mb-3 text-white">Required Science teacher</h3>
                <p class="text-xs text-white/60 mb-4 flex items-center gap-3">
                    <span class="text-red-400"><i class="fas fa-map-marker-alt mr-0.5"></i> Ranchi, Jharkhand</span>
                    <span><i class="far fa-calendar-alt mr-0.5"></i> 10 May 2026</span>
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span
                        class="bg-accent-blue/8 text-accent-blue px-2.5 py-1 rounded-lg text-[11px] font-semibold flex items-center gap-1.5"><i
                            class="fas fa-user text-[9px]"></i> 1 Openings</span>
                    <span
                        class="bg-accent-blue/8 text-accent-blue px-2.5 py-1 rounded-lg text-[11px] font-semibold flex items-center gap-1.5"><i
                            class="fas fa-graduation-cap text-[9px]"></i> Secondary - VIII - X</span>
                    <span
                        class="bg-accent-blue/8 text-accent-blue px-2.5 py-1 rounded-lg text-[11px] font-semibold flex items-center gap-1.5"><i
                            class="fas fa-laptop text-[9px]"></i> Offline</span>
                </div>
                <p class="text-[13px] text-white/60 leading-relaxed mb-6 flex-grow">Lorem Ipsum is simply dummy text of
                    the printing and typesetting industry. Lorem Ipsum has been the industry's...</p>
                <a href="#"
                    class="text-accent-blue font-semibold text-[13px] inline-flex items-center gap-2 self-start group-hover:gap-3 transition-all">View
                    Details <span
                        class="bg-accent-yellow text-[#031b4e] w-5 h-5 rounded-full flex items-center justify-center text-[9px]"><i
                            class="fas fa-chevron-right"></i></span></a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-14 px-6 lg:px-[5%] relative bg-cover bg-center bg-fixed" style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
        <div class="absolute inset-0 bg-[#0f172a]/90 backdrop-blur-sm z-0"></div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 relative z-10">
            <div
                class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 text-center text-white transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:border-accent-blue/50 cursor-pointer group reveal reveal-delay-1">
                <i class="fas fa-briefcase text-3xl mb-4 block text-accent-blue group-hover:scale-110 transition-transform"></i>
                <h3 class="text-sm font-semibold mb-4">Admin</h3>
                <div class="bg-white/20 backdrop-blur-md text-white px-3 py-1.5 rounded-full text-[11px] font-semibold inline-block">0
                    Active Jobs</div>
            </div>
            <div
                class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 text-center text-white transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:border-accent-blue/50 cursor-pointer group reveal reveal-delay-2">
                <i class="fas fa-palette text-3xl mb-4 block text-accent-blue group-hover:scale-110 transition-transform"></i>
                <h3 class="text-sm font-semibold mb-4">Art & Craft</h3>
                <div class="bg-white/20 backdrop-blur-md text-white px-3 py-1.5 rounded-full text-[11px] font-semibold inline-block">0
                    Active Jobs</div>
            </div>
            <div
                class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 text-center text-white transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:border-accent-blue/50 cursor-pointer group reveal reveal-delay-3">
                <i class="fas fa-music text-3xl mb-4 block text-accent-blue group-hover:scale-110 transition-transform"></i>
                <h3 class="text-sm font-semibold mb-4">Dance Teacher</h3>
                <div class="bg-white/20 backdrop-blur-md text-white px-3 py-1.5 rounded-full text-[11px] font-semibold inline-block">1
                    Active Jobs</div>
            </div>
            <div
                class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 text-center text-white transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:border-accent-blue/50 cursor-pointer group reveal reveal-delay-4">
                <i class="fas fa-chart-line text-3xl mb-4 block text-accent-blue group-hover:scale-110 transition-transform"></i>
                <h3 class="text-sm font-semibold mb-4">Management</h3>
                <div class="bg-white/20 backdrop-blur-md text-white px-3 py-1.5 rounded-full text-[11px] font-semibold inline-block">1
                    Active Jobs</div>
            </div>
            <div
                class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 text-center text-white transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:border-accent-blue/50 cursor-pointer group reveal reveal-delay-1">
                <i class="fas fa-guitar text-3xl mb-4 block text-accent-blue group-hover:scale-110 transition-transform"></i>
                <h3 class="text-sm font-semibold mb-4">Music Teacher</h3>
                <div class="bg-white/20 backdrop-blur-md text-white px-3 py-1.5 rounded-full text-[11px] font-semibold inline-block">1
                    Active Jobs</div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-16 px-6 lg:px-[5%] bg-[#0B1121] text-white text-center relative overflow-hidden">
        <div
            class="absolute top-5 right-[5%] opacity-[0.02] text-7xl md:text-[100px] font-extrabold uppercase pointer-events-none select-none tracking-wider">
            VEDANTA</div>
        <div class="mb-12 relative z-10 reveal">
            <h4 class="text-accent-blue text-base font-medium mb-1.5 uppercase tracking-wider">Providing Everything You
                Need</h4>
            <h2 class="text-3xl lg:text-4xl font-bold text-white">Our Services</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5 relative z-10">
            <div
                class="bg-[#0a1e4a] border border-white/5 rounded-2xl p-7 text-text-dark transition-all duration-300 hover:-translate-y-1.5 hover:shadow-card-hover group reveal reveal-delay-1">
                <div
                    class="bg-accent-blue w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5 text-white text-2xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[5deg]">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h3 class="text-sm font-bold mb-4 leading-tight">Recruitment<br>Services</h3>
                <a href="#"
                    class="text-accent-blue font-semibold text-xs flex items-center justify-center gap-1.5 group-hover:gap-2.5 transition-all">Read
                    More <span
                        class="bg-accent-yellow text-[#031b4e] w-4 h-4 rounded-full flex items-center justify-center text-[8px]"><i
                            class="fas fa-chevron-right"></i></span></a>
            </div>

            <div
                class="bg-[#0a1e4a] border border-white/5 rounded-2xl p-7 text-text-dark transition-all duration-300 hover:-translate-y-1.5 hover:shadow-card-hover group reveal reveal-delay-2">
                <div
                    class="bg-accent-blue w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5 text-white text-2xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[5deg]">
                    <i class="fas fa-globe"></i>
                </div>
                <h3 class="text-sm font-bold mb-4 leading-tight">Digital Support</h3>
                <a href="#"
                    class="text-accent-blue font-semibold text-xs flex items-center justify-center gap-1.5 group-hover:gap-2.5 transition-all">Read
                    More <span
                        class="bg-accent-yellow text-[#031b4e] w-4 h-4 rounded-full flex items-center justify-center text-[8px]"><i
                            class="fas fa-chevron-right"></i></span></a>
            </div>

            <div
                class="bg-[#0a1e4a] border border-white/5 rounded-2xl p-7 text-text-dark transition-all duration-300 hover:-translate-y-1.5 hover:shadow-card-hover group reveal reveal-delay-3">
                <div
                    class="bg-accent-blue w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5 text-white text-2xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[5deg]">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h3 class="text-sm font-bold mb-4 leading-tight">Resume Services</h3>
                <a href="#"
                    class="text-accent-blue font-semibold text-xs flex items-center justify-center gap-1.5 group-hover:gap-2.5 transition-all">Read
                    More <span
                        class="bg-accent-yellow text-[#031b4e] w-4 h-4 rounded-full flex items-center justify-center text-[8px]"><i
                            class="fas fa-chevron-right"></i></span></a>
            </div>

            <div
                class="bg-[#0a1e4a] border border-white/5 rounded-2xl p-7 text-text-dark transition-all duration-300 hover:-translate-y-1.5 hover:shadow-card-hover group reveal reveal-delay-4">
                <div
                    class="bg-accent-blue w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5 text-white text-2xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[5deg]">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 class="text-sm font-bold mb-4 leading-tight">Interview Training</h3>
                <a href="#"
                    class="text-accent-blue font-semibold text-xs flex items-center justify-center gap-1.5 group-hover:gap-2.5 transition-all">Read
                    More <span
                        class="bg-accent-yellow text-[#031b4e] w-4 h-4 rounded-full flex items-center justify-center text-[8px]"><i
                            class="fas fa-chevron-right"></i></span></a>
            </div>

            <div
                class="bg-[#0a1e4a] border border-white/5 rounded-2xl p-7 text-text-dark transition-all duration-300 hover:-translate-y-1.5 hover:shadow-card-hover group reveal reveal-delay-1">
                <div
                    class="bg-accent-blue w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5 text-white text-2xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[5deg]">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <h3 class="text-sm font-bold mb-4 leading-tight">Catering Services</h3>
                <a href="#"
                    class="text-accent-blue font-semibold text-xs flex items-center justify-center gap-1.5 group-hover:gap-2.5 transition-all">Read
                    More <span
                        class="bg-accent-yellow text-[#031b4e] w-4 h-4 rounded-full flex items-center justify-center text-[8px]"><i
                            class="fas fa-chevron-right"></i></span></a>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-16 px-6 lg:px-[5%] text-center relative bg-cover bg-center bg-fixed" style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
        <div class="absolute inset-0 bg-[#021235]/95 backdrop-blur-[2px] z-0"></div>
        <div class="mb-8 relative z-10 reveal">
            <h4 class="text-accent-yellow text-base font-medium mb-1.5 uppercase tracking-wider">Testimonial</h4>
            <h2 class="text-white text-3xl lg:text-4xl font-bold mb-4">What Our Clients Has To Say About Us</h2>
            <p class="max-w-2xl mx-auto text-[13px] text-white/70 leading-relaxed">
                Discover first hand experiences as our satisfied clients share their testimonials about the exceptional
                recruitment services we provide. Hear what they have to say about our commitment to finding the right
                talent.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16 px-0 lg:px-4 relative z-10">
            <div
                class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-7 pt-6 relative shadow-card hover:-translate-y-1.5 hover:shadow-card-hover transition-all duration-300 reveal reveal-delay-1">
                <div class="absolute top-5 right-6 text-white/20 text-2xl"><i
                        class="fas fa-quote-right"></i></div>
                <div
                    class="w-14 h-14 rounded-full -mt-[52px] mx-auto mb-4 border-4 border-[#021235] relative bg-white overflow-hidden">
                    <img src="https://i.pravatar.cc/150?img=5" alt="Asriya Robert" class="w-full h-full object-cover">
                </div>
                <p class="text-[13px] text-white/80 leading-relaxed mb-4 italic">"Working with MEMS has been a
                    game-changer for our organization. Their dedicated team understood our unique needs and brought us
                    top-tier talent."</p>
                <div class="flex justify-center gap-1 text-accent-yellow text-[11px] mb-3">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                        class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <h4 class="text-white text-sm font-bold mb-0.5">Asriya Robert</h4>
                <p class="text-[10px] text-accent-blue uppercase tracking-wider font-semibold">Job Seeker</p>
            </div>

            <div
                class="bg-gradient-to-b from-[#129aef]/90 to-[#0d85d4]/90 backdrop-blur-xl border border-white/20 rounded-2xl p-7 pt-6 relative shadow-2xl transform scale-100 md:scale-105 hover:md:scale-[1.08] transition-all duration-300 reveal reveal-delay-2">
                <div class="absolute top-5 right-6 text-white/30 text-2xl"><i
                        class="fas fa-quote-right"></i></div>
                <div
                    class="w-14 h-14 rounded-full -mt-[52px] mx-auto mb-4 border-4 border-[#129aef] relative bg-white overflow-hidden shadow-lg">
                    <img src="https://i.pravatar.cc/150?img=11" alt="Sreeraj Menon" class="w-full h-full object-cover">
                </div>
                <p class="text-[13px] text-white/90 leading-relaxed mb-4 italic">"The professionalism and efficiency
                    exceeded our expectations. They navigated the hiring process seamlessly, presenting us with highly
                    qualified candidates."</p>
                <div class="flex justify-center gap-1 text-accent-yellow text-[11px] mb-3">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                        class="fas fa-star"></i><i class="far fa-star text-white/50"></i>
                </div>
                <h4 class="text-white text-sm font-bold mb-0.5 shadow-sm">Sreeraj Menon</h4>
                <p class="text-[10px] text-white/80 uppercase tracking-wider font-bold">Employer</p>
            </div>

            <div
                class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-7 pt-6 relative shadow-card hover:-translate-y-1.5 hover:shadow-card-hover transition-all duration-300 reveal reveal-delay-3">
                <div class="absolute top-5 right-6 text-white/20 text-2xl"><i
                        class="fas fa-quote-right"></i></div>
                <div
                    class="w-14 h-14 rounded-full -mt-[52px] mx-auto mb-4 border-4 border-[#021235] relative bg-white overflow-hidden">
                    <img src="https://i.pravatar.cc/150?img=9" alt="Asriya Robert" class="w-full h-full object-cover">
                </div>
                <p class="text-[13px] text-white/80 leading-relaxed mb-4 italic">"Working with MEMS has been a
                    game-changer for our organization. Their dedicated team understood our unique needs and brought us
                    top-tier talent."</p>
                <div class="flex justify-center gap-1 text-accent-yellow text-[11px] mb-3">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                        class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <h4 class="text-white text-sm font-bold mb-0.5">Asriya Robert</h4>
                <p class="text-[10px] text-accent-blue uppercase tracking-wider font-semibold">Job Seeker</p>
            </div>
        </div>
    </section>

    <!-- We Are Available On Section -->
    <section class="py-12 bg-secondary-bg overflow-hidden">
        <div class="mb-8 text-left px-6 lg:px-[5%] reveal">
            <h2 class="text-text-dark text-2xl font-bold">We are available on</h2>
        </div>
        <div class="swiper marquee-swiper reveal">
            <div class="swiper-wrapper items-center">
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-[#38bdf8] text-lg flex items-center gap-0.5">Linked<i
                                class="fab fa-linkedin"></i></span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-blue-400 text-lg tracking-tighter">indeed</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-orange-400 text-base flex items-center gap-1.5"><i
                                class="fas fa-shield-alt text-yellow-500 text-sm"></i> Sulekha</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-green-400 text-base tracking-wide">'GLASSDOOR'</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group flex-col">
                        <span class="font-bold text-purple-300 text-base mb-0.5">apna</span>
                        <div class="w-10 h-0.5 flex rounded-full overflow-hidden">
                            <div class="w-1/3 bg-[#00a884]"></div>
                            <div class="w-1/3 bg-[#71b1ea]"></div>
                            <div class="w-1/3 bg-[#ffc300]"></div>
                        </div>
                    </div>
                </div>
            
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-[#38bdf8] text-lg flex items-center gap-0.5">Linked<i
                                class="fab fa-linkedin"></i></span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-blue-400 text-lg tracking-tighter">indeed</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-orange-400 text-base flex items-center gap-1.5"><i
                                class="fas fa-shield-alt text-yellow-500 text-sm"></i> Sulekha</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-green-400 text-base tracking-wide">'GLASSDOOR'</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group flex-col">
                        <span class="font-bold text-purple-300 text-base mb-0.5">apna</span>
                        <div class="w-10 h-0.5 flex rounded-full overflow-hidden">
                            <div class="w-1/3 bg-[#00a884]"></div>
                            <div class="w-1/3 bg-[#71b1ea]"></div>
                            <div class="w-1/3 bg-[#ffc300]"></div>
                        </div>
                    </div>
                </div>
            
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-[#38bdf8] text-lg flex items-center gap-0.5">Linked<i
                                class="fab fa-linkedin"></i></span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-blue-400 text-lg tracking-tighter">indeed</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-orange-400 text-base flex items-center gap-1.5"><i
                                class="fas fa-shield-alt text-yellow-500 text-sm"></i> Sulekha</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-green-400 text-base tracking-wide">'GLASSDOOR'</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group flex-col">
                        <span class="font-bold text-purple-300 text-base mb-0.5">apna</span>
                        <div class="w-10 h-0.5 flex rounded-full overflow-hidden">
                            <div class="w-1/3 bg-[#00a884]"></div>
                            <div class="w-1/3 bg-[#71b1ea]"></div>
                            <div class="w-1/3 bg-[#ffc300]"></div>
                        </div>
                    </div>
                </div>
            
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-[#38bdf8] text-lg flex items-center gap-0.5">Linked<i
                                class="fab fa-linkedin"></i></span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-blue-400 text-lg tracking-tighter">indeed</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-bold text-orange-400 text-base flex items-center gap-1.5"><i
                                class="fas fa-shield-alt text-yellow-500 text-sm"></i> Sulekha</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group">
                        <span class="font-extrabold text-green-400 text-base tracking-wide">'GLASSDOOR'</span>
                    </div>
                </div>
                <div class="swiper-slide w-auto">
                    <div
                        class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-center justify-center min-w-[200px] h-20 transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/15 hover:border-accent-blue/50 hover:shadow-[0_8px_32px_rgba(18,154,239,0.2)] cursor-grab active:cursor-grabbing group flex-col">
                        <span class="font-bold text-purple-300 text-base mb-0.5">apna</span>
                        <div class="w-10 h-0.5 flex rounded-full overflow-hidden">
                            <div class="w-1/3 bg-[#00a884]"></div>
                            <div class="w-1/3 bg-[#71b1ea]"></div>
                            <div class="w-1/3 bg-[#ffc300]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Certifications We Have Section -->
    <section class="py-14 px-6 lg:px-[5%] bg-primary-bg text-center">
        <div class="mb-10 reveal">
            <h2 class="text-white text-2xl lg:text-3xl font-bold mb-2 tracking-wide uppercase">Certifications We
                Have</h2>
            <p class="text-white/60 text-xs">Verified standards empowering educational careers worldwide.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div
                class="bg-[#0a1e4a] border border-white/5 rounded-xl p-6 flex flex-col items-center shadow-card hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300 reveal reveal-delay-1">
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 mb-3">
                    <i class="fas fa-certificate text-xl"></i>
                </div>
                <h3 class="font-bold text-sm mb-3 text-white">MSME</h3>
                <div
                    class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-semibold flex items-center gap-1">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
            </div>
            <div
                class="bg-[#0a1e4a] border border-white/5 rounded-xl p-6 flex flex-col items-center shadow-card hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300 reveal reveal-delay-2">
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
                class="bg-[#0a1e4a] border border-white/5 rounded-xl p-6 flex flex-col items-center shadow-card hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300 reveal reveal-delay-3">
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
                class="bg-[#0a1e4a] border border-white/5 rounded-xl p-6 flex flex-col items-center shadow-card hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300 reveal reveal-delay-4">
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
                class="bg-[#0a1e4a] border border-white/5 rounded-xl p-6 flex flex-col items-center shadow-card hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300 reveal reveal-delay-1">
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

    <!-- Get In Touch Section -->
    <section class="py-10 px-6 lg:px-[5%] pb-16 bg-secondary-bg">
        <div
            class="bg-gradient-to-r from-primary-bg to-accent-blue/90 border border-white/10 rounded-3xl flex flex-col lg:flex-row items-center relative overflow-hidden p-8 lg:p-12 text-white shadow-2xl reveal">
            <div class="git-bg-pattern absolute inset-0 z-0"></div>
            <div class="absolute text-xl text-white/15 z-10 top-6 right-[45%]"><i class="fas fa-wave-square"></i></div>
            <div class="absolute text-lg text-white/15 z-10 bottom-8 left-[30%] tracking-[5px]"><i
                    class="fas fa-ellipsis-h"></i><br><i class="fas fa-ellipsis-h"></i></div>
            <div class="absolute text-lg text-white/15 z-10 top-4 right-4 tracking-[5px]"><i
                    class="fas fa-ellipsis-v"></i><i class="fas fa-ellipsis-v"></i></div>

            <div class="flex-1 z-20 pr-0 lg:pr-8 mb-8 lg:mb-0 text-center lg:text-left">
                <h2 class="text-3xl font-bold mb-4">Get In Touch With Us</h2>
                <p class="text-sm leading-relaxed mb-6 opacity-85">Let's Build Your Dream Team Together! Reach out to us
                    for unparalleled recruitment solutions tailored to your business needs.</p>
                <a href="#"
                    class="bg-accent-yellow text-[#031b4e] px-6 py-2.5 rounded-full font-semibold text-sm inline-flex items-center gap-3 hover:-translate-y-1 hover:shadow-glow-yellow transition-all">
                    Contact us <span
                        class="bg-white text-[#031b4e] w-5 h-5 rounded-full flex items-center justify-center text-[9px]"><i
                            class="fas fa-chevron-right"></i></span>
                </a>
            </div>

            <div class="flex-1 z-20 flex justify-center lg:justify-end relative">
                <img src="https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                    alt="Contact Us"
                    class="relative lg:absolute -bottom-12 right-0 max-h-[280px] rounded-2xl shadow-2xl object-cover">
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#012d55] pt-12 pb-5 px-6 lg:px-[5%] text-white relative z-50">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
            <div>
                <a href="#" class="flex items-center no-underline mb-5">
                    <img src="/images/logo.png" alt="Vedanta Placement Agency"
                        class="h-14 w-auto object-contain brightness-0 invert">
                </a>
                <p class="text-xs text-white/50 leading-relaxed mb-5">
                    Connecting educators and schools across India since 2020. With hundreds of teacher placements, we
                    make hiring and job searching simple, fast and reliable.
                </p>
                <h5 class="font-semibold mb-2 text-sm">Download App</h5>
                <div class="flex gap-2">
                    <div
                        class="bg-gray-800/80 border border-gray-600/50 rounded-lg px-2.5 py-1 flex items-center gap-1.5 cursor-pointer hover:bg-gray-700 transition-colors">
                        <i class="fab fa-google-play text-base"></i>
                        <div class="text-left">
                            <p class="text-[7px] uppercase m-0 leading-none text-gray-400">Coming soon to</p>
                            <p class="font-semibold text-xs m-0 leading-tight">Google Play</p>
                        </div>
                    </div>
                    <div
                        class="bg-gray-800/80 border border-gray-600/50 rounded-lg px-2.5 py-1 flex items-center gap-1.5 cursor-pointer hover:bg-gray-700 transition-colors">
                        <i class="fab fa-apple text-xl"></i>
                        <div class="text-left">
                            <p class="text-[7px] uppercase m-0 leading-none text-gray-400">Coming soon to the</p>
                            <p class="font-semibold text-xs m-0 leading-tight">App Store</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-bold mb-5 tracking-wider uppercase">Quick Links</h4>
                <div class="grid grid-cols-2 gap-y-2.5 text-xs text-gray-400">
                    <a href="#" class="hover:text-accent-blue transition-colors">Home</a>
                    <a href="#" class="hover:text-accent-blue transition-colors">Apply for Job</a>
                    <a href="#" class="hover:text-accent-blue transition-colors">About us</a>
                    <a href="#" class="hover:text-accent-blue transition-colors">Post your Job</a>
                    <a href="#" class="hover:text-accent-blue transition-colors">Contact us</a>
                    <a href="#" class="hover:text-accent-blue transition-colors">Terms & Conditions</a>
                    <a href="#" class="hover:text-accent-blue transition-colors">Services</a>
                    <a href="#" class="hover:text-accent-blue transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-accent-blue transition-colors">Jobs</a>
                    <a href="#" class="hover:text-accent-blue transition-colors">Media</a>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-bold mb-5 tracking-wider uppercase">Social</h4>
                <ul class="space-y-2.5 text-xs text-gray-400">
                    <li><a href="#" class="flex items-center gap-2.5 hover:text-accent-blue transition-colors"><i
                                class="fab fa-instagram text-sm w-4 text-center"></i> Instagram</a></li>
                    <li><a href="#" class="flex items-center gap-2.5 hover:text-accent-blue transition-colors"><i
                                class="fab fa-facebook text-sm w-4 text-center"></i> Facebook</a></li>
                    <li><a href="#" class="flex items-center gap-2.5 hover:text-accent-blue transition-colors"><i
                                class="fab fa-youtube text-sm w-4 text-center"></i> Youtube</a></li>
                    <li><a href="#" class="flex items-center gap-2.5 hover:text-accent-blue transition-colors"><i
                                class="fab fa-whatsapp text-sm w-4 text-center"></i> Whatsapp</a></li>
                    <li><a href="#" class="flex items-center gap-2.5 hover:text-accent-blue transition-colors"><i
                                class="fab fa-linkedin text-sm w-4 text-center"></i> Linkedin</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-bold mb-5 tracking-wider uppercase">Get In Touch</h4>
                <div class="text-xs text-gray-400 space-y-4">
                    <p class="leading-relaxed flex gap-2"><i class="fas fa-map-marker-alt text-accent-blue mt-0.5"></i>
                        Agam Kuan, Sardar Patel Colony,<br>Patna, Bihar- 800007, India</p>
                    <div class="flex gap-2">
                        <i class="fas fa-envelope text-accent-blue mt-0.5"></i>
                        <a href="mailto:info@vedantaplacementagency.in"
                            class="hover:text-accent-blue transition-colors">info@vedantaplacementagency.in</a>
                    </div>
                    <div class="flex gap-2">
                        <i class="fas fa-phone-alt text-accent-blue mt-0.5"></i>
                        <a href="tel:+917070938975" class="hover:text-accent-blue transition-colors">+91-7070938975</a>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="border-t border-gray-600/30 pt-4 flex flex-col md:flex-row justify-between items-center text-[11px] text-gray-500">
            <p class="mb-1 md:mb-0">Copyright © 2026</p>
            <p>Designed By: Vedanta Placement Agency</p>
        </div>
    </footer>

    <!-- FABs -->
    <div class="fixed right-6 bottom-6 flex flex-col gap-3 z-[999]">
        <a href="#"
            class="w-12 h-12 rounded-full flex items-center justify-center text-white text-lg no-underline shadow-lg transition-all duration-300 hover:scale-110 hover:-translate-y-1 bg-accent-blue"><i
                class="fas fa-phone-alt"></i></a>
        <a href="#"
            class="w-12 h-12 rounded-full flex items-center justify-center text-white text-xl no-underline shadow-lg transition-all duration-300 hover:scale-110 hover:-translate-y-1 bg-[#25D366]"><i
                class="fab fa-whatsapp"></i></a>
        <a href="#"
            class="w-12 h-12 rounded-full flex items-center justify-center text-white text-lg no-underline shadow-lg transition-all duration-300 hover:scale-110 hover:-translate-y-1 bg-accent-blue"
            onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;"><i class="fas fa-chevron-up"></i></a>
    </div>

    <!-- Scripts -->
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        // ---- Swiper Initialization ----
        document.addEventListener('DOMContentLoaded', function () {
            const swipers = document.querySelectorAll('.marquee-swiper');
            swipers.forEach(function (swiperEl) {
                new Swiper(swiperEl, {
                    loop: true,
                    slidesPerView: 'auto',
                    spaceBetween: 24,
                    speed: 3000,
                    autoplay: {
                        delay: 0,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true,
                    },
                    freeMode: true,
                    grabCursor: true,
                });
            });
        });

        // ---- Scroll-triggered reveal animations ----
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // ---- Header shrink on scroll ----
        const header = document.getElementById('main-header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 40) {
                header.classList.add('header-scrolled');
                header.classList.remove('py-4');
                header.classList.add('py-2.5');
            } else {
                header.classList.remove('header-scrolled');
                header.classList.add('py-4');
                header.classList.remove('py-2.5');
            }
        });

        // ---- Role Toggle ----
        const contentData = {
            seeker: {
                title: "Get placed in top<br>schools across...",
                subtitle: "step into the right opportunity with trusted schools that value your talent",
                ctaText: "Job Seeker",
                imgUrl: "https://images.unsplash.com/photo-1556157382-97eda2d62296?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                fc1Title: "20K +",
                fc1Desc: "Job Vacancy",
                fc1Icon: "fa-briefcase",
                fc1Color: "bg-accent-yellow",
                fc2Title: "1+ Million",
                fc2Desc: "Trusted User",
                fc2HTML: `
                    <img src="https://i.pravatar.cc/100?img=11" alt="User" class="w-7 h-7 rounded-full border-2 border-white first:ml-0">
                    <img src="https://i.pravatar.cc/100?img=32" alt="User" class="w-7 h-7 rounded-full border-2 border-white -ml-2">
                    <img src="https://i.pravatar.cc/100?img=44" alt="User" class="w-7 h-7 rounded-full border-2 border-white -ml-2">
                    <img src="https://i.pravatar.cc/100?img=55" alt="User" class="w-7 h-7 rounded-full border-2 border-white -ml-2">
                    <div class="w-7 h-7 rounded-full bg-accent-yellow text-[#031b4e] flex items-center justify-center font-bold border-2 border-white -ml-2 text-[10px]">+</div>
                `
            },
            employer: {
                title: "Hire the best<br>educators globally...",
                subtitle: "partner with us to find top-tier teaching professionals for your institution",
                ctaText: "Employer",
                imgUrl: "https://images.unsplash.com/photo-1573164713988-8665fc963095?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                fc1Title: "500+",
                fc1Desc: "Partner Schools",
                fc1Icon: "fa-building",
                fc1Color: "bg-accent-blue",
                fc2Title: "Fast Hiring",
                fc2Desc: "Quality candidates",
                fc2HTML: `
                    <div class="text-xl text-accent-blue font-bold p-1"><i class="fas fa-bolt"></i></div>
                `
            }
        };

        let currentRole = 'seeker';

        function toggleRole(role) {
            if (role === currentRole) return;
            currentRole = role;

            const btnSeeker = document.getElementById('btn-seeker');
            const btnEmployer = document.getElementById('btn-employer');

            if (role === 'seeker') {
                btnSeeker.className = "role-btn px-5 lg:px-8 py-3 rounded-xl text-sm font-semibold flex items-center gap-2.5 transition-all duration-300 border-none cursor-pointer bg-accent-blue text-white shadow-glow-blue";
                btnEmployer.className = "role-btn px-5 lg:px-8 py-3 rounded-xl text-sm font-semibold text-white/70 flex items-center gap-2.5 transition-all duration-300 border-none cursor-pointer bg-transparent hover:text-white hover:bg-white/10";
            } else {
                btnEmployer.className = "role-btn px-5 lg:px-8 py-3 rounded-xl text-sm font-semibold flex items-center gap-2.5 transition-all duration-300 border-none cursor-pointer bg-accent-blue text-white shadow-glow-blue";
                btnSeeker.className = "role-btn px-5 lg:px-8 py-3 rounded-xl text-sm font-semibold text-white/70 flex items-center gap-2.5 transition-all duration-300 border-none cursor-pointer bg-transparent hover:text-white hover:bg-white/10";
            }

            const data = contentData[role];

            const elementsToFade = [
                document.getElementById('hero-title'),
                document.getElementById('hero-subtitle'),
                document.getElementById('hero-img')
            ];
            const floatingCards = [
                document.getElementById('fc-1'),
                document.getElementById('fc-2')
            ];

            elementsToFade.forEach(el => el.classList.add('fade-out'));
            floatingCards.forEach(el => el.classList.add('float-fade-out'));

            const ring1 = document.getElementById('ring-1');
            const ring2 = document.getElementById('ring-2');
            const currentRot1 = parseInt(ring1.dataset.rot || -15);
            const currentRot2 = parseInt(ring2.dataset.rot || -15);
            ring1.style.transform = `rotate(${currentRot1 + 180}deg)`;
            ring2.style.transform = `rotate(${currentRot2 - 180}deg)`;
            ring1.dataset.rot = currentRot1 + 180;
            ring2.dataset.rot = currentRot2 - 180;

            setTimeout(() => {
                document.getElementById('hero-title').innerHTML = data.title;
                document.getElementById('hero-subtitle').innerHTML = data.subtitle;
                document.getElementById('cta-text').innerText = data.ctaText;
                document.getElementById('hero-img').src = data.imgUrl;

                document.getElementById('fc-1-title').innerText = data.fc1Title;
                document.getElementById('fc-1-desc').innerText = data.fc1Desc;
                document.getElementById('fc-1-icon').className = 'fas ' + data.fc1Icon;

                const iconWrap = document.getElementById('fc-1-icon-wrap');
                iconWrap.classList.remove('bg-accent-yellow', 'bg-accent-blue');
                iconWrap.classList.add(data.fc1Color);

                document.getElementById('fc-2-title').innerText = data.fc2Title;
                document.getElementById('fc-2-desc').innerText = data.fc2Desc;
                document.getElementById('fc-2-avatars').innerHTML = data.fc2HTML;

                elementsToFade.forEach(el => el.classList.remove('fade-out'));
                floatingCards.forEach(el => el.classList.remove('float-fade-out'));
            }, 500);
        }
    </script>
</body>

</html>