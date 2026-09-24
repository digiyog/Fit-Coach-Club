@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Tips & video links | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
    /* Tips & Video Links Modern UI Styles */
    .tips-container {
        padding: 4px 6px 24px 6px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Page Header */
    .tip-header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 22px;
    }

    .tip-breadcrumb {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 6px;
    }

    .tip-breadcrumb span.active {
        color: #0f172a;
        font-weight: 600;
    }

    .tip-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .tip-subtitle {
        font-size: 14px;
        color: #64748b;
        font-weight: 400;
        margin-bottom: 0;
    }

    .tip-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-content-settings {
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

    .btn-content-settings:hover {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-add-tip {
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

    .btn-add-tip:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
    }

    /* Top Metrics Bar */
    .tip-metrics-bar {
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

    .tip-metrics-left {
        display: flex;
        align-items: center;
        gap: 28px;
        flex-wrap: wrap;
        flex: 1;
    }

    .tip-metric-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .tip-metric-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .tip-metric-icon.blue { background: #eff6ff; color: #2563eb; }
    .tip-metric-icon.purple { background: #f5f3ff; color: #7c3aed; }
    .tip-metric-icon.green { background: #ecfdf5; color: #10b981; }
    .tip-metric-icon.amber { background: #fffbeb; color: #f59e0b; }

    .tip-metric-data {
        display: flex;
        flex-direction: column;
    }

    .tip-metric-num {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .tip-metric-label {
        font-size: 12px;
        font-weight: 500;
        color: #64748b;
    }

    .tip-metrics-right-ready {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding-left: 24px;
        border-left: 1px solid #f1f5f9;
    }

    .badge-youtube-ready {
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

    .tip-ready-text {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 0;
    }

    /* Video tips library Main Card */
    .tip-main-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .tip-section-header {
        margin-bottom: 16px;
    }

    .tip-section-title-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2px;
    }

    .tip-section-pill {
        width: 4px;
        height: 18px;
        background: #2563eb;
        border-radius: 99px;
    }

    .tip-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0;
        letter-spacing: -0.01em;
    }

    .tip-section-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 0;
    }

    /* Filter Bar */
    .tip-filter-bar {
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

    .tip-search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 220px;
        display: flex;
        align-items: center;
    }

    .tip-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
        z-index: 10;
    }

    .tip-search-input {
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

    .tip-search-input:focus {
        background: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        outline: none;
    }

    .tip-filter-select {
        height: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #334155;
        padding: 0 28px 0 12px;
        background-color: #ffffff;
        min-width: 140px;
        cursor: pointer;
    }

    .tip-filter-select:focus {
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

    .btn-view-toggle {
        height: 38px;
        width: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        color: #64748b;
        background: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-view-toggle:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    /* Table Toolbar */
    .tip-table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
    }

    .tip-toolbar-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .tip-total-count {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .tip-per-page-select {
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

    .tip-toolbar-right {
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

    /* DataTable Overrides */
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
    .tip-item-name {
        font-weight: 600;
        color: #0f172a;
    }

    .tip-coach-name {
        font-weight: 500;
        color: #475569;
    }

    .btn-watch-link {
        border: 1px solid #2563eb;
        color: #2563eb !important;
        background: #eff6ff;
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 11.5px;
        font-weight: 600;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-watch-link:hover {
        background: #2563eb;
        color: #ffffff !important;
    }

    .btn-watch-link:hover .text-danger {
        color: #ffffff !important;
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

    .btn-tip-edit {
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

    .btn-tip-edit:hover {
        background: #eff6ff;
        color: #1d4ed8 !important;
    }

    .tip-order-input {
        width: 68px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        font-weight: 600;
        font-size: 12.5px;
        text-align: center;
    }

    /* Empty State View */
    .tip-empty-state {
        text-align: center;
        padding: 56px 20px;
        background: #ffffff;
    }

    .tip-empty-illustration-wrap {
        position: relative;
        width: 110px;
        height: 110px;
        margin: 0 auto 18px auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .tip-empty-circle-bg {
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

    .tip-empty-play-badge {
        position: relative;
        z-index: 2;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: #2563eb;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
    }

    .tip-empty-bulb-badge {
        position: absolute;
        bottom: 8px;
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
        z-index: 3;
    }

    .tip-empty-link-badge {
        position: absolute;
        bottom: 8px;
        right: 10px;
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
        z-index: 3;
    }

    .tip-sparkle-1 {
        position: absolute;
        top: 6px;
        right: 14px;
        color: #60a5fa;
        font-size: 16px;
        z-index: 3;
    }

    .tip-sparkle-2 {
        position: absolute;
        bottom: 12px;
        left: 6px;
        color: #93c5fd;
        font-size: 12px;
        z-index: 3;
    }

    .tip-empty-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .tip-empty-desc {
        font-size: 13.5px;
        color: #64748b;
        margin-bottom: 18px;
        max-width: 480px;
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
    .tip-workflow-card {
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
        gap: 20px;
    }

    .tip-workflow-left {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .tip-workflow-steps {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .tip-step-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tip-step-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }

    .tip-step-num {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
    }

    .tip-step-label {
        font-size: 13px;
        color: #334155;
        font-weight: 500;
    }

    .tip-step-arrow {
        color: #2563eb;
        font-size: 13px;
        margin: 0 4px;
    }

    .btn-manage-settings {
        color: #2563eb;
        font-weight: 600;
        font-size: 13.5px;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        padding-left: 20px;
        border-left: 1px solid #e2e8f0;
    }

    .btn-manage-settings:hover {
        color: #1d4ed8;
    }

    @media (max-width: 991px) {
        .tip-metrics-right-ready {
            border-left: none;
            padding-left: 0;
            width: 100%;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }
        .btn-manage-settings {
            border-left: none;
            padding-left: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing tips-container">

    <!-- Page Header -->
    <div class="tip-header-section">
        <div>
            <div class="tip-breadcrumb">
                Achievements & Hub / <span class="active">Tips (Video Links)</span>
            </div>
            <h1 class="tip-title">Tips & video links</h1>
            <p class="tip-subtitle">Share coach-curated videos and practical wellness tips with members.</p>
        </div>
        <div class="tip-header-actions">
            <button type="button" class="btn-content-settings" data-bs-toggle="modal" data-bs-target="#contentSettingsModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                Content settings
            </button>
            <a href="{{ route('nutritionPanel.tips.create') }}" class="btn-add-tip">
                <i class="fa fa-plus"></i> Add video tip
            </a>
        </div>
    </div>

    <!-- Top Metrics Bar -->
    <div class="tip-metrics-bar">
        <div class="tip-metrics-left">
            <!-- Total tips -->
            <div class="tip-metric-item">
                <div class="tip-metric-icon blue">
                    <i class="fa fa-lightbulb-o"></i>
                </div>
                <div class="tip-metric-data">
                    <span class="tip-metric-num" id="metric-total-tips">{{ $totalTips ?? 0 }}</span>
                    <span class="tip-metric-label">Total tips</span>
                </div>
            </div>

            <!-- Coach contributors -->
            <div class="tip-metric-item">
                <div class="tip-metric-icon purple">
                    <i class="fa fa-users"></i>
                </div>
                <div class="tip-metric-data">
                    <span class="tip-metric-num" id="metric-coach-contributors">{{ $coachContributors ?? 0 }}</span>
                    <span class="tip-metric-label">Coach contributors</span>
                </div>
            </div>

            <!-- Published -->
            <div class="tip-metric-item">
                <div class="tip-metric-icon green">
                    <i class="fa fa-paper-plane-o"></i>
                </div>
                <div class="tip-metric-data">
                    <span class="tip-metric-num" id="metric-published-tips">{{ $publishedTips ?? 0 }}</span>
                    <span class="tip-metric-label">Published</span>
                </div>
            </div>

            <!-- Drafts -->
            <div class="tip-metric-item">
                <div class="tip-metric-icon amber">
                    <i class="fa fa-file-text-o"></i>
                </div>
                <div class="tip-metric-data">
                    <span class="tip-metric-num" id="metric-draft-tips">{{ $draftTips ?? 0 }}</span>
                    <span class="tip-metric-label">Drafts</span>
                </div>
            </div>
        </div>

        <!-- Right Ready Status -->
        <div class="tip-metrics-right-ready">
            <span class="badge-youtube-ready">
                <span class="ready-dot"></span> YouTube links ready
            </span>
            <p class="tip-ready-text">Published tips appear in the member app.</p>
        </div>
    </div>

    <!-- Video tips library Main Card -->
    <div class="tip-main-card">
        <!-- Section Header -->
        <div class="tip-section-header">
            <div class="tip-section-title-row">
                <div class="tip-section-pill"></div>
                <h2 class="tip-section-title">Video tips library</h2>
            </div>
            <p class="tip-section-subtitle">Organize coach videos, control their order and publish them to members.</p>
        </div>

        <!-- Filter Bar -->
        <div class="tip-filter-bar">
            <div class="tip-search-input-wrap">
                <i class="fa fa-search tip-search-icon"></i>
                <input type="text" id="tip-search-input" class="tip-search-input" placeholder="Search tips..." autocomplete="off">
            </div>

            <!-- Coach Filter -->
            <select id="filter-coach" class="tip-filter-select">
                <option value="">All coaches</option>
                @if(isset($coaches) && count($coaches) > 0)
                    @foreach($coaches as $coach)
                        <option value="{{ $coach }}">{{ $coach }}</option>
                    @endforeach
                @endif
            </select>

            <!-- Status Filter -->
            <select id="filter-status" class="tip-filter-select">
                <option value="">All statuses</option>
                <option value="1">Active / Published</option>
                <option value="0">Inactive / Draft</option>
            </select>

            <!-- More Filters Dropdown -->
            <div class="dropdown">
                <button class="btn-more-filters dropdown-toggle" type="button" id="moreFiltersDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-filter"></i> More filters <i class="fa fa-chevron-down" style="font-size: 10px; margin-left: 2px;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="moreFiltersDropdown" style="border-radius: 10px; font-size: 13px;">
                    <li><a class="dropdown-item py-2 text-danger" href="javascript:void(0)" id="btnResetFilters"><i class="fa fa-refresh me-2"></i> Reset all filters</a></li>
                </ul>
            </div>

            <!-- Layout View Toggle (cosmetic) -->
            <button type="button" class="btn-view-toggle" title="Grid / List View">
                <i class="fa fa-th-large"></i>
            </button>
        </div>

        <!-- Table Toolbar -->
        <div class="tip-table-toolbar">
            <div class="tip-toolbar-left">
                <span id="tip-count-text" class="tip-total-count">{{ $totalTips ?? 0 }} video tips</span>
                <select id="tip-page-length" class="tip-per-page-select">
                    <option value="20">20 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
            <div class="tip-toolbar-right">
                <button type="button" class="btn-action-outline update-order" title="Update Order">
                    <i class="fa fa-exchange"></i> Update order
                </button>
                <button type="button" class="btn-action-muted change-status" disabled title="Change Status">
                    <i class="fa fa-refresh"></i> Change status
                </button>
                <button type="button" class="btn-action-muted dt-delete" disabled title="Delete">
                    <i class="fa fa-trash"></i> Delete
                </button>
            </div>
        </div>

        <!-- Empty State Container -->
        <div id="tip-empty-state" class="tip-empty-state" style="{{ ($totalTips ?? 0) == 0 ? '' : 'display: none;' }}">
            <div class="tip-empty-illustration-wrap">
                <div class="tip-empty-circle-bg"></div>
                <div class="tip-empty-play-badge">
                    <i class="fa fa-play"></i>
                </div>
                <div class="tip-empty-bulb-badge">
                    <i class="fa fa-lightbulb-o"></i>
                </div>
                <div class="tip-empty-link-badge">
                    <i class="fa fa-link"></i>
                </div>
                <i class="fa fa-star tip-sparkle-1"></i>
                <i class="fa fa-star tip-sparkle-2"></i>
            </div>
            <h3 class="tip-empty-title">No video tips added yet</h3>
            <p class="tip-empty-desc">Add a title, select a coach and paste a YouTube link to share helpful content with members.</p>
            <a href="{{ route('nutritionPanel.tips.create') }}" class="btn-empty-add">
                <i class="fa fa-plus"></i> Add video tip
            </a>
        </div>

        <!-- Table Container -->
        <div class="table-responsive data-table-container mb-2" style="{{ ($totalTips ?? 0) == 0 ? 'display: none;' : '' }}">
            <table id="dataTable" class="table table-hover" data-url="{{ route('nutritionPanel.tips.getTips') }}" data-change-status-url="{{ route('nutritionPanel.tips.changeStatus') }}" data-destroy-url="{{ route('nutritionPanel.tips.destroy') }}" data-update-order-url="{{ route('nutritionPanel.tips.updateOrder') }}">
                <thead>
                    <tr>
                        <th class="checkbox-column text-center"> # </th>
                        <th>Name</th>
                        <th>Coach name</th>
                        <th>YouTube link</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Bottom Workflow Card -->
    <div class="tip-workflow-card">
        <div class="tip-workflow-left">
            <div class="tip-section-title-row">
                <div class="tip-section-pill"></div>
                <h3 class="tip-section-title" style="font-size: 16px;">From coach tip to member app</h3>
            </div>
            <div class="tip-workflow-steps">
                <div class="tip-step-item">
                    <div class="tip-step-icon"><i class="fa fa-file-text-o"></i></div>
                    <span class="tip-step-num">1</span>
                    <span class="tip-step-label">Add title and coach</span>
                </div>
                <i class="fa fa-arrow-right tip-step-arrow"></i>

                <div class="tip-step-item">
                    <div class="tip-step-icon"><i class="fa fa-play-circle"></i></div>
                    <span class="tip-step-num">2</span>
                    <span class="tip-step-label">Paste YouTube link</span>
                </div>
                <i class="fa fa-arrow-right tip-step-arrow"></i>

                <div class="tip-step-item">
                    <div class="tip-step-icon"><i class="fa fa-paper-plane-o"></i></div>
                    <span class="tip-step-num">3</span>
                    <span class="tip-step-label">Publish to members</span>
                </div>
            </div>
        </div>

        <a href="javascript:void(0)" class="btn-manage-settings" data-bs-toggle="modal" data-bs-target="#contentSettingsModal">
            Manage content settings <i class="fa fa-arrow-right ms-1"></i>
        </a>
    </div>
</div>

<!-- Content Settings Modal -->
<div class="modal fade" id="contentSettingsModal" tabindex="-1" aria-labelledby="contentSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 18px 24px;">
                <h5 class="modal-title font-weight-bold" id="contentSettingsModalLabel" style="font-size: 17px; color: #0f172a; font-weight: 700;">
                    <i class="fa fa-sliders text-primary me-2"></i> Content & Video Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <p style="font-size: 13.5px; color: #475569; margin-bottom: 16px;">
                    Configure how video tips are displayed and streamed to members in their mobile app feed.
                </p>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <strong style="font-size: 13px; color: #0f172a; display: block;">Automatic YouTube ID Parser</strong>
                            <span style="font-size: 12px; color: #64748b;">Supports both full YouTube URLs and direct video IDs.</span>
                        </div>
                        <span class="badge bg-success" style="font-size: 11px;">Active</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <strong style="font-size: 13px; color: #0f172a; display: block;">Order Priority Sorting</strong>
                            <span style="font-size: 12px; color: #64748b;">Tips display ascending based on custom Order number.</span>
                        </div>
                        <span class="badge bg-primary" style="font-size: 11px;">Enabled</span>
                    </div>
                </div>
                <div class="alert alert-info py-2 px-3" style="font-size: 12.5px; border-radius: 8px;">
                    <i class="fa fa-info-circle me-1"></i> Only tips marked as <strong>Active</strong> will be visible to members.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 14px 24px;">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600; padding: 7px 20px;">Done</button>
            </div>
        </div>
    </div>
</div>

<!-- Video Preview Page Modal -->
<div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <!-- Content loaded via Ajax -->
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
<script src="{{ asset('admin-assets/js/tips/view.js') }}"></script>
@endpush
