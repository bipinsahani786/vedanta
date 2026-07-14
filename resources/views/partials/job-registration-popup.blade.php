@guest
<!-- Registration Popup -->
<div id="jobRegPopup" class="fixed inset-0 hidden items-center justify-center px-4 bg-slate-900/50 backdrop-blur-sm opacity-0 transition-opacity duration-500" style="z-index: 99999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden relative transform transition-transform duration-500 popup-content" style="transform: scale(0.95);">
        <!-- Close Button -->
        <button id="closeJobRegPopup" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-full w-8 h-8 flex items-center justify-center transition-colors z-20">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-accent-blue to-blue-600 p-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-accent-blue shadow-lg text-2xl mx-auto mb-4 relative z-10">
                <i class="fas fa-user-plus"></i>
            </div>
            <h3 class="text-2xl font-bold text-white relative z-10">Join Us Now!</h3>
            <p class="text-blue-100 mt-2 text-sm relative z-10">Register to apply for jobs and get notified about new opportunities.</p>
        </div>
        
        <!-- Registration Form -->
        <div class="p-6 bg-slate-50">
            @if($errors->any())
                <div class="mb-4 bg-red-500/10 border border-red-500/30 p-3 rounded-xl">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                        <div>
                            <ul class="text-xs text-red-400 list-disc pl-4 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <form action="{{ route('candidate.register.post') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-user text-sm"></i></span>
                        <input name="name" type="text" required class="w-full bg-white border border-slate-200 rounded-lg pl-9 pr-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-accent-blue" placeholder="Full Name" value="{{ old('name') }}">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-envelope text-sm"></i></span>
                        <input name="email" type="email" required class="w-full bg-white border border-slate-200 rounded-lg pl-9 pr-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-accent-blue" placeholder="Email" value="{{ old('email') }}">
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-phone-alt text-sm"></i></span>
                        <input name="phone" type="text" required class="w-full bg-white border border-slate-200 rounded-lg pl-9 pr-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-accent-blue" placeholder="Phone Number" value="{{ old('phone') }}">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-lock text-sm"></i></span>
                        <input name="password" type="password" required class="w-full bg-white border border-slate-200 rounded-lg pl-9 pr-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-accent-blue" placeholder="Password">
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-shield-alt text-sm"></i></span>
                        <input name="password_confirmation" type="password" required class="w-full bg-white border border-slate-200 rounded-lg pl-9 pr-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-accent-blue" placeholder="Confirm Password">
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-accent-blue text-white font-bold py-2.5 rounded-lg hover:bg-blue-600 transition-colors shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 mt-2">
                    <i class="fas fa-paper-plane"></i> Register as Candidate
                </button>
            </form>
            
            <div class="mt-4 text-center">
                <p class="text-xs text-slate-500">Already have an account? <a href="{{ route('login') }}" class="text-accent-blue font-bold hover:underline">Login here</a></p>
                <p class="text-xs text-slate-400 mt-2">Looking to hire? <a href="{{ route('employer.register') }}" class="text-slate-600 hover:text-accent-yellow transition-colors underline">Register as Employer</a></p>
            </div>
        </div>
    </div>
</div>

<script>
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

        @if($errors->any())
            // Show immediately if there are validation errors
            showPopup();
        @else
            // Show after 2 seconds normally
            setTimeout(showPopup, 2000);
        @endif

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
    })();
</script>
@endguest
