<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class UpdateServiceContentSeeder extends Seeder
{
    public function run()
    {
        $services = Service::all();
        
        foreach ($services as $s) {
            $content = '<div class="space-y-8">
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-800 mb-4">Overview</h2>
                    <p class="text-slate-600 leading-relaxed text-lg">
                        Welcome to our specialized <strong>' . $s->title . '</strong>. We provide comprehensive solutions designed to address the unique challenges of modern educational institutions. Our approach combines industry expertise with innovative strategies to ensure your success.
                    </p>
                </div>
                
                <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100">
                    <h2 class="text-2xl font-bold text-slate-800 mb-6">What We Offer</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 mb-2">Tailored Solutions</h4>
                                <p class="text-slate-600 text-sm">Customized strategies that specifically meet your institution\'s requirements.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 mb-2">Fast Execution</h4>
                                <p class="text-slate-600 text-sm">Efficient processes that save time without compromising on quality.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 mb-2">Expert Team</h4>
                                <p class="text-slate-600 text-sm">Dedicated professionals with years of experience in the education sector.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 mb-2">24/7 Support</h4>
                                <p class="text-slate-600 text-sm">Round-the-clock assistance to address any queries or concerns.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-800 mb-4">Why Choose Us?</h2>
                    <p class="text-slate-600 leading-relaxed">
                        By partnering with Vedanta Placement Agency for your ' . strtolower($s->title) . ' needs, you are choosing a trusted ally committed to excellence. We leverage our extensive network and deep industry knowledge to deliver unparalleled results. Let us help you take your institution to the next level.
                    </p>
                </div>
            </div>';
            
            $s->content = $content;
            $s->save();
        }
    }
}
