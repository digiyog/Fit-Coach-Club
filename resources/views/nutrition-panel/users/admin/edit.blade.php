@extends('nutrition-panel.layouts.main-layout')

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

            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                <!-- Validation error -->
                @component('nutrition-panel.validation.errors') @endcomponent
                <!-- / Validation error -->

                @php
                    // Set dummy image if no image available
                    $profileImage = asset('admin-assets/images/user.png');
                    
                    // Check image availability
                    if(!empty($authUser->profile_image) && Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path_thumb').$authUser->profile_image)){
                        $profileImage = get_image_url(config('constants.users.image_path_thumb'), $authUser->profile_image);
                    }
                    else if(!empty($authUser->profile_image) && Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$authUser->profile_image)){
                        $profileImage = get_image_url(config('constants.users.image_path'), $authUser->profile_image);
                    }
                    else{
                        $profileImage = asset('admin-assets/images/user.png');
                    }
                @endphp

                <div class="row">
                    <!-- Content -->
                    <!-- <div class="col-xl-3 col-lg-6 col-md-5 col-sm-12">
                        <div class="user-profile">
                            <div class="widget-content widget-content-area">
                                <img src="{{$profileImage}}" alt="avatar" class="rounded-2" width="100%" height="100%" />
                            </div>
                        </div>
                        @if($authUser->qr_code != '')
                            <div class="user-profile mt-3">
                                <div class="widget-content widget-content-area">
                                    <img src="{{ get_image_url(config('constants.users.image_path'), $authUser->qr_code) }}" alt="avatar" class="rounded-2" width="100%" height="100%" />
                                </div>
                            </div>
                        @endif
                    </div> -->

                    @php
                        $showProfile = '';
                        $activeProfile = '';
                        $showPassword = '';
                        $activePassword = '';

                        if(request()->is(Request::segment(1).'/profile*')){
                            $showProfile = 'show active';
                            $activeProfile = 'active';
                            $showPassword = '';
                            $activePassword = '';
                        }

                        if(request()->is(Request::segment(1).'/change-password*')){
                            $showProfile = '';
                            $activeProfile = '';
                            $showPassword = 'show active';
                            $activePassword = 'active';
                        }
                    @endphp

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <div class="widget-content widget-content-area br-6">
                            <div class="animated-underline-content">
                                <!-- Tab Start -->
                                <ul class="nav nav-tabs mb-3" id="animateLine" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link {{ $activeProfile }}" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-profile" role="tab" aria-controls="animated-underline-home" aria-selected="true"> {{__('language.profile')}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $activePassword }}" id="animated-underline-change-password-tab" data-bs-toggle="tab" href="#animated-underline-change-password" role="tab" aria-controls="animated-underline-change-password" aria-selected="false"> {{__('language.change_password')}}</a>
                                    </li>
                                </ul>
                                <!-- Tab End -->
                                <!-- Tab Content start -->
                                <div class="tab-content" id="animateLineContent-4">
                                    
                                    <!-- Tab Content Profile -->
                                    <div class="tab-pane fade pt-0 {{ $showProfile }}" id="animated-underline-profile" role="tabpanel" aria-labelledby="animated-underline-profile-tab">
                                        <div class="form p-3">
                                            {!! Form::open(['class' => 'update-profile-form', 'method' => 'post', 'url' => route('nutritionPanel.profile.update'), 'enctype' => 'multipart/form-data' ]) !!}

                                                @php
                                                    $imagePath = '';
                                                    $qrCode = '';

                                                    if($authUser->profile_image){
                                                        $imagePath = (get_image_url(config('constants.users.image_path'), $authUser->profile_image) ?? '');
                                                    }

                                                    if($authUser->qr_code){
                                                        $qrCode = (get_image_url(config('constants.users.image_path'), $authUser->qr_code) ?? '');
                                                    }
                                                @endphp

                                                <div class="row g-3 mb-4">
                                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                                        <div class="custom-dropify">
                                                            <label for="profile_image"> {{ __('language.profile_image') }} </label>
                                                            {!! Form::file('profile_image', ['class' => 'image-preview', 'id' => 'profile_image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => $imagePath ]) !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                                        <div class="custom-dropify">
                                                            <label for="qr_code"> QR code </label>
                                                            {!! Form::file('qr_code', ['class' => 'image-preview', 'id' => 'qr_code', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => $qrCode ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row mb-2">
                                                            <div class="col-md-12">
                                                                <label for="name"> {{ __('language.name') }} <span class="text-danger">*</span></label>
                                                                {!! Form::text('name', $authUser->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => __('language.name'), 'autocomplete' => 'off' ]) !!}
                                                            </div>
                                                        </div>

                                                        <div class="row mb-2">
                                                            <div class="col-md-12">
                                                                <div>
                                                                    <label for="mobile_number"> {{ __('language.mobile_number') }} <span class="text-danger">*</span></label>
                                                                    {!! Form::tel('mobile_number', $authUser->mobile_number, ['class' => 'form-control', 'id' => 'mobile_number', 'placeholder' => __('language.mobile_number'), 'data-url' => route('nutritionPanel.profile.checkMobile'), 'autocomplete' => 'off']) !!}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-2">
                                                            <div class="col-md-12">
                                                                <label for="email"> {{ __('language.email') }} <span class="text-danger">*</span></label>
                                                                {!! Form::text('email', $authUser->email, ['class' => 'form-control', 'id' => 'email', 'placeholder' => __('language.email'), 'data-url' => route('nutritionPanel.profile.checkEmail'), 'autocomplete' => 'off' ]) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_save'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_save') ] )}}
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                    <!-- Tab Content Profile -->

                                    <!-- Tab Content Change Password -->
                                    <div class="tab-pane fade pt-0 {{ $showPassword }}" id="animated-underline-change-password" role="tabpanel" aria-labelledby="animated-underline-change-password-tab">
                                        <div class="form p-3">
                                            {!! Form::open(['class' => 'change-password-form', 'method' => 'post', 'url' => route('nutritionPanel.profile.updatePassword'), 'enctype' => 'multipart/form-data' ]) !!}
                                                <div class="row g-3 mb-4">
                                                    <div class="col-md-12">
                                                        <div class="row mb-2">
                                                            <div class="col-md-12">
                                                                <label for="current_password"> {{ __('language.current_password') }} <span class="text-danger">*</span></label>
                                                                {!! Form::password('current_password', ['class' => 'form-control', 'id' => 'current_password', 'placeholder' => __('language.current_password'), 'autocomplete' => 'off' ]) !!}
                                                            </div>
                                                        </div>

                                                        <div class="row mb-2">
                                                            <div class="col-md-12">
                                                                <label for="new_password"> {{ __('language.new_password') }} <span class="text-danger">*</span></label>
                                                                {!! Form::password('new_password', ['class' => 'form-control', 'id' => 'new_password', 'placeholder' => __('language.new_password'), 'autocomplete' => 'off' ] ) !!}
                                                            </div>
                                                        </div>

                                                        <div class="row mb-2">
                                                            <div class="col-md-12">
                                                                <label for="confirm_password"> {{ __('language.confirm_password') }} <span class="text-danger">*</span></label>
                                                                {!! Form::password('confirm_password', ['class' => 'form-control', 'id' => 'confirm_password', 'placeholder' => __('language.confirm_password'), 'autocomplete' => 'off' ]) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_save'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_save') ] )}}
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                    <!-- Tab Content Change Password -->
                                    
                                </div>
                                <!-- Tab Content End -->
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
<script src="{{ asset('admin-assets/js/users/admin-profile.js') }}"></script>
@endpush