@extends(\Larapress\Dashboard\Dashboard\BladeCRUDViewProvider::getThemeViewName('layouts.master'))

@section('page-title', trans('dashboard.pages.home.title'))
@section('body-class', '')

@section('meta')
    @if (!is_null(auth()->user()))
        <meta name="jwt-token" content="{{ auth()->guard('api')->tokenById(auth()->user()->id) }}">
    @endif
@endsection

@section('pre-styles')
    <link href="{{ asset("lib/font-awesome/css/font-awesome.css") }}" rel="stylesheet">
    <link href="{{ asset("css/ionicons.min.css") }}" rel="stylesheet">
    <link href="{{ asset("lib/chartist/css/chartist.css") }}" rel="stylesheet">
    <link href="{{ asset("lib/jquery-toggles/css/toggles-full.css") }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/rudaw.css') }}">

    <!-- Slim CSS -->
    <link rel="stylesheet" href="{{ asset("css/slim.css") }}">
    <link rel="stylesheet" href="{{ asset("css/slim.override.css") }}">

    <style>
        html, body {
            direction: rtl;
            font-family: rudaw;
        }
    </style>
@endsection

@section('body')
    @section('content')
        @yield('content')
    @show
@endsection

@section('pre-scripts')
    <script src="{{ asset("lib/jquery/js/jquery.js") }}"></script>
    <script src="{{ asset("lib/popper.js/js/popper.js") }}"></script>
    <script src="{{ asset("lib/bootstrap/js/bootstrap.js") }}"></script>
    <script src="{{ asset("lib/jquery.cookie/js/jquery.cookie.js") }}"></script>
    <script src="{{ asset("lib/jquery-toggles/js/toggles.min.js") }}"></script>
    <script src="{{ asset("lib/d3/js/d3.js") }}"></script>

    <script src="{{ asset("js/ResizeSensor.js") }}"></script>
    <script src="{{ asset("js/slim.js") }}"></script>
    <script src="{{ asset('js/axios.js') }}"></script>
    <script src="{{ asset('js/laravel.js') }}"></script>
    @include(\Larapress\Dashboard\Dashboard\BladeCRUDViewProvider::getThemeViewName('bridges.forms-bridge'))
    <script>
        window.EditTarget = null;
    </script>
@endsection