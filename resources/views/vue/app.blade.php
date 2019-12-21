@extends(\Larapress\Dashboard\Base\BladeCRUDViewProvider::getThemeViewName('layouts.master'))

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
        window.DashboardConfig = {
            RTL: false,
            basePath: '{{ config('larapress.crud-render.prefix') }}',
            page: {
                title: 'Larapress Dashboard',
            },
            options: {
                title: 'Account',
            },
            sideMenuItems: [
                {
                    component: 'lpd-menu-item-single',
                    props: {
                        title: 'Dashboard',
                        icon: 'home',
                        url: '#',
                        key: 'dashboard-home',
                    },
                },
                {
                    component: 'lpd-menu-item-header',
                    props: {
                        title: 'Account Area',
                    },
                },
                {
                    component: 'lpd-menu-item-accordion',
                    props: {
                        title: 'Accounts',
                        icon: 'home',
                        items: [
                            {
                                component: 'lpd-menu-item-single',
                                props: {
                                    title: 'Dashboard',
                                    icon: 'home',
                                    url: '#',
                                    key: 'dashboard-home',
                                },
                            },
                            {
                                component: 'lpd-menu-item-single',
                                props: {
                                    title: 'Dashboard',
                                    icon: 'home',
                                    url: '#',
                                    key: 'dashboard-home',
                                },
                            },
                            {
                                component: 'lpd-menu-item-accordion',
                                props: {
                                    title: 'SubGroup',
                                    items: [
                                        {
                                            component: 'lpd-menu-item-single',
                                            props: {
                                                title: 'Dashboard',
                                                icon: 'home',
                                                url: '#',
                                                key: 'dashboard-home',
                                            },
                                        },
                                        {
                                            component: 'lpd-menu-item-single',
                                            props: {
                                                title: 'Dashboard',
                                                icon: 'home',
                                                url: '#',
                                                key: 'dashboard-home',
                                            },
                                        },
                                    ]
                                }
                            }
                        ]
                    }
                }
            ],
        }
    </script>
    <script src="{{ asset('/storage/vendor/larapress-dashboard/js/manifest.js') }}"></script>
    <script src="{{ asset('/storage/vendor/larapress-dashboard/js/vendor.bundle.js') }}"></script>
    <script src="{{ asset('/storage/vendor/larapress-dashboard/js/app.bundle.js') }}"></script>
@endsection

@section('scripts')
@endsection