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
                type="text"
                class=""
                @if (isset($item['placeholder']))
                placeholder="{{ $item['placeholder'] }}"
                @endif
                @if (isset($item['value']))
                value="{{is_array($item['value']) ? $item['value'][0]:$item['value']}}"
                @endif
                @if (isset($item['readonly']) && $item['readonly'])
                readonly="{{ $item['readonly'] }}"
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
        $(document).ready(function() {
            $(document).on(FormEditor.getFieldUpdateEvent('{{$id}}'), function(e, data) {
                if (data && data.value) {
                    if (typeof data.value === 'string') {
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
            $("#{{ $id }}").spectrum({
                showAlpha: true,
                showPalette:true,
                preferredFormat: "hex",
                palette: [
                    {!! json_encode(isset($item['pallet']) ? $item['pallet']: []) !!}
                ]
            })
        })
    </script>
@endsection