@extends('nutrition-panel.layouts.main-layout')

@section('page-title', ' Create Achievement | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">

<style>
    .dropify-wrapper
    {
        width:100% !important;
        margin-bottom: unset !important;
        height: 237px;
    }
</style>
@endpush

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">

                <!-- Validation error -->
                @component('nutrition-panel.validation.errors') @endcomponent
                <!-- / Validation error -->

                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-8 col-8">
                                <h4> Create Achievement </h4>
                            </div>
                        </div>

                        <div class="form pb-2">
                            {!! Form::open(['class' => 'achievement-form', 'method' => 'post', 'url' => route('nutritionPanel.achievements.store'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="custom-dropify">
                                            <label class="form-control-label" for="image">@lang('language.image') <span class="text-danger">*</span></label>
                                            {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => '', ]) !!}

                                            {!! Form::hidden('image_name', '' ,['class' => 'form-control','id' => 'image_name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row pe-3">
                                            <div class="col-md-12">
                                                <label for="title">Title <span class="text-danger">*</span></label>
                                                {!! Form::text('title', '', ['class' => 'form-control', 'id' => 'title', 'placeholder' => 'Title', ]) !!}
                                            </div>

                                            <div class="row p-3 align-items-end">
                                                <div class="col-md-6 pe-0">
                                                    <label for="in_app_show">Do You Want To show This Achievement/Announcement on Customer App home page</label>
                                                    {!! Form::select('in_app_show', create_select_options(config('constants.in_app_show'), 'display', 'value', 'Select In App Show'), '', ['class' => 'form-control select-picker', 'id' => 'in_app_show' ]) !!}
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="show_achievement">Show This Achievement/Announcement to All User Or Specific User</label>
                                                    {!! Form::select('show_achievement', create_select_options(config('constants.show_achievement'), 'display', 'value', 'Select Show Achievement'), '', ['class' => 'form-control select-picker', 'id' => 'show_achievement' ]) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6 pe-0">
                                                <label for="type">Achievement Type</label>
                                                {!! Form::select('type', create_select_options(config('constants.achievement_types'), 'display', 'value', 'Select Achievement Type'), $achievement->type, ['class' => 'form-control select-picker', 'id' => 'type' ]) !!}
                                            </div>

                                            <div class="col-md-6">
                                                <label for="order"> Order <span class="text-danger">*</span></label>
                                                {!! Form::text('order', 0, ['class' => 'form-control numeric', 'id' => 'order', 'placeholder' => 'Order', ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.save'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.save')] )}}
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
<script src="{{ asset('admin-assets/js/achievements/achievements.js') }}"></script>

@endpush
