@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Achievements | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
    /* Achievements Modern UI Styles */
    .achievements-container {
        padding: 4px 6px 24px 6px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Page Header */
    .ach-header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .ach-breadcrumb {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 6px;
    }

    .ach-breadcrumb span.active {
        color: #0f172a;
        font-weight: 600;
    }

    .ach-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .ach-subtitle {
        font-size: 14px;
        color: #64748b;
        font-weight: 400;
        margin-bottom: 0;
    }

    .ach-header-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn-preview-app {
        background: #ffffff;
        color: #2563eb;
        border: 1.5px solid #2563eb;
        border-radius: 10px;
        padding: 9px 18px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        cursor: pointer;
    }

    .btn-preview-app:hover {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-create-ach {
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
    }

    .btn-create-ach:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
    }

    /* Hero Banner & Checklist Row */
    .ach-top-cards {
        margin-bottom: 28px;
    }

    .ach-hero-card {
        background: linear-gradient(135deg, #4361ee 0%, #4834d4 40%, #686de0 75%, #7c3aed 100%);
        border-radius: 18px;
        padding: 28px 32px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 32px -4px rgba(67, 97, 238, 0.35);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        min-height: 180px;
    }

    .ach-hero-card::before {
        content: '';
        position: absolute;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, rgba(255,255,255,0) 70%);
        top: -60px;
        left: -40px;
        border-radius: 50%;
        pointer-events: none;
    }

    .ach-hero-card::after {
        content: '';
        position: absolute;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(168, 85, 247, 0.25) 0%, rgba(168, 85, 247, 0) 70%);
        bottom: -100px;
        right: 40px;
        border-radius: 50%;
        pointer-events: none;
    }

    .ach-hero-left {
        display: flex;
        align-items: center;
        gap: 22px;
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .ach-hero-medal-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .ach-hero-medal-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.18);
        border: 2px solid rgba(255, 255, 255, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(8px);
        color: #ffffff;
    }

    .ach-sparkle-1 {
        position: absolute;
        top: -4px;
        right: -4px;
        color: #ffffff;
        font-size: 14px;
        animation: pulseSparkle 2s infinite ease-in-out;
    }

    .ach-sparkle-2 {
        position: absolute;
        bottom: 0px;
        left: -6px;
        color: #ffffff;
        font-size: 11px;
        animation: pulseSparkle 2.5s infinite ease-in-out 0.5s;
    }

    .ach-sparkle-3 {
        position: absolute;
        top: 2px;
        left: 2px;
        color: rgba(255, 255, 255, 0.85);
        font-size: 10px;
    }

    @keyframes pulseSparkle {
        0%, 100% { opacity: 0.6; transform: scale(0.9); }
        50% { opacity: 1; transform: scale(1.15); }
    }

    .ach-hero-info {
        flex: 1;
    }

    .ach-hero-title {
        font-size: 21px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 5px;
        letter-spacing: -0.01em;
    }

    .ach-hero-desc {
        font-size: 13.5px;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 14px;
        line-height: 1.45;
        max-width: 520px;
    }

    .ach-hero-pills {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .ach-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
        color: #ffffff;
        backdrop-filter: blur(6px);
    }

    .ach-hero-right {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        border-left: 1px solid rgba(255, 255, 255, 0.22);
        padding-left: 28px;
        min-width: 175px;
        position: relative;
        z-index: 2;
        flex-shrink: 0;
    }

    .ach-hero-counter {
        font-size: 38px;
        font-weight: 800;
        line-height: 1;
        color: #ffffff;
        margin-bottom: 2px;
        letter-spacing: -0.02em;
    }

    .ach-hero-counter-label {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 500;
        margin-bottom: 12px;
    }

    .btn-hero-cta {
        background: #ffffff;
        color: #3b82f6 !important;
        font-weight: 700;
        font-size: 12.5px;
        padding: 7px 16px;
        border-radius: 8px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        transition: all 0.2s ease;
        white-space: nowrap;
        text-decoration: none !important;
    }

    .btn-hero-cta:hover {
        background: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.18);
    }

    /* Checklist Card */
    .ach-checklist-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 22px 24px;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.03);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .ach-checklist-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
    }

    .ach-checklist-icon-wrap {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .ach-checklist-title {
        font-size: 15.5px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0;
    }

    .ach-checklist-items {
        display: flex;
        flex-direction: column;
        gap: 11px;
        margin-bottom: 16px;
    }

    .ach-step-item {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13.5px;
        font-weight: 600;
        color: #334155;
    }

    .ach-step-badge {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        font-size: 11.5px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .ach-step-badge.completed {
        background: #ecfdf5;
        color: #10b981;
    }

    .ach-checklist-footer {
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
    }

    .ach-progress-text {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 6px;
    }

    .ach-progress-bar-wrap {
        height: 6px;
        background: #f1f5f9;
        border-radius: 999px;
        overflow: hidden;
    }

    .ach-progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #6366f1);
        border-radius: 999px;
        transition: width 0.4s ease;
    }

    /* Section Header: Achievement library */
    .ach-lib-header {
        margin-bottom: 14px;
    }

    .ach-lib-title-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2px;
    }

    .ach-lib-pill {
        width: 4px;
        height: 18px;
        background: #2563eb;
        border-radius: 99px;
    }

    .ach-lib-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0;
        letter-spacing: -0.01em;
    }

    .ach-lib-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 0;
    }

    /* Filter Bar */
    .ach-filter-bar {
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

    .ach-search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 220px;
        display: flex;
        align-items: center;
    }

    .ach-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
        z-index: 10;
    }

    .ach-search-input {
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

    .ach-search-input:focus {
        background: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        outline: none;
    }

    .ach-filter-select {
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

    .ach-filter-select:focus {
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

    /* Table Toolbar Summary & Actions */
    .ach-table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
    }

    .ach-toolbar-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .ach-total-count {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .ach-per-page-select {
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

    .ach-toolbar-right {
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

    /* Table Container & DataTable styling */
    .ach-table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

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
        padding-left: 14px;
    }

    .dataTables_wrapper .dataTables_paginate {
        padding-top: 10px;
        padding-right: 14px;
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

    /* Empty State View */
    .ach-empty-state {
        text-align: center;
        padding: 56px 24px;
        background: #ffffff;
    }

    .ach-empty-icon-wrap {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px auto;
        position: relative;
    }

    .ach-empty-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .ach-empty-desc {
        font-size: 13.5px;
        color: #64748b;
        margin-bottom: 18px;
        max-width: 440px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Modal Device Preview */
    .phone-mockup-frame {
        width: 320px;
        margin: 0 auto;
        background: #0f172a;
        border-radius: 36px;
        padding: 12px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
        border: 4px solid #334155;
    }

    .phone-mockup-screen {
        background: #f8fafc;
        border-radius: 28px;
        overflow: hidden;
        min-height: 520px;
    }

    .phone-screen-header {
        background: #2563eb;
        color: #fff;
        padding: 16px 16px 12px 16px;
        text-align: center;
    }

    .phone-screen-body {
        padding: 14px;
    }

    .phone-ach-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 10px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="achievements-container">

        <!-- Top Header & Actions -->
        <div class="ach-header-section">
            <div>
                <div class="ach-breadcrumb">
                    Achievements & Hub &nbsp;/&nbsp; <span class="active">Achievements</span>
                </div>
                <h1 class="ach-title">Achievements</h1>
                <p class="ach-subtitle">Create milestones that motivate members and celebrate progress.</p>
            </div>

            <div class="ach-header-actions">
                <button type="button" class="btn-preview-app" id="btnPreviewInApp" data-bs-toggle="modal" data-bs-target="#previewInAppModal">
                    <i class="fa fa-eye"></i>
                    <span>Preview in app</span>
                </button>

                <a href="{{ route('nutritionPanel.achievements.create') }}" class="btn-create-ach">
                    <i class="fa fa-plus"></i>
                    <span>Create achievement</span>
                </a>
            </div>
        </div>

        <!-- Top Row: Hero Banner (Left) + Publishing Checklist (Right) -->
        <div class="row ach-top-cards g-3">
            <!-- Hero Card -->
            <div class="col-xl-8 col-lg-8 col-md-12">
                <div class="ach-hero-card">
                    <div class="ach-hero-left">
                        <div class="ach-hero-medal-wrap">
                            <div class="ach-hero-medal-icon">
                                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="7"></circle>
                                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                </svg>
                            </div>
                            <span class="ach-sparkle-1"><i class="fa fa-star"></i></span>
                            <span class="ach-sparkle-2"><i class="fa fa-star"></i></span>
                            <span class="ach-sparkle-3"><i class="fa fa-star-o"></i></span>
                        </div>

                        <div class="ach-hero-info">
                            <h2 class="ach-hero-title">Make progress worth celebrating.</h2>
                            <p class="ach-hero-desc">Build an achievement library that recognizes consistency, transformation and community wins.</p>
                            <div class="ach-hero-pills">
                                <span class="ach-hero-pill">
                                    <i class="fa fa-trophy"></i> Milestones
                                </span>
                                <span class="ach-hero-pill">
                                    <i class="fa fa-shield"></i> Badges
                                </span>
                                <span class="ach-hero-pill">
                                    <i class="fa fa-eye"></i> In-app visibility
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="ach-hero-right">
                        <div class="ach-hero-counter" id="hero-ach-count">{{ $totalAchievements ?? 0 }}</div>
                        <div class="ach-hero-counter-label">achievements</div>
                        <a href="{{ route('nutritionPanel.achievements.create') }}" class="btn-hero-cta">
                            Create your first achievement
                        </a>
                    </div>
                </div>
            </div>

            <!-- Publishing Checklist Card -->
            <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="ach-checklist-card">
                    <div>
                        <div class="ach-checklist-header">
                            <div class="ach-checklist-icon-wrap">
                                <i class="fa fa-check-square-o"></i>
                            </div>
                            <h3 class="ach-checklist-title">Publishing checklist</h3>
                        </div>

                        <div class="ach-checklist-items">
                            <div class="ach-step-item">
                                <span class="ach-step-badge {{ !empty($checklistSteps['created']) ? 'completed' : '' }}">
                                    @if(!empty($checklistSteps['created']))
                                        <i class="fa fa-check"></i>
                                    @else
                                        1
                                    @endif
                                </span>
                                <span>Create achievement</span>
                            </div>

                            <div class="ach-step-item">
                                <span class="ach-step-badge {{ !empty($checklistSteps['visibility']) ? 'completed' : '' }}">
                                    @if(!empty($checklistSteps['visibility']))
                                        <i class="fa fa-check"></i>
                                    @else
                                        2
                                    @endif
                                </span>
                                <span>Choose app visibility</span>
                            </div>

                            <div class="ach-step-item">
                                <span class="ach-step-badge {{ !empty($checklistSteps['published']) ? 'completed' : '' }}">
                                    @if(!empty($checklistSteps['published']))
                                        <i class="fa fa-check"></i>
                                    @else
                                        3
                                    @endif
                                </span>
                                <span>Set order and publish</span>
                            </div>
                        </div>
                    </div>

                    <div class="ach-checklist-footer">
                        <div class="ach-progress-text">
                            <span id="checklist-complete-count">{{ $completedSteps ?? 0 }}</span> of 3 complete
                        </div>
                        <div class="ach-progress-bar-wrap">
                            <div class="ach-progress-bar-fill" style="width: {{ (($completedSteps ?? 0) / 3) * 100 }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Title: Achievement library -->
        <div class="ach-lib-header">
            <div class="ach-lib-title-row">
                <span class="ach-lib-pill"></span>
                <h2 class="ach-lib-title">Achievement library</h2>
            </div>
            <p class="ach-lib-subtitle">Manage achievement visibility, status and display order.</p>
        </div>

        <!-- Filter Bar -->
        <div class="ach-filter-bar">
            <div class="ach-search-input-wrap" style="position: relative; flex: 1; min-width: 220px; display: flex; align-items: center;">
                <i class="fa fa-search ach-search-icon" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; pointer-events: none; z-index: 10;"></i>
                <input type="text" id="custom-search-input" class="form-control ach-search-input" placeholder="Search achievements..." style="padding-left: 44px !important; padding-right: 14px !important; height: 38px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc; font-size: 13.5px;">
            </div>

            <select id="filter-type" class="ach-filter-select">
                <option value="all">All types</option>
                <option value="Achievement">Achievement</option>
                <option value="Announcement">Announcement</option>
            </select>

            <select id="filter-status" class="ach-filter-select">
                <option value="all">All statuses</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>

            <select id="filter-visibility" class="ach-filter-select">
                <option value="all">All visibility</option>
                <option value="1">All User</option>
                <option value="2">Only Online User</option>
                <option value="3">Only Offline User</option>
            </select>

            <button type="button" class="btn-more-filters" id="btnMoreFilters">
                <i class="fa fa-filter"></i>
                <span>More filters</span>
                <i class="fa fa-chevron-down" style="font-size: 10px; margin-left: 2px;"></i>
            </button>
        </div>

        <!-- Table Toolbar (Summary + Action buttons) -->
        <div class="ach-table-toolbar">
            <div class="ach-toolbar-left">
                <span class="ach-total-count" id="table-record-count-text">{{ $totalAchievements ?? 0 }} achievements</span>
                <select id="custom-page-length" class="ach-per-page-select">
                    <option value="20" selected>20 per page</option>
                    <option value="50">50 per page</option>
                    <option value="75">75 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>

            <div class="ach-toolbar-right">
                <button type="button" title="Update order" class="btn-action-outline update-order">
                    <i class="fa fa-refresh"></i>
                    <span>Update order</span>
                </button>

                <button type="button" title="Change Status" class="btn-action-muted change-status" disabled>
                    <i class="fa fa-exchange"></i>
                    <span>Change status</span>
                </button>

                <button type="button" title="Delete" class="btn-action-muted dt-delete" disabled>
                    <i class="fa fa-trash"></i>
                    <span>Delete</span>
                </button>
            </div>
        </div>

        <!-- DataTable Card -->
        <div class="ach-table-card">
            <div class="table-responsive data-table-container">
                <table id="dataTable" class="table table-hover"
                       data-url="{{ route('nutritionPanel.achievements.getAchievements') }}"
                       data-change-status-url="{{ route('nutritionPanel.achievements.changeStatus') }}"
                       data-destroy-url="{{ route('nutritionPanel.achievements.destroy') }}"
                       data-update-order-url="{{ route('nutritionPanel.achievements.updateOrder') }}">
                    <thead>
                        <tr>
                            <th class="checkbox-column" style="width: 36px;"> # </th>
                            <th>TITLE</th>
                            <th>TYPE</th>
                            <th>IN APP SHOW</th>
                            <th>SHOW THIS ACHIEVEMENT</th>
                            <th style="width: 90px;">ORDER</th>
                            <th style="width: 90px;">STATUS</th>
                            <th class="text-end" style="width: 80px;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <!-- Empty State Display (Visible if 0 records) -->
            <div id="empty-state-view" class="ach-empty-state" style="display: none;">
                <div class="ach-empty-icon-wrap">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="7"></circle>
                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                    </svg>
                </div>
                <h3 class="ach-empty-title">No achievements yet</h3>
                <p class="ach-empty-desc">Create your first achievement to start celebrating member progress.</p>
                <a href="{{ route('nutritionPanel.achievements.create') }}" class="btn-create-ach">
                    <i class="fa fa-plus"></i>
                    <span>Create achievement</span>
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Modal: Preview In App -->
<div class="modal fade" id="previewInAppModal" tabindex="-1" aria-labelledby="previewInAppModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; background: #ffffff;">
            <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 18px 24px;">
                <h5 class="modal-title fw-bold" id="previewInAppModalLabel">
                    <i class="fa fa-mobile text-primary me-2 fs-5"></i> In-App Mobile Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="phone-mockup-frame">
                    <div class="phone-mockup-screen">
                        <div class="phone-screen-header">
                            <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 11px; opacity: 0.9;">
                                <span>9:41</span>
                                <div>
                                    <i class="fa fa-signal me-1"></i>
                                    <i class="fa fa-wifi me-1"></i>
                                    <i class="fa fa-battery-full"></i>
                                </div>
                            </div>
                            <div class="fw-bold" style="font-size: 15px;">Club Achievements</div>
                            <div style="font-size: 11px; opacity: 0.85;">Celebrate milestones & wins</div>
                        </div>
                        <div class="phone-screen-body text-start">
                            <div class="phone-ach-card">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #eff6ff; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                                    <i class="fa fa-trophy"></i>
                                </div>
                                <div>
                                    <div class="fw-bold" style="font-size: 13px; color: #0f172a;">7-Day Consistency Club</div>
                                    <div style="font-size: 11px; color: #64748b;">Completed workouts 7 days straight</div>
                                </div>
                            </div>
                            <div class="phone-ach-card">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #fef3c7; display: flex; align-items: center; justify-content: center; color: #d97706;">
                                    <i class="fa fa-star"></i>
                                </div>
                                <div>
                                    <div class="fw-bold" style="font-size: 13px; color: #0f172a;">10kg Transformation Win</div>
                                    <div style="font-size: 11px; color: #64748b;">Celebrated in community feed</div>
                                </div>
                            </div>
                            <div class="phone-ach-card">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #ecfdf5; display: flex; align-items: center; justify-content: center; color: #059669;">
                                    <i class="fa fa-shield"></i>
                                </div>
                                <div>
                                    <div class="fw-bold" style="font-size: 13px; color: #0f172a;">Hydration Champion</div>
                                    <div style="font-size: 11px; color: #64748b;">Daily intake milestone met</div>
                                </div>
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
<script src="{{ asset('admin-assets/js/achievements/view.js') }}"></script>
@endpush
