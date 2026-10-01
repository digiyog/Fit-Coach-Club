{{-- App Access (On/Off) Widget & Inactive Banner Component --}}
@php
    $appUserStatus = (int)($user->status ?? 1);
    $isAppActive = ($appUserStatus === 1);
    $userEncryptedId = $userEncryptedId ?? ev($user->id);
    $userName = $user->name ?? 'Member';
    $changeStatusUrl = route('nutritionPanel.users.changeStatus');
    $currentSection = $section ?? 'all';
@endphp

@once
@push('styles')
<style type="text/css">
    /* App Access Widget Styles */
    .fcc-app-access-widget {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 6px 14px;
        border-radius: 12px;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        margin-bottom: 8px;
    }
    .fcc-app-access-widget.is-active {
        background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
        border-color: #bbf7d0;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.08);
    }
    .fcc-app-access-widget.is-inactive {
        background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
        border-color: #fecaca;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.08);
    }

    .fcc-app-access-icon-box {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }
    .fcc-app-access-widget.is-active .fcc-app-access-icon-box {
        background: #dcfce7;
        color: #15803d;
    }
    .fcc-app-access-widget.is-inactive .fcc-app-access-icon-box {
        background: #fee2e2;
        color: #dc2626;
    }
    .fcc-app-access-icon-box svg, .fcc-app-access-icon-box i {
        width: 17px;
        height: 17px;
    }

    .fcc-app-access-meta {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }
    .fcc-app-access-title-row {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 2px;
    }
    .fcc-app-access-title {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    /* Live status badge */
    .fcc-app-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 2px 7px;
        border-radius: 999px;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        transition: all 0.2s ease;
    }
    .fcc-app-status-badge.active {
        background: #dcfce7;
        color: #15803d;
    }
    .fcc-app-status-badge.inactive {
        background: #fee2e2;
        color: #dc2626;
    }
    .fcc-app-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .fcc-app-status-badge.active .fcc-app-status-dot {
        background: #16a34a;
        box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.25);
        animation: fccStatusPulse 2s infinite;
    }
    .fcc-app-status-badge.inactive .fcc-app-status-dot {
        background: #dc2626;
    }

    @keyframes fccStatusPulse {
        0% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.6); }
        70% { box-shadow: 0 0 0 5px rgba(22, 163, 74, 0); }
        100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
    }

    .fcc-app-access-subtext {
        font-size: 11px;
        color: #64748b;
        font-weight: 500;
    }

    /* Modern iOS-style Switch */
    .fcc-app-toggle-wrapper {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-left: 4px;
    }
    .fcc-app-toggle-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.04em;
        transition: color 0.15s ease;
        user-select: none;
    }
    .fcc-app-toggle-label.off {
        color: #94a3b8;
    }
    .fcc-app-toggle-label.off.active {
        color: #dc2626;
    }
    .fcc-app-toggle-label.on {
        color: #94a3b8;
    }
    .fcc-app-toggle-label.on.active {
        color: #16a34a;
    }

    .fcc-ios-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        margin-bottom: 0;
        cursor: pointer;
    }
    .fcc-ios-switch input {
        opacity: 0;
        width: 0;
        height: 0;
        position: absolute;
    }
    .fcc-ios-slider {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 34px;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
    }
    .fcc-ios-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0,0,0,0.22);
    }
    .fcc-ios-switch input:checked + .fcc-ios-slider {
        background-color: #10b981;
    }
    .fcc-ios-switch input:checked + .fcc-ios-slider:before {
        transform: translateX(20px);
    }
    .fcc-ios-switch.is-busy {
        pointer-events: none;
        opacity: 0.6;
    }

    /* Navigation Bar Container: holds tabs on left & widget on right */
    .fcc-member-nav-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        border-bottom: 1.5px solid #e2e8f0;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .fcc-member-nav-row .fcc-member-nav-tabs {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        flex: 1 1 auto;
        min-width: 0;
    }
    .fcc-member-nav-row .fcc-app-access-widget {
        margin-bottom: 8px;
        flex-shrink: 0;
    }

    /* Inactive Alert Banner */
    .fcc-app-disabled-alert {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
        border: 1.5px solid #fecdd3;
        border-radius: 14px;
        padding: 13px 18px;
        margin-bottom: 22px;
        box-shadow: 0 2px 8px rgba(225, 29, 72, 0.06);
        transition: all 0.2s ease;
    }
    .fcc-app-disabled-alert-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .fcc-app-disabled-alert-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #fee2e2;
        color: #e11d48;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .fcc-app-disabled-alert-icon svg, .fcc-app-disabled-alert-icon i {
        width: 19px;
        height: 19px;
    }
    .fcc-app-disabled-alert-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #9f1239;
        margin-bottom: 2px;
    }
    .fcc-app-disabled-alert-desc {
        font-size: 12.5px;
        color: #881337;
        margin-bottom: 0;
        line-height: 1.4;
    }
    .fcc-btn-enable-app-quick {
        background: #10b981;
        border: 1.5px solid #10b981;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 16px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25);
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.15s ease;
    }
    .fcc-btn-enable-app-quick:hover {
        background: #059669;
        border-color: #059669;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.32);
    }
    .fcc-btn-enable-app-quick svg, .fcc-btn-enable-app-quick i {
        width: 15px;
        height: 15px;
    }

    /* Header quick pill */
    .fcc-header-app-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 13px;
        border-radius: 9px;
        font-size: 12.5px;
        font-weight: 600;
        border: 1.5px solid transparent;
        transition: all 0.15s ease;
    }
    .fcc-header-app-pill.active {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: #047857;
    }
    .fcc-header-app-pill.inactive {
        background: #fef2f2;
        border-color: #fecaca;
        color: #b91c1c;
    }
    .fcc-header-app-pill .pill-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }
    .fcc-header-app-pill.active .pill-dot {
        background: #10b981;
    }
    .fcc-header-app-pill.inactive .pill-dot {
        background: #ef4444;
    }

    @media (max-width: 768px) {
        .fcc-member-nav-row {
            flex-direction: column;
            align-items: flex-start;
        }
        .fcc-app-access-widget {
            width: 100%;
            justify-content: space-between;
        }
        .fcc-app-disabled-alert {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush
@endonce

{{-- 1. Widget Markup --}}
@if($currentSection === 'all' || $currentSection === 'widget')
<div class="fcc-app-access-widget {{ $isAppActive ? 'is-active' : 'is-inactive' }}" id="fccAppAccessWidget" data-user-id="{{ $user->id }}" data-change-url="{{ $changeStatusUrl }}">
    <div class="fcc-app-access-icon-box">
        <i data-feather="smartphone"></i>
    </div>
    <div class="fcc-app-access-meta">
        <div class="fcc-app-access-title-row">
            <span class="fcc-app-access-title">App Login</span>
            <span class="fcc-app-status-badge {{ $isAppActive ? 'active' : 'inactive' }}" id="fccAppStatusBadge">
                <span class="fcc-app-status-dot"></span>
                <span id="fccAppStatusBadgeText">{{ $isAppActive ? 'ON · Active' : 'OFF · Inactive' }}</span>
            </span>
        </div>
        <span class="fcc-app-access-subtext" id="fccAppAccessSubtext">
            {{ $isAppActive ? 'User can log in to mobile app' : 'User cannot log in to app' }}
        </span>
    </div>
    <div class="fcc-app-toggle-wrapper">
        <span class="fcc-app-toggle-label off {{ !$isAppActive ? 'active' : '' }}" id="fccToggleLabelOff">OFF</span>
        <label class="fcc-ios-switch" id="fccIosSwitchContainer" title="Turn App Access ON / OFF for {{ $userName }}">
            <input type="checkbox" id="fccAppAccessToggle" {{ $isAppActive ? 'checked' : '' }} data-user-id="{{ $user->id }}" data-user-name="{{ $userName }}">
            <span class="fcc-ios-slider"></span>
        </label>
        <span class="fcc-app-toggle-label on {{ $isAppActive ? 'active' : '' }}" id="fccToggleLabelOn">ON</span>
    </div>
</div>
@endif

{{-- 2. Banner & Modal Markup --}}
@if($currentSection === 'all' || $currentSection === 'banner')
<div class="fcc-app-disabled-alert" id="fccAppDisabledAlert" style="{{ !$isAppActive ? 'display: flex;' : 'display: none;' }}">
    <div class="fcc-app-disabled-alert-left">
        <div class="fcc-app-disabled-alert-icon">
            <i data-feather="shield-off"></i>
        </div>
        <div>
            <div class="fcc-app-disabled-alert-title">Mobile App Access is OFF (User Inactive)</div>
            <p class="fcc-app-disabled-alert-desc">
                This member is currently set to <strong>Inactive</strong>. They are prevented from logging in to the mobile app, and all previous mobile sessions have been signed out.
            </p>
        </div>
    </div>
    <div>
        <button type="button" class="btn fcc-btn-enable-app-quick" id="fccBtnEnableAppQuick">
            <i data-feather="check-circle"></i>
            <span>Turn App Access ON</span>
        </button>
    </div>
</div>

<div class="modal fade" id="fccConfirmAppOffModal" tabindex="-1" role="dialog" aria-labelledby="fccConfirmAppOffModalLabel" aria-hidden="true" style="font-family: 'Outfit', sans-serif;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 440px;">
        <div class="modal-content" style="border-radius: 18px; border: none; box-shadow: 0 20px 45px rgba(15, 23, 42, 0.18); overflow: hidden;">
            <div class="modal-body p-4 text-center">
                <div style="width: 58px; height: 58px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                    <i data-feather="user-x" style="width: 26px; height: 26px;"></i>
                </div>
                <h4 class="fw-bold mb-2" id="fccConfirmAppOffModalLabel" style="color: #0f172a; font-size: 19px;">Disable App Access?</h4>
                <p style="font-size: 13.5px; color: #64748b; line-height: 1.5; margin-bottom: 22px;">
                    Are you sure you want to turn <strong>OFF</strong> app access for <strong class="text-dark">{{ $userName }}</strong>?<br>
                    The member will be <strong>logged out immediately</strong> from their mobile app and will <strong>not be able to log in</strong>.
                </p>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-dismiss="modal" id="fccCancelAppOffBtn" style="padding: 9px 22px; border-radius: 10px; font-weight: 600; font-size: 13.5px; border: 1.5px solid #e2e8f0; color: #475569;">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="fccConfirmAppOffBtn" style="padding: 9px 24px; border-radius: 10px; font-weight: 600; font-size: 13.5px; background: #e11d48; border-color: #e11d48; box-shadow: 0 4px 12px rgba(225, 29, 72, 0.28);">
                        Yes, Turn OFF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@once
@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        var $widget = $('#fccAppAccessWidget');
        var $toggle = $('#fccAppAccessToggle');
        var $switchWrap = $('#fccIosSwitchContainer');
        var $badge = $('#fccAppStatusBadge');
        var $badgeText = $('#fccAppStatusBadgeText');
        var $subtext = $('#fccAppAccessSubtext');
        var $labelOff = $('#fccToggleLabelOff');
        var $labelOn = $('#fccToggleLabelOn');
        var $alertBanner = $('#fccAppDisabledAlert');
        var $headerPill = $('#fccHeaderAppPill');
        var $confirmModal = $('#fccConfirmAppOffModal');
        var changeUrl = $widget.data('change-url');
        var userId = $widget.data('user-id');

        function updateUIState(isActive) {
            if (isActive) {
                $toggle.prop('checked', true);
                $widget.removeClass('is-inactive').addClass('is-active');
                $badge.removeClass('inactive').addClass('active');
                $badgeText.text('ON · Active');
                $subtext.text('User can log in to mobile app');
                $labelOff.removeClass('active');
                $labelOn.addClass('active');
                $alertBanner.slideUp(200);
                if ($headerPill.length) {
                    $headerPill.removeClass('inactive').addClass('active');
                    $headerPill.find('.pill-text').text('App: ON');
                }
                var $sidebarBadge = $('#fccSidebarStatusBadge');
                if ($sidebarBadge.length) {
                    $sidebarBadge.removeClass('inactive').addClass('active').html('<span class="fcc-status-dot"></span><span>Active</span>');
                }
            } else {
                $toggle.prop('checked', false);
                $widget.removeClass('is-active').addClass('is-inactive');
                $badge.removeClass('active').addClass('inactive');
                $badgeText.text('OFF · Inactive');
                $subtext.text('User cannot log in to app');
                $labelOff.addClass('active');
                $labelOn.removeClass('active');
                $alertBanner.slideDown(200);
                if ($headerPill.length) {
                    $headerPill.removeClass('active').addClass('inactive');
                    $headerPill.find('.pill-text').text('App: OFF');
                }
                var $sidebarBadge = $('#fccSidebarStatusBadge');
                if ($sidebarBadge.length) {
                    $sidebarBadge.removeClass('active').addClass('inactive').html('<span class="fcc-status-dot"></span><span>Inactive</span>');
                }
            }

            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }

        function sendStatusChange(targetStatus) {
            $switchWrap.addClass('is-busy');

            $.ajax({
                url: changeUrl,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
                    ids: [userId],
                    status: targetStatus
                },
                success: function(response) {
                    $switchWrap.removeClass('is-busy');

                    if (response && response._status) {
                        var newStatus = response.new_status !== undefined ? response.new_status : targetStatus;
                        updateUIState(newStatus == 1);

                        if (typeof iziToast !== 'undefined') {
                            iziToast.success({
                                title: newStatus == 1 ? 'App Access Enabled' : 'App Access Disabled',
                                message: response._message || (newStatus == 1 ? 'Member can now log in to the mobile app.' : 'Member logged out and cannot log in.'),
                                position: 'topRight',
                                timeout: 4000
                            });
                        } else if (typeof App !== 'undefined' && App.showNotification) {
                            App.showNotification(response);
                        }
                    } else {
                        // Revert checkbox state
                        $toggle.prop('checked', targetStatus == 0);
                        var errMsg = (response && response._message) ? response._message : 'Failed to update app access.';
                        if (typeof iziToast !== 'undefined') {
                            iziToast.error({ title: 'Error', message: errMsg, position: 'topRight' });
                        } else {
                            alert(errMsg);
                        }
                    }
                },
                error: function(xhr) {
                    $switchWrap.removeClass('is-busy');
                    // Revert checkbox state
                    $toggle.prop('checked', targetStatus == 0);
                    var msg = 'An error occurred while updating app access.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    if (typeof iziToast !== 'undefined') {
                        iziToast.error({ title: 'Error', message: msg, position: 'topRight' });
                    } else {
                        alert(msg);
                    }
                }
            });
        }

        // Toggle Click Handling
        $toggle.on('change', function(e) {
            var willBeActive = $(this).is(':checked');

            if (!willBeActive) {
                // Coach wants to turn OFF -> Show confirmation modal first to avoid accidental lockout
                e.preventDefault();
                // Temporarily keep it checked visually until modal confirms
                $(this).prop('checked', true);
                if (typeof $confirmModal.modal === 'function') {
                    $confirmModal.modal('show');
                } else {
                    if (confirm('Are you sure you want to turn OFF app access? The member will be signed out immediately and will not be able to log in to the mobile app.')) {
                        sendStatusChange(0);
                    }
                }
            } else {
                // Coach wants to turn ON -> Proceed directly
                sendStatusChange(1);
            }
        });

        // Modal "Yes, Turn OFF" confirmed
        $('#fccConfirmAppOffBtn').on('click', function() {
            if (typeof $confirmModal.modal === 'function') {
                $confirmModal.modal('hide');
            }
            sendStatusChange(0);
        });

        // Modal Cancelled
        $('#fccCancelAppOffBtn').on('click', function() {
            $toggle.prop('checked', true);
        });

        // Quick "Turn App Access ON" button from Inactive Alert banner
        $('#fccBtnEnableAppQuick').on('click', function(e) {
            e.preventDefault();
            sendStatusChange(1);
        });
    });
</script>
@endpush
@endonce
