@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center bg-secondary-bg py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 bg-card-bg rounded-3xl shadow-2xl border border-card-border overflow-hidden reveal">

        {{-- Left Panel - Branding --}}
        <div class="hidden lg:flex flex-col justify-between relative bg-gradient-to-br from-primary-bg via-accent-yellow/10 to-primary-bg p-10 overflow-hidden">
            {{-- Decorative Elements --}}
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-accent-yellow/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-accent-blue/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-40 h-40 border border-white/5 rounded-full"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 border border-white/5 rounded-full"></div>

            <div class="relative z-10">
                <a href="{{ route('home') }}">
                    <img src="/images/logo.png" alt="Vedanta Placement Agency" class="h-12 w-auto object-contain mb-2">
                </a>
            </div>

            <div class="relative z-10 space-y-6">
                <h2 class="text-3xl font-bold text-text-main leading-snug">Hire the best<br>educators with <span class="text-accent-yellow">Vedanta</span></h2>
                <p class="text-sm text-text-main/70 leading-relaxed max-w-xs">
                    Partner with us to find top-tier teaching professionals for your institution. Fast, reliable, and hassle-free hiring.
                </p>

                {{-- Benefits --}}
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-accent-yellow/15 text-accent-yellow flex items-center justify-center text-xs"><i class="fas fa-users"></i></div>
                        <span class="text-sm text-text-main/80">Access to 1M+ verified candidates</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-accent-blue/15 text-accent-blue flex items-center justify-center text-xs"><i class="fas fa-bolt"></i></div>
                        <span class="text-sm text-text-main/80">Quick turnaround on hiring</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-accent-yellow/15 text-accent-yellow flex items-center justify-center text-xs"><i class="fas fa-check-double"></i></div>
                        <span class="text-sm text-text-main/80">Pre-screened & qualified teachers</span>
                    </div>
                </div>
            </div>

            <div class="relative z-10">
                <p class="text-[11px] text-text-main/40">&copy; {{ date('Y') }} Vedanta Placement Agency. All rights reserved.</p>
            </div>
        </div>

        {{-- Right Panel - Registration Form --}}
        <div class="p-8 sm:p-10 lg:p-12 flex flex-col justify-center">
            {{-- Mobile Logo --}}
            <div class="lg:hidden flex justify-center mb-6">
                <a href="{{ route('home') }}">
                    <img src="/images/logo.png" alt="Vedanta Placement Agency" class="h-10 w-auto object-contain">
                </a>
            </div>

            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-accent-yellow/10 text-accent-yellow flex items-center justify-center text-lg">
                        <i class="fas fa-building"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-text-main">Employer Registration</h2>
                </div>
                <p class="mt-1.5 text-sm text-text-dark/60">Find the best teaching professionals for your institution</p>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/30 p-4 rounded-xl">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                        <div>
                            <ul class="text-sm text-red-300/80 list-disc pl-4 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('employer.register.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="school_name" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">School / Institution Name</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-school text-sm"></i></span>
                        <input id="school_name" name="school_name" type="text" required
                            class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-yellow/50 focus:border-accent-yellow transition-all"
                            placeholder="e.g. Delhi Public School" value="{{ old('school_name') }}">
                    </div>
                </div>

                <div>
                    <label for="contact_person" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Contact Person Name</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-user-tie text-sm"></i></span>
                        <input id="contact_person" name="contact_person" type="text" required
                            class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-yellow/50 focus:border-accent-yellow transition-all"
                            placeholder="Principal / HR name" value="{{ old('contact_person') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Email</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-envelope text-sm"></i></span>
                            <input id="email" name="email" type="email" required
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-yellow/50 focus:border-accent-yellow transition-all"
                                placeholder="school@example.com" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Phone</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-phone-alt text-sm"></i></span>
                            <input id="phone" name="phone" type="text" required
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-yellow/50 focus:border-accent-yellow transition-all"
                                placeholder="+91-XXXXXXXXXX" value="{{ old('phone') }}">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">
                            Job Category <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40 pointer-events-none"><i class="fas fa-layer-group text-sm"></i></span>
                            @php
                                $allCategories = $categories ?? \App\Models\Category::with(['subjects' => function($q) {
                                    $q->where('subjects.is_active', true)->orderBy('name');
                                }])->where('is_active', true)->orderBy('name')->get();
                            @endphp
                            <select id="category_id" name="category_id" required onchange="handleCategoryChange(this.value)"
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-10 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-yellow/50 focus:border-accent-yellow transition-all appearance-none cursor-pointer">
                                <option value="" style="background-color: #040e2d; color: #94a3b8;">Select Category</option>
                                @foreach($allCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} style="background-color: #040e2d; color: #ffffff;">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-text-dark/40 pointer-events-none"><i class="fas fa-chevron-down text-xs"></i></span>
                        </div>
                    </div>

                    <div>
                        <label for="subject_id" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">
                            Subject <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40 pointer-events-none"><i class="fas fa-book text-sm"></i></span>
                            <select id="subject_id" name="subject_id" required
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-10 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-yellow/50 focus:border-accent-yellow transition-all appearance-none cursor-pointer">
                                <option value="" style="background-color: #040e2d; color: #94a3b8;">Select Subject</option>
                            </select>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-text-dark/40 pointer-events-none"><i class="fas fa-chevron-down text-xs"></i></span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-lock text-sm"></i></span>
                            <input id="password" name="password" type="password" required
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-yellow/50 focus:border-accent-yellow transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-shield-alt text-sm"></i></span>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-yellow/50 focus:border-accent-yellow transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-accent-yellow text-[#031b4e] font-semibold py-3.5 rounded-xl hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-yellow transition-all shadow-lg hover:shadow-glow-yellow hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-2">
                    <i class="fas fa-building"></i>
                    Create Employer Account
                </button>
            </form>

            <div class="mt-6 flex items-center gap-4">
                <div class="flex-1 h-px bg-card-border"></div>
                <span class="text-xs text-text-dark/40 uppercase tracking-wider font-medium">Or</span>
                <div class="flex-1 h-px bg-card-border"></div>
            </div>

            <div class="mt-4 text-center space-y-2">
                <p class="text-sm text-text-dark/60">
                    Already registered? <a href="{{ route('login') }}" class="font-semibold text-accent-blue hover:underline">Sign in</a>
                </p>
                <p class="text-sm text-text-dark/60">
                    Looking for a job? <a href="{{ route('candidate.register') }}" class="font-semibold text-accent-blue hover:underline">Register as Candidate</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    // Embedded category-to-subjects dictionary for instant zero-latency rendering
    const categorySubjectsMap = {
        @foreach($allCategories as $cat)
            "{{ $cat->id }}": [
                @foreach($cat->subjects as $sub)
                    { id: "{{ $sub->id }}", name: "{{ addslashes($sub->name) }}" },
                @endforeach
            ],
        @endforeach
    };

    function populateSubjects(subjects, selectedSubjectId = null) {
        const subjectSelect = document.getElementById('subject_id');
        if (!subjectSelect) return;

        subjectSelect.innerHTML = '<option value="" style="background-color: #040e2d; color: #94a3b8;">Select Subject</option>';

        if (!subjects || subjects.length === 0) {
            subjectSelect.innerHTML = '<option value="" style="background-color: #040e2d; color: #94a3b8;">No subjects available for this category</option>';
            return;
        }

        subjects.forEach(subject => {
            const option = document.createElement('option');
            option.value = subject.id;
            option.textContent = subject.name;
            option.style.backgroundColor = '#040e2d';
            option.style.color = '#ffffff';
            if (selectedSubjectId && String(subject.id) === String(selectedSubjectId)) {
                option.selected = true;
            }
            subjectSelect.appendChild(option);
        });
    }

    function handleCategoryChange(categoryId, selectedSubjectId = null) {
        const subjectSelect = document.getElementById('subject_id');
        if (!subjectSelect) return;

        if (!categoryId) {
            subjectSelect.innerHTML = '<option value="" style="background-color: #040e2d; color: #94a3b8;">Select Subject (Choose category first)</option>';
            return;
        }

        // 1. Instant populate from preloaded local map
        if (categorySubjectsMap[categoryId] && categorySubjectsMap[categoryId].length > 0) {
            populateSubjects(categorySubjectsMap[categoryId], selectedSubjectId);
            return;
        }

        // 2. Fallback to API if not in preloaded map
        subjectSelect.innerHTML = '<option value="" style="background-color: #040e2d; color: #94a3b8;">Loading subjects...</option>';
        fetch("{{ url('/api/categories') }}/" + categoryId + "/subjects")
            .then(res => res.json())
            .then(data => {
                categorySubjectsMap[categoryId] = data;
                populateSubjects(data, selectedSubjectId);
            })
            .catch(() => {
                populateSubjects([], null);
            });
    }

    // Initialize on page load
    (function initCategorySubject() {
        function runInit() {
            const categorySelect = document.getElementById('category_id');
            const oldSubjectId = "{{ old('subject_id') }}";
            if (categorySelect && categorySelect.value) {
                handleCategoryChange(categorySelect.value, oldSubjectId);
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', runInit);
        } else {
            runInit();
        }
    })();
</script>
@endsection
