@extends('nutrition-panel.layouts.main-layout')

    @section('page-title', 'View Orders | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
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
                                <div class="col-md-12">
                                    <h6 class="text-primary"> {{ __('language.filters') }} </h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Filter Date Range</label>
                                        {!! Form::text('filter_date_range', '', ['class' => 'form-control filter-field date-picker-input filter-date-range-picker', 'id' => 'filter_date_range', 'placeholder' => 'Date Range' ]) !!}
                                    </div>
                                </div>

                                <input type="hidden" name="user_id" id="user_id" value="{{ $user_id }}">

                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Order Status</label>
                                        {!! Form::select('status_filter', create_select_options(config('constants.order_status'), 'display', 'value', 'Order Status'), null,  ['class' => 'form-control filter-field select-picker', 'id' => 'status_filter' ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Payment Status</label>
                                        {!! Form::select('payment_status_filter', create_select_options(config('constants.payment_statuses'), 'display', 'value', 'Payment Status'), null,  ['class' => 'form-control filter-field select-picker', 'id' => 'payment_status_filter' ]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb2">
                                    {{ Form::button( __('language.filter_clear'), ['class' => 'btn btn-dark clear-filter', 'type' => 'button', 'title' => __('language.filter_clear'), 'name' => 'clear'] )}}
                                    {{ Form::button( __('language.filter_apply'), ['class' => 'btn btn-primary apply-filter', 'type' => 'button', 'title' => __('language.filter_apply'), 'name' => 'filter'] )}}
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
                            <div class="col-xl-8 col-lg-8 col-md-8 col-8 page-heading pl-3">
                                <h4> View Orders </h4>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive data-table-container mb2">
                        <div class="table-responsive _mb-4">
                            <table id="dataTable" class="table table-hover" data-url="{{ route('nutritionPanel.orders.getOrders', ['user_id' => $userId, 'user_type' => $userType]) }}">
                                <thead>
                                    <tr>
                                        <th>Order Info</th>
                                        <th>User Name</th>
                                        <th>Mobile Number</th>
                                        <th>Total Amount</th>
                                        <th>Discount</th>
                                        <th>Net Amount</th>
                                        <th>Payment Status</th>
                                        <th>Order Status</th>
                                        <th class="text-right">{{ __('language.action') }}</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th id="total_amount_footer"></th>
                                        <th id="discount_footer"></th>
                                        <th id="net_amount_footer"></th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
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
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/orders/view.js') }}"></script>
@endpush
