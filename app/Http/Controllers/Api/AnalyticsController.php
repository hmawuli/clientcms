<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnalyticsResource;
use App\Models\Analytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of analytics records.
     */
    public function index()
    {
        try {
            $analytics = Analytics::select('id', 'page_id', 'views', 'unique_visitors', 'avg_time_spent', 'bounce_rate', 'created_at')
                ->orderByDesc('created_at')
                ->get();

            return AnalyticsResource::collection($analytics);
        } catch (\Exception $e) {
            Log::error('Error fetching analytics data', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to load analytics data'], 500);
        }
    }

    /**
     * Store a newly created analytics record.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'page_id'         => 'required|integer|exists:pages,id',
                'views'           => 'required|integer|min:0',
                'unique_visitors' => 'required|integer|min:0',
                'avg_time_spent'  => 'nullable|numeric|min:0',
                'bounce_rate'     => 'nullable|numeric|min:0|max:100',
            ]);

            $analytics = Analytics::create($validated);

            return new AnalyticsResource($analytics);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error storing analytics data', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to create analytics record'], 500);
        }
    }

    /**
     * Display a specific analytics record.
     */
    public function show(string $id)
    {
        try {
            $analytics = Analytics::findOrFail($id);
            return new AnalyticsResource($analytics);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Analytics record not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching analytics record', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to load analytics record'], 500);
        }
    }

    /**
     * Update the specified analytics record.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'views'           => 'nullable|integer|min:0',
                'unique_visitors' => 'nullable|integer|min:0',
                'avg_time_spent'  => 'nullable|numeric|min:0',
                'bounce_rate'     => 'nullable|numeric|min:0|max:100',
            ]);

            $analytics = Analytics::findOrFail($id);
            $analytics->update($validated);

            return new AnalyticsResource($analytics);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Analytics record not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error updating analytics record', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to update analytics record'], 500);
        }
    }

    /**
     * Remove the specified analytics record.
     */
    public function destroy(string $id)
    {
        try {
            $analytics = Analytics::findOrFail($id);
            $analytics->delete();

            return response()->json(['message' => 'Analytics record deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Analytics record not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting analytics record', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to delete analytics record'], 500);
        }
    }
}
