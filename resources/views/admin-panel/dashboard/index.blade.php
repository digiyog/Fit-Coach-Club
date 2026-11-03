@extends('admin-panel.layouts.main-layout')

@section('page-title', ' ' . __('language.dashboard_page_title') . ' | ' . __('language.page_main_title') . '')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/plugins/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin-assets/js/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css">

<style>
    /* ==========================================================================
       SUPER ADMIN DASHBOARD EXECUTIVE MODERN REDESIGN
       ========================================================================== */
    :root {
        --fcc-primary: #1d4ed8;
        --fcc-primary-light: #3b82f6;
        --fcc-dark: #0f172a;
        --fcc-slate: #1e293b;
        --fcc-muted: #64748b;
        --fcc-border: #e2e8f0;
        --fcc-bg: #f8fafc;
        --fcc-card-bg: #ffffff;
        --fcc-success: #10b981;
        --fcc-warning: #f59e0b;
        --fcc-danger: #ef4444;
        --fcc-card-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        --fcc-card-hover-shadow: 0 10px 25px -4px rgba(15, 23, 42, 0.08);
    }

    .admin-dashboard-wrap {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--fcc-slate);
        padding: 4px 2px 40px 2px;
    }

    .admin-dashboard-wrap h1,
    .admin-dashboard-wrap h2,
    .admin-dashboard-wrap h3,
    .admin-dashboard-wrap h4,
    .admin-dashboard-wrap h5,
    .admin-dashboard-wrap h6,
    .fcc-kpi-value,
    .fcc-card-title,
    .fcc-tab-pill {
        font-family: 'Outfit', sans-serif;
    }

    /* 1. Breadcrumbs */
    .fcc-breadcrumb-nav {
        margin-bottom: 14px;
    }

    .fcc-breadcrumb-nav .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 13px;
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

    .fcc-breadcrumb-nav .breadcrumb-item.active {
        color: #0f172a;
        font-weight: 600;
    }

    /* 2. Executive Hero Banner */
    .fcc-hero-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 22px 28px;
        margin-bottom: 22px;
        box-shadow: 0 2px 12px rgba(15, 23, 42, 0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .fcc-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #047857;
        font-size: 12px;
        font-weight: 700;
        padding: 3px 12px;
        border-radius: 30px;
        margin-bottom: 8px;
        letter-spacing: 0.01em;
    }

    .fcc-hero-pill .fcc-pulse-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: fccPulse 2s infinite;
    }

    @keyframes fccPulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .fcc-hero-title {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .fcc-hero-subtitle {
        color: #64748b;
        font-size: 13.5px;
        font-weight: 500;
        margin-bottom: 0;
        max-width: 680px;
    }

    .fcc-hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .fcc-btn-primary {
        background: #1d4ed8;
        border: 1px solid #1d4ed8;
        border-radius: 8px;
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 600;
        padding: 9px 18px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none !important;
        box-shadow: 0 2px 8px rgba(29, 78, 216, 0.25);
    }

    .fcc-btn-primary:hover {
        background: #1e40af;
        border-color: #1e40af;
        box-shadow: 0 4px 12px rgba(29, 78, 216, 0.35);
        transform: translateY(-1px);
    }

    .fcc-btn-outline {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        color: #334155 !important;
        font-size: 13px;
        font-weight: 600;
        padding: 9px 16px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }

    .fcc-btn-outline:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a !important;
        transform: translateY(-1px);
    }

    /* 3. Filter Collapse Box */
    .fcc-filter-card {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 22px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }

    /* 4. Top Metric KPI Grid (5 Cards) */
    .fcc-kpi-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 22px;
    }

    @media (max-width: 1400px) {
        .fcc-kpi-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 992px) {
        .fcc-kpi-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 576px) {
        .fcc-kpi-grid { grid-template-columns: 1fr; }
    }

    .fcc-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--fcc-card-shadow);
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .fcc-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--fcc-card-hover-shadow);
        border-color: #cbd5e1;
    }

    .fcc-kpi-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .fcc-kpi-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin: 0;
    }

    .fcc-kpi-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .fcc-kpi-icon.icon-blue { background: #dbeafe; color: #1d4ed8; }
    .fcc-kpi-icon.icon-purple { background: #f3e8ff; color: #7c3aed; }
    .fcc-kpi-icon.icon-green { background: #ecfdf5; color: #059669; }
    .fcc-kpi-icon.icon-red { background: #fee2e2; color: #dc2626; }
    .fcc-kpi-icon.icon-cyan { background: #e0f2fe; color: #0284c7; }

    .fcc-kpi-value {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        letter-spacing: -0.02em;
        margin-bottom: 8px;
    }

    .fcc-kpi-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 6px;
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        border-top: 1px dashed #f1f5f9;
        padding-top: 10px;
        margin-top: 4px;
    }

    .fcc-tag-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    .fcc-tag-green { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .fcc-tag-red { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .fcc-tag-amber { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .fcc-tag-blue { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .fcc-tag-purple { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }
    .fcc-tag-gray { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

    /* 5. Analytics Visual Cards (ApexCharts) */
    .fcc-charts-row {
        margin-bottom: 22px;
    }

    .fcc-content-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: var(--fcc-card-shadow);
        overflow: hidden;
        margin-bottom: 22px;
        transition: all 0.25s ease;
    }

    .fcc-content-card:hover {
        box-shadow: var(--fcc-card-hover-shadow);
    }

    .fcc-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: #ffffff;
    }

    .fcc-card-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fcc-card-badge-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .fcc-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.01em;
    }

    .fcc-card-subtitle {
        font-size: 12.5px;
        color: #64748b;
        margin: 2px 0 0 0;
        font-weight: 500;
    }

    .fcc-card-body {
        padding: 20px 22px;
    }

    /* 6. Navigation Tabs & Section Switcher */
    .fcc-tabs-container {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 8px;
        margin-bottom: 20px;
        scrollbar-width: thin;
        -webkit-overflow-scrolling: touch;
    }

    .fcc-tab-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        text-decoration: none !important;
    }

    .fcc-tab-pill:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
    }

    .fcc-tab-pill.active {
        background: #1d4ed8;
        color: #ffffff;
        border-color: #1d4ed8;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(29, 78, 216, 0.35);
    }

    .fcc-tab-pill .tab-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 2px 7px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        background: #f1f5f9;
        color: #475569;
    }

    .fcc-tab-pill.active .tab-badge {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* 7. Clean Table Styling */
    .fcc-table-wrapper {
        border-radius: 12px;
        overflow-x: auto;
    }

    .fcc-modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }

    .fcc-modern-table thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 13px 16px !important;
        border-top: none !important;
        border-bottom: 1px solid #e2e8f0 !important;
        white-space: nowrap;
    }

    .fcc-modern-table tbody td {
        padding: 13px 16px !important;
        vertical-align: middle !important;
        font-size: 13.5px !important;
        color: #1e293b !important;
        border-bottom: 1px solid #f1f5f9 !important;
        transition: background-color 0.15s ease;
    }

    .fcc-modern-table tbody tr:hover td {
        background-color: #f8fafc !important;
    }

    .fcc-modern-table tfoot th {
        background-color: #f8fafc !important;
        padding: 13px 16px !important;
        font-size: 13.5px !important;
        border-top: 2px solid #e2e8f0 !important;
        border-bottom: none !important;
        color: #0f172a !important;
    }

    /* Live Search Box for Tables */
    .fcc-table-search-box {
        position: relative;
        min-width: 240px;
    }

    .fcc-table-search-box input {
        width: 100%;
        padding: 8px 14px 8px 34px;
        border-radius: 20px;
        border: 1px solid #cbd5e1;
        font-size: 13px;
        background-color: #f8fafc;
        transition: all 0.2s ease;
        outline: none;
    }

    .fcc-table-search-box input:focus {
        background-color: #ffffff;
        border-color: #1d4ed8;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12);
    }

    .fcc-table-search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
    }

    /* Coach Avatar Circle */
    .fcc-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        color: #ffffff;
        flex-shrink: 0;
    }

    .avatar-bg-1 { background: linear-gradient(135deg, #1d4ed8, #3b82f6); }
    .avatar-bg-2 { background: linear-gradient(135deg, #059669, #10b981); }
    .avatar-bg-3 { background: linear-gradient(135deg, #d97706, #f59e0b); }
    .avatar-bg-4 { background: linear-gradient(135deg, #7c3aed, #8b5cf6); }
    .avatar-bg-5 { background: linear-gradient(135deg, #db2777, #ec4899); }
    .avatar-bg-6 { background: linear-gradient(135deg, #0284c7, #06b6d4); }

    /* Rank Medals for Top 10 */
    .fcc-rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 12.5px;
    }

    .rank-gold { background: linear-gradient(135deg, #f59e0b, #d97706); color: #ffffff; box-shadow: 0 2px 6px rgba(245, 158, 11, 0.3); }
    .rank-silver { background: linear-gradient(135deg, #94a3b8, #64748b); color: #ffffff; box-shadow: 0 2px 6px rgba(100, 116, 139, 0.3); }
    .rank-bronze { background: linear-gradient(135deg, #b45309, #78350f); color: #ffffff; box-shadow: 0 2px 6px rgba(180, 83, 9, 0.3); }
    .rank-other { background: #f1f5f9; color: #475569; }

    /* Quick Action Buttons in Table */
    .fcc-mini-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }

    .fcc-mini-btn-light {
        background: #f8fafc;
        color: #334155 !important;
        border: 1px solid #cbd5e1;
    }

    .fcc-mini-btn-light:hover {
        background: #e2e8f0;
        color: #0f172a !important;
    }

    .fcc-mini-btn-wa {
        background: #ecfdf5;
        color: #047857 !important;
        border: 1px solid #a7f3d0;
    }

    .fcc-mini-btn-wa:hover {
        background: #d1fae5;
    }

    /* Overdue row tint */
    .fcc-row-overdue td {
        background-color: #fff5f5 !important;
    }

    .fcc-row-overdue:hover td {
        background-color: #fee2e2 !important;
    }

    /* Empty state */
    .fcc-empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
        font-size: 14px;
        font-weight: 500;
    }

    .fcc-empty-state i {
        font-size: 36px;
        margin-bottom: 10px;
        opacity: 0.7;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="layout-top-spacing admin-dashboard-wrap">

        <!-- 1. Breadcrumb Navigation -->
        <nav class="fcc-breadcrumb-nav" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <i data-feather="home" style="width: 14px; height: 14px;" class="me-1"></i> Dashboard
                </li>
            </ol>
        </nav>

        <!-- 2. Executive Hero Header -->
        @php
            $hour = date('H');
            if ($hour < 12) {
                $greeting = 'Good morning';
            } elseif ($hour < 17) {
                $greeting = 'Good afternoon';
            } else {
                $greeting = 'Good evening';
            }
        @endphp

        <div class="fcc-hero-header">
            <div>
                <div class="fcc-hero-pill">
                    <span class="fcc-pulse-dot"></span> Super Admin Platform Control • Live System
                </div>
                <div class="fcc-hero-title">
                    {{ $greeting }}, {{ Auth::user()->name }}! 👋
                </div>
                <p class="fcc-hero-subtitle">
                    Real-time oversight of franchise operations, membership subscriptions, receivables, and platform client adoption.
                </p>
            </div>

            <div class="fcc-hero-actions">
                <a href="{{ route('adminPanel.franchises.create') }}" class="fcc-btn-primary">
                    <i data-feather="user-plus" style="width: 16px; height: 16px;"></i> Add Franchise Coach
                </a>
                <a href="{{ route('adminPanel.franchise-membership-plans.create') }}" class="fcc-btn-outline">
                    <i data-feather="credit-card" style="width: 16px; height: 16px;"></i> Assign Plan
                </a>
                <button type="button" class="fcc-btn-outline" id="toggleFilterBtn">
                    <i data-feather="filter" style="width: 16px; height: 16px;"></i> Filter Dates
                </button>
            </div>
        </div>

        <!-- 3. Date Filter Bar (Collapsible) -->
        @php
            $filterClass = Request::get('is_filter') ? '' : 'd-none';
        @endphp
        <div class="fcc-filter-card mb-4 {{ $filterClass }}" id="dashboardFilterCard">
            {!! Form::open(['class' => 'dashboard-filter-form custom-datatable-filter-form', 'method' => 'get', 'url' => route('adminPanel.dashboard'), 'enctype' => 'multipart/form-data' ]) !!}
            {!! Form::hidden('is_filter', true, ['id' => 'is_filter']) !!}
            <div class="row align-items-center">
                <div class="col-md-5 col-12 mb-2 mb-md-0">
                    <label class="form-label text-muted fw-bold mb-1" style="font-size: 12px; text-transform: uppercase;">Select Date Range</label>
                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 10px 14px; border: 1px solid #cbd5e1; width: 100%; border-radius: 8px;">
                        <i class="fa fa-calendar text-primary me-2"></i>
                        <span class="calender-text">All Time</span>
                        <i class="fa fa-caret-down float-end mt-1 text-muted"></i>
                    </div>
                    {!! Form::hidden('start_date', $filterStartDate ?? null, ['id' => 'start_date']) !!}
                    {!! Form::hidden('end_date', $filterEndDate ?? null, ['id' => 'end_date']) !!}
                    {!! Form::hidden('date_filter_type', $filterDateType ?? '1', ['id' => 'date_filter_type']) !!}
                </div>
                <div class="col-md-7 col-12 d-flex align-items-end gap-2 pt-md-4">
                    <button type="submit" class="fcc-btn-primary" style="padding: 10px 20px;">
                        <i data-feather="check" style="width: 15px; height: 15px;"></i> Apply Filter
                    </button>
                    <a href="{{ route('adminPanel.dashboard') }}" class="fcc-btn-outline" style="padding: 10px 18px;">
                        Reset
                    </a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>

        <!-- 4. Key Financial & Operational Metric Cards (5 Cards Grid) -->
        <div class="fcc-kpi-grid">
            <!-- Metric 1: Franchises -->
            <div class="fcc-kpi-card">
                <div class="fcc-kpi-top">
                    <span class="fcc-kpi-label">Franchise Coaches</span>
                    <div class="fcc-kpi-icon icon-blue">
                        <i data-feather="users" style="width: 20px; height: 20px;"></i>
                    </div>
                </div>
                <div class="fcc-kpi-value">{{ number_format($totalFranchise ?? 0) }}</div>
                <div class="fcc-kpi-footer">
                    <div>
                        <span class="fcc-tag-pill fcc-tag-green">{{ $activeFranchiseCount ?? 0 }} Active</span>
                        <span class="fcc-tag-pill fcc-tag-gray">{{ $inactiveFranchiseCount ?? 0 }} Inactive</span>
                    </div>
                    @if(isset($franchiseThisMonths) && count($franchiseThisMonths) > 0)
                        <span class="text-primary fw-bold" style="font-size: 11.5px;">+{{ count($franchiseThisMonths) }} new this mo.</span>
                    @endif
                </div>
            </div>

            <!-- Metric 2: Total Revenue -->
            <div class="fcc-kpi-card">
                <div class="fcc-kpi-top">
                    <span class="fcc-kpi-label">Gross Revenue</span>
                    <div class="fcc-kpi-icon icon-purple">
                        <i data-feather="dollar-sign" style="width: 20px; height: 20px;"></i>
                    </div>
                </div>
                <div class="fcc-kpi-value">₹{{ number_format($totalAmount ?? 0, 0) }}</div>
                <div class="fcc-kpi-footer">
                    @php
                        $collectionRate = ($totalAmount ?? 0) > 0 ? round((($receivedAmount ?? 0) / $totalAmount) * 100, 1) : 0;
                    @endphp
                    <span>Collection Efficiency</span>
                    <span class="fcc-tag-pill fcc-tag-purple">{{ $collectionRate }}% Collected</span>
                </div>
            </div>

            <!-- Metric 3: Received Amount -->
            <div class="fcc-kpi-card">
                <div class="fcc-kpi-top">
                    <span class="fcc-kpi-label">Collected In Bank</span>
                    <div class="fcc-kpi-icon icon-green">
                        <i data-feather="check-circle" style="width: 20px; height: 20px;"></i>
                    </div>
                </div>
                <div class="fcc-kpi-value">₹{{ number_format($receivedAmount ?? 0, 0) }}</div>
                <div class="fcc-kpi-footer">
                    <span class="text-success fw-bold"><i data-feather="arrow-up-right" style="width: 14px; height: 14px;"></i> Realized</span>
                    <span class="fcc-tag-pill fcc-tag-green">100% Verified</span>
                </div>
            </div>

            <!-- Metric 4: Pending Amount -->
            <div class="fcc-kpi-card">
                <div class="fcc-kpi-top">
                    <span class="fcc-kpi-label">Pending Receivables</span>
                    <div class="fcc-kpi-icon icon-red">
                        <i data-feather="alert-circle" style="width: 20px; height: 20px;"></i>
                    </div>
                </div>
                <div class="fcc-kpi-value text-danger">₹{{ number_format($pendingAmount ?? 0, 0) }}</div>
                <div class="fcc-kpi-footer">
                    <span class="text-danger fw-bold">{{ count($franchiseMembershipPlans ?? []) }} Pending Plans</span>
                    <span class="fcc-tag-pill fcc-tag-red">Requires Action</span>
                </div>
            </div>

            <!-- Metric 5: Platform Reach -->
            <div class="fcc-kpi-card">
                <div class="fcc-kpi-top">
                    <span class="fcc-kpi-label">End-User Clients</span>
                    <div class="fcc-kpi-icon icon-cyan">
                        <i data-feather="activity" style="width: 20px; height: 20px;"></i>
                    </div>
                </div>
                <div class="fcc-kpi-value">{{ number_format($totalPlatformUsers ?? 0) }}</div>
                <div class="fcc-kpi-footer">
                    <span>Coached across clubs</span>
                    <span class="fcc-tag-pill fcc-tag-blue">+{{ number_format($newPlatformUsersThisMonth ?? 0) }} this mo.</span>
                </div>
            </div>
        </div>

        <!-- 5. Visual Analytics: ApexCharts Trends & Coach Health -->
        <div class="row fcc-charts-row">
            <!-- Left Chart: Revenue & Collection Velocity -->
            <div class="col-xl-8 col-lg-7 col-12 mb-4 mb-xl-0">
                <div class="fcc-content-card h-100 mb-0">
                    <div class="fcc-card-header">
                        <div class="fcc-card-header-left">
                            <div class="fcc-card-badge-icon" style="background: #eff6ff; color: #1d4ed8;">
                                <i data-feather="bar-chart-2" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <h5 class="fcc-card-title">Revenue & Collection Trajectory</h5>
                                <p class="fcc-card-subtitle">Last 6 months billing vs realized collections (₹)</p>
                            </div>
                        </div>
                        <div>
                            <span class="fcc-tag-pill fcc-tag-blue">
                                Monthly Velocity
                            </span>
                        </div>
                    </div>
                    <div class="fcc-card-body">
                        <div id="revenueTrendChart" style="min-height: 280px;"></div>
                    </div>
                </div>
            </div>

            <!-- Right Chart: Franchise Health Breakdown -->
            <div class="col-xl-4 col-lg-5 col-12">
                <div class="fcc-content-card h-100 mb-0">
                    <div class="fcc-card-header">
                        <div class="fcc-card-header-left">
                            <div class="fcc-card-badge-icon" style="background: #ecfdf5; color: #059669;">
                                <i data-feather="pie-chart" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <h5 class="fcc-card-title">Coach Health Distribution</h5>
                                <p class="fcc-card-subtitle">Active, expiring soon, and inactive clubs</p>
                            </div>
                        </div>
                    </div>
                    <div class="fcc-card-body d-flex flex-column justify-content-between">
                        <div id="coachHealthChart" style="min-height: 220px;"></div>
                        <div class="pt-3 border-top mt-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="d-flex align-items-center gap-2" style="font-size: 13px;">
                                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #10b981;"></span> Active Subscriptions
                                </span>
                                <strong class="text-dark">{{ $activeFranchiseCount ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="d-flex align-items-center gap-2" style="font-size: 13px;">
                                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #f59e0b;"></span> Expiring Soon (≤10 Days)
                                </span>
                                <strong class="text-warning">{{ $expiringSoonCount ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center gap-2" style="font-size: 13px;">
                                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #ef4444;"></span> Inactive / Expired
                                </span>
                                <strong class="text-danger">{{ $inactiveFranchiseCount ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Operational Command Center: Tabs Navigation -->
        <div class="fcc-tabs-container">
            <button class="fcc-tab-pill active" onclick="switchDashboardTab('tab-priority', this)">
                <i data-feather="alert-triangle" style="width: 15px; height: 15px;"></i>
                Priority Actions
                <span class="tab-badge">{{ count($franchiseMembershipPlans ?? []) + ($expiringSoonCount ?? 0) }}</span>
            </button>

            <button class="fcc-tab-pill" onclick="switchDashboardTab('tab-performance', this)">
                <i data-feather="award" style="width: 15px; height: 15px;"></i>
                Coach Leaderboard
                <span class="tab-badge">Top 10</span>
            </button>

            <button class="fcc-tab-pill" onclick="switchDashboardTab('tab-growth', this)">
                <i data-feather="trending-up" style="width: 15px; height: 15px;"></i>
                Platform Usage & Growth
                <span class="tab-badge">{{ count($platformUsageThisMonths ?? []) }}</span>
            </button>

            <button class="fcc-tab-pill" onclick="switchDashboardTab('tab-new', this)">
                <i data-feather="user-plus" style="width: 15px; height: 15px;"></i>
                New Onboarding
                <span class="tab-badge">{{ count($franchiseThisMonths ?? []) }}</span>
            </button>

            <button class="fcc-tab-pill" onclick="switchDashboardTab('tab-lifecycle', this)">
                <i data-feather="repeat" style="width: 15px; height: 15px;"></i>
                All Coaches Lifecycle
                <span class="tab-badge">{{ count($franchises ?? []) }}</span>
            </button>
        </div>

        <!-- TAB CONTENT 1: PRIORITY ACTIONS (Pending Payments & Upcoming Subscriptions) -->
        <div class="tab-panel-section" id="tab-priority">
            <div class="row">
                <!-- Card 1: Pending Payments -->
                <div class="col-xl-6 col-12 layout-spacing">
                    <div class="fcc-content-card h-100">
                        <div class="fcc-card-header">
                            <div class="fcc-card-header-left">
                                <div class="fcc-card-badge-icon" style="background: #fef2f2; color: #dc2626;">
                                    <i data-feather="clock" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div>
                                    <h5 class="fcc-card-title">Pending Payment Receivables</h5>
                                    <p class="fcc-card-subtitle">Franchises with unpaid or pending membership balances</p>
                                </div>
                            </div>
                            <div class="fcc-table-search-box">
                                <i class="fa fa-search"></i>
                                <input type="text" class="fcc-table-filter" data-table="tablePendingPayments" placeholder="Search coach or plan...">
                            </div>
                        </div>

                        <div class="p-0 fcc-table-wrapper table-responsive">
                            <table class="table fcc-modern-table" id="tablePendingPayments">
                                <thead>
                                    <tr>
                                        <th>Franchise Coach</th>
                                        <th>Assigned Plan</th>
                                        <th>Pending Amount</th>
                                        <th>Plan Valid Until</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($franchiseMembershipPlans) && count($franchiseMembershipPlans) > 0)
                                        @foreach($franchiseMembershipPlans as $k => $fPlan)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="fcc-avatar avatar-bg-{{ ($k % 6) + 1 }}">
                                                            {{ strtoupper(substr($fPlan->user_name ?? 'C', 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ $fPlan->user_name }}</div>
                                                            <small class="text-muted">Coach ID: #{{ $fPlan->franchise_id }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="fcc-tag-pill fcc-tag-blue">{{ $fPlan->membership_plan_name }}</span>
                                                </td>
                                                <td>
                                                    <span class="fcc-tag-pill fcc-tag-red fw-bold" style="font-size: 13px;">
                                                        ₹{{ number_format($fPlan->total_amount, 0) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-muted fw-semibold" style="font-size: 13px;">
                                                        {{ date("d M Y", strtotime($fPlan->end_date)) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('adminPanel.franchise-membership-plans.index', $fPlan->franchise_id) }}" class="fcc-mini-btn fcc-mini-btn-light" title="View Membership Details">
                                                        <i data-feather="eye" style="width: 14px; height: 14px;"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="fcc-empty-state">
                                                <i class="fa fa-check-circle-o text-success d-block"></i>
                                                Great news! No pending payment balances found.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                @if(isset($franchiseMembershipPlans) && count($franchiseMembershipPlans) > 0)
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-end">Total Outstanding:</th>
                                            <th class="text-danger fw-bold" style="font-size: 15px;">₹{{ number_format($pendingAmount ?? 0, 0) }}</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Upcoming & Overdue Expirations -->
                <div class="col-xl-6 col-12 layout-spacing">
                    <div class="fcc-content-card h-100">
                        <div class="fcc-card-header">
                            <div class="fcc-card-header-left">
                                <div class="fcc-card-badge-icon" style="background: #fffbeb; color: #b45309;">
                                    <i data-feather="calendar" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div>
                                    <h5 class="fcc-card-title">Expiring Soon & Overdue Subscriptions</h5>
                                    <p class="fcc-card-subtitle">Plans expiring within 10 days or already overdue</p>
                                </div>
                            </div>
                            <div class="fcc-table-search-box">
                                <i class="fa fa-search"></i>
                                <input type="text" class="fcc-table-filter" data-table="tableUpcomingSubs" placeholder="Search coach...">
                            </div>
                        </div>

                        <div class="p-0 fcc-table-wrapper table-responsive">
                            <table class="table fcc-modern-table" id="tableUpcomingSubs">
                                <thead>
                                    <tr>
                                        <th>Franchise Coach</th>
                                        <th>Membership Plan</th>
                                        <th>Renewal Status</th>
                                        <th class="text-end">Contact</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $upcomingCount = 0;
                                    @endphp
                                    @if(isset($franchises) && count($franchises) > 0)
                                        @foreach($franchises as $k => $franchise)
                                            @php
                                                $pending_days = null;
                                                if (!empty($franchise->end_date)) {
                                                    $endDate = \Carbon\Carbon::parse($franchise->end_date)->startOfDay();
                                                    $today   = \Carbon\Carbon::today();
                                                    $pending_days = $today->diffInDays($endDate, false);
                                                }

                                                $last_membership = $lastMembershipByFranchise->get($franchise->id);
                                                $membership_plan_name = isset($last_membership) ? ($membershipPlanNames->get($last_membership->membership_id) ?? null) : null;
                                            @endphp

                                            @if(isset($pending_days) && $pending_days <= 10)
                                                @php $upcomingCount++; @endphp
                                                <tr class="{{ $pending_days < 0 ? 'fcc-row-overdue' : '' }}">
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="fcc-avatar avatar-bg-{{ ($k % 6) + 1 }}">
                                                                {{ strtoupper(substr($franchise->name ?? 'F', 0, 2)) }}
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold text-dark">{{ $franchise->name }}</div>
                                                                <small class="text-muted">{{ $franchise->mobile_number }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="fcc-tag-pill fcc-tag-purple">{{ $membership_plan_name ?? 'Standard Plan' }}</span>
                                                    </td>
                                                    <td>
                                                        @if($pending_days > 0)
                                                            <span class="fcc-tag-pill fcc-tag-amber">
                                                                <i data-feather="clock" style="width: 12px; height: 12px;"></i> {{ $pending_days }} Days left
                                                            </span>
                                                        @elseif($pending_days == 0)
                                                            <span class="fcc-tag-pill fcc-tag-red">
                                                                <i data-feather="alert-circle" style="width: 12px; height: 12px;"></i> Expires Today!
                                                            </span>
                                                        @else
                                                            <span class="fcc-tag-pill fcc-tag-red">
                                                                <i data-feather="alert-octagon" style="width: 12px; height: 12px;"></i> {{ abs($pending_days) }} Days Overdue
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        @if(!empty($franchise->mobile_number))
                                                            @php
                                                                $cleanPhone = preg_replace('/[^0-9]/', '', $franchise->mobile_number);
                                                            @endphp
                                                            <a href="https://wa.me/{{ strlen($cleanPhone) == 10 ? '91'.$cleanPhone : $cleanPhone }}" target="_blank" class="fcc-mini-btn fcc-mini-btn-wa" title="WhatsApp Coach">
                                                                <i class="fa fa-whatsapp"></i> Chat
                                                            </a>
                                                            <a href="tel:{{ $franchise->mobile_number }}" class="fcc-mini-btn fcc-mini-btn-light" title="Call Coach">
                                                                <i class="fa fa-phone"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif

                                    @if($upcomingCount == 0)
                                        <tr>
                                            <td colspan="4" class="fcc-empty-state">
                                                <i class="fa fa-smile-o text-success d-block"></i>
                                                All active coach subscriptions are healthy! No upcoming expirations within 10 days.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT 2: COACH LEADERBOARD (Top 10 Active & Least 10 InActive) -->
        <div class="tab-panel-section d-none" id="tab-performance">
            <div class="row">
                <!-- Top 10 Active Coaches -->
                <div class="col-xl-6 col-12 layout-spacing">
                    <div class="fcc-content-card h-100">
                        <div class="fcc-card-header">
                            <div class="fcc-card-header-left">
                                <div class="fcc-card-badge-icon" style="background: #fef3c7; color: #b45309;">
                                    <i data-feather="award" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div>
                                    <h5 class="fcc-card-title">Top 10 Active Coaches This Month</h5>
                                    <p class="fcc-card-subtitle">Highest billing and most active franchise partners</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-0 fcc-table-wrapper table-responsive">
                            <table class="table fcc-modern-table" id="tableTopActive">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">Rank</th>
                                        <th>Coach Profile</th>
                                        <th>Monthly Billings</th>
                                        <th>Total Clients</th>
                                        <th class="text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($top10ActiveThisMonths) && count($top10ActiveThisMonths) > 0)
                                        @foreach($top10ActiveThisMonths as $key => $topCoach)
                                            @php
                                                $totUsers = $totalUsersByFranchise->get($topCoach->id, 0);
                                                $rankClass = $key == 0 ? 'rank-gold' : ($key == 1 ? 'rank-silver' : ($key == 2 ? 'rank-bronze' : 'rank-other'));
                                            @endphp
                                            <tr>
                                                <td>
                                                    <span class="fcc-rank-badge {{ $rankClass }}">
                                                        {{ $key + 1 }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="fcc-avatar avatar-bg-{{ ($key % 6) + 1 }}">
                                                            {{ strtoupper(substr($topCoach->name ?? 'C', 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ ucfirst($topCoach->name) }}</div>
                                                            <small class="text-muted">{{ $topCoach->email ?? $topCoach->mobile_number }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-success">
                                                        ₹{{ number_format($topCoach->total_amount_sum ?? 0, 0) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fcc-tag-pill fcc-tag-blue">{{ $totUsers }} Clients</span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="fcc-tag-pill fcc-tag-green">Active</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="fcc-empty-state">No Active Coaches recorded this month.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Least 10 Inactive Coaches (Attention Needed) -->
                <div class="col-xl-6 col-12 layout-spacing">
                    <div class="fcc-content-card h-100">
                        <div class="fcc-card-header">
                            <div class="fcc-card-header-left">
                                <div class="fcc-card-badge-icon" style="background: #f1f5f9; color: #64748b;">
                                    <i data-feather="user-x" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div>
                                    <h5 class="fcc-card-title">Coaches Requiring Engagement</h5>
                                    <p class="fcc-card-subtitle">Inactive or expired subscriptions needing follow-up</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-0 fcc-table-wrapper table-responsive">
                            <table class="table fcc-modern-table" id="tableTopInactive">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">S.No</th>
                                        <th>Coach Profile</th>
                                        <th>Contact</th>
                                        <th>Total Clients</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($top10InActiveThisMonths) && count($top10InActiveThisMonths) > 0)
                                        @foreach($top10InActiveThisMonths as $key => $inActiveCoach)
                                            @php
                                                $totUsers = $totalUsersByFranchise->get($inActiveCoach->id, 0);
                                            @endphp
                                            <tr>
                                                <td><span class="fcc-rank-badge rank-other">{{ $key + 1 }}</span></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="fcc-avatar" style="background: #94a3b8;">
                                                            {{ strtoupper(substr($inActiveCoach->name ?? 'I', 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ ucfirst($inActiveCoach->name) }}</div>
                                                            <small class="text-muted">{{ $inActiveCoach->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted" style="font-size: 13px;">{{ $inActiveCoach->mobile_number ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    <span class="fcc-tag-pill fcc-tag-gray">{{ $totUsers }} Clients</span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('adminPanel.franchises.details', $inActiveCoach->id) }}" class="fcc-mini-btn fcc-mini-btn-light">
                                                        Re-engage
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="fcc-empty-state">No inactive coaches found!</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT 3: PLATFORM USAGE & GROWTH -->
        <div class="tab-panel-section d-none" id="tab-growth">
            <div class="row">
                <div class="col-12 layout-spacing">
                    <div class="fcc-content-card">
                        <div class="fcc-card-header">
                            <div class="fcc-card-header-left">
                                <div class="fcc-card-badge-icon" style="background: #eff6ff; color: #1d4ed8;">
                                    <i data-feather="trending-up" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div>
                                    <h5 class="fcc-card-title">Platform Usage & Client Additions This Month</h5>
                                    <p class="fcc-card-subtitle">Active franchises adding end-users to the Fit Coach Club platform</p>
                                </div>
                            </div>
                            <div class="fcc-table-search-box">
                                <i class="fa fa-search"></i>
                                <input type="text" class="fcc-table-filter" data-table="tablePlatformUsage" placeholder="Filter coaches...">
                            </div>
                        </div>

                        <div class="p-0 fcc-table-wrapper table-responsive">
                            <table class="table fcc-modern-table" id="tablePlatformUsage">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">S.No</th>
                                        <th>Franchise Coach</th>
                                        <th>New Clients Added This Month</th>
                                        <th>Total Active Client Base</th>
                                        <th>Growth Share</th>
                                        <th class="text-end">View Coach</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($platformUsageThisMonths) && count($platformUsageThisMonths) > 0)
                                        @foreach($platformUsageThisMonths as $key => $pCoach)
                                            @php
                                                $newUsers = $newUsersByFranchise->get($pCoach->id, 0);
                                                $totalUsers = $totalUsersByFranchise->get($pCoach->id, 0);
                                                $percentShare = ($totalPlatformUsers ?? 1) > 0 ? round(($totalUsers / $totalPlatformUsers) * 100, 1) : 0;
                                            @endphp
                                            <tr>
                                                <td><span class="fcc-rank-badge rank-other">{{ $key + 1 }}</span></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="fcc-avatar avatar-bg-{{ ($key % 6) + 1 }}">
                                                            {{ strtoupper(substr($pCoach->name ?? 'C', 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ ucfirst($pCoach->name) }}</div>
                                                            <small class="text-muted">{{ $pCoach->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="fcc-tag-pill fcc-tag-green fw-bold" style="font-size: 13px;">
                                                        +{{ $newUsers }} New
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fcc-tag-pill fcc-tag-blue fw-bold" style="font-size: 13px;">
                                                        {{ $totalUsers }} Total
                                                    </span>
                                                </td>
                                                <td style="width: 200px;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="progress flex-grow-1" style="height: 6px; background: #e2e8f0; border-radius: 10px;">
                                                            <div class="progress-bar" role="progressbar" style="background: #1d4ed8; width: {{ min(100, $percentShare * 2) }}%;" aria-valuenow="{{ $percentShare }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <small class="text-muted fw-bold" style="font-size: 11px;">{{ $percentShare }}%</small>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('adminPanel.franchises.details', $pCoach->id) }}" class="fcc-mini-btn fcc-mini-btn-light">
                                                        <i data-feather="external-link" style="width: 13px; height: 13px;"></i> Profile
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="fcc-empty-state">No platform usage records found for this period.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT 4: NEW ONBOARDING THIS MONTH -->
        <div class="tab-panel-section d-none" id="tab-new">
            <div class="row">
                <div class="col-12 layout-spacing">
                    <div class="fcc-content-card">
                        <div class="fcc-card-header">
                            <div class="fcc-card-header-left">
                                <div class="fcc-card-badge-icon" style="background: #ecfdf5; color: #059669;">
                                    <i data-feather="user-check" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div>
                                    <h5 class="fcc-card-title">Newly Registered Franchises ({{ date('F Y') }})</h5>
                                    <p class="fcc-card-subtitle">Coaches onboarded to Fit Coach Club in current calendar month</p>
                                </div>
                            </div>
                            <div class="fcc-table-search-box">
                                <i class="fa fa-search"></i>
                                <input type="text" class="fcc-table-filter" data-table="tableNewFranchises" placeholder="Search new coach...">
                            </div>
                        </div>

                        <div class="p-0 fcc-table-wrapper table-responsive">
                            <table class="table fcc-modern-table" id="tableNewFranchises">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">S.No</th>
                                        <th>Coach Name</th>
                                        <th>Email Address</th>
                                        <th>Phone Number</th>
                                        <th>Onboarded Date</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($franchiseThisMonths) && count($franchiseThisMonths) > 0)
                                        @foreach($franchiseThisMonths as $key => $nFranchise)
                                            <tr>
                                                <td><span class="fcc-rank-badge rank-other">{{ $key + 1 }}</span></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="fcc-avatar avatar-bg-{{ ($key % 6) + 1 }}">
                                                            {{ strtoupper(substr($nFranchise->name ?? 'N', 0, 2)) }}
                                                        </div>
                                                        <div class="fw-bold text-dark">{{ ucfirst($nFranchise->name) }}</div>
                                                    </div>
                                                </td>
                                                <td>{{ $nFranchise->email ?? 'N/A' }}</td>
                                                <td>{{ $nFranchise->mobile_number ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="text-muted fw-semibold">
                                                        {{ date("d M Y", strtotime($nFranchise->created_at)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($nFranchise->status == 1)
                                                        <span class="fcc-tag-pill fcc-tag-green">Active</span>
                                                    @else
                                                        <span class="fcc-tag-pill fcc-tag-gray">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('adminPanel.franchise-membership-plans.create') }}?franchise_id={{ $nFranchise->id }}" class="fcc-mini-btn fcc-mini-btn-light">
                                                        Assign Plan
                                                    </a>
                                                    <a href="{{ route('adminPanel.franchises.edit', $nFranchise->id) }}" class="fcc-mini-btn fcc-mini-btn-light">
                                                        Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="fcc-empty-state">
                                                <i data-feather="inbox" class="d-block mb-2"></i>
                                                No new coaches joined yet this month.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT 5: COMPLETE LIFECYCLE (All Coaches) -->
        <div class="tab-panel-section d-none" id="tab-lifecycle">
            <div class="row">
                <div class="col-12 layout-spacing">
                    <div class="fcc-content-card">
                        <div class="fcc-card-header">
                            <div class="fcc-card-header-left">
                                <div class="fcc-card-badge-icon" style="background: #ede9fe; color: #7c3aed;">
                                    <i data-feather="layers" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div>
                                    <h5 class="fcc-card-title">Complete Franchise Lifecycle Directory</h5>
                                    <p class="fcc-card-subtitle">All registered coaches, subscription status, and total clients</p>
                                </div>
                            </div>
                            <div class="fcc-table-search-box">
                                <i class="fa fa-search"></i>
                                <input type="text" class="fcc-table-filter" data-table="tableAllLifecycle" placeholder="Search any coach...">
                            </div>
                        </div>

                        <div class="p-0 fcc-table-wrapper table-responsive">
                            <table class="table fcc-modern-table" id="tableAllLifecycle">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">S.No</th>
                                        <th>Coach Profile</th>
                                        <th>Current Membership Plan</th>
                                        <th>Validity / End Date</th>
                                        <th>Clients Managed</th>
                                        <th>Outstanding Dues</th>
                                        <th class="text-end">Manage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($franchiseLifeCycles) && count($franchiseLifeCycles) > 0)
                                        @foreach($franchiseLifeCycles as $key => $coach)
                                            @php
                                                $totUsers = $totalUsersByFranchise->get($coach->id, 0);
                                                $lastMem = $lastMembershipByFranchise->get($coach->id);
                                                $planName = isset($lastMem) ? ($membershipPlanNames->get($lastMem->membership_id) ?? 'No Plan') : 'No Plan';
                                                $pAmount = $pendingAmountByFranchise->get($coach->id, 0);
                                                $isExp = !empty($coach->end_date) && \Carbon\Carbon::parse($coach->end_date)->isPast();
                                            @endphp
                                            <tr>
                                                <td><span class="fcc-rank-badge rank-other">{{ $key + 1 }}</span></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="fcc-avatar avatar-bg-{{ ($key % 6) + 1 }}">
                                                            {{ strtoupper(substr($coach->name ?? 'F', 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ ucfirst($coach->name) }}</div>
                                                            <small class="text-muted">{{ $coach->mobile_number }} • {{ $coach->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="fcc-tag-pill fcc-tag-purple">{{ $planName }}</span>
                                                </td>
                                                <td>
                                                    @if(!empty($coach->end_date))
                                                        <span class="fw-semibold {{ $isExp ? 'text-danger' : 'text-dark' }}">
                                                            {{ date("d M Y", strtotime($coach->end_date)) }}
                                                        </span>
                                                        @if($isExp)
                                                            <span class="fcc-tag-pill fcc-tag-red ms-1">Expired</span>
                                                        @else
                                                            <span class="fcc-tag-pill fcc-tag-green ms-1">Active</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Not Set</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="fcc-tag-pill fcc-tag-blue fw-bold">{{ $totUsers }} Clients</span>
                                                </td>
                                                <td>
                                                    @if($pAmount > 0)
                                                        <span class="fcc-tag-pill fcc-tag-red fw-bold">₹{{ number_format($pAmount, 0) }}</span>
                                                    @else
                                                        <span class="fcc-tag-pill fcc-tag-green">₹0 (Clear)</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('adminPanel.franchises.details', $coach->id) }}" class="fcc-mini-btn fcc-mini-btn-light" title="View Details">
                                                        <i data-feather="user" style="width: 14px; height: 14px;"></i> View
                                                    </a>
                                                    <a href="{{ route('adminPanel.franchise-membership-plans.index', $coach->id) }}" class="fcc-mini-btn fcc-mini-btn-light" title="Subscription History">
                                                        <i data-feather="file-text" style="width: 14px; height: 14px;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="fcc-empty-state">No franchises found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/plugins/apex/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/daterangepicker/daterangepicker.js') }}"></script>

<script>
    // Tab Switcher
    function switchDashboardTab(tabId, btn) {
        document.querySelectorAll('.tab-panel-section').forEach(function(el) {
            el.classList.add('d-none');
        });
        document.querySelectorAll('.fcc-tab-pill').forEach(function(el) {
            el.classList.remove('active');
        });

        var target = document.getElementById(tabId);
        if (target) {
            target.classList.remove('d-none');
        }
        if (btn) {
            btn.classList.add('active');
        }

        // Re-render feather icons if needed
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    // Toggle Filter Card
    document.getElementById('toggleFilterBtn').addEventListener('click', function() {
        var card = document.getElementById('dashboardFilterCard');
        if (card) {
            card.classList.toggle('d-none');
        }
    });

    // Instant Client-side Table Filter Search
    document.querySelectorAll('.fcc-table-filter').forEach(function(input) {
        input.addEventListener('keyup', function() {
            var tableId = this.getAttribute('data-table');
            var filterVal = this.value.toLowerCase().trim();
            var table = document.getElementById(tableId);
            if (!table) return;

            var rows = table.querySelectorAll('tbody tr');
            rows.forEach(function(row) {
                var text = row.textContent.toLowerCase();
                if (text.indexOf(filterVal) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    $(document).ready(function() {
        // Initialize Feather Icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // 1. ApexChart: Monthly Revenue & Collection Velocity
        var trendMonths = {!! json_encode(!empty($trendMonths) ? $trendMonths : ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct']) !!};
        var trendRevenue = {!! json_encode(!empty($trendRevenue) ? $trendRevenue : [0, 0, 0, 0, 0, 0]) !!};
        var trendReceived = {!! json_encode(!empty($trendReceived) ? $trendReceived : [0, 0, 0, 0, 0, 0]) !!};

        var optionsRevenue = {
            chart: {
                height: 300,
                type: 'area',
                toolbar: { show: false },
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                dropShadow: {
                    enabled: true,
                    top: 2,
                    left: 0,
                    blur: 6,
                    color: '#1d4ed8',
                    opacity: 0.1
                }
            },
            colors: ['#1d4ed8', '#10b981'],
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: [3, 3]
            },
            series: [
                {
                    name: 'Total Billings',
                    data: trendRevenue
                },
                {
                    name: 'Received Collections',
                    data: trendReceived
                }
            ],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 4,
                padding: { left: 10, right: 10, top: 0, bottom: 0 }
            },
            xaxis: {
                categories: trendMonths,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 600
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 600
                    },
                    formatter: function(value) {
                        if (value >= 100000) {
                            return '₹' + (value / 100000).toFixed(1) + 'L';
                        } else if (value >= 1000) {
                            return '₹' + (value / 1000).toFixed(0) + 'K';
                        }
                        return '₹' + Number(value).toLocaleString('en-IN');
                    }
                }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) {
                        return '₹' + Number(val).toLocaleString('en-IN');
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontWeight: 600,
                fontSize: '12px',
                labels: { colors: '#475569' },
                markers: { radius: 12 }
            }
        };

        var chartRev = new ApexCharts(document.querySelector("#revenueTrendChart"), optionsRevenue);
        chartRev.render();

        // 2. ApexChart: Coach Health Donut
        var activeCount = {{ $activeFranchiseCount ?? 0 }};
        var expiringCount = {{ $expiringSoonCount ?? 0 }};
        var inactiveCount = {{ $inactiveFranchiseCount ?? 0 }};
        var totalCount = activeCount + inactiveCount;

        // Prevent zero slice error
        var donutSeries = totalCount > 0 ? [activeCount, expiringCount, Math.max(0, inactiveCount - expiringCount)] : [1, 0, 0];

        var optionsHealth = {
            chart: {
                type: 'donut',
                height: 230,
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#10b981', '#f59e0b', '#ef4444'],
            labels: ['Active', 'Expiring Soon', 'Inactive / Expired'],
            series: donutSeries,
            dataLabels: { enabled: false },
            plotOptions: {
                pie: {
                    donut: {
                        size: '72%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '13px',
                                fontWeight: 600,
                                color: '#64748b',
                                offsetY: -4
                            },
                            value: {
                                show: true,
                                fontSize: '24px',
                                fontFamily: 'Outfit, sans-serif',
                                fontWeight: 800,
                                color: '#0f172a',
                                offsetY: 4,
                                formatter: function(val) {
                                    return val;
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total Clubs',
                                fontSize: '12px',
                                fontWeight: 600,
                                color: '#94a3b8',
                                formatter: function() {
                                    return {{ $totalFranchise ?? 0 }};
                                }
                            }
                        }
                    }
                }
            },
            legend: { show: false },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) {
                        return val + ' Coaches';
                    }
                }
            }
        };

        var chartHealth = new ApexCharts(document.querySelector("#coachHealthChart"), optionsHealth);
        chartHealth.render();

        // 3. Date Range Picker Initialization
        if (typeof $.fn.daterangepicker !== 'undefined' && $('#reportrange').length) {
            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#reportrange span.calender-text').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#start_date').val(start.format('YYYY-MM-DD'));
                $('#end_date').val(end.format('YYYY-MM-DD'));
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                   'Today': [moment(), moment()],
                   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                   'This Month': [moment().startOf('month'), moment().endOf('month')],
                   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            @if(request('start_date') && request('end_date'))
                cb(moment('{{ request('start_date') }}'), moment('{{ request('end_date') }}'));
            @endif
        }
    });
</script>
@endpush
