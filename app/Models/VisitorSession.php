<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'ip_address',
        'user_agent',
        'first_visit',
        'last_visit',
        'page_views',
    ];

    protected $casts = [
        'first_visit' => 'datetime',
        'last_visit' => 'datetime',
    ];

    // Relationships
    public function pageViews()
    {
        return $this->hasMany(PageView::class);
    }

    // Check if returning visitor
    public function isReturning(): bool
    {
        return $this->page_views > 1;
    }
}
