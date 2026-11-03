<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme fcc-sidebar-wrapper">
    <nav id="sidebar">
        @php
            $company = get_company_profile();
            $headerLogo = (isset($company[0]) && isset($company[0]['header_logo_image'])) ? $company[0]['header_logo_image'] : '';
            $user = auth()->user();
            $routeParameters = request()->route()->parameters();
            $permissions = $user ? $user->permissions->pluck('slug')->toArray() : [];

            $isDashboard = request()->is(Request::segment(1).'/dashboard*') || request()->is(Request::segment(1)) || request()->is('dashboard*');
            $isFranchiseModule = request()->is(Request::segment(1).'/franchises*') || request()->is(Request::segment(1).'/membership-plans*') || request()->is(Request::segment(1).'/franchise-membership-plans*');
            $isFranchiseList = request()->is(Request::segment(1).'/franchises') || (request()->is(Request::segment(1).'/franchises*') && !request()->is(Request::segment(1).'/franchises/create*'));
            $isFranchiseCreate = request()->is(Request::segment(1).'/franchises/create*');
            $isPackages = request()->is(Request::segment(1).'/membership-plans*');
            $isSubscriptions = request()->is(Request::segment(1).'/franchise-membership-plans*');
            
            $isProductsModule = request()->is(Request::segment(1).'/products*') || request()->is(Request::segment(1).'/product-types*');
            $isMealTypes = request()->is(Request::segment(1).'/meal-types*');
            $isCmsPages = request()->is(Request::segment(1).'/cms-pages*');
            $isCompanyProfile = request()->is(Request::segment(1).'/company-profile*') || request()->is(Request::segment(1).'/profile*');
        @endphp

        <!-- Brand Header with Original Logo & Mobile Close Button -->
        <div class="fcc-sidebar-brand d-flex align-items-center justify-content-between">
            <a href="{{ route('adminPanel.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                @if(!empty($headerLogo))
                    <img src="{{get_image_url(config('constants.company_profile.image_path'), $headerLogo)}}" class="img-fluid brand-logo-img" alt="Logo" style="height: 38px;" />
                @else
                    <div style="height: 38px; width: 38px; border-radius: 9px; background: #000000; display: flex; align-items: center; justify-content: center; padding: 3px; box-shadow: 0 2px 8px rgba(0,0,0,0.18); flex-shrink: 0;">
                        <img src="{{ asset('admin-assets/images/fit-coach-club-symbol.svg') }}" alt="Fit Coach Club" style="height: 100%; width: 100%; object-fit: contain;" />
                    </div>
                @endif
                <div class="d-flex align-items-center gap-1" style="font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif; font-size: 17px; font-weight: 800;">
                    <span style="color: #20B2AA;">Fit Coach</span> <span style="color: #ffffff;">Club</span>
                </div>
            </a>
            <!-- Mobile Close Button (Touch Friendly) -->
            <button type="button" class="fcc-mobile-close-btn d-flex d-lg-none align-items-center justify-content-center" id="fccMobileCloseSidebar" aria-label="Close Sidebar">
                <i data-feather="x" style="width: 20px; height: 20px; color: #cbd5e1;"></i>
            </button>
        </div>

        <div class="sidebar-scroll-container">
            <ul class="list-unstyled menu-categories ps" id="accordionSidebar">
                <!-- Dashboard -->
                <li class="menu">
                    <a href="{{ route('adminPanel.dashboard') }}" data-active="{{ $isDashboard ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isDashboard ? 'active parent-active' : '' }}">
                        <div class="menu-item-content">
                            <i data-feather="home"></i>
                            <span>{{ __('language.dashboard_menu') }}</span>
                        </div>
                    </a>
                </li>

                <!-- Franchises (Collapsible with Submenu) -->
                <li class="menu">
                    <a href="#franchiseMenu" data-bs-toggle="collapse" data-toggle="collapse" aria-expanded="{{ $isFranchiseModule ? 'true' : 'false' }}" class="dropdown-toggle {{ $isFranchiseModule ? 'active parent-active' : 'collapsed' }}" data-active="{{ $isFranchiseModule ? 'true' : 'false' }}">
                        <div class="menu-item-content">
                            <i data-feather="archive"></i>
                            <span>Franchises</span>
                        </div>
                        <div class="menu-chevron">
                            <i data-feather="chevron-down"></i>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ $isFranchiseModule ? 'show' : '' }}" id="franchiseMenu" data-bs-parent="#accordionSidebar">
                        <li class="{{ $isFranchiseList ? 'active' : '' }}">
                            <a href="{{ route('adminPanel.franchises.index') }}" class="{{ $isFranchiseList ? 'active-sub' : '' }}">
                                <span class="sub-dot"></span>
                                <span>All Franchises</span>
                            </a>
                        </li>
                        <li class="{{ $isFranchiseCreate ? 'active' : '' }}">
                            <a href="{{ route('adminPanel.franchises.create') }}" class="{{ $isFranchiseCreate ? 'active-sub' : '' }}">
                                <span class="sub-dot"></span>
                                <span>Create Franchise</span>
                            </a>
                        </li>
                        <li class="{{ $isPackages ? 'active' : '' }}">
                            <a href="{{ route('adminPanel.membership-plans.index') }}" class="{{ $isPackages ? 'active-sub' : '' }}">
                                <span class="sub-dot"></span>
                                <span>Packages</span>
                            </a>
                        </li>
                        <li class="{{ $isSubscriptions ? 'active' : '' }}">
                            <a href="{{ route('adminPanel.franchise-membership-plans.index') }}" class="{{ $isSubscriptions ? 'active-sub' : '' }}">
                                <span class="sub-dot"></span>
                                <span>Subscriptions</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Users -->
                <li class="menu">
                    <a href="javascript:void(0);" class="dropdown-toggle collapsed">
                        <div class="menu-item-content">
                            <i data-feather="users"></i>
                            <span>Users</span>
                        </div>
                        <div class="menu-chevron">
                            <i data-feather="chevron-down"></i>
                        </div>
                    </a>
                </li>

                <!-- Clients -->
                <li class="menu">
                    <a href="javascript:void(0);" class="dropdown-toggle collapsed">
                        <div class="menu-item-content">
                            <i data-feather="user-check"></i>
                            <span>Clients</span>
                        </div>
                    </a>
                </li>

                <!-- Workouts -->
                <li class="menu">
                    <a href="javascript:void(0);" class="dropdown-toggle collapsed">
                        <div class="menu-item-content">
                            <i data-feather="activity"></i>
                            <span>Workouts</span>
                        </div>
                        <div class="menu-chevron">
                            <i data-feather="chevron-down"></i>
                        </div>
                    </a>
                </li>

                <!-- Diet Plans -->
                <li class="menu">
                    <a href="{{ route('adminPanel.meal-types.index') }}" class="dropdown-toggle {{ $isMealTypes ? 'active parent-active' : 'collapsed' }}" data-active="{{ $isMealTypes ? 'true' : 'false' }}">
                        <div class="menu-item-content">
                            <i data-feather="coffee"></i>
                            <span>Diet Plans</span>
                        </div>
                        <div class="menu-chevron">
                            <i data-feather="chevron-down"></i>
                        </div>
                    </a>
                </li>

                <!-- Products (All Products & Product Types) -->
                <li class="menu">
                    <a href="#productsMenu" data-bs-toggle="collapse" data-toggle="collapse" aria-expanded="{{ $isProductsModule ? 'true' : 'false' }}" class="dropdown-toggle {{ $isProductsModule ? 'active parent-active' : 'collapsed' }}" data-active="{{ $isProductsModule ? 'true' : 'false' }}">
                        <div class="menu-item-content">
                            <i data-feather="shopping-bag"></i>
                            <span>Products</span>
                        </div>
                        <div class="menu-chevron">
                            <i data-feather="chevron-down"></i>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ $isProductsModule ? 'show' : '' }}" id="productsMenu" data-bs-parent="#accordionSidebar">
                        <li>
                            <a href="{{ route('adminPanel.products.index') }}" class="{{ request()->is(Request::segment(1).'/products*') ? 'active-sub' : '' }}">
                                <span class="sub-dot"></span>
                                <span>All Products</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adminPanel.product-types.index') }}" class="{{ request()->is(Request::segment(1).'/product-types*') ? 'active-sub' : '' }}">
                                <span class="sub-dot"></span>
                                <span>Product Types</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Content -->
                <li class="menu">
                    <a href="{{ route('adminPanel.cms-pages.index') }}" class="dropdown-toggle {{ $isCmsPages ? 'active parent-active' : 'collapsed' }}" data-active="{{ $isCmsPages ? 'true' : 'false' }}">
                        <div class="menu-item-content">
                            <i data-feather="file-text"></i>
                            <span>Content</span>
                        </div>
                    </a>
                </li>

                <!-- Reports -->
                <li class="menu">
                    <a href="javascript:void(0);" class="dropdown-toggle collapsed">
                        <div class="menu-item-content">
                            <i data-feather="bar-chart-2"></i>
                            <span>Reports</span>
                        </div>
                        <div class="menu-chevron">
                            <i data-feather="chevron-down"></i>
                        </div>
                    </a>
                </li>

                <!-- Settings -->
                <li class="menu">
                    <a href="#settingsMenu" data-bs-toggle="collapse" data-toggle="collapse" aria-expanded="{{ $isCompanyProfile ? 'true' : 'false' }}" class="dropdown-toggle {{ $isCompanyProfile ? 'active parent-active' : 'collapsed' }}" data-active="{{ $isCompanyProfile ? 'true' : 'false' }}">
                        <div class="menu-item-content">
                            <i data-feather="settings"></i>
                            <span>Settings</span>
                        </div>
                        <div class="menu-chevron">
                            <i data-feather="chevron-down"></i>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ $isCompanyProfile ? 'show' : '' }}" id="settingsMenu" data-bs-parent="#accordionSidebar">
                        <li>
                            <a href="{{ route('adminPanel.company-profile.index') }}">
                                <span class="sub-dot"></span>
                                <span>Company Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adminPanel.profile') }}">
                                <span class="sub-dot"></span>
                                <span>My Profile</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>
<!--  END SIDEBAR  -->
