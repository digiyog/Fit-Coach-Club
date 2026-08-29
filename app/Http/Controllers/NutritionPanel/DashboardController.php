<?php
namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Mail;
use App;
use Auth;
use DateTime;
use Carbon\Carbon;
use App\Http\Traits\UploadImage;
use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceLogs;
use App\Models\Transaction;
use MrShan0\CryptoLib\CryptoLib;

class DashboardController extends Controller
{
    use UploadImage;

    public $viewData = [];

    /**
      Home Page
    **/
    public function index(Request $request)
    {
        $data = array(
            'pageTitle'             => 'Dashboard',
            'pageDescrption'        => 'Dashboard'
        );

        return view('nutritions.pages.index')->with($data);
    }

    /**
     * View dashboard.
     *
     * @summary dashboard.
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     * @author Divyansh
     * @created 13 Feb 2023
     */
    public function dashboard(Request $request)
    {
        $authUser = auth()->user();
        $userId = $authUser->id ?? 0;

        if ($request->year_filter != '') {
            $year = $request->year_filter;
        } else {
            $year = date('Y');
        }

        // 1. Total Members Breakdown
        $totalUsers = User::where('role_type', 'user')->where('created_by', $userId)->count();
        $offlineUsers = User::where('role_type', 'user')->where('user_state', 'Offline')->where('created_by', $userId)->count();
        $onlineUsers = User::where('role_type', 'user')->where('user_state', 'Online')->where('created_by', $userId)->count();
        $totalCoaches = User::where('role_type', 'user')
            ->where('created_by', $userId)
            ->whereNotNull('coach_name')
            ->where('coach_name', '!=', '')
            ->distinct()
            ->pluck('coach_name')
            ->unique()
            ->count();

        // 2. Weekly Pulse (Last 7 Days) for Attendance and Revenue Charts
        $weeklyPulseLabels = [];
        $weeklyPulseAttendance = [];
        $weeklyPulseRevenue = [];

        for ($i = 6; $i >= 0; $i--) {
            $dayDate = Carbon::today()->subDays($i);
            $dayStr = $dayDate->format('Y-m-d');
            $weeklyPulseLabels[] = $dayDate->format('M d');

            $attendCount = Attendance::where('franchise_id', $authUser->id)
                ->where('type', 2)
                ->whereDate('date', $dayStr)
                ->count();
            $weeklyPulseAttendance[] = (int)$attendCount;

            $revSum = Transaction::where('created_by', $authUser->id)
                ->whereDate('created_at', $dayStr)
                ->sum('received_amount');
            if ($revSum == 0) {
                $revSum = Transaction::where('created_by', $authUser->id)
                    ->whereDate('created_at', $dayStr)
                    ->sum('total_amount');
            }
            $weeklyPulseRevenue[] = (float)$revSum;
        }

        // Previous 7 days vs current 7 days attendance for growth %
        $prev7DaysAttendance = Attendance::where('franchise_id', $authUser->id)
            ->where('type', 2)
            ->whereBetween('date', [Carbon::today()->subDays(13)->format('Y-m-d'), Carbon::today()->subDays(7)->format('Y-m-d')])
            ->count();
        $curr7DaysAttendance = array_sum($weeklyPulseAttendance);

        if ($prev7DaysAttendance > 0) {
            $weeklyGrowthPct = round((($curr7DaysAttendance - $prev7DaysAttendance) / $prev7DaysAttendance) * 100, 1);
        } else {
            $weeklyGrowthPct = $curr7DaysAttendance > 0 ? 100 : 0;
        }

        $dailyAvgAttendance = count($weeklyPulseAttendance) > 0 ? round(array_sum($weeklyPulseAttendance) / count($weeklyPulseAttendance), 1) : 0;
        $weeklyPeakAttendance = count($weeklyPulseAttendance) > 0 ? max($weeklyPulseAttendance) : 0;

        // 3. Today Stats
        $todayDate = date('Y-m-d');
        $todayCounsellingCount = Attendance::where('franchise_id', $authUser->id)
            ->where('type', 2)
            ->whereDate('date', $todayDate)
            ->distinct('user_id')
            ->count('user_id');

        $todayNewMemberships = User::where('role_id', 3)
            ->where('created_by', $authUser->id)
            ->whereDate('created_at', $todayDate)
            ->count();

        $thisMonthNewMembers = User::where('role_id', 3)
            ->where('created_by', $authUser->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $todayRenewalsDue = User::where('role_id', 3)
            ->where('created_by', $authUser->id)
            ->where('days', '<=', 10)
            ->where('days', '>', 0)
            ->count();

        $todayUrgentRenewals = User::where('role_id', 3)
            ->where('created_by', $authUser->id)
            ->where('days', '<=', 3)
            ->where('days', '>', 0)
            ->count();

        $thisMonthBirthdayUsers = User::where('role_id', 3)
            ->where('created_by', $userId)
            ->whereDay('date_of_birth', now()->day)
            ->whereMonth('date_of_birth', now()->month)
            ->get();

        // 4. Metric Cards
        $thisMonthShake = Attendance::where('franchise_id', $authUser->id)
            ->where('type', 2)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count();

        $thisMonthRevenue = Transaction::where('created_by', $authUser->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('received_amount');
        if ($thisMonthRevenue == 0) {
            $thisMonthRevenue = Transaction::where('created_by', $authUser->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount');
        }

        $todayCollected = Transaction::where('created_by', $authUser->id)
            ->whereDate('created_at', $todayDate)
            ->sum('received_amount');
        if ($todayCollected == 0) {
            $todayCollected = Transaction::where('created_by', $authUser->id)
                ->whereDate('created_at', $todayDate)
                ->sum('total_amount');
        }

        $todayCheckedIn = Attendance::where('franchise_id', $authUser->id)
            ->where('type', 2)
            ->whereDate('date', $todayDate)
            ->count();

        $todayAttendences = AttendanceLogs::select('attendance_logs.*', 'users.name', 'users.coach_name')
            ->leftJoin('users', function($join) use ($authUser){
                $join->on('attendance_logs.user_id', '=', 'users.id');
            })
            ->where('users.created_by', $authUser->id)
            ->where('attendance_logs.date', $todayDate)
            ->get();

        $today2Attendences = Attendance::select(
                'attendances.user_id',
                'attendances.date',
                'users.name',
                'users.coach_name',
                DB::raw('COUNT(attendances.id) as total_attendance')
            )
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->where('attendances.franchise_id', $authUser->id)
            ->where('attendances.type', 2)
            ->whereDate('attendances.date', $todayDate)
            ->groupBy('attendances.user_id', 'attendances.date', 'users.name', 'users.coach_name')
            ->having('total_attendance', '>', 1)
            ->get();

        // 5. Expiring Memberships & Pending Payments
        $paymentPendings = User::select('users.id', 'users.user_type', 'users.user_state', 'users.name', 'users.email' ,'users.mobile_number', 'users.coach_name', 'users.meal_type_id', 'users.product_type_id', 'users.days', 'users.due_amount', 'users.status', 'users.created_at')
            ->where("users.role_type", 'user')
            ->where("users.created_by", $authUser->id)
            ->where('due_amount', '>', 0)
            ->orderBy('due_amount', 'DESC')
            ->get();

        $membershipExpires = User::select('users.id', 'users.user_type', 'users.user_state', 'users.name', 'users.email' ,'users.mobile_number', 'users.coach_name', 'users.meal_type_id', 'users.product_type_id', 'users.days', 'users.due_amount', 'users.status', 'users.created_at')
            ->where("users.role_type", 'user')
            ->where("users.created_by", $authUser->id)
            ->where('days', '<=', 10)
            ->orderBy('days', 'ASC')
            ->get();

        $totalAlertsCount = count($membershipExpires->where('days', '<=', 3)) + count($paymentPendings) + count($thisMonthBirthdayUsers);

        // Dynamic Action Queue Items
        $actionQueueItems = [];
        foreach ($membershipExpires->take(4) as $mExp) {
            $actionQueueItems[] = [
                'name' => $mExp->name,
                'subtext' => ($mExp->days <= 0) ? 'Membership expired' : 'Membership expires in ' . $mExp->days . ' ' . \Illuminate\Support\Str::plural('day', $mExp->days),
                'action_label' => 'Renew',
                'action_url' => route('nutritionPanel.users.addUserDays', ['id' => ev($mExp->id)]),
                'color_class' => 'av-red',
                'link_class' => 'link-renew'
            ];
        }
        foreach ($paymentPendings->take(4) as $pPen) {
            $actionQueueItems[] = [
                'name' => $pPen->name,
                'subtext' => '₹' . number_format($pPen->due_amount, 0) . ' payment due',
                'action_label' => 'Remind',
                'action_url' => route('nutritionPanel.users.details', ['id' => ev($pPen->id)]),
                'color_class' => 'av-orange',
                'link_class' => 'link-remind'
            ];
        }

        // 6. Dynamic Recent Activity Feed (Latest Attendances + Transactions)
        $recentAttendances = Attendance::select('attendances.*', 'users.name as user_name')
            ->leftJoin('users', 'attendances.user_id', '=', 'users.id')
            ->where('attendances.franchise_id', $authUser->id)
            ->where('attendances.type', 2)
            ->orderBy('attendances.id', 'DESC')
            ->limit(5)
            ->get();

        $recentTransactions = Transaction::select('transactions.*', 'users.name as user_name')
            ->leftJoin('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.created_by', $authUser->id)
            ->orderBy('transactions.id', 'DESC')
            ->limit(5)
            ->get();

        $recentActivities = collect();
        foreach ($recentAttendances as $att) {
            $recentActivities->push([
                'title' => ($att->user_name ? ucfirst($att->user_name) : 'Member') . ' checked in',
                'time' => $att->created_at ? $att->created_at->diffForHumans() : ($att->date ? date('d M, h:i A', strtotime($att->date)) : 'Today'),
                'raw_time' => $att->created_at ? $att->created_at->timestamp : strtotime($att->date ?? 'now'),
                'dot_class' => 'fcc-dot-green'
            ]);
        }
        foreach ($recentTransactions as $trx) {
            $amt = $trx->received_amount ?: $trx->total_amount;
            $recentActivities->push([
                'title' => ($trx->user_name ? ucfirst($trx->user_name) : 'Member') . ' payment of ₹' . number_format($amt, 0) . ' recorded',
                'time' => $trx->created_at ? $trx->created_at->diffForHumans() : 'Recent',
                'raw_time' => $trx->created_at ? $trx->created_at->timestamp : 0,
                'dot_class' => 'fcc-dot-blue'
            ]);
        }
        $recentActivities = $recentActivities->sortByDesc('raw_time')->take(5)->values();

        // 7. Top 20 & Least 20 Attendance
        $month = now()->month;
        $currYear = now()->year;

        if ($month == now()->month && $currYear == now()->year) {
            $totalDaysTillToday = now()->day;
        } else {
            $totalDaysTillToday = Carbon::createFromDate($currYear, $month)->daysInMonth;
        }
        if ($totalDaysTillToday <= 0) {
            $totalDaysTillToday = 1;
        }

        $top20Attendance = Attendance::select(
            'attendances.user_id',
            'users.name',
            'users.coach_name',
            DB::raw('COUNT(attendances.id) as total_attendance'),
            DB::raw("
                ROUND(
                    (COUNT(attendances.id) / $totalDaysTillToday) * 100,
                    2
                ) as attendance_percentage
            ")
        )
        ->join('users', 'users.id', '=', 'attendances.user_id')
        ->where('attendances.franchise_id', $authUser->id)
        ->where('attendances.type', 2)
        ->whereMonth('attendances.date', $month)
        ->whereYear('attendances.date', $currYear)
        ->groupBy(
            'attendances.user_id',
            'users.name',
            'users.coach_name'
        )
        ->orderByDesc('total_attendance')
        ->limit(20)
        ->get();

        $least20Attendance = Attendance::select(
            'attendances.user_id',
            'users.name',
            'users.coach_name',
            DB::raw('COUNT(attendances.id) as total_attendance'),
            DB::raw("
                ROUND(
                    (COUNT(attendances.id) / $totalDaysTillToday) * 100,
                    2
                ) as attendance_percentage
            ")
        )
        ->join('users', 'users.id', '=', 'attendances.user_id')
        ->where('attendances.franchise_id', $authUser->id)
        ->where('attendances.type', 2)
        ->whereMonth('attendances.date', $month)
        ->whereYear('attendances.date', $currYear)
        ->groupBy(
            'attendances.user_id',
            'users.name',
            'users.coach_name'
        )
        ->orderBy('total_attendance', 'asc')
        ->limit(20)
        ->get();

        // 8. Yearly Charts (Shake Count, Users Breakdown, Transactions Breakdown)
        $thisMonthTotalShakes = Attendance::select(
            DB::raw('COUNT(id) as total'),
            DB::raw('MONTH(date) as month')
        )
        ->whereYear('date', $year)
        ->where('type', 2)
        ->where('franchise_id', $authUser->id)
        ->groupBy(DB::raw('MONTH(date)'))
        ->get();

        $totalShakeChartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $totalShakeChartData[] = (int)($thisMonthTotalShakes->where('month', $m)->sum('total'));
        }

        $usersRaw = User::select(
            DB::raw('COUNT(id) as total'),
            DB::raw('MONTH(created_at) as month'),
            'user_type'
        )
        ->whereYear('created_at', $year)
        ->groupBy('month', 'user_type')
        ->where('user_type', '!=', '')
        ->where('created_by', $userId)
        ->get();

        $userDemoChartData = [];
        $userTrailChartData = [];
        $userRegualrChartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $userDemoChartData[] = (int)($usersRaw->where('month', $m)->where('user_type', 'Demo User')->sum('total'));
            $userTrailChartData[] = (int)($usersRaw->where('month', $m)->where('user_type', '3 Days Trial')->sum('total'));
            $userRegualrChartData[] = (int)($usersRaw->where('month', $m)->where('user_type', 'Regular User')->sum('total'));
        }

        $transactionRaw = Transaction::select(
            DB::raw('SUM(COALESCE(received_amount, total_amount)) as total_amount'),
            DB::raw('MONTH(created_at) as month'),
            'title'
        )
        ->whereYear('created_at', $year)
        ->whereIn('title', ['Add User Days', 'Order Placed'])
        ->groupBy('month', 'title')
        ->where('created_by', $userId)
        ->get();

        $transactionAddUserChartData = [];
        $transactionOrderPlacedChartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $transactionAddUserChartData[] = (float)($transactionRaw->where('month', $m)->where('title', 'Add User Days')->sum('total_amount'));
            $transactionOrderPlacedChartData[] = (float)($transactionRaw->where('month', $m)->where('title', 'Order Placed')->sum('total_amount'));
        }

        $secretyKey = 1234567890;
        $encryption = new \MrShan0\CryptoLib\CryptoLib();
        $plainText  = $encryption->encryptPlainTextWithRandomIV($userId, $secretyKey);

        $breadcrumb = [
            __('language.dashboard_menu') => ''
        ];

        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['qr_code'] = $plainText;
        $this->viewData['authUser'] = $authUser;

        $this->viewData['totalUsers'] = $totalUsers;
        $this->viewData['offlineUsers'] = $offlineUsers;
        $this->viewData['onlineUsers'] = $onlineUsers;
        $this->viewData['totalCoaches'] = $totalCoaches;

        $this->viewData['weeklyPulseLabels'] = $weeklyPulseLabels;
        $this->viewData['weeklyPulseAttendance'] = $weeklyPulseAttendance;
        $this->viewData['weeklyPulseRevenue'] = $weeklyPulseRevenue;
        $this->viewData['dailyAvgAttendance'] = $dailyAvgAttendance;
        $this->viewData['weeklyPeakAttendance'] = $weeklyPeakAttendance;
        $this->viewData['weeklyGrowthPct'] = $weeklyGrowthPct;

        $this->viewData['todayCounsellingCount'] = $todayCounsellingCount;
        $this->viewData['todayNewMemberships'] = $todayNewMemberships;
        $this->viewData['thisMonthNewMembers'] = $thisMonthNewMembers;
        $this->viewData['todayRenewalsDue'] = $todayRenewalsDue;
        $this->viewData['todayUrgentRenewals'] = $todayUrgentRenewals;
        $this->viewData['thisMonthBirthdayUsers'] = $thisMonthBirthdayUsers;
        $this->viewData['totalAlertsCount'] = $totalAlertsCount;

        $this->viewData['thisMonthShake'] = $thisMonthShake;
        $this->viewData['thisMonthRevenue'] = $thisMonthRevenue;
        $this->viewData['today'] = $todayDate;
        $this->viewData['todayAttendences'] = $todayAttendences;
        $this->viewData['today2Attendences'] = $today2Attendences;
        $this->viewData['todayCollected'] = $todayCollected;
        $this->viewData['todayCheckedIn'] = $todayCheckedIn;

        $this->viewData['actionQueueItems'] = $actionQueueItems;
        $this->viewData['recentActivities'] = $recentActivities;

        $this->viewData['top20Attendance'] = $top20Attendance;
        $this->viewData['least20Attendance'] = $least20Attendance;
        $this->viewData['totalDaysInMonth'] = $totalDaysTillToday;

        $this->viewData['paymentPendings'] = $paymentPendings;
        $this->viewData['membershipExpires'] = $membershipExpires;

        $this->viewData['totalShakeChartData'] = $totalShakeChartData;
        $this->viewData['userDemoChartData'] = $userDemoChartData;
        $this->viewData['userTrailChartData'] = $userTrailChartData;
        $this->viewData['userRegualrChartData'] = $userRegualrChartData;
        $this->viewData['transactionAddUserChartData'] = $transactionAddUserChartData;
        $this->viewData['transactionOrderPlacedChartData'] = $transactionOrderPlacedChartData;
        $this->viewData['year'] = $year;

        return view('nutrition-panel.dashboard.index')->with($this->viewData);
    }
}
