<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Str;

class NewServicesSeeder extends Seeder
{
    public function run()
    {
        Service::truncate();
        
        $services = [
            [
                'title' => 'Recruitment Services',
                'description' => 'Comprehensive hiring solutions tailored for educational institutions.',
                'icon' => 'fas fa-users-viewfinder',
            ],
            [
                'title' => 'Digital Support',
                'description' => 'End-to-end digital enablement and support for modern schools.',
                'icon' => 'fas fa-globe',
            ],
            [
                'title' => 'Resume Services',
                'description' => 'Professional resume building and optimization for educators.',
                'icon' => 'fas fa-file-signature',
            ],
            [
                'title' => 'Interview Training',
                'description' => 'Expert guidance to excel in educational interviews and placements.',
                'icon' => 'fas fa-chalkboard-teacher',
            ]
        ];

        foreach ($services as $s) {
            Service::create([
                'title' => $s['title'],
                'slug' => Str::slug($s['title']),
                'description' => $s['description'],
                'content' => '<p>Welcome to our <strong>' . $s['title'] . '</strong>. We provide top-notch support and expertise to help you achieve your goals.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p><p>Contact us today to learn more about how we can assist you with our specialized ' . strtolower($s['title']) . '.</p>',
                'icon' => $s['icon'],
                'is_active' => true
            ]);
        }
    }
}
