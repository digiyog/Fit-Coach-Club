@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Activities | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
    /* Activities Modern UI Styles */
    .activities-container {
        padding: 4px 6px 24px 6px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Page Header */
    .act-header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 22px;
    }

    .act-breadcrumb {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 6px;
    }

    .act-breadcrumb span.active {
        color: #0f172a;
        font-weight: 600;
    }

    .act-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .act-subtitle {
        font-size: 14px;
        color: #64748b;
        font-weight: 400;
        margin-bottom: 0;
    }

    .act-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-notif-settings {
        background: #ffffff;
        color: #2563eb;
        border: 1.5px solid #2563eb;
        border-radius: 10px;
        padding: 8px 18px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .btn-notif-settings:hover {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-create-act {
        background: #2563eb;
        color: #ffffff !important;
        border: none;
        border-radius: 10px;
        padding: 9px 20px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        text-decoration: none !important;
    }

    .btn-create-act:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
    }

    /* Top Metrics Bar */
    .act-metrics-bar {
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

    .act-metrics-left {
        display: flex;
        align-items: center;
        gap: 28px;
        flex-wrap: wrap;
        flex: 1;
    }

    .act-metric-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .act-metric-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .act-metric-icon.blue { background: #eff6ff; color: #2563eb; }
    .act-metric-icon.purple { background: #f5f3ff; color: #7c3aed; }
    .act-metric-icon.green { background: #ecfdf5; color: #10b981; }
    .act-metric-icon.amber { background: #fffbeb; color: #f59e0b; }

    .act-metric-data {
        display: flex;
        flex-direction: column;
    }

    .act-metric-num {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .act-metric-label {
        font-size: 12px;
        font-weight: 500;
        color: #64748b;
    }

    .act-metrics-right-ready {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding-left: 24px;
        border-left: 1px solid #f1f5f9;
    }

    .badge-notif-ready {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }

    .ready-dot {
        width: 7px;
        height: 7px;
        background: #10b981;
        border-radius: 50%;
        display: inline-block;
    }

    .act-ready-text {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 0;
    }

    /* Activity Planner Main Card */
    .act-main-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .act-section-header {
        margin-bottom: 16px;
    }

    .act-section-title-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2px;
    }

    .act-section-pill {
        width: 4px;
        height: 18px;
        background: #2563eb;
        border-radius: 99px;
    }

    .act-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0;
        letter-spacing: -0.01em;
    }

    .act-section-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 0;
    }

    /* Filter Bar */
    .act-filter-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        margin-bottom: 16px;
    }

    .act-search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 220px;
        display: flex;
        align-items: center;
    }

    .act-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
        z-index: 10;
    }

    .act-search-input {
        width: 100%;
        height: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding-left: 44px !important;
        padding-right: 14px !important;
        font-size: 13.5px;
        background: #f8fafc;
        transition: all 0.2s ease;
    }

    .act-search-input:focus {
        background: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        outline: none;
    }

    .act-filter-select {
        height: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #334155;
        padding: 0 28px 0 12px;
        background-color: #ffffff;
        min-width: 130px;
        cursor: pointer;
    }

    .act-filter-select:focus {
        border-color: #3b82f6;
        outline: none;
    }

    .btn-more-filters {
        height: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        padding: 0 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-more-filters:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    /* Table Toolbar */
    .act-table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
    }

    .act-toolbar-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .act-total-count {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .act-per-page-select {
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

    .act-toolbar-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-action-outline {
        border: 1.5px solid #2563eb;
        color: #2563eb;
        background: #ffffff;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12.5px;
        padding: 6px 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-action-outline:hover {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .btn-action-muted {
        border: 1px solid #e2e8f0;
        color: #94a3b8;
        background: #f8fafc;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12.5px;
        padding: 6px 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        cursor: not-allowed;
    }

    .btn-action-muted.enabled {
        border-color: #3b82f6;
        color: #2563eb;
        background: #ffffff;
        cursor: pointer;
    }

    .btn-action-muted.enabled:hover {
        background: #eff6ff;
    }

    .btn-action-muted.danger-enabled {
        border-color: #ef4444;
        color: #ef4444;
        background: #ffffff;
        cursor: pointer;
    }

    .btn-action-muted.danger-enabled:hover {
        background: #fef2f2;
    }

    /* DataTable Table Overrides */
    #dataTable {
        margin: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
    }

    #dataTable thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-top: none !important;
        padding: 12px 14px !important;
        white-space: nowrap;
    }

    #dataTable tbody td {
        padding: 12px 14px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #334155;
        font-size: 13px;
    }

    #dataTable tbody tr:hover td {
        background-color: #f8fafc !important;
    }

    .dataTables_wrapper .dt-buttons,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_length {
        display: none !important;
    }

    .dataTables_wrapper .dataTables_info {
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
        padding-top: 14px;
    }

    .dataTables_wrapper .dataTables_paginate {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        padding: 5px 12px !important;
        border: 1px solid transparent !important;
        margin: 0 2px !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #2563eb !important;
        color: #ffffff !important;
        border-color: #2563eb !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #f1f5f9 !important;
        color: #0f172a !important;
    }

    /* Badges & Cell Styles */
    .act-item-name {
        font-weight: 600;
        color: #0f172a;
    }

    .badge-type-upcoming {
        background-color: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
    }

    .badge-type-old {
        background-color: #f8fafc;
        color: #64748b;
        border: 1px solid #e2e8f0;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
    }

    .act-date-text {
        color: #475569;
        font-size: 12.5px;
    }

    .badge-notif-scheduled {
        background-color: #f5f3ff;
        color: #7c3aed;
        border: 1px solid #ddd6fe;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
    }

    .badge-notif-sent {
        background-color: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
    }

    .badge-notif-draft {
        background-color: #f8fafc;
        color: #94a3b8;
        border: 1px solid #e2e8f0;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
    }

    .badge-status-active {
        background-color: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
    }

    .badge-status-inactive {
        background-color: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
    }

    .btn-act-edit {
        border: 1px solid #2563eb;
        color: #2563eb !important;
        background: #ffffff;
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }

    .btn-act-edit:hover {
        background: #eff6ff;
        color: #1d4ed8 !important;
    }

    .act-order-input {
        width: 68px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        font-weight: 600;
        font-size: 12.5px;
    }

    /* Empty State View */
    .act-empty-state {
        text-align: center;
        padding: 56px 20px;
        background: #ffffff;
    }

    .act-empty-illustration-wrap {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 18px auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .act-empty-circle-bg {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: #eff6ff;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
    }

    .act-empty-calendar-card {
        position: relative;
        z-index: 2;
        width: 58px;
        height: 58px;
        border: 2.5px solid #2563eb;
        border-radius: 14px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
    }

    .act-empty-cal-icon {
        font-size: 26px;
        color: #2563eb;
    }

    .act-empty-bell-badge {
        position: absolute;
        bottom: -6px;
        right: -6px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #bfdbfe;
        color: #1d4ed8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        z-index: 3;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .act-sparkle-1 {
        position: absolute;
        top: 2px;
        right: 8px;
        color: #60a5fa;
        font-size: 16px;
        z-index: 3;
    }

    .act-sparkle-2 {
        position: absolute;
        bottom: 8px;
        left: 4px;
        color: #93c5fd;
        font-size: 12px;
        z-index: 3;
    }

    .act-empty-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .act-empty-desc {
        font-size: 13.5px;
        color: #64748b;
        margin-bottom: 18px;
        max-width: 440px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.45;
    }

    .btn-empty-create {
        background: #2563eb;
        color: #ffffff !important;
        border: none;
        border-radius: 10px;
        padding: 9px 22px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        text-decoration: none !important;
    }

    .btn-empty-create:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
    }

    /* Bottom Workflow Card */
    .act-workflow-card {
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

    .act-workflow-left {
        display: flex;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .act-workflow-steps {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .act-step-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .act-step-icon-wrap {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .act-step-icon-wrap.blue { background: #eff6ff; color: #2563eb; }
    .act-step-icon-wrap.purple { background: #f5f3ff; color: #7c3aed; }
    .act-step-icon-wrap.indigo { background: #eff6ff; color: #4f46e5; }

    .act-step-num {
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

    .act-step-arrow {
        color: #94a3b8;
        font-size: 14px;
    }

    .act-workflow-link {
        font-size: 13.5px;
        font-weight: 600;
        color: #2563eb;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .act-workflow-link:hover {
        color: #1d4ed8;
        transform: translateX(2px);
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="activities-container">

        <!-- Top Header & Actions -->
        <div class="act-header-section">
            <div>
                <div class="act-breadcrumb">
                    Achievements & Hub &nbsp;/&nbsp; <span class="active">Activities</span>
                </div>
                <h1 class="act-title">Activities</h1>
                <p class="act-subtitle">Create club activities and notify members through the mobile app.</p>
            </div>

            <div class="act-header-actions">
                <button type="button" class="btn-notif-settings" data-bs-toggle="modal" data-bs-target="#notificationSettingsModal">
                    <i class="fa fa-bell-o"></i>
                    <span>Notification settings</span>
                </button>

                <a href="{{ route('nutritionPanel.activities.create') }}" class="btn-create-act">
                    <i class="fa fa-plus"></i>
                    <span>Create activity</span>
                </a>
            </div>
        </div>

        <!-- Top Metrics Bar -->
        <div class="act-metrics-bar">
            <div class="act-metrics-left">
                <!-- 1. Total activities -->
                <div class="act-metric-item">
                    <div class="act-metric-icon blue">
                        <i class="fa fa-file-text-o"></i>
                    </div>
                    <div class="act-metric-data">
                        <span class="act-metric-num" id="stat-total-activities">{{ $totalActivities ?? 0 }}</span>
                        <span class="act-metric-label">Total activities</span>
                    </div>
                </div>

                <!-- 2. Scheduled -->
                <div class="act-metric-item">
                    <div class="act-metric-icon purple">
                        <i class="fa fa-calendar-o"></i>
                    </div>
                    <div class="act-metric-data">
                        <span class="act-metric-num" id="stat-scheduled-activities">{{ $scheduledActivities ?? 0 }}</span>
                        <span class="act-metric-label">Scheduled</span>
                    </div>
                </div>

                <!-- 3. Published -->
                <div class="act-metric-item">
                    <div class="act-metric-icon green">
                        <i class="fa fa-paper-plane-o"></i>
                    </div>
                    <div class="act-metric-data">
                        <span class="act-metric-num" id="stat-published-activities">{{ $publishedActivities ?? 0 }}</span>
                        <span class="act-metric-label">Published</span>
                    </div>
                </div>

                <!-- 4. Notifications sent -->
                <div class="act-metric-item">
                    <div class="act-metric-icon amber">
                        <i class="fa fa-bell-o"></i>
                    </div>
                    <div class="act-metric-data">
                        <span class="act-metric-num" id="stat-notifications-sent">{{ $notificationsSent ?? 0 }}</span>
                        <span class="act-metric-label">Notifications sent</span>
                    </div>
                </div>
            </div>

            <!-- Right App Notifications Ready Box -->
            <div class="act-metrics-right-ready">
                <div class="badge-notif-ready">
                    <span class="ready-dot"></span> App notifications ready
                </div>
                <p class="act-ready-text">Members are notified when an activity is published.</p>
            </div>
        </div>

        <!-- Activity Planner Main Card -->
        <div class="act-main-card">
            <!-- Section Header -->
            <div class="act-section-header">
                <div class="act-section-title-row">
                    <span class="act-section-pill"></span>
                    <h2 class="act-section-title">Activity planner</h2>
                </div>
                <p class="act-section-subtitle">Schedule, publish and manage member activities.</p>
            </div>

            <!-- Filter Bar -->
            <div class="act-filter-bar">
                <div class="act-search-input-wrap">
                    <i class="fa fa-search act-search-icon"></i>
                    <input type="text" id="act-search-input" class="form-control act-search-input" placeholder="Search activities..." style="padding-left: 44px !important; padding-right: 14px !important;">
                </div>

                <select id="filter-activity-type" class="act-filter-select">
                    <option value="">All types</option>
                    <option value="2">Upcoming Activity</option>
                    <option value="1">Old Activity</option>
                </select>

                <select id="filter-date" class="act-filter-select">
                    <option value="">All dates</option>
                    <option value="today">Today</option>
                    <option value="week">This week</option>
                    <option value="month">This month</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="past">Past</option>
                </select>

                <select id="filter-status" class="act-filter-select">
                    <option value="">All statuses</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn-more-filters dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-filter"></i> <span>More filters</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2" style="border-radius: 10px;">
                        <li><a class="dropdown-item py-2 rounded-2" href="javascript:;" id="btnResetFilters"><i class="fa fa-undo me-2 text-muted"></i> Reset all filters</a></li>
                    </ul>
                </div>
            </div>

            <!-- Table Toolbar -->
            <div class="act-table-toolbar">
                <div class="act-toolbar-left">
                    <span class="act-total-count" id="act-count-text">{{ $totalActivities ?? 0 }} activities</span>
                    <select id="act-page-length" class="act-per-page-select">
                        <option value="20" selected>20 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>

                <div class="act-toolbar-right">
                    <button type="button" class="btn-action-outline update-order">
                        <i class="fa fa-exchange"></i> <span>Update order</span>
                    </button>

                    <button type="button" class="btn-action-muted change-status" disabled>
                        <i class="fa fa-refresh"></i> <span>Change status</span>
                    </button>

                    <button type="button" class="btn-action-muted dt-delete" disabled>
                        <i class="fa fa-trash-o"></i> <span>Delete</span>
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-responsive data-table-container">
                <table id="dataTable" class="table table-hover" data-url="{{ route('nutritionPanel.activities.getActivities') }}"  data-change-status-url="{{ route('nutritionPanel.activities.changeStatus') }}" data-destroy-url="{{ route('nutritionPanel.activities.destroy') }}" data-update-order-url="{{ route('nutritionPanel.activities.updateOrder') }}">
                    <thead>
                        <tr>
                            <th class="checkbox-column" style="width: 40px;"> # </th>
                            <th>Name</th>
                            <th>Activity type</th>
                            <th>Date</th>
                            <th>App notification</th>
                            <th style="width: 100px;">Order</th>
                            <th style="width: 100px;">Status</th>
                            <th class="text-end" style="width: 90px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <!-- Empty State View -->
            <div id="act-empty-state" class="act-empty-state" style="display: none;">
                <div class="act-empty-illustration-wrap">
                    <div class="act-empty-circle-bg"></div>
                    <div class="act-empty-calendar-card">
                        <i class="fa fa-calendar-o act-empty-cal-icon"></i>
                        <div class="act-empty-bell-badge">
                            <i class="fa fa-bell-o"></i>
                        </div>
                    </div>
                    <span class="act-sparkle-1">✦</span>
                    <span class="act-sparkle-2">✦</span>
                </div>

                <h3 class="act-empty-title">No activities created yet</h3>
                <p class="act-empty-desc">Create an activity, choose its date and publish it to notify members in the app.</p>

                <a href="{{ route('nutritionPanel.activities.create') }}" class="btn-empty-create">
                    <i class="fa fa-plus"></i> Create activity
                </a>
            </div>
        </div>

        <!-- Bottom Workflow Card -->
        <div class="act-workflow-card">
            <div class="act-workflow-left">
                <div class="act-section-title-row me-2">
                    <span class="act-section-pill"></span>
                    <h3 class="act-section-title" style="font-size: 15.5px;">From backend to member app</h3>
                </div>

                <div class="act-workflow-steps">
                    <!-- Step 1 -->
                    <div class="act-step-item">
                        <div class="act-step-icon-wrap blue">
                            <i class="fa fa-file-text-o"></i>
                        </div>
                        <span class="act-step-num">1</span>
                        <span>Create activity</span>
                    </div>

                    <span class="act-step-arrow">→</span>

                    <!-- Step 2 -->
                    <div class="act-step-item">
                        <div class="act-step-icon-wrap purple">
                            <i class="fa fa-calendar-o"></i>
                        </div>
                        <span class="act-step-num">2</span>
                        <span>Set date and notification</span>
                    </div>

                    <span class="act-step-arrow">→</span>

                    <!-- Step 3 -->
                    <div class="act-step-item">
                        <div class="act-step-icon-wrap indigo">
                            <i class="fa fa-bell-o"></i>
                        </div>
                        <span class="act-step-num">3</span>
                        <span>Publish and notify members</span>
                    </div>
                </div>
            </div>

            <a href="javascript:;" class="act-workflow-link" data-bs-toggle="modal" data-bs-target="#notificationSettingsModal">
                <span>Manage notification settings</span>
                <i class="fa fa-arrow-right"></i>
            </a>
        </div>

    </div>
</div>

<!-- Modal: Notification Settings -->
<div class="modal fade" id="notificationSettingsModal" tabindex="-1" aria-labelledby="notificationSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; background: #ffffff; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 18px 24px;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                        <i class="fa fa-bell-o"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="notificationSettingsModalLabel" style="font-size: 16px; color: #0f172a;">
                            Activity Notification Settings
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 12px;">Configure push notifications for club activities</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex flex-column gap-3">
                    <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fw-semibold text-dark" style="font-size: 13.5px;">Auto Push on Publish</span>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="switchAutoPush" checked style="cursor: pointer;">
                            </div>
                        </div>
                        <p class="text-muted mb-0" style="font-size: 12px;">Notify all subscribed club members immediately when a new activity is published.</p>
                    </div>

                    <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fw-semibold text-dark" style="font-size: 13.5px;">1-Hour Activity Reminder</span>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="switchReminder" checked style="cursor: pointer;">
                            </div>
                        </div>
                        <p class="text-muted mb-0" style="font-size: 12px;">Send an automated push reminder 1 hour prior to scheduled upcoming activities.</p>
                    </div>

                    <div class="p-3 rounded-3" style="background: #ecfdf5; border: 1px solid #a7f3d0;">
                        <div class="d-flex align-items-center gap-2 text-success fw-semibold" style="font-size: 13px;">
                            <i class="fa fa-check-circle"></i> Mobile Push Engine Connected
                        </div>
                        <p class="text-secondary mb-0 mt-1" style="font-size: 12px;">Members will receive rich notifications with custom activity titles and dates on their mobile devices.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 14px 24px;">
                <button type="button" class="btn btn-light px-4 py-2 rounded-3 text-secondary fw-semibold" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold" data-bs-dismiss="modal" style="background: #2563eb; border-color: #2563eb;">Save Preferences</button>
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
