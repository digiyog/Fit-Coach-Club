@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Tips & Video Links | ' . __('language.page_main_title'))

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
/* ==========================================================================
   Tips & Video Links Page - Modern SaaS Redesign Styles
   ========================================================================== */
.tip-page-wrapper {
    padding: 10px 0 40px;
    color: #1e293b;
    font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Header & Breadcrumb */
.tip-top-header {
    margin-bottom: 24px;
}
.tip-breadcrumb {
    font-size: 13px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-weight: 500;
}
.tip-breadcrumb .tip-crumb-parent {
    color: #64748b;
}
.tip-breadcrumb .tip-crumb-sep {
    color: #cbd5e1;
}
.tip-breadcrumb .tip-crumb-current {
    color: #0f172a;
    font-weight: 600;
}
.tip-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 16px;
}
.tip-header-title {
    font-size: 30px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.025em;
    line-height: 1.2;
    margin-bottom: 4px;
}
.tip-header-subtitle {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 0;
}
.tip-header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

/* Header Buttons */
.tip-btn-outline-settings {
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
.tip-btn-outline-settings:hover {
    background: #eff6ff;
    color: #1d4ed8;
    border-color: #1d4ed8;
    text-decoration: none;
}
.tip-btn-primary-create {
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
.tip-btn-primary-create:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
    color: #ffffff;
    text-decoration: none;
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
}

/* ==========================================================================
   Metrics Row (5 Cards)
   ========================================================================== */
.tip-metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr) 1.4fr;
    gap: 14px;
    margin-bottom: 24px;
}
@media (max-width: 1200px) {
    .tip-metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 640px) {
    .tip-metrics-grid {
        grid-template-columns: 1fr;
    }
}

.tip-metric-card {
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
.tip-metric-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border-color: #cbd5e1;
}
.tip-metric-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 18px;
}
.tip-metric-icon.icon-blue {
    background: #eff6ff;
    color: #2563eb;
}
.tip-metric-icon.icon-purple {
    background: #f5f3ff;
    color: #7c3aed;
}
.tip-metric-icon.icon-green {
    background: #ecfdf5;
    color: #10b981;
}
.tip-metric-icon.icon-amber {
    background: #fffbeb;
    color: #f59e0b;
}

.tip-metric-info {
    display: flex;
    flex-direction: column;
}
.tip-metric-num {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
    margin-bottom: 3px;
    font-feature-settings: "tnum";
}
.tip-metric-label {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
    margin: 0;
}

/* Status Card */
.tip-status-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 18px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
.tip-status-pill {
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
.tip-pulse-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #10b981;
    display: inline-block;
    box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
    animation: tipPulse 2s infinite;
}
@keyframes tipPulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}
.tip-status-text {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
    line-height: 1.4;
}

/* ==========================================================================
   Video Tips Library Main Card
   ========================================================================== */
.tip-planner-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    margin-bottom: 24px;
}

/* Section Title */
.tip-section-heading {
    margin-bottom: 18px;
}
.tip-section-title-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
}
.tip-section-bar {
    width: 4px;
    height: 18px;
    background: #2563eb;
    border-radius: 2px;
    display: inline-block;
}
.tip-section-title {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}
.tip-section-subtitle {
    font-size: 13.5px;
    color: #64748b;
    margin: 0;
    padding-left: 12px;
}

