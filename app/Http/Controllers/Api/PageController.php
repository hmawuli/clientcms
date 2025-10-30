<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Fetch all pages belonging to the authenticated user.
     */
    public function index()
    {
        try {
            // Use Auth::id() which returns null if unauthenticated, preventing method chain crash.
            $userId = Auth::id();
            if (!$userId) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            $pages = Page::where('user_id', $userId)
                ->latest()
                ->paginate(10);

            return PageResource::collection($pages);
        } catch (\Exception $e) {
            // Check if user is authenticated before attempting to log the ID.
            $logUserId = Auth::check() ? Auth::id() : 'Unauthenticated';
            Log::error('Error fetching pages', ['error' => $e->getMessage(), 'user_id' => $logUserId]);
            return response()->json(['message' => 'Failed to load pages'], 500);
        }
    }

    /**
     * Store a new page for the authenticated user.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|array', // Assuming content is a structured array
        ]);

        try {
            // Generate a unique slug based on the title
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $count = 1;
            while (Page::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }

            $page = Page::create([
                'user_id' => $userId,
                'title' => $validated['title'],
                'slug' => $slug, // Use the unique slug
                // Note: It looks like 'content' is stored as a JSON string in the DB
                'content' => json_encode($validated['content']),
                'is_published' => false,
            ]);

            return new PageResource($page);
        } catch (\Exception $e) {
            Log::error('Error creating page', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to create page'], 500);
        }
    }

    /**
     * Display a specific page.
     */
    public function show(Page $page)
    {
        // Use Auth::guest() and Auth::id() for more direct Facade access.
        if (Auth::guest() || $page->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new PageResource($page);
    }

    /**
     * Update an existing page.
     */
    public function update(Request $request, Page $page)
    {
        // Use Auth::guest() and Auth::id() for more direct Facade access.
        if (Auth::guest() || $page->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|array',
        ]);

        try {
            $page->update([
                'title' => $validated['title'] ?? $page->title,
                'content' => isset($validated['content'])
                    ? json_encode($validated['content'])
                    : $page->content,
            ]);

            return new PageResource($page);
        } catch (\Exception $e) {
            Log::error('Error updating page', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to update page'], 500);
        }
    }

    /**
     * Delete a page.
     */
    public function destroy(Page $page)
    {
        // Use Auth::guest() and Auth::id() for more direct Facade access.
        if (Auth::guest() || $page->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $page->delete();
            return response()->json(['message' => 'Page deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting page', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to delete page'], 500);
        }
    }
}
