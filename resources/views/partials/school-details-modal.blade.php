<!-- School Details Locked / Registration Flow Modal (Reference Image 4) -->
<div id="schoolLockModal" class="fixed inset-0 z-[99999] hidden items-center justify-center p-3 sm:p-4 bg-slate-950/75 backdrop-blur-md opacity-0 transition-opacity duration-300" aria-modal="true" role="dialog">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-xl md:max-w-3xl w-full overflow-hidden relative transform scale-95 transition-transform duration-300 flex flex-col md:flex-row">
        
        <!-- Close Button -->
        <button id="closeSchoolLockModalBtn" aria-label="Close modal" class="absolute top-3.5 right-3.5 text-slate-400 hover:text-slate-700 bg-white/90 hover:bg-white rounded-full w-8 h-8 flex items-center justify-center shadow-md transition-all z-40 cursor-pointer">
            <i class="fas fa-times text-sm"></i>
        </button>

        <!-- Left Brand Banner (Reference Image 4) -->
        <div class="md:w-5/12 bg-gradient-to-br from-[#02132d] via-[#072459] to-[#010e24] p-6 sm:p-8 text-white flex flex-col justify-between relative overflow-hidden shrink-0">
            <!-- Decorative Glow & Vector -->
            <div class="absolute -top-10 -left-10 w-44 h-44 rounded-full bg-blue-500/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -right-10 w-48 h-48 rounded-full bg-amber-400/15 blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 text-center md:text-left">
                <!-- Vedanta Logo -->
                <div class="flex items-center gap-3 justify-center md:justify-start mb-6">
                    <img src="{{ asset('images/logo.png') }}" alt="Vedanta Logo" class="h-11 sm:h-12 w-auto object-contain bg-white/10 p-1.5 rounded-xl backdrop-blur-sm border border-white/15" onerror="this.src='/images/logo.png'">
                    <div>
                        <span class="block font-black text-base sm:text-lg tracking-tight text-white leading-tight">Vedanta</span>
                        <span class="block text-[10px] sm:text-xs font-semibold text-blue-200 tracking-wider uppercase">Placement Agency</span>
                    </div>
                </div>

                <div class="hidden md:block my-4">
                    <span class="text-[10px] font-bold text-amber-300 uppercase tracking-widest block mb-1">Empowering Educators</span>
                    <h4 class="text-lg font-extrabold text-white leading-snug">
                        Your Teaching Career Starts Here
                    </h4>
                </div>
            </div>

            <!-- School/Education Architectural Watermark Graphic -->
            <div class="relative z-10 my-4 py-2 text-center">
                <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-blue-300/80 shadow-inner">
                    <i class="fas fa-school text-3xl sm:text-4xl text-[#129aef]"></i>
                </div>
                <p class="text-xs sm:text-sm text-blue-100 font-medium italic mt-4 max-w-xs mx-auto leading-relaxed" style="font-family: Georgia, serif;">
                    “Unlock opportunities. Your next big teaching journey is just a login away.”
                </p>
            </div>

            <!-- Micro Trust Badges -->
            <div class="relative z-10 pt-4 border-t border-white/10 flex items-center justify-around text-[10px] sm:text-xs text-blue-200/90">
                <span class="flex items-center gap-1.5"><i class="fas fa-shield-alt text-emerald-400"></i> 100% Verified</span>
                <span class="flex items-center gap-1.5"><i class="fas fa-school text-amber-300"></i> Top Schools</span>
            </div>
        </div>

        <!-- Right Content Area -->
        <div class="md:w-7/12 p-6 sm:p-8 flex flex-col justify-between bg-white">
            
            <!-- State: Dynamic Content Container -->
            <div id="modalDynamicContent">
                
                @guest
                <!-- GUEST STATE (Reference Image 4) -->
                <div id="modalGuestView">
                    <!-- Lock Icon Badge -->
                    <div class="w-13 h-13 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-xl text-[#129aef] mb-4 shadow-sm">
                        <i class="fas fa-lock text-2xl"></i>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 mb-2 tracking-tight">
                        Login to View School Details
                    </h3>
                    
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-5">
                        Create a free account or login to view complete details including school name, exact location, contact information and application process.
                    </p>

                    <!-- Benefits Checklist (Image 4) -->
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 mb-6 space-y-2.5">
                        <div class="flex items-center gap-3 text-xs sm:text-sm font-semibold text-slate-800">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] shrink-0 font-black">✓</span>
                            <span>Get full school details</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs sm:text-sm font-semibold text-slate-800">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] shrink-0 font-black">✓</span>
                            <span>View exact location</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs sm:text-sm font-semibold text-slate-800">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] shrink-0 font-black">✓</span>
                            <span>Apply to this job</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button type="button" id="triggerQuickLoginBtn" class="w-full py-3.5 px-6 bg-[#129aef] hover:bg-[#0d85d4] text-white font-extrabold rounded-xl shadow-lg shadow-[#129aef]/25 hover:shadow-[#129aef]/35 transition-all text-sm flex items-center justify-center gap-2 cursor-pointer">
                            <span>Login to Continue</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>

                        <a href="{{ route('candidate.register') }}" class="w-full py-3.5 px-6 bg-white hover:bg-slate-50 text-[#129aef] border-2 border-[#129aef] font-extrabold rounded-xl transition-all text-sm flex items-center justify-center gap-2 text-center">
                            <span>Create a Free Account</span>
                        </a>
                    </div>

                    <div class="text-center mt-4">
                        <span class="text-xs text-slate-500">Already have an account? </span>
                        <a href="{{ route('login') }}" class="text-xs font-bold text-[#129aef] hover:underline">Login here</a>
                    </div>
                </div>

                <!-- QUICK INLINE LOGIN FORM (Hidden by default, slides in smoothly) -->
                <div id="modalQuickLoginForm" class="hidden">
                    <div class="flex items-center gap-2 mb-4">
                        <button type="button" id="backToGuestViewBtn" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg transition-colors">
                            <i class="fas fa-arrow-left text-sm"></i>
                        </button>
                        <h4 class="text-lg font-extrabold text-slate-900">Sign In to Your Account</h4>
                    </div>

                    <div id="quickLoginAlert" class="hidden mb-3 p-3 rounded-xl text-xs font-medium bg-rose-50 border border-rose-200 text-rose-700"></div>

                    <form id="ajaxLoginForm" action="{{ route('login.post') }}" method="POST" class="space-y-3.5">
                        @csrf
                        <input type="hidden" name="return_to" id="modalReturnTo" value="{{ url()->current() }}">

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                            <input type="email" name="email" id="modalLoginEmail" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:bg-white transition-all" placeholder="Enter your email">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-slate-700">Password</label>
                                <a href="{{ route('password.request') }}" class="text-[11px] text-[#129aef] hover:underline">Forgot password?</a>
                            </div>
                            <input type="password" name="password" id="modalLoginPassword" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-[#129aef] focus:bg-white transition-all" placeholder="Enter password">
                        </div>

                        <button type="submit" id="submitQuickLoginBtn" class="w-full py-3 bg-[#129aef] hover:bg-[#0d85d4] text-white font-extrabold rounded-xl shadow-md transition-all text-sm flex items-center justify-center gap-2 cursor-pointer mt-2">
                            <span>Sign In</span>
                            <i class="fas fa-sign-in-alt"></i>
                        </button>
                    </form>
                </div>
                @endguest

                @auth
                    @if(auth()->user()->role === 'candidate')
                    <!-- LOGGED IN CANDIDATE WITH PENDING REGISTRATION STATE -->
                    <div id="modalPendingView">
                        <div class="w-13 h-13 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-xl text-amber-600 mb-4 shadow-sm">
                            <i class="fas fa-shield-alt text-2xl"></i>
                        </div>

                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-bold mb-2">
                            <i class="fas fa-clock text-amber-600"></i> Registration Incomplete
                        </div>

                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 mb-2 tracking-tight">
                            Complete Registration to Unlock School Details
                        </h3>
                        
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-5">
                            You are logged in as <span class="font-bold text-slate-800">{{ auth()->user()->name }}</span>. However, school name, exact location and contact details stay protected until your candidate registration is completed.
                        </p>

                        <!-- What You Unlock Checklist -->
                        <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4 mb-6 space-y-2.5">
                            <div class="flex items-center gap-3 text-xs sm:text-sm font-semibold text-amber-950">
                                <span class="w-5 h-5 rounded-full bg-amber-200 text-amber-800 flex items-center justify-center text-[10px] shrink-0 font-black">✓</span>
                                <span>Complete profile details & verification</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs sm:text-sm font-semibold text-amber-950">
                                <span class="w-5 h-5 rounded-full bg-amber-200 text-amber-800 flex items-center justify-center text-[10px] shrink-0 font-black">✓</span>
                                <span>Unlock exact school names & contact coordinates</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs sm:text-sm font-semibold text-amber-950">
                                <span class="w-5 h-5 rounded-full bg-amber-200 text-amber-800 flex items-center justify-center text-[10px] shrink-0 font-black">✓</span>
                                <span>Direct application & interview coordination</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('candidate.wizard') }}" class="w-full py-3.5 px-6 bg-[#129aef] hover:bg-[#0d85d4] text-white font-extrabold rounded-xl shadow-lg shadow-[#129aef]/25 hover:shadow-[#129aef]/35 transition-all text-sm flex items-center justify-center gap-2 text-center cursor-pointer">
                            <span>Complete Registration Now</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>

                        <p class="text-center text-[11px] text-slate-500 mt-3 font-medium">
                            Registration charges are applicable as per selected plan.
                        </p>
                    </div>
                    @endif
                @endauth

            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('schoolLockModal');
    const closeBtn = document.getElementById('closeSchoolLockModalBtn');
    const triggerLoginBtn = document.getElementById('triggerQuickLoginBtn');
    const backToGuestBtn = document.getElementById('backToGuestViewBtn');
    const guestView = document.getElementById('modalGuestView');
    const loginFormView = document.getElementById('modalQuickLoginForm');
    const ajaxForm = document.getElementById('ajaxLoginForm');
    const alertBox = document.getElementById('quickLoginAlert');

    window.openSchoolLockModal = function() {
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.bg-white')?.classList.remove('scale-95');
            modal.querySelector('.bg-white')?.classList.add('scale-100');
        }, 10);
    };

    window.closeSchoolLockModal = function() {
        if (!modal) return;
        modal.classList.add('opacity-0');
        modal.querySelector('.bg-white')?.classList.remove('scale-100');
        modal.querySelector('.bg-white')?.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            // reset view to default
            if (guestView && loginFormView) {
                guestView.classList.remove('hidden');
                loginFormView.classList.add('hidden');
            }
        }, 300);
    };

    if (closeBtn) closeBtn.addEventListener('click', closeSchoolLockModal);

    modal?.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeSchoolLockModal();
        }
    });

    if (triggerLoginBtn && guestView && loginFormView) {
        triggerLoginBtn.addEventListener('click', function() {
            guestView.classList.add('hidden');
            loginFormView.classList.remove('hidden');
            document.getElementById('modalLoginEmail')?.focus();
        });
    }

    if (backToGuestBtn && guestView && loginFormView) {
        backToGuestBtn.addEventListener('click', function() {
            loginFormView.classList.add('hidden');
            guestView.classList.remove('hidden');
        });
    }

    // AJAX Login Handler
    if (ajaxForm) {
        ajaxForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitQuickLoginBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Signing In...';
            if (alertBox) alertBox.classList.add('hidden');

            try {
                const formData = new FormData(ajaxForm);
                const res = await fetch(ajaxForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();

                if (res.ok && data.success) {
                    // Successful login
                    window.location.reload();
                } else {
                    if (alertBox) {
                        alertBox.textContent = data.message || 'Login failed. Please check your email and password.';
                        alertBox.classList.remove('hidden');
                    }
                }
            } catch (err) {
                if (alertBox) {
                    alertBox.textContent = 'A connection error occurred. Please try again or refresh.';
                    alertBox.classList.remove('hidden');
                }
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }

    // Attach click listeners to all elements with class 'trigger-school-lock-modal'
    document.querySelectorAll('.trigger-school-lock-modal').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            openSchoolLockModal();
        });
    });
});
</script>
