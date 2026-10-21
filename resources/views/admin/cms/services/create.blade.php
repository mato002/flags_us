@extends('layouts.admin')

@section('title', 'Create Service | Effective Media')
@section('header', 'Create Service')

@section('content')
    @include('admin.cms.services.form', ['action' => route('admin.cms.services.store'), 'method' => 'POST', 'service' => null])
@endsection
