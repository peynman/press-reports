@php($id = $formId.$item['id'])
<div class="form-group {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    @if (isset($item['icon']) && !is_null($item['icon']))
        <span class="input-group-addon">
            <i class="material-icons">{{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($item['icon']) }}</i>
        </span>
    @endif
    <div class="form-line {{ $item['validation'][0] }}">
        <label class="form-control-label">{{ $item['label'] }}</label>
        <textarea
                id="{{ $id }}"
                name="{{ $item['name'] }}"
                class="form-control"
                placeholder="{{ $item['placeholder'] }}"
                @if (isset($item['readonly']) && $item['readonly'])
                readonly="{{ $item['readonly'] }}"
                @endif
        >{{ isset($item['value']) ? $item['value']:null }}</textarea>
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
            }
        });
    </script>
    @if (!defined('form-text-blade'))
        @php(define('form-text-blade', true))
    @endif
@endsection