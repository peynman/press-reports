<?php
    $id = $formId.$item['id'];
    $service = app()->make(\App\Services\Metrics\IMetricChartRenderer::class);
    $from = \Carbon\Carbon::now();
    $to = \Carbon\Carbon::now();
    $resolution = null;
    foreach ($item['date-ranges'] as $date_range) {
        if ($date_range['name'] === $item['range']) {
            $resolution = $date_range['resolution'];
            $from = \App\Services\Metrics\MetricHelpers::getCarbonFromDesc($date_range['from']);
            $to = \App\Services\Metrics\MetricHelpers::getCarbonFromDesc($date_range['to']);
            break;
        }
    }
?>
<div class="{{ isset($item['classes.class']) ? $item['classes.class']: null }} mg-t-10 mg-b-10" id="{{ $id }}">
    <div class="card card-customer-overview" id="{{ $id }}-card">
        <div class="card-header">
            <h6 class="slim-card-title">{{ isset($item['options']['label']) ? $item['options']['label']:'' }}</h6>
            <div class="dropdown float-right">
                <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-dots-horizontal"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right multi-level">
                    <li>
                        <a class="dropdown-item" href="javascript:JSBridge.PostForm(null, {action: 'toggle-dashboard', name: '{{ $item['id'] }}'});">
                            @if ($item['on_dashboard'] || starts_with($item['name'], "dashboard_"))
                                @lang('dashboard.pages.metrics.dropdown.toggle_dashboard.off')
                            @else
                                @lang('dashboard.pages.metrics.dropdown.toggle_dashboard.on')
                            @endif
                        </a>
                    </li>
                    <li class="dropdown-divider"></li>
                    @foreach($item['date-ranges'] as $range)
                        <li>
                            <a  class="dropdown-item {{ $range['name'] === $item['range'] ? 'active-blue':'' }}"
                                href="javascript:JSBridge.PostForm(null, {action: 'switch-range', name: '{{ $item['id'] }}', range:'{{ $range['name'] }}'});">
                                {{ $range['label'] }}
                            </a>
                        </li>
                    @endforeach
                    <li class="dropdown-divider"></li>
                    <li><a  class="dropdown-item" href="#">@lang('dashboard.pages.metrics.dropdown.move.move_first')</a></li>
                    <li><a  class="dropdown-item" href="#">@lang('dashboard.pages.metrics.dropdown.move.move_last')</a></li>
                    <li><a  class="dropdown-item" href="#">@lang('dashboard.pages.metrics.dropdown.move.move_up')</a></li>
                    <li><a  class="dropdown-item" href="#">@lang('dashboard.pages.metrics.dropdown.move.move_down')</a></li>

                    {{--<li class="dropdown-submenu">--}}
                    {{--<a class="dropdown-item" href="#" tabindex="-1">@lang('dashboard.pages.metrics.dropdown.date_ranges.title')</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li class="dropdown-submenu">--}}
                    {{--<a class="dropdown-item" href="#" tabindex="-1">@lang('dashboard.pages.metrics.dropdown.move.title')</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                </ul>
            </div>
        </div>
        <div class="card-title">
            @php($summaries = $service->summarize($item['chart'], $from, $to))
            @if (count($summaries) > 0)
                @if (count($summaries) > 4)
                    @php($columns = 4)
                @else
                    @php($columns = 12 / count($summaries))
                @endif
                <div class="row text-center">
                    @foreach($summaries as $summary)
                        <div class="mb-3 col-sm-{{ $columns }}">
                            <h3 class="font-weight-light">{{ $summary['value'] }}</h3>
                            <p class="text-muted text-overflow">{{ $summary['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="chartjs-chart-example chartjs-chart" id="{{ $id }}-chart-container">
                {!! $service->renderMetricChart($item['id'], $item['chart'], $from, $to, $resolution) !!}
            </div>
        </div>
    </div>
</div>

@section('scripts')
    @parent

    <script>
        @if (isset($item['chart']['size']))
        $(document).ready(() => {
            const updateContainerSize = () => {
                const ratio = {{ $item['chart']['size'][1] / $item['chart']['size'][0] }};
                const container = $("#{{ $id }}-chart-container");
                container.height(container.width() * ratio);
            };
            $(window).resize(() => {
                updateContainerSize();
            });
            updateContainerSize();
        });
        @endif
        $('.dropdown-submenu > a').on("click", function(e) {
            var submenu = $(this);
            $('.dropdown-submenu .dropdown-menu').removeClass('show');
            submenu.next('.dropdown-menu').addClass('show');
            e.stopPropagation();
        });
        $('.dropdown').on("hidden.bs.dropdown", function() {
            // hide any open menus when parent closes
            $('.dropdown-menu.show').removeClass('show');
        });
    </script>
@endsection