<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Redirect to admin home if trying to access login while authenticated
        if ($request->is('admin') && Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        }

        // Redirect to login if not authenticated for admin routes
        if (!Auth::guard('admin')->check() && $request->is('admin/*')) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}