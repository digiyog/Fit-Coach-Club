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
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    '_status'  => false,
                    '_message' => 'Unauthenticated user.',
                ], 401);
            }

            if (empty($request['franchise_id'])) {
                return response()->json([
                    '_status'  => false,
                    '_message' => 'Franchise QR code or ID is required.',
                ], 200);
            }

            $secretyKey = 1234567890;
            $encryption = new \MrShan0\CryptoLib\CryptoLib();

            $rawFranchiseInput = trim((string)$request['franchise_id']);
            $plainText = '';

            // Attempt 1: Direct decrypt of ciphertext
            try {
                $plainText = $encryption->decryptCipherTextWithRandomIV($rawFranchiseInput, $secretyKey);
            } catch (\Throwable $e) {
                $plainText = '';
            }

            // Attempt 2: If spaces replaced '+' during HTTP transmission, restore '+' and retry decrypt
            if (empty($plainText) && strpos($rawFranchiseInput, ' ') !== false) {
                try {
                    $fixedInput = str_replace(' ', '+', $rawFranchiseInput);
                    $plainText = $encryption->decryptCipherTextWithRandomIV($fixedInput, $secretyKey);
                } catch (\Throwable $e) {
                    $plainText = '';
                }
            }

            // Attempt 3: If URL encoded, try urldecode
            if (empty($plainText) && strpos($rawFranchiseInput, '%') !== false) {
                try {
                    $decoded = urldecode($rawFranchiseInput);
                    $plainText = $encryption->decryptCipherTextWithRandomIV($decoded, $secretyKey);
                } catch (\Throwable $e) {
                    $plainText = '';
                }
            }

            // Attempt 4: UPI QR code parsing (e.g. upi://pay?pa=7728933011@ptsbi&pn=DAKSHA%20%20SANKHLA)
            if (empty($plainText) && (stripos($rawFranchiseInput, 'upi://') !== false || strpos($rawFranchiseInput, '@') !== false)) {
                try {
                    $parsedUrl = parse_url($rawFranchiseInput);
                    $queryString = $parsedUrl['query'] ?? (strpos($rawFranchiseInput, '?') !== false ? substr($rawFranchiseInput, strpos($rawFranchiseInput, '?') + 1) : $rawFranchiseInput);
                    parse_str($queryString, $upiParams);

                    $upiVpa = $upiParams['pa'] ?? '';
                    $upiPhone = '';
                    if (preg_match('/(\d{10})/', $upiVpa, $m)) {
                        $upiPhone = $m[1];
                    } elseif (preg_match('/(\d{10})/', $rawFranchiseInput, $m)) {
                        $upiPhone = $m[1];
                    }

                    if (!empty($upiPhone)) {
                        $matchedFranchise = User::where('role_type', 'franchise')
                            ->where(function ($q) use ($upiPhone) {
                                $q->where('mobile_number', 'LIKE', '%' . $upiPhone . '%');
                            })
                            ->first();
                        if ($matchedFranchise) {
                            $plainText = (string)$matchedFranchise->id;
                        }
                    }
                } catch (\Throwable $upiEx) {
                    \Log::warning('UPI QR parse failed: ' . $upiEx->getMessage());
                }
            }

            // Attempt 5: If raw numeric, check if it's a valid franchise ID
            if (empty($plainText) && is_numeric($rawFranchiseInput)) {
                $franchiseExists = User::where('id', (int)$rawFranchiseInput)->where('role_type', 'franchise')->exists();
                if ($franchiseExists) {
                    $plainText = (string)$rawFranchiseInput;
                }
            }

            // Verify franchise membership
            $isValidFranchise = false;
            if (!empty($plainText)) {
                $targetFranchiseId = (string)$plainText;

                if ($targetFranchiseId === (string)$user['created_by']) {
                    $isValidFranchise = true;
                } else {
                    // Check if the user's creator was created by this franchise (coach -> franchise hierarchy)
                    $creator = User::find($user['created_by']);
                    if ($creator && (string)$creator->created_by === $targetFranchiseId) {
                        $isValidFranchise = true;
                    }

                    // Check coach name association if available
                    if (!$isValidFranchise && !empty($user->coach_name)) {
                        $coach = User::where('name', $user->coach_name)->where('created_by', (int)$targetFranchiseId)->first();
                        if ($coach) {
                            $isValidFranchise = true;
                        }
                    }
                }
            }

            if (!$isValidFranchise) {
                return response()->json([
                    '_status'  => false,
                    '_message' => 'Invalid franchise QR code or you do not belong to this franchise.',
                ], 200);
            }

            $attendanceFranchiseId = !empty($plainText) ? (int)$plainText : (int)$user['created_by'];

            if ($user['days'] > 0) {
                $today = Carbon::today();
                $exists = Attendance::where('user_id', $user['id'])
                    ->where('type', 2)
                    ->whereDate('date', $today)
                    ->whereNull('deleted_at')
                    ->exists();

                if ($exists) {
                    return response()->json([
                        '_status'  => false,
                        '_message' => 'Attendance has already been marked for today.',
                    ], 200);
                }

                $attendance = Attendance::create([
                    'franchise_id'  => $attendanceFranchiseId,
                    'user_id'       => $user['id'],
                    'date'          => $today->toDateString(),
                    'type'          => 2
                ]);

                /** @var User $userModel */
                $userModel = User::find($user['id']);
                $newPendingDays = $userModel ? max(0, (int)($userModel->days ?? 0) - 1) : max(0, (int)($user['days'] ?? 0) - 1);
                if ($userModel) {
                    $userModel->days = $newPendingDays;
                    $userModel->save();
                }

                $data = [
                    'user_id'       => $user->id,
                    'date'          => date('Y-m-d'),
                    'remark'        => 'QR Attendance Add',
                    'days'          => 1,
                    'total_days'    => $newPendingDays,
                    'created_by'    => $attendanceFranchiseId ?: $user->id,
                ];

                AttendanceLogs::create($data);

                // Send Notification safely without crashing on missing sender
                try {
                    $senderData   = User::find($attendanceFranchiseId) ?? User::find($user['created_by']) ?? User::first();
                    $receiverData = User::find($user['id']);

                    $senderName   = $senderData && !empty($senderData->name) ? $senderData->name : 'Fit Coach Club';
                    $receiverName = $receiverData && !empty($receiverData->name) ? $receiverData->name : ($user->name ?: 'Member');
                    $senderId     = $senderData ? $senderData->id : 0;

                    $title = 'Attendance Marked ✅';
                    $notiMessage = $receiverName . ', Congratulations! Your Attendance is marked for today.';
                    $message = $receiverName . ', Congratulations! Your Attendance is marked for today.';
                    $notificationType = 5;

                    Notification::create([
                        'user_id'             => $receiverData->id,
                        'sender_id'           => $senderId,
                        'data_id'             => '',
                        'notification_title'  => $title,
                        'notification_text'   => $notiMessage,
                        'sender_name'         => $senderName,
                        'receiver_name'       => $receiverName,
                        'notification_type'   => $notificationType,
                    ]);

                    if ($receiverData && !empty($receiverData->fcm_token) && function_exists('push_notification')) {
                        $platform  = $receiverData->device_os;
                        $fcm_token = $receiverData->fcm_token;
                        push_notification(
                            $receiverData->id,
                            $title,
                            $message,
                            $senderId,
                            $notificationType,
                            $fcm_token,
                            '',
                            $senderName,
                            $receiverName,
                            $platform
                        );
                    }
                } catch (\Throwable $notiEx) {
                    \Log::warning('Attendance notification sending failed: ' . $notiEx->getMessage());
                }

                return response()->json([
                    '_status'  => true,
                    '_message' => 'Your attendance has been marked successfully.',
                    '_data'    => [
                        'attendance_id' => $attendance->id,
                        'pending_days'  => $newPendingDays,
                        'date'          => $today->toDateString(),
                    ]
                ], 200);
            } else {
                return response()->json([
                    '_status'  => false,
                    '_message' => 'Not sufficient days to mark attendance.',
                ], 200);
            }
        } catch (\Throwable $e) {
            \Log::error('API Attendance add error: ' . $e->getMessage(), [
                'trace'   => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'input'   => $request->all()
            ]);

            return response()->json([
                '_status'  => false,
                '_message' => config('app.debug') ? ('Failed to mark attendance: ' . $e->getMessage()) : 'Something went wrong. Please try again later.',
            ], 200);
        }
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
            \Log::error('Attendence updateWeight Error: ' . $e->getMessage());
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
            \Log::error('Attendence updateGoal Error: ' . $e->getMessage());
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
            \Log::error('Attendence updateWeightImage Error: ' . $e->getMessage());
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
            \Log::error('Attendence absent Error: ' . $e->getMessage());
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
