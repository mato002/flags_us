<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioItem extends Model
{
    /**
     * @var array<int, string>
     */
    protected $appends = ['image_url'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'client_name',
        'category',
        'campaign_location',
        'media_type',
        'description',
        'image_path',
        'gallery_paths',
        'featured',
        'is_published',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'featured' => 'bool',
        'is_published' => 'bool',
        'gallery_paths' => 'array',
    ];

    public function getImageUrlAttribute(): ?string
    {
        $path = $this->image_path;

        if (! is_string($path) || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }
}
