@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Achievements | ' . __('language.page_main_title'))

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
/* ==========================================================================
   Achievements Index - Modern Redesign Styles
   ========================================================================== */
.ach-page-wrapper {
    padding: 16px 0 48px;
    color: #1e293b;
    font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Header & Breadcrumbs */
.ach-top-header {
    margin-bottom: 24px;
}
.ach-breadcrumb {
    font-size: 13px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-weight: 500;
}
.ach-breadcrumb .ach-crumb-parent {
    color: #64748b;
}
.ach-breadcrumb .ach-crumb-sep {
    color: #cbd5e1;
}
.ach-breadcrumb .ach-crumb-current {
    color: #0f172a;
    font-weight: 600;
}
.ach-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 16px;
}
.ach-header-title {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.025em;
    line-height: 1.2;
    margin-bottom: 4px;
}
.ach-header-subtitle {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 0;
}
.ach-header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}
.ach-btn-preview {
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    color: #1e293b;
    font-size: 13.5px;
    font-weight: 600;
    padding: 9px 18px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}
.ach-btn-preview svg {
    color: #2563eb;
}
.ach-btn-preview:hover {
    border-color: #93c5fd;
    background: #f8fafc;
    color: #2563eb;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);
}
.ach-btn-create {
    background: #2563eb;
    border: 1.5px solid #2563eb;
    color: #ffffff !important;
    font-size: 13.5px;
    font-weight: 600;
    padding: 9px 20px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none !important;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
}
.ach-btn-create:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
}

/* Hero Section */
.ach-hero-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-bottom: 28px;
}
@media (max-width: 991px) {
    .ach-hero-grid {
        grid-template-columns: 1fr;
    }
}

/* Hero Banner Card */
.ach-hero-card {
    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 28%, #4f46e5 68%, #7c3aed 100%);
    border-radius: 18px;
    padding: 28px 32px;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px -5px rgba(37, 99, 235, 0.28);
}
.ach-hero-left {
    display: flex;
    align-items: center;
    gap: 24px;
    max-width: 68%;
    position: relative;
    z-index: 2;
}
.ach-hero-badge-wrap {
    position: relative;
    flex-shrink: 0;
}
.ach-hero-badge-circle {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.18);
    border: 2px solid rgba(255, 255, 255, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(8px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12), inset 0 0 12px rgba(255, 255, 255, 0.15);
}
.ach-sparkle-star {
    position: absolute;
    color: rgba(255, 255, 255, 0.95);
    font-size: 13px;
    line-height: 1;
    pointer-events: none;
    animation: achSparklePulse 3s infinite ease-in-out;
}
.ach-sparkle-star.sp-1 { top: -6px; left: -4px; animation-delay: 0s; }
.ach-sparkle-star.sp-2 { top: -4px; right: -8px; font-size: 11px; animation-delay: 1s; }
.ach-sparkle-star.sp-3 { bottom: 2px; right: -6px; font-size: 12px; animation-delay: 2s; }
@keyframes achSparklePulse {
    0%, 100% { opacity: 0.6; transform: scale(0.9); }
    50% { opacity: 1; transform: scale(1.15); }
}

.ach-hero-text h3 {
    font-size: 21px;
    font-weight: 700;
    color: #ffffff !important;
    margin-bottom: 6px;
    letter-spacing: -0.01em;
}
.ach-hero-text p {
    font-size: 13.5px;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 14px;
    line-height: 1.45;
}
.ach-hero-tags {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}
.ach-tag-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.16);
    border: 1px solid rgba(255, 255, 255, 0.32);
    border-radius: 9999px;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 500;
    color: #ffffff;
    backdrop-filter: blur(6px);
}

.ach-hero-right {
    text-align: center;
    position: relative;
    z-index: 2;
    padding-left: 20px;
    border-left: 1px solid rgba(255, 255, 255, 0.15);
}
.ach-hero-stat-number {
    font-size: 42px;
    font-weight: 800;
    line-height: 1;
    color: #ffffff;
    margin-bottom: 4px;
}
.ach-hero-stat-label {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.85);
    font-weight: 500;
    margin-bottom: 14px;
}
.ach-hero-btn {
    background: #ffffff;
    color: #2563eb !important;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 18px;
    border-radius: 9px;
    display: inline-block;
    text-decoration: none !important;
    white-space: nowrap;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}
.ach-hero-btn:hover {
    background: #f8fafc;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.16);
}

