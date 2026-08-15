@extends('nutrition-panel.layouts.main-layout')

@section('page-title', ' '.__('language.dashboard_page_title').' | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/plugins/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin-assets/css/components/tabs-accordian/custom-tabs.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin-assets/js/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/widgets/modules-widgets.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/forms/switches.css') }}">

<style>
    /* Modern Dashboard Hero & Cards */
    .dashboard-hero-banner {
        background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
        border: 1px solid #e2e8f0;
        border-left: 4px solid var(--npc-primary, #3246d3);
        border-radius: 12px;
        padding: 16px 22px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
    }

    .dashboard-hero-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.02em;
    }

    .dashboard-hero-subtitle {
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 0;
    }

    .plan-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 12.5px;
    }

    .plan-status-valid {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .plan-status-expired {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* 6 Quick Stat Metric Cards Grid */
    .stats-grid-container {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 14px;
        margin-bottom: 22px;
    }

    @media (max-width: 1280px) {
        .stats-grid-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 767px) {
        .stats-grid-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid-container {
            grid-template-columns: 1fr;
        }
    }

    .metric-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        transition: all 0.2s ease-in-out;
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px -3px rgba(50, 70, 211, 0.12);
        border-color: #3246d3;
    }

    .metric-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .metric-icon-circle {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        color: #ffffff;
    }

    .metric-icon-blue { background: linear-gradient(135deg, #3246d3, #4361ee); box-shadow: 0 3px 8px rgba(50, 70, 211, 0.3); }
    .metric-icon-purple { background: linear-gradient(135deg, #7c3aed, #9333ea); box-shadow: 0 3px 8px rgba(124, 58, 237, 0.3); }
    .metric-icon-green { background: linear-gradient(135deg, #059669, #10b981); box-shadow: 0 3px 8px rgba(16, 185, 129, 0.3); }
    .metric-icon-orange { background: linear-gradient(135deg, #d97706, #f59e0b); box-shadow: 0 3px 8px rgba(245, 158, 11, 0.3); }
    .metric-icon-cyan { background: linear-gradient(135deg, #0891b2, #06b6d4); box-shadow: 0 3px 8px rgba(6, 182, 212, 0.3); }
    .metric-icon-rose { background: linear-gradient(135deg, #dc2626, #ef4444); box-shadow: 0 3px 8px rgba(239, 68, 68, 0.3); }

    .metric-value {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 2px;
        line-height: 1.1;
    }

    .metric-label {
        font-size: 12.5px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* QR Code Card */
    .qr-card-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        text-align: center;
    }

    #qr-container {
        background: #ffffff;
        padding: 12px;
        border-radius: 12px;
        border: 2px dashed #cbd5e1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        margin-bottom: 16px;
        display: inline-block;
    }

    #qr-container canvas, #qr-container img {
        margin: 0 auto;
        border-radius: 6px;
    }

    @media print {
      body * { visibility: hidden; }
      #print-area, #print-area * { visibility: visible; }
      #print-area { position: absolute; left: 0; top: 0; width: 100%; }
    }

    /* Chart Headers & Legends */
    .chart-heading-wrapper {
        display: flex;
        gap: 14px;
        align-items: center;
        margin-bottom: 10px;
        font-size: 12.5px;
        font-weight: 600;
    }

    .chart-label {
        display: inline-flex;
        align-items: center;
        color: #475569;
    }

    .chart-label .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }

    .chart-label .blue { background: #3246d3; }
    .chart-label .red { background: #ef4444; }
    .chart-label .green { background: #10b981; }

    .table th {
        font-size: 11.5px !important;
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
                    Here's an overview of your nutrition club's performance, attendance, and member activity.
                </p>
            </div>
            <div>
                @php
                    use Carbon\Carbon;
                    $endDate = Carbon::parse($authUser['end_date']);
                    $today   = Carbon::today();
                @endphp

                @if($endDate->greaterThan($today))
                    <div class="plan-status-badge plan-status-valid">
                        <i data-feather="check-circle" style="width: 15px; height: 15px;"></i>
                        <span>Plan Valid Upto: <strong>{{ $endDate->format('d M, Y') }}</strong></span>
                    </div>
                @else
                    <div class="plan-status-badge plan-status-expired">
                        <i data-feather="alert-circle" style="width: 15px; height: 15px;"></i>
                        <span>Plan Expired on {{ $endDate->format('d M, Y') }} (Contact Super Admin)</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- 6 Stat Metric Cards Grid -->
        <div class="stats-grid-container">
            <!-- 1. Total Users -->
            <a href="{{ route('nutritionPanel.users.index') }}" class="text-decoration-none">
                <div class="metric-card">
                    <div class="metric-card-top">
                        <span class="badge badge-primary">Total</span>
                        <div class="metric-icon-circle metric-icon-blue">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                    <div>
                        <div class="metric-value s-counter1">{{ $totalUsers ?? 0 }}</div>
                        <div class="metric-label">Total Users</div>
                    </div>
                </div>
            </a>

            <!-- 2. This Month Shake Count -->
            <a href="{{ route('nutritionPanel.attendance-register.index') }}" class="text-decoration-none">
                <div class="metric-card">
                    <div class="metric-card-top">
                        <span class="badge badge-purple">Monthly</span>
                        <div class="metric-icon-circle metric-icon-purple">
                            <i class="fa fa-coffee"></i>
                        </div>
                    </div>
                    <div>
                        <div class="metric-value s-counter2">{{ $thisMonthShake ?? 0 }}</div>
                        <div class="metric-label">Monthly Shakes</div>
                    </div>
                </div>
            </a>

            <!-- 3. Offline Users -->
            <a href="{{ route('nutritionPanel.users.index') }}/offline" class="text-decoration-none">
                <div class="metric-card">
                    <div class="metric-card-top">
                        <span class="badge badge-warning">Club</span>
                        <div class="metric-icon-circle metric-icon-orange">
                            <i class="fa fa-building-o"></i>
                        </div>
                    </div>
                    <div>
                        <div class="metric-value s-counter3">{{ $offlineUsers ?? 0 }}</div>
                        <div class="metric-label">Offline Users</div>
                    </div>
                </div>
            </a>

            <!-- 4. Online Users -->
            <a href="{{ route('nutritionPanel.users.index') }}/online" class="text-decoration-none">
                <div class="metric-card">
                    <div class="metric-card-top">
                        <span class="badge badge-success">Digital</span>
                        <div class="metric-icon-circle metric-icon-green">
                            <i class="fa fa-globe"></i>
                        </div>
                    </div>
                    <div>
                        <div class="metric-value s-counter4">{{ $onlineUsers ?? 0 }}</div>
                        <div class="metric-label">Online Users</div>
                    </div>
                </div>
            </a>

            <!-- 5. Coach Count -->
            <a href="javascript:;" class="text-decoration-none">
                <div class="metric-card">
                    <div class="metric-card-top">
                        <span class="badge badge-info">Coaches</span>
                        <div class="metric-icon-circle metric-icon-cyan">
                            <i class="fa fa-user-md"></i>
                        </div>
                    </div>
                    <div>
                        <div class="metric-value s-counter5">{{ $coachCount ?? 0 }}</div>
                        <div class="metric-label">Coach Count</div>
                    </div>
                </div>
            </a>

            <!-- 6. New Memberships -->
            <a href="{{ route('nutritionPanel.users.index') }}" class="text-decoration-none">
                <div class="metric-card">
                    <div class="metric-card-top">
                        <span class="badge badge-danger">New</span>
                        <div class="metric-icon-circle metric-icon-rose">
                            <i class="fa fa-star"></i>
                        </div>
                    </div>
                    <div>
                        <div class="metric-value s-counter6">{{ $thisMonthUsers ?? 0 }}</div>
                        <div class="metric-label">New Members</div>
                    </div>
                </div>
            </a>
        </div>

        <!-- QR Code & Today's Birthday Section -->
        <div class="row g-3 mb-4">
            <!-- QR Attendance Card -->
            <div class="col-xl-5 col-lg-6 col-md-12 col-12">
                <div class="widget-content widget-content-area h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h4 class="mb-2">QR Code Attendance</h4>
                        <p class="text-muted fs-6 mb-4">Members can scan this QR code directly at your club to mark attendance.</p>
                    </div>
                    <div class="qr-card-content py-2">
                        <div id="print-area">
                            <div id="qr-container"></div>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <button type="button" onclick="window.print()" class="btn btn-primary">
                                <i class="fa fa-print me-1"></i> Print QR
                            </button>
                            <button type="button" id="downloadBtn" class="btn btn-dark">
                                <i class="fa fa-download me-1"></i> Download PNG
                            </button>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted"><i class="fa fa-shield text-success me-1"></i> Secure Encrypted Daily Club Pass</small>
                    </div>
                </div>
            </div>

            <!-- Today's Birthday Card -->
            <div class="col-xl-7 col-lg-6 col-md-12 col-12">
                <div class="widget-content widget-content-area h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="mb-0">Today's Birthdays 🎂</h4>
                        <span class="badge badge-purple">{{ count($thisMonthBirthdayUsers ?? []) }} Today</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Birth Year</th>
                                    <th>User Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($thisMonthBirthdayUsers) && count($thisMonthBirthdayUsers) > 0)
                                    @foreach($thisMonthBirthdayUsers as $thisMonthBirthdayUser)
                                        <tr>
                                            <td class="fw-bold text-dark">
                                                <i class="fa fa-birthday-cake text-warning me-2"></i>
                                                {{ ucfirst($thisMonthBirthdayUser->name) }}
                                            </td>
                                            <td>{{ date('Y', strtotime($thisMonthBirthdayUser->date_of_birth)) }}</td>
                                            <td>
                                                @if($thisMonthBirthdayUser->user_type == 'Regular User')
                                                    <span class="badge badge-success">{{ $thisMonthBirthdayUser->user_type }} ({{ $thisMonthBirthdayUser->user_state }})</span>
                                                @else
                                                    <span class="badge badge-primary">{{ $thisMonthBirthdayUser->user_type }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            <i class="fa fa-gift fa-2x mb-2 d-block text-muted opacity-50"></i>
                                            No birthdays today. Check back tomorrow!
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Attendance Logs & Multiple Attendance -->
        <div class="row g-3 mb-4">
            <!-- Multiple Attendance Today -->
            <div class="col-xl-6 col-lg-12 col-12">
                <div class="widget-content widget-content-area h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="mb-0">Multiple Attendance Today</h4>
                        <span class="badge badge-warning">{{ date('d M, Y') }}</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Member Name</th>
                                    <th>Date</th>
                                    <th>Shakes / Visits</th>
                                    <th>Coach</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($today2Attendences) && count($today2Attendences) > 0)
                                    @foreach($today2Attendences as $today2Attendence)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ ucfirst($today2Attendence->name) }}</td>
                                            <td>{{ $today2Attendence->date }}</td>
                                            <td><span class="badge badge-purple">{{ $today2Attendence->total_attendance }} visits</span></td>
                                            <td>{{ $today2Attendence->coach_name ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">No multiple attendance records today.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Attendance Updates Today -->
            <div class="col-xl-6 col-lg-12 col-12">
                <div class="widget-content widget-content-area h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="mb-0">Today's Attendance Logs</h4>
                        <span class="badge badge-info">{{ count($todayAttendences ?? []) }} Logs</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Member Name</th>
                                    <th>Log Type / Remark</th>
                                    <th>Count</th>
                                    <th>Coach</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($todayAttendences) && count($todayAttendences) > 0)
                                    @foreach($todayAttendences as $todayAttendence)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ ucfirst($todayAttendence->name) }}</td>
                                            <td><span class="badge badge-primary">{{ $todayAttendence->remark }}</span></td>
                                            <td>1</td>
                                            <td>{{ $todayAttendence->coach_name ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">No updates recorded yet today.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 20 & Least 20 Attendance Ranking -->
        <div class="row g-3 mb-4">
            <!-- Top 20 Attendance -->
            <div class="col-xl-6 col-lg-12 col-12">
                <div class="widget-content widget-content-area h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="mb-0">Top 20 Attendance ({{ date('F Y') }}) 🏆</h4>
                        <span class="badge badge-success">Goal: {{ $totalDaysInMonth }} Days</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Member</th>
                                    <th>Present Days</th>
                                    <th>Percentage</th>
                                    <th>Coach</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($top20Attendance) && count($top20Attendance) > 0)
                                    @foreach($top20Attendance as $top20Attend)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ ucfirst($top20Attend->name) }}</td>
                                            <td><span class="badge badge-purple">{{ $top20Attend->total_attendance }} / {{ $totalDaysInMonth }}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-grow-1" style="height: 6px; border-radius: 4px;">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(100, $top20Attend->attendance_percentage) }}%"></div>
                                                    </div>
                                                    <span class="fw-bold fs-7">{{ $top20Attend->attendance_percentage }}%</span>
                                                </div>
                                            </td>
                                            <td>{{ $top20Attend->coach_name ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">No records found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Least 20 Attendance -->
            <div class="col-xl-6 col-lg-12 col-12">
                <div class="widget-content widget-content-area h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="mb-0">Least 20 Attendance ({{ date('F Y') }}) ⚠️</h4>
                        <span class="badge badge-danger">Needs Follow-Up</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Member</th>
                                    <th>Present Days</th>
                                    <th>Percentage</th>
                                    <th>Coach</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($least20Attendance) && count($least20Attendance) > 0)
                                    @foreach($least20Attendance as $least20Attend)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ ucfirst($least20Attend->name) }}</td>
                                            <td><span class="badge badge-warning">{{ $least20Attend->total_attendance }} / {{ $totalDaysInMonth }}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-grow-1" style="height: 6px; border-radius: 4px;">
                                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ min(100, $least20Attend->attendance_percentage) }}%"></div>
                                                    </div>
                                                    <span class="fw-bold fs-7">{{ $least20Attend->attendance_percentage }}%</span>
                                                </div>
                                            </td>
                                            <td>{{ $least20Attend->coach_name ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">No records found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Year Filter for Charts -->
        <div class="widget-content widget-content-area mb-4">
            {!! Form::open(['class' => 'custom-datatable-filter-form d-flex align-items-center justify-content-between flex-wrap gap-3', 'method' => 'GET']) !!}
                <div>
                    <h4 class="mb-1">Annual Analytics Overview</h4>
                    <p class="text-muted mb-0 fs-6">Interactive graphical representation of shake intake, membership trends, and revenue.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @php
                        $years = [];
                        $currentYear = date('Y');
                        for ($i = 0; $i < 5; $i++) {
                            $years[$currentYear - $i] = $currentYear - $i;
                        }
                    @endphp
                    {!! Form::select(
                        'year_filter',
                        $years,
                        request('year_filter', $currentYear),
                        ['class' => 'form-control select-picker', 'id' => 'year_filter', 'style' => 'width: 120px;']
                    ) !!}
                    {{ Form::button( '<i class="fa fa-filter"></i> Apply', ['class' => 'btn btn-primary', 'type' => 'submit'] )}}
                </div>
            {!! Form::close() !!}
        </div>

        <!-- Apex Charts Section -->
        <div class="row g-3 mb-4">
            <!-- Shake Count Monthly Graph -->
            <div class="col-xl-6 col-lg-12 col-12">
                <div class="widget-content widget-content-area h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="mb-0">Monthly Shake Count Graph</h4>
                        <span class="badge badge-purple">{{ request('year_filter', date('Y')) }}</span>
                    </div>
                    <div id="shakeCountGraph"></div>
                </div>
            </div>

            <!-- Users Category Line Graph -->
            <div class="col-xl-6 col-lg-12 col-12">
                <div class="widget-content widget-content-area h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="mb-0">User Growth Breakdown</h4>
                        <div class="chart-heading-wrapper mb-0">
                            <span class="chart-label"><i class="dot blue"></i> Demo</span>
                            <span class="chart-label"><i class="dot red"></i> 3-Day Trial</span>
                            <span class="chart-label"><i class="dot green"></i> Regular</span>
                        </div>
                    </div>
                    <div id="revenueMonthly"></div>
                </div>
            </div>

            <!-- Income & Expenses (Deposit vs Purchase) -->
            <div class="col-12">
                <div class="widget-content widget-content-area">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="mb-0">Revenue & Product Transactions Graph</h4>
                        <div class="d-flex gap-3">
                            <span class="badge badge-primary"><i class="fa fa-square me-1 text-primary"></i> Income (Orders Placed)</span>
                            <span class="badge badge-danger"><i class="fa fa-square me-1 text-danger"></i> Revenue (Add User Days)</span>
                        </div>
                    </div>
                    <div id="incomeExpenseGraph"></div>
                </div>
            </div>
        </div>

        <!-- Pending Payments & Expiring Memberships -->
        <div class="row g-3 mb-4">
            <!-- Pending Payments -->
            <div class="col-xl-6 col-lg-12 col-12">
                <div class="widget-content widget-content-area h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="mb-0">Pending User Payments</h4>
                        <span class="badge badge-danger">{{ count($paymentPendings ?? []) }} Pending</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Member Name</th>
                                    <th>Due Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($paymentPendings) && count($paymentPendings) > 0)
                                    @foreach($paymentPendings as $key => $paymentPending)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td class="fw-bold text-dark">{{ ucfirst($paymentPending->name) }}</td>
                                            <td><span class="badge badge-danger font-weight-bold">₹{{ number_format($paymentPending->due_amount, 2) }}</span></td>
                                            <td>
                                                <a href="{{ route('nutritionPanel.users.details', ['id' => ev($paymentPending->id)]) }}" class="btn btn-sm btn-light text-primary" title="View Member">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">No pending payments found!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customer Expiring Memberships -->
            <div class="col-xl-6 col-lg-12 col-12">
                <div class="widget-content widget-content-area h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="mb-0">Expiring Memberships (≤ 10 Days)</h4>
                        <span class="badge badge-warning">{{ count($membershipExpires ?? []) }} Expiring</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Member Name</th>
                                    <th>Remaining Days</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($membershipExpires) && count($membershipExpires) > 0)
                                    @foreach($membershipExpires as $key => $membershipExpire)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td class="fw-bold text-dark">{{ ucfirst($membershipExpire->name) }}</td>
                                            <td>
                                                <span class="badge badge-warning">{{ $membershipExpire->days }} Days</span>
                                            </td>
                                            <td>
                                                <label class="switch s-success p-0 m-0">
                                                    <input type="checkbox" class="status-toggle" data-change-status-url="{{ route('nutritionPanel.users.changeStatus') }}" value="{{ $membershipExpire->id }}" @if($membershipExpire->status == 1) checked @endif>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">No memberships expiring soon.</td>
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
<script src="{{ asset('admin-assets/plugins/apex/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/counter/jquery.countTo.js') }}"></script>
<script src="{{ asset('admin-assets/js/components/custom-counter.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    $(document).on('change', '.status-toggle', function () {
        let ids = [];
        ids.push($(this).val());

        $.ajax({
            url: $(this).data("change-status-url"),
            type: "POST",
            data: {
                ids: ids,
            },
            success: function (response) {
                App.showNotification(response);
            },
            error: function () {
                App.showNotification(response);
            }
        });
    });

    // Generate QR
    const qrValue = "{{ $qr_code }}";

    if(qrValue && qrValue.trim() !== ""){
        new QRCode(document.getElementById("qr-container"), {
            text: qrValue,
            width: 140,
            height: 140,
            colorDark: "#3246d3",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }

    // Download QR as PNG
    const downloadBtn = document.getElementById('downloadBtn');
    if(downloadBtn) {
        downloadBtn.onclick = function(){
            const canvas = document.querySelector('#qr-container canvas');
            if(canvas) {
                const link = document.createElement('a');
                link.download = 'qr-' + qrValue + '.png';
                link.href = canvas.toDataURL();
                link.click();
            }
        };
    }

    // ApexCharts - Shake Count Single Bar
    var shakeCount = @json($totalShakeChartData);
    var d_1options1 = {
      chart: {
          height: 330,
          type: 'bar',
          fontFamily: 'Plus Jakarta Sans, Nunito, sans-serif',
          toolbar: { show: false },
          dropShadow: {
              enabled: false
          }
      },
      colors: ['#3246d3'],
      plotOptions: {
          bar: {
              horizontal: false,
              columnWidth: '45%',
              borderRadius: 6
          },
      },
      dataLabels: { enabled: false },
      legend: { show: false },
      stroke: { show: true, width: 2, colors: ['transparent'] },
      series: [{
          name: 'Shake Count',
          data: shakeCount
      }],
      xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          axisBorder: { show: false },
          axisTicks: { show: false }
      },
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'light',
          type: 'vertical',
          shadeIntensity: 0.2,
          opacityFrom: 1,
          opacityTo: 0.75,
          stops: [0, 100]
        }
      },
      tooltip: {
        theme: 'dark',
        y: {
            formatter: function (val) { return val + ' Shakes'; }
        }
      }
    };

    var d_1C_3 = new ApexCharts(
        document.querySelector("#shakeCountGraph"),
        d_1options1
    );
    d_1C_3.render();

    // ApexCharts - Users Breakdown Line Area
    var userDemoChartData = @json($userDemoChartData);
    var userTrailChartData = @json($userTrailChartData);
    var userRegualrChartData = @json($userRegualrChartData);

    var options1 = {
      chart: {
        fontFamily: 'Plus Jakarta Sans, Nunito, sans-serif',
        height: 330,
        type: 'area',
        toolbar: { show: false }
      },
      colors: ['#3246d3', '#ef4444', '#10b981'],
      dataLabels: { enabled: false },
      stroke: {
        show: true,
        curve: 'smooth',
        width: 3
      },
      series: [
        { name: 'Demo Users', data: userDemoChartData },
        { name: '3-Day Trial', data: userTrailChartData },
        { name: 'Regular Users', data: userRegualrChartData }
      ],
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      xaxis: {
        axisBorder: { show: false },
        axisTicks: { show: false }
      },
      grid: {
        borderColor: '#e2e8f0',
        strokeDashArray: 4
      },
      legend: { show: false },
      tooltip: {
        theme: 'dark',
        y: {
          formatter: function(val) { return val + ' Users'; }
        }
      },
      fill: {
        type: "gradient",
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.35,
          opacityTo: 0.05,
          stops: [0, 90, 100]
        }
      }
    };

    var chart1 = new ApexCharts(
      document.querySelector("#revenueMonthly"),
      options1
    );
    chart1.render();

    // ApexCharts - Income & Expenses Bar Graph
    var transactionAddUserChartData = @json($transactionAddUserChartData);
    var transactionOrderPlacedChartData = @json($transactionOrderPlacedChartData);

    var options = {
      chart: {
          height: 340,
          type: 'bar',
          fontFamily: 'Plus Jakarta Sans, Nunito, sans-serif',
          toolbar: { show: false }
      },
      colors: ['#3246d3', '#ef4444'],
      plotOptions: {
          bar: {
              horizontal: false,
              columnWidth: '40%',
              borderRadius: 6
          }
      },
      dataLabels: { enabled: false },
      legend: { show: false },
      stroke: { show: true, width: 2, colors: ['transparent'] },
      series: [
          { name: 'Income (Orders)', data: transactionOrderPlacedChartData },
          { name: 'Revenue (User Days)', data: transactionAddUserChartData }
      ],
      xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          axisBorder: { show: false },
          axisTicks: { show: false }
      },
      grid: {
        borderColor: '#e2e8f0',
        strokeDashArray: 4
      },
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'light',
          type: 'vertical',
          shadeIntensity: 0.2,
          opacityFrom: 1,
          opacityTo: 0.8,
          stops: [0, 100]
        }
      },
      tooltip: {
          theme: 'dark',
          y: {
              formatter: function (val) { return '₹ ' + Number(val).toLocaleString('en-IN'); }
          }
      }
    };

    var chart = new ApexCharts(
        document.querySelector("#incomeExpenseGraph"),
        options
    );
    chart.render();
</script>
@endpush