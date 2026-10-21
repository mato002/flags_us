<?php

namespace App\Services\Cms;

use App\Models\CmsPage;
use Illuminate\Support\Facades\Cache;

final class WebsitePageSectionsService
{
    private function cacheKey(string $slug): string
    {
        return 'website_page_sections_'.$slug.'_v2';
    }

    /**
     * @return array<int, string>
     */
    public function allowedSlugs(): array
    {
        $slugs = config('website_page_sections.slugs');

        return is_array($slugs) ? array_values(array_filter(array_map('strval', $slugs))) : [];
    }

    public static function forgetCache(?string $slug = null): void
    {
        $instance = app(self::class);
        if ($slug !== null && $slug !== '') {
            Cache::forget($instance->cacheKey($slug));

            return;
        }
        foreach ($instance->allowedSlugs() as $s) {
            Cache::forget($instance->cacheKey($s));
        }
    }

    /**
     * @return array{
     *     slug: string,
     *     title: string,
     *     meta_description: ?string,
     *     og_image: ?string,
     *     robots: ?string,
     *     canonical_url: ?string,
     *     is_published: bool,
     *     body: ?string,
     *     sections: array<string, mixed>
     * }
     */
    public function resolve(string $slug, bool $allowDraft = false): array
    {
        abort_unless(in_array($slug, $this->allowedSlugs(), true), 404);

        $publishState = CmsPage::query()
            ->where('slug', $slug)
            ->first(['is_published']);

        if ($publishState && ! $publishState->is_published && ! $allowDraft) {
            abort(404);
        }

        return Cache::remember($this->cacheKey($slug), now()->addMinutes(5), function () use ($slug): array {
            $defaultsWrap = config('website_page_sections.pages.'.$slug, []);
            if (! is_array($defaultsWrap)) {
                $defaultsWrap = [];
            }
            /** @var array<string, mixed> $defaults */
            $defaults = $defaultsWrap;
            $meta = isset($defaults['_meta']) && is_array($defaults['_meta']) ? $defaults['_meta'] : [];

            unset($defaults['_meta']);

            $page = CmsPage::query()->where('slug', $slug)->first();
            $stored = is_array($page?->sections) ? $page->sections : [];
            /** @var array<string, mixed> $storedSections */
            $storedSections = $stored;

            $merged = $this->mergeRecursiveAssoc($defaults, $storedSections);

            $robots = null;
            $canonical = null;
            if ($page) {
                $attrs = $page->getAttributes();
                if (array_key_exists('robots', $attrs) && is_string($attrs['robots']) && $attrs['robots'] !== '') {
                    $robots = $attrs['robots'];
                }
                if (array_key_exists('canonical_url', $attrs) && is_string($attrs['canonical_url']) && $attrs['canonical_url'] !== '') {
                    $canonical = $attrs['canonical_url'];
                }
            }

            return [
                'slug' => $slug,
                'title' => $page->title ?? (string) ($meta['default_title'] ?? ''),
                'meta_description' => $page->meta_description ?? (($meta['default_meta'] ?? null) ?: null),
                'og_image' => (is_string($page?->og_image) && $page?->og_image !== '') ? $page->og_image : null,
                'robots' => $robots,
                'canonical_url' => $canonical,
                'is_published' => (bool) ($page?->is_published ?? true),
                'body' => $page->body ?? null,
                'sections' => $merged,
            ];
        });
    }

    /**
     * @param  array<string, mixed>  $base
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function mergeRecursiveAssoc(array $base, array $overrides): array
    {
        foreach ($overrides as $key => $value) {
            if (! is_string($key)) {
                continue;
            }
            if (! array_key_exists($key, $base)) {
                if ($value !== null) {
                    $base[$key] = $value;
                }

                continue;
            }
            if (is_array($value) && is_array($base[$key])) {
                if ($this->isAssocArray($value) && $this->isAssocArray($base[$key])) {
                    /** @var array<string, mixed> $childBase */
                    $childBase = $base[$key];
                    /** @var array<string, mixed> $childVal */
                    $childVal = $value;
                    $base[$key] = $this->mergeRecursiveAssoc($childBase, $childVal);
                } else {
                    /** @var list<mixed>|array<int|string, mixed> $replacement */
                    $replacement = $value !== [] ? $value : $base[$key];
                    $base[$key] = $replacement;
                }
            } elseif ($value !== null && $value !== '' && (! is_array($value) || $value !== [])) {
                $base[$key] = $value;
            }
        }

        return $base;
    }

    /**
     * @param  array<mixed>  $arr
     */
    private function isAssocArray(array $arr): bool
    {
        if ($arr === []) {
            return true;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
