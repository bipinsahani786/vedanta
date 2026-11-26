<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ForcePasswordController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (!$user->requiresPasswordChange()) {
            return redirect()->route('candidate.dashboard');
        }

        return view('candidate.auth.force-password', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.min' => 'Your new password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = Auth::user();

        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Please choose a new private password different from your temporary password.',
            ]);
        }

        $user->completePasswordUpdate($request->password);

        Auth::login($user);
        $request->session()->regenerate();
        session()->flash('candidate_just_logged_in', true);

        return redirect()
            ->route('candidate.dashboard')
            ->with('success', 'Your new password has been saved. You can now access your account normally.');
    }
}
