<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageVersion;
use Illuminate\Database\Seeder;

class PageVersionSeeder extends Seeder
{
    public function run(): void
    {
        $pages = Page::all();

        foreach ($pages as $page) {
            // Create an initial published version
            PageVersion::create([
                'page_id' => $page->id,
                'version_number' => 1,
                'content' => $page->content,
                'is_published' => true,
                'published_at' => $page->published_at,
            ]);

            // Create a draft version as well
            PageVersion::create([
                'page_id' => $page->id,
                'version_number' => 2,
                'content' => array_merge($page->content, [
                    'hero_title' => 'Updated ' . $page->content['hero_title'],
                ]),
                'is_published' => false,
                'published_at' => null,
            ]);
        }
    }
}