/* Filters Row */
.tip-filters-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.tip-search-box {
    position: relative;
    flex: 1 1 260px;
    min-width: 200px;
}
.tip-search-icon {
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
input[type="text"].tip-search-input,
.tip-search-input {
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
.tip-search-input:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
}
.tip-search-input::placeholder {
    color: #94a3b8 !important;
    font-size: 13.5px !important;
    opacity: 1 !important;
}
.tip-filter-select {
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
.tip-filter-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.tip-btn-more-filters {
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
.tip-btn-more-filters:hover, .tip-btn-more-filters.active {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
}
.tip-grid-toggle-btn {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    width: 40px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    font-size: 14px;
}
.tip-more-filters-panel {
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
.tip-table-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f1f5f9;
}
.tip-toolbar-left {
    display: flex;
    align-items: center;
    gap: 14px;
}
.tip-records-count {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
}
.tip-select-per-page {
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
.tip-toolbar-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.tip-btn-toolbar {
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
.tip-btn-toolbar.update-order {
    background: #ffffff;
    border-color: #2563eb;
    color: #2563eb;
}
.tip-btn-toolbar.update-order:hover {
    background: #eff6ff;
    color: #1d4ed8;
}
/* Change status button */
.tip-btn-toolbar.change-status {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #94a3b8;
}
.tip-btn-toolbar.change-status:not(:disabled) {
    background: #ffffff;
    border-color: #2563eb;
    color: #2563eb;
    cursor: pointer;
}
.tip-btn-toolbar.change-status:not(:disabled):hover {
    background: #eff6ff;
    color: #1d4ed8;
}
/* Delete button */
.tip-btn-toolbar.dt-delete {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #94a3b8;
}
.tip-btn-toolbar.dt-delete:not(:disabled) {
    background: #fff1f2;
    border-color: #fecdd3;
    color: #e11d48;
    cursor: pointer;
}
.tip-btn-toolbar.dt-delete:not(:disabled):hover {
    background: #ffe4e6;
    color: #be123c;
}

/* ==========================================================================
   DataTable Design Overrides
   ========================================================================== */
.tip-planner-card .table-responsive {
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
.tip-coach-pill {
    display: inline-flex;
    align-items: center;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
}
.tip-video-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626 !important;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
    text-decoration: none !important;
    transition: all 0.2s ease;
}
.tip-video-pill:hover {
    background: #fee2e2;
    border-color: #fca5a5;
    color: #b91c1c !important;
}
.tip-link-external {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: all 0.2s ease;
    text-decoration: none !important;
}
.tip-link-external:hover {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
}
.tip-table-status {
    display: inline-block;
    font-size: 12px;
    font-weight: 600;
    border-radius: 9999px;
    padding: 3px 10px;
}
.tip-status-active {
    background: #ecfdf5 !important;
    border: 1px solid #a7f3d0 !important;
    color: #059669 !important;
}
.tip-status-inactive {
    background: #fffbeb !important;
    border: 1px solid #fde68a !important;
    color: #d97706 !important;
}
.tip-order-input {
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
.tip-order-input:focus {
    border-color: #2563eb;
    outline: none;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
}
.tip-table-action-edit {
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
.tip-table-action-edit:hover {
    background: #2563eb;
    border-color: #2563eb;
    color: #ffffff !important;
}
.tip-table-action-edit .badge {
    background: transparent !important;
    color: inherit !important;
    padding: 0 !important;
    font-size: inherit !important;
}

/* Empty State */
.tip-empty-state {
    padding: 44px 20px;
    text-align: center;
}
.tip-empty-icon-wrap {
    margin-bottom: 14px;
}
.tip-empty-illustration {
    display: inline-block;
    animation: tipFloat 3s ease-in-out infinite;
}
@keyframes tipFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}
.tip-empty-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 6px;
}
.tip-empty-desc {
    font-size: 13.5px;
    color: #64748b;
    max-width: 480px;
    margin: 0 auto 18px;
    line-height: 1.5;
}
.tip-btn-create-empty {
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
.tip-btn-create-empty:hover {
    background: #1d4ed8;
    color: #ffffff;
}

/* Footer & Pagination */
.tip-table-footer {
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
   Bottom Info Flow Card: Coach Tip to Member App
   ========================================================================== */
.tip-flow-card {
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
.tip-flow-left {
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}
.tip-flow-title-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
}
.tip-flow-title {
    font-size: 14.5px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}
.tip-flow-steps {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.tip-flow-step {
    display: flex;
    align-items: center;
    gap: 8px;
}
.tip-flow-step-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
}
.tip-flow-step-icon.step-1 {
    background: #eff6ff;
    color: #2563eb;
}
.tip-flow-step-icon.step-2 {
    background: #f5f3ff;
    color: #7c3aed;
}
.tip-flow-step-icon.step-3 {
    background: #ecfdf5;
    color: #10b981;
}
.tip-flow-step-num {
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
    margin-right: 4px;
}
.tip-flow-step-text {
    font-size: 13px;
    color: #475569;
    font-weight: 500;
}
.tip-flow-arrow {
    color: #94a3b8;
    font-size: 13px;
}

.tip-flow-right-link {
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
.tip-flow-right-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

/* Modal styling */
.tip-modal-header {
    border-bottom: 1px solid #f1f5f9;
    padding: 18px 24px;
}
.tip-modal-body {
    padding: 24px;
}
.tip-modal-footer {
    border-top: 1px solid #f1f5f9;
    padding: 14px 24px;
}
.tip-setting-item {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    padding: 12px 0;
    border-bottom: 1px solid #f8fafc;
}
.tip-setting-item:last-child {
    border-bottom: none;
}
.tip-setting-label {
    font-size: 13.5px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 2px;
}
.tip-setting-desc {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
}
</style>
@endpush

@section('content')
<div class="tip-page-wrapper layout-px-spacing">

    <!-- Top Breadcrumb & Header -->
    <div class="tip-top-header">
        <div class="tip-breadcrumb">
            <span class="tip-crumb-parent">Achievements &amp; Hub</span>
            <span class="tip-crumb-sep">/</span>
            <span class="tip-crumb-current">Tips (Video Links)</span>
        </div>
        <div class="tip-header-content">
            <div>
                <h1 class="tip-header-title">Tips &amp; video links</h1>
                <p class="tip-header-subtitle">Share coach-curated videos and practical wellness tips with members.</p>
            </div>
            <div class="tip-header-actions">
                <button type="button" class="tip-btn-outline-settings" data-toggle="modal" data-target="#contentSettingsModal">
                    <i class="fa fa-sliders"></i> Content settings
                </button>
                <a href="{{ route('nutritionPanel.tips.create') }}" class="tip-btn-primary-create">
                    <i class="fa fa-plus"></i> Add video tip
                </a>
            </div>
        </div>
    </div>

    <!-- 5-Metric Cards Grid -->
    <div class="tip-metrics-grid">
        <!-- Card 1: Total Tips -->
        <div class="tip-metric-card">
            <div class="tip-metric-icon icon-blue">
                <i class="fa fa-lightbulb-o"></i>
            </div>
            <div class="tip-metric-info">
                <span class="tip-metric-num" id="tip-stat-total">{{ $totalTips ?? 0 }}</span>
                <span class="tip-metric-label">Total tips</span>
            </div>
        </div>

        <!-- Card 2: Coach Contributors -->
        <div class="tip-metric-card">
            <div class="tip-metric-icon icon-purple">
                <i class="fa fa-users"></i>
            </div>
            <div class="tip-metric-info">
                <span class="tip-metric-num">{{ $coachContributors ?? 0 }}</span>
                <span class="tip-metric-label">Coach contributors</span>
            </div>
        </div>

        <!-- Card 3: Published -->
        <div class="tip-metric-card">
            <div class="tip-metric-icon icon-green">
                <i class="fa fa-paper-plane-o"></i>
            </div>
            <div class="tip-metric-info">
                <span class="tip-metric-num">{{ $publishedTips ?? 0 }}</span>
                <span class="tip-metric-label">Published</span>
            </div>
        </div>

        <!-- Card 4: Drafts -->
        <div class="tip-metric-card">
            <div class="tip-metric-icon icon-amber">
                <i class="fa fa-file-text-o"></i>
            </div>
            <div class="tip-metric-info">
                <span class="tip-metric-num">{{ $draftTips ?? 0 }}</span>
                <span class="tip-metric-label">Drafts</span>
            </div>
        </div>

        <!-- Card 5: YouTube Links Ready Status Card -->
        <div class="tip-status-card">
            <div class="tip-status-pill">
                <span class="tip-pulse-dot"></span>
                <span>YouTube links ready</span>
            </div>
            <p class="tip-status-text">Published tips appear in the member app.</p>
        </div>
    </div>

    <!-- Video Tips Library Main Card -->
    <div class="tip-planner-card data-table-container">
        <!-- Section Heading -->
        <div class="tip-section-heading">
            <div class="tip-section-title-wrap">
                <span class="tip-section-bar"></span>
                <h2 class="tip-section-title">Video tips library</h2>
            </div>
            <p class="tip-section-subtitle">Organize coach videos, control their order and publish them to members.</p>
        </div>

        <!-- Filter Bar -->
        <div class="tip-filters-bar">
            <!-- Search Input -->
            <div class="tip-search-box">
                <span class="tip-search-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" id="tip-search-input" class="tip-search-input" placeholder="Search tips..." autocomplete="off" style="padding-left: 44px !important;">
            </div>

            <!-- Filter: Coaches -->
            <select id="tip-filter-coach" class="tip-filter-select">
                <option value="all">All coaches</option>
                @if(isset($coachesList) && count($coachesList) > 0)
                    @foreach($coachesList as $coach)
                        <option value="{{ $coach }}">{{ $coach }}</option>
                    @endforeach
                @endif
            </select>

            <!-- Filter: Status -->
            <select id="tip-filter-status" class="tip-filter-select">
                <option value="all">All statuses</option>
                <option value="1">Published</option>
                <option value="0">Drafts</option>
            </select>

            <!-- More Filters Button -->
            <button type="button" id="tip-more-filters-btn" class="tip-btn-more-filters">
                <i class="fa fa-filter"></i>
                <span>More filters</span>
                <i class="fa fa-angle-down"></i>
            </button>

            <!-- Grid icon indicator -->
            <div class="tip-grid-toggle-btn d-none d-md-inline-flex" title="Table View">
                <i class="fa fa-th-large"></i>
            </div>
        </div>

        <!-- Collapsible More Filters Panel -->
        <div id="tip-more-filters-panel" class="tip-more-filters-panel">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small"><strong>Quick Actions:</strong></span>
                    <button type="button" id="tip-clear-filters-btn" class="btn btn-sm btn-outline-secondary">
                        <i class="fa fa-times me-1"></i> Clear all filters
                    </button>
                </div>
                <span class="text-muted small">Use search and coach dropdown above to refine video tips.</span>
            </div>
        </div>

        <!-- Toolbar (Count, Per Page, Actions) -->
        <div class="tip-table-toolbar">
            <div class="tip-toolbar-left">
                <span id="tip-records-count" class="tip-records-count">0 video tips</span>
                <select id="tip-per-page" class="tip-select-per-page">
                    <option value="20" selected>20 per page</option>
                    <option value="50">50 per page</option>
                    <option value="75">75 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
            <div class="tip-toolbar-right">
                <button type="button" class="tip-btn-toolbar update-order" title="Update video tip order numbers">
                    <i class="fa fa-arrows-v"></i> Update order
                </button>
                <button type="button" class="tip-btn-toolbar change-status" title="Toggle published/draft status" disabled>
                    <i class="fa fa-exchange"></i> Change status
                </button>
                <button type="button" class="tip-btn-toolbar dt-delete" title="Delete selected video tips" disabled>
                    <i class="fa fa-trash-o"></i> Delete
                </button>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-responsive">
            <table id="dataTable" class="table table-hover dataTable"
                data-url="{{ route('nutritionPanel.tips.getTips') }}"
                data-change-status-url="{{ route('nutritionPanel.tips.changeStatus') }}"
                data-destroy-url="{{ route('nutritionPanel.tips.destroy') }}"
                data-update-order-url="{{ route('nutritionPanel.tips.updateOrder') }}"
                data-create-url="{{ route('nutritionPanel.tips.create') }}">
                <thead>
                    <tr>
                        <th class="checkbox-column">#</th>
                        <th>Name</th>
                        <th>Coach name</th>
                        <th>YouTube link</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Bottom Flow Info Card: Coach Tip to Member App -->
    <div class="tip-flow-card">
        <div class="tip-flow-left">
            <div class="tip-flow-title-wrap">
                <span class="tip-section-bar"></span>
                <h3 class="tip-flow-title">From coach tip to member app</h3>
            </div>
            <div class="tip-flow-steps">
                <div class="tip-flow-step">
                    <div class="tip-flow-step-icon step-1">
                        <i class="fa fa-file-text-o"></i>
                    </div>
                    <span class="tip-flow-step-num">1</span>
                    <span class="tip-flow-step-text">Add title and coach</span>
                </div>
                <i class="fa fa-arrow-right tip-flow-arrow"></i>
                <div class="tip-flow-step">
                    <div class="tip-flow-step-icon step-2">
                        <i class="fa fa-play"></i>
                    </div>
                    <span class="tip-flow-step-num">2</span>
                    <span class="tip-flow-step-text">Paste YouTube link</span>
                </div>
                <i class="fa fa-arrow-right tip-flow-arrow"></i>
                <div class="tip-flow-step">
                    <div class="tip-flow-step-icon step-3">
                        <i class="fa fa-paper-plane-o"></i>
                    </div>
                    <span class="tip-flow-step-num">3</span>
                    <span class="tip-flow-step-text">Publish to members</span>
                </div>
            </div>
        </div>
        <div>
            <a href="javascript:void(0);" class="tip-flow-right-link" data-toggle="modal" data-target="#contentSettingsModal">
                Manage content settings <i class="fa fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

</div>

<!-- Content Settings Modal -->
<div class="modal fade" id="contentSettingsModal" tabindex="-1" role="dialog" aria-labelledby="contentSettingsModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <div class="tip-modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title font-weight-bold text-dark" id="contentSettingsModalTitle">
                    <i class="fa fa-sliders text-primary me-2"></i> Content &amp; Video Settings
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="tip-modal-body">
                <div class="tip-setting-item">
                    <div>
                        <div class="tip-setting-label">Auto-fetch YouTube previews</div>
                        <p class="tip-setting-desc">Automatically fetch high-definition video thumbnails from submitted YouTube links.</p>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="contentSwitch1" checked>
                        <label class="custom-control-label" for="contentSwitch1"></label>
                    </div>
                </div>

                <div class="tip-setting-item">
                    <div>
                        <div class="tip-setting-label">Feature on member dashboard</div>
                        <p class="tip-setting-desc">Display the latest video tip directly on the member home screen.</p>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="contentSwitch2" checked>
                        <label class="custom-control-label" for="contentSwitch2"></label>
                    </div>
                </div>

                <div class="tip-setting-item">
                    <div>
                        <div class="tip-setting-label">Push notify new video tips</div>
                        <p class="tip-setting-desc">Alert members in their mobile app feed when a coach publishes a new video tip.</p>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="contentSwitch3" checked>
                        <label class="custom-control-label" for="contentSwitch3"></label>
                    </div>
                </div>
            </div>
            <div class="tip-modal-footer d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                <button type="button" class="btn btn-primary btn-save-content-settings" style="border-radius: 8px;">Save settings</button>
            </div>
        </div>
    </div>
</div>

<!-- Video Preview Modal Container for Ajax Loading -->
<div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none;"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/plugins/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/tips/view.js') }}"></script>
@endpush
