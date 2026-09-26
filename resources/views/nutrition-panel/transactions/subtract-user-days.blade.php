@php
    $profileImageUrl = null;
    if (!empty($user->profile_image)) {
        if (\Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$user->profile_image)) {
            $profileImageUrl = get_image_url(config('constants.users.image_path'), $user->profile_image);
        } elseif (\Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path_thumb').$user->profile_image)) {
            $profileImageUrl = get_image_url(config('constants.users.image_path_thumb'), $user->profile_image);
        } else {
            $profileImageUrl = get_image_url(config('constants.users.image_path'), $user->profile_image);
        }
    }

    $initials = '';
    if (!empty($user->name)) {
        $nameWords = explode(' ', trim($user->name));
        foreach ($nameWords as $w) {
            if (!empty($w)) {
                $initials .= strtoupper(substr($w, 0, 1));
            }
        }
        $initials = substr($initials, 0, 2);
    }
    if (empty($initials)) $initials = 'U';
@endphp

<div class="modal-content fcc-sd-modal-content">
    <style type="text/css">
        .fcc-sd-modal-content {
            font-family: 'Outfit', sans-serif !important;
            border-radius: 18px !important;
            border: none !important;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
        }
        .fcc-sd-modal-header {
            padding: 22px 24px 18px 24px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
        }
        .fcc-sd-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .fcc-sd-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #fee2e2;
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .fcc-sd-modal-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.25;
        }
        .fcc-sd-modal-sub {
            font-size: 13px;
            color: #64748b;
            margin: 3px 0 0 0;
        }
        .fcc-sd-btn-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
            padding: 0;
        }
        .fcc-sd-btn-close:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        /* Member Profile Info Card */
        .fcc-sd-member-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .fcc-sd-avatar {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 1.5px solid #fca5a5;
            color: #dc2626;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }
        .fcc-sd-member-info {
            flex-grow: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .fcc-sd-name-row {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .fcc-sd-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .fcc-sd-status-badge {
            font-size: 10.5px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .fcc-sd-status-badge.active {
            background: #dcfce7;
            color: #166534;
        }
        .fcc-sd-status-badge.inactive {
            background: #fee2e2;
            color: #991b1b;
        }
        .fcc-sd-status-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }
        .fcc-sd-meta-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #64748b;
            flex-wrap: wrap;
        }
        .fcc-sd-dot-sep {
            color: #cbd5e1;
        }

        .fcc-sd-modal-body {
            padding: 24px !important;
            background: #ffffff;
        }
        .fcc-sd-form-stack {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .fcc-sd-form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .fcc-sd-label {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        .fcc-sd-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .fcc-sd-field-icon {
            position: absolute !important;
            left: 16px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: #ef4444 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            pointer-events: none !important;
            z-index: 5 !important;
        }
        .fcc-sd-select,
        select.fcc-sd-select {
            width: 100% !important;
            height: 48px !important;
            background: #ffffff !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 10px !important;
            padding-left: 48px !important;
            padding-right: 38px !important;
            font-size: 13.5px !important;
            font-weight: 500 !important;
            color: #0f172a !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            outline: none !important;
            box-shadow: none !important;
            box-sizing: border-box !important;
            transition: all 0.15s ease !important;
            cursor: pointer !important;
        }
        .fcc-sd-select:focus,
        select.fcc-sd-select:focus {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12) !important;
        }
        .fcc-sd-select-chevron {
            position: absolute !important;
            right: 14px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: #64748b !important;
            pointer-events: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            z-index: 5 !important;
        }
        .fcc-sd-hint {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }
        .fcc-sd-textarea-wrap {
            position: relative !important;
        }
        .fcc-sd-textarea-wrap .fcc-sd-field-icon {
            position: absolute !important;
            top: 14px !important;
            left: 16px !important;
            transform: none !important;
            color: #ef4444 !important;
            display: flex !important;
            align-items: flex-start !important;
            justify-content: center !important;
            pointer-events: none !important;
            z-index: 5 !important;
        }
        .fcc-sd-textarea,
        textarea.fcc-sd-textarea {
            width: 100% !important;
            min-height: 85px !important;
            background: #ffffff !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 10px !important;
            padding-top: 14px !important;
            padding-bottom: 12px !important;
            padding-right: 14px !important;
            padding-left: 48px !important;
            font-size: 13.5px !important;
            font-weight: 500 !important;
            color: #0f172a !important;
            outline: none !important;
            box-shadow: none !important;
            resize: vertical !important;
            box-sizing: border-box !important;
            transition: all 0.15s ease !important;
            font-family: inherit !important;
        }
        .fcc-sd-textarea:focus,
        textarea.fcc-sd-textarea:focus {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12) !important;
        }
        .fcc-sd-textarea::placeholder,
        textarea.fcc-sd-textarea::placeholder {
            color: #94a3b8 !important;
            font-size: 13px !important;
        }
        .fcc-sd-warning-note {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #991b1b;
            font-size: 12.5px;
            font-weight: 500;
        }
        .fcc-sd-warning-note svg {
            color: #ef4444;
            flex-shrink: 0;
        }
        .fcc-sd-modal-footer {
            padding: 16px 24px 22px 24px !important;
            border-top: 1px solid #f1f5f9 !important;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            background: #ffffff;
        }
        .fcc-sd-btn-cancel {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            color: #334155;
            font-weight: 600;
            font-size: 13.5px;
            padding: 9px 24px;
            border-radius: 9px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .fcc-sd-btn-cancel:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
        }
        .fcc-sd-btn-save {
            background: #ef4444;
            border: 1.5px solid #ef4444;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 13.5px;
            padding: 9px 24px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .fcc-sd-btn-save:hover {
            background: #dc2626;
            border-color: #dc2626;
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.35);
            transform: translateY(-1px);
        }
        .fcc-sd-form-group span.invalid-feedback {
            font-size: 11.5px;
            color: #dc2626;
            margin-top: 4px;
            display: block;
        }
    </style>

    <!-- Modal Header -->
    <div class="modal-header fcc-sd-modal-header">
        <div class="fcc-sd-header-left">
            <div class="fcc-sd-icon-wrap">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                    <line x1="9" y1="16" x2="15" y2="16"></line>
                </svg>
            </div>
            <div>
                <h4 class="fcc-sd-modal-title">Subtract member days — <span style="color: #ef4444;">{{ $user->name ?? 'Member' }}</span></h4>
                <p class="fcc-sd-modal-sub">Reduce remaining plan access for <strong>{{ $user->name ?? 'Member' }}</strong></p>
            </div>
        </div>
        <button type="button" class="fcc-sd-btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <!-- Modal Body with Form -->
    {!! Form::open(['class' => 'subtract-user-days-form', 'method' => 'post', 'url' => route('nutritionPanel.users.updateSubtractUserDays', ['id' => ev($user->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
        <div class="modal-body fcc-sd-modal-body">
            <div class="fcc-sd-form-stack">

                <!-- Member Profile Info Bar -->
                <div class="fcc-sd-member-card">
                    <div class="fcc-sd-avatar">
                        @if($profileImageUrl)
                            <img src="{{ $profileImageUrl }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block;" />
                        @else
                            <span>{{ $initials }}</span>
                        @endif
                    </div>
                    <div class="fcc-sd-member-info">
                        <div class="fcc-sd-name-row">
                            <span class="fcc-sd-name">{{ $user->name ?? 'Member' }}</span>
                            @if(($user->status ?? 1) == 1)
                                <span class="fcc-sd-status-badge active">
                                    <span class="fcc-sd-status-dot"></span>
                                    <span>Active</span>
                                </span>
                            @else
                                <span class="fcc-sd-status-badge inactive">
                                    <span class="fcc-sd-status-dot"></span>
                                    <span>Inactive</span>
                                </span>
                            @endif
                            <span class="badge" style="background: #fee2e2; color: #dc2626; font-size: 10.5px; font-weight: 600; padding: 2px 7px; border-radius: 6px;">{{ $user->user_type ?? 'Regular User' }}</span>
                        </div>
                        <div class="fcc-sd-meta-row">
                            @if(!empty($user->mobile_number))
                                <span><i class="fa fa-phone me-1" style="font-size: 11px;"></i>{{ $user->mobile_number }}</span>
                                <span class="fcc-sd-dot-sep">•</span>
                            @endif
                            <span><i class="fa fa-clock-o me-1" style="font-size: 11px;"></i>Current balance: <strong>{{ (int)($user->days ?? 0) }} days</strong></span>
                        </div>
                    </div>
                </div>

                <!-- 1. Days to subtract -->
                <div class="fcc-sd-form-group">
                    <label class="fcc-sd-label" for="days">Days to subtract</label>
                    <div class="fcc-sd-input-wrap">
                        <div class="fcc-sd-field-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                <line x1="9" y1="16" x2="15" y2="16"></line>
                            </svg>
                        </div>
                        @php
                            $maxDays = max(1, (int)($user->days ?? 30));
                        @endphp
                        <select name="days" id="days" class="fcc-sd-select" onchange="updateSubtractHint(this.value)">
                            @for($i = 1; $i <= $maxDays; $i++)
                                <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        <div class="fcc-sd-select-chevron">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="fcc-sd-hint" id="fccSubtractHint">This will reduce the remaining membership duration by 1 day.</div>
                </div>

                <!-- 2. Remark -->
                <div class="fcc-sd-form-group">
                    <label class="fcc-sd-label" for="remark">Remark</label>
                    <div class="fcc-sd-textarea-wrap">
                        <div class="fcc-sd-field-icon">
                            <svg viewBox="0 0 24 24" width="17" height="17" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                        </div>
                        <textarea name="remark" id="remark" class="fcc-sd-textarea" placeholder="Add a reason for this adjustment (optional)"></textarea>
                    </div>
                </div>

                <!-- 3. Warning Alert Box -->
                <div class="fcc-sd-warning-note">
                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>The selected days will be removed from the active membership.</span>
                </div>

            </div>
        </div>

        <!-- Modal Footer Actions -->
        <div class="modal-footer fcc-sd-modal-footer">
            <button type="button" class="btn fcc-sd-btn-cancel" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn fcc-sd-btn-save btn-submit">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                    <line x1="9" y1="16" x2="15" y2="16"></line>
                </svg>
                <span>Subtract days</span>
            </button>
        </div>
    {!! Form::close() !!}
</div>

<script type="text/javascript">
    function updateSubtractHint(val) {
        var daysText = val == 1 ? '1 day' : val + ' days';
        var hintEl = document.getElementById('fccSubtractHint');
        if (hintEl) {
            hintEl.innerText = 'This will reduce the remaining membership duration by ' + daysText + '.';
        }
    }
</script>