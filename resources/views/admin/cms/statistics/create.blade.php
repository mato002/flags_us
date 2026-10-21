@extends('layouts.admin')

@section('title', 'Create Statistic | Effective Media')
@section('header', 'Create Statistic')

@section('content')
    @include('admin.cms.statistics.form', ['action' => route('admin.cms.statistics.store'), 'method' => 'POST', 'statistic' => null])
@endsection
