@extends('nutrition-panel.layouts.main-layout')

@section('page-title', ' '.__('language.dashboard_page_title').' | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/plugins/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin-assets/css/components/tabs-accordian/custom-tabs.css') }}" rel="stylesheet" type="text/css" />

<style>
    :root {
        --fcc-primary: #3b46f1;
        --fcc-primary-gradient: linear-gradient(135deg, #3246d3 0%, #4361ee 100%);
        --fcc-pulse-gradient: linear-gradient(135deg, #1e266d 0%, #3042d6 50%, #7025e6 100%);
        --fcc-dark: #0f172a;
        --fcc-muted: #64748b;
        --fcc-border: #edf2f7;
        --fcc-bg: #f7f9fd;
        --fcc-card-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
    }

    body {
        background-color: var(--fcc-bg) !important;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        -webkit-font-smoothing: antialiased;
    }

    .fcc-main-container {
        padding: 4px 6px 36px 6px;
        max-width: 1560px;
        margin: 0 auto;
    }

    /* 1. Header Bar */
    .fcc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }

    .fcc-greeting-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--fcc-dark);
        letter-spacing: -0.025em;
        margin-bottom: 2px;
        line-height: 1.2;
    }

    .fcc-greeting-subtitle {
        font-size: 13.5px;
        color: var(--fcc-muted);
        font-weight: 500;
        margin: 0;
    }

    .fcc-header-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .fcc-search-input-box {
        position: relative;
        min-width: 210px;
    }

    .fcc-search-input-box input {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 8px 14px 8px 36px;
        font-size: 13px;
        color: var(--fcc-dark);
        width: 100%;
        outline: none;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .fcc-search-input-box input:focus {
        border-color: var(--fcc-primary);
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.14);
    }

    .fcc-search-input-box .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
    }

    .fcc-icon-btn {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        position: relative;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .fcc-icon-btn:hover {
        background: #f8fafc;
        color: var(--fcc-primary);
        border-color: #cbd5e1;
    }

    .fcc-icon-btn .badge-dot {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #ef4444;
        color: #ffffff;
        font-size: 10px;
        font-weight: 800;
        width: 17px;
        height: 17px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ffffff;
    }

    .fcc-plan-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 7px 14px;
        border-radius: 11px;
        font-size: 12.5px;
        font-weight: 600;
        color: #334155;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .fcc-btn-add {
        background: var(--fcc-primary-gradient);
        color: #ffffff !important;
        border: none;
        border-radius: 11px;
        padding: 8px 18px;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(59, 70, 241, 0.3);
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .fcc-btn-add:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(59, 70, 241, 0.4);
    }

    /* 2. Tabs Bar */
    .fcc-tabs-bar {
        display: flex;
        align-items: center;
        gap: 32px;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 22px;
        padding-bottom: 2px;
    }

    .fcc-tab-btn {
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        padding: 6px 2px 12px 2px;
        position: relative;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .fcc-tab-btn:hover {
        color: var(--fcc-primary);
    }

    .fcc-tab-btn.active {
        color: var(--fcc-primary);
        font-weight: 800;
    }

    .fcc-tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--fcc-primary);
        border-radius: 3px 3px 0 0;
    }

    /* 3. Section 1: Hero Grid (Club pulse + Today) */
    .fcc-hero-grid {
        display: grid;
        grid-template-columns: 1.52fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    @media (max-width: 991px) {
        .fcc-hero-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Club pulse */
    .fcc-pulse-card {
        background: var(--fcc-pulse-gradient);
        border-radius: 20px;
        padding: 24px 26px 18px 26px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 28px -5px rgba(48, 66, 214, 0.38);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .fcc-pulse-top {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 16.5px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 14px;
    }

    .fcc-pulse-stats-row {
        display: flex;
        align-items: baseline;
        gap: 26px;
        flex-wrap: wrap;
        margin-bottom: 4px;
    }

    .fcc-pulse-big-num {
        font-size: 46px;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -0.03em;
    }

    .fcc-pulse-big-lbl {
        font-size: 12.5px;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
        margin-top: 4px;
    }

    .fcc-pulse-pills {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .fcc-pulse-pill-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: rgba(255, 255, 255, 0.95);
        font-size: 13.5px;
        font-weight: 600;
        border-left: 1px solid rgba(255, 255, 255, 0.22);
        padding-left: 14px;
    }

    .fcc-pulse-pill-item:first-child {
        border-left: none;
        padding-left: 0;
    }

    .fcc-pulse-chart-box {
        position: relative;
        margin: 2px -10px 0 -10px;
    }

    .fcc-pulse-footer {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.95);
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.14);
    }

    .fcc-dot-live {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 8px #10b981;
    }

    /* Today card */
    .fcc-today-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 20px;
        padding: 22px 24px;
        box-shadow: var(--fcc-card-shadow);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .fcc-today-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--fcc-dark);
        margin-bottom: 14px;
    }

    .fcc-today-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 18px;
    }

    .fcc-today-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 4px 0;
    }

    .fcc-today-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fcc-today-icon {
        width: 36px;
        height: 36px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .icon-orange { background: #fff7ed; color: #ea580c; }
    .icon-coral { background: #fef2f2; color: #ef4444; }
    .icon-rose { background: #fff1f2; color: #e11d48; }
    .icon-purple { background: #faf5ff; color: #9333ea; }

    .fcc-today-text {
        font-size: 13.5px;
        color: #334155;
    }

    .fcc-today-text strong {
        font-size: 15.5px;
        font-weight: 800;
        color: var(--fcc-dark);
        margin-right: 4px;
    }

    .fcc-urgent-badge {
        color: #ef4444;
        font-weight: 700;
        font-size: 12px;
    }

    .fcc-action-chevron {
        font-size: 12.5px;
        font-weight: 700;
        color: #64748b;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s ease;
    }

    .fcc-action-chevron:hover {
        color: var(--fcc-primary);
        transform: translateX(2px);
    }

    .fcc-btn-scan-qr {
        background: var(--fcc-primary-gradient);
        color: #ffffff !important;
        border: none;
        border-radius: 13px;
        padding: 12px 18px;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        box-shadow: 0 4px 14px rgba(59, 70, 241, 0.28);
        transition: all 0.2s ease;
        text-decoration: none;
        cursor: pointer;
    }

    .fcc-btn-scan-qr:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(59, 70, 241, 0.38);
    }

    /* 4. Section 2: 4 Metric Cards */
    .fcc-metrics-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 22px;
    }

    @media (max-width: 1200px) {
        .fcc-metrics-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 575px) {
        .fcc-metrics-row {
            grid-template-columns: 1fr;
        }
    }

    .fcc-metric-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 18px;
        padding: 16px 18px;
        box-shadow: var(--fcc-card-shadow);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease;
    }

    .fcc-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.07);
        border-color: #cbd5e1;
    }

    .fcc-metric-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fcc-metric-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .mc-blue { background: #eff6ff; color: #2563eb; }
    .mc-purple { background: #f5f3ff; color: #7c3aed; }
    .mc-green { background: #ecfdf5; color: #059669; }

    .fcc-metric-num {
        font-size: 21px;
        font-weight: 800;
        color: var(--fcc-dark);
        line-height: 1.15;
        letter-spacing: -0.02em;
    }

    .fcc-metric-label {
        font-size: 12px;
        color: var(--fcc-muted);
        font-weight: 500;
        margin-top: 2px;
    }

    .fcc-spark-curve {
        width: 80px;
        height: 32px;
        flex-shrink: 0;
    }

    /* 5. Section 3: Performance Story & Action Queue */
    .fcc-analytics-grid {
        display: grid;
        grid-template-columns: 1.52fr 1fr;
        gap: 20px;
        margin-bottom: 22px;
    }

    @media (max-width: 991px) {
        .fcc-analytics-grid {
            grid-template-columns: 1fr;
        }
    }

    .fcc-card-panel {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 20px;
        padding: 22px 24px;
        box-shadow: var(--fcc-card-shadow);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .fcc-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .fcc-panel-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--fcc-dark);
        margin: 0;
    }

    .fcc-toggle-pills-box {
        display: inline-flex;
        background: #f1f5f9;
        padding: 3px;
        border-radius: 9px;
    }

    .fcc-toggle-btn {
        border: none;
        background: transparent;
        padding: 4px 12px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .fcc-toggle-btn.active {
        background: var(--fcc-primary);
        color: #ffffff;
        box-shadow: 0 2px 5px rgba(59, 70, 241, 0.25);
    }

    .fcc-summary-chips {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .fcc-sum-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 12.5px;
        color: #475569;
        font-weight: 600;
    }

    .fcc-sum-chip strong {
        font-weight: 800;
        color: var(--fcc-dark);
        font-size: 15px;
    }

    /* Action queue timeline */
    .fcc-queue-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 16px;
    }

    .fcc-queue-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .fcc-queue-avatar-side {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fcc-avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 12px;
        color: #ffffff;
        flex-shrink: 0;
    }

    .av-red { background: #e11d48; }
    .av-orange { background: #f59e0b; }
    .av-purple { background: #8b5cf6; }

    .fcc-queue-info h6 {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--fcc-dark);
        margin: 0;
        line-height: 1.2;
    }

    .fcc-queue-info p {
        font-size: 11.5px;
        color: var(--fcc-muted);
        margin: 2px 0 0 0;
    }

    .fcc-queue-link {
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .link-renew { color: #e11d48; }
    .link-remind { color: #ea580c; }
    .link-review { color: #3b46f1; }

    .fcc-activity-feed-box {
        border-top: 1px solid #f1f5f9;
        padding-top: 12px;
    }

    .fcc-feed-title {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 8px;
    }

    .fcc-feed-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12px;
        color: #334155;
        margin-bottom: 6px;
    }

    .fcc-feed-left {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .fcc-dot-green { width: 6px; height: 6px; border-radius: 50%; background: #10b981; }
    .fcc-dot-blue { width: 6px; height: 6px; border-radius: 50%; background: #3b82f6; }

    .fcc-feed-time {
        color: #94a3b8;
        font-size: 11px;
    }

    .fcc-open-feed-link {
        display: block;
        text-align: right;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--fcc-primary);
        text-decoration: none;
        margin-top: 4px;
    }

    .fcc-open-feed-link:hover {
        text-decoration: underline;
    }

    /* 6. Section 4: Top 20 Attendance Leaderboard */
    .fcc-leaderboard-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--fcc-card-shadow);
        margin-bottom: 22px;
    }

    .fcc-leaderboard-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }

    .fcc-leaderboard-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--fcc-dark);
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .fcc-leaderboard-badge {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 12px;
    }

    /* Leaderboard Table Row Styles */
    .fcc-rank-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .fcc-rank-table thead th {
        font-size: 11.5px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 6px 14px;
        border: none;
    }

    .fcc-rank-table tbody tr {
        background: #fbfcfe;
        border: 1px solid #f1f5f9;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .fcc-rank-table tbody tr:hover {
        background: #f1f5f9;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
    }

    .fcc-rank-table tbody td {
        padding: 12px 16px;
        font-size: 13.5px;
        vertical-align: middle;
        border: none;
    }

    .fcc-rank-table tbody td:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        width: 80px;
    }

    .fcc-rank-table tbody td:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    /* Fixed Rank Badge that never breaks */
    .fcc-rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-width: 52px;
        height: 32px;
        padding: 0 10px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 800;
        white-space: nowrap;
        line-height: 1;
    }

    .rank-gold { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .rank-silver { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .rank-bronze { background: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; }
    .rank-normal { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

    .fcc-member-name {
        font-weight: 700;
        color: var(--fcc-dark);
        font-size: 14px;
    }

    .fcc-days-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eef2ff;
        color: #4338ca;
        border: 1px solid #e0e7ff;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 999px;
        white-space: nowrap;
    }

    .fcc-prog-container {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 170px;
        max-width: 220px;
    }

    .fcc-prog-track {
        flex-grow: 1;
        height: 6px;
        border-radius: 999px;
        background: #e2e8f0;
        overflow: hidden;
    }

    .fcc-prog-bar {
        height: 100%;
        border-radius: 999px;
        background: #10b981;
    }

    .fcc-prog-pct {
        font-size: 12.5px;
        font-weight: 700;
        color: #10b981;
        min-width: 52px;
        text-align: right;
    }

    .fcc-coach-name {
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
        white-space: nowrap;
    }

    /* 7. Intelligence Banner */
    .fcc-intel-banner {
        background: linear-gradient(135deg, #f1f3fd 0%, #ede9fe 100%);
        border: 1px solid #e0e7ff;
        border-radius: 18px;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .fcc-intel-banner:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.14);
    }

    .fcc-intel-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .fcc-intel-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #e0e7ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .fcc-intel-title {
        font-size: 16px;
        font-weight: 800;
        color: #1e1b4b;
        margin-bottom: 1px;
    }

    .fcc-intel-subtitle {
        font-size: 12.5px;
        color: #6366f1;
        font-weight: 500;
        margin: 0;
    }

    .fcc-intel-arrow-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #4f46e5;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        box-shadow: 0 3px 8px rgba(79, 70, 229, 0.28);
        transition: all 0.2s ease;
    }

    .fcc-intel-banner:hover .fcc-intel-arrow-btn {
        transform: translateX(3px);
        background: #4338ca;
    }

    /* Supporting Content Tabs */
    .fcc-tab-panel {
        display: none;
    }

    .fcc-tab-panel.active {
        display: block;
    }

    .fcc-white-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--fcc-card-shadow);
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="fcc-main-container">

        @php
            use Carbon\Carbon;
            $endDate = isset($authUser['end_date']) ? Carbon::parse($authUser['end_date']) : Carbon::today()->addMonth();
            $hour = (int) date('H');
            if ($hour < 12) {
                $greetingTime = 'Good morning';
            } elseif ($hour < 17) {
                $greetingTime = 'Good afternoon';
            } else {
                $greetingTime = 'Good evening';
            }
            $userName = Auth::user()->name ?? 'Mokam';
            $firstName = ucfirst(explode(' ', $userName)[0]);
            $currentMonthName = date('F Y');
        @endphp

        <!-- 1. TOP HEADER BAR -->
        <div class="fcc-header">
            <div>
                <h1 class="fcc-greeting-title">{{ $greetingTime }}, {{ $firstName }}</h1>
                <p class="fcc-greeting-subtitle">Your club command center · {{ date('d M Y') }}</p>
            </div>

            <div class="fcc-header-actions">
                <div class="fcc-search-input-box">
                    <i class="fa fa-search search-icon"></i>
                    <input type="text" placeholder="Search members, logs..." />
                </div>

                <a href="#action-queue-section" class="fcc-icon-btn" title="Alerts">
                    <i class="fa fa-bell-o"></i>
                    <span class="badge-dot">7</span>
                </a>

                <div class="fcc-plan-pill">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                    <span>Active until {{ $endDate->format('d M') }}</span>
                </div>

                <a href="{{ route('nutritionPanel.users.create') }}" class="fcc-btn-add">
                    <i class="fa fa-plus"></i> Add member
                </a>
            </div>
        </div>

        <!-- 2. NAVIGATION TABS BAR -->
        <div class="fcc-tabs-bar">
            <a class="fcc-tab-btn active" data-tab="tab-overview">Overview</a>
            <a class="fcc-tab-btn" data-tab="tab-top20">Top 20 Attendance 🏆</a>
            <a class="fcc-tab-btn" data-tab="tab-members">Members</a>
            <a class="fcc-tab-btn" data-tab="tab-growth">Growth</a>
            <a class="fcc-tab-btn" data-tab="tab-finance">Finance</a>
        </div>

        <!-- TAB 1: OVERVIEW -->
        <div id="tab-overview" class="fcc-tab-panel active">
            
            <!-- SECTION 1: TOP HERO (CLUB PULSE + TODAY) -->
            <div class="fcc-hero-grid">
                
                <!-- Left: Club Pulse Card -->
                <div class="fcc-pulse-card">
                    <div>
                        <div class="fcc-pulse-top">
                            <i class="fa fa-heartbeat" style="color: #c7d2fe;"></i>
                            <span>Club pulse</span>
                        </div>

                        <div class="fcc-pulse-stats-row">
                            <div>
                                <div class="fcc-pulse-big-num">{{ $totalUsers ?? 68 }}</div>
                                <div class="fcc-pulse-big-lbl">total users</div>
                            </div>

                            <div class="fcc-pulse-pills">
                                <div class="fcc-pulse-pill-item">
                                    <i class="fa fa-building-o"></i>
                                    <span>{{ $offlineUsers ?? 31 }} offline</span>
                                </div>
                                <div class="fcc-pulse-pill-item">
                                    <i class="fa fa-wifi"></i>
                                    <span>{{ $onlineUsers ?? 0 }} online</span>
                                </div>
                                <div class="fcc-pulse-pill-item">
                                    <i class="fa fa-user-circle-o"></i>
                                    <span>3 coaches</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Weekly Attendance ApexChart -->
                    <div class="fcc-pulse-chart-box">
                        <div style="font-size: 11px; color: rgba(255,255,255,0.75); padding-left: 10px; margin-bottom: -5px;">Weekly attendance</div>
                        <div id="clubPulseChart"></div>
                    </div>

                    <div class="fcc-pulse-footer">
                        <span class="fcc-dot-live"></span>
                        <span>Operations running smoothly</span>
                    </div>
                </div>

                <!-- Right: Today Card -->
                <div class="fcc-today-card">
                    <div class="fcc-today-title">Today</div>

                    <div class="fcc-today-list">
                        <div class="fcc-today-item">
                            <div class="fcc-today-left">
                                <div class="fcc-today-icon icon-orange">
                                    <i class="fa fa-commenting-o"></i>
                                </div>
                                <div class="fcc-today-text">
                                    <strong>22</strong> counselling sessions
                                </div>
                            </div>
                            <a href="{{ route('nutritionPanel.attendance-register.index') }}" class="fcc-action-chevron">View <i class="fa fa-chevron-right"></i></a>
                        </div>

                        <div class="fcc-today-item">
                            <div class="fcc-today-left">
                                <div class="fcc-today-icon icon-coral">
                                    <i class="fa fa-user-plus"></i>
                                </div>
                                <div class="fcc-today-text">
                                    <strong>11</strong> new memberships
                                </div>
                            </div>
                            <a href="{{ route('nutritionPanel.users.index') }}" class="fcc-action-chevron">Follow up <i class="fa fa-chevron-right"></i></a>
                        </div>

                        <div class="fcc-today-item">
                            <div class="fcc-today-left">
                                <div class="fcc-today-icon icon-rose">
                                    <i class="fa fa-calendar-o"></i>
                                </div>
                                <div class="fcc-today-text">
                                    <strong>14</strong> renewals due · <span class="fcc-urgent-badge">5 urgent</span>
                                </div>
                            </div>
                            <a href="#action-queue-section" class="fcc-action-chevron">View <i class="fa fa-chevron-right"></i></a>
                        </div>

                        <div class="fcc-today-item">
                            <div class="fcc-today-left">
                                <div class="fcc-today-icon icon-purple">
                                    <i class="fa fa-gift"></i>
                                </div>
                                <div class="fcc-today-text">
                                    <strong>2</strong> birthdays
                                </div>
                            </div>
                            <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#birthdayModal" class="fcc-action-chevron">View <i class="fa fa-chevron-right"></i></a>
                        </div>
                    </div>

                    <button type="button" class="fcc-btn-scan-qr" data-bs-toggle="modal" data-bs-target="#qrScanModal">
                        <i class="fa fa-qrcode"></i> Scan attendance
                    </button>
                </div>

            </div>

            <!-- SECTION 2: 4 METRIC CARDS -->
            <div class="fcc-metrics-row">
                <!-- 1. August shake count -->
                <div class="fcc-metric-card">
                    <div class="fcc-metric-left">
                        <div class="fcc-metric-circle mc-blue">
                            <i class="fa fa-line-chart"></i>
                        </div>
                        <div>
                            <div class="fcc-metric-num">{{ $thisMonthShake ?? 560 }}</div>
                            <div class="fcc-metric-label">{{ date('F') }} shake count</div>
                        </div>
                    </div>
                    <svg class="fcc-spark-curve" viewBox="0 0 100 40">
                        <path d="M0,35 Q20,30 35,22 T70,18 T100,8" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>

                <!-- 2. monthly revenue -->
                <div class="fcc-metric-card">
                    <div class="fcc-metric-left">
                        <div class="fcc-metric-circle mc-purple">
                            <i class="fa fa-inr"></i>
                        </div>
                        <div>
                            <div class="fcc-metric-num">₹ 1,62,400</div>
                            <div class="fcc-metric-label">monthly revenue</div>
                        </div>
                    </div>
                    <svg class="fcc-spark-curve" viewBox="0 0 100 40">
                        <path d="M0,30 Q25,32 50,18 T80,12 T100,5" fill="none" stroke="#8b5cf6" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>

                <!-- 3. collected today -->
                <div class="fcc-metric-card">
                    <div class="fcc-metric-left">
                        <div class="fcc-metric-circle mc-green">
                            <i class="fa fa-credit-card"></i>
                        </div>
                        <div>
                            <div class="fcc-metric-num">₹ 8,780</div>
                            <div class="fcc-metric-label">collected today</div>
                        </div>
                    </div>
                    <svg class="fcc-spark-curve" viewBox="0 0 100 40">
                        <path d="M0,28 Q30,28 55,20 T80,15 T100,6" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>

                <!-- 4. checked in today -->
                <div class="fcc-metric-card">
                    <div class="fcc-metric-left">
                        <div class="fcc-metric-circle mc-blue">
                            <i class="fa fa-user-check"></i>
                        </div>
                        <div>
                            <div class="fcc-metric-num">68</div>
                            <div class="fcc-metric-label">checked in today</div>
                        </div>
                    </div>
                    <svg class="fcc-spark-curve" viewBox="0 0 100 40">
                        <path d="M0,32 Q25,28 50,22 T75,14 T100,8" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>

            <!-- SECTION 3: PERFORMANCE STORY & ACTION QUEUE -->
            <div class="fcc-analytics-grid" id="action-queue-section">
                
                <!-- Left: Performance story -->
                <div class="fcc-card-panel">
                    <div>
                        <div class="fcc-panel-header">
                            <h3 class="fcc-panel-title">Performance story</h3>
                            <div class="fcc-toggle-pills-box">
                                <button type="button" class="fcc-toggle-btn active" id="btnToggleAttendance">Attendance</button>
                                <button type="button" class="fcc-toggle-btn" id="btnToggleRevenue">Revenue</button>
                            </div>
                        </div>

                        <div id="performanceStoryChart"></div>
                    </div>

                    <div class="fcc-summary-chips">
                        <div class="fcc-sum-chip">
                            <i class="fa fa-bar-chart text-primary"></i>
                            <div><strong>64</strong> daily average</div>
                        </div>

                        <div class="fcc-sum-chip">
                            <i class="fa fa-line-chart" style="color: #8b5cf6;"></i>
                            <div><strong>82</strong> weekly peak</div>
                        </div>

                        <div class="fcc-sum-chip">
                            <i class="fa fa-arrow-up" style="color: #10b981;"></i>
                            <div><strong style="color: #10b981;">+12%</strong> this week</div>
                        </div>
                    </div>
                </div>

                <!-- Right: Action queue & Recent activity -->
                <div class="fcc-card-panel">
                    <div>
                        <div class="fcc-panel-header">
                            <h3 class="fcc-panel-title">Action queue</h3>
                        </div>

                        <div class="fcc-queue-group">
                            <div class="fcc-queue-row">
                                <div class="fcc-queue-avatar-side">
                                    <div class="fcc-avatar-circle av-red">RS</div>
                                    <div class="fcc-queue-info">
                                        <h6>Rahul Sharma</h6>
                                        <p>Membership expires today</p>
                                    </div>
                                </div>
                                <a href="{{ route('nutritionPanel.users.index') }}" class="fcc-queue-link link-renew">Renew <i class="fa fa-chevron-right"></i></a>
                            </div>

                            <div class="fcc-queue-row">
                                <div class="fcc-queue-avatar-side">
                                    <div class="fcc-avatar-circle av-orange">NP</div>
                                    <div class="fcc-queue-info">
                                        <h6>Neha Patel</h6>
                                        <p>₹2,500 payment due</p>
                                    </div>
                                </div>
                                <a href="{{ route('nutritionPanel.users.index') }}" class="fcc-queue-link link-remind">Remind <i class="fa fa-chevron-right"></i></a>
                            </div>

                            <div class="fcc-queue-row">
                                <div class="fcc-queue-avatar-side">
                                    <div class="fcc-avatar-circle av-purple">SG</div>
                                    <div class="fcc-queue-info">
                                        <h6>Sneha Gupta</h6>
                                        <p>BMI follow-up overdue</p>
                                    </div>
                                </div>
                                <a href="{{ route('nutritionPanel.users.index') }}" class="fcc-queue-link link-review">Review <i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="fcc-activity-feed-box">
                        <div class="fcc-feed-title">Recent activity</div>

                        <div class="fcc-feed-row">
                            <div class="fcc-feed-left">
                                <span class="fcc-dot-green"></span>
                                <span>Rahul Sharma checked in</span>
                            </div>
                            <span class="fcc-feed-time">Today, 7:45 AM</span>
                        </div>

                        <div class="fcc-feed-row">
                            <div class="fcc-feed-left">
                                <span class="fcc-dot-blue"></span>
                                <span>Neha Patel payment received</span>
                            </div>
                            <span class="fcc-feed-time">Yesterday, 8:15 PM</span>
                        </div>

                        <a href="{{ route('nutritionPanel.attendance-register.index') }}" class="fcc-open-feed-link">
                            Open activity feed <i class="fa fa-chevron-right"></i>
                        </a>
                    </div>
                </div>

            </div>

            <!-- SECTION 4: TOP 20 ATTENDANCE PREVIEW LEADERBOARD -->
            <div class="fcc-leaderboard-card">
                <div class="fcc-leaderboard-header">
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="fcc-leaderboard-title">
                            <span>Top 20 Attendance ({{ $currentMonthName }})</span>
                            <span style="font-size: 20px;">🏆</span>
                        </h3>
                        <span class="fcc-leaderboard-badge">Goal: {{ $totalDaysInMonth }} Days</span>
                    </div>
                    <a href="javascript:void(0);" onclick="$('.fcc-tab-btn[data-tab=tab-top20]').click();" class="fcc-action-chevron">
                        View Full Ranking <i class="fa fa-chevron-right"></i>
                    </a>
                </div>

                <!-- Top 20 Table Preview with Fixed Badges & Progress -->
                <div class="table-responsive">
                    <table class="fcc-rank-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Rank</th>
                                <th>Member Name</th>
                                <th style="width: 140px;">Attendance Days</th>
                                <th style="width: 240px;">Progress</th>
                                <th>Coach</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($top20Attendance) && count($top20Attendance) > 0)
                                @foreach($top20Attendance->take(10) as $index => $topAttend)
                                    <tr>
                                        <td>
                                            @if($index == 0)
                                                <span class="fcc-rank-badge rank-gold">🥇 1</span>
                                            @elseif($index == 1)
                                                <span class="fcc-rank-badge rank-silver">🥈 2</span>
                                            @elseif($index == 2)
                                                <span class="fcc-rank-badge rank-bronze">🥉 3</span>
                                            @else
                                                <span class="fcc-rank-badge rank-normal">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fcc-member-name">{{ ucfirst($topAttend->name) }}</span>
                                        </td>
                                        <td>
                                            <span class="fcc-days-pill">{{ $topAttend->total_attendance }} / {{ $totalDaysInMonth }}</span>
                                        </td>
                                        <td>
                                            <div class="fcc-prog-container">
                                                <div class="fcc-prog-track">
                                                    <div class="fcc-prog-bar" style="width: {{ min(100, $topAttend->attendance_percentage) }}%;"></div>
                                                </div>
                                                <span class="fcc-prog-pct">{{ $topAttend->attendance_percentage }}%</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fcc-coach-name">{{ $topAttend->coach_name ?? 'N/A' }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td><span class="fcc-rank-badge rank-gold">🥇 1</span></td>
                                    <td><span class="fcc-member-name">Rajan Rathi</span></td>
                                    <td><span class="fcc-days-pill">16 / 26</span></td>
                                    <td>
                                        <div class="fcc-prog-container">
                                            <div class="fcc-prog-track"><div class="fcc-prog-bar" style="width: 61.54%;"></div></div>
                                            <span class="fcc-prog-pct">61.54%</span>
                                        </div>
                                    </td>
                                    <td><span class="fcc-coach-name">Daksha</span></td>
                                </tr>
                                <tr>
                                    <td><span class="fcc-rank-badge rank-silver">🥈 2</span></td>
                                    <td><span class="fcc-member-name">Vijaya Solanki</span></td>
                                    <td><span class="fcc-days-pill">15 / 26</span></td>
                                    <td>
                                        <div class="fcc-prog-container">
                                            <div class="fcc-prog-track"><div class="fcc-prog-bar" style="width: 57.69%;"></div></div>
                                            <span class="fcc-prog-pct">57.69%</span>
                                        </div>
                                    </td>
                                    <td><span class="fcc-coach-name">Daksha</span></td>
                                </tr>
                                <tr>
                                    <td><span class="fcc-rank-badge rank-bronze">🥉 3</span></td>
                                    <td><span class="fcc-member-name">Daksha Sankhla</span></td>
                                    <td><span class="fcc-days-pill">15 / 26</span></td>
                                    <td>
                                        <div class="fcc-prog-container">
                                            <div class="fcc-prog-track"><div class="fcc-prog-bar" style="width: 57.69%;"></div></div>
                                            <span class="fcc-prog-pct">57.69%</span>
                                        </div>
                                    </td>
                                    <td><span class="fcc-coach-name">Mark Hughes</span></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION 5: INTELLIGENCE BANNER -->
            <a href="javascript:void(0);" onclick="$('.fcc-tab-btn[data-tab=tab-top20]').click();" class="fcc-intel-banner">
                <div class="fcc-intel-left">
                    <div class="fcc-intel-icon-box">
                        <i class="fa fa-bar-chart"></i>
                    </div>
                    <div>
                        <div class="fcc-intel-title">Explore attendance intelligence</div>
                        <div class="fcc-intel-subtitle">Dive deeper into trends, patterns, and member engagement.</div>
                    </div>
                </div>
                <div class="fcc-intel-arrow-btn">
                    <i class="fa fa-arrow-right"></i>
                </div>
            </a>

        </div>

        <!-- TAB 2: FULL TOP 20 & LEAST 20 ATTENDANCE RANKINGS -->
        <div id="tab-top20" class="fcc-tab-panel">
            <div class="row g-3 mb-4">
                <!-- Top 20 Attendance -->
                <div class="col-xl-6 col-12">
                    <div class="fcc-white-card">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="fw-bold mb-0">Top 20 Attendance ({{ $currentMonthName }}) 🏆</h4>
                            <span class="badge bg-success">Goal: {{ $totalDaysInMonth }} Days</span>
                        </div>
                        <div class="table-responsive">
                            <table class="fcc-rank-table">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">Rank</th>
                                        <th>Member</th>
                                        <th>Days</th>
                                        <th>Percentage</th>
                                        <th>Coach</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($top20Attendance) && count($top20Attendance) > 0)
                                        @foreach($top20Attendance as $index => $top20Attend)
                                            <tr>
                                                <td>
                                                    @if($index == 0)
                                                        <span class="fcc-rank-badge rank-gold">🥇 1</span>
                                                    @elseif($index == 1)
                                                        <span class="fcc-rank-badge rank-silver">🥈 2</span>
                                                    @elseif($index == 2)
                                                        <span class="fcc-rank-badge rank-bronze">🥉 3</span>
                                                    @else
                                                        <span class="fcc-rank-badge rank-normal">#{{ $index + 1 }}</span>
                                                    @endif
                                                </td>
                                                <td><span class="fcc-member-name">{{ ucfirst($top20Attend->name) }}</span></td>
                                                <td><span class="fcc-days-pill">{{ $top20Attend->total_attendance }} / {{ $totalDaysInMonth }}</span></td>
                                                <td>
                                                    <div class="fcc-prog-container">
                                                        <div class="fcc-prog-track">
                                                            <div class="fcc-prog-bar" style="width: {{ min(100, $top20Attend->attendance_percentage) }}%"></div>
                                                        </div>
                                                        <span class="fcc-prog-pct">{{ $top20Attend->attendance_percentage }}%</span>
                                                    </div>
                                                </td>
                                                <td><span class="fcc-coach-name">{{ $top20Attend->coach_name ?? 'N/A' }}</span></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="5" class="text-center py-3 text-muted">No attendance records found yet this month.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Least 20 Attendance -->
                <div class="col-xl-6 col-12">
                    <div class="fcc-white-card">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="fw-bold mb-0">Least 20 Attendance ({{ $currentMonthName }}) ⚠️</h4>
                            <span class="badge bg-danger">Needs Follow-Up</span>
                        </div>
                        <div class="table-responsive">
                            <table class="fcc-rank-table">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">#</th>
                                        <th>Member</th>
                                        <th>Days</th>
                                        <th>Percentage</th>
                                        <th>Coach</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($least20Attendance) && count($least20Attendance) > 0)
                                        @foreach($least20Attendance as $index => $least20Attend)
                                            <tr>
                                                <td><span class="fcc-rank-badge" style="background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2;">#{{ $index + 1 }}</span></td>
                                                <td><span class="fcc-member-name">{{ ucfirst($least20Attend->name) }}</span></td>
                                                <td><span class="fcc-days-pill" style="background: #fef3c7; color: #d97706; border-color: #fde68a;">{{ $least20Attend->total_attendance }} / {{ $totalDaysInMonth }}</span></td>
                                                <td>
                                                    <div class="fcc-prog-container">
                                                        <div class="fcc-prog-track">
                                                            <div class="fcc-prog-bar" style="background: #ef4444; width: {{ min(100, $least20Attend->attendance_percentage) }}%"></div>
                                                        </div>
                                                        <span class="fcc-prog-pct" style="color: #ef4444;">{{ $least20Attend->attendance_percentage }}%</span>
                                                    </div>
                                                </td>
                                                <td><span class="fcc-coach-name">{{ $least20Attend->coach_name ?? 'N/A' }}</span></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="5" class="text-center py-3 text-muted">No low attendance alerts this month.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: MEMBERS -->
        <div id="tab-members" class="fcc-tab-panel">
            <div class="row g-3 mb-4">
                <div class="col-xl-6 col-12">
                    <div class="fcc-white-card">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="fw-bold mb-0">Expiring Memberships (≤ 10 Days)</h4>
                            <span class="badge bg-warning text-dark">{{ count($membershipExpires ?? []) }} Expiring</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Member Name</th>
                                        <th>Remaining Days</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($membershipExpires) && count($membershipExpires) > 0)
                                        @foreach($membershipExpires as $key => $membershipExpire)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td class="fw-bold text-dark">{{ ucfirst($membershipExpire->name) }}</td>
                                                <td><span class="badge bg-warning">{{ $membershipExpire->days }} Days</span></td>
                                                <td>
                                                    <label class="switch s-success p-0 m-0">
                                                        <input type="checkbox" class="status-toggle" data-change-status-url="{{ route('nutritionPanel.users.changeStatus') }}" value="{{ $membershipExpire->id }}" @if($membershipExpire->status == 1) checked @endif>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="4" class="text-center py-3 text-muted">No memberships expiring soon.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-12">
                    <div class="fcc-white-card">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="fw-bold mb-0">Pending User Payments</h4>
                            <span class="badge bg-danger">{{ count($paymentPendings ?? []) }} Pending</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Member Name</th>
                                        <th>Due Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($paymentPendings) && count($paymentPendings) > 0)
                                        @foreach($paymentPendings as $key => $paymentPending)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td class="fw-bold text-dark">{{ ucfirst($paymentPending->name) }}</td>
                                                <td><span class="badge bg-danger">₹{{ number_format($paymentPending->due_amount, 2) }}</span></td>
                                                <td>
                                                    <a href="{{ route('nutritionPanel.users.details', ['id' => ev($paymentPending->id)]) }}" class="btn btn-sm btn-light text-primary">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="4" class="text-center py-3 text-muted">No pending payments found!</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 4: GROWTH & TAB 5: FINANCE -->
        <div id="tab-growth" class="fcc-tab-panel">
            <div class="row g-3 mb-4">
                <div class="col-xl-6 col-12">
                    <div class="fcc-white-card">
                        <h4 class="fw-bold mb-3">Monthly Shake Count Graph</h4>
                        <div id="shakeCountGraph"></div>
                    </div>
                </div>
                <div class="col-xl-6 col-12">
                    <div class="fcc-white-card">
                        <h4 class="fw-bold mb-3">User Growth Breakdown</h4>
                        <div id="revenueMonthly"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-finance" class="fcc-tab-panel">
            <div class="fcc-white-card">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <h4 class="fw-bold mb-0">Revenue & Product Transactions Graph</h4>
                    <div class="d-flex gap-3">
                        <span class="badge bg-primary">Income (Orders Placed)</span>
                        <span class="badge bg-danger">Revenue (Add User Days)</span>
                    </div>
                </div>
                <div id="incomeExpenseGraph"></div>
            </div>
        </div>

    </div>
