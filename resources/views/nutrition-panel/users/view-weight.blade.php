@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'View Weights | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="{{ asset('admin-assets/plugins/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">

<style type="text/css">
    .w-info{
        height: 125px;
    }
</style>
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
                                            {!! Form::text('date_range', request('date_range', ''), ['class' => 'form-control date_range filter-field date-picker', 'id' => 'date_range', 'autocomplete' => 'off', 'placeholder' => 'Date Range', ]) !!}    
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
                                <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
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

        <div class="row align-strech mt-3">

            <div class="col-md-12 col-sm-12 col-xs-12 mb-4">
                <a class="btn btn-primary me-2" href="{{ route('nutritionPanel.users.viewWeights', ['id' => ev($user->id)]) }}">
                    <i class="fa fa-eye"></i> 
                    View Weight
                </a>
            
                <a class="btn btn-primary me-2" href="{{ route('nutritionPanel.users.viewAttendance', ['id' => ev($user->id)]) }}">
                    <i class="fa fa-eye"></i> 
                    View Attendance
                </a>

                <a class="btn btn-primary me-2" href="{{ route('nutritionPanel.manual-attendances.manual-attendance', ['id' => ev($user->id)]) }}">
                    <i class="fa fa-eye"></i> 
                    Manual Attendance
                </a>

                <a class="btn btn-primary me-2" href="{{ route('nutritionPanel.track-shake.index', ['id' => ev($user->id)]) }}">
                    <i class="fa fa-eye"></i> 
                    Track Shake
                </a>

                <a class="btn btn-primary me-2" href="{{ route('nutritionPanel.orders.index', ['id' => ev($user->id)]) }}">
                    <i class="fa fa-eye"></i> 
                    Purchase Products
                </a>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing pb-3">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100 p-3">
                                    <h6 class="value pb-2">Counseling weight graph for</h6>
                                    <h4 class="text-dark font-weight-bold">{{ $user['name'] ?? 'N/A' }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing pb-3">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100 p-3">
                                    <h6 class="value pb-2">Weight Difference (Today Weight - Previous Weight)</h6>
                                    <h4 class="text-dark font-weight-bold">
                                        {{ number_format((($lastRecord['weight'] ?? 0) - ($secondLastRecord['weight'] ?? 0)) * 1000, 2) }} Gram
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing pb-3">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100 p-3">
                                    <h6 class="value pb-2">Total Weight Diff Till Now</h6>
                                    <h4 class="text-dark font-weight-bold">{{ ($lastRecord['weight'] ?? 0) - ($firstRecord['weight'] ?? 0) }} Kg</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-chart-one">
                    <div class="widget-content p-4">
                        <div class="tabs tab-content">
                            <div id="content_1" class="tabcontent"> 
                                <div id="weightGraph"></div>
                            </div>
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
                                <h5>Previous Weight History Listings </h5>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive data-table-container mb-4 mt-2">
                        <div class="table-responsive _mb-4">
                            <table id="dataTable" class="table table-hover" data-url="{{ route('nutritionPanel.users.getViewWeights') }}">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <!-- <th>Name</th> -->
                                        <th>Weight</th>
                                        <th>Weight Image</th>
                                        <th>Date</th>
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
<script src="{{ asset('admin-assets/plugins/apex/apexcharts.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/users/view-weight.js') }}"></script>

<script type="text/javascript">
    var weightDates = @json($weightDates);
    var weightValues = @json($weightValues);

    var options = {
        chart: {
            type: 'line',
            height: 380,
            toolbar: { show: false }
        },

        title: {
            text: "Weight Progress Graph",
            align: "center",
            style: { fontSize: '22px', fontWeight: 700 }
        },

        series: [{
            name: "User Weight",
            style: { fontSize: '22px', fontWeight: 700 },
            data: weightValues
        }],

        xaxis: {
            categories: weightDates,
            title: {
                text: "Attendance Date",
                style: { fontSize: '18px', fontWeight: 700 }
            },
            labels: {
                style: { fontSize: '14px', fontWeight: 600 }
            }
        },

        yaxis: {
            title: {
                text: "User Weight (kg)",
                style: { fontSize: '18px', fontWeight: 700 }
            },
            labels: {
                style: { fontSize: '14px', fontWeight: 600 }
            },
            min: Math.min(...weightValues) - 2,
            max: Math.max(...weightValues) + 2
        },

        stroke: {
            curve: 'smooth',
            width: 3
        },

        markers: {
            size: 5
        },

        colors: ['#1b55e2'],
        dataLabels: { enabled: false },

        tooltip: {
            theme: 'dark',
            y: { formatter: (val) => val + " kg" }
        }
    };

    var chart = new ApexCharts(document.querySelector("#weightGraph"), options);
    chart.render();
</script>
@endpush
