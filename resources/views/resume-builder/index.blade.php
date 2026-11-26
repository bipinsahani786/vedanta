@extends('layouts.app')

@section('content')
<div class="bg-primary-bg min-h-screen pt-4 pb-12" x-data="resumeBuilder()">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Top Bar --}}
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-text-main flex items-center gap-2.5">
                    <i class="fas fa-file-alt text-accent-yellow"></i>
                    <span>Free Auto Resume Builder</span>
                </h1>
                <p class="text-sm text-text-dark/60 mt-1">
                    Choose a template from Freshers, Corporate, or Tech categories, edit your details with live preview, and switch templates anytime without losing entered data.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <template x-if="step === 'editor'">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <button type="button" @click="openGallery()"
                                class="px-4 py-2.5 bg-card-bg border border-card-border hover:border-accent-blue text-text-main text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 cursor-pointer">
                            <i class="fas fa-th-large text-accent-blue"></i>
                            <span>Browse Templates</span>
                        </button>

                        <button type="button" @click="resetToSample()"
                                class="px-3.5 py-2.5 bg-card-bg border border-card-border hover:border-red-400 text-text-dark/70 hover:text-red-400 text-xs font-bold rounded-xl transition-all cursor-pointer"
                                title="Reset form fields to sample data">
                            Reset Sample
                        </button>

                        <form action="{{ route('resume.builder.download') }}" method="POST" id="downloadForm" class="inline">
                            @csrf
                            <input type="hidden" name="resume_data" :value="JSON.stringify(resume)">
                            <button type="submit"
                                    class="px-5 py-2.5 bg-accent-yellow text-[#031b4e] text-sm font-bold rounded-xl shadow-glow-yellow hover:-translate-y-0.5 transition-all flex items-center gap-2 cursor-pointer">
                                <i class="fas fa-download"></i> Download PDF
                            </button>
                        </form>
                    </div>
                </template>

                <template x-if="step === 'gallery' && resume.template">
                    <button type="button" @click="step = 'editor'"
                            class="px-5 py-2.5 bg-accent-blue hover:bg-accent-blue-hover text-white text-xs font-bold rounded-xl shadow-sm flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-edit"></i>
                        <span>Continue Editing (<span x-text="activeTemplateMeta().name"></span>)</span>
                    </button>
                </template>
            </div>
        </div>

        {{-- STEP 1: TEMPLATE GALLERY BY CATEGORY --}}
        <div x-show="step === 'gallery'" x-cloak class="space-y-6">
            <div class="bg-card-bg border border-card-border rounded-2xl p-5 flex flex-wrap items-center justify-between gap-4 shadow-sm">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-text-dark/50 mr-1">Categories:</span>
                    <template x-for="cat in categories" :key="cat">
                        <button type="button" @click="selectedCategory = cat"
                                :class="selectedCategory === cat ? 'bg-accent-yellow text-[#031b4e] shadow-sm font-extrabold' : 'bg-secondary-bg text-text-main border border-card-border hover:border-accent-blue/50'"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer"
                                x-text="cat === 'All' ? 'All Templates (' + templates.length + ')' : cat">
                        </button>
                    </template>
                </div>

                <div class="text-xs text-emerald-400 font-semibold flex items-center gap-1.5">
                    <i class="fas fa-shield-alt"></i>
                    <span>Switching templates keeps all your entered information intact.</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <template x-for="tpl in filteredTemplates()" :key="tpl.id">
                    <div :class="resume.template === tpl.id ? 'border-accent-yellow ring-2 ring-accent-yellow/30' : 'border-card-border hover:border-accent-blue/60'"
                         class="bg-card-bg border rounded-2xl overflow-hidden shadow-xl flex flex-col justify-between transition-all group">

                        {{-- Mini Visual Wireframe Preview --}}
                        <div class="p-4 bg-slate-100 border-b border-card-border">
                            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-slate-200 h-52 flex flex-col text-[8px] text-slate-700 select-none pointer-events-none">
                                <template x-if="tpl.layout === 'sidebar'">
                                    <div class="flex h-full">
                                        <div class="w-[34%] p-2.5 flex flex-col justify-between text-white" :style="`background: ${tpl.primary}`">
                                            <div>
                                                <div class="w-8 h-8 rounded-lg bg-white/20 mb-2 flex items-center justify-center font-bold text-[10px]" x-text="(resume.personal.name || 'A')[0]"></div>
                                                <div class="font-bold text-[9px] truncate" x-text="resume.personal.name || 'Your Name'"></div>
                                                <div class="opacity-80 text-[7px] truncate" x-text="resume.personal.title || 'Title'"></div>
                                            </div>
                                            <div class="space-y-1 opacity-90">
                                                <div class="h-1 bg-white/30 rounded w-4/5"></div>
                                                <div class="h-1 bg-white/30 rounded w-3/5"></div>
                                                <div class="flex flex-wrap gap-1 pt-1">
                                                    <span class="px-1 py-0.5 rounded bg-white/15 text-[6px]">Skill 1</span>
                                                    <span class="px-1 py-0.5 rounded bg-white/15 text-[6px]">Skill 2</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-[66%] p-3 space-y-2">
                                            <div class="font-bold uppercase tracking-wider text-[7px]" :style="`color: ${tpl.primary}`">Summary</div>
                                            <div class="h-1.5 bg-slate-200 rounded w-full"></div>
                                            <div class="h-1.5 bg-slate-200 rounded w-5/6"></div>
                                            <div class="font-bold uppercase tracking-wider text-[7px] pt-1" :style="`color: ${tpl.primary}`">Experience</div>
                                            <div class="h-1.5 bg-slate-300 rounded w-3/4"></div>
                                            <div class="h-1.5 bg-slate-200 rounded w-full"></div>
                                            <div class="font-bold uppercase tracking-wider text-[7px] pt-1" :style="`color: ${tpl.primary}`">Education</div>
                                            <div class="h-1.5 bg-slate-200 rounded w-4/5"></div>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="tpl.layout === 'banner'">
                                    <div class="flex flex-col h-full">
                                        <div class="p-3 text-white" :style="`background: ${tpl.primary}; border-bottom: 3px solid ${tpl.secondary}`">
                                            <div class="font-bold text-[11px] truncate" x-text="resume.personal.name || 'Your Name'"></div>
                                            <div class="opacity-85 text-[8px] truncate" x-text="resume.personal.title || 'Job Title'"></div>
                                        </div>
                                        <div class="p-3 space-y-2 flex-1">
                                            <div class="font-bold uppercase text-[7px] border-b pb-0.5" :style="`color: ${tpl.primary}`" x-text="tpl.order === 'education_first' ? 'Education & Skills' : 'Experience'"></div>
                                            <div class="h-1.5 bg-slate-300 rounded w-3/4"></div>
                                            <div class="h-1.5 bg-slate-200 rounded w-full"></div>
                                            <div class="font-bold uppercase text-[7px] border-b pb-0.5 pt-1" :style="`color: ${tpl.primary}`" x-text="tpl.order === 'education_first' ? 'Projects / Experience' : 'Education'"></div>
                                            <div class="h-1.5 bg-slate-200 rounded w-5/6"></div>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="tpl.layout === 'classic'">
                                    <div class="p-3.5 space-y-2 flex-1">
                                        <div class="text-center pb-2" :style="`border-bottom: 2px solid ${tpl.primary}`">
                                            <div class="font-bold text-[11px] text-slate-900 truncate" x-text="resume.personal.name || 'Your Name'"></div>
                                            <div class="text-[8px]" :style="`color: ${tpl.primary}`" x-text="resume.personal.title || 'Title'"></div>
                                        </div>
                                        <div class="font-bold uppercase text-[7px] border-b pb-0.5" :style="`color: ${tpl.primary}`">Summary</div>
                                        <div class="h-1.5 bg-slate-200 rounded w-full"></div>
                                        <div class="font-bold uppercase text-[7px] border-b pb-0.5 pt-1" :style="`color: ${tpl.primary}`" x-text="tpl.order === 'education_first' ? 'Education First' : 'Experience'"></div>
                                        <div class="h-1.5 bg-slate-300 rounded w-2/3"></div>
                                        <div class="h-1.5 bg-slate-200 rounded w-5/6"></div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="p-4 flex-1 flex flex-col justify-between gap-3">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-accent-blue/15 text-accent-blue border border-accent-blue/30" x-text="tpl.category"></span>
                                    <span x-show="resume.template === tpl.id" class="text-[10px] font-bold text-accent-yellow">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                </div>
                                <h3 class="text-base font-bold text-text-main" x-text="tpl.name"></h3>
                                <p class="text-xs text-text-dark/60 mt-1 leading-relaxed" x-text="tpl.tagline"></p>
                            </div>

                            <button type="button" @click="selectTemplate(tpl.id)"
                                    class="w-full py-2.5 px-4 rounded-xl bg-accent-yellow hover:brightness-110 text-[#031b4e] text-xs font-extrabold transition-all cursor-pointer flex items-center justify-center gap-1.5 shadow-sm">
                                <i class="fas fa-magic"></i>
                                <span>Use This Template</span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- STEP 2: EDITOR + LIVE PREVIEW + INSTANT TEMPLATE SWITCHER --}}
        <div x-show="step === 'editor'" x-cloak class="space-y-6">

            {{-- Instant In-Editor Template Switcher Bar (Preserves All Entered Details) --}}
            <div class="bg-card-bg border border-card-border rounded-2xl p-3.5 shadow-sm flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-text-dark/60 uppercase tracking-wider mr-1">
                        <i class="fas fa-palette text-accent-yellow mr-1"></i> Switch Template:
                    </span>
                    <template x-for="cat in categories" :key="'sw-' + cat">
                        <button type="button" @click="selectedCategory = cat"
                                :class="selectedCategory === cat ? 'bg-accent-blue text-white' : 'bg-secondary-bg text-text-dark/70 hover:text-text-main'"
                                class="px-2.5 py-1 rounded-lg text-[11px] font-bold cursor-pointer transition-colors"
                                x-text="cat"></button>
                    </template>
                </div>

                <div class="flex flex-wrap items-center gap-1.5">
                    <template x-for="tpl in filteredTemplates()" :key="'pill-' + tpl.id">
                        <button type="button" @click="selectTemplate(tpl.id)"
                                :class="resume.template === tpl.id ? 'bg-accent-yellow text-[#031b4e] font-extrabold shadow-xs' : 'bg-secondary-bg text-text-main border border-card-border hover:border-accent-blue'"
                                class="px-3 py-1.5 rounded-xl text-xs transition-all cursor-pointer flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full inline-block" :style="`background: ${tpl.primary}`"></span>
                            <span x-text="tpl.name"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                {{-- LEFT: EDITOR PANEL --}}
                <div class="w-full lg:w-[45%] lg:h-[calc(100vh-230px)] lg:overflow-y-auto pr-1 sm:pr-3 hide-scrollbar space-y-6">

                    {{-- Personal Details --}}
                    <div class="bg-card-bg border border-card-border rounded-2xl p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-text-main mb-4 border-b border-card-border pb-2">
                            <i class="fas fa-user text-accent-blue mr-2"></i> Personal Details
                        </h2>

                        <div class="flex items-center gap-4 p-4 border border-card-border rounded-xl bg-primary-bg/50 mb-4">
                            <div class="relative w-16 h-16 rounded-xl border border-card-border bg-white overflow-hidden flex items-center justify-center flex-shrink-0 shadow-inner">
                                <template x-if="resume.personal.photo">
                                    <img :src="resume.personal.photo" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!resume.personal.photo">
                                    <i class="fas fa-user-circle text-4xl text-text-dark/20"></i>
                                </template>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-text-dark/70 mb-1">Profile Photo</label>
                                <div class="flex flex-wrap items-center gap-2">
                                    <input type="file" @change="uploadPhoto($event)" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" id="photoUploadInput">
                                    <label for="photoUploadInput" class="px-3 py-1.5 bg-accent-blue text-white rounded-lg text-xs font-bold cursor-pointer hover:opacity-90 shadow-sm">
                                        <i class="fas fa-upload mr-1"></i> Choose Image
                                    </label>
                                    <template x-if="resume.personal.photo">
                                        <button type="button" @click="removePhoto()" class="px-3 py-1.5 bg-red-500/10 text-red-500 rounded-lg text-xs font-bold hover:bg-red-500 hover:text-white transition-colors cursor-pointer">
                                            <i class="fas fa-trash mr-1"></i> Remove
                                        </button>
                                    </template>
                                </div>
                                <p class="text-[10px] text-text-dark/40 mt-1">Square format recommended, Max 2MB.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-text-dark/70 mb-1">Full Name</label>
                                <input type="text" x-model="resume.personal.name" class="w-full bg-primary-bg border border-card-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:border-accent-blue outline-none" placeholder="e.g. Ananya Verma">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-dark/70 mb-1">Job Title / Designation</label>
                                <input type="text" x-model="resume.personal.title" class="w-full bg-primary-bg border border-card-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:border-accent-blue outline-none" placeholder="e.g. PGT Mathematics / Software Engineer">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-dark/70 mb-1">Email</label>
                                <input type="email" x-model="resume.personal.email" class="w-full bg-primary-bg border border-card-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:border-accent-blue outline-none" placeholder="name@example.com">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-dark/70 mb-1">Phone</label>
                                <input type="text" x-model="resume.personal.phone" class="w-full bg-primary-bg border border-card-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:border-accent-blue outline-none" placeholder="+91 9876543210">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-dark/70 mb-1">Location / City</label>
                                <input type="text" x-model="resume.personal.location" class="w-full bg-primary-bg border border-card-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:border-accent-blue outline-none" placeholder="Patna, Bihar">
                            </div>
                        </div>
                    </div>

                    {{-- Summary --}}
                    <div class="bg-card-bg border border-card-border rounded-2xl p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-text-main mb-3 border-b border-card-border pb-2">
                            <i class="fas fa-align-left text-accent-blue mr-2"></i> Professional Summary / Objective
                        </h2>
                        <textarea x-model="resume.summary" rows="4" class="w-full bg-primary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:border-accent-blue outline-none" placeholder="Summarize your strengths, achievements, and career goal..."></textarea>
                    </div>

                    {{-- Experience --}}
                    <div class="bg-card-bg border border-card-border rounded-2xl p-6 shadow-sm">
                        <div class="flex justify-between items-center mb-4 border-b border-card-border pb-2">
                            <h2 class="text-lg font-bold text-text-main"><i class="fas fa-briefcase text-accent-blue mr-2"></i> Experience / Internships</h2>
                            <button type="button" @click="addExperience()" class="text-xs font-bold bg-accent-blue/10 text-accent-blue px-3 py-1.5 rounded-lg hover:bg-accent-blue hover:text-white transition-colors cursor-pointer">+ Add</button>
                        </div>
                        <template x-for="(exp, index) in resume.experience" :key="index">
                            <div class="p-4 border border-card-border rounded-xl mb-3 bg-primary-bg/50 relative">
                                <button type="button" @click="removeExperience(index)" class="absolute top-3 right-3 text-red-400 hover:text-red-500 cursor-pointer"><i class="fas fa-trash"></i></button>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">Role / Title</label>
                                        <input type="text" x-model="exp.title" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">Organization / School</label>
                                        <input type="text" x-model="exp.company" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">Start Date</label>
                                        <input type="text" x-model="exp.startDate" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">End Date</label>
                                        <input type="text" x-model="exp.endDate" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none">
                                    </div>
                                </div>
                                <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">Description / Highlights</label>
                                <textarea x-model="exp.description" rows="3" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none"></textarea>
                            </div>
                        </template>
                    </div>

                    {{-- Education --}}
                    <div class="bg-card-bg border border-card-border rounded-2xl p-6 shadow-sm">
                        <div class="flex justify-between items-center mb-4 border-b border-card-border pb-2">
                            <h2 class="text-lg font-bold text-text-main"><i class="fas fa-graduation-cap text-accent-blue mr-2"></i> Education</h2>
                            <button type="button" @click="addEducation()" class="text-xs font-bold bg-accent-blue/10 text-accent-blue px-3 py-1.5 rounded-lg hover:bg-accent-blue hover:text-white transition-colors cursor-pointer">+ Add</button>
                        </div>
                        <template x-for="(edu, index) in resume.education" :key="index">
                            <div class="p-4 border border-card-border rounded-xl mb-3 bg-primary-bg/50 relative">
                                <button type="button" @click="removeEducation(index)" class="absolute top-3 right-3 text-red-400 hover:text-red-500 cursor-pointer"><i class="fas fa-trash"></i></button>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">Degree / Course</label>
                                        <input type="text" x-model="edu.degree" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">University / School</label>
                                        <input type="text" x-model="edu.institution" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">Year</label>
                                        <input type="text" x-model="edu.year" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">Grade / Score</label>
                                        <input type="text" x-model="edu.grade" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Skills --}}
                    <div class="bg-card-bg border border-card-border rounded-2xl p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-text-main mb-3 border-b border-card-border pb-2"><i class="fas fa-star text-accent-blue mr-2"></i> Skills</h2>
                        <div class="flex gap-2 mb-3">
                            <input type="text" x-model="newSkill" @keydown.enter.prevent="addSkill()" class="flex-1 bg-primary-bg border border-card-border rounded-lg px-4 py-2 text-sm text-text-main outline-none" placeholder="E.g. Lesson Planning, Python, React, MS Office...">
                            <button type="button" @click="addSkill()" class="px-4 py-2 bg-accent-blue text-white rounded-lg font-bold cursor-pointer"><i class="fas fa-plus"></i></button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="(skill, index) in resume.skills" :key="index">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-secondary-bg border border-card-border rounded-full text-xs font-semibold text-text-main">
                                    <span x-text="skill"></span>
                                    <button type="button" @click="removeSkill(index)" class="text-text-dark/50 hover:text-red-400 cursor-pointer"><i class="fas fa-times"></i></button>
                                </span>
                            </template>
                        </div>
                    </div>

                    {{-- Certifications / Projects --}}
                    <div class="bg-card-bg border border-card-border rounded-2xl p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-text-main mb-3 border-b border-card-border pb-2"><i class="fas fa-certificate text-accent-blue mr-2"></i> Certifications &amp; Projects (Optional)</h2>
                        <div class="flex gap-2 mb-3">
                            <input type="text" x-model="newCertification" @keydown.enter.prevent="addCertification()" class="flex-1 bg-primary-bg border border-card-border rounded-lg px-4 py-2 text-sm text-text-main outline-none" placeholder="E.g. CTET Qualified, AWS Cloud Practitioner, Smart Attendance Project...">
                            <button type="button" @click="addCertification()" class="px-4 py-2 bg-accent-blue text-white rounded-lg font-bold cursor-pointer"><i class="fas fa-plus"></i></button>
                        </div>
                        <div class="space-y-1.5">
                            <template x-for="(cert, index) in resume.certifications" :key="index">
                                <div class="flex items-center justify-between gap-2 px-3 py-2 bg-secondary-bg border border-card-border rounded-xl text-xs text-text-main">
                                    <span x-text="cert"></span>
                                    <button type="button" @click="removeCertification(index)" class="text-text-dark/50 hover:text-red-400 cursor-pointer"><i class="fas fa-times"></i></button>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>

                {{-- RIGHT: REAL-TIME A4 PREVIEW PANEL --}}
                <div class="w-full lg:w-[55%] lg:h-[calc(100vh-230px)] lg:overflow-y-auto rounded-2xl shadow-2xl bg-slate-200 p-4 sm:p-6 hide-scrollbar">
                    <div class="bg-white mx-auto min-h-[1050px] w-full max-w-[794px] shadow-md border border-slate-300 overflow-hidden text-slate-800"
                         :style="`font-family: ${activeTemplateMeta().font === 'serif' ? 'Georgia, Times New Roman, serif' : 'Inter, Outfit, Arial, sans-serif'}`">

                        {{-- SIDEBAR LAYOUT (corporate_sidebar, tech_modern_split) --}}
                        <template x-if="activeTemplateMeta().layout === 'sidebar'">
                            <div class="grid grid-cols-12 min-h-[1050px]">
                                <div class="col-span-4 p-6 text-white space-y-6" :style="`background: ${activeTemplateMeta().primary}`">
                                    <div class="text-center space-y-3 pb-4 border-b border-white/15">
                                        <template x-if="resume.personal.photo">
                                            <img :src="resume.personal.photo" class="w-24 h-24 rounded-2xl object-cover mx-auto border-2 border-white/30 shadow">
                                        </template>
                                        <template x-if="!resume.personal.photo">
                                            <div class="w-20 h-20 rounded-2xl mx-auto flex items-center justify-center text-2xl font-extrabold"
                                                 :style="`background: ${activeTemplateMeta().secondary}; color: #fff`"
                                                 x-text="(resume.personal.name || 'Y')[0]"></div>
                                        </template>
                                        <div>
                                            <h1 class="text-xl font-bold leading-tight" x-text="resume.personal.name || 'Your Name'"></h1>
                                            <p class="text-xs opacity-85 mt-1 font-medium" :style="`color: ${activeTemplateMeta().secondary}`" x-text="resume.personal.title || 'Designation'"></p>
                                        </div>
                                    </div>

                                    <div class="space-y-2 text-xs">
                                        <h3 class="text-[10px] font-bold uppercase tracking-widest opacity-70">Contact</h3>
                                        <div x-show="resume.personal.email" class="break-all opacity-90"><i class="fas fa-envelope mr-1.5 opacity-75"></i><span x-text="resume.personal.email"></span></div>
                                        <div x-show="resume.personal.phone" class="opacity-90"><i class="fas fa-phone mr-1.5 opacity-75"></i><span x-text="resume.personal.phone"></span></div>
                                        <div x-show="resume.personal.location" class="opacity-90"><i class="fas fa-map-marker-alt mr-1.5 opacity-75"></i><span x-text="resume.personal.location"></span></div>
                                    </div>

                                    <div class="space-y-2" x-show="resume.skills.length > 0">
                                        <h3 class="text-[10px] font-bold uppercase tracking-widest opacity-70">Key Skills</h3>
                                        <div class="flex flex-wrap gap-1.5">
                                            <template x-for="(skill, i) in resume.skills" :key="'sb-sk-'+i">
                                                <span class="px-2 py-0.5 rounded bg-white/15 text-white text-[11px] font-medium" x-text="skill"></span>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="space-y-1.5 text-xs" x-show="resume.certifications.length > 0">
                                        <h3 class="text-[10px] font-bold uppercase tracking-widest opacity-70">Certifications</h3>
                                        <template x-for="(cert, i) in resume.certifications" :key="'sb-ct-'+i">
                                            <div class="text-[11px] opacity-90">• <span x-text="cert"></span></div>
                                        </template>
                                    </div>
                                </div>

                                <div class="col-span-8 p-8 space-y-6 bg-white">
                                    <div x-show="resume.summary">
                                        <h3 class="text-xs font-extrabold uppercase tracking-widest border-b pb-1.5 mb-2.5" :style="`color: ${activeTemplateMeta().primary}; border-color: ${activeTemplateMeta().secondary}`">Professional Profile</h3>
                                        <p class="text-xs leading-relaxed text-slate-700 whitespace-pre-wrap" x-text="resume.summary"></p>
                                    </div>

                                    <div x-show="resume.experience.length > 0">
                                        <h3 class="text-xs font-extrabold uppercase tracking-widest border-b pb-1.5 mb-3" :style="`color: ${activeTemplateMeta().primary}; border-color: ${activeTemplateMeta().secondary}`">Experience</h3>
                                        <template x-for="(exp, i) in resume.experience" :key="'sb-ex-'+i">
                                            <div class="mb-4">
                                                <div class="flex justify-between items-baseline">
                                                    <h4 class="font-bold text-sm text-slate-900" x-text="exp.title || 'Role'"></h4>
                                                    <span class="text-[11px] font-semibold text-slate-500"><span x-text="exp.startDate"></span> - <span x-text="exp.endDate"></span></span>
                                                </div>
                                                <div class="text-xs font-semibold mb-1" :style="`color: ${activeTemplateMeta().secondary}`" x-text="exp.company || 'Organization'"></div>
                                                <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-wrap" x-text="exp.description"></p>
                                            </div>
                                        </template>
                                    </div>

                                    <div x-show="resume.education.length > 0">
                                        <h3 class="text-xs font-extrabold uppercase tracking-widest border-b pb-1.5 mb-3" :style="`color: ${activeTemplateMeta().primary}; border-color: ${activeTemplateMeta().secondary}`">Education</h3>
                                        <template x-for="(edu, i) in resume.education" :key="'sb-ed-'+i">
                                            <div class="mb-3">
                                                <div class="flex justify-between items-baseline">
                                                    <h4 class="font-bold text-sm text-slate-900" x-text="edu.degree || 'Degree'"></h4>
                                                    <span class="text-[11px] text-slate-500" x-text="edu.year"></span>
                                                </div>
                                                <div class="flex justify-between text-xs text-slate-700">
                                                    <span x-text="edu.institution || 'Institution'"></span>
                                                    <span class="font-semibold" x-text="edu.grade"></span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- BANNER & CLASSIC SINGLE-COLUMN LAYOUTS --}}
                        <template x-if="activeTemplateMeta().layout !== 'sidebar'">
                            <div>
                                {{-- Header --}}
                                <div :style="activeTemplateMeta().layout === 'banner'
                                        ? `background: ${activeTemplateMeta().primary}; color: #ffffff; border-bottom: 4px solid ${activeTemplateMeta().secondary}`
                                        : `border-bottom: 2px solid ${activeTemplateMeta().primary}; color: #111827`"
                                     class="p-8 sm:p-10 flex items-center gap-6"
                                     :class="resume.personal.photo ? 'flex-row text-left' : (activeTemplateMeta().layout === 'classic' && activeTemplateMeta().font === 'serif' ? 'flex-col text-center' : 'flex-col text-left items-start')">
                                    <template x-if="resume.personal.photo">
                                        <img :src="resume.personal.photo" class="w-24 h-24 rounded-xl object-cover border-2 border-white/40 shadow shrink-0">
                                    </template>
                                    <div class="flex-1">
                                        <h1 class="text-3xl font-extrabold" x-text="resume.personal.name || 'Your Name'"></h1>
                                        <p class="text-base font-medium mt-1 opacity-90"
                                           :style="activeTemplateMeta().layout === 'classic' ? `color: ${activeTemplateMeta().primary}` : 'color: #e2e8f0'"
                                           x-text="resume.personal.title || 'Professional Title'"></p>
                                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs mt-3 opacity-85"
                                             :class="!resume.personal.photo && activeTemplateMeta().layout === 'classic' && activeTemplateMeta().font === 'serif' ? 'justify-center' : 'justify-start'">
                                            <span x-show="resume.personal.email"><i class="fas fa-envelope mr-1"></i><span x-text="resume.personal.email"></span></span>
                                            <span x-show="resume.personal.phone"><i class="fas fa-phone mr-1"></i><span x-text="resume.personal.phone"></span></span>
                                            <span x-show="resume.personal.location"><i class="fas fa-map-marker-alt mr-1"></i><span x-text="resume.personal.location"></span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-8 sm:p-10 space-y-6">
                                    {{-- Summary --}}
                                    <div x-show="resume.summary">
                                        <h3 class="text-xs font-extrabold uppercase tracking-widest border-b pb-1 mb-2.5"
                                            :style="`color: ${activeTemplateMeta().primary}; border-color: #d1d5db`"
                                            x-text="activeTemplateMeta().order === 'education_first' ? 'Career Objective' : 'Professional Summary'"></h3>
                                        <p class="text-sm leading-relaxed text-slate-700 whitespace-pre-wrap" x-text="resume.summary"></p>
                                    </div>

                                    {{-- Education FIRST for Freshers --}}
                                    <template x-if="activeTemplateMeta().order === 'education_first'">
                                        <div class="space-y-6">
                                            <div x-show="resume.education.length > 0">
                                                <h3 class="text-xs font-extrabold uppercase tracking-widest border-b pb-1 mb-3" :style="`color: ${activeTemplateMeta().primary}; border-color: #d1d5db`">Education &amp; Academics</h3>
                                                <template x-for="(edu, i) in resume.education" :key="'fr-ed-'+i">
                                                    <div class="mb-3">
                                                        <div class="flex justify-between items-baseline">
                                                            <h4 class="font-bold text-sm text-slate-900" x-text="edu.degree || 'Degree'"></h4>
                                                            <span class="text-xs font-semibold text-slate-500" x-text="edu.year"></span>
                                                        </div>
                                                        <div class="flex justify-between text-xs text-slate-700">
                                                            <span x-text="edu.institution || 'Institution'"></span>
                                                            <span class="font-semibold" x-text="edu.grade"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <div x-show="resume.skills.length > 0">
                                                <h3 class="text-xs font-extrabold uppercase tracking-widest border-b pb-1 mb-2.5" :style="`color: ${activeTemplateMeta().primary}; border-color: #d1d5db`">Core Skills</h3>
                                                <div class="flex flex-wrap gap-1.5">
                                                    <template x-for="(skill, i) in resume.skills" :key="'fr-sk-'+i">
                                                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold border"
                                                              :style="`border-color: ${activeTemplateMeta().primary}33; background: ${activeTemplateMeta().primary}10; color: ${activeTemplateMeta().primary}`"
                                                              x-text="skill"></span>
                                                    </template>
                                                </div>
                                            </div>

                                            <div x-show="resume.experience.length > 0">
                                                <h3 class="text-xs font-extrabold uppercase tracking-widest border-b pb-1 mb-3" :style="`color: ${activeTemplateMeta().primary}; border-color: #d1d5db`">Experience &amp; Internships</h3>
                                                <template x-for="(exp, i) in resume.experience" :key="'fr-ex-'+i">
                                                    <div class="mb-4">
                                                        <div class="flex justify-between items-baseline">
                                                            <h4 class="font-bold text-sm text-slate-900" x-text="exp.title || 'Role'"></h4>
                                                            <span class="text-xs font-semibold text-slate-500"><span x-text="exp.startDate"></span> - <span x-text="exp.endDate"></span></span>
                                                        </div>
                                                        <div class="text-xs font-semibold text-slate-600 mb-1" x-text="exp.company || 'Organization'"></div>
                                                        <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-wrap" x-text="exp.description"></p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Experience FIRST for Corporate / Tech --}}
                                    <template x-if="activeTemplateMeta().order !== 'education_first'">
                                        <div class="space-y-6">
                                            <div x-show="resume.experience.length > 0">
                                                <h3 class="text-xs font-extrabold uppercase tracking-widest border-b pb-1 mb-3" :style="`color: ${activeTemplateMeta().primary}; border-color: #d1d5db`">Work Experience</h3>
                                                <template x-for="(exp, i) in resume.experience" :key="'cp-ex-'+i">
                                                    <div class="mb-4">
                                                        <div class="flex justify-between items-baseline">
                                                            <h4 class="font-bold text-sm text-slate-900" x-text="exp.title || 'Role'"></h4>
                                                            <span class="text-xs font-semibold text-slate-500"><span x-text="exp.startDate"></span> - <span x-text="exp.endDate"></span></span>
                                                        </div>
                                                        <div class="text-xs font-semibold text-slate-600 mb-1" x-text="exp.company || 'Organization'"></div>
                                                        <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-wrap" x-text="exp.description"></p>
                                                    </div>
                                                </template>
                                            </div>

                                            <div x-show="resume.education.length > 0">
                                                <h3 class="text-xs font-extrabold uppercase tracking-widest border-b pb-1 mb-3" :style="`color: ${activeTemplateMeta().primary}; border-color: #d1d5db`">Education</h3>
                                                <template x-for="(edu, i) in resume.education" :key="'cp-ed-'+i">
                                                    <div class="mb-3">
                                                        <div class="flex justify-between items-baseline">
                                                            <h4 class="font-bold text-sm text-slate-900" x-text="edu.degree || 'Degree'"></h4>
                                                            <span class="text-xs font-semibold text-slate-500" x-text="edu.year"></span>
                                                        </div>
                                                        <div class="flex justify-between text-xs text-slate-700">
                                                            <span x-text="edu.institution || 'Institution'"></span>
                                                            <span class="font-semibold" x-text="edu.grade"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <div x-show="resume.skills.length > 0">
                                                <h3 class="text-xs font-extrabold uppercase tracking-widest border-b pb-1 mb-2.5" :style="`color: ${activeTemplateMeta().primary}; border-color: #d1d5db`">Skills</h3>
                                                <div class="flex flex-wrap gap-1.5">
                                                    <template x-for="(skill, i) in resume.skills" :key="'cp-sk-'+i">
                                                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold border"
                                                              :style="activeTemplateMeta().font === 'mono_meta'
                                                                ? `font-family: 'Fira Code', monospace; border-color: ${activeTemplateMeta().secondary}; background: #ecfdf5; color: ${activeTemplateMeta().primary}`
                                                                : 'border-color: #e5e7eb; background: #f9fafb; color: #374151'"
                                                              x-text="skill"></span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Certifications / Projects --}}
                                    <div x-show="resume.certifications.length > 0">
                                        <h3 class="text-xs font-extrabold uppercase tracking-widest border-b pb-1 mb-2.5" :style="`color: ${activeTemplateMeta().primary}; border-color: #d1d5db`">Certifications &amp; Projects</h3>
                                        <div class="space-y-1 text-xs text-slate-700">
                                            <template x-for="(cert, i) in resume.certifications" :key="'cert-'+i">
                                                <div>• <span x-text="cert"></span></div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    function resumeBuilder() {
        const prefillData = @json($prefillData);
        const templates = @json($templates);
        const categories = @json($categories);
        const initialTemplate = @json($initialTemplate);
        const draftStorageKey = 'vpa_resume_builder_draft_{{ auth()->id() ?? 'guest' }}';

        function buildSampleData() {
            const base = {
                template: initialTemplate || 'fresher_starter',
                personal: {
                    name: '{{ auth()->check() ? addslashes(auth()->user()->name) : '' }}',
                    title: 'Teacher / Graduate Candidate',
                    email: '{{ auth()->check() ? addslashes(auth()->user()->email) : '' }}',
                    phone: '',
                    location: '{{ auth()->check() && auth()->user()->profile ? addslashes((string) auth()->user()->profile->address) : '' }}',
                    photo: '',
                },
                summary: 'Dedicated professional with a strong academic foundation and a commitment to continuous learning, teamwork, and measurable results.',
                experience: [
                    {
                        title: 'Mathematics Teacher',
                        company: 'Delhi Public School',
                        startDate: 'April 2021',
                        endDate: 'Present',
                        description: '• Planned engaging curriculum modules and student assessments.\n• Improved class performance through interactive problem-solving sessions.',
                    }
                ],
                education: [
                    {
                        degree: 'Bachelor of Education (B.Ed)',
                        institution: 'Patna University',
                        year: '2018 - 2020',
                        grade: '85%',
                    }
                ],
                skills: ['Curriculum Development', 'Classroom Management', 'Lesson Planning', 'Communication', 'MS Office'],
                certifications: ['CTET Qualified'],
            };

            if (prefillData) {
                return {
                    template: initialTemplate || 'corporate_classic',
                    personal: Object.assign({}, base.personal, prefillData.personal || {}),
                    summary: prefillData.summary || base.summary,
                    experience: Array.isArray(prefillData.experience) && prefillData.experience.length ? prefillData.experience : base.experience,
                    education: Array.isArray(prefillData.education) && prefillData.education.length ? prefillData.education : base.education,
                    skills: Array.isArray(prefillData.skills) ? prefillData.skills : base.skills,
                    certifications: Array.isArray(prefillData.certifications) ? prefillData.certifications : [],
                };
            }

            return base;
        }

        function loadInitialResume() {
            const sample = buildSampleData();
            try {
                const raw = localStorage.getItem(draftStorageKey);
                if (raw) {
                    const parsed = JSON.parse(raw);
                    if (parsed && typeof parsed === 'object') {
                        return {
                            template: initialTemplate || parsed.template || sample.template,
                            personal: Object.assign({}, sample.personal, parsed.personal || {}),
                            summary: parsed.summary !== undefined ? parsed.summary : sample.summary,
                            experience: Array.isArray(parsed.experience) ? parsed.experience : sample.experience,
                            education: Array.isArray(parsed.education) ? parsed.education : sample.education,
                            skills: Array.isArray(parsed.skills) ? parsed.skills : sample.skills,
                            certifications: Array.isArray(parsed.certifications) ? parsed.certifications : sample.certifications,
                        };
                    }
                }
            } catch (e) {}

            return sample;
        }

        return {
            step: initialTemplate ? 'editor' : 'gallery',
            selectedCategory: 'All',
            templates,
            categories,
            newSkill: '',
            newCertification: '',
            resume: loadInitialResume(),

            init() {
                this.$watch('resume', (val) => {
                    try {
                        const snapshot = JSON.parse(JSON.stringify(val));
                        if (snapshot.personal && snapshot.personal.photo && snapshot.personal.photo.length > 400000) {
                            snapshot.personal.photo = '';
                        }
                        localStorage.setItem(draftStorageKey, JSON.stringify(snapshot));
                    } catch (e) {}
                }, { deep: true });
            },

            filteredTemplates() {
                if (this.selectedCategory === 'All') {
                    return this.templates;
                }
                return this.templates.filter(t => t.category === this.selectedCategory);
            },

            activeTemplateMeta() {
                return this.templates.find(t => t.id === this.resume.template) || this.templates[0];
            },

            selectTemplate(templateId) {
                // Only switch active template ID; ALL entered candidate information stays untouched.
                this.resume.template = templateId;
                this.step = 'editor';
            },

            openGallery() {
                this.step = 'gallery';
            },

            resetToSample() {
                if (!confirm('Reset all resume fields back to starter sample data?')) {
                    return;
                }
                const currentTpl = this.resume.template;
                const sample = buildSampleData();
                sample.template = currentTpl;
                this.resume = sample;
            },

            uploadPhoto(e) {
                const file = e.target.files[0];
                if (!file) return;
                if (file.size > 2 * 1024 * 1024) {
                    alert('Image size must be less than 2MB');
                    return;
                }
                const reader = new FileReader();
                reader.onload = (event) => {
                    this.resume.personal.photo = event.target.result;
                };
                reader.readAsDataURL(file);
            },

            removePhoto() {
                this.resume.personal.photo = '';
                const fileInput = document.getElementById('photoUploadInput');
                if (fileInput) fileInput.value = '';
            },

            addExperience() {
                this.resume.experience.unshift({ title: '', company: '', startDate: '', endDate: '', description: '' });
            },
            removeExperience(index) {
                this.resume.experience.splice(index, 1);
            },

            addEducation() {
                this.resume.education.unshift({ degree: '', institution: '', year: '', grade: '' });
            },
            removeEducation(index) {
                this.resume.education.splice(index, 1);
            },

            addSkill() {
                if (this.newSkill.trim() !== '') {
                    this.resume.skills.push(this.newSkill.trim());
                    this.newSkill = '';
                }
            },
            removeSkill(index) {
                this.resume.skills.splice(index, 1);
            },

            addCertification() {
                if (this.newCertification.trim() !== '') {
                    this.resume.certifications.push(this.newCertification.trim());
                    this.newCertification = '';
                }
            },
            removeCertification(index) {
                this.resume.certifications.splice(index, 1);
            },
        };
    }
</script>
@endsection
