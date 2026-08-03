<div class="bg-card-bg/80 backdrop-blur-md border-b border-card-border sticky top-[60px] z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="employerNavContainer" class="flex overflow-x-auto py-0 gap-1 text-sm font-medium employer-nav-scroll items-center scroll-smooth">
            <a href="{{ route('employer.dashboard') }}"
               class="relative px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 {{ request()->routeIs('employer.dashboard') ? 'nav-active-link text-accent-yellow after:absolute after:bottom-0 after:left-2 after:right-2 after:h-[2px] after:bg-accent-yellow after:rounded-full font-bold' : 'text-text-dark/50 hover:text-text-main' }}">
                <i class="fas fa-th-large text-xs"></i> Dashboard
            </a>
            <a href="{{ route('employer.jobs.create') }}" class="relative px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 {{ request()->routeIs('employer.jobs.create') ? 'nav-active-link text-accent-yellow after:absolute after:bottom-0 after:left-2 after:right-2 after:h-[2px] after:bg-accent-yellow after:rounded-full font-bold' : 'text-text-dark/50 hover:text-text-main' }}">
                <i class="fas fa-plus-circle text-xs"></i> Post Job
            </a>
            <a href="{{ route('employer.jobs.index') }}" class="relative px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 {{ request()->routeIs('employer.jobs.index') || request()->routeIs('employer.jobs.edit') || request()->routeIs('employer.jobs.show') ? 'nav-active-link text-accent-yellow after:absolute after:bottom-0 after:left-2 after:right-2 after:h-[2px] after:bg-accent-yellow after:rounded-full font-bold' : 'text-text-dark/50 hover:text-text-main' }}">
                <i class="fas fa-briefcase text-xs"></i> My Jobs
            </a>
            <a href="{{ route('employer.applicants.index') }}" class="relative px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 {{ request()->routeIs('employer.applicants.index') ? 'nav-active-link text-accent-yellow after:absolute after:bottom-0 after:left-2 after:right-2 after:h-[2px] after:bg-accent-yellow after:rounded-full font-bold' : 'text-text-dark/50 hover:text-text-main' }}">
                <i class="fas fa-users text-xs"></i> Candidates
            </a>
            <a href="{{ route('employer.profile.edit') }}" class="relative px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 {{ request()->routeIs('employer.profile.edit') ? 'nav-active-link text-accent-yellow after:absolute after:bottom-0 after:left-2 after:right-2 after:h-[2px] after:bg-accent-yellow after:rounded-full font-bold' : 'text-text-dark/50 hover:text-text-main' }}">
                <i class="fas fa-cog text-xs"></i> Settings
            </a>
            <form action="{{ route('logout') }}" method="POST" class="ml-auto">
                @csrf
                <button type="submit" class="px-4 py-3.5 text-red-400/70 hover:text-red-400 whitespace-nowrap transition-colors flex items-center gap-1.5 text-sm">
                    <i class="fas fa-sign-out-alt text-xs"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Dedicated Mobile Scroll Indicator Sub-Bar (Below Navbar Tabs) -->
    <div id="employerNavScrollHint" class="sm:hidden flex items-center justify-between px-4 py-1.5 bg-secondary-bg/80 border-t border-card-border/40 text-xs transition-opacity duration-300">
        <span class="flex items-center gap-1.5 text-accent-yellow font-semibold text-[11px]">
            <i class="fas fa-hand-pointer text-xs animate-bounce"></i> Swipe left or right for more tabs
        </span>
        <span class="inline-flex items-center gap-1 bg-amber-500/15 text-accent-yellow font-bold px-2 py-0.5 rounded-full border border-amber-400/30 text-[10px]">
            <span>Scroll</span>
            <i class="fas fa-chevron-right text-[8px] animate-pulse"></i>
        </span>
    </div>
</div>

<style>
    @media (max-width: 640px) {
        .employer-nav-scroll::-webkit-scrollbar {
            height: 4px;
        }
        .employer-nav-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
        }
        .employer-nav-scroll::-webkit-scrollbar-thumb {
            background: rgba(234, 179, 8, 0.6);
            border-radius: 4px;
        }
    }
    @media (min-width: 641px) {
        .employer-nav-scroll::-webkit-scrollbar {
            display: none;
        }
        .employer-nav-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navContainer = document.getElementById('employerNavContainer');
    const scrollHint = document.getElementById('employerNavScrollHint');
    const activeLink = navContainer ? navContainer.querySelector('.nav-active-link') : null;

    if (navContainer) {
        if (activeLink) {
            setTimeout(() => {
                activeLink.scrollIntoView({ inline: 'center', block: 'nearest', behavior: 'smooth' });
            }, 100);
        }

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
