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
        background: linear-gradient(135deg, #071938 0%, #0c2d64 55%, #1e40af 100%);
        border-radius: 18px;
        padding: 24px 28px;
        color: #ffffff;
        margin-bottom: 22px;
        box-shadow: 0 12px 30px -4px rgba(7, 25, 56, 0.35);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        position: relative;
        overflow: hidden;
    }

    .fcc-page-hero::after {
        content: '';
        position: absolute;
        top: -60px;
        right: -40px;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.25) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .fcc-hero-content {
        position: relative;
        z-index: 2;
        max-width: 480px;
    }

    .fcc-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        color: #e2e8f0;
        font-size: 11.5px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 30px;
        margin-bottom: 10px;
        letter-spacing: 0.02em;
    }

    .fcc-hero-pill .fcc-pulse-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: fccPulse 2s infinite;
    }

    @keyframes fccPulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .fcc-hero-title {
        font-size: 25px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 6px;
        letter-spacing: -0.02em;
        line-height: 1.25;
    }

    .fcc-hero-subtitle {
        color: #cbd5e1;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 0;
        line-height: 1.5;
    }

    /* Gym Storefront Art positioned in hero center-right background */
    .fcc-hero-art {
        position: absolute;
        right: 280px;
        bottom: 0;
        height: 100%;
        max-height: 160px;
        pointer-events: none;
        opacity: 0.7;
        z-index: 1;
        display: none;
    }

    @media (min-width: 1200px) {
        .fcc-hero-art { display: block; }
    }

    /* KPI Stat Tiles */
    .fcc-hero-stats-row {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 2;
    }

    .fcc-hero-kpi-tile {
        background: rgba(10, 26, 58, 0.60);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 14px;
        padding: 12px 18px;
        min-width: 110px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.18);
        transition: transform 0.2s ease;
    }

    .fcc-hero-kpi-tile:hover {
        transform: translateY(-2px);
    }

    .fcc-kpi-icon-badge {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
    }

    .fcc-kpi-icon-blue  { background: #2563eb; color: #ffffff; }
    .fcc-kpi-icon-green { background: #10b981; color: #ffffff; }
    .fcc-kpi-icon-red   { background: #ef4444; color: #ffffff; }

    .fcc-kpi-label {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        margin-bottom: 4px;
        white-space: nowrap;
    }

    .fcc-kpi-val {
        font-size: 22px;
        font-weight: 800;
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        line-height: 1;
    }

    /* Right Action Buttons */
    .fcc-hero-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 2;
    }

    .fcc-btn-add-franchise {
        background: #ffffff;
        color: #0f172a !important;
        font-weight: 700;
        font-size: 13.5px;
        padding: 10px 18px;
        border-radius: 10px;
        border: 1px solid #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none !important;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .fcc-btn-add-franchise:hover {
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.18);
        color: var(--fcc-primary) !important;
    }

    .fcc-btn-filter-coaches {
        background: rgba(14, 38, 82, 0.65);
        border: 1px solid rgba(147, 197, 253, 0.35);
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13.5px;
        padding: 10px 18px;
        border-radius: 10px;
        backdrop-filter: blur(8px);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .fcc-btn-filter-coaches:hover {
        background: rgba(255, 255, 255, 0.18);
        transform: translateY(-2px);
        color: #ffffff !important;
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

            <!-- Stylized Gym/Club SVG Artwork in Background -->
            <div class="fcc-hero-art">
                <svg width="260" height="150" viewBox="0 0 260 150" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="130,12 25,58 235,58" fill="#0f2b68" stroke="#3b82f6" stroke-width="2"/>
                    <circle cx="130" cy="38" r="14" fill="#1e3a8a" stroke="#60a5fa" stroke-width="1.5"/>
                    <line x1="123" y1="38" x2="137" y2="38" stroke="#93c5fd" stroke-width="2.5" stroke-linecap="round"/>
                    <rect x="121" y="34" width="3" height="8" rx="1.5" fill="#93c5fd"/>
                    <rect x="136" y="34" width="3" height="8" rx="1.5" fill="#93c5fd"/>
                    <rect x="35" y="58" width="190" height="92" fill="#091d44" stroke="#1d4ed8" stroke-width="1.5"/>
                    <rect x="42" y="62" width="176" height="20" rx="3" fill="#172e66" stroke="#38bdf8" stroke-width="1"/>
                    <text x="130" y="76" font-family="Outfit, sans-serif" font-size="10" font-weight="800" fill="#e0f2fe" text-anchor="middle" letter-spacing="1.5">FIT COACH CLUB</text>
                    <rect x="46" y="88" width="46" height="58" rx="2" fill="#0369a1" fill-opacity="0.35" stroke="#38bdf8" stroke-width="1"/>
                    <line x1="69" y1="88" x2="69" y2="146" stroke="#38bdf8" stroke-opacity="0.5"/>
                    <line x1="46" y1="117" x2="92" y2="117" stroke="#38bdf8" stroke-opacity="0.5"/>
                    <rect x="106" y="88" width="48" height="62" rx="2" fill="#0284c7" fill-opacity="0.45" stroke="#67e8f9" stroke-width="1.5"/>
                    <line x1="130" y1="88" x2="130" y2="150" stroke="#67e8f9" stroke-opacity="0.7"/>
                    <rect x="168" y="88" width="46" height="58" rx="2" fill="#0369a1" fill-opacity="0.35" stroke="#38bdf8" stroke-width="1"/>
                    <line x1="191" y1="88" x2="191" y2="146" stroke="#38bdf8" stroke-opacity="0.5"/>
                    <line x1="168" y1="117" x2="214" y2="117" stroke="#38bdf8" stroke-opacity="0.5"/>
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
