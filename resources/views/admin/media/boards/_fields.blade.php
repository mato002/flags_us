<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="location" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Location</label>
        <input id="location" name="location" value="{{ old('location', $board?->location) }}" required class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('location')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="reference_code" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Reference code</label>
        <input id="reference_code" name="reference_code" value="{{ old('reference_code', $board?->reference_code) }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('reference_code')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="size" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Size</label>
        <input id="size" name="size" value="{{ old('size', $board?->size) }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('size')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="illumination" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Illumination</label>
        <input id="illumination" name="illumination" value="{{ old('illumination', $board?->illumination) }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('illumination')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="availability_status" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Availability</label>
        <input id="availability_status" name="availability_status" value="{{ old('availability_status', $board?->availability_status ?? 'available') }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('availability_status')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="maintenance_status" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Maintenance</label>
        <input id="maintenance_status" name="maintenance_status" value="{{ old('maintenance_status', $board?->maintenance_status ?? 'ok') }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('maintenance_status')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="price" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Price</label>
        <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $board?->price) }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('price')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="sm:col-span-2">
        <label for="traffic_notes" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Traffic notes</label>
        <textarea id="traffic_notes" name="traffic_notes" rows="3" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">{{ old('traffic_notes', $board?->traffic_notes) }}</textarea>
        @error('traffic_notes')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="sm:col-span-2">
        <label for="photo" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Board photo @if ($board)<span class="font-normal lowercase text-slate-400">(optional)</span>@endif</label>
        <input id="photo" type="file" name="photo" accept="image/*" class="mt-1 block w-full text-xs">
        @if ($board && $board->photo_url)
            <p class="mt-2"><img src="{{ $board->photo_url }}" alt="" class="max-h-40 rounded border border-slate-200 dark:border-white/10"></p>
        @endif
        @error('photo')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="flex items-end sm:col-span-2">
        <label class="flex items-center gap-2 text-slate-700 dark:text-[#dfd5cd]">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="rounded border-slate-400" @checked(old('is_active', $board?->is_active ?? true))>
            <span class="text-sm font-semibold">Active inventory record</span>
        </label>
    </div>
</div>
