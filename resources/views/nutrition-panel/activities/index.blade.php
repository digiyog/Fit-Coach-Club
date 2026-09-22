@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Activities | ' . __('language.page_main_title'))

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
/* ==========================================================================
   Activities Page - Modern SaaS Redesign Styles
   ========================================================================== */
.act-page-wrapper {
    padding: 10px 0 40px;
    color: #1e293b;
    font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Header & Breadcrumb */
.act-top-header {
    margin-bottom: 24px;
}
.act-breadcrumb {
    font-size: 13px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-weight: 500;
}
.act-breadcrumb .act-crumb-parent {
    color: #64748b;
}
.act-breadcrumb .act-crumb-sep {
    color: #cbd5e1;
}
.act-breadcrumb .act-crumb-current {
    color: #0f172a;
    font-weight: 600;
}
.act-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 16px;
}
.act-header-title {
    font-size: 30px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.025em;
    line-height: 1.2;
    margin-bottom: 4px;
}
.act-header-subtitle {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 0;
}
.act-header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

/* Header Buttons */
.act-btn-outline-settings {
    background: #ffffff;
    border: 1px solid #2563eb;
    color: #2563eb;
    font-weight: 600;
    font-size: 13.5px;
    border-radius: 10px;
    padding: 9px 18px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
}
.act-btn-outline-settings:hover {
    background: #eff6ff;
    color: #1d4ed8;
    border-color: #1d4ed8;
    text-decoration: none;
}
.act-btn-primary-create {
    background: #2563eb;
    border: 1px solid #2563eb;
    color: #ffffff;
    font-weight: 600;
    font-size: 13.5px;
    border-radius: 10px;
    padding: 9px 20px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}
.act-btn-primary-create:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
    color: #ffffff;
    text-decoration: none;
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
}

/* ==========================================================================
   Metrics Row (5 Cards)
   ========================================================================== */
.act-metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr) 1.4fr;
    gap: 14px;
    margin-bottom: 24px;
}
@media (max-width: 1200px) {
    .act-metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 640px) {
    .act-metrics-grid {
        grid-template-columns: 1fr;
    }
}

.act-metric-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
.act-metric-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border-color: #cbd5e1;
}
.act-metric-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 18px;
}
.act-metric-icon.icon-blue {
    background: #eff6ff;
    color: #2563eb;
}
.act-metric-icon.icon-purple {
    background: #f5f3ff;
    color: #7c3aed;
}
.act-metric-icon.icon-green {
    background: #ecfdf5;
    color: #10b981;
}
.act-metric-icon.icon-amber {
    background: #fffbeb;
    color: #f59e0b;
}

.act-metric-info {
    display: flex;
    flex-direction: column;
}
.act-metric-num {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
    margin-bottom: 3px;
    font-feature-settings: "tnum";
}
.act-metric-label {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
    margin: 0;
}

/* Status Card */
.act-status-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 18px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
.act-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
    font-size: 12px;
    font-weight: 700;
    border-radius: 9999px;
    padding: 4px 12px;
    width: fit-content;
    margin-bottom: 6px;
}
.act-pulse-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #10b981;
    display: inline-block;
    box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
    animation: actPulse 2s infinite;
}
@keyframes actPulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}
.act-status-text {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
    line-height: 1.4;
}

/* ==========================================================================
   Activity Planner Main Card
   ========================================================================== */
.act-planner-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    margin-bottom: 24px;
}

/* Section Title */
.act-section-heading {
    margin-bottom: 18px;
}
.act-section-title-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
}
.act-section-bar {
    width: 4px;
    height: 18px;
    background: #2563eb;
    border-radius: 2px;
    display: inline-block;
}
.act-section-title {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}
.act-section-subtitle {
    font-size: 13.5px;
    color: #64748b;
    margin: 0;
    padding-left: 12px;
}

/* Filters Row */
.act-filters-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.act-search-box {
    position: relative;
    flex: 1 1 260px;
    min-width: 200px;
}
.act-search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    pointer-events: none;
    z-index: 3;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
}
input[type="text"].act-search-input,
.act-search-input {
    width: 100% !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 10px !important;
    padding: 10px 16px 10px 44px !important;
    padding-left: 44px !important;
    font-size: 13.5px !important;
    color: #1e293b !important;
    height: 42px !important;
    outline: none !important;
    transition: all 0.2s ease;
}
.act-search-input:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
}
.act-search-input::placeholder {
    color: #94a3b8 !important;
    font-size: 13.5px !important;
    opacity: 1 !important;
}
.act-filter-select {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 9px 32px 9px 14px;
    font-size: 13.5px;
    color: #334155;
    font-weight: 500;
    outline: none;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    transition: all 0.2s ease;
    min-width: 130px;
}
.act-filter-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.act-btn-more-filters {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 9px 16px;
    font-size: 13.5px;
    color: #334155;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.act-btn-more-filters:hover, .act-btn-more-filters.active {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
}
.act-more-filters-panel {
    display: none;
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 20px;
}

