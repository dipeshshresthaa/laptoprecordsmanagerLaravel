<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in AND is an admin
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request); // Allow the request to proceed
        }

        // If not an admin, block access with a 403 Forbidden error
        abort(403, 'Unauthorized action. You must be an administrator to access this area.');
        
        // Alternatively, you could redirect them back with an error:
        // return redirect()->route('employees.index')->with('error', 'You do not have permission to do that.');
    }
}