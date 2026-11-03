@extends('admin-panel.layouts.main-layout')

@section('page-title', 'App Configuration | '.__('language.page_main_title').'')

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

    /* 2. Structured White Cards */
    .fcc-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        margin-bottom: 22px;
        overflow: hidden;
    }

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

    .fcc-create-container .fcc-form-grid-3 {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        column-gap: 24px !important;
        row-gap: 20px !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    @media (max-width: 991px) {
        .fcc-create-container .fcc-form-grid-3 {
            grid-template-columns: 1fr !important;
        }
    }

    .fcc-create-container .fcc-form-field {
        width: 100% !important;
        margin: 0 !important;
        display: flex !important;
        flex-direction: column !important;
    }

    /* Labels */
    .fcc-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--fcc-dark);
        margin-bottom: 7px;
        display: block;
    }

    /* Select and Input styling */
    .fcc-create-container .bootstrap-select .btn.dropdown-toggle,
    .fcc-create-container select.form-control,
    .fcc-create-container input.form-control {
        height: 48px !important;
        line-height: 48px !important;
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #ffffff !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #0f172a !important;
        padding: 0 16px !important;
        box-shadow: none !important;
        width: 100% !important;
    }

    .fcc-create-container select.form-control:focus,
    .fcc-create-container input.form-control:focus {
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
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
            </div>
            <div>
                <h1 class="fcc-header-title">{{ __('language.configurations_menu') }}</h1>
                <p class="fcc-header-subtitle">Manage mobile application version control, mandatory updates, and platform maintenance toggles.</p>
            </div>
        </div>
    </div>

    {!! Form::open(['class' => 'edit-configuration-form', 'method' => 'post', 'url' => route('adminPanel.appConfigurations.updateAppConfigurations', ['id' => ev($configurations->id ?? null)]), 'id' => 'appConfigForm' ]) !!}

        <!-- Card 1: Android Configuration -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                    <line x1="12" y1="18" x2="12.01" y2="18"></line>
                </svg>
                <span>Android App Settings</span>
            </div>
            <div class="fcc-card-body">
                <div class="fcc-form-grid-3">
                    <!-- Maintenance Mode -->
                    <div class="fcc-form-field">
                        @php
                            $AndroidMaintenanceMode = optional(get_configurations(['android-app-maintenance-mode'])->first())->config_value;
                        @endphp
                        <label class="fcc-label" for="android_app_maintenance">{{ __('language.android_app_maintenance') }}</label>
                        {!! Form::select('android_app_maintenance', create_select_options(config('constants.app_config_maintenance_mode'), 'key', 'value'), old('android_app_maintenance', ($AndroidMaintenanceMode ?? null)), ['class' => 'form-control select-picker', 'id' => 'android_app_maintenance', 'autocomplete' => 'off']) !!}
                    </div>

                    <!-- App Version -->
                    <div class="fcc-form-field">
                        @php
                            $AndroidVersion = optional(get_configurations(['android-app-version'])->first())->config_value;
                        @endphp
                        <label class="fcc-label" for="android_app_version">{{ __('language.android_app_version') }}</label>
                        {!! Form::text('android_app_version', old('android_app_version', ($AndroidVersion ?? null)), ['class' => 'form-control', 'id' => 'android_app_version', 'placeholder' => 'e.g. 1.0.0', 'autocomplete' => 'off', ]) !!}
                    </div>

                    <!-- Mandatory Update -->
                    <div class="fcc-form-field">
                        @php
                            $AndroidMandatoryUpdate = optional(get_configurations(['android-app-mandatory-update'])->first())->config_value;
                        @endphp
                        <label class="fcc-label" for="android_app_mandatory_update">{{ __('language.android_app_mandatory_update') }}</label>
                        {!! Form::select('android_app_mandatory_update', create_select_options(config('constants.app_config_maintenance_mode'), 'key', 'value'), old('android_app_mandatory_update', ($AndroidMandatoryUpdate ?? null)), ['class' => 'form-control select-picker', 'id' => 'android_app_mandatory_update', 'autocomplete' => 'off']) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: iOS Configuration -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                    <line x1="12" y1="18" x2="12.01" y2="18"></line>
                </svg>
                <span>iOS App Settings</span>
            </div>
            <div class="fcc-card-body">
                <div class="fcc-form-grid-3">
                    <!-- Maintenance Mode -->
                    <div class="fcc-form-field">
                        @php
                            $IosMaintenanceMode = optional(get_configurations(['ios-app-maintenance-mode'])->first())->config_value;
                        @endphp
                        <label class="fcc-label" for="ios_app_maintenance">{{ __('language.ios_app_maintenance') }}</label>
                        {!! Form::select('ios_app_maintenance', create_select_options(config('constants.app_config_maintenance_mode'), 'key', 'value'), ($IosMaintenanceMode ?? null), ['class' => 'form-control select-picker', 'id' => 'ios_app_maintenance', 'autocomplete' => 'off']) !!}
                    </div>

                    <!-- App Version -->
                    <div class="fcc-form-field">
                        @php
                            $IosVersion = optional(get_configurations(['ios-app-version'])->first())->config_value;
                        @endphp
                        <label class="fcc-label" for="ios_app_version">{{ __('language.ios_app_version') }}</label>
                        {!! Form::text('ios_app_version', old('ios_app_version', ($IosVersion ?? null)), ['class' => 'form-control', 'id' => 'ios_app_version', 'placeholder' => 'e.g. 1.0.0', 'autocomplete' => 'off', ]) !!}
                    </div>

                    <!-- Mandatory Update -->
                    <div class="fcc-form-field">
                        @php
                            $IosMandatoryUpdate = optional(get_configurations(['ios-app-mandatory-update'])->first())->config_value;
                        @endphp
                        <label class="fcc-label" for="ios_app_mandatory_update">{{ __('language.ios_app_mandatory_update') }}</label>
                        {!! Form::select('ios_app_mandatory_update', create_select_options(config('constants.app_config_maintenance_mode'), 'key', 'value'), old('ios_app_mandatory_update', ($IosMandatoryUpdate ?? null)), ['class' => 'form-control select-picker', 'id' => 'ios_app_mandatory_update', 'autocomplete' => 'off']) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Form Footer Actions (Save) -->
        <div class="fcc-footer-actions">
            <button type="submit" class="fcc-btn-save" id="btnUpdateAppConfigs">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Update Configuration
            </button>
        </div>

    {!! Form::close() !!}

</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/configurations/app-configuration.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
