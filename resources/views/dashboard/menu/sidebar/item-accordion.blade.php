@if ($is_sub_menu)
    <li class="sub-with-sub">
        <a href="{{ $item->url() }}">@lang($item->title())</a>
        <ul>
            @php($children = $item->getItems())
            @foreach($children as $child)
                {!! $renderer->render($child, $actives, true) !!}
            @endforeach
        </ul>
    </li>
@else
    <li class="nav-item with-sub {{ $active_class }}">
        <a class="nav-link" href="#" data-toggle="dropdown">
            <i class="icon {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($item->icon()) }}"></i>
            <span>@lang($item->title())</span>
        </a>
        <div class="sub-item">
            <ul>
                @php($children = $item->getItems())
                @foreach($children as $child)
                    {!! $renderer->render($child, $actives, true) !!}
                @endforeach
            </ul>
        </div>
    </li>
@endif