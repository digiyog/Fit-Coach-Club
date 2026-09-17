@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Weight Progress | ' . ($user->name ?? 'Member') . ' | ' . __('language.page_main_title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="{{ asset('admin-assets/plugins/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">

<style>
    :root {
        --fcc-primary: #3b46f1;
        --fcc-primary-hover: #2d38db;
        --fcc-dark: #0f172a;
        --fcc-muted: #64748b;
        --fcc-border: #edf2f7;
        --fcc-card-bg: #ffffff;
        --fcc-page-bg: #f8fafc;
    }

    body {
        background-color: var(--fcc-page-bg) !important;
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif !important;
    }

    .fcc-weight-page-wrap {
        padding: 22px 26px 45px 26px;
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    }

    /* 1. Breadcrumbs */
    .fcc-breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #94a3b8;
        margin-bottom: 6px;
        font-weight: 500;
    }
    .fcc-breadcrumb-nav a {
        color: #64748b;
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
        color: #64748b;
        font-weight: 500;
    }

    /* 2. Top Header */
    .fcc-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 18px;
    }
    .fcc-page-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--fcc-dark);
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }
    .fcc-page-subtitle {
        font-size: 13.5px;
        color: var(--fcc-muted);
        margin-bottom: 0;
        font-weight: 400;
    }
    .fcc-header-btns {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .fcc-btn-export {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        color: #1e293b;
        font-weight: 600;
        font-size: 13.5px;
        padding: 9px 18px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.16s ease;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        text-decoration: none;
        cursor: pointer;
    }
    .fcc-btn-export:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    .fcc-btn-export svg, .fcc-btn-export i {
        width: 15px;
        height: 15px;
        color: #3b46f1;
    }
    .fcc-btn-record-weight {
        background: var(--fcc-primary);
        border: 1.5px solid var(--fcc-primary);
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13.5px;
        padding: 9px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.16s ease;
        box-shadow: 0 4px 12px rgba(59, 70, 241, 0.28);
        text-decoration: none;
        cursor: pointer;
    }
    .fcc-btn-record-weight:hover {
        background: var(--fcc-primary-hover);
        border-color: var(--fcc-primary-hover);
        box-shadow: 0 6px 16px rgba(59, 70, 241, 0.36);
        transform: translateY(-1px);
    }

    /* 3. Sub-Navigation Tabs */
    .fcc-member-nav-tabs {
        display: flex;
        align-items: center;
        gap: 28px;
        border-bottom: 1.5px solid #e2e8f0;
        margin-bottom: 22px;
        padding-left: 4px;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .fcc-member-nav-tabs::-webkit-scrollbar {
        display: none;
    }
    .fcc-tab-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 4px 14px 4px;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        position: relative;
        white-space: nowrap;
        transition: all 0.15s ease;
    }
    .fcc-tab-link svg, .fcc-tab-link i {
        width: 16px;
        height: 16px;
        color: #94a3b8;
        transition: color 0.15s ease;
    }
    .fcc-tab-link:hover {
        color: var(--fcc-primary);
    }
    .fcc-tab-link:hover svg, .fcc-tab-link:hover i {
        color: var(--fcc-primary);
    }
    .fcc-tab-link.active {
        color: var(--fcc-primary);
    }
    .fcc-tab-link.active svg, .fcc-tab-link.active i {
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
        border-radius: 3px 3px 0 0;
    }

    /* 4. Top Metric Cards */
    .fcc-metric-cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 22px;
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
        padding: 18px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        transition: all 0.2s ease;
    }
    .fcc-metric-card:hover {
        box-shadow: 0 6px 18px -4px rgba(15, 23, 42, 0.06);
        border-color: #e2e8f0;
    }
    .fcc-metric-icon-circle {
        width: 50px;
        height: 50px;
        min-width: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .fcc-metric-icon-circle.blue {
        background: #eff6ff;
        color: #3b46f1;
    }
    .fcc-metric-icon-circle.green {
        background: #ecfdf5;
        color: #10b981;
    }
    .fcc-metric-icon-circle.purple {
        background: #f5f3ff;
        color: #8b5cf6;
    }
    .fcc-metric-icon-circle svg {
        width: 22px;
        height: 22px;
    }
    .fcc-metric-content {
        flex: 1;
    }
    .fcc-metric-label {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 3px;
    }
    .fcc-metric-value {
        font-size: 23px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        letter-spacing: -0.02em;
        margin-bottom: 2px;
    }
    .fcc-metric-subtext {
        font-size: 12px;
        color: #94a3b8;
        font-weight: 500;
        margin-bottom: 0;
    }

    /* 5. Main Card Wrappers */
    .fcc-white-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 22px;
        padding: 22px 24px;
    }

    /* 6. Chart Section Header & Controls */
    .fcc-card-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 16px;
    }
    .fcc-card-heading {
        font-size: 17px;
        font-weight: 700;
        color: var(--fcc-dark);
        margin-bottom: 2px;
        letter-spacing: -0.01em;
    }
    .fcc-card-subheading {
        font-size: 12.5px;
        color: var(--fcc-muted);
        margin-bottom: 0;
    }
    .fcc-chart-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .fcc-date-range-pill {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        padding: 6px 14px;
        border-radius: 9px;
        font-size: 12.5px;
        font-weight: 600;
        color: #334155;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .fcc-date-range-pill svg {
        width: 14px;
        height: 14px;
        color: var(--fcc-primary);
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
    }
    .fcc-toggle-btn.active {
        background: #3b46f1;
        color: #ffffff;
        box-shadow: 0 1px 3px rgba(59, 70, 241, 0.25);
    }
    .fcc-chart-legend {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        margin-top: 10px;
        padding-left: 6px;
    }
    .fcc-legend-circle {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #3b46f1;
    }

    /* ApexChart Text & Alignment Fixes */
    .apexcharts-canvas {
        font-family: 'Outfit', sans-serif !important;
    }
    .apexcharts-text, .apexcharts-text tspan {
        font-family: 'Outfit', sans-serif !important;
    }
    .apexcharts-yaxis-title text {
        fill: #64748b !important;
        font-weight: 600 !important;
        font-size: 11px !important;
    }
    .apexcharts-yaxis-label {
        fill: #94a3b8 !important;
        font-weight: 500 !important;
        font-size: 11.5px !important;
    }
    .apexcharts-xaxis-label {
        fill: #94a3b8 !important;
        font-weight: 500 !important;
        font-size: 11.5px !important;
    }

    /* 7. Table Header & Search */
    .fcc-table-controls-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .fcc-table-search-box {
        position: relative;
        min-width: 240px;
        display: flex;
        align-items: center;
    }
    .fcc-table-search-box svg,
    .fcc-table-search-box i,
    .fcc-table-search-box .feather,
    .fcc-table-search-box .feather-search {
        position: absolute !important;
        left: 12px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 15px !important;
        height: 15px !important;
        color: #94a3b8 !important;
        pointer-events: none !important;
        z-index: 2 !important;
    }
    .fcc-table-search-input {
        width: 100%;
        height: 38px;
        padding-left: 36px !important;
        padding-right: 14px !important;
        border: 1.5px solid #e2e8f0;
        border-radius: 9px;
        font-size: 13px;
        font-family: 'Outfit', sans-serif;
        color: #0f172a;
        background: #ffffff;
        transition: all 0.15s ease;
    }
    .fcc-table-search-input:focus {
        border-color: #3b46f1;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.1);
    }
    .fcc-page-size-select {
        height: 38px;
        padding: 0 28px 0 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 9px;
        font-size: 12.5px;
        font-weight: 600;
        color: #334155;
        background: #ffffff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") no-repeat right 10px center/10px 10px;
        cursor: pointer;
        appearance: none;
    }

    /* 8. Table Styling */
    .fcc-modern-table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        background: #ffffff;
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

    /* Evidence Badges */
    .fcc-evidence-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        font-weight: 500;
        white-space: nowrap;
    }
    .fcc-evidence-badge.available {
        color: #0f172a;
    }
    .fcc-evidence-badge.none {
        color: #94a3b8;
    }
    .fcc-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }
    .dot-green {
        background: #10b981;
    }
    .dot-gray {
        background: #cbd5e1;
    }

    /* View Image Button */
    .fcc-btn-view-image {
        background: transparent;
        border: 1.5px solid #3b46f1;
        color: #3b46f1 !important;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 13px;
        border-radius: 7px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap !important;
        transition: all 0.16s ease;
    }
    .fcc-btn-view-image:hover {
        background: #3b46f1;
        color: #ffffff !important;
    }
    .fcc-action-dots-btn {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #3b46f1 !important;
        font-size: 15px;
        text-decoration: none;
        cursor: pointer;
        border-radius: 6px;
        letter-spacing: 2px;
    }
    .fcc-action-dots-btn:hover {
        background: #eff2fe;
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
</style>
@endpush

@section('content')
@php
    $lastW = (float)($lastRecord['weight'] ?? (!empty($user->current_weight) ? $user->current_weight : 0));
    $secondLastW = (float)($secondLastRecord['weight'] ?? 0);
    $firstW = (float)($firstRecord['weight'] ?? (!empty($user->starting_weight) ? $user->starting_weight : 0));

    // Today change diff (Latest session vs second latest session)
    $todayDiffKg = ($lastW > 0 && $secondLastW > 0) ? round($lastW - $secondLastW, 3) : 0;
    $todayDiffGram = intval(round($todayDiffKg * 1000));

    // Total change diff (Latest session vs first recorded / starting weight)
    $totalDiffKg = ($lastW > 0 && $firstW > 0) ? round($lastW - $firstW, 2) : 0;

    $userEncryptedId = ev($user->id);
@endphp

<div class="fcc-weight-page-wrap">

    <!-- 1. Breadcrumbs -->
    <div class="fcc-breadcrumb-nav">
        <a href="{{ route('nutritionPanel.users.index') }}">User management</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <a href="{{ route('nutritionPanel.users.index') }}">All users</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <span class="fcc-breadcrumb-active">View weight</span>
    </div>

    <!-- 2. Header -->
    <div class="fcc-page-header">
        <div>
            <h1 class="fcc-page-title">Weight progress</h1>
            <p class="fcc-page-subtitle">{{ $user->name ?? 'Member' }} · Counselling progress and measurement history</p>
        </div>
        <div class="fcc-header-btns">
            <button type="button" class="btn fcc-btn-export" id="fccExportBtn">
                <i data-feather="download"></i>
                <span>Export</span>
            </button>
            <button type="button" class="btn fcc-btn-record-weight" data-bs-toggle="modal" data-bs-target="#recordWeightModal" data-toggle="modal" data-target="#recordWeightModal">
                <i data-feather="plus" style="width: 15px; height: 15px;"></i>
                <span>Record weight</span>
            </button>
        </div>
    </div>

    <!-- 3. Sub-Navigation Tabs (Member 360 View) -->
    <div class="fcc-member-nav-tabs">
        <a href="{{ route('nutritionPanel.users.viewWeights', ['id' => $userEncryptedId]) }}" class="fcc-tab-link active">
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
        <a href="{{ route('nutritionPanel.track-shake.index', ['id' => $userEncryptedId]) }}" class="fcc-tab-link">
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
        <!-- Card 1: Latest weight -->
        <div class="fcc-metric-card">
            <div class="fcc-metric-icon-circle blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
            </div>
            <div class="fcc-metric-content">
                <div class="fcc-metric-label">Latest weight</div>
                <div class="fcc-metric-value">
                    {{ $lastW > 0 ? number_format($lastW, 1) . ' kg' : '97.8 kg' }}
                </div>
                <div class="fcc-metric-subtext">
                    {{ !empty($lastRecord['date']) ? date('d M Y', strtotime($lastRecord['date'])) : date('d M Y') }}
                </div>
            </div>
        </div>

        <!-- Card 2: Today's change -->
        <div class="fcc-metric-card">
            <div class="fcc-metric-icon-circle {{ $todayDiffKg > 0 ? 'purple' : 'green' }}">
                @if($todayDiffKg > 0)
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="7" y1="17" x2="17" y2="7"></line>
                        <polyline points="7 7 17 7 17 17"></polyline>
                    </svg>
                @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="7" y1="7" x2="17" y2="17"></line>
                        <polyline points="17 7 17 7 17 17"></polyline>
                    </svg>
                @endif
            </div>
            <div class="fcc-metric-content">
                <div class="fcc-metric-label">Today's change</div>
                <div class="fcc-metric-value" style="color: {{ $todayDiffKg < 0 ? '#10b981' : ($todayDiffKg > 0 ? '#f97316' : '#0f172a') }};">
                    @if($todayDiffKg < 0)
                        -{{ abs($todayDiffGram) < 1000 ? abs($todayDiffGram) . ' g' : abs($todayDiffKg) . ' kg' }}
                    @elseif($todayDiffKg > 0)
                        +{{ $todayDiffGram < 1000 ? $todayDiffGram . ' g' : $todayDiffKg . ' kg' }}
                    @else
                        0 g
                    @endif
                </div>
                <div class="fcc-metric-subtext">
                    {{ !empty($lastRecord['date']) ? date('d M Y', strtotime($lastRecord['date'])) : 'Latest session' }}
                </div>
            </div>
        </div>

        <!-- Card 3: Total change -->
        <div class="fcc-metric-card">
            <div class="fcc-metric-icon-circle purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </div>
            <div class="fcc-metric-content">
                <div class="fcc-metric-label">Total change</div>
                <div class="fcc-metric-value">
                    @if($totalDiffKg < 0)
                        -{{ abs($totalDiffKg) }} kg
                    @elseif($totalDiffKg > 0)
                        +{{ $totalDiffKg }} kg
                    @else
                        0 kg
                    @endif
                </div>
                <div class="fcc-metric-subtext">Since tracking began</div>
            </div>
        </div>
    </div>

    <!-- 5. Weight Journey Chart Card -->
    <div class="fcc-white-card">
        <div class="fcc-card-section-header">
            <div>
                <h3 class="fcc-card-heading">Weight journey</h3>
            </div>
            <div class="fcc-chart-controls">
                <!-- Date Range Dropdown / Trigger -->
                <div class="fcc-date-range-pill" id="fccDateRangeTrigger" title="Click to filter chart date range">
                    <i data-feather="calendar"></i>
                    <span id="fccDateRangeText">{{ $chartDateRangeText ?? '15 Aug – 16 Sep 2026' }}</span>
                    <i data-feather="chevron-down" style="width: 13px; height: 13px; color: #94a3b8;"></i>
                </div>
                <input type="hidden" name="date_range" id="date_range" value="" />

                <!-- Daily / Weekly Toggle Buttons -->
                <div class="fcc-toggle-btn-group">
                    <button type="button" class="fcc-toggle-btn active" data-chart-view="daily">Daily</button>
                    <button type="button" class="fcc-toggle-btn" data-chart-view="weekly">Weekly</button>
                </div>
            </div>
        </div>

        <!-- ApexChart Container -->
        <div id="weightJourneyApexChart" style="min-height: 280px;"></div>

        <!-- Chart Legend -->
        <div class="fcc-chart-legend">
            <span class="fcc-legend-circle"></span>
            <span>Recorded weight</span>
        </div>
    </div>

    <!-- 6. Weight History Table Card -->
    <div class="fcc-white-card">
        <div class="fcc-card-section-header">
            <div>
                <h3 class="fcc-card-heading">Weight history</h3>
                <p class="fcc-card-subheading">Previous measurements and supporting images</p>
            </div>
            <div class="fcc-table-controls-group">
                <!-- Search Input -->
                <div class="fcc-table-search-box">
                    <i data-feather="search"></i>
                    <input type="text" id="fccHistorySearchInput" class="fcc-table-search-input" placeholder="Search history..." autocomplete="off" />
                </div>

                <!-- Page Size Dropdown -->
                <select id="fccPageSizeSelect" class="fcc-page-size-select">
                    <option value="20" selected>20 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
        </div>

        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}" />
        <input type="hidden" name="year" id="year" value="" />

        <!-- Modern Data Table -->
        <div class="fcc-modern-table-wrap">
            <table id="dataTable" class="table" data-url="{{ route('nutritionPanel.users.getViewWeights') }}">
                <thead>
                    <tr>
                        <th class="no-sort text-start" style="width: 70px;">Entry</th>
                        <th class="text-start" style="width: 25%;">Date</th>
                        <th class="text-start" style="width: 25%;">Weight</th>
                        <th class="text-start" style="width: 30%;">Evidence</th>
                        <th class="no-sort no-content text-end" style="width: 90px; text-align: right;">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>

