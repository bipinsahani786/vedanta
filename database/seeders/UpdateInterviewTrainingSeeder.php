<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class UpdateInterviewTrainingSeeder extends Seeder
{
    public function run()
    {
        $service = Service::where('title', 'Interview Training')->first();
        
        if ($service) {
            $content = '<div class="space-y-8">
                <!-- Overview Section -->
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-800 mb-4">Overview</h2>
                    <p class="text-slate-600 leading-relaxed text-lg">
                        <strong>Comprehensive interview preparation for educators and academic professionals.</strong><br><br>
                        Succeeding in educational interviews requires more than just subject knowledge; it requires the right presentation, pedagogy demonstration, and confidence. We equip you with the skills and strategies needed to excel in interviews and secure placements at top schools and educational institutions.
                    </p>
                </div>
                
                <!-- Features Grid Section -->
                <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100">
                    <h2 class="text-2xl font-bold text-slate-800 mb-8 text-center">What We Offer</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Feature 1 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-video"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Mock Interviews</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Realistic practice sessions with industry experts to simulate actual interview environments.</p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Demo Class Preparation</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Guidance on designing and delivering engaging, impactful demo lessons for interviewers.</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-brain"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Confidence Building</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Proven techniques and psychological tips to overcome nervousness and project authority.</p>
                            </div>
                        </div>

                        <!-- Feature 4 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Subject Matter Review</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Refining your answers for complex subject-specific and pedagogical questions.</p>
                            </div>
                        </div>

                        <!-- Feature 5 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Body Language & Etiquette</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Mastering non-verbal communication, professional attire, and interview etiquette.</p>
                            </div>
                        </div>

                        <!-- Feature 6 -->
                        <div class="flex gap-5 bg-white p-6 rounded-xl shadow-sm border border-slate-100 transition-transform hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#129aef] flex items-center justify-center shrink-0 text-xl shadow-sm">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg mb-2">Salary Negotiation</h4>
                                <p class="text-slate-600 text-sm leading-relaxed">Effective strategies to discuss compensation and secure the best possible package.</p>
                            </div>
                        </div>

                    </div>
                </div>
                
                <!-- Why Choose Us Section -->
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-800 mb-4">Why Choose Us?</h2>
                    <p class="text-slate-600 leading-relaxed text-lg">
                        With deep insights into what school management and recruitment panels look for, our specialized training programs give you a distinct competitive advantage. We transform nervous applicants into polished professionals ready to excel.
                    </p>
                </div>
            </div>';
            
            $service->content = $content;
            $service->save();
        }
    }
}
