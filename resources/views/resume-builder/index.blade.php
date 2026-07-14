@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="bg-primary-bg min-h-screen pt-4 pb-12" x-data="resumeBuilder()">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-text-main">Free Auto Resume Builder</h1>
                <p class="text-text-dark/60 mt-1">Create a professional teaching resume in minutes. Live preview and PDF download.</p>
            </div>
            <div>
                <form action="{{ route('resume.builder.download') }}" method="POST" id="downloadForm">
                    @csrf
                    <input type="hidden" name="resume_data" x-bind:value="JSON.stringify($data.resume)">
                    <button type="submit" class="px-6 py-3 bg-accent-yellow text-[#031b4e] font-bold rounded-xl shadow-glow-yellow hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <i class="fas fa-download"></i> Download PDF
                    </button>
                </form>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            {{-- LEFT: EDITOR PANEL --}}
            <div class="w-full lg:w-[45%] h-[calc(100vh-200px)] overflow-y-auto pr-4 hide-scrollbar">
                
                {{-- Personal Details --}}
                <div class="bg-card-bg border border-card-border rounded-2xl p-6 mb-6 shadow-sm">
                    <h2 class="text-xl font-bold text-text-main mb-4 border-b border-card-border pb-2"><i class="fas fa-user text-accent-blue mr-2"></i> Personal Details</h2>
                    
                    <!-- Photo Upload Section -->
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
                                <label for="photoUploadInput" class="px-3 py-1.5 bg-accent-blue text-white rounded-lg text-xs font-bold cursor-pointer hover:opacity-90 transition-opacity shadow-sm">
                                    <i class="fas fa-upload mr-1"></i> Choose Image
                                </label>
                                <template x-if="resume.personal.photo">
                                    <button type="button" @click="removePhoto()" class="px-3 py-1.5 bg-red-500/10 text-red-500 rounded-lg text-xs font-bold hover:bg-red-500 hover:text-white transition-colors">
                                        <i class="fas fa-trash mr-1"></i> Remove
                                    </button>
                                </template>
                            </div>
                            <p class="text-[10px] text-text-dark/40 mt-1">Recommended: Square format, Max size 2MB.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-text-dark/70 mb-1">Full Name</label>
                            <input type="text" x-model="resume.personal.name" class="w-full bg-primary-bg border border-card-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:border-accent-blue focus:ring-1 focus:ring-accent-blue outline-none" placeholder="e.g. John Doe">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-text-dark/70 mb-1">Job Title / Designation</label>
                            <input type="text" x-model="resume.personal.title" class="w-full bg-primary-bg border border-card-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:border-accent-blue outline-none" placeholder="e.g. Senior Mathematics Teacher">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-text-dark/70 mb-1">Email</label>
                            <input type="email" x-model="resume.personal.email" class="w-full bg-primary-bg border border-card-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:border-accent-blue outline-none" placeholder="john@example.com">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-text-dark/70 mb-1">Phone</label>
                            <input type="text" x-model="resume.personal.phone" class="w-full bg-primary-bg border border-card-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:border-accent-blue outline-none" placeholder="+91 9876543210">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-text-dark/70 mb-1">Location / Address</label>
                            <input type="text" x-model="resume.personal.location" class="w-full bg-primary-bg border border-card-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:border-accent-blue outline-none" placeholder="Patna, Bihar">
                        </div>
                    </div>
                </div>

                {{-- Professional Summary --}}
                <div class="bg-card-bg border border-card-border rounded-2xl p-6 mb-6 shadow-sm">
                    <h2 class="text-xl font-bold text-text-main mb-4 border-b border-card-border pb-2"><i class="fas fa-align-left text-accent-blue mr-2"></i> Professional Summary</h2>
                    <textarea x-model="resume.summary" rows="4" class="w-full bg-primary-bg border border-card-border rounded-lg px-4 py-3 text-sm text-text-main focus:border-accent-blue outline-none" placeholder="Write a brief summary of your teaching philosophy, experience, and goals..."></textarea>
                </div>

                {{-- Experience --}}
                <div class="bg-card-bg border border-card-border rounded-2xl p-6 mb-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4 border-b border-card-border pb-2">
                        <h2 class="text-xl font-bold text-text-main"><i class="fas fa-briefcase text-accent-blue mr-2"></i> Experience</h2>
                        <button type="button" @click="addExperience()" class="text-xs font-bold bg-accent-blue/10 text-accent-blue px-3 py-1.5 rounded-lg hover:bg-accent-blue hover:text-white transition-colors">+ Add</button>
                    </div>
                    
                    <template x-for="(exp, index) in resume.experience" :key="index">
                        <div class="p-4 border border-card-border rounded-xl mb-4 bg-primary-bg/50 relative">
                            <button type="button" @click="removeExperience(index)" class="absolute top-3 right-3 text-red-400 hover:text-red-500"><i class="fas fa-trash"></i></button>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">Job Title</label>
                                    <input type="text" x-model="exp.title" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none" placeholder="PGT Physics">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">School / Institution</label>
                                    <input type="text" x-model="exp.company" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none" placeholder="Delhi Public School">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">Start Date</label>
                                    <input type="text" x-model="exp.startDate" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none" placeholder="Aug 2020">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">End Date</label>
                                    <input type="text" x-model="exp.endDate" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none" placeholder="Present">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">Description</label>
                                <textarea x-model="exp.description" rows="3" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none" placeholder="Taught classes 11th and 12th..."></textarea>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Education --}}
                <div class="bg-card-bg border border-card-border rounded-2xl p-6 mb-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4 border-b border-card-border pb-2">
                        <h2 class="text-xl font-bold text-text-main"><i class="fas fa-graduation-cap text-accent-blue mr-2"></i> Education</h2>
                        <button type="button" @click="addEducation()" class="text-xs font-bold bg-accent-blue/10 text-accent-blue px-3 py-1.5 rounded-lg hover:bg-accent-blue hover:text-white transition-colors">+ Add</button>
                    </div>
                    
                    <template x-for="(edu, index) in resume.education" :key="index">
                        <div class="p-4 border border-card-border rounded-xl mb-4 bg-primary-bg/50 relative">
                            <button type="button" @click="removeEducation(index)" class="absolute top-3 right-3 text-red-400 hover:text-red-500"><i class="fas fa-trash"></i></button>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">Degree / Course</label>
                                    <input type="text" x-model="edu.degree" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none" placeholder="B.Ed. / M.Sc. Physics">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">Institution</label>
                                    <input type="text" x-model="edu.institution" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none" placeholder="Patna University">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">Year</label>
                                    <input type="text" x-model="edu.year" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none" placeholder="2018 - 2020">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-text-dark/50 uppercase tracking-wider mb-1">Grade / Percentage</label>
                                    <input type="text" x-model="edu.grade" class="w-full bg-white/5 border border-card-border rounded-md px-3 py-2 text-sm text-text-main outline-none" placeholder="85%">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Skills --}}
                <div class="bg-card-bg border border-card-border rounded-2xl p-6 mb-6 shadow-sm">
                    <h2 class="text-xl font-bold text-text-main mb-4 border-b border-card-border pb-2"><i class="fas fa-star text-accent-blue mr-2"></i> Skills</h2>
                    <div class="flex gap-2 mb-4">
                        <input type="text" x-model="newSkill" @keydown.enter.prevent="addSkill()" class="flex-1 bg-primary-bg border border-card-border rounded-lg px-4 py-2.5 text-sm text-text-main outline-none" placeholder="E.g. Lesson Planning, Smart Board...">
                        <button type="button" @click="addSkill()" class="px-4 py-2.5 bg-accent-blue text-white rounded-lg font-bold"><i class="fas fa-plus"></i></button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(skill, index) in resume.skills" :key="index">
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-secondary-bg border border-card-border rounded-full text-xs font-semibold text-text-main">
                                <span x-text="skill"></span>
                                <button type="button" @click="removeSkill(index)" class="text-text-dark/50 hover:text-red-400"><i class="fas fa-times"></i></button>
                            </span>
                        </template>
                    </div>
                </div>

            </div>

            {{-- RIGHT: LIVE PREVIEW PANEL --}}
            <div class="w-full lg:w-[55%] h-[calc(100vh-200px)] overflow-y-auto rounded-lg shadow-2xl relative bg-gray-50 hide-scrollbar" style="font-family: 'Times New Roman', Times, serif; color: #333;">
                
                {{-- A4 Paper wrapper --}}
                <div class="bg-white mx-auto min-h-[1122px] w-full max-w-[794px] p-10 sm:p-12 shadow-sm border border-gray-200">
                    
                    {{-- Header --}}
                    <div class="flex items-center gap-6 mb-8 border-b-2 border-gray-800 pb-6" :class="resume.personal.photo ? 'flex-row text-left' : 'flex-col text-center'">
                        <template x-if="resume.personal.photo">
                            <div class="relative group flex-shrink-0">
                                <img :src="resume.personal.photo" class="w-24 h-24 rounded-xl object-cover border border-gray-300 shadow-md">
                                <button type="button" @click="removePhoto()" class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity shadow-md"><i class="fas fa-times"></i></button>
                            </div>
                        </template>
                        <div class="flex-1">
                            <h1 class="text-4xl font-bold text-gray-900 mb-2" style="font-family: Arial, Helvetica, sans-serif" x-text="resume.personal.name || 'Your Name'"></h1>
                            <h2 class="text-xl text-gray-600 mb-4" x-text="resume.personal.title || 'Professional Title'"></h2>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-600" :class="resume.personal.photo ? 'justify-start' : 'justify-center'">
                                <span x-show="resume.personal.email"><i class="fas fa-envelope mr-1"></i> <span x-text="resume.personal.email"></span></span>
                                <span x-show="resume.personal.phone"><i class="fas fa-phone mr-1"></i> <span x-text="resume.personal.phone"></span></span>
                                <span x-show="resume.personal.location"><i class="fas fa-map-marker-alt mr-1"></i> <span x-text="resume.personal.location"></span></span>
                            </div>
                        </div>
                    </div>

                    {{-- Summary --}}
                    <div class="mb-8" x-show="resume.summary">
                        <h3 class="text-lg font-bold text-gray-900 uppercase tracking-widest border-b border-gray-300 pb-1 mb-3" style="font-family: Arial, Helvetica, sans-serif">Professional Summary</h3>
                        <p class="text-gray-700 text-justify leading-relaxed text-sm whitespace-pre-wrap" x-text="resume.summary"></p>
                    </div>

                    {{-- Experience --}}
                    <div class="mb-8" x-show="resume.experience.length > 0">
                        <h3 class="text-lg font-bold text-gray-900 uppercase tracking-widest border-b border-gray-300 pb-1 mb-4" style="font-family: Arial, Helvetica, sans-serif">Experience</h3>
                        <template x-for="exp in resume.experience">
                            <div class="mb-4">
                                <div class="flex justify-between items-baseline mb-1">
                                    <h4 class="font-bold text-gray-900 text-[15px]" x-text="exp.title || 'Job Title'"></h4>
                                    <span class="text-sm font-semibold text-gray-600 italic">
                                        <span x-text="exp.startDate"></span> - <span x-text="exp.endDate"></span>
                                    </span>
                                </div>
                                <div class="text-gray-800 font-medium text-sm mb-2" x-text="exp.company || 'Company Name'"></div>
                                <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap" x-text="exp.description"></p>
                            </div>
                        </template>
                    </div>

                    {{-- Education --}}
                    <div class="mb-8" x-show="resume.education.length > 0">
                        <h3 class="text-lg font-bold text-gray-900 uppercase tracking-widest border-b border-gray-300 pb-1 mb-4" style="font-family: Arial, Helvetica, sans-serif">Education</h3>
                        <template x-for="edu in resume.education">
                            <div class="mb-3">
                                <div class="flex justify-between items-baseline mb-1">
                                    <h4 class="font-bold text-gray-900 text-[15px]" x-text="edu.degree || 'Degree'"></h4>
                                    <span class="text-sm font-semibold text-gray-600 italic" x-text="edu.year"></span>
                                </div>
                                <div class="text-gray-800 text-sm flex justify-between">
                                    <span x-text="edu.institution || 'Institution'"></span>
                                    <span class="font-medium" x-text="edu.grade"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Skills --}}
                    <div x-show="resume.skills.length > 0">
                        <h3 class="text-lg font-bold text-gray-900 uppercase tracking-widest border-b border-gray-300 pb-1 mb-3" style="font-family: Arial, Helvetica, sans-serif">Skills</h3>
                        <div class="flex flex-wrap gap-x-4 gap-y-1">
                            <template x-for="skill in resume.skills">
                                <span class="text-gray-700 text-sm">• <span x-text="skill"></span></span>
                            </template>
                        </div>
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
        
        let defaultResume = {
            personal: {
                name: '{{ auth()->check() ? auth()->user()->name : '' }}',
                title: 'Teacher', 
                email: '{{ auth()->check() ? auth()->user()->email : '' }}',
                phone: '',
                location: '{{ auth()->check() && auth()->user()->profile ? auth()->user()->profile->address : '' }}',
                photo: '',
            },
            summary: 'Dedicated and experienced educator with a proven track record of creating engaging lesson plans and fostering a positive learning environment. Passionate about student development and innovative teaching methodologies.',
            experience: [
                {
                    title: 'Senior Mathematics Teacher',
                    company: 'Delhi Public School',
                    startDate: 'April 2018',
                    endDate: 'Present',
                    description: '• Developed comprehensive curriculum for 11th and 12th grade mathematics.\n• Increased student standardized test scores by 15%.\n• Integrated technology and interactive tools into daily lessons.'
                }
            ],
            education: [
                {
                    degree: 'Bachelor of Education (B.Ed)',
                    institution: 'Patna University',
                    year: '2016 - 2018',
                    grade: '85%'
                },
                {
                    degree: 'M.Sc. in Mathematics',
                    institution: 'Delhi University',
                    year: '2014 - 2016',
                    grade: '78%'
                }
            ],
            skills: ['Curriculum Development', 'Classroom Management', 'Lesson Planning', 'EdTech Integration', 'Communication']
        };

        if (prefillData) {
            defaultResume = prefillData;
        }

        return {
            newSkill: '',
            resume: defaultResume,

            uploadPhoto(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Image size must be less than 2MB');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        this.resume.personal.photo = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },

            removePhoto() {
                this.resume.personal.photo = '';
                const fileInput = document.getElementById('photoUploadInput');
                if (fileInput) {
                    fileInput.value = '';
                }
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
                if(this.newSkill.trim() !== '') {
                    this.resume.skills.push(this.newSkill.trim());
                    this.newSkill = '';
                }
            },
            removeSkill(index) {
                this.resume.skills.splice(index, 1);
            }
        }
    }
</script>
@endsection
