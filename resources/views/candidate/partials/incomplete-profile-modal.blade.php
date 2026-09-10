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

    {{-- Backdrop with deep blur & dark gradient --}}
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="close()"
         class="fixed inset-0 bg-slate-950/85 backdrop-blur-md transition-opacity"></div>

    {{-- Center Modal Dialog --}}
    <div x-show="showModal"
         x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-300 transform"
         x-transition:enter-start="opacity-0 scale-90 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-90 translate-y-4"
         class="relative w-full max-w-lg sm:max-w-xl bg-gradient-to-b from-[#0b1b4f] via-[#07173e] to-[#040e2d] border border-blue-400/30 rounded-3xl shadow-[0_25px_70px_rgba(0,0,0,0.8),0_0_50px_rgba(18,154,239,0.25)] p-6 sm:p-8 text-white z-10 overflow-hidden my-auto">

        {{-- Ambient decorative glow effects inside modal --}}
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-sky-500/15 rounded-full blur-3xl pointer-events-none"></div>

        {{-- Top Right Close Button --}}
        <button type="button" 
                @click="close()"
                class="absolute top-4 right-4 sm:top-5 sm:right-5 w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-all duration-200 border border-white/10 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-400"
                title="Close and view dashboard">
            <i class="fas fa-times text-sm"></i>
        </button>

        {{-- Header: Icon + Chip + Title --}}
        <div class="relative z-10 flex flex-col items-center text-center">
            
            {{-- Warning Icon with Animated Ping Indicator --}}
            <div class="relative mb-3">
                <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl bg-gradient-to-tr from-amber-500/20 via-sky-500/20 to-blue-600/30 border border-amber-400/40 flex items-center justify-center text-2xl sm:text-3xl text-amber-400 shadow-[0_0_25px_rgba(245,158,11,0.3)]">
                    <i class="fas fa-user-clock"></i>
                </div>
                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-500 border-2 border-[#07173e]"></span>
                </span>
            </div>

            {{-- Action Required Pill --}}
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/15 border border-amber-500/30 text-amber-300 text-[11px] font-bold uppercase tracking-wider mb-2.5 shadow-sm">
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
        <div class="relative z-10 mt-5 bg-white/[0.04] border border-white/10 rounded-2xl p-3.5 sm:p-4">
            <div class="flex items-center justify-between text-xs font-bold mb-2">
                <span class="text-slate-300 flex items-center gap-1.5">
                    <i class="fas fa-tasks text-sky-400"></i> Overall Progress
                </span>
                <span class="text-emerald-400 font-extrabold">{{ $completedSteps }}/3 Steps ({{ $progressPercent }}%)</span>
            </div>

            <div class="w-full bg-black/40 rounded-full h-2.5 p-0.5 overflow-hidden border border-white/10">
                <div class="bg-gradient-to-r from-emerald-400 via-sky-400 to-indigo-400 h-full rounded-full transition-all duration-700 ease-out shadow-[0_0_10px_rgba(52,211,153,0.5)]"
                     style="width: {{ max(10, $progressPercent) }}%;"></div>
            </div>

            {{-- 3-Step Checklist Breakdown --}}
            <div class="mt-4 space-y-2.5 text-left">
                
                {{-- Step 1 --}}
                <div class="flex items-center justify-between p-2.5 rounded-xl transition-all {{ $step1Complete ? 'bg-emerald-500/10 border border-emerald-500/25 text-emerald-200' : 'bg-amber-500/10 border border-amber-500/30 text-amber-200' }}">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ $step1Complete ? 'bg-emerald-500 text-slate-950 font-black' : 'bg-amber-500/20 text-amber-400' }}">
                            <i class="fas {{ $step1Complete ? 'fa-check' : 'fa-user' }} text-xs"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-white truncate">1. Profile & Qualifications</h4>
                            <p class="text-[10px] {{ $step1Complete ? 'text-emerald-300/80' : 'text-amber-200/80' }}">Personal details, subject & resume upload</p>
                        </div>
                    </div>
                    <span class="shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider {{ $step1Complete ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/40 animate-pulse' }}">
                        {{ $step1Complete ? 'Completed' : 'Pending' }}
                    </span>
                </div>

                {{-- Step 2 --}}
                <div class="flex items-center justify-between p-2.5 rounded-xl transition-all {{ $step2Complete ? 'bg-emerald-500/10 border border-emerald-500/25 text-emerald-200' : ($step1Complete ? 'bg-amber-500/10 border border-amber-500/30 text-amber-200' : 'bg-white/[0.03] border border-white/5 text-slate-400') }}">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ $step2Complete ? 'bg-emerald-500 text-slate-950 font-black' : ($step1Complete ? 'bg-amber-500/20 text-amber-400' : 'bg-white/5 text-white/40') }}">
                            <i class="fas {{ $step2Complete ? 'fa-check' : 'fa-file-signature' }} text-xs"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold {{ $step2Complete || $step1Complete ? 'text-white' : 'text-slate-400' }} truncate">2. Placement Agreement</h4>
                            <p class="text-[10px] {{ $step2Complete ? 'text-emerald-300/80' : ($step1Complete ? 'text-amber-200/80' : 'text-slate-500') }}">Terms acceptance & digital candidate signature</p>
                        </div>
                    </div>
                    <span class="shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider {{ $step2Complete ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($step1Complete ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40 animate-pulse' : 'bg-white/5 text-slate-500 border border-white/10') }}">
                        {{ $step2Complete ? 'Signed' : 'Pending' }}
                    </span>
                </div>

                {{-- Step 3 --}}
                <div class="flex items-center justify-between p-2.5 rounded-xl transition-all {{ $step3Complete ? 'bg-emerald-500/10 border border-emerald-500/25 text-emerald-200' : ($step2Complete ? 'bg-amber-500/10 border border-amber-500/30 text-amber-200' : 'bg-white/[0.03] border border-white/5 text-slate-400') }}">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ $step3Complete ? 'bg-emerald-500 text-slate-950 font-black' : ($step2Complete ? 'bg-amber-500/20 text-amber-400' : 'bg-white/5 text-white/40') }}">
                            <i class="fas {{ $step3Complete ? 'fa-check' : 'fa-credit-card' }} text-xs"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold {{ $step3Complete || $step2Complete ? 'text-white' : 'text-slate-400' }} truncate">3. Account Activation Fee</h4>
                            <p class="text-[10px] {{ $step3Complete ? 'text-emerald-300/80' : ($step2Complete ? 'text-amber-200/80' : 'text-slate-500') }}">One-time registration fee to verify profile</p>
                        </div>
                    </div>
                    <span class="shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider {{ $step3Complete ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($step2Complete ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40 animate-pulse' : 'bg-white/5 text-slate-500 border border-white/10') }}">
                        {{ $step3Complete ? 'Activated' : 'Pending' }}
                    </span>
                </div>

            </div>
        </div>

        {{-- Call To Action Buttons --}}
        <div class="relative z-10 mt-6 flex flex-col gap-2.5">
            {{-- Primary Action Button --}}
            <a href="{{ $wizardUrl }}" 
               class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white font-black text-sm text-center shadow-[0_8px_25px_rgba(37,99,235,0.4)] hover:shadow-[0_10px_30px_rgba(37,99,235,0.6)] flex items-center justify-center gap-2.5 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                <span>{{ $ctaLabel }}</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </a>

            {{-- Secondary Dismiss Button --}}
            <button type="button" 
                    @click="close()" 
                    class="w-full py-2.5 text-xs text-slate-400 hover:text-white font-semibold transition-colors duration-200">
                I'll complete this later &bull; Continue to Dashboard
            </button>
        </div>

    </div>
</div>
@endif
