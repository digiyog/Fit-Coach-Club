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

    /* 1. Breadcrumbs */
    .fcc-breadcrumb-nav {
        margin-bottom: 14px;
    }

    .fcc-breadcrumb-nav .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 13.5px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .fcc-breadcrumb-nav .breadcrumb-item {
        display: inline-flex;
        align-items: center;
        color: #64748b;
    }

    .fcc-breadcrumb-nav .breadcrumb-item a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .fcc-breadcrumb-nav .breadcrumb-item a:hover {
        color: var(--fcc-primary);
    }

    .fcc-breadcrumb-nav .breadcrumb-item.active {
        color: var(--fcc-dark);
        font-weight: 700;
    }

    .fcc-breadcrumb-nav .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #94a3b8;
        font-size: 15px;
        padding: 0 4px;
    }

    /* 2. Hero Banner */
    .fcc-hero-banner {
        background: linear-gradient(135deg, #071536 0%, #0f2766 45%, #1d4ed8 100%);
        border-radius: 18px;
        padding: 24px 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        margin-bottom: 22px;
        box-shadow: 0 10px 30px -8px rgba(15, 39, 102, 0.45);
    }

    .fcc-banner-artwork {
        position: absolute;
        right: 170px;
        top: 50%;
        transform: translateY(-50%);
        height: 145px;
        pointer-events: none;
        z-index: 1;
        opacity: 0.95;
    }

    .fcc-banner-left {
        display: flex;
        align-items: center;
        gap: 18px;
        z-index: 2;
    }

    .fcc-banner-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: #2563eb;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.5);
        flex-shrink: 0;
    }

    .fcc-banner-title {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        font-size: 24px;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.02em;
        margin: 0;
        line-height: 1.2;
    }

    .fcc-banner-subtitle {
        font-size: 13.5px;
        color: #bfdbfe;
        margin: 4px 0 0 0;
        font-weight: 500;
    }

    .fcc-banner-right {
        z-index: 2;
        display: flex;
        align-items: center;
    }

    .fcc-btn-add-type {
        background: #ffffff;
        color: #0f172a !important;
        font-weight: 700;
        font-size: 13.5px;
        padding: 10px 22px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        transition: all 0.2s ease;
    }

    .fcc-btn-add-type:hover {
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.18);
        color: #000000 !important;
    }

    /* 3. 4 KPI Stat Cards */
    .fcc-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    @media (max-width: 1024px) {
        .fcc-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .fcc-stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .fcc-stat-card {
        border-radius: 16px;
        padding: 18px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid;
    }

    .fcc-stat-card:hover {
        transform: translateY(-2px);
    }

    /* Blue Card */
    .fcc-stat-card-blue {
        background: #ffffff;
        border-color: #dbeafe;
        box-shadow: 0 2px 8px rgba(29, 78, 216, 0.04);
    }
    .fcc-stat-card-blue .fcc-stat-icon-wrap {
        background: #e0f2fe;
        color: #0284c7;
    }

    /* Green Card */
    .fcc-stat-card-green {
        background: #ffffff;
        border-color: #bbf7d0;
        box-shadow: 0 2px 8px rgba(22, 163, 74, 0.04);
    }
    .fcc-stat-card-green .fcc-stat-icon-wrap {
        background: #dcfce7;
        color: #16a34a;
    }

    /* Red Card */
    .fcc-stat-card-red {
        background: #ffffff;
        border-color: #fecaca;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.04);
    }
    .fcc-stat-card-red .fcc-stat-icon-wrap {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Purple Card */
    .fcc-stat-card-purple {
        background: #ffffff;
        border-color: #e9d5ff;
        box-shadow: 0 2px 8px rgba(147, 51, 234, 0.04);
    }
    .fcc-stat-card-purple .fcc-stat-icon-wrap {
        background: #f3e8ff;
        color: #9333ea;
    }

    .fcc-stat-icon-wrap {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .fcc-stat-meta {
        display: flex;
        flex-direction: column;
    }

    .fcc-stat-label {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--fcc-muted);
        margin-bottom: 2px;
    }

    .fcc-stat-value {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        font-size: 24px;
        font-weight: 800;
        color: var(--fcc-dark);
        line-height: 1.1;
    }

    /* 4. White Data Table Container */
    .fcc-table-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 16px;
        padding: 20px 24px 24px 24px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
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
    }

    .fcc-dt-search input {
        height: 40px !important;
        border-radius: 20px !important;
        border: 1.5px solid var(--fcc-border) !important;
        background: #ffffff !important;
        padding-left: 38px !important;
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

    .fcc-dt-search label {
        position: relative;
        display: block;
        margin-bottom: 0;
        width: 100%;
    }

    .fcc-dt-search label::before {
        content: "";
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'%3E%3C/line%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: center;
        pointer-events: none;
        z-index: 2;
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

    /* DataTable Table Styling */
    #dataTable {
        border-collapse: separate !important;
        border-spacing: 0 4px !important;
        margin-top: 10px !important;
        width: 100% !important;
    }

    #dataTable thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 12.5px !important;
        font-weight: 700 !important;
        border: none !important;
        padding: 12px 16px !important;
        letter-spacing: 0.01em;
    }

    #dataTable thead th:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    #dataTable thead th:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    #dataTable tbody tr {
        background: #ffffff !important;
        transition: background-color 0.15s ease;
    }

    #dataTable tbody tr:hover {
        background: #f8fafc !important;
    }

    #dataTable tbody td {
        padding: 14px 16px !important;
        vertical-align: middle !important;
        border-top: 1px solid #f1f5f9 !important;
        border-bottom: 1px solid #f1f5f9 !important;
        font-size: 13.5px !important;
        color: var(--fcc-slate) !important;
    }

    #dataTable tbody tr td:first-child {
        border-left: 1px solid #f1f5f9 !important;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    #dataTable tbody tr td:last-child {
        border-right: 1px solid #f1f5f9 !important;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
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

    <!-- 1. Breadcrumbs Navigation -->
    <nav class="fcc-breadcrumb-nav" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('adminPanel.dashboard') }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Product Types</li>
        </ol>
    </nav>

    <!-- 2. Hero Banner with Glowing Package Box Artwork -->
    <div class="fcc-hero-banner">
        <img src="{{ asset('admin-assets/images/product-type-banner-art.svg') }}" alt="Product Types Banner" class="fcc-banner-artwork">

        <div class="fcc-banner-left">
            <div class="fcc-banner-icon-box">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                    <polyline points="2 17 12 22 22 17"></polyline>
                    <polyline points="2 12 12 17 22 12"></polyline>
                </svg>
            </div>
            <div>
                <h1 class="fcc-banner-title">Product Types</h1>
                <p class="fcc-banner-subtitle">Manage all product categories/types available in the system.</p>
            </div>
        </div>

        <div class="fcc-banner-right">
            <a href="{{ route('adminPanel.product-types.create') }}" class="fcc-btn-add-type">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add Product Type
            </a>
        </div>
    </div>

    <!-- 3. 4 KPI Stat Cards -->
    <div class="fcc-stats-grid">
        <!-- Card 1: Total Types -->
        <div class="fcc-stat-card fcc-stat-card-blue">
            <div class="fcc-stat-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                    <polyline points="2 17 12 22 22 17"></polyline>
                    <polyline points="2 12 12 17 22 12"></polyline>
                </svg>
            </div>
            <div class="fcc-stat-meta">
                <span class="fcc-stat-label">Total Types</span>
                <span class="fcc-stat-value">{{ $totalTypes ?? 0 }}</span>
            </div>
        </div>

        <!-- Card 2: Active Types -->
        <div class="fcc-stat-card fcc-stat-card-green">
            <div class="fcc-stat-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div class="fcc-stat-meta">
                <span class="fcc-stat-label">Active Types</span>
                <span class="fcc-stat-value">{{ $activeTypes ?? 0 }}</span>
            </div>
        </div>

        <!-- Card 3: Inactive Types -->
        <div class="fcc-stat-card fcc-stat-card-red">
            <div class="fcc-stat-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </div>
            <div class="fcc-stat-meta">
                <span class="fcc-stat-label">Inactive Types</span>
                <span class="fcc-stat-value">{{ $inactiveTypes ?? 0 }}</span>
            </div>
        </div>

        <!-- Card 4: This Month Added -->
        <div class="fcc-stat-card fcc-stat-card-purple">
            <div class="fcc-stat-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
            </div>
            <div class="fcc-stat-meta">
                <span class="fcc-stat-label">This Month Added</span>
                <span class="fcc-stat-value">{{ $thisMonthAdded ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- 4. White Data Table Card -->
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
<script src="{{ asset('admin-assets/js/product-types/view.js') }}"></script>
@endpush
