@php($id = $formId.$item['id'])
<div class="form-group {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    @if (isset($item['icon']) && !is_null($item['icon']))
        <span class="input-group-addon">
            <i class="material-icons">{{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($item['icon']) }}</i>
        </span>
    @endif
    <div class="form-line {{ $item['validation'][0] }}">
        <label class="form-control-label">{{ $item['label'] }}</label>
        <div id="{{$id}}"></div>
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
            let dataObj = {};
            @if (empty($item['exclude-form']))
                let data = $('#{{$formId}}').serializeArray();
                data.forEach((obj) => {
                    dataObj[obj.name] = obj.value;
                });
            @endif
            const headers = {
                'X-CSRF-TOKEN': window.xhrHeaders['X-CSRF-TOKEN'],
                'Authorization': window.xhrHeaders['Authorization']
            };
            const url = '{{ isset($item['url']) ? $item['url']: \Illuminate\Http\Request::createFromGlobals()->url() }}';

            $('#{{ $id }}').plupload({
                // General settings
                runtimes: 'html5,flash,silverlight,html4',
                url: url,
                chunk_size: '1mb',
                rename: true,
                sortable: true,
                dragdrop: true,

                @if (isset($item['resize']))
                resize : {
                    width : {{ $item['resize']['width'] }},
                    height : {{ $item['resize']['height'] }},
                    quality : {{ $item['resize']['quality'] }},
                    crop: {{ $item['resize']['crop'] }},
                },
                @endif
                // Resize images on clientside if we can

                // Views to activate
                views: {
                    list: false,
                    thumbs: true, // Show thumbs
                    active: 'thumbs'
                },
                // add X-CSRF-TOKEN in headers attribute to fix this issue
                headers: headers,
                // add more overrides; see documentation...
                multipart_params: dataObj,
                filters : {
                    max_file_size : '4000mb',
                    mime_types: {!! json_encode($item['mime_types']) !!},
                },
                file_data_name: '{{ $item['name'] }}',
            });
            const uploader = $('#{{ $id }}').plupload('getUploader');
            uploader.bind('FilesRemoved', function(up, files) {
                files.forEach(function(file) {
                    if (file.status === plupload.DONE) {
                        fetch(url, {
                            method: 'POST',
                            body: JSON.stringify($.extend(dataObj, {
                                remove: {
                                    id: '{{ $item['id'] }}',
                                    fileId: file.remoteId,
                                },
                            })),
                            headers: $.extend(headers, {
                                'Content-Type': 'application/json',
                            }),
                        }).
                        then(function(data) {
                            console.log(data);
                        }).
                        catch(function(error) {
                            console.error(error);
                        });
                    }
                });
            });
            uploader.bind('UploadComplete', function(up, files) {
            });
            uploader.bind('QueueChanged', function(up) {
            });
            @if (isset($item['value']))
                const value_s = {!! json_encode($item['value']) !!}
                if (Array.isArray(value_s)) {
                    value_s.forEach(function(value) {
                        if (value && value.mime && value.title) {
                            const file = new plupload.File({size: value.size});
                            file.status = plupload.DONE;
                            file.remoteId = value.id;
                            uploader.addFile(file, value.title);
                        }
                    })
            }
            @endif
        })
    </script>
@endsection