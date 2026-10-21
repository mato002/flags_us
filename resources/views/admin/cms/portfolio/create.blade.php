@extends('layouts.admin')

@section('title', 'Create Portfolio Item | Effective Media')
@section('header', 'Create Portfolio Item')

@section('content')
    @include('admin.cms.portfolio.form', ['action' => route('admin.cms.portfolio.store'), 'method' => 'POST', 'item' => null])
@endsection
