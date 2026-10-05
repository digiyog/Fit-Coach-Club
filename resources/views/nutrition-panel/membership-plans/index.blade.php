@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Membership Plans | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    /* Membership Plans Page Styles */
    .plan-page-wrapper {
        padding: 6px 10px 40px 10px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Top Breadcrumb */
    .plan-breadcrumb {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .plan-breadcrumb a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .plan-breadcrumb a:hover {
        color: #2563eb;
    }

    .plan-breadcrumb .crumb-sep {
        color: #94a3b8;
    }

    .plan-breadcrumb .crumb-active {
        color: #0f172a;
        font-weight: 600;
    }

    /* Page Header */
    .plan-header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .plan-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .plan-subtitle {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 0;
        font-weight: 400;
    }

    .btn-header-filter {
        background: #ffffff;
        color: #2563eb !important;
        border: 1.5px solid #2563eb;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        text-decoration: none !important;
        box-shadow: 0 1px 3px rgba(37, 99, 235, 0.1);
        cursor: pointer;
    }

    .btn-header-filter:hover {
        background: #eff6ff;
        color: #1d4ed8 !important;
        border-color: #1d4ed8;
    }

    /* Summary Card ("All plan records") */
    .plan-summary-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px 26px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 24px;
    }

    .badge-all-plans {
        display: inline-block;
        background: #e0edff;
        color: #2563eb;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 9999px;
        letter-spacing: 0.02em;
        margin-bottom: 18px;
    }

    .plan-kpi-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr 1.2fr 1.1fr 1.6fr;
        gap: 20px;
        align-items: center;
    }

    @media (max-width: 1200px) {
        .plan-kpi-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .plan-kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 480px) {
        .plan-kpi-grid {
            grid-template-columns: 1fr;
        }
    }

    .plan-kpi-item {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .plan-kpi-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }

    .plan-kpi-icon.icon-blue {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }

    .plan-kpi-icon.icon-purple {
        background: #f5f3ff;
        color: #7c3aed;
        border: 1px solid #ede9fe;
    }

    .plan-kpi-icon.icon-amber {
        background: #fffbeb;
        color: #f59e0b;
        border: 1px solid #fef3c7;
    }

    .plan-kpi-icon.icon-green {
        background: #ecfdf5;
        color: #10b981;
        border: 1px solid #d1fae5;
        border-radius: 50%;
    }

    .plan-kpi-data {
        display: flex;
        flex-direction: column;
    }

    .plan-kpi-val {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .plan-kpi-lbl {
        font-size: 12.5px;
        font-weight: 500;
        color: #64748b;
        margin-top: 4px;
    }

    /* Multi-segmented Progress Bar & Legend */
    .plan-progress-block {
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 100%;
    }

    .plan-segmented-bar {
        height: 10px;
        width: 100%;
        background: #f1f5f9;
        border-radius: 999px;
        overflow: hidden;
        display: flex;
    }

    .bar-segment {
        height: 100%;
        transition: width 0.4s ease;
    }

    .plan-segmented-legend {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .legend-item {
        font-size: 12px;
        color: #475569;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    /* Main Ledger Card ("Plan history") */
    .plan-ledger-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 24px;
    }

    .ledger-title-bar {
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

    .ledger-heading {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0;
        letter-spacing: -0.01em;
    }

    .ledger-subheading {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 20px;
    }

    /* Toolbar Row 1: Filters */
    .ledger-filters-grid {
        display: grid;
        grid-template-columns: 2.2fr 1.3fr 1.3fr 1.3fr 1.3fr 1.1fr;
        gap: 12px;
        margin-bottom: 18px;
    }

    @media (max-width: 1200px) {
        .ledger-filters-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .ledger-filters-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .ledger-filters-grid {
            grid-template-columns: 1fr;
        }
    }

    .filter-search-wrap, .filter-date-wrap {
        position: relative;
    }

    .filter-icon-left {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
    }

    .filter-icon-right {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 12px;
        pointer-events: none;
    }

    .plan-ledger-card input.filter-input-styled,
    .plan-ledger-card input#plan_search,
    .plan-ledger-card input#plan_date_range,
    input.filter-input-styled {
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
        width: 100% !important;
        box-shadow: none !important;
        outline: none !important;
        line-height: normal !important;
        font-family: inherit !important;
    }

    .plan-ledger-card input.filter-input-styled:focus,
    input.filter-input-styled:focus {
        background-color: #ffffff !important;
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    .filter-date-input {
        cursor: pointer !important;
        padding-right: 36px !important;
    }

    .filter-select-styled {
        height: 42px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        font-size: 13.5px;
        color: #334155;
        font-weight: 500;
        background-color: #ffffff;
        cursor: pointer;
        box-shadow: none !important;
        width: 100%;
        padding: 0 12px;
    }

    .filter-select-styled:focus {
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
        cursor: pointer;
    }

    .btn-more-filters:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    /* Toolbar Row 2: Counts & Actions */
    .ledger-actions-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }

    .ledger-actions-left {
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

    .btn-clear-pill {
        background: #ffffff;
        border-radius: 9px;
        padding: 7px 16px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        border: 1px solid #cbd5e1;
        color: #475569;
        cursor: pointer;
    }

    .btn-clear-pill:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    /* Modern Table Styling */
    .table-container-modern {
        border-radius: 12px;
        overflow: visible !important;
    }

    #dataTable {
        width: 100% !important;
        border-collapse: separate;
        border-spacing: 0;
        border: none;
    }

    #dataTable thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 12.5px;
        font-weight: 700;
        padding: 14px 16px;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        letter-spacing: 0.01em;
        white-space: nowrap;
    }

    #dataTable tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 13.5px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
    }

    #dataTable tbody tr:hover td {
        background: #f8fafc;
    }

    /* Column Typography */
    .plan-name-text {
        font-weight: 600;
        color: #0f172a;
    }

    .plan-amount-paid {
        font-weight: 600;
        color: #16a34a;
    }

    .plan-amount-zero {
        font-weight: 600;
        color: #64748b;
    }

    .badge-plan-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-completed {
        background-color: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    .status-pending {
        background-color: #fef2f2;
        color: #ef4444;
        border: 1px solid #fecaca;
    }

    .plan-date-text {
        color: #475569;
        font-size: 13px;
        white-space: nowrap;
    }

    .plan-remark-bold {
        color: #0f172a;
        font-weight: 600;
        font-size: 13px;
    }

    .plan-remark-dash {
        color: #94a3b8;
        font-size: 13px;
    }

    /* Clean Bottom DataTables Pagination & Info Row */
    .dt-bottom-row {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        margin-top: 20px !important;
        padding-top: 14px !important;
        border-top: 1px solid #f1f5f9 !important;
        flex-wrap: wrap !important;
        gap: 16px !important;
    }

    /* Reset & format Info text (e.g. "Showing records 1 to 6 of 6") */
    div.dataTables_wrapper div.dataTables_info,
    div.dataTables_wrapper .dataTables_info,
    .plan-ledger-card .dataTables_info,
    .dataTables_info {
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
        color: #64748b !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        display: inline-flex !important;
        align-items: center !important;
        line-height: normal !important;
    }

    /* Reset Paginate Container */
    div.dataTables_wrapper div.dataTables_paginate,
    .dataTables_wrapper .dataTables_paginate,
    .dataTables_paginate {
        margin: 0 !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        float: none !important;
    }

    /* Reset Paginate UL */
    div.dataTables_wrapper div.dataTables_paginate ul.pagination,
    .dataTables_paginate ul.pagination {
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
        border: none !important;
        background: transparent !important;
    }

    /* Reset Paginate LI */
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.paginate_button,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.page-item,
    .dataTables_paginate ul.pagination li {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        box-shadow: none !important;
        display: inline-flex !important;
        align-items: center !important;
    }

    /* Style the actual clickable link inside the LI (.page-link) */
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.page-item .page-link,
    div.dataTables_wrapper div.dataTables_paginate .page-link,
    .dataTables_paginate .page-link,
    .dataTables_paginate > a.paginate_button {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 36px !important;
        height: 36px !important;
        padding: 0 10px !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        border: 1px solid #e2e8f0 !important;
        background: #ffffff !important;
        color: #475569 !important;
        margin: 0 !important;
        transition: all 0.15s ease !important;
        text-decoration: none !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
        cursor: pointer !important;
        outline: none !important;
    }

    /* Previous & Next buttons */
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.previous .page-link,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.next .page-link,
    .dataTables_paginate > a.previous,
    .dataTables_paginate > a.next {
        padding: 0 14px !important;
        min-width: auto !important;
        font-weight: 500 !important;
    }

    /* Hover on clickable items */
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.page-item:not(.active):not(.disabled) .page-link:hover,
    .dataTables_paginate > a.paginate_button:not(.current):not(.disabled):hover {
        background: #f8fafc !important;
        color: #0f172a !important;
        border-color: #cbd5e1 !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06) !important;
    }

    /* Active Page (e.g. page 1) */
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.page-item.active .page-link,
    .dataTables_paginate > a.paginate_button.current {
        background: #2563eb !important;
        color: #ffffff !important;
        border-color: #2563eb !important;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.35) !important;
    }

    /* Disabled Page (e.g. Previous button when on page 1) */
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.page-item.disabled .page-link,
    .dataTables_paginate > a.paginate_button.disabled {
        background: #f8fafc !important;
        color: #94a3b8 !important;
        border-color: #e2e8f0 !important;
        box-shadow: none !important;
        cursor: not-allowed !important;
        opacity: 0.8 !important;
        pointer-events: none !important;
    }

    /* Ellipsis item (...) */
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.page-item.disabled:not(.previous):not(.next) .page-link,
    .dataTables_paginate span.ellipsis {
        background: transparent !important;
        border-color: transparent !important;
        color: #94a3b8 !important;
        box-shadow: none !important;
        cursor: default !important;
        min-width: 24px !important;
    }

    /* Hide default legacy DataTables controls */
    div.dataTables_wrapper div.dataTables_length,
    div.dataTables_wrapper div.dataTables_filter {
        display: none !important;
    }

    /* Bottom Coverage Dates Note Banner */
    .plan-coverage-banner {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 12px;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #1e40af;
        font-size: 13px;
        font-weight: 500;
        margin-top: 20px;
    }

    .plan-coverage-banner .banner-icon {
        color: #2563eb;
        font-size: 16px;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="plan-page-wrapper">
        <!-- Breadcrumb Navigation -->
        <div class="plan-breadcrumb">
            <a href="{{ route('nutritionPanel.dashboard') }}">Finance & Plans</a>
            <span class="crumb-sep">/</span>
            <span class="crumb-active">Membership Plans</span>
        </div>

        <!-- Header Section -->
        <div class="plan-header-section">
            <div>
                <h1 class="plan-title">Membership plans</h1>
                <p class="plan-subtitle">Review plan history, recorded amounts and payment completion.</p>
            </div>
            <div>
                <button type="button" class="btn-header-filter" id="btn_toggle_header_filters">
                    <i class="fa fa-filter"></i> Filters
                </button>
            </div>
        </div>

        <!-- Summary Card ("All plan records") -->
        <div class="plan-summary-card">
            <div>
                <span class="badge-all-plans">All plan records</span>
            </div>
            <div class="plan-kpi-grid">
                <!-- Plan records -->
                <div class="plan-kpi-item">
                    <div class="plan-kpi-icon icon-blue">
                        <i class="fa fa-id-card-o"></i>
                    </div>
                    <div class="plan-kpi-data">
                        <span class="plan-kpi-val" id="kpi_total_records">{{ $summary['total_records'] ?? 0 }}</span>
                        <span class="plan-kpi-lbl">Plan records</span>
                    </div>
                </div>

                <!-- Recorded amount -->
                <div class="plan-kpi-item">
                    <div class="plan-kpi-icon icon-purple">
                        <i class="fa fa-file-text-o"></i>
                    </div>
                    <div class="plan-kpi-data">
                        <span class="plan-kpi-val" id="kpi_recorded_amount">{{ $summary['recorded_amount_formatted'] ?? 0 }}</span>
                        <span class="plan-kpi-lbl">Recorded amount</span>
                    </div>
                </div>

                <!-- Zero-amount plans -->
                <div class="plan-kpi-item">
                    <div class="plan-kpi-icon icon-amber">
                        <i class="fa fa-file-o"></i>
                    </div>
                    <div class="plan-kpi-data">
                        <span class="plan-kpi-val" id="kpi_zero_amount_plans">{{ $summary['zero_amount_plans'] ?? 0 }}</span>
                        <span class="plan-kpi-lbl">Zero-amount plans</span>
                    </div>
                </div>

                <!-- Payments completed -->
                <div class="plan-kpi-item">
                    <div class="plan-kpi-icon icon-green">
                        <i class="fa fa-check"></i>
                    </div>
                    <div class="plan-kpi-data">
                        <span class="plan-kpi-val" id="kpi_completion_text">{{ $summary['completion_text'] ?? '0 / 0' }}</span>
                        <span class="plan-kpi-lbl">Payments completed</span>
                    </div>
                </div>

                <!-- Multi-segment Progress Bar & Legend -->
                <div class="plan-progress-block">
                    <div class="plan-segmented-bar" id="kpi_segmented_bar">
                        @foreach($summary['breakdown'] ?? [] as $b)
                            <div class="bar-segment" style="width: {{ $b['percent'] }}%; background-color: {{ $b['color'] }};" title="{{ $b['name'] }}: {{ $b['count'] }}"></div>
                        @endforeach
                    </div>
                    <div class="plan-segmented-legend" id="kpi_segmented_legend">
                        @foreach($summary['breakdown'] ?? [] as $b)
                            <span class="legend-item"><span class="legend-dot" style="background-color: {{ $b['color'] }};"></span> {{ $b['name'] }} {{ $b['count'] }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Ledger Table Card ("Plan history") -->
        <div class="plan-ledger-card">
            <div class="ledger-title-bar">
                <div class="title-accent-pill"></div>
                <h3 class="ledger-heading">Plan history</h3>
            </div>
            <p class="ledger-subheading">Review plan periods, completed payments and remarks.</p>

            <!-- Filters Bar (Row 1) -->
            <div class="ledger-filters-grid">
                <!-- Search Input -->
                <div class="filter-search-wrap">
                    <i class="fa fa-search filter-icon-left"></i>
                    <input type="text" id="plan_search" class="filter-input-styled" placeholder="Search membership plans..." autocomplete="off" style="padding-left: 42px !important;">
                </div>

                <!-- All Plans Filter -->
                <div>
                    <select id="plan_id_filter" class="filter-select-styled">
                        <option value="">All plans</option>
                        @foreach($plans ?? [] as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- All Payment Statuses Filter -->
                <div>
                    <select id="plan_payment_status" class="filter-select-styled">
                        <option value="">All payment statuses</option>
                        <option value="2">Completed</option>
                        <option value="1">Pending</option>
                    </select>
                </div>

                <!-- Amount Type Filter -->
                <div>
                    <select id="plan_amount_type" class="filter-select-styled">
                        <option value="">Amount type</option>
                        <option value="paid">Paid</option>
                        <option value="zero">Zero-amount</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div class="filter-date-wrap">
                    <i class="fa fa-calendar filter-icon-left"></i>
                    <input type="text" id="plan_date_range" class="filter-input-styled filter-date-input" placeholder="Date range" readonly autocomplete="off" style="padding-left: 42px !important; padding-right: 36px !important;">
                    <i class="fa fa-chevron-down filter-icon-right"></i>
                </div>

                <!-- More Filters Button -->
                <div>
                    <button type="button" id="btn_toggle_more_filters" class="btn-more-filters">
                        <span class="d-inline-flex align-items-center gap-2"><i class="fa fa-filter"></i> More filters</span>
                        <i class="fa fa-chevron-down"></i>
                    </button>
                </div>
            </div>

            <!-- Toolbar Row 2: Counts & Actions -->
            <div class="ledger-actions-row">
                <div class="ledger-actions-left">
                    <span class="table-count-text"><span id="visible_plan_count">0</span> plan records</span>
                    <select id="plan_page_length" class="page-len-select">
                        <option value="10">10 per page</option>
                        <option value="20" selected>20 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>
                <div>
                    <button type="button" id="btn_clear_filters" class="btn-clear-pill">
                        <i class="fa fa-refresh"></i> Clear filters
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-container-modern table-responsive">
                <table id="dataTable" class="table" data-url="{{ route('nutritionPanel.membership-plans.getMembershipPlans') }}">
                    <thead>
                        <tr>
                            <th>Membership Plan</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <!-- Bottom Coverage Dates Note Banner -->
            <div class="plan-coverage-banner">
                <i class="fa fa-calendar banner-icon"></i>
                <span>Coverage dates shown exactly as recorded.</span>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/my-membership-plans/view.js') }}?v={{ file_exists(public_path('admin-assets/js/my-membership-plans/view.js')) ? filemtime(public_path('admin-assets/js/my-membership-plans/view.js')) : time() }}"></script>
@endpush
