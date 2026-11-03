<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme fcc-sidebar-wrapper">
    <nav id="sidebar">
        @php
            $company = get_company_profile();
            $headerLogo = (isset($company[0]) && isset($company[0]['header_logo_image'])) ? $company[0]['header_logo_image'] : '';
            $user = auth()->user();

            $isDashboard = request()->is(Request::segment(1).'/dashboard*') || request()->routeIs('adminPanel.dashboard*') || request()->is('admin-panel/dashboard*');
            $isFranchise = request()->is(Request::segment(1).'/franchises*') || request()->routeIs('adminPanel.franchises.*');
            $isProductType = request()->is(Request::segment(1).'/product-types*') || request()->routeIs('adminPanel.product-types.*');
            $isProduct = request()->is(Request::segment(1).'/products*') || request()->routeIs('adminPanel.products.*');
            $isMealType = request()->is(Request::segment(1).'/meal-types*') || request()->routeIs('adminPanel.meal-types.*');
            $isMembershipPlan = request()->is(Request::segment(1).'/membership-plans*') || request()->routeIs('adminPanel.membership-plans.*');
            $isFranchiseMembershipPlan = request()->is(Request::segment(1).'/franchise-membership-plans*') || request()->routeIs('adminPanel.franchise-membership-plans.*');
        @endphp

        <!-- Brand Header with Logo & Close [X] Button -->
        <div class="fcc-sidebar-brand d-flex align-items-center justify-content-between">
            <a href="{{ route('adminPanel.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                @if(!empty($headerLogo))
                    <img src="{{get_image_url(config('constants.company_profile.image_path'), $headerLogo)}}" class="img-fluid brand-logo-img" alt="Logo" style="height: 38px;" />
                @else
                    <div class="fcc-sidebar-logo-box">
                        <img src="{{ asset('admin-assets/images/fit-coach-club-symbol.svg') }}" alt="Fit Coach Club" style="height: 100%; width: 100%; object-fit: contain;" />
                    </div>
                @endif
                <div class="fcc-brand-title">
                    <span class="brand-text-accent">Fit Coach</span> <span class="brand-text-white">Club</span>
                </div>
            </a>
            <!-- Close [X] Button -->
            <button type="button" class="fcc-sidebar-close-btn" id="fccSidebarCloseBtn" aria-label="Toggle Sidebar">
                <i data-feather="x" style="width: 18px; height: 18px;"></i>
            </button>
        </div>

        <!-- Scrollable Navigation Area -->
        <div class="sidebar-scroll-container">
            <ul class="list-unstyled menu-categories ps" id="accordionSidebar">

                <!-- 1. Dashboard -->
                <li class="menu">
                    <a href="{{ route('adminPanel.dashboard') }}" data-active="{{ $isDashboard ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isDashboard ? 'active parent-active' : '' }}">
                        <div class="menu-item-icon-box">
                            <i data-feather="home"></i>
                        </div>
                        <span class="menu-item-title">{{ __('language.dashboard_menu') }}</span>
                    </a>
                </li>

                <!-- 2. Franchises -->
                <li class="menu">
                    <a href="{{ route('adminPanel.franchises.index') }}" data-active="{{ $isFranchise ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isFranchise ? 'active parent-active' : 'collapsed' }}">
                        <div class="menu-item-icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <span class="menu-item-title">Franchises</span>
                    </a>
                </li>

                <!-- 3. Product Types -->
                <li class="menu">
                    <a href="{{ route('adminPanel.product-types.index') }}" data-active="{{ $isProductType ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isProductType ? 'active parent-active' : 'collapsed' }}">
                        <div class="menu-item-icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                        </div>
                        <span class="menu-item-title">Product Types</span>
                    </a>
                </li>

                <!-- 4. Products -->
                <li class="menu">
                    <a href="{{ route('adminPanel.products.index') }}" data-active="{{ $isProduct ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isProduct ? 'active parent-active' : 'collapsed' }}">
                        <div class="menu-item-icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                        </div>
                        <span class="menu-item-title">Products</span>
                    </a>
                </li>

                <!-- 5. Meal Types -->
                <li class="menu">
                    <a href="{{ route('adminPanel.meal-types.index') }}" data-active="{{ $isMealType ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isMealType ? 'active parent-active' : 'collapsed' }}">
                        <div class="menu-item-icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                                <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                                <line x1="6" y1="1" x2="6" y2="4"></line>
                                <line x1="10" y1="1" x2="10" y2="4"></line>
                                <line x1="14" y1="1" x2="14" y2="4"></line>
                            </svg>
                        </div>
                        <span class="menu-item-title">Meal Types</span>
                    </a>
                </li>

                <!-- 6. Membership Plans -->
                <li class="menu">
                    <a href="{{ route('adminPanel.membership-plans.index') }}" data-active="{{ $isMembershipPlan ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isMembershipPlan ? 'active parent-active' : 'collapsed' }}">
                        <div class="menu-item-icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                        </div>
                        <span class="menu-item-title">Membership Plans</span>
                    </a>
                </li>

                <!-- 7. Franchise Membership -->
                <li class="menu">
                    <a href="{{ route('adminPanel.franchise-membership-plans.index') }}" data-active="{{ $isFranchiseMembershipPlan ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle {{ $isFranchiseMembershipPlan ? 'active parent-active' : 'collapsed' }}">
                        <div class="menu-item-icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                        </div>
                        <span class="menu-item-title">Franchise Membership</span>
                    </a>
                </li>

            </ul>
        </div>

        <!-- Decorative Organic Waves at Sidebar Bottom -->
        <div class="fcc-sidebar-waves" aria-hidden="true">
            <svg viewBox="0 0 260 130" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M-10 80 C 60 40, 150 110, 270 50 L 270 140 L -10 140 Z" fill="url(#waveGradBack)" opacity="0.45"/>
                <path d="M-10 100 C 80 65, 170 115, 270 75 L 270 140 L -10 140 Z" fill="url(#waveGradFront)"/>
                <defs>
                    <linearGradient id="waveGradBack" x1="0" y1="40" x2="260" y2="130" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#0284c7" stop-opacity="0.8"/>
                        <stop offset="1" stop-color="#1e40af" stop-opacity="0.3"/>
                    </linearGradient>
                    <linearGradient id="waveGradFront" x1="0" y1="65" x2="260" y2="130" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#1d4ed8"/>
                        <stop offset="0.6" stop-color="#1e40af"/>
                        <stop offset="1" stop-color="#07122b"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

    </nav>
</div>
<!--  END SIDEBAR  -->
