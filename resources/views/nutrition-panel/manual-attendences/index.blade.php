@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Manual Attendance | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<style type="text/css">
    .card-box{
        border: 1px solid #bfc9d4;
        border-radius: 15px;
    }
    .card-box p{
        padding: 5px 10px 0px 10px;
        font-size: 20px;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        background: lightgray;
        border-bottom: 1px solid gray;
        margin-bottom: 0px;
    }
    .card-box div{
        padding: 10px;
        font-size: 18px;
    }
</style>
<style type="text/css">
    .remark{
        resize: none;
        height: 100px;
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
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <input type="hidden" name="user_id" id="user_id" value="{{ $user['id'] }}">
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
        
        <div class="row layout-top-spacing align-item-stregth">
            <!-- Content -->
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="widget-content widget-content-area br-6">
                    <div class="animated-underline-content">
                        <!-- Tab Content start -->
                        <div class="tab-content" id="animateLineContent-4">
                            <div class="container-fluid mt2">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                                        <h4>Add Manual Attendance :- {{ ucfirst($user['name']) }} </h4>
                                    </div>
                                </div>
                            </div>
                            <!-- Tab Content Profile -->
                            <div class="tab-pane fade show active pt-0" id="animated-underline-profile" role="tabpanel" aria-labelledby="animated-underline-profile-tab">
                                <div class="form p-3">
                                    {!! Form::open(['class' => 'add-manual-attendance-form', 'method' => 'post', 'enctype' => 'multipart/form-data', 'url' => route('nutritionPanel.manual-attendences.addManualAttendance') ]) !!}

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label for="date"> Attendance Date <span class="text-danger">*</span></label>
                                                {!! Form::text('date', '', ['class' => 'form-control date-picker', 'id' => 'date', 'placeholder' => 'Select Attendance Date', 'autocomplete' => 'off' ]) !!}
                                            </div>

                                            <input type="hidden" name="user_id" value="{{ $user['id'] }}">

                                            <div class="col-md-12 mt-3">
                                                <label for="days"> How Many Attendance you want to mark <span class="text-danger">*</span></label>
                                                {!! Form::number('days', '', ['class' => 'form-control', 'id' => 'days', 'placeholder' => 'Enter Days', 'autocomplete' => 'off', 'min' => '1', 'max' => '10' ]) !!}
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <label for="remark"> Remark </label>
                                                {!! Form::textarea('remark', '', ['class' => 'form-control remark', 'id' => 'remark', 'placeholder' => 'Remark', ]) !!}
                                            </div>
                                        </div>

                                        {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_save'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_save') ] )}}
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <!-- Tab Content Profile -->
                        </div>
                        <!-- Tab Content End -->
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="widget-content widget-content-area br-6">
                    <div class="animated-underline-content">
                        <!-- Tab Content start -->
                        <div class="tab-content" id="animateLineContent-4">
                            <div class="container-fluid mt2">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                                        <h4>Add Today Weight :- {{ ucfirst($user['name']) }} </h4>
                                    </div>
                                </div>
                            </div>
                            <!-- Tab Content Profile -->
                            <div class="tab-pane fade show active pt-0" id="animated-underline-profile" role="tabpanel" aria-labelledby="animated-underline-profile-tab">
                                <div class="form p-3">
                                    {!! Form::open(['class' => 'add-today-weight-form', 'method' => 'post', 'enctype' => 'multipart/form-data', 'url' => route('nutritionPanel.manual-attendences.addTodayWeight') ]) !!}

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label for="date"> Attendance Date <span class="text-danger">*</span></label>
                                                {!! Form::text('date', '', ['class' => 'form-control date-picker', 'id' => 'date', 'placeholder' => 'Select Attendance Date', 'autocomplete' => 'off' ]) !!}
                                            </div>

                                            <input type="hidden" name="user_id" value="{{ $user['id'] }}">

                                            <div class="col-md-12 mt-3">
                                                <label for="weight"> Enter Today Weight(In Kg) <span class="text-danger">*</span></label>
                                                {!! Form::number('weight', '', ['class' => 'form-control', 'id' => 'weight', 'placeholder' => 'Enter Today Weight(In Kg)', 'autocomplete' => 'off' ]) !!}
                                            </div>
                                        </div>

                                        {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_save'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_save') ] )}}
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <!-- Tab Content Profile -->
                        </div>
                        <!-- Tab Content End -->
                    </div>
                </div>
            </div>
            <!-- Content -->
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-6">
                                <h4>{{ ucfirst($user['name']) }} Manual Attendance </h4>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-6 text-right">
                                <h4>Remaining Days : {{ $user['days'] }}</h4>
                                <!-- <h4>Total Shake and Validity : {{ $attendanceLogs['total_days'] }} - {{ $attendanceLogs['total_days'] - $user['days'] }} = {{ $user['days'] }}</h4> -->
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive data-table-container mb-4 mt-2">
                        <div class="table-responsive _mb-4">
                            <table id="dataTable" class="table table-hover" data-url="{{ route('nutritionPanel.manual-attendences.getManualAttendence') }}" data-track-shake-url="{{ route('nutritionPanel.track-shake.index', ['id' => ev($user->id)]) }}">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Date</th>
                                        <th>Weight</th>
                                        <th>Attendance Count</th>
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
<script src="{{ asset('admin-assets/js/flatpickr.js') }}"></script>
<script src="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/manual-attendence/view.js') }}"></script>

@endpush