/* ==========================================================================
   Toolbar (Count, Per Page, Batch Actions)
   ========================================================================== */
.act-table-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f1f5f9;
}
.act-toolbar-left {
    display: flex;
    align-items: center;
    gap: 14px;
}
.act-records-count {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
}
.act-select-per-page {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 6px 28px 6px 12px;
    font-size: 13px;
    font-weight: 500;
    color: #475569;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    cursor: pointer;
    outline: none;
}
.act-toolbar-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.act-btn-toolbar {
    font-size: 13px;
    font-weight: 600;
    border-radius: 9px;
    padding: 7px 15px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}
/* Update order button */
.act-btn-toolbar.update-order {
    background: #ffffff;
    border-color: #2563eb;
    color: #2563eb;
}
.act-btn-toolbar.update-order:hover {
    background: #eff6ff;
    color: #1d4ed8;
}
/* Change status button */
.act-btn-toolbar.change-status {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #94a3b8;
}
.act-btn-toolbar.change-status:not(:disabled) {
    background: #ffffff;
    border-color: #2563eb;
    color: #2563eb;
    cursor: pointer;
}
.act-btn-toolbar.change-status:not(:disabled):hover {
    background: #eff6ff;
    color: #1d4ed8;
}
/* Delete button */
.act-btn-toolbar.dt-delete {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #94a3b8;
}
.act-btn-toolbar.dt-delete:not(:disabled) {
    background: #fff1f2;
    border-color: #fecdd3;
    color: #e11d48;
    cursor: pointer;
}
.act-btn-toolbar.dt-delete:not(:disabled):hover {
    background: #ffe4e6;
    color: #be123c;
}

/* ==========================================================================
   DataTable Design Overrides
   ========================================================================== */
.act-planner-card .table-responsive {
    overflow-x: auto;
    border-radius: 10px;
}
#dataTable {
    margin-bottom: 0 !important;
    border-collapse: separate;
    border-spacing: 0;
    width: 100% !important;
}
#dataTable thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 12.5px;
    font-weight: 600;
    text-transform: none;
    letter-spacing: 0.01em;
    padding: 12px 14px;
    border-top: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: middle;
    white-space: nowrap;
}
#dataTable tbody td {
    padding: 13px 14px;
    vertical-align: middle;
    color: #334155;
    font-size: 13.5px;
    border-bottom: 1px solid #f1f5f9;
}
#dataTable tbody tr:hover td {
    background: #f8fafc;
}
#dataTable tbody tr.selected td {
    background: #eff6ff !important;
}

/* Custom Table Badges */
.act-badge-notify {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1d4ed8;
    font-size: 11.5px;
    font-weight: 600;
    border-radius: 9999px;
    padding: 3px 10px;
}
.act-badge-type {
    display: inline-flex;
    align-items: center;
    font-size: 12px;
    font-weight: 600;
    border-radius: 6px;
    padding: 3px 8px;
}
.act-badge-type.type-upcoming {
    background: #f5f3ff;
    border: 1px solid #ddd6fe;
    color: #6d28d9;
}
.act-badge-type.type-old {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #475569;
}
.act-table-status {
    display: inline-block;
    font-size: 12px;
    font-weight: 600;
    border-radius: 9999px;
    padding: 3px 10px;
}
.act-status-active {
    background: #ecfdf5 !important;
    border: 1px solid #a7f3d0 !important;
    color: #059669 !important;
}
.act-status-inactive {
    background: #fffbeb !important;
    border: 1px solid #fde68a !important;
    color: #d97706 !important;
}
.act-order-input {
    width: 60px !important;
    height: 32px !important;
    text-align: center;
    font-size: 13px !important;
    font-weight: 600;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    background: #ffffff;
    display: inline-block;
    padding: 2px 4px;
}
.act-order-input:focus {
    border-color: #2563eb;
    outline: none;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
}
.act-table-action-edit {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #2563eb !important;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
    transition: all 0.2s ease;
    text-decoration: none !important;
}
.act-table-action-edit:hover {
    background: #2563eb;
    border-color: #2563eb;
    color: #ffffff !important;
}
.act-table-action-edit .badge {
    background: transparent !important;
    color: inherit !important;
    padding: 0 !important;
    font-size: inherit !important;
}

