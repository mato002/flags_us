@php($g = $groups['general'])
@php($fc = 'mt-1 w-full rounded-lg border border-[#d7c4b5] bg-white px-3 py-2 text-sm text-[#2a221f] dark:border-white/15 dark:bg-[#1a1616] dark:text-[#f5eded]')

<section id="general" class="admin-glass-card scroll-mt-24 p-5 sm:p-6">
    <div class="flex flex-col gap-3 border-b border-[#ead8c9] pb-4 dark:border-white/10 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="text-lg font-black text-[#221211] dark:text-white">General branding</h2>
            <p class="mt-1 max-w-2xl text-sm text-[#6b5d55] dark:text-[#c9bfb7]">Company identity, logos, and typography tokens used across the ecosystem.</p>
        </div>
        <span class="rounded-full bg-[#f04a2a]/15 px-3 py-1 text-xs font-semibold text-[#8b1e1a] dark:bg-[#f04a2a]/20 dark:text-[#ffb69a]">Public-facing</span>
    </div>

    <form action="{{ route('admin.system.settings.update', 'general') }}" method="post" enctype="multipart/form-data" class="mt-6 grid gap-6 lg:grid-cols-2">
        @csrf
        @method('PUT')

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Company name
            <input type="text" name="company_name" value="{{ old('company_name', $g['company_name'] ?? '') }}" class="{{ $fc }}" required maxlength="120">
            @error('company_name')<span class="mt-1 block text-xs text-rose-600 dark:text-rose-300">{{ $message }}</span>@enderror
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Logo upload
            <input type="file" name="logo" accept="image/*" class="{{ $fc }}">
            @if (! empty($g['logo_path']))
                <span class="mt-1 block text-xs text-[#7a665e]">Current: {{ $g['logo_path'] }}</span>
            @endif
            @error('logo')<span class="mt-1 block text-xs text-rose-600 dark:text-rose-300">{{ $message }}</span>@enderror
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Favicon
            <input type="file" name="favicon" accept=".ico,.png,.webp,.svg" class="{{ $fc }}">
            @if (! empty($g['favicon_path']))
                <span class="mt-1 block text-xs text-[#7a665e]">Current: {{ $g['favicon_path'] }}</span>
            @endif
        </label>

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            <input type="hidden" name="remove_logo" value="0">
            <input type="checkbox" name="remove_logo" value="1" @checked(old('remove_logo', false)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a] dark:border-white/20">
            Remove stored logo file
        </label>

        <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            <input type="hidden" name="remove_favicon" value="0">
            <input type="checkbox" name="remove_favicon" value="1" @checked(old('remove_favicon', false)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a] dark:border-white/20">
            Remove stored favicon
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Tagline
            <input type="text" name="tagline" value="{{ old('tagline', $g['tagline'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Primary brand color
            <input type="text" name="primary_color" value="{{ old('primary_color', $g['primary_color'] ?? '#8b1e1a') }}" class="{{ $fc }}" pattern="^#([0-9a-fA-F]{6}|[0-9a-fA-F]{8})$">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Accent orange
            <input type="text" name="accent_color" value="{{ old('accent_color', $g['accent_color'] ?? '#f04a2a') }}" class="{{ $fc }}" pattern="^#([0-9a-fA-F]{6}|[0-9a-fA-F]{8})$">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Deep maroon
            <input type="text" name="deep_maroon_color" value="{{ old('deep_maroon_color', $g['deep_maroon_color'] ?? '#5c1514') }}" class="{{ $fc }}" pattern="^#([0-9a-fA-F]{6}|[0-9a-fA-F]{8})$">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Heading font
            <input type="text" name="font_heading" value="{{ old('font_heading', $g['font_heading'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Body font
            <input type="text" name="font_body" value="{{ old('font_body', $g['font_body'] ?? '') }}" class="{{ $fc }}">
        </label>

        <div class="lg:col-span-2 flex flex-wrap gap-3">
            <button type="submit" class="inline-flex min-h-[42px] items-center justify-center rounded-lg bg-[#8b1e1a] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#f04a2a]">Save general branding</button>
        </div>
    </form>
</section>
