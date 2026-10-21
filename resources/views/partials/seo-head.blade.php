@php
    use Illuminate\Support\Facades\View as ViewFacade;
    use App\Services\Seo\WebsiteSeoService;

    /** @var array<string, mixed> $pageSeo */
    $pageSeo = is_array($pageSeo ?? null) ? $pageSeo : [];
    $seoService = app(WebsiteSeoService::class);

    $brandFallback = (string) ($portalBrandName ?? 'Effective Media');
    $portalTitleDefault = ($portalWebsite['meta_title'] ?? '') !== ''
        ? (string) $portalWebsite['meta_title']
        : $brandFallback;
    $portalDescDefault = ($portalMetaDescription ?? '') !== ''
        ? (string) $portalMetaDescription
        : (string) config('seo.default_description');

    $yieldTitle = trim((string) ViewFacade::yieldContent('title', ''));
    $yieldDescription = trim((string) ViewFacade::yieldContent('meta_description', ''));
    $yieldOgImage = trim((string) ViewFacade::yieldContent('og_image', ''));
    $yieldCanonical = trim((string) ViewFacade::yieldContent('canonical', ''));
    $yieldRobots = trim((string) ViewFacade::yieldContent('robots', ''));
    $yieldOgTitle = trim((string) ViewFacade::yieldContent('og_title', ''));
    $yieldOgDescription = trim((string) ViewFacade::yieldContent('og_description', ''));
    $yieldOgType = trim((string) ViewFacade::yieldContent('og_type', ''));

    $title = trim((string) ($pageSeo['title'] ?? ''));
    if ($title === '' || strtolower($title) === 'null') {
        $title = $yieldTitle !== '' && strtolower($yieldTitle) !== 'null' ? $yieldTitle : $portalTitleDefault;
    }

    $description = trim((string) ($pageSeo['description'] ?? ''));
    if ($description === '') {
        $description = $yieldDescription !== '' ? $yieldDescription : $portalDescDefault;
    }

    $ogImage = trim((string) ($pageSeo['og_image'] ?? ''));
    if ($ogImage === '') {
        $ogImage = $yieldOgImage !== '' ? $yieldOgImage : (string) ($portalOgImage ?? '');
    }
    $ogImage = $seoService->absoluteUrl($ogImage);

    $canonical = trim((string) ($pageSeo['canonical'] ?? ''));
    if ($canonical === '') {
        $canonical = $yieldCanonical !== '' ? $yieldCanonical : $seoService->canonicalForRequest();
    }
    $canonical = $seoService->absoluteUrl($canonical);

    $robots = trim((string) ($pageSeo['robots'] ?? ''));
    if ($robots === '') {
        $robots = $yieldRobots !== '' ? $yieldRobots : (string) config('seo.default_robots');
    }

    $ogTitle = trim((string) ($pageSeo['og_title'] ?? ''));
    if ($ogTitle === '') {
        $ogTitle = $yieldOgTitle !== '' ? $yieldOgTitle : $title;
    }

    $ogDescription = trim((string) ($pageSeo['og_description'] ?? ''));
    if ($ogDescription === '') {
        $ogDescription = $yieldOgDescription !== '' ? $yieldOgDescription : $description;
    }

    $ogType = trim((string) ($pageSeo['og_type'] ?? ''));
    if ($ogType === '') {
        $ogType = $yieldOgType !== '' ? $yieldOgType : 'website';
    }

    $keywords = trim((string) ($pageSeo['keywords'] ?? ($portalMetaKeywords ?? '')));
    $siteName = $brandFallback;
    $twitterCard = (string) config('seo.twitter_card', 'summary_large_image');
    $ogLocale = (string) config('seo.og_locale', 'en_KE');

    $verificationGoogle = trim((string) ($portalWebsite['google_site_verification'] ?? ''));
    $verificationBing = trim((string) ($portalWebsite['bing_site_verification'] ?? ''));

    $jsonLdBlocks = is_array($seoJsonLd ?? null) ? $seoJsonLd : $seoService->organizationJsonLd();
@endphp
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
@if ($keywords !== '')
    <meta name="keywords" content="{{ $keywords }}">
@endif
<meta name="robots" content="{{ $robots }}">
<meta name="googlebot" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ $ogLocale }}">
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
<meta property="og:url" content="{{ $canonical }}">
@if ($ogImage !== '')
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:alt" content="{{ $ogTitle }}">
@endif

<meta name="twitter:card" content="{{ $twitterCard }}">
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDescription }}">
@if ($ogImage !== '')
    <meta name="twitter:image" content="{{ $ogImage }}">
@endif

@if ($verificationGoogle !== '')
    <meta name="google-site-verification" content="{{ $verificationGoogle }}">
@endif
@if ($verificationBing !== '')
    <meta name="msvalidate.01" content="{{ $verificationBing }}">
@endif

@foreach ($jsonLdBlocks as $block)
    @if (is_array($block) && $block !== [])
        <script type="application/ld+json">{!! json_encode($block, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
@endforeach
@stack('json_ld')
