<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ThrottleLoginAttempts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $key = 'login:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->withErrors([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ])->with('status', 'too-many-attempts');
        }

        // Proceed to the login request
        $response = $next($request);

        // If login failed, count it as an attempt
        if ($response->getStatusCode() === 302 &&
            $response->headers->get('location') === route('filament.admin.auth.login') &&
            $request->session()->has('errors')) {
            RateLimiter::hit($key, 60); // Keep track for 1 minute (5 attempts per minute)
        }

        // Reset limiter on successful login
        if ($response->getStatusCode() === 302 &&
            $response->headers->get('location') === route('filament.admin.pages.dashboard') &&
            !$request->session()->has('errors')) {
            RateLimiter::clear($key);
        }

        return $response;
    }
}
