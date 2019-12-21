@if(is_null($formId) || empty($formId))
    @php($formId = str_random(5))
@endif
<form class="form-layout parsley-style-1 {{ isset($item['classes.class']) ? $item['classes.class']: null }}" data-parsley-validate id="{{ $formId }}" enctype="multipart/form-data" method="post" action="{{ isset($actionUrl)? $actionUrl: $metadata->createUrl() }}">
    {{ csrf_field() }}

    @if (isset($options['hidden']))
        {!! $options['hidden']($metadata, $validation, $options) !!}
    @endif

    @php($fields = $metadata->getCreateFields())

    @foreach($fields as $field)
        @if (!isset($field['extra-options']))
            {!! \App\Extend\HTMLHelpers::render($formId, $field, isset($validation) ? $validation:null) !!}
        @else
            <?php $options = array_merge($options, $field['extra-options']) ?>
        @endif
    @endforeach
    <div class="form-layout-footer">
        <div class="row">
            @if (!isset($options['no-buttons']))
                @if (isset($options['single-button']))
                    <div class="col-xs-12">
                        <button name="redirect" value="{{ isset($options['redirect'])? $options['redirect']:$metadata->viewUrl() }}" class="btn btn-primary btn-block">
                        <span>
                        @lang('forms.buttons.ok')
                        </span>
                        </button>
                    </div>
                @else
                    <div class="offset-lg-6 offset-md-4 offset-sm-0 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <button class="btn btn-primary btn-block">
                        <span>
                            @lang('forms.buttons.submit_and_new')
                        </span>
                        </button>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <button name="redirect" value="{{ isset($options['redirect'])? $options['redirect']:$metadata->viewUrl() }}" class="btn btn-primary btn-block">
                        <span>
                            @lang('forms.buttons.submit_and_return')
                        </span>
                        </button>
                    </div>
                @endif
            @endif
        </div>
    </div>
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
            window.EditTarget = {!! json_encode($target);  !!}
            setTimeout(function () {
                console.log(EditTarget);
                FormEditor.fillForm("{{ $formId }}", EditTarget);
            }, 100);
            $("#{{$formId}}").parsley();
        });
    </script>
@endsection