@extends('admin-panel.layouts.main-layout')

@section('page-title', ' '.__('language.app_configuration_page_title').' | '.__('language.page_main_title').'')

@push('styles')
@endpush

@section('content')
    <div class="layout-px-spacing">
        {!! Form::open(['class' => 'edit-configuration-form', 'method' => 'post', 'url' => route('adminPanel.appConfigurations.updateAppConfigurations', ['id' => ev($configurations->id ?? null)]) ]) !!}

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 _layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-12 page-heading">
                                <h5> {{ __('language.configurations_menu') }} </h5>
                            </div>
                        </div>
                        <div class="form p-3">
                            <div class="form-row mb-4">
                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">

                                    @php
                                    $AndroidMaintenanceMode = get_configurations(['android-app-maintenance-mode'])[0]->config_value;
                                    @endphp

                                    <label for="name"> {{ __('language.android_app_maintenance') }} </label>
                                    {!! Form::select('android_app_maintenance', create_select_options(config('constants.app_config_maintenance_mode'), 'key', 'value'), old('android_app_maintenance', ($AndroidMaintenanceMode ?? null)), ['class' => 'form-control ', 'id' => 'android_app_maintenance', 'autocomplete' => 'off']) !!}
                                </div>
                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">

                                    @php
                                    $AndroidVersion = get_configurations(['android-app-version'])[0]->config_value;
                                    @endphp

                                    <label for="name"> {{ __('language.android_app_version') }} </label>
                                    {!! Form::text('android_app_version', old('android_app_version', ($AndroidVersion ?? null)), ['class' => 'form-control', 'id' => 'android_app_version', 'placeholder' => __('language.android_app_version'), 'autocomplete' => 'off', ]) !!}
                                </div>
                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">

                                    @php
                                    $AndroidMandatoryUpdate = get_configurations(['android-app-mandatory-update'])[0]->config_value;
                                    @endphp

                                    <label for="name"> {{ __('language.android_app_mandatory_update') }} </label>
                                    {!! Form::select('android_app_mandatory_update', create_select_options(config('constants.app_config_maintenance_mode'), 'key', 'value'), old('android_app_mandatory_update', ($AndroidMandatoryUpdate ?? null)), ['class' => 'form-control ', 'id' => 'android_app_mandatory_update', 'autocomplete' => 'off']) !!}
                                </div>
                            </div>

                            <div class="form-row mb-4">
                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    
                                    @php
                                    $IosMaintenanceMode = get_configurations(['ios-app-maintenance-mode'])[0]->config_value;
                                    @endphp

                                    <label for="name"> {{ __('language.ios_app_maintenance') }} </label>
                                    {!! Form::select('ios_app_maintenance', create_select_options(config('constants.app_config_maintenance_mode'), 'key', 'value'),($IosMaintenanceMode ?? null), ['class' => 'form-control ', 'id' => 'ios_app_maintenance', 'autocomplete' => 'off']) !!}
                                </div>
                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">

                                    @php
                                    $IosVersion = get_configurations(['ios-app-version'])[0]->config_value;
                                    @endphp

                                    <label for="name"> {{ __('language.ios_app_version') }} </label>
                                    {!! Form::text('ios_app_version', old('ios_app_version', ($IosVersion ?? null)), ['class' => 'form-control', 'id' => 'ios_app_version', 'placeholder' => __('language.ios_app_version'), 'autocomplete' => 'off', ]) !!}
                                </div>
                                <div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">

                                    @php
                                    $IosMandatoryUpdate = get_configurations(['ios-app-mandatory-update'])[0]->config_value;
                                    @endphp

                                    <label for="name"> {{ __('language.ios_app_mandatory_update') }} </label>
                                    {!! Form::select('ios_app_mandatory_update', create_select_options(config('constants.app_config_maintenance_mode'), 'key', 'value'), old('ios_app_mandatory_update', ($IosMandatoryUpdate ?? null)), ['class' => 'form-control ', 'id' => 'ios_app_mandatory_update', 'autocomplete' => 'off']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 _layout-spacing">
                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_update')] )}}
            </div>
        </div>
        <br/>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/configurations/app-configuration.js') }}"></script>
@endpush
