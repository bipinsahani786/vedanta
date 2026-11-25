@php
    $variant = $variant ?? 'banner';
@endphp

@if(!empty($whatsappChannelUrl))
    @php
        $waCardId = 'wa-channel-card-' . $variant . '-' . substr(md5($whatsappChannelUrl), 0, 8);
        $waStorageKey = 'vpa_wa_channel_closed_' . $variant . '_' . substr(md5($whatsappChannelUrl), 0, 8);
        $waCloseBtnId = $waCardId . '-close';
    @endphp

    @if($variant === 'floating')
        <div id="{{ $waCardId }}"
             role="complementary"
             aria-label="Vedanta Job Alerts WhatsApp Channel"
             class="fixed bottom-4 left-4 z-[990] w-[calc(100vw-5.5rem)] max-w-[325px]">
            <div class="rounded-2xl overflow-hidden bg-white border border-slate-200/90 shadow-[0_18px_45px_rgba(3,27,78,0.22)]">
                {{-- Navy Header matching Vedanta brand --}}
                <div class="bg-gradient-to-r from-[#031b4e] via-[#072a6e] to-[#0a368a] px-4 py-3 flex items-center justify-between gap-2.5 text-white">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center shrink-0">
                            <i class="fas fa-bell text-amber-300 text-xs"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-[13px] font-extrabold text-white leading-tight truncate">Vedanta Job Alerts</div>
                            <div class="text-[10.5px] text-blue-100/85 font-medium truncate">Verified School Vacancies • PAN-India</div>
                        </div>
                    </div>
                    <button type="button"
                            id="{{ $waCloseBtnId }}"
                            aria-label="Dismiss WhatsApp Channel card"
                            title="Close"
                            class="w-7 h-7 rounded-lg text-white/75 hover:text-white hover:bg-white/15 flex items-center justify-center text-xs transition-colors shrink-0 cursor-pointer">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Body Channel Action Card --}}
                <div class="p-3 bg-white">
                    <a href="{{ $whatsappChannelUrl }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="flex items-center gap-3 p-3 rounded-xl bg-emerald-50/80 hover:bg-emerald-100/75 border border-emerald-200/90 hover:border-emerald-300 transition-all group no-underline shadow-xs">
                        <div class="w-10 h-10 rounded-full bg-[#25D366] text-white flex items-center justify-center text-xl shrink-0 shadow-sm">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <span class="text-[13px] font-extrabold text-[#040e2d] leading-tight">WhatsApp Channel</span>
                                <i class="fas fa-check-circle text-[#16a34a] text-[11px]" title="Official Channel"></i>
                            </div>
                            <p class="text-[11px] font-medium text-slate-600 leading-snug m-0">
                                Join our WhatsApp Channel to get daily vacancies.
                            </p>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <span class="px-2.5 py-1 rounded-lg bg-[#16a34a] group-hover:bg-[#15803d] text-white font-bold text-[11px] transition-colors shadow-xs">Join</span>
                            <i class="fas fa-chevron-right text-[#16a34a] text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
                        </div>
                    </a>
                </div>

                {{-- Subtle Trust Strip --}}
                <div class="bg-slate-50/90 border-t border-slate-100 px-3.5 py-1.5 flex items-center justify-center gap-1.5 text-[10.5px] font-medium text-slate-500">
                    <i class="fas fa-lock text-amber-500 text-[9px]"></i>
                    <span>Official Channel • No Spam • Daily Updates</span>
                </div>
            </div>
        </div>
    @elseif($variant === 'footer')
        <div id="{{ $waCardId }}"
             class="mt-6 mb-6 bg-white/[0.06] border border-emerald-400/30 rounded-2xl px-4 py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 relative">
            <div class="flex items-center gap-3 pr-7 sm:pr-0">
                <div class="w-10 h-10 rounded-full bg-[#25D366] text-white flex items-center justify-center text-lg shrink-0 shadow-sm">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <div class="flex items-center gap-1.5 mb-0.5">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-300">WhatsApp Channel</span>
                        <i class="fas fa-check-circle text-emerald-400 text-[10px]"></i>
                    </div>
                    <p class="text-xs sm:text-sm font-semibold text-white m-0 leading-snug">
                        Join our WhatsApp Channel to get daily vacancies.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ $whatsappChannelUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#25D366] hover:bg-[#1fb857] text-[#040e2d] font-extrabold text-xs transition-colors shadow-sm no-underline">
                    <i class="fab fa-whatsapp text-sm"></i>
                    <span>Join Channel</span>
                    <i class="fas fa-chevron-right text-[10px] opacity-80"></i>
                </a>
                <button type="button"
                        id="{{ $waCloseBtnId }}"
                        aria-label="Dismiss WhatsApp Channel banner"
                        title="Close"
                        class="w-7 h-7 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center text-xs transition-colors cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @elseif($variant === 'banner-dark')
        <div id="{{ $waCardId }}"
             class="max-w-4xl mx-auto mt-8 bg-[#06245e]/90 border border-emerald-400/35 rounded-2xl px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-lg relative">
            <div class="flex items-center gap-3.5 pr-7 sm:pr-0">
                <div class="w-11 h-11 rounded-full bg-[#25D366] text-white flex items-center justify-center text-xl shrink-0 shadow-sm">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <div class="flex items-center gap-1.5 mb-0.5">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-300">Vedanta Job Alerts • WhatsApp Channel</span>
                        <i class="fas fa-check-circle text-emerald-400 text-[11px]"></i>
                    </div>
                    <p class="text-xs sm:text-sm font-bold text-white m-0 leading-snug">
                        Join our WhatsApp Channel to get daily vacancies.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ $whatsappChannelUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#25D366] hover:bg-[#1fb857] text-[#040e2d] font-extrabold text-xs transition-colors shadow-sm no-underline">
                    <i class="fab fa-whatsapp text-sm"></i>
                    <span>Join Channel</span>
                    <i class="fas fa-chevron-right text-[10px] opacity-80"></i>
                </a>
                <button type="button"
                        id="{{ $waCloseBtnId }}"
                        aria-label="Dismiss WhatsApp Channel banner"
                        title="Close"
                        class="w-7 h-7 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center text-xs transition-colors cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @else
        {{-- Default light banner for job listings pages --}}
        <div id="{{ $waCardId }}"
             class="mt-8 bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-sm relative">
            <div class="bg-[#031b4e] px-4 sm:px-5 py-2 flex items-center justify-between gap-2 text-white">
                <div class="flex items-center gap-2 text-xs font-bold">
                    <i class="fas fa-bell text-amber-300 text-[11px]"></i>
                    <span>Vedanta Job Alerts</span>
                    <span class="hidden sm:inline text-blue-200/80 font-normal">• Official Channel • No Spam</span>
                </div>
                <button type="button"
                        id="{{ $waCloseBtnId }}"
                        aria-label="Dismiss WhatsApp Channel banner"
                        title="Close"
                        class="w-6 h-6 rounded-md text-white/75 hover:text-white hover:bg-white/15 flex items-center justify-center text-xs transition-colors cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-3.5 sm:p-4 bg-white">
                <a href="{{ $whatsappChannelUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 p-3.5 rounded-xl bg-emerald-50/80 hover:bg-emerald-100/70 border border-emerald-200/90 transition-all group no-underline">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-full bg-[#25D366] text-white flex items-center justify-center text-xl shrink-0 shadow-sm">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <span class="text-sm font-extrabold text-[#040e2d]">WhatsApp Channel</span>
                                <i class="fas fa-check-circle text-[#16a34a] text-xs" title="Official Channel"></i>
                            </div>
                            <p class="text-xs sm:text-sm font-medium text-slate-600 m-0 leading-snug">
                                Join our WhatsApp Channel to get daily vacancies.
                            </p>
                        </div>
                    </div>
                    <span class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-[#16a34a] group-hover:bg-[#15803d] text-white font-bold text-xs transition-colors shadow-sm self-start sm:self-center shrink-0">
                        <i class="fab fa-whatsapp text-sm"></i>
                        <span>Join Channel</span>
                        <i class="fas fa-chevron-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
                    </span>
                </a>
            </div>
        </div>
    @endif

    <script>
        (function () {
            var card = document.getElementById(@json($waCardId));
            var closeBtn = document.getElementById(@json($waCloseBtnId));
            var key = @json($waStorageKey);
            if (!card) return;
            try {
                if (localStorage.getItem(key) === '1') {
                    card.style.display = 'none';
                }
            } catch (e) {}
            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    card.style.display = 'none';
                    try {
                        localStorage.setItem(key, '1');
                    } catch (e) {}
                });
            }
        })();
    </script>
@endif
