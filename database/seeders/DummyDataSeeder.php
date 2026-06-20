<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subject;
use App\Models\Qualification;
use App\Models\Location;
use App\Models\User;
use App\Models\CandidateProfile;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\ContactLead;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\ClientLogo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Categories
        $categories = ['PGT', 'TGT', 'PRT', 'Pre-Primary', 'Principal', 'Vice Principal', 'Coordinator', 'Admin Staff', 'Librarian', 'Physical Education'];
        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat], ['is_active' => true]);
        }

        // 2. Subjects
        $subjects = ['Mathematics', 'Science', 'English', 'Hindi', 'Social Studies', 'Computer Science', 'Physics', 'Chemistry', 'Biology', 'Accountancy', 'Economics', 'Business Studies'];
        foreach ($subjects as $sub) {
            Subject::firstOrCreate(['name' => $sub], ['is_active' => true]);
        }

        // 3. Qualifications
        $qualifications = ['B.Ed', 'M.Ed', 'D.El.Ed', 'B.A.', 'M.A.', 'B.Sc.', 'M.Sc.', 'B.Com.', 'M.Com.', 'B.Tech', 'M.Tech', 'Ph.D.', 'CTET Qualified'];
        foreach ($qualifications as $qual) {
            Qualification::firstOrCreate(['name' => $qual], ['is_active' => true]);
        }

        // 4. Locations
        $locations = ['Delhi NCR', 'Gurgaon', 'Noida', 'Faridabad', 'Ghaziabad', 'Mumbai', 'Pune', 'Bangalore', 'Hyderabad', 'Chennai', 'Jaipur', 'Lucknow'];
        foreach ($locations as $loc) {
            Location::firstOrCreate(['city' => $loc, 'state' => 'State'], ['is_active' => true]);
        }

        $categoryIds = Category::pluck('id')->toArray();
        $subjectIds = Subject::pluck('id')->toArray();
        $qualificationIds = Qualification::pluck('id')->toArray();
        $locationIds = Location::pluck('id')->toArray();

        // 5. Candidates (Users + Profiles)
        $candidates = [];
        for ($i = 1; $i <= 30; $i++) {
            $user = User::firstOrCreate(
                ['email' => "candidate$i@example.com"],
                [
                    'name' => "Candidate $i",
                    'phone' => '98765432' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'password' => Hash::make('password'),
                    'role' => 'candidate',
                ]
            );

            $isFeePaid = rand(0, 1) == 1;

            $profile = CandidateProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'category_id' => $categoryIds[array_rand($categoryIds)],
                    'subject_id' => $subjectIds[array_rand($subjectIds)],
                    'highest_qualification_id' => $qualificationIds[array_rand($qualificationIds)],
                    'preferred_location_id' => $locationIds[array_rand($locationIds)],
                    'experience_years' => rand(0, 15),
                    'current_salary' => rand(20000, 80000),
                    'expected_salary' => rand(30000, 100000),
                    'is_profile_complete' => true,
                    'is_agreement_signed' => true,
                    'is_fee_paid' => $isFeePaid,
                    'payment_id' => $isFeePaid ? 'pay_' . Str::random(10) : null,
                    'registration_completed_at' => Carbon::now()->subDays(rand(1, 60)),
                ]
            );

            $candidates[] = $user;
        }

        // 6. Job Posts
        $jobs = [];
        for ($i = 1; $i <= 20; $i++) {
            $catId = $categoryIds[array_rand($categoryIds)];
            $subId = $subjectIds[array_rand($subjectIds)];
            $locId = $locationIds[array_rand($locationIds)];
            $qualId = $qualificationIds[array_rand($qualificationIds)];
            
            $catName = Category::find($catId)->name;
            $subName = Subject::find($subId)->name;
            
            $status = ['pending', 'approved', 'rejected'][rand(0, 2)];

            $job = JobPost::create([
                'title' => "Required $catName Teacher for $subName",
                'school_name' => "International School $i",
                'contact_person' => "Mr. Principal $i",
                'email' => "school$i@example.com",
                'phone' => '88997766' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'category_id' => $catId,
                'subject_id' => $subId,
                'qualification_id' => $qualId,
                'location_id' => $locId,
                'salary_range' => (rand(20, 40) * 1000) . " - " . (rand(40, 80) * 1000),
                'description' => "We are looking for an experienced and passionate $catName teacher for $subName. The ideal candidate should have excellent communication skills and a deep understanding of the subject matter.",
                'status' => $status,
                'user_id' => User::where('role', 'admin')->first()->id ?? 1,
            ]);
            
            $jobs[] = $job;
        }

        // 7. Job Applications
        foreach ($candidates as $candidate) {
            // Apply to 1-3 random jobs
            $numApps = rand(1, 3);
            $appliedJobs = array_rand($jobs, $numApps);
            if (!is_array($appliedJobs)) $appliedJobs = [$appliedJobs];

            foreach ($appliedJobs as $jobIndex) {
                // Ignore duplicate applications if any
                try {
                    JobApplication::create([
                        'job_post_id' => $jobs[$jobIndex]->id,
                        'candidate_id' => $candidate->id,
                        'status' => ['applied', 'shortlisted', 'rejected', 'hired'][rand(0, 3)],
                        'match_score' => rand(50, 100),
                        'cover_letter' => rand(0, 1) ? 'I am very interested in this position and believe I am a strong fit.' : null,
                    ]);
                } catch (\Exception $e) {
                    // Ignore duplicate unique constraints
                }
            }
        }

        // 8. Contact Leads
        for ($i = 1; $i <= 15; $i++) {
            ContactLead::create([
                'name' => "Lead User $i",
                'email' => "lead$i@example.com",
                'phone' => '99887766' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'message' => "Hello, I would like to know more about your services. Please contact me at your earliest convenience.",
                'status' => ['new', 'contacted', 'closed'][rand(0, 2)],
            ]);
        }

        // 9. Services
        $services = [
            ['title' => 'School Recruitment', 'icon' => 'fas fa-school', 'description' => 'We provide top-tier teaching and non-teaching staff for schools across the country.'],
            ['title' => 'Candidate Placement', 'icon' => 'fas fa-user-graduate', 'description' => 'We help educators find their dream jobs in reputed educational institutions.'],
            ['title' => 'Leadership Hiring', 'icon' => 'fas fa-user-tie', 'description' => 'Specialized recruitment for Principals, Vice Principals, and Directors.'],
            ['title' => 'Background Verification', 'icon' => 'fas fa-check-double', 'description' => 'Comprehensive background checks to ensure candidate authenticity.'],
        ];
        foreach ($services as $service) {
            Service::firstOrCreate(['title' => $service['title']], array_merge($service, ['is_active' => true]));
        }

        // 10. Testimonials
        $testimonials = [
            ['name' => 'Dr. Sharma', 'role' => 'Principal, DPS', 'rating' => 5, 'message' => 'Vedanta Agency provided us with exceptional teachers within a week. Highly recommended!'],
            ['name' => 'Priya Gupta', 'role' => 'PGT Math Teacher', 'rating' => 5, 'message' => 'Thanks to Vedanta, I found a great job at a reputed international school with a 40% salary hike.'],
            ['name' => 'Rahul Verma', 'role' => 'Director, Heritage School', 'rating' => 4, 'message' => 'Very professional service. Their screening process is thorough and saves us a lot of time.'],
            ['name' => 'Anita Desai', 'role' => 'Coordinator', 'rating' => 5, 'message' => 'The best placement agency for educators in Delhi NCR.'],
        ];
        foreach ($testimonials as $testimonial) {
            Testimonial::firstOrCreate(['name' => $testimonial['name']], array_merge($testimonial, ['is_active' => true]));
        }

        // 11. Client Logos (Just names for now since we don't have images)
        $clients = ['Delhi Public School', 'Amity International', 'The Heritage School', 'Ryan International', 'Presidium', 'K.R. Mangalam', 'Bal Bharati', 'St. Xavier\'s'];
        foreach ($clients as $client) {
            ClientLogo::firstOrCreate(['name' => $client], ['logo_path' => null, 'is_active' => true]);
        }
    }
}
