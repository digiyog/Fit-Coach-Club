@extends('admin-panel.layouts.main-layout')

@section('page-title', ' '.__('language.profile_page_title').' | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/intl-tel-input-master/build/css/intlTelInput.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin-assets/css/components/tabs-accordian/custom-tabs.css') }}" rel="stylesheet" type="text/css" />
<style>
    .dropify-wrapper {
        width: 100% !important;
        margin-bottom: unset !important;
        height: 200px !important;
    }
    .iti {
        width: unset !important;
        display: block !important;
    }
    .admin-profile-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 24px 16px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .admin-profile-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #3246d3;
        padding: 2px;
        box-shadow: 0 2px 8px rgba(50, 70, 211, 0.2);
    }
</style>
@endpush

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                <!-- Validation error -->
                @component('admin-panel.validation.errors') @endcomponent
                <!-- / Validation error -->

                <div class="row">
                    <!-- Left Column: Profile Card -->
                    <div class="col-xl-3 col-lg-4 col-md-5 col-12 mb-4">
                        <div class="admin-profile-card">
                            @php
                                $profileImage = asset('admin-assets/images/user.png');
                                if(!empty($authUser->profile_image) && Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path_thumb').$authUser->profile_image)){
                                    $profileImage = get_image_url(config('constants.users.image_path_thumb'), $authUser->profile_image);
                                }
                                else if(!empty($authUser->profile_image) && Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$authUser->profile_image)){
                                    $profileImage = get_image_url(config('constants.users.image_path'), $authUser->profile_image);
                                }
                            @endphp
                            <img src="{{$profileImage}}" alt="avatar" class="admin-profile-avatar mb-3" />
                            <h5 class="fw-bold text-dark mb-1">{{$authUser->name}}</h5>
                            <span class="badge badge-purple px-3 py-1 mb-2">{{ ucwords( Str::replace('-', ' ', $authUser->role_name ?? 'Super Admin') ) }}</span>
                            <p class="text-muted fs-13 mb-0">{{ $authUser->email }}</p>
                        </div>
                    </div>

                    <!-- Right Column: Tabs -->
                    <div class="col-xl-9 col-lg-8 col-md-7 col-12">
                        <div class="widget-content widget-content-area br-6">
                            <div class="animated-underline-content">
                                <ul class="nav nav-tabs mb-4" id="animateLine" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active fw-bold" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-profile" role="tab" aria-controls="animated-underline-home" aria-selected="true">
                                            <i data-feather="user" style="width: 16px; height: 16px;" class="me-1"></i>
                                            {{__('language.profile')}}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link fw-bold" id="animated-underline-change-password-tab" data-bs-toggle="tab" href="#animated-underline-change-password" role="tab" aria-controls="animated-underline-change-password" aria-selected="false">
                                            <i data-feather="key" style="width: 16px; height: 16px;" class="me-1"></i>
                                            {{__('language.change_password')}}
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="animateLineContent-4">
                                    <!-- Tab 1: Profile -->
                                    <div class="tab-pane fade show active" id="animated-underline-profile" role="tabpanel" aria-labelledby="animated-underline-profile-tab">
                                        <div class="form pb-2">
                                            {!! Form::open(['class' => 'update-profile-form', 'method' => 'post', 'url' => route('adminPanel.profile.update'), 'enctype' => 'multipart/form-data' ]) !!}
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <div class="form-group">
                                                            <div class="custom-dropify">
                                                                <label for="profile_image">{{ __('language.profile_image') }}</label>
                                                                {!! Form::file('profile_image', ['class' => 'image-preview', 'id' => 'profile_image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', ]) !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-8">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="name">{{ __('language.name') }} <span class="text-danger">*</span></label>
                                                                    {!! Form::text('name', $authUser->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => __('language.name'), 'autocomplete' => 'off' ]) !!}
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="email">{{ __('language.email') }} <span class="text-danger">*</span></label>
                                                                    {!! Form::text('email', $authUser->email, ['class' => 'form-control', 'id' => 'email', 'placeholder' => __('language.email'), 'data-url' => route('adminPanel.profile.checkEmail'), 'autocomplete' => 'off' ]) !!}
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="mobile_number">{{ __('language.mobile_number') }} <span class="text-danger">*</span></label>
                                                                    {!! Form::tel('mobile_number', $authUser->mobile_number, ['class' => 'form-control', 'id' => 'mobile_number', 'placeholder' => __('language.mobile_number'), 'data-url' => route('adminPanel.profile.checkMobile'), 'autocomplete' => 'off']) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="admin-form-actions">
                                                    {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_save'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_save') ] )}}
                                                </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>

                                    <!-- Tab 2: Change Password -->
                                    <div class="tab-pane fade" id="animated-underline-change-password" role="tabpanel" aria-labelledby="animated-underline-change-password-tab">
                                        <div class="form pb-2">
                                            {!! Form::open(['class' => 'change-password-form', 'method' => 'post', 'url' => route('adminPanel.profile.updatePassword'), 'enctype' => 'multipart/form-data' ]) !!}
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="current_password">{{ __('language.current_password') }} <span class="text-danger">*</span></label>
                                                            {!! Form::password('current_password', ['class' => 'form-control', 'id' => 'current_password', 'placeholder' => __('language.current_password'), 'autocomplete' => 'off' ]) !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="new_password">{{ __('language.new_password') }} <span class="text-danger">*</span></label>
                                                            {!! Form::password('new_password', ['class' => 'form-control', 'id' => 'new_password', 'placeholder' => __('language.new_password'), 'autocomplete' => 'off' ] ) !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="confirm_password">{{ __('language.confirm_password') }} <span class="text-danger">*</span></label>
                                                            {!! Form::password('confirm_password', ['class' => 'form-control', 'id' => 'confirm_password', 'placeholder' => __('language.confirm_password'), 'autocomplete' => 'off' ]) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="admin-form-actions">
                                                    {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_save'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_save') ] )}}
                                                </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
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
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush