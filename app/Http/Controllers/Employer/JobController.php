<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JobPost;
use App\Models\Category;
use App\Models\Subject;
use App\Models\Qualification;
use App\Models\Location;

class JobController extends Controller
{
    public function index()
    {
        $jobs = JobPost::where('user_id', auth()->id())->latest()->paginate(10);
        return view('employer.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $qualifications = Qualification::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();
        
        $profile = auth()->user()->employerProfile;

        return view('employer.jobs.create', compact('categories', 'subjects', 'qualifications', 'locations', 'profile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'requirements' => 'required|array|min:1',
            'requirements.*.title' => 'required|string|max:255',
            'requirements.*.description' => 'required|string',
            'requirements.*.category_id' => 'required|exists:categories,id',
            'requirements.*.subject_id' => 'required|exists:subjects,id',
            'requirements.*.qualification_id' => 'required|exists:qualifications,id',
            'requirements.*.location_id' => 'required|exists:locations,id',
            'requirements.*.salary_range' => 'nullable|string|max:255',
        ]);

        foreach ($request->requirements as $req) {
            JobPost::create([
                'user_id' => auth()->id(),
                'school_name' => $request->school_name,
                'contact_person' => $request->contact_person,
                'email' => $request->email,
                'phone' => $request->phone,
                'title' => $req['title'],
                'description' => $req['description'],
                'category_id' => $req['category_id'],
                'subject_id' => $req['subject_id'],
                'qualification_id' => $req['qualification_id'],
                'location_id' => $req['location_id'],
                'salary_range' => $req['salary_range'] ?? null,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('employer.jobs.index')->with('success', 'Job posted successfully. It will be live after admin approval.');
    }

    public function show($id)
    {
        $job = JobPost::where('user_id', auth()->id())->findOrFail($id);
        return view('employer.jobs.show', compact('job'));
    }

    public function edit($id)
    {
        $job = JobPost::where('user_id', auth()->id())->findOrFail($id);
        
        if ($job->status !== 'pending') {
            return redirect()->route('employer.jobs.index')->with('error', 'Only pending jobs can be edited.');
        }

        $categories = Category::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $qualifications = Qualification::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        return view('employer.jobs.edit', compact('job', 'categories', 'subjects', 'qualifications', 'locations'));
    }

    public function update(Request $request, $id)
    {
        $job = JobPost::where('user_id', auth()->id())->findOrFail($id);
        
        if ($job->status !== 'pending') {
            return redirect()->route('employer.jobs.index')->with('error', 'Only pending jobs can be edited.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'qualification_id' => 'required|exists:qualifications,id',
            'location_id' => 'required|exists:locations,id',
            'salary_range' => 'nullable|string|max:255',
        ]);

        $job->update($request->only([
            'title', 'description', 'category_id', 'subject_id', 'qualification_id', 'location_id', 'salary_range'
        ]));

        return redirect()->route('employer.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy($id)
    {
        $job = JobPost::where('user_id', auth()->id())->findOrFail($id);
        $job->delete();
        return redirect()->route('employer.jobs.index')->with('success', 'Job deleted successfully.');
    }
}