/* Checklist Card */
.ach-checklist-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.ach-checklist-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
}
.ach-checklist-header svg {
    color: #2563eb;
}
.ach-checklist-header h4 {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a !important;
    margin-bottom: 0;
}
.ach-checklist-list {
    list-style: none;
    padding: 0;
    margin: 0 0 20px 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.ach-checklist-item {
    display: flex;
    align-items: center;
    gap: 12px;
}
.ach-step-num {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #334155;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.2s ease;
}
.ach-step-num.step-done {
    background: #2563eb;
    color: #ffffff;
}
.ach-step-text {
    font-size: 13.5px;
    font-weight: 500;
    color: #334155;
}
.ach-checklist-progress {
    margin-top: auto;
}
.ach-checklist-status {
    font-size: 12px;
    font-weight: 500;
    color: #64748b;
    margin-bottom: 6px;
}
.ach-progress-bar-wrap {
    width: 100%;
    height: 6px;
    background: #f1f5f9;
    border-radius: 9999px;
    overflow: hidden;
}
.ach-progress-fill {
    height: 100%;
    background: #2563eb;
    border-radius: 9999px;
    transition: width 0.4s ease;
}

/* Section Title */
.ach-section-header {
    margin-bottom: 16px;
}
.ach-section-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 2px;
}
.ach-section-indicator {
    width: 4px;
    height: 20px;
    background: #2563eb;
    border-radius: 3px;
    display: inline-block;
}
.ach-section-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a !important;
    margin-bottom: 0;
}
.ach-section-desc {
    font-size: 13.5px;
    color: #64748b;
    margin-bottom: 0;
    padding-left: 14px;
}

