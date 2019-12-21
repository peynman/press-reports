<?php $tableId = str_random(5); ?>

<div class="table-wrapper {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    <form id="table-form" method="post">
        {{ csrf_field() }}
    </form>
    <table class="dataTables_wrapper no-footer" id="{{ $tableId }}"></table>
</div>

@section('scripts')
    @parent

    <script>
        $(document).ready(function() {
            let columns = <?php echo json_encode($metadata->getTableColumns()); ?>;
            let can_edit = '1' === '{{ $metadata->hasEdit() ? auth()->user()->hasPermission($metadata->edit()):'0' }}';
            let can_delete = '1' === '{{ $metadata->hasDelete() ? auth()->user()->hasPermission($metadata->delete()) :'0' }}';
            let can_create = '1' === '{{ auth()->user()->hasPermission($metadata->create()) }}';
            let editUrl = '{{ $metadata->editUrl(':id') }}';
            let queryUrl = '{{ $metadata->queryUrl() }}';
            let title = '{{ $metadata->title() }}';
            let edit_title = '{{ trans('forms.edit_title', ['target' => $metadata->title()]) }}';
            let delete_title = '{{ trans('forms.delete_title', ['target' => $metadata->title()]) }}';
		        <?php
		        $actions = $metadata->actions();
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
            @if($metadata->hasDelete())
                let deleteUrl = '{{ route($metadata->delete(), ':id') }}';
            @endif
            window.editItemID = function (row) {
                let item = datatable.rows(row).data()[0];
                let url = editUrl.replace(':id', item.id);
                location.href = url;
            };
            window.actionItemID = function (url, row) {
                let data = datatable.rows(row).data()[0];
                if (url.indexOf('javascript:') < 0) {
                    const newurl = url.replace(':id', data.id);
                    location.href = newurl;
                } else {
                    if (url.indexOf(':id') >= 0) {
                        url = url.replace(':id', data.id);
                    }
                    eval(url);
                }
            };
            window.deleteItemID = function(row) {
                if (confirm('Area you sure you want to delete this item?')) {
                    let item = datatable.rows(row).data()[0];
                    let url = deleteUrl.replace(':id', item.id);
                    RESTful.Delete(url).done(function(response) {
                        if (response.id === item.id) {
                            RESTful.DumpCache(queryUrl);
                            datatable.draw();
                        }
                    }).fail(function (error) {
                        $("#errorModalBody").html(error.message);
                        $('#errorModalCenter').modal('show');
                    });
                }
            };

            if (!can_create) {
                $("#btn-create").hide();
            } else {
                $("#btn-create").on('click', function() {
                    location.href = '{{ $metadata->createUrl() }}';
                });
            }

            columns.forEach(function (column) {
                if (column.type === 'options') {
                    column.render = function(data, type, row, meta) {
                        if (type === 'display') {
                            let html = '';
                            @if (!is_null($actions) && count($actions) > 0)
                                @foreach ($actions as $act)
                                    @if (!isset($act['showInTable']) || $act['showInTable'])
                                        @if (isset($act['dropdown']))
                                            @foreach($act['dropdown'] as $dropdown)
                                                html += '<a class="dropdown-item" href="javascript:actionItemID(\'{{$dropdown['url']}}\', '+meta.row+', {{ isset($dropdown['method']) && $dropdown['method'] === 'POST' }})">'+'{{$dropdown['title']}}'+'</a>';
                                            @endforeach
                                        @else
                                            html += '<button title data-placement="top" data-toggle="tooltip" data-original-title="{{ $act['title']  }}" onclick="actionItemID(\'{{ $act['url'] }}\', '+meta.row+')" class="btn btn-outline-primary p-1 mr-1"><i class="icon {{ isset($act['color']) ? $act['color']:'' }} {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($act['icon']) }}"></i></button>';
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                            if (can_edit) {
                                html += '<a href="#" title data-placement="top" data-toggle="tooltip" data-original-title="'+edit_title+'" onclick="editItemID('+meta.row+')" class="btn btn-outline-secondary p-1 mr-1"><i class="icon {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon('edit') }}"></i></a>';
                            }
                            if (can_delete) {
                                html += '<a href="#" title data-placement="top" data-toggle="tooltip" data-original-title="'+delete_title+'" onclick="deleteItemID('+meta.row+')" class="btn btn-outline-danger p-1 mr-1"><i class="icon {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon('trash') }}"></i></a>';
                            }
                            html += '';
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
                            html += '<label>'+m.format('YYYY-MM-DD')+'</label><label style="margin-left: 10px;">'+m.format('H:m')+'</label>';
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

                <?php
	                /** @var \App\Services\CRUD\ICRUDProvider $provider */
	                $provider = $metadata->getCRUDProvider();
	                $providerName = class_basename($provider);
	                $filtersKey = \App\Services\CRUD\BaseCRUDService::getFilterKey(session()->getId(), $providerName);
	                $filters = \App\Models\Settings::getSettings($filtersKey, null, auth()->guest() ? null: auth()->user()->id);
	                $filters = array_merge($provider->getFilterDefaultValues(), is_array($filters) ? $filters: []);
	                ?>
                query.filters = {!! json_encode($filters); !!};

                return query;
            };
            let datatable = $("#{{ $tableId }}").dataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                paging: true,
                columns: columns,
                columnDefs: [],
                order: [[0, 'desc']],
                ajax: function(request, callback, settings) {
                    RESTful.Query(queryUrl, $.extend(getQueryParams(settings), JSON.parse('<?php echo json_encode($metadata->queryParams()) ?>'))).done(function(response) {
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