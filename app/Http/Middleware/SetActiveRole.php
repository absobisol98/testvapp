<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetActiveRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && ! session()->has('active_role')) {
            session(['active_role' => auth()->user()->activeRole()]);
        }

        return $next($request);
    }
}
