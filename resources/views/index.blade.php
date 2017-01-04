@extends(\Request::ajax() ? 'backend::layouts.empty' : 'backend::layouts.master')
@section('content')
    <div class="portlet box blue-hoki ajax-portlet" data-require="">
        <div class="portlet-title">
            <div class="caption">
                {{trans('settings::common.settings')}}</div>
            <div class="actions"></div>
        </div>

        <div class="portlet-body"></div>
    </div>
@stop
