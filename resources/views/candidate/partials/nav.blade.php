{{-- Candidate Dashboard Navigation --}}
<div class="bg-card-bg/80 backdrop-blur-md border-b border-card-border sticky top-[60px] z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex overflow-x-auto py-0 gap-1 text-sm font-medium hide-scrollbar items-center">
            @php
                $appCount = auth()->user()->applications()->count();
                $navItems = [
                    ['route' => 'candidate.dashboard', 'routeIs' => 'candidate.dashboard', 'icon' => 'fa-th-large', 'label' => 'Dashboard'],
                    ['route' => 'candidate.profile.edit', 'routeIs' => 'candidate.profile.*', 'icon' => 'fa-user-circle', 'label' => 'My Profile'],
                    ['route' => 'candidate.applications.index', 'routeIs' => 'candidate.applications.*', 'icon' => 'fa-paper-plane', 'label' => "Applications ($appCount)"],
                    ['route' => 'candidate.payment.show', 'routeIs' => 'candidate.payment.*', 'icon' => 'fa-credit-card', 'label' => 'Payment & Plan'],
                    ['route' => 'candidate.agreement.show', 'routeIs' => 'candidate.agreement.*', 'icon' => 'fa-file-contract', 'label' => 'My Agreement'],
                ];
            @endphp

            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="relative px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2
                   {{ request()->routeIs($item['routeIs'])
                       ? 'text-accent-blue after:absolute after:bottom-0 after:left-2 after:right-2 after:h-[2px] after:bg-accent-blue after:rounded-full'
                       : 'text-text-dark/50 hover:text-text-main' }}">
                    <i class="fas {{ $item['icon'] }} text-xs"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach

            <form action="{{ route('logout') }}" method="POST" class="ml-auto">
                @csrf
                <button type="submit" class="px-4 py-3.5 text-red-400/70 hover:text-red-400 whitespace-nowrap transition-colors flex items-center gap-1.5 text-sm">
                    <i class="fas fa-sign-out-alt text-xs"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
