<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleAccessAuditLog;
use App\Models\User;
use App\Services\RoleAccessAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RoleAccessController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()
            ->with(['permissions'])
            ->withCount(['permissions', 'users'])
            ->orderByRaw('CASE WHEN name = ? THEN 0 ELSE 1 END', ['super_admin'])
            ->orderBy('name')
            ->get();

        return view('admin.system.roles.index', [
            'roles' => $roles,
        ]);
    }

    public function matrix(Request $request): View
    {
        $matrix = $this->buildMatrixDataset();
        $roles = Role::query()->with('permissions')->orderBy('name')->get();

        return view('admin.system.roles.matrix', [
            'modules' => $matrix,
            'roles' => $roles,
            'highlightRoleId' => $request->query('highlight'),
        ]);
    }

    public function activity(): View
    {
        $logs = RoleAccessAuditLog::query()
            ->with(['actor', 'role'])
            ->orderByDesc('created_at')
            ->paginate(40);

        return view('admin.system.roles.activity', ['logs' => $logs]);
    }

    public function export(): StreamedResponse
    {
        $filename = 'effective-media-access-report-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function (): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Role key', 'Display name', 'System role', 'Users assigned', 'Permission count', 'Permissions']);

            Role::query()
                ->with('permissions')
                ->withCount('users')
                ->orderBy('name')
                ->each(function (Role $role) use ($out): void {
                    $perms = $role->permissions->pluck('name')->sort()->values()->implode('; ');
                    fputcsv($out, [
                        $role->name,
                        $role->display_label,
                        $role->is_system ? 'yes' : 'no',
                        $role->users_count,
                        $role->permissions->count(),
                        $perms,
                    ]);
                });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function create(): View
    {
        return view('admin.system.roles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', 'regex:/^[a-z][a-z0-9_]*$/', 'unique:roles,name'],
            'display_name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:1000'],
            'badge_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $role = Role::query()->create([
            'name' => $validated['name'],
            'guard_name' => 'web',
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'badge_color' => $validated['badge_color'] ?? null,
            'is_system' => false,
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        RoleAccessAuditLogger::log($request->user(), $role, 'role_created', [
            'name' => $role->name,
        ]);

        return redirect()
            ->route('admin.system.roles.show', $role)
            ->with('status', 'Role created. Assign permissions below.');
    }

    public function show(Role $role): View
    {
        $modules = $this->buildMatrixDataset();

        $assignedUsers = User::query()
            ->whereHas('roles', fn ($q) => $q->where('roles.id', $role->id))
            ->orderBy('name')
            ->get();

        $sessions = collect();
        if ($assignedUsers->isNotEmpty()) {
            $sessions = DB::table('sessions')
                ->whereIn('user_id', $assignedUsers->pluck('id'))
                ->select('user_id', DB::raw('MAX(last_activity) as last_activity'))
                ->groupBy('user_id')
                ->get()
                ->keyBy('user_id');
        }

        $activity = $role->auditLogs()->limit(25)->get();

        return view('admin.system.roles.show', [
            'role' => $role->load('permissions'),
            'modules' => $modules,
            'assignedUsers' => $assignedUsers,
            'sessions' => $sessions,
            'recentActivity' => $activity,
        ]);
    }

    public function edit(Role $role): View
    {
        return view('admin.system.roles.edit', ['role' => $role]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        if ($role->name === 'super_admin' && ! $request->user()->hasRole('super_admin')) {
            abort(403);
        }

        $rules = [
            'display_name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:1000'],
            'badge_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
        if (! $role->is_system) {
            $rules['name'] = ['required', 'string', 'max:120', 'regex:/^[a-z][a-z0-9_]*$/', 'unique:roles,name,'.$role->id];
        }
        $validated = $request->validate($rules);

        if (! $role->is_system) {
            $role->name = $validated['name'];
        }

        $role->display_name = $validated['display_name'];
        $role->description = $validated['description'] ?? null;
        $role->badge_color = $validated['badge_color'] ?? null;
        $role->save();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        RoleAccessAuditLogger::log($request->user(), $role, 'role_updated', [
            'updated' => array_keys($validated),
        ]);

        return redirect()->route('admin.system.roles.show', $role)->with('status', 'Role updated.');
    }

    public function updatePermissions(Request $request, Role $role): RedirectResponse
    {
        if ($role->name === 'super_admin' && ! $request->user()->hasRole('super_admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $incoming = collect($validated['permissions'] ?? [])->unique()->values()->all();

        if ($role->name === 'super_admin' && $incoming === []) {
            return redirect()->back()->with('flash_warning', __('Super Administrator profiles must retain at least one active grant.'));
        }
        $before = $role->permissions->pluck('name')->sort()->values()->all();

        $role->syncPermissions($incoming);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $after = $role->permissions()->pluck('name')->sort()->values()->all();

        RoleAccessAuditLogger::log($request->user(), $role, 'permissions_synced', [
            'before_count' => count($before),
            'after_count' => count($after),
            'added' => array_values(array_diff($after, $before)),
            'removed' => array_values(array_diff($before, $after)),
        ]);

        return redirect()->route('admin.system.roles.show', $role)->with('status', 'Permissions saved for '.$role->display_label.'.');
    }

    public function duplicate(Request $request, Role $role): RedirectResponse
    {
        $baseName = $role->name.'_copy';
        $name = $baseName;
        $i = 1;
        while (Role::query()->where('name', $name)->exists()) {
            $name = $baseName.'_'.$i;
            $i++;
        }

        $copy = Role::query()->create([
            'name' => $name,
            'guard_name' => $role->guard_name,
            'display_name' => $role->display_label.' (Copy)',
            'description' => $role->description,
            'badge_color' => $role->badge_color,
            'is_system' => false,
        ]);

        $copy->syncPermissions($role->permissions->pluck('name')->all());
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        RoleAccessAuditLogger::log($request->user(), $copy, 'role_duplicated', [
            'from_role_id' => $role->id,
            'from_role' => $role->name,
        ]);

        return redirect()->route('admin.system.roles.show', $copy)->with('status', 'Role duplicated.');
    }

    public function destroy(Request $request, Role $role): RedirectResponse
    {
        if ($role->is_system) {
            return redirect()->back()->with('flash_warning', __('System roles are protected and cannot be deleted.'));
        }

        if ($role->users()->exists()) {
            return redirect()->back()->with('flash_warning', __('Assign those users to another role before deleting this profile.'));
        }

        RoleAccessAuditLogger::log($request->user(), $role, 'role_deleted', [
            'name' => $role->name,
        ]);

        $role->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.system.roles.index')->with('status', 'Role removed.');
    }

    /**
     * @return array<string, array{label: string, rows: array<int, array<string, mixed>>}>
     */
    private function buildMatrixDataset(): array
    {
        $modules = [];
        foreach (config('access-control.modules', []) as $key => $block) {
            $rows = [];
            foreach ($block['permissions'] as $permissionName => $meta) {
                $rows[] = [
                    'name' => $permissionName,
                    'label' => $meta['label'] ?? $permissionName,
                    'matrix' => $meta['matrix'] ?? [],
                ];
            }
            $modules[$key] = [
                'label' => $block['label'],
                'rows' => $rows,
            ];
        }

        $known = collect($modules)->flatMap(fn ($m) => collect($m['rows'])->pluck('name'))->all();
        $orphans = Permission::query()
            ->whereNotIn('name', $known)
            ->orderBy('name')
            ->pluck('name');

        if ($orphans->isNotEmpty()) {
            $rows = [];
            foreach ($orphans as $name) {
                $rows[] = [
                    'name' => $name,
                    'label' => Str::headline(str_replace(['.', '_'], ' ', $name)),
                    'matrix' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false, 'export' => false, 'approve' => false],
                ];
            }
            $modules['other'] = [
                'label' => 'Additional access',
                'rows' => $rows,
            ];
        }

        return $modules;
    }
}
