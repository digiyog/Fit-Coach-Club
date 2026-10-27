@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Create Meal Type | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">

<style>
    .dropify-wrapper {
        width: 100% !important;
        margin-bottom: unset !important;
        height: 220px;
    }
    .meal-day-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 14px;
        background: #ffffff;
    }
    .meal-day-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 18px;
    }
    .meal-day-btn {
        color: #1e293b;
        font-weight: 700;
        text-decoration: none !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 0;
        border: none;
        background: none;
        font-size: 15px;
    }
    .meal-day-btn:hover {
        color: #3246d3;
    }
    .meal-slot-box {
        background: #fdfdfd;
        border: 1px solid #edf2f7;
        border-radius: 8px;
        padding: 14px;
        margin-bottom: 12px;
    }
</style>
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
                                <i data-feather="coffee" class="text-primary" style="width: 22px; height: 22px;"></i>
                                Create Meal Type
                            </h4>
                            <p class="admin-card-subtitle">Set up meal type schedules, weekdays, and diet slots.</p>
                        </div>
                        <div>
                            <a href="{{ route('adminPanel.meal-types.index') }}" class="btn-back-link">
                                <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                                Back to Meal Types
                            </a>
                        </div>
                    </div>

                    <div class="form pb-2">
                        {!! Form::open(['class' => 'meal-type-form', 'method' => 'post', 'url' => route('adminPanel.meal-types.store'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                            <div class="row">
                                <div class="col-lg-3 col-md-4 mb-4">
                                    <div class="form-group">
                                        <div class="custom-dropify">
                                            <label class="form-control-label" for="image">Meal Type Photo <span class="text-danger">*</span></label>
                                            {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => '', ]) !!}
                                            {!! Form::hidden('image_name', '' ,['class' => 'form-control','id' => 'image_name']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-9 col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">Meal Type Name <span class="text-danger">*</span></label>
                                                {!! Form::text('name', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'e.g. Weight Loss Diet Plan, High Protein Meal', ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="order">Display Order <span class="text-danger">*</span></label>
                                                {!! Form::text('order', 0, ['class' => 'form-control numeric', 'id' => 'order', 'placeholder' => 'Order number', ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Weekly Schedule Accordion -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="admin-section-title">
                                        <i data-feather="calendar" style="width: 16px; height: 16px;"></i>
                                        Weekly Meal Schedule by Day
                                    </h6>

                                    <div id="mealAccordion">
                                        @foreach($weekdays as $dayKey => $dayName)
                                            <div class="meal-day-card">
                                                <div class="meal-day-header" id="heading{{$dayKey}}">
                                                    <h5 class="mb-0">
                                                        <button type="button" class="meal-day-btn" data-bs-toggle="collapse" data-bs-target="#collapse{{$dayKey}}" aria-expanded="{{ $dayKey == 1 ? 'true' : 'false' }}">
                                                            <span><i data-feather="clock" class="text-primary me-2" style="width: 15px; height: 15px;"></i> {{ $dayName }}</span>
                                                            <i data-feather="chevron-down" style="width: 16px; height: 16px;"></i>
                                                        </button>
                                                    </h5>
                                                </div>

                                                <div id="collapse{{$dayKey}}" class="collapse {{ $dayKey == 1 ? 'show' : '' }}" data-bs-parent="#mealAccordion">
                                                    <div class="p-3">
                                                        @foreach($scheduleTypes as $typeKey => $type)
                                                            <div class="meal-slot-box">
                                                                <h6 class="fw-bold mb-2 text-primary" style="font-size: 13.5px;">
                                                                    {{ $type['display'] }}
                                                                </h6>

                                                                <input type="hidden" name="meals[{{$dayKey}}][{{$type['value']}}][title]" value="{{ $type['display'] }}" class="form-control">

                                                                <div class="row g-2">
                                                                    <div class="col-md-3">
                                                                        <label class="fs-13 fw-semibold text-muted mb-1">Time</label>
                                                                        <input type="text"
                                                                           name="meals[{{$dayKey}}][{{$type['value']}}][time]"
                                                                           class="form-control timepicker"
                                                                           placeholder="hh:mm AM/PM"
                                                                           required>
                                                                    </div>

                                                                    <div class="col-md-9">
                                                                        <label class="fs-13 fw-semibold text-muted mb-1">Diet / Food Description</label>
                                                                        <input type="text" 
                                                                            name="meals[{{$dayKey}}][{{$type['value']}}][description]"
                                                                            class="form-control"
                                                                            placeholder="Enter {{ $type['display'] }} description" required/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="admin-form-actions">
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.save'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.save')] )}}
                                <a href="{{ route('adminPanel.meal-types.index') }}" class="btn-back-link">Cancel</a>
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

<script src="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/flatpickr.js') }}"></script>
<script src="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/meal-types/meal-types.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        flatpickr(".timepicker", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            time_24hr: false,
            minuteIncrement: 5
        });
    });
</script>
@endpush
