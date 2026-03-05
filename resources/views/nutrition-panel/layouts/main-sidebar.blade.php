 BEGIN SIDEBAR  -->

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
                <a href="{{ route('nutritionPanel.dashboard') }}" data-active="{{ $activeDashboard }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i data-feather="home"></i>
                        <span>{{ __('language.dashboard_menu') }}</span>
                    </div>
                </a>
            </li>

            <!-- User Management Start -->
            @php
                $showUserManagement = 'false';
                $activeUserManagement = '';
                $activeAllUserList = '';
                $activeDemoUserList = '';
                $activeOfflineUserList = '';
                $activeOnlineUserList = '';

                if(request()->is(Request::segment(1).'/users*')){
                    $showUserManagement = 'true';
                    $activeUserManagement = 'show';
                    $activeAllUserList = 'active';
                }

                if(request()->is(Request::segment(2).'/demo*')){
                    $showUserManagement = 'true';
                    $activeUserManagement = 'show';
                    $activeDemoUserList = 'active';
                }

                if(request()->is(Request::segment(2).'/offline*')){
                    $showUserManagement = 'true';
                    $activeUserManagement = 'show';
                    $activeOfflineUserList = 'active';
                }

                if(request()->is(Request::segment(2).'/online*')){
                    $showUserManagement = 'true';
                    $activeUserManagement = 'show';
                    $activeOnlineUserList = 'active';
                }

            @endphp

            <li class="menu">
                <a href="#userManagement" data-toggle="collapse" aria-expanded="{{ $showUserManagement }}" data-active="{{ $showUserManagement }}" class="dropdown-toggle collapsed">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             width="24" height="24" viewBox="0 0 24 24" 
                             fill="none" stroke="currentColor" stroke-width="2" 
                             stroke-linecap="round" stroke-linejoin="round" 
                             class="feather feather-users">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>

                        <span>User Management</span>
                    </div>
                    <div>
                        <i data-feather="chevron-right"></i>
                    </div>
                </a>
                <ul class="submenu list-unstyled collapse {{ $activeUserManagement }}" id="userManagement" data-parent="#userManagement" style="">
                    <li class="{{ $activeAllUserList }}">
                        <a href="{{ route('nutritionPanel.users.index') }}">All Users</a>
                    </li>

                    <li class="{{ $activeDemoUserList }}">
                        <a href="{{ route('nutritionPanel.users.index') }}/demo">Demo Users</a>
                    </li>

                    <li class="{{ $activeOfflineUserList }}">
                        <a href="{{ route('nutritionPanel.users.index') }}/offline">Offline Users</a>
                    </li>

                    <li class="{{ $activeOnlineUserList }}">
                        <a href="{{ route('nutritionPanel.users.index') }}/online">Online Users</a>
                    </li>
                </ul>
            </li>
            <!-- User Management End -->

            <!-- Offline System Management Start -->
            @php
                $showOfflineSystemManagement = 'false';
                $activeOfflineSystemManagement = '';
                $activeOfflineSystemList = '';
                $activeCounsellingList = '';

                if(request()->is(Request::segment(1).'/attendence-register*')){
                    $showOfflineSystemManagement = 'true';
                    $activeOfflineSystemManagement = 'show';
                    $activeOfflineSystemList = 'active';
                }

                if(request()->is(Request::segment(1).'/counsellings*')){
                    $showOfflineSystemManagement = 'true';
                    $activeOfflineSystemManagement = 'show';
                    $activeCounsellingList = 'active';
                }

            @endphp

            <li class="menu">
                <a href="#offlineSystemManagement" data-toggle="collapse" aria-expanded="{{ $showOfflineSystemManagement }}" data-active="{{ $showOfflineSystemManagement }}" class="dropdown-toggle collapsed">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>

                        <span>Offline System</span>
                    </div>
                    <div>
                        <i data-feather="chevron-right"></i>
                    </div>
                </a>
                <ul class="submenu list-unstyled collapse {{ $activeOfflineSystemManagement }}" id="offlineSystemManagement" data-parent="#offlineSystemManagement" style="">
                    <li class="{{ $activeOfflineSystemList }}">
                        <a href="{{ route('nutritionPanel.attendence-register.index') }}">Attendance Register</a>
                    </li>

                    <li class="{{ $activeCounsellingList }}">
                        <a href="{{ route('nutritionPanel.counsellings.index') }}">Counsellings ({{ date('d-m-Y') }})</a>
                    </li>
                </ul>
            </li>
            <!-- Offline System Management End -->

            <!-- Achievement Management Start -->
            @php
                $showAchievementManagement = 'false';
                $activeAchievementManagement = '';
                $activeAchievementList = '';
                $activeCommunityPhotoList = '';
                $activeActivityList = '';
                $activeTestimonialList = '';
                $activeTipList = '';

                if(request()->is(Request::segment(1).'/achievements*')){
                    $showAchievementManagement = 'true';
                    $activeAchievementManagement = 'show';
                    $activeAchievementList = 'active';
                }

                if(request()->is(Request::segment(1).'/community-photos*')){
                    $showAchievementManagement = 'true';
                    $activeAchievementManagement = 'show';
                    $activeCommunityPhotoList = 'active';
                }

                if(request()->is(Request::segment(1).'/activities*')){
                    $showAchievementManagement = 'true';
                    $activeAchievementManagement = 'show';
                    $activeActivityList = 'active';
                }

                if(request()->is(Request::segment(1).'/testimonials*')){
                    $showAchievementManagement = 'true';
                    $activeAchievementManagement = 'show';
                    $activeTestimonialList = 'active';
                }

                if(request()->is(Request::segment(1).'/tips*')){
                    $showAchievementManagement = 'true';
                    $activeAchievementManagement = 'show';
                    $activeTipList = 'active';
                }

            @endphp

            <li class="menu">
                <a href="#achievementManagement" data-toggle="collapse" aria-expanded="{{ $showAchievementManagement }}" data-active="{{ $showAchievementManagement }}" class="dropdown-toggle collapsed">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             width="24" height="24" viewBox="0 0 24 24" 
                             fill="none" stroke="currentColor" stroke-width="2" 
                             stroke-linecap="round" stroke-linejoin="round" 
                             class="feather feather-star">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 
                                             18.18 21.02 12 17.77 5.82 21.02 
                                             7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <span>Achievement Management</span>

                    </div>
                    <div>
                        <i data-feather="chevron-right"></i>
                    </div>
                </a>
                <ul class="submenu list-unstyled collapse {{ $activeAchievementManagement }}" id="achievementManagement" data-parent="#achievementManagement" style="">
                    <li class="{{ $activeAchievementList }}">
                        <a href="{{ route('nutritionPanel.achievements.index') }}">Achievements</a>
                    </li>

                    <li class="{{ $activeCommunityPhotoList }}">
                        <a href="{{ route('nutritionPanel.community-photos.index') }}">Community Photos</a>
                    </li>

                    <li class="{{ $activeActivityList }}">
                        <a href="{{ route('nutritionPanel.activities.index') }}">Activities</a>
                    </li>

                    <li class="{{ $activeTestimonialList }}">
                        <a href="{{ route('nutritionPanel.testimonials.index') }}">Testimonials</a>
                    </li>

                    <li class="{{ $activeTipList }}">
                        <a href="{{ route('nutritionPanel.tips.index') }}">Tips (Youtube Links)</a>
                    </li>
                </ul>
            </li>
            <!-- Achievement Management End -->

            <!-- Dish Type Management Start -->
            @php
                $showDishType = 'false';
                if(request()->is(Request::segment(1).'/dish-types*')){
                    $showDishType = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('nutritionPanel.dish-types.index') }}" data-active="{{ $showDishType }}" class="dropdown-toggle collapsed">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-coffee"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                        <span>Dish Types</span>
                    </div>
                </a>
            </li>
            <!-- Dish Type Management End -->

            <!-- Custom Dish Management Start -->
            @php
                $showCustomDish = 'false';
                if(request()->is(Request::segment(1).'/custom-dishes*')){
                    $showCustomDish = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('nutritionPanel.custom-dishes.index') }}" data-active="{{ $showCustomDish }}" class="dropdown-toggle collapsed">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-coffee"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                        <span>Custom Dishes</span>
                    </div>
                </a>
            </li>
            <!-- Custom Dish Management End -->

            <!-- Calculate Calories Management Start -->
            @php
                $showCalculateCalories = 'false';
                if(request()->is(Request::segment(1).'/calculate-calories*')){
                    $showCalculateCalories = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('nutritionPanel.calculate-calories.index') }}" data-active="{{ $showCalculateCalories }}" class="dropdown-toggle collapsed">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                        <span>Calculate Calories</span>
                    </div>
                </a>
            </li>
            <!-- Calculate Calories Management End -->

            <!-- Miscellaneous Management Start -->
            @php
                $showMiscellaneousManagement = 'false';
                $activeMiscellaneousManagement = '';
                $activeProfileList = '';
                $activeChangePasswordList = '';
                $activeShakeIntakeList = '';

                if(request()->is(Request::segment(1).'/profile*')){
                    $showMiscellaneousManagement = 'true';
                    $activeMiscellaneousManagement = 'show';
                    $activeProfileList = 'active';
                }

                if(request()->is(Request::segment(1).'/change-password*')){
                    $showMiscellaneousManagement = 'true';
                    $activeMiscellaneousManagement = 'show';
                    $activeChangePasswordList = 'active';
                }

                if(request()->is(Request::segment(1).'/shake-intakes*')){
                    $showMiscellaneousManagement = 'true';
                    $activeMiscellaneousManagement = 'show';
                    $activeShakeIntakeList = 'active';
                }

            @endphp

            <li class="menu">
                <a href="#miscellaneousManagement" data-toggle="collapse" aria-expanded="{{ $showMiscellaneousManagement }}" data-active="{{ $showMiscellaneousManagement }}" class="dropdown-toggle collapsed">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder-plus"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg>
                        <span>Miscellaneous</span>
                    </div>
                    <div>
                        <i data-feather="chevron-right"></i>
                    </div>
                </a>
                <ul class="submenu list-unstyled collapse {{ $activeMiscellaneousManagement }}" id="miscellaneousManagement" data-parent="#miscellaneousManagement" style="">
                    <li class="{{ $activeProfileList }}">
                        <a href="{{ route('nutritionPanel.profile') }}">Your Info</a>
                    </li>
                    <li class="{{ $activeChangePasswordList }}">
                        <a href="{{ route('nutritionPanel.change-password.index') }}">Change Password</a>
                    </li>
                    <li class="{{ $activeShakeIntakeList }}">
                        <a href="{{ route('nutritionPanel.shake-intakes.index') }}">Shake Intakes</a>
                    </li>
                </ul>
            </li>
            <!-- Miscellaneous Management End -->

            <!-- Body Parameters Management Start -->
            @php
                $showBmiCalculator = 'false';
                if(request()->is(Request::segment(1).'/bmi-calculator*')){
                    $showBmiCalculator = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('nutritionPanel.bmi-calculator.index') }}" data-active="{{ $showBmiCalculator }}" class="dropdown-toggle collapsed">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                        <span>Body Parameters</span>
                    </div>
                </a>
            </li>
            <!-- Body Parameters Management End -->

            <!-- Transactions Start -->
            @php
                $showTransactions = 'false';
                $activeTransactions = '';
                $activeTransactionsList = '';

                if(request()->is(Request::segment(1).'/transactions*')){
                    $showTransactions = 'true';
                    $activeTransactions = 'show';
                    $activeTransactionsList = 'active';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('nutritionPanel.transactions.index') }}" aria-expanded="{{ $showTransactions }}" data-active="{{ $showTransactions }}" class="dropdown-toggle">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                            <polyline points="2 17 12 22 22 17"></polyline>
                            <polyline points="2 12 12 17 22 12"></polyline>
                        </svg>
                        <span>Transactions</span>
                    </div>
                </a>
            </li>
            <!-- / Transactions End -->

            <!-- Orders Start -->
            @php
                $showOrders = 'false';
                $activeOrders = '';
                $activeOrdersList = '';

                if(request()->is(Request::segment(1).'/orders*')){
                    $showOrders = 'true';
                    $activeOrders = 'show';
                    $activeOrdersList = 'active';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('nutritionPanel.orders.index') }}" aria-expanded="{{ $showOrders }}" data-active="{{ $showOrders }}" class="dropdown-toggle">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                            <polyline points="2 17 12 22 22 17"></polyline>
                            <polyline points="2 12 12 17 22 12"></polyline>
                        </svg>
                        <span>Orders</span>
                    </div>
                </a>
            </li>
            <!-- / Orders End -->

            <!-- Reviews Start -->
            @php
                $showReviews = 'false';
                $activeReviews = '';
                $activeReviewsList = '';

                if(request()->is(Request::segment(1).'/reviews*')){
                    $showReviews = 'true';
                    $activeReviews = 'show';
                    $activeReviewsList = 'active';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('nutritionPanel.reviews.index') }}" aria-expanded="{{ $showReviews }}" data-active="{{ $showReviews }}" class="dropdown-toggle">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                            <polyline points="2 17 12 22 22 17"></polyline>
                            <polyline points="2 12 12 17 22 12"></polyline>
                        </svg>
                        <span>Reviews</span>
                    </div>
                </a>
            </li>
            <!-- / Reviews End -->

            <!-- Membership Management Start -->
            @php
                $showMembershipPlan = 'false';
                if(request()->is(Request::segment(1).'/membership-plans*')){
                    $showMembershipPlan = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('nutritionPanel.membership-plans.index') }}" data-active="{{ $showMembershipPlan }}" class="dropdown-toggle collapsed">
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
            <!-- Membership Management End -->
            
        </ul>
    </nav>
</div>
<!--  END SIDEBAR 