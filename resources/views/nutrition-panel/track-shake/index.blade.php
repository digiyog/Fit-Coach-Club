@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Track Shake | '.__('language.page_main_title').'')

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

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-4">
                                <h4> Track Shake - {{ ucfirst($user['name']) }} </h4>
                            </div>

                            <div class="col-xl-8 col-lg-8 col-md-8 col-8 text-right">
                                <h6>Information: | <span class="text-success">+</span> Add Shakes | <span class="text-danger">-</span> Subtract Shakes | <span class="text-success">Attendance Add</span> | <span class="text-danger">Attendance Delete</span></h6>
                            </div>
                        </div>
                    </div>

                    <div class="row align-strech mt-4 ml-1">
                        <div class="col-md-12 col-sm-12 col-xs-12 mb-2">
                            <a class="btn btn-primary mr-2" href="{{ route('nutritionPanel.users.viewWeights', ['id' => ev($user->id)]) }}">
                                <i class="fa fa-eye"></i> 
                                View Weight
                            </a>
                        
                            <a class="btn btn-primary mr-2" href="{{ route('nutritionPanel.users.viewAttendance', ['id' => ev($user->id)]) }}">
                                <i class="fa fa-eye"></i> 
                                View Attendance
                            </a>

                            <a class="btn btn-primary mr-2" href="{{ route('nutritionPanel.manual-attendances.manual-attendance', ['id' => ev($user->id)]) }}">
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

                    <div class="table-responsive data-table-container mb-4 mt-2">
                        <div class="table-responsive _mb-4">
                            <table id="dataTable" class="table table-hover" data-url="{{ route('nutritionPanel.track-shake.getTrackShake') }}">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Total Shake Count After Add/Delete Shake</th>
                                        <th>Shake Or Attendance Add/Delete</th>
                                        <th>Shake Or Attendance Information</th>
                                        <th>Shake Add/Delete From Web/App</th>
                                        <th>Remark</th>
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
<script src="{{ asset('admin-assets/js/flatpickr.js') }}"></script>
<script src="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/track-shake/view.js') }}"></script>

@endpush
