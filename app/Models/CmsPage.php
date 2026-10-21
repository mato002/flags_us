<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'body',
        'meta_description',
        'og_image',
        'robots',
        'canonical_url',
        'is_published',
        'sections',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sections' => 'array',
        ];
    }
}
