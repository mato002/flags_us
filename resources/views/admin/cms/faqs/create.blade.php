@extends('layouts.admin')

@section('title', 'Create FAQ | Effective Media Ops')
@section('header', 'New FAQ')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Add FAQ',
        'description' => 'Writes to the FAQs module and public /faqs experience.',
        'breadcrumb' => 'Website CMS · FAQs · Create',
    ])

    @include('admin.cms.faqs._form', [
        'action' => route('admin.cms.faqs.store'),
        'method' => 'POST',
        'faq' => null,
    ])
@endsection
