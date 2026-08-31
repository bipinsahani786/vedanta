<!-- Role Selection Auth Choice Modal -->
<div id="authChoiceModal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4 sm:p-6 overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="authModalTitle">
    <!-- Backdrop with blur -->
    <div id="authChoiceModalBackdrop" class="fixed inset-0 bg-[#040e2d]/75 backdrop-blur-md transition-opacity duration-300 opacity-0 cursor-pointer" onclick="closeAuthChoiceModal()"></div>

    <!-- Modal Dialog Box -->
    <div id="authChoiceModalCard" class="relative z-10 w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 md:p-10 transform scale-95 opacity-0 transition-all duration-300 ease-out my-auto">
        
        <!-- Close Button -->
        <button type="button" onclick="closeAuthChoiceModal()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-all focus:outline-none focus:ring-2 focus:ring-slate-300" aria-label="Close modal">
            <i class="fas fa-times text-sm"></i>
        </button>

        <!-- Modal Header -->
        <div class="text-center mb-7 sm:mb-9">
            <!-- Badge for Login -->
            <div id="authModalBadge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs sm:text-sm font-semibold text-[#129aef] bg-blue-50/80 mb-2">
                <span>Welcome Back! 👋</span>
            </div>

            <!-- Title -->
            <h2 id="authModalTitle" class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Log in to your account
            </h2>

            <!-- Subtitle -->
            <p id="authModalSubtitle" class="mt-1.5 text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                Choose how you want to access Vedanta
            </p>
        </div>

        <!-- 2 Option Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            
            <!-- Card 1: Job Seeker -->
            <div class="group relative rounded-2xl border border-slate-200/90 bg-white p-6 sm:p-7 flex flex-col items-center text-center hover:border-[#129aef]/50 hover:shadow-xl hover:shadow-[#129aef]/10 transition-all duration-300">
                <!-- Icon Circle -->
                <div id="seekerIconContainer" class="w-16 h-16 rounded-full bg-[#f0f7ff] border border-blue-100 flex items-center justify-center text-[#129aef] text-2xl mb-4 group-hover:scale-105 transition-transform">
                    <i id="seekerIcon" class="far fa-user text-2xl"></i>
                </div>

                <!-- Card Title -->
                <h3 class="text-lg font-bold text-slate-900 mb-2">
                    I am a Job Seeker
                </h3>

                <!-- Card Description -->
                <p id="seekerDesc" class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6 flex-grow">
                    Find jobs, track applications and manage your profile.
                </p>

                <!-- Action Button -->
                <a id="seekerBtn" href="{{ route('login', ['role' => 'candidate']) }}" class="w-full py-3 px-4 rounded-xl bg-[#0052cc] hover:bg-[#0043a8] text-white font-bold text-xs sm:text-sm transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 group-hover:gap-3">
                    <span id="seekerBtnText">Login as Job Seeker</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            <!-- Card 2: Employer -->
            <div class="group relative rounded-2xl border border-slate-200/90 bg-white p-6 sm:p-7 flex flex-col items-center text-center hover:border-emerald-500/50 hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300">
                <!-- Icon Circle -->
                <div id="employerIconContainer" class="w-16 h-16 rounded-full bg-[#edfbf3] border border-emerald-100 flex items-center justify-center text-[#059669] text-2xl mb-4 group-hover:scale-105 transition-transform">
                    <i id="employerIcon" class="far fa-building text-2xl"></i>
                </div>

                <!-- Card Title -->
                <h3 class="text-lg font-bold text-slate-900 mb-2">
                    I am an Employer
                </h3>

                <!-- Card Description -->
                <p id="employerDesc" class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6 flex-grow">
                    Post vacancies, find candidates and manage your school.
                </p>

                <!-- Action Button -->
                <a id="employerBtn" href="{{ route('login', ['role' => 'employer']) }}" class="w-full py-3 px-4 rounded-xl bg-[#008a4e] hover:bg-[#007340] text-white font-bold text-xs sm:text-sm transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 group-hover:gap-3">
                    <span id="employerBtnText">Login as Employer</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

        </div>

        <!-- Trust Note & Toggle Link Footer -->
        <div class="mt-7 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <div class="inline-flex items-center gap-1.5 text-xs text-emerald-700 font-medium">
                <i class="fas fa-shield-halved text-emerald-600"></i>
                <span>Your data is safe and secure with us.</span>
            </div>

            <div class="text-xs text-slate-500">
                <span id="authModalSwitchPrompt">Don't have an account?</span>
                <button type="button" id="authModalSwitchBtn" onclick="toggleAuthChoiceMode()" class="text-[#0052cc] font-bold hover:underline ml-1">
                    Register
                </button>
            </div>
        </div>

    </div>
