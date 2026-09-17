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
    .fcc-dropdown-pill.active-filter {
        background: #eff6ff !important;
        border-color: #93c5fd !important;
        color: #1d4ed8 !important;
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

    /* 7. Modern DataTables Override & Perfect Column Formatting */
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
        min-width: 1060px !important;
    }
    table.dataTable thead th {
        position: relative !important;
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        padding: 13px 26px 13px 14px !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-top: none !important;
        white-space: nowrap !important;
        vertical-align: middle !important;
    }

    /* Beautiful Modern Sorting Indicators */
    table.dataTable thead th.sorting:before,
    table.dataTable thead th.sorting_asc:before,
    table.dataTable thead th.sorting_desc:before {
        position: absolute !important;
        right: 10px !important;
        top: 42% !important;
        transform: translateY(-50%) !important;
        content: "▲" !important;
        font-size: 8px !important;
        color: #94a3b8 !important;
        opacity: 0.4 !important;
        line-height: 1 !important;
        display: block !important;
        bottom: auto !important;
    }

    table.dataTable thead th.sorting:after,
    table.dataTable thead th.sorting_asc:after,
    table.dataTable thead th.sorting_desc:after {
        position: absolute !important;
        right: 10px !important;
        top: 58% !important;
        transform: translateY(-50%) !important;
        content: "▼" !important;
        font-size: 8px !important;
        color: #94a3b8 !important;
        opacity: 0.4 !important;
        line-height: 1 !important;
        display: block !important;
        bottom: auto !important;
    }

    table.dataTable thead th.sorting_asc:before {
        opacity: 1 !important;
        color: var(--fcc-primary) !important;
    }
    table.dataTable thead th.sorting_asc:after {
        opacity: 0.15 !important;
    }

    table.dataTable thead th.sorting_desc:after {
        opacity: 1 !important;
        color: var(--fcc-primary) !important;
    }
    table.dataTable thead th.sorting_desc:before {
        opacity: 0.15 !important;
    }

    /* Disable sort arrows on checkbox and action columns */
    table.dataTable thead th.checkbox-column,
    table.dataTable thead th.no-sort,
    table.dataTable thead th.no-content,
    table.dataTable thead th:first-child,
    table.dataTable thead th:last-child {
        background-image: none !important;
        cursor: default !important;
        padding-right: 14px !important;
        padding-left: 14px !important;
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

    /* Column Widths & Alignments */
    table.dataTable thead th.checkbox-column,
    table.dataTable thead th:first-child,
    table.dataTable tbody td.checkbox-column,
    table.dataTable tbody td:first-child {
        padding-right: 10px !important;
        padding-left: 14px !important;
        text-align: center !important;
        width: 44px !important;
        min-width: 44px !important;
        max-width: 44px !important;
    }

    /* Member */
    table.dataTable thead th:nth-child(2),
    table.dataTable tbody td:nth-child(2) {
        min-width: 220px !important;
    }

    /* User Type */
    table.dataTable thead th:nth-child(3),
    table.dataTable tbody td:nth-child(3) {
        min-width: 110px !important;
    }

    /* Contact */
    table.dataTable thead th:nth-child(4),
    table.dataTable tbody td:nth-child(4) {
        min-width: 130px !important;
    }

    /* Coach */
    table.dataTable thead th:nth-child(5),
    table.dataTable tbody td:nth-child(5) {
        min-width: 115px !important;
    }

    /* Plan */
    table.dataTable thead th:nth-child(6),
    table.dataTable tbody td:nth-child(6) {
        min-width: 180px !important;
    }

    /* Renewal */
    table.dataTable thead th:nth-child(7),
    table.dataTable tbody td:nth-child(7) {
        min-width: 105px !important;
    }

    /* Due Amount */
    table.dataTable thead th:nth-child(8),
    table.dataTable tbody td:nth-child(8) {
        min-width: 110px !important;
    }

    /* Status */
    table.dataTable thead th:nth-child(9),
    table.dataTable tbody td:nth-child(9) {
        min-width: 100px !important;
    }

    /* Action */
    table.dataTable thead th:last-child,
    table.dataTable tbody td:last-child {
        width: 70px !important;
        min-width: 70px !important;
        text-align: right !important;
        padding-right: 16px !important;
    }

    table.dataTable tbody:before,
    .table>tbody:before {
        display: none !important;
        content: none !important;
        height: 0 !important;
        line-height: 0 !important;
    }

    table.dataTable tbody td {
        padding: 12px 14px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #334155;
        font-size: 13px;
        background: transparent !important;
        white-space: nowrap !important;
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

    /* Action 3-dots button */
    .fcc-action-dots-btn {
        width: 32px;
        height: 32px;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        color: #64748b !important;
        transition: all 0.16s ease;
        background: transparent;
        text-decoration: none !important;
    }
    .fcc-action-dots-btn:hover,
    .fcc-action-dots-btn[aria-expanded="true"] {
        background: #eff2fe !important;
        color: var(--fcc-primary) !important;
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
    /* 10. Responsive Breakpoints & Enhancements (Desktop, Tablet, Mobile) */
    
    /* Scrollbar for modern table */
    .fcc-modern-table-wrap::-webkit-scrollbar {
        height: 7px;
        width: 7px;
    }
    .fcc-modern-table-wrap::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    .fcc-modern-table-wrap::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .fcc-modern-table-wrap::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Tablet and Medium Screens (max-width: 1024px) */
    @media (max-width: 1024px) {
        .fcc-users-page-wrapper {
            padding: 16px 16px 36px 16px;
        }
        .fcc-page-title {
            font-size: 23px;
        }
        .fcc-users-card {
            padding: 18px;
            border-radius: 14px;
        }
        .fcc-filter-bar {
            gap: 10px;
        }
        .fcc-search-wrap {
            max-width: 280px !important;
            min-width: 200px !important;
        }
        .fcc-dropdown-pill {
            padding: 0 11px !important;
            font-size: 12.5px !important;
        }
    }

    /* Tablet Portrait and Large Mobile (max-width: 991px) */
    @media (max-width: 991px) {
        .fcc-filter-bar {
            flex-direction: column;
            align-items: stretch;
        }
        .fcc-search-wrap {
            max-width: 100% !important;
            min-width: 100% !important;
            width: 100% !important;
        }
        .fcc-filters-group {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .fcc-filters-group > div,
        .fcc-filters-group > button {
            flex: 1 1 auto;
        }
        .fcc-dropdown-pill {
            width: 100% !important;
            justify-content: space-between !important;
        }
    }

    /* Mobile Screens (max-width: 767px) */
    @media (max-width: 767px) {
        .fcc-users-page-wrapper {
            padding: 12px 10px 30px 10px;
        }
        
        .fcc-breadcrumb-nav {
            font-size: 12px;
            margin-bottom: 6px;
        }

        .fcc-page-header {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            margin-bottom: 16px;
        }
        .fcc-page-title {
            font-size: 20px;
            margin-bottom: 2px;
        }
        .fcc-page-subtitle {
            font-size: 12.5px;
            line-height: 1.35;
        }
        .fcc-header-btns {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .fcc-btn-export,
        .fcc-btn-register-member {
            width: 100%;
            justify-content: center;
            padding: 8px 10px;
            font-size: 12.5px;
            height: 38px;
        }
        .fcc-btn-export svg,
        .fcc-btn-export i,
        .fcc-btn-register-member svg,
        .fcc-btn-register-member i {
            width: 14px !important;
            height: 14px !important;
        }

        /* Navigation tabs on mobile */
        .fcc-users-nav-tabs {
            gap: 16px;
            margin-bottom: 16px;
            padding-bottom: 0;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .fcc-users-nav-tabs::-webkit-scrollbar {
            display: none;
        }
        .fcc-tab-item-link {
            font-size: 13px;
            padding: 0 4px 10px 4px;
        }

        /* Card Container on mobile */
        .fcc-users-card {
            padding: 14px 12px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }

        /* Filter bar on mobile */
        .fcc-filter-bar {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
            margin-bottom: 14px;
        }
        .fcc-search-wrap {
            max-width: 100% !important;
            min-width: 100% !important;
            width: 100% !important;
        }
        input.fcc-search-input,
        #fccSearchInput {
            height: 38px !important;
            font-size: 13px !important;
            padding-left: 40px !important;
        }
        .fcc-search-wrap svg,
        .fcc-search-wrap i {
            left: 12px !important;
            width: 15px !important;
            height: 15px !important;
        }

        .fcc-filters-group {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 7px;
        }
        .fcc-filters-group .dropdown {
            width: 100%;
        }
        .fcc-dropdown-pill {
            width: 100% !important;
            height: 38px !important;
            padding: 0 10px !important;
            font-size: 12px !important;
            justify-content: space-between !important;
        }
        .fcc-dropdown-pill span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: calc(100% - 20px);
            text-align: left;
        }
        .fcc-dropdown-pill svg,
        .fcc-dropdown-pill i {
            flex-shrink: 0;
        }
        .dropdown-menu {
            max-width: calc(100vw - 30px) !important;
            z-index: 1060 !important;
        }

        /* Toolbar on mobile */
        .fcc-table-toolbar {
            flex-direction: column;
            align-items: stretch;
            gap: 9px;
            margin-bottom: 12px;
        }
        .fcc-toolbar-left {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        .fcc-count-text {
            font-size: 13px;
        }
        .fcc-page-len-btn {
            height: 30px;
            font-size: 12px;
            padding: 2px 8px;
        }
        .fcc-toolbar-right {
            display: flex;
            align-items: center;
            width: 100%;
            gap: 7px;
        }
        .fcc-btn-batch {
            flex: 1;
            justify-content: center;
            height: 35px !important;
            font-size: 12px !important;
            padding: 0 8px !important;
        }

        /* Table on mobile */
        .fcc-modern-table-wrap {
            border-radius: 10px;
            -webkit-overflow-scrolling: touch;
        }
        table.dataTable {
            min-width: 1040px !important;
        }
        table.dataTable thead th {
            padding: 10px 24px 10px 10px !important;
            font-size: 11px !important;
        }
        table.dataTable thead th.checkbox-column,
        table.dataTable thead th.no-sort,
        table.dataTable thead th:first-child,
        table.dataTable thead th:last-child {
            padding-right: 10px !important;
            padding-left: 10px !important;
        }
        table.dataTable tbody td {
            padding: 10px 10px !important;
            font-size: 12px !important;
        }

        /* Footer & Pagination on mobile */
        .fcc-dt-footer {
            flex-direction: column !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 10px !important;
            text-align: center !important;
            padding-top: 14px !important;
        }
        div.dataTables_wrapper div.dataTables_info {
            justify-content: center !important;
            text-align: center !important;
            width: 100% !important;
            font-size: 12px !important;
            white-space: normal !important;
        }
        div.dataTables_wrapper div.dataTables_paginate {
            justify-content: center !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: auto !important;
            padding-bottom: 3px !important;
            -webkit-overflow-scrolling: touch;
        }
        div.dataTables_wrapper div.dataTables_paginate ul.pagination {
            flex-wrap: nowrap !important;
            gap: 4px !important;
            justify-content: center !important;
        }
        div.dataTables_wrapper div.dataTables_paginate ul.pagination li a,
        div.dataTables_wrapper div.dataTables_paginate ul.pagination li .page-link {
            min-width: 32px !important;
            height: 32px !important;
            font-size: 12px !important;
            padding: 0 6px !important;
            border-radius: 7px !important;
        }
    }

    /* Extra Small Screen (< 420px) */
    @media (max-width: 420px) {
        .fcc-page-title {
            font-size: 18px;
        }
        .fcc-header-btns {
            grid-template-columns: 1fr;
            gap: 6px;
        }
        .fcc-filters-group {
            grid-template-columns: 1fr;
            gap: 6px;
        }
        .fcc-count-text {
            font-size: 12.5px;
        }
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
        <div class="fcc-header-title-box">
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
        <input type="hidden" name="user_type" id="user_type" value="{{ request('user_type', $userType) }}" />
        <input type="hidden" name="coach_name" id="coach_name" value="{{ request('coach_name', '') }}" />
        <input type="hidden" name="plan_id" id="plan_id" value="{{ request('plan_id', '') }}" />
        <input type="hidden" name="payment_status" id="payment_status" value="{{ request('payment_status', '') }}" />

        <!-- 5. Filter & Search Bar -->
        <div class="fcc-filter-bar">
            <!-- Search Box -->
            <div class="fcc-search-wrap">
                <i data-feather="search"></i>
                <input type="text" id="fccSearchInput" class="fcc-search-input" placeholder="Search name, email or mobile..." autocomplete="off" style="padding-left: 44px !important;" value="{{ request('search', '') }}" />
            </div>

            <!-- Filter Dropdowns Group -->
            <div class="fcc-filters-group">
                <!-- Coach Filter -->
                <div class="dropdown">
                    <button class="btn fcc-dropdown-pill dropdown-toggle" type="button" id="coachFilterDropdown" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span id="coachFilterLabel">{{ !empty(request('coach_name')) ? request('coach_name') : 'All coaches' }}</span>
                        <i data-feather="chevron-down" class="fcc-chevron"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="coachFilterDropdown" style="border-radius: 12px; min-width: 170px; padding: 6px; border: 1px solid #edf2f7 !important;">
                        <li><a class="dropdown-item py-2 px-3 rounded-2 {{ empty(request('coach_name')) ? 'active' : '' }}" href="javascript:;" data-filter-type="coach" data-value="">All coaches</a></li>
                        @foreach($coachesList ?? [] as $coach)
                            @if(!empty($coach))
                                <li><a class="dropdown-item py-2 px-3 rounded-2 {{ request('coach_name') == $coach ? 'active' : '' }}" href="javascript:;" data-filter-type="coach" data-value="{{ $coach }}">{{ $coach }}</a></li>
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
                        <span id="paymentFilterLabel">{{ request('payment_status') == 'paid' ? 'Paid' : (request('payment_status') == 'pending' ? 'Pending Due' : 'Payment status') }}</span>
                        <i data-feather="chevron-down" class="fcc-chevron"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="paymentFilterDropdown" style="border-radius: 12px; min-width: 160px; padding: 6px; border: 1px solid #edf2f7 !important;">
                        <li><a class="dropdown-item py-2 px-3 rounded-2 {{ empty(request('payment_status')) ? 'active' : '' }}" href="javascript:;" data-filter-type="payment" data-value="">All payments</a></li>
                        <li><a class="dropdown-item py-2 px-3 rounded-2 {{ request('payment_status') == 'paid' ? 'active' : '' }}" href="javascript:;" data-filter-type="payment" data-value="paid">Paid</a></li>
                        <li><a class="dropdown-item py-2 px-3 rounded-2 {{ request('payment_status') == 'pending' ? 'active' : '' }}" href="javascript:;" data-filter-type="payment" data-value="pending">Pending Due</a></li>
                    </ul>
                </div>

                <!-- Date Range Filter Dropdown -->
                <div class="dropdown">
                    <button class="btn fcc-dropdown-pill dropdown-toggle {{ !empty(request('date_range')) ? 'active-filter' : '' }}" type="button" id="dateFilterDropdown" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="calendar" style="width: 14px; height: 14px; color: var(--fcc-primary);"></i>
                        <span id="dateFilterLabel">{{ !empty(request('date_range')) ? request('date_range') : 'Date range' }}</span>
                        <i data-feather="chevron-down" class="fcc-chevron"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-3" aria-labelledby="dateFilterDropdown" style="border-radius: 14px; min-width: 300px; border: 1px solid #edf2f7 !important; background: #ffffff;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span style="font-size: 11.5px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Registration Date</span>
                            <a href="javascript:;" id="fccClearDateBtn" class="text-danger fw-semibold" style="font-size: 11.5px; text-decoration: none;">Clear</a>
                        </div>
                        <div class="position-relative mb-2">
                            <input type="text" name="date_range" id="date_range" class="form-control date-picker" placeholder="Select Date Range..." autocomplete="off" value="{{ request('date_range', '') }}" style="border-radius: 9px; font-size: 12.5px; height: 38px; padding-left: 12px !important; border: 1.5px solid #e2e8f0;" />
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <button type="button" class="btn btn-primary btn-sm w-100 apply-filter" style="border-radius: 8px; font-weight: 600; height: 34px;">Apply Range</button>
                        </div>
                    </div>
                </div>

                <!-- Reset All Filters Button -->
                <button type="button" class="btn fcc-dropdown-pill" id="fccResetAllFiltersBtn" title="Reset all active filters" style="color: #64748b; padding: 0 12px !important;">
                    <i data-feather="rotate-ccw" style="width: 13px; height: 13px;"></i>
                    <span>Reset</span>
                </button>
            </div>
        </div>

        <!-- 6. Toolbar Row (Count, Page Size & Batch Actions) -->
        <div class="fcc-table-toolbar">
            <div class="fcc-toolbar-left d-flex align-items-center gap-2">
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

            <div class="fcc-toolbar-right d-flex align-items-center gap-2 data-table-container">
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

    function reloadDataTable() {
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().ajax.reload();
        } else if (typeof data_table !== 'undefined') {
            data_table.ajax.reload();
        }
    }

    // Toggle More Filters
    $('#fccMoreFiltersToggle').on('click', function() {
        $('#fccMoreFiltersCollapse').collapse('toggle');
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
        var text = $(this).text();
        $('#coach_name').val(val);
        $('#coachFilterLabel').text(text);
        $('[data-filter-type="coach"]').removeClass('active');
        $(this).addClass('active');
        reloadDataTable();
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
        reloadDataTable();
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
        reloadDataTable();
    });

    // Apply date filter button
    $(document).on('click', '.apply-filter', function(e) {
        e.preventDefault();
        var val = $('#date_range').val();
        if (val) {
            $('#dateFilterLabel').text(val);
            $('#dateFilterDropdown').addClass('active-filter');
        } else {
            $('#dateFilterLabel').text('Date range');
            $('#dateFilterDropdown').removeClass('active-filter');
        }
        $('#dateFilterDropdown').dropdown('hide');
        reloadDataTable();
    });

    // Clear date filter
    $('#fccClearDateBtn').on('click', function(e) {
        e.preventDefault();
        $('#date_range').val('');
        $('#dateFilterLabel').text('Date range');
        $('#dateFilterDropdown').removeClass('active-filter');
        reloadDataTable();
    });

    // Reset all filters button
    $('#fccResetAllFiltersBtn').on('click', function(e) {
        e.preventDefault();
        $('#fccSearchInput').val('');
        $('#coach_name').val('');
        $('#coachFilterLabel').text('All coaches');
        $('[data-filter-type="coach"]').removeClass('active').first().addClass('active');
        
        $('#plan_id').val('');
        $('#planFilterLabel').text('All plans');
        $('[data-filter-type="plan"]').removeClass('active').first().addClass('active');

        $('#payment_status').val('');
        $('#paymentFilterLabel').text('Payment status');
        $('[data-filter-type="payment"]').removeClass('active').first().addClass('active');

        $('#date_range').val('');
        $('#dateFilterLabel').text('Date range');
        $('#dateFilterDropdown').removeClass('active-filter');

        reloadDataTable();
    });

    // Page size dropdown
    $(document).on('click', '[data-page-size]', function(e) {
        e.preventDefault();
        var size = parseInt($(this).data('page-size'), 10);
        var text = $(this).text();
        $('#pageSizeLabel').text(text);
        $('[data-page-size]').removeClass('active');
        $(this).addClass('active');
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().page.len(size).draw();
        } else if (typeof data_table !== 'undefined') {
            data_table.page.len(size).draw();
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
