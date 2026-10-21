<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalSettingGroup extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'data',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }
}
