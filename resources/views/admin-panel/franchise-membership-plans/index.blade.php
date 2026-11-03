@extends('admin-panel.layouts.main-layout')

@section('page-title', 'Franchise Membership Plans | '.__('language.page_main_title').'')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    :root {
        --fcc-primary: #1d4ed8;
        --fcc-primary-hover: #1e40af;
        --fcc-dark: #0f172a;
        --fcc-slate: #1e293b;
        --fcc-muted: #64748b;
        --fcc-border: #e2e8f0;
        --fcc-card-bg: #ffffff;
    }

    .fcc-franchise-plans-wrapper {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--fcc-slate);
        padding: 4px 2px 40px 2px;
        width: 100%;
        max-width: 100%;
    }

    /* ===== 1. Executive Hero Header Strip ===== */
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
        color: #ffffff !important;
        margin-bottom: 8px;
        letter-spacing: -0.02em;
        line-height: 1.25;
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        text-shadow: 0 2px 16px rgba(37, 99, 235, 0.3);
    }

    .fcc-hero-subtitle {
        color: #94a3b8;
        font-size: 13.5px;
        font-weight: 500;
        margin-bottom: 0;
        line-height: 1.55;
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
    .fcc-kpi-icon-amber { background: linear-gradient(135deg, #d97706, #f59e0b); color: #ffffff; }

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

    .fcc-hero-art-box {
        display: none;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        z-index: 2;
        margin: 0 6px;
    }

    @media (min-width: 1200px) {
        .fcc-hero-art-box {
            display: flex;
        }
    }

    /* Action Buttons */
    .fcc-hero-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 3;
    }

    .fcc-btn-filter-hero {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 13.5px;
        padding: 11px 18px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .fcc-btn-filter-hero:hover {
        background: rgba(255, 255, 255, 0.20);
        transform: translateY(-2px);
    }

    .fcc-btn-add-hero {
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
        cursor: pointer;
    }

    .fcc-btn-add-hero:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        color: #000000 !important;
        background: #ffffff;
    }

    /* Filters Card */
    .fcc-filter-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 22px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
    }

    .fcc-filter-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--fcc-dark);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ===== 2. Single White Data Table Container Card ===== */
    .fcc-table-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 16px;
        padding: 22px 24px 24px 24px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
    }

    .fcc-franchise-plans-wrapper .data-table-container,
    .fcc-franchise-plans-wrapper .table-responsive,
    .fcc-table-card .data-table-container,
    .fcc-table-card .table-responsive {
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background: transparent !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow-x: auto !important;
    }

    .fcc-table-card table.dataTable {
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        margin: 0 !important;
    }

    .fcc-table-card table.dataTable.no-footer {
        border-bottom: 1px solid var(--fcc-border) !important;
    }

    /* DataTable Top Controls Bar */
    .fcc-table-card .dataTables_wrapper .row:first-child {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 14px !important;
        margin: 0 0 18px 0 !important;
        padding: 0 !important;
    }

    .fcc-table-card .dataTables_wrapper .row:first-child > div {
        padding: 0 !important;
        margin: 0 !important;
        flex: 1 1 auto !important;
        max-width: 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 14px !important;
    }

    .fcc-table-card .dataTables_length {
        display: inline-flex !important;
        align-items: center !important;
        margin: 0 !important;
    }

    .fcc-table-card .dataTables_length label {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: var(--fcc-muted) !important;
        margin-bottom: 0 !important;
    }

    .fcc-table-card .dataTables_length select {
        height: 38px !important;
        border-radius: 9px !important;
        border: 1.5px solid var(--fcc-border) !important;
        background: #ffffff !important;
        padding: 4px 10px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: var(--fcc-dark) !important;
        outline: none !important;
        cursor: pointer;
    }

    .fcc-table-card .dataTables_filter {
        display: inline-flex !important;
        align-items: center !important;
        margin: 0 !important;
        position: relative !important;
        flex: 1 1 300px !important;
        max-width: 440px !important;
    }

    .fcc-table-card .dataTables_filter label {
        position: relative !important;
        display: block !important;
        margin-bottom: 0 !important;
        width: 100% !important;
    }

    .fcc-table-card .dataTables_filter input {
        height: 40px !important;
        border-radius: 20px !important;
        border: 1.5px solid var(--fcc-border) !important;
        background-color: #ffffff !important;
        padding-left: 40px !important;
        padding-right: 14px !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: var(--fcc-dark) !important;
        width: 100% !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
    }

    .fcc-table-card .dataTables_filter input:focus {
        border-color: #1d4ed8 !important;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12) !important;
        outline: none !important;
    }

    .fcc-table-card .dataTables_filter label::before {
        content: '' !important;
        position: absolute !important;
        left: 14px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 16px !important;
        height: 16px !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'%3E%3C/line%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: center !important;
        background-size: contain !important;
        pointer-events: none !important;
        z-index: 2 !important;
    }

    .fcc-table-card .dataTables_filter svg,
    .fcc-table-card .dataTables_filter i {
        display: none !important;
    }

    /* Table Typography & Rows */
    .fcc-table-card table.dataTable thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        padding: 13px 16px !important;
        border-top: none !important;
        border-bottom: 1.5px solid var(--fcc-border) !important;
    }

    .fcc-table-card table.dataTable tbody td {
        padding: 14px 16px !important;
        font-size: 13.5px !important;
        color: #1e293b !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    .fcc-table-card table.dataTable tbody tr:hover td {
        background-color: #f8fafc !important;
    }

    /* Action Badges / Buttons inside Table */
    .fcc-table-card table.dataTable a.badge {
        padding: 6px 12px !important;
        border-radius: 8px !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
    }

    .fcc-table-card table.dataTable a.badge-primary,
    .fcc-table-card table.dataTable a.bg-primary {
        background: #eff6ff !important;
        color: #1d4ed8 !important;
        border: 1px solid #bfdbfe !important;
    }

    .fcc-table-card table.dataTable a.badge-danger,
    .fcc-table-card table.dataTable a.bg-danger {
        background: #fef2f2 !important;
        color: #dc2626 !important;
        border: 1px solid #fecaca !important;
    }

    /* Status Badges */
    .badge-success, .badge.bg-success {
        background: #ecfdf5 !important;
        color: #059669 !important;
        border: 1px solid #a7f3d0 !important;
        border-radius: 20px !important;
        padding: 4px 10px !important;
        font-weight: 700 !important;
    }

    .badge-warning, .badge.bg-warning {
        background: #fffbeb !important;
        color: #d97706 !important;
        border: 1px solid #fde68a !important;
        border-radius: 20px !important;
        padding: 4px 10px !important;
        font-weight: 700 !important;
    }

    /* Pagination */
    .fcc-table-card .dataTables_paginate {
        margin-top: 18px !important;
        display: flex !important;
        justify-content: flex-end !important;
        align-items: center !important;
        gap: 4px !important;
    }

    .fcc-table-card .paginate_button {
        border-radius: 8px !important;
        border: 1px solid var(--fcc-border) !important;
        background: #ffffff !important;
        color: var(--fcc-dark) !important;
        font-weight: 600 !important;
        font-size: 12.5px !important;
        min-width: 34px !important;
        height: 34px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 10px !important;
        margin: 0 2px !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
    }

    .fcc-table-card .paginate_button.current,
    .fcc-table-card .paginate_button.active a {
        background: #1d4ed8 !important;
        color: #ffffff !important;
        border-color: #1d4ed8 !important;
    }
