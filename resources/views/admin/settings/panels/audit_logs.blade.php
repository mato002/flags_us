<section id="audit_logs" class="admin-glass-card scroll-mt-24 p-5 sm:p-6">
    <div class="border-b border-[#ead8c9] pb-4 dark:border-white/10">
        <h2 class="text-lg font-black text-[#221211] dark:text-white">Audit logs</h2>
        <p class="mt-1 max-w-2xl text-sm text-[#6b5d55] dark:text-[#c9bfb7]">Recent configuration activity (write actions only). Extend to exports/uploads as features land.</p>
    </div>

    <div class="em-admin-table-scroll mt-5 lg:mx-0 lg:px-0">
        <table class="min-w-full border-separate border-spacing-0 text-left text-sm">
            <thead class="text-xs font-bold uppercase tracking-wide text-[#8b1e1a] dark:text-[#f7b396]">
                <tr>
                    <th class="border-b border-[#ead8c9] px-3 py-2 dark:border-white/10">When</th>
                    <th class="border-b border-[#ead8c9] px-3 py-2 dark:border-white/10">User</th>
                    <th class="border-b border-[#ead8c9] px-3 py-2 dark:border-white/10">Action</th>
                    <th class="border-b border-[#ead8c9] px-3 py-2 dark:border-white/10">Group</th>
                    <th class="border-b border-[#ead8c9] px-3 py-2 dark:border-white/10">Metadata</th>
                </tr>
            </thead>
            <tbody class="text-[#3f2f2d] dark:text-[#e8dbd4]">
                @forelse ($audits as $audit)
                    <tr>
                        <td class="border-b border-[#f0e3d9] px-3 py-2 text-xs dark:border-white/5">{{ $audit->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</td>
                        <td class="border-b border-[#f0e3d9] px-3 py-2 text-xs dark:border-white/5">{{ $audit->user?->email ?? 'System' }}</td>
                        <td class="border-b border-[#f0e3d9] px-3 py-2 text-xs font-semibold dark:border-white/5">{{ $audit->action }}</td>
                        <td class="border-b border-[#f0e3d9] px-3 py-2 text-xs dark:border-white/5">{{ $audit->slug ?? '—' }}</td>
                        <td class="border-b border-[#f0e3d9] px-3 py-2 text-xs text-[#6b5d55] dark:border-white/5">
                            @if (is_array($audit->metadata))
                                <code class="whitespace-pre-wrap break-all rounded bg-black/5 px-1 py-0.5 text-[10px] dark:bg-white/10">{{ json_encode($audit->metadata, JSON_UNESCAPED_SLASHES) }}</code>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-3 py-6 text-center text-sm text-[#7a665e] dark:text-[#b5a79d]">No audit entries yet. Saving any settings section will appear here.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
