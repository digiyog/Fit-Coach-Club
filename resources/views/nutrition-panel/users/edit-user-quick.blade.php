@php
    $initials = '';
    $nameWords = explode(' ', trim($user->name ?? 'User'));
    foreach ($nameWords as $w) {
        if (!empty($w)) {
            $initials .= strtoupper(substr($w, 0, 1));
        }
    }
    $initials = substr($initials, 0, 2) ?: 'U';

    $profileImageUrl = null;
    if (!empty($user->profile_image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$user->profile_image)) {
        $profileImageUrl = get_image_url(config('constants.users.image_path'), $user->profile_image);
    } elseif (!empty($user->profile_image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path_thumb').$user->profile_image)) {
        $profileImageUrl = get_image_url(config('constants.users.image_path_thumb'), $user->profile_image);
    }
@endphp

<div class="modal-content fcc-qe-modal-content">
    <style type="text/css">
        .fcc-qe-modal-content {
            font-family: 'Outfit', sans-serif !important;
            border-radius: 18px !important;
            border: none !important;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
        }
        .fcc-qe-modal-header {
            padding: 22px 24px 18px 24px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
        }
        .fcc-qe-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .fcc-qe-icon-wrap {
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
        .fcc-qe-modal-title {
            font-size: 19px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.25;
        }
        .fcc-qe-modal-sub {
            font-size: 13px;
            color: #64748b;
            margin: 3px 0 0 0;
        }
        .fcc-qe-btn-close {
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
        .fcc-qe-btn-close:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
        }
        .fcc-qe-modal-body {
            padding: 24px !important;
            background: #ffffff;
        }
        .fcc-qe-member-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 20px;
            margin-bottom: 22px;
            border-bottom: 1px solid #f1f5f9;
        }
        .fcc-qe-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #dbeafe;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            flex-shrink: 0;
            overflow: hidden;
        }
        .fcc-qe-member-info {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .fcc-qe-name-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .fcc-qe-name {
            font-size: 16.5px;
            font-weight: 700;
            color: #0f172a;
        }
        .fcc-qe-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
        }
        .fcc-qe-status-badge.active {
            background: #dcfce7;
            color: #166534;
        }
        .fcc-qe-status-badge.inactive {
            background: #fee2e2;
            color: #991b1b;
        }
        .fcc-qe-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }
        .fcc-qe-status-badge.active .fcc-qe-status-dot { background: #16a34a; }
        .fcc-qe-status-badge.inactive .fcc-qe-status-dot { background: #dc2626; }
        .fcc-qe-email {
            font-size: 13px;
            color: #64748b;
        }
        .fcc-qe-form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px 20px;
        }
        @media (max-width: 575px) {
            .fcc-qe-form-grid {
                grid-template-columns: 1fr;
            }
        }
        .fcc-qe-form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .fcc-qe-label {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        .fcc-qe-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .fcc-qe-field-icon {
            position: absolute;
            left: 14px;
            color: #3b46f1;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            z-index: 2;
        }
        .fcc-qe-select {
            width: 100%;
            height: 48px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0 38px 0 44px;
            font-size: 13.5px;
            font-weight: 500;
            color: #0f172a;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            outline: none;
            transition: all 0.15s ease;
            cursor: pointer;
        }
        .fcc-qe-select:focus {
            border-color: #3b46f1;
            box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12);
        }
        .fcc-qe-select-chevron {
            position: absolute;
            right: 14px;
            color: #64748b;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .fcc-qe-info-note {
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            color: #1e40af;
            font-size: 12.5px;
            font-weight: 500;
        }
        .fcc-qe-info-note svg {
            color: #2563eb;
            flex-shrink: 0;
        }
        .fcc-qe-modal-footer {
            padding: 16px 24px 22px 24px !important;
            border-top: 1px solid #f1f5f9 !important;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            background: #ffffff;
        }
        .fcc-qe-btn-cancel {
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
        .fcc-qe-btn-cancel:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
        }
        .fcc-qe-btn-save {
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
        .fcc-qe-btn-save:hover {
            background: #2b35d8;
            border-color: #2b35d8;
            box-shadow: 0 6px 16px rgba(59, 70, 241, 0.35);
            transform: translateY(-1px);
        }
        .fcc-qe-form-group span.invalid-feedback {
            font-size: 11.5px;
            color: #dc2626;
            margin-top: 4px;
            display: block;
        }
    </style>

    <!-- Modal Header -->
    <div class="modal-header fcc-qe-modal-header">
        <div class="fcc-qe-header-left">
            <div class="fcc-qe-icon-wrap">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="21" x2="4" y2="14"></line>
                    <line x1="4" y1="10" x2="4" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12" y2="3"></line>
                    <line x1="20" y1="21" x2="20" y2="16"></line>
                    <line x1="20" y1="12" x2="20" y2="3"></line>
                    <line x1="1" y1="14" x2="7" y2="14"></line>
                    <line x1="9" y1="8" x2="15" y2="8"></line>
                    <line x1="17" y1="16" x2="23" y2="16"></line>
                </svg>
            </div>
            <div>
                <h4 class="fcc-qe-modal-title">Quick edit member</h4>
                <p class="fcc-qe-modal-sub">Update plan and account settings for {{ $user->name ?? 'Member' }}</p>
            </div>
        </div>
        <button type="button" class="fcc-qe-btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <!-- Modal Body with Form -->
    {!! Form::open(['class' => 'user-form', 'method' => 'post', 'url' => route('nutritionPanel.users.updateUserQuick', ['id' => ev($user->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
        <div class="modal-body fcc-qe-modal-body">
            
            <!-- Member Profile Info Bar -->
            <div class="fcc-qe-member-card">
                <div class="fcc-qe-avatar">
                    @if($profileImageUrl)
                        <img src="{{ $profileImageUrl }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" />
                    @else
                        <span>{{ $initials }}</span>
                    @endif
                </div>
                <div class="fcc-qe-member-info">
                    <div class="fcc-qe-name-row">
                        <span class="fcc-qe-name">{{ $user->name ?? 'Member' }}</span>
                        @if(($user->status ?? 1) == 1)
                            <span class="fcc-qe-status-badge active">
                                <span class="fcc-qe-status-dot"></span>
                                <span>Active</span>
                            </span>
                        @else
                            <span class="fcc-qe-status-badge inactive">
                                <span class="fcc-qe-status-dot"></span>
                                <span>Inactive</span>
                            </span>
                        @endif
                    </div>
                    <div class="fcc-qe-email">{{ $user->email ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- Form Grid: 4 Select Fields -->
            <div class="fcc-qe-form-grid">
                <!-- 1. Product Type -->
                <div class="fcc-qe-form-group">
                    <label class="fcc-qe-label" for="product_type_id">Product type</label>
                    <div class="fcc-qe-input-wrap">
                        <div class="fcc-qe-field-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <select name="product_type_id" id="product_type_id" class="fcc-qe-select">
                            <option value="">Select Product Type</option>
                            @foreach($productTypes as $pt)
                                <option value="{{ $pt->id }}" {{ ($user->product_type_id == $pt->id) ? 'selected' : '' }}>{{ $pt->name }}</option>
                            @endforeach
                        </select>
                        <div class="fcc-qe-select-chevron">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- 2. Meal Plan -->
                <div class="fcc-qe-form-group">
                    <label class="fcc-qe-label" for="meal_type_id">Meal plan</label>
                    <div class="fcc-qe-input-wrap">
                        <div class="fcc-qe-field-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                        </div>
                        <select name="meal_type_id" id="meal_type_id" class="fcc-qe-select">
                            <option value="">Select Meal Type</option>
                            @foreach($mealTypes as $mt)
                                <option value="{{ $mt->id }}" {{ ($user->meal_type_id == $mt->id) ? 'selected' : '' }}>{{ $mt->name }}</option>
                            @endforeach
                        </select>
                        <div class="fcc-qe-select-chevron">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- 3. User Type -->
                <div class="fcc-qe-form-group">
                    <label class="fcc-qe-label" for="user_type">User type</label>
                    <div class="fcc-qe-input-wrap">
                        <div class="fcc-qe-field-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <select name="user_type" id="user_type" class="fcc-qe-select">
                            <option value="">Select User Type</option>
                            @foreach(config('constants.user_type') as $ut)
                                <option value="{{ $ut['value'] }}" {{ ($user->user_type == $ut['value']) ? 'selected' : '' }}>{{ $ut['display'] }}</option>
                            @endforeach
                        </select>
                        <div class="fcc-qe-select-chevron">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- 4. User State -->
                <div class="fcc-qe-form-group">
                    <label class="fcc-qe-label" for="user_state">User state</label>
                    <div class="fcc-qe-input-wrap">
                        <div class="fcc-qe-field-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="2"></circle>
                                <path d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14"></path>
                            </svg>
                        </div>
                        <select name="user_state" id="user_state" class="fcc-qe-select">
                            <option value="">Select User State</option>
                            @foreach(config('constants.user_state') as $us)
                                <option value="{{ $us['value'] }}" {{ ($user->user_state == $us['value']) ? 'selected' : '' }}>{{ $us['display'] }}</option>
                            @endforeach
                        </select>
                        <div class="fcc-qe-select-chevron">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informational Note Banner -->
            <div class="fcc-qe-info-note">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span>Only plan and account settings will be updated.</span>
            </div>

        </div>

        <!-- Modal Footer Actions -->
        <div class="modal-footer fcc-qe-modal-footer">
            <button type="button" class="btn fcc-qe-btn-cancel" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn fcc-qe-btn-save btn-submit">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Save changes</span>
            </button>
        </div>
    {!! Form::close() !!}
</div>