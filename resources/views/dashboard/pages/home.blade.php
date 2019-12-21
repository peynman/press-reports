@extends('themes.Greeva.dashboard.dashboard')

@section('page-title', trans('dashboard.pages.home.title'))

@section('content')
    @include('themes.Greeva.dashboard.containers.grafana', [
        'pages' => isset($pages) ? $pages:[],
        'settings' => $settings,
    ])
@endsection