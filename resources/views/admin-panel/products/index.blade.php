@extends('admin-panel.layouts.main-layout')

@section('page-title', 'Products | '.__('language.page_main_title').'')

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

    .fcc-products-wrapper {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--fcc-slate);
        padding: 4px 2px 40px 2px;
        width: 100%;
        max-width: 100%;
    }

    /* ===== 1. Executive Hero Header Strip ===== */
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

    /* Visual Accent Icon Box */
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

    /* ===== 2. Single White Data Table Container Card ===== */
    .fcc-table-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 16px;
        padding: 22px 24px 24px 24px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
    }

    /* Remove ANY inner card container / inner border completely */
    .fcc-products-wrapper .data-table-container,
    .fcc-products-wrapper .table-responsive,
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

    .fcc-table-card table.dataTable {
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        margin: 0 !important;
    }

    .fcc-table-card table.dataTable.no-footer {
        border-bottom: 1px solid var(--fcc-border) !important;
    }

    /* DataTable Top Controls Bar */
    .fcc-table-card .dataTables_wrapper .row:first-child {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 14px !important;
        margin: 0 0 18px 0 !important;
        padding: 0 !important;
    }

    .fcc-table-card .dataTables_wrapper .row:first-child > div {
        padding: 0 !important;
        margin: 0 !important;
        flex: 1 1 auto !important;
        max-width: 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 14px !important;
    }

    /* Length Select & Search input */
    .fcc-table-card .dataTables_length {
        display: inline-flex !important;
        align-items: center !important;
        margin: 0 !important;
    }

    .fcc-table-card .dataTables_length label {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: var(--fcc-muted) !important;
        margin-bottom: 0 !important;
    }

    .fcc-table-card .dataTables_length select {
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

    .fcc-table-card .dataTables_filter {
        display: inline-flex !important;
        align-items: center !important;
        margin: 0 !important;
        position: relative !important;
        flex: 1 1 300px !important;
        max-width: 440px !important;
    }

    .fcc-table-card .dataTables_filter label {
        position: relative !important;
        display: block !important;
        margin-bottom: 0 !important;
        width: 100% !important;
    }

    .fcc-table-card .dataTables_filter input {
        height: 40px !important;
        border-radius: 20px !important;
        border: 1.5px solid var(--fcc-border) !important;
        background-color: #ffffff !important;
        padding-left: 40px !important;
        padding-right: 14px !important;
        font-size: 13px !important;
        color: var(--fcc-dark) !important;
        width: 100% !important;
        box-shadow: none !important;
        outline: none !important;
        transition: all 0.2s ease !important;
    }

    .fcc-table-card .dataTables_filter input:focus {
        border-color: var(--fcc-primary) !important;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12) !important;
    }

    .fcc-table-card .dataTables_filter label::before {
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
        background-size: contain !important;
        pointer-events: none !important;
        z-index: 2 !important;
    }

    .fcc-table-card .dataTables_filter svg,
    .fcc-table-card .dataTables_filter i {
        display: none !important;
    }

    /* Buttons Toolbar */
    .fcc-table-card .btn-toolbar {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin: 0 !important;
    }

    .fcc-table-card .btn-toolbar .btn {
        border-radius: 9px !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        padding: 7px 14px !important;
        border: 1px solid var(--fcc-border) !important;
        background: #ffffff !important;
        color: var(--fcc-slate) !important;
        transition: all 0.15s ease !important;
    }

    .fcc-table-card .btn-toolbar .btn:hover:not(:disabled) {
        background: #f8fafc !important;
        border-color: #cbd5e1 !important;
        color: var(--fcc-dark) !important;
    }

    /* Table Styles */
    .fcc-table-card table.dataTable thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        padding: 13px 16px !important;
        border-top: none !important;
        border-bottom: 1.5px solid var(--fcc-border) !important;
        white-space: nowrap !important;
    }

    .fcc-table-card table.dataTable tbody td {
        padding: 14px 16px !important;
        font-size: 13.5px !important;
        color: var(--fcc-slate) !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    .fcc-table-card table.dataTable tbody tr:hover {
        background-color: #f8fafc !important;
    }

    /* Status Badges */
    .badge-success, .badge.bg-success {
        background-color: #dcfce7 !important;
        color: #15803d !important;
        font-weight: 700 !important;
        border-radius: 20px !important;
        padding: 5px 12px !important;
    }

    .badge-danger, .badge.bg-danger {
        background-color: #fee2e2 !important;
        color: #b91c1c !important;
        font-weight: 700 !important;
        border-radius: 20px !important;
        padding: 5px 12px !important;
    }

    /* Action Buttons in Table */
    .fcc-table-card .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--fcc-border);
        background: #ffffff;
        color: #64748b;
        margin: 0 2px;
        transition: all 0.15s ease;
    }

    .fcc-table-card .btn-action:hover {
        color: var(--fcc-primary);
        border-color: #93c5fd;
        background: #eff6ff;
    }
