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
            font-size: 19px;
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
            position: absolute;
            left: 14px;
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            z-index: 2;
        }
        .fcc-sd-select {
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
        .fcc-sd-select:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
        }
        .fcc-sd-select-chevron {
            position: absolute;
            right: 14px;
            color: #64748b;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .fcc-sd-hint {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }
        .fcc-sd-textarea-wrap {
            position: relative;
        }
        .fcc-sd-textarea-wrap .fcc-sd-field-icon {
            top: 14px;
            color: #3b46f1;
            align-items: flex-start;
        }
        .fcc-sd-textarea {
            width: 100%;
            min-height: 80px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 14px 12px 42px;
            font-size: 13.5px;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            resize: vertical;
            transition: all 0.15s ease;
            font-family: inherit;
        }
        .fcc-sd-textarea:focus {
            border-color: #3b46f1;
            box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12);
        }
        .fcc-sd-textarea::placeholder {
            color: #94a3b8;
            font-size: 13px;
        }
        .fcc-sd-warning-note {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #b91c1c;
            font-size: 12.5px;
            font-weight: 500;
        }
        .fcc-sd-warning-note svg {
            color: #dc2626;
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
                <h4 class="fcc-sd-modal-title">Subtract member days</h4>
                <p class="fcc-sd-modal-sub">Reduce the member's remaining plan access</p>
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
        document.getElementById('fccSubtractHint').innerText = 'This will reduce the remaining membership duration by ' + daysText + '.';
    }
</script>