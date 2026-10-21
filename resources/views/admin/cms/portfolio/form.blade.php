<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="max-w-4xl space-y-4 rounded-lg border border-slate-200 bg-white p-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Title</label>
            <input type="text" name="title" value="{{ old('title', $item?->title) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Client name</label>
            <input type="text" name="client_name" value="{{ old('client_name', $item?->client_name) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Category</label>
            <input type="text" name="category" value="{{ old('category', $item?->category) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Campaign location</label>
            <input type="text" name="campaign_location" value="{{ old('campaign_location', $item?->campaign_location) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Media type</label>
            <input type="text" name="media_type" value="{{ old('media_type', $item?->media_type) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" placeholder="Street lights, Billboard…">
        </div>
        <div class="md:col-span-2">
            <label class="mb-1 block text-sm font-medium text-slate-700">Hero image</label>
            <input type="file" name="image" accept="image/*" class="w-full text-sm">
            @if ($item && $item->image_url)
                <p class="mt-1 text-xs text-slate-500">Current: <a href="{{ $item->image_url }}" target="_blank" rel="noopener" class="underline">view</a></p>
            @endif
        </div>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Description</label>
        <textarea name="description" rows="4" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('description', $item?->description) }}</textarea>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Sort order</label>
            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $item?->sort_order ?? 0) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <label class="mt-7 inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="hidden" name="featured" value="0">
            <input type="checkbox" name="featured" value="1" @checked(old('featured', $item?->featured ?? false))>
            Featured
        </label>
        <label class="mt-7 inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $item?->is_published ?? true))>
            Published
        </label>
    </div>
    <div class="flex gap-3">
        <button class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Save</button>
        @if ($item)
            <button form="delete-item" class="rounded-md border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-700">Delete</button>
        @endif
    </div>
</form>

@if ($item)
    <form id="delete-item" method="POST" action="{{ route('admin.cms.portfolio.destroy', $item) }}">
        @csrf
        @method('DELETE')
    </form>
@endif
