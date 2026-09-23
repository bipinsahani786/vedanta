<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vedanta Placement Agency® — The Gold Standard in Education Recruitment')</title>
    <meta name="description"
        content="@yield('meta_description', 'Vedanta Placement Agency® connects educators and schools across India. Find top teaching jobs or hire expert educators with us.')">
    <meta name="application-name" content="Vedanta Placement Agency®">
    <meta name="apple-mobile-web-app-title" content="Vedanta Placement Agency®">
    <meta property="og:site_name" content="Vedanta Placement Agency®">
    <meta property="og:title" content="@yield('title', 'Vedanta Placement Agency® — The Gold Standard in Education Recruitment')">

    <!-- Google Site Name Structured Data -->
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'Vedanta Placement Agency®',
        'alternateName' => ['Vedanta Placement Agency', 'VPA'],
        'url' => url('/')
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600;700&family=Lora:wght@400;500;600;700&family=Oswald:wght@300;400;500;600;700&family=Nunito:wght@300;400;500;600;700&family=Fira+Code:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12) !important;
            border-color: #f1f5f9 !important;
        }

        /* Nav link colors based on scroll state */
        #main-header:not(.header-scrolled) .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        #main-header:not(.header-scrolled) .nav-link:hover {
            color: #ffffff !important;
        }

        .header-scrolled .nav-link {
            color: #334155 !important;
        }

        .header-scrolled .nav-link:hover {
            color: #129aef !important;
        }

        #main-header:not(.header-scrolled) .active-link {
            color: #129aef !important;
        }

        .header-scrolled .active-link {
            color: #129aef !important;
        }

        .header-scrolled .logo-img {
            /* New logo has its own colors, no need to invert on scroll anymore */
            transition: all 0.3s ease;
        }

        /* Mobile Menu Button colors based on scroll state */
        #main-header:not(.header-scrolled) #mobileMenuBtn {
            color: #ffffff !important;
        }

        .header-scrolled #mobileMenuBtn {
            color: #010127ff !important;
        }

        /* Search bar styling in Header */
        #main-header:not(.header-scrolled) .nav-search-bar {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
        }
        #main-header:not(.header-scrolled) .nav-search-bar input {
            color: #ffffff;
        }
        #main-header:not(.header-scrolled) .nav-search-bar input::placeholder {
            color: rgba(203, 213, 225, 0.7);
        }
        #main-header:not(.header-scrolled) .nav-search-bar .search-icon {
            color: #cbd5e1;
        }
        #main-header:not(.header-scrolled) .nav-search-bar .filter-icon {
            color: #94a3b8;
            border-color: rgba(255, 255, 255, 0.2);
        }

        .header-scrolled .nav-search-bar {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
        }
        .header-scrolled .nav-search-bar input {
            color: #0f172a;
        }
        .header-scrolled .nav-search-bar input::placeholder {
            color: #64748b;
        }
        .header-scrolled .nav-search-bar .search-icon {
            color: #475569;
        }
        .header-scrolled .nav-search-bar .filter-icon {
            color: #64748b;
            border-color: #cbd5e1;
        }
        .header-scrolled .nav-search-bar .filter-icon:hover,
        .header-scrolled .nav-search-bar .search-icon:hover {
            color: #129aef;
        }
    </style>
</head>

