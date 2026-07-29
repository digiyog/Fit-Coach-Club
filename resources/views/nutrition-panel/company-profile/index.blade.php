@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Company Profile | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<style>
    .dropify-wrapper
    {
        width:100% !important;
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
                            <div class="col-xl-8 col-lg-8 col-md-8 col-8 page-heading">
                                <h4> Company Profile </h4>
                            </div>
                        </div>
                        <div class="form p-3">
                            {!! Form::open(['class' => 'company-profile-form', 'method' => 'post', 'url' => route('adminPanel.company-profile.update'),'files' => true ,  'enctype' => 'multipart/form-data' ]) !!}
                                <div class="form-row mb-4">
                                    <div class="col-md-4">
                                        <div class="custom-dropify">
                                            <label class="form-control-label" for="header_company_logo">@lang('language.header_logo')</label>
                                            {!! Form::file('header_logo_image', ['class' => 'image-preview', 'id' => 'header_logo', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', 'data-default-file' => (get_image_url(config('constants.company_profile.image_path'), $companyProfile->header_logo_image) ?? '') ]) !!}
                                            
                                            {!! Form::hidden('header_image', old('header_image',($companyProfile->header_logo_image ?? null)) ,['class' => 'form-control','id' => 'header_image']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="company_email">@lang('language.company_name')</label>
                                                {!! Form::text('name', old('name', ($companyProfile->name ?? null)), ['class' => 'form-control', 'id' => 'company_name', 'autocomplete' => 'off', 'placeholder' =>'Company Name', ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="company_email">@lang('language.company_email')</label>
                                                {!! Form::text('email', old('email', ($companyProfile->email ?? null)), ['class' => 'form-control', 'id' => 'company_email', 'autocomplete' => 'off', 'placeholder' =>'Email', ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="company_phone">@lang('language.company_phone')</label>
                                                {!! Form::text('phone_no', old('phone_no', ($companyProfile->phone_no ?? null)), ['class' => 'form-control', 'id' => 'company_phone', 'autocomplete' => 'off', 'placeholder' =>'Mobile Number', ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.update')] )}}
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
<script src="https://cdn.tiny.cloud/1/bmx2glw2c8gcbb3unhwsqjv47uvow53u8bzdlucgeuarx2qz/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/company-profile/company-profile.js') }}"></script>
@endpush
