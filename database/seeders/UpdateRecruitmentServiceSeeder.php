<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class UpdateRecruitmentServiceSeeder extends Seeder
{
    public function run()
    {
        $service = Service::where('title', 'Recruitment Services')->first();
        
        if ($service) {
            $content = '<div class="space-y-8">
                <!-- Overview Section -->
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-800 mb-4">Overview</h2>
                    <p class="text-slate-600 leading-relaxed text-lg">
                        <strong>End-to-end hiring solutions designed exclusively for educational institutions.</strong><br><br>
                        We connect schools, colleges, and academic organizations with highly qualified educators and professionals. From sourcing top talent to final placement, our streamlined process ensures you hire the right candidate—faster, smarter, and more efficiently.
                    </p>
                </div>
                
                <!-- Features Grid Section -->
                <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100">
                    <h2 class="text-2xl font-bold text-slate-800 mb-8 text-center">What We Offer</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Feature 1 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-search"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Talent Sourcing</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Access a wide pool of verified and highly skilled educators.</p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Screening & Shortlisting</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">We evaluate and present only the most suitable candidates.</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Role-Based Hiring</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Hire for teaching, administration, leadership, and support roles.</p>
                            </div>
                        </div>

                        <!-- Feature 4 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Fast Hiring Process</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Reduce hiring time with our optimized recruitment system.</p>
                            </div>
                        </div>

                        <!-- Feature 5 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-globe-asia"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Global Talent Reach</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Connect with educators across India and worldwide.</p>
                            </div>
                        </div>

                        <!-- Feature 6 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">End-to-End Support</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">From job posting to final onboarding—we handle everything.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>';
            
            $service->content = $content;
            $service->save();
        }
    }
}
