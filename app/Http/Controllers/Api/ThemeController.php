<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ThemeResource;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $themes = Theme::select('id', 'name', 'description', 'colors')->get();
            return ThemeResource::collection($themes);
        } catch (\Exception $e) {
            Log::error('Error loading themes', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to load themes'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:themes,name',
            'description' => 'nullable|string',
            'colors' => 'nullable|array',
        ]);

        try {
            $theme = Theme::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'colors' => isset($validated['colors'])
                    ? json_encode($validated['colors'])
                    : null,
            ]);

            return new ThemeResource($theme);
        } catch (\Exception $e) {
            Log::error('Error creating theme', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to create theme'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $theme = Theme::findOrFail($id);
            return new ThemeResource($theme);
        } catch (\Exception $e) {
            Log::error('Error fetching theme', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Theme not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:themes,name,' . $id,
            'description' => 'nullable|string',
            'colors' => 'nullable|array',
        ]);

        try {
            $theme = Theme::findOrFail($id);

            $theme->update([
                'name' => $validated['name'] ?? $theme->name,
                'description' => $validated['description'] ?? $theme->description,
                'colors' => isset($validated['colors'])
                    ? json_encode($validated['colors'])
                    : $theme->colors,
            ]);

            return new ThemeResource($theme);
        } catch (\Exception $e) {
            Log::error('Error updating theme', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to update theme'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $theme = Theme::findOrFail($id);
            $theme->delete();

            return response()->json(['message' => 'Theme deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting theme', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to delete theme'], 500);
        }
    }
}
