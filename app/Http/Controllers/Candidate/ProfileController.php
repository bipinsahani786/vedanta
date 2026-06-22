<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Location;
use App\Models\Qualification;
use App\Models\Subject;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile;
        
        $categories = Category::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $qualifications = Qualification::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        return view('candidate.profile.edit', compact('user', 'profile', 'categories', 'subjects', 'qualifications', 'locations'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'highest_qualification_id' => 'required|exists:qualifications,id',
            'preferred_location_id' => 'required|exists:locations,id',
            'experience_years' => 'required|integer|min:0',
            'current_salary' => 'nullable|string',
            'expected_salary' => 'required|string',
            'current_school' => 'nullable|string',
            'english_fluency' => 'nullable|in:beginner,intermediate,fluent',
            'residential_preference' => 'nullable|in:residential,day,both',
            'availability_to_join' => 'nullable|string',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = auth()->user();
        $profile = $user->profile;

        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $profile->resume_path = $path;
        }

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $profile->profile_photo_path = $path;
        }

        $profile->update([
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'category_id' => $request->category_id,
            'subject_id' => $request->subject_id,
            'highest_qualification_id' => $request->highest_qualification_id,
            'preferred_location_id' => $request->preferred_location_id,
            'experience_years' => $request->experience_years,
            'current_salary' => $request->current_salary,
            'expected_salary' => $request->expected_salary,
            'current_school' => $request->current_school,
            'english_fluency' => $request->english_fluency,
            'residential_preference' => $request->residential_preference,
            'availability_to_join' => $request->availability_to_join,
            'is_profile_complete' => true,
        ]);

        return redirect()->route('candidate.profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->with('password_error', 'Current password is incorrect.');
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password),
        ]);

        return back()->with('password_success', 'Password updated successfully!');
    }
}
