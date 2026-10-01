@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Edit Custom Dish | ' . __('language.page_main_title'))

@push('styles')
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">

<style>
    /* Hide default sub-header breadcrumb to prevent duplicate breadcrumbs */
    .sub-header-container.custom-breadcrumbs {
        display: none !important;
    }

    /* Page container */
    .custom-dish-page {
        padding: 4px 8px 36px 8px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Breadcrumbs */
    .dish-nav-breadcrumb {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .dish-nav-breadcrumb a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .dish-nav-breadcrumb a:hover {
        color: #2563eb;
    }

    .dish-nav-breadcrumb .crumb-sep {
        color: #94a3b8;
    }

    .dish-nav-breadcrumb .crumb-active {
        color: #0f172a;
        font-weight: 600;
    }

    /* Top Page Header */
    .dish-top-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }

    .dish-page-title {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .dish-page-subtitle {
        font-size: 13.5px;
        color: #64748b;
        margin-bottom: 0;
        font-weight: 400;
    }

    .dish-header-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Buttons */
    .btn-action-cancel {
        background: #ffffff;
        color: #475569;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 8px 20px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        text-decoration: none !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        cursor: pointer;
    }

    .btn-action-cancel:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .btn-action-draft {
        background: #ffffff;
        color: #2563eb;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 8px 20px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        cursor: pointer;
    }

    .btn-action-draft:hover {
        background: #eff6ff;
        border-color: #2563eb;
        color: #1d4ed8;
    }

    .btn-action-publish {
        background: #2563eb;
        color: #ffffff !important;
        border: 1.5px solid #2563eb;
        border-radius: 10px;
        padding: 8px 22px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 3px 10px rgba(37, 99, 235, 0.28);
        cursor: pointer;
    }

    .btn-action-publish:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(37, 99, 235, 0.38);
    }

    /* Stepper Card */
    .dish-stepper-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .stepper-steps-wrapper {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .stepper-step {
        display: flex;
        align-items: center;
        gap: 9px;
        text-decoration: none !important;
        cursor: pointer;
    }

    .step-number-circle {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12.5px;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .stepper-step.active .step-number-circle {
        background: #2563eb;
        color: #ffffff;
    }

    .stepper-step.inactive .step-number-circle {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }

    .step-icon-svg {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .stepper-step.active .step-icon-svg {
        color: #2563eb;
    }

    .stepper-step.inactive .step-icon-svg {
        color: #64748b;
    }

    .step-label {
        font-size: 13.5px;
        transition: all 0.2s ease;
    }

    .stepper-step.active .step-label {
        font-weight: 700;
        color: #0f172a;
    }

    .stepper-step.inactive .step-label {
        font-weight: 500;
        color: #64748b;
    }

    .stepper-divider-line {
        width: 48px;
        height: 1.5px;
        background: #cbd5e1;
    }

    .stepper-info-note {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        padding-left: 20px;
        border-left: 1px solid #e2e8f0;
    }

    /* Modern Cards */
    .dish-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 24px;
    }

    .dish-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .dish-section-title-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dish-accent-bar {
        width: 4px;
        height: 18px;
        background: #2563eb;
        border-radius: 4px;
        display: inline-block;
    }

    .dish-section-title {
        font-size: 17px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.01em;
    }

    .dish-section-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-top: 3px;
        margin-bottom: 22px;
        font-weight: 400;
    }

    /* Form Fields */
    .dish-form-label {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 7px;
        display: block;
    }

    .dish-input {
        height: 44px;
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        font-size: 13.5px !important;
        color: #0f172a !important;
        padding: 9px 14px !important;
        background-color: #ffffff !important;
        transition: all 0.2s ease;
        box-shadow: none !important;
    }

    .dish-input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    .dish-input::placeholder {
        color: #94a3b8 !important;
        font-size: 13.5px;
    }

    .dish-input-hint {
        font-size: 12px;
        color: #64748b;
        margin-top: 6px;
        display: block;
    }

    /* Dropify Modern Restyling */
    .dish-dropzone-container .dropify-wrapper {
        border: 1.5px dashed #cbd5e1 !important;
        border-radius: 14px !important;
        background-color: #fafbfc !important;
        height: 195px !important;
        transition: all 0.2s ease;
        padding: 10px !important;
        width: 100% !important;
        margin-bottom: 0 !important;
    }

    .dish-dropzone-container .dropify-wrapper:hover {
        border-color: #2563eb !important;
        background-color: #eff6ff !important;
        background-image: none !important;
    }

    .dish-dropzone-container .dropify-wrapper .dropify-message {
        position: relative !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
    }

    .dish-dropzone-container .dropify-wrapper .dropify-message span.file-icon {
        display: inline-block !important;
        width: 44px !important;
        height: 44px !important;
        margin-bottom: 10px !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%233b82f6' stroke-width='1.75' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z'/%3E%3Cpath d='M12 12v6'/%3E%3Cpath d='m15 15-3-3-3 3'/%3E%3C/svg%3E") !important;
        background-size: contain !important;
        background-repeat: no-repeat !important;
        background-position: center !important;
    }

    .dish-dropzone-container .dropify-wrapper .dropify-message span.file-icon:before {
        display: none !important;
    }

    .dish-dropzone-container .dropify-wrapper .dropify-message p {
        margin: 0 !important;
        color: #0f172a !important;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        font-family: inherit !important;
        letter-spacing: -0.01em;
    }

    .dish-dropzone-container .dropify-wrapper .dropify-message p::after {
        content: "or browse files";
        display: block;
        color: #2563eb;
        font-size: 13px;
        font-weight: 600;
        text-decoration: underline;
        margin-top: 4px;
        cursor: pointer;
    }

    .dish-dropzone-hint {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 8px;
        text-align: left;
    }

    /* Bootstrap Select Restyling */
    .bootstrap-select > .btn.dropdown-toggle {
        height: 44px !important;
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        background-color: #ffffff !important;
        color: #0f172a !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        padding: 9px 14px !important;
        box-shadow: none !important;
        transition: all 0.2s ease;
    }

    .bootstrap-select > .btn.dropdown-toggle:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    /* Summernote Editor Restyling */
    .note-editor.note-frame {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 12px !important;
        box-shadow: none !important;
        overflow: hidden;
    }

    .note-toolbar {
        background-color: #f8fafc !important;
        border-bottom: 1.5px solid #e2e8f0 !important;
        padding: 8px 12px !important;
    }

    .note-editor .btn-default,
    .note-editor .btn-light {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        color: #475569 !important;
        font-size: 12.5px !important;
        border-radius: 6px !important;
        margin: 1px 2px !important;
        padding: 4px 8px !important;
    }

    .note-editor .btn-default:hover,
    .note-editor .btn-light:hover {
        background: #f1f5f9 !important;
        color: #0f172a !important;
    }

    .note-editable {
        min-height: 190px !important;
        font-size: 14px !important;
        color: #1e293b !important;
        padding: 14px 16px !important;
        font-family: inherit !important;
        background: #ffffff !important;
    }

    .editor-footer-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
        font-size: 12.5px;
        color: #64748b;
        font-weight: 500;
    }

    /* Member app visibility Card */
    .visibility-toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 4px 0 16px 0;
    }

    .visibility-text-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .visibility-text-desc {
        font-size: 12.5px;
        color: #64748b;
        margin: 0;
    }

    /* iOS Modern Switch */
    .switch-toggle-custom {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 26px;
        flex-shrink: 0;
        margin: 0;
        cursor: pointer;
    }

    .switch-toggle-custom input {
        opacity: 0;
        width: 0;
        height: 0;
        position: absolute;
    }

    .switch-slider-round {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.25s ease;
        border-radius: 9999px;
    }

    .switch-slider-round:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.25s ease;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    .switch-toggle-custom input:checked + .switch-slider-round {
        background-color: #2563eb;
    }

    .switch-toggle-custom input:checked + .switch-slider-round:before {
        transform: translateX(22px);
    }

    /* Status Pill Badges */
    .status-pill-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .status-pill-badge.status-draft {
        background: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
    }

    .status-pill-badge.status-published {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    .badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-draft .badge-dot {
        background: #d97706;
    }

    .status-published .badge-dot {
        background: #10b981;
    }

    .card-meta-divider {
        border: 0;
        border-top: 1px solid #f1f5f9;
        margin: 0 0 16px 0;
    }

    .summary-meta-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 13px;
    }

    .summary-meta-row:last-child {
        margin-bottom: 0;
    }

    .summary-label {
        color: #64748b;
        font-weight: 500;
    }

    .summary-val {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
    }

    /* App Preview Card */
    .preview-live-badge {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 11.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .app-preview-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .app-preview-thumb {
        width: 78px;
        height: 78px;
        min-width: 78px;
        border-radius: 12px;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .app-preview-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .app-preview-content {
        flex: 1;
        overflow: hidden;
    }

    .app-preview-name {
        font-size: 14.5px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 3px 0;
        line-height: 1.25;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .app-preview-type {
        font-size: 12px;
        color: #64748b;
        margin: 0 0 10px 0;
        font-weight: 500;
    }

    .app-skeleton-line {
        background: #e2e8f0;
        height: 6px;
        border-radius: 9999px;
    }

    .app-skeleton-line.line-long {
        width: 85%;
        margin-bottom: 5px;
    }

    .app-skeleton-line.line-short {
        width: 55%;
    }

    /* Bottom Action Bar */
    .dish-bottom-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-top: 8px;
    }

    .bottom-bar-left {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #475569;
        font-size: 13.5px;
        font-weight: 500;
    }

    .bottom-bar-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Validation error messages */
    .invalid-feedback {
        font-size: 12px;
        color: #ef4444;
        margin-top: 4px;
        display: block;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="custom-dish-page">

    @php
        $imagePath = (get_image_url(config('constants.custom-dishes.image_path'), $customDish->image) ?? '');
        $currentStatus = old('status', $customDish->status ?? 0);
    @endphp

    <!-- Top Breadcrumb -->
    <div class="dish-nav-breadcrumb">
        <span>Meals & Nutrition</span>
        <span class="crumb-sep">/</span>
        <a href="{{ route('nutritionPanel.custom-dishes.index') }}">Custom Dishes</a>
        <span class="crumb-sep">/</span>
        <span class="crumb-active">Edit</span>
    </div>

    <!-- Header Section -->
    <div class="dish-top-header">
        <div>
            <h1 class="dish-page-title">Edit custom dish</h1>
            <p class="dish-page-subtitle">Update dish details, category and recipe for members.</p>
        </div>

        <div class="dish-header-buttons">
            <a href="{{ route('nutritionPanel.custom-dishes.index') }}" class="btn-action-cancel">Cancel</a>
            <button type="button" class="btn-action-draft btn-trigger-draft">Save draft</button>
            <button type="button" class="btn-action-publish btn-trigger-publish">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
                <span>Publish dish</span>
            </button>
        </div>
    </div>

    <!-- Stepper Card -->
    <div class="dish-stepper-card">
        <div class="stepper-steps-wrapper">
            <!-- Step 1: Dish details -->
            <a href="#dishDetailsCard" class="stepper-step active" id="stepLink1">
                <div class="step-number-circle">1</div>
                <div class="step-icon-svg">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <span class="step-label">Dish details</span>
            </a>

            <div class="stepper-divider-line d-none d-sm-block"></div>

            <!-- Step 2: Recipe -->
            <a href="#recipeCard" class="stepper-step inactive" id="stepLink2">
                <div class="step-number-circle">2</div>
                <div class="step-icon-svg">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                        <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                        <line x1="6" y1="1" x2="6" y2="4"></line>
                        <line x1="10" y1="1" x2="10" y2="4"></line>
                        <line x1="14" y1="1" x2="14" y2="4"></line>
                    </svg>
                </div>
                <span class="step-label">Recipe</span>
            </a>

            <div class="stepper-divider-line d-none d-sm-block"></div>

            <!-- Step 3: Publish & preview -->
            <a href="#visibilityCard" class="stepper-step inactive" id="stepLink3">
                <div class="step-number-circle">3</div>
                <div class="step-icon-svg">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </div>
                <span class="step-label">Publish & preview</span>
            </a>
        </div>

        <div class="stepper-info-note d-none d-lg-block">
            Drafts stay private until published.
        </div>
    </div>

    <!-- Validation errors if any -->
    @component('nutrition-panel.validation.errors') @endcomponent

    <!-- Form Section -->
    {!! Form::open(['class' => 'custom-dish-form', 'id' => 'customDishForm', 'method' => 'post', 'url' => route('nutritionPanel.custom-dishes.update', ['id' => ev($customDish->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off']) !!}

        <!-- Hidden inputs for status & validation support -->
        <input type="hidden" name="status" id="dishStatusInput" value="{{ $currentStatus }}">
        {!! Form::hidden('image_name', old('image_name', ($customDish->image ?? null)), ['class' => 'form-control', 'id' => 'image_name']) !!}

        <div class="row">
            <!-- Left Column: Dish details & Recipe (8 cols) -->
            <div class="col-xl-8 col-lg-8 col-12">

                <!-- Card 1: Dish details -->
                <div class="dish-card" id="dishDetailsCard">
                    <div class="dish-card-header">
                        <div class="dish-section-title-wrap">
                            <span class="dish-accent-bar"></span>
                            <h2 class="dish-section-title">Dish details</h2>
                        </div>
                    </div>
                    <p class="dish-section-subtitle">Add the basic information members will see in the app.</p>

                    <div class="row">
                        <!-- Left sub-col: Image Upload (5 cols) -->
                        <div class="col-md-5 mb-4 mb-md-0">
                            <label class="dish-form-label" for="image">
                                Dish image <span class="text-danger">*</span>
                            </label>

                            <div class="dish-dropzone-container">
                                {!! Form::file('image', [
                                    'class' => 'image-preview',
                                    'id' => 'image',
                                    'autocomplete' => 'off',
                                    'data-show-remove' => 'true',
                                    'accept' => 'image/*',
                                    'data-default-file' => $imagePath,
                                ]) !!}
                            </div>
                            <div class="dish-dropzone-hint">
                                PNG or JPG · Recommended 1200 × 800 px · Max 5 MB
                            </div>
                        </div>

                        <!-- Right sub-col: Name, Dish Type, Order (7 cols) -->
                        <div class="col-md-7">
                            <!-- Dish Name -->
                            <div class="form-group mb-3">
                                <label class="dish-form-label" for="name">
                                    Dish name <span class="text-danger">*</span>
                                </label>
                                {!! Form::text('name', old('name', $customDish->name), [
                                    'class' => 'form-control dish-input',
                                    'id' => 'name',
                                    'placeholder' => 'Enter dish name',
                                ]) !!}
                            </div>

                            <!-- Dish Type -->
                            <div class="form-group mb-3">
                                <label class="dish-form-label" for="dish_type_id">
                                    Dish type <span class="text-danger">*</span>
                                </label>
                                {!! Form::select('dish_type_id', create_select_options($dishTypes, 'name', 'id', 'Select dish type'), old('dish_type_id', $customDish->dish_type_id), [
                                    'class' => 'form-control select-picker dish-input',
                                    'id' => 'dish_type_id',
                                ]) !!}
                            </div>

                            <!-- Display Order -->
                            <div class="form-group mb-0">
                                <label class="dish-form-label" for="order">
                                    Display order <span class="text-danger">*</span>
                                </label>
                                {!! Form::text('order', old('order', $customDish->order), [
                                    'class' => 'form-control numeric dish-input',
                                    'id' => 'order',
                                    'placeholder' => '0',
                                ]) !!}
                                <span class="dish-input-hint">Lower numbers appear first in the app.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Recipe & instructions -->
                <div class="dish-card" id="recipeCard">
                    <div class="dish-card-header">
                        <div class="dish-section-title-wrap">
                            <span class="dish-accent-bar"></span>
                            <h2 class="dish-section-title">Recipe & instructions</h2>
                        </div>
                    </div>
                    <p class="dish-section-subtitle">Write ingredients, preparation steps and serving guidance.</p>

                    <div class="form-group mb-0">
                        <label class="dish-form-label" for="description">
                            Description (Recipe) <span class="text-danger">*</span>
                        </label>
                        {!! Form::textarea('description', old('description', $customDish->description), [
                            'class' => 'form-control editor-textarea',
                            'id' => 'description',
                            'placeholder' => 'Write the recipe, ingredients and preparation steps...',
                            'rows' => 6,
                        ]) !!}

                        <div class="editor-footer-bar">
                            <span id="recipeWordCount">0 words</span>
                            <span>Formatting supported</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Member app visibility & App preview (4 cols) -->
            <div class="col-xl-4 col-lg-4 col-12">

                <!-- Card 1: Member app visibility -->
                <div class="dish-card" id="visibilityCard">
                    <div class="dish-card-header">
                        <div class="dish-section-title-wrap">
                            <span class="dish-accent-bar"></span>
                            <h2 class="dish-section-title">Member app visibility</h2>
                        </div>

                        <span class="status-pill-badge {{ $currentStatus == 1 ? 'status-published' : 'status-draft' }}" id="statusBadge">
                            <span class="badge-dot"></span>
                            <span id="statusBadgeText">{{ $currentStatus == 1 ? 'Published' : 'Draft' }}</span>
                        </span>
                    </div>

                    <div class="visibility-toggle-row">
                        <div>
                            <div class="visibility-text-title">Visible in member app</div>
                            <div class="visibility-text-desc">Turn this on when the dish is ready to publish.</div>
                        </div>

                        <label class="switch-toggle-custom" for="visibilityToggle">
                            <input type="checkbox" id="visibilityToggle" {{ $currentStatus == 1 ? 'checked' : '' }}>
                            <span class="switch-slider-round"></span>
                        </label>
                    </div>

                    <hr class="card-meta-divider">

                    <div class="summary-meta-row">
                        <span class="summary-label">Selected dish type</span>
                        <span class="summary-val" id="summaryDishType">Not selected</span>
                    </div>

                    <div class="summary-meta-row">
                        <span class="summary-label">Display order</span>
                        <span class="summary-val" id="summaryOrder">{{ old('order', $customDish->order) }}</span>
                    </div>
                </div>

                <!-- Card 2: App preview -->
                <div class="dish-card" id="appPreviewCard">
                    <div class="dish-card-header">
                        <div class="dish-section-title-wrap">
                            <span class="dish-accent-bar"></span>
                            <h2 class="dish-section-title">App preview</h2>
                        </div>

                        <span class="preview-live-badge">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <span>Preview updates as you type</span>
                        </span>
                    </div>

                    <div class="app-preview-box mt-3">
                        <div class="app-preview-thumb">
                            <div id="mockupPlaceholderIcon" style="{{ !empty($imagePath) ? 'display: none;' : '' }}">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                            </div>
                            <img src="{{ $imagePath }}" id="mockupLiveImg" style="{{ !empty($imagePath) ? '' : 'display: none;' }}" alt="Dish preview">
                        </div>

                        <div class="app-preview-content">
                            <h4 class="app-preview-name" id="previewDishName">{{ old('name', $customDish->name) ?: 'Dish name' }}</h4>
                            <p class="app-preview-type" id="previewDishType">Dish type</p>
                            <div class="app-skeleton-line line-long"></div>
                            <div class="app-skeleton-line line-short"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Hidden submit button for jQuery validate & App.formLoading triggers -->
        <button type="submit" id="realSubmitBtn" class="btn-submit d-none"></button>

        <!-- Bottom Action Bar -->
        <div class="dish-bottom-bar">
            <div class="bottom-bar-left">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span>Complete the required fields before publishing.</span>
            </div>

            <div class="bottom-bar-right">
                <button type="button" class="btn-action-draft btn-trigger-draft">Save draft</button>
                <button type="button" class="btn-action-publish btn-trigger-publish">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                    <span>Publish dish</span>
                </button>
            </div>
        </div>

    {!! Form::close() !!}

</div>
@endsection

@push('scripts')
<script>
    var maxImageSize = {{ config('constants.max_image_size') ?? 5242880 }};
</script>
<script src="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/flatpickr.js') }}"></script>
<script src="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/custom-dishes/custom-dishes.js') }}"></script>

<script>
$(document).ready(function() {
    // Status Switch & Badge synchronization
    function updateStatusUI(statusVal) {
        var isPublished = (parseInt(statusVal) === 1);
        $('#dishStatusInput').val(isPublished ? 1 : 0);
        $('#visibilityToggle').prop('checked', isPublished);

        if (isPublished) {
            $('#statusBadge').removeClass('status-draft').addClass('status-published');
            $('#statusBadgeText').text('Published');
        } else {
            $('#statusBadge').removeClass('status-published').addClass('status-draft');
            $('#statusBadgeText').text('Draft');
        }
    }

    // Toggle switch change handler
    $('#visibilityToggle').on('change', function() {
        updateStatusUI($(this).is(':checked') ? 1 : 0);
    });

    // Save Draft trigger
    $(document).on('click', '.btn-trigger-draft', function(e) {
        e.preventDefault();
        updateStatusUI(0);
        $('#realSubmitBtn').trigger('click');
    });

    // Publish Dish trigger
    $(document).on('click', '.btn-trigger-publish', function(e) {
        e.preventDefault();
        updateStatusUI(1);
        $('#realSubmitBtn').trigger('click');
    });

    // Dish Name live preview
    $('#name').on('input', function() {
        var val = $(this).val().trim();
        $('#previewDishName').text(val.length > 0 ? val : 'Dish name');
    });

    // Dish Type live preview & summary
    function syncDishType() {
        var $select = $('#dish_type_id');
        var val = $select.val();
        var text = $select.find('option:selected').text();
        if (val && val !== '') {
            $('#previewDishType').text(text);
            $('#summaryDishType').text(text);
        } else {
            $('#previewDishType').text('Dish type');
            $('#summaryDishType').text('Not selected');
        }
    }

    $('#dish_type_id').on('change', function() {
        syncDishType();
    });

    // Order live summary
    $('#order').on('input', function() {
        var val = $(this).val().trim();
        $('#summaryOrder').text(val.length > 0 ? val : '0');
    });

    // Dropify Image Live Preview
    $('#image').on('change', function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#mockupLiveImg').attr('src', e.target.result).show();
                $('#mockupPlaceholderIcon').hide();
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    $(document).on('click', '.dropify-clear', function() {
        $('#image_name').val('');
        $('#mockupLiveImg').attr('src', '').hide();
        $('#mockupPlaceholderIcon').show();
    });

    // Summernote Word Counter
    function updateWordCount() {
        var $desc = $('#description');
        if ($desc.length && $desc.data('summernote')) {
            var text = $desc.summernote('isEmpty') ? '' : $($desc.summernote('code')).text().trim();
            if (!text) {
                $('#recipeWordCount').text('0 words');
                return;
            }
            var words = text.replace(/\s+/g, ' ').split(' ').filter(Boolean).length;
            $('#recipeWordCount').text(words + (words === 1 ? ' word' : ' words'));
        }
    }

    setTimeout(function() {
        $('#description').on('summernote.change', function() {
            updateWordCount();
        });
        $('#description').on('summernote.keyup', function() {
            updateWordCount();
        });
        updateWordCount();
    }, 500);

    // Initial sync
    syncDishType();
    if ($('#name').val().trim().length > 0) {
        $('#previewDishName').text($('#name').val().trim());
    }
});
</script>
@endpush