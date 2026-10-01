<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Transaction;

class YearlyReportController extends Controller
{
    /**
     * @var array
     */
    public $viewData = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * Yearly Report Index
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $authUser = auth()->user();
        $userId = $authUser->id;

        $year = (int)$request->get('year', date('Y'));
        $currentYear = (int)date('Y');
        $currentMonth = (int)date('n');

        // Year options (from next year down to 4 years ago)
        $availableYears = range($currentYear + 1, $currentYear - 4);

        // 1. Monthly Shakes (type = 2)
        $shakesRaw = Attendance::select(
            DB::raw('COUNT(id) as total_shakes'),
            DB::raw('MONTH(COALESCE(date, created_at)) as month')
        )
        ->where('franchise_id', $userId)
        ->where('type', 2)
        ->where(function ($q) use ($year) {
            $q->whereYear('date', $year)
              ->orWhere(function ($sub) use ($year) {
                  $sub->whereNull('date')->whereYear('created_at', $year);
              });
        })
        ->groupBy(DB::raw('MONTH(COALESCE(date, created_at))'))
        ->get()
        ->keyBy('month');

        // 2. Monthly Member Registrations
        $membersRaw = User::select(
            DB::raw('COUNT(id) as total_count'),
            DB::raw('MONTH(created_at) as month'),
            'user_type'
        )
        ->where('created_by', $userId)
        ->where('role_type', 'user')
        ->whereYear('created_at', $year)
        ->groupBy(DB::raw('MONTH(created_at)'), 'user_type')
        ->get();

        // 3. Monthly Financials (Revenue from Add User Days, Expenses from Order Placed)
        $transactionsRaw = Transaction::select(
            DB::raw('SUM(COALESCE(total_amount, 0)) as total_sum'),
            DB::raw('SUM(COALESCE(received_amount, 0)) as received_sum'),
            DB::raw('MONTH(created_at) as month'),
            'title'
        )
        ->where('created_by', $userId)
        ->whereYear('created_at', $year)
        ->whereIn('title', ['Add User Days', 'Order Placed'])
        ->groupBy(DB::raw('MONTH(created_at)'), 'title')
        ->get();

        // Build 12-month array
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        $shortMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $monthlyBreakdown = [];
        $shakeChartData = [];
        $regularChartData = [];
        $trialChartData = [];
        $demoChartData = [];
        $revenueChartData = [];
        $expenseChartData = [];
        $netChartData = [];

        $totalYearShakes = 0;
        $totalYearRegular = 0;
        $totalYearTrial = 0;
        $totalYearDemo = 0;
        $totalYearMembers = 0;
        $totalYearRevenue = 0.0;
        $totalYearExpense = 0.0;

        $peakMonthName = '-';
        $peakMonthShakes = 0;

        for ($m = 1; $m <= 12; $m++) {
            // Shakes
            $mShakes = isset($shakesRaw[$m]) ? (int)$shakesRaw[$m]->total_shakes : 0;
            $shakeChartData[] = $mShakes;
            $totalYearShakes += $mShakes;

            if ($mShakes > $peakMonthShakes) {
                $peakMonthShakes = $mShakes;
                $peakMonthName = $monthNames[$m];
            }

            // Members
            $mRegular = (int)$membersRaw->where('month', $m)->where('user_type', 'Regular User')->sum('total_count');
            $mTrial = (int)$membersRaw->where('month', $m)->whereIn('user_type', ['3 Days Trial', '3 Days', 'Trial'])->sum('total_count');
            $mDemo = (int)$membersRaw->where('month', $m)->whereIn('user_type', ['Demo User', 'Demo'])->sum('total_count');
            $mTotalMembers = $mRegular + $mTrial + $mDemo;

            $regularChartData[] = $mRegular;
            $trialChartData[] = $mTrial;
            $demoChartData[] = $mDemo;

            $totalYearRegular += $mRegular;
            $totalYearTrial += $mTrial;
            $totalYearDemo += $mDemo;
            $totalYearMembers += $mTotalMembers;

            // Financials
            $mRevenue = (float)$transactionsRaw->where('month', $m)->where('title', 'Add User Days')->sum('total_sum');
            $mExpense = (float)$transactionsRaw->where('month', $m)->where('title', 'Order Placed')->sum('total_sum');
            $mNet = $mRevenue - $mExpense;

            $revenueChartData[] = $mRevenue;
            $expenseChartData[] = $mExpense;
            $netChartData[] = $mNet;

            $totalYearRevenue += $mRevenue;
            $totalYearExpense += $mExpense;

            $monthlyBreakdown[$m] = [
                'month_num'      => $m,
                'month_name'     => $monthNames[$m],
                'month_short'    => $shortMonths[$m - 1],
                'shakes'         => $mShakes,
                'regular_users'  => $mRegular,
                'trial_users'    => $mTrial,
                'demo_users'     => $mDemo,
                'total_members'  => $mTotalMembers,
                'revenue'        => $mRevenue,
                'expense'        => $mExpense,
                'net_profit'     => $mNet,
                'is_current'     => ($year == $currentYear && $m == $currentMonth),
            ];
        }

        $totalYearNet = $totalYearRevenue - $totalYearExpense;
        $activeMonthsCount = ($year == $currentYear) ? max(1, $currentMonth) : 12;
        $avgMonthlyShakes = round($totalYearShakes / $activeMonthsCount);
        $avgMonthlyRevenue = round($totalYearRevenue / $activeMonthsCount);

