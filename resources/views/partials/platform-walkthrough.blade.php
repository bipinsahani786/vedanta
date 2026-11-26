{{-- Interactive First-Login & On-Demand Platform Walkthrough --}}
@php
    $wtUser = auth()->user();
    $wtPortal = $portal ?? ($wtUser && $wtUser->role === 'employer' ? 'employer' : 'candidate');
    $wtUserId = (int) ($wtUser->id ?? 0);
    $wtForceStart = (bool) ($forceWalkthrough ?? request()->boolean('tour'));
    $wtCacheKey = $wtUserId > 0 ? "platform_walkthrough_done:{$wtPortal}:{$wtUserId}" : null;
    $wtAutoStart = isset($shouldStartWalkthrough)
        ? (bool) $shouldStartWalkthrough
        : ($wtForceStart || ($wtCacheKey ? ! \Illuminate\Support\Facades\Cache::get($wtCacheKey, false) : false));

    if ($wtPortal === 'employer') {
        $wtSteps = [
            [
                'targetId' => 'tour-emp-header',
                'badge' => 'Welcome',
                'icon' => 'fa-school',
                'title' => 'School & Employer Dashboard',
                'description' => 'Manage all your institution vacancies and hiring updates from one place. Use Post New Job anytime to submit a teaching requirement.',
                'optional' => false,
            ],
            [
                'targetId' => 'tour-emp-stats',
                'badge' => 'Metrics',
                'icon' => 'fa-chart-bar',
                'title' => 'Hiring Overview Cards',
                'description' => 'Monitor Total Jobs Posted, Active Approved Jobs, Pending Approvals, and Shortlisted or Hired Candidates in real time.',
                'optional' => false,
            ],
            [
                'targetId' => 'tour-emp-jobs',
                'badge' => 'Vacancies',
                'icon' => 'fa-briefcase',
                'title' => 'Recent Job Postings',
                'description' => 'Check approval status on your listings, preview live job pages, or edit pending posts before they go live.',
                'optional' => false,
            ],
            [
                'targetId' => 'tour-emp-nav',
                'badge' => 'Help & Replay',
                'icon' => 'fa-compass',
                'title' => 'Navigation & Restart Tour',
                'description' => 'Switch quickly between Post Job, My Jobs, Candidates, and Settings. You can replay this tour anytime from Help Tour in navigation or Settings / Profile.',
                'optional' => false,
            ],
        ];
    } else {
        $wtSteps = [
            [
                'targetId' => 'tour-cand-hero',
                'badge' => 'Welcome',
                'icon' => 'fa-id-card',
                'title' => 'Profile Strength & Photo',
                'description' => 'Check your greeting, VPA Candidate ID, and Profile Completion progress bar here. Click your photo anytime to preview it large or upload a new photo.',
                'optional' => false,
            ],
            [
                'targetId' => 'tour-cand-nextstep',
                'badge' => 'Next Step',
                'icon' => 'fa-tasks',
                'title' => 'Missing Fields & Readiness Checklist',
                'description' => 'See any missing profile fields and follow your next onboarding step (Agreement, Plan activation, or Complete Profile to Apply).',
                'optional' => true,
            ],
            [
                'targetId' => 'tour-cand-summary',
                'badge' => 'Tracking',
                'icon' => 'fa-clipboard-check',
                'title' => 'Application & Verification Status',
                'description' => 'Monitor your Applied, Shortlisted, Interview, and Placed counts along with application quota and verification badge status.',
                'optional' => false,
            ],
            [
                'targetId' => 'tour-cand-quick-actions',
                'badge' => 'Shortcuts',
                'icon' => 'fa-bolt',
                'title' => 'Quick Actions Hub',
                'description' => 'Access Complete/Update Profile, View/Update Resume, Browse Jobs, Applications, Agreement, Payment/Plan, and Request Verification in one click.',
                'optional' => false,
            ],
            [
                'targetId' => 'tour-cand-plan',
                'badge' => 'Membership',
                'icon' => 'fa-crown',
                'title' => 'Membership & Plan Status',
                'description' => 'View your active plan, start date, validity period, expiry date, and renew or upgrade your membership whenever needed.',
                'optional' => false,
            ],
            [
                'targetId' => 'tour-cand-recommended',
                'badge' => 'Opportunities',
                'icon' => 'fa-briefcase',
                'title' => 'Notifications & Recommended Jobs',
                'description' => 'Stay updated with alerts and explore verified school vacancies matched to your teaching subject and preferences.',
                'optional' => false,
            ],
            [
                'targetId' => 'tour-cand-help',
                'badge' => 'Help & Replay',
                'icon' => 'fa-compass',
                'title' => 'Sidebar Menu & Restart Tour',
                'description' => 'Use the navigation menu to visit My Profile, Applications, Agreement, Payments, and Tools. You can restart this walkthrough anytime from Help / Platform Tour or My Profile.',
                'optional' => false,
            ],
        ];
    }
