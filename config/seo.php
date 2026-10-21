<?php

return [
    'default_description' => 'Modern outdoor advertising and branding infrastructure for Kenya and East Africa — street lights, billboards, pavement ads, and campaign planning.',

    'default_robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',

    'noindex_robots' => 'noindex, nofollow',

    'og_locale' => 'en_KE',

    'twitter_card' => 'summary_large_image',

    /*
    |--------------------------------------------------------------------------
    | Organization defaults (JSON-LD)
    |--------------------------------------------------------------------------
    | Contact phones/emails/social are merged at runtime from portal settings.
    */
    'organization' => [
        'name' => 'Effective Media',
        'legal_name' => 'Effective Media',
        'description' => 'Outdoor advertising infrastructure across Kenya — street light boxes, billboards, pavement ads, branding, and campaign planning.',
        'area_served' => 'Kenya',
        'same_as' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Public routes included in sitemap.xml
    |--------------------------------------------------------------------------
    */
    'sitemap_static_routes' => [
        'home',
        'who-we-are',
        'what-we-do',
        'portfolio',
        'coverage',
        'smart-campaign-planner',
        'faqs',
        'contact-us',
        'quote',
    ],

    'sitemap_service_slugs' => [
        'street-light-advertising',
        'billboards',
        'pavement-ads',
        'office-branding',
        'activations-roadshows',
        'roll-up-banners',
    ],

    'sitemap_change_freq' => [
        'home' => 'weekly',
        'default' => 'monthly',
        'landing' => 'monthly',
        'service' => 'monthly',
    ],

    'sitemap_priority' => [
        'home' => '1.0',
        'quote' => '0.9',
        'what-we-do' => '0.9',
        'coverage' => '0.8',
        'default' => '0.7',
        'landing' => '0.6',
        'service' => '0.8',
    ],
];
