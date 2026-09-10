{{-- Candidate Incomplete Registration / Profile Popup Modal --}}
@php
    $user = auth()->user();
    $profile = $user ? ($user->profile ?: null) : null;
    $step1Complete = (bool) ($profile && $profile->is_profile_complete);
    $step2Complete = (bool) ($profile && $profile->is_agreement_signed);
    $step3Complete = (bool) ($profile && ($profile->initial_fee_paid || $profile->is_fee_paid));
    
    $completedSteps = ($step1Complete ? 1 : 0) + ($step2Complete ? 1 : 0) + ($step3Complete ? 1 : 0);
    $isRegistrationComplete = ($completedSteps === 3);
    $progressPercent = (int) round(($completedSteps / 3) * 100);

    // Smart Action CTA & Step Label
    if (!$step1Complete) {
        $ctaLabel = 'Complete Profile (Step 1)';
        $pendingStepText = 'Your profile details and documents are pending.';
    } elseif (!$step2Complete) {
        $ctaLabel = 'Sign Agreement (Step 2)';
        $pendingStepText = 'Your placement agreement is pending signature.';
    } else {
        $ctaLabel = 'Pay Registration Fee (Step 3)';
        $pendingStepText = 'Your registration fee payment is pending to activate account.';
    }
    $wizardUrl = route('candidate.wizard');
@endphp

