<form method="POST" action="{{ $action }}" class="max-w-3xl space-y-4 rounded-lg border border-slate-200 bg-white p-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Label</label>
            <input type="text" name="label" value="{{ old('label', $statistic?->label) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Value</label>
            <input type="text" name="value" value="{{ old('value', $statistic?->value) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Suffix</label>
            <input type="text" name="suffix" value="{{ old('suffix', $statistic?->suffix) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Sort order</label>
            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $statistic?->sort_order ?? 0) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
    </div>
    <label class="inline-flex items-center gap-2 text-sm text-slate-700">
        <input type="hidden" name="is_published" value="0">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $statistic?->is_published ?? true))>
        Published
    </label>
    <div class="flex gap-3">
        <button class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Save</button>
        @if ($statistic)
            <button form="delete-statistic" class="rounded-md border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-700">Delete</button>
        @endif
    </div>
</form>

@if ($statistic)
    <form id="delete-statistic" method="POST" action="{{ route('admin.cms.statistics.destroy', $statistic) }}">
        @csrf
        @method('DELETE')
    </form>
@endif
