@guest
<!-- Registration Popup -->
<div id="jobRegPopup" class="fixed inset-0 hidden items-center justify-center p-3 bg-slate-950/70 backdrop-blur-sm opacity-0 transition-opacity duration-500" style="z-index: 99999;">
    <div class="bg-white rounded-2xl shadow-2xl shadow-blue-950/40 w-full max-w-[440px] max-h-[92vh] overflow-y-auto relative transform transition-transform duration-500 popup-content border border-slate-100" style="transform: scale(0.95);">
        
        <!-- Close Button -->
        <button id="closeJobRegPopup" aria-label="Close popup" class="absolute top-2.5 right-2.5 text-slate-500 hover:text-slate-800 bg-white/90 hover:bg-white rounded-full w-7 h-7 flex items-center justify-center shadow-sm transition-all z-30 cursor-pointer">
            <i class="fas fa-times text-xs"></i>
        </button>
        
        <!-- Header Banner -->
        <div class="bg-gradient-to-br from-[#041a38] via-[#082e66] to-[#041935] text-left relative overflow-hidden pt-4 px-4 pb-0">
            <!-- Background Decorative Glow & Graduation Cap Watermark -->
            <div class="absolute -right-4 -top-4 w-48 h-48 rounded-full bg-blue-500/15 blur-2xl pointer-events-none"></div>
            <svg class="absolute right-20 top-3 w-16 h-16 text-white/[0.04] pointer-events-none" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 8.54L4.74 9 12 5.06 19.26 9 12 11.54zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
            </svg>

            <!-- Top Tagline -->
            <div class="inline-flex flex-col mb-1 relative z-10">
                <span class="text-[10px] font-semibold text-blue-100 tracking-wide">Join Vedanta Placement Agency</span>
                <div class="w-10 h-[2px] bg-amber-400 mt-0.5 rounded-full"></div>
            </div>

            <div class="relative z-10 flex items-start justify-between">
                <!-- Left Title & Copy -->
                <div class="pb-2.5 max-w-[215px] sm:max-w-[235px]">
                    <h3 class="text-lg sm:text-xl font-black text-white leading-tight tracking-tight mt-0.5">
                        Create Your<br>
                        <span class="text-[#f5a623]">Professional Profile</span>
                    </h3>
                    <p class="text-blue-100/85 text-[11px] leading-snug mt-1.5">
                        Register now to build your profile and get access to the best teaching opportunities across India.
                    </p>
                </div>

                <!-- Right Teacher Visual -->
                <div class="relative w-28 sm:w-32 h-28 sm:h-32 flex-shrink-0 -mr-2 -mt-1">
                    <!-- Script Note -->
                    <div class="absolute top-0 right-1 text-right pointer-events-none select-none z-20">
                        <span class="text-[9px] text-amber-200/95 font-serif italic font-bold leading-tight block drop-shadow" style="font-family: 'Playfair Display', serif;">
                            Your<br>Teaching Career<br>Starts Here
                        </span>
                    </div>

                    <!-- Teacher Photo -->
                    <div class="w-full h-full relative overflow-hidden">
                        <img src="{{ asset('images/educator_popup.jpg') }}" alt="Educator" class="w-full h-full object-cover object-top scale-105" style="-webkit-mask-image: linear-gradient(to left, black 65%, transparent 100%), linear-gradient(to top, transparent 0%, black 20%); mask-image: linear-gradient(to left, black 65%, transparent 100%), linear-gradient(to top, transparent 0%, black 20%);">
                    </div>

                    <!-- Floating Pill Badge -->
                    <div class="absolute bottom-1 -left-2 bg-[#fef3c7] text-slate-900 px-2 py-0.5 rounded-lg shadow border border-amber-300/80 flex items-center gap-1 z-20 whitespace-nowrap">
                        <div class="w-4 h-4 rounded-full bg-[#052857] text-white flex items-center justify-center text-[8px] flex-shrink-0">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <div class="text-[8px] font-bold leading-tight text-left">
                            <span class="text-slate-900 block font-extrabold">Join Thousands</span>
                            <span class="text-slate-600 block text-[7px] font-semibold">of Educators</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Strip Bar (4 Items) -->
            <div class="bg-[#02132b]/85 backdrop-blur-md -mx-4 px-2 py-1.5 grid grid-cols-4 gap-1 border-t border-white/10 items-center relative z-10">
                <!-- 1. Build Your Profile -->
                <div class="flex items-center gap-1">
                    <div class="w-6 h-6 rounded-md bg-blue-500/25 border border-blue-400/30 flex items-center justify-center text-blue-300 flex-shrink-0 text-[10px]">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <span class="text-[8px] sm:text-[9px] font-semibold leading-tight text-white">
                        Build<br>Your Profile
                    </span>
                </div>

                <!-- 2. Get Job Alerts -->
                <div class="flex items-center gap-1">
                    <div class="w-6 h-6 rounded-md bg-blue-500/25 border border-blue-400/30 flex items-center justify-center text-blue-300 flex-shrink-0 text-[10px]">
                        <i class="fas fa-bell"></i>
                    </div>
                    <span class="text-[8px] sm:text-[9px] font-semibold leading-tight text-white">
                        Get<br>Job Alerts
                    </span>
                </div>

                <!-- 3. Apply to Top Schools -->
                <div class="flex items-center gap-1">
                    <div class="w-6 h-6 rounded-md bg-blue-500/25 border border-blue-400/30 flex items-center justify-center text-blue-300 flex-shrink-0 text-[10px]">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span class="text-[8px] sm:text-[9px] font-semibold leading-tight text-white">
                        Apply to<br>Top Schools
                    </span>
                </div>

                <!-- 4. Grow Your Teaching Career -->
                <div class="flex items-center gap-1">
                    <div class="w-6 h-6 rounded-md bg-blue-500/25 border border-blue-400/30 flex items-center justify-center text-blue-300 flex-shrink-0 text-[10px]">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <span class="text-[8px] sm:text-[9px] font-semibold leading-tight text-white">
                        Grow Your<br>Teaching Career
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Registration Form Area -->
        <div class="p-3.5 sm:p-4 bg-white space-y-2.5">
            @if($errors->any())
                <div class="mb-2 bg-red-500/10 border border-red-500/30 p-2.5 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 text-xs"></i>
                        <div>
                            <ul class="text-[11px] text-red-600 list-disc pl-3 space-y-0.5 font-medium">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('candidate.register.post') }}" method="POST" class="space-y-2">
                @csrf
                <!-- Full Name -->
                <div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <i class="fas fa-user text-xs"></i>
                        </span>
                        <input name="name" type="text" required class="w-full bg-[#f8fafc] hover:bg-white border border-slate-200 rounded-lg pl-8 pr-3 py-1.5 sm:py-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-100 transition-all shadow-sm" placeholder="Full Name" value="{{ old('name') }}">
                    </div>
                </div>

                <!-- Email & Phone Number -->
                <div class="grid grid-cols-2 gap-2">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <i class="fas fa-envelope text-xs"></i>
                        </span>
                        <input name="email" type="email" required class="w-full bg-[#f8fafc] hover:bg-white border border-slate-200 rounded-lg pl-8 pr-2 py-1.5 sm:py-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-100 transition-all shadow-sm" placeholder="Email" value="{{ old('email') }}">
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <i class="fas fa-phone-alt text-xs"></i>
                        </span>
                        <input name="phone" type="text" required class="w-full bg-[#f8fafc] hover:bg-white border border-slate-200 rounded-lg pl-8 pr-2 py-1.5 sm:py-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-100 transition-all shadow-sm" placeholder="Phone Number" value="{{ old('phone') }}">
                    </div>
                </div>

                @php
                    $popupCategories = \App\Models\Category::with(['subjects' => function($q) {
                        $q->where('subjects.is_active', true)->orderBy('name');
                    }])->where('is_active', true)->orderBy('name')->get();
                @endphp

                <!-- Category & Subject -->
                <div class="grid grid-cols-2 gap-2">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <i class="fas fa-layer-group text-xs"></i>
                        </span>
                        <select name="category_id" id="popup_category_id" required onchange="handlePopupCategoryChange(this.value)"
                            class="w-full bg-[#f8fafc] hover:bg-white border border-slate-200 rounded-lg pl-8 pr-6 py-1.5 sm:py-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-100 appearance-none cursor-pointer transition-all shadow-sm">
                            <option value="">Select Category</option>
                            @foreach($popupCategories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <i class="fas fa-book-open text-xs"></i>
                        </span>
                        <select name="subject_id" id="popup_subject_id" required
                            class="w-full bg-[#f8fafc] hover:bg-white border border-slate-200 rounded-lg pl-8 pr-6 py-1.5 sm:py-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-100 appearance-none cursor-pointer transition-all shadow-sm">
                            <option value="">Select Subject</option>
                        </select>
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </span>
                    </div>
                </div>

                <!-- Password & Confirm Password -->
                <div class="grid grid-cols-2 gap-2">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <i class="fas fa-lock text-xs"></i>
                        </span>
                        <input name="password" type="password" required class="w-full bg-[#f8fafc] hover:bg-white border border-slate-200 rounded-lg pl-8 pr-2 py-1.5 sm:py-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-100 transition-all shadow-sm" placeholder="Password">
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <i class="fas fa-shield-alt text-xs"></i>
                        </span>
                        <input name="password_confirmation" type="password" required class="w-full bg-[#f8fafc] hover:bg-white border border-slate-200 rounded-lg pl-8 pr-2 py-1.5 sm:py-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-100 transition-all shadow-sm" placeholder="Confirm Password">
                    </div>
                </div>
                
                <!-- Primary Submit Button with Arrow -->
                <button type="submit" class="w-full relative bg-[#0066f5] hover:bg-blue-600 text-white font-bold py-2 sm:py-2.5 px-4 rounded-lg shadow-md shadow-blue-500/25 flex items-center justify-center gap-2 transition-all duration-200 group mt-1 cursor-pointer">
                    <i class="fas fa-user-plus text-xs sm:text-sm"></i>
                    <span class="text-xs sm:text-[13px] font-bold">Register & Build Profile</span>
                    <i class="fas fa-arrow-right text-xs sm:text-sm absolute right-3 sm:right-4 transform group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>
            
            <!-- Secondary Button: Try Free Resume Builder -->
            <div>
                <a href="{{ route('resume.builder') }}" class="w-full bg-white border-2 border-[#0066f5] text-[#0066f5] hover:bg-blue-50/60 font-bold py-1.5 sm:py-2 px-3 rounded-lg transition-colors flex items-center justify-center gap-2 text-xs sm:text-[13px] cursor-pointer shadow-sm">
                    <span class="bg-[#0066f5] text-white text-[8px] font-black px-1 py-0.5 rounded leading-none flex items-center gap-0.5">
                        <i class="fas fa-file-pdf text-[8px]"></i> PDF
                    </span>
                    <span>Try Free Resume Builder</span>
                </a>
            </div>

            <!-- "OR" Divider -->
            <div class="relative flex items-center my-1.5">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-2.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">OR</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <!-- Account Links -->
            <div class="text-center space-y-0.5">
                <p class="text-[11px] text-slate-600">
                    Already have an account? <a href="{{ route('login') }}" class="text-[#0066f5] font-bold hover:underline">Login here</a>
                </p>
                <p class="text-[11px] text-slate-600">
                    Looking to hire? <a href="{{ route('employer.register') }}" class="text-slate-800 font-bold hover:text-[#0066f5] transition-colors underline decoration-slate-300">Register as Employer</a>
                </p>
            </div>

            <!-- Bottom Trust Badges (3 Columns) -->
            <div class="mt-2.5 pt-2 border-t border-slate-200/80 grid grid-cols-3 gap-1.5">
                <!-- 1. 100% Secure -->
                <div class="flex items-center gap-1.5 pr-1 border-r border-slate-200">
                    <div class="text-[#052857] text-base flex-shrink-0">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="leading-tight text-left">
                        <span class="block text-[10px] font-bold text-slate-900">100% Secure</span>
                        <span class="block text-[8px] text-slate-500 leading-none mt-0.5">Your data is safe</span>
                    </div>
                </div>

                <!-- 2. Trusted by -->
                <div class="flex items-center gap-1.5 px-1 border-r border-slate-200">
                    <div class="text-[#052857] text-base flex-shrink-0">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div class="leading-tight text-left">
                        <span class="block text-[10px] font-bold text-slate-900">Trusted by</span>
                        <span class="block text-[8px] text-slate-500 leading-none mt-0.5">10,000+ Educators</span>
                    </div>
                </div>

                <!-- 3. Dedicated Support -->
                <div class="flex items-center gap-1.5 pl-1">
                    <div class="text-[#052857] text-base flex-shrink-0">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="leading-tight text-left">
                        <span class="block text-[10px] font-bold text-slate-900">Dedicated</span>
                        <span class="block text-[8px] text-slate-500 leading-none mt-0.5">Support</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const popupCategorySubjectsMap = {
        @foreach($popupCategories as $cat)
            "{{ $cat->id }}": [
                @foreach($cat->subjects as $sub)
                    { id: "{{ $sub->id }}", name: "{{ addslashes($sub->name) }}" },
                @endforeach
            ],
        @endforeach
    };

    function populatePopupSubjects(subjects, selectedSubjectId = null) {
        const subjectSelect = document.getElementById('popup_subject_id');
        if (!subjectSelect) return;

        subjectSelect.innerHTML = '<option value="">Select Subject</option>';

        if (!subjects || subjects.length === 0) {
            subjectSelect.innerHTML = '<option value="">No subjects available</option>';
            return;
        }

        subjects.forEach(subject => {
            const option = document.createElement('option');
            option.value = subject.id;
            option.textContent = subject.name;
            if (selectedSubjectId && String(subject.id) === String(selectedSubjectId)) {
                option.selected = true;
            }
            subjectSelect.appendChild(option);
        });
    }

    window.handlePopupCategoryChange = function(categoryId, selectedSubjectId = null) {
        const subjectSelect = document.getElementById('popup_subject_id');
        if (!subjectSelect) return;

        if (!categoryId) {
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            return;
        }

        if (popupCategorySubjectsMap[categoryId] && popupCategorySubjectsMap[categoryId].length > 0) {
            populatePopupSubjects(popupCategorySubjectsMap[categoryId], selectedSubjectId);
            return;
        }

        subjectSelect.innerHTML = '<option value="">Loading subjects...</option>';
        fetch("{{ url('/api/categories') }}/" + categoryId + "/subjects")
            .then(res => res.json())
            .then(data => {
                popupCategorySubjectsMap[categoryId] = data;
                populatePopupSubjects(data, selectedSubjectId);
            })
            .catch(() => {
                populatePopupSubjects([], null);
            });
    };

    (function() {
        const showPopup = () => {
            const popup = document.getElementById('jobRegPopup');
            if(popup) {
                const content = popup.querySelector('.popup-content');
                
                popup.classList.remove('hidden');
                popup.style.display = 'flex';
                
                // Trigger animation
                setTimeout(() => {
                    popup.classList.remove('opacity-0');
                    popup.style.opacity = '1';
                    content.style.transform = 'scale(1)';
                }, 50);
            }
        };

        if (window.location.search.includes('show_popup=1') || @json($errors->any())) {
            // Show immediately if query param or validation errors
            showPopup();
        } else {
            // Show after 2 seconds normally
            setTimeout(showPopup, 2000);
        }

        function closeJobPopup() {
            const popup = document.getElementById('jobRegPopup');
            const content = popup.querySelector('.popup-content');
            
            // Revert inline styles
            popup.style.opacity = '0';
            content.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                popup.style.display = 'none';
            }, 500);
        }

        // Attach event listeners immediately since script is at the bottom of the DOM
        const closeBtn = document.getElementById('closeJobRegPopup');
        if(closeBtn) {
            closeBtn.addEventListener('click', closeJobPopup);
        }

        const popupEl = document.getElementById('jobRegPopup');
        if(popupEl) {
            popupEl.addEventListener('click', function(e) {
                if(e.target === this) {
                    closeJobPopup();
                }
            });
        }

        const categorySelect = document.getElementById('popup_category_id');
        const oldSubjectId = "{{ old('subject_id') }}";
        if (categorySelect && categorySelect.value) {
            window.handlePopupCategoryChange(categorySelect.value, oldSubjectId);
        }
    })();
</script>
@endguest
