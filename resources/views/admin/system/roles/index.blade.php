@extends('layouts.admin')

@section('title', 'Access Control & Roles | Effective Media Ops')
@section('header', 'Access control')

@section('content')
    <div class="mb-8">
        <nav class="mb-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#927f72] dark:text-[#c9bfb7]" aria-label="Breadcrumb">System · Access control</nav>
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-[#42221f] dark:text-white sm:text-[1.75rem]">Access Control & Roles</h1>
                <p class="mt-2 max-w-2xl text-sm text-[#5c4944] dark:text-[#cbbfb6]">
                    Manage operational access levels, permissions, and staff responsibilities across the platform.
                </p>
            </div>
            <div class="flex flex-shrink-0 flex-wrap gap-2">
                @can('roles.manage')
                    <a href="{{ route('admin.system.roles.create') }}" class="inline-flex items-center justify-center rounded-lg bg-[#8b1e1a] px-4 py-2.5 text-sm font-bold text-white shadow-md transition hover:bg-[#f04a2a] hover:shadow-lg">Create Role</a>
                @endcan
                <a href="{{ route('admin.system.roles.matrix') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-800 transition hover:border-[#f04a2a]/50 hover:bg-slate-50 dark:border-white/15 dark:bg-white/10 dark:text-white dark:hover:bg-white/15">
                    Permission Matrix
                </a>
                <a href="{{ route('admin.system.roles.export') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-800 transition hover:border-[#f04a2a]/50 hover:bg-slate-50 dark:border-white/15 dark:bg-white/10 dark:text-white dark:hover:bg-white/15">
                    Export Access Report
                </a>
                <a href="{{ route('admin.system.roles.activity') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-800 transition hover:border-[#f04a2a]/50 hover:bg-slate-50 dark:border-white/15 dark:bg-white/10 dark:text-white dark:hover:bg-white/15">
                    View Activity Logs
                </a>
            </div>
        </div>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($roles as $role)
            @php
                $color = $role->resolvedBadgeColor();
                $modules = $role->accessibleModuleLabels();
            @endphp
            <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm transition hover:border-[#f04a2a]/35 hover:shadow-xl dark:border-white/10 dark:bg-[#161212]/90 dark:hover:border-[#f04a2a]/40">
                <div class="relative px-5 pb-4 pt-5">
                    <div class="pointer-events-none absolute inset-x-0 top-0 h-1.5 rounded-t-2xl" style="background: linear-gradient(90deg, {{ $color }}, #f97316 );"></div>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $role->display_label }}</h2>
                            <p class="mt-1 font-mono text-[11px] text-slate-400 dark:text-slate-500">{{ $role->name }}</p>
                        </div>
                        @if ($role->is_system)
                            <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-slate-600 ring-1 ring-slate-200 dark:bg-white/10 dark:text-[#dfd5cd] dark:ring-white/10">System</span>
                        @else
                            <span class="shrink-0 rounded-full bg-orange-500/15 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-[#ea580c] ring-1 ring-orange-400/40">Custom</span>
                        @endif
                    </div>
                    <p class="mt-4 line-clamp-3 text-sm leading-relaxed text-slate-600 dark:text-[#b8aba2]">{{ $role->description ?: 'No description configured for this access profile.' }}</p>
                    <dl class="mt-5 grid grid-cols-2 gap-4 border-t border-slate-100 pt-4 dark:border-white/10">
                        <div>
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Permissions</dt>
                            <dd class="mt-1 text-xl font-black text-[#42221f] dark:text-white">{{ $role->permissions_count }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Users</dt>
                            <dd class="mt-1 text-xl font-black text-[#42221f] dark:text-white">{{ $role->users_count }}</dd>
                        </div>
                    </dl>
                    <div class="mt-4">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Accessible modules</p>
                        @if (count($modules))
                            <ul class="mt-2 flex flex-wrap gap-1.5">
                                @foreach ($modules as $lbl)
                                    <li class="rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700 dark:bg-white/10 dark:text-[#dfd5cd]">{{ $lbl }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mt-2 text-xs text-slate-500 italic dark:text-slate-400">No module access assigned.</p>
                        @endif
                    </div>
                </div>
                <footer class="mt-auto flex flex-wrap gap-2 border-t border-slate-100 px-5 py-4 dark:border-white/10">
                    <a href="{{ route('admin.system.roles.show', $role) }}" class="inline-flex flex-1 items-center justify-center rounded-lg border border-slate-200 px-3 py-2 text-center text-xs font-bold text-slate-800 transition group-hover:border-[#8b1e1a]/40 group-hover:text-[#8b1e1a] dark:border-white/15 dark:text-white dark:group-hover:border-[#f04a2a]/50 dark:group-hover:text-[#f7b396] sm:flex-none">View permissions</a>
                    @can('roles.manage')
                        <a href="{{ route('admin.system.roles.edit', $role) }}" class="inline-flex flex-1 items-center justify-center rounded-lg bg-slate-900 px-3 py-2 text-center text-xs font-bold text-white transition hover:bg-[#8b1e1a] dark:bg-[#f04a2a] dark:hover:bg-[#ff6b4a] sm:flex-none">Edit</a>
                    @endcan
                </footer>
            </article>
        @endforeach
    </div>
@endsection
