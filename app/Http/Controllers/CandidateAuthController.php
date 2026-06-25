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
        // Remove unverified user with same email or phone so they can register again
        $unverifiedUser = User::where(function($query) use ($request) {
            if ($request->email) $query->orWhere('email', $request->email);
            if ($request->phone) $query->orWhere('phone', $request->phone);
        })->whereNull('email_verified_at')->first();

        if ($unverifiedUser) {
            $unverifiedUser->delete();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'candidate',
            'password' => Hash::make($request->password),
        ]);

        $user->profile()->create([]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('candidate.wizard');
    }
}
