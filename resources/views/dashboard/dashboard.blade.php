@extends('dashboard.layout')

@section('page-title', trans('dashboard.pages.home.title'))
@section('body-class', '')

@section('body')
    <iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank" style="display: none;"></iframe>
    @include('dashboard.partials.header')
    @include('dashboard.navbar.body')

    @section('content')
        @yield('content')
    @show
@endsection