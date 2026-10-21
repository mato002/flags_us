<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Services\Cms\WebsitePageSectionsService;
use App\Services\Seo\WebsiteSeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class CmsPageController extends Controller
{
    /**
     * @return array<int, string>
     */
    private function allowed(): array
    {
        return app(WebsitePageSectionsService::class)->allowedSlugs();
    }

    public function index(): View
    {
        $slugs = $this->allowed();
        $pages = CmsPage::query()
            ->whereIn('slug', $slugs)
            ->get()
            ->keyBy('slug');

        return view('admin.cms.pages.index', [
            'slugs' => $slugs,
            'pages' => $pages,
        ]);
    }

    public function editWhoWeAre(): View
    {
        return $this->editPublicPage('who-we-are');
    }

    public function updateWhoWeAre(Request $request): RedirectResponse
    {
        return $this->updatePublicPage($request, 'who-we-are');
    }

    public function edit(string $slug): View
    {
        return $this->editPublicPage($slug);
    }

    public function update(Request $request, string $slug): RedirectResponse
    {
        return $this->updatePublicPage($request, $slug);
    }

    public function editPublicPage(string $slug): View
    {
        abort_unless(in_array($slug, $this->allowed(), true), 404);

        /** @var array<string, mixed> $defaults */
        $defaults = config('website_page_sections.pages.'.$slug, []);
        if (! is_array($defaults)) {
            $defaults = [];
        }

        $page = CmsPage::query()->firstOrCreate(
            ['slug' => $slug],
            [
                'title' => (string) (($defaults['_meta']['default_title'] ?? '') ?: $slug),
                'body' => '',
                'meta_description' => ($defaults['_meta']['default_meta'] ?? null) ?: null,
                'og_image' => null,
                'is_published' => true,
                'sections' => null,
            ]
        );

        $sectionsPretty = '';
        if (is_array($page->sections) && $page->sections !== []) {
            $sectionsPretty = (string) json_encode($page->sections, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        /** @var array<string, mixed> $resolvedSections */
        $resolvedSections = app(WebsitePageSectionsService::class)->resolve($slug, true)['sections'] ?? [];
        $fullSectionsPretty = (string) json_encode($resolvedSections, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return view('admin.cms.pages.edit', [
            'slug' => $slug,
            'page' => $page,
            'sectionsPretty' => $sectionsPretty,
            'fullSectionsPretty' => $fullSectionsPretty,
            'previewUrl' => app(WebsiteSeoService::class)->publicUrlForCmsSlug($slug),
        ]);
    }

    public function updatePublicPage(Request $request, string $slug): RedirectResponse
    {
        abort_unless(in_array($slug, $this->allowed(), true), 404);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:2048'],
            'robots' => ['nullable', 'string', 'max:120'],
            'canonical_url' => ['nullable', 'string', 'max:2048'],
            'is_published' => ['nullable', 'boolean'],
            'sections_json' => ['nullable', 'string'],
        ]);

        $sections = [];
        if (! empty(trim((string) ($validated['sections_json'] ?? '')))) {
            try {
                $decoded = json_decode(trim((string) $validated['sections_json']), true, flags: JSON_THROW_ON_ERROR);
                $sections = is_array($decoded) ? $decoded : [];
            } catch (Throwable $e) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['sections_json' => 'Sections must be valid JSON: '.$e->getMessage()]);
            }
        }

        $payload = [
            'title' => $validated['title'],
            'body' => $validated['body'] ?? '',
            'meta_description' => $validated['meta_description'] ?? null,
            'og_image' => ($validated['og_image'] ?? '') !== '' ? $validated['og_image'] : null,
            'is_published' => filter_var($request->input('is_published', true), FILTER_VALIDATE_BOOLEAN),
            'sections' => $sections === [] ? null : $sections,
        ];

        if (Schema::hasColumn('cms_pages', 'robots')) {
            $payload['robots'] = ($validated['robots'] ?? '') !== '' ? $validated['robots'] : null;
        }
        if (Schema::hasColumn('cms_pages', 'canonical_url')) {
            $payload['canonical_url'] = ($validated['canonical_url'] ?? '') !== '' ? $validated['canonical_url'] : null;
        }

        CmsPage::query()->updateOrCreate(
            ['slug' => $slug],
            $payload
        );

        WebsitePageSectionsService::forgetCache($slug);

        return redirect()
            ->route('admin.cms.pages.edit', ['slug' => $slug])
            ->with('status', 'Website page sections saved.');
    }

    public function uploadMedia(Request $request): JsonResponse
    {
        $request->validate([
            'asset' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,webp,gif,svg,pdf'],
        ]);

        $file = $request->file('asset');
        $safeName = Str::slug(pathinfo((string) $file->getClientOriginalName(), PATHINFO_FILENAME));
        $ext = strtolower((string) $file->getClientOriginalExtension());
        $name = trim($safeName) !== '' ? $safeName.'-'.time().'.'.$ext : Str::random(12).'.'.$ext;

        $stored = $file->storeAs('cms-pages', $name, 'public');

        return response()->json([
            'path' => $stored,
            'url' => asset('storage/'.$stored),
            'is_pdf' => $ext === 'pdf',
        ]);
    }
}
