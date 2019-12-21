<?php $id = $formId.$item['id']; ?>

@if (isset($item['group']))
    @php
        $groups = [];
        foreach ($item['objects'] as $object) {
            $group_id = $object[$item['group']];
            if (!isset($groups[$group_id])) {
                $groups[$group_id] = [];
            }
            $groups[$group_id][] = $object;
        }
    @endphp
@endif

@if (isset($item['decorator']) && !is_null($item['decorator']['processor']))
    @if (isset($item['group']))
        @foreach ($groups as $object)
            {{ $item['decorator']['processor']($object) }}
        @endforeach
    @elseif (isset($item['objects']))
        @foreach ($item['objects'] as $object)
            {{ $item['decorator']['processor']($object) }}
        @endforeach
    @endif
@endif
<div class="form-group {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    @if (isset($item['icon']) && !is_null($item['icon']))
        <span class="input-group-addon">
            <i class="material-icons">{{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($item['icon']) }}</i>
        </span>
    @endif
    @php $decorator = isset($item['decorator'])? $item['decorator']: ['id' => 'id', 'title' => 'title']; @endphp
    <div class="{{ $item['validation'][0] }}">
        <label class="form-control-label">{{ $item['label'] }}</label>
        <div style="">
            <select
                    id="{{ $id }}"
                    name="{{ $item['name'] }}"
                    class="form-control selectpicker show-tick"
                    data-placeholder="Choose one"
                    tabindex="-1"
                    aria-hidden="true"
                    data-width="100%"
                    @if (isset($item['multiple']))
                    multiple
                    @endif
                    @if (isset($item['max-options']))
                    data-max-options="{{$item['max-options']}}"
                    @endif
                    @if (isset($item['readonly']) && $item['readonly'])
                    readonly="{{ $item['readonly'] }}"
                    @endif
            >
                @if (isset($item['group']))
                    @foreach($groups as $group => $objects)
                        @if (isset($item['show-groups']))
                            <optgroup
                                    id="{{ $id.'-group-'.$group }}"
                                    label="{{ $group }}"
                            >
                                @endif
                                @foreach ($objects as $option)
                                    <option
                                            id="{{ $id.'-group-'.$group.'-option-'.$option['id'] }}"
                                            value="{{ $option['id'] }}"
                                            data-tokens="{{ isset($item['keywords']) && $item['keywords'] }}"
                                    >
                                        {{ $option[$decorator['title']] }}
                                    </option>
                                @endforeach
                                @if (isset($item['show-groups']))
                            </optgroup>
                        @endif
                    @endforeach
                @elseif (isset($item['objects']))
                    @foreach ($item['objects'] as $option)
                        <option
                                id="{{ $id.'-option-'.$option['id'] }}"
                                value="{{ $option['id'] }}"
                                data-tokens="{{ isset($item['keywords']) && $item['keywords'] }}"
                        >{{ $option[$decorator['title']] }}</option>
                    @endforeach
                @endif
            </select>
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
    @if (!defined('form-select-blade'))

    @endif
@endsection
@section('scripts')
    @parent

    <script>
        $(document).on(FormEditor.getFieldUpdateEvent('{{$id}}'), function(e, data) {
            if (data && data.value) {
                let val = data.value;
                if (typeof val === 'object') {
                    val = val.id;
                }

                let element = $("#{{$id}}");
                element.selectpicker('val', val);
                element.selectpicker('refresh');

                @if (isset($item['events']))
                    @foreach ($item['events'] as $key => $event)
                        @if ($key === 'filled')
                            {!! $event  !!}
                        @endif
                    @endforeach
                @endif
            }
        });
        @if (isset($item['events']))
            @foreach ($item['events'] as $key => $event)
                @if ($key === 'changed')
                    $("#{{$id}}").on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
                        let element = $(e.target);
                        {!! $event  !!}
                    });
                @elseif ($key === 'inited')
                    $("#{{$id}}").on('loaded.bs.select', function (e, clickedIndex, isSelected, previousValue) {
                        let element = $(e.target);
                        {!! $event  !!}
                    });
                @endif
            @endforeach
        @endif
        $(document).ready(function () {
            const picker = $("#{{ $id }}");
            picker.selectpicker({
                noneSelectedText: '',
            });
            if (EditTarget) {
                if (EditTarget['{{$item['name']}}']) {
                    picker.selectpicker('val', EditTarget['{{$item['name']}}']);
                } else if (EditTarget['data'] && EditTarget['data']['{{$item['name']}}']) {
                    picker.selectpicker('val', EditTarget['data']['{{$item['name']}}']);
                } else {
                    picker.selectpicker('val', picker.data('default'));
                }
                picker.trigger('changed.bs.select')
            }
        });
    </script>

    @if (!defined('form-select-blade'))
        @php(define('form-select-blade', true))
    @endif
@endsection
