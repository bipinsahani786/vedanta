@extends('layouts.app')

@section('content')
@include('employer.partials.nav')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-text-main">Post a New Job</h1>
        <p class="text-sm text-text-dark/50 mt-0.5">Fill in the details to post a new job requirement. Once approved, it will be visible to candidates.</p>
    </div>

    <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl reveal">
        <div class="p-8">
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-500 px-4 py-3 rounded-xl mb-6 flex items-start gap-3">
                    <i class="fas fa-check-circle mt-1"></i>
                    <div>
                        <p class="font-bold text-sm">Success!</p>
                        <p class="text-xs mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl mb-6 flex items-start gap-3">
                    <i class="fas fa-exclamation-circle mt-1"></i>
                    <div>
                        <p class="font-bold text-sm">Please fix the following errors:</p>
                        <ul class="list-disc pl-5 text-xs mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('employer.jobs.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Institution Details -->
                <div>
                    <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2 border-b border-card-border pb-2"><i class="fas fa-university text-accent-yellow"></i> Institution Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Institution/School Name</label>
                            <input type="text" name="school_name" value="{{ old('school_name', $profile?->school_name ?? '') }}" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Contact Person</label>
                            <input type="text" name="contact_person" value="{{ old('contact_person', $profile?->contact_person ?? auth()->user()->name) }}" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Contact Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Job Details Repeater -->
                <div class="pt-2" x-data="jobRepeater()">
                    <div class="flex justify-between items-center mb-4 border-b border-card-border pb-2">
                        <h3 class="text-lg font-bold text-text-main flex items-center gap-2"><i class="fas fa-briefcase text-accent-yellow"></i> Job Requirements</h3>
                    </div>

                    <template x-for="(job, index) in jobs" :key="job.id">
                        <div class="space-y-6 mb-6 p-6 bg-secondary-bg/20 border border-card-border rounded-2xl relative shadow-sm hover:shadow-md transition-shadow">
                            <!-- Job Number & Remove Button -->
                            <div class="flex justify-between items-center border-b border-card-border/50 pb-3 mb-2">
                                <span class="text-sm font-bold text-accent-blue tracking-wide uppercase" x-text="`Job Requirement #${index + 1}`"></span>
                                <button type="button" x-show="jobs.length > 1" @click="jobs.splice(index, 1)" class="text-red-400 hover:text-red-600 transition-colors flex items-center gap-1 text-xs font-bold uppercase" title="Remove Job">
                                    <i class="fas fa-trash-alt"></i> Remove
                                </button>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Job Title <span class="text-red-500">*</span></label>
                                <input type="text" :name="`jobs[${index}][title]`" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors" placeholder="e.g. Senior Physics Teacher">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Job Category <span class="text-red-500">*</span></label>
                                    <select :name="`jobs[${index}][category_id]`" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Subject <span class="text-red-500">*</span></label>
                                    <select :name="`jobs[${index}][subject_id]`" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                        <option value="">Select Subject</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Required Qualification <span class="text-red-500">*</span></label>
                                    <select :name="`jobs[${index}][qualification_id]`" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                        <option value="">Select Qualification</option>
                                        @foreach($qualifications as $qualification)
                                            <option value="{{ $qualification->id }}">{{ $qualification->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">State <span class="text-red-500">*</span></label>
                                    <select :name="`jobs[${index}][state_id]`" x-model="job.state_id" @change="fetchCities(job)" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">City <span class="text-red-500">*</span></label>
                                    <select :name="`jobs[${index}][city_id]`" required class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors">
                                        <option value="">Select City</option>
                                        <template x-for="city in job.cities" :key="city.id">
                                            <option :value="city.id" x-text="city.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Salary Range (Monthly)</label>
                                    <input type="text" :name="`jobs[${index}][salary_range]`" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors" placeholder="e.g. 40,000 - 60,000">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-text-dark/70 mb-2 uppercase tracking-wider">Job Description <span class="text-red-500">*</span></label>
                                <textarea :name="`jobs[${index}][description]`" required rows="4" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent-yellow transition-colors resize-none" placeholder="Describe the responsibilities and requirements..."></textarea>
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="addJob()" class="w-full mt-2 px-6 py-4 bg-secondary-bg/50 text-text-main font-bold rounded-2xl border-2 border-dashed border-card-border hover:border-accent-yellow hover:text-accent-yellow hover:bg-accent-yellow/5 transition-all flex items-center justify-center gap-2 group">
                        <i class="fas fa-plus-circle text-xl group-hover:scale-110 transition-transform"></i> Add Another Job Requirement
                    </button>
                </div>

                <div class="pt-6 border-t border-card-border text-right">
                    <a href="{{ route('employer.jobs.index') }}" class="inline-block px-6 py-3.5 bg-secondary-bg hover:bg-white/5 text-text-main rounded-xl font-bold transition-colors mr-2">Cancel</a>
                    <button type="submit" class="px-8 py-3.5 bg-accent-yellow text-[#031b4e] font-bold rounded-xl shadow-lg hover:shadow-glow-yellow hover:-translate-y-0.5 transition-all">Submit for Approval</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function jobRepeater() {
        return {
            jobs: [ { id: Date.now(), state_id: '', cities: [] } ],
            
            addJob() {
                this.jobs.push({ id: Date.now(), state_id: '', cities: [] });
            },
            
            fetchCities(job) {
                if(job.state_id) {
                    fetch(`/api/states/${job.state_id}/cities`)
                        .then(response => response.json())
                        .then(data => {
                            job.cities = data;
                        })
                        .catch(error => console.error('Error fetching cities:', error));
                } else {
                    job.cities = [];
                }
            }
        }
    }
</script>
@endpush
