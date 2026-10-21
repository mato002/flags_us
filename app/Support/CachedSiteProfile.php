<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

final class CachedSiteProfile
{
    /**
     * Mostly static brochure/coverage YAML merged into config — safe to memoize briefly in production.
     *
     * @return array<string, mixed>
     */
    public static function content(): array
    {
        return Cache::remember(
            'effective_media_site_profile_v1',
            now()->addHour(),
            static function (): array {
                $raw = config('effective_media_profile');

                return is_array($raw) ? $raw : [];
            }
        );
    }
}
