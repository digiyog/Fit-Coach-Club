@extends('admin-panel.layouts.main-layout')

@section('page-title', 'Edit CMS Page | '.__('language.page_main_title').'')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">

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

    .fcc-create-container .fcc-form-field {
        width: 100% !important;
        margin-bottom: 20px;
        display: flex !important;
        flex-direction: column !important;
    }

    .fcc-create-container .fcc-form-field:last-child {
        margin-bottom: 0;
    }

    /* Labels */
    .fcc-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--fcc-dark);
        margin-bottom: 7px;
        display: block;
    }

    /* Input & Textarea */
    .fcc-create-container input.form-control {
        height: 48px !important;
        line-height: 48px !important;
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #ffffff !important;
        padding-left: 16px !important;
        padding-right: 16px !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #0f172a !important;
        width: 100% !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
    }

    .fcc-create-container input.form-control[readonly] {
        background: #f8fafc !important;
        color: #475569 !important;
        cursor: not-allowed;
    }

    .fcc-create-container textarea.form-control {
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #ffffff !important;
        padding: 14px 16px !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #0f172a !important;
        box-shadow: none !important;
        line-height: 1.6 !important;
    }

    .fcc-create-container input.form-control:focus,
    .fcc-create-container textarea.form-control:focus {
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
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
            </div>
            <div>
                <h1 class="fcc-header-title">
                    @if($cms->id == 1)
                        Edit Home Page
                    @elseif($cms->id == 2)
                        Edit Procedure Booking
                    @elseif($cms->id == 3)
                        Edit Thank You Page
                    @elseif($cms->id == 4)
                        Edit Privacy Policy
                    @elseif($cms->id == 5)
                        Edit Terms & Conditions
                    @else
                        Edit CMS Page: {{ $cms->title }}
                    @endif
                </h1>
                <p class="fcc-header-subtitle">Update public web content, policies, and search engine optimization (SEO).</p>
            </div>
        </div>
        <div>
            <a href="{{ route('adminPanel.cms-pages.index') }}" class="fcc-btn-back">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to CMS Pages
            </a>
        </div>
    </div>

    <!-- Error Alerts Component -->
    @component('admin-panel.validation.errors') @endcomponent

    {!! Form::open(['class' => 'add-cms-form', 'method' => 'post', 'url' => route('adminPanel.cms-pages.update', ['id' => ev($cms->id)]), 'enctype' => 'multipart/form-data', 'id' => 'editCmsForm' ]) !!}

        <!-- 2. Section: Page Details & Content -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <span>Page Content & Copy</span>
            </div>
            <div class="fcc-card-body">
                <div class="fcc-form-field">
                    <label class="fcc-label" for="title">Page Title</label>
                    {!! Form::text('title', $cms->title, ['class' => 'form-control', 'id' => 'title', 'placeholder' => 'Page Title', 'readonly' => 'readonly']) !!}
                </div>

                @if($cms->id == 4 || $cms->id == 5)
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="description">Page Content (HTML)</label>
                        {!! Form::textarea('description', $cms->description, ['class' => 'form-control editor-textarea', 'id' => 'description', 'placeholder' => 'Enter content...', 'rows' => 10]) !!}
                    </div>
                @endif
            </div>
        </div>

        <!-- 3. Section: Search Engine Optimization (SEO) -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="2" y1="12" x2="22" y2="12"></line>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                </svg>
                <span>Search Engine Optimization (SEO)</span>
            </div>
            <div class="fcc-card-body">
                <div class="fcc-form-field">
                    <label class="fcc-label" for="meta_title">Meta Title</label>
                    {!! Form::text('meta_title', $cms->meta_title, ['class' => 'form-control', 'id' => 'meta_title', 'placeholder' => 'SEO Title for browser tab and search engines', ]) !!}
                </div>

                <div class="fcc-form-field">
                    <label class="fcc-label" for="meta_desc">Meta Description</label>
                    {!! Form::textarea('meta_desc', $cms->meta_description, ['class' => 'form-control', 'id' => 'meta_desc', 'rows' => 4, 'placeholder' => 'SEO Description for search engine snippet preview...', ]) !!}
                </div>
            </div>
        </div>

        <!-- 4. Form Footer Actions (Cancel / Update) -->
        <div class="fcc-footer-actions">
            <a href="{{ route('adminPanel.cms-pages.index') }}" class="fcc-btn-cancel">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                Cancel
            </a>
            <button type="submit" class="fcc-btn-save" id="btnUpdateCms">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Update CMS Page
            </button>
        </div>

    {!! Form::close() !!}

</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/cms/cms.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
