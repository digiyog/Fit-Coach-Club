@extends('admin-panel.layouts.main-layout')

@section('page-title', ' '.__('language.create_cms_page_title').' | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<style>
    .dropify-wrapper {
        width: 100% !important;
        height: 220px !important;
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
                                <i data-feather="file-text" class="text-primary" style="width: 22px; height: 22px;"></i>
                                {{ __('language.create_cms_page_title') }}
                            </h4>
                            <p class="admin-card-subtitle">Create a static or informative content page for the application.</p>
                        </div>
                        <div>
                            <a href="{{ route('adminPanel.cms-pages.index') }}" class="btn-back-link">
                                <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                                Back to CMS Pages
                            </a>
                        </div>
                    </div>

                    <div class="form pb-2">
                        {!! Form::open(['class' => 'add-cms-form', 'method' => 'post', 'url' => route('adminPanel.cms.store'), 'enctype' => 'multipart/form-data' ]) !!}
                            <div class="row">
                                <div class="col-lg-3 col-md-4 mb-4">
                                    <div class="form-group">
                                        <div class="custom-dropify">
                                            <label class="form-control-label" for="image">Featured Image</label>
                                            {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => '', ]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-9 col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="title">{{ __('language.title') }} <span class="text-danger">*</span></label>
                                                {!! Form::text('title', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Enter page title', ]) !!}
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sub_title">Sub Title</label>
                                                {!! Form::text('sub_title', '', ['class' => 'form-control', 'id' => 'sub_title', 'placeholder' => 'Enter page subtitle', ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="page_type">{{ __('language.page_type') }} <span class="text-danger">*</span></label>
                                                {!! Form::select('page_type', create_select_options(config('constants.page_types'), 'caption', 'value'), '', ['class' => 'form-control select-picker', 'id' => 'page_type', 'title' => 'Select Page Type']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Content Sections -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="admin-section-title">
                                        <i data-feather="edit-3" style="width: 16px; height: 16px;"></i>
                                        Page Content
                                    </h6>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="short_desc">Short Summary / Excerpt</label>
                                        {!! Form::textarea('short_desc', '', ['class' => 'form-control textarea-height', 'id' => 'short_desc', 'placeholder' => 'Brief overview...', 'rows' => 3 ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">{{ __('language.description') }}</label>
                                        {!! Form::textarea('description', '', ['class' => 'form-control editor-textarea', 'id' => 'description', 'placeholder' => 'Full page content...', 'rows' => 6 ]) !!}
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Meta Information -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="admin-section-title">
                                        <i data-feather="globe" style="width: 16px; height: 16px;"></i>
                                        SEO & Meta Information
                                    </h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meta_title">Meta Title</label>
                                        {!! Form::text('meta_title', '', ['class' => 'form-control', 'id' => 'meta_title', 'placeholder' => 'SEO Title', ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meta_keyword">Meta Keywords</label>
                                        {!! Form::text('meta_keyword', '', ['class' => 'form-control', 'id' => 'meta_keyword', 'placeholder' => 'Keywords separated by commas', ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="meta_desc">Meta Description</label>
                                        {!! Form::textarea('meta_desc', '', ['class' => 'form-control textarea-height', 'id' => 'meta_desc', 'placeholder' => 'SEO Meta description for search engines...', 'rows' => 3 ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="admin-form-actions">
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_save'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_save')] )}}
                                <a href="{{ route('adminPanel.cms-pages.index') }}" class="btn-back-link">Cancel</a>
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
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/cms/cms.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
