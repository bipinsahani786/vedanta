<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\City;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\State;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CrmTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();
        if (!$admin) {
            $this->command->error('No admin found.');
            return;
        }

        // 1. Ensure basic Categories and Subjects exist
        $category = Category::firstOrCreate(['name' => 'Library'], ['is_active' => true]);
        $subject = Subject::firstOrCreate(['name' => 'Librarian'], ['is_active' => true]);
        $state = State::first();
        $city = City::first();

        // 2. Create at least 3-5 approved Job Posts
        $sampleJobs = [
            [
                'title' => 'Senior School Librarian',
                'school_name' => 'Delhi Public World School, Patna',
                'salary_range' => '₹35,000 - ₹45,000',
                'category_name' => 'Library',
                'subject_name' => 'Librarian',
            ],
            [
                'title' => 'PGT English Teacher',
                'school_name' => 'St. Xavier High School, Patna',
                'salary_range' => '₹40,000 - ₹55,000',
                'category_name' => 'PGT',
                'subject_name' => 'English',
            ],
            [
                'title' => 'TGT Mathematics Educator',
                'school_name' => 'DAV Public School, Danapur',
                'salary_range' => '₹30,000 - ₹42,000',
                'category_name' => 'TGT',
                'subject_name' => 'Mathematics',
            ],
            [
                'title' => 'Primary Head Teacher',
                'school_name' => 'G.D. Goenka Public School, Patna',
                'salary_range' => '₹28,000 - ₹38,000',
                'category_name' => 'PRT',
                'subject_name' => 'General Teacher',
            ],
        ];

        $createdJobIds = [];
        foreach ($sampleJobs as $sJob) {
            $cat = Category::firstOrCreate(['name' => $sJob['category_name']], ['is_active' => true]);
            $sub = Subject::firstOrCreate(['name' => $sJob['subject_name']], ['is_active' => true]);

            $job = JobPost::firstOrCreate(
                [
                    'title' => $sJob['title'],
                    'school_name' => $sJob['school_name'],
                ],
                [
                    'user_id' => $admin->id,
                    'contact_person' => 'HR Director',
                    'email' => 'hr@' . Str::slug($sJob['school_name']) . '.edu.in',
                    'phone' => '9876543210',
                    'category_id' => $cat->id,
                    'subject_id' => $sub->id,
                    'state_id' => $state?->id,
                    'city_id' => $city?->id,
                    'salary_range' => $sJob['salary_range'],
                    'status' => 'approved',
                    'job_type' => 'Full Time',
                    'description' => '<p>Reputed CBSE school looking for passionate educators with strong communication skills.</p>',
                ]
            );
            $createdJobIds[] = $job->id;
        }

        // 3. Find candidate Chitra (chitranjan@gmail.com) or other candidates
        $chitra = User::where('email', 'chitranjan@gmail.com')->first();
        $candidates = User::where('role', 'candidate')->get();

        if ($chitra && !$candidates->contains('id', $chitra->id)) {
            $candidates->push($chitra);
        }

        foreach ($candidates as $cand) {
            if ($cand->profile && method_exists($cand->profile, 'ensureIdsAssigned')) {
                $cand->profile->ensureIdsAssigned();
            }

            // Assign at least 1 Hired job application and 1 Applied job application
            if (!empty($createdJobIds)) {
                // Application 1: HIRED (Ready for invoice generation)
                $hiredJobId = $createdJobIds[0];
                $app1 = JobApplication::firstOrCreate(
                    [
                        'candidate_id' => $cand->id,
                        'job_post_id' => $hiredJobId,
                    ],
                    [
                        'status' => 'hired',
                        'remarks' => 'Selected and joining finalized. Ready for service charge invoicing.',
                    ]
                );
                // Ensure status is hired
                if ($app1->status !== 'hired') {
                    $app1->update(['status' => 'hired']);
                }

                // Application 2: SHORTLISTED / APPLIED (for secondary testing)
                if (isset($createdJobIds[1])) {
                    JobApplication::firstOrCreate(
                        [
                            'candidate_id' => $cand->id,
                            'job_post_id' => $createdJobIds[1],
                        ],
                        [
                            'status' => 'applied',
                            'remarks' => 'Application received and under review.',
                        ]
                    );
                }
            }
        }

        $this->command->info('CRM Test Data Seeded successfully!');
        if ($chitra) {
            $this->command->info("Candidate Chitra (chitranjan@gmail.com) now has Hired job applications!");
        }
    }
}
