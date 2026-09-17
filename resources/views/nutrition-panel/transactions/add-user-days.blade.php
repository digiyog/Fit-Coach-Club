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
            font-size: 19px;
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
            position: absolute;
            left: 14px;
            color: #3b46f1;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            z-index: 2;
        }
        .fcc-ad-field-currency {
            position: absolute;
            left: 15px;
            color: #334155;
            font-weight: 700;
            font-size: 15px;
            pointer-events: none;
            z-index: 2;
        }
        .fcc-ad-select {
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
        .fcc-ad-select:focus {
            border-color: #3b46f1;
            box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12);
        }
        .fcc-ad-select-chevron {
            position: absolute;
            right: 14px;
            color: #64748b;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .fcc-ad-input {
            width: 100%;
            height: 48px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0 14px 0 38px;
            font-size: 13.5px;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            transition: all 0.15s ease;
        }
        .fcc-ad-input:focus {
            border-color: #3b46f1;
            box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12);
        }
        .fcc-ad-input::placeholder {
            color: #94a3b8;
            font-size: 13px;
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
            position: relative;
        }
        .fcc-ad-textarea-wrap .fcc-ad-field-icon {
            top: 14px;
            align-items: flex-start;
        }
        .fcc-ad-textarea {
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
        .fcc-ad-textarea:focus {
            border-color: #3b46f1;
            box-shadow: 0 0 0 3px rgba(59, 70, 241, 0.12);
        }
        .fcc-ad-textarea::placeholder {
            color: #94a3b8;
            font-size: 13px;
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
                <h4 class="fcc-ad-modal-title">Add member days</h4>
                <p class="fcc-ad-modal-sub">Extend plan access and record payment</p>
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