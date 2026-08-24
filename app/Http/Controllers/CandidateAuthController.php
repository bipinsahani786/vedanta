<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class CandidateAuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register_candidate');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Clean up unverified accounts with same email or phone so candidate can retry registration
        $unverifiedUsers = User::where(function($query) use ($request) {
            $query->where('email', $request->email)
                  ->orWhere('phone', $request->phone);
        })->whereNull('email_verified_at')->get();

        foreach ($unverifiedUsers as $unverified) {
            $unverified->profile()?->delete();
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
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => 'candidate',
                'password' => Hash::make($request->password),
            ]);

            $user->profile()->firstOrCreate([]);

            // Process Referral if code was supplied
            if ($request->filled('referral_code')) {
                \App\Services\ReferralService::recordReferral($user, $request->referral_code);
            }

            event(new Registered($user));
            Auth::login($user);

            return redirect()->route('candidate.wizard');
        } catch (\Illuminate\Database\UniqueConstraintViolationException | \Illuminate\Database\QueryException $e) {
            // In case of rapid double-clicks from mobile, if user was just created, auto-login or return friendly message
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser && Hash::check($request->password, $existingUser->password)) {
                Auth::login($existingUser);
                return redirect()->route('candidate.wizard');
            }

            return back()->withInput()->withErrors([
                'email' => 'This email or phone is already registered. Please login to continue.'
            ]);
        }
    }
}
