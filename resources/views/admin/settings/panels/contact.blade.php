@php($c = $groups['contact'])
@php($fc = 'mt-1 w-full rounded-lg border border-[#d7c4b5] bg-white px-3 py-2 text-sm text-[#2a221f] dark:border-white/15 dark:bg-[#1a1616] dark:text-[#f5eded]')
@php($phonesRaw = old('phones_raw', implode("\n", $c['phones'] ?? [])))
@php($emailsRaw = old('emails_raw', implode("\n", $c['emails'] ?? [])))
@php($officesRaw = old('office_locations_raw', implode("\n", $c['office_locations'] ?? [])))
@php($soc = $c['social'] ?? [])

<section id="contact" class="admin-glass-card scroll-mt-24 p-5 sm:p-6">
    <div class="flex flex-col gap-3 border-b border-[#ead8c9] pb-4 dark:border-white/10 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="text-lg font-black text-[#221211] dark:text-white">Contact information</h2>
            <p class="mt-1 max-w-2xl text-sm text-[#6b5d55] dark:text-[#c9bfb7]">Touches the website header/footer. Values override YAML profile entries when populated.</p>
        </div>
    </div>

    <form action="{{ route('admin.system.settings.update', 'contact') }}" method="post" class="mt-6 grid gap-6 lg:grid-cols-2">
        @csrf
        @method('PUT')

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Phone numbers <span class="font-normal opacity-75">— one per line</span>
            <textarea name="phones_raw" rows="4" class="{{ $fc }}">{{ $phonesRaw }}</textarea>
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Email addresses <span class="font-normal opacity-75">— one per line</span>
            <textarea name="emails_raw" rows="3" class="{{ $fc }}">{{ $emailsRaw }}</textarea>
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Office locations <span class="font-normal opacity-75">— one per line</span>
            <textarea name="office_locations_raw" rows="3" class="{{ $fc }}">{{ $officesRaw }}</textarea>
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            WhatsApp display label
            <input type="text" name="whatsapp_display" value="{{ old('whatsapp_display', $c['whatsapp_display'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            WhatsApp E.164 / digits
            <input type="text" name="whatsapp_e164" value="{{ old('whatsapp_e164', $c['whatsapp_e164'] ?? '') }}" class="{{ $fc }}" placeholder="2547XXXXXXXX">
        </label>

        @foreach (['facebook' => 'Facebook', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn', 'x' => 'X', 'youtube' => 'YouTube'] as $key => $lab)
            <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
                {{ $lab }} URL
                <input type="url" name="social[{{ $key }}]" value="{{ old('social.'.$key, $soc[$key] ?? '') }}" class="{{ $fc }}" placeholder="https://">
            </label>
        @endforeach

        <div class="lg:col-span-2 flex flex-wrap gap-3">
            <button type="submit" class="inline-flex min-h-[42px] items-center justify-center rounded-lg bg-[#8b1e1a] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#f04a2a]">Save contact</button>
        </div>
    </form>
</section>
