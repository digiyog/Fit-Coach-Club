<!--  BEGIN SIDEBAR  -->

<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">
        <div class="shadow-bottom"></div>
        @php
            $user = auth()->user();
            $routeParameters = request()->route()->parameters();
            $permissions = $user->permissions->pluck('slug')->toArray();
        @endphp

        <ul class="list-unstyled menu-categories ps" id="accordionExample">
            @php
                if(request()->is(Request::segment(1).'/dashboard*')) {
                $activeDashboard = 'true';
                } else {
                $activeDashboard = 'false';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('adminPanel.dashboard') }}" data-active="{{ $activeDashboard }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i data-feather="home"></i>
                        <span>{{ __('language.dashboard_menu') }}</span>
                    </div>
                </a>
            </li>

            <!-- Franchise Management Start -->
            @php
                $showFranchise = 'false';
                if(request()->is(Request::segment(1).'/franchises*')){
                    $showFranchise = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('adminPanel.franchises.index') }}" data-active="{{ $showFranchise }}" class="dropdown-toggle collapsed">
                    <div class="d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 7l9-4 9 4-9 4-9-4z"></path>
                            <path d="M3 7v10l9 4 9-4V7"></path>
                            <path d="M12 11v10"></path>
                        </svg>
                        <span>Franchises</span>
                    </div>
                </a>
            </li>
            <!-- Franchise Management End -->

            <!-- Product Type Management Start -->
            @php
                $showProductType = 'false';
                if(request()->is(Request::segment(1).'/product-types*')){
                    $showProductType = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('adminPanel.product-types.index') }}" data-active="{{ $showProductType }}" class="dropdown-toggle collapsed">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-shopping-bag">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <span>Product Types</span>
                    </div>
                </a>
            </li>
            <!-- Product Type Management End -->

            <!-- Product Management Start -->
            @php
                $showProduct = 'false';
                if(request()->is(Request::segment(1).'/products*')){
                    $showProduct = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('adminPanel.products.index') }}" data-active="{{ $showProduct }}" class="dropdown-toggle collapsed">
                    <div class="d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-shopping-bag">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <span>Products</span>
                    </div>
                </a>
            </li>
            <!-- Product Management End -->

            <!-- Meal Type Management Start -->
            @php
                $showMealType = 'false';
                if(request()->is(Request::segment(1).'/meal-types*')){
                    $showMealType = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('adminPanel.meal-types.index') }}" data-active="{{ $showMealType }}" class="dropdown-toggle collapsed">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-coffee"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                        <span>Meal Types</span>
                    </div>
                </a>
            </li>
            <!-- Meal Type Management End -->

            <!-- Membership Plan Management Start -->
            @php
                $showMembershipPlan = 'false';
                if(request()->is(Request::segment(1).'/membership-plans*')){
                    $showMembershipPlan = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('adminPanel.membership-plans.index') }}" data-active="{{ $showMembershipPlan }}" class="dropdown-toggle collapsed">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-credit-card">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <span>Membership Plans</span>
                    </div>
                </a>
            </li>
            <!-- Membership Plan Management End -->

            <!-- Franchise Membership Management Start -->
            @php
                $showFranchiseMembershipPlan = 'false';
                if(request()->is(Request::segment(1).'/franchise-membership-plans*')){
                    $showFranchiseMembershipPlan = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('adminPanel.franchise-membership-plans.index') }}" data-active="{{ $showFranchiseMembershipPlan }}" class="dropdown-toggle collapsed">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-credit-card">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <span>Franchise Membership</span>
                    </div>
                </a>
            </li>
            <!-- Franchise Membership Management End -->

            <!-- CMS Pages Start -->
            @php
                $showCMSPages = 'false';
                if(request()->is(Request::segment(1).'/cms-pages*')){
                    $showCMSPages = 'true';
                }
            @endphp

            <!-- <li class="menu">
                <a href="{{ route('adminPanel.cms-pages.index') }}" data-active="{{ $showCMSPages }}" class="dropdown-toggle collapsed">
                    <div class="">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                        <span>CMS Pages</span>
                    </div>
                </a>
            </li> -->
            <!-- CMS Pages End -->

        </ul>
    </nav>
</div>
<!--  END SIDEBAR  -->