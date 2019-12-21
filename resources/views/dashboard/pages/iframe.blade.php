@extends('dashboard.dashboard')

@section('page-title', trans('dashboard.pages.reports.horizon'))

@section('content')
    <section class="content">
        <iframe src="{{ url('/horizon') }}" width="100%" height="800" frameborder="0"></iframe>
    </section>
@endsection