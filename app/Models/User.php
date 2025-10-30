<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// FIX: Import the models that exist in your project
use App\Models\Page;
use App\Models\PageView;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ... (fillable, hidden, casts are correctly set) ...

    // -------------------------------------------------------------------------
    // ROLE CHECK
    // -------------------------------------------------------------------------

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // -------------------------------------------------------------------------
    // RELATIONSHIPS
    // -------------------------------------------------------------------------

    public function pages()
    {
        return $this->hasMany(Page::class, 'client_id');
    }

    public function pageViews()
    {
        return $this->hasMany(PageView::class, 'client_id');
    }
}