/* Empty State */
.act-empty-state {
    padding: 44px 20px;
    text-align: center;
}
.act-empty-icon-wrap {
    margin-bottom: 14px;
}
.act-empty-illustration {
    display: inline-block;
    animation: actFloat 3s ease-in-out infinite;
}
@keyframes actFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}
.act-empty-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 6px;
}
.act-empty-desc {
    font-size: 13.5px;
    color: #64748b;
    max-width: 440px;
    margin: 0 auto 18px;
    line-height: 1.5;
}
.act-btn-create-empty {
    background: #2563eb;
    border-color: #2563eb;
    border-radius: 9px;
    padding: 9px 20px;
    font-weight: 600;
    font-size: 13.5px;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    display: inline-flex;
    align-items: center;
}
.act-btn-create-empty:hover {
    background: #1d4ed8;
    color: #ffffff;
}

/* Footer & Pagination */
.act-table-footer {
    padding-top: 14px;
    border-top: 1px solid #f1f5f9;
}
.dataTables_info {
    font-size: 13px !important;
    color: #64748b !important;
    font-weight: 500;
    padding: 0 !important;
}
.dataTables_paginate {
    margin: 0 !important;
}
.dataTables_paginate .pagination {
    margin: 0;
    gap: 4px;
    display: flex;
    align-items: center;
}
.dataTables_paginate .paginate_button {
    border-radius: 8px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    padding: 6px 12px !important;
    color: #475569 !important;
    border: 1px solid #e2e8f0 !important;
    background: #ffffff !important;
    transition: all 0.15s ease;
    cursor: pointer;
}
.dataTables_paginate .paginate_button:hover:not(.disabled) {
    background: #f1f5f9 !important;
    color: #0f172a !important;
    border-color: #cbd5e1 !important;
}
.dataTables_paginate .paginate_button.current {
    background: #2563eb !important;
    border-color: #2563eb !important;
    color: #ffffff !important;
}
.dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f8fafc !important;
}

/* ==========================================================================
   Bottom Info Flow Card: Backend to Member App
   ========================================================================== */
.act-flow-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 18px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
}
.act-flow-left {
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}
.act-flow-title-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
}
.act-flow-title {
    font-size: 14.5px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}
.act-flow-steps {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.act-flow-step {
    display: flex;
    align-items: center;
    gap: 8px;
}
.act-flow-step-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
}
.act-flow-step-icon.step-1 {
    background: #eff6ff;
    color: #2563eb;
}
.act-flow-step-icon.step-2 {
    background: #f5f3ff;
    color: #7c3aed;
}
.act-flow-step-icon.step-3 {
    background: #ecfdf5;
    color: #10b981;
}
.act-flow-step-num {
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
    margin-right: 4px;
}
.act-flow-step-text {
    font-size: 13px;
    color: #475569;
    font-weight: 500;
}
.act-flow-arrow {
    color: #94a3b8;
    font-size: 13px;
}

.act-flow-right-link {
    font-size: 13.5px;
    font-weight: 600;
    color: #2563eb;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    text-decoration: none;
    transition: color 0.2s ease;
}
.act-flow-right-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

/* Modal styling */
.act-modal-header {
    border-bottom: 1px solid #f1f5f9;
    padding: 18px 24px;
}
.act-modal-body {
    padding: 24px;
}
.act-modal-footer {
    border-top: 1px solid #f1f5f9;
    padding: 14px 24px;
}
.act-setting-item {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    padding: 12px 0;
    border-bottom: 1px solid #f8fafc;
}
.act-setting-item:last-child {
    border-bottom: none;
}
.act-setting-label {
    font-size: 13.5px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 2px;
}
.act-setting-desc {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
}
</style>
@endpush

