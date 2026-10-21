<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\PortfolioItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PortfolioItemController extends Controller
{
    public function index(): View
    {
        return view('admin.cms.portfolio.index', [
            'items' => PortfolioItem::query()->orderBy('sort_order')->orderByDesc('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.cms.portfolio.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('cms-portfolio', 'public');
        }

        PortfolioItem::query()->create([
            ...$validated,
            'image_path' => $imagePath,
            'featured' => (bool) ($validated['featured'] ?? false),
            'is_published' => (bool) ($validated['is_published'] ?? false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.cms.portfolio.index')->with('status', 'Portfolio item created.');
    }

    public function show(PortfolioItem $portfolio): View
    {
        return view('admin.cms.portfolio.show', ['item' => $portfolio]);
    }

    public function edit(PortfolioItem $portfolio): View
    {
        return view('admin.cms.portfolio.edit', [
            'item' => $portfolio,
        ]);
    }

    public function update(Request $request, PortfolioItem $portfolio): RedirectResponse
    {
        $validated = $this->validated($request);

        $data = [
            ...$validated,
            'featured' => (bool) ($validated['featured'] ?? false),
            'is_published' => (bool) ($validated['is_published'] ?? false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('image')) {
            if ($portfolio->image_path && ! str_starts_with($portfolio->image_path, 'http')) {
                Storage::disk('public')->delete($portfolio->image_path);
            }
            $data['image_path'] = $request->file('image')->store('cms-portfolio', 'public');
        }

        $portfolio->update($data);

        return redirect()->route('admin.cms.portfolio.index')->with('status', 'Portfolio item updated.');
    }

    public function destroy(PortfolioItem $portfolio): RedirectResponse
    {
        if ($portfolio->image_path && ! str_starts_with($portfolio->image_path, 'http')) {
            Storage::disk('public')->delete($portfolio->image_path);
        }
        $portfolio->delete();

        return redirect()->route('admin.cms.portfolio.index')->with('status', 'Portfolio item deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'campaign_location' => ['nullable', 'string', 'max:255'],
            'media_type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:8192'],
        ]);
    }
}
