<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Page extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'user_id',
        'theme_id',
        'title',
        'slug',
        'content',
        'images',
        'custom_colors',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'content' => 'array',
        'images' => 'array',
        'custom_colors' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Boot model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate a unique slug when creating a page
        static::creating(function ($page) {
            if (empty($page->slug)) {
                $baseSlug = Str::slug($page->title);
                $slug = $baseSlug;
                $count = 1;

                // Ensure unique slug
                while (static::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$count}";
                    $count++;
                }

                $page->slug = $slug;
            }
        });

        // Create a new version whenever specific fields are updated
        static::updated(function ($page) {
            if ($page->isDirty(['content', 'images', 'custom_colors', 'theme_id'])) {
                $page->versions()->create([
                    'user_id' => Auth::check() ? Auth::id() : $page->user_id,
                    'content' => $page->getOriginal('content'),
                    'images' => $page->getOriginal('images'),
                    'custom_colors' => $page->getOriginal('custom_colors'),
                    'theme_id' => $page->getOriginal('theme_id'),
                ]);
            }
        });
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function versions()
    {
        return $this->hasMany(PageVersion::class)->latest();
    }

    public function pageViews()
    {
        return $this->hasMany(PageView::class);
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Helper methods
     */
    public function publish()
    {
        $this->update([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function unpublish()
    {
        $this->update(['is_published' => false]);
    }

    public function getActiveColors()
    {
        return $this->custom_colors ?? $this->theme?->colors ?? [];
    }
}
