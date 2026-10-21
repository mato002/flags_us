<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'format_key',
        'summary',
        'public_sections',
        'image_path',
        'icon',
        'is_active',
        'is_visible_public',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'bool',
        'is_visible_public' => 'bool',
        'public_sections' => 'array',
    ];
}
