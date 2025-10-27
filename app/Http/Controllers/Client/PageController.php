<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageUpdateRequest;
use App\Models\Page;
use App\Models\Theme;
use App\Services\PageService;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PageController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        private PageService $pageService,
        private ImageUploadService $imageService
    ) {}

    /**
     * Show all pages for client
     */
    public function index(Request $request)
    {
        $pages = $request->user()
            ->pages()
            ->with('theme')
            ->latest()
            ->paginate(10);

        return view('client.pages.index', compact('pages'));
    }

    /**
     * Show create page form
     */
    public function create()
    {
        $themes = Theme::active()->get();
        return view('client.pages.create', compact('themes'));
    }

    /**
     * Store new page
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'theme_id' => 'nullable|exists:themes,id',
        ]);

        $page = $this->pageService->createPage($request->user(), $validated);

        return redirect()->route('client.pages.edit', $page)
            ->with('success', 'Page created successfully!');
    }

    /**
     * Show edit page form
     */
    public function edit(Page $page)
    {
        $this->authorize('update', $page);

        $themes = Theme::active()->get();
        $versions = $page->versions()->with('user')->limit(10)->get();

        return view('client.pages.edit', compact('page', 'themes', 'versions'));
    }

    /**
     * Update page content
     */
    public function update(PageUpdateRequest $request, Page $page)
    {
        $this->authorize('update', $page);

        $page = $this->pageService->updatePage($page, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Page updated successfully!',
            'page' => $page,
        ]);
    }

    /**
     * Upload image for page
     */
    public function uploadImage(Request $request, Page $page)
    {
        $this->authorize('update', $page);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'type' => 'required|in:logo,hero,profile,background',
        ]);

        $imageData = $this->imageService->uploadImage(
            $request->file('image'),
            $request->input('type')
        );

        // Update page images
        $images = $page->images ?? [];
        $images[$request->input('type')] = $imageData['optimized'];

        $page->update(['images' => $images]);

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully!',
            'image' => $imageData,
        ]);
    }

    /**
     * Publish/unpublish page
     */
    public function togglePublish(Page $page)
    {
        $this->authorize('update', $page);

        if ($page->is_published) {
            $page->unpublish();
            $message = 'Page unpublished successfully!';
        } else {
            $page->publish();
            $message = 'Page published successfully!';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_published' => $page->is_published,
        ]);
    }

    /**
     * Delete page
     */
    public function destroy(Page $page)
    {
        $this->authorize('delete', $page);

        $page->delete();

        return redirect()->route('client.pages.index')
            ->with('success', 'Page deleted successfully!');
    }

    /**
     * Restore page from version
     */
    public function restoreVersion(Page $page, int $versionId)
    {
        $this->authorize('update', $page);

        $page = $this->pageService->restoreVersion($page, $versionId);

        return response()->json([
            'success' => true,
            'message' => 'Version restored successfully!',
            'page' => $page,
        ]);
    }

    /**
     * Duplicate page
     */
    public function duplicate(Request $request, Page $page)
    {
        $this->authorize('view', $page);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $newPage = $this->pageService->duplicatePage($page, $validated['title']);

        return redirect()->route('client.pages.edit', $newPage)
            ->with('success', 'Page duplicated successfully!');
    }
}
