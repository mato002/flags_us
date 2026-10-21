<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\CmsDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CmsDocumentController extends Controller
{
    public function index(Request $request): View
    {
        $query = CmsDocument::query()->orderBy('sort_order')->orderByDesc('id');

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $like = '%'.$search.'%';
            $query->where(fn ($q) => $q->where('title', 'like', $like)->orWhere('description', 'like', $like));
        }

        if ($request->query('active') !== null && $request->query('active') !== '') {
            $query->where('is_active', (bool) $request->query('active'));
        }

        return view('admin.cms.library.index', [
            'documents' => $query->paginate(25)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.cms.library.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'max:20480', 'mimes:pdf,doc,docx'],
            'requires_lead_capture' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $path = $request->file('file')->store('cms-documents', 'public');

        CmsDocument::query()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $path,
            'requires_lead_capture' => (bool) ($validated['requires_lead_capture'] ?? false),
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.cms.library.index')->with('status', 'Document uploaded.');
    }

    public function edit(CmsDocument $library): View
    {
        return view('admin.cms.library.edit', ['document' => $library]);
    }

    public function update(Request $request, CmsDocument $library): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:20480', 'mimes:pdf,doc,docx'],
            'requires_lead_capture' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'requires_lead_capture' => (bool) ($validated['requires_lead_capture'] ?? false),
            'is_active' => (bool) ($validated['is_active'] ?? false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('file')) {
            if ($library->file_path) {
                Storage::disk('public')->delete($library->file_path);
            }
            $data['file_path'] = $request->file('file')->store('cms-documents', 'public');
        }

        $library->update($data);

        return redirect()->route('admin.cms.library.index')->with('status', 'Document updated.');
    }

    public function destroy(CmsDocument $library): RedirectResponse
    {
        if ($library->file_path) {
            Storage::disk('public')->delete($library->file_path);
        }
        $library->delete();

        return redirect()->route('admin.cms.library.index')->with('status', 'Document removed.');
    }
}
