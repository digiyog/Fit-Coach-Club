@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Your Info | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />

<style>
    /* Profile Page Styles */
    .profile-page-wrapper {
        padding: 6px 10px 40px 10px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Top Breadcrumb */
    .profile-breadcrumb {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .profile-breadcrumb a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .profile-breadcrumb a:hover {
        color: #2563eb;
    }

    .profile-breadcrumb .crumb-sep {
        color: #94a3b8;
    }

    .profile-breadcrumb .crumb-active {
        color: #0f172a;
        font-weight: 600;
    }

    /* Page Header */
    .profile-header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .profile-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .profile-subtitle {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 0;
        font-weight: 400;
    }

    /* Action Buttons */
    .btn-save-header, .btn-save-main {
        background: #2563eb;
        color: #ffffff !important;
        border: 1px solid #2563eb;
        border-radius: 9px;
        padding: 9px 20px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        text-decoration: none !important;
        box-shadow: 0 2px 5px rgba(37, 99, 235, 0.25);
        cursor: pointer;
    }

    .btn-save-header:hover, .btn-save-main:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
    }

    /* Two Columns Grid */
    .profile-grid-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 991px) {
        .profile-grid-layout {
            grid-template-columns: 1fr;
        }
    }

    /* Card Styling */
    .profile-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 26px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .card-title-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2px;
    }

    .title-accent-pill {
        width: 4px;
        height: 18px;
        background: #2563eb;
        border-radius: 99px;
        flex-shrink: 0;
    }

    .card-heading {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0;
        letter-spacing: -0.01em;
    }

    .card-subheading {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 22px;
    }

    /* Media Upload Side-by-side */
    .profile-media-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 540px) {
        .profile-media-grid {
            grid-template-columns: 1fr;
        }
    }

    .media-box {
        display: flex;
        flex-direction: column;
    }

    .media-box-label {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
    }

    /* Dropify Modern Overrides */
    .media-box .dropify-wrapper {
        height: 230px !important;
        border: 1.5px dashed #cbd5e1 !important;
        border-radius: 12px !important;
        background-color: #f8fafc !important;
        transition: all 0.2s ease !important;
        padding: 8px !important;
    }

    .media-box .dropify-wrapper:hover {
        border-color: #2563eb !important;
        background-color: #f0f7ff !important;
    }

    .media-box .dropify-wrapper .dropify-message {
        top: 50% !important;
        transform: translateY(-50%) !important;
    }

    .media-box .dropify-wrapper .dropify-message span.file-icon:before {
        content: '\f0ee' !important;
        font-family: FontAwesome !important;
        font-size: 42px !important;
        color: #3b82f6 !important;
    }

    .media-box .dropify-wrapper .dropify-message p {
        font-size: 13px !important;
        color: #475569 !important;
        font-weight: 500 !important;
        margin-top: 10px !important;
    }

    .media-box .dropify-wrapper .dropify-preview {
        border-radius: 10px !important;
        background-color: #ffffff !important;
        padding: 4px !important;
    }

    .media-box .dropify-wrapper .dropify-render img {
        border-radius: 8px !important;
        object-fit: cover !important;
    }

    .media-footer-note {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
        margin-top: 26px;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
    }

    .media-shield-icon {
        color: #2563eb;
        font-size: 16px;
    }

    /* Contact Details Form Card */
    .contact-required-alert {
        background: #f0f7ff;
        border: 1px solid #e0f2fe;
        border-radius: 10px;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #0369a1;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 22px;
    }

    .contact-required-alert .alert-icon {
        color: #0284c7;
        font-size: 15px;
    }

    .form-group-custom {
        margin-bottom: 18px;
    }

    .form-label-styled {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon .input-icon-left {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 15px;
        pointer-events: none;
        z-index: 2;
    }

    .input-field-styled {
        height: 44px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding-left: 42px;
        padding-right: 14px;
        font-size: 14px;
        color: #0f172a;
        background-color: #ffffff;
        transition: all 0.2s ease;
        width: 100%;
        box-shadow: none !important;
    }

    .input-field-styled:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    .contact-card-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 24px;
    }

    /* Bottom Full-width Banner */
    .profile-info-banner {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.02);
        flex-wrap: wrap;
        gap: 12px;
    }

    .profile-info-banner .banner-left {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #475569;
        font-size: 13.5px;
        font-weight: 500;
    }

    .profile-info-banner .banner-icon {
        color: #2563eb;
        font-size: 16px;
    }

    .profile-info-banner .banner-right {
        color: #94a3b8;
        font-size: 13px;
        font-weight: 500;
    }

    /* Validation Errors */
    .invalid-feedback {
        display: block;
        font-size: 12px;
        font-weight: 500;
        color: #ef4444;
        margin-top: 4px;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="profile-page-wrapper">
        <!-- Breadcrumb Navigation -->
        <div class="profile-breadcrumb">
            <a href="{{ route('nutritionPanel.dashboard') }}">Settings & Info</a>
            <span class="crumb-sep">/</span>
            <span class="crumb-active">Your Info</span>
        </div>

        <!-- Header Section -->
        <div class="profile-header-section">
            <div>
                <h1 class="profile-title">Your info</h1>
                <p class="profile-subtitle">Manage your profile image, QR code and contact details.</p>
            </div>
            <div>
                <button type="button" class="btn btn-save-header" id="btn_save_top">
                    <i class="fa fa-save"></i> Save changes
                </button>
            </div>
        </div>

        <!-- Validation component -->
        @component('nutrition-panel.validation.errors') @endcomponent

        @php
            $imagePath = '';
            $qrCode = '';

            if(!empty($authUser->profile_image)){
                if (Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path_thumb').$authUser->profile_image)) {
                    $imagePath = get_image_url(config('constants.users.image_path_thumb'), $authUser->profile_image);
                } elseif (Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$authUser->profile_image)) {
                    $imagePath = get_image_url(config('constants.users.image_path'), $authUser->profile_image);
                } else {
                    $imagePath = asset('admin-assets/images/user.png');
                }
            }

            if(!empty($authUser->qr_code)){
                if (Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$authUser->qr_code)) {
                    $qrCode = get_image_url(config('constants.users.image_path'), $authUser->qr_code);
                }
            }
        @endphp

        <!-- Main Form -->
        {!! Form::open(['class' => 'update-profile-form', 'method' => 'post', 'url' => route('nutritionPanel.profile.update'), 'enctype' => 'multipart/form-data' ]) !!}
            <div class="profile-grid-layout">
                <!-- Left Card: Profile media -->
                <div class="profile-card">
                    <div class="card-title-bar">
                        <div class="title-accent-pill"></div>
                        <h3 class="card-heading">Profile media</h3>
                    </div>
                    <p class="card-subheading">Your profile image and QR code.</p>

                    <div class="profile-media-grid">
                        <!-- Profile Image Box -->
                        <div class="media-box">
                            <label class="media-box-label" for="profile_image">Profile image</label>
                            {!! Form::file('profile_image', [
                                'class' => 'image-preview',
                                'id' => 'profile_image',
                                'autocomplete' => 'off',
                                'data-show-remove' => 'false',
                                'accept' => 'image/*',
                                'data-default-file' => $imagePath,
                                'data-height' => '220'
                            ]) !!}
                        </div>

                        <!-- QR Code Box -->
                        <div class="media-box">
                            <label class="media-box-label" for="qr_code">QR code</label>
                            {!! Form::file('qr_code', [
                                'class' => 'image-preview',
                                'id' => 'qr_code',
                                'autocomplete' => 'off',
                                'data-show-remove' => 'false',
                                'accept' => 'image/*',
                                'data-default-file' => $qrCode,
                                'data-height' => '220'
                            ]) !!}
                        </div>
                    </div>

                    <div class="media-footer-note">
                        <i class="fa fa-shield media-shield-icon"></i>
                        <span>Profile media is visible only where supported.</span>
                    </div>
                </div>

                <!-- Right Card: Contact details -->
                <div class="profile-card">
                    <div class="card-title-bar">
                        <div class="title-accent-pill"></div>
                        <h3 class="card-heading">Contact details</h3>
                    </div>
                    <p class="card-subheading">Update the information associated with this account.</p>

                    <div class="contact-required-alert">
                        <i class="fa fa-info-circle alert-icon"></i>
                        <span>Required fields are marked with <span class="text-danger">*</span></span>
                    </div>

                    <!-- Name -->
                    <div class="form-group-custom">
                        <label class="form-label-styled" for="name">Name <span class="text-danger">*</span></label>
                        <div class="input-with-icon">
                            <i class="fa fa-user input-icon-left"></i>
                            {!! Form::text('name', $authUser->name, [
                                'class' => 'form-control input-field-styled',
                                'id' => 'name',
                                'placeholder' => 'Enter name',
                                'autocomplete' => 'off'
                            ]) !!}
                        </div>
                    </div>

                    <!-- Mobile Number -->
                    <div class="form-group-custom">
                        <label class="form-label-styled" for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                        <div class="input-with-icon">
                            <i class="fa fa-mobile-phone input-icon-left" style="font-size: 20px;"></i>
                            {!! Form::tel('mobile_number', $authUser->mobile_number, [
                                'class' => 'form-control input-field-styled',
                                'id' => 'mobile_number',
                                'placeholder' => 'Enter mobile number',
                                'data-url' => route('nutritionPanel.profile.checkMobile'),
                                'autocomplete' => 'off'
                            ]) !!}
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group-custom">
                        <label class="form-label-styled" for="email">Email <span class="text-danger">*</span></label>
                        <div class="input-with-icon">
                            <i class="fa fa-envelope-o input-icon-left"></i>
                            {!! Form::text('email', $authUser->email, [
                                'class' => 'form-control input-field-styled',
                                'id' => 'email',
                                'placeholder' => 'Enter email address',
                                'data-url' => route('nutritionPanel.profile.checkEmail'),
                                'autocomplete' => 'off'
                            ]) !!}
                        </div>
                    </div>

                    <div class="contact-card-actions">
                        <button type="submit" class="btn btn-save-main btn-submit">
                            <i class="fa fa-save"></i> Save changes
                        </button>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}

        <!-- Bottom Full-width Info Banner -->
        <div class="profile-info-banner">
            <div class="banner-left">
                <i class="fa fa-info-circle banner-icon"></i>
                <span>Review your details before saving changes.</span>
            </div>
            <div class="banner-right">
                <span>Profile settings</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/users/admin-profile.js') }}?v={{ file_exists(public_path('admin-assets/js/users/admin-profile.js')) ? filemtime(public_path('admin-assets/js/users/admin-profile.js')) : time() }}"></script>
@endpush