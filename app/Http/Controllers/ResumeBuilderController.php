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
            $prefillData = [
                'personal' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'address' => $profile->address,
                    'title' => 'Teacher',
                    'summary' => 'Experienced educator.'
                ],
                'experience' => [
                    ['title' => 'Teacher', 'company' => 'Previous School', 'duration' => $profile->years_of_experience . ' Years', 'description' => 'Taught ' . $profile->subject . '.']
                ],
                'education' => [
                    ['degree' => $profile->highest_qualification, 'school' => 'University/College', 'year' => 'Graduated']
                ],
                'skills' => explode(',', $profile->subject ?? '')
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
