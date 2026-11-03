@extends('admin-panel.layouts.main-layout')

@section('page-title', 'Edit Product | '.__('language.page_main_title').'')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/file-upload/file-upload-with-preview.min.css') }}" rel="stylesheet" type="text/css" />
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

    .fcc-create-container .fcc-form-field-full {
        grid-column: 1 / -1 !important;
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

    .fcc-create-container .fcc-input-wrap input:focus,
    .fcc-create-container textarea.form-control:focus {
        border-color: #1d4ed8 !important;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12) !important;
        outline: none !important;
    }

    .fcc-create-container textarea.form-control {
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        padding: 12px 16px !important;
        font-size: 13.5px !important;
        color: #0f172a !important;
    }

    .fcc-hint-text {
        font-size: 11.5px;
        color: var(--fcc-muted);
        margin-top: 6px;
        display: block;
        font-weight: 500;
    }

    /* Bootstrap Select Dropdown Polish */
    .fcc-create-container .bootstrap-select > .btn,
    .fcc-create-container .bootstrap-select > button.dropdown-toggle {
        height: 48px !important;
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #ffffff !important;
        padding-left: 16px !important;
        font-size: 13.5px !important;
        color: #0f172a !important;
        box-shadow: none !important;
    }

    /* Dropify wrapper polish */
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

    /* Gallery Upload Card */
    .custom-file-container__image-multi-preview {
        height: 100px !important;
        width: 100px !important;
        border-radius: 10px !important;
        border: 1px solid #e2e8f0 !important;
        margin: 6px !important;
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
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>
            <div>
                <h1 class="fcc-header-title">Edit Product</h1>
                <p class="fcc-header-subtitle">Modify and update product information, pricing, gallery images, and details.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('adminPanel.products.index') }}" class="fcc-btn-back">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Products
            </a>
        </div>
    </div>

    <!-- Error Alerts Component -->
    @component('admin-panel.validation.errors') @endcomponent

    {!! Form::open(['class' => 'product-form', 'method' => 'post', 'url' => route('adminPanel.products.update', ['id' => ev($product->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'id' => 'editProductForm' ]) !!}

        <!-- 2. Section: Product Media Card -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span>Product Media & Imagery</span>
            </div>
            <div class="fcc-card-body">
                <div class="row">
                    @php
                        $imagePath = (get_image_url(config('constants.products.image_path'), $product->image) ?? '');
                    @endphp

                    <div class="col-lg-4 col-md-5 mb-3">
                        <label class="fcc-label" for="image">Featured Cover Image <span class="text-danger">*</span></label>
                        <div class="custom-dropify">
                            {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => $imagePath, ]) !!}
                            {!! Form::hidden('image_name', old('image_name', ($product->image ?? null)) ,['class' => 'form-control','id' => 'image_name']) !!}
                        </div>
                        <span class="fcc-hint-text">Supported: JPG, PNG, WebP. Recommended: Square 800x800px.</span>
                    </div>

                    <div class="col-lg-8 col-md-7">
                        <label class="fcc-label">Gallery Showcase Images</label>
                        <div class="custom-file-container p-3" data-upload-id="mySecondImage" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted" style="font-size: 12.5px; font-weight: 500;">Upload supplementary photos for product carousel</span>
                                <a href="javascript:void(0)" class="custom-file-container__image-clear badge badge-danger" title="Clear Image">Clear all</a>
                            </div>
                            <label class="custom-file-container__custom-file">
                                <input type="file" id="file-input" name="multiple_images[images][]" accept="image/*" class="custom-file-container__custom-file__custom-file-input w-100" multiple>
                                <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                <span class="custom-file-container__custom-file__custom-file-control"></span>
                            </label>
                            <div id="image-preview" class="custom-file-container__image-preview mt-2"></div>
                        </div>

                        @if(isset($productImage) && $productImage->count())
                            <div class="mt-3 pt-3 border-top">
                                <label class="fcc-label mb-2">Existing Gallery Photos:</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($productImage as $img)
                                        <div class="image-box-{{ $img->id }}" style="position: relative; display: inline-block;">
                                            <img src="{{ get_image_url(config('constants.products.image_path'), $img->image) }}" 
                                                style="width: 80px; height: 80px; object-fit: cover; border: 1.5px solid #cbd5e1; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                                            <button type="button" class="btn btn-sm btn-danger remove-existing-image" data-id="{{ $img->id }}" style="position: absolute; top: -6px; right: -6px; width: 22px; height: 22px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 13px; line-height: 1;">
                                                ×
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Section: General Information Card -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                    <polyline points="2 17 12 22 22 17"></polyline>
                    <polyline points="2 12 12 17 22 12"></polyline>
                </svg>
                <span>General Information & Pricing</span>
            </div>
            <div class="fcc-card-body">
                <div class="fcc-form-grid">
                    <!-- Product Name -->
                    <div class="fcc-form-field fcc-form-field-full">
                        <label class="fcc-label" for="name">Product Name <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrap">
                            <span class="fcc-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                            </span>
                            {!! Form::text('name', $product->name, ['class' => 'form-control fcc-form-control', 'id' => 'name', 'placeholder' => 'Enter product name', 'required' => 'required' ]) !!}
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="price">Price (₹) <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrap">
                            <span class="fcc-input-icon">
                                <span style="font-weight: 800; font-size: 16px; color: #94a3b8;">₹</span>
                            </span>
                            {!! Form::text('price', $product->price, ['class' => 'form-control fcc-form-control numeric', 'id' => 'price', 'placeholder' => '0.00', 'required' => 'required' ]) !!}
                        </div>
                    </div>

                    <!-- Order -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="order">Display Order <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrap">
                            <span class="fcc-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                            </span>
                            {!! Form::text('order', $product->order, ['class' => 'form-control fcc-form-control numeric', 'id' => 'order', 'placeholder' => 'Display sequence (e.g. 1, 2)', 'required' => 'required' ]) !!}
                        </div>
                    </div>

                    <!-- Product Type -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="product_type">Product Type <span class="text-danger">*</span></label>
                        <select name="product_type" id="product_type" class="form-control filter-field select-picker" data-live-search="true" title="Select Product Type" required>
                            <option value="">Select Product Type</option>
                            @foreach($productTypes as $key => $value)
                                <option value="{{$value['id']}}" {{ (isset($product->product_type_id) && $product->product_type_id == $value['id']) ? 'selected' : '' }}>{{ $value['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Allowed Franchises -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="franchises">Select Franchises <span class="text-danger">*</span></label>
                        <select name="franchises[]" id="franchises" class="form-control filter-field select-picker" multiple data-live-search="true" title="Choose allowed franchises">
                            @foreach($franchises as $key => $value)
                                <option value="{{$value['id']}}" {{ in_array($value['id'], (array)($franchiseProduct ?? [])) ? 'selected' : '' }}>{{ $value['name'] }}</option>
                            @endforeach
                        </select>
                        <span class="fcc-hint-text">Specify which franchise branches can distribute this product.</span>
                    </div>

                    <!-- Short Description -->
                    <div class="fcc-form-field fcc-form-field-full">
                        <label class="fcc-label" for="short_description">Short Overview</label>
                        {!! Form::textarea('short_description', $product->short_description, ['class' => 'form-control textarea-height', 'id' => 'short_description', 'placeholder' => 'Brief 1-2 sentence overview for product card previews...', 'rows' => 3 ]) !!}
                    </div>

                    <!-- Full Description -->
                    <div class="fcc-form-field fcc-form-field-full">
                        <label class="fcc-label" for="description">Detailed Description</label>
                        {!! Form::textarea('description', $product->description, ['class' => 'form-control textarea-height editor-textarea', 'id' => 'description', 'placeholder' => 'Full specifications, ingredients, usage instructions...', 'rows' => 5 ]) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Form Footer Actions (Cancel / Update) -->
        <div class="fcc-footer-actions">
            <a href="{{ route('adminPanel.products.index') }}" class="fcc-btn-cancel">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                Cancel
            </a>
            <button type="submit" class="fcc-btn-save" id="btnSubmitProduct">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Update Product
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
<script src="{{ asset('admin-assets/js/products/products.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/file-upload/file-upload-with-preview.min.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        document.querySelectorAll(".custom-file-container").forEach(container => {
            let uploadId = container.getAttribute("data-upload-id");
            let fileInput = document.querySelector(`#file-input`);
            let imagePreview = document.querySelector(`#image-preview`);
            let clearButton = container.querySelector(".custom-file-container__image-clear");

            new FileUploadWithPreview(uploadId);

            if (!fileInput || !imagePreview || !clearButton) {
                return;
            }

            let removedFiles = [];

            clearButton.addEventListener("click", function () {
                imagePreview.innerHTML = "";
                removedFiles = [...fileInput.files];
                fileInput.value = "";
            });

            fileInput.addEventListener("change", function () {
                let files = Array.from(fileInput.files);
                files = files.filter(file => !removedFiles.includes(file));

                let dataTransfer = new DataTransfer();
                files.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;

                removedFiles = [];
            });
        });
    });
</script>
@endpush