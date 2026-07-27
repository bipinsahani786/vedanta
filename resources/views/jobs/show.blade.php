@extends('layouts.app')

@section('content')
<x-page-header title="{{ $job->title ?? 'Job Requirement' }}" :breadcrumbs="['Home' => route('home'), 'Jobs' => route('jobs'), 'Job Details' => null]" />

<div class="py-12 px-4 sm:px-6 lg:px-[5%] bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto bg-white border border-slate-200/80 rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.06)] overflow-hidden reveal">
        
        <!-- Flash Messages -->
        @if(session('error'))
            <div class="m-6 p-5 mb-2 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl shadow-sm flex items-center justify-between" style="animation: slideDown 0.4s ease-out forwards;">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center shrink-0">
                        <i class="fas fa-exclamation-triangle text-rose-600 text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-rose-900 font-bold text-sm">Action Failed</h4>
                        <span class="text-xs sm:text-sm text-rose-700 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
                <button type="button" class="text-rose-400 hover:text-rose-600 p-2 rounded-lg hover:bg-rose-100 transition-colors" onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('success'))
            <div class="m-6 p-5 mb-2 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl shadow-sm flex items-center justify-between" style="animation: slideDown 0.4s ease-out forwards;">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                        <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-emerald-900 font-bold text-sm">Success</h4>
                        <span class="text-xs sm:text-sm text-emerald-700 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="text-emerald-400 hover:text-emerald-600 p-2 rounded-lg hover:bg-emerald-100 transition-colors" onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        
        <style>
            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>

        <!-- Job Header -->
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-white">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-start sm:items-center gap-4 sm:gap-5">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-blue-50/80 rounded-2xl flex items-center justify-center p-2.5 shadow-sm border border-blue-100 shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($job->school_name ?: $job->title) }}&background=129aef&color=ffffff&bold=true" class="w-full h-full rounded-xl object-cover" alt="Institution avatar">
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#040e2d] tracking-tight mb-2">
                            {{ $job->title ?? 'Job Requirement' }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-y-2 gap-x-4 text-xs sm:text-sm font-semibold text-slate-600">
                            <span class="flex items-center gap-1.5 bg-slate-100 px-3 py-1 rounded-lg text-slate-700">
                                <i class="fas fa-building text-[#129aef]"></i> {{ $job->school_name ?: 'Institution Name Confidential' }}
                            </span>
                            <span class="flex items-center gap-1.5 bg-slate-100 px-3 py-1 rounded-lg text-slate-700">
                                <i class="fas fa-map-marker-alt text-[#129aef]"></i> {{ $job->city?->name ?? 'N/A' }}, {{ $job->state?->name ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-auto shrink-0 pt-2 md:pt-0">
                    @auth
                        @if(auth()->user()->role === 'candidate')
                            <form action="{{ route('candidate.applications.apply', $job->id) }}" method="POST" class="w-full sm:w-auto">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-[#129aef] hover:bg-[#0d85d4] text-white font-extrabold rounded-xl shadow-lg shadow-[#129aef]/25 hover:shadow-[#129aef]/40 hover:-translate-y-0.5 active:translate-y-0 transition-all text-center flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Apply Now</span>
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('candidate.register') }}" class="w-full sm:w-auto px-8 py-3.5 bg-[#129aef] hover:bg-[#0d85d4] text-white font-extrabold rounded-xl shadow-lg shadow-[#129aef]/25 hover:shadow-[#129aef]/40 hover:-translate-y-0.5 active:translate-y-0 transition-all inline-flex items-center justify-center gap-2 text-center">
                            <i class="fas fa-right-to-bracket"></i>
                            <span>Login / Register to Apply</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Job Details Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-100 bg-slate-50/70">
            <div class="p-5 sm:p-6 text-center">
                <div class="w-12 h-12 rounded-2xl bg-blue-100/70 text-[#129aef] flex items-center justify-center mx-auto mb-3 text-xl shadow-sm">
                    <i class="fas fa-book-open"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Subject</h4>
                <p class="font-bold text-slate-800 text-sm sm:text-base">{{ $job->subject?->name ?? 'N/A' }}</p>
            </div>
            
            <div class="p-5 sm:p-6 text-center">
                <div class="w-12 h-12 rounded-2xl bg-purple-100/70 text-purple-600 flex items-center justify-center mx-auto mb-3 text-xl shadow-sm">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Qualification</h4>
                <p class="font-bold text-slate-800 text-sm sm:text-base">{{ $job->qualification?->name ?? 'N/A' }}</p>
            </div>

            <div class="p-5 sm:p-6 text-center">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100/70 text-emerald-600 flex items-center justify-center mx-auto mb-3 text-xl shadow-sm">
                    <i class="fas fa-indian-rupee-sign"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Salary Range</h4>
                <p class="font-bold text-slate-800 text-sm sm:text-base">{{ $job->salary_range ?? 'Not specified' }}</p>
            </div>
        </div>

        <!-- Job Description & Bullet Points Formatter -->
        <div class="p-6 sm:p-10">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4 mb-6">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#129aef] flex items-center justify-center text-base font-bold shadow-sm">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="text-lg font-bold text-[#040e2d] tracking-wide">
                    Job Description & Requirements
                </h3>
            </div>

            <!-- Enhanced Parsed Description -->
            <div class="bg-[#f8fafc] border border-slate-200/80 rounded-2xl p-6 sm:p-8 text-slate-700 leading-relaxed shadow-inner [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:space-y-2.5 [&_ul]:my-4 [&_li]:text-slate-700 [&_li]:marker:text-[#129aef] [&_li]:marker:font-bold [&_p]:mb-4 [&_h4]:font-extrabold [&_h4]:text-[#040e2d] [&_h4]:text-xs sm:[&_h4]:text-sm [&_h4]:mt-6 [&_h4]:mb-3 [&_h4]:border-b [&_h4]:border-slate-200 [&_h4]:pb-2 [&_h4]:uppercase [&_h4]:tracking-wider">
                @php
                    $rawDesc = $job->description ?? '';

                    if (empty(trim($rawDesc))) {
                        $formattedDescription = '<p class="text-slate-500 italic text-sm">No detailed description provided for this job role.</p>';
                    } else {
                        // Check if string contains HTML tags
                        $hasHtml = preg_match('/<[a-z][\s\S]*>/i', $rawDesc);

                        if ($hasHtml) {
                            $formattedDescription = $rawDesc;
                        } else {
                            $normalized = str_replace(['•', '·', '►', '▪', '⁃', '●'], '•', $rawDesc);

                            $sectionKeywords = [
                                'Key Responsibilities:', 'Responsibilities:', 'Job Responsibilities:',
                                'Requirements:', 'Key Requirements:', 'Eligibility:', 'Qualifications:',
                                'Job Details:', 'Job Overview:', 'Key Details:', 'About the Role:',
                                'Job Type:', 'Location:', 'Salary:', 'Perks & Benefits:', 'Perks:'
                            ];

                            foreach ($sectionKeywords as $keyword) {
                                $normalized = preg_replace('/(?i)' . preg_quote($keyword, '/') . '/', "\n\n<h4>" . trim($keyword, ':') . "</h4>\n", $normalized);
                            }

                            $normalized = preg_replace('/(?<!^|\n)•/', "\n•", $normalized);
                            $rawLines = explode("\n", $normalized);
                            $outputHtml = '';
                            $inList = false;

                            foreach ($rawLines as $line) {
                                $trimmed = trim($line);
                                if (empty($trimmed)) continue;

                                if (strpos($trimmed, '<h4>') === 0) {
                                    if ($inList) {
                                        $outputHtml .= '</ul>';
                                        $inList = false;
                                    }
                                    $outputHtml .= $trimmed;
                                }
                                elseif (strpos($trimmed, '•') === 0 || strpos($trimmed, '-') === 0 || strpos($trimmed, '*') === 0) {
                                    if (!$inList) {
                                        $outputHtml .= '<ul>';
                                        $inList = true;
                                    }
                                    $cleanItem = e(trim(ltrim($trimmed, '•-* ')));
                                    $outputHtml .= '<li>' . $cleanItem . '</li>';
                                }
                                else {
                                    if ($inList) {
                                        $outputHtml .= '</ul>';
                                        $inList = false;
                                    }
                                    $outputHtml .= '<p>' . e($trimmed) . '</p>';
                                }
                            }

                            if ($inList) {
                                $outputHtml .= '</ul>';
                            }

                            $formattedDescription = $outputHtml;
                        }
                    }
                @endphp
                {!! $formattedDescription !!}
            </div>

            <!-- Footer Tags & Info -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                <div class="text-xs sm:text-sm text-slate-500 font-semibold flex items-center gap-2">
                    <i class="far fa-calendar-alt text-[#129aef]"></i>
                    <span>Posted on {{ $job->created_at->format('d M, Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-50 text-[#129aef] text-xs font-bold rounded-xl border border-blue-100 shadow-sm">
                        <i class="fas fa-layer-group"></i> {{ $job->category?->name ?? 'General Category' }}
                    </span>
                    @if($job->specialization)
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-purple-50 text-purple-700 text-xs font-bold rounded-xl border border-purple-100 shadow-sm">
                            <i class="fas fa-award"></i> {{ $job->specialization->name }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Application CTA Footer for Guests -->
        @guest
        <div class="p-6 sm:p-8 bg-gradient-to-r from-[#040e2d] via-[#092265] to-[#040e2d] text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg sm:text-xl font-extrabold mb-1">Interested in applying for this role?</h3>
                <p class="text-blue-200 text-xs sm:text-sm">Create a verified candidate profile to get shortlisted directly by top schools.</p>
            </div>
            <a href="{{ route('candidate.register') }}" class="w-full sm:w-auto px-7 py-3 bg-[#ffb800] hover:bg-amber-400 text-[#040e2d] font-bold rounded-xl shadow-lg hover:-translate-y-0.5 transition-all text-center whitespace-nowrap text-sm cursor-pointer">
                Register as Candidate <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        @endguest
    </div>
</div>
@endsection
