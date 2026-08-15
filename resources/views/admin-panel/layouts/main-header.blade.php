@php
$company = get_company_profile();
$headerLogo = (isset($company[0]) && isset($company[0]['header_logo_image'])) ? $company[0]['header_logo_image'] : '';
@endphp

<!--  BEGIN NAVBAR  -->
<div class="header-container fixed-top">
    <header class="header navbar navbar-expand-sm py-0">

        @php
        $authUser = auth()->user();
        @endphp
        <ul class="navbar-item flex-row  text-center">
            <li class="nav-item">
                <a href="{{ route('adminPanel.dashboard') }}" class="nav-link">
                    <img src="{{get_image_url(config('constants.company_profile.image_path'), $headerLogo)}}" class="img-fluid" alt="" style="height: 44px;padding-left: 18px;" />
                </a>
            </li>
            <li class="nav-item theme-text">
                <a href="{{ route('adminPanel.dashboard') }}" class="nav-link">
                    {{ config('app.name') }}
                </a>
            </li>
        </ul>
        <ul class="navbar-item flex-row ms-1">
            <a href="javascript:void(0);" class="sidebarCollapse" data-bs-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>
        </ul>

        <ul class="navbar-item flex-row ms-md-auto">
            <li class="nav-item user-name">
                {{$authUser->name}}
            </li>

            <li class="nav-item dropdown user-profile-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user-icon" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    {{-- {{ Html::image(asset('admin-assets/images/90x90.jpg'), 'avatar', array('class' => '')) }} --}}
                    <i data-feather="user" class="feather-24"></i>
                </a>
                <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                    <div class="">
                        <div class="dropdown-item">
                            <a class="" href="{{ route('adminPanel.profile') }}"> <i data-feather="user"></i> My Profile</a>
                        </div>
                        <!-- <div class="dropdown-item">
                            <a class="" href="{{ route('adminPanel.company-profile.index') }}"> <i data-feather="user"></i> Company Profile</a>
                        </div> -->
                        <div class="dropdown-item">
                            <a class="" href="{{ route('adminPanel.logout') }}"> <i data-feather="log-out"></i> Sign Out</a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </header>
</div>
<!--  END NAVBAR  -->