<?php
$id = $formId.$item['id'];
$service = app()->make(\App\Services\Metrics\IMetricChartRenderer::class);
?>
<div class="{{ isset($item['classes.class']) ? $item['classes.class']: null }} mg-t-10 mg-b-10" id="{{ $id }}">
    <div class="card card-customer-overview" id="{{ $id }}-card" style="height: 100%;">
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
                    <li><a  class="dropdown-item" href="#">@lang('dashboard.pages.metrics.dropdown.move.move_first')</a></li>
                    <li><a  class="dropdown-item" href="#">@lang('dashboard.pages.metrics.dropdown.move.move_last')</a></li>
                    <li><a  class="dropdown-item" href="#">@lang('dashboard.pages.metrics.dropdown.move.move_up')</a></li>
                    <li><a  class="dropdown-item" href="#">@lang('dashboard.pages.metrics.dropdown.move.move_down')</a></li>
                </ul>
            </div>
        </div>
        <div class="card-title">
            <iframe style="min-height: 300px; border: none" src="{{ route('website.any.live-stream.admin', 'stat') }}" height="100%" width="100%"></iframe>
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