</style>
@endpush

@section('content')
@php
    $totalProducts = \App\Models\Product::count();
    $activeProducts = \App\Models\Product::where('status', 1)->count();
    $inactiveProducts = max(0, $totalProducts - $activeProducts);
@endphp

<div class="layout-px-spacing fcc-products-wrapper">

    <!-- 1. Executive Hero Header Strip -->
    <div class="fcc-page-hero">
        <div class="fcc-hero-shimmer"></div>
        <div class="fcc-hero-grid"></div>

        <!-- Left Information -->
        <div class="fcc-hero-content">
            <div class="fcc-hero-pill">
                <span class="fcc-pulse-dot"></span> Product Catalog System
            </div>
            <div class="fcc-hero-title">
                Products Directory 🏷️
            </div>
            <p class="fcc-hero-subtitle">
                Manage store inventory, product categories, pricing, and distribution rules.
            </p>
        </div>

        <!-- KPI Stat Tiles inside Banner -->
        <div class="fcc-hero-stats-row">
            <div class="fcc-hero-kpi-tile">
                <div class="fcc-kpi-icon-badge fcc-kpi-icon-blue">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
                <span class="fcc-kpi-label">Total Products</span>
                <span class="fcc-kpi-val">{{ number_format($totalProducts) }}</span>
            </div>

            <div class="fcc-hero-kpi-tile">
                <div class="fcc-kpi-icon-badge fcc-kpi-icon-green">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <span class="fcc-kpi-label">Active Products</span>
                <span class="fcc-kpi-val">{{ number_format($activeProducts) }}</span>
            </div>

            <div class="fcc-hero-kpi-tile">
                <div class="fcc-kpi-icon-badge fcc-kpi-icon-red">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </div>
                <span class="fcc-kpi-label">Inactive / Out</span>
                <span class="fcc-kpi-val">{{ number_format($inactiveProducts) }}</span>
            </div>
        </div>

        <!-- Center 3D Package Art -->
        <div class="fcc-hero-art-box">
            <img src="{{ asset('admin-assets/images/product-type-banner-art.svg') }}" alt="Products Banner Art" style="height: 125px; object-fit: contain;">
        </div>

        <!-- Action Buttons -->
        <div class="fcc-hero-actions">
            <a href="{{ route('adminPanel.products.create') }}" class="fcc-btn-add-hero">
                <i class="fa fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    <!-- 2. Single White Data Table Card Container -->
    <div class="fcc-table-card">
        <div class="table-responsive data-table-container">
            <table id="dataTable" class="table table-hover" data-url="{{ route('adminPanel.products.getProducts') }}" data-change-status-url="{{ route('adminPanel.products.changeStatus') }}" data-destroy-url="{{ route('adminPanel.products.destroy') }}" data-update-order-url="{{ route('adminPanel.products.updateOrder') }}">
                <thead>
                    <tr>
                        <th class="checkbox-column">#</th>
                        <th>Product Type</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Short Description</th>
                        <th>Description</th>
                        <th>Order</th>
                        <th>{{ __('language.table_status') }}</th>
                        <th class="text-end">{{ __('language.table_action') }}</th>
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
<script src="{{ asset('admin-assets/js/products/view.js') }}"></script>
@endpush