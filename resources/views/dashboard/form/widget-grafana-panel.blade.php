<div class="{{$item['options']['size']}}">
    <button
            name="toggle_widget"
            value="{{$item['options']['dashboard']}}:{{$item['options']['panel']}}"
            class="btn btn-default btn-xs waves-effect"
            style="position: relative; top: 18px; left: -12px; width: 30px; height: 30px;"
            type="submit">
        <i class="material-icons {{$item['options']['toggle']['color']}}">{{$item['options']['toggle']['icon']}}</i>
    </button>
    <iframe
            id="{{$item['id']}}"
            src="{{ route('dashboard.any.grafana.proxy', $item['options']['url'])  }}"
            frameborder="0">
    </iframe>
</div>

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            let element = $("#{{$item['id']}}");
            element.css('width', '100%');
            element.css('height', element.outerWidth() * parseFloat('{{isset($item['options']['aspect']) ? $item['options']['aspect']:0.34}}'));
        })
    </script>
@endsection