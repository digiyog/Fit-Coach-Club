@php
$company = get_company_profile();
$headerLogo = (isset($company[0]) && isset($company[0]['header_logo_image'])) ? $company[0]['header_logo_image'] : '';
$authUser = auth()->user();
$isDashboard = request()->routeIs('adminPanel.dashboard*') || request()->is('admin-panel/dashboard*') || request()->is('admin-panel') || request()->is('admin-panel/');

// Universal Breadcrumb Trail Builder for Admin Panel
$headerCrumbs = [];

if (!$isDashboard) {
    $rawCrumbs = $breadcrumb ?? $breadcrumbFilter ?? null;

    if (is_array($rawCrumbs) && count($rawCrumbs) > 0) {
        foreach ($rawCrumbs as $label => $url) {
            $trimmed = trim((string)$label);
            if (empty($trimmed)) continue;
            $headerCrumbs[] = [
                'label' => $trimmed,
                'url' => !empty($url) ? $url : null,
            ];
        }
    }

    // Fallback if controller did not supply full breadcrumb trail
    if (empty($headerCrumbs) || count($headerCrumbs) < 2) {
        $headerCrumbs = [
            ['label' => 'Dashboard', 'url' => route('adminPanel.dashboard')]
        ];

        $segment2 = request()->segment(2); // e.g. 'product-types'
        $segment3 = request()->segment(3); // e.g. 'create', 'edit'

        $moduleMap = [
            'product-types' => ['title' => 'Product Types', 'route' => 'adminPanel.product-types.index', 'singular' => 'Product Type'],
            'products' => ['title' => 'Products', 'route' => 'adminPanel.products.index', 'singular' => 'Product'],
            'franchises' => ['title' => 'Franchises', 'route' => 'adminPanel.franchises.index', 'singular' => 'Franchise'],
            'meal-types' => ['title' => 'Meal Types', 'route' => 'adminPanel.meal-types.index', 'singular' => 'Meal Type'],
            'membership-plans' => ['title' => 'Membership Plans', 'route' => 'adminPanel.membership-plans.index', 'singular' => 'Membership Plan'],
            'franchise-membership-plans' => ['title' => 'Franchise Membership Plans', 'route' => 'adminPanel.franchise-membership-plans.index', 'singular' => 'Franchise Membership Plan'],
            'cms-pages' => ['title' => 'CMS Pages', 'route' => 'adminPanel.cms-pages.index', 'singular' => 'CMS Page'],
            'profile' => ['title' => 'My Profile', 'route' => 'adminPanel.profile', 'singular' => 'Profile'],
            'company-profile' => ['title' => 'Company Profile', 'route' => 'adminPanel.company-profile.index', 'singular' => 'Company Profile'],
            'users' => ['title' => 'Users', 'route' => 'adminPanel.users.index', 'singular' => 'User'],
            'roles' => ['title' => 'Roles', 'route' => 'adminPanel.roles.index', 'singular' => 'Role'],
        ];

        if (!empty($segment2) && $segment2 !== 'dashboard') {
            $modInfo = $moduleMap[$segment2] ?? [
                'title' => ucwords(str_replace('-', ' ', $segment2)),
                'route' => 'adminPanel.' . $segment2 . '.index',
                'singular' => ucwords(str_replace('-', ' ', rtrim($segment2, 's')))
            ];

            $modUrl = \Illuminate\Support\Facades\Route::has($modInfo['route']) ? route($modInfo['route']) : null;

            if (!empty($segment3) && !is_numeric($segment3)) {
                $headerCrumbs[] = [
                    'label' => $modInfo['title'],
                    'url' => $modUrl
                ];

                $actionLabel = ucwords(str_replace('-', ' ', $segment3));
                if ($segment3 === 'create') {
                    $actionLabel = 'Create ' . $modInfo['singular'];
                } elseif ($segment3 === 'edit') {
                    $actionLabel = 'Edit ' . $modInfo['singular'];
                }

                $headerCrumbs[] = [
                    'label' => $actionLabel,
                    'url' => null
                ];
            } else {
                $headerCrumbs[] = [
                    'label' => $modInfo['title'],
                    'url' => null
                ];
            }
        }
    } else {
        // Beautify trailing generic action label ('Create' -> 'Create [Singular Parent]', 'Edit' -> 'Edit [Singular Parent]')
        $lastIdx = count($headerCrumbs) - 1;
        if ($lastIdx > 0) {
            $lastLower = strtolower($headerCrumbs[$lastIdx]['label']);
            $parentLabel = $headerCrumbs[$lastIdx - 1]['label'];
            $parentSingular = rtrim($parentLabel, 's');

            if (in_array($lastLower, ['create', 'add', 'add new', strtolower(__('language.create'))])) {
                $headerCrumbs[$lastIdx]['label'] = 'Create ' . $parentSingular;
            } elseif (in_array($lastLower, ['edit', 'update', strtolower(__('language.edit'))])) {
                $headerCrumbs[$lastIdx]['label'] = 'Edit ' . $parentSingular;
            }
        }
    }
}
@endphp

