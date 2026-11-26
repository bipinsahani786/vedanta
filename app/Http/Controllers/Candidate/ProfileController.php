<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\State;
use App\Models\City;
use App\Models\Qualification;
use App\Models\Subject;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile ?: $user->profile()->firstOrCreate([]);
        
        $categories = Category::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $qualifications = Qualification::where('is_active', true)->get();
        $states = State::where('is_active', true)->get();
        $cities = City::where('state_id', $profile->preferred_state_id)->where('is_active', true)->get();

        return view('candidate.profile.edit', compact('user', 'profile', 'categories', 'subjects', 'qualifications', 'states', 'cities'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile ?: $user->profile()->firstOrCreate([]);

        $request->validate([
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'highest_qualification_id' => 'required|exists:qualifications,id',
            'other_qualifications' => 'nullable',
            'custom_qualification' => 'nullable|string',
            'preferred_state_id' => 'required|exists:states,id',
            'preferred_city_id' => 'required|exists:cities,id',
            'experience_years' => 'required|integer|min:0',
            'current_salary' => 'required|string',
            'expected_salary' => 'required|string',
            'current_school' => 'nullable|string',
            'english_fluency' => 'required|in:beginner,intermediate,fluent',
            'residential_preference' => 'required|in:residential,day,both',
            'availability_to_join' => 'required|string',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'salary_slip' => 'nullable|mimes:pdf,doc,docx,jpg,png,jpeg|max:5120',
            'offer_letter' => 'nullable|mimes:pdf,doc,docx,jpg,png,jpeg|max:5120',
            'agreement_pdf' => 'nullable|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $profile->resume_path = $path;
        }

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $profile->profile_photo_path = $path;
        }

        if ($request->hasFile('salary_slip')) {
            $path = $request->file('salary_slip')->store('salary_slips', 'public');
            $profile->salary_slip_path = $path;
        }

        if ($request->hasFile('offer_letter')) {
            $path = $request->file('offer_letter')->store('offer_letters', 'public');
            $profile->offer_letter_path = $path;
        }

        if ($request->hasFile('agreement_pdf')) {
            $path = $request->file('agreement_pdf')->store('agreements', 'public');
            $profile->agreement_pdf_path = $path;
            $profile->is_agreement_signed = true;
        }

        $otherQualsArray = is_array($request->other_qualifications) ? $request->other_qualifications : ($request->other_qualifications ? explode(',', $request->other_qualifications) : []);
        if ($request->filled('custom_qualification')) {
            $otherQualsArray[] = trim($request->custom_qualification);
        }
        $otherQualsStr = implode(', ', array_unique(array_filter(array_map('trim', $otherQualsArray))));

        $profile->update([
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'category_id' => $request->category_id,
            'subject_id' => $request->subject_id,
            'highest_qualification_id' => $request->highest_qualification_id,
            'other_qualifications' => $otherQualsStr,
            'preferred_state_id' => $request->preferred_state_id,
            'preferred_city_id' => $request->preferred_city_id,
            'experience_years' => $request->experience_years,
            'current_salary' => $request->current_salary,
            'expected_salary' => $request->expected_salary,
            'current_school' => $request->current_school ?: ($profile->current_school ?: 'Fresher'),
            'english_fluency' => $request->english_fluency,
            'residential_preference' => $request->residential_preference,
            'availability_to_join' => $request->availability_to_join,
            'is_profile_complete' => true,
        ]);

        $freshProfile = $profile->fresh();
        $hasSigned = $freshProfile && (
            $freshProfile->is_agreement_signed
            || ! empty($freshProfile->signature_data)
            || ! empty($freshProfile->agreement_signed_at)
            || ! empty($freshProfile->agreement_pdf_path)
            || $freshProfile->is_manual_agreement
        );
        if ($freshProfile && $hasSigned && $freshProfile->has_paid_plan && empty($freshProfile->registration_completed_at)) {
            $freshProfile->update(['registration_completed_at' => now()]);
        }

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

    /**
     * Stream or download the candidate's uploaded resume from public storage.
     */
    public function viewResume()
    {
        $user = auth()->user();
        $profile = $user->profile;

        if (! $profile || empty($profile->resume_path) || ! \Illuminate\Support\Facades\Storage::disk('public')->exists($profile->resume_path)) {
            return redirect()->to(route('candidate.profile.edit') . '#resume-section')
                ->with('info', 'No uploaded resume found. Please upload your resume below or use the Resume Builder.');
        }

        $cleanName = preg_replace('/[^A-Za-z0-9_]/', '_', $user->name ?? 'Candidate');
        $ext = strtolower(pathinfo($profile->resume_path, PATHINFO_EXTENSION) ?: 'pdf');
        $mimeMap = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        $mimeType = $mimeMap[$ext] ?? 'application/octet-stream';

        return \Illuminate\Support\Facades\Storage::disk('public')->response(
            $profile->resume_path,
            'Resume_' . $cleanName . '.' . $ext,
            ['Content-Type' => $mimeType],
            'inline'
        );
    }

    /**
     * Submit a profile verification request notification to admins.
     */
    public function requestVerification(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile ?: $user->profile()->firstOrCreate([]);

        if ($profile->is_verified) {
            return redirect()->route('candidate.dashboard')
                ->with('info', 'Your profile is already verified.');
        }

        if (! $profile->hasCompletedProfileDetails()) {
            return redirect()->route('candidate.profile.edit')
                ->with('warning', 'Please complete your profile details first before requesting verification.');
        }

        $cid = (int) $user->id;
        $lastVerifiedNotifAt = \Illuminate\Support\Facades\DB::table('notifications')
            ->where('type', 'App\Notifications\ProfileVerified')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $cid)
            ->max('created_at');

        $existingRequests = \Illuminate\Support\Facades\DB::table('notifications')
            ->whereIn('type', ['App\Notifications\VerificationRequested', 'App\Notifications\VerificationRequestSubmitted'])
            ->where(function ($q) use ($cid) {
                $q->where('data', 'like', '%"candidate_id":' . $cid . ',%')
                  ->orWhere('data', 'like', '%"candidate_id":' . $cid . '}%')
                  ->orWhere('data', 'like', '%"candidate_id": ' . $cid . ',%')
                  ->orWhere('data', 'like', '%"candidate_id": ' . $cid . '}%');
            })
            ->orderByDesc('created_at')
            ->get(['id', 'data', 'created_at']);

        foreach ($existingRequests as $reqRow) {
            $decoded = json_decode($reqRow->data, true);
            if (is_array($decoded) && (int) ($decoded['candidate_id'] ?? 0) === $cid) {
                if (! $lastVerifiedNotifAt || $reqRow->created_at > $lastVerifiedNotifAt) {
                    return redirect()->route('candidate.dashboard')
                        ->with('info', 'Your verification request is already under review by our team.');
                }
                break;
            }
        }

        $admins = \App\Models\User::where('role', 'admin')->get();
        $vpaId = $profile->vpa_id ? " ({$profile->vpa_id})" : '';
        $payload = json_encode([
            'title' => 'Candidate Verification Request',
            'message' => "{$user->name}{$vpaId} has requested profile verification.",
            'candidate_id' => $cid,
            'vpa_id' => $profile->vpa_id,
        ]);

        foreach ($admins as $admin) {
            \Illuminate\Support\Facades\DB::table('notifications')->insert([
                'id' => \Illuminate\Support\Str::uuid()->toString(),
                'type' => 'App\Notifications\VerificationRequested',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $admin->id,
                'data' => $payload,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        \Illuminate\Support\Facades\DB::table('notifications')->insert([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => 'App\Notifications\VerificationRequestSubmitted',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $cid,
            'data' => json_encode([
                'title' => 'Verification Request Submitted',
                'message' => 'Your profile verification request has been received. Our team will review your profile shortly.',
                'candidate_id' => $cid,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('candidate.dashboard')
            ->with('success', 'Verification request submitted successfully! Our team will review your profile shortly.');
    }

    /**
     * Update only the candidate's profile photo from the dashboard.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'profile_photo.required' => 'Please select a photo to upload.',
            'profile_photo.image' => 'The selected file must be a valid image (JPG, PNG, WEBP).',
            'profile_photo.mimes' => 'Only JPG, JPEG, PNG, and WEBP images are allowed.',
            'profile_photo.max' => 'Profile photo size must not exceed 5MB.',
        ]);

        $user = auth()->user();
        $profile = $user->profile ?: $user->profile()->firstOrCreate([]);

        $path = $request->file('profile_photo')->store('profile_photos', 'public');
        $profile->update(['profile_photo_path' => $path]);

        return redirect()->route('candidate.dashboard')
            ->with('success', 'Profile photo updated successfully!');
    }
}
