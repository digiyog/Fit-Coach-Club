@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Dish Types | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
    /* Dish Types Modern Page Styles */
    .dish-page-wrapper {
        padding: 4px 6px 36px 6px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Top Breadcrumb */
    .dish-breadcrumb {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .dish-breadcrumb a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .dish-breadcrumb a:hover {
        color: #2563eb;
    }

    .dish-breadcrumb .crumb-sep {
        color: #94a3b8;
    }

    .dish-breadcrumb .crumb-active {
        color: #0f172a;
        font-weight: 600;
    }

    /* Page Header */
    .dish-header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .dish-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .dish-subtitle {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 0;
        font-weight: 400;
    }

    .dish-header-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn-catalogue-settings {
        background: #ffffff;
        color: #2563eb;
        border: 1.5px solid #2563eb;
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
        cursor: pointer;
    }

    .btn-catalogue-settings:hover {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-add-dish {
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
        text-decoration: none !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
    }

    .btn-add-dish:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.4);
    }

    /* KPI / Stats Card */
    .dish-stats-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 26px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 14px;
        flex: 1;
        min-width: 140px;
    }

    .stat-icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon-blue {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #2563eb;
    }

    .stat-icon-orange {
        background: #fff7ed;
        border: 1px solid #ffedd5;
        color: #ea580c;
    }

    .stat-icon-red {
        background: #fef2f2;
        border: 1px solid #fee2e2;
        color: #ef4444;
    }

    .stat-number {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        letter-spacing: -0.02em;
    }

    .stat-label {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-top: 4px;
    }

    .stat-divider {
        width: 1px;
        height: 46px;
        background: #f1f5f9;
    }

    .stat-sync-box {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        min-width: 180px;
    }

    .badge-sync-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 700;
    }

    .sync-dot-pulse {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #16a34a;
        display: inline-block;
        box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2);
    }

    .sync-subtext {
        font-size: 12px;
        color: #64748b;
        margin-top: 5px;
    }

    /* Main Catalogue Card */
    .dish-catalogue-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 24px;
    }

    .catalogue-title-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2px;
    }

    .title-accent-pill {
        width: 4px;
        height: 18px;
        background: #2563eb;
        border-radius: 99px;
        flex-shrink: 0;
    }

    .catalogue-heading {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0;
        letter-spacing: -0.01em;
    }

    .catalogue-subheading {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 20px;
    }

    /* Filters Bar */
    .catalogue-filters-row {
        margin-bottom: 16px;
    }

    .filter-search-wrap {
        position: relative;
    }

    .filter-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
    }

    input.filter-search-input,
    #customSearchInput {
        height: 42px !important;
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        padding-left: 42px !important;
        padding-right: 14px !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #0f172a !important;
        background-color: #f8fafc !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
        outline: none !important;
        line-height: normal !important;
        font-family: inherit !important;
    }

    input.filter-search-input:focus,
    #customSearchInput:focus {
        background-color: #ffffff !important;
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    .filter-select {
        height: 42px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        font-size: 13.5px;
        color: #334155;
        font-weight: 500;
        background-color: #ffffff;
        cursor: pointer;
        box-shadow: none !important;
    }

    .filter-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    .btn-more-filters {
        height: 42px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        font-size: 13.5px;
        font-weight: 600;
        padding: 0 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        transition: all 0.2s ease;
    }

    .btn-more-filters:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    /* Actions / Counter Sub-toolbar */
    .catalogue-actions-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }

    .catalogue-actions-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .table-count-text {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .page-len-select {
        height: 36px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        color: #475569;
        padding: 4px 10px;
        font-weight: 500;
        cursor: pointer;
        box-shadow: none !important;
    }

    .catalogue-actions-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-action-tool {
        background: #ffffff;
        border-radius: 9px;
        padding: 7px 14px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .btn-order-update {
        color: #2563eb;
        border: 1.5px solid #2563eb;
        background: #ffffff;
    }

    .btn-order-update:hover {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .btn-status-change {
        color: #64748b;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .btn-status-change:not(:disabled):hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .btn-status-change:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-tool-delete {
        color: #ef4444;
        border: 1px solid #fee2e2;
        background: #fef2f2;
    }

    .btn-tool-delete:not(:disabled):hover {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-tool-delete:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* DataTable Table Custom Styles */
    .data-table-container {
        position: relative;
    }

    /* Hide unstyled datatables default header row */
    .dataTables_wrapper > .row:first-child {
        display: none !important;
    }

    #dataTable {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
        margin-bottom: 0 !important;
    }

    #dataTable thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        text-transform: capitalize !important;
        border-top: none !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 12px 14px !important;
        white-space: nowrap !important;
    }

    #dataTable tbody td {
        padding: 14px 14px !important;
        vertical-align: middle !important;
        font-size: 13.5px !important;
        color: #334155 !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    #dataTable tbody tr:hover {
        background-color: #f8fafc !important;
    }

    /* Badges */
    .badge-app-published {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #059669;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 9999px;
    }

    .badge-app-hidden {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 9999px;
    }

    .badge-status-active {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #059669;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 9999px;
    }

    .badge-status-inactive {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #b45309;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 9999px;
    }

    .status-dot-active {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #10b981;
    }

    .status-dot-inactive {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #f59e0b;
    }

    .btn-dish-edit {
        background: #ffffff;
        color: #2563eb !important;
        border: 1.5px solid #2563eb;
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 12.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s ease;
        text-decoration: none !important;
    }

    .btn-dish-edit:hover {
        background: #eff6ff;
        color: #1d4ed8 !important;
        border-color: #1d4ed8;
    }

    /* Empty State Illustration */
    .custom-empty-state-wrap {
        padding: 56px 20px;
        text-align: center;
        display: none;
    }

    .empty-illustration-badge {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: #eff6ff;
        border: 2px solid #dbeafe;
        margin: 0 auto 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .empty-badge-check {
        position: absolute;
        top: 2px;
        right: 4px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #10b981;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        border: 2px solid #ffffff;
    }

    .empty-sparkle-1 {
        position: absolute;
        top: 8px;
        left: 10px;
        color: #2563eb;
        font-size: 12px;
    }

    .empty-sparkle-2 {
        position: absolute;
        bottom: 12px;
        right: 10px;
        color: #3b82f6;
        font-size: 10px;
    }

    .empty-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .empty-desc {
        font-size: 13.5px;
        color: #64748b;
        max-width: 460px;
        margin: 0 auto 20px auto;
        line-height: 1.5;
    }

    .btn-empty-add {
        background: #2563eb;
        color: #ffffff !important;
        border: none;
        border-radius: 10px;
        padding: 10px 22px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        text-decoration: none !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    }

    .btn-empty-add:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.45);
    }

    /* Bottom Workflow Card */
    .dish-workflow-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px 26px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }

    .workflow-left {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .workflow-title-bar {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .workflow-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .workflow-steps-wrap {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }

    .workflow-step {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .step-icon-bubble {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .step-number {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
    }

    .step-text {
        font-size: 13px;
        font-weight: 500;
        color: #334155;
    }

    .step-arrow-icon {
        color: #94a3b8;
        font-size: 14px;
    }

    .link-workflow-settings {
        color: #2563eb;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: color 0.15s ease;
    }

    .link-workflow-settings:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="dish-page-wrapper">

    <!-- Top Breadcrumb -->
    <div class="dish-breadcrumb">
        <span>Meals & Nutrition</span>
        <span class="crumb-sep">/</span>
        <span class="crumb-active">Dish Types</span>
    </div>

    <!-- Header Section -->
    <div class="dish-header-section">
        <div>
            <h1 class="dish-title">Dish types</h1>
            <p class="dish-subtitle">Create and organize dish categories that members can browse in the mobile app.</p>
        </div>

        <div class="dish-header-actions">
            <button type="button" class="btn btn-catalogue-settings">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="21" x2="4" y2="14"></line>
                    <line x1="4" y1="10" x2="4" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12" y2="3"></line>
                    <line x1="20" y1="21" x2="20" y2="16"></line>
                    <line x1="20" y1="12" x2="20" y2="3"></line>
                    <line x1="1" y1="14" x2="7" y2="14"></line>
                    <line x1="9" y1="8" x2="15" y2="8"></line>
                    <line x1="17" y1="16" x2="23" y2="16"></line>
                </svg>
                <span>App catalogue settings</span>
            </button>

            <a href="{{ route('nutritionPanel.dish-types.create') }}" class="btn btn-add-dish">
                <i class="fa fa-plus"></i>
                <span>Add dish type</span>
            </a>
        </div>
    </div>

    <!-- KPI / Stats Card -->
    <div class="dish-stats-card">
        <!-- Metric 1: Total dish types -->
        <div class="stat-item">
            <div class="stat-icon-circle stat-icon-blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                    <line x1="6" y1="1" x2="6" y2="4"></line>
                    <line x1="10" y1="1" x2="10" y2="4"></line>
                    <line x1="14" y1="1" x2="14" y2="4"></line>
                </svg>
            </div>
            <div>
                <div class="stat-number" id="metricTotal">{{ $totalDishTypes ?? 0 }}</div>
                <div class="stat-label">Total dish types</div>
            </div>
        </div>

        <div class="stat-divider d-none d-md-block"></div>

        <!-- Metric 2: Published to app -->
        <div class="stat-item">
            <div class="stat-icon-circle stat-icon-blue">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                    <polyline points="9 11 12 14 16 9"></polyline>
                </svg>
            </div>
            <div>
                <div class="stat-number" id="metricPublished">{{ $publishedDishTypes ?? 0 }}</div>
                <div class="stat-label">Published to app</div>
            </div>
        </div>

        <div class="stat-divider d-none d-md-block"></div>

        <!-- Metric 3: Drafts -->
        <div class="stat-item">
            <div class="stat-icon-circle stat-icon-orange">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </div>
            <div>
                <div class="stat-number" id="metricDrafts">{{ $draftDishTypes ?? 0 }}</div>
                <div class="stat-label">Drafts</div>
            </div>
        </div>

        <div class="stat-divider d-none d-md-block"></div>

        <!-- Metric 4: Hidden -->
        <div class="stat-item">
            <div class="stat-icon-circle stat-icon-red">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                </svg>
            </div>
            <div>
                <div class="stat-number" id="metricHidden">{{ $hiddenDishTypes ?? 0 }}</div>
                <div class="stat-label">Hidden</div>
            </div>
        </div>

        <div class="stat-divider d-none d-lg-block"></div>

        <!-- Metric 5: Member app synced -->
        <div class="stat-sync-box">
            <span class="badge-sync-pill">
                <span class="sync-dot-pulse"></span>
                Member app synced
            </span>
            <div class="sync-subtext">Active dish types are available to members.</div>
        </div>
    </div>

    <!-- Main Card: Dish Type Catalogue -->
    <div class="dish-catalogue-card">
        <!-- Title bar -->
        <div class="catalogue-title-bar">
            <span class="title-accent-pill"></span>
            <h2 class="catalogue-heading">Dish type catalogue</h2>
        </div>
        <p class="catalogue-subheading">Manage category names, app visibility and display order.</p>

        <!-- Search & Filter Row -->
        <div class="row g-3 catalogue-filters-row">
            <div class="col-lg-5 col-md-12">
                <div class="filter-search-wrap">
                    <i class="fa fa-search filter-search-icon"></i>
                    <input type="text" id="customSearchInput" class="form-control filter-search-input" placeholder="Search dish types..." autocomplete="off" style="padding-left: 42px !important;">
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <select id="filterVisibility" class="form-select filter-select">
                    <option value="all">All visibility</option>
                    <option value="1">Published to app</option>
                    <option value="0">Hidden</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <select id="filterStatus" class="form-select filter-select">
                    <option value="all">All statuses</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="col-lg-3 col-md-4 col-12">
                <button type="button" class="btn btn-more-filters">
                    <span><i class="fa fa-filter me-2 text-primary"></i> More filters</span>
                    <i class="fa fa-chevron-down text-muted"></i>
                </button>
            </div>
        </div>

        <!-- Sub-toolbar Actions Row -->
        <div class="catalogue-actions-row">
            <div class="catalogue-actions-left">
                <span class="table-count-text"><span id="tableDishCount">{{ $totalDishTypes ?? 0 }}</span> dish types</span>
                <select id="customPageLength" class="form-select page-len-select">
                    <option value="20" selected>20 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>

            <div class="catalogue-actions-right">
                <button type="button" class="btn btn-action-tool btn-order-update update-order">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="17 1 21 5 17 9"></polyline>
                        <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                        <polyline points="7 23 3 19 7 15"></polyline>
                        <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                    </svg>
                    <span>Update order</span>
                </button>

                <button type="button" class="btn btn-action-tool btn-status-change change-status" disabled>
                    <i class="fa fa-refresh"></i>
                    <span>Change status</span>
                </button>

                <button type="button" class="btn btn-action-tool btn-tool-delete dt-delete" disabled>
                    <i class="fa fa-trash-o"></i>
                    <span>Delete</span>
                </button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-responsive data-table-container">
            <table id="dataTable" class="table table-hover"
                data-url="{{ route('nutritionPanel.dish-types.getDishTypes') }}"
                data-change-status-url="{{ route('nutritionPanel.dish-types.changeStatus') }}"
                data-destroy-url="{{ route('nutritionPanel.dish-types.destroy') }}"
                data-update-order-url="{{ route('nutritionPanel.dish-types.updateOrder') }}">
                <thead>
                    <tr>
                        <th class="checkbox-column"> # </th>
                        <th>Dish type name</th>
                        <th>App visibility</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
            </table>

            <!-- Empty State Illustration -->
            <div class="custom-empty-state-wrap">
                <div class="empty-illustration-badge">
                    <span class="empty-sparkle-1"><i class="fa fa-sparkles">&#10022;</i></span>
                    <span class="empty-sparkle-2"><i class="fa fa-star">&#9733;</i></span>
                    
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                        <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                    </svg>
                    
                    <div class="empty-badge-check">
                        <i class="fa fa-check"></i>
                    </div>
                </div>
                <h3 class="empty-title">No dish types added yet</h3>
                <p class="empty-desc">Create your first dish category and publish it so members can browse it in the app.</p>
                <a href="{{ route('nutritionPanel.dish-types.create') }}" class="btn btn-empty-add">
                    <i class="fa fa-plus"></i>
                    <span>Add dish type</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Workflow Card: From admin catalogue to member app -->
    <div class="dish-workflow-card">
        <div class="workflow-left">
            <div class="workflow-title-bar">
                <span class="title-accent-pill"></span>
                <h4 class="workflow-title">From admin catalogue to member app</h4>
            </div>
            <div class="workflow-steps-wrap">
                <!-- Step 1 -->
                <div class="workflow-step">
                    <div class="step-icon-bubble">
                        <i class="fa fa-file-text-o"></i>
                    </div>
                    <span class="step-number">1</span>
                    <span class="step-text">Create dish type</span>
                </div>

                <span class="step-arrow-icon">&rarr;</span>

                <!-- Step 2 -->
                <div class="workflow-step">
                    <div class="step-icon-bubble">
                        <i class="fa fa-cog"></i>
                    </div>
                    <span class="step-number">2</span>
                    <span class="step-text">Set visibility and order</span>
                </div>

                <span class="step-arrow-icon">&rarr;</span>

                <!-- Step 3 -->
                <div class="workflow-step">
                    <div class="step-icon-bubble">
                        <i class="fa fa-paper-plane-o"></i>
                    </div>
                    <span class="step-number">3</span>
                    <span class="step-text">Publish to members</span>
                </div>
            </div>
        </div>

        <div>
            <a href="{{ route('nutritionPanel.dish-types.create') }}" class="link-workflow-settings">
                <span>Manage catalogue settings</span>
                <i class="fa fa-arrow-right"></i>
            </a>
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
<script src="{{ asset('admin-assets/js/dish-types/view.js') }}"></script>
@endpush
