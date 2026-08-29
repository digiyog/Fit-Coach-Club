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
            <a class="fcc-tab-btn" data-tab="tab-coaches">Coaches <span class="badge rounded-pill bg-primary ms-1" style="font-size: 11px; padding: 2px 7px;">{{ count($coachesData ?? []) }}</span></a>
            <a class="fcc-tab-btn" data-tab="tab-top20">Top 20 Attendance 🏆</a>
            <a class="fcc-tab-btn" data-tab="tab-members">Members</a>
            <a class="fcc-tab-btn" data-tab="tab-growth">Growth</a>
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
                                <div class="fcc-pulse-pill-item" style="cursor: pointer; transition: all 0.2s ease;" onclick="$('.fcc-tab-btn[data-tab=\'tab-coaches\']').trigger('click');" title="Click to view all coaches">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.85;">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    <div>
                                        <div style="font-weight: 800; font-size: 16px; line-height: 1;">{{ $totalCoaches ?? 0 }}</div>
                                        <div style="font-size: 11px; opacity: 0.85; font-weight: 600;">coaches <i class="fa fa-arrow-right" style="font-size: 9px; margin-left: 2px;"></i></div>
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

        <!-- TAB: ALL COACHES LIST & PERFORMANCE -->
        <div id="tab-coaches" class="fcc-tab-panel">
            
            <!-- Coach Metrics Row -->
            <div class="fcc-metrics-row mb-4">
                <div class="fcc-metric-card">
                    <div class="fcc-metric-left">
                        <div class="fcc-metric-circle mc-blue">
                            <i class="fa fa-users"></i>
                        </div>
                        <div>
                            <div class="fcc-metric-num">{{ count($coachesData ?? []) }}</div>
                            <div class="fcc-metric-label">total active coaches</div>
                        </div>
                    </div>
                    <span class="badge bg-primary rounded-pill px-2.5 py-1" style="font-size: 11px;">Coaches</span>
                </div>

                <div class="fcc-metric-card">
                    <div class="fcc-metric-left">
                        <div class="fcc-metric-circle mc-purple">
                            <i class="fa fa-user-plus"></i>
                        </div>
                        <div>
                            <div class="fcc-metric-num">{{ $coachesData->sum('total_members') ?? 0 }}</div>
                            <div class="fcc-metric-label">assigned members</div>
                        </div>
                    </div>
                    <span class="badge bg-info rounded-pill px-2.5 py-1" style="font-size: 11px;">Clients</span>
                </div>

                <div class="fcc-metric-card">
                    <div class="fcc-metric-left">
                        <div class="fcc-metric-circle mc-green">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="fcc-metric-num">{{ $coachesData->sum('active_members') ?? 0 }}</div>
                            <div class="fcc-metric-label">active clients</div>
                        </div>
                    </div>
                    <span class="badge bg-success rounded-pill px-2.5 py-1" style="font-size: 11px;">Active</span>
                </div>

                <div class="fcc-metric-card">
                    <div class="fcc-metric-left">
                        <div class="fcc-metric-circle mc-blue">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                        <div>
                            <div class="fcc-metric-num">{{ $coachMonthlyAttendance->sum() ?? 0 }}</div>
                            <div class="fcc-metric-label">{{ date('F') }} check-ins</div>
                        </div>
                    </div>
                    <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1" style="font-size: 11px;">This Month</span>
                </div>
            </div>

            @if(isset($unassignedMembersCount) && $unassignedMembersCount > 0)
                <div class="alert alert-warning border-0 rounded-4 d-flex align-items-center justify-content-between p-3 mb-4" style="background: #fffbeb; border: 1px solid #fde68a !important; color: #92400e;">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa fa-exclamation-circle fa-2x text-warning"></i>
                        <div>
                            <strong style="font-size: 14px;">{{ $unassignedMembersCount }} Unassigned Members Found</strong>
                            <div style="font-size: 12.5px; opacity: 0.9;">These members do not have a coach assigned yet. You can assign coaches in member edit pages.</div>
                        </div>
                    </div>
                    <a href="{{ route('nutritionPanel.users.index') }}" class="btn btn-sm btn-warning text-dark fw-bold px-3 py-1.5" style="border-radius: 8px;">View Members</a>
                </div>
            @endif

            <!-- Top Coaches Highlights Cards -->
            @if(isset($coachesData) && count($coachesData) > 0)
                <div class="row g-3 mb-4">
                    @foreach($coachesData->take(3) as $cIndex => $topCoach)
                        @php
                            $cName = $topCoach->coach_name ?? 'Coach';
                            $cInitials = strtoupper(substr($cName, 0, 1) . (str_contains($cName, ' ') ? substr(explode(' ', $cName)[1] ?? '', 0, 1) : ''));
                            $cTotal = $topCoach->total_members ?? 0;
                            $cActive = $topCoach->active_members ?? 0;
                            $cPct = $cTotal > 0 ? round(($cActive / $cTotal) * 100) : 0;
                            $cAtt = $coachMonthlyAttendance[$cName] ?? 0;
                            $cRev = $coachMonthlyRevenue[$cName] ?? 0;
                        @endphp
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="fcc-coach-card h-100">
                                <div>
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="fcc-coach-avatar-lg" style="background: {{ $cIndex == 0 ? 'linear-gradient(135deg, #3b46f1 0%, #1e1b4b 100%)' : ($cIndex == 1 ? 'linear-gradient(135deg, #059669 0%, #064e3b 100%)' : 'linear-gradient(135deg, #7c3aed 0%, #4c1d95 100%)') }};">
                                                {{ $cInitials ?: 'C' }}
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-1" style="color: #0f172a; font-size: 16px;">{{ $cName }}</h5>
                                                <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 11.5px; font-weight: 600;">
                                                    Coach #{{ $cIndex + 1 }}
                                                </span>
                                            </div>
                                        </div>
                                        @if($cIndex == 0)
                                            <span class="badge bg-warning text-dark px-2.5 py-1" style="border-radius: 8px; font-weight: 800; font-size: 12px;">🥇 Top Coach</span>
                                        @elseif($cIndex == 1)
                                            <span class="badge bg-secondary px-2.5 py-1" style="border-radius: 8px; font-weight: 800; font-size: 12px;">🥈 #2</span>
                                        @elseif($cIndex == 2)
                                            <span class="badge bg-light text-dark border px-2.5 py-1" style="border-radius: 8px; font-weight: 800; font-size: 12px;">🥉 #3</span>
                                        @endif
                                    </div>

                                    <!-- Quick Stats Grid -->
                                    <div class="row g-2 text-center my-3">
                                        <div class="col-4">
                                            <div class="p-2 rounded-3" style="background: #f8fafc; border: 1px solid #f1f5f9;">
                                                <div class="fw-bold text-dark" style="font-size: 16px;">{{ $cTotal }}</div>
                                                <div class="text-muted" style="font-size: 11px; font-weight: 500;">Clients</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-2 rounded-3" style="background: #ecfdf5; border: 1px solid #d1fae5;">
                                                <div class="fw-bold text-success" style="font-size: 16px;">{{ $cActive }}</div>
                                                <div class="text-muted" style="font-size: 11px; font-weight: 500;">Active</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-2 rounded-3" style="background: #eff6ff; border: 1px solid #dbeafe;">
                                                <div class="fw-bold text-primary" style="font-size: 16px;">{{ $cAtt }}</div>
                                                <div class="text-muted" style="font-size: 11px; font-weight: 500;">Check-ins</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Active Ratio Bar -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 12px;">
                                            <span class="text-muted font-weight-500">Client Retention</span>
                                            <span class="fw-bold text-dark">{{ $cPct }}% Active</span>
                                        </div>
                                        <div class="progress" style="height: 6px; border-radius: 10px; background: #e2e8f0;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $cPct }}%; border-radius: 10px;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                                    <div style="font-size: 12px; color: #64748b;">
                                        Revenue: <strong class="text-dark">₹{{ number_format($cRev, 0) }}</strong>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary view-coach-members-btn" data-coach="{{ $cName }}" style="border-radius: 8px; font-weight: 700; font-size: 12px; padding: 5px 12px;">
                                        <i class="fa fa-users me-1"></i> View Members
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Full Coaches Table Card -->
            <div class="fcc-white-card mb-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-3 border-bottom">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: #0f172a; font-size: 18px;">
                            <i class="fa fa-id-badge text-primary me-2"></i>All Coaches Directory
                        </h4>
                        <p class="text-muted mb-0" style="font-size: 13px;">Overview of all club coaches, client assignments, attendance performance, and revenue</p>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;"><i class="fa fa-search text-muted"></i></span>
                            <input type="text" id="coachTableSearchInput" class="form-control border-start-0" placeholder="Filter coaches..." style="border-radius: 0 10px 10px 0;">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="allCoachesTable">
                        <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <tr style="font-size: 12px; color: #475569; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                                <th style="padding: 12px 14px; width: 60px;">#</th>
                                <th style="padding: 12px 14px;">Coach Name</th>
                                <th style="padding: 12px 14px; text-align: center;">Total Clients</th>
                                <th style="padding: 12px 14px; text-align: center;">Active Ratio</th>
                                <th style="padding: 12px 14px; text-align: center;">Month Check-ins</th>
                                <th style="padding: 12px 14px; text-align: right;">Month Revenue</th>
                                <th style="padding: 12px 14px; text-align: right;">Pending Due</th>
                                <th style="padding: 12px 14px; text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($coachesData) && count($coachesData) > 0)
                                @foreach($coachesData as $index => $coach)
                                    @php
                                        $cName = $coach->coach_name ?? 'Coach';
                                        $cInitials = strtoupper(substr($cName, 0, 1) . (str_contains($cName, ' ') ? substr(explode(' ', $cName)[1] ?? '', 0, 1) : ''));
                                        $cTotal = $coach->total_members ?? 0;
                                        $cActive = $coach->active_members ?? 0;
                                        $cInactive = $coach->inactive_members ?? 0;
                                        $cOnline = $coach->online_members ?? 0;
                                        $cOffline = $coach->offline_members ?? 0;
                                        $cPct = $cTotal > 0 ? round(($cActive / $cTotal) * 100) : 0;
                                        $cAtt = $coachMonthlyAttendance[$cName] ?? 0;
                                        $cRev = $coachMonthlyRevenue[$cName] ?? 0;
                                        $cDue = $coach->total_due_amount ?? 0;
                                    @endphp
                                    <tr class="coach-row">
                                        <td style="padding: 14px 14px; font-weight: 700; color: #64748b;">
                                            @if($index == 0)
                                                <span class="badge bg-warning text-dark rounded-circle p-1.5" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px;">1</span>
                                            @elseif($index == 1)
                                                <span class="badge bg-secondary text-white rounded-circle p-1.5" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px;">2</span>
                                            @elseif($index == 2)
                                                <span class="badge bg-dark text-white rounded-circle p-1.5" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px;">3</span>
                                            @else
                                                <span class="text-muted ms-1">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td style="padding: 14px 14px;">
                                            <div class="d-flex align-items-center">
                                                <div class="fcc-coach-avatar-sm">
                                                    {{ $cInitials ?: 'C' }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark coach-name-cell" style="font-size: 14px;">{{ $cName }}</div>
                                                    <div class="text-muted" style="font-size: 11.5px;">Coach</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 14px 14px; text-align: center;">
                                            <span class="badge bg-primary rounded-pill px-3 py-1.5" style="font-size: 12.5px; font-weight: 700;">{{ $cTotal }}</span>
                                            <div class="mt-1" style="font-size: 11px; color: #64748b;">
                                                <span class="text-success fw-bold">{{ $cOnline }} Online</span> · <span class="text-info fw-bold">{{ $cOffline }} Offline</span>
                                            </div>
                                        </td>
                                        <td style="padding: 14px 14px; text-align: center; min-width: 140px;">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <div class="progress flex-grow-1" style="height: 6px; max-width: 90px; border-radius: 10px; background: #fee2e2;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $cPct }}%; border-radius: 10px;"></div>
                                                </div>
                                                <span class="fw-bold text-dark" style="font-size: 12px;">{{ $cActive }}/{{ $cTotal }}</span>
                                            </div>
                                            <div style="font-size: 11px; color: #10b981; font-weight: 600;" class="mt-1">{{ $cPct }}% Active</div>
                                        </td>
                                        <td style="padding: 14px 14px; text-align: center;">
                                            <span class="badge" style="background: #eff6ff; color: #2563eb; font-size: 13px; font-weight: 700; padding: 6px 12px; border-radius: 8px;">
                                                <i class="fa fa-check-circle me-1"></i> {{ $cAtt }}
                                            </span>
                                        </td>
                                        <td style="padding: 14px 14px; text-align: right; font-weight: 800; color: #0f172a; font-size: 13.5px;">
                                            ₹{{ number_format($cRev, 0) }}
                                        </td>
                                        <td style="padding: 14px 14px; text-align: right;">
                                            @if($cDue > 0)
                                                <span class="badge bg-danger" style="font-size: 12px; font-weight: 700; padding: 5px 10px; border-radius: 6px;">₹{{ number_format($cDue, 0) }}</span>
                                            @else
                                                <span class="badge bg-light text-muted border" style="font-size: 11px;">₹0</span>
                                            @endif
                                        </td>
                                        <td style="padding: 14px 14px; text-align: right;">
                                            <button type="button" class="btn btn-sm btn-outline-primary view-coach-members-btn px-3 py-1.5" data-coach="{{ $cName }}" style="border-radius: 8px; font-weight: 700; font-size: 12px;">
                                                <i class="fa fa-users me-1"></i> View ({{ $cTotal }})
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa fa-user-times fa-3x mb-3 d-block opacity-25"></i>
                                        <h5 class="fw-bold text-dark">No coaches found</h5>
                                        <p class="mb-0" style="font-size: 13px;">When you assign coach names to your members, they will automatically be organized here.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

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

            <!-- Additional Member Insights: 3 Tables in a Row -->
            <div class="row g-3 mb-4">
                
                <!-- Table 1: Today's Birthdays -->
                <div class="col-xl-4 col-lg-4 col-12">
                    <div class="fcc-leaderboard-card h-100 mb-0 d-flex flex-column" id="today-birthday-section" style="padding: 20px;">
                        <div class="fcc-leaderboard-header mb-3 pb-2" style="border-bottom: 1px solid #f1f5f9;">
                            <div class="d-flex align-items-center gap-2">
                                <h3 class="fcc-leaderboard-title" style="color: #4338ca; font-size: 15px; font-weight: 700;">
                                    <span>Today Birthday</span>
                                    <span style="font-size: 18px;">🎂</span>
                                </h3>
                                @if(isset($thisMonthBirthdayUsers) && count($thisMonthBirthdayUsers) > 0)
                                    <span class="badge bg-primary rounded-pill px-2.5 py-1" style="font-size: 11px; font-weight: 700;">
                                        {{ count($thisMonthBirthdayUsers) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive flex-grow-1">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr style="font-size: 11.5px; color: #4338ca; font-weight: 800; border-bottom: 1px solid #e2e8f0; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <th style="padding: 8px 10px;">CUSTOMER NAME</th>
                                        <th style="padding: 8px 10px;">YEAR</th>
                                        <th style="padding: 8px 10px;">USER TYPE</th>
                                        @if(isset($thisMonthBirthdayUsers) && count($thisMonthBirthdayUsers) > 0)
                                            <th style="padding: 8px 10px; text-align: right;">ACTION</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($thisMonthBirthdayUsers) && count($thisMonthBirthdayUsers) > 0)
                                        @foreach($thisMonthBirthdayUsers as $bUser)
                                            <tr>
                                                <td style="padding: 10px 10px; font-weight: 700; color: #1e293b; font-size: 13px;">
                                                    {{ ucfirst($bUser->name) }}
                                                    @if($bUser->coach_name)
                                                        <div class="text-muted" style="font-size: 11px; font-weight: normal;">Coach: {{ $bUser->coach_name }}</div>
                                                    @endif
                                                </td>
                                                <td style="padding: 10px 10px; font-size: 12.5px; font-weight: 600; color: #475569;">
                                                    {{ date('Y', strtotime($bUser->date_of_birth)) }}
                                                </td>
                                                <td style="padding: 10px 10px;">
                                                    <span class="badge" style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 6px;">
                                                        {{ $bUser->user_type }}
                                                    </span>
                                                </td>
                                                <td style="padding: 10px 10px; text-align: right;">
                                                    @if(!empty($bUser->mobile_number))
                                                        <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $bUser->mobile_number) }}?text=Happy%20Birthday%20{{ urlencode($bUser->name) }}!%20Wishing%20you%20a%20healthy%20and%20fit%20year%20ahead!%20🎉" target="_blank" class="btn btn-sm btn-success px-2 py-1" style="border-radius: 6px; font-size: 11px; font-weight: 700;">
                                                            <i class="fa fa-whatsapp me-1"></i> Wish
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted" style="font-weight: 600; font-size: 13px;">
                                                No Record Found !!
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Table 2: More Than One Attendance Card -->
                <div class="col-xl-4 col-lg-4 col-12">
                    <div class="fcc-leaderboard-card h-100 mb-0 d-flex flex-column" style="padding: 20px;">
                        <div class="fcc-leaderboard-header mb-3 pb-2" style="border-bottom: 1px solid #f1f5f9;">
                            <div class="d-flex align-items-center gap-2">
                                <h3 class="fcc-leaderboard-title" style="color: #4338ca; font-size: 15px; font-weight: 700;">
                                    <span>More Than One Attendance</span>
                                </h3>
                                @if(isset($today2Attendences) && count($today2Attendences) > 0)
                                    <span class="badge bg-danger rounded-pill px-2.5 py-1" style="font-size: 11px;">
                                        {{ count($today2Attendences) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive flex-grow-1">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr style="font-size: 11.5px; color: #4338ca; font-weight: 800; border-bottom: 1px solid #e2e8f0; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <th style="padding: 8px 10px;">Name</th>
                                        <th style="padding: 8px 10px;">Date</th>
                                        <th style="padding: 8px 10px; text-align: center;">Count</th>
                                        <th style="padding: 8px 10px;">Coach</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($today2Attendences) && count($today2Attendences) > 0)
                                        @foreach($today2Attendences as $today2Attendence)
                                            <tr>
                                                <td style="padding: 10px 10px; font-weight: 700; color: #1e293b; font-size: 13px;">
                                                    {{ ucfirst($today2Attendence->name) }}
                                                </td>
                                                <td style="padding: 10px 10px; font-size: 12px; color: #64748b;">
                                                    {{ $today2Attendence->date }}
                                                </td>
                                                <td style="padding: 10px 10px; text-align: center;">
                                                    <span class="badge bg-danger px-2 py-0.5" style="border-radius: 6px; font-size: 11.5px; font-weight: 700;">
                                                        {{ $today2Attendence->total_attendance }}
                                                    </span>
                                                </td>
                                                <td style="padding: 10px 10px; font-size: 12px; color: #334155;">
                                                    {{ $today2Attendence->coach_name ?? 'N/A' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted" style="font-weight: 600; font-size: 13px;">
                                                No Record Found !!
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Table 3: Updation Logs Card -->
                <div class="col-xl-4 col-lg-4 col-12">
                    <div class="fcc-leaderboard-card h-100 mb-0 d-flex flex-column" style="padding: 20px;">
                        <div class="fcc-leaderboard-header mb-3 pb-2" style="border-bottom: 1px solid #f1f5f9;">
                            <div class="d-flex align-items-center gap-2">
                                <h3 class="fcc-leaderboard-title" style="color: #4338ca; font-size: 15px; font-weight: 700;">
                                    <span>Updation on {{ date('Y-m-d', strtotime($today ?? date('Y-m-d'))) }}</span>
                                </h3>
                                @if(isset($todayAttendences) && count($todayAttendences) > 0)
                                    <span class="badge bg-primary rounded-pill px-2.5 py-1" style="font-size: 11px;">
                                        {{ count($todayAttendences) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive flex-grow-1">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr style="font-size: 11.5px; color: #4338ca; font-weight: 800; border-bottom: 1px solid #e2e8f0; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <th style="padding: 8px 10px;">Name</th>
                                        <th style="padding: 8px 10px;">Remark</th>
                                        <th style="padding: 8px 10px; text-align: center;">Count</th>
                                        <th style="padding: 8px 10px;">Date</th>
                                        <th style="padding: 8px 10px;">Coach</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($todayAttendences) && count($todayAttendences) > 0)
                                        @foreach($todayAttendences as $todayAttendence)
                                            <tr>
                                                <td style="padding: 10px 10px; font-weight: 700; color: #1e293b; font-size: 13px;">
                                                    {{ ucfirst($todayAttendence->name) }}
                                                </td>
                                                <td style="padding: 10px 10px; font-size: 11.5px;">
                                                    <span class="badge bg-light text-dark border">{{ $todayAttendence->remark ?? 'Attendance' }}</span>
                                                </td>
                                                <td style="padding: 10px 10px; text-align: center; font-weight: 700; color: #3b82f6; font-size: 12px;">
                                                    1
                                                </td>
                                                <td style="padding: 10px 10px; font-size: 11.5px; color: #64748b;">
                                                    {{ $todayAttendence->date }}
                                                </td>
                                                <td style="padding: 10px 10px; font-size: 11.5px; color: #334155;">
                                                    {{ $todayAttendence->coach_name ?? 'N/A' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted" style="font-weight: 600; font-size: 13px;">
                                                No Record Found !!
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

<!-- COACH MEMBERS MODAL -->
<div class="modal fade" id="coachMembersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);">
            <div class="modal-header border-0 pb-2 pt-4 px-4">
                <div>
                    <h5 class="modal-title fw-bold" style="color: #0f172a; font-size: 18px;">
                        <i class="fa fa-id-card-o text-primary me-2"></i>Members under <span id="coachModalTitleName" class="text-primary"></span>
                    </h5>
                    <p class="text-muted mb-0" style="font-size: 13px;" id="coachModalSubtitle">Showing assigned members</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="mb-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0" style="border-radius: 8px 0 0 8px;"><i class="fa fa-search text-muted"></i></span>
                        <input type="text" id="modalMemberSearchInput" class="form-control border-start-0" placeholder="Search members in this coach list..." style="border-radius: 0 8px 8px 0; font-size: 13px;">
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" id="coachMembersTable">
                        <thead style="background: #f8fafc; position: sticky; top: 0; z-index: 2; border-bottom: 2px solid #e2e8f0;">
                            <tr style="font-size: 11.5px; color: #64748b; font-weight: 700; text-transform: uppercase;">
                                <th style="padding: 10px;">#</th>
                                <th style="padding: 10px;">Member</th>
                                <th style="padding: 10px;">Contact</th>
                                <th style="padding: 10px;">Type</th>
                                <th style="padding: 10px; text-align: center;">Days Left</th>
                                <th style="padding: 10px; text-align: right;">Due</th>
                                <th style="padding: 10px; text-align: center;">Status</th>
                                <th style="padding: 10px; text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="coachMembersTableBody">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex justify-content-between">
                <span class="text-muted" style="font-size: 12.5px;" id="coachModalCountText">0 members</span>
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ATTENDANCE QR CODE & SCANNER MODAL -->
<div class="modal fade" id="qrAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content" style="border-radius: 22px; border: 1px solid #e2e8f0; box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25); overflow: hidden;">
            <div class="modal-header border-0 pb-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: #eef2ff; color: #3b46f1; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fa fa-qrcode"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="color: #0f172a; font-size: 17px;">Attendance QR Pass</h5>
                        <p class="text-muted mb-0" style="font-size: 12px;">Scan or display QR code for daily check-in</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body px-4 py-3">
                <!-- Segmented Tabs for QR Pass vs Live Camera Scanner -->
                <div class="d-flex p-1 mb-3" style="background: #f1f5f9; border-radius: 12px;">
                    <button type="button" class="btn btn-sm flex-fill fw-bold bg-white text-dark shadow-sm qr-modal-tab-btn" data-target="#qrPassView" style="border-radius: 9px; font-size: 12.5px; padding: 7px 12px;">
                        <i class="fa fa-id-badge me-1 text-primary"></i> Club QR Pass
                    </button>
                    <button type="button" class="btn btn-sm flex-fill fw-bold text-muted qr-modal-tab-btn" data-target="#qrScannerView" style="border-radius: 9px; font-size: 12.5px; padding: 7px 12px;">
                        <i class="fa fa-camera me-1"></i> Camera Scanner
                    </button>
                </div>

                <!-- VIEW 1: QR PASS DISPLAY -->
                <div id="qrPassView" class="qr-modal-view-panel">
                    <div class="text-center p-3" style="background: #f8fafc; border-radius: 18px; border: 1px dashed #cbd5e1;">
                        <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 mb-2 rounded-pill" style="background: #dcfce7; color: #15803d; font-size: 11.5px; font-weight: 700;">
                            <span style="width: 7px; height: 7px; border-radius: 50%; background: #22c55e; display: inline-block;"></span>
                            Active Club Pass
                        </div>
                        <h6 class="fw-bold mb-1" style="color: #1e293b; font-size: 16px;">{{ $authUser->name ?? 'Fit Coach Club' }}</h6>
                        <p class="text-muted mb-3" style="font-size: 12px;">Members scan this pass on their mobile app</p>

                        <div class="d-flex justify-content-center my-2">
                            <div style="background: #ffffff; padding: 16px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; display: inline-block;">
                                <div id="qr-container" style="display: flex; justify-content: center; align-items: center; min-width: 170px; min-height: 170px;">
                                    <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                                        <span class="visually-hidden">Generating QR...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2 text-muted" style="font-size: 11.5px;">
                            <i class="fa fa-info-circle text-primary me-1"></i> Valid for all club members to record daily attendance &amp; shake
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

                <!-- VIEW 2: LIVE CAMERA SCANNER -->
                <div id="qrScannerView" class="qr-modal-view-panel d-none">
                    <div class="text-center p-3" style="background: #0f172a; border-radius: 18px; color: #fff;">
                        <div id="qr-reader" style="width: 100%; border-radius: 12px; overflow: hidden; background: #000; min-height: 230px;"></div>
                        <div id="scanner-status" class="mt-2 text-muted" style="font-size: 12px; color: #94a3b8 !important;">
                            <i class="fa fa-video-camera me-1"></i> Camera is idle. Click start to scan.
                        </div>
                        <div id="scanner-result-box" class="mt-2 p-2 rounded text-start d-none" style="background: #1e293b; border: 1px solid #334155;">
                            <div class="text-success fw-bold" style="font-size: 11px;"><i class="fa fa-check-circle me-1"></i> Scanned Result:</div>
                            <div id="scanner-result-text" class="text-white font-monospace text-break" style="font-size: 12px;"></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="button" id="btnStartScan" class="btn btn-success flex-fill fw-bold py-2" style="border-radius: 12px; font-size: 13px;">
                            <i class="fa fa-camera me-1.5"></i> Start Camera
                        </button>
                        <button type="button" id="btnStopScan" class="btn btn-danger flex-fill fw-bold py-2 d-none" style="border-radius: 12px; font-size: 13px;">
                            <i class="fa fa-stop-circle me-1.5"></i> Stop Camera
                        </button>
                    </div>
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
<script src="https://unpkg.com/html5-qrcode"></script>

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

    // Coach data for dynamic modal
    var allCoachMembers = {!! json_encode($coachMembers ?? []) !!};

    // Open Coach Members Modal
    $(document).on('click', '.view-coach-members-btn', function() {
        var coachName = $(this).data('coach');
        var members = allCoachMembers[coachName] || [];
        
        $('#coachModalTitleName').text(coachName);
        $('#coachModalSubtitle').text(members.length + ' member' + (members.length === 1 ? '' : 's') + ' assigned to ' + coachName);
        $('#coachModalCountText').text(members.length + ' total members assigned');
        $('#modalMemberSearchInput').val('');

        renderCoachMembersRows(members);

        $('#coachMembersModal').modal('show');
    });

    function renderCoachMembersRows(members) {
        var tbody = $('#coachMembersTableBody');
        tbody.empty();

        if (!members || members.length === 0) {
            tbody.append('<tr><td colspan="8" class="text-center py-4 text-muted">No members found under this coach.</td></tr>');
            return;
        }

        $.each(members, function(idx, m) {
            var mName = m.name ? m.name.charAt(0).toUpperCase() + m.name.slice(1) : 'Member';
            var mobile = m.mobile_number || 'N/A';
            var cleanPhone = mobile.replace(/[^0-9]/g, '');
            var userType = m.user_type || 'Regular User';
            var userState = m.user_state ? ' (' + m.user_state + ')' : '';
            var days = m.days !== undefined && m.days !== null ? m.days : 0;
            var daysBadge = days <= 3 ? 'bg-danger' : (days <= 10 ? 'bg-warning text-dark' : 'bg-success');
            var dueAmt = parseFloat(m.due_amount || 0);
            var statusBadge = m.status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
            var profileUrl = m.details_url || '#';

            var contactHtml = mobile !== 'N/A' && cleanPhone.length >= 10 ? 
                `<span>${mobile}</span> <a href="https://wa.me/91${cleanPhone}" target="_blank" class="text-success ms-1" title="Chat on WhatsApp"><i class="fa fa-whatsapp"></i></a>` : 
                `<span>${mobile}</span>`;

            var row = `
                <tr class="modal-member-row">
                    <td style="padding: 10px; font-weight: 600; color: #64748b;">${idx + 1}</td>
                    <td style="padding: 10px;">
                        <strong class="text-dark member-name-text">${mName}</strong>
                        <div class="text-muted" style="font-size: 11px;">ID: #${m.id}</div>
                    </td>
                    <td style="padding: 10px; font-size: 12.5px;">${contactHtml}</td>
                    <td style="padding: 10px;"><span class="badge bg-light text-dark border" style="font-size: 11px;">${userType}${userState}</span></td>
                    <td style="padding: 10px; text-align: center;"><span class="badge ${daysBadge}" style="font-size: 11.5px; font-weight: 700;">${days} Days</span></td>
                    <td style="padding: 10px; text-align: right; font-weight: 700; color: ${dueAmt > 0 ? '#ef4444' : '#64748b'};">₹${dueAmt.toLocaleString('en-IN')}</td>
                    <td style="padding: 10px; text-align: center;">${statusBadge}</td>
                    <td style="padding: 10px; text-align: right;">
                        <a href="${profileUrl}" class="btn btn-sm btn-light text-primary px-2 py-1" style="font-size: 11.5px; border-radius: 6px; font-weight: 700;">
                            <i class="fa fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Modal Member Search Filter
    $('#modalMemberSearchInput').on('keyup', function() {
        var query = $(this).val().toLowerCase().trim();
        $('#coachMembersTableBody tr.modal-member-row').each(function() {
            var rowText = $(this).text().toLowerCase();
            if (rowText.indexOf(query) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Coaches Table Filter
    $('#coachTableSearchInput').on('keyup', function() {
        var query = $(this).val().toLowerCase().trim();
        $('#allCoachesTable tbody tr.coach-row').each(function() {
            var rowText = $(this).text().toLowerCase();
            if (rowText.indexOf(query) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

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

    // 4. Attendance QR Pass & Scanner Logic
    const qrValue = "{{ $qr_code ?? '' }}";
    let qrRendered = false;

    function renderQrCode() {
        if (!qrValue || qrValue.trim() === "") return;
        const qrBox = document.getElementById("qr-container");
        if (qrBox) {
            qrBox.innerHTML = '';
            new QRCode(qrBox, {
                text: qrValue,
                width: 170,
                height: 170,
                colorDark: "#1e266d",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
            qrRendered = true;
        }
    }

    // Render immediately or when modal shown
    renderQrCode();
    $('#qrAttendanceModal').on('shown.bs.modal', function () {
        if (!qrRendered || $('#qr-container canvas, #qr-container img').length === 0) {
            renderQrCode();
        }
    });

    // Modal Sub-Tabs (QR Pass vs Camera Scanner)
    $('.qr-modal-tab-btn').on('click', function() {
        $('.qr-modal-tab-btn').removeClass('active bg-white text-dark shadow-sm').addClass('text-muted');
        $(this).addClass('active bg-white text-dark shadow-sm').removeClass('text-muted');
        
        const targetView = $(this).data('target');
        $('.qr-modal-view-panel').addClass('d-none');
        $(targetView).removeClass('d-none');

        if (targetView === '#qrScannerView') {
            startScanner();
        } else {
            stopScanner();
        }
    });

    // Download QR Code
    const downloadBtn = document.getElementById('downloadBtn');
    if (downloadBtn) {
        downloadBtn.onclick = function(){
            const canvas = document.querySelector('#qr-container canvas');
            const img = document.querySelector('#qr-container img');
            const link = document.createElement('a');
            link.download = 'club-attendance-qr.png';
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

    // Live HTML5 Camera Scanner
    let html5QrCode = null;
    let isScanning = false;

    function startScanner() {
        if (isScanning) return;
        if (typeof Html5Qrcode === 'undefined') {
            $('#scanner-status').html('<span class="text-danger"><i class="fa fa-exclamation-circle me-1"></i> Scanner library loading...</span>');
            return;
        }

        $('#scanner-status').html('<span class="text-info"><i class="fa fa-spinner fa-spin me-1"></i> Initializing camera...</span>');
        
        try {
            html5QrCode = new Html5Qrcode("qr-reader");
            html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 200, height: 200 }
                },
                (decodedText, decodedResult) => {
                    // Success callback
                    $('#scanner-result-box').removeClass('d-none');
                    $('#scanner-result-text').text(decodedText);
                    $('#scanner-status').html('<span class="text-success fw-bold"><i class="fa fa-check-circle me-1"></i> QR Scanned Successfully!</span>');
                },
                (errorMessage) => {
                    // Ongoing frame scanning - ignore
                }
            ).then(() => {
                isScanning = true;
                $('#scanner-status').html('<span class="text-success"><i class="fa fa-camera me-1"></i> Camera active. Align QR in frame.</span>');
                $('#btnStartScan').addClass('d-none');
                $('#btnStopScan').removeClass('d-none');
            }).catch(err => {
                $('#scanner-status').html('<span class="text-danger"><i class="fa fa-exclamation-triangle me-1"></i> Camera access unavailable or permission denied.</span>');
            });
        } catch(e) {
            $('#scanner-status').html('<span class="text-danger"><i class="fa fa-exclamation-triangle me-1"></i> Scanner error: ' + e.message + '</span>');
        }
    }

    function stopScanner() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                $('#scanner-status').html('<span class="text-muted"><i class="fa fa-video-camera me-1"></i> Camera is turned off.</span>');
                $('#btnStartScan').removeClass('d-none');
                $('#btnStopScan').addClass('d-none');
            }).catch(err => {
                console.error(err);
                isScanning = false;
            });
        }
    }

    $('#btnStartScan').on('click', startScanner);
    $('#btnStopScan').on('click', stopScanner);

    // Turn off camera when modal closes
    $('#qrAttendanceModal').on('hidden.bs.modal', function () {
        stopScanner();
        // Reset subtab to Pass View
        $('.qr-modal-tab-btn[data-target="#qrPassView"]').trigger('click');
        $('#scanner-result-box').addClass('d-none');
    });
</script>
@endpush
