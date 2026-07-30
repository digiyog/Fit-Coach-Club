<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Traits\UploadImage;

use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceLogs;
use App\Models\MealType;

class DashboardController extends Controller
{
    use UploadImage;

    /**
     * Create an controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Viw Attendance.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // View Attendance
        try {
            $year  = $request->year  ?? date('Y');
            $month = $request->month ?? date('m');
            $days  = $request->days  ?? null; // 7, 15, 30

            $monthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $monthEnd   = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $today = Carbon::today();

            // 🔹 Default: full month
            $startDate = $monthStart;
            $endDate   = $monthEnd->gt($today) ? $today : $monthEnd;

            // 🔹 If days provided → last N days of THAT month
            if ($days) {

                // Month ka effective end (today se aage nahi)
                $effectiveEnd = $monthEnd->gt($today) ? $today : $monthEnd;

                $calculatedStart = $effectiveEnd->copy()->subDays($days - 1);

                // Month ke bahar na jaaye
                $startDate = $calculatedStart->lt($monthStart)
                    ? $monthStart
                    : $calculatedStart;

                $endDate = $effectiveEnd;
            }

            $attendances = Attendance::leftJoin('users', 'attendances.user_id', '=', 'users.id')
                ->where('users.role_type', 'user')
                ->where('attendances.type', 2)
                ->where('attendances.weight', '!=', '')
                ->where('attendances.user_id', $user->id)
                ->whereBetween('attendances.date', [$startDate, $endDate])
                ->orderBy('attendances.date', 'ASC')
                ->select(
                    'attendances.date',
                    'attendances.weight'
                )
                ->get();

        } catch (\Exception $e) {
            \Log::error('Dashboard attendances Error: ' . $e->getMessage());
            $attendances = null;
        }
        //-----------------------

        // Set response
        if ($attendances[0]) {
            $response = [
                '_status'  => true,
                '_message' => 'Attendance data fetched successfully.',
                '_data'    => $attendances
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => 'No record found.',
            ];
        }

        return response()->json($response, 200);
    }

    public function weight(Request $request)
    {
        $user = Auth::user();

        $mealType = MealType::where('id', $user['meal_type_id'])->select('id', 'name')->first();

        $firstRecord = Attendance::select('attendances.id as attendance_id', 'attendances.weight', 'attendances.date', 'attendances.created_at')
            ->where('weight','!=','')
            ->where('type', 2)->where('user_id', $user['id'])
            ->orderBy('attendances.id','ASC')->first();

        $lastRecord = Attendance::select('attendances.id as attendance_id', 'attendances.weight', 'attendances.weight_goal', 'attendances.date', 'attendances.created_at')
            ->where('weight','!=','')
            ->where('type', 2)->where('user_id', $user['id'])
            ->orderBy('attendances.id','DESC')->first();

        $weightGoal = Attendance::select('attendances.id as attendance_id', 'attendances.weight', 'attendances.weight_goal', 'attendances.date', 'attendances.created_at')
            ->where('attendances.weight_goal','!=','')
            ->where('type', 2)->where('user_id', $user['id'])
            ->orderBy('attendances.id','DESC')->first();

        $diifference = ($lastRecord['weight'] ?? 0) - ($user['current_weight'] ?? 0);

        if($diifference == 0){
            $title = 'Total Weight Achieved Through Our App : ';
        } else if($diifference > 0) {
            $title = 'Total Weight Gain Achieved Through Our App : ';
        } else {
            $title = 'Total Weight Loss Achieved Through Our App : ';
        }

        $response = [
            '_status'  => true,
            '_message' => 'Data fetched successfully.',
            '_tagline' => $title,
            'days' => $user['days'],
            'mealType'    => $mealType,
            'overall_weight_difference' => $diifference,
            'current_weight' => $lastRecord['weight'] ?? '0',
            'goal_weight' => $user['weight_goal'] ?? '0'
        ];

        return response()->json($response, 200);
    }

}
