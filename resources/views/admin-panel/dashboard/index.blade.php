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
    /* Modern Dashboard Hero & Stat Cards */
    .dashboard-hero-banner {
        background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
        border: 1px solid #e2e8f0;
        border-left: 4px solid var(--npc-primary, #3246d3);
        border-radius: 12px;
        padding: 18px 24px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
    }

    .dashboard-hero-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 3px;
        letter-spacing: -0.02em;
    }

    .dashboard-hero-subtitle {
        color: #64748b;
        font-size: 13.5px;
        font-weight: 500;
        margin-bottom: 0;
    }

    /* 4 Quick Stat Metric Cards Grid */
    .stats-grid-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (max-width: 1200px) {
        .stats-grid-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .stats-grid-container {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    }

    .metric-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        transition: all 0.25s ease-in-out;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px -4px rgba(50, 70, 211, 0.12);
        border-color: #3246d3;
    }

    .metric-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .metric-icon-circle {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #ffffff;
    }

    .metric-icon-blue { background: linear-gradient(135deg, #3246d3, #4361ee); box-shadow: 0 4px 10px rgba(50, 70, 211, 0.3); }
    .metric-icon-purple { background: linear-gradient(135deg, #7c3aed, #9333ea); box-shadow: 0 4px 10px rgba(124, 58, 237, 0.3); }
    .metric-icon-green { background: linear-gradient(135deg, #059669, #10b981); box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3); }
    .metric-icon-rose { background: linear-gradient(135deg, #dc2626, #ef4444); box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3); }

    .metric-value {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 2px;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .metric-label {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .admin-card-header {
        padding: 16px 20px 12px;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .admin-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .admin-card-title i {
        color: #3246d3;
        width: 18px;
        height: 18px;
    }

    #reportrange {
        color: #3b3f5c !important;
    }

    #reportrange i.fa-caret-down {
        margin-top: 3px;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">

    <div class="layout-top-spacing">
        
        <!-- Welcome Hero Banner -->
        <div class="dashboard-hero-banner">
            <div>
                <div class="dashboard-hero-title">
                    Welcome back, <span class="text-primary">{{ Auth::user()->name }}</span>! 👋
                </div>
                <p class="dashboard-hero-subtitle">
                    Manage your franchises, membership subscriptions, products, and overall platform metrics.
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge badge-purple" style="font-size: 12px; padding: 6px 14px;">
                    <i data-feather="shield" style="width: 14px; height: 14px;" class="me-1"></i> Super Admin Portal
                </span>
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
                                        <span class="calender-text d-inline-block"></span> <i class="fa fa-caret-down pull-right d-inline-block pe-2"></i>
                                    </div>
                                    </div>
                                </div>
                                
                                {!! Form::hidden('start_date', $filterStartDate ?? null, ['id' => 'start_date']) !!}
                                {!! Form::hidden('end_date', $filterEndDate ?? null, ['id' => 'end_date']) !!}
                                {!! Form::hidden('date_filter_type', $filterDateType ?? '1', ['id' => 'date_filter_type']) !!}
                                
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-12">
                                    {{ Form::button( __('language.filter_apply'), ['class' => 'btn btn-primary apply-filter ms-1 mt-1', 'type' => 'submit', 'title' => __('language.filter_apply'), 'name' => 'filter'] )}}
                                    <a href="{{route('adminPanel.dashboard')}}" class="btn btn-dark ms-1 mt-1 clear-filter">Clear</a>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Filters -->

        <!-- 4 Metric Cards Grid -->
        <div class="stats-grid-container">
            <div class="metric-card">
                <div class="metric-card-top">
                    <span class="metric-label">Total Franchises</span>
                    <div class="metric-icon-circle metric-icon-blue">
                        <i data-feather="home" style="width: 20px; height: 20px;"></i>
                    </div>
                </div>
                <div class="metric-value">{{ $totalFranchise ?? 0 }}</div>
            </div>

            <div class="metric-card">
                <div class="metric-card-top">
                    <span class="metric-label">Total Amount</span>
                    <div class="metric-icon-circle metric-icon-purple">
                        <i data-feather="dollar-sign" style="width: 20px; height: 20px;"></i>
                    </div>
                </div>
                <div class="metric-value">{{ $totalAmount ?? 0 }}</div>
            </div>

            <div class="metric-card">
                <div class="metric-card-top">
                    <span class="metric-label">Received Amount</span>
                    <div class="metric-icon-circle metric-icon-green">
                        <i data-feather="check-circle" style="width: 20px; height: 20px;"></i>
                    </div>
                </div>
                <div class="metric-value">{{ $receivedAmount ?? 0 }}</div>
            </div>

            <div class="metric-card">
                <div class="metric-card-top">
                    <span class="metric-label">Pending Amount</span>
                    <div class="metric-icon-circle metric-icon-rose">
                        <i data-feather="clock" style="width: 20px; height: 20px;"></i>
                    </div>
                </div>
                <div class="metric-value">{{ $pendingAmount ?? 0 }}</div>
            </div>
        </div>
        
        <!-- Row: Pending Payments & Upcoming Subscriptions -->
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-12 col-12 layout-spacing">
                <div class="widget-content widget-content-area br-6 p-0">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title">
                            <i data-feather="alert-circle"></i> Pending Payments
                        </h5>
                    </div>

                    <div class="data-table-container p-3">
                        <div id="dataTable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap5 p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="dataTable" class="table table-hover dataTable" role="grid" aria-describedby="dataTable_info">
                                        <thead>
                                            <tr>
                                                <th>Franchise Name</th>
                                                <th>Franchise Plan</th>
                                                <th>Pending Amount</th>
                                                <th>End Date</th>
                                            </tr>
                                        </thead>

                                        @if(count($franchiseMembershipPlans) > 0)
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
                                                        <td><strong>{{ $franchiseMembershipPlan['user_name'] }}</strong></td>
                                                        <td><span class="badge badge-primary">{{ $franchiseMembershipPlan['membership_plan_name'] }}</span></td>
                                                        <td><span class="text-danger fw-bold">{{ $franchiseMembershipPlan['total_amount'] }}</span></td>
                                                        <td>{{ date("d-m-Y", strtotime($franchiseMembershipPlan->end_date)) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        @else
                                            <tbody>
                                                <tr>
                                                    <td colspan="4" class="text-center py-4 text-muted">No Record Found !</td>
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
                <div class="widget-content widget-content-area br-6 p-0">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title">
                            <i data-feather="calendar"></i> Upcoming Subscriptions
                        </h5>
                    </div>

                    <div class="data-table-container p-3">
                        <div id="dataTable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap5 p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="dataTable" class="table table-hover dataTable" role="grid" aria-describedby="dataTable_info">
                                        <thead>
                                            <tr>
                                                <th>Franchise Name</th>
                                                <th>Membership Plan</th>
                                                <th>Pending Days</th>
                                            </tr>
                                        </thead>

                                        @if(count($franchises) > 0)
                                            <tbody>
                                                @foreach($franchises as $franchise)

                                                    @php
                                                        $pending_days = null;
                                                        if (!empty($franchise['end_date'])) {
                                                            $endDate = \Carbon\Carbon::parse($franchise['end_date'])->startOfDay();
                                                            $today   = \Carbon\Carbon::today();
                                                            $pending_days = $today->diffInDays($endDate, false);
                                                        }

                                                        $last_membership  = $lastMembershipByFranchise->get($franchise->id);
                                                        $membership_plan_name = isset($last_membership) ? ($membershipPlanNames->get($last_membership->membership_id) ?? null) : null;
                                                        $pending_amount     = $pendingAmountByFranchise->get($franchise->id, 0);
                                                    @endphp

                                                    @if(isset($pending_days) && $pending_days <= 10)
                                                        <tr>
                                                            <td><strong>{{ $franchise['name'] }}</strong></td>
                                                            <td><span class="badge badge-info">{{ $membership_plan_name ?? 'N/A' }}</span></td>
                                                            <td>
                                                                @if($pending_days > 0)
                                                                    <span class="badge badge-success">{{ $pending_days }} Days left</span>
                                                                @else
                                                                    <span class="badge badge-danger">{{ abs($pending_days) }} Days Overdue</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        @else
                                            <tbody>
                                                <tr>
                                                    <td colspan="3" class="text-center py-4 text-muted">No Record Found !</td>
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

        <!-- Row: Top 10 & Least 10 Coach this Month -->
        <div class="row">
            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6 p-0">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title">
                            <i data-feather="trending-up"></i> Top 10 Active Coach this Month
                        </h5>
                    </div>
                    <div class="table-responsive p-3" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">S.No</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($top10ActiveThisMonths) > 0)
                                    @foreach($top10ActiveThisMonths as $key => $top10ActiveThisMonth)
                                        <tr>
                                            <td><span class="badge badge-purple">{{ $key+1 }}</span></td>
                                            <td><strong>{{ ucfirst($top10ActiveThisMonth->name) }}</strong></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted">No Record Found !!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6 p-0">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title">
                            <i data-feather="trending-down"></i> Least 10 InActive Coach this Month
                        </h5>
                    </div>
                    <div class="table-responsive p-3" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">S.No</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($top10InActiveThisMonths) > 0)
                                    @foreach($top10InActiveThisMonths as $key => $top10InActiveThisMonth)
                                        <tr>
                                            <td><span class="badge badge-warning">{{ $key+1 }}</span></td>
                                            <td><strong>{{ ucfirst($top10InActiveThisMonth->name) }}</strong></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted">No Record Found !!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row: New Franchises & Platform Usage -->
        <div class="row">
            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6 p-0">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title">
                            <i data-feather="user-plus"></i> Total New Franchise this Month
                        </h5>
                    </div>
                    <div class="table-responsive p-3" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">S.No</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($franchiseThisMonths) > 0)
                                    @foreach($franchiseThisMonths as $key => $franchiseThisMonth)
                                        <tr>
                                            <td><span class="badge badge-info">{{ $key+1 }}</span></td>
                                            <td><strong>{{ ucfirst($franchiseThisMonth->name) }}</strong></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted">No Record Found !!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6 p-0">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title">
                            <i data-feather="activity"></i> Platform Usage this Month
                        </h5>
                    </div>
                    <div class="table-responsive p-3" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">S.No</th>
                                    <th>Coach Name</th>
                                    <th>New User Adds</th>
                                    <th>Total Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($platformUsageThisMonths) > 0)
                                    @foreach($platformUsageThisMonths as $key => $platformUsageThisMonth)

                                        @php
                                            $newUsers = $newUsersByFranchise->get($platformUsageThisMonth->id, 0);
                                            $totalUsers = $totalUsersByFranchise->get($platformUsageThisMonth->id, 0);
                                        @endphp

                                        <tr>
                                            <td><span class="badge badge-purple">{{ $key+1 }}</span></td>
                                            <td><strong>{{ ucfirst($platformUsageThisMonth->name) }}</strong></td>
                                            <td><span class="badge badge-success">+{{ $newUsers }}</span></td>
                                            <td><span class="badge badge-primary">{{ $totalUsers }}</span></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No Record Found !!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row: Coach LifeCycle -->
        <div class="row">
            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6 p-0">
                    <div class="admin-card-header">
                        <h5 class="admin-card-title">
                            <i data-feather="refresh-cw"></i> Coach LifeCycle
                        </h5>
                    </div>
                    <div class="table-responsive p-3" style="min-height: 10px;">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">S.No</th>
                                    <th>Coach Name</th>
                                    <th>Total Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($franchiseLifeCycles) > 0)
                                    @foreach($franchiseLifeCycles as $key => $franchiseLifeCycle)

                                        @php
                                            $totalUsers = $totalUsersByFranchise->get($franchiseLifeCycle->id, 0);
                                        @endphp

                                        <tr>
                                            <td><span class="badge badge-info">{{ $key+1 }}</span></td>
                                            <td><strong>{{ ucfirst($franchiseLifeCycle->name) }}</strong></td>
                                            <td><span class="badge badge-primary">{{ $totalUsers }}</span></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">No Record Found !!</td>
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