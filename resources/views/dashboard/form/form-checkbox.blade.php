@php($id = $formId.$item['id'])
<div class="form-group {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    @if (isset($item['icon']) && !is_null($item['icon']))
        <span class="input-group-prepend">
            <i class="icon {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($item['icon']) }}"></i>
        </span>
    @endif
    <div class="{{ $item['validation'][0] }}">
        <div class="toggle-wrapper" style="position: relative; top: 10px;">
            <div id="{{$id}}toggle" class="toggle toggle-light primary" style="width: 60px; height: 24px;"></div>
        </div>
        <label class="form-control-label" style="width: auto;">
            {{ $item['label'] }}
        </label>
        <input
                style="display: none"
                id="{{ $id }}"
                name="{{ $item['name'] }}"
                type="checkbox"
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
        $(document).ready(() => {
            $(document).on(FormEditor.getFieldUpdateEvent('{{$id}}'), function(e, data) {
                if (data && data.value) {
                    const toggle = $("#{{ $id }}toggle").data('toggles');
                    if (data.value === "on" || data.value === true || data.value === "true" || data.value === 1) {
                        toggle.toggle(true);
                    } else {
                        toggle.toggle(false);
                    }
                }
            });

            $("#{{ $id }}toggle").toggles({
                height: 26,
                checkbox: $("#{{$id}}"),
                on: {{ isset($item['value']) && $item['value'] === "on" ? 'true':'false' }},
            });

        });
    </script>
@endsection