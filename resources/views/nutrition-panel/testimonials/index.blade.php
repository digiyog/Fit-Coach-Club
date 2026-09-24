@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Testimonials | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
    /* Testimonials Modern UI Styles */
    .testimonials-container {
        padding: 4px 6px 24px 6px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Page Header */
    .test-header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 22px;
    }

    .test-breadcrumb {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 6px;
    }

    .test-breadcrumb span.active {
        color: #0f172a;
        font-weight: 600;
    }

    .test-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .test-subtitle {
        font-size: 14px;
        color: #64748b;
        font-weight: 400;
        margin-bottom: 0;
    }

    .test-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-display-settings {
        background: #ffffff;
        color: #2563eb;
        border: 1.5px solid #2563eb;
        border-radius: 10px;
        padding: 8px 18px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .btn-display-settings:hover {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-add-test {
        background: #2563eb;
        color: #ffffff !important;
        border: none;
        border-radius: 10px;
        padding: 9px 20px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        text-decoration: none !important;
    }

    .btn-add-test:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
    }

    /* Top Metrics Bar */
    .test-metrics-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        margin-bottom: 24px;
    }

    .test-metrics-left {
        display: flex;
        align-items: center;
        gap: 28px;
        flex-wrap: wrap;
        flex: 1;
    }

    .test-metric-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .test-metric-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .test-metric-icon.blue { background: #eff6ff; color: #2563eb; }
    .test-metric-icon.purple { background: #f5f3ff; color: #7c3aed; }
    .test-metric-icon.green { background: #ecfdf5; color: #10b981; }
    .test-metric-icon.amber { background: #fffbeb; color: #f59e0b; }

    .test-metric-data {
        display: flex;
        flex-direction: column;
    }

    .test-metric-num {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .test-metric-label {
        font-size: 12px;
        font-weight: 500;
        color: #64748b;
    }

    .test-metrics-right-ready {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding-left: 24px;
        border-left: 1px solid #f1f5f9;
    }

    .badge-mobile-ready {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }

    .ready-dot {
        width: 7px;
        height: 7px;
        background: #10b981;
        border-radius: 50%;
        display: inline-block;
    }

    .test-ready-text {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 0;
    }

    /* Testimonial Library Main Card */
    .test-main-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .test-section-header {
        margin-bottom: 16px;
    }

    .test-section-title-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2px;
    }

    .test-section-pill {
        width: 4px;
        height: 18px;
        background: #2563eb;
        border-radius: 99px;
    }

    .test-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0;
        letter-spacing: -0.01em;
    }

    .test-section-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 0;
    }

    /* Filter Bar */
    .test-filter-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        margin-bottom: 16px;
    }

    .test-search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 220px;
        display: flex;
        align-items: center;
    }

    .test-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
        z-index: 10;
    }

    .test-search-input {
        width: 100%;
        height: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding-left: 44px !important;
        padding-right: 14px !important;
        font-size: 13.5px;
        background: #f8fafc;
        transition: all 0.2s ease;
    }

    .test-search-input:focus {
        background: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        outline: none;
    }

    .test-filter-select {
        height: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #334155;
        padding: 0 28px 0 12px;
        background-color: #ffffff;
        min-width: 130px;
        cursor: pointer;
    }

    .test-filter-select:focus {
        border-color: #3b82f6;
        outline: none;
    }

    .btn-more-filters {
        height: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        padding: 0 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-more-filters:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    /* Table Toolbar */
    .test-table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
    }

    .test-toolbar-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .test-total-count {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .test-per-page-select {
        height: 32px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 500;
        color: #334155;
        padding: 0 24px 0 10px;
        background-color: #ffffff;
        cursor: pointer;
    }

    .test-toolbar-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-action-outline {
        border: 1.5px solid #2563eb;
        color: #2563eb;
        background: #ffffff;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12.5px;
        padding: 6px 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-action-outline:hover {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .btn-action-muted {
        border: 1px solid #e2e8f0;
        color: #94a3b8;
        background: #f8fafc;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12.5px;
        padding: 6px 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        cursor: not-allowed;
    }

    .btn-action-muted.enabled {
        border-color: #3b82f6;
        color: #2563eb;
        background: #ffffff;
        cursor: pointer;
    }

    .btn-action-muted.enabled:hover {
        background: #eff6ff;
    }

    .btn-action-muted.danger-enabled {
        border-color: #ef4444;
        color: #ef4444;
        background: #ffffff;
        cursor: pointer;
    }

    .btn-action-muted.danger-enabled:hover {
        background: #fef2f2;
    }

    /* DataTable Table Overrides */
    #dataTable {
        margin: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
    }

    #dataTable thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-top: none !important;
        padding: 12px 14px !important;
        white-space: nowrap;
    }

    #dataTable tbody td {
        padding: 12px 14px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #334155;
        font-size: 13px;
    }

    #dataTable tbody tr:hover td {
        background-color: #f8fafc !important;
    }

    .dataTables_wrapper .dt-buttons,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_length {
        display: none !important;
    }

    .dataTables_wrapper .dataTables_info {
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
        padding-top: 14px;
    }

    .dataTables_wrapper .dataTables_paginate {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        padding: 5px 12px !important;
        border: 1px solid transparent !important;
        margin: 0 2px !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #2563eb !important;
        color: #ffffff !important;
        border-color: #2563eb !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #f1f5f9 !important;
        color: #0f172a !important;
    }

    /* Badges & Cell Styles */
    .test-item-name {
        font-weight: 600;
        color: #0f172a;
    }

    .badge-type-video {
        background-color: #f5f3ff;
        color: #7c3aed;
        border: 1px solid #ddd6fe;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
    }

    .badge-type-photo {
        background-color: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
    }

    .badge-type-text {
        background-color: #f8fafc;
        color: #64748b;
        border: 1px solid #e2e8f0;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
    }

    .test-media-thumb {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .btn-watch-link {
        border: 1px solid #2563eb;
        color: #2563eb !important;
        background: #eff6ff;
        border-radius: 999px;
        padding: 3px 10px;
        font-size: 11.5px;
        font-weight: 600;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }

    .btn-watch-link:hover {
        background: #2563eb;
        color: #ffffff !important;
    }

    .badge-channel-app {
        background-color: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
    }

    .badge-channel-web {
        background-color: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
    }

    .badge-status-active {
        background-color: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
    }

    .badge-status-inactive {
        background-color: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
    }

    .btn-test-edit {
        border: 1px solid #2563eb;
        color: #2563eb !important;
        background: #ffffff;
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }

    .btn-test-edit:hover {
        background: #eff6ff;
        color: #1d4ed8 !important;
    }

    .test-order-input {
        width: 68px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        font-weight: 600;
        font-size: 12.5px;
    }

    /* Empty State View */
    .test-empty-state {
        text-align: center;
        padding: 56px 20px;
        background: #ffffff;
    }

    .test-empty-illustration-wrap {
        position: relative;
        width: 110px;
        height: 110px;
        margin: 0 auto 18px auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .test-empty-circle-bg {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: #eff6ff;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
    }

    .test-empty-card-wrap {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .test-empty-bubble {
        position: absolute;
        top: -10px;
        font-size: 26px;
        font-weight: 800;
        color: #2563eb;
        font-family: Georgia, serif;
    }

    .test-empty-play-badge {
        position: absolute;
        bottom: 6px;
        left: 10px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid #2563eb;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.15);
    }

    .test-empty-pic-card {
        position: absolute;
        bottom: 2px;
        right: 12px;
        width: 36px;
        height: 32px;
        border-radius: 8px;
        background: #ffffff;
        border: 2px solid #2563eb;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.15);
    }

    .test-sparkle-1 {
        position: absolute;
        top: 6px;
        right: 14px;
        color: #60a5fa;
        font-size: 16px;
        z-index: 3;
    }

    .test-sparkle-2 {
        position: absolute;
        bottom: 12px;
        left: 6px;
        color: #93c5fd;
        font-size: 12px;
        z-index: 3;
    }

    .test-empty-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .test-empty-desc {
        font-size: 13.5px;
        color: #64748b;
        margin-bottom: 18px;
        max-width: 460px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.45;
    }

    .btn-empty-add {
        background: #2563eb;
        color: #ffffff !important;
        border: none;
        border-radius: 10px;
        padding: 9px 22px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        text-decoration: none !important;
    }

    .btn-empty-add:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
    }

    /* Bottom Workflow Card */
    .test-workflow-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        margin-top: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 18px;
    }

    .test-workflow-left {
        display: flex;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .test-workflow-steps {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .test-step-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .test-step-icon-wrap {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .test-step-icon-wrap.blue { background: #eff6ff; color: #2563eb; }
    .test-step-icon-wrap.purple { background: #f5f3ff; color: #7c3aed; }
    .test-step-icon-wrap.indigo { background: #eff6ff; color: #4f46e5; }

    .test-step-num {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .test-step-arrow {
        color: #94a3b8;
        font-size: 14px;
    }

    .test-workflow-link {
        font-size: 13.5px;
        font-weight: 600;
        color: #2563eb;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .test-workflow-link:hover {
        color: #1d4ed8;
        transform: translateX(2px);
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="testimonials-container">

        <!-- Top Header & Actions -->
        <div class="test-header-section">
            <div>
                <div class="test-breadcrumb">
                    Achievements & Hub &nbsp;/&nbsp; <span class="active">Testimonials</span>
                </div>
                <h1 class="test-title">Testimonials</h1>
                <p class="test-subtitle">Manage member success stories across the mobile app and website.</p>
            </div>

            <div class="test-header-actions">
                <button type="button" class="btn-display-settings" data-bs-toggle="modal" data-bs-target="#displaySettingsModal">
                    <i class="fa fa-eye"></i>
                    <span>Display settings</span>
                </button>

                <a href="{{ route('nutritionPanel.testimonials.create') }}" class="btn-add-test">
                    <i class="fa fa-plus"></i>
                    <span>Add testimonial</span>
                </a>
            </div>
        </div>

        <!-- Top Metrics Bar -->
        <div class="test-metrics-bar">
            <div class="test-metrics-left">
                <!-- 1. Total testimonials -->
                <div class="test-metric-item">
                    <div class="test-metric-icon blue">
                        <i class="fa fa-quote-left"></i>
                    </div>
                    <div class="test-metric-data">
                        <span class="test-metric-num" id="stat-total-testimonials">{{ $totalTestimonials ?? 0 }}</span>
                        <span class="test-metric-label">Total testimonials</span>
                    </div>
                </div>

                <!-- 2. Video stories -->
                <div class="test-metric-item">
                    <div class="test-metric-icon purple">
                        <i class="fa fa-play-circle-o"></i>
                    </div>
                    <div class="test-metric-data">
                        <span class="test-metric-num" id="stat-video-stories">{{ $videoStories ?? 0 }}</span>
                        <span class="test-metric-label">Video stories</span>
                    </div>
                </div>

                <!-- 3. Photo stories -->
                <div class="test-metric-item">
                    <div class="test-metric-icon green">
                        <i class="fa fa-picture-o"></i>
                    </div>
                    <div class="test-metric-data">
                        <span class="test-metric-num" id="stat-photo-stories">{{ $photoStories ?? 0 }}</span>
                        <span class="test-metric-label">Photo stories</span>
                    </div>
                </div>

                <!-- 4. Published -->
                <div class="test-metric-item">
                    <div class="test-metric-icon amber">
                        <i class="fa fa-paper-plane-o"></i>
                    </div>
                    <div class="test-metric-data">
                        <span class="test-metric-num" id="stat-published-testimonials">{{ $publishedTestimonials ?? 0 }}</span>
                        <span class="test-metric-label">Published</span>
                    </div>
                </div>
            </div>

            <!-- Right Mobile & Web Ready Box -->
            <div class="test-metrics-right-ready">
                <div class="badge-mobile-ready">
                    <span class="ready-dot"></span> Mobile & web ready
                </div>
                <p class="test-ready-text">Published stories can appear across member channels.</p>
            </div>
        </div>

        <!-- Testimonial Library Main Card -->
        <div class="test-main-card">
            <!-- Section Header -->
            <div class="test-section-header">
                <div class="test-section-title-row">
                    <span class="test-section-pill"></span>
                    <h2 class="test-section-title">Testimonial library</h2>
                </div>
                <p class="test-section-subtitle">Organize, publish and feature member success stories.</p>
            </div>

            <!-- Filter Bar -->
            <div class="test-filter-bar">
                <div class="test-search-input-wrap">
                    <i class="fa fa-search test-search-icon"></i>
                    <input type="text" id="test-search-input" class="form-control test-search-input" placeholder="Search testimonials..." style="padding-left: 44px !important; padding-right: 14px !important;">
                </div>

                <select id="filter-format" class="test-filter-select">
                    <option value="">All formats</option>
                    <option value="video">Video stories</option>
                    <option value="photo">Photo stories</option>
                    <option value="both">Both video & photo</option>
                </select>

                <select id="filter-channel" class="test-filter-select">
                    <option value="">All channels</option>
                    <option value="app">Mobile app</option>
                    <option value="web">Website</option>
                </select>

                <select id="filter-status" class="test-filter-select">
                    <option value="">All statuses</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn-more-filters dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-filter"></i> <span>More filters</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2" style="border-radius: 10px;">
                        <li><a class="dropdown-item py-2 rounded-2" href="javascript:;" id="btnResetFilters"><i class="fa fa-undo me-2 text-muted"></i> Reset all filters</a></li>
                    </ul>
                </div>
            </div>

            <!-- Table Toolbar -->
            <div class="test-table-toolbar">
                <div class="test-toolbar-left">
                    <span class="test-total-count" id="test-count-text">{{ $totalTestimonials ?? 0 }} testimonials</span>
                    <select id="test-page-length" class="test-per-page-select">
                        <option value="20" selected>20 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>

                <div class="test-toolbar-right">
                    <button type="button" class="btn-action-outline update-order">
                        <i class="fa fa-exchange"></i> <span>Update order</span>
                    </button>

                    <button type="button" class="btn-action-muted change-status" disabled>
                        <i class="fa fa-refresh"></i> <span>Change status</span>
                    </button>

                    <button type="button" class="btn-action-muted dt-delete" disabled>
                        <i class="fa fa-trash-o"></i> <span>Delete</span>
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-responsive data-table-container">
                <table id="dataTable" class="table table-hover" data-url="{{ route('nutritionPanel.testimonials.getTestimonials') }}" data-change-status-url="{{ route('nutritionPanel.testimonials.changeStatus') }}" data-destroy-url="{{ route('nutritionPanel.testimonials.destroy') }}" data-update-order-url="{{ route('nutritionPanel.testimonials.updateOrder') }}">
                    <thead>
                        <tr>
                            <th class="checkbox-column" style="width: 40px;"> # </th>
                            <th>NAME</th>
                            <th>STORY TYPE</th>
                            <th>MEDIA</th>
                            <th>DISPLAY CHANNELS</th>
                            <th style="width: 100px;">ORDER</th>
                            <th style="width: 100px;">STATUS</th>
                            <th class="text-end" style="width: 90px;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <!-- Empty State View -->
            <div id="test-empty-state" class="test-empty-state" style="display: none;">
                <div class="test-empty-illustration-wrap">
                    <div class="test-empty-circle-bg"></div>
                    <div class="test-empty-card-wrap">
                        <span class="test-empty-bubble">“</span>
                        <div class="test-empty-play-badge">
                            <i class="fa fa-play"></i>
                        </div>
                        <div class="test-empty-pic-card">
                            <i class="fa fa-picture-o"></i>
                        </div>
                    </div>
                    <span class="test-sparkle-1">✦</span>
                    <span class="test-sparkle-2">✦</span>
                </div>

                <h3 class="test-empty-title">No testimonials added yet</h3>
                <p class="test-empty-desc">Add a member story with a video or image, then publish it to the app or website.</p>

                <a href="{{ route('nutritionPanel.testimonials.create') }}" class="btn-empty-add">
                    <i class="fa fa-plus"></i> Add testimonial
                </a>
            </div>
        </div>

        <!-- Bottom Workflow Card -->
        <div class="test-workflow-card">
            <div class="test-workflow-left">
                <div class="test-section-title-row me-2">
                    <span class="test-section-pill"></span>
                    <h3 class="test-section-title" style="font-size: 15.5px;">From member story to published testimonial</h3>
                </div>

                <div class="test-workflow-steps">
                    <!-- Step 1 -->
                    <div class="test-step-item">
                        <div class="test-step-icon-wrap blue">
                            <i class="fa fa-file-text-o"></i>
                        </div>
                        <span class="test-step-num">1</span>
                        <span>Add member details</span>
                    </div>

                    <span class="test-step-arrow">→</span>

                    <!-- Step 2 -->
                    <div class="test-step-item">
                        <div class="test-step-icon-wrap purple">
                            <i class="fa fa-picture-o"></i>
                        </div>
                        <span class="test-step-num">2</span>
                        <span>Attach video or image</span>
                    </div>

                    <span class="test-step-arrow">→</span>

                    <!-- Step 3 -->
                    <div class="test-step-item">
                        <div class="test-step-icon-wrap indigo">
                            <i class="fa fa-paper-plane-o"></i>
                        </div>
                        <span class="test-step-num">3</span>
                        <span>Publish to app & website</span>
                    </div>
                </div>
            </div>

            <a href="javascript:;" class="test-workflow-link" data-bs-toggle="modal" data-bs-target="#displaySettingsModal">
                <span>Manage display settings</span>
                <i class="fa fa-arrow-right"></i>
            </a>
        </div>

    </div>
</div>

<!-- Modal: Display Settings -->
<div class="modal fade" id="displaySettingsModal" tabindex="-1" aria-labelledby="displaySettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; background: #ffffff; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 18px 24px;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                        <i class="fa fa-sliders"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="displaySettingsModalLabel" style="font-size: 16px; color: #0f172a;">
                            Testimonial Display Settings
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 12px;">Configure channels where member stories appear</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex flex-column gap-3">
                    <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fw-semibold text-dark" style="font-size: 13.5px;">Mobile App Success Feed</span>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="switchMobileApp" checked style="cursor: pointer;">
                            </div>
                        </div>
                        <p class="text-muted mb-0" style="font-size: 12px;">Display active member transformation stories on the mobile app home screen.</p>
                    </div>

                    <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fw-semibold text-dark" style="font-size: 13.5px;">Website Showcase Section</span>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="switchWebsite" checked style="cursor: pointer;">
                            </div>
                        </div>
                        <p class="text-muted mb-0" style="font-size: 12px;">Feature published member testimonials on the public club website carousel.</p>
                    </div>

                    <div class="p-3 rounded-3" style="background: #ecfdf5; border: 1px solid #a7f3d0;">
                        <div class="d-flex align-items-center gap-2 text-success fw-semibold" style="font-size: 13px;">
                            <i class="fa fa-check-circle"></i> Multi-Channel Distribution Ready
                        </div>
                        <p class="text-secondary mb-0 mt-1" style="font-size: 12px;">Active testimonials automatically format properly across phone screens and web browsers.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 14px 24px;">
                <button type="button" class="btn btn-light px-4 py-2 rounded-3 text-secondary fw-semibold" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold" data-bs-dismiss="modal" style="background: #2563eb; border-color: #2563eb;">Save Preferences</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/plugins/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/testimonials/view.js') }}"></script>
@endpush
