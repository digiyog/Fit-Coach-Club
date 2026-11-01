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
        --fcc-primary: #3246d3;
        --fcc-primary-light: #4361ee;
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
        --fcc-card-hover-shadow: 0 12px 28px -4px rgba(50, 70, 211, 0.10), 0 4px 10px -2px rgba(15, 23, 42, 0.04);
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
    .fcc-franchise-page h5,
    .fcc-franchise-page h6,
    .fcc-page-hero-title,
    .fcc-card-title {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    }

    /* 1. Page Hero Banner */
    .fcc-page-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #3246d3 100%);
        border-radius: 18px;
        padding: 24px 28px;
        color: #ffffff;
        margin-bottom: 22px;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.22);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 18px;
        position: relative;
        overflow: hidden;
    }

    .fcc-page-hero::after {
        content: '';
        position: absolute;
        top: -60px;
        right: -40px;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(67, 97, 238, 0.25) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .fcc-page-hero-content {
        position: relative;
        z-index: 2;
    }

    .fcc-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        color: #e2e8f0;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 30px;
        margin-bottom: 10px;
        letter-spacing: 0.02em;
    }

    .fcc-hero-pill .fcc-pulse-dot {
        width: 8px;
        height: 8px;
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

    .fcc-page-hero-title {
        font-size: 26px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 5px;
        letter-spacing: -0.02em;
        line-height: 1.25;
    }

    .fcc-page-hero-subtitle {
        color: #cbd5e1;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 0;
        max-width: 650px;
    }

    .fcc-hero-stats-row {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        position: relative;
        z-index: 2;
    }

    .fcc-hero-stat-card {
        background: rgba(255, 255, 255, 0.10);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 12px;
        padding: 10px 16px;
        min-width: 130px;
        display: flex;
        flex-direction: column;
    }

    .fcc-hero-stat-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #94a3b8;
        font-weight: 700;
    }

    .fcc-hero-stat-num {
        font-size: 20px;
        font-weight: 800;
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        line-height: 1.2;
    }

    .fcc-hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        position: relative;
        z-index: 2;
    }

    .fcc-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 13.5px;
        padding: 10px 18px;
        border-radius: 10px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
        white-space: nowrap;
        cursor: pointer;
    }

    .fcc-btn-white {
        background: #ffffff;
        color: #0f172a !important;
        border: 1px solid #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .fcc-btn-white:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.18);
        color: var(--fcc-primary) !important;
    }

    .fcc-btn-glass {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.22);
        backdrop-filter: blur(8px);
    }

    .fcc-btn-glass:hover {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff !important;
        transform: translateY(-2px);
    }

    /* 2. Modern Filter Card */
    .fcc-filter-card {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 22px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        transition: all 0.25s ease;
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

    .fcc-form-group {
        margin-bottom: 14px;
    }

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
        box-shadow: 0 0 0 3px rgba(50, 70, 211, 0.12);
        outline: none;
    }

    /* 3. Main Table Card */
    .fcc-card-container {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 16px;
        box-shadow: var(--fcc-card-shadow);
        overflow: hidden;
        margin-bottom: 24px;
        transition: all 0.25s ease;
    }

    .fcc-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--fcc-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: #ffffff;
    }

    .fcc-card-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fcc-card-badge-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: var(--fcc-primary);
        font-size: 18px;
    }

    .fcc-card-title {
        font-size: 17px;
        font-weight: 700;
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

    /* 4. DataTable Modern Styling Overrides */
    .dataTables_wrapper {
        padding: 16px 20px 20px 20px;
    }

    table.dataTable {
        border-collapse: separate !important;
        border-spacing: 0;
        width: 100% !important;
        margin-top: 14px !important;
        margin-bottom: 16px !important;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
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

    /* Polish for badges rendered inside the table */
    table.dataTable tbody td .badge {
        font-size: 12px !important;
        font-weight: 700 !important;
        padding: 6px 12px !important;
        border-radius: 20px !important;
        letter-spacing: 0.02em;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        line-height: 1.2;
    }

    table.dataTable tbody td .badge-success {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border: 1px solid #a7f3d0 !important;
    }

    table.dataTable tbody td .badge-warning {
        background-color: #fffbeb !important;
        color: #b45309 !important;
        border: 1px solid #fde68a !important;
    }

    table.dataTable tbody td .badge-danger {
        background-color: #fef2f2 !important;
        color: #b91c1c !important;
        border: 1px solid #fecaca !important;
    }

    table.dataTable tbody td .badge-primary {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        border: 1px solid #bfdbfe !important;
        transition: all 0.2s ease;
    }

    table.dataTable tbody td a:hover .badge-primary {
        background-color: #3246d3 !important;
        color: #ffffff !important;
        border-color: #3246d3 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(50, 70, 211, 0.25);
    }

    /* Pending Days & Amounts */
    table.dataTable tbody td .text-danger {
        color: #dc2626 !important;
        font-weight: 700 !important;
    }

    table.dataTable tbody td .text-success {
        color: #10b981 !important;
        font-weight: 700 !important;
    }

    /* Toolbar & Search Input Polish */
    .dataTables_filter label {
        font-weight: 600;
        color: #64748b;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .dataTables_filter input {
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        padding: 7px 12px !important;
        font-size: 13px !important;
        background-color: #f8fafc !important;
        transition: all 0.2s ease;
    }

    .dataTables_filter input:focus {
        background-color: #ffffff !important;
        border-color: var(--fcc-primary) !important;
        box-shadow: 0 0 0 3px rgba(50, 70, 211, 0.12) !important;
        outline: none;
    }

    .dataTables_length select {
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
        background-color: #f8fafc !important;
    }

    /* Action buttons in datatable toolbar */
    .dt-buttons .btn {
        border-radius: 8px !important;
        padding: 7px 14px !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        border: 1px solid #cbd5e1 !important;
        background: #ffffff !important;
        color: #475569 !important;
        transition: all 0.2s ease;
    }

    .dt-buttons .btn:hover:not(:disabled) {
        background: #f1f5f9 !important;
        color: var(--fcc-primary) !important;
        border-color: var(--fcc-primary) !important;
    }

    .dt-buttons .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Pagination Polish */
    .dataTables_paginate .pagination li a {
        border-radius: 8px !important;
        margin: 0 3px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
    }

    .dataTables_paginate .pagination li.active a {
        background-color: var(--fcc-primary) !important;
        border-color: var(--fcc-primary) !important;
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')
    @if(isset($breadcrumbFilter))
        <!-- Include breadcrumb -->
        @include('admin-panel.layouts.breadcrumb-filter')
        <!--/ Include breadcrumb -->
    @endif

    @php
        $totalFranchisesCount = \App\Models\User::where('role_type', 'franchise')->count();
        $activeFranchisesCount = \App\Models\User::where('role_type', 'franchise')->whereDate('end_date', '>=', now())->count();
        $inactiveFranchisesCount = max(0, $totalFranchisesCount - $activeFranchisesCount);
    @endphp

    <div class="layout-px-spacing fcc-franchise-page">

        <!-- 1. Page Hero Header Strip -->
        <div class="fcc-page-hero">
            <div class="fcc-page-hero-content">
                <div class="fcc-hero-pill">
                    <span class="fcc-pulse-dot"></span> Franchise Management System
                </div>
                <div class="fcc-page-hero-title">
                    Franchise Coaches Directory 🏋️‍♂️
                </div>
                <p class="fcc-page-hero-subtitle">
                    Real-time roster of registered franchises, active membership packages, validity days, and financial pending balances.
                </p>
            </div>

            <div class="fcc-hero-stats-row">
                <div class="fcc-hero-stat-card">
                    <span class="fcc-hero-stat-label">Total Clubs</span>
                    <span class="fcc-hero-stat-num">{{ number_format($totalFranchisesCount) }}</span>
                </div>
                <div class="fcc-hero-stat-card">
                    <span class="fcc-hero-stat-label">Active Clubs</span>
                    <span class="fcc-hero-stat-num text-success" style="color: #34d399 !important;">{{ number_format($activeFranchisesCount) }}</span>
                </div>
                <div class="fcc-hero-stat-card">
                    <span class="fcc-hero-stat-label">Inactive / Exp.</span>
                    <span class="fcc-hero-stat-num text-danger" style="color: #f87171 !important;">{{ number_format($inactiveFranchisesCount) }}</span>
                </div>
            </div>

            <div class="fcc-hero-actions">
                <a href="{{ route('adminPanel.franchises.create') }}" class="fcc-btn fcc-btn-white">
                    <i data-feather="plus" style="width: 16px; height: 16px;"></i> Add Franchise
                </a>
                <button type="button" class="fcc-btn fcc-btn-glass filter-button">
                    <i data-feather="filter" style="width: 16px; height: 16px;"></i> Filter Coaches
                </button>
            </div>
        </div>

        <!-- 2. Collapsible Filter Drawer -->
        <div class="custom-datatable-filters hide">
            <div class="fcc-filter-card">
                <div class="fcc-filter-title">
                    <i data-feather="sliders" style="width: 16px; height: 16px;"></i>
                    Advanced Franchise Filters
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
                <div class="fcc-card-header-left">
                    <div class="fcc-card-badge-icon">
                        <i data-feather="users" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <h4 class="fcc-card-title">All Registered Franchises</h4>
                        <p class="fcc-card-subtitle">Manage coach profile, active membership plans, dues, and statuses</p>
                    </div>
                </div>
                <div>
                    <span class="badge badge-purple" style="font-size: 12px; padding: 6px 14px; border-radius: 20px;">
                        Live Directory
                    </span>
                </div>
            </div>

            <div class="table-responsive data-table-container">
                <table id="dataTable" class="table table-hover" 
                    data-url="{{ route('adminPanel.franchises.getFranchises') }}"  
                    data-change-status-url="{{ route('adminPanel.franchises.changeStatus') }}" 
                    data-destroy-url="{{ route('adminPanel.franchises.destroy') }}" 
                    data-update-order-url="{{ route('adminPanel.franchises.updateOrder') }}">
                    <thead>
                        <tr>
                            <th class="checkbox-column"> # </th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Pending Amount</th>
                            <th>Pending Days</th>
                            <th>View Memberships</th>
                            <th> {{ __('language.table_status') }} </th>
                            <th> {{ __('language.table_action') }} </th>
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
