@extends('layouts.app')

@section('content')
@include('employer.partials.nav')

<div class="pt-8 pb-20 px-6 lg:px-[5%] relative">
    <div class="max-w-4xl mx-auto bg-card-bg border border-card-border rounded-2xl shadow-2xl relative z-10 overflow-hidden reveal">
        <!-- Job Header -->
        <div class="bg-secondary-bg border-b border-card-border p-8 relative">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-text-main mb-2 relative z-10">{{ $job->title ?? 'Teacher Required' }}</h1>
                    <div class="flex flex-wrap gap-4 text-sm font-semibold text-text-dark/70 relative z-10">
                        <span class="flex items-center gap-1.5"><i class="fas fa-university text-accent-yellow"></i> {{ $job->school_name }}</span>
                        <span class="flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-accent-yellow"></i> {{ $job->city?->name ?? '' }}, {{ $job->state?->name ?? '' }}</span>
                    </div>
                </div>
                <div>
                    @if($job->status === 'approved')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-green-500/10 text-green-500 border border-green-500/20">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span> Approved
                        </span>
                    @elseif($job->status === 'pending')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                            <span class="w-2 h-2 rounded-full bg-blue-400"></span> Pending
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-red-500/10 text-red-500 border border-red-500/20">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span> Rejected
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Job Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-card-border bg-secondary-bg/30">
            <div class="p-6 text-center">
                <div class="w-10 h-10 rounded-full bg-accent-blue/10 text-accent-blue flex items-center justify-center mx-auto mb-3 text-lg"><i class="fas fa-book"></i></div>
                <h4 class="text-xs font-bold text-text-dark/50 uppercase tracking-wider mb-1">Subject</h4>
                <p class="font-semibold text-text-main">{{ $job->subject->name }}</p>
            </div>
            <div class="p-6 text-center">
                <div class="w-10 h-10 rounded-full bg-purple-500/10 text-purple-500 flex items-center justify-center mx-auto mb-3 text-lg"><i class="fas fa-graduation-cap"></i></div>
                <h4 class="text-xs font-bold text-text-dark/50 uppercase tracking-wider mb-1">Qualification</h4>
                <p class="font-semibold text-text-main">{{ $job->qualification->name }}</p>
            </div>
            <div class="p-6 text-center">
                <div class="w-10 h-10 rounded-full bg-green-500/10 text-green-500 flex items-center justify-center mx-auto mb-3 text-lg"><i class="fas fa-rupee-sign"></i></div>
                <h4 class="text-xs font-bold text-text-dark/50 uppercase tracking-wider mb-1">Salary Range</h4>
                <p class="font-semibold text-text-main">{{ $job->salary_range ?? 'Not specified' }}</p>
            </div>
        </div>

        <!-- Job Description -->
        <div class="p-8">
            <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-accent-blue"></i> Job Description & Requirements
            </h3>
            <div class="bg-secondary-bg/50 border border-card-border rounded-xl p-6 text-text-main leading-relaxed shadow-inner [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:space-y-2 [&_ul]:my-3 [&_li]:text-text-main [&_li]:marker:text-accent-blue [&_p]:mb-3 [&_h4]:font-extrabold [&_h4]:text-text-main [&_h4]:text-xs [&_h4]:mt-5 [&_h4]:mb-2 [&_h4]:border-b [&_h4]:border-card-border [&_h4]:pb-1.5 [&_h4]:uppercase [&_h4]:tracking-wider">
                @php
                    $rawDesc = $job->description ?? '';

                    if (empty(trim($rawDesc))) {
                        $formattedDescription = '<p class="text-text-dark/50 italic text-sm">No detailed description provided for this job role.</p>';
                    } else {
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

            <div class="mt-10 pt-6 border-t border-card-border flex justify-between items-center">
                <div class="text-sm text-text-main opacity-50">
                    Posted on {{ $job->created_at->format('d M, Y') }}
                </div>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-accent-blue/10 text-accent-blue text-xs font-bold rounded-lg border border-accent-blue/20">
                        <i class="fas fa-tag"></i> {{ $job->category->name }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Application Footer -->
        @guest
        <div class="p-8 bg-gradient-to-r from-accent-blue to-accent-blue-hover text-white flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold mb-1">Interested in this role?</h3>
                <p class="text-white/80 text-sm">Join Vedanta Agency and apply directly to top schools.</p>
            </div>
            <a href="{{ route('candidate.register') }}" class="px-8 py-3 bg-white text-accent-blue font-bold rounded-xl shadow-lg hover:bg-gray-50 hover:-translate-y-0.5 transition-all whitespace-nowrap">
                Register as Teacher
            </a>
        </div>
        @endguest
    </div>
</div>
@endsection
