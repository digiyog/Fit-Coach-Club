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
                                            {!! Form::text('date', request('date', ''), ['class' => 'form-control filter-field date-picker', 'id' => 'date', 'autocomplete' => 'off', 'placeholder' => 'Date Range', ]) !!}    
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

        <!-- 4 Modern Stat Cards -->
        <div class="stats-4-grid">
            <div class="stat-modern-card stat-blue">
                <div class="stat-icon-wrapper">
                    <i class="fa fa-coffee"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Today Total Shakes</span>
                    <h3 class="stat-value s-counter1">{{ $todayAttendences ?? 0 }}</h3>
                </div>
            </div>

            <div class="stat-modern-card stat-emerald">
                <div class="stat-icon-wrapper">
                    <i class="fa fa-users"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Regular Users</span>
                    <h3 class="stat-value s-counter2">{{ $todayAttendencesRegularUser ?? 0 }}</h3>
                </div>
            </div>

            <div class="stat-modern-card stat-amber">
                <div class="stat-icon-wrapper">
                    <i class="fa fa-user-plus"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Trial Users</span>
                    <h3 class="stat-value s-counter3">{{ $todayAttendencesTrail ?? 0 }}</h3>
                </div>
            </div>

            <div class="stat-modern-card stat-purple">
                <div class="stat-icon-wrapper">
                    <i class="fa fa-user-circle"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Demo Users</span>
                    <h3 class="stat-value s-counter4">{{ $todayAttendencesDemo ?? 0 }}</h3>
                </div>
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
                                        <th class="text-end"> {{ __('language.table_action') }} </th>
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
