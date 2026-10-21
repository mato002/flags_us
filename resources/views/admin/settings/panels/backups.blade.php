@php($b = $groups['backups'])
@php($fc = 'mt-1 w-full rounded-lg border border-[#d7c4b5] bg-white px-3 py-2 text-sm text-[#2a221f] dark:border-white/15 dark:bg-[#1a1616] dark:text-[#f5eded]')

<section id="backups" class="admin-glass-card scroll-mt-24 p-5 sm:p-6">
    <div class="border-b border-[#ead8c9] pb-4 dark:border-white/10">
        <h2 class="text-lg font-black text-[#221211] dark:text-white">Backups</h2>
        <p class="mt-1 max-w-2xl text-sm text-[#6b5d55] dark:text-[#c9bfb7]">Policy metadata for future automated backup workers.</p>
    </div>

    <form action="{{ route('admin.system.settings.update', 'backups') }}" method="post" class="mt-6 grid gap-6 lg:grid-cols-2">
        @csrf
        @method('PUT')

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Policy frequency
            <select name="frequency" class="{{ $fc }}">
                @foreach (['hourly' => 'Hourly', 'daily' => 'Daily', 'weekly' => 'Weekly', 'manual' => 'Manual only'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('frequency', $b['frequency'] ?? 'daily') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Retention (days)
            <input type="number" min="1" max="365" name="retention_days" value="{{ old('retention_days', $b['retention_days'] ?? 14) }}" class="{{ $fc }}">
        </label>

        <div class="lg:col-span-2 rounded-xl border border-dashed border-[#d1b9a8] bg-[#fffaf6] p-4 text-xs text-[#63544c] dark:border-white/15 dark:bg-white/5 dark:text-[#d6cdc4]">
            <p class="font-semibold text-[#5c1514] dark:text-[#f7b396]">Last manual timestamp</p>
            <p class="mt-1 font-mono text-sm">{{ $b['last_manual_backup_at'] ?? '—' }}</p>
        </div>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Notes
            <textarea name="backups_notes" rows="2" class="{{ $fc }}">{{ old('backups_notes', $b['backups_notes'] ?? '') }}</textarea>
        </label>

        <div class="lg:col-span-2 flex flex-wrap gap-3">
            <button type="submit" class="inline-flex min-h-[42px] items-center justify-center rounded-lg bg-[#8b1e1a] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#f04a2a]">Save backup policy</button>
        </div>
    </form>
</section>
