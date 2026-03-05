@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Calculate Calories | '.__('language.page_main_title').'')

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

            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                <!-- Validation error -->
                @component('nutrition-panel.validation.errors') @endcomponent
                <!-- / Validation error -->

                <div class="row align-item-stregth">
                    <!-- Content -->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="widget-content widget-content-area br-6">
                            <div class="animated-underline-content">
                                <!-- Tab Content start -->
                                <div class="tab-content" id="animateLineContent-4">
                                    
                                    <!-- Tab Content Profile -->
                                    <div class="tab-pane fade show active pt-0" id="animated-underline-profile" role="tabpanel" aria-labelledby="animated-underline-profile-tab">
                                        <div class="form p-3">
                                            {!! Form::open(['class' => 'calculate-calories-form', 'method' => 'post', 'url' => route('nutritionPanel.profile.update'), 'enctype' => 'multipart/form-data' ]) !!}

                                                <div class="row mb-3">
                                                    <div class="col-md-12">
                                                        <label for="weight"> Enter Weight(In Kg) <span class="text-danger">*</span></label>
                                                        {!! Form::number('weight', '', ['class' => 'form-control', 'id' => 'weight', 'placeholder' => 'Enter Weight(In Kg)', 'autocomplete' => 'off' ]) !!}
                                                    </div>

                                                    <div class="col-md-12 mt-3">
                                                        <label for="height"> Enter Height(In cm) <span class="text-danger">*</span></label>
                                                        {!! Form::number('height', '', ['class' => 'form-control', 'id' => 'height', 'placeholder' => 'Enter Height(In cm)', 'autocomplete' => 'off' ]) !!}
                                                    </div>

                                                    <div class="col-md-12 mt-3">
                                                        <label for="age"> Enter Age <span class="text-danger">*</span></label>
                                                        {!! Form::number('age', '', ['class' => 'form-control', 'id' => 'age', 'placeholder' => 'Enter Age', 'autocomplete' => 'off', 'min' => '5', 'max' => '120' ]) !!}
                                                    </div>
                                                        
                                                    <div class="col-md-12 mt-3">
                                                        <label for="gender">Select Gender</label>
                                                        {!! Form::select('gender', create_select_options(config('constants.users.gender'), 'caption', 'value', 'Select Gender'), '', ['class' => 'form-control select-picker', 'id' => 'gender' ]) !!}
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
                        <div class="user-profile" style="height: 100%;">
                            <div class="widget-content widget-content-area" style="height: 100%; display: flex; justify-content: center; align-items: center;">
                                <div class="text-center user-info mt-0 p-5" id="responseHide">
                                    <p><strong>🔥 You’re one step closer to your fitness goal!</strong></p>
                                    <p><strong>Let’s see what your body needs to stay on track 💪</strong></p>
                                    <!-- <img src="{{$profileImage}}" alt="avatar" class="rounded-circle" width="100" height="100" />
                                    <p class="mb-1">{{$authUser->name}}</p>
                                    <p class="m-0"><small>{{ ucwords( Str::replace('-', ' ', $authUser->role_name) ) }}</small></p> -->
                                </div>
                                <div class="text-center user-info mt-0 p-5 d-none" id="responseShow">
                                    <p><strong>🌿 Your health insights are ready.</strong></p>

                                    <p><strong>Today Calaroie Intake = </strong> <small class="text-black" id="calaroie_take">1150</small></p>
                                    <p><strong>Classification according to Your Weight, you are = </strong><small class="text-black" id="weight_calculate">1150</small></p>
                                    <p><strong>Your Body mass index(BMI),Kg/m2 = </strong><small class="text-black" id="body_mass">1150</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content -->
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
<script src="{{ asset('admin-assets/js/calculate-calories/index.js') }}"></script>
@endpush