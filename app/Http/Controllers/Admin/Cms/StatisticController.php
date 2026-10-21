<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.cms.statistics.index', [
            'statistics' => Statistic::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.cms.statistics.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        Statistic::query()->create([
            ...$validated,
            'is_published' => (bool) ($validated['is_published'] ?? false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.cms.statistics.index')->with('status', 'Statistic created.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Statistic $statistic): View
    {
        return view('admin.cms.statistics.edit', [
            'statistic' => $statistic,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Statistic $statistic): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $statistic->update([
            ...$validated,
            'is_published' => (bool) ($validated['is_published'] ?? false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.cms.statistics.index')->with('status', 'Statistic updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Statistic $statistic): RedirectResponse
    {
        $statistic->delete();

        return redirect()->route('admin.cms.statistics.index')->with('status', 'Statistic deleted.');
    }
}
