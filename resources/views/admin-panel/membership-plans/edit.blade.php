@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Edit Membership Plan | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">

                <!-- Validation error -->
                @component('admin-panel.validation.errors') @endcomponent
                <!-- / Validation error -->

                <div class="widget-content widget-content-area br-6">
                    <div class="admin-card-header">
                        <div>
                            <h4 class="admin-card-title">
                                <i data-feather="edit" class="text-primary" style="width: 22px; height: 22px;"></i>
                                Edit Membership Plan
                            </h4>
                            <p class="admin-card-subtitle">Update pricing, validity, or name of this membership plan.</p>
                        </div>
                        <div>
                            <a href="{{ route('adminPanel.membership-plans.index') }}" class="btn-back-link">
                                <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                                Back to Membership Plans
                            </a>
                        </div>
                    </div>

                    <div class="form pb-2">
                        {!! Form::open(['class' => 'membership-plan-form', 'method' => 'post', 'url' => route('adminPanel.membership-plans.update', ['id' => ev($membershipPlan->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Plan Name <span class="text-danger">*</span></label>
                                        {!! Form::text('name', $membershipPlan->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Enter plan name', ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Price (₹) <span class="text-danger">*</span></label>
                                        {!! Form::text('price', $membershipPlan->price, ['class' => 'form-control numeric', 'id' => 'price', 'placeholder' => 'Enter price', ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="days">Duration (Days) <span class="text-danger">*</span></label>
                                        {!! Form::text('days', $membershipPlan->days, ['class' => 'form-control numeric', 'id' => 'days', 'placeholder' => 'Enter duration in days', ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="order">Display Order <span class="text-danger">*</span></label>
                                        {!! Form::text('order', $membershipPlan->order, ['class' => 'form-control numeric', 'id' => 'order', 'placeholder' => 'Enter display order', ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="admin-form-actions">
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.update')] )}}
                                <a href="{{ route('adminPanel.membership-plans.index') }}" class="btn-back-link">Cancel</a>
                            </div>
                        {!! Form::close() !!}
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
<script src="{{ asset('admin-assets/js/flatpickr.js') }}"></script>
<script src="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/membership-plans/membership-plans.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush