{{-- Candidate Sidebar Component --}}
@php
    $user = auth()->user();
    $profile = $user ? ($user->profile ?: null) : null;
    $appCount = $user ? $user->applications()->count() : 0;
    $savedCount = $user ? \App\Models\SavedJob::where('user_id', $user->id)->count() : 0;
    $isAgreementSigned = $profile && $profile->is_agreement_signed;
    $isVerified = $profile && $profile->is_verified;
    $isDrawer = $isMobileDrawer ?? false;

    $navSections = [
        [
            'title' => 'Main Navigation',
            'items' => [
                [
                    'route' => 'candidate.dashboard',
                    'routeIs' => 'candidate.dashboard',
                    'icon' => 'fa-th-large',
                    'label' => 'Dashboard',
                ],
                [
                    'route' => 'candidate.profile.edit',
                    'routeIs' => 'candidate.profile.*',
                    'icon' => 'fa-user-circle',
                    'label' => 'My Profile',
                ],
                [
                    'route' => 'candidate.applications.index',
                    'routeIs' => 'candidate.applications.index',
                    'icon' => 'fa-paper-plane',
                    'label' => 'My Applications',
                    'badge' => $appCount,
                    'badgeClass' => 'bg-accent-blue/25 text-accent-blue border border-accent-blue/30',
                ],
                [
                    'route' => 'candidate.savedJobs.index',
                    'routeIs' => 'candidate.savedJobs.*',
                    'icon' => 'fa-bookmark',
                    'label' => 'Saved Jobs',
                    'badge' => $savedCount > 0 ? $savedCount : null,
                    'badgeClass' => 'bg-amber-500/20 text-amber-300 border border-amber-500/30',
                ],
                [
                    'route' => 'candidate.applications.available',
                    'routeIs' => 'candidate.applications.available',
                    'icon' => 'fa-briefcase',
                    'label' => 'Explore Jobs',
                    'badge' => 'New',
                    'badgeClass' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30',
                ],
            ]
        ],
        [
            'title' => 'Billing & Legal',
            'items' => [
                [
                    'route' => 'candidate.payment.show',
                    'routeIs' => 'candidate.payment.*',
                    'icon' => 'fa-credit-card',
                    'label' => 'Payment & Plan',
                ],
                [
                    'route' => 'candidate.agreement.show',
                    'routeIs' => 'candidate.agreement.*',
                    'icon' => 'fa-file-contract',
                    'label' => 'My Agreement',
                    'badge' => $isAgreementSigned ? 'Signed' : 'Action',
                    'badgeClass' => $isAgreementSigned ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30 animate-pulse',
                ],
                [
                    'route' => 'candidate.registration.show',
                    'routeIs' => 'candidate.registration.*',
                    'icon' => 'fa-clipboard-list',
                    'label' => 'Registration Details',
                ],
                [
                    'route' => 'candidate.serviceCharge.show',
                    'routeIs' => 'candidate.servicecharge.*',
                    'icon' => 'fa-file-invoice-dollar',
                    'label' => 'Service Charge',
                ],
            ]
        ],
        [
            'title' => 'Rewards & Extras',
            'items' => [
                [
                    'route' => 'candidate.referral.index',
                    'routeIs' => 'candidate.referral.*',
                    'icon' => 'fa-gift',
                    'label' => 'Refer & Earn',
                    'badge' => 'Hot',
                    'badgeClass' => 'bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-black shadow-sm',
                    'special' => true,
                ],
                [
                    'route' => 'candidate.aditionalFeature.show',
                    'routeIs' => 'candidate.aditional.*',
                    'icon' => 'fa-puzzle-piece',
                    'label' => 'Additional Tools',
                ],
            ]
        ],
    ];
@endphp

