<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Seo\WebsiteSeoService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(WebsiteSeoService $seo): Response
    {
        $entries = $seo->sitemapEntries();

        $xml = view('seo.sitemap', ['entries' => $entries])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
