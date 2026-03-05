@extends('nutrition-panel.layouts.main-layout')

@section('page-title', ' Edit Tip | '.__('language.page_main_title').'')

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
                @component('nutrition-panel.validation.errors') @endcomponent
                <!-- / Validation error -->

                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-8 col-8">
                                <h4> Edit Tip (Youtube Links)</h4>
                            </div>
                        </div>

                        <div class="form pb-2">
                            {!! Form::open(['class' => 'tip-form', 'method' => 'post', 'url' => route('nutritionPanel.tips.update', ['id' => ev($tip->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label for="name">Exercise or Recipe or Tips Topic Name <span class="text-danger">*</span></label>
                                        {!! Form::text('name', $tip->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Name', ]) !!}
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label for="link">Youtube Link(Please Add Only id ) <span class="text-danger">*</span></label>
                                        {!! Form::text('link', $tip->link, ['class' => 'form-control', 'id' => 'link', 'placeholder' => 'https://www.youtube.com/watch?v=_X1nGnrHhDI Enter only id after =' ]) !!}
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label for="coach_name">Coach Name <span class="text-danger">*</span></label>
                                        {!! Form::text('coach_name', $tip->coach_name, ['class' => 'form-control', 'id' => 'coach_name', 'placeholder' => 'Coach Name', ]) !!}
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label for="order"> Order <span class="text-danger">*</span></label>
                                        {!! Form::text('order', $tip->order, ['class' => 'form-control numeric', 'id' => 'order', 'placeholder' => 'Order', ]) !!}
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
<script src="{{ asset('admin-assets/js/tips/tips.js') }}"></script>

@endpush