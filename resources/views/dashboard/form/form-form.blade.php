@if(is_null($formId) || empty($formId))
    @php($formId = str_random(5))
@endif
<form id="{{ $formId }}"
      class="form-layout {{ isset($item['classes.class']) ? $item['classes.class']: null }}"
      enctype="multipart/form-data"
      @if(isset($actionUrl))
              action="{{ $actionUrl }}"
      @endif
      method="post"
>
    {{ csrf_field() }}
    @if (isset($options['hidden']))
        {!! $options['hidden']($metadata, $validation, $options) !!}
    @endif
    @foreach($fields as $field)
        {!! \App\Extend\HTMLHelpers::render($formId, $field, isset($validation) ? $validation:null) !!}
    @endforeach
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
            $("#{{$formId}}").parsley();
        });
    </script>
@endsection