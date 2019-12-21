@php($id = $formId.$item['id'])
<div class="form-group {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    @if (isset($item['icon']) && !is_null($item['icon']))
        <span class="input-group-addon">
            <i class="material-icons">{{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($item['icon']) }}</i>
        </span>
    @endif
    <div class="form-line {{ $item['validation'][0] }}">
        <label class="form-control-label">{{ $item['label'] }}</label>
        <input
                id="{{ $id }}"
                name="{{ $item['name'] }}"
                type="file"
                class="form-control"
                placeholder="{{ $item['placeholder'] }}"
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

@section('styles')
    @parent

    @if (!defined('form-upload-blade'))
        <link rel="stylesheet" type="text/css" href="{{{ asset(('bsbmd/plugins/dropzone/min/dropzone.min.css')) }}}">
    @endif
@endsection

@section('scripts')
    @parent

    <script>
        $(document).on(FormEditor.getFieldUpdateEvent('{{$id}}'), function(e, data) {
            if (data && data.value) {
                $("#{{$id}}").val(data.value);
            }
        });
    </script>
    @if (!defined('form-upload-blade'))
        @php(define('form-upload-blade', true))
        <script src="{{ asset('bsbmd/plugins/dropzone/min/dropzone.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                // $("input[type=file]").dropzone({
                //     url: '/'
                // });
            })
        </script>
    @endif
@endsection