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

            $prefillData = [
                'personal' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'location' => $profile->address,
                    'title' => 'Teacher',
                    'photo' => $photoBase64
                ],
                'summary' => 'Experienced educator.',
                'experience' => [
                    [
                        'title' => 'Teacher',
                        'company' => 'Previous School',
                        'startDate' => 'Previous Years',
                        'endDate' => 'Present',
                        'description' => 'Taught ' . $profile->subject . '.'
                    ]
                ],
                'education' => [
                    [
                        'degree' => $profile->highest_qualification,
                        'institution' => 'University/College',
                        'year' => 'Graduated',
                        'grade' => ''
                    ]
                ],
                'skills' => array_filter(array_map('trim', explode(',', $profile->subject ?? '')))
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
