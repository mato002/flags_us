@extends('layouts.admin')

@section('title', 'Testimonials CMS | Effective Media')
@section('header', 'Testimonials CMS')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.cms.testimonials.create') }}" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Add testimonial</a>
    </div>
    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
        <div class="em-admin-table-scroll lg:mx-0 lg:px-0">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3">Client</th>
                    <th class="px-4 py-3">Company</th>
                    <th class="px-4 py-3">Rating</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($testimonials as $testimonial)
                    <tr class="border-t border-slate-200">
                        <td class="px-4 py-3">{{ $testimonial->client_name }}</td>
                        <td class="px-4 py-3">{{ $testimonial->company_name }}</td>
                        <td class="px-4 py-3">{{ $testimonial->rating }}/5</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('admin.cms.testimonials.edit', $testimonial) }}" class="underline">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
@endsection
