@extends('admin-panel.layouts.main-layout')

@section('page-title', 'Create Franchise | '.__('language.page_main_title').'')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        border-top-left-radius: 15px !important;
        border-top-right-radius: 15px !important;
    }

    /* Natural descending stacking order */
    form .fcc-card:nth-of-type(1),
    .fcc-create-container .fcc-card:nth-of-type(1) { z-index: 40; }
    form .fcc-card:nth-of-type(2),
    .fcc-create-container .fcc-card:nth-of-type(2) { z-index: 30; }
    form .fcc-card:nth-of-type(3),
    .fcc-create-container .fcc-card:nth-of-type(3) { z-index: 20; }
    form .fcc-card:nth-of-type(4),
    .fcc-create-container .fcc-card:nth-of-type(4) { z-index: 10; }

    .fcc-card:focus-within,
    .fcc-card.has-dropdown-open,
    .fcc-card:has(.show),
    .fcc-card:has(.open) {
        z-index: 1200 !important;
    }

    .fcc-card-body,
    .fcc-create-container .fcc-form-grid,
    .fcc-create-container .fcc-form-field,
    .fcc-create-container .fcc-input-wrap,
    .fcc-create-container .fcc-select-wrap {
        overflow: visible !important;
    }

    /* Logo Card */
    .fcc-logo-card-inner {
        padding: 24px 28px;
        display: flex;
        align-items: center;
        gap: 28px;
        flex-wrap: wrap;
    }

    .fcc-logo-preview {
        width: 130px;
        height: 130px;
        min-width: 130px;
        border-radius: 18px;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        position: relative;
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.02);
        overflow: hidden;
    }

    .fcc-logo-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .fcc-logo-info {
        flex: 1 1 320px;
    }

    .fcc-logo-title {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: var(--fcc-dark);
        margin-bottom: 4px;
    }

    .fcc-logo-desc {
        font-size: 13px;
        color: var(--fcc-muted);
        margin-bottom: 12px;
        line-height: 1.45;
    }

    .fcc-btn-upload {
        background: var(--fcc-primary);
        border: none;
        border-radius: 8px;
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        padding: 8px 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(29, 78, 216, 0.25);
    }

    .fcc-btn-upload:hover {
        background: var(--fcc-primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(29, 78, 216, 0.35);
    }

    .fcc-meta-text {
        font-size: 12px;
        color: var(--fcc-muted);
        margin-top: 12px;
        font-weight: 500;
    }

    .fcc-meta-sep {
        color: #cbd5e1;
        margin: 0 6px;
    }

    /* Section Header Strip */
    .fcc-card-header-bar {
        background: var(--fcc-banner-bg);
        border-bottom: 1px solid var(--fcc-banner-border);
        padding: 12px 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fcc-card-header-bar span {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: var(--fcc-banner-text);
        letter-spacing: -0.01em;
    }

    .fcc-card-body {
        padding: 24px 28px;
    }

    /* 3. Dedicated 2-Column CSS Grid (Not dependent on Bootstrap/Basscss column classes) */
    .fcc-create-container .fcc-form-grid {
        display: grid !important;
        grid-template-columns: calc(50% - 13px) calc(50% - 13px) !important;
        column-gap: 26px !important;
        row-gap: 22px !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .fcc-create-container .fcc-form-field {
        width: 100% !important;
        margin: 0 !important;
        display: flex !important;
        flex-direction: column !important;
    }

    @media (max-width: 991px) {
        .fcc-create-container .fcc-form-grid {
            grid-template-columns: 1fr !important;
            row-gap: 18px !important;
        }
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

    /* Inputs Wrapper */
    .fcc-create-container .fcc-input-wrap {
        position: relative !important;
        display: block !important;
        width: 100% !important;
    }

    /* Absolute Left Icon Placement */
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

    /* Input Field Polish: Forced 48px padding-left so placeholder/text never overlaps icon */
    .fcc-create-container .fcc-input-wrap input.fcc-form-control,
    .fcc-create-container .fcc-input-wrap input.form-control,
    .fcc-create-container .fcc-input-wrap input {
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

    .fcc-create-container .fcc-input-wrap input.fcc-form-control::placeholder,
    .fcc-create-container .fcc-input-wrap input.form-control::placeholder {
        color: #94a3b8 !important;
        font-weight: 400 !important;
    }

    .fcc-create-container .fcc-input-wrap input.fcc-form-control:focus,
    .fcc-create-container .fcc-input-wrap input.form-control:focus {
        border-color: #1d4ed8 !important;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12) !important;
        outline: none !important;
    }

    .fcc-hint-text {
        font-size: 11.5px;
        color: var(--fcc-muted);
        margin-top: 6px;
        display: block;
        font-weight: 500;
    }

    /* Password Input Eye Toggle */
    .fcc-create-container .fcc-input-wrap input[type="password"],
    .fcc-create-container .fcc-input-wrap input[name="new_pass"],
    .fcc-create-container .fcc-input-wrap input[name="confirm_pass"] {
        padding-right: 46px !important;
    }

    .fcc-create-container .fcc-password-eye-btn {
        position: absolute !important;
        right: 14px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        background: transparent !important;
        border: none !important;
        color: #94a3b8 !important;
        cursor: pointer !important;
        padding: 4px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        z-index: 5 !important;
        transition: color 0.15s ease !important;
    }

    .fcc-create-container .fcc-password-eye-btn:hover {
        color: #0f172a !important;
    }

    /* Bootstrap Select Dropdown Polish */
    .fcc-create-container .fcc-select-wrap .bootstrap-select {
        width: 100% !important;
        margin: 0 !important;
    }

    .fcc-create-container .fcc-select-wrap .bootstrap-select > .btn,
    .fcc-create-container .fcc-select-wrap .bootstrap-select > button.dropdown-toggle {
        height: 48px !important;
        line-height: normal !important;
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #ffffff !important;
        padding-left: 48px !important;
        padding-right: 36px !important;
        font-size: 13.5px !important;
        color: #0f172a !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        box-shadow: none !important;
        width: 100% !important;
    }

    .fcc-create-container .fcc-select-wrap .bootstrap-select > button.dropdown-toggle:focus {
        border-color: #1d4ed8 !important;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12) !important;
        outline: none !important;
    }

    .fcc-create-container .fcc-select-wrap .bootstrap-select .filter-option-inner-inner {
        color: #94a3b8 !important;
        font-size: 13.5px !important;
        font-weight: 400 !important;
    }

    .fcc-create-container .fcc-select-wrap .bootstrap-select .bs-caret {
        display: none !important;
    }

    .fcc-create-container .fcc-select-wrap .bootstrap-select > button.dropdown-toggle::after {
        content: "" !important;
        display: inline-block !important;
        margin-left: auto !important;
        border: none !important;
        width: 16px !important;
        height: 16px !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: center !important;
        background-size: contain !important;
        vertical-align: middle !important;
        flex-shrink: 0 !important;
    }

    /* Validation Feedback */
    .fcc-create-container .invalid-feedback {
        font-size: 12px !important;
        color: #ef4444 !important;
        margin-top: 5px !important;
        display: block !important;
        font-weight: 600 !important;
    }

    .fcc-create-container .has-danger input.fcc-form-control,
    .fcc-create-container input.is-invalid {
        border-color: #ef4444 !important;
    }

    /* 4. Footer Actions */
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

    @media (max-width: 768px) {
        .fcc-card-body,
        .fcc-logo-card-inner {
            padding: 18px 16px;
        }
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
                    <path d="M3 9l1-6h16l1 6"></path>
                    <path d="M3 9a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0"></path>
                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7"></path>
                </svg>
            </div>
            <div>
                <h1 class="fcc-header-title">Create Franchise</h1>
                <p class="fcc-header-subtitle">Register a new franchise partner in the FitCoachClub platform.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('adminPanel.franchises.index') }}" class="fcc-btn-back">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Franchises
            </a>
        </div>
    </div>

    <!-- Error Alerts Component -->
    @component('admin-panel.validation.errors') @endcomponent

    {!! Form::open(['class' => 'franchise-form', 'method' => 'post', 'url' => route('adminPanel.franchises.store'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'id' => 'createFranchiseForm' ]) !!}

        <!-- 2. Section 1: Franchise Logo / Photo Card -->
        <div class="fcc-card">
            <div class="fcc-logo-card-inner">
                <div class="fcc-logo-preview">
                    <img id="franchiseLogoPreview" src="{{ asset('admin-assets/images/fit-coach-club-preview.svg') }}" alt="Fit Coach Club" />
                </div>
                <div class="fcc-logo-info">
                    <div class="fcc-logo-title">
                        Franchise Logo / Photo <span class="text-danger">*</span>
                    </div>
                    <p class="fcc-logo-desc">
                        Upload your franchise or brand logo. It will be used across the franchise portal.
                    </p>

                    <!-- Hidden File Input -->
                    <input type="file" name="image" id="franchiseImageInput" accept="image/png, image/jpeg, image/jpg, image/webp" class="d-none">
                    <input type="hidden" name="image_name" id="image_name" value="">

                    <div>
                        <button type="button" class="fcc-btn-upload" id="btnUploadImage">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            Upload Image
                        </button>
                    </div>

                    <div class="fcc-meta-text">
                        Supported formats: JPG, PNG, WebP <span class="fcc-meta-sep">|</span> Max size: 2 MB <span class="fcc-meta-sep">|</span> Recommended: Square (512 x 512 px)
                    </div>
                    <div id="image-error-container"></div>
                </div>
            </div>
        </div>

        <!-- 3. Section 2: General Information Card -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span>General Information</span>
            </div>

            <div class="fcc-card-body">
                <div class="fcc-form-grid">
                    <!-- Franchise / Coach Name -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="name">Franchise / Coach Name <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrap fcc-input-wrapper">
                            <span class="fcc-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l1-6h16l1 6"></path>
                                    <path d="M3 9a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0"></path>
                                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7"></path>
                                </svg>
                            </span>
                            <input type="text" name="name" id="name" class="form-control fcc-form-control" placeholder="Enter franchise or coach name" required value="{{ old('name') }}">
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="email">Email Address <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrap fcc-input-wrapper">
                            <span class="fcc-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </span>
                            <input type="email" name="email" id="email" class="form-control fcc-form-control" placeholder="coach@fitcoachclub.com" autocomplete="off" data-url="{{ route('adminPanel.franchises.checkEmail') }}" required value="{{ old('email') }}">
                        </div>
                        <span class="fcc-hint-text">Used as login username for the coach portal.</span>
                    </div>

                    <!-- Mobile Number -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrap fcc-input-wrapper">
                            <span class="fcc-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                            </span>
                            <input type="text" name="mobile_number" id="mobile_number" class="form-control fcc-form-control numeric" placeholder="Enter 10-digit mobile number" data-url="{{ route('adminPanel.franchises.checkMobile') }}" required value="{{ old('mobile_number') }}">
                        </div>
                    </div>

                    <!-- Select Allowed Products -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="products">Select Allowed Products</label>
                        <div class="fcc-input-wrap fcc-input-wrapper fcc-select-wrap">
                            <span class="fcc-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                    <polyline points="2 17 12 22 22 17"></polyline>
                                    <polyline points="2 12 12 17 22 12"></polyline>
                                </svg>
                            </span>
                            <select name="products[]" id="products" class="form-control fcc-form-control select-picker" multiple data-live-search="true" title="Choose products this franchise can distribute...">
                                @foreach($products as $key => $value)
                                    <option value="{{$value['id']}}">{{ $value['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="fcc-hint-text">Assign specific core products and sale to this franchise.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Section 3: Portal Security Credentials Card -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <span>Portal Security Credentials</span>
            </div>

            <div class="fcc-card-body">
                <div class="fcc-form-grid">
                    <!-- New Password -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="new_pass">New Password <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrap fcc-input-wrapper">
                            <span class="fcc-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </span>
                            <input type="password" name="new_pass" id="new_pass" class="form-control fcc-form-control" placeholder="Enter secure password" autocomplete="new-password" required>
                            <button type="button" class="fcc-password-eye-btn" data-target="#new_pass" tabindex="-1" title="Toggle password visibility">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                        <span class="fcc-hint-text">Minimum 6 characters recommended.</span>
                    </div>

                    <!-- Confirm Password -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="confirm_pass">Confirm Password <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrap fcc-input-wrapper">
                            <span class="fcc-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </span>
                            <input type="password" name="confirm_pass" id="confirm_pass" class="form-control fcc-form-control" placeholder="Re-enter password" required>
                            <button type="button" class="fcc-password-eye-btn" data-target="#confirm_pass" tabindex="-1" title="Toggle password visibility">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                        <span class="fcc-hint-text">Must match password above.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Footer Actions -->
        <div class="fcc-footer-actions">
            <a href="{{ route('adminPanel.franchises.index') }}" class="fcc-btn-cancel">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                Cancel
            </a>
            <button type="submit" class="fcc-btn-save btn-submit">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Save Franchise
            </button>
        </div>

    {!! Form::close() !!}
</div>
@endsection

@push('scripts')
<script>
    var maxImageSize = {{ config('constants.max_image_size') ?? 2097152 }};
    var defaultLogoUrl = "{{ asset('admin-assets/images/fit-coach-club-preview.svg') }}";
</script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/franchises/franchises.js') }}"></script>
<script>
    $(document).ready(function() {
        // Upload Trigger
        $('#btnUploadImage').on('click', function() {
            $('#franchiseImageInput').trigger('click');
        });

        // File Change Handler
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
                $('#image-error-container').empty();
            };
            reader.readAsDataURL(file);
        });

        // Password Visibility Toggle
        $('.fcc-password-eye-btn').on('click', function(e) {
            e.preventDefault();
            var targetSelector = $(this).data('target');
            var $target = $(targetSelector);
            var isPassword = $target.attr('type') === 'password';

            $target.attr('type', isPassword ? 'text' : 'password');
            if (isPassword) {
                $(this).html('<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>');
            } else {
                $(this).html('<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>');
            }
        });

        // Custom image validation trigger on submit
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