</style>
@endpush

@section('content')
@php
    $totalCount = \App\Models\FranchiseMembershipPlan::count();
    $completedCount = \App\Models\FranchiseMembershipPlan::where('payment_status', 2)->count();
    $pendingCount = \App\Models\FranchiseMembershipPlan::where('payment_status', 1)->count();
@endphp

<div class="layout-px-spacing fcc-franchise-plans-wrapper">

    <!-- ===== 1. Executive Hero Header Strip ===== -->
    <div class="fcc-page-hero">
        <div class="fcc-hero-shimmer"></div>
        <div class="fcc-hero-grid"></div>

        <!-- Left: Title & Subtitle -->
        <div class="fcc-hero-content">
            <div class="fcc-hero-pill">
                <span class="fcc-pulse-dot"></span>
                <span>Franchise Subscriptions</span>
            </div>
            <h1 class="fcc-hero-title">Franchise Membership Plans</h1>
            <p class="fcc-hero-subtitle">Assign, track, and audit membership plans and payments across all franchises.</p>
        </div>

        <!-- Center: 3D Isometric Art & Glow Orbs -->
        <div class="fcc-hero-art-box">
            <svg width="150" height="88" viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="100" cy="95" rx="70" ry="18" fill="rgba(0,0,0,0.25)" filter="blur(8px)" />
                <path d="M100 20 L160 50 L100 80 L40 50 Z" fill="url(#heroPlaneGradientFMP)" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" />
                <path d="M40 50 L100 80 L100 100 L40 70 Z" fill="url(#heroSideLeftFMP)" />
                <path d="M160 50 L100 80 L100 100 L160 70 Z" fill="url(#heroSideRightFMP)" />
                <circle cx="100" cy="50" r="12" fill="#60a5fa" filter="url(#heroGlowFMP)" />
                <circle cx="100" cy="50" r="6" fill="#ffffff" />
                <defs>
                    <linearGradient id="heroPlaneGradientFMP" x1="40" y1="20" x2="160" y2="80" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#3b82f6" />
                        <stop offset="1" stop-color="#1d4ed8" />
                    </linearGradient>
                    <linearGradient id="heroSideLeftFMP" x1="40" y1="50" x2="100" y2="100" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#1e40af" />
                        <stop offset="1" stop-color="#0f172a" />
                    </linearGradient>
                    <linearGradient id="heroSideRightFMP" x1="160" y1="50" x2="100" y2="100" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#2563eb" />
                        <stop offset="1" stop-color="#1e3a8a" />
                    </linearGradient>
                    <filter id="heroGlowFMP" x="78" y="28" width="44" height="44" filterUnits="userSpaceOnUse">
                        <feGaussianBlur stdDeviation="5" />
                    </filter>
                </defs>
            </svg>
        </div>

        <!-- Center-Right: In-Hero KPI Stat Tiles -->
        <div class="fcc-hero-stats-row">
            <!-- Total Assigned -->
            <div class="fcc-hero-kpi-tile">
                <div class="fcc-kpi-icon-badge fcc-kpi-icon-blue">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                </div>
                <span class="fcc-kpi-label">Total Assigned</span>
                <span class="fcc-kpi-val">{{ $totalCount }}</span>
            </div>

            <!-- Completed -->
            <div class="fcc-hero-kpi-tile">
                <div class="fcc-kpi-icon-badge fcc-kpi-icon-green">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <span class="fcc-kpi-label">Paid / Completed</span>
                <span class="fcc-kpi-val">{{ $completedCount }}</span>
            </div>

            <!-- Pending -->
            <div class="fcc-hero-kpi-tile">
                <div class="fcc-kpi-icon-badge fcc-kpi-icon-amber">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
                <span class="fcc-kpi-label">Pending</span>
                <span class="fcc-kpi-val">{{ $pendingCount }}</span>
            </div>
        </div>

        <!-- Right: Action Buttons -->
        <div class="fcc-hero-actions">
            <button type="button" class="fcc-btn-filter-hero filter-button">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                Filter
            </button>
            <a href="{{ route('adminPanel.franchise-membership-plans.create') }}" class="fcc-btn-add-hero">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Assign Plan
            </a>
        </div>
    </div>

    <!-- Filter Card (Toggled via Filter Button) -->
    <div class="custom-datatable-filters hide">
        <div class="fcc-filter-card">
            <div class="fcc-filter-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                Filter Franchise Plans
            </div>
            {!! Form::open(['class' => 'custom-datatable-filter-form', 'autoComplete' => 'off']) !!}
            <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 700; color: #475569;">Franchise</label>
                    <select name="franchise_id" id="franchise_id" class="form-control filter-field select-picker">
                        <option value="">All Franchises</option>
                        @foreach($franchises as $key => $franchise)
                            <option value="{{ $franchise['id'] }}" @if($franchise_id == $franchise['id']) selected @endif>{{ $franchise['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 700; color: #475569;">Membership Plan</label>
                    <select name="membership_plan_id" id="membership_plan_id" class="form-control filter-field select-picker">
                        <option value="">All Plans</option>
                        @foreach($membershipPlans as $key => $membershipPlan)
                            <option value="{{ $membershipPlan['id'] }}">{{ $membershipPlan['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 700; color: #475569;">Payment Status</label>
                    <select name="payment_status" id="payment_status" class="form-control filter-field select-picker">
                        <option value="">All Statuses</option>
                        <option value="1">Pending</option>
                        <option value="2">Completed</option>
                    </select>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 mt-2">
                {{ Form::button( __('language.filter_apply'), ['class' => 'btn btn-primary apply-filter', 'type' => 'button', 'title' => __('language.filter_apply'), 'name' => 'filter', 'style' => 'border-radius: 8px; font-weight: 700;'] )}}
                {{ Form::button( __('language.filter_clear'), ['class' => 'btn btn-outline-secondary clear-filter', 'type' => 'button', 'title' => __('language.filter_clear'), 'name' => 'clear', 'style' => 'border-radius: 8px; font-weight: 700;'] )}}
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <!-- ===== 2. Single White Data Table Container Card ===== -->
    <div class="fcc-table-card">
        <div class="table-responsive">
            <table id="dataTable" class="table table-hover" data-url="{{ route('adminPanel.franchise-membership-plans.getFranchiseMembershipPlans') }}">
                <thead>
                    <tr>
                        <th>Franchise Name</th>
                        <th>Membership Plan</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Remark</th>
                        <th>{{ __('language.table_action') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end" style="font-weight: 800;">Total:</th>
                        <th id="total_amount_footer" style="font-weight: 800; color: #1d4ed8;"></th>
                        <th colspan="5"></th>
                    </tr>
                </tfoot>
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
<script src="{{ asset('admin-assets/js/franchise-membership-plans/view.js') }}"></script>
@endpush
