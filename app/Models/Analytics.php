<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    use HasFactory,SoftDeletes,HasApiTokens;

    /**
     * The table associated with the model (optional if same as class name in plural form).
     */
    protected $table = 'analytics';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'page_id',
        'views',
        'unique_visitors',
        'avg_time_spent',
        'bounce_rate',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'views' => 'integer',
        'unique_visitors' => 'integer',
        'avg_time_spent' => 'float',
        'bounce_rate' => 'float',
    ];

    /**
     * Define relationship: An analytics record belongs to a page.
     */
    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    /**
     * Accessor for formatted bounce rate (optional convenience function).
     */
    public function getFormattedBounceRateAttribute(): string
    {
        return number_format($this->bounce_rate ?? 0, 2) . '%';
    }

    /**
     * Accessor for formatted average time spent (optional).
     */
    public function getFormattedAvgTimeSpentAttribute(): string
    {
        return ($this->avg_time_spent ?? 0) . 's';
    }
}
