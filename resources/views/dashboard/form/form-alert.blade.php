<?php $id = $formId.(isset($item['id']) ? $item['id']:null); ?>
<div class="alert {{ isset($item['classes.class']) ? $item['classes.class'] : null }}">
    @if (isset($item['label']) && !empty($item['label']))
        <strong>{{ $item['label'] }}</strong>
    @endif
    <ul>
        @if (isset($item['messages']))
            @foreach($item['messages'] as $message)
                <li>{{ $message }}</li>
            @endforeach
        @endif
    </ul>
</div>