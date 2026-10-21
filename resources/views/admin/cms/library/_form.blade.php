<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-5 rounded-lg border border-slate-200 bg-white p-6 text-sm dark:border-white/10 dark:bg-white/5">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label for="title" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Title</label>
        <input id="title" name="title" value="{{ old('title', $document?->title) }}" required class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        @error('title')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="description" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Description</label>
        <textarea id="description" name="description" rows="3" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">{{ old('description', $document?->description) }}</textarea>
        @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="file" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">File @if ($document)<span class="font-normal lowercase text-slate-400">(optional — replace PDF/DOC)</span>@endif</label>
        <input id="file" type="file" name="file" @if(!$document) required @endif accept=".pdf,.doc,.docx,application/pdf" class="mt-1 block w-full text-xs">
        @if ($document && $document->file_path)
            <p class="mt-2 text-xs text-slate-500">Current file: <a href="{{ asset('storage/'.ltrim($document->file_path, '/')) }}" target="_blank" rel="noopener" class="font-semibold text-[#8b1e1a] underline">Preview / download</a></p>
        @endif
        @error('file')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="sort_order" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Sort order</label>
            <input id="sort_order" type="number" name="sort_order" min="0" value="{{ old('sort_order', $document?->sort_order ?? 0) }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
            @error('sort_order')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div class="space-y-3 pt-6">
            <label class="flex items-center gap-2 text-slate-700 dark:text-[#dfd5cd]">
                <input type="hidden" name="requires_lead_capture" value="0">
                <input type="checkbox" name="requires_lead_capture" value="1" class="rounded border-slate-400" @checked(old('requires_lead_capture', $document?->requires_lead_capture ?? false))>
                <span class="text-sm font-semibold">Lead capture required</span>
            </label>
            <label class="flex items-center gap-2 text-slate-700 dark:text-[#dfd5cd]">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="rounded border-slate-400" @checked(old('is_active', $document?->is_active ?? true))>
                <span class="text-sm font-semibold">Active</span>
            </label>
        </div>
    </div>

    <div class="flex flex-wrap gap-2">
        <button type="submit" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Save</button>
        <a href="{{ route('admin.cms.library.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold dark:border-white/20 dark:text-white">Cancel</a>
    </div>
</form>
