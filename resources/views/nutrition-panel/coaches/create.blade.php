@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Add Coach | ' . __('language.page_main_title'))

@push('styles')
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />

<style>
    .coach-form-wrapper {
        padding: 6px 10px 40px 10px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Breadcrumbs */
    .fcc-breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .fcc-breadcrumb-nav a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .fcc-breadcrumb-nav a:hover {
        color: #2563eb;
    }

    .fcc-breadcrumb-sep {
        color: #94a3b8;
    }

    .fcc-breadcrumb-active {
        color: #0f172a;
        font-weight: 600;
    }

    /* Header */
    .fcc-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .fcc-page-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .fcc-page-subtitle {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 0;
        font-weight: 400;
    }

    /* Layout Grid */
    .coach-grid-form {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 991px) {
        .coach-grid-form {
            grid-template-columns: 1fr;
        }
    }

    /* Cards */
    .fcc-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 26px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .card-title-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2px;
    }

    .title-accent-pill {
        width: 4px;
        height: 18px;
        background: #2563eb;
        border-radius: 99px;
        flex-shrink: 0;
    }

    .card-heading {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .card-subheading {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 20px;
    }

    /* Form Fields */
    .form-group-custom {
        margin-bottom: 18px;
    }

    .form-label-styled {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon .input-icon-left {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 15px;
        pointer-events: none;
        z-index: 2;
    }

    .input-field-styled {
        height: 44px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding-left: 42px;
        padding-right: 14px;
        font-size: 14px;
        color: #0f172a;
        background-color: #ffffff;
        transition: all 0.2s ease;
        width: 100%;
        box-shadow: none !important;
    }

    .input-field-styled:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    .textarea-styled {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 12px 14px;
        font-size: 14px;
        color: #0f172a;
        background-color: #ffffff;
        transition: all 0.2s ease;
        width: 100%;
        box-shadow: none !important;
        resize: vertical;
        min-height: 90px;
    }

    .textarea-styled:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }

    /* Dropify */
    .dropify-wrapper {
        border-radius: 12px !important;
        border: 1.5px dashed #cbd5e1 !important;
        height: 220px !important;
        background: #f8fafc !important;
    }

    .btn-save-coach {
        background: #2563eb;
        color: #ffffff !important;
        border: 1px solid #2563eb;
        border-radius: 9px;
        padding: 10px 24px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.28);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-save-coach:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-cancel {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569;
        border-radius: 9px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        text-decoration: none !important;
        margin-right: 10px;
        transition: all 0.15s ease;
    }

    .btn-cancel:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="coach-form-wrapper">
        <!-- Breadcrumbs -->
        <div class="fcc-breadcrumb-nav">
            <a href="{{ route('nutritionPanel.dashboard') }}">Member & Club</a>
            <span class="fcc-breadcrumb-sep">/</span>
            <a href="{{ route('nutritionPanel.coaches.index') }}">Coach Management</a>
            <span class="fcc-breadcrumb-sep">/</span>
            <span class="fcc-breadcrumb-active">Add Coach</span>
        </div>

        <!-- Header -->
        <div class="fcc-header-bar">
            <div>
                <h1 class="fcc-page-title">Add New Coach</h1>
                <p class="fcc-page-subtitle">Register a fitness & nutrition coach under your franchise.</p>
            </div>
            <div>
                <a href="{{ route('nutritionPanel.coaches.index') }}" class="btn btn-cancel">
                    <i class="fa fa-arrow-left me-1"></i> Back to Coaches
                </a>
            </div>
        </div>

        <!-- Validation Flash Message -->
        @component('nutrition-panel.validation.errors') @endcomponent

        {!! Form::open(['url' => route('nutritionPanel.coaches.store'), 'method' => 'POST', 'files' => true, 'id' => 'createCoachForm']) !!}
            <div class="coach-grid-form">
                <!-- Left: Profile Image -->
                <div class="fcc-card">
                    <div class="card-title-bar">
                        <div class="title-accent-pill"></div>
                        <h3 class="card-heading">Coach Photo</h3>
                    </div>
                    <p class="card-subheading">Upload a profile picture for this coach.</p>

                    <input type="file" name="profile_image" id="profile_image" class="dropify" data-height="220" data-max-file-size="5M" accept="image/*" />
                    
                    <div class="mt-4 pt-3 border-top">
                        <label class="form-label-styled" for="status">Status</label>
                        <select name="status" id="status" class="form-select input-field-styled" style="padding-left: 14px;">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Right: Coach Information -->
                <div class="fcc-card">
                    <div class="card-title-bar">
                        <div class="title-accent-pill"></div>
                        <h3 class="card-heading">Coach Details</h3>
                    </div>
                    <p class="card-subheading">Information and credentials for this coach.</p>

                    <!-- Full Name -->
                    <div class="form-group-custom">
                        <label class="form-label-styled" for="name">Full Name <span class="text-danger">*</span></label>
                        <div class="input-with-icon">
                            <i class="fa fa-user input-icon-left"></i>
                            <input type="text" name="name" id="name" class="form-control input-field-styled" placeholder="e.g. John Doe" value="{{ old('name') }}" required>
                        </div>
                    </div>

                    <!-- Contact Row: Mobile & Email -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-styled" for="mobile_number">Mobile Number</label>
                                <div class="input-with-icon">
                                    <i class="fa fa-phone input-icon-left"></i>
                                    <input type="tel" name="mobile_number" id="mobile_number" class="form-control input-field-styled" placeholder="e.g. 9876543210" value="{{ old('mobile_number') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-styled" for="email">Email Address</label>
                                <div class="input-with-icon">
                                    <i class="fa fa-envelope-o input-icon-left"></i>
                                    <input type="email" name="email" id="email" class="form-control input-field-styled" placeholder="coach@example.com" value="{{ old('email') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Specialization & Experience -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-styled" for="specialization">Specialization / Role</label>
                                <div class="input-with-icon">
                                    <i class="fa fa-certificate input-icon-left"></i>
                                    <input type="text" name="specialization" id="specialization" class="form-control input-field-styled" placeholder="e.g. Weight Loss & Nutrition" value="{{ old('specialization', 'Fitness Coach') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-styled" for="experience_years">Experience</label>
                                <div class="input-with-icon">
                                    <i class="fa fa-briefcase input-icon-left"></i>
                                    <input type="text" name="experience_years" id="experience_years" class="form-control input-field-styled" placeholder="e.g. 3 Years, 5+ Years" value="{{ old('experience_years') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bio / Notes -->
                    <div class="form-group-custom">
                        <label class="form-label-styled" for="bio">Bio / Notes</label>
                        <textarea name="bio" id="bio" class="form-control textarea-styled" placeholder="Brief background, certifications or coaching notes...">{{ old('bio') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end align-items-center mt-4 pt-2">
                        <a href="{{ route('nutritionPanel.coaches.index') }}" class="btn btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-save-coach">
                            <i class="fa fa-check"></i> Save Coach
                        </button>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('.dropify').dropify();
});
</script>
@endpush
