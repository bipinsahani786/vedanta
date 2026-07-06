<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class UpdateResumeServiceSeeder extends Seeder
{
    public function run()
    {
        $service = Service::where('title', 'Resume Services')->first();
        
        if ($service) {
            $content = '<div class="space-y-8">
                <!-- Overview Section -->
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-800 mb-4">Overview</h2>
                    <p class="text-slate-600 leading-relaxed text-lg">
                        <strong>Stand out with a professional resume tailored specifically for the education sector.</strong><br><br>
                        Your resume is your first impression. We help teachers, principals, professors, and administrative staff create compelling, modern, and ATS-friendly resumes that effectively highlight their achievements, teaching methodologies, and leadership skills to secure interviews at top educational institutions.
                    </p>
                </div>
                
                <!-- Features Grid Section -->
                <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100">
                    <h2 class="text-2xl font-bold text-slate-800 mb-8 text-center">What We Offer</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Feature 1 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Resume Formatting</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Modern, clean, and ATS-friendly templates designed to catch the recruiter\'s eye.</p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-highlighter"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Content Optimization</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Highlighting key skills, educational achievements, and impact-driven results.</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-envelope-open-text"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Cover Letter Writing</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Customized and persuasive cover letters crafted for your target teaching roles.</p>
                            </div>
                        </div>

                        <!-- Feature 4 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fab fa-linkedin-in"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">LinkedIn Profile Setup</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Optimizing your digital presence for professional networking and headhunters.</p>
                            </div>
                        </div>

                        <!-- Feature 5 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Portfolio Development</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Guidance on showcasing teaching materials, lesson plans, and student results.</p>
                            </div>
                        </div>

                        <!-- Feature 6 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Career Consultation</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">One-on-one expert advice to prepare for interviews and negotiate salaries.</p>
                            </div>
                        </div>

                    </div>
                </div>
                
                <!-- Why Choose Us Section -->
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-800 mb-4">Why Choose Us?</h2>
                    <p class="text-slate-600 leading-relaxed text-lg">
                        We know exactly what top schools and universities are looking for. Our team of expert writers understands the nuances of the education sector and ensures your profile stands out from the crowd. Let us help you take the next big step in your academic career.
                    </p>
                </div>
            </div>';
            
            $service->content = $content;
            $service->save();
        }
    }
}
