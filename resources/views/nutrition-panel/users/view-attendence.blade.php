@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'View Attendance | '.__('language.page_main_title').'')

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
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
        text-align: center;
    }

    .calendar-day-header {
        font-weight: bold;
        background: #f2f2f2;
        padding: 8px 0;
        border-radius: 8px;
    }

    .calendar-day {
        border-radius: 12px;
        padding: 10px;
        font-size: 14px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: transform 0.2s ease;
    }

    .calendar-day:hover {
        transform: scale(1.05);
    }

    .calendar-day.present {
        background-color: #c8f7c5; /* Light green */
        color: #2e7d32;
        border: 1px solid #81c784;
    }

    .calendar-day.blank {
        color: gray;
        border: 1px solid gray;
    }

    .calendar-day.absent {
        background-color: #ffcdd2; /* Light red */
        color: #c62828;
        border: 1px solid #ef5350;
    }

    .calendar-day.not-marked {
        background-color: #eeeeee;
        color: #757575;
        border: 1px solid #ccc;
    }

    .calendar-day.empty {
        background: transparent;
        border: none;
    }

    .day-number {
        font-weight: bold;
        font-size: 16px;
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

        <div class="row align-strech mt-4">
            <div class="col-md-12 col-sm-12 col-xs-12 mb-2">
                <a class="btn btn-primary mr-2" href="{{ route('nutritionPanel.users.viewWeights', ['id' => ev($user->id)]) }}">
                    <i class="fa fa-eye"></i> 
                    View Weight
                </a>
            
                <a class="btn btn-primary mr-2" href="{{ route('nutritionPanel.users.viewAttendence', ['id' => ev($user->id)]) }}">
                    <i class="fa fa-eye"></i> 
                    View Attendance
                </a>

                <a class="btn btn-primary mr-2" href="{{ route('nutritionPanel.manual-attendences.manual-attendence', ['id' => ev($user->id)]) }}">
                    <i class="fa fa-eye"></i> 
                    Manual Attendance
                </a>

                <a class="btn btn-primary mr-2" href="{{ route('nutritionPanel.track-shake.index', ['id' => ev($user->id)]) }}">
                    <i class="fa fa-eye"></i> 
                    Track Shake
                </a>

                <a class="btn btn-primary mr-2" href="{{ route('nutritionPanel.orders.index', ['id' => ev($user->id)]) }}">
                    <i class="fa fa-eye"></i> 
                    Purchase Products
                </a>
            </div>
        </div>

        <div class="row layout-top-spacing custom-datatable-filters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 _layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="custom-datatable-filter _hide">
                            {!! Form::open(['class' => 'custom-datatable-filter-form']) !!}
                                <div class="row align-items-end">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Year</label>
                                            @php
                                                $years = [];
                                                for ($y = date('Y'); $y >= 2000; $y--) {
                                                    $years[$y] = $y;
                                                }
                                            @endphp
                                            {!! Form::select('year', $years, $year, ['class' => 'form-control select-picker', 'id' => 'year']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb2">
                                        {{ Form::submit( __('language.filter_apply'), ['class' => 'btn btn-primary apply-filter', 'type' => 'button', 'title' => __('language.filter_apply'), 'name' => 'filter'] )}}
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @for ($m = 1; $m <= 12; $m++)
                @php
                    $monthName = date('F', mktime(0, 0, 0, $m, 10));
                    $firstDayOfWeek = Carbon\Carbon::createFromDate($year, $m, 1)->dayOfWeek;
                    $daysInMonth = Carbon\Carbon::createFromDate($year, $m, 1)->daysInMonth;
                @endphp

                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing pb-3">
                    <div class="card mt-4">
                        <div class="card-header text-center">
                            <h5>{{ $monthName }} {{ $year }} - Attendance Calendar</h5>
                        </div>

                        <div class="card-body">
                            <div class="calendar-grid">

                                {{--days header--}}
                                @foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                                    <div class="calendar-day-header">{{ $day }}</div>
                                @endforeach

                                {{-- empty slots --}}
                                @for ($i = 0; $i < $firstDayOfWeek; $i++)
                                    <div class="calendar-day empty"></div>
                                @endfor

                                @php
                                    $presentCount = 0;
                                @endphp

                                {{-- Days --}}
                                @for ($d = 1; $d <= $daysInMonth; $d++)
                                    @php
                                        $currentDate = \Carbon\Carbon::createFromDate($year, $m, $d)->format('Y-m-d');
                                        $attendance = $attendances->get($currentDate);
                                        $status = $attendance ? $attendance->type : null;

                                        if($status == 2){
                                            $presentCount++;
                                        }
                                    @endphp

                                    @if(strtotime($currentDate) >= strtotime($user->created_at) && strtotime(date('Y-m-d')) >= strtotime($currentDate))
                                        <div class="calendar-day
                                            @if ($status == 2) present
                                            @elseif ($status == 1) absent
                                            @else absent
                                            @endif">
                                            <span class="day-number">{{ $d }}</span>
                                        </div>
                                    @else
                                        <div class="calendar-day @if ($status == 2) present @else blank @endif">
                                            <span class="day-number">{{ $d }}</span>
                                        </div>
                                    @endif
                                @endfor

                            </div>
                        </div>
                        <div class="card-header text-center">
                            <h5>Present - {{ $presentCount }}</h5>
                        </div>
                    </div>
                </div>
            @endfor
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

@endpush
