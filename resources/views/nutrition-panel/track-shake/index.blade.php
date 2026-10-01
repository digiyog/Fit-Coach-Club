@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Shake Tracking | ' . ($user->name ?? 'User') . ' | ' . __('language.page_main_title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/plugins/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">

<style type="text/css">
    /* Global Scoped Page Styles */
    :root {
        --fcc-primary: #3b46f1;
        --fcc-primary-light: #eff2fe;
        --fcc-text-dark: #0f172a;
        --fcc-muted: #64748b;
        --fcc-border: #edf2f7;
        --fcc-card-bg: #ffffff;
        --fcc-bg: #f8fafc;
        --fcc-green: #10b981;
        --fcc-green-light: #dcfce7;
        --fcc-purple: #9333ea;
        --fcc-purple-light: #f3e8ff;
    }

    .fcc-shake-page-wrap {
        font-family: 'Outfit', sans-serif !important;
        color: var(--fcc-text-dark);
        padding: 6px 4px 40px 4px;
    }

    /* 1. Breadcrumbs */
    .fcc-breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--fcc-muted);
        margin-bottom: 12px;
    }
    .fcc-breadcrumb-nav a {
        color: var(--fcc-muted);
        text-decoration: none;
        transition: color 0.15s ease;
    }
    .fcc-breadcrumb-nav a:hover {
        color: var(--fcc-primary);
    }
    .fcc-breadcrumb-sep {
        color: #cbd5e1;
        font-size: 11px;
    }
    .fcc-breadcrumb-active {
        color: var(--fcc-primary);
        font-weight: 600;
    }

    /* 2. Header */
    .fcc-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }
    .fcc-page-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--fcc-text-dark);
        margin: 0;
        line-height: 1.2;
    }
    .fcc-page-subtitle {
        font-size: 13.5px;
        color: var(--fcc-muted);
        margin: 4px 0 0 0;
    }
    .fcc-header-btns {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .fcc-btn-export {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        color: #334155;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 18px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .fcc-btn-export:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    .fcc-btn-export svg {
        width: 15px;
        height: 15px;
        color: var(--fcc-primary);
    }
    .fcc-btn-adjust-shakes {
        background: var(--fcc-primary);
        border: 1.5px solid var(--fcc-primary);
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 20px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(59, 70, 241, 0.25);
        transition: all 0.15s ease;
        cursor: pointer;
        text-decoration: none;
    }
    .fcc-btn-adjust-shakes:hover {
        background: #2b35d8;
        border-color: #2b35d8;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(59, 70, 241, 0.35);
    }

    /* 3. Sub-Navigation Tabs Ribbon */
    .fcc-member-nav-tabs {
        display: flex;
        align-items: center;
        gap: 28px;
        border-bottom: 1.5px solid #e2e8f0;
        margin-bottom: 24px;
        overflow-x: auto;
        padding-bottom: 0;
    }
    .fcc-tab-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0 2px 12px 2px;
        font-size: 13.5px;
        font-weight: 500;
        color: #64748b;
        text-decoration: none;
        position: relative;
        white-space: nowrap;
        transition: all 0.15s ease;
    }
    .fcc-tab-link svg {
        width: 16px;
        height: 16px;
        color: #94a3b8;
        transition: all 0.15s ease;
    }
    .fcc-tab-link:hover {
        color: var(--fcc-primary);
    }
    .fcc-tab-link:hover svg {
        color: var(--fcc-primary);
    }
    .fcc-tab-link.active {
        color: var(--fcc-primary);
        font-weight: 600;
    }
    .fcc-tab-link.active svg {
        color: var(--fcc-primary);
    }
    .fcc-tab-link.active::after {
        content: '';
        position: absolute;
        bottom: -1.5px;
        left: 0;
        right: 0;
        height: 2.5px;
        background: var(--fcc-primary);
        border-radius: 2px 2px 0 0;
    }

    /* 4. Top 3 Metric Cards Grid */
    .fcc-metric-cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 991px) {
        .fcc-metric-cards-grid {
            grid-template-columns: 1fr;
        }
    }
    .fcc-metric-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .fcc-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
    }
    .fcc-metric-icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .fcc-metric-icon-circle.blue {
        background: #eff2fe;
        color: #3b46f1;
    }
    .fcc-metric-icon-circle.green {
        background: #dcfce7;
        color: #16a34a;
    }
    .fcc-metric-icon-circle.purple {
        background: #f3e8ff;
        color: #9333ea;
    }
    .fcc-metric-icon-circle svg {
        width: 22px;
        height: 22px;
    }
    .fcc-metric-content {
        flex: 1;
        min-width: 0;
    }
    .fcc-metric-label {
        font-size: 12.5px;
        color: var(--fcc-muted);
        font-weight: 500;
        margin-bottom: 2px;
    }
    .fcc-metric-value {
        font-size: 22px;
        font-weight: 700;
        color: var(--fcc-text-dark);
        line-height: 1.2;
    }
    .fcc-metric-subtext {
        font-size: 11.5px;
        color: #94a3b8;
        margin-top: 3px;
        font-weight: 500;
    }

    /* 5. Main Card Styles */
    .fcc-white-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        margin-bottom: 24px;
    }
    .fcc-card-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }
    .fcc-card-heading {
        font-size: 17px;
        font-weight: 700;
        color: var(--fcc-text-dark);
        margin: 0;
    }
    .fcc-card-subheading {
        font-size: 12.5px;
        color: var(--fcc-muted);
        margin-bottom: 0;
        margin-top: 2px;
    }

    /* Chart Controls & Badges */
    .fcc-chart-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .fcc-range-badge-pill {
        background: #3b46f1;
        color: #ffffff;
        font-size: 12px;
        font-weight: 600;
        padding: 5px 14px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
    }
    .fcc-toggle-btn-group {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        padding: 3px;
        border-radius: 9px;
    }
    .fcc-toggle-btn {
        padding: 5px 14px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        background: transparent;
        color: #64748b;
        border-radius: 7px;
        cursor: pointer;
        transition: all 0.15s ease;
        font-family: 'Outfit', sans-serif;
    }
    .fcc-toggle-btn.active {
        background: #3b46f1;
        color: #ffffff;
        box-shadow: 0 1px 3px rgba(59, 70, 241, 0.25);
    }

    /* Chart Quick Stats Ribbon */
    .fcc-chart-quick-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        padding: 12px 18px;
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        margin-bottom: 16px;
    }
    @media (max-width: 768px) {
        .fcc-chart-quick-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    .fcc-cstat-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .fcc-cstat-lbl {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .fcc-cstat-val {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }

    /* Chart Legend Bar */
    .fcc-shake-legend-bar {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
    }
    .fcc-shake-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .fcc-legend-circle {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    .fcc-legend-circle.green { background: #10b981; }
    .fcc-legend-circle.red { background: #ef4444; }
    .fcc-legend-circle.blue { background: #3b46f1; }
    .fcc-legend-circle.orange { background: #f59e0b; }

    /* ApexChart Text Fixes */
    .apexcharts-canvas {
        font-family: 'Outfit', sans-serif !important;
    }
    .apexcharts-text, .apexcharts-text tspan {
        font-family: 'Outfit', sans-serif !important;
    }
    .apexcharts-yaxis-label, .apexcharts-xaxis-label {
        fill: #94a3b8 !important;
        font-weight: 500 !important;
        font-size: 11.5px !important;
    }

    /* 6. Table Controls Group */
    .fcc-table-controls-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .fcc-table-search-box {
        position: relative !important;
        min-width: 200px;
        display: flex !important;
        align-items: center !important;
    }
    .fcc-table-search-box svg,
    .fcc-table-search-box i,
    .fcc-table-search-box .feather,
    .fcc-table-search-box .feather-search {
        position: absolute !important;
        left: 14px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 15px !important;
        height: 15px !important;
        color: #94a3b8 !important;
        pointer-events: none !important;
        margin: 0 !important;
        padding: 0 !important;
        z-index: 5 !important;
    }
    input.fcc-table-search-input,
    .fcc-table-search-input {
        width: 100% !important;
        height: 38px !important;
        padding: 0 14px 0 42px !important;
        padding-left: 42px !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 9px !important;
        font-size: 12.5px !important;
        font-family: 'Outfit', sans-serif !important;
        color: #0f172a !important;
        background: #ffffff !important;
        transition: all 0.15s ease !important;
    }
    input.fcc-table-search-input:focus,
    .fcc-table-search-input:focus {
        border-color: #3b46f1 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.1) !important;
    }
    .fcc-filter-select {
        height: 38px;
        padding: 0 28px 0 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 9px;
        font-size: 12.5px;
        font-weight: 600;
        font-family: 'Outfit', sans-serif;
        color: #334155;
        background: #ffffff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") no-repeat right 10px center/10px 10px;
        cursor: pointer;
        appearance: none;
    }
    .fcc-filter-select:focus {
        border-color: #3b46f1;
        outline: none;
    }

    /* Modern Table */
    .fcc-modern-table-wrap {
        width: 100%;
        overflow-x: auto;
        margin-top: 10px;
    }
    #dataTable {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        margin-bottom: 0 !important;
    }
    #dataTable thead th {
        position: relative !important;
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        padding: 13px 28px 13px 16px !important;
        border-top: none !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-left: none !important;
        border-right: none !important;
        white-space: nowrap;
        vertical-align: middle !important;
    }
    #dataTable thead th.no-sort,
    #dataTable thead th.no-content,
    #dataTable thead th:first-child,
    #dataTable thead th:last-child {
        padding-right: 16px !important;
        padding-left: 16px !important;
    }
    #dataTable thead th.text-end,
    #dataTable thead th:last-child {
        text-align: right !important;
    }

    /* Modern Sorting Indicators */
    #dataTable thead th.sorting:before,
    #dataTable thead th.sorting_asc:before,
    #dataTable thead th.sorting_desc:before {
        position: absolute !important;
        right: 10px !important;
        top: 42% !important;
        transform: translateY(-50%) !important;
        content: "▲" !important;
        font-size: 8px !important;
        color: #94a3b8 !important;
        opacity: 0.35 !important;
        line-height: 1 !important;
        display: block !important;
        bottom: auto !important;
    }

    #dataTable thead th.sorting:after,
    #dataTable thead th.sorting_asc:after,
    #dataTable thead th.sorting_desc:after {
        position: absolute !important;
        right: 10px !important;
        top: 58% !important;
        transform: translateY(-50%) !important;
        content: "▼" !important;
        font-size: 8px !important;
        color: #94a3b8 !important;
        opacity: 0.35 !important;
        line-height: 1 !important;
        display: block !important;
        bottom: auto !important;
    }

    #dataTable thead th.sorting_asc:before {
        opacity: 1 !important;
        color: var(--fcc-primary) !important;
    }
    #dataTable thead th.sorting_asc:after {
        opacity: 0.15 !important;
    }

    #dataTable thead th.sorting_desc:after {
        opacity: 1 !important;
        color: var(--fcc-primary) !important;
    }
    #dataTable thead th.sorting_desc:before {
        opacity: 0.15 !important;
    }

    #dataTable thead th:first-child:before,
    #dataTable thead th:first-child:after,
    #dataTable thead th:last-child:before,
    #dataTable thead th:last-child:after,
    #dataTable thead th.no-sort:before,
    #dataTable thead th.no-sort:after,
    #dataTable thead th.no-content:before,
    #dataTable thead th.no-content:after {
        display: none !important;
    }

    #dataTable tbody td {
        padding: 13px 16px !important;
        vertical-align: middle !important;
        font-size: 13px !important;
        color: #334155 !important;
        font-weight: 500 !important;
        border-bottom: 1px solid #f1f5f9 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        background-color: #ffffff !important;
    }
    #dataTable tbody tr:hover td {
        background-color: #fafbfd !important;
    }

    /* Table Footer & Pagination */
    .fcc-dt-footer {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 14px !important;
        padding-top: 16px !important;
        border-top: 1px solid #f1f5f9 !important;
        margin-top: 10px !important;
    }
    div.dataTables_wrapper div.dataTables_info {
        border: none !important;
        background: transparent !important;
        padding: 0 !important;
        font-size: 13px !important;
        color: #64748b !important;
        font-weight: 500 !important;
    }
    div.dataTables_wrapper div.dataTables_paginate ul.pagination {
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
    }
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li a,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li .page-link {
        min-width: 32px !important;
        height: 32px !important;
        padding: 0 10px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        background: #ffffff !important;
        color: #475569 !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        text-decoration: none !important;
    }
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.active a,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.active .page-link {
        background: #3b46f1 !important;
        border-color: #3b46f1 !important;
        color: #ffffff !important;
        font-weight: 700 !important;
    }

    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dt-buttons {
        display: none !important;
    }

    /* Responsive Media Queries (Mobile & Tablet) */
    @media (max-width: 991px) {
        .fcc-shake-summary-grid {
            grid-template-columns: 1fr !important;
            gap: 12px !important;
        }
        .fcc-shake-charts-grid {
            grid-template-columns: 1fr !important;
        }
    }

    @media (max-width: 768px) {
        .fcc-shake-page-wrap {
            padding: 8px 4px 30px 4px !important;
        }
        .fcc-page-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px !important;
        }
        .fcc-header-btns {
            width: 100% !important;
        }
        .fcc-btn-history-link, .fcc-btn-primary-action {
            width: 100% !important;
            justify-content: center !important;
        }
        .fcc-member-nav-tabs {
            gap: 16px !important;
            -webkit-overflow-scrolling: touch !important;
        }
        .fcc-card-section-header {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 10px !important;
        }
        .fcc-table-controls-group {
            width: 100% !important;
            flex-direction: column !important;
            align-items: stretch !important;
        }
        .fcc-table-search-box {
            width: 100% !important;
        }
    }
