@if ($is_sub_menu)
    <li><a href="{{ $item->url() }}">{{ $item->title() }}</a></li>
@else
    <li class="nav-item {{ $active_class }}">
        <a class="nav-link" href="{{ $item->url() }}">
            <i class="icon {{ \App\Services\Rendering\Menu\BaseMenuRenderService::getMappedIcon($item->icon()) }}"></i>
            <span>{{ $item->title() }}</span>
        </a>
    </li>
@endif