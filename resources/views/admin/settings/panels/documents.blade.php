@php($d = $groups['documents'])
@php($fc = 'mt-1 w-full rounded-lg border border-[#d7c4b5] bg-white px-3 py-2 text-sm text-[#2a221f] dark:border-white/15 dark:bg-[#1a1616] dark:text-[#f5eded]')

<section id="documents" class="admin-glass-card scroll-mt-24 p-5 sm:p-6">
    <div class="border-b border-[#ead8c9] pb-4 dark:border-white/10">
        <h2 class="text-lg font-black text-[#221211] dark:text-white">Document center</h2>
        <p class="mt-1 max-w-2xl text-sm text-[#6b5d55] dark:text-[#c9bfb7]">Lead capture gates, thumbnails, ingestion limits.</p>
    </div>

    <form action="{{ route('admin.system.settings.update', 'documents') }}" method="post" class="mt-6 grid gap-6 lg:grid-cols-2">
        @csrf
        @method('PUT')

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            <input type="hidden" name="lead_capture_enabled" value="0">
            <input type="checkbox" name="lead_capture_enabled" value="1" @checked(old('lead_capture_enabled', $d['lead_capture_enabled'] ?? true)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
            Lead capture on downloads
        </label>

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            <input type="hidden" name="auto_thumbnails" value="0">
            <input type="checkbox" name="auto_thumbnails" value="1" @checked(old('auto_thumbnails', $d['auto_thumbnails'] ?? true)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
            Auto thumbnails for imagery
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            PDF watermark text
            <input type="text" name="pdf_watermark_text" value="{{ old('pdf_watermark_text', $d['pdf_watermark_text'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Max upload size (MB)
            <input type="number" min="1" max="50" name="max_upload_mb" value="{{ old('max_upload_mb', $d['max_upload_mb'] ?? 15) }}" class="{{ $fc }}">
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Notes
            <textarea name="documents_notes" rows="3" class="{{ $fc }}">{{ old('documents_notes', $d['documents_notes'] ?? '') }}</textarea>
        </label>

        <div class="lg:col-span-2 flex flex-wrap gap-3">
            <button type="submit" class="inline-flex min-h-[42px] items-center justify-center rounded-lg bg-[#8b1e1a] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#f04a2a]">Save documents</button>
        </div>
    </form>
</section>
