@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Attendance Register | ' . __('language.page_main_title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
    :root {
        --fcc-primary: #3b46f1;
        --fcc-primary-hover: #2e39dc;
        --fcc-primary-light: #eff2fe;
        --fcc-dark: #0f172a;
        --fcc-muted: #64748b;
        --fcc-border: #edf2f7;
        --fcc-border-subtle: #f1f5f9;
        --fcc-card-bg: #ffffff;
        --fcc-page-bg: #f8fafc;
        --fcc-green: #10b981;
        --fcc-green-light: #dcfce7;
        --fcc-coral: #fb7185;
        --fcc-coral-light: #ffe4e6;
        --fcc-orange: #f97316;
        --fcc-orange-light: #ffedd5;
        --fcc-purple: #8b5cf6;
        --fcc-purple-light: #f3e8ff;
    }

    body {
        background-color: var(--fcc-page-bg) !important;
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif !important;
    }

    .fcc-attendance-wrapper {
        padding: 16px 20px 40px 20px;
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
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }
    .fcc-page-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--fcc-dark);
        letter-spacing: -0.025em;
        margin-bottom: 3px;
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
        padding: 8px 18px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .fcc-btn-export:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: var(--fcc-primary);
    }
    .fcc-btn-mark {
        background: var(--fcc-primary);
        border: 1.5px solid var(--fcc-primary);
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13.5px;
        padding: 8px 18px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(59, 70, 241, 0.25);
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .fcc-btn-mark:hover {
        background: var(--fcc-primary-hover);
        border-color: var(--fcc-primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(59, 70, 241, 0.35);
    }

    /* 3. Filter Bar */
    .fcc-filter-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 14px;
        padding: 10px 14px;
        margin-bottom: 22px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .fcc-search-box {
        position: relative;
        flex: 1;
        min-width: 220px;
    }
    .fcc-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
    }
    .fcc-search-input {
        width: 100%;
        padding: 8px 14px 8px 38px;
        font-size: 13.5px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #ffffff;
        color: var(--fcc-dark);
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .fcc-search-input:focus {
        border-color: var(--fcc-primary);
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.1);
    }
    .fcc-filter-select-wrap {
        position: relative;
        min-width: 160px;
    }
    .fcc-filter-select {
        width: 100%;
        padding: 8px 32px 8px 14px;
        font-size: 13.5px;
        font-weight: 500;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #ffffff;
        color: #334155;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        cursor: pointer;
        transition: border-color 0.15s ease;
    }
    .fcc-filter-select:focus {
        border-color: var(--fcc-primary);
    }
    .fcc-select-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 11px;
        pointer-events: none;
    }
    .fcc-btn-clear-filters {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 14px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .fcc-btn-clear-filters:hover {
        background: #edf2f7;
        color: var(--fcc-dark);
    }

    /* 4. Pulse & Attention Widgets */
    .fcc-pulse-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }
    @media (max-width: 991px) {
        .fcc-pulse-row {
            grid-template-columns: 1fr;
        }
    }

    /* Left Card: Attendance Pulse */
    .fcc-pulse-card {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 45%, #2563eb 100%) !important;
        border-radius: 18px !important;
        padding: 22px 26px !important;
        color: #ffffff !important;
        position: relative !important;
        box-shadow: 0 10px 25px -5px rgba(30, 58, 138, 0.35) !important;
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
        margin-bottom: 2px !important;
    }
    .fcc-pulse-bar-accent {
        width: 3.5px !important;
        height: 16px !important;
        background: #60a5fa !important;
        border-radius: 4px !important;
        display: inline-block !important;
    }
    .fcc-pulse-title,
    .fcc-pulse-title span,
    .fcc-pulse-card h2,
    .fcc-pulse-card h2 span,
    .fcc-pulse-card .fcc-pulse-title {
        font-size: 16px !important;
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
        color: #bfdbfe !important;
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
        stroke: rgba(255, 255, 255, 0.18) !important;
        stroke-width: 7 !important;
    }
    .fcc-donut-circle {
        fill: none !important;
        stroke: #60a5fa !important;
        stroke-width: 7 !important;
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
        font-size: 19px !important;
        font-weight: 800 !important;
        color: #ffffff !important;
        line-height: 1 !important;
        margin-bottom: 2px !important;
    }
    .fcc-donut-label {
        font-size: 8.5px !important;
        color: #bfdbfe !important;
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
        gap: 24px !important;
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
        border-radius: 50 !important;
        border-radius: 50% !important;
        background: #22c55e !important;
        display: inline-block !important;
        box-shadow: 0 0 8px rgba(34, 197, 94, 0.6) !important;
    }
    .fcc-pulse-dot-coral {
        width: 10px !important;
        height: 10px !important;
        border-radius: 50% !important;
        background: #f87171 !important;
        display: inline-block !important;
        box-shadow: 0 0 8px rgba(248, 113, 113, 0.6) !important;
    }
    .fcc-pulse-stat-num,
    #pulse-total-present,
    #pulse-total-absent {
        font-size: 18px !important;
        font-weight: 700 !important;
        color: #ffffff !important;
    }
    .fcc-pulse-stat-lbl {
        font-size: 12.5px !important;
        color: #cbd5e1 !important;
    }
    .fcc-segmented-bar-track {
        height: 10px !important;
        background: rgba(255, 255, 255, 0.18) !important;
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
        background: #f87171 !important;
        height: 100% !important;
        transition: width 0.6s ease !important;
    }
    .fcc-seg-labels {
        display: flex !important;
        justify-content: space-between !important;
        font-size: 11px !important;
        color: #bfdbfe !important;
        font-weight: 500 !important;
    }
    .fcc-seg-labels span {
        color: #bfdbfe !important;
    }

    /* Right Consistency */
    .fcc-pulse-consistency {
        text-align: right !important;
        min-width: 130px !important;
        border-left: 1px solid rgba(255, 255, 255, 0.15) !important;
        padding-left: 20px !important;
    }
    .fcc-trophy-icon {
        font-size: 20px !important;
        color: #fde047 !important;
        margin-bottom: 4px !important;
        display: inline-block !important;
    }
    .fcc-consistency-label {
        font-size: 11px !important;
        color: #bfdbfe !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        margin-bottom: 2px !important;
    }
    .fcc-consistency-days,
    #pulse-top-days {
        font-size: 20px !important;
        font-weight: 800 !important;
        color: #ffffff !important;
        line-height: 1.2 !important;
    }
    .fcc-consistency-names,
    #pulse-top-names {
        font-size: 12px !important;
        color: #93c5fd !important;
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
        padding: 20px 22px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
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
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #fee2e2;
        color: #ef4444;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        font-weight: 700;
    }
    .fcc-attention-title {
        font-size: 15.5px;
        font-weight: 700;
        color: var(--fcc-dark);
        margin: 0;
        line-height: 1.2;
    }
    .fcc-attention-subtitle {
        font-size: 12px;
        color: #94a3b8;
        margin: 2px 0 0 0;
    }
    .fcc-attention-list {
        display: flex;
        flex-direction: column;
        gap: 9px;
        margin-bottom: 14px;
        flex: 1;
    }
    .fcc-attention-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 10px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        transition: background 0.15s ease;
    }
    .fcc-attention-item:hover {
        background: #f1f5f9;
    }
    .fcc-attention-user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .fcc-attention-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
    }
    .fcc-attention-name {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
    }
    .fcc-attention-stat {
        font-size: 12px;
        font-weight: 500;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .fcc-btn-review-attention {
        width: 100%;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: var(--fcc-primary);
        font-weight: 600;
        font-size: 13px;
        padding: 8px 14px;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .fcc-btn-review-attention:hover {
        background: #dbeafe;
        color: #1d4ed8;
    }

    /* 5. Main Data Table Card */
    .fcc-table-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
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
    }
    .fcc-table-bar-accent {
        width: 3.5px;
        height: 18px;
        background: var(--fcc-primary);
        border-radius: 4px;
        display: inline-block;
    }
    .fcc-table-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--fcc-dark);
        margin: 0;
    }
    .fcc-table-subtitle {
        font-size: 13px;
        color: var(--fcc-muted);
        margin: 0;
    }
    .fcc-page-length-select {
        padding: 6px 28px 6px 12px;
        font-size: 13px;
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
        border-spacing: 0 4px !important;
    }
    .fcc-datatable thead th {
        background: #f8fafc !important;
        color: #64748b !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.03em !important;
        padding: 12px 14px !important;
        border-top: none !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    .fcc-datatable tbody tr {
        background: #ffffff;
        transition: all 0.15s ease;
    }
    .fcc-datatable tbody tr:hover {
        background: #f8fafc !important;
    }
    .fcc-datatable tbody td {
        padding: 13px 14px !important;
        font-size: 13.5px !important;
        color: #334155 !important;
        vertical-align: middle !important;
        border-top: 1px solid #f1f5f9 !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    /* Table Cell Specific Styles */
    .fcc-avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        flex-shrink: 0;
    }
    .fcc-member-name {
        font-size: 13.5px;
        font-weight: 600;
        color: #0f172a;
    }
    .fcc-mini-progress {
        width: 60px;
        height: 6px;
        background: #e2e8f0;
        border-radius: 6px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .fcc-mini-bar {
        background: var(--fcc-primary);
        height: 100%;
        border-radius: 6px;
    }
    .fcc-attendance-label {
        font-size: 13px;
        color: #334155;
        font-weight: 500;
    }
    .fcc-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
        vertical-align: middle;
    }
    .fcc-dot-green {
        background: #10b981;
    }
    .fcc-dot-coral {
        background: #fb7185;
    }
    .fcc-rate-text {
        font-weight: 600;
        color: #1e293b;
    }
    
    /* Badges */
    .fcc-badge {
        display: inline-block;
        padding: 4px 10px;
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
        border: 1px solid #bfdbfe;
        color: var(--fcc-primary);
        font-weight: 600;
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .fcc-btn-view-attendance:hover {
        background: #eff6ff;
        border-color: var(--fcc-primary);
        color: #1d4ed8;
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
        gap: 4px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 5px 11px !important;
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        background: #ffffff !important;
        color: #475569 !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        cursor: pointer;
        margin: 0 2px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--fcc-primary) !important;
        border-color: var(--fcc-primary) !important;
        color: #ffffff !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
        color: var(--fcc-dark) !important;
    }
    .dataTables_wrapper .dataTables_info {
        padding-top: 16px;
        font-size: 13px;
        color: #64748b;
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
            <input type="text" id="filterSearch" class="fcc-search-input" placeholder="Search members...">
        </div>

        <!-- Month & Year Selector -->
        <div class="fcc-filter-select-wrap">
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
                            📅 {{ $name }} {{ $y }}
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

        <!-- Clear / Reset Filter -->
        <button type="button" id="btnClearFilters" class="fcc-btn-clear-filters" title="Reset all filters">
            <i class="fa fa-filter"></i> Clear filters
        </button>
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
                <p class="fcc-pulse-subtitle" style="color: #bfdbfe !important;">Current view &middot; <span id="pulse-member-count" style="color: #ffffff !important; font-weight: 700 !important;">{{ $stats['total_members'] ?? 0 }}</span> members</p>
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
                                    <i class="fa fa-chevron-right ms-1"></i>
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
                        <th>Member</th>
                        <th>Attendance</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th>Rate</th>
                        <th>Follow-up</th>
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
