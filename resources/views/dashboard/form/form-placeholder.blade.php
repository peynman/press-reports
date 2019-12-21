<div id="{{ $item["id"] }}"></div>

@section('styles')
    @parent

    @if (!defined('templates-component-placeholder'))
    @endif
@endsection

@section('scripts')
    @parent

    <script>
        $(document).ready(function() {
            const LoadingHTML = '';
            const PlaceholderID = '{{ $item["id"] }}';
            const LoadingIds = {!! isset($item["loading-ids"]) ? json_encode($item["loading-ids"]):'[]' !!};
            const UpdateIds = {!! isset($item["update-ids"]) ? json_encode($item["update-ids"]):'[]'  !!};
            const ContentSources = {!! isset($item["content-sources"]) ? json_encode($item["content-sources"]):'[]' !!};
            const ClearIds = {!! isset($item["clear-ids"]) ? json_encode($item["clear-ids"]):'[]'  !!};
            const Autoloads = {!! isset($item["autoload"]) ? json_encode($item["autoload"]):'[]' !!};

            if (Array.isArray(Autoloads)) {
                Autoloads.forEach(function(load) {
                    if (ContentSources.hasOwnProperty(load)) {
                        $.globalEval(ContentSources[load]);
                    }
                })
            }
            $(document).on(Page.Events.PlaceholderLoadContent, function(e, d) {
                if (ContentSources.hasOwnProperty(d.id)) {
                    $.globalEval(ContentSources[d.id])

                }
            });
            $(document).on(Page.Events.PlaceholderStartedLoading, function(e, d) {
                if (LoadingIds.includes(d.updateId) || PlaceholderID == d.updateId) {
                    const el = $("#{{ $item["id"] }}");
                    el.html(LoadingHTML);
                } else if (ClearIds.includes(d.updateId) || PlaceholderID == d.updateId) {
                    const el = $("#{{ $item["id"] }}");
                    el.html("");
                }
            });
            $(document).on(Page.Events.PlaceholderEndedLoading, function(e, d) {
                if (UpdateIds.includes(d.updateId) || PlaceholderID == d.updateId) {
                    @if (isset($item["replace-id"]))
                        const replace = $("#{{ $item["replace-id"] }}", d.temp)
                            .clone()
                            .attr('id', "{{ $item['id'] }}");
                    @else
                        const replace = d.html;
                    @endif

                    const el = $("#{{ $item["id"] }}");
                    @if (isset($item["append"]) && $item['append'])
                        el.html(replace);
                    @else
                        el.replaceWith(replace);
                    @endif
                }
            });
        });
    </script>
@endsection