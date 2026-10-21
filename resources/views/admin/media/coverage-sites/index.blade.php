@extends('layouts.admin')

@section('title', 'Coverage sites | Effective Media Ops')
@section('header', 'Coverage')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Coverage inventory',
        'description' => 'Pin street furniture and inventory nodes for counties and towns.',
        'breadcrumb' => 'Media coverage · Coverage sites',
    ])

    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.media.coverage-sites.create') }}" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Add site</a>
    </div>

    <form method="GET" class="mb-4 flex flex-wrap gap-3 rounded-lg border border-slate-200 bg-white p-4 text-sm dark:border-white/10 dark:bg-white/5">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="County, town, site…" class="min-w-0 flex-1 rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        <select name="county" class="min-w-[10rem] rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
            <option value="">All counties</option>
            @foreach ($counties as $c)
                <option value="{{ $c }}" @selected(request('county') === $c)>{{ $c }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-xs font-semibold text-white dark:bg-[#f04a2a]">Apply</button>
        <a href="{{ route('admin.media.coverage-sites.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold dark:border-white/20 dark:text-white">Reset</a>
    </form>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 dark:bg-[#221b1b] dark:text-[#dfd5cd]">
                <tr>
                    <th class="px-4 py-3 font-semibold">Site</th>
                    <th class="px-4 py-3 font-semibold">County · Town</th>
                    <th class="px-4 py-3 font-semibold">Poles</th>
                    <th class="px-4 py-3 font-semibold">Media</th>
                    <th class="px-4 py-3 font-semibold">Active</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sites as $site)
                    <tr class="border-t border-slate-200 dark:border-white/10">
                        <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">{{ $site->site_name }}</td>
                        <td class="px-4 py-3">{{ $site->county }} · {{ $site->town }}</td>
                        <td class="px-4 py-3">{{ $site->poles_count }}</td>
                        <td class="px-4 py-3">{{ $site->media_type ?: '—' }}</td>
                        <td class="px-4 py-3">{{ $site->is_active ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.media.coverage-sites.show', $site) }}" class="font-semibold text-slate-600 underline dark:text-[#f7b396]">View</a>
                            <a href="{{ route('admin.media.coverage-sites.edit', $site) }}" class="ml-2 font-semibold text-[#8b1e1a] underline">Edit</a>
                            <form method="POST" action="{{ route('admin.media.coverage-sites.destroy', $site) }}" class="inline" onsubmit="return confirm('Delete this site row?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-2 text-rose-600 underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-slate-500">No coverage rows yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $sites->links() }}</div>
@endsection
