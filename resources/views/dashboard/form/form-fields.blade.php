@stack('styles')
@yield('styles')

@foreach($fields as $field)
    @if (isset($field['objects']) && is_array($field['objects']))
        @if (isset($field['objects']['method']))
            @switch($field['objects']['method'])
                @case('call_user_func')
                    @if (count($field['objects']['args']) > 1)
                        @php($field['objects'] = call_user_func($field['objects']['obj'], $field['objects']['args']))
                    @else
                        @php($field['objects'] = call_user_func($field['objects']['obj'], $field['objects']['args'][0]))
                    @endif
                @break
            @endswitch
        @endif
    @endif

    {!! App\Extend\HTMLHelpers::render($formId, $field, $validations, $viewGroup) !!}
@endforeach

@stack('scripts')
@yield('scripts')
