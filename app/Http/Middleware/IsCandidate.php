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

            $isImpersonating = $request->session()->has('impersonate_admin_id')
                || $request->session()->has('admin_id');

            if (
                !$isImpersonating
                && $user->requiresPasswordChange()
                && !$request->routeIs('candidate.force-password.*')
                && !$request->routeIs('logout')
            ) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'message' => 'Please change your temporary password before continuing.',
                        'redirect' => route('candidate.force-password.show'),
                    ], 403);
                }

                return redirect()
                    ->route('candidate.force-password.show')
                    ->with('warning', 'You signed in with a temporary password. Please set a new private password to continue.');
            }

            return $next($request);
        }

        return redirect('/')->with('error', 'Unauthorized access.');
    }
}
