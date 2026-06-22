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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600;700&family=Lora:wght@400;500;600;700&family=Oswald:wght@300;400;500;600;700&family=Nunito:wght@300;400;500;600;700&family=Fira+Code:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            background-color: var(--theme-primary-bg) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body class="bg-secondary-bg text-text-dark">

    <!-- Preloader -->
    <div id="page-loader" class="fixed inset-0 z-[9999] bg-primary-bg flex flex-col items-center justify-center transition-opacity duration-700 ease-in-out">
        <div class="relative flex flex-col items-center">
            <!-- Logo -->
            <img src="/images/logo.png" alt="Vedanta Placement Agency" class="h-20 md:h-24 w-auto object-contain mb-8 animate-pulse-soft">
            
            <!-- Modern Loader -->
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-accent-blue animate-bounce" style="animation-delay: -0.3s"></div>
                <div class="w-3 h-3 rounded-full bg-accent-yellow animate-bounce" style="animation-delay: -0.15s"></div>
                <div class="w-3 h-3 rounded-full bg-accent-blue hover:bg-accent-blue-hover animate-bounce"></div>
            </div>
        </div>
    </div>

    <!-- Top Contact Bar -->
    <div
        class="bg-secondary-bg text-text-main opacity-80 text-xs py-2 px-6 lg:px-[5%] hidden md:flex justify-between items-center border-b border-card-border">
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
        class="sticky top-0 bg-primary-bg px-6 py-4 lg:px-[5%] flex justify-between items-center z-[100] transition-all duration-500">
        <a href="#" class="flex items-center no-underline">
            <img src="/images/logo.png" alt="Vedanta Placement Agency" class="h-12 w-auto object-contain">
        </a>
        <nav class="hidden lg:flex items-center">
            <ul class="flex gap-6 mr-8 list-none">
                <li><a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') ? 'text-accent-blue after:w-full' : 'text-text-main opacity-80 hover:text-accent-blue hover:after:w-full' }} font-medium text-[13px] transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-accent-blue after:transition-all {{ !request()->routeIs('home') ? 'after:w-0' : '' }}">Home</a>
                </li>
                <li><a href="{{ route('about') }}"
                        class="{{ request()->routeIs('about') ? 'text-accent-blue after:w-full' : 'text-text-main opacity-80 hover:text-accent-blue hover:after:w-full' }} font-medium text-[13px] transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-accent-blue after:transition-all {{ !request()->routeIs('about') ? 'after:w-0' : '' }}">About us</a></li>
                <li><a href="{{ route('services') }}"
                        class="{{ request()->routeIs('services') ? 'text-accent-blue after:w-full' : 'text-text-main opacity-80 hover:text-accent-blue hover:after:w-full' }} font-medium text-[13px] transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-accent-blue after:transition-all {{ !request()->routeIs('services') ? 'after:w-0' : '' }}">Our Services</a></li>
                <li><a href="{{ route('jobs') }}"
                        class="{{ request()->routeIs('jobs') ? 'text-accent-blue after:w-full' : 'text-text-main opacity-80 hover:text-accent-blue hover:after:w-full' }} font-medium text-[13px] transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-accent-blue after:transition-all {{ !request()->routeIs('jobs') ? 'after:w-0' : '' }}">Jobs</a>
                </li>
                <li><a href="{{ route('resume.builder') }}"
                        class="{{ request()->routeIs('resume.builder') ? 'text-accent-blue after:w-full' : 'text-text-main opacity-80 hover:text-accent-blue hover:after:w-full' }} font-medium text-[13px] transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-accent-blue after:transition-all {{ !request()->routeIs('resume.builder') ? 'after:w-0' : '' }}">Resume Builder <span class="bg-accent-yellow text-white text-[8px] px-1 py-0.5 rounded uppercase font-bold ml-1 relative -top-1">Free</span></a></li>
                <li><a href="{{ route('hiring') }}"
                        class="{{ request()->routeIs('hiring') ? 'text-accent-blue after:w-full' : 'text-text-main opacity-80 hover:text-accent-blue hover:after:w-full' }} font-medium text-[13px] transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-accent-blue after:transition-all {{ !request()->routeIs('hiring') ? 'after:w-0' : '' }}">Hiring Process</a></li>
                <li><a href="{{ route('contact') }}"
                        class="{{ request()->routeIs('contact') ? 'text-accent-blue after:w-full' : 'text-text-main opacity-80 hover:text-accent-blue hover:after:w-full' }} font-medium text-[13px] transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-accent-blue after:transition-all {{ !request()->routeIs('contact') ? 'after:w-0' : '' }}">Contact us</a></li>
            </ul>
            <div class="flex gap-3 items-center">
                <!-- Theme Switcher -->
                <div class="relative">
                    <button id="themeSwitcherBtn" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/5 border border-white/10 text-text-main opacity-80 hover:text-text-main hover:bg-white/10 transition-all cursor-pointer">
                        <i class="fas fa-palette"></i>
                    </button>
                    <!-- Dropdown -->
                    <div id="themeDropdown" class="absolute right-0 mt-3 w-80 bg-card-bg border border-card-border rounded-2xl shadow-2xl p-3 hidden opacity-0 transition-opacity duration-200 z-[110]">
                        <h4 class="text-[11px] uppercase tracking-wider text-text-dark font-bold mb-2 px-2">Theme</h4>
                        <div class="grid grid-cols-6 gap-2 mb-4 px-2">
                            <button data-set-theme="brand" class="w-8 h-8 rounded-full flex overflow-hidden border-2 border-transparent shadow-sm cursor-pointer transition-transform hover:scale-110" title="Brand Light Theme">
                                <div class="w-1/2 h-full bg-[#ffffff]"></div>
                                <div class="w-1/2 h-full flex flex-col">
                                    <div class="h-1/2 w-full bg-[#00a8e8]"></div>
                                    <div class="h-1/2 w-full bg-[#f26522]"></div>
                                </div>
                            </button>
                            <button data-set-theme="brand-dark" class="w-8 h-8 rounded-full flex overflow-hidden border-2 border-transparent shadow-sm cursor-pointer transition-transform hover:scale-110" title="Brand Dark Theme">
                                <div class="w-1/2 h-full bg-[#031b4e]"></div>
                                <div class="w-1/2 h-full flex flex-col">
                                    <div class="h-1/2 w-full bg-[#00a8e8]"></div>
                                    <div class="h-1/2 w-full bg-[#f26522]"></div>
                                </div>
                            </button>
                            <button data-set-theme="dark" class="w-8 h-8 rounded-full flex overflow-hidden border-2 border-accent-blue cursor-pointer transition-transform hover:scale-110" title="Dark Theme">
                                <div class="w-1/2 h-full bg-[#031b4e]"></div>
                                <div class="w-1/2 h-full flex flex-col">
                                    <div class="h-1/2 w-full bg-[#129aef]"></div>
                                    <div class="h-1/2 w-full bg-[#ffb800]"></div>
                                </div>
                            </button>
                            <button data-set-theme="light" class="w-8 h-8 rounded-full flex overflow-hidden border-2 border-transparent shadow-sm cursor-pointer transition-transform hover:scale-110" title="Light Theme">
                                <div class="w-1/2 h-full bg-[#f8fafc]"></div>
                                <div class="w-1/2 h-full flex flex-col">
                                    <div class="h-1/2 w-full bg-[#2563eb]"></div>
                                    <div class="h-1/2 w-full bg-[#f59e0b]"></div>
                                </div>
                            </button>
                            <button data-set-theme="midnight" class="w-8 h-8 rounded-full flex overflow-hidden border-2 border-transparent shadow-sm cursor-pointer transition-transform hover:scale-110" title="Midnight Theme">
                                <div class="w-1/2 h-full bg-[#0f172a]"></div>
                                <div class="w-1/2 h-full flex flex-col">
                                    <div class="h-1/2 w-full bg-[#38bdf8]"></div>
                                    <div class="h-1/2 w-full bg-[#fbbf24]"></div>
                                </div>
                            </button>
                            <button data-set-theme="forest" class="w-8 h-8 rounded-full flex overflow-hidden border-2 border-transparent shadow-sm cursor-pointer transition-transform hover:scale-110" title="Forest Theme">
                                <div class="w-1/2 h-full bg-[#064e3b]"></div>
                                <div class="w-1/2 h-full flex flex-col">
                                    <div class="h-1/2 w-full bg-[#10b981]"></div>
                                    <div class="h-1/2 w-full bg-[#fcd34d]"></div>
                                </div>
                            </button>
                            <button data-set-theme="rose" class="w-8 h-8 rounded-full flex overflow-hidden border-2 border-transparent shadow-sm cursor-pointer transition-transform hover:scale-110" title="Rose Theme">
                                <div class="w-1/2 h-full bg-[#4c0519]"></div>
                                <div class="w-1/2 h-full flex flex-col">
                                    <div class="h-1/2 w-full bg-[#f43f5e]"></div>
                                    <div class="h-1/2 w-full bg-[#fda4af]"></div>
                                </div>
                            </button>
                            <button data-set-theme="sunset" class="w-8 h-8 rounded-full flex overflow-hidden border-2 border-transparent shadow-sm cursor-pointer transition-transform hover:scale-110" title="Sunset Theme">
                                <div class="w-1/2 h-full bg-[#fffbeb]"></div>
                                <div class="w-1/2 h-full flex flex-col">
                                    <div class="h-1/2 w-full bg-[#ea580c]"></div>
                                    <div class="h-1/2 w-full bg-[#e11d48]"></div>
                                </div>
                            </button>
                            <button data-set-theme="ocean" class="w-8 h-8 rounded-full flex overflow-hidden border-2 border-transparent shadow-sm cursor-pointer transition-transform hover:scale-110" title="Ocean Theme">
                                <div class="w-1/2 h-full bg-[#ecfeff]"></div>
                                <div class="w-1/2 h-full flex flex-col">
                                    <div class="h-1/2 w-full bg-[#0891b2]"></div>
                                    <div class="h-1/2 w-full bg-[#0284c7]"></div>
                                </div>
                            </button>
                            <button data-set-theme="purple" class="w-8 h-8 rounded-full flex overflow-hidden border-2 border-transparent shadow-sm cursor-pointer transition-transform hover:scale-110" title="Purple Theme">
                                <div class="w-1/2 h-full bg-[#2e1065]"></div>
                                <div class="w-1/2 h-full flex flex-col">
                                    <div class="h-1/2 w-full bg-[#a855f7]"></div>
                                    <div class="h-1/2 w-full bg-[#f472b6]"></div>
                                </div>
                            </button>
                            <button data-set-theme="coffee" class="w-8 h-8 rounded-full flex overflow-hidden border-2 border-transparent shadow-sm cursor-pointer transition-transform hover:scale-110" title="Coffee Theme">
                                <div class="w-1/2 h-full bg-[#451a03]"></div>
                                <div class="w-1/2 h-full flex flex-col">
                                    <div class="h-1/2 w-full bg-[#d97706]"></div>
                                    <div class="h-1/2 w-full bg-[#fde047]"></div>
                                </div>
                            </button>
                            <button data-set-theme="monochrome" class="w-8 h-8 rounded-full flex overflow-hidden border-2 border-transparent shadow-sm cursor-pointer transition-transform hover:scale-110" title="Monochrome Theme">
                                <div class="w-1/2 h-full bg-[#171717]"></div>
                                <div class="w-1/2 h-full flex flex-col">
                                    <div class="h-1/2 w-full bg-[#a3a3a3]"></div>
                                    <div class="h-1/2 w-full bg-[#ffffff]"></div>
                                </div>
                            </button>
                        </div>
                        <h4 class="text-[11px] uppercase tracking-wider text-text-dark font-bold mb-2 px-2">Font</h4>
                        <div class="grid grid-cols-2 gap-1">
                            <button data-set-font="outfit" class="text-left px-3 py-1.5 rounded-lg text-sm text-text-main bg-white/10 hover:bg-white/15 transition-colors font-outfit">Outfit</button>
                            <button data-set-font="inter" class="text-left px-3 py-1.5 rounded-lg text-sm text-text-dark hover:bg-white/10 transition-colors" style="font-family: 'Inter', sans-serif;">Inter</button>
                            <button data-set-font="roboto" class="text-left px-3 py-1.5 rounded-lg text-sm text-text-dark hover:bg-white/10 transition-colors" style="font-family: 'Roboto', sans-serif;">Roboto</button>
                            <button data-set-font="playfair" class="text-left px-3 py-1.5 rounded-lg text-sm text-text-dark hover:bg-white/10 transition-colors" style="font-family: 'Playfair Display', serif;">Playfair Display</button>
                            <button data-set-font="poppins" class="text-left px-3 py-1.5 rounded-lg text-sm text-text-dark hover:bg-white/10 transition-colors" style="font-family: 'Poppins', sans-serif;">Poppins</button>
                            <button data-set-font="montserrat" class="text-left px-3 py-1.5 rounded-lg text-sm text-text-dark hover:bg-white/10 transition-colors" style="font-family: 'Montserrat', sans-serif;">Montserrat</button>
                            <button data-set-font="lora" class="text-left px-3 py-1.5 rounded-lg text-sm text-text-dark hover:bg-white/10 transition-colors" style="font-family: 'Lora', serif;">Lora</button>
                            <button data-set-font="oswald" class="text-left px-3 py-1.5 rounded-lg text-sm text-text-dark hover:bg-white/10 transition-colors" style="font-family: 'Oswald', sans-serif;">Oswald</button>
                            <button data-set-font="nunito" class="text-left px-3 py-1.5 rounded-lg text-sm text-text-dark hover:bg-white/10 transition-colors" style="font-family: 'Nunito', sans-serif;">Nunito</button>
                            <button data-set-font="fira" class="text-left px-3 py-1.5 rounded-lg text-sm text-text-dark hover:bg-white/10 transition-colors" style="font-family: 'Fira Code', monospace;">Fira Code</button>
                        </div>
                    </div>
                </div>

                @auth
                    <a href="{{ auth()->user()->role === 'candidate' ? route('candidate.dashboard') : (auth()->user()->role === 'employer' ? route('employer.dashboard') : route('admin.dashboard')) }}"
                        class="px-4 py-2 rounded-xl font-medium text-[13px] cursor-pointer transition-all bg-white/10 text-text-main hover:bg-white/20 border border-white/15 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-accent-blue text-white flex items-center justify-center text-[10px] font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-xl font-medium text-[13px] cursor-pointer transition-all text-red-400 hover:bg-red-500/10 border border-red-500/20 flex items-center gap-1.5">
                            <i class="fas fa-sign-out-alt text-xs"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="/login"
                        class="px-5 py-2 rounded-xl font-medium text-[13px] cursor-pointer transition-all bg-white/10 text-text-main hover:bg-white/20 border border-white/15">Login</a>
                    <a href="/register"
                        class="px-5 py-2 rounded-xl font-medium text-[13px] cursor-pointer transition-all bg-accent-blue text-white hover:bg-accent-blue-hover hover:-translate-y-0.5 shadow-glow-blue">Register</a>
                @endauth
            </div>
        </nav>
        <!-- Mobile Menu Button -->
        <button id="mobileMenuBtn" class="lg:hidden text-text-main text-2xl focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>
    </header>

    <!-- Mobile Menu Overlay -->
    <div id="mobileMenu" class="fixed inset-0 bg-primary-bg z-[105] transform translate-x-full transition-transform duration-300 lg:hidden flex flex-col">
        <div class="flex justify-between items-center p-6 border-b border-card-border">
            <img src="/images/logo.png" alt="Logo" class="h-10 w-auto">
            <button id="closeMobileMenuBtn" class="text-text-main text-2xl focus:outline-none"><i class="fas fa-times"></i></button>
        </div>
        <div class="flex-grow overflow-y-auto p-6 flex flex-col gap-6">
            <ul class="flex flex-col gap-5 text-lg font-semibold">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-accent-blue' : 'text-text-main hover:text-accent-blue' }} transition-colors">Home</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-accent-blue' : 'text-text-main hover:text-accent-blue' }} transition-colors">About us</a></li>
                <li><a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'text-accent-blue' : 'text-text-main hover:text-accent-blue' }} transition-colors">Our Services</a></li>
                <li><a href="{{ route('jobs') }}" class="{{ request()->routeIs('jobs') ? 'text-accent-blue' : 'text-text-main hover:text-accent-blue' }} transition-colors">Jobs</a></li>
                <li><a href="{{ route('resume.builder') }}" class="{{ request()->routeIs('resume.builder') ? 'text-accent-blue' : 'text-text-main hover:text-accent-blue' }} transition-colors">Resume Builder <span class="bg-accent-yellow text-white text-[8px] px-1 py-0.5 rounded uppercase font-bold ml-1 relative -top-1">Free</span></a></li>
                <li><a href="{{ route('hiring') }}" class="{{ request()->routeIs('hiring') ? 'text-accent-blue' : 'text-text-main hover:text-accent-blue' }} transition-colors">Hiring Process</a></li>
                <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-accent-blue' : 'text-text-main hover:text-accent-blue' }} transition-colors">Contact us</a></li>
            </ul>
            
            <div class="h-px bg-card-border w-full"></div>
            
            <div class="flex flex-col gap-3">
                @auth
                    <a href="{{ auth()->user()->role === 'candidate' ? route('candidate.dashboard') : (auth()->user()->role === 'employer' ? route('employer.dashboard') : route('admin.dashboard')) }}" class="px-5 py-3.5 rounded-xl font-medium text-center bg-accent-blue text-white shadow-glow-blue flex items-center justify-center gap-2">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-5 py-3.5 rounded-xl font-medium text-center text-red-400 border border-red-500/20 hover:bg-red-500/10 transition-colors">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="/login" class="px-5 py-3.5 rounded-xl font-medium text-center bg-white/10 text-text-main">Login</a>
                    <a href="/register" class="px-5 py-3.5 rounded-xl font-medium text-center bg-accent-blue text-white shadow-glow-blue">Register</a>
                @endauth
            </div>
        </div>
    </div>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-primary-bg pt-12 pb-5 px-6 lg:px-[5%] text-text-main relative z-50">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
            <div>
                <a href="#" class="flex items-center no-underline mb-5">
                    <img src="/images/logo.png" alt="Vedanta Placement Agency"
                        class="h-14 w-auto object-contain">
                </a>
                <p class="text-xs text-text-main opacity-50 leading-relaxed mb-5">
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
                    <a href="{{ route('apply') }}" class="hover:text-accent-blue transition-colors">Apply for Job</a>
                    <a href="{{ route('about') }}" class="hover:text-accent-blue transition-colors">About us</a>
                    <a href="{{ route('post-job') }}" class="hover:text-accent-blue transition-colors">Post your Job</a>
                    <a href="{{ route('contact') }}" class="hover:text-accent-blue transition-colors">Contact us</a>
                    <a href="{{ route('terms') }}" class="hover:text-accent-blue transition-colors">Terms & Conditions</a>
                    <a href="{{ route('services') }}" class="hover:text-accent-blue transition-colors">Services</a>
                    <a href="{{ route('privacy') }}" class="hover:text-accent-blue transition-colors">Privacy Policy</a>
                    <a href="{{ route('jobs') }}" class="hover:text-accent-blue transition-colors">Jobs</a>
                    <a href="{{ route('media') }}" class="hover:text-accent-blue transition-colors">Media</a>
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
            class="w-12 h-12 rounded-full flex items-center justify-center text-text-main text-lg no-underline shadow-lg transition-all duration-300 hover:scale-110 hover:-translate-y-1 bg-accent-blue"><i
                class="fas fa-phone-alt"></i></a>
        <a href="#"
            class="w-12 h-12 rounded-full flex items-center justify-center text-text-main text-xl no-underline shadow-lg transition-all duration-300 hover:scale-110 hover:-translate-y-1 bg-[#25D366]"><i
                class="fab fa-whatsapp"></i></a>
        <a href="#"
            class="w-12 h-12 rounded-full flex items-center justify-center text-text-main text-lg no-underline shadow-lg transition-all duration-300 hover:scale-110 hover:-translate-y-1 bg-accent-blue"
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
                btnEmployer.className = "role-btn px-5 lg:px-8 py-3 rounded-xl text-sm font-semibold text-text-main opacity-70 flex items-center gap-2.5 transition-all duration-300 border-none cursor-pointer bg-transparent hover:text-text-main hover:bg-white/10";
            } else {
                btnEmployer.className = "role-btn px-5 lg:px-8 py-3 rounded-xl text-sm font-semibold flex items-center gap-2.5 transition-all duration-300 border-none cursor-pointer bg-accent-blue text-white shadow-glow-blue";
                btnSeeker.className = "role-btn px-5 lg:px-8 py-3 rounded-xl text-sm font-semibold text-text-main opacity-70 flex items-center gap-2.5 transition-all duration-300 border-none cursor-pointer bg-transparent hover:text-text-main hover:bg-white/10";
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
    <script>
        
        // Mobile Menu Logic
        document.addEventListener('DOMContentLoaded', () => {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const closeMobileMenuBtn = document.getElementById('closeMobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');

            if(mobileMenuBtn && closeMobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', () => {
                    mobileMenu.classList.remove('translate-x-full');
                });

                closeMobileMenuBtn.addEventListener('click', () => {
                    mobileMenu.classList.add('translate-x-full');
                });
            }
        });

        // Theme and Font Switcher Logic
        document.addEventListener('DOMContentLoaded', () => {
            const themeBtn = document.getElementById('themeSwitcherBtn');
            const themeDropdown = document.getElementById('themeDropdown');
            const htmlEl = document.documentElement;
            
            themeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if(themeDropdown.classList.contains('hidden')) {
                    themeDropdown.classList.remove('hidden');
                    setTimeout(() => themeDropdown.classList.remove('opacity-0'), 10);
                } else {
                    themeDropdown.classList.add('opacity-0');
                    setTimeout(() => themeDropdown.classList.add('hidden'), 200);
                }
            });

            document.addEventListener('click', (e) => {
                if (!themeDropdown.contains(e.target) && !themeBtn.contains(e.target)) {
                    themeDropdown.classList.add('opacity-0');
                    setTimeout(() => themeDropdown.classList.add('hidden'), 200);
                }
            });

            const themeButtons = document.querySelectorAll('[data-set-theme]');
            const applyTheme = (theme) => {
                htmlEl.setAttribute('data-theme', theme);
                localStorage.setItem('vedanta-theme', theme);
                themeButtons.forEach(btn => {
                    if(btn.dataset.setTheme === theme) {
                        btn.classList.add('border-accent-blue');
                        btn.classList.remove('border-transparent');
                    } else {
                        btn.classList.remove('border-accent-blue');
                        btn.classList.add('border-transparent');
                    }
                });
            };

            themeButtons.forEach(btn => {
                btn.addEventListener('click', () => applyTheme(btn.dataset.setTheme));
            });

            const fontButtons = document.querySelectorAll('[data-set-font]');
            const applyFont = (font) => {
                htmlEl.setAttribute('data-font', font);
                localStorage.setItem('vedanta-font', font);
                
                const fontsMap = {
                    'outfit': "'Outfit', sans-serif",
                    'inter': "'Inter', sans-serif",
                    'roboto': "'Roboto', sans-serif",
                    'playfair': "'Playfair Display', serif",
                    'poppins': "'Poppins', sans-serif",
                    'montserrat': "'Montserrat', sans-serif",
                    'lora': "'Lora', serif",
                    'oswald': "'Oswald', sans-serif",
                    'nunito': "'Nunito', sans-serif",
                    'fira': "'Fira Code', monospace"
                };
                document.body.style.fontFamily = fontsMap[font] || "'Outfit', sans-serif";

                fontButtons.forEach(btn => {
                    if(btn.dataset.setFont === font) {
                        btn.classList.replace('text-text-dark', 'text-text-main');
                        btn.classList.add('bg-white/10');
                    } else {
                        btn.classList.replace('text-text-main', 'text-text-dark');
                        btn.classList.remove('bg-white/10');
                    }
                });
            };

            fontButtons.forEach(btn => {
                btn.addEventListener('click', () => applyFont(btn.dataset.setFont));
            });

            const savedTheme = localStorage.getItem('vedanta-theme') || 'dark';
            const savedFont = localStorage.getItem('vedanta-font') || 'outfit';
            applyTheme(savedTheme);
            applyFont(savedFont);
        });
    </script>
    <script>
        // Preloader Logic
        window.addEventListener('load', () => {
            const loader = document.getElementById('page-loader');
            if (loader) {
                // Short delay to ensure logo and loader are visible and look premium
                setTimeout(() => {
                    loader.classList.add('opacity-0');
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 700); // Matches CSS transition duration
                }, 600); 
            }
        });
    </script>
    @stack('scripts')
</body>

</html>