</style>
@endpush

@section('content')
@php
    $userEncryptedId = ev($user->id);
@endphp

<div class="fcc-shake-page-wrap">

    <!-- 1. Breadcrumbs -->
    <div class="fcc-breadcrumb-nav">
        <a href="{{ route('nutritionPanel.users.index') }}">User management</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <a href="{{ route('nutritionPanel.users.index') }}">All users</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <span class="fcc-breadcrumb-active">Shake tracking</span>
    </div>

    <!-- 2. Header -->
    <div class="fcc-page-header">
        <div>
            <h1 class="fcc-page-title">Shake tracking</h1>
            <p class="fcc-page-subtitle">{{ $user->name ?? 'Member' }} · Review shake balance and attendance-linked activity</p>
        </div>
        <div class="fcc-header-btns">
            <button type="button" class="btn fcc-btn-export" id="fccExportBtn">
                <i data-feather="download"></i>
                <span>Export</span>
            </button>
            <div class="dropdown d-inline-block">
                <button type="button" class="btn fcc-btn-adjust-shakes dropdown-toggle" data-bs-toggle="dropdown" data-toggle="dropdown" aria-expanded="false">
                    <i data-feather="plus" style="width: 14px; height: 14px;"></i>
                    <span>+ / - Adjust shakes</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius: 12px; min-width: 185px; padding: 6px; font-family: 'Outfit', sans-serif;">
                    <a class="dropdown-item py-2 px-3 rounded-2 add-user-days cursor-pointer" data-url="{{ route('nutritionPanel.users.addUserDays', ['id' => $userEncryptedId]) }}">
                        <i class="fa fa-plus-circle me-2 text-success"></i> Add Shakes / Days
                    </a>
                    <a class="dropdown-item py-2 px-3 rounded-2 subtract-user-days cursor-pointer" data-url="{{ route('nutritionPanel.users.subtractUserDays', ['id' => $userEncryptedId]) }}">
                        <i class="fa fa-minus-circle me-2 text-danger"></i> Subtract Shakes / Days
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Sub-Navigation Tabs Ribbon -->
    <div class="fcc-member-nav-tabs">
        <a href="{{ route('nutritionPanel.users.viewWeights', ['id' => $userEncryptedId]) }}" class="fcc-tab-link">
            <i data-feather="activity"></i>
            <span>Weight</span>
        </a>
        <a href="{{ route('nutritionPanel.users.viewAttendance', ['id' => $userEncryptedId]) }}" class="fcc-tab-link">
            <i data-feather="calendar"></i>
            <span>Attendance</span>
        </a>
        <a href="{{ route('nutritionPanel.manual-attendances.manual-attendance', ['id' => $userEncryptedId]) }}" class="fcc-tab-link">
            <i data-feather="check-square"></i>
            <span>Manual attendance</span>
        </a>
        <a href="{{ route('nutritionPanel.track-shake.index', ['id' => $userEncryptedId]) }}" class="fcc-tab-link active">
            <i data-feather="coffee"></i>
            <span>Shake tracking</span>
        </a>
        <a href="{{ route('nutritionPanel.orders.index', ['id' => $userEncryptedId]) }}" class="fcc-tab-link">
            <i data-feather="shopping-cart"></i>
            <span>Purchases</span>
        </a>
    </div>

    <!-- 4. Top 3 Metric Cards Grid -->
    <div class="fcc-metric-cards-grid">
        <!-- Card 1: Current shake balance -->
        <div class="fcc-metric-card">
            <div class="fcc-metric-icon-circle purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                    <line x1="6" y1="1" x2="6" y2="4"></line>
                    <line x1="10" y1="1" x2="10" y2="4"></line>
                    <line x1="14" y1="1" x2="14" y2="4"></line>
                </svg>
            </div>
            <div class="fcc-metric-content">
                <div class="fcc-metric-label">Current shake balance</div>
                <div class="fcc-metric-value">
                    {{ $currentBalance ?? 0 }}
                </div>
                <div class="fcc-metric-subtext">After latest update</div>
            </div>
        </div>

        <!-- Card 2: Latest activity -->
        <div class="fcc-metric-card">
            <div class="fcc-metric-icon-circle green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div class="fcc-metric-content">
                <div class="fcc-metric-label">Latest activity</div>
                <div class="fcc-metric-value">
                    {{ $latestActivityText }}
                </div>
                <div class="fcc-metric-subtext">
                    {{ !empty($latestActivityDate) ? $latestActivityDate : 'Recorded update' }}
                </div>
            </div>
        </div>

        <!-- Card 3: Primary source -->
        <div class="fcc-metric-card">
            <div class="fcc-metric-icon-circle blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                    <line x1="12" y1="18" x2="12.01" y2="18"></line>
                </svg>
            </div>
            <div class="fcc-metric-content">
                <div class="fcc-metric-label">Primary source</div>
                <div class="fcc-metric-value">
                    {{ $primarySource }}
                </div>
                <div class="fcc-metric-subtext">{{ $primarySourceSubtext }}</div>
            </div>
        </div>
    </div>

    <!-- 5. Main Card 1: Shake balance trend (ApexChart) -->
    <div class="fcc-white-card">
        <div class="fcc-card-section-header">
            <div>
                <h3 class="fcc-card-heading">Shake balance trend</h3>
                <p class="fcc-card-subheading">Daily balance after shake and attendance updates</p>
            </div>
            <div class="fcc-chart-controls">
                <div class="fcc-toggle-btn-group">
                    <button type="button" class="fcc-toggle-btn" data-chart-days="7">7 days</button>
                    <button type="button" class="fcc-toggle-btn active" data-chart-days="30">30 days</button>
                    <button type="button" class="fcc-toggle-btn" data-chart-days="all">All time</button>
                </div>
                <div class="fcc-range-badge-pill" id="fccTrendRangeBadge">
                    {{ $trendRangeText }}
                </div>
            </div>
        </div>

        <!-- Chart Quick Stats Ribbon -->
        <div class="fcc-chart-quick-stats">
            <div class="fcc-cstat-item">
                <span class="fcc-cstat-lbl">Current Balance</span>
                <span class="fcc-cstat-val" style="color: #3b46f1;">{{ $currentBalance }} Shakes</span>
            </div>
            <div class="fcc-cstat-item">
                <span class="fcc-cstat-lbl">Total Added</span>
                <span class="fcc-cstat-val" style="color: #10b981;">+{{ $totalShakesAdded }} Shakes</span>
            </div>
            <div class="fcc-cstat-item">
                <span class="fcc-cstat-lbl">Total Consumed</span>
                <span class="fcc-cstat-val" style="color: #ef4444;">-{{ $totalShakesUsed }} Shakes</span>
            </div>
            <div class="fcc-cstat-item">
                <span class="fcc-cstat-lbl">Log Entries</span>
                <span class="fcc-cstat-val">{{ count($chartDataPoints) }} Records</span>
            </div>
        </div>

        <!-- ApexChart Container -->
        <div id="shakeBalanceApexChart" style="min-height: 290px;"></div>

        <!-- Legend Bar -->
        <div class="fcc-shake-legend-bar">
            <div class="fcc-shake-legend-item">
                <span class="fcc-legend-circle green"></span>
                <span>+ Add shakes</span>
            </div>
            <div class="fcc-shake-legend-item">
                <span class="fcc-legend-circle red"></span>
                <span>- Subtract shakes</span>
            </div>
            <div class="fcc-shake-legend-item">
                <span class="fcc-legend-circle blue"></span>
                <span>Attendance check-in (-1)</span>
            </div>
            <div class="fcc-shake-legend-item">
                <span class="fcc-legend-circle orange"></span>
                <span>Attendance deleted (+1)</span>
            </div>
        </div>
    </div>

    <!-- 6. Main Card 2: Shake activity log (Data Table) -->
    <div class="fcc-white-card data-table-container">
        <div class="fcc-card-section-header">
            <div>
                <h3 class="fcc-card-heading">Shake activity log</h3>
                <p class="fcc-card-subheading">Every balance change recorded across web and app</p>
            </div>
            <div class="fcc-table-controls-group">
                <!-- Search Box -->
                <div class="fcc-table-search-box">
                    <i data-feather="search"></i>
                    <input type="text" id="fccActivitySearchInput" class="fcc-table-search-input" placeholder="Search activity..." autocomplete="off" />
                </div>

                <!-- Activity Filter -->
                <select id="fccActivityFilter" class="fcc-filter-select">
                    <option value="">All activity</option>
                    <option value="QR Attendance Add">QR Attendance Add</option>
                    <option value="Manual Attendance Add">Manual Attendance Add</option>
                    <option value="Add User Days">Add User Days</option>
                    <option value="Subtract User Days">Subtract User Days</option>
                    <option value="Attendance Delete">Attendance Delete</option>
                </select>

                <!-- Source Filter -->
                <select id="fccSourceFilter" class="fcc-filter-select">
                    <option value="">All sources</option>
                    <option value="App Side">App Side</option>
                    <option value="Admin Panel">Admin Panel</option>
                </select>

                <!-- Page Size Selector -->
                <select id="fccPageSizeSelect" class="fcc-filter-select">
                    <option value="20" selected>20 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
        </div>

        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}" />

        <!-- Modern Data Table -->
        <table id="dataTable" class="table" data-url="{{ route('nutritionPanel.track-shake.getTrackShake') }}">
            <thead>
                <tr>
                    <th style="width: 70px;">Entry</th>
                    <th>Date</th>
                    <th>Balance</th>
                    <th>Change</th>
                    <th>Activity</th>
                    <th>Source</th>
                    <th>Remark</th>
                </tr>
            </thead>
        </table>
    </div>

