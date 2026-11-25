@extends('layouts.app')

@section('title', 'Hiring Process - Step-by-Step Recruitment Flow | Vedanta Placement Agency®')
@section('meta_description', 'Follow Vedanta Placement Agency® simple 6-step hiring process—from CV submission and online registration to interview coordination, selection, and joining.')

@section('content')
<x-page-header title="Our Hiring Process" :breadcrumbs="['Home' => route('home'), 'Hiring Process' => null]" />

@php
    $isCandidate = auth()->check() && auth()->user()->role === 'candidate';
    $startUrl = $isCandidate ? route('candidate.dashboard') : route('candidate.register');
    $startLabel = $isCandidate ? 'Go to Dashboard' : 'Start Registration';
@endphp

<!-- Overview & Quick Step Bar -->
<section class="py-16 px-6 lg:px-[5%] bg-white text-slate-900 border-b border-slate-200">
    <div class="max-w-6xl mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-12 reveal">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-[#129aef] text-xs font-extrabold uppercase tracking-widest mb-4">
                <i class="fas fa-route"></i> 6 Simple Steps
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#040e2d] mb-3">
                Step-by-Step Hiring Process
            </h2>
            <p class="text-slate-600 text-base leading-relaxed">
                A transparent, guided journey connecting qualified educators and institutional staff with verified schools across India.
            </p>
        </div>

        <!-- Quick At-a-Glance Jump Strip -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 reveal">
            <a href="#step-1" class="group bg-slate-50 hover:bg-[#040e2d] border border-slate-200 hover:border-[#040e2d] rounded-2xl p-4 text-center transition-all duration-200">
                <div class="text-xs font-black text-[#129aef] group-hover:text-[#ffb800] mb-1">STEP 01</div>
                <div class="text-xs font-bold text-[#040e2d] group-hover:text-white leading-snug">Document Submission</div>
            </a>
            <a href="#step-2" class="group bg-slate-50 hover:bg-[#040e2d] border border-slate-200 hover:border-[#040e2d] rounded-2xl p-4 text-center transition-all duration-200">
                <div class="text-xs font-black text-[#129aef] group-hover:text-[#ffb800] mb-1">STEP 02</div>
                <div class="text-xs font-bold text-[#040e2d] group-hover:text-white leading-snug">Registration &amp; Plan</div>
            </a>
            <a href="#step-3" class="group bg-slate-50 hover:bg-[#040e2d] border border-slate-200 hover:border-[#040e2d] rounded-2xl p-4 text-center transition-all duration-200">
                <div class="text-xs font-black text-[#129aef] group-hover:text-[#ffb800] mb-1">STEP 03</div>
                <div class="text-xs font-bold text-[#040e2d] group-hover:text-white leading-snug">Agreement &amp; Verification</div>
            </a>
            <a href="#step-4" class="group bg-slate-50 hover:bg-[#040e2d] border border-slate-200 hover:border-[#040e2d] rounded-2xl p-4 text-center transition-all duration-200">
                <div class="text-xs font-black text-[#129aef] group-hover:text-[#ffb800] mb-1">STEP 04</div>
                <div class="text-xs font-bold text-[#040e2d] group-hover:text-white leading-snug">Introduction Video</div>
            </a>
            <a href="#step-5" class="group bg-slate-50 hover:bg-[#040e2d] border border-slate-200 hover:border-[#040e2d] rounded-2xl p-4 text-center transition-all duration-200">
                <div class="text-xs font-black text-[#129aef] group-hover:text-[#ffb800] mb-1">STEP 05</div>
                <div class="text-xs font-bold text-[#040e2d] group-hover:text-white leading-snug">Interview &amp; Selection</div>
            </a>
            <a href="#step-6" class="group bg-slate-50 hover:bg-[#040e2d] border border-slate-200 hover:border-[#040e2d] rounded-2xl p-4 text-center transition-all duration-200">
                <div class="text-xs font-black text-[#129aef] group-hover:text-[#ffb800] mb-1">STEP 06</div>
                <div class="text-xs font-bold text-[#040e2d] group-hover:text-white leading-snug">Joining &amp; Service Charge</div>
            </a>
        </div>
    </div>
</section>

<!-- Detailed 6-Step Cards -->
<section class="py-16 md:py-20 px-6 lg:px-[5%] bg-slate-50 text-slate-900">
    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Step 1 -->
        <div id="step-1" class="scroll-mt-28 bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm hover:shadow-md transition-shadow reveal">
            <div class="flex flex-col md:flex-row md:items-start gap-5">
                <div class="flex items-center gap-4 md:w-64 shrink-0">
                    <div class="w-12 h-12 rounded-2xl bg-[#040e2d] text-white font-black text-lg flex items-center justify-center shrink-0">01</div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#129aef] block">Getting Started</span>
                        <h3 class="text-lg font-extrabold text-[#040e2d]">Document Submission</h3>
                    </div>
                </div>
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2 md:pt-0 md:border-l md:border-slate-100 md:pl-6">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2.5 flex items-center gap-1.5">
                            <i class="fas fa-upload text-[#129aef]"></i> What You Submit
                        </h4>
                        <ul class="space-y-2 text-sm text-slate-700">
                            <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-500 text-xs"></i> Updated Resume (CV)</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-500 text-xs"></i> Latest Salary Slip or Offer Letter (if experienced)</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2.5 flex items-center gap-1.5">
                            <i class="fas fa-bullseye text-[#129aef]"></i> Purpose
                        </h4>
                        <ul class="space-y-2 text-sm text-slate-700">
                            <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-500 text-xs"></i> Profile evaluation &amp; eligibility check</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-500 text-xs"></i> Matching against active school vacancies</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div id="step-2" class="scroll-mt-28 bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm hover:shadow-md transition-shadow reveal">
            <div class="flex flex-col md:flex-row md:items-start gap-5">
                <div class="flex items-center gap-4 md:w-64 shrink-0">
                    <div class="w-12 h-12 rounded-2xl bg-[#ffb800] text-[#040e2d] font-black text-lg flex items-center justify-center shrink-0">02</div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600 block">Plan Selection</span>
                        <h3 class="text-lg font-extrabold text-[#040e2d]">Registration Process</h3>
                    </div>
                </div>
                <div class="flex-1 pt-2 md:pt-0 md:border-l md:border-slate-100 md:pl-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-600">Standard Plan</span>
                                <span class="text-lg font-black text-[#040e2d]">₹500</span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                3 months validity • Up to 2 applications • Starts within 24 hrs (₹500 balance after selection).
                            </p>
                        </div>
                        <div class="bg-amber-50/60 border border-amber-300 rounded-2xl p-4">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-extrabold uppercase tracking-wider text-amber-800">Premium Plan</span>
                                <span class="text-lg font-black text-[#040e2d]">₹1,000</span>
                            </div>
                            <p class="text-xs text-slate-700 leading-relaxed">
                                6 months validity • Up to 3 applications • Same-day verification &amp; priority support (no extra balance).
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-rose-50 border border-rose-100 rounded-xl px-4 py-2.5 text-xs text-rose-800">
                        <span><i class="fas fa-exclamation-triangle mr-1.5"></i><strong>Note:</strong> Registration fees are non-refundable once processing begins.</span>
                        <a href="{{ route('pricing') }}" class="font-bold text-[#129aef] hover:underline whitespace-nowrap shrink-0">
                            View Plan &amp; Pricing <i class="fas fa-arrow-right text-[10px] ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div id="step-3" class="scroll-mt-28 bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm hover:shadow-md transition-shadow reveal">
            <div class="flex flex-col md:flex-row md:items-start gap-5">
                <div class="flex items-center gap-4 md:w-64 shrink-0">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black text-lg flex items-center justify-center shrink-0">03</div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 block">Online Onboarding</span>
                        <h3 class="text-lg font-extrabold text-[#040e2d]">Agreement &amp; Verification</h3>
                    </div>
                </div>
                <div class="flex-1 pt-2 md:pt-0 md:border-l md:border-slate-100 md:pl-6">
                    <p class="text-sm text-slate-600 mb-3">Before profile activation, candidates digitally review, verify, and accept:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs font-semibold text-slate-700">
                        <div class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-500"></i> Candidate Consent &amp; Agreement
                        </div>
                        <div class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-500"></i> Live Photo &amp; Location
                        </div>
                        <div class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-500"></i> Digital Signature &amp; Terms
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4 -->
        <div id="step-4" class="scroll-mt-28 bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm hover:shadow-md transition-shadow reveal">
            <div class="flex flex-col md:flex-row md:items-start gap-5">
                <div class="flex items-center gap-4 md:w-64 shrink-0">
                    <div class="w-12 h-12 rounded-2xl bg-violet-600 text-white font-black text-lg flex items-center justify-center shrink-0">04</div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-violet-600 block">1–2 Minutes</span>
                        <h3 class="text-lg font-extrabold text-[#040e2d]">Introduction Video</h3>
                    </div>
                </div>
                <div class="flex-1 pt-2 md:pt-0 md:border-l md:border-slate-100 md:pl-6">
                    <p class="text-sm text-slate-600 mb-3">Record a short, clear introduction video covering these four points so schools can evaluate your communication confidently:</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs font-semibold text-slate-700">
                        <div class="bg-violet-50/70 border border-violet-100 rounded-xl px-3 py-2.5 text-center">Personal Intro</div>
                        <div class="bg-violet-50/70 border border-violet-100 rounded-xl px-3 py-2.5 text-center">Qualification</div>
                        <div class="bg-violet-50/70 border border-violet-100 rounded-xl px-3 py-2.5 text-center">Experience</div>
                        <div class="bg-violet-50/70 border border-violet-100 rounded-xl px-3 py-2.5 text-center">Subject / Role</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 5 -->
        <div id="step-5" class="scroll-mt-28 bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm hover:shadow-md transition-shadow reveal">
            <div class="flex flex-col md:flex-row md:items-start gap-5">
                <div class="flex items-center gap-4 md:w-64 shrink-0">
                    <div class="w-12 h-12 rounded-2xl bg-[#129aef] text-white font-black text-lg flex items-center justify-center shrink-0">05</div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#129aef] block">Shortlisting &amp; Offer</span>
                        <h3 class="text-lg font-extrabold text-[#040e2d]">Interview &amp; Selection</h3>
                    </div>
                </div>
                <div class="flex-1 pt-2 md:pt-0 md:border-l md:border-slate-100 md:pl-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-slate-700">
                        <div class="flex items-center gap-2.5 bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5">
                            <i class="fas fa-check-circle text-[#129aef]"></i> Profile shortlisting for matching vacancies
                        </div>
                        <div class="flex items-center gap-2.5 bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5">
                            <i class="fas fa-check-circle text-[#129aef]"></i> School interview &amp; demo coordination
                        </div>
                        <div class="flex items-center gap-2.5 bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5">
                            <i class="fas fa-check-circle text-[#129aef]"></i> Final selection by the school/institution
                        </div>
                        <div class="flex items-center gap-2.5 bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5">
                            <i class="fas fa-check-circle text-[#129aef]"></i> Offer letter &amp; joining date confirmation
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 6 -->
        <div id="step-6" class="scroll-mt-28 bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm hover:shadow-md transition-shadow reveal">
            <div class="flex flex-col md:flex-row md:items-start gap-5">
                <div class="flex items-center gap-4 md:w-64 shrink-0">
                    <div class="w-12 h-12 rounded-2xl bg-orange-500 text-white font-black text-lg flex items-center justify-center shrink-0">06</div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-orange-600 block">Pay After Joining</span>
                        <h3 class="text-lg font-extrabold text-[#040e2d]">Joining &amp; Service Charge</h3>
                    </div>
                </div>
                <div class="flex-1 pt-2 md:pt-0 md:border-l md:border-slate-100 md:pl-6 space-y-4">
                    <p class="text-sm text-slate-600">
                        Service charges are strictly performance-based and payable <strong>only after successful joining and receipt of your first salary</strong>.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-center">
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5">
                            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Teaching Staff</div>
                            <div class="text-xl font-black text-[#040e2d] mt-1">50%</div>
                            <div class="text-[11px] text-slate-500">of 1st Month Salary</div>
                        </div>
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5">
                            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Non-Teaching / Mgmt</div>
                            <div class="text-xl font-black text-[#040e2d] mt-1">20 Days</div>
                            <div class="text-[11px] text-slate-500">Salary (66.67%)</div>
                        </div>
                        <div class="bg-orange-50 border border-orange-200 rounded-xl p-3.5">
                            <div class="text-[11px] font-bold uppercase tracking-wider text-orange-700">EMI Available</div>
                            <div class="text-sm font-extrabold text-[#040e2d] mt-1.5">₹1,499 / ₹2,999</div>
                            <div class="text-[11px] text-slate-600">Teaching / Non-Teaching fee</div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('pricing') }}" class="text-xs font-bold text-[#129aef] hover:underline">
                            See salary examples &amp; service charge terms <i class="fas fa-arrow-right text-[10px] ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Vacancy & Application Flow + CTA -->
<section class="py-16 px-6 lg:px-[5%] bg-white border-t border-slate-200 text-slate-900">
    <div class="max-w-5xl mx-auto space-y-12">
        <div class="bg-slate-50 border border-slate-200 rounded-3xl overflow-hidden reveal">
            <div class="bg-[#040e2d] text-white text-center py-3.5 px-4 font-bold uppercase tracking-wider text-xs">
                How Vacancies Move From Schools to Candidates
            </div>
            <div class="p-6 md:p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 text-[#040e2d] flex items-center justify-center text-lg mb-3 shadow-sm">
                        <i class="fas fa-school"></i>
                    </div>
                    <p class="text-xs font-semibold text-slate-700 leading-snug">Vacancies received from partner schools</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 text-[#040e2d] flex items-center justify-center text-lg mb-3 shadow-sm">
                        <i class="fas fa-search"></i>
                    </div>
                    <p class="text-xs font-semibold text-slate-700 leading-snug">Requirement review &amp; eligibility check</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 text-[#040e2d] flex items-center justify-center text-lg mb-3 shadow-sm">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <p class="text-xs font-semibold text-slate-700 leading-snug">Submit &amp; screen updated Resume (CV)</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 text-[#040e2d] flex items-center justify-center text-lg mb-3 shadow-sm">
                        <i class="fas fa-users"></i>
                    </div>
                    <p class="text-xs font-semibold text-slate-700 leading-snug">Profile evaluation &amp; shortlisting</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-2xl bg-[#129aef] text-white flex items-center justify-center text-lg mb-3 shadow-md">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <p class="text-xs font-bold text-[#040e2d] leading-snug">Interview coordination &amp; final selection</p>
                </div>
            </div>
        </div>

        <!-- Clean CTA -->
        <div class="bg-[#040e2d] text-white rounded-3xl p-8 md:p-10 flex flex-col lg:flex-row items-center justify-between gap-6 shadow-xl reveal">
            <div class="text-center lg:text-left max-w-2xl">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#ffb800]">Ready to Get Placed?</span>
                <h3 class="text-2xl md:text-3xl font-extrabold text-white mt-1.5 mb-2">Start Your Teaching Career Journey Today</h3>
                <p class="text-sm text-slate-300 leading-relaxed">
                    We are committed to a transparent, reliable recruitment experience connecting passionate educators with trusted schools across India.
                </p>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-3 shrink-0">
                <a href="{{ $startUrl }}" class="px-6 py-3 rounded-xl bg-[#ffb800] hover:bg-yellow-400 text-[#040e2d] font-extrabold text-sm transition-all shadow-lg">
                    {{ $startLabel }}
                </a>
                <a href="{{ route('pricing') }}" class="px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm border border-white/20 transition-all">
                    Plan &amp; Pricing
                </a>
                <a href="{{ route('jobs') }}" class="px-5 py-3 rounded-xl bg-[#129aef] hover:bg-[#0d85d4] text-white font-bold text-sm transition-all">
                    Browse Jobs
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
