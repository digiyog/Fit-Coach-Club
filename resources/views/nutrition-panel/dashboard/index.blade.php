@extends('nutrition-panel.layouts.main-layout')

@section('page-title', ' '.__('language.dashboard_page_title').' | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/plugins/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin-assets/css/components/tabs-accordian/custom-tabs.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin-assets/js/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin-assets/plugins/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/widgets/modules-widgets.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/forms/switches.css') }}">

<style>
    @media screen and (max-width:1360px){
        .widget-card-four .w-info h6{
            font-size: 18px !important;
        }
    }
    @media screen and (max-width:1024px){
        .calender-text{
            font-size: 0.7rem !important;
        }
    }

    #reportrange{
        color: #3b3f5c !important;
    }

    #reportrange i.fa-caret-down{
        margin-top: 3px;
    }
    .widget-card-four .w-info h6{
        font-size: 16px;
    }
    .widget-card-four {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 110px;
    }

    @media print {
      body * { visibility: hidden; }
      #print-area, #print-area * { visibility: visible; }
      #print-area { position: absolute; left: 0; top: 0; width: 100%; }
    }

    .chart-heading-wrapper {
        display: flex;
        gap: 20px;
        align-items: center;
        margin-bottom: 10px;
        font-family: 'Nunito', sans-serif;
        font-size: 14px;
        font-weight: 600;
    }

    .chart-label {
        display: flex;
        align-items: center;
        color: #0e1726;
    }

    .chart-label .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }

    .chart-label .blue {
        background: #1b55e2;
    }

    .chart-label .red {
        background: #e7515a;
    }

    .chart-label .green {
        background: #00ab55;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">

    <div class="layout-top-spacing">
        <div class="row mb-2">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-md-3">
                        <h5 class="page-title py-20">Welcome! <b style="color : #3246d3;"> {{Auth::user()->name}} </b></h5>
                    </div>
                    <div class="col-md-9 text-end">
                        @php
                            use Carbon\Carbon;

                            $endDate = Carbon::parse($authUser['end_date']);
                            $today   = Carbon::today();
                        @endphp

                        <h5 class="page-title py-20">
                            @if($endDate->greaterThan($today))
                                Plan Valid Upto 
                                <b style="color:#3246d3;">
                                    {{ $endDate->format('d F Y') }}
                                </b>
                                <i class="feather feather-eye ms-2" style="color:#28a745;"></i>
                            @else
                                <b style="color:#dc3545;">
                                    Your Plan Expired on {{ $endDate->format('d F Y') }},
                                    Contact Super Admin for Renew the Plan
                                </b>
                            @endif
                        </h5>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3 ps-3 pe-3 align-items-stretch">
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12 p-0 layout-spacing text-center">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100">
                                    <h4 class="text-dark font-weight-bold s-counter1">{{ $totalUsers ?? 0 }}</h4>
                                    <h6 class="value">Total Users</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12 p-0 ps-3 layout-spacing text-center">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100">
                                    <h4 class="text-dark font-weight-bold s-counter2">{{ $thisMonthShake ?? 0 }}</h4>
                                    <h6 class="value">This Month Shake Count</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12 p-0 ps-3 layout-spacing text-center">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100">
                                    <h4 class="text-dark font-weight-bold s-counter3">{{ $offlineUsers ?? 0 }}</h4>
                                    <h6 class="value">Offline Users</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12 p-0 ps-3 layout-spacing text-center">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100">
                                    <h4 class="text-dark font-weight-bold s-counter4">{{ $onlineUsers ?? 0 }}</h4>
                                    <h6 class="value">Online Users</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12 p-0 ps-3 layout-spacing text-center">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100">
                                    <h4 class="text-dark font-weight-bold s-counter5">{{ $coachCount ?? 0 }}</h4>
                                    <h6 class="value">Coach Count</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12 p-0 ps-3 layout-spacing text-center">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100">
                                    <h4 class="text-dark font-weight-bold s-counter6">{{ $thisMonthUsers ?? 0 }}</h4>
                                    <h6 class="value">New Membership this Month</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row mt-3 ps-3 pe-3 align-items-stretch">
            <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 ps-0 pe-0 col-12 layout-spacing">
                <div class="widget widget-card-two">
                    <div class="widget-content">

                        <div class="media text-center">
                            <div class="media-body">
                                <h4 class="mb-0" style="color : #3246d3;">You can scan your Attendance by following QR code.</h4>
                            </div>
                        </div>

                        <div class="card-bottom-section">
                            <div id="print-area">
                                <div id="qr-container" class="flex justify-center"></div>
                            </div>

                            <div class="flex justify-center">
                                <div>
                                    <a href="javascript:void(0);" onclick="window.print()" class="btn me-2">Print QR Code</a>
                                </div>
                                <div>
                                    <a href="javascript:void(0);" id="downloadBtn" class="btn ms-2">Download PNG</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="col-xl-4 col-lg-12 col-md-6 col-sm-12 col-12 ps-0 layout-spacing">
                <div class="widget widget-table-one bg-white p-3">
                    <div class="widget-heading">
                        <h5 class="" style="color : #3246d3;">Today Revenue</h5>
                    </div>

                    <div class="widget-content">
                        <div class="transactions-list">
                            <div class="t-item">
                                <div class="t-company-name">
                                    <div class="t-icon">
                                        <div class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                        </div>
                                    </div>
                                    <div class="t-name">
                                        <h4>Today Cash Collection</h4>
                                    </div>
                                </div>
                                <div class="t-rate rate-inc">
                                    <p><span>₹0</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="transactions-list">
                            <div class="t-item">
                                <div class="t-company-name">
                                    <div class="t-icon">
                                        <div class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                        </div>
                                    </div>
                                    <div class="t-name">
                                        <h4>Today Online Collection</h4>
                                    </div>
                                </div>
                                <div class="t-rate rate-inc">
                                    <p><span>₹0</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="transactions-list">
                            <div class="t-item">
                                <div class="t-company-name">
                                    <div class="t-icon">
                                        <div class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                        </div>
                                    </div>
                                    <div class="t-name">
                                        <h4>Today Total Collection</h4>
                                    </div>
                                </div>
                                <div class="t-rate rate-inc">
                                    <p><span>₹0</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="transactions-list">
                            <div class="t-item">
                                <div class="t-company-name">
                                    <div class="t-icon">
                                        <div class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                        </div>
                                    </div>
                                    <div class="t-name">
                                        <h4>Product/Service Sale Today</h4>
                                    </div>
                                </div>
                                <div class="t-rate rate-inc">
                                    <p><span>₹0</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="widget-card-two p-0">
                    <div class="card-bottom-section">
                        <a href="javascript:void(0);" class="btn mx-0">Track Your Product And Payment</a>
                    </div>
                </div>
            </div> -->

            <div class="col-xl-4 col-lg-12 col-md-6 col-sm-12 layout-spacing p-0 ps-3">
                <div class="widget-content widget-content-area br-6">
                    <div class="widget-heading pt-2 d-flex align-items-center">
                        <div class="me-3">
                            <h5 class="m-0" style="color : #3246d3;">Today Birthday </h5>
                        </div>
                        <div style="width: 30px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M182.4 53.5L157.8 95.6C154 102.1 152 109.6 152 117.2L152 120C152 142.1 169.9 160 192 160C214.1 160 232 142.1 232 120L232 117.2C232 109.6 230 102.2 226.2 95.6L201.6 53.5C199.6 50.1 195.9 48 192 48C188.1 48 184.4 50.1 182.4 53.5zM310.4 53.5L285.8 95.6C282 102.1 280 109.6 280 117.2L280 120C280 142.1 297.9 160 320 160C342.1 160 360 142.1 360 120L360 117.2C360 109.6 358 102.2 354.2 95.6L329.6 53.5C327.6 50.1 323.9 48 320 48C316.1 48 312.4 50.1 310.4 53.5zM413.8 95.6C410 102.1 408 109.6 408 117.2L408 120C408 142.1 425.9 160 448 160C470.1 160 488 142.1 488 120L488 117.2C488 109.6 486 102.2 482.2 95.6L457.6 53.5C455.6 50.1 451.9 48 448 48C444.1 48 440.4 50.1 438.4 53.5L413.8 95.6zM224 224C224 206.3 209.7 192 192 192C174.3 192 160 206.3 160 224L160 277.5C122.7 290.6 96 326.2 96 368L96 388.8C116.9 390.1 137.6 396.1 156.3 406.8L163.4 410.9C189.7 425.9 222.3 424.3 247 406.7C290.7 375.5 349.3 375.5 393 406.7C417.6 424.3 450.3 426 476.6 410.9L483.7 406.8C502.4 396.1 523 390.1 544 388.8L544 368C544 326.2 517.3 290.6 480 277.5L480 224C480 206.3 465.7 192 448 192C430.3 192 416 206.3 416 224L416 272L352 272L352 224C352 206.3 337.7 192 320 192C302.3 192 288 206.3 288 224L288 272L224 272L224 224zM544 437C531.3 438.2 518.9 442 507.5 448.5L500.4 452.6C457.8 476.9 405 474.3 365.1 445.8C338.1 426.5 301.9 426.5 274.9 445.8C235 474.3 182.2 477 139.6 452.6L132.5 448.5C121.1 442 108.7 438.1 96 437L96 512C96 547.3 124.7 576 160 576L480 576C515.3 576 544 547.3 544 512L544 437z"/></svg>
                        </div>
                    </div>
                    <div class="table-responsive mb-4 mt-4" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Birthday Year</th>
                                    <th>User Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($thisMonthBirthdayUsers[0])
                                    @foreach($thisMonthBirthdayUsers as $thisMonthBirthdayUser)
                                        <tr>
                                            <td>{{ ucfirst($thisMonthBirthdayUser->name) }}</td>
                                            <td>{{ date('Y', strtotime($thisMonthBirthdayUser->date_of_birth)) }}</td>

                                            @if($thisMonthBirthdayUser->user_type == 'Regular User')
                                                <td>{{ $thisMonthBirthdayUser->user_type }} ({{ $thisMonthBirthdayUser->user_state }})</td>
                                            @else
                                                <td>{{ $thisMonthBirthdayUser->user_type }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="font-weight-bold text-center">No Record Found !!</td>
                                    </tr>
                                @endif
                        </table>
                    </div>
                </div>

                <!-- <div class="widget-card-two p-0">
                    <div class="card-bottom-section">
                        <a href="javascript:void(0);" class="btn mx-0">View All December Birthday</a>
                    </div>
                </div> -->
            </div>
        </div>

        <div class="row mt-0 mb-4 ps-3 pe-3 align-items-stretch">
            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 layout-spacing ps-0">
                <div class="widget-content widget-content-area br-6">
                    <div class="widget-heading pt-2 d-flex align-items-center justify-center">
                        <h5 class="m-0  font-weight-bold" style="color : #3246d3;">More Than One Attendance on {{ date('Y-m-d', strtotime($today)) }}</h5>
                    </div>
                    <div class="table-responsive mb-4 mt-4" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Attendance Count</th>
                                    <th>Coach</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($today2Attendences[0])
                                    @foreach($today2Attendences as $today2Attendence)
                                        @php
                                            $type = \App\Models\AttendanceLogs::where('user_id', $today2Attendence->user_id)
                                                ->where('date', $today2Attendence->date)
                                                ->first();
                                        @endphp

                                        <tr>
                                            <td>{{ ucfirst($today2Attendence->name) }}</td>
                                            <td>{{ $today2Attendence->date }}</td>
                                            <td>{{ $today2Attendence->total_attendance }}</td>
                                            <td>{{ $today2Attendence->coach_name }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="font-weight-bold text-center">No Record Found !!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 layout-spacing p-0">
                <div class="widget-content widget-content-area br-6">
                    <div class="widget-heading pt-2 d-flex align-items-center justify-center">
                        <h5 class="m-0 font-weight-bold" style="color : #3246d3;">Updation on {{ date('Y-m-d', strtotime($today)) }}</h5>
                    </div>
                    <div class="table-responsive mb-4 mt-4" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <!-- <th>Delete / Add Date</th> -->
                                    <th>Type</th>
                                    <th>Attendance Count</th>
                                    <th>Date of Attendance</th>
                                    <th>Coach Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($todayAttendences[0])
                                    @foreach($todayAttendences as $todayAttendence)
                                        
                                        <tr>
                                            <td>{{ ucfirst($todayAttendence->name) }}</td>
                                            <!-- <td>{{ $todayAttendence->date }}</td> -->
                                            <td>{{ $todayAttendence->remark }}</td>
                                            <td>1</td>
                                            <td>{{ $todayAttendence->date }}</td>
                                            <td>{{ $todayAttendence->coach_name }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="font-weight-bold text-center">No Record Found !!</td>
                                    </tr>
                                @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-0 ps-3 pe-3 align-items-stretch">
            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 layout-spacing ps-0">
                <div class="widget-content widget-content-area br-6">
                    <div class="widget-heading pt-2 d-flex align-items-center justify-center">
                        <h5 class="m-0 font-weight-bold" style="color : #3246d3;">Top 20 Attendance in {{ date('F') }}</h5>
                    </div>
                    <div class="table-responsive mb-4 mt-4" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Present Out of {{ $totalDaysInMonth }} Days</th>
                                    <th>Percentage</th>
                                    <th>Coach Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($top20Attendance[0])
                                    @foreach($top20Attendance as $top20Attend)
                                        <tr>
                                            <td>{{ ucfirst($top20Attend->name) }}</td>
                                            <td>{{ $top20Attend->total_attendance }}</td>
                                            <td>{{ $top20Attend->attendance_percentage }}</td>
                                            <td>{{ $top20Attend->coach_name }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="font-weight-bold text-center">No Record Found !!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 layout-spacing p-0">
                <div class="widget-content widget-content-area br-6">
                    <div class="widget-heading pt-2 d-flex align-items-center justify-center">
                        <h5 class="m-0 font-weight-bold" style="color : #3246d3;">Least 20 Attendance in {{ date('F') }}</h5>
                    </div>
                    <div class="table-responsive mb-4 mt-4" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Present Out of {{ $totalDaysInMonth }} Days</th>
                                    <th>Percentage</th>
                                    <th>Coach Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($least20Attendance[0])
                                    @foreach($least20Attendance as $least20Attend)
                                        <tr>
                                            <td>{{ ucfirst($least20Attend->name) }}</td>
                                            <td>{{ $least20Attend->total_attendance }}</td>
                                            <td>{{ $least20Attend->attendance_percentage }}</td>
                                            <td>{{ $least20Attend->coach_name }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="font-weight-bold text-center">No Record Found !!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-0 layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 _layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="custom-datatable-filter _hide">
                            {!! Form::open(['class' => 'custom-datatable-filter-form']) !!}
                            <div class="row">
                                <div class="col-xl-3"></div>
                                <div class="col-md-5 text-center">
                                    <h6 class="text-primary font-weight-bold"> Shake Count Income & Expense and User Graph {{ date('Y') }} </h6>
                                </div>
                                <div class="col-xl-3"></div>
                            </div>
                            <div class="row mb-3 align-items-end">
                                <div class="col-xl-3"></div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    @php
                                        $years = [];
                                        $currentYear = date('Y');
                                        for ($i = 0; $i < 5; $i++) {
                                            $years[$currentYear - $i] = $currentYear - $i;
                                        }
                                    @endphp

                                    <div class="form-group mb-0">
                                        <label>Year</label>
                                        {!! Form::select(
                                            'year_filter',
                                            $years,
                                            null,
                                            ['class' => 'form-control filter-field select-picker', 'id' => 'year_filter']
                                        ) !!}
                                    </div>
                                </div>
                            
                                <div class="col-md-2 ps-0">
                                    {{ Form::button( __('language.filter_apply'), ['class' => 'btn btn-primary apply-filter', 'type' => 'submit', 'title' => __('language.filter_apply'), 'name' => 'filter'] )}}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row sales mt-4 ps-3 pe-3 align-items-stretch">
            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing ps-0">
                <div class="widget widget-chart-one">
                    <div class="widget-heading ps-3">
                        <h5 class="" style="color : #3246d3;">Bar Graph Representation of Shake Count {{ date('Y') }}</h5>
                    </div>

                    <div class="widget-content" style="height:68px;">
                        <div class="tabs tab-content">
                            <div id="content_1" class="tabcontent"> 
                                <div class="chart-heading-wrapper">
                                    <!-- <span class="chart-label">
                                        <i class="dot blue"></i> Demo
                                    </span>
                                    <span class="chart-label">
                                        <i class="dot red"></i> 3 Days
                                    </span>
                                    <span class="chart-label">
                                        <i class="dot green"></i> Regular Users
                                    </span> -->
                                </div>
                                <!-- <div id="revenueMonthly"></div> -->
                            </div>
                        </div>
                    </div>

                    <div class="widget-content">
                        <div id="shakeCountGraph"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing p-0">
                <div class="widget widget-chart-one">
                    <div class="widget-heading">
                        <h5 class="" style="color : #3246d3;">Lines Graph Representation of Demo, 3 Days & Regular User Count {{ date('Y') }}</h5>
                    </div>

                    <div class="widget-content">
                        <div class="tabs tab-content">
                            <div id="content_1" class="tabcontent"> 
                                <div class="chart-heading-wrapper">
                                    <span class="chart-label">
                                        <i class="dot blue"></i> Demo
                                    </span>
                                    <span class="chart-label">
                                        <i class="dot red"></i> 3 Days
                                    </span>
                                    <span class="chart-label">
                                        <i class="dot green"></i> Regular Users
                                    </span>
                                </div>
                                <div id="revenueMonthly"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row sales mt-4 ps-3 pe-3 align-items-stretch">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing p-0">
                <div class="widget widget-chart-one">
                    <div class="widget-heading ps-3">
                        <h5 class="" style="color : #3246d3;">Income and Expenses Graph (Purchase & Deposit Graph {{ date('Y') }})</h5>
                    </div>

                    <div class="widget-content">
                        <div id="incomeExpenseGraph"></div>
                    </div>

                    <p style="background-color: #1b55e2; color:white;">This Color Represent The Purchase(You are Giving Product)</p>
                    <p style="background-color: #e7515a; color:white;">This Color Represent The Deposit(Your Revenue)</p>
                </div>
            </div>
        </div>

        <div class="row mt-0 ps-3 pe-3 align-items-stretch my-4">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 layout-spacing p-0 pe-2">
                <div class="widget-content widget-content-area br-6">
                    <div class="widget-heading pt-2 d-flex align-items-center justify-center">
                        <h5 class="m-0  font-weight-bold" style="color : #3246d3;">Pending Payment of Users</h5>
                    </div>
                    <div class="table-responsive mb-4 mt-4" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Pending Payment</th>
                                    <!-- <th>Coach Name</th>
                                    <th>Mobile Number</th>
                                    <th>Type</th>
                                    <th>Status</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @if($paymentPendings[0])
                                    @foreach($paymentPendings as $key => $paymentPending)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ ucfirst($paymentPending->name) }}</td>
                                            <td>{{ $paymentPending->due_amount }}</td>
                                            <!-- <td>{{ $paymentPending->coach_name }}</td>
                                            <td>{{ $paymentPending->mobile_number }}</td>

                                            @if($paymentPending->user_type == 'Regular User')
                                                <td>{{ $paymentPending->user_type }} ({{ $paymentPending->user_state }})</td>
                                            @else
                                                <td>{{ $paymentPending->user_type }}</td>
                                            @endif

                                            <td class="">
                                                <label class="switch s-success p-0 m-0 mt-2">
                                                    <input type="checkbox" class="status-toggle" data-change-status-url="{{ route('nutritionPanel.users.changeStatus') }}" value="{{ $paymentPending->id }}" @if($paymentPending->status == 1) checked @endif>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td> -->
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="font-weight-bold text-center">No Record Found !!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 layout-spacing p-0 ps-2">
                <div class="widget-content widget-content-area br-6">
                    <div class="widget-heading pt-2 d-flex align-items-center justify-center">
                        <h5 class="m-0  font-weight-bold" style="color : #3246d3;">Customer whose Membership Expire Soon</h5>
                    </div>
                    <div class="table-responsive mb-4 mt-4" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Pending Days</th>
                                    <th>Status</th>
                                    <!-- <th>Coach Name</th>
                                    <th>Mobile Number</th>
                                    <th>Type</th>
                                     -->
                                </tr>
                            </thead>
                            <tbody>
                                @if($membershipExpires[0])
                                    @foreach($membershipExpires as $key => $membershipExpire)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ ucfirst($membershipExpire->name) }}</td>
                                            <td>{{ $membershipExpire->days }}</td>
                                            <td class="">
                                                <label class="switch s-success p-0 m-0 mt-2">
                                                    <input type="checkbox" class="status-toggle" data-change-status-url="{{ route('nutritionPanel.users.changeStatus') }}" value="{{ $membershipExpire->id }}" @if($membershipExpire->status == 1) checked @endif>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            
                                            <!-- <td>{{ $membershipExpire->coach_name }}</td>
                                            <td>{{ $membershipExpire->mobile_number }}</td>

                                            @if($membershipExpire->user_type == 'Regular User')
                                                <td>{{ $membershipExpire->user_type }} ({{ $membershipExpire->user_state }})</td>
                                            @else
                                                <td>{{ $membershipExpire->user_type }}</td>
                                            @endif

                                             -->
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="font-weight-bold text-center">No Record Found !!</td>
                                    </tr>
                                @endif
                            </tbody>
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
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<!-- <script src="{{ asset('admin-assets/js/dashboard/view.js') }}"></script> -->
<script src="{{ asset('admin-assets/plugins/apex/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/counter/jquery.countTo.js') }}"></script>
<script src="{{ asset('admin-assets/js/components/custom-counter.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<!-- <script src="{{ asset('admin-assets/js/widgets/modules-widgets.js') }}"></script> -->

<script>
    $(document).on('change', '.status-toggle', function () {

        let ids = [];

        ids.push($(this).val());

        $.ajax({
            url: $(this).data("change-status-url"),
            type: "POST",
            data: {
                ids: ids,      // 🔥 array
            },
            success: function (response) {
                App.showNotification(response);
            },
            error: function () {
                App.showNotification(response);
            }
        });
    });
</script>

<script>
    // Generate QR
    const qrValue = "{{ $qr_code }}";

    // Check empty or invalid value
    if(!qrValue || qrValue.trim() === ""){
        console.error("Invalid QR value");
    }

    // Generate QR
    new QRCode(document.getElementById("qr-container"), {
        text: qrValue,
        // width: 100,
        // height: 100%,
        correctLevel: QRCode.CorrectLevel.H
    });

    // Download QR as PNG
    document.getElementById('downloadBtn').onclick = function(){
        const canvas = document.querySelector('#qr-container canvas');
        const link = document.createElement('a');
        link.download = 'qr-' + qrValue + '.png';
        link.href = canvas.toDataURL();
        link.click();
    }

    var shakeCount = @json($totalShakeChartData);

    // Shake Count - Single Bar
    var d_1options1 = {
      chart: {
          height: 350,
          type: 'bar',
          toolbar: {
            show: false,
          },
          dropShadow: {
              enabled: true,
              top: 1,
              left: 1,
              blur: 2,
              color: '#acb0c3',
              opacity: 0.7,
          }
      },
      colors: ['#5c1ac3'], // single color
      plotOptions: {
          bar: {
              horizontal: false,
              columnWidth: '55%',
              endingShape: 'rounded'
          },
      },
      dataLabels: {
          enabled: false
      },
      legend: {
            show: false // single bar me legend hide
      },
      stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
      },
      series: [{
          name: 'Shake Count',
          data: shakeCount
      }],
      xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      },
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'light',
          type: 'vertical',
          shadeIntensity: 0.3,
          inverseColors: false,
          opacityFrom: 1,
          opacityTo: 0.8,
          stops: [0, 100]
        }
      },
      tooltip: {
        enabled: true,
         marker: {
            show: false   // ❌ point/dot hide
        },
          y: {
              formatter: function (val) {
                  return val;
              }
          }
      }
    };

    var d_1C_3 = new ApexCharts(
        document.querySelector("#shakeCountGraph"),
        d_1options1
    );
    d_1C_3.render();


    var userDemoChartData = @json($userDemoChartData);
    var userTrailChartData = @json($userTrailChartData);
    var userRegualrChartData = @json($userRegualrChartData);

    // Users
    var options1 = {
      chart: {
        fontFamily: 'Nunito, sans-serif',
        height: 365,
        type: 'area',
        zoom: {
          enabled: false
        },
        dropShadow: {
          enabled: true,
          opacity: 0.3,
          blur: 5,
          left: -7,
          top: 22
        },
        toolbar: {
          show: false
        }
      },

      // 🎨 3 Different Colors
      colors: ['#1b55e2', '#e7515a', '#00ab55'],

      dataLabels: {
        enabled: false
      },

      stroke: {
        show: true,
        curve: 'smooth',
        width: 2
      },

      // 📊 3 SERIES
      series: [
        {
          name: 'Demo',
          data: userDemoChartData
        },
        {
          name: '3 Days',
          data: userTrailChartData
        },
        {
          name: 'Regular Users',
          data: userRegualrChartData
        }
      ],

      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],

      xaxis: {
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            fontSize: '12px',
            fontFamily: 'Nunito, sans-serif'
          }
        }
      },

      yaxis: {
        labels: {
          formatter: function(value) {
            return value;
          },
          style: {
            fontSize: '12px',
            fontFamily: 'Nunito, sans-serif'
          }
        }
      },

      grid: {
        borderColor: '#e0e6ed',
        strokeDashArray: 5
      },

      legend: {
        position: 'top',
        horizontalAlign: 'right',
        offsetY: -20,
        fontSize: '14px'
      },

      tooltip: {
        theme: 'dark',
        y: {
          formatter: function(val) {
            return '₹ ' + val;
          }
        }
      },

      fill: {
        type: "gradient",
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.35,
          opacityTo: 0.1,
          stops: [0, 90, 100]
        }
      }
    };

    var chart1 = new ApexCharts(
      document.querySelector("#revenueMonthly"),
      options1
    );

    chart1.render();

    var transactionAddUserChartData = @json($transactionAddUserChartData);
    var transactionOrderPlacedChartData = @json($transactionOrderPlacedChartData);

    // Income & Expenses
    var options = {
      chart: {
          height: 350,
          type: 'bar',
          toolbar: {
            show: false
          },
          dropShadow: {
              enabled: false,
              // top: 1,
              // left: 1,
              // blur: 2,
              // color: '#acb0c3',
              // opacity: 1
          }
      },

      // 🎨 Income & Expense colors
      colors: ['#1b55e2', '#e7515a'],

      plotOptions: {
          bar: {
              horizontal: false,
              columnWidth: '45%',
              endingShape: 'rounded'
          }
      },

      dataLabels: {
          enabled: false,
          position: 'top',
          style: {
              fontSize: '12px',
              fontWeight: '600',
              colors: ['#333']
          },
          formatter: function (val) {
              return '₹ ' + val;
          }
      },

      legend: {
          position: 'top',
          horizontalAlign: 'right'
      },

      stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
      },

      // 📊 TWO SERIES
      series: [
          {
              name: 'Income',
              data: transactionOrderPlacedChartData
          },
          {
              name: 'Revenue',
              data: transactionAddUserChartData
          }
      ],

      xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
      },

      fill: {
        type: 'gradient',
        gradient: {
          shade: 'light',
          type: 'vertical',
          shadeIntensity: 0.3,
          opacityFrom: 1,
          opacityTo: 0.8,
          stops: [0, 100]
        }
      },

      tooltip: {
          y: {
              formatter: function (val) {
                  return '₹ ' + val;
              }
          }
      }
    };

    var chart = new ApexCharts(
        document.querySelector("#incomeExpenseGraph"),
        options
    );
    chart.render();
</script>
@endpush