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

        // Analytics based on current filtered query
        $stats = [
            'total' => (clone $query)->count(),
            'live' => (clone $query)->where('status', 'approved')->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
        ];
        
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

        return view('admin.jobs.show', compact('job', 'suggestedCandidates'));
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
            'title' => 'nullable|string|max:255',
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
            'title' => 'nullable|string|max:255',
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
}
