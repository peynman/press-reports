@php($id = $formId.$item['id'])
@php($langs = \App\Models\Filter::getByType('language'))
<div class="form-group {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    <label class="form-control-label">{{ $item['label'] }}</label>
    <div class="input-group {{ $item['validation'][0] }}"
         style="direction: ltr;"
    >
        @if (isset($item['icon']) && !is_null($item['icon']))
            <span class="input-group-prepend">
                    <i class="icon {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($item['icon']) }}"></i>
                </span>
        @endif
            <input id="{{ $id }}" name="{{ $item['name'] }}" style="display: none;">
            <input id="{{ $id }}_translations" name="{{ $item['name'] }}_translations" style="display: none;">
            @php($tag = isset($item['tag']) ? $item['tag']:'input')
            @foreach($langs as $lang)
                @php($display = $lang->name === \App\Services\SessionService\SetSessionLocale::getSessionLocale() ? 'block':'none')
                <{{$tag}}
                    id="{{ $id }}{{ $lang->name }}"
                    name="{{ $item['name'] }}{{ $lang->name }}"
                    type="{{ isset($item['type']) ? $item['type']: 'text' }}"
                    class="form-control"
                    placeholder="{{ $item['placeholder'] }}"
                    style="display: {{ $display }}; direction: rtl;"
                    @if (isset($item['value']))
                    value="{{ $item['value'] }}"
                    @endif
                    @if (isset($item['placeholder']))
                    placeholder="{{ $item['placeholder'] }}"
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

                ></{{$tag}}>
            @endforeach
        <span class="input-group-append" style="margin-left: 4px; position: absolute; top: -30px;">
            <div class="btn-group" role="group">
                @foreach($langs as $lang)
                    @php($active = $lang->name === \App\Services\SessionService\SetSessionLocale::getSessionLocale() ? 'active':'')
                    <button style="padding: 5px 4px; margin: 4px 1px; height: 34px;"
                            data-language="{{ $lang->name }}"
                            id="btn_lang{{$id}}{{$lang->name}}"
                            type="button"
                            class="btn btn-secondary pd-x-25 btn-sm tx-20 {{ $active }}">
                        {{ $lang->name }}
                    </button>
                @endforeach
            </div>
        </span>
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
                const defaultIdLocale = '{{ config('app.locale') }}';
                if (typeof data.value === 'string' || typeof data.value === 'number') {
                    $("#{{$id}}"+defaultIdLocale).val(data.value);
                    $("#{{$id}}").val(data.value);
                } else if (Array.isArray(data.value)) {
                    let strs = [];
                    data.value.forEach((d) => {
                        strs.push(d.name);
                    });
                    $("#{{$id}}"+defaultIdLocale).val(strs.join(", "));
                    $("#{{$id}}").val(strs.join(", "));
                }
            }

            if (data && data.target.translations) {
                if (data.target.translations['{{ $item['name'] }}']) {
                    const translations = data.target.translations['{{ $item['name'] }}'];
                    for (let prop in translations) {
                        if (translations.hasOwnProperty(prop)) {
                            $("#{{$id}}"+prop).val(translations[prop]);
                        }
                    }
                }
            }
        });
        $(document).on(FormEditor.Events.beforeFormSubmit, function(e, data) {
            const translations = {};
            const defaultSessionLocale = '{{ \App\Services\SessionService\SetSessionLocale::getSessionLocale() }}';
            @foreach($langs as $lang)
            translations['{{ $lang->name }}'] = $("#{{$id}}{{$lang->name}}").val();
            @endforeach
            $("#{{$id}}_translations").val(JSON.stringify(translations));
            $("#{{$id}}").val(translations[defaultSessionLocale])
        });
        $(document).ready(function() {
            @foreach($langs as $lang)
            $("#btn_lang{{$id}}{{$lang->name}}").on('click', function(e) {
                $("[id^='{{$id}}']").css('display', 'none');
                $("button[id^='btn_lang{{$id}}']").removeClass('active');
                $("#{{$id}}{{$lang->name}}").css('display', 'block');
                $("#btn_lang{{$id}}{{$lang->name}}").addClass('active');
            });
            @endforeach
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
            @foreach($langs as $lang)
                $("#{{$id}}{{$lang->name}}").inputmask('{{ $item['mask'] }}', maskOptions);
            @endforeach
        });
        @endif
    </script>
    @if (!defined('form-text-blade'))
        @php(define('form-text-blade', true))
    @endif
@endsection