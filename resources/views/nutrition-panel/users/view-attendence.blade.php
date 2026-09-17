@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'View Attendance | ' . ($user->name ?? 'User') . ' | ' . __('language.page_main_title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style type="text/css">
    /* Global Scoped Page Styles */
    :root {
        --fcc-primary: #3b46f1;
        --fcc-primary-light: #eff2fe;
        --fcc-text-dark: #0f172a;
        --fcc-muted: #64748b;
        --fcc-border: #edf2f7;
        --fcc-card-bg: #ffffff;
        --fcc-bg: #f8fafc;
        --fcc-green: #10b981;
        --fcc-green-light: #dcfce7;
        --fcc-green-pill: #86efac;
        --fcc-green-pill-text: #14532d;
    }

    .fcc-attendance-page-wrap {
        font-family: 'Outfit', sans-serif !important;
        color: var(--fcc-text-dark);
        padding: 6px 4px 40px 4px;
    }

    /* 1. Breadcrumbs */
    .fcc-breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--fcc-muted);
        margin-bottom: 12px;
    }
    .fcc-breadcrumb-nav a {
        color: var(--fcc-muted);
        text-decoration: none;
        transition: color 0.15s ease;
    }
    .fcc-breadcrumb-nav a:hover {
        color: var(--fcc-primary);
    }
    .fcc-breadcrumb-sep {
        color: #cbd5e1;
        font-size: 11px;
    }
    .fcc-breadcrumb-active {
        color: var(--fcc-primary);
        font-weight: 600;
    }

    /* 2. Header */
    .fcc-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }
    .fcc-page-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--fcc-text-dark);
        margin: 0;
        line-height: 1.2;
    }
    .fcc-page-subtitle {
        font-size: 13.5px;
        color: var(--fcc-muted);
        margin: 4px 0 0 0;
    }
    .fcc-header-btns {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .fcc-btn-export {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        color: #334155;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 18px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        transition: all 0.15s ease;
    }
    .fcc-btn-export:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    .fcc-btn-export svg {
        width: 15px;
        height: 15px;
        color: var(--fcc-primary);
    }
    .fcc-btn-mark-attendance {
        background: var(--fcc-primary);
        border: 1.5px solid var(--fcc-primary);
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 20px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(59, 70, 241, 0.25);
        transition: all 0.15s ease;
    }
    .fcc-btn-mark-attendance:hover {
        background: #2b35d8;
        border-color: #2b35d8;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(59, 70, 241, 0.35);
    }

    /* 3. Sub-Navigation Tabs Ribbon */
    .fcc-member-nav-tabs {
        display: flex;
        align-items: center;
        gap: 28px;
        border-bottom: 1.5px solid #e2e8f0;
        margin-bottom: 24px;
        overflow-x: auto;
        padding-bottom: 0;
    }
    .fcc-tab-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0 2px 12px 2px;
        font-size: 13.5px;
        font-weight: 500;
        color: #64748b;
        text-decoration: none;
        position: relative;
        white-space: nowrap;
        transition: all 0.15s ease;
    }
    .fcc-tab-link svg {
        width: 16px;
        height: 16px;
        color: #94a3b8;
        transition: all 0.15s ease;
    }
    .fcc-tab-link:hover {
        color: var(--fcc-primary);
    }
    .fcc-tab-link:hover svg {
        color: var(--fcc-primary);
    }
    .fcc-tab-link.active {
        color: var(--fcc-primary);
        font-weight: 600;
    }
    .fcc-tab-link.active svg {
        color: var(--fcc-primary);
    }
    .fcc-tab-link.active::after {
        content: '';
        position: absolute;
        bottom: -1.5px;
        left: 0;
        right: 0;
        height: 2.5px;
        background: var(--fcc-primary);
        border-radius: 2px 2px 0 0;
    }

    /* 4. Metric Cards */
    .fcc-metric-cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 991px) {
        .fcc-metric-cards-grid {
            grid-template-columns: 1fr;
        }
    }
    .fcc-metric-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .fcc-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
    }
    .fcc-metric-icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .fcc-metric-icon-circle.blue {
        background: #eff2fe;
        color: #3b46f1;
    }
    .fcc-metric-icon-circle.green {
        background: #dcfce7;
        color: #16a34a;
    }
    .fcc-metric-icon-circle.purple {
        background: #f3e8ff;
        color: #9333ea;
    }
    .fcc-metric-icon-circle svg {
        width: 22px;
        height: 22px;
    }
    .fcc-metric-content {
        flex: 1;
        min-width: 0;
    }
    .fcc-metric-label {
        font-size: 12.5px;
        color: var(--fcc-muted);
        font-weight: 500;
        margin-bottom: 2px;
    }
    .fcc-metric-value {
        font-size: 22px;
        font-weight: 700;
        color: var(--fcc-text-dark);
        line-height: 1.2;
    }
    .fcc-metric-subtext {
        font-size: 11.5px;
        color: #94a3b8;
        margin-top: 3px;
        font-weight: 500;
    }

    /* 5. Calendar Main Card */
    .fcc-white-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        margin-bottom: 24px;
    }
    .fcc-card-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }
    .fcc-card-heading {
        font-size: 17px;
        font-weight: 700;
        color: var(--fcc-text-dark);
        margin: 0;
    }
    .fcc-card-subheading {
        font-size: 12.5px;
        color: var(--fcc-muted);
        margin-bottom: 0;
        margin-top: 2px;
    }
    .fcc-calendar-controls {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    /* Year Selector */
    .fcc-year-select {
        height: 36px;
        padding: 0 30px 0 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 9px;
        font-size: 12.5px;
        font-weight: 600;
        font-family: 'Outfit', sans-serif;
        color: #334155;
        background: #ffffff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") no-repeat right 10px center/10px 10px;
        cursor: pointer;
        appearance: none;
        transition: all 0.15s ease;
    }
    .fcc-year-select:focus {
        border-color: var(--fcc-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.1);
    }

    /* Half-year Toggle */
    .fcc-toggle-btn-group {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        padding: 3px;
        border-radius: 9px;
    }
    .fcc-toggle-btn {
        padding: 5px 14px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        background: transparent;
        color: #64748b;
        border-radius: 7px;
        cursor: pointer;
        transition: all 0.15s ease;
        font-family: 'Outfit', sans-serif;
    }
    .fcc-toggle-btn.active {
        background: #3b46f1;
        color: #ffffff;
        box-shadow: 0 1px 3px rgba(59, 70, 241, 0.25);
    }

    /* Legend */
    .fcc-cal-legend {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 12px;
        font-weight: 500;
        color: #64748b;
    }
    .fcc-cal-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .fcc-legend-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        display: inline-block;
    }
    .fcc-legend-dot.present {
        background: #10b981;
    }
    .fcc-legend-dot.absent {
        background: #ef4444;
    }
    .fcc-legend-dot.blank {
        background: #ffffff;
        border: 1.5px solid #94a3b8;
    }

    /* 6. Months Grid Layout */
    .fcc-months-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-top: 10px;
    }
    @media (max-width: 1100px) {
        .fcc-months-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 767px) {
        .fcc-months-grid {
            grid-template-columns: 1fr;
        }
    }

    .fcc-month-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px 16px 16px;
        transition: all 0.15s ease;
    }
    .fcc-month-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
    }
    .fcc-month-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
    }
    .fcc-month-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .fcc-month-present-badge {
        font-size: 11.5px;
        font-weight: 600;
        color: #64748b;
    }
    .fcc-month-present-badge.has-present {
        color: #16a34a;
    }

    /* Mini Calendar Grid */
    .fcc-cal-days-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        text-align: center;
        margin-bottom: 6px;
    }
    .fcc-cal-day-name {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        padding: 2px 0;
    }

    .fcc-cal-days-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        text-align: center;
    }
    .fcc-day-cell {
        aspect-ratio: 1;
        height: 28px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 500;
        transition: all 0.15s ease;
    }
    .fcc-day-cell.empty {
        background: transparent;
        pointer-events: none;
    }
    .fcc-day-cell.blank {
        background: #f8fafc;
        color: #94a3b8;
    }
    .fcc-day-cell.blank:hover {
        background: #f1f5f9;
        color: #64748b;
    }
    .fcc-day-cell.present {
        background: #86efac;
        color: #14532d;
        font-weight: 700;
        box-shadow: 0 1px 2px rgba(16, 185, 129, 0.2);
        cursor: pointer;
    }
    .fcc-day-cell.present:hover {
        background: #4ade80;
        transform: scale(1.08);
    }
    .fcc-day-cell.absent {
        background: #fee2e2;
        color: #b91c1c;
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(239, 68, 68, 0.1);
        cursor: pointer;
    }
    .fcc-day-cell.absent:hover {
        background: #fca5a5;
        color: #7f1d1d;
        transform: scale(1.08);
    }
    .fcc-day-cell.today-cell {
        border: 1.5px solid #3b46f1;
        font-weight: 700;
    }

    /* 7. Bottom Recent Check-ins Bar */
    .fcc-bottom-recent-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        padding-top: 18px;
        margin-top: 18px;
        border-top: 1px solid #f1f5f9;
    }
    .fcc-recent-checkins-group {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .fcc-recent-title {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        margin-right: 4px;
    }
    .fcc-recent-pill {
        background: #dcfce7;
        color: #15803d;
        font-size: 12px;
        font-weight: 600;
        padding: 3px 12px;
        border-radius: 8px;
        border: 1px solid #bbf7d0;
        display: inline-flex;
        align-items: center;
    }
    .fcc-view-log-link {
        color: var(--fcc-primary);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .fcc-view-log-link:hover {
        color: #2b35d8;
        transform: translateX(2px);
    }
    .fcc-view-log-link svg {
        width: 14px;
        height: 14px;
    }
</style>
@endpush

@section('content')
@php
    $userEncryptedId = ev($user->id);
@endphp

<div class="fcc-attendance-page-wrap">

    <!-- 1. Breadcrumbs -->
    <div class="fcc-breadcrumb-nav">
        <a href="{{ route('nutritionPanel.users.index') }}">User management</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <a href="{{ route('nutritionPanel.users.index') }}">All users</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <span class="fcc-breadcrumb-active">View attendance</span>
    </div>

    <!-- 2. Header -->
    <div class="fcc-page-header">
        <div>
            <h1 class="fcc-page-title">Attendance history</h1>
            <p class="fcc-page-subtitle">{{ $user->name ?? 'Member' }} · Yearly check-ins and attendance consistency</p>
        </div>
        <div class="fcc-header-btns">
            <button type="button" class="btn fcc-btn-export" id="fccExportBtn">
                <i data-feather="download"></i>
                <span>Export</span>
            </button>
            <button type="button" class="btn fcc-btn-mark-attendance" data-bs-toggle="modal" data-bs-target="#markAttendanceModal" data-toggle="modal" data-target="#markAttendanceModal">
                <i data-feather="plus" style="width: 15px; height: 15px;"></i>
                <span>Mark attendance</span>
            </button>
        </div>
    </div>

    <!-- 3. Sub-Navigation Tabs Ribbon -->
    <div class="fcc-member-nav-tabs">
        <a href="{{ route('nutritionPanel.users.viewWeights', ['id' => $userEncryptedId]) }}" class="fcc-tab-link">
            <i data-feather="activity"></i>
            <span>Weight</span>
        </a>
        <a href="{{ route('nutritionPanel.users.viewAttendance', ['id' => $userEncryptedId]) }}" class="fcc-tab-link active">
            <i data-feather="calendar"></i>
            <span>Attendance</span>
        </a>
        <a href="{{ route('nutritionPanel.manual-attendances.manual-attendance', ['id' => $userEncryptedId]) }}" class="fcc-tab-link">
            <i data-feather="check-square"></i>
            <span>Manual attendance</span>
        </a>
        <a href="{{ route('nutritionPanel.track-shake.index', ['id' => $userEncryptedId]) }}" class="fcc-tab-link">
            <i data-feather="coffee"></i>
            <span>Shake tracking</span>
        </a>
        <a href="{{ route('nutritionPanel.orders.index', ['id' => $userEncryptedId]) }}" class="fcc-tab-link">
            <i data-feather="shopping-cart"></i>
            <span>Purchases</span>
        </a>
    </div>

    <!-- 4. Top 3 Metric Cards Grid -->
    <div class="fcc-metric-cards-grid">
        <!-- Card 1: Check-ins -->
        <div class="fcc-metric-card">
            <div class="fcc-metric-icon-circle blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div class="fcc-metric-content">
                <div class="fcc-metric-label">Check-ins</div>
                <div class="fcc-metric-value" id="fccMetricCheckinsVal">
                    {{ $totalCheckIns > 0 ? $totalCheckIns : 0 }}
                </div>
                <div class="fcc-metric-subtext" id="fccMetricCheckinsSubtext">
                    Jan–Dec {{ $year }}
                </div>
            </div>
        </div>

        <!-- Card 2: Longest Streak -->
        <div class="fcc-metric-card">
            <div class="fcc-metric-icon-circle green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path>
                </svg>
            </div>
            <div class="fcc-metric-content">
                <div class="fcc-metric-label">Longest streak</div>
                <div class="fcc-metric-value">
                    {{ $longestStreakDays > 0 ? $longestStreakDays . ' days' : '0 days' }}
                </div>
                <div class="fcc-metric-subtext">
                    {{ !empty($longestStreakRange) ? $longestStreakRange : 'No streak recorded' }}
                </div>
            </div>
        </div>

        <!-- Card 3: Last check-in -->
        <div class="fcc-metric-card">
            <div class="fcc-metric-icon-circle purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div class="fcc-metric-content">
                <div class="fcc-metric-label">Last check-in</div>
                <div class="fcc-metric-value">
                    {{ !empty($lastCheckInDate) ? $lastCheckInDate : 'No check-in' }}
                </div>
                <div class="fcc-metric-subtext">Recorded attendance</div>
            </div>
        </div>
    </div>

    <!-- 5. Attendance Calendar Main Card -->
    <div class="fcc-white-card">
        <div class="fcc-card-section-header">
            <div>
                <h3 class="fcc-card-heading">Attendance calendar</h3>
                <p class="fcc-card-subheading">Select a year and review monthly check-ins</p>
            </div>
            <div class="fcc-calendar-controls">
                <!-- Year Select Dropdown -->
                <select id="yearSelect" class="fcc-year-select">
                    @for ($y = date('Y') + 1; $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <!-- Jan-Jun / Jul-Dec Half Year Switcher -->
                <div class="fcc-toggle-btn-group">
                    <button type="button" class="fcc-toggle-btn active" data-half-view="h1">Jan-Jun</button>
                    <button type="button" class="fcc-toggle-btn" data-half-view="h2">Jul-Dec</button>
                </div>

                <!-- Legend Indicator -->
                <div class="fcc-cal-legend">
                    <div class="fcc-cal-legend-item">
                        <span class="fcc-legend-dot present"></span>
                        <span>Present</span>
                    </div>
                    <div class="fcc-cal-legend-item">
                        <span class="fcc-legend-dot absent"></span>
                        <span>Absent</span>
                    </div>
                    <div class="fcc-cal-legend-item">
                        <span class="fcc-legend-dot blank"></span>
                        <span>No check-in</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Months Grid (12 months rendered, filtered dynamically by H1 / H2) -->
        <div class="fcc-months-grid">
            @for ($m = 1; $m <= 12; $m++)
                @php
                    $monthName = date('F', mktime(0, 0, 0, $m, 10));
                    $firstDayOfWeek = Carbon\Carbon::createFromDate($year, $m, 1)->dayOfWeek;
                    $daysInMonth = Carbon\Carbon::createFromDate($year, $m, 1)->daysInMonth;
                    $halfClass = $m <= 6 ? 'fcc-half-h1' : 'fcc-half-h2';
                    $isH2Hidden = $m > 6 ? 'display: none;' : '';

                    // Pre-calculate present count for this month
                    $monthPresentCount = 0;
                    for ($d = 1; $d <= $daysInMonth; $d++) {
                        $testDate = Carbon\Carbon::createFromDate($year, $m, $d)->format('Y-m-d');
                        $att = $attendances->get($testDate);
                        if ($att && $att->type == 2) {
                            $monthPresentCount++;
                        }
                    }
                @endphp

                <div class="fcc-month-card {{ $halfClass }}" style="{{ $isH2Hidden }}" data-month="{{ $m }}">
                    <div class="fcc-month-header">
                        <div class="fcc-month-title">{{ $monthName }} {{ $year }}</div>
                        <div class="fcc-month-present-badge {{ $monthPresentCount > 0 ? 'has-present' : '' }}">
                            {{ $monthPresentCount }} present
                        </div>
                    </div>

                    <!-- Day of week header -->
                    <div class="fcc-cal-days-header">
                        <div class="fcc-cal-day-name">S</div>
                        <div class="fcc-cal-day-name">M</div>
                        <div class="fcc-cal-day-name">T</div>
                        <div class="fcc-cal-day-name">W</div>
                        <div class="fcc-cal-day-name">T</div>
                        <div class="fcc-cal-day-name">F</div>
                        <div class="fcc-cal-day-name">S</div>
                    </div>

                    <!-- Days Grid -->
                    <div class="fcc-cal-days-grid">
                        {{-- Empty leading slots --}}
                        @for ($i = 0; $i < $firstDayOfWeek; $i++)
                            <div class="fcc-day-cell empty"></div>
                        @endfor

                        {{-- Month Days --}}
                        @for ($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $currentDate = Carbon\Carbon::createFromDate($year, $m, $d)->format('Y-m-d');
                                $today = date('Y-m-d');
                                $attendance = $attendances->get($currentDate);
                                $isPresent = ($attendance && $attendance->type == 2);
                                $isAbsentExplicit = ($attendance && $attendance->type == 1);
                                $isPastOrToday = ($currentDate <= $today);
                                $isAbsent = !$isPresent && ($isAbsentExplicit || $isPastOrToday);
                                $isToday = ($currentDate === $today);

                                if ($isPresent) {
                                    $cellStatusClass = 'present';
                                    $tooltipText = 'Present on ' . date('d M Y', strtotime($currentDate)) . (!empty($attendance->weight) ? ' (Weight: ' . $attendance->weight . ' kg)' : '');
                                } elseif ($isAbsent) {
                                    $cellStatusClass = 'absent';
                                    $tooltipText = 'Absent on ' . date('d M Y', strtotime($currentDate));
                                } else {
                                    $cellStatusClass = 'blank';
                                    $tooltipText = date('d M Y', strtotime($currentDate)) . ' (Upcoming)';
                                }
                            @endphp

                            <div class="fcc-day-cell {{ $cellStatusClass }} {{ $isToday ? 'today-cell' : '' }}" title="{{ $tooltipText }}">
                                {{ $d }}
                            </div>
                        @endfor
                    </div>
                </div>
            @endfor
        </div>

        <!-- 7. Bottom Recent Check-ins Bar -->
        <div class="fcc-bottom-recent-bar">
            <div class="fcc-recent-checkins-group">
                <span class="fcc-recent-title">Recent check-ins</span>
                @if(!empty($recentCheckIns) && count($recentCheckIns) > 0)
                    @foreach($recentCheckIns as $r)
                        <span class="fcc-recent-pill" title="Recorded: {{ $r->full }}">{{ $r->formatted }}</span>
                    @endforeach
                @else
                    <span class="text-muted" style="font-size: 12.5px;">No recent check-ins recorded</span>
                @endif
            </div>

            <a href="{{ route('nutritionPanel.manual-attendances.manual-attendance', ['id' => $userEncryptedId]) }}" class="fcc-view-log-link">
                <span>View attendance log</span>
                <i data-feather="arrow-right"></i>
            </a>
        </div>
    </div>

</div>

<!-- Mark Attendance Modal -->
<div class="modal fade" id="markAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="markAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; font-family: 'Outfit', sans-serif;">
            <div class="modal-header py-3 px-4" style="border-bottom: 1px solid #edf2f7; background: #ffffff;">
                <h5 class="modal-title fw-bold" id="markAttendanceModalLabel" style="color: #0f172a; font-size: 16.5px;">Mark Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('nutritionPanel.manual-attendances.addManualAttendance') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 13px; color: #334155;">Attendance Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required style="border-radius: 9px; height: 42px; border: 1.5px solid #e2e8f0; font-size: 13.5px;" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 13px; color: #334155;">Weight (kg) <span class="text-muted fw-normal">(Optional)</span></label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="weight" class="form-control" placeholder="e.g. 74.5" style="border-radius: 9px 0 0 9px; height: 42px; border: 1.5px solid #e2e8f0; font-size: 13.5px;" />
                            <span class="input-group-text fw-bold" style="border-radius: 0 9px 9px 0; background: #f8fafc; border: 1.5px solid #e2e8f0; border-left: none; color: #64748b;">kg</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-3 px-4" style="border-top: 1px solid #edf2f7; background: #f8fafc;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-dismiss="modal" style="border-radius: 9px; font-weight: 600; font-size: 13px; padding: 8px 16px;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 9px; font-weight: 600; font-size: 13px; padding: 8px 20px; background: #3b46f1; border-color: #3b46f1;">Save Attendance</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Year Change Handler
        $('#yearSelect').on('change', function() {
            var selectedYear = $(this).val();
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('year', selectedYear);
            window.location.href = currentUrl.toString();
        });

        // Jan-Jun (H1) vs Jul-Dec (H2) Switcher
        $('[data-half-view]').on('click', function(e) {
            e.preventDefault();
            $('[data-half-view]').removeClass('active');
            $(this).addClass('active');

            var view = $(this).data('half-view');
            if (view === 'h1') {
                $('.fcc-half-h2').fadeOut(120, function() {
                    $('.fcc-half-h1').fadeIn(150);
                });
                $('#fccMetricCheckinsSubtext').text('Jan–Jun ' + $('#yearSelect').val());
            } else {
                $('.fcc-half-h1').fadeOut(120, function() {
                    $('.fcc-half-h2').fadeIn(150);
                });
                $('#fccMetricCheckinsSubtext').text('Jul–Dec ' + $('#yearSelect').val());
            }
        });

        // Export button handler
        $('#fccExportBtn').on('click', function() {
            window.print();
        });
    });
</script>
@endpush
