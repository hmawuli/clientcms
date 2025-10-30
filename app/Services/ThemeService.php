<?php

namespace App\Services;

use App\Models\Theme;
use App\Models\Page;

class ThemeService
{
    /**
     * Apply theme to a page
     */
    public function applyTheme(Page $page, Theme $theme): Page
    {
        $page->update([
            'theme_id' => $theme->id,
            'custom_colors' => null, // Clear custom colors when applying theme
        ]);

        return $page->fresh();
    }

    /**
     * Apply custom colors to a page
     */
    public function applyCustomColors(Page $page, array $colors): Page
    {
        $page->update([
            'custom_colors' => $colors,
        ]);

        return $page->fresh();
    }

    /**
     * Get all active themes
     */
    public function getActiveThemes()
    {
        return Theme::active()->get();
    }

    /**
     * Preview theme colors
     */
    public function previewTheme(Theme $theme): array
    {
        return [
            'theme_id' => $theme->id,
            'theme_name' => $theme->name,
            'colors' => $theme->colors,
            'description' => $theme->description,
        ];
    }
}
