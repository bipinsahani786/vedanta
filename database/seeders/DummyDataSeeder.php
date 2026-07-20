<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subject;
use App\Models\Qualification;
use App\Models\State;
use App\Models\City;
use App\Models\User;
use App\Models\CandidateProfile;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\ContactLead;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\ClientLogo;
use App\Models\EmployerProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Categories
        $categories = [
            'PRT',
            'TGT',
            'PGT',
            'School Leadership & Administration',
            'Administration & Office',
            'Student Support Services',
            'IT Department',
            'Library',
            'Performing Arts',
            'Hostel Department'
        ];
        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat], ['is_active' => true]);
        }

        // 2. Subjects & Specializations
        $prtSubjects = ['English', 'Hindi', 'Mathematics', 'EVS', 'Science', 'Social Studies', 'Computer', 'General Teacher', 'Art & Craft'];
        $tgtSubjects = ['English', 'Hindi', 'Mathematics', 'Science', 'Social Science', 'Sanskrit', 'Urdu', 'French', 'German', 'Computer Science', 'Information Technology (IT)'];
        $pgtSubjects = ['English', 'Hindi', 'Mathematics', 'Physics', 'Chemistry', 'Biology', 'Commerce', 'Arts / Humanities', 'Computer Science', 'Informatics Practices (IP)', 'Physical Education', 'Fine Arts'];
        $leadershipSubjects = ['Principal', 'Vice Principal', 'Headmaster/Headmistress', 'Academic Coordinator', 'Examination In-charge', 'Dean Academics', 'Dean Student Affairs', 'Head of Boarding (HOB)', 'Hostel Warden (Male)', 'Hostel Warden (Female)'];
        $adminSubjects = ['School Administrator', 'HR Manager', 'HR Executive', 'Office Executive', 'Front Office Executive', 'Receptionist', 'Admission Counsellor', 'Administrative Officer', 'Office Assistant', 'Data Entry Operator', 'Accountant', 'Cashier'];
        $supportSubjects = ['School Counsellor', 'Special Educator', 'Wellness Counsellor', 'Career Counsellor', 'Speech Therapist', 'Occupational Therapist', 'Psychologist', 'Nurse (GNM/B.Sc Nursing)'];
        $itSubjects = ['IT Executive', 'IT Administrator', 'Network Engineer', 'ERP Executive', 'Computer Lab Assistant', 'Digital Marketing Executive', 'Graphic Designer', 'Website Administrator'];
        $librarySubjects = ['Librarian', 'Assistant Librarian', 'Library Assistant'];
        $performingArtsSubjects = ['Music Teacher (Vocal)', 'Music Teacher (Instrumental)', 'Dance Teacher', 'Art Teacher', 'Craft Teacher', 'Theatre/Drama Teacher'];
        $hostelSubjects = ['Head of Boarding (HOB)', 'Hostel Superintendent', 'Hostel Warden (Male)', 'Hostel Warden (Female)', 'Residential Tutor', 'Caretaker'];
        
        $allSubjects = array_unique(array_merge($prtSubjects, $tgtSubjects, $pgtSubjects, $leadershipSubjects, $adminSubjects, $supportSubjects, $itSubjects, $librarySubjects, $performingArtsSubjects, $hostelSubjects));
        
        foreach ($allSubjects as $sub) {
            Subject::firstOrCreate(['name' => $sub], ['is_active' => true]);
        }

        // Map PRT subjects
        $prtCategory = Category::where('name', 'PRT')->first();
        if ($prtCategory) {
            $prtCategory->subjects()->sync(Subject::whereIn('name', $prtSubjects)->pluck('id'));
        }

        // Map TGT subjects
        $tgtCategory = Category::where('name', 'TGT')->first();
        if ($tgtCategory) {
            $tgtCategory->subjects()->sync(Subject::whereIn('name', $tgtSubjects)->pluck('id'));
        }

        // Map PGT subjects
        $pgtCategory = Category::where('name', 'PGT')->first();
        if ($pgtCategory) {
            $pgtCategory->subjects()->sync(Subject::whereIn('name', $pgtSubjects)->pluck('id'));
        }

        // Map School Leadership subjects
        $leadershipCategory = Category::where('name', 'School Leadership & Administration')->first();
        if ($leadershipCategory) {
            $leadershipCategory->subjects()->sync(Subject::whereIn('name', $leadershipSubjects)->pluck('id'));
        }

        // Map Administration & Office subjects
        $adminCategory = Category::where('name', 'Administration & Office')->first();
        if ($adminCategory) {
            $adminCategory->subjects()->sync(Subject::whereIn('name', $adminSubjects)->pluck('id'));
        }

        // Map Student Support Services subjects
        $supportCategory = Category::where('name', 'Student Support Services')->first();
        if ($supportCategory) {
            $supportCategory->subjects()->sync(Subject::whereIn('name', $supportSubjects)->pluck('id'));
        }

        // Map IT Department subjects
        $itCategory = Category::where('name', 'IT Department')->first();
        if ($itCategory) {
            $itCategory->subjects()->sync(Subject::whereIn('name', $itSubjects)->pluck('id'));
        }

        // Map Library subjects
        $libraryCategory = Category::where('name', 'Library')->first();
        if ($libraryCategory) {
            $libraryCategory->subjects()->sync(Subject::whereIn('name', $librarySubjects)->pluck('id'));
        }

        // Map Performing Arts subjects
        $performingArtsCategory = Category::where('name', 'Performing Arts')->first();
        if ($performingArtsCategory) {
            $performingArtsCategory->subjects()->sync(Subject::whereIn('name', $performingArtsSubjects)->pluck('id'));
        }

        // Map Hostel Department subjects
        $hostelCategory = Category::where('name', 'Hostel Department')->first();
        if ($hostelCategory) {
            $hostelCategory->subjects()->sync(Subject::whereIn('name', $hostelSubjects)->pluck('id'));
        }

        // Map other subjects randomly to other active categories so there's dummy data
        $otherCategoryIds = Category::whereNotIn('name', ['PRT', 'TGT', 'PGT', 'School Leadership & Administration', 'Administration & Office', 'Student Support Services', 'IT Department', 'Library', 'Performing Arts', 'Hostel Department'])->pluck('id');
        $allSubjectIds = Subject::pluck('id');
        foreach ($otherCategoryIds as $catId) {
            $cat = Category::find($catId);
            $cat->subjects()->syncWithoutDetaching($allSubjectIds->random(min(5, $allSubjectIds->count())));
        }
        // Specializations for TGT
        $scienceSubject = Subject::where('name', 'Science')->first();
        if ($scienceSubject) {
            $scienceSpecializations = ['Physics', 'Chemistry', 'Biology'];
            foreach ($scienceSpecializations as $spec) {
                \App\Models\Specialization::firstOrCreate([
                    'subject_id' => $scienceSubject->id,
                    'name' => $spec
                ], ['is_active' => true]);
            }
        }

        $socialScienceSubject = Subject::where('name', 'Social Science')->first();
        if ($socialScienceSubject) {
            $socialScienceSpecializations = ['History', 'Geography', 'Political Science', 'Economics'];
            foreach ($socialScienceSpecializations as $spec) {
                \App\Models\Specialization::firstOrCreate([
                    'subject_id' => $socialScienceSubject->id,
                    'name' => $spec
                ], ['is_active' => true]);
            }
        }

        // Specializations for PGT
        $biologySubject = Subject::where('name', 'Biology')->first();
        if ($biologySubject) {
            $biologySpecializations = ['Botany', 'Zoology'];
            foreach ($biologySpecializations as $spec) {
                \App\Models\Specialization::firstOrCreate([
                    'subject_id' => $biologySubject->id,
                    'name' => $spec
                ], ['is_active' => true]);
            }
        }

        $commerceSubject = Subject::where('name', 'Commerce')->first();
        if ($commerceSubject) {
            $commerceSpecializations = ['Accountancy', 'Business Studies', 'Economics', 'English'];
            foreach ($commerceSpecializations as $spec) {
                \App\Models\Specialization::firstOrCreate([
                    'subject_id' => $commerceSubject->id,
                    'name' => $spec
                ], ['is_active' => true]);
            }
        }

        $artsHumanitiesSubject = Subject::where('name', 'Arts / Humanities')->first();
        if ($artsHumanitiesSubject) {
            $artsSpecializations = ['History', 'Geography', 'Political Science', 'Psychology', 'Economics', 'English'];
            foreach ($artsSpecializations as $spec) {
                \App\Models\Specialization::firstOrCreate([
                    'subject_id' => $artsHumanitiesSubject->id,
                    'name' => $spec
                ], ['is_active' => true]);
            }
        }

        // 3. Qualifications
        $qualifications = ['B.Ed', 'M.Ed', 'D.El.Ed', 'B.A.', 'M.A.', 'B.Sc.', 'M.Sc.', 'B.Com.', 'M.Com.', 'B.Tech', 'M.Tech', 'Ph.D.', 'CTET Qualified'];
        foreach ($qualifications as $qual) {
            Qualification::firstOrCreate(['name' => $qual], ['is_active' => true]);
        }

        $categoryIds = Category::pluck('id')->toArray();
        $subjectIds = Subject::pluck('id')->toArray();
        $qualificationIds = Qualification::pluck('id')->toArray();
        $stateIds = State::pluck('id')->toArray();
        $cityIds = City::pluck('id')->toArray();

