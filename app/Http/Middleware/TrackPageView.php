<?php

namespace App\Http\Middleware;

use App\Services\AnalyticsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    public function __construct(
        private AnalyticsService $analyticsService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Track view after response is ready
        if ($request->route('page')) {
            $page = $request->route('page');

            $visitorData = [
                'session_id' => session()->getId(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer'),
            ];

            // Queue the tracking job for better performance
            dispatch(function () use ($page, $visitorData) {
                app(AnalyticsService::class)->trackPageView($page, $visitorData);
            })->afterResponse();
        }

        return $response;
    }
}
