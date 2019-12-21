@php($id = $formId.$item['id'])
<div class="form-group {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    <div class="{{ $item['validation'][0] }}">
        <input type="hidden" id="{{$id}}" name="{{$item['name']}}">
        <div id="{{$id}}-holder"></div>
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
    @if (!defined('form-json-schema-blade'))
        <link rel="stylesheet" type="text/css" href="{{{ asset('lib/json-editor/css/jsoneditor.min.css') }}}">
    @endif
@endsection

@section('scripts')
    @parent

    <script>
        $(document).ready(function() {
            const holder = document.getElementById('{{ $id }}-holder');
            const editor = new JSONEditor(holder, {
                schema: {
                    type: '{{ isset($item['schema']['type']) ? $item['schema']['type']: 'object' }}',
                    title: '{{ isset($item['schema']['title']) ? $item['schema']['title']: $item['label'] }}',
                    properties: {!! json_encode($item['schema']['properties']) !!},
                },
                theme: 'bootstrap4',
                iconlib: 'bootstrap4',
                disable_collapse: true,
                disable_edit_json: true,
            });

            $(document).on(FormEditor.getFieldUpdateEvent('{{$id}}'), function(e, data) {
                if (data && data.value) {
                    if (typeof data.value === 'string') {
                        editor.setValue(JSON.parse(data.value));
                    } else {
                        editor.setValue(data.value);
                    }
                }
            });
            $(document).on(FormEditor.Events.beforeFormSubmit, function(e, data) {
                $("#{{$id}}").val(JSON.stringify(editor.getValue()));
            });
        });
    </script>
    @if (!defined('form-json-schema-blade'))
        @php(define('form-json-schema-blade', true))
        <script src="{{{ asset('lib/json-editor/cleave.min.js') }}}"></script>
        <script src="{{{ asset('lib/json-editor/jsoneditor.min.js') }}}"></script>
    @endif
@endsection