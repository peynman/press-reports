@php($id = $formId.$item['id'])

<div class="form-group {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    @if (isset($item['icon']) && !is_null($item['icon']))
        <span class="input-group-addon">
            <i class="material-icons">{{$item['icon']}}</i>
        </span>
    @endif
    <div class="form-line {{ $item['validation'][0] }}">
        <label class="form-control-label">{{ $item['label'] }}</label>
        <input
                id="{{ $id }}"
                name="{{ $item['name'] }}"
                @php (isset($item['value']) ?: $item['value'] = [])
                value="{{ implode(',', $item['value']) }}"
                {{--data-role="tagsinput"--}}
                type="text"
                class="form-control focused"
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
    @if (!defined('form-tagsinput-blade'))
    <style>
        .bootstrap-tagsinput {
            display: block;
            font-size: 140%;
        }
        .tag .label .label-info {
            border-radius: 10px !important;
        }
        .bootstrap-tagsinput .tag [data-role="remove"]:after {
            content: '×';
            padding: 0px 2px;
            background-color: white;
            color: red;
            border-radius: 15px 15px;
            margin-top: 1px;
            margin-right: 10px;
        }
    </style>
    @endif
@endsection

@section('scripts')
    @parent

    <script>
        $(document).on(FormEditor.getFieldUpdateEvent('{{$id}}'), function(e, data) {
            if (data && data.value) {
                if (typeof data.value === 'string') {
                    try {
                        data.value = JSON.parse(data.value);
                    } catch (e) {
                        console.log(e);
                    }
                }

                if (Array.isArray(data.value)) {
                    data.value.forEach(function(d) {
                        if (typeof d === 'object') {
                            {{--$("#{{$id}}").tagsinput('add', d['{{isset($item['decorator']) && isset($item['decorator']['title']) ? $item['decorator']['title']:'title'}}']);--}}
                            $("#{{$id}}").val(d['{{isset($item['decorator']) && isset($item['decorator']['title']) ? $item['decorator']['title']:'title'}}']);
                        } else {
                            {{--$("#{{$id}}").tagsinput('add', d);--}}
                            $("#{{$id}}").val(d);
                        }
                    });
                } else {
                    if (typeof data.value === 'object') {
                        $("#{{$id}}").val(data.value['{{isset($item['decorator']) && isset($item['decorator']['title']) ? $item['decorator']['title']:'title'}}']);
                    } else {
                        $("#{{$id}}").val(data.value);
                    }
                    {{--$("#{{$id}}").tagsinput('add', data.value['{{isset($item['decorator']) && isset($item['decorator']['title']) ? $item['decorator']['title']:'title'}}']);--}}
                }
            }
        });
        $(document).on(FormEditor.Events.beforeFormSubmit, function(e, data) {
            let input = $("#{{$id}}");
            // let items = input.tagsinput('items');
            let items = input.val();
            input.val(JSON.stringify(items));
        });
    </script>

    @if (!defined('form-tagsinput-blade'))
        @php(define('form-tagsinput-blade', true))
        <script>
            $(document).ready(function () {
                // $("[data-role='tagsinput']").tagsinput();
            });
        </script>
    @endif
@endsection