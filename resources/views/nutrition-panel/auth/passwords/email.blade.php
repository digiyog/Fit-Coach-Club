@extends('nutrition-panel.layouts.auth')

@section('page-title', ' '.__('language.password_recovery_page_title').' | '.__('language.page_main_title').'')

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

<div class="modern-auth-wrapper nutrition-theme">
    <div class="modern-auth-card">
        <!-- Brand / Header Section -->
        <div class="auth-header">
            <div class="auth-logo-badge nutrition">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            
            <h1 class="auth-brand-name">Password Recovery</h1>
            
            <div>
                <span class="auth-role-pill nutrition">
                    <span class="pill-dot"></span>
                    Coach & Member Recovery
                </span>
            </div>
            
            <p class="auth-subtext">{{ __('language.password_recovery_instructions') }}</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success mb-4" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Success!</strong>
                <div>{{ session('status') }}</div>
            </div>
        @endif

        {!! Form::open(['class' => 'text-start login-form', 'url' => route('adminPanel.passwordEmail'), ]) !!}
            <!-- Validation error -->
            @component('nutrition-panel.validation.errors') @endcomponent

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
                        'autocomplete' => 'off',
                        'placeholder' => __('language.email'),
                        'autofocus' => 'autofocus'
                    ]) !!}
                </div>
            </div>

            <button class="btn-modern-submit" type="submit">
                <span>{{ __('language.reset') }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>

            <div style="text-align: center; margin-top: 18px;">
                <a href="{{ route('nutritionPanel.login') }}" style="color: #059669; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Login
                </a>
            </div>
        {!! Form::close() !!}

        <!-- Security Footer -->
        <div class="auth-footer">
            <div class="auth-security-badge">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <span>Protected by 256-Bit SSL Encryption</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('admin-assets/js/authentication/form-2.js') }}"></script>
    <script src="{{ asset('admin-assets/js/auth/login.js') }}"></script>
@endpush
