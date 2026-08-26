@php
$company = get_company_profile();
$headerLogo = (isset($company[0]) && isset($company[0]['header_logo_image'])) ? $company[0]['header_logo_image'] : '';
$authUser = auth()->user();
@endphp

<!--  BEGIN NAVBAR  -->
<div class="header-container fixed-top" style="background: #ffffff; border-bottom: 1px solid #eef2f7; height: 56px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
    <header class="header navbar navbar-expand-sm py-0 px-3" style="height: 56px;">

        <ul class="navbar-item flex-row align-items-center">
            <li class="nav-item theme-logo me-2">
                <a href="{{ route('nutritionPanel.dashboard') }}" class="nav-link p-0 d-flex align-items-center text-decoration-none">
                    @if(!empty($headerLogo))
                        <img src="{{get_image_url(config('constants.company_profile.image_path'), $headerLogo)}}" class="img-fluid brand-logo-img" alt="Logo" style="height: 38px; border-radius: 8px;" />
                    @else
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #3246d3 0%, #4361ee 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; box-shadow: 0 2px 8px rgba(50, 70, 211, 0.28);">
                            FC
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item theme-text d-none d-sm-block ps-1">
                <a href="{{ route('nutritionPanel.dashboard') }}" class="nav-link fw-bold text-dark fs-5 p-0 text-decoration-none" style="letter-spacing: -0.02em; font-family: 'Plus Jakarta Sans', sans-serif;">
                    {{ config('app.name', 'Fit Coach Club') }}
                </a>
            </li>
            <li class="nav-item ms-3">
                <a href="javascript:void(0);" class="sidebarCollapse btn btn-light rounded-circle p-2 d-flex align-items-center justify-content-center" data-bs-placement="bottom" style="width: 34px; height: 34px; background: #f8fafc; border: 1px solid #e2e8f0;">
                    <i data-feather="menu" style="width: 16px; height: 16px; color: #475569;"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-item flex-row ms-auto align-items-center gap-2">
            <li class="nav-item d-none d-md-flex align-items-center me-1">
                <div class="user-greeting-pill" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 5px 12px; border-radius: 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 8px;">
                    <span class="user-status-dot" style="width: 7px; height: 7px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                    <span class="fw-semibold text-dark">{{ $authUser->name }}</span>
                    <span class="badge" style="background: #eff1fe; color: #3246d3; font-size: 11px; font-weight: 700; border-radius: 6px;">Nutrition Panel</span>
                </div>
            </li>

            <li class="nav-item dropdown user-profile-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user-icon shadow-sm" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 36px; height: 36px; border-radius: 50%; background: #f1f5f9; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; color: #475569 !important;">
                    <i data-feather="user" style="width: 17px; height: 17px; color: #475569;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="userProfileDropdown" style="border-radius: 14px; min-width: 200px; padding: 8px;">
                    <div class="px-3 py-2 border-bottom d-md-none">
                        <div class="fw-bold text-dark">{{ $authUser->name }}</div>
                        <small class="text-muted">{{ $authUser->email ?? '' }}</small>
                    </div>
                    <div class="p-1">
                        <div class="dropdown-item">
                            <a class="d-flex align-items-center py-2 text-dark" href="{{ route('nutritionPanel.profile') }}">
                                <i data-feather="user" class="me-2 text-primary" style="width: 16px; height: 16px;"></i> My Profile
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a class="d-flex align-items-center py-2 text-dark" href="{{ route('nutritionPanel.change-password.index') }}">
                                <i data-feather="lock" class="me-2 text-warning" style="width: 16px; height: 16px;"></i> Change Password
                            </a>
                        </div>
                        <div class="dropdown-divider my-1"></div>
                        <div class="dropdown-item">
                            <a class="d-flex align-items-center py-2 text-danger" href="{{ route('nutritionPanel.logout') }}">
                                <i data-feather="log-out" class="me-2 text-danger" style="width: 16px; height: 16px;"></i> Sign Out
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </header>
</div>
<!--  END NAVBAR  -->