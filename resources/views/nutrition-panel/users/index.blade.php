@extends('nutrition-panel.layouts.main-layout')

@section('page-title', ($currentTabTitle ?? 'Users') . ' | ' . __('language.page_main_title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

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

    .fcc-users-page-wrapper {
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
        margin-bottom: 8px;
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
        margin-bottom: 22px;
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
    .fcc-btn-register-member {
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
    .fcc-btn-register-member svg,
    .fcc-btn-register-member i {
        width: 16px;
        height: 16px;
        color: #ffffff;
    }
    .fcc-btn-register-member:hover {
        background: #2d38db;
        border-color: #2d38db;
        box-shadow: 0 6px 16px rgba(59, 70, 241, 0.38);
    }

    /* 3. Navigation Tabs */
    .fcc-users-nav-tabs {
        display: flex;
        align-items: center;
        gap: 28px;
        border-bottom: 1.5px solid #e2e8f0;
        margin-bottom: 22px;
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

    /* 4. White Card Container */
    .fcc-users-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        padding: 22px;
    }

    /* 5. Filter & Search Bar */
    .fcc-filter-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }
    .fcc-search-wrap {
        position: relative !important;
        flex-grow: 1;
        max-width: 330px !important;
        min-width: 240px !important;
        display: flex !important;
        align-items: center !important;
    }
    .fcc-search-wrap svg,
    .fcc-search-wrap i,
    .fcc-search-wrap .feather,
    .fcc-search-wrap .feather-search {
        position: absolute !important;
        left: 14px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #94a3b8 !important;
        width: 16px !important;
        height: 16px !important;
        pointer-events: none !important;
        margin: 0 !important;
        padding: 0 !important;
        z-index: 5 !important;
    }
    input.fcc-search-input,
    #fccSearchInput {
        width: 100% !important;
        height: 40px !important;
        background-color: #f8fafc !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding-left: 44px !important;
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
    input.fcc-search-input::placeholder,
    #fccSearchInput::placeholder {
        color: #94a3b8 !important;
        font-size: 13.5px !important;
        opacity: 1 !important;
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
    .fcc-dropdown-pill svg,
    .fcc-dropdown-pill i {
        width: 14px !important;
        height: 14px !important;
        color: #64748b !important;
        vertical-align: middle !important;
    }
    .fcc-dropdown-pill svg.fcc-chevron,
    .fcc-dropdown-pill i.fcc-chevron {
        width: 13px !important;
        height: 13px !important;
        color: #94a3b8 !important;
    }

    /* 6. Table Controls Toolbar */
    .fcc-table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 14px;
        padding-top: 2px;
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
    .fcc-page-len-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }
    .fcc-page-len-btn svg,
    .fcc-page-len-btn i {
        width: 12px;
        height: 12px;
        color: #94a3b8;
    }
    .fcc-btn-batch {
        background: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 9px !important;
        padding: 0 13px !important;
        height: 34px !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        color: #64748b !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        transition: all 0.16s ease !important;
        cursor: pointer !important;
    }
    .fcc-btn-batch svg,
    .fcc-btn-batch i {
        width: 13px !important;
        height: 13px !important;
    }
    .fcc-btn-batch:disabled {
        opacity: 0.55 !important;
        cursor: not-allowed !important;
        background: #f8fafc !important;
        color: #94a3b8 !important;
    }
    .fcc-btn-batch:not(:disabled):hover {
        background: #eff2fe !important;
        border-color: var(--fcc-primary) !important;
        color: var(--fcc-primary) !important;
        box-shadow: 0 2px 6px rgba(59, 70, 241, 0.15) !important;
    }
    .fcc-btn-batch.dt-delete:not(:disabled):hover {
        background: #fef2f2 !important;
        border-color: #ef4444 !important;
        color: #ef4444 !important;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.15) !important;
    }

    /* 7. Modern DataTables Override */
    .fcc-modern-table-wrap {
        overflow-x: auto;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        background: #ffffff;
        margin-bottom: 0 !important;
    }
    table.dataTable {
        margin: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
    }
    table.dataTable thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        padding: 13px 14px !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-top: none !important;
        white-space: nowrap !important;
    }

    /* Disable sort arrows on checkbox and action columns */
    table.dataTable thead th.checkbox-column,
    table.dataTable thead th.no-sort,
    table.dataTable thead th.no-content,
    table.dataTable thead th:first-child,
    table.dataTable thead th:last-child {
        background-image: none !important;
        cursor: default !important;
    }

    table.dataTable thead th.checkbox-column:before,
    table.dataTable thead th.checkbox-column:after,
    table.dataTable thead th.no-sort:before,
    table.dataTable thead th.no-sort:after,
    table.dataTable thead th.no-content:before,
    table.dataTable thead th.no-content:after,
    table.dataTable thead th:first-child:before,
    table.dataTable thead th:first-child:after,
    table.dataTable thead th:last-child:before,
    table.dataTable thead th:last-child:after {
        display: none !important;
        content: "" !important;
        opacity: 0 !important;
    }

    table.dataTable thead th.checkbox-column,
    table.dataTable thead th:first-child {
        padding-right: 10px !important;
        padding-left: 12px !important;
        text-align: center !important;
        width: 40px !important;
        min-width: 40px !important;
        max-width: 40px !important;
    }

    table.dataTable tbody td:first-child,
    table.dataTable tbody td.checkbox-column {
        padding-right: 10px !important;
        padding-left: 12px !important;
        text-align: center !important;
        width: 40px !important;
        min-width: 40px !important;
        max-width: 40px !important;
    }

    table.dataTable tbody:before,
    .table>tbody:before {
        display: none !important;
        content: none !important;
        height: 0 !important;
        line-height: 0 !important;
    }

    table.dataTable tbody td {
        padding: 13px 14px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #334155;
        font-size: 13px;
        background: transparent !important;
    }
    table.dataTable tbody tr:hover td {
        background: #f8faff !important;
    }
    table.dataTable tbody tr.selected td {
        background: #f0f5ff !important;
    }
    table.dataTable tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Avatar Ring with two gaps */
    .fcc-avatar-wrapper {
        position: relative;
        width: 38px;
        height: 38px;
        min-width: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .fcc-avatar-ring {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 1.5px solid #cbd5e1;
        border-top-color: transparent !important;
        border-bottom-color: transparent !important;
        pointer-events: none;
    }

    /* Hide default DataTable elements replaced by custom UI */
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dt-buttons {
        display: none !important;
    }

    /* 8. Modern DataTables Footer & Pagination */
    .fcc-dt-footer {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 14px !important;
        padding-top: 18px !important;
        border-top: 1px solid #f1f5f9 !important;
        margin-top: 14px !important;
    }

    div.dataTables_wrapper div.dataTables_info {
        border: none !important;
        background: transparent !important;
        border-radius: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #64748b !important;
        display: inline-flex !important;
        align-items: center !important;
        white-space: nowrap !important;
        line-height: 1.4 !important;
    }

    div.dataTables_wrapper div.dataTables_paginate {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        margin: 0 !important;
        padding: 0 !important;
        float: none !important;
    }

    div.dataTables_wrapper div.dataTables_paginate ul.pagination {
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
    }

    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.page-item,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.paginate_button,
    div.dataTables_wrapper div.dataTables_paginate .page-item,
    div.dataTables_wrapper div.dataTables_paginate .paginate_button {
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        min-width: 0 !important;
        height: auto !important;
        display: inline-flex !important;
        align-items: center !important;
    }

    div.dataTables_wrapper div.dataTables_paginate ul.pagination li a,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li .page-link {
        min-width: 36px !important;
        height: 36px !important;
        padding: 0 10px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 9px !important;
        border: 1px solid #e2e8f0 !important;
        background: #ffffff !important;
        color: #475569 !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.16s cubic-bezier(0.16, 1, 0.3, 1) !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04) !important;
        text-decoration: none !important;
        user-select: none !important;
    }

    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.active a,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.active .page-link {
        background: var(--fcc-primary) !important;
        border-color: var(--fcc-primary) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(59, 70, 241, 0.3) !important;
        font-weight: 700 !important;
    }

    div.dataTables_wrapper div.dataTables_paginate ul.pagination li:not(.active):not(.disabled) a:hover,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li:not(.active):not(.disabled) .page-link:hover {
        background: #f8fafc !important;
        color: #0f172a !important;
        border-color: #cbd5e1 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(15, 23, 42, 0.08) !important;
    }

    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.disabled a,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.disabled .page-link {
        background: #f8fafc !important;
        border-color: #f1f5f9 !important;
        color: #cbd5e1 !important;
        cursor: not-allowed !important;
        opacity: 0.65 !important;
        box-shadow: none !important;
        transform: none !important;
    }

    div.dataTables_wrapper div.dataTables_paginate ul.pagination li a svg,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li .page-link svg {
        width: 14px !important;
        height: 14px !important;
        stroke-width: 2.2 !important;
        color: #64748b !important;
        vertical-align: middle !important;
    }

    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.disabled a svg,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.disabled .page-link svg {
        color: #cbd5e1 !important;
        stroke: #cbd5e1 !important;
    }

    /* 9. Unique Modern Custom Checkbox */
    .fcc-custom-checkbox {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin: 0 !important;
        user-select: none;
        vertical-align: middle;
    }

    .fcc-custom-checkbox input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        margin: 0;
        pointer-events: none;
    }

    .fcc-checkbox-control {
        width: 19px;
        height: 19px;
        border: 1.8px solid #cbd5e1;
        border-radius: 6px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        position: relative;
    }

    .fcc-custom-checkbox:hover .fcc-checkbox-control {
        border-color: var(--fcc-primary);
        background: #f8faff;
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12);
    }

    .fcc-custom-checkbox input[type="checkbox"]:focus + .fcc-checkbox-control {
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.18);
        border-color: var(--fcc-primary);
    }

    .fcc-custom-checkbox input[type="checkbox"]:checked + .fcc-checkbox-control {
        background: linear-gradient(135deg, #3b46f1 0%, #4361ee 100%) !important;
        border-color: #3b46f1 !important;
        box-shadow: 0 2px 8px rgba(59, 70, 241, 0.32);
    }

    .fcc-custom-checkbox input[type="checkbox"]:checked + .fcc-checkbox-control .fcc-check-icon {
        opacity: 1;
        transform: scale(1);
    }

    .fcc-check-icon {
        width: 11px;
        height: 11px;
        stroke: #ffffff;
        stroke-width: 2.4;
        fill: none;
        stroke-linecap: round;
        stroke-linejoin: round;
        opacity: 0;
        transform: scale(0.6);
        transition: all 0.15s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Indeterminate state */
    .fcc-custom-checkbox input[type="checkbox"]:indeterminate + .fcc-checkbox-control {
        background: linear-gradient(135deg, #3b46f1 0%, #4361ee 100%) !important;
        border-color: #3b46f1 !important;
        box-shadow: 0 2px 8px rgba(59, 70, 241, 0.32);
    }

    .fcc-indeterminate-bar {
        width: 9px;
        height: 2.2px;
        background: #ffffff;
        border-radius: 2px;
        display: none;
    }

    .fcc-custom-checkbox input[type="checkbox"]:indeterminate + .fcc-checkbox-control .fcc-indeterminate-bar {
        display: block;
    }

    .fcc-custom-checkbox input[type="checkbox"]:indeterminate + .fcc-checkbox-control .fcc-check-icon {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="fcc-users-page-wrapper">

    <!-- 1. Breadcrumbs -->
    <div class="fcc-breadcrumb-nav">
        <a href="{{ route('nutritionPanel.users.index') }}">User management</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <span class="fcc-breadcrumb-active">{{ $currentTabTitle ?? 'Offline users' }}</span>
    </div>

    <!-- 2. Header & Action Buttons -->
    <div class="fcc-page-header">
        <div>
            <h1 class="fcc-page-title">{{ $currentTabTitle ?? 'Offline users' }}</h1>
            <p class="fcc-page-subtitle">{{ $currentTabSubtitle ?? 'Manage in-club members, coach assignments, plans and collections' }}</p>
        </div>
        <div class="fcc-header-btns">
            <button type="button" class="btn fcc-btn-export" id="fccExportBtn" title="Export users data to Excel">
                <i data-feather="download" style="width: 15px; height: 15px;"></i>
                <span>Export</span>
            </button>
            <a href="{{ route('nutritionPanel.users.create') }}" class="btn fcc-btn-register-member">
                <i data-feather="plus" style="width: 16px; height: 16px;"></i>
                <span>Register member</span>
            </a>
        </div>
    </div>

    <!-- 3. Navigation Tabs -->
    <div class="fcc-users-nav-tabs">
        <a href="{{ route('nutritionPanel.users.index') }}" class="fcc-tab-item-link {{ empty($userType) ? 'active' : '' }}">
            <span>All users</span>
        </a>
        <a href="{{ route('nutritionPanel.users.index') }}/demo" class="fcc-tab-item-link {{ $userType == 'demo' ? 'active' : '' }}">
            <span>Demo users</span>
        </a>
        <a href="{{ route('nutritionPanel.users.index') }}/offline" class="fcc-tab-item-link {{ $userType == 'offline' ? 'active' : '' }}">
            <span>Offline users</span>
        </a>
        <a href="{{ route('nutritionPanel.users.index') }}/online" class="fcc-tab-item-link {{ $userType == 'online' ? 'active' : '' }}">
            <span>Online users</span>
        </a>
    </div>

    <!-- 4. Main White Card Container -->
    <div class="fcc-users-card">
        
        <!-- Hidden Inputs for Filtering -->
        <input type="hidden" name="user_type" id="user_type" value="{{ $userType }}" />
        <input type="hidden" name="coach_name" id="coach_name" value="" />
        <input type="hidden" name="plan_id" id="plan_id" value="" />
        <input type="hidden" name="payment_status" id="payment_status" value="" />

        <!-- 5. Filter & Search Bar -->
        <div class="fcc-filter-bar">
            <!-- Search Box -->
            <div class="fcc-search-wrap">
                <i data-feather="search"></i>
                <input type="text" id="fccSearchInput" class="fcc-search-input" placeholder="Search name, email or mobile..." autocomplete="off" style="padding-left: 44px !important;" />
            </div>

            <!-- Filter Dropdowns Group -->
            <div class="fcc-filters-group">
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

                <!-- Plan Filter -->
                <div class="dropdown">
                    <button class="btn fcc-dropdown-pill dropdown-toggle" type="button" id="planFilterDropdown" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span id="planFilterLabel">All plans</span>
                        <i data-feather="chevron-down" class="fcc-chevron"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="planFilterDropdown" style="border-radius: 12px; min-width: 200px; padding: 6px; border: 1px solid #edf2f7 !important;">
                        <li><a class="dropdown-item py-2 px-3 rounded-2 active" href="javascript:;" data-filter-type="plan" data-value="">All plans</a></li>
                        @foreach($mealTypes ?? [] as $mt)
                            <li><a class="dropdown-item py-2 px-3 rounded-2" href="javascript:;" data-filter-type="plan" data-value="{{ $mt->id }}">{{ $mt->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Payment Status Filter -->
                <div class="dropdown">
                    <button class="btn fcc-dropdown-pill dropdown-toggle" type="button" id="paymentFilterDropdown" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span id="paymentFilterLabel">Payment status</span>
                        <i data-feather="chevron-down" class="fcc-chevron"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="paymentFilterDropdown" style="border-radius: 12px; min-width: 160px; padding: 6px; border: 1px solid #edf2f7 !important;">
                        <li><a class="dropdown-item py-2 px-3 rounded-2 active" href="javascript:;" data-filter-type="payment" data-value="">All payments</a></li>
                        <li><a class="dropdown-item py-2 px-3 rounded-2" href="javascript:;" data-filter-type="payment" data-value="paid">Paid</a></li>
                        <li><a class="dropdown-item py-2 px-3 rounded-2" href="javascript:;" data-filter-type="payment" data-value="pending">Pending Due</a></li>
                    </ul>
                </div>

                <!-- More Filters Toggle -->
                <button type="button" class="btn fcc-dropdown-pill" id="fccMoreFiltersToggle">
                    <i data-feather="filter" style="width: 14px; height: 14px; color: var(--fcc-primary);"></i>
                    <span>More filters</span>
                    <i data-feather="chevron-down" class="fcc-chevron"></i>
                </button>
            </div>
        </div>

        <!-- Collapsible More Filters (Date Range, etc.) -->
        <div class="collapse mb-3" id="fccMoreFiltersCollapse">
            <div class="p-3 bg-light rounded-3 border" style="border-color: #e2e8f0 !important;">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label text-muted" style="font-size: 12px; font-weight: 600;">Registration Date Range</label>
                        <input type="text" name="date_range" id="date_range" class="form-control form-control-sm date-picker" placeholder="Select Date Range..." autocomplete="off" />
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2 mt-3 mt-md-0 pt-md-3">
                        <button type="button" class="btn btn-sm btn-primary apply-filter px-3">Apply Date</button>
                        <button type="button" class="btn btn-sm btn-light clear-filter px-3 border" id="fccResetDateBtn">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Toolbar Row (Count, Page Size & Batch Actions) -->
        <div class="fcc-table-toolbar">
            <div class="d-flex align-items-center gap-2">
                <span class="fcc-count-text" id="fccTableCountDisplay">
                    {{ $currentTabCount ?? 0 }} {{ strtolower($currentTabTitle ?? 'users') }}
                </span>
                
                <!-- Page Size Selector -->
                <div class="dropdown">
                    <button class="btn fcc-page-len-btn dropdown-toggle" type="button" id="pageSizeDropdown" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span id="pageSizeLabel">20 per page</span>
                        <i data-feather="chevron-down" style="width: 12px; height: 12px;"></i>
                    </button>
                    <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="pageSizeDropdown" style="border-radius: 10px; min-width: 140px; padding: 4px; border: 1px solid #edf2f7 !important;">
                        <li><a class="dropdown-item py-1 px-3 rounded-2 active" href="javascript:;" data-page-size="20">20 per page</a></li>
                        <li><a class="dropdown-item py-1 px-3 rounded-2" href="javascript:;" data-page-size="50">50 per page</a></li>
                        <li><a class="dropdown-item py-1 px-3 rounded-2" href="javascript:;" data-page-size="75">75 per page</a></li>
                        <li><a class="dropdown-item py-1 px-3 rounded-2" href="javascript:;" data-page-size="100">100 per page</a></li>
                    </ul>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 data-table-container">
                <button type="button" class="btn fcc-btn-batch change-status" disabled title="Change Status of selected users">
                    <i data-feather="refresh-cw" style="width: 13px; height: 13px;"></i>
                    <span>Change status</span>
                </button>
                <button type="button" class="btn fcc-btn-batch dt-delete" disabled title="Delete selected users">
                    <i data-feather="trash-2" style="width: 13px; height: 13px;"></i>
                    <span>Delete</span>
                </button>
            </div>
        </div>

        <!-- 7. Table Container -->
        <div class="data-table-container">
            <table id="dataTable" class="table table-hover dataTable" data-url="{{ route('nutritionPanel.users.getUsers') }}" data-change-status-url="{{ route('nutritionPanel.users.changeStatus') }}" data-destroy-url="{{ route('nutritionPanel.users.destroy') }}">
                <thead>
                    <tr>
                        <th class="checkbox-column no-sort no-content text-center" style="width: 38px;"></th>
                        <th>Member</th>
                        <th>User type</th>
                        <th>Contact</th>
                        <th>Coach</th>
                        <th>Plan</th>
                        <th>Renewal</th>
                        <th>Due amount</th>
                        <th>Status</th>
                        <th class="text-end no-sort no-content" style="width: 60px;">Action</th>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/users/view.js') }}"></script>

<script>
$(document).ready(function() {
    feather.replace();

    // Toggle More Filters
    $('#fccMoreFiltersToggle').on('click', function() {
        $('#fccMoreFiltersCollapse').collapse('toggle');
    });

    // Live search input with debounce
    var searchTimer;
    $('#fccSearchInput').on('keyup input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            if (typeof data_table !== 'undefined') {
                data_table.ajax.reload();
            }
        }, 300);
    });

    // Coach filter selection
    $(document).on('click', '[data-filter-type="coach"]', function(e) {
        e.preventDefault();
        var val = $(this).data('value');
        var text = $(this).text();
        $('#coach_name').val(val);
        $('#coachFilterLabel').text(text);
        $('[data-filter-type="coach"]').removeClass('active');
        $(this).addClass('active');
        if (typeof data_table !== 'undefined') {
            data_table.ajax.reload();
        }
    });

    // Plan filter selection
    $(document).on('click', '[data-filter-type="plan"]', function(e) {
        e.preventDefault();
        var val = $(this).data('value');
        var text = $(this).text();
        $('#plan_id').val(val);
        $('#planFilterLabel').text(text);
        $('[data-filter-type="plan"]').removeClass('active');
        $(this).addClass('active');
        if (typeof data_table !== 'undefined') {
            data_table.ajax.reload();
        }
    });

    // Payment status filter selection
    $(document).on('click', '[data-filter-type="payment"]', function(e) {
        e.preventDefault();
        var val = $(this).data('value');
        var text = $(this).text();
        $('#payment_status').val(val);
        $('#paymentFilterLabel').text(text);
        $('[data-filter-type="payment"]').removeClass('active');
        $(this).addClass('active');
        if (typeof data_table !== 'undefined') {
            data_table.ajax.reload();
        }
    });

    // Page size dropdown
    $(document).on('click', '[data-page-size]', function(e) {
        e.preventDefault();
        var size = parseInt($(this).data('page-size'), 10);
        var text = $(this).text();
        $('#pageSizeLabel').text(text);
        $('[data-page-size]').removeClass('active');
        $(this).addClass('active');
        if (typeof data_table !== 'undefined') {
            data_table.page.len(size).draw();
        }
    });

    // Reset date filter
    $('#fccResetDateBtn').on('click', function() {
        $('#date_range').val('');
        if (typeof data_table !== 'undefined') {
            data_table.ajax.reload();
        }
    });

    // Export button click handler
    $('#fccExportBtn').on('click', function(e) {
        e.preventDefault();
        if (typeof data_table !== 'undefined') {
            // Trigger DataTable buttons excel if available
            var btn = data_table.buttons('.buttons-excel');
            if (btn.length) {
                btn.trigger();
            } else {
                // Export current filtered table data to CSV
                var csv = [];
                var rows = document.querySelectorAll("#dataTable tr");
                for (var i = 0; i < rows.length; i++) {
                    var row = [], cols = rows[i].querySelectorAll("td, th");
                    for (var j = 1; j < cols.length - 1; j++) {
                        var text = cols[j].innerText.replace(/"/g, '""').replace(/\n/g, ' ');
                        row.push('"' + text + '"');
                    }
                    if (row.length > 0) csv.push(row.join(","));
                }
                var csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
                var downloadLink = document.createElement("a");
                downloadLink.download = "{{ strtolower($currentTabTitle ?? 'users') }}_export.csv";
                downloadLink.href = window.URL.createObjectURL(csvFile);
                downloadLink.style.display = "none";
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            }
        }
    });

    // Update dynamic count on table draw
    $('#dataTable').on('draw.dt', function() {
        feather.replace();
        if (typeof data_table !== 'undefined') {
            var info = data_table.page.info();
            if (info) {
                $('#fccTableCountDisplay').text(info.recordsDisplay + ' {{ strtolower($currentTabTitle ?? "users") }}');
            }
        }
    });
});
</script>
@endpush
