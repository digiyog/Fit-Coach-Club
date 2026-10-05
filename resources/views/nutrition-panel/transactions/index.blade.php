@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Transactions | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    /* Transactions Page Styles */
    .tx-page-wrapper {
        padding: 6px 10px 40px 10px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Top Breadcrumb */
    .tx-breadcrumb {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .tx-breadcrumb a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .tx-breadcrumb a:hover {
        color: #2563eb;
    }

    .tx-breadcrumb .crumb-sep {
        color: #94a3b8;
    }

    .tx-breadcrumb .crumb-active {
        color: #0f172a;
        font-weight: 600;
    }

    /* Page Header */
    .tx-header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .tx-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .tx-subtitle {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 0;
        font-weight: 400;
    }

    .btn-add-tx {
        background: #2563eb;
        color: #ffffff !important;
        border: none;
        border-radius: 10px;
        padding: 9px 20px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        text-decoration: none !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        cursor: pointer;
    }

    .btn-add-tx:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.4);
    }

    /* KPI Summary Card ("Current results") */
    .tx-summary-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px 26px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 24px;
    }

    .badge-current-results {
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

    .tx-kpi-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 20px;
        align-items: center;
    }

    @media (max-width: 1200px) {
        .tx-kpi-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .tx-kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 480px) {
        .tx-kpi-grid {
            grid-template-columns: 1fr;
        }
    }

    .tx-kpi-item {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .tx-kpi-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }

    .tx-kpi-icon.icon-blue {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }

    .tx-kpi-icon.icon-green {
        background: #ecfdf5;
        color: #10b981;
        border: 1px solid #d1fae5;
    }

    .tx-kpi-icon.icon-amber {
        background: #fffbeb;
        color: #f59e0b;
        border: 1px solid #fef3c7;
    }

    .tx-kpi-data {
        display: flex;
        flex-direction: column;
    }

    .tx-kpi-val {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .tx-kpi-lbl {
        font-size: 12.5px;
        font-weight: 500;
        color: #64748b;
        margin-top: 4px;
    }

    /* Donut chart for collection rate */
    .tx-kpi-donut-wrap {
        width: 46px;
        height: 46px;
        position: relative;
        flex-shrink: 0;
    }

    .tx-kpi-donut-svg {
        transform: rotate(-90deg);
        width: 46px;
        height: 46px;
    }

    .donut-bg {
        stroke: #f1f5f9;
    }

    .donut-stroke {
        transition: stroke-dasharray 0.5s ease;
    }

    /* Dual-tone Progress Bar */
    .tx-kpi-progress-item {
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 100%;
    }

    .tx-kpi-progress-track {
        background: #fde68a;
        border-radius: 999px;
        height: 10px;
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .tx-kpi-progress-fill {
        background: #10b981;
        border-radius: 999px;
        height: 100%;
        transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Main Ledger Card */
    .tx-ledger-card {
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
        grid-template-columns: 2.2fr 1.3fr 1.3fr 1.3fr 1.1fr;
        gap: 12px;
        margin-bottom: 16px;
    }

    @media (max-width: 1200px) {
        .ledger-filters-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .ledger-filters-grid {
            grid-template-columns: 1fr;
        }
    }

    .filter-search-wrap,
    .filter-date-wrap {
        position: relative !important;
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
    }

    .filter-icon-left {
        position: absolute !important;
        left: 14px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #94a3b8 !important;
        font-size: 14px !important;
        pointer-events: none !important;
        z-index: 5 !important;
        line-height: 1 !important;
    }

    .filter-icon-right {
        position: absolute !important;
        right: 14px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #94a3b8 !important;
        font-size: 11px !important;
        pointer-events: none !important;
        z-index: 5 !important;
        line-height: 1 !important;
    }

    .tx-ledger-card input.filter-input-styled,
    .tx-ledger-card input#tx_search,
    .tx-ledger-card input#tx_date_range,
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

    .tx-ledger-card input#tx_date_range,
    input.filter-date-input {
        padding-right: 36px !important;
        cursor: pointer !important;
    }

    .tx-ledger-card input.filter-input-styled:focus,
    .tx-ledger-card input#tx_search:focus,
    .tx-ledger-card input#tx_date_range:focus,
    input.filter-input-styled:focus {
        background-color: #ffffff !important;
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    .tx-ledger-card input.filter-input-styled::placeholder,
    .tx-ledger-card input#tx_search::placeholder,
    .tx-ledger-card input#tx_date_range::placeholder,
    input.filter-input-styled::placeholder {
        color: #94a3b8 !important;
        font-size: 13.5px !important;
        font-weight: 400 !important;
        opacity: 1 !important;
    }

    .tx-ledger-card select.filter-select-styled,
    .tx-ledger-card select#tx_payment_type,
    .tx-ledger-card select#tx_collection_state,
    select.filter-select-styled {
        height: 42px !important;
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        font-size: 13.5px !important;
        color: #334155 !important;
        font-weight: 500 !important;
        background-color: #f8fafc !important;
        cursor: pointer !important;
        box-shadow: none !important;
        outline: none !important;
        width: 100% !important;
        padding: 0 34px 0 14px !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 14px center !important;
        background-size: 12px !important;
        transition: all 0.2s ease !important;
        font-family: inherit !important;
    }

    .tx-ledger-card select.filter-select-styled:focus,
    .tx-ledger-card select#tx_payment_type:focus,
    .tx-ledger-card select#tx_collection_state:focus,
    select.filter-select-styled:focus {
        background-color: #ffffff !important;
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    .tx-ledger-card .btn-more-filters,
    .btn-more-filters {
        height: 42px !important;
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        padding: 0 14px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
        outline: none !important;
        font-family: inherit !important;
    }

    .tx-ledger-card .btn-more-filters:hover,
    .tx-ledger-card .btn-more-filters.active,
    .btn-more-filters:hover,
    .btn-more-filters.active {
        background: #ffffff !important;
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
    }

    /* Toolbar Row 2: Counts & Actions */
    .ledger-actions-row {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 12px !important;
        margin-bottom: 18px !important;
        padding-bottom: 14px !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    .ledger-actions-left {
        display: flex !important;
        align-items: center !important;
        gap: 16px !important;
    }

    .table-count-text {
        font-size: 13.5px !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
    }

    .tx-ledger-card .page-len-select,
    .page-len-select {
        height: 36px !important;
        border-radius: 8px !important;
        border: 1.5px solid #e2e8f0 !important;
        font-size: 12.5px !important;
        color: #334155 !important;
        padding: 0 28px 0 10px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        background-color: #ffffff !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 10px center !important;
        background-size: 10px !important;
        box-shadow: none !important;
        outline: none !important;
        transition: all 0.15s ease !important;
    }

    .tx-ledger-card .page-len-select:focus,
    .page-len-select:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    .tx-ledger-card .btn-clear-pill,
    .btn-clear-pill {
        background: #ffffff !important;
        border-radius: 9px !important;
        padding: 6px 15px !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 7px !important;
        transition: all 0.15s ease !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04) !important;
        border: 1.5px solid #e2e8f0 !important;
        color: #475569 !important;
        cursor: pointer !important;
        height: 36px !important;
        outline: none !important;
    }

    .tx-ledger-card .btn-clear-pill:hover,
    .btn-clear-pill:hover {
        background: #f8fafc !important;
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
    }

    /* Modern Table Styling with smooth horizontal scrolling */
    .table-container-modern {
        border-radius: 12px;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        background: #ffffff;
        border: 1px solid #edf2f7;
    }

    .table-container-modern::-webkit-scrollbar {
        height: 7px;
    }

    .table-container-modern::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 4px;
    }

    .table-container-modern::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .table-container-modern::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    #dataTable {
        width: 100% !important;
        min-width: 1150px !important;
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
        white-space: nowrap !important;
    }

    #dataTable tbody td {
        padding: 13px 16px;
        vertical-align: middle;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
        white-space: nowrap !important;
    }

    #dataTable tbody tr:hover td {
        background: #f8fafc;
    }

    /* Row Content Typography & Badges */
    .user-name-text {
        font-weight: 600;
        color: #0f172a;
    }

    .order-number-text {
        color: #64748b;
    }

    .title-text {
        color: #334155;
        font-weight: 500;
    }

    .amount-total {
        font-weight: 500;
        color: #0f172a;
    }

    .amount-due-active {
        color: #d97706;
        font-weight: 700;
    }

    .amount-received-active {
        color: #059669;
        font-weight: 700;
    }

    .amount-zero {
        color: #64748b;
    }

    .badge-type-subscription {
        background-color: #eff6ff;
        color: #2563eb;
        font-size: 12px;
        font-weight: 600;
        border-radius: 6px;
        padding: 4px 12px;
        display: inline-block;
        border: 1px solid #dbeafe;
    }

    .badge-type-product {
        background-color: #f5f3ff;
        color: #7c3aed;
        font-size: 12px;
        font-weight: 600;
        border-radius: 6px;
        padding: 4px 12px;
        display: inline-block;
        border: 1px solid #ede9fe;
    }

    .btn-remark-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.15s ease;
    }

    .btn-remark-pill:hover {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .btn-edit-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        background: #ffffff;
        color: #334155;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        transition: all 0.15s ease;
    }

    .btn-edit-action:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .date-text {
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

    /* Reset & format Info text (e.g. "Showing 333 transactions") */
    div.dataTables_wrapper div.dataTables_info,
    div.dataTables_wrapper .dataTables_info,
    .tx-ledger-card .dataTables_info,
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

    /* In case of span containers in non-bootstrap renderer */
    .dataTables_paginate > span {
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Hide default legacy DataTables controls */
    div.dataTables_wrapper div.dataTables_length,
    div.dataTables_wrapper div.dataTables_filter {
        display: none !important;
    }

    /* Hide footer row inside table */
    #dataTable tfoot {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="tx-page-wrapper">
        <!-- Breadcrumb Navigation -->
        <div class="tx-breadcrumb">
            <a href="{{ route('nutritionPanel.dashboard') }}">Finance & Plans</a>
            <span class="crumb-sep">/</span>
            <span class="crumb-active">Transactions</span>
        </div>

        <!-- Header Section -->
        <div class="tx-header-section">
            <div>
                <h1 class="tx-title">Transactions</h1>
                <p class="tx-subtitle">Track subscription and product payments, received amounts and outstanding balances.</p>
            </div>
            <div>
                <a href="javascript:;" class="btn-add-tx create-transaction" data-url="{{ route('nutritionPanel.transactions.addTransaction') }}">
                    <i class="fa fa-plus"></i> Add transaction
                </a>
            </div>
        </div>

        <!-- Summary Card ("Current results") -->
        <div class="tx-summary-card">
            <div>
                <span class="badge-current-results">Current results</span>
            </div>
            <div class="tx-kpi-grid">
                <!-- Total Amount -->
                <div class="tx-kpi-item">
                    <div class="tx-kpi-icon icon-blue">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <div class="tx-kpi-data">
                        <span class="tx-kpi-val" id="kpi-total-amount">{{ $totalAmountFormatted ?? number_format($totalAmount ?? 0, 0) }}</span>
                        <span class="tx-kpi-lbl">Total amount</span>
                    </div>
                </div>

                <!-- Received Amount -->
                <div class="tx-kpi-item">
                    <div class="tx-kpi-icon icon-green">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                    </div>
                    <div class="tx-kpi-data">
                        <span class="tx-kpi-val" id="kpi-received-amount">{{ $receivedAmountFormatted ?? number_format($receivedAmount ?? 0, 0) }}</span>
                        <span class="tx-kpi-lbl">Received amount</span>
                    </div>
                </div>

                <!-- Outstanding Amount -->
                <div class="tx-kpi-item">
                    <div class="tx-kpi-icon icon-amber">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="tx-kpi-data">
                        <span class="tx-kpi-val" id="kpi-due-amount">{{ $dueAmountFormatted ?? number_format($dueAmount ?? 0, 0) }}</span>
                        <span class="tx-kpi-lbl">Outstanding amount</span>
                    </div>
                </div>

                <!-- Collection Rate -->
                <div class="tx-kpi-item">
                    <div class="tx-kpi-donut-wrap">
                        <svg class="tx-kpi-donut-svg" viewBox="0 0 36 36">
                            <path class="donut-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-width="4.5"/>
                            <path id="kpi-donut-stroke" class="donut-stroke" stroke-dasharray="{{ min(100, max(0, $collectionRate ?? 0)) }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#10b981" stroke-width="4.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="tx-kpi-data">
                        <span class="tx-kpi-val" id="kpi-collection-rate">{{ $collectionRateFormatted ?? ($collectionRate . '%') }}</span>
                        <span class="tx-kpi-lbl">Collection rate</span>
                    </div>
                </div>

                <!-- Progress Bar: Received versus total amount -->
                <div class="tx-kpi-progress-item">
                    <div class="tx-kpi-progress-track">
                        <div class="tx-kpi-progress-fill" id="kpi-progress-fill" style="width: {{ min(100, max(0, $collectionRate ?? 0)) }}%;"></div>
                    </div>
                    <span class="tx-kpi-lbl mt-2">Received versus total amount</span>
                </div>
            </div>
        </div>

        <!-- Ledger Table Card -->
        <div class="tx-ledger-card">
            <div class="ledger-title-bar">
                <div class="title-accent-pill"></div>
                <h3 class="ledger-heading">Transaction ledger</h3>
            </div>
            <p class="ledger-subheading">Review payments, balances, remarks and transaction dates.</p>

            <!-- Filters Bar (Row 1) -->
            <div class="ledger-filters-grid">
                <!-- Search Input -->
                <div class="filter-search-wrap">
                    <i class="fa fa-search filter-icon-left"></i>
                    <input type="text" id="tx_search" class="filter-input-styled" placeholder="Search user, order or title..." autocomplete="off" style="padding-left: 42px !important;">
                </div>

                <!-- Date Range -->
                <div class="filter-date-wrap">
                    <i class="fa fa-calendar filter-icon-left"></i>
                    <input type="text" id="tx_date_range" class="filter-input-styled filter-date-input" placeholder="Date range" readonly autocomplete="off" style="padding-left: 42px !important; padding-right: 36px !important;">
                    <i class="fa fa-chevron-down filter-icon-right"></i>
                </div>

                <!-- Payment Type Filter -->
                <div>
                    <select id="tx_payment_type" class="filter-select-styled">
                        <option value="">All payment types</option>
                        <option value="subscription">Subscription</option>
                        <option value="product">Product</option>
                    </select>
                </div>

                <!-- Collection State Filter -->
                <div>
                    <select id="tx_collection_state" class="filter-select-styled">
                        <option value="">All collection states</option>
                        <option value="paid">Paid in full</option>
                        <option value="due">Outstanding due</option>
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
                    <span class="table-count-text"><span id="visible_tx_count">0</span> visible transactions</span>
                    <select id="tx_page_length" class="page-len-select">
                        <option value="20" selected>20 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                        <option value="200">200 per page</option>
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
                <table id="dataTable" class="table" data-url="{{ route('nutritionPanel.transactions.getTransactions') }}">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Order Number</th>
                            <th>Title</th>
                            <th>Total Amount</th>
                            <th>Due Amount</th>
                            <th>Received Amount</th>
                            <th>Payment Type</th>
                            <th>Remark</th>
                            <th>Date</th>
                            <th class="text-end">Action</th>
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
<script src="{{ asset('admin-assets/js/transactions/view.js') }}?v={{ file_exists(public_path('admin-assets/js/transactions/view.js')) ? filemtime(public_path('admin-assets/js/transactions/view.js')) : time() }}"></script>
@endpush
