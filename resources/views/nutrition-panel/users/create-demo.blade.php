@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Add demo user | ' . __('language.page_main_title'))

@push('styles')
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<style>
    :root {
        --demo-primary: #3b46f1;
        --demo-primary-hover: #2d37e2;
        --demo-primary-soft: #eff2fe;
        --demo-text-main: #0f172a;
        --demo-text-muted: #64748b;
        --demo-border: #e2e8f0;
        --demo-card-bg: #ffffff;
    }

    body {
        background-color: #f8fafc !important;
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    }

    .fcc-demo-wrapper {
        max-width: 1240px;
        margin: 0 auto;
        padding: 24px 20px 80px 20px;
    }

    /* Breadcrumbs */
    .fcc-breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--demo-text-muted);
        margin-bottom: 12px;
    }

    .fcc-breadcrumb-nav a {
        color: var(--demo-text-muted);
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .fcc-breadcrumb-nav a:hover {
        color: var(--demo-primary);
    }

    .fcc-breadcrumb-sep {
        color: #cbd5e1;
        font-size: 11px;
    }

    /* Header Bar */
    .fcc-demo-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .fcc-demo-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--demo-text-main);
        letter-spacing: -0.02em;
        margin: 0 0 4px 0;
    }

    .fcc-demo-subtitle {
        font-size: 13.5px;
        color: var(--demo-text-muted);
        margin: 0;
    }

    .fcc-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fcc-btn-cancel {
        background: #ffffff;
        border: 1px solid var(--demo-border);
        color: #334155;
        font-size: 13.5px;
        font-weight: 600;
        padding: 9px 20px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.18s ease;
    }

    .fcc-btn-cancel:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .fcc-btn-primary {
        background: var(--demo-primary);
        border: 1px solid var(--demo-primary);
        color: #ffffff !important;
        font-size: 13.5px;
        font-weight: 700;
        padding: 9px 22px;
        border-radius: 10px;
        box-shadow: 0 4px 14px rgba(59, 70, 241, 0.28);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .fcc-btn-primary:hover {
        background: var(--demo-primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(59, 70, 241, 0.38);
    }

    /* Layout Grid */
    .fcc-demo-grid {
        display: grid;
        grid-template-columns: 1fr 370px;
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 991px) {
        .fcc-demo-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Cards */
    .fcc-card {
        background: #ffffff;
        border: 1px solid var(--demo-border);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        margin-bottom: 20px;
    }

    /* Top Highlight Banner */
    .fcc-quick-banner {
        background: #ffffff;
        border: 1px solid #e0e7ff;
        border-radius: 16px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
        box-shadow: 0 1px 2px rgba(99, 102, 241, 0.04);
    }

    .fcc-sparkle-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #eff2fe;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .fcc-quick-banner h4 {
        font-size: 15px;
        font-weight: 700;
        color: var(--demo-text-main);
        margin: 0 0 2px 0;
    }

    .fcc-quick-banner p {
        font-size: 12.5px;
        color: var(--demo-text-muted);
        margin: 0;
    }

    /* Section Header */
    .fcc-sec-head {
        margin-bottom: 22px;
    }

    .fcc-sec-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--demo-text-main);
        margin: 0 0 4px 0;
    }

    .fcc-sec-sub {
        font-size: 13px;
        color: var(--demo-text-muted);
        margin: 0;
    }

    /* Form Inputs */
    .fcc-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px 20px;
    }

    @media (max-width: 640px) {
        .fcc-form-grid {
            grid-template-columns: 1fr;
        }
    }

    .fcc-field-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .fcc-label {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
    }

    .fcc-label .req {
        color: #ef4444;
        margin-left: 2px;
    }

    .fcc-input {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13.5px;
        color: #0f172a;
        transition: all 0.18s ease;
        outline: none;
        width: 100%;
    }

    .fcc-input:focus {
        border-color: var(--demo-primary);
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12);
    }

    .fcc-input::placeholder {
        color: #94a3b8;
    }

    /* Input with prepend (+91) */
    .fcc-input-group {
        display: flex;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.18s ease;
    }

    .fcc-input-group:focus-within {
        border-color: var(--demo-primary);
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12);
    }

    .fcc-input-prepend {
        background: #f8fafc;
        border-right: 1px solid #cbd5e1;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 4px;
        user-select: none;
    }

    .fcc-input-group .fcc-input {
        border: none;
        border-radius: 0;
        box-shadow: none !important;
    }

    /* Datepicker input */
    .fcc-date-wrap {
        position: relative;
    }

    .fcc-date-wrap i {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        pointer-events: none;
    }

    /* Coach Preview Card */
    .fcc-coach-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 18px;
        gap: 14px;
    }

    .fcc-coach-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fcc-coach-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #dbeafe;
        color: #1d4ed8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        flex-shrink: 0;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .fcc-coach-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--demo-text-main);
        line-height: 1.25;
    }

    .fcc-coach-status {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--demo-text-muted);
        margin-top: 2px;
    }

    .fcc-dot-avail {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10b981;
        display: inline-block;
    }

    .fcc-coach-change {
        font-size: 13px;
        font-weight: 700;
        color: var(--demo-primary);
        text-decoration: none;
        cursor: pointer;
    }

    .fcc-coach-change:hover {
        text-decoration: underline;
    }

    /* Expiry Notice Strip */
    .fcc-notice-strip {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 20px;
        font-size: 13.5px;
        font-weight: 600;
        color: #1e40af;
    }

    .fcc-notice-strip i {
        font-size: 18px;
        color: #2563eb;
        flex-shrink: 0;
    }

    /* RIGHT SIDEBAR */
    .fcc-sidebar-card {
        position: sticky;
        top: 20px;
    }

    .fcc-summary-user {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 16px;
    }

    .fcc-summary-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #eff2fe;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .fcc-summary-name {
        font-size: 15px;
        font-weight: 800;
        color: var(--demo-text-main);
        line-height: 1.25;
        margin-bottom: 3px;
    }

    .fcc-draft-badge {
        background: #f1f5f9;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .fcc-progress-counter {
        font-size: 12px;
        font-weight: 700;
        color: var(--demo-text-muted);
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Checklist */
    .fcc-chk-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-bottom: 24px;
    }

    .fcc-chk-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fcc-chk-icon {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .fcc-chk-icon.user {
        background: #eff2fe;
        color: #4f46e5;
    }

    .fcc-chk-icon.coach {
        background: #fff7ed;
        color: #ea580c;
    }

    .fcc-chk-icon.lock {
        background: #ecfdf5;
        color: #059669;
    }

    .fcc-chk-body {
        flex-grow: 1;
    }

    .fcc-chk-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--demo-text-main);
        line-height: 1.2;
    }

    .fcc-chk-sub {
        font-size: 11.5px;
        color: var(--demo-text-muted);
        line-height: 1.2;
    }

    .fcc-chk-circle {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #ffffff;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .fcc-chk-circle.completed {
        background: #10b981;
        border-color: #10b981;
    }

    /* What happens next */
    .fcc-next-sec {
        border-top: 1px solid #f1f5f9;
        padding-top: 18px;
        margin-bottom: 20px;
    }

    .fcc-next-title {
        font-size: 13.5px;
        font-weight: 800;
        color: var(--demo-text-main);
        margin: 0 0 12px 0;
    }

    .fcc-next-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .fcc-next-item {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 12.5px;
        color: #334155;
    }

    .fcc-next-dot {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11.5px;
        color: #475569;
        flex-shrink: 0;
    }

    /* Green Safe Card */
    .fcc-safe-card {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12.5px;
        font-weight: 600;
        color: #15803d;
        margin-bottom: 20px;
    }

    .fcc-safe-card i {
        font-size: 15px;
        color: #16a34a;
    }

    .fcc-btn-full {
        width: 100%;
        padding: 11px 20px;
        font-size: 14px;
    }

    /* Bottom Action Footer */
    .fcc-bottom-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
        margin-top: 28px;
    }

    .fcc-btn-draft {
        background: #ffffff;
        border: 1px solid var(--demo-primary);
        color: var(--demo-primary);
        font-size: 13.5px;
        font-weight: 700;
        padding: 9px 20px;
        border-radius: 10px;
        transition: all 0.18s ease;
        cursor: pointer;
    }

    .fcc-btn-draft:hover {
        background: #eff2fe;
    }
