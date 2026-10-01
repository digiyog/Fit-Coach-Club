@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Reviews | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    /* Reviews Page Styles */
    .review-page-wrapper {
        padding: 6px 10px 40px 10px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Top Breadcrumb */
    .review-breadcrumb {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .review-breadcrumb a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .review-breadcrumb a:hover {
        color: #2563eb;
    }

    .review-breadcrumb .crumb-sep {
        color: #94a3b8;
    }

    .review-breadcrumb .crumb-active {
        color: #0f172a;
        font-weight: 600;
    }

    /* Page Header */
    .review-header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .review-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .review-subtitle {
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

    /* Summary Card ("All reviews") */
    .review-summary-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px 26px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 24px;
    }

    .badge-all-reviews {
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

    .review-kpi-grid {
        display: grid;
        grid-template-columns: 1.1fr 1.3fr 1.2fr 1.1fr 1.5fr;
        gap: 20px;
        align-items: center;
    }

    @media (max-width: 1200px) {
        .review-kpi-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .review-kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 480px) {
        .review-kpi-grid {
            grid-template-columns: 1fr;
        }
    }

    .review-kpi-item {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .review-kpi-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }

    .review-kpi-icon.icon-blue {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }

    .review-kpi-icon.icon-amber-round {
        background: #fffbeb;
        color: #f59e0b;
        border: 1px solid #fef3c7;
        border-radius: 50%;
    }

    .review-kpi-icon.icon-amber-square {
        background: #fffbeb;
        color: #f59e0b;
        border: 1px solid #fef3c7;
        border-radius: 12px;
    }

    .review-kpi-icon.icon-purple {
        background: #f5f3ff;
        color: #7c3aed;
        border: 1px solid #ede9fe;
    }

    .review-kpi-data {
        display: flex;
        flex-direction: column;
    }

    .review-kpi-val {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .review-kpi-sub {
        font-size: 12px;
        font-weight: 500;
        color: #94a3b8;
    }

    .review-kpi-lbl {
        font-size: 12.5px;
        font-weight: 500;
        color: #64748b;
        margin-top: 4px;
    }

    /* Breakdown Bar Graph (Right Section) */
    .review-breakdown-block {
        display: flex;
        flex-direction: column;
        gap: 6px;
        width: 100%;
        max-width: 280px;
        margin-left: auto;
    }

    .breakdown-row {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        color: #334155;
        font-weight: 600;
    }

    .breakdown-lbl {
        width: 46px;
        text-align: left;
        white-space: nowrap;
        color: #0f172a;
        font-weight: 700;
    }

    .breakdown-count {
        width: 18px;
        text-align: right;
        color: #64748b;
        font-weight: 600;
    }

    .breakdown-bar-track {
        flex: 1;
        height: 7px;
        background: #f1f5f9;
        border-radius: 999px;
        overflow: hidden;
    }

    .breakdown-bar-fill {
        height: 100%;
        border-radius: 999px;
        transition: width 0.4s ease;
    }

    .breakdown-bar-fill.fill-amber {
        background: #f59e0b;
    }

    .breakdown-bar-fill.fill-slate {
        background: #94a3b8;
    }

    /* Main Ledger Card ("Member reviews") */
    .review-ledger-card {
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
        grid-template-columns: 2.2fr 1.3fr 1.5fr 1.4fr 1.1fr;
        gap: 12px;
        margin-bottom: 18px;
    }

    @media (max-width: 1100px) {
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

    .filter-input-styled {
        height: 42px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding-left: 38px;
        padding-right: 14px;
        font-size: 13.5px;
        color: #0f172a;
        background-color: #ffffff;
        transition: all 0.2s ease;
        width: 100%;
        box-shadow: none !important;
    }

    .filter-input-styled:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    .filter-date-input {
        cursor: pointer;
        padding-right: 32px;
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

    .btn-delete-selected {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 7px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        cursor: not-allowed;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    .btn-delete-selected:not(:disabled) {
        background: #fef2f2;
        color: #ef4444;
        border-color: #fecaca;
        cursor: pointer;
        box-shadow: 0 1px 3px rgba(239, 68, 68, 0.1);
    }

    .btn-delete-selected:not(:disabled):hover {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fca5a5;
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

    #dataTable thead th.th-chk {
        width: 40px;
        text-align: center;
        padding-left: 16px;
        padding-right: 8px;
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

    #dataTable tbody tr.selected td {
        background: #eff6ff;
    }

    /* Checkbox cell */
    .custom-chk-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .review-chk-native {
        width: 17px;
        height: 17px;
        border-radius: 4px;
        border: 1.5px solid #cbd5e1;
        cursor: pointer;
        accent-color: #2563eb;
        vertical-align: middle;
        margin: 0;
    }

    /* Column Typography */
    .reviewer-name {
        font-weight: 600;
        color: #0f172a;
    }

    .rating-cell {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .rating-num {
        font-weight: 700;
        color: #0f172a;
        font-size: 13.5px;
        min-width: 12px;
    }

    .stars-gold {
        color: #f59e0b;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 2px;
    }

    .star-filled {
        color: #f59e0b;
    }

    .star-empty {
        color: #cbd5e1;
    }

    .badge-unrated {
        display: inline-block;
        background-color: #f1f5f9;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        padding: 3px 12px;
        border-radius: 6px;
    }

    .review-message-text {
        color: #475569;
        font-size: 13px;
    }

    .review-date-text {
        color: #475569;
        font-size: 13px;
        white-space: nowrap;
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

    /* Reset & format Info text (e.g. "Showing records 1 to 9 of 9") */
    div.dataTables_wrapper div.dataTables_info,
    div.dataTables_wrapper .dataTables_info,
    .review-ledger-card .dataTables_info,
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
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="review-page-wrapper">
        <!-- Breadcrumb Navigation -->
        <div class="review-breadcrumb">
            <a href="{{ route('nutritionPanel.dashboard') }}">Finance & Plans</a>
            <span class="crumb-sep">/</span>
            <span class="crumb-active">Reviews</span>
        </div>

        <!-- Header Section -->
        <div class="review-header-section">
            <div>
                <h1 class="review-title">Reviews</h1>
                <p class="review-subtitle">Understand member ratings and manage submitted feedback.</p>
            </div>
            <div>
                <button type="button" class="btn-header-filter" id="btn_toggle_header_filters">
                    <i class="fa fa-filter"></i> Filters
                </button>
            </div>
        </div>

        <!-- Summary Card ("All reviews") -->
        <div class="review-summary-card">
            <div>
                <span class="badge-all-reviews">All reviews</span>
            </div>
            <div class="review-kpi-grid">
                <!-- Total Reviews -->
                <div class="review-kpi-item">
                    <div class="review-kpi-icon icon-blue">
                        <i class="fa fa-commenting-o"></i>
                    </div>
                    <div class="review-kpi-data">
                        <span class="review-kpi-val" id="kpi_total_reviews">{{ $summary['total_reviews'] ?? 0 }}</span>
                        <span class="review-kpi-lbl">Total reviews</span>
                    </div>
                </div>

                <!-- Average Rating -->
                <div class="review-kpi-item">
                    <div class="review-kpi-icon icon-amber-round">
                        <i class="fa fa-star"></i>
                    </div>
                    <div class="review-kpi-data">
                        <div>
                            <span class="review-kpi-val" id="kpi_average_rating">{{ $summary['average_rating'] ?? '0.00' }}</span>
                            <span class="review-kpi-sub">out of 5</span>
                        </div>
                        <span class="review-kpi-lbl">Average rating</span>
                    </div>
                </div>

                <!-- Five-star reviews -->
                <div class="review-kpi-item">
                    <div class="review-kpi-icon icon-amber-square">
                        <i class="fa fa-star"></i>
                    </div>
                    <div class="review-kpi-data">
                        <span class="review-kpi-val" id="kpi_five_star_reviews">{{ $summary['five_star_reviews'] ?? 0 }}</span>
                        <span class="review-kpi-lbl">Five-star reviews</span>
                    </div>
                </div>

                <!-- Written messages -->
                <div class="review-kpi-item">
                    <div class="review-kpi-icon icon-purple">
                        <i class="fa fa-file-text-o"></i>
                    </div>
                    <div class="review-kpi-data">
                        <span class="review-kpi-val" id="kpi_written_messages">{{ $summary['written_messages'] ?? 0 }}</span>
                        <span class="review-kpi-lbl">Written messages</span>
                    </div>
                </div>

                <!-- Star Rating Breakdown Bars -->
                <div class="review-breakdown-block">
                    <!-- 5 Star -->
                    <div class="breakdown-row">
                        <span class="breakdown-lbl">5★</span>
                        <span class="breakdown-count" id="kpi_breakdown_5_count">{{ $summary['five_star_reviews'] ?? 0 }}</span>
                        <div class="breakdown-bar-track">
                            <div class="breakdown-bar-fill fill-amber" id="kpi_breakdown_5_bar" style="width: {{ $summary['five_star_percent'] ?? 0 }}%;"></div>
                        </div>
                    </div>
                    <!-- 4 Star -->
                    <div class="breakdown-row">
                        <span class="breakdown-lbl">4★</span>
                        <span class="breakdown-count" id="kpi_breakdown_4_count">{{ $summary['four_star_reviews'] ?? 0 }}</span>
                        <div class="breakdown-bar-track">
                            <div class="breakdown-bar-fill fill-amber" id="kpi_breakdown_4_bar" style="width: {{ $summary['four_star_percent'] ?? 0 }}%;"></div>
                        </div>
                    </div>
                    <!-- Unrated -->
                    <div class="breakdown-row">
                        <span class="breakdown-lbl">Unrated</span>
                        <span class="breakdown-count" id="kpi_breakdown_unrated_count">{{ $summary['unrated_reviews'] ?? 0 }}</span>
                        <div class="breakdown-bar-track">
                            <div class="breakdown-bar-fill fill-slate" id="kpi_breakdown_unrated_bar" style="width: {{ $summary['unrated_percent'] ?? 0 }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ledger Table Card ("Member reviews") -->
        <div class="review-ledger-card">
            <div class="ledger-title-bar">
                <div class="title-accent-pill"></div>
                <h3 class="ledger-heading">Member reviews</h3>
            </div>
            <p class="ledger-subheading">Search ratings, review dates and available feedback.</p>

            <!-- Filters Bar (Row 1) -->
            <div class="ledger-filters-grid">
                <!-- Search Input -->
                <div class="filter-search-wrap">
                    <i class="fa fa-search filter-icon-left"></i>
                    <input type="text" id="review_search" class="filter-input-styled" placeholder="Search reviewer or message...">
                </div>

                <!-- Rating Filter -->
                <div>
                    <select id="review_rating_filter" class="filter-select-styled">
                        <option value="">All ratings</option>
                        <option value="5">5 stars</option>
                        <option value="4">4 stars</option>
                        <option value="3">3 stars</option>
                        <option value="2">2 stars</option>
                        <option value="1">1 star</option>
                        <option value="unrated">Unrated</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div class="filter-date-wrap">
                    <i class="fa fa-calendar filter-icon-left"></i>
                    <input type="text" id="review_date_range" class="filter-input-styled filter-date-input" placeholder="Date range" readonly autocomplete="off">
                    <i class="fa fa-chevron-down filter-icon-right"></i>
                </div>

                <!-- Message Availability Filter -->
                <div>
                    <select id="review_message_filter" class="filter-select-styled">
                        <option value="">Message availability</option>
                        <option value="with_message">With message</option>
                        <option value="without_message">Without message</option>
                    </select>
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
                    <span class="table-count-text"><span id="visible_review_count">0</span> reviews</span>
                    <select id="review_page_length" class="page-len-select">
                        <option value="10">10 per page</option>
                        <option value="20" selected>20 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>
                <div>
                    <button type="button" id="btn_delete_selected" class="btn-delete-selected" disabled>
                        <i class="fa fa-trash-o"></i> Delete selected
                    </button>
                </div>
            </div>

            <input type="hidden" name="user_id" id="user_id" value="">

            <!-- Table Container -->
            <div class="table-container-modern table-responsive">
                <table id="dataTable" class="table" data-url="{{ route('nutritionPanel.reviews.getReviews') }}" data-destroy-url="{{ route('nutritionPanel.reviews.destroy') }}">
                    <thead>
                        <tr>
                            <th class="th-chk">
                                <div class="custom-chk-wrap">
                                    <input type="checkbox" id="select_all_reviews" class="review-chk-native">
                                </div>
                            </th>
                            <th>Name</th>
                            <th>Rating</th>
                            <th>Message</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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
<script src="{{ asset('admin-assets/js/reviews/view.js') }}?v={{ file_exists(public_path('admin-assets/js/reviews/view.js')) ? filemtime(public_path('admin-assets/js/reviews/view.js')) : time() }}"></script>
@endpush
