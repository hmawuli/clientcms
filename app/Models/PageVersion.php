<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'user_id',
        'content',
        'images',
        'custom_colors',
        'theme_id',
        'change_note',
    ];

    protected $casts = [
        'content' => 'array',
        'images' => 'array',
        'custom_colors' => 'array',
    ];

    // Relationships
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    // Restore this version
    public function restore()
    {
        $this->page->update([
            'content' => $this->content,
            'images' => $this->images,
            'custom_colors' => $this->custom_colors,
            'theme_id' => $this->theme_id,
        ]);
    }
}
