@extends('layouts.app')
@section('content')
<x-page-header title="Our Hiring Process" :breadcrumbs="['Home' => route('home'), 'Hiring Process' => null]" />

<div class="py-20 px-6 lg:px-[5%] bg-[#040e2d] relative font-sans overflow-hidden min-h-screen">


    <div class="max-w-5xl mx-auto relative z-10">
        
        <div class="text-center mb-16 reveal">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4 drop-shadow-md">Step-by-Step Hiring Process</h2>
            <p class="text-slate-300 max-w-2xl mx-auto font-light leading-relaxed">Our streamlined recruitment process is designed to connect talented educators with top institutions efficiently.</p>
        </div>

        <div class="space-y-12 relative before:absolute before:inset-0 before:ml-8 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-1 before:bg-gradient-to-b before:from-[#ffb800] before:via-accent-blue before:to-accent-yellow">

            <!-- Step 1 -->
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group reveal">
                <div class="flex items-center justify-center w-16 h-16 rounded-full border-4 border-white bg-[#031b4e] text-white shadow-xl shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                    <span class="text-2xl font-bold">1</span>
                </div>
                
                <div class="w-[calc(100%-4.5rem)] md:w-[calc(50%-3rem)] bg-white p-6 md:p-8 rounded-2xl shadow-lg border border-slate-100 hover:shadow-xl transition-all duration-300 ml-5 md:ml-0 group-hover:-translate-y-1">
                    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#031b4e] flex items-center justify-center text-xl shrink-0 shadow-inner">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h3 class="text-xl font-bold text-[#031b4e]">Document Submission</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-[13px] font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2"><i class="fas fa-upload text-accent-blue"></i> Submit</h4>
                            <ul class="space-y-2 text-[13px] text-slate-600">
                                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-0.5"></i> Updated Resume (CV)</li>
                                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-0.5"></i> Latest Salary Slip OR Offer Letter</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-[13px] font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2"><i class="fas fa-bullseye text-accent-blue"></i> Purpose</h4>
                            <ul class="space-y-2 text-[13px] text-slate-600">
                                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-0.5"></i> Profile Evaluation</li>
                                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-0.5"></i> Eligibility Check</li>
                                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-0.5"></i> Vacancy Matching</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group reveal reveal-delay-1">
                <div class="flex items-center justify-center w-16 h-16 rounded-full border-4 border-white bg-accent-yellow text-slate-900 shadow-xl shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                    <span class="text-2xl font-bold">2</span>
                </div>
                
                <div class="w-[calc(100%-4.5rem)] md:w-[calc(50%-3rem)] bg-white p-6 md:p-8 rounded-2xl shadow-lg border border-slate-100 hover:shadow-xl transition-all duration-300 ml-5 md:ml-0 group-hover:-translate-y-1">
                    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-yellow-50 text-accent-yellow flex items-center justify-center text-xl shrink-0 shadow-inner">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Registration Process</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-5">
                        <div class="border border-slate-200 rounded-xl p-4 relative bg-slate-50 transition-colors hover:border-accent-blue/30">
                            <div class="text-center mb-3">
                                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wide">Standard Plan</h4>
                                <div class="text-xl font-extrabold text-accent-blue mt-1">₹500</div>
                            </div>
                            <ul class="space-y-2 text-[12px] text-slate-600">
                                <li class="flex items-start gap-1.5"><i class="fas fa-check-circle text-accent-blue mt-0.5"></i> Process starts within 24 hours</li>
                                <li class="flex items-start gap-1.5"><i class="fas fa-check-circle text-accent-blue mt-0.5"></i> Validity: 3 Months</li>
                                <li class="flex items-start gap-1.5"><i class="fas fa-check-circle text-accent-blue mt-0.5"></i> Up to 2 Interview Opportunities</li>
                                <li class="flex items-start gap-1.5"><i class="fas fa-check-circle text-accent-blue mt-0.5"></i> ₹500 (After Selection)</li>
                            </ul>
                        </div>
                        <div class="border-2 border-accent-yellow rounded-xl p-4 relative bg-[#fffdf5] shadow-[0_4px_15px_rgba(255,184,0,0.1)]">
                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-accent-yellow text-slate-900 text-[9px] font-bold px-2.5 py-0.5 rounded-full uppercase whitespace-nowrap shadow-sm">Recommended</div>
                            <div class="text-center mb-3 mt-1">
                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Premium Plan</h4>
                                <div class="text-xl font-extrabold text-accent-yellow mt-1 drop-shadow-sm">₹1000</div>
                            </div>
                            <ul class="space-y-2 text-[12px] text-slate-700 font-medium">
                                <li class="flex items-start gap-1.5"><i class="fas fa-star text-accent-yellow mt-0.5"></i> Priority Processing</li>
                                <li class="flex items-start gap-1.5"><i class="fas fa-star text-accent-yellow mt-0.5"></i> Same-Day Verification</li>
                                <li class="flex items-start gap-1.5"><i class="fas fa-star text-accent-yellow mt-0.5"></i> Up to 3 Interview Opportunities</li>
                                <li class="flex items-start gap-1.5"><i class="fas fa-star text-accent-yellow mt-0.5"></i> Priority Vacancies</li>
                                <li class="flex items-start gap-1.5"><i class="fas fa-star text-accent-yellow mt-0.5"></i> Validity: 6 Months</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="bg-red-50 border border-red-100 p-3 rounded-lg text-[11px] text-red-700 flex gap-2.5 items-start shadow-sm">
                        <i class="fas fa-exclamation-triangle mt-0.5"></i>
                        <span><strong class="font-bold">IMPORTANT:</strong> Registration charges are non-refundable after process initiation.</span>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group reveal reveal-delay-2">
                <div class="flex items-center justify-center w-16 h-16 rounded-full border-4 border-white bg-[#10b981] text-white shadow-xl shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                    <span class="text-2xl font-bold">3</span>
                </div>
                
                <div class="w-[calc(100%-4.5rem)] md:w-[calc(50%-3rem)] bg-white p-6 md:p-8 rounded-2xl shadow-lg border border-slate-100 hover:shadow-xl transition-all duration-300 ml-5 md:ml-0 group-hover:-translate-y-1">
                    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0 shadow-inner">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Candidate Agreement</h3>
                    </div>
                    
                    <p class="text-[13px] text-slate-600 mb-4 font-medium">Before proceeding, candidates review and sign:</p>
                    <div class="flex items-center gap-6">
                        <ul class="space-y-3.5 text-[13px] text-slate-700 font-semibold flex-1">
                            <li class="flex items-center gap-3 bg-slate-50 p-2.5 rounded-lg border border-slate-100"><i class="fas fa-check-circle text-emerald-500 text-lg"></i> Candidate Consent Form</li>
                            <li class="flex items-center gap-3 bg-slate-50 p-2.5 rounded-lg border border-slate-100"><i class="fas fa-check-circle text-emerald-500 text-lg"></i> Service Agreement</li>
                            <li class="flex items-center gap-3 bg-slate-50 p-2.5 rounded-lg border border-slate-100"><i class="fas fa-check-circle text-emerald-500 text-lg"></i> Terms & Conditions Acceptance</li>
                        </ul>
                        <div class="hidden sm:flex text-6xl text-emerald-600/10 pr-4">
                            <i class="fas fa-file-signature"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group reveal reveal-delay-3">
                <div class="flex items-center justify-center w-16 h-16 rounded-full border-4 border-white bg-[#8b5cf6] text-white shadow-xl shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                    <span class="text-2xl font-bold">4</span>
                </div>
                
                <div class="w-[calc(100%-4.5rem)] md:w-[calc(50%-3rem)] bg-white p-6 md:p-8 rounded-2xl shadow-lg border border-slate-100 hover:shadow-xl transition-all duration-300 ml-5 md:ml-0 group-hover:-translate-y-1">
                    <div class="flex items-center gap-4 mb-4 pb-4 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-xl shrink-0 shadow-inner">
                            <i class="fas fa-video"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-slate-800 mb-1">Introduction Video</h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-violet-100 text-violet-700 px-2.5 py-0.5 rounded-full">Duration: 1-2 Minutes</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-6 items-center sm:items-start">
                        <div class="flex-1 w-full">
                            <p class="text-[13px] font-bold text-slate-800 mb-3 uppercase tracking-wide">Include in your video:</p>
                            <ul class="space-y-2 text-[13px] text-slate-600 list-disc list-inside marker:text-violet-500">
                                <li>Personal Introduction</li>
                                <li>Educational Qualification</li>
                                <li>Experience</li>
                                <li>Subject / Position Applied For</li>
                            </ul>
                        </div>
                        <div class="w-full sm:w-28 h-28 bg-slate-50 rounded-xl flex items-center justify-center text-violet-200 text-5xl relative overflow-hidden shrink-0 border border-slate-200 shadow-inner">
                            <i class="fas fa-desktop"></i>
                            <div class="absolute inset-0 flex items-center justify-center text-violet-500 drop-shadow-md">
                                <i class="fas fa-play-circle text-4xl bg-white rounded-full"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 bg-violet-50 border border-violet-100 p-3.5 rounded-xl flex gap-3 items-start shadow-sm">
                        <div class="bg-violet-200 text-violet-700 w-6 h-6 rounded-full flex items-center justify-center shrink-0 mt-0.5"><i class="far fa-lightbulb text-[11px]"></i></div>
                        <p class="text-[12px] text-violet-900 font-medium leading-relaxed">Speak confidently and professionally. This helps us understand you better!</p>
                    </div>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group reveal reveal-delay-4">
                <div class="flex items-center justify-center w-16 h-16 rounded-full border-4 border-white bg-accent-blue text-white shadow-xl shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                    <span class="text-2xl font-bold">5</span>
                </div>
                
                <div class="w-[calc(100%-4.5rem)] md:w-[calc(50%-3rem)] bg-white p-6 md:p-8 rounded-2xl shadow-lg border border-slate-100 hover:shadow-xl transition-all duration-300 ml-5 md:ml-0 group-hover:-translate-y-1">
                    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-accent-blue flex items-center justify-center text-xl shrink-0 shadow-inner relative overflow-hidden">
                            <i class="fas fa-trophy text-accent-yellow absolute z-10 drop-shadow-sm"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Interview & Selection</h3>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-6 items-center">
                        <ul class="space-y-3.5 text-[13px] text-slate-700 font-semibold flex-1 w-full">
                            <li class="flex items-center gap-3 bg-slate-50 p-2.5 rounded-lg border border-slate-100"><i class="fas fa-check-circle text-accent-blue text-lg"></i> Profile Shortlisting</li>
                            <li class="flex items-center gap-3 bg-slate-50 p-2.5 rounded-lg border border-slate-100"><i class="fas fa-check-circle text-accent-blue text-lg"></i> School Interview Coordination</li>
                            <li class="flex items-center gap-3 bg-slate-50 p-2.5 rounded-lg border border-slate-100"><i class="fas fa-check-circle text-accent-blue text-lg"></i> Final Selection by School</li>
                            <li class="flex items-center gap-3 bg-slate-50 p-2.5 rounded-lg border border-slate-100"><i class="fas fa-check-circle text-accent-blue text-lg"></i> Offer Confirmation</li>
                        </ul>
                        <div class="hidden sm:flex text-6xl text-accent-blue/10 pr-4">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    
                </div>
            </div>

        <!-- Step 6 -->
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group reveal reveal-delay-1">
                <div class="flex items-center justify-center w-16 h-16 rounded-full border-4 border-white bg-[#ea580c] text-white shadow-xl shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                    <span class="text-2xl font-bold">6</span>
                </div>
                
                <div class="w-[calc(100%-4.5rem)] md:w-[calc(50%-3rem)] bg-white p-6 md:p-8 rounded-2xl shadow-lg border border-slate-100 hover:shadow-xl transition-all duration-300 ml-5 md:ml-0 group-hover:-translate-y-1">
                    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl shrink-0 shadow-inner">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Joining & Service Charges</h3>
                    </div>
                    
                    <div class="mb-5 bg-orange-50/70 p-4 rounded-xl border border-orange-100 flex flex-col sm:flex-row items-center gap-4 justify-between">
                        <div>
                            <h4 class="text-[13px] font-bold text-slate-800 uppercase tracking-wide mb-2.5">Payable Only After:</h4>
                            <ul class="space-y-2 text-[13px] text-slate-700 font-medium">
                                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-orange-500"></i> Successful Joining</li>
                                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-orange-500"></i> First Salary Received</li>
                            </ul>
                        </div>
                        <div class="text-5xl text-orange-400 opacity-80 drop-shadow-sm px-4">
                            <i class="fas fa-sack-dollar"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                        <div class="border border-slate-200 rounded-xl p-4 text-center bg-white shadow-sm transition-colors hover:border-orange-300">
                            <h4 class="text-[11px] font-bold text-orange-600 uppercase tracking-wide mb-3">Teaching Staff</h4>
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="text-3xl text-slate-700 mb-1"><i class="fas fa-chalkboard-teacher"></i></div>
                                <div>
                                    <div class="text-2xl font-extrabold text-slate-800 leading-none">50%</div>
                                    <div class="text-[10px] text-slate-500 uppercase font-bold mt-1">of One Month Salary</div>
                                </div>
                            </div>
                        </div>
                        <div class="border border-slate-200 rounded-xl p-4 text-center bg-white shadow-sm transition-colors hover:border-orange-300">
                            <h4 class="text-[11px] font-bold text-orange-600 uppercase tracking-wide mb-3">Management / Non-Teaching</h4>
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="text-3xl text-slate-700 mb-1"><i class="fas fa-briefcase"></i></div>
                                <div>
                                    <div class="text-2xl font-extrabold text-slate-800 leading-none">20 Days</div>
                                    <div class="text-[10px] text-slate-500 uppercase font-bold mt-1">Salary <br><span class="text-[8px] opacity-70">(66.67% of One Month)</span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-orange-600 to-orange-500 text-white rounded-xl overflow-hidden shadow-md mb-5">
                        <div class="text-center py-2 bg-black/10 text-[11px] font-bold uppercase tracking-widest border-b border-white/20">EMI Facility Available</div>
                        <div class="grid grid-cols-2 divide-x divide-white/20 p-3.5">
                            <div class="text-center">
                                <div class="text-[10px] font-medium opacity-90 mb-1 uppercase tracking-wide">Teaching Staff</div>
                                <div class="text-[10px] opacity-80 mb-0.5">Processing Fee</div>
                                <div class="text-lg font-bold drop-shadow-sm">₹1499</div>
                            </div>
                            <div class="text-center">
                                <div class="text-[10px] font-medium opacity-90 mb-1 uppercase tracking-wide">Management / Non-Teaching</div>
                                <div class="text-[10px] opacity-80 mb-0.5">Processing Fee</div>
                                <div class="text-lg font-bold drop-shadow-sm">₹2999</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 border border-slate-200 p-3.5 rounded-xl text-[11px] text-slate-600 flex gap-3 items-start shadow-sm">
                        <div class="bg-[#031b4e] text-white w-5 h-5 rounded-full flex items-center justify-center shrink-0 mt-0.5 text-[9px]"><i class="fas fa-info"></i></div>
                        <p class="font-medium leading-relaxed">Service charges are performance-based and payable only after successful employment through Vedanta Placement Agency.</p>
                    </div>
                </div>
            </div>

        </div> <!-- End of timeline container -->

        <!-- Bottom Sections -->
        <div class="mt-20 max-w-4xl mx-auto space-y-8 reveal">
            <!-- Vacancy & Application Process -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-[#031b4e] text-white text-center py-3 font-bold uppercase tracking-wider text-[13px]">
                    Vacancy & Application Process
                </div>
                <div class="p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-4 md:gap-2 text-center relative bg-slate-50/50">
                    <!-- Step A -->
                    <div class="flex-1 flex flex-col items-center z-10">
                        <div class="w-14 h-14 rounded-full bg-white border border-slate-200 text-[#031b4e] flex items-center justify-center text-xl mb-3 shadow-sm hover:scale-110 transition-transform">
                            <i class="fas fa-school"></i>
                        </div>
                        <p class="text-[11px] text-slate-700 font-semibold leading-tight max-w-[120px]">Vacancies received from reputed schools</p>
                    </div>
                    <!-- Arrow -->
                    <div class="hidden md:block text-slate-300 text-lg"><i class="fas fa-chevron-right"></i></div>
                    <div class="md:hidden text-slate-300 text-lg my-1"><i class="fas fa-chevron-down"></i></div>
                    
                    <!-- Step B -->
                    <div class="flex-1 flex flex-col items-center z-10">
                        <div class="w-14 h-14 rounded-full bg-white border border-slate-200 text-[#031b4e] flex items-center justify-center text-xl mb-3 shadow-sm hover:scale-110 transition-transform">
                            <i class="fas fa-search"></i>
                        </div>
                        <p class="text-[11px] text-slate-700 font-semibold leading-tight max-w-[120px]">Careful review & sharing of details</p>
                    </div>
                    <!-- Arrow -->
                    <div class="hidden md:block text-slate-300 text-lg"><i class="fas fa-chevron-right"></i></div>
                    <div class="md:hidden text-slate-300 text-lg my-1"><i class="fas fa-chevron-down"></i></div>
                    
                    <!-- Step C -->
                    <div class="flex-1 flex flex-col items-center z-10">
                        <div class="w-14 h-14 rounded-full bg-white border border-slate-200 text-[#031b4e] flex items-center justify-center text-xl mb-3 shadow-sm hover:scale-110 transition-transform">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <p class="text-[11px] text-slate-700 font-semibold leading-tight max-w-[120px]">Submit updated Resume (CV)</p>
                    </div>
                    <!-- Arrow -->
                    <div class="hidden md:block text-slate-300 text-lg"><i class="fas fa-chevron-right"></i></div>
                    <div class="md:hidden text-slate-300 text-lg my-1"><i class="fas fa-chevron-down"></i></div>
                    
                    <!-- Step D -->
                    <div class="flex-1 flex flex-col items-center z-10">
                        <div class="w-14 h-14 rounded-full bg-white border border-slate-200 text-[#031b4e] flex items-center justify-center text-xl mb-3 shadow-sm hover:scale-110 transition-transform">
                            <i class="fas fa-users"></i>
                        </div>
                        <p class="text-[11px] text-slate-700 font-semibold leading-tight max-w-[120px]">Profile evaluation & shortlisting</p>
                    </div>
                    <!-- Arrow -->
                    <div class="hidden md:block text-slate-300 text-lg"><i class="fas fa-chevron-right"></i></div>
                    <div class="md:hidden text-slate-300 text-lg my-1"><i class="fas fa-chevron-down"></i></div>
                    
                    <!-- Step E -->
                    <div class="flex-1 flex flex-col items-center z-10">
                        <div class="w-14 h-14 rounded-full bg-[#031b4e] border border-[#031b4e] text-white flex items-center justify-center text-xl mb-3 shadow-md hover:scale-110 transition-transform">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <p class="text-[11px] text-slate-800 font-bold leading-tight max-w-[120px]">Interview coordination & final selection</p>
                    </div>
                </div>
            </div>
            
            <!-- Conclusion -->
            <div class="bg-[#031b4e] rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row items-center p-8 gap-6 text-white relative">
                <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.4) 0%, transparent 40%);"></div>
                <div class="flex-1 relative z-10 text-center md:text-left">
                    <h4 class="text-accent-yellow font-bold uppercase tracking-wider mb-3 text-sm">Conclusion</h4>
                    <p class="text-[13px] leading-relaxed text-slate-200 opacity-90 max-w-2xl">We appreciate your trust in Vedanta Placement Agency and look forward to supporting your professional journey. Our team remains committed to providing a transparent, efficient, and reliable recruitment experience while helping candidates connect with suitable career opportunities across India.</p>
                </div>
                <div class="text-6xl text-accent-yellow opacity-80 relative z-10 shrink-0 hidden md:block">
                    <i class="fas fa-users-cog"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection