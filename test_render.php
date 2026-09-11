<?php
require __DIR__ . '/vendor/autoload.php';
 = require_once __DIR__ . '/bootstrap/app.php';
 = ->make(Illuminate\Contracts\Console\Kernel::class);
->bootstrap();

try {
    view('welcome', [
        'recentJobs' => \App\Models\JobPost::with(['category', 'subject', 'state', 'city', 'qualification'])->where('status', 'approved')->latest()->take(6)->get(),
        'categories' => \App\Models\Category::withCount(['jobs' => function() { ->where('status', 'approved'); }])->where('is_active', true)->get(),
        'services' => \App\Models\Service::where('is_active', true)->get(),
        'testimonials' => \App\Models\Testimonial::where('is_active', true)->latest()->get(),
        'clients' => \App\Models\ClientLogo::where('is_active', true)->latest()->get(),
        'totalJobs' => 10,
        'totalApplications' => 20,
        'totalTeachers' => 30,
        'totalSchools' => 40,
        'allCategories' => \App\Models\Category::with('subjects')->where('is_active', true)->orderBy('name')->get(),
    ])->render();
    echo 'SUCCESS';
} catch (\Throwable ) {
    echo 'ERROR: ' . ->getMessage() . PHP_EOL;
    echo 'FILE: ' . ->getFile() . ':' . ->getLine() . PHP_EOL;
}
