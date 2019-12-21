@extends('dashboard.dashboard')

@section('page-title', $pageTitle)

@section('content')
    <div class="slim-mainpanel">
        <div class="container">
            <div class="slim-pageheader">
                @include('dashboard.partials.breadcrumb_add', [
                    'name' => 'view',
                ])
                <h6 class="slim-pagetitle">@lang('forms.view_title', ['target' => $metadata->plural()])</h6>
            </div>

            <div class="row">
                <div class="col-sm-12">
			        <?php
                        /** @var \App\Services\CRUD\ICRUDProvider $provider */
                        $provider = $metadata->getCRUDProvider();
                        $providerName = class_basename($provider);
                        $filtersKey = \App\Services\CRUD\BaseCRUDService::getFilterKey(session()->getId(), $providerName);
                        $filters = \App\Models\Settings::getSettings($filtersKey, null, auth()->guest() ? null: auth()->user()->id);
    			        $expanded = is_array($filters);
			        ?>
                    <div class="card">
                        @php($filterFields = $metadata->getFilterFields())
                        <div class="card-header d-flex align-items-center justify-content-between pd-y-5 bd-b">
                            <h6 class="mg-b-0 tx-14 tx-inverse">@lang('forms.view_title', ['target' => $metadata->plural()])</h6>
                            <div class="card-option tx-24">
                                @if (!is_null($filterFields))
                                    <a href="#collapseFilters" aria-controls="collapseExample"  data-toggle="collapse" data-target="#collapseFilters" aria-expanded="false" class="tx-gray-600 mg-l-10 {{ $expanded ? '':'collapsed' }}" role="button"><i class="icon {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon('list') }}"></i></a>
                                @endif
                                @if ($metadata instanceof \App\Services\Grafana\IGrafanaReportsMetaData)
							        <?php $reports = $metadata->getReportPages();?>
                                    @if (!is_null($reports) && count($reports) > 0)
                                        <a onclick="window.location = '{{$metadata->reportsUrl()}}'" title data-placement="top" data-toggle="tooltip" data-original-title="{{ trans('forms.reports_title', ['target' => $metadata->plural()]) }}" class="tx-gray-600 mg-l-10" role="button" href="#"><i class="icon {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon('stats') }}"></i></a>
                                    @endif
                                @endif
                                @if ($metadata instanceof \App\Services\Metrics\IMetricsReportMetaData)
                                    <?php $reports = $metadata->getReportMetrics();?>
                                    @if (!is_null($reports) && count($reports) > 0)
                                        <a href="#" onclick="window.location = '{{$metadata->reportMetricsUrl()}}'" title data-placement="top" data-toggle="tooltip" data-original-title="{{ trans('forms.reports_title', ['target' => $metadata->plural()]) }}" class="tx-gray-600 mg-l-10" role="button"><i class="icon {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon('graph') }}"></i></a>
                                    @endif
                                @endif
                                @if ($metadata->hasCreate())
                                    <a title data-placement="top" data-toggle="tooltip" data-original-title="{{ trans('forms.create_title', ['target' => $metadata->singular()]) }}" id="btn-create" href="javascript:void(0)" class="tx-gray-600 mg-l-10"><i class="icon {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon('plus') }}"></i></a>
                                @endif
						        <?php $tableActions = $metadata->getTableActions(); ?>
                                @if (count($tableActions) > 0)
                                    @foreach($tableActions as $action)
                                        <a title class="tx-gray-600 mg-l-10" data-placement="top" data-toggle="tooltip" data-original-title="{{ $action['title'] }}" href="{{ $action['link']() }}"><i class="icon {{ isset($action['color']) ? $action['color']:'' }}  {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($action['icon']) }}"></i></a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @if (!is_null($filterFields))
                                <div class="row collapse {{ $expanded ? 'in':'' }}" id="collapseFilters" style="direction: ltr;">
                                    <div class="col-xs-12 col-md-6 offset-md-3">
                                        <div class="well" aria-expanded="false">
                                            {!! \App\Extend\HTMLHelpers::form($filterFields) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

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

                            {!! \App\Extend\HTMLHelpers::table($metadata) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="errorModalCenter" tabindex="-1" role="dialog" aria-labelledby="errorModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalCenterTitle">@lang('tables.messages.error-title')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="errorModalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    @parent

    @include('dashboard.partials.tables-css')
@endsection

@section('scripts')
    @include('dashboard.partials.tables-js')
    @include('bridges.js-bridge')

    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip({
                container: 'body'
            });

            @if (isset($validation))
            console.log('{!! json_encode( $validation->messages() )  !!}');
            @endif
        });
    </script>
@endsection