<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\VisitorSession;
use App\Models\PageView;
use Illuminate\Database\Seeder;

class PageViewSeeder extends Seeder
{
    public function run(): void
    {
        $pages = Page::all();
        $sessions = VisitorSession::all();

        foreach ($sessions as $session) {
            foreach ($pages as $page) {
                PageView::create([
                    'page_id' => $page->id,
                    'visitor_session_id' => $session->id,
                    'viewed_at' => now()->subMinutes(rand(1, 1000)),
                ]);
            }
        }
    }
}
