@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Change Password | '.__('language.page_main_title').'')

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
                                <i data-feather="key" class="text-primary" style="width: 22px; height: 22px;"></i>
                                {{ __('language.change_password') }}
                            </h4>
                            <p class="admin-card-subtitle">Ensure your account is using a long, random password to stay secure.</p>
                        </div>
                    </div>

                    <div class="form pb-2">
                        {!! Form::open(['class' => 'change-password-form', 'method' => 'post', 'url' => route('adminPanel.profile.updatePassword'), 'enctype' => 'multipart/form-data' ]) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="current_password">{{ __('language.current_password') }} <span class="text-danger">*</span></label>
                                        {!! Form::password('current_password', ['class' => 'form-control', 'id' => 'current_password', 'placeholder' => 'Enter your current password', 'autocomplete' => 'off', ]) !!}
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_password">{{ __('language.new_password') }} <span class="text-danger">*</span></label>
                                        {!! Form::password('new_password', ['class' => 'form-control', 'id' => 'new_password', 'placeholder' => 'Enter new password', 'autocomplete' => 'off' ] ) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="confirm_password">{{ __('language.confirm_password') }} <span class="text-danger">*</span></label>
                                        {!! Form::password('confirm_password', ['class' => 'form-control', 'id' => 'confirm_password', 'placeholder' => 'Confirm new password', 'autocomplete' => 'off' ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="admin-form-actions">
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_update') ] )}}
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
<script src="{{ asset('admin-assets/js/users/admin-profile.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush