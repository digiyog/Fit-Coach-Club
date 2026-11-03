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
            $isFranchise = request()->is(Request::segment(1).'/franchises*');
            $isProductType = request()->is(Request::segment(1).'/product-types*');
            $isProduct = request()->is(Request::segment(1).'/products*');
            $isMealType = request()->is(Request::segment(1).'/meal-types*');
            $isMembershipPlan = request()->is(Request::segment(1).'/membership-plans*');
            $isFranchiseMembershipPlan = request()->is(Request::segment(1).'/franchise-membership-plans*');
            $isCMSPages = request()->is(Request::segment(1).'/cms-pages*');
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

        <!-- Scrollable Navigation Area (Original Navigation Links Kept As Is) -->
        <div class="sidebar-scroll-container">
            <ul class="list-unstyled menu-categories ps" id="accordionSidebar">

                <!-- 1. Dashboard -->
                <li class="menu">
                    <a href="{{ route('adminPanel.dashboard') }}" data-active="{{ $isDashboard ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isDashboard ? 'active parent-active' : '' }}">
                        <div class="menu-item-content">
                            <i data-feather="home"></i>
                            <span>{{ __('language.dashboard_menu') }}</span>
                        </div>
                    </a>
                </li>

                <!-- 2. Franchises -->
                <li class="menu">
                    <a href="{{ route('adminPanel.franchises.index') }}" data-active="{{ $isFranchise ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isFranchise ? 'active parent-active' : 'collapsed' }}">
                        <div class="menu-item-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 7l9-4 9 4-9 4-9-4z"></path>
                                <path d="M3 7v10l9 4 9-4V7"></path>
                                <path d="M12 11v10"></path>
                            </svg>
                            <span>Franchises</span>
                        </div>
                    </a>
                </li>

                <!-- 3. Product Types -->
                <li class="menu">
                    <a href="{{ route('adminPanel.product-types.index') }}" data-active="{{ $isProductType ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isProductType ? 'active parent-active' : 'collapsed' }}">
                        <div class="menu-item-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            <span>Product Types</span>
                        </div>
                    </a>
                </li>

                <!-- 4. Products -->
                <li class="menu">
                    <a href="{{ route('adminPanel.products.index') }}" data-active="{{ $isProduct ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isProduct ? 'active parent-active' : 'collapsed' }}">
                        <div class="menu-item-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            <span>Products</span>
                        </div>
                    </a>
                </li>

                <!-- 5. Meal Types -->
                <li class="menu">
                    <a href="{{ route('adminPanel.meal-types.index') }}" data-active="{{ $isMealType ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isMealType ? 'active parent-active' : 'collapsed' }}">
                        <div class="menu-item-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                                <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                                <line x1="6" y1="1" x2="6" y2="4"></line>
                                <line x1="10" y1="1" x2="10" y2="4"></line>
                                <line x1="14" y1="1" x2="14" y2="4"></line>
                            </svg>
                            <span>Meal Types</span>
                        </div>
                    </a>
                </li>

                <!-- 6. Membership Plans -->
                <li class="menu">
                    <a href="{{ route('adminPanel.membership-plans.index') }}" data-active="{{ $isMembershipPlan ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isMembershipPlan ? 'active parent-active' : 'collapsed' }}">
                        <div class="menu-item-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            <span>Membership Plans</span>
                        </div>
                    </a>
                </li>

                <!-- 7. Franchise Membership -->
                <li class="menu">
                    <a href="{{ route('adminPanel.franchise-membership-plans.index') }}" data-active="{{ $isFranchiseMembershipPlan ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isFranchiseMembershipPlan ? 'active parent-active' : 'collapsed' }}">
                        <div class="menu-item-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            <span>Franchise Membership</span>
                        </div>
                    </a>
                </li>

                <!-- CMS Pages (Optional) -->
                @if(Route::has('adminPanel.cms-pages.index'))
                <!--
                <li class="menu">
                    <a href="{{ route('adminPanel.cms-pages.index') }}" data-active="{{ $isCMSPages ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isCMSPages ? 'active parent-active' : 'collapsed' }}">
                        <div class="menu-item-content">
                            <i data-feather="file-text"></i>
                            <span>CMS Pages</span>
                        </div>
                    </a>
                </li>
                -->
                @endif

            </ul>
        </div>
    </nav>
</div>
<!--  END SIDEBAR  -->
