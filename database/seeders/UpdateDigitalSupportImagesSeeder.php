<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class UpdateDigitalSupportImagesSeeder extends Seeder
{
    public function run()
    {
        $service = Service::where('title', 'Digital Support')->first();
        
        if ($service) {
            $content = '
            <div class="py-4">
                
                <!-- Section 1: Intro (Image Right) -->
                <div class="flex flex-col lg:flex-row items-center gap-12 py-16">
                    <div class="w-full lg:w-1/2">
                        <h4 class="text-[#129aef] font-bold uppercase tracking-wider text-sm mb-2">Complete Digital Transformation</h4>
                        <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-800 mb-6">Digital Support</h2>
                        <p class="text-slate-600 leading-relaxed mb-6">
                            Complete digital support including professional school website development, ERP setup & management, online admission system, student data management, social media handling and institutional branding support.
                        </p>
                        <p class="text-slate-600 leading-relaxed mb-8">
                            We help schools, colleges, and educational institutions build a strong digital ecosystem for smooth operations and better management.
                        </p>
                        <a href="/contact" class="inline-block bg-[#129aef] text-white font-semibold px-8 py-3 rounded-full shadow-lg hover:shadow-xl hover:bg-[#0f80c6] hover:-translate-y-1 transition-all duration-300">
                            Get Digital Support
                        </a>
                    </div>
                    <div class="w-full lg:w-1/2">
                        <img src="/images/digital_support.png" alt="Digital Support Team" class="w-full h-auto rounded-2xl shadow-lg object-cover" style="max-height: 450px;">
                    </div>
                </div>

                <div class="w-full h-px bg-slate-200 my-8"></div>

                <!-- Section 2: Website Dev (Image Right) -->
                <div class="flex flex-col lg:flex-row items-center gap-12 py-16">
                    <div class="w-full lg:w-1/2">
                        <h2 class="text-3xl font-extrabold text-slate-800 mb-6">School Website Development</h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            We create modern, responsive and user-friendly school websites that reflect your institution\'s identity, values and professionalism.
                        </p>
                        <p class="text-slate-600 leading-relaxed mb-6">
                            From admission forms to notices and updates, everything is designed for better communication and easy content management.
                        </p>
                        <ul class="space-y-4 text-slate-700 font-medium">
                            <li class="flex items-center gap-3"><i class="fas fa-check text-[#129aef] bg-blue-50 p-1.5 rounded-full"></i> Custom design aligned with branding</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-[#129aef] bg-blue-50 p-1.5 rounded-full"></i> Mobile-friendly & SEO optimized</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-[#129aef] bg-blue-50 p-1.5 rounded-full"></i> Admission forms & inquiry management</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-[#129aef] bg-blue-50 p-1.5 rounded-full"></i> Gallery, notices and updates</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-[#129aef] bg-blue-50 p-1.5 rounded-full"></i> Easy content management</li>
                        </ul>
                    </div>
                    <div class="w-full lg:w-1/2">
                        <img src="/images/website_dev.png" alt="School Website Development" class="w-full h-auto rounded-2xl shadow-lg object-cover" style="max-height: 450px;">
                    </div>
                </div>

                <div class="w-full h-px bg-slate-200 my-8"></div>

                <!-- Section 3: ERP System (Image Left) -->
                <div class="flex flex-col lg:flex-row-reverse items-center gap-12 py-16">
                    <div class="w-full lg:w-1/2">
                        <h2 class="text-3xl font-extrabold text-slate-800 mb-6">School ERP System</h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            A centralized ERP system helps manage academic and administrative operations efficiently from one secure platform.
                        </p>
                        <p class="text-slate-600 leading-relaxed mb-6">
                            From attendance to fee reports and exam management, everything becomes faster, smarter and easier to control.
                        </p>
                        <ul class="space-y-4 text-slate-700 font-medium">
                            <li class="flex items-center gap-3"><i class="fas fa-check text-[#129aef] bg-blue-50 p-1.5 rounded-full"></i> Student & Staff Management</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-[#129aef] bg-blue-50 p-1.5 rounded-full"></i> Attendance Tracking</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-[#129aef] bg-blue-50 p-1.5 rounded-full"></i> Fee Management & Reports</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-[#129aef] bg-blue-50 p-1.5 rounded-full"></i> Exam & Result Management</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check text-[#129aef] bg-blue-50 p-1.5 rounded-full"></i> Timetable & Class Scheduling</li>
                        </ul>
                    </div>
                    <div class="w-full lg:w-1/2">
                        <img src="/images/erp_system.png" alt="School ERP System" class="w-full h-auto rounded-2xl shadow-lg object-cover" style="max-height: 450px;">
                    </div>
                </div>

            </div>';
            
            $service->content = $content;
            $service->save();
        }
    }
}
