@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Community Photos | ' . __('language.page_main_title'))

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
/* ==========================================================================
   Community Photos - Modern Redesign Styles
   ========================================================================== */
.cp-page-wrapper {
    padding: 16px 0 48px;
    color: #1e293b;
    font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Header & Breadcrumbs */
.cp-top-header {
    margin-bottom: 22px;
}
.cp-breadcrumb {
    font-size: 13px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-weight: 500;
}
.cp-breadcrumb .cp-crumb-parent {
    color: #64748b;
}
.cp-breadcrumb .cp-crumb-sep {
    color: #cbd5e1;
}
.cp-breadcrumb .cp-crumb-current {
    color: #0f172a;
    font-weight: 600;
}
.cp-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 16px;
}
.cp-header-title {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.025em;
    line-height: 1.2;
    margin-bottom: 4px;
}
.cp-header-subtitle {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 0;
}
.cp-header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

/* Live Sync Pill */
.cp-badge-live-sync {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
    font-size: 12.5px;
    font-weight: 600;
    border-radius: 9999px;
    padding: 7px 14px;
}
.cp-pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10b981;
    display: inline-block;
    box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
    animation: cpPulse 2s infinite;
}
@keyframes cpPulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

/* Header Buttons */
.cp-btn-header {
    background: #ffffff;
    border: 1.5px solid #2563eb;
    color: #2563eb;
    font-size: 13.5px;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none !important;
}
.cp-btn-header:hover {
    background: #eff6ff;
    color: #1d4ed8;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
}

