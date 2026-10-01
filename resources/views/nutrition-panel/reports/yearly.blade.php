@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Yearly Report (' . $year . ') | ' . __('language.page_main_title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/plugins/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">

<style>
    :root {
        --fcc-primary: #2563eb;
        --fcc-dark: #0f172a;
        --fcc-muted: #64748b;
        --fcc-border: #e2e8f0;
        --fcc-card-bg: #ffffff;
        --fcc-bg: #f8fafc;
    }

    .yearly-report-page {
        padding: 6px 8px 40px 8px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Breadcrumb */
    .report-breadcrumb {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .report-breadcrumb a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .report-breadcrumb a:hover {
        color: #2563eb;
    }

    .report-breadcrumb .crumb-sep {
        color: #94a3b8;
    }

    .report-breadcrumb .crumb-active {
        color: #0f172a;
        font-weight: 600;
    }

    /* Header Bar */
    .report-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .report-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .report-subtitle {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 0;
        font-weight: 400;
    }

    .report-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* Year Selector Pill */
    .year-select-form {
        display: flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 4px 12px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .year-select-form i {
        color: #64748b;
        font-size: 14px;
        margin-right: 8px;
    }

    .year-select-form select {
        border: none;
        background: transparent;
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        outline: none;
        cursor: pointer;
        padding-right: 4px;
    }

    .btn-report-export {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-size: 13.5px;
        font-weight: 600;
        padding: 9px 16px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        text-decoration: none !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        transition: all 0.15s ease;
    }

    .btn-report-export:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .btn-report-print {
        background: #2563eb;
        border: 1px solid #2563eb;
        color: #ffffff !important;
        font-size: 13.5px;
        font-weight: 600;
        padding: 9px 18px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        box-shadow: 0 2px 5px rgba(37, 99, 235, 0.25);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-report-print:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    /* 5-Column Metric Cards Grid */
    .report-kpi-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (max-width: 1200px) {
        .report-kpi-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .report-kpi-grid {
            grid-template-columns: 1fr;
        }
    }

    .report-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .report-kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3.5px;
    }

    .kpi-blue::before { background: #2563eb; }
    .kpi-green::before { background: #10b981; }
    .kpi-indigo::before { background: #6366f1; }
    .kpi-amber::before { background: #f59e0b; }
    .kpi-teal::before { background: #06b6d4; }

    .kpi-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .kpi-label {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .kpi-icon-pill {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .kpi-blue .kpi-icon-pill { background: #eff6ff; color: #2563eb; }
    .kpi-green .kpi-icon-pill { background: #ecfdf5; color: #10b981; }
    .kpi-indigo .kpi-icon-pill { background: #eef2ff; color: #6366f1; }
    .kpi-amber .kpi-icon-pill { background: #fffbeb; color: #f59e0b; }
    .kpi-teal .kpi-icon-pill { background: #ecfeff; color: #06b6d4; }

    .kpi-value {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.15;
        margin-bottom: 6px;
        font-family: 'Outfit', sans-serif;
    }

    .kpi-subtext {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }

    /* Charts Section */
    .report-charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 991px) {
        .report-charts-grid {
            grid-template-columns: 1fr;
        }
    }

    .report-chart-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 22px 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .chart-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .chart-card-title {
        font-size: 16.5px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-card-title i {
        color: #2563eb;
        font-size: 16px;
    }

    .chart-card-badge {
        font-size: 11.5px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 99px;
        background: #f1f5f9;
        color: #475569;
    }

    /* Full Width Table Card */
    .report-table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .table-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .table-title {
        font-size: 17px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .table-subtitle {
        font-size: 13px;
        color: #64748b;
        margin: 2px 0 0 0;
    }

    .report-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13.5px;
    }

    .report-table thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 12.5px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 12px 14px;
        border-bottom: 1.5px solid #e2e8f0;
        white-space: nowrap;
    }

    .report-table tbody td {
        padding: 13px 14px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        vertical-align: middle;
    }

    .report-table tbody tr:hover td {
        background: #f8fafc;
    }

    .report-table tbody tr.row-current td {
        background: #f0f7ff;
        font-weight: 600;
    }

    .report-table tfoot td {
        background: #f8fafc;
        color: #0f172a;
        font-weight: 800;
        font-size: 13.5px;
        padding: 14px;
        border-top: 2px solid #cbd5e1;
        border-bottom: 2px solid #cbd5e1;
    }

    .badge-month {
        font-weight: 700;
        color: #0f172a;
    }

    .current-month-tag {
        font-size: 10.5px;
        font-weight: 700;
        background: #2563eb;
        color: #ffffff;
        padding: 2px 7px;
        border-radius: 99px;
        margin-left: 6px;
        text-transform: uppercase;
    }

    .num-positive {
        color: #10b981;
        font-weight: 700;
    }

    .num-negative {
        color: #ef4444;
        font-weight: 700;
    }

    .shakes-pill {
        background: #eff6ff;
        color: #2563eb;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 6px;
        display: inline-block;
    }

    /* Print Styles */
    @media print {
        .report-actions,
        .sidebar-wrapper,
        .header-container,
        .btn-report-export,
        .btn-report-print,
        .year-select-form {
            display: none !important;
        }
        .yearly-report-page {
            padding: 0 !important;
        }
        .report-table-card, .report-chart-card, .report-kpi-card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
        }
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="yearly-report-page">
        <!-- Breadcrumb -->
        <div class="report-breadcrumb">
            <a href="{{ route('nutritionPanel.dashboard') }}">Finance & Plans</a>
            <span class="crumb-sep">/</span>
            <span class="crumb-active">Yearly Report ({{ $year }})</span>
        </div>

        <!-- Header Bar -->
        <div class="report-header-bar">
            <div>
                <h1 class="report-title">Yearly Report</h1>
                <p class="report-subtitle">Annual performance, member acquisition, and financial runway overview.</p>
            </div>
            <div class="report-actions">
                <!-- Year Selector Form -->
                <form action="{{ route('nutritionPanel.yearly-report.index') }}" method="GET" class="year-select-form" id="yearSelectForm">
                    <i class="fa fa-calendar"></i>
                    <select name="year" id="selectYearDropdown" onchange="document.getElementById('yearSelectForm').submit();">
                        @foreach($availableYears as $availYear)
                            <option value="{{ $availYear }}" {{ $year == $availYear ? 'selected' : '' }}>
                                Year {{ $availYear }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <a href="{{ route('nutritionPanel.yearly-report.export', ['year' => $year]) }}" class="btn-report-export" title="Export as CSV">
                    <i class="fa fa-download"></i>
                    <span>Export CSV</span>
                </a>

                <button type="button" class="btn-report-print" onclick="window.print();" title="Print this report">
                    <i class="fa fa-print"></i>
                    <span>Print</span>
                </button>
            </div>
        </div>

        <!-- 5 Top KPI Cards -->
        <div class="report-kpi-grid">
            <!-- 1. Total Shakes -->
            <div class="report-kpi-card kpi-blue">
                <div>
                    <div class="kpi-head">
                        <span class="kpi-label">Annual Shakes</span>
                        <div class="kpi-icon-pill">
                            <i class="fa fa-coffee"></i>
                        </div>
                    </div>
                    <div class="kpi-value">{{ number_format($totalYearShakes) }}</div>
                </div>
                <div class="kpi-subtext">
                    Monthly Avg: <strong>{{ number_format($avgMonthlyShakes) }}</strong> · Peak: {{ $peakMonthName }} ({{ number_format($peakMonthShakes) }})
                </div>
            </div>

            <!-- 2. New Members Added -->
            <div class="report-kpi-card kpi-green">
                <div>
                    <div class="kpi-head">
                        <span class="kpi-label">New Registrations</span>
                        <div class="kpi-icon-pill">
                            <i class="fa fa-user-plus"></i>
                        </div>
                    </div>
                    <div class="kpi-value">{{ number_format($totalYearMembers) }}</div>
                </div>
                <div class="kpi-subtext">
                    Regular: <strong>{{ $totalYearRegular }}</strong> · Trial: <strong>{{ $totalYearTrial }}</strong> · Demo: <strong>{{ $totalYearDemo }}</strong>
                </div>
            </div>

            <!-- 3. Revenue -->
            <div class="report-kpi-card kpi-indigo">
                <div>
                    <div class="kpi-head">
                        <span class="kpi-label">Annual Revenue</span>
                        <div class="kpi-icon-pill">
                            <i class="fa fa-inr"></i>
                        </div>
                    </div>
                    <div class="kpi-value">₹{{ number_format($totalYearRevenue, 2) }}</div>
                </div>
                <div class="kpi-subtext">
                    From member renewals & day additions
                </div>
            </div>

            <!-- 4. Product Expenses -->
            <div class="report-kpi-card kpi-amber">
                <div>
                    <div class="kpi-head">
                        <span class="kpi-label">Club Expenses</span>
                        <div class="kpi-icon-pill">
                            <i class="fa fa-shopping-bag"></i>
                        </div>
                    </div>
                    <div class="kpi-value">₹{{ number_format($totalYearExpense, 2) }}</div>
                </div>
                <div class="kpi-subtext">
                    Orders placed for nutrition products
                </div>
            </div>

            <!-- 5. Net Operating Profit -->
            <div class="report-kpi-card kpi-teal">
                <div>
                    <div class="kpi-head">
                        <span class="kpi-label">Net Balance</span>
                        <div class="kpi-icon-pill">
                            <i class="fa fa-shield"></i>
                        </div>
                    </div>
                    <div class="kpi-value {{ $totalYearNet >= 0 ? 'num-positive' : 'num-negative' }}">
                        ₹{{ number_format($totalYearNet, 2) }}
                    </div>
                </div>
                <div class="kpi-subtext">
                    Operating margin (Revenue − Orders)
                </div>
            </div>
        </div>

        <!-- 2 Visual Charts Grid -->
        <div class="report-charts-grid">
            <!-- Left Chart: Shake Count by Month -->
            <div class="report-chart-card">
                <div class="chart-card-header">
                    <h3 class="chart-card-title">
                        <i class="fa fa-bar-chart"></i>
                        <span>Monthly Shake Count Trend</span>
                    </h3>
                    <span class="chart-card-badge">{{ $year }} Attendance Pulse</span>
                </div>
                <div id="chartYearlyShakes" style="min-height: 290px;"></div>
            </div>

            <!-- Right Chart: Member Growth Trajectories -->
            <div class="report-chart-card">
                <div class="chart-card-header">
                    <h3 class="chart-card-title">
                        <i class="fa fa-line-chart"></i>
                        <span>Member Registrations by Month</span>
                    </h3>
                    <span class="chart-card-badge">Breakdown: Regular / Trial / Demo</span>
                </div>
                <div id="chartYearlyMembers" style="min-height: 290px;"></div>
            </div>
        </div>

        <!-- Full-Width Chart: Financial Runway (Income vs Expense) -->
        <div class="report-chart-card mb-4">
            <div class="chart-card-header">
                <h3 class="chart-card-title">
                    <i class="fa fa-area-chart"></i>
                    <span>Financial Runway & Cash Flow ({{ $year }})</span>
                </h3>
                <span class="chart-card-badge">Income (User Days) vs Expenses (Orders Placed)</span>
            </div>
            <div id="chartYearlyFinancials" style="min-height: 310px;"></div>
        </div>

        <!-- Month-by-Month Detailed Table -->
        <div class="report-table-card">
            <div class="table-card-head">
                <div>
                    <h3 class="table-title">Month-by-Month Performance Breakdown</h3>
                    <p class="table-subtitle">Complete 12-month summary for {{ $year }}</p>
                </div>
                <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">
                    12 Months Recorded
                </span>
            </div>

            <div class="table-responsive">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th class="text-center">Shakes Count</th>
                            <th class="text-center">Regular Users</th>
                            <th class="text-center">3-Day Trial</th>
                            <th class="text-center">Demo Users</th>
                            <th class="text-center">Total New</th>
                            <th class="text-end">Revenue</th>
                            <th class="text-end">Expenses</th>
                            <th class="text-end">Net Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyBreakdown as $row)
                            <tr class="{{ $row['is_current'] ? 'row-current' : '' }}">
                                <td>
                                    <span class="badge-month">{{ $row['month_name'] }}</span>
                                    @if($row['is_current'])
                                        <span class="current-month-tag">Current</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="shakes-pill">{{ number_format($row['shakes']) }}</span>
                                </td>
                                <td class="text-center font-monospace">{{ $row['regular_users'] }}</td>
                                <td class="text-center font-monospace">{{ $row['trial_users'] }}</td>
                                <td class="text-center font-monospace">{{ $row['demo_users'] }}</td>
                                <td class="text-center fw-bold">{{ $row['total_members'] }}</td>
                                <td class="text-end font-monospace">₹{{ number_format($row['revenue'], 2) }}</td>
                                <td class="text-end font-monospace text-muted">₹{{ number_format($row['expense'], 2) }}</td>
                                <td class="text-end font-monospace {{ $row['net_profit'] >= 0 ? 'num-positive' : 'num-negative' }}">
                                    ₹{{ number_format($row['net_profit'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>TOTAL ({{ $year }})</td>
                            <td class="text-center font-monospace">{{ number_format($totalYearShakes) }}</td>
                            <td class="text-center font-monospace">{{ number_format($totalYearRegular) }}</td>
                            <td class="text-center font-monospace">{{ number_format($totalYearTrial) }}</td>
                            <td class="text-center font-monospace">{{ number_format($totalYearDemo) }}</td>
                            <td class="text-center font-monospace">{{ number_format($totalYearMembers) }}</td>
                            <td class="text-end font-monospace">₹{{ number_format($totalYearRevenue, 2) }}</td>
                            <td class="text-end font-monospace">₹{{ number_format($totalYearExpense, 2) }}</td>
                            <td class="text-end font-monospace {{ $totalYearNet >= 0 ? 'num-positive' : 'num-negative' }}">
                                ₹{{ number_format($totalYearNet, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/plugins/apex/apexcharts.min.js') }}"></script>
<script>
$(document).ready(function() {
    var shortMonths = {!! json_encode($shortMonths) !!};

    // 1. Shake Count Trend Bar Chart
    var shakeData = {!! json_encode($shakeChartData) !!};
    var shakesOptions = {
        chart: {
            type: 'bar',
            height: 290,
            fontFamily: 'Plus Jakarta Sans, sans-serif',
            toolbar: { show: false }
        },
        colors: ['#2563eb'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '40%',
                borderRadius: 6
            }
        },
        dataLabels: { enabled: false },
        series: [{
            name: 'Shakes Count',
            data: shakeData
        }],
        xaxis: {
            categories: shortMonths,
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                formatter: function(val) { return Math.round(val); }
            }
        },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 3
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) { return val + ' Shakes'; }
            }
        }
    };
    new ApexCharts(document.querySelector("#chartYearlyShakes"), shakesOptions).render();

    // 2. Member Registrations Multi-line Area Chart
    var regData = {!! json_encode($regularChartData) !!};
    var trialData = {!! json_encode($trialChartData) !!};
    var demoData = {!! json_encode($demoChartData) !!};

    var membersOptions = {
        chart: {
            type: 'area',
            height: 290,
            fontFamily: 'Plus Jakarta Sans, sans-serif',
            toolbar: { show: false }
        },
        colors: ['#10b981', '#3b82f6', '#8b5cf6'],
        stroke: {
            curve: 'smooth',
            width: 2.5
        },
        dataLabels: { enabled: false },
        series: [
            { name: 'Regular Users', data: regData },
            { name: '3-Day Trial', data: trialData },
            { name: 'Demo Users', data: demoData }
        ],
        xaxis: {
            categories: shortMonths,
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                formatter: function(val) { return Math.round(val); }
            }
        },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.35,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) { return val + ' Members'; }
            }
        }
    };
    new ApexCharts(document.querySelector("#chartYearlyMembers"), membersOptions).render();

    // 3. Financial Runway (Revenue vs Expense vs Net)
    var revData = {!! json_encode($revenueChartData) !!};
    var expData = {!! json_encode($expenseChartData) !!};

    var financeOptions = {
        chart: {
            type: 'bar',
            height: 310,
            fontFamily: 'Plus Jakarta Sans, sans-serif',
            toolbar: { show: false }
        },
        colors: ['#2563eb', '#f59e0b'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '45%',
                borderRadius: 6
            }
        },
        dataLabels: { enabled: false },
        series: [
            { name: 'Revenue (User Days)', data: revData },
            { name: 'Expenses (Orders Placed)', data: expData }
        ],
        xaxis: {
            categories: shortMonths,
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                formatter: function(val) { return '₹' + Number(val).toLocaleString('en-IN'); }
            }
        },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 3
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) { return '₹ ' + Number(val).toLocaleString('en-IN'); }
            }
        }
    };
    new ApexCharts(document.querySelector("#chartYearlyFinancials"), financeOptions).render();
});
</script>
@endpush