@if(!$isRegistrationComplete)
<div x-data="{
        showModal: true,
        close() {
            this.showModal = false;
        }
    }"
    x-show="showModal"
    x-cloak
    @keydown.escape.window="close()"
    @open-incomplete-modal.window="showModal = true"
    class="fixed inset-0 z-[999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="incomplete-modal-title">

    {{-- Backdrop with deep blur & dark overlay --}}
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="close()"
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity"></div>

    {{-- Center Modal Dialog --}}
    <div x-show="showModal"
         x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-300 transform"
         x-transition:enter-start="opacity-0 scale-90 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-90 translate-y-4"
         style="background: linear-gradient(180deg, #0a1b4d 0%, #07173e 60%, #040e2d 100%); border: 1px solid rgba(56, 189, 248, 0.35); box-shadow: 0 25px 70px rgba(0, 0, 0, 0.85), 0 0 50px rgba(14, 165, 233, 0.2);"
         class="relative w-full max-w-lg sm:max-w-xl rounded-3xl p-6 sm:p-8 text-white z-10 overflow-hidden my-auto">

        {{-- Ambient decorative glow effects inside modal --}}
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-sky-500/15 rounded-full blur-3xl pointer-events-none"></div>

        {{-- Top Right Close Button --}}
        <button type="button" 
                @click="close()"
                class="absolute top-4 right-4 sm:top-5 sm:right-5 w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-all duration-200 border border-white/15 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-400"
                title="Close and view dashboard">
            <i class="fas fa-times text-sm"></i>
        </button>

        {{-- Header: Icon + Chip + Title --}}
        <div class="relative z-10 flex flex-col items-center text-center">
            
            {{-- Warning Icon with Glowing Pulse Indicator --}}
            <div class="relative mb-3">
                <div style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.25) 0%, rgba(217, 119, 6, 0.35) 100%); border: 1.5px solid rgba(245, 158, 11, 0.6); box-shadow: 0 0 25px rgba(245, 158, 11, 0.25);"
                     class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl text-amber-400">
                    <i class="fas fa-user-clock"></i>
                </div>
                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-500 border-2 border-[#07173e]"></span>
                </span>
            </div>

            {{-- Action Required Pill --}}
            <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-amber-500/15 border border-amber-500/35 text-amber-300 text-[11px] font-extrabold uppercase tracking-wider mb-2.5 shadow-sm">
                <i class="fas fa-exclamation-triangle text-[10px]"></i>
                <span>Action Required &bull; Registration Incomplete</span>
            </div>

            {{-- Main Title --}}
            <h2 id="incomplete-modal-title" class="text-xl sm:text-2xl font-black text-white tracking-tight leading-snug">
                Complete Your Registration
            </h2>

            {{-- Subtitle --}}
            <p class="text-xs sm:text-sm text-slate-300/90 mt-1.5 max-w-md mx-auto leading-relaxed">
                Welcome, <span class="font-bold text-white">{{ $user->name ?? 'Candidate' }}</span>! 
                {{ $pendingStepText }} Complete your registration to unlock job applications and start receiving interview calls from top schools.
            </p>
        </div>

        {{-- Progress Bar Section --}}
        <div style="background: rgba(13, 27, 68, 0.6); border: 1px solid rgba(56, 189, 248, 0.2);" 
             class="relative z-10 mt-5 rounded-2xl p-3.5 sm:p-4 shadow-sm">
            <div class="flex items-center justify-between text-xs font-bold mb-2.5">
                <span class="text-slate-300 flex items-center gap-1.5 font-bold">
                    <i class="fas fa-tasks text-sky-400"></i> Overall Progress
                </span>
                <span class="text-emerald-400 font-extrabold bg-emerald-500/15 border border-emerald-500/30 px-2.5 py-0.5 rounded-full text-[11px]">
                    {{ $completedSteps }}/3 Steps ({{ $progressPercent }}%)
                </span>
            </div>

            <div class="w-full bg-slate-900/90 rounded-full h-2.5 p-0.5 overflow-hidden border border-white/10 shadow-inner">
                <div style="width: {{ max(10, $progressPercent) }}%; background: linear-gradient(90deg, #10b981 0%, #06b6d4 50%, #3b82f6 100%);"
                     class="h-full rounded-full transition-all duration-700 ease-out shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>
            </div>

            {{-- 3-Step Checklist Breakdown --}}
            <div class="mt-4 space-y-2.5 text-left">
                
                {{-- Step 1: Profile & Qualifications --}}
                <div style="{{ $step1Complete ? 'background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.35);' : 'background: rgba(245, 158, 11, 0.12); border: 1.5px solid rgba(245, 158, 11, 0.5);' }}"
                     class="flex items-center justify-between p-3 rounded-xl transition-all">
                    <div class="flex items-center gap-3 min-w-0">
                        <div style="{{ $step1Complete ? 'background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff;' : 'background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #020617;' }}"
                             class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 text-xs font-black shadow-sm">
                            <i class="fas {{ $step1Complete ? 'fa-check' : 'fa-user' }}"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-bold text-white truncate">1. Profile & Qualifications</h4>
                            <p class="text-[11px] {{ $step1Complete ? 'text-emerald-300/80' : 'text-amber-200/90' }}">Personal details, subject & resume upload</p>
                        </div>
                    </div>
                    <span class="shrink-0 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider flex items-center gap-1 {{ $step1Complete ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/35' : 'bg-amber-500/25 text-amber-300 border border-amber-500/50 animate-pulse' }}">
                        @if($step1Complete)
                            <i class="fas fa-check text-[9px]"></i>
                            <span>Completed</span>
                        @else
                            <i class="fas fa-clock text-[9px]"></i>
                            <span>Pending</span>
                        @endif
                    </span>
                </div>

                {{-- Step 2: Placement Agreement --}}
                <div style="{{ $step2Complete ? 'background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.35);' : ($step1Complete ? 'background: rgba(245, 158, 11, 0.14); border: 1.5px solid rgba(245, 158, 11, 0.6); box-shadow: 0 0 18px rgba(245, 158, 11, 0.12);' : 'background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08);') }}"
                     class="flex items-center justify-between p-3 rounded-xl transition-all">
                    <div class="flex items-center gap-3 min-w-0">
                        <div style="{{ $step2Complete ? 'background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff;' : ($step1Complete ? 'background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #020617;' : 'background: rgba(255, 255, 255, 0.06); color: rgba(255, 255, 255, 0.4);') }}"
                             class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 text-xs font-black shadow-sm">
                            <i class="fas {{ $step2Complete ? 'fa-check' : 'fa-file-signature' }}"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-bold {{ $step2Complete || $step1Complete ? 'text-white' : 'text-slate-400' }} truncate">2. Placement Agreement</h4>
                            <p class="text-[11px] {{ $step2Complete ? 'text-emerald-300/80' : ($step1Complete ? 'text-amber-200/90' : 'text-slate-500') }}">Terms acceptance & digital candidate signature</p>
                        </div>
                    </div>
                    <span class="shrink-0 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider flex items-center gap-1 {{ $step2Complete ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/35' : ($step1Complete ? 'bg-amber-500/25 text-amber-300 border border-amber-500/50 animate-pulse' : 'bg-white/5 text-slate-500 border border-white/10') }}">
                        @if($step2Complete)
                            <i class="fas fa-check text-[9px]"></i>
                            <span>Signed</span>
                        @elseif($step1Complete)
                            <i class="fas fa-clock text-[9px]"></i>
                            <span>Pending</span>
                        @else
                            <span>Locked</span>
                        @endif
                    </span>
                </div>

                {{-- Step 3: Account Activation Fee --}}
                <div style="{{ $step3Complete ? 'background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.35);' : ($step2Complete ? 'background: rgba(245, 158, 11, 0.14); border: 1.5px solid rgba(245, 158, 11, 0.6); box-shadow: 0 0 18px rgba(245, 158, 11, 0.12);' : 'background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08);') }}"
                     class="flex items-center justify-between p-3 rounded-xl transition-all">
                    <div class="flex items-center gap-3 min-w-0">
                        <div style="{{ $step3Complete ? 'background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff;' : ($step2Complete ? 'background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #020617;' : 'background: rgba(255, 255, 255, 0.06); color: rgba(255, 255, 255, 0.4);') }}"
                             class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 text-xs font-black shadow-sm">
                            <i class="fas {{ $step3Complete ? 'fa-check' : 'fa-credit-card' }}"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-bold {{ $step3Complete || $step2Complete ? 'text-white' : 'text-slate-400' }} truncate">3. Account Activation Fee</h4>
                            <p class="text-[11px] {{ $step3Complete ? 'text-emerald-300/80' : ($step2Complete ? 'text-amber-200/90' : 'text-slate-500') }}">One-time registration fee to verify profile</p>
                        </div>
                    </div>
                    <span class="shrink-0 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider flex items-center gap-1 {{ $step3Complete ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/35' : ($step2Complete ? 'bg-amber-500/25 text-amber-300 border border-amber-500/50 animate-pulse' : 'bg-white/5 text-slate-500 border border-white/10') }}">
                        @if($step3Complete)
                            <i class="fas fa-check text-[9px]"></i>
                            <span>Activated</span>
                        @elseif($step2Complete)
                            <i class="fas fa-clock text-[9px]"></i>
                            <span>Pending</span>
                        @else
                            <span>Locked</span>
                        @endif
                    </span>
                </div>

            </div>
        </div>

        {{-- Call To Action Buttons --}}
        <div class="relative z-10 mt-6 flex flex-col gap-3">
            {{-- Primary Action Button (Guaranteed vibrant gradient, crisp white text & prominent button container) --}}
            <a href="{{ $wizardUrl }}" 
               style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff;"
               class="w-full py-3.5 px-6 rounded-2xl bg-blue-600 hover:bg-blue-500 font-black text-sm text-center shadow-[0_6px_25px_rgba(37,99,235,0.5)] hover:shadow-[0_8px_32px_rgba(37,99,235,0.7)] flex items-center justify-center gap-2.5 transition-all transform hover:scale-[1.01] active:scale-[0.99] whitespace-nowrap">
                <span class="font-black tracking-wide">{{ $ctaLabel }}</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </a>

            {{-- Secondary Dismiss Button --}}
            <button type="button" 
                    @click="close()" 
                    class="w-full py-2.5 px-4 rounded-xl text-xs text-slate-400 hover:text-white hover:bg-white/5 font-semibold transition-all duration-200 border border-transparent hover:border-white/10 flex items-center justify-center gap-1.5">
                <span>I'll complete this later &bull; Continue to Dashboard</span>
                <i class="fas fa-arrow-right text-[10px] text-slate-500"></i>
            </button>
        </div>

    </div>
</div>
@endif
