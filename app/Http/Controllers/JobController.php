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
        $categories = Category::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $qualifications = Qualification::where('is_active', true)->get();
        $states = State::where('is_active', true)->get();

        return view('post-job', compact('categories', 'subjects', 'qualifications', 'states'));
    }

    public function show(JobPost $job)
    {
        if ($job->status !== 'approved') {
            abort(404);
        }
        
        return view('jobs.show', compact('job'));
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
            'qualification_id' => 'required|exists:qualifications,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'salary_range' => 'nullable|string|max:255',
        ]);

        JobPost::create([
            'user_id' => auth()->check() && auth()->user()->role === 'employer' ? auth()->id() : null,
            'school_name' => $request->school_name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'subject_id' => $request->subject_id,
            'qualification_id' => $request->qualification_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'salary_range' => $request->salary_range,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Your job requirement has been submitted successfully. Our team will review and approve it shortly.');
    }
}
