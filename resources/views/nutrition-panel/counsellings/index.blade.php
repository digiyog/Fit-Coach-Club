@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Counsellings | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/widgets/modules-widgets.css') }}">
@endpush

@section('content')
    @if(isset($breadcrumbFilter))
        <!-- Include breadcrumb -->
        @include('nutrition-panel.layouts.breadcrumb-filter')
        <!--/ Include breadcrumb -->
    @endif
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing custom-datatable-filters hide">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 _layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="custom-datatable-filter _hide">
                            {!! Form::open(['class' => 'custom-datatable-filter-form']) !!}
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label> Date </label>
                                            {!! Form::text('date', ($request['date'] ?? $request['date']), ['class' => 'form-control filter-field date-picker', 'id' => 'date', 'autocomplete' => 'off', 'placeholder' => 'Date Range', ]) !!}    
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Month</label>
                                            {!! Form::select('month', [
                                                '01' => 'January',
                                                '02' => 'February',
                                                '03' => 'March',
                                                '04' => 'April',
                                                '05' => 'May',
                                                '06' => 'June',
                                                '07' => 'July',
                                                '08' => 'August',
                                                '09' => 'September',
                                                '10' => 'October',
                                                '11' => 'November',
                                                '12' => 'December'
                                            ], date('m'), ['class' => 'form-control select-picker', 'id' => 'month']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Year</label>
                                            @php
                                                $years = [];
                                                for ($y = date('Y'); $y >= 2000; $y--) {
                                                    $years[$y] = $y;
                                                }
                                            @endphp
                                            {!! Form::select('year', $years, date('Y'), ['class' => 'form-control select-picker', 'id' => 'year']) !!}
                                        </div>
                                    </div> -->
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

        <div class="row layout-top-spacing mt-3 pl-3 pr-3 align-items-stretch">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 p-0 layout-spacing text-center">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100">
                                    <h4 class="text-dark font-weight-bold s-counter1">{{ $todayAttendences ?? 0 }}</h4>
                                    <h6 class="value">Today Total Shakes</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 p-0 pl-3 layout-spacing text-center">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100">
                                    <h4 class="text-dark font-weight-bold s-counter2">{{ $todayAttendencesRegularUser ?? 0 }}</h4>
                                    <h6 class="value">Regular Users</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 p-0 pl-3 layout-spacing text-center">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100">
                                    <h4 class="text-dark font-weight-bold s-counter3">{{ $todayAttendencesTrail ?? 0 }}</h4>
                                    <h6 class="value">Trial Users</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 p-0 pl-3 layout-spacing text-center">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100">
                                    <h4 class="text-dark font-weight-bold s-counter4">{{ $todayAttendencesDemo ?? 0 }}</h4>
                                    <h6 class="value">Demo Users</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-8 col-8">
                                <h4>Counsellings (Today) </h4>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive data-table-container mb-4 mt-2">
                        <div class="table-responsive _mb-4">
                            <table id="dataTable" class="table table-hover" data-url="{{ route('nutritionPanel.counsellings.getCounsellings') }}">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Coach Name</th>
                                        <th>Attendance Count</th>
                                        <th>Pending Days</th>
                                        <th>Current Meal</th>
                                        <th>Date of Attendance</th>
                                        <th class="text-right"> {{ __('language.table_action') }} </th>
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
<script src="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/counsellings/view.js') }}"></script>
@endpush
