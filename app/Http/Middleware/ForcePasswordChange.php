<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->requires_password_change) {
            // Allow access to the password change routes and logout so they aren't stuck in an infinite loop
            if (! $request->routeIs('password.change') && ! $request->routeIs('password.update') && ! $request->routeIs('logout')) {
                return redirect()->route('password.change')->with('error', 'You must change your default password before continuing.');
            }
        }

        return $next($request);
    }
}