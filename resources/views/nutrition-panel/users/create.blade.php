@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Register new member | ' . __('language.page_main_title'))

@push('styles')
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<style>
    :root {
        --ums-primary: #3b46f1;
        --ums-primary-hover: #2d37e2;
        --ums-primary-soft: #eff2fe;
        --ums-text-main: #0f172a;
        --ums-text-muted: #64748b;
        --ums-border: #e2e8f0;
        --ums-card-bg: #ffffff;
    }

    body {
        background-color: #f8fafc !important;
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    }

    .fcc-ums-wrapper {
        max-width: 1260px;
        margin: 0 auto;
        padding: 24px 20px 80px 20px;
    }

    /* Header Bar */
    .fcc-ums-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .fcc-ums-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--ums-text-main);
        letter-spacing: -0.02em;
        margin: 0 0 4px 0;
    }

    .fcc-ums-subtitle {
        font-size: 13.5px;
        color: var(--ums-text-muted);
        margin: 0;
    }

    .fcc-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fcc-btn-cancel {
        background: #ffffff;
        border: 1px solid var(--ums-border);
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

    .fcc-btn-draft {
        background: #ffffff;
        border: 1px solid var(--ums-border);
        color: #334155;
        font-size: 13.5px;
        font-weight: 700;
        padding: 9px 20px;
        border-radius: 10px;
        transition: all 0.18s ease;
        cursor: pointer;
    }

    .fcc-btn-draft:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .fcc-btn-primary {
        background: var(--ums-primary);
        border: 1px solid var(--ums-primary);
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
        background: var(--ums-primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(59, 70, 241, 0.38);
    }

    /* Stepper Bar */
    .fcc-stepper-wrap {
        background: #ffffff;
        border: 1px solid var(--ums-border);
        border-radius: 16px;
        padding: 16px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        overflow-x: auto;
    }

    .fcc-steps-list {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-grow: 1;
    }

    .fcc-step-node {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 700;
        color: var(--ums-text-muted);
        white-space: nowrap;
    }

    .fcc-step-node.active {
        color: var(--ums-primary);
    }

    .fcc-step-num {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12.5px;
        font-weight: 800;
        background: #f1f5f9;
        color: #64748b;
        transition: all 0.2s ease;
    }

    .fcc-step-node.active .fcc-step-num {
        background: var(--ums-primary);
        color: #ffffff;
    }

    .fcc-step-node.completed .fcc-step-num {
        background: #10b981;
        color: #ffffff;
    }

    .fcc-step-line {
        flex-grow: 1;
        height: 2px;
        background: #e2e8f0;
        min-width: 24px;
    }

    .fcc-step-pct {
        font-size: 13px;
        font-weight: 800;
        color: #475569;
        white-space: nowrap;
        padding-left: 12px;
    }

    /* Layout Grid */
    .fcc-ums-grid {
        display: grid;
        grid-template-columns: 1fr 370px;
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 991px) {
        .fcc-ums-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Section Cards */
    .fcc-card {
        background: #ffffff;
        border: 1px solid var(--ums-border);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        margin-bottom: 20px;
    }

    .fcc-sec-title-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 4px;
    }

    .fcc-sec-badge {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--ums-primary);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .fcc-sec-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--ums-text-main);
        margin: 0;
    }

    .fcc-sec-sub {
        font-size: 12.5px;
        color: var(--ums-text-muted);
        margin: 0 0 20px 34px;
    }

    /* Personal Details Section Layout */
    .fcc-personal-layout {
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 24px;
    }

    @media (max-width: 768px) {
        .fcc-personal-layout {
            grid-template-columns: 1fr;
        }
    }

    /* Photo Upload Box */
    .fcc-photo-box {
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        padding: 20px 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        background: #f8fafc;
        transition: all 0.2s ease;
    }

    .fcc-photo-box:hover {
        border-color: var(--ums-primary);
        background: #f1f5f9;
    }

    .fcc-avatar-circle {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 12px;
        position: relative;
        overflow: hidden;
    }

    .fcc-avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .fcc-avatar-badge {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--ums-primary);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        border: 2px solid #ffffff;
    }

    .fcc-photo-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--ums-text-main);
        margin-bottom: 3px;
    }

    .fcc-photo-hint {
        font-size: 11px;
        color: var(--ums-text-muted);
        margin-bottom: 14px;
    }

    .fcc-btn-choose {
        background: #ffffff;
        border: 1px solid var(--ums-border);
        color: #334155;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .fcc-btn-choose:hover {
        background: #eff2fe;
        border-color: var(--ums-primary);
        color: var(--ums-primary);
    }

    /* Form Fields Grid */
    .fcc-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px 18px;
    }

    .fcc-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1.2fr;
        gap: 16px 18px;
    }

    @media (max-width: 768px) {
        .fcc-grid-2, .fcc-grid-3 {
            grid-template-columns: 1fr;
        }
    }

    .fcc-field-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
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
        padding: 9px 13px;
        font-size: 13.5px;
        color: #0f172a;
        transition: all 0.18s ease;
        outline: none;
        width: 100%;
    }

    .fcc-input:focus {
        border-color: var(--ums-primary);
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12);
    }

    .fcc-input::placeholder {
        color: #94a3b8;
    }

    /* Input Prepend (+91) */
    .fcc-input-group {
        display: flex;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.18s ease;
    }

    .fcc-input-group:focus-within {
        border-color: var(--ums-primary);
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12);
    }

    .fcc-input-prepend {
        background: #f8fafc;
        border-right: 1px solid #cbd5e1;
        padding: 9px 13px;
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

    /* Datepicker / Icon Wrap */
    .fcc-icon-wrap {
        position: relative;
    }

    .fcc-icon-wrap i {
        position: absolute;
        right: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        pointer-events: none;
    }

    /* Password Eye Toggle */
    .fcc-pass-wrap {
        position: relative;
    }

    .fcc-pass-wrap .pass-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        cursor: pointer;
        font-size: 14px;
        transition: color 0.15s ease;
    }

    .fcc-pass-wrap .pass-toggle:hover {
        color: #334155;
    }

    /* BMI Preview Card */
    .fcc-bmi-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .fcc-bmi-head {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--ums-primary);
        margin-bottom: 2px;
    }

    .fcc-bmi-val {
        font-size: 16px;
        font-weight: 800;
        color: var(--ums-text-main);
        line-height: 1.2;
    }

    .fcc-bmi-desc {
        font-size: 11px;
        color: var(--ums-text-muted);
    }

    /* Password Chips */
    .fcc-pass-chips {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .fcc-chip {
        font-size: 11.5px;
        font-weight: 600;
        color: #64748b;
        background: #f1f5f9;
        border-radius: 20px;
        padding: 3px 10px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }

    .fcc-chip.met {
        background: #ecfdf5;
        color: #059669;
    }

    .fcc-chip-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #cbd5e1;
    }

    .fcc-chip.met .fcc-chip-dot {
        background: #10b981;
    }

    /* Send login checkbox */
    .fcc-check-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        user-select: none;
        margin-top: 14px;
    }

    .fcc-check-wrap input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--ums-primary);
        cursor: pointer;
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
        color: var(--ums-text-main);
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
        font-size: 12.5px;
        font-weight: 700;
        color: var(--ums-text-muted);
        margin-bottom: 16px;
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

    .fcc-chk-icon.blue { background: #eff2fe; color: #4f46e5; }
    .fcc-chk-icon.purple { background: #faf5ff; color: #9333ea; }
    .fcc-chk-icon.orange { background: #fff7ed; color: #ea580c; }
    .fcc-chk-icon.green { background: #ecfdf5; color: #059669; }

    .fcc-chk-body {
        flex-grow: 1;
    }

    .fcc-chk-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--ums-text-main);
        line-height: 1.2;
    }

    .fcc-chk-sub {
        font-size: 11.5px;
        color: var(--ums-text-muted);
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
        color: var(--ums-text-main);
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
</style>
@endpush

@section('content')
<div class="fcc-ums-wrapper">

    <!-- Validation errors if any -->
    @component('nutrition-panel.validation.errors') @endcomponent

    <!-- Header Bar -->
    <div class="fcc-ums-header">
        <div>
            <h1 class="fcc-ums-title">Register new member</h1>
            <p class="fcc-ums-subtitle">Create their profile, goals, plan and portal access</p>
        </div>
        <div class="fcc-header-actions">
            <button type="button" class="fcc-btn-draft" onclick="window.history.back();">Save draft</button>
            <button type="button" class="fcc-btn-primary btn-submit-ums">Create member</button>
        </div>
    </div>

    <!-- Stepper Progress Bar -->
    <div class="fcc-stepper-wrap">
        <div class="fcc-steps-list">
            <div class="fcc-step-node active" id="stepNode1">
                <div class="fcc-step-num">1</div>
                <span>Personal details</span>
            </div>
            <div class="fcc-step-line"></div>
            <div class="fcc-step-node" id="stepNode2">
                <div class="fcc-step-num">2</div>
                <span>Health & goals</span>
            </div>
            <div class="fcc-step-line"></div>
            <div class="fcc-step-node" id="stepNode3">
                <div class="fcc-step-num">3</div>
                <span>Plan & coach</span>
            </div>
            <div class="fcc-step-line"></div>
            <div class="fcc-step-node" id="stepNode4">
                <div class="fcc-step-num">4</div>
                <span>Account access</span>
            </div>
        </div>
        <div class="fcc-step-pct" id="stepPctText">0% complete</div>
    </div>

    <!-- Main Form -->
    {!! Form::open(['class' => 'user-form', 'id' => 'umsUserForm', 'method' => 'post', 'url' => route('nutritionPanel.users.store'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
        {!! Form::hidden('country_code', '+91', ['id' => 'country_code']) !!}
        {!! Form::file('image', ['id' => 'imageInput', 'style' => 'display: none;', 'accept' => 'image/*']) !!}
        {!! Form::hidden('image_name', '', ['id' => 'image_name']) !!}

        <div class="fcc-ums-grid">

            <!-- LEFT MAIN CONTENT -->
            <div>

                <!-- SECTION 1: Personal Details -->
                <div class="fcc-card">
                    <div class="fcc-sec-title-row">
                        <span class="fcc-sec-badge">1</span>
                        <h2 class="fcc-sec-title">Personal details</h2>
                    </div>
                    <p class="fcc-sec-sub">Basic information used across the member profile</p>

                    <div class="fcc-personal-layout">
                        <!-- Photo Upload Box -->
                        <div class="fcc-photo-box">
                            <div class="fcc-avatar-circle" id="avatarPreviewBox">
                                <i class="fa fa-user"></i>
                                <div class="fcc-avatar-badge"><i class="fa fa-cloud-upload"></i></div>
                            </div>
                            <div class="fcc-photo-title">Upload profile photo</div>
                            <div class="fcc-photo-hint">PNG or JPG • Max 5 MB</div>
                            <button type="button" class="fcc-btn-choose" onclick="$('#imageInput').click();">Choose photo</button>
                        </div>

                        <!-- Personal Fields Grid -->
                        <div class="fcc-grid-2">
                            <!-- Full name -->
                            <div class="fcc-field-group">
                                <label class="fcc-label" for="full_name">Full name <span class="req">*</span></label>
                                <input type="text" name="name" id="full_name" class="fcc-input ums-track" placeholder="Enter member name" required value="{{ old('name') }}" />
                            </div>

                            <!-- Mobile number -->
                            <div class="fcc-field-group">
                                <label class="fcc-label" for="mobile_number">Mobile number <span class="req">*</span></label>
                                <div class="fcc-input-group">
                                    <div class="fcc-input-prepend">
                                        <span>+91</span>
                                        <i class="fa fa-caret-down ms-1" style="font-size: 10px;"></i>
                                    </div>
                                    <input type="text" name="mobile_number" id="mobile_number" class="fcc-input ums-track numeric" placeholder="Enter mobile number" required value="{{ old('mobile_number') }}" maxlength="10" />
                                </div>
                            </div>

                            <!-- Email address -->
                            <div class="fcc-field-group">
                                <label class="fcc-label" for="email">Email address</label>
                                <input type="email" name="email" id="email" class="fcc-input ums-track" placeholder="member@example.com" value="{{ old('email') }}" />
                            </div>

                            <!-- Date of birth -->
                            <div class="fcc-field-group">
                                <label class="fcc-label" for="date_of_birth">Date of birth</label>
                                <div class="fcc-icon-wrap">
                                    <input type="text" name="date_of_birth" id="date_of_birth" class="fcc-input ums-track datepicker" placeholder="Select date" value="{{ old('date_of_birth') }}" />
                                    <i class="fa fa-calendar-o"></i>
                                </div>
                            </div>

                            <!-- Age -->
                            <div class="fcc-field-group">
                                <label class="fcc-label" for="age">Age <span class="req">*</span></label>
                                <input type="number" min="5" max="120" name="age" id="age" class="fcc-input ums-track" placeholder="Enter age" required value="{{ old('age') }}" />
                            </div>

                            <!-- Gender -->
                            <div class="fcc-field-group">
                                <label class="fcc-label" for="gender">Gender</label>
                                <select name="gender" id="gender" class="fcc-input ums-track">
                                    <option value="">Select gender</option>
                                    <option value="1" {{ old('gender') == '1' ? 'selected' : '' }}>Male</option>
                                    <option value="2" {{ old('gender') == '2' ? 'selected' : '' }}>Female</option>
                                    <option value="3" {{ old('gender') == '3' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <!-- User state -->
                            <div class="fcc-field-group" style="grid-column: 1 / -1;">
                                <label class="fcc-label" for="user_state">User state</label>
                                <select name="user_state" id="user_state" class="fcc-input ums-track">
                                    <option value="">Select user state</option>
                                    @foreach(config('constants.user_state') as $stateItem)
                                        <option value="{{ $stateItem['value'] }}" {{ (old('user_state') == $stateItem['value'] || $loop->first) ? 'selected' : '' }}>
                                            {{ $stateItem['display'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Health & Goals -->
                <div class="fcc-card">
                    <div class="fcc-sec-title-row">
                        <span class="fcc-sec-badge">2</span>
                        <h2 class="fcc-sec-title">Health & goals</h2>
                    </div>
                    <p class="fcc-sec-sub">Target body composition and health milestones</p>

                    <div class="fcc-grid-3">
                        <!-- Current weight -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="weight">Current weight (kg) <span class="req">*</span></label>
                            <input type="number" step="0.1" name="weight" id="weight" class="fcc-input ums-track" placeholder="Enter weight" required value="{{ old('weight') }}" />
                        </div>

                        <!-- Goal weight -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="weight_goal">Goal weight (kg) <span class="req">*</span></label>
                            <input type="number" step="0.1" name="weight_goal" id="weight_goal" class="fcc-input ums-track" placeholder="Enter goal" required value="{{ old('weight_goal') }}" />
                        </div>

                        <!-- Height -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="height">Height (cm) <span class="req">*</span></label>
                            <input type="number" step="0.1" name="height" id="height" class="fcc-input ums-track" placeholder="Enter height" required value="{{ old('height') }}" />
                        </div>

                        <!-- BMI preview -->
                        <div class="fcc-bmi-card">
                            <div class="fcc-bmi-head">BMI preview</div>
                            <div class="fcc-bmi-val" id="bmiDisplayVal">—</div>
                            <div class="fcc-bmi-desc" id="bmiDisplayDesc">Calculated after height and weight</div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: Plan & Coaching -->
                <div class="fcc-card">
                    <div class="fcc-sec-title-row">
                        <span class="fcc-sec-badge">3</span>
                        <h2 class="fcc-sec-title">Plan & coaching</h2>
                    </div>
                    <p class="fcc-sec-sub">Assign nutritional guide and coach mentorship</p>

                    <div class="fcc-grid-2">
                        <!-- Coach name -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="coach_name">Coach name</label>
                            <select name="coach_name" id="coach_name" class="fcc-input ums-track">
                                <option value="">Select coach</option>
                                @if(isset($coachesList) && count($coachesList) > 0)
                                    @foreach($coachesList as $coachItem)
                                        <option value="{{ $coachItem->coach_name }}" {{ old('coach_name') == $coachItem->coach_name ? 'selected' : '' }}>
                                            {{ $coachItem->coach_name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="{{ $authUser->name }}">{{ $authUser->name }}</option>
                                @endif
                            </select>
                        </div>

                        <!-- Meal type -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="meal_type_id">Meal type</label>
                            <select name="meal_type_id" id="meal_type_id" class="fcc-input ums-track">
                                <option value="">Select meal type</option>
                                @if(isset($mealTypes))
                                    @foreach($mealTypes as $mt)
                                        <option value="{{ $mt->id }}" {{ old('meal_type_id') == $mt->id ? 'selected' : '' }}>
                                            {{ $mt->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Product type -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="product_type_id">Product type</label>
                            <select name="product_type_id" id="product_type_id" class="fcc-input ums-track">
                                <option value="">Select product type</option>
                                @if(isset($productTypes))
                                    @foreach($productTypes as $pt)
                                        <option value="{{ $pt->id }}" {{ old('product_type_id') == $pt->id ? 'selected' : '' }}>
                                            {{ $pt->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- User type -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="user_type">User type</label>
                            <select name="user_type" id="user_type" class="fcc-input ums-track">
                                <option value="">Select user type</option>
                                @foreach(config('constants.user_type') as $ut)
                                    <option value="{{ $ut['value'] }}" {{ (old('user_type', $selectedUserType ?? 'Regular User') == $ut['value']) ? 'selected' : '' }}>
                                        {{ $ut['display'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: Portal Access -->
                <div class="fcc-card">
                    <div class="fcc-sec-title-row">
                        <span class="fcc-sec-badge">4</span>
                        <h2 class="fcc-sec-title">Portal access</h2>
                    </div>
                    <p class="fcc-sec-sub">Set member credentials for portal login</p>

                    <div class="fcc-grid-2">
                        <!-- New password -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="new_pass">New password</label>
                            <div class="fcc-pass-wrap">
                                <input type="password" name="new_pass" id="new_pass" class="fcc-input ums-track" placeholder="Create password" />
                                <i class="fa fa-eye pass-toggle" onclick="togglePasswordVisibility('new_pass', this)"></i>
                            </div>
                        </div>

                        <!-- Confirm password -->
                        <div class="fcc-field-group">
                            <label class="fcc-label" for="confirm_pass">Confirm password</label>
                            <div class="fcc-pass-wrap">
                                <input type="password" name="confirm_pass" id="confirm_pass" class="fcc-input ums-track" placeholder="Re-enter password" />
                                <i class="fa fa-eye pass-toggle" onclick="togglePasswordVisibility('confirm_pass', this)"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Password strength chips -->
                    <div class="fcc-pass-chips">
                        <div class="fcc-chip" id="chipLength">
                            <span class="fcc-chip-dot"></span>
                            <span>8+ characters</span>
                        </div>
                        <div class="fcc-chip" id="chipNumber">
                            <span class="fcc-chip-dot"></span>
                            <span>One number</span>
                        </div>
                        <div class="fcc-chip" id="chipSpecial">
                            <span class="fcc-chip-dot"></span>
                            <span>One special character</span>
                        </div>
                    </div>

                    <!-- Send login credentials checkbox -->
                    <label class="fcc-check-wrap">
                        <input type="checkbox" name="send_login_details" value="1" checked />
                        <span>Send login details to member</span>
                    </label>
                </div>

            </div>

            <!-- RIGHT SIDEBAR: Registration Summary -->
            <div>
                <div class="fcc-card fcc-sidebar-card">
                    <h3 class="fcc-sec-title mb-3" style="font-size: 15px;">Registration summary</h3>

                    <div class="fcc-summary-user">
                        <div class="fcc-summary-avatar" id="umsSummaryAvatar">
                            <i class="fa fa-user"></i>
                        </div>
                        <div>
                            <div class="fcc-summary-name" id="umsSummaryName">New member</div>
                            <span class="fcc-draft-badge">Draft</span>
                        </div>
                    </div>

                    <div class="fcc-progress-counter">
                        <span id="umsProgressTxt">0 of 16 fields completed</span>
                    </div>

                    <div class="fcc-chk-list">
                        <!-- 1. Personal details -->
                        <div class="fcc-chk-item">
                            <div class="fcc-chk-icon blue">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="fcc-chk-body">
                                <div class="fcc-chk-title">Personal details</div>
                                <div class="fcc-chk-sub">7 fields</div>
                            </div>
                            <div class="fcc-chk-circle" id="chkPersonal">
                                <i class="fa fa-check"></i>
                            </div>
                        </div>

                        <!-- 2. Health & goals -->
                        <div class="fcc-chk-item">
                            <div class="fcc-chk-icon purple">
                                <i class="fa fa-heart"></i>
                            </div>
                            <div class="fcc-chk-body">
                                <div class="fcc-chk-title">Health & goals</div>
                                <div class="fcc-chk-sub">3 fields</div>
                            </div>
                            <div class="fcc-chk-circle" id="chkHealth">
                                <i class="fa fa-check"></i>
                            </div>
                        </div>

                        <!-- 3. Plan & coach -->
                        <div class="fcc-chk-item">
                            <div class="fcc-chk-icon orange">
                                <i class="fa fa-file-text-o"></i>
                            </div>
                            <div class="fcc-chk-body">
                                <div class="fcc-chk-title">Plan & coach</div>
                                <div class="fcc-chk-sub">4 fields</div>
                            </div>
                            <div class="fcc-chk-circle" id="chkPlan">
                                <i class="fa fa-check"></i>
                            </div>
                        </div>

                        <!-- 4. Account access -->
                        <div class="fcc-chk-item">
                            <div class="fcc-chk-icon green">
                                <i class="fa fa-lock"></i>
                            </div>
                            <div class="fcc-chk-body">
                                <div class="fcc-chk-title">Account access</div>
                                <div class="fcc-chk-sub">2 fields</div>
                            </div>
                            <div class="fcc-chk-circle" id="chkAccess">
                                <i class="fa fa-check"></i>
                            </div>
                        </div>
                    </div>

                    <div class="fcc-next-sec">
                        <h4 class="fcc-next-title">What happens next?</h4>
                        <div class="fcc-next-list">
                            <div class="fcc-next-item">
                                <div class="fcc-next-dot"><i class="fa fa-user"></i></div>
                                <span>Member profile is created</span>
                            </div>
                            <div class="fcc-next-item">
                                <div class="fcc-next-dot"><i class="fa fa-users"></i></div>
                                <span>Coach and plan are assigned</span>
                            </div>
                            <div class="fcc-next-item">
                                <div class="fcc-next-dot"><i class="fa fa-paper-plane-o"></i></div>
                                <span>Login details are shared</span>
                            </div>
                        </div>
                    </div>

                    <div class="fcc-safe-card">
                        <i class="fa fa-lock"></i>
                        <span>Member data is securely stored</span>
                    </div>

                    <button type="button" class="fcc-btn-primary fcc-btn-full btn-submit-ums">
                        Create member
                    </button>
                </div>
            </div>

        </div>

        <!-- Bottom Action Bar -->
        <div class="fcc-bottom-footer">
            <a href="{{ route('nutritionPanel.users.index') }}" class="fcc-btn-cancel">Cancel</a>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="fcc-btn-draft" onclick="window.history.back();">Save draft</button>
                <button type="button" class="fcc-btn-primary btn-submit-ums">Create member</button>
            </div>
        </div>

    {!! Form::close() !!}

</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/flatpickr.js') }}"></script>
<script>
    // Password toggle
    function togglePasswordVisibility(inputId, iconEl) {
        var input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            iconEl.classList.remove('fa-eye');
            iconEl.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            iconEl.classList.remove('fa-eye-slash');
            iconEl.classList.add('fa-eye');
        }
    }

    $(document).ready(function() {
        // Initialize datepicker
        if (typeof flatpickr === "function") {
            $(".datepicker").flatpickr({
                dateFormat: "Y-m-d",
                maxDate: "today"
            });
        }

        // Live Photo preview
        $('#imageInput').on('change', function(e) {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(evt) {
                    $('#avatarPreviewBox').html('<img src="' + evt.target.result + '" alt="Avatar" /><div class="fcc-avatar-badge"><i class="fa fa-check"></i></div>');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Live BMI calculation
        function calculateBmi() {
            var weight = parseFloat($('#weight').val());
            var height = parseFloat($('#height').val());

            if (weight > 0 && height > 0) {
                var heightM = height / 100;
                var bmi = (weight / (heightM * heightM)).toFixed(1);
                var category = 'Normal';
                var colorClass = '#10b981';

                if (bmi < 18.5) {
                    category = 'Underweight';
                    colorClass = '#3b82f6';
                } else if (bmi >= 25 && bmi < 30) {
                    category = 'Overweight';
                    colorClass = '#f59e0b';
                } else if (bmi >= 30) {
                    category = 'Obese';
                    colorClass = '#ef4444';
                }

                $('#bmiDisplayVal').html(bmi + ' <span style="font-size: 13px; font-weight: 700; color:' + colorClass + ';">(' + category + ')</span>');
                $('#bmiDisplayDesc').text('Healthy BMI is 18.5 - 24.9');
            } else {
                $('#bmiDisplayVal').text('—');
                $('#bmiDisplayDesc').text('Calculated after height and weight');
            }
        }

        // Password strength checker
        function checkPasswordStrength() {
            var pass = $('#new_pass').val();
            var hasLength = pass.length >= 8;
            var hasNumber = /\d/.test(pass);
            var hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(pass);

            if (hasLength) $('#chipLength').addClass('met'); else $('#chipLength').removeClass('met');
            if (hasNumber) $('#chipNumber').addClass('met'); else $('#chipNumber').removeClass('met');
            if (hasSpecial) $('#chipSpecial').addClass('met'); else $('#chipSpecial').removeClass('met');
        }

        // Live fields tracker & progress
        function updateProgress() {
            var name = $('#full_name').val().trim();
            var mobile = $('#mobile_number').val().trim();
            var email = $('#email').val().trim();
            var dob = $('#date_of_birth').val().trim();
            var age = $('#age').val().trim();
            var gender = $('#gender').val();
            var userState = $('#user_state').val();

            var weight = $('#weight').val().trim();
            var weightGoal = $('#weight_goal').val().trim();
            var height = $('#height').val().trim();

            var coach = $('#coach_name').val();
            var meal = $('#meal_type_id').val();
            var product = $('#product_type_id').val();
            var userType = $('#user_type').val();

            var pass = $('#new_pass').val().trim();
            var confirmPass = $('#confirm_pass').val().trim();

            // Sections check
            var personalDone = (name !== '' && mobile !== '' && age !== '');
            var healthDone = (weight !== '' && weightGoal !== '' && height !== '');
            var planDone = (coach !== '' || meal !== '' || product !== '' || userType !== '');
            var accessDone = (pass !== '' && confirmPass !== '');

            if (personalDone) {
                $('#chkPersonal').addClass('completed');
                $('#stepNode1').addClass('completed');
            } else {
                $('#chkPersonal').removeClass('completed');
                $('#stepNode1').removeClass('completed');
            }

            if (healthDone) {
                $('#chkHealth').addClass('completed');
                $('#stepNode2').addClass('completed active');
            } else {
                $('#chkHealth').removeClass('completed');
                $('#stepNode2').removeClass('completed active');
            }

            if (planDone) {
                $('#chkPlan').addClass('completed');
                $('#stepNode3').addClass('completed active');
            } else {
                $('#chkPlan').removeClass('completed');
                $('#stepNode3').removeClass('completed active');
            }

            if (accessDone) {
                $('#chkAccess').addClass('completed');
                $('#stepNode4').addClass('completed active');
            } else {
                $('#chkAccess').removeClass('completed');
                $('#stepNode4').removeClass('completed active');
            }

            // Total fields counter (16 possible fields)
            var count = 0;
            if (name !== '') count++;
            if (mobile !== '') count++;
            if (email !== '') count++;
            if (dob !== '') count++;
            if (age !== '') count++;
            if (gender !== '') count++;
            if (userState !== '') count++;

            if (weight !== '') count++;
            if (weightGoal !== '') count++;
            if (height !== '') count++;

            if (coach !== '') count++;
            if (meal !== '') count++;
            if (product !== '') count++;
            if (userType !== '') count++;

            if (pass !== '') count++;
            if (confirmPass !== '') count++;

            $('#umsProgressTxt').text(count + ' of 16 fields completed');
            var pct = Math.round((count / 16) * 100);
            $('#stepPctText').text(pct + '% complete');

            // Summary Name update
            if (name !== '') {
                $('#umsSummaryName').text(name);
                var initials = name.split(' ').map(function(s) { return s[0]; }).join('').substring(0, 2).toUpperCase();
                if (!$('#imageInput').val()) {
                    $('#umsSummaryAvatar').html('<span style="font-weight: 700; font-size: 16px;">' + initials + '</span>');
                }
            } else {
                $('#umsSummaryName').text('New member');
                if (!$('#imageInput').val()) {
                    $('#umsSummaryAvatar').html('<i class="fa fa-user"></i>');
                }
            }

            calculateBmi();
            checkPasswordStrength();
        }

        $('.ums-track').on('input change', updateProgress);
        updateProgress();

        // Submit form
        $('.btn-submit-ums').on('click', function(e) {
            e.preventDefault();
            var $form = $('#umsUserForm');

            // Check passwords match if entered
            var pass = $('#new_pass').val();
            var conf = $('#confirm_pass').val();
            if (pass !== '' && pass !== conf) {
                alert('Passwords do not match. Please verify your password entry.');
                $('#confirm_pass').focus();
                return;
            }

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