<div class="space-y-3">
    {{-- Minimize / Expand Toggle Button on Desktop --}}
    @if(!$isDrawer)
        <div class="flex items-center pb-2 border-b border-white/10"
             :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">
            <span x-show="!sidebarCollapsed" class="text-[10px] font-bold text-accent-blue tracking-wider uppercase flex items-center gap-1.5">
                <i class="fas fa-bars-staggered text-xs"></i>
                <span>Navigation</span>
            </span>
            <button type="button" @click="toggleCollapse()"
                class="w-7 h-7 rounded-lg bg-white/5 hover:bg-accent-blue hover:text-white text-slate-400 flex items-center justify-center text-xs transition-all shadow-sm"
                :title="sidebarCollapsed ? 'Expand Sidebar' : 'Minimize Sidebar'">
                <i class="fas" :class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
            </button>
        </div>
    @endif

    {{-- Candidate Mini Profile Header --}}
    <div class="p-2.5 sm:p-3 rounded-2xl bg-gradient-to-b from-white/[0.07] to-white/[0.02] border border-white/10 shadow-inner transition-all">
        <div class="flex items-center gap-3" :class="(!{{ $isDrawer ? 'true' : '!sidebarCollapsed' }}) ? 'justify-center' : ''">
            <div class="relative shrink-0">
                @if($profile && $profile->profile_photo_path)
                    <img src="{{ asset('storage/' . $profile->profile_photo_path) }}" alt="{{ $user->name ?? 'Candidate' }}"
                        class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl object-cover border-2 border-accent-blue shadow-glow-blue">
                @else
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-gradient-to-br from-accent-blue to-blue-700 text-white font-extrabold text-base flex items-center justify-center shadow-glow-blue border border-white/20">
                        {{ strtoupper(substr($user->name ?? 'C', 0, 1)) }}
                    </div>
                @endif
                @if($isVerified)
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-[#040e2d] flex items-center justify-center text-[8px] text-white" title="Verified Candidate">
                        <i class="fas fa-check"></i>
                    </span>
                @endif
            </div>

            <div class="min-w-0 flex-1" 
                 @if(!$isDrawer) x-show="!sidebarCollapsed" x-transition @endif>
                <h3 class="text-xs sm:text-sm font-bold text-text-main truncate leading-tight" title="{{ $user->name ?? 'Candidate' }}">
                    {{ $user->name ?? 'Candidate' }}
                </h3>
                <p class="text-[10px] text-text-dark/50 truncate mt-0.5" title="{{ $user->email ?? '' }}">
                    {{ $user->email ?? '' }}
                </p>
                <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                    <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9px] font-bold bg-accent-blue/20 text-accent-blue border border-accent-blue/30 uppercase tracking-wider">
                        Candidate
                    </span>
                    @if($isVerified)
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <i class="fas fa-check-circle text-[8px]"></i> Verified
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Sections --}}
    <nav class="space-y-3">
        @foreach($navSections as $section)
            <div>
                <div class="text-[9px] uppercase font-bold tracking-wider text-text-dark/40 px-2 mb-1"
                     @if(!$isDrawer) x-show="!sidebarCollapsed" x-transition @endif>
                    {{ $section['title'] }}
                </div>
                @if(!$isDrawer)
                    <div class="h-px bg-white/5 my-2" x-show="sidebarCollapsed"></div>
                @endif

                <div class="space-y-1">
                    @foreach($section['items'] as $item)
                        @php 
                            $isActive = request()->routeIs($item['routeIs']); 
                            $isSpecial = $item['special'] ?? false;
                        @endphp
                        <a href="{{ route($item['route']) }}"
                           title="{{ $item['label'] }}"
                           class="flex items-center rounded-xl text-xs font-semibold transition-all group relative {{ $isActive ? 'bg-gradient-to-r from-accent-blue to-accent-blue-hover text-white shadow-glow-blue font-bold' : ($isSpecial ? 'text-amber-300/90 hover:text-amber-200 hover:bg-amber-400/10' : 'text-text-dark/70 hover:text-text-main hover:bg-white/5') }}"
                           :class="(!{{ $isDrawer ? 'true' : '!sidebarCollapsed' }}) ? 'justify-center p-2.5' : 'justify-between px-3 py-2.5'">
                            
                            <div class="flex items-center gap-2.5 min-w-0" :class="(!{{ $isDrawer ? 'true' : '!sidebarCollapsed' }}) ? 'justify-center' : ''">
                                <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0 {{ $isActive ? 'text-white' : ($isSpecial ? 'text-amber-400 group-hover:scale-110 transition-transform' : 'text-accent-blue/80 group-hover:text-accent-blue group-hover:scale-110 transition-transform') }}">
                                    <i class="fas {{ $item['icon'] }} text-xs"></i>
                                </div>
                                <span class="truncate" @if(!$isDrawer) x-show="!sidebarCollapsed" @endif>{{ $item['label'] }}</span>
                            </div>

                            @if(isset($item['badge']))
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold shrink-0 {{ $item['badgeClass'] ?? 'bg-white/10 text-white' }}"
                                      @if(!$isDrawer) x-show="!sidebarCollapsed" @endif>
                                    {{ $item['badge'] }}
                                </span>
                                {{-- Dot indicator for collapsed view if badge exists --}}
                                @if(!$isDrawer)
                                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full {{ $item['badgeClass'] ?? 'bg-accent-blue' }}"
                                          x-show="sidebarCollapsed"></span>
                                @endif
                            @elseif($isActive)
                                <i class="fas fa-chevron-right text-[9px] text-white/70"
                                   @if(!$isDrawer) x-show="!sidebarCollapsed" @endif></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    {{-- Refer & Earn Promo Box --}}
    <div>
        {{-- Expanded View --}}
        <div class="p-3 rounded-2xl bg-gradient-to-br from-amber-500/15 via-accent-blue/10 to-transparent border border-amber-500/30 text-center relative overflow-hidden group"
             @if(!$isDrawer) x-show="!sidebarCollapsed" x-transition @endif>
            <div class="flex items-center justify-center gap-1.5 text-amber-400 text-xs font-bold mb-1">
                <i class="fas fa-gift text-sm animate-bounce"></i>
                <span>Refer & Earn Points</span>
            </div>
            <p class="text-[10px] text-text-dark/70 mb-2 leading-snug">
                Invite fellow educators & earn discount points!
            </p>
            <a href="{{ route('candidate.referral.index') }}" 
               class="inline-flex items-center justify-center gap-1.5 w-full py-1.5 px-3 rounded-xl bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 font-extrabold text-[10px] shadow-sm transition-all">
                <i class="fas fa-share-alt text-[9px]"></i>
                <span>Refer Friends</span>
            </a>
        </div>

        {{-- Minimized View Icon --}}
        @if(!$isDrawer)
            <div x-show="sidebarCollapsed" class="flex justify-center">
                <a href="{{ route('candidate.referral.index') }}" 
                   title="Refer & Earn Rewards"
                   class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 text-slate-950 flex items-center justify-center text-sm shadow-md hover:scale-110 transition-transform">
                    <i class="fas fa-gift"></i>
                </a>
            </div>
        @endif
    </div>

    {{-- Logout Action --}}
    <div class="pt-2 border-t border-white/10">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                title="Logout"
                class="w-full flex items-center rounded-xl text-xs font-bold text-red-400 hover:text-red-300 hover:bg-red-500/10 border border-red-500/20 transition-all group"
                :class="(!{{ $isDrawer ? 'true' : '!sidebarCollapsed' }}) ? 'justify-center p-2.5' : 'justify-center gap-2 px-3 py-2'">
                <i class="fas fa-sign-out-alt text-xs group-hover:-translate-x-0.5 transition-transform"></i>
                <span @if(!$isDrawer) x-show="!sidebarCollapsed" @endif>Logout</span>
            </button>
        </form>
    </div>
</div>
