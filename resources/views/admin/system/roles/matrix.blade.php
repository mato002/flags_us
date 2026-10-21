@extends('layouts.admin')

@section('title', 'Permission Matrix | Access Control')
@section('header', 'Matrix')

@section('content')
    <div class="mb-6">
        <nav class="mb-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#927f72] dark:text-[#c9bfb7]">System · Access control · Matrix</nav>
        <h1 class="text-2xl font-black tracking-tight text-[#42221f] dark:text-white">Cross-role permission matrix</h1>
        <p class="mt-2 max-w-3xl text-sm text-[#5c4944] dark:text-[#cbbfb6]">
            Compare how operational roles align across modules. Highlights help orient large permission sets; adjust grants from each role’s workspace.
        </p>
    </div>

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-end sm:justify-between">
        <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.system.roles.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-bold hover:bg-slate-50 dark:border-white/20 dark:text-white dark:hover:bg-white/10">← Access control home</a>
        @can('roles.manage')
            <a href="{{ route('admin.system.roles.create') }}" class="rounded-lg bg-[#8b1e1a] px-4 py-2 text-xs font-bold text-white hover:bg-[#f04a2a]">Create Role</a>
        @endcan
        <a href="{{ route('admin.system.roles.export') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-bold dark:border-white/20 dark:text-white">Export report</a>
        </div>
        <label class="flex min-w-[12rem] max-w-xs flex-col text-[10px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">
            Search capabilities
            <input type="search" id="global-matrix-filter" autocomplete="off" placeholder="Narrow rows…" class="mt-1 rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium normal-case dark:border-white/20 dark:bg-transparent dark:text-white">
        </label>
    </div>

    @php
        $slots = ['view', 'create', 'edit', 'delete', 'export', 'approve'];
    @endphp

    @foreach ($modules as $moduleKey => $module)
        <section data-global-matrix-module class="mb-8 overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm dark:border-white/10 dark:bg-[#161212]/80">
            <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-transparent px-4 py-4 dark:border-white/10 dark:from-[#1f1a1a] dark:to-transparent">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $module['label'] }}</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[980px] w-full border-collapse text-left text-[11px]">
                    <thead>
                        <tr class="border-b border-slate-100 bg-white text-[10px] font-bold uppercase tracking-wide text-slate-500 dark:border-white/10 dark:bg-[#161212] dark:text-slate-400">
                            <th class="sticky left-0 z-20 min-w-[220px] border-r border-slate-100 bg-white px-3 py-3 dark:border-white/10 dark:bg-[#161212]">Capability</th>
                            @foreach ($slots as $s)
                                <th class="px-1 py-3 text-center font-semibold">{{ strtoupper(substr($s, 0, 1)).substr($s, 1) }}</th>
                            @endforeach
                            @foreach ($roles as $role)
                                @php($hl = (int) request('highlight') === (int) $role->id)
                                <th class="{{ $hl ? 'bg-[#f04a2a]/10 ring-2 ring-[#f04a2a]/35' : '' }} min-w-[120px] border-l border-slate-100 px-2 py-3 text-center dark:border-white/10">
                                    <a href="{{ route('admin.system.roles.show', $role) }}" class="block font-bold text-[#42221f] underline-offset-4 hover:underline dark:text-white">{{ Str::limit($role->display_label, 14) }}</a>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($module['rows'] as $row)
                            <tr data-global-matrix-row data-global-matrix-search="{{ strtolower($row['label'].' '.$row['name']) }}" class="border-t border-slate-50 hover:bg-slate-50/80 dark:border-white/5 dark:hover:bg-white/5">
                                <td class="sticky left-0 z-10 border-r border-slate-100 bg-white px-3 py-2 align-top shadow-[2px_0_8px_rgba(15,23,42,0.04)] dark:border-white/10 dark:bg-[#161212] dark:shadow-none">
                                    <span class="block font-semibold text-slate-800 dark:text-[#f2ebe6]">{{ $row['label'] }}</span>
                                    <span class="font-mono text-[9px] text-slate-400">{{ $row['name'] }}</span>
                                </td>
                                @foreach ($slots as $s)
                                    <td class="px-1 py-2 text-center">
                                        @if (! empty($row['matrix'][$s]))
                                            <span class="inline-block h-2 w-2 rounded-full bg-gradient-to-br from-[#8b1e1a] to-[#f04a2a]"></span>
                                        @else
                                            <span class="text-slate-300 dark:text-slate-600">·</span>
                                        @endif
                                    </td>
                                @endforeach
                                @foreach ($roles as $role)
                                    @php($perm = $role->permissions->contains('name', $row['name']))
                                    @php($hl = (int) request('highlight') === (int) $role->id)
                                    <td class="{{ $hl ? 'bg-[#f04a2a]/5' : '' }} border-l border-slate-100 px-2 py-2 text-center align-middle dark:border-white/10">
                                        @if ($perm)
                                            <span class="rounded-md bg-emerald-500/20 px-1.5 py-0.5 text-[11px] font-black text-emerald-800 dark:text-emerald-200">✓</span>
                                        @else
                                            <span class="text-slate-300 dark:text-slate-600">—</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="border-t border-slate-100 px-4 py-2 text-[10px] text-slate-400 dark:border-white/10 dark:text-[#92857c]">
                Capability columns summarise coverage; granting still happens against precise permission keys listed under each capability.
            </p>
        </section>
    @endforeach

    @unless (auth()->user()->can('roles.manage'))
        <p class="text-xs text-slate-500 dark:text-[#a89f98]">Your profile can inspect this lattice. Request an administrator uplift if edits are needed.</p>
    @endunless

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const input = document.getElementById('global-matrix-filter');
                if (!input) return;
                input.addEventListener('input', () => {
                    const q = input.value.trim().toLowerCase();
                    document.querySelectorAll('[data-global-matrix-row]').forEach((row) => {
                        const blob = (row.getAttribute('data-global-matrix-search') || '').toLowerCase();
                        row.classList.toggle('hidden', q !== '' && !blob.includes(q));
                    });
                    document.querySelectorAll('[data-global-matrix-module]').forEach((mod) => {
                        const any = mod.querySelector('[data-global-matrix-row]:not(.hidden)');
                        mod.classList.toggle('hidden', q !== '' && !any);
                    });
                });
            });
        </script>
    @endpush
@endsection
