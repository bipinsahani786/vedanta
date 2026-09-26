<!-- Modern Job Share Modal (Matches Candidate Referral & Modern Dashboard Aesthetic) -->
<div id="jobShareModal" class="fixed inset-0 z-[99999] hidden items-center justify-center p-3 sm:p-4 bg-slate-950/75 backdrop-blur-md opacity-0 transition-opacity duration-200" aria-modal="true" role="dialog">
    <div id="jobShareModalContainer" class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-lg w-full overflow-hidden relative transform scale-95 transition-all duration-200">
        
        <!-- Top Accent Gradient Line -->
        <div class="h-1.5 w-full bg-gradient-to-r from-[#129aef] via-[#25D366] to-[#ffb800]"></div>

        <div class="p-6 sm:p-7">
            <!-- Header & Close Button -->
            <div class="flex items-start justify-between gap-3 mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#129aef] border border-blue-100 flex items-center justify-center text-xl shrink-0 shadow-sm">
                        <i class="fas fa-share-nodes"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-extrabold uppercase tracking-wider text-[#129aef]">Vedanta Placement Agency</div>
                        <h3 class="text-lg sm:text-xl font-black text-slate-900 leading-tight">Share Job Opportunity</h3>
                    </div>
                </div>

                <button type="button" onclick="closeJobShareModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition-colors cursor-pointer shrink-0" title="Close">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Job Snippet Card Preview -->
            <div class="p-4 rounded-2xl bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/20 border border-slate-200/80 mb-5 relative overflow-hidden">
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span id="shareModalJobCode" class="px-2.5 py-0.5 rounded-md bg-blue-100 text-[#129aef] font-mono font-bold text-xs tracking-wider">
                        #JOB-0000
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold text-emerald-700 bg-emerald-100/80 px-2.5 py-0.5 rounded-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Actively Hiring
                    </span>
                </div>

                <h4 id="shareModalJobTitle" class="text-sm sm:text-base font-black text-slate-900 leading-snug line-clamp-2 mb-2.5">
                    Teaching Vacancy
                </h4>

                <div class="flex flex-wrap items-center gap-y-1.5 gap-x-3 text-xs text-slate-600 font-medium">
                    <span class="flex items-center gap-1 text-slate-700">
                        <i class="fas fa-map-marker-alt text-rose-500 text-[11px]"></i>
                        <span id="shareModalJobLocation">Location</span>
                    </span>
                    <span class="text-slate-300">•</span>
                    <span class="flex items-center gap-1 text-emerald-700 font-bold">
                        <i class="fas fa-money-bill-wave text-[11px]"></i>
                        <span id="shareModalJobSalary">Salary</span>
                    </span>
                    <span id="shareModalJobCategoryWrapper" class="hidden sm:inline-flex items-center gap-1">
                        <span class="text-slate-300">•</span>
                        <span id="shareModalJobCategory" class="px-2 py-0.5 rounded bg-blue-50 text-[#129aef] font-bold text-[10px]"></span>
                    </span>
                </div>
            </div>

            <!-- Share Channels Heading -->
            <div class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider mb-2.5 flex items-center justify-between">
                <span>Share via Channels</span>
                <span class="text-[10px] font-medium normal-case text-slate-400">1-click direct sharing</span>
            </div>

            <!-- Primary Action: WhatsApp (Featured) -->
            <a id="shareModalWaBtn" href="#" target="_blank" style="background-color: #25D366 !important; color: #ffffff !important;" class="w-full py-3 px-4 rounded-2xl bg-[#25d366] hover:bg-[#20bd5a] text-white font-extrabold text-sm transition-all shadow-md hover:shadow-lg hover:shadow-[#25D366]/30 flex items-center justify-center gap-2.5 mb-3 group active:scale-[0.99]">
                <i class="fab fa-whatsapp text-lg group-hover:scale-110 transition-transform text-white" style="color: #ffffff !important;"></i>
                <span class="text-white" style="color: #ffffff !important;">Share on WhatsApp</span>
                <span class="ml-1 text-[10px] uppercase font-black bg-white/20 px-2 py-0.5 rounded-full text-white" style="color: #ffffff !important;">Instant</span>
            </a>

            <!-- Other Social Channels Grid (Telegram, LinkedIn, Facebook, More Apps) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-5">
                <!-- Telegram -->
                <a id="shareModalTgBtn" href="#" target="_blank" style="background-color: #0088cc !important; color: #ffffff !important;" class="py-2.5 px-3 rounded-xl bg-[#0088cc] hover:bg-[#0077b5] text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all shadow-sm hover:shadow hover:-translate-y-0.5 active:translate-y-0">
                    <i class="fab fa-telegram-plane text-sm text-white" style="color: #ffffff !important;"></i>
                    <span class="text-white" style="color: #ffffff !important;">Telegram</span>
                </a>

                <!-- LinkedIn -->
                <a id="shareModalLiBtn" href="#" target="_blank" style="background-color: #0a66c2 !important; color: #ffffff !important;" class="py-2.5 px-3 rounded-xl bg-[#0a66c2] hover:bg-[#084e96] text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all shadow-sm hover:shadow hover:-translate-y-0.5 active:translate-y-0">
                    <i class="fab fa-linkedin text-sm text-white" style="color: #ffffff !important;"></i>
                    <span class="text-white" style="color: #ffffff !important;">LinkedIn</span>
                </a>

                <!-- Facebook -->
                <a id="shareModalFbBtn" href="#" target="_blank" style="background-color: #1877f2 !important; color: #ffffff !important;" class="py-2.5 px-3 rounded-xl bg-[#1877f2] hover:bg-[#1465cf] text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all shadow-sm hover:shadow hover:-translate-y-0.5 active:translate-y-0">
                    <i class="fab fa-facebook text-sm text-white" style="color: #ffffff !important;"></i>
                    <span class="text-white" style="color: #ffffff !important;">Facebook</span>
                </a>

                <!-- Native Device Share / More Apps -->
                <button type="button" id="shareModalNativeBtn" onclick="triggerNativeShare()" style="background-color: #1e293b !important; color: #ffffff !important;" class="py-2.5 px-3 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all shadow-sm hover:shadow hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                    <i class="fas fa-ellipsis-h text-xs text-white" style="color: #ffffff !important;"></i>
                    <span class="text-white" style="color: #ffffff !important;">More Apps</span>
                </button>
            </div>

            <!-- Copy Link Box -->
            <div class="mb-4">
                <label class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">
                    Job Link
                </label>
                <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-2xl p-1.5 focus-within:border-[#129aef] focus-within:ring-2 focus-within:ring-[#129aef]/20 transition-all">
                    <input type="text" id="shareModalLinkInput" readonly class="bg-transparent border-0 text-slate-700 text-xs font-mono font-medium px-2.5 w-full focus:outline-none select-all truncate" value="">
                    <button type="button" id="shareModalCopyBtn" onclick="copyJobShareLink()" style="background-color: #129aef !important; color: #ffffff !important;" class="px-4 py-2 rounded-xl bg-[#129aef] hover:bg-[#0d85d4] text-white font-bold text-xs transition-all shadow-sm flex items-center gap-1.5 shrink-0 cursor-pointer">
                        <i class="far fa-copy text-xs text-white" style="color: #ffffff !important;"></i>
                        <span class="text-white" style="color: #ffffff !important;">Copy Link</span>
                    </button>
                </div>
            </div>

            <!-- Copy Formatted Text Option -->
            <button type="button" id="shareModalCopyTextBtn" onclick="copyJobFullText()" class="w-full py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors flex items-center justify-center gap-2 cursor-pointer border border-slate-200/60">
                <i class="fas fa-align-left text-slate-500 text-xs"></i>
                <span>Copy Full Job Details Message</span>
            </button>
        </div>

        <!-- Safe Footer Guarantee -->
        <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
            <span class="flex items-center gap-1.5">
                <i class="fas fa-shield-alt text-emerald-500"></i>
                <span>Verified by Vedanta Placement Agency</span>
            </span>
            <span class="font-semibold text-slate-400">Patna, Bihar</span>
        </div>
    </div>
</div>

<!-- Floating Toast Notification -->
<div id="jobShareToast" class="fixed bottom-6 right-6 z-[100000] hidden bg-slate-950 text-white px-4 py-3 rounded-2xl shadow-2xl border border-white/10 items-center gap-2.5 text-xs font-bold transition-all duration-300 pointer-events-none transform translate-y-2 opacity-0">
    <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">
        <i class="fas fa-check"></i>
    </div>
    <span id="jobShareToastMsg">Job link copied to clipboard!</span>
</div>

<script>
    (function() {
        let currentJobShareData = null;
        let toastTimeout = null;

        function showJobShareToast(message) {
            const toast = document.getElementById('jobShareToast');
            const msgEl = document.getElementById('jobShareToastMsg');
            if (!toast || !msgEl) return;

            msgEl.textContent = message;
            toast.classList.remove('hidden');
            
            // Trigger animation
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            });

            if (toastTimeout) clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 300);
            }, 2500);
        }

        function buildJobShareMessage(data) {
            let msg = `*Teacher Job Vacancy - Vedanta Placement Agency*\n\n`;
            msg += `*Role:* ${data.title || 'Teaching Faculty'} (${data.code || ''})\n`;
            if (data.location) msg += `*Location:* ${data.location}\n`;
            if (data.salary && data.salary !== 'Not disclosed') msg += `*Salary:* ${data.salary}\n`;
            if (data.category) msg += `*Category:* ${data.category}\n`;
            if (data.subject) msg += `*Subject:* ${data.subject}\n`;
            msg += `\n*View Details & Apply Here:*\n${data.url}\n\n`;
            msg += `_Vedanta Placement Agency - India's Premier Educational Recruitment Agency_`;
            return msg;
        }

        window.openJobShare = function(data) {
            currentJobShareData = data;
            
            // Populate modal fields
            const codeEl = document.getElementById('shareModalJobCode');
            const titleEl = document.getElementById('shareModalJobTitle');
            const locEl = document.getElementById('shareModalJobLocation');
            const salEl = document.getElementById('shareModalJobSalary');
            const catWrapper = document.getElementById('shareModalJobCategoryWrapper');
            const catEl = document.getElementById('shareModalJobCategory');
            const linkInput = document.getElementById('shareModalLinkInput');
            const waBtn = document.getElementById('shareModalWaBtn');
            const tgBtn = document.getElementById('shareModalTgBtn');
            const liBtn = document.getElementById('shareModalLiBtn');
            const fbBtn = document.getElementById('shareModalFbBtn');

            if (codeEl) codeEl.textContent = data.code || '#JOB';
            if (titleEl) titleEl.textContent = data.title || 'Teaching Opportunity';
            if (locEl) locEl.textContent = data.location || 'India';
            if (salEl) salEl.textContent = data.salary || 'Best in Industry';
            
            if (catWrapper && catEl) {
                if (data.category || data.subject) {
                    catEl.textContent = data.category || data.subject;
                    catWrapper.classList.remove('hidden');
                } else {
                    catWrapper.classList.add('hidden');
                }
            }

            if (linkInput) linkInput.value = data.url;

            // Generate Pre-filled share links
            const shareText = buildJobShareMessage(data);
            const encodedText = encodeURIComponent(shareText);
            const encodedUrl = encodeURIComponent(data.url);
            const encodedTitle = encodeURIComponent(`${data.title} (${data.code}) - Vedanta Placement Agency`);

            if (waBtn) {
                waBtn.href = `https://api.whatsapp.com/send?text=${encodedText}`;
            }
            if (tgBtn) {
                tgBtn.href = `https://t.me/share/url?url=${encodedUrl}&text=${encodeURIComponent('Teaching Vacancy: ' + data.title + ' (' + data.code + ')')}`;
            }
            if (liBtn) {
                liBtn.href = `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`;
            }
            if (fbBtn) {
                fbBtn.href = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
            }

            // Reset copy button states
            const copyBtn = document.getElementById('shareModalCopyBtn');
            if (copyBtn) {
                copyBtn.className = 'px-4 py-2 rounded-xl bg-[#129aef] hover:bg-[#0d85d4] text-white font-bold text-xs transition-all shadow-sm flex items-center gap-1.5 shrink-0 cursor-pointer';
                copyBtn.style.backgroundColor = '#129aef';
                copyBtn.style.color = '#ffffff';
                copyBtn.innerHTML = '<i class="far fa-copy text-xs text-white" style="color: #ffffff !important;"></i> <span class="text-white" style="color: #ffffff !important;">Copy Link</span>';
            }

            const copyTextBtn = document.getElementById('shareModalCopyTextBtn');
            if (copyTextBtn) {
                copyTextBtn.className = 'w-full py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors flex items-center justify-center gap-2 cursor-pointer border border-slate-200/60';
                copyTextBtn.innerHTML = '<i class="fas fa-align-left text-slate-500 text-xs"></i> <span>Copy Full Job Details Message</span>';
            }

            // Show Modal with Animation
            const modal = document.getElementById('jobShareModal');
            const container = document.getElementById('jobShareModalContainer');
            if (modal && container) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                
                requestAnimationFrame(() => {
                    modal.classList.remove('opacity-0');
                    modal.classList.add('opacity-100');
                    container.classList.remove('scale-95');
                    container.classList.add('scale-100');
                });
            }
        };

        window.closeJobShareModal = function() {
            const modal = document.getElementById('jobShareModal');
            const container = document.getElementById('jobShareModalContainer');
            if (!modal || !container) return;

            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            container.classList.remove('scale-100');
            container.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 200);
        };

        function copySafe(text, callback) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(callback).catch(() => {
                    fallbackCopySafe(text, callback);
                });
            } else {
                fallbackCopySafe(text, callback);
            }
        }

        function fallbackCopySafe(text, callback) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                if (callback) callback();
            } catch (e) {
                console.error('Fallback copy failed', e);
            }
            document.body.removeChild(textArea);
        }

        window.copyJobShareLink = function() {
            if (!currentJobShareData || !currentJobShareData.url) return;
            copySafe(currentJobShareData.url, () => {
                const copyBtn = document.getElementById('shareModalCopyBtn');
                if (copyBtn) {
                    copyBtn.className = 'px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all shadow-sm flex items-center gap-1.5 shrink-0 cursor-pointer';
                    copyBtn.style.backgroundColor = '#059669';
                    copyBtn.style.color = '#ffffff';
                    copyBtn.innerHTML = '<i class="fas fa-check text-xs text-white" style="color: #ffffff !important;"></i> <span class="text-white" style="color: #ffffff !important;">Copied!</span>';
                }
                showJobShareToast('Job link copied to clipboard!');
            });
        };

        window.copyJobFullText = function() {
            if (!currentJobShareData) return;
            const fullText = buildJobShareMessage(currentJobShareData);
            copySafe(fullText, () => {
                const copyTextBtn = document.getElementById('shareModalCopyTextBtn');
                if (copyTextBtn) {
                    copyTextBtn.className = 'w-full py-2.5 px-3 rounded-xl bg-emerald-100 text-emerald-800 font-bold text-xs transition-colors flex items-center justify-center gap-2 cursor-pointer border border-emerald-300';
                    copyTextBtn.innerHTML = '<i class="fas fa-check text-emerald-600 text-xs"></i> <span>Message Copied!</span>';
                }
                showJobShareToast('Job details message copied! Ready to paste.');
            });
        };

        window.triggerNativeShare = function() {
            if (!currentJobShareData) return;
            const shareText = buildJobShareMessage(currentJobShareData);
            if (navigator.share) {
                navigator.share({
                    title: `${currentJobShareData.title} (${currentJobShareData.code})`,
                    text: shareText,
                    url: currentJobShareData.url
                }).catch(() => {});
            } else {
                // Fallback on desktop or unsupported browser
                window.copyJobFullText();
            }
        };

        // Close on backdrop click
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('jobShareModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeJobShareModal();
                    }
                });
            }

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                    closeJobShareModal();
                }
            });
        });
    })();
</script>