</style>
@endpush

@section('content')
<div class="fcc-demo-wrapper">

    <!-- Validation errors if any -->
    @component('nutrition-panel.validation.errors') @endcomponent

    <!-- Breadcrumbs -->
    <div class="fcc-breadcrumb-nav">
        <a href="{{ route('nutritionPanel.users.index') }}">Members</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <a href="{{ route('nutritionPanel.users.index') }}/demo">Demo users</a>
        <span class="fcc-breadcrumb-sep">/</span>
        <span class="text-dark fw-semibold">Add demo user</span>
    </div>

    <!-- Header Bar -->
    <div class="fcc-demo-header">
        <div>
            <h1 class="fcc-demo-title">Add demo user</h1>
            <p class="fcc-demo-subtitle">Create a lightweight trial profile and assign a coach</p>
        </div>
        <div class="fcc-header-actions">
            <a href="{{ route('nutritionPanel.users.index') }}/demo" class="fcc-btn-cancel">Cancel</a>
            <button type="button" class="fcc-btn-primary btn-submit-demo">Create demo user</button>
        </div>
    </div>

    <!-- Main Form -->
    {!! Form::open(['class' => 'user-form', 'id' => 'demoUserForm', 'method' => 'post', 'url' => route('nutritionPanel.users.store'), 'autocomplete' => 'off' ]) !!}
        {!! Form::hidden('user_type', 'Demo User') !!}
        {!! Form::hidden('is_demo', '1') !!}
        {!! Form::hidden('days', '3') !!}
        {!! Form::hidden('country_code', '+91', ['id' => 'country_code']) !!}

        <div class="fcc-demo-grid">

            <!-- LEFT COLUMN: Form Details -->
            <div>

                <!-- Quick demo registration banner -->
                <div class="fcc-quick-banner">
                    <div class="fcc-sparkle-icon">
                        <i class="fa fa-magic"></i>
                    </div>
                    <div>
                        <h4>Quick demo registration</h4>
                        <p>Only essential details are required</p>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="fcc-card">
                    <div class="fcc-sec-head">
                        <h2 class="fcc-sec-title">Demo member details</h2>
                        <p class="fcc-sec-sub">Basic information for a 3-day trial profile</p>
                    </div>

                    <div class="fcc-form-grid">

                        <!-- User name -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="user_name">User name <span class="req">*</span></label>
                            <input type="text" name="name" id="user_name" class="fcc-input live-track" placeholder="Enter full name" required value="{{ old('name') }}" />
                        </div>

                        <!-- Mobile number -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="mobile_number">Mobile number <span class="req">*</span></label>
                            <div class="fcc-input-group">
                                <div class="fcc-input-prepend">
                                    <span>+91</span>
                                    <i class="fa fa-caret-down ms-1" style="font-size: 10px;"></i>
                                </div>
                                <input type="text" name="mobile_number" id="mobile_number" class="fcc-input live-track numeric" placeholder="Enter mobile number" required value="{{ old('mobile_number') }}" maxlength="10" />
                            </div>
                        </div>

                        <!-- Email address -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="email">Email address <span class="req">*</span></label>
                            <input type="email" name="email" id="email" class="fcc-input live-track" placeholder="member@example.com" required value="{{ old('email') }}" />
                        </div>

                        <!-- Current weight -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="weight">Current weight (kg) <span class="req">*</span></label>
                            <input type="number" step="0.1" name="weight" id="weight" class="fcc-input live-track" placeholder="Enter weight" required value="{{ old('weight') }}" />
                        </div>

                        <!-- Assign coach -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="coach_name">Assign coach <span class="req">*</span></label>
                            <select name="coach_name" id="coach_name" class="fcc-input live-track" required>
                                <option value="">Search or select coach</option>
                                @if(isset($coachesList) && count($coachesList) > 0)
                                    @foreach($coachesList as $coachItem)
                                        <option value="{{ $coachItem->coach_name }}" data-members="{{ $coachItem->total_members ?? 0 }}" {{ (old('coach_name') == $coachItem->coach_name || (count($coachesList) == 1 && $loop->first)) ? 'selected' : '' }}>
                                            {{ $coachItem->coach_name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="{{ $authUser->name }}" selected>{{ $authUser->name }}</option>
                                @endif
                            </select>
                        </div>

                        <!-- Demo start date -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="start_date">Demo start date <span class="req">*</span></label>
                            <div class="fcc-date-wrap">
                                <input type="text" name="start_date" id="start_date" class="fcc-input live-track datepicker" placeholder="Select start date" required value="{{ old('start_date', date('Y-m-d')) }}" />
                                <i class="fa fa-calendar-o"></i>
                            </div>
                        </div>

                    </div>

                    <!-- Coach preview card -->
                    <div class="fcc-coach-card" id="coachPreviewCard">
                        <div class="fcc-coach-left">
                            <div class="fcc-coach-avatar" id="coachAvatarLetter">
                                <i class="fa fa-user"></i>
                            </div>
                            <div>
                                <div class="fcc-coach-name" id="coachDisplayName">Coach Mokam</div>
                                <div class="fcc-coach-status">
                                    <span id="coachMembersCount">14 active members</span>
                                    <span>•</span>
                                    <span class="fcc-dot-avail"></span>
                                    <span class="text-success fw-semibold">Available</span>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="fcc-coach-change" onclick="$('#coach_name').focus();">Change</a>
                    </div>

                    <!-- Notice Strip -->
                    <div class="fcc-notice-strip">
                        <i class="fa fa-clock-o"></i>
                        <span>Demo access expires automatically after 3 days</span>
                    </div>

                </div>

            </div>

            <!-- RIGHT COLUMN: Sticky Summary -->
            <div>
                <div class="fcc-card fcc-sidebar-card">
                    <h3 class="fcc-sec-title mb-3" style="font-size: 15px;">Demo access summary</h3>

                    <div class="fcc-summary-user">
                        <div class="fcc-summary-avatar" id="summaryAvatar">
                            <i class="fa fa-user"></i>
                        </div>
                        <div>
                            <div class="fcc-summary-name" id="summaryUserName">New demo user</div>
                            <span class="fcc-draft-badge">Draft</span>
                        </div>
                    </div>

                    <div class="fcc-progress-counter">
                        <span id="fieldCountTxt">0 of 6 fields completed</span>
                    </div>

                    <div class="fcc-chk-list">
                        <!-- 1. Member details -->
                        <div class="fcc-chk-item">
                            <div class="fcc-chk-icon user">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="fcc-chk-body">
                                <div class="fcc-chk-title">Member details</div>
                                <div class="fcc-chk-sub">4 required fields</div>
                            </div>
                            <div class="fcc-chk-circle" id="chkMemberDetails">
                                <i class="fa fa-check"></i>
                            </div>
                        </div>

                        <!-- 2. Coach assignment -->
                        <div class="fcc-chk-item">
                            <div class="fcc-chk-icon coach">
                                <i class="fa fa-user-circle"></i>
                            </div>
                            <div class="fcc-chk-body">
                                <div class="fcc-chk-title">Coach assignment</div>
                                <div class="fcc-chk-sub">1 required field</div>
                            </div>
                            <div class="fcc-chk-circle" id="chkCoach">
                                <i class="fa fa-check"></i>
                            </div>
                        </div>

                        <!-- 3. Trial access -->
                        <div class="fcc-chk-item">
                            <div class="fcc-chk-icon lock">
                                <i class="fa fa-unlock-alt"></i>
                            </div>
                            <div class="fcc-chk-body">
                                <div class="fcc-chk-title">Trial access</div>
                                <div class="fcc-chk-sub">Start date required • 3-day access</div>
                            </div>
                            <div class="fcc-chk-circle" id="chkTrialAccess">
                                <i class="fa fa-check"></i>
                            </div>
                        </div>
                    </div>

                    <div class="fcc-next-sec">
                        <h4 class="fcc-next-title">What happens next?</h4>
                        <div class="fcc-next-list">
                            <div class="fcc-next-item">
                                <div class="fcc-next-dot"><i class="fa fa-user"></i></div>
                                <span>Demo profile is created</span>
                            </div>
                            <div class="fcc-next-item">
                                <div class="fcc-next-dot"><i class="fa fa-bell-o"></i></div>
                                <span>Coach receives a notification</span>
                            </div>
                            <div class="fcc-next-item">
                                <div class="fcc-next-dot"><i class="fa fa-hourglass-half"></i></div>
                                <span>Access closes after 3 days</span>
                            </div>
                        </div>
                    </div>

                    <div class="fcc-safe-card">
                        <i class="fa fa-lock"></i>
                        <span>Member data is securely stored</span>
                    </div>

                    <button type="button" class="fcc-btn-primary fcc-btn-full btn-submit-demo">
                        Create demo user
                    </button>
                </div>
            </div>

        </div>

        <!-- Bottom Action Bar -->
        <div class="fcc-bottom-footer">
            <a href="{{ route('nutritionPanel.users.index') }}/demo" class="fcc-btn-cancel">Cancel</a>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="fcc-btn-draft" onclick="window.history.back();">Save draft</button>
                <button type="button" class="fcc-btn-primary btn-submit-demo">Create demo user</button>
            </div>
        </div>

    {!! Form::close() !!}

</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/flatpickr.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initialize datepicker
        if (typeof flatpickr === "function") {
            $(".datepicker").flatpickr({
                dateFormat: "Y-m-d",
                defaultDate: "{{ date('Y-m-d') }}"
            });
        }

        // Live field completion tracker
        function updateProgress() {
            var name = $('#user_name').val().trim();
            var mobile = $('#mobile_number').val().trim();
            var email = $('#email').val().trim();
            var weight = $('#weight').val().trim();
            var coach = $('#coach_name').val().trim();
            var date = $('#start_date').val().trim();

            // Member details group
            var memberDetailsDone = (name !== '' && mobile !== '' && email !== '' && weight !== '');
            if (memberDetailsDone) {
                $('#chkMemberDetails').addClass('completed');
            } else {
                $('#chkMemberDetails').removeClass('completed');
            }

            // Coach group
            if (coach !== '') {
                $('#chkCoach').addClass('completed');
            } else {
                $('#chkCoach').removeClass('completed');
            }

            // Trial group
            if (date !== '') {
                $('#chkTrialAccess').addClass('completed');
            } else {
                $('#chkTrialAccess').removeClass('completed');
            }

            // Count fields
            var count = 0;
            if (name !== '') count++;
            if (mobile !== '') count++;
            if (email !== '') count++;
            if (weight !== '') count++;
            if (coach !== '') count++;
            if (date !== '') count++;

            $('#fieldCountTxt').text(count + ' of 6 fields completed');

            // Name preview in summary card
            if (name !== '') {
                $('#summaryUserName').text(name);
                var initials = name.split(' ').map(function(s) { return s[0]; }).join('').substring(0, 2).toUpperCase();
                $('#summaryAvatar').html('<span style="font-weight: 700; font-size: 16px;">' + initials + '</span>');
            } else {
                $('#summaryUserName').text('New demo user');
                $('#summaryAvatar').html('<i class="fa fa-user"></i>');
            }

            // Coach preview update
            if (coach !== '') {
                $('#coachDisplayName').text(coach);
                var opt = $('#coach_name option:selected');
                var members = opt.data('members') || 14;
                $('#coachMembersCount').text(members + ' active members');
                var coachLetter = coach.charAt(0).toUpperCase();
                $('#coachAvatarLetter').text(coachLetter);
            } else {
                $('#coachDisplayName').text('Select a coach');
                $('#coachMembersCount').text('No coach assigned');
                $('#coachAvatarLetter').html('<i class="fa fa-user"></i>');
            }
        }

        // Attach listeners
        $('.live-track').on('input change', updateProgress);
        updateProgress();

        // Submit form
        $('.btn-submit-demo').on('click', function(e) {
            e.preventDefault();
            var $form = $('#demoUserForm');

            // HTML5 validation check
            var formEl = $form[0];
            if (!formEl.checkValidity()) {
                formEl.reportValidity();
                return;
            }

            $form.submit();
        });
    });
</script>
@endpush
