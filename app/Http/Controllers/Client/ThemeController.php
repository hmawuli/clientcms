<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Theme;
use App\Services\ThemeService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // ✅ Add this
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    use AuthorizesRequests; // ✅ Include the trait to enable $this->authorize()

    public function __construct(
        private ThemeService $themeService
    ) {}

    /**
     * Get all themes
     */
    public function index()
    {
        $themes = $this->themeService->getActiveThemes();

        return response()->json([
            'success' => true,
            'themes' => $themes,
        ]);
    }

    /**
     * Apply theme to page
     */
    public function apply(Request $request, Page $page)
    {
        $this->authorize('update', $page); // ✅ Works now

        $validated = $request->validate([
            'theme_id' => 'required|exists:themes,id',
        ]);

        $theme = Theme::findOrFail($validated['theme_id']);
        $page = $this->themeService->applyTheme($page, $theme);

        return response()->json([
            'success' => true,
            'message' => 'Theme applied successfully!',
            'page' => $page->load('theme'),
        ]);
    }

    /**
     * Apply custom colors
     */
    public function applyCustomColors(Request $request, Page $page)
    {
        $this->authorize('update', $page); // ✅ Works now

        $validated = $request->validate([
            'colors' => 'required|array',
            'colors.primary' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'colors.secondary' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'colors.accent' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'colors.background' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'colors.text' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'colors.heading' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $page = $this->themeService->applyCustomColors($page, $validated['colors']);

        return response()->json([
            'success' => true,
            'message' => 'Custom colors applied successfully!',
            'page' => $page,
        ]);
    }

    /**
     * Preview theme
     */
    public function preview(Theme $theme)
    {
        $preview = $this->themeService->previewTheme($theme);

        return response()->json([
            'success' => true,
            'preview' => $preview,
        ]);
    }
}
