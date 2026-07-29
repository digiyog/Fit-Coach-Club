@extends('admin-panel.layouts.auth')

@section('page-title', ' '.__('language.password_recovery_page_title').' | '.__('language.page_main_title').'')

@push('styles')
    <link href="{{ asset('admin-assets/css/forms/form-2.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="form-container outer auth-page">
    <div class="form-form">
        <div class="form-form-wrap">
            <div class="form-container">
                <div class="form-content">

                    <h1 class="">Password Recovery</h1>
                    <p class="signup-link recovery"> {{ __('language.password_recovery_instructions') }} </p>

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="dark alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    {!! Form::open(['class' => 'text-left login-form', 'url' => route('adminPanel.passwordEmail'), ]) !!}

                        <!-- Validation error -->
                        @component('admin-panel.validation.errors') @endcomponent
                        <!-- / Validation error -->

                        @if (session('status'))
                            <div class="alert alert-success mb-4" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i data-feather="x" class="feather-16"></i> </button>
                                <strong>Success!</strong>
                                <div>{{ session('status') }}</div>
                            </div>
                        @endif

                        <div class="form">
                            <div id="email-field" class="field-wrapper input">
                                <div class="d-flex justify-content-between">
                                    <label for="email">EMAIL</label>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                                {!! Form::text('email', '', ['class' => 'form-control', 'id' => 'email', 'autocomplete' => 'off', 'placeholder' => __('language.email')]) !!}
                            </div>

                            <div class="d-sm-flex justify-content-between mb-50">
                                <div class="field-wrapper">
                                    {{ Form::button(__('language.reset'), ['class' => 'btn btn-primary', 'type' => 'submit'] )}}
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
    <script src="{{ asset('admin-assets/js/authentication/form-2.js') }}"></script>
    <script src="{{ asset('admin-assets/js/auth/login.js') }}"></script>
@endpush
