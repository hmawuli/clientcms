<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private AnalyticsService $analyticsService
    ) {}

    /**
     * Show client dashboard
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get user's pages
        $pages = $user->pages()->with('theme')->latest()->get();

        // Get aggregate analytics for all pages
        $totalViews = 0;
        $totalUniqueVisitors = 0;
        $recentViews = [];

        foreach ($pages as $page) {
            $analytics = $this->analyticsService->getPageAnalytics($page, 'month');
            $totalViews += $analytics['total_views'];
            $totalUniqueVisitors += $analytics['unique_visitors'];

            if (!empty($analytics['views_by_day'])) {
                foreach ($analytics['views_by_day'] as $dayData) {
                    $date = $dayData['date'];
                    if (!isset($recentViews[$date])) {
                        $recentViews[$date] = 0;
                    }
                    $recentViews[$date] += $dayData['views'];
                }
            }
        }

        // Format recent views for chart
        ksort($recentViews);
        $chartData = [
            'labels' => array_keys($recentViews),
            'data' => array_values($recentViews),
        ];

        return view('client.dashboard', compact(
            'pages',
            'totalViews',
            'totalUniqueVisitors',
            'chartData'
        ));
    }
}
