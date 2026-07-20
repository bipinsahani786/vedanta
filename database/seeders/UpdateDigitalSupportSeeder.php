<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class UpdateDigitalSupportSeeder extends Seeder
{
    public function run()
    {
        $service = Service::where('title', 'Digital Support')->first();
        
        if ($service) {
            $content = '
            <div class="space-y-16 py-8">
                
                <!-- Section 1: Intro (Image Right) -->
                <div class="flex flex-col lg:flex-row items-center gap-12">
                    <div class="w-full lg:w-1/2">
                        <h4 class="text-[#129aef] font-bold uppercase tracking-wider text-sm mb-2">Complete Digital Transformation</h4>
                        <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-800 mb-6">Digital Support</h2>
                        <p class="text-slate-600 leading-relaxed mb-6">
                            Complete digital support including professional school website development, ERP setup & management, online admission system, student data management, social media handling and institutional branding support.
                        </p>
                        <p class="text-slate-600 leading-relaxed mb-8">
                            We help schools, colleges, and educational institutions build a strong digital ecosystem for smooth operations and better management.
                        </p>
                        <a href="/contact" class="inline-block bg-white text-slate-800 font-semibold px-8 py-3 rounded-full border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-300">
                            Get Digital Support
                        </a>
                    </div>
                    <div class="w-full lg:w-1/2">
                        <img src="/images/women.jpg" alt="Digital Support Team" class="w-full h-auto rounded-2xl shadow-lg object-cover" style="max-height: 400px;">
                    </div>
                </div>

                <hr class="border-slate-200">

                <!-- Section 2: Website Dev (Image Right) -->
                <div class="flex flex-col lg:flex-row items-center gap-12">
                    <div class="w-full lg:w-1/2">
                        <h2 class="text-3xl font-extrabold text-slate-800 mb-6">School Website Development</h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            We create modern, responsive and user-friendly school websites that reflect your institution\'s identity, values and professionalism.
                        </p>
                        <p class="text-slate-600 leading-relaxed mb-6">
                            From admission forms to notices and updates, everything is designed for better communication and easy content management.
                        </p>
                        <ul class="space-y-3 text-slate-700 font-medium">
                            <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> Custom design aligned with branding</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> Mobile-friendly & SEO optimized</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> Admission forms & inquiry management</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> Gallery, notices and updates</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> Easy content management</li>
                        </ul>
                    </div>
                    <div class="w-full lg:w-1/2">
                        <img src="/images/pic.png" alt="School Website Development" class="w-full h-auto rounded-2xl shadow-lg object-cover" style="max-height: 400px;">
                    </div>
                </div>

                <hr class="border-slate-200">

                <!-- Section 3: ERP System (Image Left) -->
                <div class="flex flex-col lg:flex-row-reverse items-center gap-12">
                    <div class="w-full lg:w-1/2">
                        <h2 class="text-3xl font-extrabold text-slate-800 mb-6">School ERP System</h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            A centralized ERP system helps manage academic and administrative operations efficiently from one secure platform.
                        </p>
                        <p class="text-slate-600 leading-relaxed mb-6">
                            From attendance to fee reports and exam management, everything becomes faster, smarter and easier to control.
                        </p>
                        <ul class="space-y-3 text-slate-700 font-medium">
                            <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> Student & Staff Management</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> Attendance Tracking</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> Fee Management & Reports</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> Exam & Result Management</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> Timetable & Class Scheduling</li>
                        </ul>
                    </div>
                    <div class="w-full lg:w-1/2">
                        <img src="/images/men.jpg" alt="School ERP System" class="w-full h-auto rounded-2xl shadow-lg object-cover" style="max-height: 400px;">
                    </div>
                </div>

            </div>';
            
            $service->content = $content;
            $service->save();
        }
    }
}
