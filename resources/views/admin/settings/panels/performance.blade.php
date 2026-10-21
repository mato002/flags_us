@php($perf = $groups['performance'])
@php($fc = 'mt-1 w-full rounded-lg border border-[#d7c4b5] bg-white px-3 py-2 text-sm text-[#2a221f] dark:border-white/15 dark:bg-[#1a1616] dark:text-[#f5eded]')

<section id="performance" class="admin-glass-card scroll-mt-24 p-5 sm:p-6">
    <div class="border-b border-[#ead8c9] pb-4 dark:border-white/10">
        <h2 class="text-lg font-black text-[#221211] dark:text-white">Performance</h2>
        <p class="mt-1 max-w-2xl text-sm text-[#6b5d55] dark:text-[#c9bfb7]">Edge caching hints, media delivery defaults, and Laravel maintenance utilities.</p>
    </div>

    <form action="{{ route('admin.system.settings.update', 'performance') }}" method="post" class="mt-6 grid gap-6 lg:grid-cols-2">
        @csrf
        @method('PUT')

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            <input type="hidden" name="image_optimization" value="0">
            <input type="checkbox" name="image_optimization" value="1" @checked(old('image_optimization', $perf['image_optimization'] ?? true)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
            Prefer optimized derivatives when generating thumbnails
        </label>

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            <input type="hidden" name="lazy_loading" value="0">
            <input type="checkbox" name="lazy_loading" value="1" @checked(old('lazy_loading', $perf['lazy_loading'] ?? true)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
            Lazy-loading for non-critical imagery
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Public page cache hint (seconds, 0 disables advisory TTL)
            <input type="number" min="0" max="86400" name="cache_public_pages_seconds" value="{{ old('cache_public_pages_seconds', $perf['cache_public_pages_seconds'] ?? 60) }}" class="{{ $fc }}">
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Notes
            <textarea name="performance_notes" rows="2" class="{{ $fc }}">{{ old('performance_notes', $perf['performance_notes'] ?? '') }}</textarea>
        </label>

        <div class="lg:col-span-2 flex flex-wrap gap-3">
            <button type="submit" class="inline-flex min-h-[42px] items-center justify-center rounded-lg bg-[#8b1e1a] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#f04a2a]">Save performance</button>
        </div>
    </form>

    <div class="mt-8 grid gap-4 border-t border-[#ead8c9] pt-6 dark:border-white/10 lg:grid-cols-3">
        <form action="{{ route('admin.system.settings.actions', 'clear-cache') }}" method="post" class="rounded-xl border border-[#dbc6b8] bg-[#fffaf6] p-4 dark:border-white/10 dark:bg-white/5">
            @csrf
            <p class="text-sm font-semibold text-[#3f2f2d] dark:text-[#f4ece6]">Clear cache</p>
            <p class="mt-1 text-xs text-[#71655e] dark:text-[#c9bfb7]">Flushes framework + view caches and portal memoization.</p>
            <button type="submit" class="mt-3 w-full rounded-lg border border-[#8b1e1a] px-3 py-2 text-xs font-bold text-[#8b1e1a] hover:bg-[#8b1e1a] hover:text-white dark:border-[#f7b396] dark:text-[#f7b396]">Run</button>
        </form>

        <form action="{{ route('admin.system.settings.actions', 'optimize') }}" method="post" class="rounded-xl border border-[#dbc6b8] bg-[#fffaf6] p-4 dark:border-white/10 dark:bg-white/5">
            @csrf
            <p class="text-sm font-semibold text-[#3f2f2d] dark:text-[#f4ece6]">Optimize</p>
            <p class="mt-1 text-xs text-[#71655e] dark:text-[#c9bfb7]">Runs <code class="rounded bg-black/5 px-1 py-0.5 text-[10px] dark:bg-white/10">php artisan optimize</code>.</p>
            <button type="submit" class="mt-3 w-full rounded-lg border border-[#8b1e1a] px-3 py-2 text-xs font-bold text-[#8b1e1a] hover:bg-[#8b1e1a] hover:text-white dark:border-[#f7b396] dark:text-[#f7b396]">Run</button>
        </form>

        <form action="{{ route('admin.system.settings.actions', 'manual-backup') }}" method="post" class="rounded-xl border border-[#dbc6b8] bg-[#fffaf6] p-4 dark:border-white/10 dark:bg-white/5">
            @csrf
            <p class="text-sm font-semibold text-[#3f2f2d] dark:text-[#f4ece6]">Record backup</p>
            <p class="mt-1 text-xs text-[#71655e] dark:text-[#c9bfb7]">Logs a manual backup timestamp. Wire Laravel Backup for real archives.</p>
            <button type="submit" class="mt-3 w-full rounded-lg border border-[#8b1e1a] px-3 py-2 text-xs font-bold text-[#8b1e1a] hover:bg-[#8b1e1a] hover:text-white dark:border-[#f7b396] dark:text-[#f7b396]">Run</button>
        </form>
    </div>
</section>
