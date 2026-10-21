<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoverageSite extends Model
{
    protected $fillable = [
        'county',
        'town',
        'site_name',
        'poles_count',
        'media_type',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'bool',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }
}
