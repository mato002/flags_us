@extends('layouts.admin')

@section('title', 'Edit Service | Effective Media')
@section('header', 'Edit Service')

@section('content')
    @include('admin.cms.services.form', ['action' => route('admin.cms.services.update', $service), 'method' => 'PUT', 'service' => $service])
@endsection
