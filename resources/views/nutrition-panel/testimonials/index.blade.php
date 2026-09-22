@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Testimonials | ' . __('language.page_main_title'))

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
/* ==========================================================================
   Testimonials Page - Modern SaaS Redesign Styles
   ========================================================================== */
.tst-page-wrapper {
    padding: 10px 0 40px;
    color: #1e293b;
    font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Header & Breadcrumb */
.tst-top-header {
    margin-bottom: 24px;
}
.tst-breadcrumb {
    font-size: 13px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-weight: 500;
}
.tst-breadcrumb .tst-crumb-parent {
    color: #64748b;
}
.tst-breadcrumb .tst-crumb-sep {
    color: #cbd5e1;
}
.tst-breadcrumb .tst-crumb-current {
    color: #0f172a;
    font-weight: 600;
}
.tst-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 16px;
}
.tst-header-title {
    font-size: 30px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.025em;
    line-height: 1.2;
    margin-bottom: 4px;
}
.tst-header-subtitle {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 0;
}
.tst-header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

/* Header Buttons */
.tst-btn-outline-settings {
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
.tst-btn-outline-settings:hover {
    background: #eff6ff;
    color: #1d4ed8;
    border-color: #1d4ed8;
    text-decoration: none;
}
.tst-btn-primary-create {
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
.tst-btn-primary-create:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
    color: #ffffff;
    text-decoration: none;
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
}

/* ==========================================================================
   Metrics Row (5 Cards)
   ========================================================================== */
.tst-metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr) 1.4fr;
    gap: 14px;
    margin-bottom: 24px;
}
@media (max-width: 1200px) {
    .tst-metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 640px) {
    .tst-metrics-grid {
        grid-template-columns: 1fr;
    }
}

.tst-metric-card {
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
.tst-metric-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border-color: #cbd5e1;
}
.tst-metric-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 18px;
}
.tst-metric-icon.icon-blue {
    background: #eff6ff;
    color: #2563eb;
}
.tst-metric-icon.icon-purple {
    background: #f5f3ff;
    color: #7c3aed;
}
.tst-metric-icon.icon-green {
    background: #ecfdf5;
    color: #10b981;
}
.tst-metric-icon.icon-amber {
    background: #fffbeb;
    color: #f59e0b;
}

.tst-metric-info {
    display: flex;
    flex-direction: column;
}
.tst-metric-num {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
    margin-bottom: 3px;
    font-feature-settings: "tnum";
}
.tst-metric-label {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
    margin: 0;
}

/* Status Card */
.tst-status-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 18px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
.tst-status-pill {
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
.tst-pulse-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #10b981;
    display: inline-block;
    box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
    animation: tstPulse 2s infinite;
}
@keyframes tstPulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}
.tst-status-text {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
    line-height: 1.4;
}

/* ==========================================================================
   Testimonial Library Main Card
   ========================================================================== */
.tst-planner-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    margin-bottom: 24px;
}

/* Section Title */
.tst-section-heading {
    margin-bottom: 18px;
}
.tst-section-title-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
}
.tst-section-bar {
    width: 4px;
    height: 18px;
    background: #2563eb;
    border-radius: 2px;
    display: inline-block;
}
.tst-section-title {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}
.tst-section-subtitle {
    font-size: 13.5px;
    color: #64748b;
    margin: 0;
    padding-left: 12px;
}

