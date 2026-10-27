@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Edit Franchise | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<style>
    .dropify-wrapper {
        width: 100% !important;
        margin-bottom: unset !important;
        height: 230px !important;
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
                    <div class="admin-card-header">
                        <div>
                            <h4 class="admin-card-title">
                                <i data-feather="edit" class="text-primary" style="width: 22px; height: 22px;"></i>
                                Edit Franchise
                            </h4>
                            <p class="admin-card-subtitle">Update franchise profile details and credentials.</p>
                        </div>
                        <div>
                            <a href="{{ route('adminPanel.franchises.index') }}" class="btn-back-link">
                                <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                                Back to Franchises
                            </a>
                        </div>
                    </div>

                    <div class="form pb-2">
                        {!! Form::open(['class' => 'franchise-form', 'method' => 'post', 'url' => route('adminPanel.franchises.update', ['id' => ev($franchise->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                            <div class="row">
                                @php
                                    $imagePath = (get_image_url(config('constants.users.image_path'), $franchise->profile_image) ?? '');
                                @endphp

                                <div class="col-lg-3 col-md-4 mb-4">
                                    <div class="form-group">
                                        <div class="custom-dropify">
                                            <label class="form-control-label" for="image">@lang('language.image')</label>
                                            {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => $imagePath, ]) !!}
                                            {!! Form::hidden('image_name', old('image_name', ($franchise->profile_image ?? null)) ,['class' => 'form-control','id' => 'image_name']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-9 col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                {!! Form::text('name', $franchise->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Enter franchise name', ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email <span class="text-danger">*</span></label>
                                                {!! Form::email('email', $franchise->email, ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Enter email address', 'autocomplete' => 'off', 'data-url' => route('adminPanel.franchises.checkEmail', ['id' => $franchise->id]) ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                                                {!! Form::text('mobile_number', $franchise->mobile_number, ['class' => 'form-control numeric', 'id' => 'mobile_number', 'placeholder' => 'Enter mobile number', 'data-url' => route('adminPanel.franchises.checkMobile', ['id' => $franchise->id]) ]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row pt-2">
                                        <div class="col-12">
                                            <h6 class="admin-section-title">
                                                <i data-feather="lock" style="width: 16px; height: 16px;"></i>
                                                Security Credentials
                                            </h6>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="new_pass">New Password <small class="text-muted">(Leave blank to keep current)</small></label>
                                                {!! Form::password('new_pass', ['class' => 'form-control', 'id' => 'new_pass', 'placeholder' => 'Enter new password', 'autocomplete' => 'new-password' ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="confirm_pass">Confirm Password </label>
                                                {!! Form::password('confirm_pass', ['class' => 'form-control', 'id' => 'confirm_pass', 'placeholder' => 'Confirm new password', ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="admin-form-actions">
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.update')] )}}
                                <a href="{{ route('adminPanel.franchises.index') }}" class="btn-back-link">Cancel</a>
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
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/franchises/franchises.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
