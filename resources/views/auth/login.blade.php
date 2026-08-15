@extends('admin-panel.layouts.auth')

@section('page-title', ' '.__('language.login_page_title').' | '.__('language.page_main_title').'')

@push('styles')
    <!-- Additional page styles if needed -->
@endpush

@section('content')
<!-- Ambient Animated Background -->
<div class="auth-ambient-bg">
    <div class="auth-ambient-grid"></div>
    <div class="auth-glow-orb-1"></div>
    <div class="auth-glow-orb-2"></div>
</div>

<div class="modern-auth-wrapper admin-theme">
    <div class="modern-auth-card">
        <!-- Brand / Header Section -->
        <div class="auth-header">
            <div class="auth-logo-badge admin">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            
            <h1 class="auth-brand-name">{{ env('APP_NAME', 'Fit Coach Club') }}</h1>
            
            <div>
                <span class="auth-role-pill admin">
                    <span class="pill-dot"></span>
                    Admin Portal
                </span>
            </div>
            
            <p class="auth-subtext">{{ __('language.login_instructions') }}</p>
        </div>

        <!-- Form Section -->
        {!! Form::open(['class' => 'text-start login-form', 'url' => route('adminPanel.login')]) !!}
            {!! Form::hidden('timezone', '', ['id' => 'timezone']) !!}

            <!-- Validation error component -->
            @component('admin-panel.validation.errors') @endcomponent

            <!-- Email Field -->
            <div class="modern-form-group">
                <label for="email" class="modern-form-label">
                    {{ __('language.email') }} <span class="required-star">*</span>
                </label>
                <div class="modern-input-wrapper">
                    <span class="input-icon-left">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </span>
                    {!! Form::text('email', '', [
                        'class' => 'form-control',
                        'id' => 'email',
                        'placeholder' => 'admin@fitcoachclub.com',
                        'autocomplete' => 'off',
                        'autofocus' => 'autofocus'
                    ]) !!}
                </div>
            </div>

            <!-- Password Field -->
            <div class="modern-form-group">
                <label for="password" class="modern-form-label">
                    {{ __('language.password') }} <span class="required-star">*</span>
                </label>
                <div class="modern-input-wrapper">
                    <span class="input-icon-left">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    {!! Form::password('password', [
                        'class' => 'form-control',
                        'id' => 'password',
                        'placeholder' => '••••••••••••',
                        'autocomplete' => 'off'
                    ]) !!}
                    <button type="button" class="password-toggle-btn" id="toggle-password" tabindex="-1" title="Toggle password visibility">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Google reCAPTCHA -->
            @if(!app()->environment('local') && !in_array(request()->getHost(), ['localhost', '127.0.0.1']))
                <div class="auth-recaptcha-wrap">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                </div>
            @endif

            <!-- Submit Button -->
            <button class="btn-modern-submit" type="submit">
                <span>{{ __('language.login') }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        {!! Form::close() !!}

        <!-- Security Footer -->
        <div class="auth-footer">
            <div class="auth-security-badge">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <span>256-Bit SSL Encrypted Admin Access</span>
            </div>
            <div class="auth-footer-copyright">
                &copy; {{ date('Y') }} {{ env('APP_NAME', 'Fit Coach Club') }}. All rights reserved.
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @if(!app()->environment('local') && !in_array(request()->getHost(), ['localhost', '127.0.0.1']))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
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
