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
            // Check if column exists before checking value (fallback for missing migration)
            if (array_key_exists('is_active', auth()->user()->getAttributes()) && !auth()->user()->is_active) {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact support.');
            }
            return $next($request);
        }

        return redirect('/')->with('error', 'Unauthorized access.');
    }
}
