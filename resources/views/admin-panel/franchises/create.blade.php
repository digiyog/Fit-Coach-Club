@extends('admin-panel.layouts.main-layout')

@section('page-title', 'Create Franchise | '.__('language.page_main_title').'')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">

<style>
    /* ==========================================================================
       FITCOACHCLUB CREATE FRANCHISE MODERN EXECUTIVE FORM
       ========================================================================== */
    .fcc-create-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #1e293b;
        padding: 6px 4px 40px 4px;
        max-width: 100%;
    }

    /* 1. Breadcrumbs */
    .fcc-breadcrumb-nav {
        margin-bottom: 12px;
    }

    .fcc-breadcrumb-nav .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 13px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .fcc-breadcrumb-nav .breadcrumb-item {
        display: inline-flex;
        align-items: center;
        color: #64748b;
    }

    .fcc-breadcrumb-nav .breadcrumb-item a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .fcc-breadcrumb-nav .breadcrumb-item a:hover {
        color: #1d4ed8;
    }

    .fcc-breadcrumb-nav .breadcrumb-item.active {
        color: #0f172a;
        font-weight: 600;
    }

    .fcc-breadcrumb-nav .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #94a3b8;
        font-size: 15px;
        padding: 0 4px;
    }

    /* 2. Page Header */
    .fcc-create-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 22px;
    }

    .fcc-create-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .fcc-create-header-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #e0f2fe;
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.12);
    }

    .fcc-create-header-icon svg {
        width: 24px;
        height: 24px;
        stroke: #0284c7;
    }

    .fcc-create-title {
        font-family: 'Outfit', sans-serif;
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        margin: 0;
        line-height: 1.2;
    }

    .fcc-create-subtitle {
        font-size: 13.5px;
        color: #64748b;
        margin: 4px 0 0 0;
        font-weight: 500;
    }

    .fcc-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        text-decoration: none !important;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .fcc-btn-back:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
        transform: translateY(-1px);
    }

    .fcc-btn-back svg {
        width: 15px;
        height: 15px;
        stroke: #475569;
    }

    /* 3. Main Card */
    .fcc-create-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
        padding: 28px 32px;
    }

    @media (max-width: 767px) {
        .fcc-create-card {
            padding: 20px 16px;
            border-radius: 12px;
        }
    }

    /* 4. Logo / Photo Upload Section */
    .fcc-logo-uploader-card {
        display: flex;
        align-items: center;
        gap: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .fcc-logo-preview-box {
        width: 140px;
        height: 140px;
        min-width: 140px;
        border-radius: 14px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 12px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .fcc-logo-preview-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.2s ease;
    }

    .fcc-logo-delete-badge {
        position: absolute;
        top: 7px;
        right: 7px;
        width: 26px;
        height: 26px;
        border-radius: 6px;
        background: #ef4444;
        color: #ffffff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.15s ease, transform 0.15s ease;
        z-index: 5;
    }

    .fcc-logo-delete-badge:hover {
        background: #dc2626;
        transform: scale(1.05);
    }

    .fcc-logo-delete-badge svg {
        width: 13px;
        height: 13px;
        stroke: #ffffff;
    }

    .fcc-logo-uploader-content {
        flex: 1 1 300px;
    }

    .fcc-logo-title {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .fcc-logo-desc {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 14px;
        line-height: 1.45;
    }

    .fcc-logo-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .fcc-btn-upload {
        background: #1d4ed8;
        border: 1px solid #1d4ed8;
        border-radius: 8px;
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 18px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(29, 78, 216, 0.22);
    }

    .fcc-btn-upload:hover {
        background: #1e40af;
        border-color: #1e40af;
        box-shadow: 0 4px 10px rgba(29, 78, 216, 0.32);
        transform: translateY(-1px);
    }

    .fcc-btn-upload svg {
        width: 15px;
        height: 15px;
        stroke: #ffffff;
    }

    .fcc-btn-remove {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 18px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .fcc-btn-remove:hover {
        background: #f8fafc;
        border-color: #ef4444;
        color: #ef4444;
    }

    .fcc-btn-remove svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
    }

    .fcc-logo-meta {
        font-size: 12px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .fcc-logo-meta .meta-separator {
        color: #cbd5e1;
    }

    /* 5. Section Banners */
    .fcc-section-banner {
        background: #dbeafe;
        border-radius: 9px;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 24px;
        margin-bottom: 20px;
    }

    .fcc-section-banner svg {
        width: 17px;
        height: 17px;
        color: #1d4ed8;
        stroke: #1d4ed8;
        flex-shrink: 0;
    }

    .fcc-section-banner span {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    /* 6. Form Fields & Groups */
    .fcc-label {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 7px;
        display: block;
    }

    .fcc-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .fcc-input-icon {
        position: absolute;
        left: 14px;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        pointer-events: none;
        z-index: 2;
    }

    .fcc-input-icon svg {
        width: 17px;
        height: 17px;
        stroke: #94a3b8;
    }

    .fcc-form-control {
        height: 46px !important;
        border-radius: 10px !important;
        border: 1px solid #cbd5e1 !important;
        background: #ffffff !important;
        padding-left: 42px !important;
        padding-right: 14px !important;
        font-size: 13.5px !important;
        color: #0f172a !important;
        width: 100% !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
    }

    .fcc-form-control::placeholder {
        color: #94a3b8 !important;
        font-weight: 400;
    }

    .fcc-form-control:focus {
        border-color: #1d4ed8 !important;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12) !important;
        outline: none !important;
    }

    .fcc-help-text {
        font-size: 12px;
        color: #64748b;
        margin-top: 6px;
        display: block;
        font-weight: 500;
    }

    /* Password Eye Toggle */
    .fcc-password-toggle {
        position: absolute;
        right: 10px;
        background: transparent;
        border: none;
        padding: 6px;
        color: #94a3b8;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 3;
        border-radius: 6px;
        transition: color 0.15s ease;
    }

    .fcc-password-toggle:hover {
        color: #0f172a;
    }

    .fcc-password-toggle svg {
        width: 17px;
        height: 17px;
        stroke: currentColor;
    }

    /* Bootstrap Select Picker Polish */
    .fcc-select-wrapper .bootstrap-select {
        width: 100% !important;
    }

    .fcc-select-wrapper .bootstrap-select > .dropdown-toggle {
        height: 46px !important;
        border-radius: 10px !important;
        border: 1px solid #cbd5e1 !important;
        background: #ffffff !important;
        padding-left: 42px !important;
        padding-right: 28px !important;
        font-size: 13.5px !important;
        color: #0f172a !important;
        display: flex !important;
        align-items: center !important;
        box-shadow: none !important;
    }

    .fcc-select-wrapper .bootstrap-select > .dropdown-toggle:focus {
        border-color: #1d4ed8 !important;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12) !important;
        outline: none !important;
    }

    .fcc-select-wrapper .bootstrap-select .filter-option-inner-inner {
        color: #475569;
        font-size: 13.5px;
    }

    /* Validation feedback */
    .invalid-feedback {
        font-size: 12px !important;
        color: #ef4444 !important;
        margin-top: 5px !important;
        display: block !important;
        font-weight: 600;
    }

    .form-group.has-danger .fcc-form-control {
        border-color: #ef4444 !important;
    }

    /* 7. Footer Actions */
    .fcc-form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }

    .fcc-btn-cancel {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px 22px;
        font-size: 13.5px;
        font-weight: 600;
        color: #334155;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.2s ease;
    }

    .fcc-btn-cancel:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .fcc-btn-cancel svg {
        width: 15px;
        height: 15px;
        stroke: #64748b;
    }

    .fcc-btn-save {
        background: #1d4ed8;
        border: 1px solid #1d4ed8;
        border-radius: 8px;
        padding: 10px 26px;
        font-size: 13.5px;
        font-weight: 600;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(29, 78, 216, 0.28);
    }

    .fcc-btn-save:hover {
        background: #1e40af;
        border-color: #1e40af;
        box-shadow: 0 4px 14px rgba(29, 78, 216, 0.4);
        transform: translateY(-1px);
    }

    .fcc-btn-save svg {
        width: 16px;
        height: 16px;
        stroke: #ffffff;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing fcc-create-wrapper">

    <!-- 1. Breadcrumb Navigation -->
    <nav class="fcc-breadcrumb-nav" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('adminPanel.dashboard') }}">
                    <i data-feather="home" style="width: 14px; height: 14px;"></i> Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('adminPanel.franchises.index') }}">Franchises</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Create Franchise</li>
        </ol>
    </nav>

    <!-- 2. Header Row -->
    <div class="fcc-create-header">
        <div class="fcc-create-header-left">
            <div class="fcc-create-header-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </div>
            <div>
                <h1 class="fcc-create-title">Create Franchise</h1>
                <p class="fcc-create-subtitle">Register a new franchise partner in the FitCoachClub platform.</p>
            </div>
        </div>
        <div class="fcc-create-header-right">
            <a href="{{ route('adminPanel.franchises.index') }}" class="fcc-btn-back">
                <i data-feather="arrow-left"></i> Back to Franchises
            </a>
        </div>
    </div>

    <!-- 3. Form Card -->
    <div class="fcc-create-card">
        <!-- Validation error component -->
        @component('admin-panel.validation.errors') @endcomponent

        {!! Form::open(['class' => 'franchise-form', 'method' => 'post', 'url' => route('adminPanel.franchises.store'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'id' => 'createFranchiseForm' ]) !!}

            <!-- Logo Section -->
            <div class="fcc-logo-uploader-card">
                <div class="fcc-logo-preview-box" id="logoPreviewBox">
                    <button type="button" class="fcc-logo-delete-badge" id="btnDeleteLogo" title="Delete image" style="display: none;">
                        <i data-feather="trash-2"></i>
                    </button>
                    <img id="franchiseLogoPreview" src="{{ asset('admin-assets/images/fit-coach-club-logo.svg') }}" alt="Franchise Logo" />
                </div>
                <div class="fcc-logo-uploader-content">
                    <div class="fcc-logo-title">
                        Franchise Logo / Photo <span class="text-danger">*</span>
                    </div>
                    <p class="fcc-logo-desc">
                        Upload your franchise or brand logo. It will be used across the franchise portal.
                    </p>

                    <div class="fcc-logo-actions">
                        <!-- Hidden File Input -->
                        <input type="file" name="image" id="franchiseImageInput" accept="image/png, image/jpeg, image/jpg, image/webp" class="d-none">
                        <input type="hidden" name="image_name" id="image_name" value="">

                        <button type="button" class="fcc-btn-upload" id="btnUploadImage">
                            <i data-feather="upload"></i> Upload Image
                        </button>
                        <button type="button" class="fcc-btn-remove" id="btnRemoveImage" style="display: none;">
                            <i data-feather="trash-2"></i> Remove
                        </button>
                    </div>

                    <div class="fcc-logo-meta">
                        <span>Supported formats: JPG, PNG, WebP</span>
                        <span class="meta-separator">|</span>
                        <span>Max size: 2 MB</span>
                        <span class="meta-separator">|</span>
                        <span>Recommended: Square (512 × 512 px)</span>
                    </div>
                    <div id="image-error-container"></div>
                </div>
            </div>

            <!-- General Information Section Banner -->
            <div class="fcc-section-banner">
                <i data-feather="user"></i>
                <span>General Information</span>
            </div>

            <div class="row">
                <!-- Franchise / Coach Name -->
                <div class="col-lg-6 col-12 mb-4">
                    <div class="form-group mb-0">
                        <label class="fcc-label" for="name">Franchise / Coach Name <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrapper">
                            <span class="fcc-input-icon"><i data-feather="user"></i></span>
                            <input type="text" name="name" id="name" class="form-control fcc-form-control" placeholder="Enter franchise or coach name" required value="{{ old('name') }}">
                        </div>
                    </div>
                </div>

                <!-- Email Address -->
                <div class="col-lg-6 col-12 mb-4">
                    <div class="form-group mb-0">
                        <label class="fcc-label" for="email">Email Address <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrapper">
                            <span class="fcc-input-icon"><i data-feather="mail"></i></span>
                            <input type="email" name="email" id="email" class="form-control fcc-form-control" placeholder="coach@fitcoachclub.com" autocomplete="off" data-url="{{ route('adminPanel.franchises.checkEmail') }}" required value="{{ old('email') }}">
                        </div>
                        <span class="fcc-help-text">Used as login username for the coach portal.</span>
                    </div>
                </div>

                <!-- Mobile Number -->
                <div class="col-lg-6 col-12 mb-4">
                    <div class="form-group mb-0">
                        <label class="fcc-label" for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrapper">
                            <span class="fcc-input-icon"><i data-feather="phone"></i></span>
                            <input type="text" name="mobile_number" id="mobile_number" class="form-control fcc-form-control numeric" placeholder="Enter 10-digit mobile number" data-url="{{ route('adminPanel.franchises.checkMobile') }}" required value="{{ old('mobile_number') }}">
                        </div>
                    </div>
                </div>

                <!-- Select Allowed Products -->
                <div class="col-lg-6 col-12 mb-4">
                    <div class="form-group mb-0">
                        <label class="fcc-label" for="products">Select Allowed Products</label>
                        <div class="fcc-input-wrapper fcc-select-wrapper">
                            <span class="fcc-input-icon"><i data-feather="layers"></i></span>
                            <select name="products[]" id="products" class="form-control fcc-form-control select-picker" multiple data-live-search="true" title="Choose products this franchise can distribute...">
                                @foreach($products as $key => $value)
                                    <option value="{{$value['id']}}">{{ $value['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="fcc-help-text">Assign specific core products and sale to this franchise.</span>
                    </div>
                </div>
            </div>

            <!-- Security Credentials Section Banner -->
            <div class="fcc-section-banner">
                <i data-feather="lock"></i>
                <span>Portal Security Credentials</span>
            </div>

            <div class="row">
                <!-- New Password -->
                <div class="col-lg-6 col-12 mb-4">
                    <div class="form-group mb-0">
                        <label class="fcc-label" for="new_pass">New Password <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrapper">
                            <span class="fcc-input-icon"><i data-feather="lock"></i></span>
                            <input type="password" name="new_pass" id="new_pass" class="form-control fcc-form-control" placeholder="Enter secure password" autocomplete="new-password" required>
                            <button type="button" class="fcc-password-toggle" data-target="#new_pass" tabindex="-1" title="Toggle password visibility">
                                <i data-feather="eye"></i>
                            </button>
                        </div>
                        <span class="fcc-help-text">Minimum 6 characters recommended.</span>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="col-lg-6 col-12 mb-4">
                    <div class="form-group mb-0">
                        <label class="fcc-label" for="confirm_pass">Confirm Password <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrapper">
                            <span class="fcc-input-icon"><i data-feather="lock"></i></span>
                            <input type="password" name="confirm_pass" id="confirm_pass" class="form-control fcc-form-control" placeholder="Re-enter password" required>
                            <button type="button" class="fcc-password-toggle" data-target="#confirm_pass" tabindex="-1" title="Toggle password visibility">
                                <i data-feather="eye"></i>
                            </button>
                        </div>
                        <span class="fcc-help-text">Must match password above.</span>
                    </div>
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="fcc-form-actions">
                <a href="{{ route('adminPanel.franchises.index') }}" class="fcc-btn-cancel">
                    <i data-feather="x"></i> Cancel
                </a>
                <button type="submit" class="fcc-btn-save btn-submit">
                    <i data-feather="check"></i> Save Franchise
                </button>
            </div>

        {!! Form::close() !!}
    </div>
</div>
@endsection

@push('scripts')
<script>
    var maxImageSize = {{ config('constants.max_image_size') ?? 2097152 }};
    var defaultLogoUrl = "{{ asset('admin-assets/images/fit-coach-club-logo.svg') }}";
</script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/franchises/franchises.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // 1. Upload Trigger
        $('#btnUploadImage').on('click', function() {
            $('#franchiseImageInput').trigger('click');
        });

        // 2. File Change Handler
        $('#franchiseImageInput').on('change', function(e) {
            var file = this.files && this.files[0];
            if (!file) return;

            // Check size (2MB)
            if (file.size > maxImageSize) {
                alert('File size exceeds 2 MB. Please select a smaller image.');
                $(this).val('');
                return;
            }

            // Check type
            var validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!validTypes.includes(file.type.toLowerCase())) {
                alert('Invalid file format. Please upload JPG, PNG, or WebP.');
                $(this).val('');
                return;
            }

            var reader = new FileReader();
            reader.onload = function(evt) {
                $('#franchiseLogoPreview').attr('src', evt.target.result);
                $('#image_name').val(file.name);
                $('#btnRemoveImage').show();
                $('#btnDeleteLogo').show();
                $('#image-error-container').empty();
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            };
            reader.readAsDataURL(file);
        });

        // 3. Remove / Delete Handler
        $('#btnRemoveImage, #btnDeleteLogo').on('click', function() {
            $('#franchiseImageInput').val('');
            $('#image_name').val('');
            $('#franchiseLogoPreview').attr('src', defaultLogoUrl);
            $('#btnRemoveImage').hide();
            $('#btnDeleteLogo').hide();
        });

        // 4. Password Visibility Toggle
        $('.fcc-password-toggle').on('click', function(e) {
            e.preventDefault();
            var targetSelector = $(this).data('target');
            var $target = $(targetSelector);
            var isPassword = $target.attr('type') === 'password';

            $target.attr('type', isPassword ? 'text' : 'password');
            $(this).html(isPassword ? '<i data-feather="eye-off"></i>' : '<i data-feather="eye"></i>');
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });

        // 5. Custom image validation trigger on submit
        $('#createFranchiseForm').on('submit', function(e) {
            var hasImage = $('#franchiseImageInput').val() || $('#image_name').val();
            if (!hasImage) {
                $('#image-error-container').html('<span class="invalid-feedback d-block">Franchise logo / photo is required.</span>');
            } else {
                $('#image-error-container').empty();
            }
        });
    });
</script>
@endpush
