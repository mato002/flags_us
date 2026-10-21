<?php

namespace App\Http\Controllers\Admin\Media;

use App\Http\Controllers\Controller;
use App\Models\CoverageSite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoverageSiteController extends Controller
{
    public function index(Request $request): View
    {
        $query = CoverageSite::query()->orderBy('county')->orderBy('town');

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $like = '%'.$search.'%';
            $query->where(fn ($q) => $q->where('county', 'like', $like)
                ->orWhere('town', 'like', $like)
                ->orWhere('site_name', 'like', $like));
        }

        if ($request->filled('county')) {
            $query->where('county', $request->query('county'));
        }

        return view('admin.media.coverage-sites.index', [
            'sites' => $query->paginate(25)->withQueryString(),
            'counties' => CoverageSite::query()->distinct()->orderBy('county')->pluck('county'),
        ]);
    }

    public function create(): View
    {
        return view('admin.media.coverage-sites.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        CoverageSite::query()->create($validated);

        return redirect()->route('admin.media.coverage-sites.index')->with('status', 'Coverage site created.');
    }

    public function show(CoverageSite $coverage_site): View
    {
        return view('admin.media.coverage-sites.show', ['site' => $coverage_site]);
    }

    public function edit(CoverageSite $coverage_site): View
    {
        return view('admin.media.coverage-sites.edit', ['site' => $coverage_site]);
    }

    public function update(Request $request, CoverageSite $coverage_site): RedirectResponse
    {
        $coverage_site->update($this->validated($request));

        return redirect()->route('admin.media.coverage-sites.index')->with('status', 'Coverage site updated.');
    }

    public function destroy(CoverageSite $coverage_site): RedirectResponse
    {
        $coverage_site->delete();

        return redirect()->route('admin.media.coverage-sites.index')->with('status', 'Coverage site deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'county' => ['required', 'string', 'max:255'],
            'town' => ['required', 'string', 'max:255'],
            'site_name' => ['required', 'string', 'max:255'],
            'poles_count' => ['nullable', 'integer', 'min:0'],
            'media_type' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        return [
            'county' => $validated['county'],
            'town' => $validated['town'],
            'site_name' => $validated['site_name'],
            'poles_count' => $validated['poles_count'] ?? 0,
            'media_type' => $validated['media_type'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ];
    }
}
