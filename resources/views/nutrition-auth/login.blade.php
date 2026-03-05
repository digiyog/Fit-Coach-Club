@extends('nutrition-panel.layouts.auth')

@section('page-title', ' '.$pageTitle.' ')

@push('styles')
    <link href="{{ asset('admin-assets/css/forms/switches.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/forms/form-2.css') }}" rel="stylesheet">
    <style>
        #role-field{
            padding: 0px 0px 0px 0;
        }

        #role-field label{
            font-size: unset !important;
        }
        label.new-control{
            width: 44.55%;
        }
    </style>
@endpush

@section('content')
<div class="form-container outer auth-page">
    <div class="form-form">
        <div class="form-form-wrap">
            <div class="form-container">
                <div class="form-content">

                    <h1 class="">{{ env('APP_NAME') }}</h1>
                    <p class=""> {{ __('language.login_instructions') }}</p>
                    {!! Form::open(['class' => 'text-left login-form', 'url' => route('nutritionPanel.login'), ]) !!}
                    {!! Form::hidden('timezone', '', ['id' => 'timezone']) !!}

                        <!-- Validation error -->
                        @component('nutrition-panel.validation.errors') @endcomponent
                        <!-- / Validation error -->

                            <div id="username-field" class="field-wrapper input">
                                <div class="d-flex justify-content-between">
                                    <label for="email">{{ __('language.email') }} <span class="text-danger">*</span></label>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" style="top: 44px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                                {!! Form::text('email', '', ['class' => 'form-control', 'id' => 'email', 'autocomplete' => 'on', 'placeholder' => __('language.email'), 'autocomplete' => 'off', 'autofocus']) !!}
                            </div>

                            <div id="password-field" class="field-wrapper input mb-2">
                                <div class="d-flex justify-content-between">
                                    <label for="password" class="text-uppercase">{{ __('language.password') }} <span class="text-danger">*</span></label>
                                </div>

                                <svg xmlns="http://www.w3.org/2000/svg" style="top: 44px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>

                                {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'autocomplete' => 'on', 'placeholder' => __('language.password')]) !!}

                                <svg xmlns="http://www.w3.org/2000/svg" style="top: 42px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </div>

                            <input type="hidden" name="role_type" id="" value="users">
                            <div class="g-recaptcha mb-5" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>

                            <div class="d-sm-flex justify-content-between mb-30 mt-5">
                                <div class="field-wrapper mt-5">
                                    {{ Form::button( __('language.login'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit'] )}}
                                </div>
                            </div>
                        </div>
                    
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="{{ asset('admin-assets/js/authentication/form-2.js') }}"></script>
    <script src="{{ asset('admin-assets/js/auth/login.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data-2012-2022.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#timezone').val(moment.tz.guess());
        });
    </script>
@endpush
