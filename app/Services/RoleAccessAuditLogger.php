<?php

namespace App\Services;

use App\Models\Role;
use App\Models\RoleAccessAuditLog;
use App\Models\User;

class RoleAccessAuditLogger
{
    /**
     * @param  array<string, mixed>  $meta
     */
    public static function log(?User $actor, Role $role, string $action, array $meta = []): void
    {
        RoleAccessAuditLog::query()->create([
            'user_id' => $actor?->id,
            'role_id' => $role->id,
            'action' => $action,
            'meta' => empty($meta) ? null : $meta,
            'ip_address' => request()?->ip(),
            'created_at' => now(),
        ]);
    }
}
