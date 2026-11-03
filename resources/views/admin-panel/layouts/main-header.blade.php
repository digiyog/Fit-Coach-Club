@php
$company = get_company_profile();
$headerLogo = (isset($company[0]) && isset($company[0]['header_logo_image'])) ? $company[0]['header_logo_image'] : '';
$authUser = auth()->user();
$isDashboard = request()->routeIs('adminPanel.dashboard*') || request()->is('admin-panel/dashboard*');
@endphp

<!--  BEGIN NAVBAR  -->
<div class="header-container fixed-top fcc-header-container" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; height: 68px;">
    <header class="header navbar navbar-expand-sm py-0 px-3 px-md-4 d-flex align-items-center justify-content-between h-100">

        @if($isDashboard)
            <!-- Dashboard Specific Left Header: Hamburger + Title + Subcrumb -->
            <div class="d-flex align-items-center">
                <a href="javascript:void(0);" class="sidebarCollapse me-3 btn btn-light rounded-circle p-2 d-flex align-items-center justify-content-center" data-bs-placement="bottom" style="width: 38px; height: 38px; border: 1px solid #e2e8f0; background: #ffffff;" aria-label="Toggle Sidebar">
                    <i data-feather="menu" style="width: 18px; height: 18px; color: #1e293b;"></i>
                </a>
                <div class="d-flex flex-column">
                    <h1 class="fcc-header-page-title mb-0" style="font-size: 19px; font-weight: 800; color: #0f172a; line-height: 1.2; font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.01em;">Dashboard Overview</h1>
                    <span class="fcc-header-subcrumb text-muted" style="font-size: 11.5px; font-weight: 500;">Home / Overview</span>
                </div>
            </div>
        @else
            <!-- Standard Header Left: Brand Logo & Title -->
            <ul class="navbar-item flex-row align-items-center mb-0 ps-0">
                <li class="nav-item theme-logo me-2">
                    <a href="{{ route('adminPanel.dashboard') }}" class="nav-link p-0 d-flex align-items-center">
                        @if(!empty($headerLogo))
                            <img src="{{get_image_url(config('constants.company_profile.image_path'), $headerLogo)}}" class="img-fluid brand-logo-img" alt="Logo" style="height: 38px;" />
                        @else
                            <div style="height: 38px; width: 38px; border-radius: 9px; background: #000000; display: flex; align-items: center; justify-content: center; padding: 3px; box-shadow: 0 2px 8px rgba(0,0,0,0.18);">
                                <img src="{{ asset('admin-assets/images/fit-coach-club-symbol.svg') }}" alt="Fit Coach Club" style="height: 100%; width: 100%; object-fit: contain;" />
                            </div>
                        @endif
                    </a>
                </li>
                <li class="nav-item theme-text d-none d-sm-block">
                    <a href="{{ route('adminPanel.dashboard') }}" class="nav-link fw-bold text-primary fs-5 p-0 text-decoration-none" style="display: flex; align-items: center; gap: 4px; font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;">
                        <span style="color: #20B2AA; font-weight: 800;">Fit Coach</span> <span style="color: #0f172a; font-weight: 800;">Club</span>
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <a href="javascript:void(0);" class="sidebarCollapse btn btn-light rounded-circle p-2 d-flex align-items-center justify-content-center" data-bs-placement="bottom" style="width: 38px; height: 38px; border: 1px solid #e2e8f0; background: #ffffff;">
                        <i data-feather="menu" style="width: 18px; height: 18px; color: #3246d3;"></i>
                    </a>
                </li>
            </ul>
        @endif

        <!-- Right Header Items: Filter Pill + Search + Notifications + User Profile -->
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <!-- Date Filter Pill (Trigger for filter drawer) -->
            <button type="button" class="d-none d-sm-inline-flex align-items-center gap-2 px-3 py-2 text-decoration-none" id="headerDateRangeBtn" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 9px; font-size: 12.5px; font-weight: 600; color: #334155; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.02); transition: all 0.2s ease;">
                <i data-feather="calendar" style="width: 14px; height: 14px; color: #1d4ed8;"></i>
                <span>Last 6 Months</span>
                <i data-feather="chevron-down" style="width: 13px; height: 13px; color: #94a3b8;"></i>
            </button>

            <!-- Search Icon Button -->
            <button type="button" class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border: 1px solid #e2e8f0; background: #ffffff; color: #475569;" aria-label="Search">
                <i data-feather="search" style="width: 16px; height: 16px;"></i>
            </button>

            <!-- Notification Bell with Red Dot -->
            <div class="position-relative">
                <button type="button" class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border: 1px solid #e2e8f0; background: #ffffff; color: #475569;" aria-label="Notifications">
                    <i data-feather="bell" style="width: 16px; height: 16px;"></i>
                </button>
                <span style="position: absolute; top: 6px; right: 7px; width: 7px; height: 7px; background: #ef4444; border-radius: 50%; border: 1.5px solid #ffffff;"></span>
            </div>

            <!-- User Profile Dropdown Pill -->
            <div class="dropdown user-profile-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle d-flex align-items-center gap-2 p-0 text-decoration-none" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="fcc-user-avatar-wrap" style="width: 36px; height: 36px; border-radius: 50%; overflow: hidden; border: 1.5px solid #e2e8f0; background: #eff6ff; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #1d4ed8; font-size: 13px; flex-shrink: 0;">
                        @if(!empty($authUser->photo))
                            <img src="{{ get_image_url(config('constants.user.image_path'), $authUser->photo) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;" />
                        @else
                            <span>{{ strtoupper(substr($authUser->name ?? 'A', 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="d-none d-md-flex flex-column text-start" style="line-height: 1.2;">
                        <span style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $authUser->name ?? 'Admin' }}</span>
                        <span style="font-size: 11px; font-weight: 500; color: #64748b;">Super Admin</span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="userProfileDropdown" style="border-radius: 12px; min-width: 190px; padding: 6px; margin-top: 10px;">
                    <div class="px-3 py-2 border-bottom d-md-none">
                        <div class="fw-bold text-dark">{{ $authUser->name }}</div>
                        <small class="text-muted">{{ $authUser->email ?? '' }}</small>
                    </div>
                    <div class="p-1">
                        <a class="dropdown-item py-2 text-dark rounded d-flex align-items-center" href="{{ route('adminPanel.profile') }}">
                            <i data-feather="user" class="me-2 text-primary" style="width: 15px; height: 15px;"></i> My Profile
                        </a>
                        <div class="dropdown-divider my-1"></div>
                        <a class="dropdown-item py-2 text-danger rounded d-flex align-items-center" href="{{ route('adminPanel.logout') }}">
                            <i data-feather="log-out" class="me-2 text-danger" style="width: 15px; height: 15px;"></i> Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </header>
</div>
<!--  END NAVBAR  -->
