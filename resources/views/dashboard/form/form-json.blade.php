@php($id = $formId.$item['id'])
<div class="form-group {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    @if (isset($item['icon']) && !is_null($item['icon']))
        <span class="input-group-addon">
            <i class="material-icons">{{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($item['icon']) }}</i>
        </span>
    @endif
    <div class="{{ $item['validation'][0] }}">
        <label class="form-control-label">{{ $item['label'] }}</label>
        <input id="{{ $id }}" name="{{ $item['name'] }}" type="hidden">
        <div style="display: flex">
            <div id="{{ $id }}-editor"  style="flex: 1; height: 300px;"></div>
        </div>
    </div>
    @if (!empty($item['validation'][1]))
        <div class="form-control-feedback">{!! $item['validation'][1] !!}</div>
    @endif
    @if (isset($item['help']))
        <div class="help-info">{{ $item['help'] }}</div>
    @endif
</div>

@section('styles')
    @parent
    @if (!defined('form-json-blade'))
        <link rel="stylesheet" type="text/css" href="{{{ asset(('css/jsoneditor.css')) }}}">
    @endif
@endsection

@section('scripts')
    @parent

    <script>
        $(document).ready(function() {
            const container = document.getElementById("{{$id}}-editor");
            const schemaContainer = document.getElementById("{{$id}}-editor-schema");
            const editor = new JSONEditor(container, {
                mode: 'code',
                statusBar: false,
                mainMenuBar: false,
            });

            $(document).on(FormEditor.getFieldUpdateEvent('{{$id}}'), function(e, data) {
                if (data && data.value) {
                    if (typeof data.value === 'string') {
                        editor.set(JSON.parse(data.value));
                    } else {
                        editor.set(data.value);
                    }
                }
            });
            $(document).on(FormEditor.Events.beforeFormSubmit, function(e, data) {
                $("#{{$id}}").val(JSON.stringify(editor.get()));
            });
        });
    </script>
    @if (!defined('form-json-blade'))
        @php(define('form-json-blade', true))
        <script src="{{{ asset('js/jsoneditor.js') }}}"></script>
    @endif
@endsection