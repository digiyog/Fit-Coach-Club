@extends('admin-panel.layouts.main-layout')

@section('page-title', 'CMS Pages | '.__('language.page_main_title').'')

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

    .fcc-cms-wrapper {
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
        max-width: 500px;
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
        padding: 12px 20px;
        min-width: 120px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        box-shadow: 0 8px 24px -4px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.08);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
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

    /* ===== 2. Single White Data Table Container Card ===== */
    .fcc-table-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 16px;
        padding: 22px 24px 24px 24px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
    }

    .fcc-cms-wrapper .data-table-container,
    .fcc-cms-wrapper .table-responsive,
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
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: var(--fcc-dark) !important;
        width: 100% !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
    }

    .fcc-table-card .dataTables_filter input:focus {
        border-color: #1d4ed8 !important;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12) !important;
        outline: none !important;
    }

    .fcc-table-card .dataTables_filter label::before {
        content: '' !important;
        position: absolute !important;
        left: 14px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 16px !important;
        height: 16px !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'%3E%3C/line%3E%3C/svg%3E") !important;
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

    /* Table Typography & Rows */
    .fcc-table-card table.dataTable thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        padding: 13px 16px !important;
        border-top: none !important;
        border-bottom: 1.5px solid var(--fcc-border) !important;
    }

    .fcc-table-card table.dataTable tbody td {
        padding: 14px 16px !important;
        font-size: 13.5px !important;
        color: #1e293b !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    .fcc-table-card table.dataTable tbody tr:hover td {
        background-color: #f8fafc !important;
    }

    /* Action Buttons in Table */
    .fcc-table-card table.dataTable a.badge-primary,
    .fcc-table-card table.dataTable a.bg-primary {
        background: #eff6ff !important;
        color: #1d4ed8 !important;
        border: 1px solid #bfdbfe !important;
        padding: 6px 12px !important;
        border-radius: 8px !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
    }

    /* Pagination */
    .fcc-table-card .dataTables_paginate {
        margin-top: 18px !important;
        display: flex !important;
        justify-content: flex-end !important;
        align-items: center !important;
        gap: 4px !important;
    }

    .fcc-table-card .paginate_button {
        border-radius: 8px !important;
        border: 1px solid var(--fcc-border) !important;
        background: #ffffff !important;
        color: var(--fcc-dark) !important;
        font-weight: 600 !important;
        font-size: 12.5px !important;
        min-width: 34px !important;
        height: 34px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 10px !important;
        margin: 0 2px !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
    }

    .fcc-table-card .paginate_button.current,
    .fcc-table-card .paginate_button.active a {
        background: #1d4ed8 !important;
        color: #ffffff !important;
        border-color: #1d4ed8 !important;
    }
</style>
@endpush

@section('content')
@php
    $totalCms = \App\Models\Cms::count();
@endphp

<div class="layout-px-spacing fcc-cms-wrapper">

    <!-- ===== 1. Executive Hero Header Strip ===== -->
    <div class="fcc-page-hero">
        <div class="fcc-hero-shimmer"></div>
        <div class="fcc-hero-grid"></div>

        <!-- Left: Title & Subtitle -->
        <div class="fcc-hero-content">
            <div class="fcc-hero-pill">
                <span class="fcc-pulse-dot"></span>
                <span>Content Management</span>
            </div>
            <h1 class="fcc-hero-title">CMS Pages</h1>
            <p class="fcc-hero-subtitle">Maintain static pages, policies, terms of service, and public disclosures.</p>
        </div>

        <!-- Right: In-Hero KPI Stat Tiles -->
        <div class="fcc-hero-stats-row">
            <div class="fcc-hero-kpi-tile">
                <div class="fcc-kpi-icon-badge fcc-kpi-icon-blue">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <span class="fcc-kpi-label">Total CMS Pages</span>
                <span class="fcc-kpi-val">{{ $totalCms }}</span>
            </div>
        </div>
    </div>

    <!-- ===== 2. Single White Data Table Container Card ===== -->
    <div class="fcc-table-card">
        <div class="table-responsive">
            <table id="dataTable" class="table table-hover" data-url="{{ route('adminPanel.cms-pages.getCms') }}">
                <thead>
                    <tr>
                        <th>{{ __('language.title') }}</th>
                        <th width="80">{{ __('language.table_action') }}</th>
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
<script src="{{ asset('admin-assets/js/cms/view.js') }}"></script>
@endpush
