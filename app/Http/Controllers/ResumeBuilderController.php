<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResumeBuilderController extends Controller
{
    public const TEMPLATES = [
        [
            'id' => 'fresher_starter',
            'name' => 'Freshers Clean Starter',
            'category' => 'Freshers',
            'layout' => 'classic',
            'order' => 'education_first',
            'primary' => '#0284c7',
            'secondary' => '#e0f2fe',
            'text_on_accent' => '#ffffff',
            'font' => 'sans',
            'tagline' => 'Education-first ATS layout built for fresh graduates & first-time applicants.',
        ],
        [
            'id' => 'fresher_campus',
            'name' => 'Campus Modern',
            'category' => 'Freshers',
            'layout' => 'banner',
            'order' => 'education_first',
            'primary' => '#2563eb',
            'secondary' => '#1e3a8a',
            'text_on_accent' => '#ffffff',
            'font' => 'sans',
            'tagline' => 'Bold blue header highlighting academics, skills, and internships/projects.',
        ],
        [
            'id' => 'corporate_classic',
            'name' => 'Executive Editorial',
            'category' => 'Corporate',
            'layout' => 'classic',
            'order' => 'experience_first',
            'primary' => '#111827',
            'secondary' => '#f3f4f6',
            'text_on_accent' => '#ffffff',
            'font' => 'serif',
            'tagline' => 'Timeless serif classic suited for school leaders, administrators, and managers.',
        ],
        [
            'id' => 'corporate_executive',
            'name' => 'Corporate Navy Gold',
            'category' => 'Corporate',
            'layout' => 'banner',
            'order' => 'experience_first',
            'primary' => '#0f172a',
            'secondary' => '#ca8a04',
            'text_on_accent' => '#ffffff',
            'font' => 'sans',
            'tagline' => 'Prestigious navy banner with gold accents for senior corporate & leadership roles.',
        ],
        [
            'id' => 'corporate_sidebar',
            'name' => 'Corporate Sidebar Split',
            'category' => 'Corporate',
            'layout' => 'sidebar',
            'order' => 'experience_first',
            'primary' => '#1e293b',
            'secondary' => '#3b82f6',
            'text_on_accent' => '#f8fafc',
            'font' => 'sans',
            'tagline' => 'Clean two-column split keeping contact & skills scannable beside career timeline.',
        ],
        [
            'id' => 'tech_developer',
            'name' => 'Tech Minimal Mono',
            'category' => 'Tech',
            'layout' => 'banner',
            'order' => 'experience_first',
            'primary' => '#064e3b',
            'secondary' => '#10b981',
            'text_on_accent' => '#ffffff',
            'font' => 'mono_meta',
            'tagline' => 'Sharp developer & IT educator resume with clean stack chips and crisp hierarchy.',
        ],
        [
            'id' => 'tech_modern_split',
            'name' => 'Tech Modern Sidebar',
            'category' => 'Tech',
            'layout' => 'sidebar',
            'order' => 'experience_first',
            'primary' => '#0f172a',
            'secondary' => '#06b6d4',
            'text_on_accent' => '#ecfeff',
            'font' => 'sans',
            'tagline' => 'High-contrast slate sidebar with cyan accents ideal for software, CS & STEM roles.',
        ],
    ];

    public function index(Request $request)
    {
        $prefillData = null;
        if (auth()->check() && auth()->user()->profile) {
            $user = auth()->user();
            $profile = $user->profile;

            $photoBase64 = null;
            if ($profile->profile_photo_path && Storage::disk('public')->exists($profile->profile_photo_path)) {
                $ext = strtolower(pathinfo($profile->profile_photo_path, PATHINFO_EXTENSION) ?: 'jpeg');
                $rawBytes = Storage::disk('public')->get($profile->profile_photo_path);
                $photoBase64 = 'data:image/' . ($ext === 'jpg' ? 'jpeg' : $ext) . ';base64,' . base64_encode($rawBytes);
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
            if (! empty($profile->skills)) {
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
                    'photo' => $photoBase64,
                ],
                'summary' => $profile->bio ?? 'Dedicated and passionate educator committed to fostering student success, developing engaging lesson plans, and creating an interactive learning environment.',
                'experience' => [
                    [
                        'title' => $subjectName ? $subjectName . ' Teacher' : 'Teacher',
                        'company' => $profile->current_school ?: ($profile->school_name ?? 'Previous Institution'),
                        'startDate' => 'Previous Years',
                        'endDate' => 'Present',
                        'description' => "• Specialized in teaching " . ($subjectName ?: 'assigned curriculum') . " with interactive methodologies.\n• Designed comprehensive lesson plans and evaluated student academic progress.",
                    ],
                ],
                'education' => [
                    [
                        'degree' => $qualificationName ?: 'Bachelor of Education (B.Ed)',
                        'institution' => 'University / College',
                        'year' => 'Graduated',
                        'grade' => '',
                    ],
                ],
                'skills' => array_values(array_unique($skills)),
                'certifications' => ! empty($profile->other_qualifications)
                    ? array_values(array_filter(array_map('trim', explode(',', $profile->other_qualifications))))
                    : [],
            ];
        }

        $templates = self::TEMPLATES;
        $categories = ['All', 'Freshers', 'Corporate', 'Tech'];
        $requestedTemplate = $request->query('template');
        $initialTemplate = $requestedTemplate && self::hasTemplate((string) $requestedTemplate)
            ? (string) $requestedTemplate
            : null;

        return view('resume-builder.index', compact('prefillData', 'templates', 'categories', 'initialTemplate'));
    }

    public function download(Request $request)
    {
        $data = $request->validate([
            'resume_data' => 'required|string',
        ]);

        $resumeData = json_decode($data['resume_data'], true);
        if (! is_array($resumeData)) {
            return back()->with('error', 'Invalid resume payload.');
        }

        $templateMeta = self::findTemplate($resumeData['template'] ?? null);

        $pdf = Pdf::loadView('resume-builder.pdf', [
            'data' => $resumeData,
            'templateMeta' => $templateMeta,
        ])->setPaper('a4', 'portrait');

        $rawName = trim((string) ($resumeData['personal']['name'] ?? 'Candidate'));
        $safeName = trim((string) preg_replace('/[^A-Za-z0-9_\-]/', '_', $rawName), '_') ?: 'Candidate';

        return $pdf->download("Resume_{$safeName}.pdf");
    }

    public static function hasTemplate(string $id): bool
    {
        foreach (self::TEMPLATES as $tpl) {
            if ($tpl['id'] === $id) {
                return true;
            }
        }

        return false;
    }

    public static function findTemplate(?string $id): array
    {
        foreach (self::TEMPLATES as $tpl) {
            if ($tpl['id'] === $id) {
                return $tpl;
            }
        }

        return self::TEMPLATES[2]; // corporate_classic safe default
    }
}