</div>

<!-- Modal for Add/Subtract User Days -->
<div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/plugins/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
<script src="{{ asset('admin-assets/plugins/apex/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/track-shake/view.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Export handler
        $('#fccExportBtn').on('click', function() {
            window.print();
        });

        // Raw Data Points from Controller
        var allDataPoints = @json($chartDataPoints ?? []);

        // Prepare chart data helper to prevent duplicate labels and handle discrete event types
        function prepareChartData(points) {
            if (!points || points.length === 0) {
                return { categories: [], balances: [], points: [] };
            }

            var categories = [];
            var balances = [];
            var seenDates = {};

            points.forEach(function(pt) {
                var dStr = pt.date_short;
                if (!seenDates[dStr]) {
                    seenDates[dStr] = 1;
                    categories.push(dStr);
                } else {
                    seenDates[dStr]++;
                    categories.push(dStr + ' (' + seenDates[dStr] + ')');
                }
                balances.push(pt.balance);
            });

            return {
                categories: categories,
                balances: balances,
                points: points
            };
        }

        var activePoints = (allDataPoints.length > 30) ? allDataPoints.slice(-30) : allDataPoints;
        var initialPrepared = prepareChartData(activePoints);

        var numericBalances = initialPrepared.balances;
        var minBal = numericBalances.length ? Math.max(0, Math.floor(Math.min(...numericBalances) - 4)) : 0;
        var maxBal = numericBalances.length ? Math.ceil(Math.max(...numericBalances) + 5) : 40;

        // Custom Marker colors per data point
        var markerColors = initialPrepared.points.map(function(p) { return p.color || '#3b46f1'; });

        var chartOptions = {
            chart: {
                type: 'area',
                height: 290,
                toolbar: { show: false },
                fontFamily: "'Outfit', sans-serif",
                sparkline: { enabled: false },
                zoom: { enabled: false },
                dropShadow: {
                    enabled: true,
                    top: 4,
                    left: 0,
                    blur: 6,
                    opacity: 0.18,
                    color: '#3b46f1'
                }
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: 'Shake balance',
                data: initialPrepared.balances
            }],
            xaxis: {
                categories: initialPrepared.categories,
                labels: {
                    offsetY: 4,
                    style: {
                        colors: '#94a3b8',
                        fontSize: '11px',
                        fontFamily: "'Outfit', sans-serif",
                        fontWeight: 600
                    },
                    rotate: 0,
                    hideOverlappingLabels: true
                },
                axisBorder: { show: false },
                axisTicks: { show: false },
                tooltip: {
                    enabled: false
                }
            },
            yaxis: {
                labels: {
                    show: true,
                    align: 'right',
                    minWidth: 32,
                    offsetX: -6,
                    style: {
                        colors: '#94a3b8',
                        fontSize: '11px',
                        fontFamily: "'Outfit', sans-serif",
                        fontWeight: 600
                    },
                    formatter: function(val) {
                        return val !== undefined && val !== null ? parseInt(val, 10) : '';
                    }
                },
                min: minBal,
                max: maxBal,
                tickAmount: 4,
                forceNiceScale: true
            },
            colors: ['#3b46f1'],
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.42,
                    opacityTo: 0.02,
                    stops: [0, 90, 100],
                    colorStops: [
                        { offset: 0, color: '#3b46f1', opacity: 0.4 },
                        { offset: 85, color: '#eff2fe', opacity: 0.08 },
                        { offset: 100, color: '#ffffff', opacity: 0.0 }
                    ]
                }
            },
            markers: {
                size: 5,
                colors: markerColors,
                strokeColors: '#ffffff',
                strokeWidth: 2,
                hover: { size: 7.5 }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                padding: {
                    top: 10,
                    right: 20,
                    bottom: 10,
                    left: 15
                },
                yaxis: { lines: { show: true } },
                xaxis: { lines: { show: false } }
            },
            tooltip: {
                theme: 'dark',
                custom: function({series, seriesIndex, dataPointIndex, w}) {
                    var val = series[seriesIndex][dataPointIndex];
                    var pt = activePoints[dataPointIndex] || {};
                    var dateStr = pt.date_formatted || w.globals.categoryLabels[dataPointIndex] || '';
                    var changeStr = pt.change_text || '';
                    var remarkStr = pt.remark || 'Activity';
                    var sourceStr = pt.source || 'Admin Panel';
                    var badgeBg = pt.color || '#3b46f1';

                    return '<div style="background: #0f172a; color: #ffffff; padding: 10px 14px; border-radius: 10px; font-size: 12px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.4); font-family: Outfit, sans-serif; border: 1px solid rgba(255,255,255,0.1); min-width: 175px;">' +
                           '<div style="font-size: 11px; color: #94a3b8; margin-bottom: 6px; font-weight: 500;">' + dateStr + '</div>' +
                           '<div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 6px;">' +
                           '<span style="font-size: 16px; font-weight: 800; color: #ffffff;">' + val + ' <span style="font-size: 11px; font-weight: 500; color: #cbd5e1;">shakes</span></span>' +
                           (changeStr ? '<span style="background: ' + badgeBg + '; color: #ffffff; font-size: 10.5px; font-weight: 700; padding: 2px 7px; border-radius: 5px;">' + changeStr + '</span>' : '') +
                           '</div>' +
                           '<div style="font-size: 11px; color: #94a3b8; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 6px; display: flex; justify-content: space-between;">' +
                           '<span>' + remarkStr + '</span>' +
                           '<span style="color: #64748b;">' + sourceStr + '</span>' +
                           '</div>' +
                           '</div>';
                }
            }
        };

        var shakeChart = new ApexCharts(document.querySelector("#shakeBalanceApexChart"), chartOptions);
        shakeChart.render();

        // 7 days, 30 days, All time toggles
        $('[data-chart-days]').on('click', function(e) {
            e.preventDefault();
            $('[data-chart-days]').removeClass('active');
            $(this).addClass('active');

            var filter = $(this).data('chart-days');
            if (filter === 7 || filter === '7') {
                activePoints = allDataPoints.slice(-7);
            } else if (filter === 30 || filter === '30') {
                activePoints = allDataPoints.slice(-30);
            } else {
                activePoints = allDataPoints;
            }

            var updated = prepareChartData(activePoints);
            var updatedMarkerColors = updated.points.map(function(p) { return p.color || '#3b46f1'; });
            var uMin = updated.balances.length ? Math.max(0, Math.floor(Math.min(...updated.balances) - 4)) : 0;
            var uMax = updated.balances.length ? Math.ceil(Math.max(...updated.balances) + 5) : 40;

            shakeChart.updateOptions({
                xaxis: { categories: updated.categories },
                yaxis: { min: uMin, max: uMax },
                markers: { colors: updatedMarkerColors },
                series: [{ name: 'Shake balance', data: updated.balances }]
            });

            if (activePoints.length > 1) {
                $('#fccTrendRangeBadge').text(activePoints[0].date_short + ' – ' + activePoints[activePoints.length - 1].date_short);
            } else if (activePoints.length === 1) {
                $('#fccTrendRangeBadge').text(activePoints[0].date_short);
            }
        });
    });
</script>
@endpush
