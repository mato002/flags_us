@extends('layouts.admin')

@section('title', 'Open leads | Effective Media Ops')
@section('header', 'Sales leads')

@section('content')
    @include('admin.partials.page-header', [
        'title' => $pageTitle,
        'description' => $pageSubtitle,
        'breadcrumb' => 'Sales · Leads',
    ])

    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.sales.leads.export', request()->query()) }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white">Export open leads CSV</a>
        <a href="{{ route('admin.quotes.index') }}" class="text-sm font-semibold text-[#8b1e1a] underline">View full quote pipeline</a>
    </div>

    <form method="GET" class="mb-4 rounded-lg border border-slate-200 bg-white p-4 text-sm dark:border-white/10 dark:bg-white/5">
        <label class="block text-xs font-semibold text-slate-500 dark:text-[#cbbfb6]">Keyword</label>
        <div class="mt-1 flex flex-wrap gap-2">
            <input type="search" name="search" value="{{ request('search') }}" class="min-w-0 flex-1 rounded border border-slate-300 px-2 py-1.5 dark:border-white/20 dark:bg-transparent dark:text-white">
            <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-xs font-semibold text-white dark:bg-[#f04a2a]">Search</button>
        </div>
    </form>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-600 dark:bg-[#221b1b] dark:text-[#dfd5cd]">
                <tr>
                    <th class="px-4 py-3 font-semibold">Lead</th>
                    <th class="px-4 py-3 font-semibold">Contact</th>
                    <th class="px-4 py-3 font-semibold">Focus</th>
                    <th class="px-4 py-3 font-semibold">Stage</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quotes as $quote)
                    <tr class="border-t border-slate-100 dark:border-white/10">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $quote->full_name }}</p>
                            <p class="text-xs text-slate-500">{{ $quote->company_name ?: 'No company' }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-700 dark:text-[#dfd5cd]">
                            <p>{{ $quote->phone }}</p>
                            <p class="text-xs">{{ $quote->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            {{ $quote->county ?: ($quote->location ?: '—') }}<br>
                            <span class="text-xs text-slate-500">{{ $quote->media_type ?: 'Undeclared' }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-[#8b1e1a]">{{ $quote->status }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.quotes.show', $quote) }}" class="rounded border border-slate-300 px-2 py-1 text-xs font-semibold dark:border-white/20">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">No open quote leads right now.</td>
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
