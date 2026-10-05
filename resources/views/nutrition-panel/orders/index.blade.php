@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Orders | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    /* Orders Page Styles */
    .order-page-wrapper {
        padding: 6px 10px 40px 10px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Top Breadcrumb */
    .order-breadcrumb {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .order-breadcrumb a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .order-breadcrumb a:hover {
        color: #2563eb;
    }

    .order-breadcrumb .crumb-sep {
        color: #94a3b8;
    }

    .order-breadcrumb .crumb-active {
        color: #0f172a;
        font-weight: 600;
    }

    /* Page Header */
    .order-header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .order-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .order-subtitle {
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

    /* KPI Summary Card ("Current results") */
    .order-summary-card {
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

    .order-kpi-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr 1.6fr;
        gap: 20px;
        align-items: center;
    }

    @media (max-width: 1200px) {
        .order-kpi-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .order-kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 480px) {
        .order-kpi-grid {
            grid-template-columns: 1fr;
        }
    }

    .order-kpi-item {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .order-kpi-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }

    .order-kpi-icon.icon-blue {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }

    .order-kpi-icon.icon-cyan {
        background: #f0f9ff;
        color: #0284c7;
        border: 1px solid #e0f2fe;
    }

    .order-kpi-icon.icon-green {
        background: #ecfdf5;
        color: #10b981;
        border: 1px solid #d1fae5;
        border-radius: 50%;
    }

    .order-kpi-icon.icon-amber {
        background: #fffbeb;
        color: #f59e0b;
        border: 1px solid #fef3c7;
        border-radius: 50%;
    }

    .order-kpi-data {
        display: flex;
        flex-direction: column;
    }

    .order-kpi-val {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .order-kpi-lbl {
        font-size: 12.5px;
        font-weight: 500;
        color: #64748b;
        margin-top: 4px;
    }

    /* Multi-segmented Progress Bar & Legend */
    .order-progress-block {
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 100%;
    }

    .order-segmented-bar {
        height: 10px;
        width: 100%;
        background: #f1f5f9;
        border-radius: 999px;
        overflow: hidden;
        display: flex;
    }

    .bar-segment {
        height: 100%;
        transition: width 0.4s ease;
    }

    .bar-segment.seg-delivered {
        background: #10b981;
    }

    .bar-segment.seg-cancelled {
        background: #f43f5e;
    }

    .bar-segment.seg-placed {
        background: #334155;
    }

    .order-segmented-legend {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .legend-item {
        font-size: 12px;
        color: #475569;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .legend-dot.dot-delivered {
        background: #10b981;
    }

    .legend-dot.dot-cancelled {
        background: #f43f5e;
    }

    .legend-dot.dot-placed {
        background: #334155;
    }

    /* Main Ledger Card */
    .order-ledger-card {
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
    .tx-ledger-card input#order_search,
    .tx-ledger-card input#order_date_range,
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

    .tx-ledger-card input#order_date_range,
    input.filter-date-input {
        padding-right: 36px !important;
        cursor: pointer !important;
    }

    .tx-ledger-card input.filter-input-styled:focus,
    .tx-ledger-card input#order_search:focus,
    .tx-ledger-card input#order_date_range:focus,
    input.filter-input-styled:focus {
        background-color: #ffffff !important;
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    .tx-ledger-card input.filter-input-styled::placeholder,
    .tx-ledger-card input#order_search::placeholder,
    .tx-ledger-card input#order_date_range::placeholder,
    input.filter-input-styled::placeholder {
        color: #94a3b8 !important;
        font-size: 13.5px !important;
        font-weight: 400 !important;
        opacity: 1 !important;
    }

    .tx-ledger-card select.filter-select-styled,
    .tx-ledger-card select#order_payment_status,
    .tx-ledger-card select#order_status_filter,
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
    .tx-ledger-card select#order_payment_status:focus,
    .tx-ledger-card select#order_status_filter:focus,
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

    /* Modern Table Styling */
    .table-container-modern {
        border-radius: 12px;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        background: #ffffff;
        border: 1px solid #edf2f7;
    }

    .table-container-modern::-webkit-scrollbar {
        height: 6px;
    }

    .table-container-modern::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 3px;
    }

    .table-container-modern::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .table-container-modern::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    #dataTable {
        width: 100% !important;
        min-width: 980px !important;
        border-collapse: separate;
        border-spacing: 0;
        border: none;
    }

    #dataTable thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        padding: 12px 10px;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        letter-spacing: 0.01em;
        white-space: nowrap !important;
        vertical-align: middle;
    }

    #dataTable thead th:first-child,
    #dataTable tbody td:first-child {
        padding-left: 14px;
    }

    #dataTable thead th:last-child,
    #dataTable tbody td:last-child {
        padding-right: 14px;
    }

    #dataTable tbody td {
        padding: 11px 10px;
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

    /* Column Typography & Links */
    .order-info-cell {
        display: flex;
        flex-direction: column;
        gap: 2px;
        line-height: 1.25;
    }

    .order-date-text {
        font-size: 11.5px;
        color: #64748b;
        white-space: nowrap;
    }

    .order-num-text {
        font-size: 12.5px;
        color: #0f172a;
        font-weight: 600;
        white-space: nowrap;
    }

    .order-link {
        color: #2563eb !important;
        font-weight: 600;
        text-decoration: none;
    }

    .order-link:hover {
        text-decoration: underline;
    }

    .order-user-name {
        font-weight: 600;
        color: #0f172a;
        font-size: 13px;
        white-space: nowrap;
    }

    .order-mobile-num {
        color: #475569;
        font-size: 12.5px;
        font-weight: 500;
        white-space: nowrap;
    }

    .order-amount, .order-net-amount {
        font-weight: 600;
        color: #0f172a;
        font-size: 13px;
        white-space: nowrap;
    }

    .order-discount {
        color: #64748b;
        font-size: 12.5px;
        white-space: nowrap;
    }

    /* Payment Status Pill Dropdowns */
    .btn-payment-pill {
        border-radius: 6px;
        padding: 3px 9px;
        font-size: 11.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
    }

    .btn-payment-pill.pill-pending {
        background-color: #fef2f2;
        color: #ef4444;
        border-color: #fee2e2;
    }

    .btn-payment-pill.pill-pending:hover {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .btn-payment-pill.pill-success {
        background-color: #f0fdf4;
        color: #16a34a;
        border-color: #bbf7d0;
    }

    .btn-payment-pill.pill-success:hover {
        background-color: #dcfce7;
        color: #15803d;
    }

    /* Order Status Badges */
    .badge-order-status {
        display: inline-block;
        padding: 3px 9px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-placed {
        background-color: #f1f5f9;
        color: #334155;
    }

    .status-ready {
        background-color: #eff6ff;
        color: #2563eb;
    }

    .status-shipped {
        background-color: #f5f3ff;
        color: #7c3aed;
    }

    .status-transit {
        background-color: #eef2ff;
        color: #4f46e5;
    }

    .status-delivered {
        background-color: #ecfdf5;
        color: #059669;
    }

    .status-cancelled {
        background-color: #fef2f2;
        color: #e11d48;
    }

    .status-return {
        background-color: #fffbeb;
        color: #d97706;
    }

    .status-refund {
        background-color: #ecfeff;
        color: #0891b2;
    }

    /* Action 3-dots button and Dropdown */
    .btn-action-dots {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .btn-action-dots:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .action-dropdown-menu {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        padding: 6px;
        min-width: 170px;
        z-index: 1050;
    }

    .action-dropdown-menu .dropdown-item {
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 500;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.15s ease;
    }

    .action-dropdown-menu .dropdown-item:hover {
        background-color: #f1f5f9;
        color: #2563eb;
    }

    .action-item-icon {
        font-size: 14px;
        width: 16px;
        text-align: center;
        color: #64748b;
    }

    .action-dropdown-menu .dropdown-item:hover .action-item-icon {
        color: #2563eb;
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

    /* Reset & format Info text (e.g. "Showing 9 orders") */
    div.dataTables_wrapper div.dataTables_info,
    div.dataTables_wrapper .dataTables_info,
    .order-ledger-card .dataTables_info,
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

    @media (max-width: 768px) {
        .order-page-wrapper {
            padding: 4px 6px 30px 6px;
        }
        .order-ledger-card {
            padding: 16px;
            border-radius: 12px;
        }
        .order-title {
            font-size: 22px;
        }
        .order-summary-card {
            padding: 16px;
            border-radius: 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="order-page-wrapper">
        <!-- Breadcrumb Navigation -->
        <div class="order-breadcrumb">
            <a href="{{ route('nutritionPanel.dashboard') }}">Finance & Plans</a>
            <span class="crumb-sep">/</span>
            <span class="crumb-active">Orders</span>
        </div>

        <!-- Header Section -->
        <div class="order-header-section">
            <div>
                <h1 class="order-title">Orders</h1>
                <p class="order-subtitle">Monitor customer orders, payment progress and fulfilment status.</p>
            </div>
            <div>
                <button type="button" class="btn-header-filter" id="btn_toggle_header_filters">
                    <i class="fa fa-filter"></i> Filters
                </button>
            </div>
        </div>

        <!-- Summary Card ("Current results") -->
        <div class="order-summary-card">
            <div>
                <span class="badge-current-results">Current results</span>
            </div>
            <div class="order-kpi-grid">
                <!-- Total Orders -->
                <div class="order-kpi-item">
                    <div class="order-kpi-icon icon-blue">
                        <i class="fa fa-cube"></i>
                    </div>
                    <div class="order-kpi-data">
                        <span class="order-kpi-val" id="kpi_total_orders">{{ $summary['total_orders_formatted'] ?? 0 }}</span>
                        <span class="order-kpi-lbl">Total orders</span>
                    </div>
                </div>

                <!-- Net Amount -->
                <div class="order-kpi-item">
                    <div class="order-kpi-icon icon-cyan">
                        <i class="fa fa-file-text-o"></i>
                    </div>
                    <div class="order-kpi-data">
                        <span class="order-kpi-val" id="kpi_net_amount">{{ $summary['net_amount_formatted'] ?? 0 }}</span>
                        <span class="order-kpi-lbl">Net amount</span>
                    </div>
                </div>

                <!-- Successful Payments -->
                <div class="order-kpi-item">
                    <div class="order-kpi-icon icon-green">
                        <i class="fa fa-check"></i>
                    </div>
                    <div class="order-kpi-data">
                        <span class="order-kpi-val" id="kpi_successful_payments">{{ $summary['successful_payments_formatted'] ?? 0 }}</span>
                        <span class="order-kpi-lbl">Successful payments</span>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="order-kpi-item">
                    <div class="order-kpi-icon icon-amber">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <div class="order-kpi-data">
                        <span class="order-kpi-val" id="kpi_pending_payments">{{ $summary['pending_payments_formatted'] ?? 0 }}</span>
                        <span class="order-kpi-lbl">Pending payments</span>
                    </div>
                </div>

                <!-- Multi-segment Progress Bar & Legend -->
                <div class="order-progress-block">
                    <div class="order-segmented-bar">
                        <div class="bar-segment seg-delivered" id="kpi_seg_delivered" style="width: {{ $summary['delivered_percent'] ?? 0 }}%;"></div>
                        <div class="bar-segment seg-cancelled" id="kpi_seg_cancelled" style="width: {{ $summary['cancelled_percent'] ?? 0 }}%;"></div>
                        <div class="bar-segment seg-placed" id="kpi_seg_placed" style="width: {{ $summary['order_placed_percent'] ?? 0 }}%;"></div>
                    </div>
                    <div class="order-segmented-legend">
                        <span class="legend-item"><span class="legend-dot dot-delivered"></span> <span id="kpi_legend_delivered">{{ $summary['delivered_orders'] ?? 0 }}</span> Delivered</span>
                        <span class="legend-item"><span class="legend-dot dot-cancelled"></span> <span id="kpi_legend_cancelled">{{ $summary['cancelled_orders'] ?? 0 }}</span> Cancelled</span>
                        <span class="legend-item"><span class="legend-dot dot-placed"></span> <span id="kpi_legend_placed">{{ $summary['order_placed'] ?? 0 }}</span> Order placed</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ledger Table Card -->
        <div class="order-ledger-card">
            <div class="ledger-title-bar">
                <div class="title-accent-pill"></div>
                <h3 class="ledger-heading">Order management</h3>
            </div>
            <p class="ledger-subheading">Review payment results and move orders through fulfilment.</p>

            <!-- Filters Bar (Row 1) -->
            <div class="ledger-filters-grid">
                <!-- Search Input -->
                <div class="filter-search-wrap">
                    <i class="fa fa-search filter-icon-left"></i>
                    <input type="text" id="order_search" class="filter-input-styled" placeholder="Search order, user or mobile..." autocomplete="off" style="padding-left: 42px !important;">
                </div>

                <!-- Date Range -->
                <div class="filter-date-wrap">
                    <i class="fa fa-calendar filter-icon-left"></i>
                    <input type="text" id="order_date_range" class="filter-input-styled filter-date-input" placeholder="Date range" readonly autocomplete="off" style="padding-left: 42px !important; padding-right: 36px !important;">
                    <i class="fa fa-chevron-down filter-icon-right"></i>
                </div>

                <!-- Payment Status Filter -->
                <div>
                    <select id="order_payment_status" class="filter-select-styled">
                        <option value="">All payment statuses</option>
                        <option value="2">Success</option>
                        <option value="1">Pending</option>
                        <option value="3">Failed</option>
                    </select>
                </div>

                <!-- Order Status Filter -->
                <div>
                    <select id="order_status_filter" class="filter-select-styled">
                        <option value="">All order statuses</option>
                        <option value="1">Order Placed</option>
                        <option value="2">Ready to Ship</option>
                        <option value="4">Shipped</option>
                        <option value="5">In Transit</option>
                        <option value="6">Delivered</option>
                        <option value="7">Cancelled</option>
                        <option value="3">Return</option>
                        <option value="8">Refund</option>
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
                    <span class="table-count-text"><span id="visible_order_count">0</span> visible orders</span>
                    <select id="order_page_length" class="page-len-select">
                        <option value="10" selected>10 per page</option>
                        <option value="20">20 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>
                <div>
                    <button type="button" id="btn_clear_filters" class="btn-clear-pill">
                        <i class="fa fa-refresh"></i> Clear filters
                    </button>
                </div>
            </div>

            <input type="hidden" name="user_id" id="user_id" value="{{ $user_id ?? '' }}">

            <!-- Table Container -->
            <div class="table-container-modern table-responsive">
                <table id="dataTable" class="table" data-url="{{ route('nutritionPanel.orders.getOrders', ['user_id' => ($user_id ?? ''), 'user_type' => request('user_type', '')]) }}">
                    <thead>
                        <tr>
                            <th>Order Info</th>
                            <th>User Name</th>
                            <th>Mobile Number</th>
                            <th>Total Amount</th>
                            <th>Discount</th>
                            <th>Net Amount</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
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
<script src="{{ asset('admin-assets/js/orders/view.js') }}?v={{ file_exists(public_path('admin-assets/js/orders/view.js')) ? filemtime(public_path('admin-assets/js/orders/view.js')) : time() }}"></script>
@endpush
