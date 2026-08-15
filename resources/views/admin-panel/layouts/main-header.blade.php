@php
$company = get_company_profile();
$headerLogo = (isset($company[0]) && isset($company[0]['header_logo_image'])) ? $company[0]['header_logo_image'] : '';
$authUser = auth()->user();
@endphp

<!--  BEGIN NAVBAR  -->
<div class="header-container fixed-top">
    <header class="header navbar navbar-expand-sm py-0 px-3">

        <ul class="navbar-item flex-row align-items-center">
            <li class="nav-item theme-logo me-2">
                <a href="{{ route('adminPanel.dashboard') }}" class="nav-link p-0 d-flex align-items-center">
                    @if(!empty($headerLogo))
                        <img src="{{get_image_url(config('constants.company_profile.image_path'), $headerLogo)}}" class="img-fluid brand-logo-img" alt="Logo" style="height: 38px;" />
                    @else
                        <div class="brand-logo-fallback">FC</div>
                    @endif
                </a>
            </li>
            <li class="nav-item theme-text d-none d-sm-block">
                <a href="{{ route('adminPanel.dashboard') }}" class="nav-link fw-bold text-primary fs-5 p-0">
                    {{ config('app.name', 'Fit-Coach Club') }}
                </a>
            </li>
            <li class="nav-item ms-3">
                <a href="javascript:void(0);" class="sidebarCollapse btn btn-light rounded-circle p-2 d-flex align-items-center justify-content-center" data-bs-placement="bottom" style="width: 36px; height: 36px;">
                    <i data-feather="menu" style="width: 18px; height: 18px; color: #3246d3;"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-item flex-row ms-auto align-items-center gap-2">
            <li class="nav-item d-none d-md-flex align-items-center me-1">
                <div class="user-greeting-pill">
                    <span class="user-status-dot"></span>
                    <span class="fw-semibold text-dark">{{ $authUser->name }}</span>
                    <span class="badge badge-purple ms-2" style="font-size: 11px;">Super Admin</span>
                </div>
            </li>

            <li class="nav-item dropdown user-profile-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user-icon shadow-sm" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="user" style="width: 20px; height: 20px;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="userProfileDropdown">
                    <div class="px-3 py-2 border-bottom d-md-none">
                        <div class="fw-bold text-dark">{{ $authUser->name }}</div>
                        <small class="text-muted">{{ $authUser->email ?? '' }}</small>
                    </div>
                    <div class="p-1">
                        <div class="dropdown-item">
                            <a class="d-flex align-items-center py-2 text-dark" href="{{ route('adminPanel.profile') }}">
                                <i data-feather="user" class="me-2 text-primary" style="width: 16px; height: 16px;"></i> My Profile
                            </a>
                        </div>
                        <div class="dropdown-divider my-1"></div>
                        <div class="dropdown-item">
                            <a class="d-flex align-items-center py-2 text-danger" href="{{ route('adminPanel.logout') }}">
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