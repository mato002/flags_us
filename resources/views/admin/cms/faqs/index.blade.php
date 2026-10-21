@extends('layouts.admin')

@section('title', 'FAQs | Effective Media Ops')
@section('header', 'FAQs')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Frequently asked questions',
        'description' => 'Reorder, publish, and retire help content shown on the marketing site.',
        'breadcrumb' => 'Website CMS · FAQs',
    ])

    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.cms.faqs.create') }}" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Add FAQ</a>
    </div>

    <form method="GET" class="mb-4 flex flex-wrap gap-3 rounded-lg border border-slate-200 bg-white p-4 text-sm dark:border-white/10 dark:bg-white/5">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search question or answer…" class="min-w-0 flex-1 rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
        <select name="active" class="rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
            <option value="">All statuses</option>
            <option value="1" @selected(request('active') === '1')>Active</option>
            <option value="0" @selected(request('active') === '0')>Inactive</option>
        </select>
        <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-xs font-semibold text-white dark:bg-[#f04a2a]">Apply</button>
        <a href="{{ route('admin.cms.faqs.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold dark:border-white/20 dark:text-white">Reset</a>
    </form>

    @if ($faqs->count() === 0)
        <div class="rounded-lg border border-dashed border-slate-300 bg-white/60 p-12 text-center text-sm text-slate-600 dark:border-white/15 dark:bg-white/5 dark:text-[#cbbfb6]">
            No FAQs yet. Create the first entry to populate the public page.
        </div>
    @else
        <form method="POST" action="{{ route('admin.cms.faqs.reorder') }}" class="mb-6 overflow-hidden rounded-lg border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
            @csrf
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-4 py-3 dark:border-white/10">
                <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-[#cbbfb6]">Display order</span>
                <button type="submit" class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-semibold hover:bg-slate-50 dark:border-white/20 dark:hover:bg-white/10">Save ordering</button>
            </div>
            <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 dark:bg-[#221b1b] dark:text-[#dfd5cd]">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Question</th>
                            <th class="px-4 py-3 font-semibold">Active</th>
                            <th class="px-4 py-3 font-semibold">Sort</th>
                            <th class="px-4 py-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($faqs as $faq)
                            <tr class="border-t border-slate-200 dark:border-white/10">
                                <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ Str::limit($faq->question, 80) }}</td>
                                <td class="px-4 py-3">{{ $faq->is_active ? 'Yes' : 'No' }}</td>
                                <td class="px-4 py-3">
                                    <input type="number" name="orders[{{ $faq->id }}]" value="{{ old('orders.'.$faq->id, $faq->sort_order) }}" min="0" class="w-24 rounded border border-slate-300 px-2 py-1 dark:border-white/20 dark:bg-transparent dark:text-white">
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.cms.faqs.edit', $faq) }}" class="font-semibold text-[#8b1e1a] underline">Edit</a>
                                    <button type="submit" form="delete-faq-{{ $faq->id }}" class="ml-3 text-rose-600 underline" onclick="return confirm('Delete this FAQ?');">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>

        @foreach ($faqs as $faq)
            <form id="delete-faq-{{ $faq->id }}" method="POST" action="{{ route('admin.cms.faqs.destroy', $faq) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach

        <div class="mt-4">{{ $faqs->links() }}</div>
    @endif
@endsection
