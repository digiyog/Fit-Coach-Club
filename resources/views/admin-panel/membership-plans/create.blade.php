@extends('admin-panel.layouts.main-layout')

@section('page-title', 'Create Membership Plan | '.__('language.page_main_title').'')

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
        padding: 24px 28px;
    }

    /* Dedicated 2-Column Form Grid */
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

    /* Input Field Polish */
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

    .fcc-create-container .fcc-input-wrap input:focus {
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
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
            </div>
            <div>
                <h1 class="fcc-header-title">Create Membership Plan</h1>
                <p class="fcc-header-subtitle">Define a new subscription package for clubs, coaches, and members.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('adminPanel.membership-plans.index') }}" class="fcc-btn-back">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Membership Plans
            </a>
        </div>
    </div>

    <!-- Error Alerts Component -->
    @component('admin-panel.validation.errors') @endcomponent

    {!! Form::open(['class' => 'membership-plan-form', 'method' => 'post', 'url' => route('adminPanel.membership-plans.store'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'id' => 'createMembershipPlanForm' ]) !!}

        <!-- 2. Section: Plan Information Card -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                <span>Plan Terms & Pricing</span>
            </div>
            <div class="fcc-card-body">
                <div class="fcc-form-grid">
                    <!-- Plan Name -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="name">Plan Name <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrap">
                            <span class="fcc-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                            </span>
                            {!! Form::text('name', '', ['class' => 'form-control fcc-form-control', 'id' => 'name', 'placeholder' => 'e.g. Monthly Gold Membership', 'required' => 'required' ]) !!}
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="price">Price (₹) <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrap">
                            <span class="fcc-input-icon">
                                <span style="font-weight: 800; font-size: 16px; color: #94a3b8;">₹</span>
                            </span>
                            {!! Form::text('price', '', ['class' => 'form-control fcc-form-control numeric', 'id' => 'price', 'placeholder' => 'e.g. 1500', 'required' => 'required' ]) !!}
                        </div>
                    </div>

                    <!-- Duration (Days) -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="days">Duration (Days) <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrap">
                            <span class="fcc-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            </span>
                            {!! Form::text('days', '', ['class' => 'form-control fcc-form-control numeric', 'id' => 'days', 'placeholder' => 'e.g. 30, 90, 365', 'required' => 'required' ]) !!}
                        </div>
                        <span class="fcc-hint-text">Total calendar validity days before renewal is required.</span>
                    </div>

                    <!-- Display Order -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="order">Display Order <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrap">
                            <span class="fcc-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                            </span>
                            {!! Form::text('order', 0, ['class' => 'form-control fcc-form-control numeric', 'id' => 'order', 'placeholder' => 'Display sequence (e.g. 1, 2)', 'required' => 'required' ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Form Footer Actions (Cancel / Save) -->
        <div class="fcc-footer-actions">
            <a href="{{ route('adminPanel.membership-plans.index') }}" class="fcc-btn-cancel">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                Cancel
            </a>
            <button type="submit" class="fcc-btn-save" id="btnSubmitMembershipPlan">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Save Membership Plan
            </button>
        </div>

    {!! Form::close() !!}

</div>
@endsection

@push('scripts')
<script>
    var maxImageSize = {{config('constants.max_image_size')}};
</script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/membership-plans/membership-plans.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush