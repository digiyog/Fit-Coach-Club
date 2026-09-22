@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Community Photos | ' . __('language.page_main_title'))

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
/* ==========================================================================
   Community Photos - Pixel-Perfect Redesign Styles
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
    border: 1px solid #cbd5e1;
    color: #2563eb;
    font-size: 13px;
    font-weight: 600;
    border-radius: 10px;
    padding: 8px 16px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    text-decoration: none;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    cursor: pointer;
}
.cp-btn-header:hover {
    background: #eff6ff;
    border-color: #2563eb;
    color: #1d4ed8;
}

/* Metrics Bar */
.cp-metrics-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px 22px;
    margin-bottom: 22px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
}
.cp-metrics-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px 24px;
}
.cp-metric-item {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 140px;
}
.cp-metric-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.cp-icon-blue {
    background: #eff6ff;
    color: #2563eb;
}
.cp-icon-purple {
    background: #f5f3ff;
    color: #7c3aed;
}
.cp-icon-green {
    background: #ecfdf5;
    color: #059669;
}
.cp-icon-orange {
    background: #fff7ed;
    color: #ea580c;
}
.cp-metric-val {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.15;
}
.cp-metric-label {
    font-size: 12.5px;
    font-weight: 500;
    color: #64748b;
}
.cp-metric-divider {
    width: 1px;
    height: 38px;
    background: #f1f5f9;
}
@media (max-width: 768px) {
    .cp-metric-divider {
        display: none;
    }
}
.cp-metric-info-box {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 13px;
    color: #64748b;
    max-width: 320px;
}
@media (max-width: 1200px) {
    .cp-metric-info-box {
        margin-left: 0;
        max-width: 100%;
        width: 100%;
    }
}
.cp-cloud-icon {
    color: #2563eb;
    font-size: 26px;
    flex-shrink: 0;
}

/* Two-Column Grid */
.cp-main-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 350px;
    gap: 22px;
    align-items: start;
    margin-bottom: 24px;
}
@media (max-width: 1200px) {
    .cp-main-grid {
        grid-template-columns: minmax(0, 1fr) 310px;
    }
}
@media (max-width: 992px) {
    .cp-main-grid {
        grid-template-columns: 1fr;
    }
}

/* Section Title */
.cp-section-title-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 4px;
}
.cp-section-title-left {
    display: flex;
    align-items: center;
    gap: 8px;
}
.cp-section-indicator {
    width: 4px;
    height: 18px;
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

/* Left Filter Bar */
.cp-filter-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}
.cp-search-wrap {
    flex: 1 1 200px;
    min-width: 170px;
    position: relative;
}
.cp-search-wrap svg {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    pointer-events: none;
    z-index: 3;
}
.cp-search-input {
    width: 100% !important;
    height: 38px !important;
    padding: 6px 12px 6px 44px !important;
    padding-left: 44px !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    background: #ffffff !important;
    font-size: 13px !important;
    color: #1e293b !important;
    outline: none !important;
    transition: all 0.2s ease;
}
.cp-search-input:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
}
.cp-search-input::placeholder {
    color: #94a3b8 !important;
    opacity: 1 !important;
}

.cp-select-filter {
    height: 38px;
    padding: 6px 28px 6px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background-color: #ffffff;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 12px;
    appearance: none;
    -webkit-appearance: none;
    font-size: 13px;
    color: #334155;
    font-weight: 500;
    cursor: pointer;
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
    height: 36px;
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
    background: #f1f5f9;
    color: #0f172a;
}

/* Toolbar */
.cp-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
    padding: 0 2px;
}
.cp-toolbar-count {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
}
.cp-select-perpage {
    height: 32px;
    padding: 3px 26px 3px 10px;
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

/* Main Left Card */
.cp-content-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    min-height: 480px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
    padding: 20px;
}

/* Empty State Illustration */
.cp-empty-illustration-wrap {
    padding: 40px 20px;
    text-align: center;
    margin: auto;
}
.cp-empty-icon-circle {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: #eff6ff;
    border: 2px solid #dbeafe;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: relative;
    margin-bottom: 20px;
}
.cp-phone-sync-graphic {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.cp-phone-badge-sync {
    position: absolute;
    bottom: -6px;
    right: -8px;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #2563eb;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2.5px solid #ffffff;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
}
.cp-sparkle-dot {
    position: absolute;
    color: #3b82f6;
    font-size: 13px;
    line-height: 1;
}
.cp-sparkle-dot.s1 { top: 6px; left: 4px; font-size: 12px; }
.cp-sparkle-dot.s2 { top: 4px; right: -4px; font-size: 15px; }
.cp-sparkle-dot.s3 { bottom: 12px; right: -8px; font-size: 12px; }

.cp-empty-heading {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
}
.cp-empty-text {
    font-size: 13px;
    color: #64748b;
    max-width: 420px;
    margin: 0 auto 20px;
    line-height: 1.45;
}
.cp-btn-check {
    background: #ffffff;
    border: 1.5px solid #2563eb;
    color: #2563eb;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 22px;
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

/* Gallery Cards Grid */
.cp-gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    padding: 10px 0 20px;
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
    box-shadow: 0 8px 18px rgba(37, 99, 235, 0.08);
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
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.cp-card-avatar-fallback {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #eff6ff;
    color: #2563eb;
    font-weight: 700;
    font-size: 12px;
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
    bottom: 6px;
    right: 6px;
    background: rgba(15, 23, 42, 0.75);
    color: #ffffff;
    font-size: 11px;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 6px;
    backdrop-filter: blur(4px);
}
.cp-card-message {
    font-size: 12px;
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
    padding: 12px 14px !important;
    border-top: none !important;
    border-bottom: 1px solid #e2e8f0 !important;
}
.cp-table tbody td {
    padding: 12px 14px !important;
    font-size: 13px !important;
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
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.cp-table-avatar-fallback {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #eff6ff;
    color: #2563eb;
    font-size: 12px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Left Card Footer Pagination */
.cp-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding-top: 14px;
    margin-top: auto;
    border-top: 1px solid #f1f5f9;
}
.cp-footer-info {
    font-size: 13px;
    color: #64748b;
}
.cp-footer-paginate {
    display: flex;
    align-items: center;
    gap: 6px;
}
.cp-btn-page {
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    border-radius: 6px;
    padding: 5px 12px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.cp-btn-page:hover:not(:disabled) {
    background: #f8fafc;
    color: #0f172a;
}
.cp-btn-page:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.cp-btn-page.active {
    background: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
}

/* Right Column: Photo Preview */
.cp-preview-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    overflow: hidden;
}
.cp-preview-nav-btns {
    display: flex;
    align-items: center;
    gap: 6px;
}
.cp-btn-nav-arrow {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s ease;
}
.cp-btn-nav-arrow:hover {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #cbd5e1;
}

.cp-preview-box {
    background: #f8fafc;
    height: 250px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    border-bottom: 1px solid #f1f5f9;
    overflow: hidden;
}
.cp-preview-empty-state {
    text-align: center;
    color: #94a3b8;
}
.cp-preview-img-container {
    width: 100%;
    height: 100%;
    position: relative;
}
.cp-preview-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.cp-btn-zoom {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(15, 23, 42, 0.7);
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
    transition: all 0.2s;
}
.cp-btn-zoom:hover {
    background: #2563eb;
    color: #ffffff;
}

.cp-preview-metadata {
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.cp-meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
}
.cp-meta-label {
    color: #64748b;
    font-weight: 500;
}
.cp-meta-val {
    color: #0f172a;
    font-weight: 700;
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.cp-preview-status-bar {
    padding: 12px 20px;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    font-size: 12px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cp-status-dot-active {
    color: #10b981;
    font-size: 14px;
}

/* Bottom How-it-works Flow Card */
.cp-bottom-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px 22px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
}
.cp-how-flow-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 12px;
}
.cp-flow-steps {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px 22px;
}
.cp-flow-step-item {
    display: flex;
    align-items: center;
    gap: 10px;
}
.cp-flow-icon-wrap {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #eff6ff;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
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
    font-size: 13px;
    font-weight: 600;
    color: #334155;
}
.cp-flow-arrow {
    color: #2563eb;
    font-size: 18px;
    font-weight: bold;
}
.cp-btn-open-settings {
    color: #2563eb;
    font-weight: 600;
    font-size: 13px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}
.cp-btn-open-settings:hover {
    text-decoration: underline;
}

/* Phone Mockup Modal */
.cp-phone-frame {
    width: 280px;
    margin: 0 auto;
    border: 10px solid #1e293b;
    border-radius: 36px;
    background: #ffffff;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
.cp-phone-inner {
    padding: 12px;
}
.cp-phone-status {
    display: flex;
    justify-content: space-between;
    font-size: 10px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 10px;
}
.cp-phone-app-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.cp-app-title {
    font-weight: 800;
    font-size: 13px;
    color: #0f172a;
}
.cp-app-post-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px;
    text-align: left;
}
.cp-post-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}
.cp-post-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #2563eb;
    color: #ffffff;
    font-size: 10px;
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
    height: 120px;
    background: #e2e8f0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    margin-bottom: 8px;
}
.cp-post-caption {
    font-size: 10.5px;
    color: #334155;
    margin-bottom: 8px;
    line-height: 1.35;
}
.cp-post-actions {
    display: flex;
    gap: 12px;
    font-size: 10px;
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

        <!-- Metrics Stats Bar -->
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

        <!-- Main Two-Column Grid -->
        <div class="cp-main-grid">

            <!-- Left Column: Community Gallery & List -->
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

                <!-- Content Card (Gallery + List) -->
                <div class="cp-content-card">
                    <!-- Gallery View Section -->
                    <div id="cp-gallery-section">
                        <!-- Empty State in Gallery -->
                        <div id="cp-gallery-empty" class="cp-empty-illustration-wrap" style="{{ ($totalUploads ?? 0) > 0 ? 'display: none;' : '' }}">
                            <div class="cp-empty-icon-circle">
                                <div class="cp-sparkle-dot s1">✦</div>
                                <div class="cp-sparkle-dot s2">✦</div>
                                <div class="cp-sparkle-dot s3">✦</div>
                                <div class="cp-phone-sync-graphic">
                                    <svg width="44" height="60" viewBox="0 0 44 60" fill="none">
                                        <rect x="2" y="2" width="40" height="56" rx="8" fill="#ffffff" stroke="#2563eb" stroke-width="2.5"/>
                                        <line x1="16" y1="7" x2="28" y2="7" stroke="#93c5fd" stroke-width="2" stroke-linecap="round"/>
                                        <rect x="7" y="14" width="30" height="26" rx="4" fill="#eff6ff" stroke="#bfdbfe" stroke-width="1.5"/>
                                        <circle cx="27" cy="20" r="2.5" fill="#3b82f6"/>
                                        <path d="M10 36L18 25L24 31L28 27L34 36H10Z" fill="#60a5fa"/>
                                    </svg>
                                    <span class="cp-phone-badge-sync">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
                                        </svg>
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
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th style="width: 200px;">NAME</th>
                                        <th>MESSAGE</th>
                                        <th style="width: 140px;">VIEW PHOTOS</th>
                                        <th style="width: 160px;">DATE &amp; TIME</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Card Bottom Footer (Showing & Pagination) -->
                    <div class="cp-card-footer">
                        <div class="cp-footer-info" id="cp-footer-info">Showing 0 of 0 photos</div>
                        <div class="cp-footer-paginate" id="cp-footer-paginate">
                            <button type="button" class="cp-btn-page" id="cp-prev-page" disabled>
                                <i class="fa fa-angle-left"></i> Previous
                            </button>
                            <button type="button" class="cp-btn-page active" id="cp-curr-page">1</button>
                            <button type="button" class="cp-btn-page" id="cp-next-page" disabled>
                                Next <i class="fa fa-angle-right"></i>
                            </button>
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
                <div style="height: 18px;"></div>

                <!-- Preview Card -->
                <div class="cp-preview-card">
                    <div id="cp-preview-visual" class="cp-preview-box">
                        <div class="cp-preview-empty-state">
                            <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                <circle cx="9" cy="9" r="2"/>
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                            </svg>
                            <p class="mb-0 mt-2 text-muted fw-semibold" style="font-size: 13px;">Select a photo to preview</p>
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
                        <div class="cp-flow-icon-wrap">
                            <i class="fa fa-mobile"></i>
                        </div>
                        <span class="cp-flow-num">1</span>
                        <span class="cp-flow-text">Member uploads from app</span>
                    </div>

                    <span class="cp-flow-arrow">&rarr;</span>

                    <!-- Step 2 -->
                    <div class="cp-flow-step-item">
                        <div class="cp-flow-icon-wrap">
                            <i class="fa fa-cloud"></i>
                        </div>
                        <span class="cp-flow-num">2</span>
                        <span class="cp-flow-text">Photo syncs automatically</span>
                    </div>

                    <span class="cp-flow-arrow">&rarr;</span>

                    <!-- Step 3 -->
                    <div class="cp-flow-step-item">
                        <div class="cp-flow-icon-wrap">
                            <i class="fa fa-picture-o"></i>
                        </div>
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

<!-- Modal: Photos Viewer for Ajax Loading -->
<div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">
            <div class="modal-loading p-5 text-center text-muted">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2 small">Loading photos...</div>
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
                <button type="button" class="btn-close close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
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

<script>
window.CommunityPhoto = (function() {
    var data_table;
    var table;
    var allRecords = [];
    var currentIndex = -1;
    var searchTimer = null;

    return {
        init: function() {
            CommunityPhoto.getCommunityPhotos();
            CommunityPhoto.bindEventHandlers();
            CommunityPhoto.viewPhotos();
        },

        getCommunityPhotos: function() {
            var $dataTable = $("#dataTable");

            data_table = table = $dataTable.DataTable({
                headerCallback: function(e, a, t, n, s) {
                    var ths = e.getElementsByTagName("th");
                    if (ths.length > 0) ths[0].innerHTML = '#';
                },
                oLanguage: {
                    sEmptyTable: 'No photos uploaded yet',
                    sZeroRecords: 'No matching photos found'
                },
                processing: true,
                serverSide: true,
                pageLength: 20,
                dom: 'rt',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.date = $("#cp-filter-date").val() || "all";
                        d.sort_order = $("#cp-filter-sort").val() || "newest";
                    }
                },
                columns: [
                    {
                        data: null,
                        name: "serial_no",
                        searchable: false,
                        sortable: false,
                        width: 50,
                        className: "text-center fw-bold text-muted",
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "name",
                        name: "name",
                        width: 200,
                        render: function(data, type, row) {
                            var initial = (data && data.trim().length > 0) ? data.trim().charAt(0).toUpperCase() : 'U';
                            var colors = [
                                { bg: '#eff6ff', color: '#2563eb' },
                                { bg: '#f5f3ff', color: '#7c3aed' },
                                { bg: '#ecfdf5', color: '#059669' },
                                { bg: '#fff7ed', color: '#ea580c' },
                                { bg: '#fdf2f8', color: '#db2777' },
                                { bg: '#fefce8', color: '#ca8a04' }
                            ];
                            var charCode = (data && data.length > 0) ? data.charCodeAt(0) + data.length : 0;
                            var col = colors[charCode % colors.length];

                            var fallbackHtml = '<div class="cp-table-avatar-fallback" style="background: ' + col.bg + '; color: ' + col.color + '; font-weight: 700; width: 32px; height: 32px; min-width: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">' + initial + '</div>';

                            var avatarHtml;
                            if (row && row.avatar && row.avatar.trim() !== '') {
                                avatarHtml = '<div class="position-relative d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px; flex-shrink: 0;">' +
                                    fallbackHtml +
                                    '<img src="' + row.avatar + '" class="cp-table-avatar" alt="" style="position: absolute; top: 0; left: 0; width: 32px; height: 32px; border-radius: 50%; object-fit: cover; z-index: 2;" onerror="this.remove();" />' +
                                '</div>';
                            } else {
                                avatarHtml = fallbackHtml;
                            }

                            return '<div class="d-flex align-items-center gap-2">' +
                                   avatarHtml +
                                   '<div><div class="fw-bold text-dark" style="font-size: 13px;">' + (data || 'N/A') + '</div></div>' +
                                   '</div>';
                        }
                    },
                    {
                        data: "message",
                        name: "message",
                        render: function(data) {
                            if (!data || data === 'N/A' || data.trim() === '') {
                                return '<span class="text-muted fst-italic" style="font-size: 12px;">No caption provided</span>';
                            }
                            return '<span class="text-secondary" style="font-size: 12.5px; line-height: 1.4;">' + data + '</span>';
                        }
                    },
                    {
                        data: "view_photos",
                        name: "view_photos",
                        width: 140,
                        render: function(data, type, row) {
                            var url = (row && row.view_photos_url) ? row.view_photos_url : '';
                            var count = (row && row.images_count) ? row.images_count : 1;
                            var thumb = (row && row.first_image) ?
                                '<img src="' + row.first_image + '" style="width: 34px; height: 34px; border-radius: 6px; object-fit: cover; border: 1px solid #e2e8f0;" alt="Photo">' :
                                '';

                            if (url) {
                                return '<div class="d-flex align-items-center gap-2">' +
                                       thumb +
                                       '<a href="javascript:void(0);" data-url="' + url + '" class="view-photos btn btn-sm btn-outline-primary py-1 px-2" style="border-radius: 8px; font-weight: 600; font-size: 11.5px; display: inline-flex; align-items: center; gap: 4px;"><i class="fa fa-eye"></i> View (' + count + ')</a>' +
                                       '</div>';
                            }
                            return data || '<span class="text-muted">No photos</span>';
                        }
                    },
                    {
                        data: "date_time",
                        name: "date_time",
                        width: 160,
                        render: function(data, type, row) {
                            var formatted = (row && row.date_formatted) ? row.date_formatted : data;
                            var rel = (row && row.relative_time) ? '<div class="text-muted" style="font-size: 11px;">' + row.relative_time + '</div>' : '';
                            return '<div><div style="font-size: 12px; font-weight: 600; color: #334155;">' + (formatted || 'N/A') + '</div>' + rel + '</div>';
                        }
                    }
                ]
            });

            table.on("draw", function() {
                allRecords = table.rows().data().toArray();
                var pageInfo = table.page.info();
                var total = pageInfo.recordsTotal || 0;
                var current = pageInfo.page + 1;
                var totalPages = pageInfo.pages || 1;

                // Sync counts
                $("#cp-records-count").text(total + " photos");
                $("#cp-stat-total").text(total);

                // Sync custom footer info & pagination
                if (total === 0) {
                    $("#cp-footer-info").text("Showing 0 of 0 photos");
                    $("#cp-curr-page").text("1");
                    $("#cp-prev-page").prop("disabled", true);
                    $("#cp-next-page").prop("disabled", true);
                } else {
                    var start = pageInfo.start + 1;
                    var end = pageInfo.end;
                    $("#cp-footer-info").text("Showing " + start + " to " + end + " of " + total + " photos");
                    $("#cp-curr-page").text(current);
                    $("#cp-prev-page").prop("disabled", current <= 1);
                    $("#cp-next-page").prop("disabled", current >= totalPages);
                }

                // Render Gallery
                CommunityPhoto.renderGallery();

                // Select first photo if available and none selected
                if (allRecords.length > 0) {
                    if (currentIndex < 0 || currentIndex >= allRecords.length) {
                        CommunityPhoto.selectPhoto(0);
                    } else {
                        CommunityPhoto.selectPhoto(currentIndex);
                    }
                } else {
                    CommunityPhoto.resetPreview();
                }
            });
        },

        renderGallery: function() {
            var $galleryWrap = $("#cp-gallery-grid");
            var $emptyWrap = $("#cp-gallery-empty");

            if (!allRecords || allRecords.length === 0) {
                $galleryWrap.hide();
                $emptyWrap.show();
                return;
            }

            $emptyWrap.hide();
            $galleryWrap.show().empty();

            $.each(allRecords, function(idx, item) {
                var imgSrc = item.first_image || (item.images && item.images.length > 0 ? item.images[0] : '');
                var isSelected = (idx === currentIndex) ? 'active' : '';
                var initial = (item && item.name && item.name.trim().length > 0) ? item.name.trim().charAt(0).toUpperCase() : 'U';
                var colors = [
                    { bg: '#eff6ff', color: '#2563eb' },
                    { bg: '#f5f3ff', color: '#7c3aed' },
                    { bg: '#ecfdf5', color: '#059669' },
                    { bg: '#fff7ed', color: '#ea580c' },
                    { bg: '#fdf2f8', color: '#db2777' },
                    { bg: '#fefce8', color: '#ca8a04' }
                ];
                var charCode = (item && item.name && item.name.length > 0) ? item.name.charCodeAt(0) + item.name.length : 0;
                var col = colors[charCode % colors.length];

                var cardFallbackHtml = '<div class="cp-card-avatar-fallback" style="background: ' + col.bg + '; color: ' + col.color + '; font-weight: 700; width: 32px; height: 32px; min-width: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">' + initial + '</div>';

                var avatarHtml;
                if (item && item.avatar && item.avatar.trim() !== '') {
                    avatarHtml = '<div class="position-relative d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px; flex-shrink: 0;">' +
                        cardFallbackHtml +
                        '<img src="' + item.avatar + '" class="cp-card-avatar" alt="" style="position: absolute; top: 0; left: 0; width: 32px; height: 32px; border-radius: 50%; object-fit: cover; z-index: 2;" onerror="this.remove();" />' +
                    '</div>';
                } else {
                    avatarHtml = cardFallbackHtml;
                }

                var countBadge = (item.images_count > 1) ?
                    '<span class="cp-card-photo-count"><i class="fa fa-camera"></i> ' + item.images_count + ' photos</span>' : '';

                var cardHtml = `
                    <div class="cp-gallery-card ${isSelected}" data-index="${idx}">
                        <div class="cp-card-header">
                            ${avatarHtml}
                            <div class="cp-card-user-info">
                                <div class="cp-card-name" title="${item.name || ''}">${item.name || 'Member'}</div>
                                <div class="cp-card-time">${item.relative_time || item.date_formatted || ''}</div>
                            </div>
                        </div>
                        <div class="cp-card-image-wrap">
                            ${imgSrc ?
                                `<img src="${imgSrc}" class="cp-card-thumb" alt="Upload" loading="lazy">` :
                                `<div class="cp-card-placeholder d-flex align-items-center justify-content-center h-100 text-muted" style="min-height: 140px; background: #f1f5f9;"><i class="fa fa-picture-o fa-2x"></i></div>`
                            }
                            ${countBadge}
                        </div>
                        ${item.message ? `<div class="cp-card-message" title="${item.message}">${item.message}</div>` : `<div class="cp-card-message empty-msg">No caption</div>`}
                    </div>
                `;
                $galleryWrap.append(cardHtml);
            });
        },

        selectPhoto: function(idx) {
            if (!allRecords || idx < 0 || idx >= allRecords.length) {
                CommunityPhoto.resetPreview();
                return;
            }

            currentIndex = idx;
            var item = allRecords[idx];

            // Highlight gallery card
            $(".cp-gallery-card").removeClass("active");
            $(".cp-gallery-card[data-index='" + idx + "']").addClass("active");

            // Highlight table row
            $("#dataTable tbody tr").removeClass("selected");
            $($("#dataTable tbody tr").get(idx)).addClass("selected");

            // Update Right Preview Box
            var imgSrc = item.first_image || (item.images && item.images.length > 0 ? item.images[0] : '');
            var $box = $("#cp-preview-visual");

            if (imgSrc) {
                $box.html(`
                    <div class="cp-preview-img-container">
                        <img src="${imgSrc}" id="cp-main-preview-img" class="cp-preview-img" alt="Preview">
                        <button type="button" class="cp-btn-zoom view-photos" data-url="${item.view_photos_url}" title="View Fullscreen">
                            <i class="fa fa-arrows-alt"></i>
                        </button>
                    </div>
                `);
            } else {
                $box.html(`
                    <div class="cp-preview-empty-state">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                            <circle cx="9" cy="9" r="2"/>
                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                        </svg>
                        <p class="mb-0 mt-2 text-muted fw-semibold" style="font-size: 13px;">No image available</p>
                    </div>
                `);
            }

            // Update Metadata
            $("#cp-meta-member").text(item.name || '—');
            $("#cp-meta-uploaded").text(item.date_formatted || item.date_time || '—');
            $("#cp-meta-message").text(item.message || 'No message provided');

            // Status bar
            $("#cp-meta-status").html('<span class="cp-status-dot-active">●</span> Mobile upload synced');
        },

        resetPreview: function() {
            currentIndex = -1;
            $("#cp-preview-visual").html(`
                <div class="cp-preview-empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                        <circle cx="9" cy="9" r="2"/>
                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                    </svg>
                    <p class="mb-0 mt-2 text-muted fw-semibold" style="font-size: 13px;">Select a photo to preview</p>
                </div>
            `);
            $("#cp-meta-member").text('—');
            $("#cp-meta-uploaded").text('—');
            $("#cp-meta-message").text('—');
            $("#cp-meta-status").html('<i class="fa fa-clock-o text-muted"></i> Waiting for the first mobile upload');
        },

        bindEventHandlers: function() {
            // View Mode Switching (Gallery vs List)
            $(document).on("click", "#cp-view-gallery", function(e) {
                e.preventDefault();
                $("#cp-view-gallery").addClass("active");
                $("#cp-view-list").removeClass("active");
                $("#cp-list-section").hide();
                $("#cp-gallery-section").show();
                CommunityPhoto.renderGallery();
            });

            $(document).on("click", "#cp-view-list", function(e) {
                e.preventDefault();
                $("#cp-view-list").addClass("active");
                $("#cp-view-gallery").removeClass("active");
                $("#cp-gallery-section").hide();
                $("#cp-list-section").show();
                if (data_table) {
                    data_table.columns.adjust().draw(false);
                }
            });

            // Click Gallery Card to select photo
            $(document).on("click", ".cp-gallery-card", function() {
                var idx = parseInt($(this).data("index"), 10);
                CommunityPhoto.selectPhoto(idx);
            });

            // Click Table Row to select photo
            $(document).on("click", "#dataTable tbody tr", function() {
                var idx = $(this).index();
                CommunityPhoto.selectPhoto(idx);
            });

            // Preview Nav Arrows (< and >)
            $(document).on("click", "#cp-preview-prev", function() {
                if (allRecords.length === 0) return;
                var newIdx = (currentIndex <= 0) ? allRecords.length - 1 : currentIndex - 1;
                CommunityPhoto.selectPhoto(newIdx);
            });

            $(document).on("click", "#cp-preview-next", function() {
                if (allRecords.length === 0) return;
                var newIdx = (currentIndex >= allRecords.length - 1) ? 0 : currentIndex + 1;
                CommunityPhoto.selectPhoto(newIdx);
            });

            // Pagination Controls (< Previous, Next >)
            $(document).on("click", "#cp-prev-page", function(e) {
                e.preventDefault();
                if (data_table) {
                    data_table.page("previous").draw(false);
                }
            });

            $(document).on("click", "#cp-next-page", function(e) {
                e.preventDefault();
                if (data_table) {
                    data_table.page("next").draw(false);
                }
            });

            // Live Search with Debounce
            $("#cp-search-input").on("keyup input", function() {
                clearTimeout(searchTimer);
                var q = $(this).val();
                searchTimer = setTimeout(function() {
                    if (data_table) {
                        data_table.search(q).draw();
                    }
                }, 300);
            });

            // Filter dropdowns
            $("#cp-filter-date, #cp-filter-sort").on("change", function() {
                if (data_table) {
                    data_table.ajax.reload();
                }
            });

            // Per page dropdown
            $("#cp-per-page").on("change", function() {
                if (data_table) {
                    data_table.page.len(parseInt($(this).val(), 10)).draw();
                }
            });

            // Refresh buttons
            $(document).on("click", "#cp-btn-refresh, .cp-check-photos-btn", function(e) {
                e.preventDefault();
                var $icon = $(this).find(".fa-refresh");
                $icon.addClass("fa-spin");
                if (data_table) {
                    data_table.ajax.reload(function() {
                        setTimeout(function() {
                            $icon.removeClass("fa-spin");
                        }, 400);
                    }, false);
                }
            });
        },

        viewPhotos: function() {
            $(document).on("click", ".view-photos", function(e) {
                e.preventDefault();
                var url = $(this).data("url") || $(this).attr("href");
                if (!url || url === "#" || url.indexOf("javascript") === 0) return;

                var $modal = $("#pageModal");
                $modal.find(".modal-dialog").html(
                    '<div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;"><div class="text-center py-5"><div class="spinner-border text-primary" role="status" style="width: 2.5rem; height: 2.5rem;"><span class="visually-hidden">Loading...</span></div><div class="mt-2 text-muted small">Loading photos...</div></div></div>'
                );

                $modal.modal("show");
                $modal.find(".modal-dialog").load(url, function(response, status, xhr) {
                    if (status === "error") {
                        $(this).html('<div class="modal-content" style="border-radius: 16px; border: none;"><div class="p-4 text-center text-danger"><i class="fa fa-exclamation-triangle fa-2x mb-2"></i><p>Failed to load photos. Please try again.</p><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Close</button></div></div>');
                    }
                });
            });
        }
    };
})();

$(document).ready(function() {
    if (!window.__cp_initialized) {
        window.__cp_initialized = true;
        CommunityPhoto.init();
    }
});
</script>
@endpush
