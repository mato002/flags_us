<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardInventoryItem extends Model
{
    protected $fillable = [
        'reference_code',
        'location',
        'size',
        'illumination',
        'traffic_notes',
        'price',
        'availability_status',
        'maintenance_status',
        'photo_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'bool',
            'price' => 'decimal:2',
        ];
    }

    public function getPhotoUrlAttribute(): ?string
    {
        $path = $this->photo_path;
        if (! is_string($path) || $path === '') {
            return null;
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}
