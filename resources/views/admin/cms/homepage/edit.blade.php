@extends('layouts.admin')

@section('title', 'Homepage CMS | Effective Media')
@section('header', 'Homepage CMS')

@section('content')
    <form method="POST" action="{{ route('admin.cms.homepage.update') }}" class="max-w-4xl space-y-4 rounded-lg border border-slate-200 bg-white p-6">
        @csrf
        @method('PUT')

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Hero badge</label>
            <input type="text" name="hero_badge" value="{{ old('hero_badge', $setting?->hero_badge ?? '') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Hero title</label>
            <input type="text" name="hero_title" value="{{ old('hero_title', $setting?->hero_title ?? '') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Hero description</label>
            <textarea name="hero_description" rows="4" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('hero_description', $setting?->hero_description ?? '') }}</textarea>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Primary CTA text</label>
                <input type="text" name="primary_cta_text" value="{{ old('primary_cta_text', $setting?->primary_cta_text ?? '') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Primary CTA link</label>
                <input type="text" name="primary_cta_link" value="{{ old('primary_cta_link', $setting?->primary_cta_link ?? '') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Secondary CTA text</label>
                <input type="text" name="secondary_cta_text" value="{{ old('secondary_cta_text', $setting?->secondary_cta_text ?? '') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Secondary CTA link</label>
                <input type="text" name="secondary_cta_link" value="{{ old('secondary_cta_link', $setting?->secondary_cta_link ?? '') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>
        </div>

        <div class="border-t border-slate-200 pt-6">
            <h2 class="text-base font-semibold text-slate-900">Rates preview (homepage strip)</h2>
            <p class="mt-1 text-sm text-slate-600">Three cards under “Estimate Campaign Costs Fast”. Use relative URLs (e.g. <code class="rounded bg-slate-100 px-1">/quote?budget_range=KES+100K+-+300K</code>).</p>
            <div class="mt-4 space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Eyebrow / kicker</label>
                    <input type="text" name="rates_kicker" value="{{ old('rates_kicker', $ratesPreview['kicker'] ?? '') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" maxlength="120">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Section title</label>
                    <input type="text" name="rates_title" value="{{ old('rates_title', $ratesPreview['title'] ?? '') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" maxlength="255">
                </div>
            </div>
            @foreach ($ratesPreview['tiers'] ?? [] as $i => $tier)
                <fieldset class="mt-6 rounded-lg border border-slate-200 bg-slate-50/80 p-4">
                    <legend class="px-1 text-sm font-semibold text-slate-800">Tier {{ $i + 1 }}</legend>
                    <div class="mt-3 grid gap-3 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">Tier name</label>
                            <input type="text" name="rates_tiers[{{ $i }}][tier_label]" value="{{ old('rates_tiers.'.$i.'.tier_label', $tier['tier_label'] ?? '') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">Price line</label>
                            <input type="text" name="rates_tiers[{{ $i }}][price]" value="{{ old('rates_tiers.'.$i.'.price', $tier['price'] ?? '') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-xs font-medium text-slate-600">Description</label>
                            <textarea name="rates_tiers[{{ $i }}][description]" rows="2" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('rates_tiers.'.$i.'.description', $tier['description'] ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">Button label</label>
                            <input type="text" name="rates_tiers[{{ $i }}][cta_label]" value="{{ old('rates_tiers.'.$i.'.cta_label', $tier['cta_label'] ?? '') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">Button URL</label>
                            <input type="text" name="rates_tiers[{{ $i }}][cta_href]" value="{{ old('rates_tiers.'.$i.'.cta_href', $tier['cta_href'] ?? '') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" placeholder="/quote?... or /contact-us">
                        </div>
                        <div class="flex items-center gap-2 md:col-span-2">
                            <input type="hidden" name="rates_tiers[{{ $i }}][highlighted]" value="0">
                            <input type="checkbox" id="rates_highlight_{{ $i }}" name="rates_tiers[{{ $i }}][highlighted]" value="1" class="rounded border-slate-300" @checked(old('rates_tiers.'.$i.'.highlighted', ($tier['highlighted'] ?? false) ? '1' : '0') === '1')>
                            <label for="rates_highlight_{{ $i }}" class="text-sm text-slate-700">Highlighted card (accent background + primary button)</label>
                        </div>
                    </div>
                </fieldset>
            @endforeach
        </div>

        <button class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Save homepage content</button>
    </form>
@endsection
