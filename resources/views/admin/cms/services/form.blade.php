<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="max-w-3xl space-y-4 rounded-lg border border-slate-200 bg-white p-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Title</label>
        <input type="text" name="title" value="{{ old('title', $service?->title) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Explorer format preset</label>
        @php
            $fk = old('format_key', $service?->format_key);
            $presetOptions = [
                '' => 'Auto-detect from title',
                'street_light' => 'Street light corridors',
                'billboard' => 'Large format billboards',
                'pavement' => 'Pavement / proximity',
                'office' => 'Office branding',
                'activation' => 'Activations / roadshows',
                'rollup' => 'Roll-ups / collateral',
                'generic' => 'Generic / bundled',
            ];
        @endphp
        <select name="format_key" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            @foreach ($presetOptions as $value => $label)
                <option value="{{ $value }}" @selected((string) $fk === (string) $value)>{{ $label }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-slate-500">Controls default copy hierarchy on What We Do. Override via JSON.</p>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Summary</label>
        <textarea name="summary" rows="4" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('summary', $service?->summary) }}</textarea>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Public page JSON overrides</label>
        @php
            $preset = '';
            $ps = old('public_sections_json');
            if ($ps === null && $service?->public_sections) {
                $preset = json_encode($service->public_sections, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            } elseif (is_string($ps)) {
                $preset = $ps;
            }
        @endphp
        <textarea name="public_sections_json" rows="14" placeholder='{"headline":"","positioning":"","ideal_use_cases":["…"],"cta_primary_label":""}'
            class="w-full rounded-md border border-slate-300 px-3 py-2 font-mono text-xs">{{ $preset }}</textarea>
        @error('public_sections_json')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        <p class="mt-1 text-xs text-slate-500">Optional — merges onto format presets (headlines, bullets, imagery URLs, CTAs).</p>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Icon hint</label>
        <input type="text" name="icon" value="{{ old('icon', $service?->icon) }}" placeholder="Emoji or glyph key" maxlength="50" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Featured image</label>
        <input type="file" name="image" accept="image/*" class="w-full text-sm">
        @if ($service && $service->image_path)
            <p class="mt-1 text-xs text-slate-500">File stored · replace by uploading anew.</p>
        @endif
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Sort order</label>
            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $service?->sort_order ?? 0) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div class="mt-7 space-y-2">
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service?->is_active ?? true))>
                Active
            </label>
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="hidden" name="is_visible_public" value="0">
                <input type="checkbox" name="is_visible_public" value="1" @checked(old('is_visible_public', $service?->is_visible_public ?? true))>
                Visible on public site
            </label>
        </div>
    </div>
    <div class="flex gap-3">
        <button class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Save</button>
        @if ($service)
            <button form="delete-service" class="rounded-md border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-700">Delete</button>
        @endif
    </div>
</form>

@if ($service)
    <form id="delete-service" method="POST" action="{{ route('admin.cms.services.destroy', $service) }}">
        @csrf
        @method('DELETE')
    </form>
@endif
