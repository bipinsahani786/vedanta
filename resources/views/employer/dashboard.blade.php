@extends('layouts.app')

@section('content')
{{-- Employer Navigation --}}
@include('employer.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Welcome Header --}}
    <div id="tour-emp-header" class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10 reveal">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-yellow to-accent-yellow/60 text-[#031b4e] flex items-center justify-center text-xl font-bold shadow-lg">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-text-main">Welcome, {{ auth()->user()->name }}</h1>
                <p class="text-sm text-text-dark/50 mt-0.5">Manage your job postings and find the best candidates.</p>
            </div>
        </div>
        <a href="{{ route('employer.jobs.create') }}" class="px-5 py-2.5 bg-accent-yellow text-[#031b4e] rounded-xl text-sm font-bold hover:brightness-110 hover:-translate-y-0.5 transition-all shadow-lg hover:shadow-glow-yellow flex items-center gap-2">
            <i class="fas fa-plus"></i> Post New Job
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-5 py-3.5 rounded-2xl flex items-center gap-3 text-sm font-medium">
            <i class="fas fa-check-circle text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-400 px-5 py-3.5 rounded-2xl flex items-center gap-3 text-sm font-medium">
            <i class="fas fa-exclamation-circle text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-400 px-5 py-3.5 rounded-2xl text-sm">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div id="tour-emp-stats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-10 reveal">
        <a href="{{ route('employer.jobs.index') }}" class="block bg-card-bg rounded-2xl border border-card-border p-6 shadow-xl relative overflow-hidden group hover:border-accent-yellow/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-accent-yellow/10 rounded-full blur-xl group-hover:bg-accent-yellow/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-accent-yellow/10 text-accent-yellow flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-briefcase"></i>
                </div>
                <span class="text-xs font-bold text-accent-yellow opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">
                    View <i class="fas fa-arrow-right text-[10px]"></i>
                </span>
            </div>
            <h3 class="text-3xl font-bold text-text-main relative z-10 group-hover:text-accent-yellow transition-colors">{{ $stats['total_jobs'] }}</h3>
            <p class="text-xs font-semibold text-text-dark/50 uppercase tracking-wide mt-1 relative z-10">Total Jobs Posted</p>
        </a>

        <a href="{{ route('employer.jobs.index', ['status' => 'approved']) }}" class="block bg-card-bg rounded-2xl border border-card-border p-6 shadow-xl relative overflow-hidden group hover:border-green-500/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-500/10 rounded-full blur-xl group-hover:bg-green-500/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-green-500/10 text-green-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-check-circle"></i>
                </div>
                <span class="text-xs font-bold text-green-500 opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">
                    View <i class="fas fa-arrow-right text-[10px]"></i>
                </span>
            </div>
            <h3 class="text-3xl font-bold text-text-main relative z-10 group-hover:text-green-500 transition-colors">{{ $stats['active_jobs'] }}</h3>
            <p class="text-xs font-semibold text-text-dark/50 uppercase tracking-wide mt-1 relative z-10">Active / Live Jobs</p>
        </a>

        <a href="{{ route('employer.jobs.index', ['status' => 'pending']) }}" class="block bg-card-bg rounded-2xl border border-card-border p-6 shadow-xl relative overflow-hidden group hover:border-blue-500/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500/10 rounded-full blur-xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-clock"></i>
                </div>
                <span class="text-xs font-bold text-blue-500 opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">
                    View <i class="fas fa-arrow-right text-[10px]"></i>
                </span>
            </div>
            <h3 class="text-3xl font-bold text-text-main relative z-10 group-hover:text-blue-500 transition-colors">{{ $stats['pending_jobs'] }}</h3>
            <p class="text-xs font-semibold text-text-dark/50 uppercase tracking-wide mt-1 relative z-10">Pending Approval</p>
        </a>

        <a href="{{ route('employer.jobs.index', ['status' => 'rejected']) }}" class="block bg-card-bg rounded-2xl border border-card-border p-6 shadow-xl relative overflow-hidden group hover:border-red-500/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-500/10 rounded-full blur-xl group-hover:bg-red-500/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-times-circle"></i>
                </div>
                <span class="text-xs font-bold text-red-500 opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">
                    View <i class="fas fa-arrow-right text-[10px]"></i>
                </span>
            </div>
            <h3 class="text-3xl font-bold text-text-main relative z-10 group-hover:text-red-500 transition-colors">{{ $stats['rejected_jobs'] ?? 0 }}</h3>
            <p class="text-xs font-semibold text-text-dark/50 uppercase tracking-wide mt-1 relative z-10">Rejected Jobs</p>
        </a>

        <a href="{{ route('employer.applicants.index') }}" class="block bg-card-bg rounded-2xl border border-card-border p-6 shadow-xl relative overflow-hidden group hover:border-purple-500/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-purple-500/10 rounded-full blur-xl group-hover:bg-purple-500/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-users"></i>
                </div>
                <span class="text-xs font-bold text-purple-500 opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">
                    View <i class="fas fa-arrow-right text-[10px]"></i>
                </span>
            </div>
            <h3 class="text-3xl font-bold text-text-main relative z-10 group-hover:text-purple-500 transition-colors">{{ $stats['total_candidates'] }}</h3>
            <p class="text-xs font-semibold text-text-dark/50 uppercase tracking-wide mt-1 relative z-10">Shortlisted/Hired Candidates</p>
        </a>
    </div>

    {{-- Search Candidates / Search Teachers Discovery Section --}}
    @if(!empty($discoveryReady))
        <div class="mb-8 bg-card-bg rounded-2xl border border-accent-yellow/35 p-6 shadow-xl reveal space-y-5">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <span class="w-9 h-9 rounded-xl bg-accent-yellow/15 text-accent-yellow flex items-center justify-center text-base">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </span>
                        <h2 class="text-lg font-bold text-text-main">Search Candidates / Search Teachers</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-accent-yellow/15 text-accent-yellow border border-accent-yellow/30">
                            {{ $discoveryStats['eligible'] ?? 0 }} Eligible Profiles
                        </span>
                    </div>
                    <p class="text-xs text-text-dark/60 mt-1.5">
                        Browse Admin-approved teacher profiles by Category (TGT / PGT / PRT) &amp; Subject, inspect blurred previews, and request field-level unlock for Photo, Full Profile, CV, or Contact details.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <a href="{{ route('employer.talent.index', ['curated' => 'verified']) }}"
                       class="px-3.5 py-2 rounded-xl bg-blue-500/15 hover:bg-blue-500/25 border border-blue-500/30 text-blue-400 text-xs font-bold inline-flex items-center gap-1.5">
                        <i class="fas fa-check-circle"></i> Verified Profiles ({{ $discoveryStats['verified'] ?? 0 }})
                    </a>
                    <a href="{{ route('employer.talent.index', ['curated' => 'best']) }}"
                       class="px-3.5 py-2 rounded-xl bg-amber-500/15 hover:bg-amber-500/25 border border-amber-500/30 text-amber-400 text-xs font-bold inline-flex items-center gap-1.5">
                        <i class="fas fa-award"></i> Best Profiles ({{ $discoveryStats['best'] ?? 0 }})
                    </a>
                    <a href="{{ route('employer.talent.index', ['tab' => 'requests']) }}"
                       class="px-3.5 py-2 rounded-xl bg-secondary-bg hover:bg-card-border/50 border border-card-border text-text-main text-xs font-bold inline-flex items-center gap-1.5">
                        <i class="fas fa-unlock-alt text-accent-yellow"></i> My Requests
                        <span class="text-amber-400">({{ $discoveryStats['pending_requests'] ?? 0 }} Pending</span>
                        <span class="text-emerald-400">/ {{ $discoveryStats['approved_requests'] ?? 0 }} Unlocked)</span>
                    </a>
                </div>
            </div>

            <form action="{{ route('employer.talent.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-5">
                    <input type="text" name="keyword" placeholder="Search subject, B.Ed, CTET, VPA ID, city..."
                           class="w-full px-4 py-2.5 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:border-accent-yellow focus:outline-none">
                </div>
                <div class="md:col-span-3">
                    <select name="category_id" class="w-full px-3 py-2.5 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:border-accent-yellow focus:outline-none">
                        <option value="">All Categories (TGT / PGT / PRT)</option>
                        @foreach($quickCategories ?? [] as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <select name="subject_id" class="w-full px-3 py-2.5 bg-secondary-bg border border-card-border rounded-xl text-sm text-text-main focus:border-accent-yellow focus:outline-none">
                        <option value="">All Subjects</option>
                        @foreach($quickSubjects ?? [] as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="w-full h-full px-4 py-2.5 bg-accent-yellow text-[#031b4e] rounded-xl text-xs font-extrabold hover:brightness-110 transition-all cursor-pointer">
                        <i class="fas fa-search mr-1"></i> Find Teachers
                    </button>
                </div>
            </form>

            @if(isset($recentAccessRequests) && $recentAccessRequests->count() > 0)
                <div class="pt-3 border-t border-card-border/60 flex flex-wrap items-center gap-2 text-xs">
                    <span class="text-text-dark/50 font-semibold mr-1">Recent Access Requests:</span>
                    @foreach($recentAccessRequests as $rar)
                        @php
                            $rarClass = match ($rar->status) {
                                'approved' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                                'partially_approved' => 'bg-blue-500/15 text-blue-400 border-blue-500/30',
                                'declined' => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                                default => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                            };
                        @endphp
                        <a href="{{ route('employer.talent.index', ['tab' => 'requests']) }}"
                           class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border {{ $rarClass }} text-[11px] font-bold hover:opacity-90">
                            <span>{{ $rar->candidate?->profile?->vpa_id ?? ('#' . $rar->candidate_user_id) }}</span>
                            <span>({{ $rar->candidate?->profile?->subject?->name ?? 'Teacher' }})</span>
                            <span>· {{ $rar->statusLabel() }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div id="tour-emp-jobs" class="lg:col-span-2 space-y-8">
            @if($recentJobs->count() > 0)
                <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl reveal">
                    <div class="px-6 py-4 border-b border-card-border flex justify-between items-center bg-black/20">
                        <h3 class="font-bold text-text-main flex items-center gap-2">
                            <i class="fas fa-history text-accent-yellow"></i> Recent Jobs
                        </h3>
                        <a href="{{ route('employer.jobs.index') }}" class="text-xs font-semibold text-accent-yellow hover:text-white transition-colors">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-card-border/50 bg-black/10">
                                    <th class="px-6 py-4 text-xs font-semibold text-text-dark/50 uppercase tracking-wider">Job Title</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-text-dark/50 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-text-dark/50 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-text-dark/50 uppercase tracking-wider">Posted On</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-text-dark/50 uppercase tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-card-border/50">
                                @foreach($recentJobs as $job)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-text-main">{{ $job->title ?? 'N/A' }}</div>
                                            <div class="text-xs text-text-dark/50 mt-1"><i class="fas fa-map-marker-alt text-accent-yellow/70 mr-1"></i> {{ $job->city?->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-white/5 text-text-main border border-white/10">
                                                {{ $job->category->name ?? '' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($job->status === 'approved')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Approved
                                                </span>
                                            @elseif($job->status === 'pending')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span> Pending
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Rejected
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-text-dark/70">
                                            {{ $job->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('employer.jobs.show', $job->id) }}" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-text-dark hover:text-accent-yellow hover:bg-accent-yellow/10 transition-colors" title="View Job">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($job->status === 'pending')
                                                    <a href="{{ route('employer.jobs.edit', $job->id) }}" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-text-dark hover:text-blue-400 hover:bg-blue-400/10 transition-colors" title="Edit Job">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                {{-- Empty State --}}
                <div class="bg-card-bg rounded-2xl border border-card-border p-12 text-center shadow-xl reveal reveal-delay-1">
                    <div class="w-20 h-20 rounded-2xl bg-accent-yellow/10 text-accent-yellow flex items-center justify-center text-3xl mx-auto mb-5">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3 class="text-xl font-bold text-text-main mb-2">No Active Jobs</h3>
                    <p class="text-sm text-text-dark/40 max-w-md mx-auto mb-8 leading-relaxed">You haven't posted any job requirements yet. Post a job to start receiving candidate applications from qualified teachers.</p>
                    <a href="{{ route('employer.jobs.create') }}" class="inline-flex items-center px-8 py-3.5 bg-accent-yellow text-[#031b4e] rounded-xl font-bold hover:brightness-110 hover:-translate-y-0.5 transition-all shadow-lg hover:shadow-glow-yellow gap-2">
                        <i class="fas fa-plus-circle"></i> Post Your First Job
                    </a>
                </div>
            @endif
        </div>

        <div class="lg:col-span-1 space-y-8">
            {{-- Institution Agreement Download, Upload & Approval Status --}}
            @php
                $ep = $employerProfile ?? auth()->user()->employerProfile;
                $agrStatus = $ep->agreement_status ?? 'not_uploaded';
                $hasUploadedAgr = $ep && !empty($ep->agreement_path);
                $statusBadgeMap = [
                    'approved' => ['label' => 'Approved', 'classes' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30', 'icon' => 'fa-check-circle'],
                    'pending' => ['label' => 'Pending Approval', 'classes' => 'bg-amber-500/15 text-amber-400 border-amber-500/30', 'icon' => 'fa-clock'],
                    'rejected' => ['label' => 'Rejected — Re-upload', 'classes' => 'bg-rose-500/15 text-rose-400 border-rose-500/30', 'icon' => 'fa-times-circle'],
                    'not_uploaded' => ['label' => 'Not Uploaded', 'classes' => 'bg-slate-500/15 text-slate-300 border-slate-500/30', 'icon' => 'fa-file-contract'],
                ];
                $agrMeta = $statusBadgeMap[$agrStatus] ?? $statusBadgeMap['not_uploaded'];
            @endphp
            <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl reveal">
                <div class="px-6 py-4 border-b border-card-border flex items-center justify-between gap-2 bg-secondary-bg/30">
                    <h3 class="font-bold text-text-main flex items-center gap-2 text-sm">
                        <i class="fas fa-file-signature text-accent-yellow"></i>
                        <span>Institution Agreement</span>
                    </h3>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $agrMeta['classes'] }}">
                        <i class="fas {{ $agrMeta['icon'] }} text-[10px]"></i>
                        <span>{{ $agrMeta['label'] }}</span>
                    </span>
                </div>

                <div class="p-5 space-y-4">
                    <p class="text-xs text-text-dark/70 leading-relaxed">
                        Download your prefilled Institution Recruitment Agreement, sign/stamp it, and upload the completed copy for Admin verification.
                    </p>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('employer.agreement.template') }}"
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-accent-yellow text-[#031b4e] hover:brightness-110 text-xs font-extrabold transition-all shadow-sm">
                            <i class="fas fa-download"></i>
                            <span>Download Agreement PDF</span>
                        </a>

                        @if($hasUploadedAgr)
                            <a href="{{ route('employer.agreement.uploaded') }}" target="_blank"
                               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-secondary-bg hover:bg-card-border/50 text-text-main border border-card-border text-xs font-bold transition-all">
                                <i class="fas fa-eye text-accent-blue"></i>
                                <span>View Uploaded Agreement</span>
                            </a>
                        @endif
                    </div>

                    @if($agrStatus === 'rejected' && !empty($ep?->agreement_rejection_reason))
                        <div class="p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-xs text-rose-300">
                            <p class="font-bold text-rose-400 mb-0.5"><i class="fas fa-exclamation-triangle mr-1"></i> Rejection Reason:</p>
                            <p>{{ $ep->agreement_rejection_reason }}</p>
                        </div>
                    @endif

                    @if($ep?->agreement_uploaded_at)
                        <p class="text-[11px] text-text-dark/50">
                            Last uploaded: <strong class="text-text-main">{{ $ep->agreement_uploaded_at->copy()->timezone('Asia/Kolkata')->format('d M Y, h:i A') }} IST</strong>
                        </p>
                    @endif

                    @if($agrStatus !== 'approved')
                        <form action="{{ route('employer.agreement.upload') }}" method="POST" enctype="multipart/form-data" class="pt-3 border-t border-card-border/60 space-y-3">
                            @csrf
                            <div>
                                <label for="employer_agreement_file" class="block text-[11px] font-bold uppercase tracking-wider text-text-dark/70 mb-1.5">
                                    {{ $hasUploadedAgr ? 'Re-upload Signed Agreement' : 'Upload Signed Agreement' }}
                                </label>
                                <input type="file" id="employer_agreement_file" name="agreement_file" accept=".pdf,.jpg,.jpeg,.png" required
                                       class="w-full text-xs text-text-main bg-secondary-bg border border-card-border rounded-xl p-2 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-accent-blue file:text-white cursor-pointer">
                                <span class="text-[10px] text-text-dark/50 mt-1 block">Accepted: PDF, JPG, PNG (Max 10MB)</span>
                            </div>
                            <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-accent-blue hover:bg-accent-blue-hover text-white text-xs font-bold shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fas fa-upload"></i>
                                <span>{{ $hasUploadedAgr ? 'Replace & Submit for Approval' : 'Upload Signed Agreement' }}</span>
                            </button>
                        </form>
                    @else
                        <div class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-xs text-emerald-300 flex items-center gap-2">
                            <i class="fas fa-shield-alt text-emerald-400"></i>
                            <span>Verified &amp; Approved{{ $ep?->agreement_reviewed_at ? ' on ' . $ep->agreement_reviewed_at->copy()->timezone('Asia/Kolkata')->format('d M Y') : '' }}.</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Recent Notifications --}}
            <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-sm reveal">
                <div class="px-6 py-4 border-b border-card-border flex justify-between items-center bg-secondary-bg/30">
                    <h3 class="font-bold text-text-main flex items-center gap-2"><i class="fas fa-bell text-accent-yellow"></i> Notifications</h3>
                </div>
                <div class="divide-y divide-card-border">
                    @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                        <div class="p-5 flex gap-4 hover:bg-secondary-bg/30 transition-colors {{ $notification->unread() ? 'bg-secondary-bg/10' : '' }}">
                            <div class="w-10 h-10 rounded-full bg-accent-yellow/10 text-accent-yellow flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-text-main mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h4>
                                <p class="text-xs text-text-dark/70 leading-relaxed">{{ $notification->data['message'] ?? 'You have a new update.' }}</p>
                                <span class="text-[10px] text-text-dark/40 font-medium mt-2 block">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center text-text-dark/50 text-sm">
                            No new notifications
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.platform-walkthrough', ['portal' => 'employer'])
@endsection
