@if(is_null($formId) || empty($formId))
    @php($formId = str_random(5))
@endif
<form class="form-layout {{ isset($item['classes.class']) ? $item['classes.class']: null }}" id="{{ $formId }}" enctype="multipart/form-data" method="post" action="{{ isset($actionUrl)? $actionUrl: $metadata->editUrl(isset($target->id) && !is_null($target->id) ? $target->id:null) }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if (isset($options['_return_key']))
        <input type="hidden" id="_return_key" name="_return_key" value="{{$options['_return_key']}}">
    @endif
    @php($fields = $metadata->getUpdateFields($target))
    @foreach($fields as $field)
        @if (!isset($field['extra-options']))
            {!! \App\Extend\HTMLHelpers::render($formId, $field, isset($validation) ? $validation:null) !!}
        @else
			<?php $options = array_merge($options, $field['extra-options']) ?>
        @endif
    @endforeach

    @if (!isset($options['no-buttons']))
        @if (isset($options['single-button']))
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-primary btn-block"name="redirect" value="{{ isset($options['redirect'])? $options['redirect']:$metadata->viewUrl() }}" >@lang('forms.buttons.submit')</button>
                </div>
            </div>
        @else
            <div class="row">
                <div class="offset-lg-6 offset-md-4 offset-sm-0 offset-xs-0 col-lg-3 col-md-4 col-sm-6 col-xs-4">
                    <button class="btn btn-primary btn-block">@lang('forms.buttons.submit_save_and_stay')</button>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-4">
                    <button name="redirect" name="redirect" value="{{ isset($options['redirect'])? $options['redirect']:$metadata->viewUrl() }}" class="btn btn-primary btn-block">@lang('forms.buttons.submit_save_and_return')</button>
                </div>
            </div>
        @endif
    @endif
</form>

@section('styles')
    @parent
    @include('dashboard.partials.forms-css')
@endsection()

@section('scripts')
    @parent
    @include('dashboard.partials.forms-js')

    <script>
        $(document).ready(function() {
            window.EditTarget = <?php echo isset($target)? json_encode($target): 'null'; ?>;
            setTimeout(function () {
                FormEditor.fillForm("{{ $formId }}", EditTarget);
            }, 500);
        });
    </script>
@endsection