@section('content')
<div class="act-page-wrapper layout-px-spacing">

    <!-- Top Breadcrumb & Header -->
    <div class="act-top-header">
        <div class="act-breadcrumb">
            <span class="act-crumb-parent">Achievements &amp; Hub</span>
            <span class="act-crumb-sep">/</span>
            <span class="act-crumb-current">Activities</span>
        </div>
        <div class="act-header-content">
            <div>
                <h1 class="act-header-title">Activities</h1>
                <p class="act-header-subtitle">Create club activities and notify members through the mobile app.</p>
            </div>
            <div class="act-header-actions">
                <button type="button" class="act-btn-outline-settings" data-toggle="modal" data-target="#notificationSettingsModal">
                    <i class="fa fa-bell-o"></i> Notification settings
                </button>
                <a href="{{ route('nutritionPanel.activities.create') }}" class="act-btn-primary-create">
                    <i class="fa fa-plus"></i> Create activity
                </a>
            </div>
        </div>
    </div>

    <!-- 5-Metric Cards Grid -->
    <div class="act-metrics-grid">
        <!-- Card 1: Total Activities -->
        <div class="act-metric-card">
            <div class="act-metric-icon icon-blue">
                <i class="fa fa-file-text-o"></i>
            </div>
            <div class="act-metric-info">
                <span class="act-metric-num" id="act-stat-total">{{ $totalActivities ?? 0 }}</span>
                <span class="act-metric-label">Total activities</span>
            </div>
        </div>

        <!-- Card 2: Scheduled -->
        <div class="act-metric-card">
            <div class="act-metric-icon icon-purple">
                <i class="fa fa-calendar-o"></i>
            </div>
            <div class="act-metric-info">
                <span class="act-metric-num">{{ $scheduledActivities ?? 0 }}</span>
                <span class="act-metric-label">Scheduled</span>
            </div>
        </div>

        <!-- Card 3: Published -->
        <div class="act-metric-card">
            <div class="act-metric-icon icon-green">
                <i class="fa fa-paper-plane-o"></i>
            </div>
            <div class="act-metric-info">
                <span class="act-metric-num">{{ $publishedActivities ?? 0 }}</span>
                <span class="act-metric-label">Published</span>
            </div>
        </div>

        <!-- Card 4: Notifications sent -->
        <div class="act-metric-card">
            <div class="act-metric-icon icon-amber">
                <i class="fa fa-bell-o"></i>
            </div>
            <div class="act-metric-info">
                <span class="act-metric-num">{{ $notificationsSent ?? 0 }}</span>
                <span class="act-metric-label">Notifications sent</span>
            </div>
        </div>

        <!-- Card 5: App Notifications Ready Status Card -->
        <div class="act-status-card">
            <div class="act-status-pill">
                <span class="act-pulse-dot"></span>
                <span>App notifications ready</span>
            </div>
            <p class="act-status-text">Members are notified when an activity is published.</p>
        </div>
    </div>

    <!-- Activity Planner Main Card -->
    <div class="act-planner-card data-table-container">
        <!-- Section Heading -->
        <div class="act-section-heading">
            <div class="act-section-title-wrap">
                <span class="act-section-bar"></span>
                <h2 class="act-section-title">Activity planner</h2>
            </div>
            <p class="act-section-subtitle">Schedule, publish and manage member activities.</p>
        </div>

        <!-- Filter Bar -->
        <div class="act-filters-bar">
            <!-- Search Input -->
            <div class="act-search-box">
                <span class="act-search-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" id="act-search-input" class="act-search-input" placeholder="Search activities..." autocomplete="off" style="padding-left: 44px !important;">
            </div>

            <!-- Filter: Activity Type -->
            <select id="act-filter-type" class="act-filter-select">
                <option value="all">All types</option>
                <option value="2">Upcoming Activity</option>
                <option value="1">Old Activity</option>
            </select>

            <!-- Filter: Dates -->
            <select id="act-filter-date" class="act-filter-select">
                <option value="all">All dates</option>
                <option value="today">Today</option>
                <option value="upcoming">Upcoming</option>
                <option value="past">Past</option>
            </select>

            <!-- Filter: Status -->
            <select id="act-filter-status" class="act-filter-select">
                <option value="all">All statuses</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>

            <!-- More Filters Button -->
            <button type="button" id="act-more-filters-btn" class="act-btn-more-filters">
                <i class="fa fa-filter"></i>
                <span>More filters</span>
                <i class="fa fa-angle-down"></i>
            </button>
        </div>

        <!-- Collapsible More Filters Panel -->
        <div id="act-more-filters-panel" class="act-more-filters-panel">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small"><strong>Quick Actions:</strong></span>
                    <button type="button" id="act-clear-filters-btn" class="btn btn-sm btn-outline-secondary">
                        <i class="fa fa-times me-1"></i> Clear all filters
                    </button>
                </div>
                <span class="text-muted small">Use search and dropdowns above to refine activity listings.</span>
            </div>
        </div>

        <!-- Toolbar (Count, Per Page, Actions) -->
        <div class="act-table-toolbar">
            <div class="act-toolbar-left">
                <span id="act-records-count" class="act-records-count">0 activities</span>
                <select id="act-per-page" class="act-select-per-page">
                    <option value="20" selected>20 per page</option>
                    <option value="50">50 per page</option>
                    <option value="75">75 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
            <div class="act-toolbar-right">
                <button type="button" class="act-btn-toolbar update-order" title="Update activity order numbers">
                    <i class="fa fa-arrows-v"></i> Update order
                </button>
                <button type="button" class="act-btn-toolbar change-status" title="Toggle active/inactive status" disabled>
                    <i class="fa fa-exchange"></i> Change status
                </button>
                <button type="button" class="act-btn-toolbar dt-delete" title="Delete selected activities" disabled>
                    <i class="fa fa-trash-o"></i> Delete
                </button>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-responsive">
            <table id="dataTable" class="table table-hover dataTable"
                data-url="{{ route('nutritionPanel.activities.getActivities') }}"
                data-change-status-url="{{ route('nutritionPanel.activities.changeStatus') }}"
                data-destroy-url="{{ route('nutritionPanel.activities.destroy') }}"
                data-update-order-url="{{ route('nutritionPanel.activities.updateOrder') }}"
                data-create-url="{{ route('nutritionPanel.activities.create') }}">
                <thead>
                    <tr>
                        <th class="checkbox-column">#</th>
                        <th>Name</th>
                        <th>Activity type</th>
                        <th>Date</th>
                        <th>App notification</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Bottom Flow Info Card: Backend to Member App -->
    <div class="act-flow-card">
        <div class="act-flow-left">
            <div class="act-flow-title-wrap">
                <span class="act-section-bar"></span>
                <h3 class="act-flow-title">From backend to member app</h3>
            </div>
            <div class="act-flow-steps">
                <div class="act-flow-step">
                    <div class="act-flow-step-icon step-1">
                        <i class="fa fa-file-text-o"></i>
                    </div>
                    <span class="act-flow-step-num">1</span>
                    <span class="act-flow-step-text">Create activity</span>
                </div>
                <i class="fa fa-arrow-right act-flow-arrow"></i>
                <div class="act-flow-step">
                    <div class="act-flow-step-icon step-2">
                        <i class="fa fa-calendar-o"></i>
                    </div>
                    <span class="act-flow-step-num">2</span>
                    <span class="act-flow-step-text">Set date and notification</span>
                </div>
                <i class="fa fa-arrow-right act-flow-arrow"></i>
                <div class="act-flow-step">
                    <div class="act-flow-step-icon step-3">
                        <i class="fa fa-bell-o"></i>
                    </div>
                    <span class="act-flow-step-num">3</span>
                    <span class="act-flow-step-text">Publish and notify members</span>
                </div>
            </div>
        </div>
        <div>
            <a href="javascript:void(0);" class="act-flow-right-link" data-toggle="modal" data-target="#notificationSettingsModal">
                Manage notification settings <i class="fa fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

