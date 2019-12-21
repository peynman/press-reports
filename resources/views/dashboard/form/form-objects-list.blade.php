<?php $id = $formId.$item['id']; ?>
@if (isset($item['decoration']) && !is_null($item['decoration']['processor']))
    @foreach($item['groups'] as $group_name => $group_objects)
        @foreach ($group_objects as $object)
            {{ $item['decoration']['processor']($object) }}
        @endforeach
    @endforeach
@endif
<div class="form-group {{$item['validation'][0]}} {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    <input
            id="{{ $id }}"
            name="{{ $item['name'] }}"
            type="hidden"
            data-objects-title-mode="count"
            data-objects-decorator="<?php echo htmlspecialchars(json_encode($item['decoration'])) ?>"
            data-objects-group="{{ $id }}"
            data-objects="<?php echo htmlspecialchars(json_encode($item['objects'])) ?>"
            value="<?php echo htmlspecialchars(json_encode($item['value'])) ?>"
    >
    <label class="form-control-label">
        @if (isset($item['icon']) && !is_null($item['icon']))
            <span class="input-group-prepend">
            <i class="material-icons">{{\App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($item['icon'])}}</i>
        </span>
        @endif
        {{ $item['label'] }}
    </label>
    <div class="form-line">
        <div id="parent{{$id}}" class="accordion-two" role="tablist" aria-multiselectable="true">
            @php
                $indexer = 1;
                $collapse_class = 'collapse';
                if (count($item['groups']) == 1) {
                    $collapse_class = '';
                }
            @endphp
            @foreach($item['groups'] as $group_name => $group_objects)
                @php $group_title_name = $id.'-'.$group_name; @endphp
                <div class="card">
                    <div class="card-header d-flex align-items-center " role="tab" id="header{{ $id.$indexer }}">
                        <a style="flex: 1;" href="#collapse{{ $id.$indexer }}" class="tx-gray-800 transition {{ empty($collapse_class) ? '':'collapsed' }}" data-toggle="collapse" data-target="#collapse{{ $id.$indexer }}" aria-expanded="{{ empty($collapse_class) ? 'false':'true' }}" aria-controls="collapse{{ $id.$indexer }}">
                            @if (!is_null($item['decoration']['groupTitle']))
                                <span id="{{ $group_title_name }}">{{ trans($group_objects[0][$item['decoration']['groupTitle']]) }}</span>
                            @else
                                <span id="{{ $group_title_name }}">@lang('forms.nothing_selected')</span>
                            @endif
                        </a>
                        <div class="card-options tx-24 mr-2 ml-2">
                            <a style="display: inline; border: none; background: none;" class="tx-gray-600 mg-l-10 pd-1-force" href="javascript:void(0)" data-objects-check-all="false" data-objects-group-name="{{ $group_name }}"><i class="icon {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon('checkbox-empty') }}"></i></a>
                            <a style="display: inline; border: none; background: none;" class="tx-gray-600 mg-l-10 pd-1-force" href="javascript:void(0)" data-objects-check-all="true" data-objects-group-name="{{ $group_name }}"><i class="icon {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon('checkbox') }}"></i></a>
                        </div>
                    </div>
                    <div id="collapse{{ $id.$indexer }}" class="{{ $collapse_class }}" aria-labelledby="heading{{ $id.$indexer }}" data-parent="#parent{{ $id }}">
                        <div class="card-body">
                            <div class="row">
                                @foreach ($group_objects as $object)
                                    <div class="col-sm-12 col-md-6" style="direction: ltr;">
                                        <label class="ckbox" for="{{ $id.'-'.$object[$item['decoration']['id']] }}">
                                            <input
                                                    data-objects-checkbox=""
                                                    data-objects-group-owner="{{ $group_name }}"
                                                    data-object-title-id="{{ $group_title_name }}"
                                                    data-object-id="{{ $object[$item['decoration']['id']] }}"
                                                    data-objects-element-id="{{ $id }}"
                                                    id="{{ $id.'-'.$object[$item['decoration']['id']] }}"
                                                    value="{{ $object[$item['decoration']['id']] }}"
                                                    type="checkbox">
                                            <span>{{ trans($object[$item['decoration']['title']]) }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @php $indexer++ @endphp
            @endforeach
        </div>
    </div>
    @if (!empty($item['validation'][1]))
        {!! $item['validation'][1] !!}
    @endif
    @if (isset($item['help']))
        <div class="help-info">{{ $item['help'] }}</div>
    @endif
</div>


@section('scripts')
    @parent

    <script>
        $(document).on(FormEditor.getFieldUpdateEvent('{{$id}}'), function(e, data) {
            if (data && data.value) {
                $("#{{$id}}").val(JSON.stringify(data.value));
                FormEditor.Elements.ObjectGroup.fillObjectGroupsFromValues();
            }
        });
    </script>

    @if (!defined('form-tagsinput-blade'))
        @php(define('form-tagsinput-blade', true))
        <script>
            $(document).ready(function () {
                $("[data-objects-checkbox]").on('change', function (e) {
                    let target = $(e.currentTarget);
                    let id = target.data('objects-element-id');
                    let update_group_title = target.data('objects-group-owner');
                    let element = $('#' + id);
                    let decorator = element.data('objects-decorator');
                    let title_mode = element.data('objects-title-mode');
                    if (decorator.titleMode) {
                        title_mode = decorator.titleMode;
                    }

                    let objects = element.data('objects');
                    let selects = [];
                    let obj_selects = [];
                    let self = null;
                    objects.forEach(function (object) {
                        let check = $('#' + id + '-' + object[decorator.id]);
                        if (decorator.groupName) {
                            if (object[decorator.groupName] === update_group_title) {
                                if (check.prop('checked')) {
                                    selects.push(object[decorator.title]);
                                    obj_selects.push(object);
                                }
                            }
                        } else {
                            if (check.prop('checked')) {
                                selects.push(object[decorator.title]);
                                obj_selects.push(object);
                            }
                        }
                        if (object[decorator.id] == target.val()) {
                            self = object;
                        }
                    });
                    let title_element = $('#' + target.data('object-title-id'));
                    if (selects.length > 0) {
                        if (title_mode === 'count') {
                            if (decorator.groupTitle) {
                                title_element.html(Lang.get(self[decorator.groupTitle]) + ': ' + selects.length + '');
                            } else {
                                title_element.html(Lang.get('forms.selected_count') + ': ' + selects.length + '');
                            }
                        } else if (title_mode === 'names') {
                            title_element.html(Lang.get('forms.selected_names') + ': ' + selects.join(', '));
                        } else if (title_mode === 'price') {
                            let price = 0;
                            obj_selects.forEach(function (obj) {
                                if (obj['price']) {
                                    price += parseInt(obj['price']);
                                }
                            });
                            title_element.html(Lang.get('forms.selected_prices') + ': ' + JSBridge.getPrice(price));
                        }
                    } else {
                        if (decorator.groupTitle) {
                            title_element.html(Lang.get(self[decorator.groupTitle]));
                        } else {
                            title_element.html(Lang.get('forms.nothing_selected'));
                        }
                    }
                    $(document).trigger(FormEditor.Events.elementChanged, {id: id, element: 'objects-list', selects: obj_selects}, element);
                });
                $("[data-objects-check-all]").on('click', function (e) {
                    let target = $(e.currentTarget);
                    let group_name = target.data('objects-group-name');
                    let value = target.data('objects-check-all');
                    $("[data-objects-group-owner='" + group_name + "']").prop('checked', value).change();
                });
                $(document).on('click', '.dropdown-item', function (e) {
                    e.stopPropagation();
                });

                let objectGroups = $("[data-objects-group]");
                FormEditor.Elements.ObjectGroup = {
                    updateObjectGroups: function () {
                        objectGroups.each(function (index, elm) {
                            let element = $(elm);
                            let field = element.data('objects-group');
                            let decorator = element.data('objects-decorator');

                            let values = [];
                            $("[id^=" + field + "-]").each(function (index, inside) {
                                let check = $(inside);
                                if (check.prop('checked')) {
                                    let select = {};
                                    select[decorator.target_id] = check.data('object-id');
                                    values.push(select);
                                }
                            });
                            element.val(JSON.stringify(values));
                        });
                    },
                    fillObjectGroupsFromValues: function () {
                        objectGroups.each(function (index, elm) {
                            let element = $(elm);
                            let field = element.data('objects-group');
                            let decorator = element.data('objects-decorator');


                            if (element.val().length > 0) {
                                let values = JSON.parse(element.val());
                                if (values && Array.isArray(values)) {
                                    values.forEach(function (value) {
                                        $("#" + field + '-' + value[decorator.target_id]).prop('checked', true).change();
                                    });
                                }
                            }
                        });
                    },
                };
                $(document).on(FormEditor.Events.beforeObjectUpdate, function(e, data) {
                    FormEditor.Elements.ObjectGroup.fillObjectGroupsFromValues();
                    FormEditor.Elements.ObjectGroup.updateObjectGroups();
                });
                $(document).on(FormEditor.Events.beforeFormSubmit, function(e, data) {
                    FormEditor.Elements.ObjectGroup.updateObjectGroups();
                });
            });
        </script>
    @endif
@endsection
