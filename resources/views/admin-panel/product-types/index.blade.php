@extends('admin-panel.layouts.main-layout')

@section('page-title', 'Product Types | '.__('language.page_main_title').'')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
    :root {
        --fcc-primary: #1d4ed8;
        --fcc-primary-hover: #1e40af;
        --fcc-dark: #0f172a;
        --fcc-slate: #1e293b;
        --fcc-muted: #64748b;
        --fcc-border: #e2e8f0;
        --fcc-card-bg: #ffffff;
    }

    .fcc-product-types-wrapper {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--fcc-slate);
        padding: 4px 2px 40px 2px;
        width: 100%;
        max-width: 100%;
    }

    /* ===== 1. Page Hero Banner ===== */
    .fcc-page-hero {
        background: linear-gradient(135deg, #020617 0%, #0c1e4a 30%, #1e3a8a 65%, #2563eb 100%);
        border-radius: 20px;
        padding: 28px 32px;
        color: #ffffff;
        margin-bottom: 22px;
        box-shadow: 0 16px 40px -6px rgba(2, 6, 23, 0.55), 0 0 0 1px rgba(59, 130, 246, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 22px;
        position: relative;
        overflow: hidden;
    }

    /* Top-right bright orb glow */
    .fcc-page-hero::before {
        content: '';
        position: absolute;
        top: -80px;
        right: -50px;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.35) 0%, rgba(99, 102, 241, 0.12) 40%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }

    /* Bottom-left subtle purple orb */
    .fcc-page-hero::after {
        content: '';
        position: absolute;
        bottom: -60px;
        left: -40px;
        width: 280px;
        height: 280px;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.18) 0%, transparent 65%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }

    /* Animated shimmer sweep across the banner */
    .fcc-hero-shimmer {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.03) 40%, rgba(255, 255, 255, 0.08) 50%, rgba(255, 255, 255, 0.03) 60%, transparent 100%);
        animation: fccShimmerSweep 6s ease-in-out infinite;
        pointer-events: none;
        z-index: 1;
    }

    @keyframes fccShimmerSweep {
        0% { left: -100%; }
        50% { left: 100%; }
        100% { left: 100%; }
    }

    /* Decorative grid dot pattern */
    .fcc-hero-grid {
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        background-image: radial-gradient(circle, rgba(148, 163, 184, 0.12) 1px, transparent 1px);
        background-size: 24px 24px;
        pointer-events: none;
        z-index: 0;
        opacity: 0.6;
    }

    .fcc-hero-content {
        position: relative;
        z-index: 3;
        max-width: 440px;
    }

    .fcc-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.18) 0%, rgba(59, 130, 246, 0.12) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(16, 185, 129, 0.30);
        color: #a5f3fc;
        font-size: 11.5px;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 30px;
        margin-bottom: 12px;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .fcc-hero-pill .fcc-pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #34d399;
        box-shadow: 0 0 6px rgba(52, 211, 153, 0.8);
        animation: fccPulse 2s infinite;
    }

    @keyframes fccPulse {
        0% { transform: scale(0.95); box-shadow: 0 0 4px rgba(52, 211, 153, 0.8); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 7px rgba(52, 211, 153, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 4px rgba(52, 211, 153, 0); }
    }

    .fcc-hero-title {
        font-size: 26px;
        font-weight: 800;
        color: #ffffff !important;
        margin-bottom: 8px;
        letter-spacing: -0.02em;
        line-height: 1.25;
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        text-shadow: 0 2px 16px rgba(37, 99, 235, 0.3);
    }

    .fcc-hero-subtitle {
        color: #94a3b8;
        font-size: 13.5px;
        font-weight: 500;
        margin-bottom: 0;
        line-height: 1.55;
    }

    /* KPI Stat Tiles */
    .fcc-hero-stats-row {
        display: flex;
        align-items: stretch;
        gap: 12px;
        position: relative;
        z-index: 3;
    }

    .fcc-hero-kpi-tile {
        background: linear-gradient(145deg, rgba(15, 23, 42, 0.75) 0%, rgba(30, 58, 138, 0.50) 100%);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 14px;
        padding: 12px 18px;
        min-width: 110px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        box-shadow: 0 8px 24px -4px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.08);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .fcc-hero-kpi-tile:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 32px -6px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.12);
        border-color: rgba(148, 163, 184, 0.30);
    }

    .fcc-kpi-icon-badge {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
    }

    .fcc-kpi-icon-blue  { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #ffffff; }
    .fcc-kpi-icon-green { background: linear-gradient(135deg, #059669, #10b981); color: #ffffff; }
    .fcc-kpi-icon-red   { background: linear-gradient(135deg, #dc2626, #ef4444); color: #ffffff; }

    .fcc-kpi-label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        margin-bottom: 4px;
        white-space: nowrap;
    }

    .fcc-kpi-val {
        font-size: 24px;
        font-weight: 800;
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        line-height: 1;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
    }

    /* 3D Package Art */
    .fcc-hero-art-box {
        display: none;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        z-index: 2;
        margin: 0 6px;
    }

    @media (min-width: 1200px) {
        .fcc-hero-art-box {
            display: flex;
        }
    }

    /* Action Buttons */
    .fcc-hero-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 3;
    }

    .fcc-btn-add-hero {
        background: #ffffff;
        color: #0f172a !important;
        font-weight: 700;
        font-size: 13.5px;
        padding: 11px 20px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.8);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .fcc-btn-add-hero:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        color: #000000 !important;
        background: #ffffff;
    }

    /* 4. Single White Data Table Container Card */
    .fcc-table-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 16px;
        padding: 22px 24px 24px 24px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
    }

    /* Remove ANY inner card container / inner border completely */
    .fcc-product-types-wrapper .data-table-container,
    .fcc-product-types-wrapper .table-responsive,
    .fcc-table-card .data-table-container,
    .fcc-table-card .table-responsive {
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background: transparent !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow-x: auto !important;
    }

    .fcc-product-types-wrapper table.dataTable,
    .fcc-product-types-wrapper .table-responsive table.dataTable,
    .fcc-product-types-wrapper .data-table-container table.dataTable,
    .fcc-table-card table.dataTable,
    #dataTable {
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        border-collapse: collapse !important;
        border-spacing: 0 !important;
        margin-top: 10px !important;
        margin-bottom: 12px !important;
        width: 100% !important;
        background: transparent !important;
    }

    /* Toolbar Layout */
    .fcc-dt-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .fcc-dt-length {
        display: flex;
        align-items: center;
    }

    .fcc-dt-length select {
        height: 38px !important;
        border-radius: 9px !important;
        border: 1.5px solid var(--fcc-border) !important;
        background: #ffffff !important;
        padding: 4px 10px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: var(--fcc-dark) !important;
        outline: none !important;
        cursor: pointer;
    }

    .fcc-dt-search {
        flex: 1 1 320px;
        max-width: 480px;
        position: relative !important;
    }

    .fcc-dt-search label {
        position: relative !important;
        display: block !important;
        margin-bottom: 0 !important;
        width: 100% !important;
    }

    .fcc-dt-search input {
        height: 40px !important;
        border-radius: 20px !important;
        border: 1.5px solid var(--fcc-border) !important;
        background-color: #ffffff !important;
        background-image: none !important;
        padding-left: 40px !important;
        padding-right: 14px !important;
        font-size: 13px !important;
        color: var(--fcc-dark) !important;
        width: 100% !important;
        box-shadow: none !important;
        transition: all 0.2s ease !important;
    }

    .fcc-dt-search input:focus {
        border-color: var(--fcc-primary) !important;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12) !important;
        outline: none !important;
    }

    /* Single clean CSS-rendered search icon */
    .fcc-dt-search label::before {
        content: "" !important;
        position: absolute !important;
        left: 14px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 16px !important;
        height: 16px !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'%3E%3C/line%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: center !important;
        pointer-events: none !important;
        z-index: 5 !important;
    }

    /* Hide any duplicate SVGs or icons injected by other themes/scripts */
    .fcc-dt-search svg,
    .fcc-dt-search i,
    .fcc-dt-search .feather,
    .fcc-dt-search .feather-search,
    div.dataTables_wrapper div.dataTables_filter svg,
    div.dataTables_wrapper div.dataTables_filter i,
    .fcc-dt-search label::after {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        width: 0 !important;
        height: 0 !important;
        pointer-events: none !important;
    }

    .fcc-dt-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fcc-dt-filter-btn {
        background: #ffffff;
        border: 1.5px solid var(--fcc-border);
        border-radius: 9px;
        padding: 8px 18px;
        font-size: 13px;
        font-weight: 700;
        color: var(--fcc-dark);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .fcc-dt-filter-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .fcc-dt-mass-btn {
        background: #ffffff;
        border: 1.5px solid var(--fcc-border);
        border-radius: 9px;
        padding: 8px 18px;
        font-size: 13px;
        font-weight: 700;
        color: var(--fcc-dark);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .fcc-dt-mass-btn:hover:not(:disabled) {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .fcc-dt-mass-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* DataTable Header Styling */
    #dataTable thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        padding: 13px 16px !important;
        border: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        border-top: none !important;
    }

    #dataTable thead th:first-child {
        border-top-left-radius: 10px !important;
        border-bottom-left-radius: 10px !important;
    }

    #dataTable thead th:last-child {
        border-top-right-radius: 10px !important;
        border-bottom-right-radius: 10px !important;
    }

    /* DataTable Row & Cell Styling */
    #dataTable tbody tr {
        background-color: #ffffff !important;
        border: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        box-shadow: none !important;
        transition: background-color 0.15s ease;
    }

    #dataTable tbody tr:hover {
        background-color: #f8fafc !important;
    }

    #dataTable tbody td {
        padding: 14px 16px !important;
        vertical-align: middle !important;
        border: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        font-size: 13.5px !important;
        color: var(--fcc-slate) !important;
        background: transparent !important;
    }

    #dataTable tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Row Number */
    .fcc-row-idx {
        font-weight: 700;
        color: #64748b;
        font-size: 13px;
    }

    /* Type Name with Icon Box */
    .fcc-type-name-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fcc-type-icon-box {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .fcc-type-title {
        font-weight: 700;
        color: var(--fcc-dark);
        font-size: 13.5px;
    }

    /* Status Pill Badges */
    .fcc-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 700;
    }

    .fcc-status-active {
        background: #dcfce7;
        color: #15803d;
    }

    .fcc-status-inactive {
        background: #fee2e2;
        color: #b91c1c;
    }

    /* Date Cell */
    .fcc-date-cell {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #475569;
        font-size: 13px;
        font-weight: 500;
    }

    /* Action Buttons */
    .fcc-action-btns {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .fcc-btn-action-edit {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #2563eb !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        text-decoration: none !important;
    }

    .fcc-btn-action-edit:hover {
        background: #dbeafe;
        color: #1d4ed8 !important;
        transform: translateY(-1px);
    }

    .fcc-btn-action-delete {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #fef2f2;
        border: 1px solid #fee2e2;
        color: #dc2626;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        cursor: pointer;
    }

    .fcc-btn-action-delete:hover {
        background: #fee2e2;
        color: #b91c1c;
        transform: translateY(-1px);
    }

    /* Footer Pagination */
    .fcc-dt-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 18px;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
    }

    .fcc-dt-info {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }

    .fcc-dt-paginate .pagination {
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .fcc-dt-paginate .page-item .page-link {
        width: 34px;
        height: 34px;
        border-radius: 8px !important;
        border: 1px solid var(--fcc-border) !important;
        background: #ffffff !important;
        color: var(--fcc-dark) !important;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        padding: 0;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .fcc-dt-paginate .page-item.active .page-link {
        background: var(--fcc-primary) !important;
        border-color: var(--fcc-primary) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(29, 78, 216, 0.35);
    }

    .fcc-dt-paginate .page-item.disabled .page-link {
        opacity: 0.45;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .fcc-hero-banner {
            padding: 20px;
        }
        .fcc-banner-artwork {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing fcc-product-types-wrapper">

    <!-- 1. Executive Hero Header Strip (Matches Franchises Directory) -->
    <div class="fcc-page-hero">
        <div class="fcc-hero-shimmer"></div>
        <div class="fcc-hero-grid"></div>

        <!-- Left Information -->
        <div class="fcc-hero-content">
            <div class="fcc-hero-pill">
                <span class="fcc-pulse-dot"></span> Product Management System
            </div>
            <div class="fcc-hero-title">
                Product Types Directory 🏆
            </div>
            <p class="fcc-hero-subtitle">
                Real-time roster of registered product categories, active inventory types, and status.
            </p>
        </div>

        <!-- KPI Stat Tiles inside Banner -->
        <div class="fcc-hero-stats-row">
            <div class="fcc-hero-kpi-tile">
                <div class="fcc-kpi-icon-badge fcc-kpi-icon-blue">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                        <polyline points="2 17 12 22 22 17"></polyline>
                        <polyline points="2 12 12 17 22 12"></polyline>
                    </svg>
                </div>
                <span class="fcc-kpi-label">Total Types</span>
                <span class="fcc-kpi-val">{{ number_format($totalTypes ?? 0) }}</span>
            </div>

            <div class="fcc-hero-kpi-tile">
                <div class="fcc-kpi-icon-badge fcc-kpi-icon-green">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <span class="fcc-kpi-label">Active Types</span>
                <span class="fcc-kpi-val">{{ number_format($activeTypes ?? 0) }}</span>
            </div>

            <div class="fcc-hero-kpi-tile">
                <div class="fcc-kpi-icon-badge fcc-kpi-icon-red">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </div>
                <span class="fcc-kpi-label">Inactive / Exp.</span>
                <span class="fcc-kpi-val">{{ number_format($inactiveTypes ?? 0) }}</span>
            </div>
        </div>

        <!-- Center 3D Package Art -->
        <div class="fcc-hero-art-box">
            <img src="{{ asset('admin-assets/images/product-type-banner-art.svg') }}" alt="Product Types Art" style="height: 125px; object-fit: contain;">
        </div>

        <!-- Action Buttons -->
        <div class="fcc-hero-actions">
            <a href="{{ route('adminPanel.product-types.create') }}" class="fcc-btn-add-hero">
                <i class="fa fa-plus"></i> Add Product Type
            </a>
        </div>
    </div>

    <!-- 4. Single White Data Table Card Container -->
    <div class="fcc-table-card">
        <div class="table-responsive data-table-container">
            <table id="dataTable" class="table" data-url="{{ route('adminPanel.product-types.getProductTypes') }}" data-change-status-url="{{ route('adminPanel.product-types.changeStatus') }}" data-destroy-url="{{ route('adminPanel.product-types.destroy') }}">
                <thead>
                    <tr>
                        <th class="checkbox-column">#</th>
                        <th>#</th>
                        <th>Type Name</th>
                        <th>Status</th>
                        <th>Created On</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
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
<script src="{{ asset('admin-assets/js/product-types/view.js') }}?v={{ file_exists(public_path('admin-assets/js/product-types/view.js')) ? filemtime(public_path('admin-assets/js/product-types/view.js')) : time() }}"></script>
@endpush
