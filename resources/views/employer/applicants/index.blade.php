@extends('layouts.app')

@section('content')
@include('employer.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-text-main">Candidates</h1>
            <p class="text-sm text-text-dark/50 mt-0.5">View candidates whose applications have been forwarded to you for an interview.</p>
        </div>
        <form action="{{ route('employer.applicants.index') }}" method="GET" class="flex items-center gap-2">
            <select name="job_post_id" class="bg-secondary-bg border border-card-border rounded-xl px-4 py-2 text-sm text-text-main focus:border-accent-yellow focus:outline-none" onchange="this.form.submit()">
                <option value="">All Jobs</option>
                @foreach($myJobs as $job)
                    <option value="{{ $job->id }}" {{ request('job_post_id') == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                @endforeach
            </select>
            @if(request('job_post_id'))
                <a href="{{ route('employer.applicants.index') }}" class="text-text-dark/50 hover:text-red-400 text-sm font-bold ml-2">Clear</a>
            @endif
        </form>
    </div>

    <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-secondary-bg/50 border-b border-card-border text-xs text-text-dark uppercase tracking-wider font-bold">
                    <th class="py-4 px-6">Candidate Details</th>
                    <th class="py-4 px-6">Applied For</th>
                    <th class="py-4 px-6">Experience & Qual.</th>
                    <th class="py-4 px-6">Status</th>
                    <th class="py-4 px-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-card-border">
                @forelse($applications as $app)
                <tr class="hover:bg-secondary-bg/30 transition-colors group">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            @if($app->candidate->profile && $app->candidate->profile->profile_photo_path)
                                <img src="{{ asset('storage/' . $app->candidate->profile->profile_photo_path) }}" alt="{{ $app->candidate->name }}" class="w-10 h-10 rounded-xl object-cover border border-accent-yellow/30">
                            @else
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-accent-yellow/20 to-accent-yellow/10 text-accent-yellow flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($app->candidate->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="font-bold text-text-main">{{ $app->candidate->name }}</div>
                                <div class="text-xs text-text-dark/60 mt-0.5 flex flex-col gap-0.5">
                                    <span><i class="fas fa-envelope text-[10px] w-3"></i> {{ $app->candidate->email }}</span>
                                    <span><i class="fas fa-phone-alt text-[10px] w-3"></i> {{ $app->candidate->phone }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-semibold text-text-main">{{ $app->jobPost->title }}</div>
                        <div class="text-xs text-text-dark/50">Forwarded on {{ $app->updated_at->format('M d, Y') }}</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-sm font-medium text-text-main">{{ $app->candidate->profile->experience_years ?? '0' }} Years</div>
                        <div class="text-xs text-text-dark/50">{{ $app->candidate->profile->highestQualification->name ?? 'N/A' }}</div>
                    </td>
                    <td class="py-4 px-6">
                        @if($app->status === 'shortlisted')
                            <span class="bg-accent-yellow/10 text-accent-yellow px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider flex items-center gap-1 w-max">
                                <i class="fas fa-hourglass-half"></i> Pending Interview
                            </span>
                        @elseif($app->status === 'hired')
                            <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider flex items-center gap-1 w-max">
                                <i class="fas fa-check-circle"></i> Selected
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-right">
                        @php
                            $candidateData = [
                                'name' => $app->candidate->name,
                                'email' => $app->candidate->email,
                                'phone' => $app->candidate->phone,
                                'photo' => $app->candidate->profile?->profile_photo_path ? asset('storage/' . $app->candidate->profile->profile_photo_path) : null,
                                'gender' => $app->candidate->profile?->gender ?? 'N/A',
                                'dob' => $app->candidate->profile?->date_of_birth ? $app->candidate->profile->date_of_birth->format('M d, Y') : 'N/A',
                                'address' => $app->candidate->profile?->address ?? 'N/A',
                                'category' => $app->candidate->profile?->category?->name ?? 'N/A',
                                'subject' => $app->candidate->profile?->subject?->name ?? 'N/A',
                                'highest_qual' => $app->candidate->profile?->highestQualification?->name ?? 'N/A',
                                'other_quals' => $app->candidate->profile?->other_qualifications ?? null,
                                'experience' => ($app->candidate->profile?->experience_years ?? 0) . ' Years',
                                'current_salary' => $app->candidate->profile?->current_salary ?? 'N/A',
                                'expected_salary' => $app->candidate->profile?->expected_salary ?? 'N/A',
                                'residential_preference' => $app->candidate->profile?->residential_preference ?? 'N/A',
                                'availability' => $app->candidate->profile?->availability_to_join ?? 'N/A',
                                'current_school' => $app->candidate->profile?->current_school ?? 'N/A',
                                'resume_url' => $app->candidate->profile?->resume_path ? asset('storage/' . $app->candidate->profile->resume_path) : null,
                                'job_title' => $app->jobPost->title
                            ];
                        @endphp
                        <button type="button" onclick='openEmployerCandidateModal(@json($candidateData))' class="px-3.5 py-2 bg-accent-yellow/10 text-accent-yellow hover:bg-accent-yellow hover:text-[#031b4e] rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1.5 shadow-sm">
                            <i class="fas fa-eye"></i> View Profile
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-16 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-secondary-bg text-text-dark/20 flex items-center justify-center text-3xl mx-auto mb-4 border border-card-border">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <p class="text-text-main font-bold text-lg mb-1">No candidates forwarded yet</p>
                        <p class="text-text-dark/40 text-sm">When the admin forwards a candidate for your jobs, they will appear here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($applications->hasPages())
    <div class="mt-6 flex justify-end">
        {{ $applications->links('pagination::tailwind') }}
    </div>
    @endif

</div>

<!-- Candidate Details Modal for Employer -->
<div id="employerCandidateModal" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-card-bg border border-card-border rounded-2xl max-w-2xl w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="closeEmployerCandidateModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-secondary-bg text-text-dark/60 hover:text-text-main flex items-center justify-center transition-colors">
            <i class="fas fa-times"></i>
        </button>

        <!-- Candidate Header -->
        <div class="flex items-center gap-4 pb-4 border-b border-card-border mb-6">
            <div id="candModalPhotoContainer" class="w-16 h-16 rounded-2xl overflow-hidden bg-accent-yellow/10 border border-accent-yellow/30 flex items-center justify-center text-accent-yellow font-bold text-2xl flex-shrink-0">
                <span id="candModalAvatar"></span>
                <img id="candModalPhoto" class="w-full h-full object-cover hidden" src="" alt="Candidate Photo">
            </div>
            <div>
                <h3 class="text-xl font-bold text-text-main" id="candModalName">Candidate Name</h3>
                <p class="text-xs text-accent-yellow font-semibold mt-0.5">Applied For: <span id="candModalJobTitle" class="text-text-main"></span></p>
                <div class="flex items-center gap-4 text-xs text-text-dark/60 mt-1">
                    <span><i class="fas fa-envelope text-accent-yellow/80"></i> <span id="candModalEmail"></span></span>
                    <span><i class="fas fa-phone-alt text-accent-yellow/80"></i> <span id="candModalPhone"></span></span>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="space-y-4">
            <h4 class="text-xs font-bold text-accent-yellow uppercase tracking-wider">Professional & Academic Profile</h4>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <div class="bg-secondary-bg/50 p-3 rounded-xl border border-card-border">
                    <div class="text-[10px] text-text-dark/50 uppercase font-bold mb-0.5">Category</div>
                    <div class="text-xs font-bold text-text-main" id="candModalCategory"></div>
                </div>
                <div class="bg-secondary-bg/50 p-3 rounded-xl border border-card-border">
                    <div class="text-[10px] text-text-dark/50 uppercase font-bold mb-0.5">Subject</div>
                    <div class="text-xs font-bold text-text-main" id="candModalSubject"></div>
                </div>
                <div class="bg-secondary-bg/50 p-3 rounded-xl border border-card-border">
                    <div class="text-[10px] text-text-dark/50 uppercase font-bold mb-0.5">Highest Qualification</div>
                    <div class="text-xs font-bold text-text-main" id="candModalHighestQual"></div>
                </div>
                <div class="bg-secondary-bg/50 p-3 rounded-xl border border-card-border">
                    <div class="text-[10px] text-text-dark/50 uppercase font-bold mb-0.5">Experience</div>
                    <div class="text-xs font-bold text-text-main" id="candModalExperience"></div>
                </div>
                <div class="bg-secondary-bg/50 p-3 rounded-xl border border-card-border">
                    <div class="text-[10px] text-text-dark/50 uppercase font-bold mb-0.5">Current Salary</div>
                    <div class="text-xs font-bold text-text-main" id="candModalCurrentSalary"></div>
                </div>
                <div class="bg-secondary-bg/50 p-3 rounded-xl border border-card-border">
                    <div class="text-[10px] text-text-dark/50 uppercase font-bold mb-0.5">Expected Salary</div>
                    <div class="text-xs font-bold text-text-main" id="candModalExpectedSalary"></div>
                </div>
                <div class="bg-secondary-bg/50 p-3 rounded-xl border border-card-border">
                    <div class="text-[10px] text-text-dark/50 uppercase font-bold mb-0.5">School Preference</div>
                    <div class="text-xs font-bold text-text-main capitalize" id="candModalSchoolPref"></div>
                </div>
                <div class="bg-secondary-bg/50 p-3 rounded-xl border border-card-border">
                    <div class="text-[10px] text-text-dark/50 uppercase font-bold mb-0.5">Availability</div>
                    <div class="text-xs font-bold text-text-main" id="candModalAvailability"></div>
                </div>
                <div class="bg-secondary-bg/50 p-3 rounded-xl border border-card-border">
                    <div class="text-[10px] text-text-dark/50 uppercase font-bold mb-0.5">Current School</div>
                    <div class="text-xs font-bold text-text-main" id="candModalCurrentSchool"></div>
                </div>
            </div>

            <!-- Other Qualifications -->
            <div id="candModalOtherQualsContainer" class="hidden">
                <h4 class="text-xs font-bold text-accent-yellow uppercase tracking-wider mb-2">Other Qualifications & Certifications</h4>
                <div id="candModalOtherQualsBadges" class="flex flex-wrap gap-1.5 bg-secondary-bg/50 p-3 rounded-xl border border-card-border"></div>
            </div>

            <!-- Resume Action Section -->
            <div class="pt-4 border-t border-card-border flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-text-main">Candidate Resume / CV</span>
                    <p class="text-[11px] text-text-dark/50">Click below to view or download candidate's detailed resume.</p>
                </div>
                <div id="candModalResumeAction">
                    <a id="candModalResumeBtn" href="" target="_blank" class="px-5 py-2.5 bg-accent-yellow text-[#031b4e] rounded-xl text-xs font-bold hover:brightness-110 transition-all shadow-md inline-flex items-center gap-2">
                        <i class="fas fa-file-pdf text-sm"></i> View / Download Resume
                    </a>
                    <span id="candModalNoResume" class="text-xs text-red-400 font-semibold hidden"><i class="fas fa-exclamation-circle"></i> No Resume Uploaded</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openEmployerCandidateModal(data) {
        document.getElementById('candModalName').textContent = data.name || 'N/A';
        document.getElementById('candModalJobTitle').textContent = data.job_title || 'N/A';
        document.getElementById('candModalEmail').textContent = data.email || 'N/A';
        document.getElementById('candModalPhone').textContent = data.phone || 'N/A';
        
        // Photo / Avatar
        const photo = document.getElementById('candModalPhoto');
        const avatar = document.getElementById('candModalAvatar');
        if (data.photo) {
            photo.src = data.photo;
            photo.classList.remove('hidden');
            avatar.classList.add('hidden');
        } else {
            photo.classList.add('hidden');
            avatar.textContent = (data.name || 'C').charAt(0).toUpperCase();
            avatar.classList.remove('hidden');
        }

        document.getElementById('candModalCategory').textContent = data.category || 'N/A';
        document.getElementById('candModalSubject').textContent = data.subject || 'N/A';
        document.getElementById('candModalHighestQual').textContent = data.highest_qual || 'N/A';
        document.getElementById('candModalExperience').textContent = data.experience || '0 Years';
        document.getElementById('candModalCurrentSalary').textContent = data.current_salary || 'N/A';
        document.getElementById('candModalExpectedSalary').textContent = data.expected_salary || 'N/A';
        document.getElementById('candModalSchoolPref').textContent = data.residential_preference || 'N/A';
        document.getElementById('candModalAvailability').textContent = data.availability || 'N/A';
        document.getElementById('candModalCurrentSchool').textContent = data.current_school || 'N/A';

        // Other Qualifications
        const otherQualsContainer = document.getElementById('candModalOtherQualsContainer');
        const otherQualsBadges = document.getElementById('candModalOtherQualsBadges');
        otherQualsBadges.innerHTML = '';
        if (data.other_quals) {
            const list = data.other_quals.split(',').map(s => s.trim()).filter(Boolean);
            if (list.length > 0) {
                list.forEach(q => {
                    const badge = document.createElement('span');
                    badge.className = 'px-2.5 py-1 bg-accent-yellow/10 text-accent-yellow text-xs font-semibold rounded-lg border border-accent-yellow/20';
                    badge.textContent = q;
                    otherQualsBadges.appendChild(badge);
                });
                otherQualsContainer.classList.remove('hidden');
            } else {
                otherQualsContainer.classList.add('hidden');
            }
        } else {
            otherQualsContainer.classList.add('hidden');
        }

        // Resume Button
        const resumeBtn = document.getElementById('candModalResumeBtn');
        const noResume = document.getElementById('candModalNoResume');
        if (data.resume_url) {
            resumeBtn.href = data.resume_url;
            resumeBtn.classList.remove('hidden');
            noResume.classList.add('hidden');
        } else {
            resumeBtn.classList.add('hidden');
            noResume.classList.remove('hidden');
        }

        document.getElementById('employerCandidateModal').classList.remove('hidden');
    }

    function closeEmployerCandidateModal() {
        document.getElementById('employerCandidateModal').classList.add('hidden');
    }
</script>

    @if($applications->hasPages())
    <div class="mt-6 flex justify-end">
        {{ $applications->links('pagination::tailwind') }}
    </div>
    @endif

</div>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
