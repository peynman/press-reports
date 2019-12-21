<!DOCTYPE html>
<html lang="@yield('page-language')" dir="@yield('lang-direction')">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('page-description')">
    <meta name="author" content="@yield('page-author')">
    @section('meta')
        @yield('meta')
    @show
    <title>@yield('page-title')</title>
    @section('head')
        @yield('head')
    @show

    @section('pre-styles')
        @yield('pre-styles')
    @show
    @section('styles')
        @yield('styles')
    @show
    @section('post-styles')
        @yield('post-styles')
    @show
</head>
<body class="@yield('body-class')">
@section('body')
    @yield('body')
@show
@section('pre-scripts')
    @yield('pre-scripts')
@show
@section('scripts')
    @yield('scripts')
@show
@section('post-scripts')
    @yield('post-scripts')
@show
</body>
</html>