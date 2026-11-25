@extends('layouts.app')

@section('title', 'Plan & Pricing - Registration Plans & Service Charges | Vedanta Placement Agency®')
@section('meta_description', 'Explore Vedanta Placement Agency® registration plans (Standard ₹500 & Premium ₹1,000), Pay After Joining service charges, registration steps, and transparent terms & conditions.')

@section('content')
<x-page-header title="Plan & Pricing" :breadcrumbs="['Home' => route('home'), 'Plan & Pricing' => null]" />

@php
    $isCandidate = auth()->check() && auth()->user()->role === 'candidate';
    $planActionUrl = $isCandidate ? route('candidate.payment.show') : route('candidate.register');
    $planActionLabelStandard = $isCandidate ? 'Choose Standard Plan' : 'Register with Standard';
    $planActionLabelPremium = $isCandidate ? 'Choose Premium Plan' : 'Register with Premium';
@endphp

<!-- Registration Plans Section -->
<section class="py-20 px-6 lg:px-[5%] bg-white text-slate-900 relative overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-[0.04] pointer-events-none" style="background-image: radial-gradient(#129aef 1px, transparent 1px); background-size: 28px 28px;"></div>

    <div class="max-w-6xl mx-auto relative z-10">
        <div class="text-center mb-14 reveal">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-[#129aef] text-xs font-extrabold uppercase tracking-widest mb-4">
                <i class="fas fa-tags"></i> Transparent Pricing
            </span>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-[#040e2d] mb-4">
                Candidate Registration Plans
            </h2>
            <p class="text-slate-600 max-w-2xl mx-auto text-base md:text-lg leading-relaxed">
                Choose the registration plan that matches your hiring timeline. Registration covers profile verification, screening, and interview coordination—with zero placement charges before joining.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch max-w-5xl mx-auto">
            <!-- Standard Plan Card -->
            <div class="bg-slate-50 border border-slate-200 rounded-3xl p-7 md:p-9 flex flex-col justify-between shadow-sm hover:shadow-xl hover:border-[#129aef]/40 transition-all duration-300 reveal reveal-delay-1">
                <div>
                    <div class="flex items-center justify-between gap-3 mb-5">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-[#040e2d] text-white flex items-center justify-center text-xl shadow-sm shrink-0">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-extrabold text-[#040e2d]">Standard Registration</h3>
                                <p class="text-xs font-semibold text-slate-500">Standard Access &amp; Basic Support</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-[#040e2d] text-[11px] font-bold uppercase tracking-wider shrink-0">
                            Starter
                        </span>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl p-5 mb-6">
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-black text-[#040e2d]">₹500</span>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Initial Registration Fee</span>
                        </div>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                            <i class="fas fa-info-circle text-[#129aef] mr-1"></i>
                            An additional <strong class="text-slate-800">₹500</strong> final registration balance becomes payable after interview selection (before offer letter issuance), or upgrade anytime during active validity by paying the ₹500 difference.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-white border border-slate-200 rounded-xl p-3 text-center">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Plan Validity</div>
                            <div class="text-sm font-extrabold text-[#040e2d] mt-0.5">3 Months</div>
                        </div>
                        <div class="bg-white border border-slate-200 rounded-xl p-3 text-center">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Application Limit</div>
                            <div class="text-sm font-extrabold text-[#040e2d] mt-0.5">Up to 2 Applications</div>
                        </div>
                    </div>

                    <ul class="space-y-3 text-sm text-slate-700 mb-8">
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-emerald-500 mt-0.5 shrink-0"></i>
                            <span>Process starts within 24 hours of registration</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-emerald-500 mt-0.5 shrink-0"></i>
                            <span>Candidate profile creation, verification &amp; resume screening</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-emerald-500 mt-0.5 shrink-0"></i>
                            <span>Database listing &amp; standard profile visibility</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-emerald-500 mt-0.5 shrink-0"></i>
                            <span>Interview coordination &amp; regular process updates</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-emerald-500 mt-0.5 shrink-0"></i>
                            <span>Email recruitment support</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-slate-400">
                            <i class="fas fa-times-circle text-rose-400 mt-0.5 shrink-0"></i>
                            <span>No priority processing, WhatsApp/call assistance, or early job access</span>
                        </li>
                    </ul>
                </div>

                <a href="{{ $planActionUrl }}"
                    class="w-full py-3.5 px-6 rounded-xl bg-[#040e2d] hover:bg-[#129aef] text-white font-bold text-sm text-center transition-all shadow-md hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span>{{ $planActionLabelStandard }}</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            <!-- Premium Plan Card -->
            <div class="bg-gradient-to-b from-[#040e2d] to-[#0a1e4a] text-white border-2 border-[#ffb800] rounded-3xl p-7 md:p-9 flex flex-col justify-between shadow-2xl relative reveal reveal-delay-2">
                <div class="absolute -top-3.5 right-6 px-4 py-1 rounded-full bg-[#ffb800] text-[#040e2d] text-[11px] font-black uppercase tracking-wider shadow-md flex items-center gap-1.5">
                    <i class="fas fa-crown"></i> Recommended
                </div>

                <div>
                    <div class="flex items-center justify-between gap-3 mb-5">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-[#ffb800]/15 border border-[#ffb800]/40 text-[#ffb800] flex items-center justify-center text-xl shadow-sm shrink-0">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-extrabold text-white">Premium Registration</h3>
                                <p class="text-xs font-medium text-slate-300">Priority Processing &amp; Dedicated Support</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-[11px] font-bold uppercase tracking-wider shrink-0">
                            Best Value
                        </span>
                    </div>

                    <div class="bg-white/[0.06] border border-white/15 rounded-2xl p-5 mb-6">
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-black text-[#ffb800]">₹1,000</span>
                            <span class="text-xs font-bold text-slate-300 uppercase tracking-wide">One-Time Registration Fee</span>
                        </div>
                        <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                            <i class="fas fa-shield-alt text-emerald-400 mr-1"></i>
                            Complete registration payment upfront — <strong class="text-white">no additional registration balance</strong> is payable after final selection.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-white/[0.06] border border-white/10 rounded-xl p-3 text-center">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Plan Validity</div>
                            <div class="text-sm font-extrabold text-white mt-0.5">6 Months</div>
                        </div>
                        <div class="bg-white/[0.06] border border-white/10 rounded-xl p-3 text-center">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Application Limit</div>
                            <div class="text-sm font-extrabold text-white mt-0.5">Up to 3 Applications</div>
                        </div>
                    </div>

                    <ul class="space-y-3 text-sm text-slate-100 mb-8">
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-bolt text-[#ffb800] mt-0.5 shrink-0"></i>
                            <span>Everything in Standard Plan plus <strong>same-day verification</strong> &amp; priority processing</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-emerald-400 mt-0.5 shrink-0"></i>
                            <span>Priority profile highlight &amp; early access to newly posted vacancies</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-emerald-400 mt-0.5 shrink-0"></i>
                            <span>Dedicated Relationship Manager for end-to-end assistance</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-emerald-400 mt-0.5 shrink-0"></i>
                            <span>Email, WhatsApp &amp; Priority Call support</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-emerald-400 mt-0.5 shrink-0"></i>
                            <span>Priority interview coordination &amp; faster shortlisting updates</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-emerald-400 mt-0.5 shrink-0"></i>
                            <span>Zero extra registration fee after selection</span>
                        </li>
                    </ul>
                </div>

                <a href="{{ $planActionUrl }}"
                    class="w-full py-3.5 px-6 rounded-xl bg-[#ffb800] hover:bg-yellow-400 text-[#040e2d] font-extrabold text-sm text-center transition-all shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span>{{ $planActionLabelPremium }}</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        <div class="max-w-5xl mx-auto mt-8 bg-amber-50 border border-amber-200 rounded-2xl p-4 md:p-5 flex items-start gap-3 text-xs md:text-sm text-amber-950 reveal">
            <i class="fas fa-exclamation-circle text-amber-600 text-base mt-0.5 shrink-0"></i>
            <p class="leading-relaxed">
                <strong>Important Note:</strong> Registration fees cover profile verification, resume screening, database listing, and interview coordination. Neither Standard nor Premium registration guarantees job placement, interview selection, or employment, as final hiring decisions rest solely with the school/employer.
            </p>
        </div>
    </div>
</section>

<!-- Placement Service Charges Section -->
<section class="py-20 px-6 lg:px-[5%] bg-slate-50 border-t border-slate-200 text-slate-900">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12 reveal">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-extrabold uppercase tracking-widest mb-4">
                <i class="fas fa-handshake"></i> Pay After Successful Joining
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#040e2d] mb-4">
                Placement Service Charges
            </h2>
            <p class="text-slate-600 max-w-2xl mx-auto text-base leading-relaxed">
                We follow a performance-based <strong>"Pay After Successful Joining"</strong> model. No placement service charge is collected before you accept an offer, officially join the institution, and receive your first salary.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
            <!-- Teaching Staff Service Charge -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm reveal reveal-delay-1">
                <div class="flex items-center gap-4 mb-5 pb-4 border-b border-slate-100">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#129aef] flex items-center justify-center text-xl shrink-0">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#129aef]">Category 1</span>
                        <h3 class="text-xl font-extrabold text-[#040e2d]">Teaching Staff</h3>
                    </div>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed mb-4">
                    Applicable to PRT, TGT, PGT, Principal, Vice Principal, Academic Coordinator, Head of Boarding, and all academic/teaching positions.
                </p>

                <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-4 mb-5 flex items-center justify-between gap-4">
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Service Charge Rate</div>
                        <div class="text-2xl font-black text-[#040e2d]">50% of First Month Gross Salary</div>
                        <div class="text-xs text-slate-600 mt-0.5">Approximately equivalent to 15 days' salary</div>
                    </div>
                    <div class="text-3xl font-black text-[#129aef] shrink-0">50%</div>
                </div>

                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Salary Calculation Examples</h4>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-100 text-[#040e2d]">
                                <th class="px-4 py-2.5 font-bold border-b border-slate-200">First Month Gross Salary</th>
                                <th class="px-4 py-2.5 font-bold border-b border-slate-200 text-right">Service Charge (50%)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr>
                                <td class="px-4 py-2.5">₹30,000</td>
                                <td class="px-4 py-2.5 text-right font-bold text-[#040e2d]">₹15,000</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2.5">₹40,000</td>
                                <td class="px-4 py-2.5 text-right font-bold text-[#040e2d]">₹20,000</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2.5">₹50,000</td>
                                <td class="px-4 py-2.5 text-right font-bold text-[#040e2d]">₹25,000</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2.5">₹60,000</td>
                                <td class="px-4 py-2.5 text-right font-bold text-[#040e2d]">₹30,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Non-Teaching Staff Service Charge -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm reveal reveal-delay-2">
                <div class="flex items-center gap-4 mb-5 pb-4 border-b border-slate-100">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Category 2</span>
                        <h3 class="text-xl font-extrabold text-[#040e2d]">Management / Non-Teaching Staff</h3>
                    </div>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed mb-4">
                    Applicable to Receptionist, Accountant, Admission Counsellor, Office/Admin Staff, Hostel Staff, HR, Librarian, IT Support, and similar non-teaching roles.
                </p>

                <div class="bg-amber-50/70 border border-amber-100 rounded-2xl p-4 mb-5 flex items-center justify-between gap-4">
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Service Charge Rate</div>
                        <div class="text-2xl font-black text-[#040e2d]">20 Days' Salary (66.67%)</div>
                        <div class="text-xs text-slate-600 mt-0.5">66.67% of first month's gross salary</div>
                    </div>
                    <div class="text-3xl font-black text-amber-600 shrink-0">20 Days</div>
                </div>

                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Salary Calculation Examples</h4>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-100 text-[#040e2d]">
                                <th class="px-4 py-2.5 font-bold border-b border-slate-200">First Month Gross Salary</th>
                                <th class="px-4 py-2.5 font-bold border-b border-slate-200 text-right">Service Charge (20 Days)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr>
                                <td class="px-4 py-2.5">₹30,000</td>
                                <td class="px-4 py-2.5 text-right font-bold text-[#040e2d]">₹20,000</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2.5">₹45,000</td>
                                <td class="px-4 py-2.5 text-right font-bold text-[#040e2d]">₹30,000</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2.5">₹60,000</td>
                                <td class="px-4 py-2.5 text-right font-bold text-[#040e2d]">₹40,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- EMI Facility & Direct Employer Billing -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 reveal">
            <div class="bg-gradient-to-r from-[#040e2d] to-[#0d2459] text-white rounded-3xl p-6 md:p-7 shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-[#ffb800]/20 text-[#ffb800] flex items-center justify-center">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#ffb800]">Flexible Payment</span>
                        <h3 class="text-lg font-extrabold">EMI Facility Available</h3>
                    </div>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed mb-5">
                    Candidates who need installment flexibility for placement service charges can opt for the EMI facility with nominal processing fees:
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-white/10 border border-white/15 rounded-xl p-3.5 text-center">
                        <div class="text-[11px] font-semibold text-slate-300">Teaching Staff</div>
                        <div class="text-xs text-slate-400 mt-0.5">Processing Fee</div>
                        <div class="text-xl font-black text-[#ffb800] mt-1">₹1,499</div>
                    </div>
                    <div class="bg-white/10 border border-white/15 rounded-xl p-3.5 text-center">
                        <div class="text-[11px] font-semibold text-slate-300">Non-Teaching / Mgmt</div>
                        <div class="text-xs text-slate-400 mt-0.5">Processing Fee</div>
                        <div class="text-xl font-black text-[#ffb800] mt-1">₹2,999</div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-7 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-600">Billing Transparency</span>
                            <h3 class="text-lg font-extrabold text-[#040e2d]">When Payable &amp; Direct Employer Invoicing</h3>
                        </div>
                    </div>
                    <ul class="space-y-2.5 text-xs md:text-sm text-slate-600">
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check text-emerald-500 mt-1 shrink-0"></i>
                            <span>Service charge becomes payable only after offer acceptance, official joining, and receipt of first salary (ordinarily due within 12 hours of salary credit).</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check text-emerald-500 mt-1 shrink-0"></i>
                            <span>Where agreed commercially, invoices may be billed directly to the hiring school/employer—with strict <strong>no double recovery</strong> from both candidate and employer.</span>
                        </li>
                    </ul>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-500 flex items-center gap-2">
                    <i class="fas fa-lock text-[#129aef]"></i>
                    <span>Accepted online modes: UPI, Debit/Credit Cards, Net Banking &amp; Digital Wallets.</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Registration Process & What Registration Covers -->
<section class="py-20 px-6 lg:px-[5%] bg-white border-t border-slate-200 text-slate-900">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-14 reveal">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-[#129aef] text-xs font-extrabold uppercase tracking-widest mb-4">
                <i class="fas fa-clipboard-list"></i> Registration Details
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#040e2d] mb-4">
                How Online Registration Works
            </h2>
            <p class="text-slate-600 max-w-2xl mx-auto text-base leading-relaxed">
                Complete your candidate onboarding in four simple digital steps to activate your Vedanta Placement Agency profile and start applying for verified school vacancies.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-14">
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 relative reveal reveal-delay-1">
                <div class="w-10 h-10 rounded-full bg-[#040e2d] text-white font-black text-sm flex items-center justify-center mb-4">1</div>
                <h3 class="text-base font-extrabold text-[#040e2d] mb-2">Profile &amp; Documents</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Create your account and submit personal, qualification, subject preference details, updated Resume (CV), and latest Salary Slip or Offer Letter (if experienced).
                </p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 relative reveal reveal-delay-2">
                <div class="w-10 h-10 rounded-full bg-[#129aef] text-white font-black text-sm flex items-center justify-center mb-4">2</div>
                <h3 class="text-base font-extrabold text-[#040e2d] mb-2">Service Agreement</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Review and digitally accept the Candidate Consent Form, Service Agreement, fee structure, and recruitment terms &amp; conditions online.
                </p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 relative reveal reveal-delay-3">
                <div class="w-10 h-10 rounded-full bg-emerald-600 text-white font-black text-sm flex items-center justify-center mb-4">3</div>
                <h3 class="text-base font-extrabold text-[#040e2d] mb-2">Identity Verification</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Complete digital verification with a live photograph, current location capture, and electronic signature (draw, type, or upload).
                </p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 relative reveal reveal-delay-4">
                <div class="w-10 h-10 rounded-full bg-[#ffb800] text-[#040e2d] font-black text-sm flex items-center justify-center mb-4">4</div>
                <h3 class="text-base font-extrabold text-[#040e2d] mb-2">Plan &amp; Activation</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Select Standard (₹500) or Premium (₹1,000) and complete secure online payment to activate your registration and VPA Candidate ID.
                </p>
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 md:p-8 reveal">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="lg:max-w-md">
                    <h3 class="text-xl font-extrabold text-[#040e2d] mb-2">What the Registration Fee Covers</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        The registration fee covers administrative and recruitment facilitation services—it is separate from placement service charges.
                    </p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 flex-1 text-xs font-semibold text-slate-700">
                    <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 flex items-center gap-2.5">
                        <i class="fas fa-check-circle text-[#129aef]"></i> Profile Creation &amp; Verification
                    </div>
                    <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 flex items-center gap-2.5">
                        <i class="fas fa-check-circle text-[#129aef]"></i> Resume Screening &amp; Review
                    </div>
                    <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 flex items-center gap-2.5">
                        <i class="fas fa-check-circle text-[#129aef]"></i> Database Maintenance
                    </div>
                    <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 flex items-center gap-2.5">
                        <i class="fas fa-check-circle text-[#129aef]"></i> Employer Profile Sharing
                    </div>
                    <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 flex items-center gap-2.5">
                        <i class="fas fa-check-circle text-[#129aef]"></i> Interview Scheduling
                    </div>
                    <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 flex items-center gap-2.5">
                        <i class="fas fa-check-circle text-[#129aef]"></i> Onboarding Coordination
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Relevant Terms & Policies Section -->
<section class="py-20 px-6 lg:px-[5%] bg-[#040e2d] text-white border-t border-white/10">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-14 reveal">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/15 text-[#ffb800] text-xs font-extrabold uppercase tracking-widest mb-4">
                <i class="fas fa-file-contract"></i> Key Terms &amp; Policies
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">
                Relevant Terms &amp; Conditions
            </h2>
            <p class="text-slate-300 max-w-2xl mx-auto text-base leading-relaxed">
                Summary of important registration, payment, joining, and cancellation terms applicable to all candidates and institutions.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <div class="bg-white/[0.05] border border-white/10 rounded-2xl p-6 reveal">
                <div class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-300 flex items-center justify-center mb-4">
                    <i class="fas fa-ban"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-2">Non-Refundable &amp; Non-Transferable</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Registration fees are strictly non-refundable and non-transferable once the profile is activated or process is initiated—including if a candidate is not shortlisted, not selected, declines an offer, or withdraws.
                </p>
            </div>

            <div class="bg-white/[0.05] border border-white/10 rounded-2xl p-6 reveal">
                <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-300 flex items-center justify-center mb-4">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-2">Validity &amp; Renewal</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Plans carry both time and application limits (Standard: 3 months / up to 2 applications; Premium: 6 months / up to 3 applications). Renewal applies once permitted usage or validity ends.
                </p>
            </div>

            <div class="bg-white/[0.05] border border-white/10 rounded-2xl p-6 reveal">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center mb-4">
                    <i class="fas fa-user-clock"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-2">Joining &amp; 5-Day Withdrawal Notice</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    After accepting a placement, candidates must inform the Agency at least <strong>5 calendar days</strong> before the joining date if withdrawing. Unjustified last-minute withdrawal or no-show may attract contractual compensation up to ₹5,000.
                </p>
            </div>

            <div class="bg-white/[0.05] border border-white/10 rounded-2xl p-6 reveal">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center mb-4">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-2">30-Day Continuity Expectation</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Candidates are expected to continue for at least 30 days after joining. Leaving or verified discontinuation attributable after joining may attract a 30% first-month gross salary discontinuation charge following verification.
                </p>
            </div>

            <div class="bg-white/[0.05] border border-white/10 rounded-2xl p-6 reveal">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-300 flex items-center justify-center mb-4">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-2">Payment Timeline &amp; Late Charges</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Applicable service charges are due within 12 hours of first salary credit. Overdue invoices may incur a ₹300/day late charge and applicable interest subject to Indian contract law.
                </p>
            </div>

            <div class="bg-white/[0.05] border border-white/10 rounded-2xl p-6 reveal">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-300 flex items-center justify-center mb-4">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-2">Authenticity, Taxes &amp; Jurisdiction</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    All submitted records must be authentic and non-circumvention applies to Agency-introduced openings. Fees exclude statutory taxes/GST where applicable. Disputes are subject to Patna, Bihar jurisdiction.
                </p>
            </div>
        </div>

        <div class="bg-white/[0.04] border border-white/10 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-4 reveal">
            <div class="text-center md:text-left">
                <h4 class="text-base font-bold text-white">Need Full Legal Details or Have Questions?</h4>
                <p class="text-xs text-slate-300 mt-1">Read our complete policy pages or reach out to our support team before registering.</p>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('terms') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-colors border border-white/15">
                    Terms &amp; Conditions
                </a>
                <a href="{{ route('refund') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-colors border border-white/15">
                    Refund Policy
                </a>
                <a href="{{ route('hiring') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-colors border border-white/15">
                    Hiring Process
                </a>
                <a href="{{ route('contact') }}" class="px-5 py-2.5 rounded-xl bg-[#129aef] hover:bg-[#0d85d4] text-white text-xs font-bold transition-colors shadow-md">
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
