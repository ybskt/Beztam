<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PageView;
use Symfony\Component\HttpFoundation\Response;

class TrackViews
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip if this is an admin route
        if (str_starts_with($request->path(), 'admin')) {
            return $next($request);
        }

        // Skip non-GET requests and AJAX requests
        if (!$request->isMethod('GET') || $request->ajax()) {
            return $next($request);
        }

        // Process the request first
        $response = $next($request);

        // Track the page view after response is ready
        PageView::create([
            'url' => $request->path(),
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return $response;
    }
}