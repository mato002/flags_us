@extends('layouts.admin')

@section('title', 'Create Testimonial | Effective Media')
@section('header', 'Create Testimonial')

@section('content')
    @include('admin.cms.testimonials.form', ['action' => route('admin.cms.testimonials.store'), 'method' => 'POST', 'testimonial' => null])
@endsection
