@extends('layouts.admin')

@section('title', 'Upload document | Effective Media Ops')
@section('header', 'Upload')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Upload document',
        'description' => 'Files are stored privately and linked from lead flows as needed.',
        'breadcrumb' => 'Website CMS · Documents · Upload',
    ])

    @include('admin.cms.library._form', [
        'action' => route('admin.cms.library.store'),
        'method' => 'POST',
        'document' => null,
    ])
@endsection
