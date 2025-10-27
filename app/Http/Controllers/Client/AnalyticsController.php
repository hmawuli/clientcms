<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnalyticsExport;

class AnalyticsController extends Controller
{
    protected AnalyticsService $analyticsService;

    /**
     * Inject AnalyticsService
     */
    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Show overall analytics dashboard
     */
    public function index()
    {
        $analytics = $this->analyticsService->getOverallAnalytics();

        return view('client.analytics.index', compact('analytics'));
    }

    /**
     * Show analytics for a specific page
     */
    public function show(Page $page)
    {
        $pageAnalytics = $this->analyticsService->getPageAnalytics($page->slug);

        return view('client.analytics.show', compact('page', 'pageAnalytics'));
    }

    /**
     * Export analytics for a specific page as Excel
     */
    public function export(Page $page, Request $request)
    {
        $period = $request->input('period', 'last_30_days');

        return Excel::download(
            
            new AnalyticsExport($page, $period, $this->analyticsService),
            "analytics_{$page->slug}_{$period}.xlsx"
        );
    }
}
