@extends('layouts.admin')

@section('title', 'Quote requests | Effective Media Ops')
@section('header', 'Quote pipeline')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Quote requests',
        'description' => 'Track inbound outdoor media briefs from the public site and planners. Export respects current filters.',
        'breadcrumb' => 'Sales · Quotes',
    ])

    <div class="mb-4 flex flex-wrap items-center gap-2">
        <a href="{{ route('quote') }}" target="_blank" rel="noopener noreferrer" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Generate quote (public form)</a>
        <a href="{{ route('admin.quotes.export', request()->query()) }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white">Export CSV</a>
    </div>

    <form method="GET" class="mb-4 grid gap-3 rounded-lg border border-slate-200 bg-white p-4 text-sm dark:border-white/10 dark:bg-white/5 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-[#cbbfb6]">Search</label>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Name, phone, county…" class="mt-1 w-full rounded border border-slate-300 px-2 py-1.5 dark:border-white/20 dark:bg-transparent">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-[#cbbfb6]">Status</label>
            @php($statusOpts = ['' => 'All', 'new' => 'New', 'contacted' => 'Contacted', 'qualified' => 'Qualified', 'quoted' => 'Quoted', 'won' => 'Won', 'lost' => 'Lost'])
            <select name="status" class="mt-1 w-full rounded border border-slate-300 px-2 py-1.5 dark:border-white/20 dark:bg-transparent">
                @foreach ($statusOpts as $val => $label)
                    <option value="{{ $val }}" @selected(request('status') === (string) $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-[#cbbfb6]">County</label>
            <input type="text" name="county" value="{{ request('county') }}" class="mt-1 w-full rounded border border-slate-300 px-2 py-1.5 dark:border-white/20 dark:bg-transparent">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-[#cbbfb6]">Media focus</label>
            <input type="text" name="service" value="{{ request('service') }}" placeholder="matches media_type" class="mt-1 w-full rounded border border-slate-300 px-2 py-1.5 dark:border-white/20 dark:bg-transparent">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-[#cbbfb6]">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="mt-1 w-full rounded border border-slate-300 px-2 py-1.5 dark:border-white/20 dark:bg-transparent">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-[#cbbfb6]">To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="mt-1 w-full rounded border border-slate-300 px-2 py-1.5 dark:border-white/20 dark:bg-transparent">
        </div>
        <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-2">
            <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-xs font-semibold text-white dark:bg-[#f04a2a] dark:text-white">Apply</button>
            <a href="{{ route('admin.quotes.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold dark:border-white/20 dark:text-white">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-600 dark:bg-[#221b1b] dark:text-[#dfd5cd]">
                <tr>
                    <th class="px-4 py-3 font-semibold">Lead</th>
                    <th class="px-4 py-3 font-semibold">Contact</th>
                    <th class="px-4 py-3 font-semibold">Campaign</th>
                    <th class="px-4 py-3 font-semibold">Stage</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quotes as $quote)
                    <tr class="border-t border-slate-100 dark:border-white/10">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $quote->full_name }}</p>
                            <p class="text-xs text-slate-500">{{ $quote->company_name ?: 'No company captured' }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-700 dark:text-[#dfd5cd]">
                            <p>{{ $quote->phone }}</p>
                            <p class="text-xs">{{ $quote->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p>{{ $quote->location ?: ($quote->county ?: 'N/A') }}</p>
                            <p class="text-xs text-slate-500">{{ $quote->media_type ?: 'Undeclared' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-[#f04a2a]/15 px-2 py-0.5 text-xs font-semibold text-[#8b1e1a]">{{ $quote->status ?? 'new' }}</span>
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.quotes.show', $quote) }}" class="rounded border border-slate-300 px-2 py-1 text-xs font-semibold hover:bg-slate-50 dark:border-white/20 dark:hover:bg-white/10">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-[#a89f98]">No quote requests for this lens yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $quotes->links() }}
    </div>
@endsection
