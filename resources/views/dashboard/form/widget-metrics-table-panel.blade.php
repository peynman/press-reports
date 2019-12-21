<?php
$id = $formId.$item['id'];
$tableId = $formId.$item['id'].'-talbe';
$service = app()->make(\App\Services\Metrics\IMetricChartRenderer::class);
$from = \Carbon\Carbon::now();
$to = \Carbon\Carbon::now();
$resolution = null;
$table = $item['table'];
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
            <h6 class="slim-cart-title">{{ isset($item['options']['label']) ? $item['options']['label']:'' }}</h6>
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
        @if (isset($item['chart']))
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
                            <h3 class="font-weight-semi-bold">{{ $summary['value'] }}</h3>
                            <p class="text-overflow">{{ $summary['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <table class="table table-centered table-hover mb-0 dataTable no-footer" id="{{$tableId}}" role="grid" aria-describedby="datatable_info">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script>
        $('.dropdown-submenu > a').on("click", function(e) {
            var submenu = $(this);
            $('.dropdown-submenu .dropdown-menu').removeClass('show');
            submenu.next('.dropdown-menu').addClass('show');
            e.stopPropagation();
        });
        $('.dropdown').on("hidden.bs.dropdown", function() {
            $('.dropdown-menu.show').removeClass('show');
        });
    </script>
    <script>
        $(document).ready(function() {
            <?php
            $actions = $table->getTableActions();
            if (!is_null($actions) && count($actions) > 0) {
                foreach ($actions as &$action) {
                    if (isset($action['dropdown'])) {
                        foreach ($action['dropdown'] as &$dd) {
                            $dd['url'] = $dd['link'](':id');
                        }
                    } else if (isset($action['link'])) {
                        $action['url'] = $action['link'](':id');
                    }
                }
            }
            ?>
            let columns = <?php echo json_encode($table->getTableColumns()); ?>;
            console.log(columns);
            let queryUrl = '{{ $table->queryUrl() }}';
            columns.forEach(function (column) {
                if (column.type === 'options') {
                    column.render = function(data, type, row, meta) {
                        if (type === 'display') {
                            let html = '<div class="button-list">';
                            @if (!is_null($actions) && count($actions) > 0)
                                    @foreach ($actions as $act)
                                    @if (!isset($act['showInTable']) || $act['showInTable'])
                                    @if (isset($act['dropdown']))
                                html += '<div class="dropdown"><button class="btn btn-outline-primary btn-icon btn-sm m-r-5" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="mdi mdi-{{ $act['icon'] }}"></span>' + '</button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                            @foreach($act['dropdown'] as $dropdown)
                                html += '<a class="dropdown-item" href="javascript:actionItemID(\'{{$dropdown['url']}}\', '+meta.row+', {{ isset($dropdown['method']) && $dropdown['method'] === 'POST' }})">'+'{{$dropdown['title']}}'+'</a>';
                            @endforeach
                                html += '</div></div>';
                            @else
                                html += '<button title data-placement="top" data-toggle="tooltip" data-original-title="{{ $act['title']  }}" onclick="actionItemID(\'{{ $act['url'] }}\', '+meta.row+')" class="btn btn-outline-primary btn-icon btn-sm m-r-5"><span class="mdi mdi-{{ $act['icon'] }}"></span></button>';
                            @endif
                                    @endif
                                    @endforeach
                                    @endif
                            html += '</div>';
                            return html;
                        } else if (type === 'type') {
                            return 'string';
                        }
                        return '';
                    }
                } else if (column.type === 'filter') {
                    column.render = function(data, type, row, meta) {
                        if (type === 'type') {
                            return 'string';
                        }

                        const display = eval(column.filter);
                        if (column.link) {
                            const reg = /::(.+):/;
                            const s = column.link;
                            const vars = s.split(reg).filter(x => x.trim().length !== 0);

                            for (let i = 1; i < vars.length; i++) {
                                if (vars[i] === 'data') {
                                    return '<a href="'+column.link.replace('::data:', display)+'">' + display + '</a>';
                                } else {
                                    return '<a href="'+column.link.replace('::'+vars[i]+':', Object.byString(row, vars[i]))+'">' + display + '</a>';
                                }
                            }
                        }

                        return display;
                    };
                } else if (column.type === 'datetime') {
                    column.render = function(data, type, row, meta) {
                        if (type === 'type') {
                            return 'string';
                        }
                        let html = '';
                        if (data) {
                            m = moment(data);
                            html += '<div>';
                            html += '<label>'
                                // +JSBridge.getPersianNumbers(m.format('jYYYY-jM-jD'))
                                +m.format('YYYY-MM-DD')
                                +'</label><label style="margin-left: 10px;">'
                                // +JSBridge.getPersianNumbers(m.format('H:m'))
                                +m.format('HH:mm')
                                +'</label>';
                            html += '</div>';
                        }
                        return html;
                    }
                } else {
                    column.render = function(data, type, row, meta) {
                        if (type === 'type') {
                            return 'string';
                        }

                        if (data) {
                            if (column.link) {
                                const reg = /::(.+):/;
                                const s = column.link;
                                const vars = s.split(reg).filter(x => x.trim().length !== 0);

                                for (let i = 1; i < vars.length; i++) {
                                    if (vars[i] === 'data') {
                                        return '<a href="'+column.link.replace('::data:', data)+'">' + data + '</a>';
                                    } else {
                                        return '<a href="'+column.link.replace('::'+vars[i]+':', Object.byString(row, vars[i]))+'">' + data + '</a>';
                                    }
                                }
                            }
                            return data;
                        } else {
                            return null;
                        }
                    }
                }
            });
            let getQueryParams = function(settings) {
                let query = {
                    page: Math.floor((settings._iDisplayStart+1) / settings._iDisplayLength)+1,
                    limit: settings._iDisplayLength,
                    from: '{{ $from }}',
                    to: '{{ $to }}',
                };
                if (settings.oPreviousSearch && settings.oPreviousSearch.sSearch) {
                    query.search = settings.oPreviousSearch.sSearch;
                } else {
                    query.search = undefined;
                }
                if (settings.aaSorting) {
                    query.sort = [];
                    settings.aaSorting.forEach(function(sort) {
                        query.sort.push({
                            column: columns[sort[0]].data,
                            direction: sort[1]
                        });
                    });
                }
                query.ref_id = settings.iDraw;
                query.filters = [];

                return query;
            };
            let datatable = $("#{{ $tableId }}").dataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searching: false,
                paging: false,
                columns: columns,
                columnDefs: [],
                order: [[0, 'desc']],
                ajax: function(request, callback, settings) {
                    console.log(getQueryParams(settings));
                    RESTful.Query(queryUrl, $.extend(getQueryParams(settings), JSON.parse('<?php echo json_encode($table->queryParams()) ?>'))).done(function(response) {
                        callback({
                            data: response.data,
                            draw: response.ref_id,
                            recordsTotal: response.total,
                            recordsFiltered: response.total,
                        });
                    });
                }
            }).DataTable();
        });
    </script>
@endsection