<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPost::with(['category', 'subject', 'state', 'city', 'qualification']);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('school_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        // Analytics based on base query (before status filter)
        $baseQuery = clone $query;
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'live' => (clone $baseQuery)->where('status', 'approved')->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
        ];

        // Status Filter
        $status = $request->input('status');
        if ($status) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        } else {
            // Default to live (approved) jobs when no status is provided
            $query->where('status', 'approved');
        }

        // Sorting
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('order', 'desc');
        
        $allowedFields = ['id', 'title', 'school_name', 'status', 'created_at'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $jobs = $query->paginate(15)->withQueryString();

        return view('admin.jobs.index', compact('jobs', 'stats', 'sortField', 'sortDirection'));
    }

    public function show(JobPost $job)
    {
        // Module 16: AI Shortlisting Engine logic (matching candidate profiles)
        $suggestedCandidates = clone $job->getSuggestedCandidates(10); // Use the logic from the JobPost model

        // Because the model logic maps to 'match_percentage' instead of 'match_score' 
        // and returns CandidateProfile objects rather than User objects, 
        // we'll map them so the view doesn't break.
        $suggestedCandidates = $suggestedCandidates->map(function ($candidate) {
            return (object) [
                'id' => $candidate->user->id,
                'name' => $candidate->user->name,
                'match_score' => $candidate->match_percentage,
                'matched_criteria' => $candidate->matched_criteria,
                'profile' => $candidate
            ];
        });

        $categories = \App\Models\Category::where('is_active', true)->get();
        $subjects = \App\Models\Subject::where('is_active', true)->get();
        $qualifications = \App\Models\Qualification::where('is_active', true)->get();
        $states = \App\Models\State::where('is_active', true)->get();
        $cities = \App\Models\City::where('is_active', true)->get();

        return view('admin.jobs.show', compact('job', 'suggestedCandidates', 'categories', 'subjects', 'qualifications', 'states', 'cities'));
    }

    public function approve(Request $request, JobPost $job)
    {
        $request->validate([
            'create_account' => 'boolean'
        ]);

        $job->update([
            'status' => 'approved'
        ]);

        // Smart Job Matching: Notify Candidates with similar subject/location/category
        $suggestedCandidates = $job->getSuggestedCandidates(50); // Get top 50 matching candidates

        foreach ($suggestedCandidates as $candidateProfile) {
            $candidate = $candidateProfile->user;
            
            if ($candidate) {
                // Insert Database Notification
                \Illuminate\Support\Facades\DB::table('notifications')->insert([
                    'id' => Str::uuid(),
                    'type' => 'App\Notifications\JobMatched',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $candidate->id,
                    'data' => json_encode([
                        'title' => 'New Matching Job: ' . $job->title,
                        'message' => 'A new job at ' . $job->school_name . ' matches your profile (' . $candidateProfile->match_percentage . '% match).',
                        'job_id' => $job->id
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Send Email Notification
                \Illuminate\Support\Facades\Mail::to($candidate->email)->send(new \App\Mail\CandidateJobMatchNotification($job, $candidateProfile->match_percentage));
            }
        }

        // If the admin chose to generate an account and one doesn't exist
        if ($request->has('create_account') && is_null($job->user_id)) {
            // Check if user already exists
            $existingUser = User::where('email', $job->email)->orWhere('phone', $job->phone)->first();
            
            if (!$existingUser) {
                $password = Str::random(8); // Auto-generate password
                
                $user = User::create([
                    'name' => $job->contact_person,
                    'email' => $job->email,
                    'phone' => $job->phone,
                    'role' => 'employer',
                    'password' => Hash::make($password),
                ]);

                // Update job with user ID
                $job->update(['user_id' => $user->id]);

                // Fire registered event to send verification email and send password email
                event(new \Illuminate\Auth\Events\Registered($user));
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\SchoolAccountCreatedMail($user, $password));
                
                return redirect()->route('admin.jobs.index')->with('success', "Job approved and employer account created. Temporary password is: $password");
            } else {
                // User exists, just link it
                $job->update(['user_id' => $existingUser->id]);
                return redirect()->route('admin.jobs.index')->with('success', 'Job approved and linked to existing user.');
            }
        }

        return redirect()->route('admin.jobs.index')->with('success', 'Job has been approved successfully.');
    }

    public function reject(Request $request, JobPost $job)
    {
        $job->update([
            'status' => 'rejected'
        ]);

        return redirect()->route('admin.jobs.index')->with('success', 'Job has been rejected.');
    }

    public function create()
    {
        $categories = \App\Models\Category::where('is_active', true)->get();
        $subjects = \App\Models\Subject::where('is_active', true)->get();
        $qualifications = \App\Models\Qualification::where('is_active', true)->get();
        $states = \App\Models\State::where('is_active', true)->get();

        return view('admin.jobs.create', compact('categories', 'subjects', 'qualifications', 'states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'school_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'qualification_id' => 'required|exists:qualifications,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'salary_range' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $validated['user_id'] = auth()->id();

        JobPost::create($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Job posted successfully.');
    }

    public function edit(JobPost $job)
    {
        $categories = \App\Models\Category::where('is_active', true)->get();
        $subjects = \App\Models\Subject::where('is_active', true)->get();
        $qualifications = \App\Models\Qualification::where('is_active', true)->get();
        $states = \App\Models\State::where('is_active', true)->get();
        $cities = \App\Models\City::where('state_id', $job->state_id)->where('is_active', true)->get();

        return view('admin.jobs.edit', compact('job', 'categories', 'subjects', 'qualifications', 'states', 'cities'));
    }

    public function update(Request $request, JobPost $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'school_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'qualification_id' => 'required|exists:qualifications,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'salary_range' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $job->update($validated);

        return redirect()->route('admin.jobs.show', $job->id)->with('success', 'Job post updated successfully.');
    }

    public function destroy(JobPost $job)
    {
        $job->delete();
        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }

    public function searchCandidates(Request $request, JobPost $job)
    {
        $query = User::where('role', 'candidate')->with(['profile.category', 'profile.subject', 'profile.preferredCity']);

        if ($categoryId = $request->input('category_id')) {
            $query->whereHas('profile', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        if ($subjectId = $request->input('subject_id')) {
            $query->whereHas('profile', function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            });
        }
        if ($qualificationId = $request->input('qualification_id')) {
            $query->whereHas('profile', function($q) use ($qualificationId) {
                $q->where('highest_qualification_id', $qualificationId);
            });
        }
        if ($stateId = $request->input('state_id')) {
            $query->whereHas('profile', function($q) use ($stateId) {
                $q->where('preferred_state_id', $stateId);
            });
        }
        if ($cityId = $request->input('city_id')) {
            $query->whereHas('profile', function($q) use ($cityId) {
                $q->where('preferred_city_id', $cityId);
            });
        }

        $candidates = $query->take(100)->get(); // Limit to 100 for safety

        $formatted = $candidates->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'category' => $user->profile?->category?->name ?? 'N/A',
                'subject' => $user->profile?->subject?->name ?? 'N/A',
                'city' => $user->profile?->preferredCity?->name ?? 'N/A',
            ];
        });

        return response()->json($formatted);
    }

    public function sendMessage(Request $request, JobPost $job)
    {
        $request->validate([
            'audience' => 'required|in:all,matched,manual',
            'candidate_ids' => 'required_if:audience,manual|array',
            'candidate_ids.*' => 'exists:users,id'
        ]);

        $audience = $request->input('audience');
        $candidates = collect();

        if ($audience === 'all') {
            $candidates = User::where('role', 'candidate')->get();
        } elseif ($audience === 'matched') {
            $suggested = $job->getSuggestedCandidates(50);
            $candidates = $suggested->map(function ($profile) {
                return $profile->user;
            })->filter();
        } elseif ($audience === 'manual') {
            $candidates = User::whereIn('id', $request->input('candidate_ids'))->get();
        }

        $sentCount = 0;

        foreach ($candidates as $candidate) {
            if (!$candidate) continue;

            $matchScore = $audience === 'matched' ? 'AI Matched' : 'Manually Selected';

            // Insert Database Notification
            \Illuminate\Support\Facades\DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => 'App\Notifications\JobMatched',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $candidate->id,
                'data' => json_encode([
                    'title' => 'New Job Alert: ' . $job->title,
                    'message' => 'A new job at ' . $job->school_name . ' has been shared with you.',
                    'job_id' => $job->id
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Send Email Notification (Queued)
            try {
                \Illuminate\Support\Facades\Mail::to($candidate->email)->queue(new \App\Mail\CandidateJobMatchNotification($job, $matchScore));
                $sentCount++;
            } catch (\Exception $e) {
                \Log::error('Failed to send job match email to: ' . $candidate->email . '. Error: ' . $e->getMessage());
            }
        }

        return back()->with('success', "Notification sent successfully to {$sentCount} candidate(s).");
    }
}
