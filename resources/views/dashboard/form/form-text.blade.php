@php($id = $formId.$item['id'])
<div class="form-group {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    @if (isset($item['icon']) && !is_null($item['icon']))
        <span class="input-group-prepend">
            <i class="icon {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($item['icon']) }}"></i>
        </span>
    @endif
    <div class="{{ $item['validation'][0] }}">
        <label class="form-control-label">{{ $item['label'] }}</label>
        <input
                id="{{ $id }}"
                name="{{ $item['name'] }}"
                type="{{ isset($item['type']) ? $item['type']: 'text' }}"
                class="form-control"
                @if (isset($item['placeholder']))
                placeholder="{{ $item['placeholder'] }}"
                @endif
                @if (isset($item['value']))
                value="{{$item['value']}}"
                @endif
                @if (isset($item['readonly']) && $item['readonly'])
                readonly="{{ $item['readonly'] }}"
                @endif
                @if (isset($item['pattern']))
                pattern="{{ $item['pattern'] }}"
                @endif
                @if (isset($item['title']))
                title="{{ $item['title'] }}"
                @endif
                @if (isset($item['min-length']))
                minlength='{{$item['min-length']}}'
                @endif
                @if (isset($item['max-length']))
                maxlength='{{$item['max-length']}}'
                @endif
        >
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
                if (typeof data.value === 'string' || typeof data.value === 'number') {
                    $("#{{$id}}").val(data.value);
                } else if (Array.isArray(data.value)) {
                    let strs = [];
                    data.value.forEach((d) => {
                        strs.push(d.name);
                    });
                    $("#{{$id}}").val(strs.join(", "));
                }
            }
        });
        @if (isset($item['mask']))
        $(document).ready(function() {
            const maskOptions = $.extend({}, {
                        @if (isset($item['unmask-on-submit']))
                        removeMaskOnSubmit: true,
                        @endif
                },
                    @if (isset($item['mask-options']))
                        {!! json_encode($item['mask-options']) !!}
                    @else
                        {}
                    @endif
                    );
            $("#{{$id}}").inputmask('{{ $item['mask'] }}', maskOptions);
        });
        @endif
    </script>
    @if (!defined('form-text-blade'))
        @php(define('form-text-blade', true))
    @endif
@endsection