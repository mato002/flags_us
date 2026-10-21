@extends('layouts.admin')

@section('title', 'Services CMS | Effective Media Ops')
@section('header', 'Services')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Website services',
        'description' => 'Control merchandised services. Sort integers run ascending — lower renders first.',
        'breadcrumb' => 'CMS · Services',
    ])

    <div class="mb-4 flex flex-wrap items-center gap-3">
        <a href="{{ route('admin.cms.services.create') }}" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-[#f04a2a]">Add service</a>
    </div>

    <form method="POST" action="{{ route('admin.cms.services.reorder') }}" class="mb-10 rounded-lg border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        @csrf
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-4 py-3 dark:border-white/10">
            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-[#cbbfb6]">Batch ordering</span>
            <button type="submit" class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-semibold hover:bg-slate-50 dark:border-white/20 dark:hover:bg-white/10">Save ordering</button>
        </div>
        <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 dark:bg-[#221b1b] dark:text-[#dfd5cd]">
                <tr>
                    <th class="px-4 py-3 font-semibold">Title</th>
                    <th class="px-4 py-3 font-semibold">Active</th>
                    <th class="px-4 py-3 font-semibold">Public</th>
                    <th class="px-4 py-3 font-semibold">Sort value</th>
                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $service)
                    <tr class="border-t border-slate-200 dark:border-white/10">
                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $service->title }}</td>
                        <td class="px-4 py-3">{{ $service->is_active ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">{{ $service->is_visible_public ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">
                            <input type="number" name="orders[{{ $service->id }}]" value="{{ old('orders.'.$service->id, $service->sort_order) }}" min="0" class="w-24 rounded border border-slate-300 px-2 py-1 text-sm dark:border-white/20 dark:bg-transparent dark:text-white">
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.cms.services.edit', $service) }}" class="text-[#8b1e1a] underline">Edit</a>
                            <button type="submit" form="delete-service-{{ $service->id }}" class="ml-3 text-rose-600 underline" onclick="return confirm('Delete this service?');">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </form>

    @foreach ($services as $service)
        <form id="delete-service-{{ $service->id }}" method="POST" action="{{ route('admin.cms.services.destroy', $service) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endsection
