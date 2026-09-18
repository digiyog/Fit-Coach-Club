@extends('admin-panel.layouts.auth')

@section('page-title', ' '.__('language.login_page_title').' | '.__('language.page_main_title').'')

@push('styles')
    <!-- Additional page styles if needed -->
@endpush

@section('content')
<div class="split-auth-container">
    <!-- ================= LEFT HERO SHOWCASE PANE ================= -->
    <div class="auth-hero-pane">
        <!-- Ambient Glowing Background & Orbits -->
        <div class="hero-ambient-glows">
            <div class="hero-glow-top-left"></div>
            <div class="hero-glow-bottom-right"></div>
            <div class="hero-orbit-line"></div>
            <div class="hero-orbit-line orbit-2"></div>
        </div>

        <div class="hero-content-wrap">
            <!-- Top Section: Brand & Headlines -->
            <div class="hero-top-block">
                <!-- Brand Header -->
                <div class="hero-brand-header">
                    <div class="hero-brand-logo" style="width: 46px; height: 46px; background: #000000; border-radius: 12px; padding: 5px; border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 4px 16px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center;">
                        <img src="{{ asset('admin-assets/images/fit-coach-club-symbol.svg') }}" alt="Fit Coach Club" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <div class="d-flex align-items-center" style="gap: 6px; font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;">
                        <span style="font-size: 22px; font-weight: 800; color: #20B2AA; letter-spacing: -0.02em;">Fit Coach</span>
                        <span style="font-size: 22px; font-weight: 800; color: #ffffff; letter-spacing: -0.02em;">Club</span>
                    </div>
                </div>

                <!-- Tagline Badge -->
                <div class="hero-tagline-badge">
                    <span class="dash"></span>
                    <span>Club Management, Simplified</span>
                </div>

                <!-- Hero Display Heading -->
                <h1 class="hero-headline">
                    Your club,<br>intelligently managed.
                </h1>

                <!-- Subheading -->
                <p class="hero-description">
                    One workspace for members, attendance, coaching and payments.
                </p>

                <!-- 3 Feature Glass Cards -->
                <div class="hero-features-grid">
                    <!-- Members Card -->
                    <div class="hero-feature-card">
                        <div class="feature-icon-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <div class="feature-text-block">
                            <span class="feature-title">Members</span>
                            <span class="feature-sub">Grow your community</span>
                        </div>
                    </div>

                    <!-- Attendance Card -->
                    <div class="hero-feature-card">
                        <div class="feature-icon-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <div class="feature-text-block">
                            <span class="feature-title">Attendance</span>
                            <span class="feature-sub">Stay on top, always</span>
                        </div>
                    </div>

                    <!-- Payments Card -->
                    <div class="hero-feature-card">
                        <div class="feature-icon-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                        </div>
                        <div class="feature-text-block">
                            <span class="feature-title">Payments</span>
                            <span class="feature-sub">Track and grow revenue</span>
                        </div>
                    </div>
                </div>

                <!-- Live Dashboard Glass Card -->
                <div class="hero-dashboard-preview">
                    <!-- Top Status Bar -->
                    <div class="dashboard-preview-header">
                        <h3 class="dashboard-preview-title">Today at your club</h3>
                        <div class="dashboard-status-pill">
                            <span class="pulse-dot"></span>
                            <span>Operations running smoothly</span>
                        </div>
                    </div>

                    <!-- Key 3 Metrics Row -->
                    <div class="dashboard-metrics-row">
                        <!-- Check-ins -->
                        <div class="metric-item">
                            <div class="metric-icon-wrap blue">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div class="metric-info">
                                <span class="metric-value">68</span>
                                <span class="metric-label">check-ins</span>
                            </div>
                        </div>

                        <!-- Renewals -->
                        <div class="metric-item">
                            <div class="metric-icon-wrap rose">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                            <div class="metric-info">
                                <span class="metric-value">11</span>
                                <span class="metric-label">renewals</span>
                            </div>
                        </div>

                        <!-- Collected -->
                        <div class="metric-item">
                            <div class="metric-icon-wrap emerald">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                    <line x1="2" y1="10" x2="22" y2="10"></line>
                                </svg>
                            </div>
                            <div class="metric-info">
                                <span class="metric-value">₹8,780</span>
                                <span class="metric-label">collected</span>
                            </div>
                        </div>
                    </div>

                    <!-- Area Sparkline Graph -->
                    <div class="dashboard-chart-container">
                        <svg class="dashboard-svg-chart" viewBox="0 0 500 75" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="chartAreaGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.35"/>
                                    <stop offset="100%" stop-color="#38bdf8" stop-opacity="0.0"/>
                                </linearGradient>
                                <linearGradient id="chartLineGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#38bdf8"/>
                                    <stop offset="50%" stop-color="#60a5fa"/>
                                    <stop offset="100%" stop-color="#a78bfa"/>
                                </linearGradient>
                            </defs>
                            <!-- Fill -->
                            <path d="M 10 60 Q 60 56 100 48 T 190 28 T 275 54 T 360 52 T 440 38 T 490 22 L 490 75 L 10 75 Z" fill="url(#chartAreaGrad)" />
                            <!-- Stroke Line -->
                            <path d="M 10 60 Q 60 56 100 48 T 190 28 T 275 54 T 360 52 T 440 38 T 490 22" fill="none" stroke="url(#chartLineGrad)" stroke-width="2.5" stroke-linecap="round" />
                            <!-- Nodes -->
                            <circle cx="10" cy="60" r="3.5" fill="#ffffff" stroke="#38bdf8" stroke-width="2" class="chart-node" />
                            <circle cx="100" cy="48" r="3.5" fill="#ffffff" stroke="#38bdf8" stroke-width="2" class="chart-node" />
                            <circle cx="190" cy="28" r="4" fill="#ffffff" stroke="#60a5fa" stroke-width="2" class="chart-node" />
                            <circle cx="275" cy="54" r="3.5" fill="#ffffff" stroke="#818cf8" stroke-width="2" class="chart-node" />
                            <circle cx="360" cy="52" r="3.5" fill="#ffffff" stroke="#818cf8" stroke-width="2" class="chart-node" />
                            <circle cx="440" cy="38" r="3.5" fill="#ffffff" stroke="#a78bfa" stroke-width="2" class="chart-node" />
                            <circle cx="490" cy="22" r="4.5" fill="#ffffff" stroke="#a78bfa" stroke-width="2.5" class="chart-node" />
                        </svg>

                        <!-- Days Labels Row -->
                        <div class="chart-dates-row">
                            <span class="chart-date-label">Aug 19</span>
                            <span class="chart-date-label">Aug 20</span>
                            <span class="chart-date-label">Aug 21</span>
                            <span class="chart-date-label">Aug 22</span>
                            <span class="chart-date-label">Aug 23</span>
                            <span class="chart-date-label">Aug 24</span>
                            <span class="chart-date-label">Aug 25</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Left Tag -->
            <div class="hero-bottom-tag">
                <span class="dash"></span>
                <span>Built for modern fitness communities</span>
            </div>
        </div>
    </div>

    <!-- ================= RIGHT LOGIN FORM PANE ================= -->
    <div class="auth-form-pane">
        <div class="modern-auth-card-elevated">
            <!-- Card Header -->
            <div class="auth-card-header">
                <h2 class="auth-card-title">Welcome back</h2>
                <p class="auth-card-subtext">Log in to your Fit Coach Club account.</p>
                <div>
                    <span class="portal-role-badge admin">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        ADMINISTRATOR PORTAL
                    </span>
                </div>
            </div>

            <!-- Form -->
            {!! Form::open(['class' => 'text-start login-form login-form-body', 'url' => route('adminPanel.login')]) !!}
                {!! Form::hidden('timezone', '', ['id' => 'timezone']) !!}

                <!-- Validation error component -->
                @component('admin-panel.validation.errors') @endcomponent

                <!-- Email Input Field -->
                <div class="input-field-group">
                    <label for="email" class="field-label">
                        Email <span class="required-star">*</span>
                    </label>
                    <div class="input-icon-container">
                        <span class="input-leading-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

                <!-- Password Input Field -->
                <div class="input-field-group">
                    <label for="password" class="field-label">
                        Password <span class="required-star">*</span>
                    </label>
                    <div class="input-icon-container">
                        <span class="input-leading-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                        <button type="button" class="input-trailing-action" id="toggle-password" tabindex="-1" title="Toggle password visibility">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Options Row: Remember Me & Forgot Password -->
                <div class="form-options-row">
                    <label class="remember-me-label" for="remember">
                        <input type="checkbox" name="remember" id="remember" class="remember-me-checkbox" value="1">
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('adminPanel.passwordRest') }}" class="forgot-password-link">
                        Forgot password?
                    </a>
                </div>

                <!-- Google reCAPTCHA if enabled -->
                @if(!app()->environment('local') && !in_array(request()->getHost(), ['localhost', '127.0.0.1']))
                    <div class="auth-recaptcha-wrap">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    </div>
                @endif

                <!-- Submit CTA Button -->
                <button class="btn-primary-action" type="submit">
                    <span>Log in</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            {!! Form::close() !!}

            <!-- Having trouble signing in divider -->
            <div class="support-help-divider">
                <span>Having trouble signing in? <a href="mailto:support@fitcoachclub.com">Contact support</a></span>
            </div>

            <!-- SSL Security Badge -->
            <div class="ssl-security-badge">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <polyline points="9 12 11 14 15 10"></polyline>
                </svg>
                <span>Protected by 256-Bit SSL Encryption</span>
            </div>

            <!-- Powered by DigiYog Technosoft Branding -->
            <div class="digiyog-brand-footer">
                <span class="powered-by-label">POWERED BY</span>
                <a href="https://digiyog.com" target="_blank" rel="noopener noreferrer" class="digiyog-logo-wrap" title="DigiYog Technosoft">
                    <img src="{{ asset('admin-assets/images/digiyog-logo.png') }}" alt="DigiYog Technosoft" class="digiyog-official-logo">
                </a>
            </div>
        </div>

        <!-- Bottom Page Footer (Copyright & Legal) -->
        <div class="auth-pane-bottom-footer">
            <span>&copy; {{ date('Y') }} {{ env('APP_NAME', 'Fit Coach Club') }}</span>
            <span class="divider">|</span>
            <a href="{{ url('privacy-policy') }}">Privacy</a>
            <span class="divider">|</span>
            <a href="mailto:support@fitcoachclub.com">Help</a>
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
            if ($('#timezone').length) {
                $('#timezone').val(moment.tz.guess());
            }
        });
    </script>
@endpush
