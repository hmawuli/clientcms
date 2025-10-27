<?php

namespace App\Services;

use App\Models\Page;
use App\Models\PageView;
use App\Models\VisitorSession;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Track a page view
     */
    public function trackPageView(Page $page, array $visitorData): PageView
    {
        // Get or create visitor session
        $session = $this->getOrCreateSession($visitorData);

        // Create page view record
        $pageView = PageView::create([
            'page_id' => $page->id,
            'visitor_session_id' => $session->id,
            'ip_address' => $visitorData['ip_address'],
            'user_agent' => $visitorData['user_agent'],
            'referrer' => $visitorData['referrer'] ?? null,
            'viewed_at' => now(),
        ]);

        // Update session
        $session->increment('page_views');
        $session->update(['last_visit' => now()]);

        return $pageView;
    }

    /**
     * Get or create visitor session
     */
    private function getOrCreateSession(array $visitorData): VisitorSession
    {
        $sessionId = $visitorData['session_id'];

        $session = VisitorSession::where('session_id', $sessionId)->first();

        if (!$session) {
            $session = VisitorSession::create([
                'session_id' => $sessionId,
                'ip_address' => $visitorData['ip_address'],
                'user_agent' => $visitorData['user_agent'],
                'first_visit' => now(),
                'last_visit' => now(),
                'page_views' => 0,
            ]);
        }

        return $session;
    }

    /**
     * Get analytics for a page
     */
    public function getPageAnalytics(Page $page, string $period = 'month'): array
    {
        $startDate = $this->getStartDate($period);

        return [
            'total_views' => $this->getTotalViews($page, $startDate),
            'unique_visitors' => $this->getUniqueVisitors($page, $startDate),
            'returning_visitors' => $this->getReturningVisitors($page, $startDate),
            'views_by_day' => $this->getViewsByDay($page, $startDate),
            'top_referrers' => $this->getTopReferrers($page, $startDate),
            'period' => $period,
        ];
    }

    /**
     * Get total views for a page
     */
    private function getTotalViews(Page $page, Carbon $startDate): int
    {
        return $page->pageViews()
            ->where('viewed_at', '>=', $startDate)
            ->count();
    }

    /**
     * Get unique visitors
     */
    private function getUniqueVisitors(Page $page, Carbon $startDate): int
    {
        return $page->pageViews()
            ->where('viewed_at', '>=', $startDate)
            ->distinct('ip_address')
            ->count('ip_address');
    }

    /**
     * Get returning visitors
     */
    private function getReturningVisitors(Page $page, Carbon $startDate): int
    {
        return $page->pageViews()
            ->where('viewed_at', '>=', $startDate)
            ->whereHas('visitorSession', function ($query) {
                $query->where('page_views', '>', 1);
            })
            ->distinct('visitor_session_id')
            ->count('visitor_session_id');
    }

    /**
     * Get views grouped by day
     */
    private function getViewsByDay(Page $page, Carbon $startDate): array
    {
        $views = $page->pageViews()
            ->where('viewed_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(viewed_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $views->map(function ($item) {
            return [
                'date' => $item->date,
                'views' => $item->count,
            ];
        })->toArray();
    }

    /**
     * Get top referrers
     */
    private function getTopReferrers(Page $page, Carbon $startDate): array
    {
        return $page->pageViews()
            ->where('viewed_at', '>=', $startDate)
            ->whereNotNull('referrer')
            ->select('referrer', DB::raw('COUNT(*) as count'))
            ->groupBy('referrer')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Get start date based on period
     */
    private function getStartDate(string $period): Carbon
    {
        return match ($period) {
            'day' => Carbon::today(),
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonth(),
        };
    }

    /**
     * Export analytics data to array
     */
    public function exportAnalytics(Page $page, string $period = 'month'): array
    {
        $startDate = $this->getStartDate($period);

        return $page->pageViews()
            ->where('viewed_at', '>=', $startDate)
            ->with('visitorSession')
            ->get()
            ->map(function ($view) {
                return [
                    'Date' => $view->viewed_at->format('Y-m-d H:i:s'),
                    'IP Address' => $view->ip_address,
                    'User Agent' => $view->user_agent,
                    'Referrer' => $view->referrer ?? 'Direct',
                    'Returning Visitor' => $view->visitorSession?->isReturning() ? 'Yes' : 'No',
                ];
            })
            ->toArray();
    }
}
