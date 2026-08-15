@extends('nutrition-panel.layouts.main-layout')

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
            <div class="col-xl-3 col-lg-6 col-md-5 col-sm-12 layout-spacing">
                <div class="user-profile layout-spacing">
                    <div class="widget-content widget-content-area">
                        <div class="d-flex justify-content-between p-2">
                            <h3 class="pb-3">Profile</h3>
                        </div>
                        <div class="text-center user-info mt-1 p-2">
                            @php
                                $profileImage = asset('admin-assets/images/user.png');
                                if(!empty($user->profile_image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$user->profile_image))
                                {
                                    $profileImage = get_image_url(config('constants.users.image_path'), $user->profile_image);
                                }
                            @endphp
                            <img class="rounded-3" src="{{ $profileImage }}" alt="{{$user->profile_image}}" style="width: 100% !important;">
                            <p class="">{{ ucwords($user->name) }}</p>
                        </div>
                        <div class="user-info-list">
                            <div class="ms-3">
                                <ul class="contacts-block list-unstyled">
                                    <li class="contacts-block__item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                        {{$user->email}}
                                    </li>
                                    <li class="contacts-block__item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                        {{$user->mobile_number}}
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
            <div class="col-xl-9 col-lg-6 col-md-7 col-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt-2">
                        <div class="row mb-3">
                            <div class="col-12">
                                <h4 class="mb-0">User Information</h4>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Name</div>
                                    <div class="detail-tile-value">{{ !empty($user->name) ? $user->name : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Email</div>
                                    <div class="detail-tile-value">{{ !empty($user->email) ? $user->email : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Mobile Number</div>
                                    <div class="detail-tile-value">{{ !empty($user->mobile_number) ? $user->mobile_number : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Coach Name</div>
                                    <div class="detail-tile-value">{{ !empty($user->coach_name) ? $user->coach_name : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Meal Type</div>
                                    <div class="detail-tile-value">{{ !empty($user->meal_type->name) ? $user->meal_type->name : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">User Type</div>
                                    <div class="detail-tile-value">
                                        <span class="badge badge-purple">{{ !empty($user->user_type) ? $user->user_type : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">User State</div>
                                    <div class="detail-tile-value">{{ !empty($user->user_state) ? $user->user_state : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Age</div>
                                    <div class="detail-tile-value">{{ !empty($user->age) ? $user->age . ' yrs' : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Current Weight</div>
                                    <div class="detail-tile-value text-primary">{{ !empty($user->current_weight) ? $user->current_weight . ' kg' : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Height</div>
                                    <div class="detail-tile-value">{{ !empty($user->height) ? $user->height . ' cm' : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Weight Goal</div>
                                    <div class="detail-tile-value text-success">{{ !empty($user->weight_goal) ? $user->weight_goal . ' kg' : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Gender</div>
                                    <div class="detail-tile-value">{{ !empty($user->gender) ? $user->gender : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Remaining Days</div>
                                    <div class="detail-tile-value text-info font-weight-bold">{{ !empty($user->days) ? $user->days . ' Days' : '0 Days' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Due Amount</div>
                                    <div class="detail-tile-value text-danger font-weight-bold">{{ !empty($user->due_amount) ? '₹' . number_format($user->due_amount, 2) : '₹0.00' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Joining Date</div>
                                    <div class="detail-tile-value">
                                        {{ !empty($user->created_at) ? \Carbon\Carbon::parse($user->created_at)->timezone(session()->get('timezone'))->format('d M, Y h:i A') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Joining Weight</div>
                                    <div class="detail-tile-value">{{ !empty($user->starting_weight) ? $user->starting_weight . ' kg' : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Status</div>
                                    <div class="detail-tile-value">
                                        @if($user->status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Latest Weight</div>
                                    <div class="detail-tile-value">{{ !empty($lastRecord->weight) ? $lastRecord->weight . ' kg' : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Overall Max Weight</div>
                                    <div class="detail-tile-value text-danger">{{ !empty($maxWeight->weight) ? $maxWeight->weight . ' kg' : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="detail-tile">
                                    <div class="detail-tile-label">Overall Min Weight</div>
                                    <div class="detail-tile-value text-success">{{ !empty($minWeight->weight) ? $minWeight->weight . ' kg' : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons Row -->
                        <div class="row mt-4 pt-2 border-top">
                            <div class="col-12 d-flex flex-wrap gap-2">
                                <a class="btn btn-primary" href="{{ route('nutritionPanel.users.viewWeights', ['id' => ev($user->id)]) }}">
                                    <i class="fa fa-line-chart"></i> View Weight
                                </a>
                                <a class="btn btn-primary" href="{{ route('nutritionPanel.users.viewAttendance', ['id' => ev($user->id)]) }}">
                                    <i class="fa fa-calendar-check-o"></i> View Attendance
                                </a>
                                <a class="btn btn-primary" href="{{ route('nutritionPanel.manual-attendances.manual-attendance', ['id' => ev($user->id)]) }}">
                                    <i class="fa fa-edit"></i> Manual Attendance
                                </a>
                                <a class="btn btn-primary" href="{{ route('nutritionPanel.track-shake.index', ['id' => ev($user->id)]) }}">
                                    <i class="fa fa-coffee"></i> Track Shake
                                </a>
                                <a class="btn btn-primary" href="{{ route('nutritionPanel.orders.index', ['id' => ev($user->id)]) }}">
                                    <i class="fa fa-shopping-bag"></i> Purchase Products
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
