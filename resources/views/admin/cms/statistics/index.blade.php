@extends('layouts.admin')

@section('title', 'Statistics CMS | Effective Media')
@section('header', 'Statistics CMS')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.cms.statistics.create') }}" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Add statistic</a>
    </div>
    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
        <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3">Label</th>
                    <th class="px-4 py-3">Value</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($statistics as $statistic)
                    <tr class="border-t border-slate-200">
                        <td class="px-4 py-3">{{ $statistic->label }}</td>
                        <td class="px-4 py-3">{{ $statistic->value }} {{ $statistic->suffix }}</td>
                        <td class="px-4 py-3">{{ $statistic->is_published ? 'Published' : 'Hidden' }}</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('admin.cms.statistics.edit', $statistic) }}" class="underline">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
@endsection
