@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    /* Completely hide scrollbars while preserving scroll functionality */
    .no-scrollbar::-webkit-scrollbar {
        display: none !important;
        width: 0px !important;
        height: 0px !important;
        background: transparent !important;
    }
    .no-scrollbar {
        -ms-overflow-style: none !important;  /* IE and Edge */
        scrollbar-width: none !important;     /* Firefox */
    }
</style>

<div class="bg-secondary-bg min-h-screen py-3 lg:py-5" 
     x-data="{ 
         mobileSidebarOpen: false,
         sidebarCollapsed: localStorage.getItem('candidate_sidebar_collapsed') === 'true',
         toggleCollapse() {
             this.sidebarCollapsed = !this.sidebarCollapsed;
             localStorage.setItem('candidate_sidebar_collapsed', this.sidebarCollapsed);
         }
     }">
    <div class="w-full px-2 sm:px-4 lg:px-6 xl:px-8">

        {{-- Mobile Top Bar / Sidebar Toggle (Visible only on mobile/tablet) --}}
        <div class="lg:hidden mb-4 bg-card-bg/95 backdrop-blur-md border border-card-border rounded-2xl p-3 flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-2.5 min-w-0">
                @if(auth()->user()->profile?->profile_photo_path)
                    <img src="{{ asset('storage/' . auth()->user()->profile->profile_photo_path) }}" alt="Profile"
                        class="w-9 h-9 rounded-xl object-cover border border-accent-blue/40 shadow-sm shrink-0">
                @else
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-accent-blue to-blue-700 text-white font-extrabold flex items-center justify-center text-xs shadow-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'C', 0, 1)) }}
                    </div>
                @endif
                <div class="min-w-0">
                    <h4 class="text-xs font-bold text-text-main truncate">{{ auth()->user()->name }}</h4>
                    <span class="text-[10px] text-accent-blue flex items-center gap-1 font-semibold">
                        <i class="fas fa-user-circle text-[9px]"></i> Candidate Portal
                    </span>
                </div>
            </div>

            <button type="button" @click="mobileSidebarOpen = true"
                class="px-3 py-1.5 rounded-xl bg-accent-blue/15 hover:bg-accent-blue text-accent-blue hover:text-white border border-accent-blue/30 font-bold text-xs flex items-center gap-1.5 transition-all shadow-sm">
                <i class="fas fa-bars text-xs"></i>
                <span>Menu</span>
            </button>
        </div>

        {{-- Mobile Drawer (Off-Canvas) --}}
        <div x-show="mobileSidebarOpen" 
             x-cloak
             class="fixed inset-0 z-[200] lg:hidden"
             role="dialog" aria-modal="true">
            {{-- Backdrop --}}
            <div x-show="mobileSidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 @click="mobileSidebarOpen = false"
                 class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"></div>

            {{-- Off-Canvas Panel --}}
            <div x-show="mobileSidebarOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full" 
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="-translate-x-full"
                 class="relative max-w-xs w-full h-full bg-[#040e2d] border-r border-white/10 shadow-2xl p-4 overflow-y-auto no-scrollbar flex flex-col justify-between">
                
                <div>
                    {{-- Drawer Header --}}
                    <div class="flex items-center justify-between pb-3.5 mb-3.5 border-b border-white/10">
                        <div class="flex items-center gap-2">
                            <img src="/images/logo.png" alt="Logo" class="h-8 w-auto object-contain">
                            <span class="text-xs font-bold text-white uppercase tracking-wider">Candidate</span>
                        </div>
                        <button type="button" @click="mobileSidebarOpen = false"
                            class="w-8 h-8 rounded-lg bg-white/10 text-white/70 hover:text-white hover:bg-white/20 flex items-center justify-center text-sm transition-all">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    @include('candidate.partials.sidebar', ['isMobileDrawer' => true])
                </div>
            </div>
        </div>

        {{-- Desktop Two-Column Layout (Sidebar on Left + Content on Right) --}}
        <div class="flex flex-col lg:flex-row gap-4 xl:gap-6 items-start">
            {{-- Desktop Sidebar with Minimize Support --}}
            <aside class="hidden lg:block shrink-0 sticky top-28 z-30 transition-all duration-300 ease-in-out"
                   :class="sidebarCollapsed ? 'w-20' : 'w-64 xl:w-72'">
                <div class="bg-card-bg/95 backdrop-blur-xl border border-card-border rounded-3xl p-3.5 shadow-xl max-h-[calc(100vh-8.5rem)] overflow-y-auto no-scrollbar transition-all duration-300">
                    @include('candidate.partials.sidebar', ['isMobileDrawer' => false])
                </div>
            </aside>

            {{-- Main Content Area --}}
            <main class="flex-1 min-w-0 w-full transition-all duration-300">
                @yield('candidate_content')
            </main>
        </div>

    </div>
</div>
@endsection
