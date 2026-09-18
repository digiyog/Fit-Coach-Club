@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Attendance Register | ' . __('language.page_main_title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
    :root {
        --fcc-blue: #2b52f5;
        --fcc-blue-hover: #1e40d8;
        --fcc-text-dark: #1b2559;
        --fcc-text-body: #2b3674;
        --fcc-text-muted: #a3aed0;
        --fcc-border: #edf2f7;
        --fcc-card-bg: #ffffff;
        --fcc-page-bg: #f4f7fe;
    }

    body {
        background-color: var(--fcc-page-bg) !important;
        font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif !important;
        -webkit-font-smoothing: antialiased;
    }

    .fcc-attendance-wrapper {
        padding: 16px 20px 40px 20px;
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif !important;
    }

    /* 1. Breadcrumbs */
    .fcc-breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        color: var(--fcc-text-muted);
        margin-bottom: 4px;
        font-weight: 500;
    }
    .fcc-breadcrumb-nav a {
        color: var(--fcc-text-muted);
        text-decoration: none;
        transition: color 0.15s ease;
    }
    .fcc-breadcrumb-nav a:hover {
        color: var(--fcc-blue);
    }
    .fcc-breadcrumb-sep {
        color: #cbd5e1;
        font-size: 11px;
    }
    .fcc-breadcrumb-active {
        color: var(--fcc-text-muted);
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
        color: var(--fcc-text-dark);
        letter-spacing: -0.02em;
        margin-bottom: 2px;
        line-height: 1.2;
    }
    .fcc-page-subtitle {
        font-size: 13.5px;
        color: var(--fcc-text-muted);
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
        border: 1.5px solid #d4dcfa;
        color: var(--fcc-text-body);
        font-weight: 600;
        font-size: 13.5px;
        padding: 8px 18px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .fcc-btn-export:hover {
        background: #f8fafc;
        border-color: var(--fcc-blue);
        color: var(--fcc-blue);
    }
    .fcc-btn-mark {
        background: var(--fcc-blue);
        border: none;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13.5px;
        padding: 8px 18px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(43, 82, 245, 0.28);
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .fcc-btn-mark:hover {
        background: var(--fcc-blue-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(43, 82, 245, 0.38);
    }

    /* 3. Filter Bar */
    .fcc-filter-card {
        background: #ffffff !important;
        border: 1px solid var(--fcc-border) !important;
        border-radius: 14px !important;
        padding: 8px 12px !important;
        margin-bottom: 20px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02) !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        flex-wrap: wrap !important;
    }
    .fcc-search-box {
        position: relative !important;
        flex: 1.3 !important;
        min-width: 220px !important;
    }
    .fcc-search-icon {
        position: absolute !important;
        left: 14px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #a3aed0 !important;
        font-size: 13px !important;
        pointer-events: none !important;
        z-index: 3 !important;
    }
    .fcc-search-input {
        width: 100% !important;
        padding-left: 38px !important;
        padding-right: 14px !important;
        padding-top: 7px !important;
        padding-bottom: 7px !important;
        height: 38px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        background: #ffffff !important;
        color: var(--fcc-text-dark) !important;
        outline: none !important;
        box-sizing: border-box !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
    }
    .fcc-search-input::placeholder {
        color: #a3aed0 !important;
    }
    .fcc-search-input:focus {
        border-color: var(--fcc-blue) !important;
        box-shadow: 0 0 0 3px rgba(43, 82, 245, 0.08) !important;
    }
    .fcc-filter-select-wrap {
        position: relative !important;
        min-width: 160px !important;
        flex: 1 !important;
    }
    .fcc-filter-select-wrap.has-icon .fcc-filter-select {
        padding-left: 36px !important;
    }
    .fcc-prefix-icon {
        position: absolute !important;
        left: 13px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #a3aed0 !important;
        font-size: 13px !important;
        pointer-events: none !important;
        z-index: 3 !important;
    }
    .fcc-filter-select {
        width: 100% !important;
        padding-left: 14px !important;
        padding-right: 30px !important;
        padding-top: 7px !important;
        padding-bottom: 7px !important;
        height: 38px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        background: #ffffff !important;
        color: var(--fcc-text-body) !important;
        outline: none !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        cursor: pointer !important;
        box-sizing: border-box !important;
        transition: border-color 0.15s ease !important;
    }
    .fcc-filter-select:focus {
        border-color: var(--fcc-blue) !important;
    }
    .fcc-select-icon {
        position: absolute !important;
        right: 12px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #a3aed0 !important;
        font-size: 11px !important;
        pointer-events: none !important;
        z-index: 3 !important;
    }
    .fcc-btn-clear-filters {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: var(--fcc-text-body);
        font-weight: 500;
        font-size: 13px;
        padding: 7px 14px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .fcc-btn-clear-filters:hover {
        background: #f8fafc;
        border-color: var(--fcc-blue);
        color: var(--fcc-blue);
    }

    /* 4. Pulse & Attention Widgets */
    .fcc-pulse-row {
        display: grid;
        grid-template-columns: 2.1fr 1fr;
        gap: 20px;
        margin-bottom: 22px;
    }
    @media (max-width: 991px) {
        .fcc-pulse-row {
            grid-template-columns: 1fr;
        }
    }

    /* Left Card: Attendance Pulse */
    .fcc-pulse-card {
        background: #2b52f5 !important;
        background: linear-gradient(135deg, #2b52f5 0%, #2f4eed 100%) !important;
        border-radius: 18px !important;
        padding: 22px 28px !important;
        color: #ffffff !important;
        position: relative !important;
        box-shadow: 0 10px 24px -4px rgba(43, 82, 245, 0.32) !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
    }
    .fcc-pulse-header {
        margin-bottom: 16px !important;
    }
    .fcc-pulse-title-wrap {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin-bottom: 3px !important;
    }
    .fcc-pulse-bar-accent {
        width: 3.5px !important;
        height: 16px !important;
        background: #85a9ff !important;
        border-radius: 3px !important;
        display: inline-block !important;
    }
    .fcc-pulse-title,
    .fcc-pulse-title span,
    .fcc-pulse-card h2,
    .fcc-pulse-card h2 span,
    .fcc-pulse-card .fcc-pulse-title {
        font-size: 16.5px !important;
        font-weight: 700 !important;
        letter-spacing: -0.01em !important;
        margin: 0 !important;
        color: #ffffff !important;
    }
    .fcc-pulse-subtitle,
    .fcc-pulse-subtitle span,
    .fcc-pulse-card p,
    .fcc-pulse-card p span,
    .fcc-pulse-card .fcc-pulse-subtitle {
        font-size: 12.5px !important;
        color: #d1e0ff !important;
        margin: 0 !important;
        padding-left: 11.5px !important;
    }
    .fcc-pulse-content {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 24px !important;
        flex-wrap: wrap !important;
    }
    
    /* Gauge / Donut */
    .fcc-pulse-donut-wrap {
        display: flex !important;
        align-items: center !important;
        gap: 16px !important;
    }
    .fcc-donut-container {
        position: relative !important;
        width: 90px !important;
        height: 90px !important;
    }
    .fcc-donut-svg {
        transform: rotate(-90deg) !important;
        width: 90px !important;
        height: 90px !important;
    }
    .fcc-donut-bg {
        fill: none !important;
        stroke: rgba(255, 255, 255, 0.2) !important;
        stroke-width: 6 !important;
    }
    .fcc-donut-circle {
        fill: none !important;
        stroke: #ffffff !important;
        stroke-width: 6 !important;
        stroke-linecap: round !important;
        stroke-dasharray: 251.2 !important;
        stroke-dashoffset: 200 !important;
        transition: stroke-dashoffset 0.8s ease !important;
    }
    .fcc-donut-text-wrap {
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        text-align: center !important;
        width: 100% !important;
        pointer-events: none !important;
    }
    .fcc-donut-pct,
    #pulse-avg-rate {
        font-size: 22px !important;
        font-weight: 800 !important;
        color: #ffffff !important;
        line-height: 1 !important;
        margin-bottom: 2px !important;
    }
    .fcc-donut-label {
        font-size: 10px !important;
        color: #d1e0ff !important;
        text-transform: lowercase !important;
        line-height: 1.1 !important;
        display: block !important;
    }

    /* Middle Stats & Segmented Bar */
    .fcc-pulse-middle {
        flex: 1 !important;
        min-width: 200px !important;
    }
    .fcc-pulse-stat-indicators {
        display: flex !important;
        align-items: center !important;
        gap: 28px !important;
        margin-bottom: 12px !important;
    }
    .fcc-pulse-stat-item {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
    }
    .fcc-pulse-dot-green {
        width: 10px !important;
        height: 10px !important;
        border-radius: 50% !important;
        background: #22c55e !important;
        display: inline-block !important;
    }
    .fcc-pulse-dot-coral {
        width: 10px !important;
        height: 10px !important;
        border-radius: 50% !important;
        background: #ff7875 !important;
        display: inline-block !important;
    }
    .fcc-pulse-stat-num,
    #pulse-total-present,
    #pulse-total-absent {
        font-size: 19px !important;
        font-weight: 700 !important;
        color: #ffffff !important;
        margin-right: 4px !important;
    }
    .fcc-pulse-stat-lbl {
        font-size: 13px !important;
        color: #d1e0ff !important;
    }
    .fcc-segmented-bar-track {
        height: 11px !important;
        background: rgba(255, 255, 255, 0.2) !important;
        border-radius: 10px !important;
        display: flex !important;
        overflow: hidden !important;
        margin-bottom: 6px !important;
    }
    .fcc-seg-present {
        background: #22c55e !important;
        height: 100% !important;
        transition: width 0.6s ease !important;
    }
    .fcc-seg-absent {
        background: #ff7875 !important;
        height: 100% !important;
        transition: width 0.6s ease !important;
    }
    .fcc-seg-labels {
        display: flex !important;
        justify-content: space-between !important;
        font-size: 11.5px !important;
        color: #d1e0ff !important;
        font-weight: 500 !important;
    }
    .fcc-seg-labels span {
        color: #d1e0ff !important;
    }

    /* Right Consistency */
    .fcc-pulse-consistency {
        text-align: right !important;
        min-width: 130px !important;
        border-left: 1px solid rgba(255, 255, 255, 0.18) !important;
        padding-left: 24px !important;
    }
    .fcc-trophy-icon {
        font-size: 22px !important;
        color: #ffffff !important;
        margin-bottom: 4px !important;
        display: inline-block !important;
    }
    .fcc-consistency-label {
        font-size: 11.5px !important;
        color: #d1e0ff !important;
        letter-spacing: 0.02em !important;
        margin-bottom: 2px !important;
    }
    .fcc-consistency-days,
    #pulse-top-days {
        font-size: 22px !important;
        font-weight: 800 !important;
        color: #ffffff !important;
        line-height: 1.2 !important;
    }
    .fcc-consistency-names,
    #pulse-top-names {
        font-size: 12px !important;
        color: #d1e0ff !important;
        margin-top: 2px !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        max-width: 160px !important;
    }

    /* Right Card: Needs Attention */
    .fcc-attention-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 18px;
        padding: 18px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .fcc-attention-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    .fcc-attention-icon-wrap {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #ff5252;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        font-weight: 800;
        flex-shrink: 0;
    }
    .fcc-attention-title {
        font-size: 15.5px;
        font-weight: 700;
        color: var(--fcc-text-dark);
        margin: 0;
        line-height: 1.2;
    }
    .fcc-attention-subtitle {
        font-size: 12px;
        color: var(--fcc-text-muted);
        margin: 2px 0 0 0;
    }
    .fcc-attention-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 12px;
        flex: 1;
    }
    .fcc-attention-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 4px 0;
        transition: background 0.15s ease;
    }
    .fcc-attention-user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .fcc-attention-avatar {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        background: #ede9fe;
        color: #7c3aed;
    }
    .fcc-attention-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--fcc-text-body);
    }
    .fcc-attention-stat {
        font-size: 12px;
        font-weight: 500;
        color: var(--fcc-text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .fcc-btn-review-attention {
        width: 100%;
        background: #ffffff;
        border: 1.5px solid var(--fcc-blue);
        color: var(--fcc-blue);
        font-weight: 600;
        font-size: 13px;
        padding: 7px 14px;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.15s ease;
        display: block;
    }
    .fcc-btn-review-attention:hover {
        background: #f4f7fe;
    }

    /* 5. Main Data Table Card */
    .fcc-table-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 18px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        padding: 22px 24px;
    }
    .fcc-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }
    .fcc-table-title-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2px;
    }
    .fcc-table-bar-accent {
        width: 3.5px;
        height: 16px;
        background: var(--fcc-blue);
        border-radius: 3px;
        display: inline-block;
    }
    .fcc-table-title {
        font-size: 16.5px;
        font-weight: 700;
        color: var(--fcc-text-dark);
        margin: 0;
    }
    .fcc-table-subtitle {
        font-size: 12.5px;
        color: var(--fcc-text-muted);
        margin: 0;
        padding-left: 11.5px;
    }
    .fcc-page-length-select {
        padding: 5px 26px 5px 12px;
        font-size: 12.5px;
        font-weight: 500;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #ffffff;
        color: #475569;
        outline: none;
        appearance: none;
        cursor: pointer;
    }

    /* Table Styling */
    .fcc-datatable {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        margin-top: 8px !important;
    }
    .fcc-datatable thead th {
        background: #ffffff !important;
        color: var(--fcc-text-muted) !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        letter-spacing: 0.01em !important;
        padding: 10px 14px !important;
        border-top: none !important;
        border-bottom: 1.5px solid var(--fcc-border) !important;
    }
    .fcc-datatable tbody tr {
        background: #ffffff;
        transition: all 0.15s ease;
    }
    .fcc-datatable tbody tr:hover {
        background: #f9fafb !important;
    }
    .fcc-datatable tbody td {
        padding: 11px 14px !important;
        font-size: 13px !important;
        color: var(--fcc-text-body) !important;
        vertical-align: middle !important;
        border-top: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    /* Table Cell Specific Styles */
    .fcc-avatar-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }
    .fcc-member-name {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--fcc-text-body);
    }
    .fcc-mini-progress {
        width: 55px;
        height: 5px;
        background: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .fcc-mini-bar {
        background: var(--fcc-blue);
        height: 100%;
        border-radius: 10px;
    }
    .fcc-attendance-label {
        font-size: 13px;
        color: var(--fcc-text-body);
        font-weight: 500;
    }
    .fcc-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
        vertical-align: middle;
    }
    .fcc-dot-green {
        background: #22c55e;
    }
    .fcc-dot-coral {
        background: #ff7875;
    }
    .fcc-rate-text {
        font-weight: 600;
        color: var(--fcc-text-body);
        font-size: 13px;
    }
    
    /* Badges */
    .fcc-badge {
        display: inline-block;
        padding: 3px 12px;
        font-size: 11.5px;
        font-weight: 600;
        border-radius: 20px;
        text-align: center;
    }
    .fcc-badge-danger {
        background: #fee2e2;
        color: #dc2626;
    }
    .fcc-badge-warning {
        background: #ffedd5;
        color: #ea580c;
    }
    .fcc-badge-purple {
        background: #f3e8ff;
        color: #9333ea;
    }
    .fcc-badge-success {
        background: #dcfce7;
        color: #16a34a;
    }

    /* Action Button */
    .fcc-btn-view-attendance {
        background: #ffffff;
        border: 1.5px solid var(--fcc-blue);
        color: var(--fcc-blue);
        font-weight: 600;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .fcc-btn-view-attendance:hover {
        background: #f4f7fe;
        color: var(--fcc-blue-hover);
    }
    .fcc-chevron-icon {
        font-size: 9px;
    }

    /* DataTables Overrides */
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 16px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 6px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 4px 10px !important;
        border-radius: 8px !important;
        border: none !important;
        background: transparent !important;
        color: var(--fcc-text-muted) !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        cursor: pointer;
        margin: 0 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--fcc-blue) !important;
        color: #ffffff !important;
        font-weight: 700 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #f1f5f9 !important;
        color: var(--fcc-text-dark) !important;
    }
    .dataTables_wrapper .dataTables_info {
        padding-top: 16px;
        font-size: 13px;
        color: var(--fcc-text-muted);
        font-weight: 500;
    }
    .dataTables_filter, .dataTables_length {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="fcc-attendance-wrapper">
    <!-- 1. Breadcrumbs -->
    <div class="fcc-breadcrumb-nav">
        <a href="{{ route('nutritionPanel.dashboard') }}">Offline system</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <span class="fcc-breadcrumb-active">Attendance register</span>
    </div>

    <!-- 2. Header & Action Buttons -->
    <div class="fcc-page-header">
        <div>
            <h1 class="fcc-page-title">Attendance register</h1>
            <p class="fcc-page-subtitle"><span class="pulse-dynamic-month">{{ $stats['month_name'] ?? date('F') }}</span> check-in performance and member follow-up</p>
        </div>
        <div class="fcc-header-btns">
            <button type="button" id="btnExportReport" class="fcc-btn-export">
                <i class="fa fa-download"></i> Export report
            </button>
            <a href="{{ route('nutritionPanel.manual-attendances.manual-attendance') }}" class="fcc-btn-mark">
                <i class="fa fa-plus"></i> Mark attendance
            </a>
        </div>
    </div>

    <!-- 3. Filter Bar -->
    <div class="fcc-filter-card">
        <!-- Search -->
        <div class="fcc-search-box">
            <i class="fa fa-search fcc-search-icon"></i>
            <input type="text" id="filterSearch" class="fcc-search-input" placeholder="Search members..." style="padding-left: 38px !important;">
        </div>

        <!-- Month & Year Selector -->
        <div class="fcc-filter-select-wrap has-icon">
            <i class="fa fa-calendar-o fcc-prefix-icon"></i>
            <select id="filterMonthYear" class="fcc-filter-select">
                @php
                    $currentYear = date('Y');
                    $months = [
                        '01' => 'January', '02' => 'February', '03' => 'March',
                        '04' => 'April',   '05' => 'May',      '06' => 'June',
                        '07' => 'July',    '08' => 'August',   '09' => 'September',
                        '10' => 'October', '11' => 'November', '12' => 'December'
                    ];
                @endphp
                @for ($y = $currentYear; $y >= $currentYear - 3; $y--)
                    @foreach ($months as $num => $name)
                        <option value="{{ $num }}-{{ $y }}" {{ ($selectedMonth == $num && $selectedYear == $y) ? 'selected' : '' }}>
                            {{ $name }} {{ $y }}
                        </option>
                    @endforeach
                @endfor
            </select>
            <i class="fa fa-chevron-down fcc-select-icon"></i>
        </div>

        <!-- Coaches Filter -->
        <div class="fcc-filter-select-wrap">
            <select id="filterCoach" class="fcc-filter-select">
                <option value="">All coaches</option>
                @if(isset($coachesList) && count($coachesList) > 0)
                    @foreach($coachesList as $coach)
                        <option value="{{ $coach }}">{{ $coach }}</option>
                    @endforeach
                @endif
            </select>
            <i class="fa fa-chevron-down fcc-select-icon"></i>
        </div>

        <!-- Attendance Status Filter -->
        <div class="fcc-filter-select-wrap">
            <select id="filterAttendanceStatus" class="fcc-filter-select">
                <option value="">All attendance</option>
                <option value="no_checkins">Needs attention (0 check-ins)</option>
                <option value="low">Low (1 - 20%)</option>
                <option value="building">Building (21 - 50%)</option>
                <option value="on_track">On track (> 50%)</option>
            </select>
            <i class="fa fa-chevron-down fcc-select-icon"></i>
        </div>

        <!-- More Filters / Clear -->
        <div class="fcc-filter-select-wrap has-icon" style="min-width: 140px;">
            <i class="fa fa-filter fcc-prefix-icon"></i>
            <select id="filterMoreActions" class="fcc-filter-select">
                <option value="">More filters</option>
                <option value="clear">Clear filters</option>
            </select>
            <i class="fa fa-chevron-down fcc-select-icon"></i>
        </div>
    </div>

    <!-- 4. Pulse Summary & Attention Row -->
    <div class="fcc-pulse-row">
        <!-- Left: Pulse Card -->
        <div class="fcc-pulse-card">
            <div class="fcc-pulse-header">
                <div class="fcc-pulse-title-wrap">
                    <span class="fcc-pulse-bar-accent"></span>
                    <h2 class="fcc-pulse-title" style="color: #ffffff !important; font-weight: 700 !important;"><span class="pulse-dynamic-month" style="color: #ffffff !important;">{{ $stats['month_name'] ?? date('F') }}</span> attendance pulse</h2>
                </div>
                <p class="fcc-pulse-subtitle" style="color: #d1e0ff !important;">Current view &middot; <span id="pulse-member-count" style="color: #ffffff !important; font-weight: 700 !important;">{{ $stats['total_members'] ?? 0 }}</span> members</p>
            </div>

            <div class="fcc-pulse-content">
                <!-- Gauge -->
                <div class="fcc-pulse-donut-wrap">
                    <div class="fcc-donut-container">
                        <svg class="fcc-donut-svg" viewBox="0 0 100 100">
                            <circle class="fcc-donut-bg" cx="50" cy="50" r="40"></circle>
                            @php
                                $circumference = 2 * pi() * 40;
                                $rate = $stats['avg_rate'] ?? 0;
                                $dashoffset = $circumference - ($rate / 100) * $circumference;
                            @endphp
                            <circle id="pulse-donut-gauge" class="fcc-donut-circle" cx="50" cy="50" r="40" style="stroke-dasharray: {{ $circumference }}; stroke-dashoffset: {{ $dashoffset }};"></circle>
                        </svg>
                        <div class="fcc-donut-text-wrap">
                            <div id="pulse-avg-rate" class="fcc-donut-pct">{{ $stats['avg_rate'] ?? 0 }}%</div>
                            <span class="fcc-donut-label">average<br>attendance</span>
                        </div>
                    </div>
                </div>

                <!-- Middle: Stats & Progress Bar -->
                <div class="fcc-pulse-middle">
                    <div class="fcc-pulse-stat-indicators">
                        <div class="fcc-pulse-stat-item">
                            <span class="fcc-pulse-dot-green"></span>
                            <div>
                                <span id="pulse-total-present" class="fcc-pulse-stat-num">{{ $stats['total_present'] ?? 0 }}</span>
                                <span class="fcc-pulse-stat-lbl">Present</span>
                            </div>
                        </div>
                        <div class="fcc-pulse-stat-item">
                            <span class="fcc-pulse-dot-coral"></span>
                            <div>
                                <span id="pulse-total-absent" class="fcc-pulse-stat-num">{{ $stats['total_absent'] ?? 0 }}</span>
                                <span class="fcc-pulse-stat-lbl">Absent</span>
                            </div>
                        </div>
                    </div>

                    <!-- Dual Segmented Bar -->
                    <div class="fcc-segmented-bar-track">
                        <div id="pulse-bar-present" class="fcc-seg-present" style="width: {{ $stats['present_pct'] ?? 0 }}%;"></div>
                        <div id="pulse-bar-absent" class="fcc-seg-absent" style="width: {{ $stats['absent_pct'] ?? 100 }}%;"></div>
                    </div>
                    <div class="fcc-seg-labels">
                        <span><span id="pulse-present-pct">{{ $stats['present_pct'] ?? 0 }}%</span> present</span>
                        <span><span id="pulse-absent-pct">{{ $stats['absent_pct'] ?? 100 }}%</span> absent</span>
                    </div>
                </div>

                <!-- Right: Top Consistency -->
                <div class="fcc-pulse-consistency">
                    <i class="fa fa-trophy fcc-trophy-icon"></i>
                    <div class="fcc-consistency-label">Top consistency</div>
                    <div id="pulse-top-days" class="fcc-consistency-days">{{ $stats['top_consistency_days'] ?? 0 }} days</div>
                    <div id="pulse-top-names" class="fcc-consistency-names" title="{{ $stats['top_consistency_names'] ?? 'None' }}">{{ $stats['top_consistency_names'] ?? 'None' }}</div>
                </div>
            </div>
        </div>

        <!-- Right: Needs Attention Card -->
        <div class="fcc-attention-card">
            <div>
                <div class="fcc-attention-header">
                    <div class="fcc-attention-icon-wrap">!</div>
                    <div>
                        <h3 class="fcc-attention-title">Needs attention</h3>
                        <p class="fcc-attention-subtitle">No check-ins this month</p>
                    </div>
                </div>

                <!-- Members List with 0 check-ins -->
                <div id="needs-attention-list" class="fcc-attention-list">
                    @if(isset($stats['needs_attention_list']) && count($stats['needs_attention_list']) > 0)
                        @foreach($stats['needs_attention_list'] as $item)
                            <div class="fcc-attention-item">
                                <div class="fcc-attention-user-info">
                                    <div class="fcc-attention-avatar" style="background-color: {{ $item['bg_color'] }}; color: {{ $item['text_color'] }};">
                                        {{ $item['initial'] }}
                                    </div>
                                    <span class="fcc-attention-name">{{ $item['name'] }}</span>
                                </div>
                                <div class="fcc-attention-stat">
                                    <span>0 / {{ $item['total_days'] }} days</span>
                                    <i class="fa fa-chevron-right ms-1" style="font-size: 10px;"></i>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-muted text-center py-3" style="font-size: 13px;">
                            <i class="fa fa-check-circle text-success me-1"></i> All members have active check-ins!
                        </div>
                    @endif
                </div>
            </div>

            <!-- Review Button -->
            <button type="button" id="btnReviewNeedsAttention" class="fcc-btn-review-attention">
                Review <span id="needs-attention-count-btn">{{ $stats['needs_attention_count'] ?? 0 }}</span> members &gt;
            </button>
        </div>
    </div>

    <!-- 5. Main Member Register Table Card -->
    <div class="fcc-table-card">
        <div class="fcc-table-header">
            <div>
                <div class="fcc-table-title-wrap">
                    <span class="fcc-table-bar-accent"></span>
                    <h2 class="fcc-table-title">Member register</h2>
                </div>
                <p class="fcc-table-subtitle"><span class="pulse-dynamic-month">{{ $stats['month_name'] ?? date('F') }}</span> totals and follow-up priority</p>
            </div>
            <div class="fcc-table-controls d-flex align-items-center gap-2">
                <div class="position-relative">
                    <select id="filterPageLength" class="fcc-page-length-select">
                        <option value="10">10 per page</option>
                        <option value="20" selected>20 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                    <i class="fa fa-chevron-down fcc-select-icon"></i>
                </div>
            </div>
        </div>

        <div class="table-responsive data-table-container">
            <table id="attendanceDataTable" class="table fcc-datatable" data-url="{{ route('nutritionPanel.attendance-register.getAttendanceRegister') }}">
                <thead>
                    <tr>
                        <th>Member <i class="fa fa-arrow-down ms-1" style="font-size: 10px; color: #a3aed0;"></i></th>
                        <th>Attendance <i class="fa fa-sort ms-1" style="font-size: 10px; color: #cbd5e1;"></i></th>
                        <th>Present <i class="fa fa-sort ms-1" style="font-size: 10px; color: #cbd5e1;"></i></th>
                        <th>Absent <i class="fa fa-sort ms-1" style="font-size: 10px; color: #cbd5e1;"></i></th>
                        <th>Rate <i class="fa fa-sort ms-1" style="font-size: 10px; color: #cbd5e1;"></i></th>
                        <th>Follow-up <i class="fa fa-sort ms-1" style="font-size: 10px; color: #cbd5e1;"></i></th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
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
<script src="{{ asset('admin-assets/js/attendence-register/view.js') }}"></script>
@endpush
