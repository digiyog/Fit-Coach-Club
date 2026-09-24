@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Community Photos | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
    /* Community Photos Modern UI Styles */
    .community-container {
        padding: 4px 6px 24px 6px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Page Header */
    .comm-header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 22px;
    }

    .comm-breadcrumb {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 6px;
    }

    .comm-breadcrumb span.active {
        color: #0f172a;
        font-weight: 600;
    }

    .comm-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .comm-subtitle {
        font-size: 14px;
        color: #64748b;
        font-weight: 400;
        margin-bottom: 0;
    }

    .comm-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .badge-sync-live {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        border-radius: 999px;
        padding: 6px 14px;
        font-size: 12.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .sync-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
    }

    .btn-comm-action {
        background: #ffffff;
        color: #2563eb;
        border: 1.5px solid #2563eb;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .btn-comm-action:hover {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #1d4ed8;
    }

    /* Top Metrics Bar */
    .comm-metrics-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        margin-bottom: 24px;
    }

    .comm-metrics-left {
        display: flex;
        align-items: center;
        gap: 28px;
        flex-wrap: wrap;
        flex: 1;
    }

    .comm-metric-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .comm-metric-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .comm-metric-icon.blue { background: #eff6ff; color: #2563eb; }
    .comm-metric-icon.purple { background: #f5f3ff; color: #7c3aed; }
    .comm-metric-icon.green { background: #ecfdf5; color: #10b981; }
    .comm-metric-icon.amber { background: #fffbeb; color: #f59e0b; }

    .comm-metric-data {
        display: flex;
        flex-direction: column;
    }

    .comm-metric-num {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .comm-metric-label {
        font-size: 12px;
        font-weight: 500;
        color: #64748b;
    }

    .comm-metrics-right-tip {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-left: 24px;
        border-left: 1px solid #f1f5f9;
        max-width: 320px;
    }

    .comm-cloud-icon {
        font-size: 26px;
        color: #2563eb;
        flex-shrink: 0;
    }

    .comm-tip-text {
        font-size: 12px;
        color: #475569;
        line-height: 1.4;
        margin-bottom: 0;
    }

    /* Section Headers */
    .comm-section-header {
        margin-bottom: 14px;
    }

    .comm-section-title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2px;
    }

    .comm-section-title-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .comm-section-pill {
        width: 4px;
        height: 18px;
        background: #2563eb;
        border-radius: 99px;
    }

    .comm-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0;
        letter-spacing: -0.01em;
    }

    .comm-section-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 0;
    }

    /* Left Card: Gallery & Filters */
    .comm-main-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .comm-filter-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .comm-search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .comm-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
    }

    .comm-search-input {
        width: 100%;
        height: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding-left: 36px;
        padding-right: 12px;
        font-size: 13.5px;
        background: #f8fafc;
        transition: all 0.2s ease;
    }

    .comm-search-input:focus {
        background: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        outline: none;
    }

    .comm-filter-select {
        height: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #334155;
        padding: 0 26px 0 10px;
        background-color: #ffffff;
        cursor: pointer;
    }

    .comm-filter-select:focus {
        border-color: #3b82f6;
        outline: none;
    }

    .comm-view-toggle-wrap {
        display: inline-flex;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
    }

    .btn-view-toggle {
        border: none;
        background: #ffffff;
        color: #64748b;
        font-size: 12.5px;
        font-weight: 600;
        padding: 8px 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-view-toggle.active {
        background: #2563eb;
        color: #ffffff;
    }

    .comm-toolbar-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .comm-count-badge {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
    }

    .comm-per-page-select {
        height: 32px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 500;
        color: #334155;
        padding: 0 24px 0 10px;
        background-color: #ffffff;
        cursor: pointer;
    }

    /* Empty State View */
    .comm-empty-state {
        text-align: center;
        padding: 56px 20px;
        background: #ffffff;
        border-radius: 12px;
    }

    .comm-phone-illustration-wrap {
        position: relative;
        width: 100px;
        height: 120px;
        margin: 0 auto 18px auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .comm-phone-bg {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #eff6ff;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
    }

    .comm-phone-outline {
        position: relative;
        z-index: 2;
        width: 52px;
        height: 86px;
        border: 2.5px solid #2563eb;
        border-radius: 12px;
        background: #ffffff;
        padding: 6px 4px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    }

    .comm-phone-inner-icon {
        font-size: 18px;
        color: #93c5fd;
    }

    .comm-phone-sync-badge {
        position: absolute;
        bottom: 6px;
        right: 14px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #bfdbfe;
        color: #1d4ed8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        z-index: 3;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .comm-empty-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .comm-empty-desc {
        font-size: 13.5px;
        color: #64748b;
        margin-bottom: 18px;
        max-width: 440px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.45;
    }

    .btn-check-new {
        background: #ffffff;
        color: #2563eb;
        border: 1.5px solid #2563eb;
        border-radius: 10px;
        padding: 9px 20px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-check-new:hover {
        background: #eff6ff;
        color: #1d4ed8;
    }

    /* Right Card: Photo Preview */
    .comm-preview-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .comm-nav-arrow-btn {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .comm-nav-arrow-btn:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .comm-preview-box {
        background: #f8fafc;
        border: 1.5px dashed #e2e8f0;
        border-radius: 14px;
        min-height: 240px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 24px;
        margin-bottom: 20px;
        color: #94a3b8;
    }

    .comm-preview-box img {
        max-width: 100%;
        max-height: 260px;
        border-radius: 10px;
        object-fit: cover;
    }

    .comm-preview-meta-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }

    .comm-meta-row {
        display: flex;
        align-items: center;
        font-size: 13px;
    }

    .comm-meta-label {
        width: 90px;
        color: #64748b;
        font-weight: 500;
    }

    .comm-meta-val {
        color: #0f172a;
        font-weight: 600;
        flex: 1;
    }

    .comm-preview-footer {
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
        font-size: 12px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Bottom Flow Card */
    .comm-flow-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        margin-top: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 18px;
    }

    .comm-flow-left {
        display: flex;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .comm-flow-header-wrap {
        margin-right: 12px;
    }

    .comm-flow-steps {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .comm-step-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .comm-step-icon-wrap {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .comm-step-num {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .comm-step-arrow {
        color: #94a3b8;
        font-size: 13px;
    }

    .comm-flow-link {
        font-size: 13.5px;
        font-weight: 600;
        color: #2563eb;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .comm-flow-link:hover {
        color: #1d4ed8;
        transform: translateX(2px);
    }

    /* DataTable Overrides */
    .dataTables_wrapper .dt-buttons,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_length {
        display: none !important;
    }

    #dataTable thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 12px 14px !important;
    }

    #dataTable tbody td {
        padding: 12px 14px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #334155;
        font-size: 13px;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="community-container">

        <!-- Top Header & Actions -->
        <div class="comm-header-section">
            <div>
                <div class="comm-breadcrumb">
                    Achievements & Hub &nbsp;/&nbsp; <span class="active">Community Photos</span>
                </div>
                <h1 class="comm-title">Community photos</h1>
                <p class="comm-subtitle">Browse photos and messages shared by members from the mobile app.</p>
            </div>

            <div class="comm-header-actions">
                <span class="badge-sync-live">
                    <span class="sync-dot"></span> Mobile sync live
                </span>

                <button type="button" class="btn-comm-action" id="btnRefreshCommunity">
                    <i class="fa fa-refresh"></i>
                    <span>Refresh</span>
                </button>

                <button type="button" class="btn-comm-action" data-bs-toggle="modal" data-bs-target="#communityPreviewModal">
                    <i class="fa fa-eye"></i>
                    <span>View in app</span>
                </button>
            </div>
        </div>

        <!-- Top Metrics Bar -->
        <div class="comm-metrics-bar">
            <div class="comm-metrics-left">
                <!-- Total uploads -->
                <div class="comm-metric-item">
                    <div class="comm-metric-icon blue">
                        <i class="fa fa-picture-o"></i>
                    </div>
                    <div class="comm-metric-data">
                        <span class="comm-metric-num" id="stat-total-uploads">{{ $totalUploads ?? 0 }}</span>
                        <span class="comm-metric-label">Total uploads</span>
                    </div>
                </div>

                <!-- Today -->
                <div class="comm-metric-item">
                    <div class="comm-metric-icon purple">
                        <i class="fa fa-calendar-o"></i>
                    </div>
                    <div class="comm-metric-data">
                        <span class="comm-metric-num" id="stat-today-uploads">{{ $todayUploads ?? 0 }}</span>
                        <span class="comm-metric-label">Today</span>
                    </div>
                </div>

                <!-- This week -->
                <div class="comm-metric-item">
                    <div class="comm-metric-icon green">
                        <i class="fa fa-bar-chart"></i>
                    </div>
                    <div class="comm-metric-data">
                        <span class="comm-metric-num" id="stat-week-uploads">{{ $weekUploads ?? 0 }}</span>
                        <span class="comm-metric-label">This week</span>
                    </div>
                </div>

                <!-- With messages -->
                <div class="comm-metric-item">
                    <div class="comm-metric-icon amber">
                        <i class="fa fa-comment-o"></i>
                    </div>
                    <div class="comm-metric-data">
                        <span class="comm-metric-num" id="stat-with-messages">{{ $withMessagesCount ?? 0 }}</span>
                        <span class="comm-metric-label">With messages</span>
                    </div>
                </div>
            </div>

            <!-- Right Tip Box -->
            <div class="comm-metrics-right-tip">
                <i class="fa fa-cloud comm-cloud-icon"></i>
                <p class="comm-tip-text">Photos appear here automatically after members upload from the app.</p>
            </div>
        </div>

        <!-- Main 2-Column Content Row -->
        <div class="row g-3">
            <!-- Left Column: Community Gallery -->
            <div class="col-xl-8 col-lg-8 col-md-12">
                <div class="comm-main-card">
                    <!-- Section Header -->
                    <div class="comm-section-header">
                        <div class="comm-section-title-row">
                            <div class="comm-section-title-left">
                                <span class="comm-section-pill"></span>
                                <h2 class="comm-section-title">Community gallery</h2>
                            </div>
                        </div>
                        <p class="comm-section-subtitle">All member uploads in one place.</p>
                    </div>

                    <!-- Filter Bar -->
                    <div class="comm-filter-bar">
                        <div class="comm-search-input-wrap">
                            <i class="fa fa-search comm-search-icon"></i>
                            <input type="text" id="community-search-input" class="comm-search-input" placeholder="Search member or message...">
                        </div>

                        <select id="community-filter-date" class="comm-filter-select">
                            <option value="all">All dates</option>
                            <option value="today">Today</option>
                            <option value="week">This week</option>
                            <option value="month">This month</option>
                        </select>

                        <select id="community-filter-sort" class="comm-filter-select">
                            <option value="desc">Newest first</option>
                            <option value="asc">Oldest first</option>
                        </select>

                        <div class="comm-view-toggle-wrap">
                            <button type="button" class="btn-view-toggle active" id="btnViewGallery">
                                <i class="fa fa-th-large"></i> Gallery
                            </button>
                            <button type="button" class="btn-view-toggle" id="btnViewList">
                                <i class="fa fa-list"></i> List
                            </button>
                        </div>
                    </div>

                    <!-- Toolbar row -->
                    <div class="comm-toolbar-row">
                        <span class="comm-count-badge" id="comm-record-count-text">{{ $totalUploads ?? 0 }} photos</span>
                        <select id="comm-page-length" class="comm-per-page-select">
                            <option value="20" selected>20 per page</option>
                            <option value="50">50 per page</option>
                            <option value="75">75 per page</option>
                        </select>
                    </div>

                    <!-- Table container for DataTable / List View -->
                    <div class="table-responsive data-table-container" style="display: none;">
                        <table id="dataTable" class="table table-hover" data-url="{{ route('nutritionPanel.community-photos.getCommunityPhotos') }}">
                            <thead>
                                <tr>
                                    <th class="checkbox-column" style="width: 40px;"> S.No </th>
                                    <th> Name </th>
                                    <th> Message </th>
                                    <th> View Photos </th>
                                    <th> Date & Time </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State View -->
                    <div id="empty-state-view" class="comm-empty-state">
                        <div class="comm-phone-illustration-wrap">
                            <div class="comm-phone-bg"></div>
                            <div class="comm-phone-outline">
                                <i class="fa fa-picture-o comm-phone-inner-icon"></i>
                            </div>
                            <div class="comm-phone-sync-badge">
                                <i class="fa fa-refresh"></i>
                            </div>
                        </div>

                        <h3 class="comm-empty-title">No community photos yet</h3>
                        <p class="comm-empty-desc">Photos shared through the Fit Coach Club mobile app will appear here automatically. No admin upload is required.</p>
                        
                        <button type="button" class="btn-check-new" id="btnCheckNewPhotos">
                            <i class="fa fa-refresh"></i>
                            <span>Check for new photos</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Photo Preview -->
            <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="comm-preview-card">
                    <div>
                        <!-- Section Header -->
                        <div class="comm-section-header">
                            <div class="comm-section-title-row">
                                <div class="comm-section-title-left">
                                    <span class="comm-section-pill"></span>
                                    <h2 class="comm-section-title">Photo preview</h2>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <button type="button" class="comm-nav-arrow-btn" title="Previous photo"><i class="fa fa-chevron-left"></i></button>
                                    <button type="button" class="comm-nav-arrow-btn" title="Next photo"><i class="fa fa-chevron-right"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Placeholder Box -->
                        <div class="comm-preview-box" id="preview-image-container">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            <span style="font-size: 13.5px; font-weight: 500;">Select a photo to preview</span>
                        </div>

                        <!-- Meta List -->
                        <div class="comm-preview-meta-list">
                            <div class="comm-meta-row">
                                <span class="comm-meta-label">Member</span>
                                <span class="comm-meta-val" id="meta-member-val">—</span>
                            </div>
                            <div class="comm-meta-row">
                                <span class="comm-meta-label">Uploaded</span>
                                <span class="comm-meta-val" id="meta-uploaded-val">—</span>
                            </div>
                            <div class="comm-meta-row">
                                <span class="comm-meta-label">Message</span>
                                <span class="comm-meta-val" id="meta-message-val">—</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="comm-preview-footer">
                        <i class="fa fa-clock-o text-muted"></i>
                        <span>Waiting for the first mobile upload</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Flow Card -->
        <div class="comm-flow-card">
            <div class="comm-flow-left">
                <div class="comm-flow-header-wrap">
                    <div class="comm-section-title-left">
                        <span class="comm-section-pill"></span>
                        <h3 class="comm-section-title" style="font-size: 15.5px;">How mobile uploads appear</h3>
                    </div>
                </div>

                <div class="comm-flow-steps">
                    <!-- Step 1 -->
                    <div class="comm-step-item">
                        <div class="comm-step-icon-wrap">
                            <i class="fa fa-mobile" style="font-size: 18px;"></i>
                        </div>
                        <span class="comm-step-num">1</span>
                        <span>Member uploads from app</span>
                    </div>

                    <i class="fa fa-arrow-right comm-step-arrow"></i>

                    <!-- Step 2 -->
                    <div class="comm-step-item">
                        <div class="comm-step-icon-wrap">
                            <i class="fa fa-cloud"></i>
                        </div>
                        <span class="comm-step-num">2</span>
                        <span>Photo syncs automatically</span>
                    </div>

                    <i class="fa fa-arrow-right comm-step-arrow"></i>

                    <!-- Step 3 -->
                    <div class="comm-step-item">
                        <div class="comm-step-icon-wrap">
                            <i class="fa fa-picture-o"></i>
                        </div>
                        <span class="comm-step-num">3</span>
                        <span>Appears in community gallery</span>
                    </div>
                </div>
            </div>

            <a href="javascript:;" class="comm-flow-link" data-bs-toggle="modal" data-bs-target="#communityPreviewModal">
                <span>Open app settings</span>
                <i class="fa fa-arrow-right"></i>
            </a>
        </div>

    </div>
</div>

<!-- Modal: Community App Preview -->
<div class="modal fade" id="communityPreviewModal" tabindex="-1" aria-labelledby="communityPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; background: #ffffff;">
            <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 18px 24px;">
                <h5 class="modal-title fw-bold" id="communityPreviewModalLabel">
                    <i class="fa fa-mobile text-primary me-2 fs-5"></i> In-App Community Feed Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div style="width: 300px; margin: 0 auto; background: #0f172a; border-radius: 36px; padding: 12px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35); border: 4px solid #334155;">
                    <div style="background: #f8fafc; border-radius: 26px; overflow: hidden; min-height: 480px;">
                        <div style="background: #2563eb; color: #fff; padding: 14px 16px; text-align: center;">
                            <div class="fw-bold" style="font-size: 14.5px;">Community Photos</div>
                            <div style="font-size: 11px; opacity: 0.85;">Fit Coach Club Live Feed</div>
                        </div>
                        <div style="padding: 14px; text-align: left;">
                            <div style="background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 10px; margin-bottom: 12px;">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div style="width: 28px; height: 28px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">M</div>
                                    <div>
                                        <div style="font-size: 12px; font-weight: bold;">Member Win</div>
                                        <div style="font-size: 10px; color: #64748b;">Just now</div>
                                    </div>
                                </div>
                                <div style="background: #f1f5f9; height: 120px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                    <i class="fa fa-picture-o fa-2x"></i>
                                </div>
                                <div style="font-size: 11.5px; color: #334155; margin-top: 8px;">Day 30 transformation update! Feeling energetic 💪</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center" style="border-top: 1px solid #f1f5f9; padding: 14px 24px;">
                <button type="button" class="btn btn-primary px-4 py-2 rounded-3" data-bs-dismiss="modal">Close Preview</button>
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
