@php($id = $formId.$item['id'])
<div class="form-group {{ isset($item['classes.class']) ? $item['classes.class']: null }}">
    @if (isset($item['icon']) && !is_null($item['icon']))
        <span class="input-group-addon">
            <i class="material-icons">{{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($item['icon']) }}</i>
        </span>
    @endif
    <div class="form-line {{ $item['validation'][0] }}">
        <label class="form-control-label">{{ $item['label'] }}</label>
        {!! captcha_img() !!}
        <input
                id="{{ $id }}"
                name="{{ $item['name'] }}"
                type="text"
                class="form-control"
                @if (isset($item['placeholder']) && $item['placeholder'])
                    placeholder="{{ $item['placeholder'] }}"
                @endif
                @if (isset($item['readonly']) && $item['readonly'])
                readonly="{{ $item['readonly'] }}"
                @endif
        >
    </div>
    @if (!empty($item['validation'][1]))
        {!! $item['validation'][1] !!}
    @endif
    @if (isset($item['help']))
        <div class="help-info">{{ $item['help'] }}</div>
    @endif
</div>