@php
$company = get_company_profile();
$headerLogo = (isset($company[0]) && isset($company[0]['header_logo_image'])) ? $company[0]['header_logo_image'] : '';
$authUser = auth()->user();
$userInitial = !empty($authUser->name) ? strtoupper(substr(trim($authUser->name), 0, 1)) : 'A';

$authUserProfileImage = null;
if (!empty($authUser->profile_image)) {
    if (\Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path_thumb') . $authUser->profile_image)) {
        $authUserProfileImage = get_image_url(config('constants.users.image_path_thumb'), $authUser->profile_image);
    } elseif (\Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path') . $authUser->profile_image)) {
        $authUserProfileImage = get_image_url(config('constants.users.image_path'), $authUser->profile_image);
    } else {
        $authUserProfileImage = get_image_url(config('constants.users.image_path'), $authUser->profile_image);
    }
}
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
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user-icon shadow-sm p-0" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #eff2fe 0%, #e0e7ff 100%); border: 1.5px solid #c7d2fe; display: flex; align-items: center; justify-content: center; color: #3b46f1 !important; font-weight: 800; font-size: 14px; font-family: 'Outfit', sans-serif; overflow: hidden;">
                    @if($authUserProfileImage)
                        <img src="{{ $authUserProfileImage }}" alt="{{ $authUser->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block;" />
                    @else
                        {{ $userInitial }}
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="userProfileDropdown" style="border-radius: 14px; min-width: 230px; padding: 10px; border: 1px solid #e2e8f0 !important; box-shadow: 0 16px 36px -6px rgba(15, 23, 42, 0.14) !important; font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;">
                    <div class="px-3 py-2 border-bottom d-flex align-items-center gap-3">
                        <div style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #eff2fe 0%, #e0e7ff 100%); border: 1.5px solid #c7d2fe; display: flex; align-items: center; justify-content: center; color: #3b46f1; font-weight: 800; font-size: 14px; flex-shrink: 0; overflow: hidden;">
                            @if($authUserProfileImage)
                                <img src="{{ $authUserProfileImage }}" alt="{{ $authUser->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block;" />
                            @else
                                {{ $userInitial }}
                            @endif
                        </div>
                        <div style="min-width: 0;">
                            <div class="fw-bold text-dark text-truncate" style="font-size: 13.5px;">{{ $authUser->name }}</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 11.5px;">{{ $authUser->email ?? 'Super Admin' }}</small>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="dropdown-item p-0">
                            <a class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-2" href="{{ route('adminPanel.profile') }}" style="font-size: 13px; font-weight: 600; transition: background 0.15s ease;">
                                <i data-feather="user" class="me-2 text-primary" style="width: 16px; height: 16px;"></i> My Profile
                            </a>
                        </div>
                        <div class="dropdown-divider my-1"></div>
                        <div class="dropdown-item p-0">
                            <a class="d-flex align-items-center px-3 py-2 text-danger text-decoration-none rounded-2" href="{{ route('adminPanel.logout') }}" style="font-size: 13px; font-weight: 700; transition: background 0.15s ease;">
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