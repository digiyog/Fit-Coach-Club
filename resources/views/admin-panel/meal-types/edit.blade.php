@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Edit Meal Type | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">

<style>
    .dropify-wrapper
    {
        width:100% !important;
        margin-bottom: unset !important;
        height: 213px;
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
                    <div class="container-fluid mt2">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-8 col-8">
                                <h4> Edit Meal Type </h4>
                            </div>
                        </div>

                        <div class="form pb-2">
                            {!! Form::open(['class' => 'meal-type-form', 'method' => 'post', 'url' => route('adminPanel.meal-types.update', ['id' => ev($mealType->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                                <div class="row mb-3">
                                    
                                    @php
                                        $imagePath = (get_image_url(config('constants.meal-types.image_path'), $mealType->image) ?? '');
                                    @endphp

                                    <div class="col-md-3 mb-4">
                                        <div class="custom-dropify">
                                            <label class="form-control-label" for="image">@lang('language.image') <span class="text-danger">*</span></label>
                                            {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => $imagePath, ]) !!}

                                            {!! Form::hidden('image_name', old('image_name', ($mealType->image ?? null)) ,['class' => 'form-control','id' => 'image_name']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="row pr-3">
                                            <div class="col-md-12">
                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                {!! Form::text('name', $mealType->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Name', ]) !!}
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <label for="order"> Order <span class="text-danger">*</span></label>
                                                {!! Form::text('order', $mealType->order, ['class' => 'form-control numeric', 'id' => 'order', 'placeholder' => 'Order', ]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div id="mealAccordion">
                                            @foreach($weekdays as $dayKey => $dayName)

                                                <div class="card mb-3">
                                                    <div class="card-header" id="heading{{$dayKey}}">
                                                        <h5 class="mb-0">
                                                            <button type="button" class="btn btn-link"
                                                                data-toggle="collapse"
                                                                data-target="#collapse{{$dayKey}}">
                                                                {{ $dayName }}
                                                            </button>
                                                        </h5>
                                                    </div>

                                                    <div id="collapse{{$dayKey}}"
                                                        class="collapse {{ $dayKey == 1 ? 'show' : '' }}"
                                                        data-parent="#mealAccordion">

                                                        <div class="card-body">
                                                            @foreach($scheduleTypes as $typeKey => $type)

                                                                @php
                                                                    $title = $mealData[$dayKey][$type['value']]['title'] ?? '';
                                                                    $time  = $mealData[$dayKey][$type['value']]['time'] ?? '';
                                                                    $desc  = $mealData[$dayKey][$type['value']]['description'] ?? '';
                                                                @endphp

                                                                <div class="border rounded p-3 mb-3">
                                                                    <h6 class="fw-600 mb-2 text-primary">
                                                                        {{ $type['display'] }}
                                                                    </h6>

                                                                    <input type="hidden" name="meals[{{$dayKey}}][{{$type['value']}}][title]" value="{{ $type['display'] }}" class="form-control">

                                                                    <div class="row">
                                                                        <div class="col-md-4 mb-2">
                                                                            <label class="fs-13">Time</label>
                                                                            <input type="text"
                                                                                name="meals[{{$dayKey}}][{{$type['value']}}][time]"
                                                                                class="form-control timepicker"
                                                                                value="{{ old("meals.$dayKey.{$type['value']}.time", $time) }}"
                                                                                placeholder="hh:mm AM/PM">
                                                                        </div>

                                                                        <div class="col-md-8 mb-2 pl-0">
                                                                            <label class="fs-13">Description</label>
                                                                            <input type="text"
                                                                                name="meals[{{$dayKey}}][{{$type['value']}}][description]"
                                                                                class="form-control"
                                                                                value="{{ old("meals.$dayKey.{$type['value']}.description", $desc) }}"
                                                                                placeholder="Enter {{ $type['display'] }} description">
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

                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.update')] )}}
                            {!! Form::close() !!}
                        </div>
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
    flatpickr(".timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",   // 12 hour format with AM/PM
        time_24hr: false,      // important
        minuteIncrement: 5
    });
</script>

@endpush