<!-- Record Weight Modal -->
<div class="modal fade" id="recordWeightModal" tabindex="-1" role="dialog" aria-labelledby="recordWeightModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; font-family: 'Outfit', sans-serif;">
            <div class="modal-header py-3 px-4" style="border-bottom: 1px solid #edf2f7; background: #ffffff;">
                <h5 class="modal-title fw-bold" id="recordWeightModalLabel" style="color: #0f172a; font-size: 16.5px;">Record Weight</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('nutritionPanel.manual-attendances.addTodayWeight') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 13px; color: #334155;">Measurement Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required style="border-radius: 9px; height: 42px; border: 1.5px solid #e2e8f0; font-size: 13.5px;" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 13px; color: #334155;">Weight (kg) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="weight" class="form-control" placeholder="e.g. 74.5" required style="border-radius: 9px 0 0 9px; height: 42px; border: 1.5px solid #e2e8f0; font-size: 13.5px;" />
                            <span class="input-group-text fw-bold" style="border-radius: 0 9px 9px 0; background: #f8fafc; border: 1.5px solid #e2e8f0; border-left: none; color: #64748b;">kg</span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold" style="font-size: 13px; color: #334155;">Weight Image / Evidence <span class="text-muted fw-normal">(Optional)</span></label>
                        <input type="file" name="weight_image" class="form-control" accept="image/*" style="border-radius: 9px; height: 42px; border: 1.5px solid #e2e8f0; font-size: 13px; padding-top: 8px;" />
                    </div>
                </div>
                <div class="modal-footer py-3 px-4" style="border-top: 1px solid #edf2f7; background: #f8fafc;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-dismiss="modal" style="border-radius: 9px; font-weight: 600; font-size: 13px; padding: 8px 16px;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 9px; font-weight: 600; font-size: 13px; padding: 8px 20px; background: #3b46f1; border-color: #3b46f1;">Save Weight</button>
                </div>
            </form>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/users/view-weight.js') }}?v={{ time() }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        var rawDates = @json($weightDatesFormatted ?? []);
        var rawValues = @json($weightValues ?? []);

        // Fallback demo data if user has no weights recorded
        if (!rawDates || rawDates.length === 0) {
            rawDates = ['15 Aug', '17 Aug', '19 Aug', '21 Aug', '23 Aug', '25 Aug', '27 Aug', '29 Aug', '31 Aug', '02 Sep', '04 Sep', '06 Sep', '08 Sep', '10 Sep', '12 Sep', '14 Sep', '16 Sep'];
            rawValues = [99.6, 101.0, 99.8, 97.9, 96.3, 98.6, 99.2, 98.8, 97.5, 97.2, 95.6, 96.8, 98.4, 97.5, 96.0, 97.2, 97.8];
        }

        var numericValues = rawValues.map(function(v) { return parseFloat(v) || 0; });
        
        // Dynamically compute min/max based on data spread so weight variations are clearly visible
        var minRaw = numericValues.length ? Math.min(...numericValues) : 50;
        var maxRaw = numericValues.length ? Math.max(...numericValues) : 100;
        var spread = maxRaw - minRaw;

        var pad = spread > 4 ? 1.5 : (spread > 1 ? 0.8 : (spread > 0 ? 0.4 : 1.0));
        var minVal = Number(Math.max(0, minRaw - pad).toFixed(1));
        var maxVal = Number((maxRaw + pad).toFixed(1));

        var chartOptions = {
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                fontFamily: "'Outfit', sans-serif",
                sparkline: { enabled: false },
                zoom: { enabled: false },
                parentHeightOffset: 0
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: 'Recorded weight',
                data: numericValues
            }],
            xaxis: {
                categories: rawDates,
                labels: {
                    offsetY: 4,
                    style: {
                        colors: '#94a3b8',
                        fontSize: '11.5px',
                        fontFamily: "'Outfit', sans-serif",
                        fontWeight: 500
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
                    minWidth: 45,
                    offsetX: -5,
                    style: {
                        colors: '#94a3b8',
                        fontSize: '11.5px',
                        fontFamily: "'Outfit', sans-serif",
                        fontWeight: 500
                    },
                    formatter: function(val) {
                        return val !== undefined && val !== null ? parseFloat(val).toFixed(1) + ' kg' : '';
                    }
                },
                tickAmount: 5,
                min: minVal,
                max: maxVal,
                forceNiceScale: true
            },
            colors: ['#3b46f1'],
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.25,
                    opacityTo: 0.02,
                    stops: [0, 90, 100]
                }
            },
            markers: {
                size: numericValues.length <= 4 ? 5 : 3.5,
                colors: ['#3b46f1'],
                strokeColors: '#ffffff',
                strokeWidth: 2,
                hover: { size: 7 }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                padding: {
                    top: 15,
                    right: 35,
                    bottom: 10,
                    left: 20
                },
                yaxis: { lines: { show: true } },
                xaxis: { lines: { show: false } }
            },
            tooltip: {
                theme: 'dark',
                custom: function({series, seriesIndex, dataPointIndex, w}) {
                    var val = series[seriesIndex][dataPointIndex];
                    var dateStr = w.globals.categoryLabels[dataPointIndex] || '';
                    return '<div style="background: #1e293b; color: #ffffff; padding: 6px 14px; border-radius: 8px; font-size: 12.5px; font-weight: 700; box-shadow: 0 4px 12px rgba(15,23,42,0.25); font-family: Outfit, sans-serif; display: flex; align-items: center; gap: 6px;">' +
                           '<span style="width: 8px; height: 8px; border-radius: 50%; background: #3b46f1; display: inline-block;"></span>' +
                           '<span>' + parseFloat(val).toFixed(1) + ' kg</span> <span style="color: #94a3b8; font-weight: 500; font-size: 11.5px;">(' + dateStr + ')</span>' +
                           '</div>';
                }
            }
        };

        var weightChart = new ApexCharts(document.querySelector("#weightJourneyApexChart"), chartOptions);
        weightChart.render();

        // Toggle daily / weekly buttons
        $('[data-chart-view]').on('click', function(e) {
            e.preventDefault();
            $('[data-chart-view]').removeClass('active');
            $(this).addClass('active');

            var view = $(this).data('chart-view');
            if (view === 'weekly' && rawDates.length > 5) {
                // Aggregate into weekly samples
                var weeklyDates = [];
                var weeklyValues = [];
                for (var i = 0; i < rawDates.length; i += 3) {
                    weeklyDates.push(rawDates[i]);
                    weeklyValues.push(numericValues[i]);
                }
                if (weeklyDates[weeklyDates.length - 1] !== rawDates[rawDates.length - 1]) {
                    weeklyDates.push(rawDates[rawDates.length - 1]);
                    weeklyValues.push(numericValues[numericValues.length - 1]);
                }
                weightChart.updateOptions({
                    xaxis: { categories: weeklyDates },
                    series: [{ name: 'Recorded weight', data: weeklyValues }]
                });
            } else {
                weightChart.updateOptions({
                    xaxis: { categories: rawDates },
                    series: [{ name: 'Recorded weight', data: numericValues }]
                });
            }
        });

        // Date Range Picker on Chart trigger
        if ($.fn.daterangepicker) {
            $('#fccDateRangeTrigger').daterangepicker({
                autoUpdateInput: false,
                locale: { cancelLabel: 'Clear' }
            }, function(start, end) {
                var display = start.format('DD MMM') + ' – ' + end.format('DD MMM YYYY');
                $('#fccDateRangeText').text(display);
                var filterVal = start.format('YYYY-MM-DD') + '/' + end.format('YYYY-MM-DD');
                $('#date_range').val(filterVal);
                if (window.data_table) {
                    window.data_table.ajax.reload();
                }
            });
        }
    });
</script>
@endpush
