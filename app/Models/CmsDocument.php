<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsDocument extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'requires_lead_capture',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'requires_lead_capture' => 'bool',
            'is_active' => 'bool',
        ];
    }

    public function getPublicUrlAttribute(): string
    {
        $path = $this->file_path;
        if (is_string($path) && str_starts_with($path, 'http')) {
            return $path;
        }

        return asset('storage/'.ltrim((string) $path, '/'));
    }
}
