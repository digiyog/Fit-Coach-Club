@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Users | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@endpush

@section('content')
    @if(isset($breadcrumbFilter))
        <!-- Include breadcrumb -->
        @include('admin-panel.layouts.breadcrumb-filter')
        <!--/ Include breadcrumb -->
    @endif
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing custom-datatable-filters hide">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 _layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="custom-datatable-filter _hide">
                            {!! Form::open(['class' => 'custom-datatable-filter-form', 'autoComplete' => 'off']) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="text-primary"> {{ __('language.filters') }} </h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label> Name </label>
                                        {!! Form::text('name', '', ['class' => 'form-control filter-field', 'id' => 'name', 'placeholder' => 'Name', ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 ps-0">
                                    <div class="form-group">
                                        <label> Email </label>
                                        {!! Form::text('email', '', ['class' => 'form-control filter-field', 'id' => 'email', 'placeholder' => 'Email', ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 ps-0">
                                    <div class="form-group">
                                        <label> Mobile Number </label>
                                        {!! Form::text('m_no', '', ['class' => 'form-control filter-field', 'id' => 'm_no', 'placeholder' => 'Mobile Number', ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>{{__('language.users_platform')}}</label>
                                        {!! Form::select('platform_filter', create_select_options(config('constants.platforms'), 'caption', 'value', __('language.select_platform')), null,  ['class' => 'form-control filter-field select-picker', 'id' => 'platform_filter', ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 ps-0">
                                    <div class="form-group">
                                        <label> Date </label>
                                        {!! Form::text('date_range', ($request['date_range'] ?? $request['date_range']), ['class' => 'form-control date_range filter-field date-picker', 'id' => 'date_range', 'autocomplete' => 'off', 'placeholder' => 'Date Range', ]) !!}    
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb2">
                                    {{ Form::button( __('language.filter_apply'), ['class' => 'btn btn-primary apply-filter', 'type' => 'button', 'title' => __('language.filter_apply'), 'name' => 'filter'] )}}
                                    {{ Form::button( __('language.filter_clear'), ['class' => 'btn btn-dark clear-filter', 'type' => 'button', 'title' => __('language.filter_clear'), 'name' => 'clear'] )}}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                    <div class="widget-content widget-content-area br-6">
                        <div class="container-fluid mt2">
                            <div class="row">
                                <div class="col-xl-8 col-lg-8 col-md-8 col-8">
                                    <h4> Users </h4>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive data-table-container mb-2 mt-2">
                            <div class="table-responsive _mb-4">
                                <table id="dataTable" class="table table-hover" data-url="{{ route('adminPanel.users.getUsers') }}"  data-change-status-url="{{ route('adminPanel.users.changeStatus') }}" data-destroy-url="{{ route('adminPanel.users.destroy') }}">
                                    <thead>
                                        <tr>
                                            <th class="checkbox-column"> # </th>
                                            <th> Name </th>
                                            <th> Email </th>
                                            <th> Mobile Number </th>
                                            <th> Registration Date </th>
                                            <th> {{ __('language.users_platform') }}  </th>
                                            <th width="80px"> {{ __('language.table_status') }} </th>
                                            <th width="50px"> Action </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/plugins/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/users/users.js') }}"></script>
@endpush
