<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'employer') {
                return redirect()->intended('/employer/dashboard');
            } else {
                return redirect()->intended('/candidate/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showOtpForm()
    {
        return view('auth.otp');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();
        $otp = rand(100000, 999999);
        
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        // Send OTP email (using basic raw mail for simplicity)
        try {
            Mail::raw("Your Vedanta Placement Agency login OTP is: $otp. It is valid for 10 minutes.", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Login OTP - Vedanta Placement Agency');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('OTP Email Failed: ' . $e->getMessage());
        }

        // Store email in session to verify OTP
        session(['otp_email' => $user->email]);

        return redirect()->route('login.otp')->with('success', 'OTP has been sent to your email.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please try again.']);
        }

        $user = User::where('email', $email)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>', now())
            ->first();

        if ($user) {
            // Clear OTP
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            session()->forget('otp_email');

            Auth::login($user, true); // login and remember
            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'employer') {
                return redirect()->intended('/employer/dashboard');
            } else {
                return redirect()->intended('/candidate/dashboard');
            }
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }
}
