<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'description',
        'image',
        'price',
        'quantity',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get image URL with automatic fallback.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            return asset('storage/' . $this->image);
        }

        // SVG fallback placeholder (works offline and online)
        return "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='400' height='300' viewBox='0 0 400 300'><rect fill='%23f1f3f5' width='400' height='300'/><text fill='%23adb5bd' font-family='sans-serif' font-size='20' font-weight='bold' x='50%' y='50%' text-anchor='middle' dominant-baseline='middle'>📦 No Picture</text></svg>";
    }
}