@extends('layouts.admin')

@section('title', 'Edit FAQ | Effective Media Ops')
@section('header', 'Edit FAQ')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Edit FAQ',
        'description' => 'Update copy, visibility, or ordering weight.',
        'breadcrumb' => 'Website CMS · FAQs · Edit',
    ])

    @include('admin.cms.faqs._form', [
        'action' => route('admin.cms.faqs.update', $faq),
        'method' => 'PUT',
        'faq' => $faq,
    ])
@endsection
