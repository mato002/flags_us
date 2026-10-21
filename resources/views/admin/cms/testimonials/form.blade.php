<form method="POST" action="{{ $action }}" class="max-w-3xl space-y-4 rounded-lg border border-slate-200 bg-white p-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Client name</label>
            <input type="text" name="client_name" value="{{ old('client_name', $testimonial?->client_name) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Company name</label>
            <input type="text" name="company_name" value="{{ old('company_name', $testimonial?->company_name) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Quote</label>
        <textarea name="quote" rows="4" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('quote', $testimonial?->quote) }}</textarea>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Rating</label>
            <input type="number" name="rating" min="1" max="5" value="{{ old('rating', $testimonial?->rating ?? 5) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Sort order</label>
            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $testimonial?->sort_order ?? 0) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <label class="mt-7 inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $testimonial?->is_published ?? true))>
            Published
        </label>
    </div>
    <div class="flex gap-3">
        <button class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Save</button>
        @if ($testimonial)
            <button form="delete-testimonial" class="rounded-md border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-700">Delete</button>
        @endif
    </div>
</form>

@if ($testimonial)
    <form id="delete-testimonial" method="POST" action="{{ route('admin.cms.testimonials.destroy', $testimonial) }}">
        @csrf
        @method('DELETE')
    </form>
@endif
