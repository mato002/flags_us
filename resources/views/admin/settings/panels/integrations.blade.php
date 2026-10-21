@php($in = $groups['integrations'])
@php($fc = 'mt-1 w-full rounded-lg border border-[#d7c4b5] bg-white px-3 py-2 text-sm text-[#2a221f] dark:border-white/15 dark:bg-[#1a1616] dark:text-[#f5eded]')
@php($m = $in['_meta'] ?? [])

<section id="integrations" class="admin-glass-card scroll-mt-24 p-5 sm:p-6">
    <div class="border-b border-[#ead8c9] pb-4 dark:border-white/10">
        <h2 class="text-lg font-black text-[#221211] dark:text-white">Integrations</h2>
        <p class="mt-1 max-w-2xl text-sm text-[#6b5d55] dark:text-[#c9bfb7]">API keys encrypted at rest. Leave secret fields blank to retain prior values.</p>
    </div>

    <form action="{{ route('admin.system.settings.update', 'integrations') }}" method="post" class="mt-6 grid gap-6 lg:grid-cols-2">
        @csrf
        @method('PUT')

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Google Maps API key @if(!empty($m['google_maps_api_key_stored']))<span class="text-xs font-normal text-emerald-700 dark:text-emerald-300">(stored)</span>@endif
            <input type="password" name="google_maps_api_key" value="" class="{{ $fc }}" autocomplete="new-password">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            OpenAI model
            <input type="text" name="openai_model" value="{{ old('openai_model', $in['openai_model'] ?? '') }}" class="{{ $fc }}" autocomplete="off">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            OpenAI API key @if(!empty($m['openai_api_key_stored']))<span class="text-xs font-normal text-emerald-700 dark:text-emerald-300">(stored)</span>@endif
            <input type="password" name="openai_api_key" value="" class="{{ $fc }}" autocomplete="new-password">
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Meta Pixel ID
            <input type="text" name="meta_pixel_id" value="{{ old('meta_pixel_id', $in['meta_pixel_id'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Cloudinary cloud name
            <input type="text" name="cloudinary_cloud_name" value="{{ old('cloudinary_cloud_name', $in['cloudinary_cloud_name'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Cloudinary API key
            <input type="text" name="cloudinary_api_key" value="{{ old('cloudinary_api_key', $in['cloudinary_api_key'] ?? '') }}" class="{{ $fc }}" autocomplete="off">
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Cloudinary API secret @if(!empty($m['cloudinary_api_secret_stored']))<span class="text-xs font-normal text-emerald-700 dark:text-emerald-300">(stored)</span>@endif
            <input type="password" name="cloudinary_api_secret" value="" class="{{ $fc }}" autocomplete="new-password">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            AWS access key ID
            <input type="text" name="aws_access_key_id" value="{{ old('aws_access_key_id', $in['aws_access_key_id'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            AWS secret access key @if(!empty($m['aws_secret_access_key_stored']))<span class="text-xs font-normal text-emerald-700 dark:text-emerald-300">(stored)</span>@endif
            <input type="password" name="aws_secret_access_key" value="" class="{{ $fc }}" autocomplete="new-password">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            AWS default region
            <input type="text" name="aws_default_region" value="{{ old('aws_default_region', $in['aws_default_region'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            AWS bucket
            <input type="text" name="aws_bucket" value="{{ old('aws_bucket', $in['aws_bucket'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Integration playbook / notes
            <textarea name="integrations_notes" rows="3" class="{{ $fc }}">{{ old('integrations_notes', $in['integrations_notes'] ?? '') }}</textarea>
        </label>

        <div class="lg:col-span-2 flex flex-wrap gap-3">
            <button type="submit" class="inline-flex min-h-[42px] items-center justify-center rounded-lg bg-[#8b1e1a] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#f04a2a]">Save integrations</button>
        </div>
    </form>
</section>
