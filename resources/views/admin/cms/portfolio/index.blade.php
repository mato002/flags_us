@extends('layouts.admin')

@section('title', 'Campaign assets | Effective Media Ops')
@section('header', 'Portfolio & campaign assets')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Campaign assets gallery',
        'description' => 'Manage published portfolio evidence, spotlight locations, media types and featured placements.',
        'breadcrumb' => 'CMS · Portfolio',
    ])

    <div class="mb-4 flex flex-wrap gap-3">
        <a href="{{ route('admin.cms.portfolio.create') }}" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-[#f04a2a]">Add campaign asset</a>
        <a href="{{ route('portfolio') }}" target="_blank" rel="noopener" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-white/20 dark:text-white dark:hover:bg-white/10">Preview live gallery</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 dark:bg-[#221b1b] dark:text-[#dfd5cd]">
                <tr>
                    <th class="px-4 py-3 font-semibold">Title</th>
                    <th class="px-4 py-3 font-semibold">Location</th>
                    <th class="px-4 py-3 font-semibold">Media</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr class="border-t border-slate-200 dark:border-white/10">
                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $item->title }}</td>
                        <td class="px-4 py-3 text-slate-700 dark:text-[#e6dad2]">{{ $item->campaign_location ?: '—' }}</td>
                        <td class="px-4 py-3">{{ $item->media_type ?: '—' }}</td>
                        <td class="px-4 py-3">
                            {{ $item->is_published ? 'Published' : 'Draft' }}
                            @if ($item->featured)
                                <span class="ml-1 rounded bg-[#f04a2a]/15 px-1.5 text-[11px] font-semibold text-[#f04a2a]">Featured</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.cms.portfolio.show', $item) }}" class="text-slate-600 underline dark:text-[#f7b396]">View</a>
                            <a href="{{ route('admin.cms.portfolio.edit', $item) }}" class="text-[#8b1e1a] underline">Edit</a>
                            <form method="POST" action="{{ route('admin.cms.portfolio.destroy', $item) }}" class="inline" onsubmit="return confirm('Delete this asset?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
@endsection
