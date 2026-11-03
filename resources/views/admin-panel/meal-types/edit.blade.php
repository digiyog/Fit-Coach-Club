@extends('admin-panel.layouts.main-layout')

@section('page-title', 'Edit Meal Type | '.__('language.page_main_title').'')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">

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

    /* Dropify Polish */
    .dropify-wrapper {
        border-radius: 14px !important;
        border: 2px dashed #cbd5e1 !important;
        height: 220px !important;
        background-color: #f8fafc !important;
        transition: all 0.2s ease;
    }

    .dropify-wrapper:hover {
        background-color: #eff6ff !important;
        border-color: #3b82f6 !important;
    }

    /* Meal Schedule Accordion Polish */
    .meal-day-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 14px;
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.02);
    }

    .meal-day-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 18px;
    }

    .meal-day-btn {
        color: var(--fcc-dark);
        font-weight: 700;
        text-decoration: none !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 0;
        border: none;
        background: none;
        font-size: 14.5px;
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    }

    .meal-day-btn:hover {
        color: var(--fcc-primary);
    }

    .meal-slot-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 12px;
    }

    .meal-slot-box input.form-control {
        height: 42px !important;
        border-radius: 8px !important;
        border: 1.5px solid #e2e8f0 !important;
        font-size: 13px !important;
        color: #0f172a !important;
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
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                    <line x1="6" y1="1" x2="6" y2="4"></line>
                    <line x1="10" y1="1" x2="10" y2="4"></line>
                    <line x1="14" y1="1" x2="14" y2="4"></line>
                </svg>
            </div>
            <div>
                <h1 class="fcc-header-title">Edit Meal Type</h1>
                <p class="fcc-header-subtitle">Modify and update weekly diet templates, meal classifications, and nutritional slots.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('adminPanel.meal-types.index') }}" class="fcc-btn-back">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Meal Types
            </a>
        </div>
    </div>

    <!-- Error Alerts Component -->
    @component('admin-panel.validation.errors') @endcomponent

    {!! Form::open(['class' => 'meal-type-form', 'method' => 'post', 'url' => route('adminPanel.meal-types.update', ['id' => ev($mealType->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'id' => 'editMealTypeForm' ]) !!}

        <!-- 2. Section: General Information & Media Card -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                    <polyline points="2 17 12 22 22 17"></polyline>
                    <polyline points="2 12 12 17 22 12"></polyline>
                </svg>
                <span>General Information</span>
            </div>
            <div class="fcc-card-body">
                <div class="row">
                    @php
                        $imagePath = (get_image_url(config('constants.meal_types.image_path'), $mealType->image) ?? '');
                    @endphp

                    <div class="col-lg-4 col-md-5 mb-3">
                        <label class="fcc-label" for="image">Meal Type Photo <span class="text-danger">*</span></label>
                        <div class="custom-dropify">
                            {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => $imagePath, ]) !!}
                            {!! Form::hidden('image_name', old('image_name', ($mealType->image ?? null)) ,['class' => 'form-control','id' => 'image_name']) !!}
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-7">
                        <div class="fcc-form-field mb-3">
                            <label class="fcc-label" for="name">Meal Type Name <span class="text-danger">*</span></label>
                            <div class="fcc-input-wrap">
                                <span class="fcc-input-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path></svg>
                                </span>
                                {!! Form::text('name', $mealType->name, ['class' => 'form-control fcc-form-control', 'id' => 'name', 'placeholder' => 'e.g. Weight Loss Diet Plan, High Protein Meal', 'required' => 'required' ]) !!}
                            </div>
                        </div>

                        <div class="fcc-form-field">
                            <label class="fcc-label" for="order">Display Order <span class="text-danger">*</span></label>
                            <div class="fcc-input-wrap">
                                <span class="fcc-input-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                </span>
                                {!! Form::text('order', $mealType->order, ['class' => 'form-control fcc-form-control numeric', 'id' => 'order', 'placeholder' => 'Order number', 'required' => 'required' ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Section: Weekly Meal Schedule Accordion Card -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>Weekly Meal Schedule by Day</span>
            </div>
            <div class="fcc-card-body">
                <div id="mealAccordion">
                    @foreach($weekdays as $dayKey => $dayName)
                        <div class="meal-day-card">
                            <div class="meal-day-header" id="heading{{$dayKey}}">
                                <h5 class="mb-0">
                                    <button type="button" class="meal-day-btn" data-bs-toggle="collapse" data-bs-target="#collapse{{$dayKey}}" aria-expanded="{{ $dayKey == 1 ? 'true' : 'false' }}">
                                        <span><i data-feather="calendar" class="text-primary me-2" style="width: 15px; height: 15px;"></i> {{ $dayName }}</span>
                                        <i data-feather="chevron-down" style="width: 16px; height: 16px;"></i>
                                    </button>
                                </h5>
                            </div>

                            <div id="collapse{{$dayKey}}" class="collapse {{ $dayKey == 1 ? 'show' : '' }}" data-bs-parent="#mealAccordion">
                                <div class="p-3">
                                    @foreach($scheduleTypes as $typeKey => $type)
                                        @php
                                            $title = $mealData[$dayKey][$type['value']]['title'] ?? '';
                                            $time  = $mealData[$dayKey][$type['value']]['time'] ?? '';
                                            $desc  = $mealData[$dayKey][$type['value']]['description'] ?? '';
                                        @endphp

                                        <div class="meal-slot-box">
                                            <h6 class="fw-bold mb-2 text-primary" style="font-size: 13.5px;">
                                                {{ $type['display'] }}
                                            </h6>

                                            <input type="hidden" name="meals[{{$dayKey}}][{{$type['value']}}][title]" value="{{ $type['display'] }}" class="form-control">

                                            <div class="row g-2">
                                                <div class="col-md-3">
                                                    <label class="fs-13 fw-semibold text-muted mb-1">Time</label>
                                                    <input type="text"
                                                        name="meals[{{$dayKey}}][{{$type['value']}}][time]"
                                                        value="{{ $time }}"
                                                        class="form-control timepicker"
                                                        placeholder="hh:mm AM/PM"
                                                        required>
                                                </div>

                                                <div class="col-md-9">
                                                    <label class="fs-13 fw-semibold text-muted mb-1">Diet / Food Description</label>
                                                    <input type="text" 
                                                        name="meals[{{$dayKey}}][{{$type['value']}}][description]"
                                                        value="{{ $desc }}"
                                                        class="form-control"
                                                        placeholder="Enter {{ $type['display'] }} description" required/>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 4. Form Footer Actions (Cancel / Update) -->
        <div class="fcc-footer-actions">
            <a href="{{ route('adminPanel.meal-types.index') }}" class="fcc-btn-cancel">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                Cancel
            </a>
            <button type="submit" class="fcc-btn-save" id="btnSubmitMealType">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Update Meal Type
            </button>
        </div>

    {!! Form::close() !!}

</div>
@endsection

@push('scripts')
<script>
    var maxImageSize = {{config('constants.max_image_size')}};
</script>
<script src="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/flatpickr.js') }}"></script>
<script src="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/meal-types/meal-types.js') }}"></script>

<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush