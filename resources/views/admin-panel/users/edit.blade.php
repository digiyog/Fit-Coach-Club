@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Edit User | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<style>
    .dropify-wrapper
    {
        width:100% !important;
        height: 280px !important;
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
                                <h4> User Profile </h4>
                            </div>
                        </div>

                        <div class="form p-3">
                            {!! Form::open(['class' => 'edit-user-form', 'method' => 'post', 'url' => route('adminPanel.users.update', ['id' => ev($user->id)]), 'enctype' => 'multipart/form-data' ]) !!}
                                <div class="row g-3 mb-4">
                                    @php
                                    $imagePath = (get_image_url('', $user->profile_image) ?? '');
                                    @endphp
                                    <div class="col-md-3">
                                        <div class="custom-dropify">
                                            <label class="form-control-label" for="image">@lang('language.upload_image')</label>
                                            {!! Form::file('profile_image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => $imagePath, ]) !!}

                                            {!! Form::hidden('image_name', old('image_name', ($user->profile_image ?? null)) ,['class' => 'form-control','id' => 'image_name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row ps-3">
                                            <div class="col-md-12 mt-3">
                                                <label for="name"> Name </label>
                                                {!! Form::text('name', $user->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Name', ]) !!}
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label for="email">Email </label>
                                                {!! Form::email('email', $user->email, ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Email','data-url' => route('adminPanel.users.checkEmail', ['id' => $user->id]) ]) !!}
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label for="mobile_number">Mobile Number </label>
                                                {!! Form::text('mobile_number', $user->mobile_number, ['class' => 'form-control numeric', 'id' => 'mobile_number', 'placeholder' => 'Mobile Number', 'data-url' => route('adminPanel.users.checkMobile', ['id' => $user->id]) ]) !!}
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                @php 
                                                    if(!empty($user->email_verified_at)){
                                                        $checked = 'checked';
                                                    }else{
                                                        $checked = '';
                                                    }
                                                @endphp
                                                {!!  Form::checkbox('email_verify', '1',$checked, ['class' => 'type', 'id' => 'email_verify' ]) !!}
                                                <label for="email_verify"> Is Email Verified </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="new_pass">New Password </label>
                                        {!! Form::password('new_pass', ['class' => 'form-control', 'id' => 'new_pass', 'placeholder' => 'New Password', ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="confirm_pass">Confirm Password </label>
                                        {!! Form::password('confirm_pass', ['class' => 'form-control', 'id' => 'confirm_pass', 'placeholder' => 'Confirm Password', ]) !!}
                                    </div>
                                </div>                                
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.service_update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.service_update')] )}}
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
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/users/users-edit.js') }}"></script>
@endpush
