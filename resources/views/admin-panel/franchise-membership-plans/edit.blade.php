@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Edit Franchise Membership Plan | '.__('language.page_main_title').'')

@push('styles')
@endpush

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">

                <!-- Validation error -->
                @component('admin-panel.validation.errors') @endcomponent
                <!-- / Validation error -->

                <div class="widget-content widget-content-area br-6">
                    <div class="admin-card-header">
                        <div>
                            <h4 class="admin-card-title">
                                <i data-feather="edit" class="text-primary" style="width: 22px; height: 22px;"></i>
                                Edit Franchise Membership Plan
                            </h4>
                            <p class="admin-card-subtitle">Update payment status and notes for this franchise plan allocation.</p>
                        </div>
                        <div>
                            <a href="{{ route('adminPanel.franchise-membership-plans.index') }}" class="btn-back-link">
                                <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                                Back to Franchise Plans
                            </a>
                        </div>
                    </div>

                    <div class="form pb-2">
                        {!! Form::open(['class' => 'franchise-membership-plan-form', 'method' => 'post', 'url' => route('adminPanel.franchise-membership-plans.update', ['id' => ev($franchiseMembershipPlan->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="franchise_id">Franchise</label>
                                        <select name="franchise_id" id="franchise_id" class="form-control filter-field select-picker" disabled>
                                            <option value="">Select Franchise</option>
                                            @foreach($franchises as $key => $franchise)
                                                <option value="{{ $franchise['id'] }}" @if($franchise['id'] == $franchiseMembershipPlan->franchise_id) selected @endif>{{ $franchise['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="membership_plan_id">Membership Plan</label>
                                        <select name="membership_plan_id" id="membership_plan_id" class="form-control filter-field select-picker" disabled>
                                            <option value="">Select Membership Plan</option>
                                            @foreach($membershipPlans as $key => $membershipPlan)
                                                <option value="{{ $membershipPlan['id'] }}" @if($membershipPlan['id'] == $franchiseMembershipPlan->membership_id) selected @endif>{{ $membershipPlan['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="payment_status">Payment Status <span class="text-danger">*</span></label>
                                        <select name="payment_status" id="payment_status" class="form-control filter-field select-picker">
                                            <option value="">Select Payment Status</option>
                                            <option value="1" @if($franchiseMembershipPlan->payment_status == 1) selected @endif>Pending</option>
                                            <option value="2" @if($franchiseMembershipPlan->payment_status == 2) selected @endif>Completed</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="remark">Remarks / Notes</label>
                                        {!! Form::textarea('remark', $franchiseMembershipPlan->remark, ['class' => 'form-control textarea-height', 'id' => 'remark', 'placeholder' => 'Enter remarks...', 'rows' => 4 ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="admin-form-actions">
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.update')] )}}
                                <a href="{{ route('adminPanel.franchise-membership-plans.index') }}" class="btn-back-link">Cancel</a>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
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
