<div class="slim-navbar">
    <div class="container">
        <ul class="nav">
            @php($sidebar_items = $sidebar['items'])
            @foreach($sidebar_items as $item)
                {!! $menuRenderService->render($item, $actives) !!}
            @endforeach
        </ul>
    </div>
</div>