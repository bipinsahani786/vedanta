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
        $query = JobPost::with(['category', 'subject', 'location', 'qualification']);

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
        if ($status === 'pending') {
            $query->whereIn('status', ['pending', 'rejected']);
        } else {
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
        
        return view('admin.jobs.index', compact('jobs', 'sortField', 'sortDirection'));
    }

    public function show(JobPost $job)
    {
        // Module 16: AI Shortlisting Engine logic (matching candidate profiles)
        $suggestedCandidates = User::where('role', 'candidate')
            ->whereHas('profile', function ($q) use ($job) {
                // We'll consider it a "match" if subject or location matches
                $q->where('subject_id', $job->subject_id)
                  ->orWhere('preferred_location_id', $job->location_id)
                  ->orWhere('highest_qualification_id', $job->qualification_id);
            })
            ->with('profile')
            ->get()
            ->map(function ($candidate) use ($job) {
                // Calculate match score
                $score = 0;
                $profile = $candidate->profile;
                if ($profile) {
                    if ($profile->subject_id === $job->subject_id) $score += 40;
                    if ($profile->preferred_location_id === $job->location_id) $score += 30;
                    if ($profile->highest_qualification_id === $job->qualification_id) $score += 20;
                    // Adding experience match check loosely
                    if ($profile->years_of_experience >= 1) $score += 10;
                }
                $candidate->match_score = $score;
                return $candidate;
            })
            ->filter(fn($c) => $c->match_score >= 30) // Only show reasonable matches
            ->sortByDesc('match_score')
            ->take(10); // Show top 10 matches

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

        // Smart Job Matching: Notify Candidates with similar subject/location
        $job->load(['subject', 'location']);
        if ($job->subject) {
            $matchingCandidates = \App\Models\User::where('role', 'candidate')
                ->whereHas('profile', function($q) use ($job) {
                    $q->where('subject_id', $job->subject_id)
                      ->orWhere('preferred_location_id', $job->location_id);
                })->get();

            foreach ($matchingCandidates as $candidate) {
                \Illuminate\Support\Facades\DB::table('notifications')->insert([
                    'id' => Str::uuid(),
                    'type' => 'App\Notifications\JobMatched',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $candidate->id,
                    'data' => json_encode([
                        'title' => 'New Matching Job: ' . $job->title,
                        'message' => 'A new job at ' . $job->school_name . ' matches your profile.',
                        'job_id' => $job->id
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
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

                // Fire registered event to send verification email (could also send password here)
                event(new Registered($user));

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
        $locations = \App\Models\Location::where('is_active', true)->get();

        return view('admin.jobs.create', compact('categories', 'subjects', 'qualifications', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'school_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'qualification_id' => 'required|exists:qualifications,id',
            'location_id' => 'required|exists:locations,id',
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
        $locations = \App\Models\Location::where('is_active', true)->get();

        return view('admin.jobs.edit', compact('job', 'categories', 'subjects', 'qualifications', 'locations'));
    }

    public function update(Request $request, JobPost $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'school_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'qualification_id' => 'required|exists:qualifications,id',
            'location_id' => 'required|exists:locations,id',
            'salary_range' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
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
