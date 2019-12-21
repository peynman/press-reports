@extends('dashboard.dashboard')

@section('page-title', trans('dashboard.pages.reports.title'))

@section('content')
    <div class="container">
        @if(!isset($formId) || is_null($formId) || empty($formId))
            @php($formId = str_random(5))
        @endif
        <form id="{{ $formId }}" enctype="multipart/form-data" method="post" action="#" enctype="multipart/form-data">
            {{ csrf_field() }}
            @if (isset($options['_return_key']))
                <input type="hidden" id="_return_key" name="_return_key" value="{{$options['_return_key']}}">
            @endif

            @if ($metadata instanceof \App\Services\Metrics\IMetricsReportMetaData)
                <div class="row">
                    @php($fields = $metadata->getReportMetrics())
                    @php($user = \Illuminate\Support\Facades\Auth::user())
				    <?php
				    $sorted = [];
				    foreach ($fields as $indexer => $field) {
					    $sorted[] = array_merge($field, [
						    'on_dashboard' => \App\Models\Settings::getSettings( 'metrics.' . $field['id'] . '.on_dashboard', false, $user->id),
						    'range' => \App\Models\Settings::getSettings( 'metrics.' . $field['id'] . '.range', 'today', $user->id),
						    'zorder' => \App\Models\Settings::getSettings( 'metrics.' . $field['id'] . '.zorder', $indexer, $user->id),
					    ]);
				    }
				    usort($sorted, function($a, $b) {
					    return $a['zorder'] <=> $b['zorder'];
				    });
				    ?>
                    @foreach($sorted as $field)
                        {!! \App\Extend\HTMLHelpers::render($formId, $field, isset($validation) ? $validation:null) !!}
                    @endforeach
                </div>
            @endif
        </form>
    </div>
@endsection


@section('styles')
    @parent

    @include(\App\Http\Controllers\Dashboard\DashboardCRUDViewProvider::getThemeViewName('dashboard.partials.forms-css'))
    @include(\App\Http\Controllers\Dashboard\DashboardCRUDViewProvider::getThemeViewName('dashboard.partials.tables-css'))
@endsection

@section('scripts')
    @parent

    @include(\App\Http\Controllers\Dashboard\DashboardCRUDViewProvider::getThemeViewName('dashboard.partials.forms-js'))
    @include(\App\Http\Controllers\Dashboard\DashboardCRUDViewProvider::getThemeViewName('dashboard.partials.tables-js'))
    @include(\App\Http\Controllers\Dashboard\DashboardCRUDViewProvider::getThemeViewName('dashboard.partials.chartjs-js'))

    @include('bridges.js-bridge')

    <script>
        $(document).ready(function () {

        });
    </script>

@endsection