@extends('auction-panel.layouts.auth')

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
        .form-form-wrap{
            max-width: 100% !important;
        }
        .field-wrapper input {
            max-width: 100% !important;
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
                    <p class="signup-link register">Already have an account? <a href="{{ route('auctionPanel.login') }}">Log in</a></p>
                    <!-- <p class=""> Regsiter your account.</p> -->
                    {!! Form::open(['class' => 'text-left register-form', 'url' => route('auctionPanel.registerUser'), ]) !!}
                    {!! Form::hidden('timezone', '', ['id' => 'timezone']) !!}

                        <!-- Validation error -->
                        @component('auction-panel.validation.errors') @endcomponent
                        <!-- / Validation error -->

                        <div class="row">
                            <div class="col-md-6">
                                <div id="username-field" class="field-wrapper input">
                                    <div class="d-flex justify-content-between">
                                        <label for="name">{{ __('language.name') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" style="top: 44px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    {!! Form::text('name', '', ['class' => 'form-control', 'id' => 'name', 'autocomplete' => 'on', 'placeholder' => __('language.name'), 'autocomplete' => 'off', 'autofocus']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="username-field" class="field-wrapper input">
                                    <div class="d-flex justify-content-between">
                                        <label for="city"> City <span class="text-danger">*</span></label>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" style="top: 44px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    {!! Form::text('city', '', ['class' => 'form-control', 'id' => 'city', 'autocomplete' => 'on', 'placeholder' => 'City', 'autocomplete' => 'off', 'autofocus']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="username-field" class="field-wrapper input">
                                    <div class="d-flex justify-content-between">
                                        <label for="mobile_number">{{ __('language.mobile_number') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" style="top: 44px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    {!! Form::text('mobile_number', '', ['class' => 'form-control', 'id' => 'mobile_number', 'autocomplete' => 'on', 'placeholder' => __('language.mobile_number'), 'autocomplete' => 'off', 'autofocus', 'data-url' => route('auctionPanel.users.checkMobile') ]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="username-field" class="field-wrapper input">
                                    <div class="d-flex justify-content-between">
                                        <label for="email">{{ __('language.email') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" style="top: 44px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    {!! Form::text('email', '', ['class' => 'form-control', 'id' => 'email', 'autocomplete' => 'on', 'placeholder' => __('language.email'), 'autocomplete' => 'off', 'autofocus', 'data-url' => route('auctionPanel.users.checkEmail') ]) !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div id="password-field" class="field-wrapper input mb-2">
                                    <div class="d-flex justify-content-between">
                                        <label for="password" class="text-uppercase">{{ __('language.password') }} <span class="text-danger">*</span></label>
                                    </div>

                                    <svg xmlns="http://www.w3.org/2000/svg" style="top: 44px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>

                                    {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'autocomplete' => 'on', 'placeholder' => __('language.password')]) !!}

                                    <svg xmlns="http://www.w3.org/2000/svg" style="top: 42px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="role_type" id="" value="users">

                        <div class="d-sm-flex justify-content-between mb-30">
                            <div class="field-wrapper">
                                {{ Form::button( 'Register', ['class' => 'btn btn-primary btn-submit', 'type' => 'submit'] )}}
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
    <script src="{{ asset('admin-assets/js/authentication/form-2.js') }}"></script>
    <script src="{{ asset('admin-assets/js/auth/register.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data-2012-2022.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#timezone').val(moment.tz.guess());
        });
    </script>
@endpush