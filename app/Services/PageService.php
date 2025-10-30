<?php

namespace App\Services;

use App\Models\Page;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PageService
{
    /**
     * Create a new page for a user.
     */
    public function createPage(User $user, array $data): Page
    {
        return $user->pages()->create([
            'title' => $data['title'],
            'theme_id' => $data['theme_id'] ?? null,
            'content' => $data['content'] ?? '',
            'is_published' => false,
            'images' => [],
        ]);
    }

    /**
     * Update an existing page.
     */
    public function updatePage(Page $page, array $data): Page
    {
        DB::transaction(function () use ($page, $data) {
            $page->update($data);

            // Create a version history
            $page->versions()->create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'data' => $page->toArray(),
            ]);
        });

        return $page->fresh();
    }

    /**
     * Restore a page version.
     */
    public function restoreVersion(Page $page, int $versionId): Page
    {
        $version = $page->versions()->findOrFail($versionId);
        $page->update($version->data);
        return $page;
    }

    /**
     * Duplicate an existing page.
     */
    public function duplicatePage(Page $page, string $title): Page
    {
        $newPage = $page->replicate();
        $newPage->title = $title;
        $newPage->is_published = false;
        $newPage->save();

        return $newPage;
    }
}
