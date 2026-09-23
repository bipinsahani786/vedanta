<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\JobPost;
use App\Models\State;
use App\Models\City;
use App\Models\Qualification;
use App\Models\Subject;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function showPostJobForm()
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.jobs.create');
        }

        return redirect()->route('employer.jobs.create');
    }

    public function show(JobPost $job)
    {
        if ($job->status !== 'approved') {
            abort(404);
        }

        $user = auth()->user();
        $isUnlocked = $job->canUserViewProtectedDetails($user);
        $candidateStatus = !$user ? 'guest' : (!$isUnlocked ? 'registration_pending' : 'unlocked');

        // Fetch similar jobs (same category or latest approved jobs)
        $similarJobs = JobPost::with(['category', 'subject', 'state', 'city'])
            ->where('status', 'approved')
            ->where('id', '!=', $job->id)
            ->when($job->category_id, function($q) use ($job) {
                $q->where('category_id', $job->category_id);
            })
            ->latest()
            ->take(4)
            ->get();

        if ($similarJobs->count() < 2) {
            $similarJobs = JobPost::with(['category', 'subject', 'state', 'city'])
                ->where('status', 'approved')
                ->where('id', '!=', $job->id)
                ->latest()
                ->take(4)
                ->get();
        }
        
        return view('jobs.show', compact('job', 'isUnlocked', 'candidateStatus', 'similarJobs'));
    }

    public function checkSchoolAccess(JobPost $job)
    {
        $user = auth()->user();
        $isUnlocked = $job->canUserViewProtectedDetails($user);
        $candidateStatus = !$user ? 'guest' : (!$isUnlocked ? 'registration_pending' : 'unlocked');

        return response()->json([
            'authenticated' => (bool) $user,
            'is_unlocked' => $isUnlocked,
            'can_view' => $isUnlocked,
            'status' => $candidateStatus,
            'wizard_url' => route('candidate.wizard'),
            'school' => $isUnlocked ? [
                'name' => $job->school_name,
                'city' => $job->city?->name,
                'state' => $job->state?->name,
                'image' => $job->getMaskedSchoolImage($user),
            ] : null
        ]);
    }

    public function storeJobQuery(Request $request)
    {
        $request->validate([
            'school_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:15',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'specialization_id' => 'nullable|exists:specializations,id',
            'qualification_id' => 'required|exists:qualifications,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'salary_range' => 'nullable|string|max:255',
        ]);

        $job = JobPost::create([
            'user_id' => auth()->check() && auth()->user()->role === 'employer' ? auth()->id() : null,
            'school_name' => $request->school_name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'subject_id' => $request->subject_id,
            'specialization_id' => $request->specialization_id,
            'qualification_id' => $request->qualification_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'salary_range' => $request->salary_range,
            'status' => 'pending',
        ]);

        // Send Email to Employer
        if ($job->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($job->email)->send(new \App\Mail\JobSubmittedForApprovalMail($job));
            } catch (\Exception $e) {
                \Log::error('Failed to send job submitted email to employer: ' . $e->getMessage());
            }
        }

        // Notify Admin
        $adminUser = \App\Models\User::where('role', 'admin')->first();
        if ($adminUser) {
            \Illuminate\Support\Facades\DB::table('notifications')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\NewJobPosted',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $adminUser->id,
                'data' => json_encode([
                    'title' => 'New Job Posted',
                    'message' => $request->school_name . ' has posted a new job vacancy: ' . $request->title . '.',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Your job requirement has been submitted successfully. Our team will review and approve it shortly.');
    }
}
