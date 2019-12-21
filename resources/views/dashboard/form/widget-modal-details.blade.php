@php($id = $formId.$item['id'])
<div class="modal fade" id="{{$id}}" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            @if (isset($item['options']['title']))
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">{{$item['options']['title']}}</h4>
                </div>
            @endif
            <div class="modal-body">
                @if (isset($item['options']['body']))
                    {!! $item['options']['body']() !!}
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">@lang('dashboard.modal.buttons.back')</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    @parent

    <script>
        $(document).ready(function() {
            $(document).on(FormEditor.Events.elementChanged, function(e, data) {
            });
        });
    </script>
@endsection