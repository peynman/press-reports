<div class="form-group {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    <button class="btn {{ isset($item['options']['color']) ? $item['options']['color']:'btn-primary' }} btn-block"
            id="{{ $item['id'] }}"
            name="{{ $item['name'] }}"
            @if (isset($item['options']['value']))
                value="{{ $item['options']['value'] }}"
            @endif
            @if (isset($item['options']['onclick']))
                onclick="{{ $item['options']['onclick'] }}"
                type="button"
            @endif
        >
        <span>
            {!! $item['options']['label'] !!}
        </span>
    </button>
</div>