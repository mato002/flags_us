@extends('layouts.admin')

@section('title', $role->display_label.' | Access Control')
@section('header', $role->display_label)

@section('content')
    @php
        $assignedSet = $role->permissions->pluck('name')->flip();
        $canEditPerms = auth()->user()->can('roles.manage') && ($role->name !== 'super_admin' || auth()->user()->hasRole('super_admin'));
    @endphp

    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <nav class="mb-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#927f72] dark:text-[#c9bfb7]">System · Access control · {{ $role->display_label }}</nav>
            <h1 class="text-2xl font-black tracking-tight text-[#42221f] dark:text-white">{{ $role->display_label }}</h1>
            <p class="mt-2 max-w-2xl text-sm text-[#5c4944] dark:text-[#cbbfb6]">
                Inspect scope, collaborators, and the permission lattice for this access profile.
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.system.roles.matrix', ['highlight' => $role->id]) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-800 hover:bg-slate-50 dark:border-white/15 dark:bg-white/10 dark:text-white dark:hover:bg-white/15">
                Matrix view
            </a>
            <a href="{{ route('admin.system.roles.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 dark:border-white/20 dark:text-[#dfd5cd] dark:hover:bg-white/10">
                ← All roles
            </a>
            @can('roles.manage')
                @if ($role->name !== 'super_admin' || auth()->user()->hasRole('super_admin'))
                    <form method="POST" action="{{ route('admin.system.roles.duplicate', $role) }}" class="inline">@csrf
                        <button type="submit" class="rounded-lg border border-orange-400/60 bg-orange-500/10 px-4 py-2 text-xs font-bold text-orange-800 hover:bg-orange-500/20 dark:text-orange-100">Duplicate</button>
                    </form>
                @endif
                <a href="{{ route('admin.system.roles.edit', $role) }}" class="rounded-lg bg-[#8b1e1a] px-4 py-2 text-xs font-bold text-white hover:bg-[#f04a2a]">Edit profile</a>
                @if (! $role->is_system && $assignedUsers->isEmpty())
                    <form method="POST" action="{{ route('admin.system.roles.destroy', $role) }}" class="inline" onsubmit="return confirm('Remove this role permanently?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="rounded-lg border border-rose-400/70 px-4 py-2 text-xs font-bold text-rose-700 hover:bg-rose-500/10 dark:text-rose-300">Delete</button>
                    </form>
                @endif
            @endcan
        </div>
    </div>

    {{-- Role information --}}
    <section class="admin-glass-card mb-8 rounded-2xl border border-slate-200/90 p-6 dark:border-white/10 dark:bg-[#161212]/60">
        <h2 class="text-sm font-black uppercase tracking-[0.14em] text-[#927f72] dark:text-[#f7b396]">Role information</h2>
        <div class="mt-4 grid gap-6 lg:grid-cols-3">
            <div>
                <p class="text-[10px] font-bold uppercase text-slate-400">Display title</p>
                <p class="mt-1 text-lg font-bold text-slate-900 dark:text-white">{{ $role->display_label }}</p>
                <div class="mt-3 inline-flex gap-2">
                    @if ($role->is_system)
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold uppercase text-slate-600 dark:bg-white/10 dark:text-[#dfd5cd]">System baseline</span>
                    @else
                        <span class="rounded-full bg-orange-500/15 px-2 py-0.5 text-[10px] font-bold uppercase text-orange-700 dark:text-orange-200">Tailored profile</span>
                    @endif
                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase text-white shadow-sm ring-1 ring-black/10" style="background: {{ $role->resolvedBadgeColor() }};">Accent</span>
                </div>
            </div>
            <div class="lg:col-span-2">
                <p class="text-[10px] font-bold uppercase text-slate-400">Narrative</p>
                <p class="mt-1 text-sm leading-relaxed text-slate-700 dark:text-[#dfd5cd]">{{ $role->description ?: 'This profile does not yet include a stewardship narrative.' }}</p>
                <dl class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-4 dark:border-white/10 dark:bg-white/5">
                        <dt class="text-[10px] font-bold uppercase text-slate-500">Permissions wired</dt>
                        <dd class="mt-1 text-2xl font-black text-[#42221f] dark:text-white">{{ $role->permissions->count() }}</dd>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-4 dark:border-white/10 dark:bg-white/5">
                        <dt class="text-[10px] font-bold uppercase text-slate-500">People assigned</dt>
                        <dd class="mt-1 text-2xl font-black text-[#42221f] dark:text-white">{{ $assignedUsers->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    {{-- Permission matrix --}}
    <section class="mb-8">
        <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-end sm:justify-between">
            <div>
                <h2 class="text-sm font-black uppercase tracking-[0.14em] text-[#927f72] dark:text-[#f7b396]">Permission matrix</h2>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Capability columns summarise each grant; filtering is client-side so saving never drops hidden rows.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <label class="flex min-w-[12rem] flex-1 flex-col text-[10px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400 sm:max-w-xs">
                    Search permissions
                    <input type="search" id="perm-matrix-filter" autocomplete="off" placeholder="Filter by name…" class="mt-1 rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium normal-case dark:border-white/20 dark:bg-transparent dark:text-white">
                </label>
                @unless ($canEditPerms)
                    <p class="rounded-lg bg-slate-100 px-3 py-1.5 text-[11px] font-semibold text-slate-600 dark:bg-white/10 dark:text-[#cbbfb6]">Read-only view</p>
                @endunless
            </div>
        </div>

        @if ($canEditPerms)
            <form method="POST" action="{{ route('admin.system.roles.permissions', $role) }}" class="space-y-8" id="role-permissions-form">
                @csrf
                @method('PATCH')
        @endif

        @foreach ($modules as $moduleKey => $module)
            <div data-perm-matrix-module class="mb-6 overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm dark:border-white/10 dark:bg-[#161212]/80">
                <div class="border-b border-slate-100 bg-slate-50/90 px-4 py-3 dark:border-white/10 dark:bg-[#1a1515]">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $module['label'] }}</h3>
                </div>
                <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
                    <table class="min-w-[720px] w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-white text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-[#161212] dark:text-slate-400">
                                <th class="sticky left-0 z-10 min-w-[200px] bg-white px-4 py-3 dark:bg-[#161212]">Capability</th>
                                @foreach (['view' => 'View', 'create' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete', 'export' => 'Export', 'approve' => 'Approve'] as $k => $label)
                                    <th class="px-2 py-3 text-center">{{ $label }}</th>
                                @endforeach
                                <th class="min-w-[100px] px-4 py-3 text-center">Access</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($module['rows'] as $row)
                                <tr data-perm-matrix-row data-perm-search="{{ strtolower($row['label'].' '.$row['name']) }}" class="border-b border-slate-50 hover:bg-slate-50/80 dark:border-white/5 dark:hover:bg-white/5">
                                    <td class="sticky left-0 z-10 bg-white px-4 py-3 font-semibold text-slate-800 dark:bg-[#161212] dark:text-[#f2ebe6]">
                                        <span class="block">{{ $row['label'] }}</span>
                                        <span class="mt-0.5 block font-mono text-[10px] font-normal text-slate-400">{{ $row['name'] }}</span>
                                    </td>
                                    @foreach (['view', 'create', 'edit', 'delete', 'export', 'approve'] as $slot)
                                        @php $on = $row['matrix'][$slot] ?? false; @endphp
                                        <td class="px-2 py-3 text-center text-slate-500">
                                            @if ($on)
                                                <span class="inline-block h-2 w-2 rounded-full bg-[#f04a2a] shadow-[0_0_8px_rgba(240,74,42,0.45)]" title="In scope"></span>
                                            @else
                                                <span class="text-slate-300 dark:text-slate-600">—</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-3 text-center">
                                        @if ($canEditPerms)
                                            <label class="inline-flex cursor-pointer items-center gap-2">
                                                <input type="checkbox" name="permissions[]" value="{{ $row['name'] }}" class="h-4 w-4 rounded border-slate-400 text-[#8b1e1a] focus:ring-[#f04a2a]" @checked($assignedSet->has($row['name']))>
                                            </label>
                                        @else
                                            @if ($assignedSet->has($row['name']))
                                                <span class="text-emerald-600 dark:text-emerald-400">✓</span>
                                            @else
                                                <span class="text-slate-300">—</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        @if ($canEditPerms)
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="rounded-lg bg-[#8b1e1a] px-6 py-2.5 text-sm font-bold text-white shadow hover:bg-[#f04a2a]">Save permission changes</button>
                    <a href="{{ route('admin.system.roles.show', $role) }}" class="rounded-lg border border-slate-300 px-6 py-2.5 text-sm font-bold dark:border-white/20 dark:text-white">Cancel</a>
                </div>
            </form>
        @endif
    </section>

    {{-- Assigned users --}}
    <section class="admin-glass-card mb-8 rounded-2xl border border-slate-200/90 p-6 dark:border-white/10 dark:bg-[#161212]/60">
        <h2 class="text-sm font-black uppercase tracking-[0.14em] text-[#927f72] dark:text-[#f7b396]">Assigned users</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Directory members currently inheriting this role via the operations portal.</p>

        <div class="em-admin-table-scroll mt-6 rounded-xl border border-slate-100 dark:border-white/10 lg:mx-0 lg:px-0">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:bg-[#1f1a1a] dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Last session</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                    @forelse ($assignedUsers as $user)
                        @php
                            $initials = '';
                            $nameParts = preg_split('/\s+/u', trim((string) $user->name), -1, PREG_SPLIT_NO_EMPTY) ?: [];
                            foreach (array_slice($nameParts, 0, 2) as $part) {
                                $initials .= mb_strtoupper(mb_substr($part, 0, 1));
                            }
                            if ($initials === '') {
                                $initials = '?';
                            }
                            $lastTs = isset($sessions[$user->id]) ? (int) $sessions[$user->id]->last_activity : null;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-white/5">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-black text-white ring-2 ring-white dark:ring-black" style="background: {{ $role->resolvedBadgeColor() }};">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($user->email_verified_at)
                                    <span class="rounded-full bg-emerald-500/15 px-2 py-0.5 text-[11px] font-bold text-emerald-700 dark:text-emerald-300">Verified</span>
                                @else
                                    <span class="rounded-full bg-amber-500/15 px-2 py-0.5 text-[11px] font-bold text-amber-800 dark:text-amber-200">Pending verification</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-600 dark:text-[#cbbfb6]">
                                {{ $lastTs ? \Carbon\Carbon::createFromTimestamp($lastTs)->diffForHumans() : 'No recent session' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                @can('users.manage')
                                    <a href="{{ route('admin.system.users.edit', $user) }}" class="text-xs font-bold text-[#8b1e1a] underline dark:text-[#f7b396]">Adjust roles</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-[#cbbfb6]">No teammates carry this profile yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Audit --}}
    <section class="admin-glass-card rounded-2xl border border-slate-200/90 p-6 dark:border-white/10 dark:bg-[#161212]/60">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-sm font-black uppercase tracking-[0.14em] text-[#927f72] dark:text-[#f7b396]">Recent access activity</h2>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Chronological feed for audits tied specifically to {{ $role->display_label }}.</p>
            </div>
            <a href="{{ route('admin.system.roles.activity') }}" class="text-xs font-bold text-[#8b1e1a] underline dark:text-[#f7b396]">View full log →</a>
        </div>
        <ul class="mt-6 space-y-3">
            @forelse ($recentActivity as $log)
                <li class="flex flex-wrap gap-3 rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3 text-xs dark:border-white/10 dark:bg-white/5">
                    <span class="font-bold text-[#42221f] dark:text-white">{{ \Illuminate\Support\Str::headline(str_replace('_', ' ', $log->action)) }}</span>
                    <span class="text-slate-500">{{ $log->created_at?->format('M j, Y · H:i') }}</span>
                    @if ($log->actor)
                        <span class="text-slate-600 dark:text-[#cbbfb6]">Actor: {{ $log->actor->name }}</span>
                    @endif
                </li>
            @empty
                <li class="rounded-xl border border-dashed border-slate-200 px-4 py-8 text-center text-sm text-slate-500 dark:border-white/10 dark:text-[#a89f98]">No recorded changes yet for this role.</li>
            @endforelse
        </ul>
    </section>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const input = document.getElementById('perm-matrix-filter');
                if (!input) return;
                const run = () => {
                    const q = input.value.trim().toLowerCase();
                    document.querySelectorAll('[data-perm-matrix-row]').forEach((row) => {
                        const blob = (row.getAttribute('data-perm-search') || '').toLowerCase();
                        row.classList.toggle('hidden', q !== '' && !blob.includes(q));
                    });
                    document.querySelectorAll('[data-perm-matrix-module]').forEach((mod) => {
                        const any = mod.querySelector('[data-perm-matrix-row]:not(.hidden)');
                        mod.classList.toggle('hidden', q !== '' && !any);
                    });
                };
                input.addEventListener('input', run);
            });
        </script>
    @endpush
@endsection
