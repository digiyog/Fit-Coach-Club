@extends('admin-panel.layouts.main-layout')

@section('page-title', 'Edit Franchise Membership Plan | '.__('language.page_main_title').'')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">

<style>
    :root {
        --fcc-primary: #1d4ed8;
        --fcc-primary-hover: #1e40af;
        --fcc-dark: #0f172a;
        --fcc-slate: #1e293b;
        --fcc-muted: #64748b;
        --fcc-border: #e2e8f0;
        --fcc-card-bg: #ffffff;
        --fcc-banner-bg: #dbeafe;
        --fcc-banner-border: #bfdbfe;
        --fcc-banner-text: #1e3a8a;
    }

    .fcc-create-container {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--fcc-slate);
        padding: 8px 4px 60px 4px;
        width: 100%;
        max-width: 1440px;
        margin: 0 auto;
    }

    /* 1. Page Header Bar */
    .fcc-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 22px;
    }

    .fcc-header-info {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .fcc-header-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #e0f2fe;
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .fcc-header-title {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: var(--fcc-dark);
        letter-spacing: -0.02em;
        margin: 0;
        line-height: 1.2;
    }

    .fcc-header-subtitle {
        font-size: 13px;
        color: var(--fcc-muted);
        margin: 3px 0 0 0;
        font-weight: 500;
    }

    .fcc-btn-back {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 9px 18px;
        font-size: 13px;
        font-weight: 700;
        color: var(--fcc-dark);
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .fcc-btn-back:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #000000;
        transform: translateY(-1px);
    }

    /* 2. Structured White Cards */
    .fcc-card {
        background: #ffffff;
        border: 1px solid var(--fcc-border);
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        margin-bottom: 22px;
        overflow: hidden;
    }

    .fcc-card-header-bar {
        background: var(--fcc-banner-bg);
        border-bottom: 1px solid var(--fcc-banner-border);
        padding: 12px 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fcc-card-header-bar span {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: var(--fcc-banner-text);
        letter-spacing: -0.01em;
    }

    .fcc-card-body {
        padding: 24px 28px;
    }

    .fcc-create-container .fcc-form-grid-3 {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        column-gap: 24px !important;
        row-gap: 20px !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    @media (max-width: 991px) {
        .fcc-create-container .fcc-form-grid-3 {
            grid-template-columns: 1fr !important;
        }
    }

    .fcc-create-container .fcc-form-field {
        width: 100% !important;
        margin: 0 !important;
        display: flex !important;
        flex-direction: column !important;
    }

    /* Labels */
    .fcc-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--fcc-dark);
        margin-bottom: 7px;
        display: block;
    }

    .fcc-label .text-danger {
        color: #ef4444;
    }

    /* Select and Input styling */
    .fcc-create-container .bootstrap-select .btn.dropdown-toggle,
    .fcc-create-container select.form-control {
        height: 48px !important;
        line-height: 48px !important;
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #ffffff !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #0f172a !important;
        padding: 0 16px !important;
        box-shadow: none !important;
    }

    .fcc-create-container textarea.form-control {
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #ffffff !important;
        padding: 14px 16px !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #0f172a !important;
        box-shadow: none !important;
        line-height: 1.6 !important;
    }

    .fcc-create-container textarea.form-control:focus,
    .fcc-create-container select.form-control:focus {
        border-color: #1d4ed8 !important;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12) !important;
        outline: none !important;
    }

    /* 3. Footer Actions */
    .fcc-footer-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 26px;
        margin-bottom: 40px;
    }

    .fcc-btn-cancel {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 10px 24px;
        font-size: 13.5px;
        font-weight: 700;
        color: var(--fcc-dark);
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .fcc-btn-cancel:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #000000;
        transform: translateY(-1px);
    }

    .fcc-btn-save {
        background: var(--fcc-primary);
        border: none;
        border-radius: 10px;
        padding: 10px 28px;
        font-size: 13.5px;
        font-weight: 700;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(29, 78, 216, 0.35);
    }

    .fcc-btn-save:hover {
        background: var(--fcc-primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(29, 78, 216, 0.45);
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing fcc-create-container">

    <!-- 1. Page Header Bar -->
    <div class="fcc-page-header">
        <div class="fcc-header-info">
            <div class="fcc-header-icon-box">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
            </div>
            <div>
                <h1 class="fcc-header-title">Edit Franchise Membership Plan</h1>
                <p class="fcc-header-subtitle">Update payment status and review transaction notes for this franchise plan.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('adminPanel.franchise-membership-plans.index') }}" class="fcc-btn-back">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Franchise Plans
            </a>
        </div>
    </div>

    <!-- Error Alerts Component -->
    @component('admin-panel.validation.errors') @endcomponent

    {!! Form::open(['class' => 'franchise-membership-plan-form', 'method' => 'post', 'url' => route('adminPanel.franchise-membership-plans.update', ['id' => ev($franchiseMembershipPlan->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'id' => 'editFranchisePlanForm' ]) !!}

        <!-- 2. Section: Plan Assignment Details -->
        <div class="fcc-card">
            <div class="fcc-card-header-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>Membership Allocation & Payment Status</span>
            </div>
            <div class="fcc-card-body">
                <div class="fcc-form-grid-3 mb-4">
                    <!-- Franchise Selection (disabled / display) -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="franchise_id">Franchise</label>
                        <select name="franchise_id" id="franchise_id" class="form-control filter-field select-picker" disabled>
                            <option value="">Select Franchise</option>
                            @foreach($franchises as $key => $franchise)
                                <option value="{{ $franchise['id'] }}" @if($franchise['id'] == $franchiseMembershipPlan->franchise_id) selected @endif>{{ $franchise['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Membership Plan Selection (disabled / display) -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="membership_plan_id">Membership Plan</label>
                        <select name="membership_plan_id" id="membership_plan_id" class="form-control filter-field select-picker" disabled>
                            <option value="">Select Membership Plan</option>
                            @foreach($membershipPlans as $key => $membershipPlan)
                                <option value="{{ $membershipPlan['id'] }}" @if($membershipPlan['id'] == $franchiseMembershipPlan->membership_id) selected @endif>{{ $membershipPlan['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payment Status -->
                    <div class="fcc-form-field">
                        <label class="fcc-label" for="payment_status">Payment Status <span class="text-danger">*</span></label>
                        <select name="payment_status" id="payment_status" class="form-control filter-field select-picker" title="Select Status" required>
                            <option value="">Select Payment Status</option>
                            <option value="1" @if($franchiseMembershipPlan->payment_status == 1) selected @endif>Pending</option>
                            <option value="2" @if($franchiseMembershipPlan->payment_status == 2) selected @endif>Completed</option>
                        </select>
                    </div>
                </div>

                <!-- Remarks / Notes -->
                <div class="fcc-form-field">
                    <label class="fcc-label" for="remark">Remarks / Notes</label>
                    {!! Form::textarea('remark', $franchiseMembershipPlan->remark, ['class' => 'form-control', 'id' => 'remark', 'placeholder' => 'Enter remarks...', 'rows' => 3 ]) !!}
                </div>
            </div>
        </div>

        <!-- 3. Form Footer Actions (Cancel / Update) -->
        <div class="fcc-footer-actions">
            <a href="{{ route('adminPanel.franchise-membership-plans.index') }}" class="fcc-btn-cancel">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                Cancel
            </a>
            <button type="submit" class="fcc-btn-save" id="btnUpdateFranchisePlan">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Update Allocation
            </button>
        </div>

    {!! Form::close() !!}

</div>
@endsection

@push('scripts')
<script>
    var maxImageSize = {{config('constants.max_image_size')}};
</script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/franchise-membership-plans/franchise-membership-plans.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
