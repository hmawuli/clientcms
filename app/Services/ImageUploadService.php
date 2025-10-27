<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager; // Use the manager class
use Illuminate\Support\Facades\Validator; // Needed for validation

class ImageUploadService
{
    /**
     * Upload and process an image
     */
    public function uploadImage(UploadedFile $file, string $type = 'general'): array
    {
        // Validate image using a dedicated helper method
        $this->validateImage($file);

        // Generate unique filename
        $filename = $this->generateFilename($file);

        // Determine path based on type (e.g., 'images/logos')
        $path = $this->getPath($type);

        // Store original file (e.g., 'logos/random.jpg')
        $fullPath = $file->storeAs($path, $filename, 'public');

        // Create optimized version
        $optimizedPath = $this->optimizeImage($fullPath, $type);

        return [
            // Returns the relative path used by Storage::url()
            'original' => $fullPath,
            'optimized' => $optimizedPath,
            'filename' => $filename,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ];
    }

    /**
     * Delete an image and its optimized counterpart.
     */
    public function deleteImage(string $path): bool
    {
        // Original file path (e.g., images/logos/file.jpg)
        $success = Storage::disk('public')->delete($path);

        // Optimized file path (e.g., images/logos/file_optimized.jpg)
        $optimizedPath = str_replace('.', '_optimized.', $path);
        Storage::disk('public')->delete($optimizedPath);

        return $success;
    }

    /**
     * Validate uploaded image
     */
    private function validateImage(UploadedFile $file): void
    {
        $validator = Validator::make(['image' => $file], [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        $validator->validate();
    }

    /**
     * Generate unique filename
     */
    private function generateFilename(UploadedFile $file): string
    {
        return Str::random(40) . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Get storage path based on type
     */
    private function getPath(string $type): string
    {
        return match ($type) {
            'logo' => 'images/logos',
            'hero' => 'images/heroes',
            'profile' => 'images/profiles',
            'background' => 'images/backgrounds',
            default => 'images/general',
        };
    }

    /**
     * Optimize image for web using Intervention Image v3
     */
    private function optimizeImage(string $path, string $type): string
    {
        $fullPath = Storage::disk('public')->path($path);
        $optimizedPath = str_replace('.', '_optimized.', $path);
        $optimizedFullPath = Storage::disk('public')->path($optimizedPath);

        // Get max dimensions based on type
        [$maxWidth, $maxHeight] = $this->getMaxDimensions($type);

        // 1. Initialize the Image Manager (using the GD driver)
        $manager = ImageManager::gd();

        // 2. Load and optimize image (v3: read() replaces make())
        $image = $manager->read($fullPath);

        // 3. Resize if needed
        $image->resize($maxWidth, $maxHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // 4. Save optimized version (v3: save() simplifies quality argument)
        $image->save($optimizedFullPath, quality: 85);

        return $optimizedPath;
    }

    /**
     * Get max dimensions based on image type
     */
    private function getMaxDimensions(string $type): array
    {
        return match ($type) {
            'logo' => [300, 300],
            'hero' => [1920, 1080],
            'profile' => [500, 500],
            'background' => [1920, 1080],
            default => [1200, 1200],
        };
    }
}
