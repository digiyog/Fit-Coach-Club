@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Create Product Type | '.__('language.page_main_title').'')

@push('styles')
@endpush

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-8 col-lg-10 col-md-12 col-12 layout-spacing">

                <!-- Validation error -->
                @component('admin-panel.validation.errors') @endcomponent
                <!-- / Validation error -->

                <div class="widget-content widget-content-area br-6">
                    <div class="admin-card-header">
                        <div>
                            <h4 class="admin-card-title">
                                <i data-feather="tag" class="text-primary" style="width: 22px; height: 22px;"></i>
                                Create Product Type
                            </h4>
                            <p class="admin-card-subtitle">Add a category or classification for products.</p>
                        </div>
                        <div>
                            <a href="{{ route('adminPanel.product-types.index') }}" class="btn-back-link">
                                <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                                Back to Product Types
                            </a>
                        </div>
                    </div>

                    <div class="form pb-2">
                        {!! Form::open(['class' => 'product-type-form', 'method' => 'post', 'url' => route('adminPanel.product-types.store'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Product Type Name <span class="text-danger">*</span></label>
                                        {!! Form::text('name', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'e.g. Nutritional Supplements, Apparel, Shakes', ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="admin-form-actions">
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.save'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.save')] )}}
                                <a href="{{ route('adminPanel.product-types.index') }}" class="btn-back-link">Cancel</a>
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
<script src="{{ asset('admin-assets/js/product-types/product-types.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