//         // 5. Candidates (Users + Profiles)
//         $candidates = [];
//         for ($i = 1; $i <= 30; $i++) {
//             $user = User::firstOrCreate(
//                 ['email' => "candidate$i@example.com"],
//                 [
//                     'name' => "Candidate $i",
//                     'phone' => '98765432' . str_pad($i, 2, '0', STR_PAD_LEFT),
//                     'password' => Hash::make('password'),
//                     'role' => 'candidate',
//                 ]
//             );
// 
//             $isFeePaid = rand(0, 1) == 1;
// 
//             $profile = CandidateProfile::firstOrCreate(
//                 ['user_id' => $user->id],
//                 [
//                     'category_id' => $categoryIds[array_rand($categoryIds)],
//                     'subject_id' => $subjectIds[array_rand($subjectIds)],
//                     'highest_qualification_id' => $qualificationIds[array_rand($qualificationIds)],
//                     'preferred_state_id' => !empty($stateIds) ? $stateIds[array_rand($stateIds)] : null,
//                     'preferred_city_id' => !empty($cityIds) ? $cityIds[array_rand($cityIds)] : null,
//                     'experience_years' => rand(0, 15),
//                     'current_salary' => rand(20000, 80000),
//                     'expected_salary' => rand(30000, 100000),
//                     'is_profile_complete' => true,
//                     'is_agreement_signed' => true,
//                     'is_fee_paid' => $isFeePaid,
//                     'payment_id' => $isFeePaid ? 'pay_' . Str::random(10) : null,
//                     'registration_completed_at' => Carbon::now()->subDays(rand(1, 60)),
//                 ]
//             );
// 
//             $candidates[] = $user;
//         }
// 
//         // 5.5 Employers (Users + Profiles)
//         $employers = [];
//         for ($i = 1; $i <= 5; $i++) {
//             $user = User::firstOrCreate(
//                 ['email' => "employer$i@example.com"],
//                 [
//                     'name' => "Employer $i",
//                     'phone' => '88776655' . str_pad($i, 2, '0', STR_PAD_LEFT),
//                     'password' => Hash::make('password'),
//                     'role' => 'employer',
//                 ]
//             );
// 
//             $profile = EmployerProfile::firstOrCreate(
//                 ['user_id' => $user->id],
//                 [
//                     'school_name' => "International School $i",
//                     'contact_person' => "Mr. Principal $i",
//                     'address' => "Block A, City Center, Sector $i",
//                     'about' => "We are a reputed educational institution focused on holistic development.",
//                 ]
//             );
// 
//             $employers[] = $user;
//         }
// 
//         // 6. Job Posts
//         $jobs = [];
//         for ($i = 1; $i <= 20; $i++) {
//             $catId = $categoryIds[array_rand($categoryIds)];
//             $subId = $subjectIds[array_rand($subjectIds)];
//             $stateId = !empty($stateIds) ? $stateIds[array_rand($stateIds)] : null;
//             $cityId = !empty($cityIds) ? $cityIds[array_rand($cityIds)] : null;
//             $qualId = $qualificationIds[array_rand($qualificationIds)];
//             
//             $catName = Category::find($catId)->name;
//             $subName = Subject::find($subId)->name;
//             
//             $status = ['pending', 'approved', 'rejected'][rand(0, 2)];
//             $employer = $employers[array_rand($employers)];
// 
//             $job = JobPost::create([
//                 'title' => "Required $catName Teacher for $subName",
//                 'school_name' => $employer->employerProfile->school_name,
//                 'contact_person' => $employer->employerProfile->contact_person,
//                 'email' => $employer->email,
//                 'phone' => $employer->phone,
//                 'category_id' => $catId,
//                 'subject_id' => $subId,
//                 'qualification_id' => $qualId,
//                 'state_id' => $stateId,
//                 'city_id' => $cityId,
//                 'salary_range' => (rand(20, 40) * 1000) . " - " . (rand(40, 80) * 1000),
//                 'description' => "We are looking for an experienced and passionate $catName teacher for $subName. The ideal candidate should have excellent communication skills and a deep understanding of the subject matter.",
//                 'status' => $status,
//                 'user_id' => $employer->id,
//             ]);
//             
//             $jobs[] = $job;
//         }
// 
//         // 7. Job Applications
//         foreach ($candidates as $candidate) {
//             // Apply to 1-3 random jobs
//             $numApps = rand(1, 3);
//             $appliedJobs = array_rand($jobs, $numApps);
//             if (!is_array($appliedJobs)) $appliedJobs = [$appliedJobs];
// 
//             foreach ($appliedJobs as $jobIndex) {
//                 // Ignore duplicate applications if any
//                 try {
//                     JobApplication::create([
//                         'job_post_id' => $jobs[$jobIndex]->id,
//                         'candidate_id' => $candidate->id,
//                         'status' => ['applied', 'shortlisted', 'rejected', 'hired'][rand(0, 3)],
//                         'match_score' => rand(50, 100),
//                         'cover_letter' => rand(0, 1) ? 'I am very interested in this position and believe I am a strong fit.' : null,
//                     ]);
//                 } catch (\Exception $e) {
//                     // Ignore duplicate unique constraints
//                 }
//             }
//         }

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
