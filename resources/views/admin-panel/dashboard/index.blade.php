@extends('admin-panel.layouts.main-layout')

@section('page-title', ' '.__('language.dashboard_page_title').' | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/plugins/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin-assets/css/components/tabs-accordian/custom-tabs.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin-assets/js/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />

<style>
    @media screen and (max-width:1360px){
        .widget-card-four .w-info h6{
            font-size: 18px !important;
        }
    }
    @media screen and (max-width:1024px){
        .calender-text{
            font-size: 0.7rem !important;
        }
    }

    #reportrange{
        color: #3b3f5c !important;
    }

    #reportrange i.fa-caret-down{
        margin-top: 3px;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">

    <div class="layout-top-spacing">
        <div class="row mb-2">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-md-3">
                        <h5 class="page-title py-20">Welcome! {{Auth::user()->name}}</h5>
                    </div>
                    <!-- <div class="col-md-9 text-right">
                        <button type="button" class="btn btn-dark rounded-circle filter-button" title="Filter">
                            <i data-feather="filter" class="feather-16"></i>
                        </button>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- Filters -->
        @php
            $filterClass = 'hide';

            if(Request::get('is_filter')){
                $filterClass = '';
            }
        @endphp
        <div class="row custom-datatable-filters mb-4 {{$filterClass}}">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 _layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid">
                        <div class="custom-datatable-filter _hide">
                            {!! Form::open(['class' => 'dashboard-filter-form custom-datatable-filter-form', 'method' => 'get', 'url' => route('adminPanel.dashboard'), 'enctype' => 'multipart/form-data' ]) !!}
                            
                            {!! Form::hidden('is_filter', true, ['id' => 'is_filter'] ) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="text-primary"> {{ __('language.filters') }} </h6>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-12 col-xl-4 col-md-12 col-12">
                                    <div id="reportrange" class="mt-1" style="background: #fff; cursor: pointer; padding: 8px 5px; border: 1px solid #ccc; width: 100%; border-radius: 5px;">
                                       <div class=""> <i class="fa fa-calendar d-inline-block"></i>&nbsp;
                                        <span class="calender-text d-inline-block"></span> <i class="fa fa-caret-down pull-right d-inline-block pr-2"></i>
                                    </div>
                                    </div>
                                </div>
                                
                                {!! Form::hidden('start_date', $filterStartDate ?? null, ['id' => 'start_date']) !!}
                                {!! Form::hidden('end_date', $filterEndDate ?? null, ['id' => 'end_date']) !!}
                                {!! Form::hidden('date_filter_type', $filterDateType ?? '1', ['id' => 'date_filter_type']) !!}
                                
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-12">
                                    {{ Form::button( __('language.filter_apply'), ['class' => 'btn btn-primary apply-filter ml-1 mt-1', 'type' => 'submit', 'title' => __('language.filter_apply'), 'name' => 'filter'] )}}
                                    <a href="{{route('adminPanel.dashboard')}}" class="btn btn-dark ml-1 mt-1 clear-filter">Clear</a>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Filters -->

        <div class="row mt-3 pl-3 align-items-stretch">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 p-0 layout-spacing text-center">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100">
                                    <h4 class="text-dark font-weight-bold s-counter1">{{ $totalFranchise ?? 0 }}</h4>
                                    <h6 class="value">Total Franchises</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 layout-spacing text-center">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100">
                                    <h4 class="text-dark font-weight-bold s-counter1">{{ $totalAmount ?? 0 }}</h4>
                                    <h6 class="value">Total Amount</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 pl-0 pr-0 layout-spacing text-center">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100">
                                    <h4 class="text-dark font-weight-bold s-counter1">{{ $receivedAmount ?? 0 }}</h4>
                                    <h6 class="value">Received Amount</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 layout-spacing text-center">
                <a href="javascript:;">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-content">
                                <div class="w-info w-100">
                                    <h4 class="text-dark font-weight-bold s-counter1">{{ $pendingAmount ?? 0 }}</h4>
                                    <h6 class="value">Pending Amount</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-12 col-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row text-center justify-center">
                            <h4 class="font-weight-bold" style="color : #3246d3;">Pending Payments </h4>
                        </div>
                    </div>

                    <div class="data-table-container mb-0 pl-3 pr-3">
                        <div id="dataTable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="dataTable" class="table table-hover dataTable" role="grid" aria-describedby="dataTable_info">
                                        <thead>
                                            <tr>
                                                <th>Franchise Name</th>
                                                <th>Franchise Plan</th>
                                                <th>Pending Amount</th>
                                                <!-- <th>Payment Status</th> -->
                                                <!-- <th>Start Date</th> -->
                                                <th>End Date</th>
                                                <!-- <th>Remark</th> -->
                                                <!-- <th>Action</th> -->
                                            </tr>
                                        </thead>

                                        @if($franchiseMembershipPlans['0'])
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2" class="text-end" rowspan="1">Total:</th>
                                                    <th id="total_amount_footer" rowspan="1" colspan="1">{{ $pendingAmount }}</th>
                                                    <th colspan="1" rowspan="1"></th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                @foreach($franchiseMembershipPlans as $franchiseMembershipPlan)
                                                    <tr>
                                                        <td>{{ $franchiseMembershipPlan['user_name'] }}</td>
                                                        <td>{{ $franchiseMembershipPlan['membership_plan_name'] }}</td>
                                                        <td>{{ $franchiseMembershipPlan['total_amount'] }}</td>
                                                        <!-- <td>
                                                            <label class="badge badge-danger">Pending</label>
                                                        </td>
                                                        <td>{{ date("d-m-Y", strtotime($franchiseMembershipPlan->start_date)) }}</td> -->
                                                        <td>{{ date("d-m-Y", strtotime($franchiseMembershipPlan->end_date)) }}</td>
                                                        <!-- <td>{{ $franchiseMembershipPlan->remark ?? 'N/A' }}</td>
                                                        <td>
                                                            <a href="{{ route('adminPanel.franchise-membership-plans.edit', ['id' => ev($franchiseMembershipPlan->id)]) }}" class="" title="Edit">
                                                                <div class="badge badge-primary">
                                                                    <i class="fa fa-pencil"></i> Edit
                                                                </div>
                                                            </a>
                                                        </td> -->
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        @else
                                            <tbody>
                                                <tr>
                                                    <th colspan="4" class="text-center" rowspan="1">No Record Found !</th>
                                                </tr>
                                            </tbody>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-12 col-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row text-center justify-center">
                            <h4 class="font-weight-bold" style="color : #3246d3;">Upcoming Subscriptions </h4>
                        </div>
                    </div>

                    <div class="data-table-container mb-4 pl-3 pr-3 mt-2">
                        <div id="dataTable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="dataTable" class="table table-hover dataTable" role="grid" aria-describedby="dataTable_info">
                                        <thead>
                                            <tr>
                                                <th>Franchise Name</th>
                                                <!-- <th>Email</th>
                                                <th>Mobile Number</th>
                                                <th>Pending Amount</th>
                                                <th>Pending Days</th> -->
                                                <th>Membership Plan</th>
                                                <th>Pending Days</th>
                                                <!-- <th>Status</th>
                                                <th>Action</th> -->
                                            </tr>
                                        </thead>

                                        @if($franchises['0'])
                                            <tbody>
                                                @foreach($franchises as $franchise)

                                                    @php
                                                        if (!empty($franchise['end_date'])) {
                                                            $endDate = \Carbon\Carbon::parse($franchise['end_date'])->startOfDay();
                                                            $today   = \Carbon\Carbon::today();

                                                            // 🔹 Difference without negative value
                                                            $pending_days = $today->diffInDays($endDate, false);
                                                        }

                                                        $last_membership  = $lastMembershipByFranchise->get($franchise->id);
                                                        $membership_plan_name = isset($last_membership) ? ($membershipPlanNames->get($last_membership->membership_id) ?? null) : null;
                                                        $pending_amount     = $pendingAmountByFranchise->get($franchise->id, 0);
                                                    @endphp

                                                    @if($pending_days <= 10)
                                                        <tr>
                                                            <td>{{ $franchise['name'] }}</td>
                                                            <td>{{ $membership_plan_name ?? 'N/A' }}</td>
                                                            <!-- <td>{{ $franchise['email'] }}</td>
                                                            <td>{{ $franchise['mobile_number'] }}</td>

                                                            <td>
                                                                @if($pending_amount > 0)
                                                                    <span class="text-danger">{{ $pending_amount }}</span>
                                                                @else
                                                                    <span class="text-success">{{ $pending_amount }}</span>
                                                                @endif
                                                            </td> -->

                                                            <td>
                                                                @if($pending_days > 0)
                                                                    <span class="text-success">{{ $pending_days }}</span>
                                                                @else
                                                                    <span class="text-danger">{{ $pending_days }}</span>
                                                                @endif
                                                            </td>

                                                            <!-- <td>
                                                                <a href="{{ route('adminPanel.franchise-membership-plans.index', ['id' => ev($franchise->id)]) }}" class="" title="View Memberships">
                                                                    <div class="badge badge-primary">
                                                                        View Memberships {{ $total_membership }}
                                                                    </div>
                                                                </a>
                                                            </td>

                                                            <td>
                                                                @if($franchise['status'] == 0)
                                                                    <label class="badge badge-warning">Inactive</label>
                                                                @else
                                                                    <label class="badge badge-success">Active</label>
                                                                @endif
                                                            </td>

                                                            <td>
                                                                <a href="{{ route('adminPanel.franchises.edit', ['id' => ev($franchise->id)]) }}" class="" title="Edit">
                                                                    <div class="badge badge-primary">
                                                                        <i class="fa fa-pencil"></i> Edit
                                                                    </div>
                                                                </a>
                                                            </td> -->
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        @else
                                            <tbody>
                                                <tr>
                                                    <th colspan="8" class="text-center" rowspan="1">No Record Found !</th>
                                                </tr>
                                            </tbody>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-0 pl-3 pr-3 align-items-stretch">
            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 layout-spacing pl-0">
                <div class="widget-content widget-content-area br-6">
                    <div class="widget-heading pt-2 d-flex align-items-center justify-center">
                        <h5 class="m-0 font-weight-bold" style="color : #3246d3;">Top 10 Active Coach this Month</h5>
                    </div>
                    <div class="table-responsive mb-4 mt-4" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($top10ActiveThisMonths[0])
                                    @foreach($top10ActiveThisMonths as $key => $top10ActiveThisMonth)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ ucfirst($top10ActiveThisMonth->name) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="font-weight-bold text-center">No Record Found !!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 layout-spacing p-0">
                <div class="widget-content widget-content-area br-6">
                    <div class="widget-heading pt-2 d-flex align-items-center justify-center">
                        <h5 class="m-0 font-weight-bold" style="color : #3246d3;">Least 10 InActive Coach this Month</h5>
                    </div>
                    <div class="table-responsive mb-4 mt-4" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($top10InActiveThisMonths[0])
                                    @foreach($top10InActiveThisMonths as $key => $top10InActiveThisMonth)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ ucfirst($top10InActiveThisMonth->name) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="font-weight-bold text-center">No Record Found !!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-0 pl-3 pr-3 align-items-stretch">
            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 layout-spacing pl-0">
                <div class="widget-content widget-content-area br-6">
                    <div class="widget-heading pt-2 d-flex align-items-center justify-center">
                        <h5 class="m-0 font-weight-bold" style="color : #3246d3;">Total New Franchise this Month</h5>
                    </div>
                    <div class="table-responsive mb-4 mt-4" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($franchiseThisMonths[0])
                                    @foreach($franchiseThisMonths as $key => $franchiseThisMonth)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ ucfirst($franchiseThisMonth->name) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="font-weight-bold text-center">No Record Found !!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 layout-spacing p-0">
                <div class="widget-content widget-content-area br-6">
                    <div class="widget-heading pt-2 d-flex align-items-center justify-center">
                        <h5 class="m-0 font-weight-bold" style="color : #3246d3;">Platform Usage this Month</h5>
                    </div>
                    <div class="table-responsive mb-4 mt-4" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Coach Name</th>
                                    <th>New User Adds</th>
                                    <th>Total Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($platformUsageThisMonths[0])
                                    @foreach($platformUsageThisMonths as $key => $platformUsageThisMonth)

                                        @php
                                            $newUsers = $newUsersByFranchise->get($platformUsageThisMonth->id, 0);

                                            $totalUsers = $totalUsersByFranchise->get($platformUsageThisMonth->id, 0);
                                        @endphp

                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ ucfirst($platformUsageThisMonth->name) }}</td>
                                            <td>{{ $newUsers }}</td>
                                            <td>{{ $totalUsers }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="font-weight-bold text-center">No Record Found !!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-0 pl-3 pr-3 align-items-stretch">
            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 layout-spacing pl-0">
                <div class="widget-content widget-content-area br-6">
                    <div class="widget-heading pt-2 d-flex align-items-center justify-center">
                        <h5 class="m-0 font-weight-bold" style="color : #3246d3;">Coach LifeCycle</h5>
                    </div>
                    <div class="table-responsive mb-4 mt-4" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Coach Name</th>
                                    <th>Total Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($franchiseLifeCycles[0])
                                    @foreach($franchiseLifeCycles as $key => $franchiseLifeCycle)

                                        @php
                                            $totalUsers = $totalUsersByFranchise->get($franchiseLifeCycle->id, 0);
                                        @endphp

                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ ucfirst($franchiseLifeCycle->name) }}</td>
                                            <td>{{ $totalUsers }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="font-weight-bold text-center">No Record Found !!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
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
<script src="{{ asset('admin-assets/js/dashboard/view.js') }}"></script>
<script src="{{ asset('admin-assets/plugins/apex/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/daterangepicker/daterangepicker.js') }}"></script>
@endpush