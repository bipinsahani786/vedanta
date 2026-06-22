<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->employerProfile ?? $user->employerProfile()->create();
        return view('employer.profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'school_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'about' => 'nullable|string',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Update User
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.']);
            }
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        // Update Profile
        $profile = $user->employerProfile ?? $user->employerProfile()->create();
        
        $profile->update([
            'school_name' => $request->school_name,
            'contact_person' => $request->contact_person,
            'address' => $request->address,
            'about' => $request->about,
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('employer_logos', 'public');
            $profile->update(['logo_path' => $path]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
