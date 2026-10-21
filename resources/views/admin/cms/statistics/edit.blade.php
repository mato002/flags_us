@extends('layouts.admin')

@section('title', 'Edit Statistic | Effective Media')
@section('header', 'Edit Statistic')

@section('content')
    @include('admin.cms.statistics.form', ['action' => route('admin.cms.statistics.update', $statistic), 'method' => 'PUT', 'statistic' => $statistic])
@endsection
