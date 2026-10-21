@php($w = $groups['website'])
@php($fc = 'mt-1 w-full rounded-lg border border-[#d7c4b5] bg-white px-3 py-2 text-sm text-[#2a221f] dark:border-white/15 dark:bg-[#1a1616] dark:text-[#f5eded]')

<section id="website" class="admin-glass-card scroll-mt-24 p-5 sm:p-6">
    <div class="border-b border-[#ead8c9] pb-4 dark:border-white/10">
        <h2 class="text-lg font-black text-[#221211] dark:text-white">Website settings</h2>
        <p class="mt-1 max-w-2xl text-sm text-[#6b5d55] dark:text-[#c9bfb7]">Hero defaults, UX toggles, SEO, and telemetry IDs (synced to CMS homepage rows where applicable).</p>
    </div>

    <form action="{{ route('admin.system.settings.update', 'website') }}" method="post" class="mt-6 grid gap-6 lg:grid-cols-2">
        @csrf
        @method('PUT')

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Hero eyebrow text
            <input type="text" name="hero_badge" value="{{ old('hero_badge', $w['hero_badge'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Hero headline
            <input type="text" name="hero_headline" value="{{ old('hero_headline', $w['hero_headline'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Hero supporting text
            <textarea name="hero_subtext" rows="3" class="{{ $fc }}">{{ old('hero_subtext', $w['hero_subtext'] ?? '') }}</textarea>
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Primary CTA label
            <input type="text" name="cta_plan_label" value="{{ old('cta_plan_label', $w['cta_plan_label'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Primary CTA path or URL
            <input type="text" name="cta_plan_url" value="{{ old('cta_plan_url', $w['cta_plan_url'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Coverage CTA label
            <input type="text" name="cta_coverage_label" value="{{ old('cta_coverage_label', $w['cta_coverage_label'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Coverage CTA URL
            <input type="text" name="cta_coverage_url" value="{{ old('cta_coverage_url', $w['cta_coverage_url'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Download CTA label
            <input type="text" name="cta_download_label" value="{{ old('cta_download_label', $w['cta_download_label'] ?? '') }}" class="{{ $fc }}">
        </label>

        <div class="lg:col-span-2 grid gap-4 sm:grid-cols-3">
            <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
                <input type="hidden" name="maintenance_mode" value="0">
                <input type="checkbox" name="maintenance_mode" value="1" @checked(old('maintenance_mode', $w['maintenance_mode'] ?? false)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
                Maintenance mode (public site)
            </label>
            <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
                <input type="hidden" name="sticky_navbar" value="0">
                <input type="checkbox" name="sticky_navbar" value="1" @checked(old('sticky_navbar', $w['sticky_navbar'] ?? true)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
                Sticky navigation
            </label>
            <label class="flex items-center gap-3 text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
                <input type="hidden" name="floating_action_buttons" value="0">
                <input type="checkbox" name="floating_action_buttons" value="1" @checked(old('floating_action_buttons', $w['floating_action_buttons'] ?? true)) class="h-4 w-4 rounded border-[#cbb5a8] text-[#8b1e1a]">
                Floating action buttons
            </label>
        </div>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Default meta title
            <input type="text" name="meta_title" value="{{ old('meta_title', $w['meta_title'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Open Graph image URL
            <input type="text" name="og_image" value="{{ old('og_image', $w['og_image'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Meta description
            <textarea name="meta_description" rows="2" class="{{ $fc }}">{{ old('meta_description', $w['meta_description'] ?? '') }}</textarea>
        </label>

        <label class="lg:col-span-2 block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Meta keywords
            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $w['meta_keywords'] ?? '') }}" class="{{ $fc }}">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Google Search Console verification
            <input type="text" name="google_site_verification" value="{{ old('google_site_verification', $w['google_site_verification'] ?? '') }}" class="{{ $fc }}" placeholder="content value from Google meta tag">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            Bing Webmaster verification
            <input type="text" name="bing_site_verification" value="{{ old('bing_site_verification', $w['bing_site_verification'] ?? '') }}" class="{{ $fc }}" placeholder="msvalidate.01 content value">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            GA4 measurement ID
            <input type="text" name="analytics_ga4_id" value="{{ old('analytics_ga4_id', $w['analytics_ga4_id'] ?? '') }}" class="{{ $fc }}" placeholder="G-XXXXXX">
        </label>

        <label class="block text-sm font-semibold text-[#3f2f2d] dark:text-[#e8dbd4]">
            GTM container ID
            <input type="text" name="analytics_gtm_id" value="{{ old('analytics_gtm_id', $w['analytics_gtm_id'] ?? '') }}" class="{{ $fc }}" placeholder="GTM-XXXX">
        </label>

        <div class="lg:col-span-2 flex flex-wrap gap-3">
            <button type="submit" class="inline-flex min-h-[42px] items-center justify-center rounded-lg bg-[#8b1e1a] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#f04a2a]">Save website</button>
        </div>
    </form>
</section>
