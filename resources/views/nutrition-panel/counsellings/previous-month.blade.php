@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Previous Month Counselling (' . $monthYearLabel . ') | ' . __('language.page_main_title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

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
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .fcc-month-badge {
        font-size: 13px;
        font-weight: 700;
        background: #eef2ff;
        color: #4338ca;
        border: 1px solid #c7d2fe;
        padding: 3px 10px;
        border-radius: 8px;
        vertical-align: middle;
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
        flex-wrap: wrap;
    }
    .fcc-btn-today {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        color: #334155;
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
    .fcc-btn-today:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
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

    /* Month Selector Strip */
    .fcc-month-navigator {
        background: linear-gradient(135deg, #ffffff 0%, #fbfcfe 100%);
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
    }
    .fcc-nav-arrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.16s ease;
    }
    .fcc-nav-arrow:hover {
        background: #f1f5f9;
        color: var(--fcc-primary);
        border-color: #cbd5e1;
    }
    .fcc-month-dropdown-select {
        height: 38px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        font-size: 13.5px;
        font-weight: 600;
        color: #0f172a;
        padding: 0 16px;
        background: #ffffff;
        cursor: pointer;
        min-width: 220px;
    }
    .fcc-month-dropdown-select:focus {
        border-color: var(--fcc-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.15);
    }

    /* KPI Cards Grid */
    .fcc-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }
    .fcc-kpi-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: transform 0.16s ease, box-shadow 0.16s ease;
    }
    .fcc-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
    }
    .fcc-kpi-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .fcc-kpi-title {
        font-size: 12.5px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0;
    }
    .fcc-kpi-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    .fcc-kpi-icon-indigo { background: #eef2ff; color: #4338ca; }
    .fcc-kpi-icon-blue   { background: #eff6ff; color: #2563eb; }
    .fcc-kpi-icon-green  { background: #ecfdf5; color: #059669; }
    .fcc-kpi-icon-amber  { background: #fffbeb; color: #d97706; }
    .fcc-kpi-icon-red    { background: #fef2f2; color: #dc2626; }

    .fcc-kpi-value {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.15;
        margin-bottom: 4px;
        letter-spacing: -0.02em;
    }
    .fcc-kpi-sub {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 0;
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

    /* Main Card */
    .fcc-counselling-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        padding: 20px;
    }

    /* Filter & Search Bar */
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
        padding-left: 40px !important;
        padding-right: 14px !important;
        font-size: 13.5px !important;
        color: #0f172a !important;
        font-weight: 500 !important;
        transition: all 0.16s ease !important;
    }
    input.fcc-search-input:focus,
    #fccSearchInput:focus {
        background-color: #ffffff !important;
        border-color: #3b46f1 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12) !important;
    }
    .fcc-filters-group {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .fcc-dropdown-pill {
        background: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 8px 14px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #475569 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        height: 40px !important;
        cursor: pointer !important;
        transition: all 0.16s ease !important;
    }
    .fcc-dropdown-pill:hover,
    .fcc-dropdown-pill.active-filter {
        border-color: #cbd5e1 !important;
        background: #f8fafc !important;
        color: #0f172a !important;
    }
    .fcc-btn-reset-filters {
        background: transparent !important;
        border: 1.5px dashed #cbd5e1 !important;
        border-radius: 10px !important;
        padding: 8px 12px !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        color: #64748b !important;
        height: 40px !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        transition: all 0.16s ease !important;
    }
    .fcc-btn-reset-filters:hover {
        border-color: #ef4444 !important;
        color: #dc2626 !important;
        background: #fef2f2 !important;
    }

    /* Table Toolbar */
    .fcc-table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 13px;
        color: #64748b;
    }
    .fcc-count-text {
        font-weight: 600;
        color: #0f172a;
    }
    .fcc-page-len-btn {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 12.5px;
        font-weight: 500;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    /* Table Badges & Styling */
    .fcc-att-pill {
        background: #eff6ff;
        color: #2563eb;
        font-weight: 700;
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .fcc-pending-pill {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 700;
        font-size: 12px;
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
        justify-content: center;
        white-space: nowrap !important;
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

    /* Checkbox */
    .fcc-custom-checkbox {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
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

    /* DataTables Footer */
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
        <a href="{{ route('nutritionPanel.counsellings.index') }}">Counselling</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <span class="fcc-breadcrumb-active">Previous Month ({{ $monthYearLabel }})</span>
    </div>

    <!-- 2. Header & Action Buttons -->
    <div class="fcc-page-header">
        <div class="fcc-header-title-box">
            <h1 class="fcc-page-title">
                <span>Previous Month Counselling</span>
                <span class="fcc-month-badge">{{ $monthYearLabel }}</span>
            </h1>
            <p class="fcc-page-subtitle">Historical archive of counselling records, weight transformations, and member progress</p>
        </div>
        <div class="fcc-header-btns">
            <a href="{{ route('nutritionPanel.counsellings.index') }}" class="btn fcc-btn-today" title="Back to Today's Counselling">
                <i data-feather="clock"></i>
                <span>Today's Counselling</span>
            </a>
            <button type="button" class="btn fcc-btn-export" id="fccExportBtn" title="Export data to Excel">
                <i data-feather="download"></i>
                <span>Export Excel</span>
            </button>
            <a href="{{ route('nutritionPanel.manual-attendances.manual-attendance') }}" class="btn fcc-btn-start-counselling">
                <i data-feather="plus" style="width: 16px; height: 16px;"></i>
                <span>Start counselling</span>
            </a>
        </div>
    </div>

    <!-- 3. Month & Year Navigator Bar -->
    <div class="fcc-month-navigator">
        <a href="{{ route('nutritionPanel.counsellings.previousMonth', ['month' => $prevNav->format('m'), 'year' => $prevNav->format('Y')]) }}" class="fcc-nav-arrow" title="View {{ $prevNav->format('F Y') }}">
            <i data-feather="chevron-left" style="width: 15px; height: 15px;"></i>
            <span>{{ $prevNav->format('M Y') }}</span>
        </a>

        <div class="d-flex align-items-center gap-2">
            <label for="monthSelectDropdown" class="text-muted fw-bold mb-0 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Switch Month:</label>
            <select id="monthSelectDropdown" class="fcc-month-dropdown-select" onchange="window.location.href = this.value;">
                @foreach($monthOptions as $opt)
                    <option value="{{ route('nutritionPanel.counsellings.previousMonth', ['month' => $opt['month'], 'year' => $opt['year']]) }}" {{ $opt['is_selected'] ? 'selected' : '' }}>
                        {{ $opt['label'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <a href="{{ route('nutritionPanel.counsellings.previousMonth', ['month' => $nextNav->format('m'), 'year' => $nextNav->format('Y')]) }}" class="fcc-nav-arrow" title="View {{ $nextNav->format('F Y') }}">
            <span>{{ $nextNav->format('M Y') }}</span>
            <i data-feather="chevron-right" style="width: 15px; height: 15px;"></i>
        </a>
    </div>

    <!-- 4. Monthly KPI Metric Cards Grid -->
    <div class="fcc-kpi-grid">
        <!-- Sessions Conducted -->
        <div class="fcc-kpi-card">
            <div class="fcc-kpi-top">
                <p class="fcc-kpi-title">Monthly Sessions</p>
                <div class="fcc-kpi-icon-box fcc-kpi-icon-indigo">
                    <i data-feather="calendar" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div class="fcc-kpi-value">{{ number_format($totalMonthlySessions) }}</div>
            <p class="fcc-kpi-sub">Check-ins in {{ $monthName }}</p>
        </div>

        <!-- Unique Members -->
        <div class="fcc-kpi-card">
            <div class="fcc-kpi-top">
                <p class="fcc-kpi-title">Members Counselled</p>
                <div class="fcc-kpi-icon-box fcc-kpi-icon-blue">
                    <i data-feather="users" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div class="fcc-kpi-value">{{ number_format($uniqueMembersCount) }}</div>
            <p class="fcc-kpi-sub">Unique active members</p>
        </div>

        <!-- Weight Loss Achievers -->
        <div class="fcc-kpi-card">
            <div class="fcc-kpi-top">
                <p class="fcc-kpi-title">Weight Loss Achievers</p>
                <div class="fcc-kpi-icon-box fcc-kpi-icon-green">
                    <i data-feather="trending-down" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div class="fcc-kpi-value">{{ $weightLossCount }}</div>
            <p class="fcc-kpi-sub">{{ $totalWeightLost }} kg total reduced</p>
        </div>

        <!-- Active Coaches -->
        <div class="fcc-kpi-card">
            <div class="fcc-kpi-top">
                <p class="fcc-kpi-title">Active Coaches</p>
                <div class="fcc-kpi-icon-box fcc-kpi-icon-amber">
                    <i data-feather="award" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div class="fcc-kpi-value">{{ $activeCoachesCount }}</div>
            <p class="fcc-kpi-sub">Conducted counselling</p>
        </div>

        <!-- Dues Flagged -->
        <div class="fcc-kpi-card">
            <div class="fcc-kpi-top">
                <p class="fcc-kpi-title">Dues Flagged</p>
                <div class="fcc-kpi-icon-box fcc-kpi-icon-red">
                    <i data-feather="alert-triangle" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div class="fcc-kpi-value" style="color: #dc2626;">₹{{ number_format($duesFlagged, 0) }}</div>
            <p class="fcc-kpi-sub">Outstanding among counselled</p>
        </div>
    </div>

    <!-- 5. Navigation Tabs -->
    <div class="fcc-counselling-tabs">
        <a href="javascript:;" class="fcc-tab-item-link active" data-tab="all_sessions">
            <span>All Month Sessions ({{ $totalMonthlySessions }})</span>
        </a>
        <a href="javascript:;" class="fcc-tab-item-link" data-tab="member_summary">
            <span>Member Progress Summary ({{ $uniqueMembersCount }})</span>
        </a>
        <a href="javascript:;" class="fcc-tab-item-link" data-tab="weight_loss">
            <span>Weight Loss Achievers ({{ $weightLossCount }})</span>
        </a>
        <a href="javascript:;" class="fcc-tab-item-link" data-tab="pending_dues">
            <span>Pending Dues</span>
        </a>
    </div>

    <!-- 6. Main Card Container -->
    <div class="fcc-counselling-card">
        
        <!-- Hidden Inputs for Filtering -->
        <input type="hidden" name="tab" id="active_tab" value="all_sessions" />
        <input type="hidden" name="coach_name" id="coach_name" value="" />
        <input type="hidden" name="plan_id" id="plan_id" value="" />
        <input type="hidden" name="month" id="selected_month" value="{{ $selectedMonth }}" />
        <input type="hidden" name="year" id="selected_year" value="{{ $selectedYear }}" />

        <!-- Filter & Search Bar -->
        <div class="fcc-filter-bar">
            <!-- Search Box -->
            <div class="fcc-search-wrap">
                <i data-feather="search"></i>
                <input type="text" id="fccSearchInput" class="fcc-search-input" placeholder="Search member, mobile, coach..." autocomplete="off" />
            </div>

            <!-- Filter Dropdowns Group -->
            <div class="fcc-filters-group">
                <!-- Coach Filter -->
                <div class="dropdown">
                    <button class="btn fcc-dropdown-pill dropdown-toggle" type="button" id="coachFilterDropdown" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span id="coachFilterLabel">All coaches</span>
                        <i data-feather="chevron-down" style="width: 13px; height: 13px;"></i>
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
                        <i data-feather="chevron-down" style="width: 13px; height: 13px;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="planFilterDropdown" style="border-radius: 12px; min-width: 200px; padding: 6px; border: 1px solid #edf2f7 !important;">
                        <li><a class="dropdown-item py-2 px-3 rounded-2 active" href="javascript:;" data-filter-type="plan" data-value="">All meal plans</a></li>
                        @foreach($mealTypes ?? [] as $mt)
                            <li><a class="dropdown-item py-2 px-3 rounded-2" href="javascript:;" data-filter-type="plan" data-value="{{ $mt->id }}">{{ $mt->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Reset Filters Button -->
                <button type="button" class="btn fcc-btn-reset-filters" id="fccResetAllFiltersBtn" title="Reset all filters">
                    <i data-feather="refresh-cw" style="width: 13px; height: 13px;"></i>
                    <span>Reset</span>
                </button>
            </div>
        </div>

        <!-- 7. Table Toolbar -->
        <div class="fcc-table-toolbar">
            <div class="d-flex align-items-center gap-2">
                <span class="fcc-count-text" id="fccTableCountDisplay">
                    {{ $totalMonthlySessions }} sessions
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
            <table id="previousMonthDataTable" class="table table-hover dataTable" data-url="{{ route('nutritionPanel.counsellings.getPreviousMonthCounsellings') }}">
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
                        <th style="width: 70px;">Month Att.</th>
                        <th>Coach</th>
                        <th>Plan</th>
                        <th style="width: 65px;">Pending</th>
                        <th>Progress</th>
                        <th style="width: 75px;">Dues</th>
                        <th style="width: 90px;">Meal</th>
                        <th>Session Date</th>
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
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/counsellings/previous-month.js') }}?v={{ file_exists(public_path('admin-assets/js/counsellings/previous-month.js')) ? filemtime(public_path('admin-assets/js/counsellings/previous-month.js')) : time() }}"></script>
@endpush