<!--  BEGIN NAVBAR  -->
<div class="header-container fixed-top fcc-header-container" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; height: 68px;">
    <header class="header navbar navbar-expand-sm py-0 px-3 px-md-4 d-flex align-items-center justify-content-between h-100">

        @if($isDashboard)
            <!-- Dashboard Left Header: Circular Hamburger + Dashboard Overview Title -->
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
            <!-- Universal Left Header: Circular Hamburger + Breadcrumbs Trail (Dashboard › Section › ...) -->
            <div class="d-flex align-items-center">
                <a href="javascript:void(0);" class="sidebarCollapse me-3 btn btn-light rounded-circle p-2 d-flex align-items-center justify-content-center" data-bs-placement="bottom" style="width: 38px; height: 38px; border: 1px solid #e2e8f0; background: #ffffff;" aria-label="Toggle Sidebar">
                    <i data-feather="menu" style="width: 18px; height: 18px; color: #1e293b;"></i>
                </a>
                <div class="d-flex align-items-center gap-2 fcc-header-breadcrumbs" style="font-family: 'Plus Jakarta Sans', sans-serif; flex-wrap: nowrap; overflow-x: auto; white-space: nowrap;">
                    @foreach($headerCrumbs as $index => $crumb)
                        @if($index > 0)
                            <i data-feather="chevron-right" style="width: 14px; height: 14px; color: #94a3b8; flex-shrink: 0;"></i>
                        @endif

                        @if(!empty($crumb['url']) && $index < count($headerCrumbs) - 1)
                            <a href="{{ $crumb['url'] }}" style="font-size: 14.5px; font-weight: 600; color: #64748b; text-decoration: none; transition: color 0.15s ease;">
                                {{ $crumb['label'] }}
                            </a>
                        @else
                            <span style="font-size: 14.5px; font-weight: 700; color: #0f172a;">
                                {{ $crumb['label'] }}
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Right Header Items: Filter Pill + Search + Notifications + User Profile -->
        <div class="d-flex align-items-center gap-2 gap-md-3">
            @if($isDashboard)
            <!-- Date Filter Pill (Trigger for filter drawer) -->
            <button type="button" class="d-none d-sm-inline-flex align-items-center gap-2 px-3 py-2 text-decoration-none" id="headerDateRangeBtn" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 9px; font-size: 12.5px; font-weight: 600; color: #334155; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.02); transition: all 0.2s ease;">
                <i data-feather="calendar" style="width: 14px; height: 14px; color: #1d4ed8;"></i>
                <span>Last 6 Months</span>
                <i data-feather="chevron-down" style="width: 13px; height: 13px; color: #94a3b8;"></i>
            </button>
            @endif

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