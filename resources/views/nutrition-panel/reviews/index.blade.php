@extends('nutrition-panel.layouts.main-layout')

@section('page-title', ' Reviews | '.__('language.page_main_title').'')

@push('styles')
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
                                <div class="col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label> Name </label>
                                        {!! Form::select('user_id', create_select_options($users, 'name', 'id', 'Select Name'), null,  ['class' => 'form-control filter-field select-picker', 'id' => 'name', ]) !!}
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
                            <div class="col-xl-8 col-lg-8 col-md-8 col-8 page-heading">
                                <h4> Reviews</h4>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive data-table-container mb-4 mt-4">
                        <div class="table-responsive _mb-4">
                            <table id="dataTable" class="table table-hover" data-url="{{ route('nutritionPanel.reviews.getReviews') }}" data-destroy-url="{{ route('nutritionPanel.reviews.destroy') }}">
                                <thead>
                                    <tr>
                                        <th class="checkbox-column"> # </th>
                                        <th> Name </th>
                                        <th> Rating </th>
                                        <th width="350"> Message </th>
                                        <th> Created At </th>
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
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/reviews/view.js') }}"></script>
@endpush
