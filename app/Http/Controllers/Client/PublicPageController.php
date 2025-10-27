<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class PublicPageController extends Controller
{
    public function __construct(
        private AnalyticsService $analyticsService
    ) {}

    /**
     * Display public page
     */
    public function show(string $slug, Request $request)
    {
        $page = Page::where('slug', $slug)
            ->published()
            ->with(['user', 'theme'])
            ->firstOrFail();

        // Track page view (using middleware is better, but this works too)
        $this->trackView($page, $request);

        // Get active colors (custom or theme)
        $colors = $page->getActiveColors();

        return view('public.page', compact('page', 'colors'));
    }

    /**
     * Track page view
     */
    private function trackView(Page $page, Request $request)
    {
        $visitorData = [
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
        ];

        $this->analyticsService->trackPageView($page, $visitorData);
    }
}
