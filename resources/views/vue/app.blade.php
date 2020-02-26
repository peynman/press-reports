@extends(\Larapress\Dashboard\Rendering\BladeCRUDViewProvider::getThemeViewName('layouts.master'))

@section('page-title', trans('dashboard.pages.home.title'))
@section('lang-direction', '')
@section('body-class', '')

@section('meta')
    @if (!is_null(auth()->user()))
        <meta name="jwt-token" content="{{ auth()->guard('api')->tokenById(auth()->user()->id) }}">
    @endif
@endsection

@section('pre-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/storage/vendor/larapress-dashboard/css/app.css') }}">
@endsection

@section('body')
    <div id="App">
    </div>
@endsection

@section('pre-scripts')
    <script>
        window.AppConfig = {!! json_encode($config) !!};
        console.log(window.AppConfig);
    </script>
    <script src="{{ asset('/storage/vendor/larapress-dashboard/js/manifest.js') }}"></script>
    <script src="{{ asset('/storage/vendor/larapress-dashboard/js/vendor.bundle.js') }}"></script>
    <script src="{{ asset('/storage/vendor/larapress-dashboard/js/app.bundle.js') }}"></script>
@endsection

@section('scripts')
@endsection