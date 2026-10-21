<?php

namespace App\Http\Controllers\Admin\Media;

use App\Http\Controllers\Controller;
use App\Models\BoardInventoryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BoardInventoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = BoardInventoryItem::query()->latest('id');

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $like = '%'.$search.'%';
            $query->where(fn ($q) => $q->where('location', 'like', $like)
                ->orWhere('reference_code', 'like', $like));
        }

        if ($request->filled('availability')) {
            $query->where('availability_status', $request->query('availability'));
        }

        return view('admin.media.boards.index', [
            'boards' => $query->paginate(25)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.media.boards.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('board-photos', 'public');
        }
        BoardInventoryItem::query()->create($data);

        return redirect()->route('admin.media.boards.index')->with('status', 'Board added.');
    }

    public function show(BoardInventoryItem $board): View
    {
        return view('admin.media.boards.show', ['board' => $board]);
    }

    public function edit(BoardInventoryItem $board): View
    {
        return view('admin.media.boards.edit', ['board' => $board]);
    }

    public function update(Request $request, BoardInventoryItem $board): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('photo')) {
            if ($board->photo_path) {
                Storage::disk('public')->delete($board->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('board-photos', 'public');
        }
        $board->update($data);

        return redirect()->route('admin.media.boards.index')->with('status', 'Board updated.');
    }

    public function destroy(BoardInventoryItem $board): RedirectResponse
    {
        if ($board->photo_path) {
            Storage::disk('public')->delete($board->photo_path);
        }
        $board->delete();

        return redirect()->route('admin.media.boards.index')->with('status', 'Board removed.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'reference_code' => ['nullable', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
            'size' => ['nullable', 'string', 'max:120'],
            'illumination' => ['nullable', 'string', 'max:120'],
            'traffic_notes' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'availability_status' => ['nullable', 'string', 'max:80'],
            'maintenance_status' => ['nullable', 'string', 'max:80'],
            'photo' => ['nullable', 'image', 'max:10240'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        return [
            'reference_code' => $validated['reference_code'] ?? null,
            'location' => $validated['location'],
            'size' => $validated['size'] ?? null,
            'illumination' => $validated['illumination'] ?? null,
            'traffic_notes' => $validated['traffic_notes'] ?? null,
            'price' => $validated['price'] ?? null,
            'availability_status' => $validated['availability_status'] ?? 'available',
            'maintenance_status' => $validated['maintenance_status'] ?? 'ok',
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ];
    }
}
