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

    /* Chart Legend Bar */
    .fcc-shake-legend-bar {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid #f8fafc;
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
    .fcc-legend-circle.orange { background: #f97316; }

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
        background-color: #f8fafc !important;
        color: #64748b !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        padding: 12px 16px !important;
        border-top: 1px solid #edf2f7 !important;
        border-bottom: 1.5px solid #edf2f7 !important;
        border-left: none !important;
        border-right: none !important;
        white-space: nowrap;
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
                </div>
                <div class="fcc-range-badge-pill" id="fccTrendRangeBadge">
                    {{ $trendRangeText }}
                </div>
            </div>
        </div>

        <!-- ApexChart Container -->
        <div id="shakeBalanceApexChart" style="min-height: 270px;"></div>

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
                <span>Attendance added</span>
            </div>
            <div class="fcc-shake-legend-item">
                <span class="fcc-legend-circle orange"></span>
                <span>Attendance deleted</span>
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
        <div class="fcc-modern-table-wrap">
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

        // ApexChart Data
        var rawDates = @json($chartDates ?? []);
        var rawBalances = @json($chartBalances ?? []);

        var numericBalances = rawBalances.map(function(v) { return parseInt(v, 10) || 0; });
        var minBal = numericBalances.length ? Math.max(0, Math.floor(Math.min(...numericBalances) - 5)) : 0;
        var maxBal = numericBalances.length ? Math.ceil(Math.max(...numericBalances) + 5) : 40;

        var chartOptions = {
            chart: {
                type: 'area',
                height: 270,
                toolbar: { show: false },
                fontFamily: "'Outfit', sans-serif",
                sparkline: { enabled: false },
                zoom: { enabled: false }
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: 'Shake balance',
                data: numericBalances
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
                    minWidth: 35,
                    offsetX: -4,
                    style: {
                        colors: '#94a3b8',
                        fontSize: '11.5px',
                        fontFamily: "'Outfit', sans-serif",
                        fontWeight: 500
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
                size: numericBalances.length <= 4 ? 5 : 3.5,
                colors: ['#3b46f1'],
                strokeColors: '#ffffff',
                strokeWidth: 2,
                hover: { size: 6.5 }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                padding: {
                    top: 10,
                    right: 25,
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
                    var dateStr = w.globals.categoryLabels[dataPointIndex] || '';
                    return '<div style="background: #1e293b; color: #ffffff; padding: 6px 14px; border-radius: 8px; font-size: 12.5px; font-weight: 700; box-shadow: 0 4px 12px rgba(15,23,42,0.25); font-family: Outfit, sans-serif; display: flex; align-items: center; gap: 6px;">' +
                           '<span style="width: 8px; height: 8px; border-radius: 50%; background: #3b46f1; display: inline-block;"></span>' +
                           '<span>' + val + ' shakes</span> <span style="color: #94a3b8; font-weight: 500; font-size: 11.5px;">(' + dateStr + ')</span>' +
                           '</div>';
                }
            }
        };

        var shakeChart = new ApexCharts(document.querySelector("#shakeBalanceApexChart"), chartOptions);
        shakeChart.render();

        // 7 days vs 30 days toggle
        $('[data-chart-days]').on('click', function(e) {
            e.preventDefault();
            $('[data-chart-days]').removeClass('active');
            $(this).addClass('active');

            var days = parseInt($(this).data('chart-days'), 10);
            if (days === 7 && rawDates.length > 7) {
                var slicedDates = rawDates.slice(-7);
                var slicedBalances = numericBalances.slice(-7);
                shakeChart.updateOptions({
                    xaxis: { categories: slicedDates },
                    series: [{ name: 'Shake balance', data: slicedBalances }]
                });
                $('#fccTrendRangeBadge').text(slicedDates[0] + ' – ' + slicedDates[slicedDates.length - 1]);
            } else {
                shakeChart.updateOptions({
                    xaxis: { categories: rawDates },
                    series: [{ name: 'Shake balance', data: numericBalances }]
                });
                $('#fccTrendRangeBadge').text(rawDates[0] + ' – ' + rawDates[rawDates.length - 1]);
            }
        });
    });
</script>
@endpush
