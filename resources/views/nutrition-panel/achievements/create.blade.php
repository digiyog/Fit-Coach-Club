@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Create Achievement | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">

<style>
    /* Achievements Form Modern Styling */
    .ach-form-container {
        padding: 4px 6px 36px 6px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Header Section */
    .ach-header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .ach-breadcrumb {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .ach-breadcrumb a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .ach-breadcrumb a:hover {
        color: #2563eb;
    }

    .ach-breadcrumb span.active {
        color: #0f172a;
        font-weight: 600;
    }

    .ach-title {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.25;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ach-badge-new {
        font-size: 12px;
        font-weight: 700;
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        border-radius: 9999px;
        padding: 2px 10px;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .ach-subtitle {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 0;
    }

    .btn-ach-back {
        background: #ffffff;
        color: #334155;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 9px 18px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        text-decoration: none !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .btn-ach-back:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
        transform: translateX(-2px);
    }

    /* Cards */
    .ach-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        margin-bottom: 24px;
        overflow: hidden;
        transition: box-shadow 0.2s ease;
    }

    .ach-card:hover {
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
    }

    .ach-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 14px;
        background: #ffffff;
    }

    .ach-card-icon-wrap {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .icon-wrap-primary {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }

    .icon-wrap-indigo {
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        color: #4f46e5;
        border: 1px solid #c7d2fe;
    }

    .icon-wrap-cyan {
        background: linear-gradient(135deg, #ecfeff 0%, #cffafe 100%);
        color: #0891b2;
        border: 1px solid #a5f3fc;
    }

    .ach-card-title {
        font-size: 16.5px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
        line-height: 1.3;
    }

    .ach-card-desc {
        font-size: 12.5px;
        color: #64748b;
        margin-bottom: 0;
    }

    .ach-card-body {
        padding: 24px;
    }

    /* Form Fields */
    .ach-form-group {
        margin-bottom: 20px;
    }

    .ach-form-group:last-child {
        margin-bottom: 0;
    }

    .ach-label {
        font-size: 13.5px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
        display: block;
    }

    .ach-label .text-danger {
        color: #ef4444;
        margin-left: 2px;
    }

    .ach-input {
        height: 48px;
        border-radius: 10px !important;
        border: 1.5px solid #cbd5e1 !important;
        font-size: 14px !important;
        color: #0f172a !important;
        padding: 10px 16px !important;
        background-color: #ffffff !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
    }

    .ach-input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
        background-color: #ffffff !important;
    }

    .ach-input::placeholder {
        color: #94a3b8;
    }

    /* Bootstrap Select Picker Custom Styling */
    .bootstrap-select.select-picker > .btn {
        height: 48px !important;
        border-radius: 10px !important;
        border: 1.5px solid #cbd5e1 !important;
        background-color: #ffffff !important;
        color: #0f172a !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        padding: 10px 16px !important;
        box-shadow: none !important;
        outline: none !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        transition: all 0.2s ease !important;
    }

    .bootstrap-select.select-picker > .btn:focus,
    .bootstrap-select.select-picker > .btn:active,
    .bootstrap-select.select-picker.show > .btn {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    .bootstrap-select.select-picker .dropdown-menu {
        border-radius: 12px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 25px -4px rgba(15, 23, 42, 0.1) !important;
        padding: 6px !important;
    }

    .bootstrap-select.select-picker .dropdown-menu .dropdown-item {
        border-radius: 8px !important;
        padding: 8px 12px !important;
        font-size: 13.5px !important;
        color: #334155 !important;
    }

    .bootstrap-select.select-picker .dropdown-menu .dropdown-item.active,
    .bootstrap-select.select-picker .dropdown-menu .dropdown-item:hover {
        background-color: #eff6ff !important;
        color: #2563eb !important;
    }

    .ach-field-hint {
        font-size: 12px;
        color: #64748b;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Dropify Artwork Styling */
    .custom-dropify {
        position: relative;
    }

    .dropify-wrapper {
        width: 100% !important;
        height: 250px !important;
        border-radius: 14px !important;
        border: 2px dashed #cbd5e1 !important;
        background-color: #f8fafc !important;
        transition: all 0.25s ease !important;
        padding: 10px !important;
    }

    .dropify-wrapper:hover {
        border-color: #2563eb !important;
        background-color: #eff6ff !important;
        background-image: none !important;
    }

    .dropify-wrapper .dropify-message span.file-icon {
        font-size: 40px !important;
        color: #94a3b8 !important;
    }

    .dropify-wrapper .dropify-message p {
        font-size: 13.5px !important;
        font-weight: 600 !important;
        color: #475569 !important;
        margin: 8px 0 0 0 !important;
    }

    .dropify-wrapper .dropify-preview {
        border-radius: 10px !important;
    }

    /* Artwork spec chips */
    .ach-tips-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 14px;
    }

    .ach-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 600;
        color: #475569;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 9999px;
        padding: 4px 10px;
    }

    .ach-chip i {
        color: #2563eb;
        font-size: 11px;
    }

    /* Best Practices Info Card */
    .ach-info-card {
        background: linear-gradient(145deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 22px;
        margin-bottom: 24px;
    }

    .ach-info-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }

    .ach-info-card-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .ach-tips-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .ach-tips-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 12.5px;
        color: #475569;
        line-height: 1.5;
    }

    .ach-tips-item-icon {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #e0f2fe;
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .ach-tips-item strong {
        color: #1e293b;
    }

    /* Actions Footer */
    .ach-actions-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 24px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
    }

    .btn-ach-submit {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 10px;
        padding: 11px 26px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        cursor: pointer;
    }

    .btn-ach-submit:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.45);
    }

    .btn-ach-cancel {
        background: #ffffff;
        color: #64748b !important;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }

    .btn-ach-cancel:hover {
        background: #f8fafc;
        color: #0f172a !important;
        border-color: #94a3b8;
    }

    /* Validation Errors */
    .invalid-feedback {
        color: #ef4444;
        font-size: 12px;
        font-weight: 500;
        margin-top: 5px;
        display: block;
    }
</style>
@endpush

@section('content')
<div class="ach-form-container">

    <!-- Header Section -->
    <div class="ach-header-section">
        <div>
            <div class="ach-breadcrumb">
                <a href="{{ route('nutritionPanel.dashboard') }}"><i class="fa fa-home"></i> {{ __('language.dashboard') }}</a>
                <span>&rsaquo;</span>
                <a href="{{ route('nutritionPanel.achievements.index') }}">Achievements</a>
                <span>&rsaquo;</span>
                <span class="active">Create</span>
            </div>
            <h1 class="ach-title">
                Create Achievement
                <span class="ach-badge-new">New</span>
            </h1>
            <p class="ach-subtitle">Configure badges, milestone announcements, and mobile app visibility for your members.</p>
        </div>

        <div>
            <a href="{{ route('nutritionPanel.achievements.index') }}" class="btn-ach-back">
                <i class="fa fa-arrow-left"></i>
                <span>Back to Achievements</span>
            </a>
        </div>
    </div>

    <!-- Validation errors -->
    @component('nutrition-panel.validation.errors') @endcomponent
    <!-- / Validation errors -->

    {!! Form::open(['class' => 'achievement-form', 'method' => 'post', 'url' => route('nutritionPanel.achievements.store'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
        <div class="row g-4">
            
            <!-- Left Column: Details & Settings -->
            <div class="col-xl-8 col-lg-8 col-12">
                
                <!-- Card 1: Primary Information -->
                <div class="ach-card">
                    <div class="ach-card-header">
                        <div class="ach-card-icon-wrap icon-wrap-primary">
                            <i class="fa fa-trophy"></i>
                        </div>
                        <div>
                            <h3 class="ach-card-title">Achievement Details</h3>
                            <p class="ach-card-desc">Set title, badge classification, and display ordering</p>
                        </div>
                    </div>
                    <div class="ach-card-body">
                        
                        <!-- Title Field -->
                        <div class="ach-form-group">
                            <label class="ach-label" for="title">
                                Title <span class="text-danger">*</span>
                            </label>
                            {!! Form::text('title', old('title', ''), [
                                'class' => 'form-control ach-input',
                                'id' => 'title',
                                'placeholder' => 'e.g., 30 Days Streak Champion, 5kg Transformation Club',
                                'required' => 'required'
                            ]) !!}
                            <div class="ach-field-hint">
                                <i class="fa fa-info-circle text-primary"></i>
                                <span>A motivating title displayed on the user's mobile app milestone wall.</span>
                            </div>
                        </div>

                        <!-- Type & Order Row -->
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <div class="ach-form-group">
                                    <label class="ach-label" for="type">
                                        Achievement Type <span class="text-danger">*</span>
                                    </label>
                                    {!! Form::select(
                                        'type',
                                        create_select_options(config('constants.achievement_types'), 'display', 'value', 'Select Achievement Type'),
                                        old('type', ''),
                                        ['class' => 'form-control select-picker', 'id' => 'type', 'required' => 'required']
                                    ) !!}
                                    <div class="ach-field-hint">
                                        <i class="fa fa-tag text-primary"></i>
                                        <span>Categorizes the badge in client trophy collections.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="ach-form-group">
                                    <label class="ach-label" for="order">
                                        Display Order <span class="text-danger">*</span>
                                    </label>
                                    {!! Form::text('order', old('order', 0), [
                                        'class' => 'form-control ach-input numeric',
                                        'id' => 'order',
                                        'placeholder' => '0',
                                        'required' => 'required'
                                    ]) !!}
                                    <div class="ach-field-hint">
                                        <i class="fa fa-sort-numeric-asc text-primary"></i>
                                        <span>Lower numbers (e.g. 0, 1, 2) appear first in mobile feeds.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Card 2: App Visibility & Audience -->
                <div class="ach-card">
                    <div class="ach-card-header">
                        <div class="ach-card-icon-wrap icon-wrap-indigo">
                            <i class="fa fa-mobile"></i>
                        </div>
                        <div>
                            <h3 class="ach-card-title">Mobile App Visibility</h3>
                            <p class="ach-card-desc">Configure where this banner appears and member audience reach</p>
                        </div>
                    </div>
                    <div class="ach-card-body">
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <div class="ach-form-group">
                                    <label class="ach-label" for="in_app_show">
                                        Feature on App Home Screen <span class="text-danger">*</span>
                                    </label>
                                    {!! Form::select(
                                        'in_app_show',
                                        create_select_options(config('constants.in_app_show'), 'display', 'value', 'Select Home Display Option'),
                                        old('in_app_show', ''),
                                        ['class' => 'form-control select-picker', 'id' => 'in_app_show', 'required' => 'required']
                                    ) !!}
                                    <div class="ach-field-hint">
                                        <i class="fa fa-bullhorn text-primary"></i>
                                        <span>Featured items get hero highlight placement on the app home tab.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="ach-form-group">
                                    <label class="ach-label" for="show_achievement">
                                        Target Audience <span class="text-danger">*</span>
                                    </label>
                                    {!! Form::select(
                                        'show_achievement',
                                        create_select_options(config('constants.show_achievement'), 'display', 'value', 'Select Audience Option'),
                                        old('show_achievement', ''),
                                        ['class' => 'form-control select-picker', 'id' => 'show_achievement', 'required' => 'required']
                                    ) !!}
                                    <div class="ach-field-hint">
                                        <i class="fa fa-users text-primary"></i>
                                        <span>Show to all members or restrict to designated user groups.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Artwork & Guidelines -->
            <div class="col-xl-4 col-lg-4 col-12">
                
                <!-- Card 3: Artwork Upload -->
                <div class="ach-card">
                    <div class="ach-card-header">
                        <div class="ach-card-icon-wrap icon-wrap-cyan">
                            <i class="fa fa-image"></i>
                        </div>
                        <div>
                            <h3 class="ach-card-title">Badge Artwork</h3>
                            <p class="ach-card-desc">Visual graphic or certificate badge</p>
                        </div>
                    </div>
                    <div class="ach-card-body">
                        <div class="custom-dropify">
                            <label class="ach-label" for="image">
                                Upload Graphic <span class="text-danger">*</span>
                            </label>
                            {!! Form::file('image', [
                                'class' => 'image-preview',
                                'id' => 'image',
                                'autocomplete' => 'off',
                                'data-show-remove' => 'false',
                                'accept' => 'image/*',
                                'data-default-file' => '',
                            ]) !!}

                            {!! Form::hidden('image_name', '', ['class' => 'form-control', 'id' => 'image_name']) !!}
                        </div>

                        <div class="ach-tips-chips">
                            <span class="ach-chip"><i class="fa fa-file-picture-o"></i> PNG, JPG, WEBP</span>
                            <span class="ach-chip"><i class="fa fa-square-o"></i> 1:1 Square</span>
                            <span class="ach-chip"><i class="fa fa-database"></i> Max {{ config('constants.max_image_size') }}MB</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Best Practices Card -->
                <div class="ach-info-card">
                    <div class="ach-info-card-header">
                        <div class="ach-card-icon-wrap" style="width: 32px; height: 32px; border-radius: 8px; background: #fef3c7; color: #d97706; font-size: 14px;">
                            <i class="fa fa-lightbulb-o"></i>
                        </div>
                        <h4 class="ach-info-card-title">Best Practices</h4>
                    </div>
                    <ul class="ach-tips-list">
                        <li class="ach-tips-item">
                            <div class="ach-tips-item-icon"><i class="fa fa-check"></i></div>
                            <div><strong>High Contrast:</strong> Clean 3D badges with transparent background render best on member screens.</div>
                        </li>
                        <li class="ach-tips-item">
                            <div class="ach-tips-item-icon"><i class="fa fa-check"></i></div>
                            <div><strong>Clear Goal:</strong> Short titles (under 40 chars) convey immediate accomplishment.</div>
                        </li>
                        <li class="ach-tips-item">
                            <div class="ach-tips-item-icon"><i class="fa fa-check"></i></div>
                            <div><strong>Curated Home:</strong> Highlight your top 3-5 major milestones for the highest engagement.</div>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Action Bar -->
            <div class="col-12">
                <div class="ach-actions-card">
                    <a href="{{ route('nutritionPanel.achievements.index') }}" class="btn-ach-cancel">
                        <i class="fa fa-times"></i>
                        <span>Cancel</span>
                    </a>

                    <button type="submit" class="btn-ach-submit btn-submit">
                        <i class="fa fa-check-circle"></i>
                        <span>{{ __('language.save') }} Achievement</span>
                    </button>
                </div>
            </div>

        </div>
    {!! Form::close() !!}

</div>
@endsection

@push('scripts')
<script>
    var maxImageSize = {{ config('constants.max_image_size') }};
</script>
<script src="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/flatpickr.js') }}"></script>
<script src="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/achievements/achievements.js') }}"></script>
@endpush
