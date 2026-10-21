@extends('layouts.admin')

@section('title', 'Access Activity | Effective Media Ops')
@section('header', 'Audit trail')

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <nav class="mb-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#927f72] dark:text-[#c9bfb7]">System · Access control · Audit</nav>
            <h1 class="text-2xl font-black tracking-tight text-[#42221f] dark:text-white">Access change history</h1>
            <p class="mt-2 max-w-2xl text-sm text-[#5c4944] dark:text-[#cbbfb6]">
                Stewardship ledger for roles, duplication events, permission syncs, and removals across the Effective Media Operations Portal.
            </p>
        </div>
        <a href="{{ route('admin.system.roles.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-bold hover:bg-white dark:border-white/20 dark:text-white">← Access control home</a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm dark:border-white/10 dark:bg-[#161212]/85">
        <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
        <table class="min-w-full divide-y divide-slate-100 text-sm dark:divide-white/10">
            <thead class="bg-slate-50 text-left text-[10px] font-bold uppercase tracking-wide text-slate-500 dark:bg-[#1f1515] dark:text-[#dfd5cd]">
                <tr>
                    <th class="px-4 py-3">Event</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Actor</th>
                    <th class="px-4 py-3">Context</th>
                    <th class="px-4 py-3">When</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                @forelse ($logs as $log)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-white/5">
                        <td class="px-4 py-4 font-semibold text-slate-900 dark:text-white">{{ Str::headline(str_replace('_', ' ', $log->action)) }}</td>
                        <td class="px-4 py-4">
                            @if ($log->role)
                                <a href="{{ route('admin.system.roles.show', $log->role) }}" class="font-semibold text-[#8b1e1a] underline dark:text-[#f7b396]">{{ $log->role->display_label }}</a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-slate-600 dark:text-[#cbbfb6]">{{ $log->actor?->name ?? 'System' }}</td>
                        <td class="px-4 py-4 font-mono text-[11px] text-slate-500 dark:text-[#92857c]">
                            {{ $log->ip_address ?: '—' }}
                            @if (! empty($log->meta))
                                <details class="mt-1 cursor-pointer"><summary class="select-none font-sans font-semibold">Details</summary><pre class="mt-2 max-h-32 overflow-auto rounded bg-slate-100 p-2 text-[10px] dark:bg-black/40">@json($log->meta, JSON_PRETTY_PRINT)</pre></details>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-4 text-slate-600 dark:text-[#cbbfb6]">{{ $log->created_at?->format('M j, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center text-sm text-slate-500 dark:text-[#cbbfb6]">
                            Governance events will accumulate here whenever roles refresh.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-6">{{ $logs->links() }}</div>
@endsection
