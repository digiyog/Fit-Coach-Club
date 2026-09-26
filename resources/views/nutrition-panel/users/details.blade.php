@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Member Profile | ' . ($user->name ?? 'User') . ' | ' . __('language.page_main_title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style type="text/css">
    /* Global Scoped Page Styles */
    :root {
        --fcc-primary: #3b46f1;
        --fcc-primary-hover: #2b35d8;
        --fcc-text-dark: #0f172a;
        --fcc-muted: #64748b;
        --fcc-border: #edf2f7;
        --fcc-card-bg: #ffffff;
        --fcc-bg: #f8fafc;
        --fcc-green: #10b981;
        --fcc-green-light: #dcfce7;
        --fcc-purple: #9333ea;
        --fcc-purple-light: #f3e8ff;
    }

    .fcc-profile-page-wrap {
        font-family: 'Outfit', sans-serif !important;
        color: var(--fcc-text-dark);
        padding: 6px 4px 40px 4px;
    }

    /* 1. Breadcrumbs */
    .fcc-breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 8px;
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
        font-size: 12px;
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
        margin-bottom: 22px;
    }
    .fcc-page-title {
        font-size: 26px;
        font-weight: 700;
        color: var(--fcc-text-dark);
        margin: 0;
        line-height: 1.2;
    }
    .fcc-page-subtitle {
        font-size: 13.5px;
        color: var(--fcc-muted);
        margin: 5px 0 0 0;
    }
    .fcc-header-btns {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .fcc-btn-more-actions {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        color: #334155;
        font-weight: 600;
        font-size: 13.5px;
        padding: 8px 18px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .fcc-btn-more-actions:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    .fcc-btn-edit-member {
        background: var(--fcc-primary);
        border: 1.5px solid var(--fcc-primary);
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13.5px;
        padding: 8px 20px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(59, 70, 241, 0.25);
        transition: all 0.15s ease;
        text-decoration: none;
    }
    .fcc-btn-edit-member:hover {
        background: var(--fcc-primary-hover);
        border-color: var(--fcc-primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(59, 70, 241, 0.35);
    }
    .fcc-btn-edit-member svg {
        width: 15px;
        height: 15px;
    }

    /* 3. Sub-Navigation Tabs Ribbon */
    .fcc-member-nav-tabs {
        display: flex;
        align-items: center;
        gap: 32px;
        border-bottom: 1.5px solid #e2e8f0;
        margin-bottom: 24px;
        overflow-x: auto;
        padding-bottom: 0;
    }
    .fcc-tab-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0 2px 14px 2px;
        font-size: 14px;
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

    /* 4. Layout 2-Column Grid */
    .fcc-profile-main-grid {
        display: grid;
        grid-template-columns: 290px 1fr;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 991px) {
        .fcc-profile-main-grid {
            grid-template-columns: 1fr;
        }
    }

    /* 5. Left Sidebar Profile Card */
    .fcc-sidebar-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 24px 22px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    }
    .fcc-avatar-box {
        width: 100%;
        aspect-ratio: 1;
        max-width: 240px;
        margin: 0 auto 18px auto;
        border-radius: 16px;
        overflow: hidden;
        background: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
    }
    .fcc-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .fcc-avatar-initials {
        font-size: 52px;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: 2px;
    }
    .fcc-profile-name {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 10px 0;
    }
    .fcc-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .fcc-status-badge.active {
        background: #dcfce7;
        color: #166534;
    }
    .fcc-status-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
    }
    .fcc-status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }
    .fcc-status-badge.active .fcc-status-dot { background: #16a34a; }
    .fcc-status-badge.inactive .fcc-status-dot { background: #dc2626; }

    .fcc-profile-tags {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 12px;
        flex-wrap: wrap;
    }
    .fcc-profile-pill {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .fcc-profile-pill.user-type {
        background: #eff2fe;
        color: #3b46f1;
    }
    .fcc-profile-pill.user-state {
        background: #f1f5f9;
        color: #475569;
    }

    .fcc-sidebar-contacts-list {
        list-style: none;
        padding: 0;
        margin: 22px 0 0 0;
        border-top: 1px solid #f1f5f9;
        padding-top: 20px;
    }
    .fcc-sidebar-contacts-item {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13px;
        color: #475569;
        margin-bottom: 14px;
        word-break: break-all;
    }
    .fcc-sidebar-contacts-item:last-child {
        margin-bottom: 0;
    }
    .fcc-sidebar-contacts-item svg {
        width: 16px;
        height: 16px;
        color: #64748b;
        flex-shrink: 0;
    }

    .fcc-sidebar-coach-section {
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid #f1f5f9;
    }
    .fcc-coach-label {
        font-size: 12px;
        font-weight: 500;
        color: #94a3b8;
        margin-bottom: 3px;
    }
    .fcc-coach-name {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    /* 6. Right Content Sections */
    .fcc-right-content {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    /* Top 4 Metric Cards */
    .fcc-top-metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    @media (max-width: 1200px) {
        .fcc-top-metrics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 575px) {
        .fcc-top-metrics-grid {
            grid-template-columns: 1fr;
        }
    }

    .fcc-metric-box {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 18px 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .fcc-metric-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
    }
    .fcc-metric-box-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .fcc-metric-box-icon.blue { background: #eff2fe; color: #3b46f1; }
    .fcc-metric-box-icon.purple { background: #f3e8ff; color: #9333ea; }
    .fcc-metric-box-icon.green { background: #dcfce7; color: #16a34a; }
    .fcc-metric-box-icon.orange { background: #ffedd5; color: #ea580c; }
    .fcc-metric-box-icon svg {
        width: 20px;
        height: 20px;
    }
    .fcc-metric-box-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 2px;
    }
    .fcc-metric-box-val {
        font-size: 21px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }

    /* Membership Overview Card */
    .fcc-overview-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    }
    .fcc-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .fcc-card-sub {
        font-size: 12.5px;
        color: #64748b;
        margin: 3px 0 0 0;
    }
    .fcc-plan-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 18px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .fcc-plan-banner-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .fcc-plan-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #eff2fe;
        color: #3b46f1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .fcc-plan-icon-wrap svg {
        width: 22px;
        height: 22px;
    }
    .fcc-plan-heading {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .fcc-plan-remaining {
        font-size: 12.5px;
        color: #64748b;
        margin-top: 2px;
    }
    .fcc-plan-status-tag {
        font-size: 12.5px;
        font-weight: 500;
        color: #94a3b8;
    }

    /* Progress Bar */
    .fcc-plan-progress-track {
        width: 100%;
        height: 6px;
        background: #f1f5f9;
        border-radius: 10px;
        margin: 16px 0 20px 0;
        overflow: hidden;
    }
    .fcc-plan-progress-fill {
        height: 100%;
        background: #3b46f1;
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    /* Overview Footer Stats Row */
    .fcc-overview-footer-stats {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        align-items: center;
        gap: 16px;
        padding-top: 18px;
        border-top: 1px solid #f1f5f9;
    }
    @media (max-width: 575px) {
        .fcc-overview-footer-stats {
            grid-template-columns: 1fr;
        }
    }
    .fcc-stat-block-label {
        font-size: 12px;
        color: #94a3b8;
        font-weight: 500;
        margin-bottom: 2px;
    }
    .fcc-stat-block-val {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }
    .fcc-due-amount-display {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    .fcc-due-icon-wrap {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: #ffedd5;
        color: #ea580c;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .fcc-due-icon-wrap svg {
        width: 18px;
        height: 18px;
    }

    /* 7. Two Column Details Grid */
    .fcc-details-two-cols {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 22px;
    }
    @media (max-width: 991px) {
        .fcc-details-two-cols {
            grid-template-columns: 1fr;
        }
    }

    .fcc-detail-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .fcc-key-value-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 20px;
    }
    .fcc-kv-row {
        display: grid;
        grid-template-columns: 140px 1fr;
        align-items: center;
        font-size: 13.5px;
    }
    .fcc-kv-key {
        color: #64748b;
        font-weight: 500;
    }
    .fcc-kv-val {
        color: #0f172a;
        font-weight: 600;
        text-align: left;
    }

    /* Weight Progress Highlight Box */
    .fcc-weight-progress-box {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 20px;
    }
    .fcc-weight-progress-box.orange {
        background: #fff7ed;
        border-color: #fed7aa;
    }
    .fcc-weight-progress-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #dcfce7;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .fcc-weight-progress-box.orange .fcc-weight-progress-icon {
        background: #ffedd5;
        color: #ea580c;
    }
    .fcc-weight-progress-icon svg {
        width: 18px;
        height: 18px;
    }
    .fcc-wp-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #15803d;
        margin: 0;
    }
    .fcc-weight-progress-box.orange .fcc-wp-title {
        color: #c2410c;
    }
    .fcc-wp-sub {
        font-size: 12px;
        color: #166534;
        margin: 2px 0 0 0;
    }
    .fcc-weight-progress-box.orange .fcc-wp-sub {
        color: #9a3412;
    }
</style>
@endpush

@section('content')
@php
    $userEncryptedId = ev($user->id);

    // Profile Image
    $profileImage = null;
    if (!empty($user->profile_image)) {
        if (\Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path_thumb') . $user->profile_image)) {
            $profileImage = get_image_url(config('constants.users.image_path_thumb'), $user->profile_image);
        } elseif (\Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path') . $user->profile_image)) {
            $profileImage = get_image_url(config('constants.users.image_path'), $user->profile_image);
        } else {
            $profileImage = get_image_url(config('constants.users.image_path'), $user->profile_image);
        }
    }

    $initials = '';
    $words = explode(' ', trim($user->name ?? 'User'));
    foreach ($words as $w) {
        if (!empty($w)) $initials .= strtoupper(substr($w, 0, 1));
    }
    $initials = substr($initials, 0, 2) ?: 'U';

    $planName = !empty($user->meal_type->name) ? $user->meal_type->name : (!empty($user->product_type->name) ? $user->product_type->name : 'Basic Weight Loss Plan');
    $remainingDays = (int)($user->days ?? 0);
    $progressPercent = min(100, max(15, ($remainingDays / 30) * 100));

    $genderText = match((string)$user->gender) {
        '1' => 'Male',
        '2' => 'Female',
        default => ($user->gender ?: 'N/A'),
    };
@endphp

<div class="fcc-profile-page-wrap">

    <!-- 1. Breadcrumbs -->
    <div class="fcc-breadcrumb-nav">
        <a href="{{ route('nutritionPanel.users.index') }}">User management</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <a href="{{ route('nutritionPanel.users.index') }}">All users</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <span class="fcc-breadcrumb-active">Member profile</span>
    </div>

    <!-- 2. Header -->
    <div class="fcc-page-header">
        <div>
            <h1 class="fcc-page-title">Member profile</h1>
            <p class="fcc-page-subtitle"><strong style="color: #0f172a; font-weight: 700;">{{ $user->name ?? 'Member' }}</strong> · Account, coaching and progress details</p>
        </div>
        <div class="fcc-header-btns">
            <!-- More Actions Dropdown -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn fcc-btn-more-actions dropdown-toggle" data-bs-toggle="dropdown" data-toggle="dropdown" aria-expanded="false">
                    <span>··· More actions</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius: 12px; min-width: 230px; padding: 6px; font-family: 'Outfit', sans-serif; white-space: nowrap;">
                    <a class="dropdown-item py-2 px-3 rounded-2" href="{{ route('nutritionPanel.users.viewWeights', ['id' => $userEncryptedId]) }}">
                        <i class="fa fa-line-chart me-2 text-muted"></i> View Weight
                    </a>
                    <a class="dropdown-item py-2 px-3 rounded-2" href="{{ route('nutritionPanel.users.viewAttendance', ['id' => $userEncryptedId]) }}">
                        <i class="fa fa-calendar-check-o me-2 text-muted"></i> View Attendance
                    </a>
                    <a class="dropdown-item py-2 px-3 rounded-2" href="{{ route('nutritionPanel.manual-attendances.manual-attendance', ['id' => $userEncryptedId]) }}">
                        <i class="fa fa-clock-o me-2 text-muted"></i> Manual Attendance
                    </a>
                    <a class="dropdown-item py-2 px-3 rounded-2" href="{{ route('nutritionPanel.track-shake.index', ['id' => $userEncryptedId]) }}">
                        <i class="fa fa-coffee me-2 text-muted"></i> Track Shake
                    </a>
                    <a class="dropdown-item py-2 px-3 rounded-2" href="{{ route('nutritionPanel.orders.index', ['id' => $userEncryptedId]) }}">
                        <i class="fa fa-shopping-cart me-2 text-muted"></i> Purchases
                    </a>
                </div>
            </div>

            <!-- Edit Member Button -->
            <a href="{{ route('nutritionPanel.users.edit', ['id' => $userEncryptedId]) }}" class="btn fcc-btn-edit-member">
                <i data-feather="edit-2"></i>
                <span>Edit member</span>
            </a>
        </div>
    </div>

    <!-- 3. Sub-Navigation Tabs Ribbon -->
    <div class="fcc-member-nav-tabs">
        <a href="{{ route('nutritionPanel.users.details', ['id' => $userEncryptedId]) }}" class="fcc-tab-link active">
            <i data-feather="user"></i>
            <span>Overview</span>
        </a>
        <a href="{{ route('nutritionPanel.users.viewWeights', ['id' => $userEncryptedId]) }}" class="fcc-tab-link">
            <i data-feather="activity"></i>
            <span>Weight</span>
        </a>
        <a href="{{ route('nutritionPanel.users.viewAttendance', ['id' => $userEncryptedId]) }}" class="fcc-tab-link">
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

    <!-- 4. Profile Main Grid -->
    <div class="fcc-profile-main-grid">

        <!-- Left Sidebar: Profile Card -->
        <div class="fcc-sidebar-card">
            <div class="fcc-avatar-box">
                @if($profileImage)
                    <img src="{{ $profileImage }}" alt="{{ $user->name }}" />
                @else
                    <div class="fcc-avatar-initials">{{ $initials }}</div>
                @endif
            </div>

            <h2 class="fcc-profile-name">{{ ucwords($user->name) }}</h2>
            
            <div>
                @if($user->status == 1)
                    <span class="fcc-status-badge active">
                        <span class="fcc-status-dot"></span>
                        <span>Active</span>
                    </span>
                @else
                    <span class="fcc-status-badge inactive">
                        <span class="fcc-status-dot"></span>
                        <span>Inactive</span>
                    </span>
                @endif
            </div>

            <div class="fcc-profile-tags">
                <span class="fcc-profile-pill user-type">{{ $user->user_type ?? 'Regular User' }}</span>
                <span class="fcc-profile-pill user-state">{{ $user->user_state ?? 'Offline' }}</span>
            </div>

            <ul class="fcc-sidebar-contacts-list">
                <li class="fcc-sidebar-contacts-item">
                    <i data-feather="mail"></i>
                    <span>{{ $user->email ?? 'N/A' }}</span>
                </li>
                <li class="fcc-sidebar-contacts-item">
                    <i data-feather="phone"></i>
                    <span>{{ $user->mobile_number ?? 'N/A' }}</span>
                </li>
                <li class="fcc-sidebar-contacts-item">
                    <i data-feather="calendar"></i>
                    <span>Joined {{ !empty($user->created_at) ? date('d M Y, h:i A', strtotime($user->created_at)) : 'N/A' }}</span>
                </li>
            </ul>

            <div class="fcc-sidebar-coach-section">
                <div class="fcc-coach-label">Coach</div>
                <div class="fcc-coach-name">{{ $user->coach_name ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Right Column: Metric Cards & Details -->
        <div class="fcc-right-content">

            <!-- Top 4 Metric Cards Grid -->
            <div class="fcc-top-metrics-grid">
                <!-- Card 1: Latest weight -->
                <div class="fcc-metric-box">
                    <div class="fcc-metric-box-icon blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="fcc-metric-box-label">Latest weight</div>
                        <div class="fcc-metric-box-val">{{ $latestWeight > 0 ? number_format($latestWeight, 1) . ' kg' : 'N/A' }}</div>
                    </div>
                </div>

                <!-- Card 2: Weight goal -->
                <div class="fcc-metric-box">
                    <div class="fcc-metric-box-icon purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="6"></circle>
                            <circle cx="12" cy="12" r="2"></circle>
                        </svg>
                    </div>
                    <div>
                        <div class="fcc-metric-box-label">Weight goal</div>
                        <div class="fcc-metric-box-val">{{ !empty($user->weight_goal) ? $user->weight_goal . ' kg' : 'N/A' }}</div>
                    </div>
                </div>

                <!-- Card 3: Joining weight -->
                <div class="fcc-metric-box">
                    <div class="fcc-metric-box-icon green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                            <polyline points="17 6 23 6 23 12"></polyline>
                        </svg>
                    </div>
                    <div>
                        <div class="fcc-metric-box-label">Joining weight</div>
                        <div class="fcc-metric-box-val">{{ $startingWeight > 0 ? number_format($startingWeight, 1) . ' kg' : 'N/A' }}</div>
                    </div>
                </div>

                <!-- Card 4: Amount due -->
                <div class="fcc-metric-box">
                    <div class="fcc-metric-box-icon orange">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                        </svg>
                    </div>
                    <div>
                        <div class="fcc-metric-box-label">Amount due</div>
                        <div class="fcc-metric-box-val">₹{{ !empty($user->due_amount) ? number_format($user->due_amount, 0) : '0' }}</div>
                    </div>
                </div>
            </div>

            <!-- Membership Overview Card -->
            <div class="fcc-overview-card">
                <div>
                    <h3 class="fcc-card-title">Membership overview</h3>
                    <p class="fcc-card-sub">Current plan, status and remaining duration</p>
                </div>

                <div class="fcc-plan-banner">
                    <div class="fcc-plan-banner-left">
                        <div class="fcc-plan-icon-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                        </div>
                        <div>
                            <h4 class="fcc-plan-heading">{{ $planName }}</h4>
                            <p class="fcc-plan-remaining">{{ $remainingDays }} days remaining</p>
                        </div>
                    </div>
                    <div class="fcc-plan-status-tag">Plan active</div>
                </div>

                <!-- Progress Track -->
                <div class="fcc-plan-progress-track">
                    <div class="fcc-plan-progress-fill" style="width: {{ $progressPercent }}%;"></div>
                </div>

                <!-- Footer Stats -->
                <div class="fcc-overview-footer-stats">
                    <div>
                        <div class="fcc-stat-block-label">Coach</div>
                        <div class="fcc-stat-block-val">{{ $user->coach_name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="fcc-stat-block-label">Status</div>
                        <div>
                            @if($user->status == 1)
                                <span class="badge" style="background: #dcfce7; color: #166534; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 6px;">Active</span>
                            @else
                                <span class="badge" style="background: #fee2e2; color: #991b1b; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 6px;">Inactive</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="fcc-due-amount-display">
                            <div class="fcc-due-icon-wrap">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                    <line x1="2" y1="10" x2="22" y2="10"></line>
                                </svg>
                            </div>
                            <div>
                                <div class="fcc-stat-block-label">Amount due</div>
                                <div class="fcc-stat-block-val" style="font-size: 15px; color: #0f172a;">₹{{ !empty($user->due_amount) ? number_format($user->due_amount, 0) : '0' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Sub-Cards Grid -->
            <div class="fcc-details-two-cols">
                <!-- Sub-Card 1: Personal Information -->
                <div class="fcc-detail-card">
                    <div>
                        <h3 class="fcc-card-title">Personal information</h3>
                        <p class="fcc-card-sub">Identity and contact details</p>
                        
                        <div class="fcc-key-value-list">
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Name</span>
                                <span class="fcc-kv-val">{{ !empty($user->name) ? $user->name : 'N/A' }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Email</span>
                                <span class="fcc-kv-val">{{ !empty($user->email) ? $user->email : 'N/A' }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Mobile number</span>
                                <span class="fcc-kv-val">{{ !empty($user->mobile_number) ? $user->mobile_number : 'N/A' }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Age</span>
                                <span class="fcc-kv-val">{{ !empty($user->age) ? $user->age : 'N/A' }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Gender</span>
                                <span class="fcc-kv-val">{{ $genderText }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Height</span>
                                <span class="fcc-kv-val">{{ !empty($user->height) ? $user->height . ' cm' : 'N/A' }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">User type</span>
                                <span class="fcc-kv-val">{{ !empty($user->user_type) ? $user->user_type : 'Regular User' }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">User state</span>
                                <span class="fcc-kv-val">{{ !empty($user->user_state) ? $user->user_state : 'Offline' }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Joining date</span>
                                <span class="fcc-kv-val">{{ !empty($user->created_at) ? date('d M Y, h:i A', strtotime($user->created_at)) : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sub-Card 2: Weight & coaching details -->
                <div class="fcc-detail-card">
                    <div>
                        <h3 class="fcc-card-title">Weight & coaching details</h3>
                        <p class="fcc-card-sub">Measurements, goal and assigned plan</p>

                        <div class="fcc-key-value-list">
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Coach name</span>
                                <span class="fcc-kv-val">{{ !empty($user->coach_name) ? $user->coach_name : 'N/A' }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Meal plan</span>
                                <span class="fcc-kv-val">{{ $planName }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Joining weight</span>
                                <span class="fcc-kv-val">{{ $startingWeight > 0 ? number_format($startingWeight, 1) . ' kg' : 'N/A' }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Latest weight</span>
                                <span class="fcc-kv-val">{{ $latestWeight > 0 ? number_format($latestWeight, 1) . ' kg' : 'N/A' }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Overall max weight</span>
                                <span class="fcc-kv-val">{{ !empty($maxWeight->weight) ? number_format($maxWeight->weight, 1) . ' kg' : 'N/A' }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Overall min weight</span>
                                <span class="fcc-kv-val">{{ !empty($minWeight->weight) ? number_format($minWeight->weight, 1) . ' kg' : 'N/A' }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Weight goal</span>
                                <span class="fcc-kv-val">{{ !empty($user->weight_goal) ? $user->weight_goal . ' kg' : 'N/A' }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Remaining days</span>
                                <span class="fcc-kv-val">{{ $remainingDays }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Due amount</span>
                                <span class="fcc-kv-val">₹{{ !empty($user->due_amount) ? number_format($user->due_amount, 0) : '0' }}</span>
                            </div>
                            <div class="fcc-kv-row">
                                <span class="fcc-kv-key">Status</span>
                                <span class="fcc-kv-val">
                                    @if($user->status == 1)
                                        <span class="badge" style="background: #dcfce7; color: #166534; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px;">Active</span>
                                    @else
                                        <span class="badge" style="background: #fee2e2; color: #991b1b; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px;">Inactive</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Highlight Box -->
                    @if($weightDiff < 0)
                        <div class="fcc-weight-progress-box">
                            <div class="fcc-weight-progress-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                    <polyline points="17 18 23 18 23 12"></polyline>
                                </svg>
                            </div>
                            <div>
                                <h4 class="fcc-wp-title">{{ abs($weightDiff) }} kg below joining weight</h4>
                                <p class="fcc-wp-sub">Your latest weight is {{ abs($weightDiff) }} kg less than your joining weight.</p>
                            </div>
                        </div>
                    @elseif($weightDiff > 0)
                        <div class="fcc-weight-progress-box orange">
                            <div class="fcc-weight-progress-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                            </div>
                            <div>
                                <h4 class="fcc-wp-title">{{ $weightDiff }} kg above joining weight</h4>
                                <p class="fcc-wp-sub">Your latest weight is {{ $weightDiff }} kg more than your joining weight.</p>
                            </div>
                        </div>
                    @else
                        <div class="fcc-weight-progress-box">
                            <div class="fcc-weight-progress-icon">
                                <i data-feather="check" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <h4 class="fcc-wp-title">Consistent with joining weight</h4>
                                <p class="fcc-wp-sub">Tracking weight consistency according to plan goals.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

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
    });
</script>
@endpush
