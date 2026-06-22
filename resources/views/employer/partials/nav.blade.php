<div class="bg-card-bg/80 backdrop-blur-md border-b border-card-border sticky top-[60px] z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex overflow-x-auto py-0 gap-1 text-sm font-medium hide-scrollbar items-center">
            <a href="{{ route('employer.dashboard') }}"
               class="relative px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 {{ request()->routeIs('employer.dashboard') ? 'text-accent-yellow after:absolute after:bottom-0 after:left-2 after:right-2 after:h-[2px] after:bg-accent-yellow after:rounded-full' : 'text-text-dark/50 hover:text-text-main' }}">
                <i class="fas fa-th-large text-xs"></i> Dashboard
            </a>
            <a href="{{ route('employer.jobs.create') }}" class="relative px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 {{ request()->routeIs('employer.jobs.create') ? 'text-accent-yellow after:absolute after:bottom-0 after:left-2 after:right-2 after:h-[2px] after:bg-accent-yellow after:rounded-full' : 'text-text-dark/50 hover:text-text-main' }}">
                <i class="fas fa-plus-circle text-xs"></i> Post Job
            </a>
            <a href="{{ route('employer.jobs.index') }}" class="relative px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 {{ request()->routeIs('employer.jobs.index') || request()->routeIs('employer.jobs.edit') || request()->routeIs('employer.jobs.show') ? 'text-accent-yellow after:absolute after:bottom-0 after:left-2 after:right-2 after:h-[2px] after:bg-accent-yellow after:rounded-full' : 'text-text-dark/50 hover:text-text-main' }}">
                <i class="fas fa-briefcase text-xs"></i> My Jobs
            </a>
            <a href="{{ route('employer.applicants.index') }}" class="relative px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 {{ request()->routeIs('employer.applicants.index') ? 'text-accent-yellow after:absolute after:bottom-0 after:left-2 after:right-2 after:h-[2px] after:bg-accent-yellow after:rounded-full' : 'text-text-dark/50 hover:text-text-main' }}">
                <i class="fas fa-users text-xs"></i> Candidates
            </a>
            <a href="{{ route('employer.profile.edit') }}" class="relative px-4 py-3.5 whitespace-nowrap transition-all flex items-center gap-2 {{ request()->routeIs('employer.profile.edit') ? 'text-accent-yellow after:absolute after:bottom-0 after:left-2 after:right-2 after:h-[2px] after:bg-accent-yellow after:rounded-full' : 'text-text-dark/50 hover:text-text-main' }}">
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
</div>
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
