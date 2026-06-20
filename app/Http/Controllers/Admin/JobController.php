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
        if ($status = $request->input('status')) {
            $query->where('status', $status);
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
        return view('admin.jobs.show', compact('job'));
    }

    public function approve(Request $request, JobPost $job)
    {
        $request->validate([
            'create_account' => 'boolean'
        ]);

        $job->update([
            'status' => 'approved'
        ]);

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
            'title' => 'nullable|string|max:255',
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

    public function destroy(JobPost $job)
    {
        $job->delete();
        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }
}
