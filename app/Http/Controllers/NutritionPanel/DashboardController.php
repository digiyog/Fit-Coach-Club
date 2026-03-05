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

    /**
      Home Page
    **/
    public function index(Request $request)
    {
        $data = array(
            'pageTitle'             => 'Dashboard',
            'pageDescrption'        => 'Dashboard',
            'services'              => $services
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
        // Get Users
        $authUser = auth()->user();
        //----------

        // $today = Carbon::today();

        // // Users range 188 to 238
        // $userIds = range(188, 238);

        // foreach ($userIds as $userId) {

        //     $exists = Attendance::where('user_id', $userId)->whereDate('date', $today)->exists();
        //     $type = rand(1, 10) == 1 ? 1 : 2;

        //     if (!$exists) {
        //         Attendance::create([
        //             'user_id' => $userId,
        //             'date'    => $today,
        //             'weight'  => rand(30, 70),
        //             'type'    => $type, // 10% chance Absent
        //         ]);

        //         $user           = User::where('id', $userId)->first();

        //         if($type == 2 && $user['days'] > 1){
        //             $attendenceLogs = AttendanceLogs::where('user_id',$user->id)->orderBy('id','DESC')->first();

        //             if ($attendanceLogs) {
        //                 $data = [
        //                     'user_id'       => $user->id,
        //                     'date'          => date('Y-m-d'),
        //                     'remark'        => 'QR Attendance Add',
        //                     'days'          => 1,
        //                     'total_days'    => 1,
        //                     'created_by'    => $authUser->id,
        //                 ];
                        
        //                 AttendanceLogs::create($data);
        //             } else {
        //                 $data = [
        //                     'user_id'       => $user->id,
        //                     'date'          => date('Y-m-d'),
        //                     'remark'        => 'QR Attendance Add',
        //                     'days'          => 1,
        //                     'total_days'    => $attendenceLogs['total_days'] - 1,
        //                     'created_by'    => $authUser->id,
        //                 ];
                        
        //                 AttendanceLogs::create($data);
        //             }

        //             User::where('id', $userId)->decrement('days', 1);
        //         }
        //     } else {
        //     }
        // }

        if($request->year_filter != ''){
            $year = $request->year_filter;
        } else {
            $year = date('Y');
        }

        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = Carbon::create($year, $m, 1)->format('M y');
        }

        // This Month Total Shakes
        $thisMonthTotalShakes = Attendance::select(
            DB::raw('COUNT(id) as total'),
            DB::raw('MONTH(created_at) as month')
        )
        ->whereYear('created_at', $year)
        ->where('type', 2)
        ->where('franchise_id', $authUser->id)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->get();

        $totalShakeChartData = [];

        for ($m = 1; $m <= 12; $m++) {
            $totalShakeChartData[] = $thisMonthTotalShakes->where('month', $m)->sum('total');
        }


        // Users Chart Data
        $usersRaw = User::select(
            DB::raw('COUNT(id) as total'),
            DB::raw('MONTH(created_at) as month'),
            'user_type'
        )
        ->whereYear('created_at', $year)
        ->groupBy('month', 'user_type')
        ->where('user_type','!=','')
        ->where('created_by', $authUser['id'])
        ->get();

        $userDemoChartData = [];
        $userTrailChartData = [];
        $userRegualrChartData = [];

        for ($m = 1; $m <= 12; $m++) {
            $userDemoChartData[] = $usersRaw->where('month', $m)->where('user_type', 'Demo User')->sum('total');
            $userTrailChartData[] = $usersRaw->where('month', $m)->where('user_type', '3 Days Trial')->sum('total');
            $userRegualrChartData[] = $usersRaw->where('month', $m)->where('user_type', 'Regular User')->sum('total');
        }

        // Transaction Chart
        $transactionRaw = Transaction::select(
            DB::raw('SUM(total_amount) as total_amount'),
            DB::raw('MONTH(created_at) as month'),
            'title'
        )
        ->whereYear('created_at', $year)
        ->whereIn('title', ['Add User Days', 'Order Placed'])
        // ->where('payment_status', 1)
        ->groupBy('month', 'title')
        ->where('created_by', $authUser['id'])
        ->get();

        $transactionAddUserChartData = [];
        $transactionOrderPlacedChartData = [];

        for ($m = 1; $m <= 12; $m++) {
            $transactionAddUserChartData[] = $transactionRaw->where('month', $m)->where('title', 'Add User Days')->sum('total_amount');
            $transactionOrderPlacedChartData[] = $transactionRaw->where('month', $m)->where('title', 'Order Placed')->sum('total_amount');
        }

        $totalUsers     = User::where('role_id', 3)->where('created_by', $authUser['id'])->get()->count();
        $thisMonthShake = Attendance::where('franchise_id', $authUser->id)->where('type', 2)->whereMonth('date', now()->month)->whereYear('date', now()->year)->count();

        $offlineUsers     = User::where('role_id', 3)->where('user_state', 'Offline')->where('created_by', $authUser['id'])->get()->count();
        $onlineUsers     = User::where('role_id', 3)->where('user_state', 'Online')->where('created_by', $authUser['id'])->get()->count();

        $thisMonthUsers     = User::where('role_id', 3)->where('created_by', $authUser['id'])->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->get()->count();

        $todayAttendences = Attendance::where('franchise_id', $authUser->id)
        ->leftJoin('users', function($join){
            $join->on('attendances.user_id', '=', 'users.id');
        })
        ->where('type', 2)->where('date', date('Y-m-d'))->get();

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
        ->where('attendances.date', date('Y-m-d'))
        ->groupBy('attendances.user_id')
        ->having('total_attendance', '>', 1)
        ->get();

        $month = now()->month;
        $year  = now()->year;

        // Agar current month hai → aaj tak
        if ($month == now()->month && $year == now()->year) {
            $totalDaysTillToday = now()->day;
        } else {
            // Purana month hai → full month
            $totalDaysTillToday = Carbon::createFromDate($year, $month)->daysInMonth;
        }

        // Top 20 January Attendance
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
        ->whereYear('attendances.date', $year)
        ->groupBy(
            'attendances.user_id',
            'users.name',
            'users.coach_name'
        )
        ->orderByDesc('total_attendance')
        ->limit(20)
        ->get();

        // Least 20 January Attendance
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
        ->whereYear('attendances.date', $year)
        ->groupBy(
            'attendances.user_id',
            'users.name',
            'users.coach_name'
        )
        ->orderBy('total_attendance', 'asc')
        ->limit(20)
        ->get();

        // Pending Payments
        $paymentPendings = User::select('users.id', 'users.user_type', 'users.user_state', 'users.name', 'users.email' ,'users.mobile_number', 'users.coach_name', 'users.meal_type_id', 'users.product_type_id', 'users.days', 'users.due_amount', 'users.status', 'users.created_at')
        ->where("users.role_type", 'user')->where("users.created_by", $authUser->id)->where('due_amount','>', 0)->orderBy('due_amount', 'DESC')->get();

        // Customer whose Membership Expire Soon
        $membershipExpires = User::select('users.id', 'users.user_type', 'users.user_state', 'users.name', 'users.email' ,'users.mobile_number', 'users.coach_name', 'users.meal_type_id', 'users.product_type_id', 'users.days', 'users.due_amount', 'users.status', 'users.created_at')
        ->where("users.role_type", 'user')->where("users.created_by", $authUser->id)->where('days','<=', 10)->orderBy('id', 'DESC')->get();

        $thisMonthBirthdayUsers = User::where('role_id', 3)->where('created_by', $authUser['id'])->whereDay('date_of_birth', now()->day)->whereMonth('date_of_birth', now()->month)->get();

        $secretyKey = 1234567890;
        $encryption = new \MrShan0\CryptoLib\CryptoLib();
        $plainText  = $encryption->encryptPlainTextWithRandomIV($authUser['id'], $secretyKey);

        $breadcrumb = [
            __('language.dashboard_menu') => ''
        ];

        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['qr_code'] = $plainText;
        $this->viewData['totalUsers'] = $totalUsers;
        $this->viewData['thisMonthShake'] = $thisMonthShake;
        $this->viewData['offlineUsers'] = $offlineUsers;
        $this->viewData['onlineUsers'] = $onlineUsers;
        $this->viewData['thisMonthUsers'] = $thisMonthUsers;
        $this->viewData['todayAttendences'] = $todayAttendences;
        $this->viewData['today2Attendences'] = $today2Attendences;
        $this->viewData['top20Attendance'] = $top20Attendance;
        $this->viewData['least20Attendance'] = $least20Attendance;
        $this->viewData['totalDaysInMonth'] = $totalDaysTillToday;
        $this->viewData['paymentPendings'] = $paymentPendings;
        $this->viewData['membershipExpires'] = $membershipExpires;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['totalShakeChartData'] = $totalShakeChartData;
        $this->viewData['userDemoChartData'] = $userDemoChartData;
        $this->viewData['userTrailChartData'] = $userTrailChartData;
        $this->viewData['userRegualrChartData'] = $userRegualrChartData;
        $this->viewData['transactionAddUserChartData'] = $transactionAddUserChartData;
        $this->viewData['transactionOrderPlacedChartData'] = $transactionOrderPlacedChartData;
        $this->viewData['thisMonthBirthdayUsers'] = $thisMonthBirthdayUsers;

        return view('nutrition-panel.dashboard.index')->with($this->viewData);
    }

}
