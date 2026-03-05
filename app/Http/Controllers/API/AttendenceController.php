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
use App\Models\Notification;

class AttendenceController extends Controller
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
     * Add Attendance.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function add(Request $request)
    {
        $user = Auth::user();

        $secretyKey = 1234567890;
        $encryption = new \MrShan0\CryptoLib\CryptoLib();
        $plainText  = $encryption->decryptCipherTextWithRandomIV($request['franchise_id'], $secretyKey);

        if($plainText != $user['created_by']){
            $response = [
                '_status'  => false,
                '_message' => 'Something went wrong. Please try again later.',
            ];

            return response()->json($response, 200);
        }

        if($user['days'] > 0){
            $today = Carbon::today();
            $exists = Attendance::where('user_id', $user['id'])->where('type',2)->whereDate('date', $today)->exists();

            if($exists){
                $response = [
                    '_status'  => false,
                    '_message' => 'Attendance has already been marked for today.',
                ];
            } else {
                Attendance::create([
                    'franchise_id'  => $user['created_by'],
                    'user_id'       => $user['id'],
                    'date'          => $today,
                    'type'          => 2
                ]);

                $attendenceLogs = AttendanceLogs::where('user_id',$user->id)->orderBy('id','DESC')->count();

                if($attendanceLogs == 0) {
                    $data = [
                        'user_id'       => $user->id,
                        'date'          => date('Y-m-d'),
                        'remark'        => 'QR Attendance Add',
                        'days'          => 1,
                        'total_days'    => $user['days'] - 1,
                        'created_by'    => $user->id,
                    ];
                    
                    AttendanceLogs::create($data);
                } else {
                    $attendenceLogs = AttendanceLogs::where('user_id',$user->id)->orderBy('id','DESC')->first();
                    $data = [
                        'user_id'       => $user->id,
                        'date'          => date('Y-m-d'),
                        'remark'        => 'QR Attendance Add',
                        'days'          => 1,
                        'total_days'    => $attendenceLogs['total_days'] - 1,
                        'created_by'    => $user->id,
                    ];
                    
                    AttendanceLogs::create($data);
                }

                User::where('id', $user->id)->decrement('days', 1);

                // Send Notification
                $senderData   = User::find(0);
                $receiverData = User::find($user['id']);

                // Set usernames
                $senderData['username']     = $senderData['name'] == '' ? 'Anonymous User' : $senderData['name'];
                $receiverData['username']   = $receiverData['name'] == '' ? 'Anonymous User' : $receiverData['name'];

                // Notification content
                $title = 'Attendance Marked ✅';
                $notiMessage = $receiverData['username'].', Congratulations! Your Attendance is marked for today.';
                $message = $receiverData['username'].', Congratulations! Your Attendance is marked for today.';
                $notificationType = 5;

                Notification::create([
                    'user_id'             => $receiverData->id,
                    'sender_id'           => $senderData->id,
                    'data_id'             => '',
                    'notification_title'  => $title,
                    'notification_text'   => $notiMessage,
                    'sender_name'         => $senderData['name'],
                    'receiver_name'       => $receiverData['name'],
                    'notification_type'   => $notificationType,
                ]);

                $user_id                = $receiverData->id;
                $notification_title     = $title;
                $notification_text      = $message;
                $sender_id              = $senderData->id;
                $notification_type      = $notificationType;
                $platform               = $receiverData->device_os;
                $fcm_token              = $receiverData->fcm_token;
                $data_id                = '';
                $sender_name            = $senderData['name'];
                $receiver_name          = $receiverData['name'];

                push_notification($user_id, $notification_title, $notification_text, $sender_id, $notification_type, $fcm_token, $data_id, $sender_name, $receiver_name, $platform);
                //---------

                $response = [
                    '_status'  => true,
                    '_message' => 'Your attendance has been marked successfully.',
                ];
            }
        } else {
            $response = [
                '_status'  => false,
                '_message' => 'Not sufficient days to mark attendance.',
            ];
        }
        //-------------

        return response()->json($response, 200);

    }

    /**
     * Check Attendance.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function checkAttendence(Request $request)
    {
        $user   = Auth::user();
        $today  = Carbon::today();
        $exists = Attendance::where('user_id', $user['id'])->where('type',2)->whereDate('date', $today)->exists();

        if($exists){
            $response = [
                '_status'  => true,
                '_message' => 'Attendance has already been marked for today.',
                '_image_path' => env('AWS_CloudFront_URL').'/'.config('constants.weights.image_path'),
                '_data'    => Attendance::where('user_id', $user['id'])->where('type',2)->whereDate('date', $today)->first()
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => 'Attendance has not been marked for today.',
                '_data'    => ''
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Update Weight.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function updateWeight(Request $request)
    {
        $user = Auth::user();

        // Update Weight
        try {
            $updateWeight = Attendance::updateOrCreate([
                'id'  => $request->attendence_id,
            ], [
                'weight' => $request['weight'],
            ]);

            if ($request->file('weight_image')) {
                $file = $this->uploadImage($request->file('weight_image'), config('constants.weights.image_path'));
                $weightImage = $file['_data'];

                $updateWeight = Attendance::updateOrCreate([
                    'id'  => $request->attendence_id,
                ], [
                    'weight_image' => $weightImage,
                ]);
            }
        } catch (\Exception $e) {
            $updateWeight = null;
        }
        //-----------------------

        // Set response
        if (!empty($updateWeight)) {
            $response = [
                '_status'  => true,
                '_message' => 'Your weight has been updated successfully.',
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => 'We were unable to update your weight. Please try again.',
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Update Goal.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function updateGoal(Request $request)
    {
        $user = Auth::user();

        // Update Goal
        try {
            $updateGoal = Attendance::updateOrCreate([
                'id'  => $request->attendence_id,
            ], [
                'weight_goal' => $request['weight_goal'],
            ]);
        } catch (\Exception $e) {
            $updateGoal = null;
        }
        //-----------------------

        // Set response
        if (!empty($updateGoal)) {
            $response = [
                '_status'  => true,
                '_message' => 'Your goal has been updated successfully.',
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => 'We were unable to update your goal. Please try again.',
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Update Weight Image.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function updateWeightImage(Request $request)
    {
        $user = Auth::user();

        // Update Weight Image
        try {
            if ($request->file('weight_image')) {
                $file = $this->uploadImage($request->file('weight_image'), config('constants.weights.image_path'));
                $weightImage = $file['_data'];

                $updateWeightImage = Attendance::updateOrCreate([
                    'id'  => $request->attendence_id,
                ], [
                    'weight_image' => $weightImage,
                ]);
            }
        } catch (\Exception $e) {
            $updateWeightImage = null;
        }
        //-----------------------

        // Set response
        if (!empty($updateWeightImage)) {
            $response = [
                '_status'  => true,
                '_message' => 'Your weight image has been updated successfully.',
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => 'We were unable to update your weight image. Please try again.',
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Viw Attendance.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function view(Request $request)
    {
        $user = Auth::user();

        // View Attendance
        try {
            $year  = $request->year ?? date('Y');
            $month = $request->month ?? date('m');

            if ($request->month) {
                $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
                $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            } else {
                $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
                $endDate   = Carbon::createFromDate($year, 12, 31)->endOfYear();
            }

            $attendances = Attendance::select(
                'attendances.id as attendance_id',
                'users.id',
                'users.name',
                'attendances.weight',
                'attendances.date',
                'attendances.type',
                'attendances.created_at'
            )
            ->leftJoin('users', 'attendances.user_id', '=', 'users.id')
            ->where('users.role_type', 'user')
            ->where('attendances.type', 2)
            ->where('attendances.user_id', $user->id)
            ->whereBetween('attendances.date', [$startDate, $endDate])
            ->orderBy('attendances.date', 'ASC')
            ->get();

            $presentDates = $attendances->pluck('date')->map(function ($d) {
                return Carbon::parse($d)->format('Y-m-d');
            })->toArray();

            $joinDate = Carbon::parse($user->created_at)->startOfDay();
            $today    = Carbon::today();

            // FINAL START = join date or month start (whichever is later)
            $finalStart = $joinDate->gt($startDate) ? $joinDate : $startDate;

            // ❗ FINAL END = endDate OR today (jo chhota ho)
            $finalEnd = $endDate->gt($today) ? $today : $endDate;

            $allDates = [];

            for ($date = $finalStart->copy(); $date->lte($finalEnd); $date->addDay()) {
                $allDates[] = $date->format('Y-m-d');
            }

            $absentDates = array_values(array_diff($allDates, $presentDates));

        } catch (\Exception $e) {
            $attendances = null;
        }
        //-----------------------

        // Set response
        // if ($attendances[0]) {
            $response = [
                '_status'  => true,
                '_message' => 'Attendance data fetched successfully.',
                '_data'    => [
                    'year'          => $year,
                    'month'         => $month,
                    'present_dates' => $presentDates,
                    'absent_dates'  => $absentDates,
                ]
            ];
        // } else {
        //     $response = [
        //         '_status'  => false,
        //         '_message' => 'No record found.',
        //     ];
        // }

        return response()->json($response, 200);
    }

}
