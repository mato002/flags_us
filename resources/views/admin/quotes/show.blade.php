@extends('layouts.admin')

@section('title', 'Quote #'.$quote->id.' | Effective Media Ops')
@section('header', 'Quote intelligence')

@section('content')
    @include('admin.partials.page-header', [
        'title' => $quote->full_name,
        'description' => ($quote->company_name ? $quote->company_name.' · ' : '').'Captured '.$quote->created_at?->diffForHumans(),
        'breadcrumb' => 'Sales · Quotes · #'.$quote->id,
    ])

    <div class="grid gap-4 lg:grid-cols-2">
        <div class="rounded-lg border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-[#cbbfb6]">Contact</h2>
            <div class="mt-3 space-y-2 text-sm text-slate-700 dark:text-[#dfd5cd]">
                <p><span class="font-semibold text-slate-900 dark:text-white">Name:</span> {{ $quote->full_name }}</p>
                <p><span class="font-semibold text-slate-900 dark:text-white">Company:</span> {{ $quote->company_name ?: 'N/A' }}</p>
                <p><span class="font-semibold text-slate-900 dark:text-white">Phone:</span> {{ $quote->phone }}</p>
                <p><span class="font-semibold text-slate-900 dark:text-white">Email:</span> {{ $quote->email }}</p>
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-[#cbbfb6]">Campaign fingerprint</h2>
            <div class="mt-3 space-y-2 text-sm text-slate-700 dark:text-[#dfd5cd]">
                <p><span class="font-semibold text-slate-900 dark:text-white">County:</span> {{ $quote->county ?: 'N/A' }}</p>
                <p><span class="font-semibold text-slate-900 dark:text-white">Location:</span> {{ $quote->location ?: 'N/A' }}</p>
                <p><span class="font-semibold text-slate-900 dark:text-white">Media type:</span> {{ $quote->media_type ?: 'N/A' }}</p>
                <p><span class="font-semibold text-slate-900 dark:text-white">Budget:</span> {{ $quote->budget_range ?: 'N/A' }}</p>
                <p><span class="font-semibold text-slate-900 dark:text-white">Source:</span> {{ $quote->source }}</p>
                <p><span class="font-semibold text-slate-900 dark:text-white">Received:</span> {{ $quote->created_at?->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="mt-4 rounded-lg border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-[#cbbfb6]">Client storyline</h2>
        <p class="mt-3 whitespace-pre-line text-sm text-slate-700 dark:text-[#dfd5cd]">{{ $quote->message ?: 'No supporting notes provided.' }}</p>
    </div>

    <form method="POST" action="{{ route('admin.quotes.update', $quote) }}" class="mt-6 rounded-lg border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
        @csrf
        @method('PATCH')
        <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-[#cbbfb6]">Operations desk</h2>
        <div class="mt-4 grid gap-4 lg:grid-cols-3">
            <div>
                <label class="text-xs font-semibold text-slate-500 dark:text-[#cbbfb6]">Status</label>
                <select name="status" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 text-sm dark:border-white/20 dark:bg-transparent dark:text-white">
                    @foreach (['new' => 'New', 'contacted' => 'Contacted', 'qualified' => 'Qualified', 'quoted' => 'Quoted', 'won' => 'Won', 'lost' => 'Lost'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('status', $quote->status) === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-500 dark:text-[#cbbfb6]">Assign teammate</label>
                <select name="assigned_to" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 text-sm dark:border-white/20 dark:bg-transparent dark:text-white">
                    <option value="">—</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected((int) old('assigned_to', $quote->assigned_to) === $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Save desk updates</button>
            </div>
        </div>
        <div class="mt-4">
            <label class="text-xs font-semibold text-slate-500 dark:text-[#cbbfb6]">Internal notes</label>
            <textarea name="internal_notes" rows="5" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 text-sm dark:border-white/20 dark:bg-transparent dark:text-white">{{ old('internal_notes', $quote->internal_notes) }}</textarea>
        </div>
    </form>

    <div class="mt-6 flex flex-wrap gap-2">
        <a href="{{ route('quote', array_filter([
                'location' => $quote->location,
                'county' => $quote->county,
                'industry' => $quote->industry,
                'campaign_objective' => $quote->campaign_objective,
                'target_audience' => $quote->target_audience,
                'budget_range' => $quote->budget_range,
                'media_type' => $quote->media_type,
                'duration' => $quote->campaign_duration,
            ], fn ($v) => $v !== null && $v !== '')) }}" target="_blank" rel="noopener" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold dark:border-white/20 dark:text-white dark:hover:bg-white/10">Convert pathway · open estimator</a>
        <a href="{{ route('admin.quotes.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold dark:border-white/20 dark:text-white dark:hover:bg-white/10">Back to list</a>
        <form method="POST" action="{{ route('admin.quotes.destroy', $quote) }}" class="inline" onsubmit="return confirm('Archive/delete this inbound request permanently?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-md border border-rose-300 px-4 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50 dark:border-rose-500/40 dark:text-rose-200">Delete</button>
        </form>
    </div>
@endsection
