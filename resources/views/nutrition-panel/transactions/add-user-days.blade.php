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

<div class="modal-content fcc-ad-modal-content">
    <style type="text/css">
        .fcc-ad-modal-content {
            font-family: 'Outfit', sans-serif !important;
            border-radius: 18px !important;
            border: none !important;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
        }
        .fcc-ad-modal-header {
            padding: 22px 24px 18px 24px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
        }
        .fcc-ad-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .fcc-ad-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #eff2fe;
            color: #3b46f1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .fcc-ad-modal-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.25;
        }
        .fcc-ad-modal-sub {
            font-size: 13px;
            color: #64748b;
            margin: 3px 0 0 0;
        }
        .fcc-ad-btn-close {
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
        .fcc-ad-btn-close:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        /* Member Profile Info Card */
        .fcc-ad-member-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .fcc-ad-avatar {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #eff2fe 0%, #e0e7ff 100%);
            border: 1.5px solid #c7d2fe;
            color: #3b46f1;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }
        .fcc-ad-member-info {
            flex-grow: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .fcc-ad-name-row {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .fcc-ad-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .fcc-ad-status-badge {
            font-size: 10.5px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .fcc-ad-status-badge.active {
            background: #dcfce7;
            color: #166534;
        }
        .fcc-ad-status-badge.inactive {
            background: #fee2e2;
            color: #991b1b;
        }
        .fcc-ad-status-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }
        .fcc-ad-meta-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #64748b;
            flex-wrap: wrap;
        }
        .fcc-ad-dot-sep {
            color: #cbd5e1;
        }

        .fcc-ad-modal-body {
            padding: 24px !important;
            background: #ffffff;
        }
        .fcc-ad-form-stack {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .fcc-ad-form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .fcc-ad-label {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        .fcc-ad-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .fcc-ad-field-icon {
            position: absolute !important;
            left: 16px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: #3b46f1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            pointer-events: none !important;
            z-index: 5 !important;
        }
        .fcc-ad-field-currency {
            position: absolute !important;
            left: 16px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: #334155 !important;
            font-weight: 700 !important;
            font-size: 16px !important;
            pointer-events: none !important;
            z-index: 5 !important;
            line-height: 1 !important;
        }
        .fcc-ad-select,
        select.fcc-ad-select {
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
        .fcc-ad-select:focus,
        select.fcc-ad-select:focus {
            border-color: #3b46f1 !important;
            box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12) !important;
        }
        .fcc-ad-select-chevron {
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
        .fcc-ad-input,
        input.fcc-ad-input,
        input[type="number"].fcc-ad-input {
            width: 100% !important;
            height: 48px !important;
            background: #ffffff !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 10px !important;
            padding-left: 46px !important;
            padding-right: 14px !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            font-size: 13.5px !important;
            font-weight: 500 !important;
            color: #0f172a !important;
            outline: none !important;
            box-shadow: none !important;
            box-sizing: border-box !important;
            transition: all 0.15s ease !important;
        }
        .fcc-ad-input:focus,
        input.fcc-ad-input:focus,
        input[type="number"].fcc-ad-input:focus {
            border-color: #3b46f1 !important;
            box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12) !important;
        }
        .fcc-ad-input::placeholder,
        input.fcc-ad-input::placeholder,
        input[type="number"].fcc-ad-input::placeholder {
            color: #94a3b8 !important;
            font-size: 13px !important;
        }
        .fcc-ad-hint {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }
        .fcc-ad-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        @media (max-width: 575px) {
            .fcc-ad-grid-2 {
                grid-template-columns: 1fr;
            }
        }
        .fcc-ad-textarea-wrap {
            position: relative !important;
        }
        .fcc-ad-textarea-wrap .fcc-ad-field-icon {
            position: absolute !important;
            top: 14px !important;
            left: 16px !important;
            transform: none !important;
            color: #3b46f1 !important;
            display: flex !important;
            align-items: flex-start !important;
            justify-content: center !important;
            pointer-events: none !important;
            z-index: 5 !important;
        }
        .fcc-ad-textarea,
        textarea.fcc-ad-textarea {
            width: 100% !important;
            min-height: 80px !important;
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
        .fcc-ad-textarea:focus,
        textarea.fcc-ad-textarea:focus {
            border-color: #3b46f1 !important;
            box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12) !important;
        }
        .fcc-ad-textarea::placeholder,
        textarea.fcc-ad-textarea::placeholder {
            color: #94a3b8 !important;
            font-size: 13px !important;
        }
        .fcc-ad-info-note {
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1e40af;
            font-size: 12.5px;
            font-weight: 500;
        }
        .fcc-ad-info-note svg {
            color: #2563eb;
            flex-shrink: 0;
        }
        .fcc-ad-modal-footer {
            padding: 16px 24px 22px 24px !important;
            border-top: 1px solid #f1f5f9 !important;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            background: #ffffff;
        }
        .fcc-ad-btn-cancel {
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
        .fcc-ad-btn-cancel:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
        }
        .fcc-ad-btn-save {
            background: #3b46f1;
            border: 1.5px solid #3b46f1;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 13.5px;
            padding: 9px 24px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(59, 70, 241, 0.25);
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .fcc-ad-btn-save:hover {
            background: #2b35d8;
            border-color: #2b35d8;
            box-shadow: 0 6px 16px rgba(59, 70, 241, 0.35);
            transform: translateY(-1px);
        }
        .fcc-ad-form-group span.invalid-feedback {
            font-size: 11.5px;
            color: #dc2626;
            margin-top: 4px;
            display: block;
        }
    </style>

    <!-- Modal Header -->
    <div class="modal-header fcc-ad-modal-header">
        <div class="fcc-ad-header-left">
            <div class="fcc-ad-icon-wrap">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                    <line x1="10" y1="16" x2="14" y2="16"></line>
                    <line x1="12" y1="14" x2="12" y2="18"></line>
                </svg>
            </div>
            <div>
                <h4 class="fcc-ad-modal-title">Add member days — <span style="color: #3b46f1;">{{ $user->name ?? 'Member' }}</span></h4>
                <p class="fcc-ad-modal-sub">Extend plan access and record payment for <strong>{{ $user->name ?? 'Member' }}</strong></p>
            </div>
        </div>
        <button type="button" class="fcc-ad-btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <!-- Modal Body with Form -->
    {!! Form::open(['class' => 'add-user-days-form', 'method' => 'post', 'url' => route('nutritionPanel.users.updateUserDays', ['id' => ev($user->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
        <input type="hidden" name="payment_type" value="Received" />
        
        <div class="modal-body fcc-ad-modal-body">
            <div class="fcc-ad-form-stack">

                <!-- Member Profile Info Bar -->
                <div class="fcc-ad-member-card">
                    <div class="fcc-ad-avatar">
                        @if($profileImageUrl)
                            <img src="{{ $profileImageUrl }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block;" />
                        @else
                            <span>{{ $initials }}</span>
                        @endif
                    </div>
                    <div class="fcc-ad-member-info">
                        <div class="fcc-ad-name-row">
                            <span class="fcc-ad-name">{{ $user->name ?? 'Member' }}</span>
                            @if(($user->status ?? 1) == 1)
                                <span class="fcc-ad-status-badge active">
                                    <span class="fcc-ad-status-dot"></span>
                                    <span>Active</span>
                                </span>
                            @else
                                <span class="fcc-ad-status-badge inactive">
                                    <span class="fcc-ad-status-dot"></span>
                                    <span>Inactive</span>
                                </span>
                            @endif
                            <span class="badge" style="background: #eff2fe; color: #3b46f1; font-size: 10.5px; font-weight: 600; padding: 2px 7px; border-radius: 6px;">{{ $user->user_type ?? 'Regular User' }}</span>
                        </div>
                        <div class="fcc-ad-meta-row">
                            @if(!empty($user->mobile_number))
                                <span><i class="fa fa-phone me-1" style="font-size: 11px;"></i>{{ $user->mobile_number }}</span>
                                <span class="fcc-ad-dot-sep">•</span>
                            @endif
                            <span><i class="fa fa-clock-o me-1" style="font-size: 11px;"></i>Current balance: <strong>{{ (int)($user->days ?? 0) }} days</strong></span>
                        </div>
                    </div>
                </div>

                <!-- 1. Days to add -->
                <div class="fcc-ad-form-group">
                    <label class="fcc-ad-label" for="days">Days to add</label>
                    <div class="fcc-ad-input-wrap">
                        <div class="fcc-ad-field-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <select name="days" id="days" class="fcc-ad-select">
                            @for($i = 1; $i <= 60; $i++)
                                <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        <div class="fcc-ad-select-chevron">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="fcc-ad-hint">Select the number of days to extend the membership.</div>
                </div>

                <!-- 2. Total amount & Received amount (2 Columns) -->
                <div class="fcc-ad-grid-2">
                    <div class="fcc-ad-form-group">
                        <label class="fcc-ad-label" for="amount">Total amount</label>
                        <div class="fcc-ad-input-wrap">
                            <span class="fcc-ad-field-currency">₹</span>
                            <input type="number" step="any" name="amount" id="amount" class="fcc-ad-input" placeholder="Enter total amount" />
                        </div>
                    </div>

                    <div class="fcc-ad-form-group">
                        <label class="fcc-ad-label" for="received_amount">Received amount</label>
                        <div class="fcc-ad-input-wrap">
                            <span class="fcc-ad-field-currency">₹</span>
                            <input type="number" step="any" name="received_amount" id="received_amount" class="fcc-ad-input" placeholder="Enter received amount" />
                        </div>
                    </div>
                </div>

                <!-- 3. Remark -->
                <div class="fcc-ad-form-group">
                    <label class="fcc-ad-label" for="remark">Remark</label>
                    <div class="fcc-ad-textarea-wrap">
                        <div class="fcc-ad-field-icon">
                            <svg viewBox="0 0 24 24" width="17" height="17" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                        </div>
                        <textarea name="remark" id="remark" class="fcc-ad-textarea" placeholder="Add a note about this extension (optional)"></textarea>
                    </div>
                </div>

                <!-- 4. Informational Note -->
                <div class="fcc-ad-info-note">
                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span>Payment details will be saved with the membership extension.</span>
                </div>

            </div>
        </div>

        <!-- Modal Footer Actions -->
        <div class="modal-footer fcc-ad-modal-footer">
            <button type="button" class="btn fcc-ad-btn-cancel" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn fcc-ad-btn-save btn-submit">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                    <line x1="10" y1="16" x2="14" y2="16"></line>
                    <line x1="12" y1="14" x2="12" y2="18"></line>
                </svg>
                <span>Add days</span>
            </button>
        </div>
    {!! Form::close() !!}
</div>