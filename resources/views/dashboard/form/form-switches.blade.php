<?php $id = $formId.$item['id']; ?>
<div class="form-group {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    <input
            id="{{ $id }}"
            name="{{ $item['name'] }}"
            type="hidden"
            data-objects-decorator="<?php echo htmlspecialchars(json_encode($item['decoration'])) ?>"
            data-objects-flag="{{ $id }}"
            data-objects="<?php echo htmlspecialchars(json_encode($item['objects'])) ?>"
            value="<?php htmlspecialchars(json_encode($item['value'])) ?>"
    >
    <label class="form-control-label">
        @if (isset($item['icon']) && !is_null($item['icon']))
            <span class="input-group-addon">
            <i class="material-icons">person</i>
        </span>
        @endif
        {{ $item['label'] }}
    </label>
    <div class="{{ $item['validation'][0] }}">
        <div id="parent{{$id}}" class="accordion-two" role="tablist" aria-multiselectable="true">
            @php
                $indexer = 1;
                $collapse_class = 'collapsed';
                if (count($item['groups']) == 1) {
                    $collapse_class = '';
                }
            @endphp
            @foreach($item['groups'] as $group_name => $group_objects)
                @php $group_title_name = $id.'-'.$group_name; @endphp
                <div class="card">
                    <div id="collapse{{ $id.$indexer }}" class="{{ $collapse_class }}" aria-labelledby="heading{{ $id.$indexer }}" data-parent="#accordion{{ $id }}">
                        <div class="card-body">
                            <div class="row">
                                @foreach ($group_objects as $object)
                                    <div class="col-sm-12 col-md-6" style="margin: 0;">
                                        <label class="ckbox">
                                            <input
                                                    data-objects-toggle=""
                                                    data-objects-group-owner="{{ $group_name }}"
                                                    data-object-title-id="{{ $group_title_name }}"
                                                    data-object-id="{{ $object[$item['decoration']['id']] }}"
                                                    data-objects-element-id="{{ $id }}"
                                                    id="{{ $id.'-'.$object[$item['decoration']['id']] }}"
                                                    value="{{ $object[$item['decoration']['id']] }}"
                                                    type="checkbox">
                                            <span for="{{ $id.'-'.$object[$item['decoration']['id']] }}">
                                                    {{ trans($object[$item['decoration']['title']]) }}
                                                </span>
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
                $("#{{$id}}").val(data.value);
                FormEditor.Elements.Flags.fillToggleGroupsFromValues();
            }
        });
    </script>

    @if (!defined('form-flags-blade'))
        @php(define('form-flags-blade', true))
        <script>
            $(document).ready(function () {
                let objectGroups = $("[data-objects-flag]");
                FormEditor.Elements.Flags = {
                    updateToggleGroups: function () {
                        objectGroups.each(function (index, elm) {
                            let element = $(elm);
                            let decorator = element.data('objects-decorator');
                            let values = [];
                            $("[id^=" + element.attr('id') + "-]").each(function (index, inside) {
                                let check = $(inside);
                                if (check.prop('checked')) {
                                    let select = {};
                                    select[decorator.id] = check.data('object-id');
                                    values.push(select);
                                }
                            });

                            let flags = 0;
                            values.forEach(function(v) {
                                flags |= v[decorator.id];
                            });
                            element.val(flags);
                        });
                    },
                    fillToggleGroupsFromValues: function () {
                        objectGroups.each(function (index, elm) {
                            let element = $(elm);
                            let field = element.attr('id');
                            let decorator = element.data('objects-decorator');
                            let objects = element.data('objects');
                            let value = element.val();

                            objects.forEach(function (obj) {
                                if ((obj[decorator.id] & value) !== 0) {
                                    $("#" + field + '-' + obj[decorator.id]).prop('checked', true).change();
                                } else {
                                    $("#" + field + '-' + obj[decorator.id]).prop('checked', false).change();
                                }
                            });
                        });
                    },
                };
                $(document).on(FormEditor.Events.beforeFormSubmit, function(e, data) {
                    FormEditor.Elements.Flags.updateToggleGroups();
                });
            });
        </script>
    @endif
@endsection
