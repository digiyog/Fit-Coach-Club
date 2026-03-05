@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Create Franchise Membership Plan | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<style>
    .dropify-wrapper
    {
        width:100% !important;
        margin-bottom: unset !important;
        height: 215px !important;
    }
</style>
@endpush

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">

                <!-- Validation error -->
                @component('admin-panel.validation.errors') @endcomponent
                <!-- / Validation error -->

                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-8 col-8">
                                <h4> Create Franchise Membership Plan </h4>
                            </div>
                        </div>

                        <div class="form pb-2">
                            {!! Form::open(['class' => 'franchise-membership-plan-form', 'method' => 'post', 'url' => route('adminPanel.franchise-membership-plans.store'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Franchise</label>
                                            <select name="franchise_id" id="franchise_id" class="form-control filter-field select-picker">
                                                <option value="">Select Franchise</option>
                                                @foreach($franchises as $key => $franchise)
                                                    <option value="{{ $franchise['id'] }}">{{ $franchise['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 pl-0">
                                        <div class="form-group">
                                            <label>Select Membership Plan</label>
                                            <select name="membership_plan_id" id="membership_plan_id" class="form-control filter-field select-picker">
                                                <option value="">Select Membership Plan</option>
                                                @foreach($membershipPlans as $key => $membershipPlan)
                                                    <option value="{{ $membershipPlan['id'] }}">{{ $membershipPlan['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Payment Status</label>
                                            <select name="payment_status" id="payment_status" class="form-control filter-field select-picker">
                                                <option value="">Select Payment Status</option>
                                                <option value="1">Pending</option>
                                                <option value="2">Completed</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="remark">Remark</label>
                                            {!! Form::textarea('remark', '', ['class' => 'form-control textarea-height editor-textarea', 'id' => 'remark', 'placeholder' => 'Remark', 'rows' => 5 , "cols" => 40 ]) !!}
                                        </div>
                                    </div>
                                </div>
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.save'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.save')] )}}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    var maxImageSize = {{config('constants.max_image_size')}};
</script>
<script src="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/franchise-membership-plans/franchise-membership-plans.js') }}"></script>
@endpush
