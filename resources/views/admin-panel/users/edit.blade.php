@extends('admin-panel.layouts.main-layout')

@section('page-title', 'Edit User | '.__('language.page_main_title').'')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">

<style>
    :root {
        --fcc-primary: #1d4ed8;
        --fcc-primary-hover: #1e40af;
        --fcc-dark: #0f172a;
        --fcc-slate: #1e293b;
        --fcc-muted: #64748b;
        --fcc-border: #e2e8f0;
        --fcc-card-bg: #ffffff;
        --fcc-banner-bg: #dbeafe;
        --fcc-banner-border: #bfdbfe;
        --fcc-banner-text: #1e3a8a;
    }

    .fcc-create-container {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--fcc-slate);
        padding: 8px 4px 60px 4px;
        width: 100%;
        max-width: 1440px;
        margin: 0 auto;
    }

    /* 1. Page Header Bar */
    .fcc-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 22px;
    }

    .fcc-header-info {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .fcc-header-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #e0f2fe;
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .fcc-header-title {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: var(--fcc-dark);
        letter-spacing: -0.02em;
        margin: 0;
        line-height: 1.2;
    }

    .fcc-header-subtitle {
        font-size: 13px;
        color: var(--fcc-muted);
        margin: 3px 0 0 0;
        font-weight: 500;
    }

    .fcc-btn-back {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 9px 18px;
        font-size: 13px;
        font-weight: 700;
        color: var(--fcc-dark);
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .fcc-btn-back:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #000000;
        transform: translateY(-1px);
    }

    /* 2. Structured White Cards */
    .fcc-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        margin-bottom: 22px;
        position: relative;
        overflow: visible !important;
    }

    .fcc-card-header-bar {
        background: var(--fcc-banner-bg);
        border-bottom: 1px solid var(--fcc-banner-border);
        padding: 12px 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .fcc-card:focus-within,
    .fcc-card.has-dropdown-open,
    .fcc-card:has(.show),
    .fcc-card:has(.open) {
        z-index: 1050 !important;
    }

    .fcc-card-header-bar span {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: var(--fcc-banner-text);
        letter-spacing: -0.01em;
    }

    .fcc-card-body {
        padding: 26px 28px;
    }

    /* Dropify Polish */
    .dropify-wrapper {
        width: 100% !important;
        height: 220px !important;
        border: 1.5px dashed #cbd5e1 !important;
        border-radius: 12px !important;
        background-color: #f8fafc !important;
    }

    /* Labels */
    .fcc-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--fcc-dark);
        margin-bottom: 7px;
        display: block;
    }

    .fcc-label .text-danger {
        color: #ef4444;
    }

    /* Inputs */
    .fcc-create-container .fcc-input-wrap {
        position: relative !important;
        display: block !important;
        width: 100% !important;
    }

    .fcc-create-container .fcc-input-wrap .fcc-input-icon {
        position: absolute !important;
        left: 15px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 18px !important;
        height: 18px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #94a3b8 !important;
        pointer-events: none !important;
        z-index: 4 !important;
    }

    .fcc-create-container .fcc-input-wrap .fcc-input-icon svg {
        width: 18px !important;
        height: 18px !important;
        stroke: #94a3b8 !important;
    }

    .fcc-create-container .fcc-input-wrap input.form-control {
        height: 48px !important;
        line-height: 48px !important;
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #ffffff !important;
        padding-left: 48px !important;
        padding-right: 16px !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #0f172a !important;
        width: 100% !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
    }

    .fcc-create-container .fcc-input-wrap input.form-control:focus {
        border-color: #1d4ed8 !important;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12) !important;
        outline: none !important;
    }

    /* 3. Footer Actions */
    .fcc-footer-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 26px;
        margin-bottom: 40px;
    }

    .fcc-btn-cancel {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 10px 24px;
        font-size: 13.5px;
        font-weight: 700;
        color: var(--fcc-dark);
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .fcc-btn-cancel:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #000000;
        transform: translateY(-1px);
    }

    .fcc-btn-save {
        background: var(--fcc-primary);
        border: none;
        border-radius: 10px;
        padding: 10px 28px;
        font-size: 13.5px;
        font-weight: 700;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(29, 78, 216, 0.35);
    }

    .fcc-btn-save:hover {
        background: var(--fcc-primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(29, 78, 216, 0.45);
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing fcc-create-container">

    <!-- 1. Page Header Bar -->
    <div class="fcc-page-header">
        <div class="fcc-header-info">
            <div class="fcc-header-icon-box">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
            </div>
            <div>
                <h1 class="fcc-header-title">Edit User Profile</h1>
                <p class="fcc-header-subtitle">Update credentials, profile photo, contact details, and account security.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('adminPanel.users.index') }}" class="fcc-btn-back">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Users
            </a>
        </div>
    </div>

    <!-- Error Alerts Component -->
    @component('admin-panel.validation.errors') @endcomponent

    {!! Form::open(['class' => 'edit-user-form', 'method' => 'post', 'url' => route('adminPanel.users.update', ['id' => ev($user->id)]), 'enctype' => 'multipart/form-data', 'id' => 'editUserForm' ]) !!}

        <!-- 2. Section: Profile Information Card -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span>General Member Information</span>
            </div>
            <div class="fcc-card-body">
                <div class="row align-items-center">
                    @php
                        $imagePath = (get_image_url('', $user->profile_image) ?? '');
                    @endphp

                    <!-- Left: Profile Photo -->
                    <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
                        <label class="fcc-label" for="image">@lang('language.upload_image')</label>
                        <div class="custom-dropify">
                            {!! Form::file('profile_image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => $imagePath, ]) !!}
                            {!! Form::hidden('image_name', old('image_name', ($user->profile_image ?? null)) ,['class' => 'form-control','id' => 'image_name']) !!}
                        </div>
                    </div>

                    <!-- Right: Info Fields -->
                    <div class="col-lg-9 col-md-8">
                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-12 mb-3">
                                <label class="fcc-label" for="name">Full Name <span class="text-danger">*</span></label>
                                <div class="fcc-input-wrap">
                                    <span class="fcc-input-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    </span>
                                    {!! Form::text('name', $user->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Enter user full name', 'required' => 'required']) !!}
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="col-md-6 mb-3">
                                <label class="fcc-label" for="email">Email Address <span class="text-danger">*</span></label>
                                <div class="fcc-input-wrap">
                                    <span class="fcc-input-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    </span>
                                    {!! Form::email('email', $user->email, ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Enter email', 'data-url' => route('adminPanel.users.checkEmail', ['id' => $user->id]), 'required' => 'required' ]) !!}
                                </div>
                            </div>

                            <!-- Mobile Number -->
                            <div class="col-md-6 mb-3">
                                <label class="fcc-label" for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                                <div class="fcc-input-wrap">
                                    <span class="fcc-input-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </span>
                                    {!! Form::text('mobile_number', $user->mobile_number, ['class' => 'form-control numeric', 'id' => 'mobile_number', 'placeholder' => 'Enter mobile number', 'data-url' => route('adminPanel.users.checkMobile', ['id' => $user->id]), 'required' => 'required']) !!}
                                </div>
                            </div>

                            <!-- Email Verification Toggle -->
                            <div class="col-12">
                                @php 
                                    $checked = !empty($user->email_verified_at) ? 'checked' : '';
                                @endphp
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    {!! Form::checkbox('email_verify', '1', $checked, ['class' => 'form-check-input', 'id' => 'email_verify' ]) !!}
                                    <label class="form-check-label fw-semibold text-dark mb-0 ms-1" for="email_verify" style="font-size: 13.5px; cursor: pointer;">
                                        Mark Email Address as Verified
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Section: Security Credentials Card -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <span>Account Security Credentials</span>
            </div>
            <div class="fcc-card-body">
                <div class="row">
                    <!-- New Password -->
                    <div class="col-md-6 mb-3">
                        <label class="fcc-label" for="new_pass">New Password <small class="text-muted">(Leave blank to keep unchanged)</small></label>
                        <div class="fcc-input-wrap">
                            <span class="fcc-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            </span>
                            {!! Form::password('new_pass', ['class' => 'form-control', 'id' => 'new_pass', 'placeholder' => 'Enter new password', ]) !!}
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6 mb-3">
                        <label class="fcc-label" for="confirm_pass">Confirm Password</label>
                        <div class="fcc-input-wrap">
                            <span class="fcc-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            </span>
                            {!! Form::password('confirm_pass', ['class' => 'form-control', 'id' => 'confirm_pass', 'placeholder' => 'Re-enter new password', ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Form Footer Actions (Cancel / Update) -->
        <div class="fcc-footer-actions">
            <a href="{{ route('adminPanel.users.index') }}" class="fcc-btn-cancel">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                Cancel
            </a>
            <button type="submit" class="fcc-btn-save" id="btnUpdateUser">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Update User
            </button>
        </div>

    {!! Form::close() !!}

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
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
