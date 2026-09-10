<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class EmployerAuthController extends Controller
{
    public function showRegistrationForm()
    {
        $categories = \App\Models\Category::with(['subjects' => function($q) {
            $q->where('subjects.is_active', true)->orderBy('name');
        }])->where('is_active', true)->orderBy('name')->get();

        return view('auth.register_employer', compact('categories'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:15',
            'category_id' => 'nullable|exists:categories,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Clean up unverified accounts with same email or phone so employer can retry registration
        $unverifiedUsers = User::where(function($query) use ($request) {
            $query->where('email', $request->email)
                  ->orWhere('phone', $request->phone);
        })->whereNull('email_verified_at')->get();

        foreach ($unverifiedUsers as $unverified) {
            $unverified->employerProfile()?->delete();
            $unverified->delete();
        }

        // Check if an existing verified user already exists with this email or phone
        $existingEmailUser = User::where('email', $request->email)->first();
        if ($existingEmailUser) {
            return back()->withInput()->withErrors([
                'email' => 'This email is already registered. Please login instead.'
            ]);
        }

        $existingPhoneUser = User::where('phone', $request->phone)->first();
        if ($existingPhoneUser) {
            return back()->withInput()->withErrors([
                'phone' => 'This mobile number is already registered. Please login instead.'
            ]);
        }

        try {
            $user = User::create([
                'name' => $request->contact_person,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => 'employer',
                'password' => Hash::make($request->password),
            ]);

            $user->employerProfile()->firstOrCreate([
                'school_name' => $request->school_name,
                'contact_person' => $request->contact_person,
            ]);

            event(new Registered($user));
            Auth::login($user);

            return redirect()->route('verification.notice');
        } catch (\Illuminate\Database\UniqueConstraintViolationException | \Illuminate\Database\QueryException $e) {
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser && Hash::check($request->password, $existingUser->password)) {
                Auth::login($existingUser);
                return redirect()->route('verification.notice');
            }

            return back()->withInput()->withErrors([
                'email' => 'This email or phone is already registered. Please login to continue.'
            ]);
        }
    }
}
