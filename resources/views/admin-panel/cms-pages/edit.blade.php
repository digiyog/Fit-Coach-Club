@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Edit CMS Page | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<style>
    .custom-dropify .dropify-wrapper {
        width: 100% !important;
        height: 220px;
    }
    .short_description {
        height: 105px !important;
    }
    #meta_desc {
        height: 90px;
        resize: vertical;
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

            <div class="widget-content widget-content-area br-6">
                <div class="admin-card-header">
                    <div>
                        <h4 class="admin-card-title">
                            <i data-feather="edit" class="text-primary" style="width: 22px; height: 22px;"></i>
                            @if($cms->id == 1)
                                Edit Home Page
                            @elseif($cms->id == 2)
                                Edit Procedure Booking
                            @elseif($cms->id == 3)
                                Edit Thank You Page
                            @elseif($cms->id == 4)
                                Edit Privacy Policy
                            @elseif($cms->id == 5)
                                Edit Terms & Conditions
                            @else
                                Edit CMS Page: {{ $cms->title }}
                            @endif
                        </h4>
                        <p class="admin-card-subtitle">Manage public-facing text, legal disclosures, and SEO metadata.</p>
                    </div>
                    <div>
                        <a href="{{ route('adminPanel.cms-pages.index') }}" class="btn-back-link">
                            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                            Back to CMS Pages
                        </a>
                    </div>
                </div>

                <div class="form pb-2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title">Page Title</label>
                                {!! Form::text('title', $cms->title, ['class' => 'form-control', 'id' => 'title', 'placeholder' => 'Page Title', 'readonly' => 'readonly']) !!}
                            </div>
                        </div>

                        @if($cms->id == 4 || $cms->id == 5)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Page Content (HTML)</label>
                                    {!! Form::textarea('description', $cms->description, ['class' => 'form-control editor-textarea', 'id' => 'description', 'placeholder' => 'Enter content...', 'rows' => 8]) !!}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- SEO Metadata Section -->
                    <h6 class="admin-section-title mt-3">
                        <i data-feather="globe" style="width: 16px; height: 16px;"></i>
                        Search Engine Optimization (SEO)
                    </h6>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="meta_title">Meta Title</label>
                                {!! Form::text('meta_title', $cms->meta_title, ['class' => 'form-control', 'id' => 'meta_title', 'placeholder' => 'SEO Title', ]) !!}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="meta_desc">Meta Description</label>
                                {!! Form::textarea('meta_desc', $cms->meta_description, ['class' => 'form-control textarea-height', 'id' => 'meta_desc', 'rows' => 4, 'placeholder' => 'SEO Description for search results...', ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-actions">
                        {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_update')] )}}
                        <a href="{{ route('adminPanel.cms-pages.index') }}" class="btn-back-link">Cancel</a>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/cms/cms.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush