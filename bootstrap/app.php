<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('employer*')) {
                return route('login', ['role' => 'employer']);
            }
            if ($request->is('admin*')) {
                return route('login', ['role' => 'admin']);
            }
            return route('login');
        });
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'employer' => \App\Http\Middleware\IsEmployer::class,
            'candidate' => \App\Http\Middleware\IsCandidate::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'candidate/payment/callback',
            'candidate/wizard/callback',
            'candidate/service-charge/callback',
            'webhook/phonepe'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
