@php
$company = get_company_profile();
$headerLogo = (isset($company[0]) && isset($company[0]['header_logo_image'])) ? $company[0]['header_logo_image'] : '';
$authUser = auth()->user();
$userInitial = !empty($authUser->name) ? strtoupper(substr(trim($authUser->name), 0, 1)) : 'U';
@endphp

<!--  BEGIN NAVBAR  -->
<div class="header-container fixed-top" style="background: #ffffff; border-bottom: 1px solid #edf2f7; height: 58px; box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04); z-index: 1040;">
    <header class="header navbar navbar-expand-sm py-0 px-3 px-md-4 d-flex align-items-center justify-content-between" style="height: 58px;">

        <ul class="navbar-item flex-row align-items-center mb-0 ps-0 list-unstyled gap-2">
            <li class="nav-item theme-logo">
                <a href="{{ route('nutritionPanel.dashboard') }}" class="nav-link p-0 d-flex align-items-center text-decoration-none">
                    @if(!empty($headerLogo))
                        <img src="{{get_image_url(config('constants.company_profile.image_path'), $headerLogo)}}" class="img-fluid brand-logo-img" alt="Logo" style="height: 38px; border-radius: 9px;" />
                    @else
                        <div style="height: 38px; width: 38px; border-radius: 9px; background: #000000; display: flex; align-items: center; justify-content: center; padding: 3px; box-shadow: 0 2px 8px rgba(0,0,0,0.18);">
                            <img src="{{ asset('admin-assets/images/fit-coach-club-symbol.svg') }}" alt="Fit Coach Club" style="height: 100%; width: 100%; object-fit: contain;" />
                        </div>
                    @endif
                </a>
            </li>
            <li class="nav-item theme-text d-none d-sm-block ps-1">
                <a href="{{ route('nutritionPanel.dashboard') }}" class="nav-link fw-bold text-dark fs-5 p-0 text-decoration-none" style="letter-spacing: -0.025em; font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif; font-size: 17px !important; display: flex; align-items: center; gap: 4px;">
                    <span style="color: #20B2AA; font-weight: 800;">Fit Coach</span> <span style="color: #0f172a; font-weight: 800;">Club</span>
                </a>
            </li>
            <li class="nav-item ms-2">
                <a href="javascript:void(0);" class="sidebarCollapse btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" data-bs-placement="bottom" style="width: 34px; height: 34px; background: #f8fafc; border: 1px solid #e2e8f0; transition: all 0.2s ease;">
                    <i data-feather="menu" style="width: 16px; height: 16px; color: #475569;"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-item flex-row ms-auto align-items-center gap-2 mb-0 list-unstyled">
            <li class="nav-item d-none d-md-flex align-items-center me-1">
                <div class="user-greeting-pill" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 5px 12px; border-radius: 20px; font-size: 12.5px; display: inline-flex; align-items: center; gap: 8px; font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;">
                    <span class="user-status-dot" style="width: 7px; height: 7px; border-radius: 50%; background: #10b981; box-shadow: 0 0 6px rgba(16, 185, 129, 0.5); display: inline-block;"></span>
                    <span class="fw-bold" style="color: #1e293b;">{{ $authUser->name }}</span>
                    <span class="badge" style="background: #eff2fe; color: #3b46f1; font-size: 11px; font-weight: 700; border-radius: 6px; padding: 3px 7px; border: 1px solid rgba(59, 70, 241, 0.15);">Nutrition Panel</span>
                </div>
            </li>

            <li class="nav-item dropdown user-profile-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user-icon shadow-sm p-0" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #eff2fe 0%, #e0e7ff 100%); border: 1.5px solid #c7d2fe; display: flex; align-items: center; justify-content: center; color: #3b46f1 !important; font-weight: 800; font-size: 14px; font-family: 'Outfit', sans-serif;">
                    {{ $userInitial }}
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="userProfileDropdown" style="border-radius: 14px; min-width: 215px; padding: 8px; border: 1px solid #e2e8f0 !important; box-shadow: 0 16px 36px -6px rgba(15, 23, 42, 0.14) !important; font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;">
                    <div class="px-3 py-2 border-bottom">
                        <div class="fw-bold text-dark" style="font-size: 13.5px;">{{ $authUser->name }}</div>
                        <small class="text-muted" style="font-size: 11.5px;">{{ $authUser->email ?? 'Club Coach' }}</small>
                    </div>
                    <div class="pt-2">
                        <div class="dropdown-item p-0">
                            <a class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-2" href="{{ route('nutritionPanel.profile') }}" style="font-size: 13px; font-weight: 600; transition: background 0.15s ease;">
                                <i data-feather="user" class="me-2 text-primary" style="width: 15px; height: 15px;"></i> My Profile
                            </a>
                        </div>
                        <div class="dropdown-item p-0">
                            <a class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-2" href="{{ route('nutritionPanel.change-password.index') }}" style="font-size: 13px; font-weight: 600; transition: background 0.15s ease;">
                                <i data-feather="lock" class="me-2 text-warning" style="width: 15px; height: 15px;"></i> Change Password
                            </a>
                        </div>
                        <div class="dropdown-divider my-1"></div>
                        <div class="dropdown-item p-0">
                            <a class="d-flex align-items-center px-3 py-2 text-danger text-decoration-none rounded-2" href="{{ route('nutritionPanel.logout') }}" style="font-size: 13px; font-weight: 700; transition: background 0.15s ease;">
                                <i data-feather="log-out" class="me-2 text-danger" style="width: 15px; height: 15px;"></i> Sign Out
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </header>
</div>
<!--  END NAVBAR  -->