/* Filters Card */
.ach-filters-container {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px 16px;
    margin-bottom: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
.ach-filters-row {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.ach-search-box {
    flex: 1 1 240px;
    min-width: 200px;
    position: relative;
}
.ach-search-box svg {
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
input[type="text"].ach-search-input,
.ach-search-input {
    width: 100% !important;
    height: 42px !important;
    padding: 10px 16px 10px 44px !important;
    padding-left: 44px !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 10px !important;
    background: #f8fafc !important;
    color: #0f172a !important;
    font-size: 13.5px !important;
    outline: none !important;
    transition: all 0.2s ease;
}
.ach-search-input:focus {
    background: #ffffff !important;
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
}
.ach-search-input::placeholder {
    color: #94a3b8 !important;
    font-size: 13.5px !important;
    opacity: 1 !important;
}
.ach-select-filter {
    height: 40px;
    padding: 8px 34px 8px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background-color: #ffffff;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 14px;
    appearance: none;
    -webkit-appearance: none;
    color: #334155;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    min-width: 130px;
    transition: all 0.2s ease;
}
.ach-select-filter:focus {
    border-color: #2563eb;
    outline: none;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}
.ach-btn-more-filters {
    height: 40px;
    padding: 8px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #ffffff;
    color: #334155;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.ach-btn-more-filters:hover, .ach-btn-more-filters.open {
    border-color: #2563eb;
    color: #2563eb;
    background: #eff6ff;
}
.ach-more-filters-collapse {
    border-top: 1px solid #f1f5f9;
    margin-top: 12px;
    padding-top: 14px;
}
.ach-btn-clear-filters {
    height: 38px;
    padding: 6px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    background: #ffffff;
    color: #64748b;
    font-size: 12.5px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.15s ease;
}
.ach-btn-clear-filters:hover {
    border-color: #ef4444;
    color: #ef4444;
    background: #fef2f2;
}

/* Toolbar & Batch Actions */
.ach-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 14px;
}
.ach-toolbar-left {
    display: flex;
    align-items: center;
    gap: 14px;
}
.ach-toolbar-count {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
}
.ach-select-perpage {
    height: 36px;
    padding: 6px 30px 6px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background-color: #ffffff;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 12px;
    appearance: none;
    -webkit-appearance: none;
    font-size: 13px;
    font-weight: 500;
    color: #334155;
    cursor: pointer;
}
.ach-toolbar-right {
    display: flex;
    align-items: center;
    gap: 10px;
}
.ach-toolbar-btn {
    height: 36px;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #334155;
}
.ach-btn-order-update {
    border: 1.5px solid #2563eb;
    color: #2563eb;
    background: #ffffff;
}
.ach-btn-order-update:hover {
    background: #eff6ff;
}
.ach-toolbar-btn:disabled {
    border-color: #e2e8f0 !important;
    background: #f8fafc !important;
    color: #94a3b8 !important;
    cursor: not-allowed !important;
    opacity: 0.85;
}
.ach-toolbar-btn.active-btn {
    opacity: 1;
    cursor: pointer;
}
.ach-toolbar-btn.change-status.active-btn {
    border-color: #2563eb;
    color: #2563eb;
    background: #eff6ff;
}
.ach-toolbar-btn.dt-delete.active-btn {
    border-color: #ef4444;
    color: #ef4444;
    background: #fef2f2;
}

/* Table Card Container */
.ach-table-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    overflow: hidden;
}
.ach-table {
    margin-bottom: 0 !important;
    width: 100% !important;
    border-collapse: collapse !important;
}
.ach-table thead th {
    background: #f8fafc !important;
    color: #64748b !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    letter-spacing: 0.05em !important;
    text-transform: uppercase !important;
    padding: 14px 16px !important;
    border-top: none !important;
    border-bottom: 1px solid #e2e8f0 !important;
    white-space: nowrap;
}
.ach-table tbody td {
    padding: 14px 16px !important;
    font-size: 13.5px !important;
    color: #334155 !important;
    vertical-align: middle !important;
    border-bottom: 1px solid #f1f5f9 !important;
}
.ach-table tbody tr:hover td {
    background-color: #f8fafc !important;
}
.ach-table tbody tr.selected td {
    background-color: #eff6ff !important;
}

/* Badges inside table */
.ach-table-status {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 600;
}
.ach-status-active {
    background-color: #dcfce7 !important;
    color: #15803d !important;
    border: 1px solid #bbf7d0;
}
.ach-status-inactive {
    background-color: #fef3c7 !important;
    color: #b45309 !important;
    border: 1px solid #fde68a;
}
.ach-table-inapp {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}
.ach-table-inapp.inapp-yes {
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #dbeafe;
}
.ach-table-inapp.inapp-no {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
}
.ach-table-user-badge {
    display: inline-block;
    font-size: 12.5px;
    color: #475569;
    font-weight: 500;
}
.ach-order-input {
    width: 60px !important;
    height: 32px !important;
    padding: 2px 6px !important;
    border-radius: 8px !important;
    border: 1px solid #cbd5e1 !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #0f172a !important;
}
.ach-order-input:focus {
    border-color: #2563eb !important;
    outline: none !important;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15) !important;
}
.ach-table-action-edit {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 12px;
    border-radius: 8px;
    background: #eff6ff;
    color: #2563eb !important;
    border: 1px solid #bfdbfe;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none !important;
    transition: all 0.15s ease;
}
.ach-table-action-edit:hover {
    background: #2563eb;
    color: #ffffff !important;
    border-color: #2563eb;
}
.ach-table-action-edit .badge {
    background: transparent !important;
    color: inherit !important;
    padding: 0 !important;
}

/* Empty State Table View */
.ach-empty-illustration-wrap {
    padding: 60px 20px;
    text-align: center;
}
.ach-empty-icon-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #eff6ff;
    border: 2px solid #dbeafe;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: relative;
    margin-bottom: 18px;
}
.ach-sparkle-dot {
    position: absolute;
    color: #3b82f6;
    font-size: 12px;
    line-height: 1;
}
.ach-sparkle-dot.s1 { top: -4px; left: -4px; }
.ach-sparkle-dot.s2 { top: 0px; right: -6px; font-size: 14px; }
.ach-sparkle-dot.s3 { bottom: 0px; right: -2px; font-size: 11px; }

.ach-empty-heading {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
}
.ach-empty-text {
    font-size: 13.5px;
    color: #64748b;
    max-width: 440px;
    margin: 0 auto 20px;
    line-height: 1.45;
}
.ach-empty-create-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