</div>

<script>
    (function () {
        let currentAuthMode = 'login'; // 'login' or 'register'

        const modal = document.getElementById('authChoiceModal');
        const backdrop = document.getElementById('authChoiceModalBackdrop');
        const card = document.getElementById('authChoiceModalCard');

        const badge = document.getElementById('authModalBadge');
        const title = document.getElementById('authModalTitle');
        const subtitle = document.getElementById('authModalSubtitle');

        const seekerIcon = document.getElementById('seekerIcon');
        const seekerDesc = document.getElementById('seekerDesc');
        const seekerBtn = document.getElementById('seekerBtn');
        const seekerBtnText = document.getElementById('seekerBtnText');

        const employerIcon = document.getElementById('employerIcon');
        const employerDesc = document.getElementById('employerDesc');
        const employerBtn = document.getElementById('employerBtn');
        const employerBtnText = document.getElementById('employerBtnText');

        const switchPrompt = document.getElementById('authModalSwitchPrompt');
        const switchBtn = document.getElementById('authModalSwitchBtn');

        const authConfigs = {
            login: {
                showBadge: true,
                badgeHtml: '<span>Welcome Back! 👋</span>',
                title: 'Log in to your account',
                subtitle: 'Choose how you want to access Vedanta',
                seekerIconClass: 'far fa-user text-2xl',
                seekerDesc: 'Find jobs, track applications and manage your profile.',
                seekerBtnText: 'Login as Job Seeker',
                seekerUrl: "{{ route('login', ['role' => 'candidate']) }}",
                employerIconClass: 'far fa-building text-2xl',
                employerDesc: 'Post vacancies, find candidates and manage your school.',
                employerBtnText: 'Login as Employer',
                employerUrl: "{{ route('login', ['role' => 'employer']) }}",
                switchPrompt: "Don't have an account?",
                switchBtnText: 'Register'
            },
            register: {
                showBadge: false,
                badgeHtml: '',
                title: 'Create your account',
                subtitle: 'Select an account type to get started',
                seekerIconClass: 'fas fa-user-plus text-2xl',
                seekerDesc: 'Create your profile and apply for teaching jobs.',
                seekerBtnText: 'Register as Job Seeker',
                seekerUrl: "{{ route('candidate.register') }}",
                employerIconClass: 'fas fa-building-circle-check text-2xl',
                employerDesc: 'Register your school and hire the best teachers.',
                employerBtnText: 'Register as Employer',
                employerUrl: "{{ route('employer.register') }}",
                switchPrompt: 'Already have an account?',
                switchBtnText: 'Log in'
            }
        };

        function renderModalContent(mode) {
            currentAuthMode = mode;
            const config = authConfigs[mode] || authConfigs.login;

            if (config.showBadge) {
                badge.classList.remove('hidden');
                badge.innerHTML = config.badgeHtml;
            } else {
                badge.classList.add('hidden');
            }

            title.textContent = config.title;
            subtitle.textContent = config.subtitle;

            seekerIcon.className = config.seekerIconClass;
            seekerDesc.textContent = config.seekerDesc;
            seekerBtnText.textContent = config.seekerBtnText;
            seekerBtn.setAttribute('href', config.seekerUrl);

            employerIcon.className = config.employerIconClass;
            employerDesc.textContent = config.employerDesc;
            employerBtnText.textContent = config.employerBtnText;
            employerBtn.setAttribute('href', config.employerUrl);

            switchPrompt.textContent = config.switchPrompt;
            switchBtn.textContent = config.switchBtnText;
        }

        window.openAuthChoiceModal = function (mode) {
            renderModalContent(mode === 'register' ? 'register' : 'login');
            
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            // Animation
            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        };

        window.closeAuthChoiceModal = function () {
            if (!modal) return;
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 250);
        };

        window.toggleAuthChoiceMode = function () {
            const nextMode = currentAuthMode === 'login' ? 'register' : 'login';
            
            // Subtly animate card content change
            card.classList.add('opacity-80', 'scale-[0.98]');
            setTimeout(() => {
                renderModalContent(nextMode);
                card.classList.remove('opacity-80', 'scale-[0.98]');
            }, 120);
        };

        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                closeAuthChoiceModal();
            }
        });
    })();
</script>
