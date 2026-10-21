@php($m = $groups['media_coverage'])
@php($fc = 'mt-1 w-full rounded-lg border border-[#d7c4b5] bg-white px-3 py-2 text-sm text-[#2a221f] dark:border-white/15 dark:bg-[#1a1616] dark:text-[#f5eded]')
@php($cats = old('board_categories_raw', implode("\n", $m['board_categories'] ?? [])))

<section id="media_coverage" class="admin-glass-card scroll-mt-24 p-5 sm:p-6">
    <div class="border-b border-[#ead8c9] pb-4 dark:border-white/10">
        <h2 class="text-lg font-black text-[#221211] dark:text-white">Media coverage defaults</h2>
        <p class="mt-1 max-w-2xl text-sm text-[#6b5d55] dark:text-[#c9bfb7]">Geography scaffolding and map UX tokens for explorers & inventory.</p>
    </div>

    <form action="{{ route('admin.system.settings.update', 'media_coverage') }}" method="post" class="mt-6 grid gap-6">
        @csrf
        @method('PUT')

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Default counties <span class="text-xs font-normal opacity-75">comma or newline hints</span>
            <textarea name="default_counties" rows="3" class="{{ $fc }}">{{ old('default_counties', $m['default_counties'] ?? '') }}</textarea>
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Default constituencies
            <textarea name="default_constituencies" rows="3" class="{{ $fc }}">{{ old('default_constituencies', $m['default_constituencies'] ?? '') }}</textarea>
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Default wards
            <textarea name="default_wards" rows="3" class="{{ $fc }}">{{ old('default_wards', $m['default_wards'] ?? '') }}</textarea>
        </label>

        <div class="grid gap-6 lg:grid-cols-2">
            <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
                Map theme key
                <input type="text" name="map_theme" value="{{ old('map_theme', $m['map_theme'] ?? '') }}" class="{{ $fc }}">
            </label>

            <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
                Marker style
                <input type="text" name="marker_style" value="{{ old('marker_style', $m['marker_style'] ?? '') }}" class="{{ $fc }}">
            </label>
        </div>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Board categories <span class="text-xs font-normal opacity-75">— one per line</span>
            <textarea name="board_categories_raw" rows="5" class="{{ $fc }}">{{ $cats }}</textarea>
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Internal notes
            <textarea name="coverage_notes" rows="3" class="{{ $fc }}">{{ old('coverage_notes', $m['coverage_notes'] ?? '') }}</textarea>
        </label>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex min-h-[42px] items-center justify-center rounded-lg bg-[#8b1e1a] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#f04a2a]">Save coverage</button>
        </div>
    </form>
</section>