        // Breadcrumb
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Yearly Report' => '',
        ];

        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['year'] = $year;
        $this->viewData['availableYears'] = $availableYears;
        $this->viewData['monthlyBreakdown'] = $monthlyBreakdown;
        $this->viewData['shortMonths'] = $shortMonths;

        // KPI totals
        $this->viewData['totalYearShakes'] = $totalYearShakes;
        $this->viewData['avgMonthlyShakes'] = $avgMonthlyShakes;
        $this->viewData['peakMonthName'] = $peakMonthName;
        $this->viewData['peakMonthShakes'] = $peakMonthShakes;

        $this->viewData['totalYearMembers'] = $totalYearMembers;
        $this->viewData['totalYearRegular'] = $totalYearRegular;
        $this->viewData['totalYearTrial'] = $totalYearTrial;
        $this->viewData['totalYearDemo'] = $totalYearDemo;

        $this->viewData['totalYearRevenue'] = $totalYearRevenue;
        $this->viewData['totalYearExpense'] = $totalYearExpense;
        $this->viewData['totalYearNet'] = $totalYearNet;
        $this->viewData['avgMonthlyRevenue'] = $avgMonthlyRevenue;

        // Chart arrays
        $this->viewData['shakeChartData'] = $shakeChartData;
        $this->viewData['regularChartData'] = $regularChartData;
        $this->viewData['trialChartData'] = $trialChartData;
        $this->viewData['demoChartData'] = $demoChartData;
        $this->viewData['revenueChartData'] = $revenueChartData;
        $this->viewData['expenseChartData'] = $expenseChartData;
        $this->viewData['netChartData'] = $netChartData;

        return view('nutrition-panel.reports.yearly')->with($this->viewData);
    }

    /**
     * Export Yearly Report as CSV
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        $authUser = auth()->user();
        $userId = $authUser->id;
        $year = (int)$request->get('year', date('Y'));

        // Query aggregations
        $shakesRaw = Attendance::select(
            DB::raw('COUNT(id) as total_shakes'),
            DB::raw('MONTH(COALESCE(date, created_at)) as month')
        )
        ->where('franchise_id', $userId)
        ->where('type', 2)
        ->where(function ($q) use ($year) {
            $q->whereYear('date', $year)
              ->orWhere(function ($sub) use ($year) {
                  $sub->whereNull('date')->whereYear('created_at', $year);
              });
        })
        ->groupBy(DB::raw('MONTH(COALESCE(date, created_at))'))
        ->get()
        ->keyBy('month');

        $membersRaw = User::select(
            DB::raw('COUNT(id) as total_count'),
            DB::raw('MONTH(created_at) as month'),
            'user_type'
        )
        ->where('created_by', $userId)
        ->where('role_type', 'user')
        ->whereYear('created_at', $year)
        ->groupBy(DB::raw('MONTH(created_at)'), 'user_type')
        ->get();

        $transactionsRaw = Transaction::select(
            DB::raw('SUM(COALESCE(total_amount, 0)) as total_sum'),
            DB::raw('MONTH(created_at) as month'),
            'title'
        )
        ->where('created_by', $userId)
        ->whereYear('created_at', $year)
        ->whereIn('title', ['Add User Days', 'Order Placed'])
        ->groupBy(DB::raw('MONTH(created_at)'), 'title')
        ->get();

        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        $fileName = 'yearly-report-' . $year . '-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($year, $monthNames, $shakesRaw, $membersRaw, $transactionsRaw) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Month',
                'Year',
                'Shakes Count',
                'Regular Members',
                '3-Day Trial Members',
                'Demo Members',
                'Total New Members',
                'Revenue (INR)',
                'Expenses (INR)',
                'Net Balance (INR)'
            ]);

            $totShakes = 0;
            $totRegular = 0;
            $totTrial = 0;
            $totDemo = 0;
            $totMembers = 0;
            $totRev = 0;
            $totExp = 0;

            for ($m = 1; $m <= 12; $m++) {
                $shakes = isset($shakesRaw[$m]) ? (int)$shakesRaw[$m]->total_shakes : 0;
                $regular = (int)$membersRaw->where('month', $m)->where('user_type', 'Regular User')->sum('total_count');
                $trial = (int)$membersRaw->where('month', $m)->whereIn('user_type', ['3 Days Trial', '3 Days', 'Trial'])->sum('total_count');
                $demo = (int)$membersRaw->where('month', $m)->whereIn('user_type', ['Demo User', 'Demo'])->sum('total_count');
                $totalM = $regular + $trial + $demo;

                $rev = (float)$transactionsRaw->where('month', $m)->where('title', 'Add User Days')->sum('total_sum');
                $exp = (float)$transactionsRaw->where('month', $m)->where('title', 'Order Placed')->sum('total_sum');
                $net = $rev - $exp;

                $totShakes += $shakes;
                $totRegular += $regular;
                $totTrial += $trial;
                $totDemo += $demo;
                $totMembers += $totalM;
                $totRev += $rev;
                $totExp += $exp;

                fputcsv($file, [
                    $monthNames[$m],
                    $year,
                    $shakes,
                    $regular,
                    $trial,
                    $demo,
                    $totalM,
                    number_format($rev, 2, '.', ''),
                    number_format($exp, 2, '.', ''),
                    number_format($net, 2, '.', '')
                ]);
            }

            // Total row
            fputcsv($file, [
                'TOTAL',
                $year,
                $totShakes,
                $totRegular,
                $totTrial,
                $totDemo,
                $totMembers,
                number_format($totRev, 2, '.', ''),
                number_format($totExp, 2, '.', ''),
                number_format($totRev - $totExp, 2, '.', '')
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
