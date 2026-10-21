@extends('layouts.admin')

@section('title', 'Edit document | Effective Media Ops')
@section('header', 'Edit document')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Edit metadata',
        'description' => 'Replace the file or adjust publication and lead-capture rules.',
        'breadcrumb' => 'Website CMS · Documents · Edit',
    ])

    @include('admin.cms.library._form', [
        'action' => route('admin.cms.library.update', ['library' => $document]),
        'method' => 'PUT',
        'document' => $document,
    ])
@endsection