</div>

<!-- Notification Settings Modal -->
<div class="modal fade" id="notificationSettingsModal" tabindex="-1" role="dialog" aria-labelledby="notificationSettingsModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <div class="act-modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title font-weight-bold text-dark" id="notificationSettingsModalTitle">
                    <i class="fa fa-bell-o text-primary me-2"></i> Notification Settings
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="act-modal-body">
                <div class="act-setting-item">
                    <div>
                        <div class="act-setting-label">Notify on publish</div>
                        <p class="act-setting-desc">Send push notifications to members immediately when an activity is published.</p>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="notifSwitch1" checked>
                        <label class="custom-control-label" for="notifSwitch1"></label>
                    </div>
                </div>

                <div class="act-setting-item">
                    <div>
                        <div class="act-setting-label">24-hour activity reminder</div>
                        <p class="act-setting-desc">Automatically alert enrolled members one day before the activity begins.</p>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="notifSwitch2" checked>
                        <label class="custom-control-label" for="notifSwitch2"></label>
                    </div>
                </div>

                <div class="act-setting-item">
                    <div>
                        <div class="act-setting-label">In-app activity banner</div>
                        <p class="act-setting-desc">Display upcoming activities prominently at top of member app feed.</p>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="notifSwitch3" checked>
                        <label class="custom-control-label" for="notifSwitch3"></label>
                    </div>
                </div>
            </div>
            <div class="act-modal-footer d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                <button type="button" class="btn btn-primary btn-save-notif-settings" style="border-radius: 8px;">Save settings</button>
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
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/activities/view.js') }}"></script>
@endpush
