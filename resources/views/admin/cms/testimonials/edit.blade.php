@extends('layouts.admin')

@section('title', 'Edit Testimonial | Effective Media')
@section('header', 'Edit Testimonial')

@section('content')
    @include('admin.cms.testimonials.form', ['action' => route('admin.cms.testimonials.update', $testimonial), 'method' => 'PUT', 'testimonial' => $testimonial])
@endsection
