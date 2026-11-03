@extends('admin-panel.layouts.main-layout')

@section('page-title', 'Franchises | '.__('language.page_main_title').'')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    :root {
        --fcc-primary: #1d4ed8;
        --fcc-primary-light: #3b82f6;
        --fcc-dark: #0f172a;
        --fcc-slate: #1e293b;
        --fcc-muted: #64748b;
        --fcc-border: #e2e8f0;
        --fcc-bg: #f8fafc;
        --fcc-card-bg: #ffffff;
        --fcc-success: #10b981;
        --fcc-warning: #f59e0b;
        --fcc-danger: #ef4444;
        --fcc-purple: #8b5cf6;
        --fcc-card-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
    }

    .fcc-franchise-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--fcc-slate);
        padding: 4px 2px 30px 2px;
    }

    .fcc-franchise-page h1,
    .fcc-franchise-page h2,
    .fcc-franchise-page h3,
    .fcc-franchise-page h4,
    .fcc-hero-title,
    .fcc-card-title {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    }

    /* ===== 1. Page Hero Banner ===== */
    .fcc-page-hero {
        background: linear-gradient(135deg, #020617 0%, #0c1e4a 30%, #1e3a8a 65%, #2563eb 100%);
        border-radius: 20px;
        padding: 28px 32px;
        color: #ffffff;
        margin-bottom: 22px;
        box-shadow: 0 16px 40px -6px rgba(2, 6, 23, 0.55), 0 0 0 1px rgba(59, 130, 246, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 22px;
        position: relative;
        overflow: hidden;
    }

    /* Top-right bright orb glow */
    .fcc-page-hero::before {
        content: '';
        position: absolute;
        top: -80px;
        right: -50px;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.35) 0%, rgba(99, 102, 241, 0.12) 40%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }

    /* Bottom-left subtle purple orb */
    .fcc-page-hero::after {
        content: '';
        position: absolute;
        bottom: -60px;
        left: -40px;
        width: 280px;
        height: 280px;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.18) 0%, transparent 65%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }

    /* Animated shimmer sweep across the banner */
    .fcc-hero-shimmer {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.03) 40%, rgba(255, 255, 255, 0.08) 50%, rgba(255, 255, 255, 0.03) 60%, transparent 100%);
        animation: fccShimmerSweep 6s ease-in-out infinite;
        pointer-events: none;
        z-index: 1;
    }

    @keyframes fccShimmerSweep {
        0% { left: -100%; }
        50% { left: 100%; }
        100% { left: 100%; }
    }

    /* Decorative grid dot pattern */
    .fcc-hero-grid {
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        background-image: radial-gradient(circle, rgba(148, 163, 184, 0.12) 1px, transparent 1px);
        background-size: 24px 24px;
        pointer-events: none;
        z-index: 0;
        opacity: 0.6;
    }

    .fcc-hero-content {
        position: relative;
        z-index: 3;
        max-width: 440px;
    }

    .fcc-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.18) 0%, rgba(59, 130, 246, 0.12) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(16, 185, 129, 0.30);
        color: #a5f3fc;
        font-size: 11.5px;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 30px;
        margin-bottom: 12px;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .fcc-hero-pill .fcc-pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #34d399;
        box-shadow: 0 0 6px rgba(52, 211, 153, 0.8);
        animation: fccPulse 2s infinite;
    }

    @keyframes fccPulse {
        0% { transform: scale(0.95); box-shadow: 0 0 4px rgba(52, 211, 153, 0.8); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 7px rgba(52, 211, 153, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 4px rgba(52, 211, 153, 0); }
    }

    .fcc-hero-title {
        font-size: 26px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 8px;
        letter-spacing: -0.02em;
        line-height: 1.25;
        text-shadow: 0 2px 16px rgba(37, 99, 235, 0.3);
    }

    .fcc-hero-subtitle {
        color: #94a3b8;
        font-size: 13.5px;
        font-weight: 500;
        margin-bottom: 0;
        line-height: 1.55;
    }

    /* Gym Storefront Art between content and KPI cards */
    .fcc-hero-building-art {
        display: none;
        align-items: flex-end;
        justify-content: center;
        flex-shrink: 0;
        z-index: 2;
        margin: 0 4px;
    }

    @media (min-width: 1200px) {
        .fcc-hero-building-art {
            display: flex;
        }
    }

    /* KPI Stat Tiles */
    .fcc-hero-stats-row {
        display: flex;
        align-items: stretch;
        gap: 12px;
        position: relative;
        z-index: 3;
    }

    .fcc-hero-kpi-tile {
        background: linear-gradient(145deg, rgba(15, 23, 42, 0.75) 0%, rgba(30, 58, 138, 0.50) 100%);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 14px;
        padding: 12px 18px;
        min-width: 110px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        box-shadow: 0 8px 24px -4px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.08);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .fcc-hero-kpi-tile:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 32px -6px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.12);
        border-color: rgba(148, 163, 184, 0.30);
    }

    .fcc-kpi-icon-badge {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
    }

    .fcc-kpi-icon-blue  { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #ffffff; }
    .fcc-kpi-icon-green { background: linear-gradient(135deg, #059669, #10b981); color: #ffffff; }
    .fcc-kpi-icon-red   { background: linear-gradient(135deg, #dc2626, #ef4444); color: #ffffff; }

    .fcc-kpi-label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        margin-bottom: 4px;
        white-space: nowrap;
    }

    .fcc-kpi-val {
        font-size: 24px;
        font-weight: 800;
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        line-height: 1;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
    }

    /* Right Action Buttons */
    .fcc-hero-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 3;
    }

    .fcc-btn-add-franchise {
        background: #ffffff;
        color: #0f172a !important;
        font-weight: 700;
        font-size: 13.5px;
        padding: 11px 20px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.8);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
    }

    .fcc-btn-add-franchise:hover {
        background: #f0f9ff;
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.20), 0 0 0 1px rgba(59, 130, 246, 0.4);
        color: #1d4ed8 !important;
    }

    .fcc-btn-filter-coaches {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.55) 0%, rgba(37, 99, 235, 0.35) 100%);
        border: 1px solid rgba(147, 197, 253, 0.30);
        color: #e0f2fe !important;
        font-weight: 700;
        font-size: 13.5px;
        padding: 11px 20px;
        border-radius: 10px;
        backdrop-filter: blur(10px);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
    }

    .fcc-btn-filter-coaches:hover {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.50) 0%, rgba(99, 102, 241, 0.40) 100%);
        transform: translateY(-2px);
        color: #ffffff !important;
        border-color: rgba(147, 197, 253, 0.5);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.25);
    }

    /* ===== 2. Filter Card ===== */
    .fcc-filter-card {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 22px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
    }

    .fcc-filter-title {
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--fcc-primary);
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
    }

    .fcc-form-group { margin-bottom: 14px; }

    .fcc-form-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--fcc-muted);
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 6px;
        display: block;
    }

    .fcc-form-input {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-size: 13.5px;
        color: var(--fcc-slate);
        background: #f8fafc;
        transition: all 0.2s ease;
    }

    .fcc-form-input:focus {
        background: #ffffff;
        border-color: var(--fcc-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        outline: none;
    }

    /* ===== 3. Main White Card Container ===== */
    .fcc-card-container {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 18px;
        box-shadow: var(--fcc-card-shadow);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .fcc-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: #ffffff;
    }

    .fcc-card-badge-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        font-size: 17px;
    }

    .fcc-card-title {
        font-size: 17.5px;
        font-weight: 800;
        color: var(--fcc-dark);
        margin: 0;
        letter-spacing: -0.01em;
    }

    .fcc-card-subtitle {
        font-size: 12.5px;
        color: var(--fcc-muted);
        margin: 2px 0 0 0;
        font-weight: 500;
    }

    .fcc-live-badge {
        background: #f5f3ff;
        color: #7c3aed;
        border: 1px solid #ddd6fe;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .fcc-live-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #8b5cf6;
        box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.7);
        animation: fccLivePulse 2s infinite;
    }

    @keyframes fccLivePulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 5px rgba(139, 92, 246, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(139, 92, 246, 0); }
    }

    /* ===== 4. DataTable Toolbar ===== */
    .fcc-dt-toolbar {
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
    }

    .fcc-dt-length {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .fcc-dt-length select {
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
        background-color: #ffffff !important;
        color: #0f172a !important;
        font-weight: 600 !important;
        outline: none !important;
    }

    .fcc-dt-search {
        position: relative;
        flex: 1;
        max-width: 480px;
        min-width: 260px;
    }

    .fcc-dt-search label {
        width: 100%;
        margin-bottom: 0;
        position: relative;
        display: flex;
        align-items: center;
    }

    .fcc-dt-search label::before {
        content: "\f002";
        font-family: 'FontAwesome';
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
    }

    .fcc-dt-search input {
        width: 100% !important;
        border-radius: 22px !important;
        border: 1px solid #e2e8f0 !important;
        padding: 8px 16px 8px 38px !important;
        font-size: 13px !important;
        background-color: #ffffff !important;
        color: #0f172a !important;
        transition: all 0.2s ease !important;
        outline: none !important;
    }

    .fcc-dt-search input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    .fcc-dt-search input::placeholder {
        color: #94a3b8 !important;
        font-size: 13px !important;
    }

    .fcc-dt-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fcc-dt-mass-btn {
        border-radius: 8px !important;
        padding: 7px 14px !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        border: 1px solid #e2e8f0 !important;
        background: #ffffff !important;
        color: #475569 !important;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .fcc-dt-mass-btn:hover:not(:disabled) {
        background: #f1f5f9 !important;
        color: var(--fcc-primary) !important;
        border-color: var(--fcc-primary) !important;
    }

    .fcc-dt-mass-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* ===== 5. DataTable Overrides ===== */
    .dataTables_wrapper {
        padding: 0 !important;
    }

    table.dataTable {
        border-collapse: separate !important;
        border-spacing: 0;
        width: 100% !important;
        margin: 0 !important;
        border: none !important;
    }

    table.dataTable thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        padding: 14px 16px !important;
        border-top: none !important;
        border-bottom: 1px solid #e2e8f0 !important;
        white-space: nowrap;
    }

    table.dataTable tbody td {
        padding: 14px 16px !important;
        vertical-align: middle !important;
        font-size: 13.5px !important;
        color: var(--fcc-slate) !important;
        border-bottom: 1px solid #f1f5f9 !important;
        transition: background-color 0.15s ease;
    }

    table.dataTable tbody tr:hover td {
        background-color: #f8fafc !important;
    }

    /* Cell Components Rendered by Controller */
    .fcc-store-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fcc-store-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 14px;
    }

    .fcc-store-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 13.5px;
        white-space: nowrap;
    }

    .fcc-contact-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #334155;
        font-size: 13px;
        font-weight: 500;
        white-space: nowrap;
    }

    .fcc-cell-icon {
        color: #64748b;
        font-size: 13.5px;
    }

    .fcc-amount-pill {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
        white-space: nowrap;
    }

    .fcc-amount-success {
        background: #ecfdf5;
        color: #10b981;
        border: 1px solid #a7f3d0;
    }

    .fcc-amount-danger {
        background: #fef2f2;
        color: #ef4444;
        border: 1px solid #fecaca;
    }

    .fcc-days-pill {
        font-weight: 700;
        font-size: 13px;
    }

    .fcc-days-success { color: #16a34a !important; }
    .fcc-days-danger  { color: #ef4444 !important; }

    .fcc-memberships-btn {
        background: #ffffff;
        color: #1d4ed8 !important;
        border: 1px solid #bfdbfe;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none !important;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .fcc-memberships-btn:hover {
        background: #eff6ff;
        border-color: #93c5fd;
        transform: translateY(-1px);
    }

    .fcc-status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .fcc-status-active {
        background: #ecfdf5;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .fcc-status-inactive {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .fcc-actions-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .fcc-round-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .fcc-btn-edit {
        background: #eff6ff;
        color: #2563eb !important;
        border: 1px solid #dbeafe;
    }

    .fcc-btn-edit:hover {
        background: #2563eb;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .fcc-btn-delete {
        background: #fef2f2;
        color: #ef4444 !important;
        border: 1px solid #fee2e2;
        cursor: pointer;
    }

    .fcc-btn-delete:hover {
        background: #ef4444;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    /* ===== 6. DataTable Footer ===== */
    .fcc-dt-footer {
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        border-top: 1px solid #f1f5f9;
        background: #ffffff;
    }

    .dataTables_info {
        color: #64748b !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        padding-top: 0 !important;
    }

    .dataTables_paginate .pagination {
        margin: 0 !important;
    }

    .dataTables_paginate .pagination li a {
        border-radius: 8px !important;
        margin: 0 3px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        border: 1px solid #e2e8f0 !important;
        color: #475569 !important;
        padding: 6px 12px !important;
    }

    .dataTables_paginate .pagination li.active a {
        background-color: #1d4ed8 !important;
        border-color: #1d4ed8 !important;
        color: #ffffff !important;
    }

    /* ===== Responsive ===== */
    @media (max-width: 992px) {
        .fcc-page-hero {
            flex-direction: column;
            align-items: flex-start;
        }
        .fcc-hero-stats-row {
            flex-wrap: wrap;
        }
        .fcc-hero-actions {
            flex-wrap: wrap;
        }
        .fcc-dt-toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        .fcc-dt-search {
            max-width: 100%;
        }
    }

    @media (max-width: 576px) {
        .fcc-page-hero {
            padding: 18px 16px;
        }
        .fcc-hero-title {
            font-size: 20px;
        }
        .fcc-card-header {
            padding: 14px 16px;
        }
        .fcc-dt-toolbar {
            padding: 12px 16px;
        }
        .fcc-dt-footer {
            padding: 12px 16px;
        }
    }
</style>
@endpush

@section('content')

    @php
        $totalFranchisesCount = \App\Models\User::where('role_type', 'franchise')->count();
        $activeFranchisesCount = \App\Models\User::where('role_type', 'franchise')->whereDate('end_date', '>=', now())->count();
        $inactiveFranchisesCount = max(0, $totalFranchisesCount - $activeFranchisesCount);
    @endphp

    <div class="layout-px-spacing fcc-franchise-page">

        <!-- 1. Executive Hero Header Strip -->
        <div class="fcc-page-hero">
            <div class="fcc-hero-shimmer"></div>
            <div class="fcc-hero-grid"></div>

            <!-- Left Information -->
            <div class="fcc-hero-content">
                <div class="fcc-hero-pill">
                    <span class="fcc-pulse-dot"></span> Franchise Management System
                </div>
                <div class="fcc-hero-title">
                    Franchise Coaches Directory 🏆
                </div>
                <p class="fcc-hero-subtitle">
                    Real-time roster of registered franchises, active membership packages, validity days and financial pending balances.
                </p>
            </div>

            <!-- Center Gym Storefront Art between content and KPI stats -->
            <div class="fcc-hero-building-art">
                <svg width="220" height="135" viewBox="0 0 220 135" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="fccRoofGrad" x1="110" y1="8" x2="110" y2="48" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#1e40af"/>
                            <stop offset="100%" stop-color="#0c2d64"/>
                        </linearGradient>
                        <linearGradient id="fccSignGrad" x1="110" y1="50" x2="110" y2="68" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#1e3a8a"/>
                            <stop offset="100%" stop-color="#0f172a"/>
                        </linearGradient>
                        <linearGradient id="fccWindowGrad" x1="0" y1="72" x2="0" y2="132" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#0284c7" stop-opacity="0.38"/>
                            <stop offset="100%" stop-color="#38bdf8" stop-opacity="0.12"/>
                        </linearGradient>
                        <linearGradient id="fccDoorGrad" x1="0" y1="72" x2="0" y2="135" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.55"/>
                            <stop offset="100%" stop-color="#0284c7" stop-opacity="0.22"/>
                        </linearGradient>
                    </defs>

                    <!-- Ambient Glow -->
                    <circle cx="110" cy="70" r="55" fill="#3b82f6" fill-opacity="0.18"/>

                    <!-- Roof Pediment Triangle -->
                    <polygon points="110,10 18,48 202,48" fill="url(#fccRoofGrad)" stroke="#38bdf8" stroke-width="1.8" stroke-linejoin="round"/>

                    <!-- Dumbbell Emblem in Roof -->
                    <circle cx="110" cy="30" r="13" fill="#0f2b68" stroke="#60a5fa" stroke-width="1.5"/>
                    <line x1="104" y1="30" x2="116" y2="30" stroke="#93c5fd" stroke-width="2.2" stroke-linecap="round"/>
                    <rect x="102" y="26.5" width="2.5" height="7" rx="1" fill="#93c5fd"/>
                    <rect x="115.5" y="26.5" width="2.5" height="7" rx="1" fill="#93c5fd"/>

                    <!-- Main Body -->
                    <rect x="25" y="48" width="170" height="85" fill="#071738" stroke="#2563eb" stroke-width="1.2"/>

                    <!-- Fascia Signboard -->
                    <rect x="30" y="51" width="160" height="18" rx="3" fill="url(#fccSignGrad)" stroke="#38bdf8" stroke-width="1"/>
                    <text x="110" y="64" font-family="'Outfit', sans-serif" font-size="9" font-weight="800" fill="#e0f2fe" text-anchor="middle" letter-spacing="1.2">FIT COACH CLUB</text>

                    <!-- Left Window Bay -->
                    <rect x="34" y="73" width="40" height="58" rx="2" fill="url(#fccWindowGrad)" stroke="#38bdf8" stroke-width="1" stroke-opacity="0.8"/>
                    <line x1="54" y1="73" x2="54" y2="131" stroke="#38bdf8" stroke-opacity="0.4"/>
                    <line x1="34" y1="100" x2="74" y2="100" stroke="#38bdf8" stroke-opacity="0.4"/>
                    <line x1="42" y1="116" x2="66" y2="116" stroke="#0284c7" stroke-width="1.8" stroke-linecap="round"/>
                    <line x1="46" y1="110" x2="62" y2="110" stroke="#0284c7" stroke-width="1.5" stroke-linecap="round"/>

                    <!-- Center Glass Double Door -->
                    <rect x="83" y="73" width="54" height="60" rx="2" fill="url(#fccDoorGrad)" stroke="#67e8f9" stroke-width="1.4"/>
                    <line x1="110" y1="73" x2="110" y2="133" stroke="#67e8f9" stroke-width="1.2" stroke-opacity="0.8"/>
                    <rect x="107" y="100" width="1.5" height="9" rx="0.75" fill="#ffffff"/>
                    <rect x="111.5" y="100" width="1.5" height="9" rx="0.75" fill="#ffffff"/>
                    <circle cx="102" cy="94" r="3" fill="#bae6fd"/>
                    <path d="M99 107 L102 99 L105 107" stroke="#bae6fd" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="118" cy="92" r="3.2" fill="#e0f2fe"/>
                    <path d="M114 107 L118 97 L122 107" stroke="#e0f2fe" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>

                    <!-- Right Window Bay -->
                    <rect x="146" y="73" width="40" height="58" rx="2" fill="url(#fccWindowGrad)" stroke="#38bdf8" stroke-width="1" stroke-opacity="0.8"/>
                    <line x1="166" y1="73" x2="166" y2="131" stroke="#38bdf8" stroke-opacity="0.4"/>
                    <line x1="146" y1="100" x2="186" y2="100" stroke="#38bdf8" stroke-opacity="0.4"/>
                    <line x1="154" y1="118" x2="178" y2="118" stroke="#0284c7" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="158" cy="118" r="3" fill="#0284c7"/>
                    <circle cx="174" cy="118" r="3" fill="#0284c7"/>

                    <!-- Ground Accent -->
                    <line x1="10" y1="133" x2="210" y2="133" stroke="#38bdf8" stroke-opacity="0.4" stroke-width="1.5"/>
                </svg>
            </div>

            <!-- KPI Stat Tiles -->
            <div class="fcc-hero-stats-row">
                <div class="fcc-hero-kpi-tile">
                    <div class="fcc-kpi-icon-badge fcc-kpi-icon-blue">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <span class="fcc-kpi-label">Total Clubs</span>
                    <span class="fcc-kpi-val">{{ number_format($totalFranchisesCount) }}</span>
                </div>

                <div class="fcc-hero-kpi-tile">
                    <div class="fcc-kpi-icon-badge fcc-kpi-icon-green">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <span class="fcc-kpi-label">Active Clubs</span>
                    <span class="fcc-kpi-val">{{ number_format($activeFranchisesCount) }}</span>
                </div>

                <div class="fcc-hero-kpi-tile">
                    <div class="fcc-kpi-icon-badge fcc-kpi-icon-red">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </div>
                    <span class="fcc-kpi-label">Inactive / Exp.</span>
                    <span class="fcc-kpi-val">{{ number_format($inactiveFranchisesCount) }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="fcc-hero-actions">
                <a href="{{ route('adminPanel.franchises.create') }}" class="fcc-btn-add-franchise">
                    <i class="fa fa-plus"></i> Add Franchise
                </a>
                <button type="button" class="fcc-btn-filter-coaches filter-button">
                    <i class="fa fa-filter"></i> Filter Coaches
                </button>
            </div>
        </div>

        <!-- 2. Collapsible Filter Drawer -->
        <div class="custom-datatable-filters hide">
            <div class="fcc-filter-card">
                <div class="fcc-filter-title">
                    <i class="fa fa-sliders"></i> Advanced Franchise Filters
                </div>

                {!! Form::open(['class' => 'custom-datatable-filter-form', 'autoComplete' => 'off']) !!}
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12 fcc-form-group">
                        <label class="fcc-form-label"><i class="fa fa-user me-1"></i> Franchise Name</label>
                        {!! Form::text('name', '', ['class' => 'fcc-form-input filter-field', 'id' => 'name', 'placeholder' => 'Search by name...' ]) !!}
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 fcc-form-group">
                        <label class="fcc-form-label"><i class="fa fa-envelope me-1"></i> Email Address</label>
                        {!! Form::text('email', '', ['class' => 'fcc-form-input filter-field', 'id' => 'email', 'placeholder' => 'Search by email...' ]) !!}
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 fcc-form-group">
                        <label class="fcc-form-label"><i class="fa fa-phone me-1"></i> Mobile Number</label>
                        {!! Form::text('mobile_number', '', ['class' => 'fcc-form-input filter-field', 'id' => 'mobile_number', 'placeholder' => 'Search by mobile...' ]) !!}
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 fcc-form-group">
                        <label class="fcc-form-label"><i class="fa fa-calendar me-1"></i> Joined Date Range</label>
                        {!! Form::text('date_range', request('date_range', ''), ['class' => 'fcc-form-input date_range filter-field date-picker', 'id' => 'date_range', 'autocomplete' => 'off', 'placeholder' => 'Select date range...', ]) !!}    
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 pt-2">
                    {{ Form::button( '<i class="fa fa-search me-1"></i> ' . __('language.filter_apply'), ['class' => 'btn btn-primary apply-filter px-4', 'type' => 'button', 'title' => __('language.filter_apply'), 'name' => 'filter', 'style' => 'border-radius: 8px; font-weight: 600; padding: 9px 20px;'] )}}
                    {{ Form::button( '<i class="fa fa-refresh me-1"></i> ' . __('language.filter_clear'), ['class' => 'btn btn-dark clear-filter px-4', 'type' => 'button', 'title' => __('language.filter_clear'), 'name' => 'clear', 'style' => 'border-radius: 8px; font-weight: 600; padding: 9px 20px;'] )}}
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        <!-- 3. Franchises Master DataTable Card -->
        <div class="fcc-card-container">
            <div class="fcc-card-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="fcc-card-badge-icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div>
                        <h4 class="fcc-card-title">All Registered Franchises</h4>
                        <p class="fcc-card-subtitle">Manage coach profile, active membership plans, dues and statuses.</p>
                    </div>
                </div>
                <div>
                    <span class="fcc-live-badge">
                        <span class="fcc-live-dot"></span> Live Directory
                    </span>
                </div>
            </div>

            <div class="data-table-container">
                <table id="dataTable" class="table table-hover" 
                    data-url="{{ route('adminPanel.franchises.getFranchises') }}"  
                    data-change-status-url="{{ route('adminPanel.franchises.changeStatus') }}" 
                    data-destroy-url="{{ route('adminPanel.franchises.destroy') }}" 
                    data-update-order-url="{{ route('adminPanel.franchises.updateOrder') }}">
                    <thead>
                        <tr>
                            <th class="checkbox-column" style="width: 36px !important;"> # </th>
                            <th style="width: 40px !important;">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Pending Amount</th>
                            <th>Pending Days</th>
                            <th>View Memberships</th>
                            <th> {{ __('language.table_status') }} </th>
                            <th class="text-center" style="width: 90px !important;"> {{ __('language.table_action') }} </th>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/franchises/view.js') }}"></script>

<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