<body class="bg-secondary-bg text-text-dark {{ session()->has('impersonate_admin_id') ? 'pt-10' : '' }}">

    <!-- Preloader removed per user request -->

    @if(session()->has('impersonate_admin_id'))
        <div class="fixed top-0 left-0 w-full z-[9999] bg-gradient-to-r from-red-600 to-red-500 text-white text-center py-2 px-4 shadow-lg flex justify-center items-center gap-4 text-sm font-semibold">
            <span><i class="fas fa-user-secret mr-2"></i> You are currently impersonating <strong>{{ auth()->user()->name }}</strong> ({{ ucfirst(auth()->user()->role) }}).</span>
            <a href="{{ route('admin.impersonate.leave') }}" class="bg-white text-red-600 px-3 py-1 rounded-full text-xs font-bold hover:bg-red-50 transition-colors shadow-sm">
                Return to Admin <i class="fas fa-sign-out-alt ml-1"></i>
            </a>
        </div>
    @endif    <!-- Global Marquee Bar (Moving text left-to-right) -->
    <div
        class="sticky top-0 w-full block bg-gradient-to-r from-[#040e2d] via-[#129aef] to-[#040e2d] text-white text-[11px] md:text-sm py-1.5 md:py-2 px-2 md:px-6 font-semibold tracking-wide shadow-md z-[105] border-b border-white/20">
        <marquee behavior="scroll" direction="left" scrollamount="6" class="flex items-center mt-0.5">
            ✨ Welcome to Vedanta Placement Agency® — India's Most Trusted Education Recruitment Partner! Connecting
            passionate educators with premier institutions across the nation. ✨
        </marquee>
    </div>

    <!-- Top Contact Bar -->
    <div
        class="bg-gradient-to-r from-[#040e2d] via-[#129aef] to-[#040e2d] text-white text-xs py-2 px-6 lg:px-[5%] hidden md:flex justify-between items-center border-b border-white/10 shadow-sm">
        <div class="flex items-center gap-6">
            <span class="flex items-center gap-1.5"><i class="fas fa-envelope text-[#ffb800] text-[10px]"></i>
                info@vedantaplacementagency.in</span>
            <span class="flex items-center gap-1.5"><i class="fas fa-phone-alt text-[#ffb800] text-[10px]"></i>
                +91-7070938975</span>
            <span class="flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-[#ffb800] text-[10px]"></i>
                Patna, Bihar, India</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="https://www.facebook.com/share/1Dmruc9Esx/" target="_blank" class="text-white/80 hover:text-[#ffb800] transition-colors"><i
                    class="fab fa-facebook-f"></i></a>
            <a href="https://www.instagram.com/vedantaplacementagency?igsh=MWUwczFoYzQ2eGUydQ==" target="_blank" class="text-white/80 hover:text-[#ffb800] transition-colors"><i
                    class="fab fa-instagram"></i></a>
            <a href="https://www.linkedin.com/company/vedanta-placement-agency-india/" target="_blank" class="text-white/80 hover:text-[#ffb800] transition-colors"><i
                    class="fab fa-linkedin-in"></i></a>
            <a href="https://youtube.com/@vedantaplacementagency?si=UZ0VPukRGknF4EhQ" target="_blank" class="text-white/80 hover:text-[#ffb800] transition-colors"><i class="fab fa-youtube"></i></a>
            <a href="https://wa.me/917070938975" target="_blank" class="text-white/80 hover:text-[#ffb800] transition-colors"><i class="fab fa-whatsapp"></i></a>
        </div>
    </div>

    <!-- Header -->
    <header id="main-header"
        class="sticky top-10 md:top-12 w-[96%] lg:w-[94%] xl:w-[92%] 2xl:w-[90%] max-w-[1440px] mx-auto bg-[#040e2d]/80 backdrop-blur-md border border-white/20 rounded-full px-5 lg:px-7 py-2.5 flex justify-between items-center z-[100] transition-all duration-500 shadow-xl mt-2 lg:mt-4">
        <a href="{{ route('home') }}" class="flex items-center no-underline py-1 shrink-0">
            <img src="/images/logo.png?v={{ time() }}" alt="Vedanta Placement Agency®"
                class="logo-img h-9 md:h-11 w-auto object-contain transition-all duration-300">
        </a>
        <nav class="hidden lg:flex items-center">
            <ul class="flex gap-3.5 xl:gap-5 2xl:gap-6 mr-3 xl:mr-5 list-none items-center">
                <li><a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') ? 'active-link after:w-full' : 'nav-link hover:after:w-full' }} font-bold text-[14px] xl:text-[15px] transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-accent-blue after:transition-all {{ !request()->routeIs('home') ? 'after:w-0' : '' }}">Home</a>
                </li>
                <li><a href="{{ route('about') }}"
                        class="{{ request()->routeIs('about') ? 'active-link after:w-full' : 'nav-link hover:after:w-full' }} font-bold text-[14px] xl:text-[15px] transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-accent-blue after:transition-all {{ !request()->routeIs('about') ? 'after:w-0' : '' }}">About
                        us</a></li>
                <li><a href="{{ route('services') }}"
                        class="{{ request()->routeIs('services') ? 'active-link after:w-full' : 'nav-link hover:after:w-full' }} font-bold text-[14px] xl:text-[15px] transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-accent-blue after:transition-all {{ !request()->routeIs('services') ? 'after:w-0' : '' }}">Our
                        Services</a></li>
                <li><a href="{{ route('jobs') }}"
                        class="{{ request()->routeIs('jobs') ? 'active-link after:w-full' : 'nav-link hover:after:w-full' }} font-bold text-[14px] xl:text-[15px] transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-accent-blue after:transition-all {{ !request()->routeIs('jobs') ? 'after:w-0' : '' }}">Jobs</a>
                </li>
                <li><a href="{{ route('resume.builder') }}"
                        class="{{ request()->routeIs('resume.builder') ? 'active-link after:w-full' : 'nav-link hover:after:w-full' }} font-bold text-[14px] xl:text-[15px] transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-accent-blue after:transition-all {{ !request()->routeIs('resume.builder') ? 'after:w-0' : '' }}">Resume
                        Builder <span
                            class="bg-accent-yellow text-white text-[9px] px-1.5 py-0.5 rounded uppercase font-extrabold ml-1 relative -top-1">Free</span></a>
                </li>
                <li><a href="{{ route('hiring') }}"
                        class="{{ request()->routeIs('hiring') ? 'active-link after:w-full' : 'nav-link hover:after:w-full' }} font-bold text-[14px] xl:text-[15px] transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-accent-blue after:transition-all {{ !request()->routeIs('hiring') ? 'after:w-0' : '' }}">Hiring
                        Process</a></li>
                <li><a href="{{ route('contact') }}"
                        class="{{ request()->routeIs('contact') ? 'active-link after:w-full' : 'nav-link hover:after:w-full' }} font-bold text-[14px] xl:text-[15px] transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:bg-accent-blue after:transition-all {{ !request()->routeIs('contact') ? 'after:w-0' : '' }}">Contact
                        Us</a></li>
            </ul>

            <!-- Navbar Search Bar (Working Search with Live Dropdown) -->
            <div class="relative mr-3 xl:mr-5"
                 x-data="{
                     query: '{{ request('q') }}',
                     showDropdown: false,
                     loading: false,
                     suggestions: [],
                     fetchSuggestions() {
                         if (this.query.trim().length < 2) {
                             this.suggestions = [];
                             this.showDropdown = false;
                             return;
                         }
                         this.loading = true;
                         this.showDropdown = true;
                         fetch('{{ route('api.jobs.suggestions') }}?q=' + encodeURIComponent(this.query))
                             .then(r => r.json())
                             .then(data => {
                                 this.suggestions = data.jobs || [];
                                 this.loading = false;
                             })
                             .catch(() => { this.loading = false; });
                     }
                 }"
                 @click.away="showDropdown = false">
                <form action="{{ route('jobs') }}" method="GET" class="relative flex items-center m-0">
                    <div class="nav-search-bar flex items-center border rounded-xl px-3 py-1.5 xl:py-2 transition-all duration-300 w-48 xl:w-60 2xl:w-68 shadow-sm focus-within:ring-2 focus-within:ring-[#129aef]/40">
                        <input type="text"
                               name="q"
                               x-model="query"
                               @input.debounce.300ms="fetchSuggestions()"
                               @keydown.escape="showDropdown = false"
                               @focus="if(query.trim().length >= 2) showDropdown = true"
                               placeholder="Search jobs, subjects, or locations..."
                               autocomplete="off"
                               class="bg-transparent border-none outline-none text-xs xl:text-[13px] w-full pr-1.5 focus:ring-0">
                        
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button type="submit" class="search-icon hover:scale-110 transition-transform cursor-pointer" title="Search">
                                <i class="fas fa-search text-xs"></i>
                            </button>
                            <a href="{{ route('jobs') }}" class="filter-icon pl-2 border-l transition-colors" title="Filter Jobs">
                                <i class="fas fa-sliders-h text-xs"></i>
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Live Search Suggestions Dropdown -->
                <div x-show="showDropdown && (suggestions.length > 0 || loading)"
                     x-cloak
                     class="absolute top-full left-0 mt-2 w-72 sm:w-80 bg-[#040e2d]/95 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl overflow-hidden z-[110] py-2 text-xs"
                     style="display: none;">
                    
                    <div x-show="loading" class="px-4 py-3 text-slate-300 flex items-center gap-2">
                        <i class="fas fa-circle-notch fa-spin text-[#129aef]"></i>
                        <span>Searching jobs...</span>
                    </div>

                    <div x-show="!loading && suggestions.length > 0">
                        <div class="px-3.5 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider flex items-center justify-between">
                            <span>Matching Jobs</span>
                            <span class="text-[#129aef]" x-text="suggestions.length + ' found'"></span>
                        </div>
                        <template x-for="item in suggestions" :key="item.id">
                            <a :href="item.url" class="flex items-center justify-between px-3.5 py-2 hover:bg-white/10 transition-colors group">
                                <div class="min-w-0 pr-2">
                                    <div class="font-bold text-white group-hover:text-[#129aef] transition-colors truncate" x-text="item.title"></div>
                                    <div class="text-[11px] text-slate-400 flex items-center gap-2 mt-0.5">
                                        <span x-show="item.subject" x-text="item.subject"></span>
                                        <span x-show="item.subject && item.city">•</span>
                                        <span x-show="item.city" class="text-rose-400 flex items-center gap-1">
                                            <i class="fas fa-map-marker-alt text-[9px]"></i>
                                            <span x-text="item.city"></span>
                                        </span>
                                    </div>
                                </div>
                                <i class="fas fa-arrow-right text-[10px] text-slate-500 group-hover:text-white transition-colors"></i>
                            </a>
                        </template>

                        <div class="pt-2 mt-1 border-t border-white/10 px-3.5 pb-1">
                            <a :href="'{{ route('jobs') }}?q=' + encodeURIComponent(query)" class="text-[11px] font-bold text-[#129aef] hover:text-white flex items-center justify-between transition-colors">
                                <span>View all results for "<span x-text="query"></span>"</span>
                                <i class="fas fa-chevron-right text-[9px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-2.5 xl:gap-3 items-center shrink-0">
                @auth
                    <a href="{{ auth()->user()->role === 'candidate' ? route('candidate.dashboard') : (auth()->user()->role === 'employer' ? route('employer.dashboard') : route('admin.dashboard')) }}"
                        class="px-3.5 xl:px-4 py-2 rounded-xl font-medium text-[13px] cursor-pointer transition-all bg-slate-100 text-slate-800 hover:bg-slate-200 border border-slate-200 flex items-center gap-2">
                        <div
                            class="w-6 h-6 rounded-full bg-accent-blue text-white flex items-center justify-center text-[10px] font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="px-3.5 xl:px-4 py-2 rounded-xl font-medium text-[13px] cursor-pointer transition-all text-red-500 hover:bg-red-50 border border-red-200 flex items-center gap-1.5">
                            <i class="fas fa-sign-out-alt text-xs"></i> Logout
                        </button>
                    </form>
                @else
                    <button type="button" onclick="openAuthChoiceModal('login')"
                        class="px-4 xl:px-5 py-2 xl:py-2.5 rounded-xl font-bold text-[14px] xl:text-[15px] cursor-pointer transition-all bg-slate-100 text-slate-800 hover:bg-slate-200 border border-slate-200">Login</button>
                    <button type="button" onclick="openAuthChoiceModal('register')"
                        class="px-4 xl:px-5 py-2 xl:py-2.5 rounded-xl font-bold text-[14px] xl:text-[15px] cursor-pointer transition-all bg-accent-blue text-white hover:bg-accent-blue-hover hover:-translate-y-0.5 shadow-glow-blue">Register</button>
                @endauth
            </div>
        </nav>
        <!-- Mobile Menu Button -->
        <button id="mobileMenuBtn" class="lg:hidden text-white text-2xl focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>
    </header>

    <!-- Mobile Menu Overlay -->
    <div id="mobileMenu"
        class="fixed inset-0 bg-primary-bg z-[105] transform translate-x-full transition-transform duration-300 lg:hidden flex flex-col">
        <div class="flex justify-between items-center p-6 border-b border-card-border">
            <img src="/images/logo.png" alt="Logo" class="h-14 w-auto object-contain">
            <button id="closeMobileMenuBtn" class="text-text-main text-2xl focus:outline-none"><i
                    class="fas fa-times"></i></button>
        </div>
        <div class="flex-grow overflow-y-auto p-6 flex flex-col gap-5">
            <!-- Mobile Search Bar -->
            <form action="{{ route('jobs') }}" method="GET" class="relative">
                <div class="flex items-center bg-card-bg border border-card-border rounded-xl px-4 py-2.5 shadow-inner">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Search jobs, subjects, or locations..." 
                           class="bg-transparent border-none outline-none text-text-main text-xs sm:text-sm w-full placeholder-text-dark/40">
                    <button type="submit" class="text-accent-blue pl-2 shrink-0">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            <ul class="flex flex-col gap-5 text-lg font-semibold">
                <li><a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') ? 'text-accent-blue' : 'text-text-main hover:text-accent-blue' }} transition-colors">Home</a>
                </li>
                <li><a href="{{ route('about') }}"
                        class="{{ request()->routeIs('about') ? 'text-accent-blue' : 'text-text-main hover:text-accent-blue' }} transition-colors">About
                        us</a></li>
                <li><a href="{{ route('services') }}"
                        class="{{ request()->routeIs('services') ? 'text-accent-blue' : 'text-text-main hover:text-accent-blue' }} transition-colors">Our
                        Services</a></li>
                <li><a href="{{ route('jobs') }}"
                        class="{{ request()->routeIs('jobs') ? 'text-accent-blue' : 'text-text-main hover:text-accent-blue' }} transition-colors">Jobs</a>
                </li>
                <li><a href="{{ route('resume.builder') }}"
                        class="{{ request()->routeIs('resume.builder') ? 'text-accent-blue' : 'text-text-main hover:text-accent-blue' }} transition-colors">Resume
                        Builder <span
                            class="bg-accent-yellow text-white text-[8px] px-1 py-0.5 rounded uppercase font-bold ml-1 relative -top-1">Free</span></a>
                </li>
                <li><a href="{{ route('hiring') }}"
                        class="{{ request()->routeIs('hiring') ? 'text-accent-blue' : 'text-text-main hover:text-accent-blue' }} transition-colors">Hiring
                        Process</a></li>
                <li><a href="{{ route('contact') }}"
                        class="{{ request()->routeIs('contact') ? 'text-accent-blue' : 'text-text-main hover:text-accent-blue' }} transition-colors">Contact
                        us</a></li>
            </ul>

            <div class="h-px bg-card-border w-full"></div>

            <div class="flex flex-col gap-3">
                @auth
                    <a href="{{ auth()->user()->role === 'candidate' ? route('candidate.dashboard') : (auth()->user()->role === 'employer' ? route('employer.dashboard') : route('admin.dashboard')) }}"
                        class="px-5 py-3.5 rounded-xl font-medium text-center bg-accent-blue text-white shadow-glow-blue flex items-center justify-center gap-2">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full px-5 py-3.5 rounded-xl font-medium text-center text-red-400 border border-red-500/20 hover:bg-red-500/10 transition-colors">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </button>
                    </form>
                @else
                    <button type="button" onclick="closeMobileMenu(); openAuthChoiceModal('login');"
                        class="px-5 py-3.5 rounded-xl font-medium text-center bg-white/10 text-text-main hover:bg-white/20 transition-colors">Login</button>
                    <button type="button" onclick="closeMobileMenu(); openAuthChoiceModal('register');"
                        class="px-5 py-3.5 rounded-xl font-medium text-center bg-accent-blue text-white shadow-glow-blue hover:bg-accent-blue-hover transition-colors">Register</button>
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
                    <img src="/images/logo.png" alt="Vedanta Placement Agency®"
                        class="h-20 md:h-24 w-auto object-contain">
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
                    <a href="{{ route('home') }}" class="hover:text-accent-blue transition-colors">Home</a>
                    <a href="{{ route('about') }}" class="hover:text-accent-blue transition-colors">About us</a>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.jobs.create') }}" class="hover:text-accent-blue transition-colors">Post your Job</a>
                        @else
                            <a href="{{ route('employer.jobs.create') }}" class="hover:text-accent-blue transition-colors">Post your Job</a>
                        @endif
                    @else
                        <a href="{{ route('employer.jobs.create') }}" class="hover:text-accent-blue transition-colors">Post your Job</a>
                    @endauth
                    <a href="{{ route('contact') }}" class="hover:text-accent-blue transition-colors">Contact us</a>
                    <a href="{{ route('terms') }}" class="hover:text-accent-blue transition-colors">Terms &
                        Conditions</a>
                    {{-- <a href="{{ route('refund') }}" class="hover:text-accent-blue transition-colors">Refund,
                        Cancellation & Payment Policy</a> --}}
                    {{-- <a href="{{ route('pricing')}}" class="hover:text-accent-blue transition-colors"> Pricing & Service
                        Charges Policy</a> --}}
                    <a href="{{ route('cookie') }}" class="hover:text-accent-blue transition-colors">Cookie Policy</a>
                    <a href="{{ route('disclaimer') }}" class="hover:text-accent-blue transition-colors">Disclaimer</a>
                    <a href="{{ route('services') }}" class="hover:text-accent-blue transition-colors">Services</a>
                    <a href="{{ route('privacy') }}" class="hover:text-accent-blue transition-colors">Privacy Policy</a>
                    <a href="{{ route('jobs') }}" class="hover:text-accent-blue transition-colors">Jobs</a>
                    <a href="{{ route('media') }}" class="hover:text-accent-blue transition-colors">Media</a>
                    <a href="{{ route('employer') }}" class="hover:text-accent-blue transition-colors">Employer</a>
                    <a href="{{ route('candidate') }}" class="hover:text-accent-blue transition-colors">Candidate</a>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-bold mb-5 tracking-wider uppercase">Social</h4>
                <ul class="space-y-2.5 text-xs text-gray-400">
                    <li><a href="https://www.instagram.com/vedantaplacementagency?igsh=MWUwczFoYzQ2eGUydQ=="
                            target="'blank"
                            class="flex items-center gap-2.5 hover:text-accent-blue transition-colors"><i
                                class="fab fa-instagram text-sm w-4 text-center"></i> Instagram</a></li>
                    <li><a href="https://www.facebook.com/share/1Dmruc9Esx/" target="blank"
                            class="flex items-center gap-2.5 hover:text-accent-blue transition-colors"><i
                                class="fab fa-facebook text-sm w-4 text-center"></i> Facebook</a></li>
                    <li><a href="https://youtube.com/@vedantaplacementagency?si=UZ0VPukRGknF4EhQ" target="blank"
                            class="flex items-center gap-2.5 hover:text-accent-blue transition-colors"><i
                                class="fab fa-youtube text-sm w-4 text-center"></i> Youtube</a></li>
                    <li><a href="https://wa.me/917070938975" target="_blank"
                            class="flex items-center gap-2.5 hover:text-accent-blue transition-colors"><i
                                class="fab fa-whatsapp text-sm w-4 text-center"></i> Whatsapp</a></li>
                    <li><a href="https://www.linkedin.com/company/vedanta-placement-agency-india/" target='blank'
                            class="flex items-center gap-2.5 hover:text-accent-blue transition-colors"><i
                                class="fab fa-linkedin text-sm w-4 text-center"></i> Linkedin</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-bold mb-5 tracking-wider uppercase">Get In Touch</h4>
                <div class="text-xs text-gray-400 space-y-4">
                    <p class="leading-relaxed flex gap-2"><i class="fas fa-map-marker-alt text-accent-b lue mt-0.5"></i>
                        Career Point Building, 2nd floor,<br>Patna, 800001, Bihar</p>
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
            <a href="https://startupwebsupport.com" class="text-accent-blue hover:text-accent-blue">Designed By: Startup Web Support</a>
        </div>
    </footer>

    <!-- FABs -->
    <div class="fixed right-6 bottom-6 flex flex-col gap-3 z-[999]">
        <a href="tel:+917070938975" title="Call Us"
            class="w-12 h-12 rounded-full flex items-center justify-center text-text-main text-lg no-underline shadow-lg transition-all duration-300 hover:scale-110 hover:-translate-y-1 bg-accent-blue"><i
                class="fas fa-phone-alt"></i></a>
        <a href="https://wa.me/917070938975" target="_blank" title="WhatsApp Us"
            class="w-12 h-12 rounded-full flex items-center justify-center text-text-main text-xl no-underline shadow-lg transition-all duration-300 hover:scale-110 hover:-translate-y-1 bg-[#25D366]"><i
                class="fab fa-whatsapp"></i></a>
        <a href="#" title="Scroll to Top"
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
                    speed: 2000,
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
                header.classList.remove('py-2');
                header.classList.add('py-1');
            } else {
                header.classList.remove('header-scrolled');
                header.classList.add('py-2');
                header.classList.remove('py-1');
            }
        });

        // ---- Role Toggle ----
        const contentData = {
            seeker: {
                title: "Get placed in top<br>schools across...",
                subtitle: "step into the right opportunity with trusted schools that value your talent",
                ctaText: "Job Seeker",
                ctaLink: "{{ route('candidate.register') }}",
                imgUrl: "images/2.jpeg",
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
                title: "Hire the Minds <br> That Shape Tomorrow",
                subtitle: "partner with us to find top-tier teaching professionals for your institution",
                ctaText: "Employer",
                ctaLink: "{{ route('employer.register') }}",
                imgUrl: "images/women.jpg",
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
            const btnSeekerMob = document.getElementById('btn-seeker-mobile');
            const btnEmployerMob = document.getElementById('btn-employer-mobile');

            const activeClass = "role-btn flex-1 py-3.5 rounded-lg text-[15px] font-extrabold flex items-center justify-center gap-2.5 transition-all duration-300 bg-gradient-to-r from-[#2196f3] to-[#00bcd4] text-white shadow-md";
            const inactiveClass = "role-btn flex-1 py-3.5 rounded-lg text-[15px] font-extrabold text-slate-800 flex items-center justify-center gap-2.5 transition-all duration-300 bg-transparent hover:bg-slate-50";

            if (role === 'seeker') {
                if (btnSeeker) btnSeeker.className = activeClass;
                if (btnEmployer) btnEmployer.className = inactiveClass;
                if (btnSeekerMob) btnSeekerMob.className = activeClass;
                if (btnEmployerMob) btnEmployerMob.className = inactiveClass;
            } else {
                if (btnEmployer) btnEmployer.className = activeClass;
                if (btnSeeker) btnSeeker.className = inactiveClass;
                if (btnEmployerMob) btnEmployerMob.className = activeClass;
                if (btnSeekerMob) btnSeekerMob.className = inactiveClass;
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

            const svgRings = document.getElementById('hero-svg-rings');
            if (svgRings) {
                const currentRot = parseInt(svgRings.dataset.rot || 0);
                const newRot = currentRot + 180;
                svgRings.style.transform = `rotate(${newRot}deg)`;
                svgRings.dataset.rot = newRot;
            }

            setTimeout(() => {
                document.getElementById('hero-title').innerHTML = data.title;
                document.getElementById('hero-subtitle').innerHTML = data.subtitle;
                document.getElementById('cta-text').innerText = data.ctaText;
                document.getElementById('hero-cta-btn').href = data.ctaLink;
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
            }, 300);
        }
    </script>
    <script>

        // Mobile Menu Logic
        document.addEventListener('DOMContentLoaded', () => {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const closeMobileMenuBtn = document.getElementById('closeMobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');

            if (mobileMenuBtn && closeMobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', () => {
                    mobileMenu.classList.remove('translate-x-full');
                });

                closeMobileMenuBtn.addEventListener('click', () => {
                    mobileMenu.classList.add('translate-x-full');
                });

                window.closeMobileMenu = function () {
                    mobileMenu.classList.add('translate-x-full');
                };
            }
        });

        // Theme and Font Switcher Logic
        document.addEventListener('DOMContentLoaded', () => {
            const themeBtn = document.getElementById('themeSwitcherBtn');
            const themeDropdown = document.getElementById('themeDropdown');
            const htmlEl = document.documentElement;

            if (themeBtn && themeDropdown) {
                themeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (themeDropdown.classList.contains('hidden')) {
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
            }

            const themeButtons = document.querySelectorAll('[data-set-theme]');
            const applyTheme = (theme) => {
                htmlEl.setAttribute('data-theme', theme);
                localStorage.setItem('vedanta-theme', theme);
                themeButtons.forEach(btn => {
                    if (btn.dataset.setTheme === theme) {
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
                    if (btn.dataset.setFont === font) {
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

    @include('partials.auth-choice-modal')
    @include('partials.job-share-modal')

    @stack('scripts')
</body>

</html>