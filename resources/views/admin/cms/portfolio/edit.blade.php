@extends('layouts.admin')

@section('title', 'Edit Portfolio Item | Effective Media')
@section('header', 'Edit Portfolio Item')

@section('content')
    @include('admin.cms.portfolio.form', ['action' => route('admin.cms.portfolio.update', $item), 'method' => 'PUT', 'item' => $item])
@endsection
