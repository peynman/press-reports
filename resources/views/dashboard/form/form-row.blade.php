<div class="form-row {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    @foreach($items as $item)
        {!! \App\Extend\HTMLHelpers::render($formId, $item, $validation) !!}
    @endforeach
</div>