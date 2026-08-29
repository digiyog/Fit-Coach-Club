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
        grid-template-columns: 1.45fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    @media (max-width: 1100px) {
        .fcc-hero-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Club pulse */
    .fcc-pulse-card {
        background: linear-gradient(135deg, #1e266d 0%, #3042d6 50%, #4338ca 100%);
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
        background: linear-gradient(135deg, #3b46f1 0%, #2f38c7 100%);
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
        margin-bottom: 20px;
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
        padding: 16px 20px;
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
        gap: 14px;
    }

    .fcc-metric-circle {
        width: 42px;
        height: 42px;
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
        font-size: 22px;
        font-weight: 800;
        color: var(--fcc-dark);
        line-height: 1.15;
        letter-spacing: -0.02em;
    }

    .fcc-metric-label {
        font-size: 12.5px;
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
    .fcc-story-action-grid {
        display: grid;
        grid-template-columns: 1.45fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 1100px) {
        .fcc-story-action-grid {
            grid-template-columns: 1fr;
        }
    }

    .fcc-story-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 20px;
        padding: 22px 24px;
        box-shadow: var(--fcc-card-shadow);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .fcc-story-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .fcc-story-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--fcc-dark);
        margin: 0;
    }

    .fcc-pill-toggle {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        padding: 3px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .fcc-pill-btn {
        border: none;
        background: transparent;
        padding: 5px 14px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .fcc-pill-btn.active {
        background: #2546e8;
        color: #ffffff;
        font-weight: 700;
        box-shadow: 0 2px 6px rgba(37, 70, 232, 0.25);
    }

    .fcc-story-stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        padding-top: 16px;
        border-top: 1px solid #f1f5f9;
        margin-top: 6px;
    }

    .fcc-story-stat-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fcc-story-stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .fcc-story-stat-num {
        font-size: 18px;
        font-weight: 800;
        color: var(--fcc-dark);
        line-height: 1.1;
    }

    .fcc-story-stat-lbl {
        font-size: 12px;
        color: var(--fcc-muted);
        font-weight: 500;
    }

    /* Action Queue Card */
    .fcc-action-queue-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 20px;
        padding: 22px 24px;
        box-shadow: var(--fcc-card-shadow);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .fcc-action-queue-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--fcc-dark);
        margin-bottom: 14px;
    }

    .fcc-action-timeline {
        position: relative;
        padding-left: 10px;
        margin-bottom: 16px;
    }

    .fcc-action-timeline::before {
        content: '';
        position: absolute;
        top: 18px;
        bottom: 18px;
        left: 27px;
        width: 2px;
        background: #f1f5f9;
        z-index: 1;
    }

    .fcc-action-item {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        z-index: 2;
    }

    .fcc-action-left {
        display: flex;
        align-items: center;
        gap: 14px;
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
        border: 3px solid #ffffff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    .av-red { background: #ef4444; }
    .av-orange { background: #f59e0b; }
    .av-purple { background: #6366f1; }
    .av-blue { background: #3b82f6; }

    .fcc-action-details {
        display: flex;
        flex-direction: column;
    }

    .fcc-action-name {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--fcc-dark);
        line-height: 1.2;
    }

    .fcc-action-subtext {
        font-size: 12px;
        font-weight: 600;
        margin-top: 2px;
    }

    .subtext-red { color: #ef4444; }
    .subtext-orange { color: #d97706; }
    .subtext-purple { color: #4f46e5; }
    .subtext-blue { color: #2563eb; }

    .link-renew { color: #ef4444 !important; }
    .link-remind { color: #d97706 !important; }
    .link-review { color: #4f46e5 !important; }

    /* Recent Activity Feed */
    .fcc-recent-activity-section {
        border-top: 1px solid #f1f5f9;
        padding-top: 14px;
    }

    .fcc-recent-title {
        font-size: 13.5px;
        font-weight: 800;
        color: var(--fcc-dark);
        margin-bottom: 10px;
    }

    .fcc-activity-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 10px;
    }

    .fcc-activity-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12.5px;
    }

    .fcc-activity-left {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: #334155;
    }

    .fcc-act-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .fcc-act-dot.green { background: #10b981; }
    .fcc-act-dot.blue { background: #3b82f6; }

    .fcc-activity-time {
        color: #94a3b8;
        font-size: 11.5px;
        font-weight: 500;
    }

    .fcc-open-feed-link {
        display: block;
        text-align: right;
        font-size: 12.5px;
        font-weight: 700;
        color: #3b46f1;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .fcc-open-feed-link:hover {
        color: #2546e8;
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

    /* Coach Hub Styles */
    .fcc-coach-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 20px;
        padding: 22px;
        box-shadow: var(--fcc-card-shadow);
        transition: all 0.2s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .fcc-coach-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px -4px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }

    .fcc-coach-avatar-lg {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        flex-shrink: 0;
    }

    .fcc-coach-avatar-sm {
        width: 36px;
        height: 36px;
        border-radius: 11px;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        margin-right: 10px;
        box-shadow: 0 2px 6px rgba(79, 70, 229, 0.2);
    }

    /* 8. Attendance Tab Premium Styles */
    .fcc-att-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 1200px) {
        .fcc-att-grid {
            grid-template-columns: 1fr;
        }
    }

    .fcc-att-main-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--fcc-card-shadow);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .fcc-att-top-row {
        display: grid;
        grid-template-columns: 240px 1fr 180px;
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 992px) {
        .fcc-att-top-row {
            grid-template-columns: 1fr;
        }
    }

    /* Calendar Heatmap */
    .fcc-cal-header {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .fcc-cal-days-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        margin-bottom: 8px;
    }

    .fcc-cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
    }

    .fcc-cal-cell {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11.5px;
        font-weight: 600;
        border-radius: 8px;
        color: #334155;
        background: #f8fafc;
        transition: all 0.15s ease;
        position: relative;
    }

    .fcc-cal-cell.empty {
        background: transparent;
    }

    .fcc-cal-cell.heat-0 { background: #f8fafc; color: #94a3b8; }
    .fcc-cal-cell.heat-1 { background: #e0e7ff; color: #3730a3; font-weight: 700; }
    .fcc-cal-cell.heat-2 { background: #818cf8; color: #ffffff; font-weight: 700; }
    .fcc-cal-cell.heat-3 { background: #4f46e5; color: #ffffff; font-weight: 800; }
    .fcc-cal-cell.heat-4 { background: #312e81; color: #ffffff; font-weight: 800; }
    .fcc-cal-cell.today { outline: 2px solid #3b82f6; outline-offset: 1px; font-weight: 900; }

    /* Middle KPIs & Trend Chart */
    .fcc-trend-kpi-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        padding: 0 10px;
    }

    .fcc-trend-kpi-item {
        text-align: center;
    }

    .fcc-trend-kpi-val {
        font-size: 22px;
        font-weight: 900;
        color: #1e1b4b;
        line-height: 1;
        letter-spacing: -0.5px;
    }

    .fcc-trend-kpi-val.target {
        color: #4338ca;
    }

    .fcc-trend-kpi-lbl {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        margin-top: 4px;
    }

    /* Consistency Score Box */
    .fcc-consist-box {
        text-align: center;
    }

    .fcc-consist-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .fcc-consist-legend {
        margin-top: 8px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .fcc-consist-leg-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12.5px;
    }

    .fcc-leg-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }

    /* Sidebar Live Attention & Audit */
    .fcc-att-side-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 20px;
        padding: 22px;
        box-shadow: var(--fcc-card-shadow);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .fcc-live-alert-card {
        background: #fff5f5;
        border: 1px solid #fee2e2;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .fcc-live-alert-top {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
    }

    .fcc-live-alert-icon {
        color: #ef4444;
        font-size: 20px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .fcc-live-alert-title {
        font-size: 13.5px;
        font-weight: 800;
        color: #991b1b;
        line-height: 1.3;
    }

    .fcc-live-alert-sub {
        font-size: 11.5px;
        color: #b91c1c;
        margin-top: 2px;
    }

    .fcc-btn-review-rec {
        display: block;
        width: 100%;
        text-align: center;
        background: #ef4444;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 12px;
        padding: 7px 12px;
        border-radius: 999px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .fcc-btn-review-rec:hover {
        background: #dc2626;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    .fcc-audit-timeline-title {
        font-size: 13.5px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .fcc-audit-list {
        position: relative;
        padding-left: 24px;
        margin-bottom: 14px;
    }

    .fcc-audit-list::before {
        content: '';
        position: absolute;
        left: 6px;
        top: 6px;
        bottom: 6px;
        width: 2px;
        background: #f1f5f9;
    }

    .fcc-audit-item {
        position: relative;
        margin-bottom: 14px;
        font-size: 12px;
    }

    .fcc-audit-item:last-child {
        margin-bottom: 0;
    }

    .fcc-audit-node {
        position: absolute;
        left: -24px;
        top: 2px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid #ef4444;
    }

    .fcc-audit-badge-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 800;
        padding: 1px 6px;
        border-radius: 6px;
        margin: 0 6px;
    }

    .fcc-audit-badge-pill.plus {
        background: #dcfce7;
        color: #16a34a;
    }

    .fcc-audit-badge-pill.minus {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Member Consistency Board */
    .fcc-board-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--fcc-card-shadow);
        margin-bottom: 24px;
    }

    .fcc-board-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 22px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .fcc-board-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    .fcc-board-filter-pills {
        display: inline-flex;
        background: #f1f5f9;
        padding: 3px;
        border-radius: 999px;
    }

    .fcc-board-pill-btn {
        border: none;
        background: transparent;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .fcc-board-pill-btn.active {
        background: #3b46f1;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(59, 70, 241, 0.25);
    }

    .fcc-board-cols-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 24px;
    }

    @media (max-width: 992px) {
        .fcc-board-cols-grid {
            grid-template-columns: 1fr;
        }
    }

    .fcc-board-col {
        border-right: 1px solid #f1f5f9;
        padding-right: 18px;
    }

    .fcc-board-col:last-child {
        border-right: none;
        padding-right: 0;
    }

    .fcc-board-col-head {
        font-size: 14px;
        font-weight: 800;
        color: #1e1b4b;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .fcc-board-row-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f8fafc;
        gap: 8px;
    }

    .fcc-board-row-left {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .fcc-board-rank-num {
        font-size: 13px;
        font-weight: 800;
        color: #1e1b4b;
        width: 16px;
        flex-shrink: 0;
    }

    .fcc-board-pct-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 2px solid #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
        color: #1d4ed8;
        flex-shrink: 0;
        background: #eff6ff;
    }

    .fcc-board-pct-circle.coral {
        border-color: #f87171;
        background: #fef2f2;
        color: #dc2626;
    }

    .fcc-board-member-name {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fcc-board-row-right {
        text-align: right;
        flex-shrink: 0;
    }

    .fcc-board-days-txt {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
    }

    .fcc-board-coach-txt {
        font-size: 10.5px;
        color: #64748b;
        font-weight: 500;
    }

    .fcc-btn-message-nudge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #f97316;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 13px;
        padding: 10px 18px;
        border-radius: 12px;
        text-decoration: none;
        margin-top: 14px;
        transition: all 0.2s ease;
    }

    .fcc-btn-message-nudge:hover {
        background: #ea580c;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.35);
    }

    /* 9. Membership Renewals Dashboard Styles */
    .fcc-renew-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }

    .fcc-renew-title {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.02em;
    }

    .fcc-renew-sub {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        margin: 0;
    }

    .fcc-renew-toolbar {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .fcc-renew-search-box {
        position: relative;
        min-width: 220px;
    }

    .fcc-renew-search-box input {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 8px 14px 8px 34px;
        font-size: 13px;
        color: #0f172a;
        width: 100%;
        outline: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .fcc-renew-search-box .search-icon {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 12.5px;
    }

    .fcc-renew-select {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        outline: none;
        cursor: pointer;
    }

    .fcc-btn-bulk-remind {
        background: #3b46f1;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 13px;
        padding: 8px 18px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(59, 70, 241, 0.25);
    }

    .fcc-btn-bulk-remind:hover {
        background: #2f38d4;
        box-shadow: 0 6px 16px rgba(59, 70, 241, 0.35);
        transform: translateY(-1px);
    }

    /* KPI Summary Strip with Colored Underline */
    .fcc-renew-summary-wrap {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 20px;
        box-shadow: var(--fcc-card-shadow);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .fcc-renew-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        padding: 16px 24px;
        gap: 16px;
    }

    @media (max-width: 992px) {
        .fcc-renew-summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .fcc-renew-summary-grid {
            grid-template-columns: 1fr;
        }
    }

    .fcc-renew-stat-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fcc-renew-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .fcc-renew-stat-icon.red { background: #fee2e2; color: #ef4444; }
    .fcc-renew-stat-icon.orange { background: #ffedd5; color: #f97316; }
    .fcc-renew-stat-icon.purple { background: #f3e8ff; color: #9333ea; }
    .fcc-renew-stat-icon.green { background: #dcfce7; color: #16a34a; }

    .fcc-renew-stat-num {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }

    .fcc-renew-stat-lbl {
        font-size: 12.5px;
        color: #64748b;
        font-weight: 500;
        margin-left: 4px;
    }

    .fcc-renew-rainbow-bar {
        height: 3.5px;
        width: 100%;
        background: linear-gradient(90deg, #ef4444 0%, #f97316 25%, #8b5cf6 50%, #10b981 100%);
    }

    /* Kanban 4 Columns Layout */
    .fcc-renew-columns-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 310px;
        gap: 18px;
        margin-bottom: 24px;
        align-items: start;
    }

    @media (max-width: 1300px) {
        .fcc-renew-columns-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 768px) {
        .fcc-renew-columns-grid {
            grid-template-columns: 1fr;
        }
    }

    .fcc-renew-column-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 16px;
        min-height: 520px;
    }

    .fcc-renew-column-box.col-red {
        background: #fff8f8;
        border-color: #fee2e2;
    }

    .fcc-renew-column-box.col-amber {
        background: #fffbf2;
        border-color: #fef3c7;
    }

    .fcc-renew-column-box.col-blue {
        background: #f8faff;
        border-color: #e0e7ff;
    }

    .fcc-renew-col-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        padding-bottom: 8px;
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
    }

    .fcc-renew-col-title {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .fcc-renew-cards-stack {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* Renewal Card */
    .fcc-renew-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px 16px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        transition: all 0.2s ease;
        position: relative;
    }

    .fcc-renew-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }

    .fcc-renew-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .fcc-renew-card-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fcc-renew-drag-dots {
        color: #cbd5e1;
        font-size: 13px;
        cursor: grab;
    }

    .fcc-renew-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 13px;
        color: #ffffff;
        flex-shrink: 0;
    }

    .fcc-renew-avatar.red { background: linear-gradient(135deg, #f87171 0%, #ef4444 100%); }
    .fcc-renew-avatar.amber { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); }
    .fcc-renew-avatar.blue { background: linear-gradient(135deg, #818cf8 0%, #6366f1 100%); }

    .fcc-renew-mname {
        font-size: 13.5px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .fcc-renew-mcoach {
        font-size: 11px;
        color: #64748b;
        font-weight: 500;
    }

    .fcc-renew-days-pill {
        font-size: 12px;
        font-weight: 800;
        text-align: right;
    }

    .fcc-renew-days-pill.red { color: #ef4444; }
    .fcc-renew-days-pill.amber { color: #f59e0b; }
    .fcc-renew-days-pill.blue { color: #3b82f6; }

    .fcc-renew-phone {
        font-size: 11.5px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 8px;
    }

    .fcc-renew-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 8px;
        border-top: 1px dashed #f1f5f9;
    }

    .fcc-renew-type-badge {
        font-size: 11px;
        color: #475569;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 600;
    }

    .fcc-btn-renew {
        font-size: 12px;
        font-weight: 700;
        color: #3b46f1;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        padding: 4px 12px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .fcc-btn-renew:hover {
        background: #3b46f1;
        color: #ffffff !important;
        border-color: #3b46f1;
    }

    /* Follow-up Assistant Card */
    .fcc-assistant-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 20px;
        padding: 22px;
        box-shadow: var(--fcc-card-shadow);
    }

    .fcc-assistant-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 16px;
    }

    .fcc-plan-checklist {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 18px;
    }

    .fcc-plan-check-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #334155;
        font-weight: 600;
        cursor: pointer;
    }

    .fcc-plan-check-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #3b46f1;
        cursor: pointer;
    }

    .fcc-btn-start-followup {
        display: block;
        width: 100%;
        text-align: center;
        background: #3b46f1;
        color: #ffffff !important;
        font-weight: 800;
        font-size: 13px;
        padding: 10px 16px;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(59, 70, 241, 0.25);
    }

    .fcc-btn-start-followup:hover {
        background: #2f38d4;
        box-shadow: 0 6px 18px rgba(59, 70, 241, 0.35);
        transform: translateY(-1px);
    }

    .fcc-reminder-pill-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 16px;
    }

    .fcc-reminder-bell-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #eef2ff;
        color: #3b46f1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    /* Bottom Activity Flow Strip */
    .fcc-renew-flow-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 20px;
        padding: 20px 24px;
        box-shadow: var(--fcc-card-shadow);
        margin-bottom: 24px;
    }

    .fcc-renew-flow-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .fcc-flow-nodes-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        position: relative;
    }

    .fcc-flow-node-item {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 220px;
    }

    .fcc-flow-node-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #ffffff;
        flex-shrink: 0;
    }

    .fcc-flow-node-icon.green { background: #10b981; }
    .fcc-flow-node-icon.blue { background: #3b82f6; }
    .fcc-flow-node-icon.purple { background: #8b5cf6; }

    .fcc-flow-node-line {
        flex-grow: 1;
        height: 2px;
        background: #e2e8f0;
        margin: 0 12px;
    }

    @media (max-width: 768px) {
        .fcc-flow-node-line {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="fcc-main-container">

        @php
            use Carbon\Carbon;
            $currentUser = Auth::user() ?? ($authUser ?? null);
            $userEndDate = is_object($currentUser) ? ($currentUser->end_date ?? null) : (is_array($currentUser) ? ($currentUser['end_date'] ?? null) : null);
            $endDate = !empty($userEndDate) ? Carbon::parse($userEndDate) : Carbon::today()->addMonth();
            $hour = (int) date('H');
            if ($hour < 12) {
                $greetingTime = 'Good morning';
            } elseif ($hour < 17) {
                $greetingTime = 'Good afternoon';
            } else {
                $greetingTime = 'Good evening';
            }
            $userName = is_object($currentUser) ? ($currentUser->name ?? 'Mokam') : (is_array($currentUser) ? ($currentUser['name'] ?? 'Mokam') : 'Mokam');
            $nameParts = !empty($userName) ? explode(' ', trim($userName)) : ['Mokam'];
            $firstName = ucfirst($nameParts[0] ?? 'Mokam');
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

                <a href="javascript:void(0)" class="fcc-icon-btn" title="Alerts">
                    <i class="fa fa-bell-o"></i>
                    @if(isset($totalAlertsCount) && $totalAlertsCount > 0)
                        <span class="badge-dot">{{ $totalAlertsCount }}</span>
                    @endif
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
            <a class="fcc-tab-btn" data-tab="tab-top20">Attendance</a>
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
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                            </svg>
                            <span>Club pulse</span>
                        </div>

                        <div class="fcc-pulse-stats-row">
                            <div>
                                <div class="fcc-pulse-big-num">{{ $totalUsers ?? 0 }}</div>
                                <div class="fcc-pulse-big-lbl">total users</div>
                            </div>

                            <div class="fcc-pulse-pills">
                                <div class="fcc-pulse-pill-item">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.85;">
                                        <circle cx="12" cy="7" r="4"></circle>
                                        <path d="M5.5 21v-2a6.5 6.5 0 0 1 13 0v2"></path>
                                    </svg>
                                    <div>
                                        <div style="font-weight: 800; font-size: 16px; line-height: 1;">{{ $offlineUsers ?? 0 }}</div>
                                        <div style="font-size: 11px; opacity: 0.8; font-weight: 500;">offline</div>
                                    </div>
                                </div>
                                <div class="fcc-pulse-pill-item">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.85;">
                                        <path d="M5 12.55a11 11 0 0 1 14.08 0"></path>
                                        <path d="M1.42 9a16 16 0 0 1 21.16 0"></path>
                                        <path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path>
                                        <line x1="12" y1="20" x2="12.01" y2="20"></line>
                                    </svg>
                                    <div>
                                        <div style="font-weight: 800; font-size: 16px; line-height: 1;">{{ $onlineUsers ?? 0 }}</div>
                                        <div style="font-size: 11px; opacity: 0.8; font-weight: 500;">online</div>
                                    </div>
                                </div>
                                <div class="fcc-pulse-pill-item" style="cursor: pointer; transition: all 0.2s ease;" onclick="$('.fcc-tab-btn[data-tab=\'tab-finance\']').trigger('click');" title="Click to view finance">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.85;">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    <div>
                                        <div style="font-weight: 800; font-size: 16px; line-height: 1;">₹ {{ number_format($thisMonthRevenue ?? 0, 0) }}</div>
                                        <div style="font-size: 11px; opacity: 0.85; font-weight: 600;">revenue <i class="fa fa-arrow-right" style="font-size: 9px; margin-left: 2px;"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Weekly Attendance ApexChart -->
                    <div class="fcc-pulse-chart-box">
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 0 12px; margin-bottom: -6px;">
                            <span style="font-size: 11px; color: rgba(255,255,255,0.75); font-weight: 500;">Weekly attendance</span>
                        </div>
                        <div id="clubPulseChart"></div>
                    </div>

                    <div class="fcc-pulse-footer">
                        <span class="fcc-dot-live"></span>
                        <span>Operations running smoothly</span>
                    </div>
                </div>

                <!-- Middle: Today Card -->
                <div class="fcc-today-card">
                    <div class="fcc-today-title">Today</div>

                    <div class="fcc-today-list">
                        <div class="fcc-today-item">
                            <div class="fcc-today-left">
                                <div class="fcc-today-icon icon-orange">
                                    <i class="fa fa-commenting-o"></i>
                                </div>
                                <div class="fcc-today-text">
                                    <strong>{{ $todayCounsellingCount ?? 0 }}</strong> counselling sessions
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
                                    <strong>{{ $todayNewMemberships ?? 0 }}</strong> new memberships
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
                                    <strong>{{ $todayRenewalsDue ?? 0 }}</strong> renewals due @if(($todayUrgentRenewals ?? 0) > 0)· <span class="fcc-urgent-badge">{{ $todayUrgentRenewals }} urgent</span>@endif
                                </div>
                            </div>
                            <a href="{{ route('nutritionPanel.users.index') }}" class="fcc-action-chevron">View <i class="fa fa-chevron-right"></i></a>
                        </div>

                        <div class="fcc-today-item">
                            <div class="fcc-today-left">
                                <div class="fcc-today-icon icon-purple">
                                    <i class="fa fa-gift"></i>
                                </div>
                                <div class="fcc-today-text">
                                    <strong>{{ count($thisMonthBirthdayUsers ?? []) }}</strong> birthdays
                                </div>
                            </div>
                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#birthdayModal" class="fcc-action-chevron">View <i class="fa fa-chevron-right"></i></a>
                        </div>
                    </div>

                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#qrAttendanceModal" class="fcc-btn-scan-qr">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                            <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                            <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                            <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                            <rect x="7" y="7" width="10" height="10" rx="1"></rect>
                        </svg>
                        <span>Scan attendance</span>
                    </a>
                </div>

            </div>

            <!-- SECTION 2: 4 METRIC CARDS -->
            <div class="fcc-metrics-row">
                <!-- 1. Current month shake count -->
                <div class="fcc-metric-card">
                    <div class="fcc-metric-left">
                        <div class="fcc-metric-circle mc-blue">
                            <i class="fa fa-line-chart"></i>
                        </div>
                        <div>
                            <div class="fcc-metric-num">{{ $thisMonthShake ?? 0 }}</div>
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
                            <div class="fcc-metric-num">₹ {{ number_format($thisMonthRevenue ?? 0, 0) }}</div>
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
                            <div class="fcc-metric-num">₹ {{ number_format($todayCollected ?? 0, 0) }}</div>
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
                            <i class="fa fa-user"></i>
                        </div>
                        <div>
                            <div class="fcc-metric-num">{{ $todayCheckedIn ?? 0 }}</div>
                            <div class="fcc-metric-label">checked in today</div>
                        </div>
                    </div>
                    <svg class="fcc-spark-curve" viewBox="0 0 100 40">
                        <path d="M0,32 Q25,28 50,22 T75,14 T100,8" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>

            <!-- SECTION 3: PERFORMANCE STORY & ACTION QUEUE -->
            <div class="fcc-story-action-grid">
                
                <!-- Left: Performance Story Card -->
                <div class="fcc-story-card">
                    <div>
                        <div class="fcc-story-header">
                            <h3 class="fcc-story-title">Performance story</h3>
                            <div class="fcc-pill-toggle">
                                <button type="button" class="fcc-pill-btn active" id="storyToggleAttendance">Attendance</button>
                                <button type="button" class="fcc-pill-btn" id="storyToggleRevenue">Revenue</button>
                            </div>
                        </div>

                        <!-- Performance Story Spline Chart -->
                        <div id="performanceStoryChart" style="min-height: 195px;"></div>
                    </div>

                    <!-- 3 Bottom Metrics -->
                    <div class="fcc-story-stats-row">
                        <div class="fcc-story-stat-item">
                            <div class="fcc-story-stat-icon" style="background: #eff6ff; color: #2563eb;">
                                <i class="fa fa-bar-chart"></i>
                            </div>
                            <div>
                                <div class="fcc-story-stat-num">{{ $dailyAvgAttendance ?? 0 }}</div>
                                <div class="fcc-story-stat-lbl">daily average</div>
                            </div>
                        </div>

                        <div class="fcc-story-stat-item">
                            <div class="fcc-story-stat-icon" style="background: #f5f3ff; color: #7c3aed;">
                                <i class="fa fa-line-chart"></i>
                            </div>
                            <div>
                                <div class="fcc-story-stat-num">{{ $weeklyPeakAttendance ?? 0 }}</div>
                                <div class="fcc-story-stat-lbl">weekly peak</div>
                            </div>
                        </div>

                        <div class="fcc-story-stat-item">
                            <div class="fcc-story-stat-icon" style="background: #ecfdf5; color: #059669;">
                                <i class="fa fa-arrow-up"></i>
                            </div>
                            <div>
                                <div class="fcc-story-stat-num" style="color: #059669;">{{ ($weeklyGrowthPct >= 0 ? '+' : '') . $weeklyGrowthPct }}%</div>
                                <div class="fcc-story-stat-lbl">this week</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Action Queue Card -->
                <div class="fcc-action-queue-card">
                    <div>
                        <h3 class="fcc-action-queue-title">Action queue</h3>

                        <div class="fcc-action-timeline">
                            @php
                                $displayActions = !empty($actionQueueItems) ? array_slice($actionQueueItems, 0, 3) : [
                                    ['name' => 'Rahul Sharma', 'subtext' => 'Membership expires today', 'action_label' => 'Renew', 'action_url' => route('nutritionPanel.users.index'), 'color_class' => 'av-red', 'link_class' => 'link-renew', 'subtext_class' => 'subtext-red'],
                                    ['name' => 'Neha Patel', 'subtext' => '₹2,500 payment due', 'action_label' => 'Remind', 'action_url' => route('nutritionPanel.users.index'), 'color_class' => 'av-orange', 'link_class' => 'link-remind', 'subtext_class' => 'subtext-orange'],
                                    ['name' => 'Sneha Gupta', 'subtext' => 'BMI follow-up overdue', 'action_label' => 'Review', 'action_url' => route('nutritionPanel.users.index'), 'color_class' => 'av-purple', 'link_class' => 'link-review', 'subtext_class' => 'subtext-purple'],
                                ];
                            @endphp

                            @foreach($displayActions as $action)
                                @php
                                    $actName = is_array($action) ? ($action['name'] ?? 'Member') : ($action->name ?? 'Member');
                                    $nameParts = !empty($actName) ? explode(' ', trim($actName)) : ['M'];
                                    $initials = strtoupper(substr($nameParts[0] ?? 'M', 0, 1) . substr($nameParts[1] ?? '', 0, 1));
                                    $colorCls = is_array($action) ? ($action['color_class'] ?? 'av-red') : ($action->color_class ?? 'av-red');
                                    $subtext = is_array($action) ? ($action['subtext'] ?? '') : ($action->subtext ?? '');
                                    $actionUrl = is_array($action) ? ($action['action_url'] ?? '#') : ($action->action_url ?? '#');
                                    $actionLabel = is_array($action) ? ($action['action_label'] ?? 'View') : ($action->action_label ?? 'View');
                                    $subtextCls = str_contains($colorCls, 'red') ? 'subtext-red' : (str_contains($colorCls, 'orange') ? 'subtext-orange' : 'subtext-purple');
                                    $linkCls = str_contains($colorCls, 'red') ? 'link-renew' : (str_contains($colorCls, 'orange') ? 'link-remind' : 'link-review');
                                @endphp
                                <div class="fcc-action-item">
                                    <div class="fcc-action-left">
                                        <div class="fcc-avatar-circle {{ $colorCls }}">{{ $initials }}</div>
                                        <div class="fcc-action-details">
                                            <span class="fcc-action-name">{{ $actName }}</span>
                                            <span class="fcc-action-subtext {{ $subtextCls }}">{{ $subtext }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ $actionUrl }}" class="fcc-action-chevron {{ $linkCls }}">{{ $actionLabel }} <i class="fa fa-chevron-right"></i></a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recent Activity Feed -->
                    <div class="fcc-recent-activity-section">
                        <div class="fcc-recent-title">Recent activity</div>
                        <div class="fcc-activity-list">
                            @if(isset($recentActivities) && count($recentActivities) > 0)
                                @foreach($recentActivities->take(2) as $act)
                                    @php
                                        $actTitle = is_array($act) ? ($act['title'] ?? 'Activity') : ($act->title ?? 'Activity');
                                        $actTime = is_array($act) ? ($act['time'] ?? '') : ($act->time ?? '');
                                        $dotClass = is_array($act) ? ($act['dot_class'] ?? '') : ($act->dot_class ?? '');
                                    @endphp
                                    <div class="fcc-activity-item">
                                        <div class="fcc-activity-left">
                                            <span class="fcc-act-dot {{ str_contains($dotClass, 'green') ? 'green' : 'blue' }}"></span>
                                            <span>{{ $actTitle }}</span>
                                        </div>
                                        <span class="fcc-activity-time">{{ $actTime }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="fcc-activity-item">
                                    <div class="fcc-activity-left">
                                        <span class="fcc-act-dot green"></span>
                                        <span>Rahul Sharma checked in</span>
                                    </div>
                                    <span class="fcc-activity-time">Today, 7:45 AM</span>
                                </div>
                                <div class="fcc-activity-item">
                                    <div class="fcc-activity-left">
                                        <span class="fcc-act-dot blue"></span>
                                        <span>Neha Patel payment received</span>
                                    </div>
                                    <span class="fcc-activity-time">Yesterday, 8:15 PM</span>
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('nutritionPanel.attendance-register.index') }}" class="fcc-open-feed-link">Open activity feed <i class="fa fa-chevron-right"></i></a>
                    </div>
                </div>

            </div>

        </div>

        <!-- TAB 2: ATTENDANCE MAP, TRENDS & MEMBER CONSISTENCY BOARD -->
        <div id="tab-top20" class="fcc-tab-panel">
            
            <!-- 1. TOP SECTION: ATTENDANCE MAP + TREND + CONSISTENCY + LIVE ATTENTION -->
            <div class="fcc-att-grid">
                
                <!-- Left: Main Attendance Card (Map + Trend + Consistency) -->
                <div class="fcc-att-main-card">
                    <div class="fcc-att-top-row">
                        
                        <!-- 1. Attendance Calendar Map -->
                        <div class="fcc-cal-box">
                            <div class="fcc-cal-header">
                                <span>Attendance map</span>
                                <span class="text-muted fw-normal">· {{ $currentMonthName ?? date('F') }}</span>
                            </div>

                            <div class="fcc-cal-days-header">
                                <span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                            </div>

                            <div class="fcc-cal-grid">
                                @php
                                    $firstDayOfWeek = $currentMonthFirstDayOfWeek ?? 0;
                                    $daysCount = $currentMonthDaysCount ?? date('t');
                                    $todayDay = (int)date('j');
                                    $dailyMap = $monthlyDailyAttendance ?? [];
                                @endphp

                                @for($empty = 0; $empty < $firstDayOfWeek; $empty++)
                                    <div class="fcc-cal-cell empty"></div>
                                @endfor

                                @for($d = 1; $d <= $daysCount; $d++)
                                    @php
                                        $attCount = $dailyMap[$d] ?? 0;
                                        if ($attCount == 0) $heatClass = 'heat-0';
                                        elseif ($attCount <= 10) $heatClass = 'heat-1';
                                        elseif ($attCount <= 25) $heatClass = 'heat-2';
                                        elseif ($attCount <= 50) $heatClass = 'heat-3';
                                        else $heatClass = 'heat-4';

                                        $isToday = ($d == $todayDay) ? 'today' : '';
                                    @endphp
                                    <div class="fcc-cal-cell {{ $heatClass }} {{ $isToday }}" title="{{ date('M') }} {{ $d }}: {{ $attCount }} check-ins">
                                        {{ $d }}
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- 2. Performance Trend Spline Chart -->
                        <div class="fcc-trend-box">
                            <div class="fcc-trend-kpi-row">
                                <div class="fcc-trend-kpi-item">
                                    <div class="fcc-trend-kpi-val">{{ $todayCheckedIn ?? 0 }}</div>
                                    <div class="fcc-trend-kpi-lbl">today</div>
                                </div>
                                <div class="fcc-trend-kpi-item">
                                    <div class="fcc-trend-kpi-val">{{ $dailyAvgAttendance ?? 0 }}</div>
                                    <div class="fcc-trend-kpi-lbl">daily average</div>
                                </div>
                                <div class="fcc-trend-kpi-item">
                                    <div class="fcc-trend-kpi-val">{{ $weeklyPeakAttendance ?? 0 }}</div>
                                    <div class="fcc-trend-kpi-lbl">weekly peak</div>
                                </div>
                                <div class="fcc-trend-kpi-item">
                                    <div class="fcc-trend-kpi-val target">70</div>
                                    <div class="fcc-trend-kpi-lbl">Target</div>
                                </div>
                            </div>

                            <div id="attendanceTrendChart" style="min-height: 140px;"></div>
                        </div>

                        <!-- 3. Consistency Score Radial & Breakdown -->
                        <div class="fcc-consist-box">
                            <div class="fcc-consist-title">Consistency score</div>
                            <div id="consistencyRadialChart" style="min-height: 120px;"></div>
                            
                            <div class="fcc-consist-legend">
                                <div class="fcc-consist-leg-item">
                                    <span class="text-muted"><span class="fcc-leg-dot" style="background: #3b82f6;"></span>Regular</span>
                                    <strong class="text-dark">{{ $regularUsersCount ?? 0 }}</strong>
                                </div>
                                <div class="fcc-consist-leg-item">
                                    <span class="text-muted"><span class="fcc-leg-dot" style="background: #6366f1;"></span>3-day</span>
                                    <strong class="text-dark">{{ $trialUsersCount ?? 0 }}</strong>
                                </div>
                                <div class="fcc-consist-leg-item">
                                    <span class="text-muted"><span class="fcc-leg-dot" style="background: #c084fc;"></span>Demo</span>
                                    <strong class="text-dark">{{ $demoUsersCount ?? 0 }}</strong>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right: Live Attention & Audit Timeline Sidebar -->
                <div class="fcc-att-side-card">
                    
                    <!-- Live Attention Box -->
                    @if(isset($today2Attendences) && count($today2Attendences) > 0)
                        @php $firstAlert = $today2Attendences->first(); @endphp
                        <div class="fcc-live-alert-card">
                            <div class="fcc-live-alert-top">
                                <i class="fa fa-exclamation-triangle fcc-live-alert-icon"></i>
                                <div>
                                    <div class="fcc-live-alert-title">{{ ucfirst($firstAlert->name) }} checked in</div>
                                    <div class="fcc-live-alert-sub"><strong>{{ $firstAlert->total_attendance }} times today</strong></div>
                                    <div style="font-size: 11.5px; color: #7f1d1d; margin-top: 2px;">Coach: {{ $firstAlert->coach_name ?? 'Club Coach' }}</div>
                                </div>
                            </div>
                            <a href="{{ route('nutritionPanel.attendance-register.index') }}" class="fcc-btn-review-rec">
                                Review record
                            </a>
                        </div>
                    @else
                        <div class="fcc-live-alert-card" style="background: #f0fdf4; border-color: #dcfce7;">
                            <div class="fcc-live-alert-top">
                                <i class="fa fa-check-circle" style="color: #16a34a; font-size: 20px; flex-shrink: 0; margin-top: 2px;"></i>
                                <div>
                                    <div class="fcc-live-alert-title" style="color: #166534;">Live Attention · All Clear</div>
                                    <div class="fcc-live-alert-sub" style="color: #15803d;">Check-in rhythm running smoothly today</div>
                                </div>
                            </div>
                            <a href="{{ route('nutritionPanel.attendance-register.index') }}" class="fcc-btn-review-rec" style="background: #16a34a;">
                                View Register
                            </a>
                        </div>
                    @endif

                    <!-- Audit Timeline · Today -->
                    <div>
                        <div class="fcc-audit-timeline-title">
                            <span>Audit timeline · Today</span>
                        </div>

                        <div class="fcc-audit-list">
                            @if(isset($todayAttendences) && count($todayAttendences) > 0)
                                @foreach($todayAttendences->take(3) as $tIndex => $tAtt)
                                    @php
                                        $tTime = $tAtt->created_at ? date('H:i', strtotime($tAtt->created_at)) : date('H:i');
                                        $badgeVal = '+1';
                                        $badgeClass = 'plus';
                                    @endphp
                                    <div class="fcc-audit-item">
                                        <span class="fcc-audit-node" style="border-color: #16a34a;"></span>
                                        <div class="d-flex align-items-center flex-wrap">
                                            <span class="text-muted" style="font-size: 11.5px; font-weight: 600;">{{ $tTime }}</span>
                                            <span class="fcc-audit-badge-pill {{ $badgeClass }}">{{ $badgeVal }}</span>
                                            <strong class="text-dark">{{ $tAtt->remark ?? 'QR Attendance' }}</strong>
                                        </div>
                                        <div class="text-muted" style="font-size: 11px; margin-top: 1px;">
                                            {{ ucfirst($tAtt->name) }} · {{ $tAtt->coach_name ?? 'Club' }}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="fcc-audit-item">
                                    <span class="fcc-audit-node" style="border-color: #3b82f6;"></span>
                                    <div class="d-flex align-items-center">
                                        <span class="text-muted" style="font-size: 11.5px;">{{ date('H:i') }}</span>
                                        <span class="fcc-audit-badge-pill plus">+1</span>
                                        <strong class="text-dark">Ready for check-ins</strong>
                                    </div>
                                    <div class="text-muted" style="font-size: 11px;">Awaiting scans</div>
                                </div>
                            @endif
                        </div>

                        <div class="text-end">
                            <a href="{{ route('nutritionPanel.attendance-register.index') }}" class="d-flex align-items-center justify-content-end gap-1 text-primary fw-bold" style="font-size: 12px; text-decoration: none;">
                                <span>View last 20 days</span>
                                <i class="fa fa-chevron-right" style="font-size: 10px;"></i>
                            </a>
                        </div>
                    </div>

                </div>

            </div>

            <!-- 2. MEMBER CONSISTENCY BOARD -->
            <div class="fcc-board-card">
                
                <!-- Board Header & Filters -->
                <div class="fcc-board-top-bar">
                    <h3 class="fcc-board-title">Member consistency board</h3>

                    <div class="fcc-board-filter-pills">
                        <button type="button" class="fcc-board-pill-btn active" data-board-filter="top">Top performers</button>
                        <button type="button" class="fcc-board-pill-btn" data-board-filter="nudge">Needs follow-up</button>
                        <button type="button" class="fcc-board-pill-btn" data-board-filter="all">All members</button>
                    </div>

                    <span class="text-muted" style="font-size: 12.5px; font-weight: 500;">Present out of {{ $totalDaysInMonth ?? 25 }} days</span>
                </div>

                <!-- 3 Columns Board Layout -->
                <div class="fcc-board-cols-grid">
                    
                    <!-- Column 1: Leaders (Top 1-4) -->
                    <div class="fcc-board-col">
                        <div class="fcc-board-col-head">
                            <i class="fa fa-trophy text-primary"></i>
                            <span>Leaders</span>
                        </div>

                        <div>
                            @if(isset($top20Attendance) && count($top20Attendance) > 0)
                                @foreach($top20Attendance->slice(0, 4) as $idx => $leader)
                                    @php $pct = round($leader->attendance_percentage); @endphp
                                    <div class="fcc-board-row-item">
                                        <div class="fcc-board-row-left">
                                            <span class="fcc-board-rank-num">{{ $idx + 1 }}</span>
                                            <div class="fcc-board-pct-circle">
                                                {{ $pct }}%
                                            </div>
                                            <div style="min-width: 0;">
                                                <div class="fcc-board-member-name">{{ ucfirst($leader->name) }}</div>
                                            </div>
                                        </div>
                                        <div class="fcc-board-row-right">
                                            <div class="fcc-board-days-txt">{{ $leader->total_attendance }} <span class="text-muted fw-normal" style="font-size: 11px;">/ {{ $totalDaysInMonth }} days</span></div>
                                            <div class="fcc-board-coach-txt">{{ $leader->coach_name ?? 'Coach' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4 text-muted" style="font-size: 12.5px;">No attendance logged yet.</div>
                            @endif
                        </div>
                    </div>

                    <!-- Column 2: On track (Top 5-8) -->
                    <div class="fcc-board-col">
                        <div class="fcc-board-col-head">
                            <i class="fa fa-line-chart text-primary"></i>
                            <span>On track</span>
                        </div>

                        <div>
                            @if(isset($top20Attendance) && count($top20Attendance) > 4)
                                @foreach($top20Attendance->slice(4, 4) as $idx => $onTrack)
                                    @php $pct = round($onTrack->attendance_percentage); @endphp
                                    <div class="fcc-board-row-item">
                                        <div class="fcc-board-row-left">
                                            <span class="fcc-board-rank-num">{{ $idx + 5 }}</span>
                                            <div style="min-width: 0;">
                                                <div class="fcc-board-member-name">{{ ucfirst($onTrack->name) }}</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="text-end">
                                                <div class="fcc-board-days-txt">{{ $onTrack->total_attendance }} <span class="text-muted fw-normal" style="font-size: 11px;">/ {{ $totalDaysInMonth }} days</span></div>
                                                <div class="fcc-board-coach-txt">{{ $onTrack->coach_name ?? 'Coach' }}</div>
                                            </div>
                                            <div class="fcc-board-pct-circle">
                                                {{ $pct }}%
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif(isset($top20Attendance) && count($top20Attendance) > 0)
                                @foreach($top20Attendance->slice(0, 4) as $idx => $onTrack)
                                    @php $pct = round($onTrack->attendance_percentage); @endphp
                                    <div class="fcc-board-row-item">
                                        <div class="fcc-board-row-left">
                                            <span class="fcc-board-rank-num">{{ $idx + 1 }}</span>
                                            <div style="min-width: 0;">
                                                <div class="fcc-board-member-name">{{ ucfirst($onTrack->name) }}</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="text-end">
                                                <div class="fcc-board-days-txt">{{ $onTrack->total_attendance }} <span class="text-muted fw-normal" style="font-size: 11px;">/ {{ $totalDaysInMonth }} days</span></div>
                                                <div class="fcc-board-coach-txt">{{ $onTrack->coach_name ?? 'Coach' }}</div>
                                            </div>
                                            <div class="fcc-board-pct-circle">
                                                {{ $pct }}%
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4 text-muted" style="font-size: 12.5px;">No active streaks yet.</div>
                            @endif
                        </div>
                    </div>

                    <!-- Column 3: Needs a nudge (Least 1-4) -->
                    <div class="fcc-board-col">
                        <div class="fcc-board-col-head">
                            <i class="fa fa-exclamation-circle text-danger"></i>
                            <span>Needs a nudge</span>
                        </div>

                        <div>
                            @if(isset($least20Attendance) && count($least20Attendance) > 0)
                                @foreach($least20Attendance->slice(0, 4) as $idx => $nudge)
                                    @php $pct = round($nudge->attendance_percentage); @endphp
                                    <div class="fcc-board-row-item">
                                        <div class="fcc-board-row-left">
                                            <span class="fcc-board-rank-num">{{ $idx + 1 }}</span>
                                            <div style="min-width: 0;">
                                                <div class="fcc-board-member-name">{{ ucfirst($nudge->name) }}</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="text-end">
                                                <div class="fcc-board-days-txt">{{ $nudge->total_attendance }} <span class="text-muted fw-normal" style="font-size: 11px;">/ {{ $totalDaysInMonth }} days</span></div>
                                                <div class="fcc-board-coach-txt">{{ $nudge->coach_name ?? 'Coach' }}</div>
                                            </div>
                                            <div class="fcc-board-pct-circle coral" style="width: 36px; height: 36px; font-size: 10px;">
                                                {{ $pct }}%
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <a href="javascript:void(0)" onclick="$('.fcc-tab-btn[data-tab=\'tab-members\']').trigger('click');" class="fcc-btn-message-nudge">
                                    <i class="fa fa-commenting-o"></i>
                                    <span>Message {{ count($least20Attendance) }} members</span>
                                </a>
                            @else
                                <div class="text-center py-4 text-muted" style="font-size: 12.5px;">All members on schedule!</div>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="text-end mt-3 pt-2 border-top">
                    <a href="{{ route('nutritionPanel.attendance-register.index') }}" class="d-inline-flex align-items-center gap-1 text-primary fw-bold" style="font-size: 12.5px; text-decoration: none;">
                        <span>View all 20</span>
                        <i class="fa fa-chevron-right" style="font-size: 10px;"></i>
                    </a>
                </div>

            </div>

            <!-- 3. BOTTOM BANNER: CONTINUE TO GROWTH & FINANCE -->
            <div class="fcc-intel-banner mb-4" onclick="$('.fcc-tab-btn[data-tab=\'tab-growth\']').trigger('click');" style="cursor: pointer;">
                <div class="fcc-intel-left">
                    <div class="fcc-intel-icon-box">
                        <i class="fa fa-line-chart"></i>
                    </div>
                    <div>
                        <div class="fcc-intel-title">Continue to growth &amp; finance</div>
                        <p class="fcc-intel-subtitle">Explore attendance impact on membership and revenue</p>
                    </div>
                </div>
                <div class="fcc-intel-arrow-btn">
                    <i class="fa fa-arrow-right"></i>
                </div>
            </div>
        </div>

         <!-- TAB 3: MEMBERSHIP RENEWALS & FOLLOW-UP ASSISTANT -->
        <div id="tab-members" class="fcc-tab-panel">
            
            <!-- 1. TOP HEADER & ACTION TOOLBAR -->
            <div class="fcc-renew-header-bar">
                <div>
                    <h2 class="fcc-renew-title">Membership renewals</h2>
                    <p class="fcc-renew-sub">Act before access expires</p>
                </div>

                <div class="fcc-renew-toolbar">
                    <div class="fcc-renew-search-box">
                        <i class="fa fa-search search-icon"></i>
                        <input type="text" id="renewMemberSearchInput" placeholder="Search members..." />
                    </div>

                    <select id="renewFilterDaysSelect" class="fcc-renew-select">
                        <option value="7">Next 7 days</option>
                        <option value="14">Next 14 days</option>
                        <option value="30">Next 30 days</option>
                        <option value="all">All renewals</option>
                    </select>

                    <select id="renewFilterCoachSelect" class="fcc-renew-select">
                        <option value="">All coaches</option>
                        @if(isset($allCoachesList) && count($allCoachesList) > 0)
                            @foreach($allCoachesList as $coachItem)
                                <option value="{{ $coachItem->coach_name }}">{{ $coachItem->coach_name }}</option>
                            @endforeach
                        @endif
                    </select>

                    <button type="button" id="sendBulkReminderBtn" class="btn fcc-btn-bulk-remind">
                        <i class="fa fa-paper-plane"></i>
                        <span>Send bulk reminder</span>
                    </button>
                </div>
            </div>

            <!-- 2. KPI SUMMARY STRIP WITH RAINBOW BAR -->
            <div class="fcc-renew-summary-wrap">
                <div class="fcc-renew-summary-grid">
                    
                    <!-- Stat 1: Due Soon -->
                    <div class="fcc-renew-stat-item">
                        <div class="fcc-renew-stat-icon red">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <span class="fcc-renew-stat-num text-danger">{{ $totalDueSoonCount ?? 14 }}</span>
                            <span class="fcc-renew-stat-lbl">due soon</span>
                        </div>
                    </div>

                    <!-- Stat 2: Expire Today -->
                    <div class="fcc-renew-stat-item">
                        <div class="fcc-renew-stat-icon orange">
                            <i class="fa fa-exclamation-circle"></i>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <span class="fcc-renew-stat-num text-warning">{{ $totalExpireTodayCount ?? 5 }}</span>
                            <span class="fcc-renew-stat-lbl">expire today</span>
                        </div>
                    </div>

                    <!-- Stat 3: Renewal Value -->
                    <div class="fcc-renew-stat-item">
                        <div class="fcc-renew-stat-icon purple">
                            <i class="fa fa-inr"></i>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <span class="fcc-renew-stat-num" style="color: #6366f1;">₹{{ number_format($estimatedRenewalValue ?? 28500, 0) }}</span>
                            <span class="fcc-renew-stat-lbl">renewal value</span>
                        </div>
                    </div>

                    <!-- Stat 4: Contacted Rate -->
                    <div class="fcc-renew-stat-item">
                        <div class="fcc-renew-stat-icon green">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <span class="fcc-renew-stat-num text-success">{{ $contactedRate ?? 72 }}%</span>
                            <span class="fcc-renew-stat-lbl">contacted</span>
                        </div>
                    </div>

                </div>
                <div class="fcc-renew-rainbow-bar"></div>
            </div>

            <!-- 3. KANBAN 4-COLUMNS BOARD LAYOUT -->
            <div class="fcc-renew-columns-grid">
                
                <!-- Column 1: Expires Today -->
                <div class="fcc-renew-column-box col-red">
                    <div class="fcc-renew-col-header">
                        <div class="fcc-renew-col-title text-danger">
                            <i class="fa fa-clock-o"></i>
                            <span>Expires today · {{ count($expiresTodayMembers ?? []) }}</span>
                        </div>
                        <i class="fa fa-ellipsis-h text-muted" style="cursor: pointer;"></i>
                    </div>

                    <div class="fcc-renew-cards-stack" id="expiresTodayCardsContainer">
                        @if(isset($expiresTodayMembers) && count($expiresTodayMembers) > 0)
                            @foreach($expiresTodayMembers as $m)
                                @php
                                    $mName = ucfirst($m->name);
                                    $mInitials = strtoupper(substr($mName, 0, 1) . (str_contains($mName, ' ') ? substr(explode(' ', $mName)[1] ?? '', 0, 1) : ''));
                                    $coach = $m->coach_name ?? 'Club Coach';
                                    $phone = $m->mobile_number ?? '94601 07529';
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                                    $userType = $m->user_type ?? 'Regular';
                                    $userState = $m->user_state ?? 'Offline';
                                @endphp
                                <div class="fcc-renew-card renew-card-item" data-name="{{ strtolower($mName) }}" data-coach="{{ strtolower($coach) }}">
                                    <div class="fcc-renew-card-top">
                                        <div class="fcc-renew-card-left">
                                            <span class="fcc-renew-drag-dots"><i class="fa fa-ellipsis-v me-0.5"></i><i class="fa fa-ellipsis-v"></i></span>
                                            <div class="form-check p-0 m-0">
                                                <input class="form-check-input renew-member-checkbox m-0" type="checkbox" value="{{ $m->id }}">
                                            </div>
                                            <div class="fcc-renew-avatar red">{{ $mInitials ?: 'M' }}</div>
                                            <div>
                                                <div class="fcc-renew-mname">{{ $mName }}</div>
                                                <div class="fcc-renew-mcoach">{{ $coach }}</div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fcc-renew-days-pill red">0 days left</div>
                                            <div class="text-muted" style="font-size: 11px;">Not contacted</div>
                                        </div>
                                    </div>

                                    <div class="fcc-renew-phone">
                                        <i class="fa fa-phone me-1"></i>
                                        <span>{{ $phone }}</span>
                                        @if(!empty($cleanPhone))
                                            <a href="https://wa.me/91{{ $cleanPhone }}" target="_blank" class="text-success ms-1" title="Chat on WhatsApp"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                    </div>

                                    <div class="fcc-renew-card-footer">
                                        <span class="fcc-renew-type-badge">{{ $userType }} ({{ $userState }})</span>
                                        <a href="{{ route('nutritionPanel.users.addUserDays', ['id' => ev($m->id)]) }}" class="fcc-btn-renew">
                                            Renew
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fa fa-check-circle fa-2x text-success mb-2 d-block opacity-50"></i>
                                <span style="font-size: 13px; font-weight: 600;">No memberships expiring today!</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Column 2: Due Tomorrow -->
                <div class="fcc-renew-column-box col-amber">
                    <div class="fcc-renew-col-header">
                        <div class="fcc-renew-col-title text-warning">
                            <i class="fa fa-exclamation-circle"></i>
                            <span>Due tomorrow · {{ count($expiresTomorrowMembers ?? []) }}</span>
                        </div>
                        <i class="fa fa-ellipsis-h text-muted" style="cursor: pointer;"></i>
                    </div>

                    <div class="fcc-renew-cards-stack" id="expiresTomorrowCardsContainer">
                        @if(isset($expiresTomorrowMembers) && count($expiresTomorrowMembers) > 0)
                            @foreach($expiresTomorrowMembers as $m)
                                @php
                                    $mName = ucfirst($m->name);
                                    $mInitials = strtoupper(substr($mName, 0, 1) . (str_contains($mName, ' ') ? substr(explode(' ', $mName)[1] ?? '', 0, 1) : ''));
                                    $coach = $m->coach_name ?? 'Club Coach';
                                    $phone = $m->mobile_number ?? '97830 71763';
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                                    $userType = $m->user_type ?? 'Regular';
                                    $userState = $m->user_state ?? 'Offline';
                                @endphp
                                <div class="fcc-renew-card renew-card-item" data-name="{{ strtolower($mName) }}" data-coach="{{ strtolower($coach) }}">
                                    <div class="fcc-renew-card-top">
                                        <div class="fcc-renew-card-left">
                                            <span class="fcc-renew-drag-dots"><i class="fa fa-ellipsis-v me-0.5"></i><i class="fa fa-ellipsis-v"></i></span>
                                            <div class="form-check p-0 m-0">
                                                <input class="form-check-input renew-member-checkbox m-0" type="checkbox" value="{{ $m->id }}">
                                            </div>
                                            <div class="fcc-renew-avatar amber">{{ $mInitials ?: 'A' }}</div>
                                            <div>
                                                <div class="fcc-renew-mname">{{ $mName }}</div>
                                                <div class="fcc-renew-mcoach">{{ $coach }}</div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fcc-renew-days-pill amber">1 day left</div>
                                            <div class="text-muted" style="font-size: 11px;">Not contacted</div>
                                        </div>
                                    </div>

                                    <div class="fcc-renew-phone">
                                        <i class="fa fa-phone me-1"></i>
                                        <span>{{ $phone }}</span>
                                        @if(!empty($cleanPhone))
                                            <a href="https://wa.me/91{{ $cleanPhone }}" target="_blank" class="text-success ms-1" title="Chat on WhatsApp"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                    </div>

                                    <div class="fcc-renew-card-footer">
                                        <span class="fcc-renew-type-badge">{{ $userType }} ({{ $userState }})</span>
                                        <a href="{{ route('nutritionPanel.users.addUserDays', ['id' => ev($m->id)]) }}" class="fcc-btn-renew">
                                            Renew
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fa fa-check-circle fa-2x text-success mb-2 d-block opacity-50"></i>
                                <span style="font-size: 13px; font-weight: 600;">No memberships due tomorrow!</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Column 3: Next 2–3 Days -->
                <div class="fcc-renew-column-box col-blue">
                    <div class="fcc-renew-col-header">
                        <div class="fcc-renew-col-title text-primary">
                            <i class="fa fa-calendar-o"></i>
                            <span>Next 2–3 days · {{ count($expiresNext23Members ?? []) }}</span>
                        </div>
                        <i class="fa fa-ellipsis-h text-muted" style="cursor: pointer;"></i>
                    </div>

                    <div class="fcc-renew-cards-stack" id="expiresNextCardsContainer">
                        @if(isset($expiresNext23Members) && count($expiresNext23Members) > 0)
                            @foreach($expiresNext23Members as $m)
                                @php
                                    $mName = ucfirst($m->name);
                                    $mInitials = strtoupper(substr($mName, 0, 1) . (str_contains($mName, ' ') ? substr(explode(' ', $mName)[1] ?? '', 0, 1) : ''));
                                    $coach = $m->coach_name ?? 'Club Coach';
                                    $phone = $m->mobile_number ?? '70239 19979';
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                                    $userType = $m->user_type ?? 'Regular';
                                    $userState = $m->user_state ?? 'Offline';
                                @endphp
                                <div class="fcc-renew-card renew-card-item" data-name="{{ strtolower($mName) }}" data-coach="{{ strtolower($coach) }}">
                                    <div class="fcc-renew-card-top">
                                        <div class="fcc-renew-card-left">
                                            <span class="fcc-renew-drag-dots"><i class="fa fa-ellipsis-v me-0.5"></i><i class="fa fa-ellipsis-v"></i></span>
                                            <div class="form-check p-0 m-0">
                                                <input class="form-check-input renew-member-checkbox m-0" type="checkbox" value="{{ $m->id }}">
                                            </div>
                                            <div class="fcc-renew-avatar blue">{{ $mInitials ?: 'N' }}</div>
                                            <div>
                                                <div class="fcc-renew-mname">{{ $mName }}</div>
                                                <div class="fcc-renew-mcoach">{{ $coach }}</div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fcc-renew-days-pill blue">{{ $m->days }} days left</div>
                                            <div class="text-muted" style="font-size: 11px;">Not contacted</div>
                                        </div>
                                    </div>

                                    <div class="fcc-renew-phone">
                                        <i class="fa fa-phone me-1"></i>
                                        <span>{{ $phone }}</span>
                                        @if(!empty($cleanPhone))
                                            <a href="https://wa.me/91{{ $cleanPhone }}" target="_blank" class="text-success ms-1" title="Chat on WhatsApp"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                    </div>

                                    <div class="fcc-renew-card-footer">
                                        <span class="fcc-renew-type-badge">{{ $userType }} ({{ $userState }})</span>
                                        <a href="{{ route('nutritionPanel.users.addUserDays', ['id' => ev($m->id)]) }}" class="fcc-btn-renew">
                                            Renew
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fa fa-check-circle fa-2x text-success mb-2 d-block opacity-50"></i>
                                <span style="font-size: 13px; font-weight: 600;">No renewals upcoming in 2–3 days!</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Column 4: Follow-up Assistant -->
                <div class="fcc-assistant-card">
                    <div class="fcc-assistant-head">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa fa-magic text-primary" style="font-size: 16px;"></i>
                            <span>Follow-up assistant</span>
                        </div>
                        <i class="fa fa-chevron-up text-muted" style="font-size: 12px; cursor: pointer;"></i>
                    </div>

                    <div>
                        <div class="fw-bold text-dark mb-2.5" style="font-size: 14px;">Today's plan</div>

                        <div class="fcc-plan-checklist">
                            <label class="fcc-plan-check-item">
                                <input type="checkbox" checked />
                                <span>Call {{ max(1, count($expiresTodayMembers ?? [])) }} urgent members</span>
                            </label>
                            <label class="fcc-plan-check-item">
                                <input type="checkbox" checked />
                                <span>Send {{ max(1, count($expiresTomorrowMembers ?? [])) }} reminders</span>
                            </label>
                            <label class="fcc-plan-check-item">
                                <input type="checkbox" />
                                <span>Review payment links</span>
                            </label>
                        </div>

                        <!-- Progress Line -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1" style="font-size: 11.5px; color: #64748b; font-weight: 600;">
                                <span>Progress</span>
                                <span>2 of 3 completed</span>
                            </div>
                            <div class="progress" style="height: 6px; border-radius: 999px; background: #e2e8f0;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 66%; border-radius: 999px;"></div>
                            </div>
                        </div>

                        <button type="button" class="btn fcc-btn-start-followup" id="startFollowupBtn">
                            Start follow-up
                        </button>

                        <div class="fcc-reminder-pill-box">
                            <div class="fcc-reminder-bell-icon">
                                <i class="fa fa-bell-o"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 12.5px;">Next reminder</div>
                                <div class="text-muted" style="font-size: 11.5px;">at 6:00 PM</div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- 4. BOTTOM HORIZONTAL RECENT RENEWAL ACTIVITY STRIP -->
            <div class="fcc-renew-flow-card">
                <div class="fcc-renew-flow-head">
                    <h4 class="fw-bold mb-0" style="color: #0f172a; font-size: 15px;">Recent renewal activity</h4>
                    <a href="{{ route('nutritionPanel.attendance-register.index') }}" class="text-primary fw-bold" style="font-size: 12.5px; text-decoration: none;">
                        <span>View all activity</span>
                        <i class="fa fa-chevron-right" style="font-size: 10px;"></i>
                    </a>
                </div>

                <div class="fcc-flow-nodes-row">
                    <!-- Node 1 -->
                    <div class="fcc-flow-node-item">
                        <div class="fcc-flow-node-icon green">
                            <i class="fa fa-credit-card"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 13.5px;">Rahul Sharma renewed</div>
                            <div class="text-muted" style="font-size: 12px;">₹3,200 · 18 mins ago</div>
                        </div>
                    </div>

                    <div class="fcc-flow-node-line"></div>

                    <!-- Node 2 -->
                    <div class="fcc-flow-node-item">
                        <div class="fcc-flow-node-icon blue">
                            <i class="fa fa-link"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 13.5px;">Payment link sent to Neha Patel</div>
                            <div class="text-muted" style="font-size: 12px;">₹2,500 · 1 hour ago</div>
                        </div>
                    </div>

                    <div class="fcc-flow-node-line"></div>

                    <!-- Node 3 -->
                    <div class="fcc-flow-node-item">
                        <div class="fcc-flow-node-icon purple">
                            <i class="fa fa-envelope-o"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 13.5px;">Reminder delivered to Mala</div>
                            <div class="text-muted" style="font-size: 12px;">32 mins ago</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- TAB 4: GROWTH -->
        <div id="tab-growth" class="fcc-tab-panel">
            
            <!-- Filter Card -->
            <div class="fcc-leaderboard-card mb-4" style="padding: 24px;">
                <h4 style="color: #4338ca; font-weight: 700; font-size: 16px; text-align: center; margin-bottom: 16px;">
                    Shake Count Income & Expense and User Graph {{ $year ?? date('Y') }}
                </h4>
                <form action="{{ route('nutritionPanel.dashboard') }}" method="GET" class="d-flex align-items-end justify-content-center gap-3">
                    <input type="hidden" name="tab" value="tab-growth">
                    <div>
                        <label style="font-size: 12.5px; color: #64748b; font-weight: 600; margin-bottom: 4px; display: block;">Year</label>
                        <select name="year_filter" class="form-select form-control" style="width: 220px; border-radius: 8px; border: 1px solid #cbd5e1; height: 38px; font-weight: 600; font-size: 13.5px;">
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ (($year ?? date('Y')) == $y) ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary px-4" style="background: #3b46f1; border: none; border-radius: 8px; font-weight: 700; height: 38px; font-size: 13.5px;">Apply</button>
                </form>
            </div>

            <!-- 2 Graph Cards in a Row -->
            <div class="row g-4 mb-4">
                <div class="col-xl-6 col-12">
                    <div class="fcc-leaderboard-card h-100 mb-0" style="padding: 24px;">
                        <h4 style="color: #4338ca; font-size: 16px; font-weight: 700; margin-bottom: 20px;">
                            Bar Graph Representation of Shake Count {{ $year ?? date('Y') }}
                        </h4>
                        <div id="shakeCountGraph"></div>
                    </div>
                </div>
                <div class="col-xl-6 col-12">
                    <div class="fcc-leaderboard-card h-100 mb-0" style="padding: 24px;">
                        <h4 style="color: #4338ca; font-size: 16px; font-weight: 700; margin-bottom: 20px;">
                            Lines Graph Representation of Demo, 3 Days & Regular User Count {{ $year ?? date('Y') }}
                        </h4>
                        <div id="revenueMonthly"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: FINANCE & REVENUE -->
        <div id="tab-finance" class="fcc-tab-panel">
            
            <!-- Finance Metrics Row -->
            <div class="fcc-metrics-row mb-4">
                <!-- 1. Monthly Revenue -->
                <div class="fcc-metric-card">
                    <div class="fcc-metric-left">
                        <div class="fcc-metric-circle mc-purple">
                            <i class="fa fa-inr"></i>
                        </div>
                        <div>
                            <div class="fcc-metric-num">₹ {{ number_format($thisMonthRevenue ?? 0, 0) }}</div>
                            <div class="fcc-metric-label">{{ date('F') }} total revenue</div>
                        </div>
                    </div>
                    <span class="badge text-white rounded-pill px-2.5 py-1" style="font-size: 11px; background: #8b5cf6;">This Month</span>
                </div>

                <!-- 2. Today's Collections -->
                <div class="fcc-metric-card">
                    <div class="fcc-metric-left">
                        <div class="fcc-metric-circle mc-green">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="fcc-metric-num">₹ {{ number_format($todayCollected ?? 0, 0) }}</div>
                            <div class="fcc-metric-label">today's collected</div>
                        </div>
                    </div>
                    <span class="badge bg-success rounded-pill px-2.5 py-1" style="font-size: 11px;">Today</span>
                </div>

                <!-- 3. Orders Placed Income -->
                <div class="fcc-metric-card">
                    <div class="fcc-metric-left">
                        <div class="fcc-metric-circle mc-blue">
                            <i class="fa fa-shopping-bag"></i>
                        </div>
                        <div>
                            <div class="fcc-metric-num">₹ {{ number_format(array_sum($transactionOrderPlacedChartData ?? []), 0) }}</div>
                            <div class="fcc-metric-label">{{ $year ?? date('Y') }} product orders</div>
                        </div>
                    </div>
                    <span class="badge bg-primary rounded-pill px-2.5 py-1" style="font-size: 11px;">Orders</span>
                </div>

                <!-- 4. User Days Revenue -->
                <div class="fcc-metric-card">
                    <div class="fcc-metric-left">
                        <div class="fcc-metric-circle mc-rose">
                            <i class="fa fa-calendar-plus-o"></i>
                        </div>
                        <div>
                            <div class="fcc-metric-num">₹ {{ number_format(array_sum($transactionAddUserChartData ?? []), 0) }}</div>
                            <div class="fcc-metric-label">{{ $year ?? date('Y') }} user days fees</div>
                        </div>
                    </div>
                    <span class="badge bg-danger rounded-pill px-2.5 py-1" style="font-size: 11px;">Memberships</span>
                </div>
            </div>

            <!-- Revenue & Transactions Graph -->
            <div class="fcc-white-card mb-4">
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 pb-3 border-bottom">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: #0f172a; font-size: 18px;">
                            <i class="fa fa-line-chart text-primary me-2"></i>Revenue &amp; Product Transactions ({{ $year ?? date('Y') }})
                        </h4>
                        <p class="text-muted mb-0" style="font-size: 13px;">Month-by-month financial comparison between order income and user days fees</p>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <span class="badge px-3 py-1.5" style="background: #3b82f6; font-size: 12px; font-weight: 600;">Income (Orders Placed)</span>
                        <span class="badge px-3 py-1.5" style="background: #ef4444; font-size: 12px; font-weight: 600;">Revenue (Add User Days)</span>
                    </div>
                </div>
                <div id="incomeExpenseGraph"></div>
            </div>

            <!-- Pending Payments Card -->
            @if(isset($paymentPendings) && count($paymentPendings) > 0)
                <div class="fcc-white-card">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <div>
                            <h4 class="fw-bold mb-1" style="color: #0f172a; font-size: 16px;">
                                <i class="fa fa-clock-o text-warning me-2"></i>Pending Payments &amp; Due Balances
                            </h4>
                            <p class="text-muted mb-0" style="font-size: 12.5px;">Members with pending fees or renewals</p>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold">{{ count($paymentPendings) }} Pending</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: #f8fafc; font-size: 12px; font-weight: 700; color: #475569;">
                                <tr>
                                    <th style="padding: 10px 14px;">Member Name</th>
                                    <th style="padding: 10px 14px;">Contact</th>
                                    <th style="padding: 10px 14px;">Expiry Date</th>
                                    <th style="padding: 10px 14px; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentPendings as $pending)
                                    <tr>
                                        <td style="padding: 12px 14px; font-weight: 700; color: #0f172a;">
                                            <i class="fa fa-user-circle text-primary me-2"></i>{{ ucfirst($pending->name) }}
                                        </td>
                                        <td style="padding: 12px 14px; color: #64748b; font-size: 13px;">
                                            {{ $pending->phone ? (str_starts_with($pending->phone, '+') ? $pending->phone : '+91 ' . $pending->phone) : 'N/A' }}
                                        </td>
                                        <td style="padding: 12px 14px; font-size: 13px; color: #dc2626; font-weight: 600;">
                                            {{ $pending->expiry_date ? date('d M Y', strtotime($pending->expiry_date)) : 'Expired' }}
                                        </td>
                                        <td style="padding: 12px 14px; text-align: right;">
                                            <a href="{{ route('nutritionPanel.users.edit', ['id' => ev($pending->id)]) }}" class="btn btn-sm btn-outline-primary px-3" style="border-radius: 8px; font-weight: 600; font-size: 12px;">
                                                Update Days
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

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
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ATTENDANCE QR CODE MODAL -->
<div class="modal fade" id="qrAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content" style="border-radius: 22px; border: 1px solid #e2e8f0; box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25); overflow: hidden;">
            <div class="modal-header border-0 pb-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 42px; height: 42px; border-radius: 12px; background: #eef2ff; color: #3b46f1; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fa fa-qrcode"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="color: #0f172a; font-size: 17px;">Attendance QR Code</h5>
                        <p class="text-muted mb-0" style="font-size: 12px;">Scan to mark daily club attendance</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body px-4 py-3">
                <div class="text-center p-3" style="background: #f8fafc; border-radius: 18px; border: 1px dashed #cbd5e1;">
                    <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 mb-2.5 rounded-pill" style="background: #dcfce7; color: #15803d; font-size: 11.5px; font-weight: 700;">
                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #22c55e; display: inline-block;"></span>
                        Active Attendance Pass
                    </div>
                    <h6 class="fw-bold mb-1" style="color: #0f172a; font-size: 16px;">{{ $authUser->name ?? 'Fit Coach Club' }}</h6>
                    <p class="text-muted mb-3" style="font-size: 12px;">Members scan this pass using their mobile app</p>

                    <div class="d-flex justify-content-center my-2">
                        <div style="background: #ffffff; padding: 16px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; display: inline-block;">
                            <div id="qr-container" style="display: flex; justify-content: center; align-items: center; min-width: 190px; min-height: 190px;">
                                <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                                    <span class="visually-hidden">Loading QR...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 text-muted" style="font-size: 11.5px;">
                        <i class="fa fa-info-circle text-primary me-1"></i> Valid for all club members to mark daily attendance &amp; shake
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="button" id="downloadBtn" class="btn btn-primary flex-fill fw-bold py-2" style="border-radius: 12px; font-size: 13px; background: #3b46f1; border-color: #3b46f1;">
                        <i class="fa fa-download me-1.5"></i> Download QR
                    </button>
                    <button type="button" id="printQrBtn" class="btn btn-outline-secondary flex-fill fw-bold py-2" style="border-radius: 12px; font-size: 13px;">
                        <i class="fa fa-print me-1.5"></i> Print Pass
                    </button>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0 pb-3 px-4 d-flex justify-content-between align-items-center" style="background: #f8fafc !important; border-top: 1px solid #f1f5f9 !important;">
                <div class="d-flex gap-2">
                    <a href="{{ route('nutritionPanel.attendance-register.index') }}" class="btn btn-link text-decoration-none p-0 fw-semibold text-primary" style="font-size: 12.5px;">
                        <i class="fa fa-list-alt me-1"></i> Attendance Register
                    </a>
                </div>
                <button type="button" class="btn btn-light px-3 py-1.5" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600; font-size: 12.5px;">Close</button>
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

    // Auto switch to active tab from URL query param if present
    const urlParams = new URLSearchParams(window.location.search);
    const activeTabParam = urlParams.get('tab');
    if (activeTabParam && $('#' + activeTabParam).length) {
        $('.fcc-tab-btn').removeClass('active');
        $('.fcc-tab-panel').removeClass('active');
        $(`.fcc-tab-btn[data-tab="${activeTabParam}"]`).addClass('active');
        $('#' + activeTabParam).addClass('active');
    }



    // 1. Club Pulse Area Line Chart
    var weeklyPulseLabels = {!! json_encode($weeklyPulseLabels ?? ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7']) !!};
    var weeklyPulseAttendance = {!! json_encode($weeklyPulseAttendance ?? [0, 0, 0, 0, 0, 0, 0]) !!};
    var weeklyPulseRevenue = {!! json_encode($weeklyPulseRevenue ?? [0, 0, 0, 0, 0, 0, 0]) !!};

    var pulseOptions = {
        chart: {
            type: 'area',
            height: 155,
            toolbar: { show: false },
            parentHeightOffset: 0
        },
        series: [{
            name: 'Attendance',
            data: weeklyPulseAttendance
        }],
        xaxis: {
            categories: weeklyPulseLabels,
            labels: {
                style: {
                    colors: 'rgba(255, 255, 255, 0.8)',
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
        annotations: {
            yaxis: [{
                y: 70,
                borderColor: 'rgba(255, 255, 255, 0.35)',
                strokeDashArray: 4,
                label: {
                    text: 'Target (70)',
                    borderColor: 'transparent',
                    style: {
                        color: 'rgba(255, 255, 255, 0.8)',
                        background: 'transparent',
                        fontSize: '10.5px',
                        fontWeight: 600
                    },
                    position: 'right',
                    textAnchor: 'end'
                }
            }]
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
        grid: {
            borderColor: 'rgba(255, 255, 255, 0.1)',
            strokeDashArray: 3,
            padding: { top: 0, right: 20, bottom: 0, left: 5 }
        },
        tooltip: {
            theme: 'dark',
            y: { formatter: function(val) { return val + ' members'; } }
        }
    };
    var pulseElem = document.querySelector("#clubPulseChart");
    if (pulseElem) {
        new ApexCharts(pulseElem, pulseOptions).render();
    }

    // 2. Performance Story Area Line Chart
    var storyOptions = {
        chart: {
            type: 'area',
            height: 195,
            toolbar: { show: false },
            parentHeightOffset: 0
        },
        series: [{
            name: 'Attendance',
            data: weeklyPulseAttendance
        }],
        colors: ['#3b46f1'],
        xaxis: {
            categories: weeklyPulseLabels,
            labels: {
                style: {
                    colors: '#94a3b8',
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
                    colors: '#94a3b8',
                    fontSize: '10px'
                },
                formatter: function(val) {
                    return Math.round(val);
                }
            }
        },
        annotations: {
            yaxis: [{
                y: 70,
                borderColor: '#3b82f6',
                strokeDashArray: 4,
                label: {
                    text: 'Target (70)',
                    borderColor: 'transparent',
                    style: {
                        color: '#3b82f6',
                        background: 'transparent',
                        fontSize: '10.5px',
                        fontWeight: 600
                    },
                    position: 'right',
                    textAnchor: 'end'
                }
            }]
        },
        stroke: {
            curve: 'smooth',
            width: 3,
            colors: ['#3b46f1']
        },
        markers: {
            size: 4,
            colors: ['#ffffff'],
            strokeColors: '#3b46f1',
            strokeWidth: 2.5,
            hover: { size: 6 }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.4,
                opacityFrom: 0.4,
                opacityTo: 0.03,
                stops: [0, 100]
            }
        },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 3,
            padding: { top: 0, right: 20, bottom: 0, left: 5 }
        },
        tooltip: {
            theme: 'light',
            y: { formatter: function(val) { return val + ' members'; } }
        }
    };

    var storyChartElem = document.querySelector("#performanceStoryChart");
    var performanceStoryChart = null;
    if (storyChartElem) {
        performanceStoryChart = new ApexCharts(storyChartElem, storyOptions);
        performanceStoryChart.render();
    }

    $('#storyToggleAttendance').on('click', function() {
        $(this).addClass('active');
        $('#storyToggleRevenue').removeClass('active');
        if (performanceStoryChart) {
            performanceStoryChart.updateOptions({
                series: [{ name: 'Attendance', data: weeklyPulseAttendance }],
                yaxis: {
                    min: 0,
                    max: 100,
                    tickAmount: 4,
                    labels: {
                        style: { colors: '#94a3b8', fontSize: '10px' },
                        formatter: function(val) { return Math.round(val); }
                    }
                },
                annotations: {
                    yaxis: [{
                        y: 70,
                        borderColor: '#3b82f6',
                        strokeDashArray: 4,
                        label: {
                            text: 'Target (70)',
                            borderColor: 'transparent',
                            style: { color: '#3b82f6', background: 'transparent', fontSize: '10.5px', fontWeight: 600 },
                            position: 'right',
                            textAnchor: 'end'
                        }
                    }]
                },
                tooltip: {
                    y: { formatter: function(val) { return val + ' members'; } }
                }
            });
        }
    });

    $('#storyToggleRevenue').on('click', function() {
        $(this).addClass('active');
        $('#storyToggleAttendance').removeClass('active');
        if (performanceStoryChart) {
            performanceStoryChart.updateOptions({
                series: [{ name: 'Revenue', data: weeklyPulseRevenue }],
                yaxis: {
                    min: 0,
                    tickAmount: 4,
                    labels: {
                        style: { colors: '#94a3b8', fontSize: '10px' },
                        formatter: function(val) { return '₹' + Number(val).toLocaleString('en-IN'); }
                    }
                },
                annotations: {
                    yaxis: []
                },
                tooltip: {
                    y: { formatter: function(val) { return '₹' + Number(val).toLocaleString('en-IN'); } }
                }
            });
        }
    });



    // 3. Yearly Analytics Charts (Growth Tab)
    var shakeCount = {!! json_encode($totalShakeChartData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!};
    var shakeElem = document.querySelector("#shakeCountGraph");
    if (shakeElem) {
        new ApexCharts(shakeElem, {
            chart: { height: 320, type: 'bar', fontFamily: 'Plus Jakarta Sans, sans-serif', toolbar: { show: false } },
            colors: ['#6366f1'],
            plotOptions: { bar: { horizontal: false, columnWidth: '40%', borderRadius: 8 } },
            dataLabels: { enabled: false },
            series: [{ name: 'Shake Count', data: shakeCount }],
            xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.25,
                    opacityFrom: 0.95,
                    opacityTo: 0.65,
                    stops: [0, 100]
                }
            }
        }).render();
    }

    var userDemoChartData = {!! json_encode($userDemoChartData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!};
    var userTrailChartData = {!! json_encode($userTrailChartData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!};
    var userRegualrChartData = {!! json_encode($userRegualrChartData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!};

    var userGrowthElem = document.querySelector("#revenueMonthly");
    if (userGrowthElem) {
        new ApexCharts(userGrowthElem, {
            chart: { fontFamily: 'Plus Jakarta Sans, sans-serif', height: 320, type: 'line', toolbar: { show: false } },
            colors: ['#3b82f6', '#ef4444', '#10b981'],
            dataLabels: { enabled: false },
            stroke: { show: true, curve: 'smooth', width: 2.5 },
            series: [
                { name: 'Demo', data: userDemoChartData },
                { name: '3 Days', data: userTrailChartData },
                { name: 'Regular Users', data: userRegualrChartData }
            ],
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                markers: { radius: 12 }
            },
            xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] }
        }).render();
    }

    var transactionAddUserChartData = {!! json_encode($transactionAddUserChartData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!};
    var transactionOrderPlacedChartData = {!! json_encode($transactionOrderPlacedChartData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!};

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

    // 3.5 Attendance Tab Trend & Radial Gauge Charts
    var trendLabels = {!! json_encode($monthAttendanceTrendLabels ?? ['Aug 1', 'Aug 8', 'Aug 15', 'Aug 22', 'Aug 29']) !!};
    var trendData = {!! json_encode($monthAttendanceTrendData ?? [50, 68, 60, 52, 64]) !!};

    var trendOptions = {
        chart: {
            type: 'area',
            height: 140,
            toolbar: { show: false },
            parentHeightOffset: 0
        },
        series: [{
            name: 'Attendance',
            data: trendData
        }],
        colors: ['#3b46f1'],
        xaxis: {
            categories: trendLabels,
            labels: {
                style: {
                    colors: '#94a3b8',
                    fontSize: '10px',
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
                style: { colors: '#94a3b8', fontSize: '10px' },
                formatter: function(val) { return Math.round(val); }
            }
        },
        annotations: {
            yaxis: [{
                y: 70,
                borderColor: '#3b82f6',
                strokeDashArray: 4,
                label: {
                    text: 'Target 70',
                    borderColor: 'transparent',
                    style: {
                        color: '#3b82f6',
                        background: 'transparent',
                        fontSize: '10px',
                        fontWeight: 600
                    },
                    position: 'right',
                    textAnchor: 'end'
                }
            }]
        },
        stroke: { curve: 'smooth', width: 2.5, colors: ['#3b46f1'] },
        markers: {
            size: 3.5,
            colors: ['#ffffff'],
            strokeColors: '#3b46f1',
            strokeWidth: 2,
            hover: { size: 5.5 }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.3,
                opacityFrom: 0.35,
                opacityTo: 0.02,
                stops: [0, 100]
            }
        },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 3,
            padding: { top: 0, right: 10, bottom: 0, left: 5 }
        },
        tooltip: {
            theme: 'light',
            y: { formatter: function(val) { return val + ' members'; } }
        }
    };

    var trendElem = document.querySelector("#attendanceTrendChart");
    if (trendElem) {
        new ApexCharts(trendElem, trendOptions).render();
    }

    // Consistency Radial Gauge Chart
    var consistencyScoreVal = {{ $consistencyScore ?? 76 }};
    var radialOptions = {
        chart: {
            height: 135,
            type: 'radialBar',
            sparkline: { enabled: true }
        },
        series: [consistencyScoreVal],
        plotOptions: {
            radialBar: {
                hollow: { size: '60%' },
                track: {
                    background: '#e0e7ff',
                    strokeWidth: '100%'
                },
                dataLabels: {
                    show: true,
                    name: { show: false },
                    value: {
                        fontSize: '19px',
                        fontWeight: '800',
                        color: '#0f172a',
                        offsetY: 7,
                        formatter: function(val) {
                            return val + '%';
                        }
                    }
                }
            }
        },
        colors: ['#2563eb'],
        stroke: { lineCap: 'round' }
    };

    var radialElem = document.querySelector("#consistencyRadialChart");
    if (radialElem) {
        new ApexCharts(radialElem, radialOptions).render();
    }

    // Consistency Board Filter Switcher
    $(document).on('click', '.fcc-board-pill-btn', function() {
        $('.fcc-board-pill-btn').removeClass('active');
        $(this).addClass('active');

        var filter = $(this).data('board-filter');
        var cols = $('.fcc-board-cols-grid .fcc-board-col');

        if (filter === 'all') {
            cols.show();
        } else if (filter === 'top') {
            cols.eq(0).show();
            cols.eq(1).show();
            cols.eq(2).hide();
        } else if (filter === 'nudge') {
            cols.eq(0).hide();
            cols.eq(1).hide();
            cols.eq(2).show();
        }
    });

    // 3.8 Membership Renewals Search & Filter Logic
    $('#renewMemberSearchInput').on('keyup', function() {
        var query = $(this).val().toLowerCase().trim();
        $('.renew-card-item').each(function() {
            var name = $(this).data('name') || '';
            var coach = $(this).data('coach') || '';
            if (name.indexOf(query) > -1 || coach.indexOf(query) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#renewFilterCoachSelect').on('change', function() {
        var selectedCoach = $(this).val().toLowerCase().trim();
        $('.renew-card-item').each(function() {
            var coach = $(this).data('coach') || '';
            if (!selectedCoach || coach.indexOf(selectedCoach) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#sendBulkReminderBtn').on('click', function() {
        var checkedCount = $('.renew-member-checkbox:checked').length;
        if (checkedCount > 0) {
            alert('Bulk reminder scheduled for ' + checkedCount + ' selected member(s)!');
        } else {
            alert('Bulk reminders will be sent to all due soon members via WhatsApp/SMS.');
        }
    });

    $('#startFollowupBtn').on('click', function() {
        alert('Follow-up assistant activated! Calling queue initiated.');
    });

    // 4. Attendance QR Code Pass Logic
    const qrValue = "{{ $qr_code ?? '' }}";
    let qrRendered = false;

    function renderQrCode() {
        if (!qrValue || qrValue.trim() === "") return;
        const qrBox = document.getElementById("qr-container");
        if (qrBox) {
            qrBox.innerHTML = '';
            new QRCode(qrBox, {
                text: qrValue,
                width: 190,
                height: 190,
                colorDark: "#1e266d",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
            qrRendered = true;
        }
    }

    // Render immediately on load and also when modal opens
    renderQrCode();
    $('#qrAttendanceModal').on('shown.bs.modal', function () {
        if (!qrRendered || $('#qr-container canvas, #qr-container img').length === 0) {
            renderQrCode();
        }
    });

    // Download QR Code
    const downloadBtn = document.getElementById('downloadBtn');
    if (downloadBtn) {
        downloadBtn.onclick = function(){
            const canvas = document.querySelector('#qr-container canvas');
            const img = document.querySelector('#qr-container img');
            const link = document.createElement('a');
            link.download = 'attendance-qr-pass.png';
            if (canvas) {
                link.href = canvas.toDataURL("image/png");
                link.click();
            } else if (img && img.src) {
                link.href = img.src;
                link.click();
            }
        };
    }

    // Print QR Pass
    $('#printQrBtn').on('click', function() {
        const canvas = document.querySelector('#qr-container canvas');
        const img = document.querySelector('#qr-container img');
        let qrSrc = '';
        if (canvas) {
            qrSrc = canvas.toDataURL("image/png");
        } else if (img && img.src) {
            qrSrc = img.src;
        }

        const clubName = "{{ addslashes($authUser->name ?? 'Fit Coach Club') }}";
        const printWindow = window.open('', '_blank', 'width=600,height=700');
        if (printWindow) {
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Print Attendance QR - ${clubName}</title>
                    <style>
                        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; text-align: center; padding: 40px; background: #fff; color: #1e293b; }
                        .pass-card { border: 2.5px dashed #3b46f1; border-radius: 24px; padding: 36px 24px; max-width: 380px; margin: 0 auto; box-sizing: border-box; }
                        .club-name { font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0; }
                        .pass-subtitle { font-size: 13px; color: #64748b; margin: 0 0 24px 0; }
                        .qr-wrap { background: #fff; padding: 12px; border-radius: 16px; display: inline-block; border: 1px solid #e2e8f0; }
                        .qr-wrap img { display: block; }
                        .pass-badge { display: inline-block; background: #eef2ff; color: #3b46f1; font-weight: 700; font-size: 12px; padding: 5px 14px; border-radius: 20px; margin-top: 20px; }
                        .instructions { font-size: 11.5px; color: #94a3b8; margin-top: 16px; line-height: 1.5; }
                    </style>
                </head>
                <body>
                    <div class="pass-card">
                        <div class="club-name">${clubName}</div>
                        <div class="pass-subtitle">Daily Attendance &amp; Shake Check-In</div>
                        <div class="qr-wrap">
                            <img src="${qrSrc}" width="200" height="200" alt="Attendance QR Code" />
                        </div>
                        <br/>
                        <div class="pass-badge">Fit Coach Club Pass</div>
                        <div class="instructions">Open your Fit Coach Club Mobile App &gt; Tap Scan to check-in.</div>
                    </div>
                    <script>
                        window.onload = function() { window.print(); window.close(); };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }
    });
</script>
@endpush
