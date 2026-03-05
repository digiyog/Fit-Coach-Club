@extends('admin-panel.layouts.main-layout')

@section('page-title', ' '.__('language.create_cms_page_title').' | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
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
                            <div class="col-xl-8 col-lg-8 col-md-8 col-8 page-heading">
                                <h4> {{ __('language.create_cms_page_title') }} </h4>
                            </div>
                        </div>

                        <div class="form p-3">
                            {!! Form::open(['class' => 'add-cms-form', 'method' => 'post', 'url' => route('adminPanel.cms.store'), 'enctype' => 'multipart/form-data' ]) !!}
                                <div class="form-row mb-4">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="custom-dropify">
                                                <label class="form-control-label" for="image">@lang('language.upload_image')</label>
                                                {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => '', ]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="title"> {{ __('language.title') }} </label>
                                            {!! Form::text('title', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => __('language.title'), ]) !!}
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sub_title"> Sub Title </label>
                                            {!! Form::text('sub_title', '', ['class' => 'form-control', 'id' => 'sub_title', 'placeholder' => 'Sub Title', ]) !!}
                                        </div>

                                        <div class="form-group">
                                            <label for="page_type"> {{ __('language.page_type') }} </label>
                                            {!! Form::select('page_type', create_select_options(config('constants.page_types'), 'caption', 'value'), '',['class' => 'form-control select-picker', 'id' => 'page_type', ]) !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="description"> {{ __('language.description') }} </label>
                                        {!! Form::textarea('description', '', ['class' => 'form-control editor-textarea', 'id' => 'description', 'placeholder' => __('language.description'), ]) !!}
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="short_desc"> Short Description </label>
                                        {!! Form::textarea('short_desc', '', ['class' => 'form-control', 'id' => 'short_desc', 'placeholder' => 'Short Description', ]) !!}
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="meta_title"> Meta Title </label>
                                        {!! Form::text('meta_title', '', ['class' => 'form-control', 'id' => 'meta_title', 'placeholder' => 'Meta Title', ]) !!}
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="meta_keyword"> Meta Keyword </label>
                                        {!! Form::text('meta_keyword', '', ['class' => 'form-control', 'id' => 'meta_keyword', 'placeholder' => 'Meta Keyword', ]) !!}
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="meta_desc"> Meta Description </label>
                                        {!! Form::textarea('meta_desc', '', ['class' => 'form-control', 'id' => 'meta_desc', 'placeholder' => 'Meta Description', ]) !!}
                                    </div>
                                </div>
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_save'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_save')] )}}
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
<script src="https://cdn.tiny.cloud/1/bmx2glw2c8gcbb3unhwsqjv47uvow53u8bzdlucgeuarx2qz/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/cms/cms.js') }}"></script>
@endpush
