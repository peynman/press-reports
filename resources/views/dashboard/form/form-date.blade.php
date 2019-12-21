@php($id = $formId.$item['id'])
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
            <input
                    id="{{ $id }}"
                    name="{{ $item['name'] }}"
                    type="text"
                    class="form-control datepicker"
                    placeholder="{{ $item['placeholder'] }}"
                    @if (isset($item['value']))
                    value="{{ $item['value'] }}"
                    @endif
                    @if (isset($item['readonly']) && $item['readonly'])
                    readonly="{{ $item['readonly'] }}"
                    @endif
            >
            {{--<span class="input-group-append">--}}
                {{--<div class="btn-group" role="group">--}}
                    {{--<button id="btn_calendar{{$id}}g" data-calendar="gregorian" type="button" class="btn btn-secondary pd-x-25 btn-sm tx-20"><i class="icon ion-md-calendar"></i></button>--}}
                    {{--<button id="btn_calendar{{$id}}h" data-calendar="hijri" type="button" class="btn btn-secondary pd-x-25 btn-sm tx-20"><i class="icon ion-ios-moon"></i></button>--}}
                    {{--<button id="btn_calendar{{$id}}j" data-calendar="jalali" type="button" class="btn btn-secondary pd-x-25 btn-sm tx-20"><i class="icon ion-ios-sunny"></i></button>--}}
                {{--</div>--}}
            {{--</span>--}}
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
    @if (!defined('form-date-blade'))
        <link rel="stylesheet" type="text/css" href="{{{ asset(('lib/persian-datetime-picker/persian-date.css')) }}}">
    @endif
@endsection

@section('scripts')
    @parent

    <script>
        $(document).on(FormEditor.getFieldUpdateEvent('{{$id}}'), function(e, data) {
            if (data && data.value) {
                $("#{{$id}}").val(data.value);
                console.log('val', data);
                $('#btn_calendar{{$id}}g').trigger('click');
                $("#{{$id}}").data('calendar', 'gregorian');
            }
        });

        $(document).ready(function () {
            $("#{{$id}}").on('change', function(e) {

            });
            $('[id^="btn_calendar{{$id}}"]').on('click', function (e) {
                $('[id^="btn_calendar{{$id}}"]').removeClass('active');
                let el = $(e.target);
                if (el.prop('tagName') === 'I') {
                    el = el.parent();
                }
                const calendar = el.data('calendar');
                $('[id^="btn_calendar{{$id}}"][data-calendar^="'+calendar+'"]').addClass('active');
                const val = $("#{{$id}}").val();
                console.log(val);
                if (val) {
                    const current_cal = $("#{{$id}}").data('calendar');
                    let curr_time = null;
                    switch (current_cal) {
                        case 'gregorian':
                            curr_time = moment(val, 'YYYY-M-D HH:mm:ss');
                            break;
                        case 'jalali':
                            curr_time = moment(val, 'jYYYY-jM-jD HH:mm:ss');
                            break;
                        case 'hijri':
                            curr_time = momentHijri(val, 'iYYYY-iM-iD HH:mm:ss');
                            break;
                    }
                    switch (calendar) {
                        case 'gregorian':
                            console.log();
                            break;
                        case 'hijri':

                            break;
                        case 'jalali':

                            break;
                    }
                }
            });
            @if (isset($item['mask']))
                $("#{{$id}}").inputmask('{{$item['mask']}}');
            @else
                @if ($item['input'] === 'datetime')
                    $("#{{$id}}").inputmask('9999-99-99 99:99:99');
                @else
                    $("#{{$id}}").inputmask('9999-99-99');
                @endif
            @endif
        });
        $()
    </script>

    @if (!defined('form-date-blade'))
        @php(define('form-date-blade', true))
    @endif
@endsection