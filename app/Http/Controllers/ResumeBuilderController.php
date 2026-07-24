<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumeBuilderController extends Controller
{
    public function index()
    {
        $prefillData = null;
        if (auth()->check() && auth()->user()->profile) {
            $user = auth()->user();
            $profile = $user->profile;

            $photoBase64 = null;
            if ($profile->profile_photo_path) {
                $path = storage_path('app/public/' . $profile->profile_photo_path);
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $photoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            }

            $subjectName = $profile->subject?->name ?? (is_string($profile->subject) ? $profile->subject : '');
            $qualificationName = $profile->highestQualification?->name ?? (is_string($profile->highest_qualification) ? $profile->highest_qualification : '');
            $specializationName = $profile->specialization?->name ?? '';
            $categoryName = $profile->category?->name ?? 'Teacher';

            $skills = [];
            if ($subjectName) {
                $skills[] = $subjectName;
            }
            if ($specializationName) {
                $skills[] = $specializationName;
            }
            if ($qualificationName) {
                $skills[] = $qualificationName;
            }
            if (!empty($profile->skills)) {
                if (is_array($profile->skills)) {
                    $skills = array_merge($skills, $profile->skills);
                } elseif (is_string($profile->skills)) {
                    $skills = array_merge($skills, array_map('trim', explode(',', $profile->skills)));
                }
            }
            if (empty($skills)) {
                $skills = ['Lesson Planning', 'Classroom Management', 'Curriculum Development', 'Student Assessment'];
            }

            $prefillData = [
                'personal' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? $profile->phone ?? '',
                    'location' => $profile->address ?? implode(', ', array_filter([$profile->preferredCity?->name, $profile->preferredState?->name])),
                    'title' => $subjectName ? $subjectName . ' Teacher' : ($categoryName ?: 'Educator'),
                    'photo' => $photoBase64
                ],
                'summary' => $profile->bio ?? 'Dedicated and passionate educator committed to fostering student success, developing engaging lesson plans, and creating an interactive learning environment.',
                'experience' => [
                    [
                        'title' => $subjectName ? $subjectName . ' Teacher' : 'Teacher',
                        'company' => $profile->school_name ?? 'Previous Institution',
                        'startDate' => 'Previous Years',
                        'endDate' => 'Present',
                        'description' => '• Specialized in teaching ' . ($subjectName ?: 'assigned curriculum') . ' with interactive methodologies.\n• Designed comprehensive lesson plans and evaluated student academic progress.'
                    ]
                ],
                'education' => [
                    [
                        'degree' => $qualificationName ?: 'Bachelor of Education (B.Ed)',
                        'institution' => 'University / College',
                        'year' => 'Graduated',
                        'grade' => ''
                    ]
                ],
                'skills' => array_values(array_unique($skills))
            ];
        }
        return view('resume-builder.index', compact('prefillData'));
    }

    public function download(Request $request)
    {
        $data = $request->validate([
            'resume_data' => 'required|string',
        ]);

        $resumeData = json_decode($data['resume_data'], true);

        $pdf = Pdf::loadView('resume-builder.pdf', ['data' => $resumeData]);
        
        return $pdf->download('Resume_' . ($resumeData['personal']['name'] ?? 'Candidate') . '.pdf');
    }
}
