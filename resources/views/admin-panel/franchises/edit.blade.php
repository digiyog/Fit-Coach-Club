@extends('admin-panel.layouts.main-layout')

@section('page-title', 'Edit Franchise | '.__('language.page_main_title').'')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">

<style>
    :root {
        --fcc-primary: #3246d3;
        --fcc-primary-light: #4361ee;
        --fcc-dark: #0f172a;
        --fcc-slate: #1e293b;
        --fcc-muted: #64748b;
        --fcc-border: #e2e8f0;
        --fcc-bg: #f8fafc;
        --fcc-card-bg: #ffffff;
        --fcc-success: #10b981;
        --fcc-warning: #f59e0b;
        --fcc-danger: #ef4444;
        --fcc-card-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
    }

    .fcc-create-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--fcc-slate);
        padding: 4px 2px 30px 2px;
    }

    .fcc-create-page h1,
    .fcc-create-page h2,
    .fcc-create-page h3,
    .fcc-create-page h4,
    .fcc-create-page h5,
    .fcc-create-page h6,
    .fcc-page-hero-title,
    .fcc-card-title,
    .fcc-section-title {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    }

    /* 1. Page Hero Header */
    .fcc-page-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #3246d3 100%);
        border-radius: 18px;
        padding: 24px 28px;
        color: #ffffff;
        margin-bottom: 24px;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.22);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        position: relative;
        overflow: hidden;
    }

    .fcc-page-hero::after {
        content: '';
        position: absolute;
        top: -60px;
        right: -40px;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(67, 97, 238, 0.25) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .fcc-page-hero-content {
        position: relative;
        z-index: 2;
    }

    .fcc-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        color: #e2e8f0;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 30px;
        margin-bottom: 8px;
        letter-spacing: 0.02em;
    }

    .fcc-hero-pill .fcc-pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: fccPulse 2s infinite;
    }

    @keyframes fccPulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .fcc-page-hero-title {
        font-size: 26px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 4px;
        letter-spacing: -0.02em;
        line-height: 1.25;
    }

    .fcc-page-hero-subtitle {
        color: #cbd5e1;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 0;
        max-width: 650px;
    }

    .fcc-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 13.5px;
        padding: 10px 18px;
        border-radius: 10px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
        white-space: nowrap;
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.22);
        backdrop-filter: blur(8px);
        position: relative;
        z-index: 2;
    }

    .fcc-btn-back:hover {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff !important;
        transform: translateY(-2px);
    }

    /* 2. Form Cards */
    .fcc-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 16px;
        box-shadow: var(--fcc-card-shadow);
        padding: 24px;
        margin-bottom: 24px;
        transition: all 0.25s ease;
    }

    .fcc-card-header-clean {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 16px;
        margin-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .fcc-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #eff6ff;
        color: var(--fcc-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .fcc-card-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--fcc-dark);
        margin: 0;
        letter-spacing: -0.01em;
    }

    .fcc-card-subtitle {
        font-size: 12.5px;
        color: var(--fcc-muted);
        margin: 2px 0 0 0;
        font-weight: 500;
    }

    /* Form Section Divider */
    .fcc-section-block {
        margin-bottom: 24px;
    }

    .fcc-section-title {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--fcc-dark);
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
    }

    .fcc-section-title i {
        color: var(--fcc-primary);
        width: 16px;
        height: 16px;
    }

    /* Form Controls */
    .fcc-form-group {
        margin-bottom: 18px;
    }

    .fcc-label {
        font-size: 12.5px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 7px;
        display: block;
    }

    .fcc-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .fcc-input-wrap .fcc-field-icon {
        position: absolute;
        left: 14px;
        color: #94a3b8;
        font-size: 15px;
        pointer-events: none;
        z-index: 2;
    }

    .fcc-input-wrap .form-control {
        padding-left: 42px !important;
        height: 44px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background-color: #f8fafc;
        font-size: 14px;
        color: #1e293b;
        transition: all 0.2s ease;
    }

    .fcc-input-wrap .form-control:focus {
        background-color: #ffffff;
        border-color: var(--fcc-primary);
        box-shadow: 0 0 0 3px rgba(50, 70, 211, 0.12);
        outline: none;
    }

    .fcc-input-wrap.has-password-toggle .form-control {
        padding-right: 42px !important;
    }

    .fcc-password-toggle {
        position: absolute;
        right: 14px;
        background: transparent;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 0;
        z-index: 3;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s ease;
    }

    .fcc-password-toggle:hover {
        color: var(--fcc-primary);
    }

    .fcc-field-hint {
        font-size: 12px;
        color: var(--fcc-muted);
        margin-top: 5px;
        display: block;
    }

    /* Dropify Upload Box Polish */
    .dropify-wrapper {
        width: 100% !important;
        height: 250px !important;
        border: 2px dashed #cbd5e1 !important;
        border-radius: 14px !important;
        background-color: #f8fafc !important;
        transition: all 0.2s ease !important;
    }

    .dropify-wrapper:hover {
        border-color: var(--fcc-primary) !important;
        background-color: #eff6ff !important;
    }

    .dropify-wrapper .dropify-message p {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        color: #475569 !important;
    }

    /* Form Actions Footer */
    .fcc-form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 18px;
        border-top: 1px solid #f1f5f9;
        margin-top: 10px;
    }

    .fcc-btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        font-size: 14px;
        padding: 12px 28px;
        border-radius: 10px;
        background: linear-gradient(135deg, #3246d3 0%, #4361ee 100%);
        border: none;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(50, 70, 211, 0.3);
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .fcc-btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(50, 70, 211, 0.4);
    }

    .fcc-btn-cancel {
        display: inline-flex;
        align-items: center;
        font-weight: 600;
        font-size: 14px;
        padding: 11px 22px;
        border-radius: 10px;
        background: #f1f5f9;
        color: #475569 !important;
        border: 1px solid #cbd5e1;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }

    .fcc-btn-cancel:hover {
        background: #e2e8f0;
        color: #0f172a !important;
    }

    .custom-dropify label.form-control-label {
        font-size: 12.5px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 7px;
        display: block;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing fcc-create-page">

    <!-- Validation error -->
    @component('admin-panel.validation.errors') @endcomponent
    <!-- / Validation error -->

    <!-- 1. Executive Page Hero -->
    <div class="fcc-page-hero">
        <div class="fcc-page-hero-content">
            <div class="fcc-hero-pill">
                <span class="fcc-pulse-dot"></span> Super Admin Portal • Franchise Management
            </div>
            <div class="fcc-page-hero-title">
                Edit Franchise Coach: {{ $franchise->name }} ✍️
            </div>
            <p class="fcc-page-hero-subtitle">
                Update coach account profile, contact information, and modify portal security credentials.
            </p>
        </div>

        <div>
            <a href="{{ route('adminPanel.franchises.index') }}" class="fcc-btn-back">
                <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back to Franchises
            </a>
        </div>
    </div>

    <!-- 2. Form Section -->
    {!! Form::open(['class' => 'franchise-form', 'method' => 'post', 'url' => route('adminPanel.franchises.update', ['id' => ev($franchise->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
        <div class="row">
            @php
                $imagePath = (get_image_url(config('constants.users.image_path'), $franchise->profile_image) ?? '');
            @endphp

            <!-- Left Column: Photo Uploader & Meta -->
            <div class="col-xl-4 col-lg-4 col-12">
                <div class="fcc-card">
                    <div class="fcc-card-header-clean">
                        <div class="fcc-card-icon">
                            <i data-feather="camera" style="width: 18px; height: 18px;"></i>
                        </div>
                        <div>
                            <h4 class="fcc-card-title">Profile Photo / Logo</h4>
                            <p class="fcc-card-subtitle">Coach or club brand visual</p>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <div class="custom-dropify">
                            <label class="form-control-label" for="image">Coach Avatar / Logo</label>
                            {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => $imagePath, ]) !!}
                            {!! Form::hidden('image_name', old('image_name', ($franchise->profile_image ?? null)) ,['class' => 'form-control','id' => 'image_name']) !!}
                        </div>
                        <small class="fcc-field-hint mt-2">
                            <i class="fa fa-info-circle text-primary me-1"></i> Upload a new image to replace the current photo.
                        </small>
                    </div>
                </div>

                <div class="fcc-card" style="padding: 18px 20px;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted" style="font-size: 13px; font-weight: 600;">Account Status</span>
                        @if($franchise->status == 1)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-warning">Inactive</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted" style="font-size: 13px; font-weight: 600;">Registered Date</span>
                        <span class="text-dark fw-bold" style="font-size: 13px;">{{ date('d M Y', strtotime($franchise->created_at)) }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Personal Info & Security -->
            <div class="col-xl-8 col-lg-8 col-12">
                <div class="fcc-card">

                    <!-- Section 1: Basic Information -->
                    <div class="fcc-section-block">
                        <h6 class="fcc-section-title">
                            <i data-feather="user"></i> Basic Information & Identity
                        </h6>

                        <div class="row">
                            <div class="col-12 fcc-form-group">
                                <label class="fcc-label" for="name">Franchise / Coach Name <span class="text-danger">*</span></label>
                                <div class="fcc-input-wrap">
                                    <i class="fa fa-user-circle fcc-field-icon"></i>
                                    {!! Form::text('name', $franchise->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Enter coach or franchise name', ]) !!}
                                </div>
                            </div>

                            <div class="col-md-6 col-12 fcc-form-group">
                                <label class="fcc-label" for="email">Email Address <span class="text-danger">*</span></label>
                                <div class="fcc-input-wrap">
                                    <i class="fa fa-envelope fcc-field-icon"></i>
                                    {!! Form::email('email', $franchise->email, ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'e.g. coach@fitcoachclub.com', 'autocomplete' => 'off', 'data-url' => route('adminPanel.franchises.checkEmail', ['id' => $franchise->id]) ]) !!}
                                </div>
                                <small class="fcc-field-hint">Used as login username for the coach portal.</small>
                            </div>

                            <div class="col-md-6 col-12 fcc-form-group">
                                <label class="fcc-label" for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                                <div class="fcc-input-wrap">
                                    <i class="fa fa-phone fcc-field-icon"></i>
                                    {!! Form::text('mobile_number', $franchise->mobile_number, ['class' => 'form-control numeric', 'id' => 'mobile_number', 'placeholder' => '10-digit mobile number', 'data-url' => route('adminPanel.franchises.checkMobile', ['id' => $franchise->id]) ]) !!}
                                </div>
                                <small class="fcc-field-hint">Used for SMS alerts and WhatsApp notifications.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Security & Credentials -->
                    <div class="fcc-section-block mb-2">
                        <h6 class="fcc-section-title">
                            <i data-feather="lock"></i> Portal Security Credentials
                        </h6>

                        <div class="row">
                            <div class="col-md-6 col-12 fcc-form-group">
                                <label class="fcc-label" for="new_pass">New Password <small class="text-muted fw-normal">(Leave blank to keep current)</small></label>
                                <div class="fcc-input-wrap has-password-toggle">
                                    <i class="fa fa-lock fcc-field-icon"></i>
                                    {!! Form::password('new_pass', ['class' => 'form-control', 'id' => 'new_pass', 'placeholder' => 'Enter new password', 'autocomplete' => 'new-password' ]) !!}
                                    <button type="button" class="fcc-password-toggle" onclick="togglePassField('new_pass', this)" title="Show/Hide Password">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                <small class="fcc-field-hint">Minimum 6 characters recommended.</small>
                            </div>

                            <div class="col-md-6 col-12 fcc-form-group">
                                <label class="fcc-label" for="confirm_pass">Confirm Password</label>
                                <div class="fcc-input-wrap has-password-toggle">
                                    <i class="fa fa-check-circle fcc-field-icon"></i>
                                    {!! Form::password('confirm_pass', ['class' => 'form-control', 'id' => 'confirm_pass', 'placeholder' => 'Confirm new password', ]) !!}
                                    <button type="button" class="fcc-password-toggle" onclick="togglePassField('confirm_pass', this)" title="Show/Hide Password">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                <small class="fcc-field-hint">Must match password above if changed.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Form Submission Bar -->
                    <div class="fcc-form-actions">
                        <a href="{{ route('adminPanel.franchises.index') }}" class="fcc-btn-cancel">
                            Cancel
                        </a>
                        <button type="submit" class="fcc-btn-submit btn-submit">
                            <i data-feather="check" style="width: 18px; height: 18px;"></i>
                            Update Franchise Profile
                        </button>
                    </div>

                </div>
            </div>

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
<script src="{{ asset('admin-assets/js/franchises/franchises.js') }}"></script>

<script>
    // Toggle Password Visibility Helper
    function togglePassField(fieldId, btn) {
        var input = document.getElementById(fieldId);
        if (!input) return;
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        } else {
            input.type = 'password';
            if (icon) {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    }

    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
