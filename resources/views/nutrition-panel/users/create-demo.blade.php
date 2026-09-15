@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Add Demo User | ' . __('language.page_main_title'))

@push('styles')
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<style>
    :root {
        --demo-primary: #3b46f1;
        --demo-primary-hover: #2d37e2;
        --demo-primary-soft: #eff2fe;
        --demo-primary-border: rgba(59, 70, 241, 0.18);
        --demo-text-main: #0f172a;
        --demo-text-muted: #64748b;
        --demo-border: #e2e8f0;
        --demo-card-bg: #ffffff;
    }

    body {
        background-color: #f8fafc !important;
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    }

    .fcc-demo-container {
        width: 100%;
        max-width: 100%;
        margin: 0;
        padding: 16px 28px 48px 28px;
        box-sizing: border-box;
    }

    /* Page Header */
    .fcc-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .fcc-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .fcc-btn-back {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #ffffff;
        border: 1px solid var(--demo-border);
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.18s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .fcc-btn-back:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .fcc-title-area {
        display: flex;
        flex-direction: column;
    }

    .fcc-title-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fcc-main-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--demo-text-main);
        letter-spacing: -0.02em;
        margin: 0;
        line-height: 1.2;
    }

    .fcc-badge-pill {
        background: #eff2fe;
        color: var(--demo-primary);
        font-size: 11.5px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        border: 1px solid var(--demo-primary-border);
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .fcc-subtitle {
        font-size: 13px;
        color: var(--demo-text-muted);
        margin: 3px 0 0 0;
    }

    .fcc-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fcc-btn-outline {
        background: #ffffff;
        border: 1px solid var(--demo-border);
        color: #334155;
        font-size: 13px;
        font-weight: 600;
        padding: 9px 20px;
        border-radius: 9px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.18s ease;
    }

    .fcc-btn-outline:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .fcc-btn-submit {
        background: var(--demo-primary);
        border: 1px solid var(--demo-primary);
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 700;
        padding: 9px 22px;
        border-radius: 9px;
        box-shadow: 0 4px 14px rgba(59, 70, 241, 0.25);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .fcc-btn-submit:hover {
        background: var(--demo-primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(59, 70, 241, 0.35);
    }

    /* Full Width Main Card */
    .fcc-full-card {
        background: #ffffff;
        border: 1px solid var(--demo-border);
        border-radius: 16px;
        padding: 26px 30px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        width: 100%;
        box-sizing: border-box;
    }

    .fcc-card-header {
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .fcc-card-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--demo-text-main);
        margin: 0 0 2px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .fcc-card-title i {
        color: var(--demo-primary);
    }

    .fcc-card-sub {
        font-size: 13px;
        color: var(--demo-text-muted);
        margin: 0;
    }

    /* 3-Column Responsive Grid */
    .fcc-form-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px 24px;
    }

    @media (max-width: 1100px) {
        .fcc-form-grid-3 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 680px) {
        .fcc-form-grid-3 {
            grid-template-columns: 1fr;
        }
    }

    .fcc-field {
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
    }

    .fcc-input-control {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13.5px;
        color: #0f172a;
        transition: all 0.18s ease;
        outline: none;
        width: 100%;
        box-sizing: border-box;
    }

    .fcc-input-control:focus {
        border-color: var(--demo-primary);
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12);
    }

    .fcc-input-control::placeholder {
        color: #94a3b8;
    }

    /* Mobile Number Group (+91) */
    .fcc-group-wrap {
        display: flex;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.18s ease;
    }

    .fcc-group-wrap:focus-within {
        border-color: var(--demo-primary);
        box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12);
    }

    .fcc-prefix-box {
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

    .fcc-group-wrap .fcc-input-control {
        border: none;
        border-radius: 0;
        box-shadow: none !important;
    }

    /* Datepicker icon */
    .fcc-date-container {
        position: relative;
    }

    .fcc-date-container i {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        pointer-events: none;
        font-size: 14px;
    }

    /* Live Summary Strip */
    .fcc-summary-strip {
        margin-top: 28px;
        padding: 18px 22px;
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        display: grid;
        grid-template-columns: 1.2fr 1fr 1fr 1fr;
        gap: 16px;
        align-items: center;
    }

    @media (max-width: 991px) {
        .fcc-summary-strip {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 600px) {
        .fcc-summary-strip {
            grid-template-columns: 1fr;
        }
    }

    .fcc-strip-user {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fcc-strip-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #e0e7ff;
        color: #3730a3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .fcc-strip-name {
        font-size: 14.5px;
        font-weight: 800;
        color: var(--demo-text-main);
        line-height: 1.2;
    }

    .fcc-status-chip {
        font-size: 11px;
        color: #059669;
        font-weight: 700;
        background: #dcfce7;
        padding: 2px 8px;
        border-radius: 5px;
        display: inline-block;
        margin-top: 2px;
    }

    .fcc-strip-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .fcc-strip-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #eff2fe;
        color: var(--demo-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .fcc-strip-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 12.5px;
        margin-bottom: 1px;
    }

    .fcc-strip-desc {
        color: #64748b;
        font-size: 12px;
    }

    /* Footer Action Bar */
    .fcc-form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
        gap: 16px;
        flex-wrap: wrap;
    }

    .fcc-shield-notice {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 12.5px;
        font-weight: 600;
        color: #166534;
    }
</style>
@endpush

@section('content')
<div class="fcc-demo-container">

    <!-- Validation errors if any -->
    @component('nutrition-panel.validation.errors') @endcomponent

    <!-- Page Header -->
    <div class="fcc-page-header">
        <div class="fcc-header-left">
            <a href="{{ route('nutritionPanel.users.index') }}/demo" class="fcc-btn-back" title="Back to Demo Members">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div class="fcc-title-area">
                <div class="fcc-title-row">
                    <h1 class="fcc-main-title">Add Demo User</h1>
                    <span class="fcc-badge-pill"><i class="fa fa-clock-o"></i> 3-Day Trial</span>
                </div>
                <p class="fcc-subtitle">Create a 3-day complimentary trial profile and assign a nutrition coach</p>
            </div>
        </div>

        <div class="fcc-header-actions">
            <a href="{{ route('nutritionPanel.users.index') }}/demo" class="fcc-btn-outline">Cancel</a>
            <button type="button" class="fcc-btn-submit btn-submit-action">
                <i class="fa fa-user-plus"></i> Create Demo User
            </button>
        </div>
    </div>

    <!-- Main Form Card -->
    {!! Form::open(['class' => 'user-form', 'id' => 'demoUserForm', 'method' => 'post', 'url' => route('nutritionPanel.users.store'), 'autocomplete' => 'off' ]) !!}
        {!! Form::hidden('user_type', 'Demo User') !!}
        {!! Form::hidden('is_demo', '1') !!}
        {!! Form::hidden('days', '3') !!}
        {!! Form::hidden('country_code', '+91', ['id' => 'country_code']) !!}

        <div class="fcc-full-card">

            <div class="fcc-card-header">
                <div>
                    <h2 class="fcc-card-title">
                        <i class="fa fa-address-card-o"></i> Trial Member Details
                    </h2>
                    <p class="fcc-card-sub">All essential fields required to activate the 3-day demo profile</p>
                </div>
                <div class="fcc-shield-notice">
                    <i class="fa fa-shield text-success"></i>
                    <span>Member data is securely encrypted</span>
                </div>
            </div>

            <!-- 3-Column Responsive Input Grid -->
            <div class="fcc-form-grid-3">

                <!-- Full Name -->
                <div class="fcc-field">
                    <label class="fcc-label" for="user_name">Full Name <span class="req">*</span></label>
                    <input type="text" name="name" id="user_name" class="fcc-input-control live-watcher" placeholder="e.g. Rahul Sharma" required value="{{ old('name') }}" />
                </div>

                <!-- Mobile Number -->
                <div class="fcc-field">
                    <label class="fcc-label" for="mobile_number">Mobile Number <span class="req">*</span></label>
                    <div class="fcc-group-wrap">
                        <div class="fcc-prefix-box">
                            <span>+91</span>
                            <i class="fa fa-caret-down" style="font-size: 10px;"></i>
                        </div>
                        <input type="text" name="mobile_number" id="mobile_number" class="fcc-input-control live-watcher numeric" placeholder="9876543210" required value="{{ old('mobile_number') }}" maxlength="10" data-url="{{ route('nutritionPanel.users.checkMobile') }}" />
                    </div>
                    <div id="demo_mobile_error" class="text-danger mt-1" style="font-size: 11.5px; display: none;"></div>
                </div>

                <!-- Email Address -->
                <div class="fcc-field">
                    <label class="fcc-label" for="email">Email Address <span class="req">*</span></label>
                    <input type="email" name="email" id="email" class="fcc-input-control live-watcher" placeholder="rahul@example.com" required value="{{ old('email') }}" data-url="{{ route('nutritionPanel.users.checkEmail') }}" />
                    <div id="demo_email_error" class="text-danger mt-1" style="font-size: 11.5px; display: none;"></div>
                </div>

                <!-- Current Weight -->
                <div class="fcc-field">
                    <label class="fcc-label" for="weight">Current Weight (kg) <span class="req">*</span></label>
                    <input type="number" step="0.1" name="weight" id="weight" class="fcc-input-control live-watcher" placeholder="e.g. 72.5" required value="{{ old('weight') }}" />
                </div>

                <!-- Assign Coach -->
                <div class="fcc-field">
                    <label class="fcc-label" for="coach_name">Assign Coach <span class="req">*</span></label>
                    <select name="coach_name" id="coach_name" class="fcc-input-control live-watcher" required>
                        <option value="">Select a coach</option>
                        @if(isset($coachesList) && count($coachesList) > 0)
                            @foreach($coachesList as $coachItem)
                                <option value="{{ $coachItem->coach_name }}" {{ (old('coach_name') == $coachItem->coach_name || (count($coachesList) == 1 && $loop->first)) ? 'selected' : '' }}>
                                    {{ $coachItem->coach_name }}
                                </option>
                            @endforeach
                        @else
                            <option value="{{ $authUser->name }}" selected>{{ $authUser->name }}</option>
                        @endif
                    </select>
                </div>

                <!-- Start Date -->
                <div class="fcc-field">
                    <label class="fcc-label" for="start_date">Trial Start Date <span class="req">*</span></label>
                    <div class="fcc-date-container">
                        <input type="text" name="start_date" id="start_date" class="fcc-input-control live-watcher datepicker" placeholder="Select start date" required value="{{ old('start_date', date('Y-m-d')) }}" />
                        <i class="fa fa-calendar"></i>
                    </div>
                </div>

            </div>

            <!-- Real-time Live Summary Strip -->
            <div class="fcc-summary-strip">
                <div class="fcc-strip-user">
                    <div class="fcc-strip-avatar" id="liveAvatar">
                        <i class="fa fa-user"></i>
                    </div>
                    <div>
                        <div class="fcc-strip-name" id="liveName">New Demo User</div>
                        <span class="fcc-status-chip"><i class="fa fa-check-circle"></i> 3-Day Pass</span>
                    </div>
                </div>

                <div class="fcc-strip-item">
                    <div class="fcc-strip-icon"><i class="fa fa-calendar"></i></div>
                    <div>
                        <div class="fcc-strip-title">Trial Period</div>
                        <div class="fcc-strip-desc" id="liveDuration">3 Days Active Trial</div>
                    </div>
                </div>

                <div class="fcc-strip-item">
                    <div class="fcc-strip-icon"><i class="fa fa-user-circle"></i></div>
                    <div>
                        <div class="fcc-strip-title">Assigned Coach</div>
                        <div class="fcc-strip-desc" id="liveCoach">Select a coach</div>
                    </div>
                </div>

                <div class="fcc-strip-item">
                    <div class="fcc-strip-icon"><i class="fa fa-cutlery"></i></div>
                    <div>
                        <div class="fcc-strip-title">Club Privileges</div>
                        <div class="fcc-strip-desc">Check-in, Shake & Follow-up</div>
                    </div>
                </div>
            </div>

            <!-- Footer Action Bar -->
            <div class="fcc-form-footer">
                <a href="{{ route('nutritionPanel.users.index') }}/demo" class="fcc-btn-outline">Cancel</a>
                <button type="button" class="fcc-btn-submit btn-submit-action">
                    <i class="fa fa-user-plus"></i> Create Demo User
                </button>
            </div>

        </div>

    {!! Form::close() !!}

</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/flatpickr.js') }}"></script>
<script>
    $(document).ready(function() {
        // Init flatpickr
        if (typeof flatpickr === "function") {
            $(".datepicker").flatpickr({
                dateFormat: "Y-m-d",
                defaultDate: "{{ date('Y-m-d') }}",
                onChange: function() {
                    updatePreview();
                }
            });
        }

        // Live preview sync
        function updatePreview() {
            var name = $('#user_name').val().trim();
            var coach = $('#coach_name').val().trim();
            var startDateVal = $('#start_date').val().trim();

            if (name !== '') {
                $('#liveName').text(name);
                var initials = name.split(' ').map(function(s) { return s[0]; }).join('').substring(0, 2).toUpperCase();
                $('#liveAvatar').html('<span>' + initials + '</span>');
            } else {
                $('#liveName').text('New Demo User');
                $('#liveAvatar').html('<i class="fa fa-user"></i>');
            }

            if (coach !== '') {
                $('#liveCoach').text(coach);
            } else {
                $('#liveCoach').text('Select a coach');
            }

            if (startDateVal) {
                var d = new Date(startDateVal);
                if (!isNaN(d.getTime())) {
                    var endD = new Date(d);
                    endD.setDate(endD.getDate() + 3);
                    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    var formattedStart = months[d.getMonth()] + ' ' + d.getDate();
                    var formattedEnd = months[endD.getMonth()] + ' ' + endD.getDate() + ', ' + endD.getFullYear();
                    $('#liveDuration').text(formattedStart + ' → ' + formattedEnd + ' (3 Days)');
                }
            }
        }

        // Email duplicate check
        $('#email').on('blur', function() {
            var email = $(this).val().trim();
            if (email) {
                $.ajax({
                    url: "{{ route('nutritionPanel.users.checkEmail') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        email: email
                    },
                    success: function(resp) {
                        if (resp === false || resp === 'false') {
                            $('#demo_email_error').text('This email address is already registered.').show();
                        } else {
                            $('#demo_email_error').text('').hide();
                        }
                    }
                });
            } else {
                $('#demo_email_error').text('').hide();
            }
        });

        // Mobile duplicate check
        $('#mobile_number').on('blur', function() {
            var mobile = $(this).val().trim();
            if (mobile) {
                $.ajax({
                    url: "{{ route('nutritionPanel.users.checkMobile') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        mobile_number: mobile
                    },
                    success: function(resp) {
                        if (resp === false || resp === 'false') {
                            $('#demo_mobile_error').text('This mobile number is already registered.').show();
                        } else {
                            $('#demo_mobile_error').text('').hide();
                        }
                    }
                });
            } else {
                $('#demo_mobile_error').text('').hide();
            }
        });

        // Input listeners
        $('.live-watcher').on('input change', updatePreview);
        updatePreview();

        // Submit form
        $('.btn-submit-action').on('click', function(e) {
            e.preventDefault();
            var $form = $('#demoUserForm');
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
