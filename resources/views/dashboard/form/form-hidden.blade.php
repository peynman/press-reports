@php($id = $formId.$item['id'])
<input
        type="hidden"
        id="{{ $id }}"
        name="{{ $item['name'] }}"
        @if (isset($item['value']))
            value="{{ $item['value'] }}"
        @endif
>