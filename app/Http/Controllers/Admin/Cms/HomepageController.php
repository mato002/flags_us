<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use App\Services\Cms\HomepageContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomepageController extends Controller
{
    public function __construct(
        private readonly HomepageContentService $homepageContentService,
    ) {}

    public function edit(): View
    {
        $setting = HomepageSetting::query()->first();

        return view('admin.cms.homepage.edit', [
            'setting' => $setting,
            'ratesPreview' => $this->homepageContentService->effectiveRatesPreview($setting),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_badge' => ['required', 'string', 'max:255'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string'],
            'primary_cta_text' => ['required', 'string', 'max:255'],
            'primary_cta_link' => ['required', 'string', 'max:255'],
            'secondary_cta_text' => ['required', 'string', 'max:255'],
            'secondary_cta_link' => ['required', 'string', 'max:255'],
            'rates_kicker' => ['required', 'string', 'max:120'],
            'rates_title' => ['required', 'string', 'max:255'],
            'rates_tiers' => ['required', 'array', 'min:1', 'max:6'],
            'rates_tiers.*.tier_label' => ['required', 'string', 'max:120'],
            'rates_tiers.*.price' => ['required', 'string', 'max:120'],
            'rates_tiers.*.description' => ['nullable', 'string', 'max:500'],
            'rates_tiers.*.cta_label' => ['required', 'string', 'max:120'],
            'rates_tiers.*.cta_href' => ['required', 'string', 'max:500'],
        ]);

        $tiersOut = [];
        foreach ($validated['rates_tiers'] as $i => $row) {
            $tiersOut[] = [
                'tier_label' => $row['tier_label'],
                'price' => $row['price'],
                'description' => (string) ($row['description'] ?? ''),
                'cta_label' => $row['cta_label'],
                'cta_href' => $row['cta_href'],
                'highlighted' => $request->boolean('rates_tiers.'.$i.'.highlighted'),
            ];
        }

        $ratesPreview = [
            'kicker' => $validated['rates_kicker'],
            'title' => $validated['rates_title'],
            'tiers' => $tiersOut,
        ];

        $payload = [
            'hero_badge' => $validated['hero_badge'],
            'hero_title' => $validated['hero_title'],
            'hero_description' => $validated['hero_description'] ?? '',
            'primary_cta_text' => $validated['primary_cta_text'],
            'primary_cta_link' => $validated['primary_cta_link'],
            'secondary_cta_text' => $validated['secondary_cta_text'],
            'secondary_cta_link' => $validated['secondary_cta_link'],
            'rates_preview' => $ratesPreview,
        ];

        $setting = HomepageSetting::query()->first();

        if ($setting instanceof HomepageSetting) {
            $setting->update($payload);
        } else {
            HomepageSetting::query()->create($payload);
        }

        Cache::forget(HomepageContentService::HOMEPAGE_PAYLOAD_CACHE_KEY);

        return back()->with('status', 'Homepage content updated successfully.');
    }
}
