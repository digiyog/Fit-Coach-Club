@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Company Profile | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<style>
    .dropify-wrapper {
        width: 100% !important;
        height: 220px !important;
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
                                <i data-feather="briefcase" class="text-primary" style="width: 22px; height: 22px;"></i>
                                Company Profile
                            </h4>
                            <p class="admin-card-subtitle">Manage organization branding, name, contact email, and support phone.</p>
                        </div>
                    </div>

                    <div class="form pb-2">
                        {!! Form::open(['class' => 'company-profile-form', 'method' => 'post', 'url' => route('adminPanel.company-profile.update'), 'files' => true, 'enctype' => 'multipart/form-data' ]) !!}
                            <div class="row">
                                <div class="col-lg-3 col-md-4 mb-4">
                                    <div class="form-group">
                                        <div class="custom-dropify">
                                            <label class="form-control-label" for="header_company_logo">@lang('language.header_logo')</label>
                                            {!! Form::file('header_logo_image', ['class' => 'image-preview', 'id' => 'header_logo', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', 'data-default-file' => (get_image_url(config('constants.company_profile.image_path'), $companyProfile->header_logo_image) ?? '') ]) !!}
                                            {!! Form::hidden('header_image', old('header_image', ($companyProfile->header_logo_image ?? null)) ,['class' => 'form-control','id' => 'header_image']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-9 col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="company_name">Company Name <span class="text-danger">*</span></label>
                                                {!! Form::text('name', old('name', ($companyProfile->name ?? null)), ['class' => 'form-control', 'id' => 'company_name', 'autocomplete' => 'off', 'placeholder' => 'Enter company name', ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="company_email">Official Email <span class="text-danger">*</span></label>
                                                {!! Form::text('email', old('email', ($companyProfile->email ?? null)), ['class' => 'form-control', 'id' => 'company_email', 'autocomplete' => 'off', 'placeholder' => 'contact@company.com', ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="company_phone">Support Phone No. <span class="text-danger">*</span></label>
                                                {!! Form::text('phone_no', old('phone_no', ($companyProfile->phone_no ?? null)), ['class' => 'form-control', 'id' => 'company_phone', 'autocomplete' => 'off', 'placeholder' => 'Enter phone number', ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="admin-form-actions">
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.update')] )}}
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
<script src="{{ asset('admin-assets/js/company-profile/company-profile.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
