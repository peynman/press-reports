@extends('dashboard.dashboard')

@section('page-title', $pageTitle)

@section('content')
    <div class="slim-mainpanel">
        <div class="container">
            <div class="slim-pageheader">
                @include('dashboard.partials.breadcrumb_add', [
                    'id' => isset($target) && !is_null($target) && isset($target->id) ? $target->id:null,
                    'name' => 'edit'
                ])
                <h6 class="slim-pagetitle">@lang('forms.edit_title', ['target' => $metadata->singular()])</h6>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between pd-y-5 bd-b">
                            <h6 class="mg-b-0 tx-14 tx-inverse">@lang('forms.edit_title', ['target' => $metadata->singular()])</h6>
                            <div class="card-options tx-24">
                                @if (count($metadata->actions()))
                                    @foreach($metadata->actions() as $action)
                                        @if ($metadata != $action['metadata'])
                                            @if (isset($target) && !is_null($target))
                                                @if (isset($action['dropdown']))
                                                @else
                                                    <a class="tx-gray-600 mg-l-10" title data-placement="top" data-toggle="tooltip" data-original-title="{{ $action['title'] }}" href="{{ $action['link']($target->id) }}"><i class="icon {{ isset($action['color'])? $action['color']:'' }} {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($action['icon']) }}"></i></a>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                                @if (!is_null($metadata->viewUrl()))
                                    <a class="tx-gray-600 mg-l-10" title data-placement="top" data-toggle="tooltip" data-original-title="{{ trans('forms.back_to', ['target' => $metadata->title()]) }}" href="{{ $metadata->viewUrl() }}"><i class="icon {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon('forward') }}"></i></a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="msg">
                                @if (isset($error))
                                    <div class="alert alert-danger">
                                        {!! $error  !!}
                                    </div>
                                @endif
                                @if (isset($success))
                                    <div class="alert alert-success">
                                        {!! $success !!}
                                    </div>
                                @endif
                            </div>
                            @php
                                $extras = [];
                                if (isset($actionUrl)) {
                                    $extras['actionUrl'] = $actionUrl;
                                }
                            @endphp
                            {!! \App\Extend\HTMLHelpers::formEdit($metadata, isset($validation) ? $validation: null, $target, [], $extras) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    @parent

    @include('dashboard.partials.forms-css')
@endsection

@section('scripts')
    @include('dashboard.partials.forms-js')
    @include('bridges.js-bridge')

    <script>
        $(document).ready(function () {
            window.FormTarget = {!! json_encode($target) !!};

            $('[data-toggle="tooltip"]').tooltip({
                container: 'body'
            });

            @if (isset($validation))
                console.log('{!! json_encode( $validation->messages() ) !!}');
            @endif
        });
    </script>

@endsection