{{-- Candidate Dashboard Navigation --}}
<div class="bg-card-bg/80 backdrop-blur-md border-b border-card-border sticky top-[60px] z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="candidateNavContainer" class="flex overflow-x-auto py-0 gap-1 text-sm font-medium candidate-nav-scroll items-center scroll-smooth">
            @php
                $appCount = auth()->user()->applications()->count();
                $navItems = [
                    ['route' => 'candidate.dashboard', 'routeIs' => 'candidate.dashboard', 'icon' => 'fa-th-large', 'label' => 'Dashboard'],
                    ['route' => 'candidate.profile.edit', 'routeIs' => 'candidate.profile.*', 'icon' => 'fa-user-circle', 'label' => 'My Profile'],
                    ['route' => 'candidate.applications.index', 'routeIs' => 'candidate.applications.*', 'icon' => 'fa-paper-plane', 'label' => "Applications ($appCount)"],
                    ['route' => 'candidate.payment.show', 'routeIs' => 'candidate.payment.*', 'icon' => 'fa-credit-card', 'label' => 'Payment & Plan'],
                    ['route' => 'candidate.agreement.show', 'routeIs' => 'candidate.agreement.*', 'icon' => 'fa-file-contract', 'label' => 'My Agreement'],
                    ['route' => 'candidate.registration.show', 'routeIs' => 'candidate.registration.*', 'icon' => 'fa-clipboard-list', 'label' => 'Registration'],
                    ['route' => 'candidate.serviceCharge.show', 'routeIs' => 'candidate.servicecharge.*', 'icon' => 'fa-file-invoice-dollar', 'label' => 'Service Charge'],
                    ['route' => 'candidate.aditionalFeature.show', 'routeIs' => 'candidate.aditional.*', 'icon' => 'fa-puzzle-piece', 'label' => 'Aditional Feature'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $isActive = request()->routeIs($item['routeIs']); @endphp
                <a href="{{ route($item['route']) }}" 
                   class="relative px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 {{ $isActive ? 'nav-active-link text-accent-blue after:absolute after:bottom-0 after:left-2 after:right-2 after:h-[2px] after:bg-accent-blue after:rounded-full font-bold' : 'text-text-dark/50 hover:text-text-main' }}">

                    @if($item['route'] === 'candidate.profile.edit' && auth()->user()->profile?->profile_photo_path)
                        <img src="{{ asset('storage/' . auth()->user()->profile->profile_photo_path) }}" alt="Profile"
                            class="w-5 h-5 rounded-full object-cover border border-accent-blue/30">
                    @else
                        <i class="fas {{ $item['icon'] }} text-xs"></i>
                    @endif

                    {{ $item['label'] }}
                </a>
            @endforeach

            <form action="{{ route('logout') }}" method="POST" class="ml-auto">
                @csrf
                <button type="submit"
                    class="px-4 py-3.5 text-red-400/70 hover:text-red-400 whitespace-nowrap transition-colors flex items-center gap-1.5 text-sm">
                    <i class="fas fa-sign-out-alt text-xs"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Dedicated Mobile Scroll Indicator Sub-Bar (Below Navbar Tabs) -->
    <div id="candidateNavScrollHint" class="sm:hidden flex items-center justify-between px-4 py-1.5 bg-secondary-bg/80 border-t border-card-border/40 text-xs transition-opacity duration-300">
        <span class="flex items-center gap-1.5 text-accent-blue font-semibold text-[11px]">
            <i class="fas fa-hand-pointer text-xs animate-bounce"></i> Swipe left or right for more tabs
        </span>
        <span class="inline-flex items-center gap-1 bg-accent-blue/15 text-accent-blue font-bold px-2 py-0.5 rounded-full border border-accent-blue/30 text-[10px]">
            <span>Scroll</span>
            <i class="fas fa-chevron-right text-[8px] animate-pulse"></i>
        </span>
    </div>
</div>

<style>
    @media (max-width: 640px) {
        .candidate-nav-scroll::-webkit-scrollbar {
            height: 4px;
        }
        .candidate-nav-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
        }
        .candidate-nav-scroll::-webkit-scrollbar-thumb {
            background: rgba(18, 154, 239, 0.6);
            border-radius: 4px;
        }
    }
    @media (min-width: 641px) {
        .candidate-nav-scroll::-webkit-scrollbar {
            display: none;
        }
        .candidate-nav-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navContainer = document.getElementById('candidateNavContainer');
    const scrollHint = document.getElementById('candidateNavScrollHint');
    const activeLink = navContainer ? navContainer.querySelector('.nav-active-link') : null;

    if (navContainer) {
        // Auto scroll active tab into view
        if (activeLink) {
            setTimeout(() => {
                activeLink.scrollIntoView({ inline: 'center', block: 'nearest', behavior: 'smooth' });
            }, 100);
        }

        // Toggle right scroll hint on mobile
        function updateScrollHint() {
            if (!scrollHint) return;
            const maxScroll = navContainer.scrollWidth - navContainer.clientWidth;
            if (navContainer.scrollLeft >= maxScroll - 15) {
                scrollHint.style.opacity = '0';
            } else {
                scrollHint.style.opacity = '1';
            }
        }

        navContainer.addEventListener('scroll', updateScrollHint);
        updateScrollHint();
    }
});
</script>