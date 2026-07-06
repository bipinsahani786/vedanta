<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPost;
use App\Models\Category;
use App\Models\Subject;
use App\Models\Location;
use App\Models\User;
use Faker\Factory as Faker;

class JobPostsSeeder extends Seeder
{
    public function run()
    {
        JobPost::query()->delete();
        
        $faker = Faker::create();
        
        $categories = Category::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $locations = Location::all();
        $admin = User::first();

        $jobTypes = ['Full Time', 'Part Time', 'Contract'];
        
        $jobTitles = [
            'Administration & Office' => ['Admin Officer', 'Front Desk Executive', 'School Coordinator'],
            'Hostel Department' => ['Hostel Warden', 'Assistant Warden', 'Care Taker'],
            'IT Department' => ['System Administrator', 'Computer Teacher', 'IT Executive'],
            'Library' => ['Head Librarian', 'Assistant Librarian'],
            'Performing Arts' => ['Music Teacher', 'Dance Instructor', 'Art Teacher'],
            'PGT' => ['Senior PGT Teacher', 'PGT Physics', 'PGT Chemistry', 'PGT Mathematics'],
            'PRT' => ['Primary Teacher', 'PRT English', 'PRT EVS'],
            'TGT' => ['TGT Science', 'TGT English', 'TGT Social Science'],
        ];

        foreach ($categories as $category) {
            $titles = $jobTitles[$category->name] ?? ['Experienced Teacher', 'Subject Expert', 'Educator'];
            
            // Generate 3 to 6 jobs per category
            $numJobs = rand(3, 6);
            for ($i = 0; $i < $numJobs; $i++) {
                JobPost::create([
                    'user_id' => $admin->id ?? 1,
                    'school_name' => $faker->company . ' ' . $faker->randomElement(['International School', 'Public School', 'Academy', 'Global School']),
                    'contact_person' => $faker->name,
                    'email' => $faker->companyEmail,
                    'phone' => '9' . $faker->numerify('#########'),
                    'title' => $faker->randomElement($titles),
                    'description' => '<p>' . implode('</p><p>', $faker->paragraphs(2)) . '</p><ul><li>Strong communication skills</li><li>Minimum 3 years experience</li><li>Passionate about teaching</li></ul>',
                    'category_id' => $category->id,
                    'subject_id' => $subjects->isNotEmpty() ? $subjects->random()->id : null,
                    'location_id' => $locations->isNotEmpty() ? $locations->random()->id : null,
                    'salary_range' => '₹' . rand(20, 45) . ',000 - ₹' . rand(50, 85) . ',000 per month',
                    'status' => 'approved',
                    'job_type' => $faker->randomElement($jobTypes),
                ]);
            }
        }
    }
}
