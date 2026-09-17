@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Counselling | ' . __('language.page_main_title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">

<style>
    :root {
        --fcc-primary: #3b46f1;
        --fcc-primary-hover: #2d38db;
        --fcc-dark: #0f172a;
        --fcc-muted: #64748b;
        --fcc-border: #edf2f7;
        --fcc-bg-card: #ffffff;
        --fcc-page-bg: #f8fafc;
    }

    body {
        background-color: var(--fcc-page-bg) !important;
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif !important;
    }

    .fcc-counselling-wrapper {
        padding: 20px 24px 40px 24px;
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
    .fcc-btn-export svg,
    .fcc-btn-export i {
        width: 15px;
        height: 15px;
        color: #3b46f1;
    }
    .fcc-btn-export:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    .fcc-btn-start-counselling {
        background: #3b46f1;
        border: 1.5px solid #3b46f1;
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
    .fcc-btn-start-counselling:hover {
        background: #2d38db;
        border-color: #2d38db;
        box-shadow: 0 6px 16px rgba(59, 70, 241, 0.38);
    }

    /* 3. Navigation Tabs */
    .fcc-counselling-tabs {
        display: flex;
        align-items: center;
        gap: 28px;
        border-bottom: 1.5px solid #e2e8f0;
        margin-bottom: 18px;
        padding-bottom: 0;
        overflow-x: auto;
    }
    .fcc-tab-item-link {
        font-size: 14px;
        font-weight: 500;
        color: #64748b;
        text-decoration: none;
        padding: 0 2px 14px 2px;
        position: relative;
        transition: color 0.15s ease;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
    }
    .fcc-tab-item-link:hover {
        color: var(--fcc-primary);
    }
    .fcc-tab-item-link.active {
        color: var(--fcc-primary);
        font-weight: 700;
    }
    .fcc-tab-item-link.active::after {
        content: '';
        position: absolute;
        bottom: -1.5px;
        left: 0;
        right: 0;
        height: 2.5px;
        background: var(--fcc-primary);
        border-radius: 3px 3px 0 0;
    }

    /* 4. Horizontal Summary Strip */
    .fcc-summary-strip {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
        margin-bottom: 18px;
    }
    .fcc-summary-item {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 13.5px;
        color: #334155;
    }
    .fcc-summary-item i,
    .fcc-summary-item svg {
        font-size: 17px;
    }
    .fcc-summary-item strong {
        font-weight: 700;
        color: #0f172a;
    }
    .fcc-summary-sep {
        height: 20px;
        width: 1px;
        background: #e2e8f0;
    }

    /* 5. Main Card Container */
    .fcc-counselling-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        padding: 20px;
    }

    /* 6. Filter & Search Bar */
    .fcc-filter-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }
    .fcc-search-wrap {
        position: relative !important;
        flex-grow: 1;
        max-width: 320px !important;
        min-width: 220px !important;
        display: flex !important;
        align-items: center !important;
    }
    .fcc-search-wrap svg,
    .fcc-search-wrap i {
        position: absolute !important;
        left: 14px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #94a3b8 !important;
        width: 16px !important;
        height: 16px !important;
        pointer-events: none !important;
        z-index: 5 !important;
    }
    input.fcc-search-input,
    #fccSearchInput {
        width: 100% !important;
        height: 40px !important;
        background-color: #f8fafc !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding-left: 42px !important;
        padding-right: 14px !important;
        font-size: 13.5px !important;
        color: #0f172a !important;
        transition: all 0.18s ease !important;
        box-shadow: none !important;
    }
    input.fcc-search-input:focus,
    #fccSearchInput:focus {
        background-color: #ffffff !important;
        border-color: var(--fcc-primary) !important;
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12) !important;
        outline: none !important;
    }
    .fcc-filters-group {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
    }
    .fcc-dropdown-pill {
        background: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 0 14px !important;
        height: 40px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #334155 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        transition: all 0.16s ease !important;
        cursor: pointer !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03) !important;
    }
    .fcc-dropdown-pill:hover,
    .fcc-dropdown-pill[aria-expanded="true"] {
        border-color: #cbd5e1 !important;
        background: #f8fafc !important;
        color: #0f172a !important;
    }
    .fcc-dropdown-pill.active-filter {
        background: #eff6ff !important;
        border-color: #93c5fd !important;
        color: #1d4ed8 !important;
    }
    .fcc-dropdown-pill svg.fcc-chevron {
        width: 13px !important;
        height: 13px !important;
        color: #94a3b8 !important;
    }

    /* 7. Table Toolbar */
    .fcc-table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 14px;
    }
    .fcc-count-text {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }
    .fcc-page-len-btn {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 12.5px;
        font-weight: 600;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 32px;
        cursor: pointer;
    }

    /* 8. Table Styling & Pills */
    .fcc-modern-table-wrap {
        overflow-x: auto;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        background: #ffffff;
        margin-bottom: 0 !important;
        width: 100%;
    }
    table.dataTable {
        margin: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
        min-width: 1050px !important;
    }
    table.dataTable thead th {
        position: relative !important;
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        letter-spacing: 0.02em !important;
        padding: 12px 14px !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-top: none !important;
        white-space: nowrap !important;
        vertical-align: middle !important;
    }
    table.dataTable tbody td {
        padding: 12px 14px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #334155;
        font-size: 13px;
        background: transparent;
        white-space: nowrap !important;
    }
    table.dataTable tbody tr:hover td {
        background: #f8faff !important;
    }
    table.dataTable tbody tr.fcc-row-dues-flagged td {
        background: #fff8f8;
    }
    table.dataTable tbody tr.fcc-row-dues-flagged:hover td {
        background: #fff1f1 !important;
    }

    /* Custom Checkbox */
    .fcc-custom-checkbox {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin: 0 !important;
        user-select: none;
    }
    .fcc-custom-checkbox input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        margin: 0;
    }
    .fcc-checkbox-control {
        width: 18px;
        height: 18px;
        border: 1.8px solid #cbd5e1;
        border-radius: 5px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.16s ease;
    }
    .fcc-custom-checkbox:hover .fcc-checkbox-control {
        border-color: var(--fcc-primary);
        background: #f8faff;
    }
    .fcc-custom-checkbox input[type="checkbox"]:checked + .fcc-checkbox-control {
        background: #3b46f1 !important;
        border-color: #3b46f1 !important;
    }
    .fcc-custom-checkbox input[type="checkbox"]:checked + .fcc-checkbox-control .fcc-check-icon {
        opacity: 1;
        transform: scale(1);
    }
    .fcc-check-icon {
        width: 10px;
        height: 10px;
        stroke: #ffffff;
        stroke-width: 2.4;
        fill: none;
        stroke-linecap: round;
        stroke-linejoin: round;
        opacity: 0;
        transform: scale(0.6);
        transition: all 0.15s ease;
    }

    /* Table Column Badges & Pills matching image */
    .fcc-att-pill {
        background: #eff6ff;
        color: #2563eb;
        font-weight: 700;
        font-size: 12.5px;
        width: 28px;
        height: 28px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .fcc-pending-pill {
        background: #eff6ff;
        color: #2563eb;
        font-weight: 700;
        font-size: 12.5px;
        width: 30px;
        height: 28px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .fcc-coach-name, .fcc-plan-name {
        font-size: 13px;
        font-weight: 500;
        color: #334155;
    }
    .fcc-weight-val {
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        min-width: 60px;
    }
    .fcc-progress-pill {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 5px;
        white-space: nowrap;
    }
    .fcc-pill-loss {
        background: #dcfce7;
        color: #16a34a;
    }
    .fcc-pill-gain {
        background: #ffedd5;
        color: #ea580c;
    }
    .fcc-pill-neutral {
        background: #f1f5f9;
        color: #64748b;
    }
    .fcc-dues-flagged {
        background: #fee2e2;
        color: #dc2626;
        font-weight: 700;
        font-size: 12px;
        padding: 2px 8px;
        border-radius: 6px;
        display: inline-block;
    }
    .fcc-btn-view-meal {
        background: transparent;
        border: 1.5px solid #3b46f1;
        color: #3b46f1 !important;
        font-size: 12px;
        font-weight: 600;
        padding: 3px 12px;
        border-radius: 7px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.16s ease;
    }
    .fcc-btn-view-meal:hover {
        background: #3b46f1;
        color: #ffffff !important;
    }
    .fcc-action-dots-btn {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #3b46f1 !important;
        font-size: 16px;
        text-decoration: none;
        cursor: pointer;
        border-radius: 6px;
        letter-spacing: 2px;
    }
    .fcc-action-dots-btn:hover {
        background: #eff2fe;
    }

    /* 9. DataTables Footer */
    .fcc-dt-footer {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 14px !important;
        padding-top: 16px !important;
        border-top: 1px solid #f1f5f9 !important;
        margin-top: 12px !important;
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
        min-width: 34px !important;
        height: 34px !important;
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
<div class="fcc-counselling-wrapper">

    <!-- 1. Breadcrumbs -->
    <div class="fcc-breadcrumb-nav">
        <a href="javascript:;">Offline system</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <span class="fcc-breadcrumb-active">Counselling</span>
    </div>

    <!-- 2. Header & Action Buttons -->
    <div class="fcc-page-header">
        <div class="fcc-header-title-box">
            <h1 class="fcc-page-title">Counselling</h1>
            <p class="fcc-page-subtitle">Review completed sessions, member progress and meal follow-ups</p>
        </div>
        <div class="fcc-header-btns">
            <button type="button" class="btn fcc-btn-export" id="fccExportBtn" title="Export data to Excel">
                <i data-feather="download"></i>
                <span>Export</span>
            </button>
            <a href="{{ route('nutritionPanel.manual-attendances.manual-attendance') }}" class="btn fcc-btn-start-counselling">
                <i data-feather="plus" style="width: 16px; height: 16px;"></i>
                <span>Start counselling</span>
            </a>
        </div>
    </div>

    <!-- 3. Navigation Tabs -->
    <div class="fcc-counselling-tabs">
        <a href="javascript:;" class="fcc-tab-item-link" data-tab="today">
            <span>Today's sessions</span>
        </a>
        <a href="javascript:;" class="fcc-tab-item-link active" data-tab="completed">
            <span>Completed</span>
        </a>
        <a href="javascript:;" class="fcc-tab-item-link" data-tab="pending">
            <span>Pending follow-ups</span>
        </a>
    </div>

    <!-- 4. Horizontal Summary Strip -->
    <div class="fcc-summary-strip">
        <div class="fcc-summary-item">
            <i class="fa fa-check-circle" style="color: #10b981;"></i>
            <span><strong>{{ $todayCompletedCount ?? 12 }}</strong> completed</span>
        </div>
        <div class="fcc-summary-sep"></div>
        <div class="fcc-summary-item">
            <i class="fa fa-file-text-o" style="color: #8b5cf6;"></i>
            <span><strong>{{ $mealPlansCount ?? 9 }}</strong> meal plans available</span>
        </div>
        <div class="fcc-summary-sep"></div>
        <div class="fcc-summary-item">
            <i class="fa fa-exclamation-triangle" style="color: #ef4444;"></i>
            <span><strong style="color: #ef4444;">₹{{ number_format($duesFlagged ?? 6000, 0) }}</strong> dues flagged</span>
        </div>
        <div class="fcc-summary-sep"></div>
        <div class="fcc-summary-item">
            <i class="fa fa-clock-o" style="color: #3b46f1;"></i>
            <span>Last session <strong>{{ $lastSessionTime ?? '09:41 AM' }}</strong></span>
        </div>
    </div>

    <!-- 5. Main White Card Container -->
    <div class="fcc-counselling-card">
        
        <!-- Hidden Inputs for Filtering -->
        <input type="hidden" name="tab" id="active_tab" value="completed" />
        <input type="hidden" name="coach_name" id="coach_name" value="" />
        <input type="hidden" name="plan_id" id="plan_id" value="" />
        <input type="hidden" name="date" id="date" value="{{ date('d-m-Y') }}" />

        <!-- 6. Filter & Search Bar -->
        <div class="fcc-filter-bar">
            <!-- Search Box -->
            <div class="fcc-search-wrap">
                <i data-feather="search"></i>
                <input type="text" id="fccSearchInput" class="fcc-search-input" placeholder="Search member..." autocomplete="off" />
            </div>

            <!-- Filter Dropdowns Group -->
            <div class="fcc-filters-group">
                <!-- Date Filter Dropdown -->
                <div class="dropdown">
                    <button class="btn fcc-dropdown-pill dropdown-toggle" type="button" id="dateFilterDropdown" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="calendar" style="width: 14px; height: 14px; color: var(--fcc-primary);"></i>
                        <span id="dateFilterLabel">Today, {{ date('d M Y') }}</span>
                        <i data-feather="chevron-down" class="fcc-chevron"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-3" aria-labelledby="dateFilterDropdown" style="border-radius: 14px; min-width: 290px; border: 1px solid #edf2f7 !important; background: #ffffff;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span style="font-size: 11.5px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Select Date</span>
                            <a href="javascript:;" id="fccResetDateBtn" class="text-primary fw-semibold" style="font-size: 11.5px; text-decoration: none;">Today</a>
                        </div>
                        <div class="position-relative mb-2">
                            <input type="text" name="filter_date" id="filter_date" class="form-control date-picker" placeholder="Select Date..." autocomplete="off" value="{{ date('d-m-Y') }}" style="border-radius: 9px; font-size: 12.5px; height: 38px; padding-left: 12px !important; border: 1.5px solid #e2e8f0;" />
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <button type="button" class="btn btn-primary btn-sm w-100 apply-date-filter" style="border-radius: 8px; font-weight: 600; height: 34px;">Apply Date</button>
                        </div>
                    </div>
                </div>

                <!-- Coach Filter -->
                <div class="dropdown">
                    <button class="btn fcc-dropdown-pill dropdown-toggle" type="button" id="coachFilterDropdown" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span id="coachFilterLabel">All coaches</span>
                        <i data-feather="chevron-down" class="fcc-chevron"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="coachFilterDropdown" style="border-radius: 12px; min-width: 170px; padding: 6px; border: 1px solid #edf2f7 !important;">
                        <li><a class="dropdown-item py-2 px-3 rounded-2 active" href="javascript:;" data-filter-type="coach" data-value="">All coaches</a></li>
                        @foreach($coachesList ?? [] as $coach)
                            @if(!empty($coach))
                                <li><a class="dropdown-item py-2 px-3 rounded-2" href="javascript:;" data-filter-type="coach" data-value="{{ $coach }}">{{ $coach }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <!-- Meal Plan Filter -->
                <div class="dropdown">
                    <button class="btn fcc-dropdown-pill dropdown-toggle" type="button" id="planFilterDropdown" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span id="planFilterLabel">All meal plans</span>
                        <i data-feather="chevron-down" class="fcc-chevron"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="planFilterDropdown" style="border-radius: 12px; min-width: 200px; padding: 6px; border: 1px solid #edf2f7 !important;">
                        <li><a class="dropdown-item py-2 px-3 rounded-2 active" href="javascript:;" data-filter-type="plan" data-value="">All meal plans</a></li>
                        @foreach($mealTypes ?? [] as $mt)
                            <li><a class="dropdown-item py-2 px-3 rounded-2" href="javascript:;" data-filter-type="plan" data-value="{{ $mt->id }}">{{ $mt->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- More Filters Dropdown -->
                <div class="dropdown">
                    <button class="btn fcc-dropdown-pill dropdown-toggle" type="button" id="moreFiltersDropdown" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="filter" style="width: 13px; height: 13px;"></i>
                        <span>More filters</span>
                        <i data-feather="chevron-down" class="fcc-chevron"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="moreFiltersDropdown" style="border-radius: 12px; min-width: 170px; padding: 6px; border: 1px solid #edf2f7 !important;">
                        <li><a class="dropdown-item py-2 px-3 rounded-2" href="javascript:;" id="fccResetAllFiltersBtn"><i class="fa fa-refresh me-2 text-muted"></i> Reset All Filters</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- 7. Table Toolbar -->
        <div class="fcc-table-toolbar">
            <div class="d-flex align-items-center gap-2">
                <span class="fcc-count-text" id="fccTableCountDisplay">
                    {{ $todayCompletedCount ?? 12 }} completed sessions
                </span>
                
                <!-- Page Size Selector -->
                <div class="dropdown">
                    <button class="btn fcc-page-len-btn dropdown-toggle" type="button" id="pageSizeDropdown" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span id="pageSizeLabel">25 per page</span>
                        <i data-feather="chevron-down" style="width: 12px; height: 12px;"></i>
                    </button>
                    <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="pageSizeDropdown" style="border-radius: 10px; min-width: 130px; padding: 4px; border: 1px solid #edf2f7 !important;">
                        <li><a class="dropdown-item py-1 px-3 rounded-2" href="javascript:;" data-page-size="10">10 per page</a></li>
                        <li><a class="dropdown-item py-1 px-3 rounded-2 active" href="javascript:;" data-page-size="25">25 per page</a></li>
                        <li><a class="dropdown-item py-1 px-3 rounded-2" href="javascript:;" data-page-size="50">50 per page</a></li>
                        <li><a class="dropdown-item py-1 px-3 rounded-2" href="javascript:;" data-page-size="100">100 per page</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- 8. Modern DataTables Table -->
        <div class="data-table-container">
            <table id="dataTable" class="table table-hover dataTable" data-url="{{ route('nutritionPanel.counsellings.getCounsellings') }}">
                <thead>
                    <tr>
                        <th class="checkbox-column no-sort no-content text-center" style="width: 36px;">
                            <label class="fcc-custom-checkbox m-0">
                                <input type="checkbox" name="select_all" class="fcc-checkbox-input chk-parent" id="select-all-counsellings">
                                <span class="fcc-checkbox-control">
                                    <svg viewBox="0 0 12 10" class="fcc-check-icon"><polyline points="1.5 6 4.5 9 10.5 1"></polyline></svg>
                                </span>
                            </label>
                        </th>
                        <th>Member</th>
                        <th style="width: 50px;">Att.</th>
                        <th>Coach</th>
                        <th>Plan</th>
                        <th style="width: 65px;">Pending</th>
                        <th>Progress</th>
                        <th style="width: 75px;">Dues</th>
                        <th style="width: 90px;">Meal</th>
                        <th>Completed at</th>
                        <th class="text-end no-sort no-content" style="width: 50px;">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/counsellings/view.js') }}"></script>

<script>
$(document).ready(function() {
    feather.replace();

    function reloadDataTable() {
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().ajax.reload();
        }
    }

    // Tabs navigation
    $('.fcc-tab-item-link').on('click', function(e) {
        e.preventDefault();
        $('.fcc-tab-item-link').removeClass('active');
        $(this).addClass('active');
        var tab = $(this).data('tab');
        $('#active_tab').val(tab);
        reloadDataTable();
    });

    // Live search input with debounce
    var searchTimer;
    $('#fccSearchInput').on('keyup input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            reloadDataTable();
        }, 300);
    });

    // Coach filter selection
    $(document).on('click', '[data-filter-type="coach"]', function(e) {
        e.preventDefault();
        var val = $(this).data('value');
        var label = val ? val : 'All coaches';
        $('#coach_name').val(val);
        $('#coachFilterLabel').text(label);
        $('[data-filter-type="coach"]').removeClass('active');
        $(this).addClass('active');
        if (val) {
            $('#coachFilterDropdown').addClass('active-filter');
        } else {
            $('#coachFilterDropdown').removeClass('active-filter');
        }
        reloadDataTable();
    });

    // Plan filter selection
    $(document).on('click', '[data-filter-type="plan"]', function(e) {
        e.preventDefault();
        var val = $(this).data('value');
        var label = $(this).text();
        $('#plan_id').val(val);
        $('#planFilterLabel').text(label);
        $('[data-filter-type="plan"]').removeClass('active');
        $(this).addClass('active');
        if (val) {
            $('#planFilterDropdown').addClass('active-filter');
        } else {
            $('#planFilterDropdown').removeClass('active-filter');
        }
        reloadDataTable();
    });

    // Date picker initialization
    if ($.fn.datepicker) {
        $('#filter_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        }).on('changeDate', function(e) {
            var formatted = moment(e.date).format('DD-MM-YYYY');
            var display = moment(e.date).format('DD MMM YYYY');
            $('#date').val(formatted);
            $('#dateFilterLabel').text(display);
            $('#dateFilterDropdown').addClass('active-filter');
        });
    }

    // Apply Date Filter button
    $('.apply-date-filter').on('click', function(e) {
        e.preventDefault();
        var dateVal = $('#filter_date').val();
        $('#date').val(dateVal);
        if (dateVal) {
            var display = moment(dateVal, 'DD-MM-YYYY').format('DD MMM YYYY');
            $('#dateFilterLabel').text(display);
            $('#dateFilterDropdown').addClass('active-filter');
        }
        reloadDataTable();
    });

    // Reset date to today
    $('#fccResetDateBtn').on('click', function(e) {
        e.preventDefault();
        var todayStr = moment().format('DD-MM-YYYY');
        var display = 'Today, ' + moment().format('DD MMM YYYY');
        $('#filter_date').val(todayStr);
        $('#date').val(todayStr);
        $('#dateFilterLabel').text(display);
        $('#dateFilterDropdown').removeClass('active-filter');
        reloadDataTable();
    });

    // Reset all filters
    $('#fccResetAllFiltersBtn').on('click', function(e) {
        e.preventDefault();
        $('#fccSearchInput').val('');
        $('#coach_name').val('');
        $('#coachFilterLabel').text('All coaches');
        $('#coachFilterDropdown').removeClass('active-filter');
        $('[data-filter-type="coach"]').removeClass('active');
        $('[data-filter-type="coach"][data-value=""]').addClass('active');

        $('#plan_id').val('');
        $('#planFilterLabel').text('All meal plans');
        $('#planFilterDropdown').removeClass('active-filter');
        $('[data-filter-type="plan"]').removeClass('active');
        $('[data-filter-type="plan"][data-value=""]').addClass('active');

        var todayStr = moment().format('DD-MM-YYYY');
        $('#filter_date').val(todayStr);
        $('#date').val(todayStr);
        $('#dateFilterLabel').text('Today, ' + moment().format('DD MMM YYYY'));
        $('#dateFilterDropdown').removeClass('active-filter');

        reloadDataTable();
    });

    // Page size dropdown selection
    $(document).on('click', '[data-page-size]', function(e) {
        e.preventDefault();
        var size = parseInt($(this).data('page-size'));
        $('#pageSizeLabel').text(size + ' per page');
        $('[data-page-size]').removeClass('active');
        $(this).addClass('active');
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().page.len(size).draw();
        }
    });

    // Export button triggers datatable excel export
    $('#fccExportBtn').on('click', function(e) {
        e.preventDefault();
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            var dt = $('#dataTable').DataTable();
            dt.button('.buttons-excel').trigger();
        }
    });

    // Checkbox select all
    $('#select-all-counsellings').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('.child-chk').prop('checked', isChecked);
    });
});
</script>
@endpush
