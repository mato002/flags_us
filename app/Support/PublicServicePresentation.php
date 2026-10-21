<?php

namespace App\Support;

use App\Models\Service;

final class PublicServicePresentation
{
    /**
     * @return array<string, mixed>
     */
    public static function row(Service $service): array
    {
        $defaults = config('website_service_presentation.presentation_by_key.'.self::formatKey($service), []);
        $over = is_array($service->public_sections) ? $service->public_sections : [];

        /** @var array<string, mixed> $merged */
        $merged = array_replace_recursive($defaults, array_filter($over, static fn ($v) => $v !== null && $v !== ''));

        $cmsImage = null;
        if (! empty($service->image_path)) {
            $cmsImage = str_starts_with((string) $service->image_path, 'http')
                ? $service->image_path
                : asset('storage/'.ltrim((string) $service->image_path, '/'));
        }

        $image = $merged['explorer_image_override'] ?? $merged['fallback_image'] ?? $cmsImage;
        if (! is_string($image) || $image === '') {
            $image = 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80';
        }

        /** @var list<string>|mixed $ideal */
        $ideal = $merged['ideal_use_cases'] ?? [];
        if (is_string($ideal)) {
            $idealLines = preg_split('/\r\n|\r|\n/', $ideal);
            $ideal = array_values(array_filter(array_map('trim', is_array($idealLines) ? $idealLines : [])));
        }

        /** @var list<string>|mixed $objs */
        $objs = $merged['campaign_objectives'] ?? [];
        if (is_string($objs)) {
            $lines = preg_split('/\r\n|\r|\n/', $objs);
            $objs = array_values(array_filter(array_map('trim', is_array($lines) ? $lines : [])));
        }

        $showcaseSlug = $merged['showcase_slug'] ?? null;

        return [
            'id' => $service->id,
            'title' => $service->title,
            'slug' => $service->slug,
            'showcase_slug' => is_string($showcaseSlug) && $showcaseSlug !== '' ? $showcaseSlug : null,
            'summary' => $service->summary,
            'kicker' => (string) ($merged['kicker'] ?? ''),
            'headline' => (string) ($merged['headline'] ?? $service->title),
            'positioning' => (string) ($merged['positioning'] ?? ''),
            'example' => (string) ($merged['example'] ?? ''),
            'ideal_use_cases' => is_array($ideal) ? $ideal : [],
            'campaign_objectives' => is_array($objs) ? $objs : [],
            'impact_line' => (string) ($merged['impact_line'] ?? ''),
            'cta_primary_label' => (string) ($merged['cta_primary_label'] ?? 'Brief this product'),
            'cta_secondary_label' => (string) ($merged['cta_secondary_label'] ?? 'Specifications'),
            'image' => $image,
            'format_key' => self::formatKey($service),
        ];
    }

    /**
     * @param  iterable<Service>  $services
     * @return list<array<string, mixed>>
     */
    public static function map(iterable $services): array
    {
        $out = [];
        foreach ($services as $service) {
            $out[] = self::row($service);
        }

        return $out;
    }

    private static function formatKey(Service $service): string
    {
        $key = $service->format_key;
        if (is_string($key) && $key !== '') {
            return $key;
        }

        $t = strtolower($service->title);

        foreach (
            [
                'street_light' => ['street light', 'street pole', 'pole', 'streetlight'],
                'billboard' => ['billboard', 'mega', 'spectacular'],
                'pavement' => ['pavement', 'walkway'],
                'office' => ['office', 'workspace', 'interior branding'],
                'activation' => ['activation', 'roadshow', 'road show'],
                'rollup' => ['roll-up', 'rollup', 'pull-up'],
            ] as $fk => $needles
        ) {
            foreach ($needles as $needle) {
                if (str_contains($t, $needle)) {
                    return $fk;
                }
            }
        }

        return 'generic';
    }
}
