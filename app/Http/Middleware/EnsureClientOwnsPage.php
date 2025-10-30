<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClientOwnsPage
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $page = $request->route('page');

        if ($page && $request->user()->id !== $page->user_id && !$request->user()->isAdmin()) {
            abort(403, 'Unauthorized access to this page.');
        }

        return $next($request);
    }
}