</div>

<!-- QR MODAL -->
<div class="modal fade" id="qrScanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Club Attendance QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="text-muted fs-6 mb-3">Members can scan this QR code directly at your club to mark attendance.</p>
                <div id="print-area" class="d-flex justify-content-center my-3">
                    <div id="qr-container" style="padding: 16px; background: #ffffff; border: 2px dashed #cbd5e1; border-radius: 16px;"></div>
                </div>
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <button type="button" onclick="window.print()" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">
                        <i class="fa fa-print me-1"></i> Print QR Pass
                    </button>
                    <button type="button" id="downloadBtn" class="btn btn-dark px-4 py-2" style="border-radius: 10px;">
                        <i class="fa fa-download me-1"></i> Download PNG
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BIRTHDAY MODAL -->
<div class="modal fade" id="birthdayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Today's Birthdays 🎂</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Birth Year</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($thisMonthBirthdayUsers) && count($thisMonthBirthdayUsers) > 0)
                                @foreach($thisMonthBirthdayUsers as $bUser)
                                    <tr>
                                        <td class="fw-bold text-dark"><i class="fa fa-birthday-cake text-warning me-2"></i>{{ ucfirst($bUser->name) }}</td>
                                        <td>{{ date('Y', strtotime($bUser->date_of_birth)) }}</td>
                                        <td><span class="badge bg-primary">{{ $bUser->user_type }}</span></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i class="fa fa-gift fa-2x mb-2 d-block opacity-50"></i>
                                        No birthdays today.
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
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/plugins/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('admin-assets/plugins/apex/apexcharts.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    // Tab switching
    $('.fcc-tab-btn').on('click', function() {
        $('.fcc-tab-btn').removeClass('active');
        $(this).addClass('active');

        var targetTab = $(this).data('tab');
        $('.fcc-tab-panel').removeClass('active');
        $('#' + targetTab).addClass('active');

        window.dispatchEvent(new Event('resize'));
    });

    // 1. Club Pulse Area Line Chart
    var weeklyPulseLabels = ['Aug 19', 'Aug 20', 'Aug 21', 'Aug 22', 'Aug 23', 'Aug 24', 'Aug 25'];
    var weeklyPulseData = [45, 52, 68, 55, 48, 58, 68];

    var pulseOptions = {
        chart: {
            type: 'area',
            height: 145,
            toolbar: { show: false },
            parentHeightOffset: 0
        },
        series: [{
            name: 'Attendance',
            data: weeklyPulseData
        }],
        xaxis: {
            categories: weeklyPulseLabels,
            labels: {
                style: {
                    colors: 'rgba(255, 255, 255, 0.75)',
                    fontSize: '11px',
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            min: 0,
            max: 100,
            tickAmount: 4,
            labels: {
                style: {
                    colors: 'rgba(255, 255, 255, 0.75)',
                    fontSize: '10px'
                }
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3,
            colors: ['#ffffff']
        },
        markers: {
            size: 4,
            colors: ['#ffffff'],
            strokeColors: '#3042d6',
            strokeWidth: 2,
            hover: { size: 6 }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                type: 'vertical',
                shadeIntensity: 0.5,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [0, 100]
            }
        },
        annotations: {
            yaxis: [{
                y: 70,
                borderColor: 'rgba(255, 255, 255, 0.45)',
                strokeDashArray: 4,
                label: {
                    borderColor: 'transparent',
                    style: {
                        color: 'rgba(255, 255, 255, 0.9)',
                        background: 'transparent',
                        fontSize: '10.5px'
                    },
                    text: 'Target (70)',
                    position: 'right'
                }
            }]
        },
        grid: {
            borderColor: 'rgba(255, 255, 255, 0.1)',
            strokeDashArray: 3,
            padding: { top: 0, right: 15, bottom: 0, left: 5 }
        },
        tooltip: {
            theme: 'dark',
            y: { formatter: function(val) { return val + ' users'; } }
        }
    };
    new ApexCharts(document.querySelector("#clubPulseChart"), pulseOptions).render();

    // 2. Performance Story Area Chart
    var perfAttendanceData = [45, 52, 68, 55, 48, 58, 68];
    var perfRevenueData = [5400, 6800, 12000, 8900, 7500, 10200, 8780];

    var performanceOptions = {
        chart: {
            type: 'area',
            height: 235,
            toolbar: { show: false },
            parentHeightOffset: 0
        },
        series: [{
            name: 'Attendance',
            data: perfAttendanceData
        }],
        xaxis: {
            categories: weeklyPulseLabels,
            labels: {
                style: {
                    colors: '#9ca3af',
                    fontSize: '11px',
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            min: 0,
            max: 100,
            tickAmount: 4,
            labels: {
                style: {
                    colors: '#9ca3af',
                    fontSize: '10.5px'
                }
            }
        },
        colors: ['#3b82f6'],
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 5,
            colors: ['#3b82f6'],
            strokeColors: '#ffffff',
            strokeWidth: 2,
            hover: { size: 7 }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.3,
                opacityFrom: 0.35,
                opacityTo: 0.05,
                stops: [0, 100]
            }
        },
        annotations: {
            yaxis: [{
                y: 70,
                borderColor: '#cbd5e1',
                strokeDashArray: 4,
                label: {
                    borderColor: 'transparent',
                    style: {
                        color: '#6b7280',
                        background: 'transparent',
                        fontSize: '11px'
                    },
                    text: 'Target (70)',
                    position: 'right'
                }
            }]
        },
        grid: {
            borderColor: '#f3f4f6',
            strokeDashArray: 4,
            padding: { top: 0, right: 15, bottom: 0, left: 10 }
        },
        tooltip: {
            theme: 'light',
            y: { formatter: function(val) { return val + ' users'; } }
        }
    };
    var performanceChart = new ApexCharts(document.querySelector("#performanceStoryChart"), performanceOptions);
    performanceChart.render();

    $('#btnToggleAttendance').on('click', function() {
        $('.fcc-toggle-btn').removeClass('active');
        $(this).addClass('active');
        performanceChart.updateOptions({
            colors: ['#3b82f6'],
            markers: { colors: ['#3b82f6'] },
            yaxis: { min: 0, max: 100, tickAmount: 4 },
            tooltip: { y: { formatter: function(v) { return v + ' users'; } } }
        });
        performanceChart.updateSeries([{ name: 'Attendance', data: perfAttendanceData }]);
    });

    $('#btnToggleRevenue').on('click', function() {
        $('.fcc-toggle-btn').removeClass('active');
        $(this).addClass('active');
        performanceChart.updateOptions({
            colors: ['#8b5cf6'],
            markers: { colors: ['#8b5cf6'] },
            yaxis: { min: undefined, max: undefined, tickAmount: 4 },
            tooltip: { y: { formatter: function(v) { return '₹ ' + Number(v).toLocaleString('en-IN'); } } }
        });
        performanceChart.updateSeries([{ name: 'Revenue (₹)', data: perfRevenueData }]);
    });

    // 3. Yearly Analytics Charts
    var shakeCount = {!! json_encode($totalShakeChartData ?? [45, 60, 75, 80, 65, 90, 85, 110, 95, 100, 105, 120]) !!};
    new ApexCharts(document.querySelector("#shakeCountGraph"), {
        chart: { height: 280, type: 'bar', fontFamily: 'Plus Jakarta Sans, sans-serif', toolbar: { show: false } },
        colors: ['#3b46f1'],
        plotOptions: { bar: { horizontal: false, columnWidth: '38%', borderRadius: 6 } },
        dataLabels: { enabled: false },
        series: [{ name: 'Shake Count', data: shakeCount }],
        xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] }
    }).render();

    var userDemoChartData = {!! json_encode($userDemoChartData ?? [10, 15, 20, 25, 18, 22, 30, 28, 35, 40, 38, 42]) !!};
    var userTrailChartData = {!! json_encode($userTrailChartData ?? [5, 8, 12, 14, 10, 15, 18, 20, 22, 25, 24, 28]) !!};
    var userRegualrChartData = {!! json_encode($userRegualrChartData ?? [20, 30, 45, 50, 48, 60, 70, 75, 80, 90, 95, 105]) !!};

    new ApexCharts(document.querySelector("#revenueMonthly"), {
        chart: { fontFamily: 'Plus Jakarta Sans, sans-serif', height: 280, type: 'area', toolbar: { show: false } },
        colors: ['#3b82f6', '#ef4444', '#10b981'],
        dataLabels: { enabled: false },
        stroke: { show: true, curve: 'smooth', width: 3 },
        series: [
            { name: 'Demo Users', data: userDemoChartData },
            { name: '3-Day Trial', data: userTrailChartData },
            { name: 'Regular Users', data: userRegualrChartData }
        ],
        xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] }
    }).render();

    var transactionAddUserChartData = {!! json_encode($transactionAddUserChartData ?? [15000, 22000, 30000, 35000, 28000, 40000, 45000, 50000, 48000, 55000, 60000, 65000]) !!};
    var transactionOrderPlacedChartData = {!! json_encode($transactionOrderPlacedChartData ?? [25000, 32000, 45000, 50000, 42000, 60000, 70000, 80000, 75000, 85000, 90000, 97400]) !!};

    new ApexCharts(document.querySelector("#incomeExpenseGraph"), {
        chart: { height: 280, type: 'bar', fontFamily: 'Plus Jakarta Sans, sans-serif', toolbar: { show: false } },
        colors: ['#3b82f6', '#ef4444'],
        plotOptions: { bar: { horizontal: false, columnWidth: '35%', borderRadius: 6 } },
        dataLabels: { enabled: false },
        series: [
            { name: 'Income (Orders)', data: transactionOrderPlacedChartData },
            { name: 'Revenue (User Days)', data: transactionAddUserChartData }
        ],
        xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] }
    }).render();

    // 4. QR Code Generator
    const qrValue = "{{ $qr_code }}";
    if(qrValue && qrValue.trim() !== ""){
        new QRCode(document.getElementById("qr-container"), {
            text: qrValue,
            width: 160,
            height: 160,
            colorDark: "#3246d3",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }

    const downloadBtn = document.getElementById('downloadBtn');
    if(downloadBtn) {
        downloadBtn.onclick = function(){
            const canvas = document.querySelector('#qr-container canvas');
            if(canvas) {
                const link = document.createElement('a');
                link.download = 'qr-' + qrValue + '.png';
                link.href = canvas.toDataURL();
                link.click();
            }
        };
    }
</script>
@endpush
