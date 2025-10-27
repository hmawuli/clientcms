<?php

namespace App\Exports;

use App\Models\Page;
use App\Services\AnalyticsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AnalyticsExport implements FromCollection, WithHeadings
{
    protected Page $page;
    protected string $period;
    protected AnalyticsService $analyticsService;

    /**
     * Constructor
     */
    public function __construct(Page $page, string $period, AnalyticsService $analyticsService)
    {
        $this->page = $page;
        $this->period = $period;
        $this->analyticsService = $analyticsService;
    }

    /**
     * Return a collection of data for the Excel export
     */
    public function collection(): Collection
    {
        // getPageAnalytics should return an array or collection of data
        return collect($this->analyticsService->getPageAnalytics($this->page->slug, $this->period));
    }

    /**
     * Optional column headings for Excel
     */
    public function headings(): array
    {
        // Adjust these headings to match your data structure
        return ['Date', 'Views', 'Clicks', 'Conversions'];
    }
}