@endphp

<div x-data="{
        isOpen: false,
        activeIndex: 0,
        allSteps: @js($wtSteps),
        steps: [],
        portal: @js($wtPortal),
        storageKey: 'vpa_walkthrough_done_{{ $wtPortal }}_{{ $wtUserId }}',
        shouldAutoStart: @js($wtAutoStart),
        forceStart: @js($wtForceStart),
        completeUrl: @js(route('walkthrough.complete')),
        csrfToken: @js(csrf_token()),
        spotlight: null,
        cardStyle: '',
        settleTimer: null,

        init() {
            this.rebuildVisibleSteps();
            window.addEventListener('resize', () => {
                if (this.isOpen) this.positionForCurrentStep(false);
            }, { passive: true });
            window.addEventListener('scroll', () => {
                if (this.isOpen) this.positionForCurrentStep(false);
            }, { passive: true });

            if (this.forceStart) {
                setTimeout(() => this.openTour(), 250);
            } else if (this.shouldAutoStart) {
                let seenLocal = false;
                try {
                    seenLocal = localStorage.getItem(this.storageKey) === '1';
                } catch (e) {}
                if (!seenLocal) {
                    setTimeout(() => this.openTour(), 350);
                }
            }
        },

        rebuildVisibleSteps() {
            this.steps = this.allSteps.filter((step) => {
                if (!step.optional) return true;
                return Boolean(step.targetId && document.getElementById(step.targetId));
            });
            if (this.steps.length === 0) {
                this.steps = [...this.allSteps];
            }
        },

        openTour() {
            this.rebuildVisibleSteps();
            this.activeIndex = 0;
            this.isOpen = true;
            this.$nextTick(() => this.positionForCurrentStep(true));
        },

        get currentStep() {
            return this.steps[this.activeIndex] || this.steps[0] || {};
        },

        isTargetVisible(el) {
            if (!el) return false;
            const style = window.getComputedStyle(el);
            if (style.display === 'none' || style.visibility === 'hidden') return false;
            const rect = el.getBoundingClientRect();
            return rect.width > 0 && rect.height > 0;
        },

        positionForCurrentStep(shouldScroll = true) {
            const step = this.currentStep;
            const el = step && step.targetId ? document.getElementById(step.targetId) : null;

            if (!this.isTargetVisible(el)) {
                this.spotlight = null;
                this.cardStyle = '';
                return;
            }

            if (shouldScroll) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });
                if (this.settleTimer) clearTimeout(this.settleTimer);
                this.settleTimer = setTimeout(() => {
                    if (this.isOpen) this.measureAndPlace(el);
                }, 360);
            }
            this.measureAndPlace(el);
        },

        measureAndPlace(el) {
            const rect = el.getBoundingClientRect();
            const vw = window.innerWidth;
            const vh = window.innerHeight;
            const pad = 6;

            const left = Math.max(8, rect.left - pad);
            const top = Math.max(8, rect.top - pad);
            const width = Math.min(vw - left - 8, rect.width + pad * 2);
            const height = Math.max(36, Math.min(vh - top - 8, rect.height + pad * 2));

            this.spotlight = {
                style: `left:${left}px; top:${top}px; width:${width}px; height:${height}px;`
            };

            if (vw < 640 || height > vh * 0.68) {
                const placeAtTop = rect.top > vh * 0.45 && rect.height < vh * 0.55;
                this.cardStyle = placeAtTop
                    ? 'top: 16px; left: 50%; transform: translateX(-50%);'
                    : 'bottom: 16px; left: 50%; transform: translateX(-50%);';
                return;
            }

            const cardWidth = Math.min(410, vw - 32);
            const estimatedCardHeight = 250;
            let cardLeft = Math.min(Math.max(16, rect.left + (rect.width / 2) - (cardWidth / 2)), vw - cardWidth - 16);
            let cardTop;

            const spaceBelow = vh - rect.bottom;
            const spaceAbove = rect.top;
            if (spaceBelow >= estimatedCardHeight + 18) {
                cardTop = rect.bottom + 14;
            } else if (spaceAbove >= estimatedCardHeight + 18) {
                cardTop = Math.max(16, rect.top - estimatedCardHeight - 14);
            } else {
                cardTop = Math.max(16, vh - estimatedCardHeight - 16);
            }

            cardTop = Math.min(Math.max(16, cardTop), vh - estimatedCardHeight - 16);
            this.cardStyle = `top:${cardTop}px; left:${cardLeft}px; width:${cardWidth}px;`;
        },

        nextStep() {
            if (this.activeIndex < this.steps.length - 1) {
                this.activeIndex++;
                this.$nextTick(() => this.positionForCurrentStep(true));
            } else {
                this.finishTour();
            }
        },

        prevStep() {
            if (this.activeIndex > 0) {
                this.activeIndex--;
                this.$nextTick(() => this.positionForCurrentStep(true));
            }
        },

        skipTour() {
            this.completeTour();
        },

        finishTour() {
            this.completeTour();
        },

        completeTour() {
            if (this.settleTimer) clearTimeout(this.settleTimer);
            this.isOpen = false;
            this.spotlight = null;
            this.shouldAutoStart = false;
            this.forceStart = false;

            try {
                localStorage.setItem(this.storageKey, '1');
            } catch (e) {}

            try {
                const url = new URL(window.location.href);
                if (url.searchParams.has('tour')) {
                    url.searchParams.delete('tour');
                    window.history.replaceState({}, '', url.toString());
                }
            } catch (e) {}

            try {
                fetch(this.completeUrl, {
                    method: 'POST',
                    keepalive: true,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ portal: this.portal })
                }).catch(() => {});
            } catch (e) {}
        }
    }"
    id="platform-walkthrough-root"
    @start-platform-tour.window="openTour()"
    @keydown.escape.window="if (isOpen) skipTour()">

    <div x-show="isOpen"
         x-cloak
         class="fixed inset-0 z-[1000]"
         role="dialog"
         aria-modal="true"
         aria-labelledby="walkthrough-step-title">

        {{-- Full-screen backdrop when target spotlight isn't active --}}
        <div x-show="!spotlight"
             @click="skipTour()"
             class="fixed inset-0 bg-slate-950/80 backdrop-blur-[2px]"></div>

        {{-- Illuminated Spotlight Cutout Ring when target element is visible --}}
        <div x-show="spotlight"
             :style="spotlight ? spotlight.style : ''"
             style="box-shadow: 0 0 0 9999px rgba(2, 6, 23, 0.78), 0 0 28px rgba(56, 189, 248, 0.45);"
             class="fixed z-[1001] rounded-2xl border-2 border-sky-400 pointer-events-none transition-all duration-300"></div>

        {{-- Click-catcher outside tooltip --}}
        <div x-show="spotlight"
             @click="skipTour()"
             class="fixed inset-0 z-[1001]"></div>

        {{-- Interactive Walkthrough Card --}}
        <div :style="cardStyle || 'top: 50%; left: 50%; transform: translate(-50%, -50%);'"
             class="fixed z-[1002] w-[calc(100vw-2rem)] sm:w-[410px] max-w-md rounded-2xl bg-gradient-to-b from-[#0b1f54] to-[#051030] border border-sky-400/40 p-5 text-white shadow-[0_25px_70px_rgba(0,0,0,0.8)] transition-all duration-300">

            <div class="flex items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-sky-500/20 border border-sky-400/40 text-sky-300 flex items-center justify-center text-sm shrink-0">
                        <i class="fas" :class="currentStep.icon || 'fa-compass'"></i>
                    </span>
                    <div>
                        <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-sky-300"
                              x-text="currentStep.badge || 'Platform Tour'"></span>
                        <div class="text-[11px] font-bold text-slate-300">
                            Step <span x-text="activeIndex + 1"></span> of <span x-text="steps.length"></span>
                        </div>
                    </div>
                </div>

                <button type="button"
                        @click="skipTour()"
                        class="text-xs font-semibold text-slate-400 hover:text-white px-2.5 py-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer">
                    Skip
                </button>
            </div>

            {{-- Progress Bar --}}
            <div class="w-full h-1.5 rounded-full bg-slate-900/90 border border-white/10 overflow-hidden mb-3.5">
                <div class="h-full rounded-full bg-gradient-to-r from-sky-400 to-emerald-400 transition-all duration-300"
                     :style="`width: ${steps.length ? Math.round(((activeIndex + 1) / steps.length) * 100) : 100}%`"></div>
            </div>

            <h3 id="walkthrough-step-title"
                class="text-base sm:text-lg font-black text-white tracking-tight"
                x-text="currentStep.title"></h3>

            <p class="text-xs sm:text-sm text-slate-200/90 mt-1.5 leading-relaxed"
               x-text="currentStep.description"></p>

            {{-- Step Dots --}}
            <div class="flex items-center gap-1.5 mt-4">
                <template x-for="(s, idx) in steps" :key="idx">
                    <button type="button"
                            @click="activeIndex = idx; $nextTick(() => positionForCurrentStep(true))"
                            :class="idx === activeIndex ? 'w-5 bg-sky-400' : (idx < activeIndex ? 'w-2 bg-emerald-400/70' : 'w-2 bg-white/20')"
                            class="h-1.5 rounded-full transition-all cursor-pointer"
                            :aria-label="'Go to step ' + (idx + 1)"></button>
                </template>
            </div>

            {{-- Back / Next / Finish Controls --}}
            <div class="flex items-center justify-between gap-2 mt-4 pt-3.5 border-t border-white/10">
                <button type="button"
                        @click="skipTour()"
                        class="text-xs font-bold text-slate-400 hover:text-white px-2 py-1.5 rounded-lg hover:bg-white/5 transition-colors cursor-pointer">
                    Skip Tour
                </button>

                <div class="flex items-center gap-2">
                    <button type="button"
                            x-show="activeIndex > 0"
                            @click="prevStep()"
                            class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-xs font-bold text-white transition-colors inline-flex items-center gap-1.5 cursor-pointer">
                        <i class="fas fa-arrow-left text-[10px]"></i>
                        <span>Back</span>
                    </button>

                    <button type="button"
                            @click="nextStep()"
                            class="px-4 py-2 rounded-xl bg-gradient-to-r from-sky-400 to-blue-600 hover:brightness-110 text-white text-xs font-black shadow-md transition-all inline-flex items-center gap-1.5 cursor-pointer">
                        <span x-text="activeIndex < steps.length - 1 ? 'Next' : 'Finish Tour'"></span>
                        <i class="fas text-[10px]" :class="activeIndex < steps.length - 1 ? 'fa-arrow-right' : 'fa-check'"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
