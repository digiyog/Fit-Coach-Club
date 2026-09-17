@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Manual Attendance | ' . ($user->name ?? 'User') . ' | ' . __('language.page_main_title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">

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
        --fcc-orange: #f97316;
        --fcc-orange-light: #ffedd5;
        --fcc-purple: #9333ea;
        --fcc-purple-light: #f3e8ff;
    }

    .fcc-manual-attendance-wrap {
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
    .fcc-btn-history-link {
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
        text-decoration: none;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        transition: all 0.15s ease;
    }
    .fcc-btn-history-link:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: var(--fcc-primary);
    }
    .fcc-btn-history-link svg {
        width: 15px;
        height: 15px;
        color: var(--fcc-primary);
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

    /* 4. Metric Cards */
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

    /* 5. Action Forms Grid (2 Cards) */
    .fcc-action-cards-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 991px) {
        .fcc-action-cards-grid {
            grid-template-columns: 1fr;
        }
    }
    .fcc-form-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .fcc-form-card-header {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 20px;
    }
    .fcc-form-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .fcc-form-header-icon.orange {
        background: #ffedd5;
        color: #ea580c;
    }
    .fcc-form-header-icon.green {
        background: #dcfce7;
        color: #16a34a;
    }
    .fcc-form-header-icon svg {
        width: 20px;
        height: 20px;
    }
    .fcc-form-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.25;
    }
    .fcc-form-card-subtitle {
        font-size: 12.5px;
        color: #64748b;
        margin: 3px 0 0 0;
    }

    /* Form Inputs */
    .fcc-form-label {
        font-size: 12.5px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
        display: block;
    }
    .fcc-input-wrap {
        position: relative;
        width: 100%;
    }
    .fcc-input-wrap svg,
    .fcc-input-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        color: #94a3b8;
        pointer-events: none;
        z-index: 5;
    }
    .fcc-input-wrap input,
    .fcc-input-wrap input.form-control,
    .fcc-input-wrap input.fcc-custom-input,
    .fcc-input-wrap input.date-picker,
    .fcc-input-wrap input.flatpickr-input {
        width: 100% !important;
        height: 42px !important;
        padding-left: 42px !important;
        padding-right: 14px !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 9px !important;
        font-size: 13px !important;
        font-family: 'Outfit', sans-serif !important;
        color: #0f172a !important;
        background-color: #ffffff !important;
        box-shadow: none !important;
        transition: all 0.15s ease;
    }
    .fcc-input-wrap input:focus,
    .fcc-input-wrap input.form-control:focus,
    .fcc-input-wrap input.flatpickr-input:focus {
        border-color: var(--fcc-primary) !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.1) !important;
    }
    .fcc-custom-textarea,
    textarea.remark {
        width: 100% !important;
        min-height: 80px !important;
        padding: 10px 14px !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 9px !important;
        font-size: 13px !important;
        font-family: 'Outfit', sans-serif !important;
        color: #0f172a !important;
        background-color: #ffffff !important;
        resize: none !important;
        box-shadow: none !important;
        transition: all 0.15s ease;
    }
    .fcc-custom-textarea:focus,
    textarea.remark:focus {
        border-color: var(--fcc-primary) !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.1) !important;
    }

    /* Stepper Input */
    .fcc-stepper-group {
        display: flex;
        align-items: center;
        border: 1.5px solid #e2e8f0;
        border-radius: 9px;
        height: 42px;
        background: #ffffff;
        overflow: hidden;
    }
    .fcc-stepper-btn {
        width: 42px;
        height: 100%;
        border: none;
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.15s ease;
        user-select: none;
    }
    .fcc-stepper-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .fcc-stepper-input {
        flex: 1;
        width: 100%;
        height: 100% !important;
        border: none !important;
        text-align: center !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        font-family: 'Outfit', sans-serif !important;
        box-shadow: none !important;
        background: transparent !important;
        padding: 0 !important;
        appearance: textfield;
        -moz-appearance: textfield;
    }
    .fcc-stepper-input::-webkit-outer-spin-button,
    .fcc-stepper-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .fcc-stepper-input:focus {
        outline: none;
    }

    /* Submit Button */
    .fcc-btn-submit-blue {
        background: var(--fcc-primary);
        border: 1.5px solid var(--fcc-primary);
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13px;
        padding: 9px 22px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 3px 10px rgba(59, 70, 241, 0.25);
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .fcc-btn-submit-blue:hover {
        background: #2b35d8;
        border-color: #2b35d8;
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(59, 70, 241, 0.35);
    }
    .fcc-btn-submit-blue svg {
        width: 15px;
        height: 15px;
    }

    .fcc-form-info-hint {
        font-size: 12px;
        color: var(--fcc-muted);
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 14px;
        margin-bottom: 0;
    }
    .fcc-form-info-hint svg {
        width: 14px;
        height: 14px;
        color: var(--fcc-primary);
    }

    /* 6. History Table Card */
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

    .fcc-table-controls-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .fcc-table-search-box {
        position: relative;
        min-width: 200px;
    }
    .fcc-table-search-box svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 14px;
        height: 14px;
        color: #94a3b8;
        pointer-events: none;
    }
    .fcc-table-search-input {
        width: 100%;
        height: 38px;
        padding: 0 14px 0 34px !important;
        border: 1.5px solid #e2e8f0;
        border-radius: 9px;
        font-size: 12.5px;
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
    .fcc-remaining-badge-pill {
        height: 38px;
        padding: 0 14px;
        border: 1.5px solid #e0e7ff;
        border-radius: 9px;
        background: #eff2fe;
        color: #3b46f1;
        font-size: 12.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    .fcc-remaining-badge-pill svg {
        width: 14px;
        height: 14px;
    }

    /* Table Styling */
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

    /* Delete Button in Table */
    .fcc-btn-delete-attendance {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626 !important;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 7px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .fcc-btn-delete-attendance:hover {
        background: #dc2626;
        border-color: #dc2626;
        color: #ffffff !important;
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

<div class="fcc-manual-attendance-wrap">

    <!-- 1. Breadcrumbs -->
    <div class="fcc-breadcrumb-nav">
        <a href="{{ route('nutritionPanel.users.index') }}">User management</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <a href="{{ route('nutritionPanel.users.index') }}">All users</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <span class="fcc-breadcrumb-active">Manual attendance</span>
    </div>

    <!-- 2. Header -->
    <div class="fcc-page-header">
        <div>
            <h1 class="fcc-page-title">Manual attendance</h1>
            <p class="fcc-page-subtitle">{{ $user->name ?? 'Member' }} · Mark missed sessions and record today's weight</p>
        </div>
        <div class="fcc-header-btns">
            <a href="{{ route('nutritionPanel.users.viewAttendance', ['id' => $userEncryptedId]) }}" class="btn fcc-btn-history-link">
                <i data-feather="clock"></i>
                <span>View attendance history</span>
            </a>
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
        <a href="{{ route('nutritionPanel.manual-attendances.manual-attendance', ['id' => $userEncryptedId]) }}" class="fcc-tab-link active">
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
        <!-- Card 1: Remaining days -->
        <div class="fcc-metric-card">
            <div class="fcc-metric-icon-circle blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div class="fcc-metric-content">
                <div class="fcc-metric-label">Remaining days</div>
                <div class="fcc-metric-value">
                    {{ $user->days ?? 0 }}
                </div>
            </div>
        </div>

        <!-- Card 2: Last attendance -->
        <div class="fcc-metric-card">
            <div class="fcc-metric-icon-circle green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div class="fcc-metric-content">
                <div class="fcc-metric-label">Last attendance</div>
                <div class="fcc-metric-value">
                    {{ !empty($lastAttendanceDate) ? $lastAttendanceDate : 'No check-in' }}
                </div>
            </div>
        </div>

        <!-- Card 3: Latest weight -->
        <div class="fcc-metric-card">
            <div class="fcc-metric-icon-circle purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
            </div>
            <div class="fcc-metric-content">
                <div class="fcc-metric-label">Latest weight</div>
                <div class="fcc-metric-value">
                    {{ $latestWeight > 0 ? number_format($latestWeight, 1) . ' kg' : 'N/A' }}
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Action Forms Grid (2 Cards Side-by-Side) -->
    <div class="fcc-action-cards-grid">
        <!-- Form 1: Mark manual attendance -->
        <div class="fcc-form-card">
            <div>
                <div class="fcc-form-card-header">
                    <div class="fcc-form-header-icon orange">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div>
                        <h3 class="fcc-form-card-title">Mark manual attendance</h3>
                        <p class="fcc-form-card-subtitle">Add one or more missed check-ins</p>
                    </div>
                </div>

                {!! Form::open(['class' => 'add-manual-attendance-form', 'method' => 'post', 'enctype' => 'multipart/form-data', 'url' => route('nutritionPanel.manual-attendances.addManualAttendance') ]) !!}
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="fcc-form-label">Attendance date <span class="text-danger">*</span></label>
                            <div class="fcc-input-wrap">
                                <i data-feather="calendar"></i>
                                {!! Form::text('date', '', ['class' => 'form-control fcc-custom-input date-picker', 'id' => 'date', 'placeholder' => 'Select attendance date', 'autocomplete' => 'off', 'required' => 'required' ]) !!}
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="fcc-form-label">Attendance count <span class="text-danger">*</span></label>
                            <div class="fcc-stepper-group">
                                <button type="button" class="fcc-stepper-btn" id="fccDaysDecrement">-</button>
                                {!! Form::number('days', '1', ['class' => 'fcc-stepper-input', 'id' => 'days', 'min' => '1', 'max' => '10', 'required' => 'required' ]) !!}
                                <button type="button" class="fcc-stepper-btn" id="fccDaysIncrement">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="fcc-form-label">Remark</label>
                        {!! Form::textarea('remark', '', ['class' => 'form-control fcc-custom-textarea remark', 'id' => 'remark', 'placeholder' => 'Add a note (optional)', 'rows' => 2 ]) !!}
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn fcc-btn-submit-blue">
                            <i data-feather="check-square"></i>
                            <span>Mark attendance</span>
                        </button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>

        <!-- Form 2: Record today's weight -->
        <div class="fcc-form-card">
            <div>
                <div class="fcc-form-card-header">
                    <div class="fcc-form-header-icon green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="fcc-form-card-title">Record today's weight</h3>
                        <p class="fcc-form-card-subtitle">Optional measurement for the selected date</p>
                    </div>
                </div>

                {!! Form::open(['class' => 'add-today-weight-form', 'method' => 'post', 'enctype' => 'multipart/form-data', 'url' => route('nutritionPanel.manual-attendances.addTodayWeight') ]) !!}
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <div class="mb-3">
                        <label class="fcc-form-label">Attendance date <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrap">
                            <i data-feather="calendar"></i>
                            {!! Form::text('date', date('Y-m-d'), ['class' => 'form-control fcc-custom-input date-picker', 'id' => 'weight_date', 'placeholder' => 'Select attendance date', 'autocomplete' => 'off', 'required' => 'required' ]) !!}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fcc-form-label">Weight (kg) <span class="text-danger">*</span></label>
                        <div class="fcc-input-wrap">
                            <i data-feather="activity"></i>
                            {!! Form::number('weight', '', ['class' => 'form-control fcc-custom-input', 'id' => 'weight', 'placeholder' => "Enter today's weight", 'step' => '0.01', 'autocomplete' => 'off', 'required' => 'required' ]) !!}
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-4">
                        <p class="fcc-form-info-hint">
                            <i data-feather="info"></i>
                            <span>This entry will appear in Weight history</span>
                        </p>
                        <button type="submit" class="btn fcc-btn-submit-blue">
                            <i data-feather="check"></i>
                            <span>Save weight</span>
                        </button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!-- 6. History Table Card -->
    <div class="fcc-white-card data-table-container">
        <div class="fcc-card-section-header">
            <div>
                <h3 class="fcc-card-heading">Manual attendance history</h3>
                <p class="fcc-card-subheading">Entries recorded manually for {{ $user->name ?? 'Member' }}</p>
            </div>
            <div class="fcc-table-controls-group">
                <!-- Page Size Dropdown -->
                <select id="fccPageSizeSelect" class="fcc-page-size-select">
                    <option value="20" selected>20 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>

                <!-- Remaining Days Badge Pill -->
                <div class="fcc-remaining-badge-pill">
                    <i data-feather="calendar"></i>
                    <span>{{ $user->days ?? 0 }} days remaining</span>
                </div>
            </div>
        </div>

        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}" />

        <!-- Modern Data Table -->
        <div class="fcc-modern-table-wrap">
            <table id="dataTable" class="table" data-url="{{ route('nutritionPanel.manual-attendances.getManualAttendance') }}" data-track-shake-url="{{ route('nutritionPanel.track-shake.index', ['id' => $userEncryptedId]) }}">
                <thead>
                    <tr>
                        <th style="width: 70px;">Entry</th>
                        <th>Date</th>
                        <th>Weight</th>
                        <th>Attendance count</th>
                        <th style="width: 120px; text-align: right;">Action</th>
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
<script src="{{ asset('admin-assets/js/flatpickr.js') }}"></script>
<script src="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/manual-attendence/view.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Stepper functionality for Days count
        $('#fccDaysIncrement').on('click', function() {
            var $input = $('#days');
            var val = parseInt($input.val(), 10) || 1;
            if (val < 10) {
                $input.val(val + 1);
            }
        });

        $('#fccDaysDecrement').on('click', function() {
            var $input = $('#days');
            var val = parseInt($input.val(), 10) || 1;
            if (val > 1) {
                $input.val(val - 1);
            }
        });
    });
</script>
@endpush
