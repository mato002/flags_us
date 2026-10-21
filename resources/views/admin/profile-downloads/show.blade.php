@extends('layouts.admin')

@section('title', 'Profile Download Detail | Admin')
@section('header', 'Profile Download Request Detail')

@section('content')
    <div class="grid gap-4 lg:grid-cols-2">
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Lead</h2>
            <div class="mt-3 space-y-2 text-sm text-slate-700">
                <p><span class="font-semibold text-slate-900">Name:</span> {{ $download->full_name }}</p>
                <p><span class="font-semibold text-slate-900">Company:</span> {{ $download->company_name ?: 'N/A' }}</p>
                <p><span class="font-semibold text-slate-900">Phone:</span> {{ $download->phone }}</p>
                <p><span class="font-semibold text-slate-900">Email:</span> {{ $download->email }}</p>
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Download Details</h2>
            <div class="mt-3 space-y-2 text-sm text-slate-700">
                <p><span class="font-semibold text-slate-900">Filename:</span> {{ $download->filename }}</p>
                <p><span class="font-semibold text-slate-900">Source:</span> {{ $download->source }}</p>
                <p><span class="font-semibold text-slate-900">Received:</span> {{ $download->created_at?->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-2">
        <a href="{{ route('admin.profile-downloads.index') }}" class="rounded border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Back to downloads</a>
        <form method="POST" action="{{ route('admin.profile-downloads.destroy', $download) }}" onsubmit="return confirm('Delete this profile download request?');">
            @csrf
            @method('DELETE')
            <button class="rounded border border-rose-300 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50">Delete request</button>
        </form>
    </div>
@endsection
