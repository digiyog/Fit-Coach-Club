@extends('admin-panel.layouts.main-layout')

@section('page-title', ' '.__('language.profile_page_title').' | '.__('language.page_main_title').'')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/intl-tel-input-master/build/css/intlTelInput.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin-assets/css/components/tabs-accordian/custom-tabs.css') }}" rel="stylesheet" type="text/css" />

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

    /* Profile Side Card */
    .admin-profile-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--fcc-border);
        padding: 32px 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
    }

    .admin-profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3.5px solid #2563eb;
        padding: 3px;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
    }

    .admin-profile-name {
        font-family: 'Outfit', sans-serif;
        font-size: 18px;
        font-weight: 800;
        color: var(--fcc-dark);
        margin-top: 14px;
        margin-bottom: 4px;
    }

    .admin-profile-role {
        display: inline-block;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 11.5px;
        font-weight: 700;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .admin-profile-email {
        font-size: 13px;
        color: var(--fcc-muted);
        font-weight: 500;
    }

    /* 2. Structured White Cards & Modern Tabs */
    .fcc-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        margin-bottom: 22px;
        position: relative;
        overflow: visible !important;
    }

    .fcc-card:focus-within,
    .fcc-card.has-dropdown-open,
    .fcc-card:has(.show),
    .fcc-card:has(.open) {
        z-index: 1050 !important;
    }

    .fcc-card-header-tabs {
        background: #ffffff;
        border-bottom: 1.5px solid var(--fcc-border);
        padding: 10px 24px 0 24px;
    }

    .fcc-card-header-tabs .nav-tabs {
        border-bottom: none;
        gap: 10px;
    }

    .fcc-card-header-tabs .nav-link {
        border: none !important;
        border-bottom: 3px solid transparent !important;
        background: transparent !important;
        color: var(--fcc-muted) !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        padding: 12px 18px !important;
        border-radius: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        transition: all 0.2s ease !important;
    }

    .fcc-card-header-tabs .nav-link.active,
    .fcc-card-header-tabs .nav-link:hover {
        color: #1d4ed8 !important;
        border-bottom-color: #1d4ed8 !important;
    }

    .fcc-card-body {
        padding: 26px 28px;
    }

    /* Dropify Polish */
    .dropify-wrapper {
        width: 100% !important;
        height: 200px !important;
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

    .iti {
        width: 100% !important;
    }

    /* 3. Footer Actions */
    .fcc-footer-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 26px;
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
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div>
                <h1 class="fcc-header-title">Admin Account Profile</h1>
                <p class="fcc-header-subtitle">Manage your personal credentials, contact details, and account security.</p>
            </div>
        </div>
    </div>

    <!-- Error Alerts Component -->
    @component('admin-panel.validation.errors') @endcomponent

    <div class="row">
        <!-- Left: Profile Summary Card -->
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
                <img src="{{$profileImage}}" alt="avatar" class="admin-profile-avatar mb-2" />
                <h2 class="admin-profile-name">{{ $authUser->name }}</h2>
                <span class="admin-profile-role">{{ ucwords( Str::replace('-', ' ', $authUser->role_name ?? 'Super Admin') ) }}</span>
                <p class="admin-profile-email mb-0">{{ $authUser->email }}</p>
            </div>
        </div>

        <!-- Right: Tabs Card (Profile & Password) -->
        <div class="col-xl-9 col-lg-8 col-md-7 col-12">
            <div class="fcc-card">
                <div class="fcc-card-header-tabs">
                    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-profile-link" data-bs-toggle="tab" href="#tab-profile" role="tab" aria-selected="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                Personal Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-password-link" data-bs-toggle="tab" href="#tab-password" role="tab" aria-selected="false">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                Change Password
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="fcc-card-body">
                    <div class="tab-content" id="profileTabContent">

                        <!-- Tab 1: Profile Information -->
                        <div class="tab-pane fade show active" id="tab-profile" role="tabpanel">
                            {!! Form::open(['class' => 'update-profile-form', 'method' => 'post', 'url' => route('adminPanel.profile.update'), 'enctype' => 'multipart/form-data', 'id' => 'updateProfileForm' ]) !!}
                                <div class="row align-items-center">
                                    <div class="col-md-4 mb-4 mb-md-0">
                                        <label class="fcc-label" for="profile_image">{{ __('language.profile_image') }}</label>
                                        <div class="custom-dropify">
                                            {!! Form::file('profile_image', ['class' => 'image-preview', 'id' => 'profile_image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label class="fcc-label" for="name">{{ __('language.name') }} <span class="text-danger">*</span></label>
                                                <div class="fcc-input-wrap">
                                                    <span class="fcc-input-icon">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                    </span>
                                                    {!! Form::text('name', $authUser->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => __('language.name'), 'autocomplete' => 'off', 'required' => 'required' ]) !!}
                                                </div>
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label class="fcc-label" for="email">{{ __('language.email') }} <span class="text-danger">*</span></label>
                                                <div class="fcc-input-wrap">
                                                    <span class="fcc-input-icon">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                                    </span>
                                                    {!! Form::text('email', $authUser->email, ['class' => 'form-control', 'id' => 'email', 'placeholder' => __('language.email'), 'data-url' => route('adminPanel.profile.checkEmail'), 'autocomplete' => 'off', 'required' => 'required' ]) !!}
                                                </div>
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label class="fcc-label" for="mobile_number">{{ __('language.mobile_number') }} <span class="text-danger">*</span></label>
                                                <div class="fcc-input-wrap">
                                                    <span class="fcc-input-icon">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                                    </span>
                                                    {!! Form::tel('mobile_number', $authUser->mobile_number, ['class' => 'form-control', 'id' => 'mobile_number', 'placeholder' => __('language.mobile_number'), 'data-url' => route('adminPanel.profile.checkMobile'), 'autocomplete' => 'off', 'required' => 'required']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="fcc-footer-actions">
                                    <button type="submit" class="fcc-btn-save">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Save Profile
                                    </button>
                                </div>
                            {!! Form::close() !!}
                        </div>

                        <!-- Tab 2: Change Password -->
                        <div class="tab-pane fade" id="tab-password" role="tabpanel">
                            {!! Form::open(['class' => 'change-password-form', 'method' => 'post', 'url' => route('adminPanel.profile.updatePassword'), 'enctype' => 'multipart/form-data', 'id' => 'updatePasswordForm' ]) !!}
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="fcc-label" for="current_password">{{ __('language.current_password') }} <span class="text-danger">*</span></label>
                                        <div class="fcc-input-wrap">
                                            <span class="fcc-input-icon">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                            </span>
                                            {!! Form::password('current_password', ['class' => 'form-control', 'id' => 'current_password', 'placeholder' => 'Enter current password', 'autocomplete' => 'off', 'required' => 'required' ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="fcc-label" for="new_password">{{ __('language.new_password') }} <span class="text-danger">*</span></label>
                                        <div class="fcc-input-wrap">
                                            <span class="fcc-input-icon">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                            </span>
                                            {!! Form::password('new_password', ['class' => 'form-control', 'id' => 'new_password', 'placeholder' => 'Minimum 6 characters', 'autocomplete' => 'off', 'required' => 'required' ] ) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="fcc-label" for="confirm_password">{{ __('language.confirm_password') }} <span class="text-danger">*</span></label>
                                        <div class="fcc-input-wrap">
                                            <span class="fcc-input-icon">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                            </span>
                                            {!! Form::password('confirm_password', ['class' => 'form-control', 'id' => 'confirm_password', 'placeholder' => 'Must match new password', 'autocomplete' => 'off', 'required' => 'required' ]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="fcc-footer-actions">
                                    <button type="submit" class="fcc-btn-save">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Update Password
                                    </button>
                                </div>
                            {!! Form::close() !!}
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
