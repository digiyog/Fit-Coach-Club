@extends('admin-panel.layouts.main-layout')

@section('page-title', ' '.__('language.app_configuration_page_title').' | '.__('language.page_main_title').'')

@push('styles')
@endpush

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="admin-card-header">
                        <div>
                            <h4 class="admin-card-title">
                                <i data-feather="settings" class="text-primary" style="width: 22px; height: 22px;"></i>
                                {{ __('language.configurations_menu') }}
                            </h4>
                            <p class="admin-card-subtitle">Manage mobile application versioning, mandatory updates, and maintenance modes.</p>
                        </div>
                    </div>

                    <div class="form pb-2">
                        {!! Form::open(['class' => 'edit-configuration-form', 'method' => 'post', 'url' => route('adminPanel.appConfigurations.updateAppConfigurations', ['id' => ev($configurations->id ?? null)]) ]) !!}
                            <!-- Android Configuration Section -->
                            <h6 class="admin-section-title mt-2">
                                <i data-feather="smartphone" class="text-success" style="width: 16px; height: 16px;"></i>
                                Android App Settings
                            </h6>

                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="form-group">
                                        @php
                                            $AndroidMaintenanceMode = optional(get_configurations(['android-app-maintenance-mode'])->first())->config_value;
                                        @endphp
                                        <label for="android_app_maintenance">{{ __('language.android_app_maintenance') }}</label>
                                        {!! Form::select('android_app_maintenance', create_select_options(config('constants.app_config_maintenance_mode'), 'key', 'value'), old('android_app_maintenance', ($AndroidMaintenanceMode ?? null)), ['class' => 'form-control select-picker', 'id' => 'android_app_maintenance', 'autocomplete' => 'off']) !!}
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="form-group">
                                        @php
                                            $AndroidVersion = optional(get_configurations(['android-app-version'])->first())->config_value;
                                        @endphp
                                        <label for="android_app_version">{{ __('language.android_app_version') }}</label>
                                        {!! Form::text('android_app_version', old('android_app_version', ($AndroidVersion ?? null)), ['class' => 'form-control', 'id' => 'android_app_version', 'placeholder' => 'e.g. 1.0.0', 'autocomplete' => 'off', ]) !!}
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="form-group">
                                        @php
                                            $AndroidMandatoryUpdate = optional(get_configurations(['android-app-mandatory-update'])->first())->config_value;
                                        @endphp
                                        <label for="android_app_mandatory_update">{{ __('language.android_app_mandatory_update') }}</label>
                                        {!! Form::select('android_app_mandatory_update', create_select_options(config('constants.app_config_maintenance_mode'), 'key', 'value'), old('android_app_mandatory_update', ($AndroidMandatoryUpdate ?? null)), ['class' => 'form-control select-picker', 'id' => 'android_app_mandatory_update', 'autocomplete' => 'off']) !!}
                                    </div>
                                </div>
                            </div>

                            <!-- iOS Configuration Section -->
                            <h6 class="admin-section-title mt-4">
                                <i data-feather="tablet" class="text-info" style="width: 16px; height: 16px;"></i>
                                iOS App Settings
                            </h6>

                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="form-group">
                                        @php
                                            $IosMaintenanceMode = optional(get_configurations(['ios-app-maintenance-mode'])->first())->config_value;
                                        @endphp
                                        <label for="ios_app_maintenance">{{ __('language.ios_app_maintenance') }}</label>
                                        {!! Form::select('ios_app_maintenance', create_select_options(config('constants.app_config_maintenance_mode'), 'key', 'value'), ($IosMaintenanceMode ?? null), ['class' => 'form-control select-picker', 'id' => 'ios_app_maintenance', 'autocomplete' => 'off']) !!}
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="form-group">
                                        @php
                                            $IosVersion = optional(get_configurations(['ios-app-version'])->first())->config_value;
                                        @endphp
                                        <label for="ios_app_version">{{ __('language.ios_app_version') }}</label>
                                        {!! Form::text('ios_app_version', old('ios_app_version', ($IosVersion ?? null)), ['class' => 'form-control', 'id' => 'ios_app_version', 'placeholder' => 'e.g. 1.0.0', 'autocomplete' => 'off', ]) !!}
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="form-group">
                                        @php
                                            $IosMandatoryUpdate = optional(get_configurations(['ios-app-mandatory-update'])->first())->config_value;
                                        @endphp
                                        <label for="ios_app_mandatory_update">{{ __('language.ios_app_mandatory_update') }}</label>
                                        {!! Form::select('ios_app_mandatory_update', create_select_options(config('constants.app_config_maintenance_mode'), 'key', 'value'), old('ios_app_mandatory_update', ($IosMandatoryUpdate ?? null)), ['class' => 'form-control select-picker', 'id' => 'ios_app_mandatory_update', 'autocomplete' => 'off']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="admin-form-actions">
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_update')] )}}
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/configurations/app-configuration.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