/* Metrics Row */
.cp-metrics-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
.cp-metrics-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}
.cp-metric-item {
    display: flex;
    align-items: center;
    gap: 14px;
    flex: 1;
    min-width: 140px;
}
.cp-metric-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.cp-icon-blue { background: #eff6ff; border: 1px solid #dbeafe; color: #2563eb; }
.cp-icon-purple { background: #f5f3ff; border: 1px solid #ede9fe; color: #7c3aed; }
.cp-icon-green { background: #ecfdf5; border: 1px solid #d1fae5; color: #10b981; }
.cp-icon-orange { background: #fffbeb; border: 1px solid #fef3c7; color: #f59e0b; }

.cp-metric-val {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
    margin-bottom: 2px;
}
.cp-metric-label {
    font-size: 12.5px;
    color: #64748b;
    font-weight: 500;
}
.cp-metric-divider {
    width: 1px;
    height: 42px;
    background: #f1f5f9;
}
@media (max-width: 991px) {
    .cp-metric-divider { display: none; }
}

.cp-metric-info-box {
    display: flex;
    align-items: center;
    gap: 12px;
    max-width: 260px;
    color: #64748b;
    font-size: 12px;
    line-height: 1.35;
    padding-left: 10px;
}
.cp-cloud-icon {
    color: #2563eb;
    font-size: 26px;
    flex-shrink: 0;
}

/* Two Column Layout */
.cp-main-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
}
@media (max-width: 1024px) {
    .cp-main-grid {
        grid-template-columns: 1fr;
    }
}

/* Section Titles */
.cp-section-title-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}
.cp-section-title-left {
    display: flex;
    align-items: center;
    gap: 8px;
}
.cp-section-indicator {
    width: 4px;
    height: 20px;
    background: #2563eb;
    border-radius: 2px;
    display: inline-block;
}
.cp-section-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a !important;
    margin-bottom: 0;
}
.cp-section-sub {
    font-size: 13.5px;
    color: #64748b;
    margin: 2px 0 14px 12px;
}

/* Filters & View Switcher Bar */
.cp-filter-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 14px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
.cp-search-wrap {
    flex: 1;
    min-width: 200px;
    position: relative;
}
.cp-search-wrap svg {
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
input[type="text"].cp-search-input,
.cp-search-input {
    width: 100% !important;
    height: 42px !important;
    padding: 10px 16px 10px 44px !important;
    padding-left: 44px !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    background: #f8fafc !important;
    font-size: 13.5px !important;
    color: #0f172a !important;
    outline: none !important;
    transition: all 0.2s;
}
.cp-search-input:focus {
    background: #ffffff !important;
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
}
.cp-search-input::placeholder {
    color: #94a3b8 !important;
    font-size: 13.5px !important;
    opacity: 1 !important;
}
.cp-select-filter {
    height: 38px;
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
.cp-select-filter:focus {
    border-color: #2563eb;
    outline: none;
}

/* View Switcher */
.cp-view-switcher {
    display: inline-flex;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    background: #ffffff;
}
.cp-btn-view {
    height: 38px;
    padding: 6px 14px;
    border: none;
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
.cp-btn-view.active {
    background: #2563eb;
    color: #ffffff;
}
.cp-btn-view:not(.active):hover {
    background: #f8fafc;
    color: #0f172a;
}

/* Toolbar (Count & Per Page) */
.cp-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}
.cp-toolbar-count {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
}
.cp-select-perpage {
    height: 34px;
    padding: 4px 28px 4px 10px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background-color: #ffffff;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 12px;
    appearance: none;
    -webkit-appearance: none;
    font-size: 12.5px;
    color: #334155;
    cursor: pointer;
}

/* Gallery & Table Container Card */
.cp-content-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    min-height: 420px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
}

/* Gallery Grid */
.cp-gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 16px;
    padding: 20px;
}
.cp-gallery-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.cp-gallery-card:hover {
    border-color: #93c5fd;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.08);
}
.cp-gallery-card.active {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
}
.cp-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
}
.cp-card-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.cp-card-avatar-fallback {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #eff6ff;
    color: #2563eb;
    font-weight: 700;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.cp-card-user-info {
    overflow: hidden;
}
.cp-card-name {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.cp-card-time {
    font-size: 11px;
    color: #94a3b8;
}
.cp-card-image-wrap {
    position: relative;
    width: 100%;
    height: 140px;
    border-radius: 10px;
    overflow: hidden;
    background: #f1f5f9;
}
.cp-card-thumb {
    width: 100%;
    height: 140px;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.cp-gallery-card:hover .cp-card-thumb {
    transform: scale(1.04);
}
.cp-card-photo-count {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: rgba(15, 23, 42, 0.75);
    color: #ffffff;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 6px;
    backdrop-filter: blur(4px);
}
.cp-card-message {
    font-size: 12.5px;
    color: #475569;
    line-height: 1.4;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.cp-card-message.empty-msg {
    color: #94a3b8;
    font-style: italic;
}

/* Empty State */
.cp-empty-illustration-wrap {
    padding: 60px 24px;
    text-align: center;
    margin: auto;
}
.cp-empty-icon-circle {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: #eff6ff;
    border: 2px solid #dbeafe;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: relative;
    margin-bottom: 18px;
}
.cp-phone-sync-icon {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cp-phone-badge-sync {
    position: absolute;
    bottom: -4px;
    right: -8px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #2563eb;
    color: #ffffff;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #ffffff;
}
.cp-sparkle-dot {
    position: absolute;
    color: #3b82f6;
    font-size: 12px;
    line-height: 1;
}
.cp-sparkle-dot.s1 { top: -4px; left: 0px; }
.cp-sparkle-dot.s2 { top: 4px; right: -6px; font-size: 14px; }
.cp-sparkle-dot.s3 { bottom: 0px; right: 0px; font-size: 11px; }

.cp-empty-heading {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
}
.cp-empty-text {
    font-size: 13.5px;
    color: #64748b;
    max-width: 440px;
    margin: 0 auto 20px;
    line-height: 1.45;
}
.cp-btn-check {
    background: #ffffff;
    border: 1.5px solid #2563eb;
    color: #2563eb;
    font-size: 13.5px;
    font-weight: 600;
    padding: 8px 20px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s;
}
.cp-btn-check:hover {
    background: #eff6ff;
}

/* List Table Styles */
.cp-table {
    margin-bottom: 0 !important;
    width: 100% !important;
    border-collapse: collapse !important;
}
.cp-table thead th {
    background: #f8fafc !important;
    color: #64748b !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    letter-spacing: 0.05em !important;
    text-transform: uppercase !important;
    padding: 14px 16px !important;
    border-top: none !important;
    border-bottom: 1px solid #e2e8f0 !important;
}
.cp-table tbody td {
    padding: 14px 16px !important;
    font-size: 13.5px !important;
    color: #334155 !important;
    vertical-align: middle !important;
    border-bottom: 1px solid #f1f5f9 !important;
}
.cp-table tbody tr:hover td {
    background-color: #f8fafc !important;
    cursor: pointer;
}
.cp-table tbody tr.selected td {
    background-color: #eff6ff !important;
}
.cp-table-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: cover;
}
.cp-table-avatar-fallback {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #eff6ff;
    color: #2563eb;
    font-size: 11px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Footer / Pagination */
.cp-table-footer {
    padding: 14px 20px;
    border-top: 1px solid #f1f5f9;
    background: #ffffff;
}
.cp-table-footer .dataTables_info {
    font-size: 13px !important;
    color: #64748b !important;
}
.cp-table-footer .dataTables_paginate .paginate_button {
    border-radius: 8px !important;
    border: 1px solid #e2e8f0 !important;
    background: #ffffff !important;
    color: #334155 !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    padding: 5px 12px !important;
    margin: 0 3px !important;
}
.cp-table-footer .dataTables_paginate .paginate_button.current {
    background: #2563eb !important;
    border-color: #2563eb !important;
    color: #ffffff !important;
}

/* Right Column: Photo Preview Card */
.cp-preview-nav-btns {
    display: flex;
    gap: 6px;
}
.cp-btn-nav-arrow {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.15s ease;
}
.cp-btn-nav-arrow:hover {
    border-color: #2563eb;
    color: #2563eb;
    background: #eff6ff;
}

.cp-preview-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 18px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.cp-preview-box {
    width: 100%;
    min-height: 250px;
    border-radius: 12px;
    overflow: hidden;
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cp-preview-empty-state {
    text-align: center;
    padding: 30px 16px;
}
.cp-preview-img-container {
    position: relative;
    width: 100%;
    height: 250px;
}
.cp-preview-img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 12px;
}
.cp-btn-zoom {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(15, 23, 42, 0.75);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    backdrop-filter: blur(4px);
    transition: background 0.2s;
}
.cp-btn-zoom:hover {
    background: #2563eb;
}
.cp-preview-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #cbd5e1;
    display: inline-block;
    margin: 0 4px;
    cursor: pointer;
}
.cp-preview-dot.active {
    background: #2563eb;
    transform: scale(1.2);
}

/* Metadata in Preview Card */
.cp-preview-metadata {
    display: flex;
    flex-direction: column;
    gap: 10px;
    border-top: 1px solid #f1f5f9;
    padding-top: 12px;
}
.cp-meta-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    font-size: 13px;
}
.cp-meta-label {
    color: #64748b;
    font-weight: 500;
    min-width: 75px;
}
.cp-meta-val {
    color: #0f172a;
    font-weight: 600;
    text-align: right;
    word-break: break-word;
}
.cp-preview-status-bar {
    background: #f8fafc;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 12px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cp-status-dot-active {
    color: #10b981;
    font-size: 10px;
}

/* Bottom Card: How mobile uploads appear */
.cp-bottom-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
.cp-how-flow-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 14px;
}
.cp-flow-steps {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.cp-flow-step-item {
    display: flex;
    align-items: center;
    gap: 10px;
}
.cp-flow-icon {
    color: #2563eb;
    font-size: 20px;
}
.cp-flow-num {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #eff6ff;
    color: #2563eb;
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cp-flow-text {
    font-size: 13.5px;
    font-weight: 500;
    color: #334155;
}
.cp-flow-arrow {
    color: #94a3b8;
    font-size: 16px;
}
.cp-btn-open-settings {
    color: #2563eb !important;
    font-weight: 600;
    font-size: 13.5px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none !important;
    cursor: pointer;
}
.cp-btn-open-settings:hover {
    color: #1d4ed8 !important;
}

/* Mobile Simulation Modal */
.cp-phone-frame {
    width: 300px;
    background: #0f172a;
    border-radius: 36px;
    padding: 12px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    margin: 0 auto;
    border: 3px solid #334155;
}
.cp-phone-inner {
    background: #f8fafc;
    border-radius: 26px;
    overflow: hidden;
    padding: 14px;
    text-align: left;
    min-height: 480px;
}
.cp-phone-status {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 12px;
}
.cp-phone-app-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}
.cp-app-title {
    font-size: 14px;
    font-weight: 800;
    color: #2563eb;
}
.cp-app-post-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 12px;
}
.cp-post-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 10px;
}
.cp-post-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #eff6ff;
    color: #2563eb;
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cp-post-user {
    font-size: 11px;
    font-weight: 700;
    color: #0f172a;
}
.cp-post-img-box {
    width: 100%;
    height: 160px;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
}
.cp-post-caption {
    padding: 8px 10px;
    font-size: 10px;
    color: #475569;
}
.cp-post-actions {
    display: flex;
    gap: 12px;
    padding: 0 10px 8px;
    font-size: 11px;
    color: #64748b;
}
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="cp-page-wrapper">

        <!-- Top Breadcrumb & Header -->
        <div class="cp-top-header">
            <div class="cp-breadcrumb">
                <span class="cp-crumb-parent">Achievements &amp; Hub</span>
                <span class="cp-crumb-sep">/</span>
                <span class="cp-crumb-current">Community Photos</span>
            </div>
            <div class="cp-header-content">
                <div>
                    <h1 class="cp-header-title">Community photos</h1>
                    <p class="cp-header-subtitle">Browse photos and messages shared by members from the mobile app.</p>
                </div>
                <div class="cp-header-actions">
                    <span class="cp-badge-live-sync">
                        <span class="cp-pulse-dot"></span>
                        Mobile sync live
                    </span>
                    <button type="button" id="cp-btn-refresh" class="cp-btn-header" title="Refresh uploads">
                        <i class="fa fa-refresh"></i>
                        Refresh
                    </button>
                    <button type="button" class="cp-btn-header" data-bs-toggle="modal" data-bs-target="#previewCommunityModal" title="View app experience">
                        <i class="fa fa-eye"></i>
                        View in app
                    </button>
                </div>
            </div>
        </div>

        <!-- 5-Metrics Stats Bar -->
        <div class="cp-metrics-card">
            <div class="cp-metrics-row">
                <!-- Total Uploads -->
                <div class="cp-metric-item">
                    <div class="cp-metric-icon cp-icon-blue">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                            <circle cx="9" cy="9" r="2"/>
                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                        </svg>
                    </div>
                    <div>
                        <div class="cp-metric-val" id="cp-stat-total">{{ $totalUploads ?? 0 }}</div>
                        <div class="cp-metric-label">Total uploads</div>
                    </div>
                </div>

                <div class="cp-metric-divider"></div>

                <!-- Today -->
                <div class="cp-metric-item">
                    <div class="cp-metric-icon cp-icon-purple">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div>
                        <div class="cp-metric-val">{{ $todayUploads ?? 0 }}</div>
                        <div class="cp-metric-label">Today</div>
                    </div>
                </div>

                <div class="cp-metric-divider"></div>

                <!-- This Week -->
                <div class="cp-metric-item">
                    <div class="cp-metric-icon cp-icon-green">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="20" x2="12" y2="10"/>
                            <line x1="18" y1="20" x2="18" y2="4"/>
                            <line x1="6" y1="20" x2="6" y2="16"/>
                        </svg>
                    </div>
                    <div>
                        <div class="cp-metric-val">{{ $weekUploads ?? 0 }}</div>
                        <div class="cp-metric-label">This week</div>
                    </div>
                </div>

                <div class="cp-metric-divider"></div>

                <!-- With Messages -->
                <div class="cp-metric-item">
                    <div class="cp-metric-icon cp-icon-orange">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="cp-metric-val">{{ $withMessages ?? 0 }}</div>
                        <div class="cp-metric-label">With messages</div>
                    </div>
                </div>

                <div class="cp-metric-divider"></div>

                <!-- Cloud Info -->
                <div class="cp-metric-info-box">
                    <i class="fa fa-cloud cp-cloud-icon"></i>
                    <span>Photos appear here automatically after members upload from the app.</span>
                </div>
            </div>
        </div>

        <!-- Main Two-Column Section -->
        <div class="cp-main-grid">

            <!-- Left Column: Community Gallery -->
            <div>
                <!-- Section Header -->
                <div class="cp-section-title-wrap mb-1">
                    <div class="cp-section-title-left">
                        <span class="cp-section-indicator"></span>
                        <h3 class="cp-section-title">Community gallery</h3>
                    </div>
                </div>
                <p class="cp-section-sub">All member uploads in one place.</p>

                <!-- Filter Bar & View Switcher -->
                <div class="cp-filter-bar">
                    <div class="cp-search-wrap">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="cp-search-input" class="cp-search-input" placeholder="Search member or message..." autocomplete="off" style="padding-left: 44px !important;">
                    </div>
                    <div>
                        <select id="cp-filter-date" class="cp-select-filter">
                            <option value="all">All dates</option>
                            <option value="today">Today</option>
                            <option value="this_week">This week</option>
                            <option value="this_month">This month</option>
                        </select>
                    </div>
                    <div>
                        <select id="cp-filter-sort" class="cp-select-filter">
                            <option value="newest" selected>Newest first</option>
                            <option value="oldest">Oldest first</option>
                        </select>
                    </div>
                    <div class="cp-view-switcher">
                        <button type="button" id="cp-view-gallery" class="cp-btn-view active" title="Gallery View">
                            <i class="fa fa-th-large"></i> Gallery
                        </button>
                        <button type="button" id="cp-view-list" class="cp-btn-view" title="List View">
                            <i class="fa fa-list"></i> List
                        </button>
                    </div>
                </div>

                <!-- Toolbar (Photos Count & Per Page) -->
                <div class="cp-toolbar">
                    <span class="cp-toolbar-count" id="cp-records-count">{{ $totalUploads ?? 0 }} photos</span>
                    <div>
                        <select id="cp-per-page" class="cp-select-perpage">
                            <option value="20" selected>20 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                </div>

                <!-- Gallery / List Container Card -->
                <div class="cp-content-card">
                    <!-- Gallery View Section -->
                    <div id="cp-gallery-section">
                        <!-- Empty State in Gallery -->
                        <div id="cp-gallery-empty" class="cp-empty-illustration-wrap" style="{{ ($totalUploads ?? 0) > 0 ? 'display: none;' : '' }}">
                            <div class="cp-empty-icon-circle">
                                <div class="cp-sparkle-dot s1">✦</div>
                                <div class="cp-sparkle-dot s2">✦</div>
                                <div class="cp-sparkle-dot s3">✦</div>
                                <div class="cp-phone-sync-icon">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="14" height="20" x="5" y="2" rx="2" ry="2"></rect>
                                        <path d="M12 18h.01"></path>
                                    </svg>
                                    <span class="cp-phone-badge-sync">
                                        <i class="fa fa-refresh"></i>
                                    </span>
                                </div>
                            </div>
                            <h4 class="cp-empty-heading">No community photos yet</h4>
                            <p class="cp-empty-text">Photos shared through the Fit Coach Club mobile app will appear here automatically. No admin upload is required.</p>
                            <button type="button" class="cp-btn-check cp-check-photos-btn">
                                <i class="fa fa-refresh"></i> Check for new photos
                            </button>
                        </div>

                        <!-- Gallery Cards Grid -->
                        <div id="cp-gallery-grid" class="cp-gallery-grid" style="{{ ($totalUploads ?? 0) == 0 ? 'display: none;' : '' }}">
                        </div>
                    </div>

                    <!-- List View Section (DataTables) -->
                    <div id="cp-list-section" style="display: none;">
                        <div class="table-responsive">
                            <table id="dataTable" class="table cp-table" data-url="{{ route('nutritionPanel.community-photos.getCommunityPhotos') }}">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>NAME</th>
                                        <th>MESSAGE</th>
                                        <th>VIEW PHOTOS</th>
                                        <th>DATE &amp; TIME</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Photo Preview -->
            <div>
                <!-- Section Header with Arrows -->
                <div class="cp-section-title-wrap mb-1">
                    <div class="cp-section-title-left">
                        <span class="cp-section-indicator"></span>
                        <h3 class="cp-section-title">Photo preview</h3>
                    </div>
                    <div class="cp-preview-nav-btns">
                        <button type="button" id="cp-preview-prev" class="cp-btn-nav-arrow" title="Previous photo">
                            <i class="fa fa-angle-left"></i>
                        </button>
                        <button type="button" id="cp-preview-next" class="cp-btn-nav-arrow" title="Next photo">
                            <i class="fa fa-angle-right"></i>
                        </button>
                    </div>
                </div>
                <div style="height: 20px;"></div>

                <!-- Preview Card -->
                <div class="cp-preview-card">
                    <div id="cp-preview-visual" class="cp-preview-box">
                        <div class="cp-preview-empty-state">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                <circle cx="9" cy="9" r="2"/>
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                            </svg>
                            <p class="mb-0 mt-2 text-muted fw-semibold">Select a photo to preview</p>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="cp-preview-metadata">
                        <div class="cp-meta-row">
                            <span class="cp-meta-label">Member</span>
                            <span class="cp-meta-val" id="cp-meta-member">—</span>
                        </div>
                        <div class="cp-meta-row">
                            <span class="cp-meta-label">Uploaded</span>
                            <span class="cp-meta-val" id="cp-meta-uploaded">—</span>
                        </div>
                        <div class="cp-meta-row">
                            <span class="cp-meta-label">Message</span>
                            <span class="cp-meta-val" id="cp-meta-message">—</span>
                        </div>
                    </div>

                    <!-- Status Bar Footer -->
                    <div class="cp-preview-status-bar" id="cp-meta-status">
                        <i class="fa fa-clock-o text-muted"></i>
                        Waiting for the first mobile upload
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Card: How mobile uploads appear -->
        <div class="cp-bottom-card">
            <div class="cp-section-title-left">
                <span class="cp-section-indicator"></span>
                <h3 class="cp-section-title">How mobile uploads appear</h3>
            </div>
            <div class="cp-how-flow-row">
                <div class="cp-flow-steps">
                    <!-- Step 1 -->
                    <div class="cp-flow-step-item">
                        <i class="fa fa-mobile cp-flow-icon"></i>
                        <span class="cp-flow-num">1</span>
                        <span class="cp-flow-text">Member uploads from app</span>
                    </div>

                    <span class="cp-flow-arrow">&rarr;</span>

                    <!-- Step 2 -->
                    <div class="cp-flow-step-item">
                        <i class="fa fa-cloud cp-flow-icon"></i>
                        <span class="cp-flow-num">2</span>
                        <span class="cp-flow-text">Photo syncs automatically</span>
                    </div>

                    <span class="cp-flow-arrow">&rarr;</span>

                    <!-- Step 3 -->
                    <div class="cp-flow-step-item">
                        <i class="fa fa-picture-o cp-flow-icon"></i>
                        <span class="cp-flow-num">3</span>
                        <span class="cp-flow-text">Appears in community gallery</span>
                    </div>
                </div>

                <div>
                    <a href="javascript:void(0);" class="cp-btn-open-settings" data-bs-toggle="modal" data-bs-target="#previewCommunityModal">
                        Open app settings &rarr;
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal: Member App Community Feed Preview -->
<div class="modal fade" id="previewCommunityModal" tabindex="-1" aria-labelledby="previewCommunityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="previewCommunityModalLabel">Mobile App Community Feed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="cp-phone-frame">
                    <div class="cp-phone-inner">
                        <div class="cp-phone-status">
                            <span>9:41</span>
                            <div>
                                <i class="fa fa-signal me-1"></i>
                                <i class="fa fa-wifi me-1"></i>
                                <i class="fa fa-battery-full"></i>
                            </div>
                        </div>
                        <div class="cp-phone-app-head">
                            <span class="cp-app-title">Fit Coach Club</span>
                            <span class="badge bg-primary rounded-pill">+ Share Photo</span>
                        </div>
                        <!-- Feed Post Simulation -->
                        <div class="cp-app-post-card">
                            <div class="cp-post-header">
                                <div class="cp-post-avatar">A</div>
                                <div>
                                    <div class="cp-post-user">Ananya Sharma</div>
                                    <div style="font-size: 9px; color: #94a3b8;">15 minutes ago</div>
                                </div>
                            </div>
                            <div class="cp-post-img-box">
                                <i class="fa fa-picture-o fa-2x"></i>
                            </div>
                            <div class="cp-post-caption">
                                Celebrating a 5kg weight loss milestone with today's nutrition shake! 🎉💪
                            </div>
                            <div class="cp-post-actions">
                                <span><i class="fa fa-heart-o text-danger"></i> 18 likes</span>
                                <span><i class="fa fa-comment-o"></i> 4 comments</span>
                            </div>
                        </div>
                        <div class="text-center text-muted small mt-2">
                            <i class="fa fa-check-circle text-success"></i> Auto-sync enabled for mobile uploads
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 small">Members share photos directly from their Fit Coach Club app feed.</p>
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
<script src="{{ asset('admin-assets/js/community-photos/view.js') }}"></script>
@endpush
