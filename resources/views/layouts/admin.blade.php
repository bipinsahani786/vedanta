<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Vedanta Placement Agency</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            /* Override Tailwind Theme Variables for Admin Light Mode */
            --theme-primary-bg: #f8fafc;
            --theme-secondary-bg: #f1f5f9;
            --theme-accent-blue: #3b82f6;
            --theme-accent-blue-hover: #3f76edff;
            --theme-accent-yellow: #f59e0b;
            --theme-text-dark: #64748b; /* Slate 500 */
            --theme-text-main: #0f172a; /* Slate 900 */
            --theme-card-bg: #ffffff;
            --theme-card-border: #e2e8f0;
        }
        
        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: var(--theme-primary-bg); 
            color: var(--theme-text-main);
        }
        
        .admin-sidebar { 
            background: linear-gradient(180deg, #041346ff 0%, #2a62bbff 100%);
            border-right: 1px solid rgba(255,255,255,0.05);
        }
        .sidebar-link { 
            color: rgba(255,255,255,0.6); 
            transition: all 0.3s ease; 
        }
        .sidebar-link:hover { 
            color: #ffffff; 
            background: rgba(255,255,255,0.08); 
        }
        .sidebar-link.active { 
            color: #ffffff; 
            background: rgba(255,255,255,0.1); 
            border-left: 3px solid #3b82f6;
            font-weight: 600;
        }
        
        .admin-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--theme-card-border);
        }

        /* Smooth scrolling and transitions */
        html { scroll-behavior: smooth; }
        .transition-all { transition-property: all; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 300ms; }
        
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        
        /* Table Styles */
        .admin-table th {
            background-color: #f8fafc;
            color: var(--theme-text-dark);
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--theme-card-border);
        }
        .admin-table td {
            padding: 1rem 1.5rem;
            color: var(--theme-text-main);
            border-bottom: 1px solid var(--theme-card-border);
            font-size: 0.875rem;
        }
        .admin-table tr:hover td {
            background-color: #f1f5f9;
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="admin-sidebar w-64 flex-shrink-0 flex flex-col transition-transform duration-300 z-30 shadow-2xl hidden md:flex">
            <!-- Brand -->
            <div class="h-[70px] flex items-center justify-center border-b border-white/5 px-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full bg-accent-blue/10 blur-xl"></div>
                <img src="/images/logo.png" alt="Vedanta" class="h-10 w-auto bg-white/90 backdrop-blur-sm rounded-lg p-1.5 shadow-lg relative z-10">
            </div>
            
            <!-- Navigation -->
            <div class="flex-1 overflow-y-auto py-5 px-3 flex flex-col gap-1 no-scrollbar">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} px-4 py-3 rounded-lg flex items-center gap-3 text-sm">
                    <i class="fas fa-th-large w-5 text-center text-lg"></i> Dashboard
                </a>
                
                <div class="text-[10px] uppercase font-bold tracking-widest text-white/30 mt-6 mb-2 px-4">Master Data</div>
                
                <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }} px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm">
                    <i class="fas fa-layer-group w-5 text-center"></i> Categories
                </a>
                <a href="{{ route('admin.subjects.index') }}" class="sidebar-link {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }} px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm">
                    <i class="fas fa-book-open w-5 text-center"></i> Subjects
                </a>
                <a href="{{ route('admin.qualifications.index') }}" class="sidebar-link {{ request()->routeIs('admin.qualifications.*') ? 'active' : '' }} px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm">
                    <i class="fas fa-graduation-cap w-5 text-center"></i> Qualifications
                </a>
                <a href="{{ route('admin.locations.index') }}" class="sidebar-link {{ request()->routeIs('admin.locations.*') ? 'active' : '' }} px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm">
                    <i class="fas fa-map-marker-alt w-5 text-center"></i> Locations
                </a>

                <div class="text-[10px] uppercase font-bold tracking-widest text-white/30 mt-6 mb-2 px-4">Recruitment</div>
                
                <a href="{{ route('admin.jobs.index', ['status' => 'pending']) }}" class="sidebar-link {{ request('status') === 'pending' ? 'active' : '' }} px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm">
                    <i class="fas fa-clipboard-check w-5 text-center"></i> Job Approvals
                    @php $pendingCount = \App\Models\JobPost::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-accent-yellow text-[#031b4e] text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.jobs.index') }}" class="sidebar-link {{ request()->routeIs('admin.jobs.*') && request('status') !== 'pending' ? 'active' : '' }} px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm">
                    <i class="fas fa-briefcase w-5 text-center"></i> Live Jobs
                </a>
                <a href="{{ route('admin.crm.index') }}" class="sidebar-link {{ request()->routeIs('admin.crm.*') ? 'active' : '' }} px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm">
                    <i class="fas fa-users-cog w-5 text-center"></i> Candidates CRM
                </a>
                <a href="{{ route('admin.applications.index') }}" class="sidebar-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }} px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm">
                    <i class="fas fa-file-signature w-5 text-center"></i> Job Applications
                </a>
                <a href="{{ route('admin.transactions.index') }}" class="sidebar-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }} px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm">
                    <i class="fas fa-receipt w-5 text-center"></i> Transactions
                </a>
                <a href="{{ route('admin.leads.index') }}" class="sidebar-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }} px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm">
                    <i class="fas fa-headset w-5 text-center"></i> Support Leads
                </a>

                <div class="text-[10px] uppercase font-bold tracking-widest text-white/30 mt-6 mb-2 px-4">CMS</div>
                
                <a href="{{ route('admin.services.index') }}" class="sidebar-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }} px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm">
                    <i class="fas fa-concierge-bell w-5 text-center"></i> Services
                </a>
                <a href="{{ route('admin.testimonials.index') }}" class="sidebar-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }} px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm">
                    <i class="fas fa-star w-5 text-center"></i> Testimonials
                </a>
                <a href="{{ route('admin.clients.index') }}" class="sidebar-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }} px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm">
                    <i class="fas fa-building w-5 text-center"></i> Client Logos
                </a>
            </div>
            
            <!-- User Section -->
            <div class="p-4 border-t border-white/5 bg-black/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 rounded-xl flex items-center gap-3 font-semibold text-sm text-red-400 bg-red-500/10 hover:bg-red-500/20 transition-all border border-red-500/20 shadow-lg">
                        <i class="fas fa-power-off w-5 text-center"></i> Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Sidebar Overlay (Placeholder for future JS) -->
        <div id="mobile-overlay" class="fixed inset-0 bg-black/50 z-20 hidden md:hidden backdrop-blur-sm"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden relative">
            
            <!-- Header -->
            <header class="admin-header h-[70px] flex items-center justify-between px-4 sm:px-8 z-20 shadow-sm sticky top-0">
                <div class="flex items-center gap-4">
                    <button id="mobile-menu-btn" class="text-text-dark hover:text-accent-blue md:hidden focus:outline-none transition-colors w-10 h-10 rounded-lg hover:bg-accent-blue/10 flex items-center justify-center">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    {{-- Global Search (Decorative for now, can be wired up later) --}}
                    <div class="hidden sm:flex items-center bg-secondary-bg border border-card-border rounded-full px-4 py-2 w-64 focus-within:ring-2 focus-within:ring-accent-blue/50 focus-within:border-accent-blue transition-all">
                        <i class="fas fa-search text-text-dark/40 text-sm"></i>
                        <input type="text" placeholder="Quick search..." class="bg-transparent border-none focus:ring-0 text-sm w-full ml-2 text-text-main placeholder-text-dark/40 py-0">
                    </div>
                </div>
                
                <div class="flex items-center gap-3 sm:gap-5">
                    {{-- Notifications --}}
                    <button class="relative w-10 h-10 rounded-full hover:bg-secondary-bg border border-transparent hover:border-card-border text-text-dark hover:text-accent-blue transition-all flex items-center justify-center focus:outline-none group">
                        <i class="fas fa-bell"></i>
                        <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full group-hover:animate-ping"></span>
                        <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    
                    {{-- Profile Dropdown --}}
                    <div class="flex items-center gap-3 pl-3 sm:pl-5 border-l border-card-border cursor-pointer hover:opacity-80 transition-opacity">
                        <div class="hidden sm:block text-right">
                            <p class="text-sm font-bold text-text-main leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-text-dark/50 mt-1 uppercase tracking-wider font-semibold">Super Admin</p>
                        </div>
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-accent-blue to-accent-blue/70 text-white flex items-center justify-center font-bold shadow-lg shadow-accent-blue/20">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Scrollable Area -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-8">
                
                {{-- Breadcrumb/Title Area --}}
                <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-text-main tracking-tight">@yield('title', 'Dashboard')</h1>
                        @hasSection('subtitle')
                            <p class="text-sm text-text-dark/50 mt-1">@yield('subtitle')</p>
                        @endif
                    </div>
                    @hasSection('actions')
                        <div class="flex items-center gap-3">
                            @yield('actions')
                        </div>
                    @endif
                </div>

                {{-- Alerts --}}
                @if(session('success'))
                    <div class="bg-green-500/10 border border-green-500/20 text-green-500 p-4 mb-8 rounded-xl shadow-sm flex items-start gap-3 animate-[fadeIn_0.3s_ease-out]">
                        <i class="fas fa-check-circle mt-0.5 text-lg"></i>
                        <p class="font-medium text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 mb-8 rounded-xl shadow-sm flex items-start gap-3 animate-[fadeIn_0.3s_ease-out]">
                        <i class="fas fa-exclamation-triangle mt-0.5 text-lg"></i>
                        <p class="font-medium text-sm">{{ session('error') }}</p>
                    </div>
                @endif

                {{-- Content Injection --}}
                @yield('content')
                
                {{-- Footer --}}
                <footer class="mt-12 pt-6 border-t border-card-border flex items-center justify-between text-xs text-text-dark/40 font-medium">
                    <p>&copy; {{ date('Y') }} Vedanta Placement Agency.</p>
                    <p>Admin Portal v2.0</p>
                </footer>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
