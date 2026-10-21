<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="site_name" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Site name</label>
        <input id="site_name" name="site_name" value="{{ old('site_name', $site?->site_name) }}" required class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('site_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="county" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">County</label>
        <input id="county" name="county" value="{{ old('county', $site?->county) }}" required class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('county')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="town" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Town</label>
        <input id="town" name="town" value="{{ old('town', $site?->town) }}" required class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('town')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="poles_count" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Poles count</label>
        <input id="poles_count" type="number" name="poles_count" min="0" value="{{ old('poles_count', $site?->poles_count ?? 0) }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('poles_count')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="media_type" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Media type</label>
        <input id="media_type" name="media_type" value="{{ old('media_type', $site?->media_type) }}" placeholder="e.g. Street furniture" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('media_type')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="latitude" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Latitude</label>
        <input id="latitude" type="number" step="any" name="latitude" value="{{ old('latitude', $site?->latitude) }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('latitude')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="longitude" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Longitude</label>
        <input id="longitude" type="number" step="any" name="longitude" value="{{ old('longitude', $site?->longitude) }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('longitude')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="flex items-end sm:col-span-2">
        <label class="flex items-center gap-2 text-slate-700 dark:text-[#dfd5cd]">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="rounded border-slate-400" @checked(old('is_active', $site?->is_active ?? true))>
            <span class="text-sm font-semibold">Active</span>
        </label>
    </div>
</div>
