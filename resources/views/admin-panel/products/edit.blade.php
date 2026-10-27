@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Edit Product | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/file-upload/file-upload-with-preview.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">

<style>
    .dropify-wrapper {
        width: 100% !important;
        margin-bottom: unset !important;
        height: 240px;
    }
    .textarea-height {
        resize: vertical;
    }
    .custom-file-container__image-multi-preview {
        height: 120px !important;
        width: 120px;
        border-radius: 8px;
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
                                <i data-feather="edit" class="text-primary" style="width: 22px; height: 22px;"></i>
                                Edit Product
                            </h4>
                            <p class="admin-card-subtitle">Modify product information, pricing, gallery images, and details.</p>
                        </div>
                        <div>
                            <a href="{{ route('adminPanel.products.index') }}" class="btn-back-link">
                                <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                                Back to Products
                            </a>
                        </div>
                    </div>

                    <div class="form pb-2">
                        {!! Form::open(['class' => 'product-form', 'method' => 'post', 'url' => route('adminPanel.products.update', ['id' => ev($product->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                            <div class="row">
                                @php
                                    $imagePath = (get_image_url(config('constants.products.image_path'), $product->image) ?? '');
                                @endphp

                                <div class="col-lg-3 col-md-4 mb-4">
                                    <div class="form-group">
                                        <div class="custom-dropify">
                                            <label class="form-control-label" for="image">Featured Image <span class="text-danger">*</span></label>
                                            {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => $imagePath, ]) !!}
                                            {!! Form::hidden('image_name', old('image_name', ($product->image ?? null)) ,['class' => 'form-control','id' => 'image_name']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-9 col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">Product Name <span class="text-danger">*</span></label>
                                                {!! Form::text('name', $product->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Enter product name', ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">Price (₹) <span class="text-danger">*</span></label>
                                                {!! Form::text('price', $product->price, ['class' => 'form-control numeric', 'id' => 'price', 'placeholder' => 'Enter price', ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="order">Display Order <span class="text-danger">*</span></label>
                                                {!! Form::text('order', $product->order, ['class' => 'form-control numeric', 'id' => 'order', 'placeholder' => 'Enter display order', ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="product_type">Product Type <span class="text-danger">*</span></label>
                                                <select name="product_type" id="product_type" class="form-control filter-field select-picker" data-live-search="true" title="Select Product Type">
                                                    <option value="">Select Product Type</option>
                                                    @foreach($productTypes as $key => $value)
                                                        <option value="{{$value['id']}}" @if($product->product_type_id == $value['id']) selected='selected' @endif>{{ $value['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                @php
                                                    $franchisesIds = [];
                                                    if($franchiseProduct->count() > 0){
                                                        $franchisesIds = $franchiseProduct->pluck('franchise_id')->toArray();
                                                    }
                                                @endphp
                                                <label for="franchises">Select Franchises</label>
                                                <select name="franchises[]" id="franchises" class="form-control filter-field select-picker" multiple data-live-search="true" title="Select Franchises">
                                                    @foreach($franchises as $key => $value)
                                                        <option value="{{$value['id']}}" @if(in_array($value['id'], $franchisesIds)) selected @endif>{{ $value['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Multiple Gallery Images Upload Section -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card p-3 mb-4" style="border: 1px solid #e2e8f0; border-radius: 10px; background: #fafbfc;">
                                        <h6 class="admin-section-title mt-0 mb-3" style="border-bottom: none;">
                                            <i data-feather="images" style="width: 16px; height: 16px;"></i>
                                            Multiple Gallery Images
                                        </h6>
                                        <div class="custom-file-container" data-upload-id="mySecondImage">
                                            <label class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted fs-13">Upload additional photos (Click 'x' to clear selection)</span>
                                                <a href="javascript:void(0)" class="custom-file-container__image-clear badge badge-danger" title="Clear Image">Clear all</a>
                                            </label>
                                            <label class="custom-file-container__custom-file">
                                                <input type="file" id="file-input" name="multiple_images[images][]" accept="image/*" class="custom-file-container__custom-file__custom-file-input w-100" multiple>
                                                <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                                <span class="custom-file-container__custom-file__custom-file-control"></span>
                                            </label>
                                            <div id="image-preview" class="custom-file-container__image-preview mt-2"></div>
                                        </div>

                                        @if(isset($productImage) && $productImage->count())
                                            <div class="mt-4 pt-3 border-top">
                                                <label class="form-label-styled fw-bold mb-2">Existing Gallery Photos:</label>
                                                <div class="d-flex flex-wrap gap-3">
                                                    @foreach($productImage as $img)
                                                        <div class="image-box-{{ $img->id }}" style="position: relative;">
                                                            <img src="{{ get_image_url(config('constants.products.image_path'), $img->image) }}" 
                                                                style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #cbd5e1; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                                                            <button type="button" class="btn btn-sm btn-danger remove-existing-image" data-id="{{ $img->id }}" style="position: absolute; top: -6px; right: -6px; width: 22px; height: 22px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 13px;">
                                                                ×
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Descriptions Section -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="short_description">Short Description</label>
                                        {!! Form::textarea('short_description', $product->short_description, ['class' => 'form-control textarea-height', 'id' => 'short_description', 'placeholder' => 'Enter brief overview...', 'rows' => 3 ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="description">Full Description</label>
                                        {!! Form::textarea('description', $product->description, ['class' => 'form-control textarea-height editor-textarea', 'id' => 'description', 'placeholder' => 'Detailed product description...', 'rows' => 5 ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="admin-form-actions">
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.update')] )}}
                                <a href="{{ route('adminPanel.products.index') }}" class="btn-back-link">Cancel</a>
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
<script src="{{ asset('admin-assets/js/products/products.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/file-upload/file-upload-with-preview.min.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        document.querySelectorAll(".custom-file-container").forEach(container => {
            let uploadId = container.getAttribute("data-upload-id");
            let fileInput = document.querySelector(`#file-input`);
            let imagePreview = document.querySelector(`#image-preview`);
            let clearButton = container.querySelector(".custom-file-container__image-clear");

            new FileUploadWithPreview(uploadId);

            if (!fileInput || !imagePreview || !clearButton) {
                return;
            }

            let removedFiles = [];

            clearButton.addEventListener("click", function () {
                imagePreview.innerHTML = "";
                removedFiles = [...fileInput.files];
                fileInput.value = "";
            });

            fileInput.addEventListener("change", function () {
                let files = Array.from(fileInput.files);
                files = files.filter(file => !removedFiles.includes(file));

                let dataTransfer = new DataTransfer();
                files.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;

                removedFiles = [];
            });
        });
    });
</script>
@endpush