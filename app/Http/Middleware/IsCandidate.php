<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsCandidate
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'candidate') {
            $user = auth()->user();

            // Check if column exists before checking value (fallback for missing migration)
            if (array_key_exists('is_active', $user->getAttributes()) && ! $user->is_active) {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact support.');
            }

            // Wizard routes, logout, and callbacks must always be accessible
            $isWizardRoute = $request->routeIs('candidate.wizard*') || $request->is('candidate/wizard*');
            $isExemptRoute = $request->is('logout') || $request->is('email/*') || $request->is('phonepe/*') || $request->is('webhook/*');

            if (! $isWizardRoute && ! $isExemptRoute) {
                $profile = $user->profile;
                $hasSigned = $profile && (
                    $profile->is_agreement_signed ||
                    ! empty($profile->signature_data) ||
                    ! empty($profile->signature_date_time) ||
                    ! empty($profile->agreement_signed_at) ||
                    ! empty($profile->agreement_pdf_path) ||
                    $profile->is_manual_agreement
                );

                if (! $hasSigned) {
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Please sign the official agreement before accessing your dashboard.',
                            'redirect' => route('candidate.wizard'),
                        ], 403);
                    }

                    return redirect()->route('candidate.wizard')->with('warning', 'Please complete your registration and sign the agreement to access your dashboard.');
                }
            }

            return $next($request);
        }

        return redirect('/')->with('error', 'Unauthorized access.');
    }
}
