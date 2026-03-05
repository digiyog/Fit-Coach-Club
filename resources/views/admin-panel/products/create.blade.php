@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Create Product | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/file-upload/file-upload-with-preview.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">

<style>
    .dropify-wrapper
    {
        width:100% !important;
        margin-bottom: unset !important;
        height: 220px;
    }
    .textarea-height{
        resize: none;
    }
    .custom-file-container__image-multi-preview{
        height: 130px !important;
        width: 130px;
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
                                <h4> Create Product </h4>
                            </div>
                        </div>

                        <div class="form pb-2">
                            {!! Form::open(['class' => 'product-form', 'method' => 'post', 'url' => route('adminPanel.products.store'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="custom-dropify">
                                            <label class="form-control-label" for="image">@lang('language.image') <span class="text-danger">*</span></label>
                                            {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => '', ]) !!}

                                            {!! Form::hidden('image_name', '' ,['class' => 'form-control','id' => 'image_name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row pr-3">
                                            <div class="col-md-12">
                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                {!! Form::text('name', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Name', ]) !!}
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="price"> Price <span class="text-danger">*</span></label>
                                                {!! Form::text('price', '', ['class' => 'form-control numeric', 'id' => 'price', 'placeholder' => 'Price', ]) !!}
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <div class="form-group">
                                                    <label>Select Franchise <span class="text-danger">*</span></label>
                                                    <select name="franchises[]" id="franchises" class="form-control filter-field select-picker" multiple>
                                                        <!-- <option value=""> Select Franchises </option> -->
                                                        @foreach($franchises as $key => $value)
                                                            <option value="{{$value['id']}}">{{ $value['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Select Product Type <span class="text-danger">*</span></label>
                                                    <select name="product_type" id="product_type" class="form-control filter-field select-picker">
                                                        <option value=""> Select Product Type </option>
                                                        @foreach($productTypes as $key => $value)
                                                            <option value="{{$value['id']}}">{{ $value['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="order"> Order <span class="text-danger">*</span></label>
                                                {!! Form::text('order', 0, ['class' => 'form-control numeric', 'id' => 'order', 'placeholder' => 'Order', ]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row col-md-12 layout-top-spacing pr-0">
                                        <div id="fuMultipleFile" class="col-lg-12">
                                            <div class="statbox widget box box-shadow">
                                                <div class="widget-header">
                                                    <div class="row">
                                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                            <h4>Multiple Image Upload</h4>
                                                        </div>      
                                                    </div>
                                                </div>
                                                <div class="widget-content widget-content-area">
                                                    <div class="custom-file-container" data-upload-id="mySecondImage">
                                                        <label>Upload (Allow Multiple) 
                                                            <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a>
                                                        </label>
                                                        <label class="custom-file-container__custom-file">
                                                            <input type="file" id="file-input" name="multiple_images[images][]" accept="image/*" class="custom-file-container__custom-file__custom-file-input w-100" required="required" multiple>
                                                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                                                        </label>
                                                        <div id="image-preview" class="custom-file-container__image-preview"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="short_description">Short Description</label>
                                            {!! Form::textarea('short_description', '', ['class' => 'form-control textarea-height', 'id' => 'short_description', 'placeholder' => 'Short Description', 'rows' => 5 , "cols" => 40 ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-1">
                                        <div class="form-group">
                                            <label class="form-control-label" for="description">Description</label>
                                            {!! Form::textarea('description', '', ['class' => 'form-control textarea-height editor-textarea', 'id' => 'description', 'placeholder' => 'Description', 'rows' => 5 , "cols" => 40 ]) !!}
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
<script src="{{ asset('admin-assets/js/products/products.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/file-upload/file-upload-with-preview.min.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".custom-file-container").forEach(container => {
            let uploadId = container.getAttribute("data-upload-id");
            let fileInput = document.querySelector(`#file-input`);
            let imagePreview = document.querySelector(`#image-preview`);
            let clearButton = container.querySelector(".custom-file-container__image-clear");

            new FileUploadWithPreview(uploadId);

            if (!fileInput || !imagePreview || !clearButton) {
                console.warn(`Elements missing for uploadId: ${uploadId}`);
                return; // Skip this iteration if elements are missing
            }

            let removedFiles = []; // Store removed files

            // Clear button click event
            clearButton.addEventListener("click", function () {
                imagePreview.innerHTML = ""; // Clear preview
                removedFiles = [...fileInput.files]; // Store removed files

                // Reset the file input (Remove all selected files)
                fileInput.value = "";
            });

            // Before form submission, update the file input
            fileInput.addEventListener("change", function () {
                let files = Array.from(fileInput.files);
                files = files.filter(file => !removedFiles.includes(file)); // Remove deleted files

                // Update file input manually
                let dataTransfer = new DataTransfer();
                files.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;

                removedFiles = []; // Reset removed files
            });
        });
    });
</script>

@endpush
