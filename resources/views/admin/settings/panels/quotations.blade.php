@php($q = $groups['quotations'])
@php($fc = 'mt-1 w-full rounded-lg border border-[#d7c4b5] bg-white px-3 py-2 text-sm text-[#2a221f] dark:border-white/15 dark:bg-[#1a1616] dark:text-[#f5eded]')

<section id="quotations" class="admin-glass-card scroll-mt-24 p-5 sm:p-6">
    <div class="border-b border-[#ead8c9] pb-4 dark:border-white/10">
        <h2 class="text-lg font-black text-[#221211] dark:text-white">Quotation settings</h2>
        <p class="mt-1 max-w-2xl text-sm text-[#6b5d55] dark:text-[#c9bfb7]">Prefixes, tax, expiry, PDF presentation.</p>
    </div>

    <form action="{{ route('admin.system.settings.update', 'quotations') }}" method="post" class="mt-6 grid gap-6 lg:grid-cols-2">
        @csrf
        @method('PUT')

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Quote prefix
            <input type="text" name="quote_prefix" value="{{ old('quote_prefix', $q['quote_prefix'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Invoice prefix
            <input type="text" name="invoice_prefix" value="{{ old('invoice_prefix', $q['invoice_prefix'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            VAT %
            <input type="number" step="0.01" min="0" max="50" name="vat_percentage" value="{{ old('vat_percentage', $q['vat_percentage'] ?? '16') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Currency (ISO 4217)
            <input type="text" name="currency_code" maxlength="3" value="{{ old('currency_code', $q['currency_code'] ?? 'KES') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Quote expiry (days)
            <input type="number" min="1" max="365" name="quote_expiry_days" value="{{ old('quote_expiry_days', $q['quote_expiry_days'] ?? 21) }}" class="{{ $fc }}">
        </label>

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4] lg:col-span-2">
            <input type="hidden" name="pdf_show_watermark" value="0">
            <input type="checkbox" name="pdf_show_watermark" value="1" @checked(old('pdf_show_watermark', $q['pdf_show_watermark'] ?? false)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
            Show watermark on quotations / invoice PDF previews
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Legal trading name on PDFs
            <input type="text" name="pdf_company_legal_name" value="{{ old('pdf_company_legal_name', $q['pdf_company_legal_name'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Footer note template
            <textarea name="pdf_footer_note" rows="3" class="{{ $fc }}">{{ old('pdf_footer_note', $q['pdf_footer_note'] ?? '') }}</textarea>
        </label>

        <div class="lg:col-span-2 flex flex-wrap gap-3">
            <button type="submit" class="inline-flex min-h-[42px] items-center justify-center rounded-lg bg-[#8b1e1a] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#f04a2a]">Save quotations</button>
        </div>
    </form>
</section>
