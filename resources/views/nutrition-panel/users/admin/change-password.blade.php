@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Change Password | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />

<style>
    /* Change Password Page Styles */
    .password-page-wrapper {
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

    /* Two Columns Grid Layout */
    .password-grid-layout {
        display: grid;
        grid-template-columns: 1.55fr 1fr;
        gap: 24px;
        align-items: stretch;
    }

    @media (max-width: 991px) {
        .password-grid-layout {
            grid-template-columns: 1fr;
        }
    }

    /* Card Styling */
    .password-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    /* Header with Circular Icon Badge */
    .card-header-with-badge {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
    }

    .header-icon-badge {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #2563eb;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.28);
    }

    .card-heading {
        font-size: 19px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.01em;
    }

    .card-subheading {
        font-size: 13.5px;
        color: #64748b;
        margin-bottom: 0;
    }

    /* Alert Banner inside Card */
    .password-info-alert {
        background: #f0f7ff;
        border: 1px solid #e0f2fe;
        border-radius: 10px;
        padding: 11px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #0369a1;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 22px;
    }

    .password-info-alert .alert-icon {
        color: #0284c7;
        font-size: 15px;
    }

    /* Form Fields */
    .form-group-custom {
        margin-bottom: 20px;
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
        height: 46px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding-left: 42px;
        padding-right: 42px;
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

    .btn-toggle-eye {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 15px;
        cursor: pointer;
        padding: 0;
        line-height: 1;
        z-index: 2;
        transition: color 0.15s ease;
    }

    .btn-toggle-eye:hover, .btn-toggle-eye:focus {
        color: #475569;
        outline: none;
    }

    .field-hint {
        display: block;
        font-size: 12.5px;
        color: #64748b;
        margin-top: 6px;
        font-weight: 400;
    }

    /* Button */
    .password-card-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 26px;
    }

    .btn-update-password {
        background: #2563eb;
        color: #ffffff !important;
        border: 1px solid #2563eb;
        border-radius: 9px;
        padding: 10px 22px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        text-decoration: none !important;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.28);
        cursor: pointer;
    }

    .btn-update-password:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
    }

    /* Right Card: Protect Your Account */
    .protect-account-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 32px 26px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .shield-illustration-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 6px;
        padding-top: 8px;
    }

    .security-card-title {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        text-align: center;
        margin-top: 14px;
        margin-bottom: 6px;
        letter-spacing: -0.01em;
    }

    .security-card-subtitle {
        font-size: 13.5px;
        color: #64748b;
        text-align: center;
        max-width: 310px;
        margin: 0 auto 24px auto;
        line-height: 1.5;
    }

    .security-tips-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
        max-width: 320px;
        margin: 0 auto 24px auto;
        width: 100%;
    }

    .security-tip-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .tip-check-circle {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #eff6ff;
        color: #2563eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        flex-shrink: 0;
    }

    .tip-text {
        font-size: 13.5px;
        color: #475569;
        font-weight: 500;
    }

    .security-alert-box {
        background: #f0f7ff;
        border: 1px solid #e0f2fe;
        border-radius: 10px;
        padding: 11px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #0369a1;
        font-size: 13px;
        font-weight: 500;
        margin-top: auto;
    }

    .security-alert-box .alert-icon {
        color: #0284c7;
        font-size: 15px;
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
        margin-top: 5px;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="password-page-wrapper">
        <!-- Breadcrumb Navigation -->
        <div class="profile-breadcrumb">
            <a href="{{ route('nutritionPanel.dashboard') }}">Settings & Info</a>
            <span class="crumb-sep">/</span>
            <span class="crumb-active">Change Password</span>
        </div>

        <!-- Header Section -->
        <div class="profile-header-section">
            <h1 class="profile-title">Change password</h1>
            <p class="profile-subtitle">Update the password used to access your account.</p>
        </div>

        <!-- Validation component -->
        @component('nutrition-panel.validation.errors') @endcomponent

        <div class="password-grid-layout">
            <!-- Left Column: Form Card -->
            <div class="password-card">
                <div class="card-header-with-badge">
                    <div class="header-icon-badge">
                        <i class="fa fa-lock"></i>
                    </div>
                    <div>
                        <h3 class="card-heading">Create a new password</h3>
                        <p class="card-subheading">Enter your current password, then choose and confirm a new one.</p>
                    </div>
                </div>

                <div class="password-info-alert">
                    <i class="fa fa-info-circle alert-icon"></i>
                    <span>All fields are required.</span>
                </div>

                {!! Form::open(['class' => 'change-password-form', 'method' => 'post', 'url' => route('nutritionPanel.change-password.update')]) !!}
                    <!-- Current Password -->
                    <div class="form-group-custom">
                        <label class="form-label-styled" for="current_password">Current Password <span class="text-danger">*</span></label>
                        <div class="input-with-icon">
                            <i class="fa fa-lock input-icon-left"></i>
                            {!! Form::password('current_password', [
                                'class' => 'form-control input-field-styled password-field',
                                'id' => 'current_password',
                                'placeholder' => 'Current Password',
                                'autocomplete' => 'current-password',
                                'required' => 'required'
                            ]) !!}
                            <button type="button" class="btn-toggle-eye toggle-password" data-target="#current_password" tabindex="-1" aria-label="Toggle password visibility">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="form-group-custom">
                        <label class="form-label-styled" for="new_password">New Password <span class="text-danger">*</span></label>
                        <div class="input-with-icon">
                            <i class="fa fa-lock input-icon-left"></i>
                            {!! Form::password('new_password', [
                                'class' => 'form-control input-field-styled password-field',
                                'id' => 'new_password',
                                'placeholder' => 'New Password',
                                'autocomplete' => 'new-password',
                                'required' => 'required'
                            ]) !!}
                            <button type="button" class="btn-toggle-eye toggle-password" data-target="#new_password" tabindex="-1" aria-label="Toggle password visibility">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        <span class="field-hint">Choose a strong password you don't use elsewhere.</span>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group-custom">
                        <label class="form-label-styled" for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                        <div class="input-with-icon">
                            <i class="fa fa-lock input-icon-left"></i>
                            {!! Form::password('confirm_password', [
                                'class' => 'form-control input-field-styled password-field',
                                'id' => 'confirm_password',
                                'placeholder' => 'Confirm Password',
                                'autocomplete' => 'new-password',
                                'required' => 'required'
                            ]) !!}
                            <button type="button" class="btn-toggle-eye toggle-password" data-target="#confirm_password" tabindex="-1" aria-label="Toggle password visibility">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        <span class="field-hint">Re-enter the new password to confirm it.</span>
                    </div>

                    <div class="password-card-actions">
                        <button type="submit" class="btn btn-update-password btn-submit">
                            <i class="fa fa-lock"></i> Update password
                        </button>
                    </div>
                {!! Form::close() !!}
            </div>

            <!-- Right Column: Protect Your Account -->
            <div class="protect-account-card">
                <div>
                    <div class="shield-illustration-wrapper">
                        <svg width="155" height="155" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Soft circular backdrop -->
                            <circle cx="80" cy="80" r="66" fill="#F0F7FF" />
                            
                            <!-- Floating decorative accent dots -->
                            <circle cx="28" cy="44" r="3.5" stroke="#93C5FD" stroke-width="1.5" fill="none" />
                            <circle cx="24" cy="98" r="3" stroke="#93C5FD" stroke-width="1.5" fill="none" />
                            <circle cx="134" cy="48" r="3" stroke="#93C5FD" stroke-width="1.5" fill="none" />
                            <circle cx="130" cy="112" r="3.5" stroke="#93C5FD" stroke-width="1.5" fill="none" />
                            <circle cx="98" cy="20" r="2.5" fill="#BFDBFE" />
                            <circle cx="56" cy="142" r="2.5" fill="#BFDBFE" />
                            
                            <!-- Shield Outline -->
                            <path d="M80 36C99 47 114 49 120 52C120 86 105 110 80 125C55 110 40 86 40 52C46 49 61 47 80 36Z" 
                                  stroke="#2563EB" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" fill="#FFFFFF"/>
                            
                            <!-- Padlock inside shield -->
                            <!-- Lock Shackle -->
                            <path d="M72 73V65C72 60.5817 75.5817 57 80 57C84.4183 57 88 60.5817 88 65V73" 
                                  stroke="#2563EB" stroke-width="2.8" stroke-linecap="round" fill="none" />
                            <!-- Lock Body -->
                            <rect x="67" y="73" width="26" height="21" rx="4.5" fill="#2563EB" />
                            <!-- Keyhole -->
                            <circle cx="80" cy="81.5" r="2.2" fill="#FFFFFF" />
                            <path d="M79.1 82.5H80.9L81.3 87H78.7L79.1 82.5Z" fill="#FFFFFF" />
                        </svg>
                    </div>

                    <h3 class="security-card-title">Protect your account</h3>
                    <p class="security-card-subtitle">Keep your password private and avoid reusing it across different services.</p>

                    <div class="security-tips-list">
                        <div class="security-tip-item">
                            <span class="tip-check-circle"><i class="fa fa-check"></i></span>
                            <span class="tip-text">Use a password unique to this account</span>
                        </div>
                        <div class="security-tip-item">
                            <span class="tip-check-circle"><i class="fa fa-check"></i></span>
                            <span class="tip-text">Confirm both new-password entries match</span>
                        </div>
                        <div class="security-tip-item">
                            <span class="tip-check-circle"><i class="fa fa-check"></i></span>
                            <span class="tip-text">Save only when you are ready</span>
                        </div>
                    </div>
                </div>

                <div class="security-alert-box">
                    <i class="fa fa-info-circle alert-icon"></i>
                    <span>Your password changes after a successful update.</span>
                </div>
            </div>
        </div>

        <!-- Bottom Full-width Info Banner -->
        <div class="profile-info-banner">
            <div class="banner-left">
                <i class="fa fa-info-circle banner-icon"></i>
                <span>You'll remain on this page if the update cannot be completed.</span>
            </div>
            <div class="banner-right">
                <span>Account security</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/users/admin-profile.js') }}?v={{ file_exists(public_path('admin-assets/js/users/admin-profile.js')) ? filemtime(public_path('admin-assets/js/users/admin-profile.js')) : time() }}"></script>
@endpush