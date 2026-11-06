@extends('admin-panel.layouts.main-layout')

@section('page-title', 'App Configuration | ' . __('language.page_main_title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --fcc-primary: #1d4ed8;
        --fcc-primary-hover: #1e40af;
        --fcc-dark: #0f172a;
        --fcc-slate: #1e293b;
        --fcc-muted: #64748b;
        --fcc-border: #e2e8f0;
        --fcc-card-bg: #ffffff;
        --fcc-banner-bg: #f8fafc;
        --fcc-banner-border: #e2e8f0;
        --fcc-banner-text: #0f172a;
        --fcc-success: #10b981;
    }

    .fcc-config-container {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--fcc-slate);
        padding: 12px 6px 60px 6px;
        width: 100%;
        max-width: 1380px;
        margin: 0 auto;
    }

    /* 1. Header Bar */
    .fcc-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .fcc-header-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .fcc-header-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #1d4ed8;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(29, 78, 216, 0.12);
    }

    .fcc-header-title {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        font-size: 24px;
        font-weight: 800;
        color: var(--fcc-dark);
        letter-spacing: -0.02em;
        margin: 0;
        line-height: 1.2;
    }

    .fcc-header-subtitle {
        font-size: 13.5px;
        color: var(--fcc-muted);
        margin: 4px 0 0 0;
        font-weight: 500;
    }

    /* 2. Structured Cards */
    .fcc-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .fcc-card-header-bar {
        background: var(--fcc-banner-bg);
        border-bottom: 1px solid var(--fcc-banner-border);
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .fcc-card-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fcc-card-header-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--fcc-dark);
    }

    .fcc-card-header-bar span.card-title {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        font-size: 16px;
        font-weight: 800;
        color: var(--fcc-banner-text);
        letter-spacing: -0.01em;
    }

    .fcc-card-body {
        padding: 26px 28px;
    }

    /* Form Fields */
    .fcc-form-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        column-gap: 24px;
        row-gap: 20px;
        width: 100%;
    }

    .fcc-form-field {
        display: flex;
        flex-direction: column;
        width: 100%;
        margin-bottom: 18px;
    }

    .fcc-form-field:last-child {
        margin-bottom: 0;
    }

    .fcc-label {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--fcc-dark);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .fcc-input, .fcc-textarea {
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        font-size: 14px;
        font-weight: 500;
        color: #0f172a;
        padding: 10px 16px;
        transition: all 0.2s ease;
        width: 100%;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .fcc-input {
        height: 48px;
    }

    .fcc-textarea {
        min-height: 96px;
        resize: vertical;
        line-height: 1.5;
    }

    .fcc-input:focus, .fcc-textarea:focus {
        border-color: #1d4ed8;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.15);
        outline: none;
    }

    .fcc-help-text {
        font-size: 12px;
        color: var(--fcc-muted);
        margin-top: 6px;
        line-height: 1.4;
    }

    /* Toggle Switch Component */
    .fcc-toggle-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 18px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        transition: border-color 0.2s ease;
    }

    .fcc-toggle-info {
        display: flex;
        flex-direction: column;
    }

    .fcc-toggle-label {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--fcc-dark);
        margin: 0;
    }

    .fcc-toggle-subtext {
        font-size: 11.5px;
        color: var(--fcc-muted);
        margin-top: 2px;
    }

    .fcc-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 28px;
        margin-bottom: 0;
        flex-shrink: 0;
    }

    .fcc-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .fcc-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.3s;
        border-radius: 28px;
    }

    .fcc-slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.18);
    }

    .fcc-switch input:checked + .fcc-slider {
        background-color: #1d4ed8;
    }

    .fcc-switch input:checked + .fcc-slider:before {
        transform: translateX(22px);
    }

    /* Footer Buttons */
    .fcc-footer-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 14px;
        margin-top: 28px;
    }

    .fcc-btn-save {
        background: var(--fcc-primary);
        border: none;
        border-radius: 12px;
        padding: 12px 32px;
        font-size: 14px;
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
        .fcc-form-grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing fcc-config-container">

    <!-- Page Header Bar -->
    <div class="fcc-page-header">
        <div class="fcc-header-info">
            <div class="fcc-header-icon-box">
                <i data-feather="settings" style="width: 24px; height: 24px;"></i>
            </div>
            <div>
                <h1 class="fcc-header-title">App Configuration</h1>
                <p class="fcc-header-subtitle">Manage mobile application version control, mandatory updates, and promotional dashboard popups.</p>
            </div>
        </div>
    </div>

    @if (session('notification'))
        <div class="alert alert-{{ session('notification._type') == 'success' ? 'success' : 'danger' }} alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
            <strong>{{ session('notification._message') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding: 0.75rem 1.25rem;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
            <ul class="mb-0" style="padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding: 0.75rem 1.25rem;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form method="POST" action="{{ route('adminPanel.app-config.update') }}" id="appConfigForm">
        @csrf

        <!-- CARD 1: Promotional Popup Configuration -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <div class="fcc-card-header-left">
                    <div class="fcc-card-header-icon" style="background: #fef3c7; color: #d97706;">
                        <i data-feather="bell" style="width: 18px; height: 18px;"></i>
                    </div>
                    <span class="card-title">Promotional Popup Management</span>
                </div>
                <div>
                    <span class="badge badge-light" style="font-size: 11px; padding: 6px 12px; border-radius: 8px;">Dashboard Banner</span>
                </div>
            </div>
            <div class="fcc-card-body">
                <div class="mb-4">
                    <div class="fcc-toggle-wrapper">
                        <div class="fcc-toggle-info">
                            <label class="fcc-toggle-label" for="popup_status">Popup Status</label>
                            <span class="fcc-toggle-subtext">Toggle ON to show the promotional popup in the mobile app dashboard. Toggle OFF or clear fields to disable.</span>
                        </div>
                        <label class="fcc-switch">
                            <input type="checkbox" name="popup_status" id="popup_status" value="1" {{ old('popup_status', ($config->popup_status ?? true)) ? 'checked' : '' }}>
                            <span class="fcc-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="fcc-form-field">
                    <label class="fcc-label" for="popup_title">
                        <span>Popup Title</span>
                        <span class="text-muted" style="font-weight: 500; font-size: 12px;">Leave blank to disable</span>
                    </label>
                    <input type="text" name="popup_title" id="popup_title" class="fcc-input" placeholder="e.g. Fit Coach Club Special Offer" value="{{ old('popup_title', ($config->popup_title ?? '')) }}">
                    <span class="fcc-help-text">Heading shown at the top of the modal popup on the mobile dashboard.</span>
                </div>

                <div class="fcc-form-field">
                    <label class="fcc-label" for="popup_message">
                        <span>Popup Message</span>
                        <span class="text-muted" style="font-weight: 500; font-size: 12px;">Leave blank to disable</span>
                    </label>
                    <textarea name="popup_message" id="popup_message" class="fcc-textarea" rows="3" placeholder="e.g. Enjoy our special offer. Contact us today!">{{ old('popup_message', ($config->popup_message ?? '')) }}</textarea>
                    <span class="fcc-help-text">Body text displayed inside the promotional popup dialog.</span>
                </div>
            </div>
        </div>

        <div class="fcc-form-grid-2">

            <!-- CARD 2: Android Platform Settings -->
            <div class="fcc-card">
                <div class="fcc-card-header-bar">
                    <div class="fcc-card-header-left">
                        <div class="fcc-card-header-icon" style="background: #ecfdf5; color: #059669;">
                            <i data-feather="play" style="width: 18px; height: 18px;"></i>
                        </div>
                        <span class="card-title">Android App Settings</span>
                    </div>
                    <span class="badge badge-success" style="font-size: 11px; padding: 4px 10px; border-radius: 6px;">Google Play</span>
                </div>
                <div class="fcc-card-body">
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="android_latest_version">
                            <span>Android Latest Version <span class="text-danger">*</span></span>
                        </label>
                        <input type="text" name="android_latest_version" id="android_latest_version" class="fcc-input" required placeholder="e.g. 1.0.6" value="{{ old('android_latest_version', ($config->android_latest_version ?? '1.0.0')) }}">
                        <span class="fcc-help-text">Current store version (e.g. 1.0.6). Compared semantically with app installed version.</span>
                    </div>

                    <div class="mb-4">
                        <div class="fcc-toggle-wrapper">
                            <div class="fcc-toggle-info">
                                <label class="fcc-toggle-label" for="android_force_update">Android Force Update</label>
                                <span class="fcc-toggle-subtext">If ON, users on versions older than latest will be blocked until updated.</span>
                            </div>
                            <label class="fcc-switch">
                                <input type="checkbox" name="android_force_update" id="android_force_update" value="1" {{ old('android_force_update', ($config->android_force_update ?? false)) ? 'checked' : '' }}>
                                <span class="fcc-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="fcc-form-field">
                        <label class="fcc-label" for="android_store_url">Android Store URL</label>
                        <input type="url" name="android_store_url" id="android_store_url" class="fcc-input" placeholder="https://play.google.com/store/apps/details?id=com.fitcoachclub.app" value="{{ old('android_store_url', ($config->android_store_url ?? '')) }}">
                        <span class="fcc-help-text">Custom Google Play Store URL. If left empty, the app defaults to its built-in store link.</span>
                    </div>
                </div>
            </div>

            <!-- CARD 3: iOS Platform Settings -->
            <div class="fcc-card">
                <div class="fcc-card-header-bar">
                    <div class="fcc-card-header-left">
                        <div class="fcc-card-header-icon" style="background: #f1f5f9; color: #0284c7;">
                            <i data-feather="smartphone" style="width: 18px; height: 18px;"></i>
                        </div>
                        <span class="card-title">iOS App Settings</span>
                    </div>
                    <span class="badge badge-info" style="font-size: 11px; padding: 4px 10px; border-radius: 6px;">App Store</span>
                </div>
                <div class="fcc-card-body">
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="ios_latest_version">
                            <span>iOS Latest Version <span class="text-danger">*</span></span>
                        </label>
                        <input type="text" name="ios_latest_version" id="ios_latest_version" class="fcc-input" required placeholder="e.g. 1.0.6" value="{{ old('ios_latest_version', ($config->ios_latest_version ?? '1.0.0')) }}">
                        <span class="fcc-help-text">Current App Store version (e.g. 1.0.6). Compared semantically with app installed version.</span>
                    </div>

                    <div class="mb-4">
                        <div class="fcc-toggle-wrapper">
                            <div class="fcc-toggle-info">
                                <label class="fcc-toggle-label" for="ios_force_update">iOS Force Update</label>
                                <span class="fcc-toggle-subtext">If ON, iOS users on versions older than latest will be blocked until updated.</span>
                            </div>
                            <label class="fcc-switch">
                                <input type="checkbox" name="ios_force_update" id="ios_force_update" value="1" {{ old('ios_force_update', ($config->ios_force_update ?? false)) ? 'checked' : '' }}>
                                <span class="fcc-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="fcc-form-field">
                        <label class="fcc-label" for="ios_store_url">iOS Store URL</label>
                        <input type="url" name="ios_store_url" id="ios_store_url" class="fcc-input" placeholder="https://apps.apple.com/app/id6440000000" value="{{ old('ios_store_url', ($config->ios_store_url ?? '')) }}">
                        <span class="fcc-help-text">Custom Apple App Store URL. If left empty, the app defaults to its built-in store link.</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer Actions -->
        <div class="fcc-footer-actions">
            <button type="submit" class="fcc-btn-save" id="btnSaveAppConfig">
                <i data-feather="check" style="width: 18px; height: 18px;"></i>
                Update Configuration
            </button>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush

