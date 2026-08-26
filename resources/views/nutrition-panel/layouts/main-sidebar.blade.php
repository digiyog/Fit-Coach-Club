<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">
        @php
            $user = auth()->user();
            $routeParameters = request()->route()->parameters();
            $permissions = $user->permissions->pluck('slug')->toArray();
        @endphp

        <ul class="list-unstyled menu-categories ps" id="accordionExample">
            
            <!-- CATEGORY: MAIN -->
            <li class="sidebar-category-header">Main Menu</li>

            @php
                if(request()->is(Request::segment(1).'/dashboard*')) {
                    $activeDashboard = 'true';
                } else {
                    $activeDashboard = 'false';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('nutritionPanel.dashboard') }}" data-active="{{ $activeDashboard }}" aria-expanded="false" class="dropdown-toggle">
                    <div>
                        <i data-feather="grid"></i>
                        <span>{{ __('language.dashboard_menu') }}</span>
                    </div>
                </a>
            </li>

            <!-- CATEGORY: MEMBER MANAGEMENT -->
            <li class="sidebar-category-header">Member & Club</li>

            <!-- User Management -->
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
                <a href="#userManagement" data-bs-toggle="collapse" aria-expanded="{{ $showUserManagement }}" data-active="{{ $showUserManagement }}" class="dropdown-toggle collapsed">
                    <div>
                        <i data-feather="users"></i>
                        <span>User Management</span>
                    </div>
                    <div>
                        <i data-feather="chevron-right"></i>
                    </div>
                </a>
                <ul class="submenu list-unstyled collapse {{ $activeUserManagement }}" id="userManagement" data-bs-parent="#accordionExample">
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

            <!-- Offline System Management -->
            @php
                $showOfflineSystemManagement = 'false';
                $activeOfflineSystemManagement = '';
                $activeOfflineSystemList = '';
                $activeCounsellingList = '';

                if(request()->is(Request::segment(1).'/attendance-register*')){
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
                <a href="#offlineSystemManagement" data-bs-toggle="collapse" aria-expanded="{{ $showOfflineSystemManagement }}" data-active="{{ $showOfflineSystemManagement }}" class="dropdown-toggle collapsed">
                    <div>
                        <i data-feather="check-square"></i>
                        <span>Offline System</span>
                    </div>
                    <div>
                        <i data-feather="chevron-right"></i>
                    </div>
                </a>
                <ul class="submenu list-unstyled collapse {{ $activeOfflineSystemManagement }}" id="offlineSystemManagement" data-bs-parent="#accordionExample">
                    <li class="{{ $activeOfflineSystemList }}">
                        <a href="{{ route('nutritionPanel.attendance-register.index') }}">Attendance Register</a>
                    </li>
                    <li class="{{ $activeCounsellingList }}">
                        <a href="{{ route('nutritionPanel.counsellings.index') }}">Counsellings ({{ date('d-m-Y') }})</a>
                    </li>
                </ul>
            </li>

            <!-- CATEGORY: COMMUNITY & CONTENT -->
            <li class="sidebar-category-header">Community & Growth</li>

            <!-- Achievement Management -->
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
                <a href="#achievementManagement" data-bs-toggle="collapse" aria-expanded="{{ $showAchievementManagement }}" data-active="{{ $showAchievementManagement }}" class="dropdown-toggle collapsed">
                    <div>
                        <i data-feather="award"></i>
                        <span>Achievements & Hub</span>
                    </div>
                    <div>
                        <i data-feather="chevron-right"></i>
                    </div>
                </a>
                <ul class="submenu list-unstyled collapse {{ $activeAchievementManagement }}" id="achievementManagement" data-bs-parent="#accordionExample">
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
                        <a href="{{ route('nutritionPanel.tips.index') }}">Tips (Video Links)</a>
                    </li>
                </ul>
            </li>

            <!-- CATEGORY: MEALS & NUTRITION -->
            <li class="sidebar-category-header">Meals & Nutrition</li>

            <!-- Dish Type Management -->
            @php
                $showDishType = 'false';
                if(request()->is(Request::segment(1).'/dish-types*')){
                    $showDishType = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('nutritionPanel.dish-types.index') }}" data-active="{{ $showDishType }}" class="dropdown-toggle collapsed">
                    <div>
                        <i data-feather="coffee"></i>
                        <span>Dish Types</span>
                    </div>
                </a>
            </li>

            <!-- Custom Dish Management -->
            @php
                $showCustomDish = 'false';
                if(request()->is(Request::segment(1).'/custom-dishes*')){
                    $showCustomDish = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('nutritionPanel.custom-dishes.index') }}" data-active="{{ $showCustomDish }}" class="dropdown-toggle collapsed">
                    <div>
                        <i data-feather="book-open"></i>
                        <span>Custom Dishes</span>
                    </div>
                </a>
            </li>

            <!-- Calculate Calories Management -->
            @php
                $showCalculateCalories = 'false';
                if(request()->is(Request::segment(1).'/calculate-calories*')){
                    $showCalculateCalories = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('nutritionPanel.calculate-calories.index') }}" data-active="{{ $showCalculateCalories }}" class="dropdown-toggle collapsed">
                    <div>
                        <i data-feather="activity"></i>
                        <span>Calculate Calories</span>
                    </div>
                </a>
            </li>

            <!-- Body Parameters Management -->
            @php
                $showBmiCalculator = 'false';
                if(request()->is(Request::segment(1).'/bmi-calculator*')){
                    $showBmiCalculator = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('nutritionPanel.bmi-calculator.index') }}" data-active="{{ $showBmiCalculator }}" class="dropdown-toggle collapsed">
                    <div>
                        <i data-feather="heart"></i>
                        <span>Body Parameters (BMI)</span>
                    </div>
                </a>
            </li>

            <!-- CATEGORY: FINANCE & BILLING -->
            <li class="sidebar-category-header">Finance & Plans</li>

            <!-- Transactions -->
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
                        <i data-feather="dollar-sign"></i>
                        <span>Transactions</span>
                    </div>
                </a>
            </li>

            <!-- Orders -->
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
                        <i data-feather="shopping-bag"></i>
                        <span>Orders</span>
                    </div>
                </a>
            </li>

            <!-- Reviews -->
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
                        <i data-feather="star"></i>
                        <span>Reviews</span>
                    </div>
                </a>
            </li>

            <!-- Membership Plans -->
            @php
                $showMembershipPlan = 'false';
                if(request()->is(Request::segment(1).'/membership-plans*')){
                    $showMembershipPlan = 'true';
                }
            @endphp

            <li class="menu">
                <a href="{{ route('nutritionPanel.membership-plans.index') }}" data-active="{{ $showMembershipPlan }}" class="dropdown-toggle collapsed">
                    <div>
                        <i data-feather="credit-card"></i>
                        <span>Membership Plans</span>
                    </div>
                </a>
            </li>

            <!-- CATEGORY: SETTINGS -->
            <li class="sidebar-category-header">Preferences</li>

            <!-- Miscellaneous Management -->
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
                <a href="#miscellaneousManagement" data-bs-toggle="collapse" aria-expanded="{{ $showMiscellaneousManagement }}" data-active="{{ $showMiscellaneousManagement }}" class="dropdown-toggle collapsed">
                    <div>
                        <i data-feather="settings"></i>
                        <span>Settings & Info</span>
                    </div>
                    <div>
                        <i data-feather="chevron-right"></i>
                    </div>
                </a>
                <ul class="submenu list-unstyled collapse {{ $activeMiscellaneousManagement }}" id="miscellaneousManagement" data-bs-parent="#accordionExample">
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

        </ul>
    </nav>
</div>
<!--  END SIDEBAR  -->