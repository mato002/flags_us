<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'badge_color',
        'is_system',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
        ];
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(RoleAccessAuditLog::class)->orderByDesc('created_at');
    }

    public function getDisplayLabelAttribute(): string
    {
        return $this->display_name ?: str($this->name)->replace('_', ' ')->title()->value();
    }

    public function resolvedBadgeColor(): string
    {
        if ($this->badge_color) {
            return $this->badge_color;
        }

        $palette = config('access-control.role_palette', []);

        return $palette[$this->name] ?? '#64748b';
    }

    /**
     * @return array<int, string>
     */
    public function accessibleModuleLabels(): array
    {
        $assigned = $this->relationLoaded('permissions')
            ? $this->permissions->pluck('name')
            : $this->permissions()->pluck('name');
        $flip = $assigned->flip();
        $labels = [];

        foreach (config('access-control.modules', []) as $key => $block) {
            foreach (array_keys($block['permissions'] ?? []) as $perm) {
                if ($flip->has($perm)) {
                    $labels[$key] = $block['label'];
                    break;
                }
            }
        }

        return array_values(array_unique($labels));
    }
}
