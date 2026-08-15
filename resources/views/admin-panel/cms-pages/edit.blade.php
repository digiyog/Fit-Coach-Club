@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Edit Cms | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<style>
    .custom-dropify .dropify-wrapper {
        width: 100% !important;
        height: 314px;

    }

    .modify-dropify .dropify-wrapper {
        width: 270px !important;
    }

    .short_description {
        height: 105px !important;
    }

    #meta_desc {
        height: 80px;
        resize: none;
    }

    .image-height>.dropify-wrapper {
        width: 100% !important;
        height: 200px !important;
    }

    .tox-tinymce {
        height: 500px !important;
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
            {!! Form::open(['class' => 'add-cms-form', 'method' => 'post', 'url' => route('adminPanel.cms-pages.update', ['id' => ev($cms->id)]), 'enctype' => 'multipart/form-data' ]) !!}

            <div class="widget-content widget-content-area br-6 mb-3">
                <div class="container-fluid mt2">
                    <div class="row">
                        <div class="col-xl-8 col-lg-8 col-md-8 col-8">
                            <h4>
                                @if($cms->id == 1)
                                    Edit Home Page
                                @elseif($cms->id == 2)
                                    Edit Procedure Booking
                                @elseif($cms->id == 3)
                                    Edit Thank You
                                @elseif($cms->id == 4)
                                    Edit Privacy Policy
                                @elseif($cms->id == 5)
                                    Edit Terms And Conditions
                                @endif
                            </h4>
                        </div>
                    </div>
                    <div class="form">
                        <div class="row g-3 mb-4">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="our_initiatives_title"> Title</label>
                                {!! Form::text('title', $cms->title, ['class' => 'form-control', 'id' => 'title', 'placeholder' => 'Special Initiatives Title', readonly ]) !!}
                            </div>

                            @if($cms->id == 4 || $cms->id == 5)
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="special_initiatives_description"> Description </label>
                                    {!! Form::textarea('description', $cms->description, ['class' => 'form-control editor-textarea', 'id' => 'description', 'placeholder' => 'Special Initiatives Description', ]) !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="widget-content widget-content-area br-6 mt-3">
                <div class="container-fluid mt2">
                    <div class="row">
                        <div class="col-xl-8 col-lg-8 col-md-8 col-8">
                            <h4>CMS Content</h4>
                        </div>
                    </div>
                    <div class="form">
                        <div class="row g-3 mb-4">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="meta_title"> Meta Title </label>
                                {!! Form::text('meta_title', $cms->meta_title, ['class' => 'form-control', 'id' => 'meta_title', 'placeholder' => 'Meta Title', ]) !!}
                            </div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="meta_desc"> Meta Description </label>
                                {!! Form::textarea('meta_desc', $cms->meta_description, ['class' => 'form-control', 'id' => 'meta_desc', 'rows'=>'10', 'placeholder' => 'Meta Description', ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form pt-3">
                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_update')] )}}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/bmx2glw2c8gcbb3unhwsqjv47uvow53u8bzdlucgeuarx2qz/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/cms/cms.js') }}"></script>
@endpush