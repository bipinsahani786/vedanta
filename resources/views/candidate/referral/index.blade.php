@extends('layouts.candidate')

@section('candidate_content')
<div class="space-y-8 w-full pb-12" x-data="{
    copiedCode: false,
    copiedLink: false,
    toastMsg: '',
    showToast: false,
    copyToClipboard(text, type) {
        navigator.clipboard.writeText(text);
        if (type === 'code') this.copiedCode = true;
        if (type === 'link') this.copiedLink = true;
        this.toastMsg = 'Copied to clipboard!';
        this.showToast = true;
        setTimeout(() => {
            this.copiedCode = false;
            this.copiedLink = false;
            this.showToast = false;
        }, 2500);
    },
    shareNative() {
        if (navigator.share) {
            navigator.share({
                title: 'Join Vedanta Placement Agency',
                text: 'Hi! You can register with Vedanta Placement Agency and explore relevant teaching opportunities. Use my referral link to get 100 Welcome Points:',
                url: '{{ url('/r/' . $user->referral_code) }}'
            }).catch(() => {});
        } else {
            this.copyToClipboard('{{ url('/r/' . $user->referral_code) }}', 'link');
        }
    }
}">

    {{-- Floating Toast Notification --}}
    <div x-show="showToast" x-transition.duration.300ms class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-2xl border border-white/10 flex items-center gap-3 text-sm font-semibold" style="display: none;">
        <i class="fas fa-check-circle text-emerald-400 text-base"></i>
        <span x-text="toastMsg"></span>
    </div>

    {{-- Top Alert Messages --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-2xl flex items-center justify-between text-xs font-semibold">
            <div class="flex items-center gap-2.5">
                <i class="fas fa-check-circle text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-2xl flex items-center justify-between text-xs font-semibold">
            <div class="flex items-center gap-2.5">
                <i class="fas fa-exclamation-circle text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
    @endif

    {{-- TOP GREETING HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-card-border pb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-text-main tracking-tight flex items-center gap-2">
                Welcome back, {{ $user->name }} 👋
            </h1>
            <p class="text-xs sm:text-sm text-text-dark/50 mt-1">Let's grow together with Vedanta Placement Agency — Refer Friends, Earn Points, and Save on Service Charges.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="#redeem" class="px-5 py-2.5 bg-accent-yellow text-slate-900 font-bold rounded-xl text-xs hover:bg-accent-yellow/90 transition-all shadow-md flex items-center gap-2">
                <i class="fas fa-coins"></i> Redeem Points
            </a>
        </div>
    </div>

    {{-- 4 TOP METRIC CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Card 1: Available Points --}}
        <div class="bg-card-bg border border-card-border rounded-3xl p-6 relative overflow-hidden shadow-sm hover:border-accent-yellow/40 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Available Points</span>
                <div class="w-9 h-9 rounded-2xl bg-accent-yellow/15 text-accent-yellow flex items-center justify-center text-base shadow-sm">
                    🪙
                </div>
            </div>
            <div class="text-3xl font-black text-text-main">{{ number_format($availablePoints) }} <span class="text-xs font-bold text-accent-yellow">Points</span></div>
            <div class="text-xs text-text-dark/50 mt-2 font-medium">
                Worth <strong class="text-emerald-400 font-bold">₹{{ number_format($inrValue, 2) }}</strong> Service Charge Discount
            </div>
        </div>

        {{-- Card 2: Total Referrals --}}
        <div class="bg-card-bg border border-card-border rounded-3xl p-6 relative overflow-hidden shadow-sm hover:border-accent-blue/40 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Total Referrals</span>
                <div class="w-9 h-9 rounded-2xl bg-accent-blue/15 text-accent-blue flex items-center justify-center text-base shadow-sm">
                    <i class="fas fa-user-friends"></i>
                </div>
            </div>
            <div class="text-3xl font-black text-text-main">{{ $totalReferrals }} <span class="text-xs font-bold text-accent-blue">Friends</span></div>
            <div class="text-xs text-text-dark/50 mt-2 font-medium truncate">
                <span class="text-emerald-400 font-bold">Successful {{ $successfulReferrals }}</span> &bull; 
                <span class="text-amber-400 font-bold">Pending {{ $pendingReferrals }}</span> &bull; 
                <span class="text-rose-400 font-bold">Rejected {{ $rejectedReferrals }}</span>
            </div>
        </div>

        {{-- Card 3: Lifetime Earned --}}
        <div class="bg-card-bg border border-card-border rounded-3xl p-6 relative overflow-hidden shadow-sm hover:border-purple-500/40 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Lifetime Earned</span>
                <div class="w-9 h-9 rounded-2xl bg-purple-500/15 text-purple-400 flex items-center justify-center text-base shadow-sm">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div class="text-3xl font-black text-text-main">{{ number_format($lifetimeEarned) }} <span class="text-xs font-bold text-purple-400">Points</span></div>
            <div class="text-xs text-text-dark/50 mt-2 font-medium">
                Redeemed <strong>{{ number_format($redeemedPoints) }} Points</strong>
            </div>
        </div>

        {{-- Card 4: Next Milestone --}}
        <div class="bg-card-bg border border-card-border rounded-3xl p-6 relative overflow-hidden shadow-sm hover:border-blue-500/40 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Next Milestone</span>
                <div class="w-9 h-9 rounded-2xl bg-blue-500/15 text-blue-400 flex items-center justify-center text-base shadow-sm">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>
            <div class="text-3xl font-black text-text-main">
                {{ $milestoneData['remaining'] > 0 ? $milestoneData['remaining'] : 0 }} 
                <span class="text-xs font-bold text-blue-400">Referrals to go</span>
            </div>
            <div class="text-xs text-text-dark/50 mt-2 font-medium">
                Get <strong class="text-blue-400 font-bold">+{{ number_format($milestoneData['bonus_points']) }} Bonus Points</strong>
            </div>
        </div>
    </div>

    {{-- STEP-BY-STEP REFERRAL FLOW BANNER --}}
    <div class="bg-card-bg border border-card-border rounded-3xl p-6 sm:p-8 shadow-sm">
        <h3 class="text-xs font-black uppercase tracking-widest text-text-dark/40 mb-6 text-center sm:text-left">
            Referral Flow — Step by Step
        </h3>
        
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-4 relative">
            {{-- Step 1 --}}
            <div class="text-center p-3 rounded-2xl bg-secondary-bg/60 border border-card-border">
                <div class="w-10 h-10 mx-auto rounded-full bg-accent-blue/15 text-accent-blue flex items-center justify-center font-black text-xs mb-2">
                    <i class="fas fa-share-alt"></i>
                </div>
                <div class="text-xs font-bold text-text-main">Step 1</div>
                <div class="text-[10px] text-text-dark/50 mt-0.5">You Share Referral Link</div>
            </div>

            {{-- Step 2 --}}
            <div class="text-center p-3 rounded-2xl bg-secondary-bg/60 border border-card-border">
                <div class="w-10 h-10 mx-auto rounded-full bg-blue-500/15 text-blue-400 flex items-center justify-center font-black text-xs mb-2">
                    <i class="fas fa-link"></i>
                </div>
                <div class="text-xs font-bold text-text-main">Step 2</div>
                <div class="text-[10px] text-text-dark/50 mt-0.5">Friend Clicks Link / Code</div>
            </div>

            {{-- Step 3 --}}
            <div class="text-center p-3 rounded-2xl bg-secondary-bg/60 border border-card-border">
                <div class="w-10 h-10 mx-auto rounded-full bg-purple-500/15 text-purple-400 flex items-center justify-center font-black text-xs mb-2">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="text-xs font-bold text-text-main">Step 3</div>
                <div class="text-[10px] text-text-dark/50 mt-0.5">Friend Registers on Website</div>
            </div>

            {{-- Step 4 --}}
            <div class="text-center p-3 rounded-2xl bg-secondary-bg/60 border border-card-border">
                <div class="w-10 h-10 mx-auto rounded-full bg-indigo-500/15 text-indigo-400 flex items-center justify-center font-black text-xs mb-2">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="text-xs font-bold text-text-main">Step 4</div>
                <div class="text-[10px] text-text-dark/50 mt-0.5">Friend Completes Profile</div>
            </div>

            {{-- Step 5 --}}
            <div class="text-center p-3 rounded-2xl bg-secondary-bg/60 border border-card-border">
                <div class="w-10 h-10 mx-auto rounded-full bg-amber-500/15 text-amber-400 flex items-center justify-center font-black text-xs mb-2">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="text-xs font-bold text-text-main">Step 5</div>
                <div class="text-[10px] text-text-dark/50 mt-0.5">Verified & Interview Done</div>
            </div>

            {{-- Step 6 --}}
            <div class="text-center p-3 rounded-2xl bg-secondary-bg/60 border border-card-border">
                <div class="w-10 h-10 mx-auto rounded-full bg-emerald-500/15 text-emerald-400 flex items-center justify-center font-black text-xs mb-2">
                    <i class="fas fa-building"></i>
                </div>
                <div class="text-xs font-bold text-text-main">Step 6</div>
                <div class="text-[10px] text-text-dark/50 mt-0.5">Friend Joins Successfully</div>
            </div>

            {{-- Step 7 --}}
            <div class="text-center p-3 rounded-2xl bg-accent-yellow/10 border border-accent-yellow/30 col-span-2 sm:col-span-1">
                <div class="w-10 h-10 mx-auto rounded-full bg-accent-yellow text-slate-900 flex items-center justify-center font-black text-xs mb-2 shadow-md">
                    🪙
                </div>
                <div class="text-xs font-black text-accent-yellow">Step 7</div>
                <div class="text-[10px] text-accent-yellow/80 mt-0.5 font-bold">You Earn Reward Points</div>
            </div>
        </div>
    </div>

    {{-- REFERRAL CODE & LINK CARD (HERO ACTION) --}}
    <div class="bg-gradient-to-br from-card-bg via-card-bg to-accent-blue/10 border border-card-border rounded-3xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-8 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Referral Code --}}
                    <div class="bg-secondary-bg/80 border border-card-border rounded-2xl p-4 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Your Referral Code</span>
                            <div class="text-2xl font-mono font-black text-accent-blue mt-0.5">{{ $user->referral_code }}</div>
                        </div>
                        <button type="button" @click="copyToClipboard('{{ $user->referral_code }}', 'code')" class="px-4 py-2 bg-accent-blue/15 hover:bg-accent-blue/25 text-accent-blue rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 border border-accent-blue/30">
                            <i class="fas" :class="copiedCode ? 'fa-check text-emerald-400' : 'fa-copy'"></i>
                            <span x-text="copiedCode ? 'Copied' : 'Copy Code'"></span>
                        </button>
                    </div>

                    {{-- Referral Link --}}
                    <div class="bg-secondary-bg/80 border border-card-border rounded-2xl p-4 flex items-center justify-between">
                        <div class="truncate mr-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-text-dark/40">Your Referral Link</span>
                            <div class="text-xs font-mono font-bold text-text-main mt-1 truncate">{{ url('/r/' . $user->referral_code) }}</div>
                        </div>
                        <button type="button" @click="copyToClipboard('{{ url('/r/' . $user->referral_code) }}', 'link')" class="px-4 py-2 bg-secondary-bg hover:bg-card-border text-text-main rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 border border-card-border shrink-0">
                            <i class="fas" :class="copiedLink ? 'fa-check text-emerald-400' : 'fa-link'"></i>
                            <span x-text="copiedLink ? 'Copied' : 'Copy Link'"></span>
                        </button>
                    </div>
                </div>

                {{-- Share Buttons --}}
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-xs font-bold text-text-dark/40 uppercase tracking-wider mr-1">Share your link via:</span>
                    
                    {{-- WhatsApp Share --}}
                    @php
                        $waText = urlencode("Hi! You can register with Vedanta Placement Agency and explore teaching opportunities. Use my referral link to get 100 Welcome Points:\n" . url('/r/' . $user->referral_code) . "\nReferral Code: " . $user->referral_code);
                    @endphp
                    <a href="https://api.whatsapp.com/send?text={{ $waText }}" target="_blank" class="px-4 py-2.5 bg-[#25D366] hover:bg-[#20bd5a] text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-2">
                        <i class="fab fa-whatsapp text-sm"></i> WhatsApp
                    </a>

                    {{-- Email Share Link --}}
                    <a href="#invite-section" class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-2">
                        <i class="fas fa-envelope text-xs"></i> Email
                    </a>

                    {{-- Telegram Share --}}
                    @php
                        $tgUrl = "https://t.me/share/url?url=" . urlencode(url('/r/' . $user->referral_code)) . "&text=" . urlencode("Join Vedanta Placement Agency with 100 Welcome Points!");
                    @endphp
                    <a href="{{ $tgUrl }}" target="_blank" class="px-4 py-2.5 bg-[#0088cc] hover:bg-[#0077b5] text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-2">
                        <i class="fab fa-telegram-plane text-xs"></i> Telegram
                    </a>

                    {{-- Native Device Share --}}
                    <button type="button" @click="shareNative()" class="px-4 py-2.5 bg-secondary-bg hover:bg-card-border text-text-main rounded-xl text-xs font-bold transition-all border border-card-border flex items-center gap-2">
                        <i class="fas fa-share-alt text-xs"></i> More
                    </button>
                </div>
            </div>

            {{-- Illustration side --}}
            <div class="lg:col-span-4 flex justify-center items-center">
                <div class="w-44 h-44 rounded-3xl bg-gradient-to-tr from-accent-blue/20 to-accent-yellow/20 border border-white/5 flex items-center justify-center text-7xl shadow-xl">
                    🎉
                </div>
            </div>
        </div>
    </div>

    {{-- 3-COLUMN ACTION & SHARING HUB --}}
    <div id="invite-section" class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{
        extraEmails: [],
        addEmail() {
            if (this.extraEmails.length < 4) {
                this.extraEmails.push({ name: '', email: '' });
            }
        },
        removeEmail(idx) {
            this.extraEmails.splice(idx, 1);
        }
    }">
        {{-- Column 1: Invite by Email --}}
        <div class="bg-card-bg border border-card-border rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-text-main flex items-center gap-2 mb-1">
                    <i class="fas fa-paper-plane text-purple-400"></i> Invite Your Friends
                </h3>
                <p class="text-xs text-text-dark/50 mb-5">Send direct email invitations to your teacher friends</p>

                <form action="{{ route('candidate.referral.invite') }}" method="POST" class="space-y-3.5">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-text-dark/40 uppercase tracking-widest mb-1">Friend's Name</label>
                        <input type="text" name="friend_names[]" required placeholder="Enter name" class="w-full bg-secondary-bg border border-card-border rounded-xl px-3.5 py-2.5 text-xs text-text-main placeholder-text-dark/30 focus:outline-none focus:border-accent-blue">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-text-dark/40 uppercase tracking-widest mb-1">Friend's Email</label>
                        <input type="email" name="friend_emails[]" required placeholder="Enter email address" class="w-full bg-secondary-bg border border-card-border rounded-xl px-3.5 py-2.5 text-xs text-text-main placeholder-text-dark/30 focus:outline-none focus:border-accent-blue">
                    </div>

                    {{-- Extra Email Inputs --}}
                    <template x-for="(item, index) in extraEmails" :key="index">
                        <div class="space-y-2 pt-2 border-t border-card-border relative">
                            <button type="button" @click="removeEmail(index)" class="absolute top-2 right-0 text-rose-400 hover:text-rose-300 text-xs"><i class="fas fa-times"></i></button>
                            <input type="text" name="friend_names[]" required placeholder="Friend's Name" class="w-full bg-secondary-bg border border-card-border rounded-xl px-3.5 py-2 text-xs text-text-main placeholder-text-dark/30 focus:outline-none focus:border-accent-blue">
                            <input type="email" name="friend_emails[]" required placeholder="Friend's Email" class="w-full bg-secondary-bg border border-card-border rounded-xl px-3.5 py-2 text-xs text-text-main placeholder-text-dark/30 focus:outline-none focus:border-accent-blue">
                        </div>
                    </template>

                    <div class="flex items-center justify-between pt-1">
                        <button type="button" @click="addEmail()" class="text-xs font-bold text-accent-blue hover:underline flex items-center gap-1">
                            <i class="fas fa-plus-circle"></i> Add another email
                        </button>
                        <span class="text-[10px] text-text-dark/40">Max 20/day</span>
                    </div>

                    <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl transition-all shadow-md flex items-center justify-center gap-2 mt-4">
                        <i class="fas fa-paper-plane"></i> Send Invitation
                    </button>
                </form>
            </div>
        </div>

        {{-- Column 2: Share on WhatsApp Preview --}}
        <div class="bg-card-bg border border-card-border rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-text-main flex items-center gap-2 mb-1">
                    <i class="fab fa-whatsapp text-[#25D366]"></i> Share on WhatsApp
                </h3>
                <p class="text-xs text-text-dark/50 mb-5">Pre-filled message preview for WhatsApp</p>

                <div class="p-4 rounded-2xl bg-[#075e54]/10 border border-[#25D366]/20 text-xs text-text-main space-y-2 relative">
                    <div class="text-[11px] text-text-dark/80 leading-relaxed font-sans">
                        Hi! I found a great platform for teaching jobs. Join <strong>Vedanta Placement Agency</strong> using my referral link and get <strong>100 welcome points</strong>!
                    </div>
                    <div class="text-[11px] text-accent-blue font-mono font-bold break-all">
                        {{ url('/r/' . $user->referral_code) }}
                    </div>
                    <div class="text-[10px] font-bold uppercase tracking-wider text-text-dark/40">
                        Referral Code: <strong class="text-accent-yellow">{{ $user->referral_code }}</strong>
                    </div>
                </div>
            </div>

            <a href="https://api.whatsapp.com/send?text={{ $waText }}" target="_blank" class="w-full py-3 bg-[#25D366] hover:bg-[#20bd5a] text-white font-bold text-xs rounded-xl transition-all shadow-md flex items-center justify-center gap-2 mt-4">
                <i class="fab fa-whatsapp text-sm"></i> Share on WhatsApp
            </a>
        </div>

        {{-- Column 3: More Sharing Options --}}
        <div class="bg-card-bg border border-card-border rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-text-main flex items-center gap-2 mb-1">
                    <i class="fas fa-share-alt text-accent-blue"></i> More Options
                </h3>
                <p class="text-xs text-text-dark/50 mb-5">Share across other popular channels</p>

                <div class="space-y-2.5">
                    {{-- Copy Link Button --}}
                    <button type="button" @click="copyToClipboard('{{ url('/r/' . $user->referral_code) }}', 'link')" class="w-full p-3 bg-secondary-bg hover:bg-card-border/60 text-text-main rounded-xl text-xs font-semibold flex items-center justify-between border border-card-border transition-all">
                        <span class="flex items-center gap-2.5"><i class="fas fa-link text-indigo-400"></i> Copy Link</span>
                        <span class="text-[10px] font-bold text-text-dark/40"><i class="fas fa-arrow-right"></i></span>
                    </button>

                    {{-- Telegram --}}
                    <a href="{{ $tgUrl }}" target="_blank" class="w-full p-3 bg-secondary-bg hover:bg-card-border/60 text-text-main rounded-xl text-xs font-semibold flex items-center justify-between border border-card-border transition-all">
                        <span class="flex items-center gap-2.5"><i class="fab fa-telegram text-sky-400"></i> Share on Telegram</span>
                        <span class="text-[10px] font-bold text-text-dark/40"><i class="fas fa-arrow-right"></i></span>
                    </a>

                    {{-- Facebook --}}
                    @php
                        $fbUrl = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode(url('/r/' . $user->referral_code));
                    @endphp
                    <a href="{{ $fbUrl }}" target="_blank" class="w-full p-3 bg-secondary-bg hover:bg-card-border/60 text-text-main rounded-xl text-xs font-semibold flex items-center justify-between border border-card-border transition-all">
                        <span class="flex items-center gap-2.5"><i class="fab fa-facebook text-blue-500"></i> Share on Facebook</span>
                        <span class="text-[10px] font-bold text-text-dark/40"><i class="fas fa-arrow-right"></i></span>
                    </a>

                    {{-- LinkedIn --}}
                    @php
                        $liUrl = "https://www.linkedin.com/sharing/share-offsite/?url=" . urlencode(url('/r/' . $user->referral_code));
                    @endphp
                    <a href="{{ $liUrl }}" target="_blank" class="w-full p-3 bg-secondary-bg hover:bg-card-border/60 text-text-main rounded-xl text-xs font-semibold flex items-center justify-between border border-card-border transition-all">
                        <span class="flex items-center gap-2.5"><i class="fab fa-linkedin text-blue-400"></i> Share on LinkedIn</span>
                        <span class="text-[10px] font-bold text-text-dark/40"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
            </div>

            <button type="button" @click="shareNative()" class="w-full py-3 bg-secondary-bg hover:bg-card-border text-text-main font-bold text-xs rounded-xl transition-all border border-card-border flex items-center justify-center gap-2 mt-4">
                <i class="fas fa-ellipsis-h"></i> More Sharing Options
            </button>
        </div>
    </div>

    {{-- PROGRESS OVERVIEW & RECENT REFERRALS GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left: Referral Progress Overview --}}
        <div class="lg:col-span-4 bg-card-bg border border-card-border rounded-3xl p-6 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-text-main flex items-center gap-2">
                <i class="fas fa-tasks text-accent-blue"></i> Referral Progress Overview
            </h3>
            <p class="text-xs text-text-dark/50">Points awarded as your friend advances</p>

            <div class="space-y-3 pt-2">
                {{-- Milestone 1 --}}
                <div class="p-3 bg-secondary-bg/60 rounded-2xl border border-card-border flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs font-bold">1</div>
                        <div>
                            <div class="text-xs font-bold text-text-main">Registered</div>
                            <div class="text-[10px] text-text-dark/40">Reward: 50 Points</div>
                        </div>
                    </div>
                    <span class="text-xs font-black text-blue-400">+50 Pts</span>
                </div>

                {{-- Milestone 2 --}}
                <div class="p-3 bg-secondary-bg/60 rounded-2xl border border-card-border flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center text-xs font-bold">2</div>
                        <div>
                            <div class="text-xs font-bold text-text-main">Profile Completed</div>
                            <div class="text-[10px] text-text-dark/40">Reward: 100 Points</div>
                        </div>
                    </div>
                    <span class="text-xs font-black text-purple-400">+100 Pts</span>
                </div>

                {{-- Milestone 3 --}}
                <div class="p-3 bg-secondary-bg/60 rounded-2xl border border-card-border flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-xs font-bold">3</div>
                        <div>
                            <div class="text-xs font-bold text-text-main">Verified</div>
                            <div class="text-[10px] text-text-dark/40">Reward: 100 Points</div>
                        </div>
                    </div>
                    <span class="text-xs font-black text-cyan-400">+100 Pts</span>
                </div>

                {{-- Milestone 4 --}}
                <div class="p-3 bg-secondary-bg/60 rounded-2xl border border-card-border flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center text-xs font-bold">4</div>
                        <div>
                            <div class="text-xs font-bold text-text-main">Interview Scheduled</div>
                            <div class="text-[10px] text-text-dark/40">Reward: 150 Points</div>
                        </div>
                    </div>
                    <span class="text-xs font-black text-amber-400">+150 Pts</span>
                </div>

                {{-- Milestone 5 --}}
                <div class="p-3 bg-emerald-500/10 rounded-2xl border border-emerald-500/30 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-bold">5</div>
                        <div>
                            <div class="text-xs font-bold text-white">Successfully Joined</div>
                            <div class="text-[10px] text-emerald-400/70">Reward: 500 Points</div>
                        </div>
                    </div>
                    <span class="text-xs font-black text-emerald-400">+500 Pts</span>
                </div>
            </div>

            <div class="p-3 rounded-2xl bg-secondary-bg text-[11px] text-text-dark/50 text-center font-semibold">
                Total Potential: <strong class="text-text-main font-bold">900 Points (₹450)</strong> per friend
            </div>
        </div>

        {{-- Right: Recent Referrals List --}}
        <div class="lg:col-span-8 bg-card-bg border border-card-border rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-text-main flex items-center gap-2">
                            <i class="fas fa-users text-accent-blue"></i> Recent Referrals
                        </h3>
                        <p class="text-xs text-text-dark/50">Tracking your invited friends</p>
                    </div>
                    <span class="text-xs font-bold text-accent-blue">{{ $totalReferrals }} Friends Total</span>
                </div>

                @if($recentReferrals->isEmpty())
                    <div class="py-16 text-center text-text-dark/40 text-xs">
                        <i class="fas fa-user-friends text-4xl mb-3 text-text-dark/20"></i>
                        <p class="font-bold text-text-main">No referrals yet</p>
                        <p class="mt-1">Share your link or invite a friend via email above to get started!</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-card-border text-[10px] uppercase font-bold text-text-dark/40 tracking-wider">
                                    <th class="py-2.5 px-3">Candidate</th>
                                    <th class="py-2.5 px-3">Status</th>
                                    <th class="py-2.5 px-3">Progress</th>
                                    <th class="py-2.5 px-3 text-right">Reward</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-card-border/50">
                                @foreach($recentReferrals as $ref)
                                    @php
                                        $badge = $ref->stage_badge;
                                        $pct = $ref->progress_percent;
                                    @endphp
                                    <tr class="hover:bg-secondary-bg/50 transition-colors">
                                        <td class="py-3 px-3">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-full bg-accent-blue/15 text-accent-blue font-bold text-xs flex items-center justify-center shrink-0">
                                                    {{ strtoupper(substr($ref->referee?->name ?? 'F', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-text-main">{{ $ref->referee?->name ?? 'Friend' }}</div>
                                                    <div class="text-[10px] text-text-dark/40">{{ $ref->created_at->format('d M Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-3">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badge['class'] }}">
                                                <i class="fas {{ $badge['icon'] }}"></i> {{ $badge['label'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 w-40">
                                            <div class="flex items-center gap-2">
                                                <div class="w-full h-2 bg-secondary-bg rounded-full overflow-hidden border border-card-border">
                                                    <div class="h-full bg-gradient-to-r from-accent-blue to-emerald-400 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                                </div>
                                                <span class="text-[10px] font-bold text-text-dark/60">{{ $pct }}%</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-3 text-right">
                                            <div class="font-bold text-text-main">{{ number_format($ref->points_earned) }} Points</div>
                                            @if($ref->stage !== 'placed')
                                                <div class="text-[9px] text-text-dark/40">(900 max on joining)</div>
                                            @else
                                                <div class="text-[9px] text-emerald-400 font-bold">Completed</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- WALLET SUMMARY, TRANSACTIONS & SERVICE CHARGE REDEMPTION BOX --}}
    <div id="redeem" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left: Wallet Ledger & Summary --}}
        <div class="lg:col-span-7 bg-card-bg border border-card-border rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-text-main flex items-center gap-2">
                            <i class="fas fa-history text-accent-yellow"></i> Wallet History & Ledger
                        </h3>
                        <p class="text-xs text-text-dark/50">Immutable ledger of points earned and redeemed</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-accent-yellow/15 text-accent-yellow text-[10px] font-bold border border-accent-yellow/30">
                        100 Points = ₹50 Discount
                    </span>
                </div>

                {{-- Wallet Balances Mini Row --}}
                <div class="grid grid-cols-3 gap-3 p-3.5 bg-secondary-bg/60 rounded-2xl border border-card-border text-center text-xs">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-text-dark/40">Lifetime</span>
                        <div class="text-sm font-black text-text-main mt-0.5">{{ number_format($lifetimeEarned) }}</div>
                    </div>
                    <div class="border-x border-card-border">
                        <span class="text-[10px] uppercase font-bold text-text-dark/40">Available</span>
                        <div class="text-sm font-black text-emerald-400 mt-0.5">{{ number_format($availablePoints) }}</div>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-text-dark/40">Redeemed</span>
                        <div class="text-sm font-black text-rose-400 mt-0.5">{{ number_format($redeemedPoints) }}</div>
                    </div>
                </div>
            </div>

            {{-- Transactions Table --}}
            @if($recentTransactions->isEmpty())
                <div class="py-12 text-center text-text-dark/40 text-xs">
                    No transactions recorded yet.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentTransactions as $txn)
                        <div class="p-3 bg-secondary-bg/40 rounded-xl border border-card-border/60 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg {{ $txn->type === 'credit' ? 'bg-emerald-500/15 text-emerald-400' : 'bg-rose-500/15 text-rose-400' }} flex items-center justify-center text-xs shrink-0">
                                    <i class="fas {{ $txn->type === 'credit' ? 'fa-plus' : 'fa-minus' }}"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-text-main">{{ $txn->description }}</div>
                                    <div class="text-[10px] text-text-dark/40">{{ $txn->created_at->format('d M Y, h:i A') }}</div>
                                </div>
                            </div>
                            <div class="text-right font-bold {{ $txn->type === 'credit' ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $txn->type === 'credit' ? '+' : '-' }}{{ number_format($txn->points) }} pts
                                <div class="text-[10px] text-text-dark/50 font-normal">₹{{ number_format($txn->amount_equivalent, 2) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Right: HOW POINTS CAN BE USED (Service Charge Live Calculator) --}}
        <div class="lg:col-span-5 bg-card-bg border border-card-border rounded-3xl p-6 sm:p-8 shadow-sm space-y-6 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-text-main flex items-center gap-2 mb-1">
                    <i class="fas fa-calculator text-emerald-400"></i> How Points Can Be Used
                </h3>
                <p class="text-xs text-text-dark/50 mb-5">Apply reward discount on your Vedanta Placement service charge invoice.</p>

                {{-- Calculator Breakdown Card --}}
                <div class="p-5 rounded-2xl bg-secondary-bg/80 border border-card-border space-y-3.5 text-xs">
                    <div class="flex justify-between items-center text-text-dark/70">
                        <span>Original Service Charge:</span>
                        <span class="font-bold text-text-main">₹{{ number_format($sampleOriginal, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center text-text-dark/70">
                        <span>Max Discount ({{ $maxDiscountPercentage }}% Cap):</span>
                        <span class="font-bold text-text-main">₹{{ number_format($sampleMaxDiscount, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center text-emerald-400 font-bold border-t border-card-border/60 pt-2">
                        <span>Available Discount ({{ number_format($availablePoints) }} pts):</span>
                        <span>-₹{{ number_format($sampleAvailableDiscount, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center text-base font-black text-accent-blue border-t border-card-border/60 pt-2.5">
                        <span>Final Payable Amount:</span>
                        <span>₹{{ number_format($sampleFinalPayable, 2) }}</span>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-accent-blue/10 border border-accent-blue/20 text-[11px] text-accent-blue mt-4 font-semibold text-center">
                    You can save up to ₹{{ number_format($sampleAvailableDiscount, 2) }} on your eligible invoice!
                </div>
            </div>

            @if($activeInvoice && $availablePoints > 0)
                <form action="{{ route('candidate.referral.redeem') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $activeInvoice->id }}">
                    <input type="hidden" name="points" value="{{ min($availablePoints, $sampleMaxDiscount / $pointRate) }}">
                    <button type="submit" class="w-full py-3.5 bg-accent-blue hover:bg-accent-blue-hover text-white font-black text-xs rounded-xl transition-all shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-tag"></i> Use ₹{{ number_format($sampleAvailableDiscount, 2) }} Discount Now
                    </button>
                </form>
            @else
                <a href="{{ route('candidate.serviceCharge.show') }}" class="w-full py-3.5 bg-secondary-bg hover:bg-card-border text-text-main font-bold text-xs rounded-xl transition-all border border-card-border flex items-center justify-center gap-2 mt-4">
                    <i class="fas fa-file-invoice-dollar"></i> View My Service Charges
                </a>
            @endif
        </div>
    </div>

    {{-- EMAIL INVITATION PREVIEW & MILESTONES SHOWCASE --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Email Preview Card --}}
        <div class="lg:col-span-6 bg-card-bg border border-card-border rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-text-main flex items-center gap-2">
                <i class="fas fa-envelope-open-text text-purple-400"></i> Email Invitation Preview
            </h3>
            <p class="text-xs text-text-dark/50">This is what your friend receives in their inbox</p>

            <div class="p-6 rounded-2xl bg-white text-slate-800 border border-slate-200 shadow-md space-y-4 text-xs font-sans">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="font-bold text-slate-900 text-sm">VEDANTA PLACEMENT AGENCY</div>
                    <span class="text-[10px] text-slate-400 font-mono">Invitation</span>
                </div>
                <div class="space-y-2">
                    <p class="font-bold text-slate-900">You're Invited to Join Vedanta Placement Agency! 🎓</p>
                    <p class="text-slate-600 leading-relaxed text-[11px]">
                        Hello Priya, your friend <strong>{{ $user->name }}</strong> has invited you to join Vedanta Placement Agency. Create your teacher profile, explore relevant teaching opportunities and get connected with schools through our placement network.
                    </p>
                </div>

                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 flex items-center gap-3">
                    <div class="text-2xl">🎁</div>
                    <div>
                        <div class="text-[10px] uppercase font-bold text-emerald-700">Your Referral Benefit</div>
                        <div class="text-xs font-bold text-emerald-900">Register through this link and receive 100 Welcome Points!</div>
                    </div>
                </div>

                <div class="text-center pt-2">
                    <div class="inline-block px-5 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-sm">
                        Join Vedanta Now
                    </div>
                    <div class="text-[10px] text-slate-400 mt-2 font-mono">
                        Referral Code: <strong>{{ $user->referral_code }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Points Milestone Bonuses --}}
        <div class="lg:col-span-6 bg-card-bg border border-card-border rounded-3xl p-6 sm:p-8 shadow-sm space-y-4 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-text-main flex items-center gap-2">
                    <i class="fas fa-award text-accent-yellow"></i> Points Milestone Bonuses
                </h3>
                <p class="text-xs text-text-dark/50 mb-4">Earn massive bonus points as your referral portfolio grows</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    {{-- Tier 1: 5 Referrals --}}
                    <div class="p-4 rounded-2xl bg-secondary-bg/70 border border-card-border text-center relative overflow-hidden">
                        <div class="text-2xl mb-1">🥉</div>
                        <div class="text-lg font-black text-text-main">5</div>
                        <div class="text-[10px] uppercase font-bold text-text-dark/40">Successful Referrals</div>
                        <div class="mt-3 py-1.5 px-2.5 rounded-lg bg-emerald-500/15 text-emerald-400 font-bold text-xs">
                            +500 Points
                        </div>
                    </div>

                    {{-- Tier 2: 10 Referrals --}}
                    <div class="p-4 rounded-2xl bg-secondary-bg/70 border border-card-border text-center relative overflow-hidden">
                        <div class="text-2xl mb-1">🥈</div>
                        <div class="text-lg font-black text-text-main">10</div>
                        <div class="text-[10px] uppercase font-bold text-text-dark/40">Successful Referrals</div>
                        <div class="mt-3 py-1.5 px-2.5 rounded-lg bg-blue-500/15 text-blue-400 font-bold text-xs">
                            +1,500 Points
                        </div>
                    </div>

                    {{-- Tier 3: 25 Referrals --}}
                    <div class="p-4 rounded-2xl bg-secondary-bg/70 border border-card-border text-center relative overflow-hidden">
                        <div class="text-2xl mb-1">🥇</div>
                        <div class="text-lg font-black text-text-main">25</div>
                        <div class="text-[10px] uppercase font-bold text-text-dark/40">Successful Referrals</div>
                        <div class="mt-3 py-1.5 px-2.5 rounded-lg bg-purple-500/15 text-purple-400 font-bold text-xs">
                            +5,000 Points
                        </div>
                    </div>
                </div>
            </div>

            {{-- Terms & Conditions disclaimer --}}
            <div class="p-4 rounded-2xl bg-secondary-bg/40 border border-card-border text-[10px] text-text-dark/40 leading-relaxed">
                <strong class="text-text-dark/60 uppercase">Referral Program Terms:</strong> Reward Points are promotional credits issued by Vedanta Placement Agency. Points have no cash value and cannot be transferred, sold, or withdrawn. Referral rewards are subject to verification and may be cancelled in cases of duplicate or self-referrals. Reward Points can only be redeemed against eligible Vedanta service charges up to 30% discount cap.
            </div>
        </div>
    </div>
</div>
@endsection
