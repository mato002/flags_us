<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $layoutTitle ?: ('Sign In | '.($portalBrandName ?? 'Effective Media').' Operations Portal') }}</title>
    @if (! empty($portalFaviconHref))
        <link rel="icon" href="{{ $portalFaviconHref }}">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-[#1a1614] bg-[#0f0c0d] overflow-x-hidden">
    {{ $slot }}
</body>
</html>
