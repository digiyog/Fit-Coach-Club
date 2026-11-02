@extends('admin-panel.layouts.main-layout')

@section('page-title', 'Create Franchise | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<style>
    /* Clean, Modern Admin Form Styling */
    .create-franchise-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 4px 18px -2px rgba(15, 23, 42, 0.05);
        padding: 24px 28px;
    }

    .admin-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding-bottom: 18px;
        margin-bottom: 24px;
        border-bottom: 1px solid #edf2f7;
    }

    .admin-card-title {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .admin-card-subtitle {
        font-size: 13.5px;
        color: #64748b;
        margin: 4px 0 0 0;
    }

    .btn-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569 !important;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }

    .btn-back-link:hover {
        background: #f1f5f9;
        color: #0f172a !important;
        border-color: #94a3b8;
    }

    /* Section divider */
    .form-section-heading {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        font-size: 14.5px;
        font-weight: 700;
        color: #1e293b;
        margin-top: 10px;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-section-heading i,
    .form-section-heading svg {
        color: #3246d3;
        width: 17px;
        height: 17px;
    }

    /* Form inputs */
    .form-group {
        margin-bottom: 20px !important;
    }

    .form-group label,
    .form-control-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 7px;
        display: block;
    }

    .form-control {
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        background: #ffffff !important;
        color: #0f172a !important;
        height: 42px;
        padding: 8px 14px;
        font-size: 13.5px;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #3246d3 !important;
        box-shadow: 0 0 0 3px rgba(50, 70, 211, 0.12) !important;
        outline: none;
    }

    .form-text-hint {
        font-size: 12px;
        color: #64748b;
        margin-top: 5px;
        display: block;
    }

    /* Dropify Upload Box */
    .upload-avatar-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 20px;
    }

    .upload-avatar-card .custom-dropify .dropify-wrapper {
        border-radius: 10px !important;
        border: 1.5px dashed #cbd5e1 !important;
        background: #ffffff !important;
        height: 220px !important;
        width: 100% !important;
        transition: all 0.2s ease;
    }

    .upload-avatar-card .custom-dropify .dropify-wrapper:hover {
        border-color: #3246d3 !important;
        background: #f8faff !important;
    }

    /* Bootstrap select override */
    .bootstrap-select .dropdown-toggle {
        height: 42px !important;
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        background-color: #ffffff !important;
        padding: 8px 14px !important;
        font-size: 13.5px !important;
    }

    .bootstrap-select .dropdown-toggle:focus {
        border-color: #3246d3 !important;
        box-shadow: 0 0 0 3px rgba(50, 70, 211, 0.12) !important;
    }

    /* Form Actions */
    .admin-form-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 10px;
        padding-top: 20px;
        border-top: 1px solid #edf2f7;
    }

    .btn-submit-primary {
        background: #3246d3;
        border: 1px solid #3246d3;
        color: #ffffff !important;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 24px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(50, 70, 211, 0.25);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-submit-primary:hover {
        background: #2536b8;
        border-color: #2536b8;
        box-shadow: 0 4px 12px rgba(50, 70, 211, 0.35);
        transform: translateY(-1px);
    }

    .btn-cancel-link {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #475569 !important;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
    }

    .btn-cancel-link:hover {
        background: #e2e8f0;
        color: #0f172a !important;
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

                <div class="create-franchise-card">
                    <!-- Page Header -->
                    <div class="admin-card-header">
                        <div>
                            <h4 class="admin-card-title">
                                <i data-feather="plus-circle" class="text-primary"></i>
                                Create Franchise
                            </h4>
                            <p class="admin-card-subtitle">Fill in the details below to register a new franchise partner.</p>
                        </div>
                        <div>
                            <a href="{{ route('adminPanel.franchises.index') }}" class="btn-back-link">
                                <i data-feather="arrow-left" style="width: 15px; height: 15px;"></i>
                                Back to Franchises
                            </a>
                        </div>
                    </div>

                    <!-- Form -->
                    <div class="form">
                        {!! Form::open(['class' => 'franchise-form', 'method' => 'post', 'url' => route('adminPanel.franchises.store'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                            <div class="row">
                                <!-- Left Column: Avatar Upload -->
                                <div class="col-lg-4 col-md-5 col-12">
                                    <div class="upload-avatar-card">
                                        <div class="form-group mb-0">
                                            <div class="custom-dropify">
                                                <label class="form-control-label" for="image">
                                                    Franchise Logo / Photo <span class="text-danger">*</span>
                                                </label>
                                                {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => '', ]) !!}
                                                {!! Form::hidden('image_name', '' ,['class' => 'form-control','id' => 'image_name']) !!}
                                            </div>
                                            <span class="form-text-hint">
                                                <i class="fa fa-info-circle text-primary me-1"></i> Recommended: Square image (JPG, PNG, or WEBP, max 2MB).
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Franchise Information & Credentials -->
                                <div class="col-lg-8 col-md-7 col-12">
                                    <!-- General Details -->
                                    <h6 class="form-section-heading">
                                        <i data-feather="user"></i> General Information
                                    </h6>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="name">Franchise / Coach Name <span class="text-danger">*</span></label>
                                                {!! Form::text('name', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Enter franchise or coach name', ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="email">Email Address <span class="text-danger">*</span></label>
                                                {!! Form::email('email', '', ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'e.g. coach@fitcoachclub.com', 'autocomplete' => 'off', 'data-url' => route('adminPanel.franchises.checkEmail') ]) !!}
                                                <span class="form-text-hint">Used as login username for the coach portal.</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                                                {!! Form::text('mobile_number', '', ['class' => 'form-control numeric', 'id' => 'mobile_number', 'placeholder' => '10-digit mobile number', 'data-url' => route('adminPanel.franchises.checkMobile') ]) !!}
                                                <span class="form-text-hint">Used for SMS and WhatsApp notifications.</span>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="products">Select Allowed Products</label>
                                                <select name="products[]" id="products" class="form-control filter-field select-picker" multiple data-live-search="true" title="Choose products this franchise can distribute...">
                                                    @foreach($products as $key => $value)
                                                        <option value="{{$value['id']}}">{{ $value['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="form-text-hint">Assign specific store products available to this franchise.</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Security Credentials -->
                                    <h6 class="form-section-heading mt-2">
                                        <i data-feather="lock"></i> Portal Security Credentials
                                    </h6>

                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="new_pass">New Password <span class="text-danger">*</span></label>
                                                {!! Form::password('new_pass', ['class' => 'form-control', 'id' => 'new_pass', 'placeholder' => 'Enter secure password', 'autocomplete' => 'new-password' ]) !!}
                                                <span class="form-text-hint">Minimum 6 characters recommended.</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="confirm_pass">Confirm Password <span class="text-danger">*</span></label>
                                                {!! Form::password('confirm_pass', ['class' => 'form-control', 'id' => 'confirm_pass', 'placeholder' => 'Re-enter password', ]) !!}
                                                <span class="form-text-hint">Must match password above.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="admin-form-actions">
                                <button type="submit" class="btn-submit-primary btn-submit">
                                    <i data-feather="check" style="width: 16px; height: 16px;"></i>
                                    Save Franchise
                                </button>
                                <a href="{{ route('adminPanel.franchises.index') }}" class="btn-cancel-link">
                                    Cancel
                                </a>
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
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/franchises/franchises.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
