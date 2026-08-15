@extends('admin-panel.layouts.main-layout')

@section('page-title', ' '.__('language.detail').' | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/components/tabs-accordian/custom-tabs.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin-assets/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />
<style>
    @media screen and (min-width:1100px)
    {
        .media img
        {
            width: 120px !important;
            height: 120px !important;
        }
    }
    
    .points-card
    {
        padding: 12px 23px;
        background: #fff;
        text-align: left;
        border-radius: 6px;
        /* width: 10%; */
        -webkit-box-shadow: 0px 8px 20px rgb(0 0 0 / 10%);
        box-shadow: 0px 2px 10px rgb(0 0 0 / 10%);
        margin-bottom: 5px;
    }

    .points-card-title
    {
        color: #3b3f5c;
        font-weight: 600;
    }

    .points-card-point
    {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 0;
        text-align: center;
        background: #ebedf2;
        border-radius: 6px;
        margin-top: 10px;
        padding: 3px 0;
        color: #515365;
    }
    .user-profile .widget-content-area .user-info-list ul.contacts-block{
        max-width: 300px !important;
    }
</style>
@endpush

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-4 col-lg-6 col-md-5 col-sm-12 layout-spacing">
                <div class="user-profile layout-spacing">
                    <div class="widget-content widget-content-area">
                        <div class="d-flex justify-content-between">
                            <h3 class="pb-3">Profile</h3>
                        </div>
                        <div class="text-center user-info">
                            @php
                                $profileImage = asset('admin-assets/images/user.png');
                                if(!empty($user->profile_image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$user->profile_image))
                                {
                                    $profileImage = get_image_url(config('constants.users.image_path'), $user->profile_image);
                                }
                            @endphp
                            <img class="me-3 rounded-circle" src="{{ $profileImage }}" alt="{{$user->profile_image}}" width="80" height="80" style="width: 80px !important; height: 80px !important">
                            <p class="">{{ ucwords($user->name) }}</p>
                        </div>
                        <div class="user-info-list">
                            <div class="">
                                <ul class="contacts-block list-unstyled">
                                    <li class="contacts-block__item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                        {{$user->email}} 
                                        @if(!empty($user->email))
                                            @if(!empty($user->email_verified_at))
                                                <span style="color:green;"> &#10004; </span>
                                            @else 
                                                <span style="color:red;"> &#10006; </span>
                                            @endif
                                        @endif
                                    </li>
                                    <li class="contacts-block__item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>

                                        @if(!empty($user->mobile_number))
                                            @if(!empty($user->country_code))
                                                {{$user->country_code}}-{{$user->mobile_number}}<span style="color:green;"> &#10004; </span>
                                            @else
                                                {{$user->mobile_number}}<span style="color:red;"> &#10006; </span>
                                            @endif
                                        @endif

                                    </li>
                                    <li class="contacts-block__item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                        @if(!empty($user->created_at))
                                            {{\Carbon\Carbon::parse($user->created_at)->timezone(session()->get('timezone'))->format('d M, Y h:i A')}}
                                        @else
                                            N/A
                                        @endif
                                    </li>
                                </ul>
                            </div>                                    
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-6 col-md-7 col-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row">
                            <div class="col-lg-12 col-12 layout-spacing">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Total Points</label>
                                            <div class="text-dark">
                                                @if(!empty($user->total_points))
                                                    {{ $user->total_points}} Points
                                                @else
                                                    0 Points
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">SAR Balance</label>
                                            <div class="text-dark">
                                                @if(!empty($user->sar_balance))
                                                    {{ $user->sar_balance }} Balance
                                                @else
                                                    0 Balance
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Current Country</label>
                                            <div class="text-dark">
                                                @if(!empty($user->current_country))
                                                    {{ $user->current_country }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Address</label>
                                            <div class="text-dark">
                                                @if(!empty($user->address))
                                                    {{ $user->address }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">House Number</label>
                                            <div class="text-dark">
                                                @if(!empty($user->house_number))
                                                    {{ $user->house_number }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Address Line 1</label>
                                            <div class="text-dark">
                                                @if(!empty($user->address_line_1))
                                                    {{ $user->address_line_1 }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Address Line 2</label>
                                            <div class="text-dark">
                                                @if(!empty($user->address_line_2))
                                                    {{ $user->address_line_2 }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Landmark</label>
                                            <div class="text-dark">
                                                @if(!empty($user->landmark))
                                                    {{ $user->landmark }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Pincode</label>
                                            <div class="text-dark">
                                                @if(!empty($user->pincode))
                                                    {{ $user->pincode }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Country</label>
                                            <div class="text-dark">
                                                @if(!empty($user->country->name))
                                                    {{ $user->country->name }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Last Login</label>
                                            <div class="text-dark">
                                                @if(!empty($user->last_login_at))
                                                    {{\Carbon\Carbon::parse($user->last_login_at)->timezone(session()->get('timezone'))->format('d M, Y h:i A')}}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Platform</label>
                                            <div class="">
                                                @if($user->platform == config('constants.platforms.ANDROID.value'))
                                                    <label class="badge badge-primary text-white">{{ config('constants.platforms.ANDROID.value') }}</label>
                                                @elseif($value->platform == config('constants.platforms.IOS.value'))
                                                    <label class="badge badge-warning text-white">{{ config('constants.platforms.IOS.value') }}</label>
                                                @elseif($value->platform == config('constants.platforms.WEB.value'))
                                                    <label class="badge badge-info text-white">{{ config('constants.platforms.WEB.value') }}</label>
                                                @elseif($value->platform == config('constants.platforms.ADMIN.value'))
                                                    <label class="badge badge-dark text-white">{{ config('constants.platforms.ADMIN.value') }}</label>
                                                @else
                                                    <label class="badge badge-dark text-white">N/A</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Device OS</label>
                                            <div class="">
                                                @if($user->device_os != '')
                                                    <label class="badge badge-primary text-white">{{ $user->device_os }}</label>
                                                @else
                                                    <label class="badge badge-dark text-white">N/A</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Device OS Version</label>
                                            <div class="">
                                                @if($user->device_os_version != '')
                                                    <label class="badge badge-warning text-white">{{ $user->device_os_version }}</label>
                                                @else
                                                    <label class="badge badge-dark text-white">N/A</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Device Manufacturer</label>
                                            <div class="">
                                                @if($user->device_manufacturer != '')
                                                    <label class="badge badge-info text-white">{{ $user->device_manufacturer }}</label>
                                                @else
                                                    <label class="badge badge-dark text-white">N/A</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Device Model</label>
                                            <div class="">
                                                @if($user->device_model != '')
                                                    <label class="badge badge-dark text-white">{{ $user->device_model }}</label>
                                                @else
                                                    <label class="badge badge-dark text-white">N/A</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">App Version</label>
                                            <div class="text-dark">
                                                @if($user->app_version != '')
                                                    <label class="badge badge-primary text-white">{{ $user->app_version }}</label>
                                                @else
                                                    <label class="badge badge-dark text-white">N/A</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="name">Status</label>
                                            <div class="">
                                                @if($user->status == 1)
                                                    <label class="badge badge-primary text-white">Active</label>
                                                @else
                                                    <label class="badge badge-warning text-white">Inactive</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(!empty($user['user_documents']))
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 layout-spacing">
                <div class="user-profile layout-spacing">
                    <div class="widget-content widget-content-area">
                        <div class="d-flex justify-content-between">
                            <h3 class="pb-3">User Documents</h3>
                        </div>
                        <div class="row">
                            @foreach($user->user_documents as $user_documents)
                                <div class="col-md-4 col-sm-6 col-xs-12 data-row mb-2 text-center">
                                    <div class="image-area mb-2"> 
                                        {{ $user_documents->document_name }}
                                    </div>
                                    <a class="btn btn-primary" href="{{ get_image_url(config('constants.users.image_path'), $user_documents->document_name) }}" download=""><i class="fa fa-download"></i> Download</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>


@endsection

@push('scripts')

<script src="{{ asset('admin-assets/js/plugins/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
@endpush