/* Footer / Pagination */
.ach-table-footer {
    padding: 16px 20px;
    border-top: 1px solid #f1f5f9;
    background: #ffffff;
}
.dataTables_info {
    font-size: 13px !important;
    color: #64748b !important;
    padding-top: 0 !important;
}
.dataTables_paginate {
    padding-top: 0 !important;
}
.dataTables_paginate .paginate_button {
    border-radius: 8px !important;
    border: 1px solid #e2e8f0 !important;
    background: #ffffff !important;
    color: #334155 !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    padding: 5px 12px !important;
    margin: 0 3px !important;
    transition: all 0.15s ease !important;
}
.dataTables_paginate .paginate_button:hover {
    border-color: #cbd5e1 !important;
    background: #f8fafc !important;
    color: #0f172a !important;
}
.dataTables_paginate .paginate_button.current,
.dataTables_paginate .paginate_button.current:hover {
    background: #2563eb !important;
    border-color: #2563eb !important;
    color: #ffffff !important;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25) !important;
}
.dataTables_paginate .paginate_button.disabled,
.dataTables_paginate .paginate_button.disabled:hover {
    background: #f8fafc !important;
    border-color: #f1f5f9 !important;
    color: #cbd5e1 !important;
    cursor: not-allowed !important;
}

/* Phone Mockup Modal */
.ach-phone-mockup {
    width: 300px;
    background: #0f172a;
    border-radius: 36px;
    padding: 12px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    margin: 0 auto;
    border: 3px solid #334155;
}
.ach-phone-screen {
    background: #f8fafc;
    border-radius: 26px;
    overflow: hidden;
    padding: 14px;
    text-align: left;
    min-height: 480px;
}
.ach-phone-topbar {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 14px;
}
.ach-phone-icons {
    display: flex;
    gap: 6px;
    font-size: 10px;
}
.ach-app-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}
.ach-app-brand {
    font-size: 15px;
    font-weight: 800;
    color: #2563eb;
}
.ach-app-badge-counter {
    font-size: 11px;
    background: #dbeafe;
    color: #1e40af;
    padding: 3px 8px;
    border-radius: 20px;
    font-weight: 700;
}
.ach-app-banner {
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    border-radius: 14px;
    padding: 12px;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
}
.ach-app-banner-icon {
    font-size: 24px;
}
.ach-app-banner-title {
    font-size: 12px;
    font-weight: 700;
}
.ach-app-banner-sub {
    font-size: 10px;
    opacity: 0.9;
}
.ach-app-section-title {
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
}
.ach-app-badge-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 8px;
    margin-bottom: 14px;
}
.ach-app-badge-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 8px 4px;
    text-align: center;
}
.ach-app-badge-item.unlocked .badge-icon-circ {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
}
.ach-app-badge-item.locked {
    opacity: 0.65;
}
.badge-icon-circ {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin: 0 auto 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    background: #f1f5f9;
}
.badge-name {
    font-size: 10px;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.badge-status {
    font-size: 8px;
    color: #10b981;
    font-weight: 600;
}
.ach-app-badge-item.locked .badge-status {
    color: #64748b;
}
.ach-app-announcement {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px;
}
.announcement-tag {
    font-size: 9px;
    font-weight: 700;
    color: #2563eb;
    text-transform: uppercase;
    margin-bottom: 2px;
}
.announcement-title {
    font-size: 11px;
    font-weight: 600;
    color: #1e293b;
}
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="ach-page-wrapper">

        <!-- Top Breadcrumbs & Header -->
        <div class="ach-top-header">
            <div class="ach-breadcrumb">
                <span class="ach-crumb-parent">Achievements &amp; Hub</span>
                <span class="ach-crumb-sep">/</span>
                <span class="ach-crumb-current">Achievements</span>
            </div>
            <div class="ach-header-content">
                <div>
                    <h1 class="ach-header-title">Achievements</h1>
                    <p class="ach-header-subtitle">Create milestones that motivate members and celebrate progress.</p>
                </div>
                <div class="ach-header-actions">
                    <button type="button" class="ach-btn-preview" data-bs-toggle="modal" data-bs-target="#previewAppModal">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        Preview in app
                    </button>
                    <a href="{{ route('nutritionPanel.achievements.create') }}" class="ach-btn-create">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Create achievement
                    </a>
                </div>
            </div>
        </div>

        <!-- Hero Section: Banner + Checklist -->
        <div class="ach-hero-grid">
            <!-- Left Hero Gradient Card -->
            <div class="ach-hero-card">
                <div class="ach-hero-left">
                    <div class="ach-hero-badge-wrap">
                        <div class="ach-sparkle-star sp-1">✦</div>
                        <div class="ach-sparkle-star sp-2">✦</div>
                        <div class="ach-sparkle-star sp-3">✦</div>
                        <div class="ach-hero-badge-circle">
                            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="6"></circle>
                                <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ach-hero-text">
                        <h3>Make progress worth celebrating.</h3>
                        <p>Build an achievement library that recognizes consistency, transformation and community wins.</p>
                        <div class="ach-hero-tags">
                            <span class="ach-tag-pill">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path></svg>
                                Milestones
                            </span>
                            <span class="ach-tag-pill">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                Badges
                            </span>
                            <span class="ach-tag-pill">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                In-app visibility
                            </span>
                        </div>
                    </div>
                </div>
                <div class="ach-hero-right">
                    <div class="ach-hero-stat-number ach-stat-count">{{ $totalAchievements ?? 0 }}</div>
                    <div class="ach-hero-stat-label">achievements</div>
                    <a href="{{ route('nutritionPanel.achievements.create') }}" class="ach-hero-btn">
                        Create your first achievement
                    </a>
                </div>
            </div>

            <!-- Right Publishing Checklist Card -->
            <div class="ach-checklist-card">
                <div class="ach-checklist-header">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        <path d="m9 14 2 2 4-4"></path>
                    </svg>
                    <h4>Publishing checklist</h4>
                </div>
                <ul class="ach-checklist-list">
                    <li class="ach-checklist-item">
                        <span class="ach-step-num {{ ($totalAchievements ?? 0) > 0 ? 'step-done' : '' }}">1</span>
                        <span class="ach-step-text">Create achievement</span>
                    </li>
                    <li class="ach-checklist-item">
                        <span class="ach-step-num {{ ($totalAchievements ?? 0) > 0 ? 'step-done' : '' }}">2</span>
                        <span class="ach-step-text">Choose app visibility</span>
                    </li>
                    <li class="ach-checklist-item">
                        <span class="ach-step-num {{ ($totalAchievements ?? 0) > 0 ? 'step-done' : '' }}">3</span>
                        <span class="ach-step-text">Set order and publish</span>
                    </li>
                </ul>
                <div class="ach-checklist-progress">
                    <div class="ach-checklist-status">
                        {{ ($totalAchievements ?? 0) > 0 ? '3 of 3 complete' : '0 of 3 complete' }}
                    </div>
                    <div class="ach-progress-bar-wrap">
                        <div class="ach-progress-fill" style="width: {{ ($totalAchievements ?? 0) > 0 ? '100%' : '0%' }};"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Title -->
        <div class="ach-section-header">
            <div class="ach-section-title-wrap">
                <span class="ach-section-indicator"></span>
                <h3 class="ach-section-title">Achievement library</h3>
            </div>
            <p class="ach-section-desc">Manage achievement visibility, status and display order.</p>
        </div>

        <!-- Filter Bar -->
        <div class="ach-filters-container">
            <div class="ach-filters-row">
                <div class="ach-search-box">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="ach-search-input" class="ach-search-input" placeholder="Search achievements..." autocomplete="off" style="padding-left: 44px !important;">
                </div>
                <div>
                    <select id="ach-filter-type" class="ach-select-filter ach-filter-select">
                        <option value="all">All types</option>
                        <option value="Achievement">Achievement</option>
                        <option value="Announcement">Announcement</option>
                    </select>
                </div>
                <div>
                    <select id="ach-filter-status" class="ach-select-filter ach-filter-select">
                        <option value="all">All statuses</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div>
                    <select id="ach-filter-visibility" class="ach-select-filter ach-filter-select">
                        <option value="all">All visibility</option>
                        <option value="1">All User</option>
                        <option value="2">Only Online User</option>
                        <option value="3">Only Offline User</option>
                    </select>
                </div>
                <div>
                    <button type="button" id="ach-more-filters-btn" class="ach-btn-more-filters">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                        <span>More filters</span>
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- More Filters Accordion -->
            <div id="ach-more-filters-collapse" class="ach-more-filters-collapse" style="display: none;">
                <div class="row align-items-center">
                    <div class="col-md-4 col-sm-6 mb-2">
                        <label class="small text-muted fw-bold mb-1">In-App Show (Home Page)</label>
                        <select id="ach-filter-inapp" class="ach-select-filter ach-filter-select w-100">
                            <option value="all">All</option>
                            <option value="1">Yes (Show on App Home)</option>
                            <option value="2">No (Hidden from App Home)</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-2 pt-md-3">
                        <button type="button" id="ach-clear-filters-btn" class="ach-btn-clear-filters">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            Clear all filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Toolbar & Batch Actions Container -->
        <div class="data-table-container">
            <div class="ach-toolbar">
                <div class="ach-toolbar-left">
                    <span class="ach-toolbar-count" id="ach-records-count">{{ $totalAchievements ?? 0 }} achievements</span>
                    <div>
                        <select id="ach-per-page" class="ach-select-perpage">
                            <option value="20" selected>20 per page</option>
                            <option value="50">50 per page</option>
                            <option value="75">75 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                </div>
                <div class="ach-toolbar-right">
                    <button type="button" title="Order Update" class="ach-toolbar-btn ach-btn-order-update update-order">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="17 1 21 5 17 9"></polyline>
                            <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                            <polyline points="7 23 3 19 7 15"></polyline>
                            <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                        </svg>
                        Update order
                    </button>
                    <button type="button" title="Change Status" class="ach-toolbar-btn change-status" disabled>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"></path>
                        </svg>
                        Change status
                    </button>
                    <button type="button" title="Delete" class="ach-toolbar-btn dt-delete" disabled>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                        Delete
                    </button>
                </div>
            </div>

            <!-- Table Card -->
            <div class="ach-table-card">
                <div class="table-responsive">
                    <table id="dataTable" class="table ach-table" 
                        data-url="{{ route('nutritionPanel.achievements.getAchievements') }}"  
                        data-change-status-url="{{ route('nutritionPanel.achievements.changeStatus') }}" 
                        data-destroy-url="{{ route('nutritionPanel.achievements.destroy') }}" 
                        data-update-order-url="{{ route('nutritionPanel.achievements.updateOrder') }}"
                        data-create-url="{{ route('nutritionPanel.achievements.create') }}">
                        <thead>
                            <tr>
                                <th class="checkbox-column"> # </th>
                                <th>TITLE</th>
                                <th>TYPE</th>
                                <th>IN APP SHOW</th>
                                <th>SHOW THIS ACHIEVEMENT</th>
                                <th>ORDER</th>
                                <th>STATUS</th>
                                <th class="text-end">ACTION</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- In-App Preview Modal -->
<div class="modal fade" id="previewAppModal" tabindex="-1" aria-labelledby="previewAppModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="previewAppModalLabel">In-App Member Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <!-- Phone Frame Mockup -->
                <div class="ach-phone-mockup">
                    <div class="ach-phone-screen">
                        <div class="ach-phone-topbar">
                            <span>9:41</span>
                            <div class="ach-phone-icons">
                                <i class="fa fa-signal"></i>
                                <i class="fa fa-wifi"></i>
                                <i class="fa fa-battery-full"></i>
                            </div>
                        </div>
                        <div class="ach-app-header">
                            <span class="ach-app-brand">Fit Coach Club</span>
                            <span class="ach-app-badge-counter"><i class="fa fa-trophy"></i> Rewards</span>
                        </div>
                        <div class="ach-app-banner">
                            <div class="ach-app-banner-icon">🏆</div>
                            <div>
                                <div class="ach-app-banner-title">Member Milestones</div>
                                <div class="ach-app-banner-sub">Complete daily nutrition &amp; habits to earn badges!</div>
                            </div>
                        </div>
                        <div class="ach-app-section-title">Your Achievements</div>
                        <div class="ach-app-badge-grid">
                            <div class="ach-app-badge-item unlocked">
                                <div class="badge-icon-circ">🎖️</div>
                                <div class="badge-name">First Step</div>
                                <span class="badge-status">Unlocked</span>
                            </div>
                            <div class="ach-app-badge-item unlocked">
                                <div class="badge-icon-circ">⚡</div>
                                <div class="badge-name">7 Day Streak</div>
                                <span class="badge-status">Unlocked</span>
                            </div>
                            <div class="ach-app-badge-item locked">
                                <div class="badge-icon-circ">🥇</div>
                                <div class="badge-name">Goal Crusher</div>
                                <span class="badge-status">In Progress</span>
                            </div>
                        </div>
                        <div class="ach-app-announcement">
                            <div class="announcement-tag">Announcement</div>
                            <div class="announcement-title">New 30-Day Fitness &amp; Nutrition Challenge!</div>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 small">This live preview simulates how achievements and announcements are displayed to members on the mobile app.</p>
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
<script src="{{ asset('admin-assets/js/achievements/view.js') }}"></script>
@endpush
