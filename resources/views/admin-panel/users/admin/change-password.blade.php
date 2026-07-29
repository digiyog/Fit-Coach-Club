@extends('admin-panel.layouts.main-layout')

@section('page-title', ' '.__('language.profile_page_title').' | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/intl-tel-input-master/build/css/intlTelInput.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin-assets/css/components/tabs-accordian/custom-tabs.css') }}" rel="stylesheet" type="text/css" />
<style>
    .dropify-wrapper
    {
        width:100% !important;
        margin-bottom: unset !important;
    }
    .iti
    {
        width: unset !important;
        display: block !important;
    }
    .custom-file-label
    {
        border: 1px solid #ced4da !important;
        color: #3b3f5c !important;
        white-space: nowrap;
        overflow: hidden;
    }
    .control_color_lightblack, input:read-only
    {
        color: #3b3f5c !important;
    }
    .control_bgcolor_white
    {
        background: #F1F2F3 !important;
        color: #3b3f5c !important;
    }
    .custom-doc-download-icon
    {
        color: #000; 
        width:30px; 
        height:30px; 
        font-size: 16px; 
        padding: 5px; 
        background: transparent !important;
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
                        <div class="form p-3">
                            {!! Form::open(['class' => 'change-password-form', 'method' => 'post', 'url' => route('adminPanel.profile.updatePassword'), 'enctype' => 'multipart/form-data' ]) !!}
                                <div class="form-row mb-4">
                                    <div class="col-md-12">
                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <label for="current_password"> {{ __('language.current_password') }} </label>
                                                {!! Form::password('current_password', ['class' => 'form-control', 'id' => 'current_password', 'placeholder' => __('language.current_password'), 'autocomplete' => 'off', ]) !!}
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <label for="new_password"> {{ __('language.new_password') }} </label>
                                                {!! Form::password('new_password', ['class' => 'form-control', 'id' => 'new_password', 'placeholder' => __('language.new_password'), 'autocomplete' => 'off' ] ) !!}
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <label for="confirm_password"> {{ __('language.confirm_password') }} </label>
                                                {!! Form::password('confirm_password', ['class' => 'form-control', 'id' => 'confirm_password', 'placeholder' => __('language.confirm_password'), 'autocomplete' => 'off' ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_update') ] )}}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/intl-tel-input-master/build/js/intlTelInput.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/intl-tel-input-master/build/js/intlTelInput-jquery.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/users/admin-profile.js') }}"></script>
@endpush