/* Filters Row */
.tst-filters-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.tst-search-box {
    position: relative;
    flex: 1 1 260px;
    min-width: 200px;
}
.tst-search-icon {
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
input[type="text"].tst-search-input,
.tst-search-input {
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
.tst-search-input:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
}
.tst-search-input::placeholder {
    color: #94a3b8 !important;
    font-size: 13.5px !important;
    opacity: 1 !important;
}
.tst-filter-select {
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
.tst-filter-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.tst-btn-more-filters {
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
.tst-btn-more-filters:hover, .tst-btn-more-filters.active {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
}
.tst-more-filters-panel {
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
.tst-table-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f1f5f9;
}
.tst-toolbar-left {
    display: flex;
    align-items: center;
    gap: 14px;
}
.tst-records-count {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
}
.tst-select-per-page {
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
.tst-toolbar-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.tst-btn-toolbar {
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
.tst-btn-toolbar.update-order {
    background: #ffffff;
    border-color: #2563eb;
    color: #2563eb;
}
.tst-btn-toolbar.update-order:hover {
    background: #eff6ff;
    color: #1d4ed8;
}
/* Change status button */
.tst-btn-toolbar.change-status {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #94a3b8;
}
.tst-btn-toolbar.change-status:not(:disabled) {
    background: #ffffff;
    border-color: #2563eb;
    color: #2563eb;
    cursor: pointer;
}
.tst-btn-toolbar.change-status:not(:disabled):hover {
    background: #eff6ff;
    color: #1d4ed8;
}
/* Delete button */
.tst-btn-toolbar.dt-delete {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #94a3b8;
}
.tst-btn-toolbar.dt-delete:not(:disabled) {
    background: #fff1f2;
    border-color: #fecdd3;
    color: #e11d48;
    cursor: pointer;
}
.tst-btn-toolbar.dt-delete:not(:disabled):hover {
    background: #ffe4e6;
    color: #be123c;
}

/* ==========================================================================
   DataTable Design Overrides
   ========================================================================== */
.tst-planner-card .table-responsive {
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
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.03em;
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
.tst-badge-type {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 6px;
    padding: 3px 8px;
}
.tst-badge-type.type-video {
    background: #f5f3ff;
    border: 1px solid #ddd6fe;
    color: #6d28d9;
}
.tst-badge-type.type-photo {
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
}
.tst-badge-channel {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1d4ed8;
    font-size: 12px;
    font-weight: 600;
    border-radius: 9999px;
    padding: 3px 10px;
}
.tst-thumb-wrapper {
    position: relative;
    width: 44px;
    height: 44px;
    border-radius: 8px;
    overflow: hidden;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.tst-thumb-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.tst-thumb-play {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 11px;
}
.tst-link-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    color: #475569;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    transition: all 0.2s ease;
    text-decoration: none !important;
}
.tst-link-btn:hover {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
}
.tst-table-status {
    display: inline-block;
    font-size: 12px;
    font-weight: 600;
    border-radius: 9999px;
    padding: 3px 10px;
}
.tst-status-active {
    background: #ecfdf5 !important;
    border: 1px solid #a7f3d0 !important;
    color: #059669 !important;
}
.tst-status-inactive {
    background: #fffbeb !important;
    border: 1px solid #fde68a !important;
    color: #d97706 !important;
}
.tst-order-input {
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
.tst-order-input:focus {
    border-color: #2563eb;
    outline: none;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
}
.tst-table-action-edit {
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
.tst-table-action-edit:hover {
    background: #2563eb;
    border-color: #2563eb;
    color: #ffffff !important;
}
.tst-table-action-edit .badge {
    background: transparent !important;
    color: inherit !important;
    padding: 0 !important;
    font-size: inherit !important;
}

/* Empty State */
.tst-empty-state {
    padding: 44px 20px;
    text-align: center;
}
.tst-empty-icon-wrap {
    margin-bottom: 14px;
}
.tst-empty-illustration {
    display: inline-block;
    animation: tstFloat 3s ease-in-out infinite;
}
@keyframes tstFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}
.tst-empty-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 6px;
}
.tst-empty-desc {
    font-size: 13.5px;
    color: #64748b;
    max-width: 440px;
    margin: 0 auto 18px;
    line-height: 1.5;
}
.tst-btn-create-empty {
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
.tst-btn-create-empty:hover {
    background: #1d4ed8;
    color: #ffffff;
}

/* Footer & Pagination */
.tst-table-footer {
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
   Bottom Info Flow Card: Member Story to Published Testimonial
   ========================================================================== */
.tst-flow-card {
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
.tst-flow-left {
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}
.tst-flow-title-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
}
.tst-flow-title {
    font-size: 14.5px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}
.tst-flow-steps {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.tst-flow-step {
    display: flex;
    align-items: center;
    gap: 8px;
}
.tst-flow-step-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
}
.tst-flow-step-icon.step-1 {
    background: #eff6ff;
    color: #2563eb;
}
.tst-flow-step-icon.step-2 {
    background: #f5f3ff;
    color: #7c3aed;
}
.tst-flow-step-icon.step-3 {
    background: #ecfdf5;
    color: #10b981;
}
.tst-flow-step-num {
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
    margin-right: 4px;
}
.tst-flow-step-text {
    font-size: 13px;
    color: #475569;
    font-weight: 500;
}
.tst-flow-arrow {
    color: #94a3b8;
    font-size: 13px;
}

.tst-flow-right-link {
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
.tst-flow-right-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

/* Modal styling */
.tst-modal-header {
    border-bottom: 1px solid #f1f5f9;
    padding: 18px 24px;
}
.tst-modal-body {
    padding: 24px;
}
.tst-modal-footer {
    border-top: 1px solid #f1f5f9;
    padding: 14px 24px;
}
.tst-setting-item {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    padding: 12px 0;
    border-bottom: 1px solid #f8fafc;
}
.tst-setting-item:last-child {
    border-bottom: none;
}
.tst-setting-label {
    font-size: 13.5px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 2px;
}
.tst-setting-desc {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
}
</style>
@endpush

@section('content')
<div class="tst-page-wrapper layout-px-spacing">

    <!-- Top Breadcrumb & Header -->
    <div class="tst-top-header">
        <div class="tst-breadcrumb">
            <span class="tst-crumb-parent">Achievements &amp; Hub</span>
            <span class="tst-crumb-sep">/</span>
            <span class="tst-crumb-current">Testimonials</span>
        </div>
        <div class="tst-header-content">
            <div>
                <h1 class="tst-header-title">Testimonials</h1>
                <p class="tst-header-subtitle">Manage member success stories across the mobile app and website.</p>
            </div>
            <div class="tst-header-actions">
                <button type="button" class="tst-btn-outline-settings" data-toggle="modal" data-target="#displaySettingsModal">
                    <i class="fa fa-eye"></i> Display settings
                </button>
                <a href="{{ route('nutritionPanel.testimonials.create') }}" class="tst-btn-primary-create">
                    <i class="fa fa-plus"></i> Add testimonial
                </a>
            </div>
        </div>
    </div>

    <!-- 5-Metric Cards Grid -->
    <div class="tst-metrics-grid">
        <!-- Card 1: Total Testimonials -->
        <div class="tst-metric-card">
            <div class="tst-metric-icon icon-blue">
                <i class="fa fa-quote-left"></i>
            </div>
            <div class="tst-metric-info">
                <span class="tst-metric-num" id="tst-stat-total">{{ $totalTestimonials ?? 0 }}</span>
                <span class="tst-metric-label">Total testimonials</span>
            </div>
        </div>

        <!-- Card 2: Video Stories -->
        <div class="tst-metric-card">
            <div class="tst-metric-icon icon-purple">
                <i class="fa fa-play-circle-o"></i>
            </div>
            <div class="tst-metric-info">
                <span class="tst-metric-num">{{ $videoStories ?? 0 }}</span>
                <span class="tst-metric-label">Video stories</span>
            </div>
        </div>

        <!-- Card 3: Photo Stories -->
        <div class="tst-metric-card">
            <div class="tst-metric-icon icon-green">
                <i class="fa fa-picture-o"></i>
            </div>
            <div class="tst-metric-info">
                <span class="tst-metric-num">{{ $photoStories ?? 0 }}</span>
                <span class="tst-metric-label">Photo stories</span>
            </div>
        </div>

        <!-- Card 4: Published -->
        <div class="tst-metric-card">
            <div class="tst-metric-icon icon-amber">
                <i class="fa fa-paper-plane-o"></i>
            </div>
            <div class="tst-metric-info">
                <span class="tst-metric-num">{{ $publishedTestimonials ?? 0 }}</span>
                <span class="tst-metric-label">Published</span>
            </div>
        </div>

        <!-- Card 5: Mobile & Web Ready Status Card -->
        <div class="tst-status-card">
            <div class="tst-status-pill">
                <span class="tst-pulse-dot"></span>
                <span>Mobile &amp; web ready</span>
            </div>
            <p class="tst-status-text">Published stories can appear across member channels.</p>
        </div>
    </div>

    <!-- Testimonial Library Main Card -->
    <div class="tst-planner-card data-table-container">
        <!-- Section Heading -->
        <div class="tst-section-heading">
            <div class="tst-section-title-wrap">
                <span class="tst-section-bar"></span>
                <h2 class="tst-section-title">Testimonial library</h2>
            </div>
            <p class="tst-section-subtitle">Organize, publish and feature member success stories.</p>
        </div>

        <!-- Filter Bar -->
        <div class="tst-filters-bar">
            <!-- Search Input -->
            <div class="tst-search-box">
                <span class="tst-search-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" id="tst-search-input" class="tst-search-input" placeholder="Search testimonials..." autocomplete="off" style="padding-left: 44px !important;">
            </div>

            <!-- Filter: Format -->
            <select id="tst-filter-format" class="tst-filter-select">
                <option value="all">All formats</option>
                <option value="video">Video story</option>
                <option value="photo">Photo story</option>
            </select>

            <!-- Filter: Channel -->
            <select id="tst-filter-channel" class="tst-filter-select">
                <option value="all">All channels</option>
                <option value="mobile">Mobile app</option>
                <option value="web">Website</option>
                <option value="both">Both</option>
            </select>

            <!-- Filter: Status -->
            <select id="tst-filter-status" class="tst-filter-select">
                <option value="all">All statuses</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>

            <!-- More Filters Button -->
            <button type="button" id="tst-more-filters-btn" class="tst-btn-more-filters">
                <i class="fa fa-filter"></i>
                <span>More filters</span>
                <i class="fa fa-angle-down"></i>
            </button>
        </div>

        <!-- Collapsible More Filters Panel -->
        <div id="tst-more-filters-panel" class="tst-more-filters-panel">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small"><strong>Quick Actions:</strong></span>
                    <button type="button" id="tst-clear-filters-btn" class="btn btn-sm btn-outline-secondary">
                        <i class="fa fa-times me-1"></i> Clear all filters
                    </button>
                </div>
                <span class="text-muted small">Use search and dropdowns above to refine testimonial stories.</span>
            </div>
        </div>

        <!-- Toolbar (Count, Per Page, Actions) -->
        <div class="tst-table-toolbar">
            <div class="tst-toolbar-left">
                <span id="tst-records-count" class="tst-records-count">0 testimonials</span>
                <select id="tst-per-page" class="tst-select-per-page">
                    <option value="20" selected>20 per page</option>
                    <option value="50">50 per page</option>
                    <option value="75">75 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
            <div class="tst-toolbar-right">
                <button type="button" class="tst-btn-toolbar update-order" title="Update testimonial order numbers">
                    <i class="fa fa-arrows-v"></i> Update order
                </button>
                <button type="button" class="tst-btn-toolbar change-status" title="Toggle active/inactive status" disabled>
                    <i class="fa fa-exchange"></i> Change status
                </button>
                <button type="button" class="tst-btn-toolbar dt-delete" title="Delete selected testimonials" disabled>
                    <i class="fa fa-trash-o"></i> Delete
                </button>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-responsive">
            <table id="dataTable" class="table table-hover dataTable"
                data-url="{{ route('nutritionPanel.testimonials.getTestimonials') }}"
                data-change-status-url="{{ route('nutritionPanel.testimonials.changeStatus') }}"
                data-destroy-url="{{ route('nutritionPanel.testimonials.destroy') }}"
                data-update-order-url="{{ route('nutritionPanel.testimonials.updateOrder') }}"
                data-create-url="{{ route('nutritionPanel.testimonials.create') }}">
                <thead>
                    <tr>
                        <th class="checkbox-column">#</th>
                        <th>NAME</th>
                        <th>STORY TYPE</th>
                        <th>MEDIA</th>
                        <th>DISPLAY CHANNELS</th>
                        <th>ORDER</th>
                        <th>STATUS</th>
                        <th class="text-end">ACTION</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Bottom Flow Info Card: Member Story to Published Testimonial -->
    <div class="tst-flow-card">
        <div class="tst-flow-left">
            <div class="tst-flow-title-wrap">
                <span class="tst-section-bar"></span>
                <h3 class="tst-flow-title">From member story to published testimonial</h3>
            </div>
            <div class="tst-flow-steps">
                <div class="tst-flow-step">
                    <div class="tst-flow-step-icon step-1">
                        <i class="fa fa-file-text-o"></i>
                    </div>
                    <span class="tst-flow-step-num">1</span>
                    <span class="tst-flow-step-text">Add member details</span>
                </div>
                <i class="fa fa-arrow-right tst-flow-arrow"></i>
                <div class="tst-flow-step">
                    <div class="tst-flow-step-icon step-2">
                        <i class="fa fa-picture-o"></i>
                    </div>
                    <span class="tst-flow-step-num">2</span>
                    <span class="tst-flow-step-text">Attach video or image</span>
                </div>
                <i class="fa fa-arrow-right tst-flow-arrow"></i>
                <div class="tst-flow-step">
                    <div class="tst-flow-step-icon step-3">
                        <i class="fa fa-paper-plane-o"></i>
                    </div>
                    <span class="tst-flow-step-num">3</span>
                    <span class="tst-flow-step-text">Publish to app &amp; website</span>
                </div>
            </div>
        </div>
        <div>
            <a href="javascript:void(0);" class="tst-flow-right-link" data-toggle="modal" data-target="#displaySettingsModal">
                Manage display settings <i class="fa fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

</div>

<!-- Display Settings Modal -->
<div class="modal fade" id="displaySettingsModal" tabindex="-1" role="dialog" aria-labelledby="displaySettingsModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <div class="tst-modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title font-weight-bold text-dark" id="displaySettingsModalTitle">
                    <i class="fa fa-eye text-primary me-2"></i> Testimonial Display Settings
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="tst-modal-body">
                <div class="tst-setting-item">
                    <div>
                        <div class="tst-setting-label">Show on mobile app home</div>
                        <p class="tst-setting-desc">Feature approved success stories in the member mobile app dashboard carousel.</p>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="dispSwitch1" checked>
                        <label class="custom-control-label" for="dispSwitch1"></label>
                    </div>
                </div>

                <div class="tst-setting-item">
                    <div>
                        <div class="tst-setting-label">Display on public website</div>
                        <p class="tst-setting-desc">Show published testimonials on your club's public website & landing page.</p>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="dispSwitch2" checked>
                        <label class="custom-control-label" for="dispSwitch2"></label>
                    </div>
                </div>

                <div class="tst-setting-item">
                    <div>
                        <div class="tst-setting-label">Auto-play video previews</div>
                        <p class="tst-setting-desc">Muted video previews automatically play when scrolled into view in member apps.</p>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="dispSwitch3" checked>
                        <label class="custom-control-label" for="dispSwitch3"></label>
                    </div>
                </div>
            </div>
            <div class="tst-modal-footer d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                <button type="button" class="btn btn-primary btn-save-display-settings" style="border-radius: 8px;">Save settings</button>
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
<script src="{{ asset('admin-assets/js/testimonials/view.js') }}"></script>